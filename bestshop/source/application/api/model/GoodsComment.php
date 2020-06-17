<?php

namespace app\api\model;

use app\common\model\GoodsComment as GoodsCommentModel;
use think\Request;

/**
 * 评论模型
 * Class Goods
 * @package app\api\model
 */
class GoodsComment extends GoodsCommentModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'is_delete',
        'wxapp_id',
        'update_time'
    ];



    /**获取商品评论
     */
    public function getList($goods_id,$type)
    {
        $filter = [];
        switch ($type) {
            case 'all':
                break;
            case 'five';
                $filter['pingfen'] = 5;
                break;
            case 'four';
                $filter['pingfen'] = 4;
                break;
            case 'three';
                $filter['pingfen'] = 3;
                break;
            case 'two';
                $filter['pingfen'] = 2;
                break;
            case 'one';
                $filter['pingfen'] = 1;
                break;
        }
        return $this->with(['user'])
            ->where('goods_id','=',$goods_id)
//            ->where('pingfen','=',$num)
            ->where($filter)
            ->order(['create_time' => 'desc'])->paginate(10, false, [
                'query' => Request::instance()->request()
            ]);
    }


    /**获取2条商品评论
     */
    public function getLimit($goods_id)
    {
        return $this->with(['user'])
            ->where('goods_id','=',$goods_id)
            ->order(['create_time' => 'desc'])
            ->limit(2)
            ->select();
    }


    /**获取商品评论总数
     * @param $goods_id
     *
     * @throws \think\exception\DbException
     */
    public function getNum($goods_id)
    {
        return $this->where('goods_id', '=', $goods_id)->count();
    }


    /**
     * 获取各类评论统计
     * @param $goods_id
     * @param string $type
     * @return int|string
     */
    public function getCount($goods_id, $type)
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'five';
                $filter['pingfen'] = 5;
                break;
            case 'four';
                $filter['pingfen'] = 4;
                break;
            case 'three';
                $filter['pingfen'] = 3;
                break;
            case 'two';
                $filter['pingfen'] = 2;
                break;
            case 'one';
                $filter['pingfen'] = 1;
                break;
        }
        return $this->where('goods_id', '=', $goods_id)
//            ->where('order_status', '<>', 20)
            ->where($filter)
            ->count();
    }




//    /**
//     * 获取商品的评论列表
//     * @param $goods_id
//     * @return null|\app\common\model\GoodsComment
//     * @throws \think\exception\DbException
//     */
//    public function getComment($goods_id)
//    {
//        return $this->with(['Goods', 'user'])
//            ->where($goods_id)
//            ->order(['create_time' => 'desc'])->paginate(10, false, [
//                'query' => Request::instance()->request()
//            ]);
//    }

}
