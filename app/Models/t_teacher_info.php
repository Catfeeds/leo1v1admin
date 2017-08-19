<?php
namespace App\Models;
class t_teacher_info extends \App\Models\Zgen\z_t_teacher_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add_new_teacher($tea_nick, $gender, $birth, $work_year, $phone,
                                    $email,$teacher_type, $teacherid, $level, $teacher_money_type,
                                    $create_time=0,$address="",$subject=0,$school="",$interview_access="",
                                    $grade_part_ex=0,$identity=0,$trial_lecture_is_pass=0,$face="",$textbook="",
                                    $resume_url="",$textbook_type=0,$dialect_note="",$interview_score=0,$wx_use_flag=1,
                                    $teacher_ref_type=0,$grade_start=0,$grade_end=0,$not_grade="",$bankcard="",
                                    $bank_address="",$bank_account="",$phone_spare="",$train_through_new=0,$add_acc="system"
    ){
        return $this->row_insert([
            'nick'                   => $tea_nick,
            'face'                   => $face,
            'realname'               => $tea_nick,
            'gender'                 => $gender,
            'birth'                  => $birth,
            'work_year'              => $work_year,
            'phone'                  => $phone,
            'email'                  => $email,
            'teacher_type'           => $teacher_type,
            'teacherid'              => $teacherid,
            'level'                  => $level,
            'teacher_money_type'     => $teacher_money_type,
            "teacher_tags"           => "",
            "create_time"            => $create_time,
            "address"                => $address,
            "school"                 => $school,
            "subject"                => $subject,
            "interview_access"       => $interview_access,
            "grade_part_ex"          => $grade_part_ex,
            "identity"               => $identity,
            "trial_lecture_is_pass"  => $trial_lecture_is_pass,
            "teacher_textbook"       => $textbook,
            "jianli"                 => $resume_url,
            "textbook_type"          => $textbook_type,
            "dialect_notes"          => $dialect_note,
            "interview_score"        => $interview_score,
            "wx_use_flag"            => $wx_use_flag,
            "teacher_ref_type"       => $teacher_ref_type,
            "grade_start"            => $grade_start,
            "grade_end"              => $grade_end,
            "not_grade"              => $not_grade,
            "bankcard"               => $bankcard,
            "bank_address"           => $bank_address,
            "bank_account"           => $bank_account,
            "phone_spare"            => $phone_spare,
            "train_through_new"      => $train_through_new,
            "add_acc"                => $add_acc,
        ]);
    }

    public function get_tea_list_for_tea_manage($tea_nick, $is_part_time, $page_num)
    {
        $cond_str = $this->gen_tea_list_cond($tea_nick, $is_part_time);
        $sql = sprintf("select teacherid, nick, tutor_subject, teacher_type from %s %s",
                       self::DB_TABLE_NAME,
                       $cond_str
        );
        //log::write("get_tea_list_for_tea_manage: ".$sql);
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    private function gen_tea_list_cond($tea_nick, $is_part_time)
    {
        $where = " where ";
        $and = "";
        $str = "";

        if($tea_nick != ""){
            $str .= $str. $and . " nick like '%".$tea_nick."%' ";
            if($and == ""){
                $and = " and ";
            }
        }

        if($is_part_time != -1){
            $str .=  $and . " teacher_type = ". $is_part_time;
        }
        if($str != ""){
            $str = $where . $str;
        }
        return $str."  order by teacherid desc";
    }

    public function get_teacher_nick_list()
    {
        $sql = sprintf("select nick from %s order by nick asc",
                       self::DB_TABLE_NAME
        );
        return $this->main_get_list( $sql  );
    }

    public function get_teacher_simple_list()
    {
        $sql = sprintf("select teacherid, nick, face, gender, phone,address "
                       ." from %s "
                       ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_list_for_select($id,$gender, $nick_phone,  $page_num)
    {
        $where_arr = array(
            array( "gender=%d", $gender, -1 ),
            array( "teacherid=%d", $id, -1 ),
        );
        if ($nick_phone!=""){
            $where_arr[]=array( "(nick like '%%%s%%' or  realname like '%%%s%%' or  phone like '%%%s%%' )",
                                array(
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone)));
        }

        $sql = sprintf("select teacherid as id , nick, phone,gender ,realname,subject,grade_part_ex from %s  where %s and is_quit=0",
                       self::DB_TABLE_NAME,  $this->where_str_gen( $where_arr));
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_none_freeze_tea_list_for_select($id,$gender, $nick_phone,  $page_num)
    {
        $where_arr = array(
            array( "gender=%d", $gender, -1 ),
            array( "teacherid=%d", $id, -1 ),
            "is_freeze=0"
        );
        if ($nick_phone!=""){
            $where_arr[]=array( "(nick like '%%%s%%' or  realname like '%%%s%%' or  phone like '%%%s%%' )",
                                array(
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone)));
        }

        $sql = sprintf("select teacherid as id , nick, phone,gender ,realname,subject,grade_part_ex from %s  where %s",
                       self::DB_TABLE_NAME,  $this->where_str_gen( $where_arr));
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_interview_tea_list_for_select($id,$gender, $nick_phone, $page_num){
        $where_arr = array(
            array( "gender=%d", $gender, -1 ),
            array( "teacherid=%d", $id, -1 ),
            "is_interview_teacher_flag =1"
        );
        if ($nick_phone!=""){
            $where_arr[]=array( "(nick like '%%%s%%' or  realname like '%%%s%%' or  phone like '%%%s%%' )",
                                array(
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone)));
        }

        $sql = sprintf("select teacherid as id , nick, phone,gender ,realname,subject,grade_part_ex from %s  where %s",
                       self::DB_TABLE_NAME,  $this->where_str_gen( $where_arr));
        return $this->main_get_list_by_page($sql,$page_num,10);

    }

    public function get_jiaoyan_tea_list_for_select($id,$gender, $nick_phone, $page_num){
        $where_arr = array(
            array( "t.gender=%d", $gender, -1 ),
            array( "teacherid=%d", $id, -1 ),
            "m.account_role in(4,5,9)",
            "m.del_flag=0"
        );
        if ($nick_phone!=""){
            $where_arr[]=array( "(nick like '%%%s%%' or  realname like '%%%s%%' or  t.phone like '%%%s%%' )",
                                array(
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone)));
        }

        $sql = sprintf("select teacherid as id , nick,t.phone,t.gender ,realname,subject,grade_part_ex from %s t".
                       " left join %s m on t.phone= m.phone".
                       " where %s ",
                       self::DB_TABLE_NAME,
                       t_manager_info::DB_TABLE_NAME,
                       $this->where_str_gen( $where_arr));
        return $this->main_get_list_by_page($sql,$page_num,10);

    }

    public function get_research_tea_list_for_select($id,$gender, $nick_phone, $page_num){
        $where_arr = array(
            array( "t.gender=%d", $gender, -1 ),
            array( "teacherid=%d", $id, -1 ),
            "m.account_role in(4,9)",
            "m.del_flag=0"
        );
        if ($nick_phone!=""){
            $where_arr[]=array( "(nick like '%%%s%%' or  realname like '%%%s%%' or  t.phone like '%%%s%%' )",
                                array(
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone)));
        }

        $sql = sprintf("select teacherid as id , nick,t.phone,t.gender ,realname,subject,grade_part_ex from %s t".
                       " left join %s m on t.phone= m.phone".
                       " where %s ",
                       self::DB_TABLE_NAME,
                       t_manager_info::DB_TABLE_NAME,
                       $this->where_str_gen( $where_arr));
        return $this->main_get_list_by_page($sql,$page_num,10);

    }



    public function get_teacher_detail_list_new($teacherid,$is_freeze,$page_num,$is_test_user,$gender,$grade_part_ex,$subject,$second_subject,$address,$limit_plan_lesson_type,$lesson_hold_flag,$train_through_new,$seller_flag,$tea_subject,$lstart,$lend,$teacherid_arr=[]){
        $where_arr = array(
            array( "teacherid=%u", $teacherid, -1 ),
            array( "gender=%u ", $gender, -1 ),
            array( "grade_part_ex=%u ", $grade_part_ex, -1 ),
            array( "subject=%u ", $subject, -1 ),
            array( "second_subject=%u ", $second_subject, -1 ),
            array( "is_test_user=%u ", $is_test_user, -1 ),
            array( "is_freeze=%u ", $is_freeze, -1 ),
            array( "limit_plan_lesson_type=%u ", $limit_plan_lesson_type, -1 ),
            array( "train_through_new=%u ", $train_through_new, -1 ),
            array( "lesson_hold_flag=%u ", $lesson_hold_flag, -1 ),
        );

        if ($address) {
            $address=$this->ensql($address);
            $where_arr[]="(address like '%%".$address."%%' or school like '%%".$address."%%' or nick like '%%".$address."%%' "
                ." or realname like '%%".$address."%%' or phone like '%%".$address."%%' or tea_note like '%%".$address."%%' "
                ." or user_agent like '%%".$address."%%' or teacher_tags like '%%".$address."%%' "
                ." or teacher_textbook like '%%".$address."%%' or teacherid like '%%".$address."%%')";
        }
        if($seller_flag==1){
            $where_arr[] = "is_good_flag=1 and change_good_time>0 and is_good_wx_flag=1 ";
        }
        if(!empty($tea_subject)){
            $where_arr[]="(subject in".$tea_subject." or second_subject in".$tea_subject.")";
        }
        $where_arr[]= $this->where_get_not_in_str("teacherid",  $teacherid_arr);

        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_seller_teacher_detail_list_new($page_num,$address){
        $where_arr = [];

        if ($address) {
            $address=$this->ensql($address);
            $where_arr[]=" (nick ='".$address."'  or realname ='".$address."')";
        }else{
            $where_arr[]=" (nick like '%%武大郎吴松峰%%'  or realname like '%%武大郎吴松峰%%')";
        }
        $sql = $this->gen_sql_new("select teacherid,realname,limit_week_lesson_num,is_freeze,freeze_reason,freeze_adminid,freeze_time,limit_plan_lesson_type,limit_plan_lesson_reason,limit_plan_lesson_time,lesson_hold_flag,lesson_hold_flag_acc,"
                                  ."lesson_hold_flag_time,limit_plan_lesson_account,phone  "
                                  ."from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);

    }


    public function get_teacher_detail_info($page_num,$teacherid,$teacher_money_type,$need_test_lesson_flag,$textbook_type=-1,
                                            $is_good_flag=-1 ,$is_new_teacher=1,$gender = -1,$grade_part_ex=-1,$subject=-1,
                                            $trial_flag = 0,$address="",$test_flag = 0,$is_test_user=-1,$second_subject=-1,
                                            $level=-1,$is_freeze=-1,$tea_subject="",$limit_plan_lesson_type=-1,$is_record_flag=-1,
                                            $test_lesson_full_flag=-1,$lstart,$lend,$train_through_new=-1,$lesson_hold_flag=-1,
                                            $test_transfor_per=-1,$week_liveness=-1,$interview_score=-1,$second_interview_score=-1,
                                            $teacherid_arr=[],$seller_flag=0,$qz_flag=0,$teacher_type
    ){
        $where_arr = array(
            array( "t.teacherid=%u", $teacherid, -1 ),
            array( "t.teacher_money_type=%u", $teacher_money_type, -1 ),
            array( "t.level=%u", $level, -1 ),
            array( "textbook_type=%u", $textbook_type, -1 ),
            array( "is_good_flag=%u", $is_good_flag, -1 ),
            array( "need_test_lesson_flag=%u ", $need_test_lesson_flag, -1 ),
            array( "t.gender=%u ", $gender, -1 ),
            array( "grade_part_ex=%u ", $grade_part_ex, -1 ),
            array( "t.subject=%u ", $subject, -1 ),
            array( "second_subject=%u ", $second_subject, -1 ),
            array( "is_test_user=%u ", $is_test_user, -1 ),
            array( "t.is_freeze=%u ", $is_freeze, -1 ),
            array( "t.limit_plan_lesson_type=%u ", $limit_plan_lesson_type, -1 ),
            array( "train_through_new=%u ", $train_through_new, -1 ),
            array( "lesson_hold_flag=%u ", $lesson_hold_flag, -1 ),
            array( "teacher_type=%u ", $teacher_type, -1 ),
        );

        if ($address) {
            $where_arr[]="(address like '%%".$address."%%' or t.school like '%%".$address."%%' or t.nick like '%%".$address."%%' "
                ." or t.realname like '%%".$address."%%' or t.phone like '%%".$address."%%' or tea_note like '%%".$address."%%' "
                ." or user_agent like '%%".$address."%%' or teacher_tags like '%%".$address."%%' "
                ." or teacher_textbook like '%%".$address."%%' or t.teacherid like '%%".$address."%%' "
                ." or t.email like '%%".$address."%%')";
        }
        if(!empty($teacherid_arr)){
            $this->where_arr_teacherid($where_arr,"t.teacherid", $teacherid_arr);
        }
        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
            }
        }

        if($is_new_teacher ==2){
            $where_arr[] = "t.create_time =(select max(create_time) from db_weiyi.t_teacher_info)";
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

        if($trial_flag ==1){
            $where_arr[] = "trial_lecture_is_pass=1";
        }
        if($test_flag ==1){
            $where_arr[] = "(t.nick not like '%%测试%%' and  t.nick not like '%%test%%')";
        }
        if($is_record_flag==1){
            $where_arr[] = "tr.add_time is not null";
        }else if($is_record_flag==0){
            $where_arr[] = "tr.add_time is null";
        }

        if($test_lesson_full_flag ==1){
            $hh = "having (sum(l.lessonid>0)>=8)";
        }else if($test_lesson_full_flag ==2){
            $hh = "having (sum(l.lessonid>0)<8 or sum(l.lessonid >0) is null)";
        }else{
            $hh = "";
        }
        if($test_transfor_per ==1){
            $where_arr[] = "t.test_transfor_per <10";
        }else if($test_transfor_per==2){
            $where_arr[] = "t.test_transfor_per >=10 and t.test_transfor_per <20";
        }else if($test_transfor_per==3){
            $where_arr[] = "t.test_transfor_per >=20";
        }
        if($week_liveness ==1){
            $where_arr[] = "t.week_liveness <5";
        }else if($week_liveness ==2){
             $where_arr[] = "t.week_liveness >=5 and t.week_liveness <10";
        }elseif($week_liveness ==3){
             $where_arr[] = "t.week_liveness >=10 and t.week_liveness <15";
        }else if($week_liveness ==4){
             $where_arr[] = "t.week_liveness >=15 and t.week_liveness <20";
        }else if($week_liveness ==5){
             $where_arr[] = "t.week_liveness >=20 ";
        }
        if($interview_score==1){
            $where_arr[] = "t.interview_score >=60 and t.interview_score <80";
        }elseif($interview_score==2){
            $where_arr[] = "t.interview_score >=80 and t.interview_score <90";
        }elseif($interview_score==3){
            $where_arr[] = "t.interview_score >=90";
        }
        if($second_interview_score==1){
            $where_arr[] = "t.second_interview_score >=60 and t.second_interview_score <80";
        }elseif($interview_score==2){
            $where_arr[] = "t.second_interview_score >=80 and t.second_interview_score <90";
        }elseif($interview_score==3){
            $where_arr[] = "t.second_interview_score >=90";
        }
        if($seller_flag==1){
            $where_arr[] = "t.is_good_flag=1 and t.change_good_time>0 and is_good_wx_flag=1 ";
        }
        $sql = $this->gen_sql_new("select t.wx_openid,need_test_lesson_flag,t.nick,realname, t.teacher_type,"
                                  ." t.gender,t.teacher_money_type,t.identity,t.is_test_user,"
                                  ." t.train_through_new, t.train_through_new_time,"
                                  ." birth, t.phone, t.email, rate_score, t.teacherid ,user_agent,teacher_tags,teacher_textbook,"
                                  ." create_meeting, t.level ,work_year,  advantage, base_intro,textbook_type,is_good_flag,"
                                  ." t.create_time,t.address,t.subject,second_subject,third_subject,t.school,tea_note,"
                                  ." grade_part_ex, free_time_new,t.is_freeze,freeze_reason,freeze_adminid,freeze_time, "
                                  ." t.limit_plan_lesson_type,t.limit_plan_lesson_reason,t.limit_plan_lesson_time,"
                                  ." t.limit_plan_lesson_account,tr.add_time,t.second_grade,t.third_grade,"
                                  ." if(interview_access!='',interview_access,tl.reason) as interview_access,"
                                  ." sum(l.lessonid >0) week_lesson_num,lesson_hold_flag,lesson_hold_flag_acc,"
                                  ." lesson_hold_flag_time,interview_score,second_interview_score, "
                                  ." test_transfor_per ,week_liveness,limit_day_lesson_num,limit_week_lesson_num,"
                                  ." limit_month_lesson_num ,research_note,r.record_info revisit_record_info,"
                                  ." r.add_time revisit_add_time,r.acc revisit_acc,r.class_will_type,r.class_will_sub_type,"
                                  ." r.recover_class_time,t.teacher_ref_type,t.saturday_lesson_num,grade_start,grade_end, "
                                  ." not_grade,not_grade_limit,week_lesson_count,t.trial_lecture_is_pass  "
                                  ." from %s t"
                                  ." left join %s f on f.teacherid=t.teacherid "
                                  ." left join %s tl on t.phone=tl.phone and tl.status=1"
                                  ." left join %s tr on ("
                                  ." tr.teacherid = t.teacherid and tr.type=1 and tr.add_time = ("
                                  ." select max(add_time) from db_weiyi.t_teacher_record_list where teacherid = t.teacherid and type=1)"
                                  ." )"
                                  ." left join %s l on (t.teacherid = l.teacherid "
                                  ." and l.lesson_type=2 and l.lesson_del_flag =0 and l.lesson_start >= %u and l.lesson_end < %u)"
                                  ." left join %s tss on (l.lessonid= tss.lessonid and tss.success_flag in(0,1))"
                                  ." left join %s r on (r.type=5 and t.teacherid = r.teacherid and r.add_time = (select max(add_time) from %s where teacherid = t.teacherid and type=5))"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where is_quit = 0 "
                                  ." and %s "
                                  ." group by t.teacherid %s "
                                  ." order by t.last_modified_time desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_freetime_for_week::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$lstart
                                  ,$lend
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$hh
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function get_teacher_detail_info_new($page_num,$teacherid,$teacher_money_type,$need_test_lesson_flag,$textbook_type=-1,
                                                $is_good_flag=-1 ,$is_new_teacher=1,$gender = -1,$grade_part_ex=-1,$subject=-1,
                                                $trial_flag = 0,$address="",$test_flag = 0,$is_test_user=-1,$second_subject=-1,
                                                $level=-1,$is_freeze=-1,$tea_subject="",$limit_plan_lesson_type=-1,
                                                $is_record_flag=-1,$test_lesson_full_flag=-1,$lstart,$lend,$train_through_new=-1,
                                                $lesson_hold_flag=-1,$test_transfor_per=-1,$week_liveness=-1,$interview_score=-1,
                                                $second_interview_score=-1, $teacherid_arr=[],$seller_flag=0,$qz_flag=0,
                                                $teacher_type,$lesson_hold_flag_adminid  =-1,$is_quit=-1 ,$set_leave_flag=-1,
                                                $fulltime_flag=-1,$seller_hold_flag=-1,$teacher_ref_type=-1,$have_wx=-1,
                                                $grade_plan=-1,$subject_plan=-1,$fulltime_teacher_type=-1
    ){
        $where_arr = array(
            array( "t.teacherid=%u", $teacherid, -1 ),
            array( "t.teacher_money_type=%u", $teacher_money_type, -1 ),
            array( "t.level=%u", $level, -1 ),
            array( "t.is_good_flag=%u", $is_good_flag, -1 ),
            array( "t.need_test_lesson_flag=%u ", $need_test_lesson_flag, -1 ),
            array( "t.gender=%u ", $gender, -1 ),
            array( "t.grade_part_ex=%u ", $grade_part_ex, -1 ),
            array( "t.subject=%u ", $subject, -1 ),
            array( "t.second_subject=%u ", $second_subject, -1 ),
            array( "t.is_test_user=%u ", $is_test_user, -1 ),
            array( "t.is_freeze=%u ", $is_freeze, -1 ),
            array( "t.limit_plan_lesson_type=%u ", $limit_plan_lesson_type, -1 ),
            array( "t.train_through_new=%u ", $train_through_new, -1 ),
            array( "t.lesson_hold_flag=%u ", $lesson_hold_flag, -1 ),
            array( "t.teacher_type=%u ", $teacher_type, -1 ),
            array( "t.is_record_flag=%u ", $is_record_flag, -1 ),
            array( "t.is_quit=%u ", $is_quit, -1 ),
            array( "t.lesson_hold_flag_adminid=%u ", $lesson_hold_flag_adminid, -1 ),
            array( "m.fulltime_teacher_type=%u ", $fulltime_teacher_type, -1 ),
        );

        if($teacher_ref_type==-2){
            $where_arr[]="t.teacher_ref_type>0";
        }else{
            $where_arr[]=["t.teacher_ref_type=%u",$teacher_ref_type,-1];
        }

        if ($address) {
            $address=$this->ensql($address);
            $where_arr[]="(t.address like '%%".$address."%%' or t.school like '%%".$address."%%' or t.nick like '%%".$address."%%' "
                ." or t.realname like '%%".$address."%%' or t.phone like '%%".$address."%%' or t.tea_note like '%%".$address."%%' "
                ." or t.user_agent like '%%".$address."%%' or t.teacher_tags like '%%".$address."%%' "
                ." or t.teacher_textbook like '%%".$address."%%' or t.teacherid like '%%".$address."%%' "
                ." or t.email like '%%".$address."%%')";
        }


        $where_arr[]= $this->where_get_not_in_str("t.teacherid",  $teacherid_arr);
        $where_arr[]= $this->where_get_subject_grade_str($grade_plan,$subject_plan);

        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
            }
        }

        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }elseif($fulltime_flag==1){
            $where_arr[] = "m.account_role=5";
        }
        if($is_new_teacher ==2){
            $where_arr[] = "t.create_time =(select max(create_time) from db_weiyi.t_teacher_info)";
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

        if($trial_flag ==1){
            $where_arr[] = "t.trial_lecture_is_pass=1";
        }
        if($test_flag ==1){
            $where_arr[] = "(t.nick not like '%%测试%%' and  t.nick not like '%%test%%')";
        }
        $hh = "";
        if($test_lesson_full_flag ==1){
            $hh = "having (sum(tss.lessonid>0)>=8)";
        }else if($test_lesson_full_flag ==2){
            $hh = "having (sum(tss.lessonid>0)<8 or sum(tss.lessonid >0) is null)";
        }else{
            $hh = "";
        }
        if($test_transfor_per ==1){
            $where_arr[] = "t.test_transfor_per <10";
        }else if($test_transfor_per==2){
            $where_arr[] = "t.test_transfor_per >=10 and t.test_transfor_per <20";
        }else if($test_transfor_per==3){
            $where_arr[] = "t.test_transfor_per >=20";
        }
        if($week_liveness ==1){
            $where_arr[] = "t.week_liveness <5";
        }else if($week_liveness ==2){
            $where_arr[] = "t.week_liveness >=5 and t.week_liveness <10";
        }elseif($week_liveness ==3){
            $where_arr[] = "t.week_liveness >=10 and t.week_liveness <15";
        }else if($week_liveness ==4){
            $where_arr[] = "t.week_liveness >=15 and t.week_liveness <20";
        }else if($week_liveness ==5){
            $where_arr[] = "t.week_liveness >=20 ";
        }
        if($interview_score==1){
            $where_arr[] = "t.interview_score >=60 and t.interview_score <80";
        }elseif($interview_score==2){
            $where_arr[] = "t.interview_score >=80 and t.interview_score <90";
        }elseif($interview_score==3){
            $where_arr[] = "t.interview_score >=90";
        }
        if($second_interview_score==1){
            $where_arr[] = "t.second_interview_score >=60 and t.second_interview_score <80";
        }elseif($interview_score==2){
            $where_arr[] = "t.second_interview_score >=80 and t.second_interview_score <90";
        }elseif($interview_score==3){
            $where_arr[] = "t.second_interview_score >=90";
        }
        if($seller_flag==1){
            $where_arr[] = "t.is_good_flag=1 and t.change_good_time>0 and t.is_good_wx_flag=1 ";
        }
        if($set_leave_flag==0){
            $where_arr[] ="t.leave_start_time=0";
        }elseif($set_leave_flag==1){
            $where_arr[] ="t.leave_start_time>0";
        }

        if($seller_hold_flag==1){
            $where_arr[] ="t.is_freeze=0 and (t.limit_plan_lesson_type=0 or t.limit_plan_lesson_type>1)";
        }

        if($have_wx==0){
            $where_arr[] ="(t.wx_openid = '' or t.wx_openid is null )";
        }elseif($have_wx==1){
            $where_arr[] ="t.wx_openid <> ''";
        }

        $sql = $this->gen_sql_new("select t.wx_openid,t.need_test_lesson_flag,t.nick,t.realname, t.teacher_type,"
                                  ." t.gender,t.teacher_money_type,t.identity,t.is_test_user,t.add_acc,"
                                  ." t.train_through_new, t.train_through_new_time,t.phone_spare,"
                                  ." t.birth,t.phone,t.email,t.rate_score,t.teacherid,t.user_agent,"
                                  ." t.teacher_tags,t.teacher_textbook,t.wx_use_flag,"
                                  ." t.create_meeting,t.level,t.work_year,t.advantage,t.base_intro,t.textbook_type,t.is_good_flag,"
                                  ." t.create_time,t.address,t.subject,t.second_subject,t.third_subject,t.school,t.tea_note,"
                                  ." t.grade_part_ex,t.is_freeze,t.freeze_reason,t.freeze_adminid,t.freeze_time, "
                                  ." t.limit_plan_lesson_type,t.limit_plan_lesson_reason,t.limit_plan_lesson_time,"
                                  ." t.limit_plan_lesson_account,t.second_grade,t.third_grade,t.interview_access,"
                                  ." t.lesson_hold_flag,t.lesson_hold_flag_acc,t.research_note ,"
                                  ." t.lesson_hold_flag_time,t.interview_score,t.second_interview_score, "
                                  ." t.test_transfor_per,t.week_liveness,t.limit_day_lesson_num,t.limit_week_lesson_num,"
                                  ." t.limit_month_lesson_num,t.teacher_ref_type,t.saturday_lesson_num,t.grade_start,t.grade_end, "
                                  ." t.not_grade,t.not_grade_limit,t.week_lesson_count,t.trial_lecture_is_pass,"
                                  ." sum(tss.lessonid >0) week_lesson_num,t.is_quit,t.part_remarks,"
                                  ." if(t.limit_plan_lesson_type>0,t.limit_plan_lesson_type-sum(tss.lessonid >0),"
                                  ." t.limit_week_lesson_num-sum(tss.lessonid >0)) left_num,"
                                  ." t.idcard,t.bankcard,t.bank_address,t.bank_account,t.bank_phone,t.bank_type, "
                                  ." t.bank_province,t.bank_city"
                                  ." from %s t"
                                  ." left join %s m on t.phone = m.phone"
                                  ." left join %s l on (t.teacherid = l.teacherid"
                                  ." and l.lesson_type=2 and l.lesson_del_flag =0 and l.lesson_start >= %u and l.lesson_end < %u)"
                                  ." left join %s tss on (l.lessonid= tss.lessonid and tss.success_flag in(0,1))"
                                  ." where %s "
                                  ." group by t.teacherid %s"
                                  ." order by t.have_test_lesson_flag asc,t.train_through_new_time desc,left_num desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$lstart
                                  ,$lend
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$hh
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function get_all_usefull_teacher_list($page_num,$teacherid_arr,$subject,$grade,$lstart,$lend){
        $where_arr=[
            ["t.subject=%u",$subject,-1],
            "t.train_through_new=1",
            "t.trial_lecture_is_pass=1",
            "t.is_test_user=0",
            "t.is_quit=0",
            "t.realname not like '%%测试%%' and  t.realname not like '%%test%%'",
            "t.not_grade not like '%%".$grade."%%' ",
            "(t.leave_end_time <".$lstart." or t.leave_start_time>".$lend.")"
        ];
        $where_arr[]= $this->where_get_not_in_str("t.teacherid",  $teacherid_arr);
        if($grade >=101 and $grade <=103){
            $where_arr[] = "(t.grade_part_ex in (1,4) or (t.grade_start>0 and t.grade_start <=1))";
        }elseif($grade >=104 and $grade <=105){
            $where_arr[] = "(t.grade_part_ex in (1,4) or (t.grade_start>0 and t.grade_start <=2 and t.grade_end >=2))";
        }elseif($grade==106){
            $where_arr[] = "(t.grade_part_ex in (1,4,6) or (t.grade_start>0 and t.grade_start <=2 and t.grade_end >=2))";
        }elseif($grade >=201 and $grade <=202){
            $where_arr[] = "(t.grade_part_ex in (2,4,5,6) or (t.grade_start>0 and t.grade_start <=3 and t.grade_end >=3))";
        }elseif($grade==203){
            $where_arr[] = "(t.grade_part_ex in (2,4,5,6,7) or (t.grade_start>0 and t.grade_start <=4 and t.grade_end >=4))";
        }elseif($grade >=301 and $grade <=302){
            $where_arr[] = "(t.grade_part_ex in (3,5,7) or (t.grade_start>0 and t.grade_start <=5 and t.grade_end >=5))";
        }else if($grade==303){
            $where_arr[] = "(t.grade_part_ex in (3,5,7) or (t.grade_start>0 and t.grade_start <=6 and t.grade_end >=6))";
        }else{
            $where_arr[] = false;
        }

        $sql = $this->gen_sql_new("select t.wx_openid,need_test_lesson_flag,t.nick,realname, t.teacher_type,"
                                  ." t.gender,t.teacher_money_type,t.identity,t.is_test_user,"
                                  ." t.train_through_new, t.train_through_new_time,"
                                  ." birth, t.phone, t.email, rate_score, t.teacherid ,user_agent,teacher_tags,teacher_textbook,"
                                  ." create_meeting, t.level ,work_year,  advantage, base_intro,textbook_type,is_good_flag,"
                                  ." t.create_time,t.address,t.subject,second_subject,third_subject,t.school,tea_note,"
                                  ." grade_part_ex,t.is_freeze,freeze_reason,freeze_adminid,freeze_time, "
                                  ." t.limit_plan_lesson_type,t.limit_plan_lesson_reason,t.limit_plan_lesson_time,"
                                  ." t.limit_plan_lesson_account,t.second_grade,t.third_grade,interview_access"
                                  ." lesson_hold_flag,lesson_hold_flag_acc,research_note ,"
                                  ." lesson_hold_flag_time,interview_score,second_interview_score, "
                                  ." test_transfor_per ,week_liveness,limit_day_lesson_num,limit_week_lesson_num,"
                                  ." limit_month_lesson_num ,t.teacher_ref_type,t.saturday_lesson_num,grade_start,grade_end, "
                                  ." not_grade,not_grade_limit,week_lesson_count,t.trial_lecture_is_pass,"
                                  ." sum(tss.lessonid >0) week_lesson_num,"
                                  ." if(limit_plan_lesson_type>0,limit_plan_lesson_type-sum(tss.lessonid >0),limit_week_lesson_num-sum(tss.lessonid >0)) left_num "
                                  ." from %s t"
                                  ." left join %s l on (t.teacherid = l.teacherid "
                                  ." and l.lesson_type=2 and l.lesson_del_flag =0 and l.lesson_start >= %u and l.lesson_end < %u)"
                                  ." left join %s tss on (l.lessonid= tss.lessonid and tss.success_flag in(0,1))"
                                  ." where %s "
                                  ." group by t.teacherid having(left_num>0) "
                                  ." order by t.have_test_lesson_flag asc,t.train_through_new_time desc,left_num desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$lstart
                                  ,$lend
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);


    }

    public function get_usefull_teacher_list($teacherid_arr,$subject,$grade,$lstart,$lend){
        $where_arr=[
            ["t.subject=%u",$subject,-1],
            "t.train_through_new=1",
            "t.trial_lecture_is_pass=1",
            "t.is_test_user=0",
            "t.is_quit=0",
            "t.realname not like '%%测试%%' and  t.realname not like '%%test%%'",
            "t.not_grade not like '%%".$grade."%%' ",
            "(t.leave_end_time <".$lstart." or t.leave_start_time>".$lend.")"
        ];
        $where_arr[]= $this->where_get_not_in_str("t.teacherid",  $teacherid_arr);
        if($grade >=101 and $grade <=103){
            $where_arr[] = "(t.grade_part_ex in (1,4) or (t.grade_start>0 and t.grade_start <=1))";
        }elseif($grade >=104 and $grade <=105){
            $where_arr[] = "(t.grade_part_ex in (1,4) or (t.grade_start>0 and t.grade_start <=2 and t.grade_end >=2))";
        }elseif($grade==106){
            $where_arr[] = "(t.grade_part_ex in (1,4,6) or (t.grade_start>0 and t.grade_start <=2 and t.grade_end >=2))";
        }elseif($grade >=201 and $grade <=202){
            $where_arr[] = "(t.grade_part_ex in (2,4,5,6) or (t.grade_start>0 and t.grade_start <=3 and t.grade_end >=3))";
        }elseif($grade==203){
            $where_arr[] = "(t.grade_part_ex in (2,4,5,6,7) or (t.grade_start>0 and t.grade_start <=4 and t.grade_end >=4))";
        }elseif($grade >=301 and $grade <=302){
            $where_arr[] = "(t.grade_part_ex in (3,5,7) or (t.grade_start>0 and t.grade_start <=5 and t.grade_end >=5))";
        }else if($grade==303){
            $where_arr[] = "(t.grade_part_ex in (3,5,7) or (t.grade_start>0 and t.grade_start <=6 and t.grade_end >=6))";
        }else{
            $where_arr[] = false;
        }

        $sql = $this->gen_sql_new("select t.wx_openid,need_test_lesson_flag,t.nick,realname, t.teacher_type,"
                                  ." t.gender,t.teacher_money_type,t.identity,t.is_test_user,"
                                  ." t.train_through_new, t.train_through_new_time,"
                                  ." birth, t.phone, t.email, rate_score, t.teacherid ,user_agent,teacher_tags,teacher_textbook,"
                                  ." create_meeting, t.level ,work_year,  advantage, base_intro,textbook_type,is_good_flag,"
                                  ." t.create_time,t.address,t.subject,second_subject,third_subject,t.school,tea_note,"
                                  ." grade_part_ex,t.is_freeze,freeze_reason,freeze_adminid,freeze_time, "
                                  ." t.limit_plan_lesson_type,t.limit_plan_lesson_reason,t.limit_plan_lesson_time,"
                                  ." t.limit_plan_lesson_account,t.second_grade,t.third_grade,interview_access"
                                  ." lesson_hold_flag,lesson_hold_flag_acc,research_note ,"
                                  ." lesson_hold_flag_time,interview_score,second_interview_score, "
                                  ." test_transfor_per ,week_liveness,limit_day_lesson_num,limit_week_lesson_num,"
                                  ." limit_month_lesson_num ,t.teacher_ref_type,t.saturday_lesson_num,grade_start,grade_end, "
                                  ." not_grade,not_grade_limit,week_lesson_count,t.trial_lecture_is_pass,"
                                  ." sum(tss.lessonid >0) week_lesson_num,"
                                  ." if(limit_plan_lesson_type>0,limit_plan_lesson_type-sum(tss.lessonid >0),limit_week_lesson_num-sum(tss.lessonid >0)) left_num "
                                  ." from %s t"
                                  ." left join %s l on (t.teacherid = l.teacherid "
                                  ." and l.lesson_type=2 and l.lesson_del_flag =0 and l.lesson_start >= %u and l.lesson_end < %u)"
                                  ." left join %s tss on (l.lessonid= tss.lessonid and tss.success_flag in(0,1))"
                                  ." where %s "
                                  ." group by t.teacherid having(left_num>0) "
                                  ." order by t.have_test_lesson_flag asc,t.train_through_new_time desc,left_num desc limit 10 "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$lstart
                                  ,$lend
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);


    }

    public function get_teacher_nick($teacher)
    {
        $sql=$this->gen_sql("select nick from %s where teacherid = %u ",
                            self::DB_TABLE_NAME,
                            $teacher
        );
        return $this->main_get_value($sql);
    }

    public function get_level_by_teacherid($teacherid)
    {
        $sql=$this->gen_sql("select level from %s where teacherid = %u ",
                            self::DB_TABLE_NAME,
                            $teacherid
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_info($teacherid){
        $sql = $this->gen_sql("select teacherid,subject,teacher_money_type,level,wx_openid,nick,phone,email,"
                              ." teacher_type,teacher_ref_type,create_time,identity,grade_start,grade_end,subject,phone,realname,"
                              ." gender,birth,address,face,grade_part_ex,bankcard,"
                              ." train_through_new,trial_lecture_is_pass,wx_use_flag"
                              ." from %s "
                              ." where teacherid=%u"
                              ,self::DB_TABLE_NAME
                              ,$teacherid
        );
        return $this->main_get_row($sql);
    }

    public function get_every_teacherid(){
        $sql=$this->gen_sql_new("select distinct(teacherid) as userid from %s"
                                ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_all_list() {
        $sql =$this->gen_sql_new("select teacherid from %s", self::DB_TABLE_NAME  );
        return $this->main_get_list($sql);
    }

    public function get_teacher_list_new() {
        $sql =$this->gen_sql_new("select teacherid,nick from %s", self::DB_TABLE_NAME  );
        return $this->main_get_list($sql);
    }

    public function send_template_msg($teacherid,$template_id,$data,$url="http://wx-teacher.leo1v1.com"){
        if (substr($url,0,7 )!= "http://" ) {
            $url="http://wx.teacher-wx.leo1v1.com/".trim($url,"/ \t");
        }
        \App\Helper\Utils::logger("WX URL $url");

        $wx=new \App\Helper\Wx();
        $openid=$this->get_wx_openid($teacherid);
        if ($openid) {
            $ret=$wx->send_template_msg($openid,$template_id,$data ,$url);
        }else{
            \App\Helper\Utils::logger("NO BIND ERR:%u", $teacherid );
            return false;
        }
        if (!$ret) {
            \App\Helper\Utils::logger("SEND ERR:%u", $teacherid );
        }
        return $ret;
    }

    public function get_teacher_info_all($teacherid){
        $sql = $this->gen_sql_new("select * from %s where teacherid = %u"
                                  ,self::DB_TABLE_NAME
                                  ,$teacherid
        );
        return $this->main_get_row($sql);
    }

    public function get_teacher_info_all_by_page($teacherid,$create_time,$page_num,$subject){
        $where_arr = [
            ["i.teacherid = %u",$teacherid,-1],
            ["m.create_time = %u",$create_time,-1],
            ["i.subject = %u",$subject,-1]
        ];
        $sql =$this->gen_sql_new("select i.teacherid,nick,join_info".
                                 " from %s i left join %s m on i.teacherid = m.teacherid".
                                 " where %s ",
                                 self::DB_TABLE_NAME,
                                 t_teacher_meeting_join_info::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_all_teacher_info_new($gender,$nick_phone,$page_num){
        $where_arr = array(
            array( "gender=%d", $gender, -1 )
        );
        if ($nick_phone!=""){
            $where_arr[]=array( "(nick like '%%%s%%' or  realname like '%%%s%%' or  phone like '%%%s%%' )",
                                array(
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone)));
        }


        $sql = $this->gen_sql_new("select * from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function tongji_get_test_lesson_info($page_num,$start_time,$end_time ,$test_lesson_flag,$l_1v1_flag ,$tutor_subject)  {
        $where_arr=[
            ["tutor_subject=%d", $tutor_subject, -1  ],
        ];
        $this->where_arr_add_time_range($where_arr,"create_time" ,$start_time,$end_time);
        $having_arr=[ ];
        if ($test_lesson_flag ==0) {
            $having_arr[]=" (sum(lesson_type=2) =0 or sum(lesson_type=2) is null  )";
        }else if ($test_lesson_flag ==1) {
            $having_arr[]=" sum(lesson_type=2) >0 ";
        }

        if ($l_1v1_flag ==0) {
            $having_arr[]=" (sum( lesson_type in (0,1,3)) =0 or sum(lesson_type in (0,1,3) )   is null  )";
        }else if ($l_1v1_flag==1) {
            $having_arr[]=" sum(lesson_type in ( 0,1,3 )) >0 ";
        }






        $sql=$this->gen_sql_new(
            "select t.teacherid, nick,create_time, tutor_subject, sum(lesson_type=2) as test_lesson_count , sum(lesson_type in (0,1,3) )  l_1v1_count "
            ."from %s t "
            ."left join %s l on l.teacherid = t.teacherid  "
            ."where %s   group by  t.teacherid having %s ",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr, $having_arr);
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function check_teacher_phone($phone){
        $where_arr = [
            ["phone like '%%%s%%'",$phone,""],
        ];
        $sql = $this->gen_sql_new("select teacherid from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacherid_by_realname($realname){
        $time = strtotime("2016-12-21 14:00:00");
        $sql  = $this->gen_sql_new("select realname,l.teacherid,set_lesson_adminid,m.account,set_lesson_time,success_flag "
                                   ." from %s tts "
                                   ." join %s t on tts.require_id =t.require_id "
                                   ." join %s l on tts.lessonid = l.lessonid "
                                   ." join %s tt on l.teacherid = tt.teacherid "
                                   ." join %s m on tts.set_lesson_adminid = m.uid "
                                   ." where realname = '%s' "
                                   ." and set_lesson_time >=%u"
                                   ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                   ,t_test_lesson_subject_require::DB_TABLE_NAME
                                   ,t_lesson_info::DB_TABLE_NAME
                                   ,self::DB_TABLE_NAME
                                   ,t_manager_info::DB_TABLE_NAME
                                   ,$realname
                                   ,$time
        );
        return $this->main_get_list($sql);
    }

    public function get_teacherid_by_phone($phone){
        $where_arr=[
            ["phone='%s'",$phone,0]
        ];

        $sql = $this->gen_sql_new("select teacherid from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacherid_by_name($name){
        $sql=$this->gen_sql_new("select teacherid from %s where realname='%s'"
                                ,self::DB_TABLE_NAME
                                ,$name
        );
        return $this->main_get_value($sql);
    }

    public function get_teacherid_by_adminid($adminid){
        $sql=$this->gen_sql_new("select teacherid "
                                ." from %s t join %s m on t.phone = m.phone "
                                ." where m.uid = %u"
                                ,self::DB_TABLE_NAME
                                ,t_manager_info::DB_TABLE_NAME
                                ,$adminid
        );
        return $this->main_get_value($sql);
    }


    public function send_wx_todo_msg($teacherid, $from_user, $header_msg,$msg,$url="",$desc=""){
        $wx = new \App\Helper\Wx();
        // $template_id="1600puebtp9CfcIg41Oz9VHu6iRXHAJ8VpHKPYvZXT0";
        $template_id="9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        $ret= $this->send_template_msg($teacherid,$template_id,[
            "first"    => $header_msg,
            "keyword1" =>'通知人:'.$from_user,
            "keyword2" => $msg,
            "keyword3" => date("Y-m-d H:i:s"),
            "remark"   => $desc,
        ],$url);
        return $ret;
    }

    public function add_teacher_info_to_ejabberd($teacherid,$passwd){
        $sql = $this->gen_sql_new("insert into %s (username,password) values('%s','%s')"
                                  ,\App\Models\Zgen\z_users::DB_TABLE_NAME
                                  ,$teacherid
                                  ,$passwd
        );
        return $this->main_insert($sql);
    }

    public function get_freeze_teacher_info($start_time){
        $where_arr=[
            ["freeze_time>= %u",$start_time,-1],
            "freeze_time <= ".time()
        ];
        $sql = $this->gen_sql_new("select teacherid,nick,subject from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }
    public function get_un_freeze_teacher_info($start_time){
        $where_arr=[
            ["un_freeze_time>= %u",$start_time,-1],
            "un_freeze_time <= ".time()
        ];
        $sql=$this->gen_sql_new("select teacherid,nick,subject from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_limit_plan_lesson_info($start_time){
        $where_arr=[
            ["limit_plan_lesson_time>= %u",$start_time,-1],
            "limit_plan_lesson_time <= ".time(),
            "limit_plan_lesson_type <> 0"
        ];
        $sql=$this->gen_sql_new("select teacherid,nick,subject from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }
    public function get_un_limit_plan_lesson_info($start_time){
        $where_arr=[
            ["limit_plan_lesson_time>= %u",$start_time,-1],
            "limit_plan_lesson_time <= ".time(),
            "limit_plan_lesson_type = 0"
        ];
        $sql=$this->gen_sql_new("select teacherid,nick,subject from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }


    public function get_freeze_teacher_num($time){
        $where_arr=[
            ["freeze_time>= %u",$time,-1],
            "freeze_time <= ".time(),
            "teacherid<>50518",
            "freeze_adminid <> 72 and freeze_adminid  <> 349",
        ];
        $sql=$this->gen_sql_new("select count(distinct teacherid) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_phone_by_nick($nick){
        $sql = $this->gen_sql_new("select phone from %s"
                                  ." where nick like '%%%s%%'"
                                  ,self::DB_TABLE_NAME
                                  ,$nick
        );
        return $this->main_get_value($sql);
    }

    public function get_reference_list($id,$gender,$nick_phone,$page_num){
        $where_arr = [
            ["gender=%d",$gender,-1],
            ["teacherid=%d",$id,-1],
        ];
        if($nick_phone != ''){
            $where_arr[] = [
                "t.nick like '%%%s%%' or t.realname like '%%%s%%' or t.phone like '%%%s%%' ",[$nick_phone,$nick_phone,$nick_phone]
            ];
        }else{
            $where_arr=" true ";
        }

        $sql=$this->gen_sql_new("select t.teacherid as id,t.nick,t.phone,gender,t.realname"
                                ." from %s t"
                                ." where %s"
                                ." and exists (select 1 "
                                ." from %s"
                                ." where reference=t.phone"
                                ." )"
                                ." order by t.teacherid desc"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
                                ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_teacher_info_by_phone($phone){
        $where_arr = [
            ["phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select teacherid,nick,realname,subject,second_subject,third_subject,grade_part_ex, "
                                  ." grade_start,grade_end,not_grade,teacher_ref_type,teacher_type,phone, "
                                  ." second_grade_start,second_grade_end,second_not_grade,wx_openid,wx_use_flag,"
                                  ." trial_lecture_is_pass,train_through_new,email,school,"
                                  ." identity,bankcard,teacher_money_type,level,interview_access"
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_subject_grade_per_info(){
        $where_arr = [
            "nick not like '%%test%%' and realname not like '%%test%%' and realname not like '%%测试%%' and nick not like '%%测试%%'"
        ];

        $sql = $this->gen_sql_new("select realname,subject,grade_part_ex".
                                  " from %s where trial_lecture_is_pass =1 and subject >0 and grade_part_ex<= 0",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
    public function get_second_subject_grade_per_info(){
        $where_arr = [
            "nick not like '%%test%%' and realname not like '%%test%%' and realname not like '%%测试%%' and nick not like '%%测试%%'"
        ];

        $sql = $this->gen_sql_new("select realname,subject,second_subject".
                                  " from %s where trial_lecture_is_pass =1 and second_subject >0 ",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function update_teacher_money_type_new($time){
        $where_arr=[
            "t.create_time >=".$time,
            "tt.realname like '%%廖老师工作室%%'"
        ];
        $sql = $this->gen_sql_new("update %s t left join %s ta on t.phone=ta.phone left join %s tt on ta.reference=tt.phone SET t.teacher_money_type=5 where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_update($sql);
    }

    public function get_third_subject_teacher_list(){
        $where_arr = [
           "third_subject>0"
        ];

        $sql = $this->gen_sql_new("select realname,subject,second_subject,third_subject".
                                  " from %s where %s and trial_lecture_is_pass =1 ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_tea_info_by_subject_and_identity($subject,$identity,$grade_part_ex){
        $where_arr = [
            ["grade_part_ex=%u",$grade_part_ex,-1],
            ["identity=%u",$identity,-1]
        ];
        if($subject>0){
            $where_arr[]="(subject=".$subject." or second_subject =".$subject." or third_subject = ".$subject.")";
        }
        $sql = $this->gen_sql_new("select teacherid".
                                  " from %s where %s and trial_lecture_is_pass =1 ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        $arr =  $this->main_get_list($sql);
        $tea = [];
        foreach($arr as $item){
            $tea[]=$item["teacherid"];
        }
        return $tea;

    }

    public function get_tea_info_by_create_type($create_type,$teacherid_list){
        $where_arr=[];
        $this->where_arr_teacherid($where_arr,"t.teacherid", $teacherid_list);
        if($create_type==1){
            $where_arr[]="t.create_time >".(time()-86400*7);
        }else if($create_type==2){
            $where_arr[]="t.create_time >".(time()-86400*30);
        }
        $sql = $this->gen_sql_new("select t.teacherid,sum(l.lessonid >0) lesson_count".
                                  " from %s t" .
                                  " join %s l on (t.teacherid= l.teacherid and l.lesson_type=2 and l.lesson_del_flag=0 )".
                                  " join %s tts on (tts.lessonid=l.lessonid and tts.success_flag in (0,1))".
                                  "where %s having (lesson_count >0)",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        $arr =  $this->main_get_list($sql);
        $tea = [];
        foreach($arr as $item){
            $tea[]=$item["teacherid"];
        }
        return $tea;
    }

    public function get_tea_info_by_tea_qua($tea_qua,$tea_create){
        $where_arr=[];
        $this->where_arr_teacherid($where_arr,"t.teacherid", $tea_create);
        if($tea_qua==1){
            $sql = $this->gen_sql_new("select teacherid".
                                      " from %s t where %s and is_freeze =1 ",
                                      self::DB_TABLE_NAME,
                                      $where_arr
            );

        }else if($tea_qua==2){
            $sql = $this->gen_sql_new("select teacherid".
                                      " from %s t where %s and limit_plan_lesson_type >0 ",
                                      self::DB_TABLE_NAME,
                                      $where_arr
            );

        }else if( $tea_qua==3){
            $sql = $this->gen_sql_new("select distinct t.teacherid".
                                      " from %s t".
                                      " join %s tr on t.teacherid = tr.teacherid".
                                       " where %s ",
                                      self::DB_TABLE_NAME,
                                      t_teacher_record_list::DB_TABLE_NAME,
                                      $where_arr
            );

        }
        $arr =  $this->main_get_list($sql);
        $tea = [];
        foreach($arr as $item){
            $tea[]=$item["teacherid"];
        }
        return $tea;
    }

    public function get_week_freeze_teacher_list($start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr,"week_freeze_time",$start_time,$end_time);
        $where_arr[] = "is_week_freeze=1";
        $sql = $this->gen_sql_new("select teacherid,realname from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_need_join_train_teacher_list($type=1,$lessonid,$subject,$grade_part_ex,$create_time,$min_per,$max_per
                                                     ,$is_test_user=0,$has_limit=-1,$is_freeze=-1
    ){
        if($type==1){
            $end_time   = time();
            $start_time = strtotime("-2 month",$end_time);
            $where_arr  = [
                ["create_time>%u",$start_time,0],
                ["create_time<%u",$end_time,0],
                ["is_test_user=%u",$is_test_user,-1],
                "trial_lecture_is_pass=1",
                "train_through_new_time=0",
                "t.teacher_type !=3",
            ];
        }elseif($type==2){
            $where_arr = [
                ["subject in (%s)",$subject,""],
                ["grade_part_ex in (%s)",$grade_part_ex,""],
                ["create_time>%u",$create_time,0],
                ["test_transfor_per<%s",$min_per,""],
                ["test_transfor_per>%s",$max_per,""],
                ["is_test_user=%u",$is_test_user,-1],
                ["is_freeze=%u",$is_freeze,-1],
            ];
            if($has_limit==0){
                $where_arr[]="limit_plan_lesson_type=0";
            }elseif($has_limit>0){
                $where_arr[]="limit_plan_lesson_type>0";
            }
        }
        $sql = $this->gen_sql_new("select t.teacherid "
                                  ." from %s t"
                                  ." where %s"
                                  ." and not exists ("
                                  ." select 1 from %s where lessonid=%u and t.teacherid=userid"
                                  ." )"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }
    public function get_is_week_freeze_info(){
        $sql = $this->gen_sql_new("select * from %s where is_week_freeze=1",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_teacher_student_normal_leson_info_new($teacherid){
        $where_arr=[
            "l.lesson_type=0 and l.lesson_del_flag=0",
            "s.type=0",
            "t.teacherid=".$teacherid
        ];
        $sql = $this->gen_sql_new("select distinct s.nick,s.lesson_count_left  from %s t"
                                  ." left join %s l on t.teacherid=l.teacherid "
                                  ." left join %s s on l.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_week_lesson_count_list($start_time,$end_time){
        $where_arr=[
            "l.lesson_type in (0,2)",
            "l.lesson_del_flag = 0",
            "l.confirm_flag in (0,1)",
            "(tss.success_flag in (0,1) or tss.success_flag is null)",
            "t.trial_lecture_is_pass =1"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.teacherid,sum(l.lesson_count) lesson_count_total"
                                  ." from %s t left join %s l on t.teacherid=l.teacherid "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." where %s group by t.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_test_per_list($start_time,$end_time){
        $where_arr=[
            "l.lesson_type =2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1) ",
            "t.trial_lecture_is_pass =1",
            "ts.require_admin_type=2"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.teacherid,count(distinct c.userid,c.teacherid,c.subject) order_number,"
                                  ." count(distinct l.lessonid) success_lesson"
                                  ." from %s t left join %s l on t.teacherid=l.teacherid "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." where %s group by t.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_free_limit_teacher_test_per_list($start_time,$end_time){
        $where_arr=[
            "l.lesson_type =2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1) ",
            "t.trial_lecture_is_pass =1",
            "ts.require_admin_type=2",
            "t.limit_plan_lesson_type >0"
        ];
        $end = time()-3*86400;
        //$this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select limit_plan_lesson_type,count(distinct c.userid,c.teacherid,c.subject) order_number,"
                                  ." count(distinct l.lessonid) success_lesson"
                                  ." from %s t left join %s l on t.teacherid=l.teacherid "
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id "
                                  ." where %s and l.lesson_start>t.limit_plan_lesson_time and l.lesson_start<%u group by limit_plan_lesson_type ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr,
                                  $end
        );
        return $this->main_get_list($sql);
    }


    public function get_interview_score_info(){
        $sql = $this->gen_sql_new("select teacherid,teacher_lecture_score from %s t"
                                  ." left join %s tl on t.phone=tl.phone and t.second_subject=tl.subject and tl.add_time = (select max(add_time) from %s where phone=t.phone and subject=t.second_subject ) "
                                  ." where teacher_lecture_score is not null and teacher_lecture_score>0 group by teacherid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_all_teacherid_list(){

        $sql = $this->gen_sql_new("select teacherid,wx_openid  from %s where wx_openid  is not null",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_limit_lesson_num_change_list(){
        $sql = $this->gen_sql_new("select teacherid,realname,limit_week_lesson_num   from %s where limit_week_lesson_num <>8",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);

    }

    public function get_textbook_list_no(){
        $sql = $this->gen_sql_new("select distinct teacher_textbook from %s where textbook_type =0",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);

    }

    public function get_no_test_lesson_teacher_list($time){
        $where_arr=[
            "l.lesson_type =2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1) ",
            "t.trial_lecture_is_pass =1",
            "t.lesson_hold_flag = 0",
            "(t.lesson_hold_flag_acc ='' or t.lesson_hold_flag_acc ='system')"
        ];
        $sql= $this->gen_sql_new("select max(l.lesson_start) lesson_start_time,t.teacherid"
                                 ." from %s t left join %s l on t.teacherid = l.teacherid"
                                 ." left join %s tss on tss.lessonid = l.lessonid"
                                 ." where %s group by t.teacherid having(lesson_start_time <%u)",
                                 self::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 $where_arr,
                                 $time
        );
        return $this->main_get_list($sql);
    }

    public function get_train_through_teacher_info($time,$subject=-1,$subject_str=""){
        $where_arr=[
            ["subject = %u",$subject,-1],
            "train_through_new_time>".$time,
            "assign_jw_adminid =0",
             "train_through_new=1",
            "is_test_user=0"
        ];
        if($subject_str){
            $where_arr[] = "subject in".$subject_str;
        }
        $sql = $this->gen_sql_new("select teacherid from %s where %s limit 1",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_teacher_test_lesson_info_by_time($page_num,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$qzls_flag=-1,$fulltime_flag=-1,$create_now=-1,$start_time=-1,$end_time=-1,$fulltime_teacher_type=-1){
        $where_arr=[
            ["t.teacherid=%u",$teacherid,-1],
            ["t.subject=%u",$teacher_subject,-1],
            ["t.identity=%u",$identity,-1],
            ["tt.teacherid = %u",$teacher_account,-1],
            ["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
            "t.trial_lecture_is_pass =1",
        ];

        if($qz_flag==1){
            $where_arr[] = "m.account_role=5";
            $where_arr[] = "m.del_flag=0";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
            }
        }
        if($create_now==1){
            $where_arr[]=["t.create_time>%u",$start_time,-1];
            $where_arr[]=["t.create_time<%u",$end_time,-1];
        }

        if($qzls_flag==1){
            $where_arr[] = "(m.account_role<>5 or m.account_role is null)";
        }else if($qzls_flag==2){
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
        $sql = $this->gen_sql_new("select t.teacherid,t.realname,t.train_through_new_time,t.identity,t.interview_access "
                                  ." ,t.school,t.is_freeze,tl.account,t.limit_plan_lesson_time,t.limit_plan_lesson_type "
                                  ." ,t.limit_plan_lesson_account,t.limit_plan_lesson_reason,t.grade_part_ex,t.second_grade, "
                                  ." t.freeze_time,t.freeze_reason,t.freeze_adminid,mm.account freeze_account,m.account_role  "
                                  ." ,t.not_grade_limit,t.not_grade"
                                  ." from %s t left join %s m on t.phone=m.phone"
                                  ." left join %s tl on (t.phone=tl.phone and t.subject = tl.subject and tl.status = 1)"
                                  ." left join %s mm on t.freeze_adminid= mm.uid"
                                  ." left join %s mmm on mmm.account = tl.account"
                                  ." left join %s tt on mmm.phone=tt.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,50);
    }

    public function get_assign_jw_adminid_info($page_num,$adminid,$teacherid,$grade_part_ex,$subject,$second_subject,$identity,$jw_adminid,$class_will_type,$have_lesson,$revisit_flag){
        $where_arr=[
            ["t.teacherid=%u",$teacherid,-1],
            ["grade_part_ex=%u",$grade_part_ex,-1],
            ["t.subject=%u",$subject,-1],
            ["t.second_subject=%u",$second_subject,-1],
            ["identity=%u",$identity,-1],
            ["assign_jw_adminid=%u",$adminid,-1],
            ["assign_jw_adminid=%u",$jw_adminid,-1],
            ["r.class_will_type=%u",$class_will_type,-1],
            "assign_jw_adminid>0",
            "t.is_test_user=0"
        ];
        if($have_lesson==0){
            $where_arr[] = "(l.lessonid =0 or l.lessonid is null)";
        }elseif($have_lesson==1){
            $where_arr[] = "l.lessonid >0 ";
        }
        if($revisit_flag==0){
            $where_arr[] = "(r.add_time =0 or r.add_time is null)";
        }elseif($revisit_flag==1){
            $where_arr[] = "r.add_time >0 ";
        }

        $sql=$this->gen_sql_new("select t.grade_part_ex,t.second_subject,realname,t.teacherid,assign_jw_adminid,t.subject,m.account,assign_jw_time,train_through_new_time,identity,t.phone,r.record_info,r.add_time,r.acc,r.class_will_type,r.class_will_sub_type,r.recover_class_time,l.lesson_start,l.subject l_subject,t.grade_start,t.grade_end,t.teacher_textbook  "
                                ." from %s t left join %s m on t.assign_jw_adminid=m.uid "
                                ." left join %s r on (r.type=5 and t.teacherid = r.teacherid and r.add_time = (select max(add_time) from %s where teacherid = t.teacherid and type=5))"
                                ." left join %s l on (l.teacherid = t.teacherid and l.confirm_flag <>2 and l.lesson_type=2 and l.lesson_del_flag=0 and l.lesson_start=(select min(lesson_start) from %s ll join %s tss on ll.lessonid = tss.lessonid where ll.teacherid =t.teacherid and ll.lesson_del_flag=0 and ll.confirm_flag <>2 and tss.set_lesson_time > t.train_through_new_time and tss.set_lesson_time < (t.train_through_new_time+9*86400) and tss.success_flag <>2))"
                                ." where %s order by assign_jw_time desc ",
                                self::DB_TABLE_NAME ,
                                t_manager_info::DB_TABLE_NAME,
                                t_teacher_record_list::DB_TABLE_NAME,
                                t_teacher_record_list::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_jw_assign_teacher_info($start_time,$end_time){
        $where_arr=[
            "m.account_role=3",
            "m.del_flag=0",
            "assign_jw_adminid>0",
            "t.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,"t.assign_jw_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select m.account,t.assign_jw_adminid,count(*) revisit_num,sum(t.subject in(1,2,3)) lesson_plan_num  "
                                ." from %s t left join %s m on t.assign_jw_adminid=m.uid "
                                ." where %s group by  t.assign_jw_adminid",
                                self::DB_TABLE_NAME ,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_jw_assign_teacher_absence_info($start_time,$end_time){
        $where_arr=[
            "m.account_role=3",
            "m.del_flag=0",
            "assign_jw_adminid>0",
            "t.is_test_user=0",
            "t.subject in (1,2,3)",
            "l.lesson_del_flag =0",
            "tss.test_lesson_fail_flag =101"
        ];

        $this->where_arr_add_time_range($where_arr,"t.assign_jw_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select m.account,t.assign_jw_adminid,count(distinct tss.lessonid) absence_num "
                                ." from %s t left join %s m on t.assign_jw_adminid=m.uid "
                                ." left join %s l on l.teacherid = t.teacherid"
                                ." left join %s tss on l.lessonid = tss.lessonid"
                                ." where %s group by  t.assign_jw_adminid",
                                self::DB_TABLE_NAME ,
                                t_manager_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["assign_jw_adminid"];
        });


    }


    public function get_jw_assign_teacher_plan_in_info($start_time,$end_time){
        $where_arr=[
            "m.account_role=3",
            "m.del_flag=0",
            "assign_jw_adminid>0",
            "t.is_test_user=0",
            "t.subject in (1,2,3)",
            "(ttss.set_lesson_time - t.train_through_new_time) <= 9*86400",
            "ttss.set_lesson_time>0"
        ];

        $this->where_arr_add_time_range($where_arr,"t.assign_jw_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select m.account,t.assign_jw_adminid,count(*) plan_in_num "
                                ." from %s t left join %s m on t.assign_jw_adminid=m.uid "
                                ." left join %s l on (l.teacherid = t.teacherid and l.confirm_flag <>2 and l.lesson_type=2 and l.lesson_del_flag=0 and l.lesson_start=(select min(lesson_start) from %s ll join %s tss on ll.lessonid = tss.lessonid where ll.teacherid =t.teacherid and ll.lesson_del_flag=0 and ll.confirm_flag <>2 and  tss.success_flag <>2))"
                                ." left join %s ttss on l.lessonid = ttss.lessonid"
                                ." where %s group by  t.assign_jw_adminid",
                                self::DB_TABLE_NAME ,
                                t_manager_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["assign_jw_adminid"];
        });

    }

    public function get_jw_assign_teacher_plan_out_info($start_time,$end_time){
        $where_arr=[
            "m.account_role=3",
            "m.del_flag=0",
            "assign_jw_adminid>0",
            "t.is_test_user=0",
            "t.subject in (1,2,3)",
            "(ttss.set_lesson_time - t.train_through_new_time)> 9*86400",
        ];

        $this->where_arr_add_time_range($where_arr,"t.assign_jw_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select m.account,t.assign_jw_adminid,count(*) plan_out_num "
                                ." from %s t left join %s m on t.assign_jw_adminid=m.uid "
                                ." left join %s l on (l.teacherid = t.teacherid and l.confirm_flag <>2 and l.lesson_type=2 and l.lesson_del_flag=0 and l.lesson_start=(select min(lesson_start) from %s ll join %s tss on ll.lessonid = tss.lessonid where ll.teacherid =t.teacherid and ll.lesson_del_flag=0 and ll.confirm_flag <>2 and  tss.success_flag <>2))"
                                ." left join %s ttss on l.lessonid = ttss.lessonid"
                                ." where %s group by  t.assign_jw_adminid",
                                self::DB_TABLE_NAME ,
                                t_manager_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["assign_jw_adminid"];
        });

    }

    public function get_jw_assign_teacher_no_plan_info($start_time,$end_time){
        $where_arr=[
            "m.account_role=3",
            "m.del_flag=0",
            "assign_jw_adminid>0",
            "t.is_test_user=0",
            "t.subject in (1,2,3)",
             "ttss.set_lesson_time is null",
        ];

        $this->where_arr_add_time_range($where_arr,"t.assign_jw_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select m.account,t.assign_jw_adminid,count(*) no_plan_num "
                                ." from %s t left join %s m on t.assign_jw_adminid=m.uid "
                                ." left join %s l on (l.teacherid = t.teacherid and l.confirm_flag <>2 and l.lesson_type=2 and l.lesson_del_flag=0 and l.lesson_start=(select min(lesson_start) from %s ll join %s tss on ll.lessonid = tss.lessonid where ll.teacherid =t.teacherid and ll.lesson_del_flag=0 and ll.confirm_flag <>2 and  tss.success_flag <>2))"
                                ." left join %s ttss on l.lessonid = ttss.lessonid"
                                ." where %s group by  t.assign_jw_adminid",
                                self::DB_TABLE_NAME ,
                                t_manager_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["assign_jw_adminid"];
        });

    }






    public function get_jw_assign_teacher_time_out_info($start_time,$end_time){
        $where_arr=[
            "m.account_role=3",
            "m.del_flag=0",
            "assign_jw_adminid>0",
            "t.is_test_user=0",
            "(r.add_time - t.assign_jw_time)>2*86400",
        ];

        $this->where_arr_add_time_range($where_arr,"t.assign_jw_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select m.account,t.assign_jw_adminid,count(*) time_out_num  "
                                ." from %s t left join %s m on t.assign_jw_adminid=m.uid "
                                 ." left join %s r on (r.type=5 and t.teacherid = r.teacherid and r.add_time = (select min(add_time) from %s where teacherid = t.teacherid and type=5))"
                                ." where %s group by  t.assign_jw_adminid",
                                self::DB_TABLE_NAME ,
                                t_manager_info::DB_TABLE_NAME,
                                t_teacher_record_list::DB_TABLE_NAME,
                                t_teacher_record_list::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["assign_jw_adminid"];
        });

    }

    public function get_jw_assign_teacher_time_in_info($start_time,$end_time){
        $where_arr=[
            "m.account_role=3",
            "m.del_flag=0",
            "assign_jw_adminid>0",
            "t.is_test_user=0",
            "(r.add_time - t.assign_jw_time)<=2*86400",
            "r.add_time>0"
        ];

        $this->where_arr_add_time_range($where_arr,"t.assign_jw_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select m.account,t.assign_jw_adminid,count(*) time_in_num  "
                                ." from %s t left join %s m on t.assign_jw_adminid=m.uid "
                                ." left join %s r on (r.type=5 and t.teacherid = r.teacherid and r.add_time = (select min(add_time) from %s where teacherid = t.teacherid and type=5))"
                                ." where %s group by  t.assign_jw_adminid",
                                self::DB_TABLE_NAME ,
                                t_manager_info::DB_TABLE_NAME,
                                t_teacher_record_list::DB_TABLE_NAME,
                                t_teacher_record_list::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["assign_jw_adminid"];
        });

    }


    public function get_jw_assign_teacher_un_revisit_info($start_time,$end_time){
        $where_arr=[
            "m.account_role=3",
            "m.del_flag=0",
            "assign_jw_adminid>0",
            "t.is_test_user=0",
            "r.add_time is null ",
        ];

        $this->where_arr_add_time_range($where_arr,"t.assign_jw_time",$start_time,$end_time);
        $sql=$this->gen_sql_new("select m.account,t.assign_jw_adminid,count(*) no_revisit_num  "
                                ." from %s t left join %s m on t.assign_jw_adminid=m.uid "
                                ." left join %s r on (r.type=5 and t.teacherid = r.teacherid and r.add_time = (select min(add_time) from %s where teacherid = t.teacherid and type=5))"
                                ." where %s group by  t.assign_jw_adminid",
                                self::DB_TABLE_NAME ,
                                t_manager_info::DB_TABLE_NAME,
                                t_teacher_record_list::DB_TABLE_NAME,
                                t_teacher_record_list::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["assign_jw_adminid"];
        });

    }



    public function get_all_teacher_info(){
        $time = strtotime(date("2017-01-05"));
        $where_arr=[
            "is_test_user=0",
            "trial_lecture_is_pass=1",
            "realname <> '刘辉' and realname not like '%%alan%%' and realname not like '%%test%%' ",
            "tl.confirm_time>".$time,
            "t.create_time>".$time
        ];
        $sql = $this->gen_sql_new("select distinct teacherid from %s  t "
                                  ." left join %s tl on (t.phone=tl.phone and tl.status=1 and tl.subject = t.subject)"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_teacher_info_by_realname($nick){
        $where_arr = [
            ["realname='%s'",$nick,""]
        ];
        $sql = $this->gen_sql_new("select teacherid,realname,level,wx_openid,teacher_money_type,wx_openid,phone,"
                                  ." bankcard,level_simulate"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_good_teacher_list($start_time){
        $where_arr=[
            "is_good_flag=1",
            "change_good_time>".$start_time,
            "is_good_wx_flag=0"
        ];
        $sql = $this->gen_sql_new("select teacherid,realname,subject"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_wx_openid_list($teacherid=-1,$time=0){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            "is_test_user=0",
            "wx_openid !=''",
        ];

        $sql = $this->gen_sql_new("select teacherid,wx_openid,user_agent"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_teacherid_by_openid ($wx_openid){
        $where_arr=[
            ["wx_openid='%s'",$wx_openid,""]
        ];
        $sql = $this->gen_sql_new("select teacherid "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_phone_by_wx_openid($wx_openid){
        $where_arr=[
            ["wx_openid='%s'",$wx_openid,""]
        ];
        $sql = $this->gen_sql_new("select phone"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);

    }


    public function get_week_info_new(){
        $sql = $this->gen_sql_new("select count(*) "
                                  ." from %s "
                                  ." where limit_week_lesson_num>8 "
                                  ." and realname "
                                  ." not like '%%alan%%'"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_type_list(){
        $where_arr = [
            "teacher_ref_type>0",
            "teacher_type!=21",
            ["realname like '%%%s%%'","工作室",""],
        ];
        $sql = $this->gen_sql_new("select teacherid,teacher_type"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_ref_num($start_time,$teacher_ref_type){
        $where_arr = [
            ["create_time<%u",$start_time,0],
            ["teacher_ref_type=%u",$teacher_ref_type,-1],
        ];
        $sql = $this->gen_sql_new("select count(1) as num"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_freeze_teacher_info_new(){
        $sql=$this->gen_sql_new("select teacherid,grade_part_ex,grade_end,grade_start,is_freeze,not_grade"
                           ." from %s where is_freeze = 1",
                           self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_limit_teacher_info_new(){
        $where_arr=[
            "is_test_user=0",
            "realname not like '%%alan%%' and realname not like '%%测试%%' and realname not like '%%test%%'"
        ];
        $sql=$this->gen_sql_new("select teacherid,grade_part_ex,grade_end,grade_start,not_grade_limit,limit_plan_lesson_type"
                                ." from %s where limit_plan_lesson_type >0",
                                self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_change_teacher_list(){
        $where_arr = [
            "grade_start=0",
            "grade_part_ex!=0",
            "subject!=0",
        ];
        $sql = $this->gen_sql_new("select teacherid,grade_part_ex,subject "
                                  ." from %s "
                                  ." where %s"
                                  ." limit 100"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_interview_access($teacherid){
        $sql = $this->gen_sql_new("select tl.reason "
                                  ." from %s t "
                                  ." join %s tl on (t.phone = tl.phone and t.subject = tl.subject and tl.status=1) "
                                  ." where teacherid = %u"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,$teacherid
        );
        return $this->main_get_value($sql);
    }

    public function get_limit_teacher_info_new_order(){
        $where_arr=[
            "is_test_user=0",
            "realname not like '%%alan%%' and realname not like '%%测试%%' and realname not like '%%test%%'",
            "limit_plan_lesson_type >0",
            "l.lesson_type=2",
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status =1",
            "m.del_flag=0",
            "m.account_role=2",
            "limit_plan_lesson_time<".(time()-30*86400)
        ];
        $sql=$this->gen_sql_new("select  limit_plan_lesson_type,count(l.lessonid) lesson_num,sum(o.orderid>0) order_num"
                                ." from %s t left join %s l on (t.teacherid = l.teacherid and l.lesson_start <= t.limit_plan_lesson_time and l.lesson_start > (t.limit_plan_lesson_time-30*86400)) "
                                ." left join %s tss on l.lessonid = tss.lessonid"
                                ." left join %s tr on tss.require_id = tr.require_id"
                                ." left join %s m on tr.cur_require_adminid = m.uid"
                                ." left join %s o on (o.contract_type=0 and o.from_test_lesson_id = l.lessonid)"
                                ."where %s group by limit_plan_lesson_type",
                                self::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                t_test_lesson_subject_require::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                t_order_info::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["limit_plan_lesson_type"];
        });
    }

    public function get_limit_teacher_info_new_order2(){
        $where_arr=[
            "is_test_user=0",
            "realname not like '%%alan%%' and realname not like '%%测试%%' and realname not like '%%test%%'",
            "limit_plan_lesson_type >0",
            "l.lesson_type=2",
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status =1",
            "m.del_flag=0",
            "m.account_role=2",
            "limit_plan_lesson_time<".(time()-30*86400)
        ];
        $sql=$this->gen_sql_new("select  limit_plan_lesson_type,count(l.lessonid) lesson_num,sum(o.orderid>0) order_num"
                                ." from %s t left join %s l on (t.teacherid = l.teacherid and l.lesson_start > t.limit_plan_lesson_time and l.lesson_start < (t.limit_plan_lesson_time+30*86400)) "
                                ." left join %s tss on l.lessonid = tss.lessonid"
                                ." left join %s tr on tss.require_id = tr.require_id"
                                ." left join %s m on tr.cur_require_adminid = m.uid"
                                ." left join %s o on (o.contract_type=0 and o.from_test_lesson_id = l.lessonid)"
                                ."where %s group by limit_plan_lesson_type",
                                self::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                t_test_lesson_subject_require::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                t_order_info::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["limit_plan_lesson_type"];
        });
    }


    public function get_not_grade_null_info(){
        $sql = $this->gen_sql_new("select is_freeze,teacherid,not_grade from %s where not_grade='' and is_freeze=1",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_tran_through_info(){
        $sql = $this->gen_sql_new("select teacherid,train_through_new,train_through_new_time from %s where train_through_new=1 and (train_through_new_time =0 or train_through_new_time='' )",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_freeze_and_limit_tea_info($time){

        $where_arr = [
            "is_test_user=0 ",
            "realname not like '%%alan%%' ",
            " realname not like '%%test%%'"
        ];
        if(is_array($time)){
            $start_time = $time['start_time'];
            $end_time   = $time['end_time'];

            // $where_arr[] ="" ;

            $where_arr[] = "(freeze_time>=$start_time and freeze_time<$end_time)  or (limit_plan_lesson_time>=$start_time and limit_plan_lesson_time<$end_time )";
            // $this->where_arr_add_time_range($where_arr,"freeze_time",$start_time,$end_time);
            // $this->where_arr_add_time_range($where_arr,"limit_plan_lesson_time",$start_time,$end_time);
        }

        $sql = $this->gen_sql_new("select sum(is_freeze=1) freeze_num,sum(limit_plan_lesson_type>0) limit_num,sum(limit_plan_lesson_type=1) limit_one,sum(limit_plan_lesson_type=3) limit_three,sum(limit_plan_lesson_type=5) limit_five"
                                  ." from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_lesson_hold_teacher_info_new(){
        $where_arr=[
            "lesson_hold_flag_adminid=0",
            "lesson_hold_flag=1",
            "is_test_user=0",
            "trial_lecture_is_pass=1",
            "realname not like '%%测试%%' and realname not like '%%test%%'"
        ];
        $sql=$this->gen_sql_new("update %s set lesson_hold_flag_adminid =343 "
                                ."where %s order by teacherid limit 98 ",
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_update($sql);

    }

    public function get_lesson_hold_teacher_info(){
        $where_arr=[
            "lesson_hold_flag_adminid=0",
            "lesson_hold_flag=1",
            "is_test_user=0",
            "trial_lecture_is_pass=1",
            "realname not like '%%测试%%' and realname not like '%%test%%'"
        ];
        $sql=$this->gen_sql_new("select teacherid "
                                ." from  %s where %s limit 98 ",
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql);

    }

   public function get_no_test_lesson_teacher_lesson_info($start_time,$end_time){
        $where_arr=[
            "l.lesson_type=2",
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status in (0,1)",
            "tss.success_flag in (0,1)",
            "have_test_lesson_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.teacherid from %s t"
                                  ." join %s l on t.teacherid = l.teacherid "
                                  ." join %s tss on l.lessonid = tss.lessonid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_teacher_quit_info($page_num,$start_time,$end_time,$teacherid,$subject){
        $where_arr=[
            "is_quit=1",
            ["teacherid=%u",$teacherid,-1],
            ["subject=%u",$subject,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"t.quit_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.quit_time,t.quit_info,t.quit_set_adminid,m.account,t.realname,t.subject,"
                                  ." t.grade_part_ex,t.grade_start,t.grade_end,t.create_time,t.teacherid "
                                  ." from %s t left join %s m on t.quit_set_adminid = m.uid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_quit_teacher_ingo_by_time(){
        $sql = $this->gen_sql_new("select teacherid,realname,quit_time,quit_set_adminid,quit_info,phone "
                                  ." from %s "
                                  ." where quit_time>0"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_all_reference_teacher($teacher_ref_type){
        $where_arr = [
            ["t.teacher_ref_type=%u",$teacher_ref_type,-1],
        ];
        $sql = $this->gen_sql_new("select t.teacherid,t.realname,t.teacher_ref_type,t.phone,t.teacher_money_type,"
                                  ." t2.realname as ref_realname,t2.phone as ref_phone,"
                                  ." t2.teacher_money_type as ref_teacher_money_type,t2.teacher_ref_type as ref_teacher_ref_type"
                                  ." from %s t"
                                  ." left join %s ta on t.phone=ta.phone"
                                  ." left join %s t2 on ta.reference=t2.phone"
                                  ." where %s "
                                  ." and t.is_test_user=0"
                                  ." and t.teacher_money_type=5"
                                  ." and t.teacher_type <20"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_wx_openid_by_teacherid($teacherid){
        $sql = $this->gen_sql_new(
            "select wx_openid from %s where teacherid='%s' ",
            self::DB_TABLE_NAME, $teacherid
        );
        return $this->main_get_value($sql);

    }

    public function get_teacher_name_list(){
        $where_arr = [
            "t1.is_test_user=1",
            "t1.phone_spare!=''",
            "t2.phone!=''",
        ];
        $sql = $this->gen_sql_new("select t1.teacherid as origin_teacherid,t1.realname,t1.phone_spare,t2.teacherid,t2.phone,"
                                  ." t2.teacherid"
                                  ." from %s t1"
                                  ." left join %s t2 on t1.phone_spare=t2.phone and t1.teacherid!=t2.teacherid"
                                  ." where %s"
                                  ." group by t2.teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_reset_is_test(){
        $where_arr = [
            "is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select teacherid,is_test_user,level,trial_lecture_is_pass,wx_use_flag"
                                  ." from %s t"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_research_teacher_list_lesson($page_info,$teacherid){
        $where_arr=[
            "m.account_role in (4,9)",
            "m.del_flag=0",
            ["t.teacherid=%u",$teacherid,-1],
            "m.uid not in (790,486,871,891)"
        ];

        $sql = $this->gen_sql_new("select teacherid,subject,grade_part_ex,t.phone,realname "
                                  ." from %s t left join %s m on t.phone= m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,20);
    }

    public function ccc(){
        $sql = $this->gen_sql_new("select nick from %s where teacherid =139081",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_wx_openid_by_phone($phone){
        $where_arr = [
            ["phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select wx_openid"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_reference_info_by_phone($phone){
        $where_arr = [
            ["tla.phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select t.teacherid,t.phone,t.nick,t.teacher_type,t.teacher_ref_type,t.wx_openid"
                                  ." from %s tla"
                                  ." left join %s t on tla.reference=t.phone"
                                  ." where %s"
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_admin_teacher_list(){
        $sql = $this->gen_sql_new("select teacherid,phone "
                                  ." from %s t"
                                  ." where exists (select 1 from %s where t.phone=phone)"
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }


    public function get_tea_subject_count(){
        $sql = $this->gen_sql_new("select count(*) num,subject from %s where is_test_user=0 and is_quit=0 group by subject",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }


    public function get_teacher_info_by_money_type($teacher_money_type,$start_time,$end_time){
        $where_arr=[
            "t.is_quit=0",
            "t.is_test_user=0",
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            "t.train_through_new = 1",
            "l.lesson_del_flag=0",
            "l.confirm_flag <>2"
        ];
        if($teacher_money_type==1){
            $where_arr[]="l.lesson_type <>2";
        }
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.teacherid,sum(l.lesson_count) lesson_count "
                                  ." from %s t left join %s l on t.teacherid=l.teacherid"
                                  ." where %s group by t.teacherid having(lesson_count>=18000)",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });


    }

    public function get_teacher_lesson_total_realname($teacher_money_type,$start_time,$end_time,$realname){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.confirm_flag <>2",
            ["t.realname='%s'",$realname,""]
        ];
        if($teacher_money_type==1){
            $where_arr[]="l.lesson_type <>2";
        }
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.teacherid,sum(l.lesson_count) lesson_count "
                                  ." from %s t left join %s l on t.teacherid=l.teacherid"
                                  ." where %s group by t.teacherid ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);


    }


    public function get_teacher_info_by_money_type_new($teacher_money_type,$start_time,$end_time,$arr){
        $where_arr=[
            "t.is_quit=0",
            "t.is_test_user=0",
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            "t.train_through_new = 1",
            "l.lesson_del_flag=0",
            "l.confirm_flag <>2"
        ];
        if($teacher_money_type==1){
            $where_arr[]="l.lesson_type <>2";
        }
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $where_arr[] = $this->where_get_in_str("t.teacherid",$arr);
        $sql = $this->gen_sql_new("select t.teacherid,sum(l.lesson_count) lesson_count "
                                  ." from %s t left join %s l on t.teacherid=l.teacherid"
                                  ." where %s group by t.teacherid ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });


    }


    public function get_teacher_level_info($page_info,$tea_list,$start_time){
        $where_arr=[];
        $where_arr[]= $this->where_get_in_str("t.teacherid",  $tea_list,false);
        $sql = $this->gen_sql_new("select t.teacherid,t.realname,t.level,t.teacher_money_type,t.phone,t.train_through_new_time "
                                  ." ,a.require_time,a.require_adminid,a.accept_adminid,a.accept_time,a.accept_flag,a.accept_info "
                                  ." from %s t left join %s a on (a.start_time = %u and t.teacherid = a.teacherid)"
                                  ." where %s order by t.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_advance_list::DB_TABLE_NAME,
                                  $start_time,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,100);


    }

    public function get_trial_teacher_month($start_time,$end_time){
        $where_arr = [
            ["create_time>%u",$start_time,0],
            ["create_time<%u",$end_time,0],
            "lesson_start>create_time",
            "lesson_start<(create_time+30*86400)",
            "lesson_type<1000",
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("select t.teacherid,t.nick,t.phone,t.create_time,"
                                  ." sum(if(l.lesson_type=2,lesson_count,0)) as trial_count ,"
                                  ." sum(if(l.lesson_type in (0,1,3),lesson_count,0)) as normal_count"
                                  ." from %s t"
                                  ." left join %s l on t.teacherid=l.teacherid"
                                  ." where %s"
                                  ." group by t.teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_wx_openid_by_lessonid($lessonid){
        $sql = $this->gen_sql_new(" select wx_openid from %s t ".
                                  " left join %s l on l.teacherid = t.teacherid".
                                  " where l.lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_value($sql);
    }

    public function get_teacher_nick_lessonid($lessonid){
        $sql = $this->gen_sql_new(" select s.nick from %s s ".
                                  " left join %s l on l.teacherid = s.teacherid".
                                  " where l.lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_value($sql);
    }

    public function get_all_train_pass_teacher_info($page_info,$arr){
        $where_arr = [];
        $this->where_arr_teacherid($where_arr,"teacherid", $arr);
        $sql = $this->gen_sql_new("select teacherid,realname,subject,grade_part_ex,grade_start,grade_end,not_grade"
                                  ." from %s where %s order by teacherid desc",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_tea_have_test_lesson($page_info,$teacherid,$subject){
        $where_arr = [
            "t.is_quit=0",
            "t.is_test_user=0",
            "t.train_through_new=1",
            ["t.teacherid=%u",$teacherid,-1],
            ["t.subject=%u",$subject,-1],
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status <2",
            "l.lesson_type=2"
        ];
        $sql = $this->gen_sql_new("select t.teacherid,count(l.lessonid) num,"
                                  ."t.realname,t.subject,t.grade_part_ex,t.grade_start,t.grade_end,t.not_grade"
                                  ." from %s t left join %s l on t.teacherid = l.teacherid"
                                  ." where %s group by t.teacherid having(num>0) ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,10,true);

    }

    public function get_tea_regular_test_lesson($page_info,$teacherid,$userid,$subject,$tea_list){
        $start_time = time()-30*86400;
        $end_time = time();
        $where_arr = [
            "t.is_quit=0",
            "t.is_test_user=0",
            "t.train_through_new=1",
            ["t.teacherid=%u",$teacherid,-1],
            ["l.userid=%u",$userid,-1],
            ["t.subject=%u",$subject,-1],
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status <2",
            "l.lesson_type in (0,1,3)",
            "l.userid>0",
            "l.lesson_start>=".$start_time,
            "l.lesson_start<".$end_time,
        ];
        $this->where_arr_teacherid($where_arr,"t.teacherid", $tea_list);
        $sql = $this->gen_sql_new("select t.teacherid,l.userid,s.nick,"
                                  ."t.realname,t.subject,t.grade_part_ex,t.grade_start,t.grade_end,t.not_grade"
                                  ." from %s t left join %s l on t.teacherid = l.teacherid"
                                  ." left join %s s on l.userid = s.userid"
                                  ." where %s group by t.teacherid,l.userid order by t.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,10,true);

    }

    public function get_tea_regular_test_lesson_list($subject){
        $start_time = time()-30*86400;
        $end_time = time();
        $where_arr = [
            "t.is_quit=0",
            "t.is_test_user=0",
            "t.train_through_new=1",
            ["t.subject=%u",$subject,-1],
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status <2",
            "l.lesson_type in (0,1,3)",
            "l.userid>0",
            "l.lesson_start>=".$start_time,
            "l.lesson_start<".$end_time,
        ];
        $sql = $this->gen_sql_new("select t.teacherid,count(distinct l.userid) num"
                                  ." from %s t left join %s l on t.teacherid = l.teacherid"
                                  ." where %s group by t.teacherid having(num >8 and num >0)",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }



    public function get_all_tea_phone_location(){
        $sql = $this->gen_sql_new("select teacherid,realname,phone,address,identity,train_through_new_time"
                                  ." from %s where is_test_user=0 and train_through_new=1",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_tkp(){
        $where_arr = [
            "teacher_money_type=5",
            "reference='13387970861'",
        ];
        $sql = $this->gen_sql_new("select teacherid"
                                  ." from %s t"
                                  ." left join %s tl on t.phone=tl.phone"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_total_list(
        $page_num,$start_time,$end_time,$teacherid,$teacher_money_type,$level,$is_test_user
    ){
        if($teacherid!=-1){
            $where_arr[]=["t.teacherid=%u",$teacherid,-1];
        }else{
            $where_arr = [
                ["t.teacher_money_type=%u",$teacher_money_type,-1],
                ["t.level=%u",$level,-1],
                ["t.is_test_user=%u",$is_test_user,-1],
            ];
        }
        $sql = $this->gen_sql_new("select t.teacherid,t.teacher_money_type,t.level,t.test_transfor_per,t.create_time, "
                                  ." t.realname,"
                                  ." group_concat(distinct(l.grade)) as all_grade,"
                                  ." group_concat(distinct(l.subject)) as all_subject,"
                                  ." count(distinct(l.userid)) as stu_num, "
                                  ." sum(if(lesson_type=2,lesson_count,0)) as trial_lesson_count, "
                                  ." sum(if(lesson_type in (0,1,3),lesson_count,0)) as normal_lesson_count "
                                  ." from %s t "
                                  ." left join %s l on "
                                  ." t.teacherid=l.teacherid and l.lesson_status=2 and l.lesson_del_flag=0 and l.confirm_flag!=2"
                                  ." and l.lesson_start>%u and lesson_start<%u"
                                  ." where %s"
                                  ." group by t.teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$start_time
                                  ,$end_time
                                  ,$where_arr
        );
        return $this->main_get_list_as_page($sql);
    }



    /**
     *@function 获取平台老师总数（通过入职培训，没有离职）
     *
     */
    public function get_teacher_count($train_through_new){
        $where_arr = [
            " train_through_new=1 ",
            " is_quit=0 ",
            " is_test_user =0"
        ];
        $sql = $this->gen_sql_new("select count(teacherid) as platform_teacher_count "
                                  ." from %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    /**
     *@function 获取平台老师课耗总数
     *
     */
    public function get_teacher_list($train_through_new,$start_time,$end_time,$full_flag=0){

        $where_arr = [
            " t.train_through_new=1 ",
            " t.is_quit=0 ",
            " t.is_test_user =0",
            "l.confirm_flag in (0,1,4)",
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.lesson_status=2",
            "l.lesson_start>=".$start_time,
            "l.lesson_start<".$end_time
        ];
        if($full_flag==1){
            $where_arr[]="m.del_flag=0";
            $where_arr[]="m.account_role=5";
        }

        $sql = $this->gen_sql_new("select sum(l.lesson_count) lesson_count,count(distinct l.teacherid) tea_num,"
                                  ."count(distinct l.assistantid) ass_num,count(distinct l.userid) stu_num "
                                  ." from %s t left join %s l on t.teacherid =l.teacherid"
                                  ." left join %s m on t.phone= m.phone"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function set_simulate_info($teacher_money_type,$level,$level_simulate){
        $where_arr = [
            ["teacher_money_type_simulate=%u",$teacher_money_type,0],
            ["level_simulate=%u",$level,0],
        ];

        $sql = $this->gen_sql_new("update %s set level_simulate=%u"
                                  ." where %s"
                                  ." and teacher_type!=3"
                                  ,self::DB_TABLE_NAME
                                  ,$level_simulate
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function check_count_by_realname($realname){
        $where_arr = [
            ["realname='%s'",$realname,""]
        ];
        $sql = $this->gen_sql_new("select count(1)"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_info_by_realname_for_level_simulate($nick,$level_simulate){
        $where_arr = [
            ["realname='%s'",$nick,""],
            ["level_simulate!=%u",$level_simulate,""],
        ];
        $sql = $this->gen_sql_new("select teacherid,realname,level,wx_openid,teacher_money_type,wx_openid,phone,"
                                  ." bankcard,level_simulate"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_all_un_throuth_info(){
        $sql = $this->gen_sql_new("select teacherid,level,trial_lecture_is_pass from %s where train_through_new=0 and level=1 and is_test_user=0 and train_through_new_time=0 and wx_use_flag=1 and trial_lecture_is_pass=1",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }







    public function get_chaxun_num($item){
        $sql = $this->gen_sql_new("select count(*) from %s  where create_time <$item and is_test_user = 0 and train_through_new=1",
                                  self::DB_TABLE_NAME
        );


        return $this->main_get_value($sql);
    }

    public function get_new_add_num($item){

        $n = date('Y-m-d',$item);
        $end_time = strtotime( "$n +1 month");


        $sql = $this->gen_sql_new("select count(*) from %s  where train_through_new_time>$item and train_through_new_time<$end_time and is_test_user = 0 ",

                                  self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);

    }


    public function get_leveal_num($item){

        // $n = date('Y-m-d',$item);
        $three_end     = 1501516800;
        $three_begin   = 1483200000;
        $where_arr = [
            "t.train_through_new =1",
            "t.is_test_user = 0",
            // "create_time <$three_begin",
            // "t.test_quit =0"
        ];
        $sql = $this->gen_sql_new(" select t.teacherid,count(l.lessonid) num from %s t left join %s l on l.teacherid=t.teacherid and l.lesson_start>=$three_begin and l.lesson_end<$three_end  ".
                                  " where %s group by t.teacherid having(num=0) ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        // return $sql;
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }


    public function get_teacher_openid_list(){
        $where_arr = [
            "train_through_new =1",
            "is_quit = 0",
            "is_test_user = 0"
        ];
        $sql = $this->gen_sql_new(" select wx_openid from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_teacher_simulate_list(
        $start_time,$end_time,$teacher_money_type,$level,$teacher_id
    ){

        $where_arr = [
            ["l.lesson_start>%u",$start_time,0],
            ["l.lesson_start<%u",$end_time,0],
            "t.is_test_user=0",
            "lesson_del_flag = 0",
            "confirm_flag!=2",
            "lesson_type in (0,1,3)",
            "lesson_status=2",
            "teacher_type!=3"
        ];
        if($teacher_id>0){
            $where_arr[]=["t.teacherid=%u",$teacher_id,-1];
        }else{
            $where_arr[]=["t.teacher_money_type=%u",$teacher_money_type,-1];
            $where_arr[]=["t.level=%u",$level,-1];
        }

        $sql = $this->gen_sql_new("select t.teacherid,t.teacher_money_type,t.level,t.realname,"
                                  ." t.level_simulate,t.teacher_money_type_simulate,"
                                  ." m1.money,m2.money as money_simulate,ol.price as lesson_price,l.lesson_count,"
                                  ." l.already_lesson_count,m1.type,m2.type as type_simulate,l.grade,t.teacher_type,"
                                  ." o.contract_type,o.lesson_total,o.default_lesson_count,o.grade as order_grade,"
                                  ." o.competition_flag,o.price,o.discount_price,l.lesson_start"
                                  ." from %s l "

                                  ." left join %s t on l.teacherid=t.teacherid "
                                  ." left join %s m1 on l.level=m1.level and l.teacher_money_type=m1.teacher_money_type "
                                  ."      and m1.grade=(case when "
                                  ."      l.competition_flag=1 then if(l.grade<200,203,303) "
                                  ."      else l.grade"
                                  ."      end )"
                                  ." left join %s m2 on t.level_simulate=m2.level "
                                  ."      and t.teacher_money_type_simulate=m2.teacher_money_type "
                                  ."      and m2.grade=(case when "
                                  ."      l.competition_flag=1 then if(l.grade<200,203,303) "
                                  ."      else l.grade"
                                  ."      end )"
                                  ." left join %s ol on l.lessonid=ol.lessonid"
                                  ." left join %s o on ol.orderid=o.orderid"

                                  ." where %s"
                                  ." group by l.lessonid"
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_money_type::DB_TABLE_NAME
                                  ,t_teacher_money_type::DB_TABLE_NAME
                                  ,t_order_lesson_list::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_lesson_info($teacherid, $start_time, $end_time){
        $where_arr = [
            ['t.teacherid=%s', $teacherid, 0],
            ['l.lesson_start>%s', $start_time, 0],
            ['l.lesson_start<%s', $end_time, 0],
            "l.lesson_del_flag=0",
            "l.confirm_flag!=2",
        ];

        $sql = $this->gen_sql_new("select sum(if(l.lesson_type=0,l.lesson_count,0)) as normal_count "
                                  ." , sum(if(l.lesson_type=2,l.lesson_count,0)) as test_count "
                                  ." , sum(if(l.lesson_type not in(0,2),l.lesson_count,0)) as other_count "
                                  ." from %s t "
                                  ." left join %s l on t.teacherid=l.teacherid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_true_level($teacherid){
        $where_arr = [
            ["teacherid=%s",$teacherid, 0],
        ];
        $sql = $this->gen_sql_new("select level,teacher_money_type"
                           ." from %s"
                           ." where %s"
                           ,self::DB_TABLE_NAME
                           ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_student_by_teacherid($teacherid, $start_time, $end_time){
        $where_arr = [
            ['t.teacherid=%s', $teacherid, 0],
            ['l.lesson_start>%s', $start_time, 0],
            ['l.lesson_start<%s', $end_time, 0],
            "l.lesson_del_flag=0",
            "l.confirm_flag!=2",
        ];

        $sql = $this->gen_sql_new("select distinct s.face"
                                  ." from %s t "
                                  ." left join %s l on t.teacherid=l.teacherid "
                                  ." left join %s s on s.userid=l.userid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_teacher_lesson_detail($teacherid, $start_time, $end_time){
        $where_arr = [
            ['t.teacherid=%s', $teacherid, 0],
            ['l.lesson_start>%s', $start_time, 0],
            ['l.lesson_start<%s', $end_time, 0],
        ];

        $sql = $this->gen_sql_new("select sum(if (l.deduct_change_class=1,1,0)) as change_count"
                                  .",sum(if(l.teacher_comment!='',1,0)) as comment_count"
                                  .",count(l.stu_praise) as praise_count"
                                  .",sum(if (l.deduct_come_late=1 and l.deduct_change_class!=1,1,0)) as late_count"
                                  .",sum(if (l.tea_cw_status=1,1,0)) as tea_cw_count"
                                  .",sum(if (l.stu_cw_status=1,1,0)) as stu_cw_count"
                                  .",sum(if (h.work_status>0,1,0)) as homework_count"
                                  ." from %s t "
                                  ." left join %s l on t.teacherid=l.teacherid "
                                  ." left join %s h on l.lessonid=h.lessonid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_level_simulate_list(){
        $where_arr = [
            "train_through_new=1",
            "is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select level_simulate,count(1) as level_num"
                                  ." from %s "
                                  ." group by level_simulate"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
}