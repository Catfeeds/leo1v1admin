<?php
namespace App\Models;
use \App\Enums as E;
class t_user_report extends \App\Models\Zgen\z_t_user_report
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_report_info($page_num){
        $where_arr = [

        ];

        $sql = $this->gen_sql_new(
            "select log_time, report_uid, report_msg, from_type,report_account_type from %s".
            " where %s"
            . " order by log_time desc ",
            self::DB_TABLE_NAME,
            $where_arr
        );

        // return $sql;
        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function get_last_msg($report_uid){
        $sql = $this->gen_sql_new("select report_msg from %s where report_uid = %d order by log_time desc ",
                                  self::DB_TABLE_NAME,
                                  $report_uid
        );

        return $this->main_get_list($sql);

    }

}
