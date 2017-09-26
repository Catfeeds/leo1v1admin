<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
class t_yxyx_new_list extends Controller
{
    use CacheNick;
    public function get_all(){
        $page_info = $this->get_in_page_info();
        $ret_info  = $this->t_yxyx_new_list->get_all_list($page_info);
        foreach ($ret_info['list'] as &$item) {
               \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
               $item["nick"] = $this->cache_get_account_nick($item["adminid"]);
        }
        return $this->pageView(__METHOD__,$ret_info,array(),['qiniu_upload_domain_url' =>
                                                             Config::get_qiniu_public_url()."/"
        ]);
    }

    public function get_one_new() {
        $id = $this->get_in_int_val('id',-1);
        $ret_info = $this->t_yxyx_new_list->get_one_new_info($id);
        \App\Helper\Utils::unixtime2date_for_item($ret_info,"create_time");
        $ret_info['new_content'] = str_replace('img src','img width="100%" src',$ret_info['new_content']);
        $ret_info['new_content'] = str_replace('width:&nbsp;670px','width:&nbsp;100%',$ret_info['new_content']);
        $ret_info["nick"] = $this->cache_get_account_nick($ret_info["adminid"]);
        return outputjson_success(array('ret_info' => $ret_info));

    }

    public function add_new_info()
    {
        $new_title   = $this->get_in_str_val('new_title','');
        $new_content = $this->get_in_str_val('new_content','');
        $new_pic     = $this->get_in_str_val('new_pic','');
        $create_time = time();
        $adminid     = $this->get_account_id();
        $ret_info    = $this->t_yxyx_new_list->add_new($new_title, $new_content, $new_pic, $adminid, $create_time);
        return outputjson_success();
    }

    public function update_new_info()
    {
        $id          = $this->get_in_int_val('id','');
        $new_title   = $this->get_in_str_val('new_title','');
        $new_content = $this->get_in_str_val('new_content','');
        $new_pic     = $this->get_in_str_val('new_pic','');
        $create_time = time();
        $adminid     = $this->get_account_id();
        $ret_info    = $this->t_yxyx_new_list->update_new($id, $new_title, $new_content, $new_pic, $adminid, $create_time);
        return outputjson_success();
    }

    public function del_new_info()
    {
        $id = $this->get_in_int_val('id',-1);
        $ret_info = $this->t_yxyx_new_list->row_delete($id);
        return outputjson_success();
    }

}