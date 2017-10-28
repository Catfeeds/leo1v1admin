<?php
namespace App\Models;
use \App\Enums as E;
class t_web_page_info extends \App\Models\Zgen\z_t_web_page_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list( $page_info, $start_time,$end_time ,$del_flag  ) {
        $where_arr=[
            ["del_flag=%u",  $del_flag, -1  ]
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);

        $sql= $this->gen_sql_new("select * from %s where %s order by  add_time  desc ",
                                 self::DB_TABLE_NAME,  $where_arr );

        return $this->main_get_list_by_page($sql,$page_info);
    }
}











