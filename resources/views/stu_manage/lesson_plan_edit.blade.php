@extends('layouts.stu_header')
@section('content')
<link rel='stylesheet' href='/css/fullcalendar.css' />
<script src='/js/moment.js'></script>
<script src='/page_js/select_teacher_free_time.js'></script>
<script src='/page_js/set_lesson_time.js'></script>
<script src='/page_js/select_user.js'></script>
<script src='/js/fullcalendar.js'></script>
<script src='/js/lang-all.js'></script>
@if($_account_role!=2)
<section class="content">
    <div class="row">
        <div class="col-xs-12 col-md-5">
            <div class="input-group ">
                <span class="input-group-addon">课程包</span>
                <select class="input-change form-control" id="id_courseid" >
                    @foreach  ($course_list as $var)
                        <option value="{{$var["courseid"]}}"> {{$var["title"]}}  </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">显示全部</span>
                <select class="input-change form-control" id="id_all_flag" >
                    <option value="0"> 否 </option>
                    <option value="1"> 是</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-1">
        </div>
        <div class="col-xs-6 col-md-1">
            <div class="input-group ">
                <a class="btn btn-primary" id="id_add_lesson" style="display:none;" > 增加课次 </a>
            </div>
        </div>

        <div class="col-xs-6 col-md-1">
            <div class="input-group ">
                <a class="btn btn-primary" id="id_change_teacher_subject" style="display:none;" > 批量修改老师科目 </a>
            </div>
        </div>

    </div>
    <hr/>
    <table class="common-table ">
        <thead>
            <tr>
                <td >lessonid</td>
                <td >老师</td>
                <td >工资分类</td>
                <td >等级</td>
                <td >年级</td>
                <td >科目</td>
                <td >状态</td>
                <td >开始</td>
                <td >结束</td>
                <td >课次</td>
                <td >课时数</td>
                <td >课时确认</td>
                <td style="display:none;">课时确认人</td>
                <td style="display:none;">课时确认时间</td>
                <td style="display:none;">课时确认原因</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td > {{$var["lessonid"]}} </td>
                    <td > {{$var["teacher_nick"]}} </td>
                    <td > {{$var["teacher_money_type_str"]}} </td>
                    <td > {{$var["level_str"]}} </td>
                    <td > {{$var["grade_str"]}} </td>
                    <td > {{$var["subject_str"]}} </td>
                    <td > {{$var["lesson_status_str"]}} </td>
                    <td > {{$var["lesson_start_str"]}} </td>
                    <td > {{$var["lesson_end_str"]}} </td>
                    <td > {{$var["lesson_num"]}} </td>
                    <td > {{$var["lesson_count"]}} </td>
                    <td > {{$var["confirm_flag_str"]}} </td>
                    <td > {{$var["confirm_admin_nick"]}} </td>
                    <td > {{$var["confirm_time_str"]}} </td>
                    <td > {{$var["confirm_reason"]}} </td>
                    <td  >
                        <div
                            data-lessonid="{{$var["lessonid"]}}"
                            data-courseid="{{$var["courseid"]}}"
                            data-teacherid="{{$var["teacherid"]}} " 
                            data-lesson_start="{{$var["lesson_start"]}} " 
                            data-lesson_status="{{$var["lesson_status"]}} " 
                            class="opt-div"
                        >
                            <a class="start_edit_lesson_time" href="javascript:;" title="" style="display:none;">按空闲时段排课</a>
                            <a class="change_time" title="" style="display:none;">自定义时间排课</a>
                            <a class="opt_change_teacher" title="" style="display:none;"> 修改老师  </a>
                            <a class="opt_change_lesson_count" title="" style="display:none;" > 修改课时数</a>
                            <a class="cancel_lesson  fa-trash-o" title="取消课程"  style="display:none;"> </a>
                            <a class="fa-edit opt-edit"  title="编辑信息" style="display:none;" > </a>
                            <a href="/seller_student/test_lesson_list?st_arrange_lessonid={{$var["lessonid"]}}" style="display:none;"> 试听信息 </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endif
@endsection
