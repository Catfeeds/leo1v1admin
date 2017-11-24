<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_money_ex extends \App\Models\Zgen\z_t_agent_money_ex
{
    public function __construct()
    {
        parent::__construct();
    }


    public function  get_list( $page_info,$start_time, $end_time) {

        $where_arr=[
        ];
        $this->where_arr_add_time_range($where_arr,"ame.add_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select ame.id,a.phone,a.nickname,ame.agent_money_ex_type,ame.add_time,ame.money,".
            "mi.account,mi.name,f.flow_status ".
            "from %s ame ".
            "left join %s mi on ame.adminid = mi.uid ".
            "left join %s a on ame.agent_id = a.id ".
            "left join %s f on ame.id = f.from_key_int and f.flow_type = %u ".
            "where %s ",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            E\Eflow_type::V_AGENT_MONEY_EX_EXAMINE,
            $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }
    public function get_all_money ($agent_id) {
        $sql=$this->gen_sql_new( "select sum(money) from %s where  agent_id =%u "
                                 ,self::DB_TABLE_NAME , $agent_id);
        return $this->main_get_value($sql);
    }

    //获取用户的昵称和手机号
    //@param:$agent_id 获奖id
    public function get_agent_info($agent_id){
        $sql=$this->gen_sql_new(
            "select phone,nickname from %s where id = %u ",
            t_agent::DB_TABLE_NAME,
            $agent_id
        );
        
        return $this->main_get_row($sql);
    }

    //获取该审批当前的状态
    //$param:$id  申请记录id
    public function get_flow_status($id){
        $where_arr = [
            "from_key_int" => $id,
            "flow_type" => E\Eflow_type::V_AGENT_MONEY_EX_EXAMINE,
        ];
        $sql=$this->gen_sql_new(
            "select flow_status from %s where %s",
            t_flow::DB_TABLE_NAME,
            $where_arr
        );
        
        return $this->main_get_value($sql);
    }
    //@desn:获取该用户当前所有奖励
    //@param:$is_cash   0所有 2可提现
    //@param:$lid_str 大转盘未体现id_str
    //@param:$agent_money_id_str 优学优享现金活动未提现id_str
    public function get_reward_list($agent_id,$page_info,$page_count,$is_cash,$lid_str,$agent_money_id_str){
        $where_arr = [
            ['agent_id = %u',$agent_id,'-1']
        ];
        if($is_cash){
            $this->where_arr_add_int_field($where_arr,'flow_status',$is_cash);
            if($agent_money_id_str)
                $where_arr[] = 'ame.id in ('.$agent_money_id_str.')';
            else
                $where_arr[] = 'false';
        }
        $where_arr2 = [
            ['agent_id = %u',$agent_id,'-1'],
            'money > 0',
        ];
        if($is_cash){
            $this->where_arr_add_int_field($where_arr2,'is_can_cash_flag',1);
            if($lid_str)
                $where_arr2[] = 'lid in ('.$lid_str.')';
            else
                $where_arr2[] ='false';
        }
        $sql = $this->gen_sql_new(
            "select ame.agent_money_ex_type,ame.money,ame.add_time,@type:=1 as activity_type ".
            "from %s ame ".
            "left join %s f on ame.id = f.from_key_int and f.flow_type = %u ".
            "where %s ".
            "union all ".
            "select l_type as agent_money_ex_type,money,create_time as add_time,@type:=2 as activity_type  ".
            "from %s where %s",
            self::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            E\Eflow_type::V_AGENT_MONEY_EX_EXAMINE,
            $where_arr,
            t_agent_daily_lottery::DB_TABLE_NAME,
            $where_arr2
        );
        $order_str = 'order by add_time desc';
        return $this->main_get_list_by_page_with_union($sql,$page_info,$page_count,$use_group_by_flag=false,$order_str='',$list_key_func=null,$is_union=2);
    }
    //@desn:获取该用户奖励之和
    //@param:$is_cash 0所有2可提现
    //@param:$agent_id 优学优享id
    public function get_activity_total_money($agent_id,$is_cash){
        $where_arr = [
            ['agent_id = %u',$agent_id,'-1']
        ];
        if($is_cash)
            $this->where_arr_add_int_field($where_arr,'flow_status',$is_cash);
        $sql = $this->gen_sql_new(
            "select sum(ame.money) ".
            "from %s ame ".
            "left join %s f on ame.id = f.from_key_int and f.flow_type = %u ".
            "where %s",
            self::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            E\Eflow_type::V_AGENT_MONEY_EX_EXAMINE,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户所有奖励之和
    public function get_agent_sum_activity_money($agent_id,$check_flag){
        $where_arr = [
            ['ame.agent_id = %u',$agent_id,'-1'],
        ];
        $this->where_arr_add_int_field($where_arr,'f.flow_status',$check_flag,0);
        // $this->where_arr_add_int_field($where_arr,'f.flow_status',$check_flag);
        $sql = $this->gen_sql_new(
            "select sum(money) from %s ame ".
            "left join %s f on ame.id = f.from_key_int and f.flow_type = %u ".
            "where %s ",
            self::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            E\Eflow_type::V_AGENT_MONEY_EX_EXAMINE,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户可体现奖励之和
    //@param:$agent_id 优学优享id
    //@param:$check_flag 审核状态 2通过 
    //@param:$last_succ_cash_time 上次体现时间  
    public function get_can_cash_activity_money($agent_id,$check_flag,$last_succ_cash_time){
        $where_arr = [
            ['ame.agent_id = %u',$agent_id,'-1'],
        ];
        $this->where_arr_add_int_field($where_arr,'f.flow_status',$check_flag,0);
        $where_arr_2 = [
            ['agent_id = %u',$agent_id],
            ['create_time > %u',$last_succ_cash_time],
            'agent_income_type' => 5
        ];
        // $this->where_arr_add_int_field($where_arr,'f.flow_status',$check_flag);
        $sql = $this->gen_sql_new(
            "select sum(money) from %s ame ".
            "left join %s f on ame.id = f.from_key_int and f.flow_type = %u ".
            "where %s and ame.id in (select agent_money_ex_id from %s where %s)",
            self::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            E\Eflow_type::V_AGENT_MONEY_EX_EXAMINE,
            $where_arr,
            t_agent_income_log::DB_TABLE_NAME,
            $where_arr_2
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户可体现活动金额[已审批]
    //@param:$id 用户优学优享id
    public function get_examined_activity_money($id){
        $where_arr = [
            ['ame.agent_id = %u',$id],
            'f.flow_status' => E\Eflow_status::V_PASS,
        ];
        $sql = $this->gen_sql_new(
            'select sum(money) from %s ame '.
            'left join %s f on ame.id = f.from_key_int and f.flow_type = %u '.
            'where %s',
            self::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            E\Eflow_type::V_4002,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
}
