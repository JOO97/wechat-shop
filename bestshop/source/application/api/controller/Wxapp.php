<?php

namespace app\api\controller;

use app\api\model\Wxapp as WxappModel;
use app\api\model\WxappNotice;
use app\api\model\WxappInfo as WxappInfoModel;
use think\Db;

/**
 * 小程序信息 公告信息 门店信息
 */
class Wxapp extends Controller
{
    /**
     * 获取小程序信息
     */
    public function base()
    {
        $wxapp = WxappModel::getWxappCache();
        return $this->renderSuccess(compact('wxapp'));
    }

    /**
     * 公告
     */
    public function notice()
    {
        $model = new WxappNotice;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

//    /**
//     * 店铺信息
//     * @return array
//     * @throws \think\db\exception\DataNotFoundException
//     * @throws \think\db\exception\ModelNotFoundException
//     * @throws \think\exception\DbException
//     */
//    public function storeInfo()
//    {
//        $info = Db::table('joo_store_info')
//             ->field('*')
//             ->where('store_info_id','=',1)
//             ->select();
//
//        return $this->renderSuccess(['storeInfo' => $info]);
//    }

    /**
     * 获取门店列表
     */
    public function lists()
    {
        $model = new WxappInfoModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }



    /**
     * 获取门店详情
     */
    public function detail($store_info_id)
    {
        $model = new WxappInfoModel;
        $list = $model->detail($store_info_id);
        return $this->renderSuccess(compact('list'));
    }



}
