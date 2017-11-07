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

        $sql = $this->gen_sql_new("  select prize_type, p.phone, get_prize_time from %s ra"
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
            ["prize_type=%u",$prize_type,-1],
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

    public function get_order_max_prize_type($parentid ) {
        /*
          E\Eruffian_prize_type

          2 => "10元折扣券",
          3 => "50元折扣券",
          4 => "100元折扣券",
          5 => "300元折扣券",
          6 => "500元折扣券",
        */
        $sql = $this->gen_sql_new(
            " select id , prize_type from %s ru "
            ." where parentid = %u"
            . " and  prize_type in(2,3,4,5,6 ) "
            ." and  to_orderid=0 "
            . " order by prize_type desc limit 1   "
            ,self::DB_TABLE_NAME , $parentid);
        return $this->main_get_row($sql);
    }
    public function set_to_orderid( $id, $to_orderid) {
        return $this->field_update_list($id,[
            "to_orderid" => $to_orderid,
        ]);
    }

    public function check_is_has_test($parentid){
        $sql = $this->gen_sql_new("  select 1 from %s ru "
                                  ." where parentid=%d and prize_type=8"
                                  ,self::DB_TABLE_NAME
                                  ,$parentid
        );

        return $this->main_get_value($sql);
    }

    public function get_active_num($parentid){
        $sql = $this->gen_sql_new("  select count(*) from %s ru "
                                  ." where parentid = %d"
                                  ,self::DB_TABLE_NAME
                                  ,$parentid
        );

        return $this->main_get_value($sql);
    }

}
