<?php

namespace app\common\model;

use think\Cache;

/**
 * 商品分类
 * Class Category
 * @package app\common\model
 */
class Category extends BaseModel
{
    protected $name = 'category';

    /**
     * 分类图片
     * @return \think\model\relation\HasOne
     */
    public function image()
    {
        return $this->hasOne('uploadFile', 'file_id', 'image_id');
    }

    /**
     * 所有分类-构建树形结构
     */
    public static function getALL()
    {
        $model = new static;
        if (!Cache::get('category_' . $model::$wxapp_id)) {//如果缓存中不存在分类信息
            $data = $model->with(['image'])->order(['sort' => 'asc'])->select();//查询所有分类信息
            $all = !empty($data) ? $data->toArray() : [];
            $tree = [];//构建树形结构
            foreach ($all as $first) {
                if ($first['parent_id'] != 0) continue;//该分类的父级id不等于0时，放弃本次循环
                $twoTree = [];//子分类
                    foreach ($all as $two) {
                    if ($two['parent_id'] != $first['category_id']) continue;//该分类的父级id不等于当前的分类,放弃本次循环
//                    $threeTree = [];
//                    foreach ($all as $three)
//                        $three['parent_id'] == $two['category_id']
//                        && $threeTree[$three['category_id']] = $three;
//                    !empty($threeTree) && $two['child'] = $threeTree;
                    $twoTree[$two['category_id']] = $two;
                }
                if (!empty($twoTree)) {
                    //将子分类按照sort从低到高排序
                    array_multisort(array_column($twoTree, 'sort'), SORT_ASC, $twoTree);
                    $first['child'] = $twoTree;
                }
                $tree[$first['category_id']] = $first;//设置顶级分类的id
            }
            Cache::set('category_' . $model::$wxapp_id, compact('all', 'tree'));
        }
        return Cache::get('category_' . $model::$wxapp_id);
    }

    /**
     * 获取所有分类
     */
    public static function getCacheAll()
    {
        return self::getALL()['all'];
    }

    /**
     * 获取所有分类(树型结构)
     */
    public static function getCacheTree()
    {
        return self::getALL()['tree'];
    }

}
