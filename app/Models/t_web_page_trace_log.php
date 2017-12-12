<?php
namespace App\Models;
use \App\Enums as E;
class t_web_page_trace_log extends \App\Models\Zgen\z_t_web_page_trace_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_admin_info($web_page_id) {
        $sql=$this->gen_sql_new(
            "select from_adminid ,  count(*) as count ,   count(distinct ip) as ip_count "
            . " from %s where web_page_id=%u group by from_adminid order by ip_count desc   ",
            self::DB_TABLE_NAME,  $web_page_id  );

        return $this->main_get_list_as_page($sql);
    }
    public function get_list( $page_info, $web_page_id, $from_adminid )  {
        $where_arr=[
            ["from_adminid=%u", $from_adminid],
            ["web_page_id=%u", $web_page_id  ],
        ];
        $sql=$this->gen_sql_new(
            "select * from %s  where %s ",
            self::DB_TABLE_NAME,
            $where_arr

        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
    public function get_web_page($web_page_id) {

        $sql=$this->gen_sql_new(
            "select log.from_adminid as adminid,sum(log.share_wx_flag) as share_count,".
            "count(log.id) as count,count(distinct log.ip) as ip_count,".
            "user.groupid,gro.main_type, gro.group_name as group_name,gro.up_groupid,gup.group_name as up_group_name ".
            "from %s log ".
            "left join %s user on log.from_adminid = user.adminid ".
            "left join %s gro on user.groupid = gro.groupid ".
            "left join %s gup on gro.up_groupid = gup.groupid ".
            "where log.web_page_id=%u ".
            "group by log.from_adminid ".
            "order by gro.main_type desc,gup.groupid asc,gro.groupid asc,log.from_adminid asc ",
            self::DB_TABLE_NAME,
            t_admin_group_user::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            t_admin_main_group_name::DB_TABLE_NAME,
            $web_page_id
        );

        return $this->main_get_list_as_page($sql);
    }
    //@desn:h5活动页统计[new]
    //@param:$web_page_id 统计网页的id
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    public function get_web_page_new($web_page_id,$start_time,$end_time){
        $where_arr = [
            ['wptl.web_page_id = %u',$web_page_id],
            'mi.account_role in (1,2)',
        ];
        // $this->where_arr_add_time_range($where_arr, 'wptl.log_time', $start_time, $end_time);
        $sql=$this->gen_sql_new(
            "select mi."
            "select wptl.from_adminid as adminid,sum(wptl.share_wx_flag) as share_count,".
            "count(wptl.id) as count,count(distinct wptl.ip) as ip_count,".
            "user.groupid,gro.main_type, gro.group_name as group_name,gro.up_groupid,gup.group_name as up_group_name ".
            "from %s mi ".
            "left join %s wptl on wptl.from_adminid = mi.uid ".
            "left join %s user on wptl.from_adminid = user.adminid ".
            "left join %s gro on user.groupid = gro.groupid ".
            "left join %s gup on gro.up_groupid = gup.groupid ".
            "where %s ".
            "group by wptl.from_adminid ".
            "order by gro.main_type desc,gup.groupid asc,gro.groupid asc,wptl.from_adminid asc ",
            t_manager_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_admin_group_user::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            t_admin_main_group_name::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list_as_page($sql);

    }
}











