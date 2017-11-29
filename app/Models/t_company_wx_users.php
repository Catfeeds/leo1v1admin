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
        $sql = $this->gen_sql_new("select id+1000 id,userid,name,position,permission,isleader,department pId from %s order by `order` desc",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_all_list_for_depart($depart) {
        $sql = $this->gen_sql_new("select userid,name username,isleader,permission from %s where department in (".$depart.") order by `order` desc ", self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

}











