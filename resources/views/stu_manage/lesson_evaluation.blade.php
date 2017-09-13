@extends('layouts.stu_header')
@section('content')
<script type="text/javascript" src="/page_js/stu_manage/lesson_evaluation.js"></script>
<section class="content" >
    <div class="row">
        <div class="col-xs-6 col-md-4">
            <div class="input-group ">
                <span>时间</span>
                <input  type="text" id="id_date_start" />
                <span >-</span>
                <input  type="text" id="id_date_end" />
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table ">
        <thead>
            <tr>
                <td >老师姓名</td>
                <td >学生姓名</td>
                <td >上课日期</td>
                <td >课次</td>
                <td >上课效果</td>
                <td >课件质量</td>
                <td >课堂互动</td>
                <td >系统稳定性</td>
                <td >学生打分</td>
                <td >学生评语</td>
                <td class="lesson_point">老师评语</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td >{{$var["tea_nick"]}}</td>
                    <td >{{$var["stu_nick"]}}</td>
                    <td >{{$var["lesson_time"]}}</td>
                    <td >{{$var["lesson_num"]}}</td>
                    <td >{{$var["teacher_effect"]}}</td>
                    <td >{{$var["teacher_quality"]}}</td>
                    <td >{{$var["teacher_interact"]}}</td>
                    <td >{{$var["stu_stability"]}}</td>
                    <td >{{$var["stu_score"]}}</td>
                    <td >{{$var["stu_comment"]}}</td>
                    <td >{{@$var["stu_point_performance"]}}</td>
                    <td class="remove-for-xs">
                        <div class="btn-group">
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
