<?php

namespace app\api\model;

use app\common\model\WxappPage as WxappPageModel;

/**
 * 微信小程序
 * Class WxappPage
 * @package app\api\model
 */
class WxappPage extends WxappPageModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

}
