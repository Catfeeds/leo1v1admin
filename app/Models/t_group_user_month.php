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
        $sql=$this->gen_sql_new("select u.adminid,m.account from %s u,%s m where u.adminid= m.uid and groupid=%u and month=%u ",
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
        $sql =$this->gen_sql_new("select g.groupid from %s gu, %s g where "
                                 ."gu.groupid= g.groupid and  "
                                 ." main_type=%u and adminid=%u and month=%u",
                                 self::DB_TABLE_NAME,
                                 t_admin_group_name::DB_TABLE_NAME,
                                 $main_type,
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

    public function del_by_month($month) {
        $sql = $this->gen_sql_new("delete from %s where  month=%u",
                                  self::DB_TABLE_NAME
                                  ,$month
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

}











