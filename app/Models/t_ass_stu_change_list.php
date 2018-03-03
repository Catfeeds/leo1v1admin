<?php
namespace App\Models;
use \App\Enums as E;
class t_ass_stu_change_list extends \App\Models\Zgen\z_t_ass_stu_change_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_ass_history_list($adminid,$start_time,$end_time,$userid=-1){
        $where_arr=[
            ["ass.old_ass_adminid = %u",$adminid,-1],
            ["ass.userid = %u",$userid,-1],
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"ass.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select ass.*,m.uid "
                                  ."from %s ass left join %s s on ass.userid = s.userid"
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s and ass.old_ass_adminid <> m.uid",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_ass_list($userid){
        $sql = $this->gen_sql_new("select adminid ,old_ass_adminid from %s where userid= %u",self::DB_TABLE_NAME,$userid);
        return $this->main_get_list($sql);
    }

}











