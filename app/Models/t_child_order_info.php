<?php
namespace App\Models;
use \App\Enums as E;
class t_child_order_info extends \App\Models\Zgen\z_t_child_order_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_child_order_info($orderid){
        $sql = $this->gen_sql_new("select * from %s where parent_orderid = %u",self::DB_TABLE_NAME,$orderid);
        return $this->main_get_list($orderid);
    }

}











