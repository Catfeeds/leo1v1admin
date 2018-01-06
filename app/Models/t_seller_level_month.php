<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_level_month extends \App\Models\Zgen\z_t_seller_level_month
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list($adminid,$page_info,$start_time){
        $where_arr = [
            ['adminid=%u',$adminid,-1],
            ['month_date=%u',$start_time,-1],
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

    public function get_all_list_new(){
        $where_arr = [];
        $sql = $this->gen_sql_new(
            " select l.id,l.adminid,l.seller_level,m.account,m.create_time "
            ." from %s l "
            ." left join %s m on m.uid=l.adminid "
            ." where % s"
            ,self::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_row_by_adminid_month_date($adminid=-1,$month_date=-1,$define_date=1509465600){
        $where_arr = [
            ['m.adminid=%u',$adminid,-1],
            ['m.month_date=%u',$month_date,-1],
            ['s.define_date=%u',$define_date,-1],
        ];
        $sql = $this->gen_sql_new(
            " select m.seller_level,"
            ."s.base_salary,s.sup_salary,s.per_salary "
            ." from %s m "
            ." left join %s s on s.seller_level=m.seller_level"
            ." where %s limit 1 "
            ,self::DB_TABLE_NAME
            ,t_seller_level_salary::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_item_list(){
        $where_arr = [
            ['month_date=%u',$month_date=1512057600,-1],
        ];
        $sql = $this->gen_sql_new(
            " select id "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

}











