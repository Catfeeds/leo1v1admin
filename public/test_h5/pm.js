var _urlbase="";var _upnum=0;var _readnum=1;var player;var PW;var PH;var PSH;var PTH;var PTW;var PSW;var _margin = 10;var transform_prop="transform";var tr_ori="transform-origin";
var xmlHttpRequest;
function Ajax(method, url, callback) {
if (window.XMLHttpRequest) {
xmlHttpRequest = new XMLHttpRequest();
xmlHttpRequest.callback = callback;
if (xmlHttpRequest.overrideMimeType) {
xmlHttpRequest.overrideMimeType("text/xml");
}
}
if (xmlHttpRequest) {
xmlHttpRequest.onreadystatechange = AjaxCallback;
xmlHttpRequest.open(method, url, true);
xmlHttpRequest.send(null);
}
}
function AjaxCallback() {
	if (xmlHttpRequest.readyState == 4) {
		if (xmlHttpRequest.status == 200) {
			xmlHttpRequest.callback(xmlHttpRequest.responseText);	
		}else{
			xmlHttpRequest.callback("");	
		}
	}
}
function GetCount(){
	var ln = document.getElementById("likenum");
	if(ln != null) return;
	var json={read:0,up:0,vip:0,option:0,wtoption:0}
	
	var d = document.createElement("div");
	d.style.width = "100%";
	
	d.className = "rich_media_tool";
	var tmpstr="";
	tmpstr="微演示";
	
	d.innerHTML= "<span class='media_tool_meta meta_primary'>阅读 "+json.read +"<i class='icon_praise_gray' id='likebtn' onclick='UpNum()'></i><span class='praise_num' id='likenum'>"+json.up+"</span></span>"+tmpstr;

	player.style.width = PSW+"px";
	player.appendChild(d);
}

function getstyle(sname) {
	for (var i=0;i<document.styleSheets.length;i++) {
		var rules;
		if (document.styleSheets[i].cssRules) {
			rules = document.styleSheets[i].cssRules;
		} else {
			rules = document.styleSheets[i].rules;
		}
		for (var j=0;j<rules.length;j++) {
			if (rules[j].selectorText == sname) {
				return rules[j].style;
			}
		}
	}
}
function setZoom() {
	PSH = document.documentElement.clientHeight;
	var zoom;
	var ispc=browserRedirect();
	if(ispc == false){
		PSW=document.documentElement.clientWidth-_margin;
		player.style.width = PSW+"px";
		zoom=PSW/PW;
		getstyle(".pf").zoom = zoom;
	}else{
		PSW=720;
		player.style.width = PSW+"px";
		zoom=PSW/PW;
		getstyle(".pf").zoom = zoom;
	}
}

function Domready(fn){
	if(document.addEventListener){
		document.addEventListener('DOMContentLoaded',function(){
			document.removeEventListener('DOMContentLoaded',arguments.callee,false);
			fn();
		},false);
	}else if(document.attachEvent){
		document.attachEvent('onreadystatechange',function(){
			if(document.readyState=='complete'){
				document.detachEvent('onreadystatechange',arguments.callee);
				fn();
			}
		});
	}
}
Domready(function() {
var agent = navigator.userAgent.toLowerCase();	
if(agent.indexOf("android")>=0 || agent.indexOf("iphone")>=0  || agent.indexOf("ipad")>=0){
transform_prop = "-webkit-transform";
tr_ori="-webkit-transform-origin";
}
player = document.getElementById("main");   
PW = player.offsetWidth;
PH = player.offsetHeight;
setZoom();
player.style.top="0px";
player.style.left="0px";
var d = document.createElement("div");
d.className = "statitle";
d.innerHTML="<h2 class='rich_media_title'>"+_title
		+"</h2><div class='rich_media_meta_list'><em id='post-date' class='rich_media_meta rich_media_meta_text'>"
		+_pubtm+"</em> <a class='rich_media_meta rich_media_meta_link rich_media_meta_nickname' href='"
		"/getauthorlink.php?aut="+_author+"&tag="+window.firsttag+"' id='post-user'>"
		+_author+"</a> </div>";
	d.style.width = PSW+"px";
	player.insertBefore(d, player.firstChild);
	document.body.style.zoom=1;
	player.style["margin-left"]=(_margin/2-2)+"px";
	GetCount();
	setTimeout(function(){
		player.style.visibility = "";
		var loading=document.getElementById("loading");
		loading.style.display = "none";
		}, 1000);

});