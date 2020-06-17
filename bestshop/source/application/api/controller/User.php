<?php

namespace app\api\controller;

use app\api\model\User as UserModel;

/**
 * 用户登录
 */
class User extends Controller
{
    /**
     * 用户登录
     */
    public function login()
    {
        $model = new UserModel;
        $user_id = $model->login($this->request->post());
        //获取token
        $token = $model->getToken();
        return $this->renderSuccess(compact('user_id', 'token'));
    }



}
