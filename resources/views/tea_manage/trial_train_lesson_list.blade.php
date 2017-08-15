@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.base64.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

    <script type="text/javascript" src="/page_js/teacher_freeze_limit_record.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />

    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div id="id_date_range" class="opt-change">
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >年级</span>
                        <select id="id_grade" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >审核状态</span>
                        <select id="id_status" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >测试用户</span>
                        <select id="id_is_test_flag" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >课程状态</span>
                        <select id="id_lesson_status" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师</span>
                        <input id="id_teacherid" class="opt-change"/>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td style="display:none">lessonid</td>
                    <td>课程信息</td>
                    <td>试听需求</td>
                    <td>课程状态</td>
                    <td>监课情况</td>
                    <td>教研建议</td>
                    <td>审核状态</td>
                    <td>审核人</td>
                    <td class ="caozuo">操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["lessonid"]}}</td>
                        <td>
                            课程时间：{{$var["lesson_time"]}}<br>
                            老师：{{$var["tea_nick"]}}<br>
                            年级：{{$var["grade_str"]}}<br>
                            科目：{{$var["subject_str"]}}<br>
                        </td>
                        <td>{{$var["stu_request_test_lesson_demand"]}}</td>
                        <td>{{$var["lesson_status_str"]}}</td>
                        <td>{{$var["record_monitor_class"]}}</td>
                        <td>{{$var["record_info"]}}</td>
                        <td>{{$var["trial_train_status_str"]}}</td>
                        <td>{{$var["acc"]}}</td>
                        <td >
                            <div
                                {!! \App\Helper\Utils::gen_jquery_data($var) !!}
                            >
                                <a class="fa-video-camera opt-play" title="回放"></a>
                                <a class="opt-play-new" title="回放-new">回放-new</a>
                                <a class="btn fa fa-link opt-out-link" title="对外视频发布链接"></a>
                                <a class="btn fa fa-qrcode  opt-qr-pad-at-time "
                                   data-type="leoedu://meeting.leoedu.com/meeting="
                                   title="pad实时课程二维码" ></a>
                                <a class="btn fa fa-qrcode  opt-qr-pad "
                                   data-type="leoedu://video.leoedu.com/video="
                                   title="视频播放二维码" > </a>
                                @if(in_array($acc,["adrian","jim",$var['acc'],"jack"]))
                                    <a class="opt-edit" title="更改状态">审核 </a>
                                @endif
                                @if($var["trial_train_status"]>0)
                                    <a class="opt-confirm-score" title="审核详情">审核详情</a>
                                @endif
                                @if(in_array($acc,["coco","jack","seth","展慧东","CoCo老师","amyshen"]))
                                    <a class="opt-reset-acc" >重置审核人</a>
                                    <a class="opt-set-new-lesson" >重新排课</a>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
