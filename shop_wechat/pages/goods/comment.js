// pages/goods/comment.js
let App = getApp()
let util = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    // pageIndex:0,
    // pageSize:6,
    goodsComment:[],
    user_id:null,
    isEdit:false,
    allList:null,
    commentCount:null,
    activeType:'all',
    page: 1,
    scrollHeight:null,
    no_more:false,
    scrollTop:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let _this = this;
    _this.data.goods_id = options.goods_id;
    console.log(_this.data.goods_id);
    _this.setListHeight();
    _this.getCommentList(true,1,'all');
    _this.isShow();
  },

  /**
   * 是否显示删除
   */
  isShow(){
    let _this=this;
    if (App.checkIsLogin() == true){
      let user_id = wx.getStorageSync('user_id');
      _this.setData({
        user_id:user_id
      })
    }
  },
  
  /**
   * 编辑
   */
  // edit(e){
  //   let _this = this;
  //   wx.showModal({
  //     title: '提示',
  //     content: '是否编辑该评论',
  //     success(res) {
  //       _this.setData({
  //         isEdit:true
  //       })
  //       // if (res.confirm) {
  //       //   let comment_id = e.currentTarget.dataset.commentid;
  //       //   let order_goods_id = e.currentTarget.dataset.ordergoodsid;
  //       //   App._get('goods/editComment', {
  //       //     comment_id: comment_id,
  //       //     order_goods_id: order_goods_id
  //       //   }, function (result) {
  //       //     console.log(result);
  //       //     wx.showToast({
  //       //       title: '删除成功',
  //       //       icon: 'loading',
  //       //       duration: 1000
  //       //     });
  //       //     // _this.onLoad(_this.data._options);
  //       //   }, function (result) {
  //       //     wx.showToast({
  //       //       title: result.msg,
  //       //       icon: 'loading',
  //       //       duration: 2000
  //       //     });
  //       //   });
  //       // } else if (res.cancel) {
  //       //   // console.log('取消')
  //       // }
  //     }
  //   })
  // },

  /**
   * 删除评论
   */
  // delete(e){
  //   let _this = this;
  //   console.log(e);
  //   wx.showModal({
  //     title: '提示',
  //     content: '是否删除该评论',
  //     success(res) {
  //       if (res.confirm) {//删除评论
  //         let comment_id = e.currentTarget.dataset.commentid;
  //         let order_goods_id = e.currentTarget.dataset.ordergoodsid;
  //         App._get('goods/deleteComment', { 
  //           comment_id: comment_id,
  //           order_goods_id: order_goods_id
  //           }, function (result) {
  //             console.log(result);
  //           wx.showToast({
  //             title: '删除成功',
  //             icon: 'loading',
  //             duration: 1000
  //           });
  //             _this.getCommentList(_this.data.activeType)//删除评论成功后重新加载页面
  //         }, function (result) {
  //           wx.showToast({
  //             title: result.msg,
  //             icon: 'loading',
  //             duration: 2000
  //           });
  //         });
  //       } else if (res.cancel) {//取消删除评论操作

  //       }
  //     }
  //   })
  // },

  /**
   * 获取全部商品评论
   */
  // getCommentList() {
  //   let _this = this;
  //   App._get('goods/commnetList', {
  //     goods_id: _this.data.goods_id,
  //   }, function (result) {
  //     // let info = result.data.list;
  //     console.log(result);
  //     _this.setData({
  //       goodsComment: result.data.list.data,
  //     });
  //     wx.stopPullDownRefresh();
  //   },function(){
  //     wx.navigateBack({
  //       delta:1
  //     })
  //   });
  // },

  /**
   * 获取评论统计数和类型评论
   */
  // getGoodsList: function (is_super, page)
  getCommentList: function(is_super,page,type) {
    wx.showLoading({
      title: '正在加载',
    })
    let _this = this;
    App._get('comment/detail', {
      page: page,
      goods_id: _this.data.goods_id,
      type:type
    }, function (result) {
      // let info = result.data.list;
      let dataList = _this.data.goodsComment;
      let resultList = result.data.allList;
      let commentCount = result.data.commentCount;
      console.log(result);
      console.log(_this.data.goodsComment);
      if (is_super === true || typeof dataList.data === 'undefined'){
        _this.setData({
          goodsComment: resultList,
          commentCount: commentCount
        });
      }else{
        // _this.setData({ 'list.data': dataList.data.concat(resultList.data) });
        _this.setData({
          'goodsComment.data': dataList.data.concat(resultList.data),
          commentCount: commentCount
        })        
      }
      // wx.stopPullDownRefresh();
    }, function () {
      wx.navigateBack({
        delta: 1
      })
    },function(){
      wx.hideLoading();
    });
  },

  
  /**
   * 切换评论类型
   */
  titleChange(e){
    // wx.pageScrollTo({
    //   scrollTop: 0,
    //   duration: 300
    // })
    let _this=this;
    console.log(e);
    let type=e.currentTarget.dataset.type;
    _this.setData({
      activeType: type,
      page:1,
      scrollTop:0
    })
    _this.getCommentList(true,1,type);
    // App._get('comment/type', {
    //   goods_id: _this.data.goods_id,
    //   type:type
    // }, function (result) {
    //   console.log(result);
    //   _this.setData({
    //     goodsComment: result.data.allList.data,
    //     activeType:type
    //   });
    // }, function (result) {
    //   wx.navigateBack({
    //     delta: 1
    //   })
    // });
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
     * 设置商品列表高度
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
    if (this.data.page >= this.data.goodsComment.last_page) {
      wx.showLoading({
        title: '已经到底了',
        duration:400
      })
      this.setData({ no_more: true });
      return false;
    }
    this.getCommentList(false, ++this.data.page);
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
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    console.log(1);
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})