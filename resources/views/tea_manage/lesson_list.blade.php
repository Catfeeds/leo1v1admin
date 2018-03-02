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
    <style>
     .ui-corner-all {
         border-radius: 0px;
     }

     @media screen and (max-width: 480px) {
     .content {
         padding-left: 2px;
     }
     }
    </style>
    <script type="text/javascript" >
     var self_groupid = "{{$self_groupid}}";
     var is_group_leader_flag   = "{{$is_group_leader_flag}}";
    </script>
    <section class="content">
        <div class="row row-query-list">
            <div class="col-xs-12 col-md-4" data-title="时间段">
                <div id="id_date_range"> </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">类型</span>
                    <select class="opt-change form-control " id="id_lesson_type" >
                        <option value="-2">正式1v1课程</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">课堂状态</span>
                    <select class="opt-change form-control " id="id_lesson_status" >
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">是否有效</span>
                    <select class="opt-change form-control" id="id_lesson_user_online_status" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">学科</span>
                    <select class="opt-change form-control " id="id_subject" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span> 测试用户 </span>
                    <select id="id_is_with_test_user" class="opt-change" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span> 课堂反馈</span>
                    <select id="id_has_performance" class="opt-change" >
                        <option value="-1">[全部]</option>
                        <option value="0">未评价</option>
                        <option value="1">已评价</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >老师</span>
                    <input id="id_teacherid" class="opt-change"  />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >学生</span>
                    <input id="id_studentid"  class="opt-change" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >助教</span>
                    <input id="id_assistantid"  class="opt-change" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">课时设置</span>
                    <input id="id_confirm_flag"  placeholder="课时" >
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <input  placeholder="年级" id="id_grade" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">销售</span>
                    <input id="id_test_seller_id" class="opt-change" />
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">课程取消原因类型</span>
                    <select class="opt-change form-control " id="id_lesson_cancel_reason_type" >
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <input type="text" class="form-control opt-change" data-field="origin" id="id_origin" placeholder="渠道, 回车查找" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span class="input-group-addon">课时数</span>
                    <select  id="id_lesson_count" class="opt-change"  >
                        <option value="-1" >[全部]</option>
                        <option value="100" >1</option>
                        <option value="150" >1.5</option>
                        <option value="200" >2</option>
                        <option value="300" >3</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">视频</span>
                    <select class="opt-change form-control" id="id_has_video_flag" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">删除标识</span>
                    <select class="opt-change form-control" id="id_lesson_del_flag" >
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span class="input-group-addon">全职老师分类</span>
                    <select class="opt-change form-control" id="id_fulltime_teacher_type" >
                    </select>
                </div>
            </div>

            <div class=" col-xs-6  col-md-2">
                <div class="input-group col-sm-12">
                    <input type="text" class="opt-change " id="id_lessonid" placeholder="课程ID/l_8888y8y8 " />
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table ">
            <thead>
                <tr>
                    <td class="remove-for-xs">id</td>
                    <td style="min-width:50px">类型</td>
                    {!!\App\Helper\Utils::th_order_gen([["上课时段", "lesson_start", "th_date_range" ]])!!}
                    <td style="display:none;">上课结束时间</td>
                    <td style="display:none;">上课实际开始时间</td>
                    {!!\App\Helper\Utils::th_order_gen([["年级", "grade", "th_grade" ]])!!}
                    <td style="display:none;" >科目</td>
                    <td style="display:none;" >知识点</td>
                    <td style="display:none;">老师</td>
                    <td style="min-width:60px">老师信息</td>
                    <td style="display:none;">老师薪资类型</td>
                    <td style="display:none;">学生电话</td>
                    <td style="display:none;">家长电话</td>
                    <td style="display:none;">学生</td>
                    <td style="min-width:60px">学生/渠道</td>
                    <td style="min-width:45px">状态</td>
                    <td style="min-width:50px">老师课件</td>
                    <td style="display:none;" >老师课件URL</td>
                    <td style="display:none;" >学生课件</td>
                    <td style="display:none;" >学生课件URL</td>
                    <td style="display:none;">课堂测验</td>
                    <td style="min-width:50px">作业</td>
                    <td style="display:none;" >作业分数</td>
                    <td style="display:none;" >分数</td>
                    <td style="display:none;" >系统分数</td>
                    <td style="display:none;" >分数-教学质量</td>
                    <td style="display:none;" >分数-课件准备</td>
                    <td style="display:none;" >分数-课堂互动</td>
                    <td style="display:none;" >分数-系统稳定</td>
                    <td style="display:none;"  >举报</td>
                    <td style="display:none;" >课程录像生成时间</td>
                    <td style="display:none;" >视频</td>
                    <td style="display:none;" >PPT课件</td>
                    <td style="display:none;" >是否新增试听</td>
                    <td style="display:none;" >助教试听类型</td>
                    <td style="display:none;" >课时数</td>
                    <td >删除标示</td>
                    <td >系统判断是否有效</td>
                    <td >课程有效申请</td>
                    <td >课时确认</td>
                    <td style="display:none;" >课程取消原因</td>
                    <td style="display:none;" >课时确认人</td>
                    <td style="display:none;" >课时确认时间</td>
                    <td style="display:none;" >课时确认原因</td>
                    <td style="display:none;" >学生设备</td>
                    <td style="display:none;" >课程名称</td>
                    <td style="display:none;" >老师课件名称</td>
                    <td style="display:none;" >课程介绍</td>
                    <td style="display:none;" >评价反馈</td>
                    <td style="display:none;" >试听申请人</td>
                    <td style="display:none;" >声音记录服务器1</td>
                    <td style="display:none" >课堂扣款</td>
                    <td style="display:none" >助教</td>
                    <td style="min-width:200px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td class="lessonid">{{$var["number"]}}/<br/>{{$var["lessonid"]}}</td>
                        <td class="">{{$var["lesson_type_str"]}}</td>
                        <td class="lesson_time">{{$var["lesson_time"]}} {{$var["room_name"]}} | {{$var["current_server"]}} </td>
                        <td >{{$var["lesson_end_str"]}}</td>
                        <td >{{$var["real_begin_time_str"]}}</td>
                        <td class="grade_str">{{$var["grade_str"]}}</td>
                        <td >{{$var["subject_str"]}}</td>
                        <td style="display:none;" >{{$var["lesson_intro"]}}</td>
                        <td >
                            {{$var["tea_nick"]}}
                        </td>
                        <td class="tea_nick" data-teacherid="{{$var["teacherid"]}}" >
                            @if ( $_origin_act == "lesson_list_seller"  )
                                <a href="/teacher_info_admin/index?teacherid={{$var["teacherid"]}}" target="_blank" >{{$var["tea_nick"]}}</a>
                            @else
                                @if(@$is_tea == 0)
                                    <a href="/human_resource/index?teacherid={{$var["teacherid"]}}" target="_blank" >{{$var["tea_nick"]}}</a>
                                @else
                                    <a href="/human_resource/index_tea_qua?teacherid={{$var["teacherid"]}}" target="_blank">{{$var["tea_nick"]}}</a>
                                @endif
                            @endif
                        </td>
                        <td >
                            {{$var["teacher_money_type_str"]}}/{{$var['level_str']}}
                        </td>
                        <td > {{$var["stu_phone"]}}    </td>
                        <td > {{$var["fa_phone"]}}    </td>
                        <td > {{$var["stu_nick"]}}    </td>
                        <td class="stu_nick" data-stu_id="{{$var["stu_id"]}}">
                            <a  href="/user_manage/index?userid={{$var["stu_id"]}}" target="_blank">{{$var["stu_nick"]}}    </a>
                            /{{$var["origin"]}}</td>
                        <td class="lesson_status"><span>{{$var["lesson_status_str"]}}</span><input class="status" type="hidden" value="{{$var["lesson_status"]}}" ></td>
                        <td class="tea_cw_url" data-v="{{$var["tea_cw_status"]}}" >
                            <span>{{$var["tea_cw_status_str"]}}</span>
                            <input class="file_url" type="hidden" value="{{$var["tea_cw_url"]}}" >
                        </td>
                        <td style="display:none;" >{{$var["tea_cw_url"]}}</td>
                        <td class="stu_cw_url" data-v="{{$var["stu_cw_status"]}}" >
                            <span>{{$var["stu_cw_status_str"]}}</span>
                            <input class="file_url" type="hidden" value="{{$var["stu_cw_url"]}}" >
                        </td>
                        <td style="display:none;" >{{$var["stu_cw_url"]}}</td>
                        <td class="lesson_quiz_url" data-v="{{$var["lesson_quiz_status"]}}" >
                            <span>{{$var["lesson_quiz_status_str"]}}</span>
                            <input class="file_url" type="hidden" value="{{$var["lesson_quiz"]}}">
                        </td>
                        <td class="homework_url" data-v="{{$var["work_status"]}}" >
                            <span>{{$var["work_status_str"]}}</span><input class="file_url" type="hidden" value="{{$var["homework_url"]}}">
                            <input class="status" type="hidden" value="{{$var["work_status"]}}" >
                        </td>
                        <td  > {{$var["score"]}} </td>
                        <td  > {{$var["teacher_score"]}} </td>
                        <td  > {{$var["stu_stability"]}} </td>
                        <td class="teacher_effect" >{{$var["teacher_effect"]}}</td>
                        <td class="teacher_quality">{{$var["teacher_quality"]}}</td>
                        <td class="teacher_interact" >{{$var["teacher_interact"]}}</td>
                        <td  >{{$var["stu_stability"]}}</td>
                        <td  > {{$var["is_complained_str"]}} </td>
                        <td  > {{$var["lesson_upload_time"]}} </td>
                        <td > {{$var["lesson_vedio_flag_str"]}} </td>
                        <td > {{$var["use_ppt"]}} </td>
                        <td > {{$var["new_test_listen"]}} </td>
                        <td > {{$var["ass_test_lesson_type_str"]}} </td>
                        <td > {{$var["lesson_count"]/100}}  </td>
                        <td > {!!  $var["lesson_del_flag_str"] !!}</td>
                        <td > {!!  $var["lesson_user_online_status_str"]!!} </td>
                        <td > {!!  $var["require_lesson_success_flow_status_str"]!!} </td>
                        @if($var["lesson_type"]==2)
                            <td > {{$var["success_flag_str"]}} 1</td>
                            <td > {{$var["test_lesson_fail_flag_str"]}} </td>
                            <td > {{$var["test_confirm_admin_nick"]}} </td>
                            <td > {{$var["test_confirm_time"]}} </td>
                            <td > {{$var["fail_reason"]}} </td>
                        @else
                            <td > {{$var["confirm_flag_str"]}} </td>
                            <td > {{$var["lesson_cancel_reason_type_str"]}} </td>
                            <td > {{$var["confirm_admin_nick"]}} </td>
                            <td > {{$var["confirm_time"]}} </td>
                            <td > {{$var["confirm_reason"]}} </td>
                        @endif
                        <td > {{$var["stu_user_agent"]}} </td>
                        <td > {{$var["lesson_name"]}} </td>
                        <td > {{$var["tea_cw_name"]}} </td>
                        <td > {{$var["lesson_intro"]}} </td>
                        <td > {{$var["performance"]}} </td>
                        <td > {{$var["require_admin_nick"]}} </td>
                        <!-- <td > {{$var["cc_account"]}} </td> -->
                        <td >
                            {{$var["record_audio_server1"]}} <br/>
                            count:{{ $var["pcm_file_count"]}} <br/>
                            size:{{$var["pcm_file_all_size"]}} <br/>
                        </td>
                        <td > {{$var["lesson_deduct"]}} </td>
                        <td > {{$var["assistant_nick"]}} </td>
                        <td class="remove-for-xs">
                            <div class="opt"
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="btn fa fa-video-camera opt-play" title="回放"></a>
                                <a class="btn fa fa-download opt-download" title="下载"></a>
                                <a class="btn fa fa-upload opt-upload" title="上传"></a>
                                <!-- <a class="btn fa fa-star opt-score-star" title="修改分数"></a> -->
                                <!-- <a class="btn fa fa-list-alt opt-small-class-or-open" title="小班课/公开课"></a> -->
                                <a class="btn fa fa-link opt-out-link" title="对外视频发布链接"></a>
                                <a class="btn fa fa-edit opt-edit-lesson-upload-time" title="生成视频配置"></a>
                                <a class="btn fa fa-qrcode  opt-qr-pad-at-time "
                                   data-type="leoedu://meeting.leoedu.com/meeting="
                                   title="pad实时课程二维码" ></a>
                                <a class="btn fa fa-qrcode  opt-qr-pad-at-time-new "
                                   data-type="leoedu://meeting.leoedu.com/meeting="
                                   title="pad实时课程二维码——新版" ></a>
                                <a class="btn fa fa-qrcode  opt-qr-pad "
                                   data-type="leoedu://video.leoedu.com/video="
                                   title="视频播放二维码" > </a>
                                <a class="btn fa fa-qrcode  opt-qr-pad-new "
                                   data-type="leoedu://video.leoedu.com/video="
                                   title="视频播放二维码——新版" > </a>
                                @if($var['lesson_type']==2)
                                    <a class="btn fa fa-gavel opt-confirm-test" title="确认课时"></a>
                                @elseif($var['lesson_type']<1000)
                                    <a class="btn fa fa-gavel opt-confirm" title="确认课时"></a>
                                @endif
                                <!-- <a class="btn fa fa-th-list opt-set_lesson_info" title="修改课堂信息"></a> -->
                                <a class="btn fa fa-th-list opt-show_teacher_comment" title="查看老师评价"></a>
                                <!-- <a class="btn fa fa-retweet opt-user-video-info" title="课堂视频回放信息"></a> -->
                                <a class="opt-enable_video" title="切换课堂视频开启状态">视</a>
                                <a class="opt-send_email" title="发送讲义作业到指定邮件">邮</a>
                                <a class="opt-require_set_confirm_flag_4" title="申请老师付工资，学生不扣课时" >申</a>
                                <a class="opt-require_lesson_success" title="申请课程成功" >申</a>
                                    <a class="opt-seller-ass-record-new" title="教学质量反馈" >馈</a>
                                <a class="fa-list-alt opt-log-list" title="登录日志"></a>
                                @if($var['lesson_type']==2)
                                    <a class="btn opt-show_stu_request" title="查看学生试听需求">需</a>
                                @endif
                                <a class="fa fa-list-alt opt-manage-all" title="课程管理信息汇总" ></a>
                                <!-- <a class="fa opt-modify-lesson-time" title="处理调课申请" >调课</a> -->
                                <a class="fa-sitemap opt-set-server " title="xmpp" ></a>
                                @if(in_array($_account_role,[9,12]))
                                    <a class="opt-first-lesson-record-new" >质监反馈-new</a>
                                @endif
                                @if(in_array($_account,["jack"]))
                                    <a class="opt-seller-ass-record" title="教学质量反馈" >馈-old</a>
                                    <a class="opt-first-lesson-record" >质监反馈-old</a>
                                @endif
                                <a class="opt-download-zip" >PPT讲义下载</a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
        <div style="display:none;" >
            <div id="id_lesson_log">
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group">
                            <select class="opt-userid form-control" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group ">
                            <select class="opt-server-type form-control"  >
                                <option value="-1" > 不限 </option>
                                <option value="1" > webrtc</option>
                                <option value="2" > xmpp</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr/>
                <table   class="table table-bordered "   >
                    <tr>  <th> 时间 <th>角色 <th>用户id <th>服务 <th> 进出 <th> ip </tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>


        <div >
            <div class="row">
                <div class="col-xs-6 col-md-10">
                    有效课时：{{$lesson_count_all/100}}  |  无效课时:{{$lesson_count_fail_all/100}}
                </div>
            </div>
        </div>

    </section>

    <div class="dlg_download" style="display:none">
        <div class="row">
            <div class="dlgc_lesson_info">
                <div class="col-md-5">
                    <label class="control-label">上课时间</label>
                    <span  class="lesson_time"></span>
                </div>
                <div class="col-md-3">
                    <label class="control-label">老师</label>
                    <span  class="tea_nick"></span>
                </div>
                <div class="col-md-3">
                    <label class="control-label">学生</label>
                    <span  class="stu_nick"></span>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">下载教师版课件</span>
                    <a href="javascript:;" class="btn fa fa-download opt-teacher-url"></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">下载学生版课件</span>
                    <a href="javascript:;" class="btn fa fa-download opt-student-url"></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">下载本次课作业</span>
                    <a href="javascript:;" class="btn fa fa-download opt-homework-url"></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">下载本次课测验</span>
                    <a href="javascript:;" class="btn fa fa-download opt-quiz-url"></a>
                </div>
            </div>


        </div>

        <div class="dlg_upload" style="display:none">
            <div class="row">
                <div class="dlgc_lesson_info">
                    <div class="col-md-5" style="display:none">
                        <label class="control-label" >课程ID</label>
                        <span  class="lessonid"></span>
                    </div>
                    <div class="col-md-5">
                        <label class="control-label">上课时间</label>
                        <span  class="lesson_time"></span>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">老师</label>
                        <span  class="tea_nick"></span>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">学生</label>
                        <span  class="stu_nick"></span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row" style="margin-top:15px;">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">上传教师版课件</span>
                        <a href="javascript:;" class="btn fa fa-upload opt-teacher-url"></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">上传学生版课件</span>
                        <a href="javascript:;" class="btn fa fa-upload opt-student-url"></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">上传本次课作业</span>
                        <a href="javascript:;" class="btn fa fa-upload opt-homework-url"></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">上传本次课测验</span>
                        <a href="javascript:;" class="btn fa fa-upload opt-quiz-url"></a>
                    </div>
                </div>
            </div>

            <div class="row" >
                <p style="height:5px;width:100%;background:#eee;font-size:5px;position:absolute;left:0;top:0;"></p>
                <p class="upload_process_info" style="height:5px;width:0;background:#0bceff;font-size:5px;position:absolute;left:0;top:0;z-index:2;"></p>
            </div>
        </div>

        <div class="dlg_score_star" style="display:none">
            <div class="row">
                <div class="dlgc_lesson_info">
                    <div class="col-md-5">
                        <label class="control-label">上课效果</label>
                        <span  class="effect"></span>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">课件质量</label>
                        <span  class="quality"></span>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">课堂互动</label>
                        <span  class="interact"></span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">上课效果</span>
                        <input class="form-control new_effect" type="text" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">课件质量</span>
                        <input class="form-control new_quality" type="text" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">课堂互动</span>
                        <input class="form-control new_interact" type="text" />
                    </div>
                </div>
            </div>
        </div>

@endsection
