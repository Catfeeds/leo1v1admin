<?php

namespace App\Models;
use App\Models\Zgen as Z;
use \App\Models as M;
use \App\Enums as E;

class t_lesson_info_b3 extends \App\Models\Zgen\z_t_lesson_info{
    public function lesson_record_server_list($page_num,$start_time, $end_time ,$record_audio_server1 ,$xmpp_server_name  ,$lesson_type, $subject) {
        $where_arr=[
            ["record_audio_server1='%s'", $record_audio_server1, "" ],
            ["xmpp_server_name='%s'", $xmpp_server_name, "" ],
        ];
        $this->where_arr_add_int_or_idlist($where_arr, "lesson_type", $lesson_type);
        $this->where_arr_add_int_or_idlist($where_arr, "subject", $subject);

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select lessonid, record_audio_server1, xmpp_server_name, lesson_start, lesson_end, userid,teacherid"
            ." from %s   where  lesson_del_flag=0 and  %s " ,
            self::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_open_lesson_list(){
        $start = strtotime("2017-9-1");
        $end   = strtotime("2017-10-1");
        $where_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            "teacherid in (55161,176999)",
            "lesson_type=1001",
        ];
        $sql = $this->gen_sql_new("select lessonid,grade"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_grade_first_test_lesson($userid, $grade ) {
        $sql = $this->gen_sql_new(
            "select lesson_start from %s  l"
            . " where userid= %u and  grade=%u and lesson_start>0 "
            . " and confirm_flag<>2  and lesson_del_flag =0  "
            . " order by lesson_start asc limit 1 ",
            self::DB_TABLE_NAME,
            $userid, $grade
        ) ;

        return $this->main_get_row($sql);
    }

    public function get_grade_last_test_lesson($userid, $grade ) {
        $sql = $this->gen_sql_new(
            "select lesson_start from %s"
            . " where userid= %u and  grade=%u and lesson_start>0 "
            . " and confirm_flag<>2  and lesson_del_flag =0  "
            . " order by lesson_start desc limit 1  ",
            self::DB_TABLE_NAME,
            $userid, $grade
        ) ;

        return $this->main_get_row($sql);
    }


    public function get_next_day_lesson_info(){
        $next_day_begin = strtotime(date('Y-m-d',strtotime("+1 days")));
        $next_day_end   = strtotime(date('Y-m-d',strtotime("+2 days")));

        $where_arr = [
            ["l.lesson_start>=%d",$next_day_begin],
            ["l.lesson_start<=%d",$next_day_end],
            "l.lesson_del_flag=0",
            "s.is_test_user=0",
            "l.lesson_type =2"
        ];

        $sql = $this->gen_sql_new("  select l.lesson_start, m.phone, t.nick as tea_nick, l.lessonid, s.userid as stu_id, s.nick as stu_nick, l.lesson_end, l.subject, t.wx_openid as tea_openid, p.wx_openid as par_openid  from %s l "
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." left join %s p on p.parentid=s.parentid "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tr on tr.require_id = tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s m on m.uid=ts.require_adminid where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_have_order_lesson_list_new($start_time,$end_time){
        $where_arr = [
            ["l.lesson_start>=%d",$start_time],
            ["l.lesson_start<=%d",$end_time],
            "l.del_flag=0",
            "s.is_test_user=0",
            "l.lesson_type =2",
            "m.account_role=2"
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.subject"
                                  ." from %s l join %s s on l.userid = s.userid"
                                  ." join %s tss on l.lessonid = tss.lessonid"
                                  ." join %s tq on tss.require_id = tq.require_id"
                                  ." join %s m on tq.cur_require_adminid = m.uid"
                                  ." join %s o on l.lessonid = o.from_test_lesson_id and contract_type in (0,3) and contract_status>0"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_trial_teacher_list($start,$end){
        $where_arr = [
            ["l.lesson_start>%u",$start,0],
            ["l.lesson_start<%u",$end,0],
            "l.lesson_type=2",
            "t.is_test_user=0"
        ];
        \App\Helper\Utils::effective_lesson_sql($where_arr,"l");
        $lesson_arr = [
            "l.userid=l2.userid",
            "l.teacherid=l2.teacherid",
            "l2.lesson_type in (0,1,3)",
        ];
        \App\Helper\Utils::effective_lesson_sql($lesson_arr,"l2");
        $sql = $this->gen_sql_new("select t.teacherid,t.realname,t.subject,t.grade_part_ex,t.grade_start,t.grade_end,"
                                  ." t.second_subject,t.second_grade,t.second_grade_start,t.second_grade_end,t.phone,"
                                  ." count(distinct(l.lessonid)) as lesson_num,count(distinct(l2.userid)) as succ_num"
                                  ." from %s t"
                                  ." left join %s l on t.teacherid=l.teacherid"
                                  ." left join %s l2 on %s"
                                  ." where %s"
                                  ." group by t.teacherid"
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$lesson_arr
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_first_lesson_time($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            "lesson_start!=0",
            "lesson_type<1000",
        ];
        \App\Helper\Utils::effective_lesson_sql($where_arr);
        $sql = $this->gen_sql_new("select min(lesson_start)"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_tea_lesson_count_list($start_time,$end_time,$teacher_money_type) {
        $where_arr = [
            ["lesson_start>=%s",$start_time,0],
            ["lesson_start<%s",$end_time,0],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            "lesson_status=2",
            "lesson_type in (0,1,3)",
            "t.is_test_user=0",
        ];
        \App\Helper\Utils::effective_lesson_sql($where_arr);
        $sql=$this->gen_sql_new("select l.teacherid,sum(lesson_count) as lesson_count,count(l.lessonid) as count,"
                                ." count(distinct(l.userid)) as stu_num,"
                                ." group_concat(distinct(l.grade)) as grade,"
                                ." group_concat(distinct(l.subject)) as subject,"
                                ." t.teacher_money_type,t.level,t.realname"
                                ." from %s l force index(lesson_type_and_start) "
                                ." left join %s t on l.teacherid=t.teacherid"
                                ." where %s"
                                ." group by l.teacherid "
                                ,self::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr
        );
        dd($sql);
        return $this->main_get_list_as_page($sql);
    }

    public function get_teacher_identity($userid){
        $start_time = time()-50*86400;
        $end_time = time();
        $where_arr = [
            ["lesson_start>=%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            ["l.userid = %u",$userid,-1],
            "lesson_status=2",
            "lesson_type in (0,1,3)",
            "l.confirm_flag <2",
            "t.is_test_user=0",
        ];
        $sql = $this->gen_sql_new("select distinct t.identity "
                                  ." from %s l left join %s t on l.teacherid= t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_seller_top_test_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$userid,$tea_subject=-1){
        $where_arr=[
            "l.lesson_del_flag=0",
            // "l.lesson_user_online_status <2",
            "l.lesson_type  =2",
            "l.lesson_status>0",
            // "t.is_test_user=0",
            "tss.top_seller_flag=1",
            ["l.subject = %u",$subject,-1],
            ["l.teacherid = %u",$teacherid,-1],
            ["l.userid = %u",$userid,-1],
        ];
        if($record_flag==0){
            $where_arr[] = "(tr.record_info is null or tr.record_info='')";
        }elseif($record_flag==1){
            $where_arr[] = "tr.record_info <>''";
        }
        if($tea_subject==12){
            $where_arr[]="l.subject in (4,6)";
        }elseif($tea_subject==13){
            $where_arr[]="l.subject in (7,8,9)";
        }else{
            $where_arr[]=["l.subject=%u",$tea_subject,-1];
        }


        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,t.realname,l.lessonid,l.lesson_start,l.subject,t.grade_start,t.grade_end,t.grade_part_ex,tr.id,s.nick,tr.acc,tr.record_info,tr.add_time,l.grade,tr.lesson_invalid_flag,l.userid,tt.stu_request_test_lesson_demand,tq.test_stu_request_test_lesson_demand "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s tr on tr.train_lessonid = l.lessonid and tr.type=1 and tr.lesson_style=6"
                                  ." left join %s s on l.userid=s.userid"
                                  ." left join %s tq on tss.require_id = tq.require_id"
                                  ." left join %s tt on tq.test_lesson_subject_id=tt.test_lesson_subject_id"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }

    public function get_seller_test_lesson_tran_info( $start_time,$end_time,$require_type,$set_type,$grab_flag=-1,$full_time_flag=-1){
        $where_arr = [
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "mm.account_role=2 ",
            // "mm.del_flag=0",
        ];
        if($set_type==1){
            $where_arr[]= ["lesson_start >= %u",$start_time,-1];
            $where_arr[]= ["lesson_start < %u",$end_time,-1];
        }elseif($set_type==2){
            $where_arr[]= ["tss.set_lesson_time >= %u",$start_time,-1];
            $where_arr[]= ["tss.set_lesson_time < %u",$end_time,-1];
        }
        if($require_type==1){
            $where_arr[] = "tq.seller_top_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
            $where_arr[] = ["tss.grab_flag=%u",$grab_flag,-1];
        }elseif($require_type==4){
            $where_arr[] = "tq.seller_top_flag=1";
            $where_arr[] = "tq.is_green_flag =0";

        }elseif($require_type==5){
            $where_arr[] = "tq.seller_top_flag=1";
            $where_arr[] = "tq.is_green_flag =1";

        }

        if($full_time_flag==1){
            $where_arr[]="m.account_role=5";
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
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." left join %s m on t.phone = m.phone"
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

    public function get_seller_test_lesson_tran_seller( $start_time,$end_time,$require_type,$set_type){
        $where_arr = [
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "mm.account_role=2 ",
            //  "mm.del_flag=0",
        ];
        if($set_type==1){
            $where_arr[]= ["lesson_start >= %u",$start_time,-1];
            $where_arr[]= ["lesson_start < %u",$end_time,-1];
        }elseif($set_type==2){
            $where_arr[]= ["tss.set_lesson_time >= %u",$start_time,-1];
            $where_arr[]= ["tss.set_lesson_time < %u",$end_time,-1];
        }
        if($require_type==1){
            $where_arr[] = "tq.seller_top_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
        }

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order,tq.cur_require_adminid"
                                  ." ,mm.account "
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
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s group by tq.cur_require_adminid " ,
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
            return $item["cur_require_adminid"];
        });

    }

    public function get_seller_test_lesson_tran_tea( $start_time,$end_time,$require_type,$set_type){
        $where_arr = [
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "mm.account_role=2 ",
            // "mm.del_flag=0",
        ];
        if($set_type==1){
            $where_arr[]= ["lesson_start >= %u",$start_time,-1];
            $where_arr[]= ["lesson_start < %u",$end_time,-1];
        }elseif($set_type==2){
            $where_arr[]= ["tss.set_lesson_time >= %u",$start_time,-1];
            $where_arr[]= ["tss.set_lesson_time < %u",$end_time,-1];
        }
        if($require_type==1){
            $where_arr[] = "tq.seller_top_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
        }


        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order,l.teacherid,t.realname"
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
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s group by l.teacherid " ,
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
            return $item["teacherid"];
        });

    }



    //
    public function get_seller_test_lesson_tran_tea_count( $page_info,$start_time,$end_time,$require_type=-1,$set_type=1,$subject,$grade_part_ex,$teacherid,$tranfer_per,$test_lesson_flag,$test_lesson_num){
        $where_arr = [
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            ["mm.account_role=%u",$test_lesson_flag,-1],
            ["t.subject=%u",$subject,-1],
            ["t.grade_part_ex=%u",$grade_part_ex,-1],
            ["t.teacherid=%u",$teacherid,-1]
            // "mm.del_flag=0",
        ];
        /*if($test_lesson_flag==1){
            $where_arr[]="mm.account_role=2";
            }*/
        if($set_type==1){
            $where_arr[]= ["lesson_start >= %u",$start_time,-1];
            $where_arr[]= ["lesson_start < %u",$end_time,-1];
        }elseif($set_type==2){
            $where_arr[]= ["tss.set_lesson_time >= %u",$start_time,-1];
            $where_arr[]= ["tss.set_lesson_time < %u",$end_time,-1];
        }

        if($require_type==1){
            $where_arr[] = "tq.seller_top_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
        }

        $having = '';
        if($test_lesson_num==-1){
            if($tranfer_per == 1){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)=0";
            }else if($tranfer_per == 2){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>0 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=10";
            }else if($tranfer_per == 3){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>10 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=15";

            }else if($tranfer_per == 4){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>15 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=20";

            }else if($tranfer_per == 5){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>20";
            }
        }elseif($test_lesson_num==1){
            if($tranfer_per == 1){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)=0 and count(l.lessonid) >=5";
            }else if($tranfer_per == 2){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>0 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=10 and count(l.lessonid) >=5";
            }else if($tranfer_per == 3){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>10 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=15 and count(l.lessonid) >=5";

            }else if($tranfer_per == 4){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>15 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=20 and count(l.lessonid) >=5";

            }else if($tranfer_per == 5){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>20 and count(l.lessonid) >=5";
            }

        }elseif($test_lesson_num==2){
            if($tranfer_per == 1){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)=0 and count(l.lessonid) <5";
            }else if($tranfer_per == 2){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>0 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=10 and count(l.lessonid) <5";
            }else if($tranfer_per == 3){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>10 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=15 and count(l.lessonid) <5";

            }else if($tranfer_per == 4){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>15 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=20 and count(l.lessonid) <5";

            }else if($tranfer_per == 5){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2)>20 and count(l.lessonid) <5";
            }

        }
        if($having != ''){
            $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order,l.teacherid,t.realname,t.subject,t.train_through_new_time,t.grade_part_ex,t.phone, round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2) as per"
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
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s group by l.teacherid having %s" ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $having
          );
        }else{
           $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order,l.teacherid,t.realname,t.subject,t.train_through_new_time,t.grade_part_ex,t.phone, round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid,l.subject) ,2) as per"
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
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s group by l.teacherid " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
          );
        }
        return $this->main_get_list_by_page($sql,$page_info,10,true);
        /*return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });*/

    }


