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

    public function get_all_info($start_time, $end_time, $teacherid, $page_info) {
        $where_arr = [
            ['lesson_start>=%u', $start_time, 0],
            ['lesson_start<%u', $end_time, 0]
        ];
        if ($teacherid) {
            array_push($where_arr, ["teacherid=%u",$teacherid,0]);
        }
        
        $sql = $this->gen_sql_new("select id,teacherid,sum(five_num) five_num,sum(fift_num) fift_num,sum(leave_num) leave_num,sum(absent_num) absent_num,sum(adjust_num) adjust_num,sum(ask_leave_num) ask_leave_num,sum(big_order_num) big_order_num from %s where %s group by teacherid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql, $page_info);
    }
}











