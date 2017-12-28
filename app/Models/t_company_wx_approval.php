<?php
namespace App\Models;
use \App\Enums as E;
class t_company_wx_approval extends \App\Models\Zgen\z_t_company_wx_approval
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_list() {
        $sql = $this->gen_sql_new("select * from %s",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_info_for_userid($userid, $start_time, $end_time) {
        $where_arr = [
            ['start_time>=%u', $start_time, 0],
            ['start_time<=%u', $end_time, 0],
            ['apply_user_id=%s',$userid,0],
            'sp_status=2', // 已通过
            'type=1' // 请假
        ];
        $sql = $this->gen_sql_new("select start_time,end_time from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        echo $sql;
        return $this->main_get_list($sql);
    }

    public function get_all_info($start_time, $end_time) {
        $where_arr = [
            ["apply_time>=%u", $start_time, 0],
            ["apply_time<%u", $end_time, 0]
        ];
        $sql = $this->gen_sql_new("select id,apply_user_id,apply_time,sp_status from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function($item) {
            return $item['apply_user_id'].'-'.$item['apply_time'];
        });
    }
}











