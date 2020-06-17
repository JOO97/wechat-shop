<?php

namespace app\api\controller;

use app\api\model\Order as OrderModel;
use app\api\model\Wxapp as WxappModel;
use app\api\model\Cart as CartModel;
//use app\common\library\wechat\WxPay;
use think\Db;

/**
 * 订单 预约服务 发布评论
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }


    /**
     * 预约服务
     * @param $order_no
     * @param $open_id
     * @param $pay_price
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function addService($service_date, $service_install, $service_repair, $service_replace, $create_time, $user_id, $update_time,$address_id,$service_msg,$service_no)
    {
//          $wxapp_id = 10001;
          $info=Db::table('joo_user_address')
//              ->alias('a')
//              ->field('a.name,a.phone,a.wxapp_id,a.detail,b.merger_name')
//              ->join('joo_region b','a.region_id=b.id')
               ->where('address_id','=',$address_id)
               ->find();
          if($info) {
              $name = $info['name'];
              $phone = $info['phone'];
              $detail = $info['detail'];
              $merger_name = $info['region'];
              $wxapp_id = $info['wxapp_id'];
//          return array("code"=>"10001","msg"=>is_array($info));
//          return array("code"=>"10001","msg"=>$info['phone']);
              $data = ['service_date' => $service_date, 'service_install' => $service_install, 'service_repair' => $service_repair, 'service_replace' => $service_replace, 'create_time' => $create_time, 'user_id' => $user_id, 'update_time' => $update_time, 'address_id' => $address_id, 'service_msg' => $service_msg, 'service_no' => $service_no, 'name' => $name, 'phone' => $phone, 'detail' => $detail, 'merger_name' => $merger_name,'wxapp_id' => $wxapp_id];
              $info2 = Db::table('joo_service_order')->insert($data);
              if ($info2) {
                  return $this->renderSuccess([], '预约成功');
              } else {
                  return $this->renderError('预约失败,请稍后再试');
              }
          }
          else{
              return $this->renderError('预约失败,请稍后再试');
          }
    }




    /**
     * 发布评价
     * @param $order_id
     * @param $$pingjia
     * @param $pingfen
     * @param $createtime
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */

    public  function addComment($order_id,$pingjia,$pingfen,$create_time,$order_goods_id,$goods_id)
    {
        //查找订单商品表中是否存在该条数据
        $info=Db::table('joo_order_goods')
            ->where('order_id',$order_id)
            ->where('goods_id',$goods_id)
            ->where('comment_status',10)
            ->find();
        if($info){
            /*
            $info3=Db::table('joo_goods_comment')
                ->where('order_id',$order_id)
                ->where('goods_id',$goods_id)
                ->where('user_id',$info['user_id'])
                ->find();
            if(!$info3){*/
               //添加新评论
                $data = ['goods_id' => $info['goods_id'],'order_id' => $order_id,'user_id' => $info['user_id'],'create_time' => (int)$create_time,'pingjia' => $pingjia,'pingfen' => (int)$pingfen,'order_goods_id' => $order_goods_id];
                $info2=Db::table('joo_goods_comment')->insert($data);
                if($info2){
                    //添加评论后，更新订单商品表中的comment_status
                    $info_upt=Db::table('joo_order_goods')->where('order_goods_id', $order_goods_id)->update(['comment_status' => 20]);
                    if($info_upt){
                        //查询订单商品表中订单中所有comment_status=10的数据
                        $info_find=Db::table('joo_order_goods')
                            ->where('order_id',$order_id)
                            ->where('comment_status','=',10)
                            ->select();
//                        echo $info_find;
                        if(count($info_find)==0){
                            //修改订单表中的comment_status
                            $info_change=Db::table('joo_order')
                                ->where('order_id',$order_id)
                                ->update(['comment_status' => 20]);
                        }
                    }
                    return  array("code"=>"10003","msg"=>"评论成功");
                }
           /* }else{
                return array("code"=>"10002","msg"=>"请勿重复提交");
            }*/
        } else{
            return array("code"=>"10001","msg"=>"无效操作");
        }

    }


    /**
     * 订单确认-立即购买
     * @param $goods_id
     * @param $goods_num
     * @param $goods_sku_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function buyNow($goods_id, $goods_num, $goods_sku_id)
    {
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getBuyNow($this->user, $goods_id, $goods_num, $goods_sku_id);
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        // 创建订单
        if ($model->add($this->user['user_id'], $order)) {
//            // 支付
//            return $this->renderSuccess([
//                'payment' => $this->wxPay($model['order_no'], $this->user['open_id']
//                    , $order['order_pay_price']),
//                'order_id' => $model['order_id']
//            ]);
            return  $this->renderSuccess([
                'order_id' => $model['order_id'],
                'price' => $order['order_pay_price']
                ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }

    /**
     * 订单确认-购物车结算
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function cart()
    {
        // 购物车-订单确认
        $model = new OrderModel;
        $order = $model->getCart($this->user);//获取购物车商品信息
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        // 创建订单
        if ($model->add($this->user['user_id'], $order)) {
            // 清空购物车
            $Card = new CartModel($this->user['user_id']);
            $Card->clearAll();
//            return $this->renderSuccess([
//                'payment' => $this->wxPay($model['order_no'], $this->user['open_id']
//                    , $order['order_pay_price']),
//                'order_id' => $model['order_id']
//            ]);
            //创建订单成功后,返回订单号和总价
            return  $this->renderSuccess([
                'order_id' => $model['order_id'],
                'price' => $order['order_pay_price']
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }

//    /**
//     * 构建微信支付
//     * @param $order_no
//     * @param $open_id
//     * @param $pay_price
//     * @return array
//     * @throws \app\common\exception\BaseException
//     * @throws \think\exception\DbException
//     */
//    private function wxPay($order_no, $open_id, $pay_price)
//    {
//        $wxConfig = WxappModel::getWxappCache();
//        $WxPay = new WxPay($wxConfig);
//        return $WxPay->unifiedorder($order_no, $open_id, $pay_price);
//    }

}
