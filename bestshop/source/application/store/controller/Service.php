<?php

namespace app\store\controller;

use app\store\model\Order as OrderModel;
use app\store\model\Service as ServiceModel;
use think\Db;
//use think\Request;
//use think\view;

/**
 * 预约订单管理
 * Class Service
 * @package app\store\controller
 */
class Service extends Controller
{
    /**
     * 全部订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function all_list()
    {
        return $this->getList('全部预约列表');
    }


    /**
     * 待接单预约列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function order_list()
    {
        return $this->getList('待接单预约列表', ['service_status' => 10]);
    }

    /**
     * 已取消预约列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function cancel_list()
    {
        return $this->getList('已取消预约列表', ['service_status' => 11]);
    }



    /**
     * 代交单预约列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function take_list()
    {
        return $this->getList('待交单预约列表', ['service_status' => 20]);
    }

    /**
     * 已完成预约列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function finish_list()
    {
        return $this->getList('已完成预约列表', ['service_status' => 30]);
    }



    /**
     * 接单
     * @param $service_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function taking($service_id)
    {
        $take_time=time();
        $info = Db::table('joo_service_order')
            ->field('service_status')
            ->where('service_id','=',$service_id)
            ->find();
        if (!$info==10) {
            return $this->renderError('操作失败');
        }else{
            $info = Db::table('joo_service_order')
                ->where('service_id','=',$service_id)
                ->update(['service_status'=>20,'take_time' => $take_time]);
            if($info==1){
                return $this->renderSuccess('操作成功');
            } else{
                return $this->renderError('操作失败');

            }
        }
    }


    /**
     * 交单
     * @param $service_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function finish($service_id)
    {
        $finish_time=time();
        $info = Db::table('joo_service_order')
            ->field('service_status')
            ->where('service_id','=',$service_id)
            ->find();
        if (!$info==20) {
            return $this->renderError('操作失败');
        }else{
            $info = Db::table('joo_service_order')
                ->where('service_id','=',$service_id)
                ->update(['service_status'=>30,'finish_time' => $finish_time]);
            if($info==1){
                return $this->renderSuccess('操作成功');
            } else{
                return $this->renderError('操作失败');

            }
        }
    }






    /**
     * 订单列表
     * @param $title
     * @param $filter
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getList($title, $filter = [])
    {
        $model = new ServiceModel;
        $list = $model->getList($filter);
        return $this->fetch('index', compact('title','list'));
    }















//    /**
//     * 订单详情
//     * @param $order_id
//     * @return mixed
//     * @throws \think\exception\DbException
//     */
    public function detail($service_id)
    {
        $detail = ServiceModel::detail($service_id);
        return $this->fetch('detail', compact('detail'));
    }

    /**
     * 接单
     * @param $service_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function takeOrder($service_id)
    {
        $model = ServiceModel::detail($service_id);
        if ($model->takeOrder($this->postData('service_order'))) {
            return $this->renderSuccess('接单成功');
        }
        $error = $model->getError() ?: '接单失败';
        return $this->renderError($error);
    }

}
