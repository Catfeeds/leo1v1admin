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
        //  $msg = $this->get_in_str_val("msg");
        $ret_info=$this->t_ssh_login_log->get_list($page_info,$start_time, $end_time);
        foreach ($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"login_time");

        }
        //dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info);

    }


    public function login_add( ) 
        $account= $this->get_in_str_val("account");
        $serverip= $this->get_in_str_val("serverip");
        $type= $this->get_in_int_val("type");
        \App\Helper\Utils::logger("serverip $serverip ");

        $this->t_ssh_login_log->row_insert([
            "login_time" =>  time(NULL) ,
            "account" =>  $account,
            "serverip" =>  $serverip,
            "type" =>  $type,
        ]);
        return $this->output_succ();
    }
    public function login_edit() {
        $account= $this->get_in_str_val("account");
        $serverip= $this->get_in_str_val("serverip");
        $type= $this->get_in_int_val("type");
        $this->t_ssh_login_log->field_update_list($id,[
            "login_time" =>  time(NULL) ,
            "account" =>  $accont,
            "serverip" =>  $serverip,
            "type" =>  $type,
        ]);

        return $this->output_succ();
    }
    public function login_del() {
        $id= $this->get_in_int_val("id");
        $this->t_ssh_login_log->row_delete($id);
        return $this->output_succ();
    }

    




}