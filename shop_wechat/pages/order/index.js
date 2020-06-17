let App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    dataType: 'all',
    list: [],
    disabledPay:false,
    page:1,
    no_more:false,
    scrollHeight: null,
    scrollTop: 0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.data.dataType = options.type || 'all';
    
    this.setData({ dataType: this.data.dataType });
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    this.setListHeight();
    // 获取订单列表
    this.getOrderList(true,1,this.data.dataType);
  },

  /**
     * 设置列表高度
     */
  setListHeight: function () {
    let _this = this;
    wx.getSystemInfo({
      success: function (res) {
        _this.setData({
          scrollHeight: res.windowHeight + 90,
        });
      }
    });
  },


  /**
   * 获取订单列表
   */
  getOrderList: function (is_super,page,dataType) {
    wx.showLoading({
      title: '正在加载',
    })
    let _this = this;
    App._get('user.order/lists', { 
      page:page,
      dataType:dataType
      }, function (result) {
      console.log(result);
      let dataList = _this.data.list;
      let resultList = result.data.list;
      // _this.setData(result.data);
      // result.data.list.length && wx.pageScrollTo({
      //   scrollTop: 0
      // });
      //判断是否为第一页
      if (is_super === true || typeof dataList.data === 'undefined') {
        _this.setData({
          list: resultList
        });
      } else {
        _this.setData({
          'list.data': dataList.data.concat(resultList.data)
        })
      }
    },function(result){

    },function(){
      wx.hideLoading();
    });
  },

  /**
   * 切换标签
   */
  bindHeaderTap: function (e) {
    this.setData({ 
      dataType: e.target.dataset.type,
      page:1,
      scrollTop:0
      });
    // 获取订单列表
    this.getOrderList(true,1,e.target.dataset.type);

  },

  /**
   * 取消订单
   */
  cancelOrder: function (e) {
    let _this = this;
    let order_id = e.currentTarget.dataset.id;
    wx.showModal({
      title: "提示",
      content: "确认取消订单？",
      success: function (o) {
        if (o.confirm) {
          App._post_form('user.order/cancel', { order_id }, function (result) {
            _this.getOrderList(_this.data.dataType);
          });
        }
      }
    });
  },
 
  /**
   * 进入详情页 评论
   */
  comment: function (e) {
    let _this = this;
    let order_id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../order/detail?order_id=' + order_id
    });
    // console.log(e.currentTarget.dataset)
    // wx.showModal({
    //   title: "提示",
    //   content: "是否对该商品进行评论？",
    //   success: function (o) {
    //     if (o.confirm) {   
    //       wx.navigateTo({
    //         url: '/pages/order/comment?order_id=' + order_id
    //       })
    //       // App._post_form('user.order/receipt', { order_id }, function (result) {
    //       //   _this.getOrderList(_this.data.dataType);
    //       // });
    //     }
    //   }
    // });
    // wx.navigateTo({
    //   url: '/pages/order/comment?order_id=' + order_id
    // });
  },

  /**
   * 确认收货
   */
  receipt: function (e) {
    let _this = this;
    let order_id = e.currentTarget.dataset.id;
    wx.showModal({
      title: "提示",
      content: "确认收到商品？",
      success: function (o) {
        if (o.confirm) {
          App._post_form('user.order/receipt', { order_id }, function (result) {
            _this.getOrderList(_this.data.dataType);
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
    let order_id = e.currentTarget.dataset.id;
    if (_this.data.disabledPay){
      return false;
    }
    wx.showModal({
      title: '提示',
      content: '是否确定支付',
      success(res) {
        if (res.confirm) {
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
              wx.navigateTo({
                url: '../order/detail?order_id=' + order_id
              });
            }, 1000)
          }, function (result) {
            App.showError(result.msg);
            // _this.setData({
            //   disabledPay: false
            // })
          }, function () {
            wx.hideLoading();
            _this.setData({
              disabledPay: false
            })
          });
        } else if (res.cancel) {
          // console.log('用户点击取消')
        }
      }
    })
   
  },

  /**
   * 跳转订单详情页
   */
  detail: function (e) {
    let order_id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../order/detail?order_id=' + order_id
    });
  },

  onPullDownRefresh: function () {
    wx.stopPullDownRefresh();
  },


  /**
   * 滚动事件
   */
  // scroll:function(t){
  //   console.log(t);
  // },

  /**
     * 下拉到底加载数据
     */
  bindDownLoad: function () {
    // 已经是最后一页
    if (this.data.page >= this.data.list.last_page) {
      wx.showLoading({
        title: '已经到底了',
        duration: 400
      })
      this.setData({ no_more: true });
      return false;
    }
    this.getOrderList(false, ++this.data.page);
  },



});