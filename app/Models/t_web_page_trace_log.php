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
    //@param:$start_time,$end_time  检索时间
    public function get_web_page($web_page_id,$start_time,$end_time) {
        $where_arr = [
            ['log.web_page_id=%u',$web_page_id],
        ];
        $this->where_arr_add_time_range($where_arr, 'log.log_time', $start_time, $end_time);

        $sql=$this->gen_sql_new(
            // "select log.from_adminid as adminid,sum(log.share_wx_flag) as share_count,".
            "select log.from_adminid as adminid,".
            "count( distinct if( log.share_wx_flag=1,from_unixtime(log.log_time, '%%Y-%%m-%%d'),0) ) as share_count,".
            "max( if( log.share_wx_flag=0,1,0) ) as has_zero,".
            "count(log.id) as count,count(distinct log.ip) as ip_count,".
            "user.groupid,gro.main_type, gro.group_name as group_name,gro.up_groupid,gup.group_name as up_group_name,".
            "(case mi.account_role when 1 then '助教' when 2 then '销售' else '其他' end) as account_role ".
            "from %s log ".
            "left join %s user on log.from_adminid = user.adminid ".
            "left join %s gro on user.groupid = gro.groupid ".
            "left join %s gup on gro.up_groupid = gup.groupid ".
            "left join %s mi on log.from_adminid = mi.uid ".
            "where %s ".
            "group by log.from_adminid ".
            "order by gro.main_type desc,gup.groupid asc,gro.groupid asc,log.from_adminid asc ",
            self::DB_TABLE_NAME,
            t_admin_group_user::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            t_admin_main_group_name::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list_as_page($sql);
    }
}











