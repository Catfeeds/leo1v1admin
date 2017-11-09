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
}