let App = getApp();
let util = require('../../utils/util.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    order_id: null,
    order: {},
    _options:null,
    disabledPay:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    console.log('load');
    let _options = options;
    this.setData({
      _options:_options
      });
    this.data.order_id = options.order_id;
    this.getOrderDetail(options.order_id);
  },

  onShow: function () {
    console.log('show');
    this.onLoad(this.data._options);
  },

  /**
   * 获取订单详情
   */
  getOrderDetail: function (order_id) {
    let _this = this;
    App._get('user.order/detail', { order_id }, function (result) {
      console.log(result);
      result.data.order.pay_time = util.transTime(result.data.order.pay_time * 1000, 1);
      result.data.order.delivery_time = util.transTime(result.data.order.delivery_time * 1000, 1);
      _this.setData(result.data);
      console.log(_this.data.order);    
    });
  },

  /**
   * 跳转到商品详情
   */
  goodsDetail: function (e) {
    let goods_id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../goods/index?goods_id=' + goods_id
    });
  },

  /**
   * 取消订单
   */
  cancelOrder: function (e) {
    let _this = this;
    let order_id = _this.data.order_id;
    wx.showModal({
      title: "提示",
      content: "确认取消订单？",
      success: function (o) {
        if (o.confirm) {
          App._post_form('user.order/cancel', { order_id }, function (result) {
            wx.navigateBack();
          });
        }
      }
    });
  },

  /**
   * 发起付款
   */
  payOrder: function (e) {
    let _this = this;
    let order_id = _this.data.order_id;
    if (_this.data.disabledPay) {
      return false;
    }
    wx.showModal({
      title: '提示',
      content: '是否确定支付',
      success(res) {
        if (res.confirm) {//确定支付,禁用按钮
          _this.setData({
            disabledPay: true
          })
          // 显示loading
          wx.showLoading({ title: '正在处理...', });
          App._post_form('user.order/pay', {
            order_id: order_id,
          }, function (result) {
            // App.showSuccess('支付成功');
            wx.showToast({
              title: '支付成功',
              icon: 'success',
              duration: 1000
            })
            setTimeout(function () {
              _this.getOrderDetail(order_id);
            }, 1000)
          }, function (result) {
            App.showError(result.msg);
            _this.setData({
              disabledPay: false
            })
          }, function () {
            wx.hideLoading();
          });
        } else if (res.cancel) {//取消支付
          // console.log('用户点击取消')
        }
      }
    })
   
  },

  /**
   * 确认收货
   */
  receipt: function (e) {
    let _this = this;
    let order_id = _this.data.order_id;
    wx.showModal({
      title: "提示",
      content: "确认收到商品？",
      success: function (o) {
        if (o.confirm) {
          App._post_form('user.order/receipt', { order_id }, function (result) {
            _this.getOrderDetail(order_id);
          });
        }
      }
    });
  },

  /**
   * 去评论
   */
  comment: function (e) {
    let _this = this;
    let order_id = _this.data.order_id;
    let order_goods_id = e.currentTarget.dataset.order_goods_id;
    // let order_goods_id = _this.data.order_goods_id;
    console.log(order_goods_id);
    wx.navigateTo({
      url: '/pages/order/comment?order_goods_id=' + order_goods_id + '&order_id=' + order_id
    })
    // wx.showModal({
    //   title: "提示",
    //   content: "是否对该商品进行评价？",
    //   success: function (o) {
    //     if (o.confirm) {
    //       wx.navigateTo({
    //         url: '/pages/order/comment?order_id=' + order_id
    //       })
    //     }
    //   }
    // });
  },

});

