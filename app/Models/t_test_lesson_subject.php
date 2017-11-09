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

    public function get_ass_kk_tongji_info($start_time,$end_time){
        $where_arr=[
            "t.ass_test_lesson_type =1",
            " l.teacherid >0",
            " l.userid >0",
            // "tr.origin not like '%%转介绍%%' "
        ];
        // $this->where_arr_add_time_range($where_arr,"tr.require_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select tr.cur_require_adminid,count(distinct l.userid,l.teacherid,l.subject) lesson_count "
                                  .",m.name"
                                  ." from %s tss  join %s tr on tss.require_id = tr.require_id"
                                  ." join %s t on t.test_lesson_subject_id =tr.test_lesson_subject_id"
                                  ."  join %s l on tss.lessonid = l.lessonid"
                                  ." join %s ll on (ll.teacherid = l.teacherid "
                                  ." and ll.userid = l.userid "
                                  ." and ll.subject = l.subject "
                                  ." and ll.lesson_start= "
                                  ." (select min(lesson_start) from %s where teacherid =l.teacherid and userid=l.userid and subject = l.subject and lesson_type in(0,3) and lesson_status =2 and confirm_flag in (0,1)) and ll.lesson_start>= %u and ll.lesson_start < %u) "

                                  ." left join %s m on m.uid = tr.cur_require_adminid"
                                  ." where %s group by tr.cur_require_adminid",
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
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
                                  ." (select min(lesson_start) from %s where teacherid =l.teacherid and userid=l.userid and subject = l.subject and lesson_type in (0,3) and lesson_status =2 and confirm_flag in (0,1)) and ll.lesson_start>= %u and ll.lesson_start < %u) "
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
}
