<?php
namespace App\Models;
use \App\Enums as E;
class t_todo extends \App\Models\Zgen\z_t_todo
{
    public function __construct()
    {
        parent::__construct();
    }
    public function check_by_from_key(  $todo_type, $from_key_int, $from_key2_int ) {
        $sql = $this->gen_sql_new(
            "select 1 from %s "
            ." where  todo_type= %u "
            . " and  from_key_int= %u "
            . " and from_key2_int=%u  ",
            self::DB_TABLE_NAME, $todo_type, $from_key_int, $from_key2_int  );
        return $this->main_get_value($sql) ==1;
    }

    public function get_list( $page_info, $adminid , $todo_type  , $todo_status, $start_time, $end_time) {
        $where_arr=[];
        $this->where_arr_add_int_field($where_arr,"adminid",$adminid);
        $this->where_arr_add_int_or_idlist($where_arr,"todo_type",$todo_type);
        $this->where_arr_add_int_or_idlist($where_arr,"todo_status",$todo_status);
        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time) ;
        $sql = $this->gen_sql_new(
            "select * from  %s where %s  ",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_todo_status_count_info( $adminid , $start_time, $end_time) {
        $where_arr=[];
        $this->where_arr_add_int_field($where_arr,"adminid",$adminid);
        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time) ;
        $sql = $this->gen_sql_new(
            "select  todo_status , count(*) as count  from  %s"
            . " where %s   group by todo_status ",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["todo_status"];
        });
    }

}
