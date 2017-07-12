<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_cancel_lesson_list extends \App\Models\Zgen\z_t_teacher_cancel_lesson_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_cancel_teacher_list_new($start_time,$end_time){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"lesson_time",$start_time,$end_time);
        $where_arr[]="t.is_week_freeze <> 1";
        $sql= $this->gen_sql_new("select c.teacherid,count(*) num,t.realname from %s c ".
                                 " left join %s t on c.teacherid=t.teacherid".
                                 " where %s group by c.teacherid having(num >=2)",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_warning_cancel_teacher_list_new($start_time,$end_time){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"lesson_time",$start_time,$end_time);
        $where_arr[]="t.week_freeze_warning_flag <> 1";
        $sql= $this->gen_sql_new("select c.teacherid,count(*) num,t.realname from %s c ".
                                 " left join %s t on c.teacherid=t.teacherid".
                                 " where %s group by c.teacherid having(num =1)",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_all_info(){
        $sql=$this->gen_sql_new("select * from %s ",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
}











