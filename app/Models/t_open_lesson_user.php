<?php
namespace App\Models;
class t_open_lesson_user extends \App\Models\Zgen\z_t_open_lesson_user
{
	public function __construct()
	{
		parent::__construct();
	}
    public function delete_open_lesson_user($lessonid, $userid)
    {
        $sql = sprintf("delete from %s where lessonid = %u and userid = %u",
                       self::DB_TABLE_NAME,
                       $lessonid,
                       $userid
        );
        return $this->main_update($sql);
    }
    public function get_open_class_user($lessonid, $userid)
    {
        $where_arr=[
            ["lessonid=%u",$lessonid,0],
            ["userid=%u",$userid,0],
        ];
        $sql = $this->gen_sql_new("select * from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }
    public function add_open_class_user($lessonid, $userid)
    {
        $sql = sprintf("insert into %s (lessonid, userid, join_time) values(%u, %u, %u) ",
                       self::DB_TABLE_NAME,
                       $lessonid,
                       $userid,
                       time(NULL)
        );
        $this->main_insert($sql);
    }

    public function get_open_list_from_userid($userid) {

        $sql=$this->gen_sql("select from_unixtime(t1.join_time) as join_time, t2.lessonid, lesson_start,lesson_end , t2.courseid,t3.course_name,t3.teacherid ,t3.assistantid from  %s  t1, %s t2 , %s t3".
                            " where t1.lessonid=t2.lessonid and  t2.courseid=t3.courseid  and t1.userid=%u "
                            ." and lesson_del_flag=0 "
                            ,
                            self::DB_TABLE_NAME,
                            t_lesson_info::DB_TABLE_NAME,
                            t_course_order::DB_TABLE_NAME,
                            $userid);
        return $this->main_get_list($sql);
    }

    public function check_lesson_has($lessonid,$userid=0){
        $where_arr=[
            ['userid=%u',$userid,0],
        ];
        $sql=$this->gen_sql_new("select count(1) from %s "
                                ." where lessonid =%u and %s"
                                ,self::DB_TABLE_NAME
                                ,$lessonid
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_user_list($courseid){
        $sql=$this->gen_sql_new("select o.userid,s.phone "
                                ." from %s l "
                                ." left join %s o on l.lessonid=o.lessonid"
                                ." left join %s s on o.userid=s.userid"
                                ." where l.courseid=%u"
                                ." and lesson_del_flag=0 "
                                ." group by o.userid"
                                ,t_lesson_info::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$courseid
        );
        return $this->main_get_list($sql);
    }
    
    public function delete_open_user($courseid,$userid){
        $sql=$this->gen_sql_new("delete from %s "
                                ." where lessonid in ("
                                ." select lessonid from %s where courseid =%u and lesson_type=1001"
                                .") and userid =%u"
                                ,self::DB_TABLE_NAME
                                ,t_lesson_info::DB_TABLE_NAME
                                ,$courseid
                                ,$userid
        );
        return $this->main_update($sql);
    }

    public function get_all_user($lessonid){
        $sql=$this->gen_sql_new("select userid from %s where lessonid=%u"
                                ,self::DB_TABLE_NAME
                                ,$lessonid
        );
        return $this->main_get_list($sql);
    }

    public function delete_open_lesson_by_lessonid($lessonid)
    {
        $sql = sprintf("delete from %s where lessonid = %u ",
                       self::DB_TABLE_NAME,
                       $lessonid
        );
        return $this->main_update($sql);
    }

}