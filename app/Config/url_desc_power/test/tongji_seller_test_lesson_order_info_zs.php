<?php
namespace App\Config\url_desc_power\test;
class  tongji_seller_test_lesson_order_info_zs{
    static function get_config(){
        return [
            ["opt_grade", "显示 编辑年级"  ],
            ["grade", "显示 年级列"  ],
            ["input_grade", "显示 年级输入框 "  ],
            ["input_subject", "显示 科目输入框 "  ],
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