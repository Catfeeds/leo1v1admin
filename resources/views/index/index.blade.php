<!DOCTYPE html>
<html class="bg-blue">
    <script type="text/javascript" >
    var g_passwd_login_flag="{{$passwd_login_flag}}";
    </script>
    <head>
        <meta charset="UTF-8">
        <title>理优管理系统 | 登录</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/css/bootstrap-dialog.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="/css/header.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style type="text/css"> 
         #verify_image { cursor: pointer; } 
         .bootstrap-dialog-message {
             color:#333;
         }
        </style> 
    </head>
    <body class="bg-blue">
        <div class="form-box" id="login-box">
            <div class="header">后台登录</div>
            <form >
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" id="id_account" class="form-control" placeholder="用户名"/>
                    </div>
                    <div class="form-group">
                        <input type="password" id="id_password"  class="form-control" placeholder="密码"/>
                    </div>
                    <div class="row" id="id_verify"   >
                        <div class="col-xs-5" style=" padding-right: 5px;">
                            <input type="text" id="id_seccode"  class="form-control" placeholder="验证码"/>
                        </div><!-- /.col -->
                        <div class="col-xs-7" style="padding-left: 0px; padding-right: 0px;">
                            <!-- <li class=" fa fa-times  " style="font-size:34px;color:red; "> </li> -->
                            <!-- <li class=" fa fa-check    " style="font-size:34px;color:green; "> </li> -->
                            <a class="btn btn-success" style="border-radius:16px; cursor: default;  display:none; " >
                                <i class="fa fa-check"></i>
                            </a>
                            <img id="verify_image" src=""  style="vertical-align:top;"/>
                        </div><!-- /.col -->
                    </div>
                    <div class="form-group">
                        <span id="id_errmsg"></span>
                    </div>
                    <div class="form-group" style="text-align:right">
                        <span style="margin-right:20px" >
                            <a href="javascript:;" id="id_reset_passwd">重置/忘记密码</a>
                        </span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-flat" id="id_user_login">登录</button>
                    <button type="submit" class="btn btn-primary btn-block btn-flat" id="id_wx_login">微信登录</button>
                    <button type="submit" class="btn btn-primary btn-block btn-flat" id="id_wx_account_login">微信验证登录</button>
                </div>
            </form>
        </div>
        
        <!-- jQuery 2.0.2 -->
        <script src="/js/jquery-2.1.4.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.md5.js"></script>
        <script type="text/javascript" src="js/jquery.query.js"></script>

        <script type="text/javascript" src="/js/jquery.websocket.js"></script>
        <script type="text/javascript" src="/page_js/index/index.js?{{$_publish_version}}"></script>
        <!-- Bootstrap -->
        <script src="/js/bootstrap.min.js" type="text/javascript"></script>        
        <script src="/js/bootstrap-dialog.js" type="text/javascript"></script>
    </body>
</html>
