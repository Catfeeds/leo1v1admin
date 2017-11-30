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

    //@desn:获取优学优享会员列表
    public function get_agent_info($page_info, $order_by_str, $phone,$type,$start_time,$end_time,$p_phone, $test_lesson_flag, $agent_level,$order_flag,$l1_child_count )
    {
        $where_arr = [
            'a.type in (2,3)'
        ];
        if($p_phone){
            $this->where_arr_add_str_field($where_arr,"aa.phone",$p_phone);
        }else if ( $phone ) {
            if(is_numeric($phone) && strlen($phone))
                $this->where_arr_add_str_field($where_arr,"a.phone",$phone);
            else
                $where_arr[] = ["a.nickname like '%s%%'", $phone, ""];
        }else {
            if($type)
                $this->where_arr_add_int_or_idlist($where_arr,"a.type",$type);
            $this->where_arr_add_int_or_idlist($where_arr,"a.agent_level",$agent_level);
            $this->where_arr_add_int_or_idlist($where_arr,"a.l1_child_count",$l1_child_count);
            $this->where_arr_add_time_range($where_arr,"a.create_time",$start_time,$end_time);
            $this->where_arr_add_boolean_for_value($where_arr,"a.test_lessonid" ,$test_lesson_flag);
            $this->where_arr_add_boolean_for_value($where_arr,"ao.orderid" ,$order_flag,true );
        }

        $sql=$this->gen_sql_new (
            " select a.id,a.phone,a.nickname,a.agent_level,a.all_yxyx_money,"
            ."a.all_open_cush_money,a.all_have_cush_money,a.create_time,a.test_lessonid,"
            ."aa.nickname p_nickname,aa.phone p_phone,"
            ."aaa.nickname pp_nickname,aaa.phone pp_phone,"
            ."l.lesson_start,l.lesson_user_online_status, "
            ."a.userid,a.parentid,"
            ."o.sys_operator,mi.account,mi.name,mi.account_role"
            ." from %s a "
            ." left join %s aa on aa.id = a.parentid"
            ." left join %s aaa on aaa.id = aa.parentid"
            ." left join %s s on s.userid = a.userid"
            ." left join %s l on l.lessonid = a.test_lessonid"
            ." left join %s ao on ao.aid = a.id "
            ." left join %s o on o.orderid = ao.orderid "
            ." left join %s mi on s.assistantid = mi.uid "
            ." where %s $order_by_str "
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,t_agent_order::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page( $sql,$page_info);
    }
    //@desn:获取全部会员
    public function get_all_agent_member_count(){
        $where_arr = [
            'type in (2,3)'
        ];
        $sql = $this->gen_sql_new(
            "selct count(1) from %s where %s",self::DB_TABLE_NAME,$where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取推荐人详情
    //@param type 1:学员 2：会员 3：学员+会员
    //@param parentid 推荐人id
    public function get_child_info($parentid,$type,$page_info){
        $where_arr = [
            ['a.parentid = %u',$parentid,'0'],
            ['a.type = %u ',$type,'-1']
        ];
        $sql = $this->gen_sql_new(
            " select a.id,a.phone,a.nickname,a.test_lessonid,o.sys_operator,mi.account,mi.name,mi.account_role "
            .",a.userid"
            ." from %s a "
            ." left join %s s on s.userid = a.userid"
            ." left join %s ao on ao.aid = a.id "
            ." left join %s o on o.orderid = ao.orderid "
            ." left join %s mi on s.assistantid = mi.uid "
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_agent_order::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page( $sql,$page_info);
    }

    //@desn:获取优学优享学员列表
    public function get_student_info($page_info,$phone,$type,$start_time,$end_time,$p_phone, $test_lesson_flag, $agent_level,$order_flag,$l1_child_count )
    {
        $where_arr = [
            'a.type in (1,3)'
        ];
        if($p_phone){
            $this->where_arr_add_str_field($where_arr,"aa.phone",$p_phone);
        }else if ( $phone ) {
            if(is_numeric($phone) && strlen($phone))
                $this->where_arr_add_str_field($where_arr,"a.phone",$phone);
            else
                $where_arr[] = ["a.nickname like '%s%%'", $phone, ""];
        }else {
            if($type)
                $this->where_arr_add_int_or_idlist($where_arr,"a.type",$type);
            $this->where_arr_add_int_or_idlist($where_arr,"a.agent_level",$agent_level);
            $this->where_arr_add_int_or_idlist($where_arr,"a.l1_child_count",$l1_child_count);
            $this->where_arr_add_time_range($where_arr,"a.create_time",$start_time,$end_time);
            $this->where_arr_add_boolean_for_value($where_arr,"a.test_lessonid" ,$test_lesson_flag);
            $this->where_arr_add_boolean_for_value($where_arr,"ao.orderid" ,$order_flag,true );
        }

        $sql=$this->gen_sql_new (
            "select a.id,a.phone,a.nickname,a.userid,a.test_lessonid,"
            ."o.sys_operator,mi.account,mi.name,mi.account_role,pa.phone as p_phone,".
            "pa.nickname as p_nickname,ppa.phone as pp_phone,ppa.nickname as pp_nickname"
            ." from %s a"
            ." left join %s pa on a.parentid = pa.id"
            ." left join %s ppa on pa.parentid = ppa.id"
            ." left join %s s on s.userid = a.userid"
            ." left join %s ao on ao.aid = a.id "
            ." left join %s o on ao.orderid = o.orderid"
            ." left join %s mi on s.assistantid = mi.uid"
            ." where %s",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_agent_order::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
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

    public function add_agent_row($parentid,$phone,$userid,$type,$parent_adminid=0){
        $ret = $this->row_insert([
            "parentid"       => $parentid,
            "phone"          => $phone,
            "userid"         => $userid,
            "type"           => $type,
            "parent_adminid" => $parent_adminid,
            "create_time"    => time(null),
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
            ."aa.wx_openid pp_wx_openid,aa.agent_level pp_agent_level, aa.id pp_id  "
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
            . " ao.pp_level o_agent_level , ao.pp_price o_price ,  ao.pp_open_price o_open_price ,  o.price o_from_price , o.pay_time o_from_pay_time  ,  o.orderid  o_from_orderid,@agent_user_link:=1 as agent_user_link"

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
                "p1_phone"                    => $p1["p_phone"],
                "agent_user_link"         => $p1["agent_user_link"],
                "p1_userid"         => $p1["p_userid"],
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
                    "p2_phone"=> $p2["phone"],
                    "agent_user_link"         => $p2["agent_user_link"],
                    "p2_userid"=> $p2["userid"],
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
        $is_test_user = $student_info['is_test_user'];
        $level        = 0;

        if($userid
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


    public function reset_user_info_order_info($send_wx_flag, $id,$userid ,$is_test_user,$create_time) {
        //重置订单信息
        $p_pp_info = $this->task->t_agent->get_p_pp_by_id($id);
        $phone = $p_pp_info['phone'];
        $p_wx_openid = $p_pp_info['p_wx_openid'];
        $pp_wx_openid = $p_pp_info['pp_wx_openid'];
        //该订单是否已生成过奖励记录
        $order_info_old = $this->task->t_agent_order->get_row_by_aid($id);
        $this->task->t_agent_order->row_delete_by_aid($id);
        $orderid=0;
        \App\Helper\Utils::logger("yxyx_userid $userid yxyx_is_test_user $is_test_user");
        if($userid && $is_test_user == 0 ){
            $order_info = $this->task-> t_order_info->get_agent_order_info($userid ,$create_time);
            \App\Helper\Utils::logger("yxyx_order_info_orderid ".$order_info["orderid"]);
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

                //插入一级佣金记录
                if($p_open_price > 0){
                    $agent_income_type = E\Eagent_income_type::V_L1_CHILD_COMMISSION_INCOME;
                    $this->task->t_agent_income_log->insert_commission_reward_log($pid,$id,$p_open_price,$agent_income_type);
                }
                //插入二级佣金奖励
                if($pp_open_price > 0){
                    $agent_income_type = E\Eagent_income_type::V_L2_CHILD_COMMISSION_INCOME;
                    $this->task->t_agent_income_log->insert_commission_reward_log($ppid,$id,$pp_open_price,$agent_income_type);
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

                \App\Helper\Utils::logger("yxyx_p_info $pid, $p_wx_openid,  $p_price  ");
                if(!$order_info_old && $p_wx_openid && $p_price){
                    $p_price_new = $p_price/100;
                    if ($send_wx_flag ) {
                        $this->send_wx_msg_2002($id,$pid,$price,$p_price_new,$phone);
                    }
                }

                if(!$order_info_old && $pp_wx_openid && $pp_price){
                    $pp_price_new = $pp_price/100;
                    if ($send_wx_flag ) {
                        $this->send_wx_msg_2002($id,$ppid,$price,$pp_price_new,$phone);
                    }
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
            $log_money = 2500;
            if ($orderid) { //有订单
                if ($pp_agent_status_money_open_flag !=1 ) {
                    $this->field_update_list($child_id,[
                        "pp_agent_status_money_open_flag" => 1
                    ]);
                }
                $order_count+=1;
                //添加收入记录
                $agent_income_type = E\Eagent_income_type::V_L2_CHILD_INVITE_INCOME;
                $this->task->t_agent_income_log->insert_reward_log($id,$child_id,$log_money,$agent_income_type);
            }else {
                if ($item["lesson_user_online_status"] ==1 ) {
                    $set_open_list[]=[
                        "id" => $child_id,
                        "pp_agent_status_money_open_flag" =>$pp_agent_status_money_open_flag,
                    ];
                    //添加收入记录
                    $agent_income_type = E\Eagent_income_type::V_L2_CHILD_INVITE_INCOME;
                    $this->task->t_agent_income_log->insert_reward_log($id,$child_id,$log_money,$agent_income_type);
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
            $log_money = 5000;
            if ($orderid) { //有订单
                if ($agent_status_money_open_flag !=1 ) {
                    $this->field_update_list($child_id,[
                        "agent_status_money_open_flag" => 1
                    ]);
                }
                $order_count+=1;
                //添加收入记录
                $agent_income_type = E\Eagent_income_type::V_L1_CHILD_INVITE_INCOME;
                $this->task->t_agent_income_log->insert_reward_log($id,$child_id,$log_money,$agent_income_type);
            }else {
                if ($item["lesson_user_online_status"] ==1 ) {
                    $set_open_list[]=[
                        "id" => $child_id,
                        "agent_status_money_open_flag" =>$agent_status_money_open_flag,
                    ];
                    //添加收入记录
                    $agent_income_type = E\Eagent_income_type::V_L1_CHILD_INVITE_INCOME;
                    $this->task->t_agent_income_log->insert_reward_log($id,$child_id,$log_money,$agent_income_type);

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
    public function wx_noti_agent_status( $send_wx_flag,$id, $create_time,$parentid, $agent_level , $test_lessonid, $phone, $old_agent_status, $agent_status) {
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


                }else if ($agent_status == E\Eagent_status::V_10) {//拨通推送
                    if ( $send_wx_flag){
                        $this->send_wx_msg_1002($id,$parentid,$phone);
                    }
                }else if ($agent_status == E\Eagent_status::V_20) { //排课
                    if ( $send_wx_flag){
                        $lesson_start=$this->task->t_lesson_info->get_lesson_start($test_lessonid);
                        $this->send_wx_msg_1003($id,$parentid,$phone,$lesson_start);
                    }
                }else if ($agent_status == E\Eagent_status::V_30) { //试听成功
                    if ( $send_wx_flag){
                        $lesson_start=$this->task->t_lesson_info->get_lesson_start($test_lessonid);
                        $this->send_wx_msg_1004($id,$parentid,$phone,$lesson_start,$agent_level);
                    }
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

    public function reset_user_info($id, $send_wx_flag=false ) {

        $agent_info = $this->field_get_list($id,"*");
        $userid  = $agent_info["userid"];
        $agent_type= $agent_info["type"];
        $agent_level_old = $agent_info["agent_level"];
        $wx_openid_old  = $agent_info["wx_openid"];
        $old_agent_status = $agent_info["agent_status"];
        $agent_student_status=0;
        $agent_status=0;

        \App\Helper\Utils::logger("t_agent_yxyx: $id");


        $test_lessonid=0;
        if ($userid) {
            $student_info = $this->task->t_student_info->field_get_list($userid,"is_test_user");
            $is_test_user = $student_info['is_test_user'];
            $create_time = $agent_info['create_time'];
            $now=time(NULL);
            list($test_lessonid ,$lesson_info)=$this->reset_user_info_test_lesson($id,$userid,$is_test_user, $create_time );
            $orderid=$this->reset_user_info_order_info($send_wx_flag , $id,$userid,$is_test_user,$create_time);

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
            if ($agent_status_money > 0) {
                $pp_agent_status_money = $agent_status_money/2;
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
        $activity_money=$this->task->t_agent_money_ex->get_all_money($id);
        //活动奖励可提现部分
        $examined_activity_money=$this->task->t_agent_money_ex->get_examined_activity_money($id);

        //双11活动
        if($userid){
            //t_luck_draw_yxyx_for_ruffian
            $ruffian_money = $this->task->t_luck_draw_yxyx_for_ruffian->get_ruffian_money_for_total($userid);
        }else{
            $ruffian_money = 0;
        }

        //优学优享每日转盘活动
        $daily_lottery_money = $this->task->t_agent_daily_lottery->get_sum_daily_lottery($id);


        \App\Helper\Utils::logger("yxyx_ruffian: $ruffian_money userid: $userid");

        //总提成信息
        $all_yxyx_money      = $order_all_money +  $l1_agent_status_all_money+ $l2_agent_status_all_money + $activity_money +$ruffian_money+$daily_lottery_money;
        // $all_yxyx_money      = $order_all_money +  $l1_agent_status_all_money+ $l2_agent_status_all_money + $activity_money ;
        //可提现大转盘奖励
        $has_cash_daily_lottery = $this->task->t_agent_daily_lottery->get_sort_daily_lottery($id,1);
        $all_open_cush_money = $order_open_all_money +  $l1_agent_status_all_open_money+ $l2_agent_status_all_open_money +$examined_activity_money+$ruffian_money+$has_cash_daily_lottery;
        $all_have_cush_money = $this->task->t_agent_cash->get_have_cash($id,1);
        $all_cush_money = $this->task->t_agent_cash->get_have_cash($id,[0,1]);
        //未提现大转盘奖励
        $no_cash_daily_lottery = $this->task->t_agent_daily_lottery->get_sort_daily_lottery($id,0);
        //如果用户剩余可体现金额超过25 [每日转盘活动金额直接进入可提现]
        if($all_open_cush_money+$no_cash_daily_lottery-$all_cush_money > 2500){
            if($no_cash_daily_lottery > 0){
                $all_open_cush_money += $no_cash_daily_lottery;
                $id_str = $this->get_daily_lottery_id_str($id,$no_cash_daily_lottery);
                //将每日转盘奖励计入资金记录
                //添加收入记录
                $agent_income_type = E\Eagent_income_type::V_AGENT_DAILY_LOTTERY;
                $this->task->t_agent_income_log->insert_daily_lottery_log($id,$daily_lottery_money,$agent_income_type,$id_str);
                $this->task->t_agent_daily_lottery->update_all_flag($id);
            }
        }
        
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

        //$check_time=strtotime( \App\Helper\Config::get_config("yxyx_new_start_time"));
        $check_time=strtotime( "2017-09-01");
        if ($agent_info["create_time"] < $check_time) {
            if ( $agent_info["agent_status_money_open_flag"] !=0 ) {
                $this->field_update_list($id,[
                    "agent_status_money_open_flag" => 0,
                    "agent_status_money" => 0,
                ]);
            }
        }else {
            $this->wx_noti_agent_status( $send_wx_flag, $id , $agent_info["create_time"], $agent_info["parentid"],$agent_level,$test_lessonid ,$agent_info["phone"],$old_agent_status,$agent_status);
        }

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
            if ($send_wx_flag)  {
                $this->send_wx_msg_2001($id,$id);
            }
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

    public function get_parent_map()
    {
        $sql = $this->gen_sql_new(
            "select id,parentid,type from %s",
            self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql,function( $item){
            return $item["id"];
        } );
    }
    //@desn:获取以推荐人为key的数组
    public function get_child_map(){
        $sql = $this->gen_sql_new(
            "select id,parentid,type from %s",
            self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql,function( $item){
            return $item["parentid"];
        } );
    }
    //设置用户为会员
    public function set_add_type_2( $id) {
        $type=$this->get_type($id);
        /*
            0 => "注册",
            1 => "学员",
            2 => "会员",
            3 => "会员+学员",
        */
        if ($type==0 || $type==1  ) { //+会员
            $this->field_update_list($id,[
                "type" => $type+2,
            ]);
        }

    }

    //@desn:检查优学优享团登录
    public function check_login_userid($phone, $passwd)
    {
        $sql = $this->gen_sql("select a.id ".
                              "from %s a, %s ui,%s ptu where a.userid = ui.userid and a.userid = ptu.userid ".
                              "and a.phone = '%s' and ui.passwd = '%s' ".
                              "and ptu.role = %u",
                              self::DB_TABLE_NAME,
                              t_user_info::DB_TABLE_NAME,
                              t_phone_to_user::DB_TABLE_NAME,
                              $phone, $passwd,E\Erole::V_STUDENT );
        return $this->main_get_value( $sql  );
    }
    //@desn:获取优学优享id
    public function get_agentid($phone )
    {
        $sql = $this->gen_sql("select  id ".
                              " from  %s a,%s ptu".
                              " where ptu.userid = a.userid and a.phone= '%s' and ptu.role = %u",
                              self::DB_TABLE_NAME,
                              t_phone_to_user::DB_TABLE_NAME,
                              $phone,
                              E\Erole::V_STUDENT
        );
        return $this->main_get_value( $sql );
    }

    //@desn:判断该用户是否为团长邀请
    //@param: $phone 被邀请人电话
    //@param: $parentid 邀请人id
    public function check_is_invite($phone,$parentid){
        $where_arr = [
            ["phone = '%s'",$phone,'-1'],
            ['parentid = %u',$parentid,'-1'],
        ];

        $sql = $this->gen_sql_new(
            "select id from %s "
            ."where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_value($sql);

    }

    //@desn:根据电话号码获取agent_id
    public function get_agentid_by_phone($phone){
        $where_arr = [
            ["phone = '%s'",$phone,'-1'],
        ];

        $sql = $this->gen_sql_new(
            "select id from %s where %s"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    //@desn:获取团长姓名
    public function get_colconel_info($colconel_agent_id){
        $where_arr = [
            'id' => $colconel_agent_id,
        ];

        $sql = $this->gen_sql_new(
            "select concat_ws('/',phone,nickname) from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_value($sql);
    }



    //获取某个团长试听数[一级]
    public function get_this_colconel_test_lesson_count($colconel_id){
        $where_arr = [
            ['id = %u',$colconel_id,'-1'],
        ];
        $sql = $this->gen_sql_new(
            "select id as colconel_id,concat_ws('/',phone,nickname) as colconel_name,".
            "l1_agent_status_test_lesson_succ_count as test_lesson_count ".
            "from %s ".
            "where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    //获取某个团长所有学员数、会员数
    public function get_this_colconel_invite_count($colconel_id){
        $where_arr = [
            ['parentid = %u',$colconel_id,'-1'],
        ];
        $sql = $this->gen_sql_new(
            "select sum(if(type in (1,3),1,0)) as student_count,sum(if(type in (2,3),1,0)) as member_count ".
            "from %s ".
            "where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    //获取所有团长试听数[一级]
    public function get_colconel_test_lesson_count(){
        $sql = $this->gen_sql_new(
            "select sum(l1_agent_status_test_lesson_succ_count) as test_lesson_count ".
            "from %s ".
            "where id in (select distinct colconel_agent_id from %s)",
            self::DB_TABLE_NAME,
            t_agent_group::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }
    //获取所有团长所有学员数、会员数
    public function get_colconel_invite_count(){
        $sql = $this->gen_sql_new(
            "select sum(if(type in (1,3),1,0)) as student_count,sum(if(type in (2,3),1,0)) as member_count ".
            "from %s ".
            "where parentid in (select distinct colconel_agent_id from %s)",
            self::DB_TABLE_NAME,
            t_agent_group::DB_TABLE_NAME
        );
        return $this->main_get_row($sql);
    }
    /*
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
    */


    public function  send_wx_msg_1004( $from_agentid, $to_agentid, $phone , $lesson_start , $agent_level) {
        $template_id = 'ahct5cHBDNVvA3rAYwMuaZ7VZlgx10xRfZ7ssh24hPQ';
        $agent_wx_msg_type = E\Eagent_wx_msg_type::V_1004;
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
        $this->send_wx_msg( $agent_wx_msg_type, $from_agentid, $to_agentid, $template_id , $data );
    }

    public function  send_wx_msg_1003( $from_agentid, $to_agentid, $phone , $lesson_start ) {
        $template_id = '5gRCvXir0giV6kQcgTMki0TUWfQuKD1Vigg7zanvsD8';
        $agent_wx_msg_type = E\Eagent_wx_msg_type::V_1003;
        $data = [
            'first'    => "您邀请的学员{$phone}成功预约测评课，您获得10元奖励。 ",
            'keyword1' => $phone,
            'keyword2' =>  $phone ,
            'keyword3' => "测评课",
            'keyword4' => \App\Helper\Utils::unixtime2date($lesson_start,"Y-m-d H:i") ,
            'keyword5' => "理优1对1",
            'remark'   => "如学员成功上完测评课，将再获得30元奖励。",
        ];
        $this->send_wx_msg( $agent_wx_msg_type, $from_agentid, $to_agentid, $template_id , $data );
    }


    public function  send_wx_msg_1002( $from_agentid, $to_agentid, $phone  ) {
        $template_id = 'eP6Guhb_w4s7NxnuF1yf2fz_cRF1wLqguFrQLtOKYlc';
        $agent_wx_msg_type = E\Eagent_wx_msg_type::V_1002;
        $data = [
            'first'    => "课程老师已成功联系学员{$phone}，您获得5元奖励。",
            'keyword1' => $phone,
            'keyword2' => "预约测评课" ,
            'keyword3' => date('Y-m-d H:i:s',time()),
            'keyword4' => "已接通" ,
            'remark'   => "如学员成功预约测评课，将再获得10元奖励",
        ];
        $this->send_wx_msg( $agent_wx_msg_type, $from_agentid, $to_agentid, $template_id , $data );
    }

    public function  send_wx_msg_1001( $from_agentid, $to_agentid, $phone  ) {
        $agent_wx_msg_type = E\Eagent_wx_msg_type::V_1001;
        $template_id = 'WEg0PqvnN23HboTngezq0Ut8cPLf-g_0Tgmv4zhj4Eo';
        $data = [
            'first'    => "恭喜您成功邀请学员{$phone}参加测评课，您获得5元奖励",
            'keyword1' =>"测评课" ,
            'keyword2' => date('Y-m-d H:i:s',time()),
            'keyword3' => $phone ,
            'remark'   => "如课程老师成功联系学员，将再获得5元奖励。"
        ];
        $this->send_wx_msg( $agent_wx_msg_type, $from_agentid, $to_agentid, $template_id , $data );

    }

    public function  send_wx_msg_2001( $from_agentid, $to_agentid ) {
        $agent_wx_msg_type = E\Eagent_wx_msg_type::V_2001;
        $template_id = 'ZPrDo_e3DHuyajnlbOnys7odLZG6ZeqImV3IgOxmu3o';
        $data = [
            'first'    => '等级升级提醒',
            'keyword1' => '水晶会员',
            'keyword2' => "永久" ,
            'remark'   => '恭喜您升级成为水晶会员,如果您邀请的学员成功购课则可获得最高1000元的奖励哦。',
        ];
        $this->send_wx_msg( $agent_wx_msg_type, $from_agentid, $to_agentid, $template_id , $data );
    }

    //消息
    public function  send_wx_msg_2002( $from_agentid, $to_agentid, $price,$get_money,$phone  ) {

        if ($price >300000 )  {
            return  false;
        }

        if ($get_money >1000 )  {
            return  false;
        }

        $agent_wx_msg_type = E\Eagent_wx_msg_type::V_2002;
        $template_id = 'o2-tyCQY0oopNiLolOq_6HgiMd8cntAtNlt9i_w_EL0';
        $data = [
            'first'    => '恭喜您获得邀请奖金',
            'keyword1' => '理优１ｖ１课程',
            'keyword2' => $price ."元" ,
            'remark'   => '恭喜您邀请的学员'.$phone.'购课成功，课程金额'.$price.'元，您获得'.$get_money.'元。',
        ];
        $this->send_wx_msg( $agent_wx_msg_type, $from_agentid, $to_agentid, $template_id , $data );

    }
    //@desn:冻结申请体现金额推送
    //@param:$from_agentid 无用参数
    //@param:$to_agentid 发送给的用户
    //@param:$agent_freeze_type 冻结类型
    //@param:$phone 违规推荐人电话号码
    //@param:$agent_money_ex_type_str 活动类型
    //@param:$url
    public function  send_wx_msg_freeze_cash_money($from_agentid='',$to_agentid,$agent_freeze_type,$phone,$agent_money_ex_type_str='',$url ="",$bad_time ) {
        $agent_wx_msg_type = E\Eagent_wx_msg_type::V_2002;
        $template_id = 'nlkQvbRYlLz8fd1Nupp7vERRRGOgBVe54d0IpJhUqZo';
        if($agent_freeze_type == 1){
            $agent_freeze_type_desc = $phone.'(手机号)试听奖励';
            $bad_time = $this->get_test_lesson_bad_time($to_agentid);
        }elseif($agent_freeze_type == 2){
            $agent_freeze_type_desc = $phone.'(手机号)签单奖励';
            $bad_time = $this->task->t_agent_order->get_order_bad_time($to_agentid);
        }elseif($agent_freeze_type == 3){
            $agent_freeze_type_desc = $phone.'(手机号)在'.$agent_money_ex_type_str.'中';
        }else
            return false;

        if(empty($bad_time))
            $bad_time = time(NULL);

        $data = [
            'first'    => '您的学员：'.$agent_freeze_type_desc.'存在违规行为。',
            'keyword1' => date('Y年m月d日 H:i:s',$bad_time),
            'keyword2' => '利用漏洞',
            'remark'   => '违规行为将会冻结此次奖励',
        ];
        $msg=json_encode($data ,JSON_UNESCAPED_UNICODE) ;
        \App\Helper\Utils::logger("msg : $msg");
        if (!$url) {
            $wx_config =\App\Helper\Config::get_config("yxyx_wx") ;
            $base_url= $wx_config["url"] ;
            $url="$base_url/wx_yxyx_web/index";
        }
        $openid= $this->get_wx_openid($to_agentid);

        $wx_config  = \App\Helper\Config::get_config("yxyx_wx");
        $wx         = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
        //$jim_openid="oAJiDwMAO47ma8cUpCNKcRumg5KU";
        $jim_openid="oAJiDwMAO47ma8cUpCNKcRumg5KU";
        $wx->send_template_msg($jim_openid,$template_id,$data,$url);

        $succ_flag=false;
        $succ_flag = $wx->send_template_msg($openid,$template_id,$data,$url);
        $this->task->t_agent_wx_msg_log->add($from_agentid,$to_agentid,$agent_wx_msg_type,$msg,$succ_flag);

    }
    //消息
    public function  send_wx_msg( $agent_wx_msg_type, $from_agentid, $to_agentid, $template_id , $data, $url ="" ) {


        if (!$this->task->t_agent_wx_msg_log->check_is_send(
            $from_agentid,$to_agentid,$agent_wx_msg_type)) {
            //未发送

            $msg=json_encode($data ,JSON_UNESCAPED_UNICODE) ;
            if (!$url) {
                $wx_config =\App\Helper\Config::get_config("yxyx_wx") ;
                $base_url= $wx_config["url"] ;
                $url="$base_url/wx_yxyx_web/index";
            }
            $openid= $this->get_wx_openid($to_agentid);

            $wx_config  = \App\Helper\Config::get_config("yxyx_wx");
            $wx         = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
            $xy_openid ="oAJiDwNulct06mAlmTTO97zKp_24";
            $wx->send_template_msg($xy_openid,$template_id,$data,$url);

           // $jim_openid="oAJiDwMAO47ma8cUpCNKcRumg5KU";
            $jim_openid="oAJiDwN_Xt1IR66kQgYxYlBA4W6I";
            $wx->send_template_msg($jim_openid,$template_id,$data,$url);

            $succ_flag=false;
            $succ_flag = $wx->send_template_msg($openid,$template_id,$data,$url);
            $this->task->t_agent_wx_msg_log->add($from_agentid,$to_agentid,$agent_wx_msg_type,$msg,$succ_flag);
        }

    }
    public function get_agentid_by_userid($userid) {
        $sql=$this->gen_sql_new(
            "select id  from %s where userid=%u  ",
            self::DB_TABLE_NAME, $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_list_for_select($id,$gender, $nick_phone, $page_num )
    {
        $where_arr = array(
            array( "id=%d", $id, -1 ),
        );
        if ($nick_phone!=""){
            $where_arr[]=sprintf( "( phone like '%s%%'  )", $this->ensql($nick_phone));
        }


        $sql =  $this->gen_sql_new( "select  id ,  nickname as  nick,    nickname  as realname,  phone,'' as gender  from %s    where %s ",
                                    self::DB_TABLE_NAME,  $where_arr );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }
    private $id_map = [];
    private $student_count = 0;
    private $member_count = 0;
    private $err_flag = false;
    private $child_arr = [];
    //@desn:获取用户无限制下级[会员数、学员数、下级字符串]
    //@param:$this_parentid 起始父id
    //@param:$month_first_day 每月开始时间戳
    public function get_cycle_child_month($this_parentid,$month_first_day=0,$month_last_day=0){
        $this->id_map = [];
        $this->student_count = 0;
        $this->member_count = 0;
        $this->err_flag = false;
        $this->child_arr = [];
        //构造用户数组
        $parent_arr = [
            ['id'=>$this_parentid]
        ];
        $this->get_child_by_cycle_month($parent_arr,$month_first_day,$month_last_day);
        if($this->err_flag)
            echo $this_parentid.'推荐人循环!';

        return [$this->child_arr,$this->student_count,$this->member_count];
    }
    //@desn:获取无限制下限信息
    private function get_child_by_cycle_month($parent_arr,$month_first_day,$month_last_day){
        foreach($parent_arr as $item){
            $this->id_map[$item['id']] = true;
            $where_arr = [
                ['parentid = %u',$item['id'],'-1'],
            ];
            $sql = $this->gen_sql_new(
                "select id,type,create_time from %s where %s",self::DB_TABLE_NAME,$where_arr
            );
            $child_list=$this->main_get_list($sql);
            foreach($child_list as $val){
                if(isset($id_map[$val['id']])){
                    $this->err_flag=true;
                    break;
                }
                $this->child_arr[] = $val['id'];
                if(($val['type'] == 1 || $val['type'] == 3) && $val['create_time'] >= $month_first_day && $val['create_time'] < $month_last_day)
                    ++$this->student_count;
                if(($val['type'] == 2 || $val['type'] == 3) && $val['create_time'] > $month_first_day && $val['create_time'] < $month_last_day)
                    ++$this->member_count;

            }
            if($child_list)
                $this->get_child_by_cycle_month($child_list,$month_first_day,$month_last_day);

        }
    }
    //@desn:获取用户无限制下级[会员数、学员数、下级字符串]
    public function get_cycle_child($this_parentid){
        //构造用户数组
        $parent_arr = [
            ['id'=>$this_parentid]
        ];

        list($child_arr,$student_count,$member_count,$error_flag)=$this->get_child_by_cycle($parent_arr);
        if($error_flag)
            echo $this_parentid.'推荐人循环!';

        return [$child_arr,$student_count,$member_count];
        // $this->get_id($res);
    }
    //@desn:获取无限制下限信息
    private function get_child_by_cycle($parent_arr){
        $counter = 0;
        if($counter == 0){
            $child_arr = [];
            $student_count = 0;
            $member_count = 0;
            $err_flag = false;
            $id_map[$parent_arr[0]['id']] = true;
        }

        foreach($parent_arr as $item){
            $where_arr = [
                ['parentid = %u',$item['id'],'-a'],
            ];
            $sql2 = $this->gen_sql_new(
                "select id from %s where %s",self::DB_TABLE_NAME,$where_arr
            );
            $child_list=$this->main_get_list($sql2);
            foreach($child_list as $val){
                if(isset($id_map[$val['id']])){
                    $err_flag=true;
                    break;
                }
                $id_map[$val['id']] = true;
                $child_arr[] = $val['id'];
            }
            $sql = $this->gen_sql_new(
                "select sum(if(type in (1,3),1,0)) as student_count,sum(if(type in (2,3),1,0)) as member_count ".
                "from %s ".
                "where %s",
                self::DB_TABLE_NAME,
                $where_arr
            );
            $child_count = $this->main_get_row($sql);
            $student_count +=$child_count['student_count'];
            $member_count +=$child_count['member_count'];

            $counter++;

            if($child_list)
                $this->get_child_by_cycle($child_list);

        }

        return [$child_arr,$student_count,$member_count,$err_flag];
    }

    function get_id($parentid , $parent_map ,$this_parentid)
    {
        $id_map=[ ];
        $id_map[$this_parentid]=true;
        $error_flag=false;
        $tmpid=$this_parentid;
        $member_count = 0;
        $student_count = 0;
        do{
            $tmpid=@$parent_map[$tmpid]["id"];
            if (isset ($id_map[ $tmpid]) ) {
                $error_flag=true;
                break;
            }
            if($tmpid){
                $id_map[$tmpid] =true;
                if(@$parent_map[$tmpid]['type'] = 1 || @$parent_map[$tmpid]['type'] = 3)
                    $student_count++;
                if(@$parent_map[$tmpid]['type'] = 1 || @$parent_map[$tmpid]['type'] = 3)
                    $member_count++;
            };


        }while (!$tmpid==0 );
        unset($id_map[$this_parentid]);
        return array( $error_flag,  $id_map ,$member_count,$student_count );
    }
    //@desn:获取推荐用户上试听课信息
    public function get_child_test_lesson_info($in_str) {
        $check_time=strtotime( \App\Helper\Config::get_config("yxyx_new_start_time"));
        if(\App\Helper\Utils::check_env_is_test())
            $check_time = 1451606400;

        $sql = $this->gen_sql_new(
            "select a.id, l.lesson_user_online_status , a.agent_status_money_open_flag , "
            . " a.agent_status_money,l.lesson_start as l_time,a.agent_student_status"
            . " from %s a "
            . " left join  %s l on a.test_lessonid =l.lessonid  "
            ." where  a.id in ".$in_str."  and a.create_time > %u  order by l.lesson_start asc ",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $check_time
        );
        return $this->main_get_list($sql);
    }
    //获取我的邀请列表
    public function my_invite($agent_id,$page_info,$page_count){
        $where_arr = [
            'a1.type <> 2',
            ['a1.parentid = %u',$agent_id,-1]
        ];
        $sql = $this->gen_sql_new(
            "select  a1.id  agent_id, a1.phone,a1.nickname, a1.agent_status,"
            ."a1.agent_status_money,a1.create_time,a1.agent_student_status,si.nick "
            . " from %s a1"
            ." left join %s si on a1.userid = si.userid"
            ." where  %s order by a1.create_time desc",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,$page_count);
    }
    //会员邀请
    public function member_invite($agent_id,$page_info,$page_count){
        $sql = $this->gen_sql_new(
            "select a2.id as agent_id,a2.phone,a2.nickname,a2.agent_status,si.nick,"
            ."a2.pp_agent_status_money as agent_status_money,a2.create_time,a2.agent_student_status "
            ."from %s a2 "
            ."left join %s si on a2.userid = si.userid "
            ." where  a2.parentid in (select id from %s where parentid = %u ) order by a2.create_time desc",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $agent_id
        );
        return $this->main_get_list_by_page($sql,$page_info,$page_count);
    }

    public function get_invite_num($start_time, $pid){

        $where_arr = [
            "a.create_time>=$start_time",
            "a.parentid=$pid",
            "a.type=1"
        ];

        $sql = $this->gen_sql_new("  select create_time, phone from %s a"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }


    public function update_money($parentid, $prize){
        $sql = $this->gen_sql_new("  update %s set all_yxyx_money = all_yxyx_money+$prize, all_open_cush_money=all_open_cush_money+$prize"
                                  ." where userid=%s"
                                  ,self::DB_TABLE_NAME
                                  ,$parentid
        );

        return $this->main_update($sql);
    }
    //@desn:获取可体现金额
    public function get_can_carry($agent_id){
        $sql = $this->gen_sql_new(
            "select all_open_cush_money from %s where id = % u "
            ,self::DB_TABLE_NAME
            ,$agent_id
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取我的邀请列表 [已获取]
    public function my_had_invite($agent_id,$page_info,$page_count,$last_succ_cash_time){
        $where_arr = [
            ['a.parentid = %u',$agent_id,'-1'],
            ['a.agent_status >= %u',30]
        ];
        $where_arr_2 = [
            ['agent_id = %u',$agent_id],
            ['create_time >= %u',$last_succ_cash_time],
            'agent_income_type' => 1
        ];
        $sql = $this->gen_sql_new(
            "select  a.id,a.phone,a.nickname,a.agent_status_money,a.agent_status,si.nick "
            . " from %s a"
            ." left join %s si on si.userid = a.userid"
            ." where %s and id in (select child_agent_id from %s where %s) order by a.id desc",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr,
            t_agent_income_log::DB_TABLE_NAME,
            $where_arr_2
        );
        return $this->main_get_list_by_page($sql,$page_info,$page_count);
    }
    //@desn:会员邀请奖励列表[已获取]
    public function member_had_invite($agent_id,$page_info,$page_count,$last_succ_cash_time){
        $where_arr_2 = [
            ['agent_id = %u',$agent_id],
            ['create_time >= %u',$last_succ_cash_time],
            'agent_income_type' => 2
        ];
        $sql = $this->gen_sql_new(
            "select a.phone,a.nickname,a.pp_agent_status_money as agent_status_money,a.agent_status,si.nick "
            ."from %s a"
            ." left join %s si on si.userid = a.userid"
            ." where  a.parentid in (select id from %s where parentid = %u ) and pp_agent_status_money_open_flag = 1 "
            ." and a.id in (select child_agent_id from %s where %s)"
            ."order by create_time desc",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $agent_id,
            t_agent_income_log::DB_TABLE_NAME,
            $where_arr_2
        );
        return $this->main_get_list_by_page($sql,$page_info,$page_count);
    }

    public function get_info_by_pid($parentid){
        $sql = $this->gen_sql_new("  select wx_openid,phone from %s ta"
                                  ." where userid=$parentid"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }
    //@desn:获取按邀请人列表 [一级]
    //@param 1:学员2：会员3：学员&会员
    public function get_invite_type_list($agent_id,$type=1,$page_info,$page_count){
        $where_arr = [
            ['a.parentid = %u',$agent_id,'-1'],
            ['a.type = %u',$type,'-1']
        ];
        $sql = $this->gen_sql_new(
            "select a.id,a.phone,a.nickname,a.agent_status,a.agent_student_status,a.create_time,si.nick ".
            "from %s a ".
            "left join %s si on si.userid = a.userid ".
            "where %s order by id desc",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,$page_count);
    }
    //@desn:获取邀请人
    public function get_second_invite_list($parentid){
        $sql = $this->gen_sql_new(
            "select a.phone,a.nickname,a.create_time,a.agent_status,oi.price,a.agent_student_status ".
            "from %s a ".
            "left join %s ao on ao.aid = a.id ".
            "left join %s oi on ao.orderid = oi.orderid ".
            "where a.parentid = %u order by a.id desc",
            self::DB_TABLE_NAME,
            t_agent_order::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $parentid
        );

        return $this->main_get_list($sql);
    }
    public function get_agent_id_by_openid($openid){
        $sql = $this->gen_sql_new("  select id, userid, phone from %s ag "
                                  ." where wx_openid = '%s'"
                                  ,self::DB_TABLE_NAME
                                  ,$openid
        );

        return $this->main_get_row($sql);
    }

    //@desn:检测团员是否是会员
    public function check_is_member($phone){
        $sql = $this->gen_sql_new(
            "select id from %s where type in (2,3) and phone = '%s' ",
            self::DB_TABLE_NAME,
            $phone
        );
        return $this->main_get_value($sql);
    }


    public function get_yxyx_member($start_time, $end_time,$nickname,$phone,$page_info,$order_by_str,$page_flag){

        $where_arr = [
            ['na.create_time>=%u', $start_time, -1],
            ['na.create_time<%u', $end_time, -1],
            's.is_test_user=0',
            'na.type in (1,3)',
        ];

        if ($nickname) {
            $where_arr[]=sprintf(" a.nickname like '%s%%' ", $this->ensql($nickname));
        }

        if ($phone) {
            $where_arr[]=sprintf(" a.phone like '%s%%' ", $this->ensql($phone));
        }
        $tq_arr = [
            ['tq.start_time>=%u', $start_time, -1],
            ['tq.start_time<%u', $end_time, -1],
        ];

        $sql = $this->gen_sql_new(
            "select a.id,a.phone phone1,a.nickname nick1,aa.phone phone2,aa.nickname nick2,aaa.phone phone3,aaa.nickname nick3,"
            ." count(distinct s.userid) user_count,count(distinct ao.aid) order_user_count,sum(o.price) price,"
            ." count(distinct if( tq.id is null,na.userid,0 ) ) no_revisit_count,"
            ." count(distinct if( tq.is_called_phone=1,na.userid,0 ) ) ok_phone_count,"
            ." count(distinct if(na.test_lessonid>0,na.userid,0 ) ) rank_count,"
            ." count(distinct if(l.lesson_del_flag=0 and l.lesson_user_online_status=1,na.userid,0 ) ) ok_lesson_count,"
            ." count(distinct if(l.lesson_del_flag=1,na.userid,0 ) ) del_lesson_count"
            ." from %s a "
            ." left join %s aa on aa.id=a.parentid"
            ." left join %s aaa on aaa.id=aa.parentid"
            ." left join %s na on na.parentid=a.id"
            ." left join %s s on s.userid=na.userid"
            ." left join %s ao on ao.pid=a.id and ao.aid=na.id"
            ." left join %s o on o.orderid=ao.orderid and o.contract_type=0 and o.contract_status>0 and o.pay_time>0"
            ." left join %s tq on tq.phone=na.phone and %s "
            ." left join %s l on l.lessonid=na.test_lessonid "
            ." where %s "
            ." group by a.id"
            ." $order_by_str"
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_agent_order::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,t_tq_call_info::DB_TABLE_NAME
            ,$tq_arr
            ,t_lesson_info::DB_TABLE_NAME
            ,$where_arr
        );

        if( $page_flag ) {
            return $this->main_get_list_by_page($sql,$page_info,10, true);
        } else {
            return $this->main_get_list($sql);
        }


    }
    //@desn:刷新团队每日业绩
    public function reset_group_member_result($id){
        $agent_info = $this->field_get_list($id,"*");
        $userid  = $agent_info["userid"];

        $child_arr = [];
        $cycle_student_count = 0;
        $cycle_member_count = 0;
        //获取当前月份开始时间戳
        $month_first_day = strtotime(date('Y-m-01'));
        $month_first_day_format = date('Y-m-01');
        $month_last_day = strtotime("$month_first_day_format +1 month -1 second");
        //计算所有学员量、会员量[无下限限制下级]
        list($child_arr,$cycle_student_count,$cycle_member_count) =$this->get_cycle_child_month($id,$month_first_day,$month_last_day);
        $cycle_test_lesson_count = 0;
        $cycle_order_count = 0;
        $cycle_order_money = 0;
        //用户有推荐人
        if($child_arr){
            $in_str = '('.implode(',',$child_arr).')';
            //获取该用户推荐人的试听量[无限制下架]
            $test_lesson_info = $this->get_child_test_lesson_info($in_str);
            if($test_lesson_info){
                foreach( $test_lesson_info as $item ) {
                    if ($item["lesson_user_online_status"] ==1 && $item['l_time'] >= $month_first_day && $item['l_time'] < $month_last_day)
                        $cycle_test_lesson_count += 1;
                }
            }

            //计算签单金额、签单量[无下限限制下级]
            $child_order_info = $this->task->t_agent->get_cycle_child_order_info($in_str,$month_first_day,$month_last_day);
            $cycle_order_count = $child_order_info['child_order_count'];
            $cycle_order_money = $child_order_info['child_order_money'];

        }

        //获取该用户最后一条记录
        $last_info = $this->task->t_agent_group_member_result->get_last_info($id);
        //获取当前时间
        $time_now = date('Y-m',$month_first_day);
        $last_time = date('Y-m',$last_info['create_time']);
        if($time_now == $last_time){
            //更新信息
            $this->task->t_agent_group_member_result->field_update_list($last_info['id'],[
                "cycle_student_count" => $cycle_student_count,
                "cycle_test_lesson_count" => $cycle_test_lesson_count,
                "cycle_order_money " => $cycle_order_money ,
                "cycle_member_count" => $cycle_member_count,
                "cycle_order_count" => $cycle_order_count,
            ]);
        }else{
            $this->task->t_agent_group_member_result->row_insert([
                'agent_id' => $id,
                'create_time' => $month_first_day,
                "cycle_student_count" => $cycle_student_count,
                "cycle_test_lesson_count" => $cycle_test_lesson_count,
                "cycle_order_money " => $cycle_order_money ,
                "cycle_member_count" => $cycle_member_count,
                "cycle_order_count" => $cycle_order_count,

            ]);
        }
    }
    //@desn:获取团长一级推荐人
    public function get_colconel_child_info($colconel_agent_id){
        $where_arr = [
            ['parentid = %u',$colconel_agent_id,'-1']
        ];
        $sql = $this->gen_sql_new(
            "select id,type,create_time from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_yxyx_member_detail($id,$start_time, $end_time,$opt_type,$page_info){

        $where_arr = [
            ['a.id=%u', $id, -1],
            ['na.create_time>=%u', $start_time, -1],
            ['na.create_time<%u', $end_time, -1],
            's.is_test_user=0',
            'na.type in (1,3)',
        ];
        $having = '';
        if( $opt_type === 'no_revisit_count') {//没有拨打过电话
            $where_arr[] = " tq.id is null ";
        } else if ( $opt_type === 'no_phone_count' ) {//未拨通
            $where_arr[] = " tq.id>0 ";
            $having = ' having ok_phone=0';
        } else if ( $opt_type === 'ok_phone_count' ) {//拨通
            $where_arr[] = " tq.id>0 and tq.is_called_phone=1";
        } else if ( $opt_type === 'ok_phone_no_lesson' ) {//拨通没排课
            $where_arr[] = " tq.id>0 and tq.is_called_phone=1 and na.test_lessonid=0";
        } else if ( $opt_type === 'rank_count' ) {//排课
            $where_arr[] = " tq.id>0 and tq.is_called_phone=1 and na.test_lessonid>0";
        } else if ( $opt_type === 'del_lesson_count' ) {//排课取消
            $where_arr[] = " tq.id>0 and tq.is_called_phone=1 and na.test_lessonid>0 and l.lesson_del_flag=1";
        } else if ( $opt_type === 'ok_lesson_count' ) {//试听成功
            $where_arr[] = " na.test_lessonid>0 and l.lesson_del_flag=0 and l.lesson_user_online_status=1";
        } else if ( $opt_type === 'ok_lesson_no_order' ) {//试听未签单
            $where_arr[] = " na.test_lessonid>0 and l.lesson_del_flag=0 and l.lesson_user_online_status=1 and ao.orderid is null";
        } else if ( $opt_type === 'order_user_count' ) {//签单
            $where_arr[] = " na.test_lessonid>0 and l.lesson_del_flag=0 and l.lesson_user_online_status=1 and ao.orderid>0 ";
        }

        $tq_arr = [
            ['tq.start_time>=%u', $start_time, -1],
            ['tq.start_time<%u', $end_time, -1],
        ];


        $sql = $this->gen_sql_new(
            "select na.id,a.phone phone1,a.nickname nick1,s.nick,s.phone,s.grade,s.userid,ss.admin_revisiterid,"
            ." group_concat( distinct if(b.sys_operator!='system',b.sys_operator,'') ) sys_operator,na.add_reason,"
            ." tl.test_lesson_subject_id,na.test_lessonid,max(r.revisit_time) revisit_time,ss.admin_revisiterid ,"
            ." count(distinct if(tq.is_called_phone=1,tq.id,0) ) phone_count,stu_request_test_lesson_demand,ss.user_desc,"
            ." sum( if(tq.id>0 and tq.is_called_phone=0,1,0) ) no_tq,l.lesson_start,na.create_time,"
            ." sum( if(tq.id>0 and tq.is_called_phone=1,1,0) ) ok_phone,"
            ." ss.last_revisit_time,ss.add_time,tl.subject,tr.test_lesson_order_fail_flag,tr.test_lesson_order_fail_desc "
            ." from %s na "
            ." left join %s a on a.id=na.parentid"
            ." left join %s s on s.userid=na.userid"
            ." left join %s tq on tq.phone=na.phone and %s"
            ." left join %s l on l.lessonid=na.test_lessonid "
            ." left join %s tl on tl.userid=na.userid "
            ." left join %s tr on tr.test_lesson_subject_id=tl.test_lesson_subject_id "
            ." left join %s r on r.userid=na.userid "
            ." left join %s ao on ao.aid=na.id "
            ." left join %s ss on ss.userid=na.userid "
            ." left join %s b on b.phone=ss.phone "
            ." where %s "
            ." group by na.userid %s"
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_tq_call_info::DB_TABLE_NAME
            ,$tq_arr
            ,t_lesson_info::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_revisit_info::DB_TABLE_NAME
            ,t_agent_order::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_book_revisit::DB_TABLE_NAME
            ,$where_arr
            ,$having
        );

        return $this->main_get_list_by_page($sql,$page_info,10, true);

    }
   //@desn:获取用户不同身份推荐人个数
    //@param:$parentid 推荐人id
    public function get_invite_sort_num($parentid){
        $sql = $this->gen_sql_new(
            "select sum(if(type=1,1,0)) as child_student_count,".
            "sum(if(type=2,1,0)) as child_member_count,".
            "sum(if(type=3,1,0)) as child_student_member_count ".
            "from %s where parentid = %u ",self::DB_TABLE_NAME,$parentid
        );
        return $this->main_get_row($sql);
    }
    //@desn:根据用户昵称获取用户信息
    //@param:$nickname 用户昵称
    public function get_agent_info_by_nickname($nickname){
        $sql = $this->gen_sql_new(
            "select * from %s where nickname like '%%%s%%'",self::DB_TABLE_NAME,$nickname
        );
        return $this->main_get_row($sql);
    }
    //@desn:获取用户每日邀请用户列表
    //@param: $agent_id 邀请人id
    //@param:$start_time 开始时间
    //@param:$end_time  结束时间
    public function get_today_invite_list($agent_id,$start_time,$end_time){
        $where_arr = [
            ['parentid = %u',$agent_id,-1],
        ];
        $this->where_arr_add_time_range($where_arr,'create_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            'select id,type from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:自增函数
    //@param:$agent_id 优学优享id
    //@param:$this_field 自增的字段
    //@param:$this_num 自增的数值
    public function since_the_add($agent_id,$this_field,$this_num){
        $sql = sprintf(
            "update %s set  %s = %s + $this_num  where  id = %u",
            self::DB_TABLE_NAME,
            $this_field,
            $this_field,
            $agent_id
        );
        $ret = $this->main_update($sql);
        return $ret;
    }
    //@desn:根据邀请人获取被邀请人试听情况
    public function get_child_test_lesson_info_by_parentid($agent_id){
        $where_arr = [
            "a.test_lessonid <> ''",
            ['a.parentid = %u',$agent_id,-1]
        ];
        $sql = $this->gen_sql_new(
            'select l.lesson_user_online_status from %s a '.
            "left join  %s l on a.test_lessonid =l.lessonid  ".
            "where %s",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_parent_adminid_by_parentid($parentid){
        $where_arr = [
            ['a.id = %u', $parentid, -1],
            // 'm.leave_member_time=0',
            'm.del_flag=0',
        ];
        $sql = $this->gen_sql_new("select m.uid from %s a"
                                  ." left join %s m on m.phone=a.phone"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取一些用户的签单数量及签单金额
    //@desn:获取推荐学员签单量、签单金额[无下限限制下级]
    //@param:$in_str child  id串
    //@param:$start_time  每月开始时间
    //@param:$end_time  每月结束时间
    public function get_cycle_child_order_info($in_str,$start_time,$end_time){
        $where_arr = [
            'a.id in '.$instr,
            'oi.order_status in (1,2)',
            'oi.contract_type in (0,3)'
        ];
        $this->where_arr_add_time_range($where_arr,"ao.create_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            'select sum(ao.orderid>0) child_order_count,sum(oi.price) child_order_money '.
            'from %s a '.
            'left join %s oi on a.uesrid = oi.userid '.
            'where %s ',
            self::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    //@desn:获取用户试听开始时间
    //@param: $to_agentid 用户优学优享id
    public function get_test_lesson_bad_time($to_agentid){
        $sql = $this->gen_sql_new(
            'select li.lesson_start from %s a '.
            'left join %s li on a.test_lessonid = li.lessonid '.
            'where a.id = %u',
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $to_agentid
        );
        return $this->main_get_value($sql);
    }

    //@desn:获取用户可提现的一级试听奖励 [不包括用户已体现金额]
    //@param:$agent_id 优学优享id
    //@param:$last_succ_cash_time 用户上次提现时间
    public function get_now_l1_all_open_money($agent_id,$last_succ_cash_time){
        $where_arr = [
            ['agent_id = %u',$agent_id],
            ['create_time >= %u',$last_succ_cash_time],
            'agent_income_type' => 1
        ];
        $sql = $this->gen_sql_new(
            'select sum(agent_status_money) '.
            'from %s '.
            'where id in (select child_agent_id from %s where %s)',
            self::DB_TABLE_NAME,
            t_agent_income_log::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户可提现的二级试听奖励 [不包括用户已体现金额]
    //@param:$agent_id 优学优享id
    //@param:$last_succ_cash_time 用户上次提现时间
    public function get_now_l2_all_open_money($agent_id,$last_succ_cash_time){
        $where_arr = [
            ['agent_id = %u',$agent_id],
            ['create_time >= %u',$last_succ_cash_time],
            'agent_income_type' => 2
        ];
        $sql = $this->gen_sql_new(
            'select sum(pp_agent_status_money) '.
            'from %s '.
            'where id in (select child_agent_id from %s where %s)',
            self::DB_TABLE_NAME,
            t_agent_income_log::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取用户新增转盘记录
    //@param:$id 用户优学优享id
    public function get_daily_lottery_id_str($id,$daily_lottery_money){
        $id_str = '';
        if($daily_lottery_money > 0){
            $last_daily_lottery_time = $this->task->t_agent_income_log->get_last_daily_lottery_time($id);
            //获取本次新增转盘记录id
            $daily_lottery_id_arr = $this->task->t_agent_daily_lottery->get_daily_lottery_id_arr($id,$last_daily_lottery_time);
            if($daily_lottery_id_arr){
                // print_r($daily_lottery_id_arr);
                foreach($daily_lottery_id_arr as $dval){
                    $id_arr[] = $dval['lid'];
                }
                // print_r($id_arr);
                $id_str = join(',',$id_arr);
            }
        }
        return $id_str;
    }

    public function get_id_by_wx_openid($wx_openid){
        $where_arr = [];
        $this->where_arr_add_str_field($where_arr,'wx_openid',$wx_openid);
        $sql = $this->gen_sql_new(
            'select id '.
            'from %s '.
            'where %s limit 1',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
}
