<?php
namespace App\Models;
use \App\Enums as E;
/**
 * 
 
 * @property t_admin_users $t_admin_users
* @property  t_seller_student_info  	 $t_seller_student_info
 * @property  t_lesson_info  	 $t_lesson_info
 */
class t_test_lesson_log_list extends \App\Models\Zgen\z_t_test_lesson_log_list
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_test_lesson_info($phone) {
        $sql=$this->gen_sql(
            "select s.phone, s.phone_location, s.test_lesson_bind_adminid,  ".
            "l.userid,s.nick,s.st_application_time,s.st_class_time,l.lessonid,l.teacherid, ".
            "lesson_start,lesson_end,s.grade,s.subject, st_application_nick,user_desc,st_demand ".
            " from %s s ,%s l where s.st_arrange_lessonid = l.lessonid and s.phone='%s'",
            t_seller_student_info::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $phone);
        return $this->main_get_row($sql);
    }

    public function  check_test_lesson_existed($userid, $teacherid,$subject,$lesson_start) {
        $sql=$this->gen_sql("select count(1)  from  %s  where    ".
                            "userid=%u and teacherid=%u and subject=%u and lesson_start=%u " ,
                            self::DB_TABLE_NAME,
                            $userid,
                            $teacherid,
                            $subject,
                            $lesson_start);
        return $this->main_get_value($sql) >=1;
    }

    public  function add_log($phone) {
        $log_data=$this->get_test_lesson_info($phone);
        if ($log_data) {
            $ret=$this->check_test_lesson_existed(
                $log_data["userid"],
                $log_data["teacherid"],
                $log_data["subject"],
                $log_data["lesson_start"]);
            if(!$ret) {
                $log_data["log_time"]=time(NULL);
                $st_application_nick=$log_data["st_application_nick"];
                $st_application_id=$this->t_admin_users->get_id_by_account($st_application_nick);
                unset($log_data["st_application_nick"]); 
                $log_data["st_application_id"]= $st_application_id;
               
                $this->row_insert($log_data);
            }
        }


    }
    public function get_log_list( $page_num,$userid, $start_time,$end_time, $teacherid,$st_application_id,$subject ,$phone,$test_lesson_status,$del_flag ,$date_type_str) {

        $where_arr=[
            ["userid=%u", $userid, -1] ,
            ["subject=%u", $subject, -1] ,
            ["teacherid=%u", $teacherid, -1] ,
            ["phone like '%s%%' ", $phone, ""] ,
            ["st_application_id=%u", $st_application_id, -1] ,
            ["del_flag=%u", $del_flag, -1] ,
        ];

        if (!$phone) {
            $where_arr[]=["$date_type_str>=%u", $start_time, -1] ;
            $where_arr[]=["$date_type_str<%u", $end_time, -1]; 
        }

        if ($test_lesson_status==-2) {
            $where_arr[]= "test_lesson_status not in (0,1)";
        }else{
            $where_arr[]= ["test_lesson_status=%u", $test_lesson_status, -1] ;
        }

       $sql=$this->gen_sql_new("select *  from  %s  where   %s order by  $date_type_str asc  ",
                           self::DB_TABLE_NAME, $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function update_test_lesson_status($userid,$lessonid, $lesson_start,$test_lesson_status )  {
        $sql=$this->gen_sql("update %s set test_lesson_status=%u"
                            ." where userid=%u and lessonid=%u and lesson_start=%u",
                            self::DB_TABLE_NAME,
                            $test_lesson_status,
                            $userid,
                            $lessonid,
                            $lesson_start
        );
        return $this->main_update($sql);
    }
}











