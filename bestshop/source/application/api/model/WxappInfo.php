<?php

namespace app\api\model;

use app\common\model\WxappInfo as WxappInfoModel;

/**
 * 门店模型
 * Class Goods
 * @package app\api\model
 */
class WxappInfo extends WxappInfoModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];


}
