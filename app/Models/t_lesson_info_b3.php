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
        $sql = $this->gen_sql_new("  select l.lesson_start, l.lesson_end, l.subject, t.wx_openid as tea_openid,  from %s l "
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." left join %s p on p.parentid=s.parentid"
        );
    }
}
