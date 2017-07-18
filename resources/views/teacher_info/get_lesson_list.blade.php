@extends('layouts.app')
@section('content')

<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<style>
 .ml30 a{
     margin-left:15px;
 }
 .input-group{
     width:100%;
 }
 .input-group-w145{
     width:145px !important;
 }
</style>
<section class="content">

    <div class="row">
        <div class="col-xs-6 col-md-3">
            <div class="input-group ">
                <span class="input-group-addon">班级类型</span>
                <select class="opt-change form-control" id="id_lesson_type" >
                    <option value="-1">全部 </option>
                    <option value="0">1对1 </option>
                    <option value="1001">公开课 </option>
                    <option value="3001">小班课</option>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-md-4">
            <div class="input-group ">
                <span >日期</span>
                <input type="text" id="id_start_date" class="opt-change"/>
                <span >-</span>
                <input type="text" id="id_end_date" class="opt-change"/>
            </div>
        </div>
    </div>
    <hr/> 
    <table class="common-table"   >
        <thead>
            <tr>
                <td style="width:50px;display:none">课堂数</td>
                <td style="width:50px;">课程类型</td>
                <td style="width:100px">班名</td>
                <td style="width:100px">课程时间</td>
                <td style="min-width:100px">课程标题</td>
                <td style="min-width:100px">知识点</td>
                <td style="min-width:100px">教材版本</td>
                <td style="min-width:500px">操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $key =>$var)
                <tr>
                    <td >{{$var["lesson_num_str"]}}</td>
                    <td >{{$var["lesson_type_str"]}}</td>
                    <td >{{$var["lesson_course_name"]}}</td>
                    <td >{{$var["lesson_time"]}}</td>
                    <td >{{$var["lesson_name"]}}</td>
                    <td >{{$var["lesson_intro"]}}</td>
                    <td >{{$var["textbook"]}}</td>
                    <td >
                        <div class="lesson_data ml30"
                             data-lesson_status="{{$var["lesson_status"]}}" data-lessonid="{{$var["lessonid"]}}"
                             data-grade="{{$var["grade"]}}" data-lesson_type="{{$var["lesson_type"]}}"
                             data-subject="{{$var["subject"]}}" data-homework_status="{{$var["homework_status"]}}"
                             data-tea_status="{{$var["tea_status"]}}" data-stu_status="{{$var["stu_status"]}}"
                             data-finishurl="{{$var["finish_url"]}}" data-checkurl="{{$var["check_url"]}}"
                             data-ass_comment_audit="{{$var["ass_comment_audit"]}}"
                        >
                            <a href="javascript:;" class="opt-up-homework" title="作业PDF">上传作业</a>
                            <a href="javascript:;" class="opt-up-handout_tea" title="老师讲义PDF">上传老师讲义</a>
                            <a href="javascript:;" class="opt-up-handout_stu" title="学生讲义PDF">上传学生讲义</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
	<include: file="../al_common/page.html"/>
	<!-- <include: file="../al_common/return_record_add.html"/> -->

    <div class="dlg_upload_homework_info" style="display:none">
        <div class="row">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" 
                     aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    <span class="sr-only">40% 完成</span>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:5px;">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>作业</td>
                    <td>PDF</td>
                </tr>
                <tr class="homework_info">
                    <td>
                        <input class="btn btn-primary opt-up-homework_list" value="选择题库" type="button"/>
                    </td>
                    <td class="pdfurl homework_url" data-pdfurl="" data-finishurl="" data-checkurl="" data-homework_status="">
                        <a href="javascript:;" class="btn btn-danger opt-up opt-homework-url">上传</a>
                        <a href="javascript:;" class="btn btn-danger opt-up-server opt-server-homework-url">中转上传</a>
                        <a href="javascript:;" class="btn btn-danger homework_cw homework_preview">上传预览</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="dlg_upload_homework_ex" style="display:none">
        <div class="row">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">本次作业题目数</span>
                            <input class="pdf_question_count" type="text" >
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="dlg_upload_handout_tea_info" style="display:none">
        <div class="row">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" 
                     aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    <span class="sr-only">40% 完成</span>
                </div>
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>讲义</td>
                    <td>老师PDF</td>
                </tr>
                <tr class="homework_info">
                    <td >
                        <input class="btn btn-primary opt-up-handout_list" value="选择题库" type="button"/>
                    </td>
                    <td class="pdfurl tea_cw_url" data-pdfurl="" data-tea_status="">
                        <a href="javascript:;" class="btn btn-danger opt-up opt-teacher-url">上传</a>
                        <a href="javascript:;" class="btn btn-danger opt-up-server opt-teacher-url-server">中转上传</a>
                        <a href="javascript:;" class="btn btn-danger tea_cw courseware_preview">预览</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="dlg_upload_handout_stu_info" style="display:none">
        <div class="row">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" 
                     aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    <span class="sr-only">40% 完成</span>
                </div>
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>学生PDF</td>
                </tr>
                <tr class="homework_info">
                    <td class="pdfurl stu_cw_url" data-pdfurl="" data-stu_status="">
                        <a href="javascript:;" class="btn btn-danger opt-up opt-student-url">上传</a>
                        <a href="javascript:;" class="btn btn-danger opt-up-server opt-student-url-server">中转上传</a>
                        <a href="javascript:;" class="btn btn-danger stu_cw courseware_preview">预览</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="dlg_upload_handout_ex" style="display:none">
        <div class="row">
            <table class="table table-bordered table-striped set_lesson_info">
                <tr>
                    <td>本次课名称</td>
                    <td>
                        <input class="lesson_name" type="text" >
                    </td>
                </tr>
                <tr class="lesson_point">
                    <td>添加课堂知识点</td>
                    <td>点击添加课堂知识点</td>
                    <td><button class="btn btn-warning fa form-control fa-plus add_lesson_point "></button></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="dlg_upload_lesson_name" style="display:none">
        <div class="row">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>
                        <div class="input-group">
                            <span class="input-group-addon">本次课名称</span>
                            <input class="lesson_name" type="text" >
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div style="display:none;" id="id_dlg_set_user_info" >
        <div class="row">
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-6  ">
                        <div class="input-group ">
                            <span class="input-group-w145" >本节课内容：</span>
                            <input type="text" class=" form-control "  id="id_stu_lesson_content"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="input-group ">
                            <span class="input-group-w145">大致推荐课时数:</span>
                            <input type="text" id="id_stu_lesson_count" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-w145">学生优点：</span>
                            <input type="text" id="id_stu_advantages"  class="form-control"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-w145">学生缺点：</span>
                            <input type="text" id="id_stu_disadvantages"  class="form-control"  />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-w145">学生课堂状态：</span>
                            <input type="text" id="id_stu_lesson_status"  class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-w145">学生吸收情况：</span>
                            <input type="text" id="id_stu_study_status"  class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span class="input-group-w145">培训计划（简述）:</span>
                            <input type="text" id="id_stu_lesson_plan" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span class="input-group-w145">教学方向:</span>
                            <input type="text" id="id_stu_teaching_direction" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span class="input-group-w145">教材及内容:</span>
                            <input type="text" id="id_stu_textbook_info" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span class="input-group-w145">教学目标:</span>
                            <input type="text" id="id_stu_teaching_aim" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-w145" >意见、建议等 <br/>（不少于50字）：</span>
                            <textarea class="form-control" style="height:150px;" id="id_stu_advice"  > </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection


