var _urlbase="http://leo1v1.whytouch.com/";
function getCookie(name){ 
var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
if(arr=document.cookie.match(reg)) return unescape(arr[2]); 
else return ""; 
}
var returl=location.href.split('#')[0];
$.ajax({type: "post",url: _urlbase+"wxsignature.php",dataType: 'json',
data: {   returl:returl,key:"whytouch113355"},error: function(data) {},success: function(data) {    startwx(data);}    });
function startwx(wxConfig){
	if(wx == null) return;
var shareimg = _urlbase+"getwxpic.php?tag="+firsttag;		
 wx.config({ debug: false, appId: wxConfig.appId, timestamp: wxConfig.timestamp,nonceStr: wxConfig.nonceStr,signature: wxConfig.signature,jsApiList: ['checkJsApi','onMenuShareTimeline','onMenuShareAppMessage']});
wx.ready(function () { wx.onMenuShareAppMessage({title: document.title,desc: document.title,
link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxbefb2cda01c3f409&redirect_uri=http://ts.whytouch.com/wxforward2.php&response_type=code&scope=snsapi_base&state='+firsttag+"Zleo1v1()"+getCookie("whytouch_token_openid")+"#wechat_redirect",
imgUrl: shareimg, trigger: function (res) {},success: function (res) {/*alert('感谢您的分享，愿您开心快乐每一天！——By TouchShow微演示');*/ },
cancel: function (res) {},fail: function (res) {}
    });wx.onMenuShareTimeline({title: document.title, link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxbefb2cda01c3f409&redirect_uri=http://ts.whytouch.com/wxforward2.php&response_type=code&scope=snsapi_base&state='+firsttag+"Zleo1v1()"+getCookie("whytouch_token_openid")+"#wechat_redirect",imgUrl: shareimg, trigger: function (res) { },  success: function (res) {/*	alert('感谢您的分享，愿您开心快乐每一天！——By TouchShow微演示');*/
  },  cancel: function (res) { },  fail: function (res) { }});  });}
