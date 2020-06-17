const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    isLogin: false,
    userInfo: {},
    orderCount: {},
    user_id:null
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options) {
    
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow() {
    let _this = this;
    _this.setData({
      isLogin: App.checkIsLogin()
    });
    if (_this.data.isLogin) {
      // 获取当前用户信息
      _this.getUserDetail();
    }
    // console.log(_this.data.userInfo);
  },

  /**
   * 获取当前用户信息
   */
  getUserDetail() {
    let _this = this;
    App._get('user.index/detail', {}, function(result) {
      console.log(result);
      _this.setData(result.data);
      _this.setData({
        user_id:result.data.userInfo.user_id
      })
      // console.log(_this.data);
    });
  },

  /**
   * 订单导航跳转
   */
  onTargetOrder(e) {
    let _this = this;
    if (!_this.onCheckLogin()) {
      return false;
    }
    let urls = {
      all: '/pages/order/index?type=all',
      payment: '/pages/order/index?type=payment',
      delivery: '/pages/order/index?type=delivery',
      received: '/pages/order/index?type=received',
      comment: '/pages/order/index?type=comment'
    };
    // 转跳指定的页面
    wx.navigateTo({
      url: urls[e.currentTarget.dataset.type]
    })
  },
  // // 客服 回调获取到用户所点消息的页面路径 path 和对应的参数 query
  // handleContact(e) {
  //   console.log("客服")
  //   console.log(e.detail.path)
  //   console.log(e.detail.query)
  // },
  /**
   * 菜单列表导航跳转
   */
  onTargetMenus(e) {
    let _this = this;
    if (!_this.onCheckLogin()) {
      return false;
    }
    wx.navigateTo({
      url: '/' + e.currentTarget.dataset.url
    })
  },

  /**
   * 跳转到登录页
   */
  onLogin() {
    wx.navigateTo({
      url: '../login/login',
    });
  },

  /**
   * 验证是否已登录
   */
  onCheckLogin() {
    let _this = this;
    if (!_this.data.isLogin) {
      App.showError('很抱歉，您还没有登录');
      return false;
    }
    return true;
  },


})