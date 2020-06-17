let App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    // nav_select: false, // 快捷导航
    options: {}, // 当前页面参数

    address: null, // 默认收货地址
    exist_address: false, // 是否存在收货地址
    goods: {}, // 商品信息

    disabled: false,

    hasError: false,
    error: '',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    // 当前页面参数
    this.data.options = options;
    console.log(options);
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    // 获取当前订单信息
    this.getOrderData();
    // console.log(this.data.exist_address);
    if (!this.data.exist_address){
      this.setData({
        disabled:false
      })
    }
  },

  /**
   * 获取当前订单信息
   */
  getOrderData: function() {
    let _this = this,
      options = _this.data.options;
    console.log(options);
    // 获取订单信息回调方法
    let callback = function(result) {
      if (result.code !== 1) {
        console.log(result);
        App.showError(result.msg);
        return false;
      }
      // 显示错误信息
      if (result.data.has_error) {
        _this.data.hasError = true;
        _this.data.error = result.data.error_msg;
        App.showError(_this.data.error);
      }else{
        _this.setData({
          hasError:false,
          error:''
        })
      }
      _this.setData(result.data);
    };

    // 立即购买-获取订单数据
    if (options.order_type === 'buyNow') {
      console.log(options.goods_sku_id);
      App._get('order/buyNow', {
        goods_id: options.goods_id,
        goods_num: options.goods_num,
        goods_sku_id: options.goods_sku_id,
      }, function(result) {
        console.log(result);
        callback(result);
      });
    }

    // 购物车结算-获取订单数据
    else if (options.order_type === 'cart') {
      App._get('order/cart', {}, function(result) {
        callback(result);
        console.log(result);
      });
    }

  },

  /**
   * 选择收货地址
   */
  selectAddress: function() {
    wx.navigateTo({
      url: '../address/' + (this.data.exist_address ? 'index?from=flow' : 'create')
    });
  },

  /**
   * 订单提交
   */
  submitOrder: function() {
    let _this = this,
      options = _this.data.options;
      console.log(options);
    console.log(_this.data.exist_address);
    if(_this.data.hasError){
      App.showError(_this.data.error);
      return false;
    }
    if (_this.data.disabled) {
      return false;
    }
    // 禁用按钮
    _this.setData({
      disabled:true
    })
    console.log(_this.data.disabled);
    // _this.data.disabled = true;
    // if (_this.data.hasError) {
    //   App.showError(_this.data.error);
    //   return false;
    // }
    wx.showLoading({ title: '正在处理...', });    
    // 订单创建成功后支付
    let callback = function(result) {
      console.log(result);
      let price = result.data.price;
      console.log(_this.data.disabled);
      let order_id = result.data.order_id;
      wx.showModal({
        title: '提示',
        content: '生成订单成功,是否进行支付',
        success(res) {
          if (res.confirm) {
            // let pay_time = parseInt(new Date().getTime() / 1000);
            if (result.data.order_id) {
              // let order_id = result.data.order_id;
              App._post_form('user.order/pay', {
                order_id: order_id
              }, function (result) {
                // App.showSuccess('支付成功');
                // console.log(price);
                App.showSuccess('成功支付' + price, function () {
                  wx.redirectTo({
                    url: '../order/detail?order_id=' + order_id,
                })
                });
                // wx.showToast({
                //   title: '成功支付'+price,
                //   icon: 'success',
                //   duration: 2000,
                //   success:function(){
                    // wx.redirectTo({
                    //   url: '../order/detail?order_id=' + order_id,
                    // });
                //   }
                // });
              }, function (result) {
                App.showError(result.msg);
                wx.redirectTo({
                  url: '../order/index?type=payment',
                });
              }, function () {
                wx.hideLoading();
              });
            } else {
              App.showError(result.msg);
              // _this.data.disabled = true;
            }
          } else if (res.cancel) {
            // console.log('用户点击取消')
              setTimeout(function () {
                wx.redirectTo({
                  url: '../order/detail?order_id=' + order_id,
                });
              }, 1000)
              // _this.setData({
              //   disabled:false
              // })

          }
        }
      })
      // if (result.code === -10) {
      
     
        // App.showError('支付成功', function() {
        //   // 跳转到未付款订单
        //   // 跳转到订单详情
        //   wx.redirectTo({
        //     url: '../order/detail?order_id=' + result.data.order_id,
        //   });
        //   // wx.redirectTo({
        //   //   url: '../order/index?type=payment',
        //   // });
        // });
        // return false;
      // }
      // 发起微信支付
      // wx.requestPayment({
      //   timeStamp: result.data.payment.timeStamp,
      //   nonceStr: result.data.payment.nonceStr,
      //   package: 'prepay_id=' + result.data.payment.prepay_id,
      //   signType: 'MD5',
      //   paySign: result.data.payment.paySign,
      //   success: function(res) {
      //     // 跳转到订单详情
      //     wx.redirectTo({
      //       url: '../order/detail?order_id=' + result.data.order_id,
      //     });
      //   },
      //   fail: function() {
      //     App.showError('订单未支付', function() {
      //       // 跳转到未付款订单
      //       wx.redirectTo({
      //         url: '../order/index?type=payment',
      //       });
      //     });
      //   },
      // });
    };

    

    // 显示loading
    // wx.showLoading({
    //   title: '正在处理...'
    // });

    // 创建订单-立即购买
    if (options.order_type === 'buyNow') {
      App._post_form('order/buyNow', {
        goods_id: options.goods_id,
        goods_num: options.goods_num,
        goods_sku_id: options.goods_sku_id,
      }, function(result) {
        callback(result);
      }, function(result) {
      }, function() {
        _this.data.disabled = false;
      });
    }

    // 创建订单-购物车结算
    else if (options.order_type === 'cart') {
      App._post_form('order/cart', {}, function(result) {
        callback(result);
      }, function(result) {
      }, function() {
        _this.data.disabled = false;
      });
    }

  },


});