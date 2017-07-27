@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>编号</td>
                    <td>学生姓名</td>
                    <td>年纪</td>
                    <td>科目 </td>
                    <td>换后老师 </td>
                    <td>原来老师 </td>
                    <td>助教 </td>
                    <td>试听时间 </td>
                    <td>课时确认 </td>
                    <td>试听结果</td>
                    <td>换老师原因</td>
                    <td>是否完成</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $index => $var )
                    <tr>
                        <td>{{@$index}}</td>
                        <td>{{@$var["stu_nick"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["teacher_nick"]}}</td>
                        <td>{{@$var["old_teacher_nick"]}}</td>
                        <td>{{@$var["ass_nick"]}} </td>
                        <td>{{@$var["test_lesson_time"]}} </td>
                        <td>{!!@$var["success_flag_str"]!!} </td>
                        <td>{!! @$var["is_lesson_time_flag_str"]!!} </td>
                        <td>{{$var["change_teacher_reason_type_str"]}}</td>
                        <td>{!!$var["is_done_flag_str"]!!}</td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
