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
            "select ame.agent_money_ex_type,ame.add_time,ame.money,mi.account,mi.name ".
            "from %s ame ".
            "left join %s mi on ame.adminid = mi.uid ".
            "where %s ",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }

}











