<?php
namespace App\Models;

class t_sms_msg extends \App\Models\Zgen\z_t_sms_msg
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_sms_list($page_num, $start, $end, $phone, $is_success, $type,$receive_content)
    {
        $where_arr=[
            ["phone like \"%%%s%%\"",$phone,"" ],
            ["receive_content like \"%%%s%%\"",$receive_content,"" ],
            ["is_success =%u ",$is_success, -1 ],
            ["type =%u ",$type , -1 ],
        ];
        $sql = $this->gen_sql_new(
            "select recordid, phone, message, send_time, type, user_ip, receive_content, is_success from %s ".
            "where send_time > %u and send_time < %u and %s order by  send_time desc",
            self::DB_TABLE_NAME,
            $start,
            $end,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function tongji_get_list($start_time, $end_time, $is_success, $type)
    {
        $where_arr=[
            ["is_success =%u ",$is_success, -1 ],
            ["type =%u ",$type , -1 ],
            ["send_time>=%u ",$start_time, -1 ],
            ["send_time<%u ",$end_time, -1 ],
        ];
        $type_str="";
        if ($type !=-1) {
            $type_str="type,";
        }

        $sql=$this->gen_sql_new("select from_unixtime(send_time ,'%%Y-%%m-%%d') as log_date , $type_str count(*) as count from %s where %s  group  by $type_str log_date  ", self::DB_TABLE_NAME, $where_arr);
        return $this->main_get_list($sql);
    }
    public function tongji_type_get_list($start_time, $end_time)
    {
        $where_arr=[
            ["send_time>=%u ",$start_time, -1 ],
            ["send_time<%u ",$end_time, -1 ],
        ];
        $this->switch_readonly_database();

        $sql=$this->gen_sql_new("select  type , sum(is_success=1 ) as succ_count ,sum(is_success<>1 ) as fail_count  from %s where %s  group  by type order  by fail_count desc,  succ_count desc ",
                                self::DB_TABLE_NAME, $where_arr);
        return $this->main_get_list($sql );
    }

}
