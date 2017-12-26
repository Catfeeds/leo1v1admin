<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_edit_log extends \App\Models\Zgen\z_t_seller_edit_log
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_all_list($uid){
        $where_arr = [
            ['type=%u',E\Eseller_edit_log_type::V_2],
        ];
        if($uid){
            $this->where_arr_add_int_or_idlist($where_arr,'uid',$uid);
        }
        $sql = $this->gen_sql_new (
            " select * "
            ." from %s where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_all_list_new($uid){
        $where_arr = [];
        $this->where_arr_add_int_or_idlist($where_arr,'type',[E\Eseller_edit_log_type::V_1,E\Eseller_edit_log_type::V_2]);
        if($uid){
            $this->where_arr_add_int_or_idlist($where_arr,'uid',$uid);
        }
        $sql = $this->gen_sql_new (
            " select * "
            ." from %s where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_count($start_time,$end_time,$origin_ex){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,'l.create_time',$start_time,$end_time);
        $this->where_arr_add_int_or_idlist($where_arr,'l.type',E\Eseller_edit_log_type::V_3);
        $this->where_arr_add_int_or_idlist($where_arr,'m.account_role',[E\Eaccount_role::V_2,E\Eaccount_role::V_7]);
        $this->where_arr_add_int_or_idlist($where_arr,'m2.account_role',E\Eaccount_role::V_2);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $sql = $this->gen_sql_new(
            " select sum(if(m.account_role = 2 and l.uid<>l.adminid,1,0)) count,l.uid adminid,l.type,sum(if(m.account_role = 7 and l.uid<>l.adminid,1,0)) tmk_count,"
            ." m.account_role "
            ." from %s l"
            ." left join %s ss on ss.userid=l.new and ss.global_tq_called_flag = 0 "
            ." left join %s m on m.uid=l.adminid "//分配人
            ." left join %s m2 on m2.uid=l.uid "//被分配人
            ." left join %s s on s.userid=ss.userid "
            ." where %s "
            ." group by l.uid "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_distribution_count($start_time,$end_time,$origin_ex){
        $where_arr = [
            's.is_test_user=0',
        ];
        $this->where_arr_add_time_range($where_arr,'l.create_time',$start_time,$end_time);
        $this->where_arr_add_int_or_idlist($where_arr,'l.type',E\Eseller_edit_log_type::V_3);
        $this->where_arr_add_int_or_idlist($where_arr,'m.account_role',E\Eaccount_role::V_2);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $sql = $this->gen_sql_new(
            " select sum(if(l.adminid>0 and l.uid<>l.adminid,1,0)) count,l.adminid adminid,l.type,"
            ." sum(if(ss.global_tq_called_flag = 0 and l.uid<>l.adminid,1,0)) no_call_count,ss.global_tq_called_flag, "
            ." m.account_role "
            ." from %s l"
            ." left join %s ss on ss.userid=l.new and ss.global_tq_called_flag = 0 "
            ." left join %s m on m.uid=l.adminid "
            ." left join %s s on s.userid=l.new "
            ." where %s "
            ." group by l.adminid "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_count_list($adminid,$start_time,$end_time,$page_info,$global_tq_called_flag,$origin_ex,$account_role){
        $where_arr = [
            ['l.uid = %u',$adminid,-1],
            'l.uid <> l.adminid',
            ['m.account_role = %u',$account_role,-1],
            ['l.type = %u',E\Eseller_edit_log_type::V_3],
            ['ss.global_tq_called_flag = %u',$global_tq_called_flag,-1],
        ];
        $this->where_arr_add_time_range($where_arr,'l.create_time',$start_time,$end_time);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $sql = $this->gen_sql_new(
            " select l.*,ss.global_tq_called_flag,if(ss.userid>0,0,1) del_flag,s.origin "
            ." from %s l "
            ." left join %s ss on ss.userid=l.new "
            ." left join %s s on s.userid=ss.userid "
            ." left join %s m on m.uid=l.adminid "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_distribution_list($adminid,$start_time,$end_time,$page_info,$global_tq_called_flag,$origin_ex,$user_name,$uid){
        $where_arr = [
            ['l.adminid = %u',$adminid,-1],
            ['l.uid = %u',$uid,-1],
            'l.uid <> l.adminid',
            ['l.type = %u',E\Eseller_edit_log_type::V_3],
            ['ss.global_tq_called_flag = %u',$global_tq_called_flag,-1],
            's.is_test_user=0',
        ];
        if ($user_name) {
            $where_arr[]=sprintf( "(s.nick like '%s%%' or s.realname like '%s%%' or s.phone like '%s%%' )",
                                  $this->ensql($user_name),
                                  $this->ensql($user_name),
                                  $this->ensql($user_name));
        }
        $this->where_arr_add_time_range($where_arr,'l.create_time',$start_time,$end_time);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $sql = $this->gen_sql_new(
            " select l.*,"
            ."ss.global_tq_called_flag,if(ss.userid>0,0,1) del_flag,ss.hand_get_adminid,"
            ."s.phone,s.origin "
            ." from %s l "
            ." left join %s ss on ss.userid=l.new "
            ." left join %s s on s.userid=ss.userid "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }


    public function get_stu_list_tmp($start_time,$end_time){
        $where_arr = [
            "se.uid=697",
            "se.adminid=697"
        ];

        $this->where_arr_add_time_range($where_arr, "se.create_time",$start_time, $end_time);

        $sql = $this->gen_sql_new("  select * from %s se "
                                  ." where %s"
                                  ,t_seller_edit_log::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_first_uid_tmp($new,$create_time){
        $sql = $this->gen_sql_new("  select * from %s s left join %s m on m.uid=s.uid"
                                  ." where new=%d and s.create_time<%d and s.uid !=697 and m.account_role=7 "
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$new
                                  ,$create_time
        );

        return $this->main_get_list($sql);
    }

    public function get_item_list(){
        $sql = "select ss.userid  from db_weiyi_admin.t_seller_edit_log l  left join db_weiyi.t_seller_student_new ss on ss.userid=l.new left join db_weiyi.t_student_info s on s.userid=ss.userid  where l.adminid = 831 and l.uid = 287 and l.uid <> l.adminid and l.type = 3 and s.is_test_user=0 and l.create_time>=1512057600 and l.create_time<1514736000";
        return $this->main_get_list($sql);
    }
}
