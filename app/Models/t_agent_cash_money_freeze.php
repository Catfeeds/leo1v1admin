<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_cash_money_freeze extends \App\Models\Zgen\z_t_agent_cash_money_freeze
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:获取该订单的冻结金额
    //@param:$agent_cash_id 体现订单id
    public function get_agent_cash_money_freeze($agent_cash_id){
        $sql = $this->gen_sql_new(
            "select sum(freeze_money) from %s where agent_cash_id = %u ",
            self::DB_TABLE_NAME,
            $agent_cash_id
        );
        return $this->main_get_value($sql);
    }

}











