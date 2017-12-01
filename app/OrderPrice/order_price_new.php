<?php
namespace App\OrderPrice;
use \App\Enums as E;

class order_price_new extends order_price_base
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

        $grade_price = @$grade_price_config[$check_grade];
        if(!$grade_price ) { //年级有错
            $grade_price = 10000;
        }
        $off_config_id = static::$grade_price_off_config[$check_grade];
        \App\Helper\Utils::logger("off_config_id:$off_config_id");
        $new_discount_config = $off_config_id==1? static::$new_discount_config_1: static::$new_discount_config_2;
        $args["new_discount_config"]= $new_discount_config;

        $old_price = $grade_price/3*$lesson_count;

        \App\Helper\Utils::logger( "old price:$old_price");
        $desc_list =  [];
        $price=0;
        $args["old_price"] =$old_price;
        $args["lesson_count"] =$lesson_count;
        $args["grade_price"] =$grade_price;
        $args["grade"] =$grade;
        $args["contract_type"] =$contract_type;

        $out_args=[];

        $do_activity_fun= function( $class_name ) use ( &$args, &$out_args, &$can_period_flag, &$price,&$present_lesson_count,&$desc_list ) {
            return (new $class_name($args))->exec( $out_args, $can_period_flag, $price,$present_lesson_count,$desc_list) ;
        };
        //get do act list >=100, < 100
        //$row

        $do_activity_fun ( Activity\activity_0::class  );
        $do_activity_fun ( Activity\activity_2017100701::class  );


        $new_do_activity_fun= function( $activity_config ) use ( &$args, &$out_args, &$can_period_flag, &$price,&$present_lesson_count,&$desc_list ) {
            return (new  Activity\activity_config_new ( $activity_config , $args))->exec( $out_args, $can_period_flag, $price,$present_lesson_count,$desc_list) ;
        };
        $task=static::get_task_controler();

        $current_activity_list = $task->t_order_activity_config->get_current_activity(null, null);
        $power_big = [];
        $power_small = [];
        $activity = [];
        if($current_activity_list){
            foreach($current_activity_list['list'] as $v){
                if(in_array($v['id'], [1, 2017100701 , 2017110901 , 2017110802,2017110803,2017080101])){
                    continue;
                }

                if($v['power_value'] >= 100){
                    $power_big[] = $v;
                }else{
                    $power_small[] = $v;
                }
            }
        }

        $find_big_true=false;

        foreach( $power_big as $item){
            $find_big_true= $new_do_activity_fun ( $item );
            if ($find_big_true) {
                break;
            }
        }

        if (! $find_big_true){
            $do_activity_fun ( Activity\activity_2017110901::class  );
            foreach( $power_small as $item){
                \App\Helper\Utils::logger("item each: ".json_encode($item));
                $new_do_activity_fun ( $item );
            }
            //优惠券 cc
            $do_activity_fun ( Activity\activity_2017110802::class  );
            //优惠券 cr
            $do_activity_fun ( Activity\activity_2017110803::class  );
            //当配
            $do_activity_fun ( Activity\activity_2017080101::class  );

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
             "desc_list"           => $desc_list,
             "can_period_flag"     => $can_period_flag ,
             "out_args"      =>$out_args,
        ];
    }

    static public function get_competition_price( $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count, $args)
    {
        return static::get_price( $order_promotion_type, $contract_type, 99,$lesson_count ,$before_lesson_count ,$args);
    }

}
