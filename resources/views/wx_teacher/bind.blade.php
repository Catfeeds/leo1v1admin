<!DOCTYPE html>
<html class="bg-blue">
    <head>
        <meta charset="UTF-8">
        <title>绑定账号</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

    </head>

    {!!  @$js_values_str !!}
    <body class="bg-blue">

        <div class="form-box" id="login-box">
            <div class="header">绑定老师账号</div>
            <div >
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" id="id_account" class="form-control" placeholder="用户名"/>
                    </div>
                    <div class="form-group">
                        <input type="password" id="id_password"  class="form-control" placeholder="密码"/>
                    </div>          
                    <div class="row" id="id_verify"   >
                        <div class="col-xs-5"
                             style=" padding-right: 5px;" 

                        >    
                            <input type="text" id="id_seccode"  class="form-control" placeholder="验证码"/>
                        </div><!-- /.col -->
                        <div class="col-xs-7"
                             style="padding-left: 0px; padding-right: 0px;    "  >

                            <!-- <li class=" fa fa-times  " style="font-size:34px;color:red; "> </li> -->
                            <!-- <li class=" fa fa-check    " style="font-size:34px;color:green; "> </li> -->
                            <a class="btn btn-success "
                               style="border-radius:16px; cursor: default;  display:none; " ><i class="fa fa-check"></i></a>
                            <img id="verify_image" src=""  style="vertical-align:top;"/>

                        </div><!-- /.col -->
                    </div>
                    <div class="form-group">
                        <span id="id_errmsg"  >  </span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-flat" id="id_user_bind">绑定</button>
                </div>
                
            </div>

        </div>

        </div>



        <!-- jQuery 2.0.2 -->
        <script src="/js/jquery-2.1.4.js" type="text/javascript"></script>
        <script type="text/javascript" src="/js/jquery.md5.js"></script>
        <script type="text/javascript" src="/page_js/{{$_ctr}}/{{$_act}}.js?{{$_publish_version}}"></script>

    </body>
</html>
