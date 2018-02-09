<?php
namespace App\Models;
use \App\Enums as E;
class t_group_name_month extends \App\Models\Zgen\z_t_group_name_month
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_group_name_list($main_type,$up_groupid,$month){
        $sql=$this->gen_sql_new("select groupid,group_name,master_adminid  from %s where main_type=%u and up_groupid = %u and month=%u order by group_name  asc " ,
                                self::DB_TABLE_NAME,
                                $main_type,
                                $up_groupid,
                                $month
        );
        return $this->main_get_list($sql);
    }

    public function get_max_groupid($month){
        $sql = $this->gen_sql_new("select max(groupid) from %s where month= %u",
                                  self::DB_TABLE_NAME,
                                  $month
        );
        return $this->main_get_value($sql);
    }

    public function get_group_list_new($page_num,$main_type,$month){
        $sql=$this->gen_sql_new("select groupid,group_name,master_adminid from %s where main_type=%u and month=%u " ,
                                self::DB_TABLE_NAME, $main_type,$month);
        return $this->main_get_list_by_page($sql,$page_num);
    }
    public function update_by_up_groupid($up_groupid,$month) {
        $sql = $this->gen_sql_new("update %s set up_groupid = 0 where up_groupid=%u and month=%u",
                                  self::DB_TABLE_NAME
                                  ,$up_groupid
                                  ,$month
        );
        return $this->main_update($sql);
    }

    public function del_by_month($month,$main_type_flag=0) {
        $where_arr=[
            ["month=%u",$month,0],
            ["main_type=%u",$main_type_flag,0],
        ];
        $sql = $this->gen_sql_new("delete from %s where %s",
                                  self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_group_admin_list($adminid,$month){
        $where_arr = [
            ["gn.master_adminid = %d ",$adminid],
            ["gn.month = %d ",$month],
            ["gu.month = %d ",$month],
            "((am.leave_member_time>$month and am.del_flag=1) or am.del_flag =0)",
        ];

        $sql = $this->gen_sql_new(" select adminid "
                                  ." from %s gn "
                                  ." left join %s gu on gn.groupid = gu.groupid"
                                  ." left join %s am on am.uid = gu.adminid "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_group_user_month::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_groupid_by_adminid($adminid, $month){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'master_adminid', $adminid);
        $this->where_arr_add_int_field($where_arr, 'month', $month);
        $sql=$this->gen_sql_new(" select groupid "
                                ." from %s "
                                ." where %s limit 1 " ,
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function is_master($month,$adminid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'month', $month);
        $this->where_arr_add_int_field($where_arr, 'master_adminid', $adminid);
        $sql = $this->gen_sql_new("select groupid from %s where %s ",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_son_adminid_by_up_groupid($month,$admin_main_groupid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'g.month', $month);
        $this->where_arr_add_int_field($where_arr, 'g.up_groupid', $admin_main_groupid);
        $sql = $this->gen_sql_new(
            " select g.up_groupid,u.adminid "
            ." from %s g "
            ." left join %s u on u.groupid = g.groupid and u.month=g.month "
            ." where %s "
            ,self::DB_TABLE_NAME//g
            ,t_group_user_month::DB_TABLE_NAME//u
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }
}
