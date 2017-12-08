@extends('layouts.app')
@section('content')
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>

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
                    <td> 老师工号 </td>
                    <td> teacherid </td>
                    <td> 老师 </td>
                    <td> 本月应出勤天数 </td>
                    <td> 本月应出勤天数 </td>
                    <td> 请假天数 </td>
                    <td> 调休天数 </td>
                    <td> 延休天数 </td>
                    <td> 迟到次数</td>
                    <td> 迟到时长 </td>
                    <td> 早退次数</td>
                    <td> 早退时长 </td>
                    <td> 旷工天数 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["adminid"]}} </td>
                        <td>{{@$var["teacherid"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["need_work_day"]}} </td>
                        <td>{{@$var["real_work_day"]}} </td>
                        <td> </td>
                        <td> </td>
                        <td>{{@$var["holiday_day"]}} </td>
                        <td>{{@$var["late_num"]}} </td>
                        <td>{{@$var["late_time"]}}小时</td>
                        <td>{{@$var["early_num"]}} </td>
                        <td>{{@$var["early_time"]}}小时 </td>
                        <td>{{@$var["no_attend_num"]}} </td>
             
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}

                            >
                                <a  href="/user_manage_new/get_fulltime_teacher_attendance_info_full?date_type_config=undefined&date_type=null&opt_date_type=3&start_time={{ $var["start"] }}&end_time={{ $var["end"] }}&attendance_type=-1&teacherid=-1&adminid={{ $var["adminid"] }}&account_role=5&fulltime_teacher_type=-1" target="_blank" >查看详情 </a>
                               
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
