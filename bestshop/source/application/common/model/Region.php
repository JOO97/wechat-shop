<?php
//
//namespace app\common\model;
//
//use think\Cache;
//
///**
// * 地区模型
// */
//class Region extends BaseModel
//{
//    protected $name = 'region';
//    protected $createTime = false;
//    protected $updateTime = false;
//
//    /**
//     * 根据id获取地区名称
//     */
//    public static function getNameById($id)
//    {
//        $region = self::getCacheAll();
//        return $region[$id]['name'];
//    }
//
//    /**
//     * 根据名称获取地区id
//     */
//    public static function getIdByName($name, $level = 0, $pid = 0)
//    {
//        //如果无该条地区信息，将该条地址信息存入
//        return static::useGlobalScope(false)->where(compact('name', 'level', 'pid'))
//            ->value('id') ?: static::add($name, $level, $pid);
//    }
//
//    /**
//     *
//     */
//    private static function add($name, $level = 0, $pid = 0)
//    {
//        $model = new static;
//        $model->save(compact('name', 'level', 'pid'));
//        Cache::rm('region');
//        //返回最新自增数据的id
//        return $model->getLastInsID();
//    }
//
////    /**
////     * 获取所有地区
////     * @return mixed
////     */
////    public static function getCacheTree()
////    {
////        return self::regionCache()['tree'];
////    }
//
//    /**
//     * 获取所有地区
//     * @return mixed
//     */
//    public static function getCacheAll()
//    {
//        return self::regionCache()['all'];
//    }
//
//    /**
//     * 获取地区缓存
//     */
//    private static function regionCache()
//    {
//        if (!Cache::get('region')) {//如果缓存不存在，则存入缓存
//            // 所有地区
//            $all = $allData = self::useGlobalScope(false)->column('id, pid, name, level', 'id');
//            // 格式化
//            $tree = [];
//            foreach ($allData as $pKey => $province) {
//                if ($province['level'] == 1) {    // 省份
//                    $tree[$province['id']] = $province;
//                    unset($allData[$pKey]);
//                    foreach ($allData as $cKey => $city) {
//                        if ($city['level'] == 2 && $city['pid'] == $province['id']) {    // 城市
//                            $tree[$province['id']]['city'][$city['id']] = $city;
//                            unset($allData[$cKey]);
//                            foreach ($allData as $rKey => $region) {
//                                if ($region['level'] == 3 && $region['pid'] == $city['id']) {    // 地区
//                                    $tree[$province['id']]['city'][$city['id']]['region'][$region['id']] = $region;
//                                    unset($allData[$rKey]);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//            Cache::set('region', compact('all', 'tree'));
//        }
//        return Cache::get('region');
//    }
//
//}
