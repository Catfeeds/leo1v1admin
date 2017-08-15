<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_campus_list extends \App\Models\Zgen\z_t_admin_campus_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_admin_campus_info(){
        $sql = $this->gen_sql_new("select c.campus_id,campus_name,ma.groupid main_groupid,ma.group_name main_group_name"
                           ." from %s c left join %s ma on c.campus_id = ma.campus_id",
                           self::DB_TABLE_NAME,
                           t_admin_main_group_name::DB_TABLE_NAME                          
        );
        return $this->main_get_list_as_page($sql);
    }

}











