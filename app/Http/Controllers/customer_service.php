<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class customer_service extends Controller
{
    use CacheNick;
     /**
     * @author    sam
     * @function  意向用户信息录入
     */
    public function  intended_user_info () {
        $userid = 99;
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_cs_intended_user_info->get_list($page_info,$userid);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            //$ret_info['list'][$key]['score'] = 100 * $ret_info['list'][$key]['score'] /  $ret_info['list'][$key]['total_score']
            //\App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            //\App\Helper\Utils::unixtime2date_for_item($item,"stu_score_time","","Y-m-d");
            //E\Esubject::set_item_value_str($item);
            //E\Esemester::set_item_value_str($item);
            //E\Egrade::set_item_value_str($item);
            //E\Estu_score_type::set_item_value_str($item);
            //$this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
        }

        return $this->pageView(__METHOD__, $ret_info);
    }

}