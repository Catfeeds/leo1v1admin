<?php
namespace App\Flow;
use \App\Enums as E;
class flow_recheck_lesson_sucess extends flow_base{

    static $type= E\Eflow_type::V_SELLER_RECHECK_LESSON_SUCESS;
    static $node_data=[
        //nodeid => next_nodeid(s) name  ,next_node_process
        0=>[ 1 , "申请"  ],
        1=>[ -1,"申请->审批人"  ],
    ];
    static function get_self_info( $from_key_int,  $from_key_str ) {
        $task=static::get_task_controler();
        $lessonid= $from_key_int;
        return $task->t_lesson_info->field_get_list($lessonid,"*") ;
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
            ["课程时间" ,  \App\Helper\Utils::fmt_lesson_time($self_info["lesson_start"] ,$self_info["lesson_end"] ) ],
            ["系统判定" , E\Eset_boolean::get_desc( $self_info["lesson_user_online_status"] ) ],
            ["课程" ,  "<a href=\"/tea_manage/lesson_list?lessonid={$self_info["lessonid"]}\" target=\"_blank\"> 课程详情</a>" ],


        ];
    }

    static function get_line_data( $from_key_int,$from_key_str) {
        $self_info=static::get_self_info( $from_key_int,  $from_key_str );

        $task=static::get_task_controler();
        //$post_admin_nick=$self_info["sys_operator"];
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $subject_str=E\Esubject::get_desc($self_info["subject"] ) ;
        return   "$user_nick-$subject_str-". \App\Helper\Utils::fmt_lesson_time($self_info["lesson_start"] ,$self_info["lesson_end"]). "-系统判定:" . E\Eset_boolean::get_desc( $self_info["lesson_user_online_status"] ) ;

    }


    static function next_node_process_0 ($flowid ,$adminid){ //
        $task=static::get_task_controler();
        return $task->t_manager_info->get_id_by_account("james");
    }

    static function next_node_process_1 ($flowid ,$adminid){ //
        //END

        return 0;
    }


}
