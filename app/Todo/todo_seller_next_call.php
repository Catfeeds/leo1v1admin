<?php
namespace App\Todo;

use \App\Enums as E;
class todo_seller_next_call extends todo_base {
    const  TYPE= E\Etodo_type::V_SELLER_NEXT_CALL;
    static function reset( $todo_info)  {
        $task                   = static::get_task();
        $todoid= $todo_info["todoid"];
        $userid                 = $todo_info["from_key_int"];
        $adminid                = $todo_info["adminid"];
        $start_time             = $todo_info["start_time"];
        $todo_type = $todo_info["todo_type"];
        $todo_status = $todo_info["todo_status"];
        $end_time = $todo_info["end_time"];
        $task_next_revisit_time = $todo_info["from_key2_int"];
        $now                    = time(NULL);
        if ($todo_type != static::TYPE ) {
            \App\Helper\Utils::logger("error type :$todo_type ");
            return ;
        }

        //检查当前回访时间
        $ss_info= $task->t_seller_student_new->field_get_list($userid,"next_revisit_time,phone");
        //$next_revisit_time=$ss_info["next_revisit_time"];

        $next_revisit_time= $task_next_revisit_time ;
        $phone=$ss_info["phone"];
        /*
        if($next_revisit_time!= $task_next_revisit_time ) {
            \App\Helper\Utils::logger("no need check");
            return ;
        }
        */
        $call_list=static::get_call_list($adminid,$phone,$start_time,$start_time+86400);
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
        $task_next_revisit_time = $todo_info["from_key2_int"];
        $row=$task->t_seller_student_new->field_get_list($userid,"phone");
        $phone=$row["phone"];
        $row=$task->t_student_info->field_get_list($userid,"nick");
        $nick=$row["nick"];
        $time_str=\App\Helper\Utils::unixtime2date($task_next_revisit_time , 'Y-m-d H:i');
        return array("[$nick-$phone][$time_str]","/seller_student_new/seller_student_list_all?userid=$userid" );
    }

}