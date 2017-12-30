<?php
namespace App\OrderPrice;
use \App\Enums as E;

class order_price_20180101 extends order_price_base
{



    static public function get_price ( $order_promotion_type, $contract_type, $grade, $lesson_count ,$before_lesson_count,$args){

        $present_lesson_count=0;
        //\App\Helper\Utils::logger("lesson_count: $lesson_count");

        $lesson_times= $lesson_count/3;
        //
        $args["lesson_times"] = $lesson_times;
        //分期
        $can_period_flag =  $args["period_flag"];

        $grade_price_config=Activity\activity_2018010101::get_lesson_price($can_period_flag, 0, $grade);

        $grade_price= $grade_price_config[1];
        $old_price = $grade_price * $lesson_times;

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

        $do_activity_fun ( Activity\activity_2017100701::class  );
        $do_activity_fun ( Activity\activity_2::class  );


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
                if(in_array($v['id'], [2,  2018010101,  2017100701,2017080101])){
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
            $do_activity_fun ( Activity\activity_2018010101::class );
            foreach( $power_small as $item){
                //\App\Helper\Utils::logger("item each: ".json_encode($item));
                $new_do_activity_fun ( $item );
            }
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
