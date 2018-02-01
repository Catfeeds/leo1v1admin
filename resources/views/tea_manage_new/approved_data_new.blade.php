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
                    <td >老师违规总数</td>
                    <td >有效试听课数</td>
                    <td >有效常规课数</td>
                    <td >未上传讲义数</td>
                    <td >试听课迟到数</td>
                    <td >常规课迟到数</td>
                    <td >未课后评价数</td>
                    <td >老师调课数</td>
                    <td >老师请假数</td>
                    <td >试听课旷课</td>
                    <td >常规课旷课</td>
                    <td >换老师数</td>
                    <td >退费数</td>
                    <td >所有试听课数</td>
                    <td >所有常规课数</td>
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
                        <td>{{@$var['violation_num']}}</td>
                        <td>{{@$var['test_lesson_count']}}</td>
                        <td>{{@$var['regular_lesson_count']}}</td>
                        <td>{{@$var['no_notes_count']}}</td>
                        <td>{{@$var['test_lesson_later_count']}}</td>
                        <td>{{@$var['regular_lesson_later_count']}}</td>
                        <td>{{@$var['no_evaluation_count']}}</td>
                        <td>{{@$var['turn_class_count']}}</td>
                        <td>{{@$var['ask_for_leavel_count']}}</td>
                        <td>{{@$var['test_lesson_truancy_count']}}</td>
                        <td>{{@$var['regular_lesson_truancy_count']}}</td>
                        <td>{{@$var['turn_teacher_count']}}</td>
                        <td>{{@$var['stu_refund']}}</td>
                        <td>{{@$var['all_test_lesson_count']}}</td>
                        <td>{{@$var['all_regular_lesson_count']}}</td>
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
