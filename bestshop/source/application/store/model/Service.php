<?php

namespace app\store\model;

use app\common\model\Service as ServiceModel;
//use think\Db;
use think\Request;

/**
 * 预约服务订单
 * Class Service
 * @package app\store\model
 */
class Service extends ServiceModel
{

    /**
     * 订单列表
     * @param $filter
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($filter)
    {
          return $this->where($filter)
              ->order(['create_time' => 'desc'])->paginate(10, false, [
              'query' => Request::instance()->request()
          ]);

    }



    /**
     * 接单
     * @param $data
     * @return bool|false|int
     */
    public function take($service_id)
    {
        if ($this['service_status']['value'] == 11
            || $this['service_status']['value'] == 20) {
            $this->error = '该订单不合法';
            return false;
        }
        return $this->save([
            'service_status' => 20,
            'take_time' => time(),
        ]);
    }

}
