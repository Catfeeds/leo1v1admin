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
        $where_arr = [
            ['create_time>=%u', $start_time, 0],
            ['create_time<%u', $end_time, 0],
        ];
        $sql = $this->gen_sql_new(
            "select lesson_stu_num,lesson_count ,lesson_count_money,from_unixtime(create_time,'%%Y-%%m') as month"
            ." from %s "
            ." where %s "
            ." group by month "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql,function($item) {
            return $item["month"];
        });
    }

    public function get_student_month_info($time){
        $sql = $this->gen_sql_new(
            "select pay_stu_num,new_pay_stu_num,normal_over_num,refund_stu_num,study_num,stop_num,drop_out_num,vacation_num,"
            ." has_ass_num,no_ass_num,warning_renow_stu_num,no_warning_renow_stu_num,warning_stu_num"
            ." from %s"
            ." where create_time=$time"
            ,self::DB_TABLE_NAME
        );
        return $this->main_get_row($sql);
    }

    public function get_all_pay_order_num($time){
        $where_arr = [
            ["create_time=%u" ,$time,-1],
        ];
        $sql = $this->gen_sql_new("select pay_order_num from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_id_by_create_time($create_time){
        $sql = $this->gen_sql_new("select id from %s where create_time=$create_time"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

}
