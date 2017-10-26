<?php
namespace App\Models;
use \App\Enums as E;
class t_test_lesson_opt_log extends \App\Models\Zgen\z_t_test_lesson_opt_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_list($page_info){
        $where_arr = [];
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
}
