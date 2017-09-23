<?php
namespace App\Models;
use \App\Enums as E;
class t_grab_lesson_link_visit_operation extends \App\Models\Zgen\z_t_grab_lesson_link_visit_operation
{
	public function __construct()
	{
		parent::__construct();

	}

    public function get_operationid_by_tea_requireid($teacherid,$requireid){
        $where_arr = [
            "teacherid='$teacherid'",
            "requireid='$requireid'",
        ];
        $sql = $this->gen_sql_new("select operationid "
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }


}











