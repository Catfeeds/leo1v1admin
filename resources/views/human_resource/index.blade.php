@extends('layouts.app')
@section('content')
    <script type="text/javascript" > 
     var acc= "{{$acc}}";
     var tea_right= "{{$tea_right}}";
    </script>
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
    <script type="text/javascript" src="/page_js/teacher_freeze_limit_record.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >工资分类</span>
                        <select id="id_teacher_money_type" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >等级分类</span>
                        <select id="id_level" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >要试听课</span>
                        <select id="id_need_test_lesson_flag" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >渠道类型</span>
                        <select id="id_teacher_ref_type" class ="opt-change" >
                            <option value="-2">所有渠道</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >教师性质</span>
                        <select id="id_is_good_flag" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >绑定微信</span>
                        <select id="id_have_wx" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>新教师筛选</span>
                        <select id="id_is_new_teacher" class ="opt-change" >
                            <option value="1"> 全部老师</option>
                            <option value="2"> 最新入职老师</option>
                            <option value="3"> 最近一周入职老师</option>
                            <option value="4"> 最近两周入职老师</option>
                            <option value="5"> 最近30天入职老师</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>周试听课数</span>
                        <select id="id_test_lesson_full_flag" class ="opt-change" >
                            <option value="-1"> 全部</option>
                            <option value="1"> 满8节</option>
                            <option value="2"> 其他</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >性别</span>
                        <select id="id_gender" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >年级段</span>
                        <select id="id_grade_part_ex" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >第一科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >第二科目</span>
                        <select id="id_second_subject" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >测试老师</span>
                        <select id="id_test_user" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >排课冻结</span>
                        <select id="id_is_freeze" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >排课限制</span>
                        <select id="id_limit_plan_lesson_type" class ="opt-change" >
                            <option value="-1"> 全部</option>
                            <option value="0"> 未限制</option>
                            <option value="1"> 一周限排1节</option>
                            <option value="3"> 一周限排3节</option>
                            <option value="5"> 一周限排5节</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否反馈</span>
                        <select id="id_is_record_flag" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >新入职培训</span>
                        <select id="id_train_through_new" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="0">未通过</option>
                            <option value="1">已通过</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >暂停接试听课</span>
                        <select id="id_lesson_hold_flag" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="0">未暂停</option>
                            <option value="1">暂停</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >近两月转化率</span>
                        <select id="id_test_transfor_per" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">10%以下</option>
                            <option value="2">10%-20%</option>
                            <option value="3">20%以上</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >一周活跃度</span>
                        <select id="id_week_liveness" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">5课时以下</option>
                            <option value="2">5课时-10课时</option>
                            <option value="3">10课时-15课时</option>
                            <option value="4">15课时-20课时</option>
                            <option value="5">20课时以上</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >第一科目面试得分</span>
                        <select id="id_interview_score" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">60-80</option>
                            <option value="2">80-90</option>
                            <option value="3">90-100</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >第二科目面试得分</span>
                        <select id="id_second_interview_score" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">60-80</option>
                            <option value="2">80-90</option>
                            <option value="3">90-100</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >全职类型</span>
                        <select id="id_teacher_type" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否离职</span>
                        <select id="id_is_quit" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否请假</span>
                        <select id="id_set_leave_flag" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >推荐人</span>
                        <input id="id_reference_teacherid" class ="opt-change"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">全职老师分类</span>
                        <select class="opt-change form-control" id="id_fulltime_teacher_type" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-3">
                    <div class="input-group " >
                        <span >暂停接课老师对应教务老师</span>
                        <select  id="id_lesson_hold_flag_adminid" class="opt-change"  >
                            <option value="-1" > 全部 </option>
                            @foreach ( $jw_teacher_list as $var )
                                <option value="{{$var["uid"]}}"> {{$var["account"]}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="address" id="id_address"  placeholder="所在地、学校、姓名等 回车查找" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-3" style="display:none">
                    <div class="input-group ">
                        <span >空闲时间筛选 </span>
                        <input type="text" value=""  class="opt-change"  id="id_free_time"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >排课年级</span>
                        <select id="id_grade_plan" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >排课科目</span>
                        <select id="id_subject_plan" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_add_teacher"> 新增老师 </button>
                </div>                
                <div class="col-xs-6 col-md-1">
                    <button class="btn" id="id_limit_week_lesson_num_person" data-value="{{$week_num_person}}" >{{$week_num_person}}</button> 
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td style="display:none;">id </td>
                    <td  style="display:none;">昵称 </td>
                    <td >真实姓名</td>
                    <td style="display:none;">机构信息</td>
                    <td style="display:none;" >工资分类</td>
                    <td style="display:none;" >推荐渠道</td>
                    <td >老师身份</td>
                    <td style="display:none;">培训通过时间</td>
                    <td style="display:none;" >入库时间</td>
                    <td >入职时长</td>
                    <td style="display:none;">等级</td>
                    <td >全职</td>
                    <td id="phone_num" style="display:none;">手机号</td>
                    <td style="display:none;">邮箱</td>
                    <td>性别</td>
                    <td class="tea_address">所在地</td>
                    <td class="tea_school">学校</td>
                    <td>年级段</td>
                    <td>第一科目</td>
                    <td>第二科目</td>
                    <td style="display:none;">第二年级段</td>
                    <td style="display:none;">第三科目</td>
                    <td style="display:none;">第三年级段</td>
                    <td style="width:320px">面试评价</td>
                    <td style="display:none;">教务备注</td>
                    <td >教研备注</td>
                    <td style="display:none;">教师性质</td>
                    <td style="display:none;">版本信息</td>
                    <td style="width:320px">标签</td>
                    <td style="display:none;">擅长教材</td>
                    <td class="tea_textbook">教材版本</td>
                    <td style="display:none;">评价</td>
                    <td class="tea_is_need_test" style="display:none;">是否需要试听课</td>
                    <td style="width:220px;display:none" id="lesson_plan_week"> 当周排课</td>
                    <td > 本周剩余试听课数</td>
                    <td style="width:220px">冻结排课</td>
                    <td style="width:220px">排课限制</td>
                    <td style="display:none;">测试老师</td>
                    <td style="display:none;">新入职培训</td>
                    <td style="width:220px;display:none" class="test_transfor_per">近两月转化率</td>
                    <td style="width:220px;" class="tea_weekness">活跃度</td>
                    <td style="width:220px;">第一科目面试得分</td>
                    <td style="width:220px;">第二科目面试得分</td>
                    <td style="display:none;" class="lesson_hold_flag">暂停接试听课情况</td>
                    <td style="display:none;" class="jw_revisit_info">教务回访信息</td>
                    <td style="display:none;">添加人</td>
                    <td >面试人</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["teacherid"]}} </td>
                        <td>{{$var["teacherid"]}} <br/>{{$var["nick"]}} </td>
                        <td>{{$var["realname"]}} </td>
                        <td>{{$var["part_remarks"]}} </td>
                        <td>{{@$var["teacher_money_type_str"]}} </td>
                        <td>{{@$var["teacher_ref_type_str"]}} </td>
                        <td>{{@$var["identity_str"]}} </td>
                        <td>{{@$var["train_through_new_time_str"]}} </td>
                        <td>{{@$var["create_time_str"]}} </td>
                        <td>{{@$var["work_day"]}}</td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["teacher_type_str"]}} </td>
                        <td>
                            @if($account_role!=3)
                                <a href="javascript:;" class="show_phone" data-phone="{{$var["phone_spare"]}}" >
                                    {{@$var["phone_ex"]}}
                                </a>
                            @else
                                {{$var["phone_spare"]}}
                            @endif
                        </td>
                        <td>{{$var["email"]}}</td>
                        <td>{{@$var["gender_str"]}} </td>
                        <td>{{$var["address"]}} </td>
                        <td>{{$var["school"]}} </td>
                        <td>
                            @if(@$var["grade_start"]>0)
                                {{@$var["grade_start_str"]}} 至 {{@$var["grade_end_str"]}}
                            @else
                                {{@$var["grade_part_ex_str"]}}
                            @endif
                        </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["second_subject_str"]}} </td>
                        <td>{{@$var["second_grade_str"]}} </td>
                        <td>{{@$var["third_subject_str"]}} </td>
                        <td>{{@$var["third_grade_str"]}} </td>
                        @if($var["interview_access"])
                            <td class="content_show" data-content="{{@$var["interview_access"]}}">
                                {{@$var["interview_access"]}}
                            </td>
                        @else
                            <td></td>
                        @endif
                        <td>{{$var["tea_note"]}} </td>
                        <td>{{@$var["research_note"]}} </td>                        
                        <td>{{@$var["is_good_flag_str"]}} </td>
                        <td>{{$var["user_agent"]}} </td>
                        <td>{!! @$var["label"] !!} </td>  
                        <td>{{@$var["teacher_textbook"]}} </td>
                        <td>{{@$var["textbook_type_str"]}} </td>
                        <td>{{@$var["rate_score"]}} </td>
                        <td>{{@$var["need_test_lesson_flag_str"]}} </td>
                        <td class="content_show" data-content="{{@$var["lesson_info_week"]}}">{{@$var["lesson_info_week"]}} </td>
                        <td>{{@$var["week_left_num"]}}</td>
                        <td>
                            @if(@$var["not_grade_str"])
                                冻结年级:{{$var["not_grade_str"]}}<br>
                                操作人:{{@$var["freeze_adminid_str"]}}
                            @endif
                        </td>
                        <td>
                            @if(@$var["limit_plan_lesson_type"]>0)
                                限课详情:{{@$var["limit_plan_lesson_type_str"]}}<br>
                                操作人:{{@$var["limit_plan_lesson_account"]}}<br>
                                操作时间:{{@$var["limit_plan_lesson_time_str"]}}<br>
                            @endif
                        </td>
                        <td>{{@$var["test_user_str"]}} </td>
                        <td>{{@$var["train_through_new_str"]}} </td>
                        @if($account_role !=3 && $account_role !=4 && $account_role !=5 && $acc !="ted" && $acc != "jack" && $acc != "michael")
                            <td></td>
                            <td></td>
                        @else
                            <td >{{@$var["test_transfor_per"]}}%</td>
                            <td>一周{{@$var["week_liveness"]}}课时</td>
                        @endif
                        <td>{{@$var["interview_score"]}}</td>
                        <td>{{@$var["second_interview_score"]}}</td>
                        <td>                            
                            @if($var["lesson_hold_flag"]==1)
                                已暂停接试听课<br>
                                操作人:{{@$var["lesson_hold_flag_acc"]}}<br>
                                操作时间:{{@$var["lesson_hold_flag_time_str"]}}
                            @endif

                        </td>
                        <td>
                            回访时间:{{@$var["revisit_add_time_str"]}}<br>
                            回访内容:{{@$var["revisit_record_info"]}}<br>
                            接课意愿:{{@$var["class_will_type_str"]}}<br>
                            详情:{{@$var["class_will_sub_type_str"]}}<br>
                            @if(@$var["class_will_sub_type"]==2 || @$var["class_will_sub_type"]==5)
                                恢复接课时间:{{@$var["recover_class_time_str"]}}<br>
                            @endif
                            负责人:{{@$var["revisit_acc"]}}<br>
                        </td>
                        <td>{{$var["add_acc"]}}</td>
                        <td>{{@$var["interview_acc"]}}</td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}  >
                                <a class="fa-user opt-user-info div_show" href="/teacher_info_admin/index?teacherid={{$var["teacherid"]}}"
                                   target="_blank" title="老师信息"> </a>
                                <a class="fa-edit opt-edit div_show"  title="编辑"> </a>
                                <a class="fa-hand-o-up opt-tea-note div_show"  title="修改教务备注"> </a>
                                <a class="fa-list opt-interview-assess div_show"  title="修改面试评价"> </a>
                                <a class="opt-show-lessons div_show"  title="课程列表">课程</a>
                                <a class="opt-show-lessons-new"  title="课程列表-new">课程-new</a>
                                <a class="fa-gavel opt-set-tmp-passwd div_show"  title="临时密码"></a>
                                <a class="fa-institution opt-meeting done_create_meeting div_show" title="设置是否可以创建会议"></a>
                                <a class="opt-teacher-freeze div_show">冻结排课</a>
                                <a class="opt-freeze-list div_show">冻结排课记录</a>
                                <a class="opt-limit-plan-lesson div_show" >限制排课</a>
                                <a class="opt-limit-plan-lesson-list div_show" >限制排课记录</a>
                                <a class="opt-set-teacher-record-new div_show" >反馈</a>
                                <a class="opt-get-teacher-record div_show">反馈记录</a>
                                <a class="div_show" href="/teacher_info/free_time?teacherid={{$var["teacherid"]}}"
                                   target="_blank" title="设置空闲时间">设置空闲时间</a>
                                <a class="opt-get-teacher-lesson-hold div_show">设置暂停接试听课</a>
                                <a class="opt-set-research-note div_show">教研备注</a>
                                <a class="opt-set-refuse-record div_show">添加拒接反馈</a>
                                <a class="fa-comment opt-return-back-new div_show" title="回访信息录入-new" ></a>
                                <a class="fa-comments opt-return-back-list-new div_show" title="回访列表" ></a>
                                @if(in_array($acc,["adrian","jim","ted","jack","alan"]))
                                    <a class="opt-account-number" title="老师账号信息修改功能">账号相关</a>
                                    <a class="fa-gratipay opt-test-user" title="设置为测试用户"></a>
                                    <a class="opt-change_tea_to_new" title="当前老师转移学生至新账号">转移</a>
                                    <a class="opt-level fa-lock" title="编辑工资分类,等级"></a>
                                    <a class="opt-change-phone" title="更换老师手机">手机</a>
                                    <a class="opt-change-level" title="更改老师等级">等级</a>
                                    <a class="opt-change-lesson-num">修改排课数</a>
                                    <a class="opt-change-week-lesson-num-list" >周排课修改记录</a>
                                    <a class="opt-change-teacher_ref_type">渠道</a>
                                    <a class="opt-change-good-teacher">优秀老师</a>
                                    <a class="opt-change-grade-range" title="更改年级范围">范</a>
                                @endif
                                @if(in_array($acc,["zoe"]))
                                    <a class="opt-change-teacher_ref_type">渠道</a>
                                @endif
                                @if(in_array($acc,["coco","nick","wander","memo","lemon","CoCo老师","lily","melody","niki"]))
                                    <a class="opt-change-good-teacher">优秀老师</a>
                                @endif
                                @if(in_array($acc,["amyshen","low-key","jack","adrian","jim","ted","alan"]))
                                    <a class="opt-trial-pass div_show"  title="设置试讲/培训通过">通过信息</a>
                                @endif
                                @if(in_array($acc,["jack","adrian","jim","ted","alan","CoCo老师"]))
                                    <a class="opt-set-remark"  title="是否在其他机构代课">机构备注</a>
                                @endif
                                <a class="opt-set-grade-range div_show">设置新版年级段</a>
                                <a class=" opt-complaints-teacher div_show" >投诉老师</a>
                                @if(in_array($acc,["alina","lee","June-王梦琼","nina","jack","jim","michael","alan","adrian","余成芳"]))
                                    <a class=" opt-set-leave"  title="设置请假">请假 </a>
                                    <a class="opt-require-teacher-quit" title="离职申请" >离职申请</a>
                                    <a class="fa-gratipay opt-test-user" title="设置为测试用户"></a>
                                @endif
                                @if(in_array($acc,["michael"]))
                                    <a class=" opt-set-quit"  title="设置离职">离职 </a>
                                @endif
                                @if(in_array($acc,["jim","jack"]))
                                    <a class="opt-set-teacher-record-test div_show" >反馈-测试</a>
                                @endif
                                <a class="opt-teacher-leave-list div_show" >请假记录</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

