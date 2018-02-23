<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_lecture_info extends \App\Models\Zgen\z_t_teacher_lecture_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_teacher_lecture_list(
        $page_num,$opt_date_type,$start_time,$end_time,$grade,$subject,$status,$phone,
        $teacherid,$tea_subject="",$is_test_flag=1,$trans_grade=-1,$have_wx=-1,$full_time=-1,
        $id_train_through_new_time=-1,$id_train_through_new=-1,$accept_adminid=-1,$identity=-1
    ){
        if($phone==''){
            if($opt_date_type=="add_time"){
                $time_str = "b.add_time";
            }else{
                $time_str = "b.confirm_time";
            }
            $where_arr = [
                ["$time_str>%u",$start_time,0],
                ["$time_str<%u",$end_time,0],
                ['b.subject=%u',$subject,-1],
                ["la.trans_grade=%u",$trans_grade,-1],
                ['b.status=%u',$status,-1],
                ['b.is_test_flag=%u',$is_test_flag,-1],
                ['t.teacherid=%u',$teacherid,-1],
                ['b.identity=%u',$identity,-1],
                ['la.full_time=%u',$full_time,-1],
                ["not exists(select 1 from %s where b.grade=grade and b.phone=phone and b.subject=subject and b.add_time<add_time)",
                 self::DB_TABLE_NAME,""],
            ];

            if($grade !=100 && $grade !=200 && $grade!=300){
                $where_arr[]= ['b.grade=%u',$grade,-1];
            }else if($grade==100){
                $where_arr[]="b.grade>=100 and b.grade <200";
            }else if($grade==200){
                $where_arr[]="b.grade>=200 and b.grade <300";
            }else if($grade==300){
                $where_arr[]="b.grade>=300";
            }
            $group_str = "group by b.phone,b.subject";

            if($have_wx==0){
                $where_arr[] = "(tt.wx_openid = '' or tt.wx_openid is null )";
            }elseif($have_wx==1){
                $where_arr[] = "tt.wx_openid <> '' and tt.wx_openid is not null";
            }
            $where_arr[] = ['la.accept_adminid=%u',$accept_adminid,-1];
        }else{
            $where_arr [] = "b.phone like '%%".$phone."%%' or b.nick like '%%".$phone."%%'";
            $group_str    = "group by b.add_time";
        }
        if(!empty($tea_subject)) {
            $where_arr[] = "b.subject in ".$tea_subject;
        }

        if($id_train_through_new_time == -1){
        }elseif($id_train_through_new_time == 0){
            $where_arr[] = " tt.train_through_new_time=0 ";
        }else{
            $where_arr[] = " tt.train_through_new_time>0 ";
        }

        if($id_train_through_new == -1){
        }elseif ($id_train_through_new == 0) {
            # code...
            $where_arr[] = " tt.train_through_new=0 ";
        }else{
            $where_arr[] = " tt.train_through_new=1 ";
        }
        $where_arr[] = "add_time!=0";
        $sql = $this->gen_sql_new("select b.id,b.nick,b.face,b.phone,b.grade,b.subject,b.title,b.draw,"
                                  ." real_begin_time,real_end_time,teacher_re_submit_num,"
                                  ." b.account,b.status,b.reason,b.add_time,b.identity,b.identity_image,b.resume_url,"
                                  ." if(audio_build='',audio,audio_build) as audio,b.is_test_flag,"
                                  ." t.nick as reference_name,t.teacherid,la.answer_begin_time,la.grade_ex,"
                                  ." tt.subject t_subject,tt.teacherid as t_teacherid,tt.create_time as t_create_time,"
                                  ." la.textbook,b.confirm_time,la.grade_start,la.grade_end,la.not_grade,la.trans_grade,"
                                  ." la.trans_grade_start,la.trans_grade_end,tt.wx_openid,tt.user_agent,"
                                  ." m.account accept_account, m.name as zs_name,"
                                  ." la.id as appointment_id,b.retrial_info,b.teacher_accuracy_score,la.full_time ,"
                                  ." tt.train_through_new_time,tt.train_through_new "
                                  ." from %s as b"
                                  ." left join %s la on b.phone=la.phone"
                                  ." left join %s t on t.phone=la.reference" //推荐人
                                  ." left join %s tt on b.phone=tt.phone"
                                  // ." left join %s ttt on b.phone=ttt.phone"
                                  ." left join %s m on la.accept_adminid = m.uid"
                                  ." where %s "
                                  ." %s"
                                  ." order by add_time desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  // ,t_teacher_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$group_str
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function get_audio_build_list(){
        $sql=$this->gen_sql_new("select id,phone,audio,audio_build"
                                ." from %s"
                                ." where audio!=''"
                                ." and audio_build=''"
                                ." and status=0"
                                ." limit 1"
                                ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_lecture_info($id){
        $sql = $this->gen_sql_new("select phone,status,nick,teacher_re_submit_num,subject,grade"
                                  ." from %s "
                                  ." where id=%u"
                                  ,self::DB_TABLE_NAME
                                  ,$id
        );
        return $this->main_get_row($sql);
    }

    public function get_simple_info_new($id){
        $where_arr = [
            ["l.id=%u",$id,0],
        ];
        $sql = $this->gen_sql_new("select l.phone,a.email,a.school,a.textbook,a.grade_start,a.grade_end,a.not_grade,"
                                  ." l.status,l.subject,l.grade,a.bankcard,a.bank_address,a.bank_account,"
                                  ." if(t.teacher_money_type in (4,5) and t.teacher_type in (21,22),t.teacher_money_type,4) "
                                  ." as teacher_money_type,l.confirm_time,a.name,"
                                  ." l.resume_url"
                                  ." from %s l "
                                  ." left join %s a on l.phone=a.phone"
                                  ." left join %s t on a.reference=t.phone"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_subject_by_phone($phone){
        $where_arr = [
            ["phone='%s'",$phone,""],
        ];
        $sql = $this->gen_sql_new("select subject "
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);

    }
    public function get_grade_by_phone($phone,$subject){
        $where_arr=[
            ["phone='%s'",$phone,""],
            ["subject=%u",$subject,0]
        ];
        $sql=$this->gen_sql_new("select grade "
                                ." from %s  "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_lecture_info_by_day($start_time,$end_time,$status=-1){
        $where_arr=[
            ["confirm_time >= %u",$start_time,-1],
            ["confirm_time < %u",$end_time,-1],
            "(tl.account <> 'adrian' && tl.account <> 'alan' && tl.account <> 'jack')",
            "tl.is_test_flag =0",
            ["tl.status=%u",$status,-1]
        ];
        $sql = $this->gen_sql_new("select count(*) all_num,count(distinct tl.phone) all_count, ".
                                  " FROM_UNIXTIME(tl.confirm_time, '%%Y-%%m-%%d') time".
                                  " from %s tl ".
                                  " left join %s m on m.account = tl.account".
                                  " left join %s t on m.phone = t.phone ".
                                  " where %s group by time",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["time"];
        });
    }

    public function get_lecture_info_by_time_new($subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject="",$status=-1){
        $where_arr=[
            ["tl.subject=%u",$subject,-1],
            ["confirm_time >= %u",$start_time,-1],
            ["confirm_time < %u",$end_time,-1],
            "(tl.account <> 'adrian' && tl.account <> 'alan' && tl.account <> 'jack')",
            ["t.teacherid = %u",$teacher_account,-1],
            ["tt.teacherid = %u",$reference_teacherid,-1],
            ["tl.identity = %u",$identity,-1],
            "(tl.account is not null && tl.account <> '')",
            "tl.is_test_flag =0"
        ];
        if(!empty($tea_subject)){
            $where_arr[]="tl.subject in".$tea_subject;
        }
        if($status==-2){
            $where_arr[]="tl.status <>4";
        }else{
            $where_arr[]= ["tl.status = %u",$status,-1];
        }
        $sql = $this->gen_sql_new("select tl.account,count(*) all_num,count(distinct tl.phone) all_count,count(distinct tl.phone) all_count_new,sum(if(tl.status=1,1,0)) suc_count,sum(if(tl.status<>4,1,0)) real_count,sum(tl.confirm_time) all_con_time,sum(tl.add_time) all_add_time from %s tl ".
                                  " left join %s m on m.account = tl.account".
                                  " left join %s t on m.phone = t.phone ".
                                  " left join %s ta on tl.phone = ta.phone".
                                  " left join %s tt on ta.reference = tt.phone ".
                                  "where %s group by tl.account",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item["account"];
        });
    }

    public function get_lecture_info_by_all($subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject="",$status=-1){
        $where_arr=[
            ["tl.subject=%u",$subject,-1],
            ["confirm_time >= %u",$start_time,-1],
            ["confirm_time < %u",$end_time,-1],
            "(tl.account <> 'adrian' && tl.account <> 'alan' && tl.account <> 'jack')",
            ["t.teacherid = %u",$teacher_account,-1],
            ["tt.teacherid = %u",$reference_teacherid,-1],
            ["tl.identity = %u",$identity,-1],
            "(tl.account is not null && tl.account <> '')",
            "tl.is_test_flag =0"
        ];
        if(!empty($tea_subject)){
            $where_arr[]="tl.subject in".$tea_subject;
        }
        if($status==-2){
            $where_arr[]="tl.status <>4";
        }else{
            $where_arr[]= ["tl.status = %u",$status,-1];
        }
        $sql = $this->gen_sql_new("select tl.account,count(*) all_num,count(distinct tl.phone) all_count,count(distinct tl.phone) all_count_new,sum(if(tl.status=1,1,0)) suc_count,sum(if(tl.status<>4,1,0)) real_count,sum(tl.confirm_time) all_con_time,sum(tl.add_time) all_add_time from %s tl ".
                                  " left join %s m on m.account = tl.account".
                                  " left join %s t on m.phone = t.phone ".
                                  " left join %s ta on tl.phone = ta.phone".
                                  " left join %s tt on ta.reference = tt.phone ".
                                  "where %s ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }
    public function get_lecture_info_by_all_new($subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject="",$status=-1){
        $where_arr=[
            ["tl.subject=%u",$subject,-1],
            ["tl.add_time >= %u",$start_time,-1],
            ["tl.add_time < %u",$end_time,-1],
            "(tl.account <> 'adrian' && tl.account <> 'alan' && tl.account <> 'jack')",
            ["t.teacherid = %u",$teacher_account,-1],
            ["tt.teacherid = %u",$reference_teacherid,-1],
            ["tl.identity = %u",$identity,-1],
            //  "(tl.account is not null && tl.account <> '')",
            "tl.is_test_flag =0"
        ];
        if(!empty($tea_subject)){
            $where_arr[]="tl.subject in".$tea_subject;
        }
        if($status==-2){
            $where_arr[]="tl.status <>4";
        }else{
            $where_arr[]= ["tl.status = %u",$status,-1];
        }
        $sql = $this->gen_sql_new("select tl.account,count(*) all_num,count(distinct tl.phone) all_count,count(distinct tl.phone) all_count_new,sum(if(tl.status=1,1,0)) suc_count,sum(if(tl.status<>4,1,0)) real_count,sum(tl.confirm_time) all_con_time,sum(tl.add_time) all_add_time from %s tl ".
                                  " left join %s m on m.account = tl.account".
                                  " left join %s t on m.phone = t.phone ".
                                  " left join %s ta on tl.phone = ta.phone".
                                  " left join %s tt on ta.reference = tt.phone ".
                                  "where %s ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }



    public function get_lecture_info_by_reference_new($subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject=""){
        $where_arr=[
            ["tl.subject=%u",$subject,-1],
            ["confirm_time >= %u",$start_time,-1],
            ["confirm_time <= %u",$end_time,-1],
            "(tl.account <> 'adrian' && tl.account <> 'alan' && tl.account <> 'jack')",
            ["t.teacherid = %u",$teacher_account,-1],
            ["tt.teacherid = %u",$reference_teacherid,-1],
            ["tl.identity = %u",$identity,-1],
        ];
        if(!empty($tea_subject)){
            $where_arr[]="tl.subject in".$tea_subject;
        }

        $sql = $this->gen_sql_new("select tt.realname,tt.nick,(if(ta.reference is not null,ta.reference,-1)) reference,count(*) all_count,sum(if(tl.status=1,1,0)) suc_count,sum(tl.confirm_time) all_con_time,sum(tl.add_time) all_add_time from %s tl ".
                                  " left join %s m on m.account = tl.account".
                                  " left join %s t on m.phone = t.phone ".
                                  " left join %s ta on tl.phone = ta.phone".
                                  " left join %s tt on ta.reference = tt.phone ".
                                  "where %s group by reference",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);

    }
    public function get_lecture_info_by_subject_new($start_time,$end_time,$status=-1){
        $where_arr=[
            ["confirm_time >= %u",$start_time,-1],
            ["confirm_time <= %u",$end_time,-1],
            ["status=%u",$status,-1],
            "is_test_flag =0",
            "account <> 'adrian'"
        ];
        $sql = $this->gen_sql_new("select subject, count(*) all_num,count(distinct phone) all_count,sum(if(status=1,1,0)) suc_count,sum(confirm_time-add_time) time_count from %s where %s  and subject>0  group by subject",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });
    }

    public function get_lecture_info_by_zs($start_time,$end_time,$status=-1){
        $where_arr=[
            ["tl.confirm_time >= %u",$start_time,-1],
            ["tl.confirm_time <= %u",$end_time,-1],
            "tl.is_test_flag =0",
            "tl.account <> 'adrian'",
            "(tl.account is not null && tl.account <> '')",
        ];
        if($status==-2){
            $where_arr[] = "tl.status <>4";
        }else{
            $where_arr[] = ["tl.status=%u",$status,-1];
        }
        $sql = $this->gen_sql_new("select la.accept_adminid , count(*) all_num,count(distinct tl.phone) all_count,count(distinct t.phone) tea_count,sum(if(status=1,1,0)) suc_count,sum(confirm_time-add_time) time_count from %s tl left join %s la on tl.phone = la.phone"
                                  ." left join %s t on tl.phone = t.phone"
                                  ." where %s  and la.accept_adminid >0  group by la.accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }

    public function get_lecture_info_by_zs_new($start_time,$end_time,$status=-1){
        $where_arr=[
            ["tl.add_time >= %u",$start_time,-1],
            ["tl.add_time <= %u",$end_time,-1],
            "tl.is_test_flag =0",
            "tl.account <> 'adrian'",
            //  "(tl.account is not null && tl.account <> '')",
        ];
        if($status==-2){
            $where_arr[] = "tl.status <>4";
        }else{
            $where_arr[] = ["tl.status=%u",$status,-1];
        }
        $sql = $this->gen_sql_new("select la.accept_adminid , count(*) all_num,count(distinct tl.phone) all_count,count(distinct t.phone) tea_count,sum(if(status=1,1,0)) suc_count,sum(confirm_time-add_time) time_count from %s tl left join %s la on tl.phone = la.phone"
                                  ." left join %s t on tl.phone = t.phone"
                                  ." where %s  and la.accept_adminid >0  group by la.accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }



    public function get_lecture_info_by_grade($start_time,$end_time,$status=-1){
        $where_arr=[
            ["confirm_time >= %u",$start_time,-1],
            ["confirm_time <= %u",$end_time,-1],
            ["status=%u",$status,-1],
            "is_test_flag =0",
            "account <> 'adrian'"
        ];
        $sql = $this->gen_sql_new("select substring(grade,1,1)*100 grade_ex, count(*) all_num,count(distinct phone) all_count,sum(if(status=1,1,0)) suc_count,sum(confirm_time-add_time) time_count from %s where %s  and grade>0  group by grade_ex",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["grade_ex"];
        });
    }

    public function get_lecture_info_by_identity($start_time,$end_time,$status=-1){
        $where_arr=[
            ["tl.confirm_time >= %u",$start_time,-1],
            ["tl.confirm_time <= %u",$end_time,-1],
            ["tl.status=%u",$status,-1],
            "tl.is_test_flag =0",
            "tl.account <> 'adrian'"
        ];
        $sql = $this->gen_sql_new("select ta.teacher_type identity_ex, count(*) all_num,count(distinct tl.phone) all_count,sum(if(tl.status=1,1,0)) suc_count,sum(tl.confirm_time-tl.add_time) time_count from %s tl "
                                  ." left join %s ta on tl.phone = ta.phone "
                                  ." where %s group by identity_ex",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["identity_ex"];
        });
    }



    public function get_lecture_info_by_subject_account($start_time,$end_time,$status=-1){
        $where_arr=[
            ["confirm_time >= %u",$start_time,-1],
            ["confirm_time <= %u",$end_time,-1],
            ["status=%u",$status,-1],
            "tl.is_test_flag =0",
            "tl.account <> 'adrian'"
        ];
        $sql = $this->gen_sql_new("select t.teacherid,tl.account,uid,count(*) all_count,sum(if(status=1,1,0)) suc_count,sum(confirm_time-add_time) time_count".
                                  " from %s tl left join %s m on tl.account=m.account  ".
                                  "left join %s t on m.phone = t.phone where %s group by account",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["account"];
        });
    }

    public function get_teacher_list_passed_detail($account,$start_time,$end_time){
        $where_arr=[
            ["tl.confirm_time >= %u",$start_time,-1],
            ["tl.confirm_time <= %u",$end_time,-1],
            ["tl.account= '%s'",$account,""],
            "tl.status =1",
            "tl.is_test_flag =0",
            "(tl.account is not null && tl.account <> '')",
            "t.teacherid is null",
            "tt.teacherid is null"
        ];
        $sql = $this->gen_sql_new("select tl.nick,t.teacherid,tl.phone,tl.confirm_time,tl.account from %s tl left join %s t on tl.phone = t.phone left join %s tt on tl.nick = tt.realname where %s order by tl.confirm_time desc",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return  $this->main_get_list($sql);

    }

    public function get_teacher_througn_detail($start_time,$end_time){
        $where_arr=[
            ["tl.confirm_time >= %u",$start_time,-1],
            ["tl.confirm_time <= %u",$end_time,-1],
            "tl.status =1",
            "tl.is_test_flag =0",
            "(tl.account is not null && tl.account <> '')",
            "t.train_through_new=1",
            "t.train_through_new_time>tl.confirm_time",
            "t.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select t.teacherid,t.train_through_new_time,tl.confirm_time,"
                                  ."t.train_through_new_time-tl.confirm_time time"
                                  ." from %s tl left join %s t on tl.phone = t.phone"
                                  ." where %s and not exists ("
                                  ." select 1 from %s where phone=tl.phone and status=1 "
                                  ."and is_test_flag =0 and confirm_time<tl.confirm_time"
                                  ." )",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return  $this->main_get_list($sql);

    }


    public function get_teacher_list_passed($account,$start_time,$end_time,$subject=-1,$teacher_account=-1,$reference_teacherid=-1,$identity=-1,$tea_subject="",$grade_ex=-1,$train_through_new=-1){
        $where_arr=[
            ["tl.confirm_time >= %u",$start_time,-1],
            ["tl.confirm_time <= %u",$end_time,-1],
            ["tl.account= '%s'",$account,""],
            "tl.status =1",
            "(tl.account is not null && tl.account <> '')",
            "tl.is_test_flag =0",
            ["tl.subject=%u",$subject,-1],
            ["ta.teacher_type = %u",$identity,-1],
            ["tt.teacherid = %u",$teacher_account,-1],
            ["ttt.teacherid = %u",$reference_teacherid,-1],
            ["t.train_through_new = %u",$train_through_new,-1],
        ];

        if(!empty($tea_subject)){
            $where_arr[]="tl.subject in".$tea_subject;
        }
        if($grade_ex>0){
            if($grade_ex==100){
                $where_arr[]="tl.grade >=100 and tl.grade <200";
            }elseif($grade_ex==200){
                $where_arr[]="tl.grade >=200 and tl.grade <300";
            }elseif($grade_ex==300){
                $where_arr[]="tl.grade >=300";
            }


        }

        $sql = $this->gen_sql_new("select distinct t.teacherid "
                                  ." from %s tl left join %s t on tl.phone = t.phone"
                                  ." left join %s m on tl.account = m.account"
                                  ." left join %s tt on tt.phone=m.phone"
                                  ." left join %s ta on tl.phone = ta.phone"
                                  ." left join %s ttt on ta.reference = ttt.phone "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $ss =  $this->main_get_list($sql);
        $arr=[];
        foreach($ss as $v){
            if(!empty( $v["teacherid"])) $arr[$v["teacherid"]] = $v["teacherid"];
        }
        return $arr;

    }

    public function get_teacher_passed_num_by_subject_grade($start_time,$end_time,$subject){
        $where_arr=[
            "tl.status =1",
            "(tl.account is not null && tl.account <> '')",
            "tl.is_test_flag =0",
            ["tl.subject=%u",$subject,-1],
            ["tl.confirm_time >= %u",$start_time,-1],
            ["tl.confirm_time <= %u",$end_time,-1],
        ];

        $sql = $this->gen_sql_new("select accept_adminid,sum(if(substring(tl.grade,1,1)=1,1,0)) primary_num, "
                                  ." sum(if(substring(tl.grade,1,1)=2,1,0)) middle_num,"
                                  ."sum(if(substring(tl.grade,1,1)=3,1,0)) senior_num "
                                  ." from %s tl "
                                  ." left join %s ta on tl.phone = ta.phone"
                                  ." where %s group by ta.accept_adminid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return  $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });

    }
    public function get_teacher_list_passed_by_reference($reference,$start_time,$end_time){
        $where_arr=[
            ["tl.confirm_time >= %u",$start_time,-1],
            ["tl.confirm_time <= %u",$end_time,-1],
            ["ta.reference= '%s'",$reference,""],
            "ta.reference is not null",
            "tl.status =1"
        ];
        if(empty($reference)){
            $where_arr[] = "ta.reference =''";
        }else{
            $where_arr[] = ["ta.reference= '%s'",$reference,""];
        }

        $sql = $this->gen_sql_new("select t.teacherid from %s tl ".
                                  "left join %s t on tl.phone = t.phone".
                                  " left join %s ta on ta.phone = tl.phone ".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $ss =  $this->main_get_list($sql);
        $arr=[];
        foreach($ss as $v){
            if(!empty( $v["teacherid"])) $arr[] = $v["teacherid"];
        }
        return $arr;

    }
    public function get_teacher_list_passed_by_reference_is_null($start_time,$end_time){
        $where_arr=[
            ["tl.confirm_time >= %u",$start_time,-1],
            ["tl.confirm_time <= %u",$end_time,-1],
            "tl.status =1",
            "ta.reference is null "
        ];
        $sql = $this->gen_sql_new("select t.teacherid from %s tl ".
                                  "left join %s t on tl.phone = t.phone".
                                  " left join %s ta on ta.phone = tl.phone ".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $ss =  $this->main_get_list($sql);
        $arr=[];
        foreach($ss as $v){
            if(!empty( $v["teacherid"])) $arr[] = $v["teacherid"];
        }
        return $arr;

    }


    public function get_sth($phone){
        $sql = $this->gen_sql_new("select * from %s where phone = '%s'",
                                  self::DB_TABLE_NAME,
                                  $phone
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_subject($teacherid){
        $where_arr = [
            ["t.teacherid='%s'",$teacherid,0]
        ];
        $sql = $this->gen_sql_new("select t.subject "
                                  ." from %s tl"
                                  ." left join %s t on t.phone=tl.phone"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_interview_info_by_reference($time){
        $where_arr=[
            ["confirm_time >= %u",$time,-1],
            "account <> 'adrian'"
        ];
        $sql = $this->gen_sql_new("select subject,count(distinct reference) all_reference from %s tl".
                                  " left join %s ta on tl.phone = ta.phone".
                                  " where %s group by subject",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }
    public function get_interview_info_by_reference_count($time){
        $where_arr=[
            ["confirm_time >= %u",$time,-1],
            "account <> 'adrian'"
        ];
        $sql = $this->gen_sql_new("select count(distinct reference) all_reference from %s tl".
                                  " left join %s ta on tl.phone = ta.phone".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_interview_info_by_reference_detail($subject,$time){
        $where_arr=[
            ["confirm_time >= %u",$time,-1],
            ["tl.subject = %u",$subject,-1],
            "account <> 'adrian'",
            "(t.realname <> '' or t.nick <> '')"
        ];
        $sql = $this->gen_sql_new("select t.realname,count(*) all_count,sum(if(status=1,1,0)) suc_count from %s tl".
                                  " left join %s ta on tl.phone = ta.phone".
                                  " left join %s t on ta.reference = t.phone".
                                  " where %s group by reference",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_reference_is_null_list(){
        $sql = $this->gen_sql_new("select tl.phone,reference,nick "
                                  ." from %s tl "
                                  ." left join %s ta on tl.phone = ta.phone "
                                  ." where ta.reference is null "
                                  ." and confirm_time >= %u"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,time()-7*86400
        );
        return $this->main_get_list($sql);
    }

    public function get_simple_info($phone){
        $where_arr = [
            ["phone='%s'",$phone,0],
        ];

        $sql = $this->gen_sql_new("select subject,grade,identity,add_time"
                                  ." from %s"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_different_teacher_info(){
        $sql = $this->gen_sql_new("select nick,phone,grade,subject,identity,add_time"
                                  ." from %s tl "
                                  ." where not exists ("
                                  ." select 1 from %s where phone=tl.phone "
                                  ." )"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_week_confirm_num($adminid,$start_time,$end_time){
        $where_arr=[
            ["confirm_time >=%u",$start_time,-1],
            ["confirm_time <%u",$end_time,-1],
            ["m.uid=%u",$adminid,-1]
        ];
        $sql = $this->gen_sql_new("select count(*) num from %s l join %s m on l.account=m.account where %s ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function tongji_teacher_info_by_accept_adminid($start_time,$end_time){
        $where_arr = [
            ["add_time >=%u",$start_time,-1],
            ["add_time <%u",$end_time,-1]
        ];
        $sql = $this->gen_sql_new("select la.accept_adminid,count(distinct l.phone,l.subject) video_count "
                                  ." from %s l "
                                  ." join %s la on l.phone=la.phone "
                                  ." where %s "
                                  ." and l.status!=4"
                                  ." and la.accept_adminid>0 "
                                  ." group by la.accept_adminid "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });

    }
    public function tongji_suc_teacher_info_by_accept_adminid($start_time,$end_time){
        $where_arr=[
            ["add_time >=%u",$start_time,-1],
            ["add_time <%u",$end_time,-1],
            "l.status=1"
        ];
        $sql = $this->gen_sql_new("select la.accept_adminid,count(distinct l.phone,l.subject) suc_count".
                                  " from %s l join %s la on l.phone=la.phone where %s and la.accept_adminid >0 ".
                                  "group by la.accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });

    }
    public function tongji_fail_teacher_info_by_accept_adminid($start_time,$end_time){
        $where_arr=[
            ["add_time >=%u",$start_time,-1],
            ["add_time <%u",$end_time,-1],
            "l.status=2"
        ];
        $sql = $this->gen_sql_new("select la.accept_adminid,count(distinct l.phone,l.subject) fail_count".
                                  " from %s l join %s la on l.phone=la.phone where %s and la.accept_adminid >0 ".
                                  "group by la.accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });

    }



    public function get_lecture_video_info_by_accept_adminid($start_time,$end_time,$accept_adminid){
        $where_arr=[
            ["add_time >=%u",$start_time,-1],
            ["add_time <%u",$end_time,-1],
            ["accept_adminid=%u",$accept_adminid,-1]
        ];
        $sql= $this->gen_sql_new("select sum(grade>=100 and grade<200) xx_count,sum(grade>=200 and grade<300) cz_count,sum(grade>=300) gz_count,count(*) all_count,l.subject from %s l "
                                 ." left join %s la on la.phone = l.phone"
                                 ." where %s group by l.subject",
                                 self::DB_TABLE_NAME,
                                 t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);


    }

    public function get_lecture_suc_info_by_accept_adminid($start_time,$end_time,$accept_adminid){
        $where_arr=[
            ["add_time >=%u",$start_time,-1],
            ["add_time <%u",$end_time,-1],
            ["accept_adminid=%u",$accept_adminid,-1],
            "l.status=1"
        ];
        $sql= $this->gen_sql_new("select sum(grade>=100 and grade<200) xx_count,sum(grade>=200 and grade<300) cz_count,sum(grade>=300) gz_count,count(*) all_count,l.subject from %s l "
                                 ." left join %s la on la.phone = l.phone"
                                 ." where %s group by l.subject",
                                 self::DB_TABLE_NAME,
                                 t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);


    }

    public function get_teacher_lecture_info_by_time_list_new($last_month_start,$month_start){
        $where_arr=[
            ["add_time >=%u",$last_month_start,-1],
            ["add_time <%u",$month_start,-1],
            "status=1"
        ];
        $sql= $this->gen_sql_new("select subject,grade from %s "
                                 ." where %s ",
                                 self::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_research_teacher_test_lesson_info($start_time,$end_time,$tea_arr){
        $confirm_time = strtotime(date("2017-01-05")); // 
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "tl.status=1",
            "tl.confirm_time>=".$confirm_time,
            // "ttt.require_admin_type=2",
            // "mm.del_flag=0",
            "mm.account_role=2",
            "m.del_flag =0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $this->where_arr_teacherid($where_arr,"t.teacherid", $tea_arr );
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid) person_num,t.realname,t.teacherid,m.uid from %s tl "
                                  ." left join %s m on tl.account =m.account"
                                  ." left join %s t on m.phone = t.phone"
                                  ." left join %s tt on tl.phone = tt.phone"
                                  ." left join %s l on tt.teacherid = l.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s ttt on ttt.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s mm on mm.uid = tr.cur_require_adminid"
                                  ." where %s group by t.teacherid order by order_num desc",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_interview_test_lesson_info($start_time,$end_time,$adminid){
        $confirm_time = strtotime(date("2017-01-05"));
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "tl.status=1",
            "tl.confirm_time>=".$confirm_time,
            // "ttt.require_admin_type=2",
            //  "mm.del_flag=0",
            "mm.account_role=2",
            //  "m.del_flag =0",
            ["m.uid=%u",$adminid,-1]
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid) person_num,tt.realname,tt.teacherid from %s tl "
                                  ." left join %s m on tl.account =m.account"
                                  ." left join %s tt on tl.phone = tt.phone"
                                  ." left join %s l on tt.teacherid = l.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s ttt on ttt.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s mm on mm.uid = tr.cur_require_adminid"
                                  ." where %s group by tt.teacherid order by order_num desc",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function  get_teacher_lecture_all_info_list_new($start_time,$subject){
        $where_arr=[
            ["confirm_time >=%u",$start_time,-1],
            ["subject=%u",$subject,-1]
        ];
        $sql =$this->gen_sql_new("select count(distinct phone,subject) all_confirm from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }
    public function  get_teacher_lecture_all_info_list_pass($start_time,$subject){
        $where_arr=[
            ["tl.confirm_time >=%u",$start_time,-1],
            ["tl.subject=%u",$subject,-1],
            "tl.status=1"
        ];
        $sql =$this->gen_sql_new("select count(distinct tl.phone,tl.subject) all_confirm,sum(if(lesson_start>0 and tss.lessonid>0,create_time,0)) create_time_count,sum(if(lesson_start>0 and tss.lessonid>0,lesson_start,0)) lesson_start_count,sum(if(lesson_start>0 and tss.lessonid>0,1,0)) lesson_count".
                                 " from %s tl ".
                                 " left join %s t on tl.phone=t.phone".
                                 " left join %s l on (t.teacherid = l.teacherid and lesson_start=(select min(lesson_start) from %s where teacherid=l.teacherid and lesson_type=2 and lesson_del_flag =0))".
                                 " left join %s tss on (l.lessonid = tss.lessonid and tss.success_flag <>2)".
                                 " where %s",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_row($sql);

    }

    public function get_new_teacher_test_lesson_info($start_time,$end_time,$grade_part_ex=-1,$subject=-1,$train_through_new=-1){

        $time = strtotime(date("2017-01-05"));

        $where_arr=[
            ["t.grade_part_ex=%u",$grade_part_ex,-1],
            ["t.subject=%u",$subject,-1],
            ["t.train_through_new=%u",$train_through_new,-1],
            "tl.confirm_time >=".$time,
            "tl.status=1",
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "m.del_flag=0",
            "m.account_role=2"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql =$this->gen_sql_new("select t.teacherid,t.realname,count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid,l.teacherid) all_lesson,t.train_through_new_time,t.train_through_new ".
                                 " from %s tl ".
                                 " left join %s t on tl.phone=t.phone".
                                 " left join %s l on t.teacherid = l.teacherid".
                                 " left join %s tss on l.lessonid = tss.lessonid".
                                  " left join %s c on ".
                                  " (l.userid = c.userid ".
                                  " and l.teacherid = c.teacherid ".
                                  " and l.subject = c.subject ".
                                  " and c.course_type=0 and c.courseid >0) ".
                                  "left join %s tq on tq.require_id = tss.require_id" .
                                 " left join %s m on tq.cur_require_adminid = m.uid ".
                                 " where %s group by t.teacherid",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 t_course_order::DB_TABLE_NAME,
                                 t_test_lesson_subject_require::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }


    public function get_new_teacher_no_test_lesson_info_wx($tea_arr=[]){
        $time = strtotime(date("2017-01-05"));
        $where_arr=[
            "tl.confirm_time >=".$time,
            "tl.status=1",
            "t.is_test_user=0",
            " t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%'"
        ];

        $where_arr[]=$this->where_get_not_in_str( "t.teacherid", $tea_arr, false );
        $sql =$this->gen_sql_new("select distinct t.teacherid,t.realname,t.train_through_new_time,t.train_through_new,t.subject,t.grade_part_ex,tl.phone,tl.confirm_time  ".
                                 " from %s tl ".
                                 " left join %s t on tl.phone=t.phone".
                                 " where %s order by teacherid  ",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_list($sql);

    }

    public function get_new_teacher_no_test_lesson_info($page_num,$grade_part_ex=-1,$subject=-1,$train_through_new=-1,$have_test_lesson_flag=-1,$tea_arr=[]){
        $time = strtotime(date("2017-01-05"));
        $where_arr=[
            ["t.grade_part_ex=%u",$grade_part_ex,-1],
            ["t.train_through_new=%u",$train_through_new,-1],
            ["t.subject=%u",$subject,-1],
            "tl.confirm_time >=".$time,
            "tl.status=1",
            "t.is_test_user=0",
            " t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%'"
        ];

        if($have_test_lesson_flag==1){
            $where_arr[]=$this->where_get_in_str( "t.teacherid", $tea_arr, false );
        }else if($have_test_lesson_flag==0){
            $where_arr[]=$this->where_get_not_in_str( "t.teacherid", $tea_arr, false );
        }
        $sql =$this->gen_sql_new("select distinct t.teacherid,t.realname,t.train_through_new_time,t.train_through_new,t.subject,t.grade_part_ex,tl.phone,tl.confirm_time  ".
                                 " from %s tl ".
                                 " left join %s t on tl.phone=t.phone".
                                 " where %s ",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function get_new_teacher_no_test_lesson_info_tongji($grade_part_ex=-1,$subject=-1,$train_through_new=-1,$have_test_lesson_flag=-1,$tea_arr=[]){
        $time = strtotime(date("2017-01-05"));
        $where_arr=[
            ["t.grade_part_ex=%u",$grade_part_ex,-1],
            ["t.train_through_new=%u",$train_through_new,-1],
            ["t.subject=%u",$subject,-1],
            "tl.confirm_time >=".$time,
            "tl.status=1",
            "t.is_test_user=0",
            " t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%'"
        ];

        if($have_test_lesson_flag==1){
            $where_arr[]=$this->where_get_in_str( "t.teacherid", $tea_arr, false );
        }else if($have_test_lesson_flag==0){
            $where_arr[]=$this->where_get_not_in_str( "t.teacherid", $tea_arr, false );
        }
        $sql =$this->gen_sql_new("select   distinct t.teacherid,t.realname,t.train_through_new_time,t.train_through_new,t.subject,t.grade_part_ex,tl.phone,tl.confirm_time,l.lesson_start".
                                 " from %s tl ".
                                 " left join %s t on tl.phone=t.phone".
                                 " left join %s l on (t.teacherid = l.teacherid and l.lesson_type=2 and l.lesson_del_flag=0 and l.lesson_user_online_status=1 and l.lesson_start = (select min(ll.lesson_start) from %s ll join %s tss on ll.lessonid = tss.lessonid join %s tr on tss.require_id = tr.require_id join %s m on tr.cur_require_adminid = m.uid where ll.teacherid = t.teacherid and ll.lesson_type=2 and ll.lesson_del_flag=0 and ll.lesson_user_online_status=1 and m.account_role=2 and m.del_flag=0 ) )".
                                 " where %s ",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 t_test_lesson_subject_require::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_list($sql);

    }

    public function get_new_teacher_no_test_lesson_info_tongji_num($grade_part_ex=-1,$subject=-1,$train_through_new=-1,$have_test_lesson_flag=-1,$tea_arr=[]){
        $time = strtotime(date("2017-01-05"));
        $where_arr=[
            ["t.grade_part_ex=%u",$grade_part_ex,-1],
            ["t.train_through_new=%u",$train_through_new,-1],
            ["t.subject=%u",$subject,-1],
            "tl.confirm_time >=".$time,
            "tl.status=1",
            "t.is_test_user=0",
            " t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%'"
        ];

        if($have_test_lesson_flag==1){
            $where_arr[]=$this->where_get_in_str( "t.teacherid", $tea_arr, false );
        }else if($have_test_lesson_flag==0){
            $where_arr[]=$this->where_get_not_in_str( "t.teacherid", $tea_arr, false );
        }
        $sql =$this->gen_sql_new("select   distinct t.teacherid,t.realname,t.train_through_new_time,t.train_through_new,t.subject,t.grade_part_ex ".
                                 " from %s tl ".
                                 " left join %s t on tl.phone=t.phone".
                                 " left join %s l on (t.teacherid = l.teacherid and l.lesson_type=2 and l.lesson_del_flag=0 and l.lesson_user_online_status=1 and l.lesson_start = (select min(ll.lesson_start) from %s ll join %s tss on ll.lessonid = tss.lessonid join %s tr on tss.require_id = tr.require_id join %s m on tr.cur_require_adminid = m.uid where ll.teacherid = t.teacherid and ll.lesson_type=2 and ll.lesson_del_flag=0 and ll.lesson_user_online_status=1 and m.account_role=2 and m.del_flag=0 ) )".
                                 " where %s ",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                 t_test_lesson_subject_require::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_list($sql);

    }



    public function get_new_teacher_no_test_lesson_info_total($grade_part_ex=-1,$subject=-1,$train_through_new=-1){
        $time = strtotime(date("2017-01-05"));
        $where_arr=[
            ["t.grade_part_ex=%u",$grade_part_ex,-1],
            ["t.train_through_new=%u",$train_through_new,-1],
            ["t.subject=%u",$subject,-1],
            "tl.confirm_time >=".$time,
            "tl.status=1",
            "t.is_test_user=0",
            " t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%'"
        ];

        $sql =$this->gen_sql_new("select count(distinct t.teacherid)  ".
                                 " from %s tl ".
                                 " left join %s t on tl.phone=t.phone".
                                 " where %s ",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_value($sql);

    }



    public function get_order_lesson_num($start_time,$subject){
        $end_time=time();
        $where_arr=[
            ["tl.confirm_time >=%u",$start_time,-1],
            ["tl.subject=%u",$subject,-1],
            "tl.status=1",
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            ["l.subject=%u",$subject,-1],
            "require_admin_type=2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_number,count(distinct l.lessonid) success_lesson"
                                  ." from %s tl "
                                  ." left join %s t on tl.phone=t.phone"
                                  ." left join  %s l on t.teacherid =l.teacherid"
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
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_all_lecture_info_new_haha_list(){
        $sql = $this->gen_sql_new("select * from %s where teacher_operation_score =0 and status >0 and teacher_mental_aura_score >0",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_fail_ave_score($start_time,$end_time){
        $where_arr=[
            "status=2",
            "is_test_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"confirm_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select avg(teacher_mental_aura_score)/10 teacher_mental_aura_score_ave,avg(teacher_exp_score)/10 teacher_exp_score_ave,avg(teacher_point_explanation_score)/5 teacher_point_explanation_score_ave,avg(teacher_class_atm_score)/10 teacher_class_atm_score_ave,avg(teacher_method_score)/10 teacher_method_score_ave,avg(teacher_knw_point_score)/10 teacher_knw_point_score_ave,avg(teacher_dif_point_score)/10 teacher_dif_point_score_ave,avg(teacher_blackboard_writing_score)/5 teacher_blackboard_writing_score_ave,avg(teacher_explain_rhythm_score)/10 teacher_explain_rhythm_score_ave,avg(teacher_language_performance_score)/10 teacher_language_performance_score_ave,avg(teacher_operation_score)/5 teacher_operation_score_ave,avg(teacher_environment_score)/5 teacher_environment_score_ave from %s"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_lecture_teacher_test_lesson_info($start_time,$end_time){
        $where_arr=[
            "tl.status=1",
            "tl.is_test_flag=0",
            "l.lesson_type=2",
            "l.lesson_del_flag=0",
            "tss.success_flag <>2",
            "l.lesson_user_online_status =1",
            "m.account_role=2",
            "m.del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"tl.confirm_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select distinct tl.phone,tl.add_time,tl.confirm_time,o.orderid,realname,l.lessonid"
                                  ." from %s tl left join %s t on (tl.phone = t.phone and tl.subject=t.subject)"
                                  ." left join %s l on l.teacherid = t.teacherid"
                                  ." left join %s o on (o.from_test_lesson_id = l.lessonid and o.contract_type=0)"
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_teacher_lecture_subject_info(){
        $sql = $this->gen_sql_new("select tl.subject,tl.grade,t.subject tea_subject,t.grade_part_ex,t.realname,t.teacherid"
                                  ." from %s tl join %s t on (tl.status=1 and tl.phone= t.phone)"
                                  ." where t.realname not like '%%alan%%' and t.realname not like '%%不要审核我%%' and  t.realname not like '%%试讲%%'",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_interview_info_by_account($start_time,$end_time){
        $where_arr=[
            "m.del_flag =0",
            "is_test_flag =0"
        ];

        $this->where_arr_add_time_range($where_arr,"confirm_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select tl.account,m.uid,count(*) interview_num,sum(confirm_time - add_time) interview_time "
                                  ." from %s tl "
                                  ." left join %s m on tl.account =m.account"
                                  ." where %s group by m.uid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });

    }

    public function get_interview_info_by_subject($start_time,$end_time){
        $where_arr=[
            "m.del_flag =0",
            "is_test_flag =0"
        ];

        $this->where_arr_add_time_range($where_arr,"confirm_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select tl.account,tl.subject,count(*) interview_num,sum(confirm_time - add_time) interview_time "
                                  ." from %s tl "
                                  ." left join %s m on tl.account =m.account"
                                  ." where %s group by m.uid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }


    public function get_interview_lesson_order_info($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "tl.status=1",
             "mm.del_flag=0",
            "mm.account_role=2",
            "m.del_flag =0"
        ];

        $this->where_arr_add_time_range($where_arr,"tl.confirm_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid) person_num,m.uid "
                                  ." from %s tl "
                                  ." left join %s m on tl.account =m.account"
                                  ." left join %s t on tl.phone = t.phone "
                                  ." left join %s l on t.teacherid = l.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s mm on mm.uid = tr.cur_require_adminid"
                                  ." where %s group by m.uid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });

    }

    public function get_interview_lesson_order_info_subject($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "tl.status=1",
            "mm.del_flag=0",
            "mm.account_role=2",
            "m.del_flag =0"
        ];

        $this->where_arr_add_time_range($where_arr,"tl.confirm_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid) person_num,tl.subject "
                                  ." from %s tl "
                                  ." left join %s m on tl.account =m.account"
                                  ." left join %s t on tl.phone = t.phone "
                                  ." left join %s l on t.teacherid = l.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s mm on mm.uid = tr.cur_require_adminid"
                                  ." where %s group by tl.subject ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });
    }

    public function get_research_teacher_test_lesson_info_account($start_time,$end_time){
        $confirm_time = strtotime(date("2017-01-05"));
        $where_arr = [
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "tl.status=1",
            "tl.confirm_time>=".$confirm_time,
            // "ttt.require_admin_type=2",
            "mm.del_flag=0",
            "mm.account_role=2",
            "m.del_flag =0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid) person_num,m.uid from %s tl "
                                  ." left join %s m on tl.account =m.account"
                                  ." left join %s tt on tl.phone = tt.phone"
                                  ." left join %s l on tt.teacherid = l.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s ttt on ttt.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s mm on mm.uid = tr.cur_require_adminid"
                                  ." where %s group by m.uid ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_research_teacher_test_lesson_info_subject($start_time,$end_time){
        $confirm_time = strtotime(date("2017-01-05"));
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "tl.status=1",
            "tl.confirm_time>=".$confirm_time,
            // "ttt.require_admin_type=2",
            "mm.del_flag=0",
            "mm.account_role=2",
            "m.del_flag =0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) order_num,count(distinct l.userid) person_num,l.subject from %s tl "
                                  ." left join %s m on tl.account =m.account"
                                  ." left join %s tt on tl.phone = tt.phone"
                                  ." left join %s l on tt.teacherid = l.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s c on "
                                  ." (l.userid = c.userid "
                                  ." and l.teacherid = c.teacherid "
                                  ." and l.subject = c.subject "
                                  ." and c.course_type=0 and c.courseid >0) "
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s ttt on ttt.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s mm on mm.uid = tr.cur_require_adminid"
                                  ." where %s group by l.subject ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
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

    public function tongji_teacher_lecture_total($time){
        $sql = $this->gen_sql_new("select count(distinct phone) from %s where confirm_time>=%u",self::DB_TABLE_NAME,$time);
        return $this->main_get_value($sql);
    }

    public function get_tea_pass_time($time){

        if(is_array($time)){
            $start_time = $time['start_time'];
            $end_time   = $time['end_time'];
            $time_str = "confirm_time>=$start_time and confirm_time < $end_time ";
        }else{
            $time_str = "confirm_time>=$time";
        }

        $sql = $this->gen_sql_new("select count(*) num,sum(confirm_time - add_time) time from %s"
                                  ." where status = 1 and %s and confirm_time>add_time",
                                  self::DB_TABLE_NAME,
                                  $time_str
        );
        return $this->main_get_row($sql);
    }

    public function get_tea_tran_pass_time($time){

        if(is_array($time)){
            $start_time = $time['start_time'];
            $end_time   = $time['end_time'];
            $time_str = "tl.confirm_time>=$start_time and tl.confirm_time < $end_time ";
        }else{
            $time_str = "tl.confirm_time>=$time";
        }


        $sql = $this->gen_sql_new("select count(*) num,sum(t.train_through_new_time - tl.confirm_time) time from %s tl "
                                  ." left join %s t on tl.phone = t.phone"
                                  ." where tl.status = 1 and %s and tl.confirm_time>tl.add_time and t.train_through_new_time > tl.confirm_time",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $time_str
        );
        return $this->main_get_row($sql);
    }

    public function get_new_teacher_first_lesson_time($time){
        $where_arr=[
            "t.train_through_new=1",
            // "tl.confirm_time >=".$time,
            "tl.status=1",
            "t.is_test_user=0",
            " t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%'"
        ];


        if(is_array($time)){
            $start_time = $time['start_time'];
            $end_time   = $time['end_time'];

            $this->where_arr_add_time_range($where_arr,"tl.confirm_time",$start_time,$end_time);
        }else{
            $where_arr[] = "tl.confirm_time>=$time";
        }



        /* if($have_test_lesson_flag==1){
            $where_arr[]=$this->where_get_in_str( "t.teacherid", $tea_arr, false );
        }else if($have_test_lesson_flag==0){
            $where_arr[]=$this->where_get_not_in_str( "t.teacherid", $tea_arr, false );
            }*/
        $sql =$this->gen_sql_new("select  AVG(l.lesson_start) lesson_time,AVG(t.train_through_new_time) confirm_time".
                                 " from %s tl ".
                                 " left join %s t on tl.phone=t.phone".
                                 " left join %s l on (t.teacherid = l.teacherid and l.lesson_type=2 and l.lesson_del_flag=0 and l.lesson_user_online_status=1 and l.lesson_start = (select min(lesson_start) from %s  where teacherid = t.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status=1  ) )".
                                 " where %s and t.train_through_new_time < l.lesson_start",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_row($sql);
    }

    public function get_new_teacher_fifth_lesson_time($time){
        $where_arr=[
            "t.train_through_new=1",
            // "tl.confirm_time >=".$time,
            "tl.status=1",
            "t.is_test_user=0",
            " t.realname not like '%%alan%%' and  t.realname not like '%%不要审核%%' and  t.realname not like '%%gavan%%' and t.realname not like '%%阿蓝%%'"
        ];



        if(is_array($time)){
            $start_time = $time['start_time'];
            $end_time   = $time['end_time'];
            $this->where_arr_add_time_range($where_arr,"tl.confirm_time",$start_time,$end_time);
        }else{
            $where_arr[] = "tl.confirm_time>=$time";
        }



        /* if($have_test_lesson_flag==1){
           $where_arr[]=$this->where_get_in_str( "t.teacherid", $tea_arr, false );
           }else if($have_test_lesson_flag==0){
           $where_arr[]=$this->where_get_not_in_str( "t.teacherid", $tea_arr, false );
           }*/
        $sql =$this->gen_sql_new("select  AVG(ll.lesson_start) lesson_time,AVG(l.lesson_start) confirm_time".
                                 " from %s tl ".
                                 " left join %s t on tl.phone=t.phone".
                                 " left join %s l on (t.teacherid = l.teacherid and l.lesson_type=2 and l.lesson_del_flag=0 and l.lesson_user_online_status=1 and l.lesson_start = (select min(lesson_start) from %s  where teacherid = t.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status=1  ) )".
                                 " left join %s ll on t.teacherid = ll.teacherid and ll.lesson_type=2 and ll.lesson_del_flag=0 and ll.lesson_user_online_status=1 and ll.lesson_start = (select lesson_start from %s where teacherid = t.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status in (0,1) order by lesson_start limit 4,1)".
                                 " where %s and t.train_through_new_time < l.lesson_start and ll.lesson_start>0",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 $where_arr);
        return $this->main_get_row($sql);
    }

    public function check_is_exists($phone){
        $where_arr = [
            ["phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select count(1)"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_lecture_list($phone){
        $where_arr = [
            ["phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select grade,subject,status"
                                  ." from %s tl"
                                  ." where %s "
                                  ." and not exists("
                                  ." select 1 from %s where tl.phone=phone and tl.subject=subject and tl.grade=grade "
                                  ." and tl.add_time<add_time"
                                  .")"
                                  ." group by grade,subject"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_all_interview_count($start_time,$end_time,$status,$subject_ex=-1){
        $where_arr=[
            "is_test_flag =0",
            "(account <> 'adrian' && account <> 'alan' && account <> 'jack')",
            ["status=%u",$status,-1]
        ];
        if($subject_ex==1){
            $where_arr[]="subject in (1,3)";
        }elseif($subject_ex==2){
             $where_arr[]="subject in (2)";
        }elseif($subject_ex==3){
             $where_arr[]="subject in (4,5,6,7,8,9,10)";
        }
        $this->where_arr_add_time_range($where_arr,"confirm_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct phone) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_video_apply_num($start_time,$end_time){
        $where_arr=[
            "is_test_flag =0"
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct phone) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_lecture_origin_list($start_time,$end_time){
        $where_arr = [
            ["add_time>%u",$start_time,0],
            ["add_time<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select teacher_ref_type,count(distinct(tl.phone)) as lecture_total,"
                                  ." sum(if(tl.status=1,1,0)) as pass_total"
                                  ." from %s tl"
                                  ." left join %s ta on tl.phone=ta.phone"
                                  ." left join %s t on ta.reference=t.phone"
                                  ." where %s"
                                  ." group by t.teacher_ref_type"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacher_ref_type'];
        });
    }

    public function get_re_submit_num($phone,$subject,$grade=-1){
        $where_arr=[
            "status =3",
            ["subject=%u",$subject,-1],
            ["grade=%u",$grade,-1],
            ["phone='%s'",$phone,-1],
        ];
        $sql = $this->gen_sql_new("select count(*) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function reset_lecture_grade(){
        $sql = $this->gen_sql_new("update %s set grade=100 where grade<200"
                                  ,self::DB_TABLE_NAME
        );
        $this->main_update($sql);
        $sql = $this->gen_sql_new("update %s set grade=200 where grade<300 and grade>200"
                                  ,self::DB_TABLE_NAME
        );
        $this->main_update($sql);
        $sql = $this->gen_sql_new("update %s set grade=300 where grade>300"
                                  ,self::DB_TABLE_NAME
        );
        $this->main_update($sql);
        return true;
    }


    public function get_interview_acc($phone){
        $sql = $this->gen_sql_new("select tl.account from %s tl where tl.phone = '%s' and tl.status=1 and confirm_time =(select max(confirm_time) from %s where phone=tl.phone and status=1)",
                                  self::DB_TABLE_NAME,
                                  $phone,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function get_have_retrial_info(){
        $sql = $this->gen_sql_new('select * from %s where retrial_info <> "" ',
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }

    public function check_teacher_lecture_info($phone,$grade,$subject){
        $where_arr = [
            ["phone='%s'",$phone,""],
            ["grade=%u",$grade,0],
            ["subject=%u",$subject,0],
        ];
        $sql = $this->gen_sql_new("select 1 "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_id_list_by_phone($phone){
        $where_arr = [
            ["phone='%s'",$phone,0]
        ];
        $sql = $this->gen_sql_new("select id "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function check_have_video($phone){
        $sql = $this->gen_sql_new("select 1 from %s where phone = '%s' and status <>4",self::DB_TABLE_NAME,$phone);
        return $this->main_get_value($sql);
    }

    public function get_last_interview_by_phone($phone){
        $sql = $this->gen_sql_new("select tl.reason from %s tl where tl.phone= '%s' and tl.confirm_time = (select max(confirm_time)  from %s where phone = tl.phone)",
                                  self::DB_TABLE_NAME,
                                  $phone,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function get_passed_interview_by_phone($phone,$subject,$grade){
        $sql = $this->gen_sql_new("select reason from %s  where phone= '%s' and subject = %u and grade=%u and status=1",
                                  self::DB_TABLE_NAME,
                                  $phone,
                                  $subject,
                                  $grade
        );
        return $this->main_get_value($sql);
    }


    public function get_video_add_num_by_reference($start_time,$end_time){
        $where_arr=[
            "tl.is_test_flag =0"
        ];
        $this->where_arr_add_time_range($where_arr,"tl.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct tl.phone) video_add_num,ta.reference,t.teacher_ref_type,c.channel_id,c.channel_name,t.realname,t.phone"
                                  ." from %s tl "
                                  ." left join %s ta on tl.phone = ta.phone"
                                  ." left join %s t on ta.reference = t.phone"
                                  ." left join %s cg on t.teacher_ref_type = cg.ref_type"
                                  ." left join %s c on cg.channel_id = c.channel_id"
                                  ." where %s group by ta.reference",
                                  self::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_admin_channel_group::DB_TABLE_NAME,
                                  t_admin_channel_list::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql,function($item){
            return $item["reference"];
        });
 
    }


    public function get_video_flag($teacherid){
        $sql = $this->gen_sql_new("  select tl.id from %s tl "
                                  ." join %s t on t.phone=tl.phone where t.teacherid=$teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_tongji_lz($start_time,$end_time){
        /*
        $where_arr = [
            ["add_time>%u",$start_time,-1],
            ["add_time<%u",$end_time,-1],
            "status=0",
            "is_test_flag=0"
        ];
        $sql = $this->gen_sql_new("select subject, count(subject)  as sum "
                                  ." from %s "
                                  ." where %s group by subject"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
        */

        $where_arr = [
            ["b.add_time>%u",$start_time,0],
            ["b.add_time<%u",$end_time,0],
            'b.status=0',
            'b.is_test_flag=0',
            ["not exists(select 1 from %s where b.grade=grade and b.phone=phone and b.subject=subject and b.add_time<add_time)",
             self::DB_TABLE_NAME,""],
        ];
        $group_str = "group by b.phone,b.subject";

        $sql = $this->gen_sql_new("select b.subject,b.id "
                                  ." from %s as b"
                                  ." left join %s la on b.phone=la.phone"
                                  ." left join %s t on t.phone=la.reference"
                                  ." left join %s tt on b.phone=tt.phone"
                                  ." left join %s ttt on b.phone=ttt.phone"
                                  ." left join %s m on la.accept_adminid = m.uid"
                                  ." where %s "
                                  ." %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$group_str
        );
        return $this->main_get_list($sql);
    }

    public function get_data_to_teacher_flow($start_time, $end_time) {
        $where_arr = [
            ["confirm_time>%u", $start_time, 0],
            ["confirm_time<%u", $end_time, 0],
            //["phone='%s'",$phone,0],
            "status=1",
            "confirm_time!=0"
        ];
        $sql = $this->gen_sql_new("select subject,grade,confirm_time,phone from %s tl where %s "
                                  ." and not exists (select 1 from %s "
                                  ." where tl.phone=phone and tl.add_time>add_time and status=1 and confirm_time!=0)"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql, function ( $item) {
            return $item['phone'];
        });
    }


    public function get_teacher_first_interview_score_info($phone){
        $where_arr = [
            ["phone='%s'",$phone,0],
            "status=1",
        ];
        $sql = $this->gen_sql_new("select teacher_lecture_score,confirm_time"
                                  ." from %s "
                                  ." where %s order by confirm_time"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_phone_data($start_time,$end_time) {
        $where_arr = [
            ['confirm_time>%u',$start_time,0],
            ['confirm_time<%u',$end_time,0],
            "status=1"
        ];
        $sql = $this->gen_sql_new("select phone from %s where %s ", self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_confirm_for_phone($phone) {
        $sql = $this->gen_sql_new("select confirm_time,phone from %s where phone in (%s)",self::DB_TABLE_NAME,$phone);
        return $this->main_get_list($sql);
    }
}