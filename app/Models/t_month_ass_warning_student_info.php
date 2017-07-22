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

    public function get_all_info_by_month_new($page_num,$up_master_adminid,$account_id,$leader_flag,$assistantid,$ass_renw_flag,$master_renw_flag,$renw_week,$end_week,$warning_type=2){
        $where_arr=[
            ["a.assistantid = %u",$assistantid,-1],
            ["ass_renw_flag = %u",$ass_renw_flag,-1],
            ["master_renw_flag = %u",$master_renw_flag,-1],
            ["renw_week = %u",$renw_week,-1],
            ["end_week = %u",$end_week,-1],
            ["warning_type = %u",$warning_type,-1],
            "done_flag=0"
        ];
        if($up_master_adminid !=-1){
            if($leader_flag==1){
                $where_arr[]=["n.master_adminid = %u",$account_id,-1];
            }else if($leader_flag==0){
                $where_arr[]=["w.adminid = %u",$account_id,-1];
            }
        }
        $sql = $this->gen_sql_new("select w.adminid,w.month,w.userid,w.groupid,w.group_name,s.lesson_count_left left_count,w.end_week,w.ass_renw_flag,w.no_renw_reason,w.renw_price,w.renw_week,w.master_renw_flag,w.master_no_renw_reason,s.nick,m.account,w.id from %s w"
                                  ." left join %s s on s.userid = w.userid"
                                  ." left join %s m on w.adminid = m.uid"
                                  ." left join %s n on w.groupid = n.groupid"
                                  ." left join %s a on m.phone = a.phone"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

}











