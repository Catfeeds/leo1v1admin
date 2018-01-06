<?php
namespace App\Config\url_desc_power\authority;
class  manager_list{
    static function get_config(){
        return [
            [
                "field_name" => "opt_admin",
                "desc" => "超级管理权限",
                "default_value" =>  false,
            ], [
                "field_name" => "input_account_role",
                "desc" => "角色输入框",
                "default_value" =>  true,
            ], [
                "field_name" => "account_role",
                "desc" => "表 角色字段",
                "default_value" =>  true,
            ],[
                "field_name" => "set_passwd",
                "desc" => "修改密码权限",
                "default_value" =>  true,
            ],[
                "field_name" => "edit_manage",
                "desc" => "编辑权限",
                "default_value" =>  true,
            ],[
                "field_name" => "set_account_role",
                "desc" => "设置角色权限",
                "default_value" =>  true,
            ],[
                "field_name" => "set_wechat",
                "desc" => "绑定微信账号权限",
                "default_value" =>  true,
            ],[
                "field_name" => "set_worker_status",
                "desc" => "更改员工状态权限",
                "default_value" =>  true,
            ],[
                "field_name" => "set_power_group",
                "desc" => "更改权限组",
                "default_value" =>  false,
            ],[
                "field_name" => "set_account_login",
                "desc" => "此账号登录",
                "default_value" =>  false,
            ],[
                "field_name" => "change_account",
                "desc" => "修改账号",
                "default_value" =>  true,
            ],[
                "field_name" => "sync_kaoqin",
                "desc" => "同步考勤",
                "default_value" =>  true,
            ],[
                "field_name" => "set_email",
                "desc" => "邮箱配置",
                "default_value" =>  true, 
            ],[
                "field_name" => "set_fulltime_teacher_type",
                "desc" => "设置全职老师类型",
                "default_value" =>  false, 
            ],[
                "field_name" => "set_user_phone",
                "desc" => "生成学生和家长账号",
                "default_value" =>  false, 
            ],[
                "field_name" => "set_gen_ass",
                "desc" => "生成助教账号",
                "default_value" =>  false, 
            ],[
                "field_name" => "set_log",
                "desc" => "用户操作日志",
                "default_value" =>  false, 
            ],[
                "field_name" => "refresh_call_end",
                "desc" => "刷新回访",
                "default_value" =>  false, 

            ],[
                "field_name" => "set_train_through_time",
                "desc" => "同步老师入职时间",
                "default_value" =>  false, 

            ],[
                "field_name" => "set_teacher_level",
                "desc" => "修改老师等级",
                "default_value" =>  false, 

            ],[
                "field_name" => "delete_permission_test",
                "desc" => "权限删除测试",
                "default_value" =>  false, 
            ],[
                "field_name" => "change_permission_new",
                "desc" => "权限备份互换",
                "default_value" =>  false, 
            ],[
                "field_name" => "ower_permission",
                "desc" => "个人拥有权限",
                "default_value" =>  false, 

            ]


        ];
    }
    static public function get_input_value_config() {
        return [
            [
                "field_name"=> "account_role",
                "desc" => "角色",
                "value_type"=> "enum",//int, function
                "enum_class"=> "account_role",
            ], [
                "field_name"=> "assign_account_role",
                "desc" => "可分配的角色",
                "value_type"=> "enum",//int, function
                "enum_class"=> "account_role",
            ],
        ];

        //$class_name="" \App\Enums\Egrade::class,
    }
};