<?php
namespace App\Flow;
use \App\Enums as E;
class flow_order_exchange extends flow_base{


    static $type= E\Eflow_type::V_ORDER_EXCHANGE;
    static $node_map= [];
    static $node_data=[
        //nodeid => next_nodeid(s) name  ,next_node_process
        0=>[ 1 , "申请"  ],
        1=>[ 2,"申请->主管审批"  ],
        2=>[ 3,"主管审批->[部]主审批" ],
        3=>[ -1 ,"财务审批" ],
    ];
    static function get_self_info( $from_key_int,  $from_key_str ) {
        $t_order_info  = new \App\Models\t_order_info();
        return $t_order_info->field_get_list($from_key_int ,"*");
    }

    static function get_table_data( $flowid ) {
        list($flow_info,$self_info)=static::get_info($flowid);

        $task=static::get_task_controler();
        $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $contract_type=$self_info["contract_type"];
        $lesson_total=$self_info["lesson_total"]*  $self_info["default_lesson_count"] /100;
        $parent_order_id=$self_info["parent_order_id"];
        $orderid=$self_info["orderid"];
        $t_order_info  = new \App\Models\t_order_info();
        $parent_order_info=$t_order_info->field_get_list($parent_order_id,"*");
        $parent_order_nick= $task->cache_get_student_nick($parent_order_info["userid"]);

        $parent_lesson_total=$parent_order_info["lesson_total"]*  $parent_order_info["default_lesson_count"] /100;

        return [
            ["申请人",  $post_admin_nick ] ,
            ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
            ["原合同",""],
            ["学生",   $parent_order_nick] ,
            ["课程类型",  E\Econtract_type::get_desc( $parent_order_info["contract_type"] ) ] ,
            ["年级", E\Egrade::get_desc($parent_order_info["grade"] )  ] ,
            ["转出课时数", $self_info["from_parent_order_lesson_count"]/100  ] ,
            ["转赠给",""],
            ["学生",  $user_nick ] ,
            ["课程类型",  E\Econtract_type::get_desc( $contract_type ) ] ,
            ["年级", E\Egrade::get_desc($self_info["grade"] )  ] ,
            ["课时数", $lesson_total  ] ,

            ["说明", $self_info["discount_reason"]  ] ,
            ["关联课程",  "<a href=\"/user_manage_new/get_relation_order_list?orderid=$orderid&contract_type=$contract_type\" target=\"_blank\" >关联课程</a>" ] ,
            ["-","-"],
            ["销售说明", $flow_info["post_msg"]  ] ,
        ];

    }

    static function get_line_data( $from_key_int,$from_key_str) {

        $self_info=static::get_self_info( $from_key_int,$from_key_str );

        $task=static::get_task_controler();
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $contract_type=$self_info["contract_type"];
        $lesson_total=$self_info["lesson_total"]*  $self_info["default_lesson_count"] /100;
        $parent_order_id=$self_info["parent_order_id"];
        $orderid=$self_info["orderid"];
        $t_order_info  = new \App\Models\t_order_info();
        $parent_order_info=$t_order_info->field_get_list($parent_order_id,"*");
        $parent_order_nick= $task->cache_get_student_nick($parent_order_info["userid"]);

        $parent_lesson_total=$parent_order_info["lesson_total"]*  $parent_order_info["default_lesson_count"] /100;

        $from_parent_order_lesson_count= $self_info["from_parent_order_lesson_count"]/100;
        return "$parent_order_nick: $from_parent_order_lesson_count 课时 =>  $user_nick: $lesson_total  ";
    }


    static function next_node_process_0 ($flowid ,$adminid){ //

        $t=  new \App\Models\t_admin_group_user();
        $item=$t->get_up_level_users($adminid);
        return $item["master_adminid1"];
        //return $t_manager_info->get_up_adminid($adminid);
    }

    static function next_node_process_1 ($flowid ,$adminid){ //
        $t=  new \App\Models\t_admin_group_user();
        $item=$t->get_up_level_users($adminid);
        \App\Helper\Utils::logger( "master_adminid2:". $item["master_adminid2"] );

        return $item["master_adminid2"];
    }


    static function next_node_process_2 ($flowid, $adminid){ //
        return "echo";
        /*
        list($flow_info,$self_info)=static::get_info($flowid);
        $contract_type=$self_info["contract_type"];
        $lesson_total=$self_info["lesson_total"]*  $self_info["default_lesson_count"] /100;
        if (preg_match("/Y[0-9][0-9][0-9][0-9][0-9]/", $self_info["discount_reason"])) {
            return [4, 281]; //amanda
        }
        if ($contract_type==E\Econtract_type::V_0 &&  $lesson_total <90 ) { //30次课

            if (($self_info["promotion_present_lesson"] !=$self_info["promotion_spec_present_lesson"]) ||
                ($self_info["promotion_discount_price"] !=$self_info["promotion_spec_discount"]) 
            ) {
                return [3,282];
            }

            return [-1, 0 ];
        }else{
            return [3,282];
        }
        */
    }

    static function next_node_process_3 ($flowid, $adminid){ //
        return 0;
    }
    static function next_node_process_4 ($flowid, $adminid){ //
        return 282;
    }
    static function next_node_process_5 ($flowid, $adminid){ //
        return 0;
    }

    static function get_next_node_info($node_type, $flowid, $adminid ) {
        return static::get_next_node_info_new($node_type, $flowid, $adminid);
    }


    static function do_succ_end( $flow_info, $self_info ) {
        $orderid=$self_info["orderid"];
        $t_order_info  = new \App\Models\t_order_info();
        $t_order_info->set_order_payed($orderid, 0, 0);
    }



    //使用新版.
    static function get_next_node_info($node_type, $flowid, $adminid ) {
        return static::get_next_node_info_new($node_type, $flowid, $adminid);
    }



}
