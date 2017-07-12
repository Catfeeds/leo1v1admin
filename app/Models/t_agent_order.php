<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_order extends \App\Models\Zgen\z_t_agent_order
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_agent_order_info($page_info)
    {
        // $where_arr = array(
        //     array( "assistantid = %u", $assistantid, -1 ),
        // );
        // $this->where_arr_add_int_or_idlist($where_arr,"assistant_type",$is_part_time);

        // if($rate_score == 1){
        //     $where_arr[] = "(rate_score >= 10 and rate_score < 20)";
        // }elseif($rate_score == 2){
        //     $where_arr[] = "(rate_score >= 20 and rate_score < 30)";
        // }elseif($rate_score == 3){
        //     $where_arr[] = "(rate_score >= 30 and rate_score < 40)";
        // }elseif($rate_score == 4){
        //     $where_arr[] = "(rate_score >= 40 and rate_score < 50)";
        // }elseif($rate_score == 5){
        //     $where_arr[] = "rate_score > 50 ";
        // }

        $sql=$this->gen_sql_new ("select orderid,aid,pid,p_price,ppid,pp_price,create_time"
                                 ." from %s "
                                 ,self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page( $sql,$page_info);
    }

    public function get_price_by_phone($phone){
        $where_arr = [
            'a1.phone = '.$phone.' or a2.phone = '.$phone,
        ];

        $sql = $this->gen_sql_new(" select ao.orderid,a1.phone p_phone,ao.p_price,a2.phone pp_phone,ao.pp_price, "
                                  ." o.price pay_price,o.userid,o.pay_time,s.parent_name "
                                  ." from %s ao "
                                  ." left join %s a1 on a1.id = ao.pid "
                                  ." left join %s a2 on a2.id = ao.ppid "
                                  ." left join %s o on o.orderid = ao.orderid "
                                  ." left join %s s on s.userid = o.userid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_agent::DB_TABLE_NAME
                                  ,t_agent::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_p_price_by_phone($phone){
        $where_arr = [
            ['a.phone = %s ',$phone],
        ];
        $sql = $this->gen_sql_new("select ao.orderid,a.phone p_phone,ao.p_price, "
                                  ." o.price pay_price,o.userid,o.pay_time,s.parent_name "
                                  ." from %s ao "
                                  ." left join %s a on a.id = ao.pid "
                                  ." left join %s o on o.orderid = ao.orderid "
                                  ." left join %s s on s.userid = o.userid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_agent::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_pp_price_by_phone($phone){
        $where_arr = [
            ['a.phone = %s',$phone],
        ];

        $sql = $this->gen_sql_new("select sum(ao.pp_price) price "
                                  ." from %s ao "
                                  ." left join %s a on a.id = ao.ppid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_agent::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_p_order_by_phone($phone){
        $where_arr = [
            ['a.phone = %s',$phone],
        ];
        $sql = $this->gen_sql_new("select ao.orderid,ao.p_price price,o.userid "
                                  ." from %s ao "
                                  ." left join %s a on a.id = ao.pid "
                                  ." left join %s o on o.orderid = ao.orderid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_agent::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_pp_order_by_phone($phone){
        $where_arr = [
            // ['a.phone = %s',$phone],
            ['a.phone = %s','111'],
        ];

        $sql = $this->gen_sql_new("select ao.orderid,ao.pp_price price,o.userid "
                                  ." from %s ao "
                                  ." left join %s a on a.id = ao.ppid "
                                  ." left join %s o on o.orderid = ao.orderid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_agent::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_all_orderid(){
        $sql = $this->gen_sql_new(
            " select ao.orderid "
            ." from %s ao ",
            self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function add_agent_order_row($orderid,$pid,$p_price,$ppid,$pp_price){
        $this->row_insert([
            "orderid" => $orderid,
            "pid"    => $pid,
            "p_price"    => $p_price,
            "ppid"    => $ppid,
            "pp_price"    => $pp_price,
            "create_time" => time(null),
        ],true);
    }

    public function get_order_by_id($id){
        $where_arr = [
            // ' ao.aid in ('.$id_array_str.')',
        ];
        if($id){
            $this->where_arr_add_int_or_idlist($where_arr,'ao.aid',$id);
        }
        $sql = $this->gen_sql_new(
            " select ao.pid,s.nick,o.price "
            ." from %s ao "
            ." left join %s o on o.orderid = ao.orderid "
            ." left join %s s on s.userid = o.userid "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

}
