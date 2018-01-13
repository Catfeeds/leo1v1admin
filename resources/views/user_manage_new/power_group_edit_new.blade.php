@extends('layouts.app_new2')
@section('content')
    <link href="/ztree/zTreeStyle.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/ztree/jquery.ztree.all.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.exhide.min.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax_more.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript">
     var zNodes = <?php echo json_encode($list)?>;
	  </script>

    <style >
     .icheckbox_flat-green.has_same{
         background-position: -110px 0;
     }
     #treeDemo li span {
         font-size: 16px;
     }
     .ztree li span.button {
         margin: 2px;
     }
     .ztree li span.button.add { margin-left: 2px;margin-right: -1px;background-position: -144px 0;vertical-align: top;}
     .power_title{ margin: 5px auto;font-size: 16px; }
     .fa-plus{ margin-left:10px}
     .btn_new{position: absolute;right: 30px;top: 15px;}
     .btn_new button{margin-right:10px}
    </style>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">角色组</span>
                    <select class="opt-change form-control " id="id_role_groupid" onchange='get_search_group(this.options[this.options.selectedIndex].value)'></select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">权限组</span>
                    <select class="opt-change form-control " id="id_groupid"></select>
                </div>
            </div>

            <div class="col-xs-1 col-md-1">
                <div class="input-group ">
                    <button class="btn btn-info" id="search_this">搜索</button>
                </div>
            </div>

            <!-- <div class="col-xs-6 col-md-2">
                 <div class="input-group ">
                 <span class="input-group-addon">显示</span>
                 <select class="opt-change form-control " id="id_show_flag">
                 <option value="-1" >全部 </option>
                 <option value="1" >仅权限</option>
                 <option value="2" >仅用户</option>
                 </select>
                 </div>
                 </div> -->

            <div class="col-xs-1 col-md-1">
                <div class="input-group ">
                    <button edit="1" class="id_edit_power_group btn btn-primary">添加权限组</button>
                </div>
            </div>
            <div class="col-xs-1 col-md-1">
                <div class="input-group ">
                    <button edit="2" class="id_edit_power_group btn btn-warning">修改权限组</button>
                </div>
            </div>

            <div class="col-xs-1 col-md-1">
                <div class="input-group ">
                    <button id="id_del_group" class="btn btn-danger"">删除权限组</button>
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
                <table  class="common-table">
                    <thead>
                        <tr>
                            <td colspan="6">
                                <div class="power_title">
                                    <span style="margin-left:20px">权限组用户</span>                            
                                    <div class="input-group btn_new">
                                        <button class="btn fa btn-primary" id="id_add_user">添加用户</button>
                                        <button class="btn fa btn-primary" id="batch_add_user">批量添加</button>
                                        <button class="btn fa btn-danger" id="batch_dele_user">批量删除</button>
                                    </div>                  
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>       
                        <tr>
                            <td ></td>
                            <td >id</td>
                            <td >账户</td>
                            <td >真实姓名</td>
                            <td>已存在权限</td>
                            <td >操作</td>
                        </tr>

                        @foreach ($user_list as $var)
                            <tr class="user_row">
                                <td><input type="checkbox" class="dele_uid_str"></td>
                                <td >{{$var["uid"]}} </td>
                                <td >{{$var["account"]}} </td>
                                <td >{{$var["name"]}} </td>
                                <td >{{@$var["permit_name"]}}</td>
                                <td >
                                    <div data-uid="{{$var['uid']}}" data-name="{{$var["name"]}}" data-account="{{$var["account"]}}">
                                        <a class="fa-trash-o  opt-del-account" title="删除" ></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="group_all hide">
            @if($group_all)
                @foreach($group_all as $role => $group)
                    <select id="role_{{$role}}">
                        @foreach($group as $var)
                            <option value="{{$var['groupid']}}">{{$var['group_name']}}</option>
                        @endforeach
                    </select>
                @endforeach
            @endif
        </div>
    </section>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>


@endsection
