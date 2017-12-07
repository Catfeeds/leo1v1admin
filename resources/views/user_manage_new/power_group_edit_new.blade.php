@extends('layouts.app_new2')
@section('content')
    <link href="/ztree/zTreeStyle.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/ztree/jquery.ztree.all.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.exhide.min.js"></script>
     <script type="text/javascript" src="/page_js/lib/select_dlg.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript">
     var zNodes = <?php echo json_encode($list)?>;
	  </script>

    <style >
     .icheckbox_flat-green.has_same{
         background-position: -110px 0;
     }
    </style>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">角色</span>
                    <select class="opt-change form-control " id="id_groupid">
                        @foreach  ($group_list as $var)
                            <option value="{{$var["groupid"]}}"> {{$var["group_name"]}} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">显示</span>
                    <select class="opt-change form-control " id="id_show_flag">
                        <option value="-1" >全部 </option>
                        <option value="1" >仅权限</option>
                        <option value="2" >仅用户</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-1 col-md-2">
                <div class="input-group ">
                    <button id="id_del_group" class="btn fa fa-minus  btn-warning" title="删除当前角色"></button>
                    <button id="id_edit_group" class="btn fa fa-edit btn-warning" title="修改当前角色名"></button>
                </div>
            </div>
            <div class="col-xs-1 col-md-2">
                <div class="input-group ">
                    <button id="id_add_group" class="btn fa fa-plus btn-primary" title="新增角色"></button>
                </div>
            </div>

            <div class="col-xs-1 col-md-2">

                <div class="input-group ">
                    <button class="btn fa fa-plus btn-primary" id="id_add_user">添加用户</button>
                </div>
            </div>

            <div class="col-xs-1 col-md-2">

                <div class="input-group ">
                    <button class="btn  btn-primary" id="id_reload_power">更新在线用户权限</button>
                </div>
            </div>

        </div>
        <hr/>

        <div class="row">
            <div class="col-xs-6 col-md-4">
                <div class="row">
                    <div class="col-xs-6 col-md-10" style="padding:20px;">
                        <div>权限 <a href="javascript:;" id="id_show_all_power"> 显示全部 </a> <a href="javascript:;" id="id_show_power"> 显示有权限部分 </a> --------- </div>
                        <div class="zTreeDemoBackground">
		                        <ul id="treeDemo" class="ztree"></ul>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-8">
                        <button class="btn btn-primary" id="id_submit_power"> 提交 </button>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-md-4">
                <table   class="common-table"   >
                    <thead>
                        <tr>
                            <td >id</td>
                            <td >账户</td>
                            <td >操作</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user_list as $var)
                    <tr>
                                <td >{{$var["uid"]}} </td>
                                <td >{{$var["account"]}} </td>
                                <td >
                                    <div data-uid="{{$var["uid"]}}">
                                        <a class="fa-trash-o  opt-del-account  " title="删除" ></a>
                                    </div>
                                </td>
                    </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>


    </section>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>


@endsection
