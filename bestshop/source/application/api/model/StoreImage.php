<?php

namespace app\api\model;

use app\common\model\StoreImage as StoreImageModel;

/**
 * 门店图片模型
 * Class GoodsImage
 * @package app\api\model
 */
class StoreImage extends StoreImageModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
    ];

}
