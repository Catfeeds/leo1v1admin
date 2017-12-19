@extends('layouts.app')
@section('content')
    <style>
     .red-border{border:2px solid red;}
     .require-table td{
         width:25%;
     }
     .font_color{color:#ff3451;}
    </style>
    <section class="content require_content" style="display:none">
        <div style="display:none">
            <div class="input-group ">
                <span>需求id</span>
                <input id="id_require_id"/>
            </div>
        </div>
        @if(!empty($require_info))
            <div align="center">
                <h3>试听需求</h3>
            </div>
            <table class="common-table require-table" id="id_require_info" {!! \App\Helper\Utils::gen_jquery_data($require_info) !!}>
                <tr >
                    <td>学生姓名:<span class="font_color" >{{$require_info['nick']}}</span></td>
                    <td>学生性别:<span class="font_color">{{$require_info['gender_str']}}</span></td>
                    <td>学生年级:<span class="font_color">{{$require_info['grade_str']}}</span></td>
                    <td>试听科目:<span class="font_color">{{$require_info['subject_str']}}</span></td>
                </tr
                <tr>
                    <td>试听时间:<span class="font_color">{{$require_info['request_time']}}</span></td>
                    <td>
                        老师身份:<span class="font_color">{{ $require_info['tea_identity_str'] }}</span><br/>
                        老师类型:<span class="font_color">{{ $require_info['teacher_type_str'] }}</span><br/>
                        年龄要求:<span class="font_color">{{ $require_info['tea_age_str'] }}</span><br/>
                        性别要求:<span class="font_color">{{ $require_info['tea_gender_str'] }}</span><br/>
                    </td>
                    <td>报价反应:<span class="font_color">{{$require_info['quotation_reaction_str']}}</span></td>
                    <td>上课意向:<span class="font_color">{{$require_info['intention_level_str']}}</span></td>
                </tr>
                <tr>
                    <td>风格性格:<span class="font_color">{{ @$require_info['风格性格'] }}</span></td>
                    <td>专业能力:<span class="font_color">{{ @$require_info['专业能力'] }}</span></td>
                    <td>课堂气氛:<span class="font_color">{{ @$require_info['课堂气氛'] }}</span></td>
                    <td>课件要求:<span class="font_color">{{ @$require_info['课件要求'] }}</span></td>
                </tr>
                <tr>
                    <td colspan="4">
                        试听需求：<span class='font_color'>{{$require_info['test_stu_request_test_lesson_demand']}}</span>
                    </td>
                </tr>
            </table>
        @endif
        <hr/>
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span>上课时间</span>
                    <input id="id_lesson_time" type="text" value="{{ @$require_info['lesson_time'] }}" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>老师</span>
                    <input id="id_teacherid" style="display:none" type="text"
                           value="{{ @$require_info['teacherid'] }}" autocomplete="off" />
                    <span id="id_teacher_name" type="text" style="height:34px" >{{ @$require_info['tea_nick'] }}</span>
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
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">老师身份</span>
                    <select class="opt-change form-control" id="id_identity">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">性别要求</span>
                    <select class="opt-change form-control" id="id_gender">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">年龄要求</span>
                    <select class="opt-change form-control" id="id_tea_age">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">老师类型</span>
                    <select class="opt-change form-control" id="id_teacher_type">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">教师相关</span>
                    <select class="opt-change form-control" id="id_teacher_tags" >
                        <option value ="">[全部]</option>
                        @foreach($teacher_tags as $val)
                            <option value="{{$val}}">{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">课堂相关</span>
                    <select class="opt-change form-control" id="id_lesson_tags" >
                        <option value ="">[全部]</option>
                        @foreach($lesson_tags as $val)
                            <option value="{{$val}}">{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
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
                    <tr class="teacher-info" data-teacherid ="{{ $var['teacherid'] }}">
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

