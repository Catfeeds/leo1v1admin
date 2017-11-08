<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_0 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_0;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-08-01"  , "2017-12-31"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_0 ,  E\Econtract_type::V_3];
        $this->lesson_times_range = [1 ,  10000];
    }

    protected function do_exec ( &$out_args,&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )   {

        if ( !parent::do_exec($out_args,$can_period_flag,$price,$present_lesson_count,$desc_list)){
            return ;
        };

        $old_price = $this->args["old_price"];
        $lesson_count = $this->args["lesson_count"];
        $grade_price = $this->args["grade_price"];

        $price =$old_price;

        $desc_list[]=static::gen_activity_item(1, " $lesson_count 课时 $old_price 元, 一次课 单价:$grade_price ", $price,  $present_lesson_count ,$can_period_flag  );
    }

    function get_desc() {
        $ret_arr=parent::get_desc();

        $ret_arr[]=["", "无优惠,用来显示 原价 " ];
        return $ret_arr;
    }
}