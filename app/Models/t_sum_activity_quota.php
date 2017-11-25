<?php
namespace App\Models;
use \App\Enums as E;
class t_sum_activity_quota extends \App\Models\Zgen\z_t_sum_activity_quota
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:配置合同活动总配额
    public function set_config_value($opt_time ,$market_quota)  {
        return $this->row_insert([
            "create_time" => $opt_time,
            "market_quota" => $market_quota,
        ],true);
    }
    //@desn:获取每月合同活动配额
    public function get_market_quota_month($start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,'create_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            'select market_quota from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }


}











