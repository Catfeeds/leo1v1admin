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
    public function get_all_record_info_time($teacherid,$type,$start_time,$end_time,$page_num,$subject,$lesson_invalid_flag=0){
        $where_arr=[
            ["add_time>%u",$start_time,-1],
            ["add_time<%u",$end_time,-1],
            ["r.teacherid=%u",$teacherid,-1],
            ["r.type=%u",$type,0],
            ["t.subject=%u",$subject,-1],
            "(t.nick <> '刘辉' and t.realname <> '刘辉' and t.nick <> 'becky老师' and t.realname <> 'becky老师')",
            "t.teacherid not in (51094,53289,59896,130462,61828,55161,90732)",
            // "lesson_invalid_flag>0"
        ];
        if($lesson_invalid_flag==1){
            $where_arr[] = "lesson_invalid_flag>0";
        }
        

        $sql=$this->gen_sql_new("select  t.nick,t.subject,t.create_time,r.record_monitor_class,r.record_info,r.acc,courseware_flag_score ,lesson_preparation_content_score ,courseware_quality_score ,tea_process_design_score ,class_atm_score ,tea_method_score ,knw_point_score,dif_point_score,teacher_blackboard_writing_score,tea_rhythm_score ,content_fam_degree_score ,answer_question_cre_score ,language_performance_score ,tea_attitude_score ,tea_concentration_score ,tea_accident_score ,tea_operation_score ,tea_environment_score ,class_abnormality_score ,record_rank,record_score,r.record_lesson_list,r.no_tea_related_score,lesson_invalid_flag   "
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

    public function get_trial_train_lesson_list(
        $page_num,$start_time,$end_time,$status,$grade,$subject,$teacherid,$is_test,$lesson_status,
        $tea_subject,$opt_date_str=1,$teacher_type=-1
    ){
        $where_arr = [
            //["tr.add_time>%u",$start_time,0],
            // ["tr.add_time<%u",$end_time,0],
            // ["lesson_start>%u",$start_time,0],
            // ["lesson_start<%u",$end_time,0],
            ["l.grade=%u",$grade,-1],
            ["l.subject=%u",$subject,-1],
            ["l.teacherid=%u",$teacherid,-1],
            ["t.is_test_user=%u",$is_test,-1],
            ["lesson_status=%u",$lesson_status,-1],
            ["t.teacher_type=%u",$teacher_type,-1],
            //["tr.trial_train_status=%u",$status,-1],
            "tr.type=1",
            "tr.lesson_style=5",
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=4",
            "l.lesson_del_flag=0",
            "l.confirm_flag <2"
        ];
        // 处理旷课
        if ($status == 999) {
            array_push($where_arr, ["l.absenteeism_flag=%u", 1, -1]);
        } else {
            array_push($where_arr, ["tr.trial_train_status=%u", $status, -1]);
        }
        if($tea_subject==12){
            $where_arr[]="l.subject in (4,6)";
        }elseif($tea_subject==13){
            $where_arr[]="l.subject in (7,8,9)";
        }elseif($tea_subject==-5){
            $where_arr[]="l.subject in (5,10)";
        }elseif($tea_subject==14){
            $where_arr[]="l.subject in (1,3,7,8,9)";
        }elseif($tea_subject==15){
            $where_arr[]="l.subject in (7,8,9,10)";
        }else{
            $where_arr[]=["l.subject=%u",$tea_subject,-1];
        }
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);

        $sql = $this->gen_sql_new("select tr.id,l.lessonid,audio,draw,l.teacherid,l.subject,l.grade,t.realname as tea_nick,"
                                  ." t.wx_openid,l.lesson_start,l.lesson_end,l.lesson_status,tr.add_time,tr.record_monitor_class,"
                                  ." tr.record_info,tr.acc,tr.trial_train_status,l.trial_train_num,l.stu_comment "
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
            "l.lesson_del_flag = 0",
            "l.lesson_type = 1100",
            "l.train_type=5",
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
                                  " left join %s la on tt.phone = la.phone".
                                  " where %s and la.accept_adminid>0",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_teacher_througn_detail($start_time,$end_time){
        $where_arr=[
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            "tr.type=10",
            "l.lesson_type=1100",
            "(tr.acc is not null && tr.acc <> '')",
            "tr.trial_train_status =1",
            "t.is_test_user=0",
            "t.train_through_new=1",
            "t.train_through_new_time>l.lesson_start"
        ];

        $sql = $this->gen_sql_new("select t.teacherid,l.lesson_start,t.train_through_new_time, "
                                  ."t.train_through_new_time-l.lesson_start time "
                                  ." from %s tr left join %s l on tr.train_lessonid  = l.lessonid"
                                  ." left join %s t on l.userid = t.teacherid"
                                  ." where %s and not exists ("
                                  ." select 1 from %s where teacherid=tr.teacherid and type=10 "
                                  ."and tr.trial_train_status =1 and add_time<tr.add_time"
                                  ." )",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );      
        return  $this->main_get_list($sql);

    }


    public function get_teacher_train_passed($account,$start_time,$end_time,$subject=-1,$teacher_account=-1,$reference_teacherid=-1,$identity=-1,$tea_subject="",$grade_ex=-1,$train_through_new=-1){
        $where_arr=[
            ["l.subject=%u",$subject,-1],
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            // "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            ["tr.acc= '%s'",$account,""],
            ["ttt.teacherid = %u",$teacher_account,-1],
            ["taa.teacher_type = %u",$identity,-1],
            ["t.train_through_new = %u",$train_through_new,-1],
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
                                  ." left join %s taa on t.phone = taa.phone "
                                  ." where %s and t.teacherid>0 ",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  //t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $arr = $this->main_get_list($sql);
        $list=[];
        foreach($arr as $val){
            $list[$val['teacherid']] = $val['teacherid'];
        }
        return $list;

    }
    
    public function get_teacher_passes_num_by_subject_grade($start_time,$end_time,$subject){
        $where_arr=[
            ["l.subject=%u",$subject,-1],
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            // "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",         
            "(tr.acc is not null && tr.acc <> '')",
            "tr.trial_train_status =1"
        ];

        $sql = $this->gen_sql_new("select taa.accept_adminid,sum(if(substring(l.grade,1,1)=1,1,0)) primary_num, "
                                  ." sum(if(substring(l.grade,1,1)=2,1,0)) middle_num,"
                                  ."sum(if(substring(l.grade,1,1)=3,1,0)) senior_num "
                                  ." from %s tr left join %s ta on tr.train_lessonid  = ta.lessonid"
                                  ." left join %s l on tr.train_lessonid  = l.lessonid "
                                  ." left join %s t on ta.userid = t.teacherid "
                                  ." left join %s taa on t.phone = taa.phone "
                                  ." where %s and t.teacherid>0 group by taa.accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });

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
            "l.lesson_del_flag = 0",
            "l.lesson_type = 1100",
            "l.train_type=5"
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

 

    public function get_all_interview_count_by_reference($start_time,$end_time,$trial_train_status){
        $where_arr=[
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            //  "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            "l.lesson_del_flag = 0",
            "l.lesson_type = 1100",
            "l.train_type=5"
            // ["tr.trial_train_status=%u",$trial_train_status,-1]
        ];
        if($trial_train_status==-2){
            $where_arr[]="tr.trial_train_status <>2";
        }else{
            $where_arr[]= ["tr.trial_train_status=%u",$trial_train_status,-1];
        }
        $sql = $this->gen_sql_new("select count(distinct tt.phone) lesson_add_num,la.reference,t.teacher_ref_type,c.channel_id,c.channel_name,t.realname,t.phone "
                                  ." from %s tr left join %s ta on tr.train_lessonid = ta.lessonid "
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s la on tt.phone = la.phone"
                                  ." left join %s t on la.reference = t.phone"
                                  ." left join %s cg on t.teacher_ref_type = cg.ref_type"
                                  ." left join %s c on cg.channel_id = c.channel_id"
                                  ." where %s and la.accept_adminid>0 group by  la.reference ",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_admin_channel_group::DB_TABLE_NAME,
                                  t_admin_channel_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["reference"];
        });
    }



    public function get_all_interview_count_by_grade($start_time,$end_time,$trial_train_status){
        $where_arr=[
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            //  "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            "tt.teacherid <> 224514",
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
                                  ." tal.teacher_type identity_ex "
                                  ." from %s tr left join %s ta on tr.train_lessonid = ta.lessonid "
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s tal on tt.phone = tal.phone "
                                  ." where %s and l.subject>0 group by identity_ex ",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
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
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s m on tr.acc=m.account"
                                  ." left join %s t on m.phone = t.phone"
                                  ." where %s group by tr.acc",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
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

    public function get_teacher_first_interview_score_info($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            "type=10",
            "trial_train_status=1",
        ];
        $sql = $this->gen_sql_new("select record_score,add_time,teacher_lecture_score"
                                  ." from %s "
                                  ." where %s order by add_time"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
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

    public function get_test_lesson_record_score($start_time,$end_time,$tea_arr,$tongji_flag=-1){
        $where_arr=[
            "type =1",
            "record_score>0"
        ];
        //  $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
        $this->where_arr_teacherid($where_arr,"teacherid", $tea_arr);
        if($tongji_flag==1){
            $where_arr[]="lesson_style in (1,2,3,4)";
            //  $where_arr[]="lesson_style in (3,4)";
        }
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

    public function get_fulltime_teacher_interview_info($start_time,$end_time,$status){
        $where_arr=[
            "tr.type=10",
            "ta.full_time=1"
        ];
        if($status==-2){
            $where_arr[] = "tr.trial_train_status <2";
        }else{
            $where_arr[] = ["tr.trial_train_status=%u",$status,-1];
        }
        $this->where_arr_add_time_range($where_arr,"tr.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(*) from %s tr"
                                  ." join %s l on tr.train_lessonid = l.lessonid"
                                  ." join %s t on l.userid = t.teacherid"
                                  ." join %s ta on t.phone = ta.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function check_lesson_record_exist($lessonid,$type,$lesson_style){
        $where_arr=[
            ["lesson_style=%u",$lesson_style,-1]  
        ];
        $sql = $this->gen_sql_new("select id from %s "
                                  ."where train_lessonid=%u and type= %u and %s",
                                  self::DB_TABLE_NAME,
                                  $lessonid,
                                  $type,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function check_lesson_record_exist_teacherid($teacherid,$type,$lesson_style){
        $sql = $this->gen_sql_new("select id from %s "
                                  ."where teacherid=%u and type= %u and lesson_style=%u",
                                  self::DB_TABLE_NAME,
                                  $lessonid,
                                  $type,
                                  $lesson_style
        );
        return $this->main_get_value($sql);
    }


    public function get_trial_train_lesson_first($start_time,$end_time,$trial_train_num=1,$subject){
        $where_arr=[
            "tr.type=1",
            "tr.lesson_style=5",
            ["l.trial_train_num=%u",$trial_train_num,-1],
            ["l.subject=%u",$subject,-1],
            "tr.trial_train_status>0",
            "tr.trial_train_status<4",
            "t.is_test_user=0",
            "tr.acc <> 'system' "
        ];
        $this->where_arr_add_time_range($where_arr,"tr.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select acc,count(*) all_num,sum(if(trial_train_status=1,1,0)) pass_num"
                                  ." from %s tr left join %s l on tr.train_lessonid=l.lessonid"
                                  ." join %s t on tr.teacherid = t.teacherid "
                                  ." where %s group by tr.acc ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["acc"];
        });
    }

    public function get_trial_train_lesson_all($start_time,$end_time,$trial_train_num=1,$subject){
        $where_arr=[
            "tr.type=1",
            "tr.lesson_style=5",
            ["l.trial_train_num=%u",$trial_train_num,-1],
            ["l.subject=%u",$subject,-1],
            "tr.trial_train_status>0",
            "tr.trial_train_status<4",
            "t.is_test_user=0",
            "tr.acc <> 'system' "
        ];
        $this->where_arr_add_time_range($where_arr,"tr.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(*) all_num,sum(if(trial_train_status=1,1,0)) pass_num"
                                  ." from %s tr left join %s l on tr.train_lessonid=l.lessonid"
                                  ." join %s t on tr.teacherid = t.teacherid "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }



    public function get_test_regular_lesson_first($start_time,$end_time,$lesson_style,$subject){
        $where_arr=[
            "tr.type=1",
            ["tr.lesson_style=%u",$lesson_style,-1],
            ["l.subject=%u",$subject,-1],
            //  "tr.lesson_invalid_flag >0"
            "tr.record_info <> ''"
        ];
        $this->where_arr_add_time_range($where_arr,"tr.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select tr.acc,count(*) all_num "
                                  ." from %s tr left join %s l on tr.train_lessonid = l.lessonid"
                                  ." where %s group by tr.acc ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["acc"];
        });
    }


    public function get_test_regular_lesson_first_per($start_time,$end_time,$lesson_style,$subject){
        $where_arr=[
            "tr.type=1",
            ["tr.lesson_style=%u",$lesson_style,-1],
            ["l.subject=%u",$subject,-1],
            "tr.record_info <> ''",
            "tr.click_time>0",
            "tr.add_time>tr.click_time",
            "tr.add_time-tr.click_time<3600"
        ];
        $this->where_arr_add_time_range($where_arr,"tr.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select tr.acc,count(*) all_num, sum(tr.add_time-tr.click_time) all_time "
                                  ." from %s tr left join %s l on tr.train_lessonid = l.lessonid"
                                  ." where %s group by tr.acc ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["acc"];
        });
    }

    public function get_test_regular_lesson_all($start_time,$end_time,$lesson_style,$subject){
        $where_arr=[
            "tr.type=1",
            ["tr.lesson_style=%u",$lesson_style,-1],
            ["l.subject=%u",$subject,-1],
            "tr.record_info <> ''"
        ];
        $this->where_arr_add_time_range($where_arr,"tr.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(*) all_num "
                                  ." from %s tr left join %s l on tr.train_lessonid = l.lessonid"
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_first_record($start_time){
        $sql = $this->gen_sql_new("select tr.id,tr.teacherid,record_lesson_list  from %s tr where tr.type=1 and tr.lesson_style=0 and tr.add_time = (select min(add_time) from %s where type= 1 and lesson_style=0 and teacherid = tr.teacherid ) and tr.add_time >=%u",
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $start_time
        );
        return $this->main_get_list($sql);
    }

    public function get_last_interview_by_phone($teacherid){
        $sql = $this->gen_sql_new("select tr.record_info from %s tr where tr.type=10 and tr.teacherid = %u and tr.add_time = (select max(add_time) from %s where teacherid = tr.teacherid and type=10)",
                                  self::DB_TABLE_NAME,
                                  $teacherid,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function get_passed_interview_by_phone($teacherid,$subject,$grade){
        $sql = $this->gen_sql_new("select tr.record_info "
                                  ."from %s tr join %s l on tr.train_lessonid = l.lessonid"
                                  ." where tr.type=10 and l.subject=%u and l.grade = %u and tr.trial_train_status=1",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $teacherid,
                                  $subject,
                                  $grade
        );
        return $this->main_get_value($sql);
    }


    public function get_no_second_train_lesson(){
        $sql = $this->gen_sql_new("select l.teacherid,t.realname,count(distinct l.lessonid) num,tr.trial_train_status"
                                  ." from %s l left join %s tr on l.lessonid = tr.train_lessonid and tr.type=1 and tr.lesson_style=5 and tr.trial_train_status=2"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where l.lesson_type=1100 and l.train_type=4  group by l.teacherid having(num=1) ",
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME
                                  
        );
        return $this->main_get_list($sql);
    }


    public function check_is_exist_lesson($teacherid,$lesson_style,$userid=-1){
        $where_arr=[
            "type=1",
            ["lesson_style=%u",$lesson_style,-1],
            ["teacherid=%u",$teacherid,-1],
            ["userid=%u",$userid,-1],
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_two_list_record(){
        $sql = $this->gen_sql_new("select train_lessonid,lesson_style,count(*)  from %s where type=1 and lesson_style in (3,4) group by train_lessonid,lesson_style having(count(*)>1)",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_id_list_by_lessonid($lessonid){
        $sql = $this->gen_sql_new("select id,userid  from %s where train_lessonid=%u and lesson_style in (3,4) order by id ",self::DB_TABLE_NAME,$lessonid);
        return $this->main_get_list($sql);

    }

    public function get_user_null_list(){
        $sql = $this->gen_sql_new("select id,train_lessonid  from %s where  type=1 and lesson_style in (3,4) and userid=0",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);

    }

    public function tongji_trial_train_lesson_list($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "t.is_test_user=0",
            "lesson_status=2",
            "tr.trial_train_status=0",
            "tr.type=1",
            "tr.lesson_style=5",
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=4",
            "l.lesson_del_flag=0",
            "l.confirm_flag <2"
        ];
        $sql = $this->gen_sql_new("select l.subject, count(tr.id) as sum"
                                  ." from %s tr"
                                  ." left join %s l on tr.train_lessonid=l.lessonid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s group by l.subject"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_record_score_list($lesson_style,$subject){
        $where_arr=[
            ["lesson_style=%u",$lesson_style,-1],
            ["l.subject=%u",$subject,-1],
            "tr.record_score>0",
            "type=1"
        ];
        $sql = $this->gen_sql_new("select count(*) num,record_score "
                                  ."from %s tr left join %s l on tr.train_lessonid = l.lessonid "
                                  ." where %s group by record_score",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function tongji_record_score_rank_list($lesson_style){
        $where_arr=[
            ["lesson_style=%u",$lesson_style,-1],
            "tr.lesson_invalid_flag=1",
            "type=1"
        ];
        $sql = $this->gen_sql_new("select l.subject,sum(if(tr.record_score<40,1,0)) first_score, "
                                  ." sum(if(tr.record_score>=40 and tr.record_score<50,1,0)) second_score,"
                                  ." sum(if(tr.record_score>=50 and tr.record_score<60,1,0)) third_score,"
                                  ." sum(if(tr.record_score>=60 and tr.record_score<65,1,0)) fourth_score,"
                                  ." sum(if(tr.record_score>=65 and tr.record_score<70,1,0)) fifth_score,"
                                  ." sum(if(tr.record_score>=70 and tr.record_score<75,1,0)) sixth_score,"
                                  ." sum(if(tr.record_score>=75 and tr.record_score<80,1,0)) seventh_score,"
                                  ." sum(if(tr.record_score>=80 and tr.record_score<85,1,0)) eighth_score,"
                                  ." sum(if(tr.record_score>=85 and tr.record_score<90,1,0)) ninth_score,"
                                  ." sum(if(tr.record_score>=90 and tr.record_score<95,1,0)) tenth_score,"
                                  ." sum(if(tr.record_score>=95 and tr.record_score<=100,1,0)) eleventh_score, "
                                  ." count(*) all_num"
                                  ." from %s tr left join %s l on tr.train_lessonid = l.lessonid "
                                  ." where %s group by l.subject",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_record_flag_info($lesson_invalid_flag=-1){
        $where_arr=[
            // ["lesson_style=%u",$lesson_style,-1],
            // ["lesson_invalid_flag=%u",$lesson_invalid_flag,-1],
            "type=1",
            "lesson_style in (1,2,3,4)"
        ];
        if($lesson_invalid_flag==1){
            $where_arr[] ="lesson_invalid_flag>0";
        }
        $sql = $this->gen_sql_new("select count(distinct teacherid) teacher_num,"
                                  ." count(distinct userid) stu_num"
                                  ." from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_record_score_avg($lesson_style_flag){
        $where_arr=[
            "lesson_invalid_flag=1",
            "type=1"
        ];
        if($lesson_style_flag==1){
             $where_arr[] ="lesson_style in (1,2)";
        }else{
            $where_arr[] ="lesson_style in (3,4)";
        }
        $sql = $this->gen_sql_new("select AVG(record_score)"
                                  ." from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

 
    }

    public function get_teacher_first_record_score($teacherid){
        $where_arr=[
            ["teacherid = %u",$teacherid,-1],
            "type=1",
            "lesson_style=1"
        ];
        $sql= $this->gen_sql_new("select record_score from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function add_teacher_action_log($teacherid,$record_info,$acc){
        $ret = $this->row_insert([
            "teacherid"   => $teacherid,
            "type"        => E\Erecord_type::V_6,
            "record_info" => $record_info,
            "add_time"    => time(),
            "acc"         => $acc,
        ]);
        return $ret;
    }

    public function get_data_to_teacher_flow($start_time,$end_time,$type)
    {
        $where_arr = [
            ["tr.add_time>%u",$start_time, 0],
            ["tr.add_time<%u",$end_time, 0],
            "tr.type=10",
            //["l.train_type=%u",$type,0],
            //["l.teacherid=%u",$teacherid,0],
            //'l.lesson_type=1100',
            //"l.lesson_del_flag=0",
            "tr.trial_train_status=1",
            //"tr.train_lessonid=l.lessonid",
        ];
        //select add_time from t_teacher_record_list where type=10 and trial_train_status=1
        //t_lesson_info userid是老师id lesson_type=1100 tran_type=5 lesson_del_flag=0
        //t_teacher_record_list     用train_lessonid  匹配   试讲通过 trial_train_status =1 通过时间  add_time
        $sql = $this->gen_sql_new("select tr.teacherid,tr.add_time,l.subject,l.grade from %s tr left join %s l on tr.train_lessonid=l.lessonid where %s ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function( $item) {
            return $item['teacherid'];
        });
    }

    public function get_data_to_teacher_flow_id($type,$teacherid)
    {
        $where_arr = [
            ["l.train_type=%u",$type,0],
            ["l.teacherid=%u",$teacherid,0],
            'l.lesson_type=1100',
            "l.lesson_del_flag=0",
            "tr.trial_train_status=1",
            "tr.train_lessonid=l.lessonid",
        ];
        //t_lesson_info userid是老师id lesson_type=1100 tran_type=5 lesson_del_flag=0
        //t_teacher_record_list     用train_lessonid  匹配   试讲通过 trial_train_status =1 通过时间  add_time
        //select tr.add_time from t_lesson_info l left join t_teacher_record_list tr on l.teacherid=tr.teacherid where l.train_type=4 and l.lesson_type=1100 and llesson_def_flag=0 and l.teacherid= and tr.trial_train_status=1 and tr.train_lessonid=l.lessonid
        $sql = $this->gen_sql_new("select l.teacherid teacherid,l.subject,l.grade,tr.add_time "
                                  ." from %s l "
                                  ." left join %s tr on l.teacherid=tr.teacherid"
                                  ." where %s ",
                                  t_lesson_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_interview_through_by_subject($start_time, $end_time, $subject){
        $where_arr = [
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start <= %u",$end_time,-1],
            ["l.subject=%u",$subject,0],
            //  "(tr.acc <> 'adrian' && tr.acc <> 'alan' && tr.acc <> 'jack')",
            "tr.type=10",
            "tr.trial_train_status=1"
        ];
        if ($subject <= 3) {
            $query = " sum(if(substring(l.grade,1,1)=1,1,0)) primary_num, "
                      ." sum(if(substring(l.grade,1,1)=2,1,0)) middle_num,"
                      ."sum(if(substring(l.grade,1,1)=3,1,0)) senior_num";
        } else {
            $query = " count(*) sum";
        }
        $sql = $this->gen_sql_new("select %s "
                                  ." from %s tr left join %s ta on tr.train_lessonid = ta.lessonid "
                                  ." left join %s tt on ta.userid = tt.teacherid "
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." where %s",
                                  $query,
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_teacher_revisit_lesson_info($start_time,$end_time){
        $where_arr=[
            "tr.type=5" ,
            ["tr.add_time >= %u",$start_time,-1],
            ["tr.add_time <= %u",$end_time,-1],
        ];
        $sql = $this->gen_sql_new("select count(distinct tr.teacherid) revisit_num,count(distinct ll.teacherid) lesson_num,tr.acc "
                                  ." from %s tr left join %s l on tr.teacherid = l.teacherid and l.lesson_del_flag=0 and l.lesson_type=2 and l.lesson_start>%u"
                                  ." left join %s tss on l.lessonid = tss.lessonid and tss.success_flag<2 and (tss.set_lesson_time-tr.add_time)>0 and (tss.set_lesson_time-tr.add_time)<=7*86400"
                                  ." left join %s ll on tss.lessonid = ll.lessonid"
                                  ." where %s group by tr.acc",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_acc_for_teacherid($teacherid, $return = 'row') {
        $where_arr = [
            ['teacherid=%d', $teacherid, 0],
            'type=16' // record_type 枚举类
        ];
        
        $sql = $this->gen_sql_new("select record_info,add_time,acc from %s where %s order by add_time desc ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        if ($return == 'all') {
            return $this->main_get_list($sql);
        }
        return $this->main_get_row($sql);
    }

    //获取老师最近一次通过的模拟试听记录id
    public function get_late_trial_train_record_id($teacherid,$type,$lesson_style,$trial_train_status){
        $where_arr=[
            ["tr.teacherid=%u",$teacherid,-1],  
            ["tr.type=%u",$type,-1],  
            ["tr.lesson_style=%u",$lesson_style,-1],  
            ["tr.trial_train_status=%u",$trial_train_status,-1],  
        ];
        $sql = $this->gen_sql_new("select tr.id,tr.train_lessonid,tr.record_info,tr.acc "
                                  ." from %s tr not exists (select 1 from %s where teacherid=tr.teacherid and type=tr.type and lesson_style=tr.lesson_style and trial_train_status = tr.trial_train_status and add_time>tr.add_time)"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }
}
