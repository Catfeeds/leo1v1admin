<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_main_group_user extends \App\Models\Zgen\z_t_admin_main_group_user
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_user_list($groupid) {
        $sql=$this->gen_sql_new("select adminid from %s where groupid=%u",
                                self::DB_TABLE_NAME,
                                $groupid);
        return $this->main_get_list_as_page($sql);
    }

}











