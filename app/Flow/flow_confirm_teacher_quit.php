<?php
namespace App\Flow;
use \App\Enums as E;
class flow_confirm_teacher_quit extends flow_base{

    static $type= E\Eflow_type::V_CONFIRM_TEACHER_QUIT;
    static $node_data=[
        //nodeid => next_nodeid(s) name  ,next_node_process
        0=>[ 1 , "申请"  ],
        1=>[ -1,"申请->审批人"  ],
    ];
    static function get_self_info( $from_key_int,  $from_key_str ) {
        $task=static::get_task_controler();
        $teacherid= $from_key_int;
        return $task->t_teacher_info->field_get_list($teacherid,"*") ;
    }

    static function get_table_data( $flowid ) {
        list($flow_info,$self_info)=static::get_info($flowid);

        $task=static::get_task_controler();
        $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);
        $tea_nick= $task->cache_get_teacher_nick($self_info["teacherid"]);

        return [
            ["申请人",  $post_admin_nick ] ,
            ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
            ["申请理由",  $flow_info["post_msg"]] ,
            ["老师",  $tea_nick ] ,
        ];
    }

    static function get_line_data( $from_key_int,$from_key_str) {
        $self_info=static::get_self_info( $from_key_int,  $from_key_str );

        $task=static::get_task_controler();
        //$post_admin_nick=$self_info["sys_operator"];
        $tea_nick= $task->cache_get_teacher_nick($self_info["teacherid"]);
        return   $tea_nick."离职已审核通过";

    }


    static function next_node_process_0 ($flowid ,$adminid){ //
        $task=static::get_task_controler();
        $master_adminid_list = $this->t_admin_main_group_name->get_maste_admin_name(3,"教务排课");        
        $master_adminid = $master_adminid_list["master_adminid"];

        return $master_adminid;
    }

    static function next_node_process_1 ($flowid ,$adminid){ //
        //END
        list($flow_info,$self_info)=static::get_info($flowid);
        $teacherid = $self_info["teacherid"];

        $quit_info = $flow_info["post_msg"];
        $task=static::get_task_controler();
        $quit_set_adminid = $task->get_account_id();

        $task->t_teacher_info->field_update_list($teacherid,[
            "is_quit"=>1,
            "quit_time"=>time(),
            "quit_info"=>$quit_info,
            "quit_set_adminid"=>$quit_set_adminid
        ]);
        return 0;
    }


}
