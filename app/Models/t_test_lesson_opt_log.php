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

    public function get_room_list(){
        $where_arr = [
            'roomid > 0',
            ['opt_type = %u',E\Eaction::V_1]
        ];
        $this->where_arr_add_int_or_idlist($where_arr,'role',[E\Erole::V_1,E\Erole::V_6]);
        $sql = $this->gen_sql_new(
            "select * "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['roomid'];
        });
    }

    public function get_test_lesson_opt_list_by_userid($userid){
        $where_arr = [
            ['o.userid = %u',$userid,-1],
            ['o.role = %u',E\Erole::V_1],
            'o.roomid > 0 and o.lessonid = 0',
        ];
        $sql = $this->gen_sql_new(
            "select o.*,"
            ." o2.userid teacherid,o2.action action_seller,o2.opt_type opt_type_seller,"
            ." o2.opt_time opt_time_seller,o2.server_ip server_ip_seller "
            ." from %s o "
            ." left join %s o2 on o2.roomid = o.roomid and o2.role = %u "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,E\Erole::V_6
            ,$where_arr
        );
        return $this->main_get_list($sql);
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

    public function get_seller_test_lesson_opt($roomid,$server_ip){
        $where_arr = [
            ['o.roomid = %u',$roomid,-1],
            ['o.server_ip <> %u',$server_ip,-1],
            ['o.role = %u',E\Erole::V_6],
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
}
