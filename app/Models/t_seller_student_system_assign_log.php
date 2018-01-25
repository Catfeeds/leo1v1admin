<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_student_system_assign_log extends \App\Models\Zgen\z_t_seller_student_system_assign_log
{
    public function __construct()
    {
        parent::__construct();
    }
    public function check_userid_adminid_existed( $userid, $adminid) {
        $sql=$this->gen_sql_new(
            "select count(*) from %s"
            . " where userid=%u and adminid=%u ",
            self::DB_TABLE_NAME,
            $userid, $adminid
        );
        return $this->main_get_value($sql)>=1;
    }

    public function add( $seller_student_assign_from_type,$userid,  $adminid){
       $this->row_insert([
           "userid" => $userid  ,
           "logtime" => time(NULL),
           "adminid" => $adminid ,
           "seller_student_assign_from_type" => $seller_student_assign_from_type,
       ]);
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
    public function get_seller_student_assign_from_type_list( $adminid, $userid_list )  {
        $where_arr=[
            "adminid" => $adminid
        ];
        $this->where_arr_add_int_or_idlist($where_arr, "userid", $userid_list );
        $sql= $this->gen_sql_new("select userid, seller_student_assign_from_type  from %s "
                                 . "where %s ",
                                 self::DB_TABLE_NAME, $where_arr
        );

        return $this->main_get_list($sql);
    }

}
