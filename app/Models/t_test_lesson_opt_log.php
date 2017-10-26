<?php
namespace App\Models;
use \App\Enums as E;
class t_test_lesson_opt_log extends \App\Models\Zgen\z_t_test_lesson_opt_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_list($start_time,$end_time,$page_info){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,'opt_time',$start_time,$end_time);
        $role = E\Erole::V_6;
        $sql = $this->gen_sql_new(
            "select o.*,m.account "
            ." from %s o "
            ." left join %s t on t.teacherid=o.userid and o.role=%u "
            ." left join %s m on m.phone=t.phone "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_teacher_info::DB_TABLE_NAME
            ,$role
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_stu_test_lesson_opt($userid){
        $where_arr = [
            ['o.userid = %u',$userid,-1],
            ['o.role = %u',E\Erole::V_1],
            'o.roomid > 0 and o.lessonid = 0',
        ];
        $sql = $this->gen_sql_new(
            "select o.* "
            ." from %s o "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_test_lesson_opt($adminid){
        $where_arr = [
            ['o.role = %u',E\Erole::V_6],
            'o.roomid > 0 and o.lessonid = 0',
            ['m.uid = %u',$adminid,-1],
        ];
        $sql = $this->gen_sql_new(
            "select o.* "
            ." from %s o "
            ." left join %s t on t.teacherid=o.userid "
            ." left join %s m on m.phone=t.phone "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_teacher_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }
}
