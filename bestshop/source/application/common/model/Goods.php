<?php

namespace app\common\model;

use think\Request;

/**
 * 商品 商品规格
 * Class Goods
 * @package app\common\model
 */
class Goods extends BaseModel
{
    protected $name = 'goods';
    //追加字段 商品销量
    protected $append = ['goods_sales'];

    /**
     * 计算显示销量
     */
    public function getGoodsSalesAttr($value, $data)
    {
        return $data['sales_actual'];
    }

    /**
     * 关联商品分类表
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('Category');
    }

    /**
     * 关联商品规格表
     * @return \think\model\relation\HasMany
     */
    public function spec()
    {
        return $this->hasMany('GoodsSpec');
    }

    /**
     * 关联商品图片表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->hasMany('GoodsImage')->order(['id' => 'asc']);
    }



    /**
     */
//    public function specRel()
//    {
//        return $this->belongsToMany('SpecValue', 'GoodsSpecRel');
//    }

    /**
     */
//    public function deliveryRule()
//    {
//        return $this->BelongsTo('DeliveryRule');
//    }

    /**
     * 商品状态信息
     */
    public function getGoodsStatusAttr($value)
    {
        $status = [10 => '上架', 20 => '下架'];
        return ['text' => $status[$value], 'value' => $value];
    }



    /**
     * 获取商品列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($status = null, $category_id = 0, $search = '', $sortType = 'all', $sortPrice = false,$sortSales = false)
    {

        $filter = [];// 条件
        //分类页-列表页
        $category_id > 0 && $filter['category_id'] = $category_id;
        $status > 0 && $filter['goods_status'] = $status;
        //trim()删除字符串前后的空格
        //搜索页-列表页 模糊查询
        !empty($search) && $filter['goods_name'] = ['like', '%' . trim($search) . '%'];
        // 排序规则
        $sort = [];
        if ($sortType === 'all') {//全部
            $sort = ['goods_sort', 'goods_id' => 'desc'];
        } elseif ($sortType === 'sales') {//销量
            $sort = $sortSales ? ['goods_sales' => 'desc'] : ['goods_sales' => 'asc'];
        } elseif ($sortType === 'price') {//价格
            $sort = $sortPrice ? ['goods_max_price' => 'desc'] : ['goods_min_price'];
        }
        // 商品表名称
        $tableName = $this->getTable();
        // 多规格商品 最高价与最低价
        $GoodsSpec = new GoodsSpec;
        $minPriceSql = $GoodsSpec->field(['MIN(goods_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        $maxPriceSql = $GoodsSpec->field(['MAX(goods_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        // 查询
        $list = $this->field(['*', '(sales_actual) as goods_sales',
            "$minPriceSql AS goods_min_price",
            "$maxPriceSql AS goods_max_price"
        ])->with(['category', 'image.file', 'spec'])
            ->where('is_delete', '=', 0)
            ->where($filter)
            ->order($sort)
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
        return $list;
    }

    /**
     * 获取商品详情
     * @param $goods_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($goods_id)
    {
        //商品信息、商品分类、商品图片、商品规格
//        return self::get($goods_id, ['category', 'image.file', 'spec', 'deliveryRule']);
        return self::get($goods_id, ['category', 'image.file', 'spec']);

    }

    /**
     * 热门商品
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBestList()
    {
        return $this->with(['spec', 'category', 'image.file'])
            ->where('is_delete', '=', 0)
            ->where('goods_status', '=', 10)
            ->order(['sales_actual' => 'desc'])
            ->limit(10)
            ->select();
    }

    /**
     * 新品推荐
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNewList()
    {
        return $this->with(['spec', 'category', 'image.file'])
            ->where('is_delete', '=', 0)
            ->where('goods_status', '=', 10)
            ->order(['goods_id' => 'desc'])
            ->limit(10)
            ->select();
    }

    /**
     * 获取商品规格信息
     */
    public function getGoodsSku($goods_sku_id)
    {
//        $goodsSkuData = array_column($this['spec']->toArray(), null, 'spec_sku_id');
//        $goodsSkuData = array_column($this['spec']->toArray(), null, 'goods_spec_id');
//        if (!isset($goodsSkuData[$goods_sku_id])) {
//            return false;
//        }
//        $goods_sku = $goodsSkuData[$goods_sku_id];
//        $goods_sku['goods_attr'] = '';
        $model=new GoodsSpec();
        $goods_sku=$model->getSpec($goods_sku_id);
        if(!$goods_sku){
            return false;
        }
        return $goods_sku;
    }





    /**
     * 获取规格信息 参数规格关系，规格信息
     * @param \think\Collection $spec_rel
     * @param \think\Collection $skuData
     * @return array
     */
    public function getManySpecData($spec_rel, $skuData)
    {
        // spec_attr 规格项与规格组
        $specAttrData = [];
        foreach ($spec_rel->toArray() as $item) {
            //规格名称-规格项名称
            if (!isset($specAttrData[$item['spec_id']])) {
                $specAttrData[$item['spec_id']] = [
                    'group_id' => $item['spec']['spec_id'],
                    'group_name' => $item['spec']['spec_name'],
                    'spec_items' => [],
                ];
            }
            $specAttrData[$item['spec_id']]['spec_items'][] = [
                'item_id' => $item['spec_value_id'],
                'spec_value' => $item['spec_value'],
            ];
        }

        // spec_list 规格项详情
        $specListData = [];
        foreach ($skuData->toArray() as $item) {
            $specListData[] = [
                'goods_spec_id' => $item['goods_spec_id'],
                'spec_sku_id' => $item['spec_sku_id'],
                'rows' => [],
                'form' => [
                    'goods_no' => $item['goods_no'],
                    'goods_price' => $item['goods_price'],
                    'goods_weight' => $item['goods_weight'],
                    'line_price' => $item['line_price'],
                    'stock_num' => $item['stock_num'],
                ],
            ];
        }
        return ['spec_attr' => array_values($specAttrData), 'spec_list' => $specListData];
    }
}
