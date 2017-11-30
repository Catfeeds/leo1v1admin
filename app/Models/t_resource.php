<?php
namespace App\Models;
use \App\Enums as E;
class t_resource extends \App\Models\Zgen\z_t_resource
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all(
        $user_type, $resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $file_title, $page_info
    ){
        $where_arr = [
            ['user_type=%u', $user_type, -1],
            ['resource_type=%u', $resource_type, -1],
            ['subject=%u', $subject, -1],
            ['grade=%u', $grade, -1],
            ['tag_one=%u', $tag_one, -1],
            ['tag_two=%u', $tag_two, -1],
            ['tag_three=%u', $tag_three, -1],
            'is_del=0',
        ];
        if($file_title != ''){
            $where_arr[] = ["file_title like '%s%%'", $this->ensql( $file_title), ""];
        }
        $sql = $this->gen_sql_new(
            "select resource_id,resource_type,file_title,file_size,file_type,update_time,edit_adminid,down_num,error_num,is_use,user_type"
            ." from %s"
            ." where %s order by resource_id desc"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

}











