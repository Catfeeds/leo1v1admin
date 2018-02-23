@extends('layouts.app')
@section('content')
<script src='/js/moment.js'></script>
<link rel='stylesheet' href='/css/fullcalendar.css' />
<script src='/js/fullcalendar.js'></script>
<script src='/js/lang-all.js'></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script type="text/javascript" > 
 var g_positive_id= "{{@$positive_info["id"]}}";
 var positive_type_old= "{{@$positive_type_old}}";
 var check_is_late= "{{@$check_is_late}}";
 var g_order_per= "{{@$account_info["order_per"]}}";
 var g_stu_num= "{{@$account_info["stu_num"]}}";
 var g_stu_lesson_total = "{{@$account_info["lesson_count_avg"]}}";
</script>

<style>
 .fc-event {
     border-radius:0px;
 }

 td input,td select{
     border:none;
     width:100%;
     height:100%;
 }
</style>
<section class="content">
    <br>
    <div class="col-xs-6 col-md-2">
        <button class="btn btn-primary" id="id_assessment_positive_info" 
                @if(empty($positive_info))
                style="display:none"
                @endif
        >查看转正申请详情</button>
    </div>                
    <div class="col-xs-6 col-md-2">
        <div class="input-group ">
            <span class="input-group-addon">全职老师</span>
            <input class="opt-change form-control" id="id_fulltime_adminid" />
        </div>
    </div>



    <table  border="1" width="100%" align="center" cellpadding="10" >
        <caption style="font-size:20px;color:#33333">全职教师转正考核表</caption>      
        <tr>
            <td id="name">姓名:</td>
            <td width="400">{{@$account_info["name"]}}</td>
            <td width="100" >部门:</td>
            <td >{{@$account_info["main_department_str"]}}</td>
            <td>职位:</td>
            <td>{{@$account_info["post_str"]}}</td>
        </tr>
        <tr>
            <td>内容</td>
            <td>考核标准</td>
            <td>分值</td>
            <td>自评</td>
            <td>自评总分</td>
            <td>组长审定</td>
        </tr>
        <tr>
            <td rowspan="5">德育(10分)</td>
            <td>遵纪守法,严格遵守公司各项规章制度,上班不迟到、不早退,无请假</td>
            <td>2</td>
            <td id="observe_law_score">
                <select  class="moral_education_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["observe_law_score"]==2)
                            selected
                    @else
                            ""
                    @endif  >从不&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["observe_law_score"]==1)                               
                                selected
                        @else
                                ""
                        @endif >偶尔&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0"  @if (!empty($ret_info) && @$ret_info["observe_law_score"]==0)                               
                                    selected
                            @else
                                    ""
                            @endif >经常&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
            <td rowspan="5" id="moral_education_score">{{@$ret_info["moral_education_score"]}}</td>
            <td rowspan="5">
                @if(!empty($ret_info) && !empty($ret_info["moral_education_score_master"]))
                    {{@$ret_info["moral_education_score_master"]}}
                @endif
            </td>
        </tr>
        <tr>
            <td>高度认同公司经营理念、企业文化和核心价值观</td>
            <td>2</td>
            <td id="core_socialist_score" >
                <select  class="moral_education_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["core_socialist_score"]==2)
                            selected
                    @else
                            ""
                    @endif  >是&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["core_socialist_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["core_socialist_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >否&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>

        </tr>
        <tr>
            <td>勇于承担工作责任,不发表消极言论,积极维护企业形象</td>
            <td>2</td>
            <td id="work_responsibility_score">
                <select  class="moral_education_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["work_responsibility_score"]==2)
                            selected
                    @else
                            ""
                    @endif  >是&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["work_responsibility_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["work_responsibility_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif  >否&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>服从上级领导的安排,积极融入团队合作</td>
            <td>2</td>
            <td id="obey_leadership_score">
                <select  class="moral_education_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["obey_leadership_score"]==2)
                            selected
                    @else
                            ""
                    @endif >是&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1"  @if (!empty($ret_info) && @$ret_info["obey_leadership_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["obey_leadership_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >否&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>有高尚的师德,爱岗敬业</td>
            <td>2</td>
            <td id="dedication_score">
                <select  class="moral_education_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2"  @if (!empty($ret_info) && @$ret_info["dedication_score"]==2)
                            selected
                    @else
                            ""
                    @endif >是&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["dedication_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["dedication_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >否&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td rowspan="10">教学(22分)</td>
            <td>认真准备好每次课的教师讲义和学生讲义,并备好本节课学生作业</td>
            <td>2</td>
            <td id="prepare_lesson_score" >
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2"  @if (!empty($ret_info) && @$ret_info["prepare_lesson_score"]==2)
                            selected
                    @else
                            ""
                    @endif >较好&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1"  @if (!empty($ret_info) && @$ret_info["prepare_lesson_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["prepare_lesson_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >从不&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
            <td rowspan="10" id="tea_score">{{@$ret_info["tea_score"]}}</td>
            <td rowspan="10">
                @if(!empty($ret_info) && !empty($ret_info["tea_score_master"]))
                    {{@$ret_info["tea_score_master"]}}
                @endif
            </td>
        </tr>
        <tr>
            <td>在上课前4小时备好本节课讲义和作业并上传至上课平台</td>
            <td>2</td>
            <td id="upload_handouts_score">
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["upload_handouts_score"]==2)
                            selected
                    @else
                            ""
                    @endif >较好&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["upload_handouts_score"]==1)
                                selected
                        @else
                                ""
                        @endif>一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["upload_handouts_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >从不&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>

        </tr>
        <tr>
            <td>讲义编写需做到:备学生、备教材、备课堂</td>
            <td>2</td>
            <td id="handout_writing_score">
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["handout_writing_score"]==2)
                            selected
                    @else
                            ""
                    @endif >较好&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["handout_writing_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["handout_writing_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >较差&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>严格按照课程表上课,不私自调、停课</td>
            <td>2</td>
            <td id="no_absences_score" >
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["no_absences_score"]==2)
                            selected
                    @else
                            ""
                    @endif >较好&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["no_absences_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["no_absences_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >较差&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>按时上下课,不迟到、不早退</td>
            <td>2</td>
            <td id="late_leave_score">
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["late_leave_score"]==2)
                            selected
                    @else
                            ""
                    @endif >从不&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["late_leave_score"]==1)
                                selected
                        @else
                                ""
                        @endif >偶尔&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["late_leave_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >经常&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>精心上好每节课,重点突出,生动形象,有吸引力</td>
            <td>2</td>
            <td id="prepare_quality_score">
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["prepare_quality_score"]==2)
                            selected
                    @else
                            ""
                    @endif >较好&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["prepare_quality_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["prepare_quality_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >较差&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>

                </select>
            </td>
        </tr>
        <tr>
            <td>上课途中手机调静音,不打接电话,不私自离岗</td>
            <td>2</td>
            <td id="class_concent_score">
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["class_concent_score"]==2)
                            selected
                    @else
                            ""
                    @endif >从不&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["class_concent_score"]==1)
                                selected
                        @else
                                ""
                        @endif >偶尔&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["class_concent_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >经常&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>适时鼓励学生,无贬低、辱骂、抱怨学生现象</td>
            <td>4</td>
            <td id="tea_attitude_score">
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="4" @if (!empty($ret_info) && @$ret_info["tea_attitude_score"]==4)
                            selected
                    @else
                            ""
                    @endif >从不&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp4分</option>
                        <option value="3" @if (!empty($ret_info) && @$ret_info["tea_attitude_score"]==3)
                                selected
                        @else
                                ""
                        @endif >很少&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp3分</option>
                            <option value="2" @if (!empty($ret_info) && @$ret_info["tea_attitude_score"]==2)
                                    selected
                            @else
                                    ""
                            @endif >偶尔&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                                <option value="1" @if (!empty($ret_info) && @$ret_info["tea_attitude_score"]==1)
                                        selected
                                @else
                                        ""
                                @endif >经常&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                                    <option value="0" @if (!empty($ret_info) && @$ret_info["tea_attitude_score"]==0)
                                            selected
                                    @else
                                            ""
                                    @endif >一直&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>课后及时对本课堂学生表现及教学效果进行评价和反馈</td>
            <td>2</td>
            <td id="after_feedback_score">
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["after_feedback_score"]==2)
                            selected
                    @else
                            ""
                    @endif >是&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["after_feedback_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["after_feedback_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >否&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>学生提交的作业及时进行讲评和订正</td>
            <td>2</td>
            <td id="modify_homework_score">
                <select class="tea_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["modify_homework_score"]==2)
                            selected
                    @else
                            ""
                    @endif >是&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["modify_homework_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["modify_homework_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >否&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td rowspan="5">教研(13分)</td>
            <td>积极配合排课老师上好所接学生的试听课</td>
            <td>2</td>
            <td id="teamwork_positive_score">
                <select class="teach_research_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["teamwork_positive_score"]==2)
                            selected
                    @else
                            ""
                    @endif >较好&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["teamwork_positive_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["teamwork_positive_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >较差&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
            <td rowspan="5" id="teach_research_score">{{@$ret_info["teach_research_score"]}}</td>
            <td rowspan="5">
                @if(!empty($ret_info) && !empty($ret_info["teach_research_score_master"]))
                    {{@$ret_info["teach_research_score_master"]}}
                @endif
            </td>
        </tr>
        <tr>
            <td>每接一个试听课,都积极准备</td>
            <td>2</td>
            <td id="test_lesson_prepare_score">
                <select class="teach_research_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["test_lesson_prepare_score"]==2)
                            selected
                    @else
                            ""
                    @endif >是&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["test_lesson_prepare_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["test_lesson_prepare_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >否&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>            
        </tr>
        <tr>
            <td>积极承担教学组长布置任务,准备充分,效果好</td>
            <td>2</td>
            <td id="undertake_actively_score">
                <select class="teach_research_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["undertake_actively_score"]==2)
                            selected
                    @else
                            ""
                    @endif >是&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["undertake_actively_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["undertake_actively_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >否&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>积极参与教学经验交流、分享活动,积极发挥以老带新作用</td>
            <td>2</td>
            <td id="active_part_score">
                <select class="teach_research_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="2" @if (!empty($ret_info) && @$ret_info["active_part_score"]==2)
                            selected
                    @else
                            ""
                    @endif >是&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                        <option value="1" @if (!empty($ret_info) && @$ret_info["active_part_score"]==1)
                                selected
                        @else
                                ""
                        @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                            <option value="0" @if (!empty($ret_info) && @$ret_info["active_part_score"]==0)
                                    selected
                            @else
                                    ""
                            @endif >否&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>

                </select>
            </td>
        </tr>
        <tr>
            <td>积极主动分享高质量教学讲义、教学视频</td>
            <td>5</td>
            <td id="active_share_score">
                <select class="teach_research_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="1" @if (!empty($ret_info) && @$ret_info["active_share_score"]==1)
                            selected
                    @else
                            ""
                    @endif >1-2份&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp1分</option>
                        <option value="2" @if (!empty($ret_info) && @$ret_info["active_share_score"]==2)
                                selected
                        @else
                                ""
                        @endif >3-4份 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                            <option value="3" @if (!empty($ret_info) && @$ret_info["active_share_score"]==3)
                                    selected
                            @else
                                    ""
                            @endif >5-6份&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp3分</option>
                                <option value="4" @if (!empty($ret_info) && @$ret_info["active_share_score"]==4)
                                        selected
                                @else
                                        ""
                                @endif >7-8份&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp4分</option>
                                    <option value="5" @if (!empty($ret_info) && @$ret_info["active_share_score"]==5)
                                            selected
                                    @else
                                            ""
                                    @endif >9-10份&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp5分</option>


                </select>

            </td>
        </tr>
        <tr>
            <td rowspan="4">成果(55分)</td>
            <td>试用期转化率绩效:{{$account_info["order_per"]}}分</td>
            <td>25</td>
            <td id="order_per_score">{{$account_info["order_per_score"]}}</td>
            <td rowspan="4" id="result_score">
                @if(!empty($ret_info))
                    {{@$ret_info["result_score_new"]}}
                @else
                    {{$account_info["result_score"]}}
                @endif
            </td>
            <td rowspan="4">
                @if(!empty($ret_info) && !empty($ret_info["result_score_master"]))
                    {{@$ret_info["result_score_master"]}}
                @endif
            </td>

        </tr>
        <tr>
            <td>家长评价</td>
            <td>5</td>
            <td id="lesson_level_score">
                <select class="complaint_refund_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="5" @if (!empty($ret_info) && @$ret_info["lesson_level_score"]==5)
                            selected
                    @else
                            ""
                    @endif >非常好&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp5分</option>
                        <option value="4" @if (!empty($ret_info) && @$ret_info["lesson_level_score"]==4)
                                selected
                        @else
                                ""
                        @endif >较好&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp4分</option>
                            <option value="3" @if (!empty($ret_info) && @$ret_info["lesson_level_score"]==3)
                                    selected
                            @else
                                    ""
                            @endif >一般&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp3分</option>
                                <option value="2" @if (!empty($ret_info) && @$ret_info["lesson_level_score"]==2)
                                        selected
                                @else
                                        ""
                                @endif >较差&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp2分</option>
                                    <option value="0" @if (!empty($ret_info) && @$ret_info["lesson_level_score"]==0)
                                            selected
                                    @else
                                            ""
                                    @endif >极差&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp0分</option>


                </select>

            </td>
        </tr>
        <tr>
            <td>试用期内月平均课时消耗:{{$account_info["lesson_count_avg"]}}课时</td>
            <td>20</td>
            <td id="lesson_count_avg_score">{{$account_info["lesson_count_avg_score"]}}</td>

        </tr>
       
        <tr>
            <td>家长投诉和退费</td>
            <td>5</td>
            <td id="complaint_refund_score">
                <select class="complaint_refund_score_flag total_score_flag">
                    <option value="0">请选择</option>
                    <option value="5" @if (!empty($ret_info) && @$ret_info["complaint_refund_score"]==5)
                            selected
                    @else
                            ""
                    @endif >5分</option>
                        <option value="4" @if (!empty($ret_info) && @$ret_info["complaint_refund_score"]==4)
                                selected
                        @else
                                ""
                        @endif >4分</option>
                            <option value="3" @if (!empty($ret_info) && @$ret_info["complaint_refund_score"]==3)
                                    selected
                            @else
                                    ""
                            @endif >3分</option>
                                <option value="2" @if (!empty($ret_info) && @$ret_info["complaint_refund_score"]==2)
                                        selected
                                @else
                                        ""
                                @endif >2分</option>
                                    <option value="1" @if (!empty($ret_info) && @$ret_info["complaint_refund_score"]==1)
                                            selected
                                    @else
                                            ""
                                    @endif >1分</option>
                                        <option value="0" @if (!empty($ret_info) && @$ret_info["complaint_refund_score"]==0)
                                                selected
                                        @else
                                                ""
                                        @endif >0分</option>


                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">评定总分</td>
            <td>100</td>
            <td colspan="2" id ="total_score" >
                @if(!empty($ret_info))
                    {{@$ret_info["total_score_new"]}}
                @endif

            </td>
            <td >
                @if(!empty($ret_info) && !empty($ret_info["total_score_master"]))
                    {{@$ret_info["total_score_master"]}}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2">评定星级</td>
            <td>5星</td>
            <td colspan="2" id ="rate_stars" >
                @if(!empty($ret_info))
                    {{@$ret_info["rate_stars_new"]}}星
                @endif
            </td>
            <td >
                @if(!empty($ret_info) && !empty($ret_info["rate_stars_master"]))
                    {{@$ret_info["rate_stars_master"]}}星
                @endif
            </td>
        </tr>
        <tr>
            <td >考评人</td>
            <td >
                @if(!empty($ret_info) && !empty($ret_info["assess_adminid"]))
                    {{@$ret_info["assess_admin_nick"]}}
                @endif
            </td>
            <td colspan="2">考评日期</td>
            <td colspan="2">
                @if(!empty($ret_info) && !empty($ret_info["assess_time"]))
                    {{@$ret_info["assess_time_str"]}}
                @endif
            </td>
        </tr>
    </table>
    @if(empty($positive_info) || (@$positive_info["master_deal_flag"]==2 || @$positive_info["main_master_deal_flag"]==2) || $check_is_late==1)
        <div class="row" >
            <div class="col-xs-12 col-md-12" style="text-align:center;  margin-top:20px">
                <button  class="btn btn-primary btn-lg " id="id_save" data-type="save">提交</button>
            </div>
        </div>
    @endif
</section>
@endsection

