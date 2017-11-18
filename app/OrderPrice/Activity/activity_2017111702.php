<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017111702 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017111702;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-11-18"  , "2017-11-20"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_3 ];
        $this->lesson_times_range = [45 ,  10000];
        $this->max_count=18;
        self::$max_count_activity_type_list=[
            E\Eorder_activity_type::V_2017111702,
            E\Eorder_activity_type::V_2017111703,
            E\Eorder_activity_type::V_2017111704,
            E\Eorder_activity_type::V_2017111705,
        ];

        $this->check_grade_list=[
            E\Egrade::V_101,
            E\Egrade::V_102,
            E\Egrade::V_103,
            E\Egrade::V_104,
            E\Egrade::V_105,
            E\Egrade::V_106,
            E\Egrade::V_201,
            E\Egrade::V_202,
        ];

        $this->lesson_times_off_perent_list=[
            E\Eperiod_flag::V_PERIOD => [
                45 =>  80,
            ],
            E\Eperiod_flag::V_NOT_PERIOD => [
                45 =>  80,
            ]
        ];

    }

}
