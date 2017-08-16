<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
class t_yxyx_new_list extends Controller
{
    use CacheNick;
    public function get_all(){
        // $type = $this->get_in_int_val('type',-1);
        $page_info= $this->get_in_page_info();
        $ret_info = $this->t_yxyx_new_list->get_all_list($page_info);
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
        $ret_info = $this->t_yxyx_wxnews_info->get_one_new_info($id);
        $ret_info['create_time'] = date("Y-m-d",$ret_info['create_time']);
        return outputjson_success(array('ret_info' => $ret_info));

    }

    public function add_new_info()
    {
        $title       = $this->get_in_str_val('title','');
        $des         = $this->get_in_str_val('des','');
        $pic         = $this->get_in_str_val('pic','');
        $new_link    = $this->get_in_str_val('new_link','');
        $type        = $this->get_in_int_val('type',-1);
        $create_time = time();
        $adminid     = $this->get_account_id();
        $ret_info    = $this->t_yxyx_wxnews_info->add_new($title, $des, $pic, $new_link, $adminid, $type, $create_time);
        return outputjson_success();
    }

    public function update_new_info()
    {
        $id          = $this->get_in_int_val('id','');
        $title       = $this->get_in_str_val('title','');
        $des         = $this->get_in_str_val('des','');
        $pic         = $this->get_in_str_val('pic','');
        $new_link    = $this->get_in_str_val('new_link','');
        $type        = $this->get_in_int_val('type',-1);
        $create_time = time();
        $ret_info    = $this->t_yxyx_wxnews_info->update_new($id, $title, $des, $pic, $new_link, $type, $create_time);
        return outputjson_success();
    }



    public function del_new_info()
    {
        $id = $this->get_in_int_val('id',-1);

        $ret_info=$this->t_yxyx_wxnews_info->row_delete($id);

        return outputjson_success();
    }


}