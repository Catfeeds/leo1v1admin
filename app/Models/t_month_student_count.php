<?php
namespace App\Models;
use \App\Enums as E;
class t_month_student_count extends \App\Models\Zgen\z_t_month_student_count
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_month_money_info($start_time, $end_time){
        $sql = $this->gen_sql_new(
            "select lesson_stu_num,lesson_count ,lesson_count_money,from_unixtime(create_time,'%%Y-%%m') as month"
            ." from %s "
            ." where create_time>$start_time and create_time<$end_time "
            ." group by month "
            ,self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql,function($item) {
            return $item["month"];
        });
    }
}











