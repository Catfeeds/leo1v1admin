<?php
namespace App\Models;
class t_teacher_freetime_for_week extends \App\Models\Zgen\z_t_teacher_freetime_for_week
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_userid($teacherid)
    {
        $sql = $this->gen_sql("select 1 from %s where teacherid = %u ",
                              self::DB_TABLE_NAME,
                              $teacherid
        );

        return $this->main_get_value($sql);
    }
    public function add_regular_course($teacherid,$tea_type,$freetime)
    {
        $this->row_insert([
            self::C_teacherid  => $teacherid,
            self::C_teacher_type  => $tea_type,
            self::C_free_time  => $freetime,
        ]);
    }

    public function get_common_lesson_config($teacherid)
    {
        $sql = $this->gen_sql("select common_lesson_config from %s where teacherid = %u",
                              self::DB_TABLE_NAME,
                              $teacherid
        );
        $str= $this->main_get_value($sql,'');

        if (trim($str)=="") {
            $str= "[]";
        }

        return \App\Helper\Utils::json_decode_as_array($str);
    }

    public function get_free_time_new($teacherid)
    {
        $sql = $this->gen_sql("select free_time_new from %s where teacherid = %u",
                              self::DB_TABLE_NAME,
                              $teacherid
        );
        $row= $this->main_get_row($sql,'');
        if (!$row) {
            $this->row_insert([
                "teacherid"   => $teacherid,
            ]);
            $str="";
        }else{
            $str=$row["free_time_new"];
        }

        if (trim($str)=="") {
            $str= "[]";
        }
        return \App\Helper\Utils::json_decode_as_array($str);
    }


    public function get_common_lesson($teacherid)
    {
        $sql = $this->gen_sql("select * from %s where teacherid = %u",
                              self::DB_TABLE_NAME,
                              $teacherid
        );
        return $this->main_get_list($sql);
    }

    public function get_teacherid_and_freetime($trial_lecture_is_pass =-1){
        $where_arr =[["t.trial_lecture_is_pass=%u",$trial_lecture_is_pass,-1]];
        $sql = $this->gen_sql_new("select f.teacherid,free_time_new from %s f left join %s t on f.teacherid=t.teacherid where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }

    public function get_free_time_new_by_teacherid_arr($teacherid_arr){
        $where_arr=[];
        $this->where_arr_teacherid($where_arr,"teacherid",$teacherid_arr);
        $sql = $this->gen_sql_new("select teacherid,free_time_new,common_week_free_time from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_vacant_arr($teacherid) {
        $sql = $this->gen_sql_new("select t.free_time_new from %s t where t.teacherid = %d",
                                  self::DB_TABLE_NAME,
                                  $teacherid);
        return $this->main_get_value($sql);
    }

}
