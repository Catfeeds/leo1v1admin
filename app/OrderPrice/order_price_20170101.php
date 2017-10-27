<?php
namespace App\OrderPrice;
use \App\Enums as E;

class order_price_20170101 extends order_price_base
{

    static $grade_price_config=[
        101=> 70,
        102=> 70,
        103=> 70,
        104=> 70,
        105=> 70,
        106=> 75,
        201=> 80,
        202=> 86.66666666666666666667,
        203=> 110,
        301=> 135,
        302=> 140,
        303=> 150,
    ];
    static $new_discount_config = [
        90 => 98,
        180 => 96,
        270 => 94,
        360 => 92,
        450 => 90,
    ];

    static $new_present_lesson_config = [
        90 => 3,
        180 => 12,
        270 => 24,
        360 => 45,
        450 => 60,
    ];

    static $next_discount_config =[
        180 => 96,
        270 => 94,
        360 => 92,
        450 => 90,
    ];

    static $next_present_lesson_config = [
        180 => 12,
        270 => 24,
        360 => 45,
        450 => 60,
    ];
    //2017-10 20-21签单 , 10号前试听的 每满10000减1000
    static  public function get_activity_2017102001 (&$price,  &$present_lesson_count, &$desc_list ,  $lesson_times,$args ){


        $order_activity_type= E\Eorder_activity_type::V_2017102001 ;

        $task= self::get_task_controler();
        if($args["from_test_lesson_id"]!=0){
            $from_test_lesson_id=@$args["from_test_lesson_id"];
            $task= self::get_task_controler();
            $lesson_info= $task->t_lesson_info_b2->field_get_list(
                $from_test_lesson_id,
                "lesson_start,userid,grade");

            $userid = $lesson_info["userid"];
            $grade  = $lesson_info["grade"];
            #$last_lesson_info=$task->t_lesson_info_b3->get_grade_last_test_lesson( $userid, $grade );
            $lesson_count_all=$task->t_student_info->get_lesson_count_all($userid);

            #$lesson_start = $last_lesson_info["lesson_start"];
            $lesson_start = $lesson_info["lesson_start"];
            $now=time(NULL);
            if ( !$lesson_count_all && $now >= strtotime("2017-10-20")
                 && $now < strtotime("2017-10-24") ) {


                if ($lesson_start < strtotime("2017-10-10") && $lesson_times >=60 && $price){ //10号前
                    $free_money=floor($price /10000)*500;
                    $price-=$free_money;
                    $activity_desc=" 立减 $free_money 元";
                    $desc_list[]=static::gen_activity_item($order_activity_type,1, $activity_desc ,  $price,  $present_lesson_count );
                }else{
                    $activity_desc="";
                    $desc_list[]=static::gen_activity_item($order_activity_type,0, $activity_desc ,  $price,  $present_lesson_count );
                }

            }
        }

    }

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
            $lesson_start = $lesson_info["lesson_start"];
            $first_lesson_info=$task->t_lesson_info_b3->get_grade_first_test_lesson( $userid, $grade );
            $lesson_start = $first_lesson_info["lesson_start"];

            $lesson_start_desc=" 试听课时间:".\App\Helper\Utils::unixtime2date($lesson_start );

            $check_time= strtotime( date("Y-m-d", $lesson_start) )+86400*2;
            if ( $lesson_times>=30 && $lesson_start &&  time(NULL)<$check_time  ) {
                $free_money=300;
                $price-=$free_money;

                //2017-0801 当配活动(常规)
                $activity_desc="试听后一天内下单 立减 300元 ,$lesson_start_desc";
                $desc_list[]=static::gen_activity_item($order_activity_type,1, $activity_desc ,  $price,  $present_lesson_count );
            }else{

                $activity_desc="$lesson_start_desc";
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



}