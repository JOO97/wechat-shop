<?php

namespace app\common\model;

/**
 * 小程序公告
 * Class WxappNotice
 * @package app\common\model
 */
class WxappNotice extends BaseModel
{
    protected $name = 'wxapp_notice';

    /**
     * 获取公告列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->order(['sort' => 'asc'])->select();
    }

    /**
     * 公告详情
     * @param $notice_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($notice_id)
    {
        return self::get($notice_id);
    }

    /**
     * 新增默认公告
     * @param $wxapp_id
     * @return false|int
     */
    public function insertDefault($wxapp_id)
    {
        return $this->save([
            'title' => '最新公告',
            'content' => '1111111111',
            'sort' => 100,
            'wxapp_id'=> $wxapp_id
        ]);
    }

}
