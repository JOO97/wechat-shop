let App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    disabled: false,
    region:null,
    detail: {},

    error: '',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    // 获取当前地址信息
    this.getAddressDetail(options.address_id);
  },

  /**
   * 获取当前地址信息
   */
  getAddressDetail: function(address_id) {
    let _this = this;
    App._get('address/detail', {
      address_id
    }, function(result) {
      console.log(result);
      // region = result.data.detail.region.split(",");
      _this.setData({
          "detail":result.data.detail,
          "region": result.data.detail.region.split(",")
        });
    });
  },

  /**
   * 表单提交
   */
  saveData: function(e) {
    let _this = this,
      values = e.detail.value
    values.region = _this.data.region;

    // 记录formId
    // App.saveFormId(e.detail.formId);

    // 表单验证
    if (!_this.validation(values)) {
      App.showError(_this.data.error);
      return false;
    }

    // 按钮禁用
    _this.setData({
      disabled: true
    });

    // 提交到后端
    values.address_id = _this.data.detail.address_id;
    App._post_form('address/edit', values, function(result) {
      App.showSuccess(result.msg, function() {
        wx.navigateBack();
      });
    }, function(result){
      _this.setData({
        disabled: false
      });
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
      this.data.error = '收件人为2-5个中文字符';
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
    if (!reg2.test(values.detail)) {
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

})