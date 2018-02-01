@extends('layouts.stu_header')
@section('content')
<link rel='stylesheet' href='/css/fullcalendar.css' />
<script src='/js/moment.js'></script>
<script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
<script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>
<script src='/page_js/select_user.js?{{@$_publish_version}}'></script>
<script src='/js/fullcalendar.js'></script>
<script src='/js/lang-all.js'></script>

<section class="content">
    <div class="row">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">显示全部</span>
                <select class=" opt-change form-control" id="id_all_flag" >
                    <option value="0"> 否 </option>
                    <option value="1"> 是</option>
                </select>
            </div>
        </div>
       
        <div class="col-xs-6 col-md-1">
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <a class="btn btn-primary" id="id_add_lesson">增加课程</a>
                <a class="btn btn-danger" id="id_cancel_lesson">批量删除课程</a>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table ">
        <thead>
            <tr>
                <td style="width:10px">
                    <a href="javascript:;" id="id_select_all" title="全选">全</a>
                    <a href="javascript:;" id="id_select_other" title="反选">反</a>
                </td>
                <td >课程id</td>
                <td >老师</td>
                <td style="display:none;">工资分类</td>
                <td style="display:none;">等级</td>
                <td >年级</td>
                <td >科目</td>
                <td >状态</td>
                <td >开始</td>
                <td >结束</td>
                <td >课次</td>
                <td >课时数</td>
                <td >课时确认</td>
                <td >课时取消原因</td>
                <td style="display:none;">课时确认人</td>
                <td style="display:none;">课时确认时间</td>
                <td style="display:none;">课时确认原因</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td>
                        <input type="checkbox" class="opt-select-item" data-lessonid="{{$var["lessonid"]}}" data-lesson_status="{{$var["lesson_status"]}}"/>
                    </td>
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
                    <td > {{$var["lesson_cancel_reason_type_str"]}} </td>
                    <td > {{$var["confirm_admin_nick"]}} </td>
                    <td > {{$var["confirm_time_str"]}} </td>
                    <td > {{$var["confirm_reason"]}} </td>
                    <td  >
                        <div {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} class="opt-div">
                            <a class="change_time">自定义时间排课</a>
                            <a class="opt_change_lesson_count"> 修改课时</a>
                            <a class="cancel_lesson  fa-trash-o" title="取消课程"></a>
                            <!-- <a href="/seller_student/test_lesson_list?st_arrange_lessonid={{$var["lessonid"]}}">试听信息</a> -->
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection

