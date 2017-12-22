<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_full_part_trans_info extends \App\Models\Zgen\z_t_teacher_full_part_trans_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list($start_time, $end_time) {
        $where_arr = [
            ['add_time>=%u', $start_time, 0],
            ['add_time<%u', $end_time, 0]
        ];

        $sql = $this->gen_sql_new("select id,teacherid,level_before,level_after,teacher_money_type_before,teacher_money_type_after,require_adminid,require_time,require_reason,type,accept_status from %s where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function is_exist_for_teacherid($teacherid) {
        $where_arr = [
            ["teacherid=%u", $teacherid, 0],
            "type=1"
        ];

        $sql = $this->gen_sql_new("select id from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_accept_list($start_time, $end_time) {
        $where_arr = [
            ["accept_time>=%u", $start_time, 0],
            ["accept_time<%u", $end_time, 0]
        ];

        $sql = $this->gen_sql_new("select teacherid,accept_time from %s where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function ($item) {
            return $item['teacherid'];
        });
    }
}











