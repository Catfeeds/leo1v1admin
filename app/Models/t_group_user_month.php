<?php
namespace App\Models;
use \App\Enums as E;
class t_group_user_month extends \App\Models\Zgen\z_t_group_user_month
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_user_list_new($groupid,$month) {
        $sql=$this->gen_sql_new("select u.adminid,m.account,"
                                ."m.create_time,m.become_member_time,m.leave_member_time,m.del_flag,m.seller_level "
                                ." from %s u,%s m where u.adminid= m.uid and groupid=%u and month=%u ",
                                self::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $groupid,
                                $month
        );
        return $this->main_get_list($sql);
    }
    public function get_user_list_new_new($groupid,$month) {
        $sql=$this->gen_sql_new("select u.adminid,m.account,"
                                ."m.create_time,m.become_member_time,m.leave_member_time,m.del_flag,m.seller_level "
                                ." from %s u,%s m where u.adminid= m.uid and groupid=%u and month=%u and "
                                // ." (m.leave_member_time>$month or m.leave_member_time =0) ",
                                ."((m.leave_member_time>$month and m.del_flag=1) or m.del_flag =0)",
                                self::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $groupid,
                                $month
        );
        return $this->main_get_list($sql);
    }
    public function del_by_groupid($groupid,$month) {
        $sql = $this->gen_sql_new("delete from %s where groupid=%u and month=%u",
                                  self::DB_TABLE_NAME
                                  ,$groupid
                                  ,$month
        );
        return $this->main_update($sql);
    }
    public function get_groupid_by_adminid( $main_type, $adminid,$month) {
        $where_arr=[
            ["main_type=%u",$main_type,-1]  
        ];
        $sql =$this->gen_sql_new("select g.groupid from %s gu, %s g where "
                                 ."gu.groupid= g.groupid and  gu.month= g.month and "
                                 ." %s and adminid=%u and gu.month=%u ",
                                 self::DB_TABLE_NAME,
                                 // t_admin_group_name::DB_TABLE_NAME,
                                 t_group_name_month::DB_TABLE_NAME,
                                 $where_arr,
                                 $adminid,
                                 $month
        );
        return $this->main_get_value($sql,0);
    }

    public function delete_admin_info($groupid,$adminid,$month)
    {
        $sql = sprintf("delete from %s  where groupid= %u and adminid= %u and month= %u ",
                       self::DB_TABLE_NAME,
                       $groupid,
                       $adminid,
                       $month
        );
        $this->main_update( $sql  );
    }

    public function del_by_month($month,$main_type_flag=0) {
        $where_arr=[
            ["u.month=%u",$month,0],
            ["n.month=%u",$month,0],
            ["n.main_type=%u",$main_type_flag,0],
        ];
        $sql = $this->gen_sql_new("delete u from %s u "
                                  ." left join %s n on u.groupid = n.groupid and u.month = n.month "
                                  ."where %s",
                                  self::DB_TABLE_NAME
                                  ,t_group_name_month::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_groupid($adminid,$month) {
        $sql=$this->gen_sql_new("select groupid from %s where adminid=%u and  month=%u ",
                                self::DB_TABLE_NAME,
                                $adminid,
                                $month
        );
        return $this->main_get_value($sql);
    }

    public function get_adminid_list($groupid,$month) {
        $sql=$this->gen_sql_new("select adminid from %s u,%s m where  groupid=%u and month=%u ",
                                self::DB_TABLE_NAME,
                                $groupid,
                                $month
        );
        $arr= $this->main_get_list($sql);
        $list=[];
        foreach($list as $val){
            $list[]=$val["adminid"];
        }
        return $list;
    }

    public function get_group_info_by_adminid( $main_type, $adminid,$month) {
        $where_arr=[
            ["main_type=%u", $main_type , -1 ] ,
            ["gu.month=%u",$month,0],
            ["g.month=%u",$month,0],
        ];
        $sql =$this->gen_sql_new("select g.groupid,g.master_adminid from %s gu, %s g where "
                                 ."gu.groupid= g.groupid and  "
                                 ." %s and adminid=%u ",
                                 self::DB_TABLE_NAME,
                                 t_group_name_month::DB_TABLE_NAME,
                                 $where_arr, $adminid);
        return $this->main_get_row($sql,0);
    }

    public function get_master_adminid_by_adminid($adminid,$main_type=-1,$month=0){
        $where_arr=[
            ["n.main_type=%u",$main_type,-1],
            ["u.month=%u",$month,0],
            ["n.month=%u",$month,0],
        ];
        $sql=$this->gen_sql_new("select master_adminid from %s u "
                                ." left join %s n on u.groupid = n.groupid"
                                ." where adminid=%u and %s",
                                self::DB_TABLE_NAME,
                                t_group_name_month::DB_TABLE_NAME,
                                $adminid,
                                $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function del_item_row(){
        $sql=$this->gen_sql_new(
            "delete from %s where groupid=0 and adminid=68 and month=1514736000",
            self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function get_son_adminid_by_up_groupid($month,$self_groupid){
        $where = [];
        $this->where_arr_add_int_field($where_arr, 'month', $month);
        $this->where_arr_add_int_field($where_arr, 'groupid', $self_groupid);
        $sql = $this->gen_sql_new(
            " select groupid,adminid "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$self_groupid
        );
        return $this->main_get_list($sql);
    }

    public function update_month_groupid($adminid,$month,$groupid_old,$groupid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'adminid', $adminid);
        $this->where_arr_add_int_field($where_arr, 'groupid', $groupid_old);
        $this->where_arr_add_int_field($where_arr, 'month', $month);
        $sql = $this->gen_sql_new(
            "update %s set groupid=%u where %s ",
            self::DB_TABLE_NAME,
            $groupid,
            $where_arr
        );
        return $this->main_update($sql);
    }
}
