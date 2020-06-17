<?php

namespace app\common\model;

use think\Hook;

/**
 * 订单模型
 * Class ServiceOrderModel
 * @package app\common\model
 */
class Service extends BaseModel
{
    protected $name = 'service_order';
//    protected $updateTime = false;


    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }




}

