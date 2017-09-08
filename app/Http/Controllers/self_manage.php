<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class self_manage extends Controller
{
    use CacheNick;

    public  function index() {
        return $this->pageView(__METHOD__);
    }

    public function qingjia() {
        list($start_time,$end_time)= $this->get_in_date_range(date("Y-01-01"), 0 );
        $adminid  = $this->get_account_id();
        $page_num = $this->get_in_page_num();

        $ret_list = $this->t_qingjia->get_list($page_num,$adminid,$start_time,$end_time);
        foreach ($ret_list["list"] as &$item) {
            E\Eqingjia_type::set_item_value_str($item,"type");
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"start_time","", "Y-m-d H");
            \App\Helper\Utils::unixtime2date_for_item($item,"end_time", "", "Y-m-d H");
            $hour_count=$item["hour_count"];
            $day_count=floor($hour_count/8);
            $hour_count_tmp=$item["hour_count"]%8;
            $item["hour_count_str"]=" $day_count 天 $hour_count_tmp 小时 ";
            \App\Helper\Common::set_item_enum_flow_status($item);
        }
        return $this->pageView(__METHOD__,$ret_list);
    }

    public function qingjia_add( ){
        $adminid    = $this->get_account_id();
        $msg        = $this->get_in_str_val("msg");
        $type       = $this->get_in_int_val("type");
        $start_time = $this->get_in_start_time_from_str();
        $end_time   = $this->get_in_end_time_from_str_next_day();
        $hour_count = $this->get_in_int_val("hour_count");

        $this->t_qingjia->start_transaction();
        $this->t_qingjia->row_insert([
            "adminid"    => $adminid,
            "add_time"   => time(NULL),
            "type"       => $type,
            "start_time" => $start_time,
            "end_time"   => $end_time,
            "hour_count" => $hour_count,
            "msg"        => $msg,
        ]);
        $id= $this->t_qingjia->get_last_insertid();
        //审批流程
        $ret=$this->t_flow->add_flow(E\Eflow_type::V_QINGJIA,$adminid,$msg, $id );
        if (!$ret) {
            $this->t_qingjia->rollback();
            return $this->output_err("你的上级不存在,不能提交休假!");
        }
        $this->t_qingjia->commit();
        return $this->output_succ();
    }

    public function qingjia_del() {
        $id      = $this->get_in_id();
        $adminid = $this->get_account_id();

        $del_flag=$this->t_flow->flow_del_by_from_key_int( $adminid, E\Eflow_type::V_QINGJIA, $id);
        if ($del_flag) {
            $this->t_qingjia->row_delete($id);
        }
        return $this->output_bool_ret($del_flag);
    }

    public function flow_list(){
        list($start_time,$end_time)=$this->get_in_date_range(-60,0);
        $adminid         = $this->get_account_id();

        $post_adminid    = $this->get_in_int_val("post_adminid",-1);
        $flow_check_flag = $this->get_in_int_val("flow_check_flag",-1,E\Eflow_check_flag::class);
        $flow_type       = $this->get_in_int_val("flow_type",-1, E\Eflow_type::class );
        $page_num        = $this->get_in_page_num();

        $ret_info=$this->t_flow_node->get_list($page_num,$start_time,$end_time,$adminid,$post_adminid,$flow_type,$flow_check_flag);
        foreach( $ret_info["list"] as &$item) {
            E\Eflow_type::set_item_value_str($item);
            $flow_class=\App\Flow\flow::get_flow_class($item["flow_type"]);
            $item["node_name"]=$flow_class::get_node_name($item["node_type"]);
            $this->cache_set_item_account_nick($item, "post_adminid","post_admin_nick");
            $item["line_data"] =$flow_class::get_line_data($item["from_key_int"] ,$item["from_key_str"],$item["from_key2_int"] );

            E\Eflow_check_flag::set_item_value_str($item);
            \App\Helper\Common::set_item_enum_flow_status($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"check_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"post_time");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function flow_table_data()
    {
        $flowid    = $this->get_in_int_val("flowid");
        $flow_type = $this->get_in_int_val("flow_type");
        if (!$flowid) {
            return $this->output_err("没有数据");
        }
        if (!$flow_type) {
            $flow_type= $this->t_flow->get_flow_type($flowid);
        }

        $flow_class = \App\Flow\flow::get_flow_class($flow_type);
        $table_data = $flow_class::get_table_data($flowid);
        $ret_info   = $this->t_flow_node->get_node_list($flowid,"asc");

        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"check_time");
            $this->cache_set_item_account_nick($item);
            E\Eflow_check_flag::set_item_value_str($item);
            $item["node_name"]=$flow_class::get_node_name($item["node_type"]);
        }

        return $this->output_succ(["table_data"=>$table_data,"node_list"=>$ret_info["list"] ]);
    }

    public function flow_node_set_check_flag() {
        $nodeid          = $this->get_in_int_val("nodeid");
        $flow_check_flag = $this->get_in_int_val("flow_check_flag",1 );
        $check_msg       = $this->get_in_str_val("check_msg" );

        $node_info  = $this->t_flow_node->field_get_list($nodeid,"*");
        $flowid     = $node_info["flowid"] ;
        $flow_info  = $this->t_flow->field_get_list($flowid,"*");
        $flow_type  = $flow_info["flow_type"];
        if($this->get_account_id() != $node_info["adminid"] ) {
            return $this->output_err("你不是审核者!");
        }

        if ($flow_check_flag== E\Eflow_check_flag::V_PASS ) {
            $ret=\App\Flow\flow_base::do_flow_pass($nodeid,$flow_check_flag, $check_msg );
            if($ret) {
                return $this->output_succ();
            }else{
                return $this->output_err("下个审批人不存在");
            }

        } else if ($flow_check_flag== E\Eflow_check_flag::V_NO_PASS ) {
            $this->t_flow_node->set_check_info($nodeid,$flow_check_flag,0);
            $this->t_flow->set_flow_status($flowid, E\Eflow_status::V_NO_PASS);
            return $this->output_succ();
        }else{
            return $this->output_err("未支持flag:$flow_check_flag");
        }


    }
    public function ssh_login() {
        $account = $this->get_account();
        if ( \App\Helper\Utils::check_env_is_release() ) {
            $ip="118.190.115.161";
            \App\Helper\Common::redis_set("SSH_LOGIN_TIME_$account",time(NULL));
            return $this->output_succ( [
                "ssh_cmd"  => "ssh -l$account $ip "
            ]);
        }else{
            return $this->output_err("内网不能用" ) ;
        }
    }
    public function todo_list() {
        list($start_time,$end_time) = $this->get_in_date_range_day(0);
        $todo_type= $this->get_in_el_todo_type();
        $todo_status= $this->get_in_el_todo_status();
        $adminid= $this->get_account_id();
        //$adminid=-1;
        $page_info= $this->get_in_page_info();

        $ret_info=$this->t_todo->get_list( $page_info, $adminid , $todo_type  ,$todo_status,  $start_time, $end_time );
        foreach( $ret_info["list"]  as &$item ) {
            $todo_type=$item["todo_type"];
            E\Etodo_type::set_item_value_str($item);
            E\Etodo_status::set_item_value_color_str($item);
            $msg_arr=\App\Helper\Utils::json_decode_as_array($item["msg"] );
            $item["line_info"]=@$msg_arr[0];
            //$item["jump_url"]=@$msg_arr[1];
            \App\Helper\Utils::unixtime2date_for_item($item,"start_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"end_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
        }
        return $this->pageView(__METHOD__,$ret_info);

    }

    //添加回访
    public function todo_add(){
        $next_revisit_time = $this->get_in_start_time_from_str("","start_time");
        $userid            = $this->get_in_userid();

        $todo_type     = 1001;
        $adminid       = $this->get_account_id();
        $from_key_int  = $userid;
        $from_key2_int = $next_revisit_time;
        $ret=\App\Todo\todo_base::add($todo_type,$next_revisit_time,$next_revisit_time+7200,$adminid,
                                      $from_key_int,$from_key2_int);
        return $this->output_succ();

    }

    public function self_menu_list() {
        $adminid= $this->get_account_id();
        $list=$this->t_admin_self_menu->get_list($adminid);
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list) );
    }


    public function self_menu_add() {
        $url   = $this->get_in_str_val("url");
        $title = $this->get_in_str_val("title");
        $icon  = $this->get_in_str_val("icon");

        $adminid=$this->get_account_id();
        $row=$this->t_admin_self_menu->get_url_info($adminid,$url);
        if ($row) {
            return $this->output_err("已经存在：".$row["title"]);
        }
        $this->t_admin_self_menu->add($adminid,$title,$url,$icon);
        (new login() )->reset_power($this->get_account() );
        return $this->output_succ();
    }

    public function  self_menu_switch() {
        $next_flag = $this->get_in_int_val("next_flag");
        $order_index= $this->get_in_int_val("order_index");
        $adminid = $this->get_account_id();
        $order_index2=$this->t_admin_self_menu->get_next_order_index($adminid,$order_index,$next_flag);
        $id1= $this->t_admin_self_menu->get_id_by_admin_order_index($adminid,$order_index);
        $id2= $this->t_admin_self_menu->get_id_by_admin_order_index($adminid,$order_index2);

        if ($order_index2) {
            $this->t_admin_self_menu->switch_order_index($id1,$id2);
        }
        return $this->output_succ();
    }


    public function  self_menu_del() {
        $adminid = $this->get_account_id();
        $id      = $this->get_in_int_val("id");
        $this->t_admin_self_menu->del($adminid,$id);
        return $this->output_succ();

    }



}
