<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_month_money extends \App\Models\Zgen\z_t_teacher_month_money
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($logtime,$confirm_flag,$pay_flag)
    {
        $where_arr=[
            ["confirm_flag=%u",$confirm_flag,-1],
            ["pay_flag=%u",$pay_flag,-1],
        ];
        $sql=$this->gen_sql_new("select * from   %s where  logtime=%u ",
                            self::DB_TABLE_NAME, $logtime);
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
         
    }

}











