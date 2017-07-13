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
        $others_arr[] ="confirm_flag<2" ;
        return $others_arr;
    }

    public function get_test_lesson_first_list(
        $page_num,$order_by_str,$start_time,$end_time,$require_adminid_list,$lesson_user_online_status
    ){
        $where_arr=[
            "s.is_test_user=0" ,
            "l.lesson_del_flag=0" ,
            "l.lesson_type=2" ,
            "l.lesson_start ",
        ];
        $where_arr[] = $this->where_get_in_str("tr.cur_require_adminid",$require_adminid_list);
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $this->where_arr_add_int_field($where_arr,"lesson_user_online_status", $lesson_user_online_status);

        $sql= $this->gen_sql_new(
            ["select cur_require_adminid ,s.userid, s.nick, n.phone, l.lessonid, l.teacherid,  l.lesson_start,l.lesson_end ,min(tq.start_time)  tq_call_time ,  (min(tq.start_time) -l.lesson_start ) as  duration ,price , max(tq.start_time)  last_tq_call_time , o.order_time, count(tq.start_time ) tq_call_count , sum(tq.duration ) as tq_call_all_time , n.phone , l.lesson_user_online_status ",
             " from %s l  ",
             " left join %s  s on s.userid=l.userid ",
             " left join %s  n on n.userid=l.userid ",
             " left join %s  tts on tts.lessonid=l.lessonid ",
             " left join %s  tr on tr.require_id=tts.require_id ",
             " left join %s  tq on (n.phone=tq.phone and tq.start_time > l.lesson_start )",
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
        ];
        if (!$always_reset) {
            $where_arr[]=" lesson_user_online_status =0 ";
        }

        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select lessonid ,userid, teacherid,lesson_start from %s ".
            " where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }
    public function get_list_by_parent_id( $parentid,$lessonid=-1) {
        $check_lesson_time=time(NULL)-180*86400;
        $where_arr=[
            ["pc.parentid = %u", $parentid, -1 ],
            ["l.lessonid= %u", $lessonid, -1 ],
            "lesson_type=2", //试听
            "lesson_del_flag=0",
            "lesson_start>$check_lesson_time", //试听
        ];

        //`ass_comment_audit` tinyint(4) NOT NULL DEFAULT '0' COMMENT '助教对老师评价的审核状态0未评价1未审批 2未通过 3已经通过',
        $sql = $this->gen_sql_new(
            "select  tls.test_lesson_subject_id,tls.stu_lesson_pic,l.lessonid,lesson_start,lesson_end,l.teacherid,l.userid,l.subject,l.grade,"
            ." ass_comment_audit,tl.level as parent_report_level,lesson_status, tss.parent_confirm_time, "
            ." lesson_type,lesson_num"
            ." from %s l "
            ." join %s pc on l.userid = pc.userid "
            ." left join %s tl on  (tl.lessonid = l.lessonid  and label_origin =1 ) "
            ." left join %s tss  on  tss.lessonid= l.lessonid "
            ." left join %s tsr  on  tsr.require_id = tss.require_id "
            ." left join %s tls  on  tls.test_lesson_subject_id= tsr.test_lesson_subject_id "
            ." where %s  order by lesson_start desc "
            ,self::DB_TABLE_NAME
            ,t_parent_child::DB_TABLE_NAME
            ,t_teacher_label::DB_TABLE_NAME
            ,t_test_lesson_subject_sub_list::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_test_lesson_subject::DB_TABLE_NAME
            ,$where_arr
        );

        \App\Helper\Utils::logger('tupian1'.$sql);


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
        $sql = $this->gen_sql_new(
            "select lessonid, c.courseid,lesson_num,lesson_type,real_begin_time,real_end_time, l.teacherid , current_server, lesson_start, lesson_end ,server_type"
            . " from %s l, %s c ".
            " where l.courseid = c.courseid and lesson_upload_time = 0 and lesson_status = 2 and real_begin_time != 0 and lesson_type != 4001 and   ".
            "  lesson_start > %u  and lesson_start <%u and  lesson_end<%u and lesson_del_flag=0 order by   gen_video_grade desc,   lesson_start asc ",
            self::DB_TABLE_NAME ,
            t_course_order::DB_TABLE_NAME ,
            $now - 86400*3, $now+3600*5 , $now );
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
            "lesson_user_online_status = 2"
        ];

        $sql=$this->gen_sql_new("select lessonid, courseid "
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

    public function get_qz_tea_lesson_info($start_time,$end_time){
        $where_arr=[
            "m.account_role in (4,5)",
            "m.del_flag=0",
            "l.lesson_del_flag=0",
            "l.confirm_flag in (0,1)",
            "(tss.success_flag is null or tss.success_flag in (0,1))"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select lesson_type,lesson_count,tss.success_flag,m.uid,m.account_role,train_type "
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
            "l.lesson_start <".$lesson_start,
            "l.lesson_end >".$lesson_end
        ];
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
    public function check_off_time_lesson_start($teacherid,$lesson_end,$lesson_start){
        $where_arr=[
            "m.account_role=5",
            "m.del_flag=0",
            "l.lesson_del_flag=0",
            "l.confirm_flag in (0,1)",
            "(tss.success_flag is null or tss.success_flag in (0,1))",
            "l.teacherid=".$teacherid,
            "l.lesson_end >".$lesson_end,
            "l.lesson_start <".$lesson_start,
        ];
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
            "confirm_flag in (0,1)",
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
        $sql = $this->gen_sql_new("select count(distinct l.userid) person_num,count(l.lessonid) lesson_num,l.teacherid "
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

        $sql = $this->gen_sql_new(" select l.lessonid,l.lesson_start,l.userid,s.nick as stu_nick,s.phone as stu_phone,l.teacherid,t.nick as tea_nick,l.lesson_start,l.lesson_end,ls.require_adminid adminid,lsl.call_before_time,lsl.call_end_time,ls.require_adminid,m.account "
                                  ." from %s l "
                                  ." left join %s lsl on lsl.lessonid=l.lessonid "
                                  ." left join %s lsr on lsr.require_id=lsl.require_id "
                                  ." left join %s ls on ls.test_lesson_subject_id=lsr.test_lesson_subject_id "
                                  ." left join %s m on m.uid=ls.require_adminid "
                                  ." left join %s t on t.teacherid=l.teacherid "
                                  ." left join %s s on s.userid=l.userid "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
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
        $page_num,$start_time,$end_time,$lesson_status,$teacherid,$subject,$grade,$check_status,$train_teacherid,$lessonid=-1,$res_teacherid=-1,$have_wx=-1,$lecture_status=-1,$opt_date_str=-1,$train_email_flag=-1
    ){
        $where_arr = [
            //  ["l.lesson_start>%u",$start_time,0],
            //  ["l.lesson_start<%u",$end_time,0],
            ["l.lesson_status=%u",$lesson_status,-1],
            ["l.subject=%u",$subject,-1],
            ["l.grade=%u",$grade,-1],
            ["l.teacherid=%u",$teacherid,-1],
            ["l.teacherid=%u",$res_teacherid,-1],
            ["tl.userid=%u",$train_teacherid,-1],
            ["l.train_email_flag=%u",$train_email_flag,-1],
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=5",
        ];
        if($check_status==-1){
            $where_arr[] = "trial_train_status is null";
        }else{
            $where_arr[] = ["trial_train_status=%u",$check_status,-2];
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

        if($lessonid >0){
            $where_arr=[
                ["l.lessonid = %u",$lessonid,-1]
            ];
        }

        $sql = $this->gen_sql_new("select l.lessonid,l.lesson_start,l.lesson_end,l.lesson_name,l.audio,l.draw,l.grade,l.subject,"
                                  ." l.lesson_status,t.teacherid,t.nick,t.phone_spare,t.user_agent,l.teacherid as l_teacherid,"
                                  ." if(tr.trial_train_status is null,-1,tr.trial_train_status) as trial_train_status,tr.acc,"
                                  ." t.phone_spare,tli.id as lecture_status,tt.teacherid real_teacherid,m.account,"
                                  ." l.real_begin_time,tr.record_info,t.identity,tl.add_time,t.wx_openid,l.train_email_flag ,"
                                  ." if(tli.status is null,-2,tli.status) as lecture_status_ex,tr.id access_id  "
                                  ." from %s l"
                                  ." left join %s tl on l.lessonid=tl.lessonid"
                                  ." left join %s t on tl.userid=t.teacherid"
                                  ." left join %s tr on l.lessonid=tr.train_lessonid"
                                  ." left join %s tli on t.phone_spare=tli.phone"
                                  ." left join %s tt on t.phone_spare=tt.phone"
                                  ." left join %s ttt on l.teacherid=ttt.teacherid"
                                  ." left join %s m on ttt.phone = m.phone "
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
            ["t.train_through_new=%u",$train_through_new,-1]
        ];
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

    public function get_all_train_num_real($start_time,$end_time,$teacher_list,$train_through_new,$flag=false){
        $where_arr = [
            "l.train_type=1",
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
            "train_type=1",
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
                                  "where l.teacherid = %d and l.lesson_start>= %d and l.lesson_end < %d and l.lesson_type in (%s) and confirm_flag<2 ".
                                  "order by l.lesson_start desc",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $teacherid, $start_time,
                                  $end_time, $lesson_type_list_str,time(NULL));

        return $this->main_get_list_by_page($sql,$page_num,10);

    }

    /**

       "from t_lesson_info l, t_student_info s " +
       "where l.userid = s.userid and l.teacherid = %d and l.lesson_start>= %d and l.lesson_end < %d and l.lesson_type in (%s) " +
       "order by l.lesson_start desc",


     **/



    // public function get_comment_list_by_page ( $teacherid, $start_time,$end_time,$lesson_type_list_str, $page_num) {
    //     $sql = $this->gen_sql_new("select l.lessonid,l.confirm_flag,l.stu_attend, l.lesson_type, subject,lesson_name, l.grade, lesson_start,lesson_end, nick, tea_rate_time ".
    //                               "from %s l left join %s s on l.userid = s.userid ".
    //                               "where l.teacherid = %d and l.lesson_start>= %d and l.lesson_end < %d and l.lesson_type in (%s) and l.lesson_type=2 and l.confirm_flag<2 and l.lesson_start< %d and l.stu_attend<>0 ".
    //                               "order by l.lesson_start desc",
    //                               self::DB_TABLE_NAME,
    //                               t_student_info::DB_TABLE_NAME,
    //                               $teacherid, $start_time,
    //                               $end_time, $lesson_type_list_str,time(NULL));

    //     return $this->main_get_list_by_page($sql,$page_num,10);

    // }



    public function get_teacher_lessons($teacherid, $start_time, $end_time) {
        $sql = $this->gen_sql_new( " select lesson_start, lesson_end,free_time_new from %s tl".
                                   " left join %s tf on tf.teacherid = tl.teacherid".
                                   " where tl.teacherid = %d and lesson_start >= %s and lesson_end < %s",
                                   self::DB_TABLE_NAME,
                                   t_teacher_freetime_for_week::DB_TABLE_NAME,
                                   $teacherid,
                                   $start_time,
                                   $end_time
        );
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_list($start_time,$end_time,$userid = -1){
        $where_arr = [
            ["l.lesson_type=%u",2],
            ["l.lesson_del_flag=%u",0],
            ["l.lesson_start>=%u",$start_time],
            ["l.lesson_start<%u",$end_time],
            ["l.userid=%u",$userid,-1],
        ];
        $sql = $this->gen_sql_new("select l.lessonid,l.lesson_start,l.lesson_end,m.tquin,n.phone "
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
        if(count($lesson_arr)>0){
            $this->update_lesson_call($lesson_arr);
        }
    }

    public function update_lesson_call($lesson_arr){
        foreach($lesson_arr as $item){
            $lessonid     = $item['lessonid'];
            $tquin        = (int)$item['tquin'];
            $phone        = $item['phone'];
            $lesson_start = $item['lesson_start'];
            $day_start    = date('Y-m-d',$lesson_start);
            $lesson_time  = strtotime($day_start."00:00:00");
            $middle_time  = strtotime($day_start.'12:00:00');
            if($lesson_start <= $middle_time){
                $call_start_time = $lesson_time - 12*3600;
                $call_end_time   = $lesson_time + 24*3600;
            }else{
                $call_start_time = $lesson_time;
                $call_end_time   = $lesson_time + 24*3600;
            };
            $lesson_call_list = $this->task->t_tq_call_info->get_list_ex($tquin,$phone,$call_start_time,$call_end_time,1);
            $call_before_time_arr = [];
            $call_end_time_arr = [];
            $call_before_time = 0;
            $call_end_time = 0;
            foreach($lesson_call_list as $time_item){
                $call_time = $time_item["start_time"] ;
                if($call_time < $lesson_start){
                    $call_before_time_arr[] =$call_time;
                }elseif($call_time > ($lesson_start+1800)) {
                    $call_end_time_arr[] = $call_time;
                }
            }
            if(count($call_before_time_arr)>0){
                $call_before_time = max($call_before_time_arr);
            }
            if(count($call_end_time_arr)>0){
                $call_end_time = min($call_end_time_arr);
            }
            $this->task->t_test_lesson_subject_sub_list->field_update_list($lessonid, [
                "call_before_time" => $call_before_time,
                "call_end_time"    => $call_end_time,
            ]);

        }
    }

    public function set_stu_performance( $lessonid, $teacherid, $stu_performance, $ass_comment_audit) {
        $sql = $this->gen_sql_new("update %s t set t.stu_performance = '%s', t.ass_comment_audit = %d ".
                                  "where t.lessonid = %d and t.teacherid = %d",
                                  self::DB_TABLE_NAME, $stu_performance,
                                  $ass_comment_audit, $lessonid, $teacherid);
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

    public function get_lesson_count_by_userid($userid){
        $where_arr = [
            ['lesson_del_flag=%d',0],
            ['lesson_status=%d',2],
            ['lesson_type = %d',0],
            ['userid = %d',$userid],
            'confirm_flag in (0,1)',
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
            "m.del_flag=0"            
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
            "tt.user_agent not like '%%3.2.0%%' and tt.user_agent not like '%%5.0.4%%'",
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

    public function get_lesson_total_list($start_time,$end_time,$teacher_money_type){
        $where_arr = [
            ["l.lesson_start>%u",$start_time,0],
            ["l.lesson_start<%u",$end_time,0],
            ["t.teacher_money_type in (%s)",$teacher_money_type,""],
            "is_test_user=0",
        ];
        $where_arr = $this->lesson_common_where_arr($where_arr);
        $sql = $this->gen_sql_new("select t.teacherid,t.teacher_money_type,t.teacher_ref_type,t.nick,"
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

    public function get_test_lesson_count_by_userid($userid){
        $where_arr = [
            ['userid = %d',$userid],
            ['lesson_type=%d',2],
            ['lesson_del_flag=%d',0],
            'confirm_flag in (0,1)',
        ];
        $sql = $this->gen_sql_new(
            "select count(lessonid) count"
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_all_train_interview_lesson_info($time){
        $where_arr=[
            "l.lesson_type=1100",
            "l.lesson_sub_type=1",
            "l.train_type=5",
            "l.lesson_del_flag=0",
            "l.confirm_flag <2",
            "l.train_email_flag=0",
            "l.lesson_start>".$time
        ];
        $sql = $this->gen_sql_new("select l.lessonid,t.phone_spare,l.train_email_flag,t.realname,l.lesson_start,l.lesson_end,t.teacherid "
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

    public function get_grade_wages_list($start_time,$end_time,$full_flag){
        $where_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
        ];
        if($full_flag==0){
            $where_arr[] = "t.teacher_type!=3 or t.teacherid in (51094,99504,97313)"
        }else{
            $where_arr[] = "t.teacher_type=3 and t.teacherid not in (51094,99504,97313)"
        }
        $sql = $this->gen_sql_new("select l.lesson_count,l.lesson_type,l.grade,sum(o.price) as lesson_price,m.money"
                                  ." from %s l"
                                  ." left join %s o on l.lessonid=o.lessonid"
                                  ." left join %s t on l.teacherid=t.teacherid"
                                  ." left join %s m on l.level=m.level "
                                  ." and m.grade=(case when "
                                  ." l.competition_flag=1 then if(l.grade<200,203,303) "
                                  ." else l.grade"
                                  ." end )"
                                  ." and l.teacher_money_type=m.teacher_money_type"
                                  ." left join %s"
                                  ." where %s"
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


}