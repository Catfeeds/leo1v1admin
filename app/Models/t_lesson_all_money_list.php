<?php
namespace App\Models;
use \App\Enums as E;
class t_lesson_all_money_list extends \App\Models\Zgen\z_t_lesson_all_money_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function update_lesson_all_money_info($lessonid,$teacher_base_money,$teacher_lesson_count_money,$teacher_lesson_cost){
        $where_arr = [
            ["lessonid=%u",$lessonid,-1]
        ];
        $sql = $this->gen_sql_new("update %s set teacher_base_money=%u"
                                  .",teacher_lesson_count_money=%u"
                                  .",teacher_lesson_cost=%u"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$teacher_base_money
                                  ,$teacher_lesson_count_money
                                  ,$teacher_lesson_cost
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_lesson_all_money_list($start_time,$end_time){
        $where_arr=[
            "s.is_test_user=0",
            ["la.add_time>=%u",$start_time,0],
            ["la.add_time<%u",$end_time,0],
        ];

        $sql = $this->gen_sql_new("select la.lessonid,la.teacherid,la.userid,s.nick as stu_nick,s.phone as stu_phone,"
                                  ." l.subject,l.grade,la.lesson_type,"
                                  ." la.lesson_count,la.per_price,la.confirm_flag,la.teacher_type,la.teacher_money_type,"
                                  ." la.teacher_base_money,la.teacher_lesson_count_money,la.teacher_lesson_cost,"
                                  ." l.lesson_count as l_lesson_count"
                                  ." from %s la"
                                  ." left join %s s on la.userid=s.userid"
                                  ." left join %s l on la.lessonid=l.lessonid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

}











