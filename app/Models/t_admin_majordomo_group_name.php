<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_majordomo_group_name extends \App\Models\Zgen\z_t_admin_majordomo_group_name
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_group_list ($main_type) {
        $sql=$this->gen_sql_new("select groupid,group_name,main_assign_percent,master_adminid  from %s where main_type=%u order by group_name  asc " ,
                                self::DB_TABLE_NAME, $main_type);
        return $this->main_get_list($sql);
    }

    public function get_max_main_type(){
        $sql = $this->gen_sql_new("select max(main_type) from %s ",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function is_master($adminid){
        $sql = $this->gen_sql_new("select groupid from %s where master_adminid='$adminid'",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_all_list(){
        $sql=$this->gen_sql_new("select * from %s ",
                                self::DB_TABLE_NAME) ;
        return $this->main_get_list($sql);
    }


    

}











