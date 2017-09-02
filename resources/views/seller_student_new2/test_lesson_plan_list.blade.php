@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" >
     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
     var g_adminid= "{{$adminid}}" ;
    </script>
    <style>
     .input-group{
         width:100%;
     }
     .input-group-w145{
         width:145px !important;
     }
    </style>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-6"  data-title="时间段">
                    <div id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人</span>
                        <input class="opt-change form-control" id="id_require_adminid" />
                    </div>
                </div>
                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">接受</span>
                        <select class="opt-change form-control" id="id_accept_flag" >
                            <option  value="-2" > 接受+未设置 </option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">测试用户</span>
                        <select class="opt-change form-control" id="id_is_test_user" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <select class="opt-change form-control" id="id_test_lesson_student_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">学生</span>
                        <input class="opt-change form-control" id="id_userid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" id="id_teacherid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">角色</span>
                        <select class="opt-change form-control" id="id_require_admin_type" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">分类</span>
                        <select class="opt-change form-control" id="id_ass_test_lesson_type" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">课时确认</span>
                        <select class="opt-change form-control" id="id_success_flag" >
                            <option value="-2">未设置+成功</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">出错</span>
                        <select class="opt-change form-control" id="id_test_lesson_fail_flag" >
                            <option value="-2">付老师工资-全部</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">申请更改状态</span>
                        <select class="opt-change form-control" id="id_seller_require_change_flag" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" id="id_assign_all">
                    <div class="input-group ">
                        <span class="input-group-addon">是否分配教务</span>
                        <select class="opt-change form-control" id="id_require_assign_flag" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">教务排课状况</span>
                        <select class="opt-change form-control" id="id_jw_test_lesson_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >教务老师</span>
                        <select  id="id_jw_teacher" class="opt-change"  >
                            <option value="-1" > 全部 </option>
                            @foreach ( $jw_teacher_list as $var )
                                <option value="{{$var["uid"]}}"> {{$var["account"]}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">TMK</span>
                        <input class="opt-change form-control" id="id_tmk_adminid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">正式课</span>
                        <select class="opt-change form-control" id="id_has_1v1_lesson_flag" >
                            <option value="-3">未确认</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-3 col-md-1">
                    <div class="input-group ">
                        <button class="btn btn-primary " id="id_add">试听申请</button>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="input-group">
                        <button class="btn btn-primary " id="id_opt_require">申请未排未分配</button>
                        <button class="btn btn-primary " id="id_opt_require_assign_done">申请未排已分配</button>
                        <button class="btn btn-primary " id="id_opt_require_assign_done_gz">已挂起</button>
                        <button class="btn btn-primary " id="id_opt_fail_lesson">失败课程</button>
                        <button class="btn btn-primary " id="id_opt_seller_require_change">申请更改未处理</button>
                    </div>
                </div>
                <div class="col-xs-3 col-md-1">
                    <div class="input-group ">
                        <button class="btn btn-success" id="id_set_accept_adminid">分配未排</button>
                    </div>
                </div>
                <div class="col-xs-3 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-danger" id="id_grab_lesson">抢单功能</button>
                    </div>
                </div>
                <div class="col-xs-3 col-md-1">
                    <div class="input-group ">
                        @if ($admin_work_status==0 )
                            <button class="btn btn-warning" id="id_test_lesson_assign">开始排课</button>
                        @else
                            <button class="btn btn-danger " id="id_test_lesson_assign">结束排课</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td style="width:10px">
                            <a href="javascript:;" id="id_select_all" title="全选">全</a>
                            <a href="javascript:;" id="id_select_other" title="反选">反</a>
                        </td>
                        <td style="display:none;">手机号</td>
                        <td >基本信息 </td>
                        <td style="display:none">抢单状态</td>
                        <td style="min-width:200px;">申请信息</td>
                        <td style="display:none;">申请时间</td>
                        <td style="display:none;">申请人</td>
                        <td style="display:none;">来源</td>
                        <td style="display:none;">姓名</td>
                        <td style="width:70px">回访状态</td>
                        <td style="display:none;">用户备注</td>
                        <td style="display:none;">年级</td>
                        <td style="display:none;" >科目</td>
                        <td style="display:none;">是否有pad</td>
                        <td style="display:none;">期待试听时间</td>
                        <td style="display:none;">回访记录</td>
                        <td style="display:none;">学校</td>
                        <td style="min-width:300px;">试听需求</td>
                        <td style="display:none;">试卷</td>
                        <td style="min-width:250px">排课信息</td>
                        <td style="display:none;">老师</td>
                        <td style="display:none;">实际上课时间</td>
                        <td style="display:none;" class="limit-require-info">限课特殊申请情况</td>
                        <td style="width:130px" >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td>
                                <input type="checkbox" class="opt-select-item "
                                       data-requireid="{{$var["require_id"]}}"
                                       data-lesson_time="{{$var["stu_request_test_lesson_time"]}}"
                                       data-grade="{{$var["grade_str"]}}"
                                       data-subject="{{$var["subject_str"]}}"
                                       data-textbook="{{$var["editionid_str"]}}"
                                />
                            </td>
                            <td > {{$var["phone"]}} </td>
                            <td >
                                序号: {{$var["id"]}} <br/>
                                {{$var["phone"]}} <br/>
                                {{$var["phone_location"]}} <br/>
                                姓名：{{$var["nick"]}}  <br/>
                                年级：{{$var["grade_str"]}}  <br/>
                                科目：{{$var["subject_str"]}}  <br/>
                                PAD：{{$var["has_pad_str"]}}  <br/>
                                Pad测试：{{$var["stu_test_ipad_flag_str"]}}<br/>
                            </td>
                            <td class="grab_status"> {{$var["grab_status_str"]}} </td>
                            <td >
                                TMK ： {{$var["tmk_admin_nick"]}}<br/>
                                申请时间： {{$var["require_time"]}}<br/>
                                申请人：{{$var["require_admin_nick"]}}<br/>
                                @if($var["require_admin_type"]==2)
                                来源：{{$var["origin"]}}<br/>
                                @else
                                来源：{{$var["ass_test_lesson_type_str"]}}<br/>
                                @endif
                                学校：{{$var["school"]}}<br/>
                                教材：{{$var["editionid_str"]}}<br/>
                                试卷：{!!  $var["stu_test_paper_flag_str"]!!}<br/>
                                <!-- 成绩情况: {{$var["stu_score_info"]}} <br/> -->
                                性格信息: {{$var["stu_character_info"]}} <br/>
                                高意向: {!! $var["intention_level_str"] !!} <br/>
                            </td>
                            <td >{{$var["require_time"]}}</td>
                            <td > {{$var["require_admin_nick"]}}</td>
                            <td class="">{{$var["origin"]}}</td>
                            <td class="">{{$var["nick"]}}</td>
                            <td class="">{{$var["test_lesson_student_status_str"]}}</td>
                            <td >{{$var["user_desc"]}}</td>
                            <td class="">{{$var["grade_str"]}}</td>
                            <td >{{$var["subject_str"]}}</td>
                            <td >{{$var["has_pad_str"]}}</td>
                            <td >{{$var["stu_request_test_lesson_time"]}}</td>
                            <td >{{$var["last_revisit_msg"]}}</td>
                            <td >{{$var["school"]}}</td>
                            <td >
                                期待时间: {{$var["stu_request_test_lesson_time"]}} <br/>
                                期待时间(其它): {!!  $var["stu_request_test_lesson_time_info_str"]!!} <br/>
                                正式上课: {!!  $var["stu_request_lesson_time_info_str"]!!} <br/>
                                试听内容: {{$var["stu_test_lesson_level_str"]}} <br/>
                                试听需求:{{$var["stu_request_test_lesson_demand"]}}<br/>
                                教材：{{$var["editionid_str"]}}<br/>
                                学生成绩情况: {{$var["stu_score_info"]}} <br/>
                                @if ($var["is_green_flag"]==1)
                                    <font color="green"> 已申请绿色通道</font>
                                @endif
                            </td>
                            <td >{!!  $var["stu_test_paper_flag_str"]!!}</td>
                            <td>
                                @if ($var["is_accept_adminid"]>0)
                                   已分配教务:{{@$var["accept_account"]}} <br/>
                                   排课操作时间:{{@$var["set_lesson_time"]}} <br/>
                                @endif

                                申请是否接受: {!!  $var["accept_flag_str"] !!} <br/>
                                老师是否可看到该课程: {!!  $var["lesson_used_flag_str"] !!} <br/>
                                老师:{{$var["teacher_nick"]}} <br/>
                                上课时间:{{$var["lesson_start"]}} <br/>
                                <br/>
                                课时确认(是否成功):{!!$var["success_flag_str"]!!} <br/>
                                确认人:{!!$var["confirm_admin_nick"]!!} <br/>
                                确认时间:{!!$var["confirm_time"]!!} <br/>
                                @if ($var["success_flag"]==2)
                                    是否付工资:
                                    @if ( in_array( $var["test_lesson_fail_flag"], [1,2,3]) )
                                        <font color="red"> 付</font>
                                    @else
                                        <font > 不付</font>
                                    @endif
                                    <br/>
                                    上课4小时前取消: {{$var["fail_greater_4_hour_flag_str"]}} <br/>
                                    出错类型:{{$var["test_lesson_fail_flag_str"]}} <br/>
                                    说明:{{$var["fail_reason"]}} <br/>
                                @endif
                                @if ($var["is_require_change"]==1)
                                    <br>销售请求更换上课时间<br/>
                                    申请时间:{{$var["seller_require_change_time_str"]}} <br/>
                                    状态: {{$var["seller_require_change_flag_str"]}} <br/>
                                    目标上课时间:{{@$var["require_change_lesson_time_str"]}} <br/>
                                @endif

                                @if ($var["assigned_lesson_count"])
                                    <font color="green"> 正式课 </font>: {{$var["assigned_lesson_count"]/100}} 节
                                @endif

                                @if ($var["test_lesson_order_fail_flag"])
                                    <font color="red">未扩课原因:</font> {{$var["test_lesson_order_fail_flag_str"] }}
                                @endif
                                <br>

                                @if ($var["limit_require_flag"]==1)
                                    <font color="blue">已做限课特殊申请:</font>
                                    状态:{{$var["limit_accept_flag_str"]}}
                                @endif



                            </td>
                            <td >{{$var["teacher_nick"]}}</td>
                            <td >{{$var["lesson_start"]}}</td>
                            <td >
                                申请原因:{{$var["limit_require_reason"]}}<br>
                                申请人:{{$var["limit_require_account"]}}<br>
                                申请时间:{{$var["limit_require_time_str"]}}<br>
                                老师:{{$var["limit_require_tea_nick"]}}<br>
                                限排理由:{{$var["limit_plan_lesson_reason"]}}<br>
                                处理人:{{$var["limit_require_send_account"]}}<br>
                                处理时间:{{$var["limit_accept_time_str"]}}<br>
                                状态:{{$var["limit_accept_flag_str"]}}
                            </td>
                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <a title="用户信息" class="fa-user opt-user show_flag"></a>
                                    <a title="查看回访" class="fa-comments opt-return-back-list show_flag"></a>
                                    <a title="下载试卷" class="fa-download opt-download-test-paper show_flag"></a>
                                    @if($var['grab_status']==0 || $var['grab_status']==1)
                                        <a title="添加抢单库" class="opt-add-grab-list show_flag">抢</a>
                                    @endif
                                    @if ($cur_page =="ass_test_lesson_list" || $cur_page =="ass_test_lesson_list_tran" )
                                        <a title="上传试卷" id="upload-test-paper-{{$var["require_id"]}}" class="fa-upload opt-upload-test-paper show_flag"></a>
                                        <a title="编辑" class=" fa-edit opt-edit show_flag"></a>
                                        <a title="删除" class=" fa-trash-o opt-del show_flag"></a>
                                    @else
                                        <a title="排课" class=" opt-set-lesson-new show_flag">驳回&排课 </a>
                                        <a title="换老师/时间" class=" fa-male  opt-set-teacher-time show_flag"></a>
                                    @endif
                                    <a title="确认课时" class="btn fa fa-gavel opt-confirm show_flag" ></a>
                                    <a title="用户试听信息" class="fa-info  opt-user-info show_flag"> </a>
                                    <a title="查看试听课老师反馈" class="fa fa-bookmark opt-get_stu_performance show_flag"></a>
                                    <a title="该申请的所有排课记录" class="fa-list fa-bookmark opt-lesson-list show_flag"></a>
                                    <a title="回放" class="fa-video-camera opt-play show_flag"></a>
                                    <a title="助教 试听成功 绑定课程包" class="fa-smile-o   opt-binding-course-order show_flag"></a>
                                    <a title="助教 试听失败 设置原因" class=" fa-frown-o  opt-test_lesson_order_fail show_flag"></a>
                                    <a title="确认更换上课时间" class="fa-check-square-o opt-accept-seller-require-change show_flag"></a>
                                    @if ($var["jw_test_lesson_status"]==0 )
                                        <a title="挂起" class="fa-pause opt-test-lesson-gz show_flag"></a>
                                    @elseif ($var["jw_test_lesson_status"]==2)
                                        <a title="解除挂起" class="fa-pause opt-test-lesson-gz show_flag"></a>
                                    @endif
                                    @if ($var["teacherid"] != null)
                                    <a title="增加老师取消课程记录" class="show_flag icon-warning-sign opt-teacher-cancel-class-confirm"></a>
                                    @endif
                                    <a title="限课特殊申请" class="fa-user-md opt-limit-lesson-require show_flag"></a>
                                    @if($var["limit_accept_flag"]==0)
                                        <a title="同意申请" class=" opt-set-limit-require-agree show_seller" >同意 </a>
                                        <a title="驳回申请" class=" opt-set-limit-require-refuce show_seller" >驳回 </a>
                                    @endif
                                    <a title="匹配老师" class="opt-match-teacher show_flag">匹配老师</a>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
    </section>
@endsection
