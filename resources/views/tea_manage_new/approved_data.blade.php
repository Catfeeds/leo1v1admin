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
                    <td >老师</td>
                    <td >学生数</td>
                    <td >课耗</td>
                    <td >CC转化率</td>
                    <td >CR转化率</td>
                    <td >老师违规数</td>
                    <td > 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>
                            <a href="http://admin.leo1v1.com/human_resource/index?teacherid={{@$var['teacherid']}}">
                                {{@$var["tea_nick"]}}
                            </a>
                        </td>
                        <td>
                            {{@$var['stu_num']}}
                        </td>
                        <td>
                            {{@$var['lesson_num']}}
                        </td>
                        <td>
                            {{@$var['cc_rate']}}
                        </td>
                        <td>
                            {{@$var['cr_rate']}}
                        </td>
                        <td>
                            {{@$var['violation_num']}}
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
