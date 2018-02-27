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
                    <td> 老师工号 </td>
                    <td> teacherid </td>
                    <td> 老师 </td>
                    <td> 考勤日期 </td>
                    <td> 考勤类型 </td>
                    <td> 考勤状态 </td>
                    <td> 当日课时数 </td>
                    <td> 满足延迟上班时间 </td>
                    <td> 满足提前下班时间 </td>
                    <td> 请假类型 </td>
                    <td> 满足延休的累计课时 </td>
                    <td> 延休日期 </td>
                    <td> 满足调休的加班日期 </td>
                    <td> 上班打卡时间 </td>
                    <td> 下班打卡时间 </td>
                    <td> 考勤结果 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["adminid"]}} </td>
                        <td>{{@$var["teacherid"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["attendance_time_str"]}} </td>
                        <td>{{@$var["kaoqin_type_str"]}} </td>
                        <td>{{@$var["attendance_type_str"]}} </td>
                        <td class="lesson_info" data-teacherid="{{@$var["teacherid"]}}" data-time="{{@$var["attendance_time"]}}" data-flag="1">
                            @if($var["attendance_type"] !=3 && $var["lesson_count"]>0)
                                <a href="javascript:;"  >
                                    {{@$var["lesson_count"]/100}}
                                </a>

                            @endif
                        </td>
                        <td class="lesson_info" data-teacherid="{{@$var["teacherid"]}}" data-time="{{@$var["attendance_time"]}}" data-flag="1">
                            @if($var["attendance_type"] ==2 && $var["delay_work_time"]>0)
                                <a href="javascript:;"  > {{@$var["delay_work_time_str"]}}  </a>
                            @endif
                        </td>
                        <td class="lesson_info" data-teacherid="{{@$var["teacherid"]}}" data-time="{{@$var["attendance_time"]}}" data-flag="1">
                            @if($var["attendance_type"] ==2 && $var["off_time"]>0)
                                <a href="javascript:;"  > {{@$var["off_time_str"]}}  </a>
                            @endif
                        </td>
                        <td>
                            
                        </td>
                        <td class="lesson_info" data-teacherid="{{@$var["teacherid"]}}" data-time="{{@$var["holiday_start_time"]}}" data-flag="2">
                            @if($var["attendance_type"] ==3 && $var["holiday_lesson_count"]>0)
                                {{@$var["holiday_lesson_count"]/100}}
                            @endif
                        </td>
                        <td>
                            @if($var["attendance_type"] ==3 )
                                {{@$var["holiday_hugh_time_str"]}}
                            @endif
                        </td>
                        <td>
                          {!!  @$var["extra_time_info"] !!}
                            
                        </td>
                        <td>{{@$var["card_start_time_str"]}} </td>
                        <td>{{@$var["card_end_time_str"]}} </td>
                        <td>
                            {{@$var["result"]}}
                        </td>





                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}

                            >
                                <a class="opt-show-lessons-new"  title="课程列表-new">课程-new</a>
                                <a  href="/teacher_info_admin/lesson_list?teacherid={{$var["teacherid"]}}" target="_blank" title="跳转到老师课表">课 </a>
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
