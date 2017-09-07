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
            "l.del_flag=0",
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
            "l.lesson_user_online_status <2",
            "l.lesson_type in =2",
            "l.lesson_status>0",
            "t.is_test_user=0",
            "tss.top_seller_flag=1",
            ["l.subject = %u",$subject,-1],
            ["tr.teacherid = %u",$teacherid,-1],
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
        $sql = $this->gen_sql_new("select tr.teacherid,t.realname,l.lessonid,l.lesson_start,l.subject,t.grade_start,t.grade_end,t.grade_part_ex,tr.id,s.nick,tr.acc,tr.record_info,tr.add_time,l.grade,tr.lesson_invalid_flag,l.userid "
                                  ." from %s tr left join %s t on tr.teacherid = t.teacherid"
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s s on l.userid=s.userid"
                                  ." where %s and tr.type=1 and tr.lesson_style=4",
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }


}
