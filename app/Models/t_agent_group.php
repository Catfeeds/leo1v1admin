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
    //@param:$group_colconel 团长电话或姓名
    public function get_agent_group_list($agent_id,$page_info,$group_colconel=''){
        $where_arr = [];
        if($agent_id){
            $where_arr = [
                ['ag.colconel_agent_id = %u',$agent_id,-1],
            ];
        }
        if($group_colconel){
            if(strlen($group_colconel) == 11 && is_numeric($group_colconel))
                $this->where_arr_add_str_field($where_arr,'a.phone',$group_colconel);
            else
                $where_arr[] = ["a.nickname like '%%%s%%'", $group_colconel, ""];
        }

        $sql = $this->gen_sql_new(
            "select ag.group_id,ag.group_name,ag.create_time,a.phone,a.nickname,ag.colconel_agent_id ".
            "from %s ag ".
            "join %s a on ag.colconel_agent_id = a.id ".
            "where %s ".
            "group by ag.group_id",
            self::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
    //@desn:获取该团长的团队
    public function get_group_list($colconel_agent_id){
        $where_arr = [
            ['colconel_agent_id = %u',$colconel_agent_id],
        ];
        $sql = $this->gen_sql_new(
            "select group_id,group_name from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取所有团长列表
    public function get_colconel_list(){
        $sql = $this->gen_sql_new(
            "select a.id as colconel_id,concat_ws('/',phone,nickname) as colconel_name ".
            "from %s ag ".
            "left join %s a on ag.colconel_agent_id = a.id ".
            "group by ag.colconel_agent_id",
            self::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }
    
}











