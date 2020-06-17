<?php

namespace app\api\model;

use app\common\model\Wxapp as WxappModel;

/**
 * 微信小程序模型
 * Class Wxapp
 * @package app\api\model
 */
class Wxapp extends WxappModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
//        'wxapp_id',
        'app_name',
        'app_id',
        'app_secret',
        'mchid',
        'apikey',
        'create_time',
        'update_time'
    ];


    /**
     * 判断用户收货地址是否存在配送范围内
     * @param $city_id
     * @return bool
     */
    public function checkAddress($city)
    {
        $region=$this->field('region')->where('wxapp_id','=',10001)->find();
        $citys = explode(',', $region['region']);
        return in_array($city, $citys);
    }

}
