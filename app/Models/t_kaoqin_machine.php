<?php
namespace App\Models;
use \App\Enums as E;
class t_kaoqin_machine extends \App\Models\Zgen\z_t_kaoqin_machine
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_list($page_info) {
        $sql=$this->gen_sql_new(
            "select * from %s ", self::DB_TABLE_NAME );
        return $this->main_get_list_by_page($sql,$page_info);

    }
    public function set_last_post_time (  $sn ){
        $sql= $this->gen_sql_new(
            "update %s  set last_post_time=%u where sn ='%s' ",
            self::DB_TABLE_NAME,
            time(NULL),
            $sn
        );
        $ret= $this->main_update($sql);
        if (!$ret) {
            $ret=$this->row_insert([
                "sn" => $sn
            ]);
        }
        return !$ret;
    }

    public function send_cmd( $machine_id,$data)  {
        $sn=$this->get_sn($machine_id);
        $this->send_cmd_by_sn($sn,$data);
    }
    public function get_info_by_sn($sn ) {
        $where_arr=[
            "sn" =>$sn
        ];
        $sql=$this->gen_sql_new("select * from %s where %s ",
                           self::DB_TABLE_NAME,
                           $where_arr );

        return $this->main_get_row($sql);
    }

    public function send_cmd_by_sn( $sn,$data)  {
        $key="kaoqin_$sn";
        $sync_data_list=\App\Helper\Common::redis_get_json($key);
        if (!is_array($sync_data_list)) {
            $sync_data_list=[];
        }
        $sync_data_list[]=$data;
        \App\Helper\Common::redis_set_json($key, $sync_data_list );
    }
    public function send_cmd_unlock( $machine_id ) {
        //{id:”1011”,do:”cmd”,cmd:”unlock”,delay:10}
        $this->send_cmd($machine_id, [
            "id"=>time(NULL),
            "do"=>"cmd",
            "cmd"=>"unlock",
            "delay"=>10,
        ]);
    }

    public function send_cmd_reboot( $machine_id ) {
        //{id:”1010”,do:”cmd”,cmd:”reboot”}
        $this->send_cmd($machine_id, [
            "id"=>time(NULL),
            "do"=>"cmd",
            "cmd"=>"reboot",
        ]);
    }
}
