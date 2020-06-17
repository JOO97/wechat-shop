// pages/order/service.js
let App = getApp();
let util = require('../../utils/util.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    user_id:null,
    serviceList:null,
    isCancel:null,
    _options:null,
    dataType:'all',
    no_more:false,
    page: 1,
    scrollHeight: null,
    scrollTop: 0
  },

  /**
   * 设置列表高度
   */
  setListHeight: function () {
    let _this = this;
    wx.getSystemInfo({
      success: function (res) {
        _this.setData({
          scrollHeight: res.windowHeight - 90,
        });
      }
    });
  },

  /**
     * 下拉到底加载数据
     */
  bindDownLoad: function () {
    // 已经是最后一页
    if (this.data.page >= this.data.serviceList.last_page) {
      wx.showLoading({
        title: '已经到底了',
        duration: 400
      })
      this.setData({ no_more: true });
      return false;
    }
    this.getServiceList(false, ++this.data.page,this.data.dataType);
  },

  
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // this.setData({
    //   user_id:options.user_id,
    //   _options:{
    //     user_id:options.user_id
    //   },
    // })
    // this.getServiceList();
    // console.log(this.data)
  },

  
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    //设置高度
    this.setListHeight();
    //获取预约列表
    this.getServiceList(true,1,this.data.dataType);
  },


  /**
   * 获取预约列表
   */
  getServiceList(is_super,page,type) {
    wx.showLoading({
      title: '正在加载',
    })
    let _this = this;
    // let user_id = _this.data.user_id;
    App._get('user.order/serviceList', { 
      page:page,
      type:type
      }, function(result){
        if(result.data.list.data.length!=0){
          for (let i = 0; i < result.data.list.data.length; i++) {
            if (result.data.list.data[i].take_time != 0) {
              result.data.list.data[i].take_time = util.transTime(result.data.list.data[i].take_time * 1000, 1);
          }
            if (result.data.list.data[i].finish_time != 0) {
              result.data.list.data[i].finish_time = util.transTime(result.data.list.data[i].finish_time * 1000, 1);
          }
        };
        }
        let dataList = _this.data.serviceList;
        let resultList = result.data.list;
        if (is_super === true || typeof dataList.data === 'undefined') {
          _this.setData({
            serviceList: resultList
          });
        } else {
          // _this.setData({ 'list.data': dataList.data.concat(resultList.data) });
          _this.setData({
            'serviceList.data': dataList.data.concat(resultList.data)
          })
        }
      // if(result.data.serviceList.length!=0){
      //   let info = result.data.serviceList;
      //   for (let i = 0; i < info.length; i++) {
      //     info[i].create_time = util.transTime(info[i].create_time * 1000, 1);
      //     if (info[i].take_time != 0) {
      //       info[i].take_time = util.transTime(info[i].take_time * 1000, 1);
      //     }
      //     if (info[i].finish_time != 0) {
      //       info[i].finish_time = util.transTime(info[i].finish_time * 1000, 1);
      //     }
      //   };
      //   _this.setData({
      //     serviceList: info,
      //   });
        // wx.pageScrollTo({
        //   scrollTop: 0,
        //   duration: 300
        // })
        console.log(result)
      // } else{
        // _this.setData({
        //   serviceList:null
        // })
      // }
    },function(result){
      App.showError(result.msg);
      wx.switchTab({
      url: '/pages/index/index'
     })
    },function(){
      wx.hideLoading();
    });
  },

 /**
  * 跳转至预约页面
  */
  goService(){
    // wx.switchTab({
    //   url: '../category/index',
    // });
    wx.redirectTo({
      url: '/pages/service/index'
    })
  },

/**
 * 取消预约
 */
  cancel(e){
    let _this = this;
    let service_id = e.currentTarget.dataset.serviceid;
    let service_date = e.currentTarget.dataset.dateid;
    wx.showModal({
      title: '提示',
      content: '是否取消预约',
      success(res) {
        if (res.confirm) {
          // console.log('用户点击确定')
          console.log(service_id);
          console.log(service_date);
          let isCancel = util.isCancel(service_date);
          console.log(isCancel);
          if (isCancel == false) {//不可以取消
            wx.showModal({
              title: '提示',
              content: '无法取消',
              showCancel: false,
              success(res) {
              }
            })
          } else {
            // console.log('操作成功');
            App._post_form('user.order/cancelService', { service_id: service_id }, function (result) {
              wx.showToast({
                title: '操作成功',
                icon: 'loading',
                duration: 1000
              });
              // _this.onLoad(_this.data._options);
              _this.getServiceList(true,1,_this.data.dataType);
            }, function (result) {
              wx.showToast({
                title: result.msg,
                icon: 'loading',
                duration: 2000
              });
            },function(){
              wx.hideLoading();
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
  delete(e){
    let _this = this;
    wx.showModal({
      title: '提示',
      content: '是否删除该预约记录',
      success(res) {
        if (res.confirm) {
          let service_id = e.currentTarget.dataset.serviceid;
          App._post_form('user.order/deleteService', { service_id: service_id }, function (result) {
            wx.showToast({
              title: '删除成功',
              icon: 'loading',
              duration: 1000
            });
            _this.getServiceList(true,1,_this.data.dataType);
          }, function (result) {
            wx.showToast({
              title: result.msg,
              icon: 'loading',
              duration: 2000
            });
          },function(){
            wx.hideLoading();
          });
        } else if (res.cancel) {
          // console.log('取消')
        }
      }
    })
  },






 /**
  * 切换预约订单状态
  */
  bindHeaderTap(e){
    let _this = this;
    console.log(e);
    // _this.setData({ dataType: e.target.dataset.type });
    let type = e.target.dataset.type;
    // this.getList(type);
    //设置当前类型，页数，滚动距离
    _this.setData({
      dataType: type,
      page: 1,
      scrollTop: 0
    })
    //获取预约列表
    _this.getServiceList(true, 1, type);
  },

  // 获取订单列表
  // getList(type){
  //   if (type == 'status-all') {
  //     this.getServiceList();
  //   } else {
  //     if (type == 'status-10') {
  //       this.getOrderList(10);
  //     } else if (type == 'status-20') {
  //       this.getOrderList(20);
  //     } else if (type == 'status-30') {
  //       this.getOrderList(30);
  //     } else if (type == 'status-11') {
  //       this.getOrderList(11);
  //     }
  //   }
  // },

  /**
   * 获取其他状态订单
   */
  // getOrderList(type){
  //   let _this = this;
  //   let user_id = _this.data.user_id;
  //   App._post_form('user.order/serviceListStatus', { 
  //     user_id:user_id,
  //     type:type
  //     }, function (result) {
  //     if(result.data.serviceList.length!=0){
  //       let info = result.data.serviceList;
  //       for (let i = 0; i < info.length; i++) {
  //         info[i].create_time = util.transTime(info[i].create_time * 1000, 1);
  //         if (info[i].take_time != 0) {
  //           info[i].take_time = util.transTime(info[i].take_time * 1000, 1);
  //         }
  //         if (info[i].finish_time != 0) {
  //           info[i].finish_time = util.transTime(info[i].finish_time * 1000, 1);
  //         }
  //       };
  //       _this.setData({
  //         serviceList: info,
  //       });
  //       wx.pageScrollTo({
  //         scrollTop: 0,
  //         duration: 300
  //       })
  //       console.log(result)
  //     }else{
  //       _this.setData({
  //         serviceList:null
  //       })
  //     }
  //   }, function (result) {
  //     App.showError(result.msg);
  //     wx.switchTab({
  //       url: '/pages/index/index'
  //     })
  //   });
  // },


  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

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