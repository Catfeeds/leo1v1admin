<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017112501 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017112501;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-11-25"  , "2017-11-30"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_0 ];
        $this->lesson_times_range = [10 ,  10000];
        $this->max_count=88;

        $this-> price_off_money_list=[
            15000 => 500,
            30000 => 1000,
            45000 => 1500,
            60000 => 2000,
            75000 => 2500,
            90000 => 3000,
            105000 => 3500,
            120000 => 4000,
            135000 => 4500,
            150000 => 5000,
        ];

    }

}
