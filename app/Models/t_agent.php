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

    public function get_agent_info($page_info, $order_by_str, $phone,$type,$start_time,$end_time,$p_phone, $test_lesson_flag, $agent_level,$order_flag,$l1_child_count )
    {
        $where_arr = [];
        if($p_phone){
            $this->where_arr_add_str_field($where_arr,"aa.phone",$p_phone);
        }else if ( $phone ) {
            $this->where_arr_add_str_field($where_arr,"a.phone",$phone);
        }else {
            $this->where_arr_add_int_or_idlist($where_arr,"a.type",$type);
            $this->where_arr_add_int_or_idlist($where_arr,"a.agent_level",$agent_level);
            $this->where_arr_add_int_or_idlist($where_arr,"a.l1_child_count",$l1_child_count);
            $this->where_arr_add_time_range($where_arr,"a.create_time",$start_time,$end_time);
            $this->where_arr_add_boolean_for_value($where_arr,"a.test_lessonid" ,$test_lesson_flag);
            $this->where_arr_add_boolean_for_value($where_arr,"ao.orderid" ,$order_flag,true );
        }

        $sql=$this->gen_sql_new (" select a.*,"
                                 ."aa.nickname p_nickname,aa.phone p_phone,"
                                 ."aaa.nickname pp_nickname,aaa.phone pp_phone,"
                                 ."s.origin,s.type student_stu_type,s.is_test_user,"
                                 ."l.lesson_start,l.lesson_user_online_status, "
                                 ."ao.p_level,ao.pp_level , ao.p_price,ao.pp_price,"
                                 ."o.price, "
                                 ."n.admin_revisiterid "
                                 ." from %s a "
                                 ." left join %s aa on aa.id = a.parentid"
                                 ." left join %s aaa on aaa.id = aa.parentid"
                                 ." left join %s s on s.userid = a.userid"
                                 ." left join %s n on n.userid = a.userid"
                                 ." left join %s l on l.lessonid = a.test_lessonid"
                                 ." left join %s ao on ao.aid = a.id "
                                 ." left join %s o on o.orderid = ao.orderid "
                                 ." where %s  $order_by_str "
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,self::DB_TABLE_NAME
                                 ,t_student_info::DB_TABLE_NAME
                                 ,t_seller_student_new::DB_TABLE_NAME
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

    public function get_agent_info_new($start_time=-1,$end_time=-1)
    {
        $where_arr = array();
        if($start_time && $end_time){
            $this->where_arr_add_time_range($where_arr,'a.create_time',$start_time,$end_time);
        }
        $this->where_arr_add_int_field($where_arr,"a.type",1);
        $sql=$this->gen_sql_new (" select a.*,"
                                 ." aa.nickname p_nickname,aa.phone p_phone,"
                                 ." aaa.nickname pp_nickname,aaa.phone pp_phone,"
                                 ." ao.orderid aoid,"
                                 ." o.price,"
                                 ." s.userid s_userid,s.origin,s.is_test_user, "
                                 ." n.admin_revisiterid,n.tmk_student_status,n.global_tq_called_flag,n.sys_invaild_flag,"
                                 ." t.seller_student_status,t.require_admin_type,"
                                 ." tr.accept_flag,"
                                 ." tea.nick as tea_nick,l.lesson_user_online_status, "
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
                                 // ." left join %s tss on tss.lessonid = tr.current_lessonid"
                                 ." left join %s l on l.lessonid = a.test_lessonid"
                                 ." left join %s tea on l.teacherid = tea.teacherid"
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
                                 // ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                 ,t_lesson_info::DB_TABLE_NAME
                                 ,t_teacher_info::DB_TABLE_NAME
                                 ,$where_arr
        );
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

    public function get_invite_money( $id, $test_lesson_succ_flag , $agent_status_money_open_flag  ) {
        $list=$this->get_invite_money_list($id, $test_lesson_succ_flag , $agent_status_money_open_flag );
        $money=0;
        foreach ($list as $item) {
            $money+=$item["agent_status_money"];
        }
        return $money;
    }


    public function get_l2_invite_money_list($id, $test_lesson_succ_flag , $agent_status_money_open_flag ){

        $yxyx_check_time=strtotime( \App\Helper\Config::get_config("yxyx_new_start_time"));
        $where_arr=[
            "a.type in (1,3)",
            ["a.pp_agent_status_money_open_flag=%s", $agent_status_money_open_flag,-1],
            "a.create_time> $yxyx_check_time",
            "a.pp_agent_status_money >0",

        ];
        if ( $test_lesson_succ_flag ==1 ) {
            $where_arr[] ="a.pp_agent_status_money=2500 ";
        }else if ( $test_lesson_succ_flag ==0 ) {
            $where_arr[] ="a.pp_agent_status_money<2500 ";
        }


        $sql=$this->gen_sql_new (
            "select  a.create_time, a.id, a.nickname,a.phone,  a.agent_status, a.pp_agent_status_money as agent_status_money , a.pp_agent_status_money_open_flag as agent_status_money_open_flag  "
            ." from %s a_p "
            ." join %s a on a.parentid = a_p.id "
            ." where a_p.parentid=%u and %s "
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$id,$where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_invite_money_list($id, $test_lesson_succ_flag , $agent_status_money_open_flag ){

        $yxyx_check_time=strtotime( \App\Helper\Config::get_config("yxyx_new_start_time"));
        $where_arr=[
            "a.type in (1,3)",
            ["agent_status_money_open_flag=%s", $agent_status_money_open_flag,-1],
            "create_time> $yxyx_check_time",

        ];
        if ( $test_lesson_succ_flag ==1 ) {
            $where_arr[] ="agent_status_money=5000 ";
        }else if ( $test_lesson_succ_flag ==0 ) {
            $where_arr[] ="agent_status_money<5000 ";
        }


        $sql=$this->gen_sql_new (
            "select  a.create_time, a.id, a.nickname,a.phone,  agent_status, agent_status_money, agent_status_money_open_flag "
            ." from %s a "
            ." where a.parentid=%u and %s "
            ,self::DB_TABLE_NAME
            ,$id,$where_arr
        );

        return $this->main_get_list($sql);
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

    public function add_agent_bind_new($phone,$headimgurl,$nickname,$wx_openid,$userid,$agent_level){
        $ret = $this->row_insert([
            "parentid"    => 0,
            "phone"       => $phone,
            "wx_openid"   => $wx_openid,
            "userid"      => $userid,
            "headimgurl"  => $headimgurl,
            "nickname"    => $nickname,
            "agent_level" => $agent_level,
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

    public function get_p_pp_by_id($id){
        $where_arr = [
            ['a.id = %u ',$id,-1],
        ];
        $sql = $this->gen_sql_new(
            " select a.phone,a.wx_openid,aa.wx_openid p_wx_openid,aaa.wx_openid pp_wx_openid"
            ." from %s a "
            ." left join %s aa on aa.id = a.parentid "
            ." left join %s aaa on aaa.id = aa.parentid "
            ." where %s limit 1 "
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
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
        $sql=$this->gen_sql_new("select id,type  from %s where userid=%u ",
                                self::DB_TABLE_NAME, $userid );
        return $this->main_get_value($sql);

    }

    public function get_id_by_phone($phone_str){
        $where_arr = [
            'a.phone in ('.$phone_str.')',
        ];
        $sql= $this->gen_sql_new(
            "select a.id,a.phone,a.type,a.wx_openid,a.agent_level,"
            ."aa.wx_openid pp_wx_openid,aa.agent_level pp_agent_level "
            . " from %s a "
            . " left join %s aa on aa.id = a.parentid "
            . " where %s ",
            self::DB_TABLE_NAME,
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
            " select a.id,a.userid,a.phone,"
            ."a1.id pid,a1.phone p_phone,a1.wx_openid p_wx_openid,"
            ."a2.id ppid,a2.phone pp_phone,a2.wx_openid pp_wx_openid".
            " from %s a ".
            " left join %s a1 on a1.id=a.parentid".
            " left join %s a2 on a2.id=a1.parentid".
            " where %s limit 1",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_p_pp_row_by_userid($userid){
        $where_arr = [
            ['a.userid = "%u"',$userid,-1],
        ];
        $sql = $this->gen_sql_new(
            " select a.id,a.userid,a.phone,a.wx_openid,"
            ."a1.id pid,a1.phone p_phone,a1.wx_openid p_wx_openid,"
            ."a2.id ppid,a2.phone pp_phone,a2.wx_openid pp_wx_openid".
            " from %s a ".
            " left join %s a1 on a1.id=a.parentid".
            " left join %s a2 on a2.id=a1.parentid".
            " where %s limit 1",
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
            . " a1.agent_status_money   p_agent_status_money ,"
            . " a1.agent_status_money_open_flag   p_agent_status_money_open_flag ,"

            . " a2.pp_agent_status_money   pp_agent_status_money ,"
            . " a2.pp_agent_status_money_open_flag   pp_agent_status_money_open_flag ,"




            . " ao1.p_level o_p_agent_level, ao1.p_price o_p_price,  ao1.p_open_price o_p_open_price,  o1.price o_p_from_price, o1.pay_time o_p_from_pay_time,  o1.orderid  o_p_from_orderid, "
            . " ao.pp_level o_agent_level , ao.pp_price o_price ,  ao.pp_open_price o_open_price ,  o.price o_from_price , o.pay_time o_from_pay_time  ,  o.orderid  o_from_orderid "

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
            $p_agent_status_money_open_flag=$item["p_agent_status_money_open_flag"];
            $p_agent_status_money=$item["p_agent_status_money"];
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


            E\Eboolean::set_item_value_color_str($item,"p_agent_status_money_open_flag" );
            E\Eboolean::set_item_value_color_str($item,"pp_agent_status_money_open_flag" );
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
                "p1_p_open_price"              => $p1["o_p_open_price"]/100,
                "p1_agent_status_money"              => $p1["p_agent_status_money"]/100,
                "p1_agent_status_money_open_flag_str" => $p1["p_agent_status_money_open_flag_str"],
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
                    "p2_p_open_price"              => $p2["o_open_price"]/100,

                    "p2_agent_status_money"              => $p2["pp_agent_status_money"]/100,
                    "p2_agent_status_money_open_flag_str" => $p2["pp_agent_status_money_open_flag_str"],
                ] ;
            }
        }
        return $ret_list;
    }
    public function reset_user_info_test_lesson($id,$userid, $is_test_user, $check_time) {
        //重置试听信息
        $lessonid = 0;
        $lesson_info=null;
        if($userid && $is_test_user == 0 ) {
            $lessonid=0;
            $lesson_info= $this->task->t_lesson_info_b2->get_succ_test_lesson($userid,$check_time);
            if ($lesson_info ) {
                $lessonid = $lesson_info['lessonid'];
            }
        }
        return array($lessonid, $lesson_info)  ;
    }

    public function get_agent_level_by_check_time($id,$agent_info,$check_time ){
        $star_count=0;
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
            $star_count=2;

        }elseif($wx_openid){//有wx绑定
            $son_test_lesson = $this->get_son_test_lesson_count_by_id($id,$check_time);
            $count           = count($son_test_lesson);
            if($count>=2){ //>=两次试听
                $level     =  E\Eagent_level::V_2 ;
                $star_count=2;
            }else{
                $level     =  E\Eagent_level::V_1 ;
                $star_count=$count;
            }
        }else{//非绑定
            $level =  E\Eagent_level::V_0;
        }
        return  array( $level, $star_count );
    }


    public function reset_user_info_order_info($id,$userid ,$is_test_user,$create_time) {
        //重置订单信息
        $p_pp_info = $this->task->t_agent->get_p_pp_by_id($id);
        $phone = $p_pp_info['phone'];
        $p_wx_openid = $p_pp_info['p_wx_openid'];
        $pp_wx_openid = $p_pp_info['pp_wx_openid'];
        $order_info_old = $this->task->t_agent_order->get_row_by_aid($id);
        $this->task->t_agent_order->row_delete_by_aid($id);
        $orderid=0;
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
                    list($p_level,$p_star_count )=$this->get_agent_level_by_check_time($pid, $p_agent_info, $check_time );
                }

                if ($ppid) {
                    \App\Helper\Utils::logger("check  pp_level ppid=$ppid");
                    $pp_agent_info= $this->field_get_list($ppid,"*");
                    list($pp_level , $pp_star_count) =$this->get_agent_level_by_check_time($ppid, $pp_agent_info, $check_time );
                }

                $order_price= $order_info["price"];
                $price           = $order_price/100;
                $level1_price    = $price/20>500?500:$price/20;
                $level2_p_price  = $price/10>1000?1000:$price/10;
                $level2_pp_price = $price/20>500?500:$price/20;
                $p_price = 0;
                $pp_price = 0;

                $p_open_price = 0;
                $pp_open_price = 0;

                if($p_level== 1){//黄金
                    $p_price = $level1_price*100;
                }elseif($p_level == 2){//水晶
                    $p_price = $level2_p_price*100;
                }

                if($pp_level== 2){//水晶
                    $pp_price = $level2_pp_price*100;
                }

                $lesson_info= $this->task->t_lesson_info_b2->get_lesson_count_by_userid($userid, $check_time );
                $lesson_count=$lesson_info["count"];
                if ($lesson_count >=8 ) {
                    $p_open_price= $p_price;
                    $pp_open_price= $pp_price;
                }else if ( $lesson_count >=2) {
                    $p_open_price= $p_price*0.2;
                    $pp_open_price= $pp_price*0.2;
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

                    'p_open_price'     =>$p_open_price,
                    'pp_open_price'     =>$pp_open_price,
                    'create_time' =>  $check_time,
                ]);

                if(!$order_info_old && $p_wx_openid && $p_price){
                    $p_price_new = $p_price/100;
                    $template_id = 'zZ6yq8hp2U5wnLaRacon9EHc26N96swIY_9CM8oqSa4';
                    $data = [
                        'first'    => '恭喜您获得邀请奖金',
                        'keyword1' => $p_price_new.'元',
                        'keyword2' => $phone,
                        'remark'   => '恭喜您邀请的学员'.$phone.'购课成功，课程金额'.$price.'元，您获得'.$p_price_new.'元。',
                    ];
                    $url = '';
                    \App\Helper\Utils::send_agent_msg_for_wx($p_wx_openid,$template_id,$data,$url);
                }

                if(!$order_info_old && $pp_wx_openid && $pp_price){
                    $pp_price_new = $pp_price/100;
                    $template_id = 'zZ6yq8hp2U5wnLaRacon9EHc26N96swIY_9CM8oqSa4';
                    $data = [
                        'first'    => '恭喜您获得邀请奖金',
                        'keyword1' => $pp_price_new.'元',
                        'keyword2' => $phone,
                        'remark'   => '恭喜您邀请的学员'.$phone.'购课成功，课程金额'.$price.'元，您获得'.$pp_price_new.'元。',
                    ];
                    $url = '';
                    \App\Helper\Utils::send_agent_msg_for_wx($pp_wx_openid,$template_id,$data,$url);
                }
            }

        }
        return $orderid;
    }
    public function eval_agent_student_status(  $stu_info, $lesson_info ){
        $agent_student_status=0;
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
                }else if ( $global_seller_student_status<=220) {
                    $agent_student_status=E\Eagent_student_status::V_30;
                }else{
                    if ( $lesson_info) {
                        if ( $lesson_info["lesson_end"]  < time(NULL)) {
                            $agent_student_status=E\Eagent_student_status::V_40;
                        }else{
                            $agent_student_status=E\Eagent_student_status::V_30;
                        }
                    }else{
                        $agent_student_status=E\Eagent_student_status::V_30;
                    }
                }
            }else{
                $agent_student_status=E\Eagent_student_status::V_100;
            }

        }
        return $agent_student_status;
    }

    public function eval_agent_status(  $stu_info, $lesson_info ){
        if ($stu_info) {
            $global_tq_called_flag        = $stu_info["global_tq_called_flag"];

            if ($lesson_info ) {
                if ($lesson_info["lesson_user_online_status"]==1) {
                    return  E\Eagent_status::V_30;
                }else{
                    return  E\Eagent_status::V_20;
                }
            }else{
                if ($global_tq_called_flag==1 ) {
                    return  E\Eagent_status::V_2;
                } else if ($global_tq_called_flag==2) {
                    return  E\Eagent_status::V_10;
                }
            }
        }
        return  E\Eagent_status::V_1;
    }

    public function eval_agent_status_money($agent_status ){
        switch ( $agent_status ) {
        case E\Eagent_status::V_1 :
            return 500;
        case E\Eagent_status::V_2 :
            return 500;
        case E\Eagent_status::V_10 :
            return 1000;
        case E\Eagent_status::V_20 :
            return 2000;
        case E\Eagent_status::V_30 :
        case E\Eagent_status::V_40 :
            return 5000;
        default:
            return 0;
        }
    }

    public function reset_user_info_l2_money_open_flag($id ){
        $l2_child_list=$this-> get_l2_test_lesson_order_list($id);
        $set_open_list=[];
        $order_count=0;
        $l2_agent_status_all_money=0;
        $need_set_open_list_count=0;
        foreach( $l2_child_list as $item ) {
            $child_id=$item["id"];
            $pp_agent_status_money_open_flag = $item["pp_agent_status_money_open_flag"];
            $l2_agent_status_all_money +=$item["pp_agent_status_money"];
            $orderid=$item["orderid"];
            if ($orderid) { //有订单
                if ($pp_agent_status_money_open_flag !=1 ) {
                    $this->field_update_list($child_id,[
                        "pp_agent_status_money_open_flag" => 1
                    ]);
                }
                $order_count+=1;
            }else {
                if ($item["lesson_user_online_status"] ==1 ) {
                    $set_open_list[]=[
                        "id" => $child_id,
                        "pp_agent_status_money_open_flag" =>$pp_agent_status_money_open_flag,
                    ];
                }else{
                    if ($pp_agent_status_money_open_flag !=0 ) { //没有开放
                        $this->field_update_list($child_id,[
                            "pp_agent_status_money_open_flag" => 0
                        ]);
                    }
                }
            }



        }

        $succ_lesson_cont=count($set_open_list );
        //8倍提现
        //$need_set_open_list_count= $succ_lesson_cont - $succ_lesson_cont %8 ;
        $need_set_open_list_count= $succ_lesson_cont;

        foreach ( $set_open_list as  $index => $item  ) {
            $child_id=$item["id"];
            $pp_agent_status_money_open_flag = $item["pp_agent_status_money_open_flag"];
            if ($index < $need_set_open_list_count) { //可提现范围
                if ($pp_agent_status_money_open_flag !=1 ) {
                    $this->field_update_list($child_id,[
                        "pp_agent_status_money_open_flag" => 1
                    ]);
                }
            }else{
                if ($pp_agent_status_money_open_flag !=0 ) { //没有开放
                    $this->field_update_list($child_id,[
                        "pp_agent_status_money_open_flag" => 0
                    ]);
                }
            }
        }
        \App\Helper\Utils::logger(" XXXX  $order_count $need_set_open_list_count  ");


        return  array(($order_count+$need_set_open_list_count)*25*100, $order_count+$succ_lesson_cont ,$l2_agent_status_all_money  );

    }
    //return 可提现金额
    public function reset_user_info_l1_money_open_flag($id ){
        $l1_child_list=$this-> get_l1_test_lesson_order_list($id);
        $set_open_list=[];
        $order_count=0;
        $need_set_open_list_count=0;
        $l1_agent_status_all_money=0;
        foreach( $l1_child_list as $item ) {
            $child_id=$item["id"];
            $agent_status_money_open_flag = $item["agent_status_money_open_flag"];
            $l1_agent_status_all_money +=$item["agent_status_money"];
            $orderid=$item["orderid"];
            if ($orderid) { //有订单
                if ($agent_status_money_open_flag !=1 ) {
                    $this->field_update_list($child_id,[
                        "agent_status_money_open_flag" => 1
                    ]);
                }
                $order_count+=1;
            }else {
                if ($item["lesson_user_online_status"] ==1 ) {
                    $set_open_list[]=[
                        "id" => $child_id,
                        "agent_status_money_open_flag" =>$agent_status_money_open_flag,
                    ];
                }else{
                    if ($agent_status_money_open_flag !=0 ) { //没有开放
                        $this->field_update_list($child_id,[
                            "agent_status_money_open_flag" => 0
                        ]);
                    }
                }
            }



            //agent_status_money_open_flag
            //a.id, lesson_user_online_status ,
        }

        $succ_lesson_cont=count($set_open_list );
        //4倍提现
        //$need_set_open_list_count= $succ_lesson_cont - $succ_lesson_cont %4 ;
        $need_set_open_list_count= $succ_lesson_cont;

        foreach ( $set_open_list as  $index => $item  ) {
            $child_id=$item["id"];
            $agent_status_money_open_flag = $item["agent_status_money_open_flag"];
            if ($index < $need_set_open_list_count) { //可提现范围
                if ($agent_status_money_open_flag !=1 ) {
                    $this->field_update_list($child_id,[
                        "agent_status_money_open_flag" => 1
                    ]);
                }
            }else{
                if ($agent_status_money_open_flag !=0 ) { //没有开放
                    $this->field_update_list($child_id,[
                        "agent_status_money_open_flag" => 0
                    ]);
                }
            }
        }
        \App\Helper\Utils::logger(" XXXX  $order_count $need_set_open_list_count  ");


        return  array(($order_count+$need_set_open_list_count)*50*100, $order_count+$succ_lesson_cont ,$l1_agent_status_all_money  );

    }
    public function wx_noti_agent_status( $id, $create_time,$parentid, $agent_level , $test_lessonid, $phone, $old_agent_status, $agent_status) {
        //$old_agent_status
        // $parentid=1850;

        if ($parentid && $old_agent_status < $agent_status ) { //状态升级
            $p_item = $this->field_get_list($parentid,"wx_openid,agent_level");
            if ($p_item) {
                $wx_config =\App\Helper\Config::get_config("yxyx_wx") ;
                $base_url= $wx_config["url"] ;
                $url="$base_url/wx_yxyx_web/index";

                $wx_openid   = $p_item["wx_openid"];
                $agent_level = $p_item["agent_level"];
                //\App\Helper\Utils::send_agent_msg_for_wx($wx_openid,$template_id,$data,$url);
                if ($agent_status == E\Eagent_status::V_1) { //报名推送

                    $template_id = 'WEg0PqvnN23HboTngezq0Ut8cPLf-g_0Tgmv4zhj4Eo';
                    $data = [
                        'first'    => "恭喜您成功邀请学员{$phone}参加测评课，您获得5元奖励",
                        'keyword1' =>"测评课" ,
                        'keyword2' => date('Y-m-d H:i:s',time()),
                        'keyword3' => $phone ,
                        'remark'   => "如课程老师成功联系学员，将再获得5元奖励。"
                    ];
                    \App\Helper\Utils::send_agent_msg_for_wx($wx_openid,$template_id,$data,$url);

                }else if ($agent_status == E\Eagent_status::V_10) {//拨通推送
                    $template_id = 'eP6Guhb_w4s7NxnuF1yf2fz_cRF1wLqguFrQLtOKYlc';
                    $data = [
                        'first'    => "课程老师已成功联系学员{$phone}，您获得5元奖励。",
                        'keyword1' => $phone,
                        'keyword2' => "预约测评课" ,
                        'keyword3' => date('Y-m-d H:i:s',time()),
                        'keyword4' => "已接通" ,
                        'remark'   => "如学员成功预约测评课，将再获得10元奖励",
                    ];
                    \App\Helper\Utils::send_agent_msg_for_wx($wx_openid,$template_id,$data,$url);
                }else if ($agent_status == E\Eagent_status::V_20) { //排课

                    $template_id = '5gRCvXir0giV6kQcgTMki0TUWfQuKD1Vigg7zanvsD8';
                    $lesson_start=$this->task->t_lesson_info->get_lesson_start($test_lessonid);
                    $data = [
                        'first'    => "您邀请的学员{$phone}成功预约测评课，您获得10元奖励。 ",
                        'keyword1' => $phone,
                        'keyword2' =>  $phone ,
                        'keyword3' => "测评课",
                        'keyword4' => \App\Helper\Utils::unixtime2date($lesson_start,"Y-m-d H:i") ,
                        'keyword5' => "理优1对1",
                        'remark'   => "如学员成功上完测评课，将再获得30元奖励。",
                    ];
                    \App\Helper\Utils::send_agent_msg_for_wx($wx_openid,$template_id,$data,$url);


                }else if ($agent_status == E\Eagent_status::V_30) { //试听成功

                    $template_id = 'ahct5cHBDNVvA3rAYwMuaZ7VZlgx10xRfZ7ssh24hPQ';
                    $lesson_start=$this->task->t_lesson_info->get_lesson_start($test_lessonid);
                    $remark="";
                    if ($agent_level==E\Eagent_level::V_1 ) {
                    }else if ($agent_level==E\Eagent_level::V_2 ) {
                        $remark="如学员购买课程，将再获得该学员学费的5%（最高500元）奖励。";
                    }
                    $data = [
                        'first'    => "您邀请的学员{$phone}成功上完测评课，您获得30元奖励。",
                        'keyword1' => "理优1对1",
                        'keyword2' => "测评课" ,
                        'keyword3' =>  \App\Helper\Utils::unixtime2date($lesson_start,"Y-m-d H:i") ,
                        'keyword4' =>  \App\Helper\Utils::unixtime2date($lesson_start+50*60,"Y-m-d H:i") ,
                        'remark'   => "$remark",
                    ];
                    \App\Helper\Utils::send_agent_msg_for_wx($wx_openid,$template_id,$data,$url);


                }else if ($agent_status == E\Eagent_status::V_40) { //签单

                }
            }
        }
        /*
          1.成功邀请学员提醒
          恭喜您成功邀请学员XXX参加测评课，您获得5元奖励。
          如课程老师成功联系学员，将再获得5元奖励。


          2.成功联系学员提醒
          课程老师已成功联系学员XXX，您获得5元奖励。
          如学员成功预约测评课，将再获得10元奖励。


          3.学员成功预约测评课提醒
          您邀请的学员XXX成功预约测评课，您获得10元奖励。
          如学员成功上完测评课，将再获得30元奖励。

          4.学员XXX测评课成功提醒
          您邀请的学员XXX成功上完测评课，您获得30元奖励。
          如学员购买课程，将再获得该学员学费的5%（最高500元）奖励。

        */



    }

    public function reset_user_info($id ) {
        $agent_info = $this->field_get_list($id,"*");
        $userid  = $agent_info["userid"];
        $agent_type= $agent_info["type"];
        $agent_level_old = $agent_info["agent_level"];
        $wx_openid_old  = $agent_info["wx_openid"];
        $old_agent_status = $agent_info["agent_status"];
        $agent_student_status=0;
        $agent_status=0;
        $test_lessonid=0;
        if ($userid) {
            $student_info = $this->task->t_student_info->field_get_list($userid,"is_test_user");
            $is_test_user = $student_info['is_test_user'];
            $create_time = $agent_info['create_time'];
            $now=time(NULL);
            list($test_lessonid ,$lesson_info)=$this->reset_user_info_test_lesson($id,$userid,$is_test_user, $create_time );
            $orderid=$this->reset_user_info_order_info($id,$userid,$is_test_user,$create_time);

            //是学员
            if (in_array( $agent_type, [E\Eagent_type::V_1 , E\Eagent_type::V_3]) ) {
                //检查合同
                if ($orderid ) {
                    $agent_student_status=E\Eagent_student_status::V_50;
                    $agent_status=E\Eagent_status::V_40;
                    if ( $orderid &&  $lesson_info && $lesson_info["lesson_user_online_status"]!=1  ) {
                        //下单,试听一定成功
                        $this->task->t_lesson_info->field_update_list($lesson_info["lessonid"],[
                            "lesson_user_online_status" => 1
                        ]);

                    }
                }else{
                    $stu_info=$this->task->t_seller_student_new->field_get_list($userid,"global_tq_called_flag, global_seller_student_status,seller_resource_type,test_lesson_count") ;

                    $agent_student_status= $this->eval_agent_student_status(  $stu_info, $lesson_info );
                    $agent_status= $this->eval_agent_status( $stu_info, $lesson_info );

                }
            }
        }

        $level_count_info= $this-> get_level_count_info($id);
        $agent_status_money =0;
        $l1_agent_status_test_lesson_succ_count=0;
        $l1_agent_status_all_money =0;
        $l1_agent_status_all_open_money=0;

        $l2_agent_status_all_open_money=0;
        $l2_agent_status_test_lesson_succ_count=0;
        $l2_agent_status_all_money=0;

        $yxyx_check_time=strtotime( \App\Helper\Config::get_config("yxyx_new_start_time"));

        if ($agent_type == E\Eagent_type::V_2  || $agent_type == E\Eagent_type::V_3 ) {
            list(  $l1_agent_status_all_open_money,
                   $l1_agent_status_test_lesson_succ_count,
                   $l1_agent_status_all_money )
                =$this->reset_user_info_l1_money_open_flag($id);

            list(  $l2_agent_status_all_open_money,
                   $l2_agent_status_test_lesson_succ_count,
                   $l2_agent_status_all_money )
                =$this->reset_user_info_l2_money_open_flag($id);
        }

        //不能降级
        if ($old_agent_status > $agent_status ) {
            $agent_status= $old_agent_status;
        }

        $pp_agent_status_money=0;
        if ($agent_info["create_time"] > $yxyx_check_time)  {
            $agent_status_money= $this->eval_agent_status_money($agent_status);
            if ($agent_status_money==5000) {
                $pp_agent_status_money= 2500;
            }
        }



        //重置当前等级
        list($agent_level, $star_count)=$this->get_agent_level_by_check_time($id,$agent_info,time(NULL));
        if ($agent_level < $agent_level_old ) {
            $agent_level= $agent_level_old;
        }

        //佣金提成信息
        $order_open_all_money= $level_count_info["l1_child_open_price"] +$level_count_info["l2_child_open_price"];
        $order_all_money= $level_count_info["l1_child_price"] +$level_count_info["l2_child_price"];
        $child_order_count= $level_count_info["l1_order_count"] +$level_count_info["l2_order_count"];

        //活动奖励

        //总提成信息
        $all_yxyx_money      = $order_all_money +  $l1_agent_status_all_money+ $l2_agent_status_all_money;
        $all_open_cush_money = $order_open_all_money +  $l1_agent_status_all_open_money+ $l2_agent_status_all_open_money;
        $all_have_cush_money = $this->task->t_agent_cash->get_have_cash($id,1);



        $this->field_update_list($id,[
            "agent_level" => $agent_level,
            "star_count" => $star_count,
            "agent_student_status" => $agent_student_status,
            "l1_child_count" => $level_count_info["l1_child_count"],
            "l2_child_count" => $level_count_info["l2_child_count"],
            "child_order_count" => $child_order_count ,

            "all_money" => $order_all_money ,
            "order_open_all_money" => $order_open_all_money,

            "agent_status" => $agent_status,
            "agent_status_money" => $agent_status_money,
            "pp_agent_status_money" => $pp_agent_status_money,

            "l1_agent_status_all_money" =>  $l1_agent_status_all_money,
            "l1_agent_status_all_open_money" =>  $l1_agent_status_all_open_money,
            "l1_agent_status_test_lesson_succ_count" =>  $l1_agent_status_test_lesson_succ_count,

            "l2_agent_status_all_money" =>  $l2_agent_status_all_money,
            "l2_agent_status_all_open_money" =>  $l2_agent_status_all_open_money,
            "l2_agent_status_test_lesson_succ_count" =>  $l2_agent_status_test_lesson_succ_count,

            "all_yxyx_money" => $all_yxyx_money,
            "all_open_cush_money" => $all_open_cush_money,
            "all_have_cush_money" => $all_have_cush_money,
            "test_lessonid" => $test_lessonid,

        ]);

        if (  $agent_type==E\Eagent_type::V_2  &&  $userid ) {//是会员, 学员,
            $this->field_update_list($id,[
                "type" =>  E\Eagent_type::V_3
            ]);
        }

        if (  $agent_type==E\Eagent_type::V_3  &&  !$userid ) {//是会员, 学员,
            $this->field_update_list($id,[
                "type" =>  E\Eagent_type::V_2
            ]);
        }

        $check_time=strtotime( \App\Helper\Config::get_config("yxyx_new_start_time"));
        if ($agent_info["create_time"] < $check_time) {
            if ( $agent_info["agent_status_money_open_flag"] !=0 ) {
                $this->field_update_list($id,[
                    "agent_status_money_open_flag" => 0,
                    "agent_status_money" => 0,
                ]);
            }
        }else {
            $this->wx_noti_agent_status($id , $agent_info["create_time"], $agent_info["parentid"],$agent_level,$test_lessonid ,$agent_info["phone"],$old_agent_status,$agent_status);
        }

        //$agent_status_money_open_flag=0;

        if ( $level_count_info["l1_child_count"]) {
            if ($agent_type ==1 ) {
                $this->field_update_list($id,[
                    "type" =>  E\Eagent_type::V_3
                ]);
            }else if( $agent_type ==0 ){
                $this->field_update_list($id,[
                    "type" =>  E\Eagent_type::V_2
                ]);
            }
        }

        if(($agent_level_old == E\Eagent_level::V_1) && ($agent_level == E\Eagent_level::V_2)){
            $template_id = 'ZPrDo_e3DHuyajnlbOnys7odLZG6ZeqImV3IgOxmu3o';
            $data = [
                'first'    => '等级升级提醒',
                'keyword1' => '水晶会员',
                'keyword2' => "永久" ,
                'remark'   => '恭喜您升级成为水晶会员,如果您邀请的学员成功购课则可获得最高1000元的奖励哦。',
            ];
            $url = '';
            \App\Helper\Utils::send_agent_msg_for_wx($wx_openid_old,$template_id,$data,$url);
        }
    }



    public function get_level_count_info($id ) {
        $sql = $this->gen_sql_new(
            "select  sum(orderid>0) l1_order_count, sum(pp_order_count) l2_order_count, count(*) as l1_child_count , sum(child_count) l2_child_count, sum(p_price) l1_child_price, sum(p_open_price) l1_child_open_price, sum( pp_price ) l2_child_price , sum(pp_open_price) l2_child_open_price "
            . " from (select  a1.id  agent_id, ao1.orderid,  ao1.p_price  , ao1.p_open_price  , sum(a2.id>0 )  child_count, sum(ao2.pp_price) as  pp_price , sum(ao2.pp_open_price) as  pp_open_price, sum(ao2.orderid>0)  pp_order_count "
            . " from %s a1"
            . " left join  %s a2 on( a1.id=a2.parentid and a2.type in (1,3)  )  "
            . " left join  %s ao1 on( a1.id=ao1.aid   )  "
            . " left join  %s ao2 on( a2.id=ao2.aid   )  "
            ." where  a1.parentid=%u  group  by a1.id ) t ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_agent_order::DB_TABLE_NAME,
            t_agent_order::DB_TABLE_NAME,
            $id
        );
        return $this->main_get_row($sql);
    }


    public function get_l1_test_lesson_order_list($id) {
        $check_time=strtotime( \App\Helper\Config::get_config("yxyx_new_start_time"));

        $sql = $this->gen_sql_new(
            "select a.id, l.lesson_user_online_status , a.agent_status_money_open_flag , "
            . " a.agent_status_money, ao.orderid  "
            . " from %s a "
            . " left join  %s l on a.test_lessonid =l.lessonid  "
            . " left join  %s ao on a.id =ao.aid "
            ." where  a.parentid=%u  and a.create_time > %u  order by l.lesson_start asc ",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_agent_order::DB_TABLE_NAME,
            $id,$check_time
        );
        return $this->main_get_list($sql);
    }

    public function get_l2_test_lesson_order_list($id) {
        $check_time=strtotime( \App\Helper\Config::get_config("yxyx_new_start_time"));

        $sql = $this->gen_sql_new(
            "select a.id, l.lesson_user_online_status , a.pp_agent_status_money_open_flag , "
            . " a.pp_agent_status_money, ao.orderid  "
            . " from %s a_p "
            . " join %s a on a_p.id=a.parentid "
            . " left join  %s l on a.test_lessonid =l.lessonid  "
            . " left join  %s ao on a.id =ao.aid "
            ." where  a_p.parentid=%u  and a.create_time > %u  order by l.lesson_start asc ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_agent_order::DB_TABLE_NAME,
            $id,$check_time
        );
        return $this->main_get_list($sql);
    }




    public function get_level_list($id ) {
        $sql = $this->gen_sql_new(
            "select  a1.id  agent_id, a1.nickname, a1.phone, a1.agent_status, a1.agent_student_status, a1.type as agent_type, a1.create_time, a1.id ,sum(a2.id>0 )  child_count "
            . " from %s a1"
            . " left join  %s a2 on ( a1.id=a2.parentid and a2.type in (1,3)  )  "
            ." where  a1.parentid=%u group  by a1.id  ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $id
        );
        return $this->main_get_list($sql);
    }

    public function get_p_pp_wx_openid_by_phone($phone){
        $where_arr = [
            ['a.phone = %d',$phone,-1]
        ];
        $sql = $this->gen_sql_new(
            " select a.id,a.wx_openid,a.agent_level,"
            ." aa.id pid,aa.wx_openid p_wx_openid,aa.agent_level p_agent_level "
            ." from %s a "
            ." left join %s aa on aa.id = a.parentid "
            ." where %s limit 1 "
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_son_userid_by_phone($phone){
        $where_arr = [
            ['aa.phone = %d',$phone,-1]
        ];
        $sql = $this->gen_sql_new(
            " select a.userid "
            ." from %s a "
            ." left join %s aa on aa.id = a.parentid "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }
}
