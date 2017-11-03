<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_group_members extends \App\Models\Zgen\z_t_agent_group_members
{
	public function __construct()
	{
		parent::__construct();
	}

    //@desn:判断团员是否已经在团中
    public function check_is_in($phone){
        $where_arr = [
            ["a.phone = '%s' ",$phone,'-1'],
        ];

        $sql = $this->gen_sql_new(
            "select agm.id from %s agm ".
            "left join %s a on a.id = agm.agent_id ".
            "where %s",
            self::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_value($sql);
    }
    //@desn:获取每个团的成员数
    //@desn:$group_id团id
    public function get_member_num($group_id){
        $where_arr = [
            ['group_id = %u',$group_id,'-1'],
        ];
        $sql = $this->gen_sql_new(
            "select count(id) from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取团队信息
    public function get_group_info($colconel_agent_id,$start_time,$end_time){
        $where_arr = [
            ['ag.colconel_agent_id = %u',$colconel_agent_id,'-1'],
        ];

        $where_arr2 = [
            ['a2.id = %u',$colconel_agent_id,'-1'],
        ];

        $sql = $this->gen_sql_new(
            "select  ci.colconel_name,ag.group_id,ag.group_name,".
            "sum(agmr.cycle_student_count) as student_count,".
            "sum(agmr.cycle_member_count) as member_count,sum(agmr.cycle_test_lesson_count) as test_lesson_count,".
            "sum(agmr.cycle_order_count) as order_count,sum(agmr.cycle_order_money) as order_money,@is_group:=1 as is_group,".
            "@level:='l-2' as level ".
            "from %s agm ".
            "left join %s ag on ag.group_id = agm.group_id ".
            "left join %s a on a.id = agm.agent_id ".
            "left join %s agmr on agmr.agent_id = agm.agent_id and agmr.create_time >= %u and agmr.create_time < %u ".
            "left join (select concat_ws('/',a2.phone,a2.nickname) as colconel_name,".
            "a2.id from %s a2 where %s ) as ci on ci.id = ag.colconel_agent_id ".
            "where %s ".
            "group by ag.group_id",
            self::DB_TABLE_NAME,
            t_agent_group::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            t_agent_group_member_result::DB_TABLE_NAME,
            $start_time,
            $end_time,
            t_agent::DB_TABLE_NAME,
            $where_arr2,
            $where_arr
        );
        
        return $this->main_get_list($sql);
    }
    //@desn:获取团队明细
    public function get_members_info($colconel_agent_id,$page_info,$group_id,$start_time,$end_time){
        $where_arr = [
            ['ag.colconel_agent_id = %u',$colconel_agent_id,'-1'],
        ];
        if($group_id > 0)
            $this->where_arr_add_int_or_idlist($where_arr,"ag.group_id",$group_id,-1);
        $sql = $this->gen_sql_new(
            "select ag.group_id,agm.id,a.phone,a.nickname,ag.group_name,agmr.cycle_student_count,".
            "agmr.cycle_test_lesson_count,agmr.cycle_order_money,agmr.cycle_member_count,agmr.cycle_order_count ".
            "from %s agm ".
            "join %s ag on agm.group_id = ag.group_id ".
            "join %s agmr on agmr.agent_id = agm.agent_id and agmr.create_time >= %u and agmr.create_time < %u ".
            "join %s a on a.id = agm.agent_id ".
            "where %s",
            self::DB_TABLE_NAME,
            t_agent_group::DB_TABLE_NAME,
            t_agent_group_member_result::DB_TABLE_NAME,
            $start_time,
            $end_time,
            t_agent::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }
    //@desn:获取团队成员业绩
    public function get_member_result($group_id,$start_time,$end_time){
        $where_arr = [
            ['agm.group_id = %u',$group_id,'-1']
        ];
        $sql= $this->gen_sql_new(
            "select a.id as member_id,a.phone,a.nickname,agmr.cycle_student_count as student_count,".
            "agmr.cycle_test_lesson_count as test_lesson_count,agmr.cycle_order_money as order_money,".
            "agmr.cycle_member_count as member_count,agmr.cycle_order_count as order_count,@is_member:=1 as is_member ".
            ",@level:='l-3' as level ".
            "from %s agm ".
            "left join %s agmr on agmr.agent_id = agm.agent_id and agmr.create_time >= %u and agmr.create_time < %u ".
            "join %s a on agm.agent_id = a.id ".
            "where %s",
            self::DB_TABLE_NAME,
            t_agent_group_member_result::DB_TABLE_NAME,
            $start_time,
            $end_time,
            t_agent::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取全部业绩
    public function get_agent_member_result($start_time,$end_time){
        
        $sql = $this->gen_sql_new(
            "select sum(agmr.cycle_student_count) as student_count,sum(agmr.cycle_test_lesson_count) as test_lesson_count,".
            "sum(agmr.cycle_order_money) as order_money,sum(agmr.cycle_member_count) as member_count,".
            "sum(agmr.cycle_order_count) as order_count ".
            "from %s agm ".
            "left join %s agmr on agmr.agent_id = agm.agent_id and agmr.create_time >= %u and agmr.create_time < %u ".
            "join %s a on agm.agent_id = a.id ",
            self::DB_TABLE_NAME,
            t_agent_group_member_result::DB_TABLE_NAME,
            $start_time,
            $end_time,
            t_agent::DB_TABLE_NAME
        );
        return $this->main_get_row($sql);
    }
    //@desn:判断将要建团的团长是否团员
    public function get_is_member($agentid){
        $where_arr = [
            ['agent_id = %u',$agentid,'-1']
        ];
        $sql =$this->gen_sql_new(
            "select id from %s where %s",self::DB_TABLE_NAME,$where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取所有团员信息
    public function get_agent_group_members_list(){
        $sql = $this->gen_sql_new(
            "select agent_id from %s ",self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
    
}
