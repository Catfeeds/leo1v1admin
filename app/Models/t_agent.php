<?php
namespace App\Models;
use \App\Enums as E;
class t_agent extends \App\Models\Zgen\z_t_agent
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_agent_list(){
        $where_arr = [
            // 'userid =0',
            // 'type =0',
        ];
        $sql=$this->gen_sql_new (" select *"
                                 ." from %s "
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );
        // dd($sql);
        return $this->main_get_list($sql);
    }

    public function get_agent_info($page_info,$phone,$type,$start_time,$end_time,$p_phone, $test_lesson_flag, $agent_level,$order_flag )
    {
        $where_arr = [];
        if($p_phone){
            $this->where_arr_add_str_field($where_arr,"aa.phone",$p_phone);
        }else if ( $phone ) {
            $this->where_arr_add_str_field($where_arr,"a.phone",$phone);
        }else {
            $this->where_arr_add_int_or_idlist($where_arr,"a.type",$type);
            $this->where_arr_add_int_or_idlist($where_arr,"a.agent_level",$agent_level);
            $this->where_arr_add_time_range($where_arr,"a.create_time",$start_time,$end_time);
            $this->where_arr_add_boolean_for_value($where_arr,"a.test_lessonid" ,$test_lesson_flag);
            $this->where_arr_add_boolean_for_value($where_arr,"ao.orderid" ,$order_flag,true );
        }

        $sql=$this->gen_sql_new (" select a.*,"
                                 ."aa.nickname p_nickname,aa.phone p_phone,"
                                 ."aaa.nickname pp_nickname,aaa.phone pp_phone,"
                                 ."s.origin,s.type student_stu_type,"
                                 ."l.lesson_start,l.lesson_user_online_status, "
                                 ."o.price,ao.p_level,ao.pp_level , ao.p_price,ao.pp_price "
                                 ." from %s a "
                                 ." left join %s aa on aa.id = a.parentid"
                                 ." left join %s aaa on aaa.id = aa.parentid"
                                 ." left join %s s on s.userid = a.userid"
                                 ." left join %s l on l.lessonid = a.test_lessonid"
                                 ." left join %s ao on ao.aid = a.id "
                                 ." left join %s o on o.orderid = ao.orderid "
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,t_student_info::DB_TABLE_NAME
                                 ,t_lesson_info::DB_TABLE_NAME
                                 ,t_agent_order::DB_TABLE_NAME
                                 ,t_order_info::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_list_by_page( $sql,$page_info);
    }

    public function get_agent_info_two(){
        $where_arr = [];
        $this->where_arr_add_int_or_idlist($where_arr,"a.type",1);
        $this->where_arr_add_time_range($where_arr,"a.create_time",1503504000,1503565200);
        $this->where_arr_add_int_or_idlist($where_arr,"s.type",0);

        $sql=$this->gen_sql_new (" select a.*,"
                                 ."aa.nickname p_nickname,aa.phone p_phone,"
                                 ."aaa.nickname pp_nickname,aaa.phone pp_phone,"
                                 ."s.origin,s.type student_stu_type,"
                                 ."l.lesson_start,l.lesson_user_online_status "
                                 ." from %s a "
                                 ." left join %s aa on aa.id = a.parentid"
                                 ." left join %s aaa on aaa.id = aa.parentid"
                                 ." left join %s s on s.userid = a.userid"
                                 ." left join %s l on l.lessonid = a.test_lessonid"
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,t_student_info::DB_TABLE_NAME
                                 ,t_lesson_info::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_agent_info_new($type)
    {
        /*
          $where_arr = [
          'n.admin_revisiterid >0',//assigned_count type=2 已分配销售
          'tmk_student_status=3',//tmk_assigned_count type=3 TMK有效
          'global_tq_called_flag=0',//tq_no_call_count type=5 未拨打
          'global_tq_called_flag <>0',//tq_called_count type=6 已拨打
          'global_tq_called_flag =1',//tq_call_fail_count type=7 未接通
          'global_tq_called_flag =2 and  n.sys_invaild_flag=0',//tq_call_succ_valid_count type=8 已拨通-有效
          'global_tq_called_flag =2 and  n.sys_invaild_flag =1',//tq_call_succ_invalid_count  type=9 已拨通-无效
          'global_tq_called_flag =1 and  n.sys_invaild_flag =1',//tq_call_fail_invalid_count  type=10 未接通-无效
          't.seller_student_status =100 and  global_tq_called_flag =2',//have_intention_a_count  type=11 有效意向(A)
          't.seller_student_status =101 and  global_tq_called_flag =2',//have_intention_b_count  type=12 有效意向(B)
          't.seller_student_status =102 and  global_tq_called_flag =2',//have_intention_c_count  type=13 有效意向(C)
          '',//require_count  type=14
          '',//test_lesson_count  type=15
          '',//succ_test_lesson_count   type=16
          type = 14
          select s.origin as check_value  , count(*) as require_count
          from  db_weiyi.t_test_lesson_subject_require  tr
          join  db_weiyi.t_test_lesson_subject  t on tr.test_lesson_subject_id = t.test_lesson_subject_id
          join  db_weiyi.t_student_info  s on t.userid= s.userid
          join  db_weiyi.t_seller_student_new  n on t.userid= n.userid
          where accept_flag =1 and is_test_user=0
          and require_admin_type =2
          and require_time>=1501516800
          and require_time<1504195200
          and s.origin in ('H5转介绍','优学优享','优学帮-0101','刘先生','张鑫龙')  group by  check_value
          type = 15,16
          select s.origin  as check_value , count(*) as test_lesson_count,
          sum(  success_flag in (0,1 ) ) as succ_test_lesson_count
          from db_weiyi.t_test_lesson_subject_require tr
          join db_weiyi.t_test_lesson_subject t  on tr.test_lesson_subject_id=t.test_lesson_subject_id
          join db_weiyi.t_seller_student_new n  on t.userid=n.userid
          join db_weiyi.t_test_lesson_subject_sub_list tss on tr.current_lessonid=tss.lessonid
          join db_weiyi.t_lesson_info l on tr.current_lessonid=l.lessonid
          join db_weiyi.t_student_info s on s.userid = l.userid
          where s.origin in ('H5转介绍','优学优享','优学帮-0101','刘先生','张鑫龙')
          and accept_flag=1
          and is_test_user=0
          and require_admin_type = 2
         */

        $where_arr = array();
        // $this->where_arr_add_str_field($where_arr,"s.origin",'优学优享');
        $this->where_arr_add_int_field($where_arr,"a.type",1);
        // if($type==2){ //已分配销售
        //     $where_arr[] = 'n.admin_revisiterid >0';
        // }elseif($type == 3){ //TMK有效
        //     $where_arr[] = 'n.tmk_student_status=3';
        // }elseif($type == 5){ //未拨打
        //     $where_arr[] = 'n.global_tq_called_flag=0';
        // }elseif($type == 6){ //已拨打
        //     $where_arr[] = 'n.global_tq_called_flag <>0';
        // }elseif($type == 7){ //未接通
        //     $where_arr[] = 'n.global_tq_called_flag =1';
        // }elseif($type == 8){ //已拨通-有效
        //     $where_arr[] = 'n.global_tq_called_flag =2 and  n.sys_invaild_flag=0';
        // }elseif($type == 9){ //已拨通-无效
        //     $where_arr[] = 'n.global_tq_called_flag =2 and  n.sys_invaild_flag =1';
        // }elseif($type == 10){ //未拨通-无效
        //     $where_arr[] = 'n.global_tq_called_flag =1 and  n.sys_invaild_flag =1';
        // }elseif($type == 11){ //有效意向(A)
        //     $where_arr[] = 't.seller_student_status =100 and  n.global_tq_called_flag =2';
        // }elseif($type == 12){ //有效意向(B)
        //     $where_arr[] = 't.seller_student_status =101 and  n.global_tq_called_flag =2';
        // }elseif($type == 13){ //有效意向(C)
        //     $where_arr[] = 't.seller_student_status =102 and  n.global_tq_called_flag =2';
        // }elseif($type == 14){ //预约数
        //     $where_arr[] = 'tr.accept_flag = 1 and s.is_test_user=0 and t.require_admin_type =2';
        // }elseif($type == 15){ //上课数
        //     $where_arr[] = 'tr.accept_flag = 1 and s.is_test_user=0 and t.require_admin_type =2';
        // }elseif($type == 16){ //试听成功数
        //     $where_arr[] = 'tr.accept_flag = 1 and s.is_test_user=0 and t.require_admin_type =2 and l.lesson_user_online_status=1';
        // }
        $sql=$this->gen_sql_new (" select a.*,"
                                 ." aa.nickname p_nickname,aa.phone p_phone,"
                                 ." aaa.nickname pp_nickname,aaa.phone pp_phone,"
                                 ." ao.orderid aoid,"
                                 ." o.price,"
                                 ." s.userid s_userid,s.origin,s.is_test_user, "
                                 ." n.admin_revisiterid,n.tmk_student_status,n.global_tq_called_flag,n.sys_invaild_flag,"
                                 ." t.seller_student_status,t.require_admin_type,"
                                 ." tr.accept_flag,"
                                 ." l.lesson_user_online_status,l.lesson_start "
                                 ." from %s a "
                                 ." left join %s aa on aa.id = a.parentid"
                                 ." left join %s aaa on aaa.id = aa.parentid"
                                 ." left join %s ao on ao.aid = a.id"
                                 ." left join %s o on o.orderid = ao.orderid"
                                 ." left join %s n on n.userid = a.userid"
                                 ." left join %s s on s.userid = a.userid"
                                 ." left join %s t on t.userid= a.userid "
                                 ." left join %s tr on tr.test_lesson_subject_id = t.test_lesson_subject_id "
                                 ." left join %s tss on tss.lessonid = tr.current_lessonid"
                                 ." left join %s l on l.lessonid = tss.lessonid"
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,t_agent_order::DB_TABLE_NAME
                                 ,t_order_info::DB_TABLE_NAME
                                 ,t_seller_student_new::DB_TABLE_NAME
                                 ,t_student_info::DB_TABLE_NAME
                                 ,t_test_lesson_subject::DB_TABLE_NAME
                                 ,t_test_lesson_subject_require::DB_TABLE_NAME
                                 ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                 ,t_lesson_info::DB_TABLE_NAME
                                 ,$where_arr
        );
        // return $this->main_get_list_by_page($sql,$page_info);
        return $this->main_get_list($sql);
    }

    public function get_type1_info($page_info)
    {
        $where_arr = array();
        $this->where_arr_add_int_field($where_arr,"a.type",1);
        $sql=$this->gen_sql_new (" select a.*,"
                                 ." aa.nickname p_nickname,aa.phone p_phone,"
                                 ." aaa.nickname pp_nickname,aaa.phone pp_phone,"
                                 ." s.origin"
                                 ." from %s a "
                                 ." left join %s aa on aa.id = a.parentid"
                                 ." left join %s aaa on aaa.id = aa.parentid"
                                 ." left join %s s on s.userid = a.userid"
                                 ." left join %s n on n.userid = a.userid"
                                 ." left join %s t on t.userid= a.userid "
                                 ." left join %s tr on tr.test_lesson_subject_id = t.test_lesson_subject_id "
                                 ." left join %s tss on tss.lessonid = tr.current_lessonid"
                                 ." left join %s l on l.lessonid = tss.lessonid"
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,t_student_info::DB_TABLE_NAME
                                 ,t_seller_student_new::DB_TABLE_NAME
                                 ,t_test_lesson_subject::DB_TABLE_NAME
                                 ,t_test_lesson_subject_require::DB_TABLE_NAME
                                 ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                 ,t_lesson_info::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_list_by_page( $sql,$page_info);
    }

    public function get_agent_info_by_phone($phone)
    {
        $where_arr = array();
        $this->where_arr_add_str_field($where_arr,"phone",$phone);

        $sql=$this->gen_sql_new ("select * "
                                 ." from %s where %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_agent_info_by_openid($wx_openid){
        $where_arr = array();
        $this->where_arr_add_str_field($where_arr,"wx_openid",$wx_openid);

        $sql=$this->gen_sql_new ("select * "
                                 ." from %s where %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_parent_phone_by_openid($wx_openid){
        $where_arr = array();
        $this->where_arr_add_str_field($where_arr,"a.wx_openid",$wx_openid);

        $sql=$this->gen_sql_new ("select a.id,a.parentid,aa.phone "
                                 ." from %s a "
                                 ." left join %s aa on aa.id=a.parentid"
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );

        return $this->main_get_row($sql);
    }


    public function get_agent_info_by_id($id){
        $where_arr = [
            ['id = %d',$id],
        ];

        $sql=$this->gen_sql_new ("select * "
                                 ." from %s where %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function xx(){
        $sql="update xx set f=10";
        /*
        $this->main_update($sql);
        $this->main_get_value("select f from Ts where uid=12");
        $this->main_get_row("select f,s from Ts where uid=12");
        $this->main_get_list("select f,s from Ts where uid=12");
        $this->main_get_list_by_page("select f,s from Ts where uid=12", 0,10);
        */
    }

    public function agent_row_del($wx_openid){
        $ret = $this->row_delete($wx_openid);
        return $ret;
    }

    public function get_agent_list_by_phone($phone){
        $where_arr = [
            //TODO
            'a2.phone = '.$phone.' or a1.phone ='.$phone,
        ];
        $sql=$this->gen_sql_new("select a1.phone phone,a2.phone p_phone,a1.id"
                                ." from %s a1 "
                                ." left join %s a2 on a2.id=a1.parentid "
                                ."where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function add_agent_row($parentid,$phone,$userid,$type){
        $ret = $this->row_insert([
            "parentid"    => $parentid,
            "phone"       => $phone,
            "userid"      => $userid,
            "type"        => $type,
            "create_time" => time(null),
        ],false,false,true);
        return $ret;
    }

    public function add_agent_row_new($phone,$headimgurl,$nickname,$wx_openid,$userid){
        $ret = $this->row_insert([
            "parentid"    => 0,
            "phone"       => $phone,
            "wx_openid"   => $wx_openid,
            "userid"      => $userid,
            "headimgurl"  => $headimgurl,
            "nickname"    => $nickname,
            "create_time" => time(null),
        ],false,false,true);
        if($ret){
            $ret = $this->get_last_insertid();
        }
        return $ret;
    }

    public function get_agent_count_by_id($id){
        $where_arr = [
            ['a.parentid = %d',$id],
            ['l.lesson_type = %d',2],
            ['l.lesson_del_flag = %d',0],
            'l.confirm_flag in (0,1) '
        ];
        $sql=$this->gen_sql_new("select count(a.id) "
                                ." from %s a "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s l on l.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_lesson_info::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_agent_level1_order_by_phone($phone){
        $where_arr = [
            ['a2.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];
        $sql=$this->gen_sql_new("select a.id,a.phone,s.userid,o.orderid,o.price/20 price "
                                ." from %s a "
                                ." left join %s a2 on a2.id = a.parentid "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s o on o.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_agent_level1_p_price_by_phone($phone){
        $where_arr = [
            ['a2.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];
        $sql=$this->gen_sql_new("select sum(o.price/20) sum "
                                ." from %s a "
                                ." left join %s a2 on a2.id = a.parentid "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s o on o.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_agent_level2_p_price_by_phone($phone){
        $where_arr = [
            ['a2.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];
        // $sql=$this->gen_sql_new("select sum(if(o.price/10>1000,1000,o.price/10)) price "
        $sql=$this->gen_sql_new("select o.* "
                                ." from %s a "
                                ." left join %s a2 on a2.id = a.parentid "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s o on o.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$where_arr
        );
        // dd($sql);
        return $this->main_get_row($sql);
    }

    public function get_agent_level2_p_order_by_phone($phone){
        $where_arr = [
            ['a2.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];
        $sql=$this->gen_sql_new("select a.id,a.phone,s.userid,o.orderid,if(o.price/10>1000,1000,o.price/10) price "
                                ." from %s a "
                                ." left join %s a2 on a2.id = a.parentid "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s o on o.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_agent_level2_pp_order_by_phone($phone){
        $where_arr = [
            ['a3.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];

        $sql = $this->gen_sql_new("select a.id,a.phone,s.userid,o.orderid,if(o.price/20>500,500,o.price/20) price  "
                                  ." from %s a "
                                  ." left join %s a2 on a2.id = a.parentid "
                                  ." left join %s a3 on a3.id = a2.parentid "
                                  ." left join %s s on s.phone = a.phone "
                                  ." left join %s o on o.userid = s.userid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_agent_level2_pp_price_by_phone($phone){
        $where_arr = [
            ['a3.phone = "%s"',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];

        $sql = $this->gen_sql_new("select sum(if(o.price/20>500,500,o.price/20)) price "
                                  ." from %s a "
                                  ." left join %s a2 on a2.id = a.parentid "
                                  ." left join %s a3 on a3.id = a2.parentid "
                                  ." left join %s s on s.phone = a.phone "
                                  ." left join %s o on o.userid = s.userid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_son_test_lesson_count_by_id($id, $check_time=-1 ){
        $where_arr=[
            "a.parentid = $id or aa.parentid = $id",
            "a.test_lessonid <> 0",
            "l.lesson_user_online_status =1",
            ["l.lesson_start < %u", $check_time , -1],
        ];

        $sql= $this->gen_sql_new(
            " select a.id,a.phone,a.test_lessonid "
            . " from %s a "
            . " left join %s aa on aa.id = a.parentid  "
            . " left join %s l on a.test_lessonid = l.lessonid "
            . " where %s order by l.lessonid asc ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_agent_test_lesson_count_by_id($id){
        $where_arr=[
            "a.parentid = $id or aa.parentid = $id",
            ['l.lesson_type = %d ',2],
            ['l.lesson_del_flag = %d ',0],
            ['l.lesson_status = %d ',2],
            'l.confirm_flag in (0,1) ',
            'l.lesson_user_online_status = 1',
            's.is_test_user = 0',
            'l.lesson_start > a.create_time',
        ];

        $sql= $this->gen_sql_new(
            " select a.id,a.phone,s.userid,l.lessonid "
            . " from %s a "//子级
            . " left join %s aa on aa.id = a.parentid "//父级
            . " left join %s s on s.userid = a.userid "
            . " left join %s l on s.userid = l.userid "
            . " where %s ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_userid(){
        $sql = $this->gen_sql_new(
            " select a.phone,s.userid "
            ." from %s a "
            ." left join %s s on s.phone = a.phone ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_pid_by_phone($phone){
        $sql = $this->gen_sql_new(
            " select a.phone,s.userid "
            ." from %s a "
            ." left join %s s on s.phone = a.phone ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_ppid_by_phone($phone){
        $sql = $this->gen_sql_new(
            " select a.phone,s.userid "
            ." from %s a "
            ." left join %s s on s.phone = a.phone ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_count_by_phone($phone){
        $where_arr=[
            ['a1.phone = %s ',$phone],
        ];

        $sql= $this->gen_sql_new(
            "select count(a.id) count "
            . " from %s a "
            . " left join %s a1 on a1.id = a.parentid "
            . " where %s ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_id_by_userid($userid) {
        $sql=$this->gen_sql_new("select id  from %s where userid=%u ",
                                self::DB_TABLE_NAME, $userid );
        return $this->main_get_value($sql);

    }

    public function get_id_by_phone($phone_str){
        $where_arr = [
            'phone in ('.$phone_str.')',
        ];
        $sql= $this->gen_sql_new(
            "select id,phone "
            . " from %s "
            . " where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_id_row_by_phone($phone){
        $sql= $this->gen_sql_new(
            "select id,phone "
            . " from %s "
            . " where phone=%s ",
            self::DB_TABLE_NAME,
            $phone
        );
        return $this->main_get_row($sql);
    }

    public function get_agent_info_row_by_phone($phone){
        $sql= $this->gen_sql_new(
            "select * "
            . " from %s "
            . " where phone = %s ",
            self::DB_TABLE_NAME,
            $phone
        );
        return $this->main_get_row($sql);
    }

    public function get_p_list_by_phone($phone){
        $sql = $this->gen_sql_new(
            " select a1.phone pp_phone,a2.phone p_phone,a2.create_time,a2.id p_id,a3.phone phone,a3.id id "
            ." from %s a1 "
            ." left join %s a2 on a2.parentid = a1.id "
            ." left join %s a3 on a3.parentid = a2.id "
            ." where a1.phone = %s "
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$phone
        );
        return $this->main_get_list($sql);
    }

    public function get_agent_order_by_phone($p_id){
        $where_arr = [];
        if($p_id){
            $this->where_arr_add_int_or_idlist($where_arr,'a.id',$p_id);
        }
        $sql = $this->gen_sql_new(
            " select a.id p_id,a.phone,a.userid,a.nickname,a.create_time p_create_time,"
            ." if(o.order_status,o.order_status,0) order_status "
            ." from %s a "
            ." left join %s ao on ao.aid=a.id "
            ." left join %s o on o.orderid=ao.orderid "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_agent_order::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_p_pp_id_by_phone($phone, $id=-1){
        $where_arr = [
            ['a.phone = "%s"',$phone,""],
            ['a.id= %d',$id,-1],
        ];
        $sql = $this->gen_sql_new(
            " select a.id,a.phone,a.parentid pid,a1.phone p_phone,a1.parentid ppid,a2.phone pp_phone".
            " from %s a ".
            " left join %s a1 on a1.id=a.parentid".
            " left join %s a2 on a2.id=a1.parentid".
            " where %s ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_test_new(){
        $where_arr = [
            'a.type = 1',
            "s.origin = '优学优享'",
        ];

        $sql=$this->gen_sql_new (" select a.id,a.phone"
                                 ." from %s a "
                                 ." left join %s s on s.phone = a.phone"
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,t_student_info::DB_TABLE_NAME
                                 ,$where_arr
        );

        return $this->main_get_list($sql);
    }
    public  function get_link_list_by_ppid($ppid) {
        $where_arr = [
            ['a2.id= %d',$ppid ],
        ];

        $sql = $this->gen_sql_new(
            " select"
            . " a1.userid as p_userid,a1.id as pid,  a1.nickname p_nick, a1.phone p_phone,  "
            . " a1.agent_level p_agent_level , a1.test_lessonid p_test_lessonid,    "
            . " a1.type p_agent_type, "
            . " a1.test_lessonid  p_test_lessonid, "

            . " a.userid as userid, a.id as id,  a.nickname nick, a.phone phone, "
            . " a.agent_level agent_level , a.test_lessonid test_lessonid , "
            . " a.type agent_type, "
            . " a.test_lessonid  test_lessonid ,"


            . " ao1.p_level o_p_agent_level, ao1.p_price o_p_price,  o1.price o_p_from_price, o1.pay_time o_p_from_pay_time,  o1.orderid  o_p_from_orderid, "
            . " ao.pp_level o_agent_level , ao.pp_price o_price ,  o1.price o_from_price , o.pay_time o_from_pay_time  ,  o.orderid  o_from_orderid "

            ." from %s a2 ".
            " left join %s a1 on a2.id=a1.parentid".
            " left join %s a on a1.id=a.parentid".

            " left join %s ao1 on a1.id=ao1.aid ".
            " left join %s o1 on ao1.orderid=o1.orderid ".

            " left join %s ao on a.id=ao.aid ".
            " left join %s o on ao.orderid=o.orderid ".
            " where %s ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_agent_order::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_agent_order::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr
        );
        return  $this->main_get_list($sql);
    }

    public  function get_link_map_list_by_ppid($ppid) {
        $list= $this->get_link_list_by_ppid($ppid);
        $map=[];
        foreach ($list as $item) {
            $pid=$item["pid"];
            $p_nick=$item["p_nick"];
            $p_userid=$item["p_userid"];
            $p_phone=$item["p_phone"];
            $p_agent_level=$item["p_agent_level"];
            $p_test_lessonid=$item["p_test_lessonid"];
            $id=$item["id"];
            $userid=$item["userid"];
            $nick=$item["nick"];
            $phone=$item["phone"];
            $agent_level=$item["agent_level"];
            $test_lessonid=$item["test_lessonid"];
            E\Eagent_level::set_item_value_str($item);
            E\Eagent_level::set_item_value_str($item,"p_agent_level");
            E\Eagent_type::set_item_value_str($item);
            E\Eagent_type::set_item_value_str($item,"p_agent_type");

            $item["test_lesson_flag"]= $item["test_lessonid"]>0?1:0;
            $item["p_test_lesson_flag"]= $item["p_test_lessonid"]>0?1:0;
            E\Eboolean::set_item_value_color_str($item,"p_test_lesson_flag" );
            E\Eboolean::set_item_value_color_str($item,"test_lesson_flag" );

            if ( !isset($map[$pid]) ){
                $item["list"]=[];
                $map[$pid]=$item ;
            }
            if( $id ) {
                $map[$pid]["list"][]= $item ;
            }
        }

        $ret_list=[];
        foreach ( $map as $p1 ) {
            $ret_list[ ] = [
                "p1_name"                 => $p1["p_nick"]."/".$p1["p_phone"],
                "p1_id"                    => $p1["pid"],
                "p1_test_lesson_flag_str" => $p1["p_test_lesson_flag_str"],
                "p1_price"                => $p1["o_p_from_price"]/100,
                "p1_p_agent_level"        => $p1["o_p_agent_level"],
                "p1_p_agent_level_str"        => E\Eagent_level::get_desc( $p1["o_p_agent_level"]),
                "p1_p_price"              => $p1["o_p_price"]/100,
            ] ;
            foreach ( $p1["list"] as $p2 ) {
                $ret_list[ ]= [
                    "p2_name"=> $p2["nick"]."/".$p2["phone"],
                    "p2_id"=> $p2["id"],
                    "p2_test_lesson_flag_str"=> $p2["test_lesson_flag_str"],
                    "p2_price"=> $p2["o_from_price"]/100,
                    "p2_p_agent_level"        => $p2["o_agent_level"],
                    "p2_p_agent_level_str"        => E\Eagent_level::get_desc( $p1["o_agent_level"]),
                    "p2_p_price"              => $p2["o_price"]/100,
                ] ;
            }
        }
        return $ret_list;
    }
    public function reset_user_info_test_lesson($id,$userid, $is_test_user, $check_time) {
        //重置试听信息
        $lessonid = 0;
        if($userid && $is_test_user == 0 ) {
            $ret = $this->task->t_lesson_info_b2->get_succ_test_lesson($userid,$check_time);
            if ($ret) {
                $lessonid = $ret['lessonid'];
            }
        }
        $this->field_update_list($id,[
            "test_lessonid" => $lessonid
        ]);
    }

    public function get_agent_level_by_check_time($id,$agent_info,$check_time ){
        $phone        = $agent_info['phone'];
        $create_time  = $agent_info['create_time'];
        $userid       = $agent_info['userid'];
        $wx_openid    = $agent_info['wx_openid'];
        $student_info = $this->task->t_student_info->field_get_list($userid,"*");
        $orderid = 0;
        if($userid){
            $order_info = $this->task->t_order_info->get_nomal_order_by_userid($userid,$check_time);
            if($order_info['orderid']){
                $orderid = $order_info['orderid'];
            }
        }
        $userid_new   = $student_info['userid'];
        $type_new     = $student_info['type'];
        $is_test_user = $student_info['is_test_user'];
        $level        = 0;

        if($userid
           && $type_new ==  E\Estudent_type::V_0
           && $is_test_user == 0
           && $orderid){//在读非测试
            $level     =  E\Eagent_level::V_2 ;
        }elseif($wx_openid){//有wx绑定
            $son_test_lesson = $this->get_son_test_lesson_count_by_id($id,$check_time);
            $count           = count($son_test_lesson);
            if($count>=2){ //>=两次试听
                $level     =  E\Eagent_level::V_2 ;
            }else{
                $level     =  E\Eagent_level::V_1 ;
            }
        }else{//非绑定
            $level =  E\Eagent_level::V_0;
        }
        return $level;
    }


    public function reset_user_info_order_info($id,$userid ,$is_test_user,$create_time) {
        //重置订单信息
        $this->task->t_agent_order->row_delete_by_aid($id);
        if($userid && $is_test_user == 0 ){
            $order_info = $this->task-> t_order_info->get_agent_order_info($userid ,$create_time);
            if ($order_info) {
                $orderid =  $order_info["orderid"] ;
                $agent_info = $this->get_p_pp_id_by_phone("", $id);
                $check_time= $order_info["pay_time"];
                $pid = $agent_info['pid'];
                $ppid = $agent_info['ppid'];
                $p_level=0;
                $pp_level=0;
                if ($pid) {
                    \App\Helper\Utils::logger("check  p_level pid=$pid");
                    $p_agent_info= $this->field_get_list($pid,"*");
                    $p_level=$this->get_agent_level_by_check_time($pid, $p_agent_info, $check_time );
                }

                if ($ppid) {
                    \App\Helper\Utils::logger("check  pp_level ppid=$ppid");
                    $pp_agent_info= $this->field_get_list($ppid,"*");
                    $pp_level=$this->get_agent_level_by_check_time($ppid, $pp_agent_info, $check_time );
                }

                $order_price= $order_info["price"];
                $price           = $order_price/100;
                $level1_price    = $price/20>500?500:$price/20;
                $level2_p_price  = $price/10>1000?1000:$price/10;
                $level2_pp_price = $price/20>500?500:$price/20;
                $p_price = 0;
                $pp_price = 0;

                if($p_level== 1){//黄金
                    $p_price = $level1_price*100;
                }elseif($p_level == 2){//水晶
                    $p_price = $level2_p_price*100;
                }

                if($pp_level== 2){//水晶
                    $pp_price = $level2_pp_price*100;
                }


                $this->task->t_agent_order->row_insert([
                    'orderid'     => $orderid,
                    'aid'         => $id,
                    'pid'         => $pid,
                    'p_price'     => $p_price,
                    'ppid'        => $ppid,
                    'pp_price'    => $pp_price,
                    'p_level'     =>$p_level,
                    'pp_level'     =>$pp_level,
                    'create_time' =>  $check_time,
                ]);
            }

        }
    }

    public function reset_user_info($id ) {
        $agent_info = $this->field_get_list($id,"*");
        $userid  = $agent_info["userid"];
        $agent_type= $agent_info["type"];
        $agent_student_status=0;
        if ($userid) {
            $student_info = $this->task->t_student_info->field_get_list($userid,"is_test_user");
            $is_test_user = $student_info['is_test_user'];
            $create_time = $agent_info['create_time'];
            $now=time(NULL);
            $this->reset_user_info_test_lesson($id,$userid,$is_test_user, $create_time );
            $this->reset_user_info_order_info($id,$userid,$is_test_user,$create_time);

            //是学员
            if (in_array( $agent_type, [E\Eagent_type::V_1 , E\Eagent_type::V_3]) ) {
                //检查合同
                if ($this->task->t_agent_order->check_aid($id) ) {
                    $agent_student_status=E\Eagent_student_status::V_50;
                }else{
                    $stu_info=$this->task->t_seller_student_new->field_get_list($userid,"global_tq_called_flag, global_seller_student_status,seller_resource_type") ;
                    if ($stu_info) {
                        $global_seller_student_status = $stu_info["global_seller_student_status"];
                        $global_tq_called_flag        = $stu_info["global_tq_called_flag"];
                        $seller_resource_type         = $stu_info["seller_resource_type"];
                        if ($seller_resource_type == E\Eseller_resource_type::V_0) { //新例子
                            if($global_tq_called_flag == 0 ) {
                                $agent_student_status=E\Eagent_student_status::V_0;
                            }else if ($global_tq_called_flag==1) {
                                $agent_student_status=E\Eagent_student_status::V_10;
                            }else if ( $global_seller_student_status<200) {
                                //E\Eseller_student_status
                                $agent_student_status=E\Eagent_student_status::V_20;
                            }else if ( $global_seller_student_status>=220) {
                                $agent_student_status=E\Eagent_student_status::V_30;
                            }else{
                                $agent_student_status=E\Eagent_student_status::V_40;
                            }
                        }else{
                            $agent_student_status=E\Eagent_student_status::V_100;
                        }

                    }

                }

            }

        }

        //重置当前等级
        $agent_level=$this->get_agent_level_by_check_time($id,$agent_info,time(NULL));
        $this->field_update_list($id,[
            "agent_level" => $agent_level,
            "agent_student_status" => $agent_student_status,
        ]);

        if ($agent_type==E\Eagent_type::V_2  &&  $userid ) {//是会员, 学员,
            $this->field_update_list($id,[
                "type" =>  E\Eagent_type::V_3
            ]);
        }
    }

    public function get_level_list($id ) {
        $sql = $this->gen_sql_new(
            "select  a1.id  agent_id, a1.nickname, a1.phone, a1.agent_student_status, a1.type as agent_type, a1.create_time, a1.id ,sum(a2.id>0 )  child_count "
            . " from %s a1"
            . " left join  %s a2 on a1.id=a2.parentid "
            ." where  a1.parentid=%u group  by a1.id  ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $id
        );
        return $this->main_get_list($sql);
    }

}
