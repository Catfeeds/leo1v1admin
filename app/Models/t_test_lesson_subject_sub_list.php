<?php
namespace App\Models;
use \App\Enums as E;
/**
 * @property t_lesson_info  $t_lesson_info
 */

class t_test_lesson_subject_sub_list extends \App\Models\Zgen\z_t_test_lesson_subject_sub_list
{
    public function __construct()
    {
        parent::__construct();
    }

    public function set_lesson_del($adminid ,$lessonid, $fail_greater_4_hour_flag, $test_lesson_fail_flag,$fail_reason ) {
        $this->t_lesson_info->field_update_list($lessonid,[
            "lesson_del_flag" => 1,
        ]);
        $this->field_update_list( $lessonid, [
            "test_lesson_fail_flag"    => $test_lesson_fail_flag,
            "fail_greater_4_hour_flag" => $fail_greater_4_hour_flag,
            "success_flag"             => E\Eset_boolean::V_2 ,
            "fail_reason"              => $fail_reason,
            'confirm_adminid' => $adminid ,
            "confirm_time" => time(NULL),
        ]);

    }
    public function get_count_by_require_id($require_id) {
        $sql=$this->gen_sql_new("select count(*) from %s where require_id=%u"
                                ,self::DB_TABLE_NAME , $require_id );
        return $this->main_get_value($sql);
    }

