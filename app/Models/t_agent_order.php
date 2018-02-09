<?php
namespace App\Models;
use \App\Enums as E;
class t_agent_order extends \App\Models\Zgen\z_t_agent_order
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_list($start_time=-1,$end_time=-1)
    {
        $where_arr = [];
        if($start_time && $end_time){
            $this->where_arr_add_time_range($where_arr,'ao.create_time',$start_time,$end_time);
        }
        $sql = $this->gen_sql_new ("select ao.*,"
                                   ." o.price "
                                   ." from %s ao "
                                   ." left join %s o on o.orderid = ao.orderid "
                                   ." left join %s a on a.id = ao.aid "
                                   ." where %s "
                                   ,self::DB_TABLE_NAME
                                   ,t_order_info::DB_TABLE_NAME
                                   ,t_agent::DB_TABLE_NAME
                                   ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_agent_order_info($page_info,$start_time=-1,$end_time=-1)
    {
        $where_arr = [];
        if($start_time && $end_time){
            $this->where_arr_add_time_range($where_arr,'ao.create_time',$start_time,$end_time);
        }
        $sql=$this->gen_sql_new ("select ao.*,"
                                 ." a.userid,a.phone phone,a.nickname nickname,a.create_time a_create_time, "
                                 ." aa.phone p_phone,aa.nickname p_nickname, "
                                 ." aaa.phone pp_phone,aaa.nickname pp_nickname, "
                                 ." o.price "
                                 ." from %s ao "
                                 ." left join %s a on a.id=ao.aid "
                                 ." left join %s aa on aa.id=ao.pid "
                                 ." left join %s aaa on aaa.id=ao.ppid "
                                 ." left join %s o on o.orderid=ao.orderid "
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,t_agent::DB_TABLE_NAME
                                 ,t_agent::DB_TABLE_NAME
                                 ,t_agent::DB_TABLE_NAME
                                 ,t_order_info::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_list_by_page( $sql,$page_info);
    }

    public function get_price_by_phone($phone){
        $where_arr = [
            'a1.phone = '.$phone.' or a2.phone = '.$phone,
        ];

        $sql = $this->gen_sql_new(" select ao.orderid,ao.p_price,ao.create_time order_time,ao.pp_price,"
                                  ."a1.phone p_phone,a2.phone pp_phone, "
                                  ." o.price pay_price,o.userid,o.pay_time,"
                                  ."s.parent_name "
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
        $sql = $this->gen_sql_new("select ao.orderid,a.phone p_phone,ao.p_price,ao.create_time order_time, "
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

    public function get_row_by_orderid($orderid){
        $where_arr = [
            ['orderid = %s ',$orderid],
        ];
        $sql = $this->gen_sql_new(
            " select * ".
            " from %s ".
            " where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_row_by_aid($aid){
        $where_arr = [
            ['aid = %u ',$aid],
        ];
        $sql = $this->gen_sql_new(
            " select * ".
            " from %s ".
            " where %s limit 1 ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function check_aid($aid ) {
        $sql=$this->gen_sql_new("select 1 from  %s where aid=%u ",
                           self::DB_TABLE_NAME,$aid
        );
        return $this->main_get_value($sql);
    }

    public function get_count_by_userid($userid ) {
        $sql = $this->gen_sql_new(
            "select count(*) from %s ao"
            ."left join  a %s on  ao.aid = a.id "
            . " where a.userid=%u ",
            self::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            $userid
        );
        return $this->main_get_value($sql);
    }
    public function row_delete_by_aid($aid) {
        $sql= $this->gen_sql_new (
            "delete from %s where aid=%u",
            self::DB_TABLE_NAME,
            $aid
        );
        return $this->main_update($sql);
    }
    //获取所有团长推荐人的签单量和签单金额
    public function get_colconel_order_info(){
        $sql = $this->gen_sql_new(
            "select sum(ao.orderid>0) as order_count,sum(oi.price) as order_money ".
            "from %s ao ".
            "left join %s oi on ao.orderid = oi.orderid ".
            "where ao.pid in (select distinct colconel_agent_id from %s)",
            self::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_agent_group::DB_TABLE_NAME
        );
        return $this->main_get_row($sql);
    }
    //获取所有团长推荐人的签单量和签单金额
    public function get_this_colconel_order_info($colconel_id){
        $where_arr = [
            ['ao.pid = %u',$colconel_id,'-1'],
        ];
        $sql = $this->gen_sql_new(
            "select sum(ao.orderid>0) as order_count,sum(oi.price) as order_money ".
            "from %s ao ".
            "left join %s oi on ao.orderid = oi.orderid ".
            "where %s",
            self::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    
    //@desn:获取推荐学员签单量、签单金额[无下限限制下级]
    //@param:$in_str child  id串
    //@param:$start_time  每月开始时间
    //@param:$end_time  每月结束时间
    public function get_cycle_child_order_info($in_str,$start_time,$end_time){
        $where_arr = [
            'ao.aid in '.$in_str,
            'oi.order_status in (1,2)',
            'oi.contract_type in (0,3) ',
        ];
        $this->where_arr_add_time_range($where_arr,"ao.create_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select sum(ao.orderid>0) child_order_count,sum(oi.price) child_order_money ".
            "from %s ao ".
            "left join %s oi on ao.orderid = oi.orderid ".
            "where %s ",
            self::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    //@desn:获取用户全部佣金奖励
    public function get_invite_child_reward($agent_id,$type,$page_info,$page_count){
        if($type == 1){
            $where_arr =[
                ['ao.pid = %u',$agent_id,'-1'],
            ];
        }else{
            $where_arr =[
                ['ao.ppid = %u',$agent_id,'-1'],
            ];
        }
        $sql = $this->gen_sql_new(
            "select a.phone,a.nickname,ao.p_price,oi.price,ao.create_time,oi.pay_time,a.userid,ao.p_price,ao.pp_price ".
            ",si.nick ".
            "from %s ao ".
            "left join %s a on ao.aid=a.id ".
            "left join %s si on si.userid = a.userid ".
            "left join %s oi on oi.orderid = ao.orderid ".
            "where %s order by ao.create_time desc",
            self::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr
        );
        dd($sql);

        return $this->main_get_list_by_page($sql,$page_info,$page_count);
    }
    //@desn:获取用户可提现佣金奖励
    //@param:$last_succ_cash_time 上次提现成功时间
    public function get_can_cash_commission_reward($agent_id,$type,$page_info,$page_count,$last_succ_cash_time){

        if($type == 1){
            $where_arr =[
                ['ao.pid = %u',$agent_id,'-1'],
                ['ao.p_open_price > %u',0]
            ];
            $where_arr_2 = [
                ['agent_id = %u',$agent_id],
                ['create_time >= %u',$last_succ_cash_time],
                'agent_income_type' => 3
            ];
        }else{
            $where_arr =[
                ['ao.ppid = %u',$agent_id,'-1'],
                ['ao.pp_open_price > %u',0]
            ];
            $where_arr_2 = [
                ['agent_id = %u',$agent_id],
                ['create_time >= %u',$last_succ_cash_time],
                'agent_income_type' => 4
            ];
        }
        $sql = $this->gen_sql_new(
            "select a.phone,a.nickname,ao.p_open_price,oi.price,ao.create_time,oi.pay_time,a.userid,ao.p_price,ao.pp_open_price ".
            ",si.nick,ao.aid ".
            "from %s ao ".
            "left join %s a on ao.aid=a.id ".
            "left join %s si on si.userid = a.userid ".
            "left join %s oi on oi.orderid = ao.orderid ".
            "where %s and ao.aid in (select distinct(child_agent_id) from %s where %s) ".
            "order by ao.create_time",
            self::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr,
            t_agent_income_log::DB_TABLE_NAME,
            $where_arr_2
        );

        return $this->main_get_list_by_page($sql,$page_info,$page_count);
    }
    //@desn:获取用户一级邀请人奖励之和
    public function get_l1_child_commission_reward($id){
        $sql = $this->gen_sql_new(
            "select sum(p_price) from %s where pid = %u",self::DB_TABLE_NAME,$id
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户二级邀请人奖励之和
    public function get_l2_child_commission_reward($id){
        $sql = $this->gen_sql_new(
            "select sum(pp_price) from %s where ppid = %u",self::DB_TABLE_NAME,$id
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户下单违规时间
    //@param: $to_agentid 用户优学优享id
    public function get_order_bad_time($to_agentid){
        $sql = $this->gen_sql_new(
            'select oi.order_time from %s ao '.
            'left join %s oi on ao.orderid = oi.orderid '.
            'where ao.aid = %u',
            t_agent_order::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $to_agentid
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户可提现的一级佣金奖励 [不包括用户已提现金额]  [包括部分本用户已提现]
    //@param:$agent_id 优学优享id
    //@param:$last_succ_cash_time 用户上次提现时间
    public function get_now_l1_commission_money($agent_id,$last_succ_cash_time){
        $where_arr = [
            ['agent_id = %u',$agent_id],
            ['create_time >= %u',$last_succ_cash_time],
            'agent_income_type' => 3
        ];
        $sql = $this->gen_sql_new(
            'select sum(p_open_price) from %s where aid in '.
            '(select distinct(child_agent_id) from %s where %s)',
            self::DB_TABLE_NAME,
            t_agent_income_log::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户可提现的二级佣金奖励 [不包括用户已提现金额]  [包括部分本用户已提现]
    //@param:$agent_id 优学优享id
    //@param:$last_succ_cash_time 用户上次提现时间
    public function get_now_l2_commission_money($agent_id,$last_succ_cash_time){
        $where_arr = [
            ['agent_id = %u',$agent_id],
            ['create_time >= %u',$last_succ_cash_time],
            'agent_income_type' => 4
        ];
        $sql = $this->gen_sql_new(
            'select sum(pp_open_price) from %s where aid in '.
            '(select distinct(child_agent_id) from %s where %s)',
            self::DB_TABLE_NAME,
            t_agent_income_log::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
}
