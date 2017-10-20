<?php
namespace App\Models;
use \App\Enums as E;
class t_interview_remind extends \App\Models\Zgen\z_t_interview_remind
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_interview_remind_list($page_num,$start_time, $end_time, $user_name){

        $where_arr = [];

        $this->where_arr_add_time_range($where_arr,"interview_time",$start_time,$end_time);

        if ($user_name) {
            $where_arr[]= " (name like '%" . $user_name . "%' or post like '%" . $user_name . "%' or dept like '%" . $user_name . "%') "   ;
        }


        $sql = $this->gen_sql_new("  select hr_adminid, interviewer_id, name, post, interview_time, dept, is_send_flag, send_msg_time from %s i "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list_by_page($sql,$page_num);
    }


    public function get_remind_list($start_time, $end_time){
        $where_arr = [
            "i.interview_time < $start_time + 3600"
        ];

        $sql = $this->gen_sql_new("  select m.wx_openid from %s i "
                                  ." left join %s m on m.uid=i.interviewer_id"
        );

    }
}