    public function get_seller_test_lesson_tran_jw( $start_time,$end_time,$require_type,$set_type){
        $where_arr = [
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "mm.account_role=2 ",
            // "mm.del_flag=0",
        ];
        if($set_type==1){
            $where_arr[]= ["lesson_start >= %u",$start_time,-1];
            $where_arr[]= ["lesson_start < %u",$end_time,-1];
        }elseif($set_type==2){
            $where_arr[]= ["tss.set_lesson_time >= %u",$start_time,-1];
            $where_arr[]= ["tss.set_lesson_time < %u",$end_time,-1];
        }
        if($require_type==1){
            $where_arr[] = "tq.seller_top_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tq.seller_top_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
        }

        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order,tss.set_lesson_adminid,"
                                  ."m.account "
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
                                  ." left join %s m on tss.set_lesson_adminid =m.uid"
                                  ." left join %s mm on tq.cur_require_adminid = mm.uid"
                                  ." where %s and tss.set_lesson_adminid>0 group by tss.set_lesson_adminid  " ,
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
            return $item["set_lesson_adminid"];
        });
    }

    public function get_need_reset_list($time){
        $where_arr = [
            ["lesson_start>%u",$time,0],
            // "lesson_start=0",
            "t.teacher_money_type=6",
            "lesson_type <1000",
            "(l.teacher_money_type!=t.teacher_money_type or l.level!=t.level)"
        ];
        $sql = $this->gen_sql_new("select lessonid,t.teacher_money_type as new_teacher_money_type,"
                                  ." l.teacher_money_type as old_teacher_money_type,"
                                  ." t.level as new_level,"
                                  ." l.level as old_level"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s"
                                  ." limit 400"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function reset_lesson_teacher_info($teacherid,$teacher_money_type,$level,$check_time=0){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            ["lesson_start>%u",$check_time,0],
        ];
        $update_arr = [
            ["teacher_money_type=%u",$teacher_money_type,-1],
            ["level=%u",$level,-1],
        ];
        $sql = $this->gen_sql_new("update %s set %s"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$update_arr
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_lesson_condition_info($courseid, $lesson_num ) {
        $sql= $this->gen_sql_new(
            "select lessonid,lesson_condition ,teacherid, lesson_status, lesson_start from  %s"
            . "  where courseid= %u and lesson_num =%u ",
            self::DB_TABLE_NAME, $courseid, $lesson_num  );
        return $this->main_get_row($sql);
    }

    public function set_real_begin_time( $lessonid, $real_begin_time  ) {
        $sql= $this->gen_sql(
            "update %s set  real_begin_time =%u "
            . "  where lessonid=%u and real_begin_time =0  ",
            self::DB_TABLE_NAME,
            $real_begin_time,
            $lessonid
        );
        return $this->main_update($sql);
    }

    public function get_lesson_count_by_teacherid($teacherid,$time=0){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            ["lesson_start>%u",$time,0],
        ];
        $sql = $this->gen_sql_new("select count(1)"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function reset_train_subject(){
        $where_arr = [
            "l.lesson_type=1100",
            "l.train_type=4",
            "l.subject=0",
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.teacherid,t.subject"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_test_succ_count($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,-1],
            ["lesson_start<%u",$end_time,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "lesson_user_online_status != 2",
        ];
        $sql = $this->gen_sql_new(
            "select "
            ." sum( if(l.grade <200,1,0)) as min,"
            ." sum( if(l.grade <200 and tl.type=2,1,0)) as min_succ,"
            ." sum( if(l.grade <300 and grade>=200,1,0)) as mid,"
            ." sum( if(l.grade <300 and grade>=200 and tl.type=2,1,0)) as mid_succ,"
            ." sum( if(l.grade >=300,1,0)) as heigh,"
            ." sum( if(l.grade >=300 and tl.type=2,1,0)) as heigh_succ"
            ." from %s l"
            ." left join %s tl on l.lessonid=tl.money_info"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_teacher_money_list::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_tea_succ_count($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,-1],
            ["lesson_start<%u",$end_time,-1],
            "lesson_type = 2",
            "lesson_del_flag = 0",
            "lesson_user_online_status != 2",
        ];
        $sql = $this->gen_sql_new(
            "select l.teacherid,group_concat(distinct(l.grade)),group_concat(distinct(l.subject)),"
            ."count(l.lessonid) as trial_num,sum(if(money_info>0,1,0)) as trial_succ"
            ." from %s l"
            ." left join %s tl on l.lessonid=tl.money_info and type=2"
            ." where %s"
            ." group by l.teacherid"
            ,self::DB_TABLE_NAME
            ,t_teacher_money_list::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_tea_count($teacherid,$start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,-1],
            ["lesson_start<%u",$end_time,-1],
            "lesson_type in (0,1,3)",
            "lesson_del_flag = 0",
            "lesson_user_online_status != 2",
            "l.teacherid=$teacherid",
        ];
        $sql = $this->gen_sql_new(
            "select sum(lesson_count)"
            ." from %s l"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_test_lesson_teacher_list($start_time,$end_time,$grade){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_type =2",
            "l.confirm_flag <2",
            //  "l.lesson_user_online_status<2",
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            ["lesson_start>%u",$start_time,-1],
            ["lesson_start<%u",$end_time,-1],
            "t.is_test_user=0",
            "l.lesson_status>1",
            "m.account_role=2 or tr.origin like '%%转介绍%%'"
        ];
        if($grade==1){
            $where_arr[]="l.grade>=100 and l.grade<200";
        }elseif($grade==2){
             $where_arr[]="l.grade>=200 and l.grade<300";
        }elseif($grade==3){
             $where_arr[]="l.grade>=300 and l.grade<400";
        }
        $sql = $this->gen_sql_new("select distinct l.teacherid,t.realname"
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s m on tr.cur_require_adminid=m.uid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }
    public function get_teacher_stu_three_month_info(){
        $end_time = time();
        $start_time = time()-90*86400;
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag <2",
            ["lesson_start>%u",$start_time,-1],
            ["lesson_start<%u",$end_time,-1],
            "t.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select count(distinct l.userid) num,l.teacherid,t.realname,"
                                  ."t.subject,t.grade_part_ex,t.grade_end,t.grade_start "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_teacher_list_by_lesson_time($start_time,$end_time,$lesson_type){
        $where_arr=[
            "l.lesson_del_flag=0",
            //   "l.lesson_type in (0,1,3)",
            "l.confirm_flag <2",
            ["lesson_start>%u",$start_time,-1],
            ["lesson_start<%u",$end_time,-1],
            "t.is_test_user=0"
        ];
        if($lesson_type==2){
            $where_arr[]="l.lesson_type=2";
            $where_arr[]="tss.success_flag<2";
        }elseif($lesson_type==-2){
            $where_arr[]="l.lesson_type in (0,1,3)";
        }
        $sql = $this->gen_sql_new("select count(distinct l.userid) num,l.teacherid,t.realname,"
                                  ."t.subject,t.grade_part_ex,t.grade_end,t.grade_start "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }


    public function get_teacher_list_by_time_new($start_time,$end_time){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag <>2",
            ["lesson_start>%u",$start_time,-1],
            ["lesson_start<%u",$end_time,-1],
            "t.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select l.teacherid,sum(l.lesson_count) lesson_total"
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_teacher_stu_three_month_list($teacherid){
        $end_time = time();
        $start_time = time()-90*86400;
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag <2",
            ["lesson_start>%u",$start_time,-1],
            ["lesson_start<%u",$end_time,-1],
            ["l.teacherid=%u",$teacherid,-1],
            "t.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select distinct l.userid "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_first_regular_lesson_time($teacherid,$userid){
        $where_arr=[
            "lesson_del_flag=0",
            "lesson_type in (0,1,3)",
            "confirm_flag <2",
            ["teacherid=%u",$teacherid,-1],
            ["userid=%u",$userid,-1],
            "lesson_status>0"
        ];
        $sql = $this->gen_sql_new("select min(lesson_start) "
                                  ." from %s "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_last_regular_lesson_time($teacherid,$userid){
        $where_arr=[
            "lesson_del_flag=0",
            "lesson_type in (0,1,3)",
            "confirm_flag <2",
            ["teacherid=%u",$teacherid,-1],
            ["userid=%u",$userid,-1],
            "lesson_status>0"
        ];
        $sql = $this->gen_sql_new("select max(lesson_start) "
                                  ." from %s "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function eval_real_xmpp_server( $xmpp_server_name, $current_server, $map=null ) {
        if (!$xmpp_server_name) {
            $xmpp_server_name=$current_server;
        }
        if (!$xmpp_server_name) { //默认设置到杭州
            $xmpp_server_name="h_01";
        }

        if (!$map)  {
            $row=$this->task->t_xmpp_server_config->get_info_by_server_name($xmpp_server_name );
        }else{
            $row=@$map[$xmpp_server_name];
        }
        if ($row) {
            $ret = [
                'ip'             => $row["ip"] ,
                'xmpp_port'      => $row["xmpp_port"] ,
                'webrtc_port'    => $row["webrtc_port"] ,
                'websocket_port' => $row["websocket_port"] ,
                "region"         => $row["server_desc"] ,
            ];
            return $ret;
        }else {
            return  [
                'ip'             => '121.43.230.95',
                'xmpp_port'      => '5222',
                'webrtc_port'    => '5061',
                'websocket_port' => '20061',
                "region"         => "杭州_01",
            ];
        }

    }


    public function get_real_xmpp_server($lessonid ) {
        $lesson_info=$this->field_get_list($lessonid,"courseid,xmpp_server_name");
        $xmpp_server= $lesson_info["xmpp_server_name"];
        $current_server="";
        if(!$xmpp_server) {
            $current_server= $this->task->t_course_order->get_current_server($lesson_info["courseid"]);
        }
        return $this->eval_real_xmpp_server( $xmpp_server, $current_server );
    }


    public function get_test_lesson_succ_num($start_time,$end_time){
        $where_arr = [
            "l.lesson_user_online_status = 1",
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            // "tll.test_lesson_fail_flag=0",
            // "tll.fail_greater_4_hour_flag=0",
            "ts.require_admin_type =2",
            "tlr.accept_flag=1",
        ];

        // $this->where_arr_add_time_range($where_arr,"tlr.require_time",$start_time,$end_time);
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select count(tll.lessonid) from %s l "
        // $sql = $this->gen_sql_new("  select tll.lessonid from %s l "
                                  ." left join %s tll on tll.lessonid=l.lessonid "
                                  ." left join %s tlr on tlr.require_id=tll.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id=tlr.test_lesson_subject_id"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_test_succ_for_month($start_time,$end_time){
        $where_arr = [
            "l.lesson_user_online_status = 1",
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tll.fail_greater_4_hour_flag=0",
            "tlr.accept_flag=1",
            "ts.require_admin_type =2",
        ];

        $this->where_arr_add_time_range($where_arr,"tll.set_lesson_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select count(distinct l.lessonid) from %s l "
                                  ." left join %s tll on tll.lessonid=l.lessonid "
                                  ." left join %s tlr on tlr.require_id=tll.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id=tlr.test_lesson_subject_id"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME //tlr
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }


    public function get_tea_stu_num_list($start_time,$end_time,$teacherid){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            ["teacherid=%u",$teacherid,0],
            "lesson_del_flag=0",
            "lesson_type<1000",
            "lesson_type<>2",
        ];
        $sql = $this->gen_sql_new("select s.userid,s.type"
                                  ." from %s l"
                                  ." left join %s s on l.userid=s.userid"
                                  ." where %s "
                                  ." group by s.userid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function check_lesson_teacher_money_type(){
        $where_arr = [
            ["lesson_start>%u",time(),0],
            "lesson_status=0",
            "lesson_type<1000",
            "(l.teacher_money_type!=t.teacher_money_type or l.level!=t.level)",
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.teacher_money_type as l_teacher_money_type,l.level as l_level,"
                                  ." t.teacher_money_type,t.level"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_lesson_total($start,$end,$teacherid,$is_test_user=-1){
        $where_arr = [
            ["l.lesson_start>%u",$start,0],
            ["l.lesson_start<%u",$end,0],
            ["l.teacherid=%u",$teacherid,0],
            ["t.is_test_user=%u",$is_test_user,-1],
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag!=2",
        ];
        $sql = $this->gen_sql_new("select sum(l.lesson_count) as lesson_total"
                                  ." from %s l left join %s t on l.teacherid=t.teacherid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_teacher_list_for_total_info($start_time,$end_time,$not_start_time=0,$not_end_time=0){
        $where_arr = [
            ["l.lesson_start>%u",$start_time,0],
            ["l.lesson_start<%u",$end_time,0],
            "l.lesson_type<1000",
            "l.lesson_del_flag=0",
            "l.confirm_flag!=2",
            "t.is_test_user=0"
        ];

        // if($not_start_time>0 && $not_end_time>0){
        //     $not_arr = [
        //         ["l2.lesson_start>%u",$not_start_time,0],
        //         ["l2.lesson_start<%u",$not_end_time,0],
        //         "l2.lesson_type<1000",
        //         "l2.lesson_del_flag=0",
        //         "l2.confirm_flag!=2",
        //         "t.teacherid=l2.teacherid"
        //     ];
        //     $not_sql = $this->gen_sql_new("and not exists (select 1 from %s l2 where %s)"
        //                                   ,self::DB_TABLE_NAME
        //                                   ,$not_arr
        //     );
        // }else{
        //     $not_sql = "true";
        // }

        $sql = $this->gen_sql_new("select t.teacherid,t.realname,t.phone,sum(if(l.lesson_type=2,1,0)) as trial_num,"
                                  ." count(r.id) as succ_num,count(distinct(l2.userid)) as normal_stu_num,"
                                  ." group_concat(distinct(l.subject)) as stu_subject"
                                  ." from %s t"
                                  ." left join %s l force index(lesson_start) on t.teacherid=l.teacherid"
                                  ." left join %s r on l.lessonid=r.lessonid"
                                  ." left join %s l2 on l.lessonid=l2.lessonid and l2.lesson_type!=2"
                                  ." where %s"
                                  // ." %s "
                                  ." group by t.teacherid"
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_money_list::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  // ,$not_sql
        );
        // echo $sql;exit;
        return $this->main_get_list($sql);
    }

    public function get_on_teacherid(){

        $sql = $this->gen_sql_new(" select distinct(t.teacherid) from %s t left join %s l on l.teacherid=t.teacherid "
                                  ." where  l.lesson_start>1490976000 and t.create_time<1490976000 "
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
        );


        return $this->main_get_list($sql);
    }


    public function get_on_num(){
        $sql = $this->gen_sql_new("select * from %s tt left join %s l on l.teacherid=tt.teacherid where l.lesson_start>1490976000 and tt.is_test_user=0 and tt.trial_lecture_is_pass =1 "
                                  ,t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_first_new_train_lessonid(){
        $time =time();
        $sql = $this->gen_sql_new("select lessonid from %s"
                                  ." where lesson_start>%u "
                                  ."and lesson_del_flag=0"
                                  ." and lesson_type=1100 and train_type=1"
                                  ." order by lesson_start limit 1",
                                  self::DB_TABLE_NAME,
                                  $time
        );
        return $this->main_get_value($sql);
    }
    public function get_lesson_info_for_check_lesson_end($courseid, $lesson_num) {
        $sql= $this->gen_sql_new(
            "select lessonid,teacherid,lesson_end from  %s"
            . "  where courseid= %u and lesson_num =%u ",
            self::DB_TABLE_NAME, $courseid, $lesson_num  );
        return $this->main_get_row($sql);
    }

    public function get_lesson_info($lessonid){
        $sql = $this->gen_sql_new( "  select lesson_name, subject, lesson_start, lesson_end, s.nick, l.grade from %s l"
                                   ." left join %s s on s.userid=l.userid"
                                   ." where l.lessonid=$lessonid"
                                   ,self::DB_TABLE_NAME
                                   ,t_student_info::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }

    public function get_month_list(){
        $sql = $this->gen_sql_new( " select l.userid,from_unixtime(l.lesson_start),s.user_agent "
                                   ." from %s l "
                                   ." left join %s s on s.userid=l.userid"
                                   ." where l.lesson_start>=1498838400 and l.lesson_start<1507824000 "
                                   ." and l.userid>0 and s.is_test_user=0 "
                                   ." order by l.lesson_start "
                                   ,self::DB_TABLE_NAME
                                   ,t_student_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_time_xmpp_list($xmpp_value,$start_time, $end_time )
    {
        // dd($xmpp_value);
        $where_arr=[
            "confirm_flag not in (2,3)",
            "lesson_del_flag=0",
            "lesson_type <>4001",

        ];
        if($xmpp_value != ''){
            $where_arr[]=  [ "xmpp_server_name='%s' ",$xmpp_value];
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

    public function get_lesson_list_by_teacher_money_type($start,$end,$teacher_money_type,$teacherid=0){
        $where_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            ["teacher_money_type=%u",$teacher_money_type,0],
            ["teacherid=%u",$teacherid,0],
            "lesson_type in (0,1,3)",
            "lesson_status = 2",
            "confirm_flag != 2",
            "lesson_del_flag = 0",
        ];
        $sql = $this->gen_sql_new("select lessonid,lesson_count,teacherid"
                                  ." from %s "
                                  ." where %s"
                                  ." order by lesson_start asc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_lesson_tea_stu_info_new($start_time,$end_time,$lesson_type){
        $where_arr=[
            ["l.lesson_start>%u",$start_time,0],
            ["l.lesson_start<%u",$end_time,0],
            "l.lesson_del_flag=0",
            // "l.lesson_type <1000",
            "s.is_test_user=0",
            "t.is_test_user=0"
        ];
        if($lesson_type==1){
            $where_arr[]="l.lesson_type in (0,1,3)";
            $where_arr[]="l.confirm_flag <>2";
        }elseif($lesson_type==2){
            $where_arr[] = "l.lesson_type=2";
            $where_arr[] = "tss.success_flag <2";
        }

        $sql = $this->gen_sql_new("select count(distinct l.userid) stu_num,count(distinct l.teacherid) tea_num,"
                                  ." count(*) test_lesson_num,l.subject "
                                  ." from %s l left join %s s on l.userid = s.userid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." where %s group by l.subject ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });
    }


    /**
     * 获取试听课学生,老师的教材版本进行匹配度统计
     */
    public function get_textbook_match_lesson_list($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type=2",
            "confirm_flag!=2",
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("select s.editionid,tl.textbook,t.teacher_textbook"
                                  ." from %s l"
                                  ." left join %s tls on l.lessonid=tls.lessonid"
                                  ." left join %s tr on tls.require_id=tr.require_id"
                                  ." left join %s tl on tr.test_lesson_subject_id=tl.test_lesson_subject_id"
                                  ." left join %s s on l.userid=s.userid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s"
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

    /**
     * 获取试听课学生,老师的教材版本进行匹配度统计,并计算相关转换率(根据t_coures_order)
     */
    public function get_textbook_match_lesson_and_order_list($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type=2",
            "confirm_flag!=2",
            "lesson_del_flag=0",
            "s.is_test_user=0",
            "t.is_test_user=0",
            "(tls.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "(m.account_role=2 or tr.origin like '%%转介绍%%')",
        ];
        $sql = $this->gen_sql_new(
            "select s.editionid,tl.textbook,t.teacher_textbook,c.userid succ_userid,l.userid as stu_userid"
            ." from %s l"
            ." left join %s tls on l.lessonid=tls.lessonid"
            ." left join %s tr on tls.require_id=tr.require_id"
            ." left join %s tl on tr.test_lesson_subject_id=tl.test_lesson_subject_id"
            ." left join %s s on l.userid=s.userid"
            ." left join %s t on l.teacherid=t.teacherid"
            ." left join %s m on tr.cur_require_adminid = m.uid"
            ." left join %s c on "
            ." (l.userid = c.userid "
            ." and l.teacherid = c.teacherid "
            ." and l.subject = c.subject "
            ." and c.course_type=0 and c.courseid >0) "
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_teacher_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_course_order::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_count_money_info_by_month($start_time,$end_time){
        $where_arr = [
            ["l.lesson_start >=%u ",$start_time,-1],
            ["l.lesson_start < %u ",$end_time,-1],
            "l.confirm_flag in (0,1,3)",
            "l.lesson_type in (0,1,3)",
            "s.is_test_user=0",
            "l.lesson_user_online_status=1 ",
            "l.lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new(
            "select sum(l.lesson_count) lesson_count,count(distinct l.userid) lesson_stu_num,sum(ol.price) lesson_count_money "
            ." from %s l"
            ." left join %s s on s.userid=l.userid"
            ." left join %s ol on ol.lessonid=l.lessonid"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_order_lesson_list::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_lesson_count_sum($userid,$start_time,$end_time){
        $where_arr = [
            ["userid=%u",$userid,-1],
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "confirm_flag  in  (0,1,3,4)",
            "lesson_type in(0,1,3 ) ",
            "lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select sum(lesson_count) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_lesson_count_list_new($userid,$start_time,$end_time){
        $where_arr = [
            ["userid=%u",$userid,-1],
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "confirm_flag  in  (0,1,3,4)",
            "lesson_type in(0,1,3 ) ",
            "lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select lesson_count,lesson_start from %s where %s order by lesson_start",
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql);

    }



    public function del_lesson_no_start_by_userid($userid){
        $where_arr=[
            ["userid = %u",$userid,-1],
            "lesson_status = 0",
            "lesson_type in (0,1,3)",
            "lesson_del_flag=0"
        ];

        $sql = $this->gen_sql_new("select courseid,lessonid from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        $ret_info = $this->main_get_list($sql);
        $sql=$this->gen_sql_new("update %s set lesson_del_flag=1 where %s",
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        $ret=$this->main_update($sql);
        if($ret_info){
            foreach($ret_info as $item){
                if ($ret) {
                    $this->task->t_homework_info->row_delete($item['lessonid']);
                }
                $this->task->t_lesson_info->reset_lesson_list($item['courseid']);
            }
        }
        return $ret;

    }


    public function get_first_lesson_start($userid,$start_time){
        $where_arr = [
            ["userid=%u",$userid,-1],
            ["lesson_start>%u",$start_time,0],
            "confirm_flag  in  (0,1,3,4)",
            "lesson_type in(0,1,3 ) ",
            "lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select min(lesson_start) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function delete_lesson_by_time_userid($userid,$start_time){
        $where_arr = [
            ["userid=%u",$userid,-1],
            ["lesson_start>%u",$start_time,0],
            "confirm_flag  in  (0,1,3,4)",
            "lesson_type in(0,1,3 ) ",
            "lesson_status = 0",
            "lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select courseid,lessonid from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        $ret_info = $this->main_get_list($sql);
        $sql=$this->gen_sql_new("update %s set lesson_del_flag=1 where %s",
                                self::DB_TABLE_NAME,
                                $where_arr
        );
        $ret=$this->main_update($sql);
        if($ret_info){
            foreach($ret_info as $item){
                if ($ret) {
                    $this->task->t_homework_info->row_delete($item['lessonid']);
                }
                $this->task->t_lesson_info->reset_lesson_list($item['courseid']);
            }
        }
        return $ret;


    }

    public function get_lesson_num_by_teacherid($teacherid,$start_time,$end_time,$lesson_type=-2){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            ["lesson_start>=%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_del_flag=0",
            "confirm_flag <>2"
        ];
        if($lesson_type==-2){
            $where_arr[]="lesson_type in(0,1,3)";
            $sql = $this->gen_sql_new("select count(*) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        }elseif($lesson_type==2){
            $where_arr[]="lesson_type =2";
            $where_arr[]="success_flag <2";
            $sql = $this->gen_sql_new("select count(tss.lessonid)"
                                      ." from %s l join %s tss on l.lessonid = tss.lessonid"
                                      ." where %s",
                                      self::DB_TABLE_NAME,
                                      t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                      $where_arr);

        }
        return $this->main_get_value($sql);
    }

    public function get_more_trial_lesson_list(){
        $where_arr = [
            "lesson_type=1100",
            "train_type=4",
            "lesson_start=0",
        ];
        $sql = $this->gen_sql_new("select lessonid,count(lessonid) as lesson_num"
                                  ." from %s "
                                  ." where %s"
                                  ." group by teacherid"
                                  ." having lesson_num>1"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_lessonid_by_parentid($parentid){
        $sql = $this->gen_sql_new("  select l.lessonid from %s l"
                                  ." left join %s p on p.userid=l.userid"
                                  ." where p.parentid=%d and l.lesson_type=2 and lesson_start>1509897600"
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,$parentid
        );


        return $this->main_get_value($sql);
    }

    public function get_train_lesson_num($start_time,$end_time,$train_type=1){
        $where_arr=[
            "lesson_del_flag=0",
            "lesson_type=1100",
            ["lesson_start>=%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            ["train_type=%u",$train_type,-1]
        ];
        $sql = $this->gen_sql_new("select count(*) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_train_lesson_part_info($start_time,$end_time,$train_type=1){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_type=1100",
            ["l.lesson_start>=%u",$start_time,0],
            ["l.lesson_start<%u",$end_time,0],
            ["l.train_type=%u",$train_type,-1]
        ];
        $sql = $this->gen_sql_new("select l.lessonid,ta.userid,lo.opt_time,t.train_through_new_time "
                                  ." ,tf.simul_test_lesson_pass_time "
                                  ." from %s l left join %s ta on l.lessonid=ta.lessonid"
                                  ." left join %s lo on l.lessonid = lo.lessonid and opt_type=1 and lo.userid = ta.userid and not"
                                  ." exists (select 1 from %s where lessonid = lo.lessonid and opt_type=1 and userid = lo.userid and opt_time < lo.opt_time)"
                                  ." left join %s t on ta.userid = t.teacherid"
                                  ." left join %s tf on t.teacherid = tf.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_lesson_opt_log::DB_TABLE_NAME,
                                  t_lesson_opt_log::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_flow::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_lessonid_by_pid($parentid){

        $where_arr = [
            "p.parentid=$parentid",
            "l.lesson_type =2",
            "l.lesson_del_flag =0"
        ];

        $sql = $this->gen_sql_new("  select lessonid from %s l "
                                  ." left join %s p on p.userid=l.userid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    /**
     * 获取学生或老师未进入课堂的有效课程 (command:SetLessonStuAttend)
     *
     */
    public function get_lesson_with_zero_attend($start_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            "lesson_type=2",
            "stu_attend=0",
            "opt_time>0",
            "lesson_status=2",
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.stu_attend,lo.opt_time"
                                  ." from %s l "
                                  ." left join %s lo on l.lessonid=lo.lessonid and l.userid=lo.userid and opt_type=1 "
                                  ." where %s"
                                  ." group by l.lessonid"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_opt_log::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_train_through_lesson_teacher_list($start_time,$end_time,$research_teacher_flag=-1){
        $where_arr = [
            " t.is_quit=0 ",
            " t.is_test_user =0",
            "t.train_through_new_time>0",
            "t.train_through_new=1",
            "l.lesson_del_flag=0",
            "l.lesson_type <1000",
            "l.confirm_flag <>2",
            "(tss.success_flag<2 or tss.success_flag is null)",
            ['l.lesson_start>=%u',$start_time,0],
            ['l.lesson_start<%u',$end_time,0],
        ];
        if($research_teacher_flag=0){
            $where_arr[]="(m.account_role is null or m.account_role !=4)";
        }

        $sql = $this->gen_sql_new("select l.teacherid,t.realname,l.subject,t.subject t_subject,"
                                  ." l.lesson_type,l.lesson_start,l.lessonid,t.grade_end,"
                                  ." t.grade_start,t.grade_part_ex,l.grade "
                                  ." from %s l "
                                  ." left join %s t on t.teacherid = l.teacherid"
                                  ." left join %s tf on t.teacherid = tf.teacherid"
                                  ." left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s order by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_flow::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_teacher_tomorrow_lesson_list($lesson_start, $lesson_end){
        $where_arr = [
            "l.lesson_type in (0,1,3)",
            "t.is_test_user = 0",
            "t.wx_openid != ''",
            "l.lesson_del_flag=0",
            "l.confirm_flag<2"
        ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$lesson_start,$lesson_end);

        $sql = $this->gen_sql_new("  select  l.teacherid,t.nick, wx_openid from %s l "
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s group by l.teacherid "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_tea_lesson_info($lesson_start, $lesson_end,$teacherid){

        $where_arr = [
            "l.lesson_type in (0,1,3)",
            "l.teacherid = $teacherid",
            "l.lesson_del_flag=0",
        ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$lesson_start,$lesson_end);

        $sql = $this->gen_sql_new("  select l.subject, lesson_start, l.lesson_end, l.teacherid, s.nick ,l.userid from %s l "
                                  ." left join %s s on s.userid=l.userid"
                                  ." where %s  "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_parent_tomorrow_lesson_list($lesson_start, $lesson_end){
        $where_arr = [
            "l.lesson_type in (0,1,3)",
            "l.lesson_del_flag=0",
            "s.is_test_user=0",
            "p.wx_openid != ''",
            "l.confirm_flag<2"
        ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$lesson_start,$lesson_end);

        $sql = $this->gen_sql_new("  select a.phone, p.parentid, p.nick, p.wx_openid, s.assistantid  from %s l "
                                  ." left join %s pc on pc.userid=l.userid"
                                  ." left join %s p on p.parentid=pc.parentid"
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s a on a.assistantid=s.assistantid"
                                  ." where %s group by p.parentid  "
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }




    public function get_par_lesson_info($lesson_start, $lesson_end, $parentid){
        $where_arr = [
            "l.lesson_type = 0",
            "pc.parentid=$parentid",
            "l.lesson_del_flag=0",
        ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$lesson_start,$lesson_end);

        $sql = $this->gen_sql_new("  select l.subject, l.lesson_start, l.lesson_end, t.nick from %s l "
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." left join %s pc on pc.userid=l.userid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }





    public function get_lesson_list_for_set_xmpp_server( $start_time,$end_time) {
        $where_arr= [
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql =  $this->gen_sql_new(

            "select l.lessonid, c.current_server "
            . " from %s l "

            . " left join  %s c on l.courseid = c.courseid "
            . " where %s ",
            self::DB_TABLE_NAME,
            t_course_order::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function check_has_tea_cw_url($four_start,$four_end){
        $where_arr = [
            "l.tea_cw_url = ''",
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag<2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$four_start,$four_end);
        $sql = $this->gen_sql_new("  select l.lessonid, l.subject, l.lesson_start, l.lesson_end, t.wx_openid from %s l"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_info_by_stu_phone($phone){
        $start_time = time()-90*86400;
        $end_time = time();
        $where_arr = [
            //  ["l.lesson_start>%u",$start_time,0],
            // ["l.lesson_start<%u",$end_time,0],
            ["s.phone='%s'",$phone,0],
            "l.lesson_type in (0,1,3)",
            "l.lesson_del_flag=0",
            "l.confirm_flag <2"
        ];
        $sql = $this->gen_sql_new("select t.realname,t.phone "
                                  ." from %s l left join %s s on l.userid = s.userid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s order by lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_late_lesson_info($late_time){
        $late_time_begin = $late_time+60;
        $where_arr = [
            "l.lesson_del_flag = 0",
            "l.tea_rate_time=0",
            "l.lesson_type in (0,1,3)",
            "l.lesson_end > $late_time",
            "l.lesson_end <= $late_time_begin",
            "l.confirm_flag<2"
        ];

        $sql = $this->gen_sql_new("  select t.wx_openid, l.lesson_start, l.lesson_end, l.subject, s.nick as stu_nick, t.nick as tea_nick from %s l"
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function check_is_doing($teacherid){
        $now = time();
        $end = $now + 60;

        $where_arr = [
            'l.lesson_del_flag=0',
            "l.teacherid=$teacherid",
            "l.lesson_type in (0,1,3)",
            "l.lesson_start<$now",
            "l.lesson_end>=$now"
        ];

        $sql = $this->gen_sql_new("  select 1 from %s l "
                                  ." left join %s t on t.teacherid=l.teacherid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);

    }


    public function check_is_doing_test($teacherid){
        $now = time();
        $end = $now + 60;

        $where_arr = [
            'l.lesson_del_flag=0',
            "l.teacherid=$teacherid",
            "l.lesson_type in (0,1,3)",
            'l.lesson_status=1'
            // "l.lesson_start<$now",
            // "l.lesson_end>=$now"
        ];

        $sql = $this->gen_sql_new("  select 1 from %s l "
                                  ." left join %s t on t.teacherid=l.teacherid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);

    }


    public function get_test_list_for_month($start_time,$end_time){

        $where_arr = [
            "l.lesson_type = 2"
        ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("  select count(*) from %s l "
                                  ." where %s  "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }


    public function get_success_test_lesson_list_broken($start_time,$end_time,$subject,$grade_part_ex,$teacherid,$teacher_subject,$identity,$tea_subject,$qz_flag,$tea_status,$teacher_account,$fulltime_flag=-1,$fulltime_teacher_type=-1){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
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

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);


        $sql = $this->gen_sql_new("select count(distinct l.lessonid) success_lesson"
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
        return $this->main_get_value($sql);

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
            "mm.del_flag=0",
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

            "mm.del_flag=0",
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

        $sql = $this->gen_sql_new("select count(distinct c.userid,c.teacherid,c.subject) have_order"
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
        return $this->main_get_value($sql);

    }

    public function get_stu_first_lesson_time_by_subject($userid){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.confirm_flag<2",
            "l.lesson_type in (0,1,3)",
            ["l.userid = %u",$userid,-1],
            "l.lesson_status>0"
        ];
        $sql = $this->gen_sql_new("select l.subject,l.lesson_start,l.userid"
                                  ." from %s l "
                                  ." where %s and l.lesson_start = (select min(lesson_start) from %s where lesson_del_flag=0 and confirm_flag<2 and lesson_type in (0,1,3) and subject = l.subject and userid = l.userid and lesson_status>0)"
                                  ." group by l.subject,l.userid",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
    public function get_stu_first_regular_lesson_time($userid){
        $where_arr=[
            "lesson_del_flag=0",
            "confirm_flag<2",
            "lesson_type in (0,1,3)",
            ["userid = %u",$userid,-1],
            "lesson_status>0"
        ];
        $sql = $this->gen_sql_new("select min(lesson_start)"
                                  ." from %s  "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function check_is_consume($orderid){
        $where_arr = [
            "o.orderid=$orderid",
        ];
        $sql = $this->gen_sql_new("select 1 "
                                  ." from %s l "
                                  ." left join %s o on o.orderid=l.orderid"
                                  ." where %s"
                                  ,t_order_lesson_list::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_lesson_teacher_num($lesson_start, $lesson_end, $lesson_type){
        $where_arr = [
            "l.lesson_del_flag=0",
            "l.confirm_flag<>2",
            "l.lesson_user_online_status<2",
            "t.is_test_user=0"
        ];
        if($lesson_type==-2){
            $where_arr[]="l.lesson_type in (0,1,3)";
        }else{
            $where_arr[]=["l.lesson_type=%u",$lesson_type,-2];
        }

        $this->where_arr_add_time_range($where_arr,"lesson_start",$lesson_start,$lesson_end);

        $sql = $this->gen_sql_new("  select count(distinct l.teacherid) num from %s l "
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_extreme_lesson_time($userid){
        $where_arr = [
            "userid = $userid",
            "l.lesson_type in (0,1,3)",
            "l.lesson_del_flag=0",
            "l.lesson_status=2"
        ];

        $sql = $this->gen_sql_new("  select max(l.lesson_start) as max_time, min(l.lesson_end) as min_time from %s l"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_tea_info_by_subject($start_time,$end_time){
        $where_arr=[
            "t.is_test_user=0",
            "l.lesson_del_flag=0",
            "l.lesson_type <1000",
            "l.confirm_flag<>2",
            "l.subject in (1,2,3)"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct l.teacherid) num,floor(l.grade/100) grade,l.subject "
                                  ."from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." where %s group by l.subject,grade",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_tea_num_by_subject_grade($start_time,$end_time,$subject,$grade){
        $where_arr=[
            "t.is_test_user=0",
            "l.lesson_del_flag=0",
            "l.lesson_type <1000",
            "l.confirm_flag<>2",
            ["l.subject=%u",$subject,-1]
        ];
        if($grade==1){
            $where_arr[]="l.grade>=100 and l.grade<200";
        }elseif($grade==2){
            $where_arr[]="l.grade>=200 and l.grade<300";
        }elseif($grade==3){
            $where_arr[]="l.grade>=300 and l.grade<400";
        }
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct l.teacherid) num "
                                  ."from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_tea_lesson_info_for_approved($start_time, $end_time,$page_num,$teacherid){
        $where_arr = [
            "t.trial_lecture_is_pass=1",
            "t.is_test_user=0",
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.lesson_status=2",
            "t.teacher_money_type = 6",
            ["l.teacherid=%d",$teacherid,-1]
        ];
        $this->where_arr_add_time_range($where_arr, "l.lesson_start", $start_time, $end_time);
        $sql = $this->gen_sql_new("  select sum(l.lesson_count)/100 as total_lesson_num, t.teacherid, t.nick as t_nick, count(distinct(l.lessonid)) as lesson_num, count(distinct(l.userid)) as stu_num from %s l "
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s group by t.teacherid order by total_lesson_num desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }


    public function get_total_lesson_time($start_time, $end_time,$teacherid){
        $where_arr = [
            "t.trial_lecture_is_pass=1",
            "t.is_test_user=0",
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.lesson_status=2",
            "t.teacher_money_type = 6",
            ["l.teacherid=%d",$teacherid,-1]
        ];
        $this->where_arr_add_time_range($where_arr, "l.lesson_start", $start_time, $end_time);
        $sql = $this->gen_sql_new("  select sum(l.lesson_count)/100 from %s l"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s  "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_violation_num($start_time, $end_time, $teacherid){
        $where_arr = [
            "l.lesson_type in (0,1,3)",
            "l.teacherid=$teacherid",
            "l.lesson_del_flag=0",
            "l.lesson_status=2"
        ];
        $this->where_arr_add_time_range($where_arr, "l.lesson_start", $start_time, $end_time);

        // select 1 from t_lesson_info where absenteeism_flag=1

        $sql = $this->gen_sql_new("  select COUNT( CASE WHEN l.lesson_cancel_reason_type=23 THEN 1 ELSE null END ) as late_num,"//迟到
                                  ." COUNT( CASE WHEN l.lesson_cancel_reason_type=21 THEN 1 ELSE null END ) as cancel_num,"//旷课
                                  ." COUNT( CASE WHEN l.stu_performance='' THEN 1 ELSE null END ) as comment_num,"//未评价
                                  ." COUNT( CASE WHEN l.tea_cw_status=0 THEN 1 ELSE null END ) as tea_cw_num,"//未传课件
                                  ." COUNT( CASE WHEN h.work_status=0 THEN 1 ELSE null END ) as work_num"//未留作业
                                  ." from %s l"
                                  ." left join %s h on h.lessonid=l.lessonid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_unlesson($start_time, $end_time,$type){
        $where_arr = [
            " tr.stu_lesson_content != '正常上课' ",
            " tr.stu_lesson_content != '顺利完成' ",
            " tr.stu_lesson_content != ' ' ",
            "l.lesson_del_flag=0",
            ['l.lesson_user_online_status=%d',$type,-1],
            "l.lesson_type=2",
            "t.teacher_type in (3,4)"
        ];

        $this->where_arr_add_time_range($where_arr, "l.lesson_start", $start_time, $end_time);

        $sql = $this->gen_sql_new("  select l.lessonid, tr.stu_lesson_content from %s l "
                                  ." left join %s tss on tss.lessonid=l.lessonid"
                                  ." left join %s tr on tr.require_id=tss.require_id"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }



    public function check_is_fail($lessonid,$lesson_type){
        $where_arr = [
            " tr.stu_lesson_content != '正常上课' ",
            " tr.stu_lesson_content != '顺利完成' ",
            " tr.stu_lesson_content != ' ' ",
            "l.lesson_del_flag=0",
            "l.lesson_type=$lesson_type",
            "l.lessonid=$lessonid",
            "t.teacher_type in (3,4)"
        ];


        $sql = $this->gen_sql_new("  select 1 from %s l "
                                  ." left join %s tss on tss.lessonid=l.lessonid"
                                  ." left join %s tr on tr.require_id=tss.require_id"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_lesson_info_by_teacherid_test($teacherid){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1]
        ];
        $sql = $this->gen_sql_new("select lessonid,teacherid,userid,lesson_start "
                                  ."from %s where %s order by lesson_start desc limit 3",
                                  self::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_common_list($start_time,$end_time){
        $where_arr = [
            " l.lesson_del_flag=0",
            " l.lesson_type in (0,1,3)",
        ];
        $this->where_arr_add_time_range($where_arr, "l.lesson_end", $start_time, $end_time);

        $sql = $this->gen_sql_new("  select l.userid, m.wx_openid, l.lesson_count, l.lesson_start, l.lesson_end, l.subject, l.teacherid from %s l"
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s a on a.assistantid=s.assistantid"
                                  ." left join %s m on m.phone=a.phone"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }


    public function check_not_first_lesson($userid,$teacherid,$subject,$lesson_start){
        $where_arr = [
            " l.lesson_del_flag=0",
            " l.lesson_type in (0,1,3)",
            " l.lesson_start<$lesson_start",
            " l.userid=$userid",
            " l.teacherid=$teacherid",
            " l.subject=$subject",
            " l.lesson_status=2"

        ];

        $sql = $this->gen_sql_new("  select 1 from %s l "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_lesson_info_for_tag($lessonid){
        $sql = $this->gen_sql_new("  select l.subject, l.userid, l.teacherid, l.lesson_start, l.lesson_end, m.wx_openid, tr.accept_adminid,m.wx_openid "
                                  ." from %s l "
                                  ." left join %s tll on tll.lessonid=l.lessonid"
                                  ." left join %s tr on tr.require_id=tll.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id=tr.test_lesson_subject_id"
                                  ." left join %s m on m.uid=require_adminid"
                                  ." where l.lessonid=$lessonid"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }


    public function get_student_info_to_tea($lessonid){
        $sql = $this->gen_sql_new("  select if(test_stu_request_test_lesson_demand='',stu_request_test_lesson_demand,test_stu_request_test_lesson_demand) as  stu_request_test_lesson_demand, s.nick as stu_nick, l.lesson_start, l.lesson_end, m.phone  "
                                  ." from %s l "
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s t on t.teacherid=l.teacherid"
                                  ." left join %s tss on tss.lessonid=l.lessonid"
                                  ." left join %s tr on tr.require_id=tss.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id=tr.test_lesson_subject_id"
                                  ." left join %s m on m.uid=require_adminid"
                                  ." where l.lessonid=$lessonid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }

    public function get_teacher_lesson_info($teacherid,$start_time,$end_time){
        $where_arr=[
            "t.is_test_user = 0",
            "l.lesson_del_flag = 0",
            "l.lesson_type<1000",
            ["l.teacherid=%d",$teacherid,-1]
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
       
        $sql=$this->gen_sql_new("select  sum(if(l.confirm_flag < 2 and l.lesson_type in (0,1,3),1,0)) reg_num, sum(if(deduct_come_late=1 and l.lesson_type in (0,1,3),1,0)) late_num, sum(if(lesson_cancel_reason_type=21 and l.lesson_type in (0,1,3),1,0)) kk_num,sum(if(lesson_cancel_reason_type=2 and l.lesson_type in (0,1,3),1,0)) change_num,sum(if(lesson_cancel_reason_type=12 and l.lesson_type in (0,1,3),1,0)) leave_num,  sum(if(l.lesson_type=2 and tss.success_flag<2,1,0)) test_num,sum(if(l.lesson_type=2 and tss.test_lesson_fail_flag=109,1,0)) test_kk_num, sum(if(l.lesson_type=2 and tss.test_lesson_fail_flag=113,1,0)) test_person_num,sum(if(deduct_come_late=1 and l.lesson_type =2,1,0)) test_late_num,  l.teacherid "
                                ." from %s l "
                                ." left join %s t on t.teacherid = l.teacherid"
                                ." left join %s tss on tss.lessonid = l.lessonid"
                                ." where  %s group by l.teacherid "
                                ,self::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);


    }

}
























