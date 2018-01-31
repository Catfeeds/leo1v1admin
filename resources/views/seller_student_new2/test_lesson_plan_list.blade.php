@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax_test.js"></script>
    <script type="text/javascript" >
     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
     var g_adminid= "{{$adminid}}" ;
    </script>
    <script type="text/javascript">
     var _KDA = _KDA || [];
     window._KDA = _KDA;
     (function(){
         var _dealProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
         var _sdkURL = _dealProtocol + "deal-admin.kuick.cn/sdk/v1/";
         _KDA.push(['SDK_URL', _dealProtocol + "deal-admin.kuick.cn/sdk/v1/"]);
         _KDA.push(['APP_KEY', '128994ec-ba97-4a28-9ecc-faa1b00eba33']);
         _KDA.push(['APP_SECRET', 'e1888aa6-f527-4477-ae9b-409fca29f44c']);
         (function() {
             var dealAdmin = document.createElement('script');
             dealAdmin.type='text/javascript';
             dealAdmin.async = true;
             dealAdmin.src = _sdkURL + 'kuickdealadmin-pc.min.js';
             var s = document.getElementsByTagName('script')[0];
             s.parentNode.insertBefore(dealAdmin, s);
         })();
     })();

     function onKDAReady(){
         // 客户下拉组件
         KDAJsSdk.widget.createCustomerDropMenuWidget({
             selector: ".kda-customer-widget",
         });
         $(function(){
             var $title=$(".kda-customer-widget .KDA_customerDropMenuName "  );
             $title.text("K");
             $(".kda-customer-widget .KDA_customerDropMenuCon"  ).attr( "style" ,"width:30px;");
         });

     }

     if (typeof KDAJsSdk == "undefined"){
         if(document.addEventListener){
             document.addEventListener('KDAReady', onKDAReady, false);
         } else if (document.attachEvent){
             document.attachEvent('KDAReady', onKDAReady);
             document.attachEvent('onKDAReady', onKDAReady);
         }
     } else {
         onKDAReady();
     }
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
                    <div class="input-group ">
                        <span class="input-group-addon">排课类型</span>
                        <select class="opt-change form-control" id="id_lesson_plan_style" >
                            <option value="-1">全部</option>
                            <option value="1">top25</option>
                            <option value="2">绿色通道</option>
                            <option value="3">常规排课</option>
                            <option value="4">抢课排课</option>
                            <option value="5">驳回重排</option>
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
                        @if(in_array($adminid,[99,831,349,448,68,1093,1122,188,60,1118]))
                            <button class="btn btn-primary " id="id_add_new">试听申请-new</button>
                        @endif
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
                        <td style="display:none;">家长wxid</td>
                        <td style="display:none;">期待试听时间</td>
                        <td style="display:none;">回访记录</td>
                        <td style="display:none;">学校</td>
                        <td style="min-width:300px;">试听需求</td>
                        <td style="display:none;">试卷</td>
                        <td style="min-width:250px">排课信息</td>
                        <td style="display:none;">老师</td>
                        <td style="display:none;">实际上课时间</td>
                        <td class="limit-require-info" style="display:none;">限课特殊申请情况</td>
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
                            <td > {{$var["phone_ex"]}} </td>
                            <td >
                                序号: {{$var["id"]}} <br/>
                                {{$var["phone_ex"]}} <br/>
                                {{$var["phone_location"]}} <br/>
                                姓名：{{$var["nick"]}}  <br/>
                                性别：{{$var["gender_str"]}}  <br/>
                                年级：{{$var["grade_str"]}}  <br/>
                                科目：{{$var["subject_str"]}}  <br/>
                                PAD：{{$var["has_pad_str"]}}  <br/>
                                Pad测试：{{$var["stu_test_ipad_flag_str"]}}<br/>
                                家长姓名：{{$var["parent_name"]}}
                            </td>
                            <td class="grab_status"> {{$var["grab_status_str"]}} </td>
                            <td >
                                @if($var["use_new_flag"]==0 || ($var["use_new_flag"]==1 && $var["new_demand_flag"]==0 ))
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
                                @elseif($var["use_new_flag"]==1 && $var["new_demand_flag"]==1)
                                    TMK ： {{$var["tmk_admin_nick"]}}<br/>
                                    申请时间： {{$var["require_time"]}}<br/>
                                    申请人：{{$var["require_admin_nick"]}}<br/>
                                    @if($var["require_admin_type"]==2)
                                        来源：{{$var["origin"]}}<br/>
                                    @else
                                        来源：{{$var["ass_test_lesson_type_str"]}}<br/>
                                    @endif
                                    学校：{{$var["school"]}}<br/>
                                    住址：{{$var["address"]}}<br/>
                                    教材：{{$var["editionid_str"]}}<br/>
                                    试卷：{!!  $var["stu_test_paper_flag_str"]!!}<br/>
                                    近期成绩: {{$var["recent_results"]}} <br/>
                                    班级排名: {{$var["class_rank"]}} <br/>
                                    年级排名: {{$var["grade_rank"]}} <br/>
                                    学习习惯  : {{$var["study_habit"]}} <br/>
                                    兴趣爱好  : {{$var["interests_and_hobbies"]}} <br/>
                                    性格特点  : {{$var["character_type"]}} <br/>
                                    所需老师风格  : {{$var["need_teacher_style"]}} <br/>
                                @endif
                            </td>
                            <td >{{$var["require_time"]}}</td>
                            <td > {{$var["require_admin_nick"]}}</td>
                            <td >{{$var["origin"]}}</td>
                            <td >{{$var["nick"]}}</td>
                            <td >{{$var["test_lesson_student_status_str"]}}</td>
                            <td >{{$var["user_desc"]}}</td>
                            <td >{{$var["grade_str"]}}</td>
                            <td >{{$var["subject_str"]}}</td>
                            <td >{{$var["has_pad_str"]}}</td>
                            <td >{{@$var["p_wx_openid_str"]}}</td>
                            <td >{{$var["stu_request_test_lesson_time"]}}</td>
                            <td >{{$var["last_revisit_msg"]}}</td>
                            <td >{{$var["school"]}}</td>
                            <td >
                                期待时间: {{$var["stu_request_test_lesson_time"]}} <br/>
                                期待结束时间: {{$var["curl_stu_request_test_lesson_time_end"]}} <br/>
                                @if($var["use_new_flag"]==0 || ($var["use_new_flag"]==1 && $var["new_demand_flag"]==0 ))
                                    期待时间(其它): {!!  $var["stu_request_test_lesson_time_info_str"]!!} <br/>
                                    正式上课: {!!  $var["stu_request_lesson_time_info_str"]!!} <br/>
                                    试听内容: {{$var["stu_test_lesson_level_str"]}} <br/>
                                    试听需求:{{$var["stu_request_test_lesson_demand"]}}<br/>
                                    教材：{{$var["editionid_str"]}}<br/>
                                    学生成绩情况: {{$var["stu_score_info"]}} <br/><br/>
                                @elseif($var["use_new_flag"]==1 && $var["new_demand_flag"]==1)
                                    升学目标:{{$var["academic_goal_str"]}} <br/>
                                    应试压力:{{$var["test_stress_str"]}} <br/>
                                    升学学校要求:{{$var["entrance_school_type_str"]}} <br/>
                                    课外提高:{{$var["extra_improvement_str"]}} <br/>
                                    习惯重塑:{{$var["habit_remodel_str"]}} <br/>
                                    试听内容:{{$var["stu_request_test_lesson_demand"]}}<br/>
                                    上课意向:{{ $var["intention_level_str"] }} <br/>
                                    需求急迫性:{{ $var["demand_urgency_str"] }} <br/>
                                    报价反应:{{ $var["quotation_reaction_str"] }} <br/><br/>
                                @endif
                                学情反馈:{{$var["learning_situation"]}} <br/><br/>
                                @if($var["seller_top_flag"]==1)
                                    <font color="blue"> 销售top25</font><br/>
                                @endif
                                @if($var["is_green_flag"]==1)
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
                                @if($var["grab_flag"]==1)
                                    是否抢课产生:{{$var["grab_flag_str"]}}<br>
                                @endif
                                <br/>
                                课时确认(是否成功):{!!$var["success_flag_str"]!!} <br/>
                                确认人:{!!$var["confirm_admin_nick"]!!} <br/>
                                确认时间:{!!$var["confirm_time"]!!} <br/>
                                试听课排课状态:{!!$var["accept_status_str"]!!} <br/>
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
                                    <font color="green">正式课</font>: {{$var["assigned_lesson_count"]/100}} 节
                                @endif
                                @if ($var["test_lesson_order_fail_flag"])
                                    <font color="red">未扩课原因:</font> {{$var["test_lesson_order_fail_flag_str"] }}
                                @endif
                                <br>
                                @if ($var["limit_require_flag"]==1)
                                    <font color="blue">已做限课特殊申请:</font>
                                    状态:{{$var["limit_accept_flag_str"]}}
                                @endif
                                <br>{!!  $var["rebut_info"]!!}<br/>
                            </td>
                            <td >{{$var["teacher_nick"]}}</td>
                            <td >{{$var["lesson_start"]}}</td>
                            <td class="limit-require-info">
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
                                <div class="opt-div" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                                    <a title="用户信息" class="fa-user opt-user show_flag"></a>
                                    <a title="查看回访" class="fa-comments opt-return-back-list show_flag"></a>
                                    <a title="下载试卷" class="fa-download opt-download-test-paper show_flag"></a>
                                    <!-- <a title="添加抢单库" class="opt-add-grab-list show_flag">抢</a> -->
                                    @if ($cur_page =="ass_test_lesson_list" || $cur_page =="ass_test_lesson_list_tran" )
                                        <a title="上传试卷" id="upload-test-paper-{{$var["require_id"]}}" class="fa-upload opt-upload-test-paper show_flag"></a>
                                        <a title="编辑" class=" fa-edit opt-edit show_flag"></a>
                                        @if(in_array($adminid,[99,831,349,448,68,1093,1122,188,60,1118]) || $account_role==12)
                                            <a title="编辑-new" class=" fa-edit opt-edit-new show_flag"></a>
                                        @endif
                                        <a title="删除" class=" fa-trash-o opt-del show_flag"></a>
                                    @else
                                        <!-- <a title="排课" class="opt-set-lesson-new show_flag">驳回&排课</a> -->
                                        <a title="换老师/时间" class="fa-male opt-set-teacher-time show_flag"></a>
                                    @endif
                                    <a title="确认课时" class="btn fa fa-gavel opt-confirm show_flag" ></a>
                                    <a title="用户试听信息" class="fa-info  opt-user-info show_flag"> </a>
                                    <a title="查看试听课老师反馈" class="fa fa-bookmark opt-get_stu_performance show_flag"></a>
                                    <a title="该申请的所有排课记录" class="fa-list fa-bookmark opt-lesson-list show_flag"></a>
                                    <a title="助教 试听成功 绑定课程包" class="fa-smile-o   opt-binding-course-order show_flag"></a>
                                    <a title="助教 试听失败 设置原因" class=" fa-frown-o  opt-test_lesson_order_fail show_flag"></a>
                                    <a title="确认更换上课时间" class="fa-check-square-o opt-accept-seller-require-change show_flag"></a>
                                    @if ($var["jw_test_lesson_status"]==0 )
                                        <a title="挂起" class="fa-pause opt-test-lesson-gz show_flag"></a>
                                    @elseif ($var["jw_test_lesson_status"]==2)
                                        <a title="解除挂起" class="fa-pause opt-test-lesson-gz show_flag"></a>
                                    @endif
                                    <!-- <a title="增加老师取消课程记录" class="show_flag opt-teacher-cancel-class-confirm"></a> -->
                                    <a title="限课特殊申请" class="fa-user-md opt-limit-lesson-require show_flag"></a>
                                    @if($var["limit_accept_flag"]==0)
                                        <a title="同意申请" class=" opt-set-limit-require-agree show_seller" >同意 </a>
                                        <a title="驳回申请" class=" opt-set-limit-require-refuce show_seller" >驳回 </a>
                                    @endif
                                    <!-- <a title="匹配老师" class="opt-match-teacher show_flag">匹配老师</a> -->
                                    <a title="新版排课" class="select-teacher-for-test-lesson">新版排课</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
        <div style="display:none;" id="id_dlg_post_user_info_new_two">
            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>基本信息&nbsp<font style="color:red">标记红色星号*的为必填内容</font></span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学员姓名：</span>
                                <input type="text" class=" form-control "  id="id_stu_nick_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">家长姓名：</span>
                                <input type="text" class=" form-control "  id="id_par_nick_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学生性别：</span>
                                <select id="id_stu_gender_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学生年级：</span>
                                <select id="id_stu_grade_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">　　<font style="color:red">*</font>&nbsp科目：</span>
                                <select id="id_stu_subject_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">在读学校：</span>
                                <input type="text" id="id_stu_school_new"  class="form-control"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp教材版本：</span>
                                <select id="id_stu_editionid_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp省</span>
                                <select class="form-control" id="province" name="province">
                                </select>

                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp市</span>
                                <select class="form-control" id="city" name="city">
                                </select>

                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp区(县)</span>
                                <select class="form-control" id="area" name="area">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>学习情况</span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp近期成绩：</span>
                                <input type="text" class=" form-control "  id="id_recent_results_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 " style="">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp是否进步：</span>
                                <select id="id_advice_flag_new" class=" form-control "   >
                                </select>
                            </div>

                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp班级排名：</span>
                                <input type="text" class=" form-control "  id="id_class_rank_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon">年级排名：</span>
                                <input type="text" class=" form-control "  id="id_grade_rank_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学目标：</span>
                                <select id="id_academic_goal_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>非智力因素</span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学习习惯：</span>
                                <input type="text" class=" form-control "  id="id_study_habit_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon">兴趣爱好：</span>
                                <input type="text" class=" form-control "  id="id_interests_hobbies_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"> <font style="color:red">*</font>&nbsp性格特点：</span>
                                <input type="text" class=" form-control "  id="id_character_type_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师要求：</span>
                                <input type="text" class=" form-control "  id="id_need_teacher_style_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师所在省</span>
                                <select class="form-control" id="tea_province" name="province">
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师所在市</span>
                                <select class="form-control" id="tea_city" name="city">
                                </select>

                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师所在区(县)</span>
                                <select class="form-control" id="tea_area" name="area">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>试听需求</span>
                </div>
                <div class="col-xs-12 col-md-9  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-12 ">
                            <div class="input-group ">
                                <span class="input-group-addon" >　<font style="color:red">*</font>&nbsp　试听内容：</span>
                                <textarea class="form-control" style="height:115px;" id="id_stu_request_test_lesson_demand_new" > </textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-3  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-12 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp上课意向：</span>
                                <select id="id_intention_level_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp试听时间：</span>
                                <input id="id_stu_request_test_lesson_time_new" class=" form-control "   />
                                <div class=" input-group-btn "  >
                                    <button class="btn  btn-primary " id="id_stu_reset_stu_request_test_lesson_time_new_new"  title="取消" >
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12  ">
                            <div class="input-group ">
                                <span class="input-group-addon">上传试卷：</span>
                                <input type="text" class=" form-control "  id="id_test_paper_new"   / >
                                <div class=" input-group-btn "  >
                                    <button class="btn  btn-primary upload_test_paper"  title="上传" >
                                        上传
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>核心诉求</span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp应试压力：</span>
                                <select id="id_test_stress_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学学校要求：</span>
                                <select id="id_entrance_school_type_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 " style="">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp趣味培养：</span>
                                <select id="id_interest_cultivation_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon">课外提高：</span>
                                <select id="id_extra_improvement_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">习惯重塑：</span>
                                <select id="id_habit_remodel_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>其他</span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp分类：</span>
                                <select id="id_ass_test_lesson_type_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  "  style="display:none;">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp换老师类型：</span>
                                <select id="id_change_teacher_reason_type_new" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  " style="display:none;" >
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp申请原因(图片)：</span>
                                <input type="text" class=" form-control "  id="id_change_reason_url_new"   / >
                                <div class=" input-group-btn "  >
                                    <button class="btn  btn-primary upload_change_reason_url"  title="上传" >
                                        上传
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  " style="display:none;" >
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp申请原因：</span>
                                <textarea class="form-control" style="height:75px;" id="id_change_reason_new" > </textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">&nbsp绿色通道：</span>
                                <input type="text" class=" form-control "  id="id_green_channel_teacherid_new"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学情反馈：</span>
                                <textarea class="form-control" style="height:75px;" id="id_learning_situation_new" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:none;" id="id_dlg_post_user_info_new">
            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>基本信息&nbsp<font style="color:red">标记红色星号*的为必填内容</font></span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学员姓名：</span>
                                <input type="text" class=" form-control "  id="id_stu_nick"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">家长姓名：</span>
                                <input type="text" class=" form-control "  id="id_par_nick"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学生性别：</span>
                                <select id="id_stu_gender" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学生年级：</span>
                                <select id="id_stu_grade" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">　　<font style="color:red">*</font>&nbsp科目：</span>
                                <select id="id_stu_subject" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">在读学校：</span>
                                <input type="text" id="id_stu_school"  class="form-control"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp教材版本：</span>
                                <select id="id_stu_editionid" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp省</span>
                                <select class="form-control" id="province_new" name="province">
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp市</span>
                                <select class="form-control" id="city_new" name="city">
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp区(县)</span>
                                <select class="form-control" id="area_new" name="area">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>学习情况</span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp近期成绩：</span>
                                <input type="text" class=" form-control "  id="id_recent_results"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 " style="">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp是否进步：</span>
                                <select id="id_advice_flag" class=" form-control "   >
                                </select>
                            </div>

                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp班级排名：</span>
                                <input type="text" class=" form-control "  id="id_class_rank"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon">年级排名：</span>
                                <input type="text" class=" form-control "  id="id_grade_rank"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学目标：</span>
                                <select id="id_academic_goal" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>非智力因素</span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学习习惯：</span>
                                <input type="text" class=" form-control "  id="id_study_habit"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon">兴趣爱好：</span>
                                <input type="text" class=" form-control "  id="id_interests_hobbies"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"> <font style="color:red">*</font>&nbsp性格特点：</span>
                                <input type="text" class=" form-control "  id="id_character_type"  />
                            </di                       </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师要求：</span>
                                <input type="text" class=" form-control "  id="id_need_teacher_style"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师所在省</span>
                                <select class="form-control" id="tea_province_new" name="province">
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师所在市</span>
                                <select class="form-control" id="tea_city_new" name="city">
                                </select>

                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师所在区(县)</span>
                                <select class="form-control" id="tea_area_new" name="area">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>试听需求</span>
                </div>
                <div class="col-xs-12 col-md-9  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-12 ">
                            <div class="input-group ">
                                <span class="input-group-addon" >　<font style="color:red">*</font>&nbsp　试听内容：</span>
                                <textarea class="form-control" style="height:115px;" id="id_stu_request_test_lesson_demand" > </textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-3  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-12 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp上课意向：</span>
                                <select id="id_intention_level" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp试听时间：</span>
                                <input id="id_stu_request_test_lesson_time" class=" form-control "   />
                                <div class=" input-group-btn "  >
                                    <button class="btn  btn-primary " id="id_stu_reset_stu_request_test_lesson_time"  title="取消" >
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12  ">
                            <div class="input-group ">
                                <span class="input-group-addon">上传试卷：</span>
                                <input type="text" class=" form-control "  id="id_test_paper"   / >
                                <div class=" input-group-btn "  >
                                    <button class="btn  btn-primary upload_test_paper"  title="上传" >
                                        上传
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>核心诉求</span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp应试压力：</span>
                                <select id="id_test_stress" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学学校要求：</span>
                                <select id="id_entrance_school_type" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 " style="">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp趣味培养：</span>
                                <select id="id_interest_cultivation" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon">课外提高：</span>
                                <select id="id_extra_improvement" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">习惯重塑：</span>
                                <select id="id_habit_remodel" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12  ">
                    <span>其他</span>
                </div>
                <div class="col-xs-12 col-md-12  ">
                    <div class="row">
                        <div class="col-xs-12 col-md-3  ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp分类：</span>
                                <select id="id_ass_test_lesson_type" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  "  style="display:none;">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp换老师类型：</span>
                                <select id="id_change_teacher_reason_type" class=" form-control "   >
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  " style="display:none;" >
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp申请原因(图片)：</span>
                                <input type="text" class=" form-control "  id="id_change_reason_url"  / >
                                <div class=" input-group-btn "  >
                                    <button class="btn  btn-primary upload_change_reason_url"  title="上传" >
                                        上传
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3  " style="display:none;" >
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp申请原因：</span>
                                <textarea class="form-control" style="height:75px;" id="id_change_reason" > </textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon">&nbsp绿色通道：</span>
                                <input type="text" class=" form-control "  id="id_green_channel_teacherid"  />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 ">
                            <div class="input-group ">
                                <span class="input-group-addon"><font style="color:red">*</font>&nbsp学情反馈：</span>
                                <textarea class="form-control" style="height:75px;" id="id_learning_situation" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
