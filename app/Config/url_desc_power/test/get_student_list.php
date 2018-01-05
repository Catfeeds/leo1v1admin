<?php
namespace App\Config\url_desc_power\test;
class  get_student_list{
    static function get_config(){
        return [
            //标示符   说明   ,  默认是否有权限
            ["opt_grade", "显示 编辑年级" ],
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