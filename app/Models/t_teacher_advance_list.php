<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_advance_list extends \App\Models\Zgen\z_t_teacher_advance_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_info_by_time($page_info,$start_time,$teacher_money_type,$teacherid,$accept_flag,$fulltime_flag=-1){
        $where_arr=[
            ["start_time = %u",$start_time,0],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            ["a.teacherid = %u",$teacherid,-1],
            ["a.accept_flag = %u",$accept_flag,-1],
            "a.require_time>0",
            "m.account_role not in (4,9) or m.account_role is null"
        ];
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role <> 5 or m.account_role is null)"; 
        }elseif($fulltime_flag==1){
            $where_arr[] = "m.account_role =5";
        }
        /*elseif($fulltime_flag==2){           
            $where_arr[] = "m.account_role =5 and fulltime_teacher_type=2";
            }*/
        $sql = $this->gen_sql_new("select a.*,t.realname,m.create_time become_member_time "
                                  ." from %s a left join %s t on a.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_info_by_time_new($page_info,$teacher_money_type,$teacherid,$accept_flag,$fulltime_flag=-1){
        $where_arr=[
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            ["a.teacherid = %u",$teacherid,-1],
            ["a.accept_flag = %u",$accept_flag,-1],
            'm.account_role in (4,9)',
            //  'm.del_flag=0'
        ];
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role <> 5 or m.account_role is null)"; 
        }elseif($fulltime_flag==1){
            $where_arr[] = "m.account_role =5 and fulltime_teacher_type=1";
        }elseif($fulltime_flag==2){           
            $where_arr[] = "m.account_role =5 and fulltime_teacher_type=2";
        }
        $sql = $this->gen_sql_new("select a.*,"
                                  ."t.realname,"
                                  ."m.create_time become_member_time "
                                  ." from %s a "
                                  ."left join %s t on a.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_advance_success_list($start_time){
        $where_arr=[
            ["start_time = %u",$start_time,0],
            "accept_flag =1"
        ];
        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_hand_add_list($start_time,$hand_flag,$fulltime_flag,$fulltime_teacher_type=-1){
        $where_arr=[
            ["ta.start_time = %u",$start_time,0],
            ["ta.hand_flag = %u",$hand_flag,0],
            ["m.fulltime_teacher_type=%u",$fulltime_teacher_type,-1]
        ];
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role <> 5 or m.account_role is null)";
        }elseif($fulltime_flag==1){
             $where_arr[] = "m.account_role =5";
        }
        $sql = $this->gen_sql_new("select ta.* "
                                  ." from %s ta left join %s t on ta.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

 
    }

}











