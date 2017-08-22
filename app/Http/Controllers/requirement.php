<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class requirement extends Controller
{
    use CacheNick;
    /**
     * @author    sam
     * @function  需求开发信息
     */
    public function requirement_info () {
        //$userid = 99;
        $userid = $this->get_account_id();
        if($userid==349){
            $userid=-1;
        }
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_requirement_info->get_list($page_info,$userid);
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
            if(($item['status'] == 1 && $item['product_status'] < 2) ||
               ($item['status'] == 2 && $item['product_status'] < 2) ){
                $item['flag'] = true;
            }
            if($item['status'] == 2){
                $item['operator_status'] = E\Erequire_product_status::get_desc($item['product_status']);
            }

        }
        //dd($ret_info);
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function requirement_info_product()
    {
        dd(2);
    }

    /**
     * @author    sam
     * @function  需求信息录入
     */
    public function add_requirement_info(){
        $name            = $this->get_in_int_val('name');
        $priority        = $this->get_in_int_val('priority');
        $significance    = $this->get_in_int_val('significance');
        $expect_time     = strtotime($this->get_in_str_val('expect_time'));
        $statement       = $this->get_in_str_val('statement');
        $content_pic     = $this->get_in_str_val('content_pic');
        $notes           = $this->get_in_str_val('notes');
        $create_time     = time();
        $create_adminid  = $this->get_account_id();
        $this->t_requirement_info->row_insert([
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'name'                     => $name,
            'priority'                 => $priority ,
            'significance'             => $significance,
            'expect_time'              => $expect_time,
            'statement'                => $statement,
            'content_pic'              => $content_pic,
            'notes'                    => $notes,
            "status"                   => 2, //提交到达产品
            "product_status"           => 0, //产品未处理
         ]);
        return $this->output_succ();
    }
}