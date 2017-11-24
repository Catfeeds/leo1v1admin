<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_card_log extends \App\Models\Zgen\z_t_admin_card_log
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_list ($page_num, $start_time,$end_time, $adminid ,$page_count=10,$account_role=-1) {
        $where_arr=[
            ["logtime>=%u" , $start_time, -1 ],
            ["logtime<%u" , $end_time, -1 ],
            //["m.uid=%u" , $adminid, -1 ],
            ["c.cardid=%u" , $adminid, -1 ],
            ["m.account_role=%u",$account_role,-1]
        ];
        $sql=$this->gen_sql_new("select  logtime, m.uid,  c.cardid,m.account from %s c ".
                                "left join %s m on c.cardid=m.uid".
                                " where  %s  order by logtime asc ",
                                self::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list_by_page($sql,$page_num,$page_count);
    }
    public function insert_arr( $row_arr_str ) {
        $sql="insert ignore into ". self::DB_TABLE_NAME ." ( logtime, cardid ) values   " .  $row_arr_str;
        return $this->main_update($sql);
    }
    public function get_admin_list( $start_time ,$end_time ) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"logtime",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            ["select cardid as adminid , min(logtime)  as start_logtime, max(logtime)  as end_logtime   ",
             "  from %s where %s group by adminid ",
            ],
            self::DB_TABLE_NAME,
            $where_arr
        );
        return    $this->main_get_list($sql);
    }

}
