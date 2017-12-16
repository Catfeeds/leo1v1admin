<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>理优管理系统 </title>
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
        <link rel="stylesheet" href="/css/skin-blue-light.css">
        <!-- Morris chart -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/morris.js/morris.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/jvectormap/jquery-jvectormap.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/bootstrap-daterangepicker/daterangepicker.css">

        <link type="text/css" rel="stylesheet" href="/css/al_page.css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/plugins/iCheck/all.css" type="text/css" >
        <link href="/css/new_header.css" rel="stylesheet" type="text/css" />

        <style>


        </style>
        <link type="text/css" rel="stylesheet" href="/css/jquery.datetimepicker.css" />


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="/AdminLTE-2.4.0-rc/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="/AdminLTE-2.4.0-rc/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue-light isidebar-mini">
        <div class="">

            <header class="main-header">
                <!-- Logo -->
                <a href="/" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b  >理</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>理优教育</b></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" title="切换菜单栏">
                        <span class="sr-only">切换菜单栏</span>
                    </a>


                    <div class="navbar-custom-menu " style="float:left;">

                        <ul class="nav navbar-nav"  >
                            <li><a href="javascript:;" id="header_title1"  style="font-size:18px">  </a></li>
                        </ul>
                    </div>





                    <div class="navbar-custom-menu ">



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
                                        <img src="{{$_face_pic}}" width="90px" height="90px" class="img-circle" alt="上传头像" />
                                        <p>
                                            {{$_account}}
                                            -
                                            <small> 2017</small>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">

                                        <div class="pull-right" style="margin-bottom: 3px;">
                                            <a href="#" class="btn btn-default btn-flat" id="id_self_menu_add">添加本页到收藏</a>
                                            <a href="/self_manage/self_menu_list" class="btn btn-default btn-flat" >收藏列表</a>
                                        </div>

                                        <div class="pull-right" style="margin-bottom: 3px;">

                                            <a href="#" class="btn btn-default btn-flat" id="id_ssh_open">SSH 开启(开发)</a>
                                            <a href="#" class="btn btn-default btn-flat" id="id_call_check_systen">电话辅助系统</a>
                                        </div>

                                        <div class="pull-right">
                                            <a href="#" class="btn btn-default btn-flat" id="id_now_refresh">查询设置</a>
                                            <a href="#" class="btn btn-default btn-flat" id="id_menu_config">菜单选项</a>
                                            <a href="#" class="btn btn-default btn-flat" id="id_public_user_reset_power">重置权限</a>
                                            <a href="#" class="btn btn-default btn-flat" id="id_system_logout">退出</a>
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
                <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" data-widget="tree">

                        {!! $_menu_html  !!}

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

            <script src="/AdminLTE-2.4.0-rc/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/AdminLTE-2.4.0-rc/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
            <script src="/AdminLTE-2.4.0-rc/plugins/input-mask/jquery.inputmask.extensions.js"></script>
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
            <script src="/AdminLTE-2.4.0-rc/plugins/iCheck/icheck.js"></script>
            <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
            <script type="text/javascript" >
             var g_account="{{$_account}}";
             var g_account_role="{{$_account_role}}";
             var g_adminid="{{$_adminid}}";
            </script>

            <!-- AdminLTE for demo purposes -->
            <script src="/js/jquery.admin.js?{{@$_publish_version}}" type="text/javascript"></script>
            <script src="/page_js/enum_map.js?{{@$_publish_version}}" type="text/javascript"></script>
            <script src="/page_js/new_header.js?{{@$_publish_version}}" type="text/javascript"></script>
            <script type="text/javascript" src="/page_ts/{{$_ctr}}/{{$_act}}.js?{{@$_publish_version}}"></script>
            <!-- 全局变量  -->
            {!!  @$js_values_str !!}


            <script type="text/javascript">
             g_power_list= {!! $_power_list !!} ;
            </script>

            <script type="text/javascript" src="/js/jquery.datetimepicker.js"></script>
            <script type="text/javascript" src="/page_js/lib/select_date_range.js?{{@$_publish_version}}"></script>
            <script src="/page_js/lib/select_dlg_ajax.js?{{@$_publish_version}}" type="text/javascript"></script>
            <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
            <script src="/page_ts/lib/admin_set_select_field.js?{{@$_publish_version}}" type="text/javascript"></script>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                @yield('content')
                <!-- /.content -->
            </div>
        </div>
        <!-- ./wrapper -->

    </body>
    <script type="text/javascript">
     if(g_account=="jack" || g_account=="jim" || g_account=="adrian"||g_account=="abner" || g_account=="michelle" ){
         download_show();
         $(".page-opt-show-all-xls").show();
     }
    </script>
</html>
