<?php
namespace App\Models;
use \App\Enums as E;
class t_complaint_assign_info extends \App\Models\Zgen\z_t_complaint_assign_info
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_remark_by_complaintid($complaintid){

        $where_arr = [
            ["complaint_id=%d",$complaintid],
            ["assign_flag=%d",0]
        ];

        $sql = $this->gen_sql_new(" select assign_adminid, accept_adminid, assign_remarks, assign_time from %s ta".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );


        return $this->main_get_list($sql);
    }


    public function get_last_accept_adminid($complaint_id){
        $where_arr = [
            ["ta.complaint_id=%d",$complaint_id],
            ["ta.assign_flag=%d",0]
        ];

        $sql = $this->gen_sql_new("select accept_adminid, account from %s ta  ".
                                  " left join %s m on m.uid = ta.accept_adminid".
                                  " where %s order by assign_time desc",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }



    public function deal_reject($complaint_id,$assign_adminid,$accept_adminid){
        $sql = $this->gen_sql_new(" update %s ta left join %s tc on tc.complaint_id=ta.complaint_id set ta.assign_flag = 1,tc.complaint_state=0,tc.current_admin_assign_time=0 ".
                                  " where ta.complaint_id = $complaint_id and (assign_adminid=$assign_adminid or accept_adminid=$accept_adminid ) ",
                                  self::DB_TABLE_NAME,
                                  t_complaint_info::DB_TABLE_NAME
        );

        return $this->main_update($sql);
    }


    public function get_ass_log($complaint_id){
        $where_arr = [
            ['assign_flag=%d',0],
            ['complaint_id=%d',$complaint_id]
        ];

        $sql = $this->gen_sql_new("select assign_adminid, accept_adminid, assign_time, assign_remarks "
                                  ."from %s ta where %s   ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_director_wx_openid($complaint_id){
        $where_arr = [
            ['assign_flag=%d',0],
            ['complaint_id=%d',$complaint_id],
            // ['accept_adminid=%d',$accept_adminid]
        ];

        $sql = $this->gen_sql_new("  select  m.wx_openid "
                                  ." from %s ta"
                                  ." left join %s m on m.uid=ta.assign_adminid where %s ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);

    }
}
