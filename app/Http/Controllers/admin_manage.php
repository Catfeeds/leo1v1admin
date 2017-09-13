<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class admin_manage extends Controller
{
    use CacheNick;
    public function kaoqin_machine() {
        $page_info = $this->get_in_page_info();
        $ret_info  = $this->t_kaoqin_machine->get_list($page_info);
        foreach( $ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"last_post_time");
            E\Eboolean::set_item_value_str($item,"open_door_flag");
        }

        return $this->pageView(__METHOD__,$ret_info);
    }


    public function kaoqin_machine_adminid() {
        $page_info = $this->get_in_page_info();
        $machine_id = $this->get_in_int_val("machine_id",-1);
        $adminid    = $this->get_in_adminid(-1);
        $auth_flag = $this->get_in_e_boolean(-1,"auth_flag");
        $ret_info=$this->t_kaoqin_machine_adminid->get_list($page_info,$machine_id,$adminid,$auth_flag);

        foreach( $ret_info["list"] as &$item ) {
            E\Eboolean::set_item_value_str($item,"auth_flag");
            $this->cache_set_item_account_nick($item);
        }

        $machine_info = $this->t_kaoqin_machine->get_list(["page_num"=>1, "page_count"=>10000 ]);

        return $this->pageView(
            __METHOD__,$ret_info,
            ["machine_list" => $machine_info["list"]  ]);


    }

    public function office_cmd_list() {
        $sync_data_list= \App\Helper\office_cmd::get_list();
        $ret_info=\App\Helper\Utils::list_to_page_info($sync_data_list);
        $last_require_time=\App\Helper\office_cmd::get_last_require_time();
        foreach( $ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            E\Eoffice_device_type::set_item_value_str($item);
            E\Edevice_opt_type::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__, $ret_info,[
            "last_require_time" => \App\Helper\Utils::unixtime2date($last_require_time),
        ]);
    }
    public function group_email_list() {
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_mail_group_name->get_list($page_info);
        return  $this->pageView(__METHOD__,$ret_info);
    }
    public function group_email_user_list() {
        $groupid=$this->get_in_int_val("groupid");
        $adminid= $this->get_in_adminid();
        $page_info= $this->get_in_page_info();
        if (!($groupid>0) ) {
            return $this->error_view(["没有选择群邮箱"]);
        }
        $ret_info= $this->t_mail_group_user_list->get_list( $page_info , $groupid, $adminid);
        $title=$this->t_mail_group_name->get_title($groupid);
        return  $this->pageView(__METHOD__,$ret_info, [
            "title" => $title
        ]);
    }

}