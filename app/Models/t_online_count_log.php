<?php
namespace App\Models;
use \App\Enums as E;
class t_online_count_log extends \App\Models\Zgen\z_t_online_count_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function test_xx() {

        $this->gen_sql_new("update %s  set online_count=%u  where id=%u ",
                           self::DB_TABLE_NAME, $online_count, $id);
        $this->main_update($sql);
        $row=$this->field_get_list($id, "*");
        $this->get_online_count($id);
    }
    public function get_list($start_time, $end_time) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"logtime",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select logtime,online_count from %s where %s order by logtime asc "
            ,self::DB_TABLE_NAME,$where_arr );


        return $this->main_get_list($sql);
    }



    public function add( $logtime, $online_count ) {
        return $this->row_insert([
            "logtime" =>$logtime,
            "online_count" => $online_count,
        ],true );
    }

}











