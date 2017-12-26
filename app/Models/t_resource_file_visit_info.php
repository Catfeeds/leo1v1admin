<?php
namespace App\Models;
use \App\Enums as E;
class t_resource_file_visit_info extends \App\Models\Zgen\z_t_resource_file_visit_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_visit_detail( $page_num, $resource_id, $file_use_type, $ex_num ){
        $where_arr = [
            ['f.resource_id=%u', $resource_id, -1],
            ['f.file_use_type=%u', $file_use_type, -1],
            ['f.ex_num=%u', $ex_num, -1],
            'v.visitor_type=0',
        ];

        $sql = $this->gen_sql_new(
            "select v.visit_type,v.create_time,v.visitor_id from %s f"
            ." left join %s v on v.file_id=f.file_id"
            ." where %s "
            ." order by v.create_time desc"
            ,t_resource_file::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql, $page_num);
    }

}











