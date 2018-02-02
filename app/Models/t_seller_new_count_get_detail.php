<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_new_count_get_detail extends \App\Models\Zgen\z_t_seller_new_count_get_detail
{
	public function __construct()
	{
		parent::__construct();
	}
    public function add( $new_count_id, $get_desc,$userid ) {
        $this->row_insert([
            "new_count_id"  => $new_count_id,
            "get_time"  =>time(NULL) ,
            "get_desc"  =>$get_desc ,
            "userid"  =>$userid ,
        ]);
    }

    public function rwo_del_by_detail_id($id){
        $sql=sprintf("delete from %s  where  detail_id='%s' ",
                     self::DB_TABLE_NAME,
                     $this->ensql($id));
        return $this->main_update($sql);
    }

    public function get_row_by_userid($new_count_id,$userid){
        $where_arr = [
            ['new_count_id=%d',$new_count_id],
            ['userid=%d',$userid],
        ];
        $sql = $this->gen_sql_new(
            " select * "
            ." from %s "
            ." where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_daily_userid($start_time,$end_time){
        $where_arr = [
            'd.userid>0',
        ];
        $this->where_arr_add_time_range($where_arr, 'd.get_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select d.userid,n.cc_called_count,n.cc_no_called_count_new "
            ." from %s d "
            ." left join %s n on n.userid=d.userid "
            ." where %s ",
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_item_rwo_by_userid($userid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'userid', $userid);
        $sql = $this->gen_sql_new(
            " select detail_id "
            ." from %s "
            ." where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
}











