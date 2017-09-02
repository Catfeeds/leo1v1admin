<?php
namespace App\Models;
use \App\Enums as E;
class t_audio_record_server extends \App\Models\Zgen\z_t_audio_record_server
{
	public function __construct()
	{
		parent::__construct();
	}
    public function add_server( $ip )  {
        $now=time();
        $sql=$this->gen_sql("INSERT INTO %s (ip,last_active_time,priority) VALUES ('%s',%u,1)  ".
                            "ON DUPLICATE KEY UPDATE last_active_time=%u  ",
                            self::DB_TABLE_NAME,$ip,$now,$now);
        return $this->main_update($sql);
    }
    public function get_active_server_list() {
        $min_time=time()-120;
        $sql=$this->gen_sql(
            "select ip, priority, max_record_count from %s where priority>0 and last_active_time  >%u ",
            self::DB_TABLE_NAME,
            $min_time);
        return $this->main_get_list($sql,function($item){
            return $item["ip"];
        });
    }

    public function get_server_list($page_num,$ip="") {
        $where_arr=[
            ["ip='%s'", $ip, ""] ,
        ];

        $sql=$this->gen_sql_new("select * from %s where %s  order by ip asc",
                            self::DB_TABLE_NAME ,$where_arr);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_server_map() {
        $sql=$this->gen_sql_new("select * from %s where %s  ",
                                self::DB_TABLE_NAME );
        return $this->main_get_list($sql,function($item){
            return $item["ip"];
        });
    }


    public function get_ip_from_config_userid($config_userid) {
        $sql=$this->gen_sql("select ip   from %s where config_userid=%u",
                            self::DB_TABLE_NAME ,$config_userid);
        return $this->main_get_value($sql);
    }
}











