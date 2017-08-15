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
        $sql = $this->gen_sql_new("select campus_id"
                           ." from %s",
                           self::DB_TABLE_NAME,
        );
        return $this->main_get_list($sql);
    }

}











