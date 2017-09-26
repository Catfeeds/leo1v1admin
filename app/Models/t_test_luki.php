<?php
namespace App\Models;
use \App\Enums as E;
class t_test_luki extends \App\Models\Zgen\z_t_test_luki
{
    public function __construct()
    {
        parent::__construct();
    }
    public function  get_list( $page_info,$grade ,$start_time, $end_time) {
        $where_arr=[
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"grade",$grade,-1);
        $this->where_arr_add_time_range($where_arr,"value",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select * from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }
    public function  test11( ) {
        $sql = $this->gen_sql_new("select * from %s where id=1", self::DB_TABLE_NAME);
        return $this->main_get_row($sql);

        $sql = $this->gen_sql_new("select grade from %s where id=1", self::DB_TABLE_NAME);
        return $this->main_get_value($sql);

        $this->main_get_list($sql);
    }

}
