<?php
namespace App\Models;
use \App\Enums as E;
class t_lesson_time_modify extends \App\Models\Zgen\z_t_lesson_time_modify
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_parent_modify_time_by_lessonid($lessonid){
        $sql = $this->gen_sql_new("select parent_modify_time, is_modify_time_flag from %s l where lessonid = %d ",
                                  self::DB_TABLE_NAME,
                                  $lessonid
        );


        return $this->main_get_row($sql);
    }

    public function get_parent_modify_time($lessonid){
        $sql = $this->gen_sql_new("select parent_modify_time from %s l where lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_value($sql);
    }


    public function get_original_time_by_lessonid($lessonid){
        $sql = $this->gen_sql_new(" select original_time from %s l ".
                                  " where lessonid = %d ",
                                  self::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_value($sql);
    }

    public function get_teacher_keep_original_remark($lessonid){
        $sql = $this->gen_sql_new(" select teacher_keep_original_remark from %s l ".
                                  " where lessonid = %d ",
                                  self::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_val($sql);

    }

    public function get_modify_flag($lessonid){
        $sql = $this->gen_sql_new(" select is_modify_time_flag from %s l ".
                                  " where lessonid = $lessonid",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }


    public function get_need_notice_lessonid($now){
        $sql = $this->gen_sql_new(" select lessonid, parent_deal_time from %s lt ".
                                  " where is_modify_time_flag = 0 and is_notice_ass_flag=0 and ((parent_deal_time+3600)<$now) and teacher_deal_time = '' ",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }


    public function get_parent_modify_remark($lessonid){
        $sql = $this->gen_sql_new("  select parent_modify_remark from %s "
                                  ." where lessonid = %d"
                                  ,self::DB_TABLE_NAME
                                  ,$lessonid
        );

        return $this->main_get_value($sql);
    }

    public function get_modify_list($start_time, $end_time, $page_num, $is_done){
        $where_arr = [
            ["lt.is_modify_time_flag=%s",$is_done,-1],
            "backstage_type=1"
        ];
        $this->where_arr_add_time_range($where_arr,"parent_deal_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("  select l.lesson_start, l.lesson_end, lt.lessonid, backstage_type,  p.nick as p_name , is_notice_ass_flag, parent_deal_time, is_modify_time_flag from %s lt"
                                  ." left join %s l on l.lessonid=lt.lessonid"
                                  ." left join %s s on s.userid=l.userid"
                                  ." left join %s p on p.parentid=s.parentid"
                                  ." where %s order by parent_deal_time desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        // return $this->main_get_list_by_page($sql,$page_num,10,$use_group_by_flag);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function checkHasExist($lessonid){
        $sql = $this->gen_sql_new("  select 1 from %s where lessonid=$lessonid"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function getChangeTimeInfo($teacherid){
        $where_arr = [
            "l.teacherid=$teacherid"
        ];
        $sql = $this->gen_sql_new("  select l.lesson_start, l.lesson_end, l.teacherid, l.userid, l.subject, tm.original_time, "
                                  ." tm.is_modify_time_flag from %s tm "
                                  ." left join %s l on l.lessonid=tm.lessonid"
                                  ." where %s order by tm.is_modify_time_flag desc,tm.parent_deal_time desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
}
