<?php

namespace app\common\model;

/**
 * 收货地址
 */
class UserAddress extends BaseModel
{
    protected $name = 'user_address';

    /**
     * 追加region字段
     */
//    protected $append = ['region'];

    /**
     * 根据地区id获取地区名称
     */
//    public function getRegionAttr($value, $data)
//    {
//        return [
//            'province' => Region::getNameById($data['province_id']),
//            'city' => Region::getNameById($data['city_id']),
//            'region' => Region::getNameById($data['region_id']),
//        ];
//    }

}
