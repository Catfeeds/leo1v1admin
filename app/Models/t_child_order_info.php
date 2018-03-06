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
        }elseif($channel==3){
            $where_arr[] = "c.channel = '建行分期'";
        }elseif($channel==2){
            $where_arr[] = "c.channel <> 'baidu' and c.channel <> '建行分期'";
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
        $last_paid_time = $due_date+4*86400;
        if($repay_status !=-1){
            if($repay_status==-2){
                $where_arr=[
                    "pr.repay_status in (2,3)" ,
                    "(pr.paid_time=0 or pr.paid_time>$last_paid_time)"
                ];
            }else{
                $where_arr=[
                    ["pr.repay_status=%u",$repay_status,-1], 
                ]; 
            }
        }
        


        $sql = $this->gen_sql_new("select s.userid,s.nick,o.order_time,o.pay_time order_pay_time,c.channel,"
                                  ." c.pay_time,c.pay_status,c.period_num,o.contract_status,o.contract_type,"
                                  ." s.grade,o.sys_operator,c.channel,c.price,o.price order_price,c.from_orderno,"
                                  ." o.lesson_left,s.type,s.assistantid,s.ass_assign_time,s.lesson_count_all,"
                                  ." s.lesson_count_left,o.lesson_total,o.default_lesson_count,o.competition_flag, "
                                  ." s.phone,c.parent_orderid,if(c.parent_name='',p.nick,c.parent_name) parent_name,s.subject_ex,c.child_orderid "
                                  .",pr.paid_time "
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

    public function get_total_price_by_parent_orderid($parent_orderid){
        $sql = $this->gen_sql_new("select sum(price) from %s where parent_orderid = %u and price>0",
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
                                  ." s.nick,o.orderid parent_orderid,c.price "
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


    public function get_period_info_by_userid($userid,$child_orderid=-1){
        $where_arr=[
            ["o.userid=%u",$userid,-1],
            ["c.child_orderid=%u",$child_orderid,-1],
            "c.child_order_type=2",
            "c.price>0",
            "c.pay_status=1",
            "o.pay_time>0",
            "o.lesson_left >0",
            "c.channel='baidu'"
        ];
        $sql = $this->gen_sql_new("select o.pay_time,o.price,c.price period_price,c.child_orderid,o.lesson_left,"
                                  ."o.discount_price,o.default_lesson_count,o.lesson_total,o.order_time   "
                                  ." from %s c left join %s o on c.parent_orderid = o.orderid "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_order_list_new_jack($userid){
        $sql=$this->gen_sql("select o.orderid,o.order_time,o.grade,o.lesson_total,"
                            ."o.contract_type,o.subject,o.taobao_orderid,o.default_lesson_count,f.flow_status, "
                            ." o.pre_pay_time,co.child_orderid ,co.pay_status,co.price ,co.period_num,co.child_order_type"
                            ." from %s co "
                            ." left join %s o on parent_orderid = o.orderid"
                            ." left join %s f on ( f.from_key_int = o.orderid  and f.flow_type=2002)"
                            ." where o.userid=%u and o.contract_type in (0,3,3001)"
                            ." and (o.order_status=0 or o.order_status =1 or o.order_status=2)"
                            ." and co.price>0 and o.orderid >0 "
                            ." order by co.parent_orderid desc"
                            ,self::DB_TABLE_NAME
                            ,t_order_info::DB_TABLE_NAME
                            ,t_flow::DB_TABLE_NAME
                            ,$userid
        );
        return $this->main_get_list($sql);
    }

    public function check_parent_order_is_period($parent_orderid){
        $where_arr=[
            ["parent_orderid = %u",$parent_orderid,-1],
            "child_order_type=2",
            "price>0"
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_all_payed_prder_info(){
        $sql = $this->gen_sql_new("select distinct p.parentid,o.userid,o.competition_flag,o.grade"
                                  ." from %s c left join %s o on c.parent_orderid = o.orderid"
                                  ." left join %s pc on pc.userid = o.userid"
                                  ." left join %s p on pc.parentid = p.parentid"
                                  ." left join %s s on o.userid = s.userid"
                                  ." where c.price>0 and c.pay_status>0 and s.is_test_user=0 and s.parentid>0 and c.child_order_type=2 and c.channel='baidu'",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_parent_child::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_other_child_order_list($parent_orderid,$userid){
        $sql = $this->gen_sql_new("select c.* from %s c"
                                  ." left join %s o on c.parent_orderid = o.orderid"
                                  ." where c.parent_orderid <> %u and o.userid = %u",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $parent_orderid,
                                  $userid
        );
        return $this->main_get_list($sql);
    }

    public function get_all_order_channel_info($page_info,$start_time,$end_time,$opt_date_type,$contract_type, $channel_origin,$channel,$user_name){
        $where_arr=[
            "c.pay_status=1",
            "s.is_test_user=0",
            "c.price>0"
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_type,$start_time,$end_time);

        if ($contract_type==-2) {
            $where_arr[]="o.contract_type in(0,1,3)" ;
            \App\Helper\Utils::logger("stu");

        }else if ( $contract_type==-3){
            $where_arr[]="o.contract_type in(0,3)" ;
        }else {
            $where_arr[]=["o.contract_type=%u" , $contract_type, -1];
        }
        if($channel_origin==1){
            $where_arr[]="c.channel like '%alipay%%'";
        }elseif($channel_origin==2){
            $where_arr[]="c.channel like '%wx%%'";
        }elseif($channel_origin==3){
            $where_arr[]="c.channel like '%建行%%'";
        }elseif($channel_origin==4){
            $where_arr[]="c.channel='baidu'";
        }elseif($channel_origin==100){
            $where_arr[]="c.channel=''";
        }
        if($channel==1){
            $where_arr[]="c.channel='alipay_pc_direct'";
        }elseif($channel==2){
            $where_arr[]="c.channel='alipay'";
        }elseif($channel==3){
            $where_arr[]="c.channel='wx_pub_qr'";
        }elseif($channel==4){
            $where_arr[]="c.channel='wx'";
        }elseif($channel==5){
            $where_arr[]="c.channel='建行分期'";
        }elseif($channel==6){
            $where_arr[]="c.channel='建行网关支付'";
        }elseif($channel==7){
            $where_arr[]="c.channel='baidu'";
        }elseif($channel==100){
            $where_arr[]="c.channel=''";
        }

                                    
        if ($user_name) {
            $where_arr[]= " (s.nick like '" . $this->ensql( $user_name)  . "%' "
                ." or s.parent_name like '" . $this->ensql(  $user_name) . "%' "
                ." or c.parent_name like '" . $this->ensql(  $user_name) . "%' "
                ." or s.phone like '" . $this->ensql(  $user_name) . "%' "
                ." or o.userid like '" . $this->ensql( $user_name) . "%') ";
        }

        $sql = $this->gen_sql_new("select s.nick,o.userid,o.grade,o.subject,o.contract_type,"
                                  ." o.default_lesson_count,o.lesson_total ,c.channel,c.price,"
                                  ." o.price order_price,c.from_orderno ,c.pay_time,o.order_time, "
                                  ." if(c.parent_name <> '',c.parent_name,s.parent_name) parent_name, "
                                  ." o.orderid,c.child_orderid "
                                  ." from %s c left join %s o on c.parent_orderid = o.orderid"
                                  ." left join %s s on o.userid = s.userid"
                                  ." where %s order by c.pay_time desc",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);


    }


}











