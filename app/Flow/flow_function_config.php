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
            "1" => "销售",
            "2" => "助教",
        ],
    ];
    static function check_qingjia_day (  $flowid, $adminid ) {
        //list($flow_info,$self_info)=static::get_info($flowid);

        //1, 2
    }

}