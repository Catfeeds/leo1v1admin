<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_level_goal extends \App\Models\Zgen\z_t_seller_level_goal
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_all_list($page_info){
        $sql = $this->gen_sql_new(
            "select * "
            ." from %s order by num "
            ,self::DB_TABLE_NAME
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_next_level_by_num($next_num){
        $sql = $this->gen_sql_new(
            "select * "
            ." from %s where num=%u limit 1"
            ,self::DB_TABLE_NAME
            ,$next_num
        );
        return $this->main_get_row($sql);
    }

}
