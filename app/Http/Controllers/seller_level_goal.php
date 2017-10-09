<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class seller_level_goal extends Controller
{
    public function seller_level_goal_list(){
        $orderid    = $this->get_in_int_val('orderid');
        $start_time = $this->get_in_int_val('start_time');
        $end_time   = $this->get_in_int_val('end_time');
        $aid        = $this->get_in_int_val('aid');
        $pid        = $this->get_in_int_val('pid');
        $p_price    = $this->get_in_int_val('p_price');
        $ppid       = $this->get_in_int_val('ppid');
        $pp_price   = $this->get_in_int_val('pp_price');
        $userid     = $this->get_in_int_val('userid');
        $page_num   = $this->get_in_page_num();
        $page_info  = $this->get_in_page_info();
        $ret_info  = $this->t_agent_order->get_agent_order_info($page_info,$start_time,$end_time);
        foreach($ret_info['list'] as &$item){
            $item['p_price'] = $item['p_price']/100;
            $item['pp_price'] = $item['pp_price']/100;
            $item['price'] = $item['price']/100;
            E\Ep_level::set_item_value_str($item);
            E\Epp_level::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
            \App\Helper\Utils::unixtime2date_for_item($item,'a_create_time');
        }
        return $this->pageView(__METHOD__,$ret_info);
    }
}
