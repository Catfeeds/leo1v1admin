<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Dashboard</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="/js/bootstrap3-dialog/css/bootstrap-dialog.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="/css/skin-teacher.css">
        <!-- Morris chart -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/morris.js/morris.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/jvectormap/jquery-jvectormap.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/bootstrap-daterangepicker/daterangepicker.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/plugins/iCheck/all.css" type="text/css" >
        <link href="/css/new_header.css" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" href="/css/jquery.datetimepicker.css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="/AdminLTE-2.4.0-rc/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="/AdminLTE-2.4.0-rc/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-teacher sidebar-mini">
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="/teacher_info/index" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><img src="/img/leo1.png" width="100%"></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><img src="/img/leo2.png"></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" title="切换菜单栏">
                        <span class="sr-only">切换菜单栏</span>
                    </a>
                    <span id="header_title1" style="font-size:18px;line-height:300%" > </span> 


                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->
                            {{-- <li class="dropdown messages-menu">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-envelope-o"></i>
                                                </a>
                                                </li> --}}
                            <li class="dropdown messages-menu">
                                <a href="http://admin.leo1v1.com/login/teacher?download=1" class="dropdown-toggle" target="_blank">
                                    <!-- <i class="fa  fa-download color-lyblue"></i> -->

                                    <span style="font-size:14px;" class="color-lyblue">客户端下载</span>
                                </a>
                            </li>
                            {{-- <li class="dropdown messages-menu">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-book"></i>
                                                </a>
                                                </li> --}}
                            <!-- Notifications: style can be found in dropdown.less -->
                            <!-- Tasks: style can be found in dropdown.less -->
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{$_face}}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{ $_nick }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="{{$_face}}" class="img-circle" alt="User Image">
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                        </div>
                                        <div class="pull-right">
                                            <a href="#" class="btn btn-default btn-flat" id="id_system_logout_teacher" >退出系统</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar" style="height:auto">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu tree" data-widget="tree">
                        <li class="treeview menu-open">
                            <a href="#">
                                <i class="fa fa-calendar"></i> <span>课程信息</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="display:block;">
                                <li><a href="{{@$_cur_http}}/teacher_info/index"><i class="fa fa-clipboard"></i> <span> 课程列表 </span> </a></li>
                                <li><a href="{{@$_cur_http}}/teacher_info/current_course"><i class="fa fa-calendar-check-o"></i> <span> 当前课表 </span> </a></li>
                                <li><a href="{{@$_cur_http}}/teacher_info/teacher_apply_list"><i class="fa fa-list"></i> <span>申请帮助列表</span> </a></li>
                            </ul>
                        </li>
                        {{--  <li><a href="/teacher_info/get_train_list"><i class="fa fa-calendar-check-o"></i> <span> 培训列表 </span> </a></li> --}}
                        <li class="treeview menu-open">
                            <a href="#">
                                <i class="fa fa-black-tie"></i> <span>老师档案</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="display:block;">
                                <li ><a href="{{@$_cur_http}}/teacher_info/get_teacher_basic_info" ><i class="fa fa-user"></i>基本信息 </a></li>
                                {{-- <li ><a href="/teacher_info/get_teacher_student"><i class="fa fa-graduation-cap"></i>我的学生</a></li> --}} 
                                {{-- <li ><a href="index2.html"><i class="fa fa-cog"></i>操作台</a></li> --}}
                                {{--<li ><a href="{{@$_cur_http}}/teacher_info/get_teacher_money_info"><i class="fa fa-database"></i>薪资相关</a></li>--}}
                            </ul>
                        </li>
                       {{-- <li  ><a href="{{@$_cur_http}}/teacher_info/file_store"><i class="fa fa-book"></i> <span>资料库</span> </a></li>--}}
                        <li class="treeview menu-open">
                            <a href="#">
                                <i class="fa fa-book"></i> <span>资料库</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="display:block;">
                                <li> <a href="{{@$_cur_http}}/teacher_info/tea_resource"><i class="fa fa-folder"></i> <span>我的资料</span> </a></li>
                                <li><a href="{{@$_cur_http}}/teacher_info/get_leo_resource"><i class="fa fa-folder"></i> <span>理优资料库</span> </a></li>
                                <li><a href="{{@$_cur_http}}/teacher_info/get_leo_train"><i class="fa fa-folder"></i> <span>理优培训库</span> </a></li> 
                            </ul>
                        </li>
                        <li><a href="{{@$_cur_http}}/teacher_info/teacher_lecture_appointment_info"><i class="fa fa-user"></i> <span>招师代理</span> </a></li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
            <!-- jQuery 3 -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/jquery/dist/jquery.min.js"></script>
            <!-- jQuery UI 1.11.4 -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/jquery-ui/jquery-ui.min.js"></script>
            <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
            <script>
             $.widget.bridge('uibutton', $.ui.button);
            </script>
            <!-- Bootstrap 3.3.7 -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

            <script src="/js/bootstrap3-dialog/js/bootstrap-dialog.js" type="text/javascript"></script>

            <!-- Morris.js charts -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/raphael/raphael.min.js"></script>
            <script src="/AdminLTE-2.4.0-rc/bower_components/morris.js/morris.min.js"></script>
            <!-- Sparkline -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
            <!-- jvectormap -->
            <script src="/AdminLTE-2.4.0-rc/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
            <script src="/AdminLTE-2.4.0-rc/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
            <!-- jQuery Knob Chart -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
            <!-- daterangepicker -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/moment/min/moment.min.js"></script>
            <script src="/AdminLTE-2.4.0-rc/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
            <!-- datepicker -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
            <!-- Bootstrap WYSIHTML5 -->
            <script src="/AdminLTE-2.4.0-rc/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
            <!-- Slimscroll -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
            <!-- FastClick -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/fastclick/lib/fastclick.js"></script>
            <!-- AdminLTE App -->
            <script src="/AdminLTE-2.4.0-rc/dist/js/adminlte.js"></script>
            <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
            <!-- AdminLTE for demo purposes -->
            <script src="/AdminLTE-2.4.0-rc/plugins/iCheck/icheck.js"></script>

            <script src="/js/jquery.admin.js?{{$_publish_version}}" type="text/javascript"></script>

            <script type="text/javascript" src="/page_ts/{{$_ctr}}/{{$_act}}.js?{{$_publish_version}}"></script>
            <script src="/page_js/enum_map.js?{{@$_publish_version}}" type="text/javascript"></script>
            <script src="/page_js/new_header.js?{{@$_publish_version}}" type="text/javascript"></script>

            <!-- 全局变量  -->
            {!!  @$js_values_str !!}


            <script type="text/javascript" src="/js/jquery.datetimepicker.js"></script>
            <script type="text/javascript" src="/page_js/lib/select_date_range.js?{{@$_publish_version}}"></script>




            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                @yield('content')
                <!-- /.content -->
            </div>

        </div>
        <!-- ./wrapper -->
    </body>
</html>
