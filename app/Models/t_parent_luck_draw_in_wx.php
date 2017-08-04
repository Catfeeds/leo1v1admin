<?php
namespace App\Models;
use \App\Enums as E;
class t_parent_luck_draw_in_wx extends \App\Models\Zgen\z_t_parent_luck_draw_in_wx
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_prize_code_list($price){
        $where_arr = [
            ["price=%d",$price],
            "receive_time<>''"
        ];

        $sql = $this->gen_sql_new(" select prize_code from %s pl ".
                                  " where %s",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }

}
