<?php
namespace App\Models;
use \App\Enums as E;
class t_grab_lesson_link_visit_operation extends \App\Models\Zgen\z_t_grab_lesson_link_visit_operation
{
    public function __construct()
    {
        parent::__construct();

    }

    public function get_operationid_by_tea_requireid($teacherid,$requireid,$visitid){
        $where_arr = [
            ['teacherid=%u', $teacherid, -1],
            ['requireid=%u', $requireid, -1],
            ['visitid=%u', $visitid, -1],
        ];
        $sql = $this->gen_sql_new("select operationid from %s where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_grab_result_info($start_time,$end_time){
        $where_arr=[
            ["create_time>=%u",$start_time,0],
            ["create_time<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select count(*) all_num,"
                                  ." sum(if(success_flag=1,1,0)) success_num"
                                  ." from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }


}
