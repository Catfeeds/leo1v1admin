<?php
namespace App\Models;
use \App\Enums as E;
class t_revisit_call_count extends \App\Models\Zgen\z_t_revisit_call_count
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_call_phone_id_str($start_time,$end_time){
        $sql = $this->gen_sql_new("select group_concat(call_phone_id) as phoneids,uid "
                                  ." from %s "
                                  ." where revisit_time2>=$start_time and revisit_time2<$end_time"
                                  ." group by uid"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_call_phone_id_str_by_uid($start_time,$end_time,$uid){
        $sql = $this->gen_sql_new("select group_concat(call_phone_id) "
                                  ." from %s "
                                  ." where revisit_time2>=$start_time and revisit_time2<$end_time and uid=$uid"
                                  // ." group by uid"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }


    public function get_today_call_count($ass_adminid,$start_time,$end_time){

        $where_arr = [
            "rc.create_time>=$start_time",
            "rc.create_time<$end_time",
            "rc.uid=$ass_adminid",
        ];
        $sql = $this->gen_sql_new("select sum(tq.duration) "
                                  ." from %s rc"
                                  ." left join %s tq on tq.id=rc.call_phone_id"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_tq_call_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }



}
