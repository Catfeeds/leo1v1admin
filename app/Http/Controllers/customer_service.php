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
        //$userid = 99;
        $userid = $this->get_account_id();
        if($userid==349){
            $userid=-1;
        }
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_cs_intended_user_info->get_list($page_info,$userid);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            E\Erelation_ship::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item,"free_subject");
            E\Eregion_version::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    /**
     * @author    sam
     * @function  用户投诉信息录入
     */
    public function  complaint_info () {
        //$userid = 99;
        $userid = $this->get_account_id();
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_cs_complaint_user_info->get_list($page_info,$userid);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            E\Ecomplaint_user_type::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    /**
     * @author    sam
     * @function  用户建议信息录入
     */
    public function  proposal_info () {
        //$userid = 99;
        $userid = $this->get_account_id();
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_cs_proposal_info->get_list($page_info,$userid);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            E\Ecomplaint_user_type::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
        }
        return $this->pageView(__METHOD__, $ret_info);
    }


}