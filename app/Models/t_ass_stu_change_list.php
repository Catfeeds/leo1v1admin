<?php
namespace App\Models;
use \App\Enums as E;
class t_ass_stu_change_list extends \App\Models\Zgen\z_t_ass_stu_change_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_ass_history_list($adminid,$start_time,$end_time){
        $where_arr=[
            ["as.old_ass_adminid = %u",$adminid,-1],
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"as.add_time",$start_time,$end_time);
        // $sql = $this->gen_sql_new("select as.* "
        //                           ."from % as left join %s s on as.userid = s.userid"
        //                           ." where %s"
        // );
    }

}











