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
    <br>
    @if(!empty($ret_info))
        <table  border="1" width="100%" align="center" cellpadding="10" >
            <caption style="font-size:20px;color:#33333">{{$ret_info["title"]}}</caption>      
            <tr>
                <td rowspan="4">教学相关</td>
                <td>
                    讲义设计情况
                </td>
                <td>
                    @if(@$ret_info["tea_process_design_score"] < 5)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["tea_process_design_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @else
                        <span >得分:<span style="color:#F6A623">{{@$ret_info["tea_process_design_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    语言表达能力
                </td>
                <td>
                    @if(@$ret_info["language_performance_score"] < 5)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["language_performance_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @else
                        <span >得分:<span style="color:#F6A623">{{@$ret_info["language_performance_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>专业知识技能</td>
                <td>
                    @if(@$ret_info["knw_point_score"] < 5)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["knw_point_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @else
                        <span >得分:<span style="color:#F6A623">{{@$ret_info["knw_point_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    教学节奏把握
                </td>
                <td>
                    @if(@$ret_info["tea_rhythm_score"] < 5)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["tea_rhythm_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @else

                        <span >得分:<span style="color:#F6A623">{{@$ret_info["tea_rhythm_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @endif
                </td>
            </tr>
            <tr>
                <td rowspan="6">非教学相关</td>
                <td>
                    互动情况
                </td>
                <td>
                    @if(@$ret_info["tea_concentration_score"] < 5)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["tea_concentration_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @else

                        <span >得分:<span style="color:#F6A623">{{@$ret_info["tea_concentration_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @endif
                </td>

            </tr>
            <tr>
                <td>板书情况</td>
                <td>
                    @if(@$ret_info["teacher_blackboard_writing_score"] < 5)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["teacher_blackboard_writing_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @else

                        <span >得分:<span style="color:#F6A623">{{@$ret_info["teacher_blackboard_writing_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>软件操作</td>
                <td>
                    @if(@$ret_info["tea_operation_score"] < 5)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["tea_operation_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @else

                        <span >得分:<span style="color:#F6A623">{{@$ret_info["tea_operation_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>授课环境</td>
                <td>
                    @if(@$ret_info["tea_environment_score"] < 3)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["tea_environment_score"]}}</span>/<span style="color:#333333">5</span></span>
                    @else

                        <span >得分:<span style="color:#F6A623">{{@$ret_info["tea_environment_score"]}}</span>/<span style="color:#333333">5</span></span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>课后反馈</td>
                <td>
                    @if(@$ret_info["answer_question_cre_score"] < 5)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["answer_question_cre_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @else

                        <span >得分:<span style="color:#F6A623">{{@$ret_info["answer_question_cre_score"]}}</span>/<span style="color:#333333">10</span></span>
                    @endif

                </td>
            </tr>
            <tr>
                <td>流程规范操作</td>
                <td>
                    @if(@$ret_info["class_abnormality_score"] < 8)
                        <span >得分:<span style="color:#FF3451">{{@$ret_info["class_abnormality_score"]}}</span>/<span style="color:#333333">15</span></span>
                    @else

                        <span >得分:<span style="color:#F6A623">{{@$ret_info["class_abnormality_score"]}}</span>/<span style="color:#333333">15</span></span>
                    @endif


                </td>
            </tr>
            
            
            
            <tr>
                <td>总得分</td>
                <td colspan="2">{{@$ret_info["record_score"]}}</td>
            </tr>       
            <tr>
                <td>监课情况</td>
                <td  colspan="2">{{@$ret_info["record_monitor_class"]}}</td>
            </tr>
            <tr>
                <td>意见或建议</td>
                <td colspan="2">{{@$ret_info["record_info"]}}</td>
            </tr>
        </table>
    @endif
   
  </section>
@endsection

