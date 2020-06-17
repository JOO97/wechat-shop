<?php

namespace app\common\model;

use think\Request;
use traits\model\SoftDelete;

/**
 * 文件 拼接路径 获取图片列表
 * Class UploadFile
 * @package app\common\model
 */
class UploadFile extends BaseModel
{
    use SoftDelete;//软删除

    protected $name = 'upload_file';
    protected $updateTime = false;
    protected $deleteTime = false;
    protected $append = ['file_path'];

    /**
     * 拼接图片完整路径 file_path
     */
    public function getFilePathAttr($value, $data)
    {
//        if ($data['storage'] === 'local') {
            return self::$base_url . 'uploads/' . $data['file_name'];
//        }
//        return $data['file_url'] . '/' . $data['file_name'];
    }

    /**
     * 根据文件名查询文件id
     * @param $fileName
     * @return mixed
     */
    public static function getFildIdByName($fileName)
    {
        return (new static)->where(['file_name' => $fileName])->value('file_id');
    }

    /**
     * 查询文件id
     * @param $fileId
     * @return mixed
     */
    public static function getFileName($fileId)
    {
        return (new static)->where(['file_id' => $fileId])->value('file_name');
    }

    /**
     * 获取图片列表
     * @param string $file_type
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($file_type = 'image')
    {
        $model = $this->where(['file_type' => $file_type, 'is_delete' => 0]);
        return $model->order(['file_id' => 'desc'])
            ->paginate(32, false, [
            'query' => Request::instance()->request()
        ]);
    }

}
