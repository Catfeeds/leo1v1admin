<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017112502  extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017112502;

    public static $need_spec_require_flag = 0;

    public function __construct(  $args   ) {
        parent::__construct($args);
        //$this->open_flag=false;
        $this->date_range=[ "2017-11-24"  , "2017-11-30"];
        $this->user_join_time_range=["2015-11-01", "2017-11-10"];

        $this->period_flag_list= [ E\Eperiod_flag::V_0  ];
        $this->contract_type_list = [ E\Econtract_type::V_0 ];
        $this->max_count=38;

        $this->lesson_times_present_lesson_count = [
            60  => 4,
            90  => 6,
            120 => 8,
        ];
    }
}
