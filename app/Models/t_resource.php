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
        $use_type, $resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four, $file_title, $page_info, $is_del = 0
    ){
        $where_arr = [
            ['use_type=%u', $use_type, -1],
            ['resource_type=%u', $resource_type, -1],
            ['subject=%u', $subject, -1],
            ['grade=%u', $grade, -1],
            ['tag_one=%u', $tag_one, -1],
            ['tag_two=%u', $tag_two, -1],
            ['tag_three=%u', $tag_three, -1],
            ['tag_four=%u', $tag_four, -1],
            ['is_del=%u', $is_del, -1],
            ['status=%u', $is_del, -1],
        ];

        if($file_title != ''){
            $where_arr[] = ["file_title like '%s%%'", $this->ensql( $file_title), ""];
        }
        $sql = $this->gen_sql_new(
            "select r.resource_id,resource_type,file_title,file_size,file_type,error_num,use_type,"
            ."file_hash,subject,grade,tag_one,tag_two,tag_three,tag_four,use_type,file_link,f.file_id,file_use_type,"
            ."max(if( v.visit_id>0,v.create_time,r.create_time)) update_time,"
            ."if( v.visit_id>0,v.visitor_id,r.adminid) edit_adminid"
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id and v.visitor_type=0"
            ." where %s group by f.file_id order by r.resource_id,f.file_use_type desc"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function get_count($start_time, $end_time){
        $where_arr = [
            'r.is_del=0',
            'f.status=0',
            ['r.create_time>%u', $start_time, -1],
            ['r.create_time<=%u', $end_time, -1],
        ];
        $sql = $this->gen_sql_new(
            "select resource_type,adminid,subject,f.file_id,f.visit_num,f.use_num,f.error_num"
            ." from %s f"
            ." left join %s r on r.resource_id=f.resource_id"
            ." where %s"
            ." group by file_id"
            ,t_resource_file::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function is_has_file($resource_type,$subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four){
        $where_arr = [
            ['resource_type=%u', $resource_type, -1],
            ['subject=%u', $subject, -1],
            ['grade=%u', $grade, ''],
            ['tag_one=%u', $tag_one, ''],
            ['tag_two=%u', $tag_two, ''],
            ['tag_three=%u', $tag_three, ''],
            ['tag_four=%u', $tag_four, ''],
            'is_del<3',
        ];
        $sql = $this->gen_sql_new("select count(1) from %s where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
}
