<?php

namespace App\Models;
use App\Models\Zgen as Z;
use \App\Models as M;
use \App\Enums as E;
class t_lesson_info_b3 extends \App\Models\Zgen\z_t_lesson_info
{
    public function get_lesson_info_by_lessonid($lessonid){
        $sql = $this->gen_sql_new("  select t.nick as tea_nick, p.wx_openid, l.lesson_start, l.lesson_end, l.subject, s.nick as stu_nick from %s l"
                                  ." left join %s s on s.userid=l.userid "
                                  ." left join %s p on p.parentid=s.parentid "
                                  ." left join %s t on t.teacherid=l.teacherid where l.lessonid=%d "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$lessonid
        );

        return $this->main_get_row($sql);
    }

}
