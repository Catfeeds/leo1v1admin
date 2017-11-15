<?php
namespace App\Models;
use \App\Enums as E;
class t_student_cc_to_cr extends \App\Models\Zgen\z_t_student_cc_to_cr
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_stu_info_by_orderid($orderid){
        $sql = $this->gen_sql_new("select * from %s where orderid = %d order by id desc limit 1",
                                  self::DB_TABLE_NAME,
                                  $orderid
        );

        return $this->main_get_row($sql);
    }

    public function get_id_by_orderid($orderid){
        $sql = $this->gen_sql_new("select id from %s where orderid = %d order by id desc limit 1  "
                                  ,self::DB_TABLE_NAME,
                                  $orderid
        );

        return $this->main_get_value($sql);
    }


    public function get_post_time_by_orderid($orderid){
        $sql = $this->gen_sql_new("select post_time from %s where orderid = %d order by id desc limit 1"
                                  ,self::DB_TABLE_NAME,
                                  $orderid
        );

        return $this->main_get_value($sql);
    }

    public function get_last_id_reject_flag_by_orderid($orderid){
        $sql = $this->gen_sql_new("select reject_flag,id from %s where orderid = %d order by id desc limit 1"
                                  ,self::DB_TABLE_NAME,
                                  $orderid
        );

        return $this->main_get_row($sql);

    }

    public function get_cc_id_by_id($id){
        $sql = $this->gen_sql_new("select cc_id from %s where id=%d",
                                  self::DB_TABLE_NAME,
                                  $id
        );

        return $this->main_get_value($sql);
    }

    public function get_hand_over_stat_by_orderid($orderid){
        $sql = $this->gen_sql_new("select count(*) as num from %s where orderid = %d ",
                                  self::DB_TABLE_NAME,
                                  $orderid
        );

        return $this->main_get_value($sql);
    }

    public function get_reject_log_by_orderid($orderid){
        $sql = $this->gen_sql_new(" select ass_id, reject_info, reject_time, orderid from %s ".
                                  " where orderid = %d and reject_info<>''   ",
                                  self::DB_TABLE_NAME,
                                  $orderid
        );

        return $this->main_get_list($sql);
    }

    public function get_ass_openid($id){
        $where_arr = [
            "sc.id=$id"
        ];

        $sql = $this->gen_sql_new("  select m.wx_openid from %s sc "
                                  ." left join %s o on o.orderid=sc.orderid"
                                  ." left join %s s on s.userid=o.userid "
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on m.phone=a.phone "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_confirm_flag($userid){
        $where_arr = [
            "o.userid=$userid",
            "sc.confirm_flag=0",
            "o.contract_status in (1)",
            "o.contract_type in (0,1,3)"
        ];
        $sql = $this->gen_sql_new("  select 1 from %s sc"
                                  ." left join %s o on o.orderid=sc.orderid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }
}
