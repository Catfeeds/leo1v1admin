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
            //['lesson_start>=%u', $start_time,0],
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

    public function get_all_info($start_time, $end_time, $page_info) {
        $where_arr = [
            //['add_time>=%u', $start_time, 0],
            ['add_time<%u', $end_time, 0]
        ];
        $sql = $this->gen_sql_new("select id,teacherid,sum(five_num) five_num,fift_num,leave_num,absent_num,adjust_num,ask_leave_num,big_order_num from %s where %s group by teacherid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql, $page_info);
    }
}











