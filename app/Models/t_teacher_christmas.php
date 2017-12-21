<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_christmas extends \App\Models\Zgen\z_t_teacher_christmas
{
	public function __construct()
	{
		parent::__construct();
	}

    public function checkHasAdd($main_pid,$next_openid){
        $where_arr = [
            "teacherid=$main_pid",
            "next_openid=$next_openid"
        ];
        $sql = $this->gen_sql_new("  select id from %s tc"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }
}











