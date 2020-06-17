<?php
/**
 * 后台菜单配置
 *    'home' => [
 *       'name' => '首页',                // 菜单名称
 *       'icon' => 'icon-home',          // 图标 (class)
 *       'index' => 'index/index',         // 链接
 *     ],
 */
return [
    'index' => [
        'name' => '首页',
        'icon' => 'icon-ai-home',
        'index' => 'index/index',
    ],
    'goods' => [
        'name' => '商品管理',
        'icon' => 'icon-goods-copy',
        'index' => 'goods/index',
        'submenu' => [
            [
                'name' => '商品列表',
                'index' => 'goods/index',
                'urls' => [
                    'goods/index',
                    'goods/add',
                    'goods/edit'
                ],
            ],
            [
                'name' => '商品分类',
                'index' => 'goods.category/index',
                'uris' => [
                    'goods.category/index',
                    'goods.category/add',
                    'goods.category/edit',
                ],
            ]
        ],
    ],
    'order' => [
        'name' => '订单管理',
        'icon' => 'icon-dingdanguanli',
        'index' => 'order/delivery_list',
        'submenu' => [
            [
                'name' => '待发货',
                'index' => 'order/delivery_list',
            ],
            [
                'name' => '待收货',
                'index' => 'order/receipt_list',
            ],
            [
                'name' => '待付款',
                'index' => 'order/pay_list',
            ],
            [
                'name' => '已完成',
                'index' => 'order/complete_list',

            ],
            [
                'name' => '已取消',
                'index' => 'order/cancel_list',
            ],
            [
                'name' => '全部订单',
                'index' => 'order/all_list',
            ],
        ]
    ],
    'service' => [
        'name' => '预约管理',
        'icon' => 'icon-yiliaohangyedeICON-',
        'index' => 'service/all_list',
        'submenu' => [
            [
                'name' => '全部预约',
                'index' => 'service/all_list',
            ],
            [
                'name' => '待接单',
                'index' => 'service/order_list',
            ],
            [
                'name' => '已取消',
                'index' => 'service/cancel_list',
            ],
            [
                'name' => '待交单',
                'index' => 'service/take_list',
            ],
            [
                'name' => '已完成',
                'index' => 'service/finish_list',

            ],
        ]
    ],
    'user' => [
        'name' => '用户管理',
        'icon' => 'icon-yonghu',
        'index' => 'user/index',
    ],
    'comment' => [
        'name' => '评论管理',
        'icon' => 'icon-pinglun2',
        'index' => 'comment/index'
    ],
//    'wxapp' => [
//        'name' => '预约管理',
//        'icon' => 'icon-order',
//        'index' => 'wxapp.notice/index',
//        'submenu' => [
//            [
//                'name' => '全部预约',
//                'index' => 'wxapp.notice/index',
//            ],
//            [
//                'name' => '待接单',
//                'index' => 'wxapp.page/home',
//            ],
//        ]
//    ],
    'wxapp' => [
        'name' => '店铺设置',
        'icon' => 'icon-dianpu',
        'index' => 'wxapp.info/index',
        'submenu' => [
            [
                'name' => '门店管理',
                'index' => 'wxapp.info/index',
                'urls' => [
                    'wxapp.info/index',
                    'wxapp.info/edit',
                ],
            ],
            [
                'name' => '店铺公告',
                'index' => 'wxapp.notice/index',
                'urls' => [
                    'wxapp.notice/index',
                    'wxapp.notice/add',
                    'wxapp.notice/edit',
                    'wxapp.notice/delete'
                ],
            ],
//            [
//                'name' => '首页banner',
//                'index' => 'wxapp.page/home'
//            ],
        ]
    ],
//    'setting' => [
//        'name' => '订单设置',
//        'icon' => 'icon-setting',
//        'index' => 'setting/trade',
//        'submenu' => [
//            [
//                'name' => '订单设置',
//                'index' => 'setting/trade'
//            ],
//            [
//                'name' => '配送规则',
//                'index' => 'setting.delivery/index'
//            ],
//        ]
//    ]
//    'banner' => [
//        'name' => '轮播图',
//        'icon' => 'icon-setting',
//        'index' => 'wxapp.page/home'
//    ],
]?>
