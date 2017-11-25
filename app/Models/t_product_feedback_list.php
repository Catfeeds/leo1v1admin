<?php
namespace App\Models;
use \App\Enums as E;
class t_product_feedback_list extends \App\Models\Zgen\z_t_product_feedback_list
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_product_list($deal_flag, $feedback_adminid, $start_time, $end_time){
        $where_arr = [
            ["pf.deal_flag=%d",$deal_flag,-1],
            ["pf.feedback_adminid=%d",$feedback_adminid,-1],
        ];

        $this->where_arr_add_time_range($where_arr, "pf.create_time", $start_time, $end_time);

        $sql = $this->gen_sql_new("  select pf.feedback_adminid, pf.record_adminid, pf.describe, pf.url, pf.reason,"
                                  ." pf.solution, pf.remark, pf.deal_flag, pf.create_time"
        );
    }

}
