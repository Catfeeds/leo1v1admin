<?php
namespace App\Models;
use \App\Enums as E;
class t_company_wx_tag_department extends \App\Models\Zgen\z_t_company_wx_tag_department
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list() {
        $sql = $this->gen_sql_new("select id,department from %s ",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

}











