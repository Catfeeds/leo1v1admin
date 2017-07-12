<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_and_ass_record_list extends \App\Models\Zgen\z_t_seller_and_ass_record_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function check_is_exist($lessonid){
        $sql = $this->gen_sql_new("select id from %s where lessonid = %u and is_done_flag <>2",self::DB_TABLE_NAME,$lessonid);
        return $this->main_get_value($sql);
    }

    public function get_seller_ass_record_info($start_time,$end_time,$adminid,$page_num,$id,$accept_adminid,$accept_adminid_list=[],    $require_adminid=-1){
        $where_arr=[
            ["sa.adminid=%u",$adminid,-1],
            ["sa.adminid=%u",$require_adminid,-1],
            ["sa.accept_adminid=%u",$accept_adminid,-1],
            ["sa.id=%u",$id,-1]
        ];
        $this->where_arr_add_time_range($where_arr,"sa.add_time",$start_time,$end_time);
        $where_arr[] = $this->where_get_in_str("sa.accept_adminid",$accept_adminid_list);
        $sql = $this->gen_sql_new("select sa.id,sa.add_time,sa.adminid,sa.userid,sa.teacherid,sa.subject,sa.grade,sa.textbook,sa.stu_score_info ,sa.stu_character_info,sa.type,sa.lessonid,sa.record_info,sa.record_info_url,sa.stu_request_test_lesson_demand ,sa.record_scheme ,sa.accept_adminid ,sa.accept_time,t.realname,s.nick,m.account,sa.record_scheme_url,sa.is_change_teacher ,sa.tea_time,sa.is_done_flag,sa.done_time,sa.is_resubmit_flag,mm.account accept_account,add_type"
                                  ." from %s sa left join %s t on sa.teacherid= t.teacherid"
                                  ." left join %s s on sa.userid = s.userid"
                                  ." left join %s m on m.uid= sa.adminid"
                                  ." left join %s mm on mm.uid= sa.accept_adminid"
                                  ." where %s order by sa.add_time desc",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr                                 
        );
        return $this->main_get_list_by_page($sql,$page_num);
 
    }

    public function get_seller_and_ass_record_by_account($start_time,$end_time){
        $where_arr=[
            "accept_time>0"
        ]; 
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select sum(accept_time -add_time) deal_time,count(*) num,accept_adminid "
                                  ." from %s where %s group by accept_adminid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }

    public function get_seller_and_ass_record_by_subject($start_time,$end_time){
        $where_arr=[
            "accept_time>0"
        ]; 
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select sum(accept_time -add_time) deal_time,count(*) num,subject "
                                  ." from %s where %s group by subject",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });
    }

}











