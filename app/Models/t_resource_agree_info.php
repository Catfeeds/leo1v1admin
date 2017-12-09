<?php
namespace App\Models;
use \App\Enums as E;
class t_resource_agree_info extends \App\Models\Zgen\z_t_resource_agree_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_agree_resource(){
        $sql = $this->gen_sql_new(
            "select agree_id,resource_type,subject,grade,tag_one,tag_two,tag_three,tag_four,is_ban from %s"
            ." order by resource_type,subject,grade,tag_one,tag_two,tag_three,tag_four"
            , self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function update_ban($resource_type,$subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four, $adminid, $time, $is_ban){
        $where_arr = [
            ['resource_type=%u', $resource_type, -1],
            ['subject=%u', $subject, -1],
            ['grade=%u', $grade, ''],
            ['tag_one=%u', $tag_one, ''],
            ['tag_two=%u', $tag_two, ''],
            ['tag_three=%u', $tag_three, ''],
            ['tag_four=%u', $tag_four, ''],
        ];

        $sql = $this->gen_sql_new("update %s set is_ban=$is_ban,lock_adminid=$adminid,lock_time=$time where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function del_agree($resource_type,$subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four ){
        $where_arr = [
            ['resource_type=%u', $resource_type, -1],
            ['subject=%u', $subject, -1],
            ['grade=%u', $grade, ''],
            ['tag_one=%u', $tag_one, ''],
            ['tag_two=%u', $tag_two, ''],
            ['tag_three=%u', $tag_three, ''],
            ['tag_four=%u', $tag_four, ''],
        ];

        $sql = $this->gen_sql_new("delete from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_all_resource_type($resource_type=-1, $subject=-1, $grade=-1){
        $where_arr = [
            ['resource_type=%u', $resource_type, -1],
            ['subject=%u', $subject, -1],
            ['grade=%u', $grade, -1],
            'resource_type in (1,2,3,4,5,9)',
        ];
        $sql = $this->gen_sql_new("select distinct tag_one from %s where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_next_info($select,$resource_type,$subject, $grade, $tag_one, $tag_two, $tag_three ){
        $where_arr = [
            ['resource_type=%u', $resource_type, -1],
            ['subject=%u', $subject, ''],
            ['grade=%u', $grade, ''],
            ['tag_one=%u', $tag_one, ''],
            ['tag_two=%u', $tag_two, ''],
            ['tag_three=%u', $tag_three, ''],
            "$select > 0",
        ];

        $sql = $this->gen_sql_new("select distinct $select from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }
}











