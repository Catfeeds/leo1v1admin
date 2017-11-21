<?php
namespace App\Flow;
use \App\Enums as E;
class flow_qingjia extends flow_base{

    static $type= E\Eflow_type::V_QINGJIA;
    static $node_data=[
        //nodeid => next_nodeid(s) name  ,next_node_process
        0=>[ 1 , "申请"  ],
        1=>[ [3,5],"申请->主管审批"  ],
        3=>[ -1 ,"主管->财务审批" ],
        5=>[ 6,"主管->校长审批"  ],
        6=>[ -1,"校长->财务审批"  ],
    ];

    static function get_check_node_function_list() {

    }

    static function get_self_info( $from_key_int,  $from_key_str ) {
        $t_qingjia = new \App\Models\t_qingjia();
        return $t_qingjia->field_get_list($from_key_int ,"*");
    }

    static function get_table_data( $flowid ) {
        list($flow_info,$self_info)=static::get_info($flowid);

        $hour_count=$self_info["hour_count"];
        $day_count=floor($hour_count/8);
        $hour_count_tmp=$self_info["hour_count"]%8;
        $hour_count_str=" $day_count 天 $hour_count_tmp 小时 ";

        $task=static::get_task_controler();
        $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);

        return [
            ["申请人",  $post_admin_nick] ,
            ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
            ["开始时间", date("Y-m-d H",$self_info["start_time"] ) ] ,
            ["结束时间", date("Y-m-d H",$self_info["end_time"] ) ] ,
            ["请假时长",  $hour_count_str ] ,
            ["原因",  $self_info["msg"] ] ,
        ];
    }

    static function get_line_data( $from_key_int,$from_key_str) {
        $self_info=static::get_self_info( $from_key_int,  $from_key_str );
        $hour_count=$self_info["hour_count"];
        $day_count=floor($hour_count/8);
        $hour_count_tmp=$self_info["hour_count"]%8;
        $hour_count_str=" $day_count 天 $hour_count_tmp 小时 ";

        return date("Y-m-d H",$self_info["start_time"])."~". date("Y-m-d H",$self_info["end_time"] )."($hour_count_str)";
    }


    static function next_node_process_0 ($flowid ,$adminid){ //
        $t_manager_info=  new \App\Models\t_manager_info();
        return $t_manager_info->get_up_adminid($adminid);
    }

    static function next_node_process_1 ($flowid ,$adminid){ //
        list($flow_info,$self_info)=static::get_info($flowid);
        if ($self_info["hour_count"]>=24) {
            //Cofing
            return [5,99];
        }else{
            return [3,99];
        }
    }

    static function next_node_process_3 ($flowid ,$adminid){ //
        return 0;
    }

    static function next_node_process_5 ($flowid, $adminid){ //
        //Config
        return 99;
    }
    static function next_node_process_6 ($flowid, $adminid){ //
        return 0;
    }

}
