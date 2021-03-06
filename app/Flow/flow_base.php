<?php
namespace App\Flow;

use \App\Enums as E;
function next_node_process_end( $adminid,$flow_id ) {
    /**  @var  $t_flow_node \App\Models\t_flow_node */
    $t_flow_node= new \App\Models\t_flow_node();
}

class flow_base{


    /**
     * @return \App\Console\Tasks\TaskController
     */
    static function get_task_controler() {
        return new \App\Console\Tasks\TaskController ();
    }
    static function get_adminid_by_account($account) {
        $task=static::get_task_controler();
        return $task->t_manager_info->get_adminid_by_account($account);
    }
    static function get_node_map() {

        \App\Helper\Utils::logger("class name ". static::class);

        $vars= get_class_vars(static::class);
        if ( array_has($vars, "node_map"  )  )  {
            return static::$node_map;
        }else{
            return [];
        }
    }

    static function set_node_map () {
        $task=static::get_task_controler();
        $vars= get_class_vars(static::class);
        if ( array_has($vars, "node_map"  ) && !static::$node_map )  {
            static::$node_map=\App\Helper\Utils::json_decode_as_array( $task->t_flow_config->get_node_map(static::$type ) );
        }
    }

    static function get_node_name( $node_type ) {
        if ($node_type==-1) {
            return "结束";
        }else  if ( $node_type==0 ) {
            return "申请";
        }else  if ( $node_type==-2 ) {
            return "抄送";
        }
        if ($node_type> 10000 ){
            $node_info= static::$node_map[$node_type];
            if (isset($node_info["title"])) return $node_info["title"]  ;
            else return $node_info["name"] ;
        }else{ //旧版是
            $ret= @static::$node_data[$node_type][1];
        }
    }

    static function get_info( $flowid ) {
        $flow_info = static::get_flow_info($flowid);
        $self_info = static::get_self_info($flow_info["from_key_int"], $flow_info["from_key_str"], $flow_info["from_key2_int"] );
        return array($flow_info, $self_info);
    }

    static function get_flow_info($flowid) {
        $t_flow = new \App\Models\t_flow();
        return $t_flow->field_get_list($flowid,"*");
    }
    static function get_admin_account_by_env( $release_account, $test_account ) {
        $flag=\App\Helper\Utils::check_env_is_release() ;
        return $flag? $release_account: $test_account;
    }

    /*
    static function get_self_info( $from_key_int,  $from_key_str ) {
        return null;
    }
    */

    // return [ $node_type, $adminid ]
    static function get_next_node_info_by_nodeid($nodeid) {
        /**  @var  $t_flow_node \App\Models\t_flow_node */
        $t_flow_node= new \App\Models\t_flow_node();
        $ret_item=$t_flow_node->field_get_list($nodeid,"flowid,node_type,adminid" );
        $node_type=$ret_item["node_type"] ;
        $flowid=$ret_item["flowid"] ;
        $adminid=$ret_item["adminid"] ;

        return static::get_next_node_info( $node_type, $flowid, $adminid  );
    }

    // return [ $node_type, $adminid ]
    static function get_next_node_info($node_type, $flowid, $adminid ) {

        $node_def_item=static::$node_data[$node_type];
        $next_node_process_fun="next_node_process_$node_type";
        $ret=static::$next_node_process_fun( $flowid,  $adminid );
        if(!is_array($ret) ) { //next_adminid
            $ret=[ $node_def_item[0], $ret ];
        }
        if (count($ret)==2) {
            $ret[]=0; //audo pass
        }
        if (!( $ret[1] >0) )  { // account
            $ret[1]= static::get_adminid_by_account( $ret[1]);
        }
        \App\Helper\Utils::logger( "get_next_node_info: ". json_encode($ret)  );

        return $ret;
    }

    // return [ $node_type, $adminid, $auto_pass ]
    static function get_next_node_info_new($node_type, $flowid, $adminid ) {
        $task= static::get_task_controler();
        list($flow_info,$self_info)=static::get_info($flowid);

        $flow_type= static::$type;
        return $task->t_flow_config->get_next_node($flow_type,$node_type, $flow_info, $self_info , $adminid );

    }




    static function call_do_succ_end($flowid) {
        $flow_info = static::get_flow_info($flowid);
        $self_info = static::get_self_info($flow_info["from_key_int"], $flow_info["from_key_str"], $flow_info["from_key2_int"] );

        return  static::do_succ_end( $flow_info, $self_info );

    }

    static function do_succ_end( $flow_info, $self_info ) {

    }

    static function do_flow_pass($nodeid ,$flow_check_flag,$check_msg) {
        $task=static::get_task_controler();

        $node_info  = $task->t_flow_node->field_get_list($nodeid,"*");
        if ($node_info["flow_check_flag"] !=0 ) {
            return false;
        }

        $flowid     = $node_info["flowid"] ;
        $flow_info  = $task->t_flow->field_get_list($flowid,"*");
        $flow_type  = $flow_info["flow_type"];
        $flow_class = \App\Flow\flow::get_flow_class($flow_type);

        list($next_node_type,$next_adminid,$auto_pass_flag)=$flow_class::get_next_node_info_by_nodeid($nodeid);
        if ($next_node_type==-1) { //END
            $task->t_flow_node->set_check_info($nodeid,$flow_check_flag,-1, $check_msg);
            $task->t_flow->set_flow_status($flowid,  E\Eflow_status::V_PASS );
            $msg= $flow_class::get_line_data( $flow_info["from_key_int"] ,$flow_info["from_key_str"],  $flow_info["from_key2_int"] );

            $task->t_manager_info->send_wx_todo_msg_by_adminid($flow_info["post_adminid"],"审批系统","审批完成:".E\Eflow_type::get_desc($flow_type),$msg,"");

            //得到抄送列表
            $node_list=$task->t_flow_node->get_node_list($flowid );
            $node_type_list=[ -1 ];
            foreach( $node_list["list"] as  $node ) {
                $node_type_list[]=$node["node_type"];
            }
            \App\Helper\Utils::logger("flow_class=$flow_class");

            $notify_adminid_list=$flow_class::get_notify_user_list($node_type_list);

            //抄送
            $notify_node_type=-2;
            $auto_pass_flag = false;
            foreach($notify_adminid_list as $notify_adminid) {
                $next_nodeid=$task->t_flow_node->add_node($notify_node_type,$flowid,$notify_adminid,0,0,"",0 ,$auto_pass_flag);
            }

            $flow_class::call_do_succ_end($flowid);
        }else{
            if (!$next_adminid) {
                return  false;
            }
            $next_nodeid=$task->t_flow_node->add_node($next_node_type,$flowid,$next_adminid,0,0,"",0 ,$auto_pass_flag);
            $task->t_flow_node->set_check_info($nodeid,$flow_check_flag,$next_nodeid,$check_msg);
            if ($auto_pass_flag) { //自动通过
                static::do_flow_pass( $next_nodeid,E\Eflow_check_flag::V_AUDO_PASS,$check_msg);
            }
        }
        return true;
    }

    static function check_post_admin_account_type( $adminid, $check_role ) {
        $t_manager_info =  new \App\Models\t_manager_info();
        $account_role = $t_manager_info->get_account_role($adminid);
        return $account_role == $check_role ;
    }
    static function check_admin_role ( $args,  $flow_info, $self_info , $adminid ) {
        $t_manager_info =  new \App\Models\t_manager_info();
        $post_adminid=$flow_info["post_adminid"];
        $account_role = $t_manager_info->get_account_role($post_adminid);

        /*
        E\Eflow_function::S_CHECK_ADMIN_ROLE   => [
            "1" => "助教",
            "2" => "销售",
        ],
        ];
        */
        if ($account_role == E\Eaccount_role::V_2){
            return 2;
        }else{
            return 1;
        }
    }
    static function check_is_master ( $args,  $flow_info, $self_info , $adminid ) {
        $t_manager_info =  new \App\Models\t_manager_info();
        $t_admin_group_user =  new \App\Models\t_admin_group_user();
        $t_admin_group_name =  new \App\Models\t_admin_group_name();
        $post_adminid=$flow_info["post_adminid"];
        $groupid=$t_admin_group_user->get_groupid_value($post_adminid);
        $item1=$t_admin_group_name->field_get_list($groupid, "master_adminid,up_groupid");

        if ( $post_adminid==$item1["master_adminid"]){
            return 1;
        }else{
            return 0;
        }
    }


    static function do_function( $flow_function, $args , $flow_type,$node_type, $flow_info, $self_info , $adminid   ) {
        $flow_class  = \App\Flow\flow::get_flow_class($flow_type);
        $function_name=E\Eflow_function::v2s($flow_function );
        $switch_value= $flow_class::$function_name( $args , $flow_info, $self_info , $adminid );
        return $switch_value;
    }

    //返回函数参数配置 需要继承
    static function get_function_config() {
        return  [
            E\Eflow_function::V_CHECK_ADMIN_ROLE => [
                "arg_config" => [
                ],
                "return_config"  => [
                    "1" => "助教",
                    "2" => "销售",
                ]
            ],
            E\Eflow_function::V_CHECK_IS_MASTER => [
                "arg_config" => [
                ],
                "return_config"  => [
                    "1" => "是",
                    "0" => "否",
                ]
            ]          
        ];
        /*
        return [
            "arg_config" => [
                "check_day_count" => [ "desc"=> "指定天数", "type"=>"integer" ],
            ],
            "return_config"  => [
                "1" => "小于3天",
                "2" => "大于等于3天",
            ]
        ];
        */

    }

    static public function  get_notify_user_list( $flow_node_list){
        $user_map=[];
        $node_map=static::get_node_map();
        \App\Helper\Utils::logger("XX:".json_encode($flow_node_list ));

        foreach( $flow_node_list as $id ) {
            $next_node_list= @$node_map[$id]["next_node_list"];
            if ($next_node_list) {
                foreach (@$node_map[$id]["next_node_list"] as $node_info ) {
                    $next_node_id=$node_info["id"];
                    if ($node_map[$next_node_id]["type"] =="notify") { //
                        $user_map[ $node_map[$next_node_id]["adminid"]]=true;
                    }
                }
            }
        }
        return array_keys($user_map);
    }

}
