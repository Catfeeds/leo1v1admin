<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017090101 extends  activity_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017090101 ;

    public function __construct(  $args   ) {
        parent::__construct($args);
    }

    protected function do_exec (  &$out_args,&$can_period_flag , &$price,  &$present_lesson_count,  &$desc_list )   {
        $new_discount_config= $this->args["new_discount_config"] ;
        \App\Helper\Utils::logger(" do price: $price");


        list($find_count_level ,$off_value)=static::get_value_from_config_ex($new_discount_config, $this->lesson_times, [1,100] );
        $price=$price*$off_value/100;
        if ($off_value<100) {
            //2017-0901 满课时打折(常规)
            $desc_list[]=static::gen_activity_item(1,  "$find_count_level 次课 $off_value 折" , $price,  $present_lesson_count, $can_period_flag );
        }else{
            $desc_list[]=static::gen_activity_item(0,  "" , $price,  $present_lesson_count , $can_period_flag );
        }

    }

}