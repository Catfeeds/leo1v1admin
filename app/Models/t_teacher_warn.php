<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_warn extends \App\Models\Zgen\z_t_teacher_warn
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_info_for_time($start_time, $end_time) {
        $where_arr = [
            ['lesson_start>=%u', $start_time,0],
            ['lesson_start<%u', $end_time,0]
        ];
        $sql = $this->gen_sql_new("select id,teacherid,lesson_start from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function($item) {
            return $item['teacherid'].'_'.$item['lesson_start'];
        });
    }

    public function get_all_info($start_time, $end_time, $teacherid, $type = 0) {
        $where_arr = [
            ['t.lesson_start>=%u', $start_time, 0],
            ['t.lesson_start<%u', $end_time, 0]
        ];
        if ($teacherid) {
            // array_push($where_arr, ["t.teacherid=%u",$teacherid,0]);
            // $sql = $this->gen_sql_new("select id,teacherid,sum(five_num) five_num,sum(fift_num) fift_num,sum(leave_num) leave_num,sum(absent_num) absent_num,sum(adjust_num) adjust_num,sum(ask_leave_num) ask_leave_num,sum(big_order_num) big_order_num from %s t where %s group by teacherid",
            //                           self::DB_TABLE_NAME,
            //                           $where_arr
            // );
            // return $this->main_get_list($sql);

        }
        if ($type == 1) { // 常规
            array_push($where_arr, "l.lesson_type in (0,1,3) ");
        } else if ($type == 2) {
            array_push($where_arr, "l.lesson_type=2 ");
        }
        
        $sql = $this->gen_sql_new("select t.id,t.teacherid,count(l.lesson_start) lesson_num,sum(t.five_num) five_num,sum(t.fift_num) fift_num,sum(t.leave_num) leave_num,sum(t.absent_num) absent_num,sum(t.adjust_num) adjust_num,sum(t.ask_leave_num) ask_leave_num,sum(t.big_order_num) big_order_num "
                                  ."from %s t left join %s l on t.lessonid=l.lessonid where %s group by t.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_info_for_teacherid($teacherid, $lesson_start) {
        $where_arr = [
            ['teacherid=%u', $teacherid, 0],
            ['lesson_start=%u', $lesson_start, 0]
        ];
        $sql = $this->gen_sql_new("select id from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_info_for_test_user() {
        $sql = $this->gen_sql_new("select w.id,t.teacherid from %s w left join %s t on t.teacherid=w.teacherid where is_test_user!=0",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_all_lessonid() {
        //select w.id from t_teacher_warn w left join t_test_lesson_subject_sub_list l on w.lessonid=l.lessonid where success_flag=2
        $sql = $this->gen_sql_new("select w.id from %s w left join %s l on w.lessonid=l.lessonid where success_flag=2 ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
}











