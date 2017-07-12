@extends('layouts.app')
@section('content')
    <style >
     .icheckbox_minimal.has_same{
         background-position: -140px 0;
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
            <div class="col-xs-1 col-md-3">
            </div>
            <div class="col-xs-1 col-md-3">
                <div class="input-group ">
                    <button class="btn fa fa-plus btn-primary" id="id_add_user">添加用户</button>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-6 col-md-4">
                <table   class=" table table-bordered table-striped"   >
                    <thead>
                        <tr>
                            <td  >权限 <a href="javascript:;" id="id_show_all_power"> 显示全部 </a> <a href="javascript:;" id="id_show_power"> 显示有权限部分 </a> --------- </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_data_list as $var)
				            <tr>
                                <td data-powerid="{{$var["pid"]}}" data-level="{{$var["level"]}}" data-key="{{$var["k_class"]}}" class="opt-select {{$var["class"]}}  {{$var["folder"]?"l_folder":""}} ">
                                    {!!  $var["level"]==1?'<input type="checkbox"  '.$var["has_power_flag"].'  />':""!!}
                                    {{$var["k1"]}}
                                    {!!  $var["level"]==2?' <input type="checkbox" '.$var["has_power_flag"].' />':""!!}
                                    {{$var["k2"]}}
                                    {!!  $var["level"]==3?' <input type="checkbox" '.$var["has_power_flag"].' />':""!!}
                                    {{$var["k3"]}}-- {{$var["pid"]}}
                                    {!! $var["folder"]?"":"<a href=\"javascript:;\" class=\"opt-set-power-user-list\"> 配置权限 <a>"!!}
                                </td>
				            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-xs-6 col-md-2">
                    </div>
                    <div class="col-xs-6 col-md-4">
                        <button class="btn btn-primary" id="id_submit_power"> 提交 </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
@endsection
