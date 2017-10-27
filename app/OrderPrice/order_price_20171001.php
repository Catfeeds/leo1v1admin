<?php
namespace App\OrderPrice;
use \App\Enums as E;

class order_price_20171001 extends order_price_base
{

    static $grade_price_config = [
        99 => 370, //
        101 => 280,
        201 => 310,
        202 => 330,
        203 => 370,
        301 => 430,
        302 => 450,
        303 => 480,
    ];


    static $new_free_lesson_config = [
        30 => 3,
        60 => 6,
        90 => 9,
        120 => 12,
        150 => 15,
        180 => 18,
        210 => 21,
        240 => 24,
        270 => 27,
        300 => 30,
        330 => 33,
        360 => 36,
        390 => 39,
        420 => 42,
        450 => 45,
        480 => 48,
        510 => 51,
        540 => 54,
    ];


    static $new_discount_config_1 = [
        30 => 90,
        60 => 86,
        90 => 82,
        120 =>78,
        160 =>74,
        240 =>70,
        360 =>66,
        480 =>62,
    ];
    static $new_discount_config_2 = [
        30 => 95,
        60 => 91,
        90 => 90,
        120 =>88,
        160 =>86,
        240 =>84,
        360 =>82,
        480 =>80,
    ];

    static $grade_price_off_config = [
        99 => 1 ,
        101 => 1 ,
        201 => 1,
        202 => 1,
        203 => 2,
        301 => 2,
        302 => 2,
        303 => 2,
    ];



    static public function get_price ( $order_promotion_type, $contract_type, $grade, $lesson_count ,$before_lesson_count,$args){

        $present_lesson_count=0;
        \App\Helper\Utils::logger("lesson_count: $lesson_count");

        $lesson_times= $lesson_count/3;
        $args["lesson_times"] = $lesson_times;
        $can_period_flag =  $args["period_flag"];

        $check_lesson_count= $lesson_times;

        if ($grade ==99 ) {
            $check_grade=99;
        } else if ($grade<=106) {
            $check_grade=101;
        }else{
            $check_grade=$grade;
        }

        $grade_price_config=static::$grade_price_config;

        $grade_price = $grade_price_config[$check_grade];
        $off_config_id = static::$grade_price_off_config[$check_grade];
        \App\Helper\Utils::logger("off_config_id:$off_config_id");
        $new_discount_config = $off_config_id==1? static::$new_discount_config_1: static::$new_discount_config_2;
        $args["new_discount_config"]= $new_discount_config;

        $old_price = $grade_price/3*$lesson_count;

        $desc_list =  [];
        $price=0;
        $args["old_price"] =$old_price;
        $args["lesson_count"] =$lesson_count;
        $args["grade_price"] =$grade_price;
        $args["grade"] =$grade;

        (new Activity\activity_0($args))->exec($can_period_flag, $price,$present_lesson_count,$desc_list, $can_period_flag) ;

        (new Activity\activity_2017100701($args))->exec($can_period_flag, $price,$present_lesson_count,$desc_list, $can_period_flag) ;


        //$off_86_flag=false;
        if ($order_promotion_type == E\Eorder_promotion_type::V_1) { //课时
        }else if ( $order_promotion_type == E\Eorder_promotion_type::V_2) { //折扣
            $off_flag=(new Activity\activity_2017102702( $args ))->exec( $can_period_flag,$price,$present_lesson_count,$desc_list) ;
            if (!$off_flag) {
                (new Activity\activity_2017090101( $args ))->exec( $can_period_flag,$price,$present_lesson_count,$desc_list) ;

            }
        }


        (new Activity\activity_2017102701( $args ))->exec($can_period_flag,$price,$present_lesson_count,$desc_list) ;

        //当配
        (new Activity\activity_2017080101( $args ))->exec($can_period_flag,$price,$present_lesson_count,$desc_list) ;


        return [
             "price"                => $old_price,
             "present_lesson_count" => $present_lesson_count ,
             "discount_price"       => $price,
             "discount_count"       => @floor(($price/$old_price)*10000)/100,
             "order_promotion_type" => $order_promotion_type,
             "contract_type"        => $contract_type,
             "grade"                => $grade,
             "lesson_count"         => $lesson_count,
             "desc_list"           => $desc_list,
             "can_period_flag"     => $can_period_flag ,
        ];
    }
    static public function get_competition_price( $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count, $args)
    {
        return static::get_price( $order_promotion_type, $contract_type, 99,$lesson_count ,$before_lesson_count ,$args);
    }

}
