<?php

namespace app\store\controller\wxapp;

use app\store\controller\Controller;
use app\store\model\WxappInfo as WxappInfoModel;

/**
 * 门店信息
 * Class Notice
 * @package app\store\controller\wxapp
 */
class Info extends Controller
{
    /**
     * 门店信息列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new WxappInfoModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

//
//    /**
//     * 更新
//     * @param $store_info_id
//     * @return array|mixed
//     * @throws \think\exception\DbException
//     */
//    public function edit($store_info_id)
//    {
//        // 详情页
//        $model = WxappInfoModel::detail($store_info_id);
//        if (!$this->request->isAjax()) {
//            return $this->fetch('edit', compact('model'));
//        }
//        // 更新记录
//        if ($model->edit($this->postData('info'))) {
//            return $this->renderSuccess('更新成功', url('wxapp.info/index'));
//        }
//        $error = $model->getError() ?: '更新失败';
//        return $this->renderError($error);
//    }


    /**
     * 添加门店
     * @return array|mixed
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('add');
        }
        $model = new WxappInfoModel;
        if ($model->add($this->postData('info'))) {
            return $this->renderSuccess('添加成功', url('wxapp.info/index'));
        }
        $error = $model->getError() ?: '添加失败';
        return $this->renderError($error);
    }


    /**
     * 编辑门店信息
     * @param $store_info_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($store_info_id)
    {
        // 商品详情
        $model = WxappInfoModel::detail($store_info_id);
        if (!$this->request->isAjax()) {
            return $this->fetch('edit', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('info'))) {
            return $this->renderSuccess('更新成功', url('wxapp.info/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }

    /**
     * 删除门店
     * @param $store_info_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($store_info_id)
    {
        $model = WxappInfoModel::get($store_info_id);
        if (!$model->remove()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }



}

