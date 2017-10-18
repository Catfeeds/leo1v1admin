<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_edit_log extends \App\Models\Zgen\z_t_seller_edit_log
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_all_list($uid){
        $where_arr = [];
        if($uid){
            $this->where_arr_add_int_or_idlist($where_arr,'uid',$uid);
        }
        $sql = $this->gen_sql_new (
            " select * "
            ." from %s where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_distribution_list($adminid,$start_time,$end_time,$page_info){
        $where_arr = [
            ['adminid = %u',$adminid,-1],
            ['type = %u',E\Eseller_edit_log_type::V_3],
        ];
        $this->where_arr_add_time_range($where_arr,'create_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select * "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_distribution_count($start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,'create_time',$start_time,$end_time);
        $this->where_arr_add_int_or_idlist($where_arr,'type',E\Eseller_edit_log_type::V_3);
        $sql = $this->gen_sql_new(
            " select count(adminid) count,adminid,type "
            ." from %s "
            ." where %s "
            ." group by adminid "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

}
