<?php
namespace App\Config;
class teaching_menu{
    static  public  function get_config()  {
        return [
            ["name"=> "教学管理事业部" , "list" => [
                [ "name"=>"核心数据",  "list"=>[
                    [ "name"=>"老师晋升审核-兼职","url"=>"/teacher_level/get_teacher_advance_info"],
                    [ "name"=>"老师晋升审核-教研","url"=>"/teacher_level/teacher_advance_info_list"],
                    [ "name"=>"老师晋升审核-全职","url"=>"/teacher_level/get_teacher_advance_info_fulltime"],
                    [ "name"=>"兼职老师流失模型",   "url"=>"/tongji_ss/get_teacher_appoinment_lecture_info"],
                    ["name"=>"咨询师试听转化率", "url"=>"/tongji_ss/seller_test_lesson_info_tongji"],
                    [ "name"=>"教研以及全职老师常规学生详情",   "url"=>"/human_resource/research_qz_teacher_stu_info"],
                    [ "name"=>"试听转化率统计","url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_for_jx"],
                    [ "name"=>"新老师试听课统计",   "url"=>"/tongji_ss/new_teacher_test_lesson_info"],
                    [ "name"=>"面试加班信息",   "url"=>"/tongji_ss/tongji_teacher_1v1_lesson_time"],
                    [ "name"=>"试听转化率统计-招师","url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_zs"],
                    [ "name"=>"老师身份统计",   "url"=>"/tongji2/tongji_lesson_teacher_identity"],
                    [ "name"=>"新老师第1次教学质量反馈",   "url"=>"/tongji_ss/teacher_first_test_lesson_week"],
                    [ "name"=>"兼职老师转化率总体",   "url"=>"/human_resource/teacher_test_lesson_info_total"],
                    [ "name"=>"试听转化率统计-质监", "url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_zj"],
                    [ "name"=>"各学科转化率总体",   "url"=>"/tongji_ss/test_lesson_order_per_subject"],
                    [ "name"=>"教材版本匹配度",   "url"=>"/tongji/match_lesson_textbook"],
                    [ "name"=>"1-3年级试听详情",  "url"=>"/tongji2/one_three_grade_student"],
                ]],

                [ "name"=>"师资管理部",  "list"=>[
                    [ "name"=>"核心数据", "url" => "/tongji2/get_teaching_core_data" ],
                    [ "name"=>"招师组",  "list"=>[
                        [ "name"=>"首页",   "url"=>"/main_page/zs_teacher_new" ],
                        [ "name"=>"面试转化信息",   "url"=>"/main_page/zs_teacher_old" ],
                        ["name"=>"教师试讲预约",   "url"=>"/human_resource/teacher_lecture_appointment_info"],
                        [ "name"=>"教师档案",   "url"=>"/human_resource/index_zs"],

                        [ "name"=>"教师试讲预约-招师",   "url"=>"/human_resource/teacher_lecture_appointment_info_zs"],
                        [ "name"=>"教研老师信息",   "url"=>"/human_resource/reaearch_teacher_lesson_list"],
                        [ "name"=>"招师统计",   "url"=>"/tongji_ss/tongji_zs_reference"],
                        [ "name"=>"面试试讲列表",   "url"=>"/train_teacher/train_lecture_lesson_list"],
                        [ "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson_zs"],
                        [ "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_zs"],
                        [ "name"=>"面试转化率模型",  "list"=>[
                            [ "name"=>"教研老师面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji_zs"],
                            [ "name"=>"面试转化数据-年级科目",   "url"=>"/tongji_ss/interview_subject_grade_tongji_zs"],
                            [ "name"=>"招师渠道面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji_by_reference_zs"],
                            ["name"=>"面试各项指标评分", "url"=>"/human_resource/get_teacher_lecture_fail_score_info_zs"],
                        ]],
                        [ "name"=>"渠道统计",   "url"=>"/human_resource/origin_list"],
                        [ "name"=>"渠道统计-new",   "url"=>"/human_resource/zs_origin_list"],
                        [ "name"=>"渠道管理",   "url"=>"/channel_manage/admin_channel_manage"],
                        [ "name"=>"渠道统计-new-list",   "url"=>"/channel_manage/zs_origin_list_new"],
                        [ "name"=>"模拟试听审核",   "url"=>"/tea_manage/trial_train_lesson_list_zs"],
                    ]],
                    ["name"=>"培训组",  "list"=>[
                        [ "name"=>"首页",   "url"=>"/main_page/zs_teacher" ],
                        [ "name"=>"老师培训管理",   "url"=>"/tea_manage/train_lesson_list_research"],
                        [ "name"=>"新师培训未通过名单",  "url"=>"/tea_manage/train_not_through_list_px"],
                        [ "name"=>"模拟试听未通过名单",   "url"=>"/tea_manage/trial_train_no_pass_list"],
                        ["name"=>"模拟试听未排名单",   "url"=>"/tongji_ss/get_no_time_train_lesson_teacher_list"],
                        ["name"=>"待培训名单",   "url"=>"/tea_manage/teacher_cc_count"],
                        ["name"=>"培训进度列表",   "url"=>"/tea_manage/teacher_train_list"],

                    ]],

                    [ "name"=>"运营组",  "list"=>[
                        [ "name"=>"兼职老师上课考勤",   "url"=>"/tongji_ss/tongji_change_lesson_by_teacher_jy"],
                        [ "name"=>"兼职老师投诉处理",   "url"=>"/tea_manage_new/get_seller_ass_record_info"],
                        [ "name"=>"兼职老师更换申请",   "url"=>"/user_manage_new/get_ass_change_teacher_info"],
                        [ "name"=>"兼职老师薪资处理",   "url"=>"/user_manage/complaint_department_deal_teacher_tea"],
                        [ "name"=>"兼职老师退费处理","url"=>"/tongji_ss/get_refund_teacher_and_ass_info"],
                        [ "name"=>"兼职老师晋升申请","url"=>"/teacher_level/get_teacher_level_quarter_info_show"],
                        [ "name"=>"助教课时折损统计",   "url"=>"/tongji/test_lesson_ass_jy"],
                        [ "name"=>"助教换老师统计",   "url"=>"/tongji_ss/get_change_teacher_info"],
                        ["name"=>"老师推荐申请",   "url"=>"/tea_manage_new/get_seller_require_commend_teacher_info_yy"],
                        [ "name"=>"投诉处理-QC",   "url"=>"/user_manage/qc_complaint_tea"],
                        [ "name"=>"微信推送",   "url"=>"/user_manage_new/wx_monitor_new_yy"],
                        [ "name"=>"老师稳定性参考数据",   "url"=>"/tea_manage_new/approved_data"],
                        [ "name"=>"教师预警","url"=>"/teacher_warn/tea_warn_list"],
                        [ "name"=>"老师稳定性参考数据-new",   "url"=>"/tea_manage_new/approved_data_new"],
                    ]],


                    ["power_id"=>14, "name"=>"教务组",  "list"=>[
                        [ "name"=>"教务组核心数据", "url"=>"/main_page/teacher_management_info"],
                        ["power_id"=>1, "name"=>"首页",   "url"=>"/main_page/jw_teacher" ],
                        ["power_id"=>12, "name"=>"KPI",   "url"=>"/tongji_ss/tongji_jw_teacher_kpi" ],
                        ["power_id"=>14, "name"=>"未排统计",   "url"=>"/tongji_ss/get_jw_no_plan_remind" ],
                        ["power_id"=>5, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list_jw"],
                        ["power_id"=>13, "name"=>"抢单库", "url"=>"/seller_student_new2/grab_test_lesson_list"],
                        ["power_id"=>6, "name"=>"排课明细",   "url"=>"/seller_student_new2/test_lesson_detail_list_jw"],
                        ["power_id"=>10, "name"=>"教务-排课明细",   "url"=>"/tongji_ss/test_lesson_plan_detail_list_jw"],
                        ["power_id"=>3, "name"=>"教师档案",   "url"=>"/human_resource/index_jw"],
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
                        ["power_id"=>29, "name"=>"CC转化率统计", "url"=>"/user_manage/tongji_cc"],
                        ["power_id"=>30, "name"=>"月份-科目统计", "url"=>"/user_manage/subject_by_month"],
                    ]],


                ]],






                [ "name"=>"教研部",  "list"=>[
                    ["name"=>"教研首页","url"=>"/tongji2/home"],
                    ["name"=>"教材版本管理",   "url"=>"/textbook_manage/get_subject_grade_textbook_info"],
                    [ "name"=>"教材版本地图",   "url"=>"/textbook_manage/show_textbook_map"],
                    [ "name"=>"老师晋升申请","url"=>"/teacher_level/get_teacher_level_quarter_info_new"],
                    [ "name"=>"教研老师信息",   "url"=>"/human_resource/reaearch_teacher_lesson_list_research"],

                    [ "name"=>"教研组",  "list"=>[
                        ["name"=>"教研排行榜", "url"=>"/tongji_ss/tongji_teaching_and_research_teacher_test_lesson_info"],
                        ["name"=>"KPI考核标准", "url"=>"/tongji_ss/research_teacher_kpi_info_new"],
                        [ "name"=>"质监月进度", "url"=>"/main_page/quality_control_jy"],
                        [ "name"=>"兼职老师转化率明细",   "url"=>"/human_resource/teacher_test_lesson_info"],
                        [ "name"=>"兼职老师档案",   "url"=>"/human_resource/index_tea_qua"],
                        [ "name"=>"教学质量反馈报告汇总（新）",   "url"=>"/human_resource/teacher_record_detail_list_new"],
                        [ "name"=>"教学质量反馈报告汇总（旧）",   "url"=>"/human_resource/teacher_record_detail_list"],
                        [ "name"=>"老师课程管理",   "url"=>"/tea_manage/lesson_list_research"],
                        [ "name"=>"老师-课程列表",   "url"=>"/tea_manage/tea_lesson_list"],
                        [ "name"=>"老师推荐申请",   "url"=>"/tea_manage_new/get_seller_require_commend_teacher_info"],
                        [ "name"=>"老师试听薪资排行",   "url"=>"/tongji_ss/teacher_trial_count"],
                        [ "name"=>"模拟试听审核",   "url"=>"/tea_manage/trial_train_lesson_list"],
                        [ "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_research"],
                        [ "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson"],
                        [ "name"=>"第一次试听课反馈",   "url"=>"/teacher_level/get_first_test_lesson_info_jy"],
                        [ "name"=>"第五次试听课反馈",   "url"=>"/teacher_level/get_fifth_test_lesson_info_jy"],
                        [ "name"=>"第一次常规课反馈",   "url"=>"/teacher_level/get_first_regular_lesson_info_jy"],
                        [ "name"=>"第五次常规课反馈",   "url"=>"/teacher_level/get_fifth_regular_lesson_info_jy"],
                        [ "name"=>"考勤信息",   "url"=>"/user_manage_new/get_fulltime_teacher_attendance_info"],
                        ["name"=>"试听课转化详情-教研", "url"=>"/tongji_ss/research_teacher_lesson_detail_info"],
                        [ "name"=>"投诉老师列表",   "url"=>"/tea_manage_new/get_teacher_complaints_info"],
                        [ "name"=>"投诉处理-家长",   "url"=>"/user_manage/complaint_department_deal_parent_tea"],
                        [ "name"=>"兼职老师薪资处理",   "url"=>"/user_manage/complaint_department_deal_teacher_tea_jy"],


                        [ "name"=>"试听转化率模型",  "list"=>[
                            [ "name"=>"试听转化率黑名单",   "url"=>"/tongji_ss/get_test_lesson_low_tra_teacher"],
                            ["name"=>"限课黑名单试听转化率", "url"=>"/tongji_ss/get_seller_require_modify_info"],
                            [ "name"=>"试卷下载试听转化率",   "url"=>"/tongji_ss/get_stu_test_paper_download_info"],
                            [ "name"=>"讲义上传试听转化率",   "url"=>"/tongji_ss/get_homework_and_work_status_info"],

                        ]],
                        [ "name"=>"教研备课上传", "list" => [
                            [ "name"=>"教研备课资料框架-新",   "url"=>"/resource/resource_frame_new"],
                            [ "name"=>"教研备课数据统计",   "url"=>"/resource/resource_count"],
                            [ "name"=>"更换讲义负责人",   "url"=>"/resource_new/admin_manage"],
                            [ "name"=>"教研备课后台",   "url"=>"/resource/get_all"],
                            ["name"=>"测评系统",   "url"=>"/test_paper/input_paper"],
                            [ "name"=>"教研回收站",   "url"=>"/resource/get_del"],
                        ]],

                        [ "name"=>"信息支持系统", "list" => [
                            [ "name"=>"信息支持主页",   "url"=>"/info_support/get_info"],
                            [ "name"=>"资源分类权限管理",   "url"=>"/info_resource_power/get_resource_power"],
                            [ "name"=>"教材版本管理",   "url"=>"/info_support/get_books"],
                        ]],

                    ]],

                    [ "name"=>"班课组",  "list"=>[
                        [ "name"=>"小班课", "list"=> [
                            [ "name"=>"小班管理",   "url"=>"/small_class/index"],
                            [ "name"=>"小班课次管理",   "url"=>"/small_class/lesson_list"],
                            [ "name"=>"小班课次管理-new",   "url"=>"/small_class/lesson_list_new"],
                            [ "name"=>"小班学生列表",   "url"=>"/small_class/student_list"],
                            [ "name"=>"小班学生列表-new",   "url"=>"/small_class/student_list_new"]
                        ]],
                    ]],


                    [ "name"=>"质监组",  "list"=>[
                        [ "name"=>"首页", "url"=>"/main_page/quality_control_kpi"],
                        //[ "name"=>"质监KPI", "url"=>"/main_page/quality_control_kpi"],
                        [ "name"=>"质监排行榜", "url"=>"/tongji_ss/tongji_teaching_and_research_teacher_test_lesson_info_zj"],
                        [ "name"=>"老师课程管理",   "url"=>"/tea_manage/lesson_list_zj"],
                        [ "name"=>"教学质量反馈报告汇总（新）",   "url"=>"/human_resource/teacher_record_detail_list_new_zj"],
                        [ "name"=>"教学质量反馈报告汇总（旧）",   "url"=>"/human_resource/teacher_record_detail_list_zj"],
                        [ "name"=>"模拟试听审核",   "url"=>"/tea_manage/trial_train_lesson_list_zj"],
                        [ "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_zj"],
                        [ "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson_zj"],
                        ["name"=>"未审核统计",        "url"=>"/user_manage/tongji_check"],
                        [ "name"=>"第一次试听课反馈",   "url"=>"/teacher_level/get_first_test_lesson_info"],
                        [ "name"=>"第五次试听课反馈",   "url"=>"/teacher_level/get_fifth_test_lesson_info"],
                        [ "name"=>"第一次常规课反馈",   "url"=>"/teacher_level/get_first_regular_lesson_info"],
                        [ "name"=>"第五次常规课反馈",   "url"=>"/teacher_level/get_fifth_regular_lesson_info"],
                        ["name"=>"精排1000教学反馈",   "url"=>"/teacher_level/get_seller_top_test_lesson_info"],
                        [ "name"=>"教研老师面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji"],
                        [ "name"=>"兼职老师转化率明细",   "url"=>"/human_resource/teacher_test_lesson_info_zj"],
                        [ "name"=>"兼职老师档案",   "url"=>"/human_resource/index_tea_qua_zj"],

                        [ "name"=>"面试转化率模型",  "list"=>[
                            [ "name"=>"面试转化数据-年级科目",   "url"=>"/tongji_ss/interview_subject_grade_tongji"],
                            [ "name"=>"招师渠道面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji_by_reference"],
                            ["name"=>"面试各项指标评分", "url"=>"/human_resource/get_teacher_lecture_fail_score_info"],
                        ]],
                        [ "name"=>"教学质量反馈模型",  "list"=>[
                            [ "name"=>"教学质量反馈标准（旧）",   "url"=>"/human_resource/teacher_record_detail_info"],
                            [ "name"=>"教学质量反馈标准（新)",   "url"=>"/human_resource/teacher_record_detail_info_new"],

                        ]],
                        [ "name"=>"质监数据总体", "url"=>"/main_page/quality_control"],
                        [ "name"=>"学生老师数量统计", "url"=>"/tongji_ss/get_lesson_tea_stu_info"],
                    ]],


                ]],


                [ "name"=>"教学部", "list"=>[
                    ["name"=>"全职老师架构管理",  "url"=>"/user_manage_new/admin_group_manage_fulltime"],
                    ["name"=>"全职老师考勤信息",   "url"=>"/fulltime_teacher/fulltime_teacher_work_attendance_info"],
                    ["name"=>"全职老师考勤汇总",   "url"=>"/fulltime_teacher/fulltime_teacher_attendance_info_month"],
                    [ "name"=>"全职老师产能",   "url"=>"/fulltime_teacher/fulltime_teacher_count"],
                    [ "name"=>"全职老师KPI",   "url"=>"/tongji_ss/tongji_fulltime_teacher_test_lesson_info"],
                    [ "name"=>"全职老师转化率总体",   "url"=>"/human_resource/teacher_test_lesson_info_total_fulltime"],
                    [ "name"=>"全职老师转化率明细",   "url"=>"/human_resource/teacher_test_lesson_info_fulltime"],

                    [ "name"=>"全职老师面试",   "url"=>"/human_resource/teacher_lecture_appointment_info_full_time"],

                    [ "name"=>"教研老师信息",   "url"=>"/human_resource/reaearch_teacher_lesson_list_fulltime"],
                    [ "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson_fulltime"],
                    [ "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_fulltime"],


                    [ "name"=>"全职老师档案",   "url"=>"/human_resource/index_fulltime"],
                    [ "name"=>"全职老师上班考勤",   "url"=>"/user_manage_new/get_fulltime_teacher_attendance_info_full"],
                    [ "name"=>"全职老师上课考勤","url"=>"/tongji_ss/tongji_change_lesson_by_full_time_teacher_jy"],
                    [ "name"=>"全职老师培训","url"=>"/tea_manage/train_lesson_list_fulltime"],
                    ["name"=>"培训课程视频","url"=>"/fulltime_teacher/get_fulltime_teacher_train_lesson_list"],


                    [ "name"=>"全职老师课程管理",   "url"=>"/tea_manage/lesson_list_fulltime"],
                    [ "name"=>"全职老师转正申请",   "url"=>"/fulltime_teacher/full_assessment_list"],
                    [ "name"=>"全职老师转正申请审批-初审",   "url"=>"/fulltime_teacher/fulltime_teacher_assessment_positive_info"],
                    [ "name"=>"全职老师转正申请审批-终审",   "url"=>"/fulltime_teacher/fulltime_teacher_assessment_positive_info_master"],
                    [ "name"=>"全职老师晋升申请","url"=>"/teacher_level/get_teacher_level_quarter_info_fulltime"],
                    [ "name"=>"武汉全职老师面试数据","url"=>"/fulltime_teacher/fulltime_teacher_data"],
                    [ "name"=>"全职老师学科转化率","url"=>"/tongji2/subject_transfer"],
                    [ "name"=>"全职老师KPI统计数据","url"=>"/tongji2/fulltime_teacher_kpi_chart"],

                ]],

                [ "name"=>"产品部",  "list"=>[

                    //["name"=>"开发需求提交",   "url"=>"/requirement/requirement_info_new"],
                    [ "name"=>"产品-需求处理",   "url"=>"/requirement/requirement_info_product_new"],
                    ["name" =>"产品-需求-old" ,  "url"=>"/requirement/requirement_info_product"],
                    //[ "name"=>"研发-需求处理",   "url"=>"/requirement/requirement_info_development"],
                    //[ "name"=>"测试-需求处理",   "url"=>"/requirement/requirement_info_test"],

                    [ "name"=>"软件使用反馈-产品",   "url"=>"/user_manage/complaint_department_deal_product"],

                    [ "name"=>"理优学生端",  "list"=>[
                        [ "name"=>"app首页设置",  "url"=>"/taobao_manage/taobao_type" ],
                        [ "name"=>"商品管理",  "url"=>"/taobao_manage/taobao_item" ]
                    ]],


                    [ "name"=>"理优升学帮", "list"=>[
                        [ "name"=>"查分数线",   "url"=>"/school_info/search_scores"],
                        [ "name"=>"找卷子",   "url"=>"/school_info/search_paper"],
                        [ "name"=>"查学校",   "url"=>"/school_info/search_school"],
                        [ "name"=>"政策百科",   "url"=>"/news_info/news_ency_info"],
                        [ "name"=>"升学头条",   "url"=>"/news_info/news_headlines_info"],
                    ]],



                    [ "name"=>"理优智能题库", "list"=>[
                        [ "name"=>"录入", "list"=>[
                            [ "name"=>"录入-编辑",   "url"=>"/question/question_list"],
                            [ "name"=>"录入-审核未通过-所有",   "url"=>"/question/question_list_nopass"],
                            [ "name"=>"录入-审核未通过-扣10%",   "url"=>"/question/question_list_nopass_10"],
                            [ "name"=>"录入-审核未通过-扣50%",   "url"=>"/question/question_list_nopass_50"],
                            [ "name"=>"录入-审核未通过-扣100%",   "url"=>"/question/question_list_nopass_100"],
                            [ "name"=>"录入-审核未通过-不入库",   "url"=>"/question/question_list_nopass_del"],
                            [ "name"=>"录入-审核通过",   "url"=>"/question/question_list_pass"]]],


                        [ "name"=>"一审", "list"=>[
                            [ "name"=>"一审-审核",  "icon"=>"fa-book", "url"=>"/question/question_list_check"],
                            [ "name"=>"一审-审核未通过-所有",   "url"=>"/question/question_list_check_nopass"],
                            [ "name"=>"一审-审核未通过-扣10%",   "url"=>"/question/question_list_check_nopass_10"],
                            [ "name"=>"一审-审核未通过-扣50%",   "url"=>"/question/question_list_check_nopass_50"],
                            [ "name"=>"一审-审核未通过-扣100%",   "url"=>"/question/question_list_check_nopass_100"],
                            [ "name"=>"一审-审核未通过-不入库",   "url"=>"/question/question_list_check_nopass_del"],
                            [ "name"=>"一审-审核通过",   "url"=>"/question/question_list_check_pass"],
                            [ "name"=>"二审-审核未通过-所有",   "url"=>"/question/question_list_check2_for1_nopass"],
                            [ "name"=>"二审-审核未通过-扣10%",   "url"=>"/question/question_list_check2_for1_nopass_10"],
                            [ "name"=>"二审-审核未通过-扣50%",   "url"=>"/question/question_list_check2_for1_nopass_50"],
                            [ "name"=>"二审-审核未通过-扣100%",   "url"=>"/question/question_list_check2_for1_nopass_100"],
                            [ "name"=>"二审-审核未通过-不入库",   "url"=>"/question/question_list_check2_for1_nopass_del"],
                            [ "name"=>"二审-审核通过",   "url"=>"/question/question_list_check2_for1_pass"]]],
                        [ "name"=>"二审", "list"=>[
                            [ "name"=>"审核",  "icon"=>"fa-book", "url"=>"/question/question_list_check2_for2"],
                            [ "name"=>"-审核未通过-所有",   "url"=>"/question/question_list_check2_nopass"],
                            [ "name"=>"审核未通过-扣10%",   "url"=>"/question/question_list_check2_nopass_10"],
                            [ "name"=>"审核未通过-扣50%",   "url"=>"/question/question_list_check2_nopass_50"],
                            [ "name"=>"审核未通过-扣100%",   "url"=>"/question/question_list_check2_nopass_100"],
                            [ "name"=>"审核未通过-不入库",   "url"=>"/question/question_list_check2_nopass_del"],
                            [ "name"=>"审核通过",   "url"=>"/question/question_list_check2_pass"]]],


                        [ "name"=>"知识点编辑",  "icon"=>"fa-book", "url"=>"/question/edit_lesson_note"],
                        [ "name"=>"所有题目",  "icon"=>"fa-book", "url"=>"/question/publish_list"],
                        [ "name"=>"录入统计",  "icon"=>"fa-book", "url"=>"/question/admin_info"],
                        [ "name"=>"审核统计",  "icon"=>"fa-book", "url"=>"/question/check_admin_info"],
                        [ "name"=>"题库题目统计",  "icon"=>"fa-book", "url"=>"/human_resource/get_question_tongji"]

                    ]
                    ],
                ]

                ]
            ],

            ]

        ];

    }

}
