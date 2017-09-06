<?php
namespace App\Models;
use \App\Enums as E;
class t_order_refund extends \App\Models\Zgen\z_t_order_refund
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_order_refund_list($page_num,$opt_date_str,$refund_type,$userid,$start_time,$end_time,$is_test_user,$refund_userid,$require_adminid_list=[]){
        $where_arr = [
            ["refund_status=%u",$refund_type,-1],
            ["r.userid=%u",$userid,-1],
            ["(s.is_test_user=%u or s.is_test_user is null )",$is_test_user,-1],
        ];
        $this->where_arr_add_int_field($where_arr,"refund_userid",$refund_userid);
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);

        $this->where_arr_adminid_in_list($where_arr,"refund_userid", $require_adminid_list );
        $sql = $this->gen_sql_new(
            " select  r.qc_contact_status, r.qc_advances_status, r.qc_voluntarily_status, r.userid,s.phone, o.discount_price,r.orderid,o.contract_type,r.lesson_total, f.flow_status,"
            ." f.flow_status_time,f.flowid,r.should_refund,r.price,o.invoice,o.order_time,o.sys_operator,r.pay_account, "
            ." r.real_refund,r.refund_status,r.apply_time,r.refund_userid,o.contractid,r.save_info,r.refund_info,file_url, "
            ." o.grade,o.need_receipt "
            ." from %s r"
            ." left join %s s on s.userid=r.userid"
            ." left join %s o on o.orderid=r.orderid"
            ." left join %s f on (f.flow_type=%u and r.orderid=f.from_key_int and r.apply_time = f.from_key2_int) "
            ." where %s"
            ." order by $opt_date_str desc"
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,t_flow::DB_TABLE_NAME
            ,E\Eflow_type::V_ASS_ORDER_REFUND
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_ass_refund_info($start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,"r.apply_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct r.userid) num,m.uid,m.account,sum(if(o.contract_type=0,r.real_refund,0)) new_price,sum(if(o.contract_type=3,r.real_refund,0)) renw_price,sum(r.real_refund) refund_money "
                                  ." from %s r  left join %s o on r.orderid = o.orderid"
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_ass_refund_detail_info($start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,"r.apply_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select r.real_refund,s.nick,s.grade,o.lesson_total,o.default_lesson_count,s.lesson_count_left,r.refund_userid  "
                                  ." from %s r left join %s o on r.orderid = o.orderid"
                                  ." left join %s s on r.userid = s.userid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_ass_refund_info_new($start_time,$end_time){
        $where_arr = [
            "ra.id is not null",
            "m.uid >0"
        ];
        $this->where_arr_add_time_range($where_arr,"ra.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select  r.real_refund,ra.id,m.uid,occ.value,ra.score,r.orderid,r.apply_time "
                                  ." from %s r "
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s ra on (ra.orderid = r.orderid and ra.apply_time = r.apply_time)"
                                  ." left join %s oc on ra.configid = oc.id"
                                  ." left join %s occ on (oc.key1= occ.key1 and occ.key2= 0 and occ.key3= 0 and occ.key4= 0 )"
                                  ." where %s order by r.orderid,r.apply_time",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_refund_analysis::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_refund_info_new($start_time,$end_time,$tea_arr){
        $where_arr = [
            "ra.id is not null",
            "t.teacherid >0"
        ];
        $this->where_arr_add_time_range($where_arr,"r.apply_time",$start_time,$end_time);
        $this->where_arr_teacherid($where_arr,"t.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select distinct r.real_refund,ra.id,t.teacherid,occ.value,ra.score,r.orderid,r.apply_time,s.nick "
                                  ." from %s r "
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s o on r.orderid = o.orderid"
                                  ." left join %s c on c.userid = s.userid"
                                  ." left join %s t on (c.teacherid = t.teacherid and (o.subject = t.subject or o.subject = t.second_subject or o.subject = t.third_subject))"
                                  ." left join %s ra on (ra.orderid = r.orderid and ra.apply_time = r.apply_time)"
                                  ." left join %s oc on ra.configid = oc.id"
                                  ." left join %s occ on (oc.key1= occ.key1 and occ.key2= 0 and occ.key3= 0 and occ.key4= 0 )"
                                  ." where %s order by r.orderid,r.apply_time",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_refund_analysis::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }



    public function get_ass_refund_list($start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,"r.apply_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select m.uid,m.account,r.real_refund "
                                  ." from %s r  left join %s o on r.orderid = o.orderid"
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_user_lesson_refund($userid,$competition_flag){
        $sql = $this->gen_sql_new("select sum(r.should_refund)/100"
                                  ." from %s r"
                                  ." left join %s o on r.orderid=o.orderid"
                                  ." where o.competition_flag=%u"
                                  ." and r.userid=%u"
                                  //." and r.refund_status=1"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$competition_flag
                                  ,$userid
        );
        return $this->main_get_value($sql);
    }

    public function get_order_refund_all($studentid){
        $sql=$this->gen_sql_new("select sum(should_refund) "
                                ." from %s "
                                ." where userid=%u"
                                ,self::DB_TABLE_NAME
                                ,$studentid
        );
        return $this->main_get_value($sql);
    }

    public function update_refund_list ($orderid, $apply_time, $qc_other_reason, $qc_analysia, $qc_reply, $qc_contact_status, $qc_advances_status, $qc_voluntarily_status) {
        $where_arr = [
            "orderid"      => $orderid,
            "apply_time"   => $apply_time,
        ];

        $sql = $this->gen_sql_new("update %s set
                                   qc_other_reason = '%s' ,
                                   qc_analysia     = '%s' ,
                                   qc_reply        = '%s',
                                   qc_contact_status  = %d,
                                   qc_advances_status = %d,
                                   qc_voluntarily_status = %d
                                   where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$qc_other_reason
                                  ,$qc_analysia
                                  ,$qc_reply
                                  ,$qc_contact_status
                                  ,$qc_advances_status
                                  ,$qc_voluntarily_status
                                  ,$where_arr
        );

        return $this->main_update($sql);

    }

    public function get_qc_anaysis_by_orderid_apply ($orderid, $apply_time) {
        $where_arr = [
            "orderid"      => $orderid,
            "apply_time"   => $apply_time,
        ];
        $sql=$this->gen_sql_new("select qc_other_reason, qc_analysia, qc_reply, qc_contact_status, qc_advances_status, qc_voluntarily_status "
                                ." from %s "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_refunded_info($start_time,$end_time,$flow_status){

        $where_arr[]=["f.flow_status=%d",$flow_status];

        $this->where_arr_add_time_range($where_arr,"f.flow_status_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select count(distinct r.userid) num,m.uid,r.orderid "
                                  ." from %s r "
                                  ." left join %s s on r.userid = s.userid "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." left join %s m on a.phone = m.phone "
                                  ." left join %s f on (r.orderid=f.from_key_int and r.apply_time = f.from_key2_int) "
                                  ." where %s"
                                  ." group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_flow::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });

    }


    public function get_refund_analysis($apply_time, $orderid){
        $where_arr = [
            ["apply_time=%d",$apply_time],
            ["orderid=%d",$orderid]
        ];

        $sql = $this->gen_sql_new(" select qc_other_reason, qc_analysia, qc_reply ".
                                  " from %s  where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_end_class_stu_order(){
        $where_arr=[
            "r.refund_status =1",
            "o.contract_type in (0,3)",
            //  "o.contract_status =3",
            "so.lesson_left >0"
        ];
        $sql = $this->gen_sql_new("select r.userid,r.orderid,so.orderid so_orderid,so.lesson_left,so.contract_type "
                                  ." from %s r left join %s o on r.orderid=o.orderid"
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s so on so.parent_order_id = o.orderid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_rufund_info_by_userid($arr){
        $where_arr=[
            "should_refund>0"
        ];

        $this->where_arr_adminid_in_list($where_arr,"userid", $arr);
        $sql = $this->gen_sql_new("select should_refund,userid from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }


    /**

           public function get_ass_refund_info_new($start_time,$end_time){
        $where_arr = [
            "ra.id is not null",
            "m.uid >0"
        ];
        $this->where_arr_add_time_range($where_arr,"ra.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select  r.real_refund,ra.id,m.uid,occ.value,ra.score,r.orderid,r.apply_time "
                                  ." from %s r "
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s ra on (ra.orderid = r.orderid and ra.apply_time = r.apply_time)"
                                  ." left join %s oc on ra.configid = oc.id"
                                  ." left join %s occ on (oc.key1= occ.key1 and occ.key2= 0 and occ.key3= 0 and occ.key4= 0 )"
                                  ." where %s order by r.orderid,r.apply_time",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_refund_analysis::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_refund_info_new($start_time,$end_time,$tea_arr){
        $where_arr = [
            "ra.id is not null",
            "t.teacherid >0"
        ];
        $this->where_arr_add_time_range($where_arr,"r.apply_time",$start_time,$end_time);
        $this->where_arr_teacherid($where_arr,"t.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select distinct r.real_refund,ra.id,t.teacherid,occ.value,ra.score,r.orderid,r.apply_time,s.nick "
                                  ." from %s r "
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s o on r.orderid = o.orderid"
                                  ." left join %s c on c.userid = s.userid"
                                  ." left join %s t on (c.teacherid = t.teacherid and (o.subject = t.subject or o.subject = t.second_subject or o.subject = t.third_subject))"
                                  ." left join %s ra on (ra.orderid = r.orderid and ra.apply_time = r.apply_time)"
                                  ." left join %s oc on ra.configid = oc.id"
                                  ." left join %s occ on (oc.key1= occ.key1 and occ.key2= 0 and occ.key3= 0 and occ.key4= 0 )"
                                  ." where %s order by r.orderid,r.apply_time",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_refund_analysis::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


     **/



    public function get_ass_refund_info_by_qc($start_time,$end_time){ //
        $where_arr = [
            "ra.id is not null",
            "m.uid >0",
            "occ.value = '助教部'",
            "ra.score>0"
        ];
        $this->where_arr_add_time_range($where_arr,"ra.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select m.uid,occ.value,ra.score,r.orderid,m.account"
                                  ." from %s r "
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s ra on (ra.orderid = r.orderid and ra.apply_time = r.apply_time)"
                                  ." left join %s oc on ra.configid = oc.id"
                                  ." left join %s occ on (oc.key1= occ.key1 and occ.key2= 0 and occ.key3= 0 and occ.key4= 0 )"
                                  ." where %s group by m.uid order by ra.score desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_refund_analysis::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);

    }

    public function get_refund_count_for_ass($start_time,$end_time,$uid){
        $where_arr = [
            "ra.id is not null",
            "m.uid =$uid",
            "occ.value = '助教部'",
            "ra.score>0"
        ];
        $this->where_arr_add_time_range($where_arr,"ra.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select  ra.orderid, ra.apply_time, s.nick as stu_nick, m.account"
                                  ." from %s r "
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s ra on (ra.orderid = r.orderid and ra.apply_time = r.apply_time)"
                                  ." left join %s oc on ra.configid = oc.id"
                                  ." left join %s occ on (oc.key1= occ.key1 and occ.key2= 0 and occ.key3= 0 and occ.key4= 0 )"
                                  ." where %s group by m.uid order by ra.score desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_refund_analysis::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);

    }






    public function get_tec_refund_info_by_qc($start_time,$end_time){
        $where_arr = [
            "ra.id is not null",
            "t.teacherid >0",
            "occ.value in ('老师管理','教学部')",
            "ra.score>0"
        ];
        $this->where_arr_add_time_range($where_arr,"r.apply_time",$start_time,$end_time);
        // $this->where_arr_teacherid($where_arr,"t.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select distinct(t.teacherid),occ.value,t.nick "
                                  ." from %s r "
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s o on r.orderid = o.orderid"
                                  ." left join %s c on c.userid = s.userid"
                                  ." left join %s t on (c.teacherid = t.teacherid and (o.subject = t.subject or o.subject = t.second_subject or o.subject = t.third_subject))"
                                  ." left join %s ra on (ra.orderid = r.orderid and ra.apply_time = r.apply_time)"
                                  ." left join %s oc on ra.configid = oc.id"
                                  ." left join %s occ on (oc.key1= occ.key1 and occ.key2= 0 and occ.key3= 0 and occ.key4= 0 )"
                                  ." where %s group by t.teacherid order by r.orderid,r.apply_time",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_refund_analysis::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }



    public function get_refund_count_for_tec($start_time,$end_time,$teacherid){
        $where_arr = [
            "ra.id is not null",
            "t.teacherid = $teacherid",
            "occ.value in ('老师管理','教学部')",
            "ra.score>0"
        ];
        $this->where_arr_add_time_range($where_arr,"r.apply_time",$start_time,$end_time);
        // $this->where_arr_teacherid($where_arr,"t.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select o.orderid, ra.apply_time, ra.add_time, s.nick as stu_nick, t.nick as teac_nick,occ.value"
                                  ." from %s r " 
                                  ." left join %s s on r.userid = s.userid"
                                  ." left join %s o on r.orderid = o.orderid"
                                  ." left join %s c on c.userid = s.userid"
                                  ." left join %s t on (c.teacherid = t.teacherid and (o.subject = t.subject or o.subject = t.second_subject or o.subject = t.third_subject))"
                                  ." left join %s ra on (ra.orderid = r.orderid and ra.apply_time = r.apply_time)"
                                  ." left join %s oc on ra.configid = oc.id"
                                  ." left join %s occ on (oc.key1= occ.key1 and occ.key2= 0 and occ.key3= 0 and occ.key4= 0 )"
                                  ." where %s group by o.orderid ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_refund_analysis::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  t_order_refund_confirm_config::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }





}