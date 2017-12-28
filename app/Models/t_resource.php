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
            ['tag_two=%u', $tag_two, -1],
            ['tag_three=%u', $tag_three, -1],
            ['tag_four=%u', $tag_four, -1],
            ['is_del=%u', $is_del, -1],
            ['status=%u', $is_del, -1],
        ];
        if( in_array($resource_type, [1,2,3,4,5,9]) ){
            //添加通用版50000
            if($tag_one != -1){
                $where_arr[] = " tag_one in ($tag_one, 50000) ";
            }
        } else {
            $where_arr[] = ['tag_one=%u', $tag_one, -1];
        }

        if($file_title != ''){
            $where_arr[] = ["file_title like '%s%%'", $this->ensql( $file_title), ""];
        }

        $sql = $this->gen_sql_new(
            "select r.resource_id,resource_type,file_title,file_size,file_type,use_type,v.create_time,v.visitor_id,f.ex_num,"
            ." file_hash,subject,grade,tag_one,tag_two,tag_three,tag_four,use_type,file_link,f.file_id,file_use_type"
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id and v.visitor_type=0 "
            ." where %s"
            ." and not exists ( select 1 from %s where file_id=v.file_id and v.create_time<create_time and visitor_type=0) "
            ." order by r.resource_id desc,f.file_use_type"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,$where_arr
            ,t_resource_file_visit_info::DB_TABLE_NAME
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
        $sql = $this->gen_sql_new("select resource_type,adminid,subject,f.file_id,f.visit_num,f.use_num,f.error_num"
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

    public function get_all_for_tea(
        $resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$page_info
    ){
         $where_arr = [
            'r.use_type=1',
            ['r.resource_type=%u', $resource_type, -1],
            ['r.subject=%u', $subject, -1],
            ['r.grade=%u', $grade, -1],
            // ['r.tag_one=%u', $tag_one, -1],
            ['r.tag_two=%u', $tag_two, -1],
            ['r.tag_three=%u', $tag_three, -1],
            ['r.tag_four=%u', $tag_four, -1],
            'r.is_del=0',
            'f.status=0',
            'ra.is_ban=0',
        ];

        //老师只开放了１－６
        if( $resource_type < 6){
            //添加通用版50000
            if($tag_one != -1){
                $where_arr[] = " r.tag_one in ($tag_one, 50000) ";
            }
        } else {
            $where_arr[] = ['r.tag_one=%u', $tag_one, -1];
        }


        $sql = $this->gen_sql_new(
            "select r.resource_id,r.resource_type,f.file_title,f.file_size,f.file_type, max(v.create_time) create_time,f.file_id,"
            ." v.visitor_id, r.subject,r.grade,r.tag_one,r.tag_two,r.tag_three,r.tag_four,f.file_link,f.file_use_type,f.use_num, "
            ." tr.tea_res_id"
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id and v.visitor_type=0 "
            ." left join %s tr on tr.file_id=f.file_id and tr.is_del=0 "
            ." left join %s ra on "
            ." ra.resource_type=r.resource_type and ra.subject=r.subject and ra.grade=r.grade and ra.tag_one=r.tag_one and"
            ." ra.tag_two=r.tag_two and ra.tag_three=r.tag_three and ra.tag_four=r.tag_four "
            ." where %s"
            ." group by f.file_id "
            ." order by r.resource_id desc,f.file_use_type"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,t_teacher_resource::DB_TABLE_NAME
            ,t_resource_agree_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function getResourceId($subject,$grade){
        $where_arr = [
            "r.subject=$subject",
            "r.grade=$grade",
            "r.use_type=1",
            "r.resource_type=3", // 标准试听课
            "r.is_del=0",
            "ra.is_ban=0",
            "f.status=0"
        ];

        $sql = $this->gen_sql_new("  select r.resource_id from %s r "
                                  ." left join %s f on f.resource_id=r.resource_id"
                                  ." left join %s ra on "
                                  ." ra.resource_type=r.resource_type and ra.subject=r.subject and ra.grade=r.grade and ra.tag_one=r.tag_one and"
                                  ." ra.tag_two=r.tag_two and ra.tag_three=r.tag_three and ra.tag_four=r.tag_four "

                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_resource_file::DB_TABLE_NAME
                                  ,t_resource_agree_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

}
