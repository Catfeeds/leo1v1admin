<?php
namespace App\Models;
use \App\Enums as E;
class t_user_login_log extends \App\Models\Zgen\z_t_user_login_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login_list($page_info,$userid,$dymanic_flag){
        $where_arr=[
            ["dymanic_flag=%u",$dymanic_flag,-1],
            ["userid=%u",$userid,],
        ];
        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  ." where %s "
                                  ." order by login_time desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_login_list($page_info, $start_time, $end_time,$userid, $ip ){
        $where_arr=[
            ["ip='%s'",$ip,""],
            ["userid=%u",$userid,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"login_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  ." where %s "
                                  ." order by login_time desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }



    public function get_login_tongji( $start_time, $end_time  ) {
        $where_arr=[];
        $this->where_arr_add_boolean_for_value($where_arr,"lesson_count_all" ,1 );
        $this->where_arr_add_time_range($where_arr,"login_time",$start_time,$end_time);
        $sql= $this->gen_sql_new(
            "select ip,  count( distinct s.userid ) as user_count"
            . " from %s lo"
            . " join %s s on lo.userid = s.userid  "
            . " where  %s group by ip having user_count >1 ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql);

    }


}
