// pages/store/index.js
const App = getApp();


Page({

  /**
   * 页面的初始数据
   */
  data: {
    storeInfo:{},
    infoId:null,
    storeDetail:null
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.showLoading({
      title: '加载中',
    })
    this.getStoreInfo();
  },

  getStoreInfo() {
    let _this = this;
    App._get('wxapp/lists', {}, function (result) {
      _this.setData({
        storeInfo:result.data.list.data,
        infoId:result.data.list.data[0].store_info_id,
        storeDetail: result.data.list.data[0]
      });
      // wx.hideLoading();
      console.log(result);
    },function(result){
      wx.redirectTo({
        url: '/pages/index/index',
      })
    },function(){
      wx.hideLoading();
    });
  },

  givingCall(){
    wx.makePhoneCall({
      phoneNumber: this.data.storeDetail.store_info_tel 
    ,
    success(result){
      console.log(result)
    },
    fail(result){
      console.log(result)
    }});
  },
  
  goMap(){
    // console.log();
    let plugin = requirePlugin('routePlan');
    let key = 'GHFBZ-SCQ3S-6EMOD-6R4QE-UEWK7-LOFH7';  //使用在腾讯位置服务申请的key
    let referer = 'gh_15aa228ad0fe';   //调用插件的app的名称
    let latitude = this.data.storeDetail.store_info_latitude;
    let longitude = this.data.storeDetail.store_info_longitude;
    let name = this.data.storeDetail.store_info_name
    let endPoint = JSON.stringify({  //终点
      'name': name,
      'latitude': latitude ,
      'longitude': longitude
    });
    wx.navigateTo({
      url: 'plugin://routePlan/index?key=' + key + '&referer=' + referer + '&endPoint=' + endPoint
    });
  },
  
  /**
   * tab栏
   */
  bindHeaderTap(e){
    // console.log(e);
    this.setData({ infoId: e.target.dataset.infoid });
    this.getStoreDetail(e.target.dataset.infoid);
    wx.showLoading({
      title: '加载中',
    })
  },


  /**
   * 获取门店详情
  */
  getStoreDetail(store_info_id) {
    let _this = this;
    App._get('wxapp/detail', { store_info_id: store_info_id}, function (result) {
      _this.setData({
        storeDetail: result.data.list,
      });
      console.log(result);
    }, function (result) {
      wx.redirectTo({
        url: '/pages/index/index',
      })
    },function(){
      wx.hideLoading();
    });
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