<?php
namespace App\Models;
use \App\Enums as E;
class t_test_lesson_require_teacher_list extends \App\Models\Zgen\z_t_test_lesson_require_teacher_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_test_lesson_require_teacher_info($page_info,$start_time,$end_time,$teacherid){
        $where_arr=[
            "test_lesson_student_status=200",
            ["teacherid=%u",$teacherid,-1]
        ];
        $sql = $this->gen_sql_new("select rt.require_id,rt.add_time,rt.id,rt.teacher_info,rt.teacherid, "
                                  ." stu_score_info, stu_character_info , s.school, s.editionid, stu_test_lesson_level,"
                                  ." stu_test_ipad_flag, stu_request_lesson_time_info,  stu_request_test_lesson_time_info, "
                                  ."  test_lesson_student_status,  s.userid,s.nick, tr.origin, ss.phone_location,"
                                  ." ss.phone,ss.userid, t.require_adminid,  tr.curl_stu_request_test_lesson_time stu_request_test_lesson_time ,  test_stu_request_test_lesson_demand as  stu_request_test_lesson_demand , "
                                  ." s.origin_assistantid , s.origin_userid  ,  t.subject, tr.test_stu_grade as grade,ss.user_desc, ss.has_pad, ss.last_revisit_time,"
                                  ." ss.last_revisit_msg,tq_called_flag,next_revisit_time,"
                                  ." t.stu_test_paper, t.tea_download_paper_time, test_lesson_student_status,"
                                  ." tr.seller_require_change_flag,tr.require_change_lesson_time,"
                                  ." tr.seller_require_change_time , tr.accept_adminid,tr.require_time,"
                                  ." jw_test_lesson_status,t.textbook,tr.cur_require_adminid,"
                                  ." tr.grab_status,tr.is_green_flag "
                                  ." from %s rt left join %s tr on rt.require_id = tr.require_id "
                                  ." left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." left join %s ss on t.userid = ss.userid"
                                  ." left join %s s on t.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME, //tr
                                  t_test_lesson_subject::DB_TABLE_NAME, //t
                                  t_seller_student_new::DB_TABLE_NAME, //ss
                                  t_student_info::DB_TABLE_NAME, //s
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function del_test_lesson_require_teacher_info($start_time,$end_time){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("delete from %s where %s",                                
                                self::DB_TABLE_NAME,
                                $where_arr  ); 
        return $this->main_update($sql);        
    }

}











