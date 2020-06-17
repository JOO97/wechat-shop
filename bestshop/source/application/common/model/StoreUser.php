<?php

namespace app\common\model;

/**
 * 管理员
 * Class StoreUser
 * @package app\common\model
 */
class StoreUser extends BaseModel
{
    protected $name = 'store_user';

    /**
     * 关联小程序表
     */
    public function wxapp() {
        return $this->belongsTo('Wxapp');
    }



}
