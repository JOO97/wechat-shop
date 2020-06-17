<?php
//
//namespace app\api\model;
//
//use app\common\model\DeliveryRule as DeliveryRuleModel;
//
///**
// * 配送模板区域及运费模型
// * Class DeliveryRule
// * @package app\api\model
// */
//class DeliveryRule extends DeliveryRuleModel
//{
//    /**
//     * 隐藏字段
//     * @var array
//     */
//    protected $hidden = [
//        'wxapp_id',
//        'create_time',
//        'update_time'
//    ];
//
//    /**
//     * 追加字段
//     * @var array
//     */
//    protected $append = ['region_data'];
//
//    /**
//     * 地区集转为数组格式
//     * @param $value
//     * @param $data
//     * @return array
//     */
//    public function getRegionDataAttr($value, $data)
//    {
//        return explode(',', $data['region']);
//    }
//
//    /**
//     * 计算运费
//     */
////    public function calcTotalFee($total_num, $total_weight, $city_id)
////    {
////        $rule = $this;  // 当前规则
////        foreach ($this['rule'] as $item) {
////            if (in_array($city_id, $item['region_data'])) {
////                $rule = $item;
////                break;
////            }
////        }
//////         商品总数量or总重量
////        $total = $rule['method']== 10 ? $total_num : $total_weight;
////        $total = $total_num;
////        if ($total <= $rule['first']) {
////            return number_format($rule['first_fee'], 2);
////        }
////        // 续件or续重 数量
////        $additional = $total - $rule['first'];
////        if ($additional <= $rule['additional']) {
////            return number_format($rule['first_fee'] + $rule['additional_fee'], 2);
////        }
////        // 计算续重/件金额
////        if ($rule['additional'] < 1) {
////            // 配送规则中续件为0
////            $additionalFee = 0.00;
////        } else {
////            $additionalFee = bcdiv($rule['additional_fee'], $rule['additional'], 2) * $additional;
////        }
////        return number_format($rule['first_fee'] + $additionalFee, 2);
////    }
//
//    /**
//     * 验证用户收货地址是否存在运费规则中
//     */
////    public function checkAddress($city_id)
////    {
////        $cityIds = explode(',', $this['region']);
////        return in_array($city_id, $cityIds);
////    }
//
//    /**
//     * 根据运费组合策略 计算最终运费
//     */
////    public static function freightRule($allExpressPrice)
////    {
////        $freight_rule = Setting::getItem('trade')['freight_rule'];
////        $expressPrice = 0.00;
////        switch ($freight_rule) {
////            case '10':    // 策略1: 叠加
////                $expressPrice = array_sum($allExpressPrice);
////                break;
////            case '20':    // 策略2: 以最低运费结算
////                $expressPrice = min($allExpressPrice);
////                break;
////            case '30':    // 策略3: 以最高运费结算
////        $expressPrice = max($allExpressPrice);
////                break;
////        }
////        return $expressPrice;
////    }
//
//}
