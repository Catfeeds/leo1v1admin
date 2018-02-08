@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.base64.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />

    <style>
     .red-border{border:2px solid red;}
     .require-table td{
         width:25%;
     }
     .font_color{color:#ff3451;}
    </style>
    <section class="content require_content" style="display:none">
        <input type="hidden" id="id_userid" value="{{$userid}}">
        @if(!empty($require_info))
            <div align="center">
                <h3>试听需求</h3>
            </div>
            <table class="common-table require-table" id="id_require_info" {!! \App\Helper\Utils::gen_jquery_data($require_info) !!}>
                <tr >
                    <td>学生姓名:<span class="font_color">{{$require_info['nick']}}</span></td>
                    <td>学生性别:<span class="font_color">{{$require_info['gender_str']}}</span></td>
                    <td>学生年级:<span class="font_color">{{$require_info['grade_str']}}</span></td>
                    <td>试听科目:<span class="font_color">{{$require_info['subject_str']}}</span></td>
                </tr
                <tr>
                    <td>
                        开始时间:<span class="font_color">{{$require_info['request_time']}}</span><br/>
                        结束时间:<span class="font_color">{{$require_info['request_time_end']}}</span>
                    </td>
                    <td>
                        老师身份:<span class="font_color">{{ $require_info['tea_identity_str'] }}</span><br/>
                        性别要求:<span class="font_color">{{ $require_info['tea_gender_str'] }}</span><br/>
                        年龄要求:<span class="font_color">{{ $require_info['tea_age_str'] }}</span><br/>
                        老师类型:<span class="font_color">{{ $require_info['teacher_type_str'] }}</span><br/>
                        学生教材:<span class="font_color">{{ $require_info['textbook'] }}</span><br/>
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
                        学科化标签:<span class='font_color'>{{ @$require_info['学科化标签'] }}</span><br/>
                        试听需求  :<span class='font_color'>{{$require_info['test_stu_request_test_lesson_demand']}}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        排课状态:<span class='font_color'>{{$require_info['test_lesson_student_status_str']}}</span>
                    </td>
                    <td colspan="2">
                        老师确认状态:<span class='font_color'>{{$require_info['accept_status_str']}}</span>
                    </td>
                </tr>
            </table>
        @endif
        <hr/>
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span><font color="red">*</font>上课时间</span>
                    <input id="id_lesson_time" type="text" value="{{ @$require_info['lesson_time'] }}" />
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="input-group">
                    <span><font color="red">*</font>老师信息</span>
                    <input id="id_teacherid"  type="text" value="{{ @$require_info['teacherid'] }}"
                           autocomplete="off" style="display:none" />
                    <input id="id_teacher_info" type="text" value="{{ @$require_info['teacher_info'] }}"
                           autocomplete="off" placeholder="输入老师姓名/手机/昵称 回车搜索" />
                </div>
            </div>
            <div class="col-md-1 col-xs-3 require_status_first">
                <div>
                    <button class="btn btn-primary" id="id_set_lesson_time">排课</button>
                </div>
            </div>
            <div class="col-md-1 col-xs-3 require_status_first">
                <div>
                    <button class="btn btn-danger" id="id_refund_lesson">驳回</button>
                </div>
            </div>
            <div class="col-md-2 col-xs-3 require_status_second">
                <div>
                    <button class="btn btn-warning" id="id_change_lesson_time">更换老师/时间</button>
                </div>
            </div>
            <div class="col-md-1 col-xs-3 require_status_second">
                <div>
                    <button class="btn btn-success" id="id_refresh_flag" data-refresh_flag=0>刷新列表</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;老师身份</span>
                    <select class="opt-change form-control" id="id_identity">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;性别要求</span>
                    <select class="opt-change form-control" id="id_gender">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;年龄要求</span>
                    <select class="opt-change form-control" id="id_tea_age">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;老师类型</span>
                    <select class="opt-change form-control" id="id_teacher_type">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;老师地区</span>
                    <input  placeholder="老师地区" id="id_dialect_type" />
                </div>
            </div>


            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;教材版本</span>
                    <select class="opt-change form-control" id="id_region_version">
                        <option value="0">无要求</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;精排筛选</span>
                    <select id="id_plan_level" class ="opt-change form-control" >
                        <option value="-1">无要求</option>
                        <option value="1">维度A</option>
                        <option value="2">维度B</option>
                        <option value="3">维度C</option>
                        <option value="4">维度C候选</option>
                        <option value="5">维度D</option>
                        <option value="6">维度D候选</option>
                        <option value="7">其他</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;教师相关</span>
                    <select class="opt-change form-control" id="id_teacher_tags" >
                        <option value ="">无要求</option>
                        @foreach($teacher_tags as $val)
                            <option value="{{$val}}">{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
           
        </div>
        <div class="row">           
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;课堂相关</span>
                    <select class="opt-change form-control" id="id_lesson_tags" >
                        <option value ="">无要求</option>
                        @foreach($lesson_tags as $val)
                            <option value="{{$val}}">{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">&nbsp;教学相关</span>
                    <select class="opt-change form-control" id="id_teaching_tags" >
                        <option value ="">无要求</option>
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
                    <td >老师身份</td>
                    <td >性别</td>
                    <td >年龄</td>
                    <td >老师类型</td>
                    <td >老师地区</td>
                    <td >精排筛选</td>
                    <td >教务备注</td>
                    <td width="300px">教材版本</td>
                    <td >手机号</td>
                    <td >可上课时间</td>
                    <td width="300px">标签</td>
                    <td >入职时长</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr class="teacher-info" data-teacherid ="{{ $var['teacherid'] }}">
                        <td>{{$var["teacherid"]}}</td>
                        <td>{{$var["realname"]}}</td>
                        <td>{{$var["identity_str"]}}</td>
                        <td>{{$var["gender_str"]}}</td>
                        <td>{{$var["age"]}}</td>
                        <td>{{$var["teacher_type_str"]}}</td>
                        <td>{{$var["phone_province"]}} </td>
                        <td>{{@$var["fine_dimension"]}}</td>
                        <td>{{$var["tea_note"]}} </td>
                        <td>{{$var["teacher_textbook_str"]}}</td>
                        <td>
                            <a href="javascript:;" class="show_phone" data-phone="{{$var["phone"]}}" >
                                {{$var["phone_hide"]}}
                            </a>
                        </td>
                        <td>
                            <a href="javascript:;" class="show_tea_free_time" data-teacherid="{{$var["teacherid"]}}" >
                                查看详情
                            </a>
                        </td>
                        <td>{{$var['tags_str']}}</td>
                        <td>{{$var["work_day"]}}</td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}>
                                <a class="opt-set-teacher btn fa" title="选中老师">选中老师</a>
                                <a class=" opt-user-info div_show" href="/teacher_info_admin/index?teacherid={{$var["teacherid"]}}" target="_blank" title="老师信息">教师档案 </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
