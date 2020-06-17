// pages/user/collect.js
let App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    user_id:null,
    list:null,
    isEmpty:true,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // console.log(options);
    wx.showLoading({
      title: '加载中',
    })
    this.setData({
      user_id:options.user_id,
    });
  },

  /**
   * 获取收藏列表
   */
  getCollect(){
    let _this = this;
    App._get('address/getCollect',{
      user_id:_this.data.user_id
    },function(result){
      if(result.data.list.length!=0){
        _this.setData({
          list: result.data.list,
          isEmpty:false
        })
      }else{
        _this.setData({
          list:null
        })
      }
      console.log(result)
    },function(result){
      console.log(result)
    },function(){
      wx.hideLoading();
    });
  },
  

  /**
   * 取消收藏
   */
  cancel(e){
    let _this = this;
    let collect_id = e.currentTarget.dataset.collectid;
    App._post_form('address/cancel2', {
     collect_id:collect_id
    }, function (result) {
      App.showSuccess('取消收藏成功');
      _this.onShow();
    }, function (result) {
      console.log(result);
      wx.showModal({
        title: '提示',
        content: '取消收藏失败',
      },function(){
        _this.onShow();
      })
    }, function () {
      wx.hideLoading();
    });
  },

  /**
   * 商品详情页
   */
  detail(e){
    let _this=this;
    let goods_id=e.currentTarget.dataset.goodsid;
    let goods_status = e.currentTarget.dataset.status;
    if(goods_status==10){
      wx.navigateTo({
        url: '../goods/index?goods_id=' + goods_id
      });
    }else{
      wx.showModal({
        title: '提示',
        content: '该商品已下架',
        showCancel:false
      })
    }
    
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    this.getCollect();
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})