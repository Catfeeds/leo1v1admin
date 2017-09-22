<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_feedback_list extends \App\Models\Zgen\z_t_teacher_feedback_list
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_teacher_feedback_list($start_time,$end_time,$teacherid,$assistantid,$accept_adminid,
                                              $lessonid,$status,$feedback_type,$page_num,$opt_date_type,$del_flag
    ){
        if($lessonid>0){
            $where_arr = [
                ["tf.lessonid=%u",$lessonid,0],
            ];
        }else{
            if($opt_date_type=="add_time"){
                $time_str = "tf.add_time";
            }else{
                $time_str = "l.lesson_start";
            }
            $where_arr = [
                ["$time_str>%u",$start_time,0],
                ["$time_str<%u",$end_time,0],
                ["tf.teacherid=%u",$teacherid,-1],
                ["status=%u",$status,-1],
                ["tf.del_flag=%u",$del_flag,-1],
            ];
            if($feedback_type==-2){
                $where_arr[] = "feedback_type<200";
            }else{
                $where_arr[] = ["feedback_type=%u",$feedback_type,-1];
            }
        }
        $where_arr[] = ["l.assistantid=%u",$assistantid,-1];
        $where_arr[] = ["tls.accept_adminid=%u",$accept_adminid,-1];
        if($assistantid>0 || $accept_adminid>0){
            $where_arr[] = " feedback_type in (201,202,203,204,205) ";
        }

        $sql = $this->gen_sql_new("select tf.id,tf.teacherid,tf.lessonid,l.lesson_count,status,feedback_type,"
                                  ." t.nick,l.lesson_start,l.lesson_end,l.userid,tf.add_time,tf.sys_operator,tf.check_time,"
                                  ." l.deduct_come_late,l.deduct_check_homework,l.deduct_change_class,l.deduct_rate_student,"
                                  ." l.deduct_upload_cw,l.grade,t.teacher_money_type,t.level,tf.del_flag,"
                                  ." tea_reason,back_reason"
                                  ." from %s tf"
                                  ." left join %s t on tf.teacherid=t.teacherid"
                                  ." left join %s l on tf.lessonid=l.lessonid"
                                  ." left join %s tl on l.lessonid=tl.lessonid"
                                  ." left join %s tls on tl.require_id=tls.require_id"
                                  ." where %s"
                                  ." order by id desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_admin_list($status=0){
        $where_arr=[
            ["status=%u",$status,-1],
            "tf.del_flag = 0"
        ];
        $sql=$this->gen_sql_new("select tf.id,tf.lessonid,assistantid,accept_adminid,feedback_type"
                                ." from %s tf"
                                ." left join %s tl on tf.lessonid=tl.lessonid"
                                ." left join %s tls on tl.require_id=tls.require_id"
                                ." left join %s l on tf.lessonid=l.lessonid"
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                ,t_test_lesson_subject_require::DB_TABLE_NAME
                                ,t_lesson_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_feedback_list ($teacherid, $lessonid){
        $sql = $this->gen_sql_new("select id, teacherid, lessonid, status, lesson_count, tea_reason, back_reason, add_time, check_time, feedback_type from %s where teacherid = %d && lessonid = %d",
                                      self::DB_TABLE_NAME, $teacherid, $lessonid);
        return $this->main_get_list($sql);

    }

    public function get_feedback_count($teacherid, $lessonid, $feedback_type){
        $sql = $this->gen_sql_new("select count(1) from %s where teacherid = %d and lessonid = %d and feedback_type = %d",
                                  self::DB_TABLE_NAME, $teacherid, $lessonid, $feedback_type);
        return $this->main_get_value($sql);
    }

    public function set_feedback( $teacherid, $lessonid, $feedback_type, $lesson_count, $tea_reason, $add_time){
        $sql = $this->gen_sql_new( "insert into %s (teacherid ,lessonid, feedback_type, lesson_count ,tea_reason, add_time)".
                                   " VALUES (%d, %d, %d, %d, '%s', %d) ",
                                   self::DB_TABLE_NAME, $teacherid, $lessonid, $feedback_type, $lesson_count, $tea_reason, $add_time);
        return $this->main_update($sql);
    }

}
