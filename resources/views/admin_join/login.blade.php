<!DOCTYPE html>
<html class="bg-blue">

    <head>
        <meta charset="UTF-8">
        <title>理优教育|面试填表 </title>
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
            <div class="header">面试填表-登录</div>
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" id="id_phone" class="form-control" placeholder="手机号"/>
                        <div class="row" style="margin-top:5px;" >
                            <div class="col-xs-8 col-md-8" >
                                <div class="input-group ">
                                    <input type="text" id="id_code" class="form-control" placeholder="验证码"/>
                                </div>
                            </div>
                            <div class="col-xs-4 col-md-4">
                                <button
                                    id="id_send_code"
                                    class="btn btn-primary btn-flat" >发到手机</button>
                            </div>

                        </div>

                    </div>

                    <button  class="btn btn-primary btn-block btn-flat" id="id_login">登录</button>

                </div>
                
        </div>



        </div>



        <!-- jQuery 2.0.2 -->
        <script src="/js/jquery-2.1.4.js" type="text/javascript"></script>

        <script type="text/javascript" src="/page_js/admin_join/login.js?{{$_publish_version}}"></script>

    </body>
</html>
