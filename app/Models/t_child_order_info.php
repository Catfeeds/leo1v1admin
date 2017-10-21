<?php
namespace App\Models;
use \App\Enums as E;
class t_child_order_info extends \App\Models\Zgen\z_t_child_order_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_child_order_info($orderid,$child_order_type=-1){
        $where_arr=[
            ["child_order_type=%u",$child_order_type,-1],                 
            ["parent_orderid=%u",$orderid,-1],                 
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_info_by_parent_orderid($parent_orderid,$child_order_type){
        $sql = $this->gen_sql_new("select * from %s where parent_orderid = %u and child_order_type=%u",
                                  self::DB_TABLE_NAME,
                                  $parent_orderid,
                                  $child_order_type
        );
        return $this->main_get_row($sql);

    }

    public function get_child_orderid($orderid,$child_order_type){
        $sql = $this->gen_sql_new("select child_orderid from %s where parent_orderid=%u and child_order_type=%u",
                                  self::DB_TABLE_NAME,
                                  $orderid,
                                  $child_order_type
        );
        return $this->main_get_value($sql);
    }

    public function chick_all_order_have_pay($parent_orderid,$pay_status=0){
        $where_arr=[
            ["pay_status=%u",$pay_status,-1] 
        ];
        $sql = $this->gen_sql_new("select 1 from %s where parent_orderid=%u and price>0 and %s",
                                  self::DB_TABLE_NAME,
                                  $parent_orderid,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_all_period_order_info($start_time,$end_time,$opt_date_str,$page_info,$pay_status,$contract_status,$contract_type,$channel,$userid,$parent_orderid,$child_orderid,$repay_status){
        $where_arr=[
            ["c.pay_status=%u",$pay_status,-1],
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);

        if ($contract_type==-2) {
            $where_arr[]="contract_type in(0,1,3)" ;
            \App\Helper\Utils::logger("stu");

        }else if ( $contract_type==-3){
            $where_arr[]="contract_type in(0,3)" ;
        }else {
            $where_arr[]=["contract_type=%u" , $contract_type, -1];
        }

        if ($contract_status ==-2) {
            $where_arr[] = "contract_status <> 0";
        }else{
            $where_arr[]=["contract_status=%u",$contract_status,-1];
            // $this->where_get_in_str_query("contract_status",$contract_status);
        }
        if($channel==1){
             $where_arr[] = "c.channel = 'baidu'";
        }elseif($channel==2){
             $where_arr[] = "c.channel <> 'baidu'";
        }
        
        if($userid != -1){
            $where_arr=[
                ["s.userid=%u",$userid,-1]
            ];
        }
        if($parent_orderid != -1){
            $where_arr=[
                ["c.parent_orderid=%u",$parent_orderid,-1]
            ];
        }
        if($child_orderid != -1){
            $where_arr=[
                ["c.child_orderid=%u",$child_orderid,-1]
            ];
        }

        //百度分期当期还款时间计算
        $d= date("d");
        if($d>=15){            
            $month_start = strtotime(date("Y-m-01",time()));
            $due_date = $month_start+14*86400;
        }else{
            $last_month = strtotime("-1 month",time());
            $month_start = strtotime(date("Y-m-01",$last_month));
            $due_date = $month_start+14*86400;
        }

        if($repay_status !=-1){
            $where_arr=[
                ["pr.repay_status=%u",$repay_status,-1], 
            ];
        }
        


        $sql = $this->gen_sql_new("select s.userid,s.nick,o.order_time,o.pay_time order_pay_time,c.channel,"
                                  ." c.pay_time,c.pay_status,c.period_num,o.contract_status,o.contract_type,"
                                  ." s.grade,o.sys_operator,c.channel,c.price,o.price order_price,c.from_orderno,"
                                  ." o.lesson_left,s.type,s.assistantid,s.ass_assign_time,s.lesson_count_all,"
                                  ." s.lesson_count_left,o.lesson_total,o.default_lesson_count,o.competition_flag, "
                                  ." s.phone,c.parent_orderid,if(c.parent_name='',p.nick,c.parent_name) parent_name,s.subject_ex,c.child_orderid "
                                  ." from %s c left join %s o on c.parent_orderid=o.orderid"
                                  ." left join %s s on o.userid = s.userid"
                                  ." left join %s p on s.parentid = p.parentid"
                                  ." left join %s pr on c.child_orderid = pr.orderid and pr.due_date =%u and c.channel='baidu' "
                                  ." where %s and c.price>0 and c.child_order_type=2",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_period_repay_list::DB_TABLE_NAME,
                                  $due_date,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }

    public function  del_contract ($parent_orderid) {
        $sql = sprintf("delete from %s "
                       . "where parent_orderid = %u ",
                       self::DB_TABLE_NAME,
                       $parent_orderid
        );
        return $this->main_update($sql);
    }

    public function set_all_order_payed_by_parent_orderid($parent_orderid){
        $sql = $this->gen_sql_new("update %s set pay_status = 1,"
                                  ." pay_time = %u "
                                  ." where parent_orderid = %u and price>0 and pay_status=0 "
                                  ,self::DB_TABLE_NAME
                                  ,time()
                                  ,$parent_orderid
        );
        return $this->main_update( $sql );
 
    }

    public function get_period_price_by_parent_orderid($parent_orderid){
        $sql = $this->gen_sql_new("select sum(price) from %s where parent_orderid = %u and child_order_type=2",
                                  self::DB_TABLE_NAME,
                                  $parent_orderid
        );
        return $this->main_get_value($sql);
    }

    public function get_period_list($pay_status,$channel){
        $where_arr=[
            ["c.pay_status=%u",$pay_status,-1],
            ["c.channel='%s'",$channel,-1],
            "s.is_test_user=0",
            "o.orderid>0",
            "c.price>0",
            "c.child_order_type=2",            
        ];
        $sql = $this->gen_sql_new("select c.child_order_type,c.child_orderid,"
                                  ."c.pay_status,c.pay_time,c.from_orderno,c.channel,"
                                  ." s.nick "
                                  ." from %s c left join %s o on c.parent_orderid = o.orderid"
                                  ." left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr                                                                    
        );
        return $this->main_get_list($sql);

    }


    public function get_period_info_by_userid($userid){
        $where_arr=[
            ["o.userid=%u",$userid,-1],
            "c.child_order_type=2",
            "c.price>0",
            "c.pay_status=1",
            "o.pay_time>0"
        ];
        $sql = $this->gen_sql_new("select o.pay_time,o.price,c.price period_price,c.child_orderid"
                                  ." from %s c left join %s o on c.parent_orderid = o.parent_orderid "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }


}











