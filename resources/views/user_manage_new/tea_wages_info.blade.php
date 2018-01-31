@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/seller_student/common.js"></script>
<script type="text/javascript" src="/js/svg.js"></script>
<script type="text/javascript" src="/js/wb-reply/audio.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script>
 var teacherid={{@$teacherid}}
</script>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >老师:</span>
                    <input type="text" id="id_teacherid" class="opt-change"/>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >学生:</span>
                    <input type="text" id="id_studentid" class="opt-change"/>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >课程核算类型:</span>
                    <select id="id_show_type" class="opt-change">
                        <option value="current">当前消耗</option>
                        <option value="all">本月所有</option>
                    </select>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">总工资:{{@$all_price}}</span>
                    <span class="input-group-addon">
                        <a class="teacher_reward_list" style="cursor:pointer">额外奖金</a>
                        :{{@$teacher_reward}}
                    </span>
                    <span class="input-group-addon">常规课时:{{@$lesson_count['normal_total']/100}}</span>
                    <span class="input-group-addon">试听课时:{{@$lesson_count['trial_total']/100}}</span>
                </div>
            </div>
        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >学生</td>
                        <td >课时消耗分组</td>
                        <td >课程类型</td>
                        <td >金额</td>
                        <td >课时数</td>
                        <td >课时基础价格</td>
                        <td >课时奖金</td>
                        <td >奖金基础价格</td>
                        <td >全勤奖</td>
                        <td >课程扣款</td>
                        <td >扣款信息</td>
                        <td >上课时间</td>
                        <td >状态</td>
                        <td >年级</td>
                        <td >科目</td>
                        <td >老师等级</td>
                        <td >累计课时数</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["key1_class"]}}" class="key1" >{{@$var["stu_nick"]}}</td>
                            <td data-class_name="{{$var["key2_class"]}}" class="key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{@$var["lesson_count_level_str"]}} </td>
                            <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{@$var["lesson_type_str"]}}</td>
                            <td >{{$var["price"]}}</td>
                            <td style="{{@$var["lesson_count_err"]}}">
                                {{$var["lesson_count"]/100}}
                            </td>
                            <td >{{@$var["pre_price"]}}</td>
                            <td >{{@$var["lesson_reward"]}}</td>
                            <td >{{@$var["pre_reward"]}}</td>
                            <td >{{@$var["lesson_full_reward"]}}</td>
                            <td >{{@$var["lesson_cost"]}}</td>
                            <td >{{@$var["lesson_cost_info"]}}</td>
                            <td >{{@$var["lesson_time"]}}</td>
                            <td >{{@$var["confirm_flag_str"]}}</td>
                            <td >{{@$var["grade_str"]}}</td>
                            <td >{{@$var["subject_str"]}}</td>
                            <td >{{@$var["teacher_money_type_str"]}}-{{@$var["tea_level"]}}</td>
                            <td >{{isset($var["already_lesson_count"])?@$var["already_lesson_count"]/100:""}}</td>
                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}
                                >
                                    <a class="opt-goto-lesson">课程</a>
                                    <a class="fa-video-camera opt-play" title="回放"></a>
                                    <a class="opt-add_reward" title="添加奖励">奖</a>
                                    <a class="opt-reset_lesson" title="重置等级">重置</a>
                                    @if(!\App\Helper\Utils::check_env_is_release() || $_account_role==12)
                                        <a class="opt-update-log" title="更改课程信息">更改课程信息</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
    <script src="/js/qiniu/plupload/plupload.full.min.js"></script>
@endsection

