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
             margin:0 0;
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
         .r-link{
             padding-left:10px;
         }
         .color-blue{
             color:#0bceff;
         }
         .color-9{
             color:#999;
         }
         .color-red{
             color:red;
         }
         .mid-con{
             margin:20px 0;
             height:370px;
         }
         .navs .selected{
             border-bottom:3px solid #0bceff;
             color:#0bceff;
         }
         .navs a:hover{
             border-bottom:3px solid #0bceff;
             color:#0bceff;
         }
         .login{
             border:1px solid #ccc;
             border-radius:5px;
             padding:0 20px;
             width: 320px;
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
             margin:100px 0 0 0;
             width:100%;
             height:auto;
             position:fixed;
             bottom:0;
         }
         input:-webkit-autofill{
             background-color:#fff;
             -webkit-box-shadow: 0 0 0px 1000px white inset;
         }
         .footer-btm{
             background:#eee;
             padding-top:100px;
             width:100%;
             text-align:center;
             color:#999;
         }
         @media screen and (max-width:380px){
         .footer-btm{
             padding-top: 10px;
         }
         }
         .bl-left{
             display:inline-block;
             width:20%;
         }
         .bl-right{
             display:inline-block;
             width:75%;
         }
         html {
             height: 100%;
         }
         body{
             min-height: 100%;
             position: relative;
         }
         .download{
             margin-top:100px;
             padding:0 20px;
             height:600px;
         }
         .download>div{
             text-align:center
         }
         .download span{
             cursor:pointer;
             width:150px;
             height:40px;
             margin-left:10px;
             display:inline-block;
             border:1px solid #999;
             font-size:18px;
             border-radius:40px;
             line-height:40px;
             text-align:center;
             color:#999;
         }
         .down-mid{
             margin-top:50px;
             text-align:center;
         }
         .down-tab{
             margin-top:20px;
             text-align:center;
         }
         .down-tab table{
             width:70%;
             max-width:450px;
             margin:0 auto;
         }
         .down-btm{
             margin:20px auto;
             width:50%;
         }
         .table>tbody>tr>td{
             border:none;
         }
         .download>div>.choised{
             background-color:#0bceff;
             border:none;
             color:#fff;
         }
         .middle{
             min-height:500px;
         }
         .band-wx{
             position:absolute;
             top:80px;
             right:100px;
         }
         .down-btm .btn:focus{
             color:#fff;
         }
        </style>
        <script>
         var downflag = '{{$downflag}}';
        </script>
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
            <div class="col-md-4 col-xs-4 nav navs">
                <a href="javascript:;" class="logo selected" data-id="#login">登录</a>
                <a href="javascript:;" class="logo" data-id="#download">下载</a>
                <!-- <a href="javascript:;" class="logo" data-id="#help">帮助中心</a> -->
            </div>
            {{-- <div class="col-md-3 col-xs-3">
                                                                        <a href="" class="logo  color-blue">收藏</a>
                                                                        <a href="" class="logo r-link  color-blue">创建桌面快捷方式</a>
                                                                        </div> --}}
        </div>
        <!-- head-end -->

        <div class="middle">
            <!-- login-start -->
            <div class="row mid-con" id="login">
                <div class="col-xs-8 col-xs-offset-2 col-md-3 col-md-offset-7 ">
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
                            <span id="id_errmsg" class="color-red"></span>
                        </div>
                        <div class="login-mig row">
                            <div class="col-xs-6">
                                <label class="color-9"><input class="bor-no" type="checkbox" id="id_remember">
                                    <span>记住密码</span>
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <!-- <a href="" class="color-blue">忘记密码？</a> -->
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

            <!-- download-start -->
            <div class="row mid-con hide" id="download">
                <!-- 微信老师帮 -->
                <div class="band-wx">
                    <img src="/img/band-wx.jpg" width="100">
                </div>
                <!-- 微信老师帮 -->

                <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
                    <div class="download">
                        <div class="">
                            <span data-type=".pc" class="choised">PC电脑</span>
                            <span data-type=".ipad">iPad</span>
                            <span data-type=".pdf">PDF</span>
                            <span data-type=".handout">讲义模板</span>
                        </div>
                        <div class="down-mid">
                            <h3 class="pc">PC电脑客户端下载</h3>
                            <h3 class="ipad hide">iPad客户端下载</h3>
                            <h3 class="pdf hide">PDF编辑器</h3>
                            <h3 class="handout hide">讲义模板</h3>
                        </div>
                        <div class="down-tab">
                            <table class="table pc">
                                <tr>
                                    <td>软件系统:</td>
                                    <td>Windows7/8/10及以上、MacOS 10.9及以上</td>
                                </tr>
                                <tr>
                                    <td>当前版本:</td>
                                    <td>4.4.0</td>
                                </tr>
                            </table>
                            <table class="table ipad hide">
                                <tr>
                                    <td>硬件要求:</td>
                                    <td>建议iPad2或以上更高版本</td>
                                </tr>
                                <tr>
                                    <td>当前版本:</td>
                                    <td>5.1.0</td>
                                </tr>
                                <tr>
                                    <td>下载方法:</td>
                                    <td>扫一扫二维码下载</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <img src="/img/leotea.png" width="100">
                                    </td>
                                </tr>

                            </table>

                        </div>
                        <div class="down-btm">
                            <button type="submit" class="btn btn-block btn-blue-ly pc download-pc-url">立即下载</button>
                            <button type="submit" class="btn btn-block btn-blue-ly pdf hide download-pdf-url">立即下载</button>
                            <button type="submit" class="btn btn-block btn-blue-ly handout hide download-handout-url">立即下载</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- download-end -->
        </div>
        <!-- footer-start -->
        <div class="row footer">
            <div class="footer-btm">
                <a href="" class="color-9">关于理优</a> | <a href="" class="color-9">服务协议</a>
                <br>
                <span>Copyright © 2014 上海理优教育科技有限公司 Shanghai Leo Education Technology Co., Ltd. All Rights Reserved，沪ICP备14054807号</span>
            </div>
        </div>
        <!-- footer-end -->
        <!-- jQuery 2.0.2 -->
        <script src="/js/jquery-2.1.4.js" type="text/javascript"></script>
        <script ype="text/javascript" src="/js/jquery.md5.js"></script>
        <script type="text/javascript" src="/js/jquery.query.js"></script>

        <script type="text/javascript" src="/js/jquery.websocket.js"></script>
        <script type="text/javascript" src="/page_ts/login/teacher.js?{{$_publish_version}}"></script>
        <!-- Bootstrap -->
        <script src="/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/js/bootstrap-dialog.js" type="text/javascript"></script>
    </body>
</html>