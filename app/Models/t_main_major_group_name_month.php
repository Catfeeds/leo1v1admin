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


}











