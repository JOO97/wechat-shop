<?php

namespace app\api\model;

use app\common\model\UserCollect as UserCollectModel;

/**
 * 用户收藏夹
 * Class UserCollect
 * @package app\common\model
 */
class UserCollect extends UserCollectModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
//        'create_time',
    ];


    /**
     * 获取收藏列表
     */
    public function getList($user_id)
    {
        return $this->with(['goods.spec','goods.image.file'])
            ->where('user_id','=',$user_id)
            ->order(['create_time' => 'desc'])
            ->select();
    }

    /**
     * 新增收藏
     */
    public function add($goods_id,$user_id)
    {
        $this->allowField(true)->save([
            'user_id' => $user_id,
//            'user_id' => $user['user_id'],
            'wxapp_id' => self::$wxapp_id,
            'goods_id' => $goods_id,
        ]);
        return true;
    }


    /**
 * 取消收藏
 */
    public function move($goods_id,$user_id)
    {
        return $this->where(['goods_id'=>$goods_id,'user_id'=>$user_id])->delete();
    }

    /**
     * 取消收藏
     */
    public function move2($collect_id)
    {
        return $this->where(['collect_id'=>$collect_id])->delete();
    }


    /**
     * 查找
     */
    public function check($goods_id,$user_id)
    {
       return $this->where(['goods_id'=>$goods_id,'user_id'=>$user_id])->find();
    }




}

