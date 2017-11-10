<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017102701  extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017102701;

    public function __construct(  $args   ) {
        parent::__construct($args);

        //2017-102701 27-31   送课:  60->3(全款) ,90->6,120->9.. :  ->((n/30)-1)*3
        $this->date_range=[ "2017-10-27"  , "2017-11-31"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0   ];
        $this->contract_type_list = [E\Econtract_type::V_0 ];
        $this->lesson_times_present_lesson_count = [
            60  => 3,
            90  => 6,
            120 => 8,
            150 => 10,
            180 => 12,
            210 => 14,
            240 => 16,
            270 => 18,
            300 => 20,
            330 => 22,
            360 => 24,
            390 => 26,
            420 => 28,
            450 => 30,
        ];
    }
}