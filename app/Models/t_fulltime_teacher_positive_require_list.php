<?php
namespace App\Models;
use \App\Enums as E;
class t_fulltime_teacher_positive_require_list extends \App\Models\Zgen\z_t_fulltime_teacher_positive_require_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info_by_assess_id($assess_id){
        $where_arr=[
            ["assess_id=%u",$assess_id,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_fail_require_positive_type($adminid){
        $where_arr=[
            ["t.adminid=%u",$adminid,-1],
            "(t.master_deal_flag=2 || t.main_master_deal_flag=2)"
        ];
        $sql = $this->gen_sql_new("select positive_type from %s t where %s and  not exists(select 1 from %s where t.adminid=adminid and t.add_time<add_time)",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function check_is_late($adminid){
        $where_arr=[
            ["t.adminid=%u",$adminid,-1],
            "t.main_master_deal_flag=1",
            "positive_type=3"
        ];
        $sql = $this->gen_sql_new("select 1 from %s t where %s and  not exists(select 1 from %s where t.adminid=adminid and t.add_time<add_time)",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);

    }

}











