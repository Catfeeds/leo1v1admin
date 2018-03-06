<?php
namespace App\Flow;
use \App\Enums as E;
class flow_student_change_grade extends flow_base{

    static $type= E\Eflow_type::V_STUDENT_CHANGE_GRADE;
    static $node_map=null; // get_flow_class 时同步设置
    static $node_data=[];


    static function get_self_info( $from_key_int,  $from_key_str ) {
        $task= static::get_task_controler();
        \App\Helper\Utils::logger(222222);

        return $task->t_student_info->field_get_list($from_key_int ,"*");
    }

    static function get_table_data( $flowid ) {
        // list($flow_info,$self_info)=static::get_info($flowid);

        // $hour_count=$self_info["hour_count"];
        // $day_count=floor($hour_count/8);
        // $hour_count_tmp=$self_info["hour_count"]%8;
        // $hour_count_str=" $day_count 天 $hour_count_tmp 小时 ";

        // $task=static::get_task_controler();
        // $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);

        // return [
        //     ["申请人",  $post_admin_nick] ,
        //     ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
        //     ["开始时间", date("Y-m-d H",$self_info["start_time"] ) ] ,
        //     ["结束时间", date("Y-m-d H",$self_info["end_time"] ) ] ,
        //     ["请假时长",  $hour_count_str ] ,
        //     ["原因",  $self_info["msg"] ] ,
        // ];
        return [];
    }

    static function get_line_data( $from_key_int,$from_key_str) {
        $task= static::get_task_controler();
        // $self_info=static::get_self_info( $from_key_int,  $from_key_str );
        // $hour_count=$self_info["hour_count"];
        // $day_count=floor($hour_count/8);
        // $hour_count_tmp=$self_info["hour_count"]%8;
        // $hour_count_str=" $day_count 天 $hour_count_tmp 小时 ";

        // return date("Y-m-d H",$self_info["start_time"])."~". date("Y-m-d H",$self_info["end_time"] )."($hour_count_str)";
        $nick = $task->t_student_info->get_nick($from_key_int);
        $arr = json_decode($from_key_str,true);
        $msg = "学生:".$nick.", 原年级:".E\Egrade::get_desc($arr["old"]).", 目标年级:".E\Egrade::get_desc($arr["new"]);

        return $msg;
    }


    //使用新版.
    static function get_next_node_info($node_type, $flowid, $adminid ) {
        return static::get_next_node_info_new($node_type, $flowid, $adminid);
    }

    static function do_succ_end( $flow_info, $self_info ) {
        $task=static::get_task_controler();
        $lesson_confirm_start_time=\App\Helper\Config::get_lesson_confirm_start_time();      

        if ( $start_time < $lesson_confirm_start_time && $start_time>0 ) {
            $start_time = $lesson_confirm_start_time;
        }

        $db_grade = $this->t_student_info->get_grade($userid);
        $this->t_student_info->field_update_list($userid,[
            "grade"  => $grade,
        ]);

        // 记录操作日志
        $this->t_user_log->add_data('修改年级为'.E\Egrade::get_desc($grade), $userid);

        //设置时间再重置课程年级,避免影响老师工资
        if($start_time>0){
            $this->t_lesson_info->update_grade_by_userid($userid,$start_time,$grade);
        }
        $this->t_revisit_info->sys_log( $this->get_account(),
                                        $userid,
                                        "年级 [". E\Egrade::get_desc($db_grade) ."]=>[". E\Egrade::get_desc($grade) ."]"
        );

        //$post_admin_nick=$self_info["sys_operator"];
       
       
    }



    
}
