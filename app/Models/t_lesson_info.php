<?php
namespace App\Models;
use App\Models\Zgen as Z;
use \App\Models as M;
use \App\Enums as E;
/**
 * @property t_student_info  $t_student_info
 * @property t_homework_info  $t_homework_info
 * @property t_course_order  $t_course_order
 * @property t_revisit_info  $t_revisit_info
 */
class t_lesson_info extends \App\Models\Zgen\z_t_lesson_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_trial_train_list($teacherid){
        $where_arr = [
            ["l.teacherid=%u",$teacherid,0],
            "lesson_start=0",
            "l.lesson_type=1100",
            "lesson_sub_type=1",
            "train_type=4",
            "lesson_del_flag=0",
            "lesson_status=0",
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.grade,l.subject,lesson_name,l.lesson_type,l.train_type,"
                                  ." lesson_start,lesson_end,lesson_intro,"
                                  ." lesson_status,ass_comment_audit,stu_cw_status as stu_status,"
                                  ." tea_cw_status as tea_status,tea_cw_url,tea_cw_upload_time,stu_cw_url,stu_cw_upload_time,"
                                  ." tea_more_cw_url,if(h.work_status>0,1,0) as homework_status"
                                  ." from %s l"
                                  ." left join %s h on l.lessonid=h.lessonid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['lessonid'];
        });
    }

    public function get_trial_train_list_new($teacherid){
        $where_arr = [
            ["l.teacherid=%u",$teacherid,0],
            "lesson_start=0",
            "l.lesson_type=1100",
            "lesson_sub_type=1",
            "train_type=4",
            "lesson_del_flag=0",
            "lesson_status=0",
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.grade,l.subject,lesson_name,l.lesson_type,l.train_type,"
                                  ." lesson_start,lesson_end,lesson_intro,"
                                  ." lesson_status,ass_comment_audit,stu_cw_status as stu_status,"
                                  ." tea_cw_status as tea_status,tea_cw_url,tea_cw_upload_time,stu_cw_url,stu_cw_upload_time,"
                                  ." tea_more_cw_url,if(h.work_status>0,1,0) as homework_status"
                                  ." from %s l"
                                  ." left join %s h on l.lessonid=h.lessonid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['lessonid'];
        });
    }


    public function lesson_common_where_arr($others_arr=[]) {
        $others_arr[] ="lesson_del_flag=0" ;
        $others_arr[] ="confirm_flag<2" ;
        return $others_arr;
    }

    public function get_first_lesson_start($teacherid){
        $where_arr = [
            ["teacherid=%d",$teacherid,0],
        ];
        $sql = $this->gen_sql_new("select lesson_start "
                                  ." from %s "
                                  ." where %s "
                                  ." order by lesson_start asc"
                                  ." limit 1"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function lesson_account($lesson_type,$start_date_s,$end_date_s,$teacherid,$page_num){
        $str = 'true';
        if($lesson_type == 0) {
            $str="lesson_type < 3000 and lesson_type >1000";
        }elseif($lesson_type == 1){
            $str="lesson_type = 3001";
        }elseif($lesson_type == 2){
            $str="lesson_type <1000";
        }

        if($teacherid>0){
            $str .= sprintf(" and t.teacherid=%d",$teacherid);
        }

        $sql = sprintf ("select "
                        ."l.lessonid,l.courseid,l.userid,l.lesson_type,l.lesson_start,l.lesson_end,t.nick as tea_nick "
                        ."from db_weiyi.t_lesson_info as l,db_weiyi.t_teacher_info as t "
                        ."where %s "
                        ."and l.teacherid = t.teacherid "
                        ."and lesson_start > %s "
                        ."and lesson_start < %s "
                        ."order by lesson_start asc"
                        ,$str
                        ,$this->ensql($start_date_s)
                        ,$this->ensql($end_date_s)
        );
        if($page_num){
            return $this->main_get_list_by_page($sql,$page_num,10);
        }else{
            return $this->main_get_list($sql);
        }
    }

    public function get_small_class_user_login_time($lessonid, $userid)
    {
        $sql =sprintf("select count(1) from %s where lessonid= %u and userid = %u and server_type=2 and opt_type = 1",
                      self::DB_TABLE_NAME,
                      // Z\z_t_student_info::DB_TABLE_NAME,

                      //$this->t_lesson_opt_log,
                      $lessonid,
                      $userid
        );

        return $this->main_get_value($sql);
    }

     public function get_stu_valid_lesson_num($userid) {
        $sql = sprintf("select count(1) as num from %s where userid = %u and lesson_status = 0 and lesson_type != 2",
                       self::DB_TABLE_NAME,
                       $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_need_set_lesson_end_list(){
        $end_time    = time(NULL)-5*60;
        $start_time  = $end_time-86400;


        $sql = $this->gen_sql("select lessonid,l.userid,l.teacherid,c.courseid,"
                              . " lesson_type,lesson_num,lesson_end, xmpp_server_name, current_server " .
                              " from %s l".
                              " left join  %s c on c.courseid=l.courseid ".
                              "where lesson_end > %u  and lesson_end < %u and  lesson_status < 2",
                              self::DB_TABLE_NAME,
                              t_course_order::DB_TABLE_NAME,
                              $start_time,
                              $end_time
        );
        return $this->main_get_list($sql);

    }

    public function get_need_set_lesson_begin_list(){
        $lesson_end   = time();
        $lesson_start = strtotime("-2 day",$lesson_end);
        $where_arr = [
            ["lesson_start<%u",$lesson_end,0],
            ["lesson_start>%u",$lesson_start,0],
            "lesson_status=0"
        ];

        $sql = $this->gen_sql_new("select lessonid "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_condition_list_ex($start, $end, $teacherid, $studentid  , $lessonid ,$lesson_type,$subject,
                                                 $is_with_test_user, $seller_adminid, $page_num, $confirm_flag, $assistantid=-1 ,
                                                 $lesson_status=-1, $test_seller_id=-1,$has_performance, $origin="",
                                                 $grade=-1, $lesson_count=-1,$lesson_cancel_reason_type=-1 ,$tea_subject="",
                                                 $has_video_flag, $lesson_user_online_status,$fulltime_flag=-1,
                                                 $lesson_del_flag=-1,$fulltime_teacher_type=-1
    ){
        $where_arr = [];
        if ($lessonid == -1 ) {
            $where_arr[] = sprintf("lesson_start > %d and lesson_start < %d", $start,$end  );
            $where_arr[] = [ "l.teacherid=%d", $teacherid ,-1];
            $where_arr[] = [ "l.userid=%d", $studentid,-1];
            $where_arr[] = $this->where_get_in_str_query("s.grade", $grade );
            $where_arr[] = $this->where_get_in_str_query("l.confirm_flag ", $confirm_flag );//04-21
            $where_arr[] = [ "s.seller_adminid=%d", $seller_adminid,-1];
            $where_arr[] = [ "s.origin like '%s%%'", $origin ,""];
            $where_arr[] = [ "l.lesson_count=%u ", $lesson_count,-1];
            $where_arr[] = [ "l.lesson_del_flag=%u ", $lesson_del_flag,-1];
            $where_arr[] = [ "l.lesson_cancel_reason_type=%u ", $lesson_cancel_reason_type,-1];
            $where_arr[] = [ "m.fulltime_teacher_type=%u ", $fulltime_teacher_type,-1];
            if ($lesson_type==-2) {
                $where_arr[] = "l.lesson_type in(0,1,3 )";
            }else{
                $where_arr[] = ["l.lesson_type=%u ",$lesson_type,-1];
            }

            $sub_arr=[];
            if($test_seller_id <> -1) {
                $sub_arr[] = "  tr.cur_require_adminid= $test_seller_id  ";
            }
            $sub_arr[] = [ "l.assistantid=%u",$assistantid,-1];
            $where_arr[]= "(". $this->where_str_gen($sub_arr, "or" )  .")";

            $where_arr[] = [ "l.subject=%d", $subject, -1];
            if($lesson_type<1000){
                $where_arr[] = [ "(s.is_test_user=%u or s.is_test_user is null )",$is_with_test_user,-1];
            }
            $where_arr[] = [ "l.lesson_status=%u",$lesson_status,-1];

            if($has_performance==0){
                $where_arr[]="l.stu_performance=''";
            }elseif($has_performance==1){
                $where_arr[]="l.stu_performance!=''";
            }
            $this->where_arr_add_boolean_for_value($where_arr,"lesson_upload_time",$has_video_flag);

        }else{
            $where_arr[] = sprintf("l.lessonid=%u",$lessonid);
        }

        $this->where_arr_add_int_field($where_arr,"lesson_user_online_status", $lesson_user_online_status);
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }elseif($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }


        if(!empty($tea_subject)){
            $where_arr[]="l.subject in ".$tea_subject;
        }
        $cond_str=$this->where_str_gen($where_arr);

        $sql=sprintf(" select"
                     ."    m.account cc_account,"
                     ."    l.lessonid,"
                     ."    l.lesson_del_flag,"
                     ."    l.courseid,"
                     ."    l.pcm_file_all_size,"
                     ."    l.pcm_file_count,"
                     ."    l.lesson_type,"
                     ."    l.lesson_count,"
                     ."    l.lesson_cancel_reason_type,"
                     ."    l.lesson_user_online_status,"
                     ."    l.teacherid,"
                     ."    l.origin,"
                     ."    l.system_version,"
                     ."    l.record_audio_server1,"
                     ."    l.record_audio_server2,"
                     ."    l.system_version,"
                     ."    l.lesson_cancel_time_type,"
                     ."    l.lesson_start, l.lesson_end,l.real_begin_time,"
                     ."    l.gen_video_grade,"
                     ."    l.assistantid,"
                     ."    l.teacher_money_type,"
                     ."    s.userid as stu_id,"
                     ."    s.phone as stu_phone,"
                     ."    s.nick as stu_nick,"
                     ."    s.user_agent as stu_user_agent,"
                     ."    s.origin as origin_str,"
                     ."    s.stu_email,"

                     ."    h.work_intro,"
                     ."    h.work_status,"
                     ."    h.issue_url,"
                     ."    h.finish_url,"
                     ."    h.check_url,"
                     ."    h.tea_research_url,"
                     ."    h.ass_research_url,     "
                     ."    h.score,     "
                     ."    h.issue_time,"
                     ."    h.finish_time,"
                     ."    h.check_time,"
                     ."    h.tea_research_time,"
                     ."    h.ass_research_time,     "

                     ."    l.tea_cw_origin,"
                     ."    l.stu_cw_origin,"
                     ."    l.enable_video,"
                     ."    l.lesson_status,"
                     ."    l.stu_score,"
                     ."    l.stu_comment,"
                     ."    l.stu_attitude,"
                     ."    l.stu_attention,"
                     ."    l.stu_ability,"
                     ."    l.stu_stability,"
                     ."    l.teacher_score,"
                     ."    l.teacher_comment,"
                     ."    l.tea_rate_time,"
                     ."    l.lesson_intro,"
                     ."    l.teacher_effect,"
                     ."    l.teacher_quality,"
                     ."    l.teacher_interact,"
                     ."    l.stu_praise,"
                     ."    l.stu_cw_upload_time,"
                     ."    l.stu_cw_status,"
                     ."    l.stu_cw_url,"
                     ."    l.tea_cw_name,"
                     ."    l.tea_cw_upload_time,"
                     ."    l.tea_cw_status,"
                     ."    l.use_ppt,"
                     ."    l.tea_cw_url,"
                     ."    l.is_complained,"
                     ."    l.lesson_upload_time,"
                     ."    l.stu_performance,"
                     ."    l.audio,"
                     ."    l.draw,"
                     ."    l.lesson_cancel_reason_type,"
                     ."    l.lesson_cancel_reason_next_lesson_time,"
                     ."    l.draw,"
                     ."    l.lesson_quiz,"
                     ."    l.lesson_quiz_status,"
                     ."    l.subject,"
                     ."    l.grade,"
                     ."    l.confirm_flag,"
                     ."    l.confirm_adminid,"
                     ."    l.confirm_time,"
                     ."    l.confirm_reason,"
                     ."    l.lesson_num,"
                     ."    l.tea_price,"
                     ."    l.level,"
                     ."    l.grade,"
                     ."    l.teacher_interact,"
                     ."    l.teacher_comment,"
                     ."    l.teacher_quality,"
                     ."    l.teacher_effect,"
                     ."    l.stu_stability,"
                     ."    t.require_adminid ,"
                     ."    pi.phone fa_phone,"
                     ."    l.lesson_name,"
                     ."    l.deduct_come_late,"
                     ."    l.deduct_change_class,"
                     ."    l.deduct_upload_cw,"
                     ."    l.deduct_rate_student,"
                     ."    l.deduct_check_homework,"
                     ."    l.lesson_full_num,"
                     ."    t.ass_test_lesson_type, "
                     ."    f.flow_status as require_lesson_success_flow_status,  "
                     ."    tts.success_flag,"
                     ."    tts.confirm_adminid test_confirm_adminid,"
                     ."    tts.confirm_time test_confirm_time,"
                     ."    tts.test_lesson_fail_flag ,"
                     ."    tts.fail_greater_4_hour_flag ,"
                     ."    c.current_server,"
                     ."    tts.fail_reason "
                     ."    from %s as l"
                     ."    LEFT JOIN db_weiyi.t_homework_info as h ON l.lessonid = h.lessonid "
                     ."    LEFT JOIN db_weiyi.t_student_info as s ON s.userid = l.userid"
                     ."    LEFT JOIN db_weiyi.t_parent_info as pi ON s.parentid = pi.parentid"
                     ."    LEFT JOIN db_weiyi.t_test_lesson_subject_sub_list as tts ON tts.lessonid = l.lessonid"
                     ."    LEFT JOIN db_weiyi.t_test_lesson_subject_require as tr ON tr.require_id = tts.require_id "
                     ."    LEFT JOIN db_weiyi.t_test_lesson_subject as t ON t.test_lesson_subject_id = tr.test_lesson_subject_id "
                     ."    LEFT JOIN %s as f ON ( f.flow_type=2003 and l.lessonid=f.from_key_int  ) "
                     ."    LEFT JOIN %s c on (c.courseid=l.courseid) "
                     ."    LEFT JOIN %s as tt ON tt.teacherid = l.teacherid "
                     ."    LEFT JOIN %s as m ON tt.phone = m.phone "
                     ."    where %s  "
                     ."    order by lesson_start asc, l.lessonid asc "
                     ,t_lesson_info::DB_TABLE_NAME
                     ,t_flow::DB_TABLE_NAME
                     ,t_course_order::DB_TABLE_NAME
                     ,t_teacher_info::DB_TABLE_NAME
                     ,t_manager_info::DB_TABLE_NAME
                     ,$cond_str
        );
        return $this->main_get_list_by_page($sql, $page_num, 10);
    }

    public function get_lesson_condition_list_ex_new($start, $end, $teacherid, $studentid  , $lessonid ,$lesson_type,$subject,
                                          $is_with_test_user, $seller_adminid, $page_num, $confirm_flag, $assistantid=-1 ,
                                          $lesson_status=-1, $test_seller_id_arr,$test_seller_adminid,$has_performance, $origin="",
                                          $grade=-1, $lesson_count=-1,$lesson_cancel_reason_type=-1 ,$tea_subject="",
                                          $has_video_flag, $lesson_user_online_status,$fulltime_flag=-1,
                                          $lesson_del_flag=-1,$fulltime_teacher_type=-1
    ){
        $where_arr = [];
        if ($lessonid == -1 ) {
            $where_arr[] = sprintf("lesson_start > %d and lesson_start < %d", $start,$end  );
            $where_arr[] = [ "l.teacherid=%d", $teacherid ,-1];
            $where_arr[] = [ "l.userid=%d", $studentid,-1];
            $where_arr[] = $this->where_get_in_str_query("s.grade", $grade );
            $where_arr[] = $this->where_get_in_str_query("l.confirm_flag ", $confirm_flag );//04-21
            $where_arr[] = [ "s.seller_adminid=%d", $seller_adminid,-1];
            $where_arr[] = [ "s.origin like '%s%%'", $origin ,""];
            $where_arr[] = [ "l.lesson_count=%u ", $lesson_count,-1];
            $where_arr[] = [ "l.lesson_del_flag=%u ", $lesson_del_flag,-1];
            $where_arr[] = [ "l.lesson_cancel_reason_type=%u ", $lesson_cancel_reason_type,-1];
            $where_arr[] = [ "m.fulltime_teacher_type=%u ", $fulltime_teacher_type,-1];
            if ($lesson_type==-2) {
                $where_arr[] = "l.lesson_type in(0,1,3 )";
            }else{
                $where_arr[] = ["l.lesson_type=%u ",$lesson_type,-1];
            }

            $sub_arr=[];
            if($test_seller_adminid == -1){
                $this->where_arr_add_int_or_idlist($where_arr,'tr.cur_require_adminid',$test_seller_id_arr);
            }else{
                $this->where_arr_add_int_or_idlist($where_arr,'tr.cur_require_adminid',$test_seller_adminid);
            }
            $sub_arr[] = [ "l.assistantid=%u",$assistantid,-1];
            $where_arr[]= "(". $this->where_str_gen($sub_arr, "or" )  .")";

            $where_arr[] = [ "l.subject=%d", $subject, -1];
            if($lesson_type<1000){
                $where_arr[] = [ "(s.is_test_user=%u or s.is_test_user is null )",$is_with_test_user,-1];
            }
            $where_arr[] = [ "l.lesson_status=%u",$lesson_status,-1];

            if($has_performance==0){
                $where_arr[]="l.stu_performance=''";
            }elseif($has_performance==1){
                $where_arr[]="l.stu_performance!=''";
            }
            $this->where_arr_add_boolean_for_value($where_arr,"lesson_upload_time",$has_video_flag);

        }else{
            $where_arr[] = sprintf("l.lessonid=%u",$lessonid);
        }

        $this->where_arr_add_int_field($where_arr,"lesson_user_online_status", $lesson_user_online_status);
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }elseif($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }


        if(!empty($tea_subject)){
            $where_arr[]="l.subject in ".$tea_subject;
        }
        $cond_str=$this->where_str_gen($where_arr);

        $sql=sprintf(" select"
                     ."    m.account cc_account,"
                     ."    l.stu_cw_origin,"
                     ."    l.tea_cw_origin,"
                     ."    l.lessonid,"
                     ."    l.lesson_del_flag,"
                     ."    l.courseid,"
                     ."    l.pcm_file_all_size,"
                     ."    l.pcm_file_count,"
                     ."    l.lesson_type,"
                     ."    l.lesson_count,"
                     ."    l.lesson_cancel_reason_type,"
                     ."    l.lesson_user_online_status,"
                     ."    l.teacherid,"
                     ."    l.origin,"
                     ."    l.system_version,"
                     ."    l.record_audio_server1,"
                     ."    l.record_audio_server2,"
                     ."    l.system_version,"
                     ."    l.lesson_cancel_time_type,"
                     ."    l.lesson_start, l.lesson_end,l.real_begin_time,"
                     ."    l.gen_video_grade,"
                     ."    l.assistantid,"
                     ."    l.teacher_money_type,"
                     ."    s.userid as stu_id,"
                     ."    s.phone as stu_phone,"
                     ."    s.nick as stu_nick,"
                     ."    s.user_agent as stu_user_agent,"
                     ."    s.origin as origin_str,"
                     ."    s.stu_email,"
                     .""
                     ."    h.work_intro,"
                     ."    h.work_status,"
                     ."    h.issue_url,"
                     ."    h.finish_url,"
                     ."    h.check_url,"
                     ."    h.tea_research_url,"
                     ."    h.ass_research_url,     "
                     ."    h.score,     "
                     ."    h.issue_time,"
                     ."    h.finish_time,"
                     ."    h.check_time,"
                     ."    h.tea_research_time,"
                     ."    h.ass_research_time,     "

                     ."    l.enable_video,"
                     ."    l.lesson_status,"
                     ."    l.stu_score,"
                     ."    l.stu_comment,"
                     ."    l.stu_attitude,"
                     ."    l.stu_attention,"
                     ."    l.stu_ability,"
                     ."    l.stu_stability,"
                     ."    l.teacher_score,"
                     ."    l.teacher_comment,"
                     ."    l.tea_rate_time,"
                     ."    l.lesson_intro,"
                     ."    l.teacher_effect,"
                     ."    l.teacher_quality,"
                     ."    l.teacher_interact,"
                     ."    l.stu_praise,"
                     ."    l.stu_cw_upload_time,"
                     ."    l.stu_cw_status,"
                     ."    l.stu_cw_url,"
                     ."    l.tea_cw_name,"
                     ."    l.tea_cw_upload_time,"
                     ."    l.tea_cw_status,"
                     ."    l.use_ppt,"
                     ."    l.tea_cw_url,"
                     ."    l.is_complained,"
                     ."    l.lesson_upload_time,"
                     ."    l.stu_performance,"
                     ."    l.audio,"
                     ."    l.draw,"
                     ."    l.lesson_cancel_reason_type,"
                     ."    l.lesson_cancel_reason_next_lesson_time,"
                     ."    l.draw,"
                     ."    l.lesson_quiz,"
                     ."    l.lesson_quiz_status,"
                     ."    l.subject,"
                     ."    l.grade,"
                     ."    l.confirm_flag,"
                     ."    l.confirm_adminid,"
                     ."    l.confirm_time,"
                     ."    l.confirm_reason,"
                     ."    l.lesson_num,"
                     ."    l.tea_price,"
                     ."    l.level,"
                     ."    l.grade,"
                     ."    l.teacher_interact,"
                     ."    l.teacher_comment,"
                     ."    l.teacher_quality,"
                     ."    l.teacher_effect,"
                     ."    l.stu_stability,"
                     ."    t.require_adminid ,"
                     ."    pi.phone fa_phone,"
                     ."    l.lesson_name,"
                     ."    l.deduct_come_late,"
                     ."    l.deduct_change_class,"
                     ."    l.deduct_upload_cw,"
                     ."    l.deduct_rate_student,"
                     ."    l.deduct_check_homework,"
                     ."    l.lesson_full_num,"
                     ."    t.ass_test_lesson_type, "
                     ."    f.flow_status as require_lesson_success_flow_status,  "
                     ."    tts.success_flag,"
                     ."    tts.confirm_adminid test_confirm_adminid,"
                     ."    tts.confirm_time test_confirm_time,"
                     ."    tts.test_lesson_fail_flag ,"
                     ."    tts.fail_greater_4_hour_flag ,"
                     ."    c.current_server,"
                     ."    tts.fail_reason "
                     ."    from"
                     ."    db_weiyi.t_lesson_info as l"
                     ."    LEFT JOIN db_weiyi.t_homework_info as h"
                     ."    ON l.lessonid = h.lessonid "

                     ."    LEFT JOIN db_weiyi.t_student_info as s"
                     ."    ON s.userid = l.userid"
                     ."    LEFT JOIN db_weiyi.t_parent_info as pi"
                     ."    ON s.parentid = pi.parentid"

                     ."    LEFT JOIN db_weiyi.t_test_lesson_subject_sub_list as tts"
                     ."    ON tts.lessonid = l.lessonid"

                     ."    LEFT JOIN db_weiyi.t_test_lesson_subject_require as tr"
                     ."    ON tr.require_id = tts.require_id "

                     ."    LEFT JOIN db_weiyi.t_test_lesson_subject as t"
                     ."    ON t.test_lesson_subject_id = tr.test_lesson_subject_id "

                     ."    LEFT JOIN  %s as f"
                     ."    ON ( f.flow_type=2003 and l.lessonid=f.from_key_int  ) "

                     ."    LEFT JOIN  %s c on (c.courseid=l.courseid) "


                     ."    LEFT JOIN db_weiyi.t_teacher_info as tt"
                     ."    ON tt.teacherid = l.teacherid "
                     ."    LEFT JOIN db_weiyi_admin.t_manager_info as m"
                     ."    ON tt.phone = m.phone "
                     ."    where"
                     ."    %s  "
                     ."    order by lesson_start asc, l.lessonid asc "
                     , t_flow::DB_TABLE_NAME
                     , t_course_order::DB_TABLE_NAME
                     ,$cond_str
        ); 
        return $this->main_get_list_by_page($sql, $page_num, 10);
    }

    public function lesson_record_server_list($page_num,$record_audio_server1 ,$xmpp_server_name  ) {
        $start_time=strtotime(date("Y-m-d"));
        $where_arr=[
            //"lesson_status=1" ,

            ["record_audio_server1='%s'", $record_audio_server1, "" ],
            ["xmpp_server_name='%s'", $xmpp_server_name, "" ],
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$start_time+86400);
        $sql=$this->gen_sql_new(
            "select lessonid, record_audio_server1, xmpp_server_name, lesson_start, lesson_end, userid,teacherid"
            ." from %s   where  lesson_del_flag=0 and  %s " ,
            self::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_lesson_cw_info($lessonid){
        $sql = $this->gen_sql_new("select  h.work_intro,"
                                  ." h.work_status,"
                                  ." h.issue_url,"
                                  ." h.finish_url,"
                                  ." h.check_url,"
                                  ." h.tea_research_url,"
                                  ." h.ass_research_url,     "
                                  ." h.score,     "
                                  ." h.issue_time,"
                                  ." h.finish_time,"
                                  ." h.check_time,"
                                  ." h.tea_research_time,"
                                  ." h.ass_research_time,"
                                  ." l.stu_cw_upload_time,"
                                  ." l.stu_cw_status,"
                                  ." l.stu_cw_url,"
                                  ." l.tea_cw_name,"
                                  ." l.tea_cw_upload_time,"
                                  ." l.tea_cw_status,"
                                  ." l.tea_cw_url,"
                                  ." l.lesson_quiz,"
                                  ." l.lesson_quiz_status,"
                                  ." l.tea_more_cw_url"
                                  ." from %s l left join %s h on l.lessonid = h.lessonid where l.lessonid = %s ",
                                  self::DB_TABLE_NAME,
                                  t_homework_info::DB_TABLE_NAME,
                                  $lessonid
        );
        return $this->main_get_row($sql);
    }

    public function get_test_listen_info($start,$end) {
        $where_arr=[
            ["lesson_start>=%u" ,$start, -1   ],
            ["lesson_start<=%u" ,$end, -1   ],
            "lesson_type=2",
            "s.is_test_user=0",
            "confirm_flag in(0,1)",
        ];

        $sql=sprintf(" select"
                     ."    l.origin,"
                     ."    count(*) as test_count, "
                     ."    count(distinct s.userid ) as test_user_count "
                     ."    from"
                     ."    db_weiyi.t_lesson_info as l"
                     ."    LEFT JOIN db_weiyi.t_student_info as s"
                     ."    ON s.userid = l.userid"
                     ."    where"
                     ."    %s"
                     ."   group by  l.origin ",
                     $this->where_str_gen($where_arr));

        return $this->main_get_list($sql,function($item){
            return $item["origin"];
        });
    }

    public function get_teacher_clothes_info($lessonid,$teacherid){
        $sql = $this->gen_sql("select gender,teacher_clothes as clothes from %s as t,%s as l where t.teacherid=%u and l.lessonid=%u"
                     ,Z\z_t_teacher_info::DB_TABLE_NAME
                     ,self::DB_TABLE_NAME
                     ,$teacherid
                     ,$lessonid
        );
        return $this->main_get_row($sql);
    }

    public function set_teacher_clothes($courseid,$clothes){
        $sql = $this->gen_sql("update %s set teacher_clothes=%u where courseid= %u"
                            ,self::DB_TABLE_NAME
                            ,$clothes
                            ,$courseid
        );
        return $this->main_update($sql);
    }

    public function get_lesson_condition_list($start,$end,$st_application_nick,$userid,$teacherid,
                                              $run_flag,$assistantid,$require_adminid
    ){
        $sub_arr = [
            ["require_adminid=%u", $require_adminid,-1] ,
        ];
        $sub_where_str = "(".$this->where_str_gen($sub_arr, "or").")";

        $sub_arr_2 = [
            ["s.assistantid=%u", $assistantid,-1] ,
        ];
        $sub_where_str_2 = "(".$this->where_str_gen($sub_arr_2, "or").")";

        $where_arr = [
            ["l.userid =%u", $userid,-1] ,
            ["l.teacherid=%u", $teacherid,-1] ,
            "confirm_flag not in ( 2, 3 )",
            $sub_where_str,
            $sub_where_str_2,
        ];

        if ($run_flag==1) {
            $now=time(NULL);
            $where_arr[] =  sprintf ( "((lesson_start -600 <%u and lesson_end+ 600> %u ) or lesson_status = 1) ", $now ,$now );
        }else if ($run_flag==2) {
            $where_arr[] = "lesson_type=2";
        }
        $where_arr[] = "l.lesson_del_flag=0";

        $sql = $this->gen_sql_new(
            "select l.lessonid,require_adminid,account,l.userid,l.teacherid,l.assistantid,lesson_start,lesson_end,".
            " l.courseid,l.lesson_type,".
            " lesson_num,c.current_server,server_type , xmpp_server_name,".
            " l.stu_agent,l.tea_agent".
            " from %s l " .
            " left join %s c on c.courseid = l.courseid  ".
            " left join %s tss on l.lessonid = tss.lessonid ".
            " left join %s tr on tr.require_id = tss.require_id ".
            " left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id ".
            " left join %s m on t.require_adminid=m.uid ".
            " left join %s s on l.userid=s.userid".
            " where lesson_start > %u ".
            " and lesson_start < %u and %s".
            " order by lesson_start asc, lessonid asc",
            self::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $start, $end, $where_arr
        );

        return $this->main_get_list_as_page($sql,function($item){
            return $item["lessonid"];
        });
    }

    public function get_lesson_condition_list_new($start,$end,$st_application_nick,$userid,$teacherid,
                                                  $run_flag,$assistantid,$require_adminid_arr
    ){
        $sub_arr = [
        ];
        $sub_where_str = "(".$this->where_str_gen($sub_arr, "or").")";

        $sub_arr_2 = [
            ["s.assistantid=%u", $assistantid,-1] ,
        ];
        $sub_where_str_2 = "(".$this->where_str_gen($sub_arr_2, "or").")";

        $where_arr = [
            ["l.userid =%u", $userid,-1] ,
            ["l.teacherid=%u", $teacherid,-1] ,
            "confirm_flag not in ( 2, 3 )",
            $sub_where_str,
            $sub_where_str_2,
        ];

        if ($run_flag==1) {
            $now=time(NULL);
            $where_arr[] =  sprintf ( "((lesson_start -600 <%u and lesson_end+ 600> %u ) or lesson_status = 1) ", $now ,$now );
        }else if ($run_flag==2) {
            $where_arr[] = "lesson_type=2";
        }
        $where_arr[] = "l.lesson_del_flag=0";
        $this->where_arr_add_int_or_idlist($where_arr,'require_adminid',$require_adminid_arr);
        $sql = $this->gen_sql_new(
            "select l.lessonid,require_adminid,account,l.userid,l.teacherid,l.assistantid,lesson_start,lesson_end,".
            " l.courseid,l.lesson_type,".
            " lesson_num,c.current_server,server_type  ".
            " from %s l " .
            " left join %s c on c.courseid = l.courseid  ".
            " left join %s tss on l.lessonid = tss.lessonid ".
            " left join %s tr on tr.require_id = tss.require_id ".
            " left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id ".
            " left join %s m on t.require_adminid=m.uid ".
            " left join %s s on l.userid=s.userid".
            " where lesson_start > %u ".
            " and lesson_start < %u and %s".
            " order by lesson_start asc, lessonid asc",
            self::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $start, $end, $where_arr
        );

        return $this->main_get_list_as_page($sql,function($item){
            return $item["lessonid"];
        });
    }

    public function get_book_user_lesson_time($userid){
        $sql = $this->gen_sql("select lesson_start from %s where userid = %u order by lesson_start desc"
                              ,self::DB_TABLE_NAME
                              ,$userid);
        return $this->main_get_value($sql);
    }

    public function get_book_user_lesson_count($userid){
        $sql = $this->gen_sql("select count(1) from %s where userid=%u"
                              ,self::DB_TABLE_NAME
                              ,$userid);
        return $this->main_get_value($sql);
    }
    public function get_lessons_available($userid,$courseid, $all_flag, $page_num, $page_size)
    {
        $lesson_status_str= $this->where_get_in_str("lesson_status", $all_flag?[0,1,2]:[0,1] );
        $sql = sprintf("select l.lessonid, l.subject,l.grade ,l.courseid, l.lesson_num, l.lesson_type, "
                       ." l.userid,s.phone,l.teacherid, l.assistantid,t.realname as teacher_nick,"
                       ." has_quiz, lesson_start, lesson_end, lesson_intro, l.lesson_status,l.lesson_count ,confirm_flag, "
                       ." l.confirm_adminid,l.confirm_time,l.confirm_reason, l.level ,l.teacher_money_type, "
                       ." l.lesson_cancel_reason_type,l.lesson_cancel_reason_next_lesson_time,l.lesson_del_flag "
                       ." from %s l "
                       ." left join %s s on l.userid = s.userid "
                       ." left join %s t on l.teacherid= t.teacherid"
                       ." where l.userid = %u "
                       ." and courseid=%u "
                       ." and %s and from_type=0 and l.lesson_del_flag=0 order by courseid,lesson_num "
                       ,self::DB_TABLE_NAME
                       ,t_student_info::DB_TABLE_NAME
                       ,t_teacher_info::DB_TABLE_NAME
                       ,$userid
                       ,$courseid
                       ,$lesson_status_str
        );
        return $this->main_get_list_by_page($sql, $page_num, $page_size);
    }

    public function get_lesson_info_by_userid($userid){
        $sql = $this->gen_sql("select *,( "
                              ."select count(1) from %s where userid = %u and lesson_type=2 "
                              .") as lesson_count from %s "
                              ." where userid                        = %u and lesson_type=2 "
                              ." order by lesson_start desc"
                              ,self::DB_TABLE_NAME
                              ,$userid
                              ,self::DB_TABLE_NAME
                              ,$userid
        );

        return $this->main_get_row($sql);
    }

    public function set_user_assistantid( $userid,$assistantid) {
        $where_arr=[
            ["userid=%u",$userid,-1],
        ];
        $now=time();
        $where_arr=$this->lesson_common_where_arr($where_arr);
        $sql=$this->gen_sql_new(" update  %s  "
                                ." set assistantid = %u"
                                ." where %s "
                                ." and  lesson_start > $now "
                                ,self::DB_TABLE_NAME
                                ,$assistantid
                                ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_today_lesson_list( $start_time,$end_time ) {
        $sql = $this->gen_sql("select lessonid,s.phone,lesson_start,lesson_end,lesson_type ".
                              " from %s l ".
                              " LEFT JOIN db_weiyi.t_student_info as s  on l.userid = s.userid ".
                              " where lesson_start>%u and lesson_start< %u and lesson_status in (0,1) and lesson_del_flag=0  ",
                              self::DB_TABLE_NAME,
                              $start_time,
                              $end_time
        );
        return $this->main_get_list($sql);
    }

    public function get_today_tea_lesson_list($start_time,$end_time){
        $sql = $this->gen_sql("select lessonid,teacherid,lesson_start,lesson_end,lesson_type "
                              ." from %s "
                              ." where lesson_start> %u "
                              ." and lesson_start< %u "
                              ." and lesson_status<=1  "
                              ." and lesson_type!=4001"
                              ." and lesson_del_flag=0"
                              ,self::DB_TABLE_NAME
                              ,$start_time
                              ,$end_time
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_list_push_homework($start,$end){
        $sql = $this->gen_sql("select lessonid,lesson_type from %s "
                              ." where lesson_end > %u "
                              ." and lesson_end < %u "
                              ." and lesson_status>1"
                              ." and confirm_flag<2"
                              ." and lesson_del_flag=0"
                              ,self::DB_TABLE_NAME
                              ,$start
                              ,$end
        );
        return $this->main_get_list($sql);
    }

    public function get_1v1_user_list($lessonid_list){
        $sql = $this->gen_sql("select lessonid,lesson_start,lesson_end,s.phone,s.userid,l.lesson_type,s.nick "
                              ." from %s l,%s s"
                              ." where lessonid in (%s) "
                              ." and l.userid = s.userid"
                              ." and lesson_del_flag=0"
                              ,self::DB_TABLE_NAME
                              ,t_student_info::DB_TABLE_NAME
                              ,$lessonid_list
        );
        return $this->main_get_list($sql);
    }

    public function get_open_user_list($lessonid_list){
        $sql = $this->gen_sql("select l.lessonid,lesson_start,lesson_end,s.phone,s.userid,l.lesson_type,s.nick "
                              ." from %s l,%s s"
                              ." left join %s as o on  o.userid=s.userid"
                              ." where l.lessonid in (%s) and o.lessonid = l.lessonid"
                              ." and lesson_del_flag=0"
                              ,self::DB_TABLE_NAME
                              ,t_student_info::DB_TABLE_NAME
                              ,t_open_lesson_user::DB_TABLE_NAME
                              ,$lessonid_list
        );
        return $this->main_get_list($sql);
    }

    public function get_small_user_list($lessonid_list){
        $sql = $this->gen_sql("select l.lessonid,lesson_start,lesson_end,s.phone,s.userid, l.lesson_type,s.nick "
                              ." from %s l,%s s"
                              ." left join %s as o on  o.userid          = s.userid"
                              ." where l.lessonid in (%s) and o.lessonid = l.lessonid"
                              ." and lesson_del_flag=0"
                              ,self::DB_TABLE_NAME
                              ,t_student_info::DB_TABLE_NAME
                              ,t_small_lesson_info::DB_TABLE_NAME
                              ,$lessonid_list
        );
        return $this->main_get_list($sql);
    }

    public function get_open_lessons($lesson_status, $teacherid, $lesson_type, $start, $end, $lessonid, $page_num)
    {
        if($lessonid==-1){
            $conf_arr=[
                ["l.teacherid=%d",$teacherid,-1],
                ["lesson_type=%d",$lesson_type,-1],
                ["lesson_start>%d",$start,-1],
                ["lesson_start<%d",$end,-1],
            ];
            if($lesson_status != -1){
                if($lesson_status == 0){
                    $conf_arr[] = ["lesson_status = %d",$lesson_status,-1];
                }else{
                    $conf_arr[] = ["lesson_status>=%d",1,-1];
                }
            }
            if($lesson_type!=1100){
                $conf_arr[] = "lesson_type in (1001,1002,1003,4001)";
            }
        }else{
            $conf_arr[] = ["l.lessonid=%d",$lessonid,-1];
        }

        $sql = $this->gen_sql_new("select l.courseid,l.lessonid,lesson_status,lesson_intro,from_lessonid,l.teacherid,"
                                  ." can_set_as_from_lessonid, lesson_num, lesson_start, lesson_end, lesson_type, tea_cw_url,"
                                  ." tea_cw_status, c.lesson_total, l.teacherid, course_name ,l.grade"
                                  ." from %s l "
                                  ." left join %s c on l.courseid=c.courseid "
                                  ." where lesson_start!=0 "
                                  ." and %s "
                                  ." and lesson_del_flag=0 "
                                  ." order by lesson_start asc "
                                  ,self::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,$conf_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_lesson_after($lessonid)
    {
        $sql = sprintf("select courseid from %s where lessonid = %u",
                       self::DB_TABLE_NAME,
                       $lessonid
        );
        $courseid = $this->main_get_value($sql,0);
        $sql      = sprintf("select lessonid, lesson_num from %s where courseid  = %u and lessonid > %u and lesson_start != 0"  ." and lesson_del_flag=0",
                       self::DB_TABLE_NAME,
                       $courseid,
                       $lessonid
        );
        return $this->main_get_list($sql);
    }

    public function delete_open_lesson($lessonid)
    {
        $sql = sprintf("update %s set lesson_start = 0, lesson_end = 0, tea_cw_status = 0, " .
                       "tea_cw_upload_time = 0 , tea_cw_url = '' where  lessonid = %u",
                       self::DB_TABLE_NAME,
                       $lessonid
        );
        return $this->main_update($sql);
    }

    public function delete_test_lesson($lessonid ) {
        $sql = sprintf("delete from %s  " . " where  lessonid = %u and lesson_type=2 and lesson_start > %u",
                       self::DB_TABLE_NAME,
                       $lessonid, time(NULL) +4*3600 );
        return $this->main_update($sql);
    }

    public function get_open_class_enter_type($lessonid)
    {
        $sql = sprintf("select enter_type from %s where lessonid = %u ",
                       self::DB_TABLE_NAME,
                       $lessonid
        );
        return $this->main_get_value($sql);
    }

    public function upload_files($itemid, $type, $urlkey){
        if($type == 0 || $type == 1)
            $this->upload_cw($itemid, $type, $urlkey);
        else
            $this->upload_home_quiz($itemid, $type, $urlkey);
    }

    private function upload_cw($lessonid, $type, $urlkey)
    {
        $cw_type = $type == 0?'tea':'stu';
        $sql     = sprintf("update %s set $cw_type" . "_cw_upload_time = %u, $cw_type" . "_cw_status = 1, $cw_type" .
                           "_cw_url = '%s' where lessonid = %u",
                           self::DB_TABLE_NAME,
                           time(NULL),
                           $urlkey,
                           $lessonid
        );
        return $this->main_update($sql);
    }

    public function add_from_lessonid($courseid,$from_lessonid)
    {
        $sql = sprintf("update %s set from_lessonid = %s where courseid = %u",
                       self::DB_TABLE_NAME,
                       $from_lessonid,
                       $courseid
        );
        return $this->main_update($sql);
    }

    public function can_set_from_lessonid($lessonid,$can_set)
    {
        $sql = sprintf("update %s set can_set_as_from_lessonid = %s where lessonid = %u",
                       self::DB_TABLE_NAME,
                       $can_set,
                       $lessonid
        );
        return $this->main_update($sql);
    }

    public function get_open_from_list($courseid,$course_type,$search_str,$page_num)
    {
        $where_arr = array(
            array( "course_type = %u", $course_type, -1 ),
            array( "c.courseid = %d", $courseid, -1 ),
        );

        $course_type_str = $this->where_get_in_str("course_type", [
                       E\Econtract_type::V_1001,
                       E\Econtract_type::V_1002,
                       E\Econtract_type::V_1003,
                       E\Econtract_type::V_3001
        ]);
        $lesson_status_str = $this->where_get_in_str("lesson_status", [
                       E\Elesson_status::V_2,
                       E\Elesson_status::V_3
        ]);
        if ($search_str != ""){
            $where_arr[] = sprintf( "( course_name like '%%%s%%' )",
                                    $this->ensql($search_str));
        }

        $where_arr[] ="lesson_del_flag=0"  ;

        $sql = sprintf("select t.lessonid, c.courseid, course_name,t.lesson_intro,"
                       ." t.lesson_start, t.lesson_end, course_type "
                       ." from %s t , %s c where  "
                       ." c.courseid = t.courseid "
                       ." and can_set_as_from_lessonid = 2 "
                       ." and %s and %s and %s "
                       ,self::DB_TABLE_NAME
                       ,\App\Models\t_course_order::DB_TABLE_NAME
                       ,$course_type_str
                       ,$lesson_status_str
                       ,$this->where_str_gen($where_arr)
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function tongji_get_1v1_lesson_list($start_time, $end_time) {
        $sql = $this->gen_sql("select userid from %s where lesson_start >=%u and lesson_start<%u and
lesson_type in (0,1) "
                              . " and lesson_del_flag=0"
                            ,self::DB_TABLE_NAME,$start_time,$end_time);
        return $this->main_get_list($sql);
    }

    public function tongji_get_test_count($start_time, $end_time) {
        $sql = $this->gen_sql("select userid from %s where lesson_start >=%u and lesson_start<%u ".
                              "and lesson_type in (2) "
                              . " and lesson_del_flag=0 "
                              ."  group by userid  "
                            ,self::DB_TABLE_NAME,$start_time,$end_time);
        return count($this->main_get_list($sql));
    }

    public function tongji_get_teacher_count($start_time,$end_time ) {
        $sql = $this->gen_sql("select count( distinct teacherid ) from %s l, %s s "
                              ." where l.userid=s.userid "
                              ." and lesson_start>=%u "
                              ." and lesson_start<%u "
                              ." and confirm_flag!=2 "
                              ." and lesson_del_flag=0 "
                              ." and is_test_user=0 "
                              ,self::DB_TABLE_NAME
                              ,t_student_info::DB_TABLE_NAME
                              ,$start_time
                              ,$end_time
        );
        return $this->main_get_value($sql);
    }

    public function tongji_get_lesson_count_list($start_time ,$end_time) {
        $sql = $this->gen_sql("select lesson_type ,t1.userid  from %s t1,  %s t2  "
                              ."where t1.userid = t2.userid and lesson_start >=%u and lesson_start<%u and "
                              ." lesson_type in (0,1,2) "
                              . " and lesson_del_flag=0 "
                              ,self::DB_TABLE_NAME,
                              t_student_info::DB_TABLE_NAME,
                              $start_time,$end_time);
        return $this->main_get_list($sql);
    }

    public function get_list_for_ajax_list($userid, $lesson_type ,$page_num) {
        $where_arr = [
            ["userid=%u",$userid,-1 ],
            ["lesson_type=%u",$lesson_type,-1 ],
        ];
        $where_str=$this->where_str_gen($where_arr);
        $sql=$this->gen_sql("select lessonid,lesson_type,lesson_start,lesson_end,teacherid from %s where %s  and lesson_start>0"
                            . " and lesson_del_flag=0 "
                            ." order by lesson_start desc ",
                           self::DB_TABLE_NAME,[$where_str]) ;

        return $this->main_get_list_by_page($sql,$page_num,10);
    }
    public function reset_lesson_list_by_lessonid( $lessonid) {
        $courseid=$this->get_courseid($lessonid);
        if ($courseid) {
            return $this->reset_lesson_list($courseid);
        }
        return false;
    }

    public function reset_lesson_list( $courseid  )
    {
        $sql=sprintf("select lessonid,lesson_start,lesson_end,lesson_num "
                     ." from %s "
                     ." where  courseid=%u "
                     ." order by lesson_start asc ",
                     self::DB_TABLE_NAME, $courseid);
        $list= $this->main_get_list($sql);
        usort ($list,
               function($a,$b){
                   $a_lessonid  = $a["lessonid"];
                   $a_lesson_start = $a["lesson_start"];
                   if ($a_lesson_start==0) {
                       $a_lesson_start=0xFFFFFFFF;
                   }

                   $b_lessonid  = $b["lessonid"];
                   $b_lesson_start = $b["lesson_start"];
                   if ($b_lesson_start==0) {
                       $b_lesson_start=0xFFFFFFFF;
                   }


                   if ($a_lesson_start  == $b_lesson_start  &&
                       $a_lessonid == $b_lessonid ) {
                       return 0;
                   }

                   return (($a_lesson_start < $b_lesson_start) || ( $a_lesson_start == $b_lesson_start && ($a_lessonid < $b_lessonid ) ) )  ? -1 : 1;
               });

        foreach( $list  as $key=> &$item )  {
            $lesson_num=$key+1;
            if ($item["lesson_num"] != $lesson_num ) { //need update
                $this->field_update_list($item["lessonid"],["lesson_num"=>$lesson_num]);
                $this->t_homework_info->field_update_list(
                    $item["lessonid"],["lesson_num"=>$lesson_num]
                );
            }

        }

        return true;
    }

    public function del_if_no_start($lessonid)  {
        $courseid=$this->get_courseid($lessonid);
        $sql=$this->gen_sql("delete from %s where lessonid=%u and lesson_status=0",
                            self::DB_TABLE_NAME
                            ,$lessonid) ;
        $ret=$this->main_update($sql);
        if ($ret) {
            $this->t_homework_info->row_delete($lessonid);
        }
        $this->reset_lesson_list($courseid);
        return $ret;
    }

    public function add_lesson($courseid, $lesson_num, $userid,$from_type,$lesson_type,  $teacherid, $assistantid
                               ,$lesson_start,$lesson_end,$grade,$subject,$lesson_count=200
                               ,$teacher_money_type=0,$level=0,$competition_flag=0,$server_type=2,$week_comment_num=0
                               ,$enable_video=0,$lesson_sub_type=0,$train_type=0
    ){
        $ret = $this->row_insert([
            'lesson_type'        => $lesson_type,
            'courseid'           => $courseid,
            'lesson_num'         => $lesson_num,
            'from_type'          => $from_type,
            'userid'             => $userid,
            'thumbnail'          => 'http://7u2f5q.com2.z0.glb.qiniucdn.com/thumbnail_default.png',
            'tea_cw_name'        => '第'. $lesson_num . '课教案(老师版)',
            'stu_cw_name'        => '第'. $lesson_num . '课教案(学生版)',
            'grade'              => $grade,
            'subject'            => $subject,
            'teacherid'          => $teacherid,
            'assistantid'        => $assistantid,
            'lesson_start'       => $lesson_start,
            'lesson_end'         => $lesson_end,
            'lesson_count'       => $lesson_count,
            'teacher_money_type' => $teacher_money_type,
            'level'              => $level,
            'competition_flag'   => $competition_flag,
            'server_type'        => $server_type,
            'week_comment_num'   => $week_comment_num,
            'enable_video'       => $enable_video,
            'lesson_sub_type'    => $lesson_sub_type,
            'train_type'         => $train_type,
        ]);

        if ($ret ==1 ) {
            return $this->get_last_insertid();
        } else {
            return false;
        }
    }

    public function add_lesson_new($courseid,$lesson_num,$userid,$from_type,$lesson_type
                                   ,$teacherid,$assistantid,$lesson_start,$lesson_end,$grade
                                   ,$subject,$lesson_count=200,$teacher_money_type=0,$level=0,$competition_flag=0
                                   ,$stu_cw_upload_time,$stu_cw_status,$stu_cw_url,$tea_cw_name,$tea_cw_upload_time
                                   ,$tea_cw_status,$tea_cw_url,$lesson_quiz,$lesson_quiz_status,$tea_more_cw_url=""
                                   ,$server_type =2
    ){
        $ret = $this->row_insert([
            'lesson_type'        => $lesson_type,
            'courseid'           => $courseid,
            'lesson_num'         => $lesson_num,
            'from_type'          => $from_type,
            'userid'             => $userid,
            'thumbnail'          => 'http://7u2f5q.com2.z0.glb.qiniucdn.com/thumbnail_default.png',
            'tea_cw_name'        => '第'. $lesson_num . '课教案(老师版)',
            'stu_cw_name'        => '第'. $lesson_num . '课教案(学生版)',
            'grade'              => $grade,
            'subject'            => $subject,
            'teacherid'          => $teacherid,
            'assistantid'        => $assistantid,
            'lesson_start'       => $lesson_start,
            'lesson_end'         => $lesson_end,
            'lesson_count'       => $lesson_count,
            'teacher_money_type' => $teacher_money_type,
            'level'              => $level,
            'competition_flag'   => $competition_flag,
            'stu_cw_url'         => $stu_cw_url,
            'stu_cw_upload_time' => $stu_cw_upload_time,
            'stu_cw_status'      => $stu_cw_status,
            'tea_cw_name'        => $tea_cw_name,
            'tea_cw_upload_time' => $tea_cw_upload_time,
            'tea_cw_status'      => $tea_cw_status,
            'tea_cw_url'         => $tea_cw_url,
            'lesson_quiz_status' => $lesson_quiz_status,
            'lesson_quiz'        => $lesson_quiz,
            'tea_more_cw_url'    => $tea_more_cw_url,
            'server_type'        => $server_type
        ]);

        if ($ret ==1 ) {
            return $this->get_last_insertid();
        } else {
            return false;
        }
    }


    public function get_lesson_use_all($studentid)
    {
        $lesson_status = $this->where_get_in_str("lesson_status", [
            E\Elesson_status::V_1,
            E\Elesson_status::V_2,
            E\Elesson_status::V_3
        ]);
        $lesson_type = $this->where_get_in_str("lesson_type", [
            E\Econtract_type::V_0,
            E\Econtract_type::V_1,
            E\Econtract_type::V_3,
        ]);
        $where_arr=[
            $lesson_status,
            $lesson_type,
            "confirm_flag not in (2,4)" ,
            "lesson_del_flag=0"
        ];
        $sql = $this->gen_sql("select sum(lesson_count) from %s where userid = %u and %s ",
                              self::DB_TABLE_NAME,
                              $studentid,
                              [$this->where_str_gen($where_arr)]
        );
        return $this->main_get_value($sql);
    }

    public function get_last_lesson_time($userid)
    {
        $sql=$this->gen_sql("select max(lesson_start) "
                            ." from %s "
                            ." where userid=%u "
                            ." and lesson_status=2 "
                            ." and lesson_type in (0,1,3) "
                            ." and confirm_flag not in (2,4)"
                            ." and lesson_del_flag=0"
                            ,self::DB_TABLE_NAME
                            ,$userid
        );
        return $this->main_get_value($sql);
    }

    public function get_current_student_list_by_start_time( $lesson_start ) {
        $sql=$this->gen_sql("select userid from %s where lesson_start=%u and userid>0 "
                            ." and lesson_del_flag=0 "
                            ,self::DB_TABLE_NAME
                            ,$lesson_start
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_list($time_start,$time_end,$lessonid=0){
        if($lessonid==0){
            $where_str = sprintf("a.lesson_end<=%u and a.lesson_end>%u and a.lesson_type=4001"
                                 ,$time_start,$time_end);
        }else{
            $where_str = sprintf("a.lessonid=%u",$lessonid);
        }

        $sql=$this->gen_sql(" select a.lessonid,a.from_lessonid,"
                            ." b.draw,b.audio,b.real_begin_time,b.real_end_time "
                            ." from %s a"
                            ." left join %s as b on b.lessonid=a.from_lessonid"
                            ." where %s"
                            . " and a.lesson_del_flag=0 "
                            ,self::DB_TABLE_NAME
                            ,self::DB_TABLE_NAME
                            ,$where_str
        );
        return $this->main_get_list($sql);
    }

    public function set_upload_info($lessonid,$real_begin,$real_end,$draw,$audio){
        $set_field_arr = array(
            self::C_lessonid        => $lessonid,
            self::C_real_begin_time => $real_begin,
            self::C_real_end_time   => $real_end,
            self::C_draw            => $draw,
            self::C_audio           => $audio,
        );
        $this->field_update_list($lessonid,$set_field_arr);
    }

    /**
     * 检测更改的课时
     */
    public function check_lesson_count_for_change( $lessonid,$lesson_count) {
        $courseid = $this->get_courseid($lessonid);

        $lesson_total = $this->t_course_order->get_lesson_count_all($courseid);
        $lesson_use   = $this->get_lesson_count_all_without_lessonid($courseid,$lessonid);
        $assigned_lesson_count = $this->t_course_order->get_assigned_lesson_count($courseid);
        if ($assigned_lesson_count>0) {
            $lesson_total=$assigned_lesson_count;
        }

        \App\Helper\Utils::logger("lesson total:".$lesson_total."lesson use:".$lesson_use."lesson count:".$lesson_count);
        if (($lesson_use+$lesson_count)>$lesson_total) {
            return false;
        }
        return true;
    }

    public function get_lesson_count_all_without_lessonid($courseid,$lessonid) {
        $sql = $this->gen_sql_new("select sum(lesson_count) "
                                  ." from %s "
                                  ." where courseid=%u "
                                  ." and lessonid<>%u "
                                  ." and confirm_flag not in (2,4) "
                                  ." and lesson_del_flag=0 "
                                  ,self::DB_TABLE_NAME
                                  ,$courseid
                                  ,$lessonid
        );
        return $this->main_get_value($sql);
    }

    /**
     * 判断条件
     * http://bbs.csdn.net/topics/360003491
     * 假设已有 t1<=t2, t3<=t4
     * 不重叠： t2<=t3 || t4<=t1
     * 重叠取反即可：t3<t2 && t4>t1
     */
    public function check_student_time_free( $userid,$cur_lessonid, $lesson_start,$lesson_end ) {
        $where_arr = [
            ["userid=%u",$userid,0],
            ["l.lessonid<>%u",$cur_lessonid,0],
            ["l.lesson_end>%u",$lesson_start,0],
            ["l.lesson_start<%u",$lesson_end,0],
        ];

        $sql = $this->gen_sql_new("select l.lessonid,lesson_start,lesson_end "
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid "
                                  ." where %s "
                                  ." and confirm_flag<2"
                                  ." and lesson_type in (0,1,2,3,3001)"
                                  ." and lesson_del_flag =0 "
                                  ." and (tss.success_flag is null or tss.success_flag <>2)"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function check_train_lesson_time_free( $userid,$cur_lessonid, $lesson_start,$lesson_end ) {
        $sql=$this->gen_sql("select l.lessonid,lesson_start,lesson_end "
                            ." from %s l "
                            ." left join %s ta on l.lessonid = ta.lessonid "
                            ." where ta.userid=%u "
                            ." and l.lessonid<> %d "
                            ." and %u < lesson_end "
                            ." and %u > lesson_start "
                            ." and confirm_flag<2"
                            ." and lesson_type =1100"
                            ." and lesson_del_flag =0 "
                            ." and l.lesson_sub_type=1"
                            ." and l.train_type=5"
                            ,self::DB_TABLE_NAME
                            ,t_train_lesson_user::DB_TABLE_NAME
                            ,$userid
                            ,$cur_lessonid
                            ,$lesson_start
                            ,$lesson_end
        );
        return $this->main_get_row($sql);
    }

    public function check_teacher_time_free($teacherid,$cur_lessonid, $lesson_start,$lesson_end){
        $sql = $this->gen_sql_new("select l.lessonid,lesson_start,lesson_end "
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." where teacherid= %u "
                                  ." and l.lessonid<> %d "
                                  ." and (%u<lesson_end and %u>lesson_start) "
                                  ." and confirm_flag not in (2,3) "
                                  ." and lesson_type!=4001"
                                  ." and lesson_del_flag =0"
                                  ." and (tss.success_flag is null or tss.success_flag<>2)"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,$teacherid
                                  ,$cur_lessonid
                                  ,$lesson_start
                                  ,$lesson_end
        );
        return $this->main_get_row($sql);
    }

    public function set_lesson_time($lessonid, $start, $end) {
        $now = time(NULL);
        if ($now >$start && $now < $end ){//时间在
            $lesson_status=1;
        }else{
            $lesson_status=0;
        }
        $userid=$this->get_userid($lessonid);
        $server_type=2;

        $sql = sprintf("update %s set lesson_start = %u, lesson_end = %u,lesson_status=%u  ,lesson_upload_time=0 "
                       .",server_type=%u  where lessonid = %u "
                       ,self::DB_TABLE_NAME, $start, $end, $lesson_status,$server_type , $lessonid);
        $ret= $this->main_update($sql);

        if ($ret==1) {
            $this->reset_lesson_list($this->get_courseid($lessonid));
        }
        return $ret;
    }

    public function get_lesson_list_info($userid,$start,$end,$lesson_status=2)
    {
        $where_arr = [
            ["l.lesson_start>%u",$start,0],
            ["l.lesson_start<%u",$end,0],
            ["l.userid=%u",$userid,-1],
            ["l.lesson_status=%u",$lesson_status,-1],
            "l.lesson_type<1000",
            "l.lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.userid,l.teacherid,l.assistantid,l.lesson_start,l.lesson_num,l.stu_attend, "
                                  ." l.lesson_end,l.lesson_count,l.teacher_score,l.teacher_comment,l.teacher_effect, "
                                  ." l.teacher_quality,l.teacher_interact,l.stu_performance,l.subject,l.lesson_type,"
                                  ." l.stu_score,l.stu_comment,l.stu_attitude,l.stu_attention,"
                                  ." l.teacher_type as lesson_teacher_type,t.teacher_type,l.operate_time,"
                                  ." l.stu_ability,l.stu_stability,l.confirm_flag,c.reset_lesson_count_flag "
                                  ." from %s l"
                                  ." left join %s c on l.courseid = c.courseid"
                                  ." left join %s t on l.teacherid= t.teacherid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_confirm_lesson_list($start_time,$end_time) {
        $sql = $this->gen_sql("select l.assistantid ,sum(lesson_count) as lesson_count,count(*) as count, count(distinct l.userid ) as user_count,a.nick assistant_nick from  %s  l, %s s,%s a  ".
                            " where  l.userid=s.userid  and l.assistantid = a.assistantid and is_test_user=0 and lesson_start >=%u and lesson_start<%u  and lesson_status =2 and confirm_flag not in (2,4)  and lesson_type in (0,1,3)"
                            ." and lesson_del_flag=0 and l.assistantid <> 59329  "
                            ." group by l.assistantid  order by lesson_count desc",
                            self::DB_TABLE_NAME,
                            t_student_info::DB_TABLE_NAME,
                            t_assistant_info::DB_TABLE_NAME,
                            $start_time,$end_time
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_confirm_lesson_list_user($page_num, $start_time,$end_time,$assistantid, $page_number=30) {
        $where_arr=[
            ["s.assistantid= %u",$assistantid, -1  ],
        ];
        $sql=$this->gen_sql_new("select s.assistantid, s.userid ,s.grade,sum(lesson_count) as lesson_count,count(*) as count from  %s  l, %s s ".
                                " where  l.userid=s.userid  and is_test_user=0 and lesson_start >=%u and lesson_start<%u  and confirm_flag not in (2,4)  and lesson_type in (0,1,3) and %s "
                                . " and lesson_del_flag=0 "
                                ." group by l.userid,l.subject ",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME, //
                                $start_time,$end_time,  $where_arr);
        return $this->main_get_list_by_page($sql,$page_num,$page_number,true);
    }
    public function get_single_confirm_lesson_list_user($page_num, $start_time,$end_time,
        $assistantid,$teacherid,$studentid,$num) {
        $where_arr=[
            ["s.assistantid= %u",$assistantid, -1  ],
            ["l.teacherid= %u",$teacherid, -1  ],
            ["s.userid= %u",$studentid, -1  ],
        ];
        $sql=$this->gen_sql_new("select s.assistantid, s.userid ,s.phone,l.subject,l.teacherid,l.grade,sum(l.lesson_count) as lesson_count,count(*) as count,sum(o.price) price from  %s  l left join %s s on  l.userid=s.userid "
                                ."left join %s o on l.lessonid = o.lessonid "
                                . " where  is_test_user=0 and lesson_start >=%u and lesson_start<%u  and confirm_flag not in (2,4)  and lesson_type in (0,1,3) and %s "
                                . " and lesson_del_flag=0 "
                                ." group by l.userid ,l.subject ",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                t_order_lesson_list::DB_TABLE_NAME,//
                                $start_time,$end_time,  $where_arr);
        if($num >= 1){
            $sql .= "having count(*) = ".$num;
        }
        return $this->main_get_list_by_page($sql,$page_num,5000,true);
    }

    public function get_student_single_subject($start_time,$end_time,$teacherid,$subject,$studentid){
        $where_arr=[
            ["l.subject= %u",$subject, -1  ],
            ["l.teacherid= %u",$teacherid, -1  ],
            ["s.userid= %u",$studentid, -1  ],
        ];
        $sql=$this->gen_sql_new("select s.assistantid, s.userid, l.teacherid,l.lesson_start,l.lesson_end, l.lesson_count  as count from  %s  l, %s s  ".
                                " where  l.userid=s.userid  and is_test_user=0 and lesson_start >=%s and lesson_start<%s  and confirm_flag not in (2,4)  and lesson_type in (0,1,3) and %s "
                                . " and lesson_del_flag=0 ",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME, //
                                $start_time,$end_time,  $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_confirm_lesson_total($start_time,$end_time ) {
        $sql=$this->gen_sql("select sum(lesson_count) as lesson_count,count(*) as count, "
                            ." count(distinct(l.userid)) as user_count "
                            ." from  %s  l, %s s "
                            ." where  l.userid=s.userid "
                            ." and is_test_user=0 "
                            ." and lesson_start >=%s "
                            ." and lesson_start<%s "
                            ." and lesson_status =2 "
                            ." and confirm_flag not in (2,4) "
                            ." and lesson_type in (0,1,3) "
                            ,self::DB_TABLE_NAME
                            ,t_student_info::DB_TABLE_NAME
                            ,$start_time
                            ,$end_time
        );
        return $this->main_get_row($sql);
    }

    public function get_tea_confirm_lesson_list($start_time,$end_time,$teacher_money_type) {
        $where_arr = [
            ["lesson_start>=%s",$start_time,0],
            ["lesson_start<%s",$end_time,0],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            "confirm_flag!=2",
            "lesson_status=2",
            "s.is_test_user=0",
            "t.is_test_user=0",
            "lesson_del_flag=0",
        ];
        $sql=$this->gen_sql_new("select l.teacherid,sum(lesson_count) as lesson_count,"
                                ." sum(if(lesson_type=2,lesson_count,0)) as trial_lesson_count,"
                                ." sum(if(lesson_type!=2,lesson_count,0)) as normal_lesson_count,"
                                ." count(1) as count,"
                                ." count(distinct(l.userid)) as stu_num,"
                                ." t.teacher_money_type,t.subject,t.realname"
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid"
                                ." left join %s t on l.teacherid=t.teacherid"
                                ." where %s"
                                ." and lesson_type <1000 "
                                ." and lesson_del_flag=0 "
                                ." group by l.teacherid "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_lesson_list_for_tongji($start_time,$end_time) {
        $sql=$this->gen_sql("select lessonid, l.userid,lesson_start,lesson_type, lesson_count  from  %s  l, %s s ".
                            " where  l.userid=s.userid "
                            ." and is_test_user=0 and lesson_start >=%s and lesson_start<%s  and lesson_status =2 and confirm_flag <>2  and lesson_type in (0,1,2,3) "
                            . " and lesson_del_flag=0 ",
                            self::DB_TABLE_NAME,
                            t_student_info::DB_TABLE_NAME, //
                            $start_time,$end_time);
        return $this->main_get_list($sql);
    }

    public function get_lesson_list_by_teacher_student($teacherid,$studentid){
        $sql = $this->gen_sql("select lessonid,lesson_start,already_lesson_count,lesson_count,confirm_flag "
                              ." from %s "
                              ." where teacherid=%u "
                              ." and userid=%u "
                              ." and lesson_type in (0,1,3) "
                              ." and lesson_del_flag=0 "
                              ." and confirm_flag!=2"
                              ." order by lesson_start asc "
                              ,self::DB_TABLE_NAME
                              ,$teacherid
                              ,$studentid
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_student_grade_list( $start_time,$end_time){
        $where_arr=[
            ["lesson_start>=%u", $start_time, -1],
            ["lesson_start<%u", $end_time, -1],
        ];
        $sql=$this->gen_sql_new("select userid,count(DISTINCT grade) grade_count,sum(grade=0) fail_count "
                                ." from %s"
                                ." where %s "
                                ." and lesson_status=2 "
                                ." and lesson_type in (0,1,3) "
                                ." and lesson_del_flag=0 "
                                ." group by userid "
                                ." having (grade_count>1 or fail_count>0)"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_ass_lesson_info( $start_time,$end_time, $require_adminid_list) {
        $where_arr=[
            "lesson_type in (0,1,3)",
            "lesson_cancel_reason_type not in ( 1,2 )",  //调课不处理
            "lesson_del_flag = 0",
            "s.is_test_user = 0",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str("m.uid",$require_adminid_list);

        $sql=$this->gen_sql_new(
            "select sum(  lesson_count) as all_lesson_count ,"
            ." sum( if(lesson_cancel_reason_type=11,lesson_count, 0   ) ) stu_fail_all_lesson_count, "
            ." sum( if(lesson_cancel_reason_type=12,lesson_count, 0   ) ) tea_fail_all_lesson_count, "
            ." sum( if(lesson_cancel_reason_type<>0 ,lesson_count, 0   ) ) fail_all_lesson_count, "
            ." sum( if( confirm_flag<>2 ,lesson_count, 0   ) ) succ_all_lesson_count "
            ." from %s  l "
            ." left join %s a on a.assistantid =  l.assistantid    "
            ." left join %s m on m.phone =  a.phone   "
            ." left join %s s on s.userid =  l.userid "
            ." where %s ",
            self::DB_TABLE_NAME,
            t_assistant_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_1v1_lesson_list_by_teacher($teacherid,$studentid, $start_time,$end_time){
        $where_arr=[
            ["l.userid=%u",$studentid,-1 ],
        ];
        $sql = $this->gen_sql("select l.lessonid,l.lesson_start,l.lesson_end,l.already_lesson_count,l.lesson_count,l.confirm_flag,"
                            ." l.userid,l.level,l.grade,l.teacher_money_type,ol.price as lesson_price,"
                            ." if(l.lesson_type=2,l.lesson_type,o.contract_type) as lesson_type "
                            ." from %s l"
                            ." left join %s s on l.userid=s.userid"
                            ." left join %s ol on l.lessonid=ol.lessonid"
                            ." left join %s o on o.orderid=ol.orderid"
                            ." where teacherid=%u "
                            ." and lesson_start>=%u "
                            ." and lesson_start<=%u "
                            ." and lesson_del_flag=0 "
                            ." and lesson_status=2 "
                            ." and l.confirm_flag!=2 "
                            ." and lesson_type in (0,1,2,3) "
                            ." and %s and s.is_test_user=0 "
                            ." group by l.lessonid"
                            ." order by lesson_start asc"
                            ,self::DB_TABLE_NAME
                            ,t_student_info::DB_TABLE_NAME
                            ,t_order_lesson_list::DB_TABLE_NAME
                            ,t_order_info::DB_TABLE_NAME
                            ,$teacherid
                            ,$start_time
                            ,$end_time
                            ,$this->where_str_gen($where_arr)
        );
        return $this->main_get_list($sql);
    }

    public function reset_teacher_student_already_lesson_count($teacherid,$studentid) {
        $lesson_list = $this->get_lesson_list_by_teacher_student($teacherid,$studentid);

        $cur_already_lesson_count = 0;
        foreach ($lesson_list as &$item) {
            $cur_already_lesson_count += $item["lesson_count"];
            if ($item["already_lesson_count"] != $cur_already_lesson_count) {
                $this->field_update_list($item["lessonid"],[
                    "already_lesson_count" => $cur_already_lesson_count
                ]);
            }
        }
    }

    public function get_student_list_by_teacher($teacherid,$start_time,$end_time){
        $where_arr = [
            ["lesson_start>=%u", $start_time,0],
            ["lesson_start<%u", $end_time,0],
            ["teacherid=%u", $teacherid,-1],
        ];
        $sql = $this->gen_sql_new("select distinct(userid) "
                                  ." from %s "
                                  ." where %s "
                                  ." and lesson_del_flag=0 "
                                  ." and confirm_flag!=2"
                                  ." and lesson_type<1000"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_lesson_list($start_time,$end_time) {
        $where_arr=[
            ["lesson_start>=%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "(s.is_test_user=0 or s.is_test_user is null)",
        ];

        $sql=$this->gen_sql_new("select l.teacherid,t.realname as tea_nick,l.userid,s.realname as stu_nick,"
                                ." lesson_start,lesson_end,lesson_type "
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid "
                                ." left join %s t on l.teacherid=t.teacherid"
                                ." where %s "
                                ." and lesson_type!=4001"
                                ." and confirm_flag<2"
                                ." and lesson_del_flag=0 "
                                ." order by lesson_start"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_list_not_set_performance($start,$end){
        $sql=$this->gen_sql("select lessonid,teacherid,lesson_start,lesson_end,grade,userid,teacherid,lesson_count "
                            ." from %s "
                            ." where lesson_end>%u "
                            ." and lesson_end <%u "
                            ." and lesson_type in (0,1,3)"
                            . " and lesson_del_flag=0 "
                            ." and tea_rate_time=0"
                            ,self::DB_TABLE_NAME
                            ,$start
                            ,$end
        );
        return $this->main_get_list($sql);
    }
    public function get_teacher_lesson_info(  $teacherid, $start_time,$end_time ) {
        $sql=$this->gen_sql("select l.userid,l.lessonid,lesson_start,lesson_end,l.lesson_type "
                            ." from %s l left join %s tss on l.lessonid = tss.lessonid "
                            ." where l.teacherid=%u "
                            ." and lesson_start>=%s "
                            ." and lesson_start<=%s "
                            ." and l.lesson_status<=2 "
                            . " and l.lesson_del_flag=0 "
                            ." and l.confirm_flag in(0,1) and if(l.lesson_type=2,tss.success_flag in (0,1),true)"
                            ." order by lesson_start asc ",
                            self::DB_TABLE_NAME,
                            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                            $teacherid, $start_time,$end_time );
        return $this->main_get_list($sql);
    }

    public function get_lesson_name_and_intro($lessonid){
        $sql=$this->gen_sql("select lesson_name,lesson_intro from %s where lessonid=%u"
                            ,self::DB_TABLE_NAME
                            ,$lessonid
        );
        return $this->main_get_row($sql);
    }

    public function set_subject_by_courseid($courseid, $subject )  {
        $sql= $this->gen_sql(" update %s  set subject=%u where courseid=%u ",
                             self::DB_TABLE_NAME,
                             $subject,$courseid);
        return $this->main_update($sql);
    }

    public function get_user_phone($lessonid){
        $sql=$this->gen_sql("select phone from %s l"
                            ." left join %s s on s.userid=l.userid"
                            ." where lessonid=%u"
                            ,self::DB_TABLE_NAME
                            ,Z\z_t_student_info::DB_TABLE_NAME
                            ,$lessonid
        );
        return $this->main_get_value($sql);
    }

    public function get_user_lesson_cost($start){
        $sql=$this->gen_sql("select l.userid,sum(lesson_count/100) as lesson_cost "
                            ." from %s l,%s s"
                            ." where lesson_end<%u "
                            ." and is_test_user=0"
                            ." and s.userid=l.userid"
                            ." and lesson_start>0"
                            ." and lesson_status=2"
                            ." and l.userid!=0 "
                            . " and lesson_del_flag=0 "
                            ." group by l.userid "
                            ,self::DB_TABLE_NAME
                            ,t_student_info::DB_TABLE_NAME
                            ,$start
        );
        return $this->main_get_list($sql,function($item){
            return $item['userid'];
        });
    }

    public function get_user_lesson_cost_by_courseid($courseid){
        $sql=$this->gen_sql("select sum(lesson_count/100)"
                            ." from %s"
                            ." where courseid=%u"
                            ." and lesson_status=2"
                            ." and lesson_start>0"
                            ,self::DB_TABLE_NAME
                            ,$courseid
        );
        return $this->main_get_value($sql);
    }

    public function get_lesson_conditions($start,$end,$st_application_nick,$userid,$teacherid,$run_flag, $assistantid,$require_adminid)
    {



        $sub_arr_2=[
            ["s.assistantid=%u", $assistantid,-1] ,
            ["require_adminid=%u", $assistantid ,-1] ,
        ];
        $sub_where_str_2="(".$this->where_str_gen($sub_arr_2, "or").")";



        $where_arr=[
            ["l.userid =%u", $userid,-1] ,
            ["l.teacherid=%u", $teacherid,-1] ,
            ["require_adminid=%u", $require_adminid,-1] ,
            "confirm_flag not in (2, 3)",
            $sub_where_str_2,
        ];

        if ($run_flag ==1) {
            $now=time(NULL);
            $where_arr[] =  sprintf ( "((lesson_start -600 <%u and lesson_end+ 600> %u ) or lesson_status = 1) ", $now ,$now );
        }else if ($run_flag==2) {
            $where_arr[] = "lesson_type=2";
        }


        $sql = $this->gen_sql_new(
            "select l.lessonid,  require_adminid,  account, l.userid,  l.teacherid ,   l.assistantid , lesson_start, lesson_end, l.courseid,  l.lesson_type, " .
            " lesson_num,   c.current_server ,  server_type , l.lesson_condition,lesson_status, xmpp_server_name  ".
            " from    %s l " .
            " left join %s c on c.courseid = l.courseid  ".
            " left join %s tss on l.lessonid = tss.lessonid ".
            " left join %s tr on tr.require_id = tss.require_id ".
            " left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id ".
            " left join %s m on t.require_adminid=m.uid ".
            " left join %s s on l.userid=s.userid".

            " where  lesson_start > %u ".
            "and lesson_start < %u and %s "
            . " and lesson_del_flag=0 "
            ."  order by lesson_start asc, lessonid asc  ",
            self::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME ,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,

            $start, $end, $where_arr);
        return $this->main_get_list_as_page($sql,function($item){
            return $item["lessonid"];
        });
    }

    public function get_lesson_left_info(){
        $sql=$this->gen_sql("select count(lessonid) as lesson_left,courseid "
                            ." from %s"
                            ." where lesson_type<3000"
                            ." and lesson_type>1000"
                            ." and lesson_status=0"
                            . " and lesson_del_flag=0 "
                            ." group by courseid"
                            ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }


    public function get_current_lessons($record_audio_server)
    {
        $where_arr=[
        ];

        if ($record_audio_server) {
            $where_arr[]= sprintf("(record_audio_server1='%s' or record_audio_server2='%s')"
                                  ,$record_audio_server
                                  ,$record_audio_server);
        }

        $now=time();
        //4001:机器课
        $sql = $this->gen_sql("select lessonid, c.courseid,lesson_num,lesson_type,real_begin_time,real_end_time,server_type,".
                       " l.teacherid , current_server,record_audio_server1,record_audio_server2 from %s l, %s c ".
                       "where l.courseid = c.courseid  and lesson_status = 1 and  ".
                       "%u-3600*5 < lesson_start and %u +3600 > lesson_start  and lesson_type <> 4001  and %s "
                              . " and lesson_del_flag=0 "
                              ." order by lesson_start desc",
                              self::DB_TABLE_NAME, t_course_order::DB_TABLE_NAME, $now, $now,
                              [$this->where_str_gen($where_arr)]);
        return $this->main_get_list($sql);
    }




    public function get_finish_lessons()
    {
        /*
          $sql = $this->gen_sql ("select lessonid, c.courseid,lesson_num,lesson_type,real_begin_time,real_end_time, l.teacherid , current_server, lesson_start, lesson_end ,server_type, record_audio_server1 ,record_audio_server2 from %s l, %s c ".
          "where l.courseid = c.courseid and lesson_upload_time = 0 and lesson_status = 2 and real_begin_time != 0 and lesson_type != 4001 and  ".
          "%u - 86400*2 <  lesson_start  and %s "
          ." and lesson_del_flag=0 "
          ." order by lesson_start desc",
          self::DB_TABLE_NAME, t_course_order::DB_TABLE_NAME, time(NULL),
          [$this->where_str_gen($where_arr)]);
        */
        return $this->main_get_list($sql);
    }
    public function get_current_audio_server_list ($add_client_ip="") {
        $key = "audio_server_list";

        $audio_server_list=\App\Helper\Common::redis_get_json($key);
        if(!$audio_server_list) {
            $audio_server_list=[];
        }
        $now=time(NULL);
        $min_time=$now-60;
        foreach ($audio_server_list as $k=>$v) {
            if ($v<$min_time) {
                unset($audio_server_list[$k]);
            }
        }
        if ($add_client_ip) {
            $audio_server_list[$add_client_ip]=time(NULL);
        }
        \App\Helper\Common::redis_set_json($key,$audio_server_list);
        return $audio_server_list;
    }

    public function test_lesson_get_tongji($start_time,$end_time,$admin_revisiterid){
        $where_arr=[
            ["admin_revisiterid=%u",$admin_revisiterid,-1]
        ];

        $sql = $this->gen_sql_new(
            "select if((lesson_start>=%u and lesson_start<%u),lesson_start,cancel_lesson_start) as opt_time, cancel_flag,"
            ." admin_revisiterid, confirm_flag "
            ." from %s b  left join %s s on b.userid=s.userid  "
            ." LEFT JOIN %s as l"
            ." ON b.st_arrange_lessonid = l.lessonid"
            ." where ((lesson_start>=%u and lesson_start<%u ) "
            ." or ( cancel_lesson_start>=%u and cancel_lesson_start<%u )) "
            ." and is_test_user=0 "
            ." and b.st_arrange_lessonid>1 "
            ." and %s "
            ." and lesson_del_flag=0 "
            ,$start_time
            ,$end_time
            ,t_seller_student_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$start_time
            ,$end_time
            ,$start_time
            ,$end_time
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function test_lesson_require_tongji($start_time,$end_time,$admin_revisiterid) {
        $where_arr=[
            ["admin_revisiterid=%u",$admin_revisiterid,-1]
        ];

        $sql = $this->gen_sql_new("select st_application_time as opt_time from  %s s left join %s b on s.userid=b.userid  ".

                              " where st_application_time >=%u and st_application_time<%u  and is_test_user=0 and %s ",
                              t_student_info::DB_TABLE_NAME,
                              t_seller_student_info::DB_TABLE_NAME,
                                  $start_time, $end_time, $where_arr);
        return $this->main_get_list($sql);
    }
    public function admin_revisiter_test_lesson_require_tongji($start_time,$end_time,$admin_revisiterid) {
        $where_arr=[
            ["admin_revisiterid=%u",$admin_revisiterid,-1]
        ];

        $sql = $this->gen_sql_new("select  admin_revisiterid,sum(cancel_flag <>2 ) as count from  %s s left join %s b on s.userid=b.userid  ".

                                  " where st_application_time >=%u and st_application_time<%u  and is_test_user=0 and %s group by admin_revisiterid ",
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_info::DB_TABLE_NAME,
                                  $start_time, $end_time, $where_arr);
        return $this->main_get_list($sql);
    }
    public function admin_revisiter_test_lesson_get_tongji($start_time,$end_time,$admin_revisiterid) {
        $where_arr=[
            ["admin_revisiterid=%u",$admin_revisiterid,-1]
        ];

        $sql = $this->gen_sql_new(
            "select admin_revisiterid, count(*) as  test_lesson_count,".
            " sum(confirm_flag=3) test_lesson_count_fail_need_money ,  "
            ."sum(  confirm_flag in(0,1)  ) test_lesson_count_succ, "
            ."sum(    cancel_flag=2  ) test_lesson_count_change_time "

            ." from %s b  left join %s s on b.userid=s.userid  ".
            "    LEFT JOIN %s as l"
            ."    ON b.st_arrange_lessonid = l.lessonid".

            " where ((lesson_start>=%u and lesson_start<%u ) or ( cancel_lesson_start>=%u and cancel_lesson_start<%u ))  and is_test_user=0 and  b.st_arrange_lessonid >1 and %s "
            ." and (lesson_del_flag=0 or lesson_del_flag is null) "
            ." group by admin_revisiterid "
            ,
            t_seller_student_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $start_time, $end_time,
            $start_time, $end_time,
            $where_arr);
        return $this->main_get_list($sql);
    }




    public function get_lesson_now($time,$userid){
        $sql=$this->gen_sql("select count(1) from %s"
                            ." where lesson_start<%u"
                            ." and lesson_end>%u"
                            ." and userid=%u"
                            ,self::DB_TABLE_NAME
                            ,$time
                            ,$time
                            ,$userid
        );
        return $this->main_get_value($sql);
    }
    public function get_teacher_student_list( $teacherid ) {
        $sql=$this->gen_sql_new("select userid ,subject , max(lesson_start) as max_lesson_start from %s where teacherid=%u and userid<>0 and lesson_type in (0,1,3) "
                                ." and lesson_del_flag=0 "
                                ." group by userid ,subject order by max_lesson_start  desc" ,
                                self::DB_TABLE_NAME,
                                $teacherid);
        return $this->main_get_list($sql);
    }

    public function get_course_lesson_count( $courseid ) {
        $sql =$this->gen_sql_new("select count(1) from %s where courseid=%u",
                                 self::DB_TABLE_NAME,
                                 $courseid);
        return $this->main_get_value($sql);

    }
    public function course_set_teacher_subject($courseid,$teacherid,$subject) {
        $sql=$this->gen_sql("update %s set subject=%u where courseid=%u and teacherid=%u",
                            self::DB_TABLE_NAME,  $subject, $courseid, $teacherid);

        return $this->main_update($sql);
    }

    public function get_lesson_info_for_teacher($start_time,$end_time ,$has_check_adminid_flag, $check_adminid)  {
        $where_arr = [
            ["check_adminid=%u",$check_adminid,-1],
        ];

        if ($has_check_adminid_flag==0) {
            $where_arr[] = "(check_adminid=0)";
        }else if ($has_check_adminid_flag==1) {
            $where_arr[] = "check_adminid>0";
        }

        $sql = $this->gen_sql_new(
            "select t.check_adminid,l.teacherid,t.realname,t.nick,t.teacher_money_type,t.level,"
            ." sum( if( lesson_type in (0,1,3) and confirm_flag<>2,l.lesson_count,0)) as l1v1_lesson_count,"
            ." sum( if( lesson_type=2 and confirm_flag<>2,1,0)) as test_lesson_count, "
            ." sum( if( confirm_flag<>2 , price, 0 )) as all_lesson_money"
            ." from  %s l "
            ." left join %s s on l.userid=s.userid "
            ." left join %s t on l.teacherid=t.teacherid "
            ." left join %s o on l.lessonid=o.lessonid"
            ." where lesson_start>=%u and lesson_end<%u "
            ." and s.is_test_user=0 and %s "
            ." and lesson_del_flag=0 "
            ." group by teacherid "
            ." order by l1v1_lesson_count desc "
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_teacher_info::DB_TABLE_NAME
            ,t_order_lesson_list::DB_TABLE_NAME
            ,$start_time
            ,$end_time
            ,$where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function set_teacher_level_info_from_now($teacherid,$teacher_money_type,$level,$start_time) {
        $sql=$this->gen_sql_new(
            "update %s set teacher_money_type=%u,level=%u where teacherid=%u and lesson_start > %u  ",
            self::DB_TABLE_NAME,
            $teacher_money_type,
            $level,
            $teacherid,
            $start_time);
        return $this->main_update($sql);
    }

    /**
     * 此方法供 UpdateOrderLessonList 命令来刷课时收入使用
     * 请勿随意更改
     * @param int userid 学生id
     * @param int competition 竞赛标示
     * @param int start_time  开始时间
     * @param int end_time    结束时间
     * @param int lesson_status 课程状态
     * @author adrian
     */
    public function get_user_lesson_list($userid,$competition=-1,$start_time=0,$end_time=0,$lesson_status=2){
        $where_str = [
            ["competition_flag=%u",$competition,-1],
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            ["userid=%u",$userid,0],
            ["lesson_status=%u",$lesson_status,-1],
            "lesson_count>0",
            "lesson_type in (0,1,3)",
            "confirm_flag in (0,1,3)",
            "lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select lessonid,lesson_count,lesson_type,lesson_start,lesson_end,lesson_status,"
                                ." teacherid,grade,userid,competition_flag,teacher_money_type"
                                ." from %s"
                                ." where %s"
                                ." order by lesson_start asc"
                                ,self::DB_TABLE_NAME
                                ,$where_str
        );
        return $this->main_get_list($sql);
    }

    public function get_user_lesson_list_sum($userid,$competition){
        $where_str=$this->where_str_gen([
            ["competition_flag=%u",$competition,-1],
            "lesson_del_flag=0"
        ]);
        $sql=$this->gen_sql_new("select format(sum(lesson_count)/100,1) as lesson_sum"
                                ." from %s"
                                ." where userid=%u"
                                ." and confirm_flag<2"
                                ." and lesson_status=2"
                                ." and lesson_type in (0,1,3)"
                                ." and %s"
                                ,self::DB_TABLE_NAME
                                ,$userid
                                ,$where_str
        );
        return $this->main_get_value($sql);
    }

    public function get_no_binding_test_lesson_list ($page_num,$start_time,$end_time) {
        $sql=$this->gen_sql_new("select l.lessonid, l.teacherid, l.userid,  lesson_start, lesson_end  from %s l  ".
                                ' left join %s s on  s.userid=l.userid '.
                                ' left join %s ss on  ss.st_arrange_lessonid =l.lessonid '.
                                " where  lesson_start>=%u and lesson_end <%u and  ss.st_arrange_lessonid is null  and lesson_type= 2  and is_test_user = 0 "
                                ." and lesson_del_flag=0 " ,
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                t_seller_student_info::DB_TABLE_NAME,
                                $start_time, $end_time);
        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function get_test_lesson_list(){
        $sql=$this->gen_sql("select s.userid,s.phone,s.realname,s.grade "
                            ." from %s s where s.userid in "
                            ." (select op.userid "
                            ." from %s op "
                            ." where op.lessonid in (14877,15221) and not exists ("
                            ." select o.userid from %s o where op.userid=o.userid and contract_type in (0,1,2,3,3001)"
                            //." ) and exists ("
                            //." select o.userid from %s o where op.userid=o.userid and contract_type=2"
                            ." )"
                            ." )"
                            ." and lesson_del_flag=0 "
                            ." group by s.userid"
                            ,t_student_info::DB_TABLE_NAME
                            ,t_open_lesson_user::DB_TABLE_NAME
                            ,t_order_info::DB_TABLE_NAME
                            ,t_order_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
    public function get_error_record_lesson_list( $page_num,$lesson_type, $start_time, $end_time)  {
        $where_arr = [
            "(audio='' or draw='')"	,
            "is_test_user =0 "	,
            "lesson_upload_time <>0 ",
            array("lesson_start > %u",$start_time,-1),
            array("lesson_start <= %u",$end_time,-1),
            array("lesson_type = %u",$lesson_type,-1),
        ];

        // userid,  is_test_user=0
        //t_student_info::DB_TABLE_NAME
        $sql = $this->gen_sql_new(
            "select l.lessonid,lesson_type, draw,audio,lesson_upload_time,lesson_start, lesson_end, l.userid, teacherid  from %s l, %s s  where l.userid=s.userid and   %s "
            ." and lesson_del_flag=0 "
            ." order by lesson_start ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function tongji_count($start_time,$end_time) {
        $sql=$this->gen_sql_new("select sum(lesson_type=2 ) as test_lesson_count , sum(lesson_type in (0,1,3) ) as l1v1_count  from %s   ".
                                "where lesson_start>=%u and lesson_start<%u",
                                self::DB_TABLE_NAME,$start_time, $end_time);
        return $this->main_get_row($sql);
    }

    public function get_user_list( $day_count="" ,$is_auto_set_type_flag=0)
    {
        $now=time(NULL);
        $where_arr=[
            ["s.is_auto_set_type_flag = %u",$is_auto_set_type_flag,-1],
            " l.lesson_type in( 0, 1,3) ",
        ];
        if(!empty($day_count)){
            $where_arr[] = "l.lesson_start > $now - $day_count*86400" ;
        }
        $sql = $this->gen_sql_new("select distinct l.userid as userid from %s l left join %s s on l.userid = s.userid ".
                                  "  where %s and  l.userid > 0  "
                                  ." and lesson_del_flag=0 " ,
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql, function($item ) {
            return $item["userid"];
        });

    }
    public function tongji_record_server_info($start_time,$end_time) {

        $where_arr = [
            array("lesson_start > %u",$start_time,-1),
            array("lesson_start <= %u",$end_time,-1),
            "lesson_type <> 4001 ",
            "confirm_flag in (0,1)  ",
        ];

        $sql= $this->gen_sql_new(
            "select record_audio_server1 as server, count(*) as count, sum( lesson_status = 1)active_count "
            ." from %s where %s   "
            ." and lesson_del_flag=0 "
            ."group by record_audio_server1 ",
            self::DB_TABLE_NAME, $where_arr ) ;
        return $this->main_get_list($sql);
    }
    public function get_succ_test_lesson_count($userid) {
        $sql = $this->gen_sql_new("select count(1) from %s where userid=%u and lesson_del_flag=0 ",
                                  self::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_all_lesson_info($lessonid){
        $sql=$this->gen_sql_new("select lessonid,lesson_name,lesson_start,lesson_end,l.subject,l.grade,l.teacherid,"
                                ." real_begin_time,real_end_time,stu_cw_url,lesson_num,lesson_type,l.userid,"
                                ." lesson_intro,draw,audio,c.packageid,c.course_name,l.lesson_count"
                                ." from %s l"
                                ." left join %s c on l.courseid=c.courseid"
                                ." where lessonid=%u"
                                ,self::DB_TABLE_NAME
                                ,t_course_order::DB_TABLE_NAME
                                ,$lessonid
        );
        return $this->main_get_row($sql);
    }

    public function get_teacher_lesson_list_new($page_num,$teacherid,$start_time,$end_time,$lesson_type,$lessonid=-1){
        if ($lessonid==-1) {
            $where_arr=[
                ["lesson_start>=%d",$start_time, -1 ] ,
                ["lesson_start<=%d",$end_time, -1 ] ,
                ["l.teacherid = %u",$teacherid, -1 ] ,
            ];
            $this->where_arr_add_int_or_idlist($where_arr,"l.lesson_type",$lesson_type);
        }else{
            $where_arr=[
                ["l.lessonid=%d",$lessonid, -1 ] ,
            ];
        }
        $sql =$this->gen_sql_new("select l.lessonid,l.lesson_type,lesson_start,lesson_end,lesson_intro,l.grade,l.subject,"
                                 ." l.lesson_num,l.train_type,l.userid,lesson_name,lesson_status, ass_comment_audit,l.userid,"
                                 ." h.work_status as homework_status,stu_cw_status as stu_status,"
                                 ." tea_cw_status as tea_status,editionid,"
                                 ." h.finish_url,h.check_url,l.tea_cw_url,l.stu_cw_url,h.issue_url,h.pdf_question_count"
                                 ." from %s l "
                                 ." left join %s h on l.lessonid=h.lessonid "
                                 ." left join %s seller on l.lessonid=seller.st_arrange_lessonid"
                                 ." left join %s s on l.userid=s.userid"
                                 ." where %s "
                                 //." and lesson_del_flag=0 "
                                 ."and lesson_del_flag=0 "
                                 ." order by lesson_start ",
                                 self::DB_TABLE_NAME ,
                                 t_homework_info::DB_TABLE_NAME ,
                                 t_seller_student_info::DB_TABLE_NAME ,
                                 t_student_info::DB_TABLE_NAME ,
                                 $where_arr);
        return $this->main_get_list_as_page($sql);
    }

    public function update_grade_by_userid($userid,$start_time,$grade ) {
        $sql = $this->gen_sql_new("update %s set  grade = %u where userid =%u and lesson_start>=%u",
                                  self::DB_TABLE_NAME,$grade,$userid,$start_time);
        return $this->main_update($sql);
    }

    public function get_lesson_list_new($courseid,$lessonid,$page_num){
        $where_arr=[
            ["courseid=%u", $courseid, -1 ] ,
            ["lessonid=%u", $lessonid, -1 ] ,
        ];

        $sql=$this->gen_sql_new("select courseid,lessonid, userid,teacherid,assistantid,lesson_start,lesson_num,stu_attend,"
                                ." lesson_end,teacher_score,teacher_comment,teacher_effect,teacher_quality,teacher_interact,"
                                ." grade,subject,stu_score,stu_comment,stu_attitude,stu_attention,stu_ability,stu_stability "
                                ." from %s "
                                ." where %s "

                                 ." and lesson_del_flag=0 "
                                ." order by lesson_start asc"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_lesson_info_ass($page_num,$start_time,$end_time,$assistantid,$userid,$subject=-1,$lesson_type=-1){
        $where_arr=[
            ["lesson_start>=%d",$start_time, -1 ] ,
            ["lesson_start<=%d",$end_time, -1 ] ,
            ["l.assistantid",$assistantid, -1 ] ,
            ["l.userid = %u",$userid, -1 ] ,
            ["l.subject = %u",$subject, -1 ] ,
            ["l.lesson_type = %u",$lesson_type, -1 ] ,
            // "l.lesson_del_flag=0"
           # "lesson_status = 0",
           # "lesson_type in (0,1,3)",
           # "confirm_flag in (0,1)"
        ];

        $sql = $this->gen_sql_new("select lesson_type,confirm_flag,lesson_cancel_reason_type,courseid,lessonid,l.userid,lesson_count,l.lesson_status,l.teacherid,lesson_start,lesson_num,lesson_end,l.grade,l.subject,confirm_reason,lesson_cancel_reason_next_lesson_time,s.phone,a.nick ass_nick,l.lesson_name ".
                                  " from %s l left join %s s on l.userid = s.userid "
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." where %s"
                                  ." and lesson_del_flag=0 "
                                  ."  order by lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_robot_lesson($lesson_start,$lesson_end){
        $where_arr=[
            ["lesson_end>%d",$lesson_start,0],
            ["lesson_end<%d",$lesson_end,0],
        ];
        $sql=$this->gen_sql_new("select l.lessonid,lesson_start,lesson_end,count(o.lessonid) as people_num"
                                ." from %s l"
                                ." left join %s o on l.lessonid=o.lessonid"
                                ." where %s"
                                ." and lesson_del_flag=0 "
                                ,self::DB_TABLE_NAME
                                ,t_open_lesson_user::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_info_ass_tongji($start_time,$end_time,$assistantid ,$require_adminid_list ){
        $where_arr=[
            "lesson_type in (0,1,3)",
            "s.is_test_user = 0",
            "lesson_del_flag = 0",
            ["l.assistantid= %d  ", $assistantid, -1],
        ];
        //$where_arr
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str("m.uid",$require_adminid_list);

        $sql=$this->gen_sql_new("select l.assistantid,count(distinct l.userid) stu_num,sum(if(confirm_flag <> 2,lesson_count,0)) valid_count,sum(if(lesson_cancel_reason_type=1,lesson_count,0)) family_change_count,sum(if(lesson_cancel_reason_type=2,lesson_count,0)) teacher_change_count,sum(if(lesson_cancel_reason_type=3,lesson_count,0)) fix_change_count,sum(if(lesson_cancel_reason_type=4,lesson_count,0)) internet_change_count,sum(if(lesson_cancel_reason_type=11,lesson_count,0)) student_leave_count,sum(if(lesson_cancel_reason_type=12,lesson_count,0)) teacher_leave_count"
                                ." from %s l "
                                ." join %s s on l.userid=s.userid "
                                ." left join  %s  a on a.assistantid= l.assistantid"
                                ." left join  %s  m on a.phone= m.phone"
                                ." where  %s group by l.assistantid "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_assistant_info::DB_TABLE_NAME
                                ,t_manager_info::DB_TABLE_NAME
                                ,$where_arr
        );
        #return $sql;
        return $this->main_get_list($sql);
    }




    public function check_test_lesson_info($userid){
        $sql=$this->gen_sql_new("select sum(lesson_count) from %s where userid=%u and lesson_type=2"
                                ,self::DB_TABLE_NAME
                                ,$userid
        );
        return $this->main_get_value($sql);
    }

    /**
     * @param type
     * 1 课后通知老师评价学生 2 上课迟到5分钟
     * 3 课前未上传学生讲义   4 试听超时评价
     * 5 常规超时评价         7 课程结束工资推送
     * 8 提醒老师上传讲义
     * @return string
     */
    private function get_wx_teacher_str($start_time,$end_time,$type){
        $lesson_time_str  = "l.lesson_end";
        $lesson_start_str = "l.lesson_start";
        switch($type){
        case 1:
            $str=" l.lesson_status=2 and l.tea_rate_time=0 and l.stu_attend!=0 and l.wx_comment_flag=0 and l.lesson_type<1000 ";
            break;
        case 2:
            $lesson_time_str = $lesson_start_str;
            $str=" l.lesson_status=1 and l.tea_attend=0 and l.wx_come_flag=0 and (l.lesson_type<1000 or (l.lesson_type =1100 and l.train_type =4 and l.lesson_sub_type=1))";
            break;
        case 3:
            $lesson_time_str = $lesson_start_str;
            $str=" (l.stu_cw_upload_time>l.lesson_start or l.stu_cw_upload_time=0 or l.stu_cw_status=0) "
                ." and l.wx_upload_flag=0 and l.lesson_type in (0,1,3) and lesson_status=1";
            break;
        case 4:
            $str=" l.lesson_type=2 and l.lesson_status=2 and l.tea_rate_time=0 and l.stu_attend!=0"
                ." and l.wx_comment_flag=1 and l.wx_rate_late_flag=0";
            break;
        case 5:
            $str=" l.lesson_type in (0,1,3) and l.lesson_status=2 and l.stu_attend!=0"
                ." and l.tea_rate_time=0 and l.wx_comment_flag=1 and l.wx_rate_late_flag=0 and week_comment_num=0";
            break;
        case 7:
            $str=" l.lesson_status=2 and l.lesson_type<1000 and l.wx_tea_price_flag=0 and t.teacher_ref_type!=3";
            break;
        case 8:
            $lesson_time_str = $lesson_start_str;
            $str=" l.stu_cw_upload_time=0 and l.stu_cw_status=0 and l.lesson_type in (0,1,3) ";
            break;
        case 11:
            $str=" l.lesson_status=2 and l.tea_rate_time=0 and l.lesson_type in (0,1,3)";
            break;
        case 13:
            $str=" l.lesson_type in (0,1,3) and l.lesson_status=2 and l.stu_attend!=0"
                ." and l.wx_comment_flag=1 and l.wx_rate_late_flag=0 and week_comment_num>0";
            break;
        case 15:
            $lesson_time_str = $lesson_start_str;
            // $str= "l.lesson_status=0 and (l.stu_cw_upload_time =0 or l.tea_cw_upload_time=0) and l.wx_before_four_hour_cw_flag =0"
            //  ." and (lesson_type=2 or (lesson_type =1100 and train_type =4)) ";
            $str= "l.lesson_status=0 and (l.stu_cw_upload_time =0 or l.tea_cw_upload_time=0 or h.work_status=0) and l.wx_before_four_hour_cw_flag =0"
                ." and l.lesson_type =1100 and (l.train_type =4 or l.lesson_type=2) and l.lesson_sub_type=1 ";
            break;
        case 16:
            $lesson_time_str = $lesson_start_str;
            // $str= "l.lesson_status=0 and (l.stu_cw_upload_time =0 or l.tea_cw_upload_time=0) and l.wx_before_four_hour_cw_flag =0"
            //  ." and (lesson_type=2 or (lesson_type =1100 and train_type =4)) ";
            $str= "l.lesson_status=0  and l.wx_before_thiry_minute_remind_flag =0"
                ." and l.lesson_type =1100 and l.train_type =4 and l.lesson_sub_type=1 ";
            break;
        case 17:
            $lesson_time_str = $lesson_start_str;
            $str=" l.lesson_type =1100 and l.train_type =4 and l.lesson_sub_type=1 ";
            break;
        case 18:
            $str=" l.lesson_status=2 and l.tea_rate_time=0 and l.tea_attend>0 and l.wx_comment_flag=0 and l.lesson_type =1100 and l.train_type =4 and l.lesson_sub_type=1";
            break;
        case 19:
            $str="l.lesson_type =1100 and l.train_type =4 and l.lesson_sub_type=1 and l.lesson_status=2"
                ." and l.tea_attend>0 and l.tea_rate_time=0 and l.wx_comment_flag=1 and l.wx_rate_late_flag=0";
            break;
        case 20:
            $str="l.lesson_type =1100 and l.train_type =4 and l.lesson_sub_type=1 and l.lesson_status=2"
                ." and l.tea_attend>0 and l.tea_rate_time=0 and l.wx_comment_flag=1 and l.wx_no_comment_count_down_flag=0";
            break;
        case 21:
            $str="l.lesson_type =1100 and l.train_type =4 and l.lesson_sub_type=1 and l.lesson_status=2"
                ." and l.tea_attend=0 and l.absenteeism_flag=0 and l.wx_absenteeism_flag=0";
            break;
        case 22:
            $str="l.lesson_type =1100 and l.train_type =4 and l.lesson_sub_type=1 and l.lesson_status=1"
                ." and l.tea_attend>0 ";
            break;
        default:
            $str=" true ";
            break;
        }
        $where_arr=[
            ["$lesson_time_str>=%u",$start_time,0],
            ["$lesson_time_str<%u",$end_time,0],
            $str
        ];
        return $this->lesson_common_where_arr($where_arr);
    }

    public function get_lesson_list_for_wx($start,$end,$type){
        $where_arr = $this->get_wx_teacher_str($start,$end,$type);
        $sql = $this->gen_sql_new("select l.lessonid,l.teacherid,l.userid,l.lesson_type,l.lesson_count,l.grade,t.teacher_type,"
                                  ." l.lesson_start,l.lesson_end,l.assistantid,s.realname as stu_nick,t.realname as tea_nick,"
                                  ." l.teacher_money_type,l.stu_cw_upload_time,m.money,tl.success_flag,l.tea_rate_time,"
                                  ." l.train_type,l.subject,l.lesson_name,l.tea_cw_upload_time,h.work_status  "
                                  ." from %s l"
                                  ." left join %s tl on l.lessonid=tl.lessonid "
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." left join %s m on l.level=m.level"
                                  ." and m.grade=(case when"
                                  ." l.competition_flag=1 then if(l.grade<200,203,303)"
                                  ." else l.grade"
                                  ." end)"
                                  ." and l.teacher_money_type=m.teacher_money_type"
                                  ." left join %s h on l.lessonid = h.lessonid"
                                  ." where %s"
                                  ." and (tl.success_flag!=2 or tl.success_flag is null)"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_money_type::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_last_lesson_end($lesson_start,$teacherid){
        $where_arr = [
            ["lesson_end<=%u",$lesson_start,0],
            ["teacherid=%u",$teacherid,0],
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select lesson_end "
                                  ." from %s "
                                  ." where %s "
                                  ." order by lesson_end desc "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_performance_stu_new($start_time,$end_time){
        $where_arr = [
            ['lesson_start>%u',$start_time,0],
            ['lesson_start<=%u',$end_time,0],
            "confirm_flag<2",
            "lesson_status=2",
            "lesson_comment_send_email_flag=0",
            "lesson_del_flag=0"
        ];

        $sql = $this->gen_sql_new("select l.lessonid,l.stu_performance,s.nick stu_nick,t.nick tea_nick,s.stu_email,"
                                  ." l.lesson_start,l.lesson_end"
                                  ." from %s l "
                                  ." left join %s s on l.userid = s.userid "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s"
                                  ." and lesson_del_flag=0 "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_performance_stu_by_lessonid($lessonid){
        $sql = $this->gen_sql_new("select stu_performance"
                                  ." from %s  "
                                  ." where lessonid = %u "
                                  ,self::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_value($sql);

    }


    public function get_small_class_performance_stu_new($start_time,$end_time){
        $where_arr=[
            ['lesson_start>%u',$start_time,0],
            ['lesson_start<=%u',$end_time,0],
            "confirm_flag<2",
            "lesson_status=2",
            "lesson_comment_send_email_flag=0",
            "lesson_type = 3001"
        ];

        $sql = $this->gen_sql_new("select l.lessonid,l.stu_performance,t.nick tea_nick,l.lesson_start,l.lesson_end ".
                                  " from %s l left join %s as t  on l.teacherid = t.teacherid ".
                                  " where %s "
                                  ." and lesson_del_flag=0 "
                                  ,self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $ret =  $this->main_get_list($sql);
        foreach($ret as &$item){
            $lessonid = $item['lessonid'];
            $sql = $this->gen_sql_new("select sl.userid,st.nick stu_nick,stu_email from %s sl,%s st where sl.userid = st.userid and sl.lessonid = %s",
                                      t_small_lesson_info::DB_TABLE_NAME,
                                      t_student_info::DB_TABLE_NAME,
                                      $lessonid
            );
            $item['stu_info'] = $this->main_get_list($sql);
        }
        return $ret;

    }


    public function get_not_check_homework_lesson($start_time,$end_time,$week_time){
        $week_start=$week_time-86400*14;
        $where_arr=[
            ["h.finish_time>=%u",$start_time,0],
            ["h.finish_time<%u",$end_time,0],
            ["l.lesson_start<%u",$week_time,0],
            ["l.lesson_start>%u",$week_start,0],
        ];
        $sql=$this->gen_sql_new("select l.lessonid,l.lesson_type,l.lesson_count,l.teacherid,l.userid,l.grade,"
                                ." l.lesson_start,l.lesson_end,s.realname as stu_nick,t.realname as tea_nick"
                                ." from %s l"
                                ." left join %s h on l.lessonid=h.lessonid"
                                ." left join %s s on s.userid=l.userid"
                                ." left join %s t on t.teacherid=l.teacherid"
                                ." where %s"
                                ." and lesson_status=2"
                                ." and confirm_flag<2"
                                ." and (work_status!=3 and work_status=2)"
                                ." and wx_homework_flag=0"
                                ." and l.lesson_type in (0,1,3)"
                                ." and lesson_del_flag=0 "
                                ,self::DB_TABLE_NAME
                                ,t_homework_info::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function test_lesson_list($page_num,$userid, $lesson_del_flag=0){
        $where_arr=[
            ["lesson_del_flag=%u", $lesson_del_flag, -1] ,
        ];
        $sql=$this->gen_sql_new("select lessonid,lesson_start,lesson_end,grade,subject,teacherid,lesson_status, lesson_del_flag "
                                ." from %s "
                                ." where userid= %u and lesson_type=2"
                                ." and %s "
                                ." order by lesson_start desc"
                                ,self::DB_TABLE_NAME
                                ,$userid, $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function get_lesson_info_ass_all($start_time,$end_time,$userid){
        $where_arr=[
            ["lesson_start>=%d",$start_time, -1 ] ,
            ["lesson_start<=%d",$end_time, -1 ] ,
            ["userid = %u",$userid, -1 ],
            "confirm_flag <>2",
        ];

        $sql = $this->gen_sql_new("select sum(lesson_count) lesson_total".
                                  " from %s where %s and lesson_type in (0,1,3)",
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_value($sql);
    }

    public function get_lesson_info_ass_all_new($start_time,$end_time,$userid){
        $where_arr=[
            ["lesson_start>=%d",$start_time, -1 ] ,
            ["lesson_start<=%d",$end_time, -1 ] ,
           # ["userid in %s",$userid, -1 ],
             "confirm_flag <>2",
        ];
        if($userid == "()"){
            $where_arr[]= "userid = 1";
        }else{
            $where_arr[] = "userid in".$userid;
        }


        $sql = $this->gen_sql_new("select userid,sum(lesson_count) lesson_total".
                                  " from %s "
                                  ." where %s "
                                  ." and lesson_type in (0,1,3)"
                                  ." and lesson_del_flag=0 "
                                  ." group by userid",
                                  self::DB_TABLE_NAME,
                                  $where_arr);

        return $this->main_get_list($sql,function($item){
            return $item['userid'];
        });
    }

    public function get_lesson_info_time($start_time,$end_time,$userid){
        $where_arr=[
            ["lesson_start>=%d",$start_time, -1 ] ,
            ["lesson_start<=%d",$end_time, -1 ] ,
            ["userid = %u",$userid, -1 ],
        ];

        $sql = $this->gen_sql_new("select lesson_start,lesson_end,teacherid,lessonid".
                                  " from %s where %s "
                                  ." and lesson_del_flag=0 "
                                  ,
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql);
    }
    public function get_lesson_info_time_new($start_time,$end_time,$userid){
        $where_arr=[
            ["lesson_start>=%d",$start_time, -1 ] ,
            ["lesson_start<=%d",$end_time, -1 ] ,
           # ["userid in %s",$userid, -1 ],
        ];
        if($userid == "()"){
            $where_arr[]= "userid = 1";
        }else{
            $where_arr[] = "userid in".$userid;
        }

        $sql = $this->gen_sql_new("select lesson_start,lesson_end,teacherid,lessonid,userid".
                                  " from %s where %s "
                                  ." and lesson_del_flag=0 "
                                  ,
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_free_lesson_next($start_time,$end_time,$teacherid){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            ["teacherid=%u",$teacherid,0],
        ];
        $sql = $this->gen_sql_new("select lessonid,lesson_start,lesson_end "
                                  ." from %s "
                                  ." where %s "
                                  //." and lesson_status=1 "
                                  ." and lesson_del_flag=0 "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function del_lesson_by_userid($userid,$start_time,$end_time,$lesson_exp=-1){
        $where_arr=[
            ["userid = %u",$userid,-1],
            "lesson_status = 0",
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_end <= %u",$end_time,-1],
            ["lessonid not in %s",$lesson_exp,-1],
        ];

        $sql = $this->gen_sql_new("select courseid,lessonid from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        $ret_info = $this->main_get_list($sql);
        $sql=$this->gen_sql_new("delete from %s where %s"

                                ." and lesson_del_flag=0 "
                                ,
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        $ret=$this->main_update($sql);
        if($ret_info){
            foreach($ret_info as $item){
                if ($ret) {
                    $this->t_homework_info->row_delete($item['lessonid']);
                }
                $this->reset_lesson_list($item['courseid']);
            }
        }
        return $ret;
    }


    public function check_is_plan($week_start_time,$week_end_time,$start_time,$userid,$teacherid){
        $start = strtotime(date('Y-m-d',$start_time));
        $week_time =explode("-",$week_start_time);
        $week= $week_time[0];
        $stome = $week_time[1];
        $lesson_start = strtotime(date('Y-m-d',($start + ($week-1)*86400)).' '.$stome);
        $lesson_end = strtotime(date('Y-m-d',($start + ($week-1)*86400)).' '.$week_end_time);
        $sql = $this->gen_sql_new("select 1 from %s where lesson_start = %u and lesson_end = %u and userid= %u and teacherid = %u",
                                  self::DB_TABLE_NAME,
                                  $lesson_start,
                                  $lesson_end,
                                  $userid,
                                  $teacherid
        );
        return $this->main_get_value($sql);
    }

    public function get_lesson_info_for_send_email_by_lessonid($lessonid){
        $where_arr = [
            ["l.lessonid in (%s)",$lessonid,-1]
        ];
        $sql = $this->gen_sql_new("select lesson_start,lesson_end,tea_cw_url,l.lessonid,l.userid,"
                                  ." l.lesson_status,s.stu_email,h.work_status,s.nick,h.issue_url"
                                  ." from %s l "
                                  ." left join %s s on l.userid = s.userid "
                                  ." left join %s h on l.lessonid = h.lessonid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_info_for_send_email($start_time,$end_time){
        $where_arr=[
            ['lesson_end>%u',$start_time,0],
            ['lesson_end<=%u',$end_time,0],
            "confirm_flag<2",
            "lesson_status=2",
            "lesson_end_todo_flag=0",
            "lesson_del_flag=0"
        ];

        $sql = $this->gen_sql_new("select lesson_start,lesson_end,tea_cw_url,l.lessonid,l.userid,"
                                  ." l.lesson_status,l.lesson_end_todo_flag,s.stu_email,h.work_status,s.nick,h.issue_url"
                                  ." from %s l "
                                  ." left join %s s on l.userid = s.userid "
                                  ." left join %s h on l.lessonid = h.lessonid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_small_class_lesson_info_for_send_email($start_time,$end_time){
        $where_arr=[
            ['lesson_end>%u',$start_time,0],
            ['lesson_end <= %u',$end_time,0],
            "confirm_flag < 2",
            "lesson_status = 2",
            "lesson_end_todo_flag = 0",
            "l.lesson_type = 3001"
        ];

        $sql = $this->gen_sql_new("select lesson_start,lesson_end,tea_cw_url,l.lessonid,l.lesson_status,l.lesson_end_todo_flag,h.work_status,h.issue_url".
                                  " from %s l left join %s as h  on l.lessonid = h.lessonid".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_homework_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $ret =  $this->main_get_list($sql);
        foreach($ret as &$item){
            $lessonid = $item['lessonid'];
            $sql = $this->gen_sql_new("select sl.userid,st.nick,stu_email from %s sl,%s st where sl.userid = st.userid and sl.lessonid = %s",
                                      t_small_lesson_info::DB_TABLE_NAME,
                                      t_student_info::DB_TABLE_NAME,
                                      $lessonid
            );
            $item['stu_info'] = $this->main_get_list($sql);
        }
        return $ret;
    }

    public  function get_lessonid_by_lesson_str($lesson_str) {
        $tmp_arr=preg_split( "/[_y]/" , $lesson_str );
        if (count($tmp_arr) !=4 ) {
            return 0;
        }
        $courseid=  $tmp_arr[1];
        $lesson_num=  $tmp_arr[2];
        $sql=$this->gen_sql_new(
            "select lessonid from %s "
            . " where   courseid=%u and lesson_num=%u ",
            self::DB_TABLE_NAME,  $courseid, $lesson_num
        );
        return $this->main_get_value($sql);
    }

    public function get_lessonid_list($courseid,$lesson_status=-1){
        $where_arr=[
            ["lesson_status=%u",$lesson_status,-1],
        ];
        $sql = $this->gen_sql_new("select lessonid"
                                  ." from %s"
                                  ." where %s"
                                  ." and courseid=%u"
                                  ." and lesson_del_flag=0 "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$courseid
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_stu_late($start_time,$end_time,$type){
        $where_arr = [
            ['lesson_start>%u',$start_time,0],
            ['lesson_start<=%u',$end_time,0]
        ];
        if($type==1){
            $where_arr[]="stu_attend=0 and lesson_status=1 and lesson_type in (0,1,3)";
        }elseif($type==2){
            $where_arr[]="lesson_type=2";
        }
        $sql = $this->gen_sql_new("select lessonid,l.assistantid,l.userid,lesson_type,if(s.nick='',s.nick,s.realname) as realname"
                                  ." from %s l"
                                  ." left join %s s on s.userid=l.userid "
                                  ." where %s "
                                  ." and confirm_flag<2"
                                  ." and lesson_del_flag=0 "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_info($lessonid){
        $sql = $this->gen_sql_new("select lessonid,teacherid,assistantid,userid,lesson_type,lesson_start,lesson_end,lesson_count,"
                                  ." teacher_money_type,level,already_lesson_count,tea_attend,stu_attend,tea_rate_time,courseid,"
                                  ." lesson_full_num,tea_cw_upload_time,stu_cw_upload_time,real_begin_time,real_end_time,"
                                  ." lesson_name,subject,grade,lesson_status,lesson_sub_type,train_type,"
                                  ." competition_flag,lesson_del_flag,confirm_flag,operate_time,lesson_num,teacher_type,accept_status"
                                  ." from %s"
                                  ." where lessonid=%u"
                                  ,self::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_row($sql);
    }

    /**
     * 老师工资相关的课程
     * @param int teacherid 老师id
     * @param int start  开始时间
     * @param int end    结束时间
     * @param int studentit 学生id
     * @param string type  current 当前为止，所有结束课程；all 所有课程；
     * @param int has_test_data 0 去掉测试用户的数据;1 包含测试用户的数据
     */
    public function get_lesson_list_for_wages($teacherid,$start,$end,$studentid=-1,$type='current',$has_test_data=0){
        $where_arr = [
            ["l.teacherid=%u",$teacherid,-1],
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            ["s.userid=%u",$studentid,-1],
            "lesson_type<1000",
            "lesson_del_flag=0",
            "(confirm_flag!=2 or deduct_change_class>0)"
        ];
        if($has_test_data==1){
            $where_arr[] = "s.is_test_user=0";
            $where_arr[] = "t.is_test_user=0";
        }
        if($type=='current'){
            $where_arr[] = "lesson_status=2";
        }
        $teacher_money_type_str = " l.teacher_money_type=m.teacher_money_type";

        $sql = $this->gen_sql_new("select l.lessonid,l.lesson_type,l.userid,l.grade,l.lesson_start,l.lesson_end,deduct_come_late,"
                                  ." deduct_check_homework,deduct_change_class,deduct_rate_student,deduct_upload_cw,l.subject,"
                                  ." l.confirm_flag,l.lesson_full_num,if(s.realname!='',s.realname,s.nick) as stu_nick,"
                                  ." already_lesson_count,l.lesson_count,sum(o.price) as lesson_price,"
                                  ." lesson_cancel_time_type,lesson_cancel_reason_type,t.teacher_type,"
                                  ." m.money,m.type,m.level,m.teacher_money_type,l.teacher_type as l_teacher_type,"
                                  ." tl.test_lesson_fail_flag,tl.fail_greater_4_hour_flag,"
                                  ." l.competition_flag,l.teacherid,l.lesson_status,l.lesson_del_flag"
                                  ." from %s l "
                                  ." left join %s tl on l.lessonid=tl.lessonid "
                                  ." left join %s s on l.userid=s.userid "
                                  ." left join %s o on l.lessonid=o.lessonid "
                                  ." left join %s t on l.teacherid=t.teacherid "
                                  ." left join %s m on l.level=m.level "
                                  ." and m.grade=(case when "
                                  ." l.competition_flag=1 then if(l.grade<200,203,303) "
                                  ." else l.grade"
                                  ." end )"
                                  ." and %s "
                                  ." where %s "
                                  ." and (tl.test_lesson_fail_flag<100 or tl.test_lesson_fail_flag is null"
                                  ." or (tl.test_lesson_fail_flag in (101,102) and tl.fail_greater_4_hour_flag=0))"
                                  ." group by l.lessonid "
                                  ." order by l.lesson_start asc "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_order_lesson_list::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_money_type::DB_TABLE_NAME
                                  ,$teacher_money_type_str
                                  ,$where_arr
        );
        // echo $sql;echo PHP_EOL;exit;
        return $this->main_get_list($sql);
    }

    public function get_lesson_list_for_wages_for_simulate($teacherid,$start,$end,$studentid=-1){
        $where_arr = [
            ["l.teacherid=%u",$teacherid,-1],
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            ["s.userid=%u",$studentid,-1],
        ];
        $teacher_money_type_str = " t.teacher_money_type_simulate=m.teacher_money_type";

        $sql = $this->gen_sql_new("select l.lessonid,l.lesson_type,l.userid,l.grade,l.lesson_start,l.lesson_end,deduct_come_late,"
                                  ." deduct_check_homework,deduct_change_class,deduct_rate_student,deduct_upload_cw,l.subject,"
                                  ." l.confirm_flag,l.lesson_full_num,if(s.realname!='',s.realname,s.nick) as stu_nick,"
                                  ." already_lesson_count,l.lesson_count,sum(o.price) as lesson_price,"
                                  ." lesson_cancel_time_type,lesson_cancel_reason_type,t.teacher_type,"
                                  ." m.money,m.type,m.level,m.teacher_money_type,"
                                  ." tl.test_lesson_fail_flag,tl.fail_greater_4_hour_flag,"
                                  ." l.competition_flag"
                                  ." from %s l "
                                  ." left join %s tl on l.lessonid=tl.lessonid "
                                  ." left join %s s on l.userid=s.userid "
                                  ." left join %s o on l.lessonid=o.lessonid "
                                  ." left join %s t on l.teacherid=t.teacherid "
                                  ." left join %s m on t.level_simulate=m.level "
                                  ." and m.grade=(case when "
                                  ." l.competition_flag=1 then if(l.grade<200,203,303) "
                                  ." else l.grade"
                                  ." end )"
                                  ." and %s "
                                  ." where %s "
                                  ." and lesson_status=2 "
                                  ." and (confirm_flag!=2 or deduct_change_class>0) "
                                  ." and lesson_type<1000 "
                                  ." and lesson_del_flag=0 "
                                  ." and (tl.test_lesson_fail_flag<100 or tl.test_lesson_fail_flag is null"
                                  ." or (tl.test_lesson_fail_flag in (101,102) and tl.fail_greater_4_hour_flag=0))"
                                  ." group by l.lessonid "
                                  ." order by l.lesson_start asc "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_order_lesson_list::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_money_type::DB_TABLE_NAME
                                  ,$teacher_money_type_str
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_open_lesson_user($lessonid){
        $sql = $this->gen_sql_new("select s.nick,s.phone,l.grade"
                                  ." from %s l"
                                  ." left join %s o on o.lessonid=l.lessonid"
                                  ." left join %s s on s.userid=o.userid"
                                  ." where l.lessonid in (%s)"
                                  ." and s.is_test_user=0"
                                  ." and lesson_del_flag=0 "
                                  ,self::DB_TABLE_NAME
                                  ,t_open_lesson_user::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_list($sql);
    }

    public function get_error_test_lesson_list($page_num,$start_time,$end_time) {

        /*
          array(103, "","[不付] 换时间 "),
          array(104, "","[不付] 换老师"),
          array(105, "","[不付] 排课出错 "),
        */

        $sql=$this->gen_sql("select  l.lessonid,lesson_start, lesson_end, teacherid, l.userid "
                            ." from %s l "
                            ." left join %s tr on tr.current_lessonid = l.lessonid "
                            ." left join %s tss on tss.lessonid = l.lessonid "
                            ." where lesson_del_flag=0 and lesson_type=2  "
                            .' and  (tr.current_lessonid is null  or tss.test_lesson_fail_flag in ( 103,104,105 ) ) and lesson_start>=%u and lesson_end<%u  ',

                            self::DB_TABLE_NAME,
                            t_test_lesson_subject_require::DB_TABLE_NAME,
                            t_test_lesson_subject_sub_list::DB_TABLE_NAME, $start_time,$end_time );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_teacher_lesson_total_list($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type in (0,1,3)"
        ];
        $sql = $this->gen_sql_new("select t.teacherid,t.nick,sum(lesson_count) as lesson_total "
                                  ." from %s l"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." left join %s s on s.userid=l.userid"
                                  ." where %s"
                                  ." and confirm_flag!=2"
                                  ." and lesson_status=2"
                                  ." and s.is_test_user=0"
                                  ." and lesson_del_flag=0"
                                  ." group by teacherid"
                                  ." order by lesson_total desc"
                                  ." limit 50"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_free_time_info_new( $teacherid, $start_time,$end_time ) {
        $sql=$this->gen_sql_new("select l.lessonid,l.lesson_start,l.lesson_end,l.userid,l.lesson_type,a.nick "
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid"
                                ." left join %s a on s.assistantid=a.assistantid"
                                ." left join %s tss on l.lessonid = tss.lessonid"
                                ." where l.teacherid=%u "
                                ." and l.lesson_start>=%s "
                                ." and l.lesson_start<=%s "
                                ." and l.lesson_status<=2 "
                                ." and l.confirm_flag<>2 "
                                ." and l.lesson_del_flag=0"
                                ." and if(l.lesson_type=2,tss.success_flag in (0,1),true) "
                                ." order by lesson_start asc ",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                $teacherid,
                                $start_time,
                                $end_time
        );
        $lesson_list =  $this->main_get_list($sql);
        $sql1 = $this->gen_sql_new("select free_time_new from %s where teacherid = %s ",
                                   t_teacher_freetime_for_week::DB_TABLE_NAME,
                                   $teacherid
        );
        $free_time_list_str = $this->main_get_value($sql1);
        $free_time_arr = [];
        if(!empty($free_time_list_str)){
            $free_time = json_decode($free_time_list_str);
            if(is_array($free_time) && !empty($free_time)){
                foreach($free_time as $item){
                    $rr = strtotime(@$item[0]);
                    $tt = substr(@$item[0],0,10)." ".@$item[1];
                    $ff = strtotime($tt);
                    $free_time_arr[] = array (
                        "free_start" => $rr,
                        "free_end"   => $ff
                    );
                }
            }
        }

        foreach ($lesson_list as  $lesson_item ) {
            foreach($free_time_arr as &$free_item ) {
                $lesson_start = $lesson_item["lesson_start"];
                $lesson_end   = $lesson_item["lesson_end"];
                $free_start   = $free_item["free_start"];
                $free_end     = $free_item["free_end"];

                /**
                 * $free 是排序的
                 * if ( $free_start >  $lesson_end  ) {
                 * break;
                 * }
                 * 假设已有 t1<=t2, t3<=t4
                 * 不重叠： t2<=t3 || t4<=t1
                 * 重叠取反即可：t3<t2 && t4>t1
                 */
                if ( $free_start < $lesson_end && $free_end > $lesson_start ) {
                    $free_item["lesson_list"][] = $lesson_item;
                }
            }
        }

        return  ["lesson_list"=>$lesson_list, "free_time_list"=>$free_time_arr];
    }

    public function get_teacherid_by_time($start,$end){
        $sql=$this->gen_sql_new("select teacherid from %s "
                                ."where ((lesson_start>=%s "
                                ." and lesson_start<=%s) "
                                ." or (lesson_end>=%s and lesson_end<=%s)) and lesson_status <=2 and confirm_flag<>2",
                                self::DB_TABLE_NAME,
                                $start,$end,$start,$end);
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }

    public function get_lesson_list_for_lesson_full_num($time,$end_time=0){
        $where_arr = [
            ["lesson_start>%u",$time,0],
            ["lesson_start<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select l.lessonid,deduct_change_class,confirm_flag,t.realname,l.lesson_count,l.lesson_full_num,"
                                  ." deduct_come_late,lesson_type,l.teacherid,lesson_cancel_reason_type, "
                                  ." lesson_cancel_time_type,success_flag,test_lesson_fail_flag,fail_greater_4_hour_flag,"
                                  ." lesson_start "
                                  ." from %s l "
                                  ." left join %s tl on tl.lessonid=l.lessonid "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s "
                                  ." and lesson_type in (0,1,2,3) "
                                  ." and lesson_status=2 "
                                  ." and is_test_user=0"
                                  ." and lesson_del_flag=0 "
                                  ." order by lesson_start asc "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_lesson_list_by_week($tea_arr=[]){
        $now   = strtotime(date("Y-m-d",time()));
        $date  = \App\Helper\Utils::get_week_range($now,1);
        $start = $date["sdate"];
        $end   = $date["edate"];
        $where_arr = [
            "lesson_start>=".$start,
            "lesson_start<".$end
        ];
        if(!empty($tea_arr)){
            $this->where_arr_teacherid($where_arr,"teacherid", $tea_arr);
        }

        $sql = $this->gen_sql_new("select teacherid,lesson_start,lesson_end,subject from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_data($type,$start,$end){
        $where_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            ["t.teacher_money_type=%u",$type,0]
        ];
        $sql=$this->gen_sql_new("select distinct(l.grade) as grade"
                                ." from %s l"
                                ." left join %s s on s.userid=l.userid"
                                ." left join %s t on t.teacherid=l.teacherid"
                                ." where %s"
                                ." and s.is_test_user=0"
                                ." and lesson_type=2"
                                ." and confirm_flag<2"
                                ." and lesson_del_flag=0"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_error_lesson_list($start,$end){
        $where_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select l.lessonid,t.realname as tea_nick,s.realname as stu_nick,l.assistantid,l.lesson_type"
                                  ." from %s l"
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s"
                                  ." and lesson_status<2"
                                  ." and lesson_type<1000"
                                  ." and ((stu_cw_status>0 and stu_cw_url='')"
                                  ." or (tea_cw_status>0 and tea_cw_url ='' and tea_more_cw_url=''))"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_trial_lesson_list($start,$end,$type,$str){
        $where_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        if($type==1){
            $sql=$this->gen_sql_new("select count(distinct(l.userid)) as num,l.%s"
                                    ." from %s l"
                                    ." left join %s s on l.userid=s.userid "
                                    ." where %s"
                                    ." and s.is_test_user=0"
                                    ." and lesson_type=2"
                                    ." group by l.%s"
                                    ,$str
                                    ,self::DB_TABLE_NAME
                                    ,t_student_info::DB_TABLE_NAME
                                    ,$where_arr
                                    ,$str
            );
        }elseif($type==2){
            $sql = $this->gen_sql_new("select count(distinct(o.userid)) as num,l.%s"
                                      ." from %s o"
                                      ." left join %s l on l.userid=o.userid"
                                      ." left join %s s on s.userid=o.userid"
                                      ." where s.is_test_user=0"
                                      ." and o.contract_status>0"
                                      ." and o.contract_type=0"
                                      ." and l.userid in ("
                                      ." select userid "
                                      ." from %s "
                                      ." where %s"
                                      ." and lesson_type=2"
                                      .")"
                                      ." group by l.%s"
                                      ,$str
                                      ,t_order_info::DB_TABLE_NAME
                                      ,self::DB_TABLE_NAME
                                      ,t_student_info::DB_TABLE_NAME
                                      ,self::DB_TABLE_NAME
                                      ,$where_arr
                                      ,$str
            );
        }
        return $this->main_get_list($sql);
    }

    public function get_teacher_subject($teacherid,$start,$end){
        $where_arr=[
            ["teacherid=%u",$teacherid,0],
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        $where_arr=$this->lesson_common_where_arr($where_arr);
        $sql=$this->gen_sql_new("select distinct(subject) "
                                ." from %s"
                                ." where %s"
                                ." and lesson_status=2"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_device_info($start_time,$end_time)  {
        $where_arr=[
            "lesson_type=2" ,
            "is_test_user=0" ,
            "lesson_del_flag=0" ,
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select "
            ." count(*) as all_count, "
            ."sum(user_agent  like '%%ipad%%') as ipad_count,    "
            ."sum(user_agent  like '%%windows%%') as windows_count,    "
            ."sum(user_agent ='' ) as null_count   "
            . " from %s l "
            . " join %s s on  s.userid=l.userid "
            . " where  %s ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_teacher_last_month_lesson_count($teacherid,$start,$end,$teacher_money_type=0){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        if($teacher_money_type==E\Eteacher_money_type::V_6){
            $where_arr[] = "lesson_type in (0,1,3)";
        }else{
            $where_arr[] = "lesson_type <1000";
        }

        $sql = $this->gen_sql_new("select sum(lesson_count) "
                                  ." from %s"
                                  ." where %s"
                                  ." and confirm_flag!=2"
                                  ." and lesson_del_flag=0"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    /**
     * 获取老师各个扣款类型的次数
     * @param start       统计开始时间
     * @param end         统计结束时间
     * @param teacherid   老师ID
     * @param type        扣款类型 1 迟到扣款 2 换课扣款
     */
    public function get_cost_num($start,$end,$teacherid,$type){
        if($type==1){
            $where_str = "deduct_come_late>0 and confirm_flag<2";
        }elseif($type==2){
            $where_str = "deduct_change_class>0 and confirm_flag=2";
        }
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            $where_str
        ];
        $sql = $this->gen_sql_new("select count(1) from %s "
                                  ." where %s"
                                  ." and lesson_del_flag=0"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_lesson_time_list($start_time, $end_time )
    {
            $where_arr=[
                "confirm_flag not in (2,3)",
                "lesson_del_flag=0",
                "lesson_type <>4001",
            ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select lesson_start,lesson_end".
            " from %s".
            " where %s".
            " order by lesson_start asc ",
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }

    //xmpp单个显示
    public function get_lesson_time_xmpp_list($xmpp_value,$start_time, $end_time )
    {
        // dd($xmpp_value);
        if($xmpp_value != ''){
            $where_arr=[
                "confirm_flag not in (2,3)",
                "lesson_del_flag=0",
                "lesson_type <>4001",
                "xmpp_server_name=".$xmpp_value,
            ];

        }else{
            $where_arr=[
                "confirm_flag not in (2,3)",
                "lesson_del_flag=0",
                "lesson_type <>4001",
            ];
        }

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select lesson_start,lesson_end".
            " from %s".
            " where %s".
            " order by lesson_start asc ",
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql);
    }


    public function get_tea_paper_lesson_list($start,$end){
        $where_arr=[
            ["l.lesson_start>=%u",$start,0],
            ["l.lesson_start<%u",$end,0],
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select l.lessonid,l.teacherid,l.userid,l.lesson_start,l.lesson_end,s.realname as stu_nick,"
                                  ." t.realname as tea_nick,l.lesson_type,l.grade,l.lesson_count"
                                  ." from %s l"
                                  ." left join %s t1 on t1.lessonid=l.lessonid"
                                  ." left join %s t2 on t2.require_id=t1.require_id"
                                  ." left join %s t3 on t3.test_lesson_subject_id=t2.test_lesson_subject_id"
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s"
                                  ." and l.lesson_type=2"
                                  ." and t3.stu_test_paper!=''"
                                  ." and t1.success_flag!=2"
                                  ." and t3.tea_download_paper_time=0"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacherid_for_reset_lesson_count($start,$end,$teacher_money_type=0){
        $where_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            ["teacher_money_type=%u",$teacher_money_type,-1],
        ];
        $sql = $this->gen_sql_new("select distinct(teacherid) "
                                  ." from %s"
                                  ." where %s"
                                  ." and lesson_del_flag=0"
                                  ." and confirm_flag!=2"
                                  ." and lesson_type<1000"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_test_lesson_info($start_time,$end_time,$teacherid,$page_num,$teacher_money_type,$subject,$identity
                                                 ,$is_new_teacher=1,$tea_subject="",$teacher_account=-1,$have_interview_teacher=-1
                                                 ,$reference_teacherid=-1,$grade_part_ex=-1,$teacher_subject=-1
    ){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            ["tt.teacherid = %u",$reference_teacherid,-1],
            ["t.teacher_money_type = %u",$teacher_money_type,-1],
            ["t.subject = %u",$teacher_subject,-1],
            ["t.identity = %u",$identity,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            ["ttt.teacherid = %u",$teacher_account,-1],
        ];

        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($is_new_teacher ==2){
            $where_arr[] = "t.create_time =(select max(create_time) from db_weiyi.t_teacher_info where t.teacherid=teacherid)";
        }else if($is_new_teacher ==3){
            $time = time()-7*86400;
            $where_arr[] = "t.create_time>=".$time;
        }else if($is_new_teacher ==4){
            $time = time()-7*86400*2;
            $where_arr[] = "t.create_time>=".$time;
        }else if($is_new_teacher ==5){
            $time = time()-86400*30;
            $where_arr[] = "t.create_time>=".$time;
        }else if($is_new_teacher ==6){
            $where_arr[] = "t.create_time<=".($start_time-15);
        }

        if(!empty($tea_subject)){
            $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
        }

        if($have_interview_teacher==0){
            $where_arr[] = "tl.account is null";
        }else if($have_interview_teacher==1){
            $where_arr[] = "tl.account is not null";
        }

        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==1){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==2){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==3){
            $where_arr[] = "l.grade >=300";
        }

        $sql = $this->gen_sql_new("select count(distinct l.lessonid) all_lesson,"
                                  ." sum(if(tss.success_flag in (0,1) and l.lesson_user_online_status=1,1,0)) success_lesson,"
                                  ." sum(if(tss.success_flag in (0,1) and l.lesson_user_online_status =2,1,0)) "
                                  ." success_not_in_lesson,"
                                  ." sum(if(o.orderid>0,1,0)) have_order,l.teacherid,t.nick,t.create_time,t.level, "
                                  ." t.interview_access,t.limit_plan_lesson_type,t.subject, "
                                  ." t.teacher_money_type,t.identity,t.school,t.is_freeze,tl.account "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) order_number"
                                  ." ,t.limit_plan_lesson_account,t.limit_plan_lesson_reason, "
                                  ." t.limit_plan_lesson_time,tr.add_time,tr.record_info,tr.acc, "
                                  ." t.freeze_time,t.freeze_reason,t.freeze_adminid,mm.account freeze_account  "
                                  ." from %s l "
                                  ." left join %s o on (l.lessonid = o.from_test_lesson_id and order_status>0)"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tl on (t.phone=tl.phone and tl.status=1)"
                                  ." left join %s m on m.account=tl.account"
                                  ." left join %s ttt on ttt.phone = m.phone"
                                  ." left join %s tll on t.phone=tll.phone"
                                  ." left join %s tt on tll.reference = tt.phone"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tr on (tr.teacherid = t.teacherid and tr.type=1 and tr.add_time = (select max(add_time) from db_weiyi.t_teacher_record_list where teacherid = t.teacherid))"
                                  ." left join %s mm on t.freeze_adminid = mm.uid"
                                  ." where %s "
                                  ." and l.teacherid > 0 "
                                  ." group by l.teacherid ",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_teacher_test_lesson_info_test($start_time,$end_time,$teacherid){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "c.courseid >0"

        ];

        $sql = $this->gen_sql_new("select distinct c.userid,c.teacherid,c.subject"
                                  ." from %s l "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0) "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_test_person_num( $start_time,$end_time,$teacherid,$subject=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
        ];
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }


        $sql = $this->gen_sql_new("select count(distinct userid) person_num "
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_all_lesson_num_info( $start_time,$end_time,$subject,$grade_part_ex,$teacherid_list=[]){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
        ];
        if($subject==20){
            $where_arr[] = "subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(grade >=100 and grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(grade >=200 and grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "grade >=300";
        }else{
            $where_arr[] =  ["grade = %u",$grade_part_ex,-1];
        }

        $this->where_arr_teacherid($where_arr,"teacherid", $teacherid_list);
        $sql=$this->gen_sql_new("select teacherid,count(distinct lessonid) num from %s where %s group by teacherid",
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_all_lesson_num_info_total( $start_time,$end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag=-1,$fulltime_teacher_type=-1){
        $where_arr = [
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start < %u",$end_time,-1],
            ["t.teacherid=%u",$teacherid,-1],
            ["t.subject=%u",$teacher_subject,-1],
            ["t.identity=%u",$identity,-1],
            ["tt.teacherid = %u",$teacher_account,-1],
            ["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
            "t.trial_lecture_is_pass =1",
            "t.train_through_new =1"
        ];

        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
            // $where_arr[] = "m.del_flag=0";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
            }
        }

        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }else if($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }

        if($tea_status==1){
            $where_arr[] = "t.is_freeze=1";
        }else if($tea_status==2){
            $where_arr[] = "t.limit_plan_lesson_type>0 and t.is_freeze=0";
        }else if($tea_status==3){
             $where_arr[] = "t.limit_plan_lesson_type=0 and t.is_freeze=0";
        }

        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $sql=$this->gen_sql_new("select count(distinct lessonid) num from %s  l"
                                ." left join %s t on l.teacherid = t.teacherid"
                                ." left join %s m on t.phone=m.phone"
                                ." where %s and lesson_type=2 and lesson_del_flag=0",
                                self::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_teacher_test_person_num_list( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid_list=[],$account_role=2,$check_flag=true){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.lesson_status>1"
            // "require_admin_type =2",
            //"tq.origin not like '%%扩课%%' and tq.origin not like '%%换老师%%'",
            //"m.account_role=2",
            // "m.account_role=2 or tq.origin like '%%转介绍%%'",
            // "m.del_flag=0"
        ];
        if($account_role==2){
            $where_arr[] = "m.account_role=2 or tq.origin like '%%转介绍%%'";
        }elseif($account_role==1){
            $where_arr[] = "m.account_role=1 and tq.origin not like '%%转介绍%%'";
        }
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list,$check_flag);
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.subject) person_num,count(l.lessonid) lesson_num,l.teacherid "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." where %s group by l.teacherid" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_teacher_test_person_num_by_all( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid_list=[],$account_role=2,$check_flag=true){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.lesson_status>1"
            // "require_admin_type =2",
            //"tq.origin not like '%%扩课%%' and tq.origin not like '%%换老师%%'",
            //"m.account_role=2",
            // "m.account_role=2 or tq.origin like '%%转介绍%%'",
            // "m.del_flag=0"
        ];
        if($account_role==2){
            $where_arr[] = "m.account_role=2 or tq.origin like '%%转介绍%%'";
        }elseif($account_role==1){
            $where_arr[] = "m.account_role=1 and tq.origin not like '%%转介绍%%'";
        }
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list,$check_flag);
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.subject,l.teacherid) person_num,count(distinct l.lessonid) lesson_num"
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_teacher_test_person_num_list_old( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid_list=[]){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.stu_attend>0 and l.tea_attend>0)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =2",
            //"tq.origin not like '%%扩课%%' and tq.origin not like '%%换老师%%'",
            "m.account_role=2",
            // "m.del_flag=0"
        ];
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list);
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.subject) person_num,count(l.lessonid) lesson_num,l.teacherid "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." where %s group by l.teacherid" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_teacher_test_person_num_list_other( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid_list=[]){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =2",
            "tq.origin not like '%%扩课%%' and tq.origin not like '%%换老师%%'",
            "m.account_role <>2",
            // "m.del_flag=0"
        ];
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list);
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.subject) person_num,count(l.lessonid) lesson_num,l.teacherid "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." where %s group by l.teacherid" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_teacher_test_person_num_list_total_old( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag=-1,$fulltime_teacher_type=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.stu_attend>0 and l.tea_attend>0)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =2",
            //  "tq.origin not like '%%扩课%%' and tq.origin not like '%%换老师%%'",
            "mm.account_role=2",
            // "mm.del_flag=0",
            ["t.teacherid=%u",$teacherid,-1],
            ["t.subject=%u",$teacher_subject,-1],
            ["t.identity=%u",$identity,-1],
            ["tt.teacherid = %u",$teacher_account,-1],
            ["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
            "t.is_test_user=0"
            // "t.trial_lecture_is_pass =1",
            //  "t.train_through_new =1"
        ];



        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
            }
        }
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }else if($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }


        if($tea_status==1){
            $where_arr[] = "t.is_freeze=1";
        }else if($tea_status==2){
            $where_arr[] = "t.limit_plan_lesson_type>0 and t.is_freeze=0";
        }else if($tea_status==3){
            $where_arr[] = "t.limit_plan_lesson_type=0 and t.is_freeze=0";
        }
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on t.phone=m.phone"
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_teacher_test_person_num_list_total( $start_time,$end_time,$subject=-1,$grade_part_ex=-1,$teacherid=-1,$teacher_subject=-1,$identity=-1,$tea_subject="",$qz_flag=-1,$tea_status=-1,$teacher_account=-1,$fulltime_flag=-1,$fulltime_teacher_type=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =2",
            //"tq.origin not like '%%扩课%%' and tq.origin not like '%%换老师%%'",
            "mm.account_role=2 ",
            //"mm.account_role=2 ",

            // "mm.del_flag=0",
            ["t.teacherid=%u",$teacherid,-1],
            ["t.subject=%u",$teacher_subject,-1],
            ["t.identity=%u",$identity,-1],
            ["tt.teacherid = %u",$teacher_account,-1],
            ["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
            "t.is_test_user=0"
            // "t.trial_lecture_is_pass =1",
            //  "t.train_through_new =1"
        ];


        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
             $where_arr[] = "m.del_flag=0";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
            }
        }

        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }else if($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }


        if($tea_status==1){
            $where_arr[] = "t.is_freeze=1";
        }else if($tea_status==2){
            $where_arr[] = "t.limit_plan_lesson_type>0 and t.is_freeze=0";
        }else if($tea_status==3){
            $where_arr[] = "t.limit_plan_lesson_type=0 and t.is_freeze=0";
        }
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on t.phone=m.phone"
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME, //c
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        //dd($sql);
        return $this->main_get_row($sql);

    }

    public function get_tongji_cc( $start_time,$end_time,$top_seller_flag =0,$grab_flag =0){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "mm.account_role=2 ",
            // "mm.del_flag=0",
            ["tss.top_seller_flag=%u",$top_seller_flag,-1],
            ["tss.grab_flag=%u",$grab_flag,-1],
        ];
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on t.phone=m.phone"
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_teacher_test_person_num_list_total_other( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag=-1,$fulltime_teacher_type=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =2",
            "tq.origin not like '%%扩课%%' and tq.origin not like '%%换老师%%'",
            "mm.account_role<>2",
            //  "mm.del_flag=0",
            ["t.teacherid=%u",$teacherid,-1],
            ["t.subject=%u",$teacher_subject,-1],
            ["t.identity=%u",$identity,-1],
            ["tt.teacherid = %u",$teacher_account,-1],
            ["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
            "t.is_test_user=0"
            // "t.trial_lecture_is_pass =1",
            //  "t.train_through_new =1"
        ];

        if(!empty($tea_subject)){
            $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
        }

        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }else if($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }

        if($tea_status==1){
            $where_arr[] = "t.is_freeze=1";
        }else if($tea_status==2){
            $where_arr[] = "t.limit_plan_lesson_type>0 and t.is_freeze=0";
        }else if($tea_status==3){
            $where_arr[] = "t.limit_plan_lesson_type=0 and t.is_freeze=0";
        }
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on t.phone=m.phone"
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }


    public function get_teacher_test_person_num_list_subject( $start_time,$end_time,$qz_flag=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "require_admin_type =2"
        ];
        if($qz_flag==0){
            $where_arr[] ="(m.account_role !=5  or m.account_role is null)";
        }elseif($qz_flag==1){
            $where_arr[] ="m.account_role =5 ";
        }


        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num,l.subject "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s group by l.subject" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });


    }

    public function get_kk_teacher_test_person_num_list_subject( $start_time,$end_time,$qz_flag=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "require_admin_type =1",
            "tq.origin like '%%扩课%%'"

        ];
        if($qz_flag==0){
            $where_arr[] ="(m.account_role !=5  or m.account_role is null)";
        }elseif($qz_flag==1){
            $where_arr[] ="m.account_role =5 ";
        }



        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) kk_num,l.subject "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) kk_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s group by l.subject" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }
    public function get_change_teacher_test_person_num_list_subject( $start_time,$end_time,$qz_flag=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "require_admin_type =1",
            "tq.origin like '%%换老师%%'"

        ];
        if($qz_flag==0){
            $where_arr[] ="(m.account_role !=5  or m.account_role is null)";
        }elseif($qz_flag==1){
            $where_arr[] ="m.account_role =5 ";
        }


        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) change_num,l.subject "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) change_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s group by l.subject" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }


    public function get_kk_teacher_test_person_num_list( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid_list=[]){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =1",
            "tq.origin like '%%扩课%%'"
        ];
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list);

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.subject) kk_per_num,count(l.lessonid) kk_num,l.teacherid "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) kk_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s group by l.teacherid" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }

    public function get_kk_teacher_test_person_num_list_total( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag,$fulltime_teacher_type=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =1",
            "tq.origin like '%%扩课%%'",
            ["t.teacherid=%u",$teacherid,-1],
            ["t.subject=%u",$teacher_subject,-1],
            ["t.identity=%u",$identity,-1],
            ["tt.teacherid = %u",$teacher_account,-1],
            ["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
            "t.is_test_user=0"
            // "t.trial_lecture_is_pass =1",
            // "t.train_through_new =1"
        ];

        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
            }
        }
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }else if($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }


        if($tea_status==1){
            $where_arr[] = "t.is_freeze=1";
        }else if($tea_status==2){
            $where_arr[] = "t.limit_plan_lesson_type>0 and t.is_freeze=0";
        }else if($tea_status==3){
            $where_arr[] = "t.limit_plan_lesson_type=0 and t.is_freeze=0";
        }

        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }


        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) kk_per_num,count(l.lessonid) kk_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) kk_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on m.phone=t.phone"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_change_teacher_test_person_num_list( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid_list=[]){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =1",
            "tq.origin like '%%换老师%%'",
        ];
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list);

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.subject) change_per_num,count(l.lessonid) change_num,l.teacherid "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) change_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s group by l.teacherid" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }

    public function get_change_teacher_test_person_num_list_total( $start_time,$end_time,$subject=-1,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag=-1,$fulltime_teacher_type=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =1",
            "tq.origin like '%%换老师%%'",
            ["t.teacherid=%u",$teacherid,-1],
            ["t.subject=%u",$teacher_subject,-1],
            ["t.identity=%u",$identity,-1],
            ["tt.teacherid = %u",$teacher_account,-1],
            ["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
            "t.is_test_user=0"
            // "t.trial_lecture_is_pass =1",
            // "t.train_through_new =1"
        ];

        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
            }
        }

        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }else if($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }

        if($tea_status==1){
            $where_arr[] = "t.is_freeze=1";
        }else if($tea_status==2){
            $where_arr[] = "t.limit_plan_lesson_type>0 and t.is_freeze=0";
        }else if($tea_status==3){
            $where_arr[] = "t.limit_plan_lesson_type=0 and t.is_freeze=0";
        }


        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }

        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }


        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) change_per_num,count(l.lessonid) change_num"
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) change_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on m.phone=t.phone"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }




    public function get_teacher_test_subject_num( $start_time,$end_time,$teacherid){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
        ];


        $sql = $this->gen_sql_new("select count(distinct l.subject) subject_num "
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }
    public function get_teacher_test_subject_num_list( $start_time,$end_time,$subject,$grade_part_ex,$teacherid_list=[]){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
        ];

        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }


        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list);

        $sql = $this->gen_sql_new("select count(distinct l.subject) subject_num,l.teacherid "
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." where %s group by teacherid " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }





    public function get_teacher_course_order_info($start_time,$end_time,$teacherid){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            "lesson_del_flag = 0",
            "lesson_type = 2",
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "c.courseid >0"
        ];
        $sql = $this->gen_sql_new("select distinct c.userid,c.teacherid,c.subject "
                                  ." from %s l "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid and l.teacherid = c.teacherid and l.subject = c.subject and c.course_type=0) "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_teacher_test_lesson_history_info($start_time,$end_time,$teacherid,$subject=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            ["l.subject = %u",$subject,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0"
        ];
        $sql = $this->gen_sql_new("select lessonid,lesson_start,l.subject,l.grade,s.nick"
                                  ." from %s l "
                                  ." left join %s s on l.userid = s.userid "
                                  ." where %s and l.teacherid > 0  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_have_ten_test_lesson_teacher_list($start_time,$subject,$tea_subject="",$end_time,$limit_plan_lesson_type=-1,$is_record_flag=-1,$is_do_sth =-1,$wx_type=-1,$start_time_ex=0,$end_time_ex=0,$qz_flag=0){
        $where_arr=[
            ["tt.subject = %u",$subject,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid >0",
            "t.success_flag in (0,1)",
            "l.lesson_user_online_status=1",
            "tt.is_freeze =0",
            "lesson_start >= ".$start_time,
            "lesson_start <= ".$end_time
        ];
        if($limit_plan_lesson_type==-2){
            $where_arr[]="tt.limit_plan_lesson_type in (1,3,5)";
        }else{
            $where_arr[]=  ["tt.limit_plan_lesson_type=%u",$limit_plan_lesson_type,-1];
        }
        if($qz_flag==1){
            $where_arr[]="m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(tt.subject in".$tea_subject." or tt.second_subject in".$tea_subject.")";
            }
        }
        if($is_record_flag==1){
            $where_arr[] = "tr.add_time is not null";
        }else if($is_record_flag==0){
            $where_arr[] = "tr.add_time is null";
        }
        if($is_do_sth==1){
            $where_arr[] = "(tr.add_time is not null or tt.limit_plan_lesson_type >0)";
        }else if($is_do_sth==0){
            $where_arr[] = "(tr.add_time is null and tt.limit_plan_lesson_type =0)";
        }
        if($wx_type==1){
            $where_arr[] = "tt.limit_plan_lesson_time >=".$start_time_ex;
            $where_arr[] = "tt.limit_plan_lesson_time <=".$end_time_ex;
        }

        $sql = $this->gen_sql_new("select l.teacherid,count(*) suc_count,tt.identity,tt.subject,tt.create_time,tt.realname,tt.interview_access,tt.level,tt.school,tt.limit_plan_lesson_type,tt.limit_plan_lesson_account,tt.limit_plan_lesson_reason,tt.limit_plan_lesson_time,tr.add_time,tr.record_info,tr.acc,tt.not_grade,tt.not_grade_limit,tt.freeze_adminid  ".
                                  "from %s l left join %s t on l.lessonid =t.lessonid".
                                  " left join %s tt on l.teacherid = tt.teacherid".
                                  " left join %s tr on (tr.teacherid = tt.teacherid and tr.type=1 and tr.add_time = (select max(add_time) from db_weiyi.t_teacher_record_list where teacherid = tt.teacherid))".
                                  " left join %s m on m.phone = tt.phone".
                                  " where %s group by l.teacherid having(suc_count >=10) ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_have_ten_test_lesson_teacher_list_ss($tea_qua_list){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid >0",
            "t.success_flag in (0,1)",
            "l.lesson_user_online_status =1"
        ];

        $this->where_arr_teacherid($where_arr,"l.teacherid", $tea_qua_list);
        $sql = $this->gen_sql_new("select l.teacherid,count(*) suc_count ".
                                  "from %s l left join %s t on l.lessonid =t.lessonid".
                                  " where %s group by l.teacherid having(suc_count >=30) ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }


    public function get_have_eight_test_lesson_teacher_list($start_time,$subject,$tea_subject="",$end_time,$limit_plan_lesson_type=-1,$is_record_flag=-1,$is_do_sth =-1,$wx_type=-1,$start_time_ex=0,$end_time_ex=0){
        $where_arr=[
            ["tt.subject = %u",$subject,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid >0",
            "t.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "tt.is_freeze =0",
            "lesson_start >= ".$start_time,
            "lesson_start <= ".$end_time
        ];
        if($limit_plan_lesson_type==-2){
            $where_arr[]="tt.limit_plan_lesson_type in (1,3,5)";
        }else{
            $where_arr[]=  ["tt.limit_plan_lesson_type=%u",$limit_plan_lesson_type,-1];
        }
        if(!empty($tea_subject)){
            $where_arr[]="(tt.subject in".$tea_subject." or tt.second_subject in".$tea_subject.")";
        }
        if($is_record_flag==1){
            $where_arr[] = "tr.add_time is not null";
        }else if($is_record_flag==0){
            $where_arr[] = "tr.add_time is null";
        }
        if($is_do_sth==1){
            $where_arr[] = "(tr.add_time is not null or tt.limit_plan_lesson_type >0)";
        }else if($is_do_sth==0){
            $where_arr[] = "(tr.add_time is null and tt.limit_plan_lesson_type =0)";
        }
        if($wx_type==1){
            $where_arr[] = "tt.limit_plan_lesson_time >=".$start_time_ex;
            $where_arr[] = "tt.limit_plan_lesson_time <=".$end_time_ex;
        }

        $sql = $this->gen_sql_new("select l.teacherid,count(*) suc_count,tt.identity,tt.subject,tt.create_time,tt.realname,tt.interview_access,tt.level,tt.school,tt.limit_plan_lesson_type,tt.limit_plan_lesson_account,tt.limit_plan_lesson_reason,tt.limit_plan_lesson_time,tr.add_time,tr.record_info,tr.acc ".
                                  "from %s l left join %s t on l.lessonid =t.lessonid".
                                  " left join %s tt on l.teacherid = tt.teacherid".
                                  " left join %s tr on (tr.teacherid = tt.teacherid and tr.type=1 and tr.add_time = (select max(add_time) from db_weiyi.t_teacher_record_list where teacherid = tt.teacherid))".
                                  " where %s group by l.teacherid having(suc_count >=8) ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_have_ten_test_lesson_teacher_list_new($start_time,$subject,$tea_subject="",$end_time){
        $freeze_time = time()-7*86400;
        $where_arr=[
            ["tt.subject = %u",$subject,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid >0",
            "t.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "(tt.is_freeze=0 or (tt.is_freeze=1 and tt.freeze_time=".$freeze_time."))",
            "lesson_start >= ".$start_time,
            "lesson_start <= ".$end_time,
            "(tt.nick <> '刘辉' and tt.realname <> '刘辉' and tt.nick <> 'becky老师' and tt.realname <> 'becky老师')",
            "tt.teacherid not in (51094,53289,59896,130462,61828,55161,90732,130500,134439,130503,130506,130490,130498,85081)",
            "tt.freeze_adminid <>72 and tt.freeze_adminid<>349"
        ];
        if(!empty($tea_subject)){
            $where_arr[]="tt.subject in ".$tea_subject;
        }
        $sql = $this->gen_sql_new("select l.teacherid,count(*) suc_count,tt.identity,tt.subject,tt.create_time,tt.realname,tt.interview_access,tt.level,tt.school ".
                                  "from %s l left join %s t on l.lessonid =t.lessonid".
                                  " left join %s tt on l.teacherid = tt.teacherid".
                                  " where %s group by l.teacherid having(suc_count >=10) ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_test_leson_info_by_teacher_list($teacher_list){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid >0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1"
        ];


        $this->where_arr_teacherid($where_arr,"t.teacherid",$teacher_list);

        $sql = $this->gen_sql_new("select count(1) all_lesson,"
                                  ." sum(if(o.orderid>0,1,0)) have_order "
                                  ." from %s l "
                                  ." left join %s o on (l.lessonid = o.from_test_lesson_id and order_status >0)"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tss on tss.lessonid= l.lessonid"
                                  ." left join %s ta on t.phone = ta.phone"
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }
    public function get_test_leson_info_by_teacher_list_new($reference,$teacher_list){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid >0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1"
        ];


        $this->where_arr_teacherid($where_arr,"t.teacherid",$teacher_list);
        if($reference==-1){
            $where_arr[] = "ta.reference is null";
        }else if(empty($reference)){
            $where_arr[] = "ta.reference =''";
        }else{
            $where_arr[] = ["ta.reference= '%s'",$reference,""];
        }

        $sql = $this->gen_sql_new("select count(1) all_lesson,"
                                  ." sum(if(o.orderid>0,1,0)) have_order "
                                  ." from %s l "
                                  ." left join %s o on (l.lessonid = o.from_test_lesson_id and order_status >0)"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tss on tss.lessonid= l.lessonid"
                                  ." left join %s ta on t.phone = ta.phone"
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }


    public function get_test_lesson_order_info_by_teacherid($teacherid,$start_time,$end_time,$test_lesson_num=10){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid =".$teacherid,
            "t.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "lesson_start >= ".$start_time,
            "lesson_start <= ".$end_time
        ];
        $sql = $this->gen_sql_new("select distinct l.teacherid,o.orderid,l.lesson_start,l.subject,l.userid,c.teacherid course_teacherid".
                                  " from %s l left join %s t on l.lessonid = t.lessonid".
                                  " left join %s o on  (l.lessonid = o.from_test_lesson_id and order_status >0)".
                                  " left join %s c on ".
                                  " (l.userid = c.userid and l.teacherid = c.teacherid and l.subject = c.subject and c.course_type=0) ".
                                  " where %s order by lesson_start desc limit %u",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr,
                                  $test_lesson_num
        );
        return $this->main_get_list($sql);

    }
    public function get_thirty_lesson_order_info_by_teacherid($teacherid){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid =".$teacherid,
            "t.success_flag in (0,1)",
            "l.lesson_user_online_status =1"
        ];
        $sql = $this->gen_sql_new("select distinct l.teacherid,o.orderid,l.lesson_start,l.subject,l.userid,c.teacherid course_teacherid".
                                  " from %s l left join %s t on l.lessonid = t.lessonid".
                                  " left join %s o on  (l.lessonid = o.from_test_lesson_id and order_status >0)".
                                  " left join %s c on ".
                                  " (l.userid = c.userid and l.teacherid = c.teacherid and l.subject = c.subject and c.course_type=0) ".
                                  " where %s order by lesson_start desc limit 30",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }


    public function get_eight_test_lesson_order_info_by_teacherid($teacherid,$start_time,$end_time){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid =".$teacherid,
            "t.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "lesson_start >= ".$start_time,
            "lesson_start <= ".$end_time
        ];
        $sql = $this->gen_sql_new("select distinct l.teacherid,o.orderid,l.lesson_start,l.subject,l.userid,c.teacherid course_teacherid".
                                  " from %s l left join %s t on l.lessonid = t.lessonid".
                                  " left join %s o on  (l.lessonid = o.from_test_lesson_id and order_status >0)".
                                  " left join %s c on ".
                                  " (l.userid = c.userid and l.teacherid = c.teacherid and l.subject = c.subject and c.course_type=0) ".
                                  " where %s order by lesson_start desc limit 8",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }


    public function get_week_test_lesson_list($teacherid,$start_time,$end_time){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid =".$teacherid,
            "t.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "lesson_start >= ".$start_time,
            "lesson_start <= ".$end_time

        ];
        $sql = $this->gen_sql_new("select teacherid,l.lesson_start,l.userid,l.subject,l.grade,s.nick,l.lessonid ".
                                  " from %s l left join %s t on l.lessonid = t.lessonid".
                                  " left join %s s on l.userid =s.userid ".
                                  " where %s order by lesson_start desc limit 20",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_week_test_lesson_info_new($teacherid,$start_time,$end_time){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid =".$teacherid,
            "t.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "lesson_start >= ".$start_time,
            "lesson_start <= ".$end_time

        ];
        $sql = $this->gen_sql_new("select teacherid,l.lesson_start,l.userid,l.subject,l.grade,s.nick,l.lessonid ".
                                  " from %s l left join %s t on l.lessonid = t.lessonid".
                                  " left join %s s on l.userid =s.userid ".
                                  " where %s order by lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_test_lesson_order_info_by_teacherid_new($teacherid,$start_time,$end_time,$test_lesson_num=10){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid =".$teacherid,
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "lesson_start >= ".$start_time,
            "lesson_start <= ".$end_time
        ];
        $sql = $this->gen_sql_new("select o.orderid,l.lessonid,lesson_start,l.subject,l.grade,s.nick,l.draw,l.audio,l.real_begin_time,n.stu_character_info,n.stu_score_info,t.stu_request_lesson_time_info,t.stu_request_test_lesson_time,t.stu_request_test_lesson_time_info,t.stu_test_lesson_level,s.editionid,t.stu_request_test_lesson_demand,t.stu_test_paper,t.tea_download_paper_time,t.require_adminid,s.phone,l.userid,l.teacherid,tr.test_lesson_order_fail_desc   ".
                                  " from %s l left join %s tss on l.lessonid = tss.lessonid".
                                  " left join %s o on  (l.lessonid = o.from_test_lesson_id and order_status >0)".
                                  " left join %s s on l.userid = s.userid ".
                                  " left join %s tr on tss.require_id = tr.require_id ".
                                  " left join %s t on tr.test_lesson_subject_id  = t.test_lesson_subject_id " .
                                  " left join %s n on n.userid  = t.userid ".
                                  " where %s order by lesson_start desc limit %u",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $where_arr,
                                  $test_lesson_num
        );
        return $this->main_get_list($sql);

    }

    public function get_first_test_lesson_order_info_by_teacherid_new($teacherid){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.teacherid =".$teacherid,
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1"
        ];
        $sql = $this->gen_sql_new("select o.orderid,l.lessonid,lesson_start,l.subject,l.grade,s.nick,l.draw,l.audio,l.real_begin_time,n.stu_character_info,n.stu_score_info,t.stu_request_lesson_time_info,t.stu_request_test_lesson_time,t.stu_request_test_lesson_time_info,t.stu_test_lesson_level,s.editionid,t.stu_request_test_lesson_demand,t.stu_test_paper,t.tea_download_paper_time,t.require_adminid,s.phone,l.userid,l.teacherid,tr.test_lesson_order_fail_desc,tr.test_lesson_order_fail_flag   ".
                                  " from %s l left join %s tss on l.lessonid = tss.lessonid".
                                  " left join %s o on  (l.lessonid = o.from_test_lesson_id and order_status >0)".
                                  " left join %s s on l.userid = s.userid ".
                                  " left join %s tr on tss.require_id = tr.require_id ".
                                  " left join %s t on tr.test_lesson_subject_id  = t.test_lesson_subject_id " .
                                  " left join %s n on n.userid  = t.userid ".
                                  " where %s order by lesson_start ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }



    public function get_teacher_test_lesson_history_success_info($start_time,$end_time,$teacherid,$subject=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            ["l.subject = %u",$subject,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "m.account_role=2",
            "m.del_flag=0"
        ];
        $sql = $this->gen_sql_new("select l.lessonid,lesson_start,l.subject,l.grade,s.nick,l.draw,l.audio,l.real_begin_time,"
                                  ."n.stu_character_info,n.stu_score_info,t.stu_request_lesson_time_info,t.stu_request_test_lesson_time,t.stu_request_test_lesson_time_info,t.stu_test_lesson_level,s.editionid,t.stu_request_test_lesson_demand,t.stu_test_paper,t.tea_download_paper_time,t.require_adminid,s.phone,l.userid,l.teacherid,tr.test_lesson_order_fail_desc,tr.test_lesson_order_fail_flag,tr.cur_require_adminid,s.phone    "
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid "
                                  ." left join %s s on l.userid = s.userid "
                                  ." left join %s tr on tss.require_id = tr.require_id "
                                  ." left join %s t on tr.test_lesson_subject_id  = t.test_lesson_subject_id "
                                  ." left join %s n on n.userid  = t.userid "
                                  ." left join %s m on m.uid= tr.cur_require_adminid"
                                  ." where %s and l.teacherid > 0  ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_test_info_old($start_time,$end_time,$teacherid,$page_num,$teacher_money_type,$subject,$identity
                                              ,$is_new_teacher
    ){
        $where_arr = [
            ["test_lesson_time >= %u",$start_time,-1],
            ["test_lesson_time <= %u",$end_time,-1],
            ["tl.teacherid = %u",$teacherid,-1],
            ["t.teacher_money_type = %u",$teacher_money_type,-1],
            ["tl.subject = %u",$subject,-1],
            ["t.identity = %u",$identity,-1],
        ];

        if($is_new_teacher ==2){
            $where_arr[] = "create_time =(select max(create_time) from db_weiyi.t_teacher_info)";
        }else if($is_new_teacher ==3){
            $time = time()-7*86400;
            $where_arr[] = "create_time>=".$time;
        }else if($is_new_teacher ==4){
            $time = time()-7*86400*2;
            $where_arr[] = "create_time>=".$time;
        }else if($is_new_teacher ==5){
            $time = time()-86400*30;
            $where_arr[] = "create_time>=".$time;
        }

        $sql = $this->gen_sql_new("select count(1) all_lesson, "
                                  ." sum(if(tl.first_lesson_time >0,1,0)) have_order,tl.teacherid,t.nick,t.create_time,t.level,"
                                  ." t.teacher_money_type,t.identity,t.school "
                                  ." from %s tl "
                                  ." left join %s t on tl.teacherid = t.teacherid"
                                  ." where %s and tl.teacherid > 0 group by tl.teacherid ",
                                  t_test_lesson_order_info_old::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);

    }

    public function get_test_info_subject(){
        $sql = $this->gen_sql_new("select userid,teacherid,lesson_start,subject from %s where lesson_type =2",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_lesson_info_xu(){
        $sql = $this->gen_sql_new("select lesson_start,l.teacherid,l.userid,l.subject,t.nick tea_nick,s.nick stu_nick from %s l"
                                  ." left join %s t on t.teacherid= l.teacherid "
                                  ." left join %s s on l.userid =s.userid where lesson_type=2 and l.teacherid = 99504 ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME

        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_for_email($lessonid){
        $where_arr=[
            ["l.lessonid=%u",$lessonid,0]
        ];

        $sql = $this->gen_sql_new("select lesson_start,lesson_end,tea_cw_url,l.lessonid,l.userid,"
                                  ." l.lesson_status,l.lesson_end_todo_flag,s.stu_email,h.work_status,s.nick,h.issue_url"
                                  ." from %s l "
                                  ." left join %s s on l.userid = s.userid "
                                  ." left join %s as h  on l.lessonid = h.lessonid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_wages_list($start_time,$end_time){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);

        $sql = $this->gen_sql_new("select l.teacherid,if(t.realname='',t.nick,t.realname) as tea_nick,"
                                  ." deduct_change_class,deduct_come_late,deduct_upload_cw,deduct_rate_student,"
                                  ." t.teacher_money_flag,t.teacher_money_type,t.level,"
                                  ." from %s l"
                                  ." left join %s s on s.userid=t.userid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s tl on l.lessonid=tl.lessonid "
                                  ." left join %s o on l.lessonid=o.lessonid "
                                  ." left join %s m on l.level=m.level "
                                  ." and l.teacher_money_type=m.teacher_money_type "
                                  ." and m.grade=(case when "
                                  ." l.competition_flag=1 then if(l.grade<200,203,303) "
                                  ." else l.grade"
                                  ." end )"
                                  ." where %s"
                                  ." and t.is_test_user=0"
                                  ." and s.is_test_user=0"
                                  ." and lesson_status=2"
                                  ." and (confirm_flag!=2 or deduct_change_class>0)"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_order_lesson_list::DB_TABLE_NAME
                                  ,t_teacher_money_type::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list_as_page($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_tea_month_list(
        $start,$end,$teacher_ref_type,$teacher_type,$teacher_money_type,$level,$show_type="current"
    ){
        $where_arr = [
            ["l.lesson_start>%u",$start,0],
            ["l.lesson_start<%u",$end,0],
            ["t.teacher_ref_type=%u",$teacher_ref_type,-1],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            ["t.level=%u",$level,-1],
            // ["tl.reference='%s'",$reference,""],
            "l.lesson_type<1000",
            "t.is_test_user=0",
        ];
        if($show_type=="current"){
            $where_arr[]="l.lesson_status=2";
        }
        if($teacher_type ==-1){
            $where_arr[] = "(t.teacher_type!=3 or l.teacherid in (51094,99504,97313))";
        }elseif($teacher_type==3 ){
            $where_arr[] = "(t.teacher_type=3 and l.teacherid not in (51094,99504,97313))";
        }else{
            $where_arr[]=["t.teacher_type=%u",$teacher_type,-1];
        }
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select t.teacherid,if(t.realname='',t.nick,t.realname) as tea_nick,t.subject,t.create_time,"
                                  ." t.teacher_money_type,t.level,t.teacher_money_flag,t.teacher_ref_type,"
                                  ." t.bankcard,t.bank_address,t.bank_account,t.bank_phone,t.bank_type,t.teacher_money_flag,"
                                  ." t.idcard,t.bank_city,t.bank_province,t.phone,"
                                  ." sum(if(l.lesson_type in (0,1,3),l.lesson_count,0)) as lesson_1v1,"
                                  ." sum(if(l.lesson_type=2,l.lesson_count,0)) as lesson_trial,"
                                  ." sum(if(l.lesson_type<1000,l.lesson_count,0)) as lesson_total"
                                  ." from %s l force index(lesson_start)"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  // ." left join %s tl on t.phone=tl.phone"
                                  ." where %s"
                                  ." group by t.teacherid"
                                  ." order by lesson_total desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  // ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        // echo $sql;exit;
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }

    public function get_tea_month_list_new($start,$end,$teacher_ref_type,$teacher_type=0,$teacher_money_type,$level,$reference=""){
        $where_arr = [
            ["l.lesson_start>%u",$start,0],
            ["l.lesson_start<%u",$end,0],
            ["t.teacher_ref_type=%u",$teacher_ref_type,-1],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            ["t.level=%u",$level,-1],
            ["tla.reference='%s'",$reference,""],
        ];
        if($teacher_type!=3){
            $where_arr[] = "(t.teacher_type!=3 or l.teacherid in (51094,99504,97313))";
        }else{
            $where_arr[] = "(t.teacher_type=3 and l.teacherid not in (51094,99504,97313))";
        }
        $where_arr = $this->lesson_common_where_arr($where_arr);

        $sql = $this->gen_sql_new("select t.teacherid,if(t.realname='',t.nick,t.realname) as tea_nick,t.subject,"
                                  ." t.teacher_money_type,t.level,t.teacher_money_flag,t.teacher_ref_type,t.test_transfor_per,"
                                  ." sum(if(l.lesson_type in (0,1,3),l.lesson_count,0)) as lesson_1v1,"
                                  ." sum(if(l.lesson_type=2,l.lesson_count,0)) as lesson_trial,"
                                  ." sum(if(l.lesson_type<1000,l.lesson_count,0)) as lesson_total,"
                                  ." sum(o.price) as lesson_price,t.create_time"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s o on l.lessonid=o.lessonid "
                                  ." left join %s tla on t.phone=tla.phone"
                                  ." where %s"
                                  ." and l.lesson_type<1000"
                                  ." and l.lesson_status=2"
                                  ." and l.lesson_del_flag=0"
                                  ." and s.is_test_user=0"
                                  ." and t.is_test_user=0"
                                  ." group by t.teacherid"
                                  ." order by lesson_total desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_order_lesson_list::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }

    public function get_user_info_by_month($month){
        $year = date('Y',time());
        $month_end = $month+1;
        if($month_end <10){
            $month_end = "0".$month_end;
        }
        if($month <10){
            $month = "0".$month;
        }

        $start_time = strtotime($year."-".$month."-01");
        $end_time = strtotime($year."-".$month_end."-01");
        if($month==12){
            $month_end = "01";
            $year1 = $year +1;
            $start_time = strtotime($year."-".$month."-01");
            $end_time = strtotime($year1."-".$month_end."-01");

        }

        $sql = $this->gen_sql_new("select distinct userid,subject from %s where lesson_type in (0,1,3) and confirm_flag in (0,1) and ".
                                  " lesson_start >= %u and lesson_start <= %u and lesson_del_flag=0",
                                  self::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time
        );

        $ret =  $this->main_get_list($sql);
        $arr[$month]=[];
        foreach($ret as $v){
            $userid= $v["userid"];
            @$arr[$month][$userid]++;
        }
        $res=[];
        foreach($arr[$month] as $item){
            if($item >= 4){
                $item = 4;
            }
            @$res[$item]++;
        }
        return $res;
    }

    public function get_subject_by_month($month){
        $year = date('Y',time());
        $month_end = $month+1;
        if($month_end <10){
            $month_end = "0".$month_end;
        }
        if($month <10){
            $month = "0".$month;
        }

        $start_time = strtotime($year."-".$month."-01");
        $end_time = strtotime($year."-".$month_end."-01");
        if($month==12){
            $month_end = "01";
            $year1 = $year +1;
            $start_time = strtotime($year."-".$month."-01");
            $end_time = strtotime($year1."-".$month_end."-01");

        }

        //sql deal
        $sql = $this->gen_sql_new("select distinct userid,subject from %s where lesson_type in (0,1,3) and confirm_flag in (0,1) and ".
                                  " lesson_start >= %u and lesson_start <= %u and lesson_del_flag=0",
                                  self::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time
        );
        $ret =  $this->main_get_list($sql);
        //deal
        $arr=[];
        foreach($ret as $v){
            $subject= $v["subject"];
            @$arr[$subject]++;
        }

        return $arr;
    }

    public function get_regular_stu_num($start,$end,$teacherid_list=[]){
        /*$sql=$this->gen_sql_new("select distinct userid,teacherid from %s where lesson_type=0 and confirm_flag in(0,1) and lesson_start >= %u and lesson_start <=%u ",self::DB_TABLE_NAME,$start,$end);
        $ret =  $this->main_get_list($sql);
        $arr=[];
        foreach($ret as $v){
            $teacherid= $v["teacherid"];
            @$arr[$teacherid]++;
        }
        return $arr;*/
        $where_arr=[];
        $this->where_arr_teacherid($where_arr,"teacherid", $teacherid_list);

        $sql=$this->gen_sql_new("select teacherid,count(distinct userid) regular_count from %s where %s and lesson_type=0 and confirm_flag in(0,1) and lesson_start >= %u and lesson_start <=%u group by teacherid ",self::DB_TABLE_NAME,$where_arr,$start,$end);
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_regular_stu_num_by_teacher($start,$end,$teacherid){
        $sql=$this->gen_sql_new("select distinct l.userid,s.nick,l.grade,l.subject,s.lesson_count_left from %s l join %s s on l.userid = s.userid where lesson_type=0 and confirm_flag in(0,1) and lesson_start >= %u and lesson_start <=%u and l.teacherid = %u",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                $start,$end,$teacherid);
            return $this->main_get_list($sql);

    }
    public function get_test_lesson_num_list($start,$end,$teacherid_list=[]){
        $where_arr=[];
        $this->where_arr_teacherid($where_arr,"teacherid", $teacherid_list);

        $sql=$this->gen_sql_new("select teacherid,count(*) test_lesson_count from %s where %s and lesson_type=2 and confirm_flag in(0,1) and lesson_start >= %u and lesson_start <=%u and lesson_del_flag =0 group by teacherid ",self::DB_TABLE_NAME,$where_arr,$start,$end);
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }

    public function get_test_lesson_num_list_detail($start,$end,$teacherid){
        $sql=$this->gen_sql_new("select lessonid,lesson_start,l.userid,l.grade,l.subject,s.nick from %s l left join %s s on l.userid = s.userid where lesson_type=2 and confirm_flag in(0,1) and lesson_start >= %u and lesson_start <=%u and lesson_del_flag =0 and teacherid =%u ",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                $start,$end,$teacherid);
        return $this->main_get_list($sql);
    }

    public function get_teacher_lesson_count_total($start,$end,$teacherid_list=[],$lesson_type=-1){
        $where_arr = [
            ["lesson_start>=%u",$start,0],
            ["lesson_start<=%u",$end,0],
            "confirm_flag!=2",
            "lesson_del_flag=0",
        ];
        $this->where_arr_teacherid($where_arr,"teacherid", $teacherid_list);
        if($lesson_type==1){
            $where_arr[] ="lesson_type <>2 and lesson_type <1000";
        }else{
            $where_arr[] ="lesson_type in (0,2)";
        }
        $sql = $this->gen_sql_new("select teacherid,teacher_money_type,sum(lesson_count) as lesson_total,"
                                  ." count(distinct(userid)) as stu_num "
                                  ." from %s "
                                  ." where %s  "
                                  ." group by teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function add_open_lesson($teacherid,$courseid,$lesson_start,$lesson_end,$subject,$grade,
                                    $lesson_num=1,$lesson_type=1001,$enter_type=1
    ){
        $this->row_insert([
            "teacherid"    => $teacherid,
            "courseid"     => $courseid,
            "lesson_start" => $lesson_start,
            "lesson_end"   => $lesson_end,
            "grade"        => $grade,
            "subject"      => $subject,
            "lesson_num"   => $lesson_num,
            "lesson_type"  => $lesson_type,
            "rand_num"     => rand(280,400),
            "enter_type"   => $enter_type,
        ]);
        return $this->get_last_insertid();
    }


    public function get_all_test_order_info_by_time($start_time){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "lesson_start >= ".$start_time
        ];

        $sql= $this->gen_sql_new("select l.lesson_start,order_time,o.orderid "
                                 ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                 ." left join %s o on (l.lessonid = o.from_test_lesson_id and order_status >0) "
                                 ." where %s",
                                 self::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_wages_count_list($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select count(distinct(l.teacherid)) as tea_num,l.teacher_money_type,l.level,"
                                  ." sum(lesson_count) as lesson_total,count(1) as lesson_num "
                                  ." from %s l "
                                  ." left join %s s on l.userid=s.userid "
                                  ." left join %s t on l.teacherid=t.teacherid "
                                  ." where %s "
                                  ." and s.is_test_user=0 "
                                  ." and t.is_test_user=0 "
                                  ." and lesson_type in (0,1,3) "
                                  ." group by l.teacher_money_type,l.level "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_1v1_money($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select l.teacherid,l.grade,deduct_come_late,deduct_change_class,deduct_check_homework,"
                                  ." deduct_rate_student,deduct_upload_cw,lesson_full_num,already_lesson_count,l.lesson_count,"
                                  ." o.price,m.money,m.type,m.teacher_money_type,m.level,l.confirm_flag,"
                                  ." lesson_cancel_time_type,lesson_cancel_reason_type "
                                  ." from %s l"
                                  ." left join %s s on l.userid=s.userid"
                                  ." left join %s o on l.lessonid=o.lessonid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on l.level=m.level"
                                  ." and m.grade=(case when"
                                  ." l.competition_flag=1 then if(l.grade<200,203,303)"
                                  ." else l.grade"
                                  ." end ) and t.teacher_money_type=m.teacher_money_type"
                                  ." where %s"
                                  ." and lesson_status=2"
                                  ." and (confirm_flag!=2 or deduct_change_class>0)"
                                  ." and lesson_type in (0,1,3)"
                                  ." and lesson_del_flag=0"
                                  ." and s.is_test_user=0"
                                  ." and t.is_test_user=0"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_order_lesson_list::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_money_type::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_fourth_teacher_already_lesson_count($start,$end){
        $where_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        $sql = $this->gen_sql_new("select sum(lesson_count) as already_lesson_count,teacherid"
                                  ." from %s"
                                  ." where %s"
                                  ." and confirm_flag!=2"
                                  ." and lesson_del_flag=0"
                                  ." and teacher_money_type in (4,5)"
                                  ." group by teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }

    public function get_lesson_info_stu($lessonid){
        $sql = $this->gen_sql_new("select lesson_start,s.nick from %s l join %s s on l.userid = s.userid where l.lessonid = %u",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $lessonid
        );
        return $this->main_get_row($sql);
    }

    public function get_stu_subject_list($start_time,$end_time,$type=1){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
        ];
        if($type==1){
            $str = " exists ";
        }else{
            $str = " not exists ";
        }

        $sql = $this->gen_sql_new("select count(distinct(l.subject)) as subject_num"
                                  ." from %s l"
                                  ." left join %s s on l.userid=s.userid "
                                  ." where %s "
                                  ." and lesson_status=2"
                                  ." and lesson_del_flag=0"
                                  ." and lesson_type in (0,1,3)"
                                  ." and %s (select 1 "
                                  ." from %s"
                                  ." where userid=l.userid"
                                  ." and contract_type=3"
                                  ." and contract_status in (1,2)"
                                  ." )"
                                  ." group by l.userid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$str
                                  ,t_order_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    /**
     * type 1 新的 2 旧的
     */
    public function get_lesson_total_by_stuid($start,$end,$type=1){
        $where_arr=[
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        if($type==1){
            $str=" exists ";
        }else{
            $str="not exists ";
        }

        $sql=$this->gen_sql_new("select sum(l.lesson_count)/100 as lesson_count "
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid"
                                ." where %s"
                                ." and lesson_status=2"
                                ." and s.is_test_user=0"
                                ." and confirm_flag<2"
                                ." and lesson_del_flag=0"
                                ." and l.lesson_type in (0,1,3)"
                                ." and %s"
                                ." (select 1 from %s "
                                ." where userid=l.userid "
                                ." and contract_status in (1,2)"
                                ." and contract_type=3"
                                .")"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
                                ,$str
                                ,t_order_info::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function get_trial_lesson_total($start_time,$end_time){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type=2",
            "lesson_status=2"
        ];

        $sql=$this->gen_sql_new("select sum(lesson_count)/100 "
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid "
                                ." where %s "
                                ." and confirm_flag<2"
                                ." and lesson_del_flag=0"
                                ." and s.is_test_user=0"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_total($start,$end,$teacher_money_type=-1,$level=-1){
        $where_arr=[
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            ["teacher_money_type=%u",$teacher_money_type,-1],
            ["level=%u",$level,-1],
        ];

        $sql=$this->gen_sql_new("select count(distinct(l.userid)) "
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid"
                                ." where %s"
                                ." and s.is_test_user=0"
                                ." and lesson_type in (0,1,3)"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_total_by_type($start_time,$end_time,$type=1){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
        ];
        if($type==1){
            $str=" exists ";
        }else{
            $str= " not exists ";
        }

        $sql=$this->gen_sql_new("select count(distinct(l.userid))"
                                ." from %s l "
                                ." left join %s s on l.userid=s.userid"
                                ." where %s"
                                ." and s.is_test_user=0"
                                ." and lesson_type in (0,1,3)"
                                ." and %s (select 1 "
                                ." from %s "
                                ." and userid=l.userid"
                                ." and contract_status in (1,2)"
                                ." and contract_type=3"
                                .")"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
                                ,$str
                                ,t_order_info::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function get_limit_type_teacher_lesson_num($teacherid,$start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "((l.lesson_status =2 and (tss.success_flag=1 or l.lesson_user_online_status=1)) or (l.lesson_status < 2 and tss.success_flag in (0,1)))"
        ];
        $sql = $this->gen_sql_new("select count(1) num from %s l ".
                                  "join %s tss on l.lessonid = tss.lessonid ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_lesson_num_list($teacher_arr,$start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "((l.lesson_status =2 and (tss.success_flag=1 or l.lesson_user_online_status=1)) or (l.lesson_status < 2 and tss.success_flag in (0,1)))"
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacher_arr);
        $sql = $this->gen_sql_new("select count(1) num,l.teacherid from %s l ".
                                  "join %s tss on l.lessonid = tss.lessonid ".
                                  " where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }


    public function get_limit_type_teacher_lesson_num_grade($teacherid,$start_time,$end_time,$grade_arr){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "((l.lesson_status =2 and (tss.success_flag=1 or l.lesson_user_online_status=1)) or (l.lesson_status < 2 and tss.success_flag in (0,1)))"
        ];
        $this->where_arr_teacherid($where_arr,"l.grade", $grade_arr);
        $sql = $this->gen_sql_new("select count(1) num from %s l ".
                                  "join %s tss on l.lessonid = tss.lessonid ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_limit_type_teacher_end_lesson_num($teacherid,$start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            "lesson_type = 2",
            "lesson_status =2",
            "lesson_del_flag = 0",
            "tss.success_flag in (0,1)"
        ];
        $sql = $this->gen_sql_new("select count(1) num from %s l ".
                                  "join %s tss on l.lessonid = tss.lessonid ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_test_lesson_info_for_api($userid){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "l.userid = ".$userid
        ];
        $sql = $this->gen_sql_new("select lesson_type,lesson_start,l.subject,t.realname teacher_nick,m.account jw_nick,tss.success_flag,lesson_status ".
                                  "from %s l left join %s tss on l.lessonid = tss.lessonid ".
                                  " left join %s t on l.teacherid = t.teacherid".
                                  " left join %s m on tss.set_lesson_adminid = m.uid".
                                  " where %s order by lesson_start desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_cost_list($start_time,$end_time,$select_type,$student_type){
        $exists_str = $student_type==0?"exists":"not exists";
        $lesson_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type in (0,1,3)",
            "lesson_status=2",
            "confirm_flag!=2",
            "lesson_del_flag=0",
        ];
        $where_arr = [
            ["lesson_start<%u",$end_time,0],
            "lesson_type in (0,1,3)",
            "lesson_status=2",
            "confirm_flag!=2",
            "lesson_del_flag=0",
        ];
        $order_arr = [
            ["pay_time>%u",$start_time,0],
            ["pay_time<%u",$end_time,0],
            "contract_type=1",
            "contract_status in (1,2,3)",
        ];

        if($select_type==0){
            $select_str = "l.userid";
        }else{
            $select_str = "l.userid,l.subject";
        }

        $sql = $this->gen_sql_new("select %s,s.nick,sum(l.lesson_count) as lesson_cost,s.type,max(l.lesson_end) as lesson_end"
                                  ." from %s l"
                                  ." left join %s s on l.userid=s.userid"
                                  ." where %s ("
                                  ." select 1 from %s"
                                  ." where %s"
                                  ." and l.userid=userid"
                                  ." )"
                                  ." and not exists (select 1 "
                                  ." from %s "
                                  ." where l.userid=userid"
                                  ." and %s"
                                  ." )"
                                  ." and %s"
                                  ." and s.is_test_user=0"
                                  ." group by %s"
                                  ,$select_str
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$exists_str
                                  ,self::DB_TABLE_NAME
                                  ,$lesson_arr
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$order_arr
                                  ,$where_arr
                                  ,$select_str
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_other_subject_list(){
        $where_arr =[
            "l.lesson_start < ".(time()-60*86400),
            "(t.realname not like '%%test%%' and  t.realname not like '%%测试%%')"
        ];
        $sql = $this->gen_sql_new("select distinct t.subject main_sub,t.realname,l.teacherid,l.subject other_sub from %s l left join %s t on l.teacherid = t.teacherid".
                                  " where %s and t.subject <> l.subject and t.trial_lecture_is_pass =1 and t.subject<>0 and l.lesson_type=0",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $ret =  $this->main_get_list($sql);
        $arr=[];
        foreach($ret as $item){
            @$arr[$item["teacherid"]][] =$item;
        }
        return $arr;
    }

    public function get_teacher_trial_lesson_list($start_time,$end_time,$identity=0,$subject=0,$is_new_teacher=0,$tea_subject="",
                                                  $count_type=1,$teacherid=0
    ){
        $where_arr = [
            ["l.lesson_start>%u",$start_time,0],
            ["l.lesson_start<%u",$end_time,0],
            ["t.subject=%u",$subject,0],
            ["t.identity=%u",$identity,0],
            ["l.teacherid=%u",$teacherid,0],
            "l.lesson_type=2",
            "l.lesson_del_flag=0",
            "l.teacherid>0",
            "l.lesson_user_online_status =1",
            "tl.success_flag in (0,1)",
        ];

        if($is_new_teacher ==2){
            $where_arr[] = "t.create_time =(select max(create_time) from db_weiyi.t_teacher_info where t.teacherid=teacherid)";
        }else if($is_new_teacher ==3){
            $time = time()-7*86400;
            $where_arr[] = "t.create_time>=".$time;
        }else if($is_new_teacher ==4){
            $time = time()-7*86400*2;
            $where_arr[] = "t.create_time>=".$time;
        }else if($is_new_teacher ==5){
            $time = time()-86400*30;
            $where_arr[] = "t.create_time>=".$time;
        }

        if(!empty($tea_subject)){
            $where_arr[]="l.subject in".$tea_subject;
        }

        if($count_type==1){
            $limit_str = "limit 10";
            $have_str  = "having count(1)>0";
        }elseif($count_type==2){
            $limit_str = "limit 10,20";
            $have_str  = "having count(1)>10";
        }elseif($count_type==3){
            $limit_str = "limit 20,30";
            $have_str  = "having count(1)>20";
        }

        if($teacherid==0){
            $ret = $this->get_tea_trial_lesson_list($where_arr,$have_str);
        }else{
            $ret = $this->get_trial_lesson_rate($where_arr,$have_str,$limit_str);
        }
        return $ret;
    }

    private function get_tea_trial_lesson_list($where_arr,$have_str){
        $sql = $this->gen_sql_new("select l.teacherid,t.nick,t.identity,t.subject,t.teacher_money_type,t.level"
                                  ." from %s l "
                                  ." left join %s tl on l.lessonid=tl.lessonid "
                                  ." left join %s t on l.teacherid=t.teacherid "
                                  ." where %s "
                                  ." group by l.teacherid "
                                  ." %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$have_str
        );

        return $this->main_get_list($sql);
    }

    private function get_trial_lesson_rate($where_arr,$have_str,$limit_str){
        $sql = $this->gen_sql_new("select count(distinct(lessonid)) all_lesson,"
                                  ." count(distinct(userid,teacherid,subject)) order_number,"
                                  ." count(distinct(orderid)) have_order,"
                                  ." min(lesson_start) min_lesson_start,"
                                  ." max(lesson_start) max_lesson_start"
                                  ." from ("
                                  ." select l.lessonid,l.lesson_start,c.userid,c.teacherid,c.subject,o.orderid"
                                  ." from %s l "
                                  ." left join %s tl on l.lessonid=tl.lessonid "
                                  ." left join %s o on l.lessonid=o.from_test_lesson_id and order_status>0 and o.orderid>0"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0) "
                                  ." where %s "
                                  ." %s "
                                  ." order by lesson_start asc"
                                  ." %s"
                                  ." ) a "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$have_str
                                  ,$limit_str
        );

        try {
            return $this->main_get_row($sql);
        }catch (\Exception $e) {
            return  false;
        }
    }

    public function get_order_add_time(){
        $where_arr = [
            "l.lesson_start >= ".(time()-30*86400),
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "order_time >0",
            "ll.lesson_start >0"
        ];
        $sql = $this->gen_sql_new("select count(1) all_count,sum(o.order_time) order_time,sum(ll.lesson_start) lesson_time"
                                  ." from %s l "
                                  ." left join %s o on (l.lessonid = o.from_test_lesson_id and order_status>0)"
                                  ." left join %s ll on "
                                  ." (l.userid = ll.userid "
                                  ." and l.teacherid = ll.teacherid "
                                  ." and l.subject = ll.subject "
                                  ." and ll.lesson_type=0 and ll.lessonid >0 and ll.lesson_start=(select min(lesson_start) from %s where  userid = l.userid and teacherid = l.teacherid and subject = l.subject  and lesson_type=0 and lessonid >0 and lesson_start >0)) "
                                  ." where %s "
                                  ." and l.teacherid > 0 ",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_user_test_lesson_start($userid,$order_time){
        $where_arr =[
            ["lesson_start<=%u",$order_time,-1],
            "lesson_type=2",
            "lesson_del_flag =0",
            "confirm_flag in(0,1)",
            "userid=".$userid
        ];
        $sql =$this->gen_sql_new("select max(lesson_start) lesson_start from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function check_teacher_have_test_lesson($teacherid,$lessonid,$start_time){
        $where_arr =[
            "l.lesson_type=2",
            "l.lesson_del_flag =0",
            "l.confirm_flag in(0,1)",
            "l.lesson_user_online_status =1",
            "l.teacherid=".$teacherid,
            "l.lessonid <>".$lessonid,
            "(tts.success_flag is null or tts.success_flag in(0,1))",
            "l.lesson_start < ".$start_time
            // "(tts.set_lesson_time is null or tts.set_lesson_time <".$start_time.")"
        ];
        $sql = $this->gen_sql_new("select 1 from %s l left join %s tts on l.lessonid=tts.lessonid where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    //检查老师是否为新入职老师(有无试听课)
    public function check_teacher_have_test_lesson_pre_week($teacherid,$start_time){
        $where_arr =[
            "l.lesson_type=2",
            "l.lesson_del_flag =0",
            "l.confirm_flag in(0,1)",
            "l.teacherid=".$teacherid,
            "(tts.success_flag is null or tts.success_flag in(0,1))",
            "l.lesson_start < ".$start_time
        ];
        $sql = $this->gen_sql_new("select 1 from %s l left join %s tts on l.lessonid=tts.lessonid where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_lesson_full_wage_old($start,$end,$lesson_num,$order_type){
        $where_arr=[
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        if($order_type==0){
            $order_str = "desc";
        }else{
            $order_str = "asc";
        }
        $sql = $this->gen_sql_new("select count(1)*100 as lesson_full_price,nick "
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s tl on l.lessonid=tl.lessonid "
                                  ." where %s"
                                  ." and lesson_full_num%%%u=0"
                                  ." and t.is_test_user=0"
                                  ." and lesson_full_num!=0"
                                  ." and lesson_type <1000"
                                  ." and lesson_del_flag=0"
                                  ." and confirm_flag!=2"
                                  ." and deduct_come_late=0"
                                  ." and deduct_change_class=0"
                                  ." and lesson_status=2"
                                  ." and (tl.test_lesson_fail_flag<100 or tl.test_lesson_fail_flag is null "
                                  ." or (tl.test_lesson_fail_flag in (101,102) and tl.fail_greater_4_hour_flag=0))"
                                  ." group by l.teacherid"
                                  ." order by count(1) %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$lesson_num
                                  ,$order_str
        );
        return $this->main_get_list($sql);
    }


    public function get_teacher_first_test_lesson_info($teacherid_arr,$subject=-1,$record_flag=-1,$record_adminid=-1){
        $where_arr = [
            ["t.subject =%u",$subject,-1],
            ["m.uid =%u",$record_adminid,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.lesson_user_online_status =1",
            "tss.success_flag in (0,1)"
        ];

        $this->where_arr_teacherid($where_arr,"l.teacherid",$teacherid_arr);

        if($record_flag==0){
            $where_arr[] = "r.add_time is null";
        }else if($record_flag==1){
            $where_arr[]="r.add_time>0";
        }
        $sql = $this->gen_sql_new("select count(1) all_lesson,l.teacherid,t.realname,t.create_time,t.level,t.school,t.identity,t.subject,t.grade_part_ex,r.add_time,r.acc "
                                  ." from %s l "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s r on (l.teacherid = r.teacherid and r.type=1 and r.add_time = (select min(add_time) from %s where teacherid = l.teacherid and type=1))"
                                  ." left join %s m on r.acc=m.account"
                                  ." where %s "
                                  ." group by l.teacherid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }
    public function reset_real_begin_time() {
        $where_arr=[
            "real_begin_time=0",
            "real_end_time>0",
        ];
        $today=strtotime(date("Y-m-d"));
        $this->where_arr_add_time_range($where_arr,"lesson_start",$today-86400,$today+86400);
        $sql= $this->gen_sql_new(
            "  update  %s set real_begin_time=lesson_start where %s  ",
            self::DB_TABLE_NAME,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_test_lesson_info_with_userid_and_teacherid($start,$end){
        $where_arr = [
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "lesson_start >= ".$start,
            "lesson_start < ".$end,
            "l.grade >0",
            "l.subject in (1,2,3,4,5)"
        ];
        $sql = $this->gen_sql_new("select userid,teacherid,subject from %s l"
                                  ." left join %s tss on l.lessonid=tss.lessonid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);


    }

    public function get_all_test_lesson_info_by_subject_and_grade($last_month_start,$month_start){
        $where_arr = [
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "lesson_start >= ".$last_month_start,
            "lesson_start < ".$month_start,
        ];
        $sql = $this->gen_sql_new("select grade,subject from %s l"
                                  ." left join %s tss on l.lessonid=tss.lessonid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);


    }

    public function get_normal_stu_num_new($start_time,$end_time,$teacherid){
        $where_arr = [
            "lesson_type = 0",
            "lesson_del_flag = 0",
            "confirm_flag in (0,1)",
            "lesson_start >= ".$start_time,
            "lesson_start < ".$end_time,
            "l.teacherid =".$teacherid,
            "s.type=0"
        ];
        $sql = $this->gen_sql_new("select count(distinct l.userid)  from %s l"
                                  ." left join %s s on l.userid=s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }
    public function get_normal_stu_num_new_list($start_time,$end_time){
        $where_arr = [
            "lesson_type = 0",
            "lesson_del_flag = 0",
            "confirm_flag in (0,1)",
            "lesson_start >= ".$start_time,
            "lesson_start < ".$end_time,
            "s.type=0"
        ];
        $sql = $this->gen_sql_new("select t.realname,t.subject,l.teacherid,count(distinct l.userid) yy from %s l"
                                  ." left join %s s on l.userid=s.userid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s group by l.teacherid order by yy desc ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function check_is_normal_stu_new($start_time,$end_time,$teacherid,$userid){
        $where_arr = [
            "lesson_type = 0",
            "lesson_del_flag = 0",
            "confirm_flag in (0,1)",
            "lesson_start >= ".$start_time,
            "lesson_start < ".$end_time,
            "l.teacherid =".$teacherid,
            "l.userid =".$userid,
            "s.type=0"
        ];
        $sql = $this->gen_sql_new("select 1 from %s l"
                                  ." left join %s s on l.userid=s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);


    }

    public function get_research_test_lesson_info_list($subject,$grade,$start_time,$end_time){

        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "m.del_flag=0",
            "m.account_role=2",
            //"require_admin_type =2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($subject==-2){
            $where_arr[] ="l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[]=["l.subject = %u",$subject,-1];
        }
        if($grade ==100){
            $where_arr[]="l.grade >=100 and l.grade <200";
        }elseif($grade ==200){
            $where_arr[]="l.grade >=200 and l.grade <300";
        }elseif($grade ==300){
            $where_arr[]="l.grade >=300";
        }else{
            $where_arr[]=["l.grade = %u",$grade,-1];
        }

        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.teacherid,l.subject) all_lesson"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s m on m.uid = tq.cur_require_adminid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_research_test_lesson_info_list_by_grade($subject,$grade,$start_time,$end_time){

        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "m.del_flag=0",
            "m.account_role=2",
            //"require_admin_type =2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($subject==-2){
            $where_arr[] ="l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[]=["l.subject = %u",$subject,-1];
        }
        if($grade ==100){
            $where_arr[]="l.grade >=100 and l.grade <200";
        }elseif($grade ==200){
            $where_arr[]="l.grade >=200 and l.grade <300";
        }elseif($grade ==300){
            $where_arr[]="l.grade >=300";
        }else{
            $where_arr[]=["l.grade = %u",$grade,-1];
        }

        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.teacherid,l.subject) all_lesson,l.grade"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s m on m.uid = tq.cur_require_adminid"
                                  ." where %s group by l.grade",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }


    public function get_research_test_lesson_info_list_new($subject,$grade,$start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
        ];
        if($subject==-2){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade==0){
            $where_arr[] = "l.grade =0";
        }else if($grade==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade==300){
            $where_arr[] = "l.grade >=300";
        }



        $sql = $this->gen_sql_new("select l.userid,teacherid "
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }


    public function get_qz_test_lesson_info_list($qz_tea_arr,$start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "(m.account_role=2 or tq.origin like '%%转介绍%%' )",
            // "m.del_flag=0"
            //"require_admin_type =2"
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $qz_tea_arr );
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.subject) all_lesson,t.teacherid,t.realname"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s m on tq.cur_require_adminid=m.uid"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
          return  $item["teacherid"];
        });

    }

    public function get_qz_test_lesson_info_list2($qz_tea_arr,$start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            // "require_admin_type =1",
            "tq.origin like '%%扩课%%'"

        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $qz_tea_arr );
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.subject) all_lesson,t.teacherid,t.realname"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }

    public function get_qz_test_lesson_info_list3($qz_tea_arr,$start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            // "require_admin_type =1",
            "tq.origin like '%%换老师%%'"
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $qz_tea_arr );
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.subject) all_lesson,t.teacherid,t.realname"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }


    public function get_subject_order_list_new($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "m.del_flag=0",
            "m.account_role=2"
            //"require_admin_type =2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.teacherid,l.subject) all_lesson,l.subject"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ."left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." where %s group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });


    }


    public function get_subject_order_list_new2($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            // "require_admin_type =1",
            "tq.origin like '%%扩课%%'"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject)  order_num,count(distinct l.userid,l.teacherid,l.subject) all_lesson,l.subject"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ."left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." where %s group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }
    public function get_subject_order_list_new3($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            // "require_admin_type =1",
            "tq.origin like '%%换老师%%'"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject)  order_num,count(distinct l.userid,l.teacherid,l.subject) all_lesson,l.subject"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ."left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." where %s group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });


    }




    public function tongji_teacher_test_lesson_info_list($start_time,$end_time,$teacherid,$teacher_money_type,$subject,$identity
                                                         ,$is_new_teacher=1,$tea_subject="",$teacher_account=-1,$have_interview_teacher=-1
                                                         ,$reference_teacherid=-1,$grade_part_ex=-1,$teacher_subject=-1,$qz_flag=0
    ){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            ["tt.teacherid = %u",$reference_teacherid,-1],
            ["t.teacher_money_type = %u",$teacher_money_type,-1],
            ["t.subject = %u",$teacher_subject,-1],
            ["t.identity = %u",$identity,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            ["ttt.teacherid = %u",$teacher_account,-1],
        ];

        if($qz_flag==1){
            $where_arr[] = "mmm.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($is_new_teacher ==2){
            $where_arr[] = "t.create_time =(select max(create_time) from db_weiyi.t_teacher_info where t.teacherid=teacherid)";
        }else if($is_new_teacher ==3){
            $time = time()-7*86400;
            $where_arr[] = "t.create_time>=".$time;
        }else if($is_new_teacher ==4){
            $time = time()-7*86400*2;
            $where_arr[] = "t.create_time>=".$time;
        }else if($is_new_teacher ==5){
            $time = time()-86400*30;
            $where_arr[] = "t.create_time>=".$time;
        }else if($is_new_teacher ==6){
            $where_arr[] = "t.create_time<=".($start_time-15);
        }

        if(!empty($tea_subject)){
            $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
        }

        if($have_interview_teacher==0){
            $where_arr[] = "tl.account is null";
        }else if($have_interview_teacher==1){
            $where_arr[] = "tl.account is not null";
        }

        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==1){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==2){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==3){
            $where_arr[] = "l.grade >=300";
        }

        $sql = $this->gen_sql_new("select count(distinct l.lessonid) all_lesson,"
                                  ." l.teacherid,t.nick,t.create_time,t.level, "
                                  ." t.interview_access,t.limit_plan_lesson_type,t.subject, "
                                  ." t.teacher_money_type,t.identity,t.school,t.is_freeze,tl.account "
                                  ." ,t.limit_plan_lesson_account,t.limit_plan_lesson_reason, "
                                  ." t.limit_plan_lesson_time,tr.add_time,tr.record_info,tr.acc, "
                                  ." t.freeze_time,t.freeze_reason,t.freeze_adminid,mm.account freeze_account  "
                                  ." from %s l "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tl on (t.phone=tl.phone and tl.status=1)"
                                  ." left join %s m on m.account=tl.account"
                                  ." left join %s ttt on (ttt.phone = m.phone )"
                                  ." left join %s tll on (t.phone=tll.phone and tll.answer_begin_time = (select max(answer_begin_time) from db_weiyi.t_teacher_lecture_appointment_info where phone = t.phone))"
                                  ." left join %s tt on tll.reference = tt.phone"
                                  ." left join %s tr on (tr.teacherid = t.teacherid and tr.type=1 and tr.add_time = (select max(add_time) from db_weiyi.t_teacher_record_list where teacherid = t.teacherid))"
                                  ." left join %s mm on t.freeze_adminid = mm.uid"
                                  ." left join %s mmm on t.phone = mmm.phone"
                                  ." where %s "
                                  ." and l.teacherid > 0 "
                                  ." group by l.teacherid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }
    public function tongji_teacher_test_lesson_info_list_ss($start_time,$end_time,$teacherid,$teacher_money_type,$subject,$identity
                                                            ,$tea_subject="",$grade_part_ex=-1,$teacher_subject=-1,$qz_flag=0,$page_num
    ){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            ["t.teacher_money_type = %u",$teacher_money_type,-1],
            ["t.subject = %u",$teacher_subject,-1],
            ["t.identity = %u",$identity,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
        ];

        if($qz_flag==1){
            $where_arr[] = "mmm.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }

        if(!empty($tea_subject)){
            $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
        }


        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==1){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==2){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==3){
            $where_arr[] = "l.grade >=300";
        }

        $sql = $this->gen_sql_new("select count(distinct l.lessonid) all_lesson,"
                                  ." l.teacherid,t.nick,t.create_time,t.level, "
                                  ." t.interview_access,t.limit_plan_lesson_type,t.subject, "
                                  ." t.teacher_money_type,t.identity,t.school,t.is_freeze,tl.account "
                                  ." ,t.limit_plan_lesson_account,t.limit_plan_lesson_reason, "
                                  ." t.limit_plan_lesson_time, "
                                  ." t.freeze_time,t.freeze_reason,t.freeze_adminid,mm.account freeze_account  "
                                  ." from %s l "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tl on (t.phone=tl.phone and tl.status=1)"
                                  ." left join %s mm on t.freeze_adminid = mm.uid"
                                  ." left join %s mmm on t.phone = mmm.phone"
                                  ." where %s "
                                  ." and l.teacherid > 0 "
                                  ." group by l.teacherid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }




    public function get_success_test_lesson_list_new($start_time,$end_time,$subject,$grade_part_ex,$teacherid_list=[]){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_number,"
                                  ." count(distinct l.lessonid,l.subject) success_lesson,l.teacherid"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_success_test_lesson_list_new_total($start_time,$end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag=-1,$fulltime_teacher_type=-1){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            ["t.teacherid=%u",$teacherid,-1],
            ["t.subject=%u",$teacher_subject,-1],
            ["t.identity=%u",$identity,-1],
            ["tt.teacherid = %u",$teacher_account,-1],
            ["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
            "t.trial_lecture_is_pass =1",
            "t.train_through_new =1"
        ];
        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
            }
        }

        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }else if($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
             $where_arr[] = "m.del_flag=0";
        }

        if($tea_status==1){
            $where_arr[] = "t.is_freeze=1";
        }else if($tea_status==2){
            $where_arr[] = "t.limit_plan_lesson_type>0 and t.is_freeze=0";
        }else if($tea_status==3){
            $where_arr[] = "t.limit_plan_lesson_type=0 and t.is_freeze=0";
        }


        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }

        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }

        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_number,count(distinct l.lessonid) success_lesson"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on m.phone=t.phone"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        //dd($sql);
        return $this->main_get_row($sql);

    }

    public function get_success_test_lesson_list_new_subject($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_number,count(distinct l.lessonid) success_lesson,l.subject"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }


    public function get_success_not_test_lesson_list_new($start_time,$end_time,$subject,$grade_part_ex,$teacherid_list=[]){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }
        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list);

        $sql = $this->gen_sql_new("select count(distinct l.lessonid) success_not_in_lesson,l.teacherid"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_success_not_test_lesson_list_new_total($start_time,$end_time,$subject,$grade_part_ex){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =2",
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==1){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==2){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==3){
            $where_arr[] = "l.grade >=300";
        }

        $sql = $this->gen_sql_new("select count(distinct l.lessonid) success_not_in_lesson"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_train_lesson($page_num,$start_time,$end_time,$teacherid=-1,$lesson_status=-1,
                                     $lessonid=-1,$lesson_sub_type=-1,$train_type=-1
    ){
        if($lessonid!=-1){
            $where_arr = [
                ["l.lessonid=%u",$lessonid,-1],
            ];
        }else{
            $where_arr = [
                ["l.lesson_start>%u",$start_time,0],
                ["l.lesson_start<%u",$end_time,0],
                ["l.teacherid=%u",$teacherid,-1],
                ["l.lesson_status=%u",$lesson_status,-1],
                ["l.lesson_sub_type=%u",$lesson_sub_type,-1],
                ["l.train_type=%u",$train_type,-1],
            ];
        }
        $where_arr=$this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select l.lessonid,l.teacherid,t.realname as tea_nick,lesson_start,lesson_end,lesson_type,"
                                  ." l.subject,l.grade,lesson_name,tea_cw_url,lesson_status,l.server_type,l.courseid,lesson_num,"
                                  ." tea_cw_url,count(distinct(tl.userid)) as user_num,count(distinct(lo.userid)) as login_num,"
                                  ." count(distinct(t2.teacherid)) as through_num,l.train_type, l.xmpp_server_name, c.current_server "
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s tl on l.lessonid=tl.lessonid"
                                  ." left join %s lo on l.lessonid=lo.lessonid"
                                  ." left join %s t2 on tl.userid=t2.teacherid and t2.train_through_new_time>0"
                                  ." left join %s c on c.courseid= l.courseid "
                                  ." where %s"
                                  ." and lesson_type=1100"
                                  ." group by l.lessonid"
                                  ." order by lesson_start desc,lessonid desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_lesson_opt_log::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true,"",function($item){
            return $item["lessonid"];
        });
    }

    public function get_report_train_lesson_info($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type=1100",
            "lesson_status=2"
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select lessonid "
                                  ." from %s"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_have_order_list_new($start_time,$end_time,$subject,$grade_part_ex,$teacherid_list=[]){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==100){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==200){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==300){
            $where_arr[] = "l.grade >=300";
        }else{
            $where_arr[] =  ["l.grade = %u",$grade_part_ex,-1];
        }
        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list);

        $sql = $this->gen_sql_new("select sum(if(o.orderid>0,1,0)) have_order,l.teacherid"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s o on (l.lessonid = o.from_test_lesson_id and order_status>0)"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });


    }

    public function get_have_order_list_new_total($start_time,$end_time,$subject,$grade_part_ex){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($subject==20){
            $where_arr[] = "l.subject in (4,5,6,7,8,9,10)";
        }else{
            $where_arr[] =  ["l.subject = %u",$subject,-1];
        }
        if($grade_part_ex==0){
            $where_arr[] = "l.grade =0";
        }else if($grade_part_ex==1){
            $where_arr[] = "(l.grade >=100 and l.grade <200)";
        }else if($grade_part_ex==2){
            $where_arr[] = "(l.grade >=200 and l.grade <300)";
        }else if($grade_part_ex==3){
            $where_arr[] = "l.grade >=300";
        }
        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_list);

        $sql = $this->gen_sql_new("select sum(if(o.orderid>0,1,0)) have_order"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s o on (l.lessonid = o.from_test_lesson_id and order_status>0)"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);


    }

    public function get_user_count_list( $start_time ,$end_time) {
        $where_arr=[
            "is_test_user=0",
            "lesson_type in (0, 1,3 )",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            "select   from_unixtime(lesson_start, '%%Y-%%m-%%d') as opt_date, count(distinct l.userid ) as count  "
            ." from %s l  "
            ."join  %s s on l.userid =s.userid "
            ." where %s group by  from_unixtime(lesson_start, '%%Y-%%m-%%d')  ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr ) ;
        return $this->main_get_list($sql);
    }

    public function get_teacher_month_order_info($start_time,$end_time,$teacherid){

        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "l.teacherid=".$teacherid,
            "m.account_role=2",
            "m.del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);


        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_number,count(distinct l.lessonid) success_lesson"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tr on tr.require_id = tss.require_id"
                                  ." left join %s m on m.uid= tr.cur_require_adminid"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_lesson_count_all_by_teacherid_new($teacherid,$start_time,$end_time){
        $where_arr1=[
            "lesson_type = 0",
            "lesson_del_flag = 0",
            ["teacherid =%u",$teacherid,-1],
            "confirm_flag in (0,1)"
        ];
        $this->where_arr_add_time_range($where_arr1,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select sum(lesson_count) from %s where %s",self::DB_TABLE_NAME,$where_arr1);
        $count1 = $this->main_get_value($sql);
        $where_arr2=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            ["l.teacherid =%u",$teacherid,-1],
            "tss.success_flag in (0,1)"
        ];
        $this->where_arr_add_time_range($where_arr2,"lesson_start",$start_time,$end_time);
        $sql=$this->gen_sql_new("select sum(lesson_count) from %s l join %s tss on l.lessonid = tss.lessonid where %s",
                                self::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                $where_arr2
        );
        $count2 = $this->main_get_value($sql);
        return $count1+$count2;

    }

    public function get_all_test_lesson_num_new($start_time,$subject){
        $end_time=time();
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            ["l.subject=%u",$subject,-1],
            "require_admin_type=2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_number,count(distinct l.lessonid) success_lesson"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "

                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "

                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function set_lesson_week_comment_num($courseid,$week_comment_num){
        $where_arr = [
            ["courseid=%u",$courseid,0],
        ];
        $sql = $this->gen_sql_new("update %s set week_comment_num=%u where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$week_comment_num
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function set_lesson_enable_video($courseid,$enable_video){
        $where_arr = [
            ["courseid=%u",$courseid,0],
        ];
        $sql = $this->gen_sql_new("update %s set enable_video=%u where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$enable_video
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_lesson_info_by_time($start_time,$end_time){
        $where_arr=[
            "l.lesson_type in (0,2)",
            "l.lesson_del_flag = 0",
            "l.confirm_flag in (0,1)",
            "(tss.success_flag in (0,1) or tss.success_flag is null)"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.lesson_start,l.lesson_end,s.nick,lesson_type,l.subject from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s s on l.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function reset_lesson_teacher_level($teacherid,$level,$lesson_status=0){
        if($teacherid==-1){
            return false;
        }
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            ["lesson_status=%u",$lesson_status,-1],
            "lesson_type in (0,1,3)"
        ];
        $sql = $this->gen_sql_new("update %s set level=%u where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$level
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_teacher_level_num($teacherid,$level){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            ["level=%u",$level,-1],
            "lesson_type in (0,1,3)",
            "lesson_status=0"
        ];
        $sql = $this->gen_sql_new("select count(1) from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_course_lesson_num($courseid){
        $sql = $this->gen_sql_new("select count(1) "
                                  ." from %s "
                                  ." where courseid=%u"
                                  ,self::DB_TABLE_NAME
                                  ,$courseid
        );
        return $this->main_get_value($sql);
    }

    public function get_week_stu_num_info($start_time,$end_time,$account_id){
        $where_arr=[
            "l.lesson_type = 0",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct l.userid) from %s l"
                                  ." left join %s a on l.assistantid = a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s and m.uid = %u",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $account_id
        );
        return $this->main_get_value($sql);

    }

    public function get_all_lesson_info_by_time($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "tss.success_flag <>2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select sum(FROM_UNIXTIME( l.lesson_start, '%%w' )=0 ) seven_num,sum(FROM_UNIXTIME( l.lesson_start, '%%w' )=1 ) one_num,sum(FROM_UNIXTIME( l.lesson_start, '%%w' )=2 ) two_num,sum(FROM_UNIXTIME( l.lesson_start, '%%w' )=3 ) three_num,sum(FROM_UNIXTIME( l.lesson_start, '%%w' )=4 ) four_num,sum(FROM_UNIXTIME( l.lesson_start, '%%w' )=5 ) five_num,sum(FROM_UNIXTIME( l.lesson_start, '%%w' )=6 ) six_num from %s l left join %s tss on l.lessonid = tss.lessonid where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }
    public function get_all_lesson_info_by_time_tea($start_time,$end_time,$num){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "tss.success_flag <>2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select  count(distinct teacherid) num from %s l left join %s tss on l.lessonid = tss.lessonid where %s and FROM_UNIXTIME( l.lesson_start, '%%w' )=%u group by FROM_UNIXTIME( l.lesson_start, '%%Y-%%m-%%d' ) ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr,
                                  $num
        );
        return $this->main_get_list($sql);

    }

    public function get_all_lesson_info_by_time_new($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "tss.success_flag <>2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select lesson_start from %s l left join %s tss on l.lessonid = tss.lessonid where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_test_lesson_num_by_attend_time($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "tss.success_flag <>2",
            "l.tea_attend>0",
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(1) from %s l left join %s tss on l.lessonid = tss.lessonid where %s and stu_attend < (lesson_start+1200) and l.stu_attend >0",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_test_lesson_num_by_attend_time_detail($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "tss.success_flag <>2",
            "l.tea_attend>0",
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select l.lessonid,l.stu_attend,l.lesson_start from %s l left join %s tss on l.lessonid = tss.lessonid where %s and stu_attend > (lesson_start+1200) and l.stu_attend >0",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);

    }


    public function get_teacher_week_test_lesson_info($teacherid,$start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "tss.success_flag <>2",
            ["l.teacherid=%u",$teacherid,-1]
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select l.lesson_start,l.lesson_end from %s l left join %s tss on l.lessonid = tss.lessonid where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);


    }

    public function get_test_lesson_num_by_free_time($start_time,$end_time,$teacherid_arr=[]){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "tss.success_flag <>2",
        ];

        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_arr);
        $sql = $this->gen_sql_new("select teacherid,count(l.lessonid) num from %s l left join %s tss on l.lessonid = tss.lessonid "
                                  ." where %s and l.lesson_start<%u and l.lesson_end>%u group by l.teacherid having(num >0)",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr,
                                  $end_time,
                                  $start_time
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_test_lesson_num_by_free_time_new($start_time,$end_time){
        $where_arr=[
            // "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "(tss.success_flag <>2 or tss.success_flag is null)",
        ];

        $sql = $this->gen_sql_new("select distinct teacherid from %s l left join %s tss on l.lessonid = tss.lessonid "
                                  ." where %s and l.lesson_start<%u and l.lesson_start>%u ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr,
                                  $end_time,
                                  $start_time
        );
        $arr=  $this->main_get_list($sql);
        $list=[];
        foreach($arr as $val){
            $list[] = $val["teacherid"];
        }
        return $list;
    }



    public function get_test_lesson_num_by_teacherid($start_time,$end_time,$teacherid,$userid_list=[]){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "tss.success_flag <>2",
            "l.teacherid=".$teacherid
        ];
        $where_arr[]= $this->where_get_not_in_str("l.userid", $userid_list);
        $sql = $this->gen_sql_new("select count(distinct l.userid) num from %s l left join %s tss on l.lessonid = tss.lessonid "
                                  ." where %s and l.lesson_start>=%u and l.lesson_end<%u ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr,
                                  $start_time,
                                  $end_time
        );
        return $this->main_get_value($sql);
    }

    public function get_month_stu_lesson_count($start,$end,$teacherid,$userid){
        $where_arr=[
            "lesson_type = 0",
            "lesson_del_flag = 0",
            "confirm_flag <>2",
            "teacherid=".$teacherid,
            "userid=".$userid,
        ];

        $sql = $this->gen_sql_new("select sum(lesson_count)  from %s  "
                                  ." where %s and lesson_start>=%u and lesson_end<%u ",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  $start,
                                  $end
        );
        return $this->main_get_value($sql);

    }

    public function get_children_lesson_info($children_id){
        $sql = $this->gen_sql_new("select courseid, lessonid, lesson_num, grade, teacher_comment, lesson_name,"
                                  ." lesson_type, teacherid, lesson_time "
                                  ." from %s"
                                  ." where userid = %u ",
                                  self::DB_TABLE_NAME,
                                  $children_id
        );
        return $this->main_get_list($sql);
    }

    public function get_all_teacher_test_lesson_info($start_time,$end_time,$grade_part_ex=-1,$subject=-1,$train_through_new=-1){
        $where_arr=[
            ["t.grade_part_ex=%u",$grade_part_ex,-1],
            ["t.subject=%u",$subject,-1],
            ["t.train_through_new=%u",$train_through_new,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "require_admin_type =2",
            "t.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql =$this->gen_sql_new("select t.teacherid,t.realname,count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.teacherid,l.subject) all_lesson,t.train_through_new_time  ".
                                 " from %s l ".
                                 " left join %s t on l.teacherid=t.teacherid".
                                 " left join %s tss on l.lessonid = tss.lessonid".
                                 " left join %s c on ".
                                 " (l.userid = c.userid ".
                                 " and l.teacherid = c.teacherid ".
                                 " and l.subject = c.subject ".
                                 " and c.course_type=0 and c.courseid >0) ".
                                 "left join %s tq on tq.require_id = tss.require_id" .
                                 " left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id ".
                                 " where %s group by t.teacherid",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 t_course_order::DB_TABLE_NAME,
                                 t_test_lesson_subject_require::DB_TABLE_NAME,
                                 t_test_lesson_subject::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }

    public function get_test_lesson_info_by_subject_and_grade($page_num,$subject,$grade,$start_time,$end_time,$have_order,$page_count=20){
        $where_arr=[
            ["l.subject=%u",$subject,-1],
            // ["l.grade=%u",$grade,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            //"require_admin_type =2"
            "m.account_role=2",
            "m.del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($grade ==100){
            $where_arr[]="l.grade >=100 and l.grade <200";
        }elseif($grade ==200){
            $where_arr[]="l.grade >=200 and l.grade <300";
        }elseif($grade ==300){
            $where_arr[]="l.grade >=300";
        }else{
            $where_arr[]=["l.grade = %u",$grade,-1];
        }

        if($have_order==0){
            $where_arr[]="(c.subject =0 or c.subject is null)";
        }else if($have_order==1){
            $where_arr[]="c.subject >0";
        }
        $sql = $this->gen_sql_new("select distinct c.subject c_subject,l.teacherid,l.userid,l.subject,l.grade,l.lesson_start,t.realname,s.nick,l.lessonid"
                                  ." from %s l "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s s on l.userid= s.userid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." where %s order by l.teacherid,l.lesson_start",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,$page_count);


    }

    public function get_test_lesson_info_by_teacherid($teacherid_arr,$num){
        $where_arr=[
            // ["l.teacherid=%u",$teacherid,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "(tss.success_flag in (0,1) or tss.success_flag is null)",
            "l.lesson_user_online_status =1",
             "require_admin_type =2",
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_arr);

        $sql = $this->gen_sql_new("select l.teacherid,l.lesson_start,c.subject from %s l".
                                  " left join %s tss on l.lessonid = tss.lessonid".
                                 " left join %s c on ".
                                 " (l.userid = c.userid ".
                                 " and l.teacherid = c.teacherid ".
                                 " and l.subject = c.subject ".
                                 " and c.course_type=0 and c.courseid >0) ".
                                  "left join %s tq on tq.require_id = tss.require_id" .
                                  " left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id ".
                                  " where %s and l.lesson_start = (select ll.lesson_start from %s ll left join %s ttss on ll.lessonid = ttss.lessonid where ll.lesson_type = 2 and ll.lesson_del_flag = 0 and ll.confirm_flag <>2 and (ttss.success_flag in (0,1) or ttss.success_flag is null) and ll.lesson_user_online_status =1 and ll.teacherid = l.teacherid order by lesson_start limit %u,1) group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $num
        );
        return $this->main_get_list($sql);

    }

    public function get_all_first_lesson_teacher($start_time,$end_time,$subject=-1,$tea_subject=""){
        $where_arr=[
            "t.trial_lecture_is_pass=1",
            // "t.identity = 1",
            "t.realname <> '刘辉' and realname not like '%%alan%%' and realname not like '%%test%%' ",
            ["l.subject=%u",$subject,-1],
            "l.lesson_user_online_status =1",
        ];
        if(!empty($tea_subject)){
            $where_arr[]="(l.subject in".$tea_subject.")";
        }

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,l.lesson_start,l.lesson_type,t.realname,l.lessonid,l.subject "
                                  ." from %s l   "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s and t.is_test_user=0 and l.lesson_type=2 and  l.lesson_del_flag = 0 and l.confirm_flag <>2   and l.lesson_start =(select min(lesson_start) from %s  where lesson_type=2 and lesson_del_flag = 0 and confirm_flag <>2 and lesson_user_online_status =1 and teacherid = l.teacherid ) group by teacherid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr,
                                  t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_seller_test_lesson_info($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "m.account_role=2",
            "m.del_flag=0",
            // "tss.success_flag in (0,1)",
            // "l.stu_attend >0 ",
            //"l.tea_attend>0",
            "t.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.is_test_user, cur_require_adminid,count(l.lessonid) lesson_count,sum(tss.success_flag in (0,1) and l.lesson_user_online_status =1) suc_count,m.account,m.create_time,sum(if(o.orderid >0,1,0)) order_count,sum(o.price) all_price "
                                  ." from %s l left join %s tss on l.lessonid=tss.lessonid"
                                  ." left join %s tq on tss.require_id =tq.require_id"
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." left join %s o on l.lessonid = o.from_test_lesson_id"
                                  ." left join %s t on t.teacherid=l.teacherid "
                                  ." where %s group by cur_require_adminid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_seller_test_lesson_order_info($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "m.account_role=2",
            "m.del_flag=0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            // "t.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select cur_require_adminid,count(distinct c.teacherid,c.userid,c.subject) order_count"
                                  ." from %s l left join %s tss on l.lessonid=tss.lessonid"
                                  ." left join %s tq on tss.require_id =tq.require_id"
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s group by cur_require_adminid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["cur_require_adminid"];
        });
    }

    public function get_seller_test_lesson_order_info_new($start_time,$end_time,$adminid){
        $where_arr=[
            ["cur_require_adminid=%u",$adminid,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "m.account_role=2",
            "m.del_flag=0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            // "t.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select distinct o.orderid,l.lesson_start,t.realname,l.subject,l.grade,s.nick,tq.test_lesson_order_fail_desc,l.lessonid"
                                  ." from %s l left join %s tss on l.lessonid=tss.lessonid"
                                  ." left join %s tq on tss.require_id =tq.require_id"
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." left join %s o on l.lessonid = o.from_test_lesson_id"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s s on l.userid = s.userid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_test_lesson_teacher_info($adminid,$start_time,$end_time){
        $where_arr=[
            ["cur_require_adminid=%u",$adminid,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            // "t.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select distinct l.teacherid"
                                  ." from %s l left join %s tss on l.lessonid=tss.lessonid"
                                  ." left join %s tq on tss.require_id =tq.require_id"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  $where_arr
        );
        $ret =  $this->main_get_list($sql);
        $arr=[];
        foreach($ret as $item){
            $arr[] = $item["teacherid"];

        }
        return $arr;
    }

    public function get_seller_teacher_test_lesson_info($start_time,$end_time,$teacherid_arr){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "(tss.success_flag in (0,1) or tss.success_flag is null)",
            "l.lesson_user_online_status =1",
            //"require_admin_type =2",
            "m.account_role=2",
            "m.del_flag=0"
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $teacherid_arr);
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(1) lesson_count,sum(if(o.orderid>0,1,0)) order_count from %s l".
                                  " left join %s tss on l.lessonid = tss.lessonid".
                                  " left join %s o on l.lessonid = o.from_test_lesson_id".
                                  " left join %s tq on tq.require_id = tss.require_id" .
                                  " left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id ".
                                  " left join %s m on tq.cur_require_adminid = m.uid".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }



    public function get_tea_test_lesson_detail_info($teacherid,$time){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            "tss.set_lesson_time>".$time
        ];
        $sql = $this->gen_sql_new("select tss.success_flag,l.lessonid,m.account,l.subject,l.lesson_user_online_status from %s l"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s m on m.uid= tq.cur_require_adminid"
                                  ." where %s order by set_lesson_time asc limit 1",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_teacher_test_lesson_order_info($start_time,$end_time,$subject,$grade){
        $where_arr=[
            ["l.subject=%u",$subject,-1],
            ["l.grade=%u",$grade,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            // "tss.success_flag in (0,1)",
            // "l.stu_attend >0 ",
            // "l.tea_attend>0",
            "m.account_role=2",
            "m.del_flag=0",
            "t.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select distinct l.teacherid,t.realname "
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s o on o.from_test_lesson_id = tss.lessonid"
                                  ." left join %s tr on tr.require_id = tss.require_id"
                                  ." left join %s m on tr.cur_require_adminid=m.uid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
      return $this->main_get_list($sql);

    }

    public function get_lesson_money_info($lessonid){
        $where_arr = [
            ["lessonid=%u",$lessonid,0]
        ];
        $sql = $this->gen_sql_new("select l.lesson_start,l.lesson_end,l.teacherid,l.teacher_money_type,"
                                  ." money,type,l.already_lesson_count,l.teacher_type,l.lesson_count "
                                  ." from %s l"
                                  ." left join %s m on l.teacher_money_type=m.teacher_money_type "
                                  ." and l.level=m.level "
                                  ." and m.grade=(case when "
                                  ." l.competition_flag=1 then if(l.grade<200,203,303) "
                                  ." else l.grade"
                                  ." end )"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_money_type::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function set_lesson_level_up($teacherid,$level,$start,$end=0){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        $sql = $this->gen_sql_new("update %s set level=%u"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$level
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_tea_info($teacherid,$userid,$subject){
        $where_arr = [
            ["userid=%u",$userid,0],
            ["teacherid=%u",$teacherid,0],
            ["subject=%u",$subject,0],
            "lesson_type=0",
            "lesson_status=2"
            // ["lesson_start<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select lesson_start,subject,teacherid,userid,lesson_del_flag from %s where %s order by lesson_start",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_first_test_lesson_by_teacher($teacherid){
        $where_arr=[
            ["l.teacherid=%u",$teacherid,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "m.del_flag=0",
            "m.account_role=2"
        ];
        $sql = $this->gen_sql_new("select min(lesson_start) first_lesson_time "
                                  ." from %s l join %s tss on l.lessonid = tss.lessonid"
                                  ." join %s tq on  tss.require_id = tq.require_id"
                                  ." join %s m on  tq.cur_require_adminid =m.uid "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);


    }

    public function get_seller_and_ass_lesson_info($lessonid){
        $sql = $this->gen_sql_new("select l.userid,l.teacherid,l.subject,l.grade,t.realname,s.nick,tr.stu_request_test_lesson_demand,n.stu_score_info ,n.stu_character_info"
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s s on l.userid = s.userid"
                                  ." left join %s n on l.userid = n.userid"
                                  ." left join %s tr on (tr.userid = l.userid and tr.subject = l.subject)"
                                  ." where l.lessonid = %u ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $lessonid
        );
        return $this->main_get_row($sql);
    }


    public function get_actual_lesson_time($userlist, $start_time){
        $end_time  = strtotime('+3 months',$start_time);

        $where_arr = [
            ["userid in (%s)",$userlist],
            ["lesson_start > %u",$start_time ],
            ["lesson_end < %u",$end_time ],
            "lesson_type = 0",
            // "lesson_type = 1003",
            "confirm_flag in (0,1)",
        ];

        $sql = $this->gen_sql_new("select courseid, userid, assistantid, lesson_start, lesson_end "
                                  ." from %s "
                                  ."where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_week_actual_time($weeks, $userid, $year){
        $actual_time = [];
        foreach($weeks as $i=>$item){
            $item_arr = explode('-',$item);
            $start_time_tmp =str_replace('.','/',$year.'/'.$item_arr['0']);
            $start_time = strtotime($start_time_tmp);

            $end_time_tmp =str_replace('.','/',$year.'/'.$item_arr['1']);
            $end_time = strtotime($end_time_tmp)+86400;

            $where_arr = [
                ["userid = %s",$userid],
                ["lesson_start > %u",$start_time ],
                ["lesson_end < %u",$end_time ],
                "lesson_type = 0",
                "confirm_flag in (0,1)",
            ];

            $sql = $this->gen_sql_new("select lesson_start, lesson_end "
                                      ." from %s "
                                      ." where %s",
                                      self::DB_TABLE_NAME,
                                      $where_arr
            );



            $ret_actual_time_arr = $this->main_get_list($sql);
            $actual_time_item = 0;
            if(!empty($ret_actual_time_arr)){

                foreach($ret_actual_time_arr as $val){
                    if (isset($val['lesson_end'])) {
                        $temp = $val['lesson_end']-$val['lesson_start'];
                        $actual_time_item += $temp;
                    } else {
                        $actual_time_item = 0;
                    }
                }
            } else {
                $actual_time_item = 0;
            }
            $actual_time[$i] = $actual_time_item;
        }
        return $actual_time;
    }

    public function get_need_check_teacher_lesson($type,$start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_status=1",
        ];
        if($type==1){
            $where_arr[] = "lesson_type in (1001,1002)";
        }
        $sql = $this->gen_sql_new("select l.lessonid,l.courseid,l.lesson_num,l.lesson_type,l.teacherid,l.userid, "
                                  ." xmpp_server_name, c.current_server,  "
                                  ." l.lesson_start,l.lesson_end"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s c on c.courseid=l.courseid"
                                  ." where %s"
                                  ." and is_test_user=0"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_not_contact_lesson_num($start_time, $end_time){
        $time = time(null)-6000;

        $where_arr = [
            ["lesson_start<%u",$time,0],
            "lesson_type=2",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(
            " select admin_revisiterid as adminid , sum(n.last_contact_time>l.lesson_end) as lesson_num"
            ." from %s l "
            ." left join %s n on n.userid = l.userid "
            ." where %s "
            ." group by n.admin_revisiterid "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,$where_arr
        );
        // return $sql;
        return $this->main_get_list($sql);


    }

    public function get_lessonid_attend_list($start_time){
        $end_time = time();
        $where_arr = [
            "lesson_type=2",
            "lesson_del_flag=0",
            "stu_attend>0",
            "tea_attend>0",
            "lesson_user_online_status=2"
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select lessonid,lesson_start,subject from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_order_per_subject($start_time,$end_time){
        $where_arr = [
            "lesson_type=2",
            "lesson_del_flag=0",
            "lesson_user_online_status=1",
            "m.account_role=2",
            "m.del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.subject,count(*) lesson_count,sum(if(o.orderid >0,1,0)) order_count"
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s m on tr.cur_require_adminid = m.uid"
                                  ." left join %s o on (o.from_test_lesson_id = l.lessonid and contract_type =0)"
                                  ." where %s group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }

    public function check_is_first($lesson_start,$teacherid){
        $where_arr = [
            "lesson_type=2",
            "lesson_del_flag=0",
            "lesson_user_online_status=1",
            ["teacherid=%u",$teacherid,-1],
            "lesson_start<".$lesson_start
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_interview_teacher_first_lesson_info($start_time,$end_time){
        $where_arr = [
            "t.trial_lecture_is_pass=1",
            "t.realname <> '刘辉' and realname not like '%%alan%%' and realname not like '%%test%%' ",
            "lesson_type=2",
            "lesson_del_flag=0",
            "lesson_user_online_status=1",
            "t.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select m.uid,count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.subject) person_num "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tl on (tl.phone =t.phone and tl.status=1 and tl.subject = t.subject)"
                                  ." left join %s m on m.account = tl.account"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s and l.lesson_start = (select min(lesson_start) from %s where teacherid = l.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status=1) group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr,
                                  t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });

    }
    public function get_interview_teacher_first_lesson_info_subject($start_time,$end_time){
        $where_arr = [
            "t.trial_lecture_is_pass=1",
            "t.realname <> '刘辉' and realname not like '%%alan%%' and realname not like '%%test%%' ",
            "lesson_type=2",
            "lesson_del_flag=0",
            "lesson_user_online_status=1",
            "t.is_test_user=0",
            "m.del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.subject,count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.subject) person_num "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tl on (tl.phone =t.phone and tl.status=1 and tl.subject = t.subject)"
                                  ." left join %s m on m.account = tl.account"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s and l.lesson_start = (select min(lesson_start) from %s where teacherid = l.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status=1) group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr,
                                  t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }


    public function get_all_first_lesson_teacher_list($start_time,$end_time){
        $where_arr = [
            "t.trial_lecture_is_pass=1",
            "t.realname <> '刘辉' and realname not like '%%alan%%' and realname not like '%%test%%' ",
            "lesson_type=2",
            "lesson_del_flag=0",
            "lesson_user_online_status=1",
            "t.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,l.lesson_start,l.lesson_type,t.realname,l.lessonid,l.subject,r.add_time,m.uid "
                                  ." from %s l   "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s r on (r.teacherid=l.teacherid and r.type=1 and r.add_time = (select min(add_time) from %s where teacherid = l.teacherid and type=1))"
                                  ." left join %s tl on (tl.phone =t.phone and tl.status=1 and tl.subject = t.subject)"
                                  ." left join %s m on m.account = tl.account"
                                  ." where %s and l.lesson_start =(select min(lesson_start) from %s  where lesson_type=2 and lesson_del_flag = 0 and lesson_user_online_status =1 and teacherid = l.teacherid ) group by teacherid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr,
                                  t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_teacher_arr_lesson_order_info($start_time,$end_time,$tea_arr){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "lesson_user_online_status=1",
        ];

        $this->where_arr_teacherid($where_arr,"l.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select m.uid,count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.subject) person_num "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tl on (tl.phone =t.phone and tl.status=1 and tl.subject = t.subject)"
                                  ." left join %s m on m.account = tl.account"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s and l.lesson_start > (select min(lesson_start) from %s where teacherid = l.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status=1) group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr,
                                  t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });


    }

    public function get_teacher_arr_lesson_order_info_subject($start_time,$end_time,$tea_arr){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "lesson_user_online_status=1",
            "m.del_flag=0"
        ];

        $this->where_arr_teacherid($where_arr,"l.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select l.subject,count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.subject) person_num "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tl on (tl.phone =t.phone and tl.status=1 and tl.subject = t.subject)"
                                  ." left join %s m on m.account = tl.account"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s and l.lesson_start > (select min(lesson_start) from %s where teacherid = l.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status=1) group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr,
                                  t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });


    }


    public function get_teacher_arr_first_lesson_order_info($start_time,$end_time,$tea_arr){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "lesson_user_online_status=1",
        ];

        $this->where_arr_teacherid($where_arr,"l.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select m.uid,count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.subject) person_num "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tl on (tl.phone =t.phone and tl.status=1 and tl.subject = t.subject)"
                                  ." left join %s m on m.account = tl.account"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s and l.lesson_start = (select min(lesson_start) from %s where teacherid = l.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status=1) group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr,
                                  t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });


    }

    public function get_teacher_arr_first_lesson_order_info_subject($start_time,$end_time,$tea_arr){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "lesson_user_online_status=1",
            "m.del_flag=0"
        ];

        $this->where_arr_teacherid($where_arr,"l.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select l.subject,count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.subject) person_num "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tl on (tl.phone =t.phone and tl.status=1 and tl.subject = t.subject)"
                                  ." left join %s m on m.account = tl.account"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s and l.lesson_start = (select min(lesson_start) from %s where teacherid = l.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status=1) group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr,
                                  t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });


    }

    public function get_lesson_info_by_lessonid($lessonid){

        $where_arr = [
            ['l.lessonid=%d',$lessonid]
        ];

        $sql = $this->gen_sql_new(
            "select l.lessonid , require_adminid,account,l.userid,l.teacherid,l.assistantid,lesson_start,lesson_end,".
            " l.courseid,l.lesson_type,".
            " lesson_num,c.current_server,server_type ".
            " from %s l " .
            " left join %s c on c.courseid = l.courseid  ".
            " left join %s i on l.lessonid = i.st_arrange_lessonid ".
            " left join %s tss on l.lessonid = tss.lessonid ".
            " left join %s tr on tr.require_id = tss.require_id ".
            " left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id ".
            " left join %s m on t.require_adminid=m.uid ".
            " left join %s s on l.userid=s.userid".
            " where %s ".
            "order by l.lessonid desc",
            self::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            t_seller_student_info::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );


        // return $this->main_get_list($sql);
        return $this->main_get_list_as_page($sql,function($item){
            return $item["lessonid"];
        });



    }
    public function get_lesson_info_by_lessonid_new($field,$lessonid){
        if(count($lessonid)>=1){
            $where_arr[] = "a.lessonid in (".implode(',',$lessonid).')';
            $sql=$this->gen_sql_new(" select ".$field
                                ." from %s a"
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
            );
        }
        return $this->main_get_list($sql);
    }

    public function check_seller_plan_lesson($teacherid,$time){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            "tss.set_lesson_time>".$time,
            "l.lesson_type =2",
            "l.lesson_del_flag=0",
            "tss.success_flag <>2"
        ];
        $sql = $this->gen_sql_new("select 1 from %s l"
                                  ." join %s tss on l.lessonid = tss.lessonid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }



    public function get_lesson_conditions_by_lessonid($lessonid)
    {

        $where_arr=[
            ['lessonid=%d',$lessonid]
        ];

        $sql = $this->gen_sql_new(
            "select lesson_condition ".
            " from  %s " .
            " where  %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_test_person_num_list_by_subject( $start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "m.account_role=2",
            "m.del_flag=0"
        ];
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.subject) person_num,count(distinct l.lessonid) lesson_num,l.subject "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." where %s group by l.subject" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });
    }
    public function get_test_person_num_list_by_subject_grade( $start_time,$end_time,$subject_arr=-1,$grade_arr=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "m.account_role=2",
            "m.del_flag=0"
        ];
        if($subject_arr != -1){
            $where_arr[]= $this->where_get_in_str("l.subject", $subject_arr);
        }
        if($grade_arr != -1){
            $where_arr[]= $this->where_get_in_str("l.grade", $grade_arr);
        }
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_test_person_num_list_subject_other( $start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "tq.origin not like '%%扩课%%' and tq.origin not like '%%换老师%%'",
            "mm.account_role<>2",
            "mm.del_flag=0",
        ];

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order,l.subject"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on t.phone=m.phone"
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s group by l.subject" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }

    public function get_test_person_num_list_subject_grade_other( $start_time,$end_time,$subject_arr=-1,$grade_arr=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "tq.origin not like '%%扩课%%' and tq.origin not like '%%换老师%%'",
            "mm.account_role<>2",
            "mm.del_flag=0",
        ];

        if($subject_arr != -1){
            $where_arr[]= $this->where_get_in_str("l.subject", $subject_arr);
        }
        if($grade_arr != -1){
            $where_arr[]= $this->where_get_in_str("l.grade", $grade_arr);
        }

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count( l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on t.phone=m.phone"
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }



    public function get_kk_teacher_test_person_subject_list( $start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =1",
            "tq.origin like '%%扩课%%'"
        ];

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.subject,l.teacherid) kk_per_num,count( l.lessonid) lesson_num,l.subject "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s group by l.subject" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }

    public function get_kk_teacher_test_person_subject_grade_list( $start_time,$end_time,$subject_arr=-1,$grade_arr=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =1",
            "tq.origin like '%%扩课%%'"
        ];
        if($subject_arr != -1){
            $where_arr[]= $this->where_get_in_str("l.subject", $subject_arr);
        }
        if($grade_arr != -1){
            $where_arr[]= $this->where_get_in_str("l.grade", $grade_arr);
        }

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) kk_per_num,count( l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_change_teacher_test_person_subject_grade_list( $start_time,$end_time,$subject_arr=-1,$grade_arr=-1){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =1",
            "tq.origin like '%%换老师%%'"
        ];

        if($subject_arr != -1){
            $where_arr[]= $this->where_get_in_str("l.subject", $subject_arr);
        }
        if($grade_arr != -1){
            $where_arr[]= $this->where_get_in_str("l.grade", $grade_arr);
        }

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) kk_per_num,count( l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_change_teacher_test_person_subject_list( $start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "require_admin_type =1",
            "tq.origin like '%%换老师%%'"
        ];

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) kk_per_num,count( l.lessonid) lesson_num,l.subject "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." where %s group by l.subject" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });
    }

    public function get_lesson_info_by_adminid($adminid, $timestamp, $teacherid){
        $week = intval(date('w', $timestamp));
        if ($week==0){ //周日
            $week=7;
        }

        $time_given = strtotime(date('Y-m-d', $timestamp) . " 00:00:00");

        $ret_week['start'] = $time_given - ($week-1)  * 24 * 60 * 60;
        $ret_week['end'] = $ret_week['start'] + 7 * 24 * 60 * 60;

        $where_arr = [
            ['s.admin_revisiterid=%d',$adminid],
            'l.lesson_type=2',
            'l.lesson_del_flag=0'
        ];

        if ($teacherid != -1) {
            $where_arr[] = [
                'l.teacherid=%d',$teacherid
            ];
        }
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$ret_week['start'],$ret_week['end']);

        $sql = $this->gen_sql_new("select l.teacherid, l.lesson_count, l.userid, l.lesson_start, l.lesson_end ".
                                  " from %s l left join %s s on s.userid = l.userid".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_teacher_trial_count($lesson_start,$lesson_end,$teacherid,$teacher_money_type,$subject){
        $where_arr = [
            ["l.teacherid=%u",$teacherid,-1],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            ["t.subject=%u",$subject,-1],
            ["lesson_start>%u",$lesson_start,0],
            ["lesson_start<%u",$lesson_end,0],
            "l.lesson_user_online_status=1",
            "tss.success_flag in (0,1)",
            "m.del_flag=0",
            "m.account_role=2"
        ];
        $sql = $this->gen_sql_new("select t.teacherid,t.nick,t.teacher_money_type,t.subject, "
                                  ." count(distinct l.lessonid) as lesson_total "
                                  ." from %s l "
                                  ." left join %s t on l.teacherid=t.teacherid "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s m on m.uid= tr.cur_require_adminid"
                                  ." where %s "
                                  ." and lesson_status=2 "
                                  ." and lesson_type=2 "
                                  ." and lesson_del_flag=0 "
                                  ." and confirm_flag!=2 "
                                  ." group by teacherid "
                                  ." order by lesson_total desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function check_have_lesson_regular($userid,$teacherid,$lesson_count,$lesson_start){
        $sql = $this->gen_sql_new("select 1 from %s where userid = %u and teacherid = %u and lesson_start=%u and lesson_count=%u and lesson_type=0 and lesson_del_flag=0 and confirm_flag in(0,1)",
                                  self::DB_TABLE_NAME,
                                  $userid,
                                  $teacherid,
                                  $lesson_start,
                                  $lesson_count
        );

        return $this->main_get_value($sql);
    }

    public function save_tea_pic_url($lessonid, $file_name_origi_str){
        $sql = $this->gen_sql_new("update %s tl ".
                                  "set tl.tea_cw_pic = '%s',tl.tea_cw_pic_flag=1 where tl.lessonid=%d",
                                  self::DB_TABLE_NAME,
                                  $file_name_origi_str,
                                  $lessonid
        );
        return $this->main_update($sql);
    }

    public function get_stu_normal_lesson_num($start_time,$end_time,$user_list){
        $where_arr = [
            "lesson_type <>2",
            "lesson_del_flag=0",
            "confirm_flag in(0,1)"
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $where_arr[]= $this->where_get_in_str("userid", $user_list);
        $sql = $this->gen_sql_new("select count(1) num,userid from %s where %s group by userid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["userid"];
        });
    }

    public function get_first_lesson($userid){
        $where_arr=[
            ["userid=%u",$userid,0],
        ];
        $sql = $this->gen_sql_new("select lesson_start"
                                  ." from %s "
                                  ." where %s"
                                  ." and lesson_type in (0,1,3)"
                                  ." and lesson_status=2"
                                  ." and confirm_flag!=2"
                                  ." order by lesson_start asc"
                                  ." limit 1"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function check_comment_status($lessonid) {
        $sql = $this->gen_sql_new("select t.tea_rate_time from %s t where t.lessonid = %d",
                                  self::DB_TABLE_NAME, $lessonid);
        return $this->main_get_value($sql);
    }

    public function get_common_stu_performance($lessonid) {
        $sql = $this->gen_sql_new("select t.stu_performance from %s t where t.lessonid = %d and t.lesson_del_flag = 0",
                                  self::DB_TABLE_NAME,
                                  $lessonid
        );
        return $this->main_get_value($sql);
    }

    public function check_train_lesson($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            "train_type=4",
            "lesson_start=0"
        ];
        $sql = $this->gen_sql_new("select count(1) "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function check_train_lesson_new($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            "train_type=4",
            "lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select count(1) "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_test_person_num_list_subject_other_jx( $start_time,$end_time,$require_adminid_list){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            "(l.lesson_user_online_status in (0,1) or f.flow_status = 2)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
        ];

        $this->where_arr_adminid_in_list($where_arr,"ts.require_adminid",$require_adminid_list);


        $sql = $this->gen_sql_new("select tq.origin as check_value "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order "
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on t.phone=m.phone"
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." left join %s f  on f.flow_type=2003 and l.lessonid= f.from_key_int  " //特殊申请
                                  ." where %s and tq.accept_flag=1 and ts.require_admin_type=2 group by check_value" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_flow::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_not_free_lesson_list($start_time,$end_time,$teacherid){
        $where_arr = [
            ["lesson_start>=%u",$start_time,0],
            ["lesson_start<=%u",$end_time,0],
            ["teacherid in (%s)",$teacherid,0],
            "lesson_type !=4001",
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("select lesson_start,lesson_end,teacherid"
                                  ." from %s force index(lesson_type_and_start) "
                                  ." where %s"
                                  ." order by lesson_start asc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_teacherid_for_free_time_by_lessonid($lesson_start,$lesson_end,$teacherid_str){
        $where_arr = [
            ["lesson_start>%u",$lesson_start,0],
            ["lesson_start<%u",$lesson_end,0],
            ["teacherid in (%s)",$teacherid_str,""],
            "lesson_type!=4001",
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("select teacherid"
                                  ." from %s "
                                  ." where %s"
                                  ." group by teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }

    public function get_stu_all_teacher($page_info,$assistantid=-1)
    {
        if($assistantid < 0){
           $where_arr = [
                "s.assistantid>0",
                "c.course_type  =0",
                "c.course_status =0",
                "c.teacherid!=0",
                "t.teacherid!=0",
                "s.is_test_user=0"
            ];
        }else{
            $where_arr = [
            ["s.assistantid = %u",$assistantid,-1],
            "c.course_type  =0",
            "c.course_status =0",
            "c.teacherid!=0",
            "t.teacherid!=0",
            "s.is_test_user=0"
        ];
        }
        $sql = $this->gen_sql_new("select s.assistantid,c.teacherid,t.phone,t.grade_part_ex ,t.subject"
                                  ." from %s s"
                                  ." left join %s c on s.userid = c.userid "
                                  ." left join %s t on c.teacherid = t.teacherid "
                                  ." where %s group by c.teacherid"
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,10,true);
        //return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_total_consume($start_time,$end_time){
        $where_arr = [
            ['lesson_start>%u',$start_time,-1],
            ['lesson_start<%u',$end_time,-1],
            "lesson_user_online_status = 1 ",
            "lesson_type IN (0, 1, 3) ",
            "l.lesson_del_flag=0",
            "(s.is_test_user = 0 or s.is_test_user is null)"
        ];
        $sql = $this->gen_sql_new("select sum(if(l.confirm_flag in (0,1,3,4), l.lesson_count,0) )as total_consume, count(distinct(l.userid)) as total_student ".
                                  "from %s l ".
                                  "left join %s s on s.userid = l.userid ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_row($sql);
    }

    public function get_total_consume_by_grade($start_time,$end_time){
        $where_arr = [
            ['lesson_start>%u',$start_time,-1],
            ['lesson_start<%u',$end_time,-1],
            "lesson_user_online_status <2 ",
            "lesson_type IN (0, 1, 3) ",
            "(s.is_test_user = 0 or s.is_test_user is null)",
            "l.lesson_status>0",
            "l.confirm_flag in (0,1,3,4)",
            "l.lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select sum( l.lesson_count) as total_consume,".
                                  " count(distinct(l.userid)) as total_student ,l.grade".
                                  " from %s l ".
                                  " left join %s s on s.userid = l.userid ".
                                  " where %s group by l.grade",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql,function($item){
            return $item["grade"];
        });
    }

    public function get_leave_num($start_time,$end_time){
        $where_arr = [
            ['lesson_start>%u',$start_time,-1],
            ['lesson_start<%u',$end_time,-1],
            " lesson_cancel_reason_type in (3,4,11,12) ",
        ];
        $sql = $this->gen_sql_new("select lesson_cancel_reason_type,  sum(lesson_count) as num ".
                                  "from %s  ".
                                  " where %s group by   lesson_cancel_reason_type order by   lesson_cancel_reason_type ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql);

    }
    public function get_total_lesson($start_time,$end_time){
        $where_arr = [
            ['lesson_start>%u',$start_time,-1],
            ['lesson_start<%u',$end_time,-1],
            "lesson_type IN (0, 1, 3) ",
            "(s.is_test_user = 0 or s.is_test_user is null)",
            "l.lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select  count(courseid) as total_plan, "
                                  ."sum(if( lesson_user_online_status = 1,1 ,0))as student_arrive ".
                                  "from %s l ".
                                  "left join %s s on s.userid = l.userid".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_row($sql);
    }

    public function get_total_income($start_time,$end_time){
        $where_arr = [
            ['lesson_start>=%u',$start_time,-1],
            ['lesson_start<%u',$end_time,-1],
            "lesson_type IN (0, 1, 3) ",
            "(s.is_test_user = 0 or s.is_test_user is null)",
            "l.confirm_flag in (0,1,3) ",
            "l.lesson_user_online_status=1 ",
            "l.lesson_del_flag =0"
        ];
        $sql = $this->gen_sql_new("select  sum(o.price) as total_income ".
                                  "from %s l ".
                                  "left join %s o on o.lessonid = l.lessonid ".
                                  "left join %s s on s.userid = l.userid ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_order_lesson_list::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_value($sql);
    }

    // t_teacher_info add_time have_test_lesson_flag
    public function get_imit_audi_sched_count($end_time, $teacherid)
    {
        $whereArr = [
            ['lesson_start<%u',$end_time,0],
            ['teacherid=%u',$teacherid,0],
            "lesson_type=1100",
            "train_type=4"
        ];
        $sql = $this->gen_sql_new("select teacherid from %s  where %s ",
                                  self::DB_TABLE_NAME,
                                  $whereArr
        );
        return $this->main_get_value($sql);
            //return $this->main_get_list($sql, function( $item) {
            //   return $item['teacherid'];
            //});
    }

    // 上课
    public function get_attend_lesson_count($start_time, $end_time) {
         $whereArr = [
             //["lesson_start>%u",$start_time,0],
             //["lesson_start<%u",$end_time,0],
            //["l.teacherid=%u",$teacherid,0],
            "tea_attend>0"
         ];


        $sql = $this->gen_sql_new("select l.teacherid,l.lesson_start"
                                  ." from %s l left join %s lo on l.lessonid=lo.lessonid "
                                  ." where %s  group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_opt_log::DB_TABLE_NAME,
                                  $whereArr
        );
        return $this->main_get_row($sql, function( $item) {
            return $item['teacherid'];
        });
        //return $this->get_handle_other_subject($info, $res);
    }

    public function get_adopt_lesson_count($start_time, $end_time,$subject) {
        $whereArr = [
            ["tf.simul_test_lesson_pass_time>%u", $start_time, 0],
            ["tf.simul_test_lesson_pass_time<%u", $end_time, 0],
            ["tf.subject=%u",$subject,0],
            //"train_through_new=1",
            //"t.is_test_user=0",
            //"t.train_type=4"
        ];
        //$table = t_teacher_info::DB_TABLE_NAME;
        //$sql = "select count(*) from %s where %s";
        //$res = $this->get_three_maj_sub($sql, $whereArr, $table);
        if ($subject <= 3) {
            $query = " sum(if(substring(tf.grade,1,1)=1,1,0)) primary_num, "
                      ." sum(if(substring(tf.grade,1,1)=2,1,0)) middle_num,"
                      ."sum(if(substring(tf.grade,1,1)=3,1,0)) senior_num";
        } else {
            $query = " count(*) sum";
        }


        $sql = $this->gen_sql_new("select %s from %s t left join %s tf on t.teacherid=tf.teacherid where %s ",
                                  $query,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_flow::DB_TABLE_NAME,
                                  $whereArr
        );

        return $this->main_get_row($sql);
    }

    public function get_one_subject_count($sql, $whereArr, $table, $where, $subject, $grade) {
        $where = array_merge($whereArr, $where);
        $sql = $this->gen_sql_new($sql,
                                  $table,
                                  $where
        );
        $info = $this->main_get_value($sql);
        $res['subject'] = $subject;
        $res['grade'] = $grade;
        $res['sum'] = $info;
        return $res;
    }

    public function get_imit_audi_sched_type_count($start_time, $end_time){
        $whereArr = [
            ["l.operate_time<%u",$start_time,0],
            ["l.operate_time>%u",$end_time,0],
            "l.lesson_type=1100",
            "l.train_type=4"
        ];

        $sql = $this->gen_sql_new("select t.identity,count(*) as sum from %s l left join %s t on l.teacherid=t.teacherid where %s group by identity",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $whereArr
        );
        $info = $this->main_get_list($sql);

        return $this->get_handle_identity_count($info);
    }

    public function get_subject_for_teacherid($teacherid) {
        $where = [['teacherid=%u',$teacherid,0]];

        $sql = $this->gen_sql_new("select subject,grade from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where
        );
        return $this->main_get_row($sql);
    }

    public function get_subject_transfer($start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            " (tss.success_flag in (0,1) ",
            " l.lesson_user_online_status =1) ",
            " lesson_type = 2 ",
            " lesson_del_flag = 0 ",
            " mm.account_role=2 ",
            " mm.del_flag=0 ",
            " t.is_test_user=0 ",
            " m.account_role=5 ",
            " m.del_flag=0 ",
            " l.subject in (1,2,3)"
        ];
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) have_order,l.subject "
                                ."from %s l  "
                                ." left join %s tss on tss.lessonid = l.lessonid "
                                ." left join %s tq on tq.require_id = tss.require_id "
                                ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id  "
                                ." left join %s c on  (l.userid = c.userid  and l.teacherid = c.teacherid  and l.subject = c.subject  and c.course_type=0 and c.courseid >0)  "
                                ." left join %s t on l.teacherid=t.teacherid "
                                ." left join %s m on t.phone=m.phone "
                                ." left join %s mm on tq.cur_require_adminid = mm.uid "
                                ." where  %s group by l.subject order by l.subject",
                                self::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                t_test_lesson_subject_require::DB_TABLE_NAME,
                                t_test_lesson_subject::DB_TABLE_NAME,
                                t_course_order::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql,function($item){
            return $item['subject'];
        });
    }

    public function get_subject_success($start_time,$end_time){
        $where_arr = [
            ["l.lesson_start>=%u",$start_time,-1],
            ["l.lesson_start<%u",$end_time,-1],
            "l.lesson_type = 2 ",
            "l.lesson_del_flag = 0 ",
            "tss.success_flag in (0,1) ",
            "l.lesson_user_online_status =1 ",
            "t.trial_lecture_is_pass =1  ",
            "t.train_through_new =1 ",
            "m.account_role=5 ",
            "m.del_flag=0 ",
            "l.subject in (1,2,3) "
        ];
        $sql = $this->gen_sql_new("select count(distinct l.lessonid) success_lesson,l.subject "
                                ." from %s l  "
                                ." left join %s tss on l.lessonid = tss.lessonid"
                                ." left join %s c on  (l.userid = c.userid  and l.teacherid = c.teacherid  and l.subject = c.subject  and c.course_type=0 and c.courseid >0)  "
                                ." left join %s t on l.teacherid=t.teacherid "
                                ." left join %s m on m.phone=t.phone "
                                ." where  %s group by l.subject order by l.subject",
                                self::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                t_course_order::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr );
        return $this->main_get_list($sql,function($item){
            return $item['subject'];
        });
    }
    //@desn:获取已试听成功为结点的漏斗数据
    public function get_funnel_data( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        if($field_name == 'grade')
            $field_name="si.grade";
        elseif($field_name == 'origin')
            $field_name = 'si.origin';
        elseif($field_name == 'subject')
            $field_name = 'tls.subject';

        $where_arr=[
            ["si.origin like '%%%s%%' ",$origin,""],
            'si.is_test_user = 0',
            'li.lesson_type = 2',
            'tlssl.success_flag in (0,1 )',
            'tls.require_admin_type = 2',
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"ssn.tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"si.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"ssn.first_seller_adminid",$adminid_list);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value,count(li.lessonid) succ_test_lesson_count,".
            "count(distinct(li.userid)) distinct_succ_count,".
            " sum(if((oi.contract_type = 0 and contract_status > 0 ),1,0)) order_count,".
            " round(sum(if((oi.contract_type = 0 and contract_status > 0 ),oi.price,0))/100) order_all_money".
            " from %s li ".
            " left join %s oi on li.userid = oi.userid".
            " left join %s si on li.userid = si.userid ".
            " left join %s ssn on li.userid = ssn.userid ".
            " left join %s tlssl on li.lessonid = tlssl.lessonid ".
            " left join %s tls on li.userid = tls.userid ".
            " where %s group by check_value",
            self::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });

    }
    //@desn:获取不重复订单数
    public function get_distinct_order_info( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        if($field_name == 'grade')
            $field_name="si.grade";
        elseif($field_name == 'origin')
            $field_name = 'si.origin';
        elseif($field_name == 'subject')
            $field_name = 'tls.subject';


        $where_arr=[
            ["si.origin like '%%%s%%' ",$origin,""],
            'si.is_test_user = 0',
            'li.lesson_type = 2',
            'tlssl.success_flag in (0,1 )',
            'oi.contract_type = 0',
            'oi.contract_status > 0',
            'tls.require_admin_type = 2'
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"ssn.tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"si.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"ssn.first_seller_adminid",$adminid_list);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value,count(distinct(oi.userid)) as user_count".
            " from %s li ".
            " left join %s oi on li.userid = oi.userid".
            " left join %s si on li.userid = si.userid ".
            " left join %s ssn on li.userid = ssn.userid ".
            " left join %s tlssl on li.lessonid = tlssl.lessonid ".
            " left join %s tls on li.userid = tls.userid ".
            " where %s group by check_value",
            self::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );


        return $this->main_get_list($sql);
    }


    public function get_teacher_warn_info($start_time, $end_time) {
        $where_arr = [
            ['lesson_start>=%u', $start_time, 0],
            ['lesson_start<%u', $end_time, 0],
            'confirm_flag=2',
            "lesson_cancel_reason_type in (21,2,12) "
        ];
        $sql = $this->gen_sql_new("select teacherid,lessonid,lesson_start,lesson_cancel_reason_type type "
                                  ."from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取公开课字数
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    public function get_public_class_num($start_time,$end_time){
        $where_arr = [
            'li.lesson_type in (1001,1002,1003)'
        ];
        $where_arr[] = '(li.lesson_num <> 1 and co.lesson_total <> 0) ';
        $this->where_arr_add_time_range($where_arr, 'li.lesson_start', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select count(li.courseid) as public_class_num from %s li '.
            'left join %s co on li.courseid = co.courseid '.
            'where %s',
            t_lesson_info::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function reset_lesson_enable_video($courseid,$enable_video,$lesson_status=1){
        $where_arr = [
            ["courseid=%u",$courseid],
            ["lesson_status=%u",$lesson_status],
        ];
        $sql = $this->gen_sql_new("update %s set enable_video=%u "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$enable_video
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_student_list($teacherid,$start,$end){
        $where_arr=[
            ["teacherid=%u",$teacherid,0],
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        $sql=$this->gen_sql_new("select s.userid,if(s.nick!='',s.nick,s.realname) as nick "
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid"
                                ." where %s"
                                ." and lesson_type<1000"
                                ." and confirm_flag<2"
                                ." and lesson_del_flag=0"
                                ." group by s.userid"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }


}
