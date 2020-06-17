<?php

namespace app\api\controller;

use app\api\model\WxappPage;
use app\api\model\Goods as GoodsModel;
use app\api\model\WxappNotice as WxappNoticeModel;

/**
 * 首页
 * Class Index
 * @package app\api\controller
 */
class Index extends Controller
{
    /**
     * 获取轮播图,新品推荐,热门商品
     * @return array
     * @throws \think\exception\DbException
     */
    public function page()
    {
        // 轮播图
        $items = WxappPage::detail();
//        $items = $wxappPage['page_data']['array']['items'];
        // 新品
        $model = new GoodsModel;
        $newest = $model->getNewList();
        // 热门商品
        $best = $model->getBestList();
        //店铺公告
        $model = new WxappNoticeModel;
        $notice = $model->getList();
        return $this->renderSuccess(compact('items', 'newest', 'best','notice'));
    }

}
