@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-4" data-always_show="1">
                    <div class="input-group ">
                        <input type="text" class=" form-control click_on put_name opt-change"  data-field="user_name" id="id_user_name"  placeholder="学生/家长姓名, 手机号,userid 回车查找" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">销售</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >动作类型</span>
                        <select class="opt-change form-control" id="id_action" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >操作类型</span>
                        <select class="opt-change form-control" id="id_test_opt_type" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >课堂类型</span>
                        <select class="opt-change form-control" id="id_test_lesson_type" >
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>课堂类型</td>
                    <td>用户</td>
                    <td>角色</td>
                    <td>动作类型 </td>
                    <td>操作类型 </td>
                    <td>ip</td>
                    <td>roomid/lessonid</td>
                    <td>操作时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["class_type"]}} </td>
                        <td>{{@$var["student_nick"]}} </td>
                        <td>{{@$var["role_str"]}} </td>
                        <td>{{@$var["action_str"]}} </td>
                        <td>{{@$var["opt_type_str"]}} </td>
                        <td>{{@$var["server_ip"]}} </td>
                        <td>{{@$var["roomid"]}}/{{@$var["lessonid"]}}</td>
                        <td>{{@$var["opt_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit" style="display:none;"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" style="display:none;" title="删除"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