    public function tongji_get_plan_list($page_num,$start_time,$end_time ,$set_lesson_adminid,
                                         $subject, $grade, $success_flag, $test_lesson_fail_flag, $userid, $require_admin_type,$require_adminid
    )
    {
        $where_arr=[
            "s.is_test_user=0" ,
            ["set_lesson_adminid=%u", $set_lesson_adminid, -1 ],
            ["l.subject=%u",$subject,-1],
            ["l.grade=%u",$grade,-1],
            ["tss.success_flag=%u",$success_flag,-1],
            ["tss.test_lesson_fail_flag=%u",$test_lesson_fail_flag,-1],
            ["l.userid=%u",$userid,-1],
            ["t.require_adminid=%u",$require_adminid,-1],
            ["t.require_admin_type=%u",$require_admin_type,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select set_lesson_adminid , t.require_adminid ,l.lesson_start, l.userid, l.teacherid , l.subject, n.phone, s.nick, l.grade,  tss.success_flag, tss.test_lesson_fail_flag, tss.fail_reason "
            ." from %s  l "
            ."  join   %s  tss on tss.lessonid = l.lessonid   "
            ."  join   %s  tr on tr.require_id = tss.require_id "
            ."  join   %s  t on t.test_lesson_subject_id = tr.test_lesson_subject_id "
            ."  join   %s  s on  s.userid = l.userid "
            ."  join   %s  n on  n.userid = s.userid "
            ."where %s"
            ." order by lesson_start asc "
            ,  t_lesson_info::DB_TABLE_NAME
            ,  self::DB_TABLE_NAME
            ,  t_test_lesson_subject_require::DB_TABLE_NAME
            ,  t_test_lesson_subject::DB_TABLE_NAME
            ,  t_student_info::DB_TABLE_NAME
            ,  t_seller_student_new::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_set_lesson_count_info($start_time,$end_time,$adminid_list=[],$adminid_all=[]) {
        $where_arr=[
            "is_test_user=0" ,
        ];

        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_all);
        $this->where_arr_add_time_range($where_arr,"set_lesson_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select count(*) as set_lesson_count "
            ."from %s tss "
            ." join %s l on l.lessonid=tss.lessonid  "
            ." join %s s on s.userid=l.userid "
            ." join %s tr on tr.require_id=tss.require_id "
            ." join %s t on t.test_lesson_subject_id=tr.test_lesson_subject_id "
            ." where %s ",
            self::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }
    public function get_seller_date_set_lesson_list( $start_time,$end_time,$adminid_list ) {
        $where_arr=[
            "is_test_user=0" ,
        ];
        $time_field_name="set_lesson_time";
        $this->where_arr_add_time_range($where_arr,$time_field_name,$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);
        $sql=$this->gen_sql_new(
            "select from_unixtime($time_field_name,'%%Y-%%m-%%d' ) as date, count(*) as set_lesson_count "
            ." from %s tss  "
            ." join %s l on l.lessonid=tss.lessonid  "
            ." join %s s on s.userid=l.userid "
            ." join %s tr on tr.require_id=tss.require_id "
            ." join %s t on t.test_lesson_subject_id=tr.test_lesson_subject_id "

            ." where %s  group by  from_unixtime($time_field_name ,'%%Y-%%m-%%d' ) order by  date  "
            ,self::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,$where_arr
        );

        $ret_list= $this->main_get_list($sql,function($item ){
            return $item["date"];
        });
        return \App\Helper\Common::gen_date_time_list($start_time,$end_time, $ret_list)  ;
    }

    public function get_dean_teacher_plan_lesson_info($start_time,$end_time){
        $where_arr=[
            ["set_lesson_time >= %u",$start_time,-1],
            ["set_lesson_time <= %u",$end_time,-1]
        ];
        $sql = $this->gen_sql_new("select count(*) all_count,sum(success_flag in(0,1)) success_count,"
                                  ."sum(success_flag = 2) fail_count ,subject "
                                  ." from %s ts join %s l on ts.lessonid = l.lessonid"
                                  ." where %s group by subject",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_dean_teacher_personal_plan_lesson_info($start_time,$end_time){
        $where_arr=[
            ["set_lesson_time >= %u",$start_time,-1],
            ["set_lesson_time <= %u",$end_time,-1]
        ];
        $sql = $this->gen_sql_new("select count(*) all_count,sum(success_flag in(0,1)) success_count,"
                                  ."sum(success_flag = 2) fail_count,set_lesson_adminid adminid,m.account, "
                                  ."sum(if(o.orderid >0,1,0)) have_order,sum(if(o.orderid is null or o.orderid =0,1,0)) no_order  "
                                  ." from %s ts join %s m on ts.set_lesson_adminid=m.uid"
                                  ." left join %s o on (ts.lessonid = o.from_test_lesson_id and order_status >0)  "
                                  ." where %s group by set_lesson_adminid",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }

    public function get_set_lesson_adminid_by_require_id($require_id){
        $sql=$this->gen_sql_new("select set_lesson_adminid from %s where require_id = %u",
                                self::DB_TABLE_NAME,
                                $require_id
        );
        return $this->main_get_value($sql);
    }

    public function get_test_lesson_time_info($start_time,$end_time,$teacherid){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start <= %u",$end_time,-1],
            ["teacherid = %u",$teacherid,-1],
            "success_flag in(0,1)",
            "lesson_status =2"
        ];
        $sql = $this->gen_sql_new("select teacherid,lesson_start from %s tss".
                                  " join %s l on tss.lessonid= l.lessonid".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_teacher_trial_success_list($start,$type=2){
        $where_arr = [
            ["set_lesson_time>%u",$start,0],
            "tl.success_flag<2",
            "l.stu_attend>0",
            "l.tea_attend>0",
        ];
        if($type==2){
            $where_arr[] = "l.teacher_money_type>3 ";
        }elseif($type==3){
            $where_arr[] = "t.teacher_money_type=0 and t.teacher_type=3";
        }
        $sql = $this->gen_sql_new("select l.teacherid,l.userid,l.lessonid,l.lesson_start,c.last_modified_time,t.phone,"
                                  ." tls.require_admin_type"
                                  ." from %s tl "
                                  ." left join %s tr on tl.require_id=tr.require_id"
                                  ." left join %s tls on tr.test_lesson_subject_id=tls.test_lesson_subject_id"
                                  ." left join %s l on tl.lessonid=l.lessonid "
                                  ." left join %s t on l.teacherid=t.teacherid "
                                  ." left join %s c on l.teacherid=c.teacherid and l.subject=c.subject and l.userid=c.userid "
                                  ." where %s "
                                  ." and exists ( "
                                  ." select 1 from %s "
                                  ." where subject=l.subject "
                                  ." and teacherid=l.teacherid "
                                  ." and userid=l.userid "
                                  ." and lesson_type in (0,1,3) "
                                  ." and lesson_status=2"
                                  ." and confirm_flag!=2"
                                  ." ) "
                                  ." and not exists( "
                                  ." select 1 from %s where l.lesson_start<lesson_start "
                                  ." and l.teacherid=teacherid "
                                  ." and l.userid=userid "
                                  ." and lesson_type=2 "
                                  ." and lesson_del_flag=0 "
                                  ." and lesson_status=2 "
                                  ." ) "
                                  ." and tl.lessonid not in ( "
                                  ." select money_info from %s "
                                  ." where teacherid=l.teacherid "
                                  ." and type=2 "
                                  ." ) "
                                  ." group by l.lessonid "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_money_list::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function  tongji_lesson_count_list($start_time,$end_time){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"set_lesson_time",$start_time,$end_time);
        $where_arr[]=" accept_flag =1  ";
        $sql = $this->gen_sql_new(
            "select cur_require_adminid as adminid ,count(*) as value "
            ." from  %s  tts "
            ." join %s tr on tts.require_id = tr.require_id "
            ." join  %s  t on tr.test_lesson_subject_id = t.test_lesson_subject_id  "
            ." where %s  group by  cur_require_adminid order by value desc ",
            self::DB_TABLE_NAME ,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_list($sql);
    }

    public function  get_lesson_count_list_all($start_time,$end_time){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"set_lesson_time",$start_time,$end_time);
        $where_arr[]=" accept_flag =1  ";
        $sql = $this->gen_sql_new(
            "select count(distinct cur_require_adminid) all_count ,count(*) as value "
            ." from  %s  tts "
            ." join %s tr on tts.require_id = tr.require_id "
            ." join  %s  t on tr.test_lesson_subject_id = t.test_lesson_subject_id  "
            ." where %s  group by  cur_require_adminid order by value desc ",
            self::DB_TABLE_NAME ,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_row($sql);
    }

    public function get_test_lessonid_list_by_set_time($start_time,$end_time,$tea_subject=""){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $where_arr[]="success_flag in (0,1)";
        $where_arr[]="lesson_del_flag =0";
        $where_arr[]="l.lesson_status =2";
        $where_arr[]="l.lesson_user_online_status =1";
        $where_arr[]="t.realname not like 'alan' and t.realname not like 'test'";
        if(!empty($tea_subject)){
            $where_arr[]="(t.subject in".$tea_subject." or t.second_subject in".$tea_subject.")";
        }

        $sql = $this->gen_sql_new("select tts.lessonid,l.lesson_start,l.teacherid,l.subject,l.grade,t.realname "
                                  ." from %s tts left join %s l on tts.lessonid = l.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_lesson_info($start,$end,$teacherid){
         $where_arr=[];
         $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start,$end);
        $sql= $this->gen_sql_new("select set_lesson_time,lesson_start,success_flag from %s tss left join %s l on tss.lessonid = l.lessonid where %s and teacherid=%u",self::DB_TABLE_NAME,t_lesson_info::DB_TABLE_NAME,$where_arr,$teacherid);
        return $this->main_get_list($sql);
    }

    public function get_lesson_admin($lessonid){
        $sql = $this->gen_sql_new("select a.account "
                                  ." from %s tls "
                                  ." left join %s tlsr on tls.require_id=tlsr.require_id "
                                  ." left join %s a on tlsr.cur_require_adminid=a.id "
                                  ." where lessonid=%u"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_admin_users::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_value($sql);
    }

    public function get_seller_test_lesson_order_info_new($start_time,$end_time,$require_adminid_list){
        $where_arr=[
            //["tr.cur_require_adminid=%u",$adminid,-1],
            "lesson_user_online_status=1"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $this->where_arr_adminid_in_list($where_arr,"tr.cur_require_adminid", $require_adminid_list );

        $sql = $this->gen_sql_new("select cur_require_adminid,l.grade,l.subject,l.lesson_user_online_status,o.orderid,ss.phone,ss.phone_location,t.stu_test_paper,t.tea_download_paper_time  "
                                  ." from %s tss left join %s l on tss.lessonid = l.lessonid"
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s o on o.from_test_lesson_id = tss.lessonid"
                                  ." left join %s ss on l.userid = ss.userid"
                                  ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_test_lesson_paper_order_info($start_time,$end_time){
        $where_arr=[
            //["tr.cur_require_adminid=%u",$adminid,-1],
            "lesson_user_online_status=1"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new('select cur_require_adminid adminid,sum(if(t.stu_test_paper<>"" and tea_download_paper_time>0,1,0)) have_download_num,sum(if(t.stu_test_paper <> "" and tea_download_paper_time>0 and o.orderid>0,1,0)) have_download_order,sum(if(t.stu_test_paper <> "" and tea_download_paper_time<=0,1,0)) no_download_num,sum(if(t.stu_test_paper <> ""  and tea_download_paper_time<=0 and o.orderid>0,1,0)) no_download_order,sum(if(t.stu_test_paper = "" ,1,0)) no_paper_num,sum(if(t.stu_test_paper = "" and o.orderid>0 ,1,0)) no_paper_order '
                                  ." from %s tss left join %s l on tss.lessonid = l.lessonid"
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s o on o.from_test_lesson_id = tss.lessonid"
                                  ." left join %s ss on l.userid = ss.userid"
                                  ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." where %s group by adminid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }

    public function get_teacher_test_lesson_paper_order_info($start_time,$end_time,$subject,$tea_subject,$qz_flag=0){
        $where_arr=[
            "tss.success_flag <>2",
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status =1",
            ["l.subject = %u",$subject,-1],
            "m.account_role=2",
            "m.del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($qz_flag==1){
            $where_arr[]="mm.account_role=5";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(l.subject in".$tea_subject.")";
            }
        }

        $sql = $this->gen_sql_new('select l.teacherid,tt.realname,sum(if(t.stu_test_paper<>"" and tea_download_paper_time>0,1,0)) have_download_num,sum(if(t.stu_test_paper <> "" and tea_download_paper_time>0 and o.orderid>0,1,0)) have_download_order,sum(if(t.stu_test_paper <> "" and tea_download_paper_time<=0,1,0)) no_download_num,sum(if(t.stu_test_paper <> ""  and tea_download_paper_time<=0 and o.orderid>0,1,0)) no_download_order,sum(if(t.stu_test_paper = "" ,1,0)) no_paper_num,sum(if(t.stu_test_paper = "" and o.orderid>0 ,1,0)) no_paper_order '
                                  ." from %s tss left join %s l on tss.lessonid = l.lessonid"
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s o on o.from_test_lesson_id = tss.lessonid"
                                  ." left join %s ss on l.userid = ss.userid"
                                  ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s m on tr.cur_require_adminid = m.uid"
                                  ." left join %s tt on l.teacherid = tt.teacherid"
                                  ." left join %s mm on tt.phone = mm.phone"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }


    public function get_teat_lesson_transfor_info($start_time,$end_time){
        $where_arr = [
            "accept_adminid > 0",
            "m.account_role = 3",
            "s.is_test_user=0",
            "ll.lesson_del_flag=0",
            "tss.success_flag <>2"
        ];
        $sql = $this->gen_sql_new("select accept_adminid,sum(ll.lessonid >0) tra_count,".
                                  "sum(ll.lessonid >0 and t.require_admin_type =1) tra_count_ass,".
                                  "sum(ll.lessonid >0 and t.require_admin_type =2) tra_count_seller".
                                  " from %s tss join %s ll on tss.lessonid=ll.lessonid ".
                                  " join %s tr on tss.require_id = tr.require_id".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s m on tr.accept_adminid = m.uid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type <>2 and lesson_status =2 and confirm_flag in (0,1) )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on t.userid = s.userid ".
                                  " where %s group by accept_adminid ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["accept_adminid"];
        });
    }


    public function get_teat_lesson_transfor_info_by_adminid($start_time,$end_time,$adminid){
        $where_arr = [
            "accept_adminid =".$adminid,
            "m.account_role = 3",
            "s.is_test_user=0",
            "ll.lesson_del_flag=0",
            "tss.success_flag <>2"
        ];
        $sql = $this->gen_sql_new("select accept_adminid,ll.userid,ll.grade,ll.subject,s.nick,tt.realname tea_name,ll.lesson_start,ll.lessonid".
                                  " from %s tss join %s ll on tss.lessonid=ll.lessonid ".
                                  " join %s tr on tss.require_id = tr.require_id".
                                  " join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id ".
                                  " join %s m on tr.accept_adminid = m.uid".
                                  " join %s l on (ll.teacherid = l.teacherid ".
                                  " and ll.userid = l.userid ".
                                  " and ll.subject = l.subject ".
                                  " and l.lesson_start= ".
                                  " (select min(lesson_start) from %s where teacherid=ll.teacherid and userid=ll.userid and subject = ll.subject and lesson_type <>2 and lesson_status =2 and confirm_flag in (0,1) )and l.lesson_start >= %u and l.lesson_start < %u)".
                                  " join %s s on ll.userid = s.userid ".
                                  " join %s tt on ll.teacherid = tt.teacherid ".
                                  " where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $start_time,
                                  $end_time,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);

    }

    public function get_set_and_lesson_time($time){
        $where_arr = [
            "tss.success_flag <>2",
            "l.lesson_del_flag=0",
            "set_lesson_time>".$time
        ];

        $sql = $this->gen_sql_new("select lesson_start , set_lesson_time"
                                  ." from %s tss join %s l on tss.lessonid = l.lessonid"
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_no_download_list(){
        $where_arr = [
            "tss.success_flag <>2",
            "l.lesson_del_flag=0",
            "t.paper_send_wx_flag=0",
            "t.stu_test_paper<>'' ",
            "t.tea_download_paper_time=0",
            "l.lesson_start<=".(time()+3600),
            "l.lesson_start >".(time()+3300),
        ];
        $sql = $this->gen_sql_new("select l.teacherid,t.test_lesson_subject_id,tt.wx_openid,l.lesson_start,s.nick "
                                  ." from %s tss join %s l on tss.lessonid = l.lessonid"
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." left join %s tt on l.teacherid = tt.teacherid"
                                  ." left join %s s on l.userid = s.userid"
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);


    }


    public function get_homework_and_work_status_info($start_time,$end_time,$tea_subject,$qz_flag){
        $where_arr = [
            "tss.success_flag <>2",
            "l.lesson_del_flag=0",
            ['lesson_start>%u',$start_time,0],
            ['lesson_end<=%u',$end_time,0],
            "confirm_flag<2",
            "lesson_status=2",
            "l.lesson_user_online_status =1",
            // ["l.subject = %u",$subject,-1],
            "m.account_role=2",
            "m.del_flag=0"
        ];
        if($qz_flag==1){
            $where_arr[]="mm.account_role=5";
        }else{
            if(!empty($tea_subject)){
                $where_arr[]="(l.subject in".$tea_subject.")";
            }
        }

        $sql = $this->gen_sql_new('select l.subject,sum(tea_cw_url = "") no_tea_cw,sum(tea_cw_url <> "") have_tea_cw,sum(h.work_status=0) no_homework,sum(h.work_status=1) have_homework,sum(tea_cw_url = "" and o.orderid>0) no_tea_cw_order,sum(tea_cw_url <> "" and o.orderid>0) have_tea_cw_order,sum(h.work_status=0 and o.orderid>0) no_homework_order,sum(h.work_status>=1 and o.orderid>0) have_homework_order'
                                  ." from %s tss left join %s l on tss.lessonid = l.lessonid "
                                  ." left join %s s on l.userid = s.userid "
                                  ." left join %s h on l.lessonid = h.lessonid "
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s o on o.from_test_lesson_id = tss.lessonid"
                                  ." left join %s ss on l.userid = ss.userid"
                                  ." left join %s t on t.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s m on tr.cur_require_adminid = m.uid"
                                  ." left join %s tt on l.teacherid = tt.teacherid"
                                  ." left join %s mm on tt.phone=mm.phone"
                                  ." where %s group by l.subject"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,t_test_lesson_subject::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["subject"];
        });
    }


    public function get_lessonid_by_require_id($require_id){
        $sql = $this->gen_sql_new("select lessonid from %s where require_id = %u",self::DB_TABLE_NAME,$require_id);
        return $this->main_get_list($sql);
    }

    public function get_seller_limit_require_info($start_time,$end_time){
        $where_arr = [
            "limit_require_flag=1",
            "l.lesson_del_flag=0",
            "tr.cur_require_adminid <>349 ",
            "tr.limit_require_send_adminid <>349 ",
        ];
        $this->where_arr_add_time_range($where_arr,"tr.limit_require_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select seller_require_flag,cur_require_adminid,limit_accept_time,limit_require_send_adminid"
                                  ." ,t.realname,m.account seller_account,mm.account acc,o.orderid,l.lesson_user_online_status"
                                  ." ,tr.limit_require_reason"
                                  ." from %s tss left join %s tr on tss.require_id = tr.require_id "
                                  ." left join %s l on tss.lessonid = l.lessonid"
                                  ." left join %s o on (o.contract_type=0 and o.from_test_lesson_id = tss.lessonid)"
                                  ." left join %s t on tr.limit_require_teacherid = t.teacherid"
                                  ." left join %s m on tr.cur_require_adminid = m.uid"
                                  ." left join %s mm on tr.limit_require_send_adminid = mm.uid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr

        );
        return $this->main_get_list($sql);
    }

    public function get_tran_require_info($start_time,$end_time){
        $where_arr=[
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status in (0,1)",
            "l.lesson_del_flag=0",
            "tr.origin like '%%转介绍%%'",
            "m.account_role=1",
            "m.del_flag=0",
            "tr.accept_flag <>2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(*) num,tr.cur_require_adminid,sum(if(o.orderid>0,1,0)) order_num,sum(if(o.orderid>0,o.price,0)) order_money "
                                  ." from %s tss left join %s l on tss.lessonid = l.lessonid"
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s m on tr.cur_require_adminid = m.uid"
                                  ." left join %s o on o.from_test_lesson_id = tss.lessonid "
                                  ." where %s group by tr.cur_require_adminid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["cur_require_adminid"];
        });
    }

    public function get_kk_require_info($start_time,$end_time,$str="l.lesson_start"){
        $where_arr=[
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status in (0,1)",
            "l.lesson_del_flag=0",
            "tr.origin not like '%%转介绍%%'",
            "m.account_role=1",
            "m.del_flag=0",
            "tr.accept_flag <>2",
            "t.ass_test_lesson_type =1"
        ];
        $this->where_arr_add_time_range($where_arr,$str,$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(*) num,tr.cur_require_adminid,sum(if(c.courseid>0,1,0)) succ_num,sum(if(tr.test_lesson_order_fail_flag>0,1,0)) fail_num"
                                  ." from %s tss left join %s l on tss.lessonid = l.lessonid"
                                  ." left join %s tr on tss.require_id = tr.require_id"
                                  ." left join %s t on tr.test_lesson_subject_id = t.test_lesson_subject_id"
                                  ." left join %s m on tr.cur_require_adminid = m.uid"
                                  ." left join %s c on c.ass_from_test_lesson_id = tss.lessonid "
                                  ." where %s group by tr.cur_require_adminid",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["cur_require_adminid"];
        });
    }

    public function get_require_id($lessonid) {
        $sql=$this->gen_sql_new("select require_id from %s  where  lessonid='%s' ",
                                self::DB_TABLE_NAME,
                                $lessonid
        );

        return $this->main_get_value($sql);
    }

    public function get_set_lesson_adminid_by_lessonid($lessonid){
        $where_arr = [
            ['lessonid=%d',$lessonid,0],
        ];
        $sql = $this->gen_sql_new(
            " select set_lesson_adminid "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_row($sql);
    }


}