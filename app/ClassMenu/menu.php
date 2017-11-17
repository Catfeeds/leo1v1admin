<?php
namespace App\ClassMenu;
class menu{
   static  public  function get_config()  {
       return [];
       /*
        return [
            ["power_id"=>80, "name"=>"小班课",  "list"=> [
                ["power_id"=>1, "name"=>"学生管理",   "list"=>[
                    ["power_id"=>1, "name"=>"全部用户",   "url"=>"/seller_student_new2/test_lesson_plan_list"],
                    ["power_id"=>2, "name"=>"订单",   "url"=>"/seller_student_new2/test_lesson_plan_list_jx"],
                    ["power_id"=>3, "name"=>"测试",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>4, "name"=>"教务排课明细", "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>5, "name"=>"试听销售签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list"],
                    ["power_id"=>6, "name"=>"预分配列表", "url"=>"/seller_student_new2/get_test_lesson_require_teacher_info"],
                ]],

                ["power_id"=>2, "name"=>"老师管理",   "list"=>[
                    ["power_id"=>1, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list"],
                    ["power_id"=>2, "name"=>"试听排课-教务",   "url"=>"/seller_student_new2/test_lesson_plan_list_jx"],
                    ["power_id"=>3, "name"=>"未绑定的试听课",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>4, "name"=>"教务排课明细", "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>5, "name"=>"试听销售签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list"],
                    ["power_id"=>6, "name"=>"预分配列表", "url"=>"/seller_student_new2/get_test_lesson_require_teacher_info"],
                ]],

                ["power_id"=>3, "name"=>"订单管理",   "list"=>[
                    ["power_id"=>1, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list"],
                    ["power_id"=>2, "name"=>"试听排课-教务",   "url"=>"/seller_student_new2/test_lesson_plan_list_jx"],
                    ["power_id"=>3, "name"=>"未绑定的试听课",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>4, "name"=>"教务排课明细", "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>5, "name"=>"试听销售签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list"],
                    ["power_id"=>6, "name"=>"预分配列表", "url"=>"/seller_student_new2/get_test_lesson_require_teacher_info"],
                ]],
                ["power_id"=>4, "name"=>"课程管理",   "list"=>[
                    ["power_id"=>1, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list"],
                    ["power_id"=>2, "name"=>"试听排课-教务",   "url"=>"/seller_student_new2/test_lesson_plan_list_jx"],
                    ["power_id"=>3, "name"=>"未绑定的试听课",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>4, "name"=>"教务排课明细", "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>5, "name"=>"试听销售签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list"],
                    ["power_id"=>6, "name"=>"预分配列表", "url"=>"/seller_student_new2/get_test_lesson_require_teacher_info"],
                ]],
                ["power_id"=>5, "name"=>"财务管理",   "list"=>[
                    ["power_id"=>1, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list"],
                    ["power_id"=>2, "name"=>"试听排课-教务",   "url"=>"/seller_student_new2/test_lesson_plan_list_jx"],
                    ["power_id"=>3, "name"=>"未绑定的试听课",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>4, "name"=>"教务排课明细", "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>5, "name"=>"试听销售签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list"],
                    ["power_id"=>6, "name"=>"预分配列表", "url"=>"/seller_student_new2/get_test_lesson_require_teacher_info"],
                ]],
                ["power_id"=>6, "name"=>"用户管理",   "list"=>[
                    ["power_id"=>1, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list"],
                    ["power_id"=>2, "name"=>"试听排课-教务",   "url"=>"/seller_student_new2/test_lesson_plan_list_jx"],
                    ["power_id"=>3, "name"=>"未绑定的试听课",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>4, "name"=>"教务排课明细", "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>5, "name"=>"试听销售签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list"],
                    ["power_id"=>6, "name"=>"预分配列表", "url"=>"/seller_student_new2/get_test_lesson_require_teacher_info"],
                ]],
                ["power_id"=>7, "name"=>"业绩",   "list"=>[
                    ["power_id"=>1, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list"],
                    ["power_id"=>2, "name"=>"试听排课-教务",   "url"=>"/seller_student_new2/test_lesson_plan_list_jx"],
                    ["power_id"=>3, "name"=>"未绑定的试听课",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>4, "name"=>"教务排课明细", "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>5, "name"=>"试听销售签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list"],
                    ["power_id"=>6, "name"=>"预分配列表", "url"=>"/seller_student_new2/get_test_lesson_require_teacher_info"],
                ]],

            ]],
            ["power_id"=>81, "name"=>"小班课数据统计",  "list"=> [
                ["power_id"=>1, "name"=>"销售",   "list"=>[
                    ["power_id"=>1, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list"],
                    ["power_id"=>11, "name"=>"试听排课-教务",   "url"=>"/seller_student_new2/test_lesson_plan_list_jx"],
                    ["power_id"=>2, "name"=>"未绑定的试听课",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>5, "name"=>"教务排课明细", "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>6, "name"=>"试听销售签单与否反馈", "url"=>"/seller_student_new/test_lesson_order_fail_list"],
                    ["power_id"=>7, "name"=>"预分配列表", "url"=>"/seller_student_new2/get_test_lesson_require_teacher_info"],
                ]],
                ["power_id"=>2, "name"=>"助教", "url"=>"/tongji/online_def_user_count_list"],
                ["power_id"=>3, "name"=>"教学",   "url"=>"/user_manage_new/record_audio_server_list"],
            ]],
        ];
       */ 

    }

}
