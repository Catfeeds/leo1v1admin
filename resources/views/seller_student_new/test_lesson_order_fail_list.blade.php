@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" data-always_show="1"  >
                    <div class="input-group ">
                        <span class="input-group-addon">学生</span>
                        <input class="opt-change form-control" id="id_userid" />
                    </div>
                </div>

                <div class="col-xs-12 col-md-4" data-always_show="1"   >
                    <div class="input-group ">
                        <span class="input-group-addon">未签单原因</span>
                        <select class="opt-change form-control" id="id_test_lesson_order_fail_flag" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人</span>
                        <input class="opt-change form-control" id="id_cur_require_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">转介绍</span>
                        <select class="opt-change form-control" id="id_origin_userid_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">签单</span>
                        <select class="opt-change form-control" id="id_order_flag" >
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>上课时间 </td>
                    <td> 申请人</td>
                    <td> 学生</td>
                    <td> 渠道key1</td>
                    <td> 老师</td>
                    <td> 科目</td>
                    <td> 年级</td>
                    <td> 试听课状态 </td>
                    <td> 签单 </td>
                    <td> 签单失败设置时间  </td>
                    <td> 签单失败分类  </td>
                    <td> 签单失败特别说明  </td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["lesson_start"]}}-{{$var["lesson_end"]}} </td>
                        <td>{{$var["cur_require_admin_nick"]}} </td>
                        <td>{{$var["student_nick"]}} </td>
                        <td>{{$var["key1"]}} </td>
                        <td>{{$var["teacher_nick"]}} </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td>{{$var["grade_str"]}} </td>
                        <td>{{$var["test_lesson_fail_flag"]? $var["test_lesson_fail_flag_str"]:""}} </td>
                        <td>{{$var["contract_status_str"]}} </td>
                        <td>{{$var["test_lesson_order_fail_set_time"]}} </td>
                        <td>{{ $var["test_lesson_order_fail_flag"]? $var["test_lesson_order_fail_flag_str"]:""}} </td>
                        <td>{{ $var["test_lesson_order_fail_desc"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
