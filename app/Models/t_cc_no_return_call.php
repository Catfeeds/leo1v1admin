<?php
namespace App\Models;
use \App\Enums as E;
class t_cc_no_return_call extends \App\Models\Zgen\z_t_cc_no_return_call
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:获取试听未回访记录表
    //@param:$adminid cc id
    //@param: $page_info 分页信息
    public function get_no_return_call_list($adminid,$page_info){
        $where_arr = [
            ['uid = %u',$adminid,-1]
        ];
        $sql = $this->gen_sql_new(
            'select cnrc.*,mi.account '.
            'from %s cnrc '.
            'join %s mi using(uid) '.
            'where %s',
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql, $page_info);
    }

}











