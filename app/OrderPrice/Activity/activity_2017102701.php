<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017102701  extends  activity_base {


    public static $order_activity_type= E\Eorder_activity_type::V_2017102701;

    public function __construct(  $args   ) {
        parent::__construct($args);
    }


    protected function do_exec (&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )   {
        //2017-102701 27-31   送课:  60->3(全款) ,90->6,120->9.. :  ->((n/30)-1)*3
        if (!$this->check_now("2017-10-27"  , "2017-11-01")) {
            return ;
        }

        $new_free_lesson_config = [
            60  => 3,
            90  => 6,
            120 => 9,
            150 => 12,
            180 => 15,
            210 => 18,
            240 => 21,
            270 => 24,
            300 => 27,
            330 => 30,
            360 => 33,
            390 => 36,
            420 => 39,
            450 => 42,
        ];

        if ( $can_period_flag  ) { //分期去掉 60->3
            unset($new_free_lesson_config[60] );
        }

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