<?php
namespace App\Flow;
use \App\Enums as E;
class flow_agent_money_ex_examine extends flow_base{

    static $type= E\Eflow_type::V_AGENT_MONEY_EX_EXAMINE;
    static $node_data=[
        //nodeid => next_nodeid(s) name  ,next_node_process
        0=>[ 1 , "申请"  ],
        1=>[ -1,"申请->主管审批"  ],
    ];
    static function get_self_info( $from_key_int,  $from_key_str ) {
        $t_agent_money_examine = new \App\Models\t_agent_money_ex();
        return $t_agent_money_examine->field_get_list($from_key_int ,"*");
    }

    static function get_table_data( $flowid ) {
        list($flow_info,$self_info)=static::get_info($flowid);

        //获取申请人
        $task=static::get_task_controler();
        $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);
        //获取用户电话及微信昵称
        $t_agent_money_examine = new \App\Models\t_agent_money_ex();
        $agent_info = $t_agent_money_examine->get_agent_info($self_info["agent_id"]);
        $agent_name = @$agent_info['phone'].'-'.@$agent_info['nickname'];
        return [
            ["申请人",  $post_admin_nick] ,
            ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
            ["申请金额", $self_info["money"]/100 ] ,
            ["用户姓名", $agent_name ]
        ];
    }

    static function get_line_data( $from_key_int,$from_key_str) {
        $self_info=static::get_self_info( $from_key_int,  $from_key_str );
        //获取用户电话及微信昵称
        $t_agent_money_examine = new \App\Models\t_agent_money_ex();
        $agent_info = $t_agent_money_examine->get_agent_info($self_info["agent_id"]);
        $agent_name = @$agent_info['phone'].'-'.@$agent_info['nickname'];

        return '申请给'.$agent_name.($self_info['money']/100).'元的奖励，待审批!';
    }


    static function next_node_process_0 ($flowid ,$adminid){ //
        $task=static::get_task_controler();
        if(\App\Helper\Utils::check_env_is_local())
            return $task->t_manager_info->get_id_by_account("jim");
        else
            return $task->t_manager_info->get_id_by_account("amanda");
    }

    static function next_node_process_1 ($flowid ,$adminid){ //
        return 0;
    }

}
