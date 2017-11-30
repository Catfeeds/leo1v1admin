@extends('layouts.app')
@section('content')
    <!-- <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
         <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
         <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
         <script type="text/javascript" src="/js/qiniu/ui.js"></script>
         <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
         <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
         <script type="text/javascript" src="/js/jquery.md5.js"></script>

    -->



    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <script type="text/javascript" src="/page_js/teacher_freeze_limit_record.js"></script>


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
                    <td >老师</td>
                    {!!\App\Helper\Utils::th_order_gen([
                        ["学生数","stu_num" ],
                        ["课耗","lesson_num" ],
                        ["CC转化率","cc_rate" ],
                        ["CR转化率","cr_rate" ],
                        ["老师违规数","violation_num" ],
                       ])  !!}

                    <td > 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>
                            {{@$var["tea_nick"]}}
                        </td>
                        <td>
                            {{@$var['stu_num']}}
                        </td>
                        <td>
                            {{@$var['lesson_num']}}
                        </td>
                        <td>
                            {{@$var['cc_rate']*100}}%
                        </td>
                        <td>
                            {{@$var['cr_rate']*100}}%
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
