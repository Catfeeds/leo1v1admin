<!DOCTYPE html>
<!--headTrap<body></body><head></head><html></html>--><html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">

        
        <script nonce="857420236" type="text/javascript">
         window.logs = {
             pagetime: {}
         };
         window.logs.pagetime['html_begin'] = (+new Date());
        </script>
        
        <script nonce="857420236" type="text/javascript">
         var biz = "MzI5MDQzMTAxNQ=="||"";
         var sn = "" || ""|| "a40e0850b35fff8d7a541110c27c8f73";
         var mid = "100000023" || ""|| "100000023";
         var idx = "1" || "" || "1";
         window.__allowLoadResFromMp = true;

        </script>
        <script nonce="857420236" type="text/javascript">
         var page_begintime=+new Date,is_rumor="",norumor="";
         1*is_rumor&&!(1*norumor)&&biz&&mid&&(document.referrer&&-1!=document.referrer.indexOf("mp.weixin.qq.com/mp/rumor")||(location.href="http://mp.weixin.qq.com/mp/rumor?action=info&__biz="+biz+"&mid="+mid+"&idx="+idx+"&sn="+sn+"#wechat_redirect")),
             document.domain="qq.com";
        </script> 
        <script nonce="857420236" type="text/javascript">
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
        <script nonce="857420236" type="text/javascript">
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
        
        <title>【数学】试听课案例鉴赏</title>
        
        <style>html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;line-height:1.6}body{-webkit-touch-callout:none;font-family:-apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;background-color:#f3f3f3;line-height:inherit}body.rich_media_empty_extra{background-color:#fff}body.rich_media_empty_extra .rich_media_area_primary:before{display:none}h1,h2,h3,h4,h5,h6{font-weight:400;font-size:16px}*{margin:0;padding:0}a{color:#607fa6;text-decoration:none}.rich_media_inner{font-size:16px;word-wrap:break-word;-webkit-hyphens:auto;-ms-hyphens:auto;hyphens:auto}.rich_media_area_primary{position:relative;padding:20px 15px 15px;background-color:#fff}.rich_media_area_primary:before{content:" ";position:absolute;left:0;top:0;width:100%;height:1px;border-top:1px solid #e5e5e5;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5);top:auto;bottom:-2px}.rich_media_area_primary .original_img_wrp{display:inline-block;font-size:0}.rich_media_area_primary .original_img_wrp .tips_global{display:block;margin-top:.5em;font-size:14px;text-align:right;width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.rich_media_area_extra{padding:0 15px 0}.rich_media_title{margin-bottom:10px;line-height:1.4;font-weight:400;font-size:24px}.rich_media_meta_list{margin-bottom:18px;line-height:20px;font-size:0}.rich_media_meta_list em{font-style:normal}.rich_media_meta{display:inline-block;vertical-align:middle;margin-right:8px;margin-bottom:10px;font-size:16px}.meta_original_tag{display:inline-block;vertical-align:middle;padding:1px .5em;border:1px solid #9e9e9e;color:#8c8c8c;border-top-left-radius:20% 50%;-moz-border-radius-topleft:20% 50%;-webkit-border-top-left-radius:20% 50%;border-top-right-radius:20% 50%;-moz-border-radius-topright:20% 50%;-webkit-border-top-right-radius:20% 50%;border-bottom-left-radius:20% 50%;-moz-border-radius-bottomleft:20% 50%;-webkit-border-bottom-left-radius:20% 50%;border-bottom-right-radius:20% 50%;-moz-border-radius-bottomright:20% 50%;-webkit-border-bottom-right-radius:20% 50%;font-size:15px;line-height:1.1}.meta_enterprise_tag img{width:30px;height:30px!important;display:block;position:relative;margin-top:-3px;border:0}.rich_media_meta_text{color:#8c8c8c}span.rich_media_meta_nickname{display:none}.rich_media_thumb_wrp{margin-bottom:6px}.rich_media_thumb_wrp .original_img_wrp{display:block}.rich_media_thumb{display:block;width:100%}.rich_media_content{overflow:hidden;color:#3e3e3e}.rich_media_content *{max-width:100%!important;box-sizing:border-box!important;-webkit-box-sizing:border-box!important;word-wrap:break-word!important}.rich_media_content p{clear:both;min-height:1em}.rich_media_content em{font-style:italic}.rich_media_content fieldset{min-width:0}.rich_media_content .list-paddingleft-2{padding-left:30px}.rich_media_content blockquote{margin:0;padding-left:10px;border-left:3px solid #dbdbdb}img{height:auto!important}@media screen and (device-aspect-ratio:2/3),screen and (device-aspect-ratio:40/71){.meta_original_tag{padding-top:0}}@media(min-device-width:375px) and (max-device-width:667px) and (-webkit-min-device-pixel-ratio:2){.mm_appmsg .rich_media_inner,.mm_appmsg .rich_media_meta,.mm_appmsg .discuss_list,.mm_appmsg .rich_media_extra,.mm_appmsg .title_tips .tips{font-size:17px}.mm_appmsg .meta_original_tag{font-size:15px}}@media(min-device-width:414px) and (max-device-width:736px) and (-webkit-min-device-pixel-ratio:3){.mm_appmsg .rich_media_title{font-size:25px}}@media screen and (min-width:1024px){.rich_media{width:740px;margin-left:auto;margin-right:auto}.rich_media_inner{padding:20px}body{background-color:#fff}}@media screen and (min-width:1025px){body{font-family:"Helvetica Neue",Helvetica,"Hiragino Sans GB","Microsoft YaHei",Arial,sans-serif}.rich_media{position:relative}.rich_media_inner{background-color:#fff;padding-bottom:100px}}.radius_avatar{display:inline-block;background-color:#fff;padding:3px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;overflow:hidden;vertical-align:middle}.radius_avatar img{display:block;width:100%;height:100%;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;background-color:#eee}.cell{padding:.8em 0;display:block;position:relative}.cell_hd,.cell_bd,.cell_ft{display:table-cell;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space:nowrap}.cell_primary{width:2000px;white-space:normal}.flex_cell{padding:10px 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.flex_cell_primary{width:100%;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;box-flex:1;flex:1}.original_tool_area{display:block;padding:.75em 1em 0;-webkit-tap-highlight-color:rgba(0,0,0,0);color:#3e3e3e;border:1px solid #eaeaea;margin:20px 0}.original_tool_area .tips_global{position:relative;padding-bottom:.5em;font-size:15px}.original_tool_area .tips_global:after{content:" ";position:absolute;left:0;bottom:0;right:0;height:1px;border-bottom:1px solid #dbdbdb;-webkit-transform-origin:0 100%;transform-origin:0 100%;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.original_tool_area .radius_avatar{width:27px;height:27px;padding:0;margin-right:.5em}.original_tool_area .radius_avatar img{height:100%!important}.original_tool_area .flex_cell_bd{width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.original_tool_area .flex_cell_ft{font-size:14px;color:#8c8c8c;padding-left:1em;white-space:nowrap}.original_tool_area .icon_access:after{content:" ";display:inline-block;height:8px;width:8px;border-width:1px 1px 0 0;border-color:#cbcad0;border-style:solid;transform:matrix(0.71,0.71,-0.71,0.71,0,0);-ms-transform:matrix(0.71,0.71,-0.71,0.71,0,0);-webkit-transform:matrix(0.71,0.71,-0.71,0.71,0,0);position:relative;top:-2px;top:-1px}.rich_media_global_msg{position:fixed;top:0;left:0;right:0;padding:1em 35px 1em 15px;z-index:1;background-color:#c6e0f8;color:#8c8c8c;font-size:13px}.rich_media_global_msg .icon_closed{position:absolute;right:15px;top:50%;margin-top:-5px;line-height:300px;overflow:hidden;-webkit-tap-highlight-color:rgba(0,0,0,0);background:transparent url(/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_appmsg_msg_closed_sprite.2x.png) no-repeat 0 0;width:11px;height:11px;vertical-align:middle;display:inline-block;-webkit-background-size:100% auto;background-size:100% auto}.rich_media_global_msg .icon_closed:active{background-position:0 -17px}.preview_appmsg .rich_media_title{margin-top:1.9em}@media screen and (min-width:1024px){.rich_media_global_msg{position:relative;margin:0 20px}.preview_appmsg .rich_media_title{margin-top:0}}</style>
         <style>
         
        </style>
        <!--[if lt IE 9]>
            <link onerror="wx_loaderror(this)" rel="stylesheet" type="text/css" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2c9cd6.css">
        <![endif]-->
        
    </head>
    <body id="activity-detail" class="zh_CN mm_appmsg">
        
        <script nonce="857420236" type="text/javascript">
         var write_sceen_time = (+new Date());
        </script>
        
        <div id="js_article" class="rich_media preview_appmsg">
            
            <div id="js_top_ad_area" class="top_banner">
                
            </div>
            
            
            <div class="rich_media_inner">
                <div id="page-content">
                    <div id="img-content" class="rich_media_area_primary">
                        <h2 class="rich_media_title" id="activity-name">
                            【数学】试听课案例鉴赏 
                        </h2>
                        <div class="rich_media_meta_list">
                            <em id="post-date" class="rich_media_meta rich_media_meta_text">2017-04-11</em>
                            
                            <em class="rich_media_meta rich_media_meta_text">coco 老师</em>
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
                            
                            
                            
                            
                            
                            
                            <section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="line-height: 1.8; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;"><strong style="box-sizing: border-box;"><span style="text-indent: 0em; letter-spacing: 0px; line-height: 1.8; color: rgb(249, 110, 87); box-sizing: border-box;"><strong style="line-height: 24px; white-space: normal;"><span style="color: rgb(51, 51, 51); font-family: 微软雅黑; line-height: 1.6; font-size: 24px;">引言</span></strong></span></strong></p><p style="margin: 5px 0px 0px; padding: 0px; box-sizing: border-box; text-indent: 0em; line-height: 1.5em; text-align: left;"><span style="color: rgb(51, 51, 51); font-size: 14px; line-height: 1.8; text-indent: 0em;">在线K12教育的试听课作为在互联网时空交互下，上演的一场师生素未谋面却需一见如故的直播初体验，若要修成正果，看起来似乎确是“戛戛乎其难哉”！&nbsp;</span></p><p style="margin: 5px 0px 0px; padding: 0px; box-sizing: border-box; text-indent: 0em; line-height: 1.5em; text-align: left;"><span style="color: rgb(51, 51, 51); font-size: 14px; line-height: 1.8;">辣么今天就请您跟随小编的脚步一起来尝试解锁下列三大正确姿势如何？</span></p><p><br  /></p></section></section></section><section powered-by="xiumi.us"><section><p style="text-indent: 0em; white-space: normal; line-height: 1.5em;"><span style="color: rgb(51, 51, 51); font-family: 微软雅黑; font-size: 24px; line-height: 38.4px;"><strong>案例鉴赏</strong></span></p><p style="white-space: normal; text-align: center; line-height: 1.5em;"><br  /></p></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="display: inline-block; width: 90%; border: 1px solid rgb(192, 200, 209); padding: 10px; box-shadow: rgb(220, 221, 221) 3.53553px 3.53553px 0px; box-sizing: border-box; background-color: rgb(254, 255, 255);"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: -28px 0% 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; border: 2px solid rgb(255, 255, 255); padding: 0.1em 0.3em; color: rgb(249, 110, 87); font-size: 18px; box-sizing: border-box; background-color: rgb(255, 255, 255);"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="color: rgb(61, 141, 226);"><strong style="box-sizing: border-box;">第1招 小学数学篇</strong></span></p></section></section></section><section class="Powered-by-XIUMI V5" style="box-sizing: border-box;" powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="font-size: 18px; color: rgb(95, 156, 239); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="color: rgb(61, 141, 226); font-size: 16px;">良好的课堂氛围&nbsp;<span style="color: rgb(61, 141, 226); letter-spacing: 0px; line-height: 1.6; box-sizing: border-box;">且看肖老师举重若轻、飘逸灵动之逍遥<span style="color: rgb(61, 141, 226); letter-spacing: 0px; line-height: 1.6;"></span>游</span></span></p></section></section></section></section><p style="text-indent: 0em; white-space: normal; text-align: center; line-height: 1.5em; background-color: rgb(255, 255, 255);"><span style="color: rgb(51, 51, 51); font-size: 14px; line-height: 1.6;"><br  /></span></p><p style="text-indent: 0em; white-space: normal; text-align: left; line-height: 1.5em; background-color: rgb(255, 255, 255);"><span style="color: rgb(51, 51, 51); font-size: 14px; line-height: 1.6;">肖老师平易近人的讲话风格加之风趣幽默的教学方式，让师生的愉快互动始终活跃于整节课程中。寓教于乐、鼓励先行的启发式教学，将冷峻抽象的数学内容幻化出色彩斑斓的意境美， 为过度追求理性而颇显枯燥的数学课堂平添几许温润的底色。&nbsp;</span><br  /></p><p style="text-align: left;"><span style="color: rgb(51, 51, 51); font-size: 14px; line-height: 1.6;">是啊，数学难道不美么，符号的简洁，图形的对称，公式的和谐......这样美的讲解少了许多繁重压抑的说教，多了几分轻松友好的引导，整体课堂的体验感恰和逍遥派武功潇洒如意之精髓！</span><br  /></p></section><section class="Powered-by-XIUMI V5" style="box-sizing: border-box;" powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p>

                                <p style="margin: 0px; padding: 0px;width:556px;height:417px; box-sizing: border-box; text-indent: 0em;">

                                    <iframe frameborder="0" src="https://v.qq.com/iframe/player.html?vid=o0386jrztgv&tiny=0&auto=0" allowfullscreen></iframe>

                                    <br  /></p>


                                    <p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;"><br  /></p></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="display: inline-block; width: 90%; border: 1px solid rgb(192, 200, 209); padding: 10px; box-shadow: rgb(220, 221, 221) 3.53553px 3.53553px 0px; box-sizing: border-box; background-color: rgb(254, 255, 255);"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: -28px 0% 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; border: 2px solid rgb(255, 255, 255); padding: 0.1em 0.3em; color: rgb(249, 110, 87); font-size: 18px; box-sizing: border-box; background-color: rgb(255, 255, 255);"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="color: rgb(61, 141, 226);"><strong style="box-sizing: border-box;">第2招 初中数学篇</strong></span></p></section></section></section><section class="Powered-by-XIUMI V5" style="box-sizing: border-box;" powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="font-size: 18px; color: rgb(95, 156, 239); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><strong style="box-sizing: border-box;">&nbsp;<span style="color: rgb(61, 141, 226);"></span></strong><span style="color: rgb(61, 141, 226); font-size: 16px;">严谨的逻辑思维 次看田老师否极泰来、有余不尽之亢龙有悔</span></p></section></section></section></section></section><section class="Powered-by-XIUMI V5" style="box-sizing: border-box;" powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="line-height: 1.8; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;"><span style="font-size: 14px; color: rgb(51, 51, 51);"><br  /></span></p><p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;"><span style="font-size: 14px; color: rgb(51, 51, 51);">著名的数学教育家波利亚曾指出：“数学有两个侧面, 一方面是欧几里德式的严谨科学, 从这方面看, 数学像是一门系统的演绎科学；但另一方面, 在创造过程中的数学更像是一门实验性的归纳科学。”</span></p><p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;"><span style="text-indent: 0em; color: rgb(51, 51, 51); font-size: 14px; line-height: 1.8;">田老师在教学过程中</span><strong style="text-indent: 0em; color: rgb(51, 51, 51); font-size: 14px; line-height: 1.8; box-sizing: border-box;">善于利用类比探讨, 对知识进行理线串点，对相互联系的命题进行类比分析</strong><span style="text-indent: 0em; color: rgb(51, 51, 51); font-size: 14px; line-height: 1.8;">, 以帮助学生对知识进行更深层次的理解。</span><span style="color: rgb(51, 51, 51); font-size: 14px; line-height: 1.8; letter-spacing: 0px; text-indent: 2em; box-sizing: border-box;">此外，田老师还会<strong style="box-sizing: border-box;">结合具体情境</strong>，以问题和条件, 题型结构或题设结论为思维起点，应用归纳演绎的数学思想，分析其与已有的认知结构中的共通特征, 然后在解题思维上做不同题型的迁移, 通过引导学生体验“分析”、“假设”、“结论”多种数学环节，培养学生独立思考和解决问题能力。</span><br  /></p><p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;"><span style="color: rgb(51, 51, 51); font-size: 14px; letter-spacing: 0px; line-height: 1.8; text-indent: 2em;">如此教法，“先天而天弗为”，正反相成，自是深谙“降龙”之道。</span><br  /></p></section></section></section><section class="Powered-by-XIUMI V5" style="box-sizing: border-box;" powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p><p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;">



                                        <iframe frameborder="0"  src="https://v.qq.com/iframe/player.html?vid=a0386uigpy3&tiny=0&auto=0" allowfullscreen></iframe>
                                        <br  /></p><p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;"><br  /></p>

                                    </section></section></section><section powered-by="xiumi.us"><section class="" style="display: inline-block; width: 90%; border: 1px solid rgb(192, 200, 209); padding: 10px; box-shadow: rgb(220, 221, 221) 3.53553px 3.53553px 0px; box-sizing: border-box; background-color: rgb(254, 255, 255);"><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style=" margin: -28px 0% 0px;  box-sizing: border-box; "><section class="" style="display: inline-block; border: 2px solid rgb(255, 255, 255); padding: 0.1em 0.3em; color: rgb(249, 110, 87); font-size: 18px; box-sizing: border-box; background-color: rgb(255, 255, 255);"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><span style="color: rgb(61, 141, 226);"><strong style="box-sizing: border-box;">第3招 高中数学篇</strong></span></p></section></section></section><section class="Powered-by-XIUMI V5" style="box-sizing: border-box;" powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="font-size: 18px; color: rgb(95, 156, 239); box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><strong style="box-sizing: border-box;"></strong></p><p><span style="font-family: 微软雅黑; font-size: 16px;">精准的高考动向把握 再看时老师天下武功、唯快不破之独孤九剑</span></p></section></section></section></section></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><p style="box-sizing: border-box; text-indent: 0em;"><span style="font-size: 14px;"><span style="text-indent: 0em; line-height: 1.8;"><br  /></span></span></p><p style="box-sizing: border-box; text-indent: 0em;"><span style="font-size: 14px;"><span style="text-indent: 0em; line-height: 1.8;">十二年终磨一剑， 多少高三学子渴望在千军万马过独木桥的高考混战中能杀出重围， 脱颖而出却因数学学习苦无良策、只落得夙夜空叹。当此情形之下，所幸我们有时老师在此。关于时老师之前在高考前夕通过制定针对性辅导方案，</span><strong style="text-indent: 0em; line-height: 1.8; box-sizing: border-box;">结合上海高考考情， 助力上海一普通高中学生逆袭考取上外</strong><span style="text-indent: 0em; line-height: 1.8;">的故事早已耳熟能详，此处不必赘述，各位且请看下面课程视频，眼见为实来体验啦......</span></span><br  /></p></section><section class="Powered-by-XIUMI V5" style="   box-sizing: border-box; " powered-by="xiumi.us"><section class="" style="   box-sizing: border-box; "><section class="" style="line-height: 1.8; box-sizing: border-box;"><p style="margin: 0px; padding: 0px; box-sizing: border-box;"><br style="box-sizing: border-box;"  /></p><p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;">

                                        <iframe frameborder="0" width="556px" height="417px" src="https://v.qq.com/iframe/player.html?vid=o0386tbirl4&tiny=0&auto=0" allowfullscreen></iframe>

                                        <br  /></p>


                                    <p style="margin: 0px; padding: 0px; box-sizing: border-box; text-indent: 0em;"><strong style="text-indent: 0em; line-height: 24px;"><span style="color: rgb(51, 51, 51); font-family: 微软雅黑; line-height: 1.6; font-size: 24px;">总结</span></strong><br  /></p></section></section></section><section powered-by="xiumi.us"><section><section><section powered-by="xiumi.us"><section><section><p style="margin-top: 5px;"><span style="color: rgb(51, 51, 51); font-size: 14px; line-height: 1.6;">在线1V1教育之路一如唐三藏西行取经，我们需要面对一路上各种猝不及防、扑面而来的磨人小妖精，历尽九九八十一难，方能成佛！愿接下来的日子我们一起在理优携手前行：春风化雨， 润物无声，回头再望，一路花开！</span><br  /></p></section></section></section></section><section class="" style="clear: both; box-sizing: border-box;"></section></section></section></section>
                        </div>
                        <script nonce="857420236" type="text/javascript">
                         var first_sceen__time = (+new Date());
                         
                         if ("" == 1 && document.getElementById('js_content'))
                         document.getElementById('js_content').addEventListener("selectstart",function(e){ e.preventDefault(); });
                         
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
                        
                        
                        
                        <div class="ct_mpda_wrp" id="js_sponsor_ad_area" style="display:none;">
                            
                        </div>
                        
                        
                        <div class="reward_area tc" id="js_preview_reward" style="display:none;">
                            <p id="js_preview_reward_wording" class="tips_global reward_tips" style="display:none;"></p>
                            <p>
                                <a class="reward_access" id='js_preview_reward_link' href="##">赞赏</a>
                            </p>
                        </div>
                        
                        <div class="rich_media_tool" id="js_toobar3">
                            <div id="js_read_area3" class="media_tool_meta tips_global meta_primary" style="display:none;">阅读 <span id="readNum3"></span></div>
                            
                            <span style="display:none;" class="media_tool_meta meta_primary tips_global meta_praise" id="like3">
                                <i class="icon_praise_gray"></i><span class="praise_num" id="likeNum3"></span>
                            </span>
                            
                            <a id="js_report_article3" style="display:none;" class="media_tool_meta tips_global meta_extra" href="##">投诉</a>
                            
                        </div>
                        
                        
                        
                    </div>
                    
                    <div class="rich_media_area_primary sougou" id="sg_tj" style="display:none">
                        
                    </div>
                    
                    <div class="rich_media_area_extra">
                        
                        
                        <div class="mpda_bottom_container" id="js_bottom_ad_area">
                            
                        </div>
                        
                        <div id="js_iframetest" style="display:none;"></div>
                        
                        <div class="rich_media_extra" id="js_preview_cmt" style="display:none">
                            <p class="discuss_icon_tips rich_split_tips tr">
                                <a href="##" id="js_preview_cmt_write">写留言<img class="icon_edit" src="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_edit25ded2.png"></a>
                            </p>
                        </div>
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
        
        
        
        <script nonce="857420236">
         var __DEBUGINFO = {
             debug_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/debug/console34c264.js",
             safe_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/moonsafe34c264.js",
             res_list: []
         };
        </script>
        
        <script nonce="857420236">
         (function() {
             function _addVConsole(uri) {
                 var url = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/vconsole/' + uri;
                 document.write('<script nonce="857420236" type="text/javascript" src="' + url + '"><\/script>');
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
        
        <script nonce="857420236" type="text/javascript">

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
                         images[i].src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkJDQzA1MTVGNkE2MjExRTRBRjEzODVCM0Q0NEVFMjFBIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkJDQzA1MTYwNkE2MjExRTRBRjEzODVCM0Q0NEVFMjFBIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QkNDMDUxNUQ2QTYyMTFFNEFGMTM4NUIzRDQ0RUUyMUEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QkNDMDUxNUU2QTYyMTFFNEFGMTM4NUIzRDQ0RUUyMUEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6p+a6fAAAAD0lEQVR42mJ89/Y1QIABAAWXAsgVS/hWAAAAAElFTkSuQmCC";
                     }
                 } else {
                     images[i].style.cssText += ";visibility: hidden !important;";
                 }
                 images[i].style.cssText += ";height: " + height + "px !important;";
             }       
         }
         occupyImg();
        </script>
        <script nonce="857420236" type="text/javascript">
         
         var not_in_mm_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm322696.css";
         var windowwx_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_winwx31619e.css";
         var article_improve_combo_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo351d3c.css";
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
         var ct = "1490343460";
         var publish_time = "2017-04-11" || "";
         var user_name = "gh_fb5ad07c1976";
         var user_name_new = "";
         var fakeid   = "";
         var version   = "";
         var is_limit_user   = "0";
         var round_head_img = "http://mmbiz.qpic.cn/mmbiz_png/cBWf565lml7Dthv8uxr3R2HlDscXmU7Vic92j3qGaicPLUgQm7oIKF4DOW3wFzqv6FvwyzicRlKliapd8OCUD1oJicQ/0?wx_fmt=png";
         var ori_head_img_url = "http://wx.qlogo.cn/mmhead/Q3auHgzwzM7PLhZmH6edZO99D8c00uNIJCSW4fFibzbwETohJuofD1w/132";
         var msg_title = "【数学】试听课案例鉴赏";
         var msg_desc = "上好高质量试听课之必备技能，老师大大们快来看看~";
         var msg_cdn_url = "http://mmbiz.qpic.cn/mmbiz_png/cBWf565lml4sqb8Xr7LTXMyUia5bFziaqyaqfslQr5sWpTc3hqz3KF0QLpAmmLjeI6xNGlNic2PibPynJSIHrU903w/0?wx_fmt=png";
         var msg_link = "http://mp.weixin.qq.com/s?__biz=MzI5MDQzMTAxNQ==\x26amp;tempkey=2KiFAPPB599rzbT2uxy%2FEJkuteAicPopi2hE4660KX6bDMgeWkOT73lzuOYyXGLYIDdFnaxo9CkLtpEPixAyrzyvMAGaWzlfP%2FoDAmVAvF7Wvtvd%2Ba1Uj98288HGxcR9hLCZ3RoCj1bZhp1JwSaeQw%3D%3D\x26amp;chksm=6c21485f5b56c14909767ff253b21e377731b3f5021b9455ec2ae0e4f535a8a067f3ca815cf7#rd";
         var user_uin = "0"*1;
         var msg_source_url = '';
         var img_format = 'png';
         var srcid = '';
         var req_id = '1509OXfszySyN3MgzJN2tEs1';
         var networkType;
         var appmsgid = '' || '100000023'|| "100000023";
         var comment_id = "0" * 1;
         var comment_enabled = "" * 1;
         var is_need_reward = "0" * 1;
         var is_https_res = ("" * 1) && (location.protocol == "https:");
         var msg_daily_idx = "0" || "";
         var profileReportInfo = "" || "";
         
         var devicetype = "";
         var source_encode_biz = "";


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
         var read_num = "12" * 1;
         var like_num = "0" * 1;
         var liked = "false" == 'true' ? true : false;
         var is_temp_url = "2KiFAPPB599rzbT2uxy/EJkuteAicPopi2hE4660KX6bDMgeWkOT73lzuOYyXGLYIDdFnaxo9CkLtpEPixAyrzyvMAGaWzlfP/oDAmVAvF7Wvtvd\x26nbsp;a1Uj98288HGxcR95pGTfMpjQLD64pNE1XT5YA==" ? 1 : 0;
         var send_time = "1492218828";
         var icon_emotion_switch = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/emotion/icon_emotion_switch.2x2f1273.png";
         var icon_emotion_switch_active = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/emotion/icon_emotion_switch_active.2x2f1273.png";
         var icon_loading_white = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/icon_loading_white2805ea.gif";
         var icon_audio_unread = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/audio/icon_audio_unread26f1f1.png";
         var icon_qqmusic_default = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/qqmusic/icon_qqmusic_default.2x26f1f1.png";
         var icon_qqmusic_source = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/qqmusic/icon_qqmusic_source263724.png";
         
         var topic_default_img = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/topic/pic_book_thumb.2x2e4987.png';
         




         

         var ban_scene = "0" * 1;
         
         var svr_time = "1492218865" * 1;

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
        
        <script nonce="857420236">window.__moon_host = 'res.wx.qq.com';window.__moon_mainjs = 'appmsg/index.js';window.moon_map = {"a/appdialog_confirm.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm.html34f0d8.js","widget/wx_profile_dialog_primary.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/widget/wx_profile_dialog_primary.css34f0d8.js","appmsg/emotion/caret.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/caret278965.js","a/appdialog_confirm.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm34c32a.js","biz_wap/jsapi/cardticket.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/cardticket34c264.js","biz_common/utils/emoji_panel_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_panel_data3518c6.js","biz_common/utils/emoji_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_data3518c6.js","appmsg/emotion/textarea.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/textarea3518c6.js","appmsg/emotion/nav.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/nav278965.js","appmsg/emotion/common.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/common3518c6.js","appmsg/emotion/slide.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/slide2a9cd9.js","pages/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/report340996.js","pages/music_player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_player34c3cf.js","pages/loadscript.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/loadscript30203e.js","appmsg/emotion/dom.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/dom31ff31.js","biz_wap/utils/fakehash.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/fakehash34c264.js","biz_common/utils/wxgspeedsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/wxgspeedsdk3518c6.js","a/sponsor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor3189b5.js","a/app_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/app_card34f0d8.js","a/ios.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/ios333f3d.js","a/android.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/android2c5484.js","a/profile.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/profile31ff31.js","a/sponsor_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor_a_tpl.html32c414.js","a/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_tpl.html32c414.js","a/mpshop.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/mpshop311179.js","a/card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/card311179.js","biz_wap/utils/position.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/position34c264.js","a/a_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_report32e586.js","appmsg/my_comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/my_comment_tpl.html325336.js","appmsg/cmt_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cmt_tpl.html348fa1.js","sougou/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/a_tpl.html2c6e7c.js","appmsg/emotion/emotion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/emotion351ce6.js","biz_wap/utils/wapsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/wapsdk34c264.js","biz_common/utils/monitor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/monitor3518c6.js","biz_common/utils/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/report3518c6.js","appmsg/open_url_with_webview.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/open_url_with_webview3145f0.js","biz_common/utils/http.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/http3518c6.js","biz_common/utils/cookie.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/cookie3518c6.js","appmsg/topic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/topic_tpl.html31ff31.js","pages/voice_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_tpl.html2f2e72.js","pages/voice_component.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_component34c3cf.js","pages/qqmusic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_tpl.html32c414.js","new_video/ctl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/ctl2d441f.js","a/testdata.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/testdata34c32a.js","appmsg/reward_entry.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/reward_entry3004a4.js","appmsg/comment.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment348fa1.js","appmsg/like.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/like2eb52b.js","pages/version4video.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/version4video33de59.js","a/a.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a34f0d8.js","rt/appmsg/getappmsgext.rt.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/rt/appmsg/getappmsgext.rt2c21f6.js","biz_wap/utils/storage.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/storage34c264.js","biz_common/tmpl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/tmpl3518c6.js","appmsg/img_copyright_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/img_copyright_tpl.html2a2c13.js","biz_common/ui/imgonepx.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/ui/imgonepx3518c6.js","biz_common/utils/respTypes.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/respTypes3518c6.js","biz_wap/utils/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/log34c264.js","sougou/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/index3420dc.js","biz_wap/safe/mutation_observer_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/mutation_observer_report34c264.js","appmsg/fereport.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/fereport33a3b2.js","appmsg/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report3404b3.js","appmsg/report_and_source.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report_and_source34c49d.js","appmsg/page_pos.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/page_pos3404b3.js","appmsg/cdn_speed_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_speed_report3097b2.js","appmsg/wxtopic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/wxtopic31a3be.js","appmsg/voice.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/voice310e30.js","appmsg/qqmusic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/qqmusic31623d.js","appmsg/iframe.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/iframe3408af.js","appmsg/review_image.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/review_image347b7e.js","appmsg/outer_link.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/outer_link275627.js","biz_wap/jsapi/core.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/core34c264.js","appmsg/copyright_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/copyright_report2ec4b2.js","appmsg/async.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/async34a2e3.js","biz_wap/ui/lazyload_img.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/ui/lazyload_img34c264.js","biz_common/log/jserr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/log/jserr3518c6.js","appmsg/share.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share34f1ed.js","appmsg/cdn_img_lib.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_img_lib34ce9e.js","biz_common/utils/url/parse.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/url/parse3518c6.js","page/appmsg/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm.css32c99a.js","page/appmsg/page_mp_article_improve_combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo.css351d3c.js","biz_common/dom/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/event3518c6.js","appmsg/test.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/test351745.js","biz_wap/utils/mmversion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/mmversion34c264.js","appmsg/max_age.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/max_age2fdd28.js","biz_common/dom/attr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/attr3518c6.js","biz_wap/utils/ajax.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/ajax34c264.js","appmsg/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/log300330.js","biz_common/dom/class.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/class3518c6.js","biz_wap/utils/device.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/device34c264.js","biz_wap/jsapi/a8key.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/a8key34c264.js","biz_common/utils/string/html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/string/html3518c6.js","appmsg/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/index351c69.js"};</script><script nonce="857420236" type="text/javascript">(function(){function d(a){window.__wxgspeeds.moonls_loadjs_begin=+new Date;var c=document.createElement("script");document.getElementsByTagName("body")[0].appendChild(c);c.type="text/javascript";c.async="async";;c.setAttribute('onerror', 'wx_loaderror');c.onload=function(){a&&f()};c.src=b;window.__wxgspeeds.moonls_loadjs_end=+new Date}function f(){window.__wxgspeeds.moonls_save_begin=+new Date;localStorage.setItem("__WXLS__moon",String(__moonf__));localStorage.setItem("__WXLS__moonarg",JSON.stringify({version:b,method:""}));window.__wxgspeeds.moonls_save_end=+new Date}var a=!!top&&!!top.window&&top.window.user_uin||0,e=0!==a&&1>Math.floor(a/100)%100;if(2876363900==a||1506075==a||942807682==a)e=!0;var b="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/moon34ef4b.js";window.__loadAllResFromMp&&(b=b.replace("res.wx.qq.com","mp.weixin.qq.com"),(new Image).src=location.protocol+"//mp.weixin.qq.com/mp/jsmonitor?idkey=27613_12_1");window.__wxgspeeds||(window.__wxgspeeds={});if("function"==typeof __moonf__)__moonf__(),e&&localStorage&&f();else if(window.__wxgspeeds.moonloadtime=+new Date,e&&localStorage)try{var g=JSON.parse(localStorage.getItem("__WXLS__moonarg"))||{};if(g&&g.version==b){var h=localStorage.getItem("__WXLS__moon");localStorage.setItem("__WXLS__moonarg",JSON.stringify({version:b,method:"fromls"}));window.__moonls_fromls=!0;window.__wxgspeeds.moonls_loadls_end=+new Date;eval(h);__moonf__()}else d(!0)}catch(k){window.__moonls_fail=!0,d(!0)}else d(!1)})();</script>
        <script nonce="857420236" type="text/javascript">
         var real_show_page_time = +new Date();
         if (!!window.addEventListener){
             window.addEventListener("load", function(){
                 window.onload_endtime = +new Date();
             });
         }
         
        </script>
        
    </body>
    <script nonce="857420236" type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
</html>
<!--tailTrap<body></body><head></head><html></html>-->


