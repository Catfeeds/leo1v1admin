<?php
namespace App\Config;
class seller_menu{
    static  public  function get_config()  {
        return [
            [ "name"=>"仪表盘", "icon"=>"fa-dashboard",  "url"=>"/main_page/seller" ],
            [ "name"=>"例子",  "list"=> [
                ["name"=>"分配例子",   "url"=>"/seller_student_new/assign_sub_adminid_list"],
                [ "name"=>"分配例子-主管",   "url"=>"/seller_student_new/assign_member_list"],
                [ "name"=>"新增例子",  "list"=> [
                ]],

                [ "name"=>"数据报表",  "list"=> [
                    ["name"=>"例子统计总表", "url"=>"/tongji_ss/user_count"],
                    ["name"=>"销售个人统计", "url"=>"/tongji_ss/seller_count"],
                    ["name"=>"tmk例子统计", "url"=>"/tongji_ss/origin_count_seller"],
                ]],

            ]],
            [ "name"=>"私海",  "list"=> [
                [ "name"=>"所有用户",   "url"=>"/seller_student_new/seller_student_list_all"],
                [ "name"=>"抢学生",  "list"=> [
                    ["name"=>"抢新学生",   "url"=>"/seller_student_new/deal_new_user"],
                    ["name"=>"公海-抢学生",   "url"=>"/seller_student_new/get_free_seller_list"],
                ]],

            ]],
        ];

    }

}