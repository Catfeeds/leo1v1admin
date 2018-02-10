<?php
namespace App\Strategy\sellerOrderMoney  ;

use \App\Enums as E;
class seller_order_money_base {

    static $cur_type = E\Eseller_order_money::V_201801;

    static $type_config=[
        E\Eseller_order_money::V_201703=>  seller_order_money_201703::class,
        E\Eseller_order_money::V_201702=>  seller_order_money_201702::class,
        E\Eseller_order_money::V_201705=>  seller_order_money_201705::class,
        E\Eseller_order_money::V_201709=>  seller_order_money_201709::class,
        E\Eseller_order_money::V_201710=>  seller_order_money_201710::class,
        E\Eseller_order_money::V_201711=>  seller_order_money_201711::class,
        E\Eseller_order_money::V_201712=>  seller_order_money_201712::class,
        E\Eseller_order_money::V_201801=>  seller_order_money_201801::class,
    ];

    static $percent_config = [
        0     => 0,
        20000 => 3,
        50000 => 5,
        80000 => 8,
        130000 => 10,
        180000 => 12,
        230000 => 15,
    ];


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
    static function get_pecent_config( $money ) {
        return static::get_value_from_config(static::$percent_config  ,$money);
    }

    static function  get_info( $adminid, $start_time, $end_time  )  {

        /** @var $tt \App\Console\Tasks\TaskController */
        $tt= new \App\Console\Tasks\TaskController();
        $ret_arr=$tt->t_order_info->get_seller_money_info($adminid,$start_time,$end_time);

        $create_time= $tt->t_manager_info->get_create_time($adminid);
        $cur_month= date("m", $start_time );
        $check_date_1_1= mktime(0,0,0, $cur_month-3,25  );
        $check_date_1_2= mktime(0,0,0, $cur_month-2,25  );
        $new_account_value=1;
        if ($create_time >  $check_date_1_2 ) {
            $new_account_value = 1.2;
        }else if ( $create_time >  $check_date_1_1 ) {
            $new_account_value = 1.1;
        }

        $percent=static::get_pecent_config( $ret_arr["all_price"]);
        $group_money_add_percent=0;

        if ( $ret_arr["group_default_money"] >0  ) {
            if ($ret_arr[ "group_all_price"] >= $ret_arr["group_default_money"] ) {
                if ($percent){
                    $percent+=1.5;
                }
                if ($ret_arr["group_adminid"] == $adminid) { //是主管
                    $group_money_add_percent=1.8;
                }
            }else if ($ret_arr[ "group_all_price"] >= $ret_arr["group_default_money"] *0.75 ) {

                if ($ret_arr["group_adminid"] == $adminid) { //是主管
                    $group_money_add_percent=0.8;
                }
            }
        }
        $ret_arr["percent"]=$percent;
        $money=0;
        $desc="";

        $all_price_1 =0;

        $require_all_price_1=0;
        $v24_hour_all_price_1 =0;
        $require_and_24_hour_price_1 =0;
        if ($percent >0 ) {
            $percent_value=$percent/100;
            $group_all_price= $ret_arr[ "group_all_price"];
            $all_price= $ret_arr["all_price"];
            $v_24_hour_all_price= $ret_arr["24_hour_all_price"];
            $require_all_price=$ret_arr["require_all_price"];
            $require_and_24_hour_price=$ret_arr["require_and_24_hour_price"];



            $all_price_1 =  $all_price - (  $v_24_hour_all_price + $require_all_price - $require_and_24_hour_price  ) ;

            $require_all_price_1=( $require_all_price - $require_and_24_hour_price );
            $v24_hour_all_price_1 =  ( $v_24_hour_all_price - $require_and_24_hour_price );
            $require_and_24_hour_price_1 = $require_and_24_hour_price;

            $group_money_add_percent_val=$group_money_add_percent/100;

            $money= ($all_price * $percent_value + $v_24_hour_all_price *$percent_value*0.1 - $require_all_price *$percent_value*0.15) * $new_account_value  + $group_all_price *  $group_money_add_percent_val    ;
            $desc= "($all_price * $percent_value + $v_24_hour_all_price *$percent_value*0.1 - $require_all_price *$percent_value*0.15) * $new_account_value + $group_all_price *  $group_money_add_percent_val  "  ;
            //$money=($all_price_1  * $percent_value + $v24_hour_all_price_1 *$percent_value*1.1 + $require_all_price_1 *$percent_value*0.85  +   $require_and_24_hour_price_1*$percent_value *0.85*1.1  ) * $new_account_value  ;
            //$desc="($all_price_1  * $percent_value + $v24_hour_all_price_1 *$percent_value*1.1 + $require_all_price_1 *$percent_value*0.85  +   $require_and_24_hour_price_1*$percent_value *0.85*1.1  ) * $new_account_value  ";

        }
        $ret_arr["money"] =$money;
        $ret_arr["desc"] = $desc ;
        $ret_arr["cur_month_money"] =$money*0.8;
        $ret_arr["three_month_money"] =$money*0.2;

        $ret_arr["all_price_1"]= $all_price_1;
        $ret_arr["require_all_price_1"]= $require_all_price_1;
        $ret_arr["v24_hour_all_price_1"]= $v24_hour_all_price_1;
        $ret_arr["require_and_24_hour_price_1"]= $require_and_24_hour_price_1;
        $ret_arr["group_money_add_percent"]= $group_money_add_percent;

        $ret_arr["new_account_value"] =  $new_account_value;
        $ret_arr["create_time"] = \App\Helper\Utils::unixtime2date($create_time, "Y-m-d"  );

        return $ret_arr;
    }

    static function  get_info_by_type( $type, $adminid, $start_time, $end_time  )  {
        $class_name=static::$type_config[$type];
        return $class_name::get_info(  $adminid, $start_time, $end_time );
    }

    static public function get_cur_info( $adminid, $start_time, $end_time) {
        return  static::get_info_by_type(static::$cur_type , $adminid, $start_time, $end_time) ;
    }

    static function  get_info_by_type_next( $type, $adminid, $start_time, $end_time  )  {
        $class_name=static::$type_config[$type];
        return $class_name::get_info_next(  $adminid, $start_time, $end_time );
    }

    static public function get_cur_info_next( $adminid, $start_time, $end_time) {
        return  static::get_info_by_type_next(static::$cur_type , $adminid, $start_time, $end_time) ;
    }


}