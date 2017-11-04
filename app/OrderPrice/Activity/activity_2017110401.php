<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017110401 extends  activity_base {


    public static $order_activity_type= E\Eorder_activity_type::V_2017110401;

    public function __construct(  $args   ) {
        parent::__construct($args);
    }


    protected function do_exec ( &$out_args, &$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )   {
        //2017-1104 11.4-6 助教 报读 送课 60->6, 90->8, 120->10, 150->11,180->12 
        if (!$this->check_now("2017-11-04"  , "2017-11-06")) {
            return ;
        }
        //续费
        if ($this->contract_type != E\Econtract_type::V_3 ) {
            return ;
        }

        $new_free_lesson_config = [
            60  => 6,
            90  => 8,
            120 => 10,
            150 => 11,
            180 => 12,
            210 => 13,
            240 => 14,
            270 => 15,
            300 => 16,
        ];


        $tmp_present_lesson_count=0 ;
        list($find_free_lesson_level , $present_lesson_count_1 )=static::get_value_from_config_ex(
            $new_free_lesson_config,  $this->lesson_times , [0,0] );
        if ( $present_lesson_count_1) {
            $present_lesson_count += $present_lesson_count_1 *3;
            $desc_list[] = static::gen_activity_item(1, "$find_free_lesson_level 次课 送 $present_lesson_count_1 次课  "   , $price,  $present_lesson_count, $can_period_flag );

        }else{
            $desc_list[]=static::gen_activity_item(0, " {$this->lesson_times}次课 未匹配", $price,  $present_lesson_count,$can_period_flag );
        }
    }


}