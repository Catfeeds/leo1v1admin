<?php
namespace App\Models;
use \App\Enums as E;
class t_tongji_seller_top_info extends \App\Models\Zgen\z_t_tongji_seller_top_info
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_admin_top_list($adminid,$logtime ) {
        $sql=$this->gen_sql("select  * from %s where logtime=%u and adminid=%u ",
                            self::DB_TABLE_NAME, $logtime ,$adminid );

        return $this->main_get_list($sql,function($item ){
            return $item["tongji_type"];
        });
    }

    public function get_admin_week_fail_percent($adminid,$logtime,$tongji_type) {
        $sql=$this->gen_sql("select value from %s where logtime=%u and adminid=%u and tongji_type=%u limit 1",
                            self::DB_TABLE_NAME, $logtime ,$adminid,$tongji_type);

        return $this->main_get_value($sql);
    }

    public function get_admin_week_fail_percent_row($adminid,$logtime,$tongji_type) {
        $sql=$this->gen_sql("select * from %s where logtime=%u and adminid=%u and tongji_type=%u limit 1",
                            self::DB_TABLE_NAME, $logtime ,$adminid,$tongji_type);

        return $this->main_get_row($sql);
    }


    public function get_admin_top_trend_list($adminid,$logtime ) {
        $sql=$this->gen_sql("select  * from %s where logtime >= %u and adminid=%u and tongji_type=6",
                            self::DB_TABLE_NAME, $logtime ,$adminid );
        return $this->main_get_list($sql);
    }


    public function del( $tongji_type, $logtime )   {

        $sql = sprintf("delete from %s where tongji_type = %u and logtime = %u",
                       self::DB_TABLE_NAME,
                       $tongji_type,
                       $logtime
        );
        return $this->main_update($sql);

    }

    public function del_type_seven($logtime){
        $sql = sprintf("delete from %s where tongji_type in(7,8) and logtime = %u",
                       self::DB_TABLE_NAME,
                       $logtime
        );
        return $this->main_update($sql);

    }

    public function add($tongji_type,$logtime,$adminid,$value,$top_index){
        return $this->row_insert([
            "tongji_type" => $tongji_type,
            "logtime" => $logtime,
            "adminid" => $adminid,
            "value" => $value,
            "top_index" => $top_index,
        ]);

    }
    public function update_list($tongji_type,$logtime, $list ) {
        $this->del($tongji_type,$logtime);
        foreach ($list as $index => $item) {
            $this->add($tongji_type,$logtime,$item["adminid"], $item["value"] , $index+1);
        }
    }
    public function get_list($page_num,$tongji_type,$logtime ) {
        $sql=$this->gen_sql_new("select  *  from  %s where tongji_type=%u and logtime=%u order by top_index  asc",
                                self::DB_TABLE_NAME,
                                $tongji_type,$logtime);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_list_top($tongji_type,$logtime ) {
        $sql=$this->gen_sql_new("select  *  from  %s where tongji_type=%u and logtime=%u order by value desc limit 5",
                                self::DB_TABLE_NAME,
                                $tongji_type,$logtime);
        // \App\Helper\Utils::logger($sql);

        return $this->main_get_list($sql);
    }

    public function get_invit_num($start_time){
        $where_arr = [
            ["ts.logtime = %d",$start_time],
            "m.account_role = 2",
            "ts.tongji_type = 1"
        ];

        $sql = $this->gen_sql_new("  select sum(value) as invit_num from %s m "
                                  ." left join %s ts on m.uid = ts.adminid"
        );

        return $this->main_get_value($sql);

    }

    /*
    public function get_top_index( $tongji_type,$logtime,$adminid ) {
        $sql=$this->gen_sql_new(
            "select top_index from %s "
            ." where tongji_type=%u  and logtime=%u  and adminid=%d ",
            self::DB_TABLE_NAME,
            $tongji_type,
            $logtime,
            $adminid
        ) ;
        return $this->main_get_value($sql);
    }
    */
}
