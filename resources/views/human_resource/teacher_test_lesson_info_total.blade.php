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
                        <span >面试老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacher_account"  placeholder="" />
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

                    <td>试听课排课数</td>
                    <td>试听成功课数 </td>
                    <td>试听成功课数(销售-old)</td>
                    <td>试听成功课数(销售)</td>
                    <td>试听成功人数</td>
                    <!--<td>试听成功课数(转介绍)</td>-->
                    <td>试听成功课数(扩课)</td>
                    <td>试听成功课数(换老师)</td>
                    <td>试听到课率</td>
                    <td>签约单数</td>
                    <td>签约课数</td>
                    <!--<td>签约单数(转介绍)</td>-->
                    <td>签约课数(扩课)</td>
                    <td>签约课数(换老师)</td>
                    <td>试听签单率</td>
                    <td>试听签课率</td>
                    <!--<td>试听签单率(转介绍)</td>-->
                    <td>试听扩课率</td>
                    <td>试听转化率(换老师)</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td >{{@$total["all_lesson"]}}</td>
                    <td>{{@$total["success_lesson"]}}</td>
                    <td >{{@$total["lesson_num_old"]}}</td>
                    <td >{{@$total["lesson_num"]}}</td>
                    <td> {{@$total["test_person_num"]}}</td>
                    <!--<td >{{@$total["lesson_num_other"]}}</td>                    -->
                    <td>{{@$total["kk_num"]}}</td>
                    <td >{{@$total["change_num"]}}</td>
                    <td>{{@$total["success_per"]}}%</td>
                    <td >{{@$total["have_order"]}}</td>
                    <td >{{@$total["order_number"]}}</td>
                    <!--<td >{{@$total["have_order_other"]}}</td>-->
                    <td>{{@$total["kk_order"]}}</td>
                    <td >{{@$total["change_order"]}}</td>
                    <td >{{@$total["order_num_per"]}}% </td>
                    <td>{{@$total["order_per"]}}%</td>
                    <!--<td >{{@$total["order_num_per_other"]}}% </td>-->
                    <td>{{@$total["kk_per"]}}% </td>
                    <td>{{@$total["change_per"]}}% </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
