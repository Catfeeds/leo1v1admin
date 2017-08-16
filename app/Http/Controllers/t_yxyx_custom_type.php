<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
class t_yxyx_custom_type extends Controller
{
    use CacheNick;

    public function get_all(){
        $page_info = $this->get_in_page_info();
        $ret_info  = $this->t_yxyx_custom_type->get_all_custom_type($page_info);
        foreach ($ret_info['list'] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            $item["nick"] = $this->cache_get_account_nick($item["adminid"] );
        }
        return $this->pageView( __METHOD__,$ret_info);
    }

    public function get_one_type() {
        $custom_type_id = $this->get_in_int_val('custom_type_id',-1);
        $ret_info = $this->t_yxyx_custom_type->get_one_type($custom_type_id);
        \App\Helper\Utils::unixtime2date_for_item($ret_info,"create_time");
        $ret_info["nick"] = $this->cache_get_account_nick($ret_info["adminid"] );
        return outputjson_success(array('ret_info' => $ret_info));

    }

    public function add_type()
    {
        $type_name = trim($this->get_in_str_val('type_name',''));
        $custom_type_id = $this->get_in_int_val('custom_type_id',-1);
        if($custom_type_id > 0) {
            $this->t_yxyx_custom_type->update_type($custom_type_id,$type_name);
        } else {
            $create_time = time();
            $adminid     = $this->get_account_id();
            $this->t_yxyx_custom_type->add_one_type($create_time, $adminid, $type_name);
        }
        return outputjson_success();
    }

    public function del_type()
    {
        $custom_type_id = $this->get_in_int_val('custom_type_id',-1);
        $this->t_yxyx_custom_type->row_delete($custom_type_id);
        return outputjson_success();
    }
}
