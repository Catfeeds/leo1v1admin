<?php
namespace App\OrderPrice;
use \App\Enums as E;

class order_price_20170901 extends order_price_base
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
        45 => 4.5,
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

        $old_price = $grade_price/3*$lesson_count;
        $desc_list =  [];
        $price=$old_price;
        $desc_list[]=static::gen_activity_item(E\Eorder_activity_type::V_0,1, " $lesson_count 课时 $old_price 元, 一次课 单价:$grade_price ", $price,  $present_lesson_count );

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
                //2017-0901 满课时打折(常规)
                $desc_list[]=static::gen_activity_item(E\Eorder_activity_type:: V_2017090101,1,  "$find_count_level 次课 $off_value 折" , $price,  $present_lesson_count );
            }else{
                $desc_list[]=static::gen_activity_item(E\Eorder_activity_type:: V_2017090101,0,  "" , $price,  $present_lesson_count );
            }
            //28-29 号 送课时

            $activity_desc="";
            if ($lesson_times  >=90 ) { //满90次课，买10送1
                $present_lesson_times= intval( $lesson_times/10 );
                $present_lesson_count= $present_lesson_times*3;
                $activity_desc="满90次课，买10送1 , 赠送$present_lesson_times 次课 ";
            }else if ( $lesson_times >=60) {
                $present_lesson_count= 5*3;
                $activity_desc="满60次课 赠送 5 次课 ";
            }else if ( $lesson_times >=30) {
                $present_lesson_count= 2*3;
                $activity_desc="满30次课 赠送 2 次课 ";
            }
            if ($present_lesson_count  ) {
                $desc_list[]=static::gen_activity_item(E\Eorder_activity_type:: V_2017092801,1, $activity_desc ,  $price,  $present_lesson_count );

            }else{
                $desc_list[]=static::gen_activity_item(E\Eorder_activity_type:: V_2017092801,0, "",  $price,  $present_lesson_count );
            }



            //满课时 90课时送 6课时 ,  2*90课时送 2*6课时   ,3*90课时 3*6
            /*

            list($find_free_lesson_level , $present_lesson_count )=static::get_value_from_config_ex(
                static::$new_free_lesson_config,  $check_lesson_count , [0,0] );
            if ( $present_lesson_count) {
                $task= self::get_task_controler();
                $use_count=$task->t_order_info->get_type1_lesson_count_by_start_time(strtotime("2017-09-16") );
                $use_count/=100;
                $max_present_count=600 ;
                $use_desc_str="";

                if ($use_count+ $present_lesson_count*2 >=  $max_present_count*3  ) {

                    if ( $present_lesson_count==4.5) { //普通45课时送3课时
                        $present_lesson_count=3;
                    }
                    $use_count_v=$use_count /3;
                    $use_desc_str="<br/>600次课特殊赠送  结束 已用 $use_count_v  次课 ";
                }else{ //600次赠送
                    $present_lesson_count*=2;
                    $left_count=($max_present_count*3- $use_count )/3;
                    $use_desc_str= "<br/>600次课特殊赠送 剩余 $left_count 次课";

                }
                $tmp= $present_lesson_count/3;

                $desc_list[]=static::gen_desc("活动-满课时送课",true, "$find_free_lesson_level 次课 送 $tmp 次课   $use_desc_str ", $price );
            }else{
                $desc_list[]=static::gen_desc("活动-满课时送课",false ,"",$price );
            }
            */

        }

        $free_money=0;

        if($args["from_test_lesson_id"]!=0){
            $from_test_lesson_id=@$args["from_test_lesson_id"];
            $task= self::get_task_controler();
            //当配活动
            $lesson_info= $task->t_lesson_info_b2->field_get_list(
                $from_test_lesson_id,
                "lesson_start,userid,grade");
            $userid = $lesson_info["userid"];
            $grade  = $lesson_info["grade"];
            $cur_lesson_start = $lesson_info["lesson_start"];
            $first_lesson_info=$task->t_lesson_info_b3->get_grade_first_test_lesson( $userid, $grade );
            $lesson_start = $first_lesson_info["lesson_start"];

            $check_time= strtotime( date("Y-m-d", $lesson_start) )+86400*2;
            if ( $lesson_count>=30*3 && $lesson_start &&  time(NULL)<$check_time  ) {
                $free_money=300;
                $price-=$free_money;

                //2017-0801 当配活动(常规)
                $activity_desc="试听后一天内下单 立减 300元";
                $desc_list[]=static::gen_activity_item(E\Eorder_activity_type::V_2017080101 ,1, $activity_desc ,  $price,  $present_lesson_count );
            }else{

                $activity_desc="";
                $desc_list[]=static::gen_activity_item(E\Eorder_activity_type::V_2017080101 ,0, $activity_desc ,  $price,  $present_lesson_count );
            }
        }else{
            $activity_desc="";
            $desc_list[]=static::gen_activity_item(E\Eorder_activity_type::V_2017080101 ,0, $activity_desc ,  $price,  $present_lesson_count );
        }




        return [
             "price"                => $old_price,
             "present_lesson_count" => $present_lesson_count ,
             "discount_price"       => $price,
             "discount_count"       => @floor(($price/$old_price)*10000)/100,
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
