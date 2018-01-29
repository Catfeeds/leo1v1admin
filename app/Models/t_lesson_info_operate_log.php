<?php
namespace App\Models;
use \App\Enums as E;
class t_lesson_info_operate_log extends \App\Models\Zgen\z_t_lesson_info_operate_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function add_lesson_operate_info($lessonid,$operate_column,$operate_before,$operate_after,$operate_referer,$operate_request){
        return $this->row_insert([
            "lessonid"        => $lessonid,
            "operate_column"  => $operate_column,
            "operate_before"  => json_encode($operate_before),
            "operate_after"   => json_encode($operate_after),
            "operate_time"    => time(),
            "operate_referer" => $operate_referer,
            "operate_request" => $operate_request,
        ]);
    }

    public function get_lesson_operate_info_by_page($page_num,$lessonid){
        $where_arr = [
            ["lessonid=%u",$lessonid,0]
        ];
        $sql = $this->gen_sql_new("select lessonid,operate_column,operate_before,operate_referer,operate_request"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_lesson_operate_info($lessonid){
        $where_arr = [
            ["lessonid=%u",$lessonid,0]
        ];
        $sql = $this->gen_sql_new("select lessonid,operate_column,operate_before,operate_referer,operate_request"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


}











