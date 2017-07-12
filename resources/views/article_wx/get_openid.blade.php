<!DOCTYPE html>
<!--headTrap<body></body><head></head><html></html>--><html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" >
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">


        <!-- jQuery 2.0.2 -->
        <script src="/js/jquery-2.1.4.js" type="text/javascript"></script>
        <script src="/js/jquery.query.js" type="text/javascript"></script>
        <script src="/js/jquery.admin.js?20170406-154544" type="text/javascript"></script>
        <script src="/page_js/enum_map.js?20170406-154544" type="text/javascript"></script>
        <script src="/page_js/header.js?20170406-154544" type="text/javascript"></script>

        <script type="text/javascript" src="/page_js/lib/select_date_range.js?20170406-154544"></script>
        <!-- jQuery UI 1.10.3 -->
        <script src="/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="/js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
        <!-- Bootstrap -->
        <script src="/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/js/bootstrap-dialog.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <!-- AdminLTE App -->
        <script src="/js/AdminLTE/app.js" type="text/javascript"></script>

        <script  type="text/javascript">
         $(function(){
         });


        </script>
        <title>理优微信分享</title>
    </head>
    <body id="activity-detail" class="zh_CN mm_appmsg">
        <div id="js_article" class="rich_media preview_appmsg">
            
            <div  id="">
                {{@$user_info['nickname']}}
            </div>
            <div class="opt-openid" >
                {{@$user_info['openid']}}
            </div>
            <div class="" >
                二维码
            </div>
            <div class="">
                <img src= "/seller_student_new/erweima?phone={{@$user_info['openid']}}"/>
            </div>
        </div>
    </body>
</html>
