<?php
namespace App\Models;
use \App\Enums as E;
class t_company_wx_users extends \App\Models\Zgen\z_t_company_wx_users
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list() {
        $sql = $this->gen_sql_new("select userid,name,department parentid,mobile,isleader from %s ",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

}











