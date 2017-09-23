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
        list($start_time, $end_time)=$this->get_in_date_range(0,0,0,[],3);
        $pay_status = $this->get_in_int_val("pay_status");
        $page_info = $this->get_in_page_info();
        
        $list = $this->t_child_order_info->get_all_period_order_info($start_time,$end_time,$page_info,$pay_status);
  
    }

}
