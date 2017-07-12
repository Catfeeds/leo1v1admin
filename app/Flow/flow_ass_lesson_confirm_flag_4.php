<?php
namespace App\Flow;
use \App\Enums as E;

class flow_ass_lesson_confirm_flag_4   extends flow_base{


    static $type= E\Eflow_type::V_ASS_ORDER_REFUND ;
    static $node_data=[
        //nodeid => next_nodeid(s) name  ,next_node_process
        0=>[ 1 , "无效课程,学生不扣，付老师工资"  ],
        1=>[ 2," 无效课程,学生不扣，付老师工资>主管审批"  ],
        2=>[ -1,"主管审批->[部]主审批" ],
    ];
    static function get_self_info( $from_key_int,  $from_key_str, $from_key2_int   ) {
        $t_lesson_info= new \App\Models\t_lesson_info();
        return $t_lesson_info->field_get_list($from_key_int ,"*");
    }

    static function get_table_data( $flowid ) {
        list($flow_info,$self_info)=static::get_info($flowid);

        $task=static::get_task_controler();
        $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $encode_lessonid= \App\Helper\Utils::encode_str($self_info["lessonid"]) ;
        return [
            ["申请人",  $post_admin_nick ] ,
            ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
            ["课程类型",  E\Econtract_type::get_desc( $self_info["lesson_type"])  ] ,
            ["学生",  $user_nick] ,
            ["老师",  $task->cache_get_teacher_nick( $self_info["teacherid"] ) ] ,
            ["上课时间", \App\Helper\Utils::fmt_lesson_time ( $self_info["lesson_start"] ,$self_info["lesson_end"]  )  ] ,
            ["课时数",  $self_info["lesson_count"]/100  ] ,

            ["课堂视频", "<a href =\"http://admin.yb1v1.com/tea_manage/show_lesson_video?lessonid={$encode_lessonid}\" target=\"_blank\"> 课堂视频</a>" ],
        ];

    }

    static function get_line_data( $from_key_int,$from_key_str, $from_key2_int=0 ) {
        $self_info=static::get_self_info( $from_key_int,$from_key_str ,  $from_key2_int);
        $task=static::get_task_controler();
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);

        return "学生:" . $user_nick.",老师:".  $task->cache_get_teacher_nick( $self_info["teacherid"] )
                       .",上课时间:". \App\Helper\Utils::fmt_lesson_time ( $self_info["lesson_start"] ,$self_info["lesson_end"]  ) ;

    }


    static function next_node_process_0 ($flowid ,$adminid){ //

        $t=  new \App\Models\t_admin_group_user();
        $item=$t->get_up_level_users($adminid);
        return $item["master_adminid1"];
    }

    static function next_node_process_1 ($flowid ,$adminid){ //
        $t=  new \App\Models\t_admin_group_user();
        $item=$t->get_up_level_users($adminid);
        \App\Helper\Utils::logger( "master_adminid2:". $item["master_adminid2"] );

        return $item["master_adminid2"];
    }

    static function next_node_process_2 ($flowid, $adminid){ //
        //完成,设置状态
        $task=static::get_task_controler();
        list($flow_info,$self_info)=static::get_info($flowid);
        $task->t_lesson_info->field_update_list( $self_info["lessonid"] ,[
            "confirm_flag" => E\Econfirm_flag::V_4
        ]);
        return 0;
    }

}
