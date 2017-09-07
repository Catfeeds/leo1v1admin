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
             min-width:966px;
             width:100%;
             padding:0 10px;
             border-bottom:1px solid #ccc;
         }
         .nav{
             height:70px;
         }
         .logo{
             height:65px;
             line-height:65px;
         }
         .test{
             background:#ccc
         }
         .nav a{
             font-size:20px;
             font-weigth:600px;
             text-align:center;
             color:#999;
             display:inline-block;
             padding:0 15px;
         }
         .nav a:hover{
             border-bottom:3px solid #0bceff;
             color:#0bceff;
         }
         .r-link{
             padding-left:10px;
         }
         .color-blue{
             color:#0bceff;
         }
         .color-9{
             color:#999;
         }
         .mid-con{
             margin:20px 0;
             height:370px;
         }
         .login{
             /* width:80%; */
             border:1px solid #ccc;
             border-radius:5px;
             padding:0 20px;
         }
         .login-top{
             color:#0bceff;
             font-size:22px;
             font-weight:600;
             text-align:center;
         }
         .login-mid{
             padding:15px 0;
         }
         .bor-btm-3{
             border-bottom:3px solid #ccc;
         }
         .bor-no{
             border:none;
         }
         .btn-blue-ly{
             background-color:#0bceff;
             font-size:18px;
             border-radius:5px;
             color:#fff;
             margin-bottom:20px;
         }
         .btn-blue-ly:hover{
             background-color:#0bbcee;
             color:#fff;
         }
         .footer{
             background-color:#eee;
             height:100%;
         }
         .footer-btm{
             margin-top:150px;
             text-align:center;
             color:#999;
         }
         .bl-left{
             display:inline-block;
             width:20%;
         }
         .bl-right{
             display:inline-block;
             width:75%;
         }

        </style>
    </head>
    <body>
        <!-- head-start -->
        <div class="row head">
            <div class="col-md-2 col-xs-1 col-xs-offset-2 nav">
                <!-- <div class="col-xs-2 col-xs-offset-2 nav"> -->
                <a href="" class="logo  pull-right">
                    <img src="/img/leo2.png">
                </a>
            </div>
            <div class="col-md-4 col-xs-4 nav">
                <a href="" class="logo">登录</a>
                <a href="" class="logo">下载</a>
                <a href="" class="logo">帮助中心</a>
            </div>
            <div class="col-md-3 col-xs-3">
                <a href="" class="logo  color-blue">收藏</a>
                <a href="" class="logo r-link  color-blue">创建桌面快捷方式</a>
            </div>
        </div>
        <!-- head-end -->

        <!-- login-start -->
        <div class="row mid-con">
            <div class="col-md-3 col-md-offset-7 col-xs-8 col-xs-offset-2">
                <div class="login">
                    <div class="login-top">
                        <h3>老师登录</h3>
                        <br>
                    </div>
                    <div class="bor-btm-3 login-mid">
                            <i class="fa fa-fw fa-user bl-left"></i>
                            <input class="bor-no bl-right" type="text"  id="id_account" placeholder="请输入手机账号">
                    </div>
                    <div class="bor-btm-3 login-mid">
                        <i class="fa fa-fw fa-lock bl-left"></i>
                        <input class="bor-no bl-right" type="password" id="id_password" placeholder="请输入密码">
                    </div>
                    <div class="login-mid">
                        <div class="row" id="id_verify"   >
                            <div class="col-xs-5" style=" padding-right: 5px;">
                                <input type="text" id="id_seccode"  class="form-control" placeholder="验证码"/>
                            </div>
                            <div class="col-xs-7" style="padding-left: 10px; padding-right: 0px;">
                                <!-- <li class=" fa fa-times  " style="font-size:34px;color:red; "> </li> -->
                                <!-- <li class=" fa fa-check    " style="font-size:34px;color:green; "> </li> -->
                                <a class="btn btn-success" style="border-radius:16px; cursor: default;  display:none; " >
                                    <i class="fa fa-check"></i>
                                </a>
                                <img id="verify_image" src=""  style="vertical-align:top;"/>
                            </div>
                        </div>
                    </div>
                    <div class="login-mig form-group">
                        <span id="id_errmsg"></span>
                    </div>
                    <div class="login-mig row">
                        <div class="col-xs-6">
                            <label><input class="bor-no" type="checkbox" name="hold"> 记住密码</label>
                        </div>
                        <div class="col-xs-6">
                            <a href="" class="color-blue">忘记密码？</a>
                        </div>
                    </div>
                    <div class="login-mig">
                        <button type="submit" class="btn btn-block btn-blue-ly" id="id_user_login">登录</button>
                    </div>
                    <div class="" id="">
                    </div>
                </div>
            </div>
        </div>
        <!-- login-end -->

        <!-- footer-start -->
        <div class="row footer">
            <div class="footer-btm">
                <a href="" class="color-9">关于理优</a> | <a href="" class="color-9">服务协议</a>
                <br>
                <span>Copyright © 2014 上海理优教育科技有限公司 Shanghai Leo Education Technology Co., Ltd. All Rights Reserved，沪ICP备14054807号</span>
            </div>
        </div>
        <!-- footer-end -->
        {{-- <body class="bg-blue">
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
             </div>
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
        --}}
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
