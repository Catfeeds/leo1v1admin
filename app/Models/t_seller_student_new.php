<?php
namespace App\Models;
use \App\Enums as E;

/**

 * @property t_test_lesson_subject  $t_test_lesson_subject


 * @property t_test_subject_free_list  $t_test_subject_free_list
* @property t_seller_student_origin  $t_seller_student_origin


 * @property t_manager_info  $t_manager_info

* @property t_admin_group_user  $t_admin_group_user


 * @property t_student_info  $t_student_info


 * @property t_assistant_info  $t_assistant_info

 * @property t_origin_key  $t_origin_key
 */

class t_seller_student_new extends \App\Models\Zgen\z_t_seller_student_new
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_origin_ass_count( $start_time,$end_time,$require_adminid_list ) {

        $where_arr=[
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $where_arr[]= $this->where_get_in_str("s.origin_assistantid",$require_adminid_list );
        $sql= $this->gen_sql_new(
            "select count(*) as count "
            . " from %s n "
            . " join %s  s on s.userid=n.userid  "
            . " join %s  t on t.userid=n.userid  "
            . " where %s",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    public function get_tongji_add_time($start_time,$end_time,$adminid_list=[],$adminid_all=[] ,$grade_list=[-1] )  {

        $where_arr=[];
        $this->where_arr_adminid_in_list($where_arr,"admin_revisiterid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"admin_revisiterid",$adminid_all);
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $where_arr[]= $this->where_get_in_str_query("s.grade",$grade_list);

        $sql = $this->gen_sql_new(
            "select from_unixtime(add_time, '%%Y-%%m-%%d') as opt_date, count(*) count"
            . " from %s n  "
            ." join %s s on s.userid=n.userid "
            ." where  %s   "
            ." group by   from_unixtime(add_time, '%%Y-%%m-%%d')  ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }


    public function get_tongji_first_revisit_time($start_time,$end_time ,$adminid_list=[],$adminid_all=[], $grade_list =[-1])  {
        $where_arr=[];
        $this->where_arr_adminid_in_list($where_arr,"admin_revisiterid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"admin_revisiterid",$adminid_all);
        $where_arr[]= $this->where_get_in_str_query("s.grade",$grade_list);

        $sql = $this->gen_sql_new(
            "select from_unixtime(first_revisit_time, '%%Y-%%m-%%d') as opt_date, count(*)  first_revisit_time_count ,".
            "  avg( if(   add_time<first_call_time , first_call_time-add_time,null) ) avg_first_time, first_revisit_time,"
            ." sum(add_time+86400>first_revisit_time) after_24_first_revisit_time_count "
            ." from %s n "
            .  "left join %s s on s.userid=n.userid"

            ." where first_revisit_time  >=%u and  first_revisit_time  <%u and %s  ".
            " group by from_unixtime(first_revisit_time, '%%Y-%%m-%%d') "
            ,
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $start_time, $end_time,$where_arr );
        return $this->main_get_list($sql);
    }

    public function book_free_lesson_new($nick,$phone,$grade, $origin, $subject, $has_pad,$user_desc="",$parent_name="" ) {
        $reg_channel = $origin;
        $passwd = md5("123456");
        $region = "";
        $userid = $this->t_student_info->register($phone,$passwd,$reg_channel,$grade,0,$nick,$region);
        //通知
        $admin_revisiterid    = 0;
        $seller_resource_type = 0;
        $data_item            = $this->field_get_list($userid,"admin_revisiterid,seller_resource_type" );
        if ($data_item) {
            $admin_revisiterid    = $data_item["admin_revisiterid"];
            $seller_resource_type = $data_item["seller_resource_type"];
        }

        if ($admin_revisiterid) {
            $subject_desc=E\Esubject::get_desc($subject);
            $this->t_manager_info->send_wx_todo_msg_by_adminid(
                $admin_revisiterid,"system","重复预约","你的用户,电话[$phone]重新提交了预约申请,科目[$subject_desc] ","");

            \App\Helper\Utils::logger(" ADD_FAIL NOTI seller ");
        }

        $add_flag=$this->t_seller_student_origin->check_and_add($userid,$origin,$subject);

        if(!$add_flag) { //用户渠道增加失败
            if (!$this->t_test_lesson_subject->check_subject($userid,$subject) ){
                $this->t_test_lesson_subject->row_insert([
                    "userid"  => $userid,
                    "grade"   => $grade,
                    "subject" => $subject,
                    "require_admin_type" => E\Eaccount_role::V_2,
                ],false,true);
            }

            if($seller_resource_type==1 && $admin_revisiterid==0  )  { //在公海里
                \App\Helper\Utils::logger(" ADD_FAIL SET NEW FROM PUBLISH");
                $this->field_update_list($userid,[
                    "seller_resource_type" => 0,
                    "first_revisit_time"   => 0,
                    "add_time"             => time(NULL),
                ]);
            }else{
                $old_add_time=$this->get_add_time($userid);
                $old_add_time=\App\Helper\Utils::unixtime2date($old_add_time);

                \App\Helper\Utils::logger(" ADD_FAIL OLD_ADD_TIME = $old_add_time" );

            }
            return  $userid;
        }

        $ret_row = $this->field_get_list($userid,"userid");
        if ($ret_row) {
            if($seller_resource_type==1 && $admin_revisiterid==0  )  { //在公海里
                \App\Helper\Utils::logger("SET NEW FROM PUBLISH");

                $this->field_update_list($userid,[
                    "seller_resource_type" => 0,
                    "first_revisit_time" => 0,
                    "add_time" => time(NULL),
                ]);
            }
            return $userid;
        }

        $this->row_insert([
            "userid"    => $userid,
            "phone"     => $phone,
            "add_time"  => time(NULL),
            "has_pad"   => $has_pad,
            "user_desc" => $user_desc,
        ]);
        $this->t_test_lesson_subject->row_insert([
            "userid"             => $userid,
            "grade"              => $grade,
            "subject"            => $subject,
            "require_adminid"    => $admin_revisiterid,
            "require_admin_type" => E\Eaccount_role::V_2,
        ]);

        $origin_info=$this->t_student_info->get_origin($userid);
        if (@$origin_info["origin"]) {
            $origin= $origin_info["origin"];
        }

        $set_stu_arr=[
            "parent_name" => $parent_name,
            "origin"      => $origin,
        ];

        $origin_level = $this->t_origin_key->get_origin_level($origin);
        if (!$origin_level){ //默认B
            $origin_level = E\Eorigin_level::V_3 ;
        }
        $set_stu_arr["origin_level"] =$origin_level;

        $phone_location = \App\Helper\Common::get_phone_location($phone);
        if( !in_array( $origin_level , [90,99] )  &&  in_array( substr($phone_location,0 ,6) , ["上海","浙江" ] ) && $has_pad =  E\Epad_type::V_1  ) {
            $set_stu_arr["origin_level"] =  0;
        }else{
            //$set_stu_arr["origin_level"] =  2;
        }

        $this->t_student_info->field_update_list( $userid, $set_stu_arr );

        $this->field_update_list($userid,[
            "phone_location" => $phone_location,
        ]);
        $this->t_student_info->field_update_list($userid,[
            "phone_location" => $phone_location,
        ]);

        if (@$origin_info["assistantid"] ) {
            $ass_adminid=$this->t_assistant_info->get_adminid_by_assistand( $origin_info["assistantid"]);
            if ($ass_adminid && !$this->t_manager_info->get_del_flag($ass_adminid)) {
                $this->t_manager_info->send_wx_todo_msg_by_adminid($ass_adminid,"系统","有助教试听申请","$phone - $phone_location");
            }else{
                $this->t_manager_info->send_wx_todo_msg("jim","系统","有助教试听申请","$phone - $phone_location");
            }
            $this->set_admin_info(0,[$userid],60,60);

        }

        if ($origin_level==E\Eorigin_level::V_100 ) {
            $this->set_admin_info(0,[$userid],60,60);
        }


        return $userid;
    }

    public function get_tmk_student_list (
        $page_num, $tmk_adminid,  $userid,  $tmk_student_status ,
        $origin, $opt_date_str, $start_time, $end_time, $grade, $subject,
        $has_pad
        ,$phone, $nick ,$admin_revisiterid, $seller_student_status,$publish_flag
    ) {

        if ($userid >0 || $phone || $nick) {
            $where_arr=[
                ["ss.userid=%u",$userid, -1],
                ["ss.phone like '%s%%'",$phone, ""],
                ["s.nick like '%%%s%%'",$nick, ""],
            ];
        }else{
            $where_arr=[
                ["ss.has_pad=%u",$has_pad, -1],
                ["t.subject=%u",$subject, -1],
                ["ss.grade=%u",$grade, -1],
                ["s.origin like '%%%s%%'",$origin, ""],
                ["ss.admin_revisiterid=%u",$admin_revisiterid, -1],
                ["seller_student_status=%u",$seller_student_status, -1],
            ];
            $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);

            $where_arr[]=['tmk_student_status=%d', $tmk_student_status,-1];
            if ($publish_flag==0) {
                $where_arr[]="t.seller_student_status =50";
            }else if($publish_flag ==1 ){
                $where_arr[]="t.seller_student_status <>50";
            }

        }

        $this->where_arr_add__2_setid_field($where_arr, "tmk_adminid", $tmk_adminid);


        $sql=$this->gen_sql_new(
            "select tmk_adminid,   ss.tmk_set_seller_adminid,return_publish_count,tmk_assign_time ,tmk_student_status ,tmk_desc,tmk_next_revisit_time ,s.user_agent, tr.notify_lesson_day1, tr.notify_lesson_day2, tss.confirm_time,tss.confirm_adminid, tss.fail_greater_4_hour_flag , tr.current_lessonid, tss.test_lesson_fail_flag, tss.success_flag,  tss.fail_greater_4_hour_flag,  tss.fail_reason, t.current_require_id, t.test_lesson_subject_id ,add_time,   seller_student_status,  s.userid,s.nick, s.origin, ss.phone_location,ss.phone,ss.userid,ss.sub_assign_adminid_2,ss.admin_revisiterid, ss.admin_assign_time, ss.sub_assign_time_2 , s.origin_assistantid , s.origin_userid  ,  t.subject, s.grade,ss.user_desc, ss.has_pad, ss.last_revisit_time,ss.last_revisit_msg,tq_called_flag,next_revisit_time,l.lesson_start,l.lesson_del_flag, tr.require_time, l.teacherid, t.stu_test_paper, t.tea_download_paper_time ".
            " from  %s t "
            ." left join %s ss on  ss.userid = t.userid "
            ."  left join %s s on ss.userid=s.userid   "
            ." left join %s tr on   t.current_require_id = tr.require_id "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." where  %s  order by  %s desc"
            , t_test_lesson_subject::DB_TABLE_NAME
            , self::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            , t_test_lesson_subject_require::DB_TABLE_NAME
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME
            , t_lesson_info::DB_TABLE_NAME
            ,$where_arr
            ,$opt_date_str
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }


    public function get_seller_list (
        $page_num, $admin_revisiterid, $seller_student_status_in_str , $userid,  $seller_student_status="" ,
        $origin="", $opt_date_str="ss.add_time", $start_time=-1, $end_time=-1, $grade=-1,
        $subject=-1,$phone_location="", $has_pad=-1, $seller_resource_type=-1 ,$origin_assistantid=-1,
        $tq_called_flag=-1,$phone="", $nick="" ,$origin_assistant_role=-1,$success_flag=-1,
        $seller_require_change_flag=-1, $adminid_list="" ,$group_seller_student_status =-1, $tmk_student_status =-1,
        $require_adminid_list=[], $page_count=10,$require_admin_type =-1, $origin_userid=-1,$end_class_flag=-1,$seller_level=-1
    ) {

        if ($userid >0 || $phone || $nick) {
            $where_arr=[
                ["ss.userid=%u",$userid, -1],
                ["ss.phone like '%s%%'", $this->ensql($phone) , ""],
                ["s.nick like '%%%s%%'",$this->ensql($nick), ""],
            ];
        }else{
            $where_arr=[
                ["ss.has_pad=%u",$has_pad, -1],
                ["t.subject=%u",$subject, -1],
                ["ss.grade=%u",$grade, -1],
                ["s.origin like '%%%s%%'",$this->ensql($origin), ""],
                ["ss.phone_location like '%%%s%%'", $this->ensql($phone_location), ""],
                ["ss.seller_resource_type = %d " ,$seller_resource_type, -1],
                ["ss.tq_called_flag= %d " ,$tq_called_flag, -1],
                ["tss.success_flag = %d " ,$success_flag, -1],
                ["tmk_student_status = %d " ,$tmk_student_status, -1],
                ["require_admin_type=%u",$require_admin_type,-1]
            ];
            $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
            if($seller_student_status==-2){
                $where_arr[] = "seller_student_status <> 60";
            }else{
                $where_arr[]=['seller_student_status=%d', $seller_student_status,-1];
            }

            $status_arr=[];
            //E\Eseller_student_status
            switch ( $group_seller_student_status ) {
            case 1 :
                $status_arr=[0,1,2];
                break;
            case 2:
                $status_arr=[100,101,102];
                break;
            case 3 :
                $status_arr=[103,110,120,200];
                break;
            case 4 :
                $status_arr=[210];
                break;
            case 5:
                $status_arr=[220,290];
                break;
            case 6:
                $status_arr=[300,301,302,303,304,305];
                break;
            case 7 :
                $status_arr=[420];

            default:
                break;
            }

            $where_arr[]=$this->where_get_in_str("seller_student_status",$status_arr );
            $this->where_arr_add_int_or_idlist($where_arr,"m.seller_level",$seller_level);


        }


        $where_arr[]=["ss.admin_revisiterid=%u",$admin_revisiterid, -1];
        $where_arr[]=["t.require_adminid=%u",$admin_revisiterid, -1];
        /*if($admin_revisiterid != -1){
            $where_arr[] = "((t.require_adminid=".$admin_revisiterid." and s.type <>1) or s.type=1)";
            }*/
        $where_arr[]=["tr.seller_require_change_flag=%u",$seller_require_change_flag, -1];
        $where_arr[]=[ 'seller_student_status in(%s) ' , $seller_student_status_in_str,""  ];
        \App\Helper\Utils::logger("adminid_list:$adminid_list");
        $where_arr[]= $this->where_get_in_str("origin_assistantid",$require_adminid_list);

        if ($adminid_list) {
            $where_arr[]="t.require_adminid in ($adminid_list) ";
        }

        $this->where_arr_add__2_setid_field($where_arr,"origin_assistantid", $origin_assistantid );
        $this->where_arr_add__2_setid_field($where_arr,"origin_userid", $origin_userid );

        if ($origin_assistant_role != -1 ) {
            $where_arr[]=sprintf ( "origin_assistantid in  (select uid from  %s where account_role = %d ) ",  t_manager_info::DB_TABLE_NAME, $origin_assistant_role );
        }
        if($end_class_flag==1){
            $where_arr[]="s.type=1";
        }elseif($end_class_flag==2){
            $where_arr[]="s.type <>1";
        }

        $sql=$this->gen_sql_new(
            "select   tss.lessonid,tss.call_end_time,tr.curl_stu_request_test_lesson_time except_lesson_time,last_lesson_time, competition_call_adminid, competition_call_time,  pay_time, tr.test_lesson_order_fail_desc, tr.test_lesson_order_fail_flag ,seller_student_sub_status,f.flow_status as  stu_test_paper_flow_status, f.flowid as stu_test_paper_flowid ,o.price/100 as order_price, s.user_agent, tr.notify_lesson_day1, tr.notify_lesson_day2, tss.confirm_time,tss.confirm_adminid, tss.fail_greater_4_hour_flag , tr.current_lessonid, tss.test_lesson_fail_flag, tss.success_flag,  tss.fail_greater_4_hour_flag,  tss.fail_reason, t.current_require_id, t.test_lesson_subject_id ,add_time,   seller_student_status,  s.userid,s.nick, s.origin, ss.phone_location,ss.phone,ss.userid,ss.sub_assign_adminid_2,ss.admin_revisiterid, ss.admin_assign_time, ss.sub_assign_time_2 , s.origin_assistantid , s.origin_userid  ,  t.subject, s.grade,ss.user_desc, ss.has_pad, ss.last_revisit_time,ss.last_revisit_msg,tq_called_flag,next_revisit_time,l.lesson_start,l.lesson_del_flag, tr.require_time, l.teacherid, t.stu_test_paper, t.tea_download_paper_time,tr.seller_require_change_flag ,tr.seller_require_change_time  ,accept_adminid,t.stu_request_test_lesson_time,tt.phone tea_phone,tt.user_agent tea_user_agent,l.stu_performance rate_score,a.phone ass_phone,a.nick ass_name,l.lesson_status,o.contract_status,s.type study_type,s.lesson_count_all ,s.lesson_count_left,o.contract_type,o.price ,o.lesson_total ,o.discount_price ,o.order_status,tr.accept_flag ,s.init_info_pdf_url ,o.orderid  , tss.parent_confirm_time , p.wx_openid as parent_wx_openid,t.stu_request_lesson_time_info,t.stu_request_test_lesson_demand,ss.stu_score_info,ss.stu_character_info,t.textbook,s.editionid,tr.no_accept_reason,s.last_lesson_time,s.type stu_type,tmk_desc, tmk_student_status "
            .",aga.nickname "
            ."from  %s t "
            ." left join %s ss on  ss.userid = t.userid "
            ."  left join %s s on ss.userid=s.userid   "
            ." left join %s tr on   t.current_require_id = tr.require_id "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." left join %s o on  o.from_test_lesson_id = l.lessonid "
            ." left join %s f on f.from_key_int = o.orderid "
            ." left join %s tt on l.teacherid = tt.teacherid"
            ." left join %s a on s.assistantid = a.assistantid"
            ." left join %s p on (p.parentid = s.parentid  and p.parentid !=0  ) "
            //." left join %s m on (n.admin_revisiterid =  m.uid ) "
            ." left join %s ag on ag.phone =  ss.phone "
            ." left join %s aga on aga.id =  ag.parentid "
            ." where  %s  order by  %s desc"
            , t_test_lesson_subject::DB_TABLE_NAME
            , self::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            , t_test_lesson_subject_require::DB_TABLE_NAME
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME
            , t_lesson_info::DB_TABLE_NAME
            , t_order_info::DB_TABLE_NAME
            , t_flow::DB_TABLE_NAME
            , t_teacher_info::DB_TABLE_NAME
            , t_assistant_info::DB_TABLE_NAME
            , t_parent_info::DB_TABLE_NAME
            , t_agent::DB_TABLE_NAME
            , t_agent::DB_TABLE_NAME
            ,$where_arr
            ,$opt_date_str
        );
        return $this->main_get_list_by_page($sql,$page_num,$page_count);
    }

    public function get_seller_count_list (
        $admin_revisiterid, $seller_student_status_in_str , $userid,  $seller_student_status ,
        $origin, $opt_date_str, $start_time, $end_time, $grade,
        $subject,$phone_location, $has_pad, $seller_resource_type ,$origin_assistantid,
        $tq_called_flag,$phone, $nick ,$origin_assistant_role,$success_flag,
        $seller_require_change_flag=-1, $adminid_list="" , $tmk_student_status =-1,
        $require_adminid_list=[],$require_admin_type =-1
    ) {

        if ($userid >0 || $phone || $nick) {
            $where_arr=[
                ["ss.userid=%u",$userid, -1],
                ["ss.phone like '%s%%'", $this->ensql($phone) , ""],
                ["s.nick like '%%%s%%'",$this->ensql($nick), ""],
            ];
        }else{
            $where_arr=[
                ["ss.has_pad=%u",$has_pad, -1],
                ["t.subject=%u",$subject, -1],
                ["ss.grade=%u",$grade, -1],
                ["s.origin like '%%%s%%'",$this->ensql($origin), ""],
                ["ss.phone_location like '%%%s%%'", $this->ensql($phone_location), ""],
                ["ss.seller_resource_type = %d " ,$seller_resource_type, -1],
                ["ss.tq_called_flag= %d " ,$tq_called_flag, -1],
                ["tss.success_flag = %d " ,$success_flag, -1],
                ["tmk_student_status = %d " ,$tmk_student_status, -1],
                ["require_admin_type=%u",$require_admin_type,-1]
            ];
            $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
            $where_arr[]=['seller_student_status=%d', $seller_student_status,-1];
        }


        $where_arr[]=["ss.admin_revisiterid=%u",$admin_revisiterid, -1];
        $where_arr[]=["t.require_adminid=%u",$admin_revisiterid, -1];
        $where_arr[]=["tr.seller_require_change_flag=%u",$seller_require_change_flag, -1];
        $where_arr[]=[ 'seller_student_status in(%s) ' , $seller_student_status_in_str,""  ];
        \App\Helper\Utils::logger("adminid_list:$adminid_list");
        $where_arr[]= $this->where_get_in_str("origin_assistantid",$require_adminid_list);

        if ($adminid_list) {
            $where_arr[]="t.require_adminid in ($adminid_list) ";
        }

        $this->where_arr_add__2_setid_field($where_arr,"origin_assistantid", $origin_assistantid );

        if ($origin_assistant_role != -1 ) {
            $where_arr[]=sprintf ( "origin_assistantid in  (select uid from  %s where account_role = %d ) ",  t_manager_info::DB_TABLE_NAME, $origin_assistant_role );
        }

        $sql=$this->gen_sql_new(
            "select tr.curl_stu_request_test_lesson_time except_lesson_time,sum(seller_student_status=0) no_revisit,sum(seller_student_status=1) invalid_resource,sum(seller_student_status=2) no_connected,sum(seller_student_status in (0,1,2)) follow_up,sum(seller_student_status=100) valid_A,sum(seller_student_status=101) valid_B,sum(seller_student_status=102) valid_C,sum(seller_student_status in (100,101,102)) pri_follow_up,sum(seller_student_status=103) valid_indete,sum(seller_student_status=110) valid_reject,sum(seller_student_status=120) valid_cancel,sum(seller_student_status=200) valid_no_course,sum(seller_student_status in (103,110,120,200)) wait_class,sum(seller_student_status=210) arrange_course,sum(seller_student_status=220) pending_class,sum(seller_student_status=290) listened_follow_up,sum(seller_student_status in (220,290)) already_notified,sum(seller_student_status=300) listened_A,sum(seller_student_status=301) listened_B,sum(seller_student_status=302) listened_C,sum(seller_student_status=303) no_listened_A,sum(seller_student_status=304) no_listened_B,sum(seller_student_status=305) no_listened_C,sum(seller_student_status in (300,301,302,303,304,305)) pending_contract,sum(seller_student_status=420) have_contract "
            ."from  %s t "
            ." left join %s ss on  ss.userid = t.userid "
            ."  left join %s s on ss.userid=s.userid   "
            ." left join %s tr on   t.current_require_id = tr.require_id "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." left join %s o on  o.from_test_lesson_id = l.lessonid "
            ." left join %s f on f.from_key_int = o.orderid "
            ." left join %s tt on l.teacherid = tt.teacherid"
            ." left join %s a on s.assistantid = a.assistantid"
            ." where  %s "
            , t_test_lesson_subject::DB_TABLE_NAME
            , self::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            , t_test_lesson_subject_require::DB_TABLE_NAME
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME
            , t_lesson_info::DB_TABLE_NAME
            , t_order_info::DB_TABLE_NAME
            , t_flow::DB_TABLE_NAME
            , t_teacher_info::DB_TABLE_NAME
            , t_assistant_info::DB_TABLE_NAME
            ,$where_arr
        );
        // dd($sql);
        return $this->main_get_row($sql);
    }



    public function get_assign_list (
            $page_num, $page_count,$userid, $admin_revisiterid, $seller_student_status ,
            $origin, $opt_date_str, $start_time, $end_time, $grade, $subject,
            $phone_location, $origin_ex,  $has_pad, $sub_assign_adminid_2,$seller_resource_type,
            $origin_assistantid,$tq_called_flag,$global_tq_called_flag,
            $tmk_adminid,$tmk_student_status,$origin_level ,$seller_student_sub_status
            , $order_by_str,$publish_flag,$admin_del_flag, $account_role, $sys_invaild_flag,$seller_level,$wx_invaild_flag,$do_filter=-1, $first_seller_adminid=-1, $call_phone_count=-1
    ) {


        if ($userid>0) {
            $where_arr=[
                ["ss.userid=%u",$userid, -1],
            ];

            if ( $sub_assign_adminid_2 >0 ) { //
                $this->where_arr_add__2_setid_field($where_arr,"ss.sub_assign_adminid_2", $sub_assign_adminid_2);
            }
        }else{
            $where_arr=[
                ["t.subject=%u",$subject, -1],
                ["s.origin like '%%%s%%'", $this->ensql( $origin), ""],
                // "s.lesson_count_all=0",
                ["ss.phone_location like '%%%s%%'",$phone_location, ""],
                ["ss.seller_resource_type = %d " ,$seller_resource_type, -1],
                ["ss.tq_called_flag = %d " ,$tq_called_flag, -1],
                ["ss.global_tq_called_flag = %d " ,$global_tq_called_flag, -1],
                // "t.require_admin_type=2",
            ];

            if($do_filter <1){
                $where_arr[] = "t.require_admin_type=2";
                $where_arr[] = "s.lesson_count_all=0";
            }

            $where_arr[]=$this->where_get_in_str_query("m.account_role",$account_role);

            $where_arr[]=$this->where_get_in_str_query("s.grade",$grade);
            $this->where_arr_add_int_or_idlist($where_arr,"origin_level",$origin_level );
            $this->where_arr_add_int_field($where_arr,"sys_invaild_flag",$sys_invaild_flag);
            $this->where_arr_add_int_or_idlist ($where_arr,"seller_level",$seller_level);
            $this->where_arr_add_int_or_idlist ($where_arr,"call_phone_count",$call_phone_count);
            //wx
            $this->where_arr_add_int_field($where_arr,"wx_invaild_flag",$wx_invaild_flag);
            if ($has_pad==-2) {
                $where_arr[]="ss.has_pad <>10";
            }else{
                $where_arr[]=["ss.has_pad=%u",$has_pad, -1];
            }
            $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
            $this->where_arr_add__2_setid_field($where_arr,"origin_assistantid", $origin_assistantid );
            $this->where_arr_add__2_setid_field($where_arr,"first_seller_adminid", $first_seller_adminid);
            $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid", $tmk_adminid);
            if ($publish_flag==0) {
                $where_arr[]="t.seller_student_status =50";
            }else if($publish_flag ==1 ){
                $where_arr[]="t.seller_student_status <>50";
            }

            $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
            $where_arr[]= $ret_in_str;

            $where_arr[]=['seller_student_status=%d', $seller_student_status,-1];
            $where_arr[]=['seller_student_sub_status=%d', $seller_student_sub_status,-1];
            $where_arr[]=['tmk_student_status=%d', $tmk_student_status,-1];
            $where_arr[]=['m.del_flag=%d', $admin_del_flag ,-1];

            $this->where_arr_add__2_setid_field($where_arr,"ss.admin_revisiterid",$admin_revisiterid);
            $this->where_arr_add__2_setid_field($where_arr,"ss.sub_assign_adminid_2", $sub_assign_adminid_2);
        }

        if ( !$order_by_str ) {
            $order_by_str= " order by $opt_date_str desc";
        }


        $sql=$this->gen_sql_new(
            "select  seller_resource_type ,first_call_time,first_contact_time,first_revisit_time,tmk_assign_time,  competition_call_adminid, competition_call_time,sys_invaild_flag,wx_invaild_flag, return_publish_count, tmk_adminid, t.test_lesson_subject_id ,seller_student_sub_status, add_time,  global_tq_called_flag, seller_student_status,wx_invaild_flag, s.userid,s.nick, s.origin, s.origin_level,ss.phone_location,ss.phone,ss.userid,ss.sub_assign_adminid_2,ss.admin_revisiterid, ss.admin_assign_time, ss.sub_assign_time_2 , s.origin_assistantid , s.origin_userid  ,  t.subject, s.grade,ss.user_desc, ss.has_pad,t.require_adminid ,tmk_student_status "
            . ",first_tmk_set_valid_admind,first_tmk_set_valid_time,tmk_set_seller_adminid,first_tmk_set_seller_time,first_admin_master_adminid,first_admin_master_time,first_admin_revisiterid,first_admin_revisiterid_time,first_seller_status "
            ." from %s t "
            ." left join %s ss on  ss.userid = t.userid "
            ." left join %s s on ss.userid=s.userid "
            ." left join %s m on  ss.admin_revisiterid =m.uid "
            ." where  %s  $order_by_str  "
            , t_test_lesson_subject::DB_TABLE_NAME
            , self::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            , t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        // dd($sql);
        return $this->main_get_list_by_page($sql,$page_num,$page_count);
    }
    public function get_un_assign_info( $sub_assign_adminid_2 ) {

    }
    public function set_up_adminid_info($userid,$adminid){

        $up_adminid=$this->t_admin_group_user->get_master_adminid($adminid);
        $set_arr=[
            "sub_assign_adminid_2"  => $up_adminid,
            "sub_assign_time_2"  => time(NULL) ,
            "sub_assign_adminid_1"  => $this->t_admin_main_group_name->get_up_group_adminid($up_adminid),
            "sub_assign_time_1"  => time(NULL),
        ];
        return $this->field_update_list($userid,$set_arr);
    }

    public function set_level_b(  $userid_list , $origin_level=3) {

        if ( count($userid_list) ==0 ) {
            return false;
        }
        $in_str=$this->where_get_in_str("s.userid",$userid_list);
        $sql=sprintf("update %s  n join %s s on n.userid=s.userid "
                     ." set origin_level= %u where %s  ",
                     self::DB_TABLE_NAME,
                     t_student_info::DB_TABLE_NAME,
                     $origin_level,
                     $in_str );
        return $this->main_update($sql);
    }

    public function set_admin_info_new( $opt_type, $userid, $opt_adminid ,$self_adminid ,  $opt_account, $account ) {

        $now=time(NULL);
        $ss_info= $this->task->t_seller_student_new->field_get_list($userid,"seller_resource_type,tmk_student_status,phone ,first_admin_master_adminid , first_admin_master_time ,first_admin_revisiterid ,first_admin_revisiterid_time");
        $tmk_student_status=$ss_info["tmk_student_status"];
        $phone=$ss_info["phone"];
        $set_arr=[];
        if($opt_type==0 || $opt_type==3 ) { //set admin , tmk 设置给cc


            $up_adminid=$this->t_admin_group_user->get_master_adminid($opt_adminid);
            $sub_assign_adminid_1 =$this->t_admin_main_group_name->get_up_group_adminid($up_adminid);
            $set_arr=[
                "admin_revisiterid"  => $opt_adminid,
                "admin_assign_time"  => $now,
                "sub_assign_adminid_2"  => $up_adminid,
                "sub_assign_time_2"  => $now ,
                "sub_assign_adminid_1"  => $sub_assign_adminid_1,
                "sub_assign_time_1"  => $now,
                "hold_flag" => 1,

            ];

            if ($opt_type==3 ||  ($tmk_student_status==E\Etmk_student_status::V_3)  ) {
                $set_arr["tmk_set_seller_time"]=$now;
                $set_arr["tmk_set_seller_adminid"]=$opt_adminid;
                $set_arr["first_tmk_set_seller_time"]=$now;
            }

            if ( $ss_info["seller_resource_type"]==0) {
                if (!$ss_info["first_admin_master_time"]) {
                    $set_arr["first_admin_master_adminid"]=$up_adminid;
                    $set_arr["first_admin_master_time"]=$now;
                }


                if (!$ss_info["first_admin_revisiterid"]) {
                    $set_arr["first_admin_revisiterid"]= $opt_adminid;
                    $set_arr["first_admin_revisiterid_time"]=$now;
                }

            }

            $this->t_test_lesson_subject->set_seller_require_adminid([$userid] , $opt_adminid );

            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: $account 状态: 分配给组员 [ $opt_account ] ",
                "system"
            );
            $this->t_id_opt_log->add(E\Edate_id_log_type::V_SELLER_ASSIGNED_COUNT
                                     ,$opt_adminid,$userid);

        }else if ( $opt_type ==1){ //分配主管
            $up_adminid=$this->t_admin_group_user->get_master_adminid($opt_adminid);
            $set_arr=[
                "admin_assignerid"  => $self_adminid,
                "sub_assign_adminid_2"  => $opt_adminid,
                "sub_assign_time_2"  => time(NULL),
                "admin_revisiterid"  => 0,
                "sub_assign_adminid_1"  => $this->t_admin_main_group_name->get_up_group_adminid($opt_adminid),
                "sub_assign_time_1"  => time(NULL),
            ];

            if ( $ss_info["seller_resource_type"]==0) {
                if (!$ss_info["first_admin_master_time"]) {
                    $set_arr["first_admin_master_adminid"]=$up_adminid;
                    $set_arr["first_admin_master_time"]=$now;
                }
            }




            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: $account 状态: 分配给主管 [ $opt_account ] ",
                "system"
            );


        }else if ( $opt_type==2) { //TMK
            $set_arr=[
                "tmk_assign_time"  => time(NULL) ,
                "tmk_adminid"  => $opt_adminid,
                "tmk_join_time"  => time(NULL),
                "tmk_student_status"  => 0,
                "hold_flag" => 1,
            ];
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: $account 状态: 分配给TMK [ $opt_account ] ",
                "system"
            );

        }
        $set_str=$this-> get_sql_set_str( $set_arr);
        $sql=sprintf("update %s set %s where userid=%u",
                            self::DB_TABLE_NAME,
                            $set_str,
                            $userid );
        return $this->main_update($sql);

    }

    public function set_admin_info( $opt_type, $userid_list, $opt_adminid ,$self_adminid ) {

        if ( count($userid_list) ==0 ) {
            return false;
        }
        $set_arr=[];
        if($opt_type==0 || $opt_type==3 ) { //set admin

            $up_adminid=$this->t_admin_group_user->get_master_adminid($opt_adminid);
            $set_arr=[
                "admin_revisiterid"  => $opt_adminid,
                "admin_assign_time"  => time(NULL),
                "sub_assign_adminid_2"  => $up_adminid,
                "sub_assign_time_2"  => time(NULL) ,
                "sub_assign_adminid_1"  => $this->t_admin_main_group_name->get_up_group_adminid($up_adminid),
                "first_seller_adminid" => $opt_adminid,
                "sub_assign_time_1"  => time(NULL),
                "hold_flag" => 1,

            ];
            if ($opt_type==1){

            }else{
                $set_arr["tmk_set_seller_adminid"]=$opt_adminid;
            }
            $this->t_test_lesson_subject->set_seller_require_adminid( $userid_list, $opt_adminid );
        }else if ( $opt_type ==1){ //分配主管
            $set_arr=[
                "admin_assignerid"  => $self_adminid,
                "sub_assign_adminid_2"  => $opt_adminid,
                "sub_assign_time_2"  => time(NULL),
                "admin_revisiterid"  => 0,
                "sub_assign_adminid_1"  => $this->t_admin_main_group_name->get_up_group_adminid($opt_adminid),
                "sub_assign_time_1"  => time(NULL),
            ];
        }else if ( $opt_type==2) { //TMK
            $set_arr=[
                "tmk_assign_time"  => time(NULL) ,
                "tmk_adminid"  => $opt_adminid,
                "tmk_join_time"  => time(NULL),
                "tmk_student_status"  => 0,
                "hold_flag" => 1,
            ];
        }
        $set_str=$this-> get_sql_set_str( $set_arr);
        $in_str=$this->where_get_in_str("userid",$userid_list);
        $sql=sprintf("update %s set %s where %s  ",
                            self::DB_TABLE_NAME,
                            $set_str,
                            $in_str);
        return $this->main_update($sql);
    }

    public function set_hold_admin_list( $hold_flag, $userid_list,$admin_revisiterid ) {

        if ( count($userid_list) ==0 ) {
            return false;
        }
        $set_arr=["admin_revisiterid=$admin_revisiterid"];
        $in_str=$this->where_get_in_str("userid",$userid_list);
        $where_arr[]=$in_str;
        $where_str=$this->where_str_gen($where_arr);

        $sql=sprintf("update %s set hold_flag=%d where %s  ",
                     self::DB_TABLE_NAME,
                     $hold_flag, $where_str);
        return $this->main_update($sql);
    }

    public function set_no_hold_admin( $hold_flag, $userid,$admin_revisiterid ) {
        $where_arr = [
            ["admin_revisiterid = %u",$admin_revisiterid,-1],
            ["userid = %u",$userid,-1]
        ];

        $sql=$this->gen_sql_new("update %s set hold_flag=%d where %s  ",
                     self::DB_TABLE_NAME,
                     $hold_flag, $where_arr);
        return $this->main_update($sql);
    }



    public function get_no_call_count($adminid) {
        $start_time=strtotime(date("Y-m-d", time(NULL)-30*86400) );
        $sql = $this->gen_sql_new("select count(*) from %s where   admin_assign_time  > %u and admin_revisiterid=%u  and  tq_called_flag=0 ",
            self::DB_TABLE_NAME,  $start_time , $adminid );
        return $this->main_get_value($sql);
    }


    public function get_new_list($page_num,$start_time,$end_time,$grade, $has_pad, $subject,$origin,$phone,$not_adminid , $t_flag=0)
    {
        $check_time =  time(NULL)  - 2*3600;
        $where_arr=[
            ["s.grade=%u", $grade, -1 ],
            ["has_pad=%u", $has_pad, -1 ],
            ["subject=%u", $subject, -1 ],
            ["origin like '%%%s%%'", $origin, ""],
            ["n.phone='%s'", $phone, ""],
            "admin_revisiterid=0",
            "sub_assign_adminid_2=0",
            "require_admin_type=2",
            "seller_resource_type=0",
            "fl.adminid is null ",
            "lesson_count_all=0",
            "tmk_adminid=0",
            "origin_level <> 99",
            "sys_invaild_flag=0",
            "s.grade<300", //非高中
            "competition_call_time<$check_time",
        ];

        if ($t_flag==1) {
            $where_arr[] = "(origin_level = 90 )";
        }else if ($t_flag==2 ){
            $where_arr[] = "( global_tq_called_flag=1 and origin_level <> 90 )";
        }else{
            $where_arr[] = "origin_level <> 90";
        }
        // E\Etq_called_flag

        $seller_level= $this->t_manager_info->get_seller_level($not_adminid);
        // E\Eseller_level
        // E\Eorigin_level
        $seller_level_flag= floor( $seller_level/100);
        \App\Helper\Utils::logger("seller_level_flag:$seller_level_flag");

        $before_24_time=time(NULL) -3600*6;
        if ($seller_level_flag<=3) { //b类以上
            $before_24_time= time(NULL) -3600*3;
        }

        $before_48_time= $before_24_time - 86400;
        $check_no_call_time_str=" (( origin_level >0  and n.add_time < $before_24_time )  or ( n.add_time < $before_48_time  )) ";
        \App\Helper\Utils::logger( "seller_level_flag:".$seller_level_flag);

        switch ( $seller_level_flag ) {
        case 1 :  //s
        case 2 :  //a
            $where_arr[] = "(origin_level >0  or $check_no_call_time_str) ";
            break;
        case 3 : //b
            $where_arr[] = "(origin_level >2 or $check_no_call_time_str )";
            break;
        case 4 : //c
        case 5 : //d
        case 6 : //e
            $where_arr[] = "(origin_level >3 or $check_no_call_time_str )";
            break;

        default:
            if ($t_flag) {
            }else{
                $where_arr[] = "false";
            }
            break;
        }

        /*
        switch ( $seller_level_flag ) {
        case 1 :  //s
        case 2 :  //a
            $where_arr[] = "origin_level >0 ";
            break;
        case 3 : //b
            $where_arr[] = "origin_level >1 ";
            break;
        case 4 : //c
        case 5 : //d
            $where_arr[] = "origin_level >2 ";
            break;
        default:
            $where_arr[] = "false";
            break;
        }
        */

        $order_by_src="desc";
        if ($seller_level_flag>=4)   {
            $order_by_src="asc";
        }

        $this->where_arr_add_time_range($where_arr,"n.add_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(
            "select t.test_lesson_subject_id,n.add_time,n.userid,n.phone,n.phone_location,s.grade,t.subject,n.has_pad,s.origin, origin_level  "
            ." from %s t "
            ." left join %s n on t.userid=n.userid "
            ." left join %s s on s.userid=n.userid "
            ." left join %s fl on (fl.userid=n.userid  and fl.adminid = $not_adminid ) "
            ." where  %s order by   competition_call_time asc, origin_level asc , n.add_time  $order_by_src ",
            t_test_lesson_subject::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_subject_free_list::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_page_random($sql,1);
    }

    public function get_new_list_time($start_time,$end_time,$grade, $has_pad, $subject,$phone)
    {
        $where_arr=[
            ["s.grade=%u", $grade, -1 ],
            ["has_pad=%u", $has_pad, -1 ],
            ["subject=%u", $subject, -1 ],
            ["n.phone='%s'", $phone, ""],
            "admin_revisiterid=0",
            "require_admin_type=2",
            "seller_resource_type=0",
            "tmk_adminid=0",
            "origin_level in (0, 2, 3) ",
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(
            "select t.test_lesson_subject_id,n.add_time,n.userid,n.phone,n.phone_location,s.grade,t.subject,n.has_pad,s.origin "
            ." from %s t "
            ." left join %s n on t.userid=n.userid "
            ." left join %s s on s.userid=n.userid "
            ." where  %s ",
            t_test_lesson_subject::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_page_random($sql,2);
    }


    public function get_free_seller_list($page_num, $start_time, $end_time ,$adminid ,$grade, $has_pad, $subject,$origin,$nick,$phone
    ) {
        $where_arr=[
            ["s.grade=%u", $grade, -1 ],
            ["has_pad=%u", $has_pad, -1 ],
            ["subject=%u", $subject, -1 ],
            "s.lesson_count_all=0",
            "n.seller_resource_type=1",
            "n.admin_revisiterid=0",
            "t.seller_student_status <>  50",
            "n.sys_invaild_flag=0",
            ["origin like '%s%%'", $this->ensql( $origin), ""],
            ["s.nick like '%s%%'",$this->ensql($nick), ""],
            ["n.phone like '%s%%'", $this->ensql( $phone), ""],
        ];
        if (!($nick || $phone)) {
            $now=time(NULL);
            $this->where_arr_add_time_range($where_arr,"n.add_time",$start_time ,$end_time);
            $where_arr[]= "f.adminid is null ";
        }

        $sql = $this->gen_sql_new(
            "select t.test_lesson_subject_id,n.add_time,n.userid,n.phone,n.phone_location,s.grade,t.subject,n.has_pad,s.origin "
            ." from %s t "
            ." left join %s n on t.userid=n.userid "
            ." left join %s s on s.userid=n.userid "
            ." left join %s m on n.admin_revisiterid=m.uid  "
            ." left join %s f on (t.userid=f.userid  and f.adminid = $adminid )  "
            ." where    %s  ",
            t_test_lesson_subject::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_test_subject_free_list::DB_TABLE_NAME,
             $where_arr
        );
        return $this->main_get_page_random ($sql,2);
    }
    public function get_free_seller_list_time($start_time,$end_time,$grade, $has_pad, $subject,$nick,$phone) {
        $where_arr=[
            ["s.grade=%u", $grade, -1 ],
            ["has_pad=%u", $has_pad, -1 ],
            ["subject=%u", $subject, -1 ],
            ["add_time>=%u", $start_time, -1 ],
            ["add_time<=%u", $end_time, -1 ],
            "s.lesson_count_all=0",
            "n.seller_resource_type=1",
            "n.admin_revisiterid=0",
            ["s.nick like '%%%s%%'",$this->ensql($nick), ""],
            ["n.phone like '%s%%'", $this->ensql( $phone), ""],
        ];
        $sql = $this->gen_sql_new(
            "select t.test_lesson_subject_id,n.add_time,n.userid,n.phone,n.phone_location,s.grade,t.subject,n.has_pad,s.origin "
            ." from %s t "
            ." left join %s n on t.userid=n.userid "
            ." left join %s s on s.userid=n.userid "
            ." left join %s m on n.admin_revisiterid=m.uid  "
            ." where    %s  ",
            t_test_lesson_subject::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_page_random ($sql);
    }



    public function get_no_called_list($page_num,$adminid ,$grade, $has_pad, $subject,$origin) {
        $where_arr=[
            ["s.grade=%u", $grade, -1 ],
            ["has_pad=%u", $has_pad, -1 ],
            ["subject=%u", $subject, -1 ],
            "t.seller_student_status<200",
            ["origin like '%%%s%%'", $origin, ""],
        ];

        $sql = $this->gen_sql_new(
            "select t.test_lesson_subject_id,n.add_time,n.userid,n.phone,n.phone_location,s.grade,t.subject,n.has_pad,s.origin "
            ." from %s t "
            ." left join %s n on t.userid=n.userid "
            ." left join %s s on s.userid=n.userid "
            ." left join %s m on n.admin_revisiterid=m.uid  "
            ." where ".
            "( ( admin_assign_time <%u   and   seller_level=1   ) or ".
            " ( admin_assign_time <%u  and   seller_level=2   ) or ".
            " ( admin_assign_time <%u  and   seller_level not in (1,2)   ) ".
            ") and admin_assign_time > %u ".
            " and admin_revisiterid<>%u  and global_tq_called_flag <2  and %s   ",
            t_test_lesson_subject::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            //1 =8  2 ->6 3->4
            time(NULL)-8*86400,
            time(NULL)-6*86400,
            time(NULL)-4*86400,
            time(NULL)-180*86400 ,
            $adminid, $where_arr
        );
        return $this->main_get_page_random($sql);
    }
    public function get_all_tq_no_call_count($start_time,$end_time) {
        $where_arr=[
            "require_admin_type=2" ,
            "global_tq_called_flag = 0",
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select count(*) "
            ." from %s n "
            ."join %s t on t.userid=n.userid "
            ." where    %s   ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_tq_no_call_count($adminid) {
        $start_time=strtotime(date("Y-m-d", time(NULL)-30*86400) );
        $sql = $this->gen_sql_new(
            "select count(*) "
            ." from %s ".
            " where   admin_assign_time  > %u and admin_revisiterid=%u and  tq_called_flag  = 0 ",
            self::DB_TABLE_NAME, $start_time , $adminid );
        return $this->main_get_value($sql);
    }

    public function get_fail_count_from_require($adminid) {
        $start_time = strtotime(date("Y-m-d",time(NULL)-30*86400));
        $sql = $this->gen_sql_new("select count(*) "
                                  ." from %s n, %s t "
                                  ." where n.userid=t.userid "
                                  ." and admin_assign_time > %u "
                                  ." and admin_revisiterid=%u "
                                  ." and seller_student_status in (110,120)"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$start_time
                                  ,$adminid
        );
        return $this->main_get_value($sql);
    }

    public function get_next_revisit_count($adminid) {
        $start_time = strtotime(date("Y-m-d"));
        $end_time= $start_time+86400;
        $sql = $this->gen_sql_new("select count(*) "
                                  ." from %s  "
                                  ." where "
                                  ." next_revisit_time >= %u "
                                  ." and next_revisit_time < %u "
                                  ." and admin_revisiterid=%u "
                                  ,self::DB_TABLE_NAME
                                  ,$start_time,$end_time
                                  ,$adminid
        );
        return $this->main_get_value($sql);
    }



    public function get_lesson_status_count($adminid) {
        $start_time=strtotime(date("Y-m-d", time(NULL)-60*86400) );
        //E\Etmk_student_status
        $sql = $this->gen_sql_new(
            "select  "
            ."sum(seller_student_status=200) lesson_status_200_count , "
            ."sum(seller_student_status=210) lesson_status_210_count , "
            ."sum(seller_student_status=220) lesson_status_220_count , "
            ."sum(seller_student_status=290) lesson_status_290_count , "
            ."sum(seller_student_status=0 &&  seller_resource_type=0 ) new_not_call_count,  "
            ."sum(tmk_student_status=3  &&  seller_student_status=0  ) tmk_new_no_call_count,  "
            ."sum( seller_student_status=0 ) not_call_count  "
            ." from %s n, %s t "
            ." where  n.userid=t.userid and   admin_assign_time > %u and admin_revisiterid=%u  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $start_time , $adminid );

        return $this->main_get_row($sql);
    }



    public function get_userid_by_phone($phone) {
        $sql=$this->gen_sql_new(
            "select userid from %s where phone='%s'",
            self::DB_TABLE_NAME,
            $phone
        );
        return $this->main_get_value($sql);
    }

    public  function sync_tq($phone,$tq_called_flag,$call_time) {
        $userid=$this->get_userid_by_phone($phone);
        if($userid) {
            $item=$this->field_get_list($userid,"tq_called_flag,global_tq_called_flag,admin_revisiterid, competition_call_adminid,  seller_resource_type ,last_contact_time,first_contact_time ,called_time, first_call_time,tmk_student_status");

            $set_arr=[];
            if ($item["tq_called_flag"]<$tq_called_flag) {
                $set_arr["tq_called_flag"]=$tq_called_flag;
            }

            if ($item["global_tq_called_flag"]<$tq_called_flag) {
                $set_arr["global_tq_called_flag"]=$tq_called_flag;
            }


            if (!$item["first_call_time"]) {
                $set_arr["first_call_time"]=$call_time;
            }


            if ($tq_called_flag ==2) {
                if (!$item["first_contact_time"]) {
                    $set_arr["first_contact_time"]=$call_time;
                }

                if ($item["last_contact_time"] < $call_time) {
                    $set_arr["last_contact_time"]=$call_time;
                }
                $set_arr["called_time"]=$call_time;
            }else{ //未接通
                $set_arr["called_time"]=$item["called_time"]+1;
            }


            if (count($set_arr) >0 ) {
                $this->field_update_list($userid,$set_arr);
            }
            $competition_call_adminid=$item["competition_call_adminid"];

            /*
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者:   [$account] 拨打 : ". E\Etq_called_flag::get_desc($tq_called_flag ) ,
                "system"
            );
            */
            //同步给销售
            if (  $tq_called_flag ==2
                  &&  $item["seller_resource_type"] ==0
                  &&  $item["tmk_student_status"]<>E\Etmk_student_status::V_3
                  && !$item["admin_revisiterid"]
                  && $call_time > time(NULL) -3600
                  &&  $competition_call_adminid ) {
                if ($this->t_seller_new_count->check_and_add_new_count($competition_call_adminid ,"获取新例子"))  {
                    \App\Helper\Utils::logger("SET COMPETITION_CALL_ADMINID ");

                    $account=$this->t_manager_info->get_account( $competition_call_adminid );
                    $this->set_admin_info(0, [$userid], $competition_call_adminid     , 0 );

                    $ret_update = $this->t_book_revisit->add_book_revisit(
                        $phone,
                        "操作者:  抢单 [$account] ",
                        "system"
                    );

                }
            }

            $this->t_test_lesson_subject->set_no_connect_for_sync_tq($userid);
        }

    }

    public function tongji_last_revisite_time($start_time,$end_time)  {

        $sql = $this->gen_sql("select  admin_revisiterid as id, count(*) as count from %s ".
                              " where last_revisit_time >=%u and  last_revisit_time <%u group by admin_revisiterid  ",
                              self::DB_TABLE_NAME,$start_time, $end_time);
        return $this->main_get_list($sql);
    }
    public function get_today_next_revisit_count( $admin_revisiterid )
    {//
        $today=strtotime( date("Y-m-d" )) ;
        $sql = $this->gen_sql(
            "select count(*) from %s where admin_revisiterid=%u and next_revisit_time>=%u and next_revisit_time < %u  ",
            self::DB_TABLE_NAME,
            $admin_revisiterid,
            $today-864000,
            $today+86400
        );
        return $this->main_get_value($sql);
    }



    public function get_hold_list($page_num,$page_count,$admin_revisiterid,$hold_flag, $subject, $grade, $seller_student_status,$nick,$phone ){

        if ( $phone || $nick) {
            $where_arr= [
                ["ss.phone like '%s%%'", $this->ensql( $phone), ""],
                ["s.nick like '%%%s%%'",$this->ensql($nick), ""],
            ];
        }else{
            $where_arr=[
                ["t.subject=%u",$subject, -1],
                ["ss.grade=%u",$grade, -1],
            ];
            $where_arr[]=['seller_student_status=%d', $seller_student_status,-1];
            $where_arr[]=["ss.hold_flag=%u",$hold_flag, -1];
        }

        $where_arr[]=["ss.admin_revisiterid=%u",$admin_revisiterid, -1];


        $sql=$this->gen_sql_new(
            "select cur_require_adminid,tr.test_lesson_order_fail_flag, s.user_agent,hold_flag, lesson_count_left, tr.notify_lesson_day1, tr.notify_lesson_day2, tss.confirm_time,tss.confirm_adminid, tss.fail_greater_4_hour_flag , tr.current_lessonid, tss.test_lesson_fail_flag, tss.success_flag,  tss.fail_greater_4_hour_flag,  tss.fail_reason, t.current_require_id, t.test_lesson_subject_id ,add_time,   seller_student_status,  s.userid,s.nick, s.origin, ss.phone_location,ss.phone,ss.userid,ss.sub_assign_adminid_2,ss.admin_revisiterid, ss.admin_assign_time, ss.sub_assign_time_2 , s.origin_assistantid , s.origin_userid  ,  t.subject, s.grade,ss.user_desc, ss.has_pad, ss.last_revisit_time,ss.last_revisit_msg,tq_called_flag,next_revisit_time,l.lesson_start,l.lesson_del_flag, tr.require_time, l.teacherid, t.stu_test_paper, t.tea_download_paper_time,tr.seller_require_change_flag ".
            " from  %s t "
            ." left join %s ss on  ss.userid = t.userid "
            ."  left join %s s on ss.userid=s.userid   "
            ." left join %s tr on   t.current_require_id = tr.require_id "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  (tss.lessonid = l.lessonid and l.lesson_del_flag=0 ) "
            ." where  %s  order by  add_time asc"
            , t_test_lesson_subject::DB_TABLE_NAME
            , self::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            , t_test_lesson_subject_require::DB_TABLE_NAME
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME
            , t_lesson_info::DB_TABLE_NAME
            ,$where_arr
        );
        //dd($sql);
        return $this->main_get_list_by_page($sql,$page_num,$page_count);

    }

    public function get_hold_count( $admin_revisiterid, $hold_flag=1 ){
        $where_arr=[
            ["hold_flag=%u", $hold_flag, -1 ],
        ];
        $sql=$this->gen_sql_new(
            "select count(*) from %s n join %s t on n.userid=t.userid where admin_revisiterid=%u and %s",
            self::DB_TABLE_NAME ,
            t_test_lesson_subject::DB_TABLE_NAME,
            $admin_revisiterid,
            $where_arr
        );
        return $this->main_get_value($sql);
    }


    //
    public function get_user_info_for_free($userid) {
        $sql=$this->gen_sql_new(
            "select n.userid,phone, seller_student_status from %s n  join %s t  on  n.userid=t.userid    "
            ."  where  n.userid=%u limit 1 ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $userid
        );
        return $this->main_get_row($sql);
    }

    public function get_no_hold_list($admin_revisiterid) {
        $sql=$this->gen_sql_new(
            "select n.userid,phone, seller_student_status from %s n  join %s t  on  n.userid=t.userid    "
            ."  where  hold_flag=0  and admin_revisiterid=%u ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $admin_revisiterid
        );
        return $this->main_get_list($sql);
    }
    public function set_all_hold($admin_revisiterid) {
        $sql=$this->gen_sql_new(
            "update %s  set  hold_flag =1   "
            ." where admin_revisiterid=%u ",
            self::DB_TABLE_NAME,
            $admin_revisiterid
        );
        return $this->main_update($sql);
    }


    public function set_user_free($userid) {
        $sql=$this->gen_sql_new(
            "update %s n  join %s t  on  n.userid=t.userid  set  sub_assign_time_1=0,sub_assign_adminid_1=0, sub_assign_time_2=0,sub_assign_adminid_2=0,  admin_assign_time=0,admin_revisiterid=0, seller_resource_type=1, require_adminid=0 , return_publish_count=return_publish_count +1 "
            ."  where   n.userid=%u  and require_admin_type=2  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $userid
        );
        //dd($sql);
        return $this->main_update($sql);
    }


    public function set_no_hold_free($admin_revisiterid) {
        $sql=$this->gen_sql_new(
            "update %s n  join %s t  on  n.userid=t.userid  set  sub_assign_time_1=0,sub_assign_adminid_1=0, sub_assign_time_2=0,sub_assign_adminid_2=0,  admin_assign_time=0,admin_revisiterid=0, seller_resource_type=1, require_adminid=0 , return_publish_count=return_publish_count +1 "
            ."  where  hold_flag=0  and admin_revisiterid=%u  and require_admin_type=2  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $admin_revisiterid
        );
        //dd($sql);
        return $this->main_update($sql);
    }

    public function check_admin_add($admin_revisiterid,&$get_count , &$max_count) {
        $get_count=$this->get_hold_count($admin_revisiterid, -1 );
        $seller_level=$this->t_manager_info->get_seller_level($admin_revisiterid);
        $conf=\App\Helper\Config::get_seller_hold_user_count();
        $max_count=$conf[$seller_level];
        return $max_count>$get_count;
        //return $get_count>=

    }

    public function get_hour_user_count($start_time,$end_time,$origin_ex){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $sql=$this->gen_sql_new(
            "select from_unixtime(add_time, '%%k' ) as hour , count(*) as count from %s n "
            ." join %s s on s.userid=n.userid  "
            ." where %s   group by  from_unixtime(add_time, '%%k' )  order by hour ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql,function($item){
            return $item["hour"];
        });

    }

    public function get_origin_info_by_order($start_time,$end_time,$origin,$origin_ex,$adminid_list){
        $where_arr = [
            "require_admin_type =2",
            ["s.origin like '%%%s%%' ",$origin,""],
            ["add_time  >= %u",$start_time,-1],
            ["add_time <= %u",$end_time,-1]
        ];
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $sql= $this->gen_sql_new("select sum(global_tq_called_flag=1) tq_flag_1_count , sum(global_tq_called_flag=2) tq_flag_2_count , count(*) all_count,sum(success_flag is not null) lesson_count,sum(success_flag in (0,1)) success_count,sum(o.orderid is not null) order_count,s.origin from %s n".
                                 " left join %s s on n.userid = s.userid".
                                 " left join %s t on n.userid = t.userid".
                                 " left join %s tr on tr.require_id = t.current_require_id ".
                                 " left join %s tss on tr.current_lessonid = tss.lessonid ".
                                 " left join %s o on tss.lessonid = o.from_test_lesson_id".
                                 " where %s group by s.origin ",
                                 self::DB_TABLE_NAME,
                                 t_student_info::DB_TABLE_NAME,
                                 t_test_lesson_subject::DB_TABLE_NAME,
                                 t_test_lesson_subject_require::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_no_call_to_free_list( $page_num,$page_count,$admin_revisiterid ,$global_tq_called_flag ,$seller_student_status){
        $where_arr=[
            ["admin_revisiterid=%u", $admin_revisiterid ,-1 ],
            ["global_tq_called_flag=%u", $global_tq_called_flag,-1 ],
            ["seller_student_status=%u", $seller_student_status,-1 ],
            ["add_time>%u", time(NULL)-15*86400*4,-1 ],

            "is_test_user=0",
        ];
        $now=time(NULL);

        $sql=$this->gen_sql_new(
            "select add_time,  admin_revisiterid, n.userid, n.phone, n.user_desc, account, admin_assign_time ,global_tq_called_flag ,seller_student_status,seller_level  from %s n "
            ." join %s m on m.uid= n.admin_revisiterid  "
            ." join %s s on s.userid= n.userid"
            ." join %s t on t.userid= n.userid"
            ." left join %s tr on tr.test_lesson_subject_id = t.test_lesson_subject_id"
            ." where  seller_resource_type =0  "
            ." and  global_tq_called_flag <2 "
            ." and  ("
            ."     (seller_level=1 and admin_assign_time <$now -96*3600) "
            ."     or (seller_level=2 and admin_assign_time  <$now -72*3600) "
            ."     or (seller_level=3 and admin_assign_time  <$now -48*3600) "
            ."     ) and %s "
            ,
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,$page_count);

    }
    public function free_to_new_user($userid,$account) {
        $this->t_seller_student_new->field_update_list($userid,[
            "admin_revisiterid"  => 0,
            "admin_assign_time"  => 0,
            "sub_assign_adminid_2"  => 0,
            "sub_assign_time_2"  => 0 ,
            "sub_assign_adminid_1"  =>0,
            "sub_assign_time_1"  => 0,
            "hold_flag" => 0,
            "user_desc" => "",
            "next_revisit_time" => 0,
            "last_revisit_msg" => "",
            "last_revisit_time" => 0,
            "tq_called_flag" => 0,
        ]);
        $this->t_test_lesson_subject-> clean_seller_info($userid);

        $phone=$this->get_phone($userid);

        $ret_update = $this->t_book_revisit->add_book_revisit(
            $phone,
            "操作者: $account 状态: 回流 新例子库 ",
            "system"
        );
    }

    public function tongji_invalid_count($start_time,$end_time, $origin_ex)  {
        $where_arr=[
            "seller_student_status=1" ,
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        $sql = $this->gen_sql_new(
            " select  seller_student_sub_status,  count(*) as count "
            ." from  %s s "
            ." join %s n  on  s.userid=n.userid "
            ." join  %s t on  s.userid=t.userid "
            . " where %s group by  seller_student_sub_status ",
            t_student_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list($sql);

    }

    public function get_user_info_for_api($userid){
        $sql = $this->gen_sql_new("select s.realname,s.nick stu_nick,s.birth,s.school,s.gender,i.xingetedian,s.reg_time,s.grade,s.phone,s.address,s.user_agent,s.lesson_count_left,s.lesson_count_all,s.praise,t.stu_test_paper,t.tea_download_paper_time,i.aihao ,i.yeyuanpai  ".
                                  " from %s n left join %s s on s.userid = n.userid".
                                  " left join %s t on s.userid = t.userid ".
                                  " left join %s i on n.userid = i.userid".
                                  " where s.userid = %u",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_init_info::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_row($sql);
    }

    public function get_test_lesson_require_info_for_api($userid,$test_lesson_subject_id){
        $sql = $this->gen_sql_new("select stu_test_lesson_level,stu_test_ipad_flag,stu_request_test_lesson_time,stu_request_test_lesson_demand,user_desc,current_require_id  ".
                                  "from %s n left join %s t on (n.userid = t.userid and t.require_admin_type=2)".
                                  " left join %s tr on t.current_require_id = tr.require_id".
                                  " where n.userid = %u and t.test_lesson_subject_id = %u",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  $userid,
                                  $test_lesson_subject_id
        );
        return $this->main_get_row($sql);
    }

    public function tongji_assign_count_list($start_time,$end_time){
        $where_arr=[
            ["admin_assign_time >= %u",$start_time,-1],
            ["admin_assign_time < %u",$end_time,-1]
        ];
        $sql=$this->gen_sql_new("select count(*) value,admin_revisiterid adminid from %s where %s group by admin_revisiterid order by value desc",
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_assign_count_list_all($start_time,$end_time){
        $where_arr=[
            ["admin_assign_time >= %u",$start_time,-1],
            ["admin_assign_time < %u",$end_time,-1]
        ];
        $sql=$this->gen_sql_new("select count(*) value,count(distinct admin_revisiterid) all_count from %s where %s ",
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_row($sql);

    }


    public function set_called_time($userid){
        $sql=$this->gen_sql_new("update %s set called_time=called_time+1  "
                                ." where userid=%u ",
                                self::DB_TABLE_NAME,
                                $userid );
        return $this->main_update($sql);
    }

    public function set_first_contact_time($userid){
        $sql=$this->gen_sql_new("update %s set first_contact_time=%d  "
                                    ." where userid=%u and first_contact_time=0 ",
                                    self::DB_TABLE_NAME,
                                    time(null),
                                    $userid );
        return $this->main_update($sql);
    }

    public function set_sys_invaild_flag($userid){
        $sql=$this->gen_sql_new("update %s set sys_invaild_flag=1  "
                                ." where userid=%u  ",
                                self::DB_TABLE_NAME,
                                $userid );
        return $this->main_update($sql);
    }

    public function reset_sys_invaild_flag($userid){
        $item_arr = $this->field_get_list($userid,"called_time,first_contact_time,add_time,competition_call_time, sys_invaild_flag,call_admin_count,phone,seller_resource_type");
        $invalid_flag=false;
        $add_time=$item_arr["add_time"];
        //连续3个人处理过了
        $deal_count=$item_arr["call_admin_count"];
        $invalid_count=$this->t_test_subject_free_list->get_set_invalid_count( $userid,$add_time);
        $invalid_str="";
        if ($deal_count >=5  &&  $item_arr["seller_resource_type"]==E\Eseller_resource_type::V_0 )  {
            $invalid_flag=true;
            $invalid_str=" $deal_count 人拨打,未接通";
        }
        if ($invalid_count >=3 ) {
            $invalid_flag=true;
            $invalid_str=" 被cc 设置无效资源: $invalid_count 次 ";
        }

        $db_sys_invaild_flag= ($item_arr["sys_invaild_flag"]==1);

        if ( $db_sys_invaild_flag!= $invalid_flag ) {
            if ($invalid_flag) {
                $this->set_sys_invaild_flag($userid);
                $this->task->t_book_revisit->add_book_revisit($item_arr["phone"],"系统:判定无效-". $invalid_str ,"system");
            }else{
                $this->field_update_list($userid,[
                    "sys_invaild_flag" => 0,
                    "competition_call_time" => $item_arr['competition_call_time']-3600,
                ]);
            }
        }
    }


    public function get_tmk_list( $start_time, $end_time, $seller_student_status, $page_num,$global_tq_called_flag,$grade, $subject ){

        $where_arr=[];
        $competition_call_time = time(NULL)   -3600*2;
        //$where_arr[] =  "f.adminid is null";
        $where_arr[] =  ['t.seller_student_status=%d', $seller_student_status,-1];
        $where_arr[] =  't.seller_student_status in (1,2,101,102)';
        $where_arr[] =  'n.tmk_student_status<>3 ';
        $where_arr[] =  " competition_call_time <  $competition_call_time ";
        //E\Eseller_student_status
        //if ( $seller_student_status ==2 ) {
        //$where_arr[] =  'n.call_admin_count>0 ';
        //}

        $this->where_arr_add_time_range($where_arr,"n.add_time",$start_time,$end_time);
        $this->where_arr_add_int_or_idlist($where_arr,"global_tq_called_flag",$global_tq_called_flag);
        $this->where_arr_add_int_or_idlist($where_arr,"s.grade",$grade);
        $this->where_arr_add_int_or_idlist($where_arr,"t.subject",$subject);

        $order_by_str= " order by n.add_time desc";

        $sql=$this->gen_sql_new(
            "select tmk_student_status, tmk_next_revisit_time, tmk_desc ,return_publish_count, tmk_adminid, t.test_lesson_subject_id ,seller_student_sub_status, n.add_time,  global_tq_called_flag, seller_student_status,  s.userid,s.nick, s.origin, s.origin_level,n.phone_location,n.phone,n.userid,n.sub_assign_adminid_2,n.admin_revisiterid, n.admin_assign_time, n.sub_assign_time_2 , s.origin_assistantid , s.origin_userid ,  t.subject, s.grade,n.user_desc, n.has_pad  ".
            " from %s t "
            ." left join %s n on  n.userid = t.userid "
            ." left join %s s on n.userid=s.userid "
            ." where  %s  $order_by_str "
            , t_test_lesson_subject::DB_TABLE_NAME
            , self::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_user_list_by_add_time($start_time,$end_time){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql= $this->gen_sql_new(
            "select userid from %s where %s ",
            self::DB_TABLE_NAME, $where_arr );
        return $this->main_get_list($sql);
    }
    public function admin_list($del_flag){
        $where_arr=[];
        $this->where_arr_add_int_field($where_arr,"del_flag",$del_flag);
        $sql = $this->gen_sql_new(
            "select admin_revisiterid as adminid ,  count(*) as count,del_flag, account"
            . " from %s n "
            ." left join %s m on m.uid= admin_revisiterid "
            ." where %s"
            ." group by  admin_revisiterid "
            ,self::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_as_page($sql);
    }
    public function clean_by_admin_revisiterid($admin_revisiterid) {
        $sql=$this->gen_sql(
            "update %s set admin_revisiterid=0 , admin_assign_time=0,  seller_resource_type=1  where admin_revisiterid=%u",
            self::DB_TABLE_NAME,
            $admin_revisiterid
        );
        return $this->main_update($sql);
    }

    public function get_origon_list($page_info,$start_time, $end_time, $opt_type_str, $origin_ex ,$origin_level ) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $ret_in_str  = $this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $this->where_arr_add_int_or_idlist($where_arr,"s.origin_level",$origin_level);
        $where_arr[] = $ret_in_str;

        switch ( $opt_type_str  ) {
        case "tq_call_fail_count" :
            $where_arr[]="global_tq_called_flag =1";
            break;
        case "tq_call_succ_invalid_count" :
            $where_arr[]="(global_tq_called_flag=2 and global_seller_student_status =1 )";
            break;
        case "valid_count" :
            $where_arr[]="(global_tq_called_flag=2 and global_seller_student_status <> 1 )";
            break;
        case "tmk_valid_count" :
            $where_arr[]="(tmk_student_status =3 )";
            break;
        case "tq_no_call_count" :
            $where_arr[]="( global_tq_called_flag=0 )";
            break;
        }

        $sql = $this->gen_sql_new("select  n.add_time, s.origin, n.phone ,n.userid  ".
                                  " from %s n ".
                                  " left join %s s on s.userid = n.userid".
                                  " where %s ",
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function del_user( $userid ) {
        $this->t_seller_student_new->row_delete($userid);
    }


    public function get_origon_list_bd2($page_info,$start_time, $end_time, $opt_type_str, $origin_ex  ) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]=$ret_in_str;

        switch ( $opt_type_str  ) {
        case "tq_call_fail_count" :
            $where_arr[]="global_tq_called_flag =1";
            break;
        case "tq_call_succ_invalid_count" :
            $where_arr[]="(global_tq_called_flag=2 and n.origin_vaild_flag =2 )";
            break;
        case "valid_count" :
            $where_arr[]="(global_tq_called_flag=2 and n.origin_vaild_flag = 1 )";
            break;

        case "tq_no_call_count" :
            $where_arr[]="(global_tq_called_flag=0)";
            break;
        }


        $sql = $this->gen_sql_new("select  n.add_time, s.origin, n.phone ,n.userid  ".
                                  " from %s n "
                                  ."left join %s s on s.userid = n.userid".
                                  " where %s ",
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr );

        \App\Helper\Utils::logger("origon_list_bd2:$sql");

        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_end_class_stu_num($adminid){
        $start = time()-30*86400;
        $end = time();
        $where_arr=[
            "s.type=1",
            ["admin_revisiterid =%u",$adminid,-1]
        ];
        $this->where_arr_add_time_range($where_arr,"s.last_lesson_time",$start,$end);
        $sql  = $this->gen_sql_new("select count(*) from %s n"
                                   ." left join %s s on n.userid = s.userid"
                                   ." where %s",
                                   self::DB_TABLE_NAME,
                                   t_student_info::DB_TABLE_NAME,
                                   $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_first_call_time_list(  $start_time, $end_time,$origin_ex )  {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"n.add_time",$start_time,$end_time);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $sql  = $this->gen_sql_new("select add_time, first_call_time  from %s n"
                                   ." left join %s s on n.userid = s.userid"
                                   ." where %s",
                                   self::DB_TABLE_NAME,
                                   t_student_info::DB_TABLE_NAME,
                                   $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_next_revisit_time_list($start_time, $end_time ) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"next_revisit_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select admin_revisiterid, next_revisit_time, userid from %s"
            . " where %s and admin_revisiterid>0 ",
            self::DB_TABLE_NAME, $where_arr );
        return $this->main_get_list($sql);
    }

    public function get_tmk_assign_time_by_adminid($tmk_adminid, $start_time, $end_time){
        $sql = $this->gen_sql_new(" select tmk_assign_time, ss.userid,global_tq_called_flag, tmk_adminid, first_call_time from %s ss left join %s tl on ss.userid=tl.userid ".
                                  " where tmk_adminid = %d and global_tq_called_flag<>0 and require_admin_type=2 and  tmk_assign_time>=$start_time and tmk_assign_time<$end_time",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $tmk_adminid
        );


        return $this->main_get_list($sql);
    }
    //通过userid得到seller_student_new相应一条记录
    public function get_userid_row($userid){

        $sql = $this->gen_sql_new("select *  "
                                   ." from %s where userid=%u "
                                   ,self::DB_TABLE_NAME,  $userid);

        return $this->main_get_row($sql);

    }

    function get_call_info( $start_time, $end_time, $sys_invaild_flag  ) {
        $where_arr=[
            "lesson_count_all=0 "
        ];

        $this->where_arr_add_time_range($where_arr,"n.add_time",$start_time,$end_time);
        $this->where_arr_add_boolean_for_value($where_arr,"sys_invaild_flag",$sys_invaild_flag);

        $sql= $this->gen_sql_new(
            " select  call_phone_count as call_count ,count(*) as user_count  "
            . " from %s n "
            ." left join  %s s on s.userid=n.userid "
            ." where %s "
            . " group by call_phone_count order by call_phone_count ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        /*
        $sql= $this->gen_sql_new(
            "select call_count , count(*) user_count  from ".
            "  (  select count(*) as call_count  "
            . " from %s n "
            ." left join  %s s on s.userid=n.userid "
            ." left join  %s tc on n.phone=tc.phone "
            ." where %s"
            . " group by n.userid )  t group by call_count order by call_count",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_tq_call_info::DB_TABLE_NAME,
            $where_arr
        );
        */
        return $this->main_get_list_as_page($sql);
    }

    public function get_seller_yxyx(){
        $where_arr = [
            'n.admin_revisiterid >0',//assigned_count
            'tmk_student_status=3',//tmk_assigned_count
            'global_tq_called_flag=0',//tq_no_call_count
            'global_tq_called_flag <>0',//tq_called_count
            'global_tq_called_flag =1',//tq_call_fail_count
            'global_tq_called_flag =2 and  n.sys_invaild_flag=0',//tq_call_succ_valid_count
            'global_tq_called_flag =2 and  n.sys_invaild_flag =1',//tq_call_succ_invalid_count
            'global_tq_called_flag =1 and  n.sys_invaild_flag =1',//tq_call_fail_invalid_count
            't.seller_student_status =100 and  global_tq_called_flag =2',//have_intention_a_count
            't.seller_student_status =101 and  global_tq_called_flag =2',//have_intention_b_count
            't.seller_student_status =102 and  global_tq_called_flag =2',//have_intention_c_count
            '',//require_count
            '',//test_lesson_count
            '',//succ_test_lesson_count
        ];
        // $sql = "select s.userid,s.phone "
        $sql = "select a.phone "
            // $sql = "select origin as check_value ,count(*) all_count,sum(global_tq_called_flag <>0) tq_called_count,"
            // ."sum(global_tq_called_flag=0 and seller_student_status =0  ) no_call_count,"
            // ."sum(n.admin_revisiterid >0) assigned_count,sum( t.seller_student_status = 1) invalid_count,"
            // ."sum(t.seller_student_status =2) no_connected_count,"
            // ."sum(t.seller_student_status =100 and  global_tq_called_flag =2 ) have_intention_a_count,"
            // ."sum(t.seller_student_status =101 and  global_tq_called_flag =2) have_intention_b_count,"
            // ."sum(t.seller_student_status =102 and  global_tq_called_flag =2)  have_intention_c_count,"
            // ."sum( tmk_student_status=3 ) tmk_assigned_count , sum(global_tq_called_flag=0 ) tq_no_call_count,"
            // ."sum( global_tq_called_flag =1 ) tq_call_fail_count,"
            // ."sum( global_tq_called_flag =1 and  n.sys_invaild_flag =1 ) tq_call_fail_invalid_count ,"
            // ."sum( global_tq_called_flag =2 and  n.sys_invaild_flag =1 ) tq_call_succ_invalid_count  ,"
            // ."avg( if(   add_time<first_call_time , first_call_time-add_time,null) ) avg_first_time, "
            // ."sum( global_tq_called_flag =2 and  n.sys_invaild_flag=0  ) tq_call_succ_valid_count   "
             ."from db_weiyi.t_seller_student_new n on a.phone=n.phone "
             ."left join db_weiyi.t_student_info s on s.userid = n.userid "
             ."left join db_weiyi.t_test_lesson_subject t on t.userid= n.userid  "
             ."where require_admin_type=2 and a.type=1 "
             ." and s.origin in ('H5转介绍','优学优享','优学帮-0101','刘先生','张鑫龙')";
        return $this->main_get_list($sql);
    }

}
