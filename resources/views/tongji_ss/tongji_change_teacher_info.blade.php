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

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">换老师原因</span>
                        <select class="opt-change form-control" id="id_change_teacher_reason_type" >
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>学生姓名</td>
                    <td>年级</td>
                    <td>科目 </td>
                    <td>老师 </td>
                    <td>原老师 </td>
                    <td>助教 </td>
                    <td>试听时间 </td>
                    <td>课时确认 </td>
                    <td>试听结果</td>
                    <td>换老师原因类型</td>
                    <td>换老师原因描述/图片链接</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $index => $var )
                    <tr>
                        <td>
                            <a  href="/user_manage/ass_archive_ass?order_by_str=ass_assign_time%20desc&grade=-1&student_type=-1&revisit_flag=-1&warning_stu=-1&user_name={{$var["userid"]}}"
                                target="_blank" title="学生信息">{{@$var["nick"]}} </a>
                        </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>
                            <a  href="/human_resource/index_ass?teacherid={{$var["teacherid"]}}"
                                target="_blank" title="老师信息">{{@$var["realname"]}} </a>
                        </td>
                        <td>
                            <a  href="/human_resource/index_ass?teacherid={{$var["old_teacher_id"]}}"
                                target="_blank" title="老师信息">{{@$var["old_teacher_nick"]}} </a>

                        </td>
                        <td>{{@$var["ass_nick"]}} </td>
                        <td>{{@$var["test_lesson_time"]}} </td>
                        <td>{!!@$var["success_flag_str"]!!} </td>
                        <td>{!! @$var["order_confirm_flag_str"]!!} </td>
                        <td>{{@$var['change_teacher_reason_type_str']}}</td>
                        <td>{{@$var['change_teacher_reason']}}
                            @if($var['change_teacher_reason_img_url'])
                                /<a target="_blank" class="btn fa fa-download" href="{{$var['change_teacher_reason_img_url']}}"></a>
                            @endif
                        </td>
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
