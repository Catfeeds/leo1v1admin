<?php
namespace App\Models;
use \App\Enums as E;
class t_month_ass_warning_student_info extends \App\Models\Zgen\z_t_month_ass_warning_student_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info_by_month($month,$page_num,$up_master_adminid,$account_id,$leader_flag,$assistantid,$ass_renw_flag,$master_renw_flag,$renw_week,$end_week,$warning_type=1){
        $where_arr=[
            ["month = %u",$month,-1],
            ["a.assistantid = %u",$assistantid,-1],
            ["ass_renw_flag = %u",$ass_renw_flag,-1],
            ["master_renw_flag = %u",$master_renw_flag,-1],
            ["renw_week = %u",$renw_week,-1],
            ["end_week = %u",$end_week,-1],
            ["warning_type = %u",$warning_type,-1],
        ];
        if($up_master_adminid !=-1){
            if($leader_flag==1){
                $where_arr[]=["n.master_adminid = %u",$account_id,-1];
            }else if($leader_flag==0){
                $where_arr[]=["w.adminid = %u",$account_id,-1];
            }
        }
        $sql = $this->gen_sql_new("select w.adminid,w.month,w.userid,w.groupid,w.group_name,w.left_count,w.end_week,w.ass_renw_flag,w.no_renw_reason,w.renw_price,w.renw_week,w.master_renw_flag,w.master_no_renw_reason,s.nick,m.account,w.id from %s w"
                                  ." left join %s s on s.userid = w.userid"
                                  ." left join %s m on w.adminid = m.uid"
                                  ." left join %s n on w.groupid = n.groupid"
                                  ." left join %s a on m.phone = a.phone"
                                  ." where %s and w.left_count>0",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_ass_stu_warning_info($time,$warning_type=1){
        $where_arr=[
            ["warning_type=%u",$warning_type,-1]  
        ];
        $sql = $this->gen_sql_new("select id,userid,m.account from %s w "
                                  ." left join %s m on w.adminid=m.uid"
                                  ." where month = %u and master_renw_flag <>1 and %s",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $time,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_ass_renw_flag_master_by_userid($userid,$month,$warning_type){
        $where_arr=[
            ["warning_type=%u",$warning_type,-1],
            ["userid =%u",$userid,-1],
            ["month = %u",$month,-1]
        ];
        $sql = $this->gen_sql_new("select master_renw_flag "
                                  ."from %s where  %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_stu_warning_info($warning_type=2,$done_flag=0){
        $where_arr=[
            ["warning_type=%u",$warning_type,-1],
            ["done_flag =%u",$done_flag,-1],
        ];
        $sql = $this->gen_sql_new("select * "
                                  ." from %s where  %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["userid"];
        });     

    }

    public function get_end_stu_warning_info(){
        $where_arr=[
            "warning_type=2",
            "done_flag =0",
            "ass_renw_flag=0",
            "s.lesson_count_left <100"
        ];
        $sql = $this->gen_sql_new("select w.id "
                                  ." from %s w left join %s s on w.userid = s.userid"
                                  ." where  %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);     

    }


    public function get_all_info_by_month_new($page_num,$up_master_adminid,$account_id,$leader_flag,$assistantid,$ass_renw_flag,$master_renw_flag,$renw_week,$end_week,$warning_type=2,$adminid,$done_flag,$id){
        $where_arr=[
            ["a.assistantid = %u",$assistantid,-1],
            ["ass_renw_flag = %u",$ass_renw_flag,-1],
            ["master_renw_flag = %u",$master_renw_flag,-1],
            ["renw_week = %u",$renw_week,-1],
            ["end_week = %u",$end_week,-1],
            ["warning_type = %u",$warning_type,-1],
            ["w.id = %u",$id,-1],
            // "done_flag=0"
        ];
        if($done_flag==-2){
            $where_arr[]="done_flag>0";
        }else{
            $where_arr[]=["done_flag = %u",$done_flag,-1]; 
        }
        if($up_master_adminid !=-1){
            if($leader_flag==1){
                $where_arr[]=["n.master_adminid = %u",$adminid,-1];
            }else if($leader_flag==0){
                $where_arr[]=["w.adminid = %u",$account_id,-1];
            }
        }
        if($leader_flag==0){
            $where_arr[]="done_flag=0";
        }
        $sql = $this->gen_sql_new("select w.adminid,w.month,w.userid,w.groupid,w.group_name,s.lesson_count_left left_count,w.end_week,w.ass_renw_flag,w.no_renw_reason,w.renw_price,w.renw_week,w.master_renw_flag,w.master_no_renw_reason,s.nick,m.account,w.id from %s w"
                                  ." left join %s s on s.userid = w.userid"
                                  ." left join %s n on w.groupid = n.groupid"
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_week_warning_info($lstart){
        $where_arr=[
            "warning_type =1",
            ["month=%u",$lstart,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_warning_info_by_userid($userid,$month){
        $where_arr=[
            "warning_type =2",
            ["month>=%u",$month,-1],
            ["userid=%u",$userid,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_row($sql);

    }

    public function get_done_info(){
        $where_arr=[
            "warning_type =2",
            "done_flag >0"
        ];
        $sql = $this->gen_sql_new("select  userid,id from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);

    }

    public function get_no_renw_end_time_list($time){
        $where_arr=[
            "m.warning_type=2",
            "m.done_flag=0",
            "m.ass_renw_flag in (1,3)",
            "m.master_renw_flag<>1",
            "a.renw_end_day =".$time
        ];
        $sql = $this->gen_sql_new("select m.id,m.userid,a.add_time,a.renw_end_day,m.month "
                                  ." from %s m left join %s a on (m.id = a.warning_id and a.add_time = (select max(add_time) from %s where warning_id = a.warning_id))"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_ass_warning_renw_flag_modefiy_list::DB_TABLE_NAME,
                                  t_ass_warning_renw_flag_modefiy_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_done_stu_info_seller( $start_time, $end_time,$all_flag, $userid,$grade, $status,
                                                       $user_name, $phone, $teacherid, $assistantid, $test_user,
                                                       $originid, $seller_adminid,$ass_adminid_list=[],$student_type
){
        //  $last_two_weeks_time = time(NULL)-86400*14;
        $where_arr=[
            ["s.userid=%u", $userid, -1] ,
            ["s.grade=%u", $grade, -1] ,
            ["s.status=%u", $status, -1] ,
            ["s.type=%u",$student_type,-1],
            ["s.assistantid=%u", $assistantid, -1] ,
            ["s.is_test_user=%u ", $test_user , -1] ,
            ["s.originid=%u ", $originid , -1] ,
            ["s.seller_adminid=%u ", $seller_adminid, -1] ,
            "m.warning_type=2",
            "m.done_flag=2",
            // "(s.type <>1 or s.last_lesson_time<".$last_two_weeks_time.")"
        ];
        $this->where_arr_add_time_range($where_arr,"s.last_lesson_time",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"mm.uid", $ass_adminid_list );
        if ($user_name) {
            $where_arr[]=sprintf( "(s.nick like '%s%%' or s.realname like '%s%%' or  s.phone like '%s%%' )",
                                  $this->ensql($user_name),
                                  $this->ensql($user_name),
                                  $this->ensql($user_name));
        }
      
        $sql =$this->gen_sql_new("select distinct m.userid "
                                 ." from %s m left join %s s on m.userid = s.userid"
                                 ." left join %s a on s.assistantid = a.assistantid"
                                 ." left join %s mm on a.phone = mm.phone"
                                 ." where %s",
                                 self::DB_TABLE_NAME,
                                 t_student_info::DB_TABLE_NAME,
                                 t_assistant_info::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_last_time_by_userid($userid){
        $sql = $this->gen_sql_new("select max(month) from %s where warning_type=2 and userid = %u",
                                  self::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_value($sql);
    }
}











