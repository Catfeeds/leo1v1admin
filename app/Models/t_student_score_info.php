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
    public function get_all_list($page_info,$username,$grade,$semester,$stu_score_type,$is_test_user  ){
        $where_arr = [
            [" u.realname= '%s'",$username,''],
            [" s.grade = %d ",$grade,-1],
            [" s.semester = %d ",$semester,-1],
            [" s.stu_score_type = %d ",$stu_score_type,-1],
            [" u.is_test_user = %d ",$is_test_user,-1],
            "s.status = 0",
        ];
        $sql = $this->gen_sql_new(" select s.userid,s.create_time,s.create_adminid,s.subject,"
                                  ."s.stu_score_type,s.stu_score_time,s.score,s.total_score,s.rank,s.semester,"
                                  ."s.total_score,s.grade,s.grade_rank,s.status,s.month,s.rank_up,s.rank_down, "
                                  ."u.realname,u.school,m.name,u.nick "
                                  ." from      %s s "
                                  ." left join %s u on s.userid         = u.userid "
                                  ." left join %s m on s.create_adminid = m.uid "
                                  ." where %s order by s.create_time desc ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME, //学生表userid
                                  t_manager_info::DB_TABLE_NAME, //管理员表uid
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_last_grade_rank($subject,$userid){
        $sql = $this->gen_sql(" select grade_rank "
                              ." from %s "
                              ."  where subject = $subject and userid = $userid order by create_time desc limit 1",
                              self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
    public function get_last_grade_rank_b1($subject,$userid,$create_time){
        $sql = $this->gen_sql(" select grade_rank "
                              ." from %s "
                              ."  where subject = $subject and userid = $userid and create_time < $create_time order by create_time desc limit 1",
                              self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
    public function get_last_grade_rank_b2($subject,$userid,$create_time){
        $sql = $this->gen_sql(" select * "
                              ." from %s "
                              ."  where subject = $subject and userid = $userid and create_time > $create_time order by create_time asc limit 1",
                              self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
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
        return $this->main_get_list($sql);
    }

    public function get_score_info_for_parent($parentid,$userid){
        $where_arr = [
            ["create_adminid = %d",$parentid,-1],
            ["userid = %d ",$userid,-1]
        ];

        $sql = $this->gen_sql_new(" select subject, stu_score_type, score, grade_rank from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_stu_score_list_for_score_type($parentid,$stu_score_list){
        $where_arr = [
            ['p.parentid=%d',$parentid,-1],
            ['stu_score_type=%d',$stu_score_type,-1]
        ];

        $sql = $this->gen_sql_new("  select id as scoreid, stu_score_type, score, total_score, subject from %s tc"
                                  ." left join %s p on p.userid = tc.userid where %s",
                                  self::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_score_info($id){
        $sql = $this->gen_sql_new(" select grade, total_score, stu_score_type, score, grade_rank, rank from %s where id = %d "
                                  ,self::DB_TABLE_NAME
                                  ,$id
        );

        return $this->main_get_list($sql);
    }
    public function get_is_status($userid,$create_time){
        $where_arr = [
            ['create_time>=%u',$create_time,-1],
            ['userid=%u',$userid,-1],
            'status=0',
        ];

        $sql = $this->gen_sql_new("  select count(id) as num from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }
}