<?php
namespace App\Models;
use \App\Enums as E;
class t_channel_node_type_statistics extends \App\Models\Zgen\z_t_channel_node_type_statistics
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:根据排序查找存档数据id
    //@param:$sort 排序
    //@param:$start_time 本月数据时间
    public function get_id_by_sort($sort=-1,$start_time=0){
        $sql = $this->gen_sql_new(
            'select id from %s where sort = %u and add_time = %u',
            self::DB_TABLE_NAME,
            $sort,$start_time
        );
        return $this->main_get_value($sql);
    }
    //@desn:获取漏斗型存档数据
    //@month_begin: 月初时间
    public function get_list($month_begin){
        $sql = $this->gen_sql_new(
            'select * from %s where add_time = %u order by sort asc',
            self::DB_TABLE_NAME,
            $month_begin
        );
        return $this->main_get_list_as_page($sql);
    }

}











