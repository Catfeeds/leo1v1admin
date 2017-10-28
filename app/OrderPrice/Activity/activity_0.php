<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_0 extends  activity_base {

    public static $order_activity_type= E\Eorder_activity_type::V_0;

    public function __construct(  $args   ) {
        parent::__construct($args);
    }

    protected function do_exec (&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )   {
        $old_price = $this->args["old_price"];
        $lesson_count = $this->args["lesson_count"];
        $grade_price = $this->args["grade_price"];

        $price =$old_price;

        $desc_list[]=static::gen_activity_item(1, " $lesson_count 课时 $old_price 元, 一次课 单价:$grade_price ", $price,  $present_lesson_count ,$can_period_flag  );
    }

}