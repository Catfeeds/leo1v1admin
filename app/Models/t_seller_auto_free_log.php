<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_auto_free_log extends \App\Models\Zgen\z_t_seller_auto_free_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list(){
        $where_arr = [];
        $sql = $this->gen_sql_new(
            " select * "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        $this->main_get_list($sql);
    }
}











