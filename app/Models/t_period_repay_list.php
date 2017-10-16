<?php
namespace App\Models;
use \App\Enums as E;
class t_period_repay_list extends \App\Models\Zgen\z_t_period_repay_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_order_repay_info($orderid){
        $where_arr=[
            ["orderid = %u",$orderid,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_repay_order_info($due_date){
        $where_arr=[
            ["due_date = %u",$due_date,-1]  
        ];
        $sql = $this->gen_sql_new("select orderid from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

}











