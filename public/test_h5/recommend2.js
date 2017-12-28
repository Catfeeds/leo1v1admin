var _urlbase="http://leo1v1.whytouch.com/";
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?19a1273d85526c4810a4c9ac0441a480";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
var intervalId;
if(window._mode<=0){
	intervalId = window.setInterval("checkRich()", 1000);
}
function checkRich(){
	if(document.getElementsByClassName('rich_media_tool').length>0){
		clearInterval(intervalId);
		var rd = document.createElement("div");
		rd.setAttribute("id","movie_rank");
		rd.style.marginTop="20px";
		rd.className = "box2";
		rd.innerHTML="<div class='inner'><span>百度'微演示',PPT/PDF轻松发手机。</span><table class='rank_list' cellspacing='20'></table></div>";
		window.player.appendChild(rd);
		//recommendready();
		//if(window.firsttag=="g50780357dcc6cf97ac9e749d0eab1b7"||window.firsttag=="g482981bf722d282b509a0ddcd27fe89")
		checkWxOpenid();
	}
}
function recommendready(){
	document.getElementById('movie_rank').style.width=document.getElementById('main').style.width;
	document.getElementById('movie_rank').style.zoom=document.getElementById('main').style.zoom;
	var ispc=browserRedirect();
	if(ispc==true){
		document.getElementById('movie_rank').style.display="none";
		//getRecommend();
	}else{
		getRecommend();
	}
}
function browserRedirect() {
	var sUserAgent = navigator.userAgent.toLowerCase();
	var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
	var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
	var bIsMidp = sUserAgent.match(/midp/i) == "midp";
	var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
	var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
	var bIsAndroid = sUserAgent.match(/android/i) == "android";
	var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
	var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
	if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
		return false;
	} else {
		return true;
	}
}
function getRecommend(){
	$.ajax({type: "post",url: _urlbase+"recommend.php",dataType: 'json',
		data:{articleid:window.firsttag},
		error: function(data) {document.getElementById('movie_rank').style.display="none";},
		success: function(data) {
			if(data.error_id!=0||data.recommend.length==0){
				document.getElementById('movie_rank').style.display="none";
				return;
			}
			document.getElementById('movie_rank').style.display="block";
			for(var i=0;i<data.recommend.length;i++){
				var ap = $("<tr><td>"+(i+1)+".<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxbefb2cda01c3f409&redirect_uri=http://ts.whytouch.com/wxforward2.php&response_type=code&scope=snsapi_base&state="+data.recommend[i].uuid+"()"+window.firsttag+"Zleo1v1**"+data.recommend[i].tag+"#wechat_redirect'>"+delExtension(data.recommend[i].filename)+"</a></td></tr>");
				ap.appendTo(".rank_list");
			}
		}    
	});
}
function delExtension(str){
 var reg = /\.\w+$/;
 var name=str.replace(reg,'');
 if(name.length>20)
 {
	 name=name.substr(0,20);
	 name=name+"...";
 }
 return name;
}

