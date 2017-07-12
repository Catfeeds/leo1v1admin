@extends('layouts.app')
@section('content') 
<include:file="../al_common/header.html"/>
<link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />

    <div class="row">
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

    <table class="common-table">
        <thead>
            <tr>
                <td class="">id</td>
                <td class="">log_date</td>
                <td class="">新增用户科目总数</td>
                <td class="">新增老用户科目总数</td>
                <td class="">新增上课次数</td>
                <td class="">老用户上课次数</td>
                <td class="">试听上课次数</td>
                <td class="">收费总额</td>
                <td class="">课时消耗累计总额</td>
                <td class="">试听人数</td>
                <td class="">付费试听人数</td>
                <td class="">付费试听总金额</td>
                <td class="">新增人数</td>
                <td class="">续费人数</td>
                <td class="">老用户数</td>
                <td class="">停课人数</td>
                <td class="">结课人数</td>
                <td class="">老师人数</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td class="lessonid">{{$var["id"]}}</td>
                    <td class="">{{$var["log_date"]}}</td>
                    <td class="">{{$var["new_course_count"]}}</td>
                    <td class="">{{$var["old_course_count"]}}</td>
                    <td class="">{{$var["new_lesson_count"]}}</td>
                    <td class="">{{$var["old_lesson_count"]}}</td>
                    <td class="">{{$var["test_lesson_count"]}}</td>
                    <td class="">{{$var["money"]}}</td>
                    <td class="">{{$var["real_money"]}}</td>
                    <td class="">{{$var["test_free_count"]}}</td>
                    <td class="">{{$var["test_money_count"]}}</td>
                    <td class="">{{$var["test_money"]}}</td>
                    <td class="">{{$var["new_count"]}}</td>
                    <td class="">{{$var["next_count"]}}</td>
                    <td class="">{{$var["old_count"]}}</td>
                    <td class="">{{$var["stop_count"]}}</td>
                    <td class="">{{$var["finish_count"]}}</td>
                    <td class="">{{$var["teacher_count"]}}</td>
                    <td >
                        <div></div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
@endsection
