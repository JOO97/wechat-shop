<?php

namespace app\api\controller;

use app\api\model\Goods as GoodsModel;
use app\api\model\Cart as CartModel;
use app\api\model\GoodsComment as GoodsCommentModel;
use think\Db;

/**
 * 商品列表 商品详情
 * Class Goods
 * @package app\api\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表 comm
     * @param $category_id
     * @param $search
     * @param $sortType
     * @param $sortPrice
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($category_id, $search, $sortType, $sortPrice, $sortSales)
    {
        $model = new GoodsModel;
        $list = $model->getList(10, $category_id, $search, $sortType, $sortPrice,$sortSales);
        // 隐藏商品分类与详情
        !$list->isEmpty() && $list->hidden(['category', 'content']);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取商品详情 comm
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function detail($goods_id)
    {
        // 商品详情
        $detail = GoodsModel::detail($goods_id);
        if (!$detail || $detail['goods_status']['value'] != 10) {
            return $this->renderError('商品信息不存在或已下架');
        }
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 获取2条商品评论
     */
    public function getComment($goods_id)
    {
        $model = new GoodsCommentModel;
        $list = $model->getLimit($goods_id);
        return $this->renderSuccess([
            'list' => $list,
        ]);
//        $info=Db::table('joo_goods_comment')
//            ->alias("a")
//            ->join("joo_user b","a.user_id = b.user_id")
//            ->field("a.comment_id,a.goods_id,a.create_time,a.pingjia,a.pingfen,b.nickName,b.avatarUrl")
//            ->where('goods_id',$goods_id)
//            ->order('a.create_time desc')
//            ->limit(2)
//            ->select();
//        return $this->renderSuccess(compact('info'));
    }


    /**
     * 获取评论列表
     */
    public function commnetList($goods_id)
    {
        $model = new GoodsCommentModel;
        $list = $model->getList($goods_id);
        return $this->renderSuccess([
            'list' => $list,
        ]);
    }


    /**
     * 获取商品评论总数
     * @param $category_id
     * @param $search
     * @param $sortType
     * @param $sortPrice
     * @return array
     * @throws \think\exception\DbException
     */
    public function getCommentNum($goods_id)
    {
        $model = new GoodsCommentModel;
        $count = $model->getNum($goods_id);
        return $this->renderSuccess([
            'count' => $count,
        ]);
//        $info=Db::table('joo_goods_comment')
//            ->alias("a")
//            ->join("joo_user b","a.user_id = b.user_id")
//            ->where('goods_id',$goods_id)
//            ->order('a.create_time desc')
//            ->select();
//        $num=count($info);
//        return array("num"=>$num);
    }


    /**
     * 删除评论
     */
//    public function deleteComment($comment_id,$order_goods_id)
//    {
//        $info=Db::table('joo_goods_comment')
//            ->where('comment_id','=',$comment_id)
//            ->delete();
//        $info2=Db::table('joo_order_goods')
//            ->where('order_goods_id','=',$order_goods_id)
//            ->setField('comment_status',10);
//        if ($info===false && $info2===false){
//            return $this->renderError('操作失败');
//        } else{
//            return $this->renderSuccess();
//        }
//    }





}


