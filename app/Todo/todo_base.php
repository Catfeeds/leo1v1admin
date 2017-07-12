<?php
namespace App\Todo;
use \App\Enums as E;
/**
 *
 */
class todo_base {
    static $type_config=[
        E\Etodo_type::V_SELLER_NEXT_CALL => todo_seller_next_call::class,
        E\Etodo_type::V_SELLER_BEFORE_LESSON_CALL  => todo_seller_before_lesson_call::class,
        E\Etodo_type::V_SELLER_AFTER_LESSON_CALL  => todo_seller_after_lesson_call::class,
    ];
    static function get_class( $todo_type) {
        return  static::$type_config[$todo_type ];
    }

    static function do_reset( $todoid, $reset_msg_flag=false)  {
        $todo_info = static::get_todo_info($todoid);
        $todo_type = $todo_info["todo_type"];
        $todo_class=static::get_class($todo_type);
        $todo_class::reset($todo_info);
        if ($reset_msg_flag ) {
            $todo_class::get_info_ex($todo_info);
            static::get_task()->t_todo->field_update_list($todoid,[
                "msg" => json_encode($todo_class::get_info_ex($todo_info))
            ]);
        }
    }

    //需要实现
    /*
    static function reset( $todo_info)  {

    }

    */

    static function add( $todo_type, $start_time, $end_time , $adminid,  $from_key_int,$from_key2_int  ) {
        $now=time(NULL);
        $task=static::get_task();
        $todo_status=E\Etodo_status::V_NOT_START;
        if ($now>= $start_time ) {
            $todo_status=E\Etodo_status::V_TODO;
        }
        $todo_status_time=$now;
        $create_time=$now;

        if (!$task->t_todo->check_by_from_key($todo_type,$from_key_int,$from_key2_int)){
            $todo_info=[
                "todo_type"=>$todo_type,
                "create_time"=>$create_time,
                "start_time"=>$start_time,
                "end_time"=>$end_time,
                "adminid"=>$adminid,
                "from_key_int"=>$from_key_int,
                "from_key2_int"=>$from_key2_int,
                "todo_status"=>$todo_status,
                "todo_status_time"=>$todo_status_time,

            ];
            $class_name=static::get_class($todo_type);
            $todo_info["msg"]= json_encode($class_name::get_info_ex($todo_info));

            $task->t_todo->row_insert($todo_info);
            return $task->t_todo->get_last_insertid();

        }else{
            return 0;
        }
    }

    static function get_flow_class($flow_type ) {
        return @static::$data[$flow_type];
    }
    /**
     * @return \App\Console\Tasks\TaskController
     */
    static function get_task() {
        return new \App\Console\Tasks\TaskController ();
    }
    static function get_todo_info($todoid) {
        return static::get_task()->t_todo->field_get_list($todoid,"*");
    }
    static function get_call_list($adminid, $phone,$start_time,$end_time, $is_called_phone = -1 ) {
        $tquin=intval( static::get_task()->t_manager_info->get_tquin($adminid));
        return static::get_task()->t_tq_call_info->get_list_ex($tquin, $phone, $start_time,$end_time , $is_called_phone );
    }
    static function set_todo_status( $todoid,$todo_status ) {
        $todo_info      = static::get_todo_info($todoid);
        $db_todo_status = $todo_info["todo_status"];
        if ($todo_status != $db_todo_status  ) {
            static::get_task()->t_todo->field_update_list($todoid,[
                "todo_status"      => $todo_status,
                "todo_status_time" => time(NULL),
            ]);
        }
    }

    static function get_info_ex($todo_info){
        return array("line-info", "jump-url");
    }


    static function get_info($todoid){
        $todo_info      = static::get_todo_info($todoid);
        return static::get_info_ex($todo_info) ;
    }

}
