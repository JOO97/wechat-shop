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
                        am-text-nowrap am-margin-bottom-xs" style="word-wrap: break-word; word-break: break-all;">
                            <thead>
                            <tr>
<!--                                <th>ID</th>-->
                                <th>商品信息</th>
                                <th>购买价格</th>
                                <th>评分</th>
                                <th width="100px">评价</th>
<!--                                <th>评论时间</th>-->
                                <th>用户</th>

                            </tr>
                            </thead>
                            <tbody style="text-align: left;">
                            <?php if (!$list->isEmpty()){ foreach ($list as $comment){ ?>
                                <tr>
                                    <td style="text-align: left;">
                                        <span class="am-margin-right-lg">评论ID:<?= $comment['comment_id'] ?></span>
                                        <span class="am-margin-right-lg">订单ID:<?= $comment['order_id'] ?></span>
                                        <span>评论时间:<?=$comment['create_time'] ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <div style="word-break: break-word;">
                                            <img style="width: 80px;float: left;margin-right: 10px;" src="<?= $comment['goods']['image']['file_path'] ?>" alt="">
                                            <p><?= $comment['goods']['goods_name'] ?></p>
<!--                                            <p>--><?//= $comment['goods']['goods_attr'] ?><!--</p>-->
                                        </div>
                                    </td>
                                    <td style="text-align: left;">
                                        <p><?= $comment['goods']['goods_price'] ?>x<?= $comment['goods']['total_num'] ?></p>
                                        <p>总价:<?= $comment['goods']['total_price'] ?></p>
                                    </td>
                                    <td class="pingfen" style="text-align: left;">
<!--                                        <p>--><?//= $comment['pingfen'] ?><!--</p>-->
                                        <?php if($comment['pingfen']==1){
                                            echo '<i class="iconfont2 icon-xingxing1"></i>';
                                         } else if($comment['pingfen']==2){
                                            echo '<i class="iconfont2 icon-xingxing1></i><i class="iconfont2 icon-xingxing1"></i>';
                                        } else if ($comment['pingfen']==3){
                                            echo '<i class="iconfont2 icon-xingxing1"></i><i class="iconfont2 icon-xingxing1"></i><i class="iconfont2 icon-xingxing1"></i>';
                                        } else if ($comment['pingfen']==4){
                                            echo '<i class="iconfont2 icon-xingxing1"></i><i class="iconfont2 icon-xingxing1"></i><i class="iconfont2 icon-xingxing1"></i><i class="iconfont2 icon-xingxing1"></i>';
                                        } else if ($comment['pingfen']==5){
                                            echo '<i class="iconfont2 icon-xingxing1"></i><i class="iconfont2 icon-xingxing1"></i><i class="iconfont2 icon-xingxing1"></i><i class="iconfont2 icon-xingxing1"></i><i class="iconfont2 icon-xingxing1"></i>';
                                        }?>
                                    </td>
                                    <td style="text-align: left;white-space:pre-wrap;"><div><?=$comment['pingjia']?></div></td>
                                    <td>
                                        <div>
                                            <img style="width: 50px;" src="<?= $comment['user']['avatarUrl'] ?>" alt="">
                                        </div>
                                        <div class="goods-info">
                                            <p class="goods-title"><?= $comment['user']['nickName'] ?></p>
                                            <p class="goods-title">(用户ID:<?= $comment['user']['user_id'] ?>)</p>
                                            <p class="goods-spec am-link-muted">
                                                <?= $comment['user']['gender'] ?>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                </tr>
<!--                                <tr>-->
<!--                                    <td>--><?//= $comment?><!--</td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td colspan="5">--><?//=$comment['goods']?><!--</td>-->
<!--                                </tr>-->
                            <?php }};?>
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


    })
</script>


