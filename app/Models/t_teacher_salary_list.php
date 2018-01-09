<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_salary_list extends \App\Models\Zgen\z_t_teacher_salary_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function check_money_is_exists($teacherid,$add_time){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["add_time=%u",$add_time,0],
        ];
        $sql = $this->gen_sql_new("select 1"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_salary_list($start_time,$end_time,$teacher_type=-1,$teacherid=-1){
        $where_arr = [
            ["ts.pay_time>=%u",$start_time,0],
            ["ts.pay_time<%u",$end_time,0],
            // ["ta.reference='%s'",$reference,""],
            "is_test_user=0",
            "ts.money!=0"
        ];
        if ($teacherid != -1) {
            array_push($where_arr, ["t.teacherid=%u", $teacherid, -1]);
        }
        if ($teacher_type == 1) { // 全职老师
            array_push($where_arr, "t.teacher_money_type=7 or (t.teacher_type=3 and t.teacher_money_type=0)");
        } elseif ($teacher_type == 2) { // 兼职老师
            array_push($where_arr, "t.teacher_money_type != 7 and (t.teacher_type != 3 or t.teacher_money_type != 0)");
        }
        $sql = $this->gen_sql_new("select ts.id,ts.pay_time,ts.add_time,"
                                  ." t.teacherid,t.realname,t.phone,t.level,t.bankcard,t.bank_address,t.bank_account,t.idcard,"
                                  ." t.bank_phone,t.bank_type,t.bank_province,t.bank_city,ts.money,ts.pay_status,"
                                  ." ts.is_negative,t.teacher_money_type,t.teacher_type,t.subject"
                                  ." from %s ts "
                                  ." left join %s t on ts.teacherid=t.teacherid "
                                  //." left join %s ta on t.phone=ta.phone"
                                  ." where %s "
                                  // ." group by t.teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  //,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function update_teacher_money($teacherid,$add_time,$money,$is_negative,$pay_time){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["add_time=%u",$add_time,0],
        ];
        $sql = $this->gen_sql_new("update %s set money=%u,is_negative=%u,pay_time=%u"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$money
                                  ,$is_negative
                                  ,$pay_time
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_teacher_money_info() {
        $sql = $this->gen_sql_new("select teacher_money_type,teacher_type,realname,teacherid from %s where is_test_user=0", t_teacher_info::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    // 全转兼 更新时间
    public function get_id_for_time($teacherid, $start_time, $end_time) {
        $where_arr = [
            ['teacherid=%u', $teacherid, 0],
            ['add_time>=%u', $start_time, 0],
            ['add_time<%u', $end_time, 0],
        ];
        $sql = $this->gen_sql_new("select id from %s where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }


}
