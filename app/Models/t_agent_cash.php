<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_cash extends \App\Models\Zgen\z_t_agent_cash
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_agent_cash_list($page_info){
        $sql=$this->gen_sql_new("select ac.*,a.nickname,a.phone,a.bankcard,a.bank_type,a.zfb_name,a.zfb_account , all_yxyx_money ,all_open_cush_money, all_have_cush_money "
                                ." from %s ac "
                                ." left join %s a on a.id = ac.aid "
                                ,self::DB_TABLE_NAME
                                ,t_agent::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_cash_list_by_phone($phone){
        $sql = $this->gen_sql_new(
            " select ac.aid,ac.cash,ac.check_money_flag,ac.create_time,a.phone "
            ." from %s ac "
            ." left join %s a on a.id = ac.aid "
            ." where a.phone=%d ",
            self::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            $phone
        );
        return $this->main_get_list($sql);
    }

    public function get_cash_by_phone($phone){
        $where_arr = [
            ['a.phone = "%s"',$phone],
        ];
        $sql = $this->gen_sql_new(
            "select sum(ac.cash) have_cash "
            ." from %s ac "
            ." left join %s a on a.id=ac.aid "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_agent::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_have_cash($aid){
        $sql = $this->gen_sql_new(
            "select sum(cash) have_cash "
            ." from %s  "
            ." where aid=%u "
            ,self::DB_TABLE_NAME
            ,$aid
        );
        return $this->main_get_value($sql);
    }

}











