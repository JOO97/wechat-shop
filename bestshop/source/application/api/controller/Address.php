<?php

namespace app\api\controller;

use app\api\model\UserAddress;
use app\api\model\UserCollect as UserCollectModel;

/**
 * 地址 收藏夹
 */
class Address extends Controller
{
    /**
     * 收货地址列表
     */
    public function lists()
    {
        $user = $this->getUser();
        $model = new UserAddress;
        $list = $model->getList($user['user_id']);
        return $this->renderSuccess([
            'list' => $list,
            'default_id' => $user['address_id'],
        ]);
    }

    /**
     * 添加地址
     */
    public function add()
    {
        $model = new UserAddress;
        if ($model->add($this->getUser(), $this->request->post())) {
            return $this->renderSuccess([], '添加成功');
        }
        return $this->renderError('添加失败');
    }

    /**
     * 获取地址详情
     */
    public function detail($address_id)
    {
        $user = $this->getUser();
        $detail = UserAddress::detail($user['user_id'],$address_id);
        //地区
//        $region = array_values($detail['region']);
//        return $this->renderSuccess(compact('detail', 'region'));
        return $this->renderSuccess(compact('detail'));

    }

    /**
     * 编辑收货地址
     */
    public function edit($address_id)
    {
        $user = $this->getUser();
        $model = UserAddress::detail($user['user_id'], $address_id);
        if ($model->edit($this->request->post())) {
            return $this->renderSuccess([], '编辑成功');
        }
        return $this->renderError('编辑失败');
    }

    /**
     * 设为默认地址
     */
    public function setDefault($address_id) {
        $user = $this->getUser();
        $model = UserAddress::detail($user['user_id'], $address_id);
        if ($model->setDefault($user)) {
            return $this->renderSuccess([], '设置成功');
        }
        return $this->renderError('设置失败');
    }

    /**
     * 删除收货地址
     * @param $address_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function delete($address_id)
    {
        $user = $this->getUser();
        $model = UserAddress::detail($user['user_id'], $address_id);
        if ($model->remove($user)) {
            return $this->renderSuccess([], '删除成功');
        }
        return $this->renderError('删除失败');
    }


    /**
     * 添加收藏
     */
    public function collect($goods_id,$user_id)
    {
        $model = new UserCollectModel;
        if($model->check($goods_id,$user_id)){
            return $this->renderError('您已收藏过该商品');
        } else{
            if ($model->add($goods_id,$user_id)) {
                return $this->renderSuccess(['isCollect'=>true,'collect_id'=>$model['collect_id']], '收藏成功');
            } else{
                return $this->renderError('收藏失败');
            }
        }

    }

    /**
     * 取消收藏(在商品页面
     */
    public function cancel($goods_id,$user_id)
    {
        $model = new UserCollectModel;
        if ($model->move($goods_id,$user_id)) {
            return $this->renderSuccess(['isCollect'=>false], '取消收藏成功');
        }
        return $this->renderError('取消收藏失败');
    }


    /**
     * 取消收藏根据收藏id(在个人收藏页面)
     */
    public function cancel2($collect_id)
    {
        $model = new UserCollectModel;
        if ($model->move2($collect_id)) {
            return $this->renderSuccess( '取消收藏成功');
        }
        return $this->renderError('取消收藏失败');
    }



    /**
     * 查看是否收藏该商品
     */
    public function checking($goods_id,$user_id)
    {
        $model = new UserCollectModel;
        if ($model->check($goods_id,$user_id)) {
            return $this->renderSuccess(['isCollect'=>true]);
        } else{
           return $this->renderSuccess(['isCollect'=>false]);
        }
    }


    /**
     * 获取收藏列表
     */
    public function getCollect($user_id)
    {
        $model = new UserCollectModel;
        $list = $model->getList($user_id);
        return $this->renderSuccess(['list'=>$list]);
    }

}
