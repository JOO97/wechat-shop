<?php

namespace app\common\model;

/**
 * 商品规格模型
 * Class GoodsSpec
 * @package app\common\model
 */
class GoodsSpec extends BaseModel
{
    protected $name = 'goods_spec';


    public function getSpec($goods_sku_id)
    {
        return $this->where('goods_spec_id','=',$goods_sku_id)->find();
    }
}
