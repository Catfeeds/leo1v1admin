<?php
namespace App\Models;
use \App\Enums as E;
class t_log_event_log extends \App\Models\Zgen\z_t_log_event_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function tongji_start_succ_fail( $start_time, $end_time  ) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr, "logtime", $start_time, $end_time);
        $sql=$this->gen_sql_new(
            "select e.sub_project, "
            . "  sum( e.event_name =\"start\") as start_count ,  "
            . "  sum( e.event_name =\"succ\") as succ_count,  "
            . "  sum( e.event_name =\"fail\") as fail_count  "
            ." from  %s el "
            ." left join %s e on e.event_type_id= el.event_type_id "
            ." where  %s group by e.sub_project ",
            self::DB_TABLE_NAME,
            t_log_event_type::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list_as_page($sql);
    }

    /**
     *
     */
    public function get_list($page_info, $order_by_str, $event_type_id,$start_time, $end_time  )
    {
        $where_arr=[];

        $this->where_arr_add_int_or_idlist($where_arr,"event_type_id" , $event_type_id);
        $this->where_arr_add_time_range($where_arr, "logtime", $start_time, $end_time);
        $sql=$this->gen_sql(
            "select *  "
            ." from  %s "
            ." where  %s "
            ."  $order_by_str ",
            self::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list_by_page($sql,$page_num);
    }



}











