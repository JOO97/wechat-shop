<?php

namespace app\api\controller;

use app\api\controller\Controller;
use app\api\model\GoodsComment as GoodsCommentModel;

/**
 * 评论页
 * Class Comment
 * @package app\api\controller\user
 */
class Comment extends Controller
{
    /**
     * 获取评论和各类评论统计
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($goods_id,$type)
    {
        // 当前用户信息
//        $userInfo = $this->getUser();
        // 获取各类订单数量
        $model = new GoodsCommentModel;
        $allList=$model->getList($goods_id,$type);
        $commentCount = [
            'all' => $model->getCount($goods_id, 'all'),
            'five' => $model->getCount($goods_id, 'five'),
            'four' => $model->getCount($goods_id, 'four'),
            'three' => $model->getCount($goods_id, 'three'),
            'two' => $model->getCount($goods_id, 'two'),
            'one' => $model->getCount($goods_id, 'one'),
        ];
        return $this->renderSuccess(compact('allList', 'commentCount'));
    }



    /**
     * 按类型获取评论
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function type($goods_id,$type)
    {
        $model = new GoodsCommentModel;
        $allList=$model->getList($goods_id,$type);
        if($allList){
            return $this->renderSuccess(compact('allList'));
        }else{
            return $this->renderError();
        }
    }



}
