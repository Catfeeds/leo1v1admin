<?php
namespace App\Models;
use \App\Enums as E;
class t_revisit_warning_overtime_info extends \App\Models\Zgen\z_t_revisit_warning_overtime_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_ass_warning_overtime_count($ass_adminid, $uid_str) {

        $where_arr=[
            ["m.uid= %u",$ass_adminid,-1],
            "wo.deal_type<>1",
        ];

        if ($uid_str != -1 & $uid_str !== null) {
            $where_arr[] = "m.uid in ($uid_str)";
        }



        $sql = $this->gen_sql_new(
            "select count(1) "
            ."from %s wo left join %s m on m.account = wo.sys_operator "
            ." where %s",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_value($sql);

    }
}
