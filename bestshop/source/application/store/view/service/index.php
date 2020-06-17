<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf"><?= $title ?></div>
                </div>
                <div class="widget-body am-fr">
                    <div class="order-list am-scrollable-horizontal am-u-sm-12 am-margin-top-xs">
                        <table width="100%" class="am-table am-table-centered
                        am-text-nowrap am-margin-bottom-xs">
                            <thead>
                            <tr>
                                <th width="5%" class="">预约ID</th>
<!--                                <th width="10%" class="">订单信息</th>-->
                                <th width="10%" class="">商品服务</th>
                                <th width="10%" class="">商品型号</th>
                                <th width="30%">地址</th><!--地址，时间-->
                                <th>用户ID</th>
                                <th>订单状态</th>
                                <th width="10%" class="">操作时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody class="service-tbody">
                            <?php if (!$list->isEmpty()){ foreach ($list as $k=>$v){?>
                                    <tr>
                                        <td colspan="8">
<!--                                            <span></span>-->
                                            <span class="service-item">单号:<?= $v['service_no'] ?></span>
                                            <span class="service-item">下单时间:<?= $v['create_time'] ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="service-item" ><?= $v['service_id'] ?></span>
                                        </td>
<!--                                        <td>-->
<!--                                            -->
<!--                                        </td>-->
                                        <td>
                                            <?= $v['service_install'] ==1? '<span class="service-item">预约安装</span>':''?>
                                            <?= $v['service_repair'] ==1? '<span class="service-item">预约维修</span>':''?>
                                            <?= $v['service_replace'] ==1? '<span class="service-item">预约换芯预</span>':''?>
                                        </td>
                                        <td>
                                            <span class="service-item"><?= $v['service_msg'] ?></span>
                                        </td>
                                        <td>
                                            <span class="service-item">收货人<?= $v['name'] ?></span><br>
                                            <span class="service-item">联系电话：<?= $v['phone'] ?></span><br>
                                            <span class="service-item">地址：<?= $v['merger_name'] ?><?= $v['detail'] ?></span>
                                        </td>
                                        <td>
                                            <span class="service-item"><?= $v['user_id'] ?></span>
                                        </td>
                                        <td>
                                            <?= $v['service_status']==10?'<span class="service-item">待接单</span>':''?>
                                            <?= $v['service_status']==11?'<span class="service-item">已取消</span>':''?>
                                            <?= $v['service_status']==20?'<span class="service-item">待交单</span>':''?>
                                            <?= $v['service_status']==30?'<span class="service-item">已完成</span>':''?>
                                        </td>
                                        <td>
                                            <?= $v['take_time']!=0?'<span class="service-item">接单:'.date('Y-m-d H:i:s',$v['take_time']).'</span><br>':''?>
                                            <?= $v['finish_time']!=0?'<span class="service-item">交单:'.date('Y-m-d H:i:s',$v['finish_time']).'</span>':''?>
                                        </td>
                                        <td>
                                            <?= $v['service_status']==10?'<a href="javascript:;" data-id="'.$v['service_id'].'" class="take service-item service-active">接单</a>':''?>
                                            <?= $v['service_status']==11?'<a class="service-item service-no">无</a>':''?>
                                            <?= $v['service_status']==20?'<a href="javascript:;" data-id="'.$v['service_id'].'" class="finish service-item service-active">交单</a>':''?>
                                            <?= $v['service_status']==30?'<a class="service-item service-no">无</a>':''?>
                                        </td>
                                    </tr>
                                    <tr><td colspan="8"></td></tr>
                            <?php }} else{?>
                                <tr>
                                    <td colspan="6" class="am-text-center">暂无记录</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
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
<script>
    $(function () {
        $('.take').click(function () {
            // var service_id= $(".take").attr("data");
            //var url = "<?//= url('service/taking') ?>//";
            var param = {};
            var index='service_id';
            param[index] = $(this).attr('data-id');
            var url = "<?= url('service/taking') ?>";
            layer.confirm('是否接单？', function (index) {
                $.post(url, param, function (result) {
                    result.code === 1 ? alert(result.msg, result.url)
                        : alert(result.msg);
                    window.location.reload();
                });
                layer.close(index);
            });
        });


        // taking('service_id',url);
        $('.finish').click(function () {
            // var service_id= $(".finish").attr("data");
            //var url = "<?//= url('service/taking') ?>//";
            var param = {};
            var index='service_id';
            param[index] = $(this).attr('data-id');
            var url = "<?= url('service/finish') ?>";
            layer.confirm('是否交单？', function (index) {
                $.post(url, param, function (result) {
                    result.code === 1 ? alert(result.msg, result.url)
                        : alert(result.msg);
                    window.location.reload();
                });
                layer.close(index);
            });
        });

    })



</script>
