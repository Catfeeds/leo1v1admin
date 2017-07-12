<?php
namespace App\Models;
use \App\Enums as E;
class t_graduating_student_lesson_time_count extends \App\Models\Zgen\z_t_graduating_student_lesson_time_count
{
    public function __construct()
    {
        parent::__construct();
    }

    public function set_plan_lesson_time($res_arr, $userid, $weeks, $year){

        $sql = $this->gen_sql_new("delete from %s where userid=%u",
                                  self::DB_TABLE_NAME
                                  ,$userid );
        $this->main_update($sql);

        foreach ($res_arr as $index=>$item) {
            $start_time_arr  =  explode('-',$weeks[$item['0']]);
            $start_time_tmp  =  $year.'.'.$start_time_arr['0'];
            $start_time      =  strtotime(str_replace(".","/",$start_time_tmp));
            $this->row_insert([
                self::C_userid            => $userid,
                self::C_start_time        => $start_time,
                self::C_plan_lesson_time  => $item['1']*100,
            ]);
        }
    }

    public function get_plan_lesson_count($userid, $start_time){
        $end_time  = strtotime('+3 months',$start_time); 
        $where_arr = [
            ["userid = %d",$userid],
        ];
        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select plan_lesson_time from %s where %s order by start_time ",
                                  self::DB_TABLE_NAME, $where_arr);

        return $this->main_get_list($sql);
    }

}
