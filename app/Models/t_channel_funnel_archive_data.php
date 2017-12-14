<?php
namespace App\Models;
use \App\Enums as E;
class t_channel_funnel_archive_data extends \App\Models\Zgen\z_t_channel_funnel_archive_data
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

}











