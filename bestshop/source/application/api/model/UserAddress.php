<?php

namespace app\api\model;

use app\common\model\Region;
use app\common\model\UserAddress as UserAddressModel;

/**
 * 地址
 * Class UserAddress
 * @package app\common\model
 */
class UserAddress extends UserAddressModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    /**
     * 获取地址列表
     */
    public function getList($user_id)
    {
        return self::all(compact('user_id'));
    }

    /**
     * 新增收货地址
     */
    public function add($user, $data)
    {
        // 添加收货地址
        $region = explode(',', $data['region']);
        $this->allowField(true)->save(array_merge([
            'user_id' => $user['user_id'],
            'wxapp_id' => self::$wxapp_id,
            'province' => $region[0],
            'city' => $region[1],
            'region' => $region[2]
        ], $data));
        // 若用户无默认地址，则将当前地址设为默认收货地址
        !$user['address_id'] && $user->save(['address_id' => $this->getLastInsID()]);
        return true;
    }

    /**
     * 编辑收货地址
     */
    public function edit($data)
    {
        $region = explode(',', $data['region']);
//        $province_id = Region::getIdByName($region[0], 1);
//        $city_id = Region::getIdByName($region[1], 2, $province_id);
//        $region_id = Region::getIdByName($region[2], 3, $city_id);
        $province=$region[0];
        $city=$region[1];
        $region=$region[2];
        return $this->allowField(true)
            ->save(array_merge(compact('province', 'city', 'region'), $data));
    }

    /**
     * 设为默认收货地址
     */
    public function setDefault($user)
    {
        // 设为默认地址
        return $user->save(['address_id' => $this['address_id']]);
    }

    /**
     * 删除收货地址
     */
    public function remove($user)
    {
        // 查询当前是否为默认地址
        $user['address_id'] == $this['address_id'] && $user->save(['address_id' => 0]);
        return $this->delete();
    }

    /**
     * 收货地址详情
     */
    public static function detail($user_id, $address_id)
    {
        return self::get(compact('user_id', 'address_id'));
//        return self::get($user_id,$address_id);
    }

}
