<?php
namespace App\Models;
use \App\Enums as E;
class t_cc_day_top extends \App\Models\Zgen\z_t_cc_day_top
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:判断今日是否已有排名
    //@param:$add_time 今日时间
    public function get_has_rank_count($add_time){
        $where_arr = [
            "add_time = $add_time"
        ];
        $sql=$this->gen_sql_new(
            'select count(*) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取排名信息
    //@param:$page_info 分页信息
    //@param:$start_time, $end_time 开始时间 结束时间
    //@param:$adminid 销售信息
    public function get_list($page_info,$start_time, $end_time,$adminid){
        $where_arr = [
            ['cdt.uid = %u',$adminid,'-1']
        ];
        $this->where_arr_add_time_range($where_arr, 'add_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select cdt.*,mi.account from %s cdt join %s mi using(uid) where %s',
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql, $page_info);
    }

}











