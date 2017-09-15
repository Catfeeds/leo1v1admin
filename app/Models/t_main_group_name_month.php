<?php
namespace App\Models;
use \App\Enums as E;
class t_main_group_name_month extends \App\Models\Zgen\z_t_main_group_name_month
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

    public function del_by_month($month) {
        $sql = $this->gen_sql_new("delete from %s where  month=%u",
                                  self::DB_TABLE_NAME
                                  ,$month
        );
        return $this->main_update($sql);
    }

    public function get_group_list_for_major ($main_type,$month,$up_groupid) {
        $sql=$this->gen_sql_new("select groupid,group_name,main_assign_percent,master_adminid  from %s where main_type=%u and month=%u and up_groupid=%d order by group_name  asc " ,
                                self::DB_TABLE_NAME,
                                $main_type,
                                $month,
                                $up_groupid
        );
        return $this->main_get_list($sql);
    }


}











