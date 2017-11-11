<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017111003  extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017111003;

    public function __construct(  $args   ) {
        parent::__construct($args);

        //2017-1110-12 ,CR,90次, 全款, 送9次  
        $this->date_range=[ "2017-11-10"  , "2017-11-12"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0  , E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [ E\Econtract_type::V_3 ];
        $this->lesson_times_range=[90,90];

        $this->lesson_times_present_lesson_count = [
            90  => 9,
        ];
    }
}