<?php

namespace app\api\model;

use think\Cache;
use app\common\library\helper;

/**
 * 购物车管理
 * Class Cart
 * @package app\api\model
 */
class Cart
{
    /* @var string $error 错误信息 */
    public $error = '';

    /* @var int $user_id 用户id */
    private $user_id;

    /* @var array $cart 购物车列表 */
    private $cart = [];

    /* @var bool $clear 是否清空购物车 */
    private $clear = false;

    /**
     * 初始化类
     * Cart constructor.
     * @param $user_id
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->cart = Cache::get('cart_' . $this->user_id) ?: [];
    }

    /**
     * 购物车列表
     * @param \think\Model|\think\Collection $user
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($user)
    {
        // 商品列表
        $goodsList = [];
        $goodsIds = array_unique(array_column($this->cart, 'goods_id'));//获取商品id集
        foreach ((new Goods)->getListByIds($goodsIds) as $goods) {
            $goodsList[$goods['goods_id']] = $goods;//商品id：商品信息
        }
        // 收货地址城市
        $city = $user['address_default'] ? $user['address_default']['city'] : null;
        // 是否存在收货地址
        $exist_address = !$user['address']->isEmpty();
        // 商品是否在配送范围
        $intraRegion = true;


        // 购物车商品列表
        $cartList = [];
        foreach ($this->cart as $key => $cart) {
            // 判断商品不存在则自动删除
            if (!isset($goodsList[$cart['goods_id']])) {//商品列表中不存在购物车中的商品id
                $this->delete($cart['goods_id'], $cart['goods_sku_id']);
                continue;
            }
            $goods = clone $goodsList[$cart['goods_id']];
            // 商品规格信息
            $goods['goods_sku_id'] = $cart['goods_sku_id'];
            // 商品规格不存在则自动删除
            if (!$goods['goods_sku'] = $goods->getGoodsSku($cart['goods_sku_id'])) {
                $this->delete($cart['goods_id'], $cart['goods_sku_id']);
                continue;
            }
            // 判断商品是否下架
            if ($goods['goods_status']['value'] != 10) {
                $this->setError('[' . $goods['goods_name'] . '] 已下架');
            }
            // 判断库存
            if ($cart['goods_num'] > $goods['goods_sku']['stock_num']) {
                $this->setError('[' . $goods['goods_name'] . '] 库存不足');
            }
            // 单价
            $goods['goods_price'] = $goods['goods_sku']['goods_price'];
            // 商品总数
            $goods['total_num'] = $cart['goods_num'];
            // 商品总价
            $goods['total_price'] = $total_price = bcmul($goods['goods_price'], $cart['goods_num'], 2);
            // 验证收货地址是否在范围内
            $wxapp=new Wxapp;
            if (!$intraRegion = $wxapp->checkAddress($city)) {
                $exist_address && $this->setError("您的地址不在的配送范围内");
            }
            $cartList[] = $goods->hidden(['category', 'content', 'spec']);
        }
        // 总金额
        $orderTotalPrice = helper::getArrayColumnSum($cartList, 'total_price');
        return [
            'goods_list' => $cartList,                       // 商品列表
            'order_total_num' => $this->getTotalNum(),       // 总数量
            'order_total_price' => helper::number2($orderTotalPrice), // 总金额
            'order_pay_price' => helper::number2($orderTotalPrice),  // 实际支付金额
            'address' => $user['address_default'],  // 默认地址
            'exist_address' => $exist_address,      // 是否存在收货地址
            'intra_region' => $intraRegion,         // 验证收货地址是否在范围内
            'has_error' => $this->hasError(),
            'error_msg' => $this->getError(),
        ];
    }

    /**
     * 添加购物车
     * @param $goods_id
     * @param $goods_num
     * @param $goods_sku_id
     * @return bool
     * @throws \think\exception\DbException
     */
    public function add($goods_id, $goods_num, $goods_sku_id = 0)
    {
        // 购物车商品索引 商品id 规格id
        $index = $goods_id . '_' . $goods_sku_id;
        // 商品详情
        $goods = Goods::detail($goods_id);
        // 商品规格信息
        $goods['goods_sku'] = $goods->getGoodsSku($goods_sku_id);
        // 判断商品是否下架
        if ($goods['goods_status']['value'] != 10) {
            $this->setError('很抱歉，该商品已下架');
            return false;
        }
        // 判断商品库存
        $cartGoodsNum = $goods_num + (isset($this->cart[$index]) ? $this->cart[$index]['goods_num'] : 0);
        if ($cartGoodsNum > $goods['goods_sku']['stock_num']) {
            $this->setError('添加失败，商品库存不足');
            return false;
        }
        $create_time = time();//添加时间
        $data = compact('goods_id', 'goods_num', 'goods_sku_id', 'create_time');
        //该商品数据是否已存在
        if (empty($this->cart)) {//不存在
            $this->cart[$index] = $data;
            return true;
        }
        //存在，增加商品数量
        isset($this->cart[$index]) ? $this->cart[$index]['goods_num'] = $cartGoodsNum : $this->cart[$index] = $data;
        return true;
    }

    /**
     * 减少数量
     */
    public function sub($goods_id, $goods_sku_id)
    {
        $index = $goods_id . '_' . $goods_sku_id;
        $this->cart[$index]['goods_num'] > 1 && $this->cart[$index]['goods_num']--;
    }

    /**
     * 删除
     */
    public function delete($goods_id, $goods_sku_id)
    {
        $index = $goods_id . '_' . $goods_sku_id;
        unset($this->cart[$index]);
    }

    /**
     * 获取当前用户购物车商品总数量
     */
    public function getTotalNum()
    {
        return array_sum(array_column($this->cart, 'goods_num'));
    }

    /**
     * 析构方法
     * 将cart数据保存到缓存文件
     */
    public function __destruct()
    {
        $this->clear !== true && Cache::set('cart_' . $this->user_id, $this->cart, 3600 * 1);
    }

    /**
     * 清空购物车
     */
    public function clearAll()
    {
        $this->clear = true;
        Cache::rm('cart_' . $this->user_id);
    }

    /**
     * 设置错误信息
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }

    /**
     * 是否存在错误
     */
    private function hasError()
    {
        return !empty($this->error);
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->error;
    }

}
