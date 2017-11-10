<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_cash extends \App\Models\Zgen\z_t_agent_cash
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:获取体现订单
    //@param: $nickname 微信昵称
    //@param:$start_time 起始时间
    //@param:$end_time 结束时间
    //@param:$opt_date_str 时间类型 
    //@param:$cash_range  提现范围
    //@param:$check_money_admin_id  审核人id
    public function get_agent_cash_list($page_info,$agent_check_money_flag,$phone,$nickname,$start_time,$end_time,$opt_date_str,$cash_range,$check_money_admin_id){
        $where_arr=[
            ["a.phone='%s'", $phone, ""],
        ];
        if($nickname)
            $where_arr[] = ["a.nickname like '%s%%' ",$nickname,null];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add_int_or_idlist ($where_arr,"cash",$cash_range);
        $this->where_arr_add_int_field($where_arr,'ac.check_money_flag',$agent_check_money_flag);
        if($check_money_admin_id == -1)
            $where_arr[] = ['ac.check_money_adminid <> %u',301];
        else
            $this->where_arr_add_str_field($where_arr,'ac.check_money_adminid',$check_money_admin_id);

        $sql=$this->gen_sql_new("select ac.*,a.nickname,a.phone,a.bankcard,a.bank_type,a.bank_account,"
                                ."a.bank_address,a.bank_phone,a.bank_province,a.bank_city,"
                                ."a.zfb_name,a.zfb_account,all_yxyx_money,all_open_cush_money,all_have_cush_money "
                                ." from %s ac "
                                ." left join %s a on a.id = ac.aid "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,t_agent::DB_TABLE_NAME
                                ,$where_arr
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

    public function get_have_cash($aid, $check_money_flag= -1 ){
        $where_arr =[
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"check_money_flag",$check_money_flag);

        $sql = $this->gen_sql_new(
            "select sum(cash) have_cash "
            ." from %s  "
            ." where aid=%u and %s "
            ,self::DB_TABLE_NAME
            ,$aid, $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户体现中金额
    public function get_is_cashing_money($agent_id){
        $where_arr = [
            ['aid = %u',$agent_id,'-1'],
            'check_money_flag' => 0,
        ];
        $sql = $this->gen_sql_new(
            "select count(cash) from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
}











