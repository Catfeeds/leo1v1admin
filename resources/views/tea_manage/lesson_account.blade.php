@extends('layouts.app')

@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
<link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />

<section class="content">
    
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="input-group ">
                <span >日期</span>
                <input type="text" id="id_start_date" class="opt-change"/>
                <span >-</span>
                <input type="text" id="id_end_date" class="opt-change"/>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span> 课程类型</span>
                <select id="id_lesson_type" class="opt-change" >
                    <option value="-1" >全部</li>
                    <option value="0" >公开课</li>
                    <option value="1" >小班课</li>
                </select>
            </div>
        </div>
        
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >老师</span>
                <input id="id_teacherid" class="opt-change"  /> 
            </div>
        </div>
        
    </div>
    <hr/>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <td class="remove-for-not-xs"></td>
                <td class="remove-for-xs">id</td>
                <td class="remove-for-xs">课程类型</td>
                <td class="remove-for-xs">课程包名称</td>
                <td class="remove-for-xs">上课时间</td>
                <td class="remove-for-xs">老师</td>
                <td class="remove-for-xs">上课学生人数</td>
                <td class="remove-for-xs">总学生人数</td>
                <td class="remove-for-xs">到达率</td>
                <td class="remove-for-xs">学生列表</td>
                <!--<td class="remove-for-xs" style="min-width:320px" >操作</td>-->
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
		            @include("layouts.td_xs_opt")
                    <td class="lessonid">{{$var["lessonid"]}}</td>
                    <td class="remove-for-xs">{{$var["lesson_type_str"]}}</td>
                    <td class="remove-for-xs">{{$var["course_name"]}}</td>
                    <td class="remove-for-xs">{{$var["lesson_time"]}}</td>
                    <td class="remove-for-xs">{{$var["tea_nick"]}}</td>
                    <td class="remove-for-xs">{{$var["user_login"]}}</td>
                    <td class="remove-for-xs">{{$var["user_all"]}}</td>
                    <td class="remove-for-xs">{{$var["user_rate"]}}</td>
                    <td class="td-info-stu" data-lesson_type="{{$var["lesson_type"]}}" data-lessonid="{{$var["lessonid"]}}" data-courseid="{{$var["courseid"]}}"></td>
                </tr>
			@endforeach
        </tbody>
    </table>
	@include("layouts.page")

@endsection
