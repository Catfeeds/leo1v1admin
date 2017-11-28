<?php
namespace App\Models;
use \App\Enums as E;
class t_tag_library extends \App\Models\Zgen\z_t_tag_library
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:按照检索条件展示产品标签
    public function get_tag_list($tag_l1_sort,$tag_l2_sort,$tag_name,$page_info){
        $where_arr = [];
        if($tag_l1_sort > 0)
            $this->where_arr_add_int_field($where_arr, 'tl.tag_l1_sort', $tag_l1_sort,0);
        if($tag_l2_sort > 0)
            $this->where_arr_add_int_field($where_arr, 'tl.tag_l2_sort', $tag_l2_sort,0);
        $where_arr[] = ["tl.tag_name like '%%%s%%'", $tag_name, ""];
        $sql = $this->gen_sql_new(
            'select tl.*,mi.account from %s tl'
            .' left join %s mi on tl.manager_id = mi.uid'
            .' where %s',
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

}











