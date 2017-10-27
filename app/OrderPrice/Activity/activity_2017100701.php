<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017100701 extends  activity_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017100701;

    public function __construct(  $args   ) {
        parent::__construct($args);
    }

    protected function do_exec (&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )   {
        if ( $can_period_flag ) {
            $grade=$this->args["grade"] ;
            $grade_str=E\Egrade::get_desc($grade);
            if (($this->lesson_times>=45 && $grade = E\Egrade::V_303  ) || $this->lesson_times>=60  ){
                //$desc_list[]=static::gen_activity_item(1, "$grade_str  ", $price,    $present_lesson_count ,$can_period_flag  );
            }else{
                $can_period_flag=false;
                $desc_list[]=static::gen_activity_item(0, " $grade_str {$this->lesson_times}次 不可开启分期", $price,  $present_lesson_count ,$can_period_flag  );
            }
        }

    }

}