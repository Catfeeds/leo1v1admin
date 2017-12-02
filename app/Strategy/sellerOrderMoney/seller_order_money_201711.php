<?php
namespace App\OrderPrice;
namespace App\Strategy\sellerOrderMoney  ;

class seller_order_money_201711  extends  seller_order_money_base
{

    static $percent_config = [
        0     => 0,
        40000 => 3,
        60000 => 5,
        90000 => 8,
        150000 => 10,
        200000 => 11.5,
        260000 => 13.5,
    ];


    static function  get_info( $adminid, $start_time, $end_time  )  {

        /** @var  \App\Console\Tasks\TaskController  $tt*/
        $tt= new \App\Console\Tasks\TaskController();
        $ret_arr=$tt->t_order_info->get_seller_money_info($adminid,$start_time,$end_time);
        //获取分期不分期金额
        // $sort = $tt->t_order_info->get_sort_order_count_money($adminid,$start_time,$end_time);
        // $sort = $tt->t_order_info->get_sort_order_count_money_new($adminid,$start_time,$end_time);
        // $sort_new = [];
        // $sort_new_two = [];
        // $parent_orderid_arr = array_unique(array_column($sort,'parent_orderid'));
        // foreach($parent_orderid_arr as $info){
        //     foreach($sort as $item){
        //         if($item['parent_orderid'] == $info){
        //             $sort_new[$info][] = $item;
        //         }
        //     }
        // }
        // foreach($sort_new as $item){
        //     foreach($item as $info){
        //         $type = $info['child_order_type'];
        //         $parent_orderid = $info['parent_orderid'];
        //         $price = $info['price'];
        //         if($type == 2){
        //             $sort_new_two[$parent_orderid]['stage_money'] = $price;
        //             $sort_new_two[$parent_orderid]['no_stage_money'] = 0;
        //             break;
        //         }else{
        //             $sort_new_two[$parent_orderid]['stage_money'] = 0;
        //             $sort_new_two[$parent_orderid]['no_stage_money'] = $price;
        //         }
        //     }
        // }
        // $stage_money = count($sort)>0?array_sum(array_column($sort_new_two,'stage_money'))/100:0;
        // $no_stage_money = count($sort)>0?array_sum(array_column($sort_new_two,'no_stage_money'))/100:0;
        // $ret_arr['stage_money'] = $stage_money;
        // $ret_arr['no_stage_money'] = $no_stage_money;
        $stage_money = $ret_arr['stage_money'];
        $no_stage_money = $ret_arr['no_stage_money'];

        // $ret_arr["all_price"] = $ret_arr['stage_money']*0.8+$ret_arr['no_stage_money'];

        $create_time= $tt->t_manager_info->get_create_time($adminid);
        $cur_month= date("m", $start_time );
        $check_date_1_1= mktime(0,0,0, $cur_month-3,25  );
        $check_date_1_2= mktime(0,0,0, $cur_month-2,25  );
        $new_account_value=1;
        /*
        if ($create_time >  $check_date_1_2 ) {
            $new_account_value = 1.2;
        }else if ( $create_time >  $check_date_1_1 ) {
            $new_account_value = 1.1;
        }
        */

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
        if ($percent >0  ||  $ret_arr["group_adminid"] == $adminid ) {
            $percent_value=$percent/100;
            $group_all_price= $ret_arr[ "group_all_price"];
            $all_price= $ret_arr["all_price"];
            $group_all_stage_price= $ret_arr["group_all_stage_price"];
            $group_all_no_stage_price= $ret_arr["group_all_no_stage_price"];
            //$v_24_hour_all_price= $ret_arr["24_hour_all_price"];
            $require_all_price=$ret_arr["require_all_price"];
            $require_and_24_hour_price=$ret_arr["require_and_24_hour_price"];



            $all_price_1 =  $all_price - (   $require_all_price - $require_and_24_hour_price  ) ;

            $require_all_price_1=( $require_all_price - $require_and_24_hour_price );
            $v24_hour_all_price_1 =  0;
            $require_and_24_hour_price_1 = $require_and_24_hour_price;

            $group_money_add_percent_val=$group_money_add_percent/100;

            // $money= ($all_price * $percent_value - $require_all_price *$percent_value*0.15) * $new_account_value  + $group_all_price *  $group_money_add_percent_val    ;
            // $desc= "($all_price * $percent_value - $require_all_price *$percent_value*0.15) * $new_account_value + $group_all_price *  $group_money_add_percent_val  "  ;
            $money= ($stage_money * $percent_value * 0.8 + $no_stage_money * $percent_value - $require_all_price *$percent_value*0.15) * $new_account_value  + $group_all_stage_price *  $group_money_add_percent_val * 0.8 + $group_all_no_stage_price * $group_money_add_percent_val  ;
            $desc ="($stage_money * $percent_value * 0.8 + $no_stage_money * $percent_value - $require_all_price *$percent_value*0.15) * $new_account_value + $group_all_stage_price *  $group_money_add_percent_val * 0.8 + $group_all_no_stage_price * $group_money_add_percent_val  ";
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

        // $ret_arr['stage_money'] = $sort['stage_money']/100;
        // $ret_arr['no_stage_money'] = $sort['no_stage_money']/100;

        return $ret_arr;
    }

    static function  get_info_next( $adminid, $start_time, $end_time  )  {

        /** @var  \App\Console\Tasks\TaskController  $tt*/
        $tt= new \App\Console\Tasks\TaskController();
        $ret_arr=$tt->t_order_info->get_seller_money_info($adminid,$start_time,$end_time);
        $stage_money = $ret_arr['stage_money'];
        $no_stage_money = $ret_arr['no_stage_money'];

        $next_all_price = static::$percent_config;
        foreach($next_all_price as $key=>$info){
            if($ret_arr['all_price']<$key){
                $ret_arr['group_all_price'] += ($key - $ret_arr['all_price']);
                $ret_arr['all_price'] = $key;
                break;
            }
        }

        $create_time= $tt->t_manager_info->get_create_time($adminid);
        $cur_month= date("m", $start_time );
        $check_date_1_1= mktime(0,0,0, $cur_month-3,25  );
        $check_date_1_2= mktime(0,0,0, $cur_month-2,25  );
        $new_account_value=1;
        /*
        if ($create_time >  $check_date_1_2 ) {
            $new_account_value = 1.2;
        }else if ( $create_time >  $check_date_1_1 ) {
            $new_account_value = 1.1;
        }
        */

        $percent = static::get_pecent_config( $ret_arr["all_price"]);
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
        $ret_arr["percent"] = $percent;
        $money=0;
        $desc="";

        $all_price_1 =0;

        $require_all_price_1=0;
        $v24_hour_all_price_1 =0;
        $require_and_24_hour_price_1 =0;
        if ($percent >0  ||  $ret_arr["group_adminid"] == $adminid ) {
            $percent_value = $percent/100;
            $group_all_price= $ret_arr[ "group_all_price"];
            $all_price= $ret_arr["all_price"];
            $group_all_stage_price= $ret_arr["group_all_stage_price"];
            $group_all_no_stage_price= $ret_arr["group_all_no_stage_price"];
            //$v_24_hour_all_price= $ret_arr["24_hour_all_price"];
            $require_all_price=$ret_arr["require_all_price"];
            $require_and_24_hour_price=$ret_arr["require_and_24_hour_price"];



            $all_price_1 =  $all_price - (   $require_all_price - $require_and_24_hour_price  ) ;

            $require_all_price_1=( $require_all_price - $require_and_24_hour_price );
            $v24_hour_all_price_1 =  0;
            $require_and_24_hour_price_1 = $require_and_24_hour_price;

            $group_money_add_percent_val=$group_money_add_percent/100;

            // $money= ($all_price * $percent_value - $require_all_price *$percent_value*0.15) * $new_account_value  + $group_all_price *  $group_money_add_percent_val    ;
            // $desc= "($all_price * $percent_value - $require_all_price *$percent_value*0.15) * $new_account_value + $group_all_price *  $group_money_add_percent_val  "  ;
            $money= ($stage_money * $percent_value * 0.8 + $no_stage_money * $percent_value - $require_all_price *$percent_value*0.15) * $new_account_value  + $group_all_stage_price *  $group_money_add_percent_val * 0.8 + $group_all_no_stage_price * $group_money_add_percent_val  ;
            $desc ="($stage_money * $percent_value * 0.8 + $no_stage_money * $percent_value - $require_all_price *$percent_value*0.15) * $new_account_value + $group_all_stage_price *  $group_money_add_percent_val * 0.8 + $group_all_no_stage_price * $group_money_add_percent_val  ";
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

}
