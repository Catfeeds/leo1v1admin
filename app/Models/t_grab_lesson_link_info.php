<?php
namespace App\Models;
use \App\Enums as E;
class t_grab_lesson_link_info extends \App\Models\Zgen\z_t_grab_lesson_link_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_grabid_by_link($link){
        $sql = $this->gen_sql_new("select grabid"
                                  ." from %s"
                                  ." where grab_lesson_link='%s'"
                                  ,self::DB_TABLE_NAME
                                  ,$link
        );
        return $this->main_get_value($sql);

    }

    public function get_all_info($start_time,$end_time,$grabid, $grab_lesson_link, $live_time, $adminid, $page_info){
        $where_arr = [
            ["g.create_time>=%s",$start_time,0],
            ["g.create_time<%s",$end_time,0],
            ['g.grabid=%u', $grabid, ''],
            ['g.grab_lesson_link="%s"', $grab_lesson_link, ''],
            ['g.live_time=%u', $live_time, ''],
            ['g.adminid=%u', $adminid, ''],
        ];
        $sql = $this->gen_sql_new(
            "select g.grabid,g.grab_lesson_link,g.live_time,g.adminid,g.create_time,g.requireids,"
            ." count(v.visitid) as visit_count, sum(if(v.operation=1,1,0)) as grab_count,"
            ." sum(if(p.success_flag=1,1,0)) as succ_count, sum( if(p.success_flag=0,1,0) ) as fail_count"
            ." from %s g "
            ." left join %s v on g.grabid=v.grabid "
            ." left join %s p on p.visitid=v.visitid "
            ." where %s "
            ." group by g.grabid "
            ." order by g.grabid desc "
            ,self::DB_TABLE_NAME
            ,t_grab_lesson_link_visit_info::DB_TABLE_NAME
            ,t_grab_lesson_link_visit_operation::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list_by_page($sql, $page_info,10,1);
    }

    public function get_requireids_by_grabid($grabid){
        $sql = $this->gen_sql_new("select requireids"
                                  ." from %s"
                                  ." where grabid='%s'"
                                  ,self::DB_TABLE_NAME
                                  ,$grabid
        );
        return $this->main_get_value($sql);



    }
}
