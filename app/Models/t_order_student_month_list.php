<?php
namespace App\Models;
use \App\Enums as E;
class t_order_student_month_list extends \App\Models\Zgen\z_t_order_student_month_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list_by_month($month){
        $where_arr=[
            ["month=%u",$month,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

}











