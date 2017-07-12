<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_complaints_info extends \App\Models\Zgen\z_t_teacher_complaints_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info($page_num,$account_id,$adminid,$accept_adminid,$require_adminid,$start_time,$end_time,$id){
        $where_arr=[
            ["tc.adminid=%u",$adminid,-1],
            ["tc.adminid=%u",$require_adminid,-1],
            ["tc.accept_adminid=%u",$accept_adminid,-1],
            ["tc.accept_adminid=%u",$account_id,-1],
            ["tc.id=%u",$id,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"tc.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select tc.id,tc.add_time,tc.adminid,tc.teacherid,tc.complaints_info,tc.complaints_info_url,t.realname,"
                                 ." tc.subject,t.grade_part_ex,t.grade_start,t.grade_end,tc.record_scheme,tc.record_scheme_url, "
                                 ." tc.accept_adminid,tc.accept_time ,tc.is_done,tc.done_time,m.account,mm.account accept_account "
                                 ." from %s tc left join %s t on tc.teacherid = t.teacherid"
                                 ." left join %s m on m.uid= tc.adminid"
                                 ." left join %s mm on mm.uid= tc.accept_adminid"
                                 ." where %s order by tc.add_time desc",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $where_arr                                 
        );
        return $this->main_get_list_by_page($sql,$page_num);
        
    }

    public function check_is_exist($teacherid,$adminid){
        $sql = $this->gen_sql_new("select id from %s where teacherid= %u and adminid= %u and accept_time =0",self::DB_TABLE_NAME,$teacherid,$adminid);
        return $this->main_get_value($sql);
    }

    
}











