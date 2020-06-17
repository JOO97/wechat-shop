<?php

namespace app\common\model;

/**
 * 轮播图
 * Class WxappPage
 * @package app\common\model
 */
class WxappPage extends BaseModel
{
    protected $name = 'wxapp_page';

    /**
     * 图片
     * @return \think\model\relation\HasOne
     */
    public function image()
    {
        return $this->hasOne('uploadFile', 'file_id', 'image_id');
    }


    /**
     * 获取轮播图数据
     */
    public static function detail()
    {
        return self::all([],['image']);
    }



}
