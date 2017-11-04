<?php
namespace App\Models;
use \App\Enums as E;
class t_ruffian_activity extends \App\Models\Zgen\z_t_ruffian_activity
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update_share_flag($parentid){

    }

    // public function get_draw_num($start_time, $end_time, $stu_type){
    //     $where_arr = [
    //         "ru.stu_type = $stu_type"
    //     ];
    //     $this->where_arr_add_time_range($where_arr,"prize_time", $start_time, $end_time);

    //     $sql = $this->gen_sql_new("  select sum(if(prize_list=1,1,0)) as bag_num, sum(if(prize_list=2,1,0)) as ten_coupon_num, "
    //                               ."  sum(if(prize_list=3,1,0)) as fifty_coupon_num, sum(if(prize_list=4,1,0)) as one_hundred_coupon_num,"
    //                               ." sum(if(prize_list=5,1,0)) as three_hundred_coupon_num, sum(if(prize_list=6,1,0)) as five_hundred_coupon_num, "
    //                               ." sum(if(prize_list=7,1,0)) as three_free_num, sum(if(prize_list=8,1,0)) as test_lesson_num"
    //                               ." from %s ru"
    //                               ." where %s"
    //                               ,self::DB_TABLE_NAME
    //                               ,$where_arr
    //     );

    //     return $this->main_get_row($sql);
    // }

    public function get_has_done($parentid){
        $where_arr = [
            ["parentid=%u",$parentid,0]
        ];
        $sql = $this->gen_sql_new("  select count(*) from %s ru"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_prize_list($parentid){

        $where_arr = [
            "ra.parentid=$parentid"
        ];

        $sql = $this->gen_sql_new("  select prize_type, p.nick, get_prize_time from %s ra"
                                  ." left join %s p on p.parentid=ra.parentid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function check_has_left($prize_type,$stu_type){
        $today = strtotime(date('Y-m-d'));
        $where_arr = [
            ["prize_type=%u",$prize_type,0],
            ["validity_time=%u",$today,0],
            ["stu_type=%u",$stu_type,0],
            "parentid=0",
        ];

        $sql = $this->gen_sql_new(" select id  from %s ru"
                                  ." where %s limit 1 for update"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

}
