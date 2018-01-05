<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_approve_refer_to_data extends \App\Models\Zgen\z_t_teacher_approve_refer_to_data
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list($start_time, $end_time,$page_num,$teacherid = -1) {
        $where_arr = [
            ["add_time>=%u", $start_time, 0],
            ["add_time<%u", $end_time, 0],
        ];
        if ($teacherid != -1) {
            array_push($where_arr, "teacherid=$teacherid");
        }
        $sql = $this->gen_sql_new(
            "select id,teacherid,stu_num,total_lesson_num,cc_order_num,cc_lesson_num,".
            "cr_order_num,cr_lesson_num,violation_num,test_lesson_count,regular_lesson_count,".
            'no_notes_count,test_lesson_later_count,regular_lesson_later_count,no_evaluation_count,'.
            'turn_class_count,ask_for_leavel_count,test_lesson_truancy_count,regular_lesson_truancy_count,'.
            'turn_teacher_count,stu_refund,all_test_lesson_count,all_regular_lesson_count '.
            "from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql, $page_num);
    }

    public function get_id_for_teacherid($start_time, $end_time, $teacherid) {
        $where_arr = [
            ['add_time>=%u', $start_time, 0],
            ['add_time<%u', $end_time, 0],
            ['teacherid=%u', $teacherid, 0]
        ];

        $sql = $this->gen_sql_new("select id from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

}











