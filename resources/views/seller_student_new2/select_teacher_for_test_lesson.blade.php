@extends('layouts.app')
@section('content')
    <section class="content require_content" style="display:none">
        <div style="display:none">
            <div class="input-group ">
                <span>需求id</span>
                <input id="id_require_id"/>
            </div>
        </div>
        @if(!empty($require_info))
            <table id="id_require_info" {!!  \App\Helper\Utils::gen_jquery_data($require_info) !!}>
                <tr>
                    <td>姓名：{{$require_info['nick']}}</td>
                    <td>性别：{{$require_info['gender_str']}}</td>
                    <td>年级：{{$require_info['grade_str']}}</td>
                    <td>科目：{{$require_info['subject_str']}}</td>
                    <td>试听上课时间：{{$require_info['request_time']}}</td>
                </tr>
                <tr>
                    <td>
                        老师要求 : <br/>
                        老师身份 : {{ $require_info['tea_identity_str'] }}&nbsp;&nbsp;&nbsp;
                        年龄段   : {{ $require_info['tea_age_str'] }}&nbsp;&nbsp;&nbsp;
                        性别     : {{ $require_info['tea_gender_str'] }}<br/>
                        风格性格 : {{ @$require_info['风格性格'] }}<br/>
                        专业能力 : {{ @$require_info['专业能力'] }}<br/>
                        课堂气氛 : {{ @$require_info['课堂气氛'] }}<br/>
                        课件要求 : {{ @$require_info['课件要求'] }}<br/>
                    </td>
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
                    <input id="id_teacherid" style="display:none" type="text" value="{{ @$require_info['teacherid'] }}" />
                    <span id="id_teacher_name" type="text" >{{ @$require_info['tea_nick'] }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span>试听上课时间</span>
                    <input id="id_lesson_time" type="text" value="{{ @$require_info['lesson_time'] }}" />
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
            <div class="col-xs-6 col-md-8" >
                <div class="input-group ">
                    <span class="input-group-addon">教师相关</span>
                    <select class="opt-change form-control" id="id_teacher_tags" >
                        <option value ="">[全部]</option>
                        @foreach($teacher_tags as $val)
                            <option value="{{$val}}">{{$val}}</option>
                        @endforeach
                    </select>
                    <span class="input-group-addon">课堂相关</span>
                    <select class="opt-change form-control" id="id_lesson_tags" >
                        <option value ="">[全部]</option>
                        @foreach($lesson_tags as $val)
                            <option value="{{$val}}">{{$val}}</option>
                        @endforeach
                    </select>
                    <span class="input-group-addon">教学相关</span>
                    <select class="opt-change form-control" id="id_teaching_tags" >
                        <option value ="">[全部]</option>
                        @foreach($teaching_tags as $val)
                            <option value="{{$val}}">{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-6" >
                <div class="input-group ">
                    <span class="input-group-addon">老师身份</span>
                    <select class="opt-change form-control" id="id_identity">
                        <option value="0">无要求</option>
                    </select>
                    <span class="input-group-addon">性别</span>
                    <select class="opt-change form-control" id="id_gender">
                        <option value="0">无要求</option>
                    </select>
                    <span class="input-group-addon">年龄段</span>
                    <select class="opt-change form-control" id="id_tea_age">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>
        </div>
        <hr>
        <hr>
        <table class="common-table">
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
                            <a href="javascript:;" class="show_tea_free_time" data-teacherid="{{$var["teacherid"]}}" >
                                查看详情
                            </a>
                        </td>
                        <td>{{$var['tags_str']}}</td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}>
                                <a class="opt-set-teacher btn fa" title="选中老师">选中老师</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection

