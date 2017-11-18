<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017111703 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017111703;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-11-18"  , "2017-11-20"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_3 ];
        $this->lesson_times_range = [30 ,  10000];
        $this->max_count=18;
        self::$max_count_activity_type_list=[
            E\Eorder_activity_type::V_2017111702,
            E\Eorder_activity_type::V_2017111703,
            E\Eorder_activity_type::V_2017111704,
            E\Eorder_activity_type::V_2017111705,
        ];

        $this->check_grade_list=[
            E\Egrade::V_203,
        ];

        $this->lesson_times_off_perent_list=[
            E\Eperiod_flag::V_PERIOD => [
                30 =>  80,
            ],
            E\Eperiod_flag::V_NOT_PERIOD => [
                30 =>  80,
            ]
        ];

    }

}
