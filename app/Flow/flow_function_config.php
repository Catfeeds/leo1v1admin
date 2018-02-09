<?php
namespace App\Flow;
use \App\Enums as E;
class flow_function_config{
    static  $config=[
        E\Eflow_function::S_CHECK_QINGJIA_DAY => [
            "1" => "小于3天",
            "2" => "大于等于3天",
        ],

        E\Eflow_function::S_CHECK_ADMIN_ROLE   => [
            "1" => "助教",
            "2" => "销售",
        ],
    ];

        //1, 2
    //@desn:获取不同分支的配置
    //@desn:$flow_type 审批类型
    static function get_branch_type_config($flow_type){
        $flow_type_arr = self::$config[$flow_type];
        return $flow_type_arr;
    }

}
