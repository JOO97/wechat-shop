<?php

namespace app\api\controller;

use app\api\model\Cart as CartModel;

/**
 * 购物车
 * Class Cart
 * @package app\api\controller
 */
class Cart extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /* @var \app\api\model\Cart $model */
    private $model;

    /**
     * 初始化类
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();//获取用户信息
        $this->model = new CartModel($this->user['user_id']);
    }

    /**
     * 购物车列表
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        return $this->renderSuccess($this->model->getList($this->user));
    }

    /**
     * 加入购物车
     * @param $goods_id
     * @param $goods_num
     * @param $goods_sku_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function add($goods_id, $goods_num, $goods_sku_id)
    {
        //商品id 商品数量 商品规格id
        if (!$this->model->add($goods_id, $goods_num, $goods_sku_id)) {
            return $this->renderError($this->model->getError() ?: '加入购物车失败');
        }
        $total_num = $this->model->getTotalNum();
        return $this->renderSuccess(['cart_total_num' => $total_num], '加入购物车成功');
    }

    /**
     * 减少数量
     * @param $goods_id
     * @param $goods_sku_id
     * @return array
     */
    public function sub($goods_id, $goods_sku_id)
    {
        $this->model->sub($goods_id, $goods_sku_id);
        return $this->renderSuccess();
    }

    /**
     * 删除商品
     * @param $goods_id
     * @param $goods_sku_id
     * @return array
     */
    public function delete($goods_id, $goods_sku_id)
    {
        $this->model->delete($goods_id, $goods_sku_id);
        return $this->renderSuccess();
    }

}
