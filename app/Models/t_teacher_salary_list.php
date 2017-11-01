<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_salary_list extends \App\Models\Zgen\z_t_teacher_salary_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function check_money_is_exists($teacherid,$pay_time){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["pay_time=%u",$pay_time,0],
        ];
        $sql = $this->gen_sql_new("select 1"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
}











