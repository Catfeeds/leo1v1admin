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
                        <span >老师</span>
                        <input id="id_teacherid" class="opt-change"  />
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>老师id</td>
                    <td >老师</td>
                    <td >学生数</td>
                    <td >课耗/(单位:课时)</td>
                    <td >CC转化率</td>
                    <td >CR转化率</td>
                    <td >老师违规数</td>
                    <td > 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var['teacherid']}}</td>
                        <td>
                            {{@$var["tea_nick"]}}
                        </td>
                        <td>
                            {{@$var['stu_num']}}
                        </td>
                        <td>
                            {{@round($var['total_lesson_num'],2)}}
                        </td>
                        <td>
                            {{@round($var['cc_rate']*100,2)}}%
                        </td>
                        <td>
                            {{@round($var['cr_rate']*100,2)}}%
                        </td>
                        <td>
                            <a class="violation_num" data-teacherid="{{@$var['teacherid']}}">
                                {{@$var['violation_num']}}
                            </a>
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
    <div style="display:none;" >
        <div id="id_assign_log">
            <table   class="table table-bordered "   >
                <tr>  <th> 类别 <th>数量   </tr>
                    <tbody class="data-body">
                    </tbody>
            </table>
        </div>
    </div>


@endsection
