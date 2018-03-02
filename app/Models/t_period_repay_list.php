<?php
namespace App\Models;
use \App\Enums as E;
class t_period_repay_list extends \App\Models\Zgen\z_t_period_repay_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_order_repay_info($orderid){
        $where_arr=[
            ["orderid = %u",$orderid,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_repay_order_info($due_date){
        $where_arr=[
            ["due_date = %u",$due_date,-1],
            // "orderid in (516,6932,7405,7457,7460,7586,7854,7892,8088,8159,8473,8666,8704,8753,9014)"
        ];
        $sql = $this->gen_sql_new("select orderid from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_period_order_overdue_warning_info($due_date,$repay_status=3,$type=-1,$wx_send_flag=-1,$userid=-1){
        $where_arr=[
            ["p.due_date = %u",$due_date,-1],
            ["p.repay_status = %u",$repay_status,-1],
            ["s.type = %u",$type,-1],
            ["s.userid = %u",$userid,-1],
            "s.is_test_user=0",           
            "r.orderid is null"
        ];
        if($wx_send_flag==1){
            $where_arr[]="p.warning_wx_send_flag=0";
        }elseif($wx_send_flag==2){
            $where_arr[]="p.stop_wx_send_flag=0";
        }
        $sql = $this->gen_sql_new("select o.userid,p.repay_status,pa.wx_openid,m.uid,"
                                  ."s.nick,c.from_orderno,p.orderid,p.period,o.sys_operator  "
                                  ." from %s p"
                                  ." left join %s c on p.orderid=c.child_orderid"
                                  ." left join %s o on c.parent_orderid = o.orderid"
                                  ." left join %s s on o.userid = s.userid"
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s pa on s.parentid = pa.parentid"
                                  ." left join %s r on o.orderid = r.orderid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_child_order_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_order_refund::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_no_first_overdue_repay_list($due_date,$orderid=-1){
        $where_arr=[
            ["p.due_date = %u",$due_date,-1],
            ["p.orderid = %u",$orderid,-1],
            "p.repay_status = 3",
            "s.type not in (1,6)",
            "s.is_test_user=0",
            "p.period >1"
        ];

        $sql = $this->gen_sql_new("select o.userid,p.repay_status,s.nick,c.from_orderno,p.orderid,p.period  "
                                  ." from %s p"
                                  ." left join %s c on p.orderid=c.child_orderid"
                                  ." left join %s o on c.parent_orderid = o.orderid"
                                  ." left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_child_order_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function check_overdue_history_flag($due_date,$orderid){
        $where_arr=[
            ["due_date < %u",$due_date,-1],
            ["orderid = %u",$orderid,-1],
            "repay_status in (2,3)",
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_paid_money_all($orderid){
        $where_arr=[
            ["orderid = %u",$orderid,-1],
        ];
        $sql = $this->gen_sql_new("select sum(paid_money) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_no_repay_list(){
        $where_arr=[
            "p.repay_status in (2,3)",
            "s.is_test_user=0",
        ];

        $sql = $this->gen_sql_new("select o.userid,p.repay_status,s.nick,c.from_orderno,p.orderid,"
                                  ."p.period,paid_time ,due_date   "
                                  ." from %s p"
                                  ." left join %s c on p.orderid=c.child_orderid"
                                  ." left join %s o on c.parent_orderid = o.orderid"
                                  ." left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_child_order_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);

    }


}











