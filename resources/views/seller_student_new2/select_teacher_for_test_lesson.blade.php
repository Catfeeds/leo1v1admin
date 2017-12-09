@extends('layouts.app')
@section('content')
    <style>
     select{width:200px;}

    </style>
    <section class="content">
    @if(!empty($require_info))
    <table >
        <tr>
            <td>姓名：{{$require_info['nick']}}</td>
            <td>性别：{{$require_info['gender_str']}}</td>
            <td>年级：{{$require_info['grade_str']}}</td>
            <td>科目：{{$require_info['subject_str']}}</td>
            <td>试听上课时间：{{$require_info['request_time']}}</td>
        </tr>
        <tr>
            <td>老师要求：</td>
        </tr>
        <tr>
            <td>试听需求：{{$require_info['test_stu_request_test_lesson_demand']}}</td>
        </tr>
        <tr>
            <td>意向度:报价反应:{{$require_info['quotation_reaction_str']}}</td>
            <td>上课意向:{{$require_info['intention_level_str']}}</td>
        </tr>
    </table>
    @endif
    <hr/>
    <div class="row">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span>老师</span>
                <input id="id_teacherid" type="text" value="" class="opt-change" placeholder="" />
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="input-group ">
                <span>试听上课时间</span>
                <input id="id_teacherid" type="text" value="" class="opt-change" placeholder="" />
            </div>
        </div>
        <div class="col-md-1 col-xs-3">
            <div>
                <button class="btn btn-primary" id="id_set_lesson_time">排课</button>
            </div>
        </div>
        <div class="col-md-1 col-xs-3">
            <div>
                <button class="btn btn-danger" id="id_refund_lesson">驳回</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-md-12" >
            <div class="input-group ">
                <span class="input-group-addon">教师相关</span>
                <select class="opt-change form-control" id="id_teacher_tag1" >
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-md-6" >
            <div class="input-group ">
                <span class="input-group-addon">老师身份</span>
                <select class="opt-change form-control" id="id_identity">
                </select>
                <span class="input-group-addon">性别</span>
                <select class="opt-change form-control" id="id_gender">
                </select>
                <span class="input-group-addon">年龄段</span>
                <select class="opt-change form-control" id="id_tea_age">
                </select>
            </div>
        </div>
    </div>
    <hr>
    <hr>
    <table   class="common-table" >
        <thead>
            <tr>
                <td style="display:none">老师id</td>
                <td >姓名</td>
                <td >性别</td>
                <td >年龄</td>
                <td >手机号</td>
                <td >入职时长</td>
                <td >老师身份</td>
                <td >可上课时间</td>
                <td >标签</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td>{{$var["teacherid"]}}</td>
                    <td>{{$var["realname"]}}</td>
                    <td>{{$var["gender_str"]}}</td>
                    <td>{{$var["age"]}}</td>
                    <td>
                        <a href="javascript:;" class="show_phone" data-phone="{{$var["phone"]}}" >
                            {{$var["phone_hide"]}}
                        </a>
                    </td>
                    <td>{{$var["work_day"]}}</td>
                    <td>{{$var["identity_str"]}}</td>
                    <td>
                        <a href="javascript:;" class="show_phone" data-phone="{{$var["teacherid"]}}" >
                            查看详情
                        </a>
                    </td>
                    <td>标签</td>
                    <td>
                        <div {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}>
                            <a class="fa-hand-o-up opt-stu-origin btn fa" title="编辑优惠力度"></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection

