<?php
namespace App\Models;
use \App\Enums as E;

/**
 * @property t_test_lesson_subject  $t_test_lesson_subject
 * @property t_admin_group_name  $t_admin_group_name
 * @property t_origin_key  $t_origin_key
 * @property t_lesson_info  $t_lesson_info
 * @property t_test_lesson_subject_sub_list  $t_test_lesson_subject_sub_list
 */

class t_test_lesson_subject_require extends \App\Models\Zgen\z_t_test_lesson_subject_require
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_count_by_test_lesson_subject_id( $test_lesson_subject_id) {
        $sql=$this->gen_sql_new("select count(*) from %s where test_lesson_subject_id=%d ",
                                self::DB_TABLE_NAME,
                                $test_lesson_subject_id);
        return $this->main_get_value($sql);
    }
    public function get_test_lesson_subject_lesson_info( $test_lesson_subject_id ) {
        $sql=$this->gen_sql_new("select  tlr.accept_flag,  sl.success_flag, lesson_end  ,test_lesson_student_status from   %s ssn "
                                ." left join   %s tlr on ssn.current_require_id  = tlr.require_id  "
                                ." left join   %s sl on tlr.current_lessonid = sl.lessonid "
                                ." left join   %s l on sl.lessonid= l.lessonid "
                                ." where ssn.test_lesson_subject_id=%u ",
                                t_test_lesson_subject::DB_TABLE_NAME,
                                self::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                $test_lesson_subject_id,time(NULL)  );
        //处理
        return $this->main_get_row($sql);
    }
    public function check_is_end_by_test_lesson_subject_id( $test_lesson_subject_id)  {
        $row=$this->get_test_lesson_subject_lesson_info($test_lesson_subject_id );
        if (!$row) {
            return true;
        }

        $accept_flag=$row["accept_flag"] ;
        if ($accept_flag===null ) { //还没有请求
            return true;
        }
        if ($accept_flag ==0) {
            return false;
        }

        $success_flag=$row["success_flag"]*1;
        if ($accept_flag==2) { //驳回
            return true;
        }else if ($success_flag==2) { //课程已失败
            return true;
        }else if (in_array( $success_flag , [0,1]) &&  $row["lesson_end"] < time(NULL) ) {
            //课程已结束
            return true;
        }

        return false;
    }

    public function set_test_lesson_status($require_id, $seller_student_status, $sys_operator)
    {
        $test_lesson_subject_id = $this->get_test_lesson_subject_id($require_id);
        $this->field_update_list($require_id,[
            "test_lesson_student_status"=> $seller_student_status,
        ]);

        $this->t_test_lesson_subject->set_seller_student_status($test_lesson_subject_id,
                                                                $seller_student_status,
                                                                $sys_operator);

    }

    public function get_list_by_test_lesson_subject_id( $page_num,$test_lesson_subject_id,$userid,$subject=-1 )
    {
        $where_arr=[
            ["test_lesson_subject_id=%d",$test_lesson_subject_id,  -1 ]   ,
            ["l.userid=%d",$userid,  -1 ]   ,
            ["l.subject=%d",$subject,  -1 ]   ,
        ];
        $sql=$this->gen_sql_new(
            "select l.lessonid, r.origin, r.require_id,require_time, accept_flag,success_flag,no_accept_reason ,"
            ." test_lesson_fail_flag,fail_reason,teacherid,lesson_end,lesson_start,l.subject, sl.confirm_adminid, accept_adminid"
            ." from  %s r  "
            ."left join %s sl on r.current_lessonid = sl.lessonid  "
            ."left join %s l on sl.lessonid = l.lessonid  where  %s order by lesson_start asc ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_list_by_test_lesson_subject_id_new( $page_num,$test_lesson_subject_id,$userid )
    {
        $where_arr=[
            ["test_lesson_subject_id=%d",$test_lesson_subject_id,  -1 ]   ,
            ["l.userid=%d",$userid,  -1 ]   ,
        ];
        $sql=$this->gen_sql_new(
            "select l.lessonid, r.origin, r.require_id,require_time, accept_flag,success_flag,no_accept_reason , test_lesson_fail_flag, fail_reason, teacherid,lesson_end, lesson_start, l.subject, sl.confirm_adminid, accept_adminid  "
            ." from  %s r  "
            ."left join %s sl on r.current_lessonid = sl.lessonid  "
            ."left join %s l on sl.lessonid = l.lessonid  where  %s order by l.lesson_start desc ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }


    public function get_lesson_list_by_require_id( $page_num,$require_id)
    {
        $where_arr=[
            ["r.require_id=%d",$require_id,  -1 ]   ,
        ];
        $sql=$this->gen_sql_new(
            "select r.origin, r.require_id,require_time, set_lesson_time , accept_flag,success_flag, test_lesson_fail_flag, fail_reason, teacherid,lesson_end, lesson_start, l.subject "
            ." from  %s sl  "
            ."left join %s r on r.require_id = sl.require_id  "
            ."left join %s l on sl.lessonid = l.lessonid  where  %s ",
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_ass_require_info($start_time, $end_time ,$require_adminid_list ) {
        //E\Eaccount_role::V_1
        $where_arr=[
            "require_admin_type=1"
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str("require_adminid",$require_adminid_list);
        $sql=$this->gen_sql_new(
            "select  count(*) as all_count,  sum(assigned_lesson_count >0 ) as succ_count  ,sum(test_lesson_order_fail_flag>0) as fail_count  "
            ." from  %s tr  "
            ." join %s t  on tr.test_lesson_subject_id= t.test_lesson_subject_id  "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s c on  tss.lessonid = c.ass_from_test_lesson_id "
            ." where %s "
            ,
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_plan_list(
        $page_num, $opt_date_str, $start_time,$end_time ,$grade,
        $subject, $test_lesson_student_status ,$teacherid, $userid,$lessonid,
        $require_admin_type,$require_adminid,$ass_test_lesson_type,$test_lesson_fail_flag,$accept_flag,
        $success_flag,$is_test_user,$tmk_adminid,$require_adminid_list=[],$adminid_all=[],
        $seller_require_change_flag=-1,$require_assign_flag=-1,$has_1v1_lesson_flag=-1,$accept_adminid=-1,$is_jw=0,
        $jw_test_lesson_status=-1,$jw_teacher=-1,$tea_subject="",$is_ass_tran=0,$limit_require_flag=-1,
        $limit_require_send_adminid=-1,$require_id=-1,$lesson_plan_style=-1
    ){
        if($require_id>0){
            $where_arr=[
                ['tr.require_id=%d', $require_id,-1],
            ];
        }else if($userid>0){
            $where_arr=[
                ["ss.userid=%u",$userid, -1],
            ];
        }else{
            $where_arr=[
                ["t.subject=%u",$subject, -1],
                ["t.require_admin_type=%u",$require_admin_type, -1],
                ["tr.cur_require_adminid=%u",$require_adminid, -1],
                ['test_lesson_student_status=%d', $test_lesson_student_status,-1],
                ['t.ass_test_lesson_type=%d', $ass_test_lesson_type,-1],
                ['s.is_test_user=%d', $is_test_user,-1],
                ['l.teacherid=%d', $teacherid,-1],
                ['tr.jw_test_lesson_status=%d', $jw_test_lesson_status,-1],
                ['tr.seller_require_change_flag=%d', $seller_require_change_flag,-1],
                ['tr.accept_adminid=%d', $jw_teacher,-1],
                ['tr.limit_require_flag=%d', $limit_require_flag,-1],
                ['tr.limit_require_send_adminid=%d', $limit_require_send_adminid,-1],
            ];
            if($grade !=100 && $grade !=200 && $grade!=300){
                $where_arr[]= ['s.grade=%u',$grade,-1];
            }else if($grade==100){
                $where_arr[]="s.grade>=100 and s.grade <200";
            }else if($grade==200){
                $where_arr[]="s.grade>=200 and s.grade <300";
            }else if($grade==300){
                $where_arr[]="s.grade>=300";
            }

            $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
            $this->where_arr_adminid_in_list($where_arr,"tr.cur_require_adminid", $require_adminid_list );
            $this->where_arr_adminid_in_list($where_arr,"tr.cur_require_adminid", $adminid_all );
            $this->where_arr_add_set_boolean_flag($where_arr, "tr.accept_flag",$accept_flag   );
            $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
            if ($has_1v1_lesson_flag==1) {
                $where_arr[]="assigned_lesson_count>0";
            }else if ($has_1v1_lesson_flag==0)  { //没有
                $where_arr[]="test_lesson_order_fail_flag>0 ";
            }else if ($has_1v1_lesson_flag == -3) { //未设置
                $where_arr[]="(test_lesson_order_fail_flag=0 and  assigned_lesson_count is null  ) ";
            }

            if($tmk_adminid ==-2){
                $where_arr[]="ss.tmk_student_status =3";
            }
            if ($test_lesson_fail_flag == -2 ) {
                $where_arr[]="tss.test_lesson_fail_flag in (1,2,3)";
            }else{
                $where_arr[]=["tss.test_lesson_fail_flag =%u ",  $test_lesson_fail_flag,-1];
            }

            if ($success_flag==-2) {
                $where_arr[]="tss.success_flag in (0,1)";
            }else{
                $where_arr[]=["tss.success_flag=%u ",  $success_flag,-1];
            }
            if($require_assign_flag == 0){
                $where_arr[]="tr.accept_adminid <=0";
            }else if($require_assign_flag == 1){
                $where_arr[]="tr.accept_adminid >0";
            }
            if($is_jw ==1){
                $where_arr[]=["tr.accept_adminid =%u ",  $accept_adminid,-1];
            }

            if(!empty($tea_subject) && $accept_adminid !=343){
                $where_arr[]="t.subject in ".$tea_subject;
            }
            if($is_ass_tran==1){
                $where_arr[]="tr.origin not like '%%转介绍%%'";
            }else if($is_ass_tran==2){
                $where_arr[]="tr.origin like '%%转介绍%%'";
            }
            if($lesson_plan_style==1){
                $where_arr[]="tr.seller_top_flag=1";
            }elseif($lesson_plan_style==2){
                $where_arr[]="tr.seller_top_flag=0 and tr.is_green_flag=1";
            }elseif($lesson_plan_style==3){
                $where_arr[]="tr.seller_top_flag=0 and tr.is_green_flag=0";
            }elseif($lesson_plan_style==4){
                $where_arr[]="tss.grab_flag =1";
            }elseif($lesson_plan_style==5){
                $where_arr[]="t.rebut_flag=1 and tr.test_lesson_student_status in (200,210,220,290,300,301,302,420)";
            }
        }

        $sql = $this->gen_sql_new(
            "select l.accept_status, tr.change_teacher_reason, tr.change_teacher_reason_img_url, tr.change_teacher_reason_type, "
            ." test_lesson_order_fail_flag, test_lesson_order_fail_desc,  test_lesson_order_fail_set_time ,tmk_adminid, "
            ." tss.confirm_time,tss.confirm_adminid , l.lessonid, tr.accept_flag , t.require_admin_type, "
            ." s.origin_userid,s.is_test_user ,"
            ." t.ass_test_lesson_type, stu_score_info, stu_character_info , s.school, s.editionid, stu_test_lesson_level,"
            ." stu_test_ipad_flag, stu_request_lesson_time_info,  stu_request_test_lesson_time_info, tr.require_id,"
            ." t.test_lesson_subject_id ,ss.add_time, tr.test_lesson_student_status,  s.userid,s.nick, tr.origin, ss.phone_location,"
            ." ss.phone,ss.userid, t.require_adminid, "
            ." tr.curl_stu_request_test_lesson_time stu_request_test_lesson_time , "
            ." tr.curl_stu_request_test_lesson_time_end, "
            ." if(test_stu_request_test_lesson_demand='',stu_request_test_lesson_demand,"
            ." test_stu_request_test_lesson_demand) as stu_request_test_lesson_demand ,tr.intention_level, "
            ." s.gender,s.origin_assistantid,s.origin_userid,t.subject,tr.test_stu_grade as grade,ss.user_desc,"
            ." ss.has_pad, ss.last_revisit_time,"
            ." ss.last_revisit_msg,tq_called_flag,next_revisit_time,l.lesson_start,l.lesson_del_flag,tr.require_time,l.teacherid,"
            ." t.stu_test_paper, t.tea_download_paper_time,tss.success_flag,t.learning_situation,"
            ." tss.fail_greater_4_hour_flag, tss.test_lesson_fail_flag, tss.fail_reason,tr.seller_require_change_flag,"
            ." tr.require_change_lesson_time,tr.seller_require_change_time , assigned_lesson_count ,tr.accept_adminid,"
            ." jw_test_lesson_status,set_lesson_time,tr.green_channel_teacherid,tc.cancel_time,t.textbook,tr.cur_require_adminid,"
            ." tr.grab_status,tr.current_lessonid,tr.is_green_flag,tr.limit_require_flag,tr.limit_require_teacherid , "
            ." tr.limit_require_lesson_start ,tr.limit_require_time,tr.limit_require_adminid ,tr.limit_require_send_adminid,"
            ." tr.limit_accept_flag,tr.limit_require_reason,tr.limit_accept_time, tea.limit_plan_lesson_reason, "
            ." t.demand_urgency,t.quotation_reaction,t.knowledge_point_location,t.recent_results,t.advice_flag,"
            ." ss.class_rank,ss.grade_rank,ss.academic_goal,ss.test_stress,ss.entrance_school_type,ss.interest_cultivation,"
            ." ss.extra_improvement ,ss.habit_remodel ,ss.study_habit,ss.interests_and_hobbies,ss.character_type,"
            ." ss.need_teacher_style,ss.new_demand_flag,s.address,s.parent_name,tr.seller_top_flag,tss.grab_flag, "
            ." t.rebut_info,t.rebut_flag,pp.wx_openid p_wx_openid "
            ." from  %s tr "
            ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ." left join %s ss on  t.userid = ss.userid "
            ." left join %s s on  t.userid = s.userid "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." left join %s c on  tss.lessonid = c.ass_from_test_lesson_id "
            ." left join %s tc on tr.current_lessonid=tc.lessonid "
            ." left join %s tea on tea.teacherid=tr.limit_require_teacherid "
            ." left join %s pp on s.parentid = pp.parentid"
            ." where  %s order by %s asc "
            , t_test_lesson_subject_require::DB_TABLE_NAME//tr
            , t_test_lesson_subject::DB_TABLE_NAME//t
            , t_seller_student_new::DB_TABLE_NAME//ss
            , t_student_info::DB_TABLE_NAME//s
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME//tss
            , t_lesson_info::DB_TABLE_NAME//l
            , t_course_order::DB_TABLE_NAME//c
            , t_teacher_cancel_lesson_list::DB_TABLE_NAME//tc
            , t_teacher_info::DB_TABLE_NAME//tea
            , t_parent_info::DB_TABLE_NAME//pp
            ,$where_arr
            ,$opt_date_str
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    //试听课拉数据
    public function get_plan_list_new(){
        $sql =
            "select"
            ." tr.test_stu_grade grade,tr.cur_require_adminid,tr.require_time,"
            ." t.subject,"
            ." ss.phone"//
            ." from  db_weiyi.t_test_lesson_subject_require tr "
            ." left join db_weiyi.t_test_lesson_subject t on t.test_lesson_subject_id = tr.test_lesson_subject_id"
            ." left join db_weiyi.t_seller_student_new ss on t.userid = ss.userid"
            ." left join db_weiyi.t_student_info s on t.userid = s.userid"
            ." left join db_weiyi.t_test_lesson_subject_sub_list tss on  tr.current_lessonid = tss.lessonid"
            ." left join db_weiyi.t_lesson_info l on tss.lessonid = l.lessonid"
            // ." left join db_weiyi.t_course_order c on  tss.lessonid = c.ass_from_test_lesson_id"
            // ." left join db_weiyi.t_teacher_cancel_lesson_list tc on tr.current_lessonid=tc.lessonid"
            ." where"
            ." s.is_test_user=0"
            ." and tr.accept_flag<>2"
            ." and 1498838400<lesson_start"
            ." and lesson_start<=1501516800"
            // ." and 1498838400<lesson_end<=1498924800"
            ." order by lesson_start asc"
            ." limit 5000,5000 ";
        // dd($sql);
        return $this->main_get_list($sql);
    }





    public function get_expect_lesson_info_by_adminid($adminid, $timestamp){

        $week = intval(date('w', $timestamp));
        if ($week==0){ //周日
            $week=7;
        }

        $time_given = strtotime(date('Y-m-d', $timestamp) . " 00:00:00");

        $ret_week['start'] = $time_given - ($week-1)  * 24 * 60 * 60;
        $ret_week['end'] = $ret_week['start'] + 7 * 24 * 60 * 60;


        $where_arr = [
            ['ss.admin_revisiterid=%d',$adminid],
            "l.lesson_start is null"
        ];

        $this->where_arr_add_time_range($where_arr,"tr.require_time",$ret_week['start'],$ret_week['end']);

        $sql = $this->gen_sql_new(
            "  select  tr.require_time, t.stu_request_test_lesson_time,l.teacherid, l.lesson_start, l.lesson_end,l.lesson_count, t.userid "
            ." from  %s tr "
            ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ." left join %s ss on  t.userid = ss.userid "
            ." left join %s s on  t.userid = s.userid "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." left join %s c on  tss.lessonid = c.ass_from_test_lesson_id "
            ." left join %s tc on tr.current_lessonid=tc.lessonid "
            ." where  %s  "
            , t_test_lesson_subject_require::DB_TABLE_NAME
            , t_test_lesson_subject::DB_TABLE_NAME
            , t_seller_student_new::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME
            , t_lesson_info::DB_TABLE_NAME
            , t_course_order::DB_TABLE_NAME
            , t_teacher_cancel_lesson_list::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);

    }



    public function get_test_lesson_info($require_id)
    {
        $where_arr=[
            ["tr.require_id=%d", $require_id, -1]
        ];
        $sql=$this->gen_sql_new(
            "select  l.lessonid, stu_score_info, stu_character_info , s.school, s.editionid, stu_test_lesson_level,stu_test_ipad_flag, stu_request_lesson_time_info,  stu_request_test_lesson_time_info, tr.require_id, t.test_lesson_subject_id ,ss.add_time, test_lesson_student_status,  s.userid,s.nick, tr.origin, ss.phone_location,ss.phone,ss.userid, t.require_adminid, t.stu_request_test_lesson_time , t.stu_request_test_lesson_demand ,  s.origin_assistantid , s.origin_userid  ,  t.subject, s.grade,ss.user_desc, ss.has_pad, ss.last_revisit_time,ss.last_revisit_msg,tq_called_flag,next_revisit_time,l.lesson_start, tr.require_time, l.teacherid, t.stu_test_paper, t.tea_download_paper_time , test_lesson_student_status "
            ." from  %s tr"
            ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ." left join %s ss on  t.userid = ss.userid "
            ." left join %s s on  t.userid = s.userid "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." where  %s "
            , t_test_lesson_subject_require::DB_TABLE_NAME
            , t_test_lesson_subject::DB_TABLE_NAME
            , t_seller_student_new::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME
            , t_lesson_info::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_row($sql);

    }

    public function get_stu_performance_for_seller($require_id) {
        $sql=$this->gen_sql("select stu_lesson_content,stu_lesson_status,stu_study_status,stu_advantages,"
                            ." stu_disadvantages,stu_lesson_plan,stu_teaching_direction,stu_advice"
                            ." from %s "
                            ." where require_id=%u"
                            ,self::DB_TABLE_NAME
                            ,$require_id
        );
        return $this->main_get_row($sql);
    }

    public function get_stu_performance_for_seller_by_lessonid($lessonid){
        $sql = $this->gen_sql("select stu_lesson_content,stu_lesson_status,stu_study_status,stu_advantages,"
                              ." stu_disadvantages,stu_lesson_plan,stu_teaching_direction,stu_advice"
                              ." from %s t"
                              ." left join %s l on l.require_id = t.require_id"
                              ." where l.lessonid=%u"
                              ,self::DB_TABLE_NAME
                              ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                              ,$lessonid
        );
        return $this->main_get_row($sql);
    }

    public function get_plan_course_info($start_time,$end_time){
        $where_arr=[
            ["require_time >= %u",$start_time,-1],
            ["require_time <= %u",$end_time,-1],
            "accept_flag <> 2",
        ];
        $sql = $this->gen_sql_new("select require_time,accept_flag ".
                                  " from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr

        );
        return $this->main_get_list($sql);

    }
    public function add_require( $cur_require_adminid ,$sys_operator, $test_lesson_subject_id,$origin,$curl_stu_request_test_lesson_time,$test_stu_grade,$test_stu_request_test_lesson_demand,$change_reason_url='',$change_reason='',$change_teacher_reason_type=0,$curl_stu_request_test_lesson_time_end=0) {
        //检查没有其他处理中的请求
        \App\Helper\Utils::logger("add_require1");
        $is_has = $this->check_is_end_by_test_lesson_subject_id($test_lesson_subject_id);
        if (!$is_has){
            return  false;
        }

        \App\Helper\Utils::logger("add_require2");
        $seller_student_status= E\Eseller_student_status::V_200;
        $this->t_test_lesson_subject_require->row_insert([
            "test_lesson_subject_id" => $test_lesson_subject_id ,
            'origin' =>  $origin ,
            "cur_require_adminid" => $cur_require_adminid,
            "require_time"=>time(NULL),
            "curl_stu_request_test_lesson_time"=>$curl_stu_request_test_lesson_time,
            "curl_stu_request_test_lesson_time_end"=>$curl_stu_request_test_lesson_time_end,
            "test_stu_grade" => $test_stu_grade,
            "test_stu_request_test_lesson_demand" => $test_stu_request_test_lesson_demand,
            "change_teacher_reason_img_url" => $change_reason_url,
            "change_teacher_reason" => $change_reason,
            "change_teacher_reason_type" => $change_teacher_reason_type
        ]);
        $require_id= $this->t_test_lesson_subject_require->get_last_insertid();
        \App\Helper\Utils::logger("require_id:$require_id");

        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "current_require_id" => $require_id,
        ]);
        $this->t_test_lesson_subject_require->set_test_lesson_status($require_id,
                                                                     $seller_student_status,
                                                                     $sys_operator);

        return true;
    }

    public function add_require_and_lessonid( $cur_require_adminid ,$sys_operator, $test_lesson_subject_id,$origin,$seller_student_status) {
        //检查没有其他处理中的请求
        if (!$this->check_is_end_by_test_lesson_subject_id($test_lesson_subject_id)){
            return  false;
        }

        $this->t_test_lesson_subject_require->row_insert([
            "test_lesson_subject_id" => $test_lesson_subject_id ,
            'origin' =>  $origin ,
            "cur_require_adminid" => $cur_require_adminid,
            "require_time"=>time(NULL),
        ]);
        $require_id= $this->t_test_lesson_subject_require->get_last_insertid();
        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "current_require_id" => $require_id,
        ]);
        $this->t_test_lesson_subject_require->set_test_lesson_status($require_id,
                                                                     $seller_student_status,
                                                                     $sys_operator);
        $lessonid = $this->t_lesson_info->add_lesson(
            0,0,
            0,
            0,
            2,
            0,
            0,
            0,
            0,
            0,
            0,
            100,
            0,
            0
        );

        $this->t_test_lesson_subject_sub_list->row_insert([
            "lessonid"  => $lessonid,
            "require_id" => $require_id,
            "set_lesson_adminid"  => $cur_require_adminid,
            "set_lesson_time"  => time(NULL) ,

        ]);
        $this->t_test_lesson_subject_require->field_update_list($require_id,[
            "current_lessonid" => $lessonid,
        ]);


        return $lessonid;

    }

    public function tongji_require_test_lesson($start_time,$end_time,$adminid_list=[],$adminid_all=[] , $grade_list=[-1], $stu_test_paper_flag=-1 ) {
        $where_arr=[];
        $this->where_arr_adminid_in_list($where_arr,"tr.cur_require_adminid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"tr.cur_require_adminid",$adminid_all);

        $where_arr[]= $this->where_get_in_str_query("s.grade",$grade_list);
        $this->where_arr_add_boolean_for_str_value($where_arr,"stu_test_paper",$stu_test_paper_flag);

        $sql=$this->gen_sql_new(
            "select   from_unixtime( require_time , '%%Y-%%m-%%d') as opt_date, sum(require_admin_type =2 ) count  "
            ." from %s tr "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s s on s.userid = t.userid "
            ." where  %s and require_time >=%u and require_time<%u "
            ." and accept_flag=1   "
            ." and is_test_user=0   group by  from_unixtime( require_time , '%%Y-%%m-%%d')   ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr,$start_time,$end_time );

        return $this->main_get_list($sql);
    }

    public function tongji_require_test_lesson_group_by_admin_revisiterid($start_time,$end_time,$grade_list=[-1] , $origin_ex="" ) {
        $where_arr=[
            // "accept_flag=1",
            "require_admin_type=2",
            "is_test_user=0",
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str_query("s.grade",$grade_list);

        $sql=$this->gen_sql_new(
            "select cur_require_adminid as  admin_revisiterid  ,count(*)  as require_test_count "
            ." from %s tr "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s n  on t.userid=n.userid"
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s s on t.userid=s.userid"
            ." where   %s "
            ." group by  cur_require_adminid "
            ,
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list_as_page($sql);

    }

    public function tongji_require_test_lesson_group_by_admin_revisiterid_new($start_time,$end_time,$grade_list=[-1] , $origin_ex="" ) {
        $where_arr=[
            "require_admin_type=2",
            "is_test_user=0",
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str_query("s.grade",$grade_list);

        $sql=$this->gen_sql_new(
            "select cur_require_adminid as  admin_revisiterid  ,count(*)  as require_test_count "
            ." from %s tr "
            ." left join %s t on t.test_lesson_subject_id=tr.test_lesson_subject_id "
            ." left join %s s on s.userid=t.userid"
            ." where %s "
            ." group by cur_require_adminid ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list_as_page($sql);
    }

    public function tongji_require_test_lesson_list($start_time,$end_time,$admin_list,$order_str){
        $where_arr=[
        ];
        $where_arr[]=$this->where_get_in_str("u.adminid",$admin_list);

        $sql=$this->gen_sql_new(
            "select  u.adminid as admin_revisiterid ,sum(tr.require_time>0 and tr.accept_flag in(0,1) )  as require_test_count ,g.group_name,m.account "
            ." from %s u "
            ." left join %s g on u.groupid=g.groupid "
            ." left join %s m on u.adminid=m.uid "
            ." left join %s tr on( tr.cur_require_adminid=u.adminid  and tr.require_time >=$start_time and tr.require_time<$end_time  )   "
            ." where %s "
            ." group by  u.adminid order by require_test_count $order_str limit 10"
            ,
            t_admin_group_user::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function tongji_require_test_lesson_list_asc($start_time,$end_time){
        $day = date('Y-m-d',$start_time);
        $month = date('Y-m-01',$start_time);
        $no_attend_adminid_list = $this->t_admin_group_name->get_seller_no_attend_list($day,$month);

        $where_arr =[
            "g.groupid not in (9,10,12)",
            "t.require_admin_type=2",
            "g.main_type =2 ",
        ];
        if(!empty($no_attend_adminid_list)){
            $where_arr[] ="u.adminid not in".$no_attend_adminid_list;
        }


        $sql=$this->gen_sql_new(
            "select   cur_require_adminid as admin_revisiterid,count(require_time)  as require_test_count ,g.group_name,m.account"
            ." from %s u "
            ." left join %s g on u.groupid=g.groupid "
            ." left join %s n on u.adminid=n.admin_revisiterid "
            ." left join %s t  on t.userid=n.userid"
            ." left join %s tr  on (tr.test_lesson_subject_id=t.test_lesson_subject_id and tr.require_time >=%u and tr.require_time<%u)"
            ." left join %s tss on tr.current_lessonid=tss.lessonid "
            ." left join %s m on u.adminid=m.uid "
            ." group by  cur_require_adminid order by require_test_count asc limit 10"
            ,
            t_admin_group_user::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $start_time,$end_time,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function tongji_test_lesson_origin( $origin='', $field_name, $start_time,$end_time,$adminid_list=[],$tmk_adminid=-1,$origin_ex="" ,$distinct = 0){
        switch ( $field_name ) {
        case "origin" :
            $field_name="s.origin";
            break;

        case "grade" :
            $field_name="l.grade";
            break;

        case "subject" :
            $field_name="l.subject";
            break;
        default:
            break;
        }

        $where_arr=[
            ["s.origin like '%%%s%%' ",$origin,''],
        ];
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        //E\Etest_lesson_fail_flag
        if ($distinct == 0) {
            $sql=$this->gen_sql_new(
                "select $field_name  as check_value , count(*) as test_lesson_count, "
                ." count( distinct t.userid ) as distinct_test_count, "
                ." sum(  success_flag in (0,1 ) ) as succ_test_lesson_count  "
                ." from %s tr "
                ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
                ." join %s n  on t.userid=n.userid "
                ." join %s tss on tr.current_lessonid=tss.lessonid "
                ." join %s l on tr.current_lessonid=l.lessonid "
                ." join %s s on s.userid = l.userid "
                ." where %s and lesson_start >=%u and lesson_start<%u and accept_flag=1  "
                ." and is_test_user=0 "
                ." and require_admin_type = 2 and l.lesson_type=2  "
                ." group by check_value " ,
                self::DB_TABLE_NAME,
                t_test_lesson_subject::DB_TABLE_NAME,
                t_seller_student_new::DB_TABLE_NAME,
                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                t_lesson_info::DB_TABLE_NAME,
                t_student_info::DB_TABLE_NAME,
                $where_arr,$start_time,$end_time );
        } else {
            $sql=$this->gen_sql_new(
                "select $field_name  as check_value , count( distinct t.userid ) as distinct_succ_count "
                ." from %s tr "
                ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
                ." join %s n  on t.userid=n.userid "
                ." join %s tss on tr.current_lessonid=tss.lessonid "
                ." join %s l on tr.current_lessonid=l.lessonid "
                ." join %s s on s.userid = l.userid "
                ." where %s and lesson_start >=%u and lesson_start<%u and accept_flag=1  "
                ." and is_test_user=0 "
                ." and require_admin_type = 2  and l.lesson_type=2 "
                ." and success_flag in (0,1) "
                ." group by check_value " ,
                self::DB_TABLE_NAME,
                t_test_lesson_subject::DB_TABLE_NAME,
                t_seller_student_new::DB_TABLE_NAME,
                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                t_lesson_info::DB_TABLE_NAME,
                t_student_info::DB_TABLE_NAME,
                $where_arr,$start_time,$end_time );
        }

        return $this->main_get_list($sql);
    }
    //@desn:计算试听课相关明细
    //@param:$cond 检索条件
    //@param:$opt_date_str 检索时间字段
    public function tongji_test_lesson_origin_info( $origin='',$field_name, $start_time,$end_time,$adminid_list=[],$tmk_adminid=-1,$origin_ex="",$check_value='', $page_info='',$cond='',$opt_date_str=''){
        switch ( $field_name ) {
        case "origin" :
            $field_name="s.origin";
            break;

        case "grade" :
            $field_name="l.grade";
            break;

        case "subject" :
            $field_name="l.subject";
            break;
        default:
            break;
        }

        $where_arr=[
            ["s.origin like '%%%s%%' ",$origin,''],
            ["$field_name='%s'",$check_value,""],
        ];

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        if($cond ='test_lesson_succ')
            $where_arr[]='tss.success_flag in (0,1 )';
        //E\Etest_lesson_fail_flag
        $sql=$this->gen_sql_new(
            "select $field_name  as check_value , t.seller_student_status,l.lesson_start, s.userid,"
            ." s.phone_location, s.phone, t.grade,t.subject, s.nick, tss.success_flag,"
            ." tea.nick as tea_nick,l.lesson_user_online_status,n.has_pad,s.origin_level "
            ." from %s tr "
            ." left join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." left join %s n  on t.userid=n.userid "
            ." left join %s tss on tr.current_lessonid=tss.lessonid "
            ." left join %s l on tr.current_lessonid=l.lessonid "
            ." left join %s s on s.userid = t.userid "
            ." left join %s tea on tea.teacherid=l.teacherid "
            ." where %s "
            ." and l.lesson_type=2"
            ." and accept_flag=1  "
            ." and s.is_test_user=0 "
            ." and require_admin_type = 2 ",
            // ." group by check_value " ,
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_teacher_info::DB_TABLE_NAME,
            $where_arr
        );

        if ($page_info) {
            return $this->main_get_list_by_page($sql,$page_info);
        } else {
            return $this->main_get_list($sql);
        }
    }


    public function tongji_test_lesson_origin_new(){
        $where_arr = [
            ' accept_flag = 1 ',
            ' is_test_user=0 ',
            ' require_admin_type =2 ',
            " s.origin = '优学优享' ",
        ];
        $sql=$this->gen_sql_new(
            " select count(*) require_count "
            ." from %s tr"
            ." join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
            ." join %s s on t.userid= s.userid"
            ." join %s n on t.userid= n.userid   "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_row($sql);
    }


    public function tongji_test_lesson($start_time,$end_time,$adminid_list=[],$adminid_all=[],$grade_list=[-1], $stu_test_paper_flag =-1  ) {
        $where_arr=[];
        $this->where_arr_adminid_in_list($where_arr,"tr.cur_require_adminid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"tr.cur_require_adminid",$adminid_all);
        $where_arr[]= $this->where_get_in_str_query("s.grade",$grade_list);
        $this->where_arr_add_boolean_for_str_value($where_arr,"stu_test_paper",$stu_test_paper_flag);
        //E\Etest_lesson_fail_flag
        $sql=$this->gen_sql_new(
            "select  lesson_login_status,lesson_user_online_status ,  lesson_start as opt_time, success_flag, test_lesson_fail_flag , require_admin_type,ti.teacher_money_type "
            ." from %s tr "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s s on s.userid = l.userid "
            ." join %s ti on l.teacherid = ti.teacherid"
            ." where %s "
            ." and lesson_start >=%u "
            ." and lesson_start<%u "
            ." and l.lesson_type=2"
            ." and accept_flag=1 "
            ." and s.is_test_user=0"
            ,self::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_teacher_info::DB_TABLE_NAME
            ,$where_arr
            ,$start_time
            ,$end_time
        );

        return $this->main_get_list($sql);
    }



    public function tongji_fail_lesson_list( $page_info,  $cur_require_adminid, $start_time,$end_time ) {
        $where_arr=[
            "accept_flag=1",
            "require_admin_type=2",
            "is_test_user=0",
            "lesson_user_online_status =2",
            "(f.flow_status is null  or f.flow_status <>2 ) ",
            ["cur_require_adminid=%d", $cur_require_adminid, -1 ],
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select  cur_require_adminid  "
            .", l.lessonid,l.lesson_end, l.lesson_start, l.userid, l.teacherid, f.flow_status   "
            ." from %s tr "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s s  on l.userid=s.userid"
            ." left join %s f  on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
            ." where %s "
            ,
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list_by_page($sql,$page_info);
    }


    public function tongji_test_lesson_group_by_admin_revisiterid($start_time,$end_time,$grade_list=[-1] , $origin_ex="",$adminid=-1) {
        $where_arr=[
            "accept_flag=1",
            "require_admin_type=2",
            "is_test_user=0",
            "lesson_del_flag=0",
            ["t.require_adminid = %u",$adminid,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        // $this->where_arr_add_time_range($where_arr,"set_lesson_time",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str_query("s.grade",$grade_list);

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        $sql=$this->gen_sql_new(
            "select  cur_require_adminid as admin_revisiterid, count(*) as test_lesson_count,   sum( test_lesson_fail_flag in (1,2,3) ) as fail_need_pay_count  "
            .", sum( lesson_user_online_status =2 and  (f.flow_status is null  or f.flow_status <>2 ) ) fail_all_count "
            .", sum( lesson_user_online_status in (0,1) or  f.flow_status = 2  ) succ_all_count "
            .",sum(green_channel_teacherid>0) green_lesson_count"
            .", sum(success_flag in (0,1) and green_channel_teacherid>0) succ_green_count "
            ." from %s tr "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s s  on l.userid=s.userid"
            ." left join %s f  on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
            ." where %s "
            ." group by  cur_require_adminid "
            ,
            self::DB_TABLE_NAME,//tr
            t_lesson_info::DB_TABLE_NAME,//l
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,//tss
            t_test_lesson_subject::DB_TABLE_NAME,//t
            t_student_info::DB_TABLE_NAME,//s
            t_flow::DB_TABLE_NAME,//f
            $where_arr);

        return $this->main_get_list_as_page($sql);
    }

    public function tongji_test_lesson_group_by_admin_revisiterid_new_five($start_time,$end_time,$grade_list=[-1] , $origin_ex="",$adminid ) {
        $where_arr=[
            "accept_flag=1",
            "require_admin_type=2",
            "is_test_user=0",
            ['cur_require_adminid = %u',$adminid,-1],
        ];
        // $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $this->where_arr_add_time_range($where_arr,"set_lesson_time",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str_query("s.grade",$grade_list);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin",'');
        $where_arr[]= $ret_in_str;

        $sql=$this->gen_sql_new(
            "select  cur_require_adminid as admin_revisiterid, count(*) as test_lesson_count,   sum( test_lesson_fail_flag in (1,2,3) ) as fail_need_pay_count  "
            .", sum( lesson_user_online_status =2 and  (f.flow_status is null  or f.flow_status <>2 ) ) fail_all_count "
            .", sum( lesson_user_online_status in (0,1) or  f.flow_status = 2  ) succ_all_count "
            .",sum(green_channel_teacherid>0) green_lesson_count"
            .", sum(success_flag in (0,1) and green_channel_teacherid>0) succ_green_count "
            ." from %s tr "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s s  on l.userid=s.userid"
            ." left join %s f  on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
            ." where %s "
            ,
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list_as_page($sql);
    }

    public function tongji_test_lesson_group_by_admin_revisiterid_new_two($start_time,$end_time,$grade_list=[-1] , $origin_ex="" ) {
        $where_arr=[
            "require_admin_type=2",
            "is_test_user=0",
            "tss.success_flag < 2",
            "l.del_flag = 0",
        ];
        // $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $this->where_arr_add_time_range($where_arr,"set_lesson_time",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str_query("s.grade",$grade_list);

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        $sql=$this->gen_sql_new(
            "select  cur_require_adminid as admin_revisiterid, count(*) as test_lesson_count,   sum( test_lesson_fail_flag in (1,2,3) ) as fail_need_pay_count  "
            .", sum( lesson_user_online_status =2 and  (f.flow_status is null  or f.flow_status <>2 ) ) fail_all_count "
            .", sum( lesson_user_online_status in (0,1) or  f.flow_status = 2  ) succ_all_count "
            .",sum(green_channel_teacherid>0) green_lesson_count"
            .", sum(success_flag in (0,1) and green_channel_teacherid>0) succ_green_count "
            ." from %s tr "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s s  on l.userid=s.userid"
            ." left join %s f  on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
            ." where %s "
            ." group by  cur_require_adminid ",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list_as_page($sql);

    }

    public function tongji_test_lesson_group_by_admin_revisiterid_new($start_time,$end_time,$grade_list=[-1] , $origin_ex="",$adminid=-1,$adminid_list=[]) {
        $where_arr=[
            "accept_flag=1",
            // "require_admin_type=2",
            "is_test_user=0",
            "l.lesson_del_flag=0",
        ];
        if(count($adminid_list)>0){
            $this->where_arr_add_int_or_idlist($where_arr,"cur_require_adminid",$adminid_list);
        }else{
            $this->where_arr_add_int_field($where_arr,"cur_require_adminid",$adminid);
        }
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str_query("s.grade",$grade_list);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        $sql=$this->gen_sql_new(
            "select cur_require_adminid as admin_revisiterid, count(*) test_lesson_count,"
            ."sum(tss.success_flag IN (0,1) and lesson_user_online_status in (0,1) or f.flow_status = 2) succ_all_count,"
            ."sum(lesson_user_online_status =2 and (f.flow_status is null or f.flow_status <>2)) fail_all_count "
            ." from %s tr "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            // ." join %s t on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s s on l.userid=s.userid"
            ." left join %s f on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
            ." where %s "
            ." group by  cur_require_adminid ",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            // t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list_as_page($sql);
    }

    public function tongji_test_lesson_group_by_admin_revisiterid_new_three($start_time,$end_time,$grade_list=[-1] , $origin_ex="",$adminid=-1,$adminid_list=[]) {
        $where_arr=[
            "accept_flag=1",
            // "require_admin_type=2",
            "is_test_user=0",
            '(lesson_user_online_status in (0,1) or  f.flow_status = 2)',
            "l.lesson_del_flag=0",
        ];
        if(count($adminid_list)>0){
            $this->where_arr_add_int_or_idlist($where_arr,"cur_require_adminid",$adminid_list);
        }else{
            $this->where_arr_add_int_field($where_arr,"cur_require_adminid",$adminid);
        }
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str_query("s.grade",$grade_list);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        $sql=$this->gen_sql_new(
            "select cur_require_adminid as admin_revisiterid,l.lessonid,l.userid,"
            ." lesson_start "
            ." from %s tr "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            // ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s s  on l.userid=s.userid"
            ." left join %s f  on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
            ." where %s order by l.lesson_start ",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            // t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list_as_page($sql);
    }

    public function get_suc_test_lesson_by_adminid($start_time,$end_time,$adminid) {
        $where_arr=[
            "accept_flag=1",
            "require_admin_type=2",
            "is_test_user=0",
            ['cur_require_adminid=%u',$adminid,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select cur_require_adminid as admin_revisiterid,"
            .", sum( lesson_user_online_status in (0,1) or f.flow_status=2) succ_all_count "
            ." from %s tr "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s s  on l.userid=s.userid"
            ." left join %s f  on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
            ." where %s ",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_value($sql);
    }

    public function set_notify_lesson_flag($require_id,$notify_flag, $sys_operator) {
        $now=time(NULL);
        $notify_lesson_check_end_time=strtotime(date("Y-m-d",$now+86400*2 ) );
        //试听-已排课 :10
        $where_arr=[
            ["lesson_start>=%u", $now-3600 ,-1 ],
            ["lesson_start<%u",$notify_lesson_check_end_time,-1 ],
            ["require_id='%u'",$require_id, -1 ],
        ];
        $next_day=$notify_lesson_check_end_time-86400;

        $sql = $this->gen_sql_new("select  lesson_start, notify_lesson_day1,notify_lesson_day2  from %s t1 left join %s t2 on t1.current_lessonid=t2.lessonid where  %s  "
                                  ,
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr);

        $item=$this->main_get_row($sql);
        if($item) {
            $lesson_start=$item["lesson_start"];
            $notify_lesson_day1=$item["notify_lesson_day1"];
            $notify_lesson_day2=$item["notify_lesson_day2"];
            $update_field_name="";
            $noti_end_flag=false;
            if ( $lesson_start<$next_day  ) { // 今天的课
                $update_field_name="notify_lesson_day1";
                $noti_end_flag=true;
            }
            if ( $lesson_start>=$next_day   ) { // 明天的课
                $update_field_name="notify_lesson_day2";
            }
            $value=0;
            if ($notify_flag) {
                $value=time(NULL);
            }
            $this->field_update_list($require_id,[
                $update_field_name => $value,
            ]);
            if ($notify_flag) {
                if($noti_end_flag ) {  //end
                    $test_lesson_subject_id = $this->get_test_lesson_subject_id($require_id);
                    $old_status= $this->t_test_lesson_subject->get_seller_student_status($test_lesson_subject_id);
                    if ($old_status ==  E\Eseller_student_status::V_210 ) {
                        $this->t_test_lesson_subject->set_seller_student_status(
                            $test_lesson_subject_id, E\Eseller_student_status::V_220 ,
                            $sys_operator);
                    }
                }
            }

        }
    }

    public function test_lesson_list($page_num,$start_time,$end_time,$lesson_flag) {

        $where_arr=[
            "accept_flag=1",
        ];
        if ($lesson_flag ==1)  { //正常
            $where_arr[]="success_flag in (0,1)";
        }else if ($lesson_flag ==2)  { //老师不要工资
            $where_arr[]="success_flag =2 and test_lesson_fail_flag not in (1, 2, 3)  ";
        }else if ($lesson_flag ==3)  { //老师要工资
            $where_arr[]=" success_flag =2 and test_lesson_fail_flag in (1, 2, 3) ";
        }
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);

        $sql=$this->gen_sql_new("select  t.test_lesson_subject_id, notify_lesson_day1,notify_lesson_day2, t.seller_student_status, t.grade,t.subject, t.require_adminid, lesson_start,lesson_end, l.teacherid, t.userid,  l.lessonid , test_lesson_fail_flag,success_flag from %s  tr "
                                ."left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
                                ." join %s l on l.lessonid= tr.current_lessonid "
                                ." join %s tss on tss.lessonid= tr.current_lessonid "
                                ." where %s  "
                                ,
                                self::DB_TABLE_NAME,
                                t_test_lesson_subject::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                $where_arr  );
        $order_str="order by lesson_start asc";
        return $this->main_get_list_by_page($sql, $page_num,10,false,$order_str);

    }
    public function get_no_confirm_count( $admin_revisiterid ) {

        $now=time(NULL);


        $where_arr=[
            ["lesson_start>=%u", $now-86400*14, -1 ],
            ["lesson_start<%u",$now-2400,-1 ],
            ["require_adminid=%u",$admin_revisiterid,-1 ],
            "success_flag=0",
        ];

        $sql = $this->gen_sql_new("select   count(*) as no_confirm_count "
                                  ." from %s tr  "
                                  ." left join %s tss on tr.current_lessonid=tss.lessonid "
                                  ." left join %s l on tr.current_lessonid=l.lessonid "
                                  ." left join %s t on tr.test_lesson_subject_id=t.test_lesson_subject_id "
                                  ."where  %s  "
                                  ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr);

        return $this->main_get_value($sql);

    }

    public function get_notify_lesson_info( $admin_revisiterid ) {
        $now=time(NULL);
        $notify_lesson_check_end_time=strtotime(date("Y-m-d",$now+86400*2 ) );


        $where_arr=[
            ["lesson_start>=%u", $now-3600 ,-1 ],
            ["lesson_start<%u",$notify_lesson_check_end_time,-1 ],
            ["require_adminid=%u",$admin_revisiterid,-1 ],
            "success_flag=0",
        ];
        $next_day=$notify_lesson_check_end_time-86400;

        $sql = $this->gen_sql_new("select  lesson_start, notify_lesson_day1,notify_lesson_day2  "
                                  ." from %s tr  "
                                  ." left join %s tss on tr.current_lessonid=tss.lessonid "
                                  ." left join %s l on tr.current_lessonid=l.lessonid "
                                  ." left join %s t on tr.test_lesson_subject_id=t.test_lesson_subject_id "
                                  ."where  %s  "
                                  ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr);

        $list=$this->main_get_list($sql);
        $today=0;
        $tomorrow=0;
        foreach ($list as $item) {
            $lesson_start=$item["lesson_start"];
            $notify_lesson_day1=$item["notify_lesson_day1"];
            $notify_lesson_day2=$item["notify_lesson_day2"];
            if ( $lesson_start<$next_day && $notify_lesson_day1 ==0  ) { // 今天的课
                $today++;
            }
            if ( $lesson_start>=$next_day && $notify_lesson_day2 ==0  ) { // 明天的课
                $tomorrow++;
            }
        }
        return  ["today"=>$today,"tomorrow"=>$tomorrow ] ;
    }

    public function tongji_set_lesson_info($account_role,$start_time, $end_time ) {
        /*
          1 => "[付] 学生未到",
          100 => "[不付] 课程取消",
        */
        $where_arr=[
            ["require_admin_type = %d", $account_role, -1 ],
            "is_test_user=0",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);

        $stu_in_lesson_str="test_lesson_fail_flag not in ( 1, 100 )";
        $order_str="price>0";
        $sql=$this->gen_sql_new(
            "select  l.subject,"
            ." sum(l.grade <200 ) as l_1_count, "
            ." sum(l.grade <200 and $stu_in_lesson_str  ) as l_1_stu_in_count, "
            ." sum( l.grade >=200 and l.grade <300 ) as l_2_count,   "
            ." sum( l.grade >=200 and l.grade <300 and $stu_in_lesson_str  ) as l_2_stu_in_count,   "
            ." sum( l.grade >=300 and l.grade <400 ) as l_3_count,   "
            ." sum( l.grade >=300 and l.grade <400 and $stu_in_lesson_str  ) as l_3_stu_in_count ,  "
            ." count( * ) as l_all_count,   "
            ." sum(  $stu_in_lesson_str  ) as l_all_stu_in_count  "

            ."  from %s tr "
            ."  join  %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ."  join  %s l on l.lessonid= tr.current_lessonid "
            ."  join  %s ts on ts.lessonid= tr.current_lessonid "
            ."  join  %s s on s.userid= l.userid "
            ." where %s  group by subject  order by subject ; ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function tongji_require_count($start_time,$end_time) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);
        $where_arr[]=" accept_flag =1  ";
        $sql = $this->gen_sql_new(
            "select cur_require_adminid as adminid ,count(*) as value "
            ." from  %s  tr "
            ." join  %s  t on tr.test_lesson_subject_id = t.test_lesson_subject_id  "
            ." where %s  group by  cur_require_adminid order by value desc ",
            self::DB_TABLE_NAME ,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_list($sql);
    }

    public function get_success_test_lesson_count_list_all($start_time,$end_time){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);
        $where_arr[]=" accept_flag =1  ";
        $sql = $this->gen_sql_new(
            "select count(*) as value,count(distinct cur_require_adminid) all_count "
            ." from  %s  tr "
            ." join  %s  t on tr.test_lesson_subject_id = t.test_lesson_subject_id  "
            ." where %s   ",
            self::DB_TABLE_NAME ,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_row($sql);

    }

    public function tongji_require_count_origin( $field_name,$start_time,$end_time,$adminid_list=[],$tmk_adminid=-1,$origin_ex="",$origin='') {
        switch($field_name){
        case "origin" :
            $field_name="s.origin";
            break;
        case "grade" :
            $field_name="s.grade";
            break;
        default:
            break;
        }
        $where_arr = [
            ["s.origin like '%%%s%%' ",$origin, ''],
            " accept_flag =1  ",
            " is_test_user=0  ",
            " require_admin_type =2  ",
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");

        $where_arr[]= $ret_in_str;

        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value  , count(distinct(s.userid)) as require_count "
            ." from  %s  tr "
            ." join  %s  t on tr.test_lesson_subject_id = t.test_lesson_subject_id  "
            ." join  %s  s on t.userid= s.userid  "
            ." join  %s  n on t.userid= n.userid  "
            ." where %s  group by  check_value ",
            self::DB_TABLE_NAME ,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_list($sql);
    }


    public function require_count_seller($start_time,$end_time,$adminid_list=[],$adminid_all=[]) {
        $where_arr=[
            "require_admin_type=2" ,
            "is_test_user=0" ,
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_all);
        $sql=$this->gen_sql_new(
            "select  cur_require_adminid  as adminid,   count(*) as require_count "
            ."  from %s tr   "
            ."  left join  %s t   on t.test_lesson_subject_id=tr.test_lesson_subject_id  "
            ."  left join  %s s   on t.userid=s.userid"
            ." where  %s group by cur_require_adminid   ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item["adminid"];
        });
    }

    public function require_no_set_lesson_tonoji( $opt_date_str, $start_time, $end_time ) {
        /*
          $now=time(NULL);
          $start_time=$now-7*86400;
          $end_time= $now+14*86400;
        */
        $where_arr=[
            "test_lesson_student_status=200",
        ];

        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        //select
        $tmp_sql=$this->gen_sql_new(
            "select  %%s from %s  tr "
            ." join  %s t  on t.test_lesson_subject_id=tr.test_lesson_subject_id "
            ."join %s s   on t.userid=s.userid "
            ." where %s    group by  %%s  order by %%s asc  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        $list=[];

        $sql=sprintf($tmp_sql," from_unixtime(stu_request_test_lesson_time ,'%m-%d' ) as  id,  count(*) as count ",  "from_unixtime( stu_request_test_lesson_time ,'%m-%d' ) ", "from_unixtime( stu_request_test_lesson_time ,'%m-%d' )"  );
        $list["date_list"]=$this->main_get_list($sql);

        $sql=$this->gen_sql_new($tmp_sql," require_adminid as  id,  count(*) as count ",  "require_adminid ", "require_adminid"  );
        $list["require_admin_list"]=$this->main_get_list($sql);

        $sql=$this->gen_sql_new($tmp_sql," s.grade as  id,  count(*) as count ",  "s.grade", "s.grade"  );
        $list["grade_list"]=$this->main_get_list($sql);
        $sql=$this->gen_sql_new($tmp_sql," t.subject as  id,  count(*) as count ",  "t.subject", "t.subject"  );
        $list["subject_list"]=$this->main_get_list($sql);

        return $list;
    }

    public function tongin_set_lesson_time_info( $start_time,$end_time,$adminid_list=[],$adminid_all=[]){
        $where_arr=[
            "require_admin_type=2" ,
            "is_test_user=0" ,
            "tss.success_flag<2" ,
            "l.del_flag=0" ,
        ];
        $this->where_arr_add_time_range($where_arr,"set_lesson_time",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_all);

        $sql=$this->gen_sql_new(
            "select  require_adminid  as adminid,   count(*) as set_lesson_count "
            ."  from %s tr   "
            ."  left join  %s t   on t.test_lesson_subject_id=tr.test_lesson_subject_id  "
            ."  left join  %s tss   on tss.lessonid =tr.current_lessonid"
            ."  left join  %s l   on l.lessonid =tss.lessonid"
            ."  left join  %s s   on t.userid =s.userid"
            ." where  %s group by require_adminid  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }
    public function get_seller_date_test_lesson_list( $start_time,$end_time,$adminid_list=[] ) {
        $where_arr=[
            "is_test_user=0",
            "accept_flag=1",
            "require_admin_type=2",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);
        //E\Etest_lesson_fail_flag
        $sql=$this->gen_sql_new(
            "select from_unixtime(lesson_start,'%%Y-%%m-%%d' ) as date, count(*) as test_lesson_count  ,sum(success_flag=2 ) as test_lesson_fail_count "
            ." from %s tr "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s s on s.userid = l.userid "
            ." where  %s  group by from_unixtime(lesson_start,'%%Y-%%m-%%d' )  order by date "
            ,
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);

        $ret=$this->main_get_row($sql);

        $ret_list= $this->main_get_list($sql,function($item ){
            return $item["date"];
        });
        return \App\Helper\Common::gen_date_time_list($start_time,$end_time, $ret_list)  ;
    }


    public function tongji_test_lesson_all($start_time,$end_time,$adminid_list=[],$adminid_all=[]) {
        $where_arr=[
            "is_test_user=0",
            "accept_flag=1",
            "require_admin_type=2",
        ];
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_all);
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        //E\Etest_lesson_fail_flag
        $sql=$this->gen_sql_new(
            "select count(*) as test_lesson_count  ,sum(success_flag=2 ) as test_lesson_fail_count "
            ." from %s tr "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s s on s.userid = l.userid "
            ." where  %s "
            ,
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);

        $ret=$this->main_get_row($sql);
        $ret["test_lesson_fail_percent"]=$ret["test_lesson_count"]? intval($ret["test_lesson_fail_count"]*100/$ret["test_lesson_count"]):0 ;

        return $ret;
    }


    public function tongji_test_lesson_succ_count($start_time,$end_time) {
        $where_arr=[
            " accept_flag =1  ",
            "success_flag in (0,1)",
            "is_test_user=0",
            "t.require_admin_type=2",
            "l.lesson_type=2"
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select cur_require_adminid as adminid ,count(*) as value  "
            . " from  %s  tr "
            ." left join %s l on  tr.current_lessonid = l.lessonid "
            ." left join %s tts on  tr.current_lessonid = tts.lessonid "
            ." left join %s t on  tr.test_lesson_subject_id = t.test_lesson_subject_id "
            ." left join %s s on l.userid=s.userid"
            ." where  %s "
            ." group by  cur_require_adminid order by value desc ",
            self::DB_TABLE_NAME ,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_list($sql);
    }

    public function get_tmk_test_lesson_count_info($start_time,$end_time,$subject=-1){
        $where_arr = [
            "ss.tmk_student_status =3",
            "tr.accept_flag in (0,1)",
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            ["l.subject = %u",$subject,-1]
        ];
        $sql=$this->gen_sql_new(
            "select tmk_adminid adminid,count(*) all_count,sum(success_flag in (0,1)) success_count,sum(success_flag =2) fail_count"
            ." from  %s tr"
            ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ." left join %s ss on  t.userid = ss.userid "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." where  %s group by tmk_adminid "
            , t_test_lesson_subject_require::DB_TABLE_NAME
            , t_test_lesson_subject::DB_TABLE_NAME
            , t_seller_student_new::DB_TABLE_NAME
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME
            , t_lesson_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['adminid'];
        });
    }

    public function get_origin_info_by_order($start_time,$end_time,$origin,$origin_ex,$adminid_list){
        $where_arr = [
            "require_admin_type =2",
            ["tr.origin like '%%%s%%' ",$origin,""],
            ["require_time  >= %u",$start_time,-1],
            ["require_time <= %u",$end_time,-1]
        ];
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"tr.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $sql= $this->gen_sql_new("select count(*) all_count,sum(success_flag is not null) lesson_count,sum(success_flag in (0,1)) success_count,sum(o.orderid is not null) order_count,tr.origin from %s tr".
                                 " left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id".
                                 " left join %s tss on tr.current_lessonid = tss.lessonid ".
                                 " left join %s o on tss.lessonid = o.from_test_lesson_id".
                                 " where %s group by tr.origin ",
                                 self::DB_TABLE_NAME,
                                 t_test_lesson_subject::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }
    public function tong_ass_test_lesson_info($start_time,$end_time ,$ass_test_lesson_type) {
        $where_arr=[ "require_adminid>0", "require_admin_type=1" ];
        if  ($ass_test_lesson_type == -1  ) {
            $where_arr[]="ass_test_lesson_type>0";
        }else{
            $where_arr[]=["ass_test_lesson_type=%d", $ass_test_lesson_type , -1];
        }
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select  require_adminid, count(*) as count, sum(assigned_lesson_count>0) course_count   "
            ." from  %s tr"
            ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ." left join %s ss on  t.userid = ss.userid "
            ." left join %s s on  t.userid = s.userid "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." left join %s c on  tss.lessonid = c.ass_from_test_lesson_id "
            ." where  %s  group by require_adminid "
            , t_test_lesson_subject_require::DB_TABLE_NAME
            , t_test_lesson_subject::DB_TABLE_NAME
            , t_seller_student_new::DB_TABLE_NAME
            , t_student_info::DB_TABLE_NAME
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME
            , t_lesson_info::DB_TABLE_NAME
            , t_course_order::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function require_time_test_lesson_require_time_date_info($start_time, $end_time ){
        $where_arr=[
            "require_time<stu_request_test_lesson_time " ,
            "is_test_user=0" ,
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select  from_unixtime(require_time, '%%Y-%%m-%%d') require_date, from_unixtime(stu_request_test_lesson_time  , '%%Y-%%m-%%d') require_lesson_date "
            ." from %s  tr "
            ." left join %s  t on tr.test_lesson_subject_id= t.test_lesson_subject_id "
            ." left join %s  n on n.userid= t.userid "
            ." left join %s  s on s.userid= t.userid ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME
        );
        $list=$this->main_get_list($sql);
        $ret_map=[];
        foreach ( $list as $item  ) {
            $require_date        = strtotime( $item["require_date"]);
            $require_lesson_date = strtotime( ["require_lesson_date"]);

            if(!isset( $ret_map[$require_date] ) ) {
                $ret_map[$require_date ]=["title" => $item["require_date"] ];
            }
            $diff_day=($require_lesson_date - $require_date)/86400;
            if ($diff_day >=7) {
                $diff_day=7;
            }
            $key="day_".$diff_day;
            $ret_map[$require_date ][$key] = @$ret_map[$require_date ][$key] +1;

        }
        return $ret_map;
    }

    public function get_test_lesson_require_list_for_jw($start_time,$end_time,$num){
        $where_arr = [
            ["t.stu_request_test_lesson_time >= %u",$start_time,-1],
            //["t.stu_request_test_lesson_time <= %u",$end_time,-1],
            "test_lesson_student_status = 200",
            "accept_adminid <= 0",
            "m.account_role <>12",
            "tr.seller_top_flag=0",
            "s.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select require_id,t.history_accept_adminid,tr.seller_top_flag"
                                  ." from %s tr"
                                  ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
                                  ." left join %s m on tr.cur_require_adminid=m.uid"
                                  ." left join %s s on t.userid=s.userid"
                                  ." where %s order by t.stu_request_test_lesson_time asc,tr.require_time asc limit %u",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $num
        );
        return $this->main_get_list($sql);
    }

    public function get_need_plan_require($start_time,$end_time){
        $where_arr = [
            ["t.stu_request_test_lesson_time >= %u",$start_time,-1],
            ["t.stu_request_test_lesson_time <= %u",$end_time,-1],
            "test_lesson_student_status = 200",
            "is_green_flag =0",
            "cur_require_adminid <> 68 and cur_require_adminid <> 349 and cur_require_adminid <> 944",
            "s.grade not in (100,200,300)"
        ];
        $sql = $this->gen_sql_new("select require_id,t.stu_request_test_lesson_time,t.subject,s.grade from %s tr"
                                  ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
                                  ." left join %s s on s.userid = t.userid"
                                  ." where %s order by t.stu_request_test_lesson_time asc,tr.require_time asc ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);


    }

    public function get_green_channel_require_id($start_time,$end_time,$num){
        $where_arr = [
            ["t.stu_request_test_lesson_time >= %u",$start_time,-1],
            //["t.stu_request_test_lesson_time <= %u",$end_time,-1],
            "test_lesson_student_status = 200",
            "accept_adminid <= 0",
            "tr.seller_top_flag=0",
            "is_green_flag=1",
            "m.account_role<>12",
            "s.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select require_id,t.history_accept_adminid,tr.seller_top_flag "
                                  ." from %s tr"
                                  ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
                                  ." left join %s m on tr.cur_require_adminid = m.uid "
                                  ." left join %s s on t.userid = s.userid"
                                  ." where %s order by t.stu_request_test_lesson_time asc,tr.require_time asc limit %u",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $num
        );
        return $this->main_get_list($sql);


    }

    public function get_jw_order_lesson_info($start_time,$end_time,$adminid){
        $where_arr = [
            ["require_assign_time >= %u",$start_time,-1],
            ["require_assign_time <= %u",$end_time,-1],
            ["tr.accept_adminid = %u",$adminid,-1],
            "accept_adminid > 0",
            "m.account_role = 3",
            "s.is_test_user=0",
            "o.orderid>0",
            "seller_top_flag=1",
            "is_green_flag=0"
        ];
        $sql = $this->gen_sql_new("select s.nick,tt.realname,tt.teacherid,tr.test_lesson_student_status,".
                                  "tss.teacher_dimension,l.lessonid,l.lesson_start ,l.grade,l.subject,mm.account ".
                                  " from %s tr left join %s m on tr.accept_adminid = m.uid ".
                                  " left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id".
                                  " left join %s s on t.userid = s.userid".
                                  " left join %s o  on tr.current_lessonid = o.from_test_lesson_id and o.contract_type in(0,3) and contract_status>0".
                                  " left join %s l on tr.current_lessonid = l.lessonid".
                                  " left join %s tss on l.lessonid = tss.lessonid".
                                  " left join %s tt on l.teacherid = tt.teacherid".
                                  " left join %s mm on tr.cur_require_adminid = mm.uid".
                                  " where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_top_lesson_list($start_time,$end_time,$adminid){
        $where_arr = [
            ["tr.require_assign_time >= %u",$start_time,-1],
            ["tr.require_assign_time <= %u",$end_time,-1],
            ["tr.accept_adminid = %u",$adminid,-1],
            "s.is_test_user=0",
            "test_lesson_student_status in(210,220,290,300,301,302,420)",
            "seller_top_flag=1",
            "is_green_flag=0"
        ];
        $sql = $this->gen_sql_new("select s.nick,t.realname,t.teacherid,tr.test_lesson_student_status,"
                                  ."tss.teacher_dimension,l.lessonid,l.lesson_start ,l.grade,l.subject "
                                  ." from %s tr left join %s l on tr.current_lessonid = l.lessonid"
                                  ." left join %s tss on tr.current_lessonid = tss.lessonid"
                                  ." left join %s s on l.userid = s.userid"
                                  ." left join %s t on t.teacherid = l.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_seller_top_lesson_suc_list($start_time,$end_time,$adminid){
        $where_arr = [
            "accept_adminid =".$adminid,
            "m.account_role = 3",
            "s.is_test_user=0",
            // "ll.lesson_user_online_status in (0,1)",
            "ll.lesson_del_flag=0",
            "seller_top_flag=1",
            "is_green_flag=0"
        ];

        $sql = $this->gen_sql_new("select s.nick,tt.realname,tt.teacherid,tr.test_lesson_student_status,".
                                  "tss.teacher_dimension,ll.lessonid,ll.lesson_start ,ll.grade,ll.subject,mm.account ".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s ll on tr.current_lessonid = ll.lessonid".
                                  " join %s tss on ll.lessonid = tss.lessonid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type <>2 and lesson_status =2 and confirm_flag in (0,1) )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on ll.userid = s.userid ".
                                  " join %s tt on ll.teacherid = tt.teacherid ".
                                  " join %s mm on tr.cur_require_adminid = mm.uid".
                                  " where %s order by ll.userid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);

    }

    public function get_jw_teacher_test_lesson_info($start_time,$end_time){
        $where_arr = [
            ["require_assign_time >= %u",$start_time,-1],
            ["require_assign_time <= %u",$end_time,-1],
            "accept_adminid > 0",
            "m.account_role = 3",
            "s.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select m.name,accept_adminid,".
                                  "m.account,sum(if(test_lesson_student_status in(210,220,290,300,301,302,420),1,0)) set_count,".
                                  "sum(if(test_lesson_student_status in(210,220,290,300,301,302,420) and t.require_admin_type=1,1,0)) ass_count_set, ".
                                  "sum(if(test_lesson_student_status in(210,220,290,300,301,302,420) and t.require_admin_type=1 and t.ass_test_lesson_type=1,1,0)) ass_kk_count_set, ".
                                  "sum(if(test_lesson_student_status in(210,220,290,300,301,302,420) and t.require_admin_type=1 and t.ass_test_lesson_type=2,1,0)) ass_hls_count_set, ".
                                  "sum(if(test_lesson_student_status in(210,220,290,300,301,302,420) and t.require_admin_type=2,1,0)) seller_count_set, ".
                                  "sum(if(test_lesson_student_status in(210,220,290,300,301,302,420) and tr.is_green_flag=1,1,0)) green_count, ".
                                  "sum(if(test_lesson_student_status in(210,220,290,300,301,302,420) and tr.is_green_flag=1 and t.require_admin_type=1,1,0)) ass_green_count, ".
                                  "sum(if(test_lesson_student_status in(210,220,290,300,301,302,420) and tr.is_green_flag=1 and t.require_admin_type=2,1,0)) seller_green_count, ".
                                  "sum(if(jw_test_lesson_status =2,1,0)) gz_count,".
                                  "sum(if(test_lesson_student_status in(110,120) and no_accept_reason='未排课,期待时间已到',1,0)) back_count,".
                                  "sum(if(test_lesson_student_status in(110,120) and no_accept_reason <> '未排课,期待时间已到',1,0)) back_other_count,".
                                  "sum(if(test_lesson_student_status =200,1,0)) un_count,".
                                  "sum(if(tr.seller_top_flag=1 and test_lesson_student_status =200 and tr.is_green_flag=0,1,0)) top_un_count,".
                                  "sum(if(tr.seller_top_flag=1 and test_lesson_student_status in(210,220,290,300,301,302,420) and tr.is_green_flag=0,1,0)) top_count,".
                                   "sum(if(tr.seller_top_flag=0 and test_lesson_student_status in(210,220,290,300,301,302,420) and tr.is_green_flag=0 and tss.grab_flag=1,1,0)) grab_count,".
                                  " sum(if(o.orderid>0 and tr.seller_top_flag=1 and tr.is_green_flag=0,1,0)) order_num,".
                                  " sum(if(test_lesson_student_status in(210,220,290,300,301,302,420),tss.set_lesson_time-tr.require_time,0)) set_lesson_time_all,".
                                  "sum(if(test_lesson_student_status in(210,220,290,300,301,302,420) and (FROM_UNIXTIME(tr.require_time, '%%H')>=21 or FROM_UNIXTIME(tr.require_time, '%%H') <=1),1,0)) set_count_late,".
                                  "sum(if(test_lesson_student_status in(210,220,290,300,301,302,420) and (FROM_UNIXTIME(tr.require_time, '%%H')>=21 or FROM_UNIXTIME(tr.require_time, '%%H') <=1),tss.set_lesson_time-tr.require_time,0)) set_count_late_time,".
                                  "count(*) all_count ".
                                  " from %s tr left join %s m on tr.accept_adminid = m.uid ".
                                  " left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id".
                                  " left join %s s on t.userid = s.userid".
                                  " left join %s o  on tr.current_lessonid = o.from_test_lesson_id and o.contract_type in(0,3) and contract_status>0".
                                  " left join %s tss on tr.current_lessonid = tss.lessonid".
                                  " where %s group by accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_jw_teacher_test_lesson_info_bc($start_time,$end_time){
        $where_arr = [
            ["set_lesson_time  >= %u",$start_time,-1],
            ["set_lesson_time  <= %u",$end_time,-1],
            "accept_adminid > 0",
            "m.account_role = 3",
            "s.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select accept_adminid,".
                                  "m.account,sum(if(test_lesson_student_status in(210,220,290,300,301,302,420),1,0)) set_count,".
                                  "sum(if(jw_test_lesson_status =2,1,0)) gz_count,".
                                  "sum(if(test_lesson_student_status in(110,120),1,0)) back_count,".
                                  "sum(if(test_lesson_student_status =200,1,0)) un_count,".
                                  "count(*) all_count ".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s tts on tr.current_lessonid=tts.lessonid ".
                                  " join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id".
                                  "  join %s s on t.userid = s.userid".
                                  " where %s group by accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }

    public function get_teat_lesson_transfor_info_detail($start_time,$end_time,$adminid=-1){
        $where_arr = [
            // "accept_adminid > 0",
            "m.account_role = 3",
            "s.is_test_user=0"
        ];
        if($adminid==-1){
            $where_arr[]="accept_adminid > 0";
        }else{
            $where_arr[]="accept_adminid =".$adminid;
        }
        $sql = $this->gen_sql_new("select distinct ll.userid,require_admin_type,ll.teacherid,ll.subject".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s ll on tr.current_lessonid = ll.lessonid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type <>2 and lesson_status =2 and confirm_flag in (0,1) )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on t.userid = s.userid ".
                                  " where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_teat_lesson_transfor_info($start_time,$end_time){
        $where_arr = [
            "accept_adminid > 0",
            "m.account_role = 3",
            "s.is_test_user=0",
            // "ll.lesson_user_online_status in (0,1)",
            "ll.lesson_del_flag=0"
        ];

        $sql = $this->gen_sql_new("select accept_adminid,sum(l.lessonid >0) tra_count,".
                                  "sum(l.lessonid >0 and t.require_admin_type =1) tra_count_ass,".
                                  "sum(l.lessonid >0 and t.require_admin_type =2) tra_count_seller".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s ll on tr.current_lessonid = ll.lessonid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type in (0,1,3) and lesson_status =2 and confirm_flag in (0,1) and lesson_del_flag=0 )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on t.userid = s.userid ".
                                  " where %s group by accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }

    public function get_teat_lesson_transfor_info_type_total($start_time,$end_time,$require_admin_type=-1,$ass_test_lesson_type=-1,$plan_type=-1){
        $where_arr = [
            "accept_adminid > 0",
            "m.account_role = 3",
            "s.is_test_user=0",
            // "ll.lesson_user_online_status in (0,1)",
            "ll.lesson_del_flag=0",
            ["t.require_admin_type=%u",$require_admin_type,-1],
            ["t.ass_test_lesson_type=%u",$ass_test_lesson_type,-1],
            "l.lessonid >0"
        ];
        if($plan_type==1){
            $where_arr[]="tr.is_green_flag=0 and tr.seller_top_flag =1";
        }elseif($plan_type==2){
            $where_arr[]="tr.is_green_flag=1";
        }elseif($plan_type==3){
             $where_arr[]="tr.is_green_flag=0 and tr.seller_top_flag =0 and tss.grab_flag=1";
        }elseif($plan_type==4){
            $where_arr[]="tr.is_green_flag=0 and tr.seller_top_flag =0 and tss.grab_flag=0";
        }

        $sql = $this->gen_sql_new("select count(distinct l.lessonid) num ".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s ll on tr.current_lessonid = ll.lessonid".
                                  " join %s tss on tr.current_lessonid = tss.lessonid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type in (0,1,3) and lesson_status =2 and confirm_flag in (0,1) and lesson_del_flag=0 )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on t.userid = s.userid ".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_value($sql);
    }


    public function get_teat_lesson_transfor_info_type($start_time,$end_time,$require_admin_type=-1,$is_green_flag=-1){
        $where_arr = [
            "accept_adminid > 0",
            "m.account_role = 3",
            "s.is_test_user=0",
            // "ll.lesson_user_online_status in (0,1)",
            "ll.lesson_del_flag=0",
            ["t.require_admin_type=%u",$require_admin_type,-1],
            ["tr.is_green_flag=%u",$is_green_flag,-1],
            "l.lessonid >0"
        ];

        $sql = $this->gen_sql_new("select accept_adminid,count(distinct l.lessonid) num ".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s ll on tr.current_lessonid = ll.lessonid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type in (0,1,3) and lesson_status =2 and confirm_flag in (0,1) and lesson_del_flag=0 )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on t.userid = s.userid ".
                                  " where %s group by accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }

    public function get_teat_lesson_transfor_info_seller_top($start_time,$end_time,$require_admin_type=-1,$seller_top_flag=-1){
        $where_arr = [
            "accept_adminid > 0",
            "m.account_role = 3",
            "s.is_test_user=0",
            // "ll.lesson_user_online_status in (0,1)",
            "ll.lesson_del_flag=0",
            ["t.require_admin_type=%u",$require_admin_type,-1],
            // ["tr.seller_top_flag=%u",$seller_top_flag,-1],
            "l.lessonid >0"
        ];
        if($seller_top_flag==1){
            $where_arr[]="tr.seller_top_flag=1";
            $where_arr[]="tr.is_green_flag=1";
        }

        $sql = $this->gen_sql_new("select accept_adminid,count(distinct l.lessonid) num ".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s ll on tr.current_lessonid = ll.lessonid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type in (0,1,3) and lesson_status =2 and confirm_flag in (0,1) and lesson_del_flag=0 )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on t.userid = s.userid ".
                                  " where %s group by accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }



    public function get_teat_lesson_transfor_new($start_time,$end_time,$adminid){
        $where_arr = [
            "accept_adminid =".$adminid,
            "m.account_role = 3",
            "s.is_test_user=0",
            //"ll.lesson_user_online_status in (0,1)",
            "ll.lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select accept_adminid,sum(l.lessonid >0) tra_count,".
                                  "sum(l.lessonid >0 and t.require_admin_type =1) tra_count_ass,".
                                  "sum(l.lessonid >0 and t.require_admin_type =2) tra_count_seller".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s ll on tr.current_lessonid = ll.lessonid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type in (0,1,3) and lesson_status =2 and confirm_flag in (0,1) and lesson_del_flag=0 )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on t.userid = s.userid ".
                                  " where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_teat_lesson_transfor_info_by_adminid($start_time,$end_time,$adminid,$is_green_flag=-1,$require_admin_type=-1){
        $where_arr = [
            "accept_adminid =".$adminid,
            "m.account_role = 3",
            "s.is_test_user=0",
            // "ll.lesson_user_online_status in (0,1)",
            "ll.lesson_del_flag=0",
            ["tr.is_green_flag=%u",$is_green_flag,-1],
            ["t.require_admin_type=%u",$require_admin_type,-1],
        ];

        $sql = $this->gen_sql_new("select accept_adminid,ll.userid,ll.grade,ll.subject,s.nick,tt.realname tea_name,ll.lesson_start,ll.lessonid".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s ll on tr.current_lessonid = ll.lessonid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type in (0,1,3) and lesson_status =2 and confirm_flag in (0,1) and lesson_del_flag=0 )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on ll.userid = s.userid ".
                                  " join %s tt on ll.teacherid = tt.teacherid ".
                                  " where %s order by ll.userid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);

        // $where_arr = [
        //     "accept_adminid =".$adminid,
        //     "m.account_role = 3",
        //     "s.is_test_user=0"
        // ];
        // $sql = $this->gen_sql_new("select accept_adminid,ll.userid,ll.grade,ll.subject,s.nick,tt.realname tea_name,ll.lesson_start,ll.lessonid ".
        //                           " from %s tr join %s m on tr.accept_adminid = m.uid ".
        //                           " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
        //                           " join %s ll on tr.current_lessonid = ll.lessonid".
        //                           " join %s l on (ll.teacherid = l.teacherid ".
        //                           " and t.userid = l.userid ".
        //                           " and t.subject = l.subject ".
        //                           " and l.lesson_start= ".
        //                           " (select min(lesson_start) from %s where userid=t.userid and subject = t.subject and lesson_type <>2 and lesson_status =2 and confirm_flag in (0,1) )and l.lesson_start >= %u and l.lesson_start <= %u)".
        //                           " join %s s on ll.userid = s.userid".
        //                           " join %s tt on ll.teacherid=tt.teacherid".
        //                           " where %s  ",
        //                           self::DB_TABLE_NAME,
        //                           t_manager_info::DB_TABLE_NAME,
        //                           t_test_lesson_subject::DB_TABLE_NAME,
        //                           t_lesson_info::DB_TABLE_NAME,
        //                           t_lesson_info::DB_TABLE_NAME,
        //                           t_lesson_info::DB_TABLE_NAME,
        //                           $start_time,
        //                           $end_time,
        //                           t_student_info::DB_TABLE_NAME,
        //                           t_teacher_info::DB_TABLE_NAME,
        //                           $where_arr
        // );

        // return $this->main_get_list($sql);
    }

    public function get_tea_lesson_transfor_info_by_adminid_new($start_time,$end_time,$adminid,$is_green_flag=-1,$require_admin_type=-1){
        $where_arr = [
            "accept_adminid =".$adminid,
            "m.account_role = 3",
            "s.is_test_user=0",
            // "ll.lesson_user_online_status in (0,1)",
            "ll.lesson_del_flag=0",
            ["tr.is_green_flag=%u",$is_green_flag,-1],
            ["t.require_admin_type=%u",$require_admin_type,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"trr.lesson_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select accept_adminid,ll.userid,ll.grade,ll.subject,s.nick,tt.realname tea_name,ll.lesson_start,ll.lessonid".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s ll on tr.current_lessonid = ll.lessonid".
                                  " join %s trr on ll.teacherid=trr.teacherid and ll.subject =trr.lesson_subject and ll.userid =trr.userid and trr.type=18 ".
                                  " join %s s on ll.userid = s.userid ".
                                  " join %s tt on ll.teacherid = tt.teacherid ".
                                  " where %s order by ll.userid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }




    public function get_teat_lesson_transfor_info_new($start_time,$end_time){
        $where_arr = [
            ["o.order_time >= %u",$start_time,-1],
            ["o.order_time <= %u",$end_time,-1],
            "o.contract_status >0",
            "accept_adminid > 0",
            "m.account_role = 3"
        ];
        $sql = $this->gen_sql_new("select accept_adminid,count(*) tra_count,".
                                  "sum(mm.account_role =1) tra_count_ass,".
                                  "sum(mm.account_role =2) tra_count_seller".
                                  " from %s tr join %s m on tr.accept_adminid = m.uid ".
                                  " join %s mm on tr.cur_require_adminid = mm.uid ".
                                  " join %s o on tr.current_lessonid = o.from_test_lesson_id".
                                  " where %s group by accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });

    }



    public function get_none_total_info($start_time,$end_time){
        $where_arr = [
            ["require_time >= %u",$start_time,-1],
            ["require_time < %u",$end_time,-1],
            // "accept_adminid = 0",
            " test_lesson_student_status = 200",
            "s.is_test_user=0"
        ];
        $sql =  $this->gen_sql_new("select count(*) from %s tr"
                                   ." left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                   ." left join %s s on s.userid = t.userid"
                                   ." where %s",
                                   self::DB_TABLE_NAME,
                                   t_test_lesson_subject::DB_TABLE_NAME,
                                   t_student_info::DB_TABLE_NAME,
                                   $where_arr);
        return $this->main_get_value($sql);

    }

    public function get_no_assign_total_info($start_time,$end_time){
        $where_arr = [
            ["require_time >= %u",$start_time,-1],
            ["require_time < %u",$end_time,-1],
            "accept_adminid = 0",
            " test_lesson_student_status = 200",
            "s.is_test_user=0"
        ];
        $sql =  $this->gen_sql_new("select count(*) from %s tr"
                                   ." left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                   ." left join %s s on s.userid = t.userid"
                                   ." where %s",
                                   self::DB_TABLE_NAME,
                                   t_test_lesson_subject::DB_TABLE_NAME,
                                   t_student_info::DB_TABLE_NAME,
                                   $where_arr);
        return $this->main_get_value($sql);

    }
    public function get_no_assign_total_info_detail($start_time,$end_time){
        $where_arr = [
            ["require_time >= %u",$start_time,-1],
            ["require_time < %u",$end_time,-1],
            "accept_adminid = 0",
            " test_lesson_student_status = 200",
            "s.is_test_user=0"
        ];
        $sql =  $this->gen_sql_new("select tr.require_id,tr.require_time from %s tr"
                                   ." left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                   ." left join %s s on s.userid = t.userid"
                                   ." where %s",
                                   self::DB_TABLE_NAME,
                                   t_test_lesson_subject::DB_TABLE_NAME,
                                   t_student_info::DB_TABLE_NAME,
                                   $where_arr);
        return $this->main_get_list($sql);

    }



    public function get_none_total_info_list($start_time,$end_time){
        $where_arr = [
            ["require_time >= %u",$start_time,-1],
            ["require_time < %u",$end_time,-1],
            // "accept_adminid = 0",
            " test_lesson_student_status = 200"
        ];
        $sql =  $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);

    }


    public function get_all_need_plan_require_list($start_time,$end_time,$type_flag=-2){
        $where_arr = [
            ["tr.curl_stu_request_test_lesson_time >= %u",$start_time,-1],
            "test_lesson_student_status = 200",
            "accept_adminid <= 0",
            "m.account_role <>12",
            "s.is_test_user=0"
        ];
        if($type_flag==-2){
            $where_arr[]="if(s.grade>=100 and s.grade<200,t.subject<>2,true)";
        }elseif($type_flag==2){
            $where_arr[]="s.grade>=100 and s.grade<200 and t.subject=2";
        }
        $sql = $this->gen_sql_new("select require_id,t.history_accept_adminid,require_adminid,"
                                  ."nick,stu_request_test_lesson_time,tr.seller_top_flag,s.grade,  "
                                  ." t.subject "
                                  ." from %s tr"
                                  ." join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
                                  ." join %s s on t.userid = s.userid"
                                  ." join %s m on tr.cur_require_adminid = m.uid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);


    }

    public function get_jw_teacher_history_accept_adminid($start_time,$end_time){
        $where_arr = [
            ["t.stu_request_test_lesson_time >= %u",$start_time,-1],
            "test_lesson_student_status = 200",
            "accept_adminid <= 0",
            "history_accept_adminid >0",
            "m.account_role <>12",
            "tr.seller_top_flag=0",
            "s.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select require_id,t.history_accept_adminid,require_adminid,"
                                  ."nick,stu_request_test_lesson_time,tr.seller_top_flag  "
                                  ." from %s tr"
                                  ." join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
                                  ." join %s s on t.userid = s.userid"
                                  ." join %s m on tr.cur_require_adminid = m.uid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_top_require_list($start_time,$end_time){
        $where_arr = [
            ["t.stu_request_test_lesson_time >= %u",$start_time,-1],
            "test_lesson_student_status = 200",
            "accept_adminid <= 0",
            "m.account_role <>12",
            "s.is_test_user=0",
            "tr.seller_top_flag=1"
        ];
        $sql = $this->gen_sql_new("select require_id,t.history_accept_adminid,require_adminid,"
                                  ."nick,stu_request_test_lesson_time,tr.seller_top_flag"
                                  ."   from %s tr"
                                  ." join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
                                  ." join %s s on t.userid = s.userid"
                                  ." join %s m on tr.cur_require_adminid = m.uid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }


    public function get_test_lesson_transfor_info_new($start_time,$end_time,$page_num){
        $where_arr = [
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start<= %u",$end_time,-1],
        ];
        $sql = $this->gen_sql_new("select t.cur_require_adminid,m.account,l.userid,s.nick,tss.lessonid,tss.success_flag,s.phone,l.grade,s.origin,ss.phone_location,s.reg_time,o.order_time,o.price,o.lesson_total,l.teacherid,tt.realname,l.lesson_start,l.subject,ss.add_time,m.account_role from %s t "
                                  ." left join  %s tss on t.current_lessonid = tss.lessonid"
                                  ." left join %s l on tss.lessonid = l.lessonid"
                                  ." left join %s o on (tss.lessonid = o.from_test_lesson_id and o.contract_status >0) "
                                  ." left join %s s on l.userid = s.userid"
                                  ." left join %s tt on l.teacherid = tt.teacherid"
                                  ." left join %s m on t.cur_require_adminid = m.uid"
                                  ." left join %s ss on l.userid = ss.userid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function get_test_succ_ass($start_time,$end_time){
        $end_time = time();
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start<= %u",$end_time,-1],
            "lesson_type = 0",
            "confirm_flag in(0,1)"
        ];
        $sql = $this->gen_sql_new("select distinct subject,teacherid,userid,grade from %s  where %s ",t_lesson_info::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }
    public function  get_order_fail_list(
        $page_num,$start_time, $end_time, $cur_require_adminid,$origin_userid_flag,$order_flag ,$test_lesson_order_fail_flag,$userid)
    {
        $where_arr=[
            "lesson_del_flag=0",
            [ "test_lesson_order_fail_flag=%u", $test_lesson_order_fail_flag, -1],
            [ "l.userid=%u", $userid, -1],
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time-3600*24*7,$end_time+3600*24*7);
        $this->where_arr_add_time_range($where_arr,"l.lesson_end",$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"cur_require_adminid",$cur_require_adminid);
        $this->where_arr_add_boolean_for_value($where_arr,"origin_userid",$origin_userid_flag );
        $this->where_arr_add_boolean_for_value($where_arr,"contract_status",$order_flag,true);

        $sql= $this->gen_sql_new(
            "select s.origin_level,tr.require_id, l.lesson_start ,l.lesson_end,l.userid,l.teacherid ,s.grade,l.subject,  cur_require_adminid ,  test_lesson_fail_flag , test_lesson_order_fail_set_time, test_lesson_order_fail_flag, test_lesson_order_fail_desc,   o.contract_status    " .
            " from %s tr ".
            " left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id".
            " left join %s tss on tr.current_lessonid = tss.lessonid ".
            " left join %s l on tr.current_lessonid = l.lessonid ".
            " left join %s s on l.userid = s.userid ".
            " left join %s o on tss.lessonid = o.from_test_lesson_id".
            " where %s  order by lesson_start desc ",
            self::DB_TABLE_NAME,//tr
            t_test_lesson_subject::DB_TABLE_NAME,//t
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,//tss
            t_lesson_info::DB_TABLE_NAME,//l
            t_student_info::DB_TABLE_NAME,//s
            t_order_info::DB_TABLE_NAME,//o
            $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function  get_test_fail_row($cur_require_adminid,$userid = -1)
    {
        $time = time(null);
        $where_arr=[
            "s.is_test_user = 0",
            "l.lesson_status = 2",
            "l.lesson_del_flag = 0",
            "tr.test_lesson_order_fail_flag=0 or tr.test_lesson_order_fail_flag is null",
            'contract_status=0 or contract_status is null',
            ['t.userid = %u ',$userid,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",1504195200,$time);
        $this->where_arr_add_time_range($where_arr,"l.lesson_end",1504195200,$time);
        $this->where_arr_add__2_setid_field($where_arr,"cur_require_adminid",$cur_require_adminid);

        $sql= $this->gen_sql_new(
            "select tr.require_id, l.lesson_start ,l.userid,l.teacherid ,s.grade,l.subject,  cur_require_adminid ,  test_lesson_fail_flag , test_lesson_order_fail_set_time, test_lesson_order_fail_flag, test_lesson_order_fail_desc,   o.contract_status    " .
            " from %s tr".
            " left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id".
            " left join %s tss on tr.current_lessonid = tss.lessonid ".
            " left join %s l on tr.current_lessonid = l.lessonid ".
            " left join %s s on l.userid = s.userid ".
            " left join %s o on tss.lessonid = o.from_test_lesson_id".
            " where %s  order by lesson_start desc limit 1",
            self::DB_TABLE_NAME,//tr
            t_test_lesson_subject::DB_TABLE_NAME,//t
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,//tss
            t_lesson_info::DB_TABLE_NAME,//l
            t_student_info::DB_TABLE_NAME,//s
            t_order_info::DB_TABLE_NAME,//o
            $where_arr);
        return $this->main_get_row($sql);
    }

    public function  get_test_fail_row_new($cur_require_adminid)
    {
        $where_arr=[
            "lesson_del_flag=0",
            "test_lesson_order_fail_flag in (0,null)",
            'contract_status in (0,null)',
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",1503849600,time(null));
        $this->where_arr_add__2_setid_field($where_arr,"cur_require_adminid",$cur_require_adminid);

        $sql = $this->gen_sql_new(
            "select tr.require_id,l.lesson_start,l.userid,l.teacherid ,s.grade,l.subject,cur_require_adminid , "
            ." test_lesson_fail_flag , test_lesson_order_fail_set_time, test_lesson_order_fail_flag, "
            ."test_lesson_order_fail_desc,o.contract_status "
            ."from %s tr "
            ."left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id "
            ."left join %s tss on tr.current_lessonid = tss.lessonid  "
            ."left join %s l on tr.current_lessonid = l.lessonid "
            ." left join %s s on l.userid = s.userid "
            ." left join %s o on tss.lessonid = o.from_test_lesson_id "
            ."where %s "
            ." order by lesson_start desc limit 0,10"
            ,self::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function  get_test_fail_row_new_tow($cur_require_adminid,$userid = -1)
    {
        $time = time(null);
        $where_arr=[
            "l.lesson_status = 2",
            "l.lesson_del_flag = 0",
            'contract_status=0 or contract_status is null',
            ['t.userid = %u ',$userid,-1],
            ['tr.test_lesson_order_fail_flag = %u ',E\Etest_lesson_order_fail_flag::V_1701,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",1504195200,$time);
        $this->where_arr_add_time_range($where_arr,"l.lesson_end",1504195200,$time);
        $this->where_arr_add__2_setid_field($where_arr,"cur_require_adminid",$cur_require_adminid);

        $sql= $this->gen_sql_new(
            "select tr.require_id, l.lesson_start ,l.userid,l.teacherid ,s.grade,l.subject,  cur_require_adminid ,  test_lesson_fail_flag , test_lesson_order_fail_set_time, test_lesson_order_fail_flag, test_lesson_order_fail_desc,   o.contract_status    " .
            " from %s tr".
            " left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id".
            " left join %s tss on tr.current_lessonid = tss.lessonid ".
            " left join %s l on tr.current_lessonid = l.lessonid ".
            " left join %s s on l.userid = s.userid ".
            " left join %s o on tss.lessonid = o.from_test_lesson_id".
            " where %s  order by lesson_start desc limit 1",
            self::DB_TABLE_NAME,//tr
            t_test_lesson_subject::DB_TABLE_NAME,//t
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,//tss
            t_lesson_info::DB_TABLE_NAME,//l
            t_student_info::DB_TABLE_NAME,//s
            t_order_info::DB_TABLE_NAME,//o
            $where_arr);
        return $this->main_get_row($sql);
    }

    public function  tongji_get_order_fail($start_time, $end_time, $cur_require_adminid,$origin_userid_flag,$require_admin_type)
    {
        $this->switch_readonly_database();
        $where_arr=[
            "lesson_del_flag=0",
            ["require_admin_type=%u", $require_admin_type,-1 ],
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"cur_require_adminid",$cur_require_adminid);
        $this->where_arr_add_boolean_for_value($where_arr,"origin_userid",$origin_userid_flag );
        $this->where_arr_add_boolean_for_value($where_arr,"contract_status",0,true);


        $sql= $this->gen_sql_new(
            "select  test_lesson_order_fail_flag,  count(*)  as count" .
            " from %s tr".
            " left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id".
            " left join %s tss on tr.current_lessonid = tss.lessonid ".
            " left join %s l on tr.current_lessonid = l.lessonid ".
            " left join %s s on l.userid = s.userid ".
            " left join %s o on tss.lessonid = o.from_test_lesson_id".
            " where %s  group by test_lesson_order_fail_flag order by   count(*)  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);

    }
    public function  tongji_order_fail_seller_set($start_time, $end_time, $origin_userid_flag)
    {
        $where_arr=[
            "lesson_del_flag=0",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $this->where_arr_add_boolean_for_value($where_arr,"origin_userid",$origin_userid_flag );
        $this->where_arr_add_boolean_for_value($where_arr,"contract_status",0,true);


        $sql= $this->gen_sql_new(
            "select   cur_require_adminid, count(*) count,  sum(test_lesson_order_fail_flag=0)  as noset_count " .
            " from %s tr ".
            " left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id".
            " left join %s tss on tr.current_lessonid = tss.lessonid ".
            " left join %s l on tr.current_lessonid = l.lessonid ".
            " left join %s s on l.userid = s.userid ".
            " left join %s o on tss.lessonid = o.from_test_lesson_id".
            " where %s  group by cur_require_adminid order by  sum(test_lesson_order_fail_flag=0) desc   ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);

    }

    public function  tongji_require_time_vs_admin_assign_time_duration  ($start_time, $end_time )
    {
        $this->switch_readonly_database();
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"admin_assign_time",$start_time,$end_time);

        $sql= $this->gen_sql_new(
            "select  admin_revisiterid,  (require_time- admin_assign_time ) as duration " .
            " from %s t ".
            " join %s n on t.userid= n.userid ".
            " left join %s t on tr.require_id = t.current_require_id ".
            " where %s     ",
            t_test_lesson_subject::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);

    }


    public function get_no_succ_require_id_list($start_time,$end_time){
        $where_arr=[
            " c.courseid is null",
            "t.test_lesson_subject_id >0" ,
            "test_lesson_order_fail_flag =0"
        ];
        $this->where_arr_add_time_range($where_arr,"require_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select require_id,current_lessonid,c.courseid,require_time from %s tr "
                                  ." left join %s c on tr.current_lessonid =c.ass_from_test_lesson_id "
                                  ." left join %s t on tr.require_id = t.current_require_id"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_no_accept_info($time){
        $sql = $this->gen_sql_new("select no_accept_reason,accept_adminid from %s "
                                  ." where accept_flag =2  and require_time >=%u and accept_adminid in(343,418,434,436) order by require_time desc",
                                  self::DB_TABLE_NAME,$time
        );
        return $this->main_get_list($sql);

    }

    public function get_test_lesson_info_time($start_time,$end_time){
        $where_arr=[
            "test_lesson_student_status = 200"
        ];
        $this->where_arr_add_time_range($where_arr,"stu_request_test_lesson_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select FROM_UNIXTIME( stu_request_test_lesson_time, '%%H' ) h,count(*) num from %s tr left join %s t on tr.test_lesson_subject_id  = t.test_lesson_subject_id  where %s group by h",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["h"];
        });

    }

    public function get_test_lesson_info_time_week($start_time,$end_time){
        $where_arr=[
            "test_lesson_student_status = 200"
        ];
        $this->where_arr_add_time_range($where_arr,"stu_request_test_lesson_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select stu_request_test_lesson_time from %s tr left join %s t on tr.test_lesson_subject_id  = t.test_lesson_subject_id  where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_jw_set_lesson_time_info($start_time){
        $where_arr=[
            "tr.accept_adminid>0",
            "tr.accept_flag <>2",
            "tr.require_time >=".$start_time,
            "tr.current_lessonid>0",
            "t.stu_request_test_lesson_time>0",
            "tss.set_lesson_time>0",
            "tr.green_channel_teacherid=0"
        ];
        $sql = $this->gen_sql_new("select tr.require_time,tss.set_lesson_time,(t.stu_request_test_lesson_time - tss.set_lesson_time ) time,(tss.set_lesson_time-tr.require_time ) set_time,from_unixtime(require_time, '%%w') as week,from_unixtime(require_time, '%%H') as h,set_lesson_adminid,m.account,l.subject "
                                  ." from %s tr left join %s tss on tr.current_lessonid = tss.lessonid"
                                  ." left join %s l on tr.current_lessonid = l.lessonid"
                                  ." left join %s m on tss.set_lesson_adminid = m.uid"
                                  ." left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_green_list(){
        $sql = $this->gen_sql_new("select tr.green_channel_teacherid,t.realname,count(distinct(tr.require_id)) as num"
                                  ." from %s tr "
                                  ." left join %s t on tr.green_channel_teacherid=t.teacherid "
                                  ." where green_channel_teacherid>0 and is_test_user=0 "
                                  ." group by green_channel_teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function set_grab_status($requireid,$status){
        $where_arr = [
            ["require_id in (%s)",$requireid,0],
        ];
        $sql = $this->gen_sql_new("update %s set grab_status=%u "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$status
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_grab_test_lesson_list($subject,$grab_status,$grade){
        $where_arr = [
            ["grab_status=%u",$grab_status,-1],
            "(current_lessonid='' or current_lessonid is null)",
            "test_lesson_student_status=200"
        ];
        $where_arr[] = $this->where_get_in_str_query("t.subject",$subject);
        $where_arr[] = $this->where_get_in_str_query("s.grade",$grade);

        $sql = $this->gen_sql_new("select t.subject,s.grade,s.phone,tr.require_id,t.stu_request_test_lesson_time, "
                                  ." s.editionid,t.textbook"
                                  ." from %s tr "
                                  ." left join %s t on tr.test_lesson_subject_id=t.test_lesson_subject_id "
                                  ." left join %s s on t.userid=s.userid "
                                  ." where %s "
                                  ." order by stu_request_test_lesson_time asc"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_jw_no_plan_time_list($accept_adminid){
        $time= time();
        $where_arr=[
            ["tr.accept_adminid=%u",$accept_adminid,-1],
            "tr.test_lesson_student_status =200",
            "tr.accept_adminid >0",
            "t.stu_request_test_lesson_time>".$time
        ];
        $sql = $this->gen_sql_new("select tr.accept_adminid,sum((t.stu_request_test_lesson_time-%s)<6*3600) six_count,sum((t.stu_request_test_lesson_time-%s)<4*3600) four_count,sum((t.stu_request_test_lesson_time-%s)<2*3600) two_count,m.account"
                                  ." from %s tr left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." left join %s m on tr.accept_adminid=m.uid"
                                  ." where %s group by tr.accept_adminid",
                                  $time,
                                  $time,
                                  $time,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_info($userid){
        $sql = $this->gen_sql_new(" select require_id"
                                  ." from %s tr left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." where t.userid = %u",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_list($sql);
    }
    public function get_userid($require_id) {
        $sql=$this->gen_sql_new(
            " select userid  from  %s tr "
            . " join %s  t on tr.test_lesson_subject_id = t.test_lesson_subject_id "
            . " where require_id=%d  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $require_id );
        return $this->main_get_value($sql);
    }

    public function get_count($start_time,$end_time){
        $where_arr = [
            ["require_time>%u",$start_time,0],
            ["require_time<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select count(1) "
                                  ." from %s tr"
                                  ." left join %s t on tr.test_lesson_subject_id=t.test_lesson_subject_id"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_limit_require_list($time){
        $where_arr = [
            //["limit_accept_time>=%u",$time,-1],
            "limit_accept_time>=".(time()-300),
            "limit_accept_time<=".(time()-120),
            "limit_require_flag=1",
            "limit_accept_flag=1",
            "(current_lessonid=0 or current_lessonid='' or current_lessonid is null)"
        ];
        $sql = $this->gen_sql_new("select tr.accept_adminid,s.grade,t.subject,tr.limit_require_teacherid,tr.limit_require_lesson_start,tr.current_lessonid,tr.require_id,tr.test_lesson_subject_id,t.userid,s.nick,limit_require_send_adminid,limit_require_adminid "
                                  ." from %s tr left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." left join %s s on t.userid= s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_month_limit_require_num($master_adminid,$start_time,$end_time){
        $where_arr=[
            ["tr.limit_require_send_adminid=%u",$master_adminid,-1],
            "tr.limit_require_flag=1",
            "tr.limit_accept_flag in (0,1)",
            "(tss.success_flag in (0,1) or tss.success_flag is null)",
            "(l.lesson_del_flag=0 or l.lesson_del_flag is null)"
        ];
        $this->where_arr_add_time_range($where_arr,"tr.limit_require_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(*) from %s tr"
                                  ." left join %s tss on tr.current_lessonid = tss.lessonid"
                                  ." left join %s l on tr.current_lessonid = l.lessonid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_jw_openid($lessonid){
        $where_arr=[
            ["l.lessonid=%d",$lessonid ],
        ];
        $sql=$this->gen_sql_new(
            "select m.wx_openid  "
            ." from  %s r  "
            ." inner join %s sl on r.require_id = sl.require_id  "
            ." inner join %s l on sl.lessonid = l.lessonid "
            ." inner join %s m on m.uid = r.accept_adminid where %s ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_tran_require_info($start_time,$end_time){
        $where_arr=[
            "origin like '%%转介绍%%'",
            "m.account_role=1",
            "m.del_flag=0",
            "accept_flag <>2"
        ];
        $this->where_arr_add_time_range($where_arr,"tr.require_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(*) num,tr.cur_require_adminid "
                                  ." from %s tr left join %s m on tr.cur_require_adminid = m.uid"
                                  ." where %s group by tr.cur_require_adminid",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["cur_require_adminid"];
        });
    }

    public function get_all_kk_require_list($page_num,$userid,$teacherid,$subject){
        $where_arr=[
            ["l.subject=%u",$subject,-1],
            ["l.userid=%u",$userid,-1],
            ["l.teacherid=%u",$teacherid,-1],
            "t.ass_test_lesson_type=1"
        ];
        $sql = $this->gen_sql_new("select require_id,require_time,l.lesson_start,m.account "
                                  ." from %s tr  left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id "
                                  ." left join %s l on tr.current_lessonid = l.lessonid"
                                  ." left join %s m on tr.cur_require_adminid = m.uid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }



    public function get_tmk_lesson_count( $field_name, $start_time,$end_time,$tmk_adminid=-1,$origin_level=-1,$wx_invaild_flag){

        $this->switch_tongji_database();

        $where_arr = [];

        $where_arr=[
            "tmk_adminid>0",
            "lesson_del_flag=0",
            "lesson_status=2",
        ];

        $this->where_arr_add_int_or_idlist($where_arr,"s.origin_level",$origin_level);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        //wx
        $this->where_arr_add_int_field($where_arr,"wx_invaild_flag",$wx_invaild_flag);

        $sql=$this->gen_sql_new(
            "select $field_name  as check_value , count(*) as tmk_count, "
            ." sum(  success_flag in (0,1 ) ) as succ_test_lesson_count  "
            ." from %s tr "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s n  on t.userid=n.userid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s s on s.userid = l.userid "
            ." where %s and lesson_start >=%u and lesson_start<%u and accept_flag=1  "
            ." and is_test_user=0 "
            ." and require_admin_type = 2 and l.lesson_type=2 "
            ." group by check_value " ,
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr,$start_time,$end_time );

        return $this->main_get_list($sql);
    }
    public function get_require_noset_lesson_count($start_time, $end_time, $adminid_list) {
        $where_arr=[
            "test_lesson_student_status=200 ",
        ];
        if ($adminid_list) {
            $this->where_arr_add_int_or_idlist($where_arr,"require_adminid",$adminid_list);
        }
        $this->where_arr_add_time_range($where_arr,"stu_request_test_lesson_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select require_adminid as adminid,stu_request_test_lesson_time as opt_time "
            . " from %s tr "
            . " left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            . " where  %s ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_need_require_lesson_count($start_time, $end_time, $adminid_list)
    {
        $where_arr=[
            "test_lesson_student_status<>110 ",
        ];
        if ($adminid_list) {
            $this->where_arr_add_int_or_idlist($where_arr,"require_adminid",$adminid_list);
        }
        $this->where_arr_add_time_range($where_arr,"stu_request_test_lesson_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select require_adminid as adminid,stu_request_test_lesson_time as opt_time "
            . " from %s tr "
            . " left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            . " where  %s ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function set_info($stu_lesson_content,$stu_lesson_status,$stu_study_status,$stu_advantages,
                             $stu_disadvantages,$stu_lesson_plan,$stu_teaching_direction, $stu_advice,
                             $requireid){

        $sql = $this->gen_sql_new("update %s t set t.stu_lesson_content = '%s'," .
                                  "t.stu_lesson_status = '%s'," .
                                  "t.stu_study_status = '%s'," .
                                  "t.stu_advantages = '%s'," .
                                  "t.stu_disadvantages = '%s'," .
                                  "t.stu_lesson_plan = '%s'," .
                                  "t.stu_teaching_direction = '%s'," .
                                  "t.stu_advice ='%s'" .
                                  "where t.require_id = %d",
                                  self::DB_TABLE_NAME,
                                  $stu_lesson_content,
                                  $stu_lesson_status,
                                  $stu_study_status,
                                  $stu_advantages,
                                  $stu_disadvantages,
                                  $stu_lesson_plan,
                                  $stu_teaching_direction,
                                  $stu_advice,
                                  $requireid
        );

        return $this->main_update($sql);
    }

    public function get_info ( $requireid) {
        $sql = $this->gen_sql_new("select t.stu_lesson_content, t.stu_lesson_status,t.stu_study_status,t.stu_advantages,t.stu_disadvantages,".
                                  "t.stu_lesson_plan, t.stu_teaching_direction,t.stu_advice " .
                                  "from %s t where t.require_id = %d",
                                  self::DB_TABLE_NAME,
                                  $requireid
        );
        return $this->main_get_list($sql);
    }

    public function get_list_by_id_list($require_id_list){
        $sql = $this->gen_sql_new(
            "select tss.confirm_time,tss.confirm_adminid , l.lessonid, tr.accept_flag , t.require_admin_type, s.origin_userid , t.ass_test_lesson_type, stu_score_info, stu_character_info , s.school, s.editionid, stu_test_lesson_level, stu_test_ipad_flag, stu_request_lesson_time_info,  stu_request_test_lesson_time_info, tr.require_id, t.test_lesson_subject_id ,ss.add_time, test_lesson_student_status,  s.userid,s.nick, tr.origin, ss.phone_location, ss.phone,ss.userid, t.require_adminid,  tr.curl_stu_request_test_lesson_time stu_request_test_lesson_time ,  test_stu_request_test_lesson_demand as  stu_request_test_lesson_demand ,  s.origin_assistantid , s.origin_userid  ,  t.subject, tr.test_stu_grade as grade,ss.user_desc, ss.has_pad, ss.last_revisit_time, ss.last_revisit_msg,tq_called_flag,next_revisit_time,l.lesson_start,l.lesson_del_flag,tr.require_time,l.teacherid, t.stu_test_paper, t.tea_download_paper_time, test_lesson_student_status, tss.success_flag, tss.fail_greater_4_hour_flag, tss.test_lesson_fail_flag, tss.fail_reason,tr.seller_require_change_flag, tr.require_change_lesson_time,tr.seller_require_change_time , assigned_lesson_count ,tr.accept_adminid, jw_test_lesson_status,set_lesson_time,tr.green_channel_teacherid,tc.cancel_time,t.textbook,tr.cur_require_adminid, tr.grab_status,tr.current_lessonid,tr.is_green_flag,tr.limit_require_flag,tr.limit_require_teacherid ,  tr.limit_require_lesson_start ,tr.limit_require_time,tr.limit_require_adminid ,tr.limit_require_send_adminid, tr.limit_accept_flag,tr.limit_require_reason,tr.limit_accept_time "
            ." from  %s tr "
            . " left join %s  t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            . " left join %s  ss on  t.userid = ss.userid "
            . " left join %s s on  t.userid = s.userid "
            . " left join %s tss on  tr.current_lessonid = tss.lessonid  "
            . " left join %s l on  tss.lessonid = l.lessonid "
            . " left join %s c on  tss.lessonid = c.ass_from_test_lesson_id "
            . " left join %s tc on tr.current_lessonid=tc.lessonid  "
            ."  where tr.require_id in( %s ) and accept_flag in(0,1)  ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            t_teacher_cancel_lesson_list::DB_TABLE_NAME,
            $require_id_list.join(",")  );
        return $this->main_get_list($sql);
    }
    public function get_lesson_content($lessonid){
        $sql=$this->gen_sql_new("select stu_lesson_content"
                                ." from %s t"
                                ." left join %s l on l.require_id=t.require_id"
                                ." where l.lessonid=%u"
                                ,self::DB_TABLE_NAME
                                ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                ,$lessonid
        );
        return $this->main_get_value($sql);
    }
    public function get_teacher_cancel_count(){
        //$sql = $this->get_sql_new("");
        $sql = "SELECT test_lesson_order_fail_flag, test_lesson_order_fail_desc, test_lesson_order_fail_set_time, tmk_adminid, tss.confirm_time, tss.confirm_adminid, l.lessonid, tr.accept_flag, t.require_admin_type, s.origin_userid
    , t.ass_test_lesson_type, stu_score_info, stu_character_info, s.school, s.editionid
    , stu_test_lesson_level, stu_test_ipad_flag, stu_request_lesson_time_info, stu_request_test_lesson_time_info, tr.require_id
    , t.test_lesson_subject_id, ss.add_time, test_lesson_student_status, s.userid, s.nick
    , tr.origin, ss.phone_location, ss.phone, ss.userid, t.require_adminid
    , tr.curl_stu_request_test_lesson_time AS stu_request_test_lesson_time, test_stu_request_test_lesson_demand AS stu_request_test_lesson_demand, s.origin_assistantid, s.origin_userid, t.subject
    , tr.test_stu_grade AS grade, ss.user_desc, ss.has_pad, ss.last_revisit_time, ss.last_revisit_msg
    , tq_called_flag, next_revisit_time, l.lesson_start, l.lesson_del_flag, tr.require_time
    , l.teacherid, t.stu_test_paper, t.tea_download_paper_time, test_lesson_student_status, tss.success_flag
    , tss.fail_greater_4_hour_flag, tss.test_lesson_fail_flag, tss.fail_reason, tr.seller_require_change_flag, tr.require_change_lesson_time
    , tr.seller_require_change_time, assigned_lesson_count, tr.accept_adminid, jw_test_lesson_status, set_lesson_time
    , tr.green_channel_teacherid, tc.cancel_time, t.textbook, tr.cur_require_adminid, tr.grab_status
    , tr.current_lessonid, tr.is_green_flag, tr.limit_require_flag, tr.limit_require_teacherid, tr.limit_require_lesson_start
    , tr.limit_require_time, tr.limit_require_adminid, tr.limit_require_send_adminid, tr.limit_accept_flag, tr.limit_require_reason
    , tr.limit_accept_time
FROM db_weiyi.t_test_lesson_subject_require tr
    LEFT JOIN db_weiyi.t_test_lesson_subject t ON t.test_lesson_subject_id = tr.test_lesson_subject_id
    LEFT JOIN db_weiyi.t_seller_student_new ss ON t.userid = ss.userid
    LEFT JOIN db_weiyi.t_student_info s ON t.userid = s.userid
    LEFT JOIN db_weiyi.t_test_lesson_subject_sub_list tss ON tr.current_lessonid = tss.lessonid
    LEFT JOIN db_weiyi.t_lesson_info l ON tss.lessonid = l.lessonid
    LEFT JOIN db_weiyi.t_course_order c ON tss.lessonid = c.ass_from_test_lesson_id
    LEFT JOIN db_weiyi.t_teacher_cancel_lesson_list tc ON tr.current_lessonid = tc.lessonid
WHERE s.is_test_user = 0
    AND tr.accept_flag <> 2
    AND require_time >= 1501084800
    AND require_time < 1501776000
ORDER BY require_time ASC";
        dd($sql);
        return $this->main_get_list_by_page($sql,10);
    }

    public function tongji_test_lesson_order($group_by_field,$start_time, $end_time, $cur_require_adminid ,$origin_ex , $teacherid ) {
        $where_arr=[
            "s.is_test_user=0"
            ," l.teacherid>0"
            ,"l.lesson_user_online_status=1"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        if ($group_by_field =="origin" ) {
            $group_by_field="s.origin";
        }

        $this->where_arr_add_int_or_idlist($where_arr,"cur_require_adminid",$cur_require_adminid);
        $this->where_arr_add_int_or_idlist($where_arr,"l.teacherid",$teacherid);
        $where_arr[]= $this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $sql=$this->gen_sql_new(
            "select $group_by_field as field_name, count(*) as test_lesson_count , sum(o.price>0) as order_count ,"
            . " sum(o.price )  as order_money " .
            " from  db_weiyi.t_test_lesson_subject_require tr ".
            " left join db_weiyi.t_test_lesson_subject t on t.test_lesson_subject_id = tr.test_lesson_subject_id ".
            " left join db_weiyi.t_student_info s on  t.userid = s.userid ".
            " left join db_weiyi.t_lesson_info l on tr.current_lessonid = l.lessonid ".
            " left join db_weiyi.t_order_info o on  (l.lessonid = o.from_test_lesson_id and o.contract_status>0 ) ".
            "  where %s group by $group_by_field ",
            $where_arr);
        return $this->main_get_list_as_page($sql);
    }

    public function test_lesson_order_detail_list($page_info, $start_time, $end_time, $cur_require_adminid ,$origin_ex , $teacherid ) {

        $where_arr=[
            "s.is_test_user=0"
            ," l.teacherid>0"
            ,"l.lesson_user_online_status=1"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);


        $this->where_arr_add_int_or_idlist($where_arr,"cur_require_adminid",$cur_require_adminid);
        $this->where_arr_add_int_or_idlist($where_arr,"l.teacherid",$teacherid);
        $where_arr[]= $this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");

        $sql=$this->gen_sql_new(
            "select m.account , s.phone, s.phone_location, l.grade, l.subject, tea.nick as tea_nick ,s.nick as stu_nick , tr.cur_require_adminid, from_unixtime( tr.require_time) require_time , from_unixtime( l.lesson_start) lesson_start, "
            . "  from_unixtime( l.lesson_start) lesson_time ,   o.price/100 as price ,  s.origin_userid, s.origin,"
            . " s.userid, l.teacherid" .
            " from  db_weiyi.t_test_lesson_subject_require tr ".

            " left join db_weiyi.t_test_lesson_subject t on t.test_lesson_subject_id = tr.test_lesson_subject_id ".
            " left join db_weiyi.t_student_info s on  t.userid = s.userid ".
            " left join db_weiyi.t_lesson_info l on tr.current_lessonid = l.lessonid ".
            " left join db_weiyi.t_order_info o on  (l.lessonid = o.from_test_lesson_id and o.contract_status>0 ) ".
            " left join db_weiyi.t_teacher_info tea on tea.teacherid=l.teacherid".
            " left join db_weiyi_admin.t_manager_info m on m.uid=tr.cur_require_adminid".
            "  where %s ",
            $where_arr);
        return $this->main_get_list_by_page($sql,$page_info);
    }




    public function tongji_test_lesson_origin_jx( $field_name, $start_time,$end_time,$adminid_list=[],$tmk_adminid=-1,$origin_ex=""){
        switch ( $field_name ) {
        case "origin" :
            $field_name="tr.origin";
            break;

        case "grade" :
            $field_name="l.grade";
            break;

        case "subject" :
            $field_name="l.subject";
            break;
        default:
            break;
        }

        $where_arr=[];
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"tr.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $sql=$this->gen_sql_new(
            "select $field_name  as check_value , "
            ." sum(lesson_user_online_status = 1 or flow_status=1  ) as succ_test_lesson_count  "
            ." from %s tr "
            ." join %s t  on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." join %s n  on t.userid=n.userid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s s on s.userid = l.userid "
            ." left join %s f on f.from_key_int = l.lessonid"
            ." where %s and lesson_start >=%u and lesson_start<%u and accept_flag=1  "
            ." and is_test_user=0 and l.lesson_type=2 "
            ." and require_admin_type = 2 "
            ." group by check_value " ,
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr,$start_time,$end_time );

        return $this->main_get_list($sql);
    }

    public function get_test_lesson_require_row($start_time,$end_time,$adminid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr,'t.require_adminid',$adminid);
        $this->where_arr_add_time_range($where_arr,'tr.require_time',$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select tr.require_id "
            ." from %s tr "
            ." left join %s t on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." where %s limit 1 "
            ,self::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_seller_top_list(){
        $sql = $this->gen_sql_new("select * from %s where seller_top_flag=1",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_seller_top_require_num($start_time,$end_time,$cur_require_adminid){
        $where_arr = [
            ["cur_require_adminid=%u",$cur_require_adminid,-1],
            "accept_flag<2",
            "seller_top_flag=1"
        ];
        $this->where_arr_add_time_range($where_arr,'curl_stu_request_test_lesson_time',$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(*) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_no_high_require(){
        $sql = $this->gen_sql_new("select * from %s tr"
                                  ." left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." where tr.seller_top_flag=1 and tr.intention_level <>1",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }

    public function get_invit_num($start_time, $end_time){ //获取邀约数
        $where_arr = [
            "s.is_test_user=0",
            "ts.require_admin_type=2"
        ];

        $this->where_arr_add_time_range($where_arr,"tr.require_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select count(tr.require_id) from %s tr "
                                  ." left join %s ts on ts.test_lesson_subject_id=tr.test_lesson_subject_id "
                                  ." left join %s s on ts.userid=s.userid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }


    public function get_plan_invit_num_for_month($start_time, $end_time){
        $where_arr = [
            "s.is_test_user=0",
            "t.require_admin_type=2",
        ];

        // $this->where_arr_add_time_range($where_arr,"tss.set_lesson_time",$start_time,$end_time);
        $this->where_arr_add_time_range($where_arr,"tr.require_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(
            "select count(tr.require_id) "
            ." from  %s tr "
            ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ." left join %s ss on  t.userid = ss.userid "
            ." left join %s s on  t.userid = s.userid "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." left join %s c on  tss.lessonid = c.ass_from_test_lesson_id "
            ." left join %s tc on tr.current_lessonid=tc.lessonid "
            ." left join %s tea on tea.teacherid=tr.limit_require_teacherid "
            ." where  %s  "
            , t_test_lesson_subject_require::DB_TABLE_NAME//tr
            , t_test_lesson_subject::DB_TABLE_NAME//t
            , t_seller_student_new::DB_TABLE_NAME//ss
            , t_student_info::DB_TABLE_NAME//s
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME//tss
            , t_lesson_info::DB_TABLE_NAME//l
            , t_course_order::DB_TABLE_NAME//c
            , t_teacher_cancel_lesson_list::DB_TABLE_NAME//tc
            , t_teacher_info::DB_TABLE_NAME//tea
            ,$where_arr
        );

        return $this->main_get_value($sql);
    }


    public function get_invit_num_for_month($start_time, $end_time){ //获取邀约数
        $where_arr = [
            "s.is_test_user=0",
            "ts.require_admin_type=2",
            "tq.is_called_phone=1",
            "tq.admin_role=2"
        ];

        $this->where_arr_add_time_range($where_arr,"ss.add_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select count(tr.require_id) from %s tr "
                                  ." left join %s ts on ts.test_lesson_subject_id=tr.test_lesson_subject_id "
                                  ." left join %s s on ts.userid=s.userid"
                                  ." left join %s ss on ss.userid=ts.userid"
                                  ." left join %s tq on ss.phone=tq.phone"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME //ts
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,t_tq_call_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }



    public function get_seller_schedule_num($start_time, $end_time ){// 试听排课数
        $where_arr = [
            "s.is_test_user=0",
            "tr.accept_flag=1",
            "t.require_admin_type=2",
            "l.lesson_del_flag = 0",
            "tss.fail_greater_4_hour_flag=0"
        ];

        $this->where_arr_add_time_range($where_arr,"tss.set_lesson_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(
            "select count(tss.lessonid) "
            ." from  %s tr "
            ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ." left join %s ss on  t.userid = ss.userid "
            ." left join %s s on  t.userid = s.userid "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." left join %s c on  tss.lessonid = c.ass_from_test_lesson_id "
            ." left join %s tc on tr.current_lessonid=tc.lessonid "
            ." left join %s tea on tea.teacherid=tr.limit_require_teacherid "
            ." where  %s  "
            , t_test_lesson_subject_require::DB_TABLE_NAME//tr
            , t_test_lesson_subject::DB_TABLE_NAME//t
            , t_seller_student_new::DB_TABLE_NAME//ss
            , t_student_info::DB_TABLE_NAME//s
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME//tss
            , t_lesson_info::DB_TABLE_NAME//l
            , t_course_order::DB_TABLE_NAME//c
            , t_teacher_cancel_lesson_list::DB_TABLE_NAME//tc
            , t_teacher_info::DB_TABLE_NAME//tea
            ,$where_arr
        );

        return $this->main_get_value($sql);

    }

    public function get_seller_schedule_num_month($start_time, $end_time ){// 试听排课数
        $where_arr = [
            "s.is_test_user=0",
            "tr.accept_flag=1",
            "t.require_admin_type=2",
        ];

        $this->where_arr_add_time_range($where_arr,"tr.require_time",$start_time,$end_time);

        $sql = $this->gen_sql_new(
            "select  count(distinct(tss.lessonid)) "
            ." from  %s tr "
            ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ." left join %s ss on  t.userid = ss.userid "
            ." left join %s s on  t.userid = s.userid "
            ." left join %s tss on  tr.current_lessonid = tss.lessonid "
            ." left join %s l on  tss.lessonid = l.lessonid "
            ." left join %s c on  tss.lessonid = c.ass_from_test_lesson_id "
            ." left join %s tc on tr.current_lessonid=tc.lessonid "
            ." left join %s tea on tea.teacherid=tr.limit_require_teacherid "
            ." where  %s  "
            , t_test_lesson_subject_require::DB_TABLE_NAME//tr
            , t_test_lesson_subject::DB_TABLE_NAME//t
            , t_seller_student_new::DB_TABLE_NAME//ss
            , t_student_info::DB_TABLE_NAME//s
            , t_test_lesson_subject_sub_list::DB_TABLE_NAME//tss
            , t_lesson_info::DB_TABLE_NAME//l
            , t_course_order::DB_TABLE_NAME//c
            , t_teacher_cancel_lesson_list::DB_TABLE_NAME//tc
            , t_teacher_info::DB_TABLE_NAME//tea
            ,$where_arr
        );



        // $sql = $this->gen_sql_new("  select count(distinct(tss.lessonid)) from %s tr "
        //                           ." left join %s ts on ts.test_lesson_subject_id=tr.test_lesson_subject_id "
        //                           ." left join %s tss on tss.require_id=tr.require_id  "
        //                           ." left join %s l on l.lessonid=tss.lessonid"
        //                           ." left join %s s on ts.userid=s.userid"
        //                           ." where %s"
        //                           ,self::DB_TABLE_NAME
        //                           ,t_test_lesson_subject::DB_TABLE_NAME
        //                           ,t_test_lesson_subject_sub_list::DB_TABLE_NAME // tss
        //                           ,t_lesson_info::DB_TABLE_NAME// l
        //                           ,t_student_info::DB_TABLE_NAME
        //                           ,$where_arr
        // );

        return $this->main_get_value($sql);

    }

    public function get_cur_require_adminid_by_lessonid($lessonid){
        $sql = $this->gen_sql_new("  select m.wx_openid from %s tr"
                                  ." left join %s tss on tss.require_id=tr.require_id"
                                  ." left join %s m on m.uid=tr.cur_require_adminid"
                                  ." where tss.lessonid=%d"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$lessonid
        );

        return $this->main_get_value($sql);
    }

    public function get_all_lsit($start_time,$end_time,$origin_ex){
        $where_arr = [
            'accept_flag=1',
            'require_admin_type=2',
            'is_test_user=0',
        ];
        if($origin_ex){
            $where_arr[] = ['s.origin = %s',$origin_ex,-1];
        }
        $this->where_arr_add_time_range($where_arr,'l.lesson_start',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select "
            ."cur_require_adminid adminid,count(*) count,"
            ."sum(lesson_user_online_status in (0, 1) or f.flow_status = 2) suc_count,"
            ."sum(if((lesson_user_online_status in (0, 1) or f.flow_status = 2) and n.test_lesson_opt_flag=1,1,0)) test_count, "
            ."sum(if(l.on_wheat_flag=1,1,0)) wheat_count "
            ."from %s tr "
            ."left join %s l on tr.current_lessonid = l.lessonid "
            ."left join %s tss on tr.current_lessonid = tss.lessonid "
            ."left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id "
            ."left join %s s on l.userid = s.userid "
            ."left join %s n on n.userid = s.userid "
            ."left join %s f on f.flow_type = 2003 and l.lessonid = f.from_key_int "
            ." where %s group by cur_require_adminid "
            ,self::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_flow::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_require_info_by_requireid($requireid_list){
        $where_arr=[];
        $where_arr[]=$this->where_get_in_str( "require_id", $requireid_list);
        $sql =$this->gen_sql_new("select require_id,accept_adminid"
                                 ." from %s where %s",
                                 self::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_planed_lesson_num($requireid_list,$accept_adminid,$start_time,$end_time){
        $where_arr=[
            ["tr.accept_adminid=%u",$accept_adminid,-1],
            "tr.test_lesson_student_status in(210,220,290,300,301,302,420)"
        ];
        $this->where_arr_add_time_range($where_arr,"tss.set_lesson_time",$start_time,$end_time);
        $where_arr[]=$this->where_get_not_in_str( "tr.require_id", $requireid_list);
        $sql =$this->gen_sql_new("select count(*) num"
                                 ." from %s tr left join %s tss on tr.current_lessonid = tss.lessonid"
                                 ." where %s",
                                 self::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_test_list($now){
        $end = $now + 60;
        $where_arr = [
            "tls.current_lessonid is null",
            "tl.require_adminid>0",
            "tls.accept_flag =0 ",
            "s.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"tls.curl_stu_request_test_lesson_time",$now,$end);

        $sql = $this->gen_sql_new("  select tls.curl_stu_request_test_lesson_time stu_request_test_lesson_time, tls.require_id, tls.current_lessonid, tl.subject, tl.grade, s.nick, tl.require_adminid,tls.require_time,m.wx_openid, m.account from %s tls"
                                  ." left join %s tl on tl.test_lesson_subject_id=tls.test_lesson_subject_id"
                                  ." left join %s m on m.uid=tl.require_adminid"
                                  ." left join %s s on s.userid=tl.userid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    /**
     * 拉取试听需求的内容
     */
    public function get_require_list_by_requireid($require_id){
        $where_arr = [
            ["tr.require_id=%u",$require_id,-1]
        ];
        $sql = $this->gen_sql_new("select s.nick,s.gender,s.grade,t.subject,t.textbook,t.stu_request_test_lesson_time_end,"
                                  ." tr.curl_stu_request_test_lesson_time_end,tr.curl_stu_request_test_lesson_time,"
                                  ." t.teacher_type,tr.accept_status,tr.require_id,"
                                  ." tr.test_stu_request_test_lesson_demand,t.tea_identity,t.tea_gender,t.tea_age,"
                                  ." t.intention_level,t.quotation_reaction,tr.seller_top_flag,t.subject_tag,tr.current_lessonid,"
                                  ." tr.test_lesson_student_status,tr.green_channel_teacherid"
                                  ." from %s tr "
                                  ." left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." left join %s s on t.userid = s.userid"
                                  ." left join %s n on t.userid = n.userid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    //@desn:获取已不同时间检索的漏斗数据
    public function get_funnel_data(
        $field_name,$opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1
    ){
        if($field_name == 'grade')
            $field_name="si.grade";
        elseif($field_name == 'origin')
            $field_name = 'tlsr.origin';
        elseif($field_name == 'subject')
            $field_name = 'tls.subject';

        $where_arr=[
            ["tlsr.origin like '%%%s%%' ",$origin,""],
            'tls.require_admin_type=2',
            // 'tlsr.accept_flag = 1',
            'si.is_test_user = 0',
            'li.lesson_type = 2'
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"ssn.first_seller_adminid",$adminid_list);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value,count(tlsr.require_id) require_count,".
            " count(if(tlsr.accept_flag = 1,tlsr.require_id,null)) as test_lesson_count,".
            " count(distinct if(tlsr.accept_flag = 1,tls.userid,null)) distinct_test_count,".
            " sum(tlssl.success_flag in (0,1 )) succ_test_lesson_count,".
            " sum(if((oi.contract_type = 0 and oi.contract_status > 0),1,0)) order_count,".
            " round(sum(if((oi.contract_type = 0 and oi.contract_status > 0 ),oi.price/100,0))) order_all_money,".
            " coutn(distinct if(tlssl.success_flag in (0,1 ),tls.userid,null)) succ_test_lesson_count".
            " from %s tlsr ".
            " left join %s tlssl on tlsr.current_lessonid=tlssl.lessonid ".
            " left join %s li on tlsr.current_lessonid = li.lessonid".
            " left join %s tls on tlsr.test_lesson_subject_id = tls.test_lesson_subject_id".
            " left join %s oi on tls.userid= oi.userid ".
            " left join %s ssn on tls.userid=ssn.userid ".
            " left join %s si on tls.userid = si.userid ".
            " where %s group by check_value",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });

    }
    //@desn:获取不重复上课数
    public function get_distinct_class( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        if($field_name == 'grade')
            $field_name="si.grade";
        elseif($field_name == 'origin')
            $field_name = 'tlsr.origin';
        elseif($field_name == 'subject')
            $field_name = 'tls.subject';

        $where_arr=[
            ["tlsr.origin like '%%%s%%' ",$origin,""],
            'tls.require_admin_type=2',
            'tlsr.accept_flag = 1',
            'si.is_test_user = 0',
            'tlssl.success_flag in (0,1)',
            'li.lesson_type = 2'
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"ssn.first_seller_adminid",$adminid_list);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value,count(tls.userid) distinct_succ_count".
            " from %s tlsr ".
            " left join %s tlssl on tlsr.current_lessonid=tlssl.lessonid ".
            " left join %s li on tlsr.current_lessonid = li.lessonid".
            " left join %s tls on tlsr.test_lesson_subject_id = tls.test_lesson_subject_id".
            " left join %s ssn on tls.userid=ssn.userid ".
            " left join %s si on tls.userid = si.userid ".
            " where %s group by check_value",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取不重复订单数
    public function get_distinct_order_info( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        if($field_name == 'grade')
            $field_name="si.grade";
        elseif($field_name == 'origin')
            $field_name = 'tlsr.origin';
        elseif($field_name == 'subject')
            $field_name = 'tls.subject';

        $where_arr=[
            ["tlsr.origin like '%%%s%%' ",$origin,""],
            'tls.require_admin_type=2',
            'tlsr.accept_flag = 1',
            'si.is_test_user = 0',
            'li.lesson_type = 2',
            'oi.contract_type = 0',
            'oi.contract_status > 0',
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"ssn.first_seller_adminid",$adminid_list);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value,count(distinct(oi.userid)) user_count".
            " from %s tlsr ".
            " left join %s tlssl on tlsr.current_lessonid=tlssl.lessonid ".
            " left join %s li on tlsr.current_lessonid = li.lessonid".
            " left join %s tls on tlsr.test_lesson_subject_id = tls.test_lesson_subject_id".
            " left join %s oi on tls.userid= oi.userid ".
            " left join %s ssn on tls.userid=ssn.userid ".
            " left join %s si on tls.userid = si.userid ".
            " where %s group by check_value",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取节点型试听课统计数据
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    public function get_test_lesson_data($start_time,$end_time){
        $where_arr = [
            ['tls.require_admin_type = %u',2],
            ['li.lesson_type = %u',2],
            ['si.is_test_user = %u',0],
        ];
        $this->where_arr_add_time_range($where_arr, 'tlsr.require_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select tlsr.origin as channel_name,count(tlsr.require_id) as require_count,'.
            'count(tlsr.accept_flag = 1) test_lesson_count,'.
            'sum(tlssl.success_flag in (0,1 )) as succ_test_lesson_count,'.
            'count(distinct if(tlsr.accept_flag = 1,tls.userid,null)) as distinct_test_count,'.
            'count(distinct if(tlssl.success_flag in (0,1 ),tls.userid,null)) as distinct_succ_count '.
            'from %s tlsr '.
            'left join %s tls on tlsr.test_lesson_subject_id = tls.test_lesson_subject_id '.
            'left join %s tlssl on tlssl.require_id = tlsr.require_id '.
            'left join %s li on tlsr.current_lessonid=li.lessonid '.
            'left join %s si on tls.userid=si.userid '.
            'where %s group by tlsr.origin',
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取节点型试听课统计数据
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    //@param:$opt_date_str 检索时间类型
    public function get_test_lesson_data_now($origin='', $field_name, $start_time,$end_time,$adminid_list=[],$tmk_adminid=-1,$origin_ex="" ){
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
            'si.is_test_user=0',
            'li.lesson_del_flag=0'
        ];
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"si.origin");
        $where_arr[]= $ret_in_str;
        // $this->where_arr_adminid_in_list($where_arr,"tls.require_adminid",$adminid_list);

        $this->where_arr_add__2_setid_field($where_arr,"ssn.tmk_adminid",$tmk_adminid);
        $this->where_arr_add_time_range($where_arr, 'li.lesson_start', $start_time, $end_time);

        $sql = $this->gen_sql_new(
            'select '.$field_name.' as check_value,count(tlsr.require_id) as require_count,'.
            'count(tlsr.accept_flag = 1) test_lesson_count,'.
            'sum(tlssl.success_flag in (0,1 ) and ((li.lesson_user_online_status in (0,1) or f.flow_status = 2)) '.
            'and tlsr.accept_flag=1) as succ_test_lesson_count,'.
            'count(distinct if(tlsr.accept_flag = 1,tls.userid,null)) as distinct_test_count,'.
            'count(distinct if((tlssl.success_flag in (0,1 ) and (li.lesson_user_online_status in (0,1) or f.flow_status = 2) '.
            'and tlsr.accept_flag=1),tls.userid,null)) as distinct_succ_count '.
            'from %s tlsr '.
            'left join %s tls on tlsr.test_lesson_subject_id = tls.test_lesson_subject_id '.
            'left join %s tlssl on tlssl.lessonid = tlsr.current_lessonid '.
            'left join %s li on tlsr.current_lessonid=li.lessonid '.
            'left join %s si on li.userid=si.userid '.
            'left join %s ssn on tls.userid = ssn.userid '.
            'left join %s f on f.flow_type=2003 and li.lessonid= f.from_key_int '.
            'where %s group by check_value',
            self::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_test_count_by_adminid($start_time,$end_time,$adminid=-1){
        $where_arr=[
            "accept_flag=1",
            "require_admin_type=2",
            "is_test_user=0",
            "l.lesson_del_flag=0",
        ];
        $this->where_arr_add_int_field($where_arr,"cur_require_adminid",$adminid);
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            " select cur_require_adminid as admin_revisiterid,count(*) test_lesson_count,"
            ." sum(lesson_user_online_status in (0,1) or f.flow_status = 2) succ_all_count "
            ." from %s tr "
            ." left join %s l on tr.current_lessonid=l.lessonid "
            ." left join %s tss on tr.current_lessonid=tss.lessonid "
            ." left join %s t on tr.test_lesson_subject_id=t.test_lesson_subject_id "
            ." left join %s s on l.userid=s.userid"
            ." left join %s f on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
            ." where %s ",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_item_count($start_time,$end_time,$adminid_list=[]) {
        $where_arr=[
            "accept_flag=1",
            "is_test_user=0",
            "l.lesson_del_flag=0",
        ];
        $this->where_arr_add_int_or_idlist($where_arr, 'cur_require_adminid', $adminid_list);
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select cur_require_adminid admin_revisiterid, count(*) test_lesson_count,"
            ."sum(lesson_user_online_status in (0,1) or f.flow_status = 2) succ_all_count "
            ." from %s tr "
            ." join %s l on tr.current_lessonid=l.lessonid "
            ." join %s tss on tr.current_lessonid=tss.lessonid "
            ." join %s s on l.userid=s.userid"
            ." left join %s f on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
            ." where %s "
            ." group by  cur_require_adminid ",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_list($adminid,$userid){
        $where_arr=[
            "accept_flag=1",
            "l.lesson_del_flag=0",
            "tss.call_end_time=0 or tss.call_before_time=0",
        ];
        $this->where_arr_add_int_field($where_arr, 'cur_require_adminid', $adminid);
        $this->where_arr_add_int_field($where_arr, 'l.userid', $userid);

        $sql=$this->gen_sql_new(
            "select tss.lessonid,tss.call_before_time,tss.call_end_time,"
            ."l.lesson_start,l.lesson_end "
            ." from %s tr "
            ." left join %s tss on tss.require_id=tr.require_id "
            ." left join %s l on tss.lessonid=l.lessonid "
            ." where %s ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取系统分配销售的试听成功数
    //@param:$begin_time,$end_time 开始时间 结束时间
    //@param:$admin_revisiterid cc的id
    public function t_test_lesson_subject_require($start_time,$end_time,$admin_revisiterid){
        $where_arr=[
            'li.lesson_del_flag=0',
            'li.lesson_type = 2 ',
            'tlssl.success_flag in (0,1 )',
            'tlsr.accept_flag=1',
            'si.is_test_user = 0',
            'tlsr.cur_require_adminid' => $admin_revisiterid,
            '(li.lesson_user_online_status in (0,1) or f.flow_status = 2)'
        ];
        $this->where_arr_add_time_range($where_arr, 'li.lesson_start', $start_time, $end_time);

        $sql = $this->gen_sql_new(
            'select  count(distinct tls.userid) '.
            'from %s tlsr '.
            'left join %s tlssl on tlssl.lessonid = tlsr.current_lessonid '.
            'left join %s li on tlsr.current_lessonid=li.lessonid '.
            'left join %s si on li.userid=si.userid '.
            'left join %s f on f.flow_type=2003 and li.lessonid= f.from_key_int '.
            'where %s',
            self::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_flow::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);

    }
}
