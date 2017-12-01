<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017100701 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017100701;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-08-01"  , "2017-12-31"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_0 ,  E\Econtract_type::V_3];
        $this->lesson_times_range = [1 ,  10000];

        $this->can_disable_flag=false;

    }

    protected function do_exec (  &$out_args,&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )   {

        if ( !parent::do_exec($out_args,$can_period_flag,$price,$present_lesson_count,$desc_list)){
            return ;
        };

        if ( $can_period_flag ) {
            $can_period_flag= E\Eperiod_flag::V_PERIOD;
            $grade=$this->args["grade"] ;
            $grade_str=E\Egrade::get_desc($grade);
            if (($grade = E\Egrade::V_303 && $this->lesson_times>=30     ) || $this->lesson_times>=60  ){
                //$desc_list[]=static::gen_activity_item(1, "$grade_str  ", $price,    $present_lesson_count ,$can_period_flag  );
            }else{
                $can_period_flag= E\Eperiod_flag::V_NOT_PERIOD ;
                $desc_list[]=static::gen_activity_item(0, " $grade_str {$this->lesson_times}次 不可开启分期", $price,  $present_lesson_count ,$can_period_flag  );
            }
        }

    }

}