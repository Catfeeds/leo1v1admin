<?php
namespace App\Config;
class stu_menu{
    static  public  function get_config()  {
        return [
            [ "power_id"=>1, "name"=>"学员信息", "icon" => "fa-dashboard", "url"=> "/stu_manage/index?sid={sid}"],
            // [ "power_id"=>20, "name"=>"家长信息", "icon" => "fa-dashboard", "url"=> "/stu_manage/parent_list?sid={sid}"],
            [ "power_id"=>3, "name"=>"个人课表-查看", "icon" => "fa-dashboard", "url"=> "/stu_manage/lesson_plan?sid={sid}"],
            [ "power_id"=>4, "name"=>"个人课表-排课", "icon" => "fa-dashboard", "url"=> "/stu_manage/lesson_plan_edit?sid={sid}"],
            [ "power_id"=>62, "name"=>"个人常规课表", "icon" => "fa-dashboard", "url"=> "/stu_manage/regular_course_stu?sid={sid}"],
            [ "power_id"=>42, "name"=>"试听课信息", "icon" => "fa-dashboard", "url"=> "/stu_manage/test_lesson_list?sid={sid}"],
            [ "power_id"=>43, "name"=>"电话记录", "icon" => "fa-dashboard", "url"=> "/stu_manage/call_list?sid={sid}"],
            [ "power_id"=>40, "name"=>"课程包列表", "icon" => "fa-dashboard", "url"=> "/stu_manage/course_list?sid={sid}"],
            [ "power_id"=>41, "name"=>"课程列表-排课", "icon" => "fa-dashboard", "url"=> "/stu_manage/course_lesson_list?sid={sid}"],
            [ "power_id"=>5, "name"=>"学习情况", "icon" => "fa-dashboard", "url"=> "/stu_manage/learn_state?sid={sid}"],
            [ "power_id"=>6, "name"=>"回访记录-助教", "icon" => "fa-dashboard", "url"=> "/stu_manage/return_record?sid={sid}" ],
            [ "power_id"=>61, "name"=>"回访记录-销售", "icon" => "fa-dashboard", "url"=> "/stu_manage/return_book_record?sid={sid}" ],
            [ "power_id"=>7, "name"=>"礼品中心", "icon" => "fa-dashboard", "url"=> "/stu_manage/present?sid={sid}"],
            [ "power_id"=>8, "name"=>"学生赞记录", "icon" => "fa-dashboard", "url"=> "/stu_manage/get_stu_praise?sid={sid}"],
            //[ "power_id"=>9, "name"=>"取消记录", "icon" => "fa-dashboard", "url"=> "/stu_manage/lesson_cancel?sid={sid}"],
            [ "power_id"=>10, "name"=>"课堂评价", "icon" => "fa-dashboard", "url"=> "/stu_manage/lesson_evaluation?sid={sid}"],
            [ "power_id"=>11, "name"=>"自定义排课", "icon" => "fa-dashboard", "url"=> "/stu_manage/lesson_custom?sid={sid}"],
            [ "power_id"=>12, "name"=>"课程消耗信息", "icon" => "fa-dashboard", "url"=> "/stu_manage/order_lesson_list?sid={sid}"],
            [ "power_id"=>14, "name"=>"合同消耗信息", "icon" => "fa-dashboard", "url"=> "/stu_manage/order_info_list?sid={sid}"],
            [ "power_id"=>13, "name"=>"交接单", "icon" => "fa-dashboard", "url"=> "/stu_manage/init_info?sid={sid}"],
            [ "power_id"=>15,"name"=>"常规交接单-CC","icon"=>"fa-dashboard", "url"=> "/stu_manage/init_info_by_contract_cc?sid={sid}"],
            [ "power_id"=>16,"name"=>"常规交接单-CR","icon"=>"fa-dashboard", "url"=> "/stu_manage/init_info_by_contract_cr?sid={sid}"],
            [ "power_id"=>17, "name"=>"交接单-临时", "icon" => "fa-dashboard", "url"=> "/stu_manage/init_info_tmp?sid={sid}"],
            [ "power_id"=>18, "name"=>"成绩记录","icon" => "fa-dashboard","url"=>"/stu_manage/score_list?sid={sid}"],
            [ "power_id"=>19, "name"=>"个人登录记录","icon" => "fa-dashboard","url"=>"/stu_manage/user_login_list?sid={sid}"],
            [ "power_id"=>20, "name"=>"学情记录","icon" => "fa-dashboard","url"=>"/stu_manage/student_lesson_learning_record?sid={sid}"],
            [ "power_id"=>21, "name"=>"学生课表","icon" => "fa-dashboard","url"=>"/stu_manage/stu_schedule?sid={sid}"],

            [ "power_id"=>50,  "admin_domain_type"=> 1 ,  "name"=>"基本信息",  "url"=> "/class_stu_manage/index?sid={sid}"],
            [ "power_id"=>51,  "admin_domain_type"=> 1 ,  "name"=>"订单信息",  "url"=> "/class_stu_manage/index?sid={sid}"],
            [ "power_id"=>52,  "admin_domain_type"=> 1 ,  "name"=>"回访信息",  "url"=> "/class_stu_manage/index?sid={sid}"],
            [ "power_id"=>53,  "admin_domain_type"=> 1 ,  "name"=>"课程信息",  "url"=> "/class_stu_manage/index?sid={sid}"],
            [ "power_id"=>54,  "admin_domain_type"=> 1 ,  "name"=>"操作记录",  "url"=> "/class_stu_manage/index?sid={sid}"],
            [ "power_id"=>55,  "admin_domain_type"=> 1 ,  "name"=>"成绩信息",  "url"=> "/class_stu_manage/index?sid={sid}"],
        ];
    }
}