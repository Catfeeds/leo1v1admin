@extends('layouts.app')
@section('content')

<script src='/js/moment.js'></script>
<link rel='stylesheet' href='/css/fullcalendar.css' />


<script src='/js/fullcalendar.js'></script>
<script src='/js/lang-all.js'></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<style>
 .fc-event {
     border-radius:0px;
 }
</style>

<section class="content">
    <div class="row" >
        
    </div>
    <br>
    @if(!empty($ret_info))
        <table  border="1" width="100%" align="center" cellpadding="10" >
            <caption style="font-size:20px;color:#33333">教师课程质量反馈报告</caption>      
        <tr>
            <td>有无课件</td>
            <td>
                {{$ret_info["courseware_flag"]}}
            </td>
            <td>
                @if($ret_info["courseware_flag_score"] < 3)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["courseware_flag_score"]}}</span>/<span style="color:#333333">5</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["courseware_flag_score"]}}</span>/<span style="color:#333333">5</span></span>
                @endif
                    
            </td>
        </tr>
        <tr>
            <td>备课内容与试听需求匹配度</td>
            <td>
                {{$ret_info["lesson_preparation_content"]}}
            </td>
            <td>
                @if($ret_info["lesson_preparation_content_score"] < 5)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["lesson_preparation_content_score"]}}</span>/<span style="color:#333333">10</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["lesson_preparation_content_score"]}}</span>/<span style="color:#333333">10</span></span>
                @endif
            </td>
        </tr>
        <tr>
            <td>课件质量</td>
            <td>
                {{$ret_info["courseware_quality"]}}
            </td>
            <td>
                @if($ret_info["courseware_quality_score"] < 5)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["courseware_quality_score"]}}</span>/<span style="color:#333333">10</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["courseware_quality_score"]}}</span>/<span style="color:#333333">10</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>教学过程设计</td>
            <td>
                {{$ret_info["tea_process_design"]}}
            </td>
            <td>
                @if($ret_info["tea_process_design_score"] < 5)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["tea_process_design_score"]}}</span>/<span style="color:#333333">10</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["tea_process_design_score"]}}</span>/<span style="color:#333333">10</span></span>
                @endif
            </td>
        </tr>
        <tr>
            <td>课堂氛围</td>
            <td>
                {{$ret_info["class_atm"]}}
            </td>
            <td>
                @if($ret_info["class_atm_score"] < 5)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["class_atm_score"]}}</span>/<span style="color:#333333">10</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["class_atm_score"]}}</span>/<span style="color:#333333">10</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>板书书写</td>
            <td>
                {{$ret_info["teacher_blackboard_writing"]}}
            </td>
            <td>
                @if($ret_info["teacher_blackboard_writing_score"] < 5)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["teacher_blackboard_writing_score"]}}</span>/<span style="color:#333333">10</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["teacher_blackboard_writing_score"]}}</span>/<span style="color:#333333">10</span></span>
                @endif
            </td>
        </tr>
        <tr>
            <td>课程节奏</td>
            <td>
                {{$ret_info["tea_rhythm"]}}
            </td>
            <td>
                @if($ret_info["tea_rhythm_score"] < 3)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["tea_rhythm_score"]}}</span>/<span style="color:#333333">5</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["tea_rhythm_score"]}}</span>/<span style="color:#333333">5</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>讲题方法思路</td>
            <td>
                {{$ret_info["tea_method"]}}
            </td>
            <td>
                @if($ret_info["tea_method_score"] < 3)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["tea_method_score"]}}</span>/<span style="color:#333333">5</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["tea_method_score"]}}</span>/<span style="color:#333333"></span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>知识点讲解</td>
            <td>
                {{$ret_info["knw_point"]}}
            </td>
            <td>
                @if($ret_info["knw_point_score"] < 3)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["knw_point_score"]}}</span>/<span style="color:#333333">5</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["knw_point_score"]}}</span>/<span style="color:#333333">5</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>重难点把握</td>
            <td>
                {{$ret_info["dif_point"]}}
            </td>
            <td>
                @if($ret_info["dif_point_score"] < 3)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["dif_point_score"]}}</span>/<span style="color:#333333">5</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["dif_point_score"]}}</span>/<span style="color:#333333">5</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>课本内容熟悉程度</td>
            <td>
                {{$ret_info["content_fam_degree"]}}
            </td>
            <td>
                @if($ret_info["content_fam_degree"] < 3)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["content_fam_degree_score"]}}</span>/<span style="color:#333333">5</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["content_fam_degree_score"]}}</span>/<span style="color:#333333">5</span></span>
                @endif
            </td>
        </tr>
        <tr>
            <td>题目解答</td>
            <td>
                {{$ret_info["answer_question_cre"]}}
            </td>
            <td>
                @if($ret_info["answer_question_cre_score"] < 3)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["answer_question_cre_score"]}}</span>/<span style="color:#333333">5</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["answer_question_cre_score"]}}</span>/<span style="color:#333333">5</span></span>
                @endif
            </td>
        </tr>
        <tr>
            <td>语言表达和组织能力</td>
            <td>
                {{$ret_info["language_performance"]}}
            </td>
            <td>
                @if($ret_info["language_performance_score"] < 5)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["language_performance_score"]}}</span>/<span style="color:#333333">10</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["language_performance_score"]}}</span>/<span style="color:#333333">10</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>教学态度</td>
            <td>
               {{$ret_info["tea_attitude"]}}
            </td>
            <td>
                @if($ret_info["tea_attitude_score"] < 13)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["tea_attitude_score"]}}</span>/<span style="color:#333333">25</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["tea_attitude_score"]}}</span>/<span style="color:#333333">25</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>教学专注度</td>
            <td>
                {{$ret_info["tea_concentration"]}}
            </td>
            <td>
                @if($ret_info["tea_concentration_score"] < 13)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["tea_concentration_score"]}}</span>/<span style="color:#333333">25</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["tea_concentration_score"]}}</span>/<span style="color:#333333">25</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>教学事故</td>
            <td>
                {{$ret_info["tea_accident"]}}
            </td>
            <td>
                @if($ret_info["tea_accident_score"] < 25)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["tea_accident_score"]}}</span>/<span style="color:#333333">50</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["tea_accident_score"]}}</span>/<span style="color:#333333">50</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>软件操作</td>
            <td>
                {{$ret_info["tea_operation"]}}
            </td>
            <td>
                @if($ret_info["tea_operation_score"] < 25)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["tea_operation_score"]}}</span>/<span style="color:#333333">50</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["tea_operation_score"]}}</span>/<span style="color:#333333">50</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>课程异常情况处理</td>
            <td>
                {{$ret_info["class_abnormality"]}}
            </td>
            <td>
                @if($ret_info["class_abnormality_score"] < 15)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["class_abnormality_score"]}}</span>/<span style="color:#333333">30</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["class_abnormality_score"]}}</span>/<span style="color:#333333">30</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>周边环境</td>
            <td>
                {{$ret_info["tea_environment"]}}
            </td>
            <td>
                @if($ret_info["tea_environment_score"] < 10)
                    <span >得分:<span style="color:#FF3451">{{$ret_info["tea_environment_score"]}}</span>/<span style="color:#333333">20</span></span>
                @else
                    <span >得分:<span style="color:#F6A623">{{$ret_info["tea_environment_score"]}}</span>/<span style="color:#333333">20</span></span>
                @endif

            </td>
        </tr>
        <tr>
            <td>总得分</td>
            <td colspan="2">{{$ret_info["record_score"]}}</td>
        </tr>
        <tr>
            <td>等级</td>
            <td colspan="2">{{$ret_info["record_rank"]}}</td>
        </tr>
        <tr>
            <td>监课情况</td>
            <td  colspan="2">{{$ret_info["record_monitor_class"]}}</td>
        </tr>
        <tr>
            <td>意见或建议</td>
            <td colspan="2">{{$ret_info["record_info"]}}</td>
        </tr>
    </table>
    @endif
  </section>
@endsection

