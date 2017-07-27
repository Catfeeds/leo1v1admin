<?php
namespace App\Models;
use \App\Enums as E;
class t_change_teacher_list extends \App\Models\Zgen\z_t_change_teacher_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_ass_change_teacher_info($start_time,$end_time,$adminid,$page_num,$id,$require_adminid,$accept_adminid=-1,$accept_flag=-1,$commend_type=1){
        $where_arr=[
            ["ch.ass_adminid=%u",$adminid,-1],
            ["ch.ass_adminid=%u",$require_adminid,-1],
            ["ch.id=%u",$id,-1],
            ["ch.accept_adminid=%u",$accept_adminid,-1],
            ["ch.commend_type=%u",$commend_type,-1],
            ["ch.accept_flag=%u",$accept_flag,-1]
        ];
        $this->where_arr_add_time_range($where_arr,"ch.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select ch.id,ch.add_time,ch.ass_adminid,ch.userid,ch.teacherid,ch.change_reason,ch.except_teacher,ch.subject,ch.grade,ch.textbook,ch.phone_location,ch.stu_score_info ,ch.stu_character_info,ch.record_teacher,ch.accept_reason,ch.accept_flag,ch.accept_adminid ,ch.accept_time,t.realname,s.nick,m.account,ch.change_reason_url,ch.commend_teacherid,tt.realname commend_realname,change_teacher_reason_type,mm.account accept_account,ch.is_done_flag,ch.done_time,ch.is_resubmit_flag "
                                  ." from %s ch left join %s t on ch.teacherid= t.teacherid"
                                  ." left join %s s on ch.userid = s.userid"
                                  ." left join %s m on m.uid= ch.ass_adminid"
                                  ." left join %s tt on ch.commend_teacherid= tt.teacherid"
                                  ." left join %s mm on mm.uid= ch.accept_adminid"
                                  ." where %s order by ch.add_time desc",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr                                 
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function check_is_exist($teacherid,$userid,$subject,$commend_type=1){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            ["userid=%u",$userid,-1],
            ["subject=%u",$subject,-1],
            ["commend_type=%u",$commend_type,-1],
            "accept_time=0"
        ];
        $sql = $this->gen_sql_new("select id from %s where %s ",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_subject_info($subject){
        $sql = $this->gen_sql_new("select  * from %s where subject <> %u and accept_adminid=0 ",self::DB_TABLE_NAME,$subject);
        return $this->main_get_list($sql);

    }

    public function get_seller_require_commend_teacher_info($start_time,$end_time,$adminid,$page_num,$id,$accept_adminid,$accept_adminid_list,$require_adminid,$commend_type=2){
        $where_arr=[
            ["ch.ass_adminid=%u",$adminid,-1],
            ["ch.ass_adminid=%u",$require_adminid,-1],
            ["ch.accept_adminid=%u",$accept_adminid,-1],
            ["ch.commend_type=%u",$commend_type,-1],
            ["ch.id=%u",$id,-1]
        ];
        $this->where_arr_add_time_range($where_arr,"ch.add_time",$start_time,$end_time);
        $where_arr[] = $this->where_get_in_str("ch.accept_adminid",$accept_adminid_list);
        $sql = $this->gen_sql_new("select ch.id,ch.add_time,ch.ass_adminid,ch.userid,ch.change_reason,ch.except_teacher,ch.subject,ch.grade,ch.textbook,ch.phone_location,ch.stu_score_info ,ch.stu_character_info,ch.record_teacher,ch.accept_reason,ch.accept_flag,ch.accept_adminid ,ch.accept_time,s.nick,m.account,ch.change_reason_url,ch.commend_teacherid,mm.account accept_account,ch.stu_request_test_lesson_demand,ch.stu_request_lesson_time_info ,ch.stu_request_test_lesson_time,ch.wx_send_time  "
                                  ." from %s ch "
                                  ." left join %s s on ch.userid = s.userid"
                                  ." left join %s m on m.uid= ch.ass_adminid"
                                  ." left join %s mm on mm.uid= ch.accept_adminid"
                                  ." where %s order by ch.add_time desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr                                 
        );
        return $this->main_get_list_by_page($sql,$page_num);

 
    }

    public function get_no_wx_send_time_list($time,$commend_type){
        $where_arr=[
            ["add_time>%u",$time,-1],
            ["commend_type=%u",$commend_type,-1],
            "wx_send_time=0"
        ];
        $sql = $this->gen_sql_new("select id,ass_adminid,accept_adminid from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
  
    }

    public function get_require_num($accept_adminid,$commend_type,$start_time,$end_time){
        $where_arr=[
            ["accept_adminid=%u",$accept_adminid,-1],
            ["commend_type=%u",$commend_type,-1],
            "accept_flag in (0,1)"
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(*) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_change_teacher_info($change_teacher_reason_type,$start_time,$end_time,$page_num){

        $where_arr = [
            'tc.userid > 0',
            'tl.lesson_type =2 ',
            ' (tc.teacherid >0 or tl.teacherid >0)',
        ];
        if($change_teacher_reason_type != -1){
            $where_arr[] = ["change_teacher_reason_type = %d",$change_teacher_reason_type];

        }
        $this->where_arr_add_time_range($where_arr,"tl.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select tc.userid, tc.teacherid as old_teacherid, tc.is_done_flag, tc.change_teacher_reason_type, tc.subject, tc.grade, tsl.confirm_adminid, tsl.success_flag, tl.lesson_start, tl.teacherid, tl.lesson_end, s.assistantid  from %s tc ".
                                  " left join %s tls on tls.userid = tc.userid ".
                                  " left join %s tlsr on tlsr.test_lesson_subject_id = tls.test_lesson_subject_id".
                                  " left join %s tsl on tsl.require_id = tlsr.require_id".
                                  " left join %s tl on tl.lessonid = tsl.lessonid".
                                  " left join %s s on s.userid = tc.userid".
                                  " where %s   order by tl.lesson_start desc ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_num,30,true);
    }
}











