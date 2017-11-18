<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_daily_lottery extends \App\Models\Zgen\z_t_agent_daily_lottery
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:获取用户今日已抽奖次数
    //@param: $agent_id 邀请人id
    //@param:$start_time 开始时间
    //@param:$end_time  结束时间
    public function get_has_used_count($agent_id,$start_time,$end_time){
        $where_arr = [
            ['agent_id = %u',$agent_id,-1],
        ];
        $this->where_arr_add_time_range($where_arr,'create_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            'select count(lid) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户总共抽奖金额
    //@param: $id 用户优学优享id
    public function get_sum_daily_lottery($id,$is_cash){
        $where_arr = [
            ['agent_id = %u',$id,-1],
        ];
        if($is_cash)
            $this->where_arr_add_int_field($where_arr,'is_can_cash_flag',1);
        $sql = $this->gen_sql_new(
            'select sum(money) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:跟新用户所有的转盘奖励状态为可提现
    public function update_all_flag($agent_id){
        $sql = sprintf('update %s set is_can_cash_flag = 1 where agent_id = %u',self::DB_TABLE_NAME,$agent_id);
        return $this->main_update($sql);
    }
}











