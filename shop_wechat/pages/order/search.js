// pages/order/search.js
let App = getApp();
let util = require('../../utils/util.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    date:null,
    user_id:null,
    isEmpty:null,
    serviceList:null,
    disabledSearch:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // console.log(options);
    this.setData({
      user_id:options.user_id
    })
    console.log(this.data.user_id);
  },

 /**
  * 选择器改变事件
  */
  bindDateChange: function (e) {
    console.log(e.detail.value)
    this.setData({
      date: e.detail.value
    })
  },

  /**
   * 搜索
   */
  searchDate(){
    let _this = this;
    if (_this.data.disabledSearch){
      return false;
    }
    if (!_this.data.date){
      wx.showModal({
        title: '提示',
        content: '您还未选择日期',
        showCancel:false
      })
    } else{
      _this.setData({ disabledSearch:true,serviceList:null})
      let date = _this.data.date;
      let user_id = _this.data.user_id;
      wx.showLoading({
        title: '加载中',
      });
      App._get('user.order/searchService', { 
        // user_id:user_id,
        date:date
       }, 
      function (result) {//success
        console.log(result);
        if(result.data.list.length==0){//查询结果的长度为0
          _this.setData({
            isEmpty:true
          });
        } else{
          _this.setData({
            isEmpty: false
          });
          let info = result.data.list;
          for (let i = 0; i < info.length; i++) {
              if (info[i].take_time != 0) {
                info[i].take_time = util.transTime(info[i].take_time * 1000, 1);
              }
              if (info[i].finish_time != 0) {
                info[i].finish_time = util.transTime(info[i].finish_time * 1000, 1);
              }
           };
           _this.setData({
             serviceList: info,
           });
        }
      }, function (result) {//fail
        App.showError(result.msg);
        // wx.switchTab({
        //   url: '/pages/index/index'
        // })
      },function(){
        _this.setData({ disabledSearch: false})
        wx.hideLoading();
      });
    }
  },

  /**
 * 取消预约
 */
  cancel(e) {
    let _this = this;
    let service_id = e.currentTarget.dataset.serviceid;
    let service_date = e.currentTarget.dataset.dateid;
    let date = _this.data.date;//获取当前搜索框的日期
    wx.showModal({
      title: '提示',
      content: '是否取消预约',
      success(res) {
        if (res.confirm) {
          // console.log('用户点击确定')
          // console.log(service_id);
          // console.log(service_date);
          let isCancel = util.isCancel(service_date);
          console.log(isCancel);
          if (isCancel == false) {//不可以取消
            wx.showModal({
              title: '提示',
              content: '无法在预约日期当天取消订单',
              showCancel: false,
              success(res) {
              }
            })
          } else {
            // console.log('操作成功');
            wx.showLoading({
              title: '正在取消',
            });
            App._post_form('user.order/cancelService', { service_id: service_id }, function (result) {
              wx.hideLoading();
              wx.showToast({
                title: '操作成功',
                icon: 'loading',
                duration: 1000,
                mask: true
              });
              // _this.onLoad(_this.data._options);
              _this.searchDate();//重新执行搜索
            }, function (result) {//取消预约失败
              wx.hideLoading();
              wx.showToast({
                title: result.msg,
                icon: 'loading',
                duration: 1000
              });
            });
          }
        } else if (res.cancel) {
          console.log('用户点击取消')
        }
      }
    });


  },


  /**
   * 删除预约订单
   */
  delete(e) {
    let _this = this;
    wx.showModal({
      title: '提示',
      content: '是否删除该预约记录',
      success(res) {
        if (res.confirm) {
          wx.showLoading({
            title: '正在删除',
          })
          let service_id = e.currentTarget.dataset.serviceid;
          App._post_form('user.order/deleteService', { service_id: service_id }, function (result) {
            wx.hideLoading();
            wx.showToast({
              title: '删除成功',
              icon: 'loading',
              duration: 1000,
              mask:true
            });
            _this.searchDate();//重新执行搜索
          }, function (result) {
            wx.hideLoading();
            wx.showToast({
              title: result.msg,
              icon: 'loading',
              duration: 2000
            });
          });
        } else if (res.cancel) {
          // console.log('取消')
        }
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