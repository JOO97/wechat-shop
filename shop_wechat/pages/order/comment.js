// pages/order/comment.js
let App = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    order_id:null,
    pingjia_msg:{},
    pingjia:'',
    pingfen:'5',
    create_time:null,
    order:{},
    goodsinfo:{},
    order_goods_id:null,
    goods_id:null,
    disabled:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.data.order_id = options.order_id;
    this.data.order_goods_id = options.order_goods_id;
    this.getOrderDetail(options.order_id);
    // console.log(this.data.goodsinfo);
    // wx.hideShareMenu();
  },
  

  
 
  /**
   * 根据order_goods_id获取要进行评论商品的信息
   */
  getOrderDetail: function (order_id) {
    let _this = this;
    let order_goods_id = _this.data.order_goods_id;
    App._get('user.order/detail', { order_id }, function (result) {
      let info = result.data.order.goods;
      for(let i = 0;i<info.length;i++){
        if(info[i]["order_goods_id"] == order_goods_id){
          _this.setData({
            goodsinfo:info[i],
            goods_id:info[i].goods_id
          })
        }
      }
      // console.log(result);
      console.log(_this.data.goodsinfo);
    });
    
  },

  /**
   * 获取评价文本
   */
  // getPingjia: function (e) {
  //   var val = e.detail.value;
  //   this.setData({
  //     pingjia: val
  //   });
  //   console.log(this.data.pingjia)
  // },

  regStrFn: function (str) {
    　　let reg = /([^\u0020-\u007E\u00A0-\u00BE\u2E80-\uA4CF\uF900-\uFAFF\uFE30-\uFE4F\uFF00-\uFFEF\u0080-\u009F\u2000-\u201f\u2026\u2022\u20ac\r\n])|(\s)/g,
      　　indexArr = reg.exec(str);
    if (str.match(reg)) {
      　　str = str.replace(reg, '');
    }
    let obj = { val: str, index: indexArr }
    return obj
  },
  inputVal: function (e) {
    　　let name = 'form.' + e.target.dataset.name
    　　let val = e.detail.value,
      　　pos = e.detail.cursor;
    　　let reg = /([^\u0020-\u007E\u00A0-\u00BE\u2E80-\uA4CF\uF900-\uFAFF\uFE30-\uFE4F\uFF00-\uFFEF\u0080-\u009F\u2000-\u201f\u2026\u2022\u20ac\r\n])|(\s)/g
    if (!reg.test(val)) {
      　　return
    }
    　　let obj = this.regStrFn(val)

    if (pos != -1 && obj.index) {
      //计算光标的位置
      　　pos = obj.index.index
    }
    return {
      　　value: obj.val,
      　　cursor: pos
    }
  },
  /**
   * 获取评分文本
   */
  // getPingfen: function (e) {
  //   var val = e.detail.value;
  //   this.setData({
  //     pingfen: val
  //   });
  //   console.log(this.data.pingfen)
  // },


  formSubmit: function (e) {
    let _this = this;
    let create_time = parseInt(new Date().getTime() / 1000);
    
    console.log(create_time);
    console.log(e);
    if (e.detail.value.pingjia=='' || e.detail.value.pingfen==0){
      wx.showModal({
        title: '提示',
        content: '评价不得为空且评论不得为0',
      })
    } else{
      wx.showLoading({
        title: '正在处理',
      })
      _this.setData({
        disabled: true
      });
      App._post_form('order/addComment', {
        order_id: _this.data.order_id,
        pingjia: e.detail.value.pingjia,
        pingfen: e.detail.value.pingfen,
        create_time: create_time,
        order_goods_id: _this.data.order_goods_id,
        goods_id:_this.data.goods_id
      }
        , function (result) {
          // success
          wx.showToast({
            title: result.msg,
            icon: 'success',
            duration: 1000
          });
          setTimeout(function () {
            wx.navigateBack({
              delta:1
            })
            // wx.redirectTo({
            //   url: '/pages/order/index',
            // })
          }, 1000)
        }, function (result) {
          //fail
          wx.showToast({
            title: '发布成功',
            icon: 'fail',
            duration: 1000
          });
          setTimeout(function () {
            _this.setData({
              disabled: false
            });
          }, 1000)
        });
    }
  },

  // commentSubmit:function(){
  //   var _this=this;
  //   var time = new Date().getTime();
  //   // console.log(typeof _this.data.pingfen);
  //   _this.setData({
  //     create_time: time
  //   });
  //   if (_this.data.pingjia=='' || _this.data.pingfen==0){
  //     wx.showModal({
  //       title: '提示',
  //       content: '评价不得为空且评论不得为0',
  //     })
  //   } else{
  //      App._post_form('order/addComment',{
  //        order_id:_this.data.order_id,
  //        pingjia:_this.data.pingjia,
  //        pingfen:_this.data.pingfen,
  //        createtime:_this.data.create_time
  //      }
  //     , function (result) {
  //     // success
  //       wx.showToast({
  //         title: result.msg,
  //         icon: 'loading',
  //         duration: 2000
  //       });
  //       setTimeout(function () {
  //         wx.redirectTo({
  //           url: '/pages/order/index',
  //         })
  //       }, 2000)
  //   },function(result){
  //     //fail
  //       wx.showToast({
  //         title: result.msg,
  //         icon: 'loading',
  //         duration: 2000
  //       });
  //       setTimeout(function () {
  //         wx.redirectTo({
  //           url: '/pages/order/index',
  //         })
  //       }, 2000)
  //   });
  //   }
    

  //   // console.log(this.data.pingjia_msg)
   
  // },
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