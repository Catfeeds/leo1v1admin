<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_level_month extends \App\Models\Zgen\z_t_seller_level_month
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list($adminid,$page_info){
        $where_arr = [
            ['adminid=%u',$adminid,-1],
        ];
        $sql = $this->gen_sql_new(
            " select * "
            ." from %s "
            ." where % s"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_row_by_adminid_month_date($adminid=-1,$month_date=-1){
        $where_arr = [
            ['adminid=%u',$adminid,-1],
            ['month_date=%u',$month_date,-1],
        ];
        $sql = $this->gen_sql_new(
            " select * "
            ." from %s "
            ." where % s limit 1 "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }
}











