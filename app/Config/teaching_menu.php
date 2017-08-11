<?php
namespace App\Config;
class teaching_menu{
    static  public  function get_config()  {
        return [

            ["power_id"=>43, "name"=>"核心数据",  "list"=>[
                ["power_id"=>1, "name"=>"老师晋升审核","url"=>"/teacher_level/get_teacher_advance_info"],
                ["power_id"=>25, "name"=>"兼职老师流失模型",   "url"=>"/tongji_ss/get_teacher_appoinment_lecture_info"],
                ["power_id"=>9,"name"=>"咨询师试听转化率", "url"=>"/tongji_ss/seller_test_lesson_info_tongji"],

            ]],


            ["power_id"=>40, "name"=>"产品部",  "list"=>[
            ]],



            ["power_id"=>25, "name"=>"招师部",  "list"=>[
                ["power_id"=>1, "name"=>"首页",   "url"=>"/main_page/zs_teacher_new" ],
                ["power_id"=>3, "name"=>"教师档案",   "url"=>"/human_resource/index_zs"],
                ["power_id"=>12, "name"=>"试听转化率统计","url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_zs"],

                ["power_id"=>4, "name"=>"教师试讲预约",   "url"=>"/human_resource/teacher_lecture_appointment_info_zs"],
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

            ]],


            ["power_id"=>37, "name"=>"质监部",  "list"=>[
                ["power_id"=>1, "name"=>"首页", "url"=>"/main_page/quality_control"],
                ["power_id"=>3, "name"=>"老师课程管理",   "url"=>"/tea_manage/lesson_list_zj"],
                ["power_id"=>8, "name"=>"试听转化率统计", "url"=>"/tongji_ss/tongji_seller_test_lesson_order_info_zj"],
                ["power_id"=>13, "name"=>"新老师第1次教学质量反馈",   "url"=>"/tongji_ss/teacher_first_test_lesson_week_zj"],
                ["power_id"=>28, "name"=>"教学质量反馈报告汇总（新）",   "url"=>"/human_resource/teacher_record_detail_list_new_zj"],
                ["power_id"=>16, "name"=>"教学质量反馈报告汇总（旧）",   "url"=>"/human_resource/teacher_record_detail_list_zj"],
                ["power_id"=>29, "name"=>"模拟试听审核",   "url"=>"/tea_manage/trial_train_lesson_list_zj"],
                ["power_id"=>9, "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_zj"],
                ["power_id"=>36, "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson_zj"],
                ["power_id"=>37, "name"=>"面试加班信息",   "url"=>"/tongji_ss/tongji_teacher_1v1_lesson_time"],
                ["power_id"=>38, "name"=>"第一次试听课反馈",   "url"=>"/teacher_level/get_first_test_lesson_info"],
                ["power_id"=>39, "name"=>"第五次试听课反馈",   "url"=>"/teacher_level/get_fifth_test_lesson_info"],
                ["power_id"=>40, "name"=>"第一次常规课反馈",   "url"=>"/teacher_level/get_first_regular_lesson_info"],
                ["power_id"=>41, "name"=>"第五次常规课反馈",   "url"=>"/teacher_level/get_fifth_regular_lesson_info"],
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


            ]],


            ["power_id"=>35, "name"=>"培训部",  "list"=>[
                ["power_id"=>1, "name"=>"首页",   "url"=>"/main_page/zs_teacher" ],
                ["power_id"=>4, "name"=>"老师培训管理",   "url"=>"/tea_manage/train_lesson_list_research"],
                ["power_id"=>5, "name"=>"培训未通过名单",  "url"=>"/tea_manage/train_not_through_list_px"],
            ]],

            ["power_id"=>36, "name"=>"运营部",  "list"=>[
                ["power_id"=>1, "name"=>"兼职老师晋升申请","url"=>"/teacher_level/get_teacher_level_quarter_info"],
                // ["power_id"=>2, "name"=>"兼职老师晋升-总监","url"=>"/teacher_level/get_teacher_advance_info"],
                ["power_id"=>3, "name"=>"课时统计",   "url"=>"/tongji/test_lesson_ass_jy"],
                ["power_id"=>22, "name"=>"助教换老师统计",   "url"=>"/tongji_ss/get_change_teacher_info"],
                ["power_id"=>23, "name"=>"老师/助教退费责任统计","url"=>"/tongji_ss/get_refund_teacher_and_ass_info"],
                ["power_id"=>4, "name"=>"兼职老师上课考勤",   "url"=>"/tongji_ss/tongji_change_lesson_by_teacher_jy"],

            ]],




            ["power_id"=>32, "name"=>"教研部",  "list"=>[
                ["power_id"=>5, "name"=>"考勤信息",   "url"=>"/user_manage_new/get_fulltime_teacher_attendance_info"],
                ["power_id"=>7,"name"=>"教研排行榜", "url"=>"/tongji_ss/tongji_teaching_and_research_teacher_test_lesson_info"],
                ["power_id"=>23,"name"=>"KPI考核标准", "url"=>"/tongji_ss/research_teacher_kpi_info_new"],
                ["power_id"=>2, "name"=>"兼职老师转化率总体",   "url"=>"/human_resource/teacher_test_lesson_info_total"],
                ["power_id"=>1, "name"=>"兼职老师转化率明细",   "url"=>"/human_resource/teacher_test_lesson_info"],
                ["power_id"=>21, "name"=>"各学科转化率总体",   "url"=>"/tongji_ss/test_lesson_order_per_subject"],
                ["power_id"=>14, "name"=>"兼职老师档案",   "url"=>"/human_resource/index_tea_qua"],
                ["power_id"=>13, "name"=>"新老师第1次教学质量反馈",   "url"=>"/tongji_ss/teacher_first_test_lesson_week"],
                ["power_id"=>28, "name"=>"教学质量反馈报告汇总（新）",   "url"=>"/human_resource/teacher_record_detail_list_new"],
                ["power_id"=>16, "name"=>"教学质量反馈报告汇总（旧）",   "url"=>"/human_resource/teacher_record_detail_list"],
                ["power_id"=>3, "name"=>"老师课程管理",   "url"=>"/tea_manage/lesson_list_research"],
                ["power_id"=>17, "name"=>"老师更换申请",   "url"=>"/user_manage_new/get_ass_change_teacher_info"],
                ["power_id"=>18, "name"=>"老师教学投诉",   "url"=>"/tea_manage_new/get_seller_ass_record_info"],
                ["power_id"=>27, "name"=>"老师推荐申请",   "url"=>"/tea_manage_new/get_seller_require_commend_teacher_info"],
                ["power_id"=>24, "name"=>"老师试听薪资排行",   "url"=>"/tongji_ss/teacher_trial_count"],
                ["power_id"=>29, "name"=>"模拟试听审核",   "url"=>"/tea_manage/trial_train_lesson_list"],
                ["power_id"=>9, "name"=>"试讲审核(录制)","url"=>"/human_resource/teacher_lecture_list_research"],
                ["power_id"=>36, "name"=>"试讲审核(面试)",   "url"=>"/tea_manage/train_lecture_lesson"],
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
                    ["power_id"=>6, "name"=>"新老师试听课统计",   "url"=>"/tongji_ss/new_teacher_test_lesson_info"],
                    ["power_id"=>8,"name"=>"试听课转化详情-教研", "url"=>"/tongji_ss/research_teacher_lesson_detail_info"],
                    ["power_id"=>26, "name"=>"投诉老师列表",   "url"=>"/tea_manage_new/get_teacher_complaints_info"],
                ]],
            ]],


