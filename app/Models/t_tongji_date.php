<?php
namespace App\Models;
use \App\Enums as E;
class t_tongji_date extends \App\Models\Zgen\z_t_tongji_date
{
	public function __construct()
	{
		parent::__construct();
	}
    public function del_log_time($log_type,$log_time) {
        $sql=$this->gen_sql(
            "delete from %s where log_type=%u and  log_time=%u ",
            self::DB_TABLE_NAME,
            $log_type,
            $log_time);
        $this->main_update($sql);
    }

    public function add($log_type,$log_time,$id, $count  ) {
        $this->row_insert([
            "log_type" => $log_type,
            "log_time" => $log_time,
            "id" => $id,
            "count" => $count,
        ],true);
    }

    public function get_list($log_type,$start_time,$end_time,$id ) {
        $where_arr=[
            ["id=%u",$id, -1]
        ];
        $sql= $this -> gen_sql_new("select log_time,id,count from %s where log_type=%u and  log_time>=%u and log_time<%u and %s",
                               self::DB_TABLE_NAME,
                               $log_type,$start_time,$end_time ,$where_arr);
        return $this->main_get_list($sql);
    }
    public function admin_revisiter_get_list($log_type,$start_time,$end_time,$id ) {
        $where_arr=[
            ["id=%u",$id, -1]
        ];
        $sql= $this -> gen_sql_new("select id as admin_revisiterid, sum(count) count from %s where log_type=%u and  log_time>=%u and log_time<%u and %s group by id",
                               self::DB_TABLE_NAME,
                               $log_type,$start_time,$end_time ,$where_arr);
        return $this->main_get_list($sql);
    }


}











