<?php

namespace app\api\model;

use app\common\model\User as UserModel;
//use app\api\model\Wxapp;
use app\common\library\wechat\WxUser;
use app\common\exception\BaseException;
use think\Cache;
use think\Request;

/**
 * 用户
 */
class User extends UserModel
{
    private $token;

    /**
     * 隐藏字段
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    /**
     * 获取用户信息
     */
    public static function getUser($token)
    {
        return self::detail(['open_id' => Cache::get($token)['openid']]);
    }

    /**
     * 用户登录
     */
    public function login($post)
    {
        // 微信登录 使用code获取session_key
        $session = $this->wxlogin($post['code']);
        // 自动注册用户
        $userInfo = json_decode(htmlspecialchars_decode($post['user_info']), true);//将用户资料转换为数组
        $user_id = $this->register($session['openid'], $userInfo);//注册用户 openid、用户资料
        // 生成token (session3rd)
        $this->token = $this->token($session['openid']);
        // 记录缓存, 7天
        Cache::set($this->token, $session, 86400 * 7);
        return $user_id;
    }

    /**
     * 获取token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 微信登录
     * @param $code
     * @return array|mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function wxlogin($code)
    {
        // 获取小程序的信息
        $wxapp = Wxapp::detail();
        //判断是否有appid和appsecret
        if (empty($wxapp['app_id']) || empty($wxapp['app_secret'])) {
            throw new BaseException(['msg' => 'appid或appsecret不存在']);
        }
        // 调用微信接口 使用app_id app_secret code获取session_key
        $WxUser = new WxUser($wxapp['app_id'], $wxapp['app_secret']);
        if (!$session = $WxUser->sessionKey($code)) {
            throw new BaseException(['msg' => $WxUser->getError()]);
        }
        return $session;
    }

    /**
     * 根据openid生成token
     */
    private function token($openid)
    {
        $wxapp_id = self::$wxapp_id;
        // 生成随机字符串
        $guid = \getGuidV4();
        // 获取当前时间戳
        $timeStamp = microtime(true);
        //小程序id 时间戳 openid 随机字符
        return md5("{$wxapp_id}_{$timeStamp}_{$openid}_{$guid}");
    }

    /**
     * 注册用户
     * @param $open_id
     * @param $userInfo
     * @return mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function register($open_id, $userInfo)
    {
        if (!$user = self::get(['open_id' => $open_id])) {//如果该openid对应的用户不存在
            $user = $this;
            $userInfo['open_id'] = $open_id;
            $userInfo['wxapp_id'] = self::$wxapp_id;
        }
        //替换用户名中的其他字符
        $userInfo['nickName'] = preg_replace('/[\xf0-\xf7].{3}/', '', $userInfo['nickName']);
        if (!$user->allowField(true)->save($userInfo)) {
            throw new BaseException(['msg' => '用户注册失败']);
        }
        return $user['user_id'];
    }

}
