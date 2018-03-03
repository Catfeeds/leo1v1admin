<?php
namespace App\Models;

use \App\Enums as E;
/**
 * @property t_user_info  $t_user_info
 * @property t_lesson_info  $t_lesson_info
 * @property t_lesson_info_b2  $t_lesson_info_b2
 * @property t_order_info  $t_order_info
 * @property t_course_order  $t_course_order
 * @property t_seller_student_info $t_seller_student_info
 * @property t_parent_info  $t_parent_info
 * @property t_manager_info  $t_manager_info
 * @property t_assistant_info  $t_assistant_info
 * @property t_admin_group_user  $t_admin_group_user
 * @property t_admin_group_name  $t_admin_group_name
 */


class t_student_info extends \App\Models\Zgen\z_t_student_info
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_test_list( $page_info, $order_by_str,  $grade ) {
        $where_arr=[] ;
        // int , 枚举, 枚举列表 ,都用这个
        $this->where_arr_add_int_or_idlist($where_arr,"grade", $grade );

        $sql = $this->gen_sql_new("select  userid, nick,realname,  phone, grade "
                              ." from %s ".
                                  "  where  %s  $order_by_str ",
                              self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql, $page_info);
    }

    public function get_student_list_search( $page_num,$all_flag, $userid,$grade, $status,
                                             $user_name, $phone, $teacherid, $assistantid, $test_user,
                                             $originid, $seller_adminid,$order_type,$student_type
    ){
        $where_arr=[
            ["userid=%u", $userid, -1] ,
            ["grade=%u", $grade, -1] ,
            ["status=%u", $status, -1] ,
            ["assistantid=%u", $assistantid, -1] ,
            ["is_test_user=%u ", $test_user , -1] ,
            ["originid=%u ", $originid , -1] ,
            ["seller_adminid=%u ", $seller_adminid, -1] ,
            ["type=%u ", $student_type, -1] ,
        ];
        if($student_type>-1)
            $where_arr[] = 'assistantid>0';

        $order_str = "";
        switch ( $order_type ) {
        case 1 :
            $order_str=" order by lesson_count_left  desc";
            break;
        case 2 :
            $order_str=" order by praise desc";
            break;
        case 3 :
            $order_str=" order by ass_assign_time desc";
            break;
        default:
            break;
        }
        $where_arr = $this->student_search_info_sql($user_name,'',$where_arr);

        $sql = $this->gen_sql_new("select origin_userid, userid, nick,realname, spree, phone, is_test_user, "
                                  ." originid, origin, grade, praise, parent_name, parent_type, last_login_ip, "
                                  ." last_lesson_time, last_login_time,assistantid, lesson_count_all, lesson_count_left, "
                                  ." user_agent,seller_adminid,ass_assign_time ,reg_time,phone_location,origin_assistantid ,grade_up"
                                  ." from %s "
                                  ." where %s %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$order_str
        );
        $ret_info = $this->main_get_list_by_page($sql,$page_num,10);
        foreach  (  $ret_info["list"] as &$item) {
            if (!$item["phone_location"] ) {
                //设置到数据库
                $arr   = explode("-",$item["phone"]);
                $phone = $arr[0];

                $item["phone_location"] = \App\Helper\Common::get_phone_location($phone);
                if ($item["phone_location"]) {
                    $this->field_update_list($item["userid"] ,[
                        "phone_location" => $item["phone_location"]
                    ]);
                }
            }
        }
        return $ret_info;
    }

    public function get_student_list_for_finance($page_num, $userid,$grade, $user_name, $assistantid, $originid, $seller_adminid){
        $where_arr=[
            ["s.userid=%u", $userid, -1] ,
            ["s.grade=%u", $grade, -1] ,
            ["s.assistantid=%u", $assistantid, -1] ,
            ["s.originid=%u ", $originid , -1] ,
            ["s.seller_adminid=%u ", $seller_adminid, -1] ,
            "s.type<>1",
            "s.is_test_user=0",
            "o.contract_type in (0,1,3)",
            "o.contract_status > 0",
            "o.price>0",
        ];
        if ($user_name) {
            $where_arr[]=sprintf( "(nick like '%s%%' or realname like '%s%%' or  phone like '%s%%' )",
                                  $this->ensql($user_name),
                                  $this->ensql($user_name),
                                  $this->ensql($user_name));
        }

        $sql = $this->gen_sql(
            "select s.origin_userid, s.userid, s.nick,s.realname, s.spree, phone, s.originid, s.origin, s.grade, "
            ."s.parent_name, s.parent_type, s.last_login_ip, s.last_lesson_time, s.last_login_time,s.assistantid, s.lesson_count_all, "
            ."s.lesson_count_left, s.user_agent,s.seller_adminid,s.ass_assign_time ,s.reg_time,s.phone_location,s.origin_assistantid ,s.grade_up"
            ." from %s s "
            ." left join %s o on o.userid=s.userid"
            ." where  %s "
            ." group by s.userid"
            ,self::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,[$this->where_str_gen($where_arr)]
        );

        return  $this->main_get_list_by_page($sql,$page_num,10,true);
    }


    public function get_student_list_for_finance_count( ){
        $where_arr=[
            "s.type<>1",
            "s.is_test_user=0",
            "o.contract_type in (0,3)",
            "o.contract_status > 0",
            "o.price>0",
        ];

        $sql = $this->gen_sql_new("select count( distinct o.userid ) as userid_count,count(distinct o.orderid) as orderid_count"
                                  ." from %s s "
                                  ." left join %s o on o.userid=s.userid"
                                  ." where  %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return  $this->main_get_row($sql);
    }




    public function get_student_list_search_two_weeks( $start_time, $end_time,$page_num,$all_flag, $userid,$grade, $status,
                                                       $user_name, $phone, $teacherid, $assistantid, $test_user,
                                                       $originid, $seller_adminid,$order_type,$ass_adminid_list=[]
    ){
        $last_two_weeks_time = time(NULL)-86400*14;
        $where_arr=[
            ["s.userid=%u", $userid, -1] ,
            ["s.grade=%u", $grade, -1] ,
            ["s.status=%u", $status, -1] ,
            ["s.assistantid=%u", $assistantid, -1] ,
            ["s.is_test_user=%u ", $test_user , -1] ,
            ["s.originid=%u ", $originid , -1] ,
            ["s.seller_adminid=%u ", $seller_adminid, -1] ,
            "s.lesson_count_all>0",
            "s.lesson_count_left<100",
            "s.last_lesson_time<$last_two_weeks_time"
        ];
        $this->where_arr_add_time_range($where_arr,"s.last_lesson_time",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"m.uid", $ass_adminid_list );
        if ($user_name) {
            $where_arr[]=sprintf( "(s.nick like '%s%%' or s.realname like '%s%%' or  s.phone like '%s%%' )",
                                  $this->ensql($user_name),
                                  $this->ensql($user_name),
                                  $this->ensql($user_name));
        }

        $order_str="";
        switch ( $order_type ) {
        case 1 :
            $order_str=" order by s.lesson_count_left  desc";
            break;
        case 2 :
            $order_str=" order by s.praise desc";
            break;
        case 3 :
            $order_str=" order by s.last_lesson_time desc";
            break;
        default:
            break;
        }

        $sql = $this->gen_sql("select s.origin_userid, s.userid, s.nick,s.realname, s.spree, s.phone, s.is_test_user, s.originid, s.origin, s.grade, s.praise, s.parent_name, s.parent_type, s.last_login_ip, s.last_lesson_time, s.last_login_time,s.assistantid, s.lesson_count_all, s.lesson_count_left, s.user_agent,seller_adminid,s.ass_assign_time ,s.reg_time,s.phone_location from %s s left join %s a on s.assistantid =a.assistantid ".
                              " left join %s m on a.phone = m.phone".
                              "  where  %s  %s  ",
                              self::DB_TABLE_NAME,
                              t_assistant_info::DB_TABLE_NAME,
                              t_manager_info::DB_TABLE_NAME,
                              [$this->where_str_gen($where_arr)],
                              $order_str
        );
        $ret_info = $this->main_get_list_by_page($sql,$page_num,10);
        foreach  (  $ret_info["list"] as &$item) {
            if (!$item["phone_location"] ) {
                //设置到数据库
                $arr=explode("-",$item["phone"]);
                $phone=$arr[0];

                $item["phone_location"] = \App\Helper\Common::get_phone_location($phone);
                if ($item["phone_location"]) {
                    $this->field_update_list($item["userid"] ,[
                        "phone_location"  =>   $item["phone_location"]
                    ]);
                }
            }
        }
        return $ret_info;
    }

    public function get_end_stu_for_seller($page_num,$stu_list,$order_type){
        $where_arr=[];
        $where_arr[] = $this->where_get_in_str("s.userid", $stu_list,false );

        $order_str="";
        switch ( $order_type ) {
        case 1 :
            $order_str=" order by s.lesson_count_left  desc";
            break;
        case 2 :
            $order_str=" order by s.praise desc";
            break;
        case 3 :
            $order_str=" order by s.last_lesson_time desc";
            break;
        default:
            break;
        }

        $sql = $this->gen_sql_new("select s.origin_userid, s.userid, s.nick,s.realname, s.spree, s.phone, s.is_test_user, s.originid, s.origin, s.grade, s.praise, s.parent_name, s.parent_type, s.last_login_ip, s.last_lesson_time, s.last_login_time,s.assistantid, s.lesson_count_all, s.lesson_count_left, s.user_agent,seller_adminid,s.ass_assign_time ,s.reg_time,s.phone_location from %s s left join %s a on s.assistantid =a.assistantid ".
                                  " left join %s m on a.phone = m.phone".
                                  "  where  %s  %s  ",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $order_str
        );
        $ret_info = $this->main_get_list_by_page($sql,$page_num,10);
        foreach  (  $ret_info["list"] as &$item) {
            if (!$item["phone_location"] ) {
                //设置到数据库
                $arr=explode("-",$item["phone"]);
                $phone=$arr[0];

                $item["phone_location"] = \App\Helper\Common::get_phone_location($phone);
                if ($item["phone_location"]) {
                    $this->field_update_list($item["userid"] ,[
                        "phone_location"  =>   $item["phone_location"]
                    ]);
                }
            }
        }
        return $ret_info;

    }
    public function get_student_search_two_weeks_list( $start_time, $end_time,$all_flag, $userid,$grade, $status,
                                                       $user_name, $phone, $teacherid, $assistantid, $test_user,
                                                       $originid, $seller_adminid,$ass_adminid_list=[],$student_type
    ){
        $last_two_weeks_time = time(NULL)-86400*14;
        $where_arr=[
            ["s.userid=%u", $userid, -1] ,
            ["s.grade=%u", $grade, -1] ,
            ["s.status=%u", $status, -1] ,
            ["s.assistantid=%u", $assistantid, -1] ,
            ["s.is_test_user=%u ", $test_user , -1] ,
            ["s.originid=%u ", $originid , -1] ,
            ["s.seller_adminid=%u ", $seller_adminid, -1] ,
            ["s.type=%u",$student_type,-1],
            "s.lesson_count_all>0",
            "s.lesson_count_left<100",
            "s.last_lesson_time<$last_two_weeks_time"
        ];
        $this->where_arr_add_time_range($where_arr,"s.last_lesson_time",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"m.uid", $ass_adminid_list );
        if ($user_name) {
            $where_arr[]=sprintf( "(s.nick like '%s%%' or s.realname like '%s%%' or  s.phone like '%s%%' )",
                                  $this->ensql($user_name),
                                  $this->ensql($user_name),
                                  $this->ensql($user_name));
        }

        $sql = $this->gen_sql("select s.userid from %s s left join %s a on s.assistantid =a.assistantid".
                              " left join %s m on a.phone = m.phone".
                              "  where  %s  ",
                              self::DB_TABLE_NAME,
                              t_assistant_info::DB_TABLE_NAME,
                              t_manager_info::DB_TABLE_NAME,
                              [$this->where_str_gen($where_arr)]
        );
        return $this->main_get_list($sql);

    }


    public function get_student_list_count($userid,$grade, $status, $user_name, $phone, $teacherid, $assistantid, $test_user, $originid, $page_num)
    {
        $where_arr=[
            ["userid=%u", $userid, -1] ,
            ["grade=%u", $grade, -1] ,
            ["status=%u", $status, -1] ,
            ["assistantid=%u", $assistantid, -1] ,
            ["is_test_user=%u ", $test_user , -1] ,
            ["originid=%u ", $originid , -1] ,

        ];

        if ($user_name) {
            $where_arr[]= " (nick like '%" . $user_name . "%' or parent_name like '%" . $user_name . "%') "   ;
        }

        if ($phone) {
            $where_arr[]=  "phone like '%".$phone."%'";
        }
        $sql = $this->gen_sql("select userid, assistantid, revisit_time, operator_note, nick, phone, is_test_user, originid, grade "
                              ." from %s  "
                              ." where %s ",
                              self::DB_TABLE_NAME,
                              [$this->where_str_gen($where_arr)]
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }



    public function get_student_list_counts($userid,$grade, $status, $user_name, $phone, $teacherid, $assistantid, $test_user, $originid, $page_num, $start, $end, $revisit_type,$revisit_assistantid=-1)
    {
        $where_arr=[
            ["b.userid=%u", $userid, -1] ,
            ["grade=%u", $grade, -1] ,
            ["status=%u", $status, -1] ,
            ["a.assistantid=%u", $assistantid, -1] ,
            ["is_test_user=%u ", $test_user , -1] ,
            ["originid=%u ", $originid , -1] ,
            ["b.revisit_type=%u ", $revisit_type, -1] ,
            ["t.assistantid=%u ", $revisit_assistantid, -1] ,

        ];
        if ($user_name) {
            $where_arr[]= " (a.nick like '%" . $user_name . "%' or a.parent_name like '%" . $user_name . "%') "   ;
        }

        if ($phone) {
            $where_arr[]=  "a.phone like '%".$phone."%'";
        }
        $sql = $this->gen_sql(
            "select b.userid, b.revisit_type, a.assistantid, b.revisit_time, b.operator_note, b.sys_operator,"
            ." a.nick, a.phone, originid, a.grade,tq.duration"
            ." from %s a left join %s b on a.userid = b.userid "
            ." left join %s m on b.sys_operator = m.account"
            ." left join %s t on t.phone = m.phone "
            ." left join %s tq on tq.id = b.call_phone_id "
            ."  where %s and b.revisit_time > %u and b.revisit_time < %u order by b.revisit_time desc",
            self::DB_TABLE_NAME,
            t_revisit_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_assistant_info::DB_TABLE_NAME,
            t_tq_call_info::DB_TABLE_NAME,
            [$this->where_str_gen($where_arr)],
            $start,
            $end
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }


    public function get_student_list_archive( $userid,$grade, $status, $user_name, $phone, $teacherid, $assistantid, $test_user, $originid, $page_num,$student_type,$revisit_flag,$warning_stu=-1,$sum_start=0,$revisit_warn_flag=1,$warn_list=[],$refund_warn=-1
)
    {
        $where_arr=[
            ["a.userid=%u", $userid, -1] ,
            ["status=%u", $status, -1] ,
            ["user=%u", $status, -1] ,
            ["assistantid=%u", $assistantid, -1] ,
            ["is_test_user=%u ", $test_user , -1] ,
            ["type=%u ", $student_type, -1] ,
            ["originid=%u ", $originid , -1] ,
            "is_test_user=0 ",
            ["a.userid in  (select userid from t_course_order where teacherid =%u )", $teacherid, -1] ,
            "assistantid>0",
        ];
        $this->where_arr_add_int_or_idlist ($where_arr,"grade", $grade  );

        $now=time(NULL);
        if ($revisit_flag == 1) {
            $where_arr[] = "ass_revisit_last_week_time < ".$sum_start;
        }elseif($revisit_flag == 2){
            $where_arr[] = "(ass_revisit_last_month_time < $now - 28 * 86400 )";
        } elseif ($revisit_flag == 3) {
            $where_arr[] = "a.refund_warning_count=0";
        } elseif ($revisit_flag == 4) {
            $where_arr[] = "a.refund_warning_count<4";
        }

        if ($refund_warn == 4) {
            $where_arr[] = "a.refund_warning_level in (1,2,3)";
        } elseif ($refund_warn != -1) {
            $where_arr[] = "a.refund_warning_level=$refund_warn";
        }

        if ($user_name) {
            $where_arr[]= " (nick like '" . $this->ensql( $user_name)  . "%' "
                ." or parent_name like '" . $this->ensql(  $user_name) . "%' "
                //." or phone_location like '" . $this->ensql(  $user_name) . "%' "
                ." or realname like '" . $this->ensql( $user_name) . "%') ";
        }

        $where_arr[]=  ["phone like '%s%%'",$phone,"" ] ;

        if($warning_stu == 1){
            $have = "(sum(b.lesson_count) - sum(a.lesson_count_left)/count(*)) >=0";
        }elseif($warning_stu == 2){
            $have = "(sum(b.lesson_count) - sum(a.lesson_count_left)/count(*)/2) >=0";
        }elseif($warning_stu == 3){
            $have = "(sum(b.lesson_count) - sum(a.lesson_count_left)/count(*)/3) >=0";
        }elseif($warning_stu == 4){
            $have = "(sum(b.lesson_count) - sum(a.lesson_count_left)/count(*)/4) >=0";
        }else if($warning_stu == 5){
            $have = "(sum(b.lesson_count) - sum(a.lesson_count_left)/count(*)/8) >=0";
        }else if($warning_stu == 6){
            $have = "(sum(b.lesson_count) - sum(a.lesson_count_left)/count(*)/12) >=0";
        }else if($warning_stu == 7){
            $have = "(sum(b.lesson_count) - sum(a.lesson_count_left)/count(*)/16) >=0";
        }else if($warning_stu == 8){
            $have = "(sum(b.lesson_count) - sum(a.lesson_count_left)/count(*)/20) >=0";
        }else if($warning_stu == 9){
            $have = "(sum(b.lesson_count) - sum(a.lesson_count_left)/count(*)/24) >=0";
        }else{
            $have = true;
        }

        if($revisit_warn_flag==0){
            $where_arr[]=$this->where_get_not_in_str("a.userid",$warn_list);
        }


        $sql = $this->gen_sql_new("select a.userid,a.type,a.refund_warning_level, count(*) lesson_num, is_auto_set_type_flag, a.stu_lesson_stop_reason, "
                                  ." a.phone, a.is_test_user, originid, a.grade, praise, assistantid, parent_name, parent_type, "
                                  ." last_login_ip, last_login_time, lesson_count_all, a.lesson_count_left, user_agent, type, "
                                  ." ass_revisit_last_month_time, ass_revisit_last_week_time,ass_assign_time,a.phone_location, "
                                  ." if(realname='',nick,realname) as nick, "
                                  ." sum(b.lesson_count) as lesson_total "
                                  // ." t.test_lesson_subject_id "
                                  ." from %s a "
                                  ." left join %s b on a.userid = b.userid "
                                  // ." left join %s t on t.userid = a.userid and t.grade=a.grade "
                                  ."  where  %s group by a.userid having %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_week_regular_course::DB_TABLE_NAME
                                  // ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$have
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true,"order by ass_assign_time desc ");


    }

    public function get_student_count_archive() {
        $where_arr=[
            "is_test_user=0 ",
            "assistantid>0",
        ];

        $sql = $this->gen_sql_new("select a.userid, a.type "
                                  ." from %s a left join %s b on a.userid = b.userid "
                                  ."  where  %s group by a.userid "
                                  ,self::DB_TABLE_NAME
                                  ,t_week_regular_course::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);

    }


    public function get_two_stu_for_archive( $grade, $sum_start)
    {
        $where_arr=[
            "type=0 ",
            "is_test_user=0 ",
            "assistantid>0",
        ];
        $this->where_arr_add_int_or_idlist ($where_arr,"grade", $grade  );

        $sql = $this->gen_sql_new(
            "select a.userid, count(*) lesson_num, is_auto_set_type_flag, a.stu_lesson_stop_reason, "
            ." phone, is_test_user, originid, grade, praise, assistantid, parent_name, parent_type, "
            ." last_login_ip, last_login_time, lesson_count_all, a.lesson_count_left, user_agent, type, "
            ." ass_revisit_last_month_time, ass_revisit_last_week_time,ass_assign_time,a.phone_location, "
            ." if(realname='',nick,realname) as nick, "
            ." sum(b.lesson_count) as lesson_total "
            ." from %s a left join %s b on a.userid = b.userid "
            ."  where  %s group by a.userid "
            ,self::DB_TABLE_NAME
            ,t_week_regular_course::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_page_random($sql,2,true);

    }


    public function get_student_sum_archive(  $assistantid)
    {
        $where_arr=[
            ["assistantid=%u", $assistantid, -1] ,
            "assistantid>0" ,
            "type=0" ,
        ];


        $now=time(NULL);

        $sql = $this->gen_sql_new("select sum(ass_revisit_last_week_time < $now - 7 * 86400 ) sumweek,sum(ass_revisit_last_month_time < $now - 28*86400) summonth  from %s ".
                                  "  where  %s   ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);

        return $this->main_get_row($sql);


    }

    public function get_student_sum_archive_new(  $assistantid,$start_time)
    {
        $where_arr=[
            ["assistantid=%u", $assistantid, -1] ,
            "assistantid>0" ,
            "type=0" ,
            "ass_revisit_last_week_time<".$start_time
        ];

        $sql = $this->gen_sql_new("select count(*) from %s ".
                                  "  where  %s   ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);

        return $this->main_get_value($sql);


    }

    public function get_student_list_id()
    {
        $where_arr = [
            "lesson_count_left>0",
            "is_auto_set_type_flag = 0",
        ];
        $sql = $this->gen_sql_new("select userid,type from %s  ".
                                  "  where  %s  ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);


        return $this->main_get_list($sql);
    }
    public   function get_valid_user_count() {
        $where_arr = [
            "lesson_count_left>0",
            "is_test_user = 0",
        ];
        $sql = $this->gen_sql_new("select count(*) from %s  ".
                                  "  where  %s  ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_value($sql);
    }

    public function get_student_lesson_all()
    {
        $where_arr = [
            "lesson_count_all>0",
            "is_auto_set_type_flag = 0",
        ];
        $sql = $this->gen_sql_new("select userid as userid from %s  ".
                                  "  where  %s  ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);


        return $this->main_get_list($sql, function($item ) {
            return $item["userid"];
        });
    }

    public function get_student_list_end_id($is_auto_set_type_flag=0)
    {
        $where_arr = [
            ["is_auto_set_type_flag=%u",$is_auto_set_type_flag,-1],
            "lesson_count_left <100",
            "type not in (1)"
            //"is_auto_set_type_flag = 0",
        ];
        $sql = $this->gen_sql_new("select userid,type from %s  ".
                                  "  where  %s  ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);


        return $this->main_get_list($sql);
    }

    public function get_no_auto_read_stu_list()
    {
        $where_arr = [
            "type=0",
            "is_auto_set_type_flag = 1",
        ];
        $sql = $this->gen_sql_new("select userid,type from %s  ".
                                  "  where  %s  ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);


        return $this->main_get_list($sql);
    }

    public function get_no_auto_stop_stu_list($time)
    {
        $where_arr = [
            "type>0",
            "type not in (5,6)",//逾期学员不更新
            // "is_auto_set_type_flag = 1",
            "type_change_time<".$time
        ];
        $sql = $this->gen_sql_new("select userid,type from %s  ".
                                  "  where  %s  ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);


        return $this->main_get_list($sql);
    }


    public function get_student_list_new_id()
    {
        $sql = $this->gen_sql_new("select userid as userid,type from %s  ".
                                  "  where lesson_count_left = lesson_count_all and is_auto_set_type_flag = 0 ",
                                  self::DB_TABLE_NAME);
        return $this->main_get_list($sql, function($item ) {
            return $item["userid"];
        });
    }



    public function get_student_simple_info($studentid)
    {
        $sql = sprintf("select userid,nick,assistantid,face,grade,phone,parent_name from %s where userid = %u"
                       ,self::DB_TABLE_NAME
                       ,$studentid
        );
        return $this->main_get_row($sql);
    }

    private function gen_condition_str($grade, $status,$user_name, $phone, $test_user, $originid)
    {
        $where = "";
        $str   = "";
        $and   = '';
        if ($grade != -1 ) {
            if ($and == "") {
                $and = " and ";
            }
            $str .= $and . " a.grade = " . $grade;
        }

        if ($status != -1 ) {
            if ($and == "") {
                $and = " and ";
            }
            $str .= $and . " status =  " . $status;
        }

        if ($user_name != "") {
            if ($and == "") {
                $and = " and ";
            }
            $str .= $and . " (nick like '%" . $user_name . "%' or parent_name like '%" . $user_name . "%') ";
        }

        if ($phone != "") {
            if ($and == "") {
                $and = " and ";
            }

            $str .= $and . " phone like '%". $phone ."%' ";
        }

        if ($test_user != -1) {
            if ($and == "") {
                $and = " and ";
            }
            $str .= $and . " is_test_user =  " . $test_user;
        }

        if ($originid!= -1) {
            if ($and == "") {
                $and = " and ";
            }
            $str .= $and . " originid =  " . $originid;
        }

        if ($str != "") {
            $str = $where . $str;
        }
        return $str;
    }

    public function get_user_list ( $id_list) {
        $in_str = $this->where_get_in_str("userid",$id_list);
        $sql    = $this->gen_sql("select userid, nick, face from %s where %s",
                                 self::DB_TABLE_NAME,
                                 [$in_str]
        );

        return $this->main_get_list($sql,function($item ){
            return $item["userid"] ;
        });
    }

    public function get_closest_list($start_time,$end_time){
        $sql=$this->gen_sql("select userid , phone ,grade,user_agent  from %s "
                            ."where reg_time >=%u and reg_time<%u ",
                            self::DB_TABLE_NAME,
                            $start_time, $end_time
        );
        return $this->main_get_list($sql) ;
    }


    public function get_origin($userid){
        $sql=$this->gen_sql("select originid, origin, assistantid from %s where userid = %u ",
                            self::DB_TABLE_NAME,
                            $userid
        );
        return $this->main_get_row($sql) ;
    }

    public function set_stu_origin($userid,$originid,$origin_userid,$origin)
    {
        $sql=$this->gen_sql("update %s set originid ='%s',origin_userid = '%s' ,origin = '%s'"
                            ." where userid='%u'",
                            self::DB_TABLE_NAME,
                            $originid,
                            $origin_userid,
                            $origin,
                            $userid
        );
        return $this->main_update($sql);
    }

    public function set_test_type($userid,$type)
    {
        $sql=$this->gen_sql("update %s set is_test_user='%s'"
                            ." where userid='%u'",
                            self::DB_TABLE_NAME,
                            $type,
                            $userid
        );
        return $this->main_update($sql);
    }

    public function set_student_type($userid,$type,$is_auto_set_type_flag=0,$lesson_stop_reason="")
    {
        /*
          $sql = sprintf("update %s set type = %u where userid = %u",
          self::DB_TABLE_NAME,
          $type,
          $userid
          );
        */
        $sql=$this->gen_sql("update %s set type='%s',is_auto_set_type_flag='%s',stu_lesson_stop_reason='%s' "
                            ." where userid='%u'",
                            self::DB_TABLE_NAME,
                            $type,
                            $is_auto_set_type_flag,
                            $lesson_stop_reason,
                            $userid
        );
        //dd($sql);
        return $this->main_update($sql);
    }


    public function get_user_nick_by_id($userid, $role)
    {
        switch($role){
        case STUDENT_ROLE:
            $sql = sprintf("select nick from %s where userid = %u",
                           self::DB_TABLE_NAME,
                           $userid);
            break;
        case TEACHER_ROLE:
            $sql = sprintf("select nick from %s where teacherid = %u",
                           \App\Models\t_teacher_info::DB_TABLE_NAME,
                           $userid);
            break;
        case PAR_ROLE:
            $sql =sprintf("select nick from %s where parentid = %u",
                          \App\Models\t_parent_info::DB_TABLE_NAME,
                          $userid);
            break;
        default:
            return false;
        }

        $nick = $this->main_get_value($sql, "");
        if("" == $nick)
            return false;
        return $nick;
    }

    public function get_seller_list_for_select ( $id,$gender, $nick_phone,  $page_num,$seller_adminid) {
        $where_arr = array(
            array( "s.gender=%d", $gender, -1 ),
            array( "s.userid=%d", $id, -1 ),
            array( "admin_revisiterid=%d", $seller_adminid, -1 ),
        );
        $where_arr = $this->student_search_info_sql($nick_phone, 's', $where_arr);

        $sql = $this->gen_sql_new("select s.userid as id , s.nick, ss.phone,s.gender,s.realname  "
                                  ." from   %s s , %s ss  where s.userid=ss.userid  and %s ",
                                  self::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $where_arr );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_ass_list_for_select ( $id,$gender, $nick_phone,  $page_num,$ass_adminid) {
        $where_arr = array(
            array( "s.gender=%d", $gender, -1 ),
            array( "s.userid=%d", $id, -1 ),
            array( "m.uid=%d", $ass_adminid, -1 ),
        );
        $where_arr = $this->student_search_info_sql($nick_phone,'s',$where_arr);

        $sql = $this->gen_sql_new("select s.userid as id , s.nick, s.phone,s.gender,s.realname  "
                                  ." from %s s , %s a,%s m "
                                  ." where s.assistantid=a.assistantid"
                                  ." and a.phone=m.phone and %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_list_for_select($id,$gender, $nick_phone,  $page_num,$adminid=-1){
        $where_arr = array(
            array( "gender=%d", $gender, -1 ),
            array( "userid=%d", $id, -1 ),
        );
        $where_arr = $this->student_search_info_sql($nick_phone,'',$where_arr);

        $sql = $this->gen_sql_new("select userid as id , nick, phone,gender,realname "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_student_list($page_num){
        $sql = sprintf("select * from %s",
                       self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function set_lesson_count_info($studentid, $lesson_total_all, $lesson_left_all,$last_lesson_time)
    {
        $this->field_update_list($studentid,[
            "lesson_count_all"  => $lesson_total_all,
            "lesson_count_left"  => $lesson_left_all,
            "last_lesson_time"  => $last_lesson_time,
        ]);
    }

    public function reset_lesson_count($studentid) {
        $lesson_total_all  = $this->t_order_info->get_lesson_total_all($studentid);
        $lesson_use_all    = $this->t_lesson_info->get_lesson_use_all($studentid);
        $lesson_refund_all = $this->t_order_refund->get_order_refund_all($studentid);
        $lesson_split_all  = $this->t_order_info->get_order_split_all($studentid);
        $lesson_left_all   = (int)$lesson_total_all-(int)$lesson_use_all-(int)$lesson_refund_all-(int)$lesson_split_all;

        $last_lesson_time = $this->t_lesson_info->get_last_lesson_time($studentid);
        $money_all        = $this->t_order_info->get_money_all($studentid);
        $first_money      = $this->t_order_info->get_first_money($studentid);

        //得到phone
        $phone=$this->get_phone($studentid);
        $is_test_user=$this->get_is_test_user($studentid);
        $this->t_seller_student_info->set_money_all($phone,$money_all,$first_money);

        $this->field_update_list($studentid,[
            "lesson_count_all"  => $lesson_total_all,
            "lesson_count_left" => $lesson_left_all,
            "last_lesson_time"  => $last_lesson_time,
            "money_all"         => $money_all,
        ]);

        if($lesson_left_all<0 && $studentid!=61737 && $is_test_user=0){
            $message = $studentid."学生课时为负!";
            \App\Helper\Common::send_mail("xcwenn@qq.com","学生课时出错!",$message);
            \App\Helper\Common::send_mail("wg392567893@163.com","学生课时出错!",$message);
        }

        return true;
    }

    public function get_parent_info($userid)
    {
        $sql = $this->gen_sql("select nick, grade, parent_name, phone, address from %s where userid = %u ",
                              self::DB_TABLE_NAME,
                              $userid
        );

        return $this->main_get_row($sql);

    }
    public function get_tea_lesson_by_interval_str($teacherid)
    {
        $sql = sprintf("select a.userid, a.nick, a.grade, lessonid, lesson_type, lesson_start, lesson_end ".
                       " from %s a, %s b where a.userid = b.userid and teacherid = %u and "
                       ." and lesson_del_flag =0 "

                       ." order by lesson_start ",
                       self::DB_TABLE_NAME,
                       \App\Models\t_lesson_info::DB_TABLE_NAME,
                       $teacherid
        );

        return $this->main_get_list( $sql  );
    }

    public function get_tea_lesson_by_interval($teacherid, $time_start, $time_end)
    {
        $sql = sprintf("select a.userid, a.nick, a.grade, lessonid, lesson_type, lesson_start, lesson_end ".
                       " from %s a, %s b where a.userid = b.userid and teacherid = %u and ".
                       " lesson_start > %u and lesson_start < %u"
                       ." and lesson_del_flag =0 "
                       ." order by lesson_start ",
                       self::DB_TABLE_NAME,
                       \App\Models\t_lesson_info::DB_TABLE_NAME,
                       $teacherid,
                       $time_start,
                       $time_end
        );

        return $this->main_get_list( $sql  );
    }

    public function get_stu_all_info($userid)
    {
        $where_arr = [
            ["userid=%u",$userid,-1]
        ];
        $sql = $this->gen_sql_new("select userid,realname, s.nick,s.stu_email, s.face, s.birth, s.originid, praise, s.phone,"
                       ." s.stu_phone, s.gender, s.grade, s.operator_note type, parent_name, parent_type, address,"
                       ." school, editionid, region, p.phone as parent_phone, assistantid, seller_adminid,"
                       ." reg_time , init_info_pdf_url, user_agent, guest_code, host_code, s.parentid, s.is_test_user, "
                       ." p.wx_openid as parent_wx_openid,s.province,s.city,s.area "
                       ." from %s as s "
                       ." left join %s as p on s.parentid = p.parentid "
                       ." where %s  "
                       ,self::DB_TABLE_NAME
                       ,t_parent_info::DB_TABLE_NAME
                       ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function update_originid_app($userid,$origin,$seller_adminid)
    {
        $sql=$this->gen_sql("update %s set origin='%s'  where userid=%u  and origin ='' ",
                            self::DB_TABLE_NAME,$origin, $userid);
        $this->main_update($sql);

        $this->field_update_list ($userid, [
            "seller_adminid" => $seller_adminid,
        ]);
    }
    public function update_origin_list( $userid_list,$origin ) {
        $where_arr=[];
        $where_arr[]= $this->where_get_in_str("userid",$userid_list,false);


        $sql=$this->gen_sql_new(
            "update %s set origin='%s' where  %s ",
            self::DB_TABLE_NAME,
            $origin,
            $where_arr
        );
        return $this->main_update($sql);
    }

    public function get_userid_by_appstore($telphone){
        $sql=$this->gen_sql("select userid from %s where phone = '%s'",
                            self::DB_TABLE_NAME,
                            $telphone
        );
        return $this->main_get_value($sql);
    }


    public function set_seller_adminid($phone_list_str,$admin_revisiterid){
        $sql = sprintf("update %s set seller_adminid = %u where phone = '%s'",
                       self::DB_TABLE_NAME,
                       $admin_revisiterid,
                       $phone_list_str
        );
        return true;
    }

    public function set_spree_details($studentid,$spree){
        $sql = $this->gen_sql(" update %s set spree = '%s' where userid = %u ",
                              self::DB_TABLE_NAME,
                              $spree,
                              $studentid
        );
        return $this->main_update($sql);
    }

    public function get_user_list_by_lesson_count(
        $page_num,$lesson_count_start,$lesson_count_end,$lesson_start,$lesson_end,$assistantid,$grade,$type
    ){
        $where_arr = [
            // ["s.lesson_count_left >=%u",$lesson_count_start,-1 ],
            // ["s.lesson_count_left <=%u",$lesson_count_end,-1 ],
            ["s.last_lesson_time >=%u",$lesson_start,-1 ],
            ["s.last_lesson_time <=%u",$lesson_end,-1 ],
            ["s.assistantid=%u", $assistantid, -1] ,
            ["s.grade=%u",$grade,-1],
            "s.is_test_user=0"
        ];

        // $refund_sql="true";
        // if(in_array($type,[1,2])){
        //     if($type==1){
        //         $exists_str = "not exists";
        //     }elseif($type==2){
        //         $exists_str = "exists";
        //     }
        //     $refund_sql = $this->gen_sql_new("%s (select 1 from %s where s.userid=userid)"
        //                                      ,$exists_str
        //                                      ,t_order_refund::DB_TABLE_NAME
        //     );
        //     $where_arr[]= "type=1";
        // }

        // $sql = $this->gen_sql_new("select userid,lesson_count_all,lesson_count_left,last_lesson_time,assistantid,grade "
        //                           ." from %s s"
        //                           ." where %s "
        //                           ." and last_lesson_time>0"
        //                           ." and %s"
        //                           ." order by last_lesson_time desc "
        //                           ,self::DB_TABLE_NAME
        //                           ,$where_arr
        //                           ,$refund_sql
        // );
        // return $this->main_get_list_by_page($sql,$page_num);

        $have_flag="";
        if(in_array($type,[1,2])){
            if($type==1){
                $where_arr[]="(r.apply_time is null or r.apply_time<s.last_lesson_time)";
                $where_arr[]="s.type=1";
            }elseif($type==2){
                $where_arr[]="r.apply_time>=s.last_lesson_time";
                $have_flag="having (sum(o.lesson_left) < 100)";
            }
        }else{
            $where_arr[] = ["s.lesson_count_left >=%u",$lesson_count_start,-1 ];
            $where_arr[] = ["s.lesson_count_left <=%u",$lesson_count_end,-1 ];
        }

        $sql = $this->gen_sql_new("select s.userid,s.lesson_count_all,s.lesson_count_left,"
                                  ."s.last_lesson_time,s.assistantid,s.grade,sum(o.lesson_left) left_all"
                                  ." from %s s  left join %s r on s.userid = r.userid and r.contract_type in (0,3) "
                                  ." and not exists (select 1 from %s where userid=r.userid and r.contract_type in (0,3) "
                                  ."and apply_time>r.apply_time)"
                                  ." left join %s o on s.userid= o.userid and o.contract_type in (0,3)"
                                  ." where %s  group by s.userid %s ",
                                  self::DB_TABLE_NAME,
                                  t_order_refund::DB_TABLE_NAME,
                                  t_order_refund::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $have_flag
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function get_user_list_by_lesson_count_new($lesson_start,$lesson_end){
        $where_arr = [
            // "lesson_count_left=0",
            ["last_lesson_time >=%u",$lesson_start,-1 ],
            ["last_lesson_time < %u",$lesson_end,-1 ],
            "is_test_user=0",
            'type=1',
        ];

        $refund_sql="true";
        $type = 1;
        if(in_array($type,[1,2])){
            if($type==1){
                $exists_str = "not exists";
            }elseif($type==2){
                $exists_str = "exists";
            }
            $refund_sql = $this->gen_sql_new("%s (select 1 from %s where s.userid=userid)"
                                             ,$exists_str
                                             ,t_order_refund::DB_TABLE_NAME
            );
        }

        $sql = $this->gen_sql_new("select count( distinct userid)  from %s s where %s  and %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$refund_sql
        );
        echo $sql;
        return $this->main_get_value($sql);
    }

    public function get_user_list_by_lesson_count_new_b1($lesson_start,$lesson_end){
        $where_arr = [
            // "lesson_count_left=0",
            ["last_lesson_time >=%u",$lesson_start,-1 ],
            ["last_lesson_time < %u",$lesson_end,-1 ],
            "is_test_user=0",
            'type=1',
        ];

        $refund_sql="true";
        $type = 1;
        if(in_array($type,[1,2])){
            if($type==1){
                $exists_str = "not exists";
            }elseif($type==2){
                $exists_str = "exists";
            }
            $refund_sql = $this->gen_sql_new("%s (select 1 from %s where s.userid=userid)"
                                             ,$exists_str
                                             ,t_order_refund::DB_TABLE_NAME
            );
        }

        $sql = $this->gen_sql_new("select count( distinct userid)  from %s s where %s  and %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$refund_sql
        );
        return $this->main_get_value($sql);
    }


    public function get_parent_total_list($userid){
        $sql = $this->gen_sql("select parentid from %s where userid = %u ",
                              self::DB_TABLE_NAME,
                              $userid
        );

        return $this->main_get_list($sql);

    }

    public function inc_revist_num($userid)
    {
        $sql =$this->gen_sql("update %s set revisit_cnt=revisit_cnt+1 where userid = %u",
                             self::DB_TABLE_NAME,
                             $userid
        );
        return $this->main_update($sql);
    }


    public function get_every_assistantid(){
        $sql= $this->gen_sql("select distinct(assistantid )from %s ",
                             self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_every_studentid(){
        $sql= $this->gen_sql("select distinct(userid) from %s ",
                             self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_count_all_by_assistantid($id)
    {
        $sql = $this->gen_sql("select sum(lesson_count_all) from %s where assistantid = %u ",
                              self::DB_TABLE_NAME,
                              $id
        );
        return $this->main_get_value($sql);
    }

    public function get_first_revisit_by_assistantid($id)
    {
        $sql = $this->gen_sql("select count(userid) from %s where assistantid = %u ",
                              self::DB_TABLE_NAME,
                              $id
        );
        return $this->main_get_value($sql);
    }

    public function get_yi_first_revisit_by_assistantid($id)
    {
        $sql = $this->gen_sql("select count(userid) from %s where assistantid = %u and revisit_status = 1",
                              self::DB_TABLE_NAME,
                              $id
        );
        return $this->main_get_value($sql);
    }

    public function get_xq_revisit_by_assistantid($id)
    {
        $sql = $this->gen_sql("select count(userid) from %s where assistantid = %u and revisit_status = 1",
                              self::DB_TABLE_NAME,
                              $id
        );
        return $this->main_get_value($sql);
    }

    public function get_yd_revisit_by_assistantid($id)
    {
        $sql = $this->gen_sql("select count(userid) from %s where assistantid = %u and revisit_status = 2",
                              self::DB_TABLE_NAME,
                              $id
        );
        return $this->main_get_value($sql);
    }
    public function get_need_yd_by_assistantid($id)
    {
        $sql = $this->gen_sql("select sum(lesson_count_all) from %s where assistantid = %u and revisit_status = 2",
                              self::DB_TABLE_NAME,
                              $id
        );
        return $this->main_get_value($sql);
    }

    public function get_revisit_count_all_by_assistantid($id)
    {
        $sql = $this->gen_sql("select sum(revisit_time) from %s where assistantid = %u ",
                              self::DB_TABLE_NAME,
                              $id
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_praise($userid) {
        $sql = sprintf("select praise from %s where userid = %u",
                       self::DB_TABLE_NAME,
                       $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_student_type_update($userid,$type) {
        /* if($type==1){
           $end_time = $this->t_lesson_info_b2->get_stu_last_lesson_time($userid);
           $this->t_student_info->field_update_list($userid,array('last_lesson_time'=>$end_time));
           }*/
        return $this->t_student_info->field_update_list($userid,array('type'=>$type,'type_change_time'=>time()));

    }

    public function tongji_assisent($assistantid  ) {
        $sql=$this->gen_sql_new(
            "select sum(type=0) as status1_count from %s where assistantid=%u and is_test_user=0",
            self::DB_TABLE_NAME, $assistantid);
        return $this->main_get_row($sql);
    }

    public function get_test_lesson_lost_user_list($page_num,$grade,$start_time,$end_time, $can_reset_seller_flag = -1, $random_order_flag=false  ) {
        $max_last_revisit_admin_time=time(NULL)-86400*60;
        $where_arr=[
            ["s.grade=%u",$grade,-1] ,
            ["lesson_start>=%u",$start_time,-1] ,
            ["lesson_end<%u",$end_time,-1] ,
        ];

        if( $can_reset_seller_flag ==1  ) { //
            $where_arr[]= "last_revisit_admin_time<$max_last_revisit_admin_time" ;
        } else if( $can_reset_seller_flag ==0  ) { //
            $where_arr[]= "last_revisit_admin_time>=$max_last_revisit_admin_time" ;
        }

        $order_str="";
        if ($random_order_flag ) {
            $order_str="order by rand()";
        }

        $sql= $this->gen_sql_new( "select  s.userid,s.phone , last_revisit_admin_time, last_revisit_adminid, lesson_start , s.gender, s.grade , nick from %s s, %s l where l.userid=s.userid  and s.is_test_user=0  and l.lesson_type=2 and lesson_start>0 and lesson_count_all =0 and    %s "

                                  ." and lesson_del_flag =0 "
                                  ." $order_str " ,
                                  self::DB_TABLE_NAME,t_lesson_info::DB_TABLE_NAME ,$where_arr) ;

        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_stu_info($assistantid){
        $where_arr=[
            ["assistantid = %u",$assistantid,0],
        ];
        $sql=$this->gen_sql_new("select userid "
                                ." from %s"
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql,function($item ){
            return $item["userid"];
        });
    }

    public function get_userid_by_phone($phone){
        $sql = $this->gen_sql_new("select userid from %s where phone='%s'"
                                  ,self::DB_TABLE_NAME
                                  ,$phone
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_ass_all($assistantid,$userid=-1,$student_type=0){
        $where_arr=[
            ["assistantid= %u",$assistantid, -1 ],
            ["userid= %u",$userid, -1 ],
            ["type= %u",$student_type, -1 ],
            #"(type= 0 or (type = 1 and lesson_count_left=lesson_count_all and lesson_count_all <>0))",
            "is_test_user= 0",
            "assistantid > 0"
        ];

        $sql = $this->gen_sql_new("select userid,grade,nick as user_nick".
                                  " from %s where  %s ",
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function register( $phone, $passwd, $reg_channel , $grade , $ip, $nick,  $region)
    {
        //$this->switch_readwrite_database();
        //$this->task->t_phone_to_user->start_transaction();
        $userid=$this->task->t_phone_to_user->get_userid_by_phone($phone,E\Erole::V_STUDENT);
        if($userid>0){
            return $userid;
        }

        $userid= $this->task->t_user_info->user_reg($passwd,$reg_channel,ip2long($ip));
        if(!$userid){
            return false;
        }
        $ret = $this->task->t_phone_to_user->add($phone,E\Erole::V_STUDENT,$userid);
        if(!$ret){
            //$this->task->t_phone_to_user->rollback();
            return false;
        }
        //$this->task->t_phone_to_user->commit();

        $ret = $this->add_student($userid,$grade,$phone,$nick,$region);
        if(!$ret){
            return false;
        }
        return $userid;
    }

    public function add_student($userid,$grade,$phone,$nick,$region){
        return $this->row_insert([
            "userid"   => $userid,
            "grade"    => $grade,
            "phone"    => $phone,
            "nick"     => $nick,
            "region"   => $region,
            "reg_time" => time(NULL),
        ]);
    }

    public function get_user_info_by_seller($adminid){
        $where_arr[] =['seller_adminid=%u',$adminid,-1];
        $sql = $this->gen_sql_new("select userid,seller_adminid from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_row_by_phone($phone){
        $this->where_arr_add_str_field($where_arr,'phone',$phone);
        $sql = $this->gen_sql_new("select * from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_all_lesson_info()  {
        $sql =$this->gen_sql_new(
            "select sum(lesson_count_all)/100 as  lesson_count_all , sum(lesson_count_left)/100 as lesson_count_left from %s where  is_test_user=0 ",
            self::DB_TABLE_NAME

        );
        return $this->main_get_row($sql);
    }

    public function count_stu_num(){
        $where_arr=[
            "type=0 ",
            "is_test_user=0 ",
            "assistantid>0",
        ];

        $have = "(sum(b.lesson_count)*8 +sum(if(substring(b.end_time,1,2)>=15,lesson_count,0))- sum(a.lesson_count_left)) >=0";

        $sql = $this->gen_sql_new("select a.userid, a.lesson_count_left,sum(if(substring(b.end_time,1,2)>=15,lesson_count,0)),sum(if(substring(b.start_time,1,1)=7,lesson_count,0)),sum(b.lesson_count) as lesson_total from %s a left join %s b on a.userid = b.userid ".
                                  "  where  %s group by a.userid having %s",
                                  self::DB_TABLE_NAME,
                                  t_week_regular_course::DB_TABLE_NAME,
                                  $where_arr,
                                  $have
        );
        return $this->main_get_list($sql);
    }

    public function noti_ass_order($userid ,$account, $check_order_flag=true) {
        // $template_id="1600puebtp9CfcIg41Oz9VHu6iRXHAJ8VpHKPYvZXT0";//old
        $template_id="9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        $assistantid= $this->get_assistantid($userid);
        if($check_order_flag ) {
            $order_count=$this->t_order_info-> get_new_order_count($userid);
            if ($order_count==0)  {
                return ;
            }
        }
        $user_info = $this->field_get_list($userid,"nick,init_info_pdf_url,origin_userid,assistantid,ass_master_adminid,origin_assistantid");
        $nick = $user_info["nick"];

        $ass_account=$this->t_assistant_info->get_account_by_id($assistantid);
        if(!$ass_account) {

            /* $ass_account="cora";
               $ret= $this->t_manager_info->send_wx_todo_msg  (
               $ass_account,
               "销售-$account",
               "交接单 更新 || 合同生效",
               "学生-$nick",
               "/user_manage_new/ass_contract_list?studentid=$userid");*/

            /*
              $ass_account="jim";
              $ret= $this->t_manager_info->send_wx_todo_msg  (
              $ass_account,
              "销售-$account",
              "交接单 更新 || 合同生效",
              "学生-$nick",
              "/user_manage_new/ass_contract_list?studentid=$userid");
            */

        }else{
            $ret= $this->t_manager_info->send_wx_todo_msg  (
                $ass_account,
                "销售-$account",
                "交接单 更新",
                "学生-$nick",
                "/user_manage_new/ass_contract_list?studentid=$userid");
        }

        // $seller_adminid = $this->task->t_seller_student_new->get_admin_revisiterid($userid);
        $seller_adminid = $this->task->t_manager_info->get_id_by_account($account);
        $origin_assistantid = $this->get_assistantid($user_info["origin_userid"]);
        $adminid = $this->t_assistant_info->get_adminid_by_assistand($origin_assistantid);
        $check_master = $this->t_admin_group_name->check_is_master(1,$user_info["ass_master_adminid"]);

        if(($user_info["ass_master_adminid"]==0 || $check_master!=1)  && !empty($user_info["init_info_pdf_url"])){
            //记录一条数据
            $phone = $this->get_phone($userid);
            $this->task->t_book_revisit->add_book_revisit(
                $phone,
                $nick."合同已确认/交界单已经提交",
                "system"
            );


            //区分已有助教,但原有组长离职的情况
            $adminid_ass_old = $this->t_assistant_info->get_adminid_by_assistand($assistantid);
            $del_flag = $this->t_manager_info->get_del_flag($adminid_ass_old);
            if($adminid_ass_old>0 &&  $del_flag!=1){
                $master_adminid_arr = $this->t_admin_group_user->get_group_master_adminid($adminid_ass_old);
                $master_adminid = @$master_adminid_arr["group_adminid"];
            }else{                
                //获取销售校区
                $campus_id = $this->task->t_admin_group_user->get_campus_id_by_adminid($seller_adminid);
                if($user_info["origin_assistantid"]>0){
                    $account_role = $this->task->get_account_role($user_info["origin_assistantid"]);
                    if($account_role==1){
                        $campus_id = $this->task->t_admin_group_user->get_campus_id_by_adminid($user_info["origin_assistantid"]);
                    }
                }
                $master_adminid_list = $this->task->t_admin_group_name->get_ass_master_adminid_by_campus_id($campus_id);
                $master_list=[];
                foreach($master_adminid_list as $tt){
                    $master_list[$tt["master_adminid"]]=$tt["master_adminid"];
                }
                $num_all_new = count($master_list);
                $j=0;
                foreach($master_list as $val){
                    $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_NEW_$val");
                    if (!$json_ret) {
                        $json_ret=0;
                    }
                    \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_NEW_$val", $json_ret);
                    if($json_ret==1){
                        $j++;
                    }
                }
                if($j==$num_all_new){
                    foreach($master_list as $val){
                        \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_NEW_$val", 0);
                    }
                }

                if($userid>0){
                    foreach($master_list as $val){
                        $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_NEW_$val");
                        if($json_ret==0){
                            $master_adminid= $val;
                            \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_NEW_$val", 1);
                            break;
                        }
                    }
                }
            }

            if(empty($master_adminid)){

                $master_adminid=0;
                if($user_info["origin_userid"] >0 && $seller_adminid==$adminid){
                    $this->field_update_list($userid,[
                        "assistantid"     => $origin_assistantid,
                        "ass_assign_time" => time()
                    ]);

                    $this->t_lesson_info->set_user_assistantid( $userid,$origin_assistantid);
                    $this->t_course_order->set_user_assistantid($userid,$origin_assistantid);

                    $wx_id_ass = $this->t_manager_info->get_wx_id($adminid);
                    $noti_account = $this->t_manager_info->get_account($adminid);
                    $this->t_manager_info->send_wx_todo_msg_by_adminid ($seller_adminid,"通知人:理优教育","学生分配助教通知","您好,学生".$nick."已经分配给助教".$noti_account."老师,助教微信号为:".$wx_id_ass,"");

                    $master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($adminid);

                    if(empty($master_adminid)){
                        $ass_leader_arr = $this->t_admin_group_name->get_leader_list(1);
                        $num_all = count($ass_leader_arr);
                        $i=0;
                        foreach($ass_leader_arr as $val){
                            $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_$val");
                            if (!$json_ret) {
                                $json_ret=0;
                            }
                            \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", $json_ret);
                            if($json_ret==1){
                                $i++;
                            }
                        }
                        if($i==$num_all){
                            foreach($ass_leader_arr as $val){
                                \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", 0);
                            }
                        }

                        if($userid>0){
                            foreach($ass_leader_arr as $val){
                                $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_$val");
                                if($json_ret==0){
                                    $master_adminid= $val;
                                    \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", 1);
                                    break;
                                }
                            }
                        }

                    }

                }elseif(!empty($user_info["init_info_pdf_url"])){
                    if($user_info["origin_userid"] >0){

                        $master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($adminid);
                        if(empty($master_adminid)){
                            $ass_leader_arr = $this->t_admin_group_name->get_leader_list(1);
                            $num_all = count($ass_leader_arr);
                            $i=0;
                            foreach($ass_leader_arr as $val){
                                $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_$val");
                                if (!$json_ret) {
                                    $json_ret=0;
                                }
                                \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", $json_ret);
                                if($json_ret==1){
                                    $i++;
                                }
                            }
                            if($i==$num_all){
                                foreach($ass_leader_arr as $val){
                                    \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", 0);
                                }
                            }

                            if($userid>0){
                                foreach($ass_leader_arr as $val){
                                    $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_$val");
                                    if($json_ret==0){
                                        $master_adminid= $val;
                                        \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", 1);
                                        break;
                                    }
                                }
                            }

                        }
                    }elseif($user_info["assistantid"]>0){
                        $adminid = $this->t_assistant_info->get_adminid_by_assistand($user_info["assistantid"]);
                        $master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($adminid);
                    }
                    if(empty($master_adminid)){
                        $ass_leader_arr = $this->t_admin_group_name->get_leader_list(1);
                        $num_all = count($ass_leader_arr);
                        $i=0;
                        foreach($ass_leader_arr as $val){
                            $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_$val");
                            if (!$json_ret) {
                                $json_ret=0;
                            }
                            \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", $json_ret);
                            if($json_ret==1){
                                $i++;
                            }
                        }
                        if($i==$num_all){
                            foreach($ass_leader_arr as $val){
                                \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", 0);
                            }
                        }

                        if($userid>0){
                            foreach($ass_leader_arr as $val){
                                $json_ret=\App\Helper\Common::redis_get_json("ASS_AUTO_ASSIGN_$val");
                                if($json_ret==0){
                                    $master_adminid= $val;
                                    \App\Helper\Common::redis_set_json("ASS_AUTO_ASSIGN_$val", 1);
                                    break;
                                }
                            }
                        }

                    }

                }
            }

            if(!empty($master_adminid)){
                $r = $this->field_update_list($userid,[
                    "ass_master_adminid"=>$master_adminid,
                    "master_assign_time"=>time(),
                    "type"=>0
                ]);
                $old_type = $this->get_type($userid);
                $this->task->t_student_type_change_list->row_insert([
                    "userid"    =>$userid,
                    "add_time"  =>time(),
                    "type_before" =>$old_type,
                    "type_cur"    =>0,
                    "change_type" =>1,
                    "adminid"     =>0,
                    "reason"      =>"新签续费"
                ]);

                if($r){
                    $ass_account = $this->t_manager_info->get_account($master_adminid);
                    $this->t_manager_info->send_wx_todo_msg  (
                        $ass_account,
                        "销售-".$account,
                        "交接单 更新 || 合同生效",
                        "学生".$nick,
                        "http://admin.leo1v1.com/user_manage_new/ass_contract_list?studentid=$userid");

                    $group_name = $this->task->t_admin_group_name->get_group_name_by_master_adminid( $master_adminid);
                    $wx_id = $this->task->t_manager_info->get_wx_id($master_adminid);
                    $this->t_manager_info->send_wx_todo_msg_by_adminid ($seller_adminid,"学生分配助教组长","学生分配助教组长通知","您好,您的学员".$nick."已经分配至".$group_name.",组长:".$ass_account.",微信号:".$wx_id.",状态:未分配助教","");

                }else{
                     $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"学生未分配助教组长","学生未分配助教组长通知","您好,学员".$nick."未找到对应助教助长,更新数据表失败","");
                }
            }else{
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"学生未分配助教组长","学生未分配助教组长通知","您好,学员".$nick."未找到对应助教助长","");
            }
        }elseif($user_info["ass_master_adminid"]>0 && $check_master==1 && !empty($user_info["init_info_pdf_url"])){
            $adminid_ass = $this->t_assistant_info->get_adminid_by_assistand($assistantid);
            $del_flag = $this->t_manager_info->get_del_flag($adminid_ass);
            if($adminid_ass ==0 || ($adminid_ass>0 && $del_flag==1)){
                $ass_account = $this->t_manager_info->get_account($user_info["ass_master_adminid"]);
                $this->t_manager_info->send_wx_todo_msg  (
                    $ass_account,
                    "销售-".$account,
                    "交接单 更新 || 合同生效",
                    "学生".$nick.",原助教已离职或无助教,请重新分配助教",
                    "http://admin.leo1v1.com/user_manage_new/ass_contract_list?studentid=$userid");

            }
            $r = $this->field_update_list($userid,[
                "type"=>0
            ]);
            $old_type = $this->get_type($userid);
            $this->task->t_student_type_change_list->row_insert([
                "userid"    =>$userid,
                "add_time"  =>time(),
                "type_before" =>$old_type,
                "type_cur"    =>0,
                "change_type" =>1,
                "adminid"     =>0,
                "reason"      =>"新签续费"
            ]);


        }


    }

    public function has_parent_logined($orderid)
    {
        $sql = $this->gen_sql_new("select parentid "
                                  ." from %s s, %s o "
                                  ." where s.userid = o.userid "
                                  ." and orderid = %u"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$orderid
        );

        $parentid = $this->main_get_value( $sql );

        $ret  = $this->t_parent_info->get_has_login($parentid);
        $info = [];
        $info['parentid'] = $parentid;
        if($ret == 1)
            $info['has_login'] = true;
        else
            $info['has_login'] = false;
        return $info;
    }

    public function get_all_stu_count(){
        $sql = $this->gen_sql_new("select count(1) from %s where is_test_user=0"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_lesson_left_list(){
        $sql=$this->gen_sql_new("select s.nick,c.subject,s.lesson_count_left,"
                                ." sum(c.assigned_lesson_count) as lesson_total,"
                                ." sum(l.lesson_count) as lesson_cost "
                                ." from %s s "
                                ." left join %s c on s.userid=c.userid "
                                ." left join %s l on c.courseid=l.courseid "
                                ." where s.lesson_count_left>0 "
                                ." and s.is_test_user=0 "
                                ." and l.lesson_del_flag=0"
                                ." and l.confirm_flag!=2"
                                ." and c.assigned_lesson_count>0"
                                ." and c.course_type in (0,1,3)"
                                ." group by c.userid,c.subject"
                                ,self::DB_TABLE_NAME
                                ,t_course_order::DB_TABLE_NAME
                                ,t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_user_info_for_api($userid){
        $sql = $this->gen_sql_new("select s.realname,s.nick stu_nick,s.birth,s.school,s.gender,i.xingetedian,s.reg_time,s.grade,s.phone,s.address,s.user_agent,s.lesson_count_left,s.lesson_count_all,s.praise,t.stu_test_paper,t.tea_download_paper_time,i.aihao ,i.yeyuanpai ,n.stu_character_info,s.parent_name,n.stu_score_info ,n.stu_test_ipad_flag ,n.has_pad ,n.next_revisit_time,n.user_desc   ".
                                  " from %s s".
                                  " left join %s t on s.userid = t.userid ".
                                  " left join %s i on s.userid = i.userid ".
                                  " left join %s n on s.userid = n.userid ".
                                  " where s.userid = %u",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_init_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_row($sql);
    }

    public function get_user_init_info_for_api($userid){
        $sql = $this->gen_sql_new("select i.real_name realname,s.nick stu_nick,i.birth,i.school,i.gender,i.xingetedian,s.reg_time,i.grade,i.phone,i.addr address,s.user_agent,s.lesson_count_left,s.lesson_count_all,s.praise,t.stu_test_paper,t.tea_download_paper_time,i.aihao ,i.yeyuanpai,n.stu_character_info,s.realname s_realname,s.birth s_birth,s.school s_school,s.gender s_gender,s.grade s_grade,s.phone s_phone,s.address s_address,n.user_desc,n.next_revisit_time ".
                                  " from %s s left join %s i on s.userid = i.userid".
                                  " left join %s t on s.userid = t.userid ".
                                  " left join %s n on s.userid = n.userid".
                                  " where s.userid = %u",
                                  self::DB_TABLE_NAME,
                                  t_student_init_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_row($sql);
    }

    public function tongji_get_region_info($userid_query_arr,  $group_by_field_name ){
        $userid_in_sql=$this->get_sql_lesson_users($userid_query_arr  );
        $where_arr=[
            $userid_in_sql
        ];
        $sql= $this->gen_sql_new(
            "select $group_by_field_name , count(*) as count from %s s "
            ." where %s   "
            ." group by   $group_by_field_name  order by count desc",self::DB_TABLE_NAME, $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_sql_lesson_users( $userid_query_arr ,$id_str="s.userid" ) {
        $start_time= $userid_query_arr["start_time"];
        $end_time= $userid_query_arr["end_time"];
        $origin_ex= $userid_query_arr["origin_ex"];
        $grade= $userid_query_arr["grade"];
        $subject= $userid_query_arr["subject"];
        $phone_location= $userid_query_arr["phone_location"];
        $origin_from_user_flag= $userid_query_arr["origin_from_user_flag"];
        $competition_flag = $userid_query_arr["competition_flag"];
        $where_arr=[
            "s_1.is_test_user=0",
            ["s_1.grade=%u", $grade ,-1 ],
            ["c_1.subject=%u", $subject,-1 ],
            [ "s_1.phone_location like '%s%%'  ", trim( $phone_location ) ,""  ],
            ["c_1.competition_flag=%u", $competition_flag ,-1 ],
        ];

        $where_arr[]=$this->t_origin_key->get_in_str_key_list($origin_ex,"s_1.origin");
        $where_arr[]=  "s_1.lesson_count_left>0";
        if ( $start_time<>1420041600 ) {
            $this->where_arr_add_time_range($where_arr,"s_1.reg_time",$start_time,$end_time);

        }
        $this->where_arr_add_boolean_for_value($where_arr,"s_1.origin_userid", $origin_from_user_flag );

        return $this->gen_sql_new(
            " $id_str  in (select distinct  s_1.userid"
            . " from %s s_1 "
            . " left join %s n_1 on s_1.userid=n_1.userid "
            . " left join %s c_1 on s_1.userid=c_1.userid "
            . " where %s  ) ",
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            $where_arr
        );
    }

    public function tongji_get_subject($userid_query_arr){

        $userid_in_sql=$this->get_sql_lesson_users( $userid_query_arr);
        $where_arr=[
            $userid_in_sql,
            ["c.subject=%u" ,$userid_query_arr["subject"] , -1],
            ["c.competition_flag=%u" ,$userid_query_arr["competition_flag"] , -1], //
        ];

        $sql= $this->gen_sql_new(
            "select  s.userid , subject, competition_flag  "
            . " from %s s "
            . " join  %s c on c.userid=s.userid "
            ." where %s  "
            ." group by  s.userid , c.subject , c.competition_flag ",
            self::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }

    public function tongji_get_lesson_start_by_field_name( $field_name,$userid_query_arr,$check_start_time, $check_end_time  ){

        switch (  $field_name) {
        case "grade" :
            $field_name="l.grade";
            break;
        case "origin" :
            $field_name="s.origin";
            break;

        default:
            break;
        }

        $userid_in_sql=$this->get_sql_lesson_users( $userid_query_arr);
        $where_arr=[
            $userid_in_sql,
            "confirm_flag<>2",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$check_start_time,$check_end_time);

        $sql= $this->gen_sql_new(
            "select  $field_name  as check_value, sum(lesson_count)/(count(*)*100) as avg_lesson_count"
            . " from %s l "
            . "join %s s on s.userid = l.userid "
            ." where %s  "
            ." group by  $field_name  ",
            t_lesson_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }
    public function tongji_get_contract_type_3_by_field_name( $field_name,$userid_query_arr,$check_start_time, $check_end_time  ){
        switch (  $field_name) {
        case "grade" :
            $field_name="o.grade";
            break;
        case "origin" :
            $field_name="s.origin";
            break;
        default:
            break;
        }



        $userid_in_sql=$this->get_sql_lesson_users( $userid_query_arr);
        //E\Econtract_type
        //E\Econtract_status
        $where_arr=[
            $userid_in_sql,
            "contract_type=3",
            "contract_status in(1,2)",
        ];
        $this->where_arr_add_time_range($where_arr,"pay_time",$check_start_time,$check_end_time);

        $sql= $this->gen_sql_new(
            "select  $field_name  as check_value, count(*)  contract_type_3_count , sum(price)/100 as  contract_type_3_all_money, count(distinct s.userid ) contract_type_3_user_count   "
            . " from %s o "
            . "join %s s on s.userid = o.userid "
            ." where %s  "
            ." group by  $field_name  ",
            t_order_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }

    public function tongji_get_origin_user_by_field_name( $field_name,$userid_query_arr,$check_start_time, $check_end_time  ){

        if ($field_name=="grade") {
            $field_name="s.grade";
        }
        if ( $field_name=="subject") {
            return [];
        }

        $userid_in_sql=$this->get_sql_lesson_users( $userid_query_arr, "origin_userid");
        //E\Econtract_type
        //E\Econtract_status
        $where_arr=[
            $userid_in_sql,
            ["c.subject=%u" ,$userid_query_arr["subject"] , -1],
            ["c.competition_flag=%u" ,$userid_query_arr["competition_flag"] , -1], //
        ];
        $this->where_arr_add_time_range($where_arr,"reg_time",$check_start_time,$check_end_time);

        $sql= $this->gen_sql_new(
            "select  $field_name  as  check_value ,count(*) origin_user_count,  sum(lesson_count_all>0 ) as  succ_origin_user_count "
            . " from %s s "
            ." where %s  "
            ." group by  $field_name ",
            self::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list($sql);
    }

    public function get_ass_stu_info_new($adminid=-1){
        $where_arr=[
            " m.account_role = 1 ",
            // "m.del_flag =0",
            "s.is_test_user=0",
            ["m.uid=%u",$adminid,-1]
        ];

        $sql = $this->gen_sql_new("select m.uid,sum(if(type in (0,2,3,4,5,6),1,0)) all_count,sum(if(type=0,1,0)) read_count,sum(if(type=2,1,0)) stop_count,sum(i.week_lesson_num>0 and i.except_lesson_count >0) except_num,sum(if(i.week_lesson_num>0 and i.except_lesson_count >0,week_lesson_num*except_lesson_count,0)) except_count,count(*) all_stu_num"
                                  ." from %s s left join %s a on a.assistantid = s.assistantid "
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s i on s.userid = i.userid"
                                  ." where %s group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_student_init_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }
    public function get_ass_month_stop_info_new($start_time,$end_time){
        $where_arr=[
            "type=2",
            " m.account_role = 1 ",
            // "m.del_flag =0",
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"s.type_change_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select m.uid,count(distinct userid) num"
                                  ." from %s s left join %s a on a.assistantid = s.assistantid "
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });


    }

    public function get_student_ass_info(){
        $sql= $this->gen_sql_new("select s.userid,m.uid from %s s".
                                 " left join %s a on a.assistantid=s.assistantid".
                                 " left join %s m on a.phone=m.phone".
                                 " where s.assistantid >0 and s.ass_master_adminid =0 and m.uid is not null",
                                 self::DB_TABLE_NAME,
                                 t_assistant_info::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_read_student_ass_info($stu_type=0,$assistantid=-1){
        $where_arr=[
            "s.assistantid >0",
            "m.account_role=1",
            ["s.assistantid=%u",$assistantid,-1]
        ];
        if($stu_type==-2){
            $where_arr[]="s.type <>1";
        }else{
            $where_arr[]=["s.type=%u",$stu_type,-1];
        }

        $sql= $this->gen_sql_new("select s.userid,m.uid from %s s".
                                 " join %s a on a.assistantid=s.assistantid".
                                 " join %s m on a.phone=m.phone".
                                 " where %s ",
                                 self::DB_TABLE_NAME,
                                 t_assistant_info::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $where_arr
        );
        $ret =  $this->main_get_list($sql);
        $userid_list=[];
        foreach($ret as $item){
            $userid_list[$item["uid"]][]=$item["userid"];
        }
        $arr=[];
        foreach($userid_list as $k=>$item){
            $arr[$k] = json_encode($item);
        }
        return $arr;

    }


    public function get_warning_stu_list(){
        $sql = $this->gen_sql_new("select s.userid, count(*) lesson_num, s.lesson_count_left, "
                                  ." sum(b.lesson_count) as lesson_total,m.uid,u.groupid,n.group_name "
                                  ." from %s s left join %s b on s.userid = b.userid "
                                  ." left join %s a  on a.assistantid=s.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s u on m.uid = u.adminid"
                                  ." left join %s n on u.groupid = n.groupid"
                                  ."  where s.type=0 and  s.is_test_user=0 and s.lesson_count_left>0 group by s.userid having ((sum(b.lesson_count) - sum(s.lesson_count_left)/count(*)/4) >=0)"
                                  ,self::DB_TABLE_NAME
                                  ,t_week_regular_course::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_warning_stu_list_summer(){
        $sql = $this->gen_sql_new("select s.userid, count(*) lesson_num, s.lesson_count_left, "
                                  ." sum(b.lesson_count) as lesson_total,m.uid,u.groupid,n.group_name "
                                  ." from %s s left join %s b on s.userid = b.userid "
                                  ." left join %s a  on a.assistantid=s.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s u on m.uid = u.adminid"
                                  ." left join %s n on u.groupid = n.groupid"
                                  ."  where s.type=0 and  s.is_test_user=0 and s.lesson_count_left>0 group by s.userid having ((sum(b.lesson_count) - sum(s.lesson_count_left)/count(*)/4) >=0)"
                                  ,self::DB_TABLE_NAME
                                  ,t_summer_week_regular_course::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }


    public function get_warning_stu_list_new(){
        $sql = $this->gen_sql_new("select s.userid, count(*) lesson_num, s.lesson_count_left, "
                                  ." sum(b.lesson_count) as lesson_total,m.uid,u.groupid,n.group_name "
                                  ." from %s s left join %s b on s.userid = b.userid "
                                  ." left join %s a  on a.assistantid=s.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s u on m.uid = u.adminid"
                                  ." left join %s n on u.groupid = n.groupid"
                                  ."  where s.type=0 and  s.is_test_user=0 and s.lesson_count_left>0 group by s.userid having ((sum(b.lesson_count) - sum(s.lesson_count_left)/count(*)/1.2) >=0)"
                                  ,self::DB_TABLE_NAME
                                  ,t_week_regular_course::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
    public function get_warning_stu_list_month_new(){
        $sql = $this->gen_sql_new("select s.userid, count(*) lesson_num, s.lesson_count_left, "
                                  ." sum(b.lesson_count) as lesson_total,m.uid,u.groupid,n.group_name "
                                  ." from %s s left join %s b on s.userid = b.userid "
                                  ." left join %s a  on a.assistantid=s.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." left join %s u on m.uid = u.adminid"
                                  ." left join %s n on u.groupid = n.groupid"
                                  ."  where s.type=0 and  s.is_test_user=0 and s.lesson_count_left>0 group by s.userid having ((sum(b.lesson_count)*4 - sum(s.lesson_count_left)/count(*)/1.2) >=0)"
                                  ,self::DB_TABLE_NAME
                                  ,t_week_regular_course::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function set_ass_revisit_last_week_time($userid , $ass_revisit_last_week_time) {
        $sql = $this->gen_sql_new(
            "update %s  set  ass_revisit_last_week_time =%d"
            . " where userid=%d and ass_revisit_last_week_time<%u  ",
            self::DB_TABLE_NAME,
            $ass_revisit_last_week_time,
            $userid,
            $ass_revisit_last_week_time
        );
        return $this->main_update($sql);
    }

    public function get_end_lesson_student_info_by_time($time){
        $where_arr=[
            "type_change_time>=".$time,
            "type=1",
            "is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select userid,type_change_time from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);

    }

    public function get_trans_stu_info_new($start_time,$end_time){
        $where_arr=[
            "s.origin_assistantid>0",
            "b.operator_note like '%%转介绍%%'",
            "m.account_role=1",
            // "m.del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"b.revisit_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct b.phone) num,m.uid"
                                  ." from %s s  join %s b on s.phone=b.phone"
                                  ."  join %s a on s.assistantid = a.assistantid"
                                  ."  join %s m on m.phone= a.phone"
                                  ." where %s group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_book_revisit::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_un_revisit_stu_info($start_time,$end_time){
        $where_arr=[
            "m.account_role=1",
            // "m.del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"master_assign_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct s.userid) un_revisit_num,m.uid"
                                  ." from %s s left join %s r on (s.userid= r.userid and revisit_type in (1,4))"
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on m.phone= a.phone"
                                  ." where %s and ((ass_assign_time+24*86400)<r.revisit_time or r.revisit_time is null) group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_revisit_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });

    }

    public function get_ass_un_revisit_info_new($master_adminid,$start_time,$end_time){
        $where_arr=[
            "m.account_role=1",
            "m.del_flag=0",
            "g.master_adminid=".$master_adminid
        ];
        $this->where_arr_add_time_range($where_arr,"master_assign_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select m.uid,s.ass_assign_time,r.revisit_time,m.account,s.nick"
                                  ." from %s s left join %s r on (s.userid= r.userid and revisit_type in (1,4))"
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on m.phone= a.phone"
                                  ." left join %s u on u.adminid=m.uid"
                                  ." left join %s g on g.groupid = u.groupid"
                                  ." where %s and ((ass_assign_time+24*86400)<r.revisit_time or r.revisit_time is null) ",
                                  self::DB_TABLE_NAME,
                                  t_revisit_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_userid_by_realname($name){
        $where_arr=[
            ["nick='%s'",$name,""],
        ];
        $sql = $this->gen_sql_new("select userid "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_test_lesson_suject_info($userid){
        $where_arr=[
            ["s.userid = %u",$userid,-1]
        ];
        $sql = $this->gen_sql_new("select distinct s.grade,n.phone_location,n.stu_score_info ,n.stu_character_info,t.subject,c.teacherid"
                                  ." from %s s left join %s n on s.userid=n.userid"
                                  ." left join %s t on s.userid = t.userid"
                                  ." left join %s c on (s.userid = c.userid and t.subject = c.subject  and course_type=0 and course_status =0)"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }

    public function get_graduating_student_info($page_num, $start_time, $group_id_lists_str, $assistantid_flag) {
        $where_arr =  ['s.grade in (203,303)',
                       'l.lesson_type = 0',
                       's.type = 0',
                       'l.confirm_flag in (0,1)'
        ];

        if($assistantid_flag == 1){
            $where_arr[] = 'm.uid >0';
        } else {
            $where_arr[] = ['m.uid in (%s)',  $group_id_lists_str];
        }

        $sql = $this->gen_sql_new(" select s.userid, s.lesson_count_left, s.nick, s.grade, s.assistantid"
                                  ." from %s s left join %s l on l.userid = s.userid "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." left join %s m on a.phone = m.phone "
                                  ." where %s"
                                  ." group by s.userid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        $ret_info = $this->main_get_list_by_page($sql,$page_num,10);
        return $ret_info;
    }

    public function get_refund_stu_by_assid($assistantid){
        $where_arr = [
            ["assistantid=%u",$assistantid,0]
        ];
        $sql = $this->gen_sql_new("select sum(userid), assistantid "
                                  ." from %s s "
                                  ." where %s"
                                  ." group by assistantid"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_ass_new_stu_first_revisit_info($start_time,$end_time){
        $where_arr=[
            "s.type=0",
            "s.assistantid > 0",
            "(s.is_test_user = 0 or s.is_test_user is null)",
        ];
        $this->where_arr_add_time_range($where_arr,"s.master_assign_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(" select m.uid,s.userid,r.revisit_time "
                                  ." from %s s  "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." left join %s m on a.phone = m.phone "
                                  ." left join %s r on ( r.userid= s.userid and r.revisit_type in (1,4,5) and r.sys_operator <> 'system' and r.sys_operator <> '系统' and r.revisit_time >=s.ass_assign_time and (r.revisit_time - s.ass_assign_time)<=86400 )"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_revisit_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_ass_first_revisit_info(){
        $where_arr=[
            //"s.type=0",
            "s.type in (0,2,3,4)",
            "s.assistantid > 0",
            "(s.is_test_user = 0 or s.is_test_user is null)",
        ];
        $sql = $this->gen_sql_new(" select m.uid,count(s.userid) num"
                                  ." from %s s  "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." left join %s m on a.phone = m.phone "
                                  ." where %s group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_ass_first_revisit_info_finish($start_time,$end_time){
        $where_arr=[
            "s.type=1",
            "s.assistantid > 0",
            "(s.is_test_user = 0 or s.is_test_user is null)",
        ];
        $this->where_arr_add_time_range($where_arr,"s.last_lesson_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(" select m.uid,count(s.userid) num "
                                  ." from %s s  "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." left join %s m on a.phone = m.phone "
                                  ." where %s group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_ass_first_revisit_info_online($start_time,$end_time){
        $where_arr=[
            "s.type=1",
            "s.assistantid > 0",
            "(s.is_test_user = 0 or s.is_test_user is null)",
        ];
        $sql = $this->gen_sql_new(" select m.uid,COUNT(DISTINCT l.userid) AS num "
        //$sql = $this->gen_sql_new(" select m.uid,s.userid "
                                  ." from %s s  "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." left join %s l on a.assistantid = l.assistantid "
                                  ." left join %s m on a.phone = m.phone "
                                  ." where %s and l.lesson_start >=%u and l.lesson_start<%u  and l.lesson_status =2 and l.confirm_flag not in (2,3)  and l.lesson_type in (0,1,3) and l.lesson_del_flag=0 group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $start_time,$end_time
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_new_assign_stu_info($start_time,$end_time){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"s.master_assign_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(" select m.uid, count(distinct s.userid) num,sum(lesson_total*default_lesson_count) lesson_count"
                                  ." from %s s  "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." left join %s m on a.phone = m.phone "
                                  ." left join %s o on s.userid = o.userid and contract_type=0"
                                  ." where %s"
                                  ." group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });


    }
    public function get_refund_info($start_time,$end_time,$type=-1){
        $where_arr=[];

        if ($type != -1) {
            $where_arr[]=["s.type=%d",$type];
        }

        $this->where_arr_add_time_range($where_arr,"s.ass_assign_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select m.uid, count(distinct s.userid) num "
                                  ." from %s s  "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." left join %s m on a.phone = m.phone "
                                  ." where %s"
                                  ." group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_refund_info_new($start_time,$end_time,$type=-1){
        $where_arr=[
            "s.assistantid>0"
        ];

        if ($type != -1) {
            $where_arr[]=["s.type=%d",$type];
        }

        $this->where_arr_add_time_range($where_arr,"s.ass_assign_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select distinct s.userid  "
                                  ." from %s s  "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr
        );

        $list = $this->main_get_list($sql);
        $ret=[];
        foreach($list as $v){
            $ret[] = $v["userid"];
        }
        return $ret;
    }


    public function get_end_stu_list_str($start_time,$end_time){
        $where_arr=[
            "s.type=1",
            's.is_test_user=0',
        ];

        $this->where_arr_add_time_range($where_arr,"s.last_lesson_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select s.userid,s.assistantid,a.nick "
                                  ." from %s  s "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["userid"];
        });

    }
    public function get_end_class_stu_info($start_time,$end_time,$assistantid=-1){
        $where_arr=[
            "s.type=1",
            ["s.assistantid=%u",$assistantid,-1]
        ];

        $this->where_arr_add_time_range($where_arr,"s.last_lesson_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select m.uid, count(distinct s.userid) num "
                                  ." from %s s  "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." left join %s m on a.phone = m.phone "
                                  ." where %s"
                                  ." group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_end_lesson_stu_list($start_time,$end_time){
        $where_arr=[
            "s.type=1"
        ];

        $this->where_arr_add_time_range($where_arr,"s.last_lesson_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select s.nick,s.userid,s.grade,a.nick name,s.phone,s.stu_lesson_stop_reason,lesson_count_left "
                                  ." from %s s  "
                                  ." left join %s a on s.assistantid = a.assistantid "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }
    //@param:$group_field  转介绍负责人id
    public function get_referral_info( $group_field, $start_time, $end_time){
        $where_arr = [
            "$group_field>0",
        ];
        $this->where_arr_add_time_range($where_arr,"n.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select count(distinct s.userid ) total_num, ".
                                  " sum(o.price) price_num, ".
                                  " $group_field ,".
                                  " sum(o.orderid is not null) orderid_num, ".
                                  " count(distinct o.userid) userid_num".
                                  " from %s s ".
                                  " left join %s o on (o.userid = s.userid and o.contract_status>0 and  o.contract_type =0 )  ".
                                  " left join %s n on s.userid = n.userid".
                                  " where %s ".
                                  " group by  $group_field ",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["origin_assistantid"];
        });
    }

    //@desn:获取统计数据
    //@param:$group_field  转介绍负责人id
    //@param:$start_time,$end_time 开始时间 结束时间
    public function get_referral_order_info($start_time,$end_time){
        $where_arr = [
            'si.origin_userid>0',
            'si.is_test_user = 0',
            'ssn.admin_revisiterid <> 0'
        ];
        $this->where_arr_add_time_range($where_arr,"si.reg_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select sum(oi.price) price_num,count(distinct oi.userid) userid_num,".
            "ssn.admin_revisiterid,sum(oi.orderid <> '') orderid_num ".
            "from %s si ".
            "left join %s oi on (oi.userid = si.userid and oi.contract_status>0 and oi.contract_type = 0) ".
            "left join %s ssn on ssn.userid = si.userid ".
            "where %s ".
            "group by ssn.admin_revisiterid",
            self::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["admin_revisiterid"];
        });
    }
    //@desn:获取转介绍试听课信息
    //@param:$start_time,$end_time 开始时间,结束时间
    public function get_referral_test_lesson($start_time,$end_time){
        $where_arr = [
            'si.origin_userid>0',
            'si.is_test_user = 0',
            'ssn.admin_revisiterid <> 0'
        ];
        $this->where_arr_add_time_range($where_arr,"si.reg_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            'select count(*) referral_num,sum(tlssl.success_flag in (0,1 )) test_lesson_succ,'.
            'ssn.admin_revisiterid,count(tlsr.require_id <> 0) test_lesson_require,ssn.admin_revisiterid adminid '.
            'from %s si '.
            'join %s ssn on si.userid = ssn.userid '.
            'join %s tls on tls.userid = si.userid '.
            'left join %s tlsr using(test_lesson_subject_id) '.
            'left join %s tlssl on tlsr.require_id = tlssl.require_id '.
            'left join %s li on tlsr.current_lessonid = li.lessonid and li.lesson_type = 2 '.
            'where %s '.
            "group by ssn.admin_revisiterid",
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['admin_revisiterid'];
        });
    }
    //@desn:获取转介绍信息
    //@param:$start_time,$end_time 开始时间,结束时间
    public function get_referral_type_info($start_time,$end_time){
        $where_arr = [
            'si.origin_userid>0',
            'si.is_test_user = 0',
            'ssn.admin_revisiterid <> 0'
        ];
        $this->where_arr_add_time_range($where_arr,"si.reg_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            'select  ssn.admin_revisiterid,si.origin_assistantid,tlsr.current_lessonid,'.
            'tlsr.accept_flag,tlssl.success_flag,oi.orderid,oi.userid,oi.price,omi.account_role '.
            'from %s si '.
            'join %s ssn on si.userid = ssn.userid '.
            'join %s tls on tls.userid = si.userid '.
            'left join %s tlsr using(test_lesson_subject_id) '.
            'left join %s tlssl on tlsr.require_id = tlssl.require_id '.
            'left join %s li on tlsr.current_lessonid = li.lessonid and li.lesson_type = 2 '.
            'left join %s oi on si.userid = oi.userid and oi.contract_status>0 and oi.contract_type = 0 '.
            'join %s omi on omi.uid = si.origin_assistantid '.
            'where %s ',
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_all_stu_num(){
        $sql = $this->gen_sql_new("select count(*) from %s ",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_all_add_stu_num($start_time,$end_time){
        $sql = $this->gen_sql_new("select count(*) from %s "
                                  ." where reg_time >=%u and reg_time <= %u ",
                                  self::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time
        );
        return $this->main_get_value($sql);

    }

    public function get_stu_info_by_type($type){
        $sql = $this->gen_sql_new("select userid,type,last_lesson_time "
                                  ." from %s "
                                  ." where type = %u "
                                  ." and assistantid>0 "
                                  ." order by userid "
                                  ." limit 0,50"
                                  ,self::DB_TABLE_NAME
                                  ,$type
        );
        return $this->main_get_list($sql);
    }

    public function get_end_lesson_stu_info($start_time,$end_time){
        $where_arr = [
            ["s.last_lesson_time>%u",$start_time,0],
            ["s.last_lesson_time<%u",$end_time,0],
            "s.type=1",
            "s.is_test_user=0",
        ];
        $sql = $this->gen_sql_new("select count(*) num,m.uid "
                                  ." from %s s left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });

    }

    public function get_parent_wx_openid($userid){

        $where_arr = [
            ['s.userid = %d',$userid]
        ];

        $sql = $this->gen_sql_new("select p.wx_openid from %s s".
                                  " left join %s p on s.parentid = p.parentid ".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  $where_arr

        );

        return $this->main_get_value($sql);
    }

    public function get_stu_grade_by_sid($sid){
        $sql = $this->gen_sql_new(" select grade from %s ts where userid = $sid",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_email_two_info($start_time){
        $where_arr=[
            "stu_email <> ''"
        ];
        $sql = $this->gen_sql_new('select stu_email,count(*) num'
                                  .' from %s'
                                  .' where reg_time>%u  and %s group by stu_email having(num>=2)',
                                  self::DB_TABLE_NAME,
                                  $start_time,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_info_by_email($stu_email){
        $sql = $this->gen_sql_new("select stu_email,userid,nick,phone"
                                  ." from %s"
                                  ." where stu_email='%s' ",
                                  self::DB_TABLE_NAME,
                                  $stu_email
        );
        return $this->main_get_list($sql,function($item){
            return $item["userid"];
        });

    }

    public function get_origin_user($start_time,$end_time){
        $where_arr = [
            ["pay_time>%u",$start_time,0],
            ["pay_time<%u",$end_time,0],
            "s.origin_userid>0",
        ];
        $sql = $this->gen_sql_new("select s.nick,s.realname,s.phone,s.email,s.phone_location,s.grade,s.user_agent,sys_operator,"
                                  ." sum(o.lesson_total*o.default_lesson_count/100) as lesson_total_all,o.contract_type,"
                                  ." sum(o.lesson_left/100) as lesson_left_all,"
                                  ." sum(if(o.order_status=3,1,0)) as refund_num,"
                                  ." s1.nick as origin_nick,s1.realname as origin_realname,s1.phone as origin_phone,"
                                  ." s1.user_agent as origin_user_agent,s1.seller_adminid as origin_seller,"
                                  ." s1.grade as origin_grade,s1.phone_location as origin_phone_location "
                                  ." from %s s"
                                  ." left join %s o on s.userid=o.userid"
                                  ." left join %s s1 on s.origin_userid=s1.userid"
                                  ." where %s"
                                  ." group by s.userid,o.orderid"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_origin_user_list(){
        $where_arr = [
            "origin_userid>0"
        ];
        $sql = $this->gen_sql_new("select origin_userid"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["origin_userid"];
        });
    }

    public function get_stu_list($type){
        $where_arr = [
            ["s.type=%u",$type,-1],
            "s.is_test_user=0",
            "s.lesson_count_left>100",
        ];
        $sql = $this->gen_sql_new("select s.userid,s.phone"
                                  ." from %s s"
                                  ." where %s"
                                  ." and exists ("
                                  ." select 1 from %s where s.userid=userid and contract_type=0 and contract_status>0"
                                  ." )"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_order_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_row_by_phone($phone){
        $where_arr = [
            ["type = %u",0],
            ["phone = '%s'",$phone,-1],
            "is_test_user = 0",
        ];
        $sql = $this->gen_sql_new("select userid,type,nick,phone "
                                  ."from %s "
                                  ."where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_assistantid_by_userid($userid){
        $sql = $this->gen_sql_new(" select uid from %s ts  ".
                                  " left join %s ta on ts.assistantid=ta.assistantid ".
                                  " left join %s m on m.phone = ta.phone where ts.userid=%s",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $userid
        );

        return $this->main_get_value($sql);
    }

    public function get_assistant_stu_order_info(){
        $sql =$this->gen_sql_new("select s.userid,s.nick,s.ass_assign_time,s.type,s.lesson_count_left,o.order_time,oo.orderid,s.grade "
                                 ." from %s s left join %s o on (s.userid = o.userid and o.contract_type =0 and o.contract_status>0 and o.order_time= (select min(order_time) from %s where userid = o.userid and contract_type =0 and contract_status>0))"
                                 ." left join %s oo on (s.userid = oo.userid and oo.contract_type =3 and oo.contract_status>0 and oo.order_time= (select max(order_time) from %s where userid = oo.userid and contract_type =3 and contract_status>0))"
                                 ." where s.assistantid>0 and s.is_test_user=0 order by s.userid",
                                 self::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_student_info_by_phone($phone){
        $where_arr = [
            ["phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select userid"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_stu_wx_remind_info($time){
        $where_arr=[
            "s.type>1",
            ["ct.wx_remind_time = %u",$time,0]
        ];
        $sql = $this->gen_sql_new("select s.nick,ct.wx_remind_time,m.uid,a.nick name,ct.recover_time "
                                  ." from %s s "
                                  ."join %s ct on s.userid = ct.userid and ct.add_time=(select max(add_time) from %s where userid=ct.userid)"
                                  ." join %s a on s.assistantid = a.assistantid"
                                  ." join %s m on a.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_type_change_list::DB_TABLE_NAME,
                                  t_student_type_change_list::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_stu_nick_by_lessonid($lessonid){
        $sql = $this->gen_sql_new(" select nick from %s s ".
                                  " left join %s l on l.userid = s.userid".
                                  " where l.lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $lessonid
        );


        return $this->main_get_value($sql);
    }

    public function get_stu_renw_info($start_time,$end_time,$userid_list){
        $where_arr=[];
        $where_arr[] = $this->where_get_in_str("s.userid",$userid_list,true);
        $sql =$this->gen_sql_new("select s.nick,if(o.orderid>0,o.price,0) status,a.nick ass_name,s.grade,s.lesson_count_left,s.userid ".
                                 " from  %s s ".
                                 " left join %s o on o.userid  = s.userid and o.contract_type in (3,3001) and o.contract_status in (1,2,3) and o.order_time >= %u and o.order_time <= %u".
                                 " left join %s a on s.assistantid = a.assistantid".
                                 " where %s ",
                                 self::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 $start_time,
                                 $end_time,
                                 t_assistant_info::DB_TABLE_NAME,
                                 $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_all_lesson_left($userid_list){
        $where_arr=[];
        $where_arr[] = $this->where_get_in_str("userid",$userid_list,true);
        $sql =$this->gen_sql_new("select sum(lesson_count_left) from %s where %s",self::DB_TABLE_NAME,$where_arr);

        return $this->main_get_value($sql);

    }

    public function get_no_type_student_score($page_info,$assistantid,$page_num,$start_time,$end_time){
        $where_arr=[
          ['o.assistantid=%d', $assistantid, -1],
          'o.lesson_count_left>0',
          's.status=2',
          ["create_time>=%u", $start_time, -1 ],
          ["create_time<=%u", $end_time, -1 ],
        ];
        $sql = $this->gen_sql_new("select s.id, s.stu_score_type ,s.grade,s.create_time,s.userid, s.subject, s.status,s.month"
                                  ." from %s s"
                                  ." left join %s o on o.userid = s.userid "
                                  ." where %s "
                                  ,t_student_score_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_all_stu_phone_location(){
        $sql = $this->gen_sql_new("select s.userid,s.phone,s.nick,s.phone_location "
                                  ."from %s s where s.type=0 and s.is_test_user=0",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list_as_page($sql);
    }
    /**
     *function 统计平台全部学员(type=0,is_test_user=0)
     *
     */
    public function get_total_student_num($type){
        $where_arr = [
            " type=0 ",
            " is_test_user=0 ",
            "assistantid>0"
        ];
        $sql = $this->gen_sql_new(" select count(userid) as platform_teacher_student "
                                  ." from %s"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr

        );
        return $this->main_get_list($sql);
    }


    public function get_stu_origin_rate($start_time, $end_time){
        $where_arr = [
            ['reg_time >=%s', $start_time, 0],
            ['reg_time <%s', $end_time, 0],
            "origin_userid>0",
            "is_test_user=0",

        ];
        $sql = $this->gen_sql_new("select origin_userid,count(origin_userid) as count ,count(o.userid) as succ"
                                  ." from %s s"
                                  ." left join %s o on o.userid=s.userid"
                                  ." and contract_type =0 and contract_status>0"
                                  ." where %s"
                                  ." group by origin_userid"
                                  . "order by count"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_money_rate($start_time, $end_time){
        $where_arr = [
            ['reg_time >=%s', $start_time, 0],
            ['reg_time <%s', $end_time, 0],
            "origin_userid>0",
            "is_test_user=0",
            // "ts.require_admin_type=1"
            "ts.require_admin_type=2"

        ];
        $sql = $this->gen_sql_new("select count(s.userid) as count,count(o.userid) as succ,"
                                  ." sum(if(ts.require_admin_type=1,1,0)) as cr,"
                                  ." sum(if(ts.require_admin_type=2,1,0)) as cc,"
                                  ." sum(o.price) money"
                                  ." from %s s"
                                  ." left join %s o on o.userid=s.userid "
                                  ." and contract_type =0 and contract_status>0"
                                  // ." left join %s tsl on tsl.orderid=o.orderid"
                                  // ." left join %s tr on tr.require_id=tsl.require_id"
                                  // ." left join %s ts on ts.test_lesson_subject_id=tr.test_lesson_subject_id"
                                  ." left join %s ts on s.userid=ts.userid"
                                  ." where %s"
                                  // ." group by so"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  // ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  // ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_studentid(){
         $where_arr=[
            "s.is_test_user=0 ",
            "s.assistantid>0",
            "s.type=0",
            "c.course_type in (0,1,3)"  ,
            "c.course_status=0 "

        ];
        $sql = $this->gen_sql_new("select s.userid,count(distinct c.subject) num "
                                  ." from %s s left join %s c on s.userid = c.userid "
                                  ."  where  %s group by s.userid "
                                  ,self::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,$where_arr
        );
        //dd($sql);
        return $this->main_get_list($sql);
    }
    public function get_studentid_grade(){
        $where_arr=[
            "s.is_test_user=0 ",
            "s.assistantid>0",
            "s.type=0",
            "c.course_type in (0,1,3)"  ,
            "c.course_status=0 "

        ];
        $sql = $this->gen_sql_new("select s.userid,s.grade,count(distinct c.subject) num "
                                  ." from %s s left join %s c on s.userid = c.userid "
                                  ."  where  %s group by s.userid "
                                  ,self::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,$where_arr
        );
        // dd($sql);
        return $this->main_get_list($sql);
    }


    public function get_stu_order_num_info(){
        $where_arr=[
            "s.is_test_user=0",
            "s.type=0",
            "o.contract_type in (0,3)"
        ];
        $sql = $this->gen_sql_new("select s.userid,s.phone,s.grade,s.assistantid,a.nick ass_nick,s.nick,count(o.orderid) num"
                                  ." from %s s left join %s o on s.userid = o.userid"
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." where %s group by s.userid order by num desc",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_stu_id_phone($start_time, $end_time){
        $where_arr = [
            ['reg_time>=%s', $start_time,0],
            ['reg_time<%s', $end_time,0],
            'is_test_user=0',
            'last_lesson_time<1',
            'lesson_count_left<1',
        ];
        $sql = $this->gen_sql_new("select distinct s.reg_time,s.userid,s.phone,if (ts.require_adminid>0,1,0) as test_flag"
                                  ." from %s s "
                                  ." left join %s ts on s.userid=ts.userid"
                                  ." where %s "
                                  ." order by reg_time desc"
                                  ." limit 10000"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_all_student($reg_time){
        $where_arr = [
            ["reg_time<%u",$reg_time,0]
        ];
        $sql = $this->gen_sql_new("select userid,grade"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_grade_info_month($time){

        $sql = $this->gen_sql_new("select count(*) num,grade from %s where reg_time <%u and is_test_user=0 and grade >0 and grade <402 and assistantid>0 group by grade",
                                  self::DB_TABLE_NAME,
                                  $time
        );
        return $this->main_get_list($sql,function($item){
            return $item["grade"];
        });
    }

    public function get_userid_by_name($realname,$nick){
        $sql = $this->gen_sql_new("select s.userid from %s s "
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." where s.nick = '%s' order by s.userid desc",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $realname
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_by_textbook($editionid){
        $sql = $this->gen_sql_new("select userid,editionid from %s where editionid=%u",self::DB_TABLE_NAME,$editionid);
        return $this->main_get_list($sql);
    }
    public function get_finish_num_new($start_time,$end_time){
        $where_arr = [
            [' last_lesson_time>=%u',$start_time,-1],
            [' last_lesson_time<=%u',$end_time,-1],
            ' lesson_count_left = 0',
            ' type = 1 ',
        ];
        $sql = $this->gen_sql_new("select count(userid) as finish_num "
                                  ." from %s s"
                                  ." where %s and NOT EXISTS ( SELECT 1 FROM %s WHERE s.userid = userid)"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_order_refund::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_finish_num_new_list($start_time,$end_time){
        $sql = "select count(distinct(s.userid )) from db_weiyi.t_student_info s  left join db_weiyi.t_order_refund r on s.userid = r.userid and r.contract_type in (0,3)  and not exists (select 1 from db_weiyi.t_order_refund where userid=r.userid and r.contract_type in (0,3) and apply_time>r.apply_time) left join db_weiyi.t_order_info o on s.userid= o.userid and o.contract_type in (0,3) where s.last_lesson_time >=$start_time and s.last_lesson_time <=$end_time and s.is_test_user=0 and (r.apply_time is null or r.apply_time<s.last_lesson_time) and s.type=1 ";
        return $this->main_get_value($sql);
    }
    public function get_read_num($start_time,$end_time){
        $where_arr = [
            " type = 0 ",
            " assistantid > 0",
            " is_test_user = 0 "
        ];
        $sql = $this->gen_sql_new("select count(distinct(userid)) as read_num"
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_read_num_by_grade(){
        $where_arr = [
            " type = 0 ",
            " assistantid > 0",
            " is_test_user = 0 "
        ];
        $sql = $this->gen_sql_new("select count(distinct userid) num,grade "
                                  ." from %s "
                                  ." where %s group by grade "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_list($sql,function($item){
            return $item["grade"];
        });
    }


    public function get_tran_stu_to_seller_info($add_time,$page_info,$assistantid,$leader_flag,$account_id,$campus_id,$groupid){
        $where_arr=[
            ["n.add_time>=%u",$add_time,0],
            ["s.assistantid=%u",$assistantid,-1],
            ["c.campus_id=%u",$campus_id,-1],
            ["na.groupid=%u",$groupid,-1],
            "s.is_test_user=0",
            "s.origin_assistantid>0",
            "mm.account_role=1",
            "(n.admin_revisiterid=0 or m.account_role=2)"
        ];
        if($leader_flag==1){
            $where_arr[]=["na.master_adminid =%u",$account_id,-1];
        }
        $sql = $this->gen_sql_new("select if(s.nick='',s.realname,s.nick) nick,mm.name ass_nick,n.add_time,m.account,"
                                  ."n.admin_assign_time,sum(o.price) order_price,n.sub_assign_adminid_1, "
                                  ." n.sub_assign_adminid_2,s.ass_assign_time,c.campus_name,"
                                  ."cc.campus_name seller_campus_name,aa.nick ass_name,"
                                  ." na.group_name,s.userid "
                                  ." from %s s left join %s n on s.userid = n.userid"
                                  ." left join %s mm on s.origin_assistantid=mm.uid"
                                  ." left join %s aa on s.assistantid = aa.assistantid"
                                  ." left join %s m on n.admin_revisiterid = m.uid"
                                  ." left join %s o on s.userid = o.userid and"
                                  ." o.contract_type in (0,3) and o.contract_status>0"
                                  ." left join %s u on s.origin_assistantid = u.adminid"
                                  ." left join %s na on u.groupid = na.groupid"
                                  ." left join %s an  on na.up_groupid = an.groupid"
                                  ." left join %s c on an.campus_id = c.campus_id "
                                  ." left join %s uu on   n.admin_revisiterid= uu.adminid"
                                  ." left join %s nna on uu.groupid = nna.groupid"
                                  ." left join %s ann on nna.up_groupid = ann.groupid"
                                  ." left join %s cc on ann.campus_id = cc.campus_id "
                                  ." where %s group by s.userid order by n.add_time desc",
                                  self::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  t_admin_campus_list::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  t_admin_campus_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }


   public function get_stu_info_by_parentid($parentid){
       $sql = $this->gen_sql_new("  select s.nick, s.grade,s.userid from %s s"
                                 ." left join %s p on p.userid=s.userid"
                                 ." where p.parentid=$parentid"
                                 ,self::DB_TABLE_NAME
                                 ,t_parent_child::DB_TABLE_NAME
       );

        return $this->main_get_list($sql);

    }

    public function check_is_test_user($userid){
        $sql = $this->gen_sql_new("select is_test_user from %s where userid=$userid"
                                  ,self::DB_TABLE_NAME

        );
        return $this->main_get_value($sql);
    }

    public function get_ass_openid($userid){
        $sql = $this->gen_sql_new("  select m.wx_openid from %s s "
                                  ." left join %s a on a.assistantid=s.assistantid"
                                  ." left join %s m on m.phone=a.phone"
                                  ." where s.userid=%d "
                                  ,self::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$userid
        );

        return $this->main_get_value($sql);
    }


    public function check_is_reading($parentid){
        $sql = $this->gen_sql_new("  select 1 from %s s"
                                  ." where s.type=0 and s.parentid = %d"
                                  ,self::DB_TABLE_NAME
                                  ,$parentid
        );
        return $this->main_get_value($sql);
    }
    public function get_list_test( $page_info, $userid=-1 ) {
        $where_arr=[
            ["userid=%u", $userid, -1 ]
        ];
        $sql= $this->gen_sql_new("select * from %s where %s   ",
                                 self::DB_TABLE_NAME, $where_arr  );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_assistant_read_stu_info($assistantid){
        $where_arr=[
            ["s.assistantid=%u",$assistantid,-1],
            "s.type=0"
        ];
        $sql = $this->gen_sql_new("select s.userid,s.nick,s.assistantid,a.nick ass_nick,s.ass_assign_time"
                                  .",m.account "
                                  ." from %s s left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_ass_create_stu_info(){
        $where_arr = [
            "n.ass_leader_create_flag=1",
            "s.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select s.userid,s.origin"
                                  ." from %s s left join %s n on s.userid = n.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function check_is_reject($userid){
        $where_arr = [
            "o.userid=$userid",
            "o.contract_type = 0",
            "o.contract_status <2"
        ];
        $sql = $this->gen_sql_new("  select sc.reject_flag from %s sc"
                                  ." left join %s o on o.userid=sc.orderid"
                                  ." where %s order by sc.id desc limit 1"
                                  ,t_student_cc_to_cr::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_all_stu_info($page_info){
        $sql = $this->gen_sql_new("select userid,nick,phone,type from %s where is_test_user=0 and assistantid>0",self::DB_TABLE_NAME);
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_parentid_by_lessonid($lessonid){
        $sql = $this->gen_sql_new("  select s.parentid from %s s "
                                  ." left join %s l on l.userid=s.userid"
                                  ." where l.lessonid=$lessonid"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_all_student_id(){
        $where_arr = [
            "s.is_test_user = 0",
        ];
        $sql = $this->gen_sql_new("select s.userid,s.reg_time,s.grade,s.phone_location,s.phone, k.has_pad "
                                ."from %s s"
                                ." left join %s k on k.userid = s.userid  "
                                ." where %s ",
                                self::DB_TABLE_NAME,
                                t_seller_student_new::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_grade_info($phone) {
        $sql = $this->gen_sql_new("select userid,grade from %s where phone='$phone'", self::DB_TABLE_NAME);
        return $this->main_get_row($sql);
    }
    //@desn:获取转介绍数量
    //@param:$userid 用户id
    //@param:$start_time $end_time 开始时间 结束时间
    public function get_introduce_count($userid,$start_time,$end_time){
        $where_arr = [
            'is_test_user = 0'
        ];
        $this->where_arr_add_time_range($where_arr, 'reg_time', $start_time, $end_time);
        $this->where_arr_add_int_or_idlist($where_arr, 'origin_userid', $userid);
        $sql = $this->gen_sql_new(
            'select count(*) introduce_count from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_test_user() {
        $sql = $this->gen_sql_new("select userid from %s where is_test_user <> 0", self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
    //@desn:获取用户助教信息
    //@param:$userid 用户id
    public function get_assistant_info($userid){
        $where_arr = [];
        $this->where_arr_add_int_or_idlist($where_arr, 'si.userid', $userid);
        $sql = $this->gen_sql_new(
            'select ai.nick,agn.group_name '.
            'from %s ai '.
            'join %s si on ai.assistantid = si.assistantid '.
            'join %s mi on ai.phone = mi.phone '.
            'left join %s mgu on mgu.adminid = mi.uid '.
            'left join %s agn on agn.groupid = mgu.groupid '.
            'where %s',
            t_assistant_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_admin_group_user::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_all_student_phone_and_id($start_time,$end_time){
        $sql = "select userid, phone from db_weiyi.t_student_info 
        where is_test_user = 0 and reg_time > $start_time and reg_time < $end_time order by userid asc";
        return $this->main_get_list($sql);
    }

    public function get_all_teacher_phone_and_id($start_time,$end_time){
        $sql = "select teacherid, phone from db_weiyi.t_teacher_info 
        where is_test_user = 0 and create_time > $start_time and create_time < $end_time order by teacherid asc";
        return $this->main_get_list($sql);
    }

    public function get_list_count_left() {
        $sql = "select assistantid,userid,nick,lesson_count_left,type from t_student_info where is_test_user=0";
        return $this->main_get_list($sql);
    }

    public function get_list_subject($userid) {
        $sql = "select distinct subject from t_lesson_info where userid=$userid and lesson_type in (0,1,3)";
        return $this->main_get_list($sql);
    }

    public function get_teacher_count($userid, $subject=-1) {
        $where_arr = [
            ["userid=%u", $userid, -1],
            ["subject=%u", $subject, -1],
            "lesson_type in (0,1,3)"
        ];
        $sql = $this->gen_sql_new("select teacherid,count(distinct teacherid) count from %s where %s order by lesson_start desc",
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        
        return $this->main_get_row($sql);
    }

    public function get_type_stu() {
        $sql = "select userid,type from t_student_info where is_test_user=0 and refund_warning_level != 3 and type in (2,3,4)";
        return $this->main_get_list($sql);
    }

    public function get_all_stu() {
        $sql = $this->gen_sql_new("select userid,type from %s where is_test_user=0 and type != 1", //and refund_warning_level != 3",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function gen_all_stu_1() {
        $sql = "select userid from t_student_info where type = 1";
        return $this->main_get_list($sql);
    }

    public function get_student_for_stop_study(){
        $where_arr = [
            "s.type in (2,4)",
            "s.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select s.userid,s.nick,o.contract_starttime,o.contract_endtime,o.contract_status,  "
                                  ." a.nick as ass_nick,gn.group_name"
                                  ." from %s s "
                                  ." left join %s o on s.userid=o.userid and o.contract_type in (0,1,3) and o.contract_status>0"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on a.phone=m.phone"
                                  ." left join %s gu on m.uid=gu.adminid"
                                  ." left join %s gn on gu.groupid=gn.groupid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_refund_warning($assistantid=-1) {
        $where_arr = [
            ["assistantid=%u", $assistantid, -1],
            "assistantid>0",
            "is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select sum(if(refund_warning_level=1,1,0)) one, sum(if(refund_warning_level=2,1,0)) two, sum(if(refund_warning_level=3,1,0)) three from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_refund_warn_info($userid) {
        $sql = $this->gen_sql_new("select type,refund_warning_level,refund_warning_reason from %s where userid=$userid", self::DB_TABLE_NAME);
        return $this->main_get_row($sql);
    }

    public function get_no_order_stu_list($start_time,$end_time){
        $where_arr = [
            ["s.reg_time>%u",$start_time,0],
            ["s.reg_time<%u",$end_time,0],
            "s.origin_userid>1000",
            "s.is_test_user=0"
        ];
        $trial_lesson_arr = [
            "s.userid=l.userid",
            "lesson_type=2",
            "lesson_status=2",
            "lesson_del_flag=0",
        ];
        $order_arr = [
            "s.userid=o.userid",
            "contract_type in (0,1,3)",
            "contract_status>0",
        ];
        $manager_arr = [
            "ss.last_contact_cc=m.uid"
        ];
        $sql = $this->gen_sql_new("select s.userid,s.nick,FROM_UNIXTIME(ss.last_revisit_time,'%%Y年%%m月%%d %%h:%%i:%%s') as revisit_time,"
                                  ." rs.userid,rs.nick as rs_nick,"
                                  ." ss.last_contact_cc,m.account,"
                                  ." s.assistantid,a.nick as ass_nick,"
                                  ." (select 1 from %s t where s.userid=t.userid limit 1) as has_require,"
                                  ." (select 1 from %s l where %s limit 1) as has_trial"
                                  ." from %s s force index(reg_time) "
                                  ." left join %s rs on s.origin_userid=rs.userid"
                                  ." left join %s ss on s.userid=ss.userid"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on ss.last_contact_cc=m.uid"
                                  ." where %s"
                                  ." and not exists (select 1 from %s o where %s limit 1)"
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$trial_lesson_arr
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$order_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_textbook_list(){
        $where_arr = [
            "s.type!=1",
            "s.is_test_user=0"
        ];
        $lesson_arr = [
            "lesson_type in (0,1,3)",
            "lesson_del_flag=0",
            "confirm_flag!=2",
            "s.userid=userid",
        ];
        $sql = $this->gen_sql_new("select s.userid,s.nick as stu_nick,s.grade,s.editionid,"
                                  ." a.nick as ass_nick,gn.group_name,group_concat(distinct(tl.textbook)) as stu_textbook"
                                  ." from %s s"
                                  ." left join %s a on s.assistantid=a.assistantid"
                                  ." left join %s m on a.phone=m.phone"
                                  ." left join %s gu on m.uid=gu.adminid"
                                  ." left join %s gn on gu.groupid=gn.groupid"
                                  ." left join %s tl on s.userid=tl.userid "
                                  ." where %s and "
                                  ." exists (select 1 from %s where %s)"
                                  ." group by s.userid"
                                  ,self::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$lesson_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_all_lesson($stuid_list){
        $where_arr = [
            ["l.userid in (%s)",$stuid_list,""],
            "l.lesson_type in (0,1,3)",
            "l.lesson_del_flag=0",
            "l.confirm_flag!=2",
        ];
        $sql = $this->gen_sql_new("select l.userid,l.teacherid,l.subject,t.nick as tea_nick"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s"
                                  ." group by l.userid,l.teacherid,l.subject"
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);

    }

}
