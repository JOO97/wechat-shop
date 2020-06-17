<?php

namespace app\store\model;

use app\common\model\WxappInfo as WxappInfoModel;
use think\Db;

/**
 * 门店信息
 * Class WxappInfo
 * @package app\store\model
 */
class WxappInfo extends WxappInfoModel
{
//    /**
//     * 编辑
//     * @param $data
//     * @return bool|int
//     */
//    public function edit($data)
//    {
//        return $this->allowField(true)->save($data) !== false;
//    }

    /**
     * 添加门店
     * @param $data
     * @return bool|int
     */
    public function add(array $data)
    {
        if (!isset($data['images']) || empty($data['images'])) {
            $this->error = '请上传商品图片';
            return false;
        }
        // 开启事务
        Db::startTrans();
        try {
            // 添加门店
            $this->allowField(true)->save($data);
            // 门店图片
            $this->addStoreImages($data['images']);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
//        return $this->allowField(true)->save($data) !== false;
    }


    /**
     * 添加门店图片
     * @param $images
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function addStoreImages($images)
    {
        $this->image()->delete();
        $data = array_map(function ($image_id) {
            return [
                'image_id' => $image_id
//                'wxapp_id' => self::$wxapp_id
            ];
        }, $images);
        return $this->image()->saveAll($data);
    }



    /**
     * 编辑商品
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        if (!isset($data['images']) || empty($data['images'])) {
            $this->error = '请上传商品图片';
            return false;
        }
        // 开启事务
        Db::startTrans();
        try {
            // 保存
            $this->allowField(true)->save($data);
            // 商品图片
            $this->addStoreImages($data['images']);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }


    /**
     * 删除商品
     * @return bool
     */
    public function remove()
    {
        // 开启事务处理
        Db::startTrans();
        try {
            $this->image()->delete();
            $this->delete();
            // 事务提交
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
            return false;
        }
    }




}
