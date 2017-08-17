<?php
namespace App\Models;
use \App\Enums as E;
/**
 * @property t_admin_group_name  $t_admin_group_name
 */

class t_tq_call_info extends \App\Models\Zgen\z_t_tq_call_info
{
    public function __construct()
    {
        parent::__construct();
    }
    public function add($id, $uid, $phone, $start_time, $end_time, $duration, $is_called_phone, $record_url) {
        $sql=$this->gen_sql_new("insert ignore into %s (id, uid, phone, start_time, end_time, duration, is_called_phone, record_url) values( %u,%u,'%s',%u,%u,%u,%u,'%s' )",
                            self::DB_TABLE_NAME,
                            $id,
                            $uid,
                            $phone,
                            $start_time,
                            $end_time,
                            $duration,
                            $is_called_phone,
                            $record_url) ;
        return $this->main_insert($sql);
    }

    public function get_call_phone_list($page_num, $start_time,$end_time,$uid, $is_called_phone ,$phone,$seller_student_status ) {
        $where_arr=[
            ["m.uid=%u", $uid, -1] ,
            ["tq.is_called_phone=%u", $is_called_phone, -1] ,
            ["tq.phone='%s'", $phone, ''] ,
        ];
        if (!$phone) {
            $where_arr[]= ["tq.start_time>=%u", $start_time, -1] ;
            $where_arr[]=["tq.start_time<%u", $end_time, -1] ;

            $this->where_arr_add_int_or_idlist ($where_arr ,"seller_student_status", $seller_student_status );
        }
        $sql=$this->gen_sql_new(
            "select tq.*, account,seller_student_status from %s tq"
            . " left  join %s m on  tq.uid=m.tquin "
            . " left  join %s n on  n.phone= tq.phone  "
            . " left  join %s t on  t.userid= n.userid "
            ."  where  %s order by start_time ",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function tongi_list($start_time,$end_time,$account_role, $callerid) {

        $where_arr=[
            ["start_time>=%u", $start_time, -1] ,
            ["start_time<%u", $end_time, -1] ,
            ["m.account_role=%u" , $account_role, -1 ],
            ["m.uid=%u" , $callerid, -1 ],
        ];

        $sql=$this->gen_sql_new("select account,tq.uid, count(*)  all_count, ".
                                "sum(is_called_phone)  as is_called_phone_count, ".
                                "sum(duration)  as duration_count, ".
                                "count(distinct tq.phone )  as phone_count, "
                                ." 0 as called_phone_count  ".
                                "from %s tq , %s m  where  tq.uid=m.tquin and %s group by uid",
                                self::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr);

        $list=$this->main_get_list($sql,function($item){
            return $item["uid"];
        });

        $sql=$this->gen_sql_new(
            "select tq.uid,  ".
            "count( distinct tq.phone )  as called_phone_count ".
            "from %s tq , %s m  where  tq.uid=m.tquin and  %s  and is_called_phone=1    group by uid",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr);

        $sub_list=$this->main_get_list($sql);
        foreach($sub_list as $item) {
            $list[$item["uid"]]["called_phone_count"] = $item["called_phone_count"];
        }
        return $list;
    }

    public function tongji_tq_info_new($start_time,$end_time) {

        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select m.uid adminid,account,tq.uid, count(*)  all_count, ".
                                "sum(is_called_phone)  as is_called_phone_count, ".
                                "sum(duration)  as duration_count, ".
                                "count(distinct tq.phone )  as phone_count, "
                                ." 0 as called_phone_count  ".
                                "from %s tq , %s m  where  tq.uid=m.tquin and %s group by uid",
                                self::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr);

        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }
    public function tongji_tq_info_all($start_time,$end_time,$adminid_list=[],$adminid_all=[]) {

        $where_arr=[];
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_all);

        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select count(*)  tq_all_count, ".
                                "sum(duration)  as tq_duration_count ".
                                "from %s t join %s m on t.uid = m.tquin where %s ",
                                self::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr);

