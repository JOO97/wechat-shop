// pages/service/index.js
let App = getApp();
let util = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    address: null, // 默认收货地址
    exist_address: false, // 是否存在收货地址
    checked_1:true,
    checked_2:false,
    checked_3:false,
    // date: '2016-09-01',
    startDate:null,
    endDate:null,
    address_id:null,
    disabled:false,
    user_id:null
  },
  
  //提交表单
  formSubmit: function (e) {
    let _this = this;
    console.log(e);
    console.log(this.data.address_id);
    if(_this.data.disabled){
      return false;
    }
    if (_this.data.checked_1==false && _this.data.checked_2==false && _this.data.checked_3==false) {
      wx.showModal({
        title: '提示',
        content: '请选择要预约的服务',
      })
    } else if(e.detail.value.service_msg=='') {
      wx.showModal({
        title: '提示',
        content: '请填写商品型号',
      }) 
      }else{
      _this.setData({
        disabled: true
      });
      let create_time = parseInt(new Date().getTime() / 1000);
      // console.log(create_time);
      let service_no = util.randomNumber();//预约单号
      // console.log(service_no);
      let service_install = e.detail.value.service_install.length == 0 ? 0 : 1;
      let service_repair = e.detail.value.service_repair.length == 0 ? 0 : 1;
      let service_replace = e.detail.value.service_replace.length == 0 ? 0 : 1;
      App._post_form('order/addService', {
        service_date: e.detail.value.service_date,
        service_install: service_install,
        service_repair: service_repair,
        service_replace: service_replace,
        create_time: create_time,
        user_id: _this.data.user_id,
        update_time: create_time,
        address_id: _this.data.address_id,
        service_msg: e.detail.value.service_msg,
        service_no: service_no
      }, function (result) {
          // success
          console.log(result);
          wx.showToast({
            title: result.msg,
            icon: 'success',
            duration: 1500
          });
          setTimeout(function () {
            wx.redirectTo({
              url: '/pages/order/service?user_id='+_this.data.user_id,
            })
          }, 1500)
        }, function (result) {
          App.showError(result.msg)
          _this.setData({
            disabled: false
          });
        },function(){
          
        });
    }
      
  },
//单选框 
  checkboxChange: function (e) {
    // console.log('checkbox发生change事件，携带value值为：', e.detail.value)
    let checked = this.data.checked_1;
    checked=checked==true?false:true;
    this.setData({
      checked_1: checked
    })
    console.log(this.data.checked_1);
  },

  checkboxChange1: function (e) {
    // console.log('checkbox发生change事件，携带value值为：', e.detail.value)
    let checked = this.data.checked_2;
    checked = checked == true ? false : true;
    this.setData({
      checked_2: checked
    })
    console.log(this.data.checked_2);
  },

  checkboxChange2: function (e) {
    // console.log('checkbox发生change事件，携带value值为：', e.detail.value)
    console.log(e);
    let checked = this.data.checked_3;
    checked = checked == true ? false : true;
    this.setData({
      checked_3: checked
    })
    console.log(this.data.checked_3);
  },

  bindDateChange: function (e) {
    // console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      startDate: e.detail.value
    })
  },
  
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
   
  },
  
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    this.setData({
      user_id: App.getUserId(),
      address: null,
      exist_address: false,
      address_id: null
    })
    this.getTime();
    // 获取收货地址列表
    this.getAddressList();
  },

  /**
   * 获取实时时间
   */
  getTime(){
    let _this = this;
    let starttime = new Date().getTime() + 86400000;
    let endtime = starttime + 2592000000;
    starttime = util.transTime(starttime, 2);
    endtime = util.transTime(endtime, 2)
    _this.setData({
      startDate:starttime,
      endDate: endtime
    })
  },

 
  
  /**
   * 获取收货地址列表
   */
  getAddressList: function () {
    let _this = this;
    //获取用户地址列表
    App._get('address/lists', {}, function (result) {
      console.log(result);
      if(result.data.default_id==0&&result.data.list.length==0){
        _this.setData({disabled:true});//无地址时，禁用按钮
        wx.showModal({
          title: '提示',
          content: '您还未添加地址',
        })
      } else{
        for (let i = 0; i < result.data.list.length; i++) {
          //默认选择默认地址
          if (result.data.list[i].address_id == result.data.default_id) {
            _this.setData({
              address: result.data.list[i],
              exist_address: true,
              address_id: result.data.list[i].address_id
            })
            // if (result.data.list[i].region_id <= 1189 && result.data.list[i].region_id >= 1184) {
            //   _this.setData({
            //     disabled: false
            //   })
            // }
            if (result.data.list[i].city === "厦门市") {
              _this.setData({
                disabled: false
              })
            }
            else {
              //如果地址不在范围内,禁用按钮
              _this.setData({
                disabled: true
              })
              wx.showModal({
                title: '提示',
                content: '您的地址不在预约服务范围内!',
              })
            }
          }
        }
      }
      
    });
  },

  /**
    * 选择地址
   */
  selectAddress: function () {
    wx.navigateTo({
      url: '../address/' + (this.data.exist_address ? 'index?from=flow' : 'create')
    });
  },

  /**
  * 正则表达式
  */
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