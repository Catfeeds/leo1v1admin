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

        <!-- Font Awesome -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="/AdminLTE-2.4.0-rc/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->

        <link rel="stylesheet" href="/css/skin-blue-light.css">




        <style>
         .content  .row  .input-group >select {
             display:table-cell;
         }
         .content  .row  .input-group >input{
             display:table-cell;
         }
         .wrapper {
             position :static;
         }

        </style>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="/AdminLTE-2.4.0-rc/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="/AdminLTE-2.4.0-rc/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="hold-transition skin-blue-light isidebar-mini">


            <!-- jQuery 3 -->
            <script src="/AdminLTE-2.4.0-rc/bower_components/jquery/dist/jquery.min.js"></script>
            <!-- jQuery UI 1.11.4 -->


            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" style="margin-left:0px">
                <!-- Main content -->
                @yield('content')
                <!-- /.content -->
            </div>
        </div>
        <!-- ./wrapper -->
</html>
