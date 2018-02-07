@extends('layouts.app')
@section('content')
    <script type="text/javascript" >
     var acc = "{{$acc}}";
     var tea_right = "{{$tea_right}}";
     var account_role = "{{$_account_role}}";
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
                        <span>近两周试听课数</span>
                        <select id="id_test_lesson_full_flag" class ="opt-change" >
                            <option value="-1"> 全部</option>
                            <option value="1"> 0</option>
                            <option value="2">1-4</option>
                            <option value="3">5-8</option>
                            <option value="4">8节以上</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>近一个月常规学生</span>
                        <select id="id_month_stu_num" class ="opt-change" >
                            <option value="-1"> 全部</option>
                            <option value="1"> 0</option>
                            <option value="2">1-3</option>
                            <option value="3">4个以上</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>第一次试听得分</span>
                        <select id="id_record_score_num" class ="opt-change" >
                            <option value="-1"> 全部</option>
                            <option value="1"> 60-80</option>
                            <option value="2">80-90</option>
                            <option value="3">90-100</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师类型</span>
                        <select id="id_identity" class ="opt-change" ></select>
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
                        <span >入职流程完成</span>
                        <select id="id_train_through_new" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="0">未完成</option>
                            <option value="1">已完成</option>
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

                <div class="col-xs-6 col-md-3">
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
                    <div class="input-group ">
                        <span >精排筛选</span>
                        <select id="id_plan_level" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">维度A</option>
                            <option value="2">维度B</option>
                            <option value="3">维度C</option>
                            <option value="4">维度C候选</option>
                            <option value="5">维度D</option>
                            <option value="6">维度D候选</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >教材版本</span>
                        <select id="id_teacher_textbook" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <!-- 避免测试账号随便乱加，关闭新增老师 -->
                <!-- <div class="col-xs-6 col-md-2">
                     <button class="btn btn-primary" id="id_add_teacher"> 新增老师 </button>
                     </div>  -->
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
                    <td >真实姓名</td>
                    <td style="display:none;" >工资分类</td>
                    <td style="display:none;" >推荐渠道</td>
                    <td >老师身份</td>
                    <td style="display:none;">培训通过时间</td>
                    <td style="display:none;" >入库时间</td>
                    <td >入职时长</td>
                    <td style="display:none;">等级</td>
                    <td class="fulltime_flag_new">全职</td>
                    <td id="phone_num" style="display:none;">手机号</td>
                    <td style="display:none;">邮箱</td>
                    <td>性别</td>
                    <td>年龄</td>
                    <td class="tea_address">所在地</td>
                    <td class="tea_school">学校</td>
                    <td>第一科目</td>
                    <td>第一科目年级段</td>
                    <td style="display:none;">第二科目</td>
                    <td style="display:none;">第二科目年级段</td>
                    <td style="width:320px">面试评价</td>
                    <td style="display:none;">教务备注</td>
                    <td >教研备注</td>
                    <td style="display:none;">教师性质</td>
                    <td style="display:none;">版本信息</td>
                    <td style="width:320px">标签</td>
                    <td >教材教材</td>
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
                    <td style="width:220px;">第一次试听课得分</td>
                    <td style="width:220px;" class="interview_score">第一科目面试得分</td>
                    <td style="width:220px;" class="second_interview_score">第二科目面试得分</td>
                    <td style="display:none;" class="lesson_hold_flag">暂停接试听课情况</td>
                    <td style="display:none;" class="jw_revisit_info">教务回访信息</td>
                    <td style="display:none;">添加人</td>
                    <td >面试人</td>
                    <td >精选维度</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["teacherid"]}} </td>
                        <td>{{$var["realname"]}} </td>
                        <td>{{@$var["teacher_money_type_str"]}} </td>
                        <td>{{@$var["teacher_ref_type_str"]}} </td>
                        <td>{{@$var["identity_str"]}} </td>
                        <td>{{@$var["train_through_new_time_str"]}} </td>
                        <td>{{@$var["create_time_str"]}} </td>
                        <td>{{@$var["work_day"]}}</td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["teacher_type_str"]}} </td>
                        <td>
                            <a href="javascript:;" class="show_phone" data-phone="{{$var["phone_spare"]}}" >
                                {{@$var["phone_spare_hide"]}}
                            </a>
                        </td>
                        <td>{{$var["email"]}}</td>
                        <td>{{@$var["gender_str"]}} </td>
                        <td>{{@$var["age"]}} </td>
                        <td>{{$var["address"]}} </td>
                        <td>{{$var["school"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>
                            {{@$var["grade_start_str"]}} 至 {{@$var["grade_end_str"]}}
                        </td>
                        <td>{{@$var["second_subject_str"]}} </td>
                        <td>
                            {{@$var["second_grade_start_str"]}} 至 {{@$var["second_grade_end_str"]}}
                        </td>
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
                        <td>{!! @$var["teacher_tags"] !!} </td>
                        <td>{{@$var["textbook"]}} </td>
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
                        @if(!in_array($account_role,[3,4,5]) && $acc !="ted" && $acc != "jack" && $acc != "michael")
                            <td></td>
                            <td></td>
                        @else
                            <td >{{@$var["test_transfor_per"]}}%</td>
                            <td>一周{{@$var["week_liveness"]}}课时</td>
                        @endif
                        <td>{{@$var["record_score"]}} </td>
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
                            空闲时间:{{ @$var["free_time"] }}<br>
                            性别:{{ @$var["gender_str"] }}<br>
                            教龄:{{ @$var["work_year"] }}<br>
                            地区:{{ @$var["address"] }}
                        </td>
                        <td>{{$var["add_acc"]}}</td>
                        <td>{{@$var["interview_acc"]}}</td>
                        <td>{{@$var["fine_dimension"]}}</td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}  >
                                <a class="fa-user opt-user-info div_show" href="/teacher_info_admin/index?teacherid={{$var["teacherid"]}}" target="_blank" title="老师信息"> </a>
                                <a class="fa-edit opt-edit div_show" title="编辑"> </a>
                                <a class="fa-hand-o-up opt-tea-note div_show" title="修改教务备注"> </a>
                                <a class="fa-list opt-interview-assess div_show" title="修改面试评价"> </a>
                                <a class="opt-show-lessons div_show" title="课程列表">课程</a>
                                <a class="opt-show-lessons-new" title="课程列表-new">课程-new</a>
                                <a  href="/teacher_info_admin/lesson_list?teacherid={{$var["teacherid"]}}" target="_blank" title="跳转到老师课表">课 </a>
                                <a class="fa-gavel opt-set-tmp-passwd div_show"  title="临时密码"></a>
                                <a class="opt-teacher-freeze div_show">冻结排课</a>
                                <a class="opt-freeze-list div_show">冻结排课记录</a>
                                <a class="opt-limit-plan-lesson div_show" >限制排课</a>
                                <a class="opt-limit-plan-lesson-list div_show" >限制排课记录</a>
                                <a class="opt-set-teacher-record-new div_show" >反馈</a>
                                <a class="opt-get-teacher-record div_show">反馈记录</a>
                                <a class="div_show" href="/teacher_info_admin/free_time?teacherid={{$var["teacherid"]}}"
                                   target="_blank" title="设置空闲时间">设置空闲时间</a>
                                <a class="opt-get-teacher-lesson-hold div_show">设置暂停接试听课</a>
                                <a class="opt-set-research-note div_show">教研备注</a>
                                <a class="opt-set-refuse-record div_show">添加拒接反馈</a>
                                <a class="fa-comment opt-return-back-new div_show" title="回访信息录入-new" ></a>
                                <a class="fa-comments opt-return-back-list-new div_show" title="回访列表" ></a>
                                @if(in_array($acc,["ted","夏宏东","haku","low-key","zoe","amyshen","朱丽莎","陆小梅"])
                                    || in_array($account_role,[12]))
                                    <a class="opt-account-number" title="老师账号信息修改功能">账号相关</a>
                                    <a class="opt-change-week-lesson-num-list" >周排课修改记录</a>
                                    <a class="opt-change-good-teacher">优秀老师</a>
                                @endif
                                @if(in_array($acc,["ted","夏宏东","江敏","jim","jack"]))
                                    <a class="opt-change-lesson-num">修改排课数</a>
                                @endif
                                @if(in_array($acc,["coco","nick","wander","memo","lemon","CoCo老师","lily","melody","niki"]))
                                    <a class="opt-change-good-teacher">优秀老师</a>
                                @endif
                                @if(in_array($acc,["amyshen","朱丽莎","陆小梅"]))
                                    <a class="opt-trial-pass div_show"  title="设置试讲/培训通过">通过信息</a>
                                @endif
                                <a class=" opt-set-grade-range div_show">设置新版年级段</a>
                                <a class=" opt-complaints-teacher div_show" >投诉老师</a>
                                @if(in_array($acc,["michael"]))
                                    <a class=" opt-set-quit"  title="设置离职">离职 </a>
                                @endif
                                @if(in_array($acc,["jim","jack"]))
                                    <a class="opt-set-teacher-record-test div_show" >反馈-测试</a>
                                @endif
                                <a class="opt-identity" >老师身份</a>
                                <a class="opt-teacher-leave-list div_show" >请假记录</a>
                                <a class=" opt-regular-lesson-detele-list div_show" >常规课表空闲记录</a>
                                <a class=" opt-teacher-cancel-lesson-list div_show" >老师取消课程记录</a>
                                <a class=" opt-jianli div_show" >简历</a>
                                @if($var['full_flag'])
                                    <a class=" opt-full-to-part">全职转兼职</a>
                                @endif
                                @if(@$var["label_id"]==0)
                                    <a class="opt-set-teacher-label" title="设置标签<">设置标签</a>
                                @elseif(@$var["label_id"]>0)
                                    <a class="opt-set-teacher-label" title="修改标签<">修改标签</a>
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
