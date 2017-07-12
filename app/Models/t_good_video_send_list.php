<?php
namespace App\Models;
use \App\Enums as E;
class t_good_video_send_list extends \App\Models\Zgen\z_t_good_video_send_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_video_send_num($start,$end,$subject){
        $where_arr = [
            ["send_time >= %u",$start,-1],
            ["send_time <%u",$end,-1],
            ["subject= %u",$subject,-1]
        ];
        $sql = $this->gen_sql_new("select count(*) send_num from %s where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_all_video_senf_info($start_time,$end_time,$subject,$page_num){
        $where_arr = [
            ["send_time >= %u",$start_time,-1],
            ["send_time <%u",$end_time,-1],
            ["subject= %u",$subject,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);

    }
}











