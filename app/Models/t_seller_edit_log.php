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

    public function get_distribution_list($adminid,$start_time,$end_time,$page_info,$global_tq_called_flag){
        $where_arr = [
            ['l.adminid = %u',$adminid,-1],
            ['l.type = %u',E\Eseller_edit_log_type::V_3],
            ['ss.global_tq_called_flag = %u',$global_tq_called_flag,-1],
            'ss.userid >0 ',
        ];
        $this->where_arr_add_time_range($where_arr,'l.create_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select l.*,ss.global_tq_called_flag "
            ." from %s l "
            ." left join %s ss on ss.userid=l.new "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_distribution_count($start_time,$end_time){
        $where_arr = [
            'ss.userid >0 ',
        ];
        $this->where_arr_add_time_range($where_arr,'l.create_time',$start_time,$end_time);
        $this->where_arr_add_int_or_idlist($where_arr,'l.type',E\Eseller_edit_log_type::V_3);
        $sql = $this->gen_sql_new(
            " select count(l.adminid) count,l.adminid,l.type,"
            ." count(ss.global_tq_called_flag = 0) no_call_count,ss.global_tq_called_flag "
            ." from %s l"
            ." left join %s ss on ss.userid=l.new and ss.global_tq_called_flag = 0 "
            ." where %s "
            ." group by l.adminid "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

}
