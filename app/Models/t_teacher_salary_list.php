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

    public function get_salary_list($start_time,$end_time,$reference=""){
        $where_arr = [
            ["pay_time>=%u",$start_time,0],
            ["pay_time<%u",$end_time,0],
            ["ta.reference='%s'",$reference,""],
            "is_test_user=0",
        ];
        $sql = $this->gen_sql_new("select t.teacherid,t.realname,t.phone,t.level,t.bankcard,t.bank_address,t.bank_account,t.idcard,"
                                  ." t.bank_phone,t.bank_type,t.bank_province,t.bank_city,ts.money,ts.pay_status "
                                  ." from %s ts "
                                  ." left join %s t on ts.teacherid=t.teacherid "
                                  ." left join %s ta on t.phone=ta.phone"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function update_teacher_money($teacherid,$pay_time,$money){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["pay_time=%u",$pay_time,0],
        ];
        $sql = $this->gen_sql_new("update %s set money=%u"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$money
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }


}
