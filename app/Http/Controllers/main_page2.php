<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class main_page2 extends Controller
{

    var $switch_tongji_database_flag = true;
    use CacheNick;
    function __construct()  {
        parent::__construct();
    }
    public  function market() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $role_2_diff_money= $this->t_order_info-> get_spec_diff_money_all( $start_time,$end_time,E\Eaccount_role::V_2 );
        $role_1_diff_money= $this->t_order_info-> get_spec_diff_money_all( $start_time,$end_time,E\Eaccount_role::V_1 );
        $role_2_diff_money_def= $this->t_config_date->get_config_value(E\Econfig_date_type::V_MONTH_MARKET_SELLER_DIFF_MONEY ,strtotime( date("Y-m-01", $start_time) ));

        return $this->pageView(__METHOD__,null,[
            "role_1_diff_money" => $role_1_diff_money ,
            "role_2_diff_money" => $role_2_diff_money ,
            "role_2_diff_money_def" =>  $role_2_diff_money_def,
        ]);
    }
}
