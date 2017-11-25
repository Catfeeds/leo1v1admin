<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017112503 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017112502;
    public static $need_spec_require_flag = 1;
    static public  $max_count_activity_type_list=[]; // 总配额 组合

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->open_flag=false;

        $this->date_range=[ "2017-11-24"  , "2017-11-30"];
        $this->user_join_time_range=["2017-11-11", "2017-11-30"];
        $this->period_flag_list= [ E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_0 ];
        $this->lesson_times_range = [10 ,  10000];
        $this->max_count=20;
        static::$max_count_activity_type_list=[
            E\Eorder_activity_type::V_2017112503,
            E\Eorder_activity_type::V_2017112504,
        ];


        $this-> price_off_money_list=[
            10000  => 500,
            20000  => 1000,
            30000  => 1500,
            40000  => 2000,
            50000  => 2500,
            60000  => 3000,
            70000  => 3500,
            80000  => 4000,
            90000  => 4500,
            100000 => 5000,
            110000 => 5500,
            120000 => 6000,
            130000 => 6500,
            140000 => 7000,
            150000 => 7500,
        ];

    }

}
