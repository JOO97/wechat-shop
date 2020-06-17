<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">店铺信息</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                        <div class="am-form-group">
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <a class="am-btn am-btn-default am-btn-success am-radius"
                                       href="<?= url('wxapp.info/add') ?>">
                                        <span class="am-icon-plus"></span> 添加门店
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-12">
                        <table class="am-table am-table-compact am-table-striped tpl-table-black ">
                            <thead>
                            <tr>
                                <th>门店图片</th>
                                <th>门店名</th>
                                <th>地址</th>
                                <th>营业时间</th>
                                <th>联系电话</th>
                                <th>门店简介</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()){ foreach ($list as $item) {?>
                                <tr>
                                    <td colspan="8">
                                        <span>门店ID:<?= $item['store_info_id'] ?></span>
                                        <span>创建时间:<?= $item['create_time'] ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="am-text-middle">
                                        <img src="<?= $item['image'][0]['file_path'] ?>"
                                             width="100" height="100" alt="门店图片">
                                    </td>
                                    <td class="am-text-middle">
                                        <p class="item-title"><?= $item['store_info_name'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p class="item-title"><?= $item['store_info_address'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p class="item-title"><?= $item['store_info_opentime'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p class="item-title"><?= $item['store_info_tel'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p class="item-title"><?= $item['store_info_msg'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p class="item-title"><?= $item['update_time'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <a href="<?= url('wxapp.info/edit',
                                                ['store_info_id' => $item['store_info_id']]) ?>">
                                                编辑
                                            </a>
                                            <a href="javascript:;" class="item-delete tpl-table-black-operation-del"
                                               data-id="<?= $item['store_info_id'] ?>">
                                                删除
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8"></td>
                                </tr>
                            <?php }} else{ ?>
                                <tr>
                                    <td colspan="8" class="am-text-center">暂无记录</td>
                                </tr>
                            <?php }; ?>
                            </tbody>
                        </table>
                        <div class="am-u-lg-12 am-cf">
                            <div class="am-fr"><?= $list->render() ?> </div>
                            <div class="am-fr pagination-total am-margin-right">
                                <div class="am-vertical-align-middle">总记录：<?= $list->total() ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

         // 删除元素
        var url = "<?= url('wxapp.info/delete') ?>";
        $('.item-delete').delete('store_info_id', url);

    });
</script>

