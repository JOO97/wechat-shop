<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">添加店铺信息</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">店铺名称</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="info[store_info_name]"
                                           value="<?= $model['store_info_name'] ?>" placeholder="请输入标题" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">店铺地址</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="info[store_info_address]"
                                           value="<?= $model['store_info_address'] ?>" placeholder="请输入地址" required>
                                </div>
                            </div>
<!--                            <div class="am-form-group">-->
<!--                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">地址对应经度</label>-->
<!--                                <div class="am-u-sm-9 am-u-end">-->
<!--                                    <input type="number" class="" rows="6" placeholder="请输入准确的经度"-->
<!--                                           name="info[store_info_latitude]" value="--><?//= $model['store_info_latitude'] ?><!--">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="am-form-group">-->
<!--                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">地址对应纬度</label>-->
<!--                                <div class="am-u-sm-9 am-u-end">-->
<!--                                    <input type="number" class="" rows="6" placeholder="请输入准确的纬度"-->
<!--                                           name="info[store_info_longitude]" value="--><?//= $model['store_info_longitude'] ?><!--">-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">营业时间</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="" rows="6" placeholder="请输入营业时间"
                                           name="info[store_info_opentime]" value="<?= $model['store_info_opentime'] ?>">
                                    <small>提示:格式为00:00-00:00</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">联系电话</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="tel" class="tpl-form-input" name="info[store_info_tel]"
                                           value="<?= $model['store_info_tel'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">门店图片 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <button type="button" class="upload-file am-btn am-btn-secondary am-radius">
                                           选择图片
                                        </button>
                                        <div class="uploader-list am-cf">
                                            <?php foreach ($model['image'] as $key => $item): ?>
                                                <div class="file-item">
                                                    <img src="<?= $item['file_path'] ?>">
                                                    <input type="hidden" name="info[images][]"
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
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">店铺简介</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <textarea  name="info[store_info_msg]" rows="6"><?= $model['store_info_msg'] ?></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <span class="am-u-sm-3 am-u-lg-2 am-form-label">上一次更新时间</span>
                                <div class="am-u-sm-9 am-u-end">
                                    <span style="vertical-align: middle;"><?= $model['update_time'] ?></span>
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

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<!-- 图片文件列表模板 -->
{{include file="layouts/_template/tpl_file_item" /}}

<script>
    $(function () {

        // 选择图片
        $('.upload-file').selectImages({
            name: 'info[images][]'
            , multiple: true
        });
        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>

