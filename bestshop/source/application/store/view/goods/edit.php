<link rel="stylesheet" href="assets/store/css/goods.css">
<link rel="stylesheet" href="assets/store/plugins/umeditor/themes/default/css/umeditor.css">
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">商品信息</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="goods[goods_name]"
                                           value="<?= $model['goods_name'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品分类 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select name="goods[category_id]" required
                                            data-am-selected="{searchBox: 1, btnSize: 'sm',  placeholder:'请选择商品分类'}">
                                        <option value=""></option>
                                        <?php if (isset($catgory)): foreach ($catgory as $first): ?>
                                            <option value="<?= $first['category_id'] ?>"
                                                <?= $model['category_id'] == $first['category_id'] ? 'selected' : '' ?>>
                                                <?= $first['name'] ?></option>
                                            <?php if (isset($first['child'])): foreach ($first['child'] as $two): ?>
                                                <option value="<?= $two['category_id'] ?>"
                                                    <?= $model['category_id'] == $two['category_id'] ? 'selected' : '' ?>>
                                                    　　<?= $two['name'] ?></option>
                                                <?php if (isset($two['child'])): foreach ($two['child'] as $three): ?>
                                                    <option value="<?= $three['category_id'] ?>"
                                                        <?= $model['category_id'] == $three['category_id'] ? 'selected' : '' ?>>
                                                        　　　<?= $three['name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            <?php endforeach; endif; ?>
                                        <?php endforeach; endif; ?>
                                    </select>
<!--                                    <small class="am-margin-left-xs">-->
<!--                                        <a href="--><?//= url('goods.category/add') ?><!--">去添加</a>-->
<!--                                    </small>-->
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品图片 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <button type="button"
                                                class="upload-file am-btn am-btn-secondary am-radius">
                                            选择图片
                                        </button>
                                        <div class="uploader-list am-cf">
                                            <?php foreach ($model['image'] as $key => $item): ?>
                                                <div class="file-item">
                                                    <img src="<?= $item['file_path'] ?>">
                                                    <input type="hidden" name="goods[images][]"
                                                           value="<?= $item['image_id'] ?>">
                                                    <i class="iconfont icon-shanchu file-item-delete"></i>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="help-block am-margin-top-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">规格信息</div>
                            </div>
<!--                            <div class="am-form-group">-->
<!--                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品规格 </label>-->
<!--                                <div class="am-u-sm-9 am-u-end">-->
<!--                                    <label class="am-radio-inline">-->
<!--                                        <input type="radio" name="goods[spec_type]" value="10" data-am-ucheck-->
<!--                                            --><?//= $model['spec_type'] == 10 ? 'checked' : '' ?><!-- >-->
<!--                                        单规格-->
<!--                                    </label>-->
<!--                                    <label class="am-radio-inline">-->
<!--                                        <input type="radio" name="goods[spec_type]" value="20" data-am-ucheck-->
<!--                                            --><?//= $model['spec_type'] == 20 ? 'checked' : '' ?><!-- >-->
<!--                                        <span class="am-link-muted">多规格</span>-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                            </div>-->

                            <!-- 商品单规格 -->
                            <div class="goods-spec-single"
                                 style="display: <?= $model['spec_type'] == 10 ? 'block' : 'none' ?>;">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品编码 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="text" class="tpl-form-input" name="goods[spec][goods_no]"
                                               value="<?= $model['spec'][0]['goods_no'] ?>">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">现价</label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" class="tpl-form-input" name="goods[spec][goods_price]"
                                               value="<?= $model['spec'][0]['goods_price'] ?>" required>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label">原价</label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" class="tpl-form-input" name="goods[spec][line_price]"
                                               value="<?= $model['spec'][0]['line_price'] ?>">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">库存 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" class="tpl-form-input" name="goods[spec][stock_num]"
                                               value="<?= $model['spec'][0]['stock_num'] ?>" required>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">重量 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="number" class="tpl-form-input" name="goods[spec][goods_weight]"
                                               value="<?= $model['spec'][0]['goods_weight'] ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="am-form-group">
<!--                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">库存计算方式 </label>-->
<!--                                <div class="am-u-sm-9 am-u-end">-->
<!--                                    <label class="am-radio-inline">-->
<!--                                        <input type="radio" name="goods[deduct_stock_type]" value="10" data-am-ucheck-->
<!--                                            --><?//= $model['deduct_stock_type'] == 10 ? 'checked' : '' ?><!-- >-->
<!--                                        下单减库存-->
<!--                                    </label>-->
<!--                                    <label class="am-radio-inline">-->
<!--                                        <input type="radio" name="goods[deduct_stock_type]" value="20" data-am-ucheck-->
<!--                                            --><?//= $model['deduct_stock_type'] == 20 ? 'checked' : '' ?><!-- >-->
<!--                                        付款减库存-->
<!--                                    </label>-->
<!--                                </div>-->
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">商品详情</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品详情 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <!-- umeditor插件 -->
                                    <textarea id="container" name="goods[content]"><?= $model['content'] ?></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品状态 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[goods_status]" value="10" data-am-ucheck
                                            <?= $model['goods_status']['value'] == 10 ? 'checked' : '' ?> >
                                        上架
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="goods[goods_status]" value="20" data-am-ucheck
                                            <?= $model['goods_status']['value'] == 20 ? 'checked' : '' ?> >
                                        下架
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit" class="j-submit am-btn am-btn-secondary">提交
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 图片文件列表模板 -->
{{include file="layouts/_template/tpl_file_item" /}}

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<!-- 商品多规格模板 -->
<!--{{include file="goods/_template/spec_many" /}}-->

<script src="assets/store/js/ddsort.js"></script>
<script src="assets/store/plugins/umeditor/umeditor.config.js"></script>
<script src="assets/store/plugins/umeditor/umeditor.min.js"></script>
<script src="assets/store/js/goods.spec.js"></script>
<script>
    $(function () {

        // 富文本编辑器
        UM.getEditor('container');

        // 选择图片
        $('.upload-file').selectImages({
            name: 'goods[images][]'
            , multiple: true
        });

        // 图片列表拖动
        $('.uploader-list').DDSort({
            target: '.file-item',
            delay: 100, // 延时处理，默认为 50 ms，防止手抖点击 A 链接无效
            floatStyle: {
                'border': '1px solid #ccc',
                'background-color': '#fff'
            }
        });

        // 注册商品多规格组件
        var specMany = new GoodsSpec({
            container: '.goods-spec-many'
        }, <?= $specData ?>);

        // 切换单/多规格
        $('input:radio[name="goods[spec_type]"]').change(function (e) {
            var $goodsSpecMany = $('.goods-spec-many')
                , $goodsSpecSingle = $('.goods-spec-single');
            if (e.currentTarget.value === '10') {
                $goodsSpecMany.hide() && $goodsSpecSingle.show();
            } else {
                $goodsSpecMany.show() && $goodsSpecSingle.hide();
            }
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm({
            // form data
            buildData: function () {
                return {
                    goods: {
                        spec_many: specMany.getData()
                    }
                };
            },
            // 自定义验证
            validation: function () {
                var specType = $('input:radio[name="goods[spec_type]"]:checked').val();
                if (specType === '20') {
                    var isEmpty = specMany.isEmptySkuList();
                    isEmpty === true && layer.msg('商品规格不能为空');
                    return !isEmpty;
                }
                return true;
            }
        });

    });
</script>
