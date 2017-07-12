<?php
namespace App\Models;
use \App\Enums as E;
class t_research_teacher_rerward_list extends \App\Models\Zgen\z_t_research_teacher_rerward_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function tongji_week_teacher_order_turn_info($start_time,$end_time){
        $where_arr = [];
        $confirm_time1 = strtotime(date("Y-m-01",$start_time-10*86400));        
        $confirm_time2 = strtotime(date("Y-m-01",$start_time-40*86400));        
        // $where_arr[]="tl.confirm_time>=".$confirm_time;
        $where_arr[] = "rt.reward>0";
        $this->where_arr_add_time_range($where_arr,"rt.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select sum(reward) reward_count,count(distinct rt.teacherid) num,t.realname,rt.adminid "
                                  ." from %s rt"
                                  ." left join %s m on rt.adminid=m.uid"
                                  ." left join %s t on m.phone=t.phone"
                                  ." left join %s tt on tt.teacherid = rt.teacherid"
                                  ." left join %s tl on (tt.phone=tl.phone and tl.status=1 and tl.subject = tt.subject)"
                                  ." where %s and if(rt.add_time>1492146000,tl.confirm_time>%u,tl.confirm_time>%u) group by rt.adminid",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $confirm_time1,
                                  $confirm_time2
        );
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });

    }

    public function tongji_research_teacher_first_reward($start_time,$end_time){
        $where_arr = [
           "first_reward>0" 
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select sum(first_reward) first_reward_count,count(distinct teacherid) first_rerward_num,adminid "
                                  ." from %s "
                                  ." where %s  group by adminid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });

    }

    public function get_all_info(){
        $sql = $this->gen_sql_new("select * from %s",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function tongji_research_teacher_rerward_info($start_time,$end_time,$adminid){
        $where_arr = [
            ["rt.adminid=%u",$adminid,-1],
            "reward>0"
        ];
        $confirm_time = strtotime(date("Y-m-01",$start_time-10*86400));
        $where_arr[]="tl.confirm_time>=".$confirm_time;
        $this->where_arr_add_time_range($where_arr,"rt.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select rt.teacherid,t.realname,sum(reward) reward_count,count(*) num"
                                  ." from %s rt left join %s t on rt.teacherid = t.teacherid"
                                  ." left join %s tl on (t.phone=tl.phone and tl.status=1 and tl.subject = t.subject)"
                                  ." where %s group by rt.teacherid order by num desc",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }
    
    public function tongji_research_teacher_first_rerward_info($start_time,$end_time,$adminid){
        $where_arr = [
            ["rt.adminid=%u",$adminid,-1],
            "first_reward>0"
        ];
        $this->where_arr_add_time_range($where_arr,"rt.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select rt.teacherid,t.realname,first_reward"
                                  ." from %s rt left join %s t on rt.teacherid = t.teacherid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }


   
}











