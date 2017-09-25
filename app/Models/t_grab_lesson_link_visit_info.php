<?php
namespace App\Models;
use \App\Enums as E;
class t_grab_lesson_link_visit_info extends \App\Models\Zgen\z_t_grab_lesson_link_visit_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_visit_detail_by_grabid($page_num,$grabid){
        $where_arr = [
            "v.grabid='$grabid'"
        ];
        $sql = $this->gen_sql_new(
            "select v.visitid,v.teacherid,v.create_time as visit_time,"
            ." v.operation,p.success_flag,p.create_time as grab_time"
            ." from %s v"
            ." left join %s p on p.visitid=v.visitid "
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_grab_lesson_link_visit_operation::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }
}











