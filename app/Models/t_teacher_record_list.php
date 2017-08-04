<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_record_list extends \App\Models\Zgen\z_t_teacher_record_list
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_teacher_record_list($teacherid,$type){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["type=%u",$type,0],
        ];
        $sql = $this->gen_sql_new("select record_info,record_score,add_time,acc,limit_plan_lesson_type,"
                                  ." is_freeze,grade_range,seller_require_flag,limit_plan_lesson_type_old,"
                                  ." limit_week_lesson_num_new,limit_week_lesson_num_old,current_acc "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_info($teacherid,$type,$add_time){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            ["type=%u",$type,0],
            ["add_time = %u",$add_time,0]
        ];

        $sql=$this->gen_sql_new("select * "
                                ." from %s "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_record_info_new($teacherid,$type){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            ["type=%u",$type,0]
        ];
        $sql=$this->gen_sql_new("select * "
                                ." from %s "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_record_list_time($time){
        $where_arr=[
            ["add_time > %u",$time,0]
        ];

        $sql=$this->gen_sql_new("select * "
                                ." from %s "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_record_list_time_new($time){
        $where_arr=[
            ["add_time > %u",$time,0],
            "r.type=1",
            "(t.nick <> '刘辉' and t.realname <> '刘辉' and t.nick <> 'becky老师' and t.realname <> 'becky老师')",
            " r.acc <> 'ted' and r.acc <> 'adrian'",
            "t.teacherid not in (51094,53289,59896,130462,61828,55161,90732,130500,134439,130503,130506,130490,130498,85081)"
        ];

        $sql=$this->gen_sql_new("select distinct r.teacherid,t.nick,t.subject,t.create_time,r.record_monitor_class,r.record_info,r.acc,courseware_flag_score ,lesson_preparation_content_score ,courseware_quality_score ,tea_process_design_score ,class_atm_score ,tea_method_score ,knw_point_score,dif_point_score,teacher_blackboard_writing_score,tea_rhythm_score ,content_fam_degree_score ,answer_question_cre_score ,language_performance_score ,tea_attitude_score ,tea_concentration_score ,tea_accident_score ,tea_operation_score ,tea_environment_score ,class_abnormality_score ,record_rank,record_score,r.record_lesson_list  "
                                ." from %s r "
                                ." left join %s t on r.teacherid = t.teacherid"
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_detail_score_info($time,$kk){
        $where_arr=[
            ["add_time > %u",$time,0],
            "(t.nick <> '刘辉' and t.realname <> '刘辉' and t.nick <> 'becky老师' and t.realname <> 'becky老师')",
            "t.teacherid not in (51094,53289,59896,130462,61828,55161,90732)"
        ];
        $kk = substr($kk,0,strlen($kk)-6);
        $sql=$this->gen_sql_new("select count(*) count ,".$kk
                                ." from %s r "
                                ." left join %s t on r.teacherid = t.teacherid"
                                ." where %s group by '%s' order by count desc"
                                ,self::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr
                                ,$kk
        );
        return $this->main_get_list($sql);

    }
    public function get_all_record_info_time($teacherid,$type,$start_time,$end_time,$page_num,$subject){
        $where_arr=[
            ["add_time >= %u",$start_time,-1],
            ["add_time <= %u",$end_time,-1],
            ["r.teacherid=%u",$teacherid,-1],
            ["r.type=%u",$type,0],
            ["t.subject=%u",$subject,-1],
            "(t.nick <> '刘辉' and t.realname <> '刘辉' and t.nick <> 'becky老师' and t.realname <> 'becky老师')",
            "t.teacherid not in (51094,53289,59896,130462,61828,55161,90732)"
        ];

        $sql=$this->gen_sql_new("select  t.nick,t.subject,t.create_time,r.record_monitor_class,r.record_info,r.acc,courseware_flag_score ,lesson_preparation_content_score ,courseware_quality_score ,tea_process_design_score ,class_atm_score ,tea_method_score ,knw_point_score,dif_point_score,teacher_blackboard_writing_score,tea_rhythm_score ,content_fam_degree_score ,answer_question_cre_score ,language_performance_score ,tea_attitude_score ,tea_concentration_score ,tea_accident_score ,tea_operation_score ,tea_environment_score ,class_abnormality_score ,record_rank,record_score,r.record_lesson_list,r.no_tea_related_score  "
                                ." from %s r "
                                ." left join %s t on r.teacherid = t.teacherid"
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);

    }


    public function get_all_info_list(){


        $sql=$this->gen_sql_new("select * "
                                ." from %s "
                                ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }
    public function get_update_info($teacherid,$add_time,$type,$score){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            ["type=%u",$type,0],
            ["add_time = %u",$add_time,0]
        ];

        $sql=$this->gen_sql_new("update %s set  tea_process_design_score=%u"
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$score
                                ,$where_arr
        );
        return $this->main_update($sql);

    }

    public function get_teacher_record_num($start_time){
        $where_arr=[
            "type=1",
            ["add_time >= %u",$start_time,0],
            "t.realname <> '刘辉' and t.realname not like '%%alan%%' and t.realname not like '%%test%%' and  t.realname not like '%%测试%%'"
        ];
        $sql= $this->gen_sql_new("select count(*) from %s r left join %s t on r.teacherid = t.teacherid"
                                 ." where %s",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_record_by_type_and_acc($type,$acc){
        $where_arr=[
            ["type=%u",$type,0],
            ["acc = '%s'",$acc,""]
        ];

        $sql= $this->gen_sql_new("select add_time,acc,teacherid,limit_plan_lesson_type,seller_require_flag,limit_plan_lesson_type_old,limit_week_lesson_num_new,limit_week_lesson_num_old  from %s "
                                 ." where %s",
                                 self::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_limit_type_by_type_and_acc($type,$add_time){
        $where_arr=[
            ["type=%u",$type,0],
            "add_time<".$add_time
        ];
        $sql= $this->gen_sql_new("select limit_plan_lesson_type type  from %s "
                                 ." where %s order by add_time desc limit 1",
                                 self::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_update_info_new($teacherid,$add_time,$type,$seller_require_flag,$limit_plan_lesson_type,$limit_plan_lesson_type_old){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            ["type=%u",$type,0],
            ["add_time = %u",$add_time,0]
        ];

        $sql=$this->gen_sql_new("update %s set  seller_require_flag=%u,limit_plan_lesson_type=%u,limit_plan_lesson_type_old=%u"
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$seller_require_flag
                                ,$limit_plan_lesson_type
                                ,$limit_plan_lesson_type_old
                                ,$where_arr
        );
        return $this->main_update($sql);

    }

    public function get_update_seller_require_flag($teacherid,$add_time,$type,$seller_require_flag){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            ["type=%u",$type,0],
            ["add_time = %u",$add_time,0]
        ];

        $sql=$this->gen_sql_new("update %s set  seller_require_flag=%u"
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$seller_require_flag
                                ,$where_arr
        );
        return $this->main_update($sql);

    }


    public function get_seller_require_modify_info($type,$start_time,$end_time){
        $where_arr=[
            // ["r.type=%u",$type,0],
            ["r.add_time >= %u",$start_time,-1],
            ["r.add_time < %u",$end_time,-1],
            "seller_require_flag=1"
        ];
        if($type==-1){
            $where_arr[]="r.type in (3,7)";
        }else{
            $where_arr[] = ["r.type=%u",$type,0];
        }

        $sql= $this->gen_sql_new("select r.add_time,r.acc,r.teacherid,r.limit_plan_lesson_type,seller_require_flag,limit_plan_lesson_type_old,t.realname,limit_week_lesson_num_new,limit_week_lesson_num_old,r.type"
                                 ."  from %s r left join %s t on r.teacherid=t.teacherid "
                                 ." where %s",
                                 self::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function check_is_repeat($add_time,$next_time,$teacherid){
        $where_arr=[
            "type in (3,7)",
            ["add_time > %u",$add_time,-1],
            ["add_time <= %u",$next_time,-1],
            ["teacherid= %u",$teacherid,-1],
            "seller_require_flag=1"
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function check_have_record($teacherid,$type,$train_lessonid=-1){
        $where_arr = [
            ["teacherid= %u",$teacherid,-1],
            ["type= %u",$type,-1],
            ["train_lessonid= %u",$train_lessonid,-1],
        ];
        $sql = $this->gen_sql_new("select id "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_max_add_time_list($teacherid,$grade_range,$type){
        $where_arr=[
            ["teacherid= %u",$teacherid,-1],
            ["type= %u",$type,-1],
            ["grade_range= %u",$grade_range,-1],
        ];
        $sql = $this->gen_sql_new("select limit_plan_lesson_type from %s where %s and add_time=(select max(add_time) from %s where %s)",self::DB_TABLE_NAME,$where_arr,self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_seller_require_record_info($add_time){
        $where_arr=[
            "a.type in (3,7)",
            ["a.add_time >= %u",$add_time,-1],
            "a.seller_require_flag=1"
        ];
        $sql = $this->gen_sql_new("select a.type,a.teacherid,a.add_time,limit_plan_lesson_type,limit_plan_lesson_type_old,limit_week_lesson_num_old,limit_week_lesson_num_new,a.seller_require_flag "
                                  ." from %s a"
                                  ." where %s and not exists(select 1  from %s b where  b.type in (3,7) and b.teacherid = a.teacherid and b.add_time>a.add_time)",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $add_time
        );
        return $this->main_get_list($sql);

    }

    public function get_teacher_record_num_list_account($start_time,$end_time){
        $where_arr=[
            "type in (1,3,4)",
            "acc <> 'ted'"
        ];
        $this->where_arr_add_time_range($where_arr,"r.add_time",$start_time,$end_time);
        $sql= $this->gen_sql_new("select count(distinct teacherid) num,m.uid"
                                 ." from %s r  left join %s m on r.acc = m.account"
                                 ." where %s and if(type=3,limit_plan_lesson_type_old = 0 or (limit_plan_lesson_type_old > 0 and limit_plan_lesson_type_old>limit_plan_lesson_type),'1=1') and if(type=4,is_freeze =1,'1=1') group by m.uid",
                                 self::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });

    }

    public function get_teacher_record_num_list_subject($start_time,$end_time){
        $where_arr=[
            "type in (1,3,4)",
            "acc <> 'ted'"
        ];
        $this->where_arr_add_time_range($where_arr,"r.add_time",$start_time,$end_time);
        $sql= $this->gen_sql_new("select count(distinct r.teacherid) num,t.subject"
                                 ." from %s r  left join %s m on r.acc = m.account"
                                 ." left join %s t on r.teacherid = t.teacherid"
                                 ." where %s and if(type=3,r.limit_plan_lesson_type_old = 0 or (r.limit_plan_lesson_type_old > 0 and r.limit_plan_lesson_type_old>r.limit_plan_lesson_type),'1=1') and if(type=4,r.is_freeze =1,'1=1') group by t.subject",
                                 self::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 t_teacher_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }

    public function get_jw_revisit_info($teacherid){
        $where_arr=[
            ["teacherid= %u",$teacherid,-1],
            "type=5"
        ];
        $sql = $this->gen_sql_new("select record_info,acc,add_time,class_will_type ,class_will_sub_type ,recover_class_time from %s where %s order by add_time desc limit 1",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_row($sql);
    }

    public function get_trial_train_lesson_list($page_num,$start_time,$end_time,$status,$grade,
                                                $subject,$teacherid,$is_test,$lesson_status
    ){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            ["l.grade=%u",$grade,-1],
            ["l.subject=%u",$subject,-1],
            ["l.teacherid=%u",$teacherid,-1],
            ["t.is_test_user=%u",$is_test,-1],
            ["lesson_status=%u",$lesson_status,-1],
            "tr.type=9",
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=4",
        ];
        $sql = $this->gen_sql_new("select tr.id,l.lessonid,audio,draw,l.teacherid,l.subject,l.grade,t.realname as tea_nick,"
                                  ." t.wx_openid,l.lesson_start,l.lesson_end,l.lesson_status,tr.add_time,tr.record_monitor_class,"
                                  ." tr.record_info,tr.acc,tr.trial_train_status"
                                  ." from %s tr"
                                  ." left join %s l on tr.train_lessonid=l.lessonid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_train_teacher_interview_info_by_day($start_time,$end_time,$trial_train_status=-1){
        $where_arr=[
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            // "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            "(tr.acc is not null && tr.acc <> '')",
            ["tr.trial_train_status=%u",$trial_train_status,-1]
        ];
        $sql = $this->gen_sql_new("select count(*) all_num,count(distinct tt.phone_spare) all_count," .
                                  " FROM_UNIXTIME(l.lesson_start, '%%Y-%%m-%%d') time ".
                                  " from %s tr ".
                                  " left join %s m on m.account = tr.acc ".
                                  " left join %s t on m.phone = t.phone ".
                                  " left join %s ta on tr.train_lessonid  = ta.lessonid ".
                                  " left join %s l on tr.train_lessonid  = l.lessonid ".
                                  " left join %s tt on ta.userid = tt.teacherid ".
                                  " where %s group by time",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["time"];
        });

    }


    public function get_train_teacher_interview_info($subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,$trial_train_status=-1){
        $where_arr=[
            ["l.subject=%u",$subject,-1],
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            // "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            ["t.teacherid = %u",$teacher_account,-1],
            // ["tt.teacherid = %u",$reference_teacherid,-1],
            ["tt.identity = %u",$identity,-1],
            "tr.type=10",
            "(tr.acc is not null && tr.acc <> '')"
        ];
        if(!empty($tea_subject)){
            $where_arr[]="l.subject in".$tea_subject;
        }
        if($trial_train_status==-2){
            $where_arr[]="tr.trial_train_status<>2";
        }else{
            $where_arr[]=["tr.trial_train_status=%u",$trial_train_status,-1];
        }
        $sql = $this->gen_sql_new("select tr.acc account,count(*) all_num,count(distinct tt.phone_spare) all_count,".
                                  " sum(if(tr.trial_train_status =1,1,0)) suc_count,".
                                  " sum(if(tr.trial_train_status <>2,1,0)) real_count,".
                                  " sum(l.lesson_start) all_con_time,sum(l.lesson_start) all_add_time ".
                                  " from %s tr ".
                                  " left join %s m on m.account = tr.acc ".
                                  " left join %s t on m.phone = t.phone ".
                                  " left join %s ta on tr.train_lessonid  = ta.lessonid ".
                                  " left join %s l on tr.train_lessonid  = l.lessonid ".
                                  " left join %s tt on ta.userid = tt.teacherid ".
                                  " where %s group by tr.acc",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item["account"];
        });

    }
    public function get_train_teacher_interview_info_all($subject,$start_time,$end_time,$teacher_account,$reference_teacherid,$identity,$tea_subject,$trial_train_status=-1){
        $where_arr=[
            ["l.subject=%u",$subject,-1],
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            // "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            ["t.teacherid = %u",$teacher_account,-1],
            // ["tt.teacherid = %u",$reference_teacherid,-1],
            ["tt.identity = %u",$identity,-1],
            "tr.type=10",
            "(tr.acc is not null && tr.acc <> '')"
        ];
        if(!empty($tea_subject)){
            $where_arr[]="l.subject in".$tea_subject;
        }
        if($trial_train_status==-2){
            $where_arr[]="tr.trial_train_status<>2";
        }else{
            $where_arr[]=["tr.trial_train_status=%u",$trial_train_status,-1];
        }
        $sql = $this->gen_sql_new("select count(*) all_num,count(distinct tt.teacherid) all_count,".
                                  " sum(if(tr.trial_train_status =1,1,0)) suc_count,".
                                  " sum(if(tr.trial_train_status <>2,1,0)) real_count,".
                                  " sum(l.lesson_start) all_con_time,sum(l.lesson_start) all_add_time ".
                                  " from %s tr ".
                                  " left join %s m on m.account = tr.acc ".
                                  " left join %s t on m.phone = t.phone ".
                                  " left join %s ta on tr.train_lessonid  = ta.lessonid ".
                                  " left join %s l on tr.train_lessonid  = l.lessonid ".
                                  " left join %s tt on ta.userid = tt.teacherid ".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }


    public function get_teacher_train_passed($account,$start_time,$end_time,$subject=-1,$teacher_account=-1,$reference_teacherid=-1,$identity=-1,$tea_subject="",$grade_ex=-1){
        $where_arr=[
            ["l.subject=%u",$subject,-1],
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            // "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            ["tr.acc= '%s'",$account,""],
            ["ttt.teacherid = %u",$teacher_account,-1],
            ["t.identity = %u",$identity,-1],
            "(tr.acc is not null && tr.acc <> '')",
            "tr.trial_train_status =1"
        ];
        if(!empty($tea_subject)){
            $where_arr[]="l.subject in".$tea_subject;
        }

        if($grade_ex>0){
            if($grade_ex==100){
                $where_arr[]="l.grade >=100 and l.grade <200";
            }elseif($grade_ex==200){
                $where_arr[]="l.grade >=200 and l.grade <300";
            }elseif($grade_ex==300){
                $where_arr[]="l.grade >=300";
            }


        }

        $sql = $this->gen_sql_new("select distinct t.teacherid "
                                  ." from %s tr left join %s ta on tr.train_lessonid  = ta.lessonid"
                                  ." left join %s l on tr.train_lessonid  = l.lessonid "
                                  ." left join %s t on ta.userid = t.teacherid "
                                  // ." left join %s tt on t.phone_spare = tt.phone"
                                  ." left join %s m on m.account = tr.acc "
                                  ." left join %s ttt on m.phone = ttt.phone "
                                  ." where %s and t.teacherid>0 ",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  //t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $arr = $this->main_get_list($sql);
        $list=[];
        foreach($arr as $val){
            $list[$val['teacherid']] = $val['teacherid'];
        }
        return $list;

    }

    public function get_all_interview_count($start_time,$end_time,$trial_train_status,$subject_ex=-1){
        $where_arr=[
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            //  "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            ["tr.trial_train_status=%u",$trial_train_status,-1]
        ];

        if($subject_ex==1){
            $where_arr[]="l.subject in (1,3)";
        }elseif($subject_ex==2){
            $where_arr[]="l.subject in (2)";
        }elseif($subject_ex==3){
            $where_arr[]="l.subject in (4,5,6,7,8,9,10)";
        }

        $sql = $this->gen_sql_new("select count(distinct tt.phone_spare) "
                                  ." from %s tr left join %s ta on tr.train_lessonid = ta.lessonid "
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_all_interview_count_by_subject($start_time,$end_time,$trial_train_status){
        $where_arr=[
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            //  "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            ["tr.trial_train_status=%u",$trial_train_status,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct tt.phone_spare) all_count,l.subject,count(*) all_num "
                                  ." from %s tr left join %s ta on tr.train_lessonid = ta.lessonid "
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." where %s and l.subject>0 group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });

    }

    public function get_all_interview_count_by_zs($start_time,$end_time,$trial_train_status){
        $where_arr=[
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            //  "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            // ["tr.trial_train_status=%u",$trial_train_status,-1]
        ];
        if($trial_train_status==-2){
            $where_arr[]="tr.trial_train_status <>2";
        }else{
            $where_arr[]= ["tr.trial_train_status=%u",$trial_train_status,-1];
        }
        $sql = $this->gen_sql_new("select count(distinct tt.phone) all_count,la.accept_adminid,count(*) all_num "
                                  ." from %s tr left join %s ta on tr.train_lessonid = ta.lessonid "
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s la on tt.phone = la.phone"
                                  ." where %s and la.accept_adminid>0 group by la.accept_adminid",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });

    }


    public function get_all_interview_count_by_grade($start_time,$end_time,$trial_train_status){
        $where_arr=[
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            //  "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            ["tr.trial_train_status=%u",$trial_train_status,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct tt.phone_spare) all_count,l.subject,count(*) all_num,l.grade grade_ex "
                                  ." from %s tr left join %s ta on tr.train_lessonid = ta.lessonid "
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." where %s and l.subject>0 group by grade_ex",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["grade_ex"];
        });
    }

    public function get_all_interview_count_by_identity($start_time,$end_time,$trial_train_status){
        $where_arr=[
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            //  "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            ["tr.trial_train_status=%u",$trial_train_status,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct tt.phone_spare) all_count,l.subject,count(*) all_num,"
                                  ." tt.identity identity_ex "
                                  ." from %s tr left join %s ta on tr.train_lessonid = ta.lessonid "
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s tal on tt.phone_spare = tal.phone and not exists ("
                                  ." select 1 from %s taa where taa.phone=tal.phone and tal.answer_begin_time<taa.answer_begin_time)"
                                  ." where %s and l.subject>0 group by identity_ex ",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["identity_ex"];
        });
    }


    public function get_all_interview_count_by_subject_account($start_time,$end_time,$trial_train_status){
        $where_arr = [
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            "tr.type=10",
            ["tr.trial_train_status=%u",$trial_train_status,-1]
        ];
        $sql = $this->gen_sql_new("select count(distinct tt.phone_spare) all_count,tr.acc account ,m.uid,t.teacherid "
                                  ." from %s tr left join %s ta on tr.train_lessonid = ta.lessonid "
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s m on tr.acc=m.account"
                                  ." left join %s t on m.phone = t.phone"
                                  ." where %s group by tr.acc",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["account"];
        });
    }

    public function get_train_lesson_info_new(){
        $sql = $this->gen_sql_new("select tr.id,t.phone_spare "
                                  ." from %s tr left join %s ta on tr.train_lessonid=ta.lessonid"
                                  ." left join %s t on ta.userid= t.teacherid"
                                  ." where tr.type=10",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_interview_access($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            "type=10",
            "trial_train_status=1",
            "record_info!=''"
        ];
        $sql = $this->gen_sql_new("select record_info"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_interview_acc($phone){
        $sql = $this->gen_sql_new("select acc from %s where phone_spare ='%s' and trial_train_status =1 and type=10",
                                  self::DB_TABLE_NAME,
                                  $phone
        );
        return $this->main_get_value($sql);
    }


    public function get_teacher_freeze_num_by_month(){
        $where_arr=[
            "type =4",
            "is_freeze =1"
        ];
        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2017-07-01");
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select FROM_UNIXTIME(add_time,'%%m') month,count(distinct teacherid) num"
                                  ." from %s where %s group by month",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_limit_num_by_month($type){
        $where_arr=[
            "type =3",
            "limit_plan_lesson_type =".$type
        ];
        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2017-07-01");
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select FROM_UNIXTIME(add_time,'%%m') month,count(distinct teacherid) num"
                                  ." from %s "
                                  ." where %s and (limit_plan_lesson_type<limit_plan_lesson_type_old or limit_plan_lesson_type_old=0) group by month",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_record_score($start_time,$end_time,$tea_arr){
        $where_arr=[
            "type =1",
            "record_score>0"
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $this->where_arr_teacherid($where_arr,"teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select count(*) num,sum(record_score) score,teacherid"
                                  ." from %s  where %s group by teacherid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }


    public function get_lesson_list_for_next_day(){

        $start_time = strtotime(date("Y-m-d",strtotime('+1 day')));
        $end_time   = $start_time + 86400;


        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_end<%u",$end_time,0],
            "t.is_test_user=0",
            "lesson_status=0",
            "tr.type=9",
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=4",
        ];

        $sql = $this->gen_sql_new("select  tr.id,l.lessonid,audio,draw,l.teacherid,l.subject,l.grade,t.realname as tea_nick,"
                                  ." t.wx_openid,l.lesson_start,l.lesson_end,l.lesson_status,tr.add_time,tr.record_monitor_class,"
                                  ." tr.record_info,tr.acc,tr.trial_train_status"
                                  ." from %s tr"
                                  ." left join %s l on tr.train_lessonid=l.lessonid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);

    }


}
