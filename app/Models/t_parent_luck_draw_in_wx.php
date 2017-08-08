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

    public function get_gift_info_by_userid($userid){
        $where_arr = [
            "userid = $userid"
        ];

        $sql = $this->gen_sql_new(" select userid, add_time, price, prize_code, use_flag, receive_time from %s pl where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_all_gift_list($price){

        $where_arr = [
            "prize_code <> ''",
            ["price = %s",$price],
            "receive_time = ''"
        ];

        $sql = $this->gen_sql_new(" select prize_code, userid, use_flag, price, receive_time from %s ".
                                  " where %s  ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function ger_today_gift_num($start_time,$end_time,$price){

        $where_arr = [
            ["price = %d",$price],
            ["receive_time>%s",$start_time],
            ["receive_time<%s",$end_time],
            "userid>0"
        ];

        $sql = $this->gen_sql_new(" select count(*) from %s pl where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_id_by_code($prize_code){
        $sql = $this->gen_sql_new(" select id from %s pl where prize_code = '$prize_code' and receive_time=''",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }
}
