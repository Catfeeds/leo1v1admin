<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_income_log extends \App\Models\Zgen\z_t_agent_income_log
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:添加优学优享用户收入记录
    //@param:$agent_id 会员id
    //@param:$child_agent_id  邀请人id
    //@param:$money 收入金额
    //@param:$agent_income_type  收入类型
    public function insert_reward_log($agent_id,$child_agent_id,$money,$agent_income_type){
        //判断该推荐人是否已经获取了该类型奖励
        $obtain_flag = $this->is_obtain($agent_id,$child_agent_id,$agent_income_type);
        if($obtain_flag)
            return false;
        else{
            $this->row_insert([
                'agent_income_type' => $agent_income_type,
                'money' => $money,
                'agent_id' => $agent_id,
                'child_agent_id' => $child_agent_id,
                'create_time' => time(NULL)
            ]);
        }
    }
    //@desn:判断用户是否获取过该类型的优学优享奖励
    public function is_obtain($agent_id,$child_agent_id,$agent_income_type,$money=0){
        $where_arr = [
            ['agent_id = %u ',$agent_id,'-1'],
            ['child_agent_id = %u',$child_agent_id,'-1'],
            ['agent_income_type = %u',$agent_income_type],
        ];
        if($money)
            $this->where_arr_add_int_field($where_arr,'money',$money);
        $sql = $this->gen_sql_new(
            'select logid from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:添加优学优享用户收入记录
    //@param:$agent_id 会员id
    //@param:$child_agent_id  邀请人id
    //@param:$money 收入金额
    //@param:$agent_income_type  收入类型
    public function insert_commission_reward_log($agent_id,$child_agent_id,$money,$agent_income_type){
        //判断该推荐人是否已经获取了该类型奖励
        $obtain_flag = $this->is_obtain($agent_id,$child_agent_id,$agent_income_type,$money);
        if($obtain_flag)
            return false;
        else{
            //判断是否生成该邀请人的非全额佣金奖励
            $issued_money = $this->get_issued_money($agent_id,$child_agent_id,$agent_income_type);
            $money -=$issued_money;
            $this->row_insert([
                'agent_income_type' => $agent_income_type,
                'money' => $money,
                'agent_id' => $agent_id,
                'child_agent_id' => $child_agent_id,
                'create_time' => time(NULL)
            ]);
        }
    }
    //@desn:获取佣金非全额记录
    //@param:$agent_id 会员id
    //@param:$child_agent_id  邀请人id
    //@param:$agent_income_type  收入类型
    public function get_issued_money($agent_id,$child_agent_id,$agent_income_type){
        $where_arr = [
            ['agent_id = %u',$agent_id,'-1'],
            ['child_agent_id = %u',$child_agent_id,'-1'],
            ['agent_income_type = %u',$agent_income_type,'-1'],
        ];
        $sql = $this->gen_sql_new(
            'select sum(money) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    //@desn:获取用户本次体现的来源记录
    //@param:$agent_id 优学优享id
    //@param:$this_cash_time 开始时间
    //@param:$last_cash_time 结束时间
    public function get_this_cash_log($agent_id,$this_cash_time,$last_cash_time){
        $where_arr = [
            ['agent_id = %u ',$agent_id,-1],
            ['ail.create_time >= %u ',$last_cash_time,-1],
            ['ail.create_time < %u',$this_cash_time,-1]
        ];
        $sql = $this->gen_sql_new(
            'select ail.logid,ail.agent_income_type,ail.money,ail.create_time,a.phone as a_phone,a.nickname as a_nickname,'.
            'ca.phone as ca_phone,ca.nickname as ca_nickname '.
            'from %s ail '.
            'left join %s a on ail.agent_id=a.id '.
            'left join %s ca on ail.child_agent_id = a.id '.
            'where %s',
            self::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    } 
}











