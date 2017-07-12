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
                <div class="col-xs-12 col-md-4" >
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>            
                <div class="col-xs-6 col-md-3">
                    本页面用于查看2017-06-01日之前数据,之后的请看<span><a href="/human_resource/teacher_record_detail_list_new" >新版数据(点击跳转)</a></span>
                </div>

            </div>
        </div>
        <hr/>
        <table width="100%" border="1" cellpadding="4" cellspacing="0"> 
            <thead>
                <tr>
                    <td rowspan="4">老师</td>
                    <td rowspan="4">科目</td>
                    <td rowspan="4">入职时间</td>
                    <td rowspan="4">反馈情况</td>
                    <td rowspan="4" width="400">监课情况</td>
                    <td rowspan="4" width="400">建议</td>
                    <td rowspan="4">监课人</td>
                    <td colspan="13">教学能力</td>
                    <td colspan="3">教学态度</td>
                    <td colspan="3">课程周边</td>
                    <td rowspan="4">教学打分</td>
                    <td rowspan="4">教学评级</td>
                   
                </tr>
                <tr>
                    <td colspan="3">备课不足</td>
                    <td colspan="4">教学过程</td>
                    <td colspan="5">专业技能</td>
                    <td >语言表达</td>
                    <td colspan="2">教学态度</td>
                    <td >教学事故</td>
                    <td colspan="2">软件操作</td>
                    <td>周边环境</td>
                </tr>
                <tr>
                    <td>5</td><td>10</td><td>10</td><td>10</td><td>10</td><td>10</td><td>10</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>10</td><td>25</td><td>25</td><td>50</td><td>50</td><td>30</td><td>20</td>
                </tr>
                <tr>
                    <td>A</td><td>B</td><td>C</td><td>D</td><td>E</td><td>F</td><td>G</td><td>H</td><td>I</td><td>J</td><td>K</td><td>L</td><td>M</td><td>N</td><td>O</td><td>P</td><td>Q</td><td>R</td><td>S</td>
                    
                </tr>

            </thead>
            <tbody>              
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["nick"]}}</td>
                        <td>{{$var["subject_str"]}}</td>
                        <td>{{$var["create_time_str"]}}</td>
                        <td>{{$var["fkqk"]}}</td>
                        <td width="400">{{$var["record_monitor_class"]}}</td>
                        <td width="400">{{$var["record_info"]}}</td>
                        <td>{{$var["acc"]}}</td>
                        <td>{{$var["courseware_flag_score"]}}</td>
                        <td>{{$var["lesson_preparation_content_score"]}}</td>
                        <td>{{$var["courseware_quality_score"]}}</td>
                        <td>{{$var["tea_process_design_score"]}}</td>
                        <td>{{$var["class_atm_score"]}}</td>
                        <td>{{$var["teacher_blackboard_writing_score"]}}</td>
                        <td>{{$var["tea_rhythm_score"]}}</td>
                        <td>{{$var["tea_method_score"]}}</td>
                        <td>{{$var["knw_point_score"]}}</td>
                        <td>{{$var["dif_point_score"]}}</td>
                        <td>{{$var["content_fam_degree_score"]}}</td>
                        <td>{{$var["answer_question_cre_score"]}}</td>
                        <td>{{$var["language_performance_score"]}}</td>
                        <td>{{$var["tea_attitude_score"]}}</td>
                        <td>{{$var["tea_concentration_score"]}}</td>
                        <td>{{$var["tea_accident_score"]}}</td>
                        <td>{{$var["tea_operation_score"]}}</td>
                        <td>{{$var["class_abnormality_score"]}}</td>
                        <td>{{$var["tea_environment_score"]}}</td>
                        <td>{{$var["record_score"]}}</td>
                        <td>{{$var["record_rank"]}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection
       
