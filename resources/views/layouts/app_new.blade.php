<!--  // SWITCH-TO:   ../../webroot/page_js/ -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>理优管理系统NEW</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="/AdminLTE-2.3.11/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="/AdminLTE-2.3.11/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="/AdminLTE-2.3.11/dist/css/skins/skin-blue-light.min.css">
        <link type="text/css" rel="stylesheet" href="/css/jquery.datetimepicker.css" />
        <link href="/css/header.css?{{@$_publish_version}}" rel="stylesheet" type="text/css" />

        <!-- iCheck -->
        <!-- <link rel="stylesheet" href="/AdminLTE-2.3.11/plugins/iCheck/flat/blue.css"> -->
        <!-- Morris chart -->
        <!-- <link rel="stylesheet" href="/AdminLTE-2.3.11/plugins/morris/morris.css"> -->
        <!-- jvectormap -->
        <!-- <link rel="stylesheet" href="/AdminLTE-2.3.11/plugins/jvectormap/jquery-jvectormap-1.2.2.css"> -->
        <!-- Date Picker -->
        <!-- <link rel="stylesheet" href="/AdminLTE-2.3.11/plugins/datepicker/datepicker3.css"> -->
        <!-- Daterange picker -->
        <!-- <link rel="stylesheet" href="/AdminLTE-2.3.11/plugins/daterangepicker/daterangepicker.css"> -->
        <!-- bootstrap wysihtml5 - text editor -->
        <!-- <link rel="stylesheet" href="/AdminLTE-2.3.11/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
           -->
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!-- jQuery 2.2.3 -->
        <script src="/AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
         $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.6 -->
        <script src="/AdminLTE-2.3.11/bootstrap/js/bootstrap.min.js"></script>
        <!-- Morris.js charts -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/morris/morris.min.js"></script> -->
        <!-- Sparkline -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/sparkline/jquery.sparkline.min.js"></script> -->
        <!-- jvectormap -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script> -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->
        <!-- jQuery Knob Chart -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/knob/jquery.knob.js"></script> -->
        <!-- daterangepicker -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script> -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/daterangepicker/daterangepicker.js"></script> -->
        <!-- datepicker -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/datepicker/bootstrap-datepicker.js"></script> -->
        <!-- Bootstrap WYSIHTML5 -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script> -->
        <!-- Slimscroll -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/slimScroll/jquery.slimscroll.min.js"></script> -->
        <!-- FastClick -->
        <!-- <script src="/AdminLTE-2.3.11/plugins/fastclick/fastclick.js"></script> -->
        <!-- AdminLTE App -->
        <script src="/AdminLTE-2.3.11/dist/js/app.min.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <!-- <script src="/AdminLTE-2.3.11/dist/js/pages/dashboard.js"></script> -->
        <!-- AdminLTE for demo purposes -->

        <style>
         .content  .row  .input-group >select {
             display:table-cell;
         }
         .content  .row  .input-group >input{
             display:table-cell;
         }
        </style>


        <script type="text/javascript" src="/js/jquery.datetimepicker.js"></script>
        <script src="/js/jquery.query.js" type="text/javascript"></script>
        <script src="/js/jquery.admin.js?{{@$_publish_version}}" type="text/javascript"></script>
        <script src="/page_js/header.js?{{@$_publish_version}}" type="text/javascript"></script>
        <script src="/page_js/enum_map.js?{{@$_publish_version}}" type="text/javascript"></script>

        <script type="text/javascript" src="/page_js/lib/select_date_range.js?{{@$_publish_version}}"></script>
        <!-- jQuery UI 1.10.3 -->
        <!-- <script type="text/javascript" src="/js/jquery.datetimepicker.js"></script> -->
        <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

        <script type="text/javascript">
         g_power_list= {!! $_power_list !!} ;
        </script>
        {!!  @$js_values_str !!}


        <script type="text/javascript" src="/page_js/{{$_ctr}}/{{$_act}}.js?{{@$_publish_version}}"></script>



    </head>
    <body class="hold-transition skin-blue-light sidebar-mini">
        <div class="wrapper"  >

            <header class="main-header">
                <!-- Logo -->
                <a href="/AdminLTE-2.3.11/index2.html" class="logo remove-for-xs ">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>L</b>EO</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Admin</b>理优</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top"    >
                    <!-- Sidebar toggle button-->
                    <a href="/AdminLTE-2.3.11/#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">切换菜单栏</span>
                    </a>

                    <div style="display: inline-block;"  >
                        <ul class="nav navbar-nav" style="height:25px" >
                            <li><a href="javascript:;" id="header_title1"  style="font-size:20px ;cursor: default; " >  </a></li>
                        </ul>
                    </div>




                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-user"></i>
                                    <span>

                                        {{$_account}}
                                        <i class="caret"></i></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header ">
                                        <img src="" class="img-circle" alt="User Image" />
                                        <p>
                                            {{$_account}}
                                            -
                                            <small> 2015</small>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-right" style="margin-bottom: 3px;">

                                            <a href="#" class="btn btn-primary btn-flat" id="">自定义菜单</a>
                                        </div>

                                        <div class="pull-right">
                                            <a href="#" class="btn btn-primary btn-flat" id="id_user_change_passwd">修改密码</a>

                                            <a href="#" class="btn btn-primary btn-flat" id="id_public_user_reset_power">重置权限</a>
                                            <a href="#" class="btn btn-warning btn-flat" id="id_system_logout">退出</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <!-- User Account: style can be found in dropdown.less -->
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        {!! $_menu_html  !!}
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->

                <script type="text/javascript" >
                 var g_account="{{$_account}}";
                 var g_account_role="{{$_account_role}}";
                 var g_adminid="{{$_adminid}}";
                </script>
                @yield('content')
            </div>


            <!-- Content Wrapper. Contains page content -->
            <!-- /.content-wrapper -->

            <footer class="main-footer" style="height:1px" >
            </footer>
        </div>
        <!-- ./wrapper -->


    </body>
</html>
