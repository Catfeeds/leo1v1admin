<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_group_member_result extends \App\Models\Zgen\z_t_agent_group_member_result
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:获取该团员最后一条信息
    public function get_last_info($id){
        $where_arr = [
            ['agent_id = %u',$id,'-1']
        ];
        $sql=$this->gen_sql_new(
            "select id,create_time from %s where %s order by id desc limit 1",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

}











