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

    public function add($id, $uid, $phone, $start_time, $end_time, $duration, $is_called_phone, $record_url ,$adminid=0, $admin_role=0,  $obj_start_time=0) {
        if ($adminid==0) {
            $admin_info=$this->task->t_manager_info->get_user_info_for_tq($uid);
            if ($admin_info){
                $adminid= $admin_info["uid"];
                $admin_role= $admin_info["account_role"];
                //update t_seller_student_new.cc_no_called_count
                if($admin_role == E\Eaccount_role::V_2){
                    $userid = $this->task->t_phone_to_user->get_userid($phone);
                    if($userid>0){
                        if($is_called_phone==1){//拨通
                            $count = $this->task->t_seller_student_new->field_get_value($userid,'cc_called_count');
                            $this->task->t_seller_student_new->field_update_list($userid,[
                                'cc_called_count'=>$count+1,
                                'cc_no_called_count'=>0,
                                'last_revisit_time'=>$start_time,
                                'last_revisit_time'=>$start_time,
                            ]);
                        }elseif($is_called_phone==0){//未拨通
                            $count = $this->task->t_seller_student_new->field_get_value($userid,'cc_no_called_count');
                            $count_new = $this->task->t_seller_student_new->field_get_value($userid,'cc_no_called_count_new');
                            $this->task->t_seller_student_new->field_update_list($userid,[
                                'cc_no_called_count'=>$count+1,
                                'last_revisit_time'=>$start_time,
                                'cc_no_called_count_new'=>$count_new+1,
                            ]);
                        }
                    }
                }
                //update t_seller_student_new.tmk_last_revisit_time
                if($admin_role == E\Eaccount_role::V_7){
                    $userid = $this->task->t_phone_to_user->get_userid($phone);
                    if($userid>0){
                        $this->task->t_seller_student_new->field_update_list($userid,[
                            'tmk_last_revisit_time'=>$start_time,
                        ]);
                    }
                }
            }
        }
        $sql=$this->gen_sql_new(
            " insert ignore into %s "
            ." (id, uid, phone, start_time, end_time, duration, is_called_phone, record_url,adminid, admin_role, obj_start_time) "
            ." values( %u,%u,'%s',%u,%u,%u,%u,'%s',%u,%u,%u)",
            self::DB_TABLE_NAME,
            $id,
            $uid,
            $phone,
            $start_time,
            $end_time,
            $duration,
            $is_called_phone,
            $record_url,
            $adminid,
            $admin_role,
            $obj_start_time
        ) ;
        return $this->main_insert($sql);
    }

    public function get_call_phone_list($page_num, $start_time,$end_time,$uid, $is_called_phone ,$phone,$seller_student_status ) {
        $where_arr=[
            ["tq.uid=%u", $uid, -1] ,
            ["tq.is_called_phone=%u", $is_called_phone, -1] ,
            ["tq.phone='%s'", $phone, ''] ,
        ];
        if (!$phone) {
            $where_arr[]= ["tq.start_time>=%u", $start_time, -1] ;
            $where_arr[]=["tq.start_time<%u", $end_time, -1] ;

            $this->where_arr_add_int_or_idlist ($where_arr ,"seller_student_status", $seller_student_status );
        }
        $sql=$this->gen_sql_new(
            "select tq.*, admin_role ,seller_student_status from %s tq"
            . " left  join %s n on  n.phone= tq.phone  "
            . " left  join %s t on  t.userid= n.userid "
            ."  where  %s order by start_time ",
            self::DB_TABLE_NAME,
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

        $sql=$this->gen_sql_new("select account,tq.uid, m.uid as adminid,  count(*)  all_count, ".
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


    public function get_list_for_post($start_time, $end_time ) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select * from %s where %s ",
            self::DB_TABLE_NAME, $where_arr );
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

    public function get_row_by_phone($phone){
        $where_arr = [];
        $this->where_arr_add_str_field($where_arr,'phone',$phone);
        $sql = $this->gen_sql_new("select * ".
                                  "from %s ".
                                  "where %s order by start_time limit 1 "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_last_call_by_phone($phone){
        $where_arr = [];
        $this->where_arr_add_str_field($where_arr,'phone',$phone);
        $sql = $this->gen_sql_new("select * ".
                                  "from %s ".
                                  "where %s order by start_time desc limit 1 "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
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

    public function get_agent_call_phone_list($page_num, $start_time,$end_time,$uid, $is_called_phone ,$phone,$seller_student_status ,$type) {
        $where_arr=[
            ["m.uid=%u", $uid, -1] ,
            ["tq.is_called_phone=%u", $is_called_phone, -1] ,
            ["tq.phone='%s'", $phone, ''] ,
            ["tq.start_time>=%u", $start_time, -1],
            ["tq.start_time<%u", $end_time, -1] ,
            ["a.type=%s", $type, -1] ,
        ];
        $this->where_arr_add_int_or_idlist ($where_arr ,"seller_student_status", $seller_student_status);

        $sql=$this->gen_sql_new(
            "select distinct tq.*, m.account,t.seller_student_status,m.account_role "
            ." from %s a"
            ." left join %s s on s.userid=a.userid "
            ." left join %s tq on a.phone=tq.phone "
            ." left join %s m on  tq.uid=m.tquin "
            ." left join %s n on  n.phone= tq.phone  "
            ." left join %s t on  t.userid= s.userid "
            ."  where  %s order by start_time ",
            t_agent::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function get_user_call_admin_count( $phone, $start_time ) {
        $where_arr=[
            "tq.phone" => $phone,
            "start_time> $start_time",
            "admin_role=2",
        ];
        $sql= $this->gen_sql_new(
            "select count( distinct tq.uid ) from %s tq"
            . " where %s  ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);

    }
    public function get_call_info_by_phone($phone){
        $sql = $this->gen_sql_new(
            "select is_called_phone,duration from %s "
            ."where phone='%s'"
            ,self::DB_TABLE_NAME
            ,$phone
        );
        return $this->main_get_list($sql);
    }
    public function get_acc_role($phone){
        $sql = $this->gen_sql_new(
            "select start_time,adminid,admin_role,is_called_phone from %s "
            ." where phone='%s'"
            ,self::DB_TABLE_NAME
            ,$phone
        );
        return $this->main_get_list($sql);
    }

    public function get_call_info_list($adminid,$phone){
        $sql = $this->gen_sql_new(
            "select start_time,adminid,record_url from %s "
            ." where phone='%s' and adminid = %u and is_called_phone=1 "
            ,self::DB_TABLE_NAME
            ,$phone
            ,$adminid
        );
        return $this->main_get_list($sql);
    }

    public function get_has_called_stu_num($start_time, $end_time){
        $where_arr = [
            "tq.admin_role=2"
        ];

        $this->where_arr_add_time_range($where_arr,"s.add_time",$start_time,$end_time);
        // $this->where_arr_add_time_range($where_arr,"tq.start_time",$start_time,$end_time);



        $sql=$this->gen_sql_new("  select count(distinct(s.userid)) from %s s"
                                ." left join %s tq on s.phone=tq.phone"
                                ." where  %s ",
                                t_seller_student_new::DB_TABLE_NAME,
                                self::DB_TABLE_NAME,
                                $where_arr
        );

        return $this->main_get_value($sql);

    }

    public function get_tq_succ_num($start_time, $end_time){
        $where_arr = [
            "tq.admin_role=2"
        ];

        $this->where_arr_add_time_range($where_arr,"tq.start_time",$start_time,$end_time);

        $sql=$this->gen_sql_new("  select count(*) from %s tq"
                                ." where  %s ",
                                self::DB_TABLE_NAME,
                                $where_arr
        );

        return $this->main_get_value($sql);
    }


    public function check_call_status($phone) {
        $where_arr=[
            ["tq.phone='%s'", $phone, ''] ,
        ];

        $sql=$this->gen_sql_new(" select max(tq.is_called_phone) from %s tq"
                                ."  where  %s ",
                                self::DB_TABLE_NAME,
                                $where_arr
        );

        return $this->main_get_value($sql);

    }


    public function get_cc_called_num($start_time, $end_time){
        $where_arr = [
            "tq.admin_role = 2"
        ];

        $this->where_arr_add_time_range($where_arr,"tq.start_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select count(distinct(adminid)) as cc_called_num from %s tq "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_cc_called_time($start_time, $end_time){
        $where_arr = [
            "tq.admin_role = 2 "
        ];

        $this->where_arr_add_time_range($where_arr,"tq.start_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select sum(tq.duration) from %s tq"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }


    public function get_call_info_row($tquin,$phone){
        $where_arr = [
            ['uid = %d',$tquin,-1],
            ["phone='%s'", $phone,-1],
            'is_called_phone = 1',
        ];
        $this->where_arr_add_time_range($where_arr,'start_time',time()-3600*24,time());
        $sql = $this->gen_sql_new(" select is_called_phone from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_call_info_row_new($adminid,$phone,$start_time){
        $where_arr = [
            ['adminid = %d',$adminid,-1],
            ["phone='%s'", $phone,-1],
        ];
        $this->where_arr_add_time_range($where_arr,'start_time',$start_time,time(null));
        $sql = $this->gen_sql_new(" select id from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
    /*
     *@desn:获取负责人联系次数
     *@date:2017-09-28
     *@author:Abner<abner@leo.edu.com>
     *@param:$adminid :拨打者id
     *@author:$phone : 拨打电话
     */

    public function get_cur_adminid_call_count($adminid,$phone){

        $sql = $this->gen_sql_new(
            "select count(*) from %s where phone='%s' and adminid = %u "
            ,self::DB_TABLE_NAME
            ,$phone
            ,$adminid
        );

        return $this->main_get_value($sql);
    }

    public function get_call_info_by_adminid_list($start_time, $end_time,$adminid_list,$adminid_all){
        $where_arr=[
            "m.account_role=2",
        ];
        $this->where_arr_add_time_range($where_arr,"tq.start_time",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_all);
        $sql=$this->gen_sql_new(
            "select count(*) call_count,sum(if(is_called_phone=1,1,0)) is_called_count,"
            ."sum(if(tq.end_time>0 and tq.obj_start_time>0 and is_called_phone=1,tq.end_time - tq.obj_start_time,0)) call_time_long, "
            ."m.uid adminid "
            ."  from %s tq"
            ."  left join  %s m on m.tquin=tq.uid"
            ." where  %s group by m.uid ",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }

    public function get_no_called_list($phone){
        $where_arr=[
            ['phone = %d',$phone,''],
        ];
        $sql=$this->gen_sql_new(
            " select is_called_phone,phone "
            ." from %s "
            ." where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_no_called_count_list($start_time,$end_time){
        $where_arr=[
            ['admin_role =%u',E\Eaccount_role::V_2],
        ];
        $this->where_arr_add_time_range($where_arr,'start_time',$start_time,$end_time);
        $sql=$this->gen_sql_new(
            " select phone,is_called_phone "
            ." from %s "
            ." where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_list(){
        $where_arr=[
            ['admin_role =%u',E\Eaccount_role::V_2],
            'is_called_phone =1',
        ];
        $sql=$this->gen_sql_new(
            " select phone "
            ." from %s "
            ." where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_time_by_phone_adminid($adminid,$phone){
        $where_arr = [
            ["adminid=%u",$adminid,-1],
            ["phone=%u",$phone,-1],
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by end_time desc limit 1",
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_row($sql);
    }


    public function get_all_info_group_by_phone(){
        $sql = "select count(*) as total,phone   from db_weiyi_admin.t_tq_call_info where is_called_phone=0 and admin_role = 2  and start_time < 1512403200 group by phone";
        return $this->main_get_list($sql);
    }

    public function get_all_info_by_cc(){
        $sql = "select m.account ,t.adminid, count(distinct(t.phone)) as total_user, 
        sum(if((o.price>0 and o.contract_type =0 and o.contract_status <> 0 and o.order_time > 1509465600),o.price,0)) as total_money,
        sum(if((o.price>0 and o.contract_type =0 and o.contract_status <> 0 and o.order_time > 1509465600),1,0)) as total_num from db_weiyi_admin.t_tq_call_info  t
left join db_weiyi.t_student_info s on s.phone = t.phone
left join db_weiyi.t_order_info o on s.userid = o.userid
left join db_weiyi_admin.t_manager_info m on m.uid = t.adminid
where t.start_time > 1509465600 and t.start_time < 1512057600  and t.admin_role =2 group by t.adminid";
        return $this->main_get_list($sql);
    }

    public function get_all_info_by_cc_new(){
        $sql = "select adminid, count(distinct(phone)) as total_con_user from db_weiyi_admin.t_tq_call_info  
        where start_time > 1509465600 and start_time < 1512057600 and is_called_phone=1 and admin_role =2 group by adminid";
        return $this->main_get_list($sql,function($item){
               return $item["adminid"];
        }); 

    }
}
