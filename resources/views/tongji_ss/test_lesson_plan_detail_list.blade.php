@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">排课人</span>
                        <input class="opt-change form-control" id="id_set_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">确认</span>
                        <select class="opt-change form-control" id="id_success_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">出错</span>
                        <select class="opt-change form-control" id="id_test_lesson_fail_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">学生</span>
                        <input class="opt-change form-control" id="id_userid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">申请角色</span>
                        <select class="opt-change form-control" id="id_require_admin_type" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人</span>
                        <input class="opt-change form-control" id="id_require_adminid" />
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>上课时间 </td>
                    <td> 学生</td>
                    <td> 电话</td>
                    <td>排课人 </td>
                    <td>申请人 </td>
                    <td> 老师 </td>
                    <td> 年级 </td>
                    <td>科目 </td>
                    <td> 成功与否 </td>
                    <td> 失败类型 </td>
                    <td> 说明 </td>
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["lesson_start"]}} </td>
                        <td>{{$var["nick"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["set_lesson_admin_nick"]}} </td>
                        <td>{{$var["require_admin_nick"]}} </td>
                        <td>{{$var["teacher_nick"]}} </td>
                        <td>{{$var["grade_str"]}} </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td>{{$var["success_flag_str"]}} </td>
                        <td>{{$var["test_lesson_fail_flag_str"]}} </td>
                        <td>{{$var["fail_reason"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a style="display:none;" class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a style="display:none;" class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
