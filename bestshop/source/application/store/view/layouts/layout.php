<!--菜单栏结构-->
<!DOCTYPE html>
<html lang="cn">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<!--    <title>--><?//= $setting['store']['values']['name'] ?><!--</title>-->
    <title>后台管理系统</title>
<!--    <meta name="viewport" content="width=device-width, initial-scale=1"/>-->
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
<!--    <meta name="apple-mobile-web-app-title" content="--><?//= $setting['store']['values']['name'] ?><!--"/>-->
    <link rel="stylesheet" href="assets/store/css/amazeui.min.css"/>
    <link rel="stylesheet" href="assets/store/css/app.css"/>
    <link rel="stylesheet" href="assets/store/css/iconfont.css">
    <link rel="stylesheet" href="//at.alicdn.com/t/font_783249_t6knt0guzo.css">
    <script src="assets/store/js/jquery.min.js"></script>
    <script src="//at.alicdn.com/t/font_783249_e5yrsf08rap.js"></script>
    <script>
        BASE_URL = '<?= isset($base_url) ? $base_url : '' ?>';
        STORE_URL = '<?= isset($store_url) ? $store_url : '' ?>';
    </script>

</head>

<body data-type="">
<div class="am-g tpl-g">
    <!-- 头部 -->
    <header class="tpl-header">
        <!-- 右侧内容 -->
        <div class="tpl-header-fluid">
<!--            <div class="am-fl tpl-header-button switch-button">-->
<!--                <i class="iconfont2 icon-iconset0412"></i>-->
<!--            </div>-->
<!--            <div class="am-fl tpl-header-button refresh-button">-->
<!--                <i class="iconfont icon-refresh"></i>-->
<!--            </div>-->
            <div class="am-fr tpl-header-navbar">
                <ul>
                    <!-- 管理员 -->
                    <li class="am-text-sm tpl-header-navbar-welcome">
<!--                        <a href="--><?//= url('store.user/renew') ?><!--"><i class="iconfont2 icon-guanliyuan1 "></i>欢迎，<span>--><?//= $store['user']['user_name'] ?><!--</span>-->
<!--                        </a>-->
                        <a href="javascript:(0)"><i class="iconfont2 icon-guanliyuan1 "></i>欢迎，<span><?= $store['user']['user_name'] ?></span>
                        </a>
                    </li>
                    <!-- 退出 -->
                    <li class="am-text-sm">
                        <a href="<?= url('passport/logout') ?>">
                            <i class="iconfont icon-tuichu"></i> 退出
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- 侧边导航栏 -->
    <div class="left-sidebar">
        <?php $menus = $menus ?: []; ?>
        <?php $group = $group ?: 0; ?>
        <!-- 一级菜单 -->
        <ul class="sidebar-nav">
            <li class="sidebar-nav-heading"></li>
            <?php foreach ($menus as $key => $item): ?>
                <li class="sidebar-nav-link">
                    <a href="<?= isset($item['index']) ? url($item['index']) : 'javascript:void(0);' ?>"
                       class="<?= $item['active'] ? 'active' : '' ?>">
                        <?php if (isset($item['is_svg']) && $item['is_svg'] === true): ?>
                            <svg class="icon sidebar-nav-link-logo" aria-hidden="true">
                                <use xlink:href="#<?= $item['icon'] ?>"></use>
                            </svg>
                        <?php else: ?>
                            <i class="iconfont2 sidebar-nav-link-logo <?= $item['icon'] ?>"
                               style="<?= isset($item['color']) ? "color:{$item['color']};" : '' ?>"></i>
                        <?php endif; ?>
                        <?= $item['name'] ?>
                    </a>
                </li>
            <?php endforeach; ?>


        </ul>
        <!-- 子级菜单-->
        <?php $second = isset($menus[$group]['submenu']) ? $menus[$group]['submenu'] : []; ?>
        <?php if (!empty($second)) : ?>
            <ul class="left-sidebar-second">
                <li class="sidebar-second-item">
                    <?php foreach ($second as $item) { ?>
                        <?php if (!isset($item['submenu'])){ ?>
                            <!-- 二级菜单-->
                            <a href="<?= url($item['index']) ?>" class="<?= $item['active'] ? 'active' : '' ?>">
                                <?= $item['name']; ?>
                            </a>
                        <?php }; ?>
                    <?php }; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>

    <!-- 内容start -->
    <div class="tpl-content-wrapper <?= empty($second) ? 'no-sidebar-second' : '' ?>">
        {__CONTENT__}
    </div>
    <!-- end -->

</div>
<script src="assets/layer/layer.js"></script>
<script src="assets/store/js/jquery.form.min.js"></script>
<script src="assets/store/js/amazeui.min.js"></script>
<script src="assets/store/js/webuploader.html5only.js"></script>
<script src="assets/store/js/art-template.js"></script>
<script src="assets/store/js/app.js"></script>
<script src="assets/store/js/file.library.js"></script>

</body>

</html>
