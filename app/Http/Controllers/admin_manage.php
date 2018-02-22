<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Flow\flow_function_config as FFC;

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


        #排序信息
        list($order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str([],"userid desc");

        $machine_id = $this->get_in_int_val("machine_id",-1);
        $adminid    = $this->get_in_adminid(-1);
        $auth_flag = $this->get_in_el_boolean(-1,"auth_flag");
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

    public function get_kaoqin_list_js()  {
        $page_info=$this->get_in_page_info();
        $machine_id= $this->get_in_int_val("machine_id",-1);

        $ret_list=$this->t_kaoqin_machine->get_list_for_select($page_info , $machine_id);
        $check_time=time(NULL)-60;

        foreach($ret_list["list"] as &$item) {
            //$item["status_class"]= ($item["last_active_time"] <$check_time)?"danger":"";
            //\App\Helper\Utils::unixtime2date_for_item($item,"last_active_time");
        }
        return $this->output_ajax_table($ret_list);
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
        $adminid= $this->get_in_adminid(-1);
        $page_info= $this->get_in_page_info();
        if (!($groupid>0) ) {
            return $this->error_view(["没有选择群邮箱"]);
        }
        $ret_info= $this->t_mail_group_user_list->get_list( $page_info , $groupid, $adminid);
        foreach ($ret_info["list"] as &$item ) {
            E\Eboolean::set_item_value_color_str($item,"email_create_flag");
            E\Eboolean::set_item_value_color_str($item,"create_flag");
        }
        $title=$this->t_mail_group_name->get_title($groupid);
        $email=$this->t_mail_group_name->get_email($groupid);
        return  $this->pageView(__METHOD__,$ret_info, [
            "title" => $title,
            "email" => $email,
        ]);
    }
    public function xmpp_server_list () {
        $page_info= $this->get_in_page_info();
        $ret_info=$this->t_xmpp_server_config->get_list($page_info);
        foreach ($ret_info["list"] as &$item ) {

        }
        return $this->pageView(__METHOD__,$ret_info);
    }
    public function web_page_info()   {
        list($start_time,$end_time ) = $this->get_in_date_range(-180,1);
        $del_flag =$this->get_in_e_boolean( 0, "del_flag");
        $page_info= $this->get_in_page_info();
        $ret_info=$this->t_web_page_info->get_list( $page_info, $start_time,$end_time, $del_flag );
        foreach ($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            $this->cache_set_item_account_nick($item,"add_adminid","add_adminid_nick");
            E\Eboolean::set_item_value_str($item,"del_flag");
        }

        return $this->pageView(__METHOD__,$ret_info);
    }
    public  function  web_page_share ( ) {
        $web_page_id= $this->get_in_int_val("web_page_id");
        $web_page_info= $this->t_web_page_info->field_get_list($web_page_id,"*");
        $page_info= $this->get_in_page_info();
        $adminid               = $this->get_in_int_val('uid',-1);
        $account_role      = $this->get_in_e_account_role(E\Eaccount_role::V_2 );

        $user_info="";

        $has_question_user=false;
        $creater_adminid =-1;
        $del_flag =0;
        $cardid=-1;
        $tquin=-1;
        $day_new_user_flag=-1;
        $seller_level=-1;
        $uid=-1;
        $fulltime_teacher_type=-1;
        $call_phone_type=-1;

        $ret_info = $this->t_manager_info->get_all_manager( $page_info,$uid,$user_info,$has_question_user, $creater_adminid,$account_role,$del_flag,$cardid,$tquin,$day_new_user_flag,$seller_level,$adminid,$fulltime_teacher_type,$call_phone_type);

        foreach($ret_info['list'] as &$item){
            E\Eaccount_role::set_item_value_str($item);
            E\Eseller_level::set_item_value_str($item);
            E\Edepartment::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"become_full_member_flag");
        }
        $this->set_filed_for_js("web_page_title",  $web_page_info["title"]  );
        $this->set_filed_for_js("web_page_url",  $web_page_info["url"]  );

        return $this->pageView(__METHOD__,$ret_info, [
            "web_page_info" =>$web_page_info
        ]);

    }

    public  function  web_page_admin_info ( ) {
        $web_page_id= $this->get_in_int_val("web_page_id");
        $ret_info=$this->t_web_page_trace_log->get_admin_info($web_page_id);

        foreach ($ret_info["list"] as &$item  ) {
            $this->cache_set_item_account_nick($item,"from_adminid", "from_adminid_nick" );
        }

        return $this->pageView(__METHOD__,$ret_info);

    }
    public function web_page_log () {
        $web_page_id= $this->get_in_int_val("web_page_id");
        $page_info= $this->get_in_page_info();
        $from_adminid= $this->get_in_int_val("from_adminid",-1);

        $ret_info=$this->t_web_page_trace_log->get_admin_info( $page_info,$web_page_id,$from_adminid);

        foreach ($ret_info["list"] as &$item  ) {
            $this->cache_set_item_account_nick($item,"from_adminid", "from_adminid_nick" );
            \App\Helper\Utils::unixtime2date_for_item($item,"log_time");
            $item["ip"] = long2ip($item["ip"]);
        }
        return $this->pageView(__METHOD__,$ret_info);

    }
    public function flow_show_map() {
        $flow_type= $this->get_in_e_flow_type();
        $json_data=@json_decode( $this->t_flow_config->get_node_map($flow_type),true);
        $json_data1=@json_decode( $this->t_flow_config->get_json_data($flow_type),true);
        dd($json_data,$json_data1);
    }

    public function flow_edit() {
        $flow_type= $this->get_in_e_flow_type();
        $json_data=@json_decode( $this->t_flow_config->get_json_data($flow_type),true);
        if (!$json_data) {
            $json_data=[];
        }
        $flow_function_list=[];
        if ($flow_type>0) {
            $flow_class=\App\Flow\flow::get_flow_class($flow_type);
            if ($flow_class) {
                $func_arr=get_class_methods($flow_class );
                foreach( E\Eflow_function::$v2s_map as  $flow_function => $func_name ){
                    if (in_array( $func_name,  $func_arr )) {
                        $flow_function_list[]= $flow_function;
                    }
                }
            }
        }

        return $this->pageOutJson(__METHOD__, null , [
            "json_data"=>$json_data,
            "flow_function_list"=> $flow_function_list,
        ]);

    }

    public function flow_save() {
        $flow_type= $this->get_in_e_flow_type();
        $json_data= $this->get_in_str_val("json_data");
        $node_map=$this->t_flow_config->gen_node_map( \App\Helper\Utils::json_decode_as_array( $json_data ) );
        if ($flow_type >0) {
            $this->t_flow_config->row_insert([
                "flow_type" => $flow_type,
                "json_data" =>$json_data,
                "node_map" =>json_encode($node_map),
            ], true);

        }
        return $this->output_succ();
    }

    public function  job_list() {
        $page_info= $this->get_in_page_info();

        $query_text=$this->get_in_query_text();

        $ret_info=$this->t_jobs->get_list($page_info,$query_text);
        foreach( $ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item, "created_at");
            \App\Helper\Utils::unixtime2date_for_item($item, "available_at");
        }
        return $this->pageView(__METHOD__,$ret_info);
        // return $this->pageOutJson(__METHOD__, $ret_info);
    }
    public function job_del_list() {
        $id_list=$this->get_in_int_list("id_list");
        foreach ($id_list as $id) {
            $this->t_jobs->row_delete($id);
        }
        return $this->output_succ() ;
    }


    public  function  web_page_new ( ) {
        $web_page_id= $this->get_in_int_val("web_page_id");
        list($start_time, $end_time  ) =$this->get_in_date_range_week(0);
        $ret_info=$this->t_web_page_trace_log->get_web_page($web_page_id,$start_time,$end_time);
        $all_list=$this->t_manager_info->get_admin_member_list(-1,-1,$sales_assistant_flag=1);
        // dd($ret_info);
        $user_map=[];

        foreach ($ret_info["list"] as $k => &$item){
            $item['share_count'] = $item['share_count'] - $item['has_zero'];
            $user_map[$item['adminid']]=true;
            if(!$item['group_name']){
                $item['group_name'] = '未定义';
            }
            if(!$item['up_group_name']){
                $item['up_group_name'] = '未定义';
            }
            $ret_info["list"][$k]['is_share'] = 0;
            if($item['share_count'] > 0){
                $ret_info["list"][$k]['is_share'] = 1;
            }

        }
        // dd($all_list['list']);
        foreach($all_list['list'] as $k => &$item){
            if(!@$user_map[$item['adminid']]){
                if(!$item['group_name']){
                    $item['group_name'] = '未定义';
                }
                if(!$item['up_group_name']){
                    $item['up_group_name'] = '未定义';
}
                $all_list["list"][$k]['is_share'] = 0;
                $ret_info['list'][] = $item;
            }
        }

        $ret_info=\App\Helper\Common::gen_admin_member_data($ret_info["list"],['account_role'],0, strtotime( date("Y-m-01" )   ));

        foreach( $ret_info as $k => &$item ) {
            E\Emain_type::set_item_value_str($item);
        }
        // dd($ret_info);
        return $this->pageView(__METHOD__,
                               \App\Helper\Utils::list_to_page_info($ret_info),
                               [
                                   "data_ex_list" => $ret_info,
                                   "_publish_version" => 201712010945
                               ]);

    }


    //@desn:获取审批分支配置项
    public function get_flow_branch_switch_value(){
        $flow_type  = $this->get_in_e_flow_type();
        $flow_function = $this->get_in_e_flow_function();
        $flow_class=\App\Flow\flow::get_flow_class($flow_type);
        if ($flow_class ){
            $config=$flow_class::get_function_config();
            $base_conig= \App\Flow\flow_base::get_function_config();
            $config= $base_conig+ $config;
            return $this->output_succ($config[$flow_function] );
        }else{
            return $this->output_err("审批配置有误.");
        }
    }


}