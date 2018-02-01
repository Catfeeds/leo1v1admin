@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/small_class/set_teacher_clothes.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<section class="content">
    <div class="row">
        <div class="col-md-2">
            <div class="input-group ">
                <span>老师</span>
                <input type="text" id="id_teacherid" />
            </div>
        </div>
        <div class="col-xs-6 col-md-2" >
            <div class="input-group ">
                <span >助教</span>
                <input id="id_assistantid"  />
            </div>
        </div>
        <div class="col-xs-6 col-md-4">
            <div class="input-group ">
                <span>时间</span>
                <input  type="text" id="id_date_start" />
                <span >-</span>
                <input  type="text" id="id_date_end" />
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <a id="add_small_class_course" class="btn btn-warning" > <li class="fa fa-plus">课程</li></a>
            </div>
        </div>
    </div>
    <hr/>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <td >课程id</td>
                <td >课程名称</td>
                <td >老师</td>
                <td >助教</td>
                <td >年级</td>
                <td >科目</td>
                <td >总课次</td>
                <td >剩余课次</td>
                <td >预定人数</td>
                <td >当前人数</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody >
            @foreach ($table_data_list as $var)
				        <tr>
                    @include('layouts.td_xs_opt')
					          <td>{{$var["courseid"]}}</td>
					          <td>{{$var["course_name"]}}</td>
					          <td>{{@$var["teacher_nick"]}}</td>
					          <td>{{@$var["assistant_nick"]}}</td>
					          <td>{{$var["grade_str"]}}</td>
					          <td>{{$var["subject_str"]}}</td>
					          <td>{{$var["lesson_total"]}}</td>
					          <td>{{$var["lesson_left"]}}</td>
					          <td>{{$var["stu_total"]}}</td>
					          <td>{{$var["stu_current"]}}</td>
                    <td class="remove-for-xs">
                        <div class="opt"
                             {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="btn fa fa-info td-info "></a>
                            <a title="更改老师" class="btn fa fa-male opt-alloc-teacher"></a>
                            <a title="更改助教" class="btn fa fa-user-md opt-alloc-assistant"></a>
                            <a title="学生列表" class="btn fa fa-group opt-list-student"></a>
                            <a title="上课时间" class="btn fa fa-clock-o opt-set-lesson-time" style="display:none"></a>
                            <a class="btn opt-add_student">添加学生</a>
                            <a title="合同列表" class="btn fa fa-tree opt-goto-contract" href="../user_manage/contract_list?config_courseid={{$var["courseid"]}}" ></a>
                            <a title="课次详细信息" class="btn fa  fa-list-alt opt-goto-lesson-list"
                               @if($group_flag== "assistant")
                               href="/small_class/lesson_list_new_ass?courseid={{$var["courseid"]}}"
                               @else
                               href="/small_class/lesson_list_new?courseid={{$var["courseid"]}}"
                               @endif ></a>
                                <a href="javascript:;" title="修改开课时间" class="btn fa fa-calendar opt-lesson-open"></a>
                                <a href="javascript:;" title="添加额外小班课" class="btn fa fa-plus opt-add-extra-lesson"></a>
                                <a href="javascript:;" title="删除小班课" class="btn fa fa-close opt-del-lesson-info"></a>
                        </div>
                    </td>
				        </tr>
            @endforeach
        </tbody>
    </table>
	@include("layouts.page")

<div class="dlg_add_course" style="display:none">
    <div class="row">
        <div class="input-group">
            <span class="input-group-addon"></span>
            <input class="course_name form-control" type="text" />
        </div>
    </div>
    <div class="row">
        <div class="input-group">
            <span class="input-group-addon">年级</span>
            <select class="user_grade form-control">
            </select>
        </div>
    </div>
    <div class="row">
        <div class="input-group">
            <span class="input-group-addon">科目</span>
            <select class="user_subject form-control">
            </select>
        </div>
    </div>
    <div class="row">
        <div class="input-group">
            <span class="input-group-addon">课次</span>
            <input class="lesson_total form-control" type="text" />
        </div>
    </div>

    <div class="row">
        <div class="input-group">
            <span class="input-group-addon"></span>
            <input class="stu_total form-control" type="text" />
        </div>
    </div>
</div>

<div class="dlg_update_teacher_clothes" style="display:none">
    <div class="row">
        <div class="input-group">
            <span class="input-group-addon">服饰</span>
            <select class="tea_pic">
            </select>
        </div>
    </div>
    <div class="row">
        <div class="input-group">
            <span class="input-group-addon">预览</span>
            <span class="show_tea_pic"></span>
        </div>
    </div>
</div>

<div class="dlg_set_lesson_time" style="display:none">
    <table class="table table-bordered table-striped lesson_time_tab"   >
        <tr>
            <td>dayofweek</td>
            <td>开始时间</td>
            <td>结束时间</td>
            <td><button class="btn btn-primary fa fa-plus add_new_time"></button></td>
        </tr>
    </table>
</div>
@endsection
