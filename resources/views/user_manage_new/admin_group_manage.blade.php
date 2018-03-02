@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/select_seller_month_thing.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" >
    </script>
    <style>
     #cal_week th  {
         text-align:center;
     }

     #cal_week td  {
         text-align:center;
     }

     #cal_week .select_free_time {
         background-color : #17a6e8;
     }
     #cal_week .leave {
         background-color : #f00;
     }
     #cal_week .overtime {
         background-color : orange;
     }
    </style>

    <section class="content ">

        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">历史分组</span>
                        <select class="opt-change form-control" id="id_monthtime_flag" >
                            <option value="1">否</option>
                            <option value="2">是</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_copy_now"> 复制当前数据 </button>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>类型 </td>
                    <td>总监 </td>
                    <td>经理 </td>
                    <td>小组 </td>
                    <td>成员 </td>
                    <td>入职时间 </td>
                    <td>离职时间 </td>
                    <td>是否离职 </td>
                    <td style="display:none;" >分配信息</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )

                    <tr class="{{$var["level"]}}">



                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>

                        <td  data-class_name="{{@$var["first_group_name_class"]}}" class=" first_group_name  {{$var["main_type_class"]}} {{@$var["first_group_name_class"]}}  " >{{@$var["first_group_name"]}}</td>

                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name {{@$var["first_group_name_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>

                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>

                        <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}} </td>

                        <td>{{$var['become_member_time']}}</td>
                        <td>{{$var['leave_member_time']}}</td>
                        <td>{!! $var['del_flag_str'] !!}</td>


                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >


                               @if($monthtime_flag==1)
                                   <a class="opt-add-major-group">新增总监分组</a>
                                   <a class="opt-edit-major-group">修改-总监</a>
                                   <a class="opt-del-major-group">删除-总监</a>
                                   <a class="opt-add-main-group">新增-经理</a>
                                   <a class="opt-assign-major-group">分配-经理</a>
                                   <a class="fa fa-list-alt opt-show_change_log btn" title="组员添加记录"></a>

                                   <a class="opt-assign-main-group">分配小组</a>
                                   <a class="opt-edit-main-group">修改</a>
                                   <a class="opt-del-main-group">删除</a>
                                   <a class="opt-add-main-group-user">新增小组</a>
                                   <a class="opt-assign-group-user">新增成员</a>
                                   <a class="opt-edit-group">修改</a>
                                   <a class="opt-del-group">删除</a>
                                   <a class="opt-del-admin">删除成员</a>
                                   @if($var["main_type"]==2 && $var["level"]=="l-5")
                                   <a class="opt-change-admin">换队</a>
                                   @endif
                                   @if($var["main_type"]==4 && $var["level"]=="l-3")
                                       <a class="opt-set-subject">配置科目</a>
                                   @endif
                               @else
                                   <a class="opt-add-major-group-new">新增总监分组</a>
                                   <a class="opt-add-main-group-new">新增-经理</a>
                                   <a class="opt-assign-main-group-new">分配小组</a>
                                   <a class="fa fa-list-alt opt-show_change_log btn" title="组员添加记录"></a>


                                   <a class="opt-assign-major-group-new">分配-经理</a>
                                   <a class="opt-edit-major-group-new">修改-总监</a>
                                   <a class="opt-del-major-group-new">删除-总监</a>


                                   <a class="opt-edit-main-group-new">修改</a>
                                   <a class="opt-del-main-group-new">删除</a>
                                   <a class="opt-add-main-group-user-new">新增小组</a>
                                   <a class="opt-assign-group-user-new">新增成员</a>
                                   <a class="opt-edit-group-new">修改</a>
                                   <a class="opt-del-group-new">删除</a>
                                   <a class="opt-del-admin-new">删除成员</a>
                                   @if($var["main_type"]==2 && $var["level"]=="l-5")
                                   <a class="opt-change-admin">换队</a>
                                   @endif
                               @endif


                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

    <div style="display:none;" >
        <div id="id_assign_log">
            <table   class="table table-bordered "   >
                <tr>  <th> 操作时间 <th>操作人 <th> 原来小组  </tr>
                    <tbody class="data-body">
                    </tbody>
            </table>
        </div>
    </div>


@endsection
