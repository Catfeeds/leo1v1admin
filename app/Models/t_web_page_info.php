<?php
namespace App\Models;
use \App\Enums as E;
class t_web_page_info extends \App\Models\Zgen\z_t_web_page_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_list( $page_info, $start_time,$end_time ,$del_flag  ) {
        $where_arr=[
            ["del_flag=%u",  $del_flag, -1  ]
        ];
        $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);

        $sql= $this->gen_sql_new("select * from %s where %s order by  add_time  desc ",
                                 self::DB_TABLE_NAME,  $where_arr );

        return $this->main_get_list_by_page($sql,$page_info);
    }


    public function h5_count($start_time, $end_time){
        $where_arr=[
            ["add_time>=%u",  $start_time, -1  ],
            ["add_time<%u",  $end_time, -1  ],
            'del_flag=0',
        ];

        $sql= $this->gen_sql_new("select count(web_page_id) from %s where %s ",
                                 self::DB_TABLE_NAME,  $where_arr );

        return $this->main_get_value($sql);
    }

    public function is_all_share($start_time, $end_time, $adminid) {
        $where_arr = [
            // ['l.from_adminid=%u', $adminid,-1],
            ["w.add_time>=%u",  $start_time, -1],
            ["w.add_time<%u",  $end_time, -1],
        ];

        $sql=$this->gen_sql_new("select max(l.share_wx_flag) as share_flag, w.web_page_id,l.from_adminid ".
                                "from %s w ".
                                "left join %s l on l.web_page_id=w.web_page_id and l.from_adminid=$adminid".
                                "where %s ".
                                "group by w.web_page_id",
                                self::DB_TABLE_NAME,
                                t_web_page_trace_log::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function updateUrlInfo($id,$title, $dealAdminId){
        $sql = $this->gen_sql_new("  update %s tw set title='$title', add_adminid=$dealAdminId "
                                  ." where act_usuall_id=$id"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function get_web_info($web_page_id_str) {

        $where_arr = [
            ['web_page_id_str in (%s)', $web_page_id_str,-1],
        ];

        $sql=$this->gen_sql_new("select web_page_id, title, url ".
                                "from %s  ".
                                "where %s ".
                                self::DB_TABLE_NAME,
                                $where_arr
        );

        return $this->main_get_list($sql);
    }

}
