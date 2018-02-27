<?php
namespace App\Models;
use \App\Enums as E;


class t_seller_student_new_b2 extends \App\Models\Zgen\z_t_seller_student_new
{
    public function get_need_check_free_list() {

        $now= time(NULL);
        $start_time=$now - 3*86400 ;
        $end_time= $now;

        $where_arr=[
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
            "tq_called_flag in (0, 1)",
            "admin_revisiterid >0",
        ];

        $this->where_arr_add_time_range($where_arr, "admin_assign_time", $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select userid , admin_assign_time,admin_revisiterid,phone,tq_called_flag "
            ." from %s  "
            ." where %s "
            ,self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function  get_today_can_system_assign_count( ){
        $start_time= strtotime( date("Y-m-d"));
        $end_time= $start_time + 86400-1;

        $where_arr=[
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
        ];

        $this->where_arr_add_time_range($where_arr, "add_time", $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select count(*)  "
            ." from %s  "
            ." where %s "
            ,self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);

    }
    public function admin_hold_count($admin_revisiterid)  {

        $sql = $this->gen_sql_new(
            " select   count(*) as count "
            ." from %s  "
            ."where admin_revisiterid=%u "
            ,self::DB_TABLE_NAME
            , $admin_revisiterid
        );
        return $this->main_get_value($sql);
    }

    public function get_check_free_list( $start_time, $end_time ) {
        $where_arr=[
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
            "tq_called_flag in (0,1)",
        ];
        $this->where_arr_add_time_range($where_arr, "admin_assign_time", $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select userid,admin_revisiterid "
            ." from %s  "
            ." where %s "
            ,self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_today_new_count($adminid) {
        $start_time= strtotime( date("Y-m-d"));
        $end_time= $start_time + 86400-1;
        $where_arr=[
            "admin_revisiterid" =>$adminid,
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
        ];
        $this->where_arr_add_time_range($where_arr, "admin_assign_time", $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select count(*)  "
            ." from %s  "
            ." where %s "
            ,self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_value($sql);
    }

    public function  get_need_new_assign_list( $global_tq_called_flag =0 , $limit_count = 1000 ) {
        $where_arr=[
            ["n.global_tq_called_flag=%u",  $global_tq_called_flag , -1 ],
            "n.seller_student_assign_type=1", // 系统分配k
            "n.seller_resource_type=0", // 新例子
            "n.admin_revisiterid=0", // 未分配
            '(s.origin_level <= 4 or s.origin_level = 99)', //s a b c 类例子
            'n.cc_no_called_count<=3' //未拨通3次以内
        ];

        // $where_arr[] = '(tls.seller_student_status in (1,2,101,102) and n.cc_no_called_count<=2)';
        $start_time = time(NULL) -86400*30;
        $end_time = time(NULL) ;
        $this->where_arr_add_time_range($where_arr, "add_time", $start_time, $end_time);
        $sql= $this->gen_sql_new(
            "select  n.userid, s.origin_level "
            . " from %s n"
            . " join %s s on n.userid=s.userid "
            // . ' left join %s tls on tls.userid = n.userid '
            . "  where  %s order by origin_level asc limit $limit_count ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            // t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取cc有效例子数
    //@param:$begin_time,$end_time 开始时间 结束时间
    //@param:$admin_revisiterid cc的id
    public function get_effect_num($begin_time,$end_time,$admin_revisiterid){
        $where_arr=[
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
            "tq_called_flag =2",
            "admin_revisiterid" => $admin_revisiterid
        ];

        $this->where_arr_add_time_range($where_arr, "admin_assign_time", $begin_time, $end_time);
        $sql = $this->gen_sql_new(
            'select count(*) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);

    }
    
}
