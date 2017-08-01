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
                        <span class="input-group-addon">调课原因 </span>
                        <select class="opt-change form-control" id="id_lesson_cancel_reason_type" >
                        </select>
                    </div>
                </div>

            </div>


        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>编号</td>
                    <td>学生</td>
                    <td>助教</td>
                    <td>课时数</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $index => $var )
                    <tr>
                        <td>{{@$index}}</td>
                        <td>
                        <a  href="/user_manage/ass_archive_ass?order_by_str=ass_assign_time%20desc&grade=-1&student_type=-1&revisit_flag=-1&warning_stu=-1&user_name={{$var["userid"]}}"
                            target="_blank" title="学生信息">{{@$var["nick"]}} </a>
                        </td>

                        <td>{{@$var["ass_nick"]}}</td>
                        <td class="show_detail" date-userid="{{$var['userid']}}"><a>{{@$var["lesson_count_total"]}}</a></td>
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

        <div style="display:none;" >
            <div id="id_assign_log">
                <table   class="table table-bordered "   >
                    <tr>  <th> 学生 <th>类型 <th>上课时段 <th>年级 <th>科目 <th>老师 <th>助教 <th>课时数 <th>课时确认</tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>

    </section>

@endsection
