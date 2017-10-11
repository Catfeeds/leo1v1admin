<?php
namespace App\Models;
use \App\Enums as E;
class t_cr_week_month_info extends \App\Models\Zgen\z_t_cr_week_month_info
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_data_by_type($create_time,$type){
        $where_arr = [
            ["create_time=%u",$create_time,-1],
            ["type=%u",$type,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_row($sql);
    }
    public function get_student_list_new($type,$create_time){
    	if($type == 2 || $type == 3){
    		$where_arr = [
	            ["create_time=%u",$create_time,-1],
	            " type=2 or type =3",
	        ];
    	}else if($type ==1){
    		$where_arr = [
	            ["create_time=%u",$create_time,-1],
	            " type=1",
	        ];
    	}

        $sql = $this->gen_sql_new("select student_list  from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }
    public function get_info_by_type_and_time($type,$create_time){
        $where_arr = [
            ["create_time=%u",$create_time,-1],
            ["type=%u",$type,-1]
        ];
        $sql = $this->gen_sql_new("select id from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_tongji(){
        $sql = "select count(s.userid) as total_student , sum(if(o.orderid>0 and contract_type = 0 and   contract_status>0, 1,0)) as total_order,  sum(if(o.orderid>0 and contract_type = 3 and   contract_status>0, 1,0)) as total_renew_order , sum(if(k.global_tq_called_flag=2,1,0)) as total_call from db_weiyi.t_student_info s left join db_weiyi.t_order_info o on s.userid = o.userid left join db_weiyi.t_seller_student_new k on k.userid = s.userid where reg_time > 1475251200 and reg_time < 1506787200 and is_test_user  =0 ";
        return $this->main_get_row($sql);
    }
    public function get_tongji2(){
        $sql = "select userid,phone from t_student_info where phone_location='免商店充值卡 ' or phone_location='' or phone_location = '鹏博士' and is_test_user = 0  and reg_time > 1475251200 and reg_time < 1506787200 ";
        return $this->main_get_list($sql);
    }
}