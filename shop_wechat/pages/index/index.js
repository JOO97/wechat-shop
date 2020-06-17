let App = getApp();
//腾讯地图
var QQMapWX = require('../../utils/qqmap-wx-jssdk.min.js');
// 实例化API核心类
const wxMap = new QQMapWX({
  key: 'GHFBZ-SCQ3S-6EMOD-6R4QE-UEWK7-LOFH7'
});


Page({
  data: {
    items: null,//轮播图
    newest: null,//新品
    best: null,//热门
    notice:null,//公告
    isShow:false,
    scrollTop: 0,
    list:[],
    name:{
      name1: '预约上门',
      name2: '门店信息',
      name3: '新品推荐',
      name4: '热门商品'
    },
    address: null
  },

  onLoad: function() {
    this.getLoc();
    
  },

  onShow: function(){
      wx.showLoading({
        title: '加载中',
      })
    // this.isLogin();
    this.getIndexData();
    //获取公告栏
    // this.getInfoList();
    //请求获取用户位置
    // wx.getSetting({
    //   success(res) {
    //     if (!res.authSetting['scope.userLocation']) {
    //       wx.authorize({
    //         scope: 'scope.userLocation',
    //         success() {
    //           // 用户已经同意小程序使用录音功能，后续调用 wx.startRecord 接口不会弹窗询问
    //           // wx.startRecord()
    //           console.log(1);
    //         }
    //       })
    //     }
    //   }
    // })
  },
 
/**
 * 获取用户定位
 */
getLoc(){
  let _this = this
  let wei = this.lc(function (wei) {
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
          address: result.result.address_component
        })
      },
      fail: function (result) {
        console.log(result);
      }
    })

  });
},

/**
 * 获取经纬度
 */
  lc: function (wei) {
    var self = this
    wx.getLocation({
      type: 'gcj02',
      success: function (result) {
        wei(result);
        return wei;
      }
    })
  },


/**
 * 去预约
 */
  orderService:function(){
    console.log(App.getUserId());
    if (App.checkIsLogin() == false){//判断是否登录
        console.log(App.checkIsLogin);
        wx.showToast({
          title: '请先授权登录',
          icon: 'loading',
          duration: 1000,
          success: function () {
            setTimeout(function () {
              //要延时执行的代码
              App.doLogin();
            }, 1000) //延迟时间
          }
        });
    } else if (App.checkIsLogin() == true) {
      // let user_id=App.getUserId;
      wx.navigateTo({
        url: '/pages/service/index'
      })
      console.log(2)
    }
    console.log(App.getUserId());

  },


  /**
   * 获取首页数据:轮播图，公告，新品，热门
   */
  getIndexData: function() {
    let _this = this;
    App._get('index/page', {}, function(result) {
      console.log(result.data);
      _this.setData(result.data);
      console.log(_this.data.items);
      _this.setData({
        isShow:true
      })
        wx.hideLoading();
        wx.stopPullDownRefresh();
    });
  },


  /**
   * 返回顶部
   */
  goTop: function(t) {
    this.setData({
      scrollTop: 0
    });
  },

 
  scroll: function(t) {
    // console.log(t);
    this.setData({
      indexSearch: t.detail.scrollTop
    }), t.detail.scrollTop > 300 ? this.setData({
      floorstatus: !0
    }) : this.setData({
      floorstatus: !1
    });
  },

  // onShareAppMessage: function() {
  //   return {
  //     title: "净水商城",
  //     desc: "",
  //     path: "/pages/index/index"
  //   };
  // },

  // /**
  //  * 获取公告列表
  //  */
  // getInfoList: function () {
  //   let _this = this;
  //   App._get('wxapp/notice', {}, function (result) {
  //     _this.setData(result.data);
  //     _this.setData({
  //       isShow2: true
  //     });
  //     if (_this.data.isShow || _this.data.isShow2) {
  //       wx.hideLoading();
  //       wx.stopPullDownRefresh();
  //     }
  //   });
  // },

  /**
  * 页面相关事件处理函数--监听用户下拉动作
  */
  onPullDownRefresh: function () {
    console.log(1);
    this.onShow();
    
  },

  /**
   * 跳转至公告页
   */
  goNotice:function(){
    wx.navigateTo({
      url: '/pages/user/notice',
    })
  }

});