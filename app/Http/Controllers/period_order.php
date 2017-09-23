<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class period_order extends Controller
{
    use CacheNick;
    use TeaPower;

    public function get_all_payed_order_info(){
        list($start_time,$end_time,$opt_date_type)=$this->get_in_date_range(0,0,4,[
            1 => array("order_time","下单日期"),
            2 => array("o.pay_time", "生效日期"),
            3 => array("app_time", "申请日期"),
            4 => array("c.pay_time","付款日期")
        ],3);

        $contract_type     = $this->get_in_int_val('contract_type',-1);
        $contract_status   = $this->get_in_int_val('contract_status',-1);

        $pay_status = $this->get_in_int_val("pay_status",-1);
        $page_info = $this->get_in_page_info();
        
        $list = $this->t_child_order_info->get_all_period_order_info($start_time,$end_time,$opt_date_type,$page_info,$pay_status,$contract_status,$contract_type);
        foreach($list["list"] as &$item){
            E\Egrade::set_item_value_str($item);           
            E\Econtract_status::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item);
            // E\Esubject::set_item_value_str($item);
            // \App\Helper\Utils::unixtime2date_for_item($item, 'contract_starttime');
            //\App\Helper\Utils::unixtime2date_for_item($item, 'contract_endtime');
            \App\Helper\Utils::unixtime2date_for_item($item, 'order_time',"_str");
            \App\Helper\Utils::unixtime2date_for_item($item, 'pay_time',"_str");
            \App\Helper\Utils::unixtime2date_for_item($item, 'ass_assign_time',"_str");
            \App\Helper\Utils::unixtime2date_for_item($item, 'order_pay_time',"_str");
            //\App\Helper\Utils::unixtime2date_for_item($item, 'lesson_start');          
            $this->cache_set_item_assistant_nick($item,"assistantid", "assistant_nick");           
            $item['lesson_total']         = $item['lesson_total']*$item['default_lesson_count']/100;
            $item['order_left']           = $item['lesson_left']/100;
            $item['competition_flag_str'] = $item['competition_flag']==0?"否":"是";
            $item['pay_status_str'] = $item['pay_status']==0?"未付款":"已付款";
            $item['price'] = $item['price']/100;
            $item['order_price'] = $item['order_price']/100;                   
            

            if ($item['contract_status'] == 0) {
                $item['status_color'] = 'color:red';
            } else {
                $item['status_color'] = 'color:green';
            }
 
        }
        return $this->Pageview(__METHOD__,$list);
  
    }

}
