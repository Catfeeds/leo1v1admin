<?php
namespace App\OrderPrice;

use \App\Enums as E;
class order_price_base {
    //static $cur_order_price_type=E\Eorder_price_type::V_20170901;
    static $cur_order_price_type=E\Eorder_price_type::V_20171001;
    static $order_price_type_config=[
        E\Eorder_price_type::V_20170101 => order_price_20170101::class,
        E\Eorder_price_type::V_20170701 => order_price_20170701::class,
        E\Eorder_price_type::V_20170901 => order_price_20170901::class,
        E\Eorder_price_type::V_20171001 => order_price_20171001::class,
        E\Eorder_price_type::V_20180101 => order_price_20180101::class,
    ];

    /**
     * @return \App\Console\Tasks\TaskController
     */
    static function get_task_controler() {
        return new \App\Console\Tasks\TaskController ();
    }

    static $grade_price_config=[
        /*
        101=> 70,
        102=> 70,
        103=> 70,
        104=> 70,
        105=> 70,
        106=> 75,
        201=> 80,
        202=> 86.6667,
        203=> 110,
        301=> 135,
        302=> 140,
        305=> 150,
        */
    ];

    static $new_discount_config = [
        /*
        30 => 98,
        60 => 96,
        90 => 94,
        120 => 92,
        150 => 90,
        */
    ];

    static $new_present_lesson_config = [
        /*
        30 => 1,
        60 => 4,
        90 => 8,
        120 => 15,
        150 => 20,
        */
    ];

    static $next_discount_config =[
    ];

    static $next_present_lesson_config = [
    ];


    static function get_value_from_config_ex($config,$check_key,$def_value=[0,100]) {

        $last_k=$def_value[0];
        $last_value=$def_value[1];
        foreach ($config as  $k =>$v ) {
            if ($k > $check_key )  {
                return array($last_k , $last_value);
            }
            $last_value= $v;
            $last_k= $k;
        }
        return array($last_k, $last_value);
    }

    static function get_value_from_config($config,$check_key,$def_value=0) {

        $last_value=$def_value;
        foreach ($config as  $k =>$v ) {
            if ($k > $check_key )  {
                return $last_value;
            }
            $last_value= $v;
        }
        return $last_value;
    }

    static public function get_price ($order_promotion_type, $contract_type, $grade, $lesson_count ,$before_lesson_count,$args){
        $present_lesson_count = 0;
        $discount_count       = 100;

        $check_lesson_count = $lesson_count+$before_lesson_count;

        if ($order_promotion_type == E\Eorder_promotion_type::V_1) { //课时
            $present_lesson_config= $contract_type==0?static::$new_present_lesson_config: static::$next_present_lesson_config;
            $present_lesson_count=static::get_value_from_config($present_lesson_config, $check_lesson_count );
        }else if ( $order_promotion_type == E\Eorder_promotion_type::V_2) { //折扣
            $discount_config = $contract_type==0?static::$new_discount_config: static::$next_discount_config;
            $discount_count  = static::get_value_from_config($discount_config, $check_lesson_count,100);
        }

        $price = static::$grade_price_config[$grade]*$lesson_count;

        return [
            "price"                => $price,
            "present_lesson_count" => $present_lesson_count,
            "discount_price"       => $discount_count*$price/100,
            "discount_count"       => $discount_count,
            "order_promotion_type" => $order_promotion_type,
            "contract_type"        => $contract_type,
            "grade"                => $grade,
            "lesson_count"         => $lesson_count,
        ];
    }

    static public function get_competition_price( $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count, $args)
    {
        //默认初三价格
        return static::get_price( $order_promotion_type, $contract_type, E\Egrade::V_203,$lesson_count ,$before_lesson_count ,$args);
    }



    // ------------------
    static public function get_price_ex( $competition_flag, $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count,$args) {
        if ($competition_flag) {
            return static::get_competition_price( $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count, $args);
        }else{
            return static::get_price( $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count,$args);
        }
    }

    static public function get_price_ex_by_order_price_type( $order_price_type, $competition_flag, $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count,$args ) {
        $class_name=static::$order_price_type_config [$order_price_type];
        return $class_name::get_price_ex ( $competition_flag,  $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count, $args);
    }

    static public function get_price_ex_cur( $competition_flag, $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count, $args) {
        if (time(NULL) < strtotime("2017-10-01") ) {
            static::$cur_order_price_type = E\Eorder_price_type::V_20170901 ;
        }
        if (\App\Helper\Utils::check_env_is_local()) {
            static::$cur_order_price_type = E\Eorder_price_type::V_20180101 ;
        }
        return  static::get_price_ex_by_order_price_type(static::$cur_order_price_type , $competition_flag, $order_promotion_type, $contract_type, $grade,$lesson_count ,$before_lesson_count,$args) ;
    }

    static public function gen_desc($title,$succ_flag, $desc="", $price ) {
        /*
        if (!$succ_flag) {
            $desc="";
        }
        */
        return [ "title"=> $title , "succ_flag"=> $succ_flag , "desc"=>$desc, "price" => $price  ];
    }

    static public function gen_activity_item($order_activity_type,$succ_flag, $desc , $cur_price, $cur_present_lesson_count  ) {
        return [ "order_activity_type" => $order_activity_type ,
                 "succ_flag"=> $succ_flag ,
                 "activity_desc"=>$desc,
                 "cur_price" => $cur_price  ,
                 "cur_present_lesson_count" => $cur_present_lesson_count
        ];
    }
}