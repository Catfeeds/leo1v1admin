<?php
namespace App\Config;


use \App\Enums as E;

class teacher_price_base  {

    static public function gen_level_name_config()  {
        $ret_map=[];
        foreach (static::$level_config as $level => $item ) {
            $v=$item[0];
            $pre_level=$level-1;
            if ($pre_level==0)  {
                $pre_v=0;
            }else{
                $pre_v=static::$level_config[$pre_level][0];
            }
            if ($v==0xFFFFFFFF ) {
                $ret_map[$level]= sprintf(">= %d", intval($pre_v/100 ) );
            }else{
                $ret_map[$level]= sprintf("%d-%d", intval($pre_v/100)+1, intval( $v/100) );
            }
        }
        return $ret_map;
    }

    //累计课时
    static public function get_lesson_count_level( $lesson_count ){
        foreach(static::$level_config  as $level => $item ) {
            $v=$item[0];
            if ($lesson_count<$v) {
                return $level;
            }
        }
        return -1;
    }

    static function get_price_class( $teacher_money_type, $level) {
        if($teacher_money_type == E\Eteacher_money_type::V_1 ) {
            $price_class=\App\Config\hs_teacher_price::class;
        }else if($teacher_money_type == E\Eteacher_money_type::V_2 ) {
            $price_class=\App\Config\waipin_teacher_price::class;
        }else if($teacher_money_type == E\Eteacher_money_type::V_0 ) {
            if ($level<=2) {
                $price_class=\App\Config\teacher_price::class;
            }else{
                $price_class=\App\Config\old_teacher_price::class;
            }
        }else if($teacher_money_type == E\Eteacher_money_type::V_3 ) { 
            $price_class=\App\Config\guding_teacher_price::class;
        }
        return $price_class;
    }

    static function get_money( $teacher_money_type,$level, $lesson_count,  $grade, $already_lesson_count ) {
        $price_class        = static::get_price_class($teacher_money_type,$level);
        $lesson_count_level = $price_class::get_lesson_count_level($already_lesson_count);
        $pre_price          = $price_class::get_price($level,$grade,$lesson_count_level);
        return $pre_price*$lesson_count;
    }
    
}
