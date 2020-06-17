<?php

namespace app\common\model;

/**
 * 门店图片模型
 * Class StoreImage
 * @package app\common\model
 */
class StoreImage extends BaseModel
{
    protected $name = 'store_image';
    protected $updateTime = false;

    /**
     * 关联文件表
     * @return \think\model\relation\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('UploadFile', 'image_id', 'file_id')
            ->bind(['file_path', 'file_name', 'file_url']);
    }

}
