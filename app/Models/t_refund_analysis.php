<?php
namespace App\Models;
use \App\Enums as E;
class t_refund_analysis extends \App\Models\Zgen\z_t_refund_analysis
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_list($orderid, $apply_time) {
        $where_arr=[
            "orderid"    => $orderid,
            "apply_time" => $apply_time
        ];
        $sql=$this->gen_sql_new(
            "select * from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function delete_by_order_apply_time( $orderid, $apply_time) {
        $where_arr = [
            "orderid"    => $orderid,
            "apply_time" => $apply_time
        ];
        $sql=$this->gen_sql_new("delete from %s  where  %s ",
                     self::DB_TABLE_NAME,
                     $where_arr
        );
        return $this->main_update($sql);
    }

    public function clear_by_order_apply_time ($orderid, $apply_time) {
        $where_arr = [
            "orderid"    => $orderid,
            "apply_time" => $apply_time
        ];
        $sql=$this->gen_sql_new("update %s set other_reason='', qc_analysia='', reply=''  where  %s ",
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function update_reply ($orderid, $apply_time, $other_reason, $qc_analysia, $reply) {
        $where_arr = [
            "orderid"    => $orderid,
            "apply_time" => $apply_time
        ];

        $sql=$this->gen_sql_new("update %s set other_reason= '".$other_reason."', qc_analysia='".$qc_analysia."', reply='".$reply."'  where  %s ",
                self::DB_TABLE_NAME,
                $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_configid_by_orderid($orderid){
        $sql = $this->gen_sql_new("select configid ,score from %s r where orderid=$orderid",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_ass_list($orderid, $apply_time) {
        $where_arr=[
            "orderid"    => $orderid,
            "apply_time" => $apply_time
        ];
        $sql=$this->gen_sql_new(
            "select oo.id from %s r "
            ." left join %s o on r.configid = o.id"
            ." left join %s oo on (o.key1= oo.key1 and oo.key2= 0 and oo.key3= 0 and oo.key4= 0) "
            ." where %s and oo.value= '助教部' ",
            self::DB_TABLE_NAME,
            t_order_refund_confirm_config::DB_TABLE_NAME,
            t_order_refund_confirm_config::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }





}
