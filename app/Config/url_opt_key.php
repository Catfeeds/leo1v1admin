<?php
namespace App\Config;
class url_opt_key {
    static public function get_config() {
        return [
            "/test/get_user_list" => [
                ["opt_grade", "显示 编辑年级"  ],
                ["grade", "显示 年级列"  ],
                ["input_grade", "显示 年级输入框 "  ],
                ["input_subject", "显示 科目输入框 "  ],
            ]
        ];

    }

}
