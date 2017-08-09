<?php
namespace App\Config;
class teaching_menu{
    static  public  function get_config()  {
        return [

            [ "power_id"=>50, "name"=>"教学管理-2招师",  "list"=>[
                [ "name"=>"首页",   "url"=>"/main_page/zs_teacher_new" ],
                [ "name"=>"教师档案",   "url"=>"/human_resource/index_zs"],
                [ "name"=>"教师试讲预约",   "url"=>"/human_resource/teacher_lecture_appointment_info_zs"],
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
            ]],

            // [ "power_id"=>51, "name"=>"教学管理-1面试转化率模型",  "list"=>[
            //     [ "name"=>"教研老师面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji_zs"],
            //     [ "name"=>"面试转化数据-年级科目",   "url"=>"/tongji_ss/interview_subject_grade_tongji_zs"],
            //     [ "name"=>"招师渠道面试转化率",   "url"=>"/tongji_ss/teacher_interview_info_tongji_by_reference_zs"],
            //     ["name"=>"面试各项指标评分", "url"=>"/human_resource/get_teacher_lecture_fail_score_info_zs"],
            // ]],


            [ "power_id"=>51, "name"=>"教学管理-3招师",  "list"=>[
                [ "name"=>"首页",   "url"=>"/main_page/zs_teacher_new" ],
                [ "name"=>"教师档案",   "url"=>"/human_resource/index_zs"],
                [ "name"=>"教师试讲预约",   "url"=>"/human_resource/teacher_lecture_appointment_info_zs"],
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
            ]],





        ];

    }

}