// 时间戳转换成具体时间
function transTime(unixtime,type) {
  var dateTime = new Date(parseInt(unixtime))
  var year = dateTime.getFullYear();
  var month = dateTime.getMonth() + 1 < 10 ? '0' + (dateTime.getMonth() + 1) : dateTime.getMonth() + 1;
  var day = dateTime.getDate() < 10 ? '0' + (dateTime.getDate()) : dateTime.getDate();
  if(type==1){
    var hour = dateTime.getHours() < 10 ? '0' + (dateTime.getHours()) : dateTime.getHours();
    var minute = dateTime.getMinutes() < 10 ? '0' + (dateTime.getMinutes()) : dateTime.getMinutes();
    var second = dateTime.getSeconds() < 10 ? '0' + (dateTime.getSeconds()) : dateTime.getSeconds();
    var timeSpanStr = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
  } else{
    var timeSpanStr = year + '-' + month + '-' + day;
  }
  var now = new Date();
  var now_new = Date.parse(now.toDateString());
  var milliseconds = now_new - dateTime;
  return timeSpanStr;
}


/**
 * 生成订单号
 */
function randomNumber() {
  var orderCode = '';
  for (var i = 0; i < 6; i++) //6位随机数，用以加在时间戳后面。
  {
    orderCode += Math.floor(Math.random() * 10);
  }
  orderCode = new Date().getTime() + orderCode;  //时间戳，用来生成订单号。
  // console.log(orderCode)
  return orderCode;
}


/**
 * 时间戳转换成日期
 */
function timestampToTime2(time) {
  var date = new Date(parseInt(time))
  // var date = time;
  var y = date.getFullYear() + '-';
  var m = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
  var d = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate());
  return y + m + d;
}

/**
 * 日期转换成时间戳
 */
function dateToTime(time) {
  // var time = '2019-05-20';
  var repTime = time.replace(/-/g, '/');//用正则主要是把“2019-05-20 00:00:00”转换成“2019/05/0 00:00:00”
  // console.log("返回时间：" + repTime);
  var timeTamp = Date.parse(repTime);
  // console.log("返回时间戳：" + timeTamp);
  return timeTamp;
}

/**
 * 判断是否取消
 */
function isCancel(time) {
  //预约时间
  let orderDate = dateToTime(time);//预约时间-时间戳
  console.log('预约时间戳'+orderDate);
  let nowDate = new Date().getTime();//当前时间-时间戳
  console.log('当前时间戳'+nowDate);
  let nowDate2 = timestampToTime2(nowDate);//当前时间-日期格式
  console.log('当前日期'+nowDate2);
  //当前日期等于预定日期 或 当前时间戳<=预定时间戳,则不可以取消订单,返回false
  if (nowDate2 === time || orderDate<=nowDate) {
    return false;
  } else {
    return true;
  }
}





module.exports = {
  transTime: transTime,
  randomNumber: randomNumber,
  dateToTime: dateToTime,
  isCancel: isCancel
}