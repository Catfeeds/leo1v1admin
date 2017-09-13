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
        return $this->main_get_list($sql);
    }

    public function get_info_by_parent_orderid($parent_orderid,$child_order_type){
        $sql = $this->gen_sql_new("select * from %s where parent_orderid = %u and child_order_type=%u",
                                  self::DB_TABLE_NAME,
                                  $parent_orderid,
                                  $child_order_type
        );
        return $this->main_get_row($sql);

    }

}











