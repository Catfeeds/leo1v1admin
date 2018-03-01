<?php

namespace App\Models;
use App\Models\Zgen as Z;
use \App\Models as M;
use \App\Enums as E;
/**
 * @property t_student_info  $t_student_info
 * @property t_homework_info  $t_homework_info
 * @property t_course_order  $t_course_order
 * @property t_parent_child  $t_parent_child
 */
class t_lesson_info_b2 extends \App\Models\Zgen\z_t_lesson_info
{
    public function lesson_common_where_arr($others_arr=[]) {
        $others_arr[] ="lesson_del_flag=0" ;
        $others_arr[] ="confirm_flag!=2" ;
        return $others_arr;
    }

    public function get_test_lesson_first_list(
        $page_num,$order_by_str,$start_time,$end_time,$require_adminid_list,$lesson_user_online_status,$test_assess_flag
    ){
        $where_arr=[
            "s.is_test_user=0" ,
            "l.lesson_del_flag=0" ,
            "l.lesson_type=2" ,
            "l.lesson_start ",
        ];
        if($test_assess_flag == E\Etest_assess_flag::V_2){
            $where_arr[] = 'tts.assess_adminid = 0';
        }elseif($test_assess_flag == E\Etest_assess_flag::V_1){
            $where_arr[] = 'tts.assess_adminid <> 0';
        }
        $where_arr[] = $this->where_get_in_str("tr.cur_require_adminid",$require_adminid_list);
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $this->where_arr_add_int_field($where_arr,"lesson_user_online_status", $lesson_user_online_status);

        $sql= $this->gen_sql_new(
            ["select tts.assess,cur_require_adminid ,s.userid, s.nick, n.phone, l.lessonid, l.teacherid,  l.lesson_start,l.lesson_end ,min(tq.start_time)  tq_call_time ,  (min(tq.start_time) -l.lesson_start ) as  duration ,price , max(tq.start_time)  last_tq_call_time , o.order_time, count(tq.start_time ) tq_call_count , sum(tq.duration ) as tq_call_all_time , n.phone , l.lesson_user_online_status ",
             " from %s l  ",
             " left join %s  s on s.userid=l.userid ",
             " left join %s  n on n.userid=l.userid ",
             " left join %s  tts on tts.lessonid=l.lessonid ",
             " left join %s  tr on tr.require_id=tts.require_id ",
             " left join %s  tq on (n.phone=tq.phone and tq.start_time > l.lesson_end )",
             " left join %s  o on ( o.from_test_lesson_id =l.lessonid  and  o.contract_status in (1,2) )",
             " where %s group by l.lessonid  $order_by_str ",
            ]
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_tq_call_info::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            , $where_arr );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }


    public function set_lesson_upload_info($draw, $audio, $real_end_time,
                                           $courseid, $lesson_num , $pcm_file_all_size,$pcm_file_count )
    {
        $sql = $this->gen_sql_new(
            "update %s  set draw = '%s',audio = '%s', lesson_upload_time = %u, real_end_time = %u,  pcm_file_all_size=%d  ,pcm_file_count=%d " .
            " where courseid = %u and lesson_num= %u",
            self::DB_TABLE_NAME , $draw , $audio ,
            time(NULL), $real_end_time,
            $pcm_file_all_size,
            $pcm_file_count,
            $courseid, $lesson_num  );
        return $this->main_update($sql);
    }

