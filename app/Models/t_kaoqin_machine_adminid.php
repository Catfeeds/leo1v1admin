<?php
namespace App\Models;
use \App\Enums as E;
class t_kaoqin_machine_adminid extends \App\Models\Zgen\z_t_kaoqin_machine_adminid
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_list_by_adminid( $page_info,$adminid ) {
        //$where_arr=[];
        //$ret_info=$this->where_arr_add_int_field($where_arr,"kma.adminid", $adminid );


        $sql=$this->gen_sql_new(
            "select  km.title, km.machine_id , kma.adminid, kma.auth_flag,km.open_door_flag, km.sn "
            . " from  %s km      "
            . " left join  %s kma  on (kma.machine_id= km.machine_id and adminid = %u  ) "
            , t_kaoqin_machine::DB_TABLE_NAME
            , self::DB_TABLE_NAME
            ,$adminid
            //,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }

    public function get_list($page_info, $machine_id, $adminid, $auth_flag ,$del_flag  =-1) {
        $where_arr=[];
        $this->where_arr_add_int_field($where_arr,"km.machine_id",$machine_id);
        $this->where_arr_add_int_field($where_arr,"adminid",$adminid);
        $this->where_arr_add_int_field($where_arr,"auth_flag",$auth_flag);
        $this->where_arr_add_int_field($where_arr,"del_flag",$del_flag);

        $sql=$this->gen_sql_new(
            "select  km.title, km.machine_id , kma.adminid, kma.auth_flag,km.open_door_flag, km.sn ,m.del_flag "
            . " from  %s kma      "
            . " join  %s  km  on kma.machine_id= km.machine_id "
            . " join  %s  m  on  kma.adminid= m.uid "
            ." where %s "
            , self::DB_TABLE_NAME
            , t_kaoqin_machine::DB_TABLE_NAME
            , t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
}
