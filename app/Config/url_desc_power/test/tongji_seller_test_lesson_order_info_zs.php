<?php
namespace App\Config\url_desc_power\test;
class  tongji_seller_test_lesson_order_info_zs{
    static function get_config(){
        return [
            [
                "field_name" => "opt_admin",
                "desc" => "编辑年级",
                "default_value" =>  false,
            ], [
                "field_name" => "gen_account",
                "desc" => "科目输入框",
                "default_value" =>  true,

            ], [
                "field_name" => "login_other",
                "desc" => "年级输入框",
                "default_value" =>  true,
            ]

        ];
    }
    static public function get_input_value_config() {
        return [
            [
                "field_name"=> "grade",
                "value_type"=> "enum",//int, function
                "enum_class"=> \App\Enums\Egrade::class,
            ],
            [
                "field_name"=> "sys_operator_uid", //
                "value_type"=> "function",//int,
            ],
        ];
        //$class_name="" \App\Enums\Egrade::class,
    }
};