<?php
namespace App\Models;
use \App\Enums as E;
class t_area extends \App\Models\Zgen\z_t_area
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_city(){
        $sql = $this->gen_sql_new("select area_id,parent_id from %s where level=2", self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

}











