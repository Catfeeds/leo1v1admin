<?php
namespace App\Config\url_desc_power\test;
class  get_student_list{
    static function get_config(){
        return [
            ["opt_grade", "显示 编辑年级"  ],
            ["grade", "显示 年级列"  ],
            ["input_grade", "显示 年级输入框 "  ],
            ["input_subject", "显示 科目输入框 "  ],
        ];
    }
    static public function get_value_config() {
        return [
            "field_name"=> "grade",
            "value_type"=> "enum",
            "value_type_ex"=> "grade",
        ];
    }
};