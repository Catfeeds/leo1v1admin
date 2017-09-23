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
                                  " where is_modify_time_flag = 0 and ((parent_deal_time+3600)<$now) and teacher_deal_time = '' ",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }


}
