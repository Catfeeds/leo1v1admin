<?php
namespace App\OrderPrice;
use \App\Enums as E;

class order_price_20170901 extends order_price_base
{

    static $grade_price_config = [
        99 => 280, //
        101 => 280,
        201 => 310,
        202 => 330,
        203 => 370,
        301 => 430,
        302 => 450,
        303 => 480,
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
        $check_lesson_count= $lesson_count /3 ;

        if ($grade<=106) {
            $check_grade=101;
        }else{
            $check_grade=$grade;
        }

        $grade_price_config=static::$grade_price_config;

        $grade_price = $grade_price_config[$check_grade];

        $old_price = $grade_price/3*$lesson_count;
        $desc_list =  [];
        $price=$old_price;

        if ($order_promotion_type == E\Eorder_promotion_type::V_1) { //课时
            /*
            $present_lesson_config= static::$new_present_lesson_config;
            $present_lesson_count=static::get_value_from_config($present_lesson_config, $check_lesson_count );
            if ( $check_lesson_count>=450 && $grade<=201 ) {
                $present_lesson_count=69;
            }

            $price = $old_price ;
            */
        }else if ( $order_promotion_type == E\Eorder_promotion_type::V_2) { //折扣
            $off_config_id = static::$grade_price_off_config[$check_grade];
            \App\Helper\Utils::logger("off_config_id:$off_config_id");

            $new_discount_config = $off_config_id==1? static::$new_discount_config_1: static::$new_discount_config_2;
            list($find_count_level ,$off_value)=static::get_value_from_config_ex($new_discount_config, $check_lesson_count , [1,100] );
            $price=$grade_price*$off_value/100/3 * $lesson_count;

            if ($off_value<100) {
                $desc_list[]=static::gen_desc("满课时打折",1, "$find_count_level 次课 $off_value 折");
            }else{
                $desc_list[]=static::gen_desc("满课时打折",false);
            }
        }

        /*

        // 活动
        $free_money=0;
        $find_free_money_lesson_count= 0;
        if  ( $lesson_count >=90*3) {
            $find_free_money_lesson_count=90;
            $free_money=1000;
        }else if ( $lesson_count >=60*3 ) {
            $find_free_money_lesson_count=60;
            $free_money=650;
        }else  if ( $lesson_count >=45*3 ) {
            $find_free_money_lesson_count=45;
            $free_money=300;
        }else  if ( $lesson_count >=30*3 ) {
            $find_free_money_lesson_count=30;
            $free_money=200;
        }
        if ($free_money) {
            $desc_list[]=static::gen_desc("满课时立减",true, "$find_free_money_lesson_count 次课 立减 $free_money 元" );
        }else{
            $desc_list[]=static::gen_desc("满课时立减",false  );
        }
        $price-=$free_money;
        */
        /*
        if($args["from_test_lesson_id"]!=0){
            $from_test_lesson_id=@$args["from_test_lesson_id"];
            $task= self::get_task_controler();
            \App\Helper\Utils::logger("hd free_money= $free_money");
            //当配活动
            $lesson_start= $task->t_lesson_info_b2->get_lesson_start($from_test_lesson_id);
            $check_time= strtotime( date("Y-m-d", $lesson_start) )+86400*2;
            if ( $lesson_count>=30*3 && $lesson_start &&  time(NULL)<$check_time  ) {
                $free_money+=300;
                \App\Helper\Utils::logger("hd 2 free_money= $free_money");
            }
        }else{

        }
        */


        return [
             "price"                => $old_price,
             "present_lesson_count" => $present_lesson_count ,
             "discount_price"       => $price,
             "discount_count"       => floor(($price/$old_price)*10000)/100,
             "order_promotion_type" => $order_promotion_type,
             "contract_type"        => $contract_type,
             "grade"                => $grade,
             "lesson_count"         => $lesson_count,
             "desc_list"           => $desc_list
        ];
    }
    static public function get_competition_price( $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count, $args)
    {
        return static::get_price( $order_promotion_type, $contract_type, 99,$lesson_count ,$before_lesson_count ,$args);
    }

}
