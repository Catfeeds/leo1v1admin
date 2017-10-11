<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_tongji_for_month extends \App\Models\Zgen\z_t_seller_tongji_for_month
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_history_data($start_time){
        $where_arr = [
            "from_time = $start_time"
        ];

        $sql = $this->gen_sql_new("select * from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }


    public function update_funnel_date($start_time, $seller_invit_month, $has_tq_succ_invit_month, $seller_plan_invit_month, $seller_test_succ_month, $order_trans_month, $order_sign_month, $has_tq_succ_sign_month, $has_called_stu ){
        $six_month_old = strtotime(date('Y-m-d 0:0:0',strtotime('-2 month',$start_time)));
        $where_arr = [
            ["from_time" ]
        ];


    }

}
