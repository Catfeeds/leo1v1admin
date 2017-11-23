@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

    <section class="content ">

        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">后台账号</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" id="id_teacherid" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">类型</span>
                        <select class="opt-change form-control " id="id_attendance_type" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">角色</span>
                        <select class="opt-change form-control " id="id_account_role" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">全职老师分类</span>
                        <select class="opt-change form-control" id="id_fulltime_teacher_type" >
                        </select>
                    </div>
                </div>



            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> 老师 </td>
                    <td> 类型 </td>
                    <td> 日期 </td>
                    <td> 当日课时 </td>
                    <td> 延休天数 </td>
                    <td> 延迟上班时间 </td>
                    <td> 提前下班时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["attendance_type_str"]}} </td>
                        <td>{{@$var["attendance_time_str"]}} </td>
                        <td>
                            @if($var["attendance_type"] ==1 && $var["lesson_count"]>0)
                                {{@$var["lesson_count"]/100}}
                            @endif
                        </td>
                        <td>
                            @if($var["attendance_type"] ==3)
                                {{@$var["day_num"]}}天
                            @endif
                        </td>
                        <td>
                            @if($var["attendance_type"] ==2 && $var["delay_work_time"]>0)
                                {{@$var["delay_work_time_str"]}}
                            @endif
                        </td>
                        <td>
                            @if($var["attendance_type"] ==2 && $var["off_time"]>0)
                                {{@$var["off_time_str"]}}
                            @endif
                        </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}

                            >
                                @if($acc=="jack")
                                    <a class="opt-del">删除</a>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
