<!-- <p style="margin: 0px; padding: 0px;width:556px;height:417px; box-sizing: border-box; text-indent: 0em;">

     <iframe frameborder="0" src="https://v.qq.com/iframe/player.html?vid=o0386jrztgv&tiny=0&auto=0" allowfullscreen></iframe>
     <br  />

     </p>
   -->

<!--

     <!--
     <!DOCTYPE html>
     <!--headTrap<body></body><head></head><html></html>--><html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">

        
        <script nonce="318884283" type="text/javascript">
         window.logs = {
             pagetime: {}
         };
         window.logs.pagetime['html_begin'] = (+new Date());
        </script>
        
        <script nonce="318884283" type="text/javascript">
         var biz = "MzI5MDQzMTAxNQ=="||"";
         var sn = "" || ""|| "dae91670817c4ef091893a7f0b63155b";
         var mid = "100000408" || ""|| "100000408";
         var idx = "1" || "" || "1";
         window.__allowLoadResFromMp = true;

        </script>
        <script nonce="318884283" type="text/javascript">
         var page_begintime=+new Date,is_rumor="",norumor="";
         1*is_rumor&&!(1*norumor)&&biz&&mid&&(document.referrer&&-1!=document.referrer.indexOf("mp.weixin.qq.com/mp/rumor")||(location.href="http://mp.weixin.qq.com/mp/rumor?action=info&__biz="+biz+"&mid="+mid+"&idx="+idx+"&sn="+sn+"#wechat_redirect")),
             document.domain="qq.com";
        </script>
        <script nonce="318884283" type="text/javascript">
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
        <script nonce="318884283" type="text/javascript">
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
             window.appmsg_token = "";
         })();

         function wx_loaderror() {
             if (location.pathname === '/bizmall/reward') {
                 new Image().src = '/mp/jsreport?key=96&content=reward_res_load_err&r=' + Math.random();
             }
         }

        </script>
        
        <title>如何录制试讲视频</title>
        
        <style>html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;line-height:1.6}body{-webkit-touch-callout:none;font-family:-apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;background-color:#f3f3f3;line-height:inherit}body.rich_media_empty_extra{background-color:#fff}body.rich_media_empty_extra .rich_media_area_primary:before{display:none}h1,h2,h3,h4,h5,h6{font-weight:400;font-size:16px}*{margin:0;padding:0}a{color:#607fa6;text-decoration:none}.rich_media_inner{font-size:16px;word-wrap:break-word;-webkit-hyphens:auto;-ms-hyphens:auto;hyphens:auto}.rich_media_area_primary{position:relative;padding:20px 15px 15px;background-color:#fff}.rich_media_area_primary:before{content:" ";position:absolute;left:0;top:0;width:100%;height:1px;border-top:1px solid #e5e5e5;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5);top:auto;bottom:-2px}.rich_media_area_primary .original_img_wrp{display:inline-block;font-size:0}.rich_media_area_primary .original_img_wrp .tips_global{display:block;margin-top:.5em;font-size:14px;text-align:right;width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.rich_media_area_extra{padding:0 15px 0}.rich_media_title{margin-bottom:10px;line-height:1.4;font-weight:400;font-size:24px}.icon_original_tag_primary{display:inline-block;padding:1px .65em;margin-top:-0.2em;vertical-align:middle;line-height:1.4;font-size:12px;border-top-left-radius:.85em 50%;-moz-border-radius-topleft:.85em 50%;-webkit-border-top-left-radius:.85em 50%;border-top-right-radius:.85em 50%;-moz-border-radius-topright:.85em 50%;-webkit-border-top-right-radius:.85em 50%;border-bottom-left-radius:.85em 50%;-moz-border-radius-bottomleft:.85em 50%;-webkit-border-bottom-left-radius:.85em 50%;border-bottom-right-radius:.85em 50%;-moz-border-radius-bottomright:.85em 50%;-webkit-border-bottom-right-radius:.85em 50%;border:1px solid #9e9e9e;color:#8c8c8c}.icon_original_tag_primary.title_tag{background-color:#e94442;border-color:#d04b4e;color:#fff;margin-bottom:.5em;padding:2px .65em;border-top-left-radius:.95em 50%;-moz-border-radius-topleft:.95em 50%;-webkit-border-top-left-radius:.95em 50%;border-top-right-radius:.95em 50%;-moz-border-radius-topright:.95em 50%;-webkit-border-top-right-radius:.95em 50%;border-bottom-left-radius:.95em 50%;-moz-border-radius-bottomleft:.95em 50%;-webkit-border-bottom-left-radius:.95em 50%;border-bottom-right-radius:.95em 50%;-moz-border-radius-bottomright:.95em 50%;-webkit-border-bottom-right-radius:.95em 50%}.rich_media_meta_list{margin-bottom:18px;line-height:20px;font-size:0}.rich_media_meta_list em{font-style:normal}.rich_media_meta{display:inline-block;vertical-align:middle;margin-right:8px;margin-bottom:10px;font-size:16px}.meta_original_tag{display:inline-block;vertical-align:middle;padding:1px .5em;border:1px solid #9e9e9e;color:#8c8c8c;border-top-left-radius:20% 50%;-moz-border-radius-topleft:20% 50%;-webkit-border-top-left-radius:20% 50%;border-top-right-radius:20% 50%;-moz-border-radius-topright:20% 50%;-webkit-border-top-right-radius:20% 50%;border-bottom-left-radius:20% 50%;-moz-border-radius-bottomleft:20% 50%;-webkit-border-bottom-left-radius:20% 50%;border-bottom-right-radius:20% 50%;-moz-border-radius-bottomright:20% 50%;-webkit-border-bottom-right-radius:20% 50%;font-size:15px;line-height:1.1}.meta_enterprise_tag img{width:30px;height:30px!important;display:block;position:relative;margin-top:-3px;border:0}.rich_media_meta_text{color:#8c8c8c}span.rich_media_meta_nickname{display:none}.rich_media_thumb_wrp{margin-bottom:6px}.rich_media_thumb_wrp .original_img_wrp{display:block}.rich_media_thumb{display:block;width:100%}.rich_media_content{overflow:hidden;color:#3e3e3e}.rich_media_content *{max-width:100%!important;box-sizing:border-box!important;-webkit-box-sizing:border-box!important;word-wrap:break-word!important}.rich_media_content p{clear:both;min-height:1em}.rich_media_content em{font-style:italic}.rich_media_content fieldset{min-width:0}.rich_media_content .list-paddingleft-2{padding-left:30px}.rich_media_content blockquote{margin:0;padding-left:10px;border-left:3px solid #dbdbdb}img{height:auto!important}@media screen and (device-aspect-ratio:2/3),screen and (device-aspect-ratio:40/71){.meta_original_tag{padding-top:0}}@media(min-device-width:375px) and (max-device-width:667px) and (-webkit-min-device-pixel-ratio:2){.mm_appmsg .rich_media_inner,.mm_appmsg .rich_media_meta,.mm_appmsg .discuss_list,.mm_appmsg .rich_media_extra,.mm_appmsg .title_tips .tips{font-size:17px}.mm_appmsg .meta_original_tag{font-size:15px}}@media(min-device-width:414px) and (max-device-width:736px) and (-webkit-min-device-pixel-ratio:3){.mm_appmsg .rich_media_title{font-size:25px}}@media screen and (min-width:1024px){.rich_media{width:740px;margin-left:auto;margin-right:auto}.rich_media_inner{padding:20px}body{background-color:#fff}}@media screen and (min-width:1025px){body{font-family:"Helvetica Neue",Helvetica,"Hiragino Sans GB","Microsoft YaHei",Arial,sans-serif}.rich_media{position:relative}.rich_media_inner{background-color:#fff;padding-bottom:100px}}.radius_avatar{display:inline-block;background-color:#fff;padding:3px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;overflow:hidden;vertical-align:middle}.radius_avatar img{display:block;width:100%;height:100%;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;background-color:#eee}.cell{padding:.8em 0;display:block;position:relative}.cell_hd,.cell_bd,.cell_ft{display:table-cell;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space:nowrap}.cell_primary{width:2000px;white-space:normal}.flex_cell{padding:10px 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.flex_cell_primary{width:100%;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;box-flex:1;flex:1}.original_tool_area{display:block;padding:.75em 1em 0;-webkit-tap-highlight-color:rgba(0,0,0,0);color:#3e3e3e;border:1px solid #eaeaea;margin:20px 0}.original_tool_area .tips_global{position:relative;padding-bottom:.5em;font-size:15px}.original_tool_area .tips_global:after{content:" ";position:absolute;left:0;bottom:0;right:0;height:1px;border-bottom:1px solid #dbdbdb;-webkit-transform-origin:0 100%;transform-origin:0 100%;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.original_tool_area .radius_avatar{width:27px;height:27px;padding:0;margin-right:.5em}.original_tool_area .radius_avatar img{height:100%!important}.original_tool_area .flex_cell_bd{width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.original_tool_area .flex_cell_ft{font-size:14px;color:#8c8c8c;padding-left:1em;white-space:nowrap}.original_tool_area .icon_access:after{content:" ";display:inline-block;height:8px;width:8px;border-width:1px 1px 0 0;border-color:#cbcad0;border-style:solid;transform:matrix(0.71,0.71,-0.71,0.71,0,0);-ms-transform:matrix(0.71,0.71,-0.71,0.71,0,0);-webkit-transform:matrix(0.71,0.71,-0.71,0.71,0,0);position:relative;top:-2px;top:-1px}.weui_loading{width:20px;height:20px;display:inline-block;vertical-align:middle;-webkit-animation:weuiLoading 1s steps(12,end) infinite;animation:weuiLoading 1s steps(12,end) infinite;background:transparent url(data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iciIgd2lkdGg9JzEyMHB4JyBoZWlnaHQ9JzEyMHB4JyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj4KICAgIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJub25lIiBjbGFzcz0iYmsiPjwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjRTlFOUU5JwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoMCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICA8L3JlY3Q+CiAgICA8cmVjdCB4PSc0Ni41JyB5PSc0MCcgd2lkdGg9JzcnIGhlaWdodD0nMjAnIHJ4PSc1JyByeT0nNScgZmlsbD0nIzk4OTY5NycKICAgICAgICAgIHRyYW5zZm9ybT0ncm90YXRlKDMwIDUwIDUwKSB0cmFuc2xhdGUoMCAtMzApJz4KICAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0naW5kZWZpbml0ZScvPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyM5Qjk5OUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSg2MCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9J2luZGVmaW5pdGUnLz4KICAgIDwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjQTNBMUEyJwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoOTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNBQkE5QUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxMjAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCMkIyQjInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxNTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCQUI4QjknCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxODAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDMkMwQzEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyMTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDQkNCQ0InCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEMkQyRDInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEQURBREEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNFMkUyRTInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0Pgo8L3N2Zz4=) no-repeat;-webkit-background-size:100%;background-size:100%}@-webkit-keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}@keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}.gif_img_wrp{display:inline-block;font-size:0;position:relative;font-weight:400;font-style:normal;text-indent:0;text-shadow:none 1px 1px rgba(0,0,0,0.5)}.gif_img_wrp img{vertical-align:top}.gif_img_tips{background:rgba(0,0,0,0.6)!important;filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#99000000',endcolorstr = '#99000000');border-top-left-radius:1.2em 50%;-moz-border-radius-topleft:1.2em 50%;-webkit-border-top-left-radius:1.2em 50%;border-top-right-radius:1.2em 50%;-moz-border-radius-topright:1.2em 50%;-webkit-border-top-right-radius:1.2em 50%;border-bottom-left-radius:1.2em 50%;-moz-border-radius-bottomleft:1.2em 50%;-webkit-border-bottom-left-radius:1.2em 50%;border-bottom-right-radius:1.2em 50%;-moz-border-radius-bottomright:1.2em 50%;-webkit-border-bottom-right-radius:1.2em 50%;line-height:2.3;font-size:11px;color:#fff;text-align:center;position:absolute;bottom:10px;left:10px;min-width:65px}.gif_img_tips.loading{min-width:75px}.gif_img_tips i{vertical-align:middle;margin:-0.2em .73em 0 -2px}.gif_img_play_arrow{display:inline-block;width:0;height:0;border-width:8px;border-style:dashed;border-color:transparent;border-right-width:0;border-left-color:#fff;border-left-style:solid;border-width:5px 0 5px 8px}.gif_img_loading{width:14px;height:14px}i.gif_img_loading{margin-left:-4px}.gif_bg_tips_wrp{position:relative;height:0;line-height:0;margin:0;padding:0}.gif_bg_tips_wrp .gif_img_tips_group{position:absolute;top:0;left:0;z-index:9999}.gif_bg_tips_wrp .gif_img_tips_group .gif_img_tips{top:0;left:0;bottom:auto}.rich_media_global_msg{position:fixed;top:0;left:0;right:0;padding:1em 35px 1em 15px;z-index:2;background-color:#c6e0f8;color:#8c8c8c;font-size:13px}.rich_media_global_msg .icon_closed{position:absolute;right:15px;top:50%;margin-top:-5px;line-height:300px;overflow:hidden;-webkit-tap-highlight-color:rgba(0,0,0,0);background:transparent url(//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_appmsg_msg_closed_sprite.2x2eb52b.png) no-repeat 0 0;width:11px;height:11px;vertical-align:middle;display:inline-block;-webkit-background-size:100% auto;background-size:100% auto}.rich_media_global_msg .icon_closed:active{background-position:0 -17px}.preview_appmsg .rich_media_title{margin-top:1.9em}@media screen and (min-width:1024px){.rich_media_global_msg{position:relative;margin:0 20px}.preview_appmsg .rich_media_title{margin-top:0}}.pages_reset{color:#3e3e3e;line-height:1.6;font-size:16px;font-weight:400;font-style:normal;text-indent:0;letter-spacing:normal;text-align:left;text-decoration:none}.weapp_element,.weapp_display_element,.mp-miniprogram{display:block;margin:1em 0}.share_audio_context{margin:16px 0}.weapp_text_link{font-size:17px}.weapp_text_link:before{content:'';display:inline-block;line-height:1;background-size:12px 12px;background-repeat:no-repeat;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAMAAABF0y+mAAAAb1BMVEUAAAB4it11h9x2h9x2h9x2htx8j+R8i+B1h9x2h9x3h92Snv91htt2h9x1h9x4h9x1h9x1h9x2idx1h9t2h9t1htt1h9x1h9x1htx2h9x1h912h9x4h913iN17juOOjuN1iNx2h9t4h958i+B1htvejBiPAAAAJHRSTlMALPLcxKcVEOXXUgXtspU498sx69DPu5+Yc2JeRDwbCYuIRiGBtoolAAAA3ElEQVQoz62S1xKDIBBFWYiFYImm2DWF///G7DJEROOb58U79zi4O8iOo8zuCRfV8EdFgbYE49qFQs8ksJInajOA1wWfYvLcGSueU/oUGBtPpti09uNS68KTMcrQ5jce4kmN/HKn9XVPAo702JEdx9hTUrWUqVrI3KwUmM1NhIWMKdwiGvpGMWZOAj1PZuzAxHwhVSplrajoseBnbyDHAwvrtvKKhdqTtFBkL8wO5ijcsS3G1JMNvQ5mdW7fc0x0+ZcnlJlZiflAomdEyFaM7qeK2JahEjy5ZyU7jC/q/Rz/DgqEuAAAAABJRU5ErkJggg==');vertical-align:middle;font-size:11px;color:#888;border-radius:10px;background-color:#f4f4f4;margin-right:6px;margin-top:-4px;background-position:center;height:20px;width:20px}.weui-mask{position:fixed;z-index:1000;top:0;right:0;left:0;bottom:0;background:rgba(0,0,0,0.6)}.weui-dialog{position:fixed;z-index:5000;width:80%;max-width:300px;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);background-color:#fff;text-align:center;border-radius:3px;overflow:hidden}.weui-dialog__hd{padding:1.3em 1.6em .5em}.weui-dialog__title{font-weight:400;font-size:18px}.weui-dialog__bd{padding:0 1.6em .8em;min-height:40px;font-size:15px;line-height:1.3;word-wrap:break-word;word-break:break-all;color:#999}.weui-dialog__bd:first-child{padding:2.7em 20px 1.7em;color:#353535}.weui-dialog__ft{position:relative;line-height:48px;font-size:18px;display:-webkit-box;display:-webkit-flex;display:flex}.weui-dialog__ft:after{content:" ";position:absolute;left:0;top:0;right:0;height:1px;border-top:1px solid #d5d5d6;color:#d5d5d6;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.weui-dialog__btn{display:block;-webkit-box-flex:1;-webkit-flex:1;flex:1;color:#3cc51f;text-decoration:none;-webkit-tap-highlight-color:rgba(0,0,0,0);position:relative}.weui-dialog__btn:active{background-color:#eee}.weui-dialog__btn:after{content:" ";position:absolute;left:0;top:0;width:1px;bottom:0;border-left:1px solid #d5d5d6;color:#d5d5d6;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleX(0.5);transform:scaleX(0.5)}.weui-dialog__btn:first-child:after{display:none}.weui-dialog__btn_default{color:#353535}.weui-dialog__btn_primary{color:#0bb20c}</style>
         <style>
        </style>
        <!--[if lt IE 9]>
            <link onerror="wx_loaderror(this)" rel="stylesheet" type="text/css" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2c9cd6.css">
        <![endif]-->
        
    </head>
    <body id="activity-detail" class="zh_CN mm_appmsg">
        
        <script nonce="318884283" type="text/javascript">
         var write_sceen_time = (+new Date());
        </script>
        
        <div id="js_article" class="rich_media preview_appmsg">
            
            <div id="js_top_ad_area" class="top_banner"></div>
            
            <div class="rich_media_inner">
                <div id="page-content" class="rich_media_area_primary">
                    
                    <div id="img-content">
                        
                        <h2 class="rich_media_title" id="activity-name">
                            如何录制试讲视频                                    </h2>
                        <div id="meta_content" class="rich_media_meta_list">
                            <em id="post-date" class="rich_media_meta rich_media_meta_text">2017-08-15</em>
                            
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
                            
                            
                            
                            
                            
                            
                            <section data-role="outer" label="Powered by 135editor.com" style="font-size: 16px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; font-family: 微软雅黑;"><section style="background-color: rgb(255, 255, 255); box-sizing: border-box;"><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 10px; margin-bottom: 10px; text-align: center; box-sizing: border-box; transform: translate3d(0px, 0px, 0px);"><section class="" style="display: inline-block; width: 528.188px; vertical-align: top; box-shadow: rgb(178, 185, 192) 1px 0px 10px; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 10px; margin-bottom: 10px; box-sizing: border-box;"><section class="" style="padding: 3px; display: inline-block; background-color: rgb(11, 206, 255); box-sizing: border-box;"><section class="" style="padding: 2px 8px; border: 1px dotted rgb(255, 255, 255); color: rgb(255, 255, 255); line-height: 1.4em; font-size: 13px; box-sizing: border-box;"><p style="box-sizing: border-box;"><a href="http://www.leo1v1.com/tea.html?reference=">兼职老师报名链接</a></p></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 10px; margin-bottom: 10px; box-sizing: border-box;"><section class="" style="color: rgb(62, 62, 62); letter-spacing: 0px; line-height: 1.5; box-sizing: border-box;"><p style="box-sizing: border-box;">请先填写报名表单，收到邮件后</p><p style="box-sizing: border-box;">查看以下教程</p></section></section></section></section></section></section><br  /><p style="line-height: 25.6px; text-align: center; box-sizing: border-box;"><strong>视频教程</strong></p>


                                <p  style="line-height: 25.6px; white-space: normal;text-align: center; margin: 0px; padding: 0px; box-sizing: border-box;">

                                    <iframe frameborder="0" src="http://v.qq.com/iframe/player.html?vid=w0538xx08c7&tiny=0&auto=0" allowfullscreen></iframe>
                                    <br>
                                </p>




                                <section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin: 0.5em; box-sizing: border-box;"><section class="" style="height: 2em; display: inline-block; box-sizing: border-box;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center; box-sizing: border-box;"><strong style="box-sizing: border-box;">准备工作</strong></p></section>

                                    <section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); box-sizing: border-box; transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><p>



                                        <img data-s="300,640" data-type="png" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/1402947021.jpg
                                                     " class="" data-ratio="0.5591603053435115" data-w="1048"  />


                                    </p></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p style="box-sizing: border-box;"><span style="letter-spacing: 0px; color: rgb(51, 51, 51); box-sizing: border-box;">下载</span><span style="color: rgb(11, 206, 255); letter-spacing: 0px; box-sizing: border-box;">“</span><strong style="color: rgb(11, 206, 255); letter-spacing: 0px; box-sizing: border-box;">理优1对1-老师端</strong><span style="color: rgb(11, 206, 255); letter-spacing: 0px; box-sizing: border-box;">”、“</span><strong style="color: rgb(11, 206, 255); letter-spacing: 0px; box-sizing: border-box;">试讲题目</strong><span style="color: rgb(11, 206, 255); letter-spacing: 0px; box-sizing: border-box;">”和</span><span style="letter-spacing: 0px; color: rgb(11, 206, 255); box-sizing: border-box;">“<strong style="box-sizing: border-box;">个人简历模板</strong>”</span><span style="color: rgb(255, 52, 81); box-sizing: border-box;"><span style="letter-spacing: 0px; box-sizing: border-box;">（</span><span style="letter-spacing: 0px; box-sizing: border-box;">下载地址请查看邮件内容）</span></span></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section data-role="outer" label="Powered by 135editor.com"><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class=""><p><br  /></p></section></section></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class=""><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class=""><section class="_135editor" data-tools="135编辑器" data-id="90388" style="border: 0px none; box-sizing: border-box;"><section data-width="100%" style="width: 556px; display: -webkit-flex; justify-content: center;"><section style="padding-top: 11px; box-sizing: border-box;"><section class="135brush" data-brushtype="text" style="padding-right: 10px; padding-left: 20px; background-color: rgb(0, 166, 255); height: 40px; line-height: 40px; color: rgb(51, 51, 51); box-sizing: border-box;"><span style="color: rgb(255, 255, 255);">&nbsp;录制试讲方式 &nbsp;</span></section></section><section style="background-color: rgb(0, 166, 255); overflow: hidden; width: 40px;">
                                        <img src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/0.png
                                                  " data-width="100%" class="" data-ratio="1.3125" data-w="64" data-type="png" style="display: block; width: 40px; float: left;"  />


                                    </section></section></section></section></section></section></section></section></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class=""><p><br  /></p></section></section></section></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin: 0.5em; box-sizing: border-box;"><section class="" style="height: 2em; display: inline-block; box-sizing: border-box;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center; box-sizing: border-box;"><strong style="box-sizing: border-box;">第一</strong><strong style="letter-spacing: 0px; text-align: left; box-sizing: border-box;">步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); box-sizing: border-box; transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 10px; margin-bottom: 10px; text-align: center; box-sizing: border-box;"></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p>

                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E5%BD%95%E5%88%B601.jpg
                                                     " class="" data-ratio="0.72890625" data-w="1280"  />




                                    </p><p style="box-sizing: border-box;"><span style="color: rgb(51, 51, 51); box-sizing: border-box;">打开</span><strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">理优老师客户端，<span style="color: rgb(51, 51, 51); box-sizing: border-box;"></span></span></strong><span style="color: rgb(11, 206, 255); box-sizing: border-box;"><span style="color: rgb(51, 51, 51); box-sizing: border-box;">输入</span></span><strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;"><span style="color: rgb(51, 51, 51); box-sizing: border-box;"></span>账号、密码</span></strong></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 10px; margin-bottom: 10px; box-sizing: border-box;"><section class="" style="padding: 3px; border: 3px solid rgb(204, 204, 204); box-sizing: border-box;"><section class="" style="padding: 10px; border: 1px solid rgb(204, 204, 204); box-sizing: border-box;"><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="text-align: center; font-size: 18px; box-sizing: border-box;"><p style="box-sizing: border-box;"><strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255);">账号：</span><span style="color: rgb(255, 52, 81);">您的报名手机号</span></strong></p><p style="color: rgb(11, 206, 255); box-sizing: border-box;"><strong style="box-sizing: border-box;">密码：<span style="color: rgb(255, 52, 81); box-sizing: border-box;">leo+手机号后四位</span></strong></p></section></section></section></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p style="box-sizing: border-box;"><br  /></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin: 0.5em; box-sizing: border-box;"><section class="" style="height: 2em; display: inline-block; box-sizing: border-box;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center; box-sizing: border-box;"><strong style="box-sizing: border-box;">第二步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); box-sizing: border-box; transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><p>

                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E5%BD%95%E5%88%B602.jpg
                                                     " class="" data-ratio="0.72890625" data-w="1280"  />



                                    </p></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;">将已<strong><span style="color: rgb(11, 206, 255);">填写完整的简历</span></strong>上传，<span style="color: rgb(255, 52, 81);">请使用理优简历模版</span>&nbsp;</section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p style="box-sizing: border-box;"><br  /></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin: 0.5em; box-sizing: border-box;"><section class="" style="height: 2em; display: inline-block; box-sizing: border-box;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center; box-sizing: border-box;"><strong style="box-sizing: border-box;">第三步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); box-sizing: border-box; transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><p>
                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E5%BD%95%E5%88%B603.jpg
                                                     " class="" data-ratio="0.72890625" data-w="1280"  />




                                    </p></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p style="box-sizing: border-box;">这里有一节已经排好的课程，点击<strong><span style="color: rgb(11, 206, 255);">“录制视频”</span></strong>，进入录制&nbsp;<span style="color: rgb(255, 52, 81); box-sizing: border-box;"></span></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p style="box-sizing: border-box;"><br  /></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin: 0.5em; box-sizing: border-box;"><section class="" style="height: 2em; display: inline-block; box-sizing: border-box;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center; box-sizing: border-box;"><strong style="box-sizing: border-box;">第四步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); box-sizing: border-box; transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><p>
                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E5%BD%95%E5%88%B604.jpg
                                                     " class="" data-ratio="0.72890625" data-w="1280"  />

                                    </p><p style="margin-top: 10px; margin-bottom: 10px; box-sizing: border-box;">点击这个<strong><span style="color: rgb(11, 206, 255);">上传本地PDF文件</span></strong>的按钮上传讲义。在第一页传第一张，记得一定要点这个“<strong><span style="color: rgb(51, 51, 51); font-family: arial; font-size: 13px;">√&nbsp;</span></strong>”<br  /></p><p style="margin-top: 10px; margin-bottom: 10px; text-align: center; box-sizing: border-box;">

                                        <img src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E5%BD%95%E5%88%B604%E5%90%8E%E5%B0%8F%E5%9B%BE.png
                                                  " class="" data-type="png" data-ratio="0.37037037037037035" data-w="243"  />





                                    </p></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class="" style="color: rgb(51, 51, 51);"><p>传好后翻页，上传第二张。需要将每张讲义都上传上去，全部上传完成后开始试讲。</p></section></section></section><p><br  /></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin: 0.5em; box-sizing: border-box;"><section class="" style="height: 2em; display: inline-block; box-sizing: border-box;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center; box-sizing: border-box;"><strong style="box-sizing: border-box;">第五步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); box-sizing: border-box; transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><p>

                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E5%BD%95%E5%88%B605.jpg
                                                     " class="" data-ratio="0.72890625" data-w="1280"  />

                                    </p></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p style="box-sizing: border-box;">上传完讲义后，返回到第一页，进行简短的自我介绍（英语科目请使用英语自我介绍），介绍完毕后请<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">开始讲题</span></strong></p><p style="box-sizing: border-box;"><span style="color: rgb(112, 218, 101); box-sizing: border-box;">温馨提示：试讲的时候可以使用画笔工具在上面做圈划（形成一种边讲题边写解析的过程），模拟1对1给学生授课场景，适当地与学生进行互动，提高通过率。</span></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p style="box-sizing: border-box;">所有题目试讲完成后，点击“<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">结束录制</span></strong>”</p><p style="box-sizing: border-box;"><span style="color: rgb(255, 52, 81); box-sizing: border-box;">注意事项：</span></p><p style="box-sizing: border-box;"><span style="color: rgb(255, 52, 81); box-sizing: border-box;">1.科目解析较多，可以先在PPT写下大概解题思路，转成<strong style="box-sizing: border-box;">PDF格式</strong>上传</span></p><p style="box-sizing: border-box;"><span style="color: rgb(255, 52, 81); box-sizing: border-box;">2.课件的存放位置请设置在桌面，便于查找</span></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p style="box-sizing: border-box;"><br  /></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin: 0.5em; box-sizing: border-box;"><section class="" style="height: 2em; display: inline-block; box-sizing: border-box;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center; box-sizing: border-box;"><strong style="box-sizing: border-box;">第六步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); box-sizing: border-box; transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><p>

                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E5%BD%95%E5%88%B606.jpg
                                                     " class="" data-ratio="0.72890625" data-w="1280"  />


                                    </p></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><p style="box-sizing: border-box;">点击<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">“提交审核”</span></strong>，弹出<strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">试讲视频上传成功</span></strong>界面，即为成功。<span style="color: rgb(255, 52, 81); box-sizing: border-box;">（如未上传成功，退出重新提交即可）</span></p><section data-role="outer" label="Powered by 135editor.com"><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class=""><p><br  /></p></section></section></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class=""><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class=""><section class="_135editor" data-tools="135编辑器" data-id="90388" style="border: 0px none; box-sizing: border-box;"><section data-width="100%" style="width: 556px; display: -webkit-flex; justify-content: center;"><section style="padding-top: 11px; box-sizing: border-box;"><section class="135brush" data-brushtype="text" style="padding-right: 10px; padding-left: 20px; background-color: rgb(0, 166, 255); height: 40px; line-height: 40px; color: rgb(51, 51, 51); box-sizing: border-box;"><span style="color: rgb(255, 255, 255);">&nbsp;面试试讲方式 &nbsp;</span></section></section><section style="background-color: rgb(0, 166, 255); overflow: hidden; width: 40px;">
                                        <img src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/0.png
                                                  " data-width="100%" class="" data-ratio="1.3125" data-w="64" data-type="png" style="display: block; width: 40px; float: left;"  />


                                    </section></section></section></section></section></section></section></section></section><p><br  /></p></section><section class=""><section class=""><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class="" style="margin: 0.5em;"><section class="" style="height: 2em; display: inline-block;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center;"><strong>第一步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><p>

                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E9%9D%A2%E8%AF%9501.jpg
                                                     " class="" data-ratio="0.7328125" data-w="1280"  />

                                    </p></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class=""><p>第一步，点击<span style="color: rgb(11, 206, 255);">“<strong>增加面试试讲课</strong>”</span>&nbsp;</p></section></section></section></section></section><p><br  /></p><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class=""><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class="" style="margin: 0.5em;"><section class="" style="height: 2em; display: inline-block;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center;"><strong>第二步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><p>

                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E9%9D%A2%E8%AF%9502.jpg
                                                     " class="" data-ratio="0.7328125" data-w="1280"  />




                                    </p></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class="">选择<span style="color: rgb(11, 206, 255);">“试讲科目”“试讲年级”<span style="color: rgb(0, 0, 0);">填写</span>“邮箱”<span style="color: rgb(0, 0, 0);">点击提交</span>&nbsp;</span></section></section></section></section></section></section><p><br  /></p><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class="" style="margin: 0.5em;"><section class="" style="height: 2em; display: inline-block;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center;"><strong>第三步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><p>


                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E9%9D%A2%E8%AF%9503.jpg"
                                             class="" data-ratio="0.7328125" data-w="1280"  />



                                    </p></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class="">选择<span style="color: rgb(11, 206, 255);">“预约时间”<span style="color: rgb(0, 0, 0);">面试</span></span></section></section></section><p><br  /></p><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class="" style="margin: 0.5em;"><section class="" style="height: 2em; display: inline-block;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center;"><strong>第四步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><p>

                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E9%9D%A2%E8%AF%9504.jpg
                                                     " class="" data-ratio="0.7328125" data-w="1280"  />


                                    </p></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class="">勾选合适的时间，点击下一步提交。温馨提示：老师可以提前五分钟进入课堂。</section><section class=""><br  /></section><section class=""><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class="" style="margin: 0.5em;"><section class="" style="height: 2em; display: inline-block;"><section class="" style="padding-right: 0.3em; padding-left: 0.3em; display: inline-block; vertical-align: top; height: 2em; line-height: 2em; border-top-width: 0.1em; border-style: solid none solid solid; border-color: rgb(0, 166, 255); border-bottom-width: 0.1em; border-left-width: 0.1em; color: rgb(0, 166, 255); box-sizing: border-box;"><p style="text-align: center;"><strong>第五步</strong></p></section><section style="margin-left: -0.6em; display: inline-block; vertical-align: top; width: 1.2em; height: 2em; border-bottom-width: 0.1em; border-bottom-style: solid; border-bottom-color: rgb(0, 166, 255); border-right-width: 0.1em; border-right-style: solid; border-right-color: rgb(0, 166, 255); transform: skew(30deg);"></section><section style="width: 0px; display: inline-block; vertical-align: middle; border-top-width: 1.8em; border-top-style: solid; border-top-color: rgb(0, 166, 255); box-sizing: border-box; border-left-width: 1em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 1em !important; border-right-style: solid !important; border-right-color: transparent !important;"></section></section></section></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><p>


                                        <img data-s="300,640" data-type="jpeg" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E4%BF%AE%E6%94%B9%E7%89%88_1/%E9%9D%A2%E8%AF%9505.jpg
                                                     " class="" data-ratio="0.7328125" data-w="1280"  />

                                    </p></section><section class="_135editor" style="border: 0px none; box-sizing: border-box;"><section class=""><section class="">老师可以提前24小时选择修改一次面试时间。</section></section></section></section></section></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 10px; margin-bottom: 10px; text-align: center; box-sizing: border-box;"></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 0.5em; margin-bottom: 0.5em; overflow: hidden; box-sizing: border-box;"><section class="" style="display: inline-block; vertical-align: middle; width: 6px; height: 6px; box-sizing: border-box;"><section style="box-sizing: border-box; transform: rotate(0.1deg);"><section style="width: 6px; height: 6px; background-color: rgb(0, 166, 255); box-sizing: border-box;"></section></section></section><section class="" style="margin-right: -6px; margin-left: -7px; display: inline-block; vertical-align: middle; width: 556px; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: rgb(0, 166, 255); box-sizing: border-box;"></section><section class="" style="display: inline-block; vertical-align: middle; width: 6px; height: 6px; background-color: rgb(0, 166, 255); box-sizing: border-box;"></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 10px; margin-bottom: 10px; text-align: center; box-sizing: border-box; transform: translate3d(0px, 0px, 0px);"><section class="" style="display: inline-block; width: 528.188px; vertical-align: top; box-shadow: rgb(178, 185, 192) 1px 0px 10px; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="color: rgb(133, 118, 106); font-size: 14px; letter-spacing: 1px; line-height: 1.6; box-sizing: border-box;"><p style="box-sizing: border-box;"><br  /></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="padding-right: 10px; padding-left: 10px; text-align: left; box-sizing: border-box;"><p style="text-align: center; box-sizing: border-box;"><span style="font-size: 18px; box-sizing: border-box;"><strong style="box-sizing: border-box;"><span style="color: rgb(11, 206, 255); box-sizing: border-box;">「通关攻略」</span></strong></span></p></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="padding: 15px; text-align: left; color: rgb(11, 206, 255); box-sizing: border-box;"><ol class=" list-paddingleft-2" style=""><li><p style="box-sizing: border-box;">保证相对安静的录制环境和稳定的网络环境</p></li><li><p style="box-sizing: border-box;">要上传讲义和板书，试讲要结合板书</p></li><li><p style="box-sizing: border-box;">注意跟学生的互动（模拟形成一种和学生1对1讲解互动的形式）</p></li><li><p style="box-sizing: border-box;">简历、PPT完善后需转成PDF格式才能上传</p></li><li><p style="box-sizing: border-box;">录制前请先充分准备，面试机会只有一次，要认真对待</p></li></ol><p style="box-sizing: border-box; text-align: center;"><strong><span style="font-family: &#39;Heiti SC Light&#39;; color: rgb(255, 0, 0); font-size: 10.5pt;"><span style="line-height: 23.8px;">（温馨提示：请在每次翻页后在白板中画一笔，保证白板和声音同步</span></span></strong><span style="font-size: 10.5pt; text-align: justify; color: rgb(255, 0, 0);"><span style="font-family: &#39;Heiti SC Light&#39;; line-height: 23.8px;">）</span></span><br  /></p><p>

                                        <img data-s="300,640" data-type="png" src="http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E5%8E%9F%E7%89%88%E5%9B%BE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E9%9D%A2%E8%AF%95%E6%8A%A5%E5%90%8D/%E8%80%81%E5%B8%88%E5%B8%AE.png" class="" data-ratio="0.4" data-w="750"  />


                                    </p></section></section></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 0.5em; margin-bottom: 0.5em; overflow: hidden; box-sizing: border-box;"><section class="" style="display: inline-block; vertical-align: middle; width: 6px; height: 6px; box-sizing: border-box;"><section style="box-sizing: border-box; transform: rotate(0.1deg);"><section style="width: 6px; height: 6px; background-color: rgb(0, 166, 255); box-sizing: border-box;"></section></section></section><section class="" style="margin-right: -6px; margin-left: -7px; display: inline-block; vertical-align: middle; width: 556px; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: rgb(0, 166, 255); box-sizing: border-box;"></section><section class="" style="display: inline-block; vertical-align: middle; width: 6px; height: 6px; background-color: rgb(0, 166, 255); box-sizing: border-box;"></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="margin-top: 10px; margin-bottom: 10px; box-sizing: border-box;"><section class="" style="display: inline-block; width: 556px; vertical-align: top; box-sizing: border-box;"><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box; transform: translate3d(0px, 0px, 0px);"><section class="" style="display: inline-block; vertical-align: top; box-sizing: border-box;"><section class="" style="margin-bottom: 4px; padding-right: 5px; padding-left: 5px; background-color: rgb(11, 206, 255); color: rgb(247, 247, 247); font-size: 18px; box-sizing: border-box;"><p style="box-sizing: border-box;">相关教程链接</p></section><section style="border-top-width: 2px; border-top-style: dotted; border-top-color: rgb(202, 198, 198); width: 118px; box-sizing: border-box;"></section></section></section></section><section class="Powered-by-XIUMI V5" powered-by="xiumi.us" style="box-sizing: border-box;"><section class="" style="box-sizing: border-box;"><section class="" style="padding-top: 2px; padding-bottom: 2px; text-align: justify; font-size: 15px; color: rgb(74, 96, 101); letter-spacing: 0px; line-height: 1.8; box-sizing: border-box;">
                                            <!-- <p style="box-sizing: border-box;">[软件]如何下载老师端、语音检测APP</p>
                                                 <p style="box-sizing: border-box;">[PC端]软件使用教程</p>
                                                 <p style="box-sizing: border-box;">[iPad端]软件使用教程</p>
                                                 <p style="box-sizing: border-box;">[讲课白板]功能介绍</p>
                                                 <p style="box-sizing: border-box;">[老师]常见问题处理办法</p>
                                                 <p style="box-sizing: border-box;">[新师培训]常见问题处理办法</p>
                                               -->

                                            <p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;">
                                                <a href="http://admin.leo1v1.com/article_wx/leo_teacher_software">[软件]如何下载老师端、语音检测APP</a></p>
                                            <p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;"><a href="http://admin.leo1v1.com/article_wx/leo_teacher_pc">[PC端]软件使用教程</a></p>
                                            <p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;"><a href="http://admin.leo1v1.com/article_wx/leo_teacher_ipad">[iPad端]软件使用教程</a></p>
                                            <p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;"><a href="http://admin.leo1v1.com/article_wx/leo_teacher_whiteboard">[讲课白板]功能介绍</a></p>
                                            <p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;"><a href="http://admin.leo1v1.com/article_wx/leo_teacher_deal_question">[老师]常见问题处理办法</a></p>
                                            <p style="white-space: normal; margin: 0px; padding: 0px; box-sizing: border-box;"><a href="http://admin.leo1v1.com/article_wx/leo_teacher_new_teacher_deal_question">[新师培训]常见问题处理办法</a></p>


                                                                                   </section></section></section></section></section></section></section></section>
                        </div>
                        <script nonce="318884283" type="text/javascript">
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
        <div id="js_minipro_dialog" style="display:none;">
            <div class="weui-mask"></div>
            <div class="weui-dialog">
                <div class="weui-dialog__bd">即将打开"<span id="js_minipro_dialog_name"></span>"小程序</div>
                <div class="weui-dialog__ft">
                    <a id="js_minipro_dialog_cancel" href="javascript:void(0);" class="weui-dialog__btn weui-dialog__btn_default">取消</a>
                    <a id="js_minipro_dialog_ok" href="javascript:void(0);" class="weui-dialog__btn weui-dialog__btn_primary">打开</a>
                </div>
            </div>
        </div>
        
        
        <script nonce="318884283">
         var __DEBUGINFO = {
             debug_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/debug/console34c264.js",
             safe_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/moonsafe34c264.js",
             res_list: []
         };
        </script>
        
        <script nonce="318884283">
         (function() {
             function _addVConsole(uri) {
                 var url = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/vconsole/' + uri;
                 document.write('<script nonce="318884283" type="text/javascript" src="' + url + '"><\/script>');
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
        
        
        
        




    </body>
</html>
