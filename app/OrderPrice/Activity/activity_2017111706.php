<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017111706 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017111706;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-11-18"  , "2017-11-20"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_3 ];
        $this->lesson_times_range = [10 ,  10000];
        $this->max_change_value=388;
        $this->lesson_times_present_lesson_count = [
            45  => 2,
            60  => 3,
            90  => 6,
            120 => 8,
            160 => 10,
            240 => 12,
            360 => 15,
            480 => 20,
        ];

    }

}
