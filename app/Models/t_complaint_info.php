<?php
namespace App\Models;
use \App\Enums as E;
class t_complaint_info extends \App\Models\Zgen\z_t_complaint_info
{
    public function __construct()
    {
        parent::__construct();
    }



    public function get_complaint_info_for_qc($time_type,$page_num,$opt_date_str,$start_time,$end_time, $is_complaint_state, $account_type  ){

        $where_arr = [
            ["complaint_state = %d",$is_complaint_state,-1],
            ["account_type   = %d",$account_type ,-1],
        ];

        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);


        $sql = $this->gen_sql_new(" select distinct(tc.complaint_id),complaint_type, userid,account_type, complaint_info, add_time, complaint_info, current_adminid,m.account as current_account,complaint_state,current_admin_assign_time,complained_adminid,complained_adminid_type, complained_adminid_nick,suggest_info, deal_info, deal_time, deal_adminid  ".
                                  " from %s tc left join %s ta on tc.complaint_id = ta.complaint_id ".
                                  " left join %s td on td.complaint_id = tc.complaint_id".
                                  " left join %s m on m.uid = tc.current_adminid ".
                                  " where %s order by complaint_state asc,tc.add_time desc ",
                                  self::DB_TABLE_NAME,
                                  t_complaint_assign_info::DB_TABLE_NAME,
                                  t_complaint_deal_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }


    public function get_complaint_info_by_ass($page_info,$opt_date_str,$start_time,$end_time,$account_id_str,$account_type,$root_flag, $complained_feedback_type){
        $where_arr = [
            ["ta.assign_flag=%d",0],
            ["tc.account_type=%d",$account_type],
        ];

        if($root_flag){
            $where_arr[] =  ["ta.accept_adminid > %d",0];
        }else{
            $where_arr[] =  ["ta.accept_adminid in ('%s')",$account_id_str];
        }


        // if(){
            
        // }

        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);

        $sql = $this->gen_sql_new(" select tc.complaint_id,complaint_type, userid,account_type, complaint_info, add_time, complaint_info, current_adminid, complaint_state,current_admin_assign_time,complained_adminid,complained_adminid_type, complained_adminid_nick, assign_adminid,accept_adminid,suggest_info, deal_info, deal_time, deal_adminid ".
                                  " from %s tc left join %s ta on tc.complaint_id = ta.complaint_id ".
                                  " left join %s td on td.complaint_id = tc.complaint_id".
                                  " left join %s m on m.uid = tc.current_adminid ".
                                  " where %s group by tc.complaint_id order by ta.assign_time desc ",
                                  self::DB_TABLE_NAME,
                                  t_complaint_assign_info::DB_TABLE_NAME,
                                  t_complaint_deal_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }


    public function get_complaint_info_by_id($complaint_id){
        $sql = $this->gen_sql_new(" select deal_time, deal_info,complaint_type,userid,complaint_info,complaint_state,add_time".
                                  " from %s tc left join %s td on td.complaint_id = tc.complaint_id "
                                  ." where tc.complaint_id = $complaint_id",
                                  self::DB_TABLE_NAME,
                                  t_complaint_deal_info::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }

    public function get_last_msg($userid){

        $sql = $this->gen_sql_new("select complaint_info from %s tc where userid = %d order by add_time desc",
                                  self::DB_TABLE_NAME,
                                  $userid
        );

        return $this->main_get_list($sql);
    }


}
