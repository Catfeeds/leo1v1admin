<?php
namespace App\Models;
use \App\Enums as E;
class t_revisit_warning_overtime_info extends \App\Models\Zgen\z_t_revisit_warning_overtime_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_ass_warning_overtime_count($ass_adminid, $uid_str, $start_time, $end_time) {

        $where_arr = [
            "wo.deal_type<>1",
            "wo.create_time>=$start_time",
            "wo.create_time<$end_time",
        ];

        $or_arr = [
            "wo.deal_type=0",
            "wo.create_time<$start_time",
        ];

        if ($uid_str != -1 && $uid_str !== null) {
            $where_arr[] = "m.uid in ($uid_str)";
            $or_arr[]    = "m.uid in ($uid_str)";
        } else {
            $where_arr[] = ["m.uid= %u",$ass_adminid,-1];
            $or_arr[]    = ["m.uid= %u",$ass_adminid,-1];
        }

        $sql = $this->gen_sql_new(
            "select count(1) "
            ."from %s wo left join %s m on m.account = wo.sys_operator "
            ." where (%s)"
            ." or (%s)",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr,
            $or_arr
        );

        return $this->main_get_value($sql);

    }

    public function get_overtime_info($userid, $revisit_time){
        $where_arr = [
            "userid=$userid",
            "revisit_time=$revisit_time",
        ];

        $sql = $this->gen_sql_new("select overtime_id,create_time,deal_type "
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }
}
