<?php
namespace App\OrderPrice;
use \App\Enums as E;

class order_price_20170701 extends order_price_base
{

    static $grade_price_config = [
        101=> [0=>280,60=>245,90=>240,135=>236,180=>232,270=>224,360=>216,450=>208],
        201=> [0=>310,60=>265,90=>260,135=>256,180=>252,270=>244,360=>236,450=>228],
        202=> [0=>330,60=>285,90=>280,135=>276,180=>272,270=>264,360=>256,450=>248],
        203=> [0=>370,60=>335,90=>330,135=>326,180=>322,270=>314,360=>306,450=>298],
        301=>[0=>415,60=>405,90=>400,135=>396,180=>392,270=>384,360=>376,450=>368],
        302=>[0=>430,60=>425,90=>420,135=>416,180=>412,270=>404,360=>396,450=>388],
        303=>[0=>460,60=>455,90=>450,135=>446,180=>442,270=>434,360=>426,450=>418],
    ];




    static $new_present_lesson_config = [
        90  => 3,
        135 => 6,
        180 => 12,
        270 => 24,
        360 => 45,
        450 => 60,
    ];


    static $grade_price_config_fix = [
        101=> [0=>280,60=>245,90=>240,135=>236-10,180=>232-10,270=>224-10,360=>216-10,450=>208-10],
        201=> [0=>310,60=>265,90=>260,135=>256-10,180=>252-10,270=>244-10,360=>236-10,450=>228-10],
        202=> [0=>330,60=>285,90=>280,135=>276-10,180=>272-10,270=>264-10,360=>256-10,450=>248-10],
        203=> [0=>370,60=>335,90=>330,135=>326,180=>322,270=>314,360=>306,450=>298],
        301=>[0=>415,60=>405,90=>400,135=>396,180=>392-2,270=>384-2,360=>376-2,450=>368-2],
        302=>[0=>430,60=>425,90=>420,135=>416,180=>412-7,270=>404-7,360=>396-7,450=>388-7],
        303=>[0=>460,60=>455,90=>450,135=>446,180=>442-9,270=>434-9,360=>426-9,450=>418-9],
    ];



    static public function get_price ( $order_promotion_type, $contract_type, $grade, $lesson_count ,$before_lesson_count){

        $now=time(NULL);
        $new_flag=false;
        //周年庆活动
        if ($now> strtotime("2017-08-05") && $now < strtotime( "2017-08-12" )  ){
            $new_flag=true;
        }

        $present_lesson_count=0;

        $check_lesson_count= $lesson_count  + $before_lesson_count;

        if ($grade<=106) {
            $check_grade=101;
        }else{
            $check_grade=$grade;
        }

        $discount_config =$new_flag ? static::$grade_price_config_fix[$check_grade]: static::$grade_price_config[$check_grade];

        $per_price_20    = static::get_value_from_config($discount_config, 60,1000 )/3;
        $per_price_0     = static::get_value_from_config($discount_config, 0,1000 )/3;
        $old_per_price   = $lesson_count>=60? $per_price_20: $per_price_0;
        $old_price       = $old_per_price * $lesson_count ;
        $price= $old_price ;

        if ($order_promotion_type == E\Eorder_promotion_type::V_1) { //课时
            $present_lesson_config= static::$new_present_lesson_config;
            $present_lesson_count=static::get_value_from_config($present_lesson_config, $check_lesson_count );
            if ( $check_lesson_count>=450 && $grade<=201 ) {
                $present_lesson_count=69;
            }
            if ($new_flag) {
                if (($check_grade <203 && $check_lesson_count>=135 ) //小学,初一,二
                    || ($check_grade >=301 && $check_lesson_count>=180 ) //高中
                ) {
                    $present_lesson_count+=3;
                }
            }
            $price= $old_price ;

        }else if ( $order_promotion_type == E\Eorder_promotion_type::V_2) { //折扣

            $per_price = static::get_value_from_config($discount_config, $check_lesson_count,1000 )/3;
            $price=$per_price*$lesson_count;
        }


        return [
             "price"                => $old_price,
             "present_lesson_count" => $present_lesson_count ,
             "discount_price"       => $price,
             "discount_count"       => $old_price?floor(($price/$old_price)*10000)/100:100,
             "order_promotion_type" => $order_promotion_type,
             "contract_type"        => $contract_type,
             "grade"                => $grade,
             "lesson_count"         => $lesson_count,
        ];
    }

}
