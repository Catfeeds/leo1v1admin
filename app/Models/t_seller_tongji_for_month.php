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
            ""
        ];

        $sql = $this->gen_sql_new();
    }

}
