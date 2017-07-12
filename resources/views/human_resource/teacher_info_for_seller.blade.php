@extends('layouts.app')
@section('content')
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
    <section class="content ">
        <div>
            <div class="row ">

                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="address" id="id_address"  placeholder="输入姓名 回车查找" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td >真实姓名</td>
                    <td>手机</td>
                    <td style="width:220px">冻结排课情况</td>
                    <td style="width:220px">限制排课情况</td>
                    <td >暂停接试听课情况</td>
                    <td >一周试听课限制次数</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["realname"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>
                            @if(@$var["freeze_time"] > 0)
                                冻结时间:{{@$var["freeze_time_str"]}}<br>
                                建议:{{@$var["freeze_reason"]}}<br>
                                操作人:{{@$var["freeze_adminid_str"]}}
                            @endif

                        </td>
                        <td>
                            @if(@$var["limit_plan_lesson_type"] > 0)
                                状态:一周限排{{$var["limit_plan_lesson_type"]}}次课<br>
                                原因:{{@$var["limit_plan_lesson_reason"]}}<br>
                                操作人:{{$var["limit_plan_lesson_account"]}}<br>
                                操作时间:{{$var["limit_plan_lesson_time_str"]}}
                            @endif
                        </td>

                        <td>
                            @if($var["lesson_hold_flag"]==1)
                                已暂停接试听课<br>
                                操作人:{{@$var["lesson_hold_flag_acc"]}}<br>
                                操作时间:{{@$var["lesson_hold_flag_time_str"]}}
                            @endif
                        </td>
                        <td>{{$var["limit_week_lesson_num"]}}</td>

                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                                <a class="opt-show-lessons-new"  title="课程列表-new">课程-new</a>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
