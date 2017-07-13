<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>理优管理系统 </title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/css/bootstrap-dialog.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <link href="/css/header.css" rel="stylesheet" type="text/css" />
        <!-- jQuery 2.0.2 -->
        <script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>
        <script src="/js/jquery.admin.js?{{$_publish_version}}" type="text/javascript"></script>
        <script src="/page_js/header_new.js?{{$_publish_version}}" type="text/javascript"></script>
        <!-- jQuery UI 1.10.3 -->

        <!-- Bootstrap -->
        <script src="/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/js/bootstrap-dialog.js" type="text/javascript"></script>
        <!-- Sparkline -->

        <!-- AdminLTE App -->
        <script src="/js/AdminLTE/app.js" type="text/javascript"></script>


        <script type="text/javascript" src="/page_ts/{{$_ctr}}/{{$_act}}.js?{{$_publish_version}}"></script>


        <!-- 全局变量  -->
        {!!  @$js_values_str !!}


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

    </head>

    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="/" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
               理优管理系统 
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-left ">
                    <ul class="nav navbar-nav">
                        <li><a href="javascript:;" id="header_title1"  style="font-size:20px ;cursor: default; " >  </a></li>
                    </ul>
                </div>

                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <!-- Notifications: style can be found in dropdown.less -->
                        <!-- Tasks: style can be found in dropdown.less -->
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>{{$_account}}<i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-warning btn-flat" id="id_bind_off">解绑账号</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="active"> <a href="/wx_teacher_info/index"  > <i class="fa "></i><span>个人信息</span></a></li>
                        <li class="active"> <a href="/wx_teacher_info/current_course"  > <i class="fa "></i><span>课程表</span></a></li>
                        <li class="active"> <a href="/wx_teacher_info/test_lesson_list"  > <i class="fa "></i><span>试听抢单</span></a></li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <script type="text/javascript" >
             $(function(){
                 $("#id_bind_off").on("click",function(){
                     $.do_ajax("/wx_teacher/bind_off",function(rep){
                         if ( rep.ret !=0 ) {
                            alert(rep.info) ;
                         }else{
                             alert("解绑成功");
                             window.location.reload();
                         }
                     });
                 });
             });
            </script>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->

                @yield('content')
            </aside>
        </div>
    </body>
</html>



