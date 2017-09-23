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

        $pay_status = $this->get_in_int_val("pay_status");
        $page_info = $this->get_in_page_info();
        
        $list = $this->t_child_order_info->get_all_period_order_info($start_time,$end_time,$opt_date_type,$page_info,$pay_status,$contract_status,$contract_type);
        dd($list);
  
    }

}
