<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_student_system_assign_log extends \App\Models\Zgen\z_t_seller_student_system_assign_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_admin_assign_count_info( $start_time, $end_time ){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr, "logtime", $start_time, $end_time);
        $sql=$this->gen_sql_new(
            "select adminid,"
            . " sum(seller_student_assign_from_type=0 ) as new_count , "
            . " sum(seller_student_assign_from_type=1 ) as no_connected_count "
            . "from %s  "
            . "where %s group by adminid  "
            ,
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql, function($item){
            return $item["adminid"];
        });

    }

}