        return $this->main_get_row($sql);
    }


    public function tongji_tq_info_ex($start_time,$end_time,$admin_list,$order_str ) {
        $where_arr[]=$this->where_get_in_str("u.adminid",$admin_list);

        $sql=$this->gen_sql_new(
            "select sum( duration )  as duration_count,u.adminid adminid,m.account,g.group_name ".
            "from %s u".
            " left join %s g on u.groupid = g.groupid".
            " left join %s m on u.adminid = m.uid   ".
            " left join %s tq on (tq.uid=m.tquin and tq.start_time>=$start_time and tq.start_time<$end_time ) ".
            " where %s group by u.adminid order by duration_count $order_str limit 10",
            t_admin_group_user::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list($sql);
    }

    public function tongji_tq_info_asc_new($start_time,$end_time) {
        $day = date('Y-m-d',$start_time);
        $month = date('Y-m-01',$start_time);
        $no_attend_adminid_list = $this->t_admin_group_name->get_seller_no_attend_list($day,$month);

         $where_arr=[
            "g.main_type=2",
            "g.up_groupid<>0",
            "g.groupid not in (9,10,12)"
        ];

        if(!empty($no_attend_adminid_list)){
            $where_arr[] ="u.adminid not in".$no_attend_adminid_list;
        }

        $sql=$this->gen_sql_new("select sum(duration)  as duration_count,m.uid adminid,m.account,g.group_name ".
                                "from %s u".
                                " left join %s g on u.groupid = g.groupid".
                                " left join %s m on u.adminid = m.uid   ".
                                " left join %s tq on (tq.uid=m.tquin and tq.start_time>=%u and tq.start_time<%u) ".
                                " where %s group by u.adminid order by duration_count asc limit 10",
                                t_admin_group_user::DB_TABLE_NAME,
                                t_admin_group_name::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                self::DB_TABLE_NAME,
                                $start_time,
                                $end_time,
                                $where_arr);


        return $this->main_get_list($sql);
    }

    public function get_list($start_time, $end_time ) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select record_url  from %s where %s ",
            self::DB_TABLE_NAME, $where_arr );
        return $this->main_get_list($sql);
    }

    public function get_list_by_phone_uid($uid,$phone) {
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, "uid" ,$uid);
        $this->where_arr_add_str_field($where_arr, "phone" ,$phone);
        $sql = $this->gen_sql_new("select * ".
                                  "from %s ".
                                  "where %s order by start_time "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_list_ex($uid,$phone,$start_time ,$end_time, $is_called_phone=-1) {
        $where_arr = [];
        $this->where_arr_add_int_or_idlist($where_arr, "uid" ,$uid);
        $this->where_arr_add_str_field($where_arr, "phone" ,$phone);
        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time);
        $this->where_arr_add_int_or_idlist($where_arr, "is_called_phone" ,$is_called_phone);


        $sql = $this->gen_sql_new("select start_time ".
                                  "from %s ".
                                  "where %s order by start_time "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_list_ex_new($uid,$phone,$start_time ,$end_time, $is_called_phone=-1,$lesson_end = -1) {
        $where_arr = [];
        $this->where_arr_add_int_or_idlist($where_arr, "uid" ,$uid);
        $this->where_arr_add_str_field($where_arr, "phone" ,$phone);
        if($is_called_phone){ //定时刷新(要求拨通)
            $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time);
            $this->where_arr_add_int_or_idlist($where_arr, "is_called_phone" ,$is_called_phone);
        }else{//手动刷新
            $where_arr[] = "start_time>$lesson_end";
        }

        $sql = $this->gen_sql_new("select start_time ".
                                  "from %s ".
                                  "where %s order by start_time "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_list_by_phone($uid,$phone){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, "uid" ,$uid);
        $this->where_arr_add_str_field($where_arr, "phone" ,$phone);
        $sql = $this->gen_sql_new("select * ".
                                  "from %s ".
                                  "where %s order by start_time "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function add_tianrun_record($item) {

        /*
          [uniqueId] => 10.10.61.69-1502416848.11782
          [customerNumber] => 15601830297
          [customerProvince] => 上海
          [customerCity] => 上海
          [numberTrunk] => 02151368906
          [queueName] => 
          [cno] => 2001
          [clientNumber] => 02145947224
          [status] => 双方接听
          [startTime] => 2017-08-11 10:00:48
          [bridgeTime] => 2017-08-11 10:01:07
          [bridgeDuration] => 00:00:05
          [cost] => 0.000
          [totalDuration] => 00:00:24
          [recordFile] => 
          [inCaseLib] => 不在
          [score] => 0
          [callType] => 点击外呼
          [comment] => 无
          [taskName] => 
          [endReason] => 否
          [userField] => 
          [sipCause] => 200
        */

    }

}
