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
            ],
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