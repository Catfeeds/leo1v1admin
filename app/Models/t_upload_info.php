<?php
namespace App\Models;
use \App\Enums as E;
class t_upload_info extends \App\Models\Zgen\z_t_upload_info
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_list($page_info, $post_adminid,$start_time, $end_time) {
        $where_arr=[] ;
        $this->where_arr_add_int_field($where_arr,"post_adminid",$post_adminid);
        $this->where_arr_add_time_range($where_arr,"upload_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            ["select p.postid, p.upload_time, p.upload_desc, p.post_flag, p.upload_adminid,",
             " sum(ps.is_new_flag=0)  as old_count ,sum( ps.is_new_flag is not null)  count ",
             " from %s p",
             "left join %s ps on ps.postid =p.postid ",
             "where %s  group by  p.postid order by p.upload_time desc "
            ]

            , self::DB_TABLE_NAME
            , t_upload_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,10, true);
    }

}
