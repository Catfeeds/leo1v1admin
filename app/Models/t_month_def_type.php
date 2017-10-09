<?php
namespace App\Models;
use \App\Enums as E;
class t_month_def_type extends \App\Models\Zgen\z_t_month_def_type
{
	public function __construct()
	{
		parent::__construct();
	}

    public function  get_list( $page_info, $month, $start_time, $end_time) {
        $where_arr=[
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"month_def_type",$month,1);
        $this->where_arr_add_time_range($where_arr,"def_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select * from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }
}











