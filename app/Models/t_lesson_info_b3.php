<?php

namespace App\Models;
use App\Models\Zgen as Z;
use \App\Models as M;
use \App\Enums as E;

class t_lesson_info_b3 extends \App\Models\Zgen\z_t_lesson_info{
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
            "select lesson_start from %s"
            . " where userid= %u and  grade=%u and lesson_start>0  order by lesson_start asc limit 1  ",
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

    public function get_seller_test_lesson_tran_info( $start_time,$end_time,$require_type,$set_type){
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
            $where_arr[] = "tss.top_seller_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
        }
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid) person_num,count(l.lessonid) lesson_num "
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
            $where_arr[] = "tss.top_seller_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
        }
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid) person_num,count(l.lessonid) lesson_num "
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
            $where_arr[] = "tss.top_seller_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
        }
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid) person_num,count(l.lessonid) lesson_num "
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
            $where_arr[] = "tss.top_seller_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
        }
        $having = '';
        if($test_lesson_num==-1){
            if($tranfer_per == 1){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)=0";
            }else if($tranfer_per == 2){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>0 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=10";
            }else if($tranfer_per == 3){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>10 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=15";
 
            }else if($tranfer_per == 4){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>15 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=20";

            }else if($tranfer_per == 5){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>20";
            }
        }elseif($test_lesson_num==1){
            if($tranfer_per == 1){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)=0 and count(l.lessonid) >=5";
            }else if($tranfer_per == 2){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>0 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=10 and count(l.lessonid) >=5";
            }else if($tranfer_per == 3){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>10 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=15 and count(l.lessonid) >=5";
 
            }else if($tranfer_per == 4){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>15 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=20 and count(l.lessonid) >=5";

            }else if($tranfer_per == 5){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>20 and count(l.lessonid) >=5";
            }

        }elseif($test_lesson_num==2){
            if($tranfer_per == 1){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)=0 and count(l.lessonid) <5";
            }else if($tranfer_per == 2){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>0 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=10 and count(l.lessonid) <5";
            }else if($tranfer_per == 3){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>10 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=15 and count(l.lessonid) <5";
 
            }else if($tranfer_per == 4){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>15 and round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)<=20 and count(l.lessonid) <5";

            }else if($tranfer_per == 5){
                $having = "round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2)>20 and count(l.lessonid) <5";
            }

        }
        if($having != ''){
            $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order,l.teacherid,t.realname,t.subject,t.train_through_new_time,t.grade_part_ex,t.phone, round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2) as per"
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
           $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid) person_num,count(l.lessonid) lesson_num "
                                  ." ,count(distinct c.userid,c.teacherid,c.subject) have_order,l.teacherid,t.realname,t.subject,t.train_through_new_time,t.grade_part_ex,t.phone, round(100*count(distinct c.userid,c.teacherid,c.subject)/count(distinct l.userid,l.teacherid) ,2) as per"
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
            $where_arr[] = "tss.top_seller_flag=1";
        }elseif($require_type==2){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =1";
        }elseif($require_type==3){
            $where_arr[] = "tss.top_seller_flag=0";
            $where_arr[] = "tq.is_green_flag =0";
        }
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid) person_num,count(l.lessonid) lesson_num "
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
            "lesson_type in (2)",
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
            "select lessonid,lesson_condition  from  %s"
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

    public function get_teacher_full_lesson_total($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,-1],
            ["lesson_start<%u",$end_time,-1],
            "lesson_type in (0,1,3)",
            "t.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select t.teacherid,t.realname,t.phone,"
                                  ." sum(if(confirm_flag!=2 and lesson_del_flag=0,lesson_count,0)) as lesson_total,"
                                  ." sum(lesson_cancel_reason_type=12) as change_class,"
                                  ." sum(if(confirm_flag!=2 and lesson_del_flag=0 and deduct_come_late=1,1,0)) as come_late"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s"
                                  ." group by l.teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
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



    public function get_real_xmpp_server($lessonid ) {
        $lesson_info=$this->field_get_list($lessonid,"courseid,xmpp_server");
        $xmpp_server= $lesson_info["xmpp_server"];
        if(!$xmpp_server) {
            $xmpp_server= $this->task->t_course_order->get_current_server($lesson_info["courseid"]);
        }
        if (!$xmpp_server) { //默认设置到杭州
            $xmpp_server="h_01";
        }
        $row=$this->task->t_xmpp_server_config->get_info_by_server_name($xmpp_server );
        if ($row) {
            $ret=[
                'ip'          => $row["ip"] ,
                'xmpp_port'   => $row["xmpp_port"] ,
                'webrtc_port' => $row["webrtc_port"] ,
                'websocket_port' => $row["websocket_port"] ,
                "region" =>   $row["server_desc"] ,
            ];
            return $ret;
        }else {
            return  [
                'ip'          => '121.43.230.95',
                'xmpp_port'   => '5222',
                'webrtc_port' => '5061',
                'websocket_port' => '20061',
                "region" => "杭州_01",
            ];
        }

    }

}
