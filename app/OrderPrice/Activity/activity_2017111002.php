<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017111002 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017111002;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-11-10"  , "2017-11-12"];
        $this->user_join_time_range=["2017-11-01", "2017-11-15"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0   ];
        $this->contract_type_list = [ E\Econtract_type::V_0 ];
        $this->lesson_times_range = [45 ,45];

        $this->grade_off_perent_list =[
            E\Eperiod_flag::V_NOT_PERIOD => [ //全款
                E\Egrade::V_101 =>  85 ,
                E\Egrade::V_102 =>  85 ,
                E\Egrade::V_103 =>  85 ,
                E\Egrade::V_104 =>  85 ,
                E\Egrade::V_105 =>  85 ,
                E\Egrade::V_106 =>  85 ,
                E\Egrade::V_201 =>  85 ,
                E\Egrade::V_202 =>  85 ,

                E\Egrade::V_203 =>  89 ,
                E\Egrade::V_301 =>  89 ,
                E\Egrade::V_302 =>  89 ,
                E\Egrade::V_303 =>  89 ,
            ]
        ];
    }


}