<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;


class contract_present extends Controller

{
    use CacheNick;
    //处理订单与赠送课程
    public function contract_present_info( )
    {
        list($start_time,$end_time) = $this->get_in_date_range_month(0 );
        $subject      = $this->get_in_e_subject(-1);
        $grade        = $this->get_in_e_grade(-1);
        $require_flag = $this->get_in_e_boolean(-1,"require_flag");
        $class_hour   = $this->get_in_e_boolean(-1,"class_hour");
        $account_role = $this->get_in_e_account_role(-1);

        $page_num = $this->get_in_page_num();
        $ret_info = $this->t_order_info-> get_order_desc_list($page_num,$start_time,$end_time, $subject, $grade,$require_flag, $class_hour, $account_role);

        $all_discount_price = 0;
        $all_cost_value = 0;

        foreach ($ret_info["list"] as &$item) {
            $this->cache_set_item_student_nick($item);
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Eaccount_role::set_item_value_str($item);
            if ($item["t_1_lesson_count"]) {
                $item['cost_price'] = ($item['discount_price'] - $item['price'])/100 + ($item['discount_price']/$item['t_1_lesson_count'] ) * ($item['t_2_lesson_count']/100);
            }else{
                $item['cost_price'] = "出错" ;
            }

            $v=\App\Helper\Common::div_safe($item['discount_price'] - $item['cost_price']*100, $item['discount_price']);
            $item['discount_rate'] = number_format($v*100,2).'%';
            $all_discount_price += $item['discount_price'];
            $all_cost_value  +=  $item['cost_price'] ;
        }

        $all_discount_price = $all_discount_price /100;
        $v=\App\Helper\Common::div_safe( $all_discount_price -  $all_cost_value , $all_discount_price);
        $all_discount_rate =  number_format($v*100,2).'%' ;

        return $this->pageView(__METHOD__,$ret_info, [
            "all_discount_price" => $all_discount_price,
            "all_price" => $all_discount_price -  $all_cost_value  ,
            "all_discount_rate" => $all_discount_rate,
        ]);
    }

}
