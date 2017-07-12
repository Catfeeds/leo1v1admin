<?php
namespace App\Flow;
use \App\Enums as E;
class flow_seller_test_lesson_without_test_paper extends flow_base{

    static $type= E\Eflow_type::V_SELLER_ORDER_REQUIRE;
    static $node_data=[
        //nodeid => next_nodeid(s) name  ,next_node_process 
        0=>[ 1 , "申请"  ],
        1=>[ -1,"申请->主管审批"  ],
    ];
    static function get_self_info( $from_key_int,  $from_key_str ) {
        $t_test_lesson_subject = new \App\Models\t_test_lesson_subject();
        $ret= $t_test_lesson_subject->field_get_list($from_key_int ,"*");

        $t_seller_student_new  = new \App\Models\t_seller_student_new();
        $ret_2 =$t_seller_student_new->field_get_list($ret["userid"],"*");
        
        return array_merge($ret,$ret_2);
    }

    static function get_table_data( $flowid ) {
        list($flow_info,$self_info)=static::get_info($flowid);

        $task=static::get_task_controler();
        $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $subject_str= E\Esubject::get_desc($self_info["subject"]);

        return [
            ["申请人",  $post_admin_nick ] ,
            ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
            ["学生",  $user_nick ] ,
            ["科目",  $subject_str ] ,
        ];
    }

    static function get_line_data( $from_key_int,$from_key_str) {
        $self_info=static::get_self_info( $from_key_int,  $from_key_str );

        $task=static::get_task_controler();
        //$post_admin_nick=$self_info["sys_operator"];
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $subject_str=E\Egrade::get_desc($self_info["subject"] ) ;
        return   "$user_nick-$subject_str";

    }


    static function next_node_process_0 ($flowid ,$adminid){ //

        $t=  new \App\Models\t_admin_group_user();
        $item=$t->get_up_level_users($adminid);
        return $item["master_adminid1"];
        //return $t_manager_info->get_up_adminid($adminid);
    }

    static function next_node_process_1 ($flowid ,$adminid){ //
        return 0;
    }


}
