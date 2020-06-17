<?php

namespace app\api\controller;

use app\api\model\Category as CategoryModel;

/**
 * 商品分类控制器
 * Class Goods
 * @package app\api\controller
 */
class Category extends Controller
{
    /**
     * 获取分类
     */
    public function lists()
    {
        $list = array_values(CategoryModel::getCacheTree());
        return $this->renderSuccess(compact('list'));
    }
}
