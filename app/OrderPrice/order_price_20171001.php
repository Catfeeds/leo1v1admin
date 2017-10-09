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


    //2017-1008  每满15000减500 88个名额 
    static  public function get_activity_2017100801 (&$price,  &$present_lesson_count,  &$desc_list ){

        $free_money=floor($price /15000)*500;


        $order_activity_type= E\Eorder_activity_type::V_2017100801 ;

        $task= self::get_task_controler();
        $now_count= $task->t_order_activity_info->get_count_by_order_activity_type($order_activity_type);


        $max_count=88;
        $activity_desc_cur_count="当前已用($now_count/$max_count) ";
        if ($now_count<$max_count  && $free_money>0) {
            $price-=$free_money;
            $activity_desc="立减 $free_money, $activity_desc_cur_count ";
            $desc_list[]=static::gen_activity_item($order_activity_type,1, $activity_desc ,  $price,  $present_lesson_count );
        }else{
            $activity_desc=" $activity_desc_cur_count ";
            $desc_list[]=static::gen_activity_item($order_activity_type,0, $activity_desc ,  $price,  $present_lesson_count );

        }


    }
    //2017-0801 当配活动(常规)
    static  public function get_activity_20170801 (&$price,  &$present_lesson_count,  &$desc_list, $args ,$lesson_times ){

        $free_money=0;

        $order_activity_type= E\Eorder_activity_type::V_2017080101;

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
            if ( $lesson_times>=30 && $lesson_start &&  time(NULL)<$check_time  ) {
                $free_money=300;
                $price-=$free_money;

                //2017-0801 当配活动(常规)
                $activity_desc="试听后一天内下单 立减 300元";
                $desc_list[]=static::gen_activity_item($order_activity_type,1, $activity_desc ,  $price,  $present_lesson_count );
            }else{

                $activity_desc="";
                $desc_list[]=static::gen_activity_item($order_activity_type,0, $activity_desc ,  $price,  $present_lesson_count );
            }
        }else{
            $activity_desc="";
            $desc_list[]=static::gen_activity_item($order_activity_type,0, $activity_desc ,  $price,  $present_lesson_count );
        }


    }

    //2017十一 送86折　
    static  public function get_activity_2017100102(&$price  ,&$present_lesson_count,  &$desc_list,  $args ){
        $order_activity_type= E\Eorder_activity_type::V_2017100102;
        $ret=false;
        if($args["from_test_lesson_id"]!=0){
            $from_test_lesson_id=@$args["from_test_lesson_id"];
            $task= self::get_task_controler();
            //当配活动
            $lesson_info= $task->t_lesson_info_b2->field_get_list(
                $from_test_lesson_id,
                "lesson_start,userid,grade,sys_operator");
            $userid=$lesson_info["userid"] ;
            $grade=$lesson_info["grade"] ;
            $first_lesson_info=$task->t_lesson_info_b3->get_grade_first_test_lesson( $userid, $grade );
            $lesson_start = $first_lesson_info["lesson_start"];
            $adminid=$task->t_seller_student_new->get_admin_assignerid($userid);
            $account_role=$task->t_manager_info->get_account_role($adminid);
            if ($account_role == E\Eaccount_role::V_2
                && $lesson_start> strtotime("2017-10-01") 
            ) {
                $now_count= $task->t_order_activity_info->get_count_by_order_activity_type($order_activity_type);
                $max_count=30;
                if ($now_count<$max_count) {
                    $price=$price*0.68;
                    $desc_list[]=static::gen_activity_item($order_activity_type ,1,  "十一68折, 当前已用($now_count/$max_count) " , $price,  $present_lesson_count );
                    $ret=true;
                }else{
                    $desc_list[]=static::gen_activity_item($order_activity_type ,0,  "十一68折, 当前已用($now_count/$max_count) " , $price,  $present_lesson_count );
                }
            }

        }
        return $ret;
    }

    //2017-0901 满课时打折(常规)
    static  public function get_activity_20170901(&$price  ,&$present_lesson_count,  &$desc_list,  $lesson_times ,$new_discount_config  ){
        $order_activity_type= E\Eorder_activity_type::V_2017090101;
        list($find_count_level ,$off_value)=static::get_value_from_config_ex($new_discount_config, $lesson_times, [1,100] );
        $price=$price*$off_value/100;

        if ($off_value<100) {
            //2017-0901 满课时打折(常规)
            $desc_list[]=static::gen_activity_item(E\Eorder_activity_type:: V_2017090101,1,  "$find_count_level 次课 $off_value 折" , $price,  $present_lesson_count );
        }else{
            $desc_list[]=static::gen_activity_item(E\Eorder_activity_type:: V_2017090101,0,  "" , $price,  $present_lesson_count );
        }


    }

    //2017十一 送课时活动
    static  public function get_activity_20171001(&$price  ,&$present_lesson_count,  &$desc_list,  $lesson_times ){

        $order_activity_type= E\Eorder_activity_type::V_2017100101;

        $new_free_lesson_config_1001 = [
            30 => 1,
            60 => 2,
            90 => 4,
            120 => 8,
            160 => 10,
            240 => 16,
            360 => 24,
            480 => 32,
        ];


        $tmp_present_lesson_count=0 ;
        list($find_free_lesson_level , $present_lesson_count_1 )=static::get_value_from_config_ex(
            $new_free_lesson_config_1001,  $lesson_times , [0,0] );
        if ( $present_lesson_count_1) {
            $present_lesson_count += $present_lesson_count_1 *3;
            $desc_list[] = static::gen_activity_item($order_activity_type ,1, "$find_free_lesson_level 次课 送 $present_lesson_count_1 次课  "   , $price,  $present_lesson_count );

        }else{
            $desc_list[]=static::gen_activity_item($order_activity_type,0, 0, $price,  $present_lesson_count );
        }
    }



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
        $off_config_id = static::$grade_price_off_config[$check_grade];
        \App\Helper\Utils::logger("off_config_id:$off_config_id");
        $new_discount_config = $off_config_id==1? static::$new_discount_config_1: static::$new_discount_config_2;

        $old_price = $grade_price/3*$lesson_count;

        $desc_list =  [];
        $price=$old_price;
        $desc_list[]=static::gen_activity_item(E\Eorder_activity_type::V_0,1, " $lesson_count 课时 $old_price 元, 一次课 单价:$grade_price ", $price,  $present_lesson_count );

        $off_86_flag=false;
        if ($order_promotion_type == E\Eorder_promotion_type::V_1) { //课时
        }else if ( $order_promotion_type == E\Eorder_promotion_type::V_2) { //折扣
            //check 6.8折
            $off_86_flag=static::get_activity_2017100102( $price, $present_lesson_count,  $desc_list, $args );
            if (!$off_86_flag) {
            static::get_activity_20170901($price, $present_lesson_count,  $desc_list,  $lesson_times,$new_discount_config );
            }


            $now=time(NULL);
            //if ($now > strtotime("2017-10-01" ) &&  $now < strtotime("2017-10-08" )   ) {
            static::get_activity_20171001($price, $present_lesson_count,  $desc_list,  $lesson_times);
                //}


        }

        static::get_activity_2017100801($price, $present_lesson_count,  $desc_list  );

        if (!$off_86_flag) {
            static::get_activity_20170801($price, $present_lesson_count,  $desc_list,  $args,$lesson_times);
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
