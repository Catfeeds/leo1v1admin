<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_get_new_log extends \App\Models\Zgen\z_t_seller_get_new_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_row_by_adminid_userid($adminid,$userid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'adminid', $adminid);
        $this->where_arr_add_int_field($where_arr, 'userid', $userid);
        $sql = $this->gen_sql_new(
            "select id,called_count,no_called_count,cc_end "
            ."from %s "
            ."where %s limit 1"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_list_by_time($start_time,$end_time,$call_flag=-1){
        $where_arr = [];
        if($call_flag == 1){
            $where_arr[] = 'called_count+no_called_count>0';
        }elseif($call_flag == 2){
            $where_arr[] = 'called_count>0';
        }
        $this->where_arr_add_time_range($where_arr, 'create_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            "select * "
            ."from %s "
            ."where %s order by create_time"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_last_get_time($adminid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'adminid', $adminid);
        $sql = $this->gen_sql_new(
            "select create_time "
            ."from %s "
            ."where %s order by create_time desc limit 1"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_cc_end_list($adminid,$start_time, $end_time){
        $where_arr = [
            'called_count+no_called_count>0',
        ];
        $this->where_arr_add_int_field($where_arr, 'called_count', 0);
        $this->where_arr_add_int_field($where_arr, 'cc_end', 0);
        $this->where_arr_add_int_field($where_arr, "adminid" ,$adminid);
        $this->where_arr_add_time_range($where_arr, 'create_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select * ".
            " from %s ".
            " where %s order by create_time asc "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_list($start_time,$end_time,$adminid=-1,$called_flag=-1){
        $where_arr = [
            ['l.adminid=%u',$adminid,-1],
        ];
        if($called_flag==0){
            $where_arr[] = 'l.called_count=0';
        }elseif($called_flag==1){
            $where_arr[] = 'l.called_count>0';
        }
        $this->where_arr_add_time_range($where_arr, 'l.create_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select l.*,n.add_time,n.phone ".
            " from %s l ".
            " left join %s n on n.userid=l.userid ".
            " where %s order by l.create_time asc "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_cc_end_count($adminid,$start_time,$end_time){
        $where_arr = [
            'called_count>0',
        ];
        $this->where_arr_add_int_field($where_arr, 'adminid', $adminid);
        $this->where_arr_add_int_field($where_arr, 'cc_end', 1);
        $this->where_arr_add_time_range($where_arr, 'create_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select count(userid) ".
            " from %s ".
            " where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_call_list($start_time,$end_time){
        $where_arr = [
            'called_count+no_called_count>0',
        ];
        $this->where_arr_add_time_range($where_arr, 'l.create_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select l.*,s.origin_level ".
            " from %s l ".
            " left join %s s on s.userid=l.userid ".
            " where %s "
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }
}
