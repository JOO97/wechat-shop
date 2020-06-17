<?php

namespace app\api\model;

use app\common\model\WxappNotice as WxappNoticeModel;

/**
 * 小程序帮助中心
 * Class WxappNotice
 * @package app\api\model
 */
class WxappNotice extends WxappNoticeModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'create_time',
//        'update_time',
    ];
}
