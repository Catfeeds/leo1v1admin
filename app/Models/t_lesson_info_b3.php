<?php

namespace App\Models;
use App\Models\Zgen as Z;
use \App\Models as M;
use \App\Enums as E;

class t_lesson_info_b3 extends \App\Models\Zgen\z_t_lesson_info{
    public function get_open_lesson_list(){
        $start = strtotime("2017-9-1");
        $end   = strtotime("2017-10-1");
        $where_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            "teacherid in (55161,176999)",
            "lesson_type=1001",
        ];
        $sql = $this->gen_sql_new("select lessonid,grade"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_next_day_lesson_info(){
        $next_day_begin = strtotime(date('Y-m-d',strtotime("+1 days")));
        $next_day_end   = strtotime(date('Y-m-d',strtotime("+2 days")));

        $where_arr = [
            ["l.lesson_start>=%d",$next_day_begin],
            ["l.lesson_end<=%d",$next_day_end],
            "l.del_flag=0",
            "s.is_test_user=0",
            "l.lesson_type =2"
        ];

        $sql = $this->gen_sql_new("  select l.lesson_start, m.phone, t.nick as tea_nick, l.lessonid, s.userid as stu_id, s.nick as stu_nick, l.lesson_end, l.subject, t.wx_openid as tea_openid, p.wx_openid as par_openid  from %s l "
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." left join %s p on p.parentid=s.parentid "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tr on tr.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s m on m.uid=ts.require_adminid where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_tea_list($start,$end){
        $where_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            "lesson_type in (0,1,3)",
            "lesson_del_flag=0",
            "confirm_flag!=2",
            "assign_jw_adminid=0",
            "lesson_status=2"
        ];
        $sql = $this->gen_sql_new("select distinct(t.teacherid)"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);


    }

}
