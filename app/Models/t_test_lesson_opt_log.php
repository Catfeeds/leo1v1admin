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
        $sql = $this->gen_sql_new(
            "select * "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
}
