<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_lecture_appointment_info_b2 extends \App\Models\Zgen\z_t_teacher_lecture_appointment_info
{
    public function __construct()
    {
        parent::__construct();
    }

    // 拉取招师人员名单(根据老师名拉取对应的招师人员)
    public function get_name_for_tea_name($name)
    {
        $where_arr = [
            ["ta.name= '%s' ",$name, ''],
            "ta.subject>0"
        ];
        $sql=$this->gen_sql_new("select ta.accept_adminid,tf.subject,tf.grade "
                                ." from %s ta left join %s tf on ta.phone=tf.phone "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,t_teacher_flow::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_name_data() { // 招师名单
        $sql=$this->gen_sql_new("select uid,name from %s where account_role=8",t_manager_info::DB_TABLE_NAME);
        return $this->main_get_list($sql, function( $item) {
            return $item['uid'];
        });
    }

    //
    public function get_teacher_list($start_time, $end_time){
        $where_arr = [
            ['l.lesson_start>%u',$start_time,0],
            ['l.lesson_start<%u',$end_time,0],
            "t.user_agent!='' ",
            "t.is_test_user=0",
            "l.lesson_type in (0,1,3) " // 常规课
        ];
        // 拉取所有数据
        $sql = $this->gen_sql_new("select t.teacherid,t.realname,t.user_agent,t.phone "
                                  ."from %s t left join %s l "
                                  ."on t.teacherid=l.teacherid where %s ",
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function( $item) {
            return $item['teacherid'];
        });
    }

    public function get_student_list($start_time, $end_time) {
        $where_arr = [
            ['l.lesson_start>%u',$start_time,0],
            ['l.lesson_start<%u',$end_time,0],
            "s.user_agent!='' ",
            "s.is_test_user=0",
            "l.lesson_type in (0,1,3) " // 常规课
        ];

        $sql = $this->gen_sql_new("select s.userid,s.nick,s.realname,s.user_agent,s.phone,l.assistantid "
                                  ."from %s s left join %s l on s.userid=l.userid where %s ",
                                  t_student_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function( $item) {
            return $item['userid'];
        });
    }

    public function get_assistant_info() {
        $sql = $this->gen_sql_new("select assistantid,nick from %s",t_assistant_info::DB_TABLE_NAME);
        return $this->main_get_list($sql, function( $item) {
            return $item['assistantid'];
        });
    }

    public function get_ref_type_data() {
        //select nick,teacher_type,teacher_ref_type from t_teacher_info where (teacher_type=21 or teacher_type=22) and (teacher_ref_type=1 or teacher_ref_type=2)
        $sql = $this->gen_sql_new("select nick,teacher_type,teacher_ref_type from %s where (teacher_type=21 or teacher_type=22) and (teacher_ref_type=1 or teacher_ref_type=2) ", t_teacher_info::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_info_for_cc($start_time, $end_time){
        $where = [
            'seller_student_status>=100',
            'seller_student_status<200',
            //['r.require_time>=%d',$start_time,0],
            //['r.require_time<%d',$end_time,0]
        ];
        //select r.require_id,r.cur_require_adminid,s.userid,s.seller_student_status,o.orderid,o.order_time from t_test_lesson_subject_require r left join t_test_lesson_subject s on s.test_lesson_subject_id = r.test_lesson_subject_id left join t_order_info o on o.userid=s.userid where seller_student_status>=100 and seller_student_status<200 and r.require_time >= unix_timestamp('2017-11-1') and r.require_time < unix_timestamp('2017-12-1')
        $sql = $this->gen_sql_new("select r.require_id,r.cur_require_adminid uid,s.userid,s.seller_student_status status,o.orderid,o.order_time from %s r left join %s s on s.test_lesson_subject_id = r.test_lesson_subject_id left join %s o on o.userid=s.userid where %s",
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where
        );
        return $this->main_get_list($sql);
    }

    public function get_manager_info() {
        $sql = $this->gen_sql_new("select uid,name,account from %s where account_role=2", t_manager_info::DB_TABLE_NAME);
        return $this->main_get_list($sql, function($item) {
            return $item['uid'];
        });
    }

    public function get_money_list($start_time, $end_time) {
        //select recommended_teacherid,t.nick  from t_teacher_money_list l left join t_teacher_info t on t.teacherid=l.recommended_teacherid  where l.teacherid=149697 and l.type=6
        $where_arr = [
            "l.teacherid=149697",
            "l.type=6",
            "t.train_through_new_time>0",
            ["train_through_new_time>=%u", $start_time, 0],
            ["train_through_new_time<%u", $end_time, 0]
        ];
        $sql = $this->gen_sql_new("select recommended_teacherid,t.nick from %s l left join %s t on t.teacherid=l.recommended_teacherid where %s",
                                  t_teacher_money_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function($item) {
            return $item['recommended_teacherid'];
        });
    }

    public function get_money_list1($start_time, $end_time) { 
        //select teacherid,name from t_teacher_info t left join t_teacher_lecture_appointment_info ta on t.phone=ta.phone where ta.reference ='15366667766' and t.train_through_new_time  > 0 and train_through_new_time >= unix_timestamp('2017-11-1') and unix_timestamp('2017-12-1')
        $where_arr = [
            "ta.reference='15366667766' ",
            "t.train_through_new_time>0",
            ["train_through_new_time>=%u", $start_time, 0],
            ["train_through_new_time<%u", $end_time, 0]
        ];
        $sql = $this->gen_sql_new("select teacherid,name from %s t left join %s ta on t.phone=ta.phone where %s",
                                  t_teacher_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function($item) {
            return $item['teacherid'];
        });
    }

    public function get_money_list2($start_time, $reference, $identity) {
        $where_arr = [
            "ta.reference='$reference'",
            ["t.train_through_new_time>=%u", $start_time, -1],
            "t.identity='$identity'"
        ];
        $sql = $this->gen_sql_new("select teacherid,name from %s t left join %s ta on t.phone=ta.phone where %s",
                                  t_teacher_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function($item) {
            return $item["teacherid"];
        });
    }
}