<?php
namespace App\Models;
use \App\Enums as E;

/**
 * @property t_student_info  $t_student_info
 * @property t_seller_student_new  $t_seller_student_new
 */

class t_test_lesson_subject extends \App\Models\Zgen\z_t_test_lesson_subject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_set_status_info( $test_lesson_subject_id) {
        $sql = $this->gen_sql_new("select  phone, subject,seller_student_status , t.userid,n.origin_vaild_flag "
                                  ." from %s t, %s n "
                                  ." where t.userid=n.userid and  test_lesson_subject_id=%u  ",
                                  self::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $test_lesson_subject_id);
        return $this->main_get_row($sql);
    }

    public function set_seller_student_status( $test_lesson_subject_id, $seller_student_status ,  $sys_operator ){
        $db_item           = $this->get_set_status_info($test_lesson_subject_id);
        $db_status         = $db_item["seller_student_status"];
        $origin_vaild_flag = $db_item['origin_vaild_flag'];
        $userid            = $db_item["userid"];
        $subject           = $db_item["subject"];
        if($db_status != $seller_student_status){
            $this->t_book_revisit->add_book_revisit(
                $db_item["phone"],
                sprintf(
                    "操作者: %s [科目:%s] 状态: %s=>%s",
                    $sys_operator,
                    E\Esubject::get_desc($subject),
                    E\Eseller_student_status::get_desc($db_status),
                    E\Eseller_student_status::get_desc($seller_student_status)
                ),
                "system"
            );

            $this->field_update_list($test_lesson_subject_id,[
                "seller_student_status" => $seller_student_status,
            ]);

            $this->t_seller_student_new->field_update_list($userid,[
                "global_seller_student_status" => $seller_student_status
            ]);
            //无效资源
            if ( $seller_student_status== E\Eseller_student_status::V_1 ) {
                $adminid= $this->task->t_manager_info->get_adminid_by_account($sys_operator);
                $this->task->t_test_subject_free_list->field_update_list_2(
                    $userid,$adminid,
                    [
                        "test_subject_free_type" =>   E\Etest_subject_free_type::V_3
                    ]);
            }

            if ($seller_student_status>=100) { // set 1
                if ($origin_vaild_flag !=1 ) {
                    $this->t_seller_student_new->field_update_list($userid,[
                        "origin_vaild_flag" => 1
                    ]);
                }
            } else if ($seller_student_status==1 && $origin_vaild_flag!=1 ) { // set 2
                $this->t_seller_student_new->field_update_list($userid,[
                    "origin_vaild_flag" => 2
                ]);

            }
        }
    }

    public function set_seller_require_adminid( $userid_list ,$adminid ){
        $in_str=$this->where_get_in_str("userid",$userid_list);

        $sql=$this->gen_sql_new(
            "update %s set  require_adminid=%d  "
            ."where %s and  require_admin_type=%u",
            self::DB_TABLE_NAME,
            $adminid,
            $in_str,
            E\Eaccount_role::V_2
        );
        return $this->main_update($sql);
    }
    public function get_test_lesson_subject_id_by_admin_subject( $require_adminid, $userid, $subject ) {
        $sql=$this->gen_sql_new("select test_lesson_subject_id from  %s "
                                ." where require_adminid = %d and userid=%u and subject =%u"
                                ,self::DB_TABLE_NAME
                                ,$require_adminid, $userid, $subject ) ;
        return $this->main_get_value($sql);
    }
    public function  check_subject( $userid, $subject) {

        $sql=$this->gen_sql_new("select  1 from  %s "
                                ." where userid=%u and userid <>0 and subject =%u"
                                ,self::DB_TABLE_NAME,
                                 $userid, $subject ) ;
        return $this->main_get_value($sql);

    }

    public function check_and_add_ass_subject( $require_adminid, $userid,$grade, $subject ,$ass_test_lesson_type )
    {
        $test_lesson_subject_id = $this->get_test_lesson_subject_id_by_admin_subject( $require_adminid, $userid, $subject);
        if (!$test_lesson_subject_id) {
            $grade=$this->t_student_info->get_grade($userid);
            $this->row_insert([
                "require_admin_type"    => E\Eaccount_role::V_1,
                "require_adminid"       => $require_adminid,
                "userid"                => $userid,
                "subject"               => $subject,
                "grade"                 => $grade,
                "ass_test_lesson_type" => $ass_test_lesson_type,
                "seller_student_status" => E\Eseller_student_status::V_200
            ]);
            $test_lesson_subject_id=$this->get_last_insertid();
        }

        return $test_lesson_subject_id;
    }

    public function set_no_connect_for_sync_tq( $userid) {
        $sql=$this->gen_sql_new("update %s set seller_student_status=2  "
                                ." where seller_student_status =0 and  userid=%u ",
                                self::DB_TABLE_NAME,
                                $userid );
        return $this->main_update($sql);
    }


    public function get_seller_new_user_count (
        $start_time,$end_time ,$grade_list , $origin_ex ="", $origin_level=-1 ,$tmk_student_status=-1,$wx_invaild_flag=-1
    ){
        $where_arr=[
            "t.require_adminid= n.admin_revisiterid ",
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"s.grade",$grade_list);
        $this->where_arr_add_int_or_idlist($where_arr,"s.origin_level",$origin_level);
        $this->where_arr_add_int_or_idlist($where_arr,"n.tmk_student_status",$tmk_student_status);

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        //wx
        $this->where_arr_add_int_field($where_arr,"wx_invaild_flag",$wx_invaild_flag);


        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql= $this->gen_sql_new(
            "select  first_seller_adminid  as  admin_revisiterid ,  count(*) as  new_user_count "
            ." from %s  t "
            ." join %s n on n.userid=t.userid "
            ." join %s s on s.userid=t.userid "
            ." where %s "
            ." group by first_seller_adminid "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list_as_page($sql,function($item){
            return $item["admin_revisiterid"];
        });
    }

    public function get_seller_test_lesson_count (
        $start_time,$end_time ,$grade_list , $origin_ex ="", $origin_level=-1 ,$tmk_student_status=-1,$wx_invaild_flag=-1
    ){
        $where_arr=[
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"s.grade",$grade_list);
        $this->where_arr_add_int_or_idlist($where_arr,"s.origin_level",$origin_level);
        $this->where_arr_add_int_or_idlist($where_arr,"n.tmk_student_status",$tmk_student_status);

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        //wx
        $this->where_arr_add_int_field($where_arr,"wx_invaild_flag",$wx_invaild_flag);

        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql= $this->gen_sql_new(
            "select tr.cur_require_adminid as adminid, sum(if( tss.success_flag in (0,1) , 1,0 ) ) as test_count"
            ." from %s t "
            ." join %s tr on t.test_lesson_subject_id=tr.test_lesson_subject_id "
            ." join %s tss on tss.require_id=tr.require_id "
            ." join %s s on s.userid=t.userid "
            ." join %s n on n.userid=t.userid "
            ." where %s "
            ." group by adminid "
            ,self::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,$where_arr
        );
        // return $sql;
        return $this->main_get_list_as_page($sql);
    }


    public function get_seller_count ($start_time,$end_time ,$grade_list , $origin_ex="" ) {
        $where_arr=[
            "t.require_adminid= n.admin_revisiterid ",
        ];
        $where_arr[]=$this->where_get_in_str_query("s.grade",$grade_list);
        $this->where_arr_add_time_range($where_arr,"admin_assign_time",$start_time,$end_time);

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        $sql= $this->gen_sql_new(
            "select admin_revisiterid, "
            ." count(*) as  all_count, "
            ." sum(seller_resource_type=0) as all_count_0 , "
            ." sum(seller_resource_type=1) as all_count_1 ,  "

            ." sum(tq_called_flag=0 )  as  no_call, "
            ." sum(global_tq_called_flag=0 )  as  global_tq_no_call, "
            ." sum(seller_resource_type=0 and tq_called_flag=0 ) as no_call_0, "
            ." sum(seller_resource_type=1 and  tq_called_flag=0 ) as no_call_1,  "

            ." sum(tq_called_flag>0 )  as call_count  , "
            ." sum( seller_student_status =1 )  as invalid_count, "
            ." sum(tq_called_flag=1 )  as no_connect "

            ." from %s  t "
            ." join %s n on n.userid=t.userid "
            ." join %s s on s.userid=t.userid "
            ." where %s "
            ."  group by admin_revisiterid  "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );

        // return $sql;
        return $this->main_get_list_as_page($sql,function($item){
            return $item["admin_revisiterid"];
        });

    }
    public function set_seller_student_status_290_at_time() {
        //10分钟
        $sql=$this->gen_sql_new(
            "update %s t, %s tr,%s l set seller_student_status=290 "
            ." where  t.current_require_id=tr.require_id and tr.current_lessonid = l.lessonid "
            ." and seller_student_status >200 and seller_student_status <290  and lesson_start >%u and lesson_start<%u  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            time(NULL)-60*12-40*60,
            time(NULL)-40*60
            );
        return $this->main_update($sql);
    }

    public function get_test_lesson_lost_user_list($page_num,$start_time,$end_time,$grade, $self_adminid  ,$phone,$fail=-1)
    {
        $where_arr=[
            ["s.grade=%u", $grade, -1 ],
            ["n.phone='%s'", $phone, "" ],
            "n.admin_revisiterid =0 ",
            "n.sys_invaild_flag=0",
            " lesson_count_all =0 ",
            "  seller_student_status  <>50 ",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        if($fail){
            $where_arr[] = 'n.test_lesson_count = 0';
        }

        $sql= $this->gen_sql_new(
            "select  s.userid,s.phone ,s.gender,t.subject, s.grade,last_revisit_admin_time, last_revisit_adminid, max(l.lesson_start) as lesson_start, nick   "
            ." from %s t  "
            ." join %s n  on t.userid=n.userid "
            ." join %s s  on t.userid=s.userid "
            ." left join %s l  on t.userid=l.userid "
            ." where "
            . " %s  "
            ." and admin_assign_time < %u "
            ." group by t.userid, t.subject  "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,$where_arr
            ,time(NULL)-60*86400
        );

        return $this->main_get_page_random($sql,2,true );
    }

    public function get_test_lesson_fail_list($page_num,$start_time,$end_time,$grade, $self_adminid  ,$phone)
    {
        $where_arr=[
            ["s.grade=%u", $grade, -1 ],
            ["n.phone='%s'", $phone, "" ],
            "n.admin_revisiterid =0 ",
            "n.sys_invaild_flag=0",
            " lesson_count_all =0 ",
            " seller_student_status  <>50 ",
            "n.test_lesson_count = 0",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);

        $sql= $this->gen_sql_new(
            "select  s.userid,s.phone ,s.gender,t.subject, s.grade,last_revisit_admin_time, last_revisit_adminid, max(l.lesson_start) as lesson_start, nick   "
            ." from %s t  "
            ." join %s n on t.userid=n.userid "
            ." join %s s on t.userid=s.userid "
            ." left join %s l on t.userid=l.userid "
            ." where %s "
            ." and admin_assign_time < %u "
            ." group by t.userid, t.subject  "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,$where_arr
            ,time(NULL)-60*86400
        );

        return $this->main_get_page_random($sql,5,true );
    }

    public function set_other_admin_init($userid,$adminid) {
        $sql=$this->gen_sql_new(
            "update %s   set   "
            ." require_adminid=%u "
            ." ,seller_student_status=0 "
            ." where userid=%u  and require_admin_type =2 "
            , self::DB_TABLE_NAME
            ,$adminid
            ,$userid);
        return $this->main_update($sql);
    }
    public function get_unallot_info  () {
        $end_time=time(NULL);
        $start_time = $end_time-86400*30*6;
        $where_arr  = [
            "s.lesson_count_all=0",
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $where_arr[]="sub_assign_adminid_2 = 0 ";
        $where_arr[]="seller_resource_type = 0 ";
        $where_arr[]="require_admin_type = 2 ";
        $where_arr[]="admin_revisiterid = 0 ";
        $where_arr[]="sys_invaild_flag = 0 ";

        $sql= $this->gen_sql_new(
            "select "
            ." sum(origin_assistantid>0 ) as zjs_unallot_count, "
            ." sum(tmk_adminid =0 and  origin_level not in (90,99 )  ) as all_unallot_count , "
            ." sum(tmk_adminid =0 and origin_level not in (90,99 ) and s.grade >=300 ) as all_unallot_count_hight_school , "
            ." sum(tmk_adminid =0 and  origin_level=99  ) as all_unallot_count_Y, "
            ." sum(tmk_adminid =0 and global_tq_called_flag =0 and   origin_level not in (0,90,99 ) and s.grade<300      ) as all_uncall_count , "
            ." sum(tmk_adminid =0 and global_tq_called_flag =0 and  origin_level =0  ) as by_hand_all_uncall_count , "
            ." sum(tmk_student_status=3  and  origin_level=90 ) as tmk_unallot_count  " //T类
            ." from %s t  "
            ." join %s s on  t.userid=s.userid  "
            ." join %s n  on t.userid=n.userid  "
            ." where  %s ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr);
        //E\Eorigin_level::V_90

        return $this->main_get_row($sql);
    }
    public function get_unallot_info_sub_assign_adminid_2  ( $sub_assign_adminid_2 ) {
        $end_time=time(NULL);
        $start_time=$end_time-86400*30*6;
        $where_arr=[
            "s.lesson_count_all=0",
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $where_arr[]=" admin_revisiterid = 0 ";
        $where_arr[]=" sub_assign_adminid_2 =  $sub_assign_adminid_2 ";
        $where_arr[]="seller_resource_type = 0 ";
        $where_arr[]="require_admin_type = 2 ";

        $sql= $this->gen_sql_new(
            "select  "
            ."sum(origin_assistantid>0  ) as zjs_unallot_count,"
            ." sum(tmk_adminid =0 )  as all_unallot_count,  "
            ." sum(tmk_adminid >0 and tmk_student_status =3 )  as tmk_unallot_count  "
            ." from %s t  "
            ." join %s s on  t.userid=s.userid  "
            ." join %s n  on t.userid=n.userid  "
            ." where  %s ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_row($sql);
    }

    public function get_require_and_return_back_count( $admin_revisiterid )
    {//
        $sql = $this->gen_sql(
            "select sum(seller_student_status=200 ) as require_count, sum(seller_student_status in( 110)) as return_back_count "
            ." from %s t "
            ." join %s tr on t.current_require_id =tr.require_id "
            ." where require_adminid=%u and  require_time >%u  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            $admin_revisiterid,
            // E\Eseller_student_status::V_200,
            time(NULL)-14*86400
        );
        return $this->main_get_row($sql);
    }

    public function get_count_by_userid( $userid ) {
        $sql=$this->gen_sql_new(
            "select count(*) from %s where userid=%u"
            ,self::DB_TABLE_NAME
            ,$userid);
        return $this->main_get_value($sql);
    }


    public function tongji_master_no_assign_count( $start_time,$end_time)
    {
        $where_arr=[
            'sub_assign_adminid_2>0' ,
        ];
        $this->where_arr_add_time_range($where_arr,"sub_assign_time_2",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select sub_assign_adminid_2 ,sum( admin_revisiterid =0) no_assign_count, sum( admin_revisiterid> 0) assign_count  "
            ." from %s t  "
            ." join %s n  on t.userid=n.userid where %s  group by sub_assign_adminid_2  order by no_assign_count desc ",
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function clean_seller_info($userid) {
        $sql=$this->gen_sql_new(
            "update %s "
            ." set require_adminid=0 , current_require_id=NULL, seller_student_status=0 "
            ." where userid=%u and require_admin_type=2 ",
            self::DB_TABLE_NAME, $userid
        );
        return $this->main_update($sql);
    }

    public function get_add_user_order_info($test_lesson_subject_id) {
        $sql= $this->gen_sql_new(
            "select t.userid, n.phone, s.grade, t.subject ,s.origin, s.nick,current_lessonid "
            ." from %s  t "
            ." join %s  n on t.userid=n.userid  "
            ." join %s  s on t.userid=s.userid  "
            ." left join %s  tr on t.current_require_id =tr.require_id "
            ." where t.test_lesson_subject_id=%u ",
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            $test_lesson_subject_id
        );
        return $this->main_get_row($sql);
    }

    public function get_list_by_phone($page_num, $phone) {

        $sql= $this->gen_sql_new(
            "select  test_lesson_subject_id, add_time, n.phone, n.userid, require_adminid, admin_revisiterid, nick, subject, seller_resource_type, sub_assign_adminid_2 "
            ."  from %s  t    "
            ." left join %s n on n.userid=t.userid "
            ." left join %s s on s.userid=t.userid "
            . " where n.phone='%s' and require_admin_type=2 "
            ,
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $phone
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_user_course_list($userid){
        $sql = $this->gen_sql_new("select subject,textbook from %s where userid =%u order by require_admin_type asc",
                                  self::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_subject_id($userid,$require_admin_type=2){
        $sql = $this->gen_sql_new("select test_lesson_subject_id from %s where userid =%u limit 1",
                                  self::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_value($sql);
    }




    public function update_subject_and_textbook($userid,$subject,$textbook){
        $sql = $this->gen_sql_new("update %s SET subject=%u,textbook='%s' where userid=%u and require_admin_type =2",
                                  self::DB_TABLE_NAME,
                                  $subject,
                                  $textbook,
                                  $userid
        );
        return $this->main_update($sql);
    }
    public function get_subject_grade_info_new($page_num){
        $sql = $this->gen_sql_new("select s.nick,n.stu_character_info,n.stu_score_info,t.stu_request_lesson_time_info,t.stu_request_test_lesson_time,t.stu_request_test_lesson_time_info,t.stu_test_lesson_level,s.editionid,t.stu_request_test_lesson_demand,t.stu_test_paper,t.tea_download_paper_time,t.require_adminid,t.subject,t.grade from %s t ".
                                  " left join %s n on t.userid = n.userid".
                                  " left join %s s on t.userid = s.userid where t.require_adminid>0 and t.userid>0 and t.current_require_id >0",
                                  self::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }
    public function get_seller_student_assign_info($page_num,$assign_type){
        $where_arr=[];
        if($assign_type ==1){
            $where_arr[]="sub_assign_time_2 >0" ;
            $order_by = " order by sub_assign_time_2 desc";
        }else if($assign_type ==0){
            $where_arr[]="(sub_assign_time_2 =0 or sub_assign_time_2 is null)" ;
            $order_by = " order by add_time desc";
        }
        $sql=$this->gen_sql_new("select t.test_lesson_subject_id,seller_student_sub_status, add_time, seller_student_status, s.userid,s.nick stu_nick, ss.phone,ss.userid,ss.sub_assign_adminid_2,ss.admin_revisiterid, if(admin_assign_time >0,admin_assign_time ,sub_assign_time_2) assign_time,  t.subject, s.grade, ss.has_pad  ".
                                " from %s t "
                                ." left join %s ss on  ss.userid = t.userid "
                                ." left join %s s on ss.userid=s.userid "
                                ." where  %s ",
                                self::DB_TABLE_NAME,
                                t_seller_student_new::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_id_by_userid_subject($userid,$subject){
        $sql=$this->gen_sql_new("select test_lesson_subject_id from %s where userid=%u and subject = %u",
                                self::DB_TABLE_NAME,
                                $userid,
                                $subject
        );
        return $this->main_get_value($sql);
    }

    public function get_ass_kk_tongji_info($start_time,$end_time,$adminid=-1){
        $where_arr=[
            "t.ass_test_lesson_type =1",
            " l.teacherid >0",
            " l.userid >0",
            ["tr.cur_require_adminid=%u",$adminid,-1]
            // "tr.origin not like '%%转介绍%%' "
        ];
        $this->where_arr_add_time_range($where_arr,"ll.lesson_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select tr.cur_require_adminid,count(distinct l.userid,l.teacherid,l.subject) lesson_count "
                                  .",m.name"
                                  ." from %s tss  join %s tr on tss.require_id = tr.require_id"
                                  ." join %s t on t.test_lesson_subject_id =tr.test_lesson_subject_id"
                                  ."  join %s l on tss.lessonid = l.lessonid"
                                  ." join %s ll on ll.teacherid = l.teacherid and ll.userid=l.userid and ll.lesson_subject =l.subject and ll.type=18"
                                  ." left join %s m on m.uid = tr.cur_require_adminid"
                                  ." where %s group by tr.cur_require_adminid",
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["cur_require_adminid"];
        });
    }

    public function get_ass_change_teacher_tongji_info($start_time,$end_time,$teacherid=-1,$adminid=-1){
        $where_arr=[
            "t.ass_test_lesson_type =2",
            " l.teacherid >0",
            " l.userid >0",
            "tr.origin not like '%%转介绍%%' ",
            ["lll.teacherid = %u",$teacherid,-1],
            ["tr.cur_require_adminid = %u",$adminid,-1]
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select tr.cur_require_adminid,l.teacherid,l.userid,l.subject,lll.teacherid old_teacherid,m.account,tt.realname,s.nick "
                                  ." from %s t  join %s tr on t.current_require_id =tr.require_id"
                                  ."  join %s l on tr.current_lessonid = l.lessonid"
                                  ."  join %s tss on tss.lessonid = tr.current_lessonid"
                                  ." join %s ll on (ll.teacherid = l.teacherid "
                                  ." and ll.userid = l.userid "
                                  ." and ll.subject = l.subject "
                                  ." and ll.lesson_start= "
                                  ." (select min(lesson_start) from %s where teacherid =l.teacherid and userid=l.userid and subject = l.subject and lesson_type in(0,3) and lesson_status =2 and confirm_flag in (0,1)) ) "
                                  ." join %s lll on (lll.teacherid <> l.teacherid and lll.userid= l.userid and lll.subject=l.subject and lll.lesson_type<>2 and lll.lesson_del_flag =0 and lll.confirm_flag <>2 and lll.lesson_start < l.lesson_start and lll.lesson_start = (select max(lesson_start) from %s where teacherid <> l.teacherid and userid= l.userid and subject=l.subject and lesson_type<>2 and lesson_del_flag =0 and confirm_flag <>2 and lesson_start < l.lesson_start))"
                                  ." join %s m on m.uid = tr.cur_require_adminid"
                                  ." join %s tt on lll.teacherid = tt.teacherid"
                                  ." join %s s on l.userid = s.userid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_ass_kk_tongji_all_info($start_time,$end_time,$adminid=-1){
        $where_arr=[
            ["tr.cur_require_adminid=%u",$adminid,-1],
            "t.ass_test_lesson_type =1",
            "tr.origin not like '%%转介绍%%' "
        ];
        $this->where_arr_add_time_range($where_arr,"tr.require_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select tr.cur_require_adminid,count(*) all_count,sum(test_lesson_order_fail_flag>0) fail_count "
                                  ." from %s tr  join %s t on t.test_lesson_subject_id =tr.test_lesson_subject_id"
                                  ." where %s group by tr.cur_require_adminid",
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["cur_require_adminid"];
        });
    }


    public function get_ass_kk_tongji_info_detail($start_time,$end_time,$adminid){
        $where_arr=[
            "t.ass_test_lesson_type =1",
            " l.teacherid >0",
            " l.userid >0",
            //"tr.origin not like '%%转介绍%%' ",
            ["tr.cur_require_adminid=%u",$adminid,-1]
        ];
        // $this->where_arr_add_time_range($where_arr,"tr.require_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select distinct l.userid,l.teacherid,l.subject,ll.lesson_start,"
                                  ."s.nick,tt.realname,s.assistantid ,a.nick ass_nick "
                                  ." from %s tss  join %s tr on tss.require_id =tr.require_id"
                                  ." join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ."  join %s l on tss.lessonid = l.lessonid"
                                  ." join %s ll on (ll.teacherid = l.teacherid "
                                  ." and ll.userid = l.userid "
                                  ." and ll.subject = l.subject "
                                  ." and ll.lesson_start= "
                                  ." (select min(lesson_start) from %s where teacherid =l.teacherid and userid=l.userid and subject = l.subject and lesson_type in (0,3) and lesson_status =2 and confirm_flag in (0,1) and lesson_del_flag=0) and ll.lesson_start>= %u and ll.lesson_start < %u) "
                                  ." left join %s s on t.userid= s.userid"
                                  ." left join %s tt on l.teacherid = tt.teacherid"
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." where %s order by ll.lesson_start desc",
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_ass_kk_tongji_info_detail_new($start_time,$end_time,$adminid){
        $where_arr=[
            "t.ass_test_lesson_type =1",
            " l.teacherid >0",
            " l.userid >0",
            ["tr.cur_require_adminid=%u",$adminid,-1],
            ["ll.lesson_time>=%u",$start_time,0],
            ["ll.lesson_time<=%u",$end_time,0],
        ];

        $sql = $this->gen_sql_new("select distinct l.userid,l.teacherid,l.subject,ll.lesson_time lesson_start,"
                                  ."s.nick,tt.realname,s.assistantid ,a.nick ass_nick "
                                  ." from %s tss  join %s tr on tss.require_id =tr.require_id"
                                  ." join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ."  join %s l on tss.lessonid = l.lessonid"
                                  ." join %s ll on ll.teacherid = l.teacherid and ll.userid=l.userid and ll.lesson_subject =l.subject and ll.type=18"
                                  ." left join %s s on t.userid= s.userid"
                                  ." left join %s tt on l.teacherid = tt.teacherid"
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." where %s order by ll.lesson_time desc",
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_except_contract_num($account_id){
        $where_arr=[
            ["require_adminid=%u",$account_id,-1],
            "seller_student_status in(300,301,302,303,304,305)",
            "s.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select count(*) from %s t left join %s s on t.userid=s.userid where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_request($lessonid){
        $sql = $this->gen_sql_new("select stu_request_test_lesson_demand,stu_test_paper,tea_download_paper_time "
                                  ." from %s tls"
                                  ." left join %s tr on tls.require_id=tr.require_id"
                                  ." left join %s tl on tr.test_lesson_subject_id=tl.test_lesson_subject_id"
                                  ." where lessonid=%u"
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_row($sql);
    }

    public function get_user_cc($userid){
        $where_arr = [
            ["userid=%u",$userid,0]
        ];
        $sql = $this->gen_sql_new("select require_adminid"
                                  ." from %s "
                                  ." where %s"
                                  ." order by test_lesson_subject_id asc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);

    }

    public function del_by_userid($userid ) {
        $sql=$this->gen_sql_new(
            "delete from %s where userid=%d",
            self::DB_TABLE_NAME,$userid   );
        return $this->main_update($sql);
    }

    public function put_pic_to_alibaba($lessonid,$alibaba_url_str){
        $sql = $this->gen_sql_new("update %s t,"
                                  ." %s tr, "
                                  ." %s tss "
                                  ." set t.stu_lesson_pic='%s'"
                                  ." where tss.lessonid=%d "
                                  ." and tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." and tr.require_id = tss.require_id ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $alibaba_url_str,
                                  $lessonid
        );
        return $this->main_update($sql);
    }

    public function save_pic_compress_url($lessonid,$alibaba_url_str){
        $sql = $this->gen_sql_new(" update %s t,".
                                  "  %s tr, ".
                                  "  %s tss ".
                                  " set t.stu_test_paper='%s'".
                                  " where tss.lessonid=%d "
                                  . " and tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  . " and tr.require_id = tss.require_id  ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $alibaba_url_str,
                                  $lessonid);

        \App\Helper\Utils::logger('chenggong4');

        return $this->main_update($sql);

    }

    public function get_from_lesson_info($lessonid){
        $where_arr = [
            ["lessonid=%u",$lessonid,0]
        ];
        $sql = $this->gen_sql_new("select stu_test_paper,stu_request_test_lesson_demand "
                                  ." from %s tl"
                                  ." left join %s tr on tl.test_lesson_subject_id =tr.test_lesson_subject_id"
                                  ." left join %s tls on tr.require_id=tls.require_id"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_from_lesson_info_new($lessonid){
        $where_arr = [
            ["lessonid=%u",$lessonid,0]
        ];
        $sql = $this->gen_sql_new("select stu_test_paper,test_stu_request_test_lesson_demand as stu_request_test_lesson_demand "
                                  ." from %s tl"
                                  ." left join %s tr on tl.test_lesson_subject_id =tr.test_lesson_subject_id"
                                  ." left join %s tls on tr.require_id=tls.require_id"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function set_seller_student_status_by_userid($userid,  $seller_student_status ) {
        $sql=$this->gen_sql_new(
            "update  %s "
            . "set seller_student_status=%u"
            . " where userid=%u",
            self::DB_TABLE_NAME,
            $seller_student_status,
            $userid
        );
        return $this->main_update($sql);
    }

    public function get_test_lesson_info($start_time,$end_time,$page_num){
        $where_arr = [
            " tl.lesson_type = 2",
            " tls.ass_test_lesson_type = 1",
            " tl.lesson_del_flag=0"
        ];

        $this->where_arr_add_time_range($where_arr,"tl.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select tl.userid,tls.subject, tls.grade, ts.nick, tt.teacherid,tt.nick as teacher_nick, ts.assistantid, tl.lesson_start, tl.lesson_end, tll.success_flag from %s tls ".
                                  " left join %s tlsr on tlsr.test_lesson_subject_id = tls.test_lesson_subject_id ".
                                  " left join %s tll on tll.require_id = tlsr.require_id ".
                                  " left join %s tl on tl.lessonid = tll.lessonid".
                                  " left join %s ts on tl.userid = ts.userid".
                                  " left join %s tt on tt.teacherid = tl.teacherid".
                                  " where %s order by tl.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_num,30,true);
    }

    public function get_test_lesson_info_by_referral($start_time,$end_time,$page_num){
        $where_arr = [
            " tl.lesson_type = 2",
            " ts.originid = 1",
            " tl.lesson_del_flag=0"
        ];

        $this->where_arr_add_time_range($where_arr,"tl.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select tl.userid,tls.subject, tls.grade, ts.nick, tt.teacherid,tt.nick as teacher_nick, ts.assistantid, tl.lesson_start, tl.lesson_end, tll.success_flag from %s tls ".
                                  " left join %s tlsr on tlsr.test_lesson_subject_id = tls.test_lesson_subject_id ".
                                  " left join %s tll on tll.require_id = tlsr.require_id ".
                                  " left join %s tl on tl.lessonid = tll.lessonid".
                                  " left join %s ts on tl.userid = ts.userid".
                                  " left join %s tt on tt.teacherid = tl.teacherid".
                                  " where %s order by tl.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_num,30,true);

    }
    public function seller_test_lesson_user_count ($require_adminid )  {
        $sql= $this->gen_sql_new(
            "select count(*) from %s where require_adminid=%u and seller_student_status in (  ) ",
            self::DB_TABLE_NAME,
            $require_adminid
        );

    }

    public function update_paper( $lessonid ,$stu_lesson_pic, $stu_test_paper ){

        $sql = $this->gen_sql_new(" update %s ts,".
                                  "  %s tr, ".
                                  "  %s tss ".
                                  " set ts.stu_lesson_pic='%s', ts.stu_test_paper=%s".
                                  " where tss.lessonid=%d "
                                  . " and tr.require_id = tss.require_id  "
                                  . " and tr.test_lesson_subject_id = ts.test_lesson_subject_id",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $stu_lesson_pic,
                                  $stu_test_paper,
                                  $lessonid);

        return $this->main_update($sql);

    }


    public function update_homework( $lessonid , $homework_pdf ){

        $sql = $this->gen_sql_new(" update %s ts,".
                                  "  %s tr, ".
                                  "  %s tss ".
                                  " set ts.homework_pdf='%s' ".
                                  " where tss.lessonid=%d "
                                  . " and tr.require_id = tss.require_id  "
                                  . " and ts.test_lesson_subject_id = tr.test_lesson_subject_id",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $homework_pdf,
                                  $lessonid);

        return $this->main_update($sql);

    }


    public function get_stu_lesson_pic_and_homework($lessonid){
        $sql = $this->gen_sql_new(" select ts.stu_lesson_pic, ts. from %s ts "
                                  ." left join %s tr on  tr.test_lesson_subject_id = ts.test_lesson_subject_id"
                                  ." left join %s tss on tss.require_id = tr.require_id  "
                                  ." where tss.lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list,
                                  $lessonid
        );

        return $this->main_get_row($sql);
    }
    public function get_test_lesson_count ($require_adminid) {
        $sql= $this->gen_sql_new(
            "select count(*) from %s t "
            ." left join %s tr on t.current_require_id = tr.require_id "
            ." left join %s l on tr.current_lessonid = l.lessonid "
            . " where require_adminid=%u and    ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $require_adminid
        );
        return $this->main_get_value($sql);
    }

    public function get_no_demand_list(){
        $sql = $this->gen_sql_new("select * from %s where stu_request_test_lesson_demand ='' and knowledge_point_location<>''",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }


    public function get_stu_request_test_lesson_time_by_adminid($adminid, $start_time, $end_time){
        $where_arr = [
            ['require_adminid=%u', $adminid, -1],
            ['stu_request_test_lesson_time>=%u', $start_time, -1],
            ['stu_request_test_lesson_time<=%u', $end_time, -1],
        ];
        $sql = $this->gen_sql_new("select stu_request_test_lesson_time from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function auto_allot_yxyx_userid($userid, $auto_allot_adminid){
        $sql = $this->gen_sql_new("update %s set require_adminid=$auto_allot_adminid where userid=$userid",
                                  self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function get_sign_count(
        $start_time, $end_time,$flag,$is_green_flag,$is_down,$user_agent,$phone_location,$grade,$subject
    ){
        $where_arr = [
            "s.is_test_user=0",
            ["tr.is_green_flag=%u", $is_green_flag, -1],
            ["tl.subject in (%s)", $subject, -1],
            ["tl.grade in (%s)", $grade, -1],
            ["l.lesson_start>=%u",$start_time,-1],
            ["l.lesson_start<%u",$end_time,-1],
        ];
        if($is_down == 0){
            $where_arr[] = "tl.tea_download_paper_time=0";
        } else if($is_down == 1) {
            $where_arr[] = "tl.tea_download_paper_time>0";
        }

        if($phone_location){
            $where_arr[] = ["ss.phone_location like '%s%%'", $this->ensql( $phone_location), ""];
        }

        if($flag == 1) {
            $group_by = 'tl.require_adminid';
            $where_arr[] = "ss.hand_get_adminid in (1,4)";//1拨打认领,4 tmk分配　
            $where_arr[] = ["ss.admin_assign_time>=%u",$start_time,-1];
            $where_arr[] = ["ss.admin_assign_time<%u",$end_time,-1];
        }else if ($flag == 2){
            $group_by = 'l.teacherid';
            $where_arr[] = ["ss.add_time>=%u",$start_time,-1];
            $where_arr[] = ["ss.add_time<%u",$end_time,-1];
        }else {
            $group_by = 'tr.origin';
            $where_arr[] = ["ss.add_time>=%u",$start_time,-1];
            $where_arr[] = ["ss.add_time<%u",$end_time,-1];
        }

        $sql = $this->gen_sql_new(
            "select count(ss.userid) as stu_count,l.teacherid,tl.require_adminid,tr.origin,t.nick,"
            ."count( distinct if(l.lesson_user_online_status=1 and l.lesson_del_flag=0,l.lessonid,0) )-1 as lesson_succ_count,"
            ."count( distinct if(o.orderid>0,o.userid,0) )-1 as order_count"
            ." from %s ss "
            ." left join %s tl on tl.userid=ss.userid"
            ." left join %s tr on tr.test_lesson_subject_id=tl.test_lesson_subject_id "
            ." left join %s tss on tss.require_id=tr.require_id"
            ." left join %s l on l.lessonid=tss.lessonid "
            ." left join %s o on o.from_test_lesson_id=l.lessonid and o.contract_type in (0,1,3) and o.contract_status>0"
            ." left join %s s on s.userid=tl.userid"
            ." left join %s t on t.teacherid=l.teacherid and t.is_test_user=0"
            ." where %s group by %s"
            ,t_seller_student_new::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_teacher_info::DB_TABLE_NAME
            ,$where_arr
            ,$group_by
        );

        return $this->main_get_list($sql);
    }

    public function get_all_list($start_time,$end_time,$limit){
        $where_arr = [
            's.lesson_count_all=0',
            'n.seller_resource_type=1',
            'n.admin_revisiterid=0',
            't.seller_student_status <> 50',
            'n.sys_invaild_flag=0',
            '(n.hand_free_count+n.auto_free_count)<5',
        ];
        $this->where_arr_add_time_range($where_arr,'n.add_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select t.test_lesson_subject_id,t.subject,"
            ."n.add_time,n.userid,n.phone,n.phone_location,n.has_pad,n.user_desc,n.seller_add_time,"
            ."n.last_revisit_time,n.free_time,n.free_adminid,"
            ."s.grade,s.origin,s.realname,s.nick,s.last_lesson_time,"
            ."l.lesson_start, tr.test_lesson_order_fail_flag "
            ."from %s t  "
            ."left join %s n on t.userid=n.userid "
            ."left join %s s on s.userid=n.userid "
            ."left join %s m on n.admin_revisiterid=m.uid   "
            ."left join %s l on l.lessonid=n.last_succ_test_lessonid  "
            ."left join %s tss on tss.lessonid=n.last_succ_test_lessonid  "
            ."left join %s tr on tr.require_id=tss.require_id  "
            ."where %s "
            ."order by n.last_revisit_time limit %s "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,$where_arr
            ,$limit
        );
        return $this->main_get_list($sql);
    }

    public function get_test_require_info($lessonid){
        $where_arr = [
            "l.lessonid=$lessonid"
        ];

        $sql = $this->gen_sql_new("  select ts.tea_identity, ts.subject_tag, l.lesson_del_flag, l.accept_status as status, if(test_stu_request_test_lesson_demand='',stu_request_test_lesson_demand,test_stu_request_test_lesson_demand) as  stu_request_test_lesson_demand, s.nick, s.gender, ts.grade, ts.subject, l.lesson_start, l.lesson_end from %s l "
                                  ." left join %s tls on tls.lessonid=l.lessonid "
                                  ." left join %s tr on tr.require_id=tls.require_id "
                                  ." left join %s ts on ts.test_lesson_subject_id=tr.test_lesson_subject_id"
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s"
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME//tr
                                  ,self::DB_TABLE_NAME //ts
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }
    //@desn:获取统计中相关渠道例子明细
    //@param:额外限制条件
    public function tongji_test_example_origin_info( $origin='',$field_name, $start_time,$end_time,$adminid_list=[],$tmk_adminid=-1,$origin_ex="",$check_value='', $page_info='',$cond=''){
        \App\Helper\Utils::logger("serverip $field_name ");
        switch ( $field_name ) {
        case "origin" :
            $field_name="si.origin";
            break;

        case "grade" :
            $field_name="li.grade";
            break;

        case "subject" :
            $field_name="li.subject";
            break;
        default:
            break;
        }

        $where_arr=[
            ["si.origin like '%%%s%%' ",$origin,''],
            ["$field_name='%s'",$check_value,""],
        ];
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"si.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"tls.require_adminid",$adminid_list);
        $this->where_arr_add_time_range($where_arr, 'ssn.add_time', $start_time, $end_time);
        $this->where_arr_add__2_setid_field($where_arr,"ssn.tmk_adminid",$tmk_adminid);
        if($cond == 'admin_revisiterid')//已分配销售
            $where_arr[] = 'ssn.admin_revisiterid > 0';
        elseif($cond == 'tmk')//TMK有效
            $this->where_arr_add_int_field($where_arr, 'ssn.tmk_student_status', 3);
        elseif($cond == 'tq_no_call')//未拨打
            $this->where_arr_add_int_field($where_arr, 'ssn.global_tq_called_flag', 0);
        elseif($cond == 'called')//已拨通
            $this->where_arr_add_int_field($where_arr, 'ssn.global_tq_called_flag', 2);
        elseif($cond == 'tq_called')//已拨打
            $where_arr[] = 'global_tq_called_flag <>0';
        elseif($cond=='tq_call_fail')//未接通
            $this->where_arr_add_int_field($where_arr, 'ssn.global_tq_called_flag', 1);
        elseif($cond=='tq_call_succ_vaild'){
            //已拨通-有效
            $this->where_arr_add_int_field($where_arr, 'ssn.global_tq_called_flag', 2);
            $this->where_arr_add_int_field($where_arr, 'ssn.sys_invaild_flag', 0);
        }elseif($cond=='tq_call_succ_invaild'){
            //已拨通-无效
            $this->where_arr_add_int_field($where_arr, 'ssn.global_tq_called_flag', 2);
            $this->where_arr_add_int_field($where_arr, 'ssn.sys_invaild_flag', 1);
        }elseif($cond=='tq_call_fail_invaild'){
            //未拨通-无效
            $this->where_arr_add_int_field($where_arr, 'ssn.global_tq_called_flag', 1);
            $this->where_arr_add_int_field($where_arr, 'ssn.sys_invaild_flag', 1);
        }


        $sql=$this->gen_sql_new(
            "select pa.nickname,seller_resource_type ,first_call_time,first_contact_time,".
            "first_revisit_time,last_revisit_time,tmk_assign_time,last_contact_time,".
            "competition_call_adminid, competition_call_time,sys_invaild_flag,wx_invaild_flag,".
            "return_publish_count, tmk_adminid, tls.test_lesson_subject_id ,seller_student_sub_status,".
            "add_time,  global_tq_called_flag, seller_student_status,wx_invaild_flag,".
            "si.userid,si.nick,si.origin,si.origin_level,ssn.phone_location,ssn.phone,ssn.userid,".
            "ssn.sub_assign_adminid_2,ssn.admin_revisiterid,ssn.admin_assign_time,ssn.sub_assign_time_2,".
            "si.origin_assistantid,si.origin_userid,tls.subject,si.grade,ssn.user_desc,".
            "ssn.has_pad,tls.require_adminid,tmk_student_status,first_tmk_set_valid_admind,".
            "first_tmk_set_valid_time,tmk_set_seller_adminid,first_tmk_set_seller_time,".
            "first_admin_master_adminid,first_admin_master_time,first_admin_revisiterid,".
            "first_admin_revisiterid_time,first_seller_status,cur_adminid_call_count call_count,".
            "ssn.auto_allot_adminid ".
            " from %s tls ".
            " left join %s ssn on  ssn.userid = tls.userid ".
            " left join %s si on ssn.userid=si.userid ".
            " left join %s mi on  ssn.admin_revisiterid =mi.uid ". " left join %s a on  a.userid =ssn.userid ". " left join %s pa on  pa.id =a.parentid ". " where  %s"
            , self::DB_TABLE_NAME
            , t_seller_student_new::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            , t_manager_info::DB_TABLE_NAME
            , t_agent::DB_TABLE_NAME
            , t_agent::DB_TABLE_NAME
            ,$where_arr
        );

        if ($page_info) {
            return $this->main_get_list_by_page($sql,$page_info);
        } else {
            return $this->main_get_list($sql);
        }
    }

    public function get_test_lesson_subject_id_by_lessonid($lessonid){
        $sql = $this->gen_sql_new("  select tls.require_id from %s tr "
                                  ." left join %s tls on tls.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s tll on tll.require_id=tls.require_id"
                                  ." where tll.lessonid=$lessonid"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_subject_only_once($userid){
        $sql = " select subject from db_weiyi.t_test_lesson_subject where subject > 0 and userid = $userid ";
        return $this->main_get_row($sql);
    }
    //@desn:获取节点型例子进入量
    public function get_example_num($start_time,$end_time){
        $where_arr = [
            ['tls.require_admin_type = %u',2],
            ['si.is_test_user = %u',0],
        ];
        $this->where_arr_add_time_range($where_arr, 'ssn.add_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select si.origin as channel_name,count(*) as all_count,'.
            'count(distinct si.userid) as heavy_count,count(ssn.admin_revisiterid >0) assigned_count,'.
            'count(ssn.tmk_student_status=3) as tmk_assigned_count,'.
            'avg(if(ssn.add_time<ssn.first_call_time,ssn.first_call_time-ssn.add_time,null)) avg_first_time,'.
            'sum(ssn.global_tq_called_flag <>0) tq_called_count,'.
            'sum(ssn.global_tq_called_flag=0) tq_no_call_count,'.
            'format(sum(ssn.global_tq_called_flag <>2)/count(*)*100,2) consumption_rate,'.
            'sum(ssn.global_tq_called_flag =2) as called_num,'.
            'sum(ssn.global_tq_called_flag =2 and ssn.sys_invaild_flag=0) tq_call_succ_valid_count,'.
            'sum(ssn.global_tq_called_flag =2 and  ssn.sys_invaild_flag =1) tq_call_succ_invalid_count,'.
            'format(sum(ssn.global_tq_called_flag =2)/sum(ssn.global_tq_called_flag <>0)*100,2) called_rate,'.
            'format(sum(ssn.global_tq_called_flag =2 and ssn.sys_invaild_flag =0)/sum(ssn.global_tq_called_flag <>0)*100,2) effect_rate,'.
            'sum(ssn.global_tq_called_flag=1) tq_call_fail_count,'.
            'sum(ssn.global_tq_called_flag =1 and ssn.sys_invaild_flag =1) tq_call_fail_invalid_count,'.
            'sum(tls.seller_student_status =100 and ssn.global_tq_called_flag =2) have_intention_a_count,'.
            'sum(tls.seller_student_status =101 and ssn.global_tq_called_flag =2) have_intention_b_count,'.
            'sum(tls.seller_student_status =102 and ssn.global_tq_called_flag =2) have_intention_c_count '.
            'from %s tls '.
            'left join %s ssn on tls.userid = ssn.userid '.
            'left join %s si on tls.userid = si.userid '.
            'where %s group by si.origin',
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['channel_name'];
        });
    }
    //@desn:获取节点型例子进入量
    public function get_example_num_now($field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        if($field_name == 'grade')
            $field_name="si.grade";
        $where_arr=[
            ["si.origin like '%%%s%%' ",$origin,""],
            'tls.require_admin_type=2',
            'si.is_test_user = 0'
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"ssn.tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->task->t_origin_key->get_in_str_key_list($origin_ex,"si.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"ssn.first_seller_adminid",$adminid_list);

        $sql = $this->gen_sql_new(
            'select '.$field_name.' as check_value,count(*) as all_count,'.
            'count(distinct si.userid) as heavy_count,count(ssn.admin_revisiterid >0) assigned_count,'.
            'count(ssn.tmk_student_status=3) as tmk_assigned_count,'.
            'avg(if(ssn.add_time<ssn.first_call_time,ssn.first_call_time-ssn.add_time,null)) avg_first_time,'.
            'sum(ssn.global_tq_called_flag <>0) tq_called_count,'.
            'sum(ssn.global_tq_called_flag=0) tq_no_call_count,'.
            'format(sum(ssn.global_tq_called_flag <>2)/count(*)*100,2) consumption_rate,'.
            'sum(ssn.global_tq_called_flag =2) as called_num,'.
            'sum(ssn.global_tq_called_flag =2 and ssn.sys_invaild_flag=0) tq_call_succ_valid_count,'.
            'sum(ssn.global_tq_called_flag =2 and  ssn.sys_invaild_flag =1) tq_call_succ_invalid_count,'.
            'format(sum(ssn.global_tq_called_flag =2)/sum(ssn.global_tq_called_flag <>0)*100,2) called_rate,'.
            'format(sum(ssn.global_tq_called_flag =2 and ssn.sys_invaild_flag =0)/sum(ssn.global_tq_called_flag <>0)*100,2) effect_rate,'.
            'sum(ssn.global_tq_called_flag=1) tq_call_fail_count,'.
            'sum(ssn.global_tq_called_flag =1 and ssn.sys_invaild_flag =1) tq_call_fail_invalid_count,'.
            'sum(tls.seller_student_status =100 and ssn.global_tq_called_flag =2) have_intention_a_count,'.
            'sum(tls.seller_student_status =101 and ssn.global_tq_called_flag =2) have_intention_b_count,'.
            'sum(tls.seller_student_status =102 and ssn.global_tq_called_flag =2) have_intention_c_count '.
            'from %s tls '.
            'left join %s ssn on tls.userid = ssn.userid '.
            'left join %s si on tls.userid = si.userid '.
            'where %s group by check_value',
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['check_value'];
        });
    }
    //@desn:获取周月报例子数据
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    public function get_example_info($start_time,$end_time){
        $where_arr=[
            'tls.require_admin_type=2',
            'si.is_test_user = 0'
        ];
        $this->where_arr_add_time_range($where_arr, 'ssn.add_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select count(ssn.userid) as example_num,sum(if(ssn.global_tq_called_flag <>0,1,0)) as called_num,'.
            'sum(if(ssn.global_tq_called_flag =2 and ssn.sys_invaild_flag=0,1,0)) as valid_example_num,'.
            'sum(if(ssn.sys_invaild_flag =1,1,0)) as invalid_example_num,'.
            'sum(if(ssn.global_tq_called_flag=1,1,0)) as not_through_num,'.
            'sum(if((si.grade >= 100 and si.grade <= 106),1,0)) as primary_num,'.
            'sum(if((si.grade >= 200 and si.grade <= 203),1,0)) as middle_num,'.
            'sum(if((si.grade >= 300 and si.grade <= 303),1,0)) as high_num '.
            'from %s tls '.
            'left join %s ssn on ssn.userid = tls.userid '.
            'left join %s si on ssn.userid=si.userid '.
            'where %s',
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    //@desn:获取所有例子量及通话时间小于60的数量
    public function get_example_call_result($start_time,$end_time){
        $where_arr = [
            'tls.require_admin_type=2',
            'si.is_test_user = 0'
        ];
        $this->where_arr_add_time_range($where_arr, 'ssn.add_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select ssn.userid,tci.duration,tci.end_reason,tci.is_called_phone,@status:=1 as status '.
            'from %s tls '.
            'left join %s ssn using(userid) '.
            'left join %s si using(userid) '.
            'left join %s tci on ssn.phone = tci.phone '.
            'where %s',
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_tq_call_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取今日头条10月份进入例子
    //@param:$start_time $end_time 开始时间  结束时间
    public function get_channel_info($start_time,$end_time){
        $where_arr = [
            's.is_test_user = 0',
            "ok.key1 = '今日头条' ",
            't.require_admin_type=2',
        ];
        $this->where_arr_add_time_range($where_arr, 'ss.add_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select ss.phone from %s t '.
            'left join %s ss on ss.userid = t.userid '.
            'left join %s s on ss.userid = s.userid '.
            "left join %s ok on ok.value = s.origin ".
            'where %s',
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_origin_key::DB_TABLE_NAME,
            $where_arr
        );
        dd($sql);
        return $this->main_get_list($sql);
    }
    //@desn:获取今日头条10月份进入例子拨打详情
    //@param:$start_time $end_time 开始时间  结束时间
    public function get_channel_call_info($start_time,$end_time){
        $where_arr = [
            's.is_test_user = 0',
            "ok.key1 = '今日头条' ",
            't.require_admin_type=2'
        ];
        $this->where_arr_add_time_range($where_arr, 'ss.add_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select ss.userid,ss.add_time,s.phone_province,s.phone_city,'.
            'sum(duration>60) con_count,sum(start_time) sum_time,'.
            'min(start_time) begin_time,max(start_time) end_time,count(tci.id) all_count '.
            'from %s t '.
            'left join %s ss on ss.userid = t.userid '.
            'left join %s s on ss.userid = s.userid '.
            'left join %s ok on ok.value = s.origin '.
            'left join %s tci on tci.phone = ss.phone '.
            'where %s group by tci.phone',
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_origin_key::DB_TABLE_NAME,
            t_tq_call_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_item_list(){
        $sql = "select t.test_lesson_subject_id,t.subject,n.add_time,n.userid,n.phone,n.phone_location,n.has_pad,n.user_desc,n.last_revisit_time,n.free_time,n.free_adminid,s.grade,s.origin,s.realname,s.nick,s.last_lesson_time,l.lesson_start, tr.test_lesson_order_fail_flag,n.return_publish_count,n.cc_no_called_count_new,n.cc_called_count,n.call_admin_count from db_weiyi.t_test_lesson_subject t  left join db_weiyi.t_seller_student_new n on t.userid=n.userid  left join db_weiyi.t_student_info s on s.userid=n.userid  left join db_weiyi_admin.t_manager_info m on n.admin_revisiterid=m.uid   left join db_weiyi.t_lesson_info l on l.lessonid=n.last_succ_test_lessonid  left join db_weiyi.t_test_lesson_subject_sub_list tss on tss.lessonid=n.last_succ_test_lessonid  left join db_weiyi.t_test_lesson_subject_require tr on tr.require_id=tss.require_id  where s.lesson_count_all=0 and n.seller_resource_type=1 and n.admin_revisiterid=0 and t.seller_student_status <> 50 and n.sys_invaild_flag=0 and (n.hand_free_count+n.auto_free_count)<5 and n.seller_add_time>=1519228800 and n.seller_add_time<1519315200 order by n.last_revisit_time";
        return $this->main_get_list($sql);
    }
}
