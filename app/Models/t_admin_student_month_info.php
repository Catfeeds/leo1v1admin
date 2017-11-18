<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_student_month_info extends \App\Models\Zgen\z_t_admin_student_month_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info(){
        $sql = $this->gen_sql_new("select * from %s ",self::DB_TABLE_NAME);
        return $this->main_get_list($sql,function($item){
            return $item["month"];
        });
    }



}











