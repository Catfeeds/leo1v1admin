<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_lecture_appointment_info extends \App\Models\Zgen\z_t_teacher_lecture_appointment_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_simple_info($phone){
        $where_arr=[
            ["phone='%s'",$phone,""],
        ];
        $sql=$this->gen_sql_new("select id,email,school,textbook,name,teacher_type,full_time,trans_subject_ex"
                                ." from %s "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_appointment_id_by_phone($phone){
        $where_arr = [
            ["phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select id"
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_id_by_phone($phone){
        $where_arr = [
            ["phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select id"
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }


    public function reset_teacher_identity_by_phone($phone,$identity){
        $where_arr = [
            ["phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("update %s set teacher_type=%u"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$identity
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }
    public function get_lecture_count_info($start_time,$end_time,$phone){
        $where_arr = [
            ["answer_begin_time>=%u", $start_time, -1 ],
            ["answer_begin_time<=%u", $end_time, -1 ],
            ["reference like '%%%s%%'", $phone, "" ],
        ];

        $sql = $this->gen_sql_new("select if(l.status is null,'-2',l.status) as status"
                                  ." from %s la"
                                  ." left join %s l on l.phone=la.phone "
                                  ." where %s "
                                  ." and not exists ("
                                  ." select 1 from %s where "
                                  ." phone=l.phone and add_time>l.add_time"
                                  ." )"
                                  ." group by la.phone "
                                  ." order by answer_begin_time desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_all_info($page_num,$start_time,$end_time,$teacherid,$lecture_appointment_status,
                                 $user_name,$status,$adminid=-1,$record_status=-1,$grade=-1,$subject=-1,
                                 $teacher_ref_type,$interview_type=-1,$have_wx=-1, $lecture_revisit_type=-1,
                                 $full_time=-1, $lecture_revisit_type_new=-1,$fulltime_teacher_type=-1,
                                 $accept_adminid=-1,$second_train_status=-1,$teacher_pass_type=-1,
                                 $opt_date_str=1,$gender=-1,$is_test_user=-1
    ){
        $where_arr = [
            ["lecture_appointment_status=%u", $lecture_appointment_status, -1 ],
            ["t.teacherid=%u", $teacherid, -1 ],
            ["tt.gender=%u", $gender, -1 ],
            ["la.accept_adminid=%u", $adminid, -1 ],
            ["la.full_time=%u", $full_time, -1 ],
            ["la.accept_adminid=%u", $accept_adminid, -1 ],
            ["tr2.trial_train_status=%u", $second_train_status, -1 ],
            // ["tt.is_test_user=%u", $is_test_user, -1 ],
        ];
        if($is_test_user ==0){
            $where_arr[]="(tt.is_test_user==0 or tt.is_test_user is null)";
        }elseif($is_test_user==1){
            $where_arr[]="tt.is_test_user=1";
        }
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        if($lecture_revisit_type==E\Electure_revisit_type::V_5){
            $where_arr[] = "(la.lecture_revisit_type=5 or ta.lesson_start>0)";
        }else{
            $where_arr[] = ["la.lecture_revisit_type=%u", $lecture_revisit_type, -1 ];
        }
        if($lecture_revisit_type_new==-2){
            $where_arr[] = "ta.lesson_start>0";
        }else{
            $where_arr[] = ["la.lecture_revisit_type=%u", $lecture_revisit_type_new, -1 ];
        }

        if($interview_type==0){
            $where_arr[] = "l.status is null and (ta.lesson_start is null or ta.lesson_start=0)";
        }elseif($interview_type==1){
            $where_arr[] = "l.status is not null ";
        }elseif($interview_type==2){
            $where_arr[] = "ta.lesson_start>0";
        }

        if($status==0){
            $where_arr[] ="((l.status=0 and ((tr.id is null and ta.lesson_start>0) or (ta.lesson_start is null or ta.lesson_start=0))) or ((l.status is null or l.status=0) && (tr.id is null and ta.lesson_start>0)) )";
        }else if($status==1){
            $where_arr[] ="(l.status=1 or tr.trial_train_status =1)";
        }else if($status==2){
            $where_arr[] ="((l.status=2 and (tr.trial_train_status =0 or (ta.lesson_start is null or ta.lesson_start=0))) or ((l.status=2 or l.status is null) and tr.trial_train_status =0))";
        }else{
            $where_arr[]=["l.status=%u", $status, -1 ];
        }

        $record_sql = $this->gen_sql_new("(select 1 from %s where la.phone=phone)",t_lecture_revisit_info::DB_TABLE_NAME);
        if($record_status==0){
            $record_str=" not exists ".$record_sql;
        }elseif($record_status==1){
            $record_str=" exists ".$record_sql;
        }else{
            $record_str=" true ";
        }

        if($grade!=-1){
            $grade_str   = E\Egrade::get_desc($grade);
            $grade_range = \App\Helper\Utils::change_grade_to_grade_range($grade);
            $where_arr[] = "(la.grade_ex='".$grade."' "
                         ." or la.grade_ex like '%%".$grade_str."%%' "
                         ." or (la.grade_end<=".$grade_range['grade_end']
                         ." and la.grade_start>=".$grade_range['grade_start']."))";
        }

        if($subject!=-1){
            $subject_str = E\Esubject::get_desc($subject);
            $where_arr[] = "(la.subject_ex='".$subject."' "
                         ." or la.subject_ex like '%%".$subject_str."%%')";
        }

        if($have_wx==0){
            $where_arr[] ="(tt.wx_openid = '' or tt.wx_openid is null )";
        }elseif($have_wx==1){
            $where_arr[] ="tt.wx_openid <> '' and tt.wx_openid is not null";
        }
        $where_arr[] = $this->where_get_in_str_query("t.teacher_ref_type", $teacher_ref_type );
        if($teacher_pass_type==1){
            $where_arr[] = "tt.train_through_new=1";
        }else{
            $where_arr[] =["la.teacher_pass_type=%u", $teacher_pass_type, -1 ];
        }
        if ($user_name) {
            $user_name = $this->ensql($user_name);
            $where_arr = [
                "(la.name like '%%".$user_name."%%' "
                ." or la.school like '%%".$user_name."%%' "
                ." or la.phone like '".$user_name."%%' "
                ." or la.qq like '%%".$user_name."%%' "
                ." or textbook like '%%".$user_name."%%' "
                ." or la.teacher_type like '%%".$user_name."%%')"
            ];
        }

        $sql = $this->gen_sql_new("select la.id,la.name,la.phone,la.email,la.textbook,la.school,tt.train_through_new_time,tt.age,"
                                  ." la.grade_ex,la.subject_ex,la.trans_grade_ex,la.trans_subject_ex,grade_1v1,trans_grade_1v1,"
                                  ." la.teacher_type,la.custom,la.self_introduction_experience,la.full_time,"
                                  ." la.lecture_appointment_status,la.reference,la.answer_begin_time,la.answer_end_time,"
                                  ." if(l.status is null,'-2',l.status) as status,ta.lesson_start,"
                                  ." if(ta.lesson_start >0,4,la.lecture_revisit_type) lecture_revisit_type,"
                                  ." if(tr.trial_train_status is null,-2,tr.trial_train_status) trial_train_status,"
                                  ." l.subject,l.grade,l.add_time,la.acc,l.reason ,tr.record_info ,ta.lessonid train_lessonid,"
                                  ." ta.teacherid interviewer_teacherid,tt.is_test_user,"
                                  ." if(t.nick='',t.realname,t.nick) as reference_name,reference,t.teacherid,m.account, "
                                  ." m.name as zs_name,"
                                  ." tt.teacherid train_teacherid,la.qq,tt.wx_openid,tt.user_agent,la.hand_flag,tt.gender,"
                                  ." tr2.trial_train_status as full_status,tr2.record_info as full_record_info,"
                                  ." la.teacher_pass_type,la.no_pass_reason "
                                  ." from %s la"
                                  ." left join %s l on l.phone=la.phone and not exists ("
                                  ." select 1 from %s ll where ll.phone=l.phone and l.add_time<ll.add_time)"
                                  ." left join %s t on t.phone=la.reference"  //推荐人
                                  ." left join %s m on la.accept_adminid=m.uid"
                                  ." left join %s tt on la.phone = tt.phone" //老师自己
                                  ." left join %s ta on ta.userid = tt.teacherid and ta.train_type=5 and ta.lesson_type =1100 and ta.lesson_del_flag=0 and ta.confirm_flag <2 and not exists (select 1 from %s taa where taa.userid=ta.userid and taa.train_type=5 and taa.lesson_type=1100 and ta.lesson_start<taa.lesson_start and taa.lesson_del_flag=0 and taa.confirm_flag <2)"
                                  ." left join %s tr on tr.train_lessonid = ta.lessonid and tr.type=10"
                                  ." left join %s tr2 on tt.teacherid = tr2.teacherid and tr2.type=12"
                                  ." where %s "
                                  ." and %s"
                                  ." group by la.phone"
                                  ." order by answer_begin_time desc,l.add_time,tr.add_time desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$record_str
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function add_all_info($answer_begin_time,$answer_end_time,$custom,$name,$phone,
                                 $email,$grade_ex,$subject_ex,$textbook,$school,
                                 $teacher_type,$self_introduction_experience,$reference,$acc="",$lecture_appointment_status=0,
                                 $lecture_appointment_origin=0
    ){
        $ret = $this-> row_insert([
                "answer_begin_time"            => $answer_begin_time,
                "answer_end_time"              => $answer_end_time,
                "custom"                       => $custom,
                "name"                         => $name,
                "phone"                        => $phone,
                "email"                        => $email,
                "grade_ex"                     => $grade_ex,
                "subject_ex"                   => $subject_ex,
                "textbook"                     => $textbook,
                "school"                       => $school,
                "teacher_type "                => $teacher_type,
                "self_introduction_experience" => $self_introduction_experience,
                "reference "                   => $reference,
                "lecture_appointment_origin"   => $lecture_appointment_origin,
                "lecture_appointment_status"   => $lecture_appointment_status,
                "acc"                          => $acc
        ]);

        if ($ret ==1 ) {
            return $this->get_last_insertid();
        } else {
            return false;
        }
    }

    public function update_teacher_lecture_appointment_info(){
        $sql = $this->gen_sql_new("update %s set lecture_appointment_origin=1"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function check_is_exist($answer_begin_time=0,$phone){
        $where_arr = [
            ["phone='%s'",$phone,""],
            ["answer_begin_time=%u",$answer_begin_time,0],
        ];

        $sql = $this->gen_sql_new("select id from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_reference_list_new(){
        $sql=$this->gen_sql_new("select distinct(reference),if(t.nick='',t.realname,t.nick) as name "
                                ." from %s tl"
                                ." left join %s t on tl.phone=t.phone"
                                ." where reference!=''"
                                ." and length(reference)=11 having(name is not null)"
                                ,self::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_id_list_desc_limit_ten($start_time){
        $where_arr=[
            " (la.accept_adminid =0 or la.accept_adminid is null)",
            " la.answer_begin_time >=".$start_time
        ];
        $sql=$this->gen_sql_new("select distinct la.id from %s la left join %s l on l.phone=la.phone where %s order by answer_begin_time desc limit 1 ",
                                self::DB_TABLE_NAME,
                                t_teacher_lecture_info::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function update_accept_adminid_and_time($adminid,$start_time,$end_time){
        $where_arr=[
            ["answer_begin_time >=%u",$start_time,-1],
            ["answer_begin_time <=%u",$end_time,-1]
        ];
        $sql=$this->gen_sql_new("update %s SET accept_adminid=%u,accept_time=%u where %s",
                                self::DB_TABLE_NAME,
                                $adminid,
                                time(),
                                $where_arr
        );
        return $this->main_update($sql);
    }

    public function tongji_teacher_lecture_appoiment_info_by_accept_adminid($start_time,$end_time){
        $where_arr=[
            ["answer_begin_time >=%u",$start_time,-1],
            ["answer_begin_time <=%u",$end_time,-1],
            // "la.accept_adminid>0"
        ];
        $sql= $this->gen_sql_new("select count(*) all_count,accept_adminid,m.account,sum(if(lecture_revisit_type=0,1,0)) no_call_count "
                                 ." from %s la "
                                 ." left join %s m on la.accept_adminid = m.uid"
                                 ." where %s group by accept_adminid",
                                 self::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }
    public function tongji_no_call_count_by_accept_adminid(){
        $sql= $this->gen_sql_new("select count(*) all_count,accept_adminid,m.account,sum(if(lecture_revisit_type=0,1,0)) no_call_count "
                                 ." from %s la "
                                 ." left join %s m on la.accept_adminid = m.uid"
                                 ." group by accept_adminid",
                                 self::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }


    public function get_lecture_video_info_by_accept_adminid($start_time,$end_time,$accept_adminid){
        $where_arr=[
            ["answer_begin_time >=%u",$start_time,-1],
            ["answer_begin_time <=%u",$end_time,-1],
            ["accept_adminid=%u",$accept_adminid,-1],
            "l.phone >0"
        ];
        $sql= $this->gen_sql_new("select count(*) all_count,l.subject from %s la "
                                 ." left join %s l on la.phone = l.phone"
                                 ." where %s group by l.subject",
                                 self::DB_TABLE_NAME,
                                 t_teacher_lecture_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lecture_suc_info_by_accept_adminid($start_time,$end_time,$accept_adminid){
        $where_arr=[
            ["answer_begin_time >=%u",$start_time,-1],
            ["answer_begin_time <=%u",$end_time,-1],
            ["accept_adminid=%u",$accept_adminid,-1],
            "l.phone >0",
            "l.status=1"
        ];
        $sql= $this->gen_sql_new("select count(*) all_count,l.subject from %s la "
                                 ." left join %s l on la.phone = l.phone"
                                 ." where %s group by l.subject",
                                 self::DB_TABLE_NAME,
                                 t_teacher_lecture_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_reference_by_phone($phone){
        $sql = $this->gen_sql_new("select reference from %s where phone='%s'"
                                  ,self::DB_TABLE_NAME
                                  ,$phone
        );
        return $this->main_get_value($sql);
    }

    public function get_appointment_info_by_id($appointment_id){
        $where_arr = [
            ["id=%u",$appointment_id,0]
        ];
        $sql = $this->gen_sql_new("select name,reference,grade_start,grade_end,not_grade,"
                                  ." trans_grade,trans_grade_start,trans_grade_end,phone"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function check_has_trans($id){
        $where_arr=[
            ["id=%u",$id,0]
        ];
        $sql = $this->gen_sql_new("select trans_grade"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);

    }

    public function tongji_teacher_appoinment_lecture_total(){
        $sql = $this->gen_sql_new("select count(distinct phone) from %s ",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }
    public function tongji_teacher_appoinment_lecture_info($time){
        $where_arr=[
        ];

        if(is_array($time)){
            $start_time = $time['start_time'];
            $end_time   = $time['end_time'];
            $this->where_arr_add_time_range($where_arr,"al.answer_begin_time",$start_time,$end_time);

        }else{
        }

        $time_begin = strtotime(date("2017-01-05")); //
        $time_str = "l.confirm_time>$time_begin";


        $sql = $this->gen_sql_new("select count(distinct al.phone) app_total,count(distinct l.phone) lec_total,count(distinct t.teacherid) tea_total,count(distinct tt.teacherid) tran_total "
                                  ." from %s al left join %s l on al.phone = l.phone and %s"
                                  ." left join %s t on l.phone = t.phone and l.status=1 and t.is_test_user=0 and t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%' "
                                  ." left join %s tt on tt.teacherid = t.teacherid and tt.train_through_new=1"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $time_str,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_teacher_appoinment_interview_info($start_time,$end_time){
        $where_arr=[
            "al.name not like '%%不要审核%%' and  al.name not like '%%gavan%%' and al.name not like '%%阿蓝%%'"
        ];
        $this->where_arr_add_time_range($where_arr,"al.answer_begin_time",$start_time,$end_time);


        $sql = $this->gen_sql_new("select distinct al.phone,al.answer_begin_time,tl.add_time,l.lesson_start "
                                  ." from %s al "
                                  ." left join %s tl on al.phone = tl.phone and tl.status <>4 and tl.is_test_flag=0 and "
                                  ." not exists (select 1 from %s where phone = tl.phone and status <>4 and "
                                  ."is_test_flag=0 and add_time<tl.add_time )"
                                  ." left join %s t on al.phone = t.phone and t.is_test_user=0"
                                  ." left join %s l on l.lesson_type=1100 and l.train_type=5 and l.lesson_del_flag=0 "
                                  ."and l.userid = t.teacherid and l.lesson_start>0 and not exists(select 1 from %s "
                                  ."where lesson_type=1100 and lesson_start>0 "
                                  ." and train_type=5 and lesson_del_flag=0 and userid=l.userid and lesson_start<l.lesson_start)"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_appoinment_interview_pass_info($start_time,$end_time){
        $where_arr=[
            "al.name not like '%%不要审核%%' and  al.name not like '%%gavan%%' and al.name not like '%%阿蓝%%'"
        ];
        $this->where_arr_add_time_range($where_arr,"al.answer_begin_time",$start_time,$end_time);


        $sql = $this->gen_sql_new("select distinct al.phone,tl.add_time,tl.confirm_time,l.lesson_start,"
                                  ."tr.add_time one_add_time,ll.lesson_start train_add_time,"
                                  ."lll.lesson_start trail_time,t.train_through_new,t.train_through_new_time, "
                                  ." if(tf.simul_test_lesson_pass_time>0,tf.simul_test_lesson_pass_time,t.train_through_new_time) simul_test_lesson_pass_time "
                                  ." from %s al "
                                  ." left join %s tl on al.phone = tl.phone and tl.status =1 and tl.is_test_flag=0 and "
                                  ." not exists (select 1 from %s where phone = tl.phone and status =1 and "
                                  ."is_test_flag=0 and add_time<tl.add_time )"
                                  ." left join %s t on al.phone = t.phone and t.is_test_user=0"
                                  ." left join %s tf on t.teacherid = tf.teacherid"
                                  ." left join %s tr on tr.trial_train_status =1 and tr.type=10 and tr.teacherid = t.teacherid "
                                  ." and  not exists(select 1 from %s where trial_train_status =1 and type=10 and "
                                  ."teacherid = tr.teacherid and add_time<tr.add_time)"
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s ta on t.teacherid = ta.userid and ta.train_type=1 and not exists (select 1 from %s where"
                                  ." userid = ta.userid and train_type=1 and add_time<ta.add_time)"
                                  ." left join %s ll on ta.lessonid = ll.lessonid"
                                  ." left join %s lll on lll.train_type=4 and lll.lesson_del_flag=0 and lll.lesson_type=1100 and lll.teacherid = t.teacherid and lll.lesson_start>0 and not exists(select 1 from %s where train_type=4 and lesson_del_flag=0 and lesson_type=1100 and teacherid = lll.teacherid and lesson_start>0 and lesson_start<lll.lesson_start)"
                                  ." where %s having(tl.add_time>0 or tr.add_time>0)",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_flow::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }




    public function get_train_through_tea($time){
        $where_arr=[
            "t.teacherid >0"
        ];

        if(is_array($time)){
            $start_time = $time['start_time'];
            $end_time   = $time['end_time'];
            // $time_str = "l.confirm_time>=$start_time and l.confirm_time < $end_time ";
            $this->where_arr_add_time_range($where_arr,"al.answer_begin_time",$start_time,$end_time);

        }else{
            // $time_str = "l.confirm_time>=$time";
        }

        $time_begin = strtotime(date("2017-01-05")); //
        $time_str = "l.confirm_time>$time_begin";



        $sql = $this->gen_sql_new("select  distinct t.teacherid"
                                  ." from %s al left join %s l on al.phone = l.phone and %s"
                                  ." left join %s t on l.phone = t.phone and l.status=1 and t.is_test_user=0 and t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%' and t.train_through_new=1 "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $time_str,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $arr =  $this->main_get_list($sql);
        $list=[];
        foreach($arr as $val){
            $list[]=$val["teacherid"];
        }
        return $list;
    }

    public function get_teacher_lecture_time($time){

        $time_limit_str = '';
        if(is_array($time)){
            $start_time = $time['start_time'];
            $end_time   = $time['end_time'];
            // $time_str = "l.confirm_time>=$start_time and l.confirm_time < $end_time ";
            // $this->where_arr_add_time_range($where_arr,"al.answer_begin_time",$start_time,$end_time);
            $time_limit_str = "(al.answer_begin_time>=$start_time and al.answer_begin_time<$end_time)";

        }else{
            // $time_str = "l.confirm_time>=$time";
        }

        $time_begin = strtotime(date("2017-01-05")); //
        $time_str = "l.confirm_time>$time_begin";



        $sql = $this->gen_sql_new("select count(*) num,sum(l.add_time -al.answer_begin_time) time "
                                  ." from %s al join %s l on al.phone = l.phone and %s"
                                  ." where al.answer_begin_time = (select min(answer_begin_time) from %s where phone = al.phone)"
                                  ." and l.add_time = (select max(add_time) from %s where phone = l.phone) and l.add_time>0 and al.answer_begin_time >0 and %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $time_str,
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $time_limit_str
        );
        return $this->main_get_row($sql);
    }

    public function get_ref_teacher_list(){
        $where_arr = [
            "la.answer_begin_time>1483200000",
            "la.reference!=''",
        ];
        $sql = $this->gen_sql_new("select la.phone,la.answer_begin_time,t1.teacher_ref_type,t2.teacherid,tl.id as lid "
                                  ." from %s la"
                                  ." left join %s t1 on la.reference=t1.phone"
                                  ." left join %s t2 on la.phone=t2.phone"
                                  ." left join %s tl on la.phone=tl.phone"
                                  ." where %s"
                                  ." and t1.teacher_ref_type>0"
                                  ." group by la.phone"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lecture_appointment_info($phone){
        $where_arr = [
            ["tl.phone='%s'",$phone,0]
        ];

        $sql = $this->gen_sql_new("select tl.subject_ex,tl.grade_start,tl.grade_end,tl.not_grade,tl.grade_ex,tl.teacher_type,"
                                  ." tl.trans_grade_ex,tl.trans_grade_start,tl.trans_grade_end,tl.trans_subject_ex,tl.name,"
                                  ." t.subject,t.grade_start,t.grade_end,t.not_grade,t.teacherid"
                                  ." from %s tl"
                                  ." left join %s t on tl.phone=t.phone"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_email_by_phone($phone){
        $sql = $this->gen_sql_new("select email"
                                  ." from %s "
                                  ." where phone='%s' "
                                  ,self::DB_TABLE_NAME
                                  ,$phone
        );
        return $this->main_get_value($sql);

    }

    public function get_lecture_appointment_num($start_time,$end_time){
        $where_arr=[
            ["answer_begin_time >=%u",$start_time,-1],
            ["answer_begin_time <%u",$end_time,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct phone) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_teacher_lecture_appointment_origin_list($start_time,$end_time){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select t.teacher_ref_type,count(ta.phone) as num"
                                  ." from %s ta"
                                  ." left join %s t on ta.reference=t.phone"
                                  ." where %s"
                                  ." group by t.teacher_ref_type"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacher_ref_type'];
        });
    }

    public function get_tea_list_by_reference($reference){
        $where_arr = [
            ["reference='%s'",$reference,""],
        ];
        $sql = $this->gen_sql_new("select teacherid,teaccher_money_type,level"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);


    }


    public function get_app_lecture_sum_by_reference($start_time,$end_time){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select count(ta.phone) app_num,ta.reference,t.teacher_ref_type,t.realname,t.phone,c.channel_id,c.channel_name"
                                  ." from %s ta left join %s t on ta.reference=t.phone"
                                  ." left join %s cg on t.teacher_ref_type = cg.ref_type"
                                  ." left join %s c on cg.channel_id = c.channel_id"
                                  ." where %s group by reference order by app_num desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_admin_channel_group::DB_TABLE_NAME
                                  ,t_admin_channel_list::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["reference"];
        });
    }

    public function get_lecture_sum_by_reference($start_time,$end_time){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select count(tl.phone) lecture_num,sum(if(tl.status=1,1,0)) lecture_pass_num ,ta.reference"
                                  ." from %s ta join %s tl on ta.phone=tl.phone"
                                  ." where %s "
                                  ." and not exists ("
                                  ." select 1 from %s tll where tll.phone=tl.phone and tl.add_time<tll.add_time)"
                                  ." group by reference "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["reference"];
        });

    }

    public function get_train_sum_by_reference($start_time,$end_time){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select count(tr.id) train_num,sum(if(tr.trial_train_status=1,1,0)) train_pass_num ,ta.reference"
                                  ." from %s ta join %s t on ta.phone=t.phone"
                                  ." join %s tra on tra.userid = t.teacherid"
                                  ." join %s l on tra.lessonid = l.lessonid"
                                  ." join %s tr on tra.lessonid = tr.train_lessonid and tr.type=10"
                                  ." where %s group by reference "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["reference"];
        });

    }

    public function get_trial_sum_by_reference($start_time,$end_time,$train_through_new=-1){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
            ["tt.train_through_new=%u",$train_through_new,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct tt.teacherid) trial_num ,ta.reference"
                                  ." from %s ta "
                                  ."  join %s tt on ta.phone = tt.phone"
                                  ." join %s taaa on tt.teacherid = taaa.userid"
                                  ."  join %s ll on (taaa.lessonid = ll.lessonid and ll.train_type =1)"
                                  ." where %s  group by reference "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["reference"];
        });
    }

    public function get_train_trial_sum_by_reference($start_time,$end_time,$train_through_new=-1){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
            ["ttt.train_through_new=%u",$train_through_new,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct ttt.teacherid) train_trial_num ,ta.reference"
                                  ." from %s ta join %s t on ta.phone=t.phone_spare"
                                  ." join %s tra on tra.userid = t.teacherid"
                                  ." join %s l on tra.lessonid = l.lessonid"
                                  ." join %s tr on tra.lessonid = tr.train_lessonid and tr.type=10"
                                  ." join %s tt on ta.phone = tt.phone"
                                  ." join %s taa on tt.teacherid = taa.userid"
                                  ." join %s ll on taa.lessonid = ll.lessonid and ll.train_type =1"
                                  ." join %s ttt on ll.teacherid = ttt.teacherid"
                                  ." where %s and not exists ("
                                  ." select 1 from %s taa where taa.phone=ta.phone and ta.answer_begin_time<taa.answer_begin_time)"
                                  ." group by reference "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["reference"];
        });
    }

    public function get_lecture_trial_sum_by_reference($start_time,$end_time,$train_through_new=-1){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
            ["ttt.train_through_new=%u",$train_through_new,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct ttt.teacherid) lecture_trial_num,ta.reference"
                                  ." from %s ta join %s tl on ta.phone=tl.phone"
                                  ." join %s t on tl.phone = t.phone"
                                  ." join %s taa on t.teacherid = taa.userid"
                                  ." join %s l on taa.lessonid = l.lessonid and l.train_type =1"
                                  ." join %s ttt on l.teacherid = ttt.teacherid"
                                  ." where %s and not exists ("
                                  ." select 1 from %s taa where taa.phone=ta.phone and ta.answer_begin_time<taa.answer_begin_time)"
                                  ." and not exists ("
                                  ." select 1 from %s tll where tll.phone=tl.phone and tl.add_time<tll.add_time)"
                                  ." group by reference "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["reference"];
        });
    }

    public function get_id_list_by_adminid($accept_adminid,$lecture_revisit_type=-1){
        $where_arr=[];
        if($lecture_revisit_type==1){
            $where_arr[]="lecture_revisit_type not in (4,8)";
        }
        $sql = $this->gen_sql_new("select id,answer_begin_time,accept_adminid,lecture_revisit_type"
                                  ." from %s where accept_adminid=%u and %s",
                                  self::DB_TABLE_NAME,
                                  $accept_adminid,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function tongji_zs_reference_info($start_time,$end_time,$admin_name=""){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
        ];
        if ($admin_name) {
            $admin_name=$this->ensql($admin_name);
            $where_arr[]="(t.realname like '%%".$admin_name."%%' or t.phone like '%%".$admin_name."%%' )";
        }
        $sql = $this->gen_sql_new("select count(distinct ta.phone) as num,ta.reference,t.realname"
                                  ." from %s ta"
                                  ." left join %s t on ta.reference=t.phone"
                                  ." where %s"
                                  ." group by t.realname"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_reference_count($phone){
        $where_arr = [
            ["reference='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select count(1)"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    /**
     * 获取所推荐的不同类型的老师数
     * @param string phone 推荐人手机号
     * @param int type  查看的老师类型 1 其他 2 在职老师
     * @param int begin_time 开始检测的时间
     */
    public function get_reference_num($phone,$type,$begin_time){
        $where_arr = [
            ["tla.reference='%s'",$phone,""],
            ["tla.answer_begin_time>%u",$begin_time,0],
            "t.train_through_new_time>0",
            "t.trial_lecture_is_pass=1",
        ];
        if($type==1){
            $where_arr[] = "t.identity in (5,6,7)";
        }else{
            $where_arr[] = "t.identity not in (5,6,7)";
        }
        if($phone!="13661763881"){
            $where_arr[] = "t.is_test_user=0";
        }
        $sql = $this->gen_sql_new("select count(1) "
                                  ." from %s tla"
                                  ." left join %s t on tla.phone=t.phone"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

     public function get_all_info_b1($page_num,$start_time,$end_time,$phone,$status=-1){
        $where_arr = [
            ["answer_begin_time>=%u", $start_time, -1 ],
            ["answer_begin_time<=%u", $end_time, -1 ],
            ["reference like '%%%s%%'", $phone, -1 ],
        ];

        $status_str = "true";
        if($status==-2){
            $where_arr[] = " l.status is null ";
        }else{
            $where_arr[] = ["l.status=%u",$status,-1];
            if($status==0){
                $status_str = "1,2,3";
            }elseif($status==1){
                $status_str = "0,2,3";
            }elseif($status==3){
                $status_str = "0,1,2";
            }
        }

        $sql = $this->gen_sql_new("select la.id,la.name,la.phone,la.email,la.grade_ex,la.subject_ex,la.textbook,la.school,"
                                  ." la.teacher_type,la.custom,la.lecture_appointment_origin,"
                                  ." la.reference,la.answer_begin_time,la.answer_end_time,l.confirm_time,"
                                  ." if(l.status is null,'-2',l.status) as status,l.subject,l.grade,l.reason"
                                  ." from %s la"
                                  ." left join %s l on l.phone=la.phone "
                                  ." where %s "
                                  ." and not exists ("
                                  ." select 1 from %s where "
                                  ." phone=l.phone and add_time>l.add_time and status in (%s) "
                                  ." )"
                                  ." group by la.phone "
                                  ." order by answer_begin_time desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$status_str
        );
        //dd($sql);
        //return $this->main_get_list_by_page($sql,$page_num,10,true);
        $ret = $this->main_get_list_by_page($sql,$page_num,10,true);
        //dd($ret);
    }

    public function get_id_by_adminid($adminid){
        $sql = $this->gen_sql_new("select id from %s where accept_adminid=%u order by id desc limit 0,82",self::DB_TABLE_NAME,$adminid);
        return $this->main_get_list($sql);
    }

    public function get_reference_teacher_info($reference){
        $sql =$this->gen_sql_new("select ta.name,t.train_through_new,t.train_through_new_time,t.teacherid,ta.subject_ex,ta.grade_start,ta.grade_end,ta.school,ta.phone,ta.teacher_type,ta.grade_ex,ta.qq,ta.email "
                                 ." from %s ta left join %s t on ta.phone = t.phone"
                                 ." where ta.reference = %u order by t.train_through_new desc",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 $reference
        );
        return $this->main_get_list_as_page($sql);
    }

    public function gen_have_video_teacher_info(){
        $sql = $this->gen_sql_new("select ta.name,t.train_through_new,t.train_through_new_time,t.teacherid,ta.subject_ex,ta.grade_start,ta.grade_end,ta.school,ta.phone,ta.teacher_type,ta.grade_ex"
                                  ." from %s ta left join %s tl on ta.phone = tl.phone"
                                  ." left join %s t on ta.phone = t.phone"
                                  ." where tl.id >0 group by ta.phone ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_all_full_time_num($start_time,$end_time){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
            "full_time=1"
        ];
        $sql = $this->gen_sql_new("select count(*) from %s where %s ",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_email_list($start_time,$end_time){
        $where_arr = [
            ["answer_begin_time >%u",$start_time,0],
            ["answer_begin_time <%u",$end_time,0],
            "tla.email!=''"
        ];
        $sql = $this->gen_sql_new("select tla.name,tla.email"
                                  ." from %s tla"
                                  ." left join %s t on tla.phone=t.phone"
                                  ." where %s"
                                  ." and not exists (select 1 from %s where tla.phone=phone)"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_self_count($reference,$start_time,$end_time){
        $where_arr=[
            ["answer_begin_time >=%u",$start_time,-1],
            ["answer_begin_time <=%u",$end_time,-1],
            ["reference = '%s'",$reference,-1]
            // "la.accept_adminid>0"
        ];
        $sql = $this->gen_sql_new("select count(*) num from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_no_call_all_info(){
        $sql = $this->gen_sql_new("select a.id,a.phone,count(distinct l.id) from %s a left join %s l on a.phone = l.phone  "
                                  ." where lecture_revisit_type=0 and l.id is not null group by a.phone",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_no_call_all_info_train($start_time,$end_time,$adminid){
        $where_arr=[
            ["l.lesson_start>=%u",$start_time,-1],
            ["l.lesson_start <=%u",$end_time,-1],
            ["accept_adminid=%u",$adminid,-1]
        ];

        $sql = $this->gen_sql_new("select a.id,a.phone,count(distinct l.lessonid)"
                                  ." from %s a left join %s t on a.phone = t.phone "
                                  ." left join %s ta on t.teacherid = ta.userid and ta.train_type=5"
                                  ." left join %s l on ta.lessonid = l.lessonid and l.train_type=5 and l.lesson_type =1100 and l.lesson_del_flag=0 and l.confirm_flag <2"
                                  ." where l.lessonid is not null and %s group by a.phone order by a.phone limit 1185,395 ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_no_call_all_info_new($start_time,$end_time){
        $where_arr=[
            ["answer_begin_time >=%u",$start_time,-1],
            ["answer_begin_time <=%u",$end_time,-1],
        ];
        $sql = $this->gen_sql_new("select id,phone from %s "
                                  ." where lecture_revisit_type=0 and %s order by id limit 222,74",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_type_list(){
        $sql = $this->gen_sql_new("update %s set teacher_type=0 "
                                  ." where teacher_type=0 or teacher_type is null or teacher_type='' or teacher_type=-1 ",
                                  self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function get_no_right_reference_list(){
        $sql = $this->gen_sql_new("select reference,id from %s where LENGTH(reference) >11",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_refresh_list(){
        $where_arr = [
            "grade_ex<100",
        ];
        $sql = $this->gen_sql_new("select id,tl.phone,tl.grade_ex,tl.subject_ex,"
                                  ." from %s tl"
                                  ." left join t on tl.phone=t.phone"
                                  ." where %s"
                                  ." limit 100"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_fulltime_teacher_count($start_time,$end_time){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,-1],
            ["answer_begin_time<%u",$end_time,-1],
            "full_time=1",
            "id>15246"
        ];
        $sql = $this->gen_sql_new("select count(phone) as apply_num "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_fulltime_teacher_total($start_time,$end_time){
        $where_arr = [
            "full_time=1",
            "id>15246",
            ['answer_begin_time>%u',$start_time,-1],
            ['answer_begin_time<%u',$end_time,-1],
        ];
        $sql = $this->gen_sql_new("select count(phone) as apply_total "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_fulltime_teacher_arrive($start_time,$end_time){
        $where_arr = [
            "l.full_time=1",
            "l.id>15246",
            "t.phone>0",
            ["t.real_begin_time>%u",$start_time,-1],
            ["t.real_begin_time<%u",$end_time,-1],
        ];
        $sql = $this->gen_sql_new("select count(distinct(l.phone)) as arrive_count"
                                  ." from %s l "
                                  ." left join %s t on t.phone = l.phone"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_fulltime_teacher_arrive_video($start_time,$end_time){
        $where_arr = [
            "l.full_time=1",
            "l.id>15246",
            "s.lesson_type=1100",
            " s.lesson_del_flag = 0",
            "s.confirm_flag < 2 ",
            "s.train_type = 5",
            " t.is_test_user  = 0",
            ["s.lesson_start>%u",$start_time,-1],
            ["s.lesson_start<%u",$end_time,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct(l.phone)) as video_num"
                                  ." from %s l "
                                  ." left join %s t on t.phone = l.phone"
                                  ." left join %s s on s.userid = t.teacherid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_fulltime_teacher_arrive_through($start_time,$end_time){
        $where_arr = [
            "l.full_time=1",
            "l.id>15246",
            "t.phone>0",
            ["t.confirm_time >%u",$start_time,-1],
            ["t.confirm_time <%u",$end_time,-1],
            "t.status=1",
        ];
        $sql = $this->gen_sql_new("select count(distinct(l.phone)) as arrive_through_count"
                                  ." from %s l "
                                  ." left join %s t on t.phone = l.phone"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_fulltime_teacher_arrive_video_through($start_time,$end_time){
        $where_arr = [
            "l.full_time=1",
            "l.id>15246",
            "s.lesson_type=1100",
            " s.lesson_del_flag = 0",
            "s.confirm_flag < 2 ",
            "s.train_type = 5",
            " t.is_test_user  = 0",
            "k.type=10",//第一次面试
            "k.trial_train_status =1",
            ["s.lesson_start>%u",$start_time,-1],
            ["s.lesson_start<%u",$end_time,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct(l.phone)) as video_through_num"
                                  ." from %s l "
                                  ." left join %s t on t.phone = l.phone"
                                  ." left join %s s on s.userid = t.teacherid"
                                  ." left join %s k on k.train_lessonid = s.lessonid  "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_fulltime_teacher_arrive_second_through($start_time,$end_time)
    {
       $where_arr = [
            "l.full_time=1",
            "l.id>15246",
            " t.is_test_user  = 0",
            "s.type=12",//第2次面试
            "s.trial_train_status =1",
            ["s.add_time>%u",$start_time,-1],
            ["s.add_time<%u",$end_time,-1],
        ];
        $sql = $this->gen_sql_new("select count(distinct(l.phone)) as through_num"
                                  ." from %s l "
                                  ." left join %s t on t.phone = l.phone"
                                  ." left join %s s on s.teacherid  = t.teacherid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_fulltime_teacher_enter($start_time,$end_time)
    {
       $where_arr = [
            "l.full_time=1",
            "l.id>15246",
            " t.is_test_user  = 0",
            "t.train_through_new =1",
            ["t.train_through_new_time >%u",$start_time,-1],
            ["t.train_through_new_time <%u",$end_time,-1],
            "m.phone>0"
        ];
        $sql = $this->gen_sql_new("select count(distinct(l.phone)) as num"
                                  ." from %s l "
                                  ." left join %s m on m.phone = l.phone"
                                  ." left join %s t on t.phone = l.phone"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_data_to_teacher_flow($phone=""){
        $where_arr = [
            //["answer_begin_time>%u", $start_time, 0],
            //["answer_begin_time<%u", $end_time, 0],
            ["phone='%s'",$phone,""],
        ];
        $sql = $this->gen_sql_new("select phone,answer_begin_time,accept_adminid from %s where %s limit 1",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function check_tea_ref($teacherid,$teacher_ref_type){
        $where_arr = [
            ["t.teacherid=%u",$teacherid,-1],
            ["t.teacher_ref_type=%u",$teacher_ref_type,0],
            ["t2.teacher_ref_type=%u",$teacher_ref_type,0],
            "t.is_test_user=0",
            "t.teacher_money_type=5",
        ];
        $sql = $this->gen_sql_new("select 1"
                                  ." from %s t"
                                  ." left join %s tla on t.phone=tla.phone"
                                  ." left join %s t2 on tla.reference=t2.phone"
                                  ." where %s"
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_references() {
        $sql = $this->gen_sql_new("select reference from %s where reference != '' and reference != 0 ",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql, function($item) {
            return $item['reference'];
        });
    }

    public function get_money_list($start_time, $end_time, $reference) {
        //select teacherid,name from t_teacher_info t left join t_teacher_lecture_appointment_info ta on t.phone=ta.phone where ta.reference ='15366667766' and t.train_through_new_time  > 0 and train_through_new_time >= unix_timestamp('2017-11-1') and unix_timestamp('2017-12-1')
        $where_arr = [
            "ta.reference='$reference' ",
            "t.train_through_new_time>0",
            ["train_through_new_time>=%u", $start_time, 0],
            ["train_through_new_time<%u", $end_time, 0]
        ];
        $sql = $this->gen_sql_new("select teacherid from %s t left join %s ta on t.phone=ta.phone where %s",
                                  t_teacher_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function($item) {
            return $item['teacherid'];
        });
    }

    public function get_all_info_new($page_num,$start_time,$end_time,$phone,$status=-1){
        $where_arr = [
            ["answer_begin_time>=%u", $start_time, -1 ],
            ["answer_begin_time<=%u", $end_time, -1 ],
            ["reference like '%%%s%%'", $phone, -1 ],
        ];

        $status_str = "true";
        if($status==-2){
            $where_arr[] = " (l.status is null or l.add_time=0)";
        }else{
            if($status!=-1){
                $where_arr[] = "l.add_time>0";
            }else{
                $where_arr[] = ["l.status=%u",$status,-1];
            }
            if($status==0){
                $status_str = "1,2,3";
            }elseif($status==1){
                $status_str = "0,2,3";
            }elseif($status==3){
                $status_str = "0,1,2";
            }
        }

        $sql = $this->gen_sql_new("select la.id,la.name,la.phone,la.email,la.grade_ex,la.subject_ex,la.textbook,la.school,"
                                  ." la.teacher_type,la.custom,la.lecture_appointment_origin,la.qq,"
                                  ." la.reference,la.answer_begin_time,la.answer_end_time,l.confirm_time,"
                                  ." if(l.status is null or l.add_time=0,'-2',l.status) as status,l.subject,l.grade,l.reason,"
                                  ." l.add_time"
                                  ." from %s la"
                                  ." left join %s l on l.phone=la.phone "
                                  ." where %s "
                                  ." and not exists ("
                                  ." select 1 from %s where "
                                  ." phone=l.phone and add_time>l.add_time and status in (%s) "
                                  ." )"
                                  ." group by la.phone "
                                  ." order by answer_begin_time desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$status_str
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function get_tongji_data($start_time,$end_time){
        $where_arr = [
            ["ta.answer_begin_time>=%u", $start_time, -1 ],
            ["ta.answer_begin_time<=%u", $end_time, -1 ],
            "ta.accept_adminid>0"
        ];
        $sql = $this->gen_sql_new("select ta.accept_adminid,m.name,"
                                  ." sum(if(ta.lecture_revisit_type in (2,3,4,6,8),1,0)) phone_count,"
                                  ." sum(if(ta.lecture_revisit_type in (4,8),1,0)) interview_num,"
                                  ." sum(if(t.trial_lecture_is_pass=1,1,0)) pass_num,"
                                  ." sum(if(t.train_through_new=1,1,0)) memeber_num"
                                  ." from %s ta  "
                                  // left join %s l on ta.phone = l.phone and not exists(select 1 from %s where add_time<l.add_time)"
                                  ." left join %s t on ta.phone = t.phone"
                                  // ." left join %s ll on ll.userid = t.teacherid and ll.lesson_start>0 and ll.lesson_status>1 and ll.lesson_del_flag=0 and ll.train_type=5 and not exists (select 1 from %s where userid = ll.userid and lesson_start>0 and lesson_status>1 and lesson_del_flag=0 and train_type=5 and lesson_start<ll.lesson_start)"
                                  ." left join %s m on ta.accept_adminid = m.uid"
                                  ." where %s group by m.name ",
                                  self::DB_TABLE_NAME,
                                  // t_teacher_lecture_info::DB_TABLE_NAME,
                                  // t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  // t_lesson_info::DB_TABLE_NAME,
                                  // t_lesson_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function($item) {
            return $item['name'];
        });
    }

    public function get_teacher_trans_info($id){
        $where_arr = [
            ["id=%u",$id,0]
        ];
        $sql = $this->gen_sql_new("select grade_ex,subject_ex,trans_grade_ex,trans_subject_ex "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }


}
