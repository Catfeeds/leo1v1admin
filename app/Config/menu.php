<?php
namespace App\Config;
class menu{
   static  public  function get_config()  {
       // 70 -89 小班
        return [
            ["power_id"=>40, "name"=>"核心数据",  "list"=> [
                ["power_id"=>80, "name"=>"测试","url"=>"/test/get_user_list", "domain_type" =>100  ],
                ["power_id"=>81, "name"=>"测试22","url"=>"/test/get_user_list1", "domain_type" =>100  ],
                ["power_id"=>1, "name"=>"试听转化率统计-招师","url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_zs"],
                ["power_id"=>2, "name"=>"试听转化率统计","url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_for_jx"],
                ["power_id"=>3, "name"=>"教师试讲预约-招师",   "url"=>"/human_resource/teacher_lecture_appointment_info_zs"],
                ["power_id"=>4, "name"=>"试听转化率统计-质监", "url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_zj"],
                ["power_id"=>5, "name"=>"面试加班信息",   "url"=>"/tongji_ss/tongji_teacher_1v1_lesson_time"],
                ["power_id"=>6, "name"=>"兼职老师转化率总体",   "url"=>"/human_resource/teacher_test_lesson_info_total"],
                ["power_id"=>7, "name"=>"各学科转化率总体",   "url"=>"/tongji_ss/test_lesson_order_per_subject"],
                ["power_id"=>8, "name"=>"新老师第1次教学质量反馈",   "url"=>"/tongji_ss/teacher_first_test_lesson_week"],
                ["power_id"=>9, "name"=>"老师试听薪资排行",   "url"=>"/tongji_ss/teacher_trial_count"],
                ["power_id"=>10, "name"=>"兼职老师流失模型",   "url"=>"/tongji_ss/get_teacher_appoinment_lecture_info"],
                ["power_id"=>11,"name"=>"咨询师试听转化率", "url"=>"/tongji_ss/seller_test_lesson_info_tongji"],
                ["power_id"=>12, "name"=>"教研以及全职老师常规学生详情",   "url"=>"/human_resource/research_qz_teacher_stu_info"],
                ["power_id"=>13, "name"=>"新老师试听课统计",   "url"=>"/tongji_ss/new_teacher_test_lesson_info"],
                ["power_id"=>14,"name"=>"试听课转化详情-教研", "url"=>"/tongji_ss/research_teacher_lesson_detail_info"],
                ["power_id"=>15, "name"=>"老师身份统计",   "url"=>"/tongji2/tongji_lesson_teacher_identity"],
                ["power_id"=>16, "name"=>"老师晋升审核-兼职","url"=>"/teacher_level/get_teacher_advance_info"],
                ["power_id"=>19, "name"=>"老师晋升审核-教研","url"=>"/teacher_level/teacher_advance_info_list"],
                ["power_id"=>20, "name"=>"老师晋升审核-全职","url"=>"/teacher_level/get_teacher_advance_info_fulltime"],
                ["power_id"=>17, "name"=>"切换薪资审核","url"=>"/teacher_level/teacher_switch_list_finally"],
                ["power_id"=>21, "name"=>"教材版本匹配度","url"=>"/tongji/match_lesson_textbook"],
                ["power_id"=>22, "name"=>"1-3年级试听详情", "url" => "/tongji2/one_three_grade_student" ],
                ["power_id"=>23, "name"=>"教学事业部核心数据指标", "url" => "/tongji2/get_teaching_core_data" ],
            ]],

            ["power_id"=>41, "name"=>"运维管理",  "list"=> [
                ["power_id"=>83, "name"=>"系统状态",   "url"=>"/main_page/admin"],
                ["power_id"=>80, "name"=>"在线预计课数", "url"=>"/tongji/online_def_user_count_list"],
                ["power_id"=>81, "name"=>"在线课数", "url"=>"/tongji/online_user_count_list", "domain_type" =>100],
                ["power_id"=>1, "name"=>"xmpp在线课数", "url"=>"/tongji/online_user_count_xmpp_list"],
                ["power_id"=>8, "name"=>"声音记录管理",   "url"=>"/user_manage_new/record_audio_server_list"],
                ["power_id"=>71, "name"=>"xmpp记录管理",   "url"=>"/admin_manage/xmpp_server_list"],

                ["power_id"=>13, "name"=>"录音xmpp分布",   "url"=>"/tea_manage_new/lesson_record_server_list"],
                ["power_id"=>72, "name"=>"ssh 登录记录",   "url"=>"/login_log/login_list"],
                ["power_id"=>2, "name"=>"job统计",   "url"=>"/admin_manage/job_list"],
            ]],

            ["power_id"=>1, "name"=>"服务管理",  "list"=> [
                ["power_id"=>22, "name"=>"课堂状态", "icon"=>"fa-dashboard", "url"=>"/supervisor/monitor"],
                ["power_id"=>1, "name"=>"所有用户",   "url"=>"/user_manage/all_users" ],
                ["power_id"=>2, "name"=>"学员档案",   "url"=>"/user_manage/index"],
                ["power_id"=>21, "name"=>"账号登录管理",   "url"=>"/user_manage_new/account_list"],
                ["power_id"=>3, "name"=>"合同管理",   "url"=>"/user_manage/contract_list"],
                ["power_id"=>10, "name"=>"分期合同管理",   "url"=>"/period_order/get_all_payed_order_info"],
                ["power_id"=>4, "name"=>"家长档案",   "url"=>"/user_manage/parent_archive"],
                ["power_id"=>8, "name"=>"家长<>学生",   "url"=>"/user_manage/pc_relationship"],
                ["power_id"=>7, "name"=>"其它", "list"=> [
                    ["power_id"=>6, "name"=>"手机电话",   "url"=>"/user_manage_new/notify_phone"]
                ]
                ],
                ["power_id"=>11, "name"=>"课次取消率-技术支持",   "url"=>"/tongji2/lessoncancelrate"],

            ]
            ],
            ["power_id"=>15, "name"=>"例子-新版", "list"=>[
                ["power_id"=>1, "name"=>"分配例子",   "url"=>"/seller_student_new/assign_sub_adminid_list"],
                ["power_id"=>2, "name"=>"分配例子-主管",   "url"=>"/seller_student_new/assign_member_list"],
                ["power_id"=>25, "name"=>"TMK例子库",   "url"=>"/seller_student_new/tmk_seller_student_new"],
                ["power_id"=>5, "name"=>"转介绍待分配例子-总监",   "url"=>"/seller_student_new/assign_member_list_master"],
                ["power_id"=>3, "name"=>"转介绍例子-全部",   "url"=>"/seller_student_new/ass_master_seller_student_list"],
                ["power_id"=>31, "name"=>"转介绍例子-主管",   "url"=>"/seller_student_new/ass_master_seller_master_student_list"],
                ["power_id"=>4, "name"=>"微信运营",   "url"=>"/seller_student_new2/tmk_student_list"],
                ["power_id"=>11, "name"=>"排课", "list"=>[
                    ["power_id"=>1, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list"],
                    ["power_id"=>3, "name"=>"选择老师",   "url"=>"/seller_student_new2/select_teacher_for_test_lesson"],
                    ["power_id"=>11, "name"=>"试听排课-教务",   "url"=>"/seller_student_new2/test_lesson_plan_list_jx"],
                    ["power_id"=>2, "name"=>"未绑定的试听课",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>5, "name"=>"教务排课明细", "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>6, "name"=>"试听销售签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list"],
                    ["power_id"=>7, "name"=>"预分配列表", "url"=>"/seller_student_new2/get_test_lesson_require_teacher_info"],
                ]],
                ["power_id"=>13, "name"=>"抢新例子数据", "list"=>[
                    ["power_id"=>1, "name"=>"当前用户可抢数",   "url"=>"/seller_student_new2/seller_get_new_count_admin_list"],
                    ["power_id"=>5, "name"=>"当前用户抢新统计",   "url"=>"/seller_student_new2/tongji_seller_get_new_count"],
                    ["power_id"=>2, "name"=>"当前用户可抢数明细",   "url"=>"/seller_student_new2/seller_get_new_count_list"],
                    ["power_id"=>3, "name"=>"当前未拨打未拨通",   "url"=>"/seller_student_new2/seller_no_call_to_free_list"],
                ]],
                ["power_id"=>23, "name"=>"在读统计", "list"=>[
                    ["power_id"=>1, "name"=>"学员人数", "url"=>"/tongji_ss/valid_user_count"],
                    ["power_id"=>2, "name"=>"区域/年级/科目 分布", "url"=>"/tongji2/valid_user_region"],
                    ["power_id"=>3, "name"=>"新增/续费/转介绍", "url"=>"/tongji2/valid_user_money_info"],

                ]],
                ["power_id"=>12, "name"=>"统计", "list"=>[

                    ["power_id"=>82, "name"=>"新增例子分时统计", "url"=>"/tongji_ss/new_user_count"],
                    ["power_id"=>1, "name"=>"例子统计", "url"=>"/tongji_ss/user_count"],
                    ["power_id"=>52, "name"=>"tmk统计-销售", "url"=>"/tongji_ss/origin_count_seller"],
                    ["power_id"=>3, "name"=>"销售个人统计", "url"=>"/tongji_ss/seller_count"],
                    ["power_id"=>31, "name"=>"销售个人统计-主管", "url"=>"/tongji_ss/seller_count_seller_master"],
                    ["power_id"=>84, "name"=>"销售试听转化率统计", "url"=>"/tongji_ss/seller_test_lesson_transfor_per"],
                    ["power_id"=>4, "name"=>"排课明细", "url"=>"/seller_student_new2/test_lesson_detail_list"],
                    ["power_id"=>5, "name"=>"合同统计", "url"=>"/tongji_ss/contract_count"],
                    ["power_id"=>6, "name"=>"排课统计", "url"=>"/tongji_ss/set_lesson_count"],
                    ["power_id"=>7, "name"=>"销售申请统计", "url"=>"/tongji_ss/require_count_seller"],
                    ["power_id"=>8, "name"=>"销售主管未分配统计", "url"=>"/tongji_ss/master_no_assign_count"],

                    ["power_id"=>9, "name"=>"销售月度统计报表",   "url"=>"/user_manage_new/seller_tongji_report_info"],
                    ["power_id"=>24, "name"=>"销售月度统计报表新",   "url"=>"/seller_tongji/month_tongji_report"],
                    ["power_id"=>10, "name"=>"销售红黑榜",   "url"=>"/user_manage_new/seller_require_tq_time_list"],
                    ["power_id"=>11, "name"=>"实时申请未排统计",   "url"=>"/tongji_ss/require_no_set_lesson_info"],
                    ["power_id"=>12, "name"=>"试听老师统计",   "url"=>"/tongji_ss/teacher_test_lesson_info"],
                    ["power_id"=>13, "name"=>"设备统计",   "url"=>"/tongji_ss/lesson_device_info"],
                    ["power_id"=>14, "name"=>"日报",   "url"=>"/tongji_ss/day_report"],
                    ["power_id"=>15, "name"=>"微信运营试听课统计",   "url"=>"/tongji_ss/tmk_test_lesson_count"],
                    ["power_id"=>16, "name"=>"渠道统计-销售",   "url"=>"/tongji_ss/get_origin_info_by_order"],
                    ["power_id"=>17, "name"=>"助教-试听统计",   "url"=>"/tongji/assistant_test_lesson_count"],

                    ["power_id"=>23,"name"=>"老师-试听分次统计", "url"=>"/tongji_ss/teacher_trial_lesson_list"],
                    ["power_id"=>19, "name"=>"老师-试听统计-old",   "url"=>"/tongji_ss/get_teacher_test_lesson_info_old"],


                    ["power_id"=>21, "name"=>"试听申请-时间间隔",   "url"=>"/tongji_ss/require_time_test_lesson_require_time_date_info"],
                    ["power_id"=>22,"name"=>"学生续费统计", "url"=>"/tongji_ss/stu_lesson_total_list"],


                ]],

                ["power_id"=>20, "name"=>"统计2", "list"=>[
                    ["power_id"=>21, "name"=>"无效资源分类",   "url"=>"/tongji_ss/invalid_user_list"],
                    ["power_id"=>2, "name"=>"试听签约失败分类",   "url"=>"/tongji_ss/order_fail_list"],
                    ["power_id"=>3, "name"=>"试听签约失败-未设置",   "url"=>"/tongji_ss/order_fail_seller_set"],
                    ["power_id"=>4, "name"=>"销售月度绩效",   "url"=>"/tongji2/seller_month_money_list"],
                    ["power_id"=>5, "name"=>"折扣情况",   "url"=>"/contract_present/contract_present_info"],
                    ["power_id"=>6, "name"=>"转介绍统计",   "url"=>"/tongji2/referral_count"],

                ]],

                ["power_id"=>21, "name"=>"统计-助教", "list"=>[
                    ["power_id"=>1, "name"=>"助教组-整体统计",   "url"=>"/tongji2/ass_all"],
                ]],
            ]],
            ["power_id"=>2, "name"=>"课程管理", "list"=> [
                ["power_id"=>1, "name"=>"课程管理",   "url"=>"/tea_manage/lesson_list"],
                ["power_id"=>11, "name"=>"课程列表", "list"=> [
                    ["power_id"=>1, "name"=>"老师-课程列表",   "url"=>"/tea_manage/tea_lesson_list"],
                ]],
                ["power_id"=>4, "name"=>"测评管理",   "url"=>"/tea_manage/quiz_info"],
                ["power_id"=>5, "name"=>"公开课堂",   "url"=>"/tea_manage/open_class2"],
                ["power_id"=>7, "name"=>"小班课", "list"=> [
                    ["power_id"=>1, "name"=>"小班管理",   "url"=>"/small_class/index"],
                    ["power_id"=>2, "name"=>"小班课次管理",   "url"=>"/small_class/lesson_list"],
                    ["power_id"=>4, "name"=>"小班课次管理-new",   "url"=>"/small_class/lesson_list_new"],
                    ["power_id"=>3, "name"=>"小班学生列表",   "url"=>"/small_class/student_list"],
                    ["power_id"=>5, "name"=>"小班学生列表-new",   "url"=>"/small_class/student_list_new"]
                ]],
                ["power_id"=>14, "name"=>"培训课堂",   "url"=>"/tea_manage/train_lesson_list"],
                ["power_id"=>15, "name"=>"培训未通过",   "url"=>"/tea_manage/train_not_through_list"],
                ["power_id"=>9, "name"=>"课程统计信息",   "url"=>"/tea_manage/lesson_account"],
                ["power_id"=>10, "name"=>"登陆过多统计",   "url"=>"/lesson_manage/stu_login_count"]
            ]],
            ["power_id"=>3, "name"=>"理优智能题库", "list"=>[
                ["power_id"=>1, "name"=>"录入", "list"=>[
                    ["power_id"=>1, "name"=>"录入-编辑",   "url"=>"/question/question_list"],
                    ["power_id"=>2, "name"=>"录入-审核未通过-所有",   "url"=>"/question/question_list_nopass"],
                    ["power_id"=>3, "name"=>"录入-审核未通过-扣10%",   "url"=>"/question/question_list_nopass_10"],
                    ["power_id"=>4, "name"=>"录入-审核未通过-扣50%",   "url"=>"/question/question_list_nopass_50"],
                    ["power_id"=>5, "name"=>"录入-审核未通过-扣100%",   "url"=>"/question/question_list_nopass_100"],
                    ["power_id"=>6, "name"=>"录入-审核未通过-不入库",   "url"=>"/question/question_list_nopass_del"],
                    ["power_id"=>7, "name"=>"录入-审核通过",   "url"=>"/question/question_list_pass"]]],
                ["power_id"=>2, "name"=>"一审", "list"=>[
                    ["power_id"=>1, "name"=>"一审-审核",  "icon"=>"fa-book", "url"=>"/question/question_list_check?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>2, "name"=>"一审-审核未通过-所有",   "url"=>"/question/question_list_check_nopass?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>3, "name"=>"一审-审核未通过-扣10%",   "url"=>"/question/question_list_check_nopass_10?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>4, "name"=>"一审-审核未通过-扣50%",   "url"=>"/question/question_list_check_nopass_50?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>5, "name"=>"一审-审核未通过-扣100%",   "url"=>"/question/question_list_check_nopass_100?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>6, "name"=>"一审-审核未通过-不入库",   "url"=>"/question/question_list_check_nopass_del?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>7, "name"=>"一审-审核通过",   "url"=>"/question/question_list_check_pass?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>8, "name"=>"二审-审核未通过-所有",   "url"=>"/question/question_list_check2_for1_nopass"],
                    ["power_id"=>9, "name"=>"二审-审核未通过-扣10%",   "url"=>"/question/question_list_check2_for1_nopass_10"],
                    ["power_id"=>10, "name"=>"二审-审核未通过-扣50%",   "url"=>"/question/question_list_check2_for1_nopass_50"],
                    ["power_id"=>11, "name"=>"二审-审核未通过-扣100%",   "url"=>"/question/question_list_check2_for1_nopass_100"],
                    ["power_id"=>12, "name"=>"二审-审核未通过-不入库",   "url"=>"/question/question_list_check2_for1_nopass_del"],
                    ["power_id"=>13, "name"=>"二审-审核通过",   "url"=>"/question/question_list_check2_for1_pass"]]],
                ["power_id"=>3, "name"=>"二审", "list"=>[
                    ["power_id"=>1, "name"=>"审核",  "icon"=>"fa-book", "url"=>"/question/question_list_check2_for2?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>2, "name"=>"-审核未通过-所有",   "url"=>"/question/question_list_check2_nopass?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>3, "name"=>"审核未通过-扣10%",   "url"=>"/question/question_list_check2_nopass_10?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>4, "name"=>"审核未通过-扣50%",   "url"=>"/question/question_list_check2_nopass_50?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>5, "name"=>"审核未通过-扣100%",   "url"=>"/question/question_list_check2_nopass_100?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>6, "name"=>"审核未通过-不入库",   "url"=>"/question/question_list_check2_nopass_del?grade=200&subject=2&start_time=2015-10-23"],
                    ["power_id"=>7, "name"=>"审核通过",   "url"=>"/question/question_list_check2_pass?grade=200&subject=2&start_time=2015-10-23"]]],
                ["power_id"=>4, "name"=>"知识点编辑",  "icon"=>"fa-book", "url"=>"/question/edit_lesson_note"],
                ["power_id"=>5, "name"=>"所有题目",  "icon"=>"fa-book", "url"=>"/question/publish_list"],
                ["power_id"=>6, "name"=>"录入统计",  "icon"=>"fa-book", "url"=>"/question/admin_info"],
                ["power_id"=>7, "name"=>"审核统计",  "icon"=>"fa-book", "url"=>"/question/check_admin_info"],
                ["power_id"=>8, "name"=>"题库题目统计",  "icon"=>"fa-book", "url"=>"/human_resource/get_question_tongji"]
            ]],

            ["power_id"=>44, "name"=>"理优题库新", "list"=>[
                ["power_id"=>22, "name"=>"题目列表",   "url"=>"/question_new/question_list"],
                ["power_id"=>24, "name"=>"知识点",   "url"=>"/question_new/knowledge_list"],
                ["power_id"=>25, "name"=>"教材列表",   "url"=>"/question_new/textbook_list"],
            ]],
            ["power_id"=>4, "name"=>"人事绩效", "list"=>[
                ["power_id"=>22, "name"=>"教师档案(试讲老师)",   "url"=>"/human_resource/index_new"],
                ["power_id"=>1, "name"=>"教师档案(全部老师)",   "url"=>"/human_resource/index"],
                ["power_id"=>25, "name"=>"老师课时统计",   "url"=>"/human_resource/teacher_total_list"],
                ["power_id"=>24, "name"=>"教师档案-new",   "url"=>"/human_resource/teacher_info_new"],
                ["power_id"=>23, "name"=>"教师评估考核",   "url"=>"/human_resource/teacher_assess"],
                ["power_id"=>8, "name"=>"教师特长",   "url"=>"/human_resource/specialty"],
                //['power_id'=>30, "name"=>"老师银行卡", "url" => "/teacher_money/show_teacher_bank_info_human"],
                ["power_id"=>21, "name"=>"老师会议", "list"=>[
                    ["power_id"=>1, "name"=>"会议记录",   "url"=>"/human_resource/teacher_meeting_info"],
                    ["power_id"=>2, "name"=>"与会人员信息",   "url"=>"/human_resource/teacher_meeting_join_info"],
                ]],
                ["power_id"=>5, "name"=>"老师工资", "list"=>[
                    ["power_id"=>1, "name"=>"老师课时-总体",   "url"=>"/user_manage_new/tea_lesson_count_total_list"],
                    ['power_id'=>30, "name"=>"老师银行卡信息", "url" => "/teacher_money/show_teacher_bank_info_human"],
                    ['power_id'=>31, "name"=>"全转兼审核", "url"=>"/teacher_trans/full_to_part_trans_info"],
                    ["power_id"=>2, "name"=>"老师工资",   "url"=>"/user_manage_new/tea_lesson_count_detail_list"],
                    ["power_id"=>3, "name"=>"老师课时工资配置信息",   "url"=>"/user_manage_new/get_teacher_money_list"],
                    ["power_id"=>4, "name"=>"学生课程年级异常",   "url"=>"/user_manage_new/lesson_student_grade_list"],
                    ["power_id"=>7, "name"=>"工资总体","url"=>"/user_manage_new/tea_wages_list"],
                    ["power_id"=>5, "name"=>"工资明细","url"=>"/user_manage_new/tea_wages_info"],
                    ["power_id"=>8, "name"=>"工资配置","url"=>"/user_manage_new/teacher_money_type_list"],
                    ["power_id"=>9, "name"=>"工资统计","url"=>"/user_manage_new/tea_wages_count_list"],
                    ["power_id"=>11, "name"=>"全勤奖配置","url"=>"/human_resource/get_lesson_full_list"],
                    ["power_id"=>12, "name"=>"全勤奖配置-old","url"=>"/human_resource/get_lesson_full_wage_old"],
                    ["power_id"=>13, "name"=>"额外奖金","url"=>"/user_manage_new/teacher_trial_reward_list"],
                    ["power_id"=>15, "name"=>"各类型薪资明细","url"=>"/user_manage_new/teacher_details_money"],
                    ["power_id"=>16, "name"=>"年级工资分类","url"=>"/teacher_money/grade_wages_list"],
                    ["power_id"=>17, "name"=>"工资总体--new","url"=>"/teacher_money/teacher_salary_list"],
                ]],
                ["power_id"=>11, "name"=>"模拟工资", "list"=>[
                    ["power_id"=>1, "name"=>"模拟工资-总体","url"=>"/teacher_simulate/teacher_simulate_salary_list"],
                    ["power_id"=>2, "name"=>"模拟工资-明细","url"=>"/teacher_simulate/tea_simulate_wages_info"],
                ]],
                ["power_id"=>4, "name"=>"助教档案",   "url"=>"/human_resource/assistant_info2"],
                ["power_id"=>5, "name"=>"助教档案-new",   "url"=>"/human_resource/assistant_info_new"],
                ["power_id"=>6, "name"=>"面试信息查看",   "url"=>"/human_resource/get_apply_info"],
                ["power_id"=>12, "name"=>"面试通知提醒",   "url"=>"/human_resource/interview_remind"],
                ["power_id"=>9, "name"=>"老师试讲课列表","url"=>"/human_resource/teacher_lecture_list"],
                ["power_id"=>10, "name"=>"老师反馈","list"=>[
                    ["power_id"=>1, "name"=>"反馈列表", "url"=>"/teacher_feedback/teacher_feedback_list"],
                ]],
            ]],
            ["power_id"=>6, "name"=>"系统设置", "list"=>[

                ["power_id"=>18, "name"=>"用户管理-vue",   "url"=>"/authority/manager_list", "domain_type" =>100 ],
                ["power_id"=>2, "name"=>"用户管理",   "url"=>"/authority/manager_list"],
                ["power_id"=>12, "name"=>"邮件组管理",   "url"=>"/admin_manage/group_email_list"],

                ["power_id"=>11, "name"=>"用户管理-考勤",   "url"=>"/authority/manager_list_for_kaoqin"],
                ["power_id"=>3, "name"=>"用户管理-离职",   "url"=>"/authority/manager_list_offline"],
                ["power_id"=>20, "name"=>"用户管理-销售",   "url"=>"/authority/manager_list_for_seller"],
                ["power_id"=>30, "name"=>"用户管理-助教",   "url"=>"/authority/manager_list_for_ass"],
                ["power_id"=>34, "name"=>"用户管理-全职老师",   "url"=>"/authority/manager_list_for_qz"],
                ["power_id"=>37, "name"=>"用户管理-全职老师(上海)",   "url"=>"/authority/manager_list_for_qz_shanghai"],
                ["power_id"=>38, "name"=>"用户管理-全职老师(武汉)",   "url"=>"/authority/manager_list_for_qz_wuhan"],

                ["power_id"=>31, "name"=>"部门管理",  "url"=>"/user_manage_new/admin_group_manage"],
                ["power_id"=>40, "name"=>"校区管理",   "url"=>"/campus_manage/admin_campus_manage"],
                ["power_id"=>41, "name"=>"月份时间定义",   "url"=>"/month_def_type/def_list"],
                ["power_id"=>35, "name"=>"各部门花名册", "list"=>[
                    ["power_id"=>1, "name"=>"教学事业管理部花名册","url"=>"/user_manage_new/department_memeber_list_production"],
                    ["power_id"=>2, "name"=>"TSR事业部花名册","url"=>"/user_manage_new/department_memeber_list_seller"],
                    ["power_id"=>3, "name"=>"SC花名册","url"=>"/user_manage_new/department_memeber_list_sc"],
                    ["power_id"=>4, "name"=>"市场部花名册","url"=>"/user_manage_new/department_memeber_list_market"],
                    ["power_id"=>5, "name"=>"研发部花名册","url"=>"/user_manage_new/department_memeber_list_development"],
                    ["power_id"=>6, "name"=>"财务部花名册","url"=>"/user_manage_new/department_memeber_list_finance"],
                    ["power_id"=>7, "name"=>"人事部花名册","url"=>"/user_manage_new/department_memeber_list_human"],

                ]],

                ["power_id"=>32, "name"=>"考勤机器",   "url"=>"/admin_manage/kaoqin_machine"],
                ["power_id"=>33, "name"=>"考勤机器-人员配置",   "url"=>"/admin_manage/kaoqin_machine_adminid", "domain_type" =>100 ],
                ["power_id"=>84, "name"=>"开关设备",   "url"=>"/admin_manage/office_cmd_list"],
                ["power_id"=>21, "name"=>"后台成员分组",   "url"=>"/user_manage_new/admin_group_edit"],
                ["power_id"=>22, "name"=>"后台主管成员分组",   "url"=>"/user_manage_new/admin_main_group_edit"],
                ["power_id"=>24, "name"=>"销售主管分组比例配置",   "url"=>"/user_manage_new/admin_main_assign_percent_edit"],
                ["power_id"=>23, "name"=>"后台销售管理", "list"=>[
                    ["power_id"=>1, "name"=>"销售额以及时间",   "url"=>"/user_manage_new/admin_member_list"],
                    ["power_id"=>2, "name"=>"销售个人出勤",   "url"=>"/user_manage_new/seller_attendance_info"]
                ]],
                ["power_id"=>104, "name"=>"合同配置管理", "list"=>[
                    ["power_id"=>2, "name"=>"查看当前活动",   "url"=>"/seller_student2/get_current_commom_activity"],
                    ["power_id"=>1, "name"=>"活动管理",   "url"=>"/seller_student2/show_order_activity_info"],
                ]],

                ["power_id"=>5, "name"=>"权限管理", "url"=>"/user_manage_new/power_group_edit"],
                ["power_id"=>50, "name"=>"权限管理-new", "url"=>"/user_manage_new/power_group_edit_new"],
                ["power_id"=>45, "name"=>"组织架构", "url"=>"/company_wx/show_department_users"],
                ["power_id"=>46, "name"=>"权限角色", "url"=>"/company_wx/all_users"],
                ["power_id"=>4, "name"=>"权限execl",   "url"=>"/authority/jurisdiction"],
                ["power_id"=>6, "name"=>"权限查询",   "url"=>"/authority/get_acc_power_list"],
                ["power_id"=>7, "name"=>"登录日志",   "url"=>"/authority/get_login_list"],
                ["power_id"=>71, "name"=>"websocket测试",   "url"=>"/seller_student_new/seller_student_ws"],
                ["power_id"=>10, "name"=>"老师后台权限",   "url"=>"/user_manage_new/user_power_group_edit"],

                ["power_id"=>8, "name"=>"数据库", "list"=>[
                    ["power_id"=>11, "name"=>"数据库-tablelist",   "url"=>"/table_manage/table_list"],
                    ["power_id"=>10, "name"=>"数据库-查询",   "url"=>"/table_manage/query"],
                    ["power_id"=>1, "name"=>"数据库-表管理",   "url"=>"/table_manage/index"],

                    ["power_id"=>2, "name"=>"数据库-数据修改",   "url"=>"/table_manage/edit_table_data"],
                    ["power_id"=>3, "name"=>"数据库-数据修改记录",   "url"=>"/table_manage/opt_table_log"],
                    ["power_id"=>5, "name"=>"tq wsdl生成",   "url"=>"/table_manage/tq_wsdl"],
                    ["power_id"=>4, "name"=>"开发信息",   "url"=>"/table_manage/dev_info"]]]]
            ],

            ["power_id"=>7, "name"=>"市场", "list"=>[
                ["power_id"=>1, "name"=>"首页",  "url"=>"/main_page2/market" ],
                ["power_id"=>88, "name"=>"周报月报",  "url"=>"/report/week_of_monthly_report" ],
                ["power_id"=>8, "name"=>"渠道-统计", "list"=>[
                    // ["power_id"=>59, "name"=>"例子分配明细", "url"=>"/tongji_ex/seller_student_distribution"],
                    ["power_id"=>3, "name"=>"渠道统计-明细", "url"=>"/tongji_ex/seller_student_detail"],
                    ["power_id"=>28, "name"=>"渠道页面-访问", "url"=>"/report/event_log_info", "domain_type" =>100 ],
                    ["power_id"=>56, "name"=>"渠道统计-new", "url"=>"/tongji_ss/channel_statistics"],
                    ["power_id"=>2, "name"=>"渠道统计 总体", "url"=>"/tongji_ss/origin_count"],
                    ["power_id"=>99, "name"=>"渠道统计-即时", "url"=>"/tongji_ss/origin_count_simple"],
                    ["power_id"=>51, "name"=>"渠道统计-BD", "url"=>"/tongji_ss/origin_count_bd"],
                    ["power_id"=>53, "name"=>"渠道统计-BD-即时", "url"=>"/tongji_ss/origin_count_bd_simple"],

                    ["power_id"=>54, "name"=>"渠道统计-优学帮", "url"=>"/tongji_ss/origin_count_yxb"],
                    // ["power_id"=>55, "name"=>"渠道统计-优学帮-即时", "url"=>"/tongji_ss/origin_count_yxb_simple"],
                    ["power_id"=>55, "name"=>"渠道统计-优学帮-即时", "url"=>"/tongji/origin_count_yxb_simple"],

                    ["power_id"=>52, "name"=>"渠道统计-用户运营", "url"=>"/tongji_ss/origin_count_yhyy"],
                    ["power_id"=>50, "name"=>"渠道统计-微信运营/tmk", "url"=>"/tongji_ss/origin_count_tmk"],
                    ["power_id"=>57, "name"=>"渠道统计-信息流", "url"=>"/tongji_ss/channel_sta_flow"],
                    ["power_id"=>11, "name"=>"渠道cc 获得新例子", "url"=>"/seller_student_new2/seller_first_admin_info"],
                    ["power_id"=>10, "name"=>"渠道cc产出统计", "url"=>"/tongji2/seller_origin_info"],
                    ["power_id"=>60, "name"=>"渠道用户明细", "url"=>"/seller_student_new2/origin_user_list"],

                    ["power_id"=>20, "name"=>"重复报名统计",   "url"=>"/tongji_ss/rejion_count_list"],
                ]],
                ["power_id"=>2, "name"=>"渠道配置", "list"=>[
                    ["power_id"=>8, "name"=>"渠道管理",   "url"=>"/seller_student/channel_manage"],
                    ["power_id"=>25, "name"=>"渠道-BD",   "url"=>"/seller_student/channel_manage_bd"],
                    ["power_id"=>26, "name"=>"渠道-优学帮",   "url"=>"/seller_student/channel_manage_yxb"],
                ]],
                ["power_id"=>3, "name"=>"单渠道-对外", "list"=>[
                    ["power_id"=>1, "name"=>"ALL",   "url"=>"/tongji_ss/origin_publish_list"],
                    ["power_id"=>8, "name"=>"考拉购物",   "url"=>"/tongji_ss/origin_publish_kaolagouwu"],
                    ["power_id"=>6, "name"=>"金数据",   "url"=>"/tongji_ss/origin_publish_jinshuju"],
                    ["power_id"=>2, "name"=>"BD",   "url"=>"/tongji_ss/origin_publish_bd"],
                    ["power_id"=>4, "name"=>"BD2",   "url"=>"/tongji_ss/origin_publish_bd_vaild"],
                    ["power_id"=>3, "name"=>"今日头条",   "url"=>"/tongji_ss/origin_publish_jrtt"],
                ]],
                ["power_id"=>11, "name"=>"电话记录", "list"=>[
                    ["power_id"=>1, "name"=>"电话明细",   "url"=>"/tq/get_list"],
                    ["power_id"=>2, "name"=>"拨打者统计",   "url"=>"/tq/tongji_list"],
                ]],
                ["power_id"=>12, "name"=>"TMK-个人",   "url"=>"/tongji_ss/tmk_count"],
                ["power_id"=>13, "name"=>"tmk数据 上传",  "list"=>[
                 ["power_id"=>1, "name"=>"批次列表",  "url"=>"/upload_tmk/post_list" ],
                 ["power_id"=>2, "name"=>"批次明细",  "url"=>"/upload_tmk/post_student_list" ]
                ]],
                ["power_id"=>14, "name"=>"角色-TMK-组长", "list"=>[
                    ["power_id"=>31, "name"=>"有效例子",   "url"=>"/seller_student_new2/tmk_student_list_all"],
                ]],
                ["power_id"=>15, "name"=>"角色-TMK", "list"=>[
                    ["power_id"=>31, "name"=>"筛选例子",   "url"=>"/seller_student_new/tel_student_list"],
                    ["power_id"=>6, "name"=>"无设备例子",   "url"=>"/seller_student_new/tmk_assign_sub_adminid_list"],
                    ["power_id"=>5, "name"=>"有效例子",   "url"=>"/seller_student_new2/tmk_student_list2"],

                ]],
                ["power_id"=>5, "name"=>"质检-退费处理", "list"=>[
                    ["power_id"=>7, "name"=>"退费管理",   "url"=>"/user_manage/refund_list"],
                    ["power_id"=>1, "name"=>"退费统计-下单人",   "url"=>"/user_manage/refund_tongji_sys_operator"],
                    ["power_id"=>2, "name"=>"退费统计-助教",   "url"=>"/user_manage/refund_tongji_cr"],
                    ["power_id"=>9, "name"=>"退费汇总",   "url"=>"/user_manage/refund_duty_analysis"],
                    ["power_id"=>8, "name"=>"退费原因分析",   "url"=>"/user_manage/refund_analysis"],
                    ["power_id"=>30, "name"=>"退费原因编辑",   "url"=>"/order_refund_confirm_config/refund_info"],
                ]],
                ["power_id"=>6, "name"=>"质检-投诉处理", "list"=>[
                    ["power_id"=>31, "name"=>"家长投诉",   "url"=>"/user_manage_new/parent_report"],
                    ["power_id"=>32, "name"=>"规则文档",   "url"=>"/rule_txt/get_all"],
                    ["power_id"=>36, "name"=>"投诉处理-家长",   "url"=>"/user_manage/complaint_department_deal_parent"],
                    ["power_id"=>37, "name"=>"退费投诉处理-QC",   "url"=>"/user_manage/complaint_department_deal_qc"],
                    ["power_id"=>33, "name"=>"投诉处理-QC",   "url"=>"/user_manage/qc_complaint"],
                    ["power_id"=>34, "name"=>"兼职老师薪资处理",   "url"=>"/user_manage/complaint_department_deal_teacher_qc"],
                ]],
                ["power_id"=>4, "name"=>"优学优享", "list"=> [
                    ["power_id"=>10, "name"=>"会员列表",   "url"=>"/agent/agent_list"],
                    ["power_id"=>93, "name"=>"学员列表",   "url"=>"/agent/student_list"],
                    ["power_id"=>14, "name"=>"微信信息",   "url"=>"/agent/agent_user_wechat"],
                    ["power_id"=>92, "name"=>"微信信息[新版]",   "url"=>"/agent/user_center_info"],
                    ["power_id"=>11, "name"=>"用户提现列表",   "url"=>"/agent/agent_cash_list"],
                    ["power_id"=>50, "name"=>"优学优享会员销售统计",   "url"=>"/agent/get_yxyx_member"],
                    ["power_id"=>13, "name"=>"test",   "url"=>"/agent/check"],
                    // ["power_id"=>13, "name"=>"test",   "url"=>"/seller_student_new2/test_lesson_plan_list_new"],
                    ["power_id"=>66, "name"=>"电话统计",   "url"=>"/agent/get_phone_count"],
                    ["power_id"=>68, "name"=>"自定义标签",   "url"=>"/t_yxyx_custom_type/get_all"],
                    ["power_id"=>70, "name"=>"活动赠送金额",   "url"=>"/agent_money_ex/agent_money_ex_list"],
                    ["power_id"=>71, "name"=>"微信推送消息",   "url"=>"/agent/get_wx_login_list"],

                    ["power_id"=>90, "name"=>"优学优享团",   "url"=>"/agent/agent_group"],
                    ["power_id"=>91, "name"=>"团队统计",   "url"=>"/agent/agent_group_statistics"],
                    ["power_id"=>69, "name"=>"精品内容",   "url"=>"/t_yxyx_new_list/get_all"],
                    ["power_id"=>67, "name"=>"学员反馈",   "url"=>"/t_yxyx_test_pic_info/get_all_info"],
                    ["power_id"=>7, "name"=>"用户下线",   "url"=>"/agent/agent_user_link"],
                    ["power_id"=>8, "name"=>"用户订单",   "url"=>"/agent/agent_order_list"],
                    ["power_id"=>12, "name"=>"优学帮列表",   "url"=>"/agent/agent_list_new"],
                ]],
                ["power_id"=>9, "name"=>"约课 课程包管理",   "url"=>"/appoint/index2"],
                ["power_id"=>18, "name"=>"h5 活动页 ",   "url"=>"/admin_manage/web_page_info"],


            ]],


            ["power_id"=>8, "name"=>"财务管理", "list"=>[
                ["power_id"=>1, "name"=>"合同管理-财务",   "url"=>"/user_manage_new/money_contract_list"],
                ["power_id"=>11, "name"=>"合同管理-new",   "url"=>"/user_manage_new/money_contract_list_stu"],
                ["power_id"=>2, "name"=>"统计",   "url"=>"/lesson_manage/stu_status_count"],
                ["power_id"=>5, "name"=>"学员档案-付费",   "url"=>"/user_manage/pay_money_stu_list"],
                ["power_id"=>8, "name"=>"总体收入",   "url"=>"/tongji/all_info"],
                ["power_id"=>9, "name"=>"月度收入",   "url"=>"/tongji/get_month_money_info"],
                ["power_id"=>3, "name"=>"渠道统计", "url"=>"/seller_student/money_contract_list_for_origin"],
                ["power_id"=>4, "name"=>"月份学生统计",   "url"=>"/user_manage_new/month_user_info"],
                ["power_id"=>10, "name"=>"退费管理",   "url"=>"/user_manage/refund_list_finance"],
                ["power_id"=>12, "name"=>"收入",   "url"=>"/finance_data/income_info"],
                ["power_id"=>13, "name"=>"退费合同",   "url"=>"/finance_data/refund_order_info"],
                ["power_id"=>14, "name"=>"学生数据",   "url"=>"/finance_data/student_data"],
                ["power_id"=>15, "name"=>"试听学生&&在读学生",   "url"=>"/finance_data/student_type_data"],
                ["power_id"=>16, "name"=>"试听转化率以及渠道统计",   "url"=>"/finance_data/test_lesson_origin_tongji"],
                ["power_id"=>17, "name"=>"合同管理",   "url"=>"/finance_data/money_contract_list"],

                ["power_id"=>9, "name"=>"考勤", "list"=>[
                    ["power_id"=>4, "name"=>"考勤记录",   "url"=>"/tongji/admin_card_log_list"],
                    ["power_id"=>5, "name"=>"员工-考勤信息",   "url"=>"/tongji/admin_card_date_log_list"],
                    ["power_id"=>6, "name"=>"员工-每天考勤信息",   "url"=>"/tongji/admin_card_admin_log_list"],
                ]],
            ]],
            ["power_id"=>9, "name"=>"工具箱", "list"=>[
                ["power_id"=>1, "name"=>"商城管理", "list"=>[
                    ["power_id"=>1, "name"=>"商品管理",   "url"=>"/authority/present_manage"],
                    ["power_id"=>4, "name"=>"商品管理-new",   "url"=>"/user_manage_new/present_manage_new"],
                    ["power_id"=>2, "name"=>"兑换管理",   "url"=>"/present/index"],
                    ["power_id"=>3, "name"=>"兑换管理-new",   "url"=>"/user_manage_new/commodity_exchange_management"],
                ]],
                ["power_id"=>3, "name"=>"消息记录", "list"=>[
                    ["power_id"=>1, "name"=>"api 实时调用消息",   "url"=>"/monitor/api_func"],
                    ["power_id"=>2, "name"=>"微信推送",   "url"=>"/monitor/wxMonitor"],
                    ["power_id"=>10, "name"=>"微信推送-new",   "url"=>"/user_manage_new/wx_monitor_new"],
                    ["power_id"=>3, "name"=>"短信发送",   "url"=>"/monitor/smsmonitor"],
                    ["power_id"=>4, "name"=>"百度推送",   "url"=>"/monitor/baiduMonitor"],
                    ["power_id"=>5, "name"=>"消息统计",   "url"=>"/monitor/aggregate"],
                    ["power_id"=>7, "name"=>"微信视频推送详情",   "url"=>"/user_manage_new/teacher_send_video_list"],
                ]],
                // ["power_id"=>66, "name"=>"微信新闻",   "url"=>"/t_yxyx_wxnews_info/all_news"],
                // ["power_id"=>71, "name"=>"教研备课资料框架",   "url"=>"/resource/resource_frame"],
                // ["power_id"=>70, "name"=>"教研备课数据统计",   "url"=>"/resource/resource_count"],
                // ["power_id"=>68, "name"=>"教研备课后台",   "url"=>"/resource/get_all"],
                // ["power_id"=>69, "name"=>"教研回收站",   "url"=>"/resource/get_del"],
                ["power_id"=>67, "name"=>"抢课访问记录",   "url"=>"/grab_lesson/get_all_grab_info"],
                //["power_id"=>4, "name"=>"图片管理",   "url"=>"/pic_manage/pic_info"],
                ["power_id"=>5, "name"=>"节日列表",   "url"=>"/festival/festival_list"],
                ["power_id"=>7, "name"=>"系统消息",   "url"=>"/news_info/stu_message_list"],
                ["power_id"=>10, "name"=>"用户消息列表",   "url"=>"/news_info/stu_detail_message_list"],
                //["power_id"=>36, "name"=>"软件版本控制",   "url"=>"/aliyun_oss/upload_list"],
                ["power_id"=>47, "name"=>"标签库",   "url"=>"/product_tag/tag_list"],
                ["power_id"=>46, "name"=>"老师稳定性参考数据",   "url"=>"/tea_manage_new/approved_data"],
                ["power_id"=>48, "name"=>"老师稳定性参考数据-new",   "url"=>"/tea_manage_new/approved_data_new"],
                ["power_id"=>49, "name"=>"审批数据", "url"=>"/company_wx/show_approv_data"],
                ["power_id"=>19, "name"=>"配置功能", "list"=>[
                    ["power_id"=>34, "name"=>"推广活动",   "url"=>"/tongji2/market_extension"],
                    ["power_id"=>4, "name"=>"图片管理",   "url"=>"/pic_manage/pic_info"],
                    ["power_id"=>36, "name"=>"软件版本控制",   "url"=>"/aliyun_oss/upload_list"],
                ]],


            ]],

            ["power_id"=>10, "name"=>"统计", "list"=>[

                ["power_id"=>15, "name"=>"市场个性海报分享数据",   "url"=>"/tongji2/marketposterdata"],
                ["power_id"=>10, "name"=>"排行榜数据",   "url"=>"/tongji_ex/top_list"],
                ["power_id"=>13, "name"=>"学生成绩录入记录",   "url"=>"/human_resource/get_input_score_list"],
                ["power_id"=>14, "name"=>"家长申请调时间记录",   "url"=>"/human_resource/get_lesson_modify_list"],
                ["power_id"=>17, "name"=>"CC/CR分享H5页面统计数据",   "url"=>"/tongji2/marketPosterData"],
                ["power_id"=>83, "name"=>"销售2", "list"=>[
                    ["power_id"=>1, "name"=>"系统分配例子信息",   "url"=>"/tongji2/tongji_sys_assign_call_info", "domain_type" =>100],
                    ["power_id"=>2, "name"=>"系统释放例子日志",   "url"=>"/tongji2/tongji_sys_free_log", "domain_type" =>100],
                ]],
                ["power_id"=>1, "name"=>"销售", "list"=>[
                    ["power_id"=>1, "name"=>"合同每日统计",   "url"=>"/tongji/contract"],
                    ["power_id"=>2, "name"=>"例子统计",   "url"=>"/tongji/user_count"],
                    ["power_id"=>21, "name"=>"例子统计-个人",   "url"=>"/tongji/seller_user_count"],
                    ["power_id"=>42, "name"=>"例子分配统计",   "url"=>"/seller_student_new2/seller_student_new_distribution"],
                    ["power_id"=>43, "name"=>"例子分配列表",   "url"=>"/seller_student_new2/seller_edit_log_list"],
                    ["power_id"=>41, "name"=>"上课统计",   "url"=>"/seller_student_new2/seller_student_new_lesson"],
                    ["power_id"=>35, "name"=>"销售个人业绩表",   "url"=>"/tongji/seller_personal_money"],
                    ["power_id"=>37, "name"=>"销售个人排行榜",   "url"=>"/tongji/seller_personal_rank"],
                    ["power_id"=>33, "name"=>"销售个效统计表",   "url"=>"/tongji/seller_time_income_list"],
                    ["power_id"=>89, "name"=>"销售个人未回访",   "url"=>"/seller_student_new/no_lesson_call_end_time_list"],
                    ["power_id"=>3, "name"=>"销售时效统计",   "url"=>"/tongji/seller_time"],
                    ["power_id"=>36, "name"=>"销售-时报", "url"=>"/tongji/seller_call_rate"],
                    ["power_id"=>4, "name"=>"试听排课统计", "url"=>"/tongji/test_lesson_tongi"],
                    ["power_id"=>5, "name"=>"试听排课-明细", "url"=>"/tongji/test_lesson_detail_list"],
                    ["power_id"=>22, "name"=>"周排课量统计",   "url"=>"/tongji2/seller_week_lesson" ],
                    ["power_id"=>23, "name"=>"周排课量统计-主管",   "url"=>"/tongji2/seller_week_lesson_master" ],
                    ["power_id"=>24, "name"=>"周排课量回访统计",   "url"=>"/tongji2/seller_week_lesson_call" ],
                    ["power_id"=>26, "name"=>"周排课量回访统计-主管",   "url"=>"/tongji2/seller_week_lesson_call_master" ],
                    ["power_id"=>25, "name"=>"周排课量回访列表",   "url"=>"/tongji2/lesson_call_list" ],
                    ["power_id"=>27, "name"=>"试听课后2小时未回访列表",   "url"=>"/tongji2/lesson_end_call_list" ],
                    ["power_id"=>6, "name"=>"首次回访例子数-小时", "url"=>"/tongji/first_revisite_time_list"],

                    ["power_id"=>61, "name"=>"首次回访例子数-间隔", "url"=>"/tongji2/first_call_info"],

                    ["power_id"=>7, "name"=>"试听首次回访时间统计", "url"=>"/tongji2/test_lesson_frist_call_time"],
                    ["power_id"=>9, "name"=>"试听首次回访时间统计-主管", "url"=>"/tongji2/test_lesson_frist_call_time_master"],
                    ["power_id"=>8, "name"=>"试听转化率统计", "url"=>"/tongji_ss/tongji_seller_test_lesson_order_info"],
                    ["power_id"=>10, "name"=>"试听转化率统计-试卷", "url"=>"/tongji_ss/tongji_seller_test_lesson_paper_order_info"],


                    ["power_id"=>11, "name"=>"例子销售分布", "url"=>"/tongji2/seller_student_admin_list"],

                    ["power_id"=>12, "name"=>"例子销售拨打数", "url"=>"/tongji_ex/call_count"],

                    ["power_id"=>13, "name"=>"转化率", "url"=>"/tongji_ex/test_lesson_order_info"],

                    ["power_id"=>14, "name"=>"转化率-明细", "url"=>"/tongji_ex/test_lesson_order_detail_list"],

                    ["power_id"=>15, "name"=>"销售-周报月报", "url"=>"/main_page/get_seller_total_info"],
                    ["power_id"=>16, "name"=>"特殊申请配额列表",   "url"=>"/seller_student_new2/seller_diff_money"],
                    ["power_id"=>56, "name"=>"拨通率实时预警",   "url"=>"/tongji_ex/actual_call_threshold"],
                ]],

                ["power_id"=>6, "name"=>"助教", "list"=>[
                    ["power_id"=>1, "name"=>"课时统计",   "url"=>"/tongji/test_lesson_ass"],
                    ["power_id"=>2, "name"=>"课时统计-助教",   "url"=>"/tongji/test_lesson_ass_self"],
                    ["power_id"=>3, "name"=>"回访统计-助教",   "url"=>"/tongji/revisit_info_tongji_ass"],
                    ["power_id"=>4, "name"=>"电话统计-助教",   "url"=>"/tq/ass_tongji_list"],
                    ["power_id"=>5, "name"=>"电话统计-个人",   "url"=>"/tq/ass_self_tongji_list"],
                    ["power_id"=>6, "name"=>"换老师统计",   "url"=>"/tongji_ss/tongji_change_teacher_info"],
                    ["power_id"=>7, "name"=>"扩课统计",   "url"=>"/tongji_ss/tongji_kuoke_info"],
                    ["power_id"=>8, "name"=>"转介绍统计",   "url"=>"/tongji_ss/tongji_referral"],
                    ["power_id"=>9, "name"=>"调课统计-老师",   "url"=>"/tongji_ss/tongji_change_lesson_by_teacher"],
                    ["power_id"=>10, "name"=>"调课统计-家长",   "url"=>"/tongji_ss/tongji_change_lesson_by_parent"],
                    ["power_id"=>11, "name"=>"科目统计-学生",   "url"=>"/user_manage/tongji_student_subject"],
                    ["power_id"=>12, "name"=>"课耗年级统计-学生",   "url"=>"/user_manage/tongji_grade_lesson_count"],
                    ["power_id"=>13, "name"=>"科目年级-学生",   "url"=>"/user_manage/tongji_student_grade_subject"],
                ]],
                ["power_id"=>2, "name"=>"消息统计", "list"=>[
                    ["power_id"=>1, "name"=>"短信日期统计",   "url"=>"/tongji/sms"],
                    ["power_id"=>2, "name"=>"短信分类统计",   "url"=>"/tongji/sms_type"],
                ]],
                ["power_id"=>3, "name"=>"招师", "list"=>[
                ]],
                ["power_id"=>4, "name"=>"获赞", "list"=>[
                    ["power_id"=>1, "name"=>"获赞统计",   "url"=>"/user_manage/count_zan"],
                    ["power_id"=>2, "name"=>"获赞类别详情",   "url"=>"/user_manage/zan_info"],
                ],],

                ["power_id"=>11, "name"=>"登入", "list"=>[
                    ["power_id"=>10, "name"=>"用户登录统计",   "url"=>"/tongji_ex/user_login"],
                    ["power_id"=>11, "name"=>"用户登录明细",   "url"=>"/tongji_ex/user_login_list"],
                    ["power_id"=>12, "name"=>"用户登录统计-new",   "url"=>"/tongji_ex/get_lesson_user_ip_same_info"],
                    ["power_id"=>1, "name"=>"后台详情",   "url"=>"/user_manage/user_login_list"],
                    ["power_id"=>2, "name"=>"后台统计",   "url"=>"/user_manage/tongji_login_ip_info"],
                ]],


            ]],

            ["power_id"=>20, "name"=>"个人中心",  "list"=>[
                //["power_id"=>1, "name"=>"我的信息",   "url"=>"/self_manage" ],

                ["power_id"=>4, "name"=>"TODO列表",   "url"=>"/self_manage/todo_list"],
                ["power_id"=>5, "name"=>"考勤信息",   "url"=>"/tongji/admin_card_date_log_list_self"],
                ["power_id"=>91, "name"=>"知识库",   "url"=>"/seller_student_new/wiki"],
                ["power_id"=>2, "name"=>"请假",   "url"=>"/self_manage/qingjia" ],
                ["power_id"=>10, "name"=>"审批",   "url"=>"/self_manage/flow_list" ],
                ["power_id"=>11, "name"=>"菜单收藏",   "url"=>"/self_manage/self_menu_list" ],
                //                ["power_id"=>12, "name"=>"开发需求提交",   "url"=>"/requirement/requirement_info"],
                ["power_id"=>12, "name"=>"开发需求提交",   "url"=>"/requirement/requirement_info_new"],

                ["power_id"=>13, "name"=>"上传头像",   "url"=>"/self_manage/upload_face_pic"],
                ["power_id"=>14, "name"=>"tmk合同",   "url"=>"/self_manage/get_self_order_list"],
                //                ["power_id"=>14, "name"=>"分享知识库", "url"=>"/seller_student_new/share_knowledge"],
                ["power_id"=>49, "name"=>"拉取数据审批", "url"=>"/company_wx/show_approv_data_self"],
            ]],

            ["power_id"=>11, "name"=>"角色-销售",  "list"=>[
                ["power_id"=>60, "name"=>"排行榜",   "url"=>"/main_page/seller" ],
                // ["power_id"=>44, "name"=>"tom拉数据",   "url"=>"/agent/check" ],
                ["power_id"=>61, "name"=>"藏金阁签约排名",   "url"=>"/main_page/seller_gold_room" ],
                ["power_id"=>62, "name"=>"转化率统计总表",   "url"=>"/seller_student_new2/tongji_sign_rate" ],
                ["power_id"=>49, "name"=>"测试课堂列表",   "url"=>"/test_lesson_opt/test_opt_list"],
                ["power_id"=>67, "name"=>"教师排课信息",   "url"=>"/human_resource/teacher_info_for_seller" ],
                ["power_id"=>75, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list_seller"],
                ["power_id"=>68, "name"=>"老师推荐",   "url"=>"/human_resource/index_seller" ],
                ["power_id"=>69, "name"=>"精品老师",   "url"=>"/human_resource/get_elite_teacher_list" ],
                ["power_id"=>76, "name"=>"暂停试听课老师",   "url"=>"/human_resource/index_new_seller_hold" ],

                ["power_id"=>66, "name"=>"课堂状态-销售",   "url"=>"/supervisor/monitor_seller"],

                ["power_id"=>64, "name"=>"课程列表-销售",   "url"=>"/tea_manage/lesson_list_seller"],
                ["power_id"=>70, "name"=>"教学质量反馈列表",   "url"=>"/tea_manage_new/get_seller_ass_record_info_seller"],
                ["power_id"=>71, "name"=>"申请推荐试听老师列表",   "url"=>"/tea_manage_new/get_seller_require_commend_teacher_info_seller"],

                ["power_id"=>1, "name"=>"所有用户",   "url"=>"/seller_student_new/seller_student_list_all"],
                ["power_id"=>38, "name"=>"排课审核",   "url"=>"/test_lesson_review/test_lesson_review_list"],
                ["power_id"=>2, "name"=>"查找用户所在",   "url"=>"/seller_student_new/find_user"],
                ["power_id"=>3, "name"=>"试听签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list_seller"],

                ["power_id"=>59, "name"=>"讲师申请帮助",   "url"=>"/teacher_apply/teacher_apply_list_one" ],

                // ["power_id"=>20, "name"=>"合同-待付费",   "url"=>"/user_manage/contract_list_seller_add"],
                // ["power_id"=>21, "name"=>"合同-已付费",   "url"=>"/user_manage/contract_list_seller_payed"],
                // ["power_id"=>22, "name"=>"合同",   "url"=>"/user_manage/contract_list_seller_mix"],
                ["power_id"=>22, "name"=>"合同",   "url"=>"/user_manage/contract_list_seller"],
                // ["power_id"=>29, "name"=>"合同-已付费-主管",   "url"=>"/user_manage_new/contract_list_seller_payed_new"],
                ["power_id"=>40, "name"=>"new-转介绍例子",   "url"=>"/seller_student_new/seller_seller_student_list"],

                ["power_id"=>53, "name"=>"例子回流公海",   "url"=>"/seller_student_new/get_hold_list"],
                ["power_id"=>54, "name"=>"公海-抢学生",   "url"=>"/seller_student_new/get_free_seller_list"],
                ["power_id"=>55, "name"=>"抢新学生",   "url"=>"/seller_student_new/deal_new_user"],

                ["power_id"=>56, "name"=>"抢新学生-tmk",   "url"=>"/seller_student_new/get_new_list_tmk"],
                ["power_id"=>57, "name"=>"抢新学生-当前用户-tmk", "url"=>"/seller_student_new/deal_new_user_tmk"],

                ["power_id"=>51, "name"=>"试听未签-抢学生",   "url"=>"/seller_student_new/test_lesson_no_order_list"],
                ["power_id"=>58, "name"=>"地中海-抢学生",   "url"=>"/seller_student_new/test_lesson_fail_list"],
                ["power_id"=>73, "name"=>"销售等级列表",   "url"=>"/seller_level_goal/seller_level_goal_list"],
                ["power_id"=>74, "name"=>"销售等级工资列表",   "url"=>"/seller_level_goal/seller_level_salary_list"],
                ["power_id"=>79, "name"=>"销售月末定级列表",   "url"=>"/seller_level_goal/seller_level_month_list"],
                ["power_id"=>32, "name"=>"销售-退款",   "url"=>"/user_manage/refund_list_seller"],
                ["power_id"=>31, "name"=>"月度绩效提成",   "url"=>"/tongji2/self_seller_month_money_list"],
                ["power_id"=>35, "name"=>"销售-试听课表",   "url"=>"/human_resource/regular_course_seller"],
                ["power_id"=>23, "name"=>"销售自定义时间",   "url"=>"/month_def_type/def_list"],
                // ["power_id"=>36, "name"=>"销售-修改日志",   "url"=>"/authority/seller_edit_log_list"],

                ["power_id"=>91, "name"=>"状态分类",  "list"=>[
                    ["power_id"=>2, "name"=>"未回访&未接通",   "url"=>"/seller_student_new/seller_student_list_0"],

                    ["power_id"=>3, "name"=>"次优先跟进",   "url"=>"/seller_student_new/seller_student_list_101"],

                    ["power_id"=>4, "name"=>"被驳回&取消",   "url"=>"/seller_student_new/seller_student_list_110"],
                    ["power_id"=>5, "name"=>"优先跟进",   "url"=>"/seller_student_new/seller_student_list_103"],
                    ["power_id"=>6, "name"=>"待排课",   "url"=>"/seller_student_new/seller_student_list_200"],
                    ["power_id"=>7, "name"=>"待通知",   "url"=>"/seller_student_new/seller_student_list_210"],
                    ["power_id"=>8, "name"=>"待开课",   "url"=>"/seller_student_new/seller_student_list_220"],

                    ["power_id"=>10, "name"=>"已试听-待跟进",   "url"=>"/seller_student_new/seller_student_list_290"],
                    ["power_id"=>9, "name"=>"已试听",   "url"=>"/seller_student_new/seller_student_list_301"],

                    ["power_id"=>30, "name"=>"无效用户",   "url"=>"/seller_student_new/seller_student_list_1"],
                ]],

                ["power_id"=>90, "name"=>"旧版",  "list"=>[

                    ["power_id"=>2, "name"=>"我负责的用户",   "url"=>"/seller_student/student_list2"],

                    ["power_id"=>12, "name"=>"未打通用户-抢学生",   "url"=>"/seller_student/no_called_list"],

                    ["power_id"=>7, "name"=>"转介绍例子-销售",   "url"=>"/seller_student/ass_add_student_list_seller"],
                    ["power_id"=>3, "name"=>"我负责的重复用户",   "url"=>"/seller_student/student_sub_list"],
                ]],



            ]],

            ["power_id"=>13, "name"=>"角色-助教组长",  "list"=>[
                ["power_id"=>100, "name"=>"首页",   "url"=>"/main_page/assistant_leader" ],
                ["power_id"=>80, "name"=>"首页-new",   "url"=>"/main_page/assistant_leader_new" ],
                ["power_id"=>88, "name"=>"首页-new2.0",   "url"=>"/tongji2/ass_month_kpi_tongji_master" ],
                ["power_id"=>82, "name"=>"首页-主管",   "url"=>"/main_page/assistant_main_leader_new" ],
                ["power_id"=>87, "name"=>"首页-主管2.0",   "url"=>"/tongji2/ass_month_kpi_tongji" ],
                ["power_id"=>20, "name"=>"助教薪资",   "url"=>"/assistant_performance/performance_info_show" ],
                ["power_id"=>83, "name"=>"周报-助长",   "url"=>"/tongji_ss/ass_weekly_info" ],
                ["power_id"=>84, "name"=>"周报-主管",   "url"=>"/tongji_ss/ass_weekly_info_master" ],
                ["power_id"=>57, "name"=>"月报-主管",   "url"=>"/tongji_ss/ass_month_info" ],
                ["power_id"=>81, "name"=>"助教组长KPI",   "url"=>"/tongji_ss/tongji_ass_leader_kpi" ],
                ["power_id"=>98, "name"=>"助教组长满意度回访",   "url"=>"/user_manage/ass_random_revisit" ],
                ["power_id"=>85, "name"=>"助教KPI",   "url"=>"/tongji_ss/tongji_ass_kpi" ],
                ["power_id"=>86, "name"=>"助教KPI-主管",   "url"=>"/tongji_ss/tongji_ass_kpi_master" ],
                ["power_id"=>99, "name"=>"每月系数录入",  "url"=>"/user_manage_new/assistant_admin_member_list"],
                ["power_id"=>54, "name"=>"预警学员信息",   "url"=>"/user_manage_new/ass_warning_stu_info_leader" ],
                ["power_id"=>56, "name"=>"预警学员信息-new",   "url"=>"/user_manage_new/ass_warning_stu_info_leader_new" ],
                ["power_id"=>14, "name"=>"学员档案-全部",   "url"=>"/user_manage/ass_archive"],
                ["power_id"=>60, "name"=>"每月学员科目统计",   "url"=>"/user_manage/user_info_by_month"],
                ["power_id"=>12, "name"=>"转介绍例子",   "url"=>"/seller_student/ass_add_student_list"],
                ["power_id"=>16, "name"=>"转介绍例子-主管",   "url"=>"/seller_student_new2/get_ass_tran_to_seller_detail_info"],
                ["power_id"=>17, "name"=>"转介绍例子-组长",   "url"=>"/seller_student_new2/get_ass_tran_to_seller_detail_info_leader"],
                ["power_id"=>11, "name"=>"试听课跟进-主管",   "url"=>"/seller_student_new2/get_ass_test_lesson_info_master"],
                ["power_id"=>13, "name"=>"试听课跟进-组长",   "url"=>"/seller_student_new2/get_ass_test_lesson_info_leader"],
                ["power_id"=>15, "name"=>"转介绍试听跟进-主管", "url"=>"/seller_student_new2/get_from_ass_tran_lesson_info_master"],
                ["power_id"=>1, "name"=>"签约管理",   "url"=>"/user_manage_new/ass_contract_list"],
                ["power_id"=>2, "name"=>"助教课时统计",   "url"=>"/user_manage_new/ass_lesson_count_list"],
                ["power_id"=>3, "name"=>"老师课时统计",   "url"=>"/user_manage_new/tea_lesson_count_list"],
                ["power_id"=>33, "name"=>"学生课时统计",   "url"=>"/user_manage_new/stu_lesson_count_list"],
                ["power_id"=>4, "name"=>"回访统计-助教",   "url"=>"/user_manage/ass_count"],
                ["power_id"=>5, "name"=>"回访统计",   "url"=>"/user_manage/ass_counts"],
                ["power_id"=>6, "name"=>"学员课时信息",   "url"=>"/user_manage_new/lesson_count_user_list"],
                ["power_id"=>7, "name"=>"回访类别统计",   "url"=>"/user_manage_new/lesson_count_type_list"],
                ["power_id"=>26, "name"=>"常规课表",   "url"=>"/human_resource/regular_course_all"],
                ["power_id"=>8, "name"=>"寒假课表",   "url"=>"/human_resource/winter_regular_course_all"],
                ["power_id"=>27, "name"=>"按常规课程排课",   "url"=>"/tea_manage/course_plan_stu"],
                ["power_id"=>28, "name"=>"教育学排课表",   "url"=>"/tea_manage/course_plan_psychological"],
                ["power_id"=>40, "name"=>"招师统计",   "url"=>"/tongji_ss/tongji_zs_teacher_info"],
                ["power_id"=>41, "name"=>"学情回访预警信息",   "url"=>"/user_manage_new/ass_revisit_warning_info"],
                ["power_id"=>75, "name"=>"限课特殊申请",   "url"=>"/seller_student_new2/test_lesson_plan_list_ass_leader"],
                ["power_id"=>55, "name"=>"结课两周用户统计",   "url"=>"/user_manage_new/get_two_weeks_old_stu_seller"],
                ["power_id"=>25, "name"=>"学生在校成绩统计",   "url"=>"/user_manage/student_school_score_stat"],
                ["power_id"=>42, "name"=>"学生单科目课时消耗",  "url"=>"/user_manage/student_single_subject"],
                ["power_id"=>43, "name"=>"未试听扩课&&转介绍数量",  "url"=>"/user_manage/ass_no_test_lesson_kk_list"],
                ["power_id"=>44, "name"=>"在读学员老师列表",  "url"=>"/user_manage/stu_all_teacher_all"],
                ["power_id"=>49, "name"=>"周报-月报-new", "url" => "/tongji2/tongji_cr"],
                ["power_id"=>50, "name"=>"新例子-主管", "url" => "/seller_student_new/get_new_student_ass_leader"],
                ["power_id"=>51, "name"=>"助教转介绍报表", "url" => "/assistant_performance/get_assistant_origin_order_losson_info_all"],

            ]],

            ["power_id"=>12, "name"=>"角色-助教",  "list"=>[
                ["power_id"=>1, "name"=>"首页",   "url"=>"/main_page/assistant" ],
                ["power_id"=>53, "name"=>"首页-new",   "url"=>"/main_page/assistant_new" ],
                ["power_id"=>54, "name"=>"预警学员信息",   "url"=>"/user_manage_new/ass_warning_stu_info" ],
                ["power_id"=>55, "name"=>"预警学员信息-new",   "url"=>"/user_manage_new/ass_warning_stu_info_new" ],
                ["power_id"=>41, "name"=>"学情回访预警信息",   "url"=>"/user_manage_new/ass_revisit_warning_info_sub"],
                ["power_id"=>5, "name"=>"学员档案-助教",   "url"=>"/user_manage/ass_archive_ass"],
                ["power_id"=>3, "name"=>"课堂状态-助教",   "url"=>"/supervisor/monitor_ass"],
                ["power_id"=>2, "name"=>"学员合同-助教",   "url"=>"/user_manage/contract_list_ass"],
                ["power_id"=>33, "name"=>"学生课时统计-助教",   "url"=>"/user_manage_new/stu_lesson_count_list_ass"],
                ["power_id"=>6, "name"=>"兑换管理-助教",   "url"=>"/user_manage_new/commodity_exchange_management_assistant"],
                ["power_id"=>20, "name"=>"课程管理-助教",   "url"=>"/tea_manage/lesson_list_ass"],
                ["power_id"=>59, "name"=>"讲师申请帮助",   "url"=>"/teacher_apply/teacher_apply_list_two" ],
                ["power_id"=>60, "name"=>"教育课排课",   "url"=>"/user_manage_new/get_ass_psychological_lesson" ],
                ["power_id"=>26, "name"=>"排课",   "url"=>"/tea_manage/course_plan"],
                ["power_id"=>27, "name"=>"按常规课程排课",   "url"=>"/tea_manage/course_plan_stu_ass"],
                ["power_id"=>21, "name"=>"教师档案",   "url"=>"/human_resource/index_ass"],
                ["power_id"=>8, "name"=>"常规课表",   "url"=>"/human_resource/regular_course"],
                ["power_id"=>48, "name"=>"三周常规课表未排课",   "url"=>"/user_manage_new/user_regular_course_check_info"],
                ["power_id"=>9, "name"=>"寒假课表",   "url"=>"/human_resource/winter_regular_course"],
                ["power_id"=>80, "name"=>"按寒假课程排课",   "url"=>"/tea_manage/course_plan_stu_winter"],
                //  ["power_id"=>77, "name"=>"寒假课表-new",   "url"=>"/human_resource/winter_teacher_lesson_list"],
                ["power_id"=>78, "name"=>"暑假课表",   "url"=>"/human_resource/summer_regular_course"],
                ["power_id"=>79, "name"=>"按暑假课程排课",   "url"=>"/tea_manage/course_plan_stu_summer"],
                ["power_id"=>10, "name"=>"教师所带学生",   "url"=>"/user_manage_new/stu_all_info"],
                ["power_id"=>22, "name"=>"教师特长",   "url"=>"/human_resource/specialty"],
                ["power_id"=>30, "name"=>"小班管理",  "list"=>[
                    ["power_id"=>23, "name"=>"小班管理-助教",   "url"=>"/small_class/index_ass"],
                    ["power_id"=>24, "name"=>"小班课次管理-助教",   "url"=>"/small_class/lesson_list_new_ass"],
                    ["power_id"=>25, "name"=>"小班学生列表-助教",   "url"=>"/small_class/student_list_new_ass"],
                ]],
                ["power_id"=>50, "name"=>"new-试听申请(常规)",   "url"=>"/seller_student_new2/ass_test_lesson_list"],
                ["power_id"=>56, "name"=>"new-试听申请(转介绍)",   "url"=>"/seller_student_new2/ass_test_lesson_list_tran"],
                ["power_id"=>11, "name"=>"试听课跟进",   "url"=>"/seller_student_new2/get_ass_test_lesson_info"],
                ["power_id"=>12, "name"=>"转介绍试听跟进",   "url"=>"/seller_student_new2/get_from_ass_tran_lesson_info"],

                ["power_id"=>51, "name"=>"new-转介绍例子",   "url"=>"/seller_student_new/ass_seller_student_list"],
                ["power_id"=>52, "name"=>"老师反馈","url"=>"/teacher_feedback/teacher_feedback_list_ass"],
                ["power_id"=>92, "name"=>"助教-退款",   "url"=>"/user_manage/refund_list_ass"],
                ["power_id"=>93, "name"=>"更换老师申请列表",   "url"=>"/user_manage_new/get_ass_change_teacher_info_ass"],
                ["power_id"=>71, "name"=>"申请推荐老师列表",  "url"=>"/tea_manage_new/get_seller_require_commend_teacher_info_ass"],
                ["power_id"=>94, "name"=>"教学质量反馈列表",   "url"=>"/tea_manage_new/get_seller_ass_record_info_ass"],

                ["power_id"=>74, "name"=>"毕业班课时统计",   "url"=>"/user_manage/graduating_lesson_time"],
                ["power_id"=>95, "name"=>"未录入成绩学生列表",   "url"=>"/user_manage/no_type_student_score"],
                ["power_id"=>96, "name"=>"在读学员老师列表",   "url"=>"/user_manage/stu_all_teacher"],
                ["power_id"=>97, "name"=>"产品问题记录-技术支持",   "url"=>"/user_manage_new/product_info"],

            ]],

            ["power_id"=>14, "name"=>"角色-教务",  "list"=>[
                ["power_id"=>1, "name"=>"首页",   "url"=>"/main_page/jw_teacher" ],
                ["power_id"=>12, "name"=>"KPI",   "url"=>"/tongji_ss/tongji_jw_teacher_kpi" ],
                ["power_id"=>14, "name"=>"未排统计",   "url"=>"/tongji_ss/get_jw_no_plan_remind" ],
                ["power_id"=>5, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list_jw"],
                ["power_id"=>13, "name"=>"抢单库", "url"=>"/seller_student_new2/grab_test_lesson_list"],
                ["power_id"=>6, "name"=>"排课明细",   "url"=>"/seller_student_new2/test_lesson_detail_list_jw"],
                ["power_id"=>10, "name"=>"教务-排课明细",   "url"=>"/tongji_ss/test_lesson_plan_detail_list_jw"],
                ["power_id"=>3, "name"=>"教务-教师档案",   "url"=>"/human_resource/index_jw"],
                ["power_id"=>2, "name"=>"教师档案(常规)",   "url"=>"/human_resource/index_new_jw"],
                ["power_id"=>9, "name"=>"教师档案(休课)",   "url"=>"/human_resource/index_new_jw_hold"],
                ["power_id"=>15, "name"=>"教师档案(离职)",   "url"=>"/human_resource/quit_teacher_info"],
                ["power_id"=>11, "name"=>"分配老师",   "url"=>"/human_resource/get_assign_jw_adminid_list"],
                ["power_id"=>16, "name"=>"教材版本匹配",   "url"=>"/human_resource/get_check_textbook_tea_list"],
                ["power_id"=>4, "name"=>"教师试讲预约",   "url"=>"/human_resource/teacher_lecture_appointment_info"],
                ["power_id"=>7, "name"=>"转介绍统计","url"=>"/human_resource/teacher_lecture_origin_count"],
                ["power_id"=>8, "name"=>"老师反馈","url"=>"/teacher_feedback/teacher_feedback_list_jw"],
                ["power_id"=>26, "name"=>"投诉老师列表",   "url"=>"/tea_manage_new/get_teacher_complaints_info_jw"],
                ["power_id"=>27, "name"=>"销售个人排行榜",   "url"=>"/tongji_ss/get_seller_rank_for_jw"],
                ["power_id"=>28, "name"=>"限课特殊申请",   "url"=>"/seller_student_new2/test_lesson_plan_list_jw_leader"],
                // ["power_id"=>29, "name"=>"CC转化率统计", "url"=>"/user_manage/tongji_cc"],
                ["power_id"=>30, "name"=>"月份-科目统计", "url"=>"/user_manage/subject_by_month"],
                ["power_id"=>31, "name"=>"师资管理-首页", "url"=>"/main_page/teacher_management_info"],

            ]],


            ["power_id"=>25, "name"=>"角色-招师",  "list"=>[
                ["power_id"=>1, "name"=>"首页",   "url"=>"/main_page/zs_teacher_new" ],
                ["power_id"=>3, "name"=>"教师档案",   "url"=>"/human_resource/index_zs"],
                [ "power_id"=>22,"name"=>"面试转化信息",   "url"=>"/main_page/zs_teacher_old" ],
                // ["power_id"=>12, "name"=>"试听转化率统计-招师","url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_zs"],
                // ["power_id"=>13, "name"=>"试听转化率统计","url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_for_jx"],

                // ["power_id"=>4, "name"=>"教师试讲预约",   "url"=>"/human_resource/teacher_lecture_appointment_info_zs"],
                ["power_id"=>2, "name"=>"教研老师信息",   "url"=>"/human_resource/reaearch_teacher_lesson_list"],
                ["power_id"=>5, "name"=>"招师统计",   "url"=>"/tongji_ss/tongji_zs_reference"],
                ["power_id"=>6, "name"=>"面试试讲列表",   "url"=>"/train_teacher/train_lecture_lesson_list"],
                ["power_id"=>7, "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson_zs"],
                ["power_id"=>10, "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_zs"],
                ["power_id"=>11, "name"=>"面试转化率模型",  "list"=>[
                    ["power_id"=>4, "name"=>"教研老师面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji_zs"],
                    ["power_id"=>1, "name"=>"面试转化数据-年级科目",   "url"=>"/tongji_ss/interview_subject_grade_tongji_zs"],
                    ["power_id"=>5, "name"=>"招师渠道面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji_by_reference_zs"],
                    ["power_id"=>10,"name"=>"面试各项指标评分", "url"=>"/human_resource/get_teacher_lecture_fail_score_info_zs"],
                ]],
                ["power_id"=>8, "name"=>"渠道统计",   "url"=>"/human_resource/origin_list"],
                ["power_id"=>9, "name"=>"渠道统计-new",   "url"=>"/human_resource/zs_origin_list"],
                ["power_id"=>20, "name"=>"招师-渠道管理",   "url"=>"/channel_manage/admin_channel_manage"],
                ["power_id"=>21, "name"=>"渠道统计-new-list", "url"=>"/channel_manage/zs_origin_list_new"],
                ["power_id"=>29, "name"=>"模拟试听审核",   "url"=>"/tea_manage/trial_train_lesson_list_zs"],

                // ["power_id"=>14, "name"=>"老师身份统计",   "url"=>"/tongji2/tongji_lesson_teacher_identity"],

            ]],

            ["power_id"=>37, "name"=>"角色-质监",  "list"=>[
                ["power_id"=>1, "name"=>"首页", "url"=>"/main_page/quality_control_kpi"],
                //["power_id"=>4, "name"=>"质监KPI", "url"=>"/main_page/quality_control_kpi"],
                ["power_id"=>2, "name"=>"质监排行榜", "url"=>"/tongji_ss/tongji_teaching_and_research_teacher_test_lesson_info_zj"],
                ["power_id"=>3, "name"=>"老师课程管理",   "url"=>"/tea_manage/lesson_list_zj"],
                // ["power_id"=>8, "name"=>"试听转化率统计-质监", "url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_zj"],
                // ["power_id"=>13, "name"=>"新老师第1次教学质量反馈",   "url"=>"/tongji_ss/teacher_first_test_lesson_week_zj"],
                ["power_id"=>28, "name"=>"教学质量反馈报告汇总（新）",   "url"=>"/human_resource/teacher_record_detail_list_new_zj"],
                ["power_id"=>16, "name"=>"教学质量反馈报告汇总（旧）",   "url"=>"/human_resource/teacher_record_detail_list_zj"],
                ["power_id"=>29, "name"=>"模拟试听审核",   "url"=>"/tea_manage/trial_train_lesson_list_zj"],
                ["power_id"=>9, "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_zj"],
                ["power_id"=>36, "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson_zj"],
                // ["power_id"=>37, "name"=>"面试加班信息",   "url"=>"/tongji_ss/tongji_teacher_1v1_lesson_time"],
                ["power_id"=>38, "name"=>"第一次试听课反馈",   "url"=>"/teacher_level/get_first_test_lesson_info"],
                ["power_id"=>39, "name"=>"第五次试听课反馈",   "url"=>"/teacher_level/get_fifth_test_lesson_info"],
                ["power_id"=>40, "name"=>"第一次常规课反馈",   "url"=>"/teacher_level/get_first_regular_lesson_info"],
                ["power_id"=>41, "name"=>"第五次常规课反馈",   "url"=>"/teacher_level/get_fifth_regular_lesson_info"],
                ["power_id"=>44, "name"=>"精排1000教学反馈",   "url"=>"/teacher_level/get_seller_top_test_lesson_info"],
                ["power_id"=>42, "name"=>"兼职老师转化率明细",   "url"=>"/human_resource/teacher_test_lesson_info_zj"],
                [ "power_id"=>43,"name"=>"兼职老师档案",   "url"=>"/human_resource/index_tea_qua_zj"],
                ["power_id"=>30, "name"=>"面试转化率模型",  "list"=>[
                    ["power_id"=>4, "name"=>"教研老师面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji"],
                    ["power_id"=>1, "name"=>"面试转化数据-年级科目",   "url"=>"/tongji_ss/interview_subject_grade_tongji"],
                    ["power_id"=>5, "name"=>"招师渠道面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji_by_reference"],
                    ["power_id"=>10,"name"=>"面试各项指标评分", "url"=>"/human_resource/get_teacher_lecture_fail_score_info"],
                ]],
                ["power_id"=>32, "name"=>"教学质量反馈模型",  "list"=>[
                    ["power_id"=>15, "name"=>"教学质量反馈标准（旧）",   "url"=>"/human_resource/teacher_record_detail_info"],
                    ["power_id"=>29, "name"=>"教学质量反馈标准（新)",   "url"=>"/human_resource/teacher_record_detail_info_new"],
                ]],
                ["power_id"=>50, "name"=>"未审核统计",   "url"=>"/user_manage/tongji_check"],
                ["power_id"=>51, "name"=>"质监数据统计",   "url"=>"/tongji_ss/tongji_lesson_record_info"],
                ["power_id"=>52, "name"=>"质监数据总体", "url"=>"/main_page/quality_control"],
                ["power_id"=>53, "name"=>"学生老师数量统计", "url"=>"/tongji_ss/get_lesson_tea_stu_info"],
            ]],

            ["power_id"=>35, "name"=>"角色-培训",  "list"=>[
                ["power_id"=>1, "name"=>"首页",   "url"=>"/main_page/zs_teacher" ],
                ["power_id"=>4, "name"=>"老师培训管理",   "url"=>"/tea_manage/train_lesson_list_research"],
                ["power_id"=>5, "name"=>"新师培训未通过名单",  "url"=>"/tea_manage/train_not_through_list_px"],
                ["power_id"=>2, "name"=>"模拟试听未通过名单",   "url"=>"/tea_manage/trial_train_no_pass_list"],
                ["power_id"=>3, "name"=>"模拟试听未排名单",   "url"=>"/tongji_ss/get_no_time_train_lesson_teacher_list"],
                ["power_id"=>6, "name"=>"培训进度列表",   "url"=>"/tea_manage/teacher_train_list"],
                ["power_id"=>7, "name"=>"待培训名单",   "url"=>"/tea_manage/teacher_cc_count"],
            ]],

            ["power_id"=>36, "name"=>"角色-运营",  "list"=>[
                ["power_id"=>45, "name"=>"兼职老师薪资处理",   "url"=>"/user_manage/complaint_department_deal_teacher_tea"],
                ["power_id"=>1, "name"=>"兼职老师晋升申请","url"=>"/teacher_level/get_teacher_level_quarter_info_show"],
                ["power_id"=>3, "name"=>"助教课时折损统计",   "url"=>"/tongji/test_lesson_ass_jy"],
                ["power_id"=>4, "name"=>"兼职老师上课考勤",   "url"=>"/tongji_ss/tongji_change_lesson_by_teacher_jy"],
                ["power_id"=>5, "name"=>"兼职老师更换申请",   "url"=>"/user_manage_new/get_ass_change_teacher_info"],
                ["power_id"=>6, "name"=>"兼职老师投诉处理",   "url"=>"/tea_manage_new/get_seller_ass_record_info"],
                ["power_id"=>7, "name"=>"助教换老师统计",   "url"=>"/tongji_ss/get_change_teacher_info"],
                ["power_id"=>8, "name"=>"兼职老师退费处理",   "url"=>"/tongji_ss/get_refund_teacher_and_ass_info"],
                ["power_id"=>9, "name"=>"薪资版本切换列表",   "url"=>"/teacher_level/teacher_switch_list"],
                ["power_id"=>27, "name"=>"老师推荐申请",   "url"=>"/tea_manage_new/get_seller_require_commend_teacher_info_yy"],
                ["power_id"=>10, "name"=>"投诉处理-QC",   "url"=>"/user_manage/qc_complaint_tea"],
                ["power_id"=>11, "name"=>"微信推送",   "url"=>"/user_manage_new/wx_monitor_new_yy"],
                ["power_id"=>30, "name"=>"教师预警","url"=>"/teacher_warn/tea_warn_list"],
            ]],




            ["power_id"=>32, "name"=>"角色-教研",  "list"=>[
                ['power_id'=>74, 'name'=>"教研备课上传","list" => [
                    ["power_id"=>05, "name"=>"教研备课资料框架-新",   "url"=>"/resource/resource_frame_new"],
                    ["power_id"=>02, "name"=>"教研备课数据统计",   "url"=>"/resource/resource_count"],
                    ["power_id"=>03, "name"=>"教研备课后台",   "url"=>"/resource/get_all"],
                    ["power_id"=>04, "name"=>"教研回收站",   "url"=>"/resource/get_del"],
                ]],
                ["power_id"=>5, "name"=>"考勤信息",   "url"=>"/user_manage_new/get_fulltime_teacher_attendance_info"],
                ["power_id"=>7,"name"=>"教研排行榜", "url"=>"/tongji_ss/tongji_teaching_and_research_teacher_test_lesson_info"],
                ["power_id"=>23,"name"=>"KPI考核标准", "url"=>"/tongji_ss/research_teacher_kpi_info_new"],
                // ["power_id"=>2, "name"=>"兼职老师转化率总体",   "url"=>"/human_resource/teacher_test_lesson_info_total"],
                ["power_id"=>1, "name"=>"兼职老师转化率明细",   "url"=>"/human_resource/teacher_test_lesson_info"],
                // ["power_id"=>21, "name"=>"各学科转化率总体",   "url"=>"/tongji_ss/test_lesson_order_per_subject"],
                ["power_id"=>14, "name"=>"兼职老师档案",   "url"=>"/human_resource/index_tea_qua"],
                // ["power_id"=>13, "name"=>"新老师第1次教学质量反馈",   "url"=>"/tongji_ss/teacher_first_test_lesson_week"],
                ["power_id"=>28, "name"=>"教学质量反馈报告汇总（新）",   "url"=>"/human_resource/teacher_record_detail_list_new"],
                ["power_id"=>16, "name"=>"教学质量反馈报告汇总（旧）",   "url"=>"/human_resource/teacher_record_detail_list"],
                ["power_id"=>3, "name"=>"老师课程管理",   "url"=>"/tea_manage/lesson_list_research"],
                // ["power_id"=>17, "name"=>"兼职老师更换申请",   "url"=>"/user_manage_new/get_ass_change_teacher_info"],
                // ["power_id"=>18, "name"=>"兼职老师投诉处理",   "url"=>"/tea_manage_new/get_seller_ass_record_info"],
                ["power_id"=>27, "name"=>"老师推荐申请",   "url"=>"/tea_manage_new/get_seller_require_commend_teacher_info"],
                // ["power_id"=>24, "name"=>"老师试听薪资排行",   "url"=>"/tongji_ss/teacher_trial_count"],
                ["power_id"=>29, "name"=>"模拟试听审核",   "url"=>"/tea_manage/trial_train_lesson_list"],
                ["power_id"=>9, "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_research"],
                ["power_id"=>36, "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson"],
                ["power_id"=>18, "name"=>"老师晋升申请","url"=>"/teacher_level/get_teacher_level_quarter_info_new"],
                ["power_id"=>38, "name"=>"第一次试听课反馈",   "url"=>"/teacher_level/get_first_test_lesson_info_jy"],
                ["power_id"=>39, "name"=>"第五次试听课反馈",   "url"=>"/teacher_level/get_fifth_test_lesson_info_jy"],
                ["power_id"=>40, "name"=>"第一次常规课反馈",   "url"=>"/teacher_level/get_first_regular_lesson_info_jy"],
                ["power_id"=>41, "name"=>"第五次常规课反馈",   "url"=>"/teacher_level/get_fifth_regular_lesson_info_jy"],
                ["power_id"=>42, "name"=>"教材版本管理",   "url"=>"/textbook_manage/get_subject_grade_textbook_info"],
                ["power_id"=>43, "name"=>"教材版本地图",   "url"=>"/textbook_manage/show_textbook_map"],
                ["power_id"=>44, "name"=>"投诉处理-家长",   "url"=>"/user_manage/complaint_department_deal_parent_tea"],
                [ "power_id"=>45,"name"=>"兼职老师薪资处理-教研",   "url"=>"/user_manage/complaint_department_deal_teacher_tea_jy"],
                ["power_id"=>50,"name"=>"教研首页","url"=>"/tongji2/home"],

                ["power_id"=>31, "name"=>"试听转化率模型",  "list"=>[
                    // ["power_id"=>25, "name"=>"兼职老师流失模型",   "url"=>"/tongji_ss/get_teacher_appoinment_lecture_info"],
                    ["power_id"=>3, "name"=>"试听转化率黑名单",   "url"=>"/tongji_ss/get_test_lesson_low_tra_teacher"],
                    // ["power_id"=>9,"name"=>"咨询师试听转化率", "url"=>"/tongji_ss/seller_test_lesson_info_tongji"],
                    ["power_id"=>11,"name"=>"限课黑名单试听转化率", "url"=>"/tongji_ss/get_seller_require_modify_info"],
                    ["power_id"=>19, "name"=>"试卷下载试听转化率",   "url"=>"/tongji_ss/get_stu_test_paper_download_info"],
                    ["power_id"=>20, "name"=>"讲义上传试听转化率",   "url"=>"/tongji_ss/get_homework_and_work_status_info"],

                ]],
                ["power_id"=>33, "name"=>"其他",  "list"=>[
                    // ["power_id"=>12, "name"=>"教研以及全职老师常规学生详情",   "url"=>"/human_resource/research_qz_teacher_stu_info"],
                    // ["power_id"=>22, "name"=>"助教换老师统计",   "url"=>"/tongji_ss/get_change_teacher_info"],
                    // ["power_id"=>23, "name"=>"兼职老师退费处理",   "url"=>"/tongji_ss/get_refund_teacher_and_ass_info"],
                    // ["power_id"=>6, "name"=>"新老师试听课统计",   "url"=>"/tongji_ss/new_teacher_test_lesson_info"],
                    // ["power_id"=>8,"name"=>"试听课转化详情-教研", "url"=>"/tongji_ss/research_teacher_lesson_detail_info"],
                    ["power_id"=>26, "name"=>"投诉老师列表",   "url"=>"/tea_manage_new/get_teacher_complaints_info"],
                ]],
                ["power_id"=>2, "name"=>"教研老师信息",   "url"=>"/human_resource/reaearch_teacher_lesson_list_research"],
                [ "power_id"=>73,"name"=>"质监月进度", "url"=>"/main_page/quality_control_jy"],//james

            ]],
            ["power_id"=>34, "name"=>"角色-教学", "list"=>[
                ["power_id"=>22, "name"=>"全职老师架构管理",  "url"=>"/user_manage_new/admin_group_manage_fulltime"],
                ["power_id"=>80, "name"=>"全职老师产能",   "url"=>"/fulltime_teacher/fulltime_teacher_count"],
                ["power_id"=>81, "name"=>"全职老师考勤信息",   "url"=>"/fulltime_teacher/fulltime_teacher_work_attendance_info"],
                ["power_id"=>82, "name"=>"全职老师考勤汇总",   "url"=>"/fulltime_teacher/fulltime_teacher_attendance_info_month"],
                ["power_id"=>2, "name"=>"全职老师KPI",   "url"=>"/tongji_ss/tongji_fulltime_teacher_test_lesson_info"],
                ["power_id"=>5, "name"=>"全职老师转化率总体",   "url"=>"/human_resource/teacher_test_lesson_info_total_fulltime"],
                ["power_id"=>20, "name"=>"全职老师转化折线",   "url"=>"/human_resource/get_broken_line_order_rate"],
                ["power_id"=>4, "name"=>"全职老师转化率明细",   "url"=>"/human_resource/teacher_test_lesson_info_fulltime"],

                ["power_id"=>10, "name"=>"全职老师面试",   "url"=>"/human_resource/teacher_lecture_appointment_info_full_time"],
                ["power_id"=>14, "name"=>"教研老师信息",   "url"=>"/human_resource/reaearch_teacher_lesson_list_fulltime"],
                ["power_id"=>15, "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson_fulltime"],
                ["power_id"=>16, "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_fulltime"],


                ["power_id"=>3, "name"=>"全职老师档案",   "url"=>"/human_resource/index_fulltime"],
                ["power_id"=>1, "name"=>"全职老师上班考勤",   "url"=>"/user_manage_new/get_fulltime_teacher_attendance_info_full"],
                ["power_id"=>12, "name"=>"全职老师上课考勤","url"=>"/tongji_ss/tongji_change_lesson_by_full_time_teacher_jy"],
                ["power_id"=>13, "name"=>"全职老师培训","url"=>"/tea_manage/train_lesson_list_fulltime"],
                ["power_id"=>17, "name"=>"培训课程视频","url"=>"/fulltime_teacher/get_fulltime_teacher_train_lesson_list"],


                ["power_id"=>6, "name"=>"全职老师课程管理",   "url"=>"/tea_manage/lesson_list_fulltime"],
                ["power_id"=>7, "name"=>"全职老师转正申请",   "url"=>"/fulltime_teacher/full_assessment_list"],
                ["power_id"=>8, "name"=>"全职老师转正申请审批-总监",   "url"=>"/fulltime_teacher/fulltime_teacher_assessment_positive_info"],
                ["power_id"=>9, "name"=>"全职老师转正申请审批-总经理",   "url"=>"/fulltime_teacher/fulltime_teacher_assessment_positive_info_master"],
                ["power_id"=>11, "name"=>"全职老师晋升申请","url"=>"/teacher_level/get_teacher_level_quarter_info_fulltime"],
                ["power_id"=>18, "name"=>"武汉全职老师面试数据","url"=>"/fulltime_teacher/fulltime_teacher_data"],
                ["power_id"=>31, "name"=>"全职老师学科转化率","url"=>"/tongji2/subject_transfer"],
                ["power_id"=>32, "name"=>"全职老师KPI统计数据","url"=>"/tongji2/fulltime_teacher_kpi_chart"],
            ]],

            ["power_id"=>39, "name"=>"角色-客服", "list"=>[
                ["power_id"=>1, "name"=>"意向用户信息录入",   "url"=>"/customer_service/intended_user_info"],
                ["power_id"=>2, "name"=>"用户投诉录入",   "url"=>"/customer_service/complaint_info"],
                ["power_id"=>3, "name"=>"用户建议录入",   "url"=>"/customer_service/proposal_info"],
            ]],


            ["power_id"=>42, "name"=>"角色-产品", "list"=>[

                ["power_id"=>2, "name"=>"理优升学帮", "list"=>[
                    ["power_id"=>1, "name"=>"查分数线",   "url"=>"/school_info/search_scores"],
                    ["power_id"=>2, "name"=>"找卷子",   "url"=>"/school_info/search_paper"],
                    ["power_id"=>3, "name"=>"查学校",   "url"=>"/school_info/search_school"],
                    ["power_id"=>4, "name"=>"政策百科",   "url"=>"/news_info/news_ency_info"],
                    ["power_id"=>5, "name"=>"升学头条",   "url"=>"/news_info/news_headlines_info"],
                ]],

                ["power_id"=>3, "name"=>"理优学生端",  "list"=>[
                    ["power_id"=>1, "name"=>"app首页设置",  "url"=>"/taobao_manage/taobao_type" ],
                    ["power_id"=>2, "name"=>"商品管理",  "url"=>"/taobao_manage/taobao_item" ]
                ]],
                ["power_id"=>38, "name"=>"软件使用反馈-产品",   "url"=>"/user_manage/complaint_department_deal_product"],
                ["power_id"=>40, "name"=>"产品-需求处理",   "url"=>"/requirement/requirement_info_product_new"],
                ["power_id"=>55, "name"=>"产品-需求-old",  "url"=>"/requirement/requirement_info_product"],
                // ["power_id"=>41, "name"=>"研发-需求处理",   "url"=>"/requirement/requirement_info_development"],
                //["power_id"=>42, "name"=>"测试-需求处理",   "url"=>"/requirement/requirement_info_test"],
            ]],
        ];
    }

}