var currdate = new Date();
var dcurrdate=currdate.getTimezoneOffset()*60;
var currtime = currdate.getTime();
function getComment(){
	$.ajax({type: "post",url: _urlbase+"comment.php",dataType: 'json',
		data:{aid:window.firsttag,stype:"get"},
		error: function(xhr,status,statusText) {
			console.log("a");
		},
		success: function(data) {
			if(data.error_id==1){

				document.getElementById('comment').style.display="block";
				for(var i=0;i<data.ret.length;i++){
					$("<tr><th rowspan='2'><img src='"+data.ret[i].avatar+"' /></th><td class='nickname'>"+data.ret[i].nickname+"</td></tr><tr class='clearspace'><td class='content'>"+data.ret[i].content+"<br><span class='time'>"+getTimeInterval(data.ret[i].pub_tm)+"</span></td></tr><tr class='addspace'><td colspan='2'></td></tr>").appendTo(".tbcomment");
				}
			}
		}
	});
}
function getTimeInterval(ctime){
	var formattimevalue=(transdate(ctime)-dcurrdate)*1000;
	var intervaltime=(currtime-formattimevalue)/1000;
	if(intervaltime>(3600*24*2)){
		return Math.floor(intervaltime/(24*3600))+"天前";
	}else if(intervaltime>(3600*24)){
		return "昨天";
	}else if(intervaltime>3600){
		return Math.floor(intervaltime/3600)+"小时前";
	}else if(intervaltime>60){
		return Math.floor(intervaltime/60)+"分钟前";
	}else{
		return "刚刚";
	}
}
function transdate(endTime){ 
	var date=new Date(); 
	date.setFullYear(endTime.substring(0,4)); 
	date.setMonth(endTime.substring(5,7)-1); 
	date.setDate(endTime.substring(8,10)); 
	date.setHours(endTime.substring(11,13)); 
	date.setMinutes(endTime.substring(14,16)); 
	date.setSeconds(endTime.substring(17,19)); 
	return Date.parse(date)/1000; 
} 
function formatdate(unix){
	var unixTimestamp = new Date(unix*1000);
	var str = unixTimestamp.toString();
	return unixTimestamp.toLocaleString();
}
function subComment(){
	var str=$("#commentcontent").val();
	if(str==""){
		alert("请填写留言内容！");
		return;
	}
	if(str.length>50){
		alert("留言内容请控制在50字以内！");
		return;
	}
	str=str.replace(/\r/ig," "); 
	str=str.replace(/\n/ig," "); 
	if(getCookie("whytouch_token_openid")!=""){
		$.ajax({type: "post",url: _urlbase+	"comment.php",dataType: 'json',
			data:{aid:window.firsttag,openid:getCookie("whytouch_token_openid"),content:str,stype:"sub"},
			error: function(data) {
				alert("系统正忙，请稍后再试！");
				closesub();
			},
			success: function(data) {
				if(data.error_id>0){
					$("<tr><th rowspan='2'><img src='"+data.avatar+"' /></th><td class='nickname'>"+data.nickname+"</td></tr><tr class='clearspace'><td class='content'>"+data.content+"<br><span class='time'>刚刚</span></td></tr><tr class='addspace'><td colspan='2'></td></tr>").prependTo(".tbcomment");
					closesub();
					if(data.error_id==2){
						$("#showqrcode img").attr("src",_urlbase+"images/qrcode.jpg");
						document.getElementById("showqrcode").style.display="block";
					}
				}else{
					alert("系统正忙，请稍后再留言！ID:"+data.error_id);
				}
			}
		});
	}else{
		
	}
}
function showsub(){
	document.getElementById("subdiv").style.display="block";
	document.getElementById("root").style.display="none";
}
function closesub(){
	document.getElementById("root").style.display="block";
	document.getElementById("subdiv").style.display="none";
	location.href="#comment";
	var viewport = document.querySelector("meta[name=viewport]");
}
function checkWxOpenid(){
	
	if(getCookie("whytouch_token_openid")!=""){
		SettingComment();
		
	}else{
		if(isWeiXin()){
			var wxurl='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxbefb2cda01c3f409&redirect_uri=http://ts.whytouch.com/wxforward2.php&response_type=code&scope=snsapi_base&state='+firsttag+"Zleo1v1()ReGetOpenid#wechat_redirect";
			location.href=wxurl;
		}else if(window.firsttag=="g5718af223107146d17e71f867517806"||window.firsttag=="g1912442bea1ffe761e565035eaa7f94"||window.firsttag=="g482981bf722d282b509a0ddcd27fe89"){
			SettingComment();
		}
	}
}
function SettingComment(){
	//显示评论div
	var cd = document.createElement("div");
	cd.setAttribute("id","comment");
	cd.style.marginTop="50px";
	cd.style.marginLeft="-3px";
	cd.innerHTML="<img src=_urlbase+'images/icon_edit.png' /><a href='javascript:void(0);' onclick='showsub();'>写留言</a><table class='tbcomment' id='tbcomment' border='0'></table>";
	//<div class='box2 tips'><p>评论内容只代表网友观点,与[微演示]立场无关!。</p></div><div style='clear:both;'></div>
	window.player.appendChild(cd);
	
	//获取评论
	getComment();
	
	//设置评论宽度
	$("#comment").width($(window).width());
	document.getElementById('tbcomment').style.width=$("#main").width()*0.95+"px";
	
	//评论div
	$('body').append("<div class='subdiv' id='subdiv'><h2 id='gettitle'>"+document.title+"</h2><span style='display: block;position:static;background-color: #fff;'><textarea class='frm_textarea' id='commentcontent' placeholder='留言将对所有人可见。'></textarea></span><div class='discuss_btn_wrp'><a class='btn btn_close btn_discuss btn_disabled' href='javascript:;' onclick='closesub();'>关闭</a><a class='btn btn_primary btn_discuss btn_disabled' href='javascript:;' onclick='subComment();'>提交</a></div></div>");
	
	//评论未关注公众号提示div
	$('body').append("<div id='showqrcode' style='display:none;position:fixed;width:300px;height:400px;left:50%;top:50%;margin-left:-150px;margin-top:-200px;border-radius:15px;background-color:#eaeaea;z-index:3;'><div style='width:200px;margin-left:50px;margin-top:20px;'><label style='word-wrap:break-word;word-break:break-all;'>关注我们微演示官方公众号您将会获得显示头像以及昵称的特权。<br>[长按二维码识别加关注]</label></div><img src='' width='200px' style='margin-left:50px;margin-top:150px;'/><div class='discuss_btn_wrp close'><a class='btn btn_close btn_discuss btn_disabled' style='width:auto;' href='javascript:;' onclick='document.getElementById(\"showqrcode\").style.display=\"none\";'>关闭</a></div></div>");
}
function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}