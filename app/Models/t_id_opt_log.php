<?php
namespace App\Models;
use \App\Enums as E;
class t_id_opt_log extends \App\Models\Zgen\z_t_id_opt_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add_ex( $log_type,$log_time, $opt_id,$value ) {
        $sql= $this->gen_sql(
            "delete from %s  "
            ." where log_type=%u and  log_time=%u ",
            self::DB_TABLE_NAME, $log_type, $log_time  );
        $this->main_update($sql);

        return $this->row_insert([
            "log_type" => $log_type,
            "log_time" => $log_time,
            "opt_id" => $opt_id ,
            "value" => $value,
        ]);
    }


    public function add( $log_type, $opt_id,$value ) {
        return $this->row_insert([
            "log_type" => $log_type,
            "log_time" => time(NULL),
            "opt_id" => $opt_id ,
            "value" => $value,
        ]);
    }
    public function get_seller_tongji(  $start_time ,$end_time  ,$grade_list) {
        $where_arr=[
        ];
        $this->where_arr_add_time_range($where_arr,"log_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select opt_id,  "
            ." sum(log_type=1001)  assigned_count, "
            // ." sum(log_type=1002) get_new_count, "
            ." sum(log_type=1003) get_histroy_count "
            ."  from %s  "
            ." where  %s "
            ."group by opt_id",
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }


    public function get_date_one_list( $log_type , $start_time ,$end_time ) {
        $where_arr=[
            ["log_type=%u" ,$log_type, -1 ],
        ];
        $this->where_arr_add_time_range($where_arr,"log_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select opt_id,   count(*) as count from %s  "
            ." where  %s "
            ."group by opt_id",
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }
    public function get_date_list( $log_type, $start_time ,$end_time ) {
        $where_arr=[
            ["log_type=%u" ,$log_type, -1 ],
        ];
        $this->where_arr_add_time_range($where_arr,"log_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select  from_unixtime(log_time, '%%Y-%%m-%%d') as opt_date,   value  as count from %s  "
            ." where  %s "
            ."",
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }

}