    public function get_lessonid_by_courseid_num ($courseid, $lesson_num )
    {
        $sql = $this->gen_sql_new(
            " select lessonid from %s  " .
            " where courseid = %u and lesson_num= %u",
            self::DB_TABLE_NAME ,
            $courseid, $lesson_num  );
        return $this->main_get_value($sql);
    }
    public function get_lesson_list_for_set_online_user_status( $start_time, $end_time, $always_reset  ) {
        $now = time(NULL);
        $where_arr=[
            "lesson_status >=2 ",
            "lesson_end  <  $now  ",
            "lesson_del_flag = 0"
        ];
        if (!$always_reset) {
            $where_arr[]=" lesson_user_online_status =0 ";
        }

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select lessonid ,userid, teacherid,lesson_start, lesson_type from %s ".
            " where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }
    public function get_list_by_parent_id( $parentid,$lessonid=-1,$type_str) {
        $check_lesson_time=time(NULL)-90*86400;
        $now = time() + 14*86400;
        $where_arr=[
            ["pc.parentid = %u", $parentid, -1 ],
            ["l.lessonid= %u", $lessonid, -1 ],
            ["lesson_type in (%s)",$type_str,-1],
            "lesson_del_flag=0",
            "lesson_start>$check_lesson_time", //试听
            "lesson_start<$now", //试听
        ];

        $sql = $this->gen_sql_new(
            "select  l.tea_rate_time, tsc.id as scoreid , tls.test_lesson_subject_id,tls.stu_lesson_pic,l.lessonid,"
            ." lesson_start,lesson_end,l.teacherid,l.userid,l.subject,l.grade,"
            ." ass_comment_audit,tl.level as parent_report_level,lesson_status, tss.parent_confirm_time, "
            ." lesson_type,lesson_num, tlm.parent_modify_time"
            ." from %s l "
            ." join %s pc on l.userid = pc.userid "
            ." left join %s tl on  (tl.lessonid = l.lessonid  and label_origin =1 ) "
            ." left join %s tss  on  tss.lessonid= l.lessonid "
            ." left join %s tsr  on  tsr.require_id = tss.require_id "
            ." left join %s tls  on  tls.test_lesson_subject_id= tsr.test_lesson_subject_id "
            ." left join %s tlm on tlm.lessonid=l.lessonid"
            ." left join %s tsc on tsc.userid=l.userid"
            ." where %s group by l.lessonid order by lesson_start desc  "
            ,self::DB_TABLE_NAME
            ,t_parent_child::DB_TABLE_NAME
            ,t_teacher_label::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,t_lesson_time_modify::DB_TABLE_NAME
            ,t_student_score_info::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function check_lesson_parentid( $lessonid, $parentid) {
        $where_arr=[
            ["pc.parentid = %u", $parentid, -1 ],
            ["l.lessonid= %u", $lessonid, -1 ],
            "lesson_type=2", //试听
        ];

        $sql= $this->gen_sql_new(
            "select l.lessonid, lesson_start, lesson_end, l.teacherid, l.userid, l.subject, l.grade  ,ass_comment_audit ,tl.level  as parent_report_level "
            .", lesson_type, lesson_num   "
            . " from %s l "
            . " join %s pc on  l.userid = pc.userid    "
            . " join %s tl on  (tl.lessonid = l.lessonid  and label_origin =1 ) "
            . "where %s  order by lesson_start desc "
            ,self::DB_TABLE_NAME
            , t_parent_child::DB_TABLE_NAME
            , t_teacher_label::DB_TABLE_NAME
            , $where_arr );
        $result = $this->main_get_list($sql);

        if (!empty($result)) {
            return true;
        } else {
            return false;
        }


    }

    public function get_finish_lessons()
    {
        $now=time(NULL);
        $where_arr=[];
        if (\App\Helper\Utils::check_env_is_test()) {
            $where_arr[]= "l.record_audio_server1<>''" ;
        }
        $sql = $this->gen_sql_new(
            "select lessonid, c.courseid,lesson_num,lesson_type,real_begin_time,real_end_time, l.teacherid , current_server, lesson_start, lesson_end ,server_type"
            . " from %s l"
            . " join %s c  on l.courseid = c.courseid  "
            . " join %s s  on l.userid = s.userid "
            ." where lesson_upload_time = 0 and lesson_status = 2 and real_begin_time != 0 and lesson_type != 4001 and   confirm_flag<2  and"
            . " ( lesson_type >=1000  or s.is_test_user= 0 )" //
            . " and "
            . "  lesson_start > %u  and lesson_start <%u and  lesson_end<%u and lesson_del_flag=0 and %s order by   gen_video_grade desc,   lesson_start asc ",
            self::DB_TABLE_NAME ,
            t_course_order::DB_TABLE_NAME ,
            t_student_info::DB_TABLE_NAME,
            $now - 86400*3, $now+3600*5 , $now, $where_arr  );
        return $this->main_get_list($sql);
    }

    public function get_teacher_lesson_info(  $teacherid, $start_time,$end_time ) {
        $sql=$this->gen_sql("select l.userid,l.lessonid,l.lesson_start,lesson_end,l.userid,l.lesson_type,s.nick "
                            ." from %s l "
                            ." left join %s s on s.userid = l.userid "
                            ." where teacherid=%u "
                            ." and lesson_start>=%s "
                            ." and lesson_start<=%s "
                            ." and lesson_status<=2 "
                            ." and confirm_flag<2"
                            ." and lesson_del_flag=0"
                            ." order by lesson_start asc ",
                            self::DB_TABLE_NAME,
                            t_student_info::DB_TABLE_NAME,
                            $teacherid, $start_time,$end_time );
        return $this->main_get_list($sql);
    }
    public function get_teacher_lesson_list_new($page_num,$teacherid,$start_time,$end_time,$lesson_type,$lessonid=-1){
        if ($lessonid==-1) {
            $where_arr=[
                ["lesson_start>=%d",$start_time, -1 ] ,
                ["lesson_start<=%d",$end_time, -1 ] ,
                ["l.teacherid = %u",$teacherid, -1 ] ,
            ];
            $this->where_arr_add_int_or_idlist($where_arr,"l.lesson_type",$lesson_type);
        }else{
            $where_arr=[
                ["l.lessonid=%d",$lessonid, -1 ] ,
            ];
        }
        $sql =$this->gen_sql_new("select price , test_lesson_order_fail_flag, test_lesson_order_fail_set_time ,s.phone, test_lesson_order_fail_desc ,  l.lessonid,l.lesson_type,lesson_start,lesson_end,lesson_intro,l.grade,l.subject,"
                                 ." l.lesson_num,l.userid,lesson_name,lesson_status, ass_comment_audit,l.userid,"
                                 ." h.work_status as homework_status,stu_cw_status as stu_status,"
                                 ." tea_cw_status as tea_status,editionid,"
                                 ." h.finish_url,h.check_url,l.tea_cw_url,l.stu_cw_url,h.issue_url,h.pdf_question_count"
                                 ." from %s l "
                                 ." left join %s h on l.lessonid=h.lessonid "
                                 ." left join %s seller on l.lessonid=seller.st_arrange_lessonid"
                                 ." left join %s s on l.userid=s.userid"
                                 ." left join %s o on o.from_test_lesson_id =l.lessonid "
                                 ." left join %s tss on tss.lessonid =l.lessonid "
                                 ." left join %s tr on tr.require_id =tss.require_id"
                                 ." where %s and lesson_del_flag=0 and l.confirm_flag in(0,1) and if(l.lesson_type=2,tss.success_flag in (0,1),true) "
                                 ." order by lesson_start ",
                                 self::DB_TABLE_NAME ,
                                 t_homework_info::DB_TABLE_NAME ,
                                 t_seller_student_info::DB_TABLE_NAME ,
                                 t_student_info::DB_TABLE_NAME ,
                                 t_order_info::DB_TABLE_NAME ,
                                 t_test_lesson_subject_sub_list::DB_TABLE_NAME ,
                                 t_test_lesson_subject_require::DB_TABLE_NAME ,
                                 $where_arr);
        return $this->main_get_list_as_page($sql);
    }

    public function get_info_for_monitor($lessonid)
    {
        $sql=$this->gen_sql("select cur_require_adminid,l.lessonid,courseid, l.userid,l.teacherid,l.subject,l.grade,"
                            ." s.nick as stu_nick, s.phone stu_phone, s.user_agent stu_user_agent,"
                            ." t.nick tea_nick, t.phone tea_phone,  t.user_agent tea_user_agent, "
                            ." tea_situation, stu_situation from %s l "
                            ." left join %s s on l.userid = s.userid "
                            ." left join %s t on l.teacherid = t.teacherid "
                            ." left join %s tss on l.lessonid = tss.lessonid "
                            ." left join %s tr on tr.require_id = tss.require_id "
                            ." where l.lessonid=%u",
                            self::DB_TABLE_NAME,
                            t_student_info::DB_TABLE_NAME,
                            t_teacher_info::DB_TABLE_NAME,
                            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                            t_test_lesson_subject_require::DB_TABLE_NAME,
                            $lessonid);

        return $this->main_get_row($sql);
    }


    public function get_tea_imgs_show($lessonid){
        $sql = $this->gen_sql_new("select tea_cw_pic from %s where lessonid=%d",
                                  self::DB_TABLE_NAME,
                                  $lessonid
        );
        // return $sql;
        return $this->main_get_value($sql);
    }


    public function get_lesson_tea_num_new($tea_arr,$num){
        $where_arr =[
            "l.lesson_type=2",
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status in (0,1)",
            "tss.success_flag in(0,1)"
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $tea_arr);

        $sql = $this->gen_sql_new("select  l.teacherid,count(distinct tss.lessonid) num "
                                  ." from %s l join %s tss on l.lessonid = tss.lessonid"
                                  ." where %s group by l.teacherid having(num >=%u)",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr,
                                  $num
        );
        $arr =  $this->main_get_list($sql);
        $list=[];
        foreach($arr as $val){
            $list[] = $val["teacherid"];
        }
        return $list;
    }

    public function get_stu_last_lesson_time($userid){
        $sql = $this->gen_sql_new("select max(lesson_end) from %s "
                                  ."where lesson_type <>2 and confirm_flag in (0,1) and lesson_del_flag=0 and lesson_status=2 and userid = %u",
                                  self::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_first_test_lesson_time_by_teacherid($tea_arr){
        $where_arr =[
            "l.lesson_type=2",
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status in (0,1)",
            "tss.success_flag in(0,1)"
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select AVG(l.lesson_start) lesson_time,AVG(tl.confirm_time) confirm_time "
                                  ." from %s l join %s tss on l.lessonid = tss.lessonid "
                                  ." join %s t on l.teacherid = t.teacherid"
                                  ." join %s tl on t.phone = tl.phone and tl.status=1"
                                  ." where %s and l.lesson_start = (select min(lesson_start) from %s ll join %s ttss where ll.teacherid = l.teacherid and ll.lesson_type=2 and ll.lesson_del_flag=0 and ll.lesson_user_online_status in (0,1) and ttss.success_flag in(0,1)) ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_info::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME
        );
        return $this->main_get_row($sql);

    }

    public function get_fifth_test_lesson_time_by_teacherid($tea_arr){
        $where_arr =[
            "l.lesson_type=2",
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status in (0,1)"
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $tea_arr);
        $sql = $this->gen_sql_new("select AVG(l.lesson_start) lesson_time,AVG(ll.lesson_start) confirm_time "
                                  ." from %s l join %s ll on l.teacherid = ll.teacherid "
                                  ." where %s and l.lesson_start = (select min(lesson_start) from %s  where teacherid = l.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status in (0,1) ) and ll.lesson_start = (select lesson_start from %s where teacherid = l.teacherid and lesson_type=2 and lesson_del_flag=0 and lesson_user_online_status in (0,1) order by lesson_start limit 4,1) ",
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_row($sql);

    }

    public function get_lesson_user_online_status_invalid($start_time, $end_time){
        $where_arr =[
            ["lesson_start>%d",$start_time],
            ["lesson_end<%d",$end_time],
            "lesson_user_online_status <> 1",
            "lesson_del_flag = 0"
        ];

        $sql=$this->gen_sql_new("select lessonid, courseid,userid "
                                ." from %s where %s ",
                                self::DB_TABLE_NAME,
                                $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_lesson_url($lessonid)
    {
        $sql = $this->gen_sql_new(" select draw, audio, real_begin_time, lesson_end ".
                                  " from %s ".
                                  " where lessonid=$lessonid",
                                  self::DB_TABLE_NAME
        );


        return $this->main_get_list($sql);
    }

    public function check_lesson_on($teacherid){
        $sql = $this->gen_sql_new("select 1 from %s where teacherid = %u and lesson_status=1 and lesson_del_flag=0",
                                  self::DB_TABLE_NAME,
                                  $teacherid
        );
        return $this->main_get_value($sql);
    }

    public function get_qz_tea_lesson_info($start_time,$end_time,$teacherid=-1){
        $where_arr=[
            "m.account_role in (4,5)",
            "m.del_flag=0",
            "l.lesson_del_flag=0",
            "l.confirm_flag in (0,1)",
            ["l.teacherid=%u",$teacherid,-1],
            "(tss.success_flag is null or tss.success_flag in (0,1))"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select lesson_type,lesson_count,tss.success_flag,m.uid,m.account_role,"
                                  ."l.train_type,l.grade,l.subject,l.lesson_start,l.lesson_end,s.nick,l.lessonid "
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." left join %s s on l.userid = s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_qz_tea_lesson_info_b2($start_time,$end_time){
        $where_arr=[
            "m.account_role=5",
            "m.del_flag=0",
            "l.lesson_del_flag=0",
            "l.confirm_flag in (0,1)",
            "(tss.success_flag is null or tss.success_flag in (0,1))"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select lesson_type,lesson_count,tss.success_flag,m.uid,m.account_role,"
                                  ."l.train_type,m.fulltime_teacher_type "
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_off_time_lesson_info($lesson_start,$lesson_end){
        $where_arr=[
            "m.account_role=5",
            "m.del_flag=0",
            "l.lesson_del_flag=0",
            "l.confirm_flag in (0,1)",
            "(tss.success_flag is null or tss.success_flag in (0,1))",
            "l.lesson_type<1000",
            "l.lesson_start <".$lesson_start,
            "l.lesson_start >".strtotime(date("Y-m-d",$lesson_start)),
            // "l.lesson_end >".$lesson_end
        ];
        $where_arr[] = "if(l.lesson_type=2,l.lesson_end>".($lesson_end-1200).",l.lesson_end>".$lesson_end.")";
        $sql = $this->gen_sql_new("select lesson_type,lesson_count,tss.success_flag,m.uid,l.lesson_start,l.lesson_end,l.teacherid "
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_delay_work_time_lesson_info($lesson_start,$lesson_end){
        $where_arr=[
            "m.account_role=5",
            "m.del_flag=0",
            "l.lesson_del_flag=0",
            "l.confirm_flag in (0,1)",
            "(tss.success_flag is null or tss.success_flag in (0,1))",
            "l.lesson_type<1000",
            "l.lesson_start >".$lesson_start,
            "l.lesson_start <=".$lesson_end
        ];
        $sql = $this->gen_sql_new("select lesson_type,lesson_count,tss.success_flag,m.uid,l.lesson_start,l.lesson_end,l.teacherid "
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s order by l.lesson_start",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }
    public function check_off_time_lesson_start($teacherid,$lesson_end,$lesson_start){
        $where_arr=[
            "m.account_role=5",
            "m.del_flag=0",
            "l.lesson_del_flag=0",
            "l.confirm_flag in (0,1)",
            "(tss.success_flag is null or tss.success_flag in (0,1))",
            "l.teacherid=".$teacherid,
            //  "l.lesson_end >".$lesson_end,
            "l.lesson_start <".$lesson_start,
            "l.lesson_start >".strtotime(date("Y-m-d",$lesson_start)),
            "l.lesson_type<1000",
        ];
        $where_arr[] = "if(l.lesson_type=2,l.lesson_end>".($lesson_end-1200).",l.lesson_end>".$lesson_end.")";
        $sql = $this->gen_sql_new("select max(l.lesson_start)  "
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }
    public function check_off_time_lesson_end($teacherid,$lesson_end,$lesson_start){
        $where_arr=[
            "m.account_role=5",
            "m.del_flag=0",
            "l.lesson_del_flag=0",
            "l.confirm_flag in (0,1)",
            "(tss.success_flag is null or tss.success_flag in (0,1))",
            "l.teacherid=".$teacherid,
            //  "l.lesson_end >".$lesson_end,
            "l.lesson_start <".$lesson_start,
            "l.lesson_start >".strtotime(date("Y-m-d",$lesson_start)),
            "l.lesson_type<1000",
        ];
        $where_arr[] = "if(l.lesson_type=2,l.lesson_end>".($lesson_end-1200).",l.lesson_end>".$lesson_end.")";
        $sql = $this->gen_sql_new("select l.lesson_end,l.lesson_type  "
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s order by l.lesson_end desc limit 1",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }



    public function get_ass_stu_lesson_list($start_time,$end_time) {
        $sql=$this->gen_sql_new("select m.uid,sum(lesson_count) as lesson_count,count(*) as count, count(distinct l.userid ) as user_count "
                                ."from  %s  l left join  %s a on l.assistantid = a.assistantid"
                                ." left join %s m on  a.phone = m.phone"
                                ." where lesson_start >=%s and lesson_start<%s  and lesson_status =2 and confirm_flag not in (2,3)  and lesson_type in (0,1,3)"
                                . " and lesson_del_flag=0  "
                                ." group by m.uid ",
                                self::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $start_time,$end_time
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_no_lesson_tongji($start_time,$end_time){
        $where_arr=[
            "lesson_type in (0,1,3)",
            "s.is_test_user = 0",
            "lesson_del_flag = 0",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);

        $sql=$this->gen_sql_new("select m.uid,sum(if(lesson_cancel_reason_type in (3,4),lesson_count,0)) other_count,sum(if(lesson_cancel_reason_type=11,lesson_count,0)) student_leave_count,sum(if(lesson_cancel_reason_type=12,lesson_count,0)) teacher_leave_count"
                                ." from %s l "
                                ." join %s s on l.userid=s.userid "
                                ." left join  %s  a on a.assistantid= l.assistantid"
                                ." left join  %s  m on a.phone= m.phone"
                                ." where  %s group by m.uid "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_assistant_info::DB_TABLE_NAME
                                ,t_manager_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function transfer_teacher_lesson($old_teacherid,$new_teacherid,$teacher_money_type,$level,$lesson_start){
        $where_arr = [
            ["teacherid=%u",$old_teacherid,0],
            ["lesson_start>%u",$lesson_start,0],
        ];
        $sql = $this->gen_sql_new("update %s set teacherid=%u,teacher_money_type=%u,level=%u"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$new_teacherid
                                  ,$teacher_money_type
                                  ,$level
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_teacher_lesson_num($teacherid,$lesson_start,$lesson_status=0){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["lesson_status=%u",$lesson_status,-1],
            ["lesson_start>%u",$lesson_start,0],
        ];
        $sql = $this->gen_sql_new("select count(1)"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr){
        $where_arr=[
            "confirm_flag in (0,1,3)",
            "lesson_del_flag=0",
            "lesson_type <>2",
            "lesson_status=2"
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $this->where_arr_teacherid($where_arr,"teacherid", $qz_tea_arr );
        $sql = $this->gen_sql_new("select sum(lesson_count) lesson_all,teacherid"
                                  ." from %s where %s group by teacherid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_teacher_lesson_grade_count($start_time,$end_time,$teacherid,$grade){
        $where_arr=[
            "confirm_flag in (0,1)",
            "lesson_del_flag=0",
            "lesson_type <>2",
            "lesson_status=2",
            ["teacherid = %u",$teacherid,-1]
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        if($grade==1){
            $where_arr[] = "grade>=100 and grade<106";
        }elseif($grade==2){
            $where_arr[] = "grade>=106 and grade<300";
        }else{
            $where_arr[] = "grade>=300 and grade<400";
        }
        $sql = $this->gen_sql_new("select count(distinct userid) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_tea_stu_num_list($qz_tea_arr,$start_time,$end_time,$flag=true){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "s.type <> 1",
            "l.lesson_type<>2",
            "l.lesson_type<1000",
            "l.lesson_del_flag=0",
            "l.confirm_flag <2"
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $qz_tea_arr,$flag );
        $sql = $this->gen_sql_new("select count(distinct l.userid) num,sum(l.lesson_count) lesson_all,l.teacherid "
                                  ." from %s l left join %s s on l.userid = s.userid"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });

    }

    public function get_tea_stu_num_list_detail($qz_tea_arr,$start_time,$end_time){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "s.type <> 1",
            "l.lesson_type<>2",
            "l.lesson_type<1000",
            "l.lesson_del_flag=0",
            "l.confirm_flag <2"
        ];
        $this->where_arr_teacherid($where_arr,"l.teacherid", $qz_tea_arr );
        $sql = $this->gen_sql_new("select sum(l.lesson_count) lesson_all,l.teacherid "
                                  ." from %s l left join %s s on l.userid = s.userid"
                                  ." where %s group by l.teacherid,l.userid",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }


    public function get_tea_stu_num_list_personal($teacherid,$start_time,$end_time){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            ["l.teacherid = %u",$teacherid,-1],
            "s.type <> 1",
            "l.lesson_type<>2",
            "l.lesson_del_flag=0",
            "l.confirm_flag <2"
        ];
        $sql = $this->gen_sql_new("select count(distinct l.userid) num,sum(l.lesson_count) lesson_all,l.teacherid "
                                  ." from %s l left join %s s on l.userid = s.userid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }


    public function get_train_list($start_time,$end_time,$type){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            ["train_type=%u",$type,0],
            "lesson_type=1100",
        ];
        $sql = $this->gen_sql_new("select lessonid,lesson_sub_type,lesson_type"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_test_lesson_order_info($teacherid, $start_time,$end_time){
        $where_arr = [
            ["lesson_start >= %u",$start_time,-1],
            ["lesson_start < %u",$end_time,-1],
            ["l.teacherid = %u",$teacherid,-1],
            "tss.seller_require_flag =0",
            "(tss.success_flag in (0,1) and l.lesson_user_online_status =1)",
            "lesson_type = 2",
            "lesson_del_flag = 0",
        ];
        $sql = $this->gen_sql_new("select count(distinct l.userid,l.teacherid,l.subject) person_num,count(l.lessonid) lesson_num,l.teacherid "
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
                                  ." where %s " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_test_lesson_num_two_info($start_time,$end_time){
        $where_arr=[
            "lesson_type=2",
            "lesson_del_flag=0",
            "lesson_user_online_status=1",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select userid,count(*) num  "
                                  ."from %s "
                                  ."where %s group by userid having(num>=2)",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_num_two_list($start_time,$end_time,$arr){
        $where_arr=[
            "l.lesson_type=2",
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status=1",
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $where_arr[]=$this->where_get_in_str("l.userid",$arr);

        $sql = $this->gen_sql_new("select l.userid,s.nick,s.phone,l.grade,l.subject,t.realname,l.lesson_start "
                                  ."from %s l left join %s s on l.userid= s.userid "
                                  ." left join %s t on t.teacherid = l.teacherid "
                                  ."where %s order by l.userid ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_normal_lesson_user_list($start_time,$end_time){
        $where_arr=[
            "lesson_type <> 2",
            "lesson_del_flag=0",
            "confirm_flag in (0,1)",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select distinct userid "
                                  ."from %s "
                                  ."where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["userid"];
        });

    }

    public function get_seller_week_lesson($start_time,$end_time ,$adminid_list){
        $where_arr=[
            ["l.lesson_type=%u",2,-1],
            ["l.lesson_del_flag=%u",0,-1],
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start < %u",$end_time,-1],
        ];
        if($adminid_list){
             $this->where_arr_add_int_or_idlist($where_arr,"ls.require_adminid",$adminid_list);
        }

        $sql = $this->gen_sql_new(" select l.lessonid,l.lesson_start,l.userid,l.teacherid,l.lesson_start,l.lesson_end,"
                                  ."lsl.call_before_time,lsl.call_end_time,m.account,"
                                  ."ls.require_adminid adminid,ls.require_adminid,"
                                  ."t.nick tea_nick,"
                                  ."s.nick as stu_nick,s.phone stu_phone"
                                  ." from %s l "
                                  ." left join %s lsl on lsl.lessonid=l.lessonid "
                                  ." left join %s lsr on lsr.require_id=lsl.require_id "
                                  ." left join %s ls on ls.test_lesson_subject_id=lsr.test_lesson_subject_id "
                                  ." left join %s m on m.uid=ls.require_adminid "
                                  ." left join %s t on t.teacherid=l.teacherid "
                                  ." left join %s s on s.userid=l.userid "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,//l
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,//lsl
                                  t_test_lesson_subject_require::DB_TABLE_NAME,//lsr
                                  t_test_lesson_subject::DB_TABLE_NAME,//ls
                                  t_manager_info::DB_TABLE_NAME,//m
                                  t_teacher_info::DB_TABLE_NAME,//t
                                  t_student_info::DB_TABLE_NAME,//s
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_check_lesson($lesson_start){
        $where_arr=[
            ["l.lesson_del_flag=%d",0],
        ];
        $this->where_arr_add_int_or_idlist($where_arr,'l.lesson_start',$lesson_start);

        $sql = $this->gen_sql_new(" select l.lessonid,l.lesson_type,l.lesson_start,l.lesson_end,l.tea_attend,"
                                  ." l.stu_attend,l.teacherid,l.assistantid,t.phone,t.wx_openid teacher_openid,t.nick teacher_nick,"
                                  ." s.require_adminid as cc_id,st.nick student_nick"
                                  ." from %s l "
                                  ." left join %s tss on tss.lessonid=l.lessonid "
                                  ." left join %s sr on sr.require_id=tss.require_id "
                                  ." left join %s s on s.test_lesson_subject_id=sr.test_lesson_subject_id "
                                  ." left join %s t on t.teacherid=l.teacherid "
                                  ." left join %s st on st.userid=l.userid "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_check_lesson_logout($time){
        $where_arr=[
            ["l.lesson_del_flag=%u",0,-1],
            ["l.lesson_end > %u",$time,-1],
            ["l.lesson_start <= %u",$time,-1],
        ];
        $sql = $this->gen_sql_new(" select l.lessonid,l.lesson_type,l.lesson_start,l.lesson_end,l.tea_attend,l.stu_attend,l.teacherid,l.assistantid,t.phone,m.account as teacher_account "
                                  ." from %s l "
                                  ." left join %s t on t.teacherid=l.teacherid "
                                  ." left join %s m on m.phone=t.phone "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_check_lesson_new(){
        $where_arr=[
            ["l.lesson_del_flag=%u",0,-1],
            ["l.lesson_start > %u",$min_time,-1],
            ["l.lesson_start <= %u",$max_time,-1],
        ];
        $sql = $this->gen_sql_new(" select l.lessonid,l.lesson_type,l.lesson_start,l.lesson_end,l.tea_attend,l.stu_attend,l.teacherid,l.assistantid,t.phone,m.account as teacher_account "
                                  ." from %s l "
                                  ." left join %s t on t.teacherid=l.teacherid "
                                  ." left join %s m on m.phone=t.phone "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }



    public function get_research_teacher_lesson($start_time,$end_time,$tea_arr){
       $where_arr=[
           "(tss.success_flag in (0,1) or tss.success_flag is null)" ,
           "l.lesson_del_flag=0" ,
           "l.confirm_flag in (0,1)" ,
       ];
        $where_arr[]=$this->where_get_in_str("l.teacherid",$tea_arr);
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,l.lesson_start,l.lesson_end,t.realname,l.lesson_type "
                                  ." from %s l left join %s tss on l.lessonid = tss.lessonid "
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s order by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function train_lecture_lesson(
        $page_num,$start_time,$end_time,$lesson_status,$teacherid,$subject,$grade,$check_status,$train_teacherid,$lessonid=-1,$res_teacherid=-1,$have_wx=-1,$lecture_status=-1,$opt_date_str=-1,$train_email_flag=-1,$full_time=-1,$id_train_through_new_time=-1,$id_train_through_new=-1,$accept_adminid=-1,$identity=-1,$recommend_teacherid_phone=-1,$subject_eg=-1,$grade_eg=-1
    ){
        $where_arr = [
            ["l.lesson_status=%u",$lesson_status,-1],
            ["l.subject=%u",$subject,-1],
            ["l.grade=%u",$grade,-1],
            ["l.teacherid=%u",$teacherid,-1],
            ["l.teacherid=%u",$res_teacherid,-1],
            ["tl.userid=%u",$train_teacherid,-1],
            ["l.train_email_flag=%u",$train_email_flag,-1],
            ["ap.full_time=%u",$full_time,-1],
            ["ap.accept_adminid=%u",$accept_adminid,-1],
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=5",
            ['ap.reference=%u',$recommend_teacherid_phone,-1],
            ['t.identity=%u',$identity,-1]
        ];
        if($check_status==-1){
            $where_arr[] = "trial_train_status is null";
        }else{
            $where_arr[] = ["trial_train_status=%u",$check_status,-2];
        }

        if($id_train_through_new_time == -1){
        }elseif($id_train_through_new_time == 0){
            $where_arr[] = " t.train_through_new_time=0 ";
        }else{
            $where_arr[] = " t.train_through_new_time>0 ";
        }

        if($id_train_through_new == -1){
        }elseif ($id_train_through_new == 0) {
            # code...
            $where_arr[] = " t.train_through_new=0 ";
        }else{
            $where_arr[] = " t.train_through_new=1 ";
        }
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);

        if($have_wx==0){
            $where_arr[] ="(t.wx_openid = '' or t.wx_openid is null )";
        }elseif($have_wx==1){
            $where_arr[] ="t.wx_openid <> '' and t.wx_openid is not null";
        }
        if($lecture_status==-2){
            $where_arr[] = "tli.status is null";
        }else{
            $where_arr[] =["tli.status= %u",$lecture_status,-1];
        }
        if($subject_eg){
            $where_arr[] ="l.subject in ".$subject_eg;
        }
        if($grade_eg){
            $where_arr[] ="l.grade in ".$grade_eg;
        }


        if($lessonid >0){
            $where_arr=[
                ["l.lessonid = %u",$lessonid,-1]
            ];
        }
       

        $sql = $this->gen_sql_new("select l.lessonid,l.lesson_start,l.lesson_end,l.lesson_name,l.audio,l.draw,l.grade,l.subject,"
                                  ." l.lesson_status,t.teacherid,t.nick,t.user_agent,l.teacherid as l_teacherid,l.courseid,"
                                  ." tr.type as record_type,ap.reference,ap.teacher_type ,tp.realname reference_name,"
                                  ." if(tr.trial_train_status is null,-1,tr.trial_train_status) as trial_train_status,tr.acc,"
                                  ." t.phone phone_spare,tli.id as lecture_status,tt.teacherid real_teacherid,m.account,"
                                  ." l.real_begin_time,tr.record_info,t.identity,tl.add_time,t.wx_openid,l.train_email_flag ,"
                                  ." if(tli.status is null,-2,tli.status) as lecture_status_ex,tr.id access_id,tl.train_type, "
                                  ." am.account zs_account,am.name zs_name,tl.train_type tt_train_type,"
                                  ." tr.train_lessonid tt_train_lessonid,"
                                  ." tr.id tt_id,tl.add_time tt_add_time,tli.resume_url,"
                                  ." t.train_through_new_time,t.train_through_new "
                                  ." from %s l"
                                  ." left join %s tl on l.lessonid=tl.lessonid"
                                  ." left join %s t on tl.userid=t.teacherid"
                                  ." left join %s tr on l.lessonid=tr.train_lessonid"
                                  ." left join %s tli on t.phone=tli.phone"
                                  ." left join %s tt on t.phone=tt.phone"
                                  ." left join %s ttt on l.teacherid=ttt.teacherid"
                                  ." left join %s m on ttt.phone = m.phone "
                                  ." left join %s ap on t.phone = ap.phone"
                                  ." left join %s tp on ap.reference=tp.phone"
                                  ." left join %s am on ap.accept_adminid = am.uid"
                                  ." where %s"
                                  ." group by l.lessonid"
                                  ." order by l.lesson_start desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_record_list::DB_TABLE_NAME
                                  ,t_teacher_lecture_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_teacher_lecture_appointment_info::DB_TABLE_NAME//ap
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    public function train_lecture_lesson_count(
        $start_time,$end_time,$opt_date_str=-1,$have_wx_flag=-1,$train_email_flag_new=-1
    ){
        $where_arr = [
            ["l.train_email_flag=%u",$train_email_flag_new,-1],
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=5",
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        if($have_wx_flag==0){
            $where_arr[] ="(t.wx_openid = '' or t.wx_openid is null )";
        }elseif($have_wx_flag==1){
            $where_arr[] ="t.wx_openid <> '' and t.wx_openid is not null";
        }

        $sql = $this->gen_sql_new("select count(distinct l.lessonid) all_num,count(distinct tl.userid) all_user "
                                  ." from %s l"
                                  ." left join %s tl on l.lessonid=tl.lessonid"
                                  ." left join %s t on tl.userid=t.teacherid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_all_train_num($start_time,$end_time,$teacher_list,$train_through_new,$flag=false){
        $where_arr = [
            "l.train_type=1",
            "l.lesson_del_flag=0",
            // ["t.train_through_new=%u",$train_through_new,-1]
        ];
        if($train_through_new==1){
            $where_arr[] = "t.train_through_new_time>0";
        }
        $where_arr[]=$this->where_get_in_str("t.teacherid",$teacher_list,$flag);
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(distinct t.teacherid)"
                                  ." from %s l left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on ta.userid  = t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }
    public function get_all_train_num_new($start_time,$end_time,$teacher_list,$train_through_new,$flag=false){
        $where_arr = [
            "l.train_type=1",
            "l.lesson_del_flag=0",
            "l.lesson_start>".$start_time
            // ["t.train_through_new=%u",$train_through_new,-1]
        ];
        if($train_through_new==1){
            $where_arr[] = "t.train_through_new_time>0";
        }
        $where_arr[]=$this->where_get_in_str("t.teacherid",$teacher_list,$flag);
        // $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(distinct t.teacherid)"
                                  ." from %s l left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on ta.userid  = t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_all_trial_train_num($start_time,$end_time,$teacher_list,$trial_train_status,$flag=false){
        $where_arr = [
            "l.train_type=4",
            "l.lesson_del_flag=0",
            ["tr.trial_train_status=%u",$trial_train_status,-1],
            "tr.trial_train_status<3",
            "tr.trial_train_status>0",
            "l.lesson_start>".$start_time
        ];
        $where_arr[]=$this->where_get_in_str("t.teacherid",$teacher_list,$flag);
        //$this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(distinct tr.teacherid)"
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tr on tr.train_lessonid = l.lessonid and tr.type=1 and tr.lesson_style=5 "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }



    public function get_all_train_num_real($start_time,$end_time,$teacher_list,$train_through_new,$flag=false){
        $where_arr = [
            "l.train_type=1",
            "l.lesson_del_flag=0",
            ["t.train_through_new=%u",$train_through_new,-1],
            "lo.lessonid is not null and lo.lessonid>0"
        ];
        $where_arr[]=$this->where_get_in_str("t.teacherid",$teacher_list,$flag);
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(distinct lo.userid)"
                                  ." from %s l left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on ta.userid  = t.teacherid"
                                  ." left join %s lo on (lo.lessonid = l.lessonid and lo.userid = ta.userid and lo.opt_type=1 and lo.opt_time=(select min(opt_time) from %s where lessonid = lo.lessonid and userid = lo.userid and opt_type=1))"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_opt_log::DB_TABLE_NAME,
                                  t_lesson_opt_log::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }



    public function get_one_apply_num($start_time,$end_time){
        $where_arr=[
            "l.train_type=5",
            "l.lesson_del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"ta.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct phone_spare) from %s l "
                                  ." left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on ta.userid = t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_value($sql);

    }

    public function get_train_lesson_list_for_select($page_num,$lesson_name,$lesson_type=1100)
    {
        $where_arr = [
            ["lesson_type=%u",$lesson_type,-1],
            ["lesson_name like '%%%s%%'",$lesson_name,""],
        ];
        $sql = $this->gen_sql_new("select lessonid,lesson_name,lesson_type,lesson_start,lesson_end"
                                  ." from %s "
                                  ." where %s"
                                  ." order by lesson_start desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_teacher_origin_list($start_time,$end_time){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type=1100",
            "l.train_type=1",
            "is_test_user=0",
        ];
        $through_arr = [
            ["train_through_new_time>%u",$start_time,0],
            ["train_through_new_time<%u",$end_time,0],
            "train_through_new=1"
        ];
        $sql = $this->gen_sql_new("select teacher_ref_type,sum(if(%s,1,0)) as through_num,count(tl.userid) as train_num"
                                  ." from %s l"
                                  ." left join %s tl on l.lessonid=tl.lessonid"
                                  ." left join %s t on tl.userid=t.teacherid"
                                  ." where %s"
                                  ." group by t.teacher_ref_type"
                                  ,$through_arr
                                  ,self::DB_TABLE_NAME
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacher_ref_type'];
        });
    }

    public function update_lesson_grade($courseid,$grade){
        $year         = date("Y",time());
        $lesson_start = strtotime("$year-7-1");
        $lesson_end   = strtotime("$year-9-1");
        $where_arr = [
            ["courseid=%u",$courseid,0],
            ["lesson_start>%u",$lesson_start,0],
            ["lesson_start<%u",$lesson_end,0],
        ];
        $sql = $this->gen_sql_new("update %s set grade=%u"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$grade
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_lesson_cost_list ($teacherid, $type, $start, $end) {
        $sql = $this->gen_sql_new(" select l.lesson_count, s.nick, l.lesson_type,l.lesson_start, l.lesson_end ".
                                  " from %s l left join %s s on  l.userid = s.userid ".
                                  " where l.teacherid = %d  and lesson_status = 2 ".
                                  " and l.lesson_type in (%s) and l.lesson_start >= %d ".
                                  " and l.lesson_end <= %d and lesson_del_flag = 0",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $teacherid, $type, $start, $end);
        return $this->main_get_list($sql);

    }

    public function set_comment_status($lessonid, $comment_date) {
        $sql = $this->gen_sql_new(
            "update %s t set t.tea_rate_time = %s ".
            "where t.lessonid = %d",
            self::DB_TABLE_NAME,
            $comment_date,
            $lessonid
        );
        return $this->main_update($sql);
    }

    public function get_comment_list_by_page ( $teacherid, $start_time,$end_time,$lesson_type_list_str, $page_num) {
        $sql = $this->gen_sql_new("select l.lessonid,l.confirm_flag,l.stu_attend, l.lesson_type, subject,lesson_name, l.grade, lesson_start,lesson_end, nick, tea_rate_time ".
                                  "from %s l left join %s s on l.userid = s.userid ".
                                  "where l.teacherid = %d and l.lesson_start>= %d and l.lesson_end < %d and l.lesson_type in (%s) and confirm_flag<2 and l.lesson_del_flag =0 and lesson_status>0 ".
                                  " order by l.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $teacherid, $start_time,
                                  $end_time, $lesson_type_list_str,time(NULL));

        return $this->main_get_list_by_page($sql,$page_num,100);

    }



    public function get_teacher_lessons($teacherid, $start_time, $end_time) {
        $sql = $this->gen_sql_new(" select lesson_start, lesson_end,free_time_new"
                                   ." from %s tl"
                                   ." left join %s tf on tf.teacherid = tl.teacherid"
                                   ." where tl.teacherid = %d and lesson_start >= %s and lesson_end < %s"
                                   ,self::DB_TABLE_NAME
                                   ,t_teacher_freetime_for_week::DB_TABLE_NAME
                                   ,$teacherid
                                   ,$start_time
                                   ,$end_time
        );
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_list($start_time,$end_time,$userid = -1,$lessonid = 0){
        $type = 1;
        if($lessonid){//手动刷新
            $type = 0;
            $this->where_arr_add_int_field($where_arr,'l.lessonid',$lessonid);
            $where_arr[] = 'lss.call_end_time = 0';
        }else{//定时刷新
            $where_arr = [
                ["l.lesson_type=%u",2],
                ["l.lesson_del_flag=%u",0],
                ["l.lesson_start>=%u",$start_time],
                ["l.lesson_start<%u",$end_time],
                ["l.userid=%u",$userid,-1],
                " lss.call_before_time = 0 or lss.call_end_time = 0 ",
            ];
        }
        $sql = $this->gen_sql_new("select l.userid,l.lessonid,l.lesson_start,l.lesson_end,m.tquin,n.phone,"
                                  ."lss.call_before_time,lss.call_end_time "
                                  ."from %s l "
                                  ."left join %s lss on lss.lessonid = l.lessonid "
                                  ."left join %s lsr on lsr.require_id = lss.require_id "
                                  ."left join %s m on m.uid = lsr.cur_require_adminid "
                                  ."left join %s n on n.userid = l.userid "
                                  ."where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_seller_student_new::DB_TABLE_NAME
                                  ,$where_arr
        );
        $lesson_arr = array();
        $lesson_arr = $this->main_get_list($sql);
        if($lessonid){
            if(!$lesson_arr){
                return $ret = 3;
            }
        }
        if(count($lesson_arr)>0){
            $ret = $this->update_lesson_call($lesson_arr,$type);
            if(!$type){
                return $ret;
            }
        }
    }

    public function update_lesson_call($lesson_arr,$type){//type:是否要求回访打通,1定时刷新,0手动刷新
        foreach($lesson_arr as $item){
            $lessonid     = $item['lessonid'];
            $tquin        = (int)$item['tquin'];
            $phone        = $item['phone'];
            $lesson_start = $item['lesson_start'];
            $lesson_end = $item['lesson_end'];
            $day_start    = date('Y-m-d',$lesson_start);
            $lesson_time  = strtotime($day_start."00:00:00");
            $middle_time  = strtotime($day_start.'12:00:00');
            $call_before_time_arr = [];
            $call_end_time_arr = [];
            $call_before_time = $item['call_before_time'];
            $call_end_time = $item['call_end_time'];

            if($lesson_start <= $middle_time){
                $call_start = $lesson_time - 12*3600;
                $call_end   = $lesson_time + 24*3600;
            }else{
                $call_start = $lesson_time;
                $call_end   = $lesson_time + 24*3600;
            };
            $lesson_call_list = $this->task->t_tq_call_info->get_list_ex_new($tquin,$phone,$call_start,$call_end,$type,$lesson_end);
            foreach($lesson_call_list as $time_item){
                $call_time = $time_item["start_time"];
                if($type){
                    if($call_time < $lesson_start){
                        $call_before_time_arr[] =$call_time;
                    }elseif($call_time > ($lesson_start+1800)){
                        $call_end_time_arr[] = $call_time;
                    }
                }else{
                    if($call_time < $lesson_start){
                        $call_before_time_arr[] =$call_time;
                    }elseif($call_time > $lesson_end){
                        $call_end_time_arr[] = $call_time;
                    }
                }
            }
            if(count($call_before_time_arr)>0){
                $call_before_time = max($call_before_time_arr);
            }
            if(count($call_end_time_arr)>0){
                $call_end_time = min($call_end_time_arr);
            }
            $ret = $this->task->t_test_lesson_subject_sub_list->field_update_list($lessonid, [
                "call_before_time" => $call_before_time,
                "call_end_time"    => $call_end_time,
            ]);
            if(!$type){//手动刷新
                return $ret;
            }
        }
    }

    public function set_stu_performance( $lessonid, $teacherid, $stu_performance, $ass_comment_audit) {
        $sql = $this->gen_sql_new("update %s t set t.stu_performance = '%s', t.ass_comment_audit = %d ".
                                  "where t.lessonid = %d and t.teacherid = %d",
                                  self::DB_TABLE_NAME, $stu_performance,
                                  $ass_comment_audit, $lessonid, $teacherid);
        return $this->main_update($sql);
    }

    public function set_stu_performance_tmp( $lessonid, $teacherid, $stu_performance, $ass_comment_audit) {
        $sql = $this->gen_sql_new("update %s t set t.stu_performance = '%s', t.ass_comment_audit = %d ".
                                  "where t.lessonid = %d ",
                                  self::DB_TABLE_NAME, $stu_performance,
                                  $ass_comment_audit, $lessonid);
        return $this->main_update($sql);
    }


    public function get_train_lesson_list(){
        $where_arr = [
            "lesson_status=0",
            "lesson_type=1100",
            "train_type=5",
            "lesson_sub_type=1"
        ];
        $sql = $this->gen_sql_new("select lessonid,lesson_name,nick"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);

    }


    public function get_seller_test_lesson_list( $start_time,$end_time) {
        $where_arr=[
            "lesson_del_flag=0",
            "lesson_type=2" ,
            "confirm_flag <>2" ,//课程有效
        ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);

        $sql= $this->gen_sql_new(
            "select l.lessonid ,tr.cur_require_adminid,l.lesson_start"
            . " from %s l "
            . " join %s tss on l.lessonid=tss.lessonid "
            . " join %s tr on tr.require_id=tss.require_id "
            . " where %s ",
            self::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_list($sql);
    }

    public function get_lesson_count_by_userid($userid,$order_time){
        $where_arr = [
            ['lesson_del_flag=%d',0],
            ['lesson_status=%d',2],
            ['lesson_type = %d',0],
            ['userid = %d',$userid],
            'confirm_flag not in (2,4)',
            "lesson_start>$order_time",
        ];
        $sql = $this->gen_sql_new("select count(lessonid) count "
                ." from %s "
                ." where %s ",
                self::DB_TABLE_NAME,
                $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_all_ass_stu_lesson_info($start_time,$end_time){
        $where_arr=[
            "l.assistantid>0",
            "s.is_test_user=0",
            "m.account_role=1",
            // "m.del_flag=0"
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql=$this->gen_sql_new("select distinct l.userid,m.uid "
                                ." from %s l left join %s s on l.userid= s.userid"
                                ." left join %s a on l.assistantid = a.assistantid"
                                ." left join %s m on a.phone = m.phone"
                                ." where %s",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_lesson_count(){
        $sql="select sum(lesson_count) as lesson_all_count,t.teacherid,t.nick,t.phone,t.teacher_money_type,t.level from t_lesson_info l left join t_teacher_info t on l.teacherid=t.teacherid where lesson_start>1490976000 and lesson_start<1498838400 and confirm_flag!=2 and lesson_type in (0,1,3) and lesson_del_flag=0 and is_test_user=0 group by l.teacherid having lesson_all_count>=0";
        return $this->main_get_list($sql);
    }

    public function get_train_lesson_intervie_no_assess_info(){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_end <=".(time()-3600),
            "r.id is null",
            "l.train_type =5",
            "l.lesson_end>".(time()-3670),
            "l.train_lesson_wx_after=0"
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.lesson_end,l.lesson_start,l.lesson_status,m.uid,t.realname,tt.realname train_realname,tt.phone_spare,tt.wx_openid,ta.userid,t.teacherid,m.account,l.subject,l.grade  "
                                  ." from %s l left join %s r on (l.lessonid = r.train_lessonid and r.type=10)"
                                  ." left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tt on ta.userid = tt.teacherid"
                                  ." left join %s m on m.phone= t.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_train_intervie_lessoning_info(){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_start >=".(time()-180),
            "l.train_type =5",
            "l.lesson_start<".(time()-120),
            "tal.accept_adminid >0"
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.lesson_end,l.lesson_start,l.lesson_status,tt.realname train_realname,tt.phone_spare,tt.wx_openid,ta.userid,l.subject,l.grade,tal.accept_adminid   "
                                  ." from %s l "
                                  ." left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s tt on ta.userid = tt.teacherid"
                                  ." left join %s tal on tt.phone_spare = tal.phone and tal.answer_begin_time = (select max(answer_begin_time) from %s where phone = tt.phone_spare)"
                                   ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_train_intervie_user_agent_info(){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.train_type =5",
            "l.lesson_start>".time(),
            "tt.user_agent is not null && tt.user_agent <> ''",
            "tt.user_agent not like '%%4.0.0%%' and tt.user_agent not like '%%5.0.4%%'",
            "tal.accept_adminid >0",
            "tt.user_agent_wx_update=0"
        ];
        $sql = $this->gen_sql_new("select distinct tt.realname,tal.accept_adminid,tt.user_agent,tt.teacherid  "
                                  ." from %s l "
                                  ." left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s tt on ta.userid = tt.teacherid"
                                  ." left join %s tal on tt.phone_spare = tal.phone and tal.answer_begin_time = (select max(answer_begin_time) from %s where phone = tt.phone_spare)"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_train_lesson_intervie_next_day_info($start_time,$end_time){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.train_type =5",
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.lessonid,l.lesson_end,l.lesson_start,l.lesson_status,m.uid,t.realname,tt.realname train_realname,tt.phone_spare,tt.wx_openid,ta.userid,t.teacherid,m.account,l.subject,l.grade  "
                                  ." from %s l "
                                  ." left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tt on ta.userid = tt.teacherid"
                                  ." left join %s m on m.phone= t.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_train_lesson_intervie_before_info(){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.train_type =5",
            "l.lesson_start<=".(time()+3600),
            "l.lesson_start >".(time()+3300),
            "l.train_lesson_wx_before=0"
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.lesson_end,l.lesson_start,l.lesson_status,m.uid,t.realname,tt.realname train_realname,tt.phone_spare,tt.wx_openid,ta.userid,t.teacherid,m.account,l.subject,l.grade  "
                                  ." from %s l "
                                  ." left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tt on ta.userid = tt.teacherid"
                                  ." left join %s m on m.phone= t.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_train_lesson_intervie_before_info_list(){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.train_type =5",
            // "l.lesson_start>=".(time()+3600),
            //"l.train_lesson_wx_before=0"
        ];
        $sql = $this->gen_sql_new("select distinct tt.phone_spare,tt.realname,tt.wx_openid  "
                                  ." from %s l "
                                  ." left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tt on ta.userid = tt.teacherid"
                                  ." where %s order by wx_openid desc",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_subject_teacher(){
        $sql = "select t.teacherid,t.phone,l.subject,l.grade from t_teacher_info t left join t_train_lesson_user tl on t.teacherid=tl.userid left join t_lesson_info l on  tl.lessonid=l.lessonid where t.create_time >1497628800 and t.subject<=0 and t.is_test_user=0 and t.phone not like '999%' and t.trial_lecture_is_pass=1;";
        return $this->main_get_list($sql);
    }

    public function get_lesson_total_list($start_time,$end_time,$teacher_money_type,$level){
        $where_arr = [
            ["l.lesson_start>%u",$start_time,0],
            ["l.lesson_start<%u",$end_time,0],
            ["t.teacher_money_type in (%s)",$teacher_money_type,""],
            ["t.level in (%s)",$level,""],
            "is_test_user=0",
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select t.teacherid,t.teacher_money_type,t.teacher_ref_type,t.nick,t.level,"
                                  ." t.create_time,t.test_transfor_per,"
                                  ." sum(if(lesson_type=2,lesson_count,0)) as trial_lesson_total,"
                                  ." sum(if(lesson_type in (0,1,3),lesson_count,0)) as lesson_total"
                                  ." from %s t"
                                  ." left join %s l on t.teacherid=l.teacherid"
                                  ." where %s"
                                  ." group by t.teacherid"
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_num($teacherid,$start,$end){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            "lesson_type in (0,1,3)",
            "lesson_status=2",
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("select count(distinct(userid))"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_test_lesson_count_by_userid($userid,$create_time, $lesson_user_online_status=1){
        $where_arr = [
            ['userid = %d',$userid],
            ['lesson_type=%d',2],
            ['lesson_del_flag=%d',0],
            'confirm_flag in (0,1)',
            ["lesson_user_online_status =%d  ",$lesson_user_online_status,-1],
            "lesson_start>$create_time",
        ];
        $sql = $this->gen_sql_new(
            "select lessonid,userid"
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_test_lesson_by_userid($userid){
        $where_arr = [
            ['userid = %d',$userid],
            // ['lesson_type=%d',2],
            // ['lesson_del_flag=%d',0],
            // 'confirm_flag in (0,1)',
            // 'lesson_user_online_status = 1',
        ];
        $sql = $this->gen_sql_new(
            "select * "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_all_train_interview_lesson_info($start_time,$end_time){
        $where_arr=[
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=5",
            "l.lesson_del_flag=0",
            "l.confirm_flag <2",
            "l.train_email_flag=0",
            "l.lesson_start>=".$start_time,
            "l.lesson_start<=".$end_time
        ];
        $sql = $this->gen_sql_new("select l.lessonid,t.phone,l.train_email_flag,t.realname,l.lesson_start,l.lesson_end,t.teacherid "
                                  ." from %s l left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on t.teacherid = ta.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function check_lesson_is_exists($teacherid,$userid){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["userid=%u",$userid,0],
        ];
        $sql = $this->gen_sql_new("select 1 "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_grade_wages_list($start_time,$end_time,$teacher_type){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type<1000",
            "lesson_status=2",
            "t.is_test_user=0",
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        if($teacher_type!=3){
            $where_arr[] = "(t.teacher_type!=3 or l.teacherid in (51094,99504,97313))";
        }else{
            $where_arr[] = "(t.teacher_type=3 and l.teacherid not in (51094,99504,97313))";
        }
        $sql = $this->gen_sql_new("select l.lesson_count,l.lesson_type,l.grade,"
                                  ." sum(o.price) as lesson_price,m.money,"
                                  ." t.teacher_type,"
                                  ." l.lesson_start,l.teacher_money_type,l.lessonid,l.level"
                                  ." from %s l"
                                  ." left join %s o on l.lessonid=o.lessonid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on l.level=m.level "
                                  ." and m.grade=(case when "
                                  ." l.competition_flag=1 then if(l.grade<200,203,303) "
                                  ." else l.grade"
                                  ." end )"
                                  ." and l.teacher_money_type=m.teacher_money_type"
                                  ." where %s"
                                  ." group by l.lessonid"
                                  ,self::DB_TABLE_NAME
                                  ,t_order_lesson_list::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_money_type::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['lessonid'];
        });
    }

    public function test_lesson_total($start,$end,$teacher_type){
        $where_arr = [
            ["l.lesson_start>%u",$start,0],
            ["l.lesson_start<%u",$end,0],
        ];
        if($teacher_type!=3){
            $where_arr[] = "(t.teacher_type!=3 or l.teacherid in (51094,99504,97313))";
        }else{
            $where_arr[] = "(t.teacher_type=3 and l.teacherid not in (51094,99504,97313))";
        }
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select l.lessonid"
                                  ." t.teacher_money_type,t.level,t.teacher_money_flag,t.teacher_ref_type,t.test_transfor_per,"
                                  ." t.bankcard,t.bank_address,t.bank_account,t.bank_phone,t.bank_type,t.teacher_money_flag,"
                                  ." t.idcard,"
                                  ." sum(if(l.lesson_type in (0,1,3),l.lesson_count,0)) as lesson_1v1,"
                                  ." sum(if(l.lesson_type=2,l.lesson_count,0)) as lesson_trial,"
                                  ." sum(if(l.lesson_type<1000,l.lesson_count,0)) as lesson_total"
                                  ." from %s l"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." where %s"
                                  ." and l.lesson_type<1000"
                                  ." and l.lesson_status=2"
                                  ." and t.is_test_user=0"
                                  ." group by l.lessonid"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['lessonid'];
        });
    }

    public function get_regular_lesson_count_tongji($start_time,$end_time){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type <>2",
            "lesson_del_flag=0",
            "confirm_flag <2"
        ];
        $sql = $this->gen_sql_new("select sum(lesson_count/3) all_count,teacherid "
                                  ." from %s where %s group by teacherid having(all_count>=3000)",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });

    }

    public function check_psychological_lesson($teacherid,$lesson_start){
        $where_arr=[
            ["lesson_start=%u",$lesson_start,0],
            ["teacherid = %u",$teacherid,-1],
            "lesson_del_flag=0",
            "confirm_flag <2"
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_online_status_by_lessonid($lessonid){
        $sql = $this->gen_sql_new("select lesson_user_online_status from %s where lessonid=%d ",
                                  self::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_value($sql);
    }

    public function check_have_regular_lesson($start_time,$end_time,$userid){
        $where_arr=[
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            ["userid = %u",$userid,-1],
            "lesson_type <>2",
            "lesson_del_flag=0",
            "confirm_flag <2"
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_value($sql);
    }


    public function update_teacher_level($teacherid,$level){
        $time = time();
        $sql = $this->gen_sql_new("update %s set level = %u where teacherid = %u and lesson_status=0 and lesson_start >=%u",
                                  self::DB_TABLE_NAME,
                                  $level,
                                  $teacherid,
                                  $time
        );
        return $this->main_update($sql);
    }

    public function get_teacher_time_by_lessonid($lessonid,$start,$end){

        $sql = $this->gen_sql_new("select l.lesson_start,l.lesson_end from %s l where teacherid in (select teacherid from %s l2 where lessonid = %d) and l.lesson_start>%d and l.lesson_end<%d and lesson_del_flag = 0 ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $lessonid,
                                  $start,
                                  $end
        );

        return $this->main_get_list($sql);
    }

    public function get_teacher_lesson_list_www($teacherid,$userid,$start_time,$end_time,$lesson_type_in_str)
    {
        $where_arr = [
            ["lesson_start>=%d",$start_time, -1 ] ,
            ["lesson_start<=%d",$end_time, -1 ] ,
            ["l.lesson_type in (%s)",$lesson_type_in_str, "" ] ,
            ["l.userid=%u",$userid, -1 ] ,
            ["l.teacherid=%u",$teacherid, 0 ] ,
            "l.lesson_del_flag=0",
        ];

        $sql =$this->gen_sql_new(
            "select t.test_lesson_subject_id,l.lessonid,l.lesson_type,lesson_start,lesson_end,stu_cw_origin,tea_cw_origin,"
            ." lesson_intro,l.grade,l.subject,l.confirm_flag,l.assistantid,ta.phone as ass_phone, issue_origin,issue_file_id,"
            ." l.lesson_num,l.userid,lesson_name,lesson_status,ass_comment_audit,l.userid,stu_cw_file_id,tea_cw_file_id,"
            ." if(h.work_status>0,1,0) as homework_status,stu_cw_status as stu_status,"
            ." tea_cw_status as tea_status, editionid,t.textbook,l.train_type,"
            ." h.finish_url,h.check_url,l.tea_cw_url,l.tea_cw_upload_time,l.tea_cw_pic_flag,l.tea_cw_pic,"
            ." l.stu_cw_url,l.stu_cw_upload_time,h.issue_url,h.issue_time,"
            ." h.pdf_question_count ,tea_more_cw_url,  "
            ." t.stu_test_paper,t.require_adminid, "
            ." tm.name as cc_account,tm.phone as cc_phone,"
            ." tr.accept_adminid,"
            ." t.stu_request_test_lesson_demand"
            ." from %s l "
            ." left join %s h on l.lessonid=h.lessonid "
            ." left join %s s on l.userid=s.userid"
            ." left join %s tr on l.lessonid=tr.current_lessonid"
            ." left join %s t on tr.require_id=t.current_require_id"
            ." left join %s tm on t.require_adminid=tm.uid"
            ." left join %s ta on l.assistantid=ta.assistantid"
            ." where %s"
            ." and confirm_flag!=2"
            ." and l.lesson_type!=4001"
            ." order by lesson_start ",
            self::DB_TABLE_NAME ,
            t_homework_info::DB_TABLE_NAME ,
            t_student_info::DB_TABLE_NAME ,
            t_test_lesson_subject_require::DB_TABLE_NAME ,
            t_test_lesson_subject::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME,
            t_assistant_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['lessonid'];
        });
    }

    public function get_student_list($teacherid,$start,$end){
        $where_arr=[
            ["teacherid=%u",$teacherid,0],
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
        ];
        $sql=$this->gen_sql_new("select s.userid,if(s.nick!='',s.nick,s.realname) as nick "
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid"
                                ." where %s"
                                ." and lesson_type<1000"
                                ." and confirm_flag<2"
                                ." and lesson_del_flag=0"
                                ." group by s.userid"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_student_lesson( $page_num,$user_name){
        $where_arr=[
        ];
        if ($user_name !== '' ) {
            $where_arr[]=sprintf( "(s.nick like '%%%s%%' or s.realname like '%%%s%%' )",
                                  $this->ensql($user_name),
                                  $this->ensql($user_name));
        }

        $sql=$this->gen_sql_new("select s.userid, s.nick, s.realname,"
                                ." SUM( if(l.lesson_type in (0,1,3),1,0) ) as normal_nums, "
                                ." SUM( if(l.lesson_type=2,1,0) ) as free_nums,SUM(l.lesson_count) as lesson_count,"
                                ." SUM( if(l.lesson_type in (1001,1002,1003),1,0) ) as board_nums"
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid"
                                ." where %s "
                                ." group by s.userid",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                $where_arr
        );
        $ret_info = $this->main_get_list_by_page($sql,$page_num,10, true);
        foreach ($ret_info["list"] as $k => $v ) {
            $ret_info["list"][$k]["lesson_count"] = $v["lesson_count"]/100;
        }
        return $ret_info;

    }
    public function get_stu_id_face_left($parentid) {
        $where_arr = [
            ["p.parentid=%u",$parentid,0],
        ];
        $sql = $this->gen_sql_new("SELECT s.nick, s.userid, s.face AS stu_face, s.lesson_count_left"
                                  .",SUM( if(l.lesson_type in (0,1,3),1,0) ) AS normal_nums "
                                  ." FROM %s s"
                                  ." LEFT JOIN %s l ON l.userid=s.userid"
                                  ." LEFT JOIN %s p ON p.userid=s.userid"
                                  ." WHERE %s "
                                  ." GROUP BY s.userid"
                                  ." ORDER BY normal_nums DESC"
                                  ,t_student_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }
    public function get_stu_first_order_time ($userid){
        $where_arr = [
            ["userid=%s", $userid, 0],
            "pay_time>0",
        ];
        $sql = $this->gen_sql_new("SELECT pay_time"
                                  ." FROM %s"
                                  ." WHERE %s"
                                  ." ORDER BY pay_time"
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
    public function get_stu_title($userid, $start_time) {
        $where_arr  = [
            ["userid=%u", $userid, 0],
            ["lesson_start>=%u", $start_time, 0],
            ["lesson_end<%u", time(), 0],
            "lesson_type in (0,1,3)",
        ];
        $sql = $this->gen_sql_new("SELECT COUNT(1) AS count,subject"
                                     ." FROM %s"
                                     ." WHERE %s"
                                     ." GROUP BY subject"
                                     , self::DB_TABLE_NAME
                                     ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_stu_praise_total($userid) {
        $where_arr  = [
            ["userid=%u", $userid, 0],
        ];
        $sql = $this->gen_sql_new("SELECT praise"
                                     ." FROM %s"
                                     ." WHERE %s"
                                     , t_student_info::DB_TABLE_NAME
                                     ,$where_arr
        );
        return $this->main_get_value($sql);
    }
    public function get_stu_first($userid) {
        $where_arr = [
            ["userid=%u", $userid, 0],
            ["lesson_end<%u", time(), 0],
            "lesson_type in (0,1,2,3)",
            "lesson_start>0",
        ];
        $sql = $this->gen_sql_new("SELECT lesson_start, subject, lesson_type"
                                ." FROM %s"
                                ." WHERE %s"
                                ." GROUP BY lesson_type"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_stu_first_open_lesson($userid){
        $where_arr = [
            ["o.userid=%u", $userid, 0],
            "l.lesson_type in (1001,1002,1003)",
            "l.lesson_start>0",
        ];
        $sql = $this->gen_sql_new("SELECT l.lesson_start"
                                  ." FROM %s l"
                                  ." LEFT JOIN %s o ON o.lessonid=l.lessonid"
                                  ." WHERE %s"
                                  ,self::DB_TABLE_NAME
                                  , t_open_lesson_user::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
    public function get_stu_homework($userid, $start_time) {
        $where_arr = [
            ["l.userid=%u", $userid, 0],
            ["l.lesson_start>=%u", $start_time, 0],
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag in (0,1,3)",
            "l.lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("SELECT COUNT( w.score ) AS score_count, w.score"
                                  ." FROM %s l"
                                  ." LEFT JOIN %s w ON l.lessonid=w.lessonid"
                                  ." WHERE %s"
                                  ." GROUP BY w.score"
                                  ,self::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_stu_homework_finish($userid, $start_time) {
        $start_time = $this->get_stu_first_order_time($userid);
        $where_arr = [
            ["l.userid=%u", $userid, 0],
            ["l.lesson_start>%u", $start_time, 0],
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag in (0,1,3)",
            "l.lesson_del_flag=0",
            "w.work_status>0",
        ];
        $sql = $this->gen_sql_new("SELECT SUM(if(w.work_status=1,1,0)) AS nofinish"
                                  .", COUNT(w.work_status) AS count"
                                  ." FROM %s l"
                                  ." LEFT JOIN %s w ON l.lessonid=w.lessonid"
                                  ." WHERE %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_homework_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }
    public function get_stu_like_teacher($userid, $start_time) {
        $where_arr = [
            ["l.userid=%u", $userid, 0],
            ["l.lesson_start>=%u", $start_time, 0],
            ["l.lesson_end<%u", time(), 0],
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag in (0,1,3)",
            "l.lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("SELECT  SUM(l.lesson_count) AS teacher_lesson_count, t.realname, l.subject, l.lesson_start, if(s.lesson_count_left > 0,1,0) AS taday, l.teacherid,MAX(l.lesson_end) AS lesson_end"
                                  ." FROM %s l"
                                  ." LEFT JOIN %s t ON t.teacherid=l.teacherid"
                                  ." LEFT JOIN %s s ON s.userid=l.userid"
                                  ." WHERE %s"
                                  ." GROUP BY t.teacherid"
                                  ." ORDER BY teacher_lesson_count DESC"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }
    public function get_stu_score_info($userid, $start_time) {
        $where_arr = [
            ["userid=%u", $userid, 0],
            "lesson_type in (0,1,3)",
            "confirm_flag in (0,1,3)",
            ["lesson_start>=%u", $start_time, 0],
            ["lesson_end<%u", time(), 0],
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("SELECT teacher_score, COUNT(teacher_score) AS teacher_score_count"
                                  ." FROM %s"
                                  ." WHERE %s"
                                  ." GROUP BY teacher_score"
                                  ." ORDER BY teacher_score DESC"
                                  ." LIMIT 3"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_stu_lesson_time_total($userid) {
        $where_arr = [
            ["userid=%u", $userid, 0],
            "lesson_start>0",
            "lesson_type in (0,1,3)",
            "confirm_flag in (0,1,3)",
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("SELECT SUM(lesson_count) AS count"
                                  ." FROM %s"
                                  ." WHERE %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_teachers_realname($userid, $teacher_realname) {
        $where_arr = [
            ["l.userid=%s", $userid, 0],
            "t.realname!='".$teacher_realname."'",
            "t.realname!=''",
        ];
        $sql = $this->gen_sql_new("SELECT t.realname"
                                  ." FROM %s l"
                                  ." LEFT JOIN %s t ON t.teacherid=l.teacherid"
                                  ." WHERE %s"
                                  ." GROUP BY t.realname"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr

        );
        return $this->main_get_list($sql);

    }
    public function get_student_lesson_time_by_lessonid($lessonid ,$start=0, $end=0 ){
        $sql = $this->gen_sql_new(" select l.lesson_start, l.lesson_end from %s l ".
                                  " where userid in (select userid from %s l2 where lessonid = %d) and l.lesson_start>$start and l.lesson_end<=$end and lesson_del_flag = 0 ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $lessonid
        );


        return $this->main_get_list($sql);
    }

    public function get_lesson_time($lessonid){
        $sql = $this->gen_sql_new("select lesson_start, lesson_end from %s where lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_list($sql);
    }


    public function get_seller_phone_by_lessonid($lessonid){
        $sql = $this->gen_sql_new(" select m.phone from %s l ".
                                  " left join %s s on s.userid = l.userid".
                                  " left join %s m on m.uid = s.seller_adminid".
                                  " where l.lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_value($sql);
    }


    public function get_ass_wx_openid($lessonid){
        $sql = $this->gen_sql_new(" select wx_openid from %s l ".
                                  " left join %s ai on ai.assistantid = l.assistantid".
                                  " left join %s m on m.phone = ai.phone ".
                                  " where l.lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_value($sql);
    }


    public function get_seller_wx_openid($lessonid){
        $sql = $this->gen_sql_new(" select m.wx_openid from %s l ".
                                  " left join %s s on s.userid = l.userid ".
                                  " left join %s m on m.uid = s.admin_revisiterid".
                                  " where l.lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_value($sql);
    }


    // public function get_seller_wx_openid($lessonid){
    //     $sql = $this->gen_sql_new(" select m.wx_openid from %s l ".
    //                               " left join %s s on s.userid = l.userid ".
    //                               " left join %s m on m.uid = s.seller_adminid".
    //                               " where l.lessonid = %d",
    //                               self::DB_TABLE_NAME,
    //                               t_student_info::DB_TABLE_NAME,
    //                               t_manager_info::DB_TABLE_NAME,
    //                               $lessonid
    //     );

    //     return $this->main_get_value($sql);
    // }



    public function get_modify_lesson_time($lessonid){
        $sql = $this->gen_sql_new(" select lesson_start, lesson_end from %s l ".
                                  " left join %s tlm on tlm.lessonid = l.lessonid".
                                  " where l.lessonid = $lessonid and is_modify_time_flag = 1",
                                  self::DB_TABLE_NAME,
                                  t_lesson_time_modify::DB_TABLE_NAME

        );

        return $this->main_get_row($sql);
    }

    public function check_teacher_lesson($teacherid,$userid,$subject,$lesson_type){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["userid=%u",$userid,0],
            ["subject=%u",$subject,0],
        ];
        if($lesson_type==2){
            $where_arr[] = "lesson_type in (0,1,3)";
        }else{
            $where_arr[] = "lesson_type=2";
        }
        $sql = $this->gen_sql_new("select 1"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_lesson_row_info($teacherid,$lesson_type,$num,$userid=-1,$desc_flag=0){
        $where_arr = [
            ["teacherid= %u",$teacherid,-1],
            ["userid= %u",$userid,-1],
            "lesson_status>1",
            "lesson_del_flag=0",
            "lesson_user_online_status=1",
        ];
        if($lesson_type==-2){
            $where_arr[] = "lesson_type in (0,1,3)";
        }else{
            $where_arr[] = ["lesson_type= %u",$lesson_type,-1];
        }
        $str="";
        if($desc_flag==1){
            $str="desc";
        }

        $sql = $this->gen_sql_new("select lessonid,userid,subject,lesson_start from %s"
                                  ." where %s order by lesson_start %s limit %u,1",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  $str,
                                  $num
        );
        return $this->main_get_row($sql);
    }

    public function get_new(){
        $where_arr = [
            ' l.lesson_type<1000 and l.lesson_type!=2 ',
        ];
        $sql=$this->gen_sql_new("select s.userid,s.nick,s.phone,l.lesson_count,l.userid "
                                ." from %s l"
                                ." left join %s s on l.userid=s.userid"
                                ." where %s group by l.userid "
                                ,SELF::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function tongji_1v1_lesson_time($start_time,$end_time){
        $where_arr=[
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=5",
            "l.lesson_del_flag=0",
            "l.confirm_flag<2",
            "tr.trial_train_status in (0,1)"
        ];
        $sql = $this->gen_sql_new("select FROM_UNIXTIME( l.lesson_start, '%%Y%%m%%d' ) day,FROM_UNIXTIME(l.lesson_start, '%%w' ) week,sum(l.lesson_end - l.lesson_start) time,l.teacherid,t.realname   "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tr on l.lessonid = tr.train_lessonid and tr.type =10"
                                  ." where %s group  by l.teacherid,day  having(week=1 or week=0)",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function tongji_1v1_lesson_time_late($start_time,$end_time){
        $where_arr=[
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=5",
            "l.lesson_del_flag=0",
            "l.confirm_flag<2",
            "tr.trial_train_status in (0,1)"
        ];
        $sql = $this->gen_sql_new("select FROM_UNIXTIME(l.lesson_start, '%%k' ) hour,FROM_UNIXTIME(l.lesson_start, '%%w' ) week,sum(l.lesson_end - l.lesson_start) time,l.teacherid  "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tr on l.lessonid = tr.train_lessonid and tr.type =10"
                                  ." where %s group  by l.teacherid,hour,week having(hour>=20 and week >1)",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function tongji_1v1_lesson_time_morning($start_time,$end_time){
        $where_arr=[
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=5",
            "l.lesson_del_flag=0",
            "l.confirm_flag<2",
            "tr.trial_train_status in (0,1)",
            "l.teacherid in (176999,190394)"
        ];
        $sql = $this->gen_sql_new("select FROM_UNIXTIME(l.lesson_start, '%%k' ) hour,FROM_UNIXTIME(l.lesson_start, '%%w' ) week,sum(l.lesson_end - l.lesson_start) time,l.teacherid  "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tr on l.lessonid = tr.train_lessonid and tr.type =10"
                                  ." where %s group  by l.teacherid,hour,week having(hour>=9 and week >1 and hour <11)",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }



    public function get_lesson_time_flag($userid,$teacherid){
        $where_arr = [
            ['userid = %d',$userid],
            ['teacherid = %d',$teacherid],
            'lesson_type = 0 '
        ];

        $sql = $this->gen_sql_new("select 1 from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_value($sql);
    }


    public function check_have_regular_lesson_new($userid,$lesson_time){
        $where_arr=[
            ["userid= %u",$userid,-1],
            ["lesson_start>%u",$lesson_time,0],
            "lesson_type<>2",
            "lesson_status=0",
            "lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }


    public function get_lesson_cancel_info_by_teacher($start_time,$end_time,$page_info,$lesson_cancel_reason_type){
        $where_arr = [
            "l.teacherid>0",
        ];

        if($lesson_cancel_reason_type == -1){
            $where_arr[] = "l.lesson_cancel_reason_type in (2,12) ";
        }else{
            $where_arr[] = ["l.lesson_cancel_reason_type = %d",$lesson_cancel_reason_type];
        }

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select t.teacher_money_type,t.train_through_new_time, l.lesson_cancel_reason_type, tls.require_adminid, l.teacherid, FORMAT(sum(l.lesson_count/100 ),2) as lesson_count_total,l.lesson_cancel_reason_type from %s l".
                                  " left join %s tll on tll.lessonid = l.lessonid".
                                  " left join %s tlr on tlr.require_id = tll.require_id".
                                  " left join %s tls on tls.test_lesson_subject_id = tlr.test_lesson_subject_id".
                                  " left join %s m on tll.confirm_adminid = m.uid".
                                  " left join %s t on t.teacherid = l.teacherid".
                                  " where %s group by l.teacherid order by l.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,null,30,true);

    }



    public function get_lesson_cancel_info_by_teacher_jy($start_time,$end_time,$page_info,$lesson_cancel_reason_type){
        $where_arr = [
            "l.teacherid>0",
        ];

        if($lesson_cancel_reason_type == -1){
            $where_arr[] = "l.lesson_cancel_reason_type in (2,12) ";
        }else{
            $where_arr[] = ["l.lesson_cancel_reason_type = %d",$lesson_cancel_reason_type];
        }

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select t.teacher_money_type,t.train_through_new_time, l.lesson_cancel_reason_type, tls.require_adminid, l.teacherid, FORMAT(sum(l.lesson_count/100 ),2) as lesson_count_total,l.lesson_cancel_reason_type from %s l".
                                  " left join %s tll on tll.lessonid = l.lessonid".
                                  " left join %s tlr on tlr.require_id = tll.require_id".
                                  " left join %s tls on tls.test_lesson_subject_id = tlr.test_lesson_subject_id".
                                  " left join %s m on tll.confirm_adminid = m.uid".
                                  " left join %s t on t.teacherid = l.teacherid".
                                  " where %s group by l.teacherid order by l.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql,null,30,true);

    }



    public function get_lesson_cancel_detail($start_time,$end_time,$lesson_cancel_reason_type,$teacherid){
        $where_arr = [
            // ["lesson_cancel_reason_type=%d",$lesson_cancel_reason_type,-1 ],
            ["l.teacherid=%d",$teacherid],
            "lesson_del_flag = 0"
        ];


        if($lesson_cancel_reason_type == -1){
            $where_arr[] ="(lesson_cancel_reason_type= 2 or lesson_cancel_reason_type= 12) ";
        }elseif($lesson_cancel_reason_type == 23){
            $where_arr[] = "l.deduct_come_late = 1";
        }else{
            $where_arr[] = ["lesson_cancel_reason_type=%d",$lesson_cancel_reason_type];
        }

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select tls.require_adminid, l.teacherid,l.lesson_type,l.lesson_start,l.lesson_end, l.lesson_count,l.lesson_cancel_reason_type, s.nick, s.grade, l.subject, s.assistantid from %s l".
                                  " left join %s s on s.userid = l.userid".
                                  " left join %s tll on tll.lessonid = l.lessonid".
                                  " left join %s tlr on tlr.require_id = tll.require_id".
                                  " left join %s tls on tls.test_lesson_subject_id = tlr.test_lesson_subject_id".
                                  " where %s order by l.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);

    }



    public function get_lesson_cancel_info_by_parent($start_time,$end_time,$page_info,$lesson_cancel_reason_type){
        $where_arr = [
            "l.userid>0",
            "s.is_test_user = 0",
            ["lesson_cancel_reason_type=%d",$lesson_cancel_reason_type,-1],
        ];

        if($lesson_cancel_reason_type == -1){
            $where_arr[] = "l.lesson_cancel_reason_type in (1,11)";
        }else{
            $where_arr[] = ["l.lesson_cancel_reason_type=%d",$lesson_cancel_reason_type];
        }

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select tls.require_adminid, l.userid,s.nick, tp.nick as parent_nick, s.assistantid, l.lesson_cancel_reason_type, FORMAT(sum(l.lesson_count/100 ),2) as lesson_count_total from %s l".
                                  " left join %s s on s.userid = l.userid".
                                  " left join %s tp on tp.parentid = s.parentid".
                                  " left join %s tll on tll.lessonid = l.lessonid".
                                  " left join %s tlr on tlr.require_id = tll.require_id".
                                  " left join %s tls on tls.test_lesson_subject_id = tlr.test_lesson_subject_id".
                                  " left join %s m on tll.confirm_adminid = m.uid".
                                  " where %s group by l.userid order by l.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,null,30,true);

    }




    public function get_lesson_cancel_detail_by_parent($start_time,$end_time,$lesson_cancel_reason_type,$userid){
        $where_arr = [
            // ["lesson_cancel_reason_type=%d",$lesson_cancel_reason_type,-1 ],
            ["l.userid=%d",$userid],
            // "lesson_del_flag = 0"
        ];

        if($lesson_cancel_reason_type == -1){
            $where_arr[] ="(lesson_cancel_reason_type= 1 or lesson_cancel_reason_type= 11) ";
        }else{
            $where_arr[] = ["lesson_cancel_reason_type=%d",$lesson_cancel_reason_type];
        }


        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select tls.require_adminid, l.teacherid,l.lesson_type,l.lesson_start,l.lesson_end, l.lesson_count,l.lesson_cancel_reason_type, s.nick,l.userid, s.grade, l.subject, s.assistantid, tp.nick as parent_nick from %s l".
                                  " left join %s s on s.userid = l.userid".
                                  " left join %s tp on  tp.parentid = s.parentid".
                                  " left join %s tll on tll.lessonid = l.lessonid".
                                  " left join %s tlr on tlr.require_id = tll.require_id".
                                  " left join %s tls on tls.test_lesson_subject_id = tlr.test_lesson_subject_id".

                                  " where %s order by l.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);

    }

    public function get_old_teacher_nick($lesson_start,$subject,$userid){
        $where_arr = [
            ["l.lesson_start<%d",$lesson_start],
            ["l.subject=%d",$subject],
            "l.lesson_type in (0,1,3)",
            ["l.userid=%d",$userid]
        ];

        $sql = $this->gen_sql_new(" select t.nick,t.teacherid from %s l".
                                  " left join %s t on t.teacherid = l.teacherid".
                                  " where %s order by l.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_test_lesson_success_list_yes(){
        $yesterday_day_start = strtotime(date('Y-m-d 00:00:00' , strtotime('-1 day')));
        $yesterday_day_end   = $yesterday_day_start + 86400;

        $where_arr = [
            ["l.lesson_start >%d",$yesterday_day_start],
            ["l.lesson_end <%d",$yesterday_day_end],
            "l.lesson_type = 2",
            "tll.success_flag = 0 "
        ];
        $sql = $this->gen_sql_new(" select t.nick as teacher_nick, s.nick as stu_nick, l.lessonid, l.userid, l.teacherid, l.grade, l.subject, l.lesson_start, l.lesson_end, tl.require_adminid, tll.success_flag, tll.order_confirm_flag from %s l ".
                                  " left join %s tll on tll.lessonid = l.lessonid ".
                                  " left join %s tls on tls.require_id = tll.require_id ".
                                  " left join %s tl on tl.test_lesson_subject_id = tls.test_lesson_subject_id".
                                  " left join %s s on s.userid = l.userid ".
                                  " left join %s t on t.teacherid = l.teacherid ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr

        );

        return $this->main_get_list($sql);
    }
    public function get_test_lesson_count( $userid) {
        $sql = $this->gen_sql_new(
            "select count(*) from %s where userid=%u and lesson_type=2",
            self::DB_TABLE_NAME,
            $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_test_lesson_success_list_two_days_ago(){
        $two_day_start = strtotime(date('Y-m-d 00:00:00' , strtotime('-2 day')));
        $two_day_end   = $two_day_start + 86400;

        $where_arr = [
            ["l.lesson_start >%d",$two_day_start],
            ["l.lesson_end <%d",$two_day_end],
            "l.lesson_type = 2",
            "tll.order_confirm_flag = 0"
        ];


        $sql = $this->gen_sql_new(" select t.nick as teacher_nick, s.nick as stu_nick, l.lessonid, l.userid, l.teacherid, l.grade, l.subject, l.lesson_start, l.lesson_end, tl.require_adminid, tll.success_flag, tll.order_confirm_flag from %s l ".
                                  " left join %s tll on tll.lessonid = l.lessonid ".
                                  " left join %s tls on tls.require_id = tll.require_id ".
                                  " left join %s tl on tl.test_lesson_subject_id = tls.test_lesson_subject_id".
                                  " left join %s s on s.userid = l.userid ".
                                  " left join %s t on t.teacherid = l.teacherid ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr

        );

        return $this->main_get_list($sql);

    }


    public function get_test_lesson_success_list_three_days_ago(){
        $three_day_start = strtotime(date('Y-m-d 00:00:00' , strtotime('-3 day')));
        $three_day_end   = $three_day_start + 86400;

        $where_arr = [
            ["l.lesson_start >%d",$three_day_start],
            ["l.lesson_end <%d",$three_day_end],
            "l.lesson_type = 2",
            "(tll.success_flag = 0 or tll.order_confirm_flag = 0)"

        ];

        $sql = $this->gen_sql_new(" select t.nick as teacher_nick, s.nick as stu_nick, l.lessonid, l.userid, l.teacherid, l.grade, l.subject, l.lesson_start, l.lesson_end, tl.require_adminid, tll.success_flag, tll.order_confirm_flag from %s l ".
                                  " left join %s tll on tll.lessonid = l.lessonid ".
                                  " left join %s tls on tls.require_id = tll.require_id ".
                                  " left join %s tl on tl.test_lesson_subject_id = tls.test_lesson_subject_id".
                                  " left join %s s on s.userid = l.userid ".
                                  " left join %s t on t.teacherid = l.teacherid ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr

        );

        return $this->main_get_list($sql);
    }




    public function get_teacher_regular_lesson_info($start_time,$end_time){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.confirm_flag <>2",
            "l.lesson_type <>2",
            "t.train_through_new_time >0",
            "t.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,t.realname,t.train_through_new_time,"
                                  ."t.grade_part_ex,t.subject,t.grade_start,t.grade_end,"
                                  ."sum(lesson_count) lesson_count"
                                  ." from %s l left join %s t on l.teacherid=t.teacherid"
                                  ." where %s group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function reset_lesson_teacher_money_type($teacherid,$lesson_start){
        $sql = $this->gen_sql_new("update %s set teacher_money_type=4"
                                  ." where teacherid=%s"
                                  ." and lesson_start>%u"
                                  ,self::DB_TABLE_NAME
                                  ,$teacherid
                                  ,$lesson_start
        );
        return $this->main_update($sql);
    }

    public function get_teacher_first_test_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$tea_subject=-1){
        $where_arr=[
            // "l.lesson_del_flag=0",
            //"l.lesson_user_online_status <2",
            //"l.lesson_type =2",
            // "l.lesson_status>0",
            //  "t.is_test_user=0",
            ["l.subject = %u",$subject,-1],
            ["tr.teacherid = %u",$teacherid,-1],
        ];
        if($record_flag==0){
            $where_arr[] = "(tr.record_info is null or tr.record_info='')";
        }elseif($record_flag==1){
            $where_arr[] = "tr.record_info <> ''";
        }

        if($tea_subject==12){
            $where_arr[]="l.subject in (4,6)";
        }elseif($tea_subject==13){
            $where_arr[]="l.subject in (7,8,9)";
        }elseif($tea_subject==-5){
            $where_arr[]="l.subject in (5,10)";
        }else{
            $where_arr[]=["l.subject=%u",$tea_subject,-1];
        }


        $this->where_arr_add_time_range($where_arr,"tr.lesson_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select tr.teacherid,t.realname,l.lessonid,l.lesson_start,l.subject,t.grade_start,t.grade_end,t.grade_part_ex,tr.id,tr.acc,tr.record_info,tr.add_time,l.grade,tr.lesson_invalid_flag,tq.test_stu_request_test_lesson_demand,tt.stu_request_test_lesson_demand   "
                                  ." from %s tr left join %s t on tr.teacherid = t.teacherid"
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s tss on tr.train_lessonid = tss.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s tt on tq.test_lesson_subject_id = tt.test_lesson_subject_id"
                                  ." where %s and tr.type=1 and tr.lesson_style=1",
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }

    public function get_teacher_first_test_lesson_detail($start_time,$end_time){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status <2",
            "l.lesson_type =2",
            "l.lesson_status>0",
            "t.is_test_user=0",
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,t.realname,l.lessonid,l.lesson_start,tr.id  "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tr on (l.teacherid = tr.teacherid and tr.type=1 and tr.lesson_style=1)"
                                  ." where %s and l.lesson_start = (select min(lesson_start) from %s where teacherid=l.teacherid and lesson_del_flag=0 and lesson_type=2 and lesson_user_online_status<2 and lesson_status>0 ) group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }

    public function get_teacher_first_regular_lesson_detail($start_time,$end_time){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status <2",
            "l.lesson_type in (0,3)",
            "l.lesson_status>0",
            "t.is_test_user=0"
        ];


        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,t.realname,l.lessonid,l.lesson_start,l.userid,tr.id "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s s on l.userid=s.userid"
                                  ." left join %s tr on (l.teacherid = tr.teacherid and l.userid = tr.userid and tr.type=1 and tr.lesson_style=3)"
                                  ." where %s and l.lesson_start = (select min(lesson_start) from %s where teacherid=l.teacherid and userid = l.userid and lesson_del_flag=0 and lesson_type in (0,3) and lesson_user_online_status<2 and lesson_status>0 ) group by l.teacherid,l.userid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }



    public function get_teacher_first_regular_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$userid,$tea_subject=-1){
        $where_arr=[
            //"l.lesson_del_flag=0",
            // "l.lesson_user_online_status <2",
            //"l.lesson_type in (0,3)",
            //"l.lesson_status>0",
            // "t.is_test_user=0",
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
        }elseif($tea_subject==-5){
            $where_arr[]="l.subject in (5,10)";
        }else{
            $where_arr[]=["l.subject=%u",$tea_subject,-1];
        }


        $this->where_arr_add_time_range($where_arr,"tr.lesson_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select tr.teacherid,t.realname,l.lessonid,l.lesson_start,l.subject,t.grade_start,t.grade_end,t.grade_part_ex,tr.id,s.nick,tr.acc,tr.record_info,tr.add_time,l.grade,tr.lesson_invalid_flag,l.userid "
                                  ." from %s tr left join %s t on tr.teacherid = t.teacherid"
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s s on l.userid=s.userid"
                                  ." where %s and tr.type=1 and tr.lesson_style=3",
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }

    public function get_teacher_fifth_regular_lesson_detail($start_time,$end_time){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status <2",
            "l.lesson_type in (0,3)",
            "l.lesson_status>0",
            "t.is_test_user=0"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,t.realname,l.lessonid,l.lesson_start,l.userid,tr.id "
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s s on l.userid=s.userid"
                                  ." left join %s tr on (l.teacherid = tr.teacherid and l.userid = tr.userid and tr.type=1 and tr.lesson_style=4)"
                                  ." where %s and l.lesson_start = (select lesson_start from %s where teacherid=l.teacherid and userid = l.userid and lesson_del_flag=0 and lesson_type in (0,3) and lesson_user_online_status<2 and lesson_status>0 order by lesson_start limit 4,1) group by l.teacherid,l.userid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }


    public function get_teacher_fifth_regular_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$userid,$tea_subject=-1){
        $where_arr=[
            //"l.lesson_del_flag=0",
            // "l.lesson_user_online_status <2",
            //"l.lesson_type in (0,3)",
            //"l.lesson_status>0",
            // "t.is_test_user=0",
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
        }elseif($tea_subject==-5){
            $where_arr[]="l.subject in (5,10)";
        }else{
            $where_arr[]=["l.subject=%u",$tea_subject,-1];
        }


        $this->where_arr_add_time_range($where_arr,"tr.lesson_time",$start_time,$end_time);
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

    public function get_teacher_fifth_regular_lesson_old($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$userid,$tea_subject=-1){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status <2",
            "l.lesson_type in (0,3)",
            "l.lesson_status>0",
            "t.is_test_user=0",
            ["l.subject = %u",$subject,-1],
            ["l.teacherid = %u",$teacherid,-1],
            ["l.userid = %u",$userid,-1],
        ];
        if($record_flag==0){
             $where_arr[] = "(tr.record_info is null or tr.record_info='')";
        }elseif($record_flag==1){
            $where_arr[] = "tr.add_time>0";
        }

        if($tea_subject==12){
            $where_arr[]="l.subject in (4,6)";
        }elseif($tea_subject==13){
            $where_arr[]="l.subject in (7,8,9)";
        }elseif($tea_subject==-5){
            $where_arr[]="l.subject in (5,10)";
        }else{
            $where_arr[]=["l.subject=%u",$tea_subject,-1];
        }


        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,t.realname,l.lessonid,l.lesson_start,l.subject,t.grade_start,t.grade_end,t.grade_part_ex,tr.id,s.nick,tr.acc,tr.record_info,tr.add_time,l.grade ,tr.lesson_invalid_flag"
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s s on l.userid=s.userid"
                                  ." left join %s tr on (l.lessonid = tr.train_lessonid and tr.type=1 and tr.lesson_style=4)"
                                  ." where %s and l.lesson_start = (select lesson_start from %s where teacherid=l.teacherid and userid = l.userid and lesson_del_flag=0 and lesson_type in (0,3) and lesson_user_online_status<2 and lesson_status>0 order by lesson_start limit 4,1) group by l.teacherid,l.userid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_info,10,true);

    }


    public function get_teacher_fifth_test_lesson_detail($start_time,$end_time){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.lesson_user_online_status <2",
            "l.lesson_type =2",
            "l.lesson_status>0",
            "t.is_test_user=0",
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select l.teacherid,t.realname,l.lessonid,l.lesson_start,tr.id"
                                  ." from %s l left join %s t on l.teacherid = t.teacherid"
                                  ." left join %s tr on (l.teacherid = tr.teacherid and tr.type=1 and tr.lesson_style=2)"
                                  ." where %s and l.lesson_start = (select lesson_start from %s where teacherid=l.teacherid and lesson_del_flag=0 and lesson_type=2 and lesson_user_online_status<2 and lesson_status>0 order by lesson_start limit 4,1 ) group by l.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  $where_arr,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }


    public function get_teacher_fifth_test_lesson($page_info,$start_time,$end_time,$subject,$teacherid,$record_flag,$tea_subject=-1){
        $where_arr=[
            // "l.lesson_del_flag=0",
            // "l.lesson_user_online_status <2",
            //"l.lesson_type =2",
            // "l.lesson_status>0",
            // "t.is_test_user=0",
            ["l.subject = %u",$subject,-1],
            ["tr.teacherid = %u",$teacherid,-1],
        ];
        if($record_flag==0){
            $where_arr[] = "(tr.record_info is null or tr.record_info='')";
        }elseif($record_flag==1){
            $where_arr[] = "tr.record_info <> ''";
        }
        if($tea_subject==12){
            $where_arr[]="l.subject in (4,6)";
        }elseif($tea_subject==13){
            $where_arr[]="l.subject in (7,8,9)";
        }elseif($tea_subject==-5){
            $where_arr[]="l.subject in (5,10)";
        }else{
            $where_arr[]=["l.subject=%u",$tea_subject,-1];
        }


        $this->where_arr_add_time_range($where_arr,"tr.lesson_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select tr.teacherid,t.realname,l.lessonid,l.lesson_start,l.subject,t.grade_start,t.grade_end,t.grade_part_ex,tr.id,tr.acc,tr.record_info,tr.add_time,l.grade ,tr.lesson_invalid_flag,tq.test_stu_request_test_lesson_demand,tt.stu_request_test_lesson_demand"
                                  ." from %s tr left join %s t on tr.teacherid = t.teacherid"
                                  ." left join %s l on tr.train_lessonid = l.lessonid"
                                  ." left join %s tss on tr.train_lessonid = tss.lessonid"
                                  ." left join %s tq on tq.require_id = tss.require_id"
                                  ." left join %s tt on tq.test_lesson_subject_id = tt.test_lesson_subject_id"
                                  ." where %s and tr.type=1 and tr.lesson_style=2",
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }


    public function get_call_end_time_by_adminid($adminid){
        $time = time()-24*3600;
        $where_arr = [
            ' l.tea_attend <> 0 ',
            ' l.stu_attend <> 0 ',
            ' l.lesson_type = 2 ',
            ' l.lesson_del_flag = 0 ',
            ' l.confirm_flag <2 ',
            ' l.lesson_user_online_status = 1 ',
            ' l.lesson_end > 1503244800 ',
            ' l.lesson_end <  '.$time,
            ' lss.call_end_time = 0 ',
            ' lss.success_flag in (0,1) ',
            [' lsr.cur_require_adminid = %d ',$adminid],
        ];
        $sql = $this->gen_sql_new(
            " select l.userid,l.lessonid,lsr.cur_require_adminid adminid,lss.call_end_time "
            ." from %s l "
            ." left join %s lss on lss.lessonid = l.lessonid "
            ." left join %s lsr on lsr.require_id = lss.require_id "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_call_end_time_by_adminid_new($adminid){
        $time = time();
        $where_arr = [
            ' l.tea_attend <> 0 ',
            ' l.stu_attend <> 0 ',
            ' l.lesson_type = 2 ',
            ' l.lesson_del_flag = 0 ',
            ' l.confirm_flag <2 ',
            ' l.lesson_user_online_status = 1 ',
            ' l.lesson_end > 1502899200 ',
            ' l.lesson_end <  '.$time,
            ' lss.call_end_time = 0 ',
            ' lss.success_flag in (0,1) ',
            [' lsr.cur_require_adminid = %d ',$adminid],
        ];
        $sql = $this->gen_sql_new(
            " select l.userid,l.lessonid,"
            ." s.phone,s.parent_name,s.nick stu_nick,"
            ." lsr.cur_require_adminid adminid,lss.call_end_time "
            ." from %s l "
            ." left join %s s on s.userid = l.userid "
            ." left join %s lss on lss.lessonid = l.lessonid "
            ." left join %s lsr on lsr.require_id = lss.require_id "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_fulltime_teacher_interview_info($start_time,$end_time){
        $where_arr=[
            "l.lesson_del_flag=0",
            "l.confirm_flag <2",
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=5",
            "ta.full_time=1"
        ];
        $this->where_arr_add_time_range($where_arr,"taa.add_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(taa.lessonid) num from %s l"
                                  ." join %s taa on l.lessonid = taa.lessonid"
                                  ." join %s t on l.userid= t.teacherid"
                                  ." join %s ta on t.phone = ta.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_lesson_info_teacher_check_total($start_time,$end_time){
        $where_arr=[
            "lesson_type in (0,1,2,3)",
            "s.is_test_user = 0",
            "lesson_del_flag = 0",
            "l.teacherid>0",
        ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(*) all_num,sum(if(l.lesson_type in (0,1,3),1,0)) normal_num, sum(if(deduct_come_late=1,1,0)) teacher_come_late_count, sum(if(lesson_cancel_reason_type=2,1,0)) teacher_change_lesson, sum(if(lesson_cancel_reason_type=12,1,0)) teacher_leave_lesson, sum(if(lesson_cancel_reason_type=21,1,0)) teacher_no_attend_lesson "
                                ." from %s l "
                                ." left join %s s on l.userid=s.userid "
                                ." where  %s"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_lesson_info_teacher_tongji_jy($start_time,$end_time,$is_full_time=-1,$teacher_money_type,$show_all_flag=1){
        $where_arr=[
            "lesson_type in (0,1,2,3)",
            "s.is_test_user = 0",
            "lesson_del_flag = 0",
            "l.teacherid>0",
            ["t.teacher_money_type=%d",$teacher_money_type,-1]
        ];

        if($is_full_time >=0){
            if($is_full_time == 1){ // 兼职老师
                $where_arr[] = "t.teacher_type not in(3,4) and (m.account_role not in(4,5) or m.account_role is null)";
            }else{ // 全职老师
                $where_arr[] = "m.account_role=5 and t.is_quit = 0 and t.trial_lecture_is_pass=1";
            }
        }

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        if($show_all_flag==0){
            $limit_str ="limit 300";
        }else{
            $limit_str ="";
        }

        $sql=$this->gen_sql_new("select t.teacher_type, m.account_role, count(distinct l.userid) stu_num, FORMAT(sum(if(confirm_flag <> 2,lesson_count/100,0)),2) valid_count,  FORMAT(sum(if(deduct_come_late=1,lesson_count/100,0)),2) teacher_come_late_count, FORMAT(sum(if(lesson_cancel_reason_type=21,lesson_count/100,0)),2) teacher_cut_class_count, FORMAT(sum(if(lesson_cancel_reason_type=2,lesson_count/100,0)),2) teacher_change_lesson,  FORMAT(sum(if(lesson_cancel_reason_type=12,lesson_count/100,0)),2) teacher_leave_lesson, sum(if(lesson_cancel_reason_type=12,1,0)) teacher_leave_num,t.teacher_money_type, t.train_through_new_time, l.lesson_cancel_reason_type,  l.teacherid"
                                ." from %s l "
                                ." left join %s s on l.userid=s.userid "
                                ." left join %s tll on tll.lessonid = l.lessonid"
                                ." left join %s tlr on tlr.require_id = tll.require_id"
                                ." left join %s tls on tls.test_lesson_subject_id = tlr.test_lesson_subject_id"
                                ." left join %s t on t.teacherid = l.teacherid"
                                ." left join %s m on t.phone = m.phone"
                                ." where  %s group by l.teacherid %s "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                ,t_test_lesson_subject_require::DB_TABLE_NAME
                                ,t_test_lesson_subject::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,t_manager_info::DB_TABLE_NAME
                                ,$where_arr
                                ,$limit_str
        );
        return $this->main_get_list($sql);
        // return $this->main_get_list_by_page($sql,$page_num,300,true);


    }

    public function get_lesson_info_teacher_tongji_jy_stu_num($start_time,$end_time,$is_full_time=-1,$teacher_money_type){
        $where_arr=[
            "lesson_type in (0,1,2,3)",
            "s.is_test_user = 0",
            "lesson_del_flag = 0",
            "l.teacherid>0",
            ["t.teacher_money_type=%d",$teacher_money_type,-1]
        ];

        if($is_full_time >=0){
            if($is_full_time == 1){ // 兼职老师
                $where_arr[] = "t.teacher_type not in(3,4) and (m.account_role not in(4,5) or m.account_role is null)";
            }else{ // 全职老师
                $where_arr[] = "m.account_role=5 and t.is_quit = 0 and t.trial_lecture_is_pass=1";
            }
        }

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);

        $sql=$this->gen_sql_new("select count(distinct l.userid) stu_num"
                                ." from %s l "
                                ." left join %s s on l.userid=s.userid "
                                ." left join %s tll on tll.lessonid = l.lessonid"
                                ." left join %s tlr on tlr.require_id = tll.require_id"
                                ." left join %s tls on tls.test_lesson_subject_id = tlr.test_lesson_subject_id"
                                ." left join %s t on t.teacherid = l.teacherid"
                                ." left join %s m on t.phone = m.phone"
                                ." where  %s "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
                                ,t_test_lesson_subject_require::DB_TABLE_NAME
                                ,t_test_lesson_subject::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,t_manager_info::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_value($sql);
        // return $this->main_get_list_by_page($sql,$page_num,300,true);


    }


    public function get_suc_test_by_userid($userid_arr){
        $where_arr = [
            'lesson_type = 2',
            'lesson_del_flag = 0',
            'lesson_user_online_status = 1',
        ];
        $this->where_arr_add_int_or_idlist($where_arr,'userid',$userid_arr);
        $sql = $this->gen_sql_new(
            " select lessonid,userid "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lessonid_list_by_userid($teacherid){
        $where_arr = [
            ["userid=%u",$teacherid,0]
        ];
        $sql = $this->gen_sql_new("select lessonid"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_test_lesson_info_for_jy($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "t.is_quit = 0",
            "t.is_test_user=0",

            // "m.account_role=2",
            // "m.del_flag=0",
            // "tss.success_flag in (0,1)",
            // "l.stu_attend >0 ",
            //"l.tea_attend>0",
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select  t.nick as account, t.train_through_new_time, l.teacherid,cur_require_adminid,count(l.lessonid) lesson_count,sum(tss.success_flag in (0,1) and l.lesson_user_online_status =1) suc_count,sum(if(o.orderid >0,1,0)) order_count,sum(o.price) all_price "
                                  ." from %s l left join %s tss on l.lessonid=tss.lessonid"
                                  ." left join %s t on t.teacherid = l.teacherid"
                                  ." left join %s tq on tss.require_id =tq.require_id"
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." left join %s o on l.lessonid = o.from_test_lesson_id"
                                  ." where %s group by t.teacherid",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }



    public function get_test_lesson_info_by_teacherid($teacherid, $start_time, $end_time){
        $where_arr=[
            ["l.teacherid=%d",$teacherid,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "m.account_role = 2"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new(" select distinct tq.cur_require_adminid "
                                  ." from %s l left join %s tss on l.lessonid=tss.lessonid"
                                  ." left join %s tq on tss.require_id =tq.require_id"
                                  ." left join %s m on m.uid = tq.cur_require_adminid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        $ret =  $this->main_get_list($sql);
        $arr=[];
        foreach($ret as $item){
            $arr[] = $item["cur_require_adminid"];

        }
        return $arr;
    }




    public function get_teacher_test_lesson_info_by_seller($start_time,$end_time,$seller_arr){
        $where_arr=[
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "(tss.success_flag in (0,1) or tss.success_flag is null)",
            "l.lesson_user_online_status =1",
            "m.del_flag=0"
        ];

        $this->where_arr_adminid_in_list($where_arr,"tq.cur_require_adminid",$seller_arr);

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(1) lesson_count,sum(if(o.orderid>0,1,0)) order_count from %s l".
                                  " left join %s tss on l.lessonid = tss.lessonid".
                                  " left join %s o on l.lessonid = o.from_test_lesson_id".
                                  " left join %s tq on tq.require_id = tss.require_id" .
                                  " left join %s ts on ts.test_lesson_subject_id =tq.test_lesson_subject_id ".
                                  " left join %s m on tq.cur_require_adminid = m.uid".
                                  " where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }


        public function get_teacher_test_lesson_order_info_new($start_time,$end_time,$adminid){
        $where_arr=[
            ["l.teacherid=%d",$adminid,-1],
            "l.lesson_type = 2",
            "l.lesson_del_flag = 0",
            "t.is_quit = 0",
            "tss.success_flag in (0,1)",
            "l.lesson_user_online_status =1",
            "t.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select distinct o.orderid,l.lesson_start,t.realname,l.subject,l.grade,s.nick,tq.test_lesson_order_fail_desc,l.lessonid"
                                  ." from %s l left join %s tss on l.lessonid=tss.lessonid"
                                  ." left join %s tq on tss.require_id =tq.require_id"
                                  ." left join %s m on tq.cur_require_adminid = m.uid"
                                  ." left join %s o on l.lessonid = o.from_test_lesson_id"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s s on l.userid = s.userid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_trial_train_no_pass_list($page_info,$start_time,$end_time,$subject,$is_test_user,$absenteeism_flag){
        $where_arr=[
            ["l.subject = %u",$subject,-1],
            ["t.is_test_user = %u",$is_test_user,-1],
            ["l.absenteeism_flag = %u",$absenteeism_flag,-1],
            "l.lesson_del_flag=0",
            "l.confirm_flag <2",
            "l.lesson_type=1100",
            "l.train_type=4",
            "l.trial_train_num=1",
            "tr.trial_train_status =2"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);

        $sql = $this->gen_sql_new("select t.realname,l.lesson_start,tr.add_time,l.absenteeism_flag,l.subject,l.teacherid,"
                                  ."tr.record_monitor_class, tr.record_info,tr.acc,tr.trial_train_status"
                                  ." from %s l left join %s tr on (l.lessonid = tr.train_lessonid and tr.type=1 and tr.lesson_style=5)"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
    public function get_trial_train_no_pass_list_b2($is_test_user){
        $where_arr=[
            ["t.is_test_user = %u",$is_test_user,-1],
            "l.lesson_del_flag=0",
            "l.confirm_flag <2",
            "l.lesson_type=1100",
            "l.train_type=4",
            "l.trial_train_num=1",
            "tr.trial_train_status =2",
            "t.train_through_new=0",
            "l.absenteeism_flag=0"
        ];
        $sql = $this->gen_sql_new("select l.teacherid "
                                  ." from %s l left join %s tr on (l.lessonid = tr.train_lessonid and tr.type=1 and tr.lesson_style=5)"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_record_list::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }

    public function get_last_trial_lesson($userid){
        $where_arr = [
            ["userid=%u",$userid,0],
            "lesson_type=2",
            "lesson_status=2",
            "lesson_del_flag=0"
        ];
        $sql = $this->gen_sql_new("select max(lesson_end)"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_data_for_qc($s, $e){ // 临时查询
        // parent_name

        $where_arr = [
            "l.lesson_user_online_status = 1",
            "tr.stu_advice = '' ",
            "l.lesson_type=2",
            "m.account_role = 2",
            "tl.success_flag =0",
            ["l.lesson_start>=%d",$s],
            ["l.lesson_end<%d",$e]
        ];


        $sql = $this->gen_sql_new(" select l.lessonid, tr.stu_advice, l.stu_performance, tl.success_flag, require_adminid, l.teacherid, m.account as seller_name, s.nick as stu_nick, l.userid, parent_name, t.realname as tea_name from %s l  "
                                  ." left join %s tl on tl.lessonid = l.lessonid "
                                  ." left join %s tr on tr.require_id = tl.require_id"
                                  ." left join %s ts on ts.test_lesson_subject_id = tr.test_lesson_subject_id"
                                  ." left join %s m on m.uid = ts.require_adminid "
                                  ." left join %s s on s.userid = l.userid"
                                  ." left join %s t on t.teacherid = l.teacherid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item['lessonid'];
        });
    }

    public function get_train_lesson($teacherid,$subject){
        $where_arr = [
            ["userid=%u",$teacherid,0],
            ["subject=%u",$subject,0],
        ];
        $sql = $this->gen_sql_new("select lessonid"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_succ_test_lesson($userid,$check_time = -1) {
        $where_arr=[
            ['l.lesson_type = %d ',2],
            ['l.lesson_del_flag = %d ',0],
            'l.confirm_flag in (0,1) ',
            "l.lesson_start > $check_time",
            "l.userid = $userid ",
        ];

        $sql= $this->gen_sql_new(
            " select l.lessonid , l.lesson_user_online_status ,l.lesson_end "
            . " from %s l "
            . " where %s order by  l.lesson_user_online_status  desc, l.lesson_start asc limit 1 ",
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_succ_test_lesson_count($userid,$check_time = -1) {
        $where_arr=[
            ['l.lesson_type = %d ',2],
            ['l.lesson_del_flag = %d ',0],
            'l.confirm_flag in (0,1) ',
            "l.userid = $userid ",
            'l.lesson_user_online_status = 1 ',
        ];

        $sql= $this->gen_sql_new(
            " select count(l.lessonid) count "
            . " from %s l "
            . " where %s   ",
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_first_test_lesson($userid){
        $where_arr=[
            ['l.lesson_type = %u ',2],
            ['l.lesson_del_flag = %u ',0],
            ['l.userid = %u',$userid],
            'l.confirm_flag in (0,1) ',
        ];

        $sql= $this->gen_sql_new(
            " select l.lessonid "
            . " from %s l "
            . " where %s order by l.lesson_start limit 1 ",
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_last_succ_test_lesson($userid){
        $where_arr=[
            ['l.lesson_type = %u ',2],
            ['l.lesson_del_flag = %u ',0],
            ['l.userid = %u',$userid],
            'l.confirm_flag in (0,1) ',
            'l.lesson_user_online_status = 1 ',
        ];

        $sql= $this->gen_sql_new(
            " select l.lessonid "
            . " from %s l "
            . " where %s order by l.lesson_start desc ",
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_train_lesson_before($lessonid,$subject,$grade,$teacherid){
        $sql = $this->gen_sql_new("select lessonid from %s"
                                  ." where lessonid <>%u and userid =%u and subject=%u and grade=%u "
                                  ." and lesson_status=0 and lesson_del_flag=0 and lesson_type=1100 and train_type=5",
                                  self::DB_TABLE_NAME,
                                  $lessonid,
                                  $teacherid,
                                  $subject,
                                  $grade
        );
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_info_for_time($lesson_begin, $lesson_end){  // 试听课开课前半个小时 通知

        $where_arr = [
            "l.lesson_type=2", //试听课
            "l.lesson_del_flag=0",
            ["l.lesson_start>%d",$lesson_begin],
            ["l.lesson_start<=%d",$lesson_end],
            "tss.test_lesson_fail_flag=0"
        ];

        $sql = $this->gen_sql_new(" select l.lessonid, m.phone as ass_phone, p.phone as par_phone, l.teacherid, l.subject, m.wx_openid as ass_openid, t.wx_openid as tea_openid, p.wx_openid as par_openid, l.lesson_start, l.lesson_end, t.nick as teacher_nick, l.userid, s.nick as stu_nick, p.nick as parent_nick from %s l "
                                  ." left join %s t on t.teacherid = l.teacherid "
                                  ." left join %s s on s.userid=l.userid "
                                  ." left join %s p on p.parentid= s.parentid "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." left join %s tr on tr.require_id = tss.require_id"
                                  ." left join %s ts on tr.test_lesson_subject_id = ts.test_lesson_subject_id"
                                  ." left join %s m on m.uid = ts.require_adminid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }



    public function get_test_lesson_num($start_time,$end_time){
        $where_arr=[
            "lesson_type = 2",
            "lesson_del_flag = 0",
            // "tss.success_flag in (0,1)",
            "lesson_user_online_status =1",
            //"t.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select teacherid,count(lessonid) num from %s where %s group by teacherid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_stu_lesson_money_info( $start_time,$end_time) {
        $sql=$this->gen_sql_new("select l.userid ,sum(o.price) price from  %s  l left join %s s on l.userid = s.userid"
                                ." left join %s o on l.lessonid = o.lessonid"
                                . " where s.is_test_user=0 and lesson_start >=%s and "
                                ."lesson_start<%s  and confirm_flag not in (2)  and lesson_type in (0,1,3)  "
                                . " and lesson_del_flag=0 "
                                ." group by l.userid ",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                t_order_lesson_list::DB_TABLE_NAME,
                                $start_time,
                                $end_time
        );

        return $this->main_get_list($sql,function($item){
            return $item["userid"];
        });
    }

    public function get_fulltime_teacher_train_lesson_list($page_info,$start_time,$end_time,$teacherid){
        $where_arr=[
            "l.lesson_type = 1100",
            "l.lesson_del_flag = 0",
            "l.train_type=7"
        ];

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select distinct l.lessonid,l.lesson_start,t.realname,l.lesson_name from %s l "
                                  ." left join %s ta on l.lessonid = ta.lessonid"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where %s and (l.teacherid = %u or ta.userid = %u)"
                                  ." group by lesson_start order by lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_train_lesson_user::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $teacherid,
                                  $teacherid
        );
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function get_open_lesson_info($start_time, $end_time)
    {
        $where_arr = [
            ['l.lesson_start>=%s', $start_time, 0],
            ['l.lesson_end<%s', $end_time, 0],
            'l.lesson_type=1001',
            'l.lesson_del_flag=0',
            'l.confirm_flag!=2',
            'l.jw_confirm_flag!=2',
            'co.packageid>0',
        ];
        $sql = $this->gen_sql_new("select count(distinct ol.userid) as num,l.subject,l.grade ,l.lessonid,"
                                  ." count( distinct lo.userid) as cur_num,l.lesson_start"
                                  ." from %s l"
                                  ." left join %s ol on ol.lessonid=l.lessonid"
                                  ." left join %s lo on lo.lessonid=l.lessonid"
                                  ." left join %s co on co.courseid=l.courseid"
                                  ." where %s"
                                  ." group by l.lessonid order by l.lesson_start"
                                  ,self::DB_TABLE_NAME
                                  ,t_open_lesson_user::DB_TABLE_NAME
                                  ,t_lesson_opt_log::DB_TABLE_NAME
                                  ,t_course_order::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_add_num_by_reference($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 1100",
            "l.lesson_del_flag = 0",
            "l.train_type=5"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct l.userid) lesson_add_num,ta.reference,tt.teacher_ref_type,c.channel_id,c.channel_name,tt.realname,tt.phone"
                                  ." from %s l left join %s t on l.userid = t.teacherid"
                                  ." left join %s ta on t.phone = ta.phone"
                                  ." left join %s tt on ta.reference = tt.phone"
                                  ." left join %s cg on tt.teacher_ref_type = cg.ref_type"
                                  ." left join %s c on cg.channel_id = c.channel_id"
                                  ." where %s group by ta.reference",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
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

    public function get_lesson_add_num_by_reference_detail($start_time,$end_time){
        $where_arr=[
            "l.lesson_type = 1100",
            "l.lesson_del_flag = 0",
            "l.train_type=5"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select distinct l.userid,ta.reference"
                                  ." from %s l left join %s t on l.userid = t.teacherid"
                                  ." left join %s ta on t.phone = ta.phone"
                                  ." left join %s tt on ta.reference = tt.phone"
                                  ." left join %s cg on tt.teacher_ref_type = cg.ref_type"
                                  ." left join %s c on cg.channel_id = c.channel_id"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_teacher_lecture_appointment_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_admin_channel_group::DB_TABLE_NAME,
                                  t_admin_channel_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }




    public function get_no_time_train_lesson_teacher_list(){
        $sql = $this->gen_sql_new("select distinct l.teacherid,t.realname,t.phone,t.wx_openid "
                                  ." from %s l left join %s ll on (l.teacherid=ll.teacherid and ll.lesson_del_flag=0 and ll.lesson_start>0 and ll.lesson_type=1100 and ll.train_type=4)"
                                  ." left join %s t on l.teacherid = t.teacherid"
                                  ." where l.lesson_start=0 and l.lesson_del_flag=0 and l.lesson_type=1100 and l.train_type=4 and ll.lessonid is null and t.is_test_user=0",
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME
        );
        return $this->main_get_list_as_page($sql);

    }


    public function get_lesson_list_for_minute(){
        $now = time();
        $next = time()+60;

        $where_arr = [
            "l.lesson_type=2", //试听课
            "l.lesson_del_flag=0",
            ["l.lesson_start>%d",$now],
            ["l.lesson_start<=%d",$next],
            "tss.test_lesson_fail_flag=0"

        ];

        $sql = $this->gen_sql_new(" select l.lessonid, l.teacherid, l.subject, m.wx_openid as ass_openid, t.wx_openid as tea_openid, p.wx_openid as par_openid, l.lesson_start, l.lesson_end, t.nick as teacher_nick, l.userid, s.nick as stu_nick, p.nick as parent_nick from %s l "
                                  ." left join %s t on t.teacherid = l.teacherid "
                                  ." left join %s s on s.userid=l.userid "
                                  ." left join %s p on p.parentid= s.parentid "
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_test_lesson_to_notic(){
        $today = strtotime(date('Y-m-d'));

        $where_arr = [
            "l.lesson_type=2", //试听课
            "l.lesson_del_flag=0",
            "l.lesson_status=1",
            "l.confirm_flag<2",
            ["l.lesson_start>%d",$today],
            "tss.test_lesson_fail_flag=0"

        ];

        $sql = $this->gen_sql_new(" select l.tea_late_minute, l.stu_late_minute, l.lessonid, l.teacherid, l.subject, m.wx_openid as ass_openid, t.wx_openid as tea_openid, p.wx_openid as par_openid, l.lesson_start, l.lesson_end, t.nick as teacher_nick, l.userid, s.nick as stu_nick, p.nick as parent_nick from %s l "
                                  ." left join %s t on t.teacherid = l.teacherid "
                                  ." left join %s s on s.userid=l.userid "
                                  ." left join %s p on p.parentid= s.parentid "
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }



    public function get_tea_openid_for_update_software(){
        $sql = $this->gen_sql_new(" select wx_openid from %s l");
    }

    public function cancel_lesson_no_start($lessonid){
        $where_arr = [
            ["lessonid=%u",$lessonid,-1],
            "lesson_status=0"
        ];
        $sql = $this->gen_sql_new("update %s set lesson_del_flag=1"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function grade_lesson_count($lesson_start,$lesson_end){
        $where_arr = [
            ["lesson_start>%u",$lesson_start,0],
            ["lesson_start<%u",$lesson_end,0],
            "lesson_type in (0,1,3) ",
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("select grade, sum(lesson_count) as sum "
                                  ." from %s "
                                  ." where %s "
                                  ." group by grade order by grade asc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_list($sql);
    }
    public function get_lesson_student_count_info($lesson_start, $lesson_end,$subject) {
        $where_arr = [
            ["lesson_start>=%u",$lesson_start,0],
            ["lesson_start<%u",$lesson_end,0],
            "subject=$subject",
            "lesson_type in (0,1,3) ",
            "lesson_del_flag=0",
            "is_test_user=0",
        ];

        $sql = $this->gen_sql_new(
            "select count( l.lessonid ) as lesson_nums,l.subject"
            ." from %s l force index(lesson_type_and_start)"
            ." left join  %s s on s.userid=l.userid"
            ." where %s "
            ." group by l.userid"
            ." order by lesson_nums"
            ,self::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_confirm_lesson_list_new($start_time,$end_time) {
        $where_arr = [
            ["lesson_start>=%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type in (0,1,3) ",
            "lesson_del_flag=0",
            "is_test_user=0",
            "lesson_status =2",
            "confirm_flag not in (2,3)"
        ];

        $sql=$this->gen_sql_new("select l.assistantid ,sum(lesson_count) as lesson_count,count(*) as count, "
                                ."count(distinct l.userid ) as user_count,a.nick assistant_nick "
                                ."from  %s  l left join %s s on l.userid = s.userid "
                                ."left join %s a on l.assistantid = a.assistantid "
                                ." where %s "
                                ." group by a.assistantid  order by lesson_count desc",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                $where_arr
        );

        return $this->main_get_list_as_page($sql);
    }


    public function get_lesson_info_by_lessonid($lessonid){
        $where_arr=[
            ["lessonid=%u",$lessonid,0],
            "lesson_type <1000"
        ];

        $sql = $this->gen_sql_new("  select t.nick as tea_nick, p.wx_openid, l.lesson_start, l.lesson_end, l.subject,"
                                  ." s.nick as stu_nick,l.userid "
                                  ." from %s l"
                                  ." left join %s s on s.userid=l.userid "
                                  ." left join %s p on p.parentid=s.parentid "
                                  ." left join %s t on t.teacherid=l.teacherid "
                                  ." where l.lessonid=%d "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_row($sql);
    }

    public function get_lessonid_by_teacherid($start_time, $end_time, $teacherid) {

        $where_arr = [
            // ["lesson_start>=%s", $start_time, 0],
            // ["lesson_start<%s", $end_time, 0],
            "teacherid in $teacherid",
            "lesson_type=1001",
        ];

        $sql = $this->gen_sql_new(
            "  select lessonid,grade"
            . " from %s "
            . " where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_teacher($sql){
            return $this->main_get_list($sql);
    }

    public function get_seller_week_lesson_new($start_time,$end_time,$adminid){
        $where_arr=[
            ["l.lesson_type=%u",2,-1],
            ["ls.require_adminid = %u",$adminid,-1],
        ];
        // $this->where_arr_add_time_range($where_arr,'lsl.set_lesson_time',$start_time,$end_time);
        $this->where_arr_add_time_range($where_arr,'l.lesson_end',$start_time,$end_time);
        $sql = $this->gen_sql_new(" select l.lessonid,l.userid,l.lesson_start,l.lesson_end,l.lesson_del_flag,"
                                  ."lsl.call_before_time,lsl.call_end_time,set_lesson_time,"
                                  ."ls.require_adminid adminid,ls.require_adminid"
                                  ." from %s l "
                                  ." left join %s lsl on lsl.lessonid=l.lessonid "
                                  ." left join %s lsr on lsr.require_id=lsl.require_id "
                                  ." left join %s ls on ls.test_lesson_subject_id=lsr.test_lesson_subject_id "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,//l
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,//lsl
                                  t_test_lesson_subject_require::DB_TABLE_NAME,//lsr
                                  t_test_lesson_subject::DB_TABLE_NAME,//ls
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_week_lesson_row($start_time,$end_time,$adminid){
        $where_arr=[
            ["l.lesson_type=%u",2,-1],
            ["lsl.set_lesson_time >= %u",$start_time,-1],
            ["lsl.set_lesson_time < %u",$end_time,-1],
            ["ls.require_adminid = %u",$adminid,-1],
        ];

        $sql = $this->gen_sql_new(" select l.lessonid,l.userid,l.lesson_start,l.lesson_end,l.lesson_del_flag,"
                                  ."lsl.call_before_time,lsl.call_end_time,"
                                  ."ls.require_adminid adminid,ls.require_adminid"
                                  ." from %s l "
                                  ." left join %s lsl on lsl.lessonid=l.lessonid "
                                  ." left join %s lsr on lsr.require_id=lsl.require_id "
                                  ." left join %s ls on ls.test_lesson_subject_id=lsr.test_lesson_subject_id "
                                  ." where %s limit 1",
                                  self::DB_TABLE_NAME,//l
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,//lsl
                                  t_test_lesson_subject_require::DB_TABLE_NAME,//lsr
                                  t_test_lesson_subject::DB_TABLE_NAME,//ls
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_lesson_time_row($lessonid){
        $sql = $this->gen_sql_new("select lesson_start, lesson_end from %s where lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_row($sql);
    }


    public function get_common_lesson_info_for_time($lesson_begin, $lesson_end){  // 常规课开课前半个小时 通知
        $where_arr = [
            "l.lesson_type in (0,1,3)", //常规课
            "l.lesson_del_flag=0",
            "t.is_test_user=0",
            "l.confirm_flag<2",
            "p.parentid>0"
        ];

        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$lesson_begin,$lesson_end);

        $sql = $this->gen_sql_new(" select m.uid, m.account as ass_nick,l.lessonid, m.phone as ass_phone, p.phone as par_phone, l.teacherid, l.subject, m.wx_openid as ass_openid, t.wx_openid as tea_openid, p.wx_openid as par_openid, l.lesson_start, l.lesson_end, t.nick as teacher_nick, l.userid, s.nick as stu_nick, p.nick as parent_nick from %s l "
                                  ." left join %s t on t.teacherid = l.teacherid "
                                  ." left join %s s on s.userid=l.userid "
                                  ." left join %s p on p.parentid= s.parentid "
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_common_lesson_list_for_minute(){
        $now = time();
        $next = time()+60;

        $where_arr = [
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag<2",
            "p.parentid>0"
        ];

        $this->where_arr_add_time_range($where_arr,'lesson_start',$now, $next);

        $sql = $this->gen_sql_new(" select l.lessonid, l.teacherid, l.subject, m.wx_openid as ass_openid, t.wx_openid as tea_openid, p.wx_openid as par_openid, l.lesson_start, l.lesson_end, t.nick as teacher_nick, l.userid, s.nick as stu_nick, p.nick as parent_nick from %s l "
                                  ." left join %s t on t.teacherid = l.teacherid "
                                  ." left join %s s on s.userid=l.userid "
                                  ." left join %s p on p.parentid= s.parentid "
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_need_late_notic(){

        $today = strtotime(date('Y-m-d'));

        $where_arr = [
            "l.lesson_del_flag=0",
            "l.lesson_type in (0,1,3)",
            "l.confirm_flag<2",
            "l.lesson_status=1",
            "l.lesson_start>$today",
        ];

        $sql = $this->gen_sql_new(" select l.stu_late_minute, l.tea_late_minute, l.lessonid, l.teacherid, l.subject, m.wx_openid as ass_openid, t.wx_openid as tea_openid, p.wx_openid as par_openid, l.lesson_start, l.lesson_end, t.nick as teacher_nick, l.userid, s.nick as stu_nick, p.nick as parent_nick from %s l "
                                  ." left join %s t on t.teacherid = l.teacherid "
                                  ." left join %s s on s.userid=l.userid "
                                  ." left join %s p on p.parentid= s.parentid "
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_parent_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_teacher_lesson_list_www_new($teacherid,$userid,$start_time,$end_time,$lesson_type_in_str)
    {
        $where_arr = [
            ["lesson_start>=%d",$start_time, -1 ] ,
            ["lesson_start<=%d",$end_time, -1 ] ,
            ["l.lesson_type in (%s)",$lesson_type_in_str, "" ] ,
            ["l.userid=%u",$userid, -1 ] ,
            ["l.teacherid=%u",$teacherid, 0 ] ,
            "l.lesson_del_flag=0",
        ];

        $sql =$this->gen_sql_new("select t.test_lesson_subject_id,l.lessonid,l.lesson_type,lesson_start,lesson_end,"
                                 ." lesson_intro,l.grade,l.subject,l.confirm_flag,l.assistantid,ta.phone as ass_phone,"
                                 ." l.lesson_num,l.userid,lesson_name,lesson_status,ass_comment_audit,l.userid,"
                                 ." if(h.work_status>0,1,0) as homework_status,stu_cw_status as stu_status,"
                                 ." tea_cw_status as tea_status, editionid,t.textbook,l.train_type, "
                                 ." h.finish_url,h.check_url,l.tea_cw_url,l.tea_cw_upload_time,l.tea_cw_pic_flag,l.tea_cw_pic,l.tea_cw_origin,l.stu_cw_origin,l.tea_cw_file_id ,l.stu_cw_file_id,  "
                                 ." l.stu_cw_url,l.stu_cw_upload_time,h.issue_url,h.issue_time,"
                                 ." h.pdf_question_count ,tea_more_cw_url,  "
                                 ." t.stu_test_paper,t.require_adminid, "
                                 ." tm.name as cc_account,tm.phone as cc_phone,"
                                 ." tr.accept_adminid,"
                                 ." t.stu_request_test_lesson_demand,"
                                 ." jm.name jw_name,jm.phone jw_phone,s.address,n.interests_and_hobbies, "
                                 ." n.character_type ,n.need_teacher_style,n.extra_improvement,n.habit_remodel ,"
                                 ." n.study_habit, s.nick "
                                 ." from %s l "
                                 ." left join %s h on l.lessonid=h.lessonid "
                                 ." left join %s s on l.userid=s.userid"
                                 ." left join %s tr on l.lessonid=tr.current_lessonid"
                                 ." left join %s t on tr.require_id=t.current_require_id"
                                 ." left join %s tm on t.require_adminid=tm.uid"
                                 ." left join %s ta on l.assistantid=ta.assistantid"
                                 ." left join %s jm on tr.accept_adminid = jm.uid"
                                 ." left join %s n on l.userid = n.userid"
                                 ." where %s"
                                 ." and confirm_flag!=2"
                                 ." and l.lesson_type!=4001"
                                 ." order by lesson_start ",
                                 self::DB_TABLE_NAME ,
                                 t_homework_info::DB_TABLE_NAME ,
                                 t_student_info::DB_TABLE_NAME ,
                                 t_test_lesson_subject_require::DB_TABLE_NAME ,
                                 t_test_lesson_subject::DB_TABLE_NAME ,
                                 t_manager_info::DB_TABLE_NAME,
                                 t_assistant_info::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 t_seller_student_new::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['lessonid'];
        });
    }

    public function get_lesson_stu_performance($lessonid){
        $sql=$this->gen_sql("select stu_performance,lesson_intro "
                            ." from %s"
                            ." where lessonid=%u"
                            ,self::DB_TABLE_NAME
                            ,$lessonid
        );
        return $this->main_get_row($sql);
    }


    //@desn:获取销售成功试听未回访用户数量
    public function get_call_end_time_num_by_adminid($adminid){
        $time = time()-24*3600;
        $where_arr = [
            ' l.tea_attend <> 0 ',
            ' l.stu_attend <> 0 ',
            ' l.lesson_type = 2 ',
            ' l.lesson_del_flag = 0 ',
            ' l.confirm_flag <2 ',
            ' l.lesson_user_online_status = 1 ',
            ' l.lesson_end > 1503244800 ',
            ' l.lesson_end <  '.$time,
            ' lss.call_end_time = 0 ',
            ' lss.success_flag in (0,1) ',
            [' lsr.cur_require_adminid = %d ',$adminid],
        ];
        $sql = $this->gen_sql_new(
            " select l.userid,si.phone "
            ." from %s l "
            ." left join %s lss on lss.lessonid = l.lessonid "
            ." left join %s lsr on lsr.require_id = lss.require_id "
            .' join %s si on l.userid = si.userid '
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }
    public function get_teacher_lesson_total($teacherid,$start_time,$end_time){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            ["lesson_end>%u",$start_time,-1],
            ["lesson_end<%u",$end_time,-1],
        ];
        $sql = $this->gen_sql_new(" select count(*) as total "
                                ." from %s "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,$where_arr);
        return $this->main_get_value($sql);
    }

}
