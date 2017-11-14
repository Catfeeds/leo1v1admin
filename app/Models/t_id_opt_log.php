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

    public function get_history_count($log_type,$adminid,$start_time,$end_time){
        $where_arr=[
            ["log_type=%u" ,$log_type, -1 ],
            ["opt_id=%u" ,$adminid, -1 ],
        ];
        $this->where_arr_add_time_range($where_arr,"log_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            " select  count(distinct(value)) count "
            ." from %s "
            ." where  %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_history_info($start_time,$end_time,$origin_ex){
        $where_arr=[
            ["l.log_type=%u" ,E\Edate_id_log_type::V_SELLER_GET_HISTORY_COUNT],
        ];
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_add_time_range($where_arr,"l.log_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            " select l.opt_id adminid,count(distinct(l.value)) get_free_count "
            ." from %s l "
            ." left join %s s on s.userid=l.value "
            ." where %s group by l.opt_id ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_yxyx_last_adminid(){
        $now = time(null);
        $where_arr=[
            ["l.log_type=%u" ,E\Edate_id_log_type::V_SELLER_ASSIGNED_COUNT],
            "s.origin='优学优享'",
            ['l.log_time>=%u',$now-3600*24],
            ['l.log_time<%u',$now],
        ];
        $sql=$this->gen_sql_new(
            " select l.opt_id adminid "
            ." from %s l "
            ." left join %s s on s.userid=l.value "
            ." where %s order by log_time desc ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
}
