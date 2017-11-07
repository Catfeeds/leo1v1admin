<?php
namespace App\Models;
use \App\Enums as E;
class t_test_lesson_opt_log extends \App\Models\Zgen\z_t_test_lesson_opt_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_list($start_time,$end_time,$test_lesson_type,$action,$test_opt_type,$adminid,$user_name,$page_info){
        $where_arr = [
            ['o.action = %u',$action,-1],
            ['m.uid = %u',$adminid,-1],
        ];
        if($test_opt_type > 0){
            $where_arr[] = ['o.opt_type=%u',$test_opt_type];
        }
        if($test_lesson_type == E\Etest_lesson_type::V_1){
            $where_arr[] = 'o.lessonid>0';
        }elseif($test_lesson_type == E\Etest_lesson_type::V_2){
            $where_arr[] = 'o.roomid>0 and o.lessonid=0';
        }
        if ($user_name) {
            $where_arr[]=sprintf( "(s.nick like '%s%%' or s.realname like '%s%%' or s.phone like '%s%%' )",
                                  $this->ensql($user_name),
                                  $this->ensql($user_name),
                                  $this->ensql($user_name));
        }
        $this->where_arr_add_time_range($where_arr,'opt_time',$start_time,$end_time);
        $role_c = E\Erole::V_6;
        $role_s = E\Erole::V_1;
        $sql = $this->gen_sql_new(
            "select o.*,m.account "
            ." from %s o "
            ." left join %s t on t.teacherid=o.userid and o.role=%u "
            ." left join %s m on m.phone=t.phone "
            ." left join %s s on s.userid=o.userid and o.role=%u "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_teacher_info::DB_TABLE_NAME
            ,$role_c
            ,t_manager_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$role_s
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_room_lesson_list($start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,'opt_time',$start_time,$end_time);
        $this->where_arr_add_int_or_idlist($where_arr,'role',[E\Erole::V_1,E\Erole::V_6]);
        $sql = $this->gen_sql_new(
            "select o.*,n.test_lesson_opt_flag,l.on_wheat_flag "
            ." from %s o "
            ." left join %s n on n.userid = o.userid "
            ." left join %s l on l.lessonid = o.lessonid "
            ." where %s "
            ." order by opt_time "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_room_list($start_time,$end_time){
        $where_arr = [
            'roomid > 0',
            ['action = %u',E\Eaction::V_1]
        ];
        $this->where_arr_add_time_range($where_arr,'opt_time',$start_time,$end_time);
        $this->where_arr_add_int_or_idlist($where_arr,'role',[E\Erole::V_1,E\Erole::V_6]);
        $sql = $this->gen_sql_new(
            "select * "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
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
