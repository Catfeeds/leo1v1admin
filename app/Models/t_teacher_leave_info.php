<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_leave_info extends \App\Models\Zgen\z_t_teacher_leave_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info_by_teacherid($teacherid){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by leave_set_time desc",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_teacher_leave_no_hold_list(){
        $where_arr=[
            "t.lesson_hold_flag =0",
            "l.leave_start_time<=".time(),
            "l.leave_start_time>0",
            "l.leave_end_time >".time()
        ];
        $sql = $this->gen_sql_new("select l.teacherid,l.leave_end_time,l.leave_start_time "
                                  ." from %s l left join %s t on l.teacherid=t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    
    public function get_teacher_leave_end_hold_list(){
        $where_arr=[
            "t.lesson_hold_flag =1",
            "l.leave_end_time <=".time(),
            "l.leave_end_time>0"
        ];
        $sql = $this->gen_sql_new("select l.teacherid,l.leave_end_time,l.leave_start_time "
                                  ." from %s l left join %s t on l.teacherid=t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

}











