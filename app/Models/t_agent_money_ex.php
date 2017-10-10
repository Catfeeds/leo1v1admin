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
            "select ame.ex_type,ame.add_time,ame.money,mi.account,mi.name,si.nick,si.real_name".
            "from %s ame".
            "left join %s si on ame.agent_id = si.user_id".
            "left join %s mi on ame.adminid_id = mi.uid".
            "where %s ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_all_money ($agent_id) {
        $sql=$this->main_get_value("select sum(money) from %s where  agent_id=%u "
                              ,self::DB_TABLE_NAME , $agent_id);
        return $this->main_get_value($sql);
    }

}
