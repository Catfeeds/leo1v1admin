<?php
namespace App\Models;
use \App\Enums as E;
class t_month_def_type extends \App\Models\Zgen\z_t_month_def_type
{
	public function __construct()
	{
		parent::__construct();
	}

    public function  get_list( $page_info, $month) {
        //$where_arr=[
        //];
        //$this->where_arr_add_int_or_idlist($where_arr,"month_def_type",$month,1);
        //$this->where_arr_add_time_range($where_arr,"def_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select id,month_def_type,def_time,start_time,end_time from %s order by def_time desc",
            self::DB_TABLE_NAME
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_count_by_def_time($def_time) {
        $sql=$this->gen_sql_new(
            "select id from %s where def_time = %u limit 1",
            self::DB_TABLE_NAME,
            $def_time
        );
        return $this->main_get_row($sql);
    }
}











