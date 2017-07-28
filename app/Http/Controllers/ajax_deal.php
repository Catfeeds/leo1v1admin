<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class ajax_deal extends Controller
{
    use CacheNick;

    public function get_flow_node_list_for_js() {
        $flowid=$this->get_in_int_val("flowid");
        if (!$flowid)  {
            return $this->output_err("没有数据");
        }
        $flow_type=$this->t_flow->get_flow_type($flowid);
        if ($flow_type==0) {
            return $this->output_err("没有数据");
        }

        $flow_class=\App\Flow\flow::get_flow_class($flow_type);
        $ret_info=$this->t_flow_node->get_node_list($flowid);

        foreach($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"check_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            $item["node_name"] = $flow_class::get_node_name($item["node_type"]);
            $this->cache_set_item_account_nick($item);
            E\Eflow_check_flag::set_item_value_str($item);
        }

        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return $this->output_succ(array('data' => $ret_info));
    }

    public function get_flow_list_for_js() {
        $flowid=$this->get_in_int_val("flowid");
        $data=$this->t_flow->field_get_list($flowid,"*");
        $flow_type=$data["flow_type"];
        $adminid=$data["post_adminid"];

        if (!$flowid  || !$flow_type ) {
            return $this->output_err("没有数据");
        }
        /**  @var  $flow_class   \App\Flow\flow_qingjia */

        $flow_class=\App\Flow\flow::get_flow_class($flow_type);
        $node_type=0;

        $ret_list=[];
        do{

            $ret_list[]=[
                "node_type" => $node_type,
                "name" => $flow_class::get_node_name($node_type),
                "adminid" => $adminid,
                "admin_nick" => $this->cache_get_account_nick($adminid),
            ];

            \App\Helper\Utils::logger("node_type:$node_type,flowid:$flowid");

            list($node_type,$adminid )=$flow_class::get_next_node_info( $node_type, $flowid, $adminid );
            \App\Helper\Utils::logger(" 22 node_type:$node_type,flowid:$flowid");

        } while ($node_type  != -1);

        $ret_list[]=[
            "node_type" => -1,
            "name" => "结束",
            "adminid" => "",
            "admin_nick" =>"" ,
        ];

        $ret_info=\App\Helper\Utils::list_to_page_info($ret_list);
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );

        return $this->output_succ(array('data' => $ret_info));
    }
    public function set_record_server_list() {

        $record_audio_server1=$this->get_in_str_val("record_audio_server1","" );
        $id_list_str= $this->get_in_str_val("id_list");
        $id_list=\App\Helper\Utils::json_decode_as_int_array($id_list_str);
        if ( count($id_list) ==0 ) {
            return $this->output_err("还没选择例子");
        }
        foreach ($id_list as $lessonid ) {
            $this->t_lesson_info->field_update_list($lessonid,[
                "record_audio_server1" =>  $record_audio_server1,
            ]);
        }

        return $this->output_succ();
    }


    public function xmpp_del() {
        $lessonid=$this->get_in_lessonid();
        $lesson_info=$this->t_lesson_info->field_get_list($lessonid,"*");
        $courseid=$lesson_info["courseid"];
        $lesson_type=$lesson_info["lesson_type"];
        $lesson_num=$lesson_info["lesson_num"];
        $teacherid= $lesson_info["teacherid"];
        $userid= $lesson_info["userid"];
        $roomid = \App\Helper\Utils::gen_roomid_name($lesson_type,$courseid,$lesson_num);
        $ret_arr  = \App\Helper\Net::get_server_info(array($courseid));

        if (isset( $ret_arr["server_list"] ) &&  isset( $ret_arr["server_list"][0])) {
            $server_config = $ret_arr["server_list"][0];
            \App\Helper\Utils::del_room($teacherid,$roomid,$server_config);
            \App\Helper\Utils::del_room($userid,$roomid,$server_config);
            echo "succ\n";
        }else{
            echo " ERROR get_server_info\n";
        }
    }
    //

    public function tongji_fail_lesson_list_js () {
        $adminid= $this->get_in_adminid();
        list($start_time, $end_time) = $this->get_in_date_range(0,0);
        $page_info= $this->get_in_page_info();
        $ret_info=$this->t_test_lesson_subject_require->tongji_fail_lesson_list($page_info, $adminid, $start_time, $end_time);
        foreach( $ret_info["list"] as &$item) {

            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item);
            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);
            E\Eflow_status::set_item_value_str($item);

        }
        return $this->output_ajax_table ($ret_info);

    }

    public function agent_add() {
        $userid=$this->get_in_userid();
        $parentid=$this->get_in_parentid();
        $wx_openid=$this->get_in_wx_openid();
        $phone = $this->get_in_phone();
        $bankcard = $this->get_in_str_val('bankcard');
        $idcard = $this->get_in_str_val('idcard');
        $bank_address = $this->get_in_str_val('bank_address');
        $bank_account = $this->get_in_str_val('bank_account');
        $bank_phone = $this->get_in_str_val('bank_phone');
        $bank_province = $this->get_in_str_val('bank_province');
        $bank_city = $this->get_in_str_val('bank_city');
        $bank_type = $this->get_in_str_val('bank_type');
        $zfb_name = $this->get_in_str_val('zfb_name');
        $zfb_account = $this->get_in_str_val('zfb_account');
        $this->t_agent->row_insert([
            "parentid" => $parentid,
            "phone" => $phone,
            "create_time" => time(NULL),
            "wx_openid" => $wx_openid,
            "userid" => $userid,
            "bankcard"      => $bankcard,
            "idcard"        => $idcard,
            "bank_address"  => $bank_address,
            "bank_account"  => $bank_account,
            "bank_phone"    => $bank_phone,
            "bank_province" => $bank_province,
            "bank_city"     => $bank_city,
            "bank_type"     => $bank_type,
            "zfb_name"     => $zfb_name,
            "zfb_account"     => $zfb_account,
        ],false,false,true );

        return $this->output_succ();
    }
    public function agent_order_add() {
        $orderid      = $this->get_in_int_val('orderid');
        $aid      = $this->get_in_int_val('aid');
        $pid      = $this->get_in_int_val('pid');
        $p_price  = $this->get_in_int_val('p_price');
        $ppid     = $this->get_in_int_val('ppid');
        $pp_price = $this->get_in_int_val('pp_price');
        $this->t_agent_order->row_insert([
            "orderid"         => $orderid,
            "aid"         => $aid,
            "pid"         => $pid,
            "p_price"     => $p_price,
            "ppid"        => $ppid,
            "pp_price"    => $pp_price,
            "create_time" => time(NULL),
        ],false,false,true );

        return $this->output_succ();
    }
    public function agent_cash_add() {
        $aid=$this->get_in_int_val('aid');
        $cash=$this->get_in_int_val('cash');
        $type=$this->get_in_int_val('type');
        $this->t_agent_cash->row_insert([
            "aid" => $aid,
            "cash" => $cash,
            "type" => $type,
            "create_time" => time(NULL),
        ],false,false,true );

        return $this->output_succ();
    }

    public function seller_edit_log_add() {
        $adminid=$this->get_in_int_val('adminid');
        $uid=$this->get_in_int_val('uid');
        $type=$this->get_in_int_val('type');
        $old=$this->get_in_str_val('old');
        $new=$this->get_in_str_val('new');
        $this->t_seller_edit_log->row_insert([
            "adminid"     => $adminid,
            "type"        => $type,
            "uid"         => $uid,
            "old"         => $old,
            "new"         => $new,
            "create_time" => time(NULL),
        ],false,false,true );

        return $this->output_succ();
    }


    public function agent_edit() {
        $id=$this->get_in_id();
        $parentid=$this->get_in_parentid();
        $phone = $this->get_in_phone();
        $wx_openid = $this->get_in_wx_openid();
        $bankcard = $this->get_in_str_val('bankcard');
        $idcard = $this->get_in_str_val('idcard');
        $bank_address = $this->get_in_str_val('bank_address');
        $bank_account = $this->get_in_str_val('bank_account');
        $bank_phone = $this->get_in_str_val('bank_phone');
        $bank_province = $this->get_in_str_val('bank_province');
        $bank_city = $this->get_in_str_val('bank_city');
        $bank_type = $this->get_in_str_val('bank_type');
        $zfb_name = $this->get_in_str_val('zfb_name');
        $zfb_account = $this->get_in_str_val('zfb_account');

        $this->t_agent->field_update_list($id,[
            "parentid"      => $parentid,
            "phone"         => $phone,
            "wx_openid"     => $wx_openid,
            "bankcard"      => $bankcard,
            "idcard"        => $idcard,
            "bank_address"  => $bank_address,
            "bank_account"  => $bank_account,
            "bank_phone"    => $bank_phone,
            "bank_province" => $bank_province,
            "bank_city"     => $bank_city,
            "bank_type"     => $bank_type,
            "zfb_name"     => $zfb_name,
            "zfb_account"     => $zfb_account,
        ]);
        return $this->output_succ();
    }

    public function agent_order_edit() {
        $orderid=$this->get_in_int_val('orderid');
        $aid=$this->get_in_int_val('aid');
        $orderid_new=$this->get_in_int_val('orderid_new');
        $pid=$this->get_in_int_val('pid');
        $p_price=$this->get_in_int_val('p_price');
        $ppid = $this->get_in_int_val('ppid');
        $pp_price = $this->get_in_int_val('pp_price');
        $this->t_agent_order->field_update_list($orderid,[
            "orderid" => $orderid_new,
            "aid" => $aid,
            "pid" => $pid,
            "ppid" => $ppid,
            "p_price" => $p_price,
            "pp_price" => $pp_price,
        ]);

        return $this->output_succ();
    }

    public function agent_cash_edit() {
        $id=$this->get_in_int_val('id');
        $aid=$this->get_in_int_val('aid');
        $cash = $this->get_in_int_val('cash');
        $type = $this->get_in_int_val('type');

        $this->t_agent_cash->field_update_list($id,[
            "aid" => $aid,
            "cash" => $cash,
            "type" => $type,
        ]);

        return $this->output_succ();

    }

    public function agent_del(){
        $id=$this->get_in_id();

        $this->t_agent->row_delete($id);

        return $this->output_succ();
    }

    public function agent_order_del(){
        $orderid=$this->get_in_int_val('orderid');

        $this->t_agent_order->row_delete($orderid);

        return $this->output_succ();
    }

    public function agent_cash_del(){
        $id=$this->get_in_int_val('id');

        $this->t_agent_cash->row_delete($id);

        return $this->output_succ();
    }

    public function teacher_apply_add() {
        $teacher_id=$this->get_in_int_val("teacher_id",0);
        $cc_id=$this->get_in_cc_id();
        $lesson_id=$this->get_in_int_val('lesson_id', 0);
        $question_type=$this->get_in_question_type();
        $question_content=$this->get_in_question_content();
        $teacher_flag=$this->get_in_teacher_flag();
        $teacher_time=$this->get_in_teacher_time();
        $cc_flag=$this->get_in_cc_flag();
        $cc_time=$this->get_in_cc_time();

        $this->t_teacher_apply->row_insert([
            "teacher_id" => $teacher_id,
            "cc_id" => $cc_id,
            "lesson_id" => $lesson_id,
            "question_type" => $question_type,
            "question_content" => $question_content,
            "teacher_flag" => $teacher_flag,
            "teacher_time" => $teacher_time,
            "cc_flag" => $cc_flag,
            "cc_time" => $cc_time,
            "create_time" => time(NULL),
        ],false,false,true );

        return $this->output_succ();
    }

    public function teacher_apply_edit() {
        $id=$this->get_in_id();
        $cc_flag=$this->get_in_cc_flag();
        // dd($cc_fl);
        if($cc_flag){
            $this->t_teacher_apply->field_update_list($id,[
                "cc_flag" => $cc_flag,
                "cc_time" => time(null),
            ]);
        }else{
            $this->t_teacher_apply->field_update_list($id,[
                "cc_flag" => $cc_flag,
            ]);
        }

        return $this->output_succ();
    }

    public function teacher_apply_del(){
        $id=$this->get_in_id();

        $this->t_teacher_apply->row_delete($id);

        return $this->output_succ();
    }

    public function unlock_door () {
        $machine_id= $this->get_in_int_val("machine_id");
        $this->t_kaoqin_machine->send_cmd_unlock($machine_id);
        return $this->output_succ();

    }

    public function kaoqin_reboot() {
        $machine_id= $this->get_in_int_val("machine_id");
        $this->t_kaoqin_machine->send_cmd_reboot($machine_id);
        return $this->output_succ();
    }

    public function get_self_todo_info() {
        $adminid=$this->get_account_id();
        //$adminid=-1;
        list($start_time,$end_time)=$this->get_in_date_range_day(0);
        $page_info=null;
        $status_map= $this->t_todo->get_todo_status_count_info(
            $adminid,$start_time,$end_time );
        $status_info=[];
        foreach( $status_map as  $k=> $item) {
            $status_info[$k] = $item["count"];
        }

        return  $this->output_succ(["status_info"=> $status_info  ] ) ;
    }

    public function get_self_todo_list() {
        $adminid=$this->get_account_id();
        list($start_time,$end_time)=$this->get_in_date_range_day(0);
        $page_info=null;
        //$adminid=-1;
        $ret_info= $this->t_todo->get_list(
            $page_info,$adminid,-1,-1, $start_time,$end_time);
        $now=time(NULL);

        foreach( $ret_info["list"]  as &$item ) {
            $todo_type=$item["todo_type"];
            E\Etodo_type::set_item_value_str($item);
            E\Etodo_status::set_item_value_color_str($item);
            $msg_arr=\App\Helper\Utils::json_decode_as_array($item["msg"] );
            $item["line_info"]=@$msg_arr[0];
            $item["jump_url"]=@$msg_arr[1];
            $start_time=$item["start_time"];
            $end_time=$item["end_time"];
            $percent=-1;
            if (  $item["todo_status"] ==1) {
                $percent = intval(($now-$start_time)/($end_time-$start_time)*10)*10;
                if ($percent <0 ) {
                    $percent=0;
                }else if ($percent>100 ){
                    $percent=100;
                }
            }
            $item["percent"]= $percent;
        }

        return $this->output_succ(["data"=>$ret_info["list"]]);

    }

    public function todo_reset() {
        $todoid=$this->get_in_int_val("todoid");
        \App\Todo\todo_base::do_reset($todoid,true);
        return $this->output_succ();
    }

}
