<?php

namespace app\store\controller;

use app\store\model\GoodsComment as GoodsCommentModel;

/**
 * 订单管理
 * Class Comment
 * @package app\store\controller
 */
class Comment extends Controller
{
    /**
     * 全部订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        return $this->getList('全部评论');
    }

    /**
     * 订单列表
     * @param $title
     * @param $filter
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getList($title, $filter = [])
    {
        $model = new GoodsCommentModel;
        $list = $model->getList($filter);
        return $this->fetch('index', compact('title','list'));
    }

//    /**
//     * 订单详情
//     * @param $order_id
//     * @return mixed
//     * @throws \think\exception\DbException
//     */
//    public function detail($order_id)
//    {
//        $detail = CommentModel::detail($order_id);
//        return $this->fetch('detail', compact('detail'));
//    }





}
