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
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>

    <link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row" >              
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >学生 </span>
                        <input type="text" value=""  class="opt-change"  id="id_userid"  placeholder="" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否反馈</span>
                        <select class="opt-change form-control" id="id_record_flag" >
                        </select>
                    </div>
                </div>





            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>老师</td>
                    <td>科目</td>
                    <td>年级</td>
                    <td>学生</td>
                    <td>审核人</td>
                    <td>是否反馈</td>
                    <td>反馈时间</td>
                    
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["acc"]}} </td>
                        <td>{{@$var["record_flag_str"]}} </td>
                        <td>
                            @if($var["record_info"])
                                {{@$var["add_time_str"]}}
                            @endif
                        </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                
                                <a class="opt-fifth-lesson-video" >视频</a>
                                <a class="opt-play-new" title="回放-new">回放-new</a>
                                <a class="btn fa fa-link opt-out-link" title="对外视频发布链接"></a>
                                <a class="btn fa fa-qrcode  opt-qr-pad-at-time "
                                   data-type="leoedu://meeting.leoedu.com/meeting="
                                   title="pad实时课程二维码" ></a>
                                <a class="btn fa fa-qrcode  opt-qr-pad "
                                   data-type="leoedu://video.leoedu.com/video="
                                   title="视频播放二维码" > </a>

                                <a class="opt-fifth-lesson-record" >反馈</a>
                                @if(in_array($acc,["jack","jim","林文彬","孙瞿"]))
                                    <a class="opt-fifth-lesson-record-new" >反馈-new</a>
                                @endif

                                @if($var["record_info"])
                                    <a class="opt-fifth-lesson-record-list" >反馈详情</a>
                                @endif
                                @if(in_array($acc,["coco","jack","melody","wander","niki","seth","展慧东","CoCo老师","孙瞿"]))
                                    <a class="opt-reset-acc" >重置审核人</a>
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

