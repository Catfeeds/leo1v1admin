<!DOCTYPE html>
<!--headTrap<body></body><head></head><html></html>--><html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">

        
        <script nonce="1670192112" type="text/javascript">
         window.logs = {
             pagetime: {}
         };
         window.logs.pagetime['html_begin'] = (+new Date());
        </script>
        
        <script nonce="1670192112" type="text/javascript">
         var biz = "MzI5MDQzMTAxNQ=="||"";
         var sn = "" || ""|| "8e26f34e52abc00698a699f0f2283a39";
         var mid = "100000309" || ""|| "100000309";
         var idx = "1" || "" || "1";
         window.__allowLoadResFromMp = true;

        </script>
        <script nonce="1670192112" type="text/javascript">
         var page_begintime=+new Date,is_rumor="",norumor="";
         1*is_rumor&&!(1*norumor)&&biz&&mid&&(document.referrer&&-1!=document.referrer.indexOf("mp.weixin.qq.com/mp/rumor")||(location.href="http://mp.weixin.qq.com/mp/rumor?action=info&__biz="+biz+"&mid="+mid+"&idx="+idx+"&sn="+sn+"#wechat_redirect")),
             document.domain="qq.com";
        </script>
        <script nonce="1670192112" type="text/javascript">
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
        <script nonce="1670192112" type="text/javascript">
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
        
        <title>[iPad]软件使用教程</title>
        
        <style>html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;line-height:1.6}body{-webkit-touch-callout:none;font-family:-apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;background-color:#f3f3f3;line-height:inherit}body.rich_media_empty_extra{background-color:#fff}body.rich_media_empty_extra .rich_media_area_primary:before{display:none}h1,h2,h3,h4,h5,h6{font-weight:400;font-size:16px}*{margin:0;padding:0}a{color:#607fa6;text-decoration:none}.rich_media_inner{font-size:16px;word-wrap:break-word;-webkit-hyphens:auto;-ms-hyphens:auto;hyphens:auto}.rich_media_area_primary{position:relative;padding:20px 15px 15px;background-color:#fff}.rich_media_area_primary:before{content:" ";position:absolute;left:0;top:0;width:100%;height:1px;border-top:1px solid #e5e5e5;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5);top:auto;bottom:-2px}.rich_media_area_primary .original_img_wrp{display:inline-block;font-size:0}.rich_media_area_primary .original_img_wrp .tips_global{display:block;margin-top:.5em;font-size:14px;text-align:right;width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.rich_media_area_extra{padding:0 15px 0}.rich_media_title{margin-bottom:10px;line-height:1.4;font-weight:400;font-size:24px}.rich_media_meta_list{margin-bottom:18px;line-height:20px;font-size:0}.rich_media_meta_list em{font-style:normal}.rich_media_meta{display:inline-block;vertical-align:middle;margin-right:8px;margin-bottom:10px;font-size:16px}.meta_original_tag{display:inline-block;vertical-align:middle;padding:1px .5em;border:1px solid #9e9e9e;color:#8c8c8c;border-top-left-radius:20% 50%;-moz-border-radius-topleft:20% 50%;-webkit-border-top-left-radius:20% 50%;border-top-right-radius:20% 50%;-moz-border-radius-topright:20% 50%;-webkit-border-top-right-radius:20% 50%;border-bottom-left-radius:20% 50%;-moz-border-radius-bottomleft:20% 50%;-webkit-border-bottom-left-radius:20% 50%;border-bottom-right-radius:20% 50%;-moz-border-radius-bottomright:20% 50%;-webkit-border-bottom-right-radius:20% 50%;font-size:15px;line-height:1.1}.meta_enterprise_tag img{width:30px;height:30px!important;display:block;position:relative;margin-top:-3px;border:0}.rich_media_meta_text{color:#8c8c8c}span.rich_media_meta_nickname{display:none}.rich_media_thumb_wrp{margin-bottom:6px}.rich_media_thumb_wrp .original_img_wrp{display:block}.rich_media_thumb{display:block;width:100%}.rich_media_content{overflow:hidden;color:#3e3e3e}.rich_media_content *{max-width:100%!important;box-sizing:border-box!important;-webkit-box-sizing:border-box!important;word-wrap:break-word!important}.rich_media_content p{clear:both;min-height:1em}.rich_media_content em{font-style:italic}.rich_media_content fieldset{min-width:0}.rich_media_content .list-paddingleft-2{padding-left:30px}.rich_media_content blockquote{margin:0;padding-left:10px;border-left:3px solid #dbdbdb}img{height:auto!important}@media screen and (device-aspect-ratio:2/3),screen and (device-aspect-ratio:40/71){.meta_original_tag{padding-top:0}}@media(min-device-width:375px) and (max-device-width:667px) and (-webkit-min-device-pixel-ratio:2){.mm_appmsg .rich_media_inner,.mm_appmsg .rich_media_meta,.mm_appmsg .discuss_list,.mm_appmsg .rich_media_extra,.mm_appmsg .title_tips .tips{font-size:17px}.mm_appmsg .meta_original_tag{font-size:15px}}@media(min-device-width:414px) and (max-device-width:736px) and (-webkit-min-device-pixel-ratio:3){.mm_appmsg .rich_media_title{font-size:25px}}@media screen and (min-width:1024px){.rich_media{width:740px;margin-left:auto;margin-right:auto}.rich_media_inner{padding:20px}body{background-color:#fff}}@media screen and (min-width:1025px){body{font-family:"Helvetica Neue",Helvetica,"Hiragino Sans GB","Microsoft YaHei",Arial,sans-serif}.rich_media{position:relative}.rich_media_inner{background-color:#fff;padding-bottom:100px}}.radius_avatar{display:inline-block;background-color:#fff;padding:3px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;overflow:hidden;vertical-align:middle}.radius_avatar img{display:block;width:100%;height:100%;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;background-color:#eee}.cell{padding:.8em 0;display:block;position:relative}.cell_hd,.cell_bd,.cell_ft{display:table-cell;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space:nowrap}.cell_primary{width:2000px;white-space:normal}.flex_cell{padding:10px 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.flex_cell_primary{width:100%;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;box-flex:1;flex:1}.original_tool_area{display:block;padding:.75em 1em 0;-webkit-tap-highlight-color:rgba(0,0,0,0);color:#3e3e3e;border:1px solid #eaeaea;margin:20px 0}.original_tool_area .tips_global{position:relative;padding-bottom:.5em;font-size:15px}.original_tool_area .tips_global:after{content:" ";position:absolute;left:0;bottom:0;right:0;height:1px;border-bottom:1px solid #dbdbdb;-webkit-transform-origin:0 100%;transform-origin:0 100%;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.original_tool_area .radius_avatar{width:27px;height:27px;padding:0;margin-right:.5em}.original_tool_area .radius_avatar img{height:100%!important}.original_tool_area .flex_cell_bd{width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.original_tool_area .flex_cell_ft{font-size:14px;color:#8c8c8c;padding-left:1em;white-space:nowrap}.original_tool_area .icon_access:after{content:" ";display:inline-block;height:8px;width:8px;border-width:1px 1px 0 0;border-color:#cbcad0;border-style:solid;transform:matrix(0.71,0.71,-0.71,0.71,0,0);-ms-transform:matrix(0.71,0.71,-0.71,0.71,0,0);-webkit-transform:matrix(0.71,0.71,-0.71,0.71,0,0);position:relative;top:-2px;top:-1px}.weui_loading{width:20px;height:20px;display:inline-block;vertical-align:middle;-webkit-animation:weuiLoading 1s steps(12,end) infinite;animation:weuiLoading 1s steps(12,end) infinite;background:transparent url(data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iciIgd2lkdGg9JzEyMHB4JyBoZWlnaHQ9JzEyMHB4JyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj4KICAgIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJub25lIiBjbGFzcz0iYmsiPjwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjRTlFOUU5JwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoMCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICA8L3JlY3Q+CiAgICA8cmVjdCB4PSc0Ni41JyB5PSc0MCcgd2lkdGg9JzcnIGhlaWdodD0nMjAnIHJ4PSc1JyByeT0nNScgZmlsbD0nIzk4OTY5NycKICAgICAgICAgIHRyYW5zZm9ybT0ncm90YXRlKDMwIDUwIDUwKSB0cmFuc2xhdGUoMCAtMzApJz4KICAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0naW5kZWZpbml0ZScvPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyM5Qjk5OUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSg2MCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9J2luZGVmaW5pdGUnLz4KICAgIDwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjQTNBMUEyJwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoOTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNBQkE5QUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxMjAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCMkIyQjInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxNTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCQUI4QjknCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxODAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDMkMwQzEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyMTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDQkNCQ0InCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEMkQyRDInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEQURBREEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNFMkUyRTInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0Pgo8L3N2Zz4=) no-repeat;-webkit-background-size:100%;background-size:100%}@-webkit-keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}@keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}.gif_img_wrp{display:inline-block;font-size:0;position:relative;font-weight:400;font-style:normal;text-indent:0;text-shadow:none 1px 1px rgba(0,0,0,0.5)}.gif_img_wrp img{vertical-align:top}.gif_img_tips{background:rgba(0,0,0,0.6)!important;filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#99000000',endcolorstr = '#99000000');border-top-left-radius:1.2em 50%;-moz-border-radius-topleft:1.2em 50%;-webkit-border-top-left-radius:1.2em 50%;border-top-right-radius:1.2em 50%;-moz-border-radius-topright:1.2em 50%;-webkit-border-top-right-radius:1.2em 50%;border-bottom-left-radius:1.2em 50%;-moz-border-radius-bottomleft:1.2em 50%;-webkit-border-bottom-left-radius:1.2em 50%;border-bottom-right-radius:1.2em 50%;-moz-border-radius-bottomright:1.2em 50%;-webkit-border-bottom-right-radius:1.2em 50%;line-height:2.3;font-size:11px;color:#fff;text-align:center;position:absolute;bottom:10px;left:10px;min-width:65px}.gif_img_tips.loading{min-width:75px}.gif_img_tips i{vertical-align:middle;margin:-0.2em .73em 0 -2px}.gif_img_play_arrow{display:inline-block;width:0;height:0;border-width:8px;border-style:dashed;border-color:transparent;border-right-width:0;border-left-color:#fff;border-left-style:solid;border-width:5px 0 5px 8px}.gif_img_loading{width:14px;height:14px}i.gif_img_loading{margin-left:-4px}.gif_bg_tips_wrp{position:relative;height:0;line-height:0;margin:0;padding:0}.gif_bg_tips_wrp .gif_img_tips_group{position:absolute;top:0;left:0;z-index:9999}.gif_bg_tips_wrp .gif_img_tips_group .gif_img_tips{top:0;left:0;bottom:auto}.rich_media_global_msg{position:fixed;top:0;left:0;right:0;padding:1em 35px 1em 15px;z-index:2;background-color:#c6e0f8;color:#8c8c8c;font-size:13px}.rich_media_global_msg .icon_closed{position:absolute;right:15px;top:50%;margin-top:-5px;line-height:300px;overflow:hidden;-webkit-tap-highlight-color:rgba(0,0,0,0);background:transparent url(//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_appmsg_msg_closed_sprite.2x2eb52b.png) no-repeat 0 0;width:11px;height:11px;vertical-align:middle;display:inline-block;-webkit-background-size:100% auto;background-size:100% auto}.rich_media_global_msg .icon_closed:active{background-position:0 -17px}.preview_appmsg .rich_media_title{margin-top:1.9em}@media screen and (min-width:1024px){.rich_media_global_msg{position:relative;margin:0 20px}.preview_appmsg .rich_media_title{margin-top:0}}.weapp_element,.weapp_display_element,.mp-miniprogram{display:block;margin:1em 0}.share_audio_context{margin:16px 0}</style>
         <style>
        </style>
        <!--[if lt IE 9]>
            <link onerror="wx_loaderror(this)" rel="stylesheet" type="text/css" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2c9cd6.css">
        <![endif]-->
        
    </head>
    <body id="activity-detail" class="zh_CN mm_appmsg">
        
        <script nonce="1670192112" type="text/javascript">
         var write_sceen_time = (+new Date());
        </script>
        
        <div id="js_article" class="rich_media preview_appmsg">
            
            <div id="js_top_ad_area" class="top_banner"></div>
            
            
            <div class="rich_media_inner">
                <div id="page-content" class="rich_media_area_primary">
                    
                    <div id="img-content">
                        
                        <h2 class="rich_media_title" id="activity-name">
                            [iPad]软件使用教程                </h2>
                        <div class="rich_media_meta_list">
                            <em id="post-date" class="rich_media_meta rich_media_meta_text">2017-05-17</em>
                            
                            <em class="rich_media_meta rich_media_meta_text">理优产品经理</em>
                            <a class="rich_media_meta rich_media_meta_link rich_media_meta_nickname" href="##" id="post-user">理优1对1老师帮</a>
                            <span class="rich_media_meta rich_media_meta_text rich_media_meta_nickname">理优1对1老师帮</span>
                            
                            <div id="js_profile_qrcode" class="profile_container" style="display:none;">
                                <div class="profile_inner">
                                    <strong class="profile_nickname">理优1对1老师帮</strong>
                                    <img class="profile_avatar" id="js_profile_qrcode_img" src="" alt="">
                                    
                                    <p class="profile_meta">
                                        <label class="profile_meta_label">微信号</label>
                                        <span class="profile_meta_value"></span>
                                    </p>
                                    
                                    <p class="profile_meta">
                                        <label class="profile_meta_label">功能介绍</label>
                                        <span class="profile_meta_value">发布老师新资讯，新活动。定期发布教研素材供老师们学习讨论。表彰优秀教案，表彰优秀教师，提供大量的优秀教学案例。定期开展活动丰富老师们的生活。</span>
                                    </p>
                                    
                                </div>
                                <span class="profile_arrow_wrp" id="js_profile_arrow_wrp">
                                    <i class="profile_arrow arrow_out"></i>
                                    <i class="profile_arrow arrow_in"></i>
                                </span>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        
                        
                        <div class="rich_media_content " id="js_content">
                            
                            
                            
                            
                            
                            
                            <section style="background-color: rgb(255, 255, 255); box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; "><section class="" style="width: 0.6em; display: inline-block; vertical-align: middle; box-sizing: border-box;"><span style="width: 0.6em; height: 0.6em; display: block; opacity: 0.2; background-color: rgb(0, 166, 255); box-sizing: border-box;"></span> <span style="width: 0.6em; height: 0.6em; display: block; opacity: 0.6; margin: 2px 0px; background-color: rgb(0, 166, 255); box-sizing: border-box;"></span> <span style="width: 0.6em; height: 0.6em; display: block; opacity: 1; background-color: rgb(0, 166, 255); box-sizing: border-box;"></span> </section><section class="" style="display: inline-block; vertical-align: middle; font-size: 19.2px; padding-left: 5px; color: rgb(51, 51, 51); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">内容简介</p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">一、<span style="letter-spacing: 0px; box-sizing: border-box;">如何登录老师端</span></p><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">二、</span><span style="letter-spacing: 0px; box-sizing: border-box;">如何修改密码</span></p><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;"></span>三、如何进入课堂上课</p>
                                <p style="margin: 0px; padding: 0px; box-sizing: border-box;">四、如何上传以及平铺讲义</p>
                                <p style="margin: 0px; padding: 0px; box-sizing: border-box;">五、如何打开讲义并截图</p>
                                <p style="margin: 0px; padding: 0px; box-sizing: border-box;">六、如何课后评价</p><p style="margin: 0px; padding: 0px; box-sizing: border-box;">七、如何回看视频</p><p style="margin: 0px; padding: 0px; box-sizing: border-box;">八、如何批改作业</p><p style="margin: 0px; padding: 0px; box-sizing: border-box;">九、如何清楚缓存</p></section></section></section><section class="Powered-by-XIUMI V5" style="box-sizing: border-box;" powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.5em 0px;  box-sizing: border-box; "><section style="border-top: 1px dashed rgb(11, 206, 255); box-sizing: border-box;" class=""></section></section></section><section class="Powered-by-XIUMI V5" style="box-sizing: border-box;" powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.55em 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; width: 2em; height: 2.16em; border-bottom-left-radius: 0.4em; border-top-left-radius: 0.4em; background-color: rgb(0, 166, 255); box-sizing: border-box;"><section class="" style="line-height: 2.16em; width: 2em; height: 1em; padding-left: 0.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><section style="box-sizing: border-box;">一</section></section></section><section style="display: inline-block; vertical-align: top; width: 0px; border-left: 0.5em solid rgb(0, 166, 255); border-top: 1.08em solid transparent !important; border-bottom: 1.08em solid transparent !important; box-sizing: border-box;"></section></section><section style="display: block; float: left; padding: 0.2em 0.3em; margin: -2.11111em 0px 0.1em 1em; border-width: 1px; border-style: solid; border-color: rgb(0, 166, 255); border-radius: 0.4em; box-sizing: border-box;"><section class="" style="padding: 0px 5px 0px 1.5em; margin-left: 2px; float: left; display: inline-block; vertical-align: top; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">如何登录老师端</p></section></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" text-align: center; margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; ">



                                <img class="" data-ratio="0.72" data-w="750" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/1%5BiPad%5D%E5%A6%82%E4%BD%95%E7%99%BB%E5%BD%95%E7%90%86%E4%BC%981%E5%AF%B91%E8%80%81%E5%B8%88%E7%AB%AF.gif" style="max-width: 100%; vertical-align: middle; box-sizing: border-box;"  />

                            </section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="color: rgb(51, 51, 51); box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">理优1对1老师端</span></strong>”图标</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="color: rgb(51, 51, 51); box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">好</span></strong>”允许接受推送通知</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(51, 51, 51); box-sizing: border-box;">输入<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">手机账号（手机号）</span></strong>和<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">登录密码（短信推送中的密码）</span></strong></span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="color: rgb(51, 51, 51); letter-spacing: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">登录</span></strong>”按钮</span></p></li></ol><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.55em 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; width: 2em; height: 2.16em; border-bottom-left-radius: 0.4em; border-top-left-radius: 0.4em; background-color: rgb(0, 166, 255); box-sizing: border-box;"><section class="" style="line-height: 2.16em; width: 2em; height: 1em; padding-left: 0.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="clear: none; margin: 0px; padding: 0px; box-sizing: border-box;">二</p></section></section><section style="display: inline-block; vertical-align: top; width: 0px; border-left: 0.5em solid rgb(0, 166, 255); border-top: 1.08em solid transparent !important; border-bottom: 1.08em solid transparent !important; box-sizing: border-box;"></section></section><section style="display: block; float: left; padding: 0.2em 0.3em; margin: -2.11111em 0px 0.1em 1em; border-width: 1px; border-style: solid; border-color: rgb(0, 166, 255); border-radius: 0.4em; box-sizing: border-box;"><section class="" style="padding: 0px 5px 0px 1.5em; margin-left: 2px; float: left; display: inline-block; vertical-align: top; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">如何修改密码</p></section></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" text-align: center; margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; ">

                                <img class="" data-ratio="0.72" data-w="750" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/2%5BiPad%5D%E5%A6%82%E4%BD%95%E4%BF%AE%E6%94%B9%E5%AF%86%E7%A0%81.gif" style="max-width: 100%; vertical-align: middle; box-sizing: border-box;"  />

                            </section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">我的</span></strong>”中的“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">设置图标</span></strong>”</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">退出当前账号</span></strong>”</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">忘记密码</span></strong>”按钮</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">输入“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">手机号码</span></strong>”并点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">下一步</span></strong>”</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">输入对应的“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">手机验证码</span></strong>”并点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">确定</span></strong>”</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;"></span><span style="letter-spacing: 0px; box-sizing: border-box;">输入并确认新密码后，点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">确定</span></strong>”按钮即可修改完成</span></p></li></ol><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section>

                            <section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.55em 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; width: 2em; height: 2.16em; border-bottom-left-radius: 0.4em; border-top-left-radius: 0.4em; background-color: rgb(0, 166, 255); box-sizing: border-box;"><section class="" style="line-height: 2.16em; width: 2em; height: 1em; padding-left: 0.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="clear: none; margin: 0px; padding: 0px; box-sizing: border-box;">三</p></section></section><section style="display: inline-block; vertical-align: top; width: 0px; border-left: 0.5em solid rgb(0, 166, 255); border-top: 1.08em solid transparent !important; border-bottom: 1.08em solid transparent !important; box-sizing: border-box;"></section></section><section style="display: block; float: left; padding: 0.2em 0.3em; margin: -2.11111em 0px 0.1em 1em; border-width: 1px; border-style: solid; border-color: rgb(0, 166, 255); border-radius: 0.4em; box-sizing: border-box;"><section class="" style="padding: 0px 5px 0px 1.5em; margin-left: 2px; float: left; display: inline-block; vertical-align: top; color: rgb(51, 51, 51); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">如何进入课堂上课</p></section></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" text-align: center; margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; ">

                                <img class="" data-ratio="0.72" data-w="750" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/3%5BiPad%5D%E5%A6%82%E4%BD%95%E8%BF%9B%E5%85%A5%E7%90%86%E4%BC%981%E5%AF%B91%E8%AF%BE%E5%A0%82%E4%B8%8A%E8%AF%BE.gif" style="max-width: 100%; vertical-align: middle; box-sizing: border-box;"  />

                            </section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">本周计划</span></strong>”中的红色区域（<span style="letter-spacing: 0px; box-sizing: border-box;">红色部分为即将开始的课程）</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">点击下方的“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">进入课堂</span></strong>”按钮</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">等待“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">系统检测</span></strong>”</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">检测完成后，点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">进入课堂</span></strong>”</span></p></li></ol><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section>



                                    <section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.55em 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; width: 2em; height: 2.16em; border-bottom-left-radius: 0.4em; border-top-left-radius: 0.4em; background-color: rgb(0, 166, 255); box-sizing: border-box;">

                                        <section class="" style="line-height: 2.16em; width: 2em; height: 1em; padding-left: 0.5em; color: rgb(255, 255, 255); box-sizing: border-box;">

                                            <p style="clear: none; margin: 0px; padding: 0px; box-sizing: border-box;">四</p></section></section><section style="display: inline-block; vertical-align: top; width: 0px; border-left: 0.5em solid rgb(0, 166, 255); border-top: 1.08em solid transparent !important; border-bottom: 1.08em solid transparent !important; box-sizing: border-box;"></section></section><section style="display: block; float: left; padding: 0.2em 0.3em; margin: -2.11111em 0px 0.1em 1em; border-width: 1px; border-style: solid; border-color: rgb(0, 166, 255); border-radius: 0.4em; box-sizing: border-box;"><section class="" style="padding: 0px 5px 0px 1.5em; margin-left: 2px; float: left; display: inline-block; vertical-align: top; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">如何上传以及平铺讲义</p></section></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" text-align: center; margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; ">

                                                <img class="" data-ratio="1" data-w="750" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/%5BiPad%5D%E5%A6%82%E4%BD%95%E5%B9%B3%E9%93%BA%E8%AE%B2%E4%B9%89.gif" style="max-width: 100%; vertical-align: middle; box-sizing: border-box;"  />

                                            </section></section>
                                            <section class="Powered-by-XIUMI V5" style="box-sizing: border-box;" powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; ">
                                                <section class="" style="box-sizing: border-box;">
                                                    <ol style="list-style-type: decimal;" class=" list-paddingleft-2">
                                                        <li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">登录老师后台 <a href="http://www.leo1v1.com/login/teacher">http://www.leo1v1.com/login/teacher</a></p>
                                                        </li>
                                                        <li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击
                                                            <strong style="box-sizing: border-box;"> <span style="color: rgb(11, 206, 255); box-sizing: border-box;">PDF上传/查看</span></strong></p>
                                                        </li>


                                                        <li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">填写讲义信息

                                                        </span></p>
                                                        </li>
                                                        <li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">
                                                            <span style="letter-spacing: 0px; box-sizing: border-box;">当前讲义是否转化为图片,选择  “<strong style="box-sizing: border-box;">
                                                                <span style="color: rgb(11, 206, 255); box-sizing: border-box;"> 是</span>
                                                            </strong>”,点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">上传</span></strong>”讲义
                                                                <span style="color:rgb(255, 0, 0);">温馨提示:只有第一份上传的讲义可平铺</span>
                                                            </span></p>
                                                        </li>
                                                        <li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">等待讲义上传完成
                                                            </p>
                                                        </li>

                                                        <li>
                                                            <p>
                                                                “<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">显示更多</span></strong>”可上传多份讲义,上传完成后,点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">确认</span></strong>”
                                                            </p>
                                                        </li>

                                                        <li>
                                                            <p>
                                                                使用iPad打开老师端,点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">进入课堂</span></strong>”
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p>
                                                                选择需要上传的讲义
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <p>
                                                                确认该份讲义是否平铺,讲义平铺后将无法重新选择其他讲义
                                                            </p>
                                                        </li>

                                                        <li>
                                                            <p>
                                                                讲义平铺完成
                                                            </p>
                                                        </li>



                                                    </ol>
                                                    <p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section>








                            <section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.55em 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; width: 2em; height: 2.16em; border-bottom-left-radius: 0.4em; border-top-left-radius: 0.4em; background-color: rgb(0, 166, 255); box-sizing: border-box;"><section class="" style="line-height: 2.16em; width: 2em; height: 1em; padding-left: 0.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="clear: none; margin: 0px; padding: 0px; box-sizing: border-box;">五</p></section></section><section style="display: inline-block; vertical-align: top; width: 0px; border-left: 0.5em solid rgb(0, 166, 255); border-top: 1.08em solid transparent !important; border-bottom: 1.08em solid transparent !important; box-sizing: border-box;"></section></section><section style="display: block; float: left; padding: 0.2em 0.3em; margin: -2.11111em 0px 0.1em 1em; border-width: 1px; border-style: solid; border-color: rgb(0, 166, 255); border-radius: 0.4em; box-sizing: border-box;"><section class="" style="padding: 0px 5px 0px 1.5em; margin-left: 2px; float: left; display: inline-block; vertical-align: top; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">如何打开讲义并截图</p></section></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" text-align: center; margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; ">

                                <img class="" data-ratio="0.72" data-w="750" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/4%5BiPad%5D%E5%A6%82%E4%BD%95%E6%89%93%E5%BC%80%E8%AE%B2%E4%B9%89%E5%B9%B6%E6%88%AA%E5%9B%BE.gif" style="max-width: 100%; vertical-align: middle; box-sizing: border-box;"  />

                            </section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">云端讲义</span></strong>”图标</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">选择在后台上传好的讲义</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">截图</span></strong>”功能</span></p></li></ol></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin-top: 10px; margin-bottom: 10px; text-align: center;  box-sizing: border-box; "><section class="" style="display: inline-block; border-radius: 0px 8px; background-color: rgb(0, 166, 255); padding: 5px; font-size: 16px; line-height: 1.2em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">方法一</p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">拖动截图框调整“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">截图区域</span></strong>”大小并点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">对号</span></strong>”（背景图可通过双指进行缩放）</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">拖动边框圆点可进行缩放，拖动图片可移动位置，调整好位置后可以点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">对号</span></strong>”置入图片</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">局部截图完成</p></li></ol></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin-top: 10px; margin-bottom: 10px; text-align: center;  box-sizing: border-box; "><section class="" style="display: inline-block; border-radius: 0px 8px; background-color: rgb(0, 166, 255); padding: 5px; font-size: 16px; line-height: 1.2em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">方法二</p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">全屏截图</span></strong>”功能</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">确定</span></strong>”</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">全屏截图完成</p><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></li></ol></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.55em 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; width: 2em; height: 2.16em; border-bottom-left-radius: 0.4em; border-top-left-radius: 0.4em; background-color: rgb(0, 166, 255); box-sizing: border-box;"><section class="" style="line-height: 2.16em; width: 2em; height: 1em; padding-left: 0.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="clear: none; margin: 0px; padding: 0px; box-sizing: border-box;">六</p></section></section><section style="display: inline-block; vertical-align: top; width: 0px; border-left: 0.5em solid rgb(0, 166, 255); border-top: 1.08em solid transparent !important; border-bottom: 1.08em solid transparent !important; box-sizing: border-box;"></section></section><section style="display: block; float: left; padding: 0.2em 0.3em; margin: -2.11111em 0px 0.1em 1em; border-width: 1px; border-style: solid; border-color: rgb(0, 166, 255); border-radius: 0.4em; box-sizing: border-box;"><section class="" style="padding: 0px 5px 0px 1.5em; margin-left: 2px; float: left; display: inline-block; vertical-align: top; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">如何课后评价</p></section></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" text-align: center; margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; ">

                                <img class="" data-ratio="0.72" data-w="750" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/5%5BiPad%5D%E5%A6%82%E4%BD%95%E8%AF%BE%E5%90%8E%E8%AF%84%E4%BB%B7.gif" style="max-width: 100%; vertical-align: middle; box-sizing: border-box;"  />

                            </section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">在“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">教学计划</span></strong>”中找到未评价的对应课程并点击</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击列表中的“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">评价学生</span></strong>”按钮</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">根据学生情况填写整体的课后反馈</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;"></span><span style="letter-spacing: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">提交反馈</span></strong>”按钮即可评价完成</span></p></li></ol><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.55em 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; width: 2em; height: 2.16em; border-bottom-left-radius: 0.4em; border-top-left-radius: 0.4em; background-color: rgb(0, 166, 255); box-sizing: border-box;"><section class="" style="line-height: 2.16em; width: 2em; height: 1em; padding-left: 0.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="clear: none; margin: 0px; padding: 0px; box-sizing: border-box;">七</p></section></section><section style="display: inline-block; vertical-align: top; width: 0px; border-left: 0.5em solid rgb(0, 166, 255); border-top: 1.08em solid transparent !important; border-bottom: 1.08em solid transparent !important; box-sizing: border-box;"></section></section><section style="display: block; float: left; padding: 0.2em 0.3em; margin: -2.11111em 0px 0.1em 1em; border-width: 1px; border-style: solid; border-color: rgb(0, 166, 255); border-radius: 0.4em; box-sizing: border-box;"><section class="" style="padding: 0px 5px 0px 1.5em; margin-left: 2px; float: left; display: inline-block; vertical-align: top; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">如何回看视频</p></section></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" text-align: center; margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; ">

                                <img class="" data-ratio="0.72" data-w="750" src=" http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/6%5BiPad%5D%E5%A6%82%E4%BD%95%E5%9B%9E%E7%9C%8B%E8%A7%86%E9%A2%91.gif" style="max-width: 100%; vertical-align: middle; box-sizing: border-box;"  />

                            </section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">我的</span></strong>”中的相应课程</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击对应课程中的“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">查看视频</span></strong>”</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">等待视频加载完成</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">播放</span></strong>”按钮</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;"></span><span style="letter-spacing: 0px; box-sizing: border-box;">拖动“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">滑块</span></strong>”查看对应进度的视频</span></p></li></ol><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.55em 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; width: 2em; height: 2.16em; border-bottom-left-radius: 0.4em; border-top-left-radius: 0.4em; background-color: rgb(0, 166, 255); box-sizing: border-box;"><section class="" style="line-height: 2.16em; width: 2em; height: 1em; padding-left: 0.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="clear: none; margin: 0px; padding: 0px; box-sizing: border-box;">八</p></section></section><section style="display: inline-block; vertical-align: top; width: 0px; border-left: 0.5em solid rgb(0, 166, 255); border-top: 1.08em solid transparent !important; border-bottom: 1.08em solid transparent !important; box-sizing: border-box;"></section></section><section style="display: block; float: left; padding: 0.2em 0.3em; margin: -2.11111em 0px 0.1em 1em; border-width: 1px; border-style: solid; border-color: rgb(0, 166, 255); border-radius: 0.4em; box-sizing: border-box;"><section class="" style="padding: 0px 5px 0px 1.5em; margin-left: 2px; float: left; display: inline-block; vertical-align: top; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">如何批改作业</p></section></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" text-align: center; margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; ">

                                <img class="" data-ratio="0.72" data-w="750" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/7%5BiPad%5D%E5%A6%82%E4%BD%95%E6%89%B9%E6%94%B9%E4%BD%9C%E4%B8%9A.gif" style="max-width: 100%; vertical-align: middle; box-sizing: border-box;"  />

                            </section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">作业</span></strong>”</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">找到需要批改的作业，点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">马上批改</span></strong>”按钮</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">使用“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">画笔</span></strong>”功能进行批注</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">根据序号判断对应题目是否正确</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">提交</span></strong>”图标提交作业</span></p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;"></span><span style="letter-spacing: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">直接提交</span></strong>”即可完成作业批改</span></p></li></ol><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.55em 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; width: 2em; height: 2.16em; border-bottom-left-radius: 0.4em; border-top-left-radius: 0.4em; background-color: rgb(0, 166, 255); box-sizing: border-box;"><section class="" style="line-height: 2.16em; width: 2em; height: 1em; padding-left: 0.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="clear: none; margin: 0px; padding: 0px; box-sizing: border-box;">九</p></section></section><section style="display: inline-block; vertical-align: top; width: 0px; border-left: 0.5em solid rgb(0, 166, 255); border-top: 1.08em solid transparent !important; border-bottom: 1.08em solid transparent !important; box-sizing: border-box;"></section></section><section style="display: block; float: left; padding: 0.2em 0.3em; margin: -2.11111em 0px 0.1em 1em; border-width: 1px; border-style: solid; border-color: rgb(0, 166, 255); border-radius: 0.4em; box-sizing: border-box;"><section class="" style="padding: 0px 5px 0px 1.5em; margin-left: 2px; float: left; display: inline-block; vertical-align: top; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">如何清除缓存</p></section></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" text-align: center; margin-top: 10px; margin-bottom: 10px;  box-sizing: border-box; ">

                                <img class="" data-ratio="0.72" data-w="750" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-ipad%E7%AB%AF/8%5BiPad%5D%E5%A6%82%E4%BD%95%E6%B8%85%E6%A5%9A%E7%BC%93%E5%AD%98.gif" style="max-width: 100%; vertical-align: middle; box-sizing: border-box;"  />

                            </section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><ol style="list-style-type: decimal;" class=" list-paddingleft-2"><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">我的</span></strong>”中的“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">设置图标</span></strong>”</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;">点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">清除缓存</span></strong>”</p></li><li><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">弹出确认弹框后，点击“<strong style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">确定</span></strong>”即可清除完成</span></p></li></ol><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0.5em 0px;  box-sizing: border-box; "><section class="" style="border-top: 2px solid rgb(0, 166, 255); padding-top: 3px; border-right-color: rgb(0, 166, 255); border-bottom-color: rgb(0, 166, 255); border-left-color: rgb(0, 166, 255); box-sizing: border-box;"><section class="" style="display: inline-block; vertical-align: top; height: 2em; line-height: 2em; padding: 0px 0.5em; background-color: rgb(0, 166, 255); color: rgb(255, 255, 255); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">使用手册</p></section><section style="width: 0px; display: inline-block; vertical-align: top; border-left: 0.8em solid rgb(0, 166, 255); border-top: 1em solid rgb(0, 166, 255); border-right: 0.8em solid transparent !important; border-bottom: 1em solid transparent !important; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0px 0% 10px;  box-sizing: border-box; "><section class="" style="display: inline-block; width: 100%; vertical-align: top; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 5px 0%;  box-sizing: border-box; "><section class="" style="display: inline-block; vertical-align: top; width: 12%; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0px 0%; text-align: center; font-size: 8.8px;  box-sizing: border-box; "><section class="" style="display: inline-block; border-width: 2px; border-style: dotted; border-color: rgb(11, 206, 255); width: 1.6em; height: 1.6em; border-radius: 0.3em; background-color: rgb(11, 206, 255); font-size: 13.2px; line-height: 1.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">1</p></section></section></section></section><section class="" style="display: inline-block; vertical-align: top; width: 88%; padding: 0px 0px 0px 5px; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0px 0%;  box-sizing: border-box; "><section class="" style="text-align: justify; line-height: 1.6; font-size: 14px; color: rgb(11, 206, 255); box-sizing: border-box;"><p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;">                                    <a href="http://admin.yb1v1.com/article_wx/leo_teacher_software"> [软件]如何下载老师端、语音检测APP</a>
                            </p></section></section></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 10px 0% 5px;  box-sizing: border-box; "><section class="" style="display: inline-block; vertical-align: top; width: 12%; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0px 0%; text-align: center; font-size: 8.8px;  box-sizing: border-box; "><section class="" style="display: inline-block; border-width: 2px; border-style: dotted; border-color: rgb(11, 206, 255); width: 1.6em; height: 1.6em; border-radius: 0.3em; background-color: rgb(11, 206, 255); font-size: 13.2px; line-height: 1.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">2</p></section></section></section></section><section class="" style="display: inline-block; vertical-align: top; width: 88%; padding: 0px 0px 0px 5px; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0px 0%;  box-sizing: border-box; "><section class="" style="text-align: justify; line-height: 1.6; font-size: 14px; color: rgb(11, 206, 255); box-sizing: border-box;"><p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;">                                    <a href="http://admin.yb1v1.com/article_wx/leo_teacher_ipad">[iPad]软件使用教程</a>
                            </p></section></section></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 10px 0% 5px;  box-sizing: border-box; "><section class="" style="display: inline-block; vertical-align: top; width: 12%; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0px 0%; text-align: center; font-size: 8.8px;  box-sizing: border-box; "><section class="" style="display: inline-block; border-width: 2px; border-style: dotted; border-color: rgb(11, 206, 255); width: 1.6em; height: 1.6em; border-radius: 0.3em; background-color: rgb(11, 206, 255); font-size: 13.2px; line-height: 1.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">3</p></section></section></section></section><section class="" style="display: inline-block; vertical-align: top; width: 88%; padding: 0px 0px 0px 5px; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0px 0%;  box-sizing: border-box; "><section class="" style="text-align: justify; line-height: 1.6; font-size: 14px; color: rgb(11, 206, 255); box-sizing: border-box;"><p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;">                                    <a href="http://admin.yb1v1.com/article_wx/leo_teacher_pc"> [PC]软件使用教程</a>

                            </p></section></section></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 10px 0% 5px;  box-sizing: border-box; "><section class="" style="display: inline-block; vertical-align: top; width: 12%; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0px 0%; text-align: center; font-size: 8.8px;  box-sizing: border-box; "><section class="" style="display: inline-block; border-width: 2px; border-style: dotted; border-color: rgb(11, 206, 255); width: 1.6em; height: 1.6em; border-radius: 0.3em; background-color: rgb(11, 206, 255); font-size: 13.2px; line-height: 1.5em; color: rgb(255, 255, 255); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;">4</p></section></section></section></section><section class="" style="display: inline-block; vertical-align: top; width: 88%; padding: 0px 0px 0px 5px; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: 0px 0%;  box-sizing: border-box; "><section class="" style="text-align: justify; line-height: 1.6; font-size: 14px; color: rgb(11, 206, 255); box-sizing: border-box;"><p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;">                                    <a href="http://admin.yb1v1.com/article_wx/leo_teacher_whiteboard">[讲课白板]功能介绍</a>
                            </p><p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;"><br  /></p></section></section></section></section></section></section></section></section></section></section>
                        </div>
                        <script nonce="1670192112" type="text/javascript">
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
                            <span class="reward_qrcode_img_wrp"><img class="reward_qrcode_img" src="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/pic/appmsg/pic_reward_qrcode.2x3534dd.png"></span>
                            <p class="tips_global">受苹果公司新规定影响，微信 iOS 版的赞赏功能被关闭，可通过二维码转账支持公众号。</p>
                        </div>
                    </div>
                    
                    <div class="rich_media_tool" id="js_toobar3">
                        <div id="js_read_area3" class="media_tool_meta tips_global meta_primary" style="display:none;">阅读 <span id="readNum3"></span></div>
                        
                        <span style="display:none;" class="media_tool_meta meta_primary tips_global meta_praise" id="like3">
                            <i class="icon_praise_gray"></i><span class="praise_num" id="likeNum3"></span>
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
                            <a href="##" id="js_preview_cmt_write">写留言<img class="icon_edit" src="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_edit25ded2.png"></a>
                        </p>
                    </div>
                </div>
                
                
                <div id="js_pc_qr_code" class="qr_code_pc_outer" style="display:none;">
                    <div class="qr_code_pc_inner">
                        <div class="qr_code_pc">
                            <img id="js_pc_qr_code_img" class="qr_code_pc_img">
                            <p>微信扫一扫<br>关注该公众号</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <script nonce="1670192112">
         var __DEBUGINFO = {
             debug_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/debug/console34c264.js",
             safe_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/moonsafe34c264.js",
             res_list: []
         };
        </script>
        
        <script nonce="1670192112">
         (function() {
             function _addVConsole(uri) {
                 var url = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/vconsole/' + uri;
                 document.write('<script nonce="1670192112" type="text/javascript" src="' + url + '"><\/script>');
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
        
        <script nonce="1670192112" type="text/javascript">

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
        <script nonce="1670192112" type="text/javascript">
         
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
         var ct = "1495007651";
         var publish_time = "2017-05-17" || "";
         var user_name = "gh_fb5ad07c1976";
         var user_name_new = "";
         var fakeid   = "";
         var version   = "";
         var is_limit_user   = "0";
         var round_head_img = "http://mmbiz.qpic.cn/mmbiz_png/cBWf565lml7Dthv8uxr3R2HlDscXmU7Vic92j3qGaicPLUgQm7oIKF4DOW3wFzqv6FvwyzicRlKliapd8OCUD1oJicQ/0?wx_fmt=png";
         var ori_head_img_url = "http://wx.qlogo.cn/mmhead/Q3auHgzwzM7PLhZmH6edZO99D8c00uNIJCSW4fFibzbwETohJuofD1w/132";
         var msg_title = "[iPad]软件使用教程";
         var msg_desc = "有关于 登录 修改密码 进入课堂 打开讲义截图 课后评价 回看视频 批改作业 清楚缓存";
         var msg_cdn_url = "http://mmbiz.qpic.cn/mmbiz_png/cBWf565lml5stjfWP8z2BB3icuWia6NV5NzibOQPP71d7HLbLUk7diadnuRMghXVIIicHnwAxMGHcfZeKu0NUaiaicIfg/0?wx_fmt=png";
         var msg_link = "http://mp.weixin.qq.com/s?__biz=MzI5MDQzMTAxNQ==\x26amp;tempkey=G5ceurLO0iwy%2FVgoujTQQroiDH7y6dbK3qWl9n7s5Rkluc2QXAVwvmnVigA1PSY18k5T%2FN6s2qEtLGWf6iWSwB8N55MtS%2FD15cN36Mos6s9P209KMavLezgUl3Wu7hRcZa4MGDdF7vlsnFSTqb98bQ%3D%3D\x26amp;chksm=6c214b7d5b56c26b533958605cf72f9b9d0f8798d75a1237953892b79e2190ed04dfa692c3ac#rd";
         var user_uin = "0"*1;
         var msg_source_url = '';
         var img_format = 'jpeg';
         var srcid = '';
         var req_id = '1810mdxTF9ycOLT1VEzzUP6o';
         var networkType;
         var appmsgid = '' || '100000309'|| "100000309";
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
         var read_num = "3" * 1;
         var like_num = "0" * 1;
         var liked = "false" == 'true' ? true : false;
         var is_temp_url = "G5ceurLO0iwy/VgoujTQQroiDH7y6dbK3qWl9n7s5Rkluc2QXAVwvmnVigA1PSY18k5T/N6s2qEtLGWf6iWSwB8N55MtS/D15cN36Mos6s9P209KMavLezgUl3Wu7hRcZa4MGDdF7vlsnFSTqb98bQ==" ? 1 : 0;
         var send_time = "1495073953";
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
         
         var svr_time = "1495073953" * 1;

         var is_transfer_msg = ""*1||0;
         
         window.wxtoken = "";





         window.is_login = '0' * 1;
         
         window.__moon_initcallback = function(){
             if(!!window.__initCatch){
                 window.__initCatch({
                     idkey : 27613,
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
        
        <script nonce="1670192112">window.__moon_host = 'res.wx.qq.com';window.__moon_mainjs = 'appmsg/index.js';window.moon_map = {"a/appdialog_confirm.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm.html34f0d8.js","widget/wx_profile_dialog_primary.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/widget/wx_profile_dialog_primary.css34f0d8.js","appmsg/emotion/caret.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/caret278965.js","a/appdialog_confirm.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm34c32a.js","biz_wap/jsapi/cardticket.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/cardticket34c264.js","biz_common/utils/emoji_panel_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_panel_data3518c6.js","biz_common/utils/emoji_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_data3518c6.js","appmsg/emotion/textarea.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/textarea353f34.js","appmsg/emotion/nav.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/nav278965.js","appmsg/emotion/common.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/common3518c6.js","appmsg/emotion/slide.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/slide2a9cd9.js","pages/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/report358df0.js","pages/music_player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_player3592c8.js","pages/loadscript.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/loadscript30203e.js","appmsg/emotion/dom.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/dom31ff31.js","appmsg/comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment_tpl.html35899f.js","biz_wap/utils/fakehash.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/fakehash34c264.js","biz_common/utils/wxgspeedsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/wxgspeedsdk3518c6.js","a/sponsor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor3189b5.js","a/app_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/app_card35b454.js","a/ios.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/ios333f3d.js","a/android.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/android2c5484.js","a/profile.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/profile31ff31.js","a/sponsor_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor_a_tpl.html32c414.js","a/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_tpl.html35b454.js","a/mpshop.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/mpshop311179.js","a/card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/card311179.js","biz_wap/utils/position.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/position34c264.js","a/a_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_report32e586.js","appmsg/my_comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/my_comment_tpl.html35899f.js","appmsg/cmt_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cmt_tpl.html348fa1.js","sougou/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/a_tpl.html2c6e7c.js","appmsg/emotion/emotion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/emotion353f34.js","biz_wap/utils/wapsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/wapsdk34c264.js","biz_common/utils/monitor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/monitor3518c6.js","biz_common/utils/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/report3518c6.js","appmsg/open_url_with_webview.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/open_url_with_webview3145f0.js","biz_common/utils/http.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/http3518c6.js","biz_common/utils/cookie.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/cookie3518c6.js","appmsg/topic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/topic_tpl.html31ff31.js","pages/weapp_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/weapp_tpl.html354755.js","pages/voice_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_tpl.html35899f.js","pages/voice_component.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_component358df0.js","pages/qqmusic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_tpl.html32c414.js","new_video/ctl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/ctl2d441f.js","a/testdata.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/testdata34c32a.js","appmsg/reward_entry.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/reward_entry3534dd.js","appmsg/comment.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment35899f.js","appmsg/like.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/like2eb52b.js","pages/version4video.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/version4video358f7a.js","a/a.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a35d6d5.js","rt/appmsg/getappmsgext.rt.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/rt/appmsg/getappmsgext.rt2c21f6.js","biz_wap/utils/storage.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/storage34c264.js","biz_common/tmpl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/tmpl3518c6.js","appmsg/img_copyright_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/img_copyright_tpl.html2a2c13.js","pages/video_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/video_ctrl35899f.js","biz_common/ui/imgonepx.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/ui/imgonepx3518c6.js","biz_common/utils/respTypes.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/respTypes3518c6.js","biz_wap/utils/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/log34c264.js","sougou/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/index355c4d.js","biz_wap/safe/mutation_observer_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/mutation_observer_report34c264.js","appmsg/fereport.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/fereport33a3b2.js","appmsg/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report3404b3.js","appmsg/report_and_source.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report_and_source34c49d.js","appmsg/page_pos.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/page_pos356b2e.js","appmsg/cdn_speed_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_speed_report3097b2.js","appmsg/wxtopic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/wxtopic31a3be.js","appmsg/weapp.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/weapp35a5aa.js","appmsg/voice.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/voice358df0.js","appmsg/qqmusic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/qqmusic35899f.js","appmsg/iframe.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/iframe35899f.js","appmsg/review_image.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/review_image355a40.js","appmsg/outer_link.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/outer_link275627.js","appmsg/copyright_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/copyright_report2ec4b2.js","appmsg/async.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/async35899f.js","biz_wap/ui/lazyload_img.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/ui/lazyload_img35a497.js","biz_common/log/jserr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/log/jserr3518c6.js","appmsg/share.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share355afb.js","appmsg/cdn_img_lib.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_img_lib35b454.js","biz_common/utils/url/parse.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/url/parse3518c6.js","page/appmsg/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm.css32c99a.js","page/appmsg/page_mp_article_improve_combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo.css35a5aa.js","biz_wap/jsapi/core.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/core34c264.js","biz_common/dom/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/event3518c6.js","appmsg/test.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/test354009.js","biz_wap/utils/mmversion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/mmversion34c264.js","appmsg/max_age.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/max_age2fdd28.js","biz_common/dom/attr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/attr3518c6.js","biz_wap/utils/ajax.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/ajax34c264.js","appmsg/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/log300330.js","biz_common/dom/class.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/class3518c6.js","biz_wap/utils/device.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/device34c264.js","biz_wap/jsapi/a8key.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/a8key34c264.js","biz_common/utils/string/html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/string/html3518c6.js","appmsg/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/index35d6d5.js"};</script><script nonce="1670192112" type="text/javascript">(function(){function d(a){window.__wxgspeeds.moonls_loadjs_begin=+new Date;var c=document.createElement("script");document.getElementsByTagName("body")[0].appendChild(c);c.type="text/javascript";c.async="async";;c.setAttribute('onerror', 'wx_loaderror');c.onload=function(){a&&f()};c.src=b;window.__wxgspeeds.moonls_loadjs_end=+new Date}function f(){window.__wxgspeeds.moonls_save_begin=+new Date;localStorage.setItem("__WXLS__moon",String(__moonf__));localStorage.setItem("__WXLS__moonarg",JSON.stringify({version:b,method:""}));window.__wxgspeeds.moonls_save_end=+new Date}var a=!!top&&!!top.window&&top.window.user_uin||0,e=0!==a&&1>Math.floor(a/100)%100;if(2876363900==a||1506075==a||942807682==a)e=!0;var b="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/moon352e30.js";window.__loadAllResFromMp&&(b=b.replace("res.wx.qq.com","mp.weixin.qq.com"),(new Image).src=location.protocol+"//mp.weixin.qq.com/mp/jsmonitor?idkey=27613_12_1");window.__wxgspeeds||(window.__wxgspeeds={});if("function"==typeof __moonf__)__moonf__(),e&&localStorage&&f();else if(window.__wxgspeeds.moonloadtime=+new Date,e&&localStorage)try{var g=JSON.parse(localStorage.getItem("__WXLS__moonarg"))||{};if(g&&g.version==b){var h=localStorage.getItem("__WXLS__moon");localStorage.setItem("__WXLS__moonarg",JSON.stringify({version:b,method:"fromls"}));window.__moonls_fromls=!0;window.__wxgspeeds.moonls_loadls_end=+new Date;eval(h);__moonf__()}else d(!0)}catch(k){window.__moonls_fail=!0,d(!0)}else d(!1)})();</script>
        <script nonce="1670192112" type="text/javascript">
         var real_show_page_time = +new Date();
         if (!!window.addEventListener){
             window.addEventListener("load", function(){
                 window.onload_endtime = +new Date();
             });
         }
         
        </script>
        
    </body>
    <script nonce="1670192112" type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
</html>
<!--tailTrap<body></body><head></head><html></html>-->
