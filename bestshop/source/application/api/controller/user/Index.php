<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\UserCollect as UserCollectModel;

/**
 * 个人主页
 * Class Index
 * @package app\api\controller\user
 */
class Index extends Controller
{
    /**
     * 获取当前用户信息
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        // 当前用户信息
        $userInfo = $this->getUser();
        // 获取各类订单数量
        $model = new OrderModel;
        $orderCount = [
            'payment' => $model->getCount($userInfo['user_id'], 'payment'),//待支付
            'delivery' => $model->getCount($userInfo['user_id'], 'delivery'),//待发货
            'received' => $model->getCount($userInfo['user_id'], 'received'),//待收货
            'comment' => $model->getCount($userInfo['user_id'], 'comment')//待评论
        ];
        return $this->renderSuccess(compact('userInfo', 'orderCount'));
    }





}
