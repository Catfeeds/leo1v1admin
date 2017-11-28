<?php
namespace App\Models;
use \App\Enums as E;
class t_activity_quota_detail extends \App\Models\Zgen\z_t_activity_quota_detail
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:配置合同活动名称
    public function set_config_value($opt_time,$order_activity_desc,$market_quota){
        return $this->row_insert([
            "create_time" => $opt_time,
            "order_activity_desc" => $order_activity_desc,
            "market_quota" => $market_quota,
        ],true);
    }
    //@desn:获取每个月的活动配额
    public function get_activity_detail_month($start_time,$end_time){
        $where_arr = ["order_activity_desc <> ''"];
        $this->where_arr_add_time_range($where_arr,'create_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            'select * from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取本月份总共投放的合同活动已投放预算
    public function get_sum_quota_month($start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,'create_time',$start_time,$end_time);
        $sql=$this->gen_sql_new(
            'select sum(market_quota) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }


}











