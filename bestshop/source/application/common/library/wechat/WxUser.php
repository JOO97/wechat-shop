<?php

namespace app\common\library\wechat;

/**
 * 使用code获取session_key
 * Class WxUser
 * @package app\common\library\wechat
 */
class WxUser
{
    private $appId;
    private $appSecret;

    private $error;

    /**
     * 构造方法
     * @param $appId
     * @param $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * 获取session_key(对用户数据进行加密签名的密钥)
     */
    public function sessionKey($code)
    {
        /**
         * code 换取 session_key
         * ​使用登录凭证 code 获取 session_key 和 openid
         */
        $url = 'https://api.weixin.qq.com/sns/jscode2session';//微信接口
        $result = json_decode(curl($url, [//发送https请求
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'grant_type' => 'authorization_code',
            'js_code' => $code
        ]), true);
        if (isset($result['errcode'])) {
            $this->error = $result['errmsg'];
            return false;
        }
        return $result;
    }

    public function getError()
    {
        return $this->error;
    }

}