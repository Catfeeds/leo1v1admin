<!--  // SWITCH-TO:   ../../webroot/page_js/ -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>理优管理系统 </title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link rel="stylesheet" href="/css/jquery-ui-1.8.custom.css"/>
        <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/css/bootstrap-dialog.css" rel="stylesheet" type="text/css" />
        <link href="/css/header.css?{{@$_publish_version}}" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" href="/css/al_page.css" />
        <!-- font Awesome -->
        <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/js/Font-Awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link href="/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="/css/morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <!-- <link href="/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" /> -->
        <!-- fullCalendar -->
        <link href="/css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <!-- <link href="/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" /> -->
        <!-- bootstrap wysihtml5 - text editor -->
        <!-- <link href="/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" /> -->
        <!-- Theme style -->
        <link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <link type="text/css" rel="stylesheet" href="/css/jquery.datetimepicker.css" />

        <style>
         .content  .row  .input-group >select {
             display:table-cell;
         }
         .content  .row  .input-group >input{
             display:table-cell;
         }

        </style>
        <!-- add new calendar event modal -->


        <!-- jQuery 2.0.2 -->
        <script src="/js/jquery-2.1.4.js" type="text/javascript"></script>
        <script src="/js/jquery.query.js" type="text/javascript"></script>
        <script src="/js/jquery.admin.js?{{@$_publish_version}}" type="text/javascript"></script>
        <script src="/page_js/enum_map.js?{{@$_publish_version}}" type="text/javascript"></script>
        <script src="/page_js/header.js?{{@$_publish_version}}" type="text/javascript"></script>

        <script type="text/javascript" src="/page_js/lib/select_date_range.js?{{@$_publish_version}}"></script>
        <!-- jQuery UI 1.10.3 -->
        <script src="/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="/js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

        <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>

        <script type="text/javascript">
         g_power_list= {!! $_power_list !!} ;
        </script>

        <!-- Bootstrap -->
        <script src="/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/js/bootstrap-dialog.js" type="text/javascript"></script>
        <!-- Sparkline -->

        <!-- AdminLTE App -->
        <script src="/js/AdminLTE/app.js" type="text/javascript"></script>

        <script type="text/javascript" src="/page_ts/{{$_ctr}}/{{$_act}}.js?{{@$_publish_version}}"></script>


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
                [NEW]理优管理系统
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button" title="切换菜单栏" >
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

                    <ul class="nav navbar-nav" id="_id_noti_info">
                        <!-- Messages: style can be found in dropdown.less-->
                        <!-- Notifications: style can be found in dropdown.less -->
                        <!-- Tasks: style can be found in dropdown.less -->
                        <!-- User Account: style can be found in dropdown.less -->
                    </ul>

                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <!-- Notifications: style can be found in dropdown.less -->

                            <!-- Tasks: style can be found in dropdown.less -->
                            <li class="dropdown tasks-menu">
                                <a href="#" class="dropdown-toggle tasks-count-flag " data-toggle="dropdown">
                                    <i class="fa fa-flag"></i>
                                </a>
                                <ul class="dropdown-menu" >
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                        </ul>
                                    </li>
                                    <li class="footer">
                                        <a href="/self_manage/todo_list">查看所有任务</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- User Account: style can be found in dropdown.less -->
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>
                                    <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img src="" class="img-circle" alt="User Image" />
                                    <p>
                                        {{$_account}}
                                        -
                                        <small> 2017</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">

                                    <div class="pull-right" style="margin-bottom: 3px;">

                                        <a href="#" class="btn btn-primary btn-flat" id="id_self_menu_add">添加本页到收藏</a>
                                        <a href="/self_manage/self_menu_list" class="btn btn-primary btn-flat" >收藏列表</a>
                                    </div>

                                    <div class="pull-right" style="margin-bottom: 3px;">

                                        <a href="#" class="btn btn-primary btn-flat" id="id_ssh_open">SSH 开启(开发)</a>
                                        <a href="#" class="btn btn-primary btn-flat" id="id_new_seller_system">新版客服系统</a>
                                    </div>

                                    <div class="pull-right">
                                        <a href="#" class="btn btn-primary btn-flat" id="id_user_change_passwd">修改密码</a>

                                        <a href="#" class="btn btn-primary btn-flat" id="id_public_user_reset_power">重置权限</a>
                                        <a href="#" class="btn btn-warning btn-flat" id="id_system_logout">退出</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="wrapper row-offcanvas row-offcanvas-left menu-list">
            <!-- Left side column. contains the logo and sidebar -->
            <!-- __COPY_START -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        {!! $_menu_html  !!}
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
            <!-- __COPY_END -->

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->

                <script type="text/javascript" >
                 var g_account="{{$_account}}";
                 var g_account_role="{{$_account_role}}";
                 var g_adminid="{{$_adminid}}";
                </script>
                @yield('content')
            </aside>
        </div>
    </body>
</html>
