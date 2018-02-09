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

    static  $func_config=[
        E\Eflow_function::S_CHECK_QINGJIA_DAY =>""   ,
    ];

}