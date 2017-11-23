<?php
namespace App\Models;
use \App\Enums as E;
class t_fulltime_teacher_attendance_list extends \App\Models\Zgen\z_t_fulltime_teacher_attendance_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_fulltime_teacher_attendance_list($start_time,$end_time,$attendance_type,$teacherid,$page_num,$adminid,$account_role=-1,$fulltime_teacher_type=-1){
        $where_arr=[
            ["f.attendance_type=%u",$attendance_type,-1],
            ["f.teacherid=%u",$teacherid,-1],
            ["f.adminid=%u",$adminid,-1],
            ["m.account_role=%u",$account_role,-1],
            ["m.fulltime_teacher_type=%u",$fulltime_teacher_type,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"f.attendance_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.realname,f.teacherid,f.adminid,f.add_time,f.attendance_time,f.attendance_type,f.day_num,f.off_time,f.cancel_flag,f.cancel_reason,f.id,f.lesson_count from %s f"
                                  ." left join %s t on f.teacherid = t.teacherid"
                                  ." left join %s m on f.adminid = m.uid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);

        
    }

    public function check_is_exist($teacherid,$day_time){
        $where_arr=[
            ["attendance_time=%u",$day_time,-1],
            ["teacherid=%u",$teacherid,-1],
        ];
        $sql = $this->gen_sql_new("select id from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_holiday_info(){
        $sql = $this->gen_sql_new("select * from %s where attendance_type=3",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

}











