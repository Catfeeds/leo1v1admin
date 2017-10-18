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
}
