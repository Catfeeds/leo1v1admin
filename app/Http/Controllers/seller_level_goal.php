<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class seller_level_goal extends Controller
{
    public function seller_level_goal_list(){
        $page_num   = $this->get_in_page_num();
        $page_info  = $this->get_in_page_info();
        $ret_info  = $this->t_seller_level_goal->get_all_list($page_info);
        foreach($ret_info['list'] as &$item){
            E\Epp_level::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
        }
        return $this->pageView(__METHOD__,$ret_info);
    }
}
