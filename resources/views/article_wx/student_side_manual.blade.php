<!DOCTYPE html>
<!--headTrap<body></body><head></head><html></html>--><html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" >
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">

        
        <script nonce="208764107" type="text/javascript">
         window.logs = {
             pagetime: {}
         };
         window.logs.pagetime['html_begin'] = (+new Date());
        </script>
        
        <script nonce="208764107" type="text/javascript">
         var biz = "MzAxMTUxMDcwMw=="||"";
         var sn = "" || ""|| "b7d0561859f6ac74bf0e9591d6aa63e1";
         var mid = "506210022" || ""|| "506210022";
         var idx = "1" || "" || "1";
         window.__allowLoadResFromMp = true;

        </script>
        <script nonce="208764107" type="text/javascript">
         var page_begintime=+new Date,is_rumor="",norumor="";
         1*is_rumor&&!(1*norumor)&&biz&&mid&&(document.referrer&&-1!=document.referrer.indexOf("mp.weixin.qq.com/mp/rumor")||(location.href="http://mp.weixin.qq.com/mp/rumor?action=info&__biz="+biz+"&mid="+mid+"&idx="+idx+"&sn="+sn+"#wechat_redirect")),
             document.domain="qq.com";
        </script> 
        <script nonce="208764107" type="text/javascript">
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
        <script nonce="208764107" type="text/javascript">
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
        
        <title>理优1对1学生软件用户使用手册</title>
        
        <style>html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;line-height:1.6}body{-webkit-touch-callout:none;font-family:-apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;background-color:#f3f3f3;line-height:inherit}body.rich_media_empty_extra{background-color:#fff}body.rich_media_empty_extra .rich_media_area_primary:before{display:none}h1,h2,h3,h4,h5,h6{font-weight:400;font-size:16px}*{margin:0;padding:0}a{color:#607fa6;text-decoration:none}.rich_media_inner{font-size:16px;word-wrap:break-word;-webkit-hyphens:auto;-ms-hyphens:auto;hyphens:auto}.rich_media_area_primary{position:relative;padding:20px 15px 15px;background-color:#fff}.rich_media_area_primary:before{content:" ";position:absolute;left:0;top:0;width:100%;height:1px;border-top:1px solid #e5e5e5;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5);top:auto;bottom:-2px}.rich_media_area_primary .original_img_wrp{display:inline-block;font-size:0}.rich_media_area_primary .original_img_wrp .tips_global{display:block;margin-top:.5em;font-size:14px;text-align:right;width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.rich_media_area_extra{padding:0 15px 0}.rich_media_title{margin-bottom:10px;line-height:1.4;font-weight:400;font-size:24px}.rich_media_meta_list{margin-bottom:18px;line-height:20px;font-size:0}.rich_media_meta_list em{font-style:normal}.rich_media_meta{display:inline-block;vertical-align:middle;margin-right:8px;margin-bottom:10px;font-size:16px}.meta_original_tag{display:inline-block;vertical-align:middle;padding:1px .5em;border:1px solid #9e9e9e;color:#8c8c8c;border-top-left-radius:20% 50%;-moz-border-radius-topleft:20% 50%;-webkit-border-top-left-radius:20% 50%;border-top-right-radius:20% 50%;-moz-border-radius-topright:20% 50%;-webkit-border-top-right-radius:20% 50%;border-bottom-left-radius:20% 50%;-moz-border-radius-bottomleft:20% 50%;-webkit-border-bottom-left-radius:20% 50%;border-bottom-right-radius:20% 50%;-moz-border-radius-bottomright:20% 50%;-webkit-border-bottom-right-radius:20% 50%;font-size:15px;line-height:1.1}.meta_enterprise_tag img{width:30px;height:30px!important;display:block;position:relative;margin-top:-3px;border:0}.rich_media_meta_text{color:#8c8c8c}span.rich_media_meta_nickname{display:none}.rich_media_thumb_wrp{margin-bottom:6px}.rich_media_thumb_wrp .original_img_wrp{display:block}.rich_media_thumb{display:block;width:100%}.rich_media_content{overflow:hidden;color:#3e3e3e}.rich_media_content *{max-width:100%!important;box-sizing:border-box!important;-webkit-box-sizing:border-box!important;word-wrap:break-word!important}.rich_media_content p{clear:both;min-height:1em}.rich_media_content em{font-style:italic}.rich_media_content fieldset{min-width:0}.rich_media_content .list-paddingleft-2{padding-left:30px}.rich_media_content blockquote{margin:0;padding-left:10px;border-left:3px solid #dbdbdb}img{height:auto!important}@media screen and (device-aspect-ratio:2/3),screen and (device-aspect-ratio:40/71){.meta_original_tag{padding-top:0}}@media(min-device-width:375px) and (max-device-width:667px) and (-webkit-min-device-pixel-ratio:2){.mm_appmsg .rich_media_inner,.mm_appmsg .rich_media_meta,.mm_appmsg .discuss_list,.mm_appmsg .rich_media_extra,.mm_appmsg .title_tips .tips{font-size:17px}.mm_appmsg .meta_original_tag{font-size:15px}}@media(min-device-width:414px) and (max-device-width:736px) and (-webkit-min-device-pixel-ratio:3){.mm_appmsg .rich_media_title{font-size:25px}}@media screen and (min-width:1024px){.rich_media{width:740px;margin-left:auto;margin-right:auto}.rich_media_inner{padding:20px}body{background-color:#fff}}@media screen and (min-width:1025px){body{font-family:"Helvetica Neue",Helvetica,"Hiragino Sans GB","Microsoft YaHei",Arial,sans-serif}.rich_media{position:relative}.rich_media_inner{background-color:#fff;padding-bottom:100px}}.radius_avatar{display:inline-block;background-color:#fff;padding:3px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;overflow:hidden;vertical-align:middle}.radius_avatar img{display:block;width:100%;height:100%;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;background-color:#eee}.cell{padding:.8em 0;display:block;position:relative}.cell_hd,.cell_bd,.cell_ft{display:table-cell;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space:nowrap}.cell_primary{width:2000px;white-space:normal}.flex_cell{padding:10px 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.flex_cell_primary{width:100%;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;box-flex:1;flex:1}.original_tool_area{display:block;padding:.75em 1em 0;-webkit-tap-highlight-color:rgba(0,0,0,0);color:#3e3e3e;border:1px solid #eaeaea;margin:20px 0}.original_tool_area .tips_global{position:relative;padding-bottom:.5em;font-size:15px}.original_tool_area .tips_global:after{content:" ";position:absolute;left:0;bottom:0;right:0;height:1px;border-bottom:1px solid #dbdbdb;-webkit-transform-origin:0 100%;transform-origin:0 100%;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.original_tool_area .radius_avatar{width:27px;height:27px;padding:0;margin-right:.5em}.original_tool_area .radius_avatar img{height:100%!important}.original_tool_area .flex_cell_bd{width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.original_tool_area .flex_cell_ft{font-size:14px;color:#8c8c8c;padding-left:1em;white-space:nowrap}.original_tool_area .icon_access:after{content:" ";display:inline-block;height:8px;width:8px;border-width:1px 1px 0 0;border-color:#cbcad0;border-style:solid;transform:matrix(0.71,0.71,-0.71,0.71,0,0);-ms-transform:matrix(0.71,0.71,-0.71,0.71,0,0);-webkit-transform:matrix(0.71,0.71,-0.71,0.71,0,0);position:relative;top:-2px;top:-1px}.rich_media_global_msg{position:fixed;top:0;left:0;right:0;padding:1em 35px 1em 15px;z-index:1;background-color:#c6e0f8;color:#8c8c8c;font-size:13px}.rich_media_global_msg .icon_closed{position:absolute;right:15px;top:50%;margin-top:-5px;line-height:300px;overflow:hidden;-webkit-tap-highlight-color:rgba(0,0,0,0);background:transparent url(/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_appmsg_msg_closed_sprite.2x.png) no-repeat 0 0;width:11px;height:11px;vertical-align:middle;display:inline-block;-webkit-background-size:100% auto;background-size:100% auto}.rich_media_global_msg .icon_closed:active{background-position:0 -17px}.preview_appmsg .rich_media_title{margin-top:1.9em}@media screen and (min-width:1024px){.rich_media_global_msg{position:relative;margin:0 20px}.preview_appmsg .rich_media_title{margin-top:0}}</style>
         <style>
         
        </style>
        <!--[if lt IE 9]>
            <link onerror="wx_loaderror(this)" rel="stylesheet" type="text/css" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2c9cd6.css">
        <![endif]-->
        
    </head>
    <body id="activity-detail" class="zh_CN mm_appmsg">
        
        <script nonce="208764107" type="text/javascript">
         var write_sceen_time = (+new Date());
        </script>
        
        <div id="js_article" class="rich_media preview_appmsg">
            
            <div id="js_top_ad_area" class="top_banner">
                
            </div>
            
            
            <div class="rich_media_inner">
                <div id="page-content">
                    <div id="img-content" class="rich_media_area_primary">
                        <h2 class="rich_media_title" id="activity-name">
                            理优1对1学生软件用户使用手册 
                        </h2>
                        <div class="rich_media_meta_list">
                            <em id="post-date" class="rich_media_meta rich_media_meta_text">2016-09-03</em>
                            
                            <em class="rich_media_meta rich_media_meta_text">理优产品经理</em>
                            <a class="rich_media_meta rich_media_meta_link rich_media_meta_nickname" href="##" id="post-user">理优教育在线学习</a>
                            <span class="rich_media_meta rich_media_meta_text rich_media_meta_nickname">理优教育在线学习</span>
                            
                            <div id="js_profile_qrcode" class="profile_container" style="display:none;">
                                <div class="profile_inner">
                                    <strong class="profile_nickname">理优教育在线学习</strong>
                                    <img class="profile_avatar" id="js_profile_qrcode_img" src="" alt="">
                                    
                                    <p class="profile_meta">
                                        <label class="profile_meta_label">微信号</label>
                                        <span class="profile_meta_value">leomath100</span>
                                    </p>
                                    
                                    <p class="profile_meta">
                                        <label class="profile_meta_label">功能介绍</label>
                                        <span class="profile_meta_value">理优教育专注于初高中学生在线1对1实时教学，教学团队经验丰富、由在校老师、知名机构老师和全职教研老师组成。</span>
                                    </p>
                                    
                                </div>
                                <span class="profile_arrow_wrp" id="js_profile_arrow_wrp">
                                    <i class="profile_arrow arrow_out"></i>
                                    <i class="profile_arrow arrow_in"></i>
                                </span>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        
                        
                        <div class="rich_media_content " id="js_content">
                            
                            
                            
                            
                            
                            
                            <section class="135editor" style="box-sizing: border-box; border: 0px none;" data-id="24"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);"><p><span style="font-size: 18px; color: rgb(0, 128, 255);"><strong>一：如何下载理优1对1学生软件</strong></span></p></section></section><p><strong><span style="line-height: 24px; font-family: 微软雅黑;"><br  /></span></strong></p><p><span style="line-height: 24px; font-family: 微软雅黑;">1.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>Ipad用户以在App Store（苹果应用商店）搜索“理优1对1”下载</span></p><p><strong><span style="line-height: 24px; font-family: 微软雅黑;"><br  /></span></strong></p><p><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%AD%A6%E7%94%9F1.gif" style="" data-ratio="1.3853333333333333" data-w="750"  /></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-size:16px;font-family:微软雅黑">2. &nbsp;安卓平板用户可以在各大应用商店搜索“理优1对1“下载<img data-type="gif"  src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/0%20%281%29.gif  "    data-ratio="0.72" data-w="750"  /><br  /></span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px">3<span style="font-family: 微软雅黑; line-height: 24px; white-space: pre-wrap;">. &nbsp;Ipad用户扫码下载</span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-family: 微软雅黑;">a . 使用ipad（IOS系统）</span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-size:16px;font-family:微软雅黑">b . 打开淘宝，天猫，京东，1号店等等（除微信，QQ）任意软件的扫码工具</span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-size:16px;font-family:微软雅黑">c . 扫一扫如下二维码，即可跳转App Store，“理优1对1学生”App下载页面</span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-size:16px;font-family:微软雅黑">d . 点击下载安装即可完成下载</span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-family: 微软雅黑; line-height: 24px; white-space: pre-wrap;"><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%AD%A6%E7%94%9FIPad.gif" data-ratio="0.72" data-w="750"  /><br  /></span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-family: 微软雅黑; line-height: 24px; white-space: pre-wrap;">4. 安卓平板用户扫码下载</span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-family: 微软雅黑;">a．使用安卓平板（安卓系统）</span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-size:16px;font-family:微软雅黑">b . 打开淘宝，天猫，京东，1号店等等（除微信，QQ）任意软件的扫码工具</span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-size:16px;font-family:微软雅黑">c . 扫一扫如下二维码，即可跳转到应用商店，“理优1对1学生”App下载页面</span></p><p style="margin-top:8px;margin-bottom:.1px;line-height:24px"><span style="font-size:16px;font-family:微软雅黑">d . 点击下载安装即可完成下载</span></p><p><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/4-%E5%AE%89%E5%8D%93%E7%89%88%E5%AE%89%E8%A3%85.gif" data-ratio="0.72" data-w="750"  /><br  /></p><p><br  /></p><p><span style="color: rgb(255, 0, 0);"> &nbsp; &nbsp; &nbsp; &nbsp;理优学生软件二维码快扫我</span></p><p><br  /></p><p><img data-s="300,640" data-type="png" src=" http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E9%80%89%E5%8C%BA_007.png" data-ratio="1" data-w="746"  /><br  /></p><p><br  /></p><p><br  /></p><section class="135editor" style="box-sizing: border-box; border: 0px none;" data-id="24"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);"><p><span style="font-size: 18px; color: rgb(0, 128, 255);"><strong>二：如何注册理优1对1帐号</strong></span></p></section></section><p><span style="color: rgb(62, 62, 62); line-height: 25.6px; white-space: pre-wrap; background-color: rgb(255, 255, 255);"><br  /></span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px; white-space: pre-wrap; background-color: rgb(255, 255, 255);">下载后点击进入APP，在下方导航栏中找到“我的，课表，写作业”后会提示登入或注册账号，选泽注册账号后填写信息，使用您的手机号注册，并设置6位及以上密码。</span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px; white-space: pre-wrap; background-color: rgb(255, 255, 255);"><br  /></span></p><p><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/0%20%2811%29.gif " style="" data-ratio="0.72" data-w="750"  /></p><p><br  /></p><section class="135editor" style="box-sizing: border-box; border: 0px none;" data-id="24"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);"><p><span style="color: rgb(0, 128, 255); background-color: rgb(255, 255, 255);"><strong><span style="font-size: 18px; background-color: rgb(255, 255, 255);">三：如何进入理优1对1课堂上课</span></strong></span></p></section></section><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);"><br  /></span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);">第一种方式：上课前5分钟，系统会发消息通知你上课，点击系统消息即可进入课堂。</span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);"><br  /></span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);"><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/0%20%285%29.gif " data-ratio="0.72" data-w="750"  /><br  /></span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);"><br  /></span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);">第二种方式：在下方菜单栏中的“课表”中“红色”区域（已开始课程）后弹出课程的详情介绍，点击“进入课堂”后会进行系统检测（包括软件的内存，麦克风，拍照功能，网速），确保系统没有问题后即可进入课堂上课。</span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);"><br  /></span></p><p><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/0%20%286%29.gif                                                                                                                                                        " data-ratio="0.72" data-w="750"  /><br  /></p><p><br  /></p><p><br  /></p><section class="135editor" style="box-sizing: border-box; border: 0px none;" data-id="24"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);"><p><span style="color: rgb(0, 128, 255);"><strong><span style="font-size: 18px;">四.如何预约免费公开课</span></strong></span></p></section></section><p><br  /></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);">理优教育会定期开设“家庭教育”、“学习方法”和“各重难点知识点讲解”的免费公开课提供给理优所有用户，从课程页面点击“免费课程”即可看到当前可预约的所有课程，点击“立即预约”即可成功预约公开课。</span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);"><br  /></span></p><p><span style="color: rgb(62, 62, 62); line-height: 25.6px;  background-color: rgb(255, 255, 255);"><br  /><img data-type="gif" src=" http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/0%20%287%29.gif " data-ratio="0.72" data-w="750"  /><br  /></span></p><p><br  /></p><section class="135editor" style="box-sizing: border-box; border: 0px none;" data-id="24"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);"><p><span style="color: rgb(0, 128, 255);"><strong><span style="font-size: 18px;">五：如何兑换商城礼物</span></strong></span></p></section></section><p><br  /></p><p>理优小店是理优小学员们最喜欢的功能，只要你平时上课表现好，作业完成情况好，课后经常复习回看视频，你就可以获得👍，通过👍可以兑换商城的礼物。对于实物类礼物兑换时填写好收货信息（姓名，地址，手机号）我们理优助教老师就会给你寄送精美的礼品。</p><p><br  /></p><p><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/0%20%288%29.gif " data-ratio="0.72" data-w="750"  /><br  /></p><p><br  /></p><section class="135editor" data-id="24" style="line-height: 25.6px; white-space: normal; box-sizing: border-box; border: 0px none;"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);"><p><span style="color: rgb(0, 128, 255);"><strong><span style="font-size: 18px;">六：如何预习讲义<br  /></span></strong></span></p></section></section><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑"><br  /></span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">1.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击课表中对应的课程</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">2.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>打开课表详情页，点击“课程讲义”</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">3.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击下方缩略图或者手指向右滑动即可切换页面</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">4.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击屏幕即可隐藏工具栏全屏查看讲义</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑"><br  /></span></p><p style="line-height: 25.6px;"><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/0%20%289%29.gif " data-ratio="0.72" data-w="750"  /><br  /></p><section class="135editor" data-id="24" style="line-height: 25.6px; white-space: normal; box-sizing: border-box; border: 0px none;"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);"><p><span style="color: rgb(0, 128, 255);"><strong><span style="font-size: 18px;">七：如何回看讲课视频</span></strong></span></p></section></section><p style="font-family: 8px -22px 0 0;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑"><br  /></span></p><p style="font-family: 8px -22px 0 0;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">第一种方式</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">1.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击“我的课程”按钮</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">2.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>找到你需要查看的课程并点击“查看视频”按钮</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">3.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>等待视频加载完成</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">4.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击播放按钮即可播放课堂视频</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">5.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击快进按钮即可切换播放速度</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑"><br  /></span></p><p style="font-family: 8px -22px 0 0;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">第二种方式</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">1.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击我的消息找到你需要查看的课程视频</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">2.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>打开视频即可回看课堂上课情况，拖动滑块可快进</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑"><br  /></span></p><p style="line-height: 25.6px;"><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/7-%E4%B8%8B%E8%BD%BD.gif" data-ratio="0.72" data-w="750"  /><br  /></p><p style="line-height: 25.6px;"><br  /></p><section class="135editor" data-id="24" style="line-height: 25.6px; white-space: normal; box-sizing: border-box; border: 0px none;"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);"><p><span style="color: rgb(0, 128, 255);"><strong>
                                <span style="font-size: 18px;">八：如何做作业<br  /></span></strong></span></p></section></section>
                                <p style="line-height: 24px;">
                                    <span style="font-family: 微软雅黑;"><br  />
                                </span>
                                </p>
                                <p><span>一、 平板端使用方式:</span></p>
                                <p style="line-height: 24px;"><span style="font-family: 微软雅黑;">1.<span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>点击课表中对应的课程</span></p><p style="line-height: 24px;"><span style="font-family: 微软雅黑;">2.<span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>打开课表详情页，点击“课程讲义”</span></p><p style="line-height: 24px;"><span style="font-family: 微软雅黑;">3.<span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>点击下方缩略图或者手指向右滑动即可切换页面</span></p><p style="line-height: 24px;"><span style="font-family: 微软雅黑;">4.<span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>点击屏幕即可隐藏工具栏全屏查看讲义</span></p><p style="line-height: 24px;"><span style="font-family: 微软雅黑;"><br  /></span></p><p style="line-height: 25.6px;"><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/8-%E4%BD%9C%E4%B8%9A.gif " data-ratio="0.72" data-w="750"  /><br  /></p>

                                <p><span>二、 电脑端使用方式:</span></p>
                                <p style="line-height: 24px;">
                                    <span style="font-family: 微软雅黑;">1.<span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;
                                    </span>
                                    登录【理优1对1官网】 <a href="http://www.leo1v1.com/download.html">http://www.leo1v1.com/download.html</a></span></p><p style="line-height: 24px;"><span style="font-family: 微软雅黑;">
                                        2.<span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>在导航栏选择【应用下载】</span></p>
                                    <p style="line-height: 24px;"><span style="font-family: 微软雅黑;">3.
                                        <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>找到理优客服端下载，选择【PC电脑】，点击【PDF作业编辑器】进行下载
                                    </span></p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">4.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>下载完成后打开软件，点击【快速安装】</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">5.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>打开【理优1对1-学生端】，登录账户</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">6.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>选择【作业中心】，点击【下载作业】</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">7.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>点击右上房【下载按钮】进行下载</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">8.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>选择文件下载位置，点击【保存】</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">9.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>使用【福昕阅读器】打开文档；点击【注释】，选择【铅笔】，进行答题</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">10.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>完成作业后，选择【文件】，点击【保存】</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">11.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>进入【理优1对1-学生端】，选择【作业中心】，点击【上传作业】</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">12.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>选择完成的作业，点击【打开】</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">13.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>点击【提交】</span>
                                    </p>
                                    <p style="line-height: 24px;">
                                        <span style="font-family: 微软雅黑;">14.
                                            <span style="font-stretch: normal; font-size: 9px; line-height: normal;">&nbsp;&nbsp;&nbsp;</span>作业上传成功</span>
                                    </p>


                                    <p style="line-height: 24px;"><span style="font-family: 微软雅黑;"><br  /></span></p><p style="line-height: 25.6px;"><img data-type="gif" src=" http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E6%95%99%E8%82%B2%E5%9C%A8%E7%BA%BF-%E5%AD%A6%E7%94%9F%E8%BD%AF%E4%BB%B6%E4%BD%BF%E7%94%A8%E6%89%8B%E5%86%8C/%E5%AD%A6%E7%94%9F%E7%AB%AF.gif " data-ratio="0.72" data-w="750"  /><br  /></p><section class="135editor" data-id="24" style="line-height: 25.6px; white-space: normal; box-sizing: border-box; border: 0px none;"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);">







                                    <p><span style="color: rgb(0, 128, 255);"><strong><span style="font-size: 18px;">九：如何查看上课进度<br  /></span></strong></span></p></section></section><p style="line-height: 24px;"><span style="font-family: 微软雅黑;"><br  /></span></p><p style="line-height: 24px;"><span style="font-family: 微软雅黑;"></span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">1.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击我的“课程消耗“按钮，当前页面即可看到1对1的课程的进度</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">2.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击页面“小班课“按钮即可切换查看小班课的进度</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">3.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击页面上“查看明细”</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">4.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>页面上即可展示小班课消耗情况</span></p><p style="line-height: 24px;"><span style="font-family: 微软雅黑;"></span><br  /></p><p><span style="font-family: 微软雅黑;"><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/9-%E8%BF%9B%E5%BA%A6.gif" data-ratio="0.72" data-w="750"  /><br  /></span></p><p><br  /></p><section class="135editor" data-id="24" style="line-height: 25.6px; white-space: normal; box-sizing: border-box; border: 0px none;"><section class="135brush layout" style="margin: 3px auto; padding: 15px; color: rgb(62, 62, 62); line-height: 24px; box-shadow: rgb(170, 170, 170) 0px 0px 3px; border: 2px solid rgb(240, 240, 240);"><p><span style="color: rgb(0, 128, 255);"><strong><span style="font-size: 18px;">十：如何修改密码<br  /></span></strong></span></p></section></section><p style="line-height: 24px;"><span style="font-family: 微软雅黑;"></span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑"><br  /></span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">1.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>点击“忘记密码”按钮</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">2.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>填写注册时的手机号及验证码并点击下一步</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">3.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>输入新的密码并点击确认按钮</span></p><p style="font-family: 8px -22px 0 24px;line-height: 24px"><span style="font-size:16px;font-family:微软雅黑">4.<span style="font-stretch: normal;font-size: 9px;line-height: normal">&nbsp;&nbsp;&nbsp;</span>输入手机号并输入新的密码即可重新登录</span></p><p style="line-height: 24px;"><span style="font-family: 微软雅黑;"></span><br  /></p><p><span style="font-family: 微软雅黑;"><img data-type="gif" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%8D%81%E2%80%94%E2%80%94%E4%BF%AE%E6%94%B9%E5%AF%86%E7%A0%81.gif"  /><br  /></span></p><p><br  /></p>
                        </div>
                        <script nonce="208764107" type="text/javascript">
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
        
        
        
        <script nonce="208764107">
         var __DEBUGINFO = {
             debug_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/debug/console2ca724.js",
             safe_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/moonsafe2f3e84.js",
             res_list: []
         };
        </script>
        
        <script nonce="208764107">
         (function() {
             function _addVConsole(uri) {
                 var url = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/vconsole/' + uri;
                 document.write('<script nonce="208764107" type="text/javascript" src="' + url + '"><\/script>');
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
        
        <script nonce="208764107" type="text/javascript">

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
        <script nonce="208764107" type="text/javascript">
         
         var not_in_mm_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm322696.css";
         var windowwx_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_winwx31619e.css";
         var article_improve_combo_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo348fa1.css";
         var tid = "";
         var aid = "";
         var clientversion = "";
         var appuin = "MzAxMTUxMDcwMw=="||"";
         
         var source = "";
         var abtest_cookie = "";
         
         var scene = 75;
         
         var itemidx = "";
         
         var _copyright_stat = "0";
         var _ori_article_type = "";
         
         var nickname = "理优教育在线学习";
         var appmsg_type = "6";
         var ct = "1467458454";
         var publish_time = "2016-09-03" || "";
         var user_name = "gh_0ad5c3265472";
         var user_name_new = "";
         var fakeid   = "";
         var version   = "";
         var is_limit_user   = "0";
         var round_head_img = "http://mmbiz.qpic.cn/mmbiz/DdBO9OC10icicPhibWftib0aXjLicNZ9wLCiaV3ZO4d2IXjc4BIjAf5v8lBff2I91ZTKGibWLnW5vEOkwQKUdmICHOG5w/0?wx_fmt=png";
         var ori_head_img_url = "http://wx.qlogo.cn/mmhead/Q3auHgzwzM4a85d2icJD4qpjaFibjyRE1wLXqniabDEsXmicAW1G8QIPzQ/132";
         var msg_title = "理优1对1学生软件用户使用手册";
         var msg_desc = "理优在线学习App包含强大的功能，具体如何使用？赶紧来学习吧。";
         var msg_cdn_url = "http://mmbiz.qpic.cn/mmbiz/DdBO9OC10ic8HmesZburwnhrl9QfQd8dkP63C2gQvGfFMs3Qz5icm6N2AmqGy4fIT4vhOnYia0SQBiaEmIP7wficE5w/0?wx_fmt=png";
         var msg_link = "http://mp.weixin.qq.com/s?__biz=MzAxMTUxMDcwMw==\x26amp;tempkey=oM98dG3QyhtiM47FE1cQY0wHZ59qVYhENn4DTO3FS1VKH1KajG8HZ44hbL9Ke7DShtEekGFPeXL%2BZvSKA3pAGgrSoA0jZuP%2FV7av5vZruxqVXwjwswGxiEFb4vz0Q7qEhXazGORraLbdPqmgz4jEUg%3D%3D\x26amp;#rd";
         var user_uin = "0"*1;
         var msg_source_url = '';
         var img_format = 'png';
         var srcid = '';
         var req_id = '2115FLo2cxSNzvc5jgNG4spr';
         var networkType;
         var appmsgid = '' || '506210022'|| "506210022";
         var comment_id = "0" * 1;
         var comment_enabled = "" * 1;
         var is_need_reward = "0" * 1;
         var is_https_res = ("" * 1) && (location.protocol == "https:");
         var msg_daily_idx = "0" || "";
         
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
             hd_head_img : "http://wx.qlogo.cn/mmhead/Q3auHgzwzM4a85d2icJD4qpjaFibjyRE1wLXqniabDEsXmicAW1G8QIPzQ/0"||(window.location.protocol+"//"+window.location.host + "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/pic/appmsg/pic_rumor_link.2x264e76.jpg")
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
         var read_num = "16" * 1;
         var like_num = "0" * 1;
         var liked = "false" == 'true' ? true : false;
         var is_temp_url = "oM98dG3QyhtiM47FE1cQY0wHZ59qVYhENn4DTO3FS1VKH1KajG8HZ44hbL9Ke7DShtEekGFPeXL\x26nbsp;ZvSKA3pAGgrSoA0jZuP/V7av5vZruxqVXwjwswGxiEFb4vz0Q7qEhXazGORraLbdPqmgz4jEUg==" ? 1 : 0;
         var send_time = "1490080100";
         var icon_emotion_switch = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/emotion/icon_emotion_switch.2x2f1273.png";
         var icon_emotion_switch_active = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/emotion/icon_emotion_switch_active.2x2f1273.png";
         var icon_loading_white = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/icon_loading_white2805ea.gif";
         var icon_audio_unread = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/audio/icon_audio_unread26f1f1.png";
         var icon_qqmusic_default = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/qqmusic/icon_qqmusic_default.2x26f1f1.png";
         var icon_qqmusic_source = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/qqmusic/icon_qqmusic_source263724.png";
         
         var topic_default_img = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/topic/pic_book_thumb.2x2e4987.png';
         




         

         var ban_scene = "0" * 1;
         
         var svr_time = "1490080100" * 1;

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
        
        <script nonce="208764107">window.__moon_host = 'res.wx.qq.com';window.__moon_mainjs = 'appmsg/index.js';window.moon_map = {"appmsg/emotion/caret.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/caret278965.js","biz_wap/jsapi/cardticket.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/cardticket275627.js","appmsg/emotion/map.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/map278965.js","appmsg/emotion/textarea.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/textarea27cdc5.js","appmsg/emotion/nav.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/nav278965.js","appmsg/emotion/common.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/common278965.js","appmsg/emotion/slide.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/slide2a9cd9.js","pages/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/report340996.js","pages/music_player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_player333ed7.js","pages/loadscript.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/loadscript30203e.js","appmsg/emotion/dom.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/dom31ff31.js","biz_wap/utils/fakehash.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/fakehash33de59.js","biz_common/utils/wxgspeedsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/wxgspeedsdk30bcdd.js","a/sponsor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor3189b5.js","a/app_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/app_card333f3d.js","a/ios.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/ios333f3d.js","a/android.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/android2c5484.js","a/profile.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/profile31ff31.js","a/sponsor_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor_a_tpl.html32c414.js","a/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_tpl.html32c414.js","a/mpshop.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/mpshop311179.js","a/card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/card311179.js","biz_wap/utils/position.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/position2f1750.js","a/a_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_report32e586.js","biz_common/utils/respTypes.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/respTypes2c57d0.js","appmsg/my_comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/my_comment_tpl.html325336.js","appmsg/cmt_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cmt_tpl.html348fa1.js","sougou/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/a_tpl.html2c6e7c.js","appmsg/emotion/emotion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/emotion2f3ac3.js","biz_wap/utils/wapsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/wapsdk315b3f.js","biz_common/utils/monitor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/monitor304edd.js","biz_common/utils/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/report275627.js","appmsg/open_url_with_webview.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/open_url_with_webview3145f0.js","biz_common/utils/http.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/http30b871.js","biz_common/utils/cookie.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/cookie275627.js","appmsg/topic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/topic_tpl.html31ff31.js","pages/voice_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_tpl.html2f2e72.js","pages/voice_component.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_component3338bb.js","pages/qqmusic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_tpl.html32c414.js","new_video/ctl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/ctl2d441f.js","a/testdata.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/testdata31a4be.js","appmsg/reward_entry.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/reward_entry3004a4.js","appmsg/comment.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment348fa1.js","appmsg/like.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/like2eb52b.js","pages/version4video.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/version4video33de59.js","a/a.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a347b7e.js","rt/appmsg/getappmsgext.rt.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/rt/appmsg/getappmsgext.rt2c21f6.js","biz_wap/utils/storage.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/storage2a74ac.js","biz_common/tmpl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/tmpl33dd00.js","appmsg/img_copyright_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/img_copyright_tpl.html2a2c13.js","biz_common/ui/imgonepx.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/ui/imgonepx275627.js","biz_wap/utils/ajax.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/ajax33d6e9.js","biz_wap/utils/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/log2fcb7c.js","sougou/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/index3420dc.js","biz_wap/safe/mutation_observer_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/mutation_observer_report2fafd1.js","appmsg/fereport.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/fereport33a3b2.js","appmsg/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report3404b3.js","appmsg/report_and_source.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report_and_source33ddd7.js","appmsg/page_pos.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/page_pos3404b3.js","appmsg/cdn_speed_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_speed_report3097b2.js","appmsg/wxtopic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/wxtopic31a3be.js","appmsg/voice.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/voice310e30.js","appmsg/qqmusic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/qqmusic31623d.js","appmsg/iframe.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/iframe3408af.js","appmsg/review_image.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/review_image347b7e.js","appmsg/outer_link.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/outer_link275627.js","biz_wap/jsapi/core.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/core2ffa93.js","appmsg/copyright_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/copyright_report2ec4b2.js","appmsg/async.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/async341b97.js","biz_wap/ui/lazyload_img.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/ui/lazyload_img34921c.js","biz_common/log/jserr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/log/jserr2805ea.js","appmsg/share.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share322696.js","appmsg/cdn_img_lib.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_img_lib347b7e.js","biz_common/utils/url/parse.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/url/parse2fb01a.js","page/appmsg/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm.css32c99a.js","page/appmsg/page_mp_article_improve_combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo.css348fa1.js","biz_common/dom/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/event32e586.js","appmsg/test.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/test314065.js","biz_wap/utils/mmversion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/mmversion2f1d97.js","appmsg/max_age.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/max_age2fdd28.js","biz_common/dom/attr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/attr275627.js","appmsg/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/log300330.js","biz_common/dom/class.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/class275627.js","biz_wap/utils/device.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/device2b3aae.js","biz_wap/jsapi/a8key.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/a8key2a30ee.js","biz_common/utils/string/html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/string/html348fa1.js","appmsg/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/index3491ca.js"};</script><script nonce="208764107" type="text/javascript">(function(){function d(a){window.__wxgspeeds.moonls_loadjs_begin=+new Date;var c=document.createElement("script");document.getElementsByTagName("body")[0].appendChild(c);c.type="text/javascript";c.async="async";;c.setAttribute('onerror', 'wx_loaderror');c.onload=function(){a&&f()};c.src=b;window.__wxgspeeds.moonls_loadjs_end=+new Date}function f(){window.__wxgspeeds.moonls_save_begin=+new Date;localStorage.setItem("__WXLS__moon",String(__moonf__));localStorage.setItem("__WXLS__moonarg",JSON.stringify({version:b,method:""}));window.__wxgspeeds.moonls_save_end=+new Date}var a=!!top&&!!top.window&&top.window.user_uin||0,e=0!==a&&1>Math.floor(a/100)%100;if(2876363900==a||1506075==a||942807682==a)e=!0;var b="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/moon32ebc4.js";window.__loadAllResFromMp&&(b=b.replace("res.wx.qq.com","mp.weixin.qq.com"),(new Image).src=location.protocol+"//mp.weixin.qq.com/mp/jsmonitor?idkey=27613_12_1");window.__wxgspeeds||(window.__wxgspeeds={});if("function"==typeof __moonf__)__moonf__(),e&&localStorage&&f();else if(window.__wxgspeeds.moonloadtime=+new Date,e&&localStorage)try{var g=JSON.parse(localStorage.getItem("__WXLS__moonarg"))||{};if(g&&g.version==b){var h=localStorage.getItem("__WXLS__moon");localStorage.setItem("__WXLS__moonarg",JSON.stringify({version:b,method:"fromls"}));window.__moonls_fromls=!0;window.__wxgspeeds.moonls_loadls_end=+new Date;eval(h);__moonf__()}else d(!0)}catch(k){window.__moonls_fail=!0,d(!0)}else d(!1)})();</script>
        <script nonce="208764107" type="text/javascript">
         var real_show_page_time = +new Date();
         if (!!window.addEventListener){
             window.addEventListener("load", function(){
                 window.onload_endtime = +new Date();
             });
         }
         
        </script>
        
    </body>
    <script nonce="208764107" type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
</html>
<!--tailTrap<body></body><head></head><html></html>-->


