<?php
namespace App\Models;
use \App\Enums as E;
class t_student_score_info extends \App\Models\Zgen\z_t_student_score_info
{
	public function __construct()
	{
		parent::__construct();
	}
	public function get_list($page_info,$user_id){
		  $sql = $this->gen_sql("select * from %s where userid = %d and status = 0 order by create_time desc ",
                              self::DB_TABLE_NAME,
                              $user_id);
        return $this->main_get_list_by_page($sql,$page_info);
	}
  public function set_every_month_student_score($time)
  {
       $where_str=$this->where_str_gen([
            ["t1.competition_flag=%u",0, -1 ] ,
            "t1.course_type=0"  ,
        ]);
        $sql=$this->gen_sql("select t1.userid,t1.courseid,t1.grade,t1.subject,t1.lesson_grade_type,"
                            ."sum(t2.lesson_count) as lesson_count, "
                            ."sum(case when lesson_status=0 then lesson_count else 0 end )  as no_finish_lesson_count , "
                            ."sum(case when lesson_status=0 then 0 else "
                            ."(case when confirm_flag in (2,4) then 0 else t2.lesson_count end) "
                            ."end ) as finish_lesson_count,t1.add_time, "
                            ." t1.assistantid,t1.course_type, "
                            ."course_status "
                            ." from %s t1 "
                            ." left join %s t2 on t1.courseid = t2.courseid "
                            ." where %s "
                            ." and (lesson_del_flag=0 or lesson_del_flag is null)"
                            ." and t1.assistantid != 0"
                            ." group by t1.courseid having no_finish_lesson_count > 0 order by t1.userid desc, t1.subject desc "
                            ,t_course_order::DB_TABLE_NAME
                            ,t_lesson_info::DB_TABLE_NAME
                            ,[$where_str]
        );
       //dd($sql);
       $ret_info = $this->main_get_list($sql);
       $arr = [];
       foreach($ret_info as $key => $value){
           $arr[$key] = $ret_info[$key]['userid'].','.$ret_info[$key]['subject'].','.$ret_info[$key]['grade'];
       }
       $arr = array_unique($arr);
       $arr = array_merge($arr);
       foreach($arr as $key => $value)
       {
           $res = explode(',',$arr[$key]);
           $userid = intval($res[0]);
           $create_time = time();
           $create_adminid = 99;
           $subject = intval($res[1]);
           $stu_score_type = 1;
           $grade = intval($res[2]);
           if(date("M",$time) < 9 && date("M",$time) > 2){
               $semester = 1;
           }else{
               $semester = 0;
           }
           $status = 2; //待补充
           $ret = $this->t_student_score_info->row_insert([
                   "userid"                => $userid,     //
                   "create_time"           => $create_time,
                   "create_adminid"        => $create_adminid,
                   "subject"               => $subject,    //
                   "stu_score_type"        => $stu_score_type,
                   "semester"              => $semester,   
                   "grade"                 => $grade,      //
                   "status"                => $status,
               ],false,false,true);
               if($ret){
                   //echo "success<br/>";
               }
        }
    }
}