<?php
namespace App\Todo;

use \App\Enums as E;
class todo_seller_before_lesson_call extends todo_base {
    const  TYPE= E\Etodo_type::V_SELLER_BEFORE_LESSON_CALL;
    static function reset( $todo_info)  {
        $task                   = static::get_task();
        $todoid= $todo_info["todoid"];
        $lessonid    = $todo_info["from_key_int"];
        $adminid                = $todo_info["adminid"];
        $start_time             = $todo_info["start_time"];
        $todo_type = $todo_info["todo_type"];
        $todo_status = $todo_info["todo_status"];
        $end_time = $todo_info["end_time"];
        $now                    = time(NULL);
        if ($todo_type != static::TYPE ) {
            \App\Helper\Utils::logger("error type :$todo_type ");
            return ;
        }

        $lesson_info= $task->t_lesson_info->field_get_list($lessonid,"userid,lesson_start");
        $lesson_start = $lesson_info["lesson_start" ];
        $userid= $lesson_info["userid" ];

        $ss_info= $task->t_seller_student_new->field_get_list($userid,"phone");

        $phone=$ss_info["phone"];
        $call_list=static::get_call_list($adminid,$phone,$start_time, $end_time );
        if (count($call_list) >0)  {//拨打过
            static::set_todo_status($todoid,E\Etodo_status::V_DONE );
        } else {
            if ($end_time  < $now   ) { //超时
                static::set_todo_status($todoid,E\Etodo_status::V_NOT_DONE );
            }
        }
    }

    static function get_info_ex($todo_info){
        $task                   = static::get_task();
        $userid                 = $todo_info["from_key_int"];
        $adminid                = $todo_info["adminid"];
        $start_time             = $todo_info["start_time"];
        $todo_type              = $todo_info["todo_type"];
        $todo_status            = $todo_info["todo_status"];
        $end_time               = $todo_info["end_time"];
        $lessonid = $todo_info["from_key_int"];
        $lesson_info= $task->t_lesson_info->field_get_list($lessonid,"userid,lesson_start");
        $userid=$lesson_info["userid"];
        $lesson_start=$lesson_info["lesson_start"];
        $row=$task->t_seller_student_new->field_get_list($userid,"phone");
        $phone=$row["phone"];
        $row=$task->t_student_info->field_get_list($userid,"nick");
        $nick=$row["nick"];
        $time_str=\App\Helper\Utils::unixtime2date($lesson_start, 'Y-m-d H:i');
        return array("[$nick-$phone][$time_str]","/seller_student_new/seller_student_list_all?userid=$userid" );
    }

}