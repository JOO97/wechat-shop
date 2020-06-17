<?php

namespace app\common\model;

use think\Request;

/**
 * 收藏表
 * Class UserCollect
 * @package app\common\model
 */
class UserCollect extends BaseModel
{
    protected $name = 'user_collect';


    /**
     * 关联商品表
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo('Goods');
    }



    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

//    /**
//     * 获取商品详情
//     * @param $goods_id
//     * @return null|static
//     * @throws \think\exception\DbException
//     */
//    public static function detail($goods_id)
//    {
//        return self::get($goods_id, ['category', 'image.file', 'spec', 'spec_rel.spec', 'delivery.rule']);
//    }








}
