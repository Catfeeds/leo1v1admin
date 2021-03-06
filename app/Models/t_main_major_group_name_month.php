<?php
namespace App\Models;
use \App\Enums as E;
class t_main_major_group_name_month extends \App\Models\Zgen\z_t_main_major_group_name_month
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_group_list ($main_type,$month) {
        $sql=$this->gen_sql_new("select groupid,group_name,main_assign_percent,master_adminid  from %s where main_type=%u and month=%u order by group_name  asc " ,
                                self::DB_TABLE_NAME,
                                $main_type,
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

    public function row_delete_for_major($groupid,$month){
        $sql=$this->gen_sql_new("delete from %s  where groupid =%d and month=%d ",
                                self::DB_TABLE_NAME,
                                $groupid,
                                $month
        );
        return $this->main_update($sql);
    }


    public function del_by_month($month,$main_type_flag=0){
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

    public function is_master($month,$adminid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'month', $month);
        $this->where_arr_add_int_field($where_arr, 'master_adminid', $adminid);
        $sql = $this->gen_sql_new("select groupid from %s where %s ",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_cc_adminid_list($month){
        $where_arr = [
            "m.account_role=2",
            "m1.account_role=2",
            "m2.account_role=2",
            "m3.account_role=2",
        ];
        $this->where_arr_add_int_field($where_arr, 'mg.month', $month);
        $sql = $this->gen_sql_new(
            "select u.adminid,n.master_adminid n_master_adminid,g.master_adminid g_master_adminid,"
            ."mg.master_adminid mg_master_adminid "
            ."from %s mg "
            ."left join %s g on g.up_groupid=mg.groupid and g.month=mg.month "
            ."left join %s n on n.up_groupid=g.groupid and n.month=g.month "
            ."left join %s u on u.groupid=n.groupid and u.month=n.month "
            ."left join %s m on m.uid=mg.master_adminid "
            ."left join %s m1 on m1.uid=g.master_adminid "
            ."left join %s m2 on m2.uid=n.master_adminid "
            ."left join %s m3 on m3.uid=u.adminid "
            ."where %s "
            ,self::DB_TABLE_NAME
            ,t_main_group_name_month::DB_TABLE_NAME
            ,t_group_name_month::DB_TABLE_NAME
            ,t_group_user_month::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }
}











