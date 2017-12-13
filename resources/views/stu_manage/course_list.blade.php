@extends('layouts.stu_header')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">课堂类型</span>
                    <select id="id_competition_flag" class="opt-chang">
                        <option value="0">常规1对1</option>
                        <option value="1">竞赛1对1</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">总课时</span>
                    <input value="{{$lesson_left}}"/>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">已分配课时</span>
                    <input value="{{$assigned_lesson_count}}"/>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">待分配课时</span>
                    <input id="id_unassigned_lesson_count" value="{{$unassigned_lesson_count}}"/>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <button class="btn btn-warning" id="id_add_course_new">增加课程包</button>
            </div>
            @if($show_flag >1)
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-warning" id="id_auto_add_course_new">一键增加课程包</button>
                </div>
            @endif
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td style="display:none">id</td>
                    <td>添加时间</td>
                    <td> 类型</td>
                    <td> 老师</td>
                    <td> 科目</td>
                    <td> 年级</td>
                    <td> 分配课时数 </td>
                    <td> 完成课时数 </td>
                    <td> 剩余课时数(包括已排未上) </td>
                    <td> 已排未上课时数 </td>
                    <td> 状态</td>
                    <td> 默认课时</td>
                    <td> 每周评价</td>
                    <td> 开启视频</td>
                    <td> 常规课上奥数课标识</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> {{$var["courseid"]}} </td>
                        <td> {{$var["add_time_str"]}} </td>
                        <td> {{$var["course_type_str"]}} </td>
                        <td> {{$var["teacher_nick"]}} </td>
                        <td> {{$var["subject_str"]}} </td>
                        <td> {{$var["grade_str"]}} </td>
                        <td> {{$var["assigned_lesson_count"]}} </td>
                        <td> {{$var["finish_lesson_count"]}} </td>
                        <td> {{$var["left_lesson_count"]}} </td>
                        <td> {{$var["no_finish_lesson_count"]}} </td>
                        <td> {{$var["course_status_str"]}} </td>
                        <td> {{$var["default_lesson_count"]}} </td>
                        <td> {{$var["week_comment_num_str"]}} </td>
                        <td> {{$var["enable_video_str"]}} </td>
                        <td> {{$var["reset_lesson_count_flag_str"]}} </td>
                        <td>
                            <div class="opt-div" 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-list opt-lesson-list" title="排课"> </a>
                                <a class="fa opt-set-course-status" title="修改课程包信息"> 课程包信息</a>
                                <a class="fa fa-trash-o opt-del" title="删除空课程包"> </a>
                                <a class="fa opt-assigned_lesson_count" title="分配课时">分配课时 </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
