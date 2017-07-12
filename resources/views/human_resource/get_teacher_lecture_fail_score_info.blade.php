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
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div id="id_date_range" class="opt-change">
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table class="table table-bordered"> 
            <thead>
                <tr>
                    <td >教师气场</td>
                    <td >教师经验</td>
                    <td >课堂氛围</td>
                    <td >知识点讲解</td>
                    <td >讲题方法思路</td>
                    <td >知识点与练习比例</td>
                    <td >重难点把握</td>
                    <td >板书</td>
                    <td >讲解节奏</td>
                    <td >语言表达和组织能力</td>
                    <td >教师端操作</td>
                    <td >周边环境</td>
                </tr>
            </thead>
            <tbody>
               
                    <tr>
                        <td>{{$list["teacher_mental_aura_score_ave"]}}% </td>
                        <td>{{$list["teacher_exp_score_ave"]}}% </td>
                        <td>{{$list["teacher_class_atm_score_ave"]}}% </td>
                        <td>{{$list["teacher_point_explanation_score_ave"]}}% </td>
                        <td>{{$list["teacher_method_score_ave"]}}% </td>
                        <td>{{$list["teacher_knw_point_score_ave"]}}% </td>
                        <td>{{$list["teacher_dif_point_score_ave"]}}% </td>
                        <td>{{$list["teacher_blackboard_writing_score_ave"]}}% </td>
                        <td>{{$list["teacher_explain_rhythm_score_ave"]}}% </td>
                        <td>{{$list["teacher_language_performance_score_ave"]}}% </td>
                        <td>{{$list["teacher_operation_score_ave"]}}% </td>
                        <td>{{$list["teacher_environment_score_ave"]}}% </td>
                        
                       
                       
                    </tr>
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

