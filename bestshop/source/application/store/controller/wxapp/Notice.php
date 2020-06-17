<?php

namespace app\store\controller\wxapp;

use app\store\controller\Controller;
use app\store\model\WxappNotice as WxappNoticeModel;

/**
 * 公告
 * Class Notice
 * @package app\store\controller\wxapp
 */
class Notice extends Controller
{
    /**
     * 公告列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new WxappNoticeModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 添加公告
     * @return array|mixed
     */
    public function add()
    {
        $model = new WxappNoticeModel;
        if (!$this->request->isAjax()) {
            return $this->fetch('add');
        }
        // 新增记录
        if ($model->add($this->postData('notice'))) {
            return $this->renderSuccess('添加成功', url('wxapp.notice/index'));
        }
        $error = $model->getError() ?: '添加失败';
        return $this->renderError($error);
    }

    /**
     * 更新公告
     * @param $notice_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($notice_id)
    {
        // 详情
        $model = WxappNoticeModel::detail($notice_id);
        if (!$this->request->isAjax()) {
            return $this->fetch('edit', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('notice'))) {
            return $this->renderSuccess('更新成功', url('wxapp.notice/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }

    /**
     * 删除公告
     * @param $notice_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($notice_id)
    {
        // 帮助详情
        $model = WxappNoticeModel::detail($notice_id);
        if (!$model->remove()) {
            $error = $model->getError() ?: '删除失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('删除成功');
    }

}
