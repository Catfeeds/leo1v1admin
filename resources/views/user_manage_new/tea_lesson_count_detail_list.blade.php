@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/seller_student/common.js"></script>
<script type="text/javascript" src="/js/svg.js"></script>
<script type="text/javascript" src="/js/wb-reply/audio.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >老师:</span>
                    <input type="text" id="id_teacherid" class="opt-change"/>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span >时间:</span>
                    <input type="text" id="id_start_time" class="opt-change"/>
                    <span >-</span>
                    <input type="text" id="id_end_time" class="opt-change"/>
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
                    <button class="btn btn-primary" id="id_reset_already_lesson_count">重置累计课时</button>
                </div>
            </div>
            <div class="col-xs-12 col-md-1">
                <div class="input-group ">
                    <button class="btn btn-primary" id="id_show_all">展开</button>
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
                        <td >课程金额</td>
                        <td >课时数</td>
                        <td >课时价格</td>
                        <td >上课时间</td>
                        <td >状态</td>
                        <td >年级</td>
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
                            <td >{{@$var["lesson_price"]}}</td>
                            <td style="{{@$var["lesson_count_err"]}}">
                                {{$var["lesson_count"]/100}}</td>

                            <td >{{@$var["pre_price"]}}</td>
                            <td >{{@$var["lesson_time"]}}</td>
                            <td >{{@$var["confirm_flag_str"]}}</td>
                            <td >{{@$var["grade_str"]}}</td>
                            <td >{{@$var["teacher_money_type_str"]}}-{{@$var["tea_level"]}}</td>
                            <td >{{isset($var["already_lesson_count"])?@$var["already_lesson_count"]/100:""}}</td>
                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <a  class="opt-goto-lesson " >  课程  </a>
                                    <a  class="fa-video-camera  opt-play " title="回放"  ></a>
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

