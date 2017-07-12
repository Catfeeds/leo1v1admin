
<!DOCTYPE html>
<!--headTrap<body></body>
<head></head>
<html></html>
-->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">

    <script nonce="459565645" type="text/javascript">
         window.logs = {
             pagetime: {}
         };
         window.logs.pagetime['html_begin'] = (+new Date());
        </script>

    <script nonce="459565645" type="text/javascript">
         var biz = "MzI5MDQzMTAxNQ=="||"";
         var sn = "" || ""|| "acabbf09a23888783b36f116b6dcc1ef";
         var mid = "100000303" || ""|| "100000303";
         var idx = "1" || "" || "1";
         window.__allowLoadResFromMp = true; 
         
        </script>
    <script nonce="459565645" type="text/javascript">
         var page_begintime=+new Date,is_rumor="",norumor="";
         1*is_rumor&&!(1*norumor)&&biz&&mid&&(document.referrer&&-1!=document.referrer.indexOf("mp.weixin.qq.com/mp/rumor")||(location.href="http://mp.weixin.qq.com/mp/rumor?action=info&__biz="+biz+"&mid="+mid+"&idx="+idx+"&sn="+sn+"#wechat_redirect")),
             document.domain="qq.com";
        </script>
    <script nonce="459565645" type="text/javascript">
         var MutationObserver=window.WebKitMutationObserver||window.MutationObserver||window.MozMutationObserver,isDangerSrc=function(t){
             if(t){
                 var e=t.match(/http(?:s)?:\/\/([^\/]+?)(\/|$)/);
                 if(e&&!/qq\.com(\:8080)?$/.test(e[1])&&!/weishi\.com$/.test(e[1]))return!0;
             }
             return!1;
         },ishttp=0==location.href.indexOf("http://");
                 -1==location.href.indexOf("safe=0")&&ishttp&&"function"==typeof MutationObserver&&"mp.weixin.qq.com"==location.host&&(window.__observer_data={
                     count:0,
                     exec_time:0,
                     list:[]
                 },window.__observer=new MutationObserver(function(t){
                     window.__observer_data.count++;
                     var e=new Date,r=[];
                     t.forEach(function(t){
                         for(var e=t.addedNodes,o=0;o<e.length;o++){
                             var n=e[o];
                             if("SCRIPT"===n.tagName){
                                 var i=n.src;
                                 isDangerSrc(i)&&(window.__observer_data.list.push(i),r.push(n)),!i&&window.__nonce_str&&n.getAttribute("nonce")!=window.__nonce_str&&(window.__observer_data.list.push("inlinescript_without_nonce"),
                                                                                                                                                                       r.push(n));
                             }
                         }
                     });
                     for(var o=0;o<r.length;o++){
                         var n=r[o];
                         n.parentNode&&n.parentNode.removeChild(n);
                     }
                     window.__observer_data.exec_time+=new Date-e;
                 }),window.__observer.observe(document,{
                     subtree:!0,
                     childList:!0
                 })),function(){
                     if(-1==location.href.indexOf("safe=0")&&Math.random()<.01&&ishttp&&HTMLScriptElement.prototype.__lookupSetter__&&"undefined"!=typeof Object.defineProperty){
                         window.__danger_src={
                             xmlhttprequest:[],
                             script_src:[],
                             script_setAttribute:[]
                         };
                         var t="$"+Math.random();
                         HTMLScriptElement.prototype.__old_method_script_src=HTMLScriptElement.prototype.__lookupSetter__("src"),
                             HTMLScriptElement.prototype.__defineSetter__("src",function(t){
                                 t&&isDangerSrc(t)&&window.__danger_src.script_src.push(t),this.__old_method_script_src(t);
                             });
                         var e="element_setAttribute"+t;
                         Object.defineProperty(Element.prototype,e,{
                             value:Element.prototype.setAttribute,
                             enumerable:!1
                         }),Element.prototype.setAttribute=function(t,r){
                             "SCRIPT"==this.tagName&&"src"==t&&isDangerSrc(r)&&window.__danger_src.script_setAttribute.push(r),
                             this[e](t,r);
                         };
                     }
                 }();
        </script>

    <link rel="dns-prefetch" href="//res.wx.qq.com">
    <link rel="dns-prefetch" href="//mmbiz.qpic.cn">
    <link rel="shortcut icon" type="image/x-icon" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/favicon22c41b.ico">
    <script nonce="459565645" type="text/javascript">
         String.prototype.html = function(encode) {
             var replace =["&#39;", "'", "&quot;", '"', "&nbsp;", " ", "&gt;", ">", "&lt;", "<", "&amp;", "&", "&yen;", "¥"];
             if (encode) {
                 replace.reverse();
             }
             for (var i=0,str=this;i< replace.length;i+= 2) {
                 str=str.replace(new RegExp(replace[i],'g'),replace[i+1]);
             }
             return str;
         };
         
         window.isInWeixinApp = function() {
             return /MicroMessenger/.test(navigator.userAgent);
         };
         
         window.getQueryFromURL = function(url) {
             url = url || 'http://qq.com/s?a=b#rd'; 
             var tmp = url.split('?'),
             query = (tmp[1] || "").split('#')[0].split('&'),
             params = {};
             for (var i=0; i<query.length; i++) {
                 var arg = query[i].split('=');
                 params[arg[0]] = arg[1];
             }
             if (params['pass_ticket']) {
                   params['pass_ticket'] = encodeURIComponent(params['pass_ticket'].html(false).html(false).replace(/\s/g,"+"));
             }
             return params;
         };
         
         (function() {
               var params = getQueryFromURL(location.href);
             window.uin = params['uin'] || "" || '';
             window.key = params['key'] || "" || '';
             window.wxtoken = params['wxtoken'] || '';
             window.pass_ticket = params['pass_ticket'] || '';
         })();
         
         function wx_loaderror() {
             if (location.pathname === '/bizmall/reward') {
                 new Image().src = '/mp/jsreport?key=96&content=reward_res_load_err&r=' + Math.random();
             }
         }
         
        </script>

    <title>【老师】常见问题处理方法</title>

    <style>html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;line-height:1.6}body{-webkit-touch-callout:none;font-family:-apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;background-color:#f3f3f3;line-height:inherit}body.rich_media_empty_extra{background-color:#fff}body.rich_media_empty_extra .rich_media_area_primary:before{display:none}h1,h2,h3,h4,h5,h6{font-weight:400;font-size:16px}*{margin:0;padding:0}a{color:#607fa6;text-decoration:none}.rich_media_inner{font-size:16px;word-wrap:break-word;-webkit-hyphens:auto;-ms-hyphens:auto;hyphens:auto}.rich_media_area_primary{position:relative;padding:20px 15px 15px;background-color:#fff}.rich_media_area_primary:before{content:" ";position:absolute;left:0;top:0;width:100%;height:1px;border-top:1px solid #e5e5e5;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5);top:auto;bottom:-2px}.rich_media_area_primary .original_img_wrp{display:inline-block;font-size:0}.rich_media_area_primary .original_img_wrp .tips_global{display:block;margin-top:.5em;font-size:14px;text-align:right;width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.rich_media_area_extra{padding:0 15px 0}.rich_media_title{margin-bottom:10px;line-height:1.4;font-weight:400;font-size:24px}.rich_media_meta_list{margin-bottom:18px;line-height:20px;font-size:0}.rich_media_meta_list em{font-style:normal}.rich_media_meta{display:inline-block;vertical-align:middle;margin-right:8px;margin-bottom:10px;font-size:16px}.meta_original_tag{display:inline-block;vertical-align:middle;padding:1px .5em;border:1px solid #9e9e9e;color:#8c8c8c;border-top-left-radius:20% 50%;-moz-border-radius-topleft:20% 50%;-webkit-border-top-left-radius:20% 50%;border-top-right-radius:20% 50%;-moz-border-radius-topright:20% 50%;-webkit-border-top-right-radius:20% 50%;border-bottom-left-radius:20% 50%;-moz-border-radius-bottomleft:20% 50%;-webkit-border-bottom-left-radius:20% 50%;border-bottom-right-radius:20% 50%;-moz-border-radius-bottomright:20% 50%;-webkit-border-bottom-right-radius:20% 50%;font-size:15px;line-height:1.1}.meta_enterprise_tag img{width:30px;height:30px!important;display:block;position:relative;margin-top:-3px;border:0}.rich_media_meta_text{color:#8c8c8c}span.rich_media_meta_nickname{display:none}.rich_media_thumb_wrp{margin-bottom:6px}.rich_media_thumb_wrp .original_img_wrp{display:block}.rich_media_thumb{display:block;width:100%}.rich_media_content{overflow:hidden;color:#3e3e3e}.rich_media_content *{max-width:100%!important;box-sizing:border-box!important;-webkit-box-sizing:border-box!important;word-wrap:break-word!important}.rich_media_content p{clear:both;min-height:1em}.rich_media_content em{font-style:italic}.rich_media_content fieldset{min-width:0}.rich_media_content .list-paddingleft-2{padding-left:30px}.rich_media_content blockquote{margin:0;padding-left:10px;border-left:3px solid #dbdbdb}img{height:auto!important}@media screen and (device-aspect-ratio:2/3),screen and (device-aspect-ratio:40/71){.meta_original_tag{padding-top:0}}@media(min-device-width:375px) and (max-device-width:667px) and (-webkit-min-device-pixel-ratio:2){.mm_appmsg .rich_media_inner,.mm_appmsg .rich_media_meta,.mm_appmsg .discuss_list,.mm_appmsg .rich_media_extra,.mm_appmsg .title_tips .tips{font-size:17px}.mm_appmsg .meta_original_tag{font-size:15px}}@media(min-device-width:414px) and (max-device-width:736px) and (-webkit-min-device-pixel-ratio:3){.mm_appmsg .rich_media_title{font-size:25px}}@media screen and (min-width:1024px){.rich_media{width:740px;margin-left:auto;margin-right:auto}.rich_media_inner{padding:20px}body{background-color:#fff}}@media screen and (min-width:1025px){body{font-family:"Helvetica Neue",Helvetica,"Hiragino Sans GB","Microsoft YaHei",Arial,sans-serif}.rich_media{position:relative}.rich_media_inner{background-color:#fff;padding-bottom:100px}}.radius_avatar{display:inline-block;background-color:#fff;padding:3px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;overflow:hidden;vertical-align:middle}.radius_avatar img{display:block;width:100%;height:100%;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;background-color:#eee}.cell{padding:.8em 0;display:block;position:relative}.cell_hd,.cell_bd,.cell_ft{display:table-cell;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space:nowrap}.cell_primary{width:2000px;white-space:normal}.flex_cell{padding:10px 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.flex_cell_primary{width:100%;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;box-flex:1;flex:1}.original_tool_area{display:block;padding:.75em 1em 0;-webkit-tap-highlight-color:rgba(0,0,0,0);color:#3e3e3e;border:1px solid #eaeaea;margin:20px 0}.original_tool_area .tips_global{position:relative;padding-bottom:.5em;font-size:15px}.original_tool_area .tips_global:after{content:" ";position:absolute;left:0;bottom:0;right:0;height:1px;border-bottom:1px solid #dbdbdb;-webkit-transform-origin:0 100%;transform-origin:0 100%;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.original_tool_area .radius_avatar{width:27px;height:27px;padding:0;margin-right:.5em}.original_tool_area .radius_avatar img{height:100%!important}.original_tool_area .flex_cell_bd{width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.original_tool_area .flex_cell_ft{font-size:14px;color:#8c8c8c;padding-left:1em;white-space:nowrap}.original_tool_area .icon_access:after{content:" ";display:inline-block;height:8px;width:8px;border-width:1px 1px 0 0;border-color:#cbcad0;border-style:solid;transform:matrix(0.71,0.71,-0.71,0.71,0,0);-ms-transform:matrix(0.71,0.71,-0.71,0.71,0,0);-webkit-transform:matrix(0.71,0.71,-0.71,0.71,0,0);position:relative;top:-2px;top:-1px}.weui_loading{width:20px;height:20px;display:inline-block;vertical-align:middle;-webkit-animation:weuiLoading 1s steps(12,end) infinite;animation:weuiLoading 1s steps(12,end) infinite;background:transparent url(data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iciIgd2lkdGg9JzEyMHB4JyBoZWlnaHQ9JzEyMHB4JyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj4KICAgIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJub25lIiBjbGFzcz0iYmsiPjwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjRTlFOUU5JwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoMCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICA8L3JlY3Q+CiAgICA8cmVjdCB4PSc0Ni41JyB5PSc0MCcgd2lkdGg9JzcnIGhlaWdodD0nMjAnIHJ4PSc1JyByeT0nNScgZmlsbD0nIzk4OTY5NycKICAgICAgICAgIHRyYW5zZm9ybT0ncm90YXRlKDMwIDUwIDUwKSB0cmFuc2xhdGUoMCAtMzApJz4KICAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0naW5kZWZpbml0ZScvPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyM5Qjk5OUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSg2MCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9J2luZGVmaW5pdGUnLz4KICAgIDwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjQTNBMUEyJwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoOTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNBQkE5QUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxMjAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCMkIyQjInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxNTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCQUI4QjknCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxODAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDMkMwQzEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyMTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDQkNCQ0InCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEMkQyRDInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEQURBREEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNFMkUyRTInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0Pgo8L3N2Zz4=) no-repeat;-webkit-background-size:100%;background-size:100%}@-webkit-keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}@keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}.gif_img_wrp{display:inline-block;font-size:0;position:relative;font-weight:400;font-style:normal;text-indent:0;text-shadow:none 1px 1px rgba(0,0,0,0.5)}.gif_img_wrp img{vertical-align:top}.gif_img_tips{background:rgba(0,0,0,0.6)!important;filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#99000000',endcolorstr = '#99000000');border-top-left-radius:1.2em 50%;-moz-border-radius-topleft:1.2em 50%;-webkit-border-top-left-radius:1.2em 50%;border-top-right-radius:1.2em 50%;-moz-border-radius-topright:1.2em 50%;-webkit-border-top-right-radius:1.2em 50%;border-bottom-left-radius:1.2em 50%;-moz-border-radius-bottomleft:1.2em 50%;-webkit-border-bottom-left-radius:1.2em 50%;border-bottom-right-radius:1.2em 50%;-moz-border-radius-bottomright:1.2em 50%;-webkit-border-bottom-right-radius:1.2em 50%;line-height:2.3;font-size:11px;color:#fff;text-align:center;position:absolute;bottom:10px;left:10px;min-width:65px}.gif_img_tips.loading{min-width:75px}.gif_img_tips i{vertical-align:middle;margin:-0.2em .73em 0 -2px}.gif_img_play_arrow{display:inline-block;width:0;height:0;border-width:8px;border-style:dashed;border-color:transparent;border-right-width:0;border-left-color:#fff;border-left-style:solid;border-width:5px 0 5px 8px}.gif_img_loading{width:14px;height:14px}i.gif_img_loading{margin-left:-4px}.gif_bg_tips_wrp{position:relative;height:0;line-height:0;margin:0;padding:0}.gif_bg_tips_wrp .gif_img_tips_group{position:absolute;top:0;left:0;z-index:9999}.gif_bg_tips_wrp .gif_img_tips_group .gif_img_tips{top:0;left:0;bottom:auto}.rich_media_global_msg{position:fixed;top:0;left:0;right:0;padding:1em 35px 1em 15px;z-index:2;background-color:#c6e0f8;color:#8c8c8c;font-size:13px}.rich_media_global_msg .icon_closed{position:absolute;right:15px;top:50%;margin-top:-5px;line-height:300px;overflow:hidden;-webkit-tap-highlight-color:rgba(0,0,0,0);background:transparent url(//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_appmsg_msg_closed_sprite.2x2eb52b.png) no-repeat 0 0;width:11px;height:11px;vertical-align:middle;display:inline-block;-webkit-background-size:100% auto;background-size:100% auto}.rich_media_global_msg .icon_closed:active{background-position:0 -17px}.preview_appmsg .rich_media_title{margin-top:1.9em}@media screen and (min-width:1024px){.rich_media_global_msg{position:relative;margin:0 20px}.preview_appmsg .rich_media_title{margin-top:0}}.weapp_element,.weapp_display_element,.mp-miniprogram{display:block;margin:1em 0}.share_audio_context{margin:16px 0}</style>
    <style></style>
    <!--[if lt IE 9]>
    <link onerror="wx_loaderror(this)" rel="stylesheet" type="text/css" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2c9cd6.css">
    <![endif]-->

</head>
<body id="activity-detail" class="zh_CN mm_appmsg">

    <script nonce="459565645" type="text/javascript">var write_sceen_time = (+new Date());</script>

    <div id="js_article" class="rich_media preview_appmsg">

        <div id="js_top_ad_area" class="top_banner"></div>

        <div class="rich_media_inner">
            <div id="page-content" class="rich_media_area_primary">

                <div id="img-content">

                    <h2 class="rich_media_title" id="activity-name">【老师】常见问题处理方法</h2>
                    <div class="rich_media_meta_list"> <em id="post-date" class="rich_media_meta rich_media_meta_text">2017-05-19</em> <em class="rich_media_meta rich_media_meta_text">理优产品经理</em>
                        <a class="rich_media_meta rich_media_meta_link rich_media_meta_nickname" href="##" id="post-user">理优1对1老师帮</a>
                        <span class="rich_media_meta rich_media_meta_text rich_media_meta_nickname">理优1对1老师帮</span>

                        <div id="js_profile_qrcode" class="profile_container" style="display:none;">
                            <div class="profile_inner"> <strong class="profile_nickname">理优1对1老师帮</strong>
                                <img class="profile_avatar" id="js_profile_qrcode_img" src="" alt="">

                                <p class="profile_meta">
                                    <label class="profile_meta_label">微信号</label>
                                    <span class="profile_meta_value"></span>
                                </p>

                                <p class="profile_meta">
                                    <label class="profile_meta_label">功能介绍</label>
                                    <span class="profile_meta_value">
                                        发布老师新资讯，新活动。定期发布教研素材供老师们学习讨论。表彰优秀教案，表彰优秀教师，提供大量的优秀教学案例。定期开展活动丰富老师们的生活。
                                    </span>
                                </p>

                            </div>
                            <span class="profile_arrow_wrp" id="js_profile_arrow_wrp"> <i class="profile_arrow arrow_out"></i> <i class="profile_arrow arrow_in"></i>
                            </span>
                        </div>
                    </div>

                    <div class="rich_media_content " id="js_content">

                        <section data-role="outer" label="Powered by 135editor.com" style="font-size: 16px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; font-family: 微软雅黑;">
                            <section data-author="Wxeditor">
                                <article class="yead_editor yead-selected" data-author="Wxeditor" style="margin: 5px auto; font-size: 14px; border: 0px;">
                                    <section class="yead_bdc" style="padding: 5px; border: 1px solid rgb(0, 166, 255); font-size: 16px; border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px;">
                                        <section class="yead_bdc" style="padding: 5px; border: 1px dashed rgb(0, 166, 255); border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px;">
                                            <section style="margin: 5px;">
                                                <p style="line-height: 30px; text-align: center; border-color: rgb(60, 40, 34);">
                                                    <span style="color: rgb(0, 166, 255);"> <strong>常见问题处理方式</strong>
                                                    </span>
                                                </p>
                                                <p style="color: rgb(60, 40, 34); line-height: 30px; border-color: rgb(60, 40, 34);">
                                                    <span class="yead_color" style="color: rgb(0, 166, 255);">1、薪资问题 &nbsp; &nbsp;&nbsp;</span>
                                                </p>
                                                <p style="color: rgb(60, 40, 34); line-height: 30px; border-color: rgb(60, 40, 34);">
                                                    <span class="yead_color" style="color: rgb(0, 166, 255);">2、软件下载／登录问题 &nbsp;</span>
                                                </p>
                                                <p style="color: rgb(60, 40, 34); line-height: 30px; border-color: rgb(60, 40, 34);">
                                                    <span class="yead_color" style="color: rgb(0, 166, 255);">3、课前问题 &nbsp; &nbsp;</span>
                                                </p>
                                                <p style="color: rgb(60, 40, 34); line-height: 30px; border-color: rgb(60, 40, 34);">
                                                    <span class="yead_color" style="color: rgb(0, 166, 255);">4、课堂问题</span>
                                                </p>
                                                <p style="color: rgb(60, 40, 34); line-height: 30px; border-color: rgb(60, 40, 34);">
                                                    <span class="yead_color" style="color: rgb(0, 166, 255);">5、课后问题 &nbsp; &nbsp;&nbsp;</span>
                                                </p>
                                                <p style="color: rgb(60, 40, 34); line-height: 30px; border-color: rgb(60, 40, 34);">
                                                    <span class="yead_color" style="color: rgb(0, 166, 255);">6、设备问题</span>
                                                </p>
                                                <p style="color: rgb(60, 40, 34); line-height: 30px; border-color: rgb(60, 40, 34);">
                                                    <span class="yead_color" style="color: rgb(0, 166, 255);">7、万能四步</span>
                                                </p>
                                            </section>
                                        </section>
                                    </section>
                                </article>
                                <p>
                                    <br  />
                                </p>
                            </section>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center; line-height: normal;">
                                <strong>薪资问题</strong>
                            </p>
                            <section style="margin: 2px 1em; font-family: 微软雅黑; orphans: 2; white-space: normal; widows: 2; font-size: 12px; border: 0px rgb(33, 33, 34); color: rgb(62, 62, 62); text-align: center; box-sizing: border-box;">
                                <section data-width="40%" style="padding-top: 2px; padding-right: 2px; padding-left: 2px; width: 212.797px; border-top-width: 3px; border-top-style: solid; border-top-color: rgb(33, 33, 34); color: rgb(0, 0, 0); font-size: 14px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(33, 33, 34); display: inline-block; box-sizing: border-box;"></section>
                            </section>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">▼</span>
                            </p>
                            <section class="" style="margin-top: 0.8em; margin-bottom: 0.8em; font-family: 微软雅黑; font-size: 1em; orphans: 2; white-space: normal; widows: 2; line-height: 1.4; border: 0px; box-sizing: border-box;">
                                <section class="" style="padding: 10px; border: 1px solid transparent; font-size: 14px; text-decoration: inherit; color: rgb(255, 255, 255); background-color: rgb(11, 206, 255); box-sizing: border-box;">
                                    <section class="" style="text-align: center;">
                                        <strong>薪资问题</strong>
                                    </section>
                                </section>
                            </section>
                            <table>
                                <tbody>
                                    <tr class="firstRow">
                                        <td valign="middle" width="28" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align:center">
                                                <span style="white-space: pre-wrap; font-size: 20px;">
                                                    <strong>目录</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                        <td valign="middle" width="515" style="margin: 5px 10px; padding-right: 20px; padding-left: 20px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❶ &nbsp;薪资相关问题</span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❷</span>
                                                <span style="font-size: 12px;">工资申诉在哪里？</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong><span style="letter-spacing: 5px;">❶</span></strong> 
                                                    <strong><span style="letter-spacing: 5px;"></span>
                                                        薪资相关问题</strong> 
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：请勿在群内讨论任何关于薪资问题； 理优有专门反馈薪资/扣款的申诉渠道 具体操作如下：&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;1、关注【理优1对1老师帮】公众号&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;2、点击【个人中心】&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;3、进入【我的薪资】&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;4、点击【总薪资】&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;5、找到有疑问的条目&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;6、点击【添加申诉】即可</span>
                                                <span style="font-size: 14px;">。</span>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="font-size: 20px; letter-spacing: 5px;"></span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        工资申诉在哪里？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：对薪资有任何疑问，可在【理优1对1老师帮】-【个人中心】—【我的薪资】-【总薪资】的对应课程中点击申诉功能进行申诉。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center; line-height: normal;">
                                <strong>
                                    <br  />
                                </strong>
                            </p>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center; line-height: normal;">
                                <strong>软件下载／登录问题</strong>
                            </p>
                            <section style="margin: 2px 1em; font-family: 微软雅黑; orphans: 2; white-space: normal; widows: 2; font-size: 12px; border: 0px rgb(33, 33, 34); color: rgb(62, 62, 62); text-align: center; box-sizing: border-box;">
                                <section data-width="40%" style="padding-top: 2px; padding-right: 2px; padding-left: 2px; width: 212.797px; border-top-width: 3px; border-top-style: solid; border-top-color: rgb(33, 33, 34); color: rgb(0, 0, 0); font-size: 14px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(33, 33, 34); display: inline-block; box-sizing: border-box;"></section>
                            </section>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">▼</span>
                            </p>
                            <section class="" style="margin-top: 0.8em; margin-bottom: 0.8em; font-family: 微软雅黑; font-size: 1em; orphans: 2; white-space: normal; widows: 2; line-height: 1.4; border: 0px; box-sizing: border-box;">
                                <section class="" style="padding: 10px; border: 1px solid transparent; font-size: 14px; text-decoration: inherit; color: rgb(255, 255, 255); background-color: rgb(11, 206, 255); box-sizing: border-box;">
                                    <section class="" style="text-align: center;">
                                        <strong>教师培训问题</strong>
                                    </section>
                                </section>
                            </section>
                            <table>
                                <tbody>
                                    <tr class="firstRow">
                                        <td valign="middle" width="28" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="white-space: pre-wrap; font-size: 20px;">
                                                    <strong>目录</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                        <td valign="middle" width="515" style="margin: 5px 10px; padding-left: 20px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❶ 后台链接</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    学生端的有电脑版吗？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❸</span>
                                                    用平板上课，支持什么系统
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❹</span>
                                                    新版教师端在哪里下载，怎样判断自己的教师端（PC/iPad）已经更新到最新版？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❺</span>
                                                    软件在哪里升级？
                                                </span>
                                                <span style="font-size: 14px;">&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❻</span>
                                                    请问iPad老师端在哪里下载？[平板下载地址]
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❼</span>
                                                    下载时一直显示等待中
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❽</span>
                                                    下载一半后突然消失
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❾</span>
                                                    下载完成后提示网络链接失败
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❿</span>
                                                    iPad安装过程中的信任问题如何处理？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">⓫</span>
                                                    PC客户端安装时弹出C盘错误&nbsp;
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">⓬</span>
                                                    理优老师端.exe不是有效的win32应用程序&nbsp;
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">⓭</span>
                                                    登录教师端账号和密码问题？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">⓮</span>
                                                    登录软件时提示“用户名或密码不正确”
                                                </span>
                                                <span style="font-size: 12px;">&nbsp;</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        后台链接
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：理优老师后台：
                                                    <a href="http://www.leo1v1.com/login/teacher">http://www.leo1v1.com/login/teacher</a>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        学生端的有电脑版吗？
                                                    </strong>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：学生电脑版官网下载地址：
                                                    <a href="http://leo1v1.com/download.html">http://leo1v1.com/download.html</a>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❸</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        用平板上课，支持什么系统。
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：目前只支持苹果ios操作系统。（必须是ios8以上）</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp;&nbsp;</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❹</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>新版教师端在哪里下载，怎样判断自己的教师端（PC/iPad）已经更新到最新版？</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">客户端下载链接：</span>
                                                
                                                    <a href="http://www.leo1v1.com/login/teacher">http://www.leo1v1.com/login/teacher</a>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">下载页面会有当前的版本号</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">【PC端】版本号查看请见理优教师端软件的左下角；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">【iPad端】查看【设置】,会有版本更新提示。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❺</span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        软件在哪里升级？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px;">
                                                    PC电脑版需在后台下载新安装包进行替换。下载地址：
                                                    <a href="http://www.leo1v1.com/common/download">http://www.leo1v1.com/common/download</a>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">iPad版可在弹出更新提示后直接更新，或点击设置中的检测新版本进行更新。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❻</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        请问iPad老师端在哪里下载？[平板下载地址]
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">1.扫描老师后台【下载中心】【iPad版下载】中的二维码进行下载。</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    <span class="Apple-tab-span" style="white-space: pre;"></span>
                                                    2.扫描【理优1对1老师帮】【帮助中心】【使用手册】下载教程中的二维码进行下载。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❼</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        下载时一直显示等待中
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">a、存储空间满了；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; b、网络问题需更换网络（重启路由、开4G）</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❽</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        下载一半后突然消失
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">a、使用新的二维码下载；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; b、网络问题需更换网络（重启路由、开4G）</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❾</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        下载完成后提示网络链接失败
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：在【设置】-【无线局域网】-使用无线局域网与蜂窝移动的应用中选择相关的应用点击允许使用。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❿</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        iPad安装过程中的信任问题如何处理？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">a、点击“理优1对1老师端”图标</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; b、点击“取消”关闭弹框</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; c、点击“设置”图标进行设置</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; d、选择“通用”类目下方的“描述文件”</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    &nbsp; &nbsp; &nbsp; e、点击企业级应用中的“Shanghai Leo Education Technology Co.,Ltd.”
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    &nbsp; &nbsp; &nbsp; f、点击“信任‘Shanghai Leo Education Technology Co.,Ltd.’ ”
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp;g、弹出弹框后选择“信任”</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    &nbsp; &nbsp; &nbsp;h、点击“Home键（iPad上的圆形按钮）”回到iPad桌面，再次点击“理优1对1老师端”即可进入
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">⓫</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>PC客户端安装时弹出C盘错误</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：C盘内存不足，换盘安装。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">⓬</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        理优老师端.exe不是有效的win32应用程序。
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：XP操作系统不支持安装，这个系统版本对于软件的兼容性太差 建议更换win10</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">⓭</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>登录教师端账号和密码问题？</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：各位老师请知悉：新师培训以及后期实际教学要使用的均为您的正式账号也即您本人在理优平台的注册手机号，密码详见您之前所收到的试讲通过的短信通知，若未收到此通知请及时联系理优教务老师哦。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">⓮</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        登录软件时提示“用户名或密码不正确”
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：1.</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">先确认账号密码是否正确</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; 2.确认当前软件是否为老师端</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; 3.点击忘记密码进行重置。</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    <br  />
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-size: 16px; white-space: normal; font-family: 微软雅黑; font-variant-ligatures: normal; orphans: 2; widows: 2; text-align: center; line-height: normal;">
                                <strong>
                                    <br  />
                                </strong>
                            </p>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-size: 16px; white-space: normal; font-family: 微软雅黑; font-variant-ligatures: normal; orphans: 2; widows: 2; text-align: center; line-height: normal;">
                                <strong>课前问题</strong>
                            </p>
                            <section style="margin: 2px 1em; white-space: normal; font-family: 微软雅黑; font-variant-ligatures: normal; orphans: 2; widows: 2; font-size: 12px; border: 0px rgb(33, 33, 34); color: rgb(62, 62, 62); text-align: center; box-sizing: border-box;">
                                <section data-width="40%" style="padding-top: 2px; padding-right: 2px; padding-left: 2px; width: 212.797px; border-top-width: 3px; border-top-style: solid; border-top-color: rgb(33, 33, 34); color: rgb(0, 0, 0); font-size: 14px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(33, 33, 34); display: inline-block; box-sizing: border-box;"></section>
                            </section>
                            <p style="font-size: 16px; white-space: normal; font-family: 微软雅黑; font-variant-ligatures: normal; orphans: 2; widows: 2; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">▼</span>
                            </p>
                            <section class="" style="margin-top: 0.8em; margin-bottom: 0.8em; font-size: 1em; white-space: normal; font-family: 微软雅黑; font-variant-ligatures: normal; orphans: 2; widows: 2; line-height: 1.4; border: 0px; box-sizing: border-box;">
                                <section class="" style="padding: 10px; border: 1px solid transparent; font-size: 14px; text-decoration: inherit; color: rgb(255, 255, 255); background-color: rgb(11, 206, 255); box-sizing: border-box;">
                                    <section class="" style="text-align: center;">
                                        <strong>课前问题</strong>
                                    </section>
                                </section>
                            </section>
                            <table>
                                <tbody>
                                    <tr class="firstRow">
                                        <td valign="middle" width="28" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="white-space: pre-wrap; font-size: 20px;">
                                                    <strong>目录</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                        <td valign="middle" width="515" style="margin: 5px 10px; padding-left: 20px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❶ 老师和学生可以提前多久进入课堂？</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    教学及备课
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❸</span>
                                                    限制课程
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❹</span>
                                                    PPT模版
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❺</span>
                                                    正在链接白板 / 正在注册白板服务 / 语音服务准备中
                                                </span>
                                                <span style="font-size: 14px;">&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❻</span>
                                                    正在链接语音
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❼</span>
                                                    一直无法进入课堂 / 系统检测页面始终显示“请稍后”
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❽</span>
                                                    进入课堂后没声音，写字没有反应。
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❾</span>
                                                    课表中时间已到，点击进入课堂弹出“课程还未开始，请稍后”提示 / 到上课时间未出现“进入课堂”的按钮。
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❿</span>
                                                    抢到了试听课后作什么准备?
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">⓫</span>
                                                    <span style="letter-spacing: 5px;"></span>
                                                    课件必须用理优模板吗？用自己设计的PPT可以吗？&nbsp;
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">⓬</span>
                                                    <span style="letter-spacing: 5px;"></span>
                                                    接的课可以调整，或者退吗?&nbsp;
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">⓭</span>
                                                    <span style="letter-spacing: 5px;"></span>
                                                    怎么与排课老师取得联系？&nbsp;
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        老师和学生可以提前多久进入课堂？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px;">老师可以提前十五分钟进入课堂。学生可以提前五分钟进入课堂。</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">如果进入课堂按钮未显示，可切换页面进行刷新。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        教学及备课
                                                    </strong>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：教学资料请在在对应的教研群群公告内查看。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❸</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        限制课程
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：1、新老师试听课数量规则：第一周最多接六节试听课，第二周开始每周最多八节试听课；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    &nbsp; &nbsp; &nbsp; &nbsp;2、系统自动限课规则：连续10次未转化 系统自动触发限课规则； &nbsp; &nbsp; &nbsp; &nbsp;3、系统自动冻结规则：连续20次未转化 系统自动触发冻结规则； &nbsp; &nbsp; &nbsp; &nbsp;4、什么时候解除限课？当转化率高于限课之前，十五天后系统自动解除限课；当十五天之后还未解除限课，可以联系对应科目教研老师进行解限处理期待各位老师，能够留出充足时间充分备课，才能转化更多的学生！只要带有充足的常规课学生，才能可持续发展。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❹</span>
                                                    </strong>
                                                    <strong>PPT模版</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px;">
                                                    1、理优资料库下载：
                                                    <a href="http://file.leo1v1.com/index.php/s/hG93JYe7szvDrmD">http://file.leo1v1.com/index.php/s/hG93JYe7szvDrmD</a>
                                                    /
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;2、可在群共享里查看下载。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❺</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        正在链接白板 / 正在注册白板服务 / 语音服务准备中
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：a、清理缓存；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;b、退出重进。</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;c、</span>
                                                <span style="font-size: 14px;">确认自己是否为最新版本</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❻</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        正在链接语音
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px;">a、清理缓存；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;b、退出重进。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❼</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        一直无法进入课堂 / 系统检测页面始终显示“请稍后”
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：联系咨询师或助教后台切服务器。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❽</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        进入课堂后没声音，写字没有反应
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：退出重进。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❾</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        课表中时间已到，点击进入课堂弹出“课程还未开始，请稍后”提示 / 到上课时间未出现“进入课堂”的按钮。
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：切换页面或者退出应用重新进入。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❿</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        抢到了试听课后作什么准备?
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：抢到试听课后需结合试听需求使用标准模板进行备课。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">⓫</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>课件必须用理优模板吗？用自己设计的PPT可以吗？</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：建议使用理优模板。（理优模板可完美适配白板尺寸，并且对于备课内容有相应的规范。）</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">⓬</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        排课时间可调整吗？或者退回。
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：如对排课有疑问，可拨打电话联系排课老师，或点击【申请帮助】按钮获取帮助。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">⓭</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        怎么与排课老师取得联系？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：去排课的那个群，看看是哪个老师发的链接，私聊她就可以。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center; line-height: normal;">
                                <strong>
                                    <br  />
                                </strong>
                            </p>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center; line-height: normal;">
                                <strong>课堂问题</strong>
                            </p>
                            <section style="margin: 2px 1em; font-family: 微软雅黑; orphans: 2; white-space: normal; widows: 2; font-size: 12px; border: 0px rgb(33, 33, 34); color: rgb(62, 62, 62); text-align: center; box-sizing: border-box;">
                                <section data-width="40%" style="padding-top: 2px; padding-right: 2px; padding-left: 2px; width: 212.797px; border-top-width: 3px; border-top-style: solid; border-top-color: rgb(33, 33, 34); color: rgb(0, 0, 0); font-size: 14px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(33, 33, 34); display: inline-block; box-sizing: border-box;"></section>
                            </section>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">试听课问题／学生问题／讲义问题</span>
                            </p>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">▼</span>
                            </p>
                            <section class="" style="margin-top: 0.8em; margin-bottom: 0.8em; font-family: 微软雅黑; font-size: 1em; orphans: 2; white-space: normal; widows: 2; line-height: 1.4; border: 0px; box-sizing: border-box;">
                                <section class="" style="padding: 10px; border: 1px solid transparent; font-size: 14px; text-decoration: inherit; color: rgb(255, 255, 255); background-color: rgb(11, 206, 255); box-sizing: border-box;">
                                    <section class="" style="text-align: center;">
                                        <strong>试听课问题</strong>
                                    </section>
                                </section>
                            </section>
                            <table>
                                <tbody>
                                    <tr class="firstRow">
                                        <td valign="middle" width="28" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="white-space: pre-wrap; font-size: 20px;">
                                                    <strong>目录</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                        <td valign="middle" width="515" style="margin: 5px 10px; padding-left: 20px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❶ 试听课要不要布置作业？</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    试听课学生未到怎么办？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❸</span>
                                                    试听课学生请假怎么办？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❹</span>
                                                    如何下载试听课试卷？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❺</span>
                                                    安排的试听课没时间上怎么办？
                                                </span>
                                                <span style="font-size: 14px;">&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❻</span>
                                                    试听课时，中途退出再重新，之前上传的PDF截图还要重新另传吗？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❼</span>
                                                    试讲要求根据学生试卷讲解错题，可是后台现在还没有传试卷，可以自己准备另外的知识点吗？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❽</span>
                                                    试听课给学生点赞为什么点不到？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        试听课学生要不要布置作业？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：试听课不需要布置作业。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        试听课学生未到怎么办？
                                                    </strong>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：试听课学生如果没来，销售老师会第一时间收到消息去联系家长，老师只需要在课堂等待40分钟就好。如果学生中途进来，老师能上多少是多少，这节课算老师全部课时费。如果学生未到，老师获得一半课时费，并且不需要写评价。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❸</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        试听课学生请假怎么办？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：课前4小时内 试听课学生请假，试听课课时费正常结算。 若发生迟到或者未评价扣款通知，可以进入公众号“理优1对1老师帮”工资明细中针对此次扣款进行申诉即可，小优会及时跟进处理。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❹</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>如何下载试听课试卷？</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：请参考群共享文件—理优教师 如何下载试听课试卷 通过试卷可以了解到学员最全面的学习近况，务必提前下载试卷，进行针对性备课，千万不要只讲解试卷错题，可以安排一些类似题型进行巩固训练。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❺</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        安排的试听课没时间上怎么办？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：1、 优先调整自己时间 确保课程如期进行；2、无法调整，请联系告知排课教务老师；3、如发生老师主动接的试听课，无法如期上课，会影响到之后接试听课的数量，可能会被限制哟！ 请老师们一定确定好自己的时间，再接课哟，切勿随意抢课，给自己和别人 带来不必要的麻烦哟。谢谢各位老师对理优的关注！
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❻</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        试听课时，中途退出再重新，之前上传的PDF截图还要重新另传吗？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：如果只是中途退出，就不用重新上传。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❼</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        试讲要求根据学生试卷讲解错题，可是后台现在还没有传试卷，可以自己准备另外的知识点吗？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：试听需求无试卷的情况下，可以根据学生需求准备知识点，如果对试听需求有疑问，可拨打电话联系教务老师，或点击【申请帮助】按钮获取帮助。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❽</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        试听课给学生点赞为什么点不到？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：试听课本身就不可以给学生点赞，常规课可以给学生点赞。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2;">
                                <br  />
                            </p>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">▼</span>
                            </p>
                            <section class="" style="margin-top: 0.8em; margin-bottom: 0.8em; font-family: 微软雅黑; font-size: 1em; orphans: 2; white-space: normal; widows: 2; line-height: 1.4; border: 0px; box-sizing: border-box;">
                                <section class="" style="padding: 10px; border: 1px solid transparent; font-size: 14px; text-decoration: inherit; color: rgb(255, 255, 255); background-color: rgb(11, 206, 255); box-sizing: border-box;">
                                    <section class="" style="text-align: center;">
                                        <strong>学生问题</strong>
                                    </section>
                                </section>
                            </section>
                            <table>
                                <tbody>
                                    <tr class="firstRow">
                                        <td valign="middle" width="28" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="white-space: pre-wrap; font-size: 20px;">
                                                    <strong>目录</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                        <td valign="middle" width="515" style="margin: 5px 10px; padding-left: 20px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <span style="font-size: 12px;">❶ 常规课给学生点赞有什么特别的用途吗？</span>
                                                <br  />
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    学生那边已经取消课程，老师还需进入课堂吗？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❸</span>
                                                    学生一直不说话。怎么处理？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❹</span>
                                                    学生信息不对，应该找谁解决？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❺</span>
                                                    如何下载学生传的试卷？
                                                </span>
                                                <span style="font-size: 14px;">&nbsp;</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        常规课给学生点赞有什么特别的用途吗？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：学生可以在商城兑换小礼品。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        学生那边已经取消课程，老师还需进入课堂吗？
                                                    </strong>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：如果学生取消课程，老师不需要进入课堂。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❸</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        学生一直不说话。怎么处理？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：尝试在白板中写字，以确认是否是学生设备问题。如果确认是设备问题，让学生退出重新进入课堂。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❹</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>学生信息不对，应该找谁解决？</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：如果对学生的试听需求有疑问，可拨打电话联系教务老师，或点击【申请帮助】按钮获取帮助。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❺</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        如何下载学生传的试卷？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：学生试卷可在老师后台对应的课程下点击下载图标进行下载。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2;">
                                <br  />
                            </p>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">▼</span>
                            </p>
                            <section class="" style="margin-top: 0.8em; margin-bottom: 0.8em; font-family: 微软雅黑; font-size: 1em; orphans: 2; white-space: normal; widows: 2; line-height: 1.4; border: 0px; box-sizing: border-box;">
                                <section class="" style="padding: 10px; border: 1px solid transparent; font-size: 14px; text-decoration: inherit; color: rgb(255, 255, 255); background-color: rgb(11, 206, 255); box-sizing: border-box;">
                                    <section class="" style="text-align: center;">
                                        <strong>讲义问题</strong>
                                    </section>
                                </section>
                            </section>
                            <table>
                                <tbody>
                                    <tr class="firstRow">
                                        <td valign="middle" width="28" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="white-space: pre-wrap; font-size: 20px;">
                                                    <strong>目录</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                        <td valign="middle" width="515" style="margin: 5px 10px; padding-left: 20px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❶ 讲义模板</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    如何上传讲义？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❸</span>
                                                    讲义上传错了怎么办？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❹</span>
                                                    已经把讲义上传到后台，在讲课的时候我可以从本地直接调取吗？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❺</span>
                                                    学生那边看不到讲义内容为什么呀，只能看到我写的字，看不到我传的讲义？
                                                </span>
                                                <span style="font-size: 14px;">&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❻</span>
                                                    讲义上传不了？
                                                </span>
                                            </p>
                                            <p style="font-size: medium; line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❼</span>
                                                    我讲义已经上传了，为什么还是否？
                                                </span>
                                            </p>
                                            <p style="font-size: medium; line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❽</span>
                                                    讲义已经上传到了后台，可怎么把后台的讲义截图？
                                                </span>
                                            </p>
                                            <p style="font-size: medium; line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❾</span>
                                                    学生讲义和教师讲义有啥区别？是不是学生端可以调取学生讲义，而看不到教师讲义啊？
                                                </span>
                                            </p>
                                            <p style="font-size: medium; line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❿</span>
                                                    备课中学生讲义和老师讲义一样行吗？
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        讲义模板
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：需要讲义模板，请点击【群文件】查看。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        如何上传讲义？
                                                    </strong>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：1、如何上传 请参考群共享-理优讲师上传讲义流程； 2、讲义分学生版和教师版 务必全部上传 不然会被扣钱哟~ 技巧分享：可以全部上传学生版讲义 提高备课效率； 3、讲义内容可以根据学生个性化情况自行准备，也可以咨询各科目教研老师获得备课帮助；
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❸</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        讲义上传错了怎么办？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：重新上传一次正确的即可</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❹</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>已经把讲义上传到后台，在讲课的时候我可以从本地直接调取吗？</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：使用图片功能就可以将本地图片添加到白板。【PC端可直接调取本地文件进行截图】</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❺</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        学生那边看不到讲义内容为什么呀，只能看到我写的字，看不到我传的讲义？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：讲义上传后，需要点击白板中的文件图标进行获取。将讲义贴到白板后，学生可见。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❻</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        讲义上传不了？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：学生老师讲义作业等文件需在后台上传。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❼</span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        我讲义已经上传了，为什么还是否？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">1、首先确认老师端版本是否为最新版本</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; 2、正常上传仍无法使用，请使用备用上传功能。</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; 3、备用功能无法上传，请检查本地网络情况。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❽</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        讲义已经上传到了后台，可怎么把后台的讲义截图？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：点击白板中的文件图标（中间有云图案的文件图标）然后选中相应的文件即可截图。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❾</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        学生讲义和教师讲义有啥区别？是不是学生端可以调取学生讲义，而看不到教师讲义啊？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：学生讲义用于学生课前预习，老师上传后学生即可在客户端查看。老师讲义用于课堂授课，老师上传后可在白板中直接调取使用。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❿</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        备课中学生讲义和老师讲义一样行吗？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：学生讲义不放解析内容，老师讲义可以放解析内容。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center; line-height: normal;">
                                <strong>
                                    <br  />
                                </strong>
                            </p>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center; line-height: normal;">
                                <strong>课后问题</strong>
                            </p>
                            <section style="margin: 2px 1em; font-family: 微软雅黑; orphans: 2; white-space: normal; widows: 2; font-size: 12px; border: 0px rgb(33, 33, 34); color: rgb(62, 62, 62); text-align: center; box-sizing: border-box;">
                                <section data-width="40%" style="padding-top: 2px; padding-right: 2px; padding-left: 2px; width: 212.797px; border-top-width: 3px; border-top-style: solid; border-top-color: rgb(33, 33, 34); color: rgb(0, 0, 0); font-size: 14px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(33, 33, 34); display: inline-block; box-sizing: border-box;"></section>
                            </section>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">▼</span>
                            </p>
                            <section class="" style="margin-top: 0.8em; margin-bottom: 0.8em; font-family: 微软雅黑; font-size: 1em; orphans: 2; white-space: normal; widows: 2; line-height: 1.4; border: 0px; box-sizing: border-box;">
                                <section class="" style="padding: 10px; border: 1px solid transparent; font-size: 14px; text-decoration: inherit; color: rgb(255, 255, 255); background-color: rgb(11, 206, 255); box-sizing: border-box;">
                                    <section class="" style="text-align: center;">
                                        <strong>课后问题</strong>
                                    </section>
                                </section>
                            </section>
                            <table>
                                <tbody>
                                    <tr class="firstRow">
                                        <td valign="middle" width="28" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="white-space: pre-wrap; font-size: 20px;">
                                                    <strong>目录</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                        <td valign="middle" width="515" style="margin: 5px 10px; padding-right: 20px; padding-left: 20px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="font-size: 18px;">评价问题</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❶ &nbsp;什么时候评价？</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px; letter-spacing: 5px;">❷</span>
                                                <span style="font-size: 12px;">什么是课后评价？</span>
                                                <span style="font-size: 12px;"></span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❸</span>
                                                    试听课学生没来或者中途退出用不用写评价？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px; letter-spacing: 5px;">❹</span>
                                                <span style="font-size: 12px; letter-spacing: 5px;"></span>
                                                <span style="font-size: 12px;">评价里面的反馈建议是写给学生的还是写给理优平台的？</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="font-size: 18px;">回放问题</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❶ &nbsp;视频内容与语音不一致</span>
                                                <span style="font-size: 12px;">&nbsp;</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    点不开回放视频
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❸</span>
                                                    回放没有声音
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <br  />
                                            </p>
                                            <p style="line-height: 1.75em; text-align: center;">
                                                <span style="font-size: 18px;">作业问题</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">❶ 如何批改作业？</span>
                                                <span style="font-size: 12px;">&nbsp;</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <span style="font-size: 19px; color: rgb(255, 255, 255);">
                                                    <strong style="color: rgb(0, 166, 255);">评价问题</strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <br  />
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        什么时候评价？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：试听课：课后45分钟内完成评价 常规课：课后48小时内完成评价 评价对于学生和家长是非常重要的，不评价会被小优老师处罚哟~ 每次课后只需要评价一次即可，无需多次评价~ 想了解理优的奖惩制度，可以关注-理优1对1老师帮 获得更多帮助。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="font-size: 20px; letter-spacing: 5px;"></span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        什么是课后评价？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：课后评价是老师对于学生课后的专业反馈，包含了学生的表现、对于知识点的掌握情况、课堂中产生了哪些问题以及老师专业的解决方案。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❸</span>
                                                    </strong>
                                                    <strong>试听课学生没来用不用写评价？</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：学生完全未进入课堂，则不需要评价。如果学生中途离开，可按照实际情况进行评价。如发生未评价扣款问题，可在【理优1对1老师帮】-【个人中心】—【我的薪资】中点击申诉功能进行申诉。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❹</span>
                                                    </strong>
                                                    <strong>评价里面的反馈建议是写给学生的还是写给理优平台的？</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：评价是针对学生本堂课的情况对家长进行的反馈，建议老师认真填写。</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <span style="font-size: 19px;">
                                                    <span style="color: rgb(0, 166, 255);">
                                                        <strong>回放问题</strong>
                                                    </span>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                    <strong>视频内容与语音不一致</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="white-space: pre-wrap; font-size: 14px;">a、更新版本；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; b、进度条不要拉太快；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; c、老师没有在白板中书写（注意：当页书写后才会录制画面）</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                    <strong>点不开回放视频</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">a、重启设备；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; b、登录其他设备查看能否播放</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp;&nbsp;</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❸</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>回放没有声音。</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;"></span>
                                                <span style="font-size: 14px;">清除缓存</span>
                                                <span style="font-size: 14px;"></span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <span style="font-size: 19px; color: rgb(255, 255, 255);">
                                                    <strong style="color: rgb(0, 166, 255);">作业问题</strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <br  />
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>如何批改作业？</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;"></span>
                                                <span style="font-size: 14px;">
                                                    PC端：进入软件--点击批改作业--找到对应学生下载作业--利用pdf阅读软件进行批改（推荐“福昕pdf阅读器”）--批改完成并保存再次上传至对应学生作业即可；iPad版本：请直接查看【理优1对1老师帮】公众号中的【用户中心】菜单，查看相关教程。
                                                </span>
                                                <span style="font-size: 14px;"></span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center; line-height: normal;">
                                <strong>
                                    <br  />
                                </strong>
                            </p>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center; line-height: normal;">
                                <strong>设备问题</strong>
                            </p>
                            <section style="margin: 2px 1em; font-family: 微软雅黑; orphans: 2; white-space: normal; widows: 2; font-size: 12px; border: 0px rgb(33, 33, 34); color: rgb(62, 62, 62); text-align: center; box-sizing: border-box;">
                                <section data-width="40%" style="padding-top: 2px; padding-right: 2px; padding-left: 2px; width: 212.797px; border-top-width: 3px; border-top-style: solid; border-top-color: rgb(33, 33, 34); color: rgb(0, 0, 0); font-size: 14px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(33, 33, 34); display: inline-block; box-sizing: border-box;"></section>
                            </section>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">▼</span>
                            </p>
                            <section class="" style="margin-top: 0.8em; margin-bottom: 0.8em; font-family: 微软雅黑; font-size: 1em; orphans: 2; white-space: normal; widows: 2; line-height: 1.4; border: 0px; box-sizing: border-box;">
                                <section class="" style="padding: 10px; border: 1px solid transparent; font-size: 14px; text-decoration: inherit; color: rgb(255, 255, 255); background-color: rgb(11, 206, 255); box-sizing: border-box;">
                                    <section class="" style="text-align: center;">
                                        <strong>设备问题</strong>
                                    </section>
                                </section>
                            </section>
                            <table>
                                <tbody>
                                    <tr class="firstRow">
                                        <td valign="middle" width="28" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250);">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="white-space: pre-wrap; font-size: 20px;">
                                                    <strong>目录</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                        <td valign="middle" width="515" style="margin: 5px 10px; padding-right: 20px; padding-left: 20px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="font-size: 18px;">白板功能</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❶ 白板功能</span>
                                                <span style="font-size: 12px;">&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">
                                                    <br  />
                                                </span>
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="font-size: 18px;">声音类</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 12px;">❶ 软件听不到声音</span>
                                                <span style="font-size: 12px;">&nbsp;</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    上课中听不到声音（断断续续）怎么办？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❸</span>
                                                    回音非常大 / 一边正常，一边有杂音 / 听不到声音
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <br  />
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em; text-align: center;">
                                                <span style="font-size: 18px;">书写类</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">❶ 橡皮擦</span>
                                                <span style="font-size: 12px;">&nbsp;</span>
                                                <span style="font-size: 12px;"></span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    画笔断断续续 / 能听到说话，看不到学生写字
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <br  />
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em; text-align: center;">
                                                <span style="font-size: 18px;">图片类</span>
                                                <span style="font-size: 12px;">
                                                    <br  />
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">❶ 上课过程中对方看不到图片怎么办？</span>
                                                <span style="font-size: 12px;">&nbsp;</span>
                                                <span style="font-size: 12px;"></span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    网络没有问题，但是无法上传图片 / 上课过程中只能看到学生写的字，但是看不到学生发的图
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <br  />
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em; text-align: center;">
                                                <span style="font-size: 18px;">网络问题</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">❶ 上课网络不稳定</span>
                                                <span style="font-size: 12px;">&nbsp;</span>
                                                <span style="font-size: 12px;"></span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❷</span>
                                                    学生端的有电脑版吗？
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <span style="letter-spacing: 5px;">❸</span>
                                                    用平板上课，支持什么系统
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">
                                                    <br  />
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em; text-align: center;">
                                                <span style="font-size: 18px;">错误提示</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px;">❶ 6003</span>
                                                <span style="font-size: 12px;">&nbsp;</span>
                                                <span style="font-size: 12px;"></span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <span style="font-size: 12px; letter-spacing: 5px;">❷</span>
                                                <span style="font-size: 12px;">错误1001 / 错误404</span>
                                                <span style="font-size: 12px;"></span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <span style="font-size: 19px; color: rgb(255, 255, 255);">
                                                    <strong style="color: rgb(0, 166, 255);">白板功能</strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <br  />
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        白板功能
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    答：[讲课白板]查看详情：
                                                    <a href="http://admin.yb1v1.com/article_wx/leo_teacher_whiteboard">http://admin.yb1v1.com/article_wx/leo_teacher_whiteboard</a>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <span style="font-size: 19px;">
                                                    <span style="color: rgb(0, 166, 255);">
                                                        <strong>声音类</strong>
                                                    </span>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">
                                                            <strong>❶</strong>
                                                        </span>
                                                    </strong>
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        软件听不到声音
                                                    </strong>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px;">
                                                    [解决办法]
                                                    <a href="http://jingyan.baidu.com/article/90808022f161c4fd90c80f61.html">
                                                        http://jingyan.baidu.com/article/90808022f161c4fd90c80f61.html
                                                    </a>
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    建议课后联系相应学生的助教老师/咨询老师和您单独做一次连线，看看网络情况是否通畅 &nbsp; 再让助教和学生也进行一次单独连线 &nbsp; 基本就可以定位是哪边的问题了
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        上课中听不到声音（断断续续）怎么办？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：1、双方重启设备，在白板上写字通知学生重启。&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    &nbsp; &nbsp; &nbsp; &nbsp;2、临时解决方案：通过QQ语音或者微信语音确保课程进程，可通过白板联系加学生QQ或者微信；
                                                </span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; &nbsp;3、</span>
                                                <span style="font-size: 14px;">重启无效请联系对应负责人，试听课对接教务老师；常规课对接助教老师。</span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp;&nbsp;</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❸</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>回音非常大 / 一边正常，一边有杂音 / 听不到声音。</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">a、和学生一起退出重进；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; b、检查耳麦是否有问题；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">&nbsp; &nbsp; &nbsp; c、win7独占模式</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">
                                                    windows7有一直听不到对方声音的问题，可以check一下是否是扬声器独占模式的原因，参考：
                                                    <a href="http://www.win7zhijia.cn/jiaocheng/win7_7092.html">http://www.win7zhijia.cn/jiaocheng/win7_7092.html</a>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <span style="font-size: 19px; color: rgb(255, 255, 255);">
                                                    <strong style="color: rgb(0, 166, 255);">书写类</strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <br  />
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        橡皮擦
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;"></span>
                                                <span style="font-size: 14px;">为了白板的使用稳定性，橡皮和清空功能的使用次数无法增加，建议老师写完就直接翻页。</span>
                                                <span style="font-size: 14px;"></span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        画笔断断续续 / 能听到说话，看不到学生写字
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px;">和学生一起退出重进。</span>
                                                <span style="font-size: 14px;"></span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <span style="font-size: 19px; color: rgb(255, 255, 255);">
                                                    <strong style="color: rgb(0, 166, 255);">图片类</strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <br  />
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        上课过程中对方看不到图片怎么办？
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">1、先确认自己图片是否上传成功，软件右上角就会上传成功提示；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px; white-space: pre-wrap;">&nbsp; &nbsp; &nbsp; 2、若提示失败，请检查本地网络 重启设备操作；</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px; white-space: pre-wrap;">&nbsp; &nbsp; &nbsp;3、若提示成功，但对方仍然看不到 请建议对方重启设备 重新连接网络；&nbsp;</span>
                                            </p>
                                            <p>
                                                <span style="font-size: 14px; white-space: pre-wrap;">
                                                    &nbsp; &nbsp; &nbsp;4、以上均无效 试听课对接排课老师切换白板服务器 常规课对接助教老师切换白板服务器。 为了确保服务体验，烦请老师安抚一下家长和学生情绪 理优后期服务部门竭诚为大家创造一个优良的教学环境
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        网络没有问题，但是无法上传图片 / 上课过程中只能看到学生写的字，但是看不到学生发的图
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;"></span>
                                                <span style="font-size: 14px;">和学生一起退出重进</span>
                                                <span style="font-size: 14px;"></span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <span style="font-size: 19px; color: rgb(255, 255, 255);">
                                                    <strong style="color: rgb(0, 166, 255);">网络问题</strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <br  />
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        上课网络不稳定
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：</span>
                                                <span style="font-size: 14px; white-space: pre-wrap;">
                                                    建议课后联系相应学生的助教老师/咨询老师和您单独做一次连线，看看网络情况是否通畅 &nbsp; 再让助教和学生也进行一次单独连线 &nbsp; 基本就可以定位是哪边的问题了。
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p style="text-align: center;">
                                                <span style="font-size: 19px; color: rgb(255, 255, 255);">
                                                    <strong style="color: rgb(0, 166, 255);">错误提示</strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <strong style="color: rgb(0, 166, 255);"></strong>
                                                    <br  />
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❶</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;"></span>
                                                        6003
                                                    </strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：检测到用户未登录，需重新登录</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" rowspan="1" colspan="2" align="left" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>
                                                        <span style="letter-spacing: 5px;">❷</span>
                                                    </strong>
                                                </span>
                                                <span style="color: rgb(0, 166, 255);">
                                                    <strong>错误1001 / 错误404</strong>
                                                </span>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="font-size: 14px;">答：网络环境太差，需要换网络（重启路由器、开4G）</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; font-variant-ligatures: normal; text-align: center; line-height: normal;">
                                <strong>
                                    <br  />
                                </strong>
                            </p>
                            <p style="margin-top: 5px; margin-right: 5px; margin-left: 5px; font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; font-variant-ligatures: normal; text-align: center; line-height: normal;">
                                <strong>万能四步</strong>
                            </p>
                            <section style="margin: 2px 1em; font-family: 微软雅黑; orphans: 2; white-space: normal; widows: 2; font-variant-ligatures: normal; font-size: 12px; border: 0px rgb(33, 33, 34); color: rgb(62, 62, 62); text-align: center; box-sizing: border-box;">
                                <section data-width="40%" style="padding-top: 2px; padding-right: 2px; padding-left: 2px; width: 212.797px; border-top-width: 3px; border-top-style: solid; border-top-color: rgb(33, 33, 34); color: rgb(0, 0, 0); font-size: 14px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(33, 33, 34); display: inline-block; box-sizing: border-box;"></section>
                            </section>
                            <p style="font-family: 微软雅黑; font-size: 16px; orphans: 2; white-space: normal; widows: 2; font-variant-ligatures: normal; text-align: center;">
                                <span style="color: rgb(136, 136, 136); font-size: 12px;">▼</span>
                            </p>
                            <section class="" style="margin-top: 0.8em; margin-bottom: 0.8em; font-family: 微软雅黑; font-size: 1em; orphans: 2; white-space: normal; widows: 2; font-variant-ligatures: normal; line-height: 1.4; border: 0px; box-sizing: border-box;">
                                <section class="" style="padding: 10px; border: 1px solid transparent; font-size: 14px; text-decoration: inherit; color: rgb(255, 255, 255); background-color: rgb(11, 206, 255); box-sizing: border-box;">
                                    <section class="" style="text-align: center;">
                                        <strong>万能四步</strong>
                                    </section>
                                </section>
                            </section>
                            <table>
                                <tbody>
                                    <tr class="firstRow">
                                        <td valign="middle" width="28" style="margin: 5px 10px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <span style="white-space: pre-wrap; font-size: 20px;">
                                                    <strong>万能四步</strong>
                                                </span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                        </td>
                                        <td valign="middle" width="515" style="margin: 5px 10px; padding-right: 20px; padding-left: 20px; border-color: rgb(255, 255, 255); background-color: rgb(237, 245, 250); word-break: break-all;">
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="font-size: 18px;">1.退出重进</span>
                                            </p>
                                            <p>
                                                <br  />
                                            </p>
                                            <p style="text-align: center;">
                                                <span style="font-size: 18px;">2.重启系统</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <br  />
                                            </p>
                                            <p style="line-height: 1.75em; text-align: center;">
                                                <span style="font-size: 18px;">3.系统升级</span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <br  />
                                            </p>
                                            <p style="line-height: 1.75em; text-align: center;">
                                                <span style="font-size: 18px;">4.卸载重装</span>
                                                <span style="font-size: 12px;">
                                                    <br  />
                                                </span>
                                            </p>
                                            <p style="line-height: 1.75em;">
                                                <br  />
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="margin-right: 5px; margin-left: 5px; line-height: normal; text-align: center;">
                                <br  />
                            </p>
                        </section>
                    </div>
                    <script nonce="459565645" type="text/javascript">
                         var first_sceen__time = (+new Date());
                         
                         if ("" == 1 && document.getElementById('js_content')) {
                             document.getElementById('js_content').addEventListener("selectstart",function(e){ e.preventDefault(); });
                         }
                         
                         
                         (function(){
                             if (navigator.userAgent.indexOf("WindowsWechat") != -1){
                                 var link = document.createElement('link');
                                 var head = document.getElementsByTagName('head')[0];
                                 link.rel = 'stylesheet';
                                 link.type = 'text/css';
                                 link.href = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_winwx31619e.css";
                                 head.appendChild(link);
                             }
                         })();
                        </script>

                    <div class="ct_mpda_wrp" id="js_sponsor_ad_area" style="display:none;"></div>

                    <div class="reward_area tc" id="js_preview_reward" style="display:none;">
                        <p id="js_preview_reward_wording" class="tips_global reward_tips" style="display:none;"></p>
                        <p>
                            <a class="reward_access" id='js_preview_reward_link' href="##">赞赏</a>
                        </p>
                    </div>
                    <div class="reward_qrcode_area reward_area tc" id="js_preview_reward_qrcode" style="display:none;">
                        <p class="tips_global">长按二维码向我转账</p>
                        <p id="js_preview_reward_ios_wording" class="reward_tips" style="display:none;"></p>
                        <span class="reward_qrcode_img_wrp">
                            <img class="reward_qrcode_img" src="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/pic/appmsg/pic_reward_qrcode.2x3534dd.png"></span>
                        <p class="tips_global">受苹果公司新规定影响，微信 iOS 版的赞赏功能被关闭，可通过二维码转账支持公众号。</p>
                    </div>
                </div>

                <div class="rich_media_tool" id="js_toobar3">
                    <div id="js_read_area3" class="media_tool_meta tips_global meta_primary" style="display:none;">
                        阅读
                        <span id="readNum3"></span>
                    </div>

                    <span style="display:none;" class="media_tool_meta meta_primary tips_global meta_praise" id="like3">
                        <i class="icon_praise_gray"></i>
                        <span class="praise_num" id="likeNum3"></span>
                    </span>

                    <a id="js_report_article3" style="display:none;" class="media_tool_meta tips_global meta_extra" href="##">投诉</a>

                </div>

            </div>

            <div class="rich_media_area_primary sougou" id="sg_tj" style="display:none"></div>

            <div class="rich_media_area_extra">

                <div class="mpda_bottom_container" id="js_bottom_ad_area"></div>

                <div id="js_iframetest" style="display:none;"></div>

                <div class="rich_media_extra" id="js_preview_cmt" style="display:none">
                    <p class="discuss_icon_tips rich_split_tips tr">
                        <a href="##" id="js_preview_cmt_write">
                            写留言
                            <img class="icon_edit" src="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_edit25ded2.png"></a>
                    </p>
                </div>
            </div>

            <div id="js_pc_qr_code" class="qr_code_pc_outer" style="display:none;">
                <div class="qr_code_pc_inner">
                    <div class="qr_code_pc">
                        <img id="js_pc_qr_code_img" class="qr_code_pc_img">
                        <p>
                            微信扫一扫
                            <br>关注该公众号</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script nonce="459565645">
         var __DEBUGINFO = {
             debug_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/debug/console34c264.js",
             safe_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/moonsafe34c264.js",
             res_list: []
         };
        </script>

    <script nonce="459565645">
         (function() {
               function _addVConsole(uri) {
                     var url = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/vconsole/' + uri;
                     document.write('<script nonce="459565645" type="text/javascript" src="' + url + '"><\/script>
    ');
               }
               if (
                     (document.cookie && document.cookie.indexOf('vconsole_open=1') > -1)
                     || location.href.indexOf('vconsole=1') > -1
               ) {
                     _addVConsole('2.5.1/vconsole.min.js');
                     _addVConsole('plugin/vconsole-elements/1.0.2/vconsole-elements.min.js');
                     _addVConsole('plugin/vconsole-sources/1.0.1/vconsole-sources.min.js');
                     _addVConsole('plugin/vconsole-resources/1.0.0/vconsole-resources.min.js');
                     _addVConsole('plugin/vconsole-mpopt/1.0.0/vconsole-mpopt.js');
               }
         })();
</script>

<script nonce="459565645" type="text/javascript">
         
         if (!window.console) window.console = { log: function() {} };
         
         if (typeof getComputedStyle == 'undefined') {
             if (document.body.currentStyle) {
                 window.getComputedStyle = function(el) {
                     return el.currentStyle;
                 }
             } else {
                 window.getComputedStyle = {};
             }
         }
         var occupyImg = function() {
             var images = document.getElementsByTagName('img');
             var length = images.length;
             var container = document.getElementById('img-content');
             var max_width = container.offsetWidth;
             var container_padding = 0;
             var container_style = getComputedStyle(container);
             container_padding = parseFloat(container_style.paddingLeft) + parseFloat(container_style.paddingRight);
             max_width -= container_padding;
             var ua = navigator.userAgent.toLowerCase();
             var re = new RegExp("msie ([0-9]+[\.0-9]*)");
             var version;
             if (re.exec(ua) != null) {
                 version = parseInt(RegExp.$1);
             }
             var isIE = false;
             if (typeof version != 'undefined' && version >= 6 && version <= 9) {
                 isIE = true;
             }
             if (!max_width) {
                 max_width = window.innerWidth - 30;      
             }
             for (var i = 0; i < length; ++i) {
                 var src_ = images[i].getAttribute('data-src');
                 var realSrc = images[i].getAttribute('src');
                 if (!src_ || realSrc) continue;
                 var width_ = 1 * images[i].getAttribute('data-w') || max_width;
                 var ratio_ = 1 * images[i].getAttribute('data-ratio');
                 var height = 100;
                 if (ratio_ && ratio_ > 0) {
                     var img_style = getComputedStyle(images[i]);
                     var init_width = images[i].style.width;
                     
                     if (init_width) {
                         images[i].setAttribute('_width', init_width);
                         if (init_width != 'auto') width_ = parseFloat(img_style.width);
                     }
                     var parent_width = 0;
                     var parent = images[i].parentNode;
                     var outerWidth = 0;
                     while (true) {
                         var parent_style = getComputedStyle(parent);
                         if (!parent || !parent_style) break;
                         parent_width = parent.clientWidth - parseFloat(parent_style.paddingLeft) - parseFloat(parent_style.paddingRight) - outerWidth;
                         if (parent_width > 0) break;
                         outerWidth += parseFloat(parent_style.paddingLeft) + parseFloat(parent_style.paddingRight) + parseFloat(parent_style.marginLeft) + parseFloat(parent_style.marginRight) + parseFloat(parent_style.borderLeftWidth) + parseFloat(parent_style.borderRightWidth);
                         parent = parent.parentNode;
                     }
                     parent_width = parent_width || max_width;
                     var width = width_ > parent_width ? parent_width : width_; 
                     var img_padding_border = parseFloat(img_style.paddingLeft) + parseFloat(img_style.paddingRight) + parseFloat(img_style.borderLeftWidth) + parseFloat(img_style.borderRightWidth);
                     var img_padding_border_top_bottom = parseFloat(img_style.paddingTop) + parseFloat(img_style.paddingBottom) + parseFloat(img_style.borderTopWidth) + parseFloat(img_style.borderBottomWidth);
                     img_padding_border = img_padding_border || 0;
                     img_padding_border_top_bottom = img_padding_border_top_bottom || 0;
                     height = (width - img_padding_border) * ratio_ + img_padding_border_top_bottom;
                     images[i].style.cssText += ";width: " + width + "px !important;";
                     if (isIE) {
                         var url = images[i].getAttribute('data-src');
                         images[i].src = url;
                     } else {
                         if(width > 40 && height > 40){
                             images[i].className += ' img_loading';
                         }
                         images[i].src = "data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg==";
                     }
                 } else {
                     images[i].style.cssText += ";visibility: hidden !important;";
                 }
                 images[i].style.cssText += ";height: " + height + "px !important;";
             }
         }
         occupyImg();
        </script>
<script nonce="459565645" type="text/javascript">
         
         var not_in_mm_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm322696.css";
         var windowwx_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_winwx31619e.css";
         var article_improve_combo_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo35a5aa.css";
         var tid = "";
         var aid = "";
         var clientversion = "";
         var appuin = "MzI5MDQzMTAxNQ=="||"";
         
         var source = "";
         var abtest_cookie = "";
         
         var scene = 75;
         
         var itemidx = "";
         
         var _copyright_stat = "0";
         var _ori_article_type = "";
         
         var nickname = "理优1对1老师帮";
         var appmsg_type = "6";
         var ct = "1495007501";
         var publish_time = "2017-05-19" || "";
         var user_name = "gh_fb5ad07c1976";
         var user_name_new = "";
         var fakeid   = "";
         var version   = "";
         var is_limit_user   = "0";
         var round_head_img = "http://mmbiz.qpic.cn/mmbiz_png/cBWf565lml7Dthv8uxr3R2HlDscXmU7Vic92j3qGaicPLUgQm7oIKF4DOW3wFzqv6FvwyzicRlKliapd8OCUD1oJicQ/0?wx_fmt=png";
         var ori_head_img_url = "http://wx.qlogo.cn/mmhead/Q3auHgzwzM7PLhZmH6edZO99D8c00uNIJCSW4fFibzbwETohJuofD1w/132";
         var msg_title = "【老师】常见问题处理方法";
         var msg_desc = "1、薪资问题     2、软件下载／登录问题  3、课前问题    4、课堂问题5、课后问题     6、设备问题7、万能四步";
         var msg_cdn_url = "http://mmbiz.qpic.cn/mmbiz_png/cBWf565lml5ticciaEDNHDsQ66rd1sibEhSiaguh6fHgfJXj1H0MaLU7fsYCAqFHc0C7cy7IiaZyvAjn0OLGydaELdA/0?wx_fmt=png";
         var msg_link = "http://mp.weixin.qq.com/s?__biz=MzI5MDQzMTAxNQ==\x26amp;tempkey=G5ceurLO0iwy%2FVgoujTQQroiDH7y6dbK3qWl9n7s5Rm%2B9GuP4N1F4D%2FimdKGC45LRhAROq9%2BBgXc67L5polTdhyS5kbSWtZpER0T424Y8vR%2F9m3oSaF6jf4tXjy1iI1%2BxnMEjSaEotd5R%2BLgHhxWkQ%3D%3D\x26amp;chksm=6c214b675b56c271db232752b0abfa93e11e333517156bec7950fe8ebe37599511f26aa7c3b9#rd";
         var user_uin = "0"*1;
         var msg_source_url = '';
         var img_format = 'png';
         var srcid = '';
         var req_id = '1917muEcoFfAJqdmmEOfg83A';
         var networkType;
         var appmsgid = '' || '100000303'|| "100000303";
         var comment_id = "0" * 1;
         var comment_enabled = "" * 1;
         var is_need_reward = "0" * 1;
         var is_https_res = ("" * 1) && (location.protocol == "https:");
         var msg_daily_idx = "0" || "";
         var profileReportInfo = "" || "";
         
         var devicetype = "";
         var source_encode_biz = "";
         var source_username = "";
         
         var reprint_ticket = "";
         var source_mid = "";
         var source_idx = "";
         
         var show_comment = "";
         var __appmsgCgiData = {
             can_use_page : "0"*1,
             is_wxg_stuff_uin : "0"*1,
             card_pos : "",
             copyright_stat : "0",
             source_biz : "",
             hd_head_img : "http://wx.qlogo.cn/mmhead/Q3auHgzwzM7PLhZmH6edZO99D8c00uNIJCSW4fFibzbwETohJuofD1w/0"||(window.location.protocol+"//"+window.location.host + "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/pic/appmsg/pic_rumor_link.2x264e76.jpg")
         };
         var _empty_v = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/pic/pages/voice/empty26f1f1.mp3";
         
         var copyright_stat = "0" * 1;
         
         var pay_fee = "" * 1;
         var pay_timestamp = "";
         var need_pay = "" * 1;
         
         var need_report_cost = "0" * 1;
         var use_tx_video_player = "0" * 1;
         var appmsg_fe_filter = "contenteditable";
         
         var friend_read_source = "" || "";
         var friend_read_version = "" || "";
         var friend_read_class_id = "" || "";
         
         var is_only_read = "1" * 1;
         var read_num = "14" * 1;
         var like_num = "0" * 1;
         var liked = "false" == 'true' ? true : false;
         var is_temp_url = "G5ceurLO0iwy/VgoujTQQroiDH7y6dbK3qWl9n7s5Rm\x26nbsp;9GuP4N1F4D/imdKGC45LRhAROq9\x26nbsp;BgXc67L5polTdhyS5kbSWtZpER0T424Y8vR/9m3oSaF6jf4tXjy1iI1\x26nbsp;xnMEjSaEotd5R\x26nbsp;LgHhxWkQ==" ? 1 : 0;
         var send_time = "1495186693";
         var icon_emotion_switch = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/emotion/icon_emotion_switch.2x2f1273.png";
         var icon_emotion_switch_active = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/emotion/icon_emotion_switch_active.2x2f1273.png";
         var icon_loading_white = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/icon_loading_white2805ea.gif";
         var icon_audio_unread = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/audio/icon_audio_unread26f1f1.png";
         var icon_qqmusic_default = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/qqmusic/icon_qqmusic_default.2x26f1f1.png";
         var icon_qqmusic_source = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/qqmusic/icon_qqmusic_source263724.png";
         
         var topic_default_img = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/topic/pic_book_thumb.2x2e4987.png';
         var comment_edit_icon = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_edit25ded2.png';
         var comment_loading_img = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/icon_loading_white2805ea.gif';
         var voice_in_appmsg = {
             "1":"1"
         };
         
         
         
         
         
         
         
         var weapp_sn_arr_json = "" || "";
         
         
         var ban_scene = "0" * 1;
         
         var svr_time = "1495186693" * 1;
         
         var is_transfer_msg = ""*1||0;
         
         window.wxtoken = "";
         
         
         
         
         
         window.is_login = '0' * 1; 
         
         window.__moon_initcallback = function(){
             if(!!window.__initCatch){
                 window.__initCatch({
                     idkey : 27611+2,
                     startKey : 0,
                     limit : 128,
                     badjsId: 43,
                     reportOpt : {
                         uin : uin,
                         biz : biz,
                         mid : mid,
                         idx : idx,
                         sn  : sn
                     },
                     extInfo : {
                         network_rate : 0.01,    
                                badjs_rate: 0.1 
                     }
                 });
             }
         }
        </script>

<script nonce="459565645">window.__moon_host = 'res.wx.qq.com';window.__moon_mainjs = 'appmsg/index.js';window.moon_map = {"a/appdialog_confirm.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm.html34f0d8.js","widget/wx_profile_dialog_primary.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/widget/wx_profile_dialog_primary.css34f0d8.js","appmsg/emotion/caret.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/caret278965.js","a/appdialog_confirm.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm34c32a.js","biz_wap/jsapi/cardticket.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/cardticket34c264.js","biz_common/utils/emoji_panel_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_panel_data3518c6.js","biz_common/utils/emoji_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_data3518c6.js","appmsg/emotion/textarea.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/textarea353f34.js","appmsg/emotion/nav.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/nav278965.js","appmsg/emotion/common.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/common3518c6.js","appmsg/emotion/slide.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/slide2a9cd9.js","pages/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/report358df0.js","pages/music_player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_player3592c8.js","pages/loadscript.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/loadscript30203e.js","appmsg/emotion/dom.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/dom31ff31.js","appmsg/comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment_tpl.html35899f.js","biz_wap/utils/fakehash.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/fakehash34c264.js","biz_common/utils/wxgspeedsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/wxgspeedsdk3518c6.js","a/sponsor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor3189b5.js","a/app_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/app_card35b454.js","a/ios.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/ios333f3d.js","a/android.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/android2c5484.js","a/profile.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/profile31ff31.js","a/sponsor_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor_a_tpl.html32c414.js","a/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_tpl.html35b454.js","a/mpshop.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/mpshop311179.js","a/card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/card311179.js","biz_wap/utils/position.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/position34c264.js","a/a_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_report32e586.js","appmsg/my_comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/my_comment_tpl.html35899f.js","appmsg/cmt_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cmt_tpl.html348fa1.js","sougou/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/a_tpl.html2c6e7c.js","appmsg/emotion/emotion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/emotion353f34.js","biz_wap/utils/wapsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/wapsdk34c264.js","biz_common/utils/monitor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/monitor3518c6.js","biz_common/utils/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/report3518c6.js","appmsg/open_url_with_webview.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/open_url_with_webview3145f0.js","biz_common/utils/http.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/http3518c6.js","biz_common/utils/cookie.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/cookie3518c6.js","appmsg/topic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/topic_tpl.html31ff31.js","pages/weapp_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/weapp_tpl.html354755.js","pages/voice_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_tpl.html35899f.js","pages/voice_component.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_component358df0.js","pages/qqmusic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_tpl.html32c414.js","new_video/ctl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/ctl2d441f.js","a/testdata.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/testdata34c32a.js","appmsg/reward_entry.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/reward_entry3534dd.js","appmsg/comment.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment35899f.js","appmsg/like.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/like2eb52b.js","pages/version4video.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/version4video358f7a.js","a/a.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a35d6d5.js","rt/appmsg/getappmsgext.rt.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/rt/appmsg/getappmsgext.rt2c21f6.js","biz_wap/utils/storage.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/storage34c264.js","biz_common/tmpl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/tmpl3518c6.js","appmsg/img_copyright_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/img_copyright_tpl.html2a2c13.js","pages/video_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/video_ctrl35899f.js","biz_common/ui/imgonepx.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/ui/imgonepx3518c6.js","biz_common/utils/respTypes.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/respTypes3518c6.js","biz_wap/utils/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/log34c264.js","sougou/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/index355c4d.js","biz_wap/safe/mutation_observer_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/mutation_observer_report34c264.js","appmsg/fereport.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/fereport33a3b2.js","appmsg/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report3404b3.js","appmsg/report_and_source.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report_and_source34c49d.js","appmsg/page_pos.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/page_pos356b2e.js","appmsg/cdn_speed_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_speed_report3097b2.js","appmsg/wxtopic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/wxtopic31a3be.js","appmsg/weapp.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/weapp35a5aa.js","appmsg/voice.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/voice358df0.js","appmsg/qqmusic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/qqmusic35899f.js","appmsg/iframe.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/iframe35899f.js","appmsg/review_image.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/review_image355a40.js","appmsg/outer_link.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/outer_link275627.js","appmsg/copyright_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/copyright_report2ec4b2.js","appmsg/async.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/async35899f.js","biz_wap/ui/lazyload_img.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/ui/lazyload_img35a497.js","biz_common/log/jserr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/log/jserr3518c6.js","appmsg/share.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share355afb.js","appmsg/cdn_img_lib.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_img_lib35b454.js","biz_common/utils/url/parse.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/url/parse3518c6.js","page/appmsg/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm.css32c99a.js","page/appmsg/page_mp_article_improve_combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo.css35a5aa.js","biz_wap/jsapi/core.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/core34c264.js","biz_common/dom/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/event3518c6.js","appmsg/test.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/test354009.js","biz_wap/utils/mmversion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/mmversion34c264.js","appmsg/max_age.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/max_age2fdd28.js","biz_common/dom/attr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/attr3518c6.js","biz_wap/utils/ajax.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/ajax34c264.js","appmsg/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/log300330.js","biz_common/dom/class.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/class3518c6.js","biz_wap/utils/device.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/device34c264.js","biz_wap/jsapi/a8key.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/a8key34c264.js","biz_common/utils/string/html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/string/html3518c6.js","appmsg/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/index35d6d5.js"};</script>
<script nonce="459565645" type="text/javascript">(function(){function d(a){window.__wxgspeeds.moonls_loadjs_begin=+new Date;var c=document.createElement("script");document.getElementsByTagName("body")[0].appendChild(c);c.type="text/javascript";c.async="async";;c.setAttribute('onerror', 'wx_loaderror');c.onload=function(){a&&f()};c.src=b;window.__wxgspeeds.moonls_loadjs_end=+new Date}function f(){window.__wxgspeeds.moonls_save_begin=+new Date;localStorage.setItem("__WXLS__moon",String(__moonf__));localStorage.setItem("__WXLS__moonarg",JSON.stringify({version:b,method:""}));window.__wxgspeeds.moonls_save_end=+new Date}var a=!!top&&!!top.window&&top.window.user_uin||0,e=0!==a&&1>Math.floor(a/100)%100;if(2876363900==a||1506075==a||942807682==a)e=!0;var b="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/moon352e30.js";window.__loadAllResFromMp&&(b=b.replace("res.wx.qq.com","mp.weixin.qq.com"),(new Image).src=location.protocol+"//mp.weixin.qq.com/mp/jsmonitor?idkey=27613_12_1");window.__wxgspeeds||(window.__wxgspeeds={});if("function"==typeof __moonf__)__moonf__(),e&&localStorage&&f();else if(window.__wxgspeeds.moonloadtime=+new Date,e&&localStorage)try{var g=JSON.parse(localStorage.getItem("__WXLS__moonarg"))||{};if(g&&g.version==b){var h=localStorage.getItem("__WXLS__moon");localStorage.setItem("__WXLS__moonarg",JSON.stringify({version:b,method:"fromls"}));window.__moonls_fromls=!0;window.__wxgspeeds.moonls_loadls_end=+new Date;eval(h);__moonf__()}else d(!0)}catch(k){window.__moonls_fail=!0,d(!0)}else d(!1)})();</script>

<script nonce="459565645" type="text/javascript">
         var real_show_page_time = +new Date();
         if (!!window.addEventListener){
             window.addEventListener("load", function(){
                 window.onload_endtime = +new Date();
             });
         }
         
        </script>

</body>
<script nonce="459565645" type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
</html>
<!--tailTrap<body></body>
<head></head>
<html></html>
-->