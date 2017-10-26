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
        $sql = $this->gen_sql_new("select sum(price) as all_money,from_unixtime(order_time,'%%Y-%%m') as order_month,"
                                  ." count(*) as count,sum(lesson_total*default_lesson_count) as order_total "
                                  ." from %s o,%s s "
                                  ." where o.userid=s.userid "
                                  ." and s.is_test_user=0 "
                                  ." and contract_status>0 "
                                  ." and price>0 "
                                  ." and order_time>$start_time"
                                  ." and order_time<$end_time"
                                  ." group by order_month "
                                  ." order by order_month asc "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["order_month"];
        });
    }
}











