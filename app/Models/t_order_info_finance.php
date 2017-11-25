<?php
namespace App\Models;
use \App\Enums as E;
class t_order_info_finance extends \App\Models\Zgen\z_t_order_info_finance
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_order_info($start_time,$end_time,$contract_type){
        $where_arr=[
            ["o.order_time>=%u",$start_time,0],  
            ["o.order_time<%u",$end_time,0],
            ["o.contract_type=%u",$contract_type,-1],
            "s.is_test_user=0",
            "o.contract_status>0",
            " o.check_money_flag =1"
        ];
        $sql = $this->gen_sql_new("select o.*  "
                                  ." from %s o left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
        

    }
    public function get_order_tongji_info($start_time,$end_time,$contract_type){
        $where_arr=[
            ["o.order_time>=%u",$start_time,0],  
            ["o.order_time<%u",$end_time,0],
            ["o.contract_type=%u",$contract_type,-1],
            "s.is_test_user=0",
            "o.contract_status>0",
            " o.check_money_flag =1"
        ];
        $sql = $this->gen_sql_new("select count(distinct o.userid) num,sum(o.price) money  "
                                  ." from %s o left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
        

    }


}











