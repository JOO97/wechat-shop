<?php

namespace app\store\model;

use app\common\model\GoodsComment as GoodsCommentModel;
use think\Request;

/**
 * 评论模型
 * Class Comment
 * @package app\store\model
 */
class GoodsComment extends GoodsCommentModel
{
    /**
     * 订单列表
     * @param $filter
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($filter)
    {
        return $this->with(['Goods', 'user'])
            ->where($filter)
            ->order(['create_time' => 'desc'])->paginate(10, false, [
                'query' => Request::instance()->request()
            ]);
    }
}
