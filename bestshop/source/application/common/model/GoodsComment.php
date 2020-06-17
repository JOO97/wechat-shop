<?php

namespace app\common\model;
use think\Request;

/**
 * 评论模型
 * Class Comment
 * @package app\common\model
 */
class GoodsComment extends BaseModel
{
    protected $name = 'goods_comment';


    /**
     * 关联订单商品表
     * @return \think\model\relation\BelongsTo
     */
    public function Goods()
    {
        return $this->belongsTo('OrderGoods');
    }

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 获取商品的评论列表
     * @param $goods_id
     * @return null|static
     * @throws \think\exception\DbException
     */
//    public function getComment($goods_id)
//    {
//        return $this->where($goods_id)
//            ->order(['create_time' => 'desc'])->paginate(10, false, [
//                'query' => Request::instance()->request()
//            ]);
//    }

}
