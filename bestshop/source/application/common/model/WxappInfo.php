<?php

namespace app\common\model;

use think\Request;

/**
 * 店铺信息
 * Class WxappInfo
 * @package app\common\model
 */
class WxappInfo extends BaseModel
{
    protected $name = 'store_info';


    /**
     * 关联门店图片表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->hasMany('StoreImage')->order(['id' => 'asc']);
    }

    /**
     * 获取门店列表
     */
    public function getList()
    {
        $list = $this->field('*')->with(['image.file'])
            ->order('store_info_id','asc')
//            ->select();
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
        return $list;
    }


    /**
     * 门店详情
     * @param $store_info_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($store_info_id)
    {
        return self::get($store_info_id, ['image.file']);
    }





}
