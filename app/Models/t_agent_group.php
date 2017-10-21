<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_group extends \App\Models\Zgen\z_t_agent_group
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:获取团信息
    //@param:$agent_id 团长id
    public function get_agent_group_list($agent_id){
        $where_arr = [
            ['ag.agent_colonel_id = %u',$agent_id,-1],
        ];

        $sql = $this->gen_sql(
            "select ag.group_id,ag.group_name,ag.create_time,count(agm.id) as member_num ".
            "from %s ag ".
            "join %s a on ag.agent_colonel_id = a.id ".
            "join %s agm on ag.group_id = agm.group_id ".
            "where %s",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_agent_group_members::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_row($sql);
    }
}











