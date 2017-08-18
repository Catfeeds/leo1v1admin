@extends('layouts.app')
@section('content')
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js?v=121"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-1 col-md-2">
                    <div class="input-group ">
                        <span >学生</span>
                        <input id="id_studentid"  /> 
                    </div>
                </div>
                <div class="col-xs-6 col-md-1">
                    <button id="id_plan_course" class="btn btn-warning">排课</button>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button id="id_plan_regular_course" class="btn btn-primary">按常规课表排课</button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>lessonid </td>
                    <td>老师</td>
                    <td>工资分类 </td>
                    <td>等级 </td>
                    <td>学生 </td>
                    <td>年级 </td>
                    <td>科目</td>
                    <td>状态 </td>
                    <td>课程名字 </td>
                    <td>助教</td>
                    <td>开始 </td>
                    <td>结束 </td>
                    <td>课次</td>
                    <td>课时数</td>
                    <td>课时确认</td>
                    <td>课程取消原因</td>
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lessonid"]}} </td>
                        <td>{{@$var["teacher_nick"]}} </td>
                        <td>{{@$var["teacher_money_type_str"]}} </td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["user_nick"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["lesson_status_str"]}} </td>
                        <td>{{@$var["lesson_name"]}} </td>
                        <td>{{@$var["ass_nick"]}} </td>
                        <td>{{@$var["lesson_start_str"]}} </td>
                        <td>{{@$var["lesson_end_str"]}} </td>
                        <td>{{@$var["lesson_num"]}} </td>
                        <td>{{@$var["lesson_count"]/100}}</td>
                        <td>{{@$var["confirm_flag_str"]}}</td>
                        <td>{{@$var["lesson_cancel_reason_type_str"]}}</td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="cancel_lesson fa-trash-o btn fa" title="删除课程"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

