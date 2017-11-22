<?php
namespace App\ClassMenu;
class menu{
   static  public  function get_config()  {
       //return [];
       return [
            ["power_id"=>80, "name"=>"小班课",  "list"=> [
                ["power_id"=>1, "name"=>"学生管理",   "list"=>[
                    ["power_id"=>1, "name"=>"全部用户",   "url"=>"/class_stu_manage/list"],
                    ["power_id"=>2, "name"=>"详细页面",   "url"=>"/class_stu_manage/detail"],
                ]],

                ["power_id"=>2, "name"=>"老师管理",   "list"=>[
                ]],

                ["power_id"=>3, "name"=>"订单管理",   "list"=>[
                ]],
                ["power_id"=>4, "name"=>"课程管理",   "list"=>[
                ]],
                ["power_id"=>5, "name"=>"财务管理",   "list"=>[
                ]],
                ["power_id"=>6, "name"=>"用户管理",   "list"=>[
                ]],
                ["power_id"=>7, "name"=>"业绩",   "list"=>[
                ]],

            ]],
            ["power_id"=>81, "name"=>"小班课数据统计",  "list"=> [
                ["power_id"=>1, "name"=>"销售",   "list"=>[
                ]],
                ["power_id"=>2, "name"=>"助教", "url"=>""],
                ["power_id"=>3, "name"=>"教学",   "url"=>""],
            ]],
        ];

    }

}