            ["power_id"=>34, "name"=>"教学部", "list"=>[
                ["power_id"=>80, "name"=>"全职老师产能",   "url"=>"/fulltime_teacher/fulltime_teacher_count"],
                ["power_id"=>2, "name"=>"全职老师KPI",   "url"=>"/tongji_ss/tongji_fulltime_teacher_test_lesson_info"],
                ["power_id"=>5, "name"=>"全职老师转化率总体",   "url"=>"/human_resource/teacher_test_lesson_info_total_fulltime"],
                ["power_id"=>4, "name"=>"全职老师转化率明细",   "url"=>"/human_resource/teacher_test_lesson_info_fulltime"],

                ["power_id"=>10, "name"=>"全职老师面试",   "url"=>"/human_resource/teacher_lecture_appointment_info_full_time"],

                ["power_id"=>3, "name"=>"全职老师档案",   "url"=>"/human_resource/index_fulltime"],
                ["power_id"=>1, "name"=>"全职老师上班考勤",   "url"=>"/user_manage_new/get_fulltime_teacher_attendance_info_full"],
                ["power_id"=>12, "name"=>"全职老师上课考勤","url"=>"/tongji_ss/tongji_change_lesson_by_full_time_teacher_jy"],


                ["power_id"=>6, "name"=>"全职老师课程管理",   "url"=>"/tea_manage/lesson_list_fulltime"],
                ["power_id"=>7, "name"=>"全职老师转正申请",   "url"=>"/fulltime_teacher/full_assessment_list"],
                ["power_id"=>8, "name"=>"全职老师转正申请审批-总监",   "url"=>"/fulltime_teacher/fulltime_teacher_assessment_positive_info"],
                ["power_id"=>9, "name"=>"全职老师转正申请审批-总经理",   "url"=>"/fulltime_teacher/fulltime_teacher_assessment_positive_info_master"],
                ["power_id"=>11, "name"=>"全职老师晋升申请","url"=>"/teacher_level/get_teacher_level_quarter_info_fulltime"],
            ]],












        ];

    }

}