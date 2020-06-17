let App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    disabled: false,

    name: '',
    region: '',
    phone: '',
    detail: '',

    error: '',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {

  },

  /**
   * 表单提交
   */
  saveData: function(e) {
    let _this = this,
      values = e.detail.value
    values.region = _this.data.region;
    console.log(values);
    // 按钮禁用
    _this.setData({
      disabled: true
    });
    console.log(_this.data.disabled);
    
    // 表单验证
    if (!_this.validation(values)) {
      App.showError(_this.data.error);
      _this.setData({
        disabled: false
      });
      console.log(_this.data.disabled);
      return false;
    }
    //发起post请求
    App._post_form('address/add', values, function(result) {
      console.log(_this.data.disabled);
      App.showSuccess(result.msg, function() {
        wx.navigateBack();
      });
    }, function(result){
      // 解除禁用
      _this.setData({
        disabled: false
      });
      console.log(_this.data.disabled);
    });
  },

  /**
   * 表单验证
   */
  validation: function(values) {
    if (values.name === '') {
      this.data.error = '收件人不能为空';
      return false;
    }
    var reg = /^[\u4e00-\u9fa5]{2,5}$/;
    if (!reg.test(values.name)) {
      this.data.error = '收货人为2-5个中文字符';
      return false;
    }
    if (values.phone.length < 1) {
      this.data.error = '手机号不能为空';
      return false;
    }
    if (values.phone.length !== 11) {
      this.data.error = '手机号长度有误';
      return false;
    }
    let reg1 = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/;
    if (!reg1.test(values.phone)) {
      this.data.error = '手机号不符合要求';
      return false;
    }
    if (!this.data.region) {
      this.data.error = '省市区不能空';
      return false;
    }
    if (values.detail === '') {
      this.data.error = '详细地址不能为空';
      return false;
    }
    let reg2 = /^[\a-\z\A-\Z0-9\u4E00-\u9FA5\ ]{5,}$/;
    if (!reg2.test(values.detail)){
      this.data.error = '详细地址只能为中文、字母、数字、空格';
      return false;
    }
    return true;
  },

  /**
   * 修改地区
   */
  bindRegionChange: function(e) {
    this.setData({
      region: e.detail.value
    })
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


  // inputVal: function (e) {
  //   let name = 'form.' + e.target.dataset.name
  //   let val = e.detail.value,
  //     pos = e.detail.cursor;
  //   let reg = /([^\u0020-\u007E\u00A0-\u00BE\u2E80-\uA4CF\uF900-\uFAFF\uFE30-\uFE4F\uFF00-\uFFEF\u0080-\u009F\u2000-\u201f\u2026\u2022\u20ac\r\n])|(\s)/g
  //   if (!reg.test(val)) {
  //     return
  //   }
  //   let obj = this.regStrFn(val)

  //   if (pos != -1 && obj.index) {
  //     //计算光标的位置
  //     pos = obj.index.index
  //   }
  //   return {
  //     value: obj.val,
  //     cursor: pos
  //   }
  // },
})