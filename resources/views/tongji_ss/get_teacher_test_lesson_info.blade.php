@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>

    <style>
     .bg_red,.bg_red td{
         background-color:#ff3451 !important;
     }
     .bg_orange,.bg_orange td{
         background-color:#0bceff !important;
     }
     .bg_orange_red,.bg_orange_red td{
         background-color:#F8E81C !important;
     }
    </style>
    <script type="text/javascript" >
     var g_month_start= "{{@$month_start}}";
     var g_start_time= "{{@$start_time}}";
     var g_end_time= "{{@$end_time}}";
     var tea_right= "{{@$tea_right}}";
     var g_tea_subject= "{{@$tea_subject}}";
     var g_subject= "{{@$subject}}";
     var adminid= "{{@$adminid}}";
     var g_teacher_test_status= "{{@$teacher_test_status}}";
    </script>
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                
                <div class="col-xs-6 col-md-2" style="display:none">
                    <div class="input-group ">
                        <span >工资分类</span>
                        <select id="id_teacher_money_type" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >教师分类</span>
                        <select id="id_identity" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >试听课科目</span>
                        <select id="id_subject" class ="opt-change" >
                            <option value="20">综合学科</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师第一科目</span>
                        <select id="id_teacher_subject" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >年级段</span>
                        <select id="id_grade_part_ex" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >新教师筛选</span>
                        <select id="id_is_new_teacher" class ="opt-change" >
                            <option value="1"> 全部老师</option>
                            <option value="2"> 最新入职老师</option>
                            <option value="3"> 最近一周入职老师</option>
                            <option value="4"> 最近两周入职老师</option>
                            <option value="5"> 最近30天入职老师</option>
                            <option value="6"> 不包含30天入职老师</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >面试老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacher_account"  placeholder="" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >是否有面试老师</span>
                        <select id="id_have_interview_teacher" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >渠道</span>
                        <input id="id_reference_teacherid">
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >试听课科目数</span>
                        <select id="id_subject_num" class ="opt-change" >
                            <option value="-1"> 全部</option>
                            <option value="1">单科目</option>
                            <option value="2">多科目</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师排课状态</span>
                        <select id="id_teacher_test_status" class ="opt-change" >
                            <option value="-1"> 全部</option>
                            <option value="1">冻结</option>
                            <option value="2">限制排课</option>
                            <option value="3">预警</option>
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="bg_orange"> </span>
                        <input type="text" value="冻结" readOnly="true">
                        <span class="bg_orange_red"> </span>
                        <input type="text" value="限制排课" readOnly="true">
                        <span class="bg_red"> </span>
                        <input type="text" value="预警" readOnly="true">
                    </div>
                </div>
               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >每月平均签单时长</span>
                        <input value="{{@$day_time}}" type="text" readOnly="true">
                    </div>
                </div>
                <div class="col-xs-8 col-md-3">
                    <div class="input-group ">
                        <span >每月平均合同转课程时长</span>
                        <input value="{{@$order_lesson_day}}" type="text" readOnly="true">
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师个数</span>
                        <input value="{{@$count}}" type="text" readOnly="true">
                    </div>
                </div>
               


            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>老师</td>
                    <td>教师分类</td>
                    <td>等级</td>
                    <td>学校</td>
                    <td width="250px">面试评价</td>
                    <td>状态</td>
                    <td class="id_account_teacher">面试老师</td>
                    <td>入职时间</td>
                    {!!\App\Helper\Utils::th_order_gen([                        
                        ["入职时长(天)","work_day" ],                     
                        ["当前常规课学生数","regular_stu_num" ],
                        ["本月剩余课时数","teacher_lesson_count_total"],
                        ["今后三周试听课数","test_lesson_num" ],
                        ["本周剩余试听课数","test_lesson_num_week" ],
                        ["试听课总数","all_lesson" ],
                        ["试听成功课数","success_lesson" ],
                        ["试听成功课数(销售)","lesson_num" ],
                        ["试听成功人数","test_person_num" ],
                        ["试听成功课数(扩课)","kk_num" ],
                        ["试听成功课数(换老师)","change_num" ],
                        ["试听到课率","success_per" ],
                        ["签约单数","have_order" ],
                        ["签约课数","order_number" ],
                        ["签约课数(扩课)","kk_order" ],
                        ["签约课数(换老师)","change_order" ],
                        ["试听签单率","order_num_per" ],
                        ["试听签课率","order_per" ],
                        ["试听扩课率","kk_per" ],
                        ["试听转化率(换老师)","change_per" ],
                       ])  !!}
                    <td width="250px" style="display:none">冻结详情</td>
                    <td width="250px" style="display:none">限制排课</td>
                    <td width="250px" style="display:none">最新反馈</td>

                    <td  class="caozuo"> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["nick"]}} </td>
                        <td width="100px">{{@$var["identity_str"]}} </td>
                        <td class="title_level_str">{{@$var["level_str"]}} </td>
                        <td width="120px">{{@$var["school"]}} </td>
                        <td width="220px">{{@$var["interview_access"]}} </td>
                        <td class="status_str">{{@$var["status_str"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["create_time_str"]}}</td>
                        <td>{{@$var["work_day"]}} </td>
                        <td width="80px" class="regular_stu_num" data-teacherid="{{@$var["teacherid"]}}" >
                            <a href="javascript:;" >{{@$var["regular_stu_num"]}}</a>
                        </td>
                        <td width="80px" class="teacher_lesson_count_total" data-teacherid="{{@$var["teacherid"]}}" >
                            <a href="javascript:;" >{{@$var["teacher_lesson_count_total"]}} </a>
                        </td>
                        <td width="80px" class="test_lesson_num" data-teacherid="{{@$var["teacherid"]}}" >
                            <a href="javascript:;" >{{@$var["test_lesson_num"]}}</a>
                        </td>
                        <td width="80px" class="test_lesson_num_week" data-teacherid="{{@$var["teacherid"]}}" >
                            <a href="javascript:;" >{{@$var["test_lesson_num_week"]}}</a>
                        </td>
                        <td class="all_lesson" data-teacherid="{{@$var["teacherid"]}}" >
                            
                            <a href="javascript:;" > {{@$var["all_lesson"]}}</a>
                        </td>

                        <td class="success_lesson" data-teacherid="{{@$var["teacherid"]}}" data-subject="{{@$var["subject"]}}">
                            <a href="javascript:;" > {{@$var["success_lesson"]}}</a>
                        </td>
                        <td class="lesson_num" data-teacherid="{{@$var["teacherid"]}}" data-subject="{{@$var["subject"]}}">
                            <a href="javascript:;" > {{@$var["lesson_num"]}}</a>
                        </td>
                       
                        <td class="test_person_num" data-teacherid="{{@$var["teacherid"]}}" >
                            <a href="javascript:;" > {{@$var["test_person_num"]}}</a>
                        </td>
                        <td class="kk_num" data-teacherid="{{@$var["teacherid"]}}" data-subject="{{@$var["subject"]}}">
                            <a href="javascript:;" > {{@$var["kk_num"]}}</a>
                        </td>
                        <td class="change_num" data-teacherid="{{@$var["teacherid"]}}" data-subject="{{@$var["subject"]}}">
                            <a href="javascript:;" > {{@$var["change_num"]}}</a>
                        </td>


                        <td>
                            @if (isset($var["success_per"]))
                                {{@$var["success_per"]}}%   
                            @endif
                        </td>
                       
                        <td class="have_order">{{@$var["have_order"]}}</td>
                        <td class="order_number">{{@$var["order_number"]}}</td>
                        <td class="kk_order">{{@$var["kk_order"]}}</td>
                        <td class="change_order">{{@$var["change_order"]}}</td>
                        <td class="order_num_per">
                            @if (isset($var["order_num_per"]))
                                {{@$var["order_num_per"]}}%   
                            @endif
                        </td>
                        <td class="order_per">
                            @if (isset($var["order_per"]))
                                {{@$var["order_per"]}}%   
                            @endif
                        </td>
                        <td class="kk_per">
                            @if (isset($var["kk_per"]))
                                {{@$var["kk_per"]}}%   
                            @endif
                        </td>
                        <td class="change_per">
                            @if (isset($var["change_per"]))
                                {{@$var["change_per"]}}%   
                            @endif
                        </td>


                        <td>
                            @if(@$var["freeze_time"] > 0)
                                冻结时间:{{$var["freeze_time_str"]}}<br>
                                建议:{{$var["freeze_reason"]}}<br>
                                操作人:{{$var["freeze_account"]}}
                            @endif

                        </td>
                        <td>
                            @if(@$var["limit_plan_lesson_type"] > 0)
                                状态:一周限排{{$var["limit_plan_lesson_type"]}}次课<br>
                                原因:{{@$var["limit_plan_lesson_reason"]}}<br>
                                当周已排试听课数:{{$var["test_lesson_num_week"]}}<br>
                                操作人:{{$var["limit_plan_lesson_account"]}}<br>
                                操作时间:{{$var["limit_plan_lesson_time_str"]}}
                            @endif
                        </td>
                        <td>
                            @if(@$var["add_time"] > 0)
                                反馈时间:{{$var["add_time_str"]}}<br>
                                反馈内容:{{$var["record_info"]}}<br>
                                操作人:{{$var["acc"]}}
                            @endif
                        </td>

                        <td >
                            <div class="data"
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                @if(@$var["is_freeze"] == 0)
                                    <a class="opt-teacher-freeze">冻结排课</a>
                                @else
                                    <a class="opt-teacher-freeze">解除冻结</a>
                                @endif
                                <a class="opt-limit-plan-lesson" >限制排课</a>
                                <a class="opt-set-teacher-record-new">反馈</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection


