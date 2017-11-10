<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017111001 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017111001;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-11-10"  , "2017-11-10"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_0 ];
        $this->lesson_times_range = [10 ,  10000];
        $this->user_join_time_range=["2015-01-10", "2017-10-31" ];
        $this->max_count=20;

        $not_period_perent=75;
        $period_perent= $not_period_perent+6;


        $this->lesson_times_off_perent_list =[
            E\Eperiod_flag::V_PERIOD => [
                10 =>  $period_perent ,
            ],
            E\Eperiod_flag::V_NOT_PERIOD => [
                10 =>  $not_period_perent ,
            ]
        ];
    }

}