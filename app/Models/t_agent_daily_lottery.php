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
    public function get_sum_daily_lottery($id,$is_cash=0){
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
        $sql = sprintf(
            'update %s set is_can_cash_flag = 1 where agent_id = %u and is_can_cash_flag <> 1',
            self::DB_TABLE_NAME,
            $agent_id
        );
        return $this->main_update($sql);
    }
    //@desn:获取新增大转盘奖励数组
    public function get_daily_lottery_id_arr($id,$last_daily_lottery_time){
        $where_arr = [
            ['agent_id = %u',$id],
            ['create_time > %u',$last_daily_lottery_time],
        ];
        $sql = $this->gen_sql_new(
            'select lid from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取用户可以体现大转盘奖励和[不包括已体现]
    //@param:$agent_id 优学优学id
    //@param:$check_flag 刷新至可提现标识
    //@param:$lid_str 尚未体现大转盘奖励id串 
    public function get_can_cash_daily_lottery($agent_id,$check_flag,$lid_str){
        $where_arr = [
            ['agent_id = %u',$agent_id],
            ['is_can_cash_flag = %u',$check_flag],
        ];
        if($lid_str)
            $where_arr[] = 'lid in ('.$lid_str.')';
        $sql = $this->gen_sql_new(
            'select sum(money) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取不同种类的大转盘奖励之和
    public function get_sort_daily_lottery($id,$is_can_cash_flag){
        $where_arr = [
            ['agent_id = %u',$id],
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"is_can_cash_flag",$is_can_cash_flag);
        $sql = $this->gen_sql_new(
            'select sum(money) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
       
}











