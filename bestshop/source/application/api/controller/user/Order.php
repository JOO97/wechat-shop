<?php

namespace app\api\controller\user;

use app\api\controller\Address;
use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\Wxapp as WxappModel;
use app\api\model\UserAddress;
use app\api\model\Service as ServiceModel;
use think\Db;

/**
 * 管理订单 预约订单管理 支付订单
 * Class Order
 * @package app\api\controller\user
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()//继承Controller中的方法
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 订单列表
     */
    public function lists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }


    /**
     * 订单详情信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        return $this->renderSuccess(['order' => $order]);
    }


    /**
     * 获取预约订单
     */
    public function serviceList($type)
    {
        $model = new ServiceModel;
        $list = $model->getList($this->user['user_id'], $type);
        return $this->renderSuccess(compact('list'));
    }



    /**
     * 查询预约订单
     */
    public function searchService($date)
    {
        $model = new ServiceModel;
        $list = $model->findService($this->user['user_id'], $date);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 取消预约订单
     */
    public function cancelService($service_id)
    {
        $model = new ServiceModel;
        $list = ServiceModel::detail($service_id, $this->user['user_id']);
        if ($list->cancel()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }


    /**
     * 删除预约订单
     */
    public function deleteService($service_id)
    {
        $model = new ServiceModel;
        $list = ServiceModel::detail($service_id, $this->user['user_id']);
        if ($list->delete()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }




    /**
     * 取消订单
     */
    public function cancel($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->cancel()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }

    /**
     * 确认收货
     */
    public function receipt($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->receipt()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }

    /**
     * 支付订单
     */
    public function pay($order_id)
    {
        // 订单详情
//        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        //修改支付状态
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->payStatus()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }



}
