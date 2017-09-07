<!DOCTYPE html>
<html class="bg-blue">
    <head>
        <meta charset="UTF-8">
        <title>理优老师管理系统 | 登录</title>
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
         .head{
             height:70px;
             padding:0 10px;
         }
         .nav{
             height:70px;
         }
         .logo{
             display:block;
             height:70px;
             line-height:70px;
         }
         .test{
             background:#ccc
         }
        </style>
    </head>
    <body>
        <!-- head-start -->
        <div class="row head test">
            <div class="col-xs-2 col-xs-offset-2 nav">
                <a href="" class="logo  pull-right">
                    <img src="/img/leo2.png">
                </a>
            </div>
            <div class="col-xs-1 nav test">
                <a href="" class="logo">登录</a>
            </div>

        </div>
        <!-- head-end -->
        <!-- <body class="bg-blue"> -->
        <div class="form-box" id="login-box">
            <div class="header">老师管理系统</div>
            <form >
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" id="id_account" class="form-control" placeholder="请输入注册时使用的手机号码"/>
                    </div>
                    <div class="form-group">
                        <input type="password" id="id_password"  class="form-control" placeholder="请输入密码"/>
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
                    <button type="submit" class="btn btn-primary btn-block btn-flat" id="id_user_login">登录</button>
                </div>
            </form>
        </div>

        <!-- jQuery 2.0.2 -->
        <script src="/js/jquery-2.1.4.js" type="text/javascript"></script>
        <script ype="text/javascript" src="/js/jquery.md5.js"></script>
        <script type="text/javascript" src="/js/jquery.query.js"></script>

        <script type="text/javascript" src="/js/jquery.websocket.js"></script>
      <script type="text/javascript" src="/page_ts/login/teacher.ts?{{$_publish_version}}"></script>
        <!-- Bootstrap -->
        <script src="/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/js/bootstrap-dialog.js" type="text/javascript"></script>
    </body>
</html>
