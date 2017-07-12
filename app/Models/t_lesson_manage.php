<?php
namespace App\Models;

class t_lesson_manage extends \App\Models\Zgen\z_t_login_log
{
    //type 课程类型
    public function lesson_account($lesson_type,$start_date_s,$end_date_s,$teacherid,$page_num){
        $str='true';
        if($lesson_type==0) {
            $str="lesson_type < 3000 and lesson_type >1000";
        }elseif($lesson_type==1){
            $str="lesson_type = 3001";
        }elseif($lesson_type==2){
            $str="lesson_type <1000";
        }

        if($teacherid>0){
            $str.=sprintf(" and t.teacherid=%d",$teacherid);
        }

        $sql = sprintf ("select "
                        ."l.lessonid,l.courseid,l.userid,l.lesson_type,l.lesson_start,l.lesson_end,t.nick as tea_nick "
                        ."from db_weiyi.t_lesson_info as l,db_weiyi.t_teacher_info as t "
                        ."where %s "
                        ."and l.teacherid = t.teacherid "
                        ."and lesson_start > %s "
                        ."and lesson_end < %s "
                        ."order by lesson_start asc"
                        ,$str
                        ,$this->ensql($start_date_s)
                        ,$this->ensql($end_date_s)
        );
        if($page_num){
            return $this->main_get_list_by_page($sql,$page_num,10);
        }else{
            return $this->main_get_list($sql);
        }
    }


    public function get_schedule_by_week($userid, $timestamp)
    {
        // $ret_week = get_week_start_end($timestamp);


        $week = intval(date('w', $timestamp));
        if ($week==0){ //周日
            $week=7;
        }

        $time_given = strtotime(date('Y-m-d', $timestamp) . " 00:00:00");

        $ret_week['start'] = $time_given - ($week-1)  * 24 * 60 * 60;
        $ret_week['end'] = $ret_week['start'] + 7 * 24 * 60 * 60;



        if ($ret_week === false) {
            return false;
        }

        $sql = sprintf("select lessonid, courseid, lesson_num, lesson_type, userid, teacherid, assistantid,"
                       ." has_quiz, lesson_start, lesson_end, lesson_intro, lesson_status "
                       ." from %s "
                       ." where userid = %u "
                       ." and lesson_start > %u "
                       ." and lesson_end < %u"
                       ." and confirm_flag!=2"
                       ." and lesson_del_flag=0"
                       ,self::DB_TABLE_NAME
                       ,$userid
                       ,$ret_week['start']
                       ,$ret_week['end']
        );
        return $this->main_get_list($sql);
    }


    public function get_schedule_by_month($userid, $timestamp)
    {
        // $ret_month = get_month_start_end($timestamp);

        $year  = intval(date('Y', $timestamp));
        $month = intval(date('m', $timestamp));
        $month_start = strtotime($year."-"."$month"."-01 00:00:00");
        if ($month == 12) {
            $year = $year + 1;
            $month_end = strtotime( $year .'-1-1 00:00:00');
        } else {
            $month = $month + 1;
            $month_end = strtotime($year . '-' . $month . '-1 00:00:00');
        }

        $ret_month['start'] = $month_start;
        $ret_month['end']   = $month_end;


        if ($ret_month === false) {
            return false;
        }

        $sql = sprintf("select lessonid, courseid, lesson_num, lesson_type, userid, teacherid, assistantid,"
                       ." has_quiz, lesson_start, lesson_end, lesson_intro, lesson_status "
                       ." from %s "
                       ." where userid = %u "
                       ." and lesson_start > %u"
                       ." and lesson_end < %u"
                       ." and confirm_flag!=2"
                       ." and lesson_del_flag=0"
                       ,self::DB_TABLE_NAME
                       ,$userid
                       ,$ret_month['start']
                       ,$ret_month['end']
        );
        return $this->main_get_list($sql);
    }





}