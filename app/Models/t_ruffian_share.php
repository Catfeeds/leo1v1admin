<?php
namespace App\Models;
use \App\Enums as E;
class t_ruffian_share extends \App\Models\Zgen\z_t_ruffian_share
{
	public function __construct()
	{
		parent::__construct();
	}


    public function delete_row_by_pid($parentid){
        $sql = $this->gen_sql_new("  delete from %s where parentid=%d"
                                  ,self::DB_TABLE_NAME
                                  ,$parentid
        );

        return $this->main_update($sql);
    }

    public function get_share_num($parentid, $start_time, $end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,"share_time",$start_time, $end_time);
        $sql = $this->gen_sql_new("  select count(*) from %s ru "
                                  ." where parentid = %d and is_share_flag=1"
                                  ,self::DB_TABLE_NAME
                                  ,$parentid
        );

        return $this->main_get_value($sql);
    }
}











