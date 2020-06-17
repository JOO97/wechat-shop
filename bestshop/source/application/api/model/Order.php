<?php

namespace app\api\model;

use think\Db;
use app\common\model\Order as OrderModel;
use app\common\exception\BaseException;
use think\Request;

/**
 * 订单模型
 * Class Order
 * @package app\api\model
 */
class Order extends OrderModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'update_time'
    ];

    /**
     * 订单确认-立即购买
     * @param User $user
     * @param $goods_id
     * @param $goods_num
     * @param $goods_sku_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function getBuyNow($user, $goods_id, $goods_num, $goods_sku_id)
    {
        // 商品信息
        $goods = Goods::detail($goods_id)->hidden(['category', 'content', 'spec']);
        // 判断商品是否下架
        if ($goods['goods_status']['value'] != 10) {
            $this->setError('该商品不存在或已下架');
        }
        // 获取商品规格信息
        $goods['goods_sku'] = $goods->getGoodsSku($goods_sku_id);
        // 判断商品库存
        if ($goods_num > $goods['goods_sku']['stock_num']) {
            $this->setError('商品库存不足');
        }
        // 商品单价
        $goods['goods_price'] = $goods['goods_sku']['goods_price'];
        // 商品总价
        $goods['total_num'] = $goods_num;
        $goods['total_price'] = $totalPrice = bcmul($goods['goods_price'], $goods_num, 2);
        // 当前用户收货城市id
        $city = $user['address_default'] ? $user['address_default']['city'] : null;
        // 是否存在收货地址
        $exist_address = !$user['address']->isEmpty();
        // 验证收货地址是否在范围内
        $wxapp=new Wxapp;
        if (!$intraRegion = $wxapp->checkAddress($city)) {
            $exist_address && $this->setError('您的地址不在配送范围内');
        }
        return [
            'goods_list' => [$goods],               // 商品详情
            'order_total_num' => $goods_num,        // 商品总数量
            'order_total_price' => $totalPrice,    // 商品总金额
            'order_pay_price' => $totalPrice,  // 实际支付金额
//            'order_pay_price' => bcadd($totalPrice, $expressPrice, 2),  // 实际支付金额
            'address' => $user['address_default'],  // 默认地址
            'exist_address' => $exist_address,  // 是否存在收货地址
            'intra_region' => $intraRegion,    // 当前用户收货地址是否在范围内
            'has_error' => $this->hasError(),
            'error_msg' => $this->getError(),
        ];
    }



    /**
     * 订单确认-购物车
     * @param $user
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCart($user)
    {
        $model = new Cart($user['user_id']);
        return $model->getList($user);
    }

    /**
     * 新增订单
     * @param $user_id
     * @param $order
     * @return bool
     * @throws \Exception
     */
    public function add($user_id, $order)
    {
        if (empty($order['address'])) {
            $this->error = '请先选择收货地址';
            return false;
        }
        Db::startTrans();
        // 保存订单信息
        $this->save([
            'user_id' => $user_id,
            'wxapp_id' => self::$wxapp_id,
            'order_no' => $this->orderNo(),
            'total_price' => $order['order_total_price'],
            'pay_price' => $order['order_pay_price'],
        ]);
        // 订单商品列表
        $goodsList = [];
        // 更新商品库存 (下单减库存)
        $deductStockData = [];
        foreach ($order['goods_list'] as $goods) {
            $goodsList[] = [
                'user_id' => $user_id,
                'wxapp_id' => self::$wxapp_id,
                'goods_id' => $goods['goods_id'],
                'goods_name' => $goods['goods_name'],
                'image_id' => $goods['image'][0]['image_id'],
//                'deduct_stock_type' => $goods['deduct_stock_type'],
//                'spec_type' => $goods['spec_type'],
                'spec_sku_id' => $goods['goods_sku']['spec_sku_id'],
                'goods_spec_id' => $goods['goods_sku']['goods_spec_id'],
                'goods_no' => $goods['goods_sku']['goods_no'],
                'goods_price' => $goods['goods_sku']['goods_price'],
//                'line_price' => $goods['goods_sku']['line_price'],
//                'goods_weight' => $goods['goods_sku']['goods_weight'],
                'total_num' => $goods['total_num'],
                'total_price' => $goods['total_price'],
            ];

            // 下单减库存,增加销量
//            $goods['deduct_stock_type'] == 10 && $deductStockData[] = [
//                'goods_spec_id' => $goods['goods_sku']['goods_spec_id'],
//                'stock_num' => ['dec', $goods['total_num']],
//                'goods_sales' => ['inc', $goods['total_num']]
//            ];
            $deductStockData[] = [
                'goods_spec_id' => $goods['goods_sku']['goods_spec_id'],
                'stock_num' => ['dec', $goods['total_num']],
                'goods_sales' => ['inc', $goods['total_num']]
            ];

//            $sales_actual = Db::table('joo_goods_spec')
//                ->where('goods_id','=',$goods['goods_id'])
//                ->sum('goods_sales');
            //生成订单后更新商品表的销量
            Db::table('joo_goods')
                ->where('goods_id','=',$goods['goods_id'])
//                ->update(['sales_actual' => $sales_actual])
                ->setInc('sales_actual',$goods['total_num']);

        }
        // 保存到订单商品表
        $this->goods()->saveAll($goodsList);
        // 更新商品库存
        !empty($deductStockData) && (new GoodsSpec)->isUpdate()->saveAll($deductStockData);
        // 保存收货地址
        $this->address()->save([
            'user_id' => $user_id,
            'wxapp_id' => self::$wxapp_id,
            'name' => $order['address']['name'],
            'phone' => $order['address']['phone'],
//            'province_id' => $order['address']['province_id'],
//            'city_id' => $order['address']['city_id'],
//            'region_id' => $order['address']['region_id'],
            'province' => $order['address']['province'],
            'city' => $order['address']['city'],
            'region' => $order['address']['region'],
            'detail' => $order['address']['detail'],
        ]);
        Db::commit();
        return true;
    }

    /**
     * 用户订单列表
     * @param $user_id
     * @param string $type
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($user_id, $type = 'all')
    {
        // 条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = 10;
                break;
            case 'delivery';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 10;
                break;
            case 'received';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                break;
            case 'comment';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 20;
                $filter['comment_status'] = 10;
                break;
        }
        return $this->with(['goods.image'])
            ->where('user_id', '=', $user_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->order(['create_time' => 'desc'])
            ->paginate(6, false, [
                'query' => Request::instance()->request()
            ]);
//            ->select();
    }

    /**
     * 取消订单
     * @return bool|false|int
     * @throws \Exception
     */
    public function cancel()
    {
        if ($this['pay_status']['value'] == 20) {
            $this->error = '已付款订单不可取消';
            return false;
        }
        // 回退商品库存
        $this->backGoodsStock($this['goods']);
        return $this->save(['order_status' => 20]);
    }

    /**
     * 回退商品库存
     * @param $goodsList
     * @return array|false
     * @throws \Exception
     */
    private function backGoodsStock(&$goodsList)
    {
        $goodsSpecSave = [];
        foreach ($goodsList as $goods) {
            // 下单减库存
//            if ($goods['deduct_stock_type'] == 10) {
                $goodsSpecSave[] = [
                    'goods_spec_id' => $goods['goods_spec_id'],
                    'stock_num' => ['inc', $goods['total_num']]
                ];
//            }
        }
        // 更新商品规格库存
        return !empty($goodsSpecSave) && (new GoodsSpec)->isUpdate()->saveAll($goodsSpecSave);
    }

    /**
     * 确认收货
     * @return bool|false|int
     */
    public function receipt()
    {
        if ($this['delivery_status']['value'] == 10 || $this['receipt_status']['value'] == 20) {
            $this->error = '该订单不合法';
            return false;
        }
        return $this->save([
            'receipt_status' => 20,
            'receipt_time' => time(),
            'order_status' => 30
        ]);
    }


    /**
     * 支付订单-修改订单pay_status
     * @return bool|false|int
     */
    public function payStatus()
    {
        if ($this['pay_status']['value'] == 20) {
            $this->error = '非法操作';
            return false;
        }
        return $this->save([
            'pay_status' => 20,
            'pay_time' => time()
        ]);
    }

    /**
     * 获取订单总数
     * @param $user_id
     * @param string $type
     * @return int|string
     */
    public function getCount($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = 10;
                break;
            case 'received';//待收货
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                break;
            case 'delivery';//待发货
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 10;
                break;
            case 'comment';//待评价
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 20;
                $filter['comment_status'] = 10;
                break;
        }
        return $this->where('user_id', '=', $user_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->count();
    }

    /**
     * 订单详情
     * @param $order_id
     * @param null $user_id
     * @return null|static
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public static function getUserOrderDetail($order_id, $user_id)
    {
        if (!$order = self::get([
            'order_id' => $order_id,
            'user_id' => $user_id,
            'order_status' => ['<>', 20]
        ], ['goods' => ['image', 'goods'], 'address'])) {
            throw new BaseException(['msg' => '订单不存在']);
        }
        return $order;
    }

    /**
     * 判断商品库存不足 (未付款订单)
     * @param $goodsList
     * @return bool
     */
//    public function checkGoodsStatusFromOrder(&$goodsList)
//    {
//        foreach ($goodsList as $goods) {
//            // 判断商品是否下架
//            if ($goods['goods']['goods_status']['value'] != 10) {
//                $this->setError('商品 [' . $goods['goods_name'] . '] 已下架');
//                return false;
//            }
//            // 付款减库存
//            if ($goods['deduct_stock_type'] == 20 && $goods['spec']['stock_num'] < 1) {
//                $this->setError('很抱歉，商品 [' . $goods['goods_name'] . '] 库存不足');
//                return false;
//            }
//        }
//        return true;
//    }

    /**
     * 设置错误信息
     * @param $error
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }

    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->error);
    }

//    /**
//     * 支付订单-修改订单order_status
//     *
//     */
//    public function payStatus($order_id)
//    {
//        if ($this['service_status']['value'] == 11
//            || $this['service_status']['value'] == 20) {
//            $this->error = '该订单不合法';
//            return false;
//        }
//        return $this->save([
//            'pay_status' => 20,
//            'pay_time' => time(),
//        ]);
//    }

}
