@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <script type="text/javascript" src="/page_js/teacher_freeze_limit_record.js"></script>

    <script type="text/javascript" >
     var g_month_start= "{{@$month_start}}";
     var g_start_time= "{{@$start_time}}";
     var g_end_time= "{{@$end_time}}";
     var tea_right= "{{@$tea_right}}";
     var g_tea_subject= "{{@$tea_subject}}";
     var g_subject= "{{@$subject}}";
     var adminid= "{{@$adminid}}";
     var g_teacher_test_status= "{{@$tea_status}}";
    </script>
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

    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div id="id_date_range" class="opt-change">
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >试听课科目</span>
                        <select id="id_subject" class ="opt-change" >
                            <option value="20">综合</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师科目</span>
                        <select id="id_teacher_subject" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师类型</span>
                        <select id="id_identity" class ="opt-change" ></select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >试听课年级</span>
                        <select id="id_grade_part_ex" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师状态</span>
                        <select id="id_tea_status" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">冻结</option>
                            <option value="2">限课</option>
                            <option value="3">正常</option>
                            <option value="4">预警</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否全职老师</span>
                        <select id="id_qzls_flag" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">非全职老师</option>
                            <option value="2">全职老师</option>
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

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否当月入职</span>
                        <select id="id_create_now" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >面试老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacher_account"  placeholder="" />
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


            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>老师</td>
                    <td>老师类型</td>
                    <td>是否全职老师</td>
                    <td>入职时长</td>
                    <td width="320px">面试评价</td>
                    <td>面试人</td>
                    <td>状态</td>
                    <td>年级段</td>
                    <td style="display:none">第二科目年级段</td>
                    <td> 当前常规课学生数</td>
                    <td>本月剩余课时数</td>
                    <td>今后三周试听课数</td>
                    <td>本周剩余试听课数</td>
                    {!!\App\Helper\Utils::th_order_gen([
                        ["试听排课数","all_lesson" ],
                        ["试听成功课数","success_lesson" ],
                        ["试听成功课数(销售-old)","lesson_num_old" ],
                        ["试听成功课数(销售)","lesson_num" ],
                        ["试听成功人数","test_person_num" ],
                        //["试听成功人数(转介绍)","lesson_num_other" ],
                        ["试听成功课数(扩课)","kk_num" ],
                        ["试听成功课数(换老师)","change_num" ],
                        ["试听到课率","success_per" ],
                        ["签约单数","have_order" ],
                        ["签约课数","order_number" ],
                        //["签约单数(转介绍)","have_order_other" ],
                        ["签约课数(扩课)","kk_order" ],
                        ["签约课数(换老师)","change_order" ],
                        ["试听签单率","order_num_per" ],
                        ["试听签课率","order_per" ],
                        //["试听签单率(转介绍)","order_num_per_other" ],
                        ["试听扩课率","kk_per" ],
                        ["试听转化率(换老师)","change_per" ],
                       ])  !!}
                    <td style="display:none">冻结情况</td>
                    <td style="display:none">限课情况</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}}</td>
                        <td>{{@$var["identity_str"]}}</td>
                        <td>
                            @if($var["account_role"]==5)
                                是
                            @else
                                否
                            @endif
                        </td>
                        <td>{{@$var["work_day"]}}</td>
                        <td>{{@$var["interview_access"]}}</td>
                        <td>{{@$var["account"]}}</td>
                        <td>{{@$var["status_str"]}}</td>
                        <td>{{@$var["grade_part_ex_str"]}}</td>
                        <td>{{@$var["second_grade_str"]}}</td>
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
                            {{@$var["success_lesson"]}}
                        </td>
                        <td class="lesson_num_old" data-teacherid="{{@$var["teacherid"]}}" data-subject="{{@$var["subject"]}}">
                            {{@$var["lesson_num_old"]}}
                        </td>

                        <td class="lesson_num" data-teacherid="{{@$var["teacherid"]}}" data-subject="{{@$var["subject"]}}">
                             <a href="javascript:;" >{{@$var["lesson_num"]}}</a>

                        </td>
                        <td class="test_person_num" data-teacherid="{{@$var["teacherid"]}}" >
                           {{@$var["test_person_num"]}}
                        </td>
                        <!--<td class="lesson_num_other" data-teacherid="{{@$var["teacherid"]}}" data-subject="{{@$var["subject"]}}">
                            {{@$var["lesson_num_other"]}}
                        </td>-->

                        <td class="kk_num" data-teacherid="{{@$var["teacherid"]}}" data-subject="{{@$var["subject"]}}">
                            {{@$var["kk_num"]}}
                        </td>
                        <td class="change_num" data-teacherid="{{@$var["teacherid"]}}" data-subject="{{@$var["subject"]}}">
                             {{@$var["change_num"]}}
                        </td>


                        <td>
                            @if (isset($var["success_per"]))
                                {{@$var["success_per"]}}%
                            @endif
                        </td>

                        <td class="have_order">{{@$var["have_order"]}}</td>
                        <td class="order_number">{{@$var["order_number"]}}</td>
                        <!-- <td class="have_order_other">{{@$var["have_order_other"]}}</td> -->
                        <td class="kk_order">{{@$var["kk_order"]}}</td>
                        <td class="change_order">{{@$var["change_order"]}}</td>
                        <td class="order_num_per" data-teacherid="{{@$var["teacherid"]}}">
                            <a href="javascript:;" >
                                @if (isset($var["order_num_per"]))
                                    {{@$var["order_num_per"]}}%
                                @endif
                            </a>
                        </td>
                        <td class="order_per">
                            @if (isset($var["order_per"]))
                                {{@$var["order_per"]}}%
                            @endif
                        </td>
                       <!-- <td class="order_num_per_other">
                            @if (isset($var["order_num_per_other"]))
                                {{@$var["order_num_per_other"]}}%
                            @endif
                        </td>
                        -->
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
                            @if($var["not_grade_str"])
                                冻结年级:{{$var["not_grade_str"]}}<br>
                                操作人:{{@$var["freeze_adminid_str"]}}
                            @endif
                        </td>
                        <td>
                            @if($var["limit_plan_lesson_type"]>0)
                                限课详情:{{$var["limit_plan_lesson_type_str"]}}<br>
                                操作人:{{$var["limit_plan_lesson_account"]}}<br>
                                操作时间:{{$var["limit_plan_lesson_time_str"]}}<br>
                            @endif
                        </td>

                        <td>
                            <div class="data"
                                {!! \App\Helper\Utils::gen_jquery_data($var) !!}
                            >
                                <a class="opt-teacher-freeze">冻结排课</a>
                                <a class="opt-freeze-list">冻结排课记录</a>
                                <a class="opt-limit-plan-lesson" >限制排课</a>
                                <a class="opt-limit-plan-lesson-list" >限制排课记录</a>
                                <a class="opt-set-teacher-record-new" >反馈</a>
                                <a class="opt-get-teacher-record">反馈记录</a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
