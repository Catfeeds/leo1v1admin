<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class login_log extends Controller
{
    use CacheNick;

    public function login_list() {
        $page_info=$this->get_in_page_info();
        list($start_time, $end_time  ) =$this->get_in_date_range_day(0);
        $account=$this->get_in_str_val("account");
        $all_account  = $this->get_in_e_boolean(0,"all_account"); //是否查询全部用户
        $ret_info=$this->t_ssh_login_log->get_list($page_info,$account,$all_account,$start_time, $end_time);
        foreach ($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"login_time");
            $item["server_ip"] = long2ip($item["server_ip"]);
            $item["login_ip"] = long2ip($item["login_ip"]);
        }
        //dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info);

    }


    public function login_add(){
        $account= $this->get_in_str_val("account");
        $server_ip= $this->get_in_int_val("server_ip");
        $login_ip= $this->get_in_int_val("login_ip");
        $login_succ_flag= $this->get_in_int_val("login_succ_flag");
        \App\Helper\Utils::logger("server_ip $server_ip ");

        $this->t_ssh_login_log->row_insert([
            "login_time" =>  time(NULL) ,
            "account" =>  $account,
            "server_ip" =>  $server_ip,
            "login_ip" =>  $login_ip,
            "login_succ_flag" =>  $login_succ_flag,
        ]);
        return $this->output_succ();
    }

    public function login_edit() {
        $id= $this->get_in_int_val("id");
        $account= $this->get_in_str_val("account");
        $server_ip= $this->get_in_int_val("server_ip");
        $login_ip= $this->get_in_int_val("login_ip");
        $login_succ_flag= $this->get_in_int_val("login_succ_flag");
        \App\Helper\Utils::logger("server_ip $server_ip ");

        $this->t_ssh_login_log->field_update_list($id,[
            "login_time" =>  time(NULL) ,
            "account" =>  $account,
            "server_ip" =>  $server_ip,
            "login_ip" =>  $login_ip,
            "login_succ_flag" =>  $login_succ_flag,
        ]);

        return $this->output_succ();
    }
    public function login_del() {
        $id= $this->get_in_int_val("id");
        $this->t_ssh_login_log->row_delete($id);
        return $this->output_succ();
    }






}