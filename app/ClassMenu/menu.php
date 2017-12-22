<?php
namespace App\ClassMenu;
class menu{
   static  public  function get_config()  {
       //return [];
       return [
            ["power_id"=>80, "name"=>"小班课",  "list"=> [
                ["power_id"=>1, "name"=>"学生数据管理",   "list"=>[
                    ["power_id"=>1, "name"=>"例子数据管理",   "url"=>"/cc_manage/index"],
                    ["power_id"=>2, "name"=>"咨询",   "url"=>"/class_stu_manage/b"],
                    ["power_id"=>3, "name"=>"助教",   "url"=>"/class_stu_manage/c"],
                    ["power_id"=>4, "name"=>"薪资反馈处理",   "url"=>"/class_stu_manage/d"],
                ]],
                ["power_id"=>2, "name"=>"老师管理",   "list"=>[
                    ["power_id"=>1, "name"=>"全部老师",   "url"=>"/class_stu_manage/aa"],
                    ["power_id"=>2, "name"=>"基本信息",   "url"=>"/class_stu_manage/ab"],
                    ["power_id"=>3, "name"=>"回访信息",   "url"=>"/class_stu_manage/ac"],
                    ["power_id"=>4, "name"=>"面试信息",   "url"=>"/class_stu_manage/ad"],
                    ["power_id"=>5, "name"=>"课程信息",   "url"=>"/class_stu_manage/ae"],
                    ["power_id"=>6, "name"=>"时间管理",   "url"=>"/class_stu_manage/af"],
                    ["power_id"=>7, "name"=>"操作记录",   "url"=>"/class_stu_manage/ag"],
                    ["power_id"=>8, "name"=>"面试课程",   "url"=>"/class_stu_manage/ah"],
                ]],
                ["power_id"=>3, "name"=>"学生管理",   "list"=>[
                    ["power_id"=>1, "name"=>"全部用户",   "url"=>"/class_stu_manage/list"],
                    ["power_id"=>2, "name"=>"订单信息",   "url"=>"/class_stu_manage/order_info"],
                    ["power_id"=>3, "name"=>"回访信息",   "url"=>"/stu_revisit/list"]
                ]],
                ["power_id"=>4, "name"=>"订单管理",   "list"=>[
                    ["power_id"=>1, "name"=>"全部订单",   "url"=>"/class_stu_manage/ba"],
                    ["power_id"=>2, "name"=>"订单审核",   "url"=>"/class_stu_manage/bb"],
                ]],
                ["power_id"=>5, "name"=>"课程管理",   "list"=>[
                    ["power_id"=>1, "name"=>"全部课程",   "url"=>"/class_stu_manage/ca"],
                    ["power_id"=>2, "name"=>"老师时间",   "url"=>"/class_stu_manage/cb"],
                ]],
                ["power_id"=>6, "name"=>"用户管理",   "list"=>[
                    ["power_id"=>1, "name"=>"权限管理",   "url"=>"/class_stu_manage/da"],
                    ["power_id"=>2, "name"=>"登录管理",   "url"=>"/class_stu_manage/db"],
                    ["power_id"=>3, "name"=>"消息管理",   "url"=>"/class_stu_manage/dc"],
                    ["power_id"=>4, "name"=>"获赞管理",   "url"=>"/class_stu_manage/dd"],
                ]],
                ["power_id"=>7, "name"=>"业绩",   "list"=>[
                    ["power_id"=>1, "name"=>"业绩",   "url"=>"/class_stu_manage/ea"],
                    ["power_id"=>2, "name"=>"报表",   "url"=>"/class_stu_manage/eb"],
                ]],

            ]],
            ["power_id"=>81, "name"=>"小班课数据统计",  "list"=> [
                ["power_id"=>1, "name"=>"市场渠道统计",   "list"=>[
                ]],
                ["power_id"=>2, "name"=>"招师渠道统计", "url"=>""],
            ]],
        ];

    }

}
