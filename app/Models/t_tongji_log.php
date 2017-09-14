<?php
namespace App\Models;
use \App\Enums as E;
class t_tongji_log extends \App\Models\Zgen\z_t_tongji_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function add($tongji_log_type, $logtime, $value ) {
        $this->row_insert_ignore([
            "tongji_log_type" => $tongji_log_type,
            "logtime" => $logtime,
            "value" => $value,
        ]);
    }

    public function get_list( $tongji_log_type, $start_time, $end_time) {
        $where_arr=[
            "tongji_log_type" => $tongji_log_type,
        ];
        $this->where_arr_add_time_range($where_arr,"logtime",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select logtime,value from %s where %s order by logtime asc "
            ,self::DB_TABLE_NAME,$where_arr );


        return $this->main_get_list($sql);
    }
}











