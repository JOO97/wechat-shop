// pages/store/map.js
var QQMapWX = require('../../utils/qqmap-wx-jssdk.min.js');
// 实例化API核心类
const wxMap = new QQMapWX({
  key: 'GHFBZ-SCQ3S-6EMOD-6R4QE-UEWK7-LOFH7'
});
Page({

  /**
   * 页面的初始数据
   */
  data: {
    latitude:null,
    longitude:null,
    address:null
  },

  lc: function (wei) {
    var self = this
    wx.getLocation({
      type: 'wgs84',
      success: function (result) {
        wei(result);
        return wei;
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var _this = this
    var wei = this.lc(function (wei) {
      console.log(wei);
      wxMap.reverseGeocoder({
        location: {
          //纬度
          latitude: wei.latitude,
          //经度
          longitude: wei.longitude
        },
        success: function (result) {
          console.log(result);
          _this.setData({
            address:result.result.address_component
          })
        },
        fail: function (result) {
          console.log(result);
        }
      })

    });


    //   var _this = this;
    //   _this.reverseGeocoder();
    // },

    // /**经纬度逆解析 */
    // reverseGeocoder() {
    //   var that = this
    //   wxMap.reverseGeocoder({
    //     location: {
    //       // 你的经纬度
    //       latitude: 25.714767,
    //       longitude: 119.355411
    //     },  
    //     success: function (res) {
    //       console.log(res);
    //     },
    //     fail: function (res) {
    //       console.log(res);
    //     }
    //   });



    // let plugin = requirePlugin('routePlan');
    // let key = 'GHFBZ-SCQ3S-6EMOD-6R4QE-UEWK7-LOFH7';  //使用在腾讯位置服务申请的key
    // let referer = 'gh_15aa228ad0fe';   //调用插件的app的名称
    // let endPoint = JSON.stringify({  //终点
    //   'name': '吉野家(北京西站北口店)',
    //   'latitude': 39.89631551,
    //   'longitude': 116.323459711
    // });
    // wx.navigateTo({
    //   url: 'plugin://routePlan/index?key=' + key + '&referer=' + referer + '&endPoint=' + endPoint
    // });
  },


  map(){
    wx.getLocation({
      type: 'gcj02', //返回可以用于wx.openLocation的经纬度
      success(res) {
        const latitude = res.latitude
        const longitude = res.longitude
        wx.openLocation({
          latitude,
          longitude,
          scale: 18
        })
      }
    })
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