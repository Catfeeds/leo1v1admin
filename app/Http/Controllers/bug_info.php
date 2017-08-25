<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class bug_info extends Controller
{
    use CacheNick;
    /**
     * @author    sam
     * @function  需求开发信息
     */
    public function bug_list () {
        //$userid = 99;
        

        /*        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $name = $this->get_in_int_val('name',"-1");
        $priority = $this->get_in_int_val('priority',"-1");
        $significance = $this->get_in_int_val('significance',"-1");
        $status       = $this->get_in_int_val('status',"-1");
        $product_status = $this->get_in_int_val('product_status',"-1");
        $development_status = $this->get_in_int_val('development_status',"-1");
        $test_status = $this->get_in_int_val('test_status',"-1");
        $now_status = 1;
        */
        $userid = $this->get_account_id();
     
        if($userid==349){
            $userid=-1;
        }
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_bug_info->get_list($page_info,$userid);
        dd($ret_info);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"expect_time");
            $item['name_str']        = E\Erequire_class::get_desc($item["name"]);
            $item['priority_str']    = E\Erequire_priority::get_desc($item["priority"]);
            $item['significance_str']= E\Erequire_significance::get_desc($item["significance"]);
            $item['status_str']      = E\Erequire_status::get_desc($item["status"]);
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
            $item['flag'] = false;
            if(($item['status'] == 2 && $item['product_status'] == 0)){
                $item['flag'] = true;
            }
            if($item['status'] == 1){
                $item['operator_status'] = E\Erequire_product_status::get_desc($item['product_status']);
                $this->cache_set_item_account_nick($item,"create_adminid","operator_nick" );
            }elseif($item['status'] == 2){
                $item['operator_status'] = E\Erequire_product_status::get_desc($item['product_status']);
                $this->cache_set_item_account_nick($item,"product_operator","operator_nick" );
            }elseif($item['status'] == 3){
                $item['operator_status'] = E\Erequire_development_status::get_desc($item['development_status']);
                $this->cache_set_item_account_nick($item,"development_operator","operator_nick" );
            }elseif($item['status'] >= 4){
                $item['operator_status'] = E\Erequire_test_status::get_desc($item['test_status']);
                $this->cache_set_item_account_nick($item,"test_operator","operator_nick" );
            }

        }
        // dd($ret_info);
        return $this->pageView(__METHOD__, $ret_info);
    }
}