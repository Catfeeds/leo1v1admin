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
    //@desn:获取漏斗型存档数据
    //@month_begin: 月初时间
    //@$origin_ex:渠道字符串
    public function get_list($month_begin,$origin_ex){
        $where_arr = [
            ['add_time = %u',$month_begin]
        ];
        if($origin_ex){
            $origin_arr = explode(',', $origin_ex);
            $this->where_arr_add_str_field($where_arr, 'key0',$origin_arr[0]);
            $this->where_arr_add_str_field($where_arr, 'key1',$origin_arr[1]);
            $where_add = '';
            $key0 = $origin_arr[0];
            $key1 = $origin_arr[1];
            if($key0)
                $where_add = " or (key0 = '$key0' and add_time = $month_begin) ";
            if($key1)
                $where_add = " or (key0 = '$key0' and key1 = '' and add_time = $month_begin) ";
        }

        $sql = $this->gen_sql_new(
            'select * from %s where %s '.$where_add.' order by sort asc',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

}











