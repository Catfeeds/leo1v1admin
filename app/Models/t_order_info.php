<?php
namespace App\Models;
use \App\Models\Zgen as Z;
use \App\Enums as E;

/**
 * @property t_manager_info  $t_manager_info
 * @property t_student_info  $t_student_info
 * @property t_seller_student_new $t_seller_student_new
 * @property t_admin_group_user  $t_admin_group_user
 * @property t_seller_month_money_target $t_seller_month_money_target
 * @property t_admin_group_month_time $t_admin_group_month_time
 * @property t_group_user_month $t_group_user_month
 * @property t_group_name_month $t_group_name_month
 */

class t_order_info extends \App\Models\Zgen\z_t_order_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_order_list_for_origin( $page_num,$start_time, $end_time, $contract_type, $contract_status, $userid,$config_courseid,$is_test_user,$show_yueyue_flag,$has_money,$check_money_flag=-1, $isset_assistantid=-1 )
    {
        $where_arr=[];

        if ($userid>0) {
            $where_arr=[["t1.userid=%u" , $userid, -1]];
        }else if ( $config_courseid >0 ) {
            $where_arr=[["config_courseid=%u" , $config_courseid, -1]];
        }else{

            $where_arr=[
                ["is_test_user=%u" , $is_test_user, -1],
                ["check_money_flag=%u" , $check_money_flag, -1],
            ];

            $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
            if ($isset_assistantid==0) {
                $where_arr[]="c.assistantid =0 " ;
            }else if ($isset_assistantid==1) {
                $where_arr[]="c.assistantid <>0 " ;
            }

            if ($contract_type==-2) { //1v1

                $where_arr[]="contract_type in(0,1,3)" ;
                /*
                  0 => "常规",
                  1 => "赠送",
                  2 => "试听",
                  3 => "续费",
                */

            }else{

                $where_arr[]=["contract_type=%u" , $contract_type, -1];
            }


            if ($contract_status ==-2) {
               $where_arr[] = "contract_status <> 0";
            }else{
               $where_arr[]= ["contract_status=%u" , $contract_status, -1];
            }

            if ($has_money ==0) {
               $where_arr[]="price=0" ;
            }else if ($has_money ==1) {
               $where_arr[]="price>0" ;
            }


        }
        if (!$show_yueyue_flag) {
            $where_arr[]="sys_operator <>'yueyue' ";
        }

        //http:7u2f5q.com2.z0.glb.qiniucdn.com/8008e863f1b6151890ccf278f711ab691460108026469.png
        //select count(*) from t_order_info as t1,t_book_info as t2 where t1.userid=t2.userid, t2.origin like "%APP课程包%" ;



        $sql = $this->gen_sql("select  t1.origin ,sum(price)/100.0 as money_all,count(*) as order_count , COUNT( DISTINCT t1.userid ) as user_count ,sum(case when contract_type=0 then price else  0 end )/100.0  as new_price    from %s t1 left join %s t2 on t1.userid=t2.userid     left join %s c on t1.orderid = c.orderid    "
                              ." where %s and t1.stu_from_type=0 and contract_type in (0,1,3) and  price>100 group by t1.origin   ",
                              self::DB_TABLE_NAME,
                              Z\z_t_student_info::DB_TABLE_NAME,
                              t_course_order::DB_TABLE_NAME,
                              [$this->where_str_gen($where_arr)]
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item["origin"];
        });
    }

    public function get_order_list_for_tongji( $start_time, $end_time,$is_test_user=0)
    {
        $where_arr = [
            ["o.order_time>=%u", $start_time, -1],
            ["o.order_time<=%u", $end_time, -1],
            ["s.is_test_user=%u", $is_test_user, -1],
            "o.contract_type in (0,3)",
            "o.contract_status>0",
            "o.price>0",
        ];
        $sql = $this->gen_sql_new("select o.userid,o.orderid,o.order_time,"
                                  ." o.default_lesson_count*o.lesson_total,"
                                  ." o.contract_type,o.contract_status,o.sys_operator,m.account_role,"
                                  ." a.nick as ass_nick,if(r.orderid>0,1,0) as has_refund,"
                                  ." r.orderid as refund_orderid"
                                  ." from %s o "
                                  ." left join %s s on o.userid = s.userid "
                                  ." left join %s m on o.sys_operator=m.account"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s r on o.orderid=r.orderid"
                                  ." where %s "
                                  ." group by o.orderid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_order_refund::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_order_user_list_by_month( $end_time) {
        $where_arr=[
            ["o.order_time<%u" , $end_time, -1],
            "s.is_test_user=0",
            "o.contract_type=0",
            "o.contract_status>0",
            "o.price>0",
        ];

        $sql = $this->gen_sql_new(
            "select  o.orderid,o.userid,min(o.order_time) as order_time,max( w.start_time ) as start_time ,s.assistantid "
            ." from %s o "
            ." left join %s s on s.userid = o.userid "
            ." left join %s w on w.userid = o.userid "
            ." where %s "
            ." group by o.orderid",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_week_regular_course::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
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

    public function get_order_list_caiwu($page_num,$start_time,$end_time){
        $where_arr=[
            "t2.is_test_user=0",
            "contract_type in(0,3)"
        ];

        $this->where_arr_add_time_range($where_arr,"t1.order_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select  t1.orderid,order_time,t1.stu_from_type, is_new_stu,"
                                  ." contract_type,contract_status, t1.default_lesson_count, "
                                  ." t1.grade,t1.lesson_total,price,t1.userid,t1.competition_flag,t1.lesson_left ,"
                                  ." t2.realname as stu_nick,t2.ass_assign_time, t1.subject, t2.nick as stu_self_nick, "
                                  ." t2.parent_name as parent_nick,t2.phone,t1.origin,t1.sys_operator,t1.from_type,"
                                  ." t1.config_lesson_account_id ,t1.config_courseid,  check_money_flag,check_money_time,"
                                  ." lesson_start,t2.origin_userid,t2.phone,t1.origin  "
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.userid = t2.userid "
                                  ." left join %s l on l.lessonid = t1.from_test_lesson_id "
                                  ." where %s  order by t1.order_time ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }


    public function get_no_ass_stu_info($num,$time){
        $where_arr=[
            "(s.ass_master_adminid =0 or s.ass_master_adminid is null)",
            "(s.assistantid =0 or s.assistantid is null)",
            "s.init_info_pdf_url is not null and s.init_info_pdf_url != '' ",
            "o.contract_type =0",
            "o.check_money_flag =1",
            "(ss.assistantid <=0 or ss.assistantid is null)",
            "o.check_money_time>=".$time
        ];
        $sql = $this->gen_sql_new("select s.userid,o.orderid,s.nick,o.sys_operator "
                                  ." from %s o left join %s s on o.userid = s.userid "
                                  ." left join %s ss on s.origin_userid = ss.userid"
                                  ." where %s limit %u",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $num
        );
        return $this->main_get_row($sql);
    }
    public function get_no_ass_stu_info_list(){
        $where_arr=[
            "(s.ass_master_adminid =0 or s.ass_master_adminid is null)",
            "o.contract_type =0",
            "o.check_money_flag =1",
            "ss.assistantid >0"
        ];
        $sql = $this->gen_sql_new("select s.userid,o.orderid,s.nick,o.sys_operator,ss.assistantid,m.uid "
                                  ." from %s o left join %s s on o.userid = s.userid "
                                  ." left join %s ss on s.origin_userid = ss.userid"
                                  ." left join %s a on a.assistantid = ss.assistantid"
                                  ." left join %s m on a.phone=m.phone"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function count_config_courseid($config_courseid)
    {
        $sql = sprintf("select count(config_courseid) from %s where config_courseid = %u",
                       self::DB_TABLE_NAME,
                       $config_courseid
        );
        return $this->main_get_value($sql);
    }
    public function tongji_get_user_is_new_stu($userid,$start_time ) {
        $sql  = $this->gen_sql("select contract_starttime   ,is_new_stu from %s where userid=%u   ",
                            self::DB_TABLE_NAME,$userid );
        $list = $this->main_get_list($sql);
        $ret  = -1;
        foreach ($list as $item) {
            $is_new_stu=$item["is_new_stu"];
            $contract_starttime=$item["contract_starttime"];
            if ($is_new_stu==1) {
                if ($contract_starttime > $start_time ) {
                    return 1;
                }else{
                    if ($ret==-1) {
                        $ret=1;
                    }
                }
            }else{
                $ret=0;
            }
        }
        return $ret;
    }
    public function tongji_get_money_all($start_time,$end_time)
    {
        $sql=$this->gen_sql("select sum(price) as price_all, sum(lesson_total) as lesson_all from %s where    contract_starttime>=%u and contract_starttime<%u and contract_type =1 ",
                            self::DB_TABLE_NAME,$start_time ,$end_time);
        return $this->main_get_row($sql);
    }
    public function tongji_get_order_user_list($start_time,$end_time)
    {
        $sql=$this->gen_sql("select userid  from %s where    contract_starttime>=%u and contract_starttime<%u and contract_type in(0,3 ) group by userid ",
                            self::DB_TABLE_NAME,$start_time ,$end_time);
        return $this->main_get_list($sql);
    }

    public function tongji_get_suject_list($start_time,$end_time)
    {
        $sql=$this->gen_sql("select userid ,subject from %s "
                            ." where contract_starttime>=%u"
                            ." and contract_starttime<%u"
                            ." and contract_type in (0,3)"
                            ." group by userid,subject "
                            ,self::DB_TABLE_NAME
                            ,$start_time
                            ,$end_time
        );
        $list=$this->main_get_list($sql);
        $ret_list=[];
        foreach( $list as $item) {
            $userid=$item["userid"];
            if(!isset($ret_list[$userid])){
                $ret_list[$userid]=0;
            }
            $ret_list[$userid]++;
        }
        return $ret_list;
    }

    public function get_order_info($userid) {
        $sql=sprintf("select a.orderid as orderid, a.courseid, course_name, course_type,a.teacherid,a.assistantid,"
                     ." contractid ,a.lesson_total*a.default_lesson_count as lesson_count,"
                     ." sum(case when lesson_status=0 then 0 else (case when confirm_flag =2 then 0 else l.lesson_count end)"
                     ." end) as finish_lesson_count,b.order_time, pay_time, b.requirement,b.subject,"
                     ." b.contract_status,b.from_type "
                     ." from %s b "
                     ." left join %s a on a.orderid = b.orderid "
                     ." left join %s l on a.courseid = l.courseid "
                     ." where b.userid= %u "
                     ." and order_status=1"
                     ." and b.contract_type<100 "
                     ." and b.from_type=0 "
                     ." and lesson_del_flag=0 "
                     ." group by a.orderid "
                     ." order by a.orderid desc "
                     ,self::DB_TABLE_NAME
                     ,t_course_order::DB_TABLE_NAME
                     ,t_lesson_info::DB_TABLE_NAME
                     ,$userid
        );
        return $this->main_get_list($sql);
    }
    public function get_new_order_count($userid) {
        $sql=sprintf(" select count(*) from %s  "
                     ." where userid= %u "
                     ." and order_status=1"
                     ." and  contract_type=0"
                     ,self::DB_TABLE_NAME
                     ,$userid);
        return $this->main_get_value($sql);
    }


    public function check_succ_order($userid, $end_time) {
        $sql=$this->gen_sql("select count(*) from  %s "
                            ." where order_time <=%u"
                            ." and contract_type in (0,3)"
                            ." and userid=%u "
                            ." and contract_status <>0 "
                            ,self::DB_TABLE_NAME
                            ,$end_time
                            ,$userid
        );
        return $this->main_get_value($sql) >0;
    }

    public function get_money_all($userid){
        $sql=$this->gen_sql("select sum(price) from  %s "
                            ." where userid=%u "
                            ." and contract_status <>0 "
                            ,self::DB_TABLE_NAME
                            ,$userid
        );
        return $this->main_get_value($sql);
    }

    public function get_first_money($userid) {
        $sql=$this->gen_sql("select price from  %s "
                            ." where userid=%u "
                            ." and contract_status <>0 "
                            ." and price>0 "
                            ." order by order_time asc limit 1"
                            ,self::DB_TABLE_NAME
                            ,$userid
        );
        return $this->main_get_value($sql);
    }

    public function get_contract_type_by_userid($userid,$contractid) {
        $sql=$this->gen_sql("select contract_type from  %s "
                            ." where userid=%u and contractid='%s' "
                            ,self::DB_TABLE_NAME
                            ,$userid
                            ,$contractid
        );
        return $this->main_get_value($sql);
    }

    public function update_only_contract_type($userid,$contractid,$contract_type) {
        $sql=$this->gen_sql("update %s set contract_type = %u ".
                            " where  userid=%u and contractid='%s' ",
                            self::DB_TABLE_NAME,
                            $contract_type,
                            $userid,
                            $contractid
        ) ;
        return $this->main_update($sql);
    }

    public function  get_contract_starttime_info($contractid,$orderid){
        $sql=$this->gen_sql("select contract_starttime from  %s ".
                            " where contractid=%u and orderid=%u ",
                            self::DB_TABLE_NAME,
                            $contractid,
                            $orderid
        ) ;
        return $this->main_get_value($sql);
    }

    public function  set_contract_sim_starttime($contractid,$orderid,$contract_starttime,$contract_endtime_s2){
        $sql=$this->gen_sql("update %s set contract_starttime = '%s',contract_endtime = '%s' ".
                            " where  orderid=%u and contractid=%u ",
                            self::DB_TABLE_NAME,
                            $contract_starttime,
                            $contract_endtime_s2,
                            $orderid,
                            $contractid
         ) ;

        return $this->main_update($sql);

    }


    public function get_user_lesson_count($start){
        $sql=$this->gen_sql("select o.userid,sum(lesson_total*default_lesson_count/100) as lesson_count "
                            ." from %s o,%s s"
                            ." where o.userid>0"
                            ." and is_test_user=0"
                            ." and s.userid=o.userid"
                            ." and contract_starttime<%u"
                            ." and contract_status>0"
                            ." and contract_type in (0,1,3)"
                            ." group by o.userid"
                            ,self::DB_TABLE_NAME
                            ,t_student_info::DB_TABLE_NAME
                            ,$start
        );
        return $this->main_get_list($sql,function($item){
            return $item['userid'];
        });
    }

    public function get_order_count($userid,$start_time=0,$end_time=0,$contract_type="0,1,3",$pay_order=-1){
        $where_arr = [
            ["userid=%u",$userid,0],
            ["order_time>%u",$start_time,0],
            ["order_time<%u",$end_time,0],
            ["contract_type in (%s)",$contract_type,""],
        ];
        if($pay_order>0){
            $where_arr[] = " contract_status>0 ";
        }

        $sql = $this->gen_sql_new("select count(orderid) "
                                  ." from %s"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function add_new_order_seller_new_user_count($sys_operator,$start_time,$end_time){
        $min_start_time=strtotime("2016-12-01");
        if ($start_time<$min_start_time) {
            $start_time=$min_start_time;
        }
        $where_arr=[
            "contract_type=0" ,
            [ "sys_operator='%s'",  $sys_operator, "XXXX" ],
            "contract_status>0" ,
            "seller_get_new_user_count<20" ,
        ];
        $this->where_arr_add_time_range($where_arr,"check_money_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(" update  %s set seller_get_new_user_count= seller_get_new_user_count+1 "
                            ." where %s limit 1"
                            ,self::DB_TABLE_NAME
                            ,$where_arr);
        return $this->main_update($sql);
    }


    public function get_new_order_seller_new_user_count($sys_operator,$start_time,$end_time){
        $min_start_time=strtotime("2016-12-01");
        if ($start_time<$min_start_time) {
            $start_time=$min_start_time;
        }

        $where_arr=[
            "contract_type=0" ,
            [ "sys_operator='%s'",  $sys_operator, "XXXX" ],
            "contract_status>0" ,
        ];
        $this->where_arr_add_time_range($where_arr,"check_money_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select count(orderid)*20-sum(seller_get_new_user_count) "
                            ." from %s"
                            ." where %s "
                            ,self::DB_TABLE_NAME
                            ,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_total_order_info($start_time, $end_time, $require_adminid_list , $contract_type )
    {

        $where_arr=[
            ["contract_type=%u ",$contract_type, -1 ],
            ["contract_status=%u", E\Econtract_status::V_1 ],
        ];
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $where_arr[]= $this->where_get_in_str("m.uid",$require_adminid_list );
        $sql= $this->gen_sql_new(
            " select sum(price )/100 as all_price  , count(*)  as all_count  "
            ." from %s o   "
            ." join %s m on o.sys_operator=m.account "
            ."where %s",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_row($sql);
    }
    public function  get_admin_list($start_time ,$end_time , $account_role=-1)  {
        $where_arr=[
            ["account_role=%u", $account_role, -1],
            "is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            [
                "select distinct m.uid  as adminid from %s o ",
                " join %s m on m.account=o.sys_operator ",
                " join %s s on s.userid=o.userid",
                "where %s"
            ],
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }

    public function  get_admin_list_new($start_time ,$end_time , $account_role=-1,$user_info=-1)  {
        $where_arr=[
            "o.contract_type =0",
            "o.contract_status in (1,2)",
            ["m.account_role=%u", $account_role, -1],
            "s.is_test_user = 0",
            "m.del_flag = 0",
        ];
        if ($user_info >0 ) {
            if  ($user_info < 10000) {
                $where_arr[]=[  "m.uid=%u", $user_info, "" ] ;
            }else{
                $where_arr[]=[  "m.phone like '%%%s%%'", $user_info, "" ] ;
            }
        }else{
            if ($user_info!=""){
                $where_arr[]=array( "(m.account like '%%%s%%' or  m.name like '%%%s%%')",
                                    array(
                                        $this->ensql($user_info),
                                        $this->ensql($user_info)));
            }
        }
        $this->where_arr_add_time_range($where_arr,"o.order_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            " select o.orderid,o.price,o.order_time,o.sys_operator, "
            ." m.uid "
            ." from %s o "
            ." left join %s m on m.account=o.sys_operator "
            ." left join %s s on o.userid = s.userid "
            ." where %s ",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function  get_seller_price($start_time ,$end_time,$adminid=-1)  {
        $where_arr=[
            "o.contract_type =0",
            "o.contract_status in (1,2)",
            ["m.account_role=%u", E\Eaccount_role::V_2, -1],
            "s.is_test_user = 0",
            "m.del_flag = 0",
            ["m.uid=%u",$adminid, -1],
        ];
        $this->where_arr_add_time_range($where_arr,"o.order_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            " select sum(o.price) price "
            ." from %s o "
            ." left join %s m on m.account=o.sys_operator "
            ." left join %s s on o.userid = s.userid "
            ." where %s ",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    /*
     *@modify:Abner<abner@leo.edu.com>
     *@param:$opt_date_str  按类型统计 [order_time:添加时间check_money_time:财务确认时间]
     */
    public function get_1v1_order_list(
        $start_time,$end_time ,$sys_operator="",$stu_from_type=-1,$adminid_list=[],
        $adminid_all=[],$contract_type=-1,$grade_list=[-1], $stu_test_paper_flag=-1,$opt_date_str=""){
        if($opt_date_str==""){
            $opt_date_str = "order_time";
        }
        $where_arr = [
            ["$opt_date_str>=%u" , $start_time, -1],
            ["$opt_date_str<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            ["sys_operator='%s'" , $sys_operator, ''],
            "contract_type in(0,1,3)",
            ["stu_from_type=%u" , $stu_from_type, -1],
            ["contract_type=%u" , $contract_type, -1],
        ];

        $where_arr[]= $this->where_get_in_str_query("s.grade",$grade_list);
        $this->where_arr_add_boolean_for_str_value($where_arr,"stu_test_paper",$stu_test_paper_flag);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_all);

        $sql = $this->gen_sql_new(
            "select  $opt_date_str as opt_date,price,o.userid,stu_from_type ,contract_type "
            ." from %s o "
            ." left join %s s on o.userid = s.userid "
            ." left join %s m on o.sys_operator = m.account "
            ." left join %s tss on o.from_test_lesson_id = tss.lessonid"
            ." left join %s tr on tr.require_id= tss.require_id"
            ." left join %s t on tr.test_lesson_subject_id= t.test_lesson_subject_id"
            ." where %s and  contract_status <> 0 order by $opt_date_str asc",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_1v1_order_list_by_adminid( $start_time,$end_time ,$stu_from_type=-1,$adminid=-1,$adminid_list=[]) {
        $where_arr = [
            ["order_time>=%u" , $start_time, -1],
            ["order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            "contract_type in(0)",
            ["stu_from_type=%u" , $stu_from_type, -1],
            // ["t2.uid=%u" ,$adminid,-1],
        ];
        if(count($adminid_list)>0){
            $this->where_arr_add_int_or_idlist($where_arr,'t2.uid',$adminid_list);
        }else{
            $this->where_arr_add_int_field($where_arr,'t2.uid',$adminid);
        }
        $sql = $this->gen_sql_new("select t2.uid adminid,count(*) all_new_contract,sum(price) all_price,MAX(price) max_price,"
                                  ."t2.create_time,t2.become_member_time,t2.leave_member_time,t2.del_flag "
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.sys_operator = t2.account "
                                  ." left join %s t3 on t1.userid = t3.userid "
                                  ." where %s and  contract_status <> 0 group by t2.uid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  Z\z_t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['adminid'];
        });
    }

    public function get_1v1_order_list_by_adminid_green( $start_time,$end_time ,$stu_from_type=-1) {

        $where_arr = [
            ["order_time>=%u" , $start_time, -1],
            ["order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            "contract_type in(0)",
            ["stu_from_type=%u" , $stu_from_type, -1],
            "green_channel_teacherid>0"
        ];
        $sql = $this->gen_sql_new("select t2.uid adminid,count(*) all_green_contract "
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.sys_operator = t2.account "
                                  ." left join %s t3 on t1.userid = t3.userid "
                                  ." left join %s tr on t1.from_test_lesson_id =tr.current_lessonid "
                                  ." where %s and  contract_status <> 0 group by t2.uid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  Z\z_t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['adminid'];
        });
    }


    public function get_1v1_order_seller_list_group_self( $start_time,$end_time, $groupid) {
        $where_arr = [
            ["order_time>=%u" , $start_time, -1],
            ["order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            "contract_type in(0)",
            "contract_type=0",
            "sys_operator<>'jim'",
            "gu.groupid = $groupid",
        ];

        $sql = $this->gen_sql_new("select sys_operator, sum(price) as all_price,count(*)as all_count  "
                                  ." from %s o , %s s , %s m,  %s gu     "

                                  ." where ".
                                  " o.userid = s.userid   and   ".
                                  " o.sys_operator =m.account   and   ".
                                  " m.uid=gu.adminid  and     ".

                                  " %s and  contract_status <> 0   group by sys_operator order by all_price desc  limit 10 ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,


                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_1v1_order_seller( $account ,$start_time,$end_time ) {

        $where_arr = [
            ["order_time>=%u" , $start_time, -1],
            ["order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            "contract_type =0 ",
            "sys_operator='$account'",
        ];
        $sql = $this->gen_sql_new("select sys_operator, sum(price)/100 as all_price,count(*)as all_count  "
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.userid = t2.userid "
                                  ." where %s and  contract_status <> 0   group by sys_operator order by all_price  ",
                                  self::DB_TABLE_NAME,
                                  Z\z_t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row ($sql);
    }
    public function get_1v1_order_seller_month_money ( $account ,$start_time,$end_time ) {

        $where_arr = [
            ["o1.order_time>=%u" , $start_time, -1],
            ["o1.order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            "o1.contract_type =0 ",
            ["o1.sys_operator='%s'" ,$account,""],
        ];
        $sql = $this->gen_sql_new(
            " select  o1.order_time,o1.orderid ,o1.price ,flowid, o1.grade,"
            ." o1.default_lesson_count* o1.lesson_total/100 as lesson_count,lesson_start,o1.promotion_spec_is_not_spec_flag,"
            ." if(o1.price>0 and o1.can_period_flag=1,o1.price,0) stage_price,"
            ." if(o1.price>0 and o1.can_period_flag=0,o1.price,0) no_stage_price"
            ." from %s o1 "
            ." left join %s s2 on o1.userid = s2.userid "
            ." left join %s f on (f.from_key_int = o1.orderid  and f.flow_type=2002  ) "
            ." left join %s l on o1.from_test_lesson_id = l.lessonid "
            ." where %s and  contract_status in (1,2)   ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list ($sql);
    }

    public function get_1v1_order_seller_month_money_new($account,$start_time,$end_time) {
        $where_arr = [
            ["o1.order_time>=%u" , $start_time, -1],
            ["o1.order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            "o1.contract_type =0 ",
            ["o1.sys_operator='%s'" ,$account,""],
            'contract_status in (1,2)',
        ];
        $sql = $this->gen_sql_new(
            " select sum(o1.price) "
            ." from %s o1 "
            ." left join %s s2 on o1.userid = s2.userid "
            ." where %s ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_1v1_order_seller_list( $start_time,$end_time ,$grade_list=[-1] , $limit_info="limit 15" , $origin_ex="" ,$origin_level=-1 ,$tmk_student_status=-1) {
        $where_arr = [
            "is_test_user=0",
            "contract_type =0 ",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
        ];


        $this->where_arr_add_int_or_idlist($where_arr,"n.tmk_student_status",$tmk_student_status);
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $this->where_arr_add_int_or_idlist($where_arr, "o.grade",$grade_list);
        $this->where_arr_add_int_or_idlist($where_arr, "s.origin_level",$origin_level);

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        $sql = $this->gen_sql_new("select sys_operator,uid adminid,sum(price)/100 as all_price,count(*)as all_count,m.face_pic, "
                                  ." m.level_face_pic, "
                                  ." g.level_icon "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ."left join %s g on g.seller_level = m.seller_level "
                                  ." where %s      group by sys_operator order by all_price desc $limit_info ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_seller_level_goal::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page ($sql);
    }

    public function get_1v1_order_seller_list_new( $start_time,$end_time ,$grade_list=[-1] , $limit_info="limit 15" , $origin_ex="" ,$origin_level=-1 ,$tmk_student_status=-1) {
        $where_arr = [
            "is_test_user=0",
            "contract_type =0 ",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
            ['n.hand_get_adminid=%u',E\Ehand_get_adminid::V_5],
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"n.tmk_student_status",$tmk_student_status);
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $this->where_arr_add_int_or_idlist($where_arr, "o.grade",$grade_list);
        $this->where_arr_add_int_or_idlist($where_arr, "s.origin_level",$origin_level);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $sql = $this->gen_sql_new("select sys_operator,uid adminid,sum(price)/100 as all_price,count(*)as all_count,m.face_pic, "
                                  ." m.level_face_pic, "
                                  ." g.level_icon "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ."left join %s g on g.seller_level = m.seller_level "
                                  ." where %s group by sys_operator order by all_price desc $limit_info ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_seller_level_goal::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page ($sql);
    }

    public function get_1v1_order_new_seller_list( $start_time,$end_time) {

        $where_arr = [
            ["order_time>=%u" , $start_time, -1],
            ["order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            "contract_type =0 ",
            "sys_operator<>'jim'",
            "m.create_time >=".(time()-90*86400)
        ];
        $sql = $this->gen_sql_new("select sys_operator, uid adminid , sum(price)/100 as all_price,count(*)as all_count  "
                                  ." from %s o "
                                  ." join %s s on o.userid = s.userid "
                                  ." join %s m on o.sys_operator = m.account "
                                  ." where %s and  contract_status <> 0   group by sys_operator order by all_price desc  limit 10 ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list ($sql);
    }

    public function month_get_1v1_order_seller_list_group( $start_time,$end_time,$adminid) {
        $month = strtotime(date("Y-m-01",$start_time));
        $groupid = $this->t_group_user_month->get_groupid($adminid,$month);
        //$adminid_list = $this->t_group_user_month->get_adminid_list($groupid,$month);
        $where_arr = [
            ["order_time>=%u" , $start_time, -1],
            ["order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            ["g.groupid=%u" , $groupid, -1],
            ["g.month=%u" , $month, -1],
            ["gu.month=%u" , $month, -1],
            "contract_type in(0,3)",
            "contract_status in(1,2)",
            // "stu_from_type=0 or stu_from_type=11",
            "stu_from_type=0",
            "m.account_role=2",
        ];
        $sql = $this->gen_sql_new(" select g.groupid, group_name , sum(price) all_price,"
                                  ." sum(if(price>0 and can_period_flag=1,price,0)) all_stage_price,"
                                  ." sum(if(price>0 and can_period_flag=0,price,0)) all_no_stage_price,"
                                  ." count(*)as all_count "
                                  ." from %s o , %s s , %s m,  %s gu,   %s g  "
                                  ." where ".
                                  " o.userid = s.userid   and   ".
                                  " o.sys_operator =m.account   and   ".
                                  " m.uid=gu.adminid  and   ".
                                  " gu.groupid =g.groupid and   ".
                                  "  %s group by g.groupid order by all_price desc  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_group_user_month::DB_TABLE_NAME,
                                  t_group_name_month::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_1v1_order_seller_list_group( $start_time,$end_time,$groupid=-1,$start_first,$order_by_str) {
        if(!$order_by_str){
            // $order_by_str = 'sum(price) desc';
            $order_by_str = 'if(sum(price)>0 and month_money<>0,sum(price)/month_money,0) desc';
        }
        $where_arr = [
            ["order_time>=%u" , $start_time, -1],
            ["order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            ["g.groupid=%u" , $groupid, -1],
            "contract_type in(0,3)",
            "contract_status in(1,2)",
            "m.account_role=2",
            "m.account_role=2",
            "g.main_type=2",
            // "g.master_adminid not in(364,416)",
        ];
        $sql = $this->gen_sql_new("select g.group_img,g.groupid,group_name,g.up_groupid,sum(price) all_price,count(*)as all_count,"
                                  ." if(gm.month_money,gm.month_money,0) month_money "
                                  ." from %s o "
                                  ." left join %s s on o.userid = s.userid "
                                  ." left join %s m on o.sys_operator =m.account "
                                  ." left join %s gu on m.uid=gu.adminid "
                                  ." left join %s g on gu.groupid =g.groupid "
                                  ." left join %s gm on gm.groupid =g.groupid and gm.month = '%s' "
                                  ." where %s "
                                  ." group by g.groupid order by %s ",
                                  self::DB_TABLE_NAME,//o
                                  t_student_info::DB_TABLE_NAME,//s
                                  t_manager_info::DB_TABLE_NAME,//m
                                  t_admin_group_user::DB_TABLE_NAME,//gu
                                  t_admin_group_name::DB_TABLE_NAME,//g
                                  t_admin_group_month_time::DB_TABLE_NAME,//gm
                                  $start_first,
                                  $where_arr,
                                  $order_by_str
        );
        return $this->main_get_list($sql);
    }

    public function get_1v1_order_seller_list_group_new( $start_time,$end_time,$groupid=-1,$start_first,$order_by_str) {
        if(!$order_by_str){
            // $order_by_str = 'sum(price) desc';
            $order_by_str = 'if(sum(price)>0 and month_money<>0,sum(price)/month_money,0) desc';
        }
        $where_arr = [
            ["order_time>=%u" , $start_time, -1],
            ["order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            ["g.groupid=%u" , $groupid, -1],
            ["n.hand_get_adminid=%u" ,E\Ehand_get_adminid::V_5],
            "contract_type in(0,3)",
            "contract_status in(1,2)",
            "m.account_role=2",
            // "g.master_adminid not in(364,416)",
        ];
        $sql = $this->gen_sql_new("select g.group_img,g.groupid, group_name , sum(price) as all_price,count(*)as all_count,"
                                  ." if(gm.month_money,gm.month_money,0) month_money "
                                  ." from %s o "
                                  ." left join %s s on o.userid = s.userid "
                                  ." left join %s n on o.userid = n.userid "
                                  ." left join %s m on o.sys_operator =m.account "
                                  ." left join %s gu on m.uid=gu.adminid "
                                  ." left join %s g on gu.groupid =g.groupid "
                                  ." left join %s gm on gm.groupid =g.groupid and gm.month = '%s' "
                                  ." where %s "
                                  ."  group by g.groupid order by %s ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_group_month_time::DB_TABLE_NAME,
                                  $start_first,
                                  $where_arr,
                                  $order_by_str
        );
        return $this->main_get_list($sql);
    }

    public function get_user_lesson_total($userid,$competition_flag=-1){
        $where_arr=[
            ['competition_flag=%u',$competition_flag,-1],
        ];
        $sql=$this->gen_sql_new("select sum(lesson_total*default_lesson_count)/100 as lesson_total "
                                ." from %s "
                                ." where userid=%s "
                                ." and %s"
                                ." and contract_status in (1,2,3)"
                                ." and contract_type in (0,1,3)"
                                ,self::DB_TABLE_NAME
                                ,$userid
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_lesson_total_all($userid)
    {
        $where_arr=[
            ["userid=%u",$userid,0],
            "contract_type in (0,1,3)",
            "contract_status in (1,2,3)"
        ];
        $sql = $this->gen_sql_new("select sum(lesson_total*default_lesson_count) "
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_order_split_all($userid)
    {
        $where_arr=[
            ["o1.userid=%u",$userid,0],
        ];
        $sql = $this->gen_sql_new("select sum(o2.from_parent_order_lesson_count) "
                                  ." from %s o1"
                                  ." left join %s o2 on o1.orderid=o2.parent_order_id "
                                  ." and o2.contract_type=1 "
                                  ." and o2.contract_status>0"
                                  ." and o2.from_parent_order_type=5"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_lesson_total_list($end_time){
        $where_arr=[
            ["pay_time<%u",$end_time,0],
            "contract_type in (0,1,3)",
            "contract_status in (1,2,3)"
        ];

        $sql = $this->gen_sql_new("select o.userid,s.nick,sum(lesson_total*default_lesson_count) as lesson_total,s.type"
                                  ." from %s o"
                                  ." left join %s s on o.userid=s.userid"
                                  ." where %s "
                                  ." and s.is_test_user=0"
                                  ." group by o.userid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['userid'];
        });
    }

    public function get_user_order_list($userid,$competition=-1){
        $where_str = $this->where_str_gen([
            ["competition_flag=%u",$competition,-1],
            ["userid=%u",$userid,-1],
        ]);
        $sql = $this->gen_sql_new("select orderid,subject,grade,price,lesson_total,default_lesson_count,contract_type,lesson_left"
                                  ." from %s"
                                  ." where %s"
                                  ." and contract_type in (0,1,3)"
                                  ." and contract_status in (1,2)"
                                  ." and lesson_left>0"
                                  ." order by orderid asc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_str
        );
        return $this->main_get_list($sql);
    }

    public function get_user_order_list_sum($userid,$competition){
        $where_str=$this->where_str_gen([
            ["competition_flag=%u",$competition,-1],
        ]);
        $sql=$this->gen_sql_new("select format(sum(lesson_total*default_lesson_count)/100,1) as order_sum"
                                ." from %s"
                                ." where userid=%u"
                                ." and contract_status>0"
                                ." and contract_type in (0,1,3)"
                                ." and %s"
                                ,self::DB_TABLE_NAME
                                ,$userid
                                ,$where_str
        );
        return $this->main_get_value($sql);
    }

    public function get_pay_user($competition_flag){
        $sql=$this->gen_sql_new("select distinct(o.userid) "
                                ." from %s o"
                                ." where contract_type in (0,1,3)"
                                ." and contract_status!=0"
                                ." and o.userid!=0"
                                ." and competition_flag=%u"
                                ,self::DB_TABLE_NAME
                                ,$competition_flag
        );
        return $this->main_get_list($sql);
    }

    public function get_pay_user_has_lesson($start_time,$end_time){
        $where_arr = [
            "contract_type in (0,1,3)",
            "s.is_test_user = 0 ",
            "contract_status= 1 ",
            "o.userid!=0 ",
        ];
        $lesson_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type in (0,1,3)",
            "o.userid = userid",
        ];
        $sql=$this->gen_sql_new("select o.orderid,o.userid,price,lesson_total,default_lesson_count,contract_type,lesson_left,"
                                ." competition_flag"
                                ." from %s o"
                                ." left join %s s on o.userid=s.userid"
                                ." where %s"
                                ." and exists (select 1 from %s where %s)"
                                ." order by orderid asc"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
                                ,t_lesson_info::DB_TABLE_NAME
                                ,$lesson_arr
        );
        return $this->main_get_list($sql);
    }

    public function order_info_list($userid,$competition_flag){
        $where_arr=[
            ["competition_flag=%d",$competition_flag,-1],
        ];
        $sql=$this->gen_sql_new("select o.orderid,contract_type,contract_status,lesson_total,default_lesson_count,lesson_left,"
                                ." o.grade,o.subject,price,discount_price,discount_reason,sys_operator,s.realname,s.phone,"
                                ." contractid"
                                ." from %s o"
                                ." left join %s s on s.userid=o.userid"
                                ." where o.userid=%u"
                                //." and contract_status!=0"
                                ." and contract_type in (0,1,3)"
                                ." and %s"
                                ." order by orderid desc "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$userid
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_order_info_by_orderid($orderid){
        $sql=$this->gen_sql_new("select contract_type,contract_status,subject,grade,lesson_total,default_lesson_count,"
                                ." userid,discount_reason,competition_flag,lesson_left,orderid,contractid ,"
                                ." parent_order_id,from_parent_order_type,from_parent_order_lesson_count,"
                                ." promotion_discount_price,promotion_spec_discount,price,discount_price"
                                ." from %s"
                                ." where orderid=%u"
                                ,self::DB_TABLE_NAME
                                ,$orderid
        );
        return $this->main_get_row($sql);
    }

    public function get_next_order_info($userid,$competition_flag){
        $sql=$this->gen_sql_new("select orderid,lesson_left"
                                ." from %s "
                                ." where userid=%u "
                                ." and contract_status!=3 "
                                ." and contract_type in (0,1,3) "
                                ." and competition_flag=%u "
                                ." and lesson_left>0 "
                                ." order by orderid asc "
                                ,self::DB_TABLE_NAME
                                ,$userid
                                ,$competition_flag
        );
        return $this->main_get_row($sql);
    }
    public function tongji_seller_order_count_origin( $field_name,$start_time,$end_time,$adminid_list=[],$tmk_adminid=-1 ,$origin_ex,$opt_date_str,$origin) {


        switch ( $field_name ) {
        case "origin" :
            $field_name="o.origin";
            break;

        case "grade" :
            $field_name="o.grade";
            break;
        default:
            break;
        }


        if($field_name=="tmk_adminid"){
            $where_arr=[
                ["o.origin like '%%%s%%' ",$origin,""],
                "contract_type in ( 0 )",
                "is_test_user=0",
                "contract_status >0 ",
                "tmk_adminid >0 ",
                "order_time>tmk_assign_time",
            ];
        } else {
            $where_arr=[
                ["o.origin like '%%%s%%' ",$origin,""],
                "contract_type in ( 0 )",
                "is_test_user=0",
                "contract_status >0 ",
            ];

        }

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        // $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_list);

        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value ,count(*) as order_count, round(sum(price)/100) as order_all_money  ,count(distinct o.userid) as user_count "
            ." from   %s  o  "
            ." left join %s m  on o.sys_operator=m.account "
            ." left join %s s  on o.userid=s.userid "
            ." left join %s n  on o.userid=n.userid "
            ." where %s "
            ." group by  check_value  ",
            self::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME ,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr );
        // dd($sql);
        // return $sql;
        return $this->main_get_list($sql);
    }

    public function tongji_seller_order_info( $origin='',$field_name,$start_time,$end_time,$adminid_list=[],$tmk_adminid=-1 ,$origin_ex,$opt_date_str, $check_value='', $page_info = '') {


        switch ( $field_name ) {
        case "origin" :
            $field_name="o.origin";
            break;

        case "grade" :
            $field_name="o.grade";
            break;
        default:
            break;
        }

        if($field_name=="tmk_adminid"){
            $where_arr=[
                ["o.origin like '%%%s%%' ",$origin,""],
                ["$field_name='%s'",$check_value,""],
                "contract_type in ( 0 )",
                "is_test_user=0",
                "contract_status >0 ",
                "tmk_adminid >0 ",
                "order_time>tmk_assign_time",
            ];
        } else {
            $where_arr=[
                ["o.origin like '%%%s%%' ",$origin,""],
                ["$field_name='%s'",$check_value,""],
                "contract_type in ( 0 )",
                "is_test_user=0",
                "contract_status >0 ",
            ];
        }
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_list);

        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value,o.price,o.orderid,s.phone_location,s.nick,s.phone,o.grade,"
            ."o.pay_time,o.subject,o.lesson_total, o.lesson_left, o.default_lesson_count,n.has_pad,s.origin_level"
            ." from   %s  o  "
            ." left join %s m  on o.sys_operator=m.account "
            ." left join %s s  on o.userid=s.userid "
            ." left join %s n  on o.userid=n.userid "
            ." where %s ",
            self::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME ,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );
        // dd($sql);
        if ($page_info) {
            return $this->main_get_list_by_page($sql, $page_info);
        } else {
            return $this->main_get_list($sql);
        }
    }

    public function tongji_seller_order_count($start_time,$end_time) {
        $where_arr=[
            ["account_role=%u", E\Eaccount_role::V_2 , -1  ],
            "contract_type=0",
            "contract_status>0",
        ];
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select uid as adminid ,count(*) as value , sum(price) as money from  %s  o  left join %s m  on o.sys_operator=m.account where %s group by  uid order by value desc ",
            self::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME ,
            $where_arr );
        return $this->main_get_list($sql);
    }

    public function tongji_seller_order_person_count($start_time,$end_time) {
        $where_arr=[
            ["account_role=%u", E\Eaccount_role::V_2 , -1  ],
            "contract_type=0",
            "contract_status>0",
        ];
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select uid as adminid ,count(distinct userid) as value from  %s  o  left join %s m  on o.sys_operator=m.account where %s group by  uid order by value desc ",
            self::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME ,
            $where_arr );
        return $this->main_get_list($sql);
    }


    public function get_order_count_list_all($start_time,$end_time){
        $where_arr=[
            ["account_role=%u", E\Eaccount_role::V_2 , -1  ],
            "contract_type=0",
            "contract_status>0",
        ];
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select count(distinct uid) all_count,count(*) as value , sum(price) as money from  %s  o  left join %s m  on o.sys_operator=m.account where %s ",
            self::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME ,
            $where_arr );
        return $this->main_get_row($sql);

    }

    public function get_order_person_count_list_all($start_time,$end_time){
        $where_arr=[
            ["account_role=%u", E\Eaccount_role::V_2 , -1  ],
            "contract_type=0",
            "contract_status>0",
        ];
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select count(distinct uid) all_count,count(distinct userid) as value from  %s  o  left join %s m  on o.sys_operator=m.account where %s ",
            self::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME ,
            $where_arr );
        return $this->main_get_row($sql);

    }


    public function get_user_normal_order($userid){
        $sql=$this->gen_sql_new("select orderid,order_time,lesson_total,default_lesson_count,competition_flag"
                                ." from %s"
                                ." where order_status=1"
                                ." and contract_type in (0,3)"
                                ." and userid=%u"
                                ." order by orderid desc"
                                ,self::DB_TABLE_NAME
                                ,$userid
        );
        return $this->main_get_list($sql);
    }
    public function add_contract_contract_type_1( $from_parent_order_type,  $sys_operator,$userid, $parent_order_id , $lesson_total,  $competition_flag , $grade,$subject  ) {

        //赠送
        $contract_type          = E\Econtract_type::V_1;
        $origin="";
        $price=0;
        $discount_price=0;
        $discount_reason="";
        $need_receipt=0;
        $title="";
        $requirement="";
        $from_test_lesson_id=0;
        $default_lesson_count=1;

        $orderid=$this->add_contract($sys_operator,  $userid , $origin, $competition_flag,$contract_type,$grade,$subject,$lesson_total,$price ,  $discount_price ,$discount_reason , $need_receipt, $title ,$requirement, $from_test_lesson_id,   $from_parent_order_type, $parent_order_id,$default_lesson_count);

    }

    public function add_contract(
        $sys_operator,  $userid , $origin, $competition_flag,$contract_type,$grade,$subject,$lesson_total,$price ,  $discount_price ,$discount_reason , $need_receipt, $title ,$requirement,$from_test_lesson_id = 0, $from_parent_order_type=0, $parent_order_id=0 ,$default_lesson_count=100,
        $order_price_type=0,
        $order_promotion_type=0,
        $promotion_discount_price=0,
        $promotion_present_lesson=0,
        $promotion_spec_discount=0,
        $promotion_spec_present_lesson=0,
        $contract_from_type=0,
        $from_parent_order_lesson_count=0,
        $pre_price=0,
        $order_price_desc="",
        $order_partition_flag =0, $can_period_flag=0,$is_new_stu=0
    )
    {

        //E\Econtract_type::V_3
        srand(microtime(true) * 1000);
        $time_str = $userid+rand(1,1000000000)%142857;
        $contractid = 'E'.date('Ymd',time()).$time_str;


        $lesson_left=0;
        if($contract_type==0 || $contract_type ==1 || $contract_type ==3){
            $lesson_left=$lesson_total*$default_lesson_count;
        }
        $this->row_insert([
            'userid'                 => $userid,
            'sys_operator'           => $sys_operator,
            'contract_type'          => $contract_type,
            'contract_status'        => E\Econtract_status::V_0 ,
            'competition_flag'       => $competition_flag,
            'order_time'             => time(NULL),
            'grade'                  => $grade,
            "contractid"             => $contractid,
            'subject'                => $subject,
            'lesson_total'           => $lesson_total,
            'lesson_left'            => $lesson_total*$default_lesson_count,
            'price'                  => $price,
            'need_receipt'           => $need_receipt,
            'title'                  => $title,
            'default_lesson_count'   => $default_lesson_count,
            'origin'                 => $origin,
            "discount_reason"        => $discount_reason,
            "discount_price"         => $discount_price,
            "from_test_lesson_id"    => $from_test_lesson_id,
            "from_parent_order_type" => $from_parent_order_type,
            "parent_order_id"        => $parent_order_id,

            "order_promotion_type"           => $order_promotion_type,
            "promotion_discount_price"       => $promotion_discount_price,
            "promotion_present_lesson"       => $promotion_present_lesson,
            "promotion_spec_discount"        => $promotion_spec_discount,
            "promotion_spec_present_lesson"  => $promotion_spec_present_lesson,
            "stu_from_type"                  => $contract_from_type,
            "from_parent_order_lesson_count" => $from_parent_order_lesson_count,

            "pre_price"                      => $pre_price,
            "order_price_desc"               => $order_price_desc,
            "order_partition_flag"           => $order_partition_flag,
            "can_period_flag"               => $can_period_flag,
            "is_new_stu"                    => $is_new_stu
        ]);
        $orderid=$this->get_last_insertid();

        if ($this->t_student_info->get_is_test_user($userid) !=1 ) {
            $nick=$this->t_student_info->get_nick($userid);
            $phone= $this->t_seller_student_new->get_phone($userid);
            $contract_type_str=E\Econtract_type::get_desc($contract_type);
            $from_parent_order_type_str=0;
            if ($contract_type==2) {
                $from_parent_order_type_str=E\Efrom_parent_order_type::get_desc($from_parent_order_type);
            }
            $price_str=$price/100;
            $lesson_total_str=$lesson_total/100;
            $this->t_manager_info->send_wx_todo_msg(
                "echo",
                "申请人:".$sys_operator,
                "[$phone]$nick:[$contract_type_str][$from_parent_order_type]",
                "购买课时[{$lesson_total_str}],价格[{$price_str}]" , "/user_manage_new/money_contract_list?studentid=$userid"
            );

            $this->t_manager_info->send_wx_todo_msg(
                "zore",
                "申请人:".$sys_operator,
                "[$phone] $nick:[$contract_type_str][$from_parent_order_type]",
                "购买课时[{$lesson_total_str}],价格[{$price_str}]" , "/user_manage_new/money_contract_list_stu?studentid=$userid"
            );
        }
        return $orderid;
    }

    public function has_1v1_order($userid) {
        $sql=$this->gen_sql(
            "select count(*) from %s where userid=%u and contract_type in (0,1,3)",
            self::DB_TABLE_NAME,
            $userid);
        return $this->main_get_value($sql)>0;
    }

    /*
     *@modify:Abner<abner@leo.edu.com>
     *@param:$opt_date_str 筛选类型[order_time:下单时间check_money_time:财务确认时间]
     */
    public function get_order_count_new($start_time,$end_time,$contract_type,$contract_status,$userid
                                        ,$config_courseid,$is_test_user,$show_yueyue_flag,$has_money,$check_money_flag=-1
                                        ,$isset_assistantid=-1,$origin="",$stu_from_type=-1,$sys_operator="",$account_role=-1
                                        ,$adminid_list=[],$adminid_all=[],$opt_date_str
    ){
        $where_arr=[];
        if($userid>0){
            $where_arr=[["t1.userid=%u",$userid,-1]];
        }else if ( $config_courseid >0 ) {
            $where_arr=[["config_courseid=%u" , $config_courseid, -1]];
        }else{
            $where_arr=[
                ["$opt_date_str>=%u" , $start_time, -1],
                ["$opt_date_str<=%u" , $end_time, -1],
                ["is_test_user=%u" , $is_test_user, -1],
                // ["check_money_flag=%u" , $check_money_flag, -1],
                ["stu_from_type=%u" , $stu_from_type, -1],
                ["sys_operator like '%%%s%%'" , $sys_operator, ""],
            ];
            if($check_money_flag == 0){//未确认
                $where_arr[] = "check_money_flag=0";
            }elseif($check_money_flag == 1){//已付款
                $where_arr[] = "t1.contract_status<>0";
            }elseif($check_money_flag == 2){//未付款
                $where_arr[] = "t1.contract_status=0";
            }

            if ($isset_assistantid==0) {
                $where_arr[]="t2.assistantid =0 " ;
            }else if ($isset_assistantid==1) {
                $where_arr[]="t2.assistantid <>0 " ;
            }

            if ($contract_type==-2) {
                $where_arr[]="contract_type in(0,1,3)" ;
            }else{
                $where_arr[]=["contract_type=%u" , $contract_type, -1];
            }

            if ($contract_status ==-2) {
               $where_arr[] = "contract_status <> 0";
            }else{
               $where_arr[]= ["contract_status=%u" , $contract_status, -1];
            }

            if ($has_money ==0) {
               $where_arr[]="price=0" ;
            }else if ($has_money ==1) {
               $where_arr[]="price>0" ;
            }

        }

        if (!$show_yueyue_flag) {
            $where_arr[]="sys_operator <>'yueyue' ";
        }

        if ($origin) {
            $where_arr[]= [ "t2.origin like '%%%s%%'" , $origin,""];
        }

        //http:7u2f5q.com2.z0.glb.qiniucdn.com/8008e863f1b6151890ccf278f711ab691460108026469.png
        //select count(*) from t_order_info as t1,t_book_info as t2 where t1.userid=t2.userid, t2.origin like "%APP课程包%" ;
        $where_arr[] = ["t3.account_role = %u" , $account_role, -1];

        $this->where_arr_adminid_in_list($where_arr,"t3.uid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"t3.uid",$adminid_all);
        $sql = $this->gen_sql_new("select t3.uid adminid,t3.account, sum(price) all_price,".
                                  "sum(if(stu_from_type=1,price,0)) transfer_introduction_price,"
                                  ." sum(if(stu_from_type=0,price,0)) new_price,"
                                  ." sum(if(stu_from_type=10,price,0)) normal_price,"
                                  ." sum(if(stu_from_type=11,price,0)) extend_price, "
                                  ." sum(if(t1.contract_status<>0,price,0)) all_price_suc,"
                                  ." sum(if(t1.contract_status=0,price,0)) all_price_fail"
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.userid = t2.userid "
                                  ." left join %s t3 on t1.sys_operator = t3.account "
                                  ." left join %s c on t1.orderid = c.orderid "
                                  ." where %s group by t1.sys_operator ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['adminid'];
        });
    }

    public function get_order_count_subject_grade_info($start_time,$end_time,$contract_type,$contract_status,$userid
                                                       ,$config_courseid,$is_test_user,$show_yueyue_flag,$has_money
                                                       ,$check_money_flag=-1,$isset_assistantid=-1,$origin="",$stu_from_type=-1
                                                       ,$sys_operator="",$account_role=-1,$adminid_list=[],$adminid_all=[]
    ){
        $where_arr=[];
        if($userid>0){
            $where_arr=[["t1.userid=%u",$userid,-1]];
        }else if ( $config_courseid >0 ) {
            $where_arr=[["config_courseid=%u" , $config_courseid, -1]];
        }else{
            $where_arr=[
                ["order_time>=%u" , $start_time, -1],
                ["order_time<=%u" , $end_time, -1],
                ["is_test_user=%u" , $is_test_user, -1],
                ["check_money_flag=%u" , $check_money_flag, -1],
                ["stu_from_type=%u" , $stu_from_type, -1],
                ["sys_operator like '%%%s%%'" , $sys_operator, ""],
            ];

            if ($isset_assistantid==0) {
                $where_arr[]="t2.assistantid =0 " ;
            }else if ($isset_assistantid==1) {
                $where_arr[]="t2.assistantid <>0 " ;
            }

            if ($contract_type==-2) {
                $where_arr[]="contract_type in(0,1,3)" ;
            }else{
                $where_arr[]=["contract_type=%u" , $contract_type, -1];
            }

            if ($contract_status ==-2) {
                $where_arr[] = "contract_status <> 0";
            }else{
                $where_arr[]= ["contract_status=%u" , $contract_status, -1];
            }

            if ($has_money ==0) {
                $where_arr[]="price=0" ;
            }else if ($has_money ==1) {
                $where_arr[]="price>0" ;
            }
        }

        if (!$show_yueyue_flag) {
            $where_arr[]="sys_operator <>'yueyue' ";
        }

        if ($origin) {
            $where_arr[]= [ "t2.origin like '%%%s%%'" , $origin,""];
        }

        //http:7u2f5q.com2.z0.glb.qiniucdn.com/8008e863f1b6151890ccf278f711ab691460108026469.png
        //select count(*) from t_order_info as t1,t_book_info as t2 where t1.userid=t2.userid, t2.origin like "%APP课程包%" ;
        $where_arr[] = ["t3.account_role = %u" , $account_role, -1];
        $this->where_arr_adminid_in_list($where_arr,"t3.uid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"t3.uid",$adminid_all);


        $sql = $this->gen_sql_new("select t1.price,t1.grade,t1.subject,t2.phone_location "
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.userid = t2.userid "
                                  ." left join %s t3 on t1.sys_operator = t3.account "
                                  ." left join %s c on t1.orderid = c.orderid "
                                  ." where %s and price>0",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }




    public function get_all_money()
    {
        $sql=$this->gen_sql_new(
            "select  sum(price)/100 as all_money from  %s o , %s s ".
            " where o.userid=s.userid and   s.is_test_user =0 and contract_status >0 ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function get_month_money_info($start_time, $end_time){
        $sql = $this->gen_sql_new("select sum(price) as all_money,from_unixtime(order_time,'%%Y-%%m') as order_month,"
                                  ." count(*) as count,sum(lesson_total*default_lesson_count) as order_total "
                                  ." from %s o,%s s "
                                  ." where o.userid=s.userid "
                                  ." and s.is_test_user=0 "
                                  ." and contract_status>0 "
                                  ." and price>0 "
                                  ." and order_time>=$start_time"
                                  ." and order_time<$end_time"
                                  ." group by order_month "
                                  ." order by order_month asc "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["order_month"];
        });
    }

    public function tongji_by_grade($account_role,$start_time, $end_time ) {
        $where_arr=[
            "is_test_user=0",
        ];
        if ($account_role == E\Eaccount_role::V_2) {
            $where_arr[] ="contract_type=0 ";
        }else if ($account_role == E\Eaccount_role::V_1) {
            $where_arr[] ="contract_type=3 ";
        }else if ($account_role == -1) {
            $where_arr[] ="contract_type in (0,3) ";
        }else{
            $where_arr[] =" false ";
        }

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $order_str="price>0";
        $sql=$this->gen_sql_new(
            "select  o.subject,"
            ." sum(s.grade <200 and $order_str  ) as l_1_order_count, "
            ." sum( s.grade >=200 and s.grade <300 and $order_str  ) as l_2_order_count,   "
            ." sum( s.grade >=300 and s.grade <400 and $order_str  ) as l_3_order_count ,  "
            ." sum(  $order_str  ) as l_all_order_count  "
            ." from %s o "
            ." join  %s s on s.userid= o.userid "
            ." where %s  group by subject   ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"] ;
        });
    }

    public function get_order_device_info($start_time,$end_time)  {
        $where_arr=[
            "contract_type=0" ,
            "is_test_user=0" ,
        ];
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select "
            ." count(*) as all_count, "
            ."sum(user_agent  like '%%ipad%%') as ipad_count,    "
            ."sum(user_agent  like '%%windows%%') as windows_count,    "
            ."sum(user_agent ='' ) as null_count   "
            . " from %s o "
            . " join %s s  on s.userid=o.userid "
            . " where  %s ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_row($sql);
    }

    public function seller_info( $start_time,$end_time,$adminid_list=[],$adminid_all=[])
    {
        $where_arr=[
            "is_test_user=0" ,
            "contract_type=0" ,
        ];
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_all);
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select sum( contract_status>0 ) as order_user_count, sum(if( contract_status>0, price, 0) )/100  order_money,  sum( contract_status=0 ) as no_pay_order_user_count, sum(if( contract_status=0, price, 0) )/100  no_pay_order_money  "
                                ." from %s o "
                                ." join %s s on s.userid=o.userid "
                                ." left join %s m on o.sys_operator =m.account "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_manager_info::DB_TABLE_NAME
                                ,$where_arr
        );
        $ret= $this->main_get_row($sql);
        $ret["order_money"]= intval( $ret["order_money"]);

        return $ret;
    }

    public function get_seller_date_money_list( $start_time,$end_time,$adminid_list=[],$adminid_all=[] ) {
        $where_arr=[
            "is_test_user=0" ,
            "contract_status>0" ,
            "contract_type=0" ,
        ];

        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_all);
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select from_unixtime(order_time,'%%Y-%%m-%%d' ) as date, sum(price)/100  money "
                                ." from %s o "
                                ." join %s s on s.userid=o.userid "
                                ." left join %s m on o.sys_operator =m.account "
                                ." where %s  group by  from_unixtime(order_time,'%%Y-%%m-%%d' ) order by  date  "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_manager_info::DB_TABLE_NAME
                                ,$where_arr
        );

        $ret_list= $this->main_get_list($sql,function($item ){
            return $item["date"];
        });

        foreach ($ret_list as &$item) {
            $item["money"] = intval($item["money"]);
        }
        return \App\Helper\Common::gen_date_time_list($start_time,$end_time, $ret_list)  ;
    }


    public function check_order_origin_userid($userid  ) {
        $sql=$this->gen_sql_new(
            "select  o1.userid from %s o1 "
            . " join %s o2 on  o1.parent_order_id=o2.orderid   "
            . " where "
            ."  o1.from_parent_order_type=1 and o2.userid=$userid limit 1 "
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function  get_type1_lesson_count_by_start_time ( $start_time ) {
        $sql=$this->gen_sql_new(
            "select  sum(lesson_total * default_lesson_count) as lesson_count  from %s  "
            . " where order_time >%u and contract_type=1 and from_parent_order_type=0 "
            ,self::DB_TABLE_NAME
            ,$start_time
        );
        return $this->main_get_value($sql);
    }



    public function  get_type1_lesson_count ($orderid) {
        $sql=$this->gen_sql_new(
            "select  lesson_total * default_lesson_count as lesson_count  from %s  "
            . " where parent_order_id=%u and contract_type=1 and from_parent_order_type=0 "
            ,self::DB_TABLE_NAME
            ,$orderid
        );
        return $this->main_get_value($sql);
    }




    public function check_order_free_to_self($orderid,$from_parent_order_type=0) {
        $sql=$this->gen_sql_new(
            "select 1 from %s o1, %s o2 where o1.parent_order_id=o2.orderid and o1.from_parent_order_type=$from_parent_order_type and  o2.orderid=%u ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $orderid
        );
        return $this->main_get_value($sql);
    }
    public function get_relation_order_list ($orderid) {
        $sql=$this->gen_sql_new(
            "select orderid,contract_type,sys_operator,userid,price,default_lesson_count,"
            ." lesson_total,order_time,from_parent_order_type,from_parent_order_lesson_count "
            ." from %s where (orderid=$orderid or  parent_order_id = $orderid ) ",
            self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,1,100);
    }
    public function get_last_seller_by_userid($userid) {

        $sql=$this->gen_sql_new(
            "select sys_operator from %s  "
            ."where userid=%d and  contract_type=0 and contract_status>0  order by order_time desc limit 1 ",
            self::DB_TABLE_NAME,$userid  );
       $sys_operator= $this->main_get_value($sql,"");
        if ($sys_operator) {
            return $this->t_manager_info->get_id_by_account($sys_operator);
        }else{
            return 0;
        }
    }

    public function  del_contract ($orderid,$userid) {
        $sql = sprintf("delete from %s "
                       . "where orderid = %u "
                       ." and contract_status = 0 "
                       ." and userid=%u "
                       ." and pre_pay_time=0 "
                       ,self::DB_TABLE_NAME
                       ,$orderid,$userid
        );
        return $this->main_update($sql);
    }

    public function set_order_payed($orderid, $channelid, $pay_number)
    {
        $sql = $this->gen_sql_new("update %s set channelid = %u,"
                                  ." contract_status = 1,"
                                  ." order_status = 1 , "
                                  ." pay_time = %u,"
                                  ." pay_number = '%s' "
                                  ." where orderid = %u "
                                  ,self::DB_TABLE_NAME
                                  ,$channelid
                                  ,time()
                                  ,$pay_number
                                  ,$orderid
        );
        return $this->main_update( $sql );
    }

    public function set_from_data($orderid,$from_key,$from_url){
        $sql = $this->gen_sql_new("update %s set from_key = %s,"
                                  ." from_url = '%s' "
                                  ." where orderid = %u "
                                  ,self::DB_TABLE_NAME
                                  ,$from_key
                                  ,$from_url
                                  ,$orderid
        );
        return $this->main_update($sql);
    }

    public function get_no_pad_list(){
        $sql = $this->gen_sql_new("select count(1) "
                                  ." from %s o"
                                  ." left join %s s"
                                  ." where has_pad=0"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_lessonid_by_userid($userid){
        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  ." where userid = %u"
                                  ,self::DB_TABLE_NAME
                                  ,$userid
        );
        return $this->main_get_list($sql);

    }
    public function update_sys_operator_by_change_account( $old_account , $new_account ) {
        $sql=$this->gen_sql_new(
            "update %s set sys_operator='%s' where sys_operator='%s'",
            self::DB_TABLE_NAME,
            $new_account, $old_account);
        return $this->main_update($sql);
    }

    public function get_all_info_by_name($name){
        $sql=$this->gen_sql_new("select * from %s where sys_operator='%s'",self::DB_TABLE_NAME,$name);
        return $this->main_get_list($sql);
    }

    public function get_son_order_list($orderid){
        $sql=$this->gen_sql_new("select orderid from %s where parent_order_id =%u",self::DB_TABLE_NAME,$orderid);
        return $this->main_get_list($sql);

    }

    public function get_order_data($type,$start_time,$end_time){
        $where_arr = [
            ["contract_type=%u",$type,-1],
            ["order_time>%u",$start_time,0],
            ["order_time<%u",$end_time,0],
            "contract_status in (1,2)",
        ];

        $sql = $this->gen_sql_new("select lesson_total,default_lesson_count,price,discount_price,order_time"
                                  ." from %s o"
                                  ." left join %s s on o.userid=s.userid"
                                  ." where %s"
                                  ." and is_test_user=0"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_money_info ( $adminid , $start_time,$end_time )
    {
        $start_time_new = $start_time;
        $ret_time = $this->task->t_month_def_type->get_all_list();//销售自定义月份时间
        foreach($ret_time as $item){
            if($start_time>=$item['start_time'] && $start_time<$item['end_time']){
                $start_time_new = $item['def_time'];
            }
        }

        $sys_operator= $this->t_manager_info->get_account($adminid);
        //$groupid= $this->t_ma

        $ret_arr=[];
        $self_group_info = $this->task->t_group_user_month->get_group_info_by_adminid(-1 , $adminid ,$start_time_new);
        // $self_group_info= $this->t_admin_group_user->get_group_info_by_adminid(-1 , $adminid );

        $order_list=$this->get_1v1_order_seller_month_money($sys_operator, $start_time, $end_time );
        $all_price = 0;
        $all_stage_price = 0;
        $all_no_stage_price = 0;

        $id_list=[];
        $require_all_price=0;
        $v_24_hour_all_price=0;
        $require_and_24_hour_price=0;
        foreach ($order_list as  $item ) {
            $require_flag=false;
            $v_24_hour_flag=false;
            $all_price+= $item["price"];
            $all_stage_price+= $item["stage_price"];
            $all_no_stage_price+= $item["no_stage_price"];
            //高中 < 90课时 不算
            if  ($item["flowid"] && !$item["promotion_spec_is_not_spec_flag"] && !(in_array($item["grade"]*1, [300,301,302,303]) &&  $item["lesson_count"] < 90) ) {
                $require_all_price+= $item["price"];
                $require_flag=true;
            }

            $lesson_start=$item["lesson_start"];
            $check_time= strtotime(date("Y-m-d", $lesson_start ) ) + 2*86400;
            if ($check_time > $item["order_time"]) {
                $v_24_hour_all_price+=$item["price"];
                $v_24_hour_flag=true;
            }
            if ($require_flag && $v_24_hour_flag) {
                $require_and_24_hour_price+=$item["price"];
            }


            $id_list[]=$item["orderid"];


        }
        $ret_arr["all_price"]=$all_price/100;
        $ret_arr["stage_money"]=$all_stage_price/100;
        $ret_arr["no_stage_money"]=$all_no_stage_price/100;
        $ret_arr["require_all_price"]=$require_all_price/100;
        $ret_arr["24_hour_all_price"] = $v_24_hour_all_price/100 ;
        $ret_arr["require_and_24_hour_price"] = $require_and_24_hour_price/100 ;
        //处理

        $group_list= $this->month_get_1v1_order_seller_list_group($start_time, $end_time, $adminid );
        $group_all_price=0;
        $group_all_stage_price = 0;
        $group_all_no_stage_price = 0;
        if ( count ( $group_list) ==1 ) {
            $group_all_price          = $group_list[0]["all_price"];
            $group_all_stage_price    = $group_list[0]["all_stage_price"];
            $group_all_no_stage_price = $group_list[0]["all_no_stage_price"];
        }

        $ret_arr["group_all_price"] = $group_all_price/100;
        $ret_arr["group_all_stage_price"] = $group_all_stage_price/100;
        $ret_arr["group_all_no_stage_price"] = $group_all_no_stage_price/100;

        $ret_arr["group_default_money"] = $this->t_admin_group_month_time->get_month_money($self_group_info["groupid"] , date("Y-m-d", $start_time_new )  );
        // $ret_arr["group_adminid"] = $this->t_admin_group_user-> get_master_adminid_by_adminid($adminid )  ;
        $ret_arr["group_adminid"] = $this->task->t_group_user_month->get_master_adminid_by_adminid($adminid,-1, $start_time_new);
        return $ret_arr;
    }

    public function get_24_hour_order_price( $orderid_list ) {
        $where_arr=[];
        $where_arr[]=$this->where_get_in_str("o1.orderid",$orderid_list,false);
        $sql=$this->gen_sql_new(
            "select sum(o1.price )"
            . " from  %s o1 "
            . " join %s o2 on (o1.orderid = o2.parent_order_id and o2.from_parent_order_type =2 )"
            . "where %s"
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$where_arr
            );
        return $this->main_get_value($sql);
    }

    public function get_zengsong_list(){
        $sql = $this->gen_sql_new("select o.userid,count(s.userid) as stu_num"
                                  ." from %s o"
                                  ." left join %s s on o.userid=s.origin_userid"
                                  ." where contract_type=1"
                                  ." and contract_status in (1,2)"
                                  ." and from_parent_order_type=1"
                                  ." and is_test_user=0"
                                  //." and order_time>1483200000"
                                  ." group by o.userid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }


    public function get_order_desc_list ($page_num, $start_time, $end_time, $subject, $grade , $require_flag, $class_hour, $account_role )  {
        $where_arr=[
            ["o1.subject=%u" , $subject, -1 ],
            ["o1.grade=%u" , $grade, -1 ],
            "o1.contract_status in(1,2)",
            "o1.contract_type=0 ",
            "is_test_user=0",
        ];
        //特殊申请
        if ($require_flag ==1 ) {
            $where_arr[] = "f.flowid is not null" ;
        }else if ($require_flag ==0 ) {
            $where_arr[] = "f.flowid is null" ;
        }

        //课时条件
        if ($class_hour ==1 ) {
            $where_arr[] =  "o1. lesson_total * o1.default_lesson_count  < 9000" ;
        }else if ($class_hour ==0 ) {
            $where_arr[] =  "o1. lesson_total * o1.default_lesson_count  >= 9000" ;
        }

        $this->where_arr_add_time_range($where_arr,"o1.order_time",$start_time,$end_time);

        $sql =  $this->gen_sql_new(
            " select m.account_role,  o1.sys_operator, o1.orderid, o1.userid, o1.discount_price, "
            ." o1.promotion_discount_price,   o1.price , o1.subject, o1.grade, "
            ." sum(o2. lesson_total * o2.default_lesson_count ) as t_2_lesson_count,"
            ." o1. lesson_total * o1.default_lesson_count  as t_1_lesson_count "
            ." from  %s o1 "
            ." left join %s o2  on (o2.parent_order_id  = o1.orderid and  o2.from_parent_order_type=0 )"
            ." left join %s s on s.userid=o1.userid "
            ." left join %s f on (f.from_key_int = o1.orderid and f.flow_type=2002)  "
            ." left join %s m on (m.account = o1.sys_operator )  "
            ." where %s "
            ." group by o1.orderid   ",
            self::DB_TABLE_NAME ,
            self::DB_TABLE_NAME ,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        ) ;

        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function check_order($userid){
        $where_arr=[
            ["userid=%u",$userid,0],
            "contract_type in (0,3)",
            "contract_status>0",
            "pay_time<1490256000"
        ];

        $sql = $this->gen_sql_new("select count(1) from %s"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function check_order_info_new($userid,$account,$start,$end){
        $where_arr=[
            ["userid=%u",$userid,0],
            "contract_type =3",
            ["sys_operator='%s'" ,$account,""],
            "contract_status=1"
        ];
        $this->where_arr_add_time_range($where_arr,"order_time",$start,$end);
        $sql = $this->gen_sql_new("select price from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_user_subject($userid){
        $sql = $this->gen_sql_new("select subject"
                                  ." from %s"
                                  ." where contract_type=3"
                                  ." and contract_status in (1,2)"
                                  ." and userid=%u"
                                  ." group by subject"
                                  ,self::DB_TABLE_NAME
                                  ,$userid
        );
        return $this->main_get_list($sql);
    }

    public function check_is_have_order($lessonid){
        $sql = $this->gen_sql_new("select orderid"
                                  ." from %s"
                                  ." where contract_type =0 and  from_test_lesson_id=%u "
                                  ,self::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_value($sql);

    }

    public function get_test_plan_order($start_time,$end_time){
        $where_arr=[
            ["pay_time>%u",$start_time,0],
            ["pay_time<%u",$end_time,0],
            "contract_type=0",
            "contract_status in (1,2,3)",
            "s.grade<106",
        ];

        $sql = $this->gen_sql_new("select price,lesson_total,default_lesson_count"
                                  ." from %s o"
                                  ." left join %s s on o.userid=s.userid"
                                  ." where %s"
                                  ." and exists ("
                                  ." select 1 "
                                  ." from %s t"
                                  ." left join %s l on t.userid=l.userid "
                                  ." where o.userid=t.userid "
                                  ." and lesson_type=2"
                                  ." and lesson_del_flag=0"
                                  ." and lesson_start<%u"
                                  ." and require_admin_type=2 "
                                  ." )"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$end_time
        );
        return $this->main_get_list($sql);
    }

    public function get_order_all_lesson_count( $userid ,$sys_operator ){
        $sql=$this->gen_sql_new(
            " select sum(lesson_total* default_lesson_count )  as lesson_count "
            . " from %s where sys_operator='%s' and userid=%u and contract_status in ( 1, 2) ",
            self::DB_TABLE_NAME,
            $sys_operator, $userid
        );
        return $this->main_get_value($sql) ;
    }

    public function get_orgin_user($userid){
        $where_arr = [
            ["s.userid=%u",$userid,0]
        ];
        $sql = $this->gen_sql_new("select orderid,pay_time,s.nick,s.phone,o.subject,o.price,"
                                  ." o.lesson_total*o.default_lesson_count as order_total"
                                  ." from %s o"
                                  ." left join %s s on o.userid=s.userid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_order_list_require_adminid(
        $page_num, $start_time,$end_time,$contract_type,$contract_status
        ,$userid,$config_courseid,$is_test_user,$show_yueyue_flag,$has_money
        ,$check_money_flag=-1,$assistantid=-1,$origin="",$stu_from_type=-1,$sys_operator=""
        ,$account_role=-1,$grade=-1,$subject=-1,$tmk_adminid=-1, $need_receipt=-1
        ,$teacherid=-1,$up_master_adminid=-1,$account_id=74,$require_adminid_list=[],$origin_userid=-1, $referral_adminid=-1,
        $opt_date_str="order_time" , $order_by_str= "order by s.assistantid asc , order_time desc"
        ,$spec_flag=-1, $orderid=-1 ,$order_activity_type=-1,$show_son_flag=false, $adminid = -1
    ){
        $where_arr=[];
        if($orderid>=0){
            $where_arr=[["o.orderid=%u",$orderid,-1]];
        }else if($userid>=0){
            $where_arr=[["o.userid=%u",$userid,-1]];
        }elseif( $config_courseid>0 ){
            $where_arr=[["config_courseid=%u",$config_courseid,-1]];
        }else{
            if($show_son_flag){//查看下级的
                $where_arr=[
                    ["is_test_user=%u" , $is_test_user, -1],
                    ["check_money_flag=%u" , $check_money_flag, -1],
                    ["stu_from_type=%u" , $stu_from_type, -1],
                    ["need_receipt=%u" , $need_receipt, -1],
                    ["l.teacherid=%u" , $teacherid, -1],
                ];
            }else{
                $where_arr=[
                    ["is_test_user=%u" , $is_test_user, -1],
                    ["check_money_flag=%u" , $check_money_flag, -1],
                    ["stu_from_type=%u" , $stu_from_type, -1],
                    ["need_receipt=%u" , $need_receipt, -1],
                    ["o.sys_operator like '%%%s%%'" , $sys_operator, ""],
                    ["l.teacherid=%u" , $teacherid, -1],
                ];
            }
            $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
            $this->where_arr_add__2_setid_field($where_arr,"s.assistantid",$assistantid);
            $this->where_arr_add_int_or_idlist($where_arr,"s.grade",$grade);
            $this->where_arr_add_int_or_idlist($where_arr,"o.subject",$subject);
            $this->where_arr_add_int_or_idlist($where_arr,"m2.uid",$adminid);
            $this->where_arr_add_boolean_for_value($where_arr,"f.flowid", $spec_flag ,true);
            $this->where_arr_add_boolean_for_value_false($where_arr,"promotion_spec_is_not_spec_flag", $spec_flag ,true);
            if ($order_activity_type != -1 ) {
                $sub_where_arr =[
                    ["order_activity_type=%u", $order_activity_type , -1 ],
                    "succ_flag=1"
                ];
                $this->where_arr_add_time_range($sub_where_arr,$opt_date_str,$start_time,$end_time);

                $where_arr[]= $this->gen_sql_new(
                    "o.orderid in (select s_o.orderid from %s s_o join %s soa on s_o.orderid=soa.orderid where %s)"
                    ,self::DB_TABLE_NAME
                    ,t_order_activity_info::DB_TABLE_NAME
                    ,$sub_where_arr
                );
            }

            if ($contract_type==-2) {
                $where_arr[]="contract_type in(0,1,3)" ;
            }else if ( $contract_type==-3){
                $where_arr[]="contract_type in(0,3)" ;
            }else {
                $this->where_arr_add_int_or_idlist($where_arr, "contract_type", $contract_type );
            }

            if ($contract_status ==-2) {
                $where_arr[] = "contract_status <> 0";
            }else{
                $this->where_arr_add_int_or_idlist($where_arr, "contract_status", $contract_status);
            }

            $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
            $this->where_arr_add__2_setid_field($where_arr,"origin_userid", $origin_userid);

            if ($has_money ==0) {
               $where_arr[]="o.price=0" ;
            }else if ($has_money ==1) {
                $where_arr[]="o.price>0" ;
            }
            $where_arr[]=$this->where_get_in_str("m2.uid", $require_adminid_list );
        }

        if($up_master_adminid != -1){
            $where_arr[]="s.ass_master_adminid=".$account_id;
        }

        if (!$show_yueyue_flag) {
            $where_arr[]="o.sys_operator <>'yueyue' ";
        }

        if ($origin) {
            $where_arr[]= [ "s.origin like '%%%s%%'" , $this->ensql($origin) ];
        }

        //http:7u2f5q.com2.z0.glb.qiniucdn.com/8008e863f1b6151890ccf278f711ab691460108026469.png
        //select count(*) from t_order_info as o,t_book_info as s where o.userid=s.userid, s.origin like "%APP课程包%" ;
        $where_arr[] = ["m2.account_role = %u" , $account_role, -1];
        if ($referral_adminid>0) {
            $where_arr[] = ["s.origin_assistantid = %u ", $referral_adminid] ;
        }

        $sql = $this->gen_sql_new(
            "select  order_price_desc, promotion_spec_is_not_spec_flag,promotion_spec_diff_money,origin_assistantid,"
            ." from_parent_order_type,s.lesson_count_all,o.userid,get_packge_time,order_stamp_flag,"
            ." f.flowid,f.flow_status,f.post_msg as flow_post_msg,l.teacherid,l.lesson_start,l.lesson_end,tmk_adminid,s.user_agent,"
            ." o.orderid,order_time,o.stu_from_type, is_new_stu,contractid,"
            ." o.from_key,o.from_url,"
            ." contract_type,contract_status,invoice,is_invoice, "
            ." contract_starttime,taobao_orderid, o.default_lesson_count, "
            ." contract_endtime,o.grade,o.lesson_total,o.price,discount_price,discount_reason,"
            ." s.phone_location,o.userid,o.competition_flag,o.lesson_left ,"
            ." s.address,s.origin_userid,ti.except_lesson_count,ti.week_lesson_num,"
            ." s.realname as stu_nick,s.ass_assign_time, o.subject, s.nick as stu_self_nick, "
            ." s.parent_name as parent_nick,s.phone,o.origin,o.sys_operator,o.from_type,"
            ." o.config_lesson_account_id ,o.config_courseid,  check_money_flag,check_money_time,"
            ." check_money_adminid,check_money_desc,s.assistantid,s.init_info_pdf_url,title,"
            ." need_receipt, order_promotion_type, promotion_discount_price, promotion_present_lesson, "
            ." promotion_spec_discount, promotion_spec_present_lesson ,lesson_start,"
            ." s.ass_master_adminid,m.account master_nick, pdf_url ,pre_price, pre_pay_time, pre_from_orderno, "
            ." if(co.child_order_type=2,1,0) is_staged_flag,o.can_period_flag"
            ." from %s o "
            ." left join %s s on o.userid = s.userid "
            ." left join %s c on o.orderid = c.orderid "
            ." left join %s n on o.userid = n.userid "
            ." left join %s l on l.lessonid = o.from_test_lesson_id "
            ." left join %s f on ( f.from_key_int = o.orderid  and f.flow_type in ( 2002, 3002))"
            ." left join %s m on s.ass_master_adminid = m.uid"
            ." left join %s m2 on o.sys_operator = m2.account"
            ." left join %s ti on o.userid = ti.userid"
            ." left join %s co on (co.parent_orderid = o.orderid and co.child_order_type = 2)"
            ." where %s "
            ." group by o.orderid "
            ." $order_by_str ",
            self::DB_TABLE_NAME, //o
            t_student_info::DB_TABLE_NAME, //s
            t_course_order::DB_TABLE_NAME, //c
            t_seller_student_new::DB_TABLE_NAME, //n
            t_lesson_info::DB_TABLE_NAME, //l
            t_flow::DB_TABLE_NAME, //f
            t_manager_info::DB_TABLE_NAME, //m
            t_manager_info::DB_TABLE_NAME, //m2
            t_student_init_info::DB_TABLE_NAME, //ti
            t_child_order_info::DB_TABLE_NAME, //co
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function get_cc_order_count($where_arr){
        $sql = $this->gen_sql_new(
            "select  count(distinct o.orderid) as count_order from %s o "
            ." left join %s s on o.userid = s.userid "
            ." left join %s t3 on o.sys_operator = t3.account "
            ." left join %s c on o.orderid = c.orderid "
            ." left join %s n on o.userid = n.userid "
            ." left join %s l on l.lessonid = o.from_test_lesson_id "
            ." left join %s f on ( f.from_key_int = o.orderid  and f.flow_type in ( 2002, 3002))"
            ." left join %s m on s.ass_master_adminid = m.uid"
            ." left join %s m2 on o.sys_operator = m2.account"
            ." left join %s ti on o.userid = ti.userid"
            ." left join %s co on (co.parent_orderid = o.orderid and co.child_order_type = 2)"
            ." where %s ",
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
            t_child_order_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_order_list_require_adminid_new(
        $page_num,$start_time,$end_time,$contract_type,$contract_status
        ,$userid,$config_courseid,$is_test_user,$show_yueyue_flag,$has_money
        ,$check_money_flag=-1,$assistantid=-1,$origin="",$stu_from_type=-1,$son_adminid_arr
        ,$account_role=-1,$grade=-1,$subject=-1,$tmk_adminid=-1, $need_receipt=-1
        ,$teacherid=-1,$up_master_adminid=-1,$account_id=74,$require_adminid_list=[],$origin_userid=-1, $referral_adminid=-1,
        $opt_date_str="order_time" , $order_by_str= " t2.assistantid asc , order_time desc"
        ,$spec_flag=-1
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
                ["l.teacherid=%u" , $teacherid, -1],
            ];
            $this->where_arr_add_int_or_idlist($where_arr,'t3.uid',$son_adminid_arr);
            $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);

            $this->where_arr_add__2_setid_field($where_arr,"t2.assistantid",$assistantid);
            $this->where_arr_add_boolean_for_value($where_arr,"f.flowid", $spec_flag ,true);
            $this->where_arr_add_boolean_for_value_false($where_arr,"promotion_spec_is_not_spec_flag", $spec_flag ,true);

            if ($contract_type==-2) {
                $where_arr[]="contract_type in(0,1,3)" ;
                \App\Helper\Utils::logger("stu");

            }else if ( $contract_type==-3){
                $where_arr[]="contract_type in(0,3)" ;
            }else {
                $where_arr[]=["contract_type=%u" , $contract_type, -1];
            }

            if ($contract_status ==-2) {
                $where_arr[] = "contract_status <> 0";
            }else{
                $where_arr[]=["contract_status=%u",$contract_status,-1];
                // $this->where_get_in_str_query("contract_status",$contract_status);
            }

            $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
            $this->where_arr_add__2_setid_field($where_arr,"origin_userid", $origin_userid);

            if ($has_money ==0) {
               $where_arr[]="price=0" ;
            }else if ($has_money ==1) {
               $where_arr[]="price>0" ;
            }
            $where_arr[]=$this->where_get_in_str("m2.uid", $require_adminid_list );
        }

        if($up_master_adminid != -1){
            $where_arr[]="t2.ass_master_adminid=".$account_id;
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
        if ($referral_adminid>0) {
            $where_arr[] = ["t2.origin_assistantid = %u ", $referral_adminid] ;
        }
        // $where_arr[] = ["t3.account_role = %u" , $account_role, -1];

        $sql = $this->gen_sql_new(
            "select order_price_desc, promotion_spec_is_not_spec_flag,promotion_spec_diff_money,origin_assistantid,"
            ." from_parent_order_type,t2.lesson_count_all,t1.userid,get_packge_time,order_stamp_flag,"
            ." f.flowid,f.flow_status,f.post_msg as flow_post_msg,l.teacherid,l.lesson_start,l.lesson_end,tmk_adminid,t2.user_agent,"
            ." t1.orderid,order_time,t1.stu_from_type, is_new_stu,contractid,"
            ." t1.from_key,t1.from_url,"
            ." contract_type,contract_status,invoice,is_invoice, "
            ." contract_starttime,taobao_orderid, t1.default_lesson_count, "
            ." contract_endtime,t1.grade,t1.lesson_total,price,discount_price,discount_reason,"
            ." t2.phone_location,t1.userid,t1.competition_flag,t1.lesson_left ,"
            ." t2.address,t2.origin_userid,ti.except_lesson_count,ti.week_lesson_num,"
            ." t2.realname as stu_nick,t2.ass_assign_time, t1.subject, t2.nick as stu_self_nick, "
            ." t2.parent_name as parent_nick,t2.phone,t1.origin,t1.sys_operator,t1.from_type,"
            ." t1.config_lesson_account_id ,t1.config_courseid,  check_money_flag,check_money_time,"
            ." check_money_adminid,check_money_desc,t2.assistantid,t2.init_info_pdf_url,title,"
            ." need_receipt, order_promotion_type, promotion_discount_price, promotion_present_lesson, "
            ." promotion_spec_discount, promotion_spec_present_lesson ,lesson_start,t1.can_period_flag,"
            ." t2.ass_master_adminid,m.account master_nick, pdf_url ,pre_price, pre_pay_time, pre_from_orderno "
            ." from %s t1 "
            ." left join %s t2 on t1.userid = t2.userid "
            ." left join %s t3 on t1.sys_operator = t3.account "
            ." left join %s c on t1.orderid = c.orderid "
            ." left join %s n on t1.userid = n.userid "
            ." left join %s l on l.lessonid = t1.from_test_lesson_id "
            ." left join %s f on ( f.from_key_int = t1.orderid  and f.flow_type in ( 2002, 3002))"
            ." left join %s m on t2.ass_master_adminid = m.uid"
            ." left join %s m2 on t1.sys_operator = m2.account"
            ." left join %s ti on t1.userid = ti.userid"
            ." where %s "
            ." order by $order_by_str ",
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
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }


    public function get_all_user_info(){
        $sql = $this->gen_sql_new("select count(distinct userid) from %s where contract_type =0",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_xf_user_info(){
        $sql = $this->gen_sql_new("select count(distinct o.userid) "
                                  ." from %s o "
                                  ." join %s r on o.userid = r.userid"
                                  ." join %s m on m.account = r.sys_operator"
                                  ." where m.account_role=2 and o.contract_type =3 and r.contract_type=0 and o.order_time = (select min(order_time) from %s where userid= o.userid and contract_type =3) and o.order_time > r.order_time and o.order_time < (r.order_time+180*86400) ",
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_price_avg(){
        $time= strtotime(date("2017-01-01"));
        $sql = $this->gen_sql_new("select sum(price) all_price,count(*) lesson_all from %s where contract_type in (0,3) and order_time>%u",self::DB_TABLE_NAME,$time);
        return $this->main_get_row($sql);
   }



    public function tongji_tmk_order_count_origin( $field_name,$start_time,$end_time,$adminid_list=[],$tmk_adminid=-1 ,$origin_ex="", $origin_level=-1,$wx_invaild_flag=-1) {

        $this->switch_tongji_database();

        switch ( $field_name ) {
        case "origin" :
            $field_name="o.origin";
            break;

        case "grade" :
            $field_name="o.grade";
            break;
        default:
            break;
        }


        $where_arr=[
            "contract_type = 0 ",
            "is_test_user=0",
            "contract_status >0 ",
            "tmk_adminid >0 ",
            "o.order_time>n.tmk_assign_time"
        ];



        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"m.uid",$adminid_list);
        $this->where_arr_add_int_or_idlist($where_arr,"s.origin_level",$origin_level);

        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        //wx
        $this->where_arr_add_int_field($where_arr,"wx_invaild_flag",$wx_invaild_flag);

        $sql = $this->gen_sql_new(
            "select $field_name as check_value ,count(*) as order_count,sum(price)/100 as order_all_money "
            ." from   %s  o  "
            ." left join %s m  on o.sys_operator=m.account "
            ." left join %s s  on o.userid=s.userid "
            ." left join %s n  on o.userid=n.userid "
            ." where %s "
            ." group by  check_value  ",
            self::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME ,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr );

        return $this->main_get_list($sql);
    }




    public function get_tmk_order_by_adminid( $start_time,$end_time,$tmk_adminid=-1,$page_num ) {

        $where_arr=[
            "contract_type = 0 ",
            "is_test_user=0",
            "contract_status >0 ",
            "tmk_adminid >0 ",
            "o.order_time>n.tmk_assign_time "
        ];

        $end_time = $end_time+86400;

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);

        $sql = $this->gen_sql_new(
            " select tmk_adminid as check_value, price/100 as order_money,o.orderid,order_time,"
            ." s.nick,o.sys_operator,n.tmk_assign_time "
            ." from %s o  "
            ." left join %s m on o.sys_operator=m.account "
            ." left join %s s on o.userid=s.userid "
            ." left join %s n on o.userid=n.userid "
            ." where %s ",
            self::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME ,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,30);
    }

    public function get_new_order($start_time,$end_time){
        $where_arr = [
            ["o.pay_time>%u",$start_time,0],
            ["o.pay_time<%u",$end_time,0],
            "o.contract_type=0",
            "o.contract_status>0",
        ];
        $sql = $this->gen_sql_new("select sum(o.default_lesson_count*o.lesson_total) as lesson_total,s.nick,s.grade,"
                                  ." sum(o.price) as price,sum(o.discount_price) as discount_price,"
                                  ." sum(o2.default_lesson_count*o2.lesson_total) as free_lesson_total"
                                  ." from %s o"
                                  ." left join %s s on o.userid=s.userid"
                                  ." left join %s o2 on s.userid=o2.userid and  o2.contract_type=1 and o2.contract_status=1"
                                  ." where %s"
                                  ." group by o.userid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_order_unit_price($start_time,$end_time){
        $where_arr=[
            "o.contract_type in (0,3)",
            "o.lesson_total>0 and o.default_lesson_count>0",
            //"((o.unit_price=0 or s.unit_price=0) or (o.unit_price=0 and s.unit_price is null))",
            "o.price>100"
        ];
        $this->where_arr_add_time_range($where_arr,"o.order_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select o.orderid,o.contract_type,o.price,o.lesson_total,o.default_lesson_count,s.lesson_total lesson_total_sub,s.default_lesson_count default_lesson_count_sub ,s.contract_type contract_type_sub,o.order_time,s.orderid orderid_sub,o.unit_price "
                                  ." from %s o left join %s s on o.orderid = s.parent_order_id "
                                  ." where %s order by o.order_time",
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_agent_order_info($userid,$create_time ) {

        $check_time=strtotime("2017-10-30");
        $where_arr = [
            ["order_time > %u ", $create_time,0 ],
            'order_status in (1,2)',
            "((contract_type in (0,3) and  order_time < $check_time ) or contract_type in (0)  )",
            ['userid=%u',  $userid ],
        ];
        $sql= $this->gen_sql_new("select pay_time, orderid, price from %s where %s limit 1 ",
                                 self::DB_TABLE_NAME, $where_arr);
        return $this->main_get_row($sql);

    }

    public function get_agent_order_info_new($userid,$create_time) {
        $where_arr = [
            ["order_time > %u ", $create_time,0 ],
            'order_status in (1,2)',
            'contract_type =0 ',
            ['userid=%u',  $userid ],
        ];
        $sql= $this->gen_sql_new("select pay_time, orderid, price from %s where %s",
                                 self::DB_TABLE_NAME, $where_arr);
        return $this->main_get_list($sql);

    }

    public function get_agent_order($orderid_array_str,$userid_array_str){
        $where_arr = [
            "o.orderid not in (".$orderid_array_str.")",
            "o.userid in (".$userid_array_str.")",
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];
        $sql = $this->gen_sql_new(
            " select o.orderid,o.price,s.phone,a1.parentid pid,a1.phone p_phone,a2.parentid ppid,a2.phone pp_phone "
            ." from %s o "
            ." left join %s s on s.userid = o.userid "
            ." left join %s a1 on a1.phone = s.phone "
            ." left join %s a2 on a2.id = a1.parentid "
            ." where %s ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_saml(){
        $sql =$this->gen_sql_new(" select userid from %s where userid in (select userid from %s group by userid having count(userid) > 1) order by order_time desc limit 10",
                                 self::DB_TABLE_NAME,
                                 self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);

        //select * from people
        // where peopleId in (select peopleId from people group by peopleId having count(peopleId) > 1)

    }

    public function get_student_info_by_orderid($orderid){
        $sql = $this->gen_sql_new(" select s.nick, s.address, s.phone, s.userid  from %s o ".
                                  " left join %s s on s.userid=o.userid".
                                  " where orderid = %d",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $orderid
        );


        return $this->main_get_row($sql);
    }


    public function get_app_by_orderid($orderid){
        $sql = $this->gen_sql_new("select applicant from %s where orderid = $orderid",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_contract_pdf_by_orderid($orderid){
        $sql = $this->gen_sql_new(" select o.addressee as nick, o.receive_addr as address, o.receive_phone as phone, o.lesson_weeks, o.lesson_duration, o.app_time,s.userid  from %s o left join %s s on s.userid=o.userid".
                                  " where orderid = %d",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $orderid
        );


        return $this->main_get_row($sql);

    }

    public function get_contract_mail_by_orderid($orderid){
        $sql = $this->gen_sql_new("select main_send_admin,mail_send_time,mail_code,mail_code_url,is_send_flag from %s where orderid = %d",
                                  self::DB_TABLE_NAME,
                                  $orderid
        );

        return $this->main_get_row($sql);
    }


    public function get_order_info_by_userid($sid){
        $sql = $this->gen_sql_new(" select competition_flag, o.orderid from %s o ".
                                  " left join %s s on o.orderid=s.orderid  ".
                                  " where o.userid = %d and reject_flag = 0 order by post_time desc",
                                  self::DB_TABLE_NAME,
                                  t_student_cc_to_cr::DB_TABLE_NAME,
                                  $sid
        );

        return $this->main_get_list($sql,function($item){
            return $item['orderid'];
        });
    }

    public function get_master_openid_by_orderid($orderid){
        $sql = $this->gen_sql_new(" select s.userid,m.wx_openid, s.nick from %s o ".
                                  " left join %s s on s.userid= o.userid ".
                                  " left join %s m on m.uid = s.ass_master_adminid ".
                                  " where o.orderid=$orderid",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME
        );


        return $this->main_get_row($sql);
    }

    public function get_origin_order($start,$end,$origin){
        $where_arr = [
            ["pay_time>%u",$start,0],
            ["pay_time<%u",$end,0],
            ["o.origin like '%%%s%%'",$origin,""],
            "contract_status=1",
            "contract_type in (0,1,3)",
        ];
        $sql = $this->gen_sql_new("select s.userid,s.nick,s.phone,s.phone_location,s.grade,"
                                  ." o.orderid,o.lesson_total,o.default_lesson_count,o.origin,o.price,o.pay_time,o.contract_type,"
                                  ." o.sys_operator,o.origin"
                                  ." from %s o"
                                  ." left join %s s on o.userid=s.userid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_origin_user_list(){
        $sql = $this->gen_sql_new("select s1.origin_userid,s2.phone,s2.nick,m.name as seller_nick,a.nick as ass_nick"
                                  ." from %s s1"
                                  ." left join %s s2 on s1.origin_userid=s2.userid"
                                  ." left join %s a on s2.assistantid=a.assistantid"
                                  ." left join %s m on s2.seller_adminid=m.uid"
                                  ." where exists ("
                                  ." select 1 from %s where s1.userid=userid and contract_type=0 and contract_status>0 "
                                  ." )"
                                  ." and s1.is_test_user=0"
                                  ." and s2.is_test_user=0"
                                  ." group by s1.origin_userid"
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_user_split_total($userid,$competition_flag){
        $where_arr = [
            ["o1.userid=%u",$userid,-1],
            ["o1.competition_flag=%u",$competition_flag,-1],
        ];
        $sql = $this->gen_sql_new("select sum(o2.from_parent_order_lesson_count)/100 "
                                  ." from %s o1"
                                  ." left join %s o2 on o1.orderid=o2.parent_order_id "
                                  ." and o2.contract_type=1 "
                                  ." and o2.from_parent_order_type=5"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_renw_order($userid,$time){
        $where_arr = [
            "contract_type in (3,3001)",
            "contract_status > 0",
            ["userid = %u",$userid,-1],
            ["order_time>%u",$time,0]
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_stu_order_list($start,$end){
        $where_arr = [
            ["pay_time>%u",$start,0],
            ["pay_time<%u",$end,0],
            "is_test_user=0",
            "contract_type in (0,3)",
            "contract_status >0"
        ];
        $sql = $this->gen_sql_new("select s.userid,s.phone,s.nick,s.realname,s.grade,min(pay_time) as first_time,o.subject,"
                                  ." sum(o.lesson_total*default_lesson_count) as lesson_total"
                                  ." from %s o"
                                  ." left join %s s on o.userid=s.userid"
                                  ." where %s"
                                  ." group by s.userid,o.subject"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_order_group_by_id($start_time, $end_time) {
        $where_arr = [
            ['pay_time>= %s', $start_time, 0],
            ['pay_time<%s', $end_time, 0],
            "o.contract_type in (0,3)",
            "o.contract_status >0"
        ];

        $sql = $this->gen_sql_new("select distinct ll.ip ,p.phone"
                                  ." from %s o"
                                  ." left join %s ll on ll.userid=o.userid"
                                  ." left join %s pc on pc.userid=ll.userid"
                                  ." left join %s p on p.parentid=pc.parentid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_user_login_log::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_phont_by_ip() {
        $sql = $this->gen_sql_new("select distinct ll.ip ,p.phone"
                                  // ." from %s o"
                                  ." from %s ll"
                                  // ." left join %s ll on ll.userid=o.userid"
                                  ." left join %s pc on pc.userid=ll.userid"
                                  ." left join %s p on p.parentid=pc.parentid"
                                  // ,self::DB_TABLE_NAME
                                  ,t_user_login_log::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_spec_diff_money_all($start_time, $end_time,$account_role , $contract_status = -1 ) {
        $where_arr=[
            ["m.account_role=%u", $account_role, -1  ],
            "promotion_spec_is_not_spec_flag=0",
            "is_test_user=0",
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"contract_status",$contract_status);

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(
            "select  sum(promotion_spec_diff_money)/100 "
            . "from %s o"
            ." left join %s m on m.account=o.sys_operator "
            ." left join %s s on s.userid=o.userid "
            ." left join %s f on ( f.from_key_int = o.orderid  and f.flow_type in ( 2002 ))"
            . " where %s  ",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr
        );
        return intval($this->main_get_value($sql));
    }

    public function get_spec_diff_money_all_new($start_time, $end_time,$account_role , $contract_status = -1 ) {
        $where_arr=[
            ["m.account_role=%u", $account_role, -1  ],
            "promotion_spec_is_not_spec_flag=0",
            "is_test_user=0",
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"contract_status",$contract_status);
        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select m.uid adminid,sum(promotion_spec_diff_money/100) diff_money "
            . "from %s o"
            ." left join %s m on m.account=o.sys_operator "
            ." left join %s s on s.userid=o.userid "
            ." left join %s f on ( f.from_key_int = o.orderid  and f.flow_type in ( 2002 ))"
            . " where %s group by m.uid ",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_teacherid_subject_by_orderid($orderid_str){
        $where_arr = [
            "o.orderid in $orderid_str",
            "o.contract_type not in (1,2)",
        ];
        $sql = $this->gen_sql_new("select distinct o.orderid, t.teacherid, t.nick, l.subject"
                                  ." from %s o"
                                  ." left join %s ol on o.orderid=ol.orderid"
                                  ." left join %s l on l.lessonid=ol.lessonid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_lesson_list::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_nomal_order_by_userid($userid,$check_time=-1){
        $where_arr = [
            "o.userid = $userid",
            "o.contract_type = 0",
            "o.contract_status in (1,2)",
            ["o.pay_time <%u" ,$check_time, -1 ]
        ];
        //E\Econtract_status
        $sql = $this->gen_sql_new("select o.orderid "
                                  ." from %s o"
                                  ." where %s limit 1 "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_agent_order_money_list($userid_list ) {
        if (count($userid_list)==0) {
            return [];
        }
        $where_arr=[
            "o.order_time > a.create_time ",
            "o.contract_status in (1,2)",
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"o.userid",$userid_list);

        $sql = $this->gen_sql_new(
            "select a.userid ,sum(price) as price from %s o  "
            ."join %s a on a.userid=o.userid  "
            . " where %s  group by a.userid ",
            self::DB_TABLE_NAME,
            t_agent::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["userid"];
        });

    }

    public function get_order_stu_acc_info($start_time, $end_time) {
        $where_arr=[
            ["o.order_time>=%s", $start_time, 0],
            ["o.order_time<%s", $end_time, 0],
            "o.contract_type=0",
        ];
        $sql = $this->gen_sql_new(
            "select o.orderid,o.sys_operator,o.origin,o.price,o.lesson_total,o.default_lesson_count,"
            ." s.phone,s.phone_location,o.order_time"
            // .",m.name,m.account_role,"
            // ." tq.start_time,tq.is_called_phone,tq.uid"
            ." from %s o"
            ." left join %s s on o.userid=s.userid"
            // ." left join %s tq on s.phone=tq.phone"
            // ." left join %s m on tq.uid=m.uid"
            ." where %s"
            ." order by o.order_time"
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            // ,t_tq_call_info::DB_TABLE_NAME
            // ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_open_order_list($start,$end){
        $where_arr = [
            ["pay_time>%u",$start,0],
            ["pay_time<%u",$end,0],
            "contract_type in (0,3)",
            "contract_status=1",
        ];
        $sql = $this->gen_sql_new("select s.userid,s.grade"
                                  ." from %s o"
                                  ." left join %s s on o.userid=s.userid"
                                  ." where %s"
                                  ." group by s.userid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_userid_by_pay_time($start_time, $end_time) {

        $where_arr = [
            ["o.pay_time>=%u",$start_time,0],
            ["o.pay_time<%u",$end_time,0],
            "o.contract_type=0",
            "o.contract_status=1",
            "s.is_test_user=0",
            // "s.grade>200",
        ];
        $sql = $this->gen_sql_new(
            "select distinct s.userid,s.grade"
            ." from %s o"
            ." left join %s s on o.userid=s.userid"
            ." where %s"
            // ." group by s.userid"
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    //get_new_income

    public function get_new_income($start_time, $end_time){

        $where_arr = [
            "is_test_user=0",
            "o.stu_from_type=0",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select sum(price)/100 as all_price, count(*) as all_count "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }



    //


    public function get_income_for_month($start_time, $end_time){

        $where_arr = [
            "is_test_user=0",
            "o.stu_from_type in (0,1)",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select sum(price)/100 as all_price,count(*)as all_count  "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }


    public function get_income_num( $start_time,$end_time ) {

        $where_arr = [
            "is_test_user=0",
            "contract_type =0 ",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select count(distinct(sys_operator)) as income_num "
                                  ." from %s o "
                                  ." left join %s s on o.userid = s.userid "
                                  ." left join %s n on n.userid = s.userid "
                                  ." left join %s m on o.sys_operator = m.account "
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }




    public function get_formal_order_info( $start_time,$end_time ) {

        $check_time = time() - 30*86400;
        $where_arr = [
            "is_test_user=0",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
            "m.leave_member_time>=$start_time or m.leave_member_time=0 or m.create_time<$start_time ",
            "m.del_flag=0",
            "o.price>0",
            "contract_status<>0",
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select  sum(price)/100 as job_price, count(distinct(o.sys_operator)) as job_num "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ." where %s   ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_referral_money_for_month($start_time, $end_time){ // 转介绍

        $where_arr = [
            "is_test_user=0",
            "o.stu_from_type=1",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select sum(price)/100 as all_price "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_high_money_for_month($start_time, $end_time){ // 高中

        $where_arr = [
            "is_test_user=0",
            "o.grade>=300",
            "o.grade<400",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
            "o.price>0"
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select sum(price)/100 as all_price "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }



    public function get_junior_money_for_month($start_time, $end_time){ // 初中  //Primary

        $where_arr = [
            "is_test_user=0",
            "o.grade>=200",
            "o.grade<300",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select sum(price)/100 as all_price "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_primary_money_for_month($start_time, $end_time){  //小学

        $where_arr = [
            "is_test_user=0",
            "o.grade>=100",
            "o.grade<200",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
            "o.price>0"
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select sum(price)/100 as all_price "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_new_order_num($start_time, $end_time){
        $where_arr = [
            "is_test_user=0",
            "o.stu_from_type = 0",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(*) as all_count  "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_value($sql);

    }


    public function get_order_num($start_time, $end_time){
        $where_arr = [
            "is_test_user=0",
            "o.stu_from_type = 0",
            "m.account_role=2",
            "sys_operator<>'jim'",
            "contract_status <> 0",
        ];

        $this->where_arr_add_time_range($where_arr,"order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(distinct(o.userid)) as order_num  "
                                  ." from %s o "
                                  ."left join %s s on o.userid = s.userid "
                                  ."left join %s n on n.userid = s.userid "
                                  ."left join %s m on o.sys_operator = m.account "
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_no_pay_order_list(){
        $sql = $this->gen_sql_new("select orderid,contract_type,contract_status,pre_price,channel,"
                           ."pre_price,pre_pay_time,pre_from_orderno,price,pay_time,from_orderno "
                           ." from %s"
                           ." where contract_type in (0,3) and contract_status>0",
                           self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_total_price($start_time,$end_time){
        $where_arr = [
            ['order_time>%u',$start_time,-1],
            ['order_time<%u',$end_time,-1],
            "contract_status <> 0",
            "price > 0",
            "m.account_role = 1"
        ];
        $sql = $this->gen_sql_new("select sum(price) as total_price, count(distinct(sys_operator )) as person_num,"
                                  ."count(orderid ) as order_num , sum(if(contract_type  =3, price, 0)) as total_renew,"
                                  ."sum(if(contract_type =3, 1, 0)) as renew_num,sum(if(origin_userid <> 0, 1, 0)) as tranfer_num,"
                                  ."sum(if(origin_userid <> 0, price, 0)) as total_tranfer  "
                                  ." from %s o "
                                  ." left join %s m on o.sys_operator = m.account"
                                  ." left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_row($sql);
    }
    public function get_total_price_new($start_time,$end_time){
        $where_arr = [
            ['order_time>%u',$start_time,-1],
            ['order_time<%u',$end_time,-1],
            "contract_status <> 0",
            "price > 0",
            "m.account_role = 1"
        ];
        $sql = $this->gen_sql_new("select sum(price) as total_price"
                                  ." from %s o "
                                  ." left join %s m on o.sys_operator = m.account"
                                  ." left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_value($sql);
    }

    public function get_total_price_thirty($start_time,$end_time){
        $where_arr = [
            ['order_time>%u',$start_time,-1],
            ['order_time<%u',$end_time,-1],
            ['m.create_time+86400*29 < %u',$end_time,-1], //大于订单时间
            "contract_status <> 0",
            "price > 0",
            "m.account_role = 1",
            " ( m.leave_member_time =0 or m.leave_member_time > $start_time)" //离职时间
        ];
        $sql = $this->gen_sql_new("select sum(price) as total_price, count(distinct(sys_operator )) as person_num ".
                                  "from %s o ".
                                  "left join %s m on o.sys_operator = m.account".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                   $where_arr);
        return $this->main_get_row($sql);
    }

    public function get_new_order_money($start_time, $end_time){
        $where_arr = [
            "o.price>0",
            "contract_status<> 0",
            "m.account_role=2",
            "s.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,'o.order_time',$start_time,$end_time);

        $sql = $this->gen_sql_new( "  select sum(o.price)/100 total_price, count(distinct o.sys_operator) total_num, count(o.orderid) order_num_new   from %s o "
        // $sql = $this->gen_sql_new( "  select o.sys_operator as account from %s o "
                                   ." left join %s m on o.sys_operator = m.account "
                                   ." left join %s s on s.userid = o.userid"
                                   ." where %s"
                                   ,self::DB_TABLE_NAME
                                   ,t_manager_info::DB_TABLE_NAME
                                   ,t_student_info::DB_TABLE_NAME
                                   ,$where_arr
        );

        // return $this->main_get_list($sql);

        return $this->main_get_row($sql);

    }






    public function get_referral_income($start_time, $end_time){
        $where_arr = [
            "o.price>0",
            "contract_status<>0",
            "m.account_role=2",
            "s.origin_userid>0",
            "s.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,'o.order_time',$start_time,$end_time);

        $sql = $this->gen_sql_new( "  select sum(o.price)/100 referral_price, count(o.orderid) referral_num, count(distinct(o.sys_operator)) total_num from %s o "
                                   ." left join %s m on o.sys_operator = m.account "
                                   ." left join %s s on s.userid = o.userid"
                                   ." where %s"
                                   ,self::DB_TABLE_NAME
                                   ,t_manager_info::DB_TABLE_NAME
                                   ,t_student_info::DB_TABLE_NAME
                                   ,$where_arr
        );

        return $this->main_get_row($sql);
    }


    /*
     *@desn:获取cc不同类型合同的金额  [分期、非分期]
     *@date:2017-09-29
     *@author:Abner<abner@leo.edu.com>
     *@param:$admind : 下单人id
     *@param:$start_time : 开始时间
     *@param:$end_time : 结束时间
     *@return:Array [分期金额，非分期金额]
     */
    public function get_sort_order_count_money($adminid,$start_time,$end_time){
        $sys_operator= $this->t_manager_info->get_account($adminid);

        //获取分期金额
        $where_arr = [
            "o.contract_status in (1,2)",
            "o.contract_type = 0",
            [ "o.sys_operator='%s'",  $sys_operator, "XXXX" ],
            "o.price>0"
        ];

        $this->where_arr_add_time_range($where_arr,'o.order_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select sum( if( co.child_order_type =2, co.price,0) ) as stage_money,".
            " sum( if(co.child_order_type <> 2,co.price,0) ) as no_stage_money".
            " from %s co".
            " join %s o on co.parent_orderid = o.orderid".
            " where %s "
            ,t_child_order_info::DB_TABLE_NAME
            ,self::DB_TABLE_NAME

            ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_sort_order_count_money_new($adminid,$start_time,$end_time){
        $sys_operator= $this->t_manager_info->get_account($adminid);

        //获取分期金额
        $where_arr = [
            "o.contract_status in (1,2)",
            "o.contract_type = 0",
            [ "o.sys_operator='%s'",  $sys_operator, "XXXX" ],
            "o.price>0"
        ];

        $this->where_arr_add_time_range($where_arr,'o.order_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select co.child_orderid,co.parent_orderid,co.child_order_type,o.price ".
            " from %s co ".
            " join %s o on co.parent_orderid = o.orderid".
            " where %s "
            ,t_child_order_info::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_sort_order_count_money_new_two($start_time,$end_time){
        $where_arr = [
            "o.contract_status in (1,2)",
            "o.contract_type = 0",
            // [ "o.sys_operator='%s'",  $sys_operator, "XXXX" ],
            "o.price>0"
        ];

        $this->where_arr_add_time_range($where_arr,'o.order_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select co.child_orderid,co.parent_orderid,co.child_order_type,o.price ".
            " from %s co ".
            " left join %s o on co.parent_orderid = o.orderid".
            " where %s "
            ,t_child_order_info::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_cr_to_cc_order_num($start_time,$end_time){
        $where_arr = [
            "o.contract_status <> 0 ",
            "o.price > 0",
            "m.account_role = 2 ",
            "n.account_role = 1",
            [' o.order_time > %u',$start_time,-1],
            [' o.order_time < %u',$end_time,-1]
        ];
        $sql = $this->gen_sql_new("select sum(o.price) as total_price, count(o.orderid) as total_num"
                                  ." from %s o  "
                                  ." left join %s m ON o.sys_operator = m.account "
                                  ." left join %s s ON o.userid = s.userid  "
                                  ." left join %s k on k.userid = s.userid  "
                                  ." left join %s n on s.origin_assistantid = n.uid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_row($sql);
    }


    public function get_order_sign_month($start_time, $end_time){
        $where_arr = [
            "tq.is_called_phone=1",
            "tq.admin_role=2",
            "contract_status <> 0 ",
            "price>0",
            "contract_type=0"
        ];

        $this->where_arr_add_time_range($where_arr,"ss.add_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select count(distinct(o.userid)) from %s o "
                                  ." left join %s ss on ss.userid=o.userid"
                                  ." left join %s tq on tq.phone=ss.phone"
                                  ." left join %s ts on ts.userid=o.userid"
                                  ." left join %s tr on tr.test_lesson_subject_id=ts.test_lesson_subject_id"
                                  ." left join %s tss on tss.require_id=tr.require_id"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,t_tq_call_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_order_trans_month($start_time, $end_time){
        $where_arr = [
            "l.lesson_user_online_status=1",
            "ts.require_admin_type =2",
            "contract_status<> 0",
            "contract_type=0",
            "price>0"
        ];

        $this->where_arr_add_time_range($where_arr,"tss.set_lesson_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select count(distinct(o.userid)) from %s o "
                                  ." left join %s ss on ss.userid=o.userid"
                                  ." left join %s ts on ts.userid=ss.userid"
                                  ." left join %s tr on tr.test_lesson_subject_id=ts.test_lesson_subject_id"
                                  ." left join %s tss on tss.require_id=tr.require_id"
                                  ." left join %s l on l.lessonid=tss.lessonid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }
    public function get_renew_student_list($start_time,$end_time){
        $where_arr = [
            ['order_time>%u',$start_time,-1],
            ['order_time<%u',$end_time,-1],
            "is_test_user = 0 ",
            "contract_type = 3 ",
            "contract_status <> 0",
            "t1.price > 0"
        ];
        $sql = $this->gen_sql_new(" select t1.userid"
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.userid = t2.userid "
                                  ." left join %s f ON f.from_key_int = t1.orderid and  f.flow_type IN (2002, 3002)"
                                  ." left join %s co ON co.parent_orderid = t1.orderid and co.child_order_type = 2 "
                                  ." where %s order by t1.userid asc"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_flow::DB_TABLE_NAME
                                  ,t_child_order_info::DB_TABLE_NAME
                                  ,$where_arr);

        return $this->main_get_list($sql);
    }
    public function get_renew_student_list_new($start_time,$end_time){
        $where_arr = [
            ['order_time>%u',$start_time,-1],
            ['order_time<%u',$end_time,-1],
            "is_test_user = 0 ",
            "contract_type = 3 ",
            "contract_status <> 0",
            "t1.price > 0"
        ];
        $sql = $this->gen_sql_new(" select t1.userid"
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.userid = t2.userid "
                                  ." left join %s f ON f.from_key_int = t1.orderid and  f.flow_type IN (2002, 3002)"
                                  ." left join %s co ON co.parent_orderid = t1.orderid and co.child_order_type = 2 "
                                  ." where %s order by t1.userid asc"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_flow::DB_TABLE_NAME
                                  ,t_child_order_info::DB_TABLE_NAME
                                  ,$where_arr);
        //return $this->main_get_list($sql);
        return $this->main_get_list($sql,function( $item){
              return $item["userid"];
        } );
        //return $this->main_get_list($sql);
    }

    public function get_order_list_by_time($start_time,$end_time){
        $where_arr = [
            "contract_type in (0,3)"
        ];

        $this->where_arr_add_time_range($where_arr,"pay_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select orderid from %s "
                                  ." where contract_status>0 and order_status=1 and %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    // public function get_has_lesson_order_list($start_time,$end_time,$lesson_status=2){
    //     $where_arr=[
    //         ["lesson_start>%u",$start_time,-1],
    //         ["lesson_start<%u",$end_time,-1],
    //         ["lesson_status=%u",$lesson_status,-1],
    //     ];
    //     $sql=$this->gen_sql_new("select orderid,subject,grade,price,lesson_total,default_lesson_count,contract_type,lesson_left,"
    //                             ." competition_flag"
    //                             ." from %s o"
    //                             ." where %s "
    //                             ." and exists ("
    //                             ." select 1 from %s where %s"
    //                             ." )"
    //                             ,self::DB_TABLE_NAME
    //                             ,$where_arr
    //     );
    //     return $this->main_get_list($sql);
    // }


    public function get_test_new(){
        $sql = $this->gen_sql_new(
            "select group_name,m.uid,m.account,price/100 price "
            ." from %s o , %s s ,%s m, %s gu,%s g  "
            ."where  o.userid = s.userid   and    o.sys_operator =m.account   and    m.uid=gu.adminid  and    gu.groupid =g.groupid and     order_time>=1504195200 and order_time<=1506960000 and is_test_user=0 and g.groupid=84 and g.month=1504195200 and gu.month=1504195200 and contract_type in(0,3) and contract_status in(1,2) and stu_from_type=0 and m.account_role=2 ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_group_user_month::DB_TABLE_NAME,
            t_group_name_month::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_order_lesson_left_pre($userid,$order_time){
        $where_arr=[
            ["userid= %u",$userid,-1],
            "contract_status in (1,2)",
            "contract_type in (0,1,3)",
            "order_time<$order_time"
        ];
        $sql = $this->gen_sql_new("select sum(lesson_left) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_order_lesson_money_info($start_time,$end_time){
        $where_arr=[
            "o.contract_status>0",
            "o.contract_type in (0,1,3)",
            "o.check_money_flag=1",
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"o.order_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct o.userid) stu_num,sum(o.price) all_price,"
                                  ."sum(o.lesson_total*o.default_lesson_count) lesson_count_all"
                                  ." from %s o left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_order_lesson_money_use_info($start_time,$end_time){
        $where_arr=[
            "o.contract_status>0",
            "o.contract_type in (0,1,3)",
            "o.check_money_flag=1",
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"o.order_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select from_unixtime(l.lesson_start,'%%Y-%%m-01') time,"
                                  ."sum(ol.lesson_count) lesson_count_all,"
                                  ." sum(ol.price) all_price "
                                  ." from %s o  join %s ol on o.orderid = ol.orderid"
                                  ." join %s l on ol.lessonid = l.lessonid"
                                  ." join %s s on o.userid = s.userid"
                                  ." where %s group by time",
                                  self::DB_TABLE_NAME,
                                  t_order_lesson_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_renow_user_by_month($start_time, $end_time){
        $where_arr = [
            ["o.order_time>=%u", $start_time,-1],
            ["o.order_time<%u", $end_time,-1],
            'o.contract_type=3',
            'o.price>0',
            's.is_test_user=0'
        ];

        $sql = $this->gen_sql_new("select distinct o.userid"
                                  ." from %s o"
                                  ." left join %s s on s.userid=o.userid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function check_is_new($parentid,$order_start, $order_end){
        $where_arr = [
            "o.contract_type in (0,1,3)",
            ["p.parentid=%u",$parentid,0],
        ];

        $this->where_arr_add_time_range($where_arr,"o.order_time",$order_start,$order_end);
        $sql = $this->gen_sql_new("  select o.orderid from %s o "
                                  ." left join %s s on s.userid=o.userid"
                                  ." left join %s p on p.userid=s.userid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }


    public function check_is_buy($parentid){
        $sql = $this->gen_sql_new("  select o.orderid from %s o "
                                  ." left join %s p on p.userid=o.userid"
                                  ." where p.parentid=%d and o.contract_type in (0,1,3) and o.contract_status<>0"
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,$parentid
        );

        return $this->main_get_value($sql);
    }

    public function get_info_by_userid($userid){
        $sql = "select * from t_order_info where userid = $userid and order_time >1506787200 and price > 0;";
        return $this->main_get_row($sql);
    }

    public function buy_ten_flag($parentid){

        $where_arr = [
            "p.parentid=$parentid",
            "o.contract_type in (0,3)",
            "o.lesson_total >=10"
        ];

        $sql = $this->gen_sql_new("  select 1 from %s o"
                                  ."　left join %s s on s.userid=o.userid"
                                  ." left　join %s p on p.userid=s.userid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_assign_lesson_count_by_account($sys_operator){
        $where_arr=[
            "contract_type=1",
            "from_parent_order_type=6",
            ["sys_operator='%s'",$sys_operator,""]
        ];
        $sql = $this->gen_sql_new("select sum(default_lesson_count*lesson_total) from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }
   //@desn:获取某用户的下单量及下单金额之和
    //@param: $userid 用户id
    public function get_agent_order_sum($userid){
        $where_arr = [
            'order_status in (1,2)',
            'contract_type in (0,3) ',
            ['userid=%u',  $userid ],
        ];
        $sql = $this->gen_sql_new(
            "select count(orderid) as self_order_count,sum(price) as self_order_price ".
            "from %s where %s",self::DB_TABLE_NAME,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_all_cr_order_info($start_time,$end_time){
        $where_arr=[
            "o.contract_status>0",
            "o.contract_type in (0,1,3)",
            "o.check_money_flag=1",
            "s.is_test_user=0",
            "m.account_role=1"
        ];
        $sql = $this->gen_sql_new("select count(distinct m.uid) ass_num,sum(o.price) all_money"
                                  ." from %s o left join %s s on o.userid = s.userid"
                                  ." left join %s m on o.sys_operator = m.account"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_order_money_user_info($order_start,$order_end){
        $where_arr=[
            "o.contract_status>0",
            "o.contract_type in (0,3)",
            "o.check_money_flag=1",
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"o.order_time",$order_start,$order_end);
        $sql = $this->gen_sql_new("select sum(if(o.contract_type=0,price,0)) new_order_money,"
                                  ." sum(if(o.contract_type=3,price,0)) renew_order_money,"
                                  ." sum(if(o.contract_type=0,1,0)) new_order_stu,"
                                  ." sum(if(o.contract_type=3,1,0)) renew_order_stu"
                                  ." from %s o left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_stu_date_num($month_start,$month_end){
        /**
         * 1.     8,9,10 三个月的上过试听课且签单成功的学员
         * 2.     上试听课的老师与第一节常规课老师不匹配的学员
         * 3.      试听课的科目需要和第一节常规课相同
        */
        $where_arr = [
            " l.lesson_type = 2",
            "o.contract_status>0",
            "o.contract_type=0"
        ];

        $this->where_arr_add_time_range($where_arr, "l.lesson_start", $month_start, $month_end);

        //t_order_lesson_list
        $sql = $this->gen_sql_new("select l.userid, o.orderid, l.lessonid,l.grade, l.subject,l.teacherid,l.lesson_start "
                                  ." from %s o "
                                  ." join %s l on o.from_test_lesson_id=l.lessonid"
                                  ." where %s"
                                  ,t_order_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_orderid_by_userid($userid,$sys_operator){
        $where_arr = [
            ['userid=%u',$userid,-1],
            [ "sys_operator like '%%%s%%'" , $this->ensql($sys_operator)],
        ];
        $sql = $this->gen_sql_new("select orderid "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_orderid_by_userid_new($userid_arr){
        $where_arr = [
            'contract_type = 0',
            'contract_status > 0',
        ];
        $this->where_arr_add_int_or_idlist($where_arr,'userid', $userid_arr);
        $sql = $this->gen_sql_new("select userid,orderid "
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_seller_add_time_by_orderid_str($orderid_arr){
        $where_arr = [];
        $this->where_arr_add_int_or_idlist($where_arr,'o.orderid',$orderid_arr);
        $sql = $this->gen_sql_new("select orderid,n.add_time "
                                  ." from %s o "
                                  ." left join %s n on n.userid=o.userid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_cc_test_lesson_num($start_time, $end_time, $teacherid, $require_admin_type){
        $where_arr = [
            "l.lesson_del_flag=0",
            "l.lesson_type=2",
            "l.lesson_status=2",
            "l.teacherid=$teacherid",
            "l.confirm_flag in (0,1)",
            "o.contract_type in (0,1,3)",
            "o.contract_status>0",
            "tls.require_admin_type=$require_admin_type"
        ];
        $this->where_arr_add_time_range($where_arr, "l.lesson_start", $start_time, $end_time);
        $sql = $this->gen_sql_new("  select count(distinct(o.orderid)) order_num from %s l"
                                  ." left join %s o on l.lessonid=o.from_test_lesson_id"
                                  ." left join %s tll on tll.lessonid=l.lessonid"
                                  ." left join %s tlr on tlr.require_id=tll.require_id"
                                  ." left join %s tls on tls.test_lesson_subject_id=tlr.test_lesson_subject_id"
                                  ." where %s "
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_cc_lesson_num($start_time, $end_time, $teacherid, $require_admin_type){
        $where_arr = [
            "l.lesson_del_flag=0",
            "l.lesson_type=2",
            "l.lesson_status=2",
            "l.teacherid=$teacherid",
            "l.confirm_flag in (0,1)",
            "tls.require_admin_type=$require_admin_type"
        ];
        $this->where_arr_add_time_range($where_arr, "l.lesson_start", $start_time, $end_time);
        $sql = $this->gen_sql_new("  select count(distinct(l.lessonid)) lesson_num from %s l"
                                  ." left join %s tll on tll.lessonid=l.lessonid"
                                  ." left join %s tlr on tlr.require_id=tll.require_id"
                                  ." left join %s tls on tls.test_lesson_subject_id=tlr.test_lesson_subject_id"
                                  ." where %s "
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_order_info_del($start_time,$end_time){
        $where_arr = [
            "o.contract_type =0",
            "o.contract_status=0",
            "s.is_test_user=0",
            "o.check_money_flag =0"
        ];
        $this->where_arr_add_time_range($where_arr, "order_time", $start_time, $end_time);
        $sql = $this->gen_sql_new("select o.orderid,o.price"
                                  ." from %s o left join %s s on o.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_self_order_list($sys_operator){
        $where_arr=[
            ["sys_operator='%s'",$sys_operator,""]
        ];
        $sql = $this->gen_sql_new("select o.userid,s.nick,o.orderid"
                                  ." from %s o left join %s s on o.userid=s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }
    //@desn:获取签单时间为结点的漏斗数据
    public function get_funnel_data( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        if($field_name == 'grade')
            $field_name="si.grade";
        elseif($field_name == 'origin')
            $field_name = 'oi.origin';
        
        $where_arr=[
            ["oi.origin like '%%%s%%' ",$origin,""],
            'si.is_test_user = 0',
            'oi.contract_type = 0',
            'oi.contract_status > 0',
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"ssn.tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"oi.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"ssn.first_seller_adminid",$adminid_list);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value,count(oi.orderid) order_count,".
            " count(distinct(oi.userid)) user_count,round(sum(price)/100) order_all_money".
            " from %s oi ".
            " left join %s si on oi.userid = si.userid ".
            " left join %s ssn on oi.userid = ssn.userid ".
            " where %s group by check_value",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });

    }

    public function get_total_price_for_tq($adminid,$start_time,$end_time){
        // $where_arr = [
        //     "tq.adminid=$adminid",
        //     "o.contract_status =1",
        //     'o.price>0',
        // ];

        // $this->where_arr_add_time_range($where_arr, "tq.start_time", $start_time, $end_time);

        $sql = $this->gen_sql_new("  select sum(o.price)/100 as total_money from %s o "
                                  ." left join %s s on s.userid=o.userid"
                                  ." where  o.contract_status!=3 and o.order_time>$start_time and price>0 and s.phone in (select phone from %s tq1 where tq1.adminid=$adminid and tq1.start_time>$start_time and tq1.start_time<$end_time group by tq1.phone )"
                                  ,self::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,t_tq_call_info::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);

    }

    public function getOrderList($start_time, $end_time){
        $where_arr = [
            "m.leave_member_time=0",
            "m.account_role=2",
            "o.check_money_flag=1",
            "o.price>0",
            "o.contract_status in (1,2)",
        ];

        $this->where_arr_add_time_range($where_arr, "o.order_time", $start_time, $end_time);

        $sql = $this->gen_sql_new("  select o.order_time, o.check_money_time, o.price/100 as price_money, o.sys_operator, m.create_time from %s o "
                                  ." left join %s m on m.account=o.sys_operator"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    //@desn:获取订单信息
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    public function get_node_type_order_data($start_time,$end_time){
        $where_arr = [
            ['oi.contract_type = %u',0],
            ['si.is_test_user = %u',0],
            "oi.contract_status >0 ",
        ];
        $this->where_arr_add_time_range($where_arr, 'oi.order_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select si.origin as channel_name,count(oi.orderid) as order_count,'.
            'count(distinct oi.userid) as user_count,'.
            'round(sum(oi.price)/100) as order_all_money '.
            'from %s oi '.
            'left join %s si on oi.userid = si.userid '.
            'where %s group by si.origin',
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function check_is_have_competition_order($userid){
        $where_arr=[
            ["userid=%u",$userid,-1],
            "competition_flag=1",
            "contract_status=1",
            "contract_type in (0,1,3)"
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }


    //助教转介绍合同
    public function get_assistant_origin_order_losson_list($start_time,$end_time,$opt_date_str, $userid, $page_info , $sys_operator , $teacherid, $origin_userid ,$order_adminid,$assistantid ){               
        $where_arr=[
            ["o.sys_operator like '%%%s%%'" , $sys_operator, ""],
            ["l.teacherid=%u" , $teacherid, -1],
            ["a.assistantid = %u" , $assistantid, -1],
            ["m.uid = %u" , $order_adminid, -1],
            ["s.origin_userid = %u" , $origin_userid, -1],
            //  "o.contract_type=0",
            "o.price>0",
            "o.contract_status>0",
            "m.account_role=1",
            "s.origin_userid>0",
            // "s.origin_assistantid>0",
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $sql = $this->gen_sql_new("select s.nick,s.userid,l.lessonid,l.grade,l.subject,s.phone,t.realname,"
                                  ." l.teacherid,o.price,o.order_time,o.pay_time,o.sys_operator,m2.name "
                                  ." from %s o "
                                  ."left join %s l on o.userid=l.userid and l.lesson_type=2 and l.lesson_del_flag=0 and not exists( select 1 from %s where userid=l.userid and lesson_type=2 and lesson_del_flag=0 and lesson_start<l.lesson_start)"
                                  ." left join %s s on o.userid = s.userid"
                                  ." left join %s m on m.account = o.sys_operator"
                                  ." left join %s m2 on s.origin_assistantid = m2.uid "
                                  ." left join %s a on a.phone = m2.phone "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s and not exists (select 1 from %s where price>0 and userid=o.userid and order_time<o.order_time)",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr,
                                  t_order_info::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }

   

    public function getOrderByParentid($parentid){
        $sql = $this->gen_sql_new("  select o.orderid from %s o "
                                  ." left join %s pc on pc.userid=o.userid"
                                  ." where pc.parentid=$parentid order by o.orderid desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }
    //@desn:获取订单信息
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    public function get_node_type_order_data_now($field_name,$start_time,$end_time,$adminid_list=[],$tmk_adminid=-1 ,$origin_ex,$opt_date_str,$origin){
        switch ( $field_name ) {
        case "origin" :
            $field_name="oi.origin";
            break;
        case "grade" :
            $field_name="oi.grade";
            break;
        default:
            break;
        }

        if($field_name=="tmk_adminid"){
            $where_arr=[
                ["oi.origin like '%%%s%%' ",$origin,""],
                ['oi.contract_type = %u',0],
                ['si.is_test_user = %u',0],
                "oi.contract_status >0 ",
                "ssn.tmk_adminid >0 ",
                "oi.order_time>ssn.tmk_assign_time",
            ];
        } else {
            $where_arr=[
                ["oi.origin like '%%%s%%' ",$origin,""],
                "oi.contract_type in ( 0 )",
                "si.is_test_user=0",
                "oi.contract_status >0 ",
            ];
        }

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"oi.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_add_time_range($where_arr,"oi.order_time",$start_time,$end_time);
        // $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"mi.uid",$adminid_list);

        $this->where_arr_add__2_setid_field($where_arr,"ssn.tmk_adminid",$tmk_adminid);

        $sql = $this->gen_sql_new(
            'select '.$field_name.' as check_value,count(oi.orderid) as order_count,'.
            'count(distinct oi.userid) as user_count,'.
            'round(sum(oi.price)/100) as order_all_money '.
            'from %s oi '.
            'left join %s si on oi.userid = si.userid '.
            "left join %s mi  on oi.sys_operator=mi.account ".
            "left join %s ssn  on oi.userid=ssn.userid ".
            'where %s group by check_value',
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME ,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取微信运营订单信息
    public function get_wx_order_info($start_time,$end_time){
        $where_arr=[
            "oi.contract_type = 0 ",
            "si.is_test_user=0",
            "oi.contract_status >0 ",
            "ssn.tmk_adminid >0 ",
            "oi.order_time>ssn.tmk_assign_time",
            'ssn.wx_invaild_flag = 1'
        ];

        $this->where_arr_add_time_range($where_arr,"oi.order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(
            "select count(*) as wx_order_count,sum(price)/100 as wx_order_all_money "
            ." from %s oi "
            ." left join %s si  on oi.userid=si.userid "
            ." left join %s ssn  on oi.userid=ssn.userid "
            ." where %s ",
            self::DB_TABLE_NAME ,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_row($sql);
    }
    //@desn:获取公众号订单信息
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    public function get_public_number_order_info($start_time,$end_time){
        $where_arr=[
            "oi.contract_type = 0 ",
            "si.is_test_user=0",
            "oi.contract_status >0 ",
        ];

        $this->where_arr_add_time_range($where_arr,"oi.order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(
            "select oi.origin,count(*) as pn_order_count,sum(price)/100 as pn_order_all_money "
            ." from %s oi "
            ." left join %s si  on oi.userid=si.userid "
            ." left join %s ssn  on oi.userid=ssn.userid "
            ." where %s group by oi.origin ",
            self::DB_TABLE_NAME ,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["origin"];
        });

    }

    public function get_order_count_by_adminid($start_time,$end_time,$adminid=-1) {
        $where_arr = [
            ["order_time>=%u" , $start_time, -1],
            ["order_time<=%u" , $end_time, -1],
            ["is_test_user=%u" , 0, -1],
            "contract_type in(0)",
        ];
        $this->where_arr_add_int_field($where_arr,'t2.uid',$adminid);
        $sql = $this->gen_sql_new("select count(*) order_count "
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.sys_operator = t2.account "
                                  ." left join %s t3 on t1.userid = t3.userid "
                                  ." where %s and  contract_status <> 0 ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  Z\z_t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_not_order($userid) {
        $sql = $this->gen_sql_new("select orderid from %s where userid=$userid",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    //助教合同详情信息(薪资版本) 助教自签
    public function get_assistant_performance_order_info($start_time,$end_time,$adminid=-1,$contract_type=-1){
        $where_arr=[
            [  "o.order_time >= %u", $start_time, -1 ] ,
            [  "o.order_time <= %u", $end_time, -1 ] , 
            "m.account_role = 1 ",
            "o.price >0",
            "o.contract_type in (0,3,3001)",
            "o.contract_status>0",
            "s.is_test_user=0",
            ["m.uid=%u",$adminid,-1]
        ];
        if($contract_type==0){
            $where_arr[]="o.contract_type =0";
        }elseif($contract_type==3){
            $where_arr[]="o.contract_type in (3,3001)";
        }
        $refund_end_time = $end_time+9*86400;

        $sql =$this->gen_sql_new("select  if(o.contract_type=3001,3,o.contract_type) contract_type,".
                                 " o.price,rf.real_refund,m.uid,o.sys_operator,s.userid,o.orderid, ".
                                 " o.pay_time,rf.apply_time,o.order_time ".
                                 " from  %s o ".
                                 " left join %s m on o.sys_operator  = m.account".
                                 " left join %s s on s.userid=o.userid".
                                 " left join %s rf on o.orderid = rf.orderid and rf.apply_time<=%u".
                                 " where %s ",
                                 self::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 t_student_info::DB_TABLE_NAME,
                                 t_order_refund::DB_TABLE_NAME,
                                 $refund_end_time,
                                 $where_arr
        );

        return $this->main_get_list($sql);
 
    }

    //临时方法(获取每个合同的金额,分期以80%计算)
    public function get_ass_self_order_period_money($start_time,$end_time){
        $where_arr=[
            [  "o.order_time >= %u", $start_time, -1 ] ,
            [  "o.order_time <= %u", $end_time, -1 ] , 
            "m.account_role = 1 ",
            "o.price >0",
            "o.contract_type in (0,3,3001)",
            "o.contract_status>0",
            "s.is_test_user=0",
        ];

        $sql =$this->gen_sql_new("select sum(if(c.child_order_type=2,c.price*0.8,c.price)) reset_money,o.price,o.orderid ".
                                 " from  %s o ".
                                 " left join %s m on o.sys_operator  = m.account".
                                 " left join %s s on s.userid=o.userid".
                                 " left join %s c on o.orderid=c.parent_orderid and c.price>0".
                                 " where %s group by o.orderid",
                                 self::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 t_student_info::DB_TABLE_NAME,
                                 t_child_order_info::DB_TABLE_NAME,
                                 $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["orderid"];
        });

    }

    //转介绍分期金额80%计算
    public function get_seller_tran_order_period_money($start_time,$end_time){
        $where_arr=[
            [ "o.order_time >= %u", $start_time, -1 ] ,
            [ "o.order_time <= %u", $end_time, -1 ] ,
            "o.contract_status  >0" ,
            "m.account_role = 1 ",
            "o.price >0",
            "mm.account_role=2",
            "s.is_test_user=0",
        ];
        $sql = $this->gen_sql_new("select sum(if(c.child_order_type=2,c.price*0.8,c.price)) reset_money,o.price,o.orderid "
                                  ." from %s o left join %s s on s.userid=o.userid "
                                  ." left join %s m on s.origin_assistantid = m.uid"
                                  ." left join %s mm on o.sys_operator = mm.account"
                                  ." left join %s c on o.orderid=c.parent_orderid and c.price>0"
                                  ." where %s group by o.orderid",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_child_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["orderid"];
        });       

    }


    //助教合同详情信息(薪资版本)  销售转介绍
    public function get_seller_tran_order_info($start_time,$end_time,$adminid=-1){
        $where_arr=[
            [ "o.order_time >= %u", $start_time, -1 ] ,
            [ "o.order_time <= %u", $end_time, -1 ] ,
            "o.contract_status  >0" ,
            "m.account_role = 1 ",
            "o.price >0",
            "mm.account_role=2",
            "s.is_test_user=0",
            ["s.origin_assistantid=%u",$adminid,-1]
        ];
        $refund_end_time = $end_time+9*86400;
        $sql = $this->gen_sql_new("select o.price,rf.real_refund,m.uid,o.sys_operator,s.userid,o.orderid, "
                                  ." o.pay_time,rf.apply_time,o.order_time "
                                  ." from %s o left join %s s on s.userid=o.userid "
                                  ." left join %s m on s.origin_assistantid = m.uid"
                                  ." left join %s mm on o.sys_operator = mm.account"
                                  ." left join %s rf on o.orderid = rf.orderid and rf.apply_time<=%u"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_refund::DB_TABLE_NAME,
                                  $refund_end_time,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_last_orderid_by_userid($userid){
        $where_arr=[
            "contract_status>0",
            "price>0",
            'contract_type = 0',
        ];
        $this->where_arr_add_int_field($where_arr, 'userid', $userid);
        $sql = $this->gen_sql_new(
            "select orderid "
            ." from %s "
            ." where %s order by order_time desc limit 1 ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_sys_operator_refund_info($one_year,$half_year,$three_month,$start_time,$end_time,$sys_operator,$account_role){
        $where_arr = [
            " price > 0 ",
            [" order_time > %s",$one_year,-1],
            [" order_time < %s",$end_time,-1],
            " s.is_test_user = 0 ",
            " o.contract_status  in (1,2,3) ",
        ];
        if ($sys_operator !=""){
            $where_arr[]=sprintf( "(sys_operator like '%%%s%%'  )",
                                    $this->ensql($sys_operator));
        }
        if($account_role == 1){
            $where_arr[] = "m.account_role=1"; 
        }elseif($account_role == 2){
            $where_arr[] = "m.account_role=2"; 
        }elseif($account_role == 3){
            $where_arr[] = "( m.account_role != 1 and  m.account_role != 2)";
        }
        $sql = $this->gen_sql_new("select sys_operator,m.uid,m.account_role as type,count(*) as one_year_num ,"
            ."  sum(if(order_time > $half_year , 1,0)) as half_year_num, "
            ."  sum(if(order_time > $three_month , 1,0)) as three_month_num,"
            ."  sum(if(order_time > $start_time , 1,0)) as one_month_num, "
            ."  sum(if (contract_status = 3, 1,0)) as one_year_refund_num, "
            ."  sum(if((order_time > $half_year and contract_status = 3),1,0)) as half_year_refund_num,"
            ."  sum(if((order_time > $three_month and contract_status =  3),1,0)) as three_month_refund_num,"
            ."  sum(if((order_time > $start_time and contract_status = 3),1,0)) as one_month_refund_num"
            ." from %s o "
            ." left join %s s on s.userid = o.userid "
            ." left join %s m on  m.account = sys_operator"
            ." where %s "
            ."group by sys_operator order by m.create_time desc",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql,function($item){
            return $item['sys_operator'];
        });

    }

    public function get_cr_refund_info($one_year,$half_year,$three_month,$start_time,$end_time,$nick){
        $where_arr = [
            " price > 0 ",
            [" order_time > %s",$one_year,-1],
            [" order_time < %s",$end_time,-1],
            " s.is_test_user = 0 ",
            " o.contract_status  in (1,2,3) ",
        ];
        
        if ($nick !=""){
            $where_arr[]=sprintf( "(a.nick like '%%%s%%'  )",
                                    $this->ensql($nick));
        }
        $sql = $this->gen_sql_new("select s.assistantid,a.nick,count(*) as one_year_num, "
            ."  sum(if(order_time > $half_year , 1,0)) as half_year_num, "
            ."  sum(if(order_time > $three_month , 1,0)) as three_month_num, "
            ."  sum(if(order_time > $start_time , 1,0)) as one_month_num, "
            ."  sum(if(contract_status = 3, 1,0)) as one_year_refund_num, "
            ."  sum(if((order_time > $half_year and contract_status = 3),1,0)) as half_year_refund_num,"
            ."  sum(if((order_time > $three_month and contract_status =  3),1,0)) as three_month_refund_num,"
            ."  sum(if((order_time > $start_time and contract_status = 3),1,0)) as one_month_refund_num"
            ."  from %s o "
            ."  left join %s s on s.userid = o.userid "
            ."  left join %s a on a.assistantid = s.assistantid "
            ."  where %s "
            ."  group by s.assistantid order by a.last_modified_time desc",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_assistant_info::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql,function($item){
            return $item['assistantid'];
        });

    }
    //@desn:获取用户首冲先关信息
    //@param:$userid 用户id
    //@param:$end_time 结束时间
    public function get_first_flush_info($userid,$end_time){
        $where_arr = [
            'price > 0',
            'contract_status > 0',
            'contract_type not in (1,2)',
            'order_time < '.$end_time
        ];
        $this->where_arr_add_int_or_idlist($where_arr, 'userid', $userid);
        $sql = $this->gen_sql_new(
            'select order_time first_flush_time,lesson_total*default_lesson_count first_flush_class_pag '.
            'from %s where %s order by orderid asc limit 1',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    //@desn:获取用户续费信息
    //@param:$userid 用户id
    public function get_renewal_info($userid,$start_time,$end_time){
        $where_arr = [
            'price > 0',
            'contract_status > 0',
            'contract_type = 3',
            'order_time < '.$end_time
        ];
        $this->where_arr_add_int_or_idlist($where_arr, 'userid', $userid);
        // $this->where_arr_add_time_range($where_arr, 'order_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select count(*) renewal_count,sum(lesson_total) renewal_class_pag,'.
            'sum(if((order_time >= 1509465600 and order_time < 1514736000),1,0)) q4_renewal '.
            'from %s '.
            'where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    //@desn:获取用户总课时数
    //@param:$userid 用户id
    //@param:$end_time 结束时间
    public function get_all_class_pag($userid,$end_time){
        $where_arr = [
            'contract_status > 0',
            'contract_type <> 2',
            'order_time < '.$end_time
        ];
        $this->where_arr_add_int_or_idlist($where_arr, 'userid', $userid);
        $sql=$this->gen_sql_new(
            'select sum(lesson_total*default_lesson_count) all_class_pag from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    //查询续费学生
    public function get_all_renew_stu_list_by_order( $start_time, $end_time){
        $where_arr = [
            'o.contract_status > 0',
            'o.contract_type = 3',
            'o.price>0',
            's.is_test_user=0',

        ];
        $this->where_arr_add_time_range($where_arr, 'o.order_time', $start_time, $end_time);
        $sql = $this->gen_sql_new("select distinct o.userid,s.assistantid,a.nick "
                                 ." from %s o left join %s s on o.userid = s.userid "
                                  ." left join %s a on s.assistantid = a.assistantid"

                                  ." where %s and exists (select 1 from %s where order_time<o.order_time and userid = o.userid and contract_type in (0,3) and price>0 and contract_status > 0)",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["userid"];
        });



    }
    //@desn:获取cc一段时间内的销售额
    //@param:$begin_time,$end_time 开始时间 结束时间
    //@param:$admin_revisiterid cc的id
    public function get_order_money_by_adminid($begin_time,$end_time,$admin_revisiterid){
        $where_arr=[
            'oi.contract_type = 0',
            'si.is_test_user = 0',
            'oi.contract_status >0'
        ];
    }
}

