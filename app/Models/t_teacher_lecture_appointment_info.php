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
        $sql=$this->gen_sql_new("select email,school,textbook,name,teacher_type "
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
        $sql = $this->gen_sql_new("select appointment_id"
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


    public function get_all_info($page_num,$start_time,$end_time,$teacherid,$lecture_appointment_status,
                                 $user_name,$status,$adminid=-1,$record_status=-1,$grade=-1,$subject=-1,
                                 $teacher_ref_type,$interview_type=-1,$have_wx=-1, $lecture_revisit_type=-1
    ){
        $where_arr = [
            ["answer_begin_time>=%u", $start_time, -1 ],
            ["answer_begin_time<=%u", $end_time, -1 ],
            ["lecture_appointment_status=%u", $lecture_appointment_status, -1 ],
            ["t.teacherid=%u", $teacherid, -1 ],
            ["la.accept_adminid=%u", $adminid, -1 ],
            ["la.lecture_revisit_type=%u", $lecture_revisit_type, -1 ],
        ];

        if($interview_type==0){
            $where_arr[] = "l.status is null and ta.lessonid is null";
        }elseif($interview_type==1){
            $where_arr[] = "l.status is not null and ta.lessonid is null";
        }elseif($interview_type==2){
            $where_arr[] = "l.status is null and ta.lessonid is not null";
        }

        if($status==0){
            $where_arr[] ="((l.status=0 and ((tr.id is null and ta.lessonid is not null) or ta.lessonid is null )) or ((l.status is null or l.status=0) && (tr.id is null and ta.lessonid is not null)) )";
        }else if($status==1){
            $where_arr[] ="(l.status=1 or tr.trial_train_status =1)";
        }else if($status==2){
            $where_arr[] ="((l.status=2 and (tr.trial_train_status =0 or ta.lessonid is null )) or ((l.status=2 or l.status is null) and tr.trial_train_status =0))";
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
            $where_arr[] ="(ttt.wx_openid = '' or ttt.wx_openid is null )";
        }elseif($have_wx==1){
            $where_arr[] ="ttt.wx_openid <> '' and ttt.wx_openid is not null";
        }
        $where_arr[] = $this->where_get_in_str_query("t.teacher_ref_type", $teacher_ref_type );


        if ($user_name) {
            $user_name=$this->ensql($user_name);
            $where_arr = [
                "(la.name like '%%".$user_name."%%' "
                ." or la.school like '%%".$user_name."%%' "
                ." or la.phone like '%%".$user_name."%%' "
                ." or grade_ex like '%%".$user_name."%%' "
                ." or la.qq like '%%".$user_name."%%' "
                ." or subject_ex like '%%".$user_name."%%' "
                ." or textbook like '%%".$user_name."%%' "
                ." or la.teacher_type like '%%".$user_name."%%')"
            ];
        }

        $sql = $this->gen_sql_new("select la.id,la.name,la.phone,la.email,la.grade_ex,la.subject_ex,la.textbook,la.school,"
                                  ." la.teacher_type,la.custom,la.self_introduction_experience,"
                                  ." la.lecture_appointment_status,la.reference,la.answer_begin_time,la.answer_end_time,"
                                  ." if(l.status is null,'-2',l.status) as status,la.lecture_revisit_type,"
                                  ." if(tr.trial_train_status is null,-2,tr.trial_train_status) trial_train_status,"
                                  ." l.subject,l.grade,la.acc,l.reason ,tr.record_info ,ta.lessonid train_lessonid,"
                                  ." if(t.nick='',t.realname,t.nick) as reference_name,reference,t.teacherid,m.account,"
                                  ." la.grade_start,la.grade_end,la.not_grade,tt.teacherid train_teacherid,"
                                  ." la.trans_grade,la.trans_grade_start,la.trans_grade_end,la.qq,ttt.wx_openid"
                                  ." from %s la"
                                  ." left join %s l on l.phone=la.phone and not exists ("
                                  ." select 1 from %s ll where ll.phone=l.phone and l.add_time<ll.add_time)"
                                  ." left join %s t on t.phone=la.reference"
                                  ." left join %s m on la.accept_adminid=m.uid"
                                  ." left join %s tt on  la.phone = tt.phone"
                                  ." left join %s ta on ta.userid= tt.teacherid and not exists ("
                                  ." select 1 from %s taa where taa.userid=ta.userid and ta.add_time<taa.add_time)"
                                  ." left join %s tr on tr.train_lessonid = ta.lessonid and type=10"
                                  ." left join %s ttt on  la.phone = ttt.phone"
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
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
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
            ["answer_begin_time <=%u",$end_time,-1]
        ];
        $sql= $this->gen_sql_new("select count(*) all_count,accept_adminid,m.account "
                                 ." from %s la "
                                 ." left join %s m on la.accept_adminid = m.uid"
                                 ." where %s group by accept_adminid",
                                 self::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);
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
        
        $sql = $this->gen_sql_new("select count(distinct al.phone) app_total,count(distinct l.phone) lec_total,count(distinct t.teacherid) tea_total,count(distinct tt.teacherid) tran_total "
                                  ." from %s al left join %s l on al.phone = l.phone and l.confirm_time >=%u"
                                  ." left join %s t on l.phone = t.phone and l.status=1 and t.is_test_user=0 and t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%' "
                                  ." left join %s tt on tt.teacherid = t.teacherid and tt.train_through_new=1"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $time,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_train_through_tea($time){
        $where_arr=[
            "t.teacherid >0"
        ];
        
        $sql = $this->gen_sql_new("select  distinct t.teacherid"
                                  ." from %s al left join %s l on al.phone = l.phone and l.confirm_time >=%u"
                                  ." left join %s t on l.phone = t.phone and l.status=1 and t.is_test_user=0 and t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%' and t.train_through_new=1 "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $time,
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
        $sql = $this->gen_sql_new("select count(*) num,sum(l.add_time -al.answer_begin_time) time "
                                  ." from %s al join %s l on al.phone = l.phone and l.confirm_time >=%u"
                                  ." where al.answer_begin_time = (select min(answer_begin_time) from %s where phone = al.phone)"
                                  ." and l.add_time = (select max(add_time) from %s where phone = l.phone) and l.add_time>0 and al.answer_begin_time >0",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $time,
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME                                  
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

    public function get_app_lecture_sum_by_reference($start_time,$end_time){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select count(ta.phone) app_num,ta.reference,t.teacher_ref_type,t.realname"
                                  ." from %s ta left join %s t on ta.reference=t.phone"
                                  ." where %s group by reference "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
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
                                  ." where %s and not exists ("
                                  ." select 1 from %s taa where taa.phone=ta.phone and ta.answer_begin_time<taa.answer_begin_time)"
                                  ." and not exists ("
                                  ." select 1 from %s tll where tll.phone=tl.phone and tl.add_time<tll.add_time)"
                                  ." group by reference "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,self::DB_TABLE_NAME
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
                                  ." from %s ta join %s t on ta.phone=t.phone_spare"
                                  ." join %s tra on tra.userid = t.teacherid"
                                  ." join %s l on tra.lessonid = l.lessonid"
                                  ." join %s tr on tra.lessonid = tr.train_lessonid and tr.type=10"
                                  ." where %s and not exists ("
                                  ." select 1 from %s taa where taa.phone=ta.phone and ta.answer_begin_time<taa.answer_begin_time)"
                                  ." group by reference "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,$where_arr
                                  ,self::DB_TABLE_NAME
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
                                  ." where %s  and not exists ("
                                  ." select 1 from %s taa where taa.phone=ta.phone and ta.answer_begin_time<taa.answer_begin_time)"
                                  ." group by reference "
                                  ,self::DB_TABLE_NAME                                 
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,self::DB_TABLE_NAME
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

    public function get_id_list_by_adminid($accept_adminid){
        $sql = $this->gen_sql_new("select id,answer_begin_time,accept_adminid from %s where accept_adminid=%u",
                                  self::DB_TABLE_NAME,
                                  $accept_adminid                                  
        );
        return $this->main_get_list($sql);
    }

  
    public function tongji_zs_reference_info($start_time,$end_time){
        $where_arr = [
            ["answer_begin_time>%u",$start_time,0],
            ["answer_begin_time<%u",$end_time,0],
        ];
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

    // 获取所推荐的不同类型的老师数
    public function get_reference_num($phone,$type,$begin_time){
        $where_arr = [
            ["tla.reference='%s'",$phone,""],
            ["tla.answer_begin_time>%u",$begin_time,0],
            "t.train_through_new=1",
            "t.trial_lecture_is_pass=1",
        ];
        if($type==1){
            $where_arr[] = "t.identity in (5,6)";
        }else{
            $where_arr[] = "t.identity not in (5,6)";
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

}