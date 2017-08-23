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

    public function get_agent_info($page_info,$phone,$type,$start_time,$end_time,$p_phone)
    {
        $where_arr = [];
        $this->where_arr_add_str_field($where_arr,"a.phone",$phone);
        if($p_phone){
            $this->where_arr_add_str_field($where_arr,"aa.phone",$p_phone);
        }
        $this->where_arr_add_int_field($where_arr,"a.type",$type);
        $where_arr[] = sprintf("a.create_time > %d and a.create_time < %d", $start_time,$end_time);
        $sql=$this->gen_sql_new (" select a.*,"
                                 ."aa.nickname p_nickname,aa.phone p_phone,"
                                 ."aaa.nickname pp_nickname,aaa.phone pp_phone,"
                                 ."s.origin,"
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
        return $this->main_get_list_by_page( $sql,$page_info);
    }

    // public function get_agent_info_new($page_info,$type)
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
            . " left join %s s on s.phone = a.phone "
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

    public function get_p_pp_id_by_phone($phone){
        $where_arr = [
            ['a.phone = %s',$phone],
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
}
