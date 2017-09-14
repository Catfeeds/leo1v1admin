<?php
namespace App\Models;
use \App\Enums as E;
class t_mail_group_name extends \App\Models\Zgen\z_t_mail_group_name
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($page_info ) {
        $sql= $this->gen_sql_new("select * from %s order by title" ,self::DB_TABLE_NAME );
        return $this->main_get_list_by_page($sql,$page_info);
    }

}











