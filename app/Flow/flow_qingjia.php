<?php
namespace App\Flow;
use \App\Enums as E;
class flow_qingjia extends flow_base{

    static $type= E\Eflow_type::V_QINGJIA;

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



}
