<?php

namespace app\api\model;

use app\common\exception\BaseException;
use app\common\model\Service as ServiceModel;
use think\Request;

/**
 * 预约
 * Class Goods
 * @package app\api\model
 */
class Service extends ServiceModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
//        'create_time',
//        'update_time'
    ];

    /**
     * 预约列表
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
            case 'order';
                $filter['service_status'] = 10;
                break;
            case 'cancel';
                $filter['service_status'] = 11;
                break;
            case 'take';
                $filter['service_status'] = 20;
                break;
            case 'finish';
                $filter['service_status'] = 30;
                break;
        }
        return $this->where('user_id', '=', $user_id)
            ->where('isdelete','=',10)
            ->where($filter)
            ->order(['create_time' => 'desc'])
            ->paginate(6, false, [
                'query' => Request::instance()->request()
            ]);
//            ->select();
    }


    /**
     * 查询预约订单
     */
    public function findService($user_id, $date)
    {
        // 订单数据类型
        return $this->where('user_id', '=', $user_id)
            ->where('isdelete','=',10)
            ->where('service_date','=',$date)
            ->order(['create_time' => 'desc'])
            ->select();
    }



    /**
     * 预约订单详情
     * @param $service_id
     * @param null $user_id
     * @return null|static
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public static function detail($service_id, $user_id)
    {
        if (!$service = self::get([
            'service_id' => $service_id,
            'user_id' => $user_id,
            'isdelete' => ['<>', 20]
        ])) {
            throw new BaseException(['msg' => '预约订单不存在']);
        }
        return $service;
    }



    /**
     * 删除预约订单
     */
    public function delete()
    {
        if ($this['service_status'] == 10 || $this['service_status'] == 20) {
            $this->error = '该订单不合法';
            return false;
        }
        return $this->save([
            'isdelete' => 20,
        ]);
    }



    /**
     * 取消预约订单
     */
    public function cancel()
    {
        if ($this['service_status'] == 11){
            $this->error = '该订单已取消';
            return false;
        } else if($this['service_status'] == 20 || $this['service_status'] == 30) {
            $this->error = '无法取消该订单';
            return false;
        }
        return $this->save([
            'service_status' => 11,
        ]);
    }

}
