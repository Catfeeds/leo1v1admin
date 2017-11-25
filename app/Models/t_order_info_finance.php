<?php
namespace App\Models;
use \App\Enums as E;
class t_order_info_finance extends \App\Models\Zgen\z_t_order_info_finance
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_order_info($start_time,$end_time,$contract_type){
        $where_arr=[
            ["o.order_time>=%u",$start_time,0],  
            ["o.order_time<%u",$end_time,0],
            ["o.contract_type=%u",$contract_type,-1],
            "s.is_test_user=0",
            "o.contract_status>0",
            " o.check_money_flag =1"
        ];
        $sql = $this->gen_sql_new("select o.*  "
                                  ." from %s o left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
        

    }


    public function get_order_tongji_info($start_time,$end_time,$contract_type){
        $where_arr=[
            ["o.order_time>=%u",$start_time,0],  
            ["o.order_time<%u",$end_time,0],
            ["o.contract_type=%u",$contract_type,-1],
            "s.is_test_user=0",
            "o.contract_status>0",
            " o.check_money_flag =1"
        ];
        $sql = $this->gen_sql_new("select count(distinct o.userid) num,sum(o.price) money  "
                                  ." from %s o left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
        

    }

    public function get_order_list(
        $page_num,$start_time,$end_time,$contract_type,$contract_status
        ,$userid,$config_courseid,$is_test_user,$show_yueyue_flag,$has_money
        ,$check_money_flag=-1,$assistantid=-1,$origin="",$stu_from_type=-1,$sys_operator=""
        ,$account_role=-1,$grade=-1,$subject=-1,$tmk_adminid=-1, $need_receipt=-1
        ,$teacherid=-1,$up_master_adminid=-1,$account_id=74,$require_adminid_list=[],$origin_userid=-1,
        $opt_date_str="order_time" , $order_by_str= " t2.assistantid asc , order_time desc",$have_init=-1,
        $have_master=-1,$sys_operator_uid=-1,$can_period_flag=-1
    ){
        $where_arr=[];
        if($userid>=0){
            $where_arr=[["t1.userid=%u",$userid,-1]];
        }elseif( $config_courseid>0 ){
            $where_arr=[["config_courseid=%u",$config_courseid,-1]];
        }else{
            $where_arr=[
                ["is_test_user=%u" , $is_test_user, -1],
                ["check_money_flag=%u" , $check_money_flag, -1],
                ["stu_from_type=%u" , $stu_from_type, -1],
                ["t1.grade=%u" , $grade, -1],
                ["t1.subject=%u" , $subject, -1],
                ["need_receipt=%u" , $need_receipt, -1],
                // ["t1.sys_operator like '%%%s%%'" , $sys_operator, ""],
                ["l.teacherid=%u" , $teacherid, -1],
                ["can_period_flag=%u" ,$can_period_flag, -1],
            ];
            if ($sys_operator) {
                $sys_operator=$this->ensql($sys_operator);
                $where_arr[]="(t1.sys_operator like '%%".$sys_operator."%%' or t3.name like '%%".$sys_operator."%%')";
            }

            $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);

            $this->where_arr_add__2_setid_field($where_arr,"t2.assistantid",$assistantid);

            if ($contract_type==-2) {
                $where_arr[]="contract_type in(0,1,3)" ;
                //$where_arr[]="contract_type in(0,3)" ;
            }else if ( $contract_type==-3){
                $where_arr[]="contract_type in(0,3)" ;
            }else {
                $where_arr[]=["contract_type=%u" , $contract_type, -1];
            }

            if ($contract_status ==-2) {
                $where_arr[] = "contract_status <> 0";
            }else{
                $this->where_arr_add_int_or_idlist($where_arr,"contract_status",$contract_status);
            }

            $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
            $this->where_arr_add__2_setid_field($where_arr,"origin_userid", $origin_userid);

            if ($has_money ==0) {
                $where_arr[]="price=0" ;
            }else if ($has_money ==1) {
                $where_arr[]="price>0" ;
            }
            $where_arr[]=$this->where_get_in_str("m2.uid", $require_adminid_list );
            if($have_init==0){
                $where_arr[] = "(t2.init_info_pdf_url='' or t2.init_info_pdf_url is null)";
            }elseif($have_init==1){
                $where_arr[] = "t2.init_info_pdf_url <> ''";
            }

            if($have_master==0){
                $where_arr[] = "(t2.ass_master_adminid=0 or t2.init_info_pdf_url is null)";
            }elseif($have_master==1){
                $where_arr[] = "t2.ass_master_adminid>0";
            }


        }
        if($up_master_adminid != -1){
            // $where_arr[]="t2.ass_master_adminid=".$account_id;
            $where_arr[]="if(nn.master_adminid>0,nn.master_adminid=".$account_id.",t2.ass_master_adminid=".$account_id.")";
        }

        if (!$show_yueyue_flag) {
            $where_arr[]="t1.sys_operator <>'yueyue' ";
        }

        if ($origin) {
            $where_arr[]= [ "t2.origin like '%%%s%%'" , $this->ensql($origin) ];
        }

        //http:7u2f5q.com2.z0.glb.qiniucdn.com/8008e863f1b6151890ccf278f711ab691460108026469.png
        //select count(*) from t_order_info as t1,t_book_info as t2 where t1.userid=t2.userid, t2.origin like "%APP课程包%" ;
        $where_arr[] = ["t3.account_role = %u" , $account_role, -1];

        if($sys_operator_uid>0){
            $where_arr=[
                "t3.uid=".$sys_operator_uid
            ];
        }
        $sql = $this->gen_sql_new(
            "select order_price_desc,from_parent_order_type,t2.lesson_count_all,t1.userid,get_packge_time,order_stamp_flag,"
            ." f.flowid,f.flow_status,f.post_msg as flow_post_msg,l.teacherid,tmk_adminid,t2.user_agent,"
            ." t1.orderid,order_time,t1.stu_from_type, is_new_stu,contractid,"
            ." contract_type,contract_status,invoice,is_invoice,t1.channel, "
            ." contract_starttime,taobao_orderid, t1.default_lesson_count, "
            ." contract_endtime,t1.grade,t1.lesson_total,t1.price,t1.discount_price,t1.discount_reason,"
            ." t2.phone_location,t1.userid,t1.competition_flag,t1.lesson_left ,"
            ." t2.address,t2.origin_userid,ti.except_lesson_count,ti.week_lesson_num,"
            ." t2.realname as stu_nick,t2.ass_assign_time, t1.subject, t2.nick as stu_self_nick, "
            ." pp.nick as parent_nick,t2.phone,t1.origin,t1.sys_operator,t1.from_type,"
            ." t1.config_lesson_account_id ,t1.config_courseid,  check_money_flag,check_money_time,"
            ." check_money_adminid,check_money_desc,t2.assistantid,t2.init_info_pdf_url,t1.title,"
            ." need_receipt, order_promotion_type, promotion_discount_price, promotion_present_lesson, "
            ." promotion_spec_discount, promotion_spec_present_lesson ,lesson_start,"
            ." t2.ass_master_adminid,m.account master_nick,t2.master_assign_time, pdf_url, "
            ." t1.pre_from_orderno ,t1.from_orderno,t1.pre_pay_time,t1.pre_price,t3.name order_set_name,n.hand_get_adminid,"
            ." t1.can_period_flag "
            // ." ,if(co.child_order_type = 2, 1, 0) is_staged_flag "
            ." from %s t1 "
            ." left join %s t2 on t1.userid = t2.userid "
            ." left join %s t3 on t1.sys_operator = t3.account "
            ." left join %s c on t1.orderid = c.orderid "
            ." left join %s n on t1.userid = n.userid "
            ." left join %s l on l.lessonid = t1.from_test_lesson_id "
            ." left join %s f on ( f.from_key_int = t1.orderid  and f.flow_type=2002)"
            ." left join %s m on t2.ass_master_adminid = m.uid"
            ." left join %s m2 on t1.sys_operator = m2.account"
            ." left join %s ti on t1.userid = ti.userid"
            ." left join %s pp on t2.parentid= pp.parentid"
            ." left join %s a on t2.assistantid = a.assistantid"
            ." left join %s mm on a.phone = mm.phone"
            ." left join %s u on u.adminid = mm.uid"
            ." left join %s nn on u.groupid = nn.groupid"
            ." where %s order by $order_by_str ",
            // ." left join %s co on (co.parent_orderid = t1.orderid and co.child_order_type = 2)"
            // ." where %s group by t1.orderid order by $order_by_str ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_init_info::DB_TABLE_NAME,
            t_parent_info::DB_TABLE_NAME,
            t_assistant_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_admin_group_user::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            // t_child_order_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }



}











