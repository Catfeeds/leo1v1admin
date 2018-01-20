<?php
namespace App\Models;
use \App\Enums as E;
class t_user_video_info extends \App\Models\Zgen\z_t_user_video_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_info_by_lessonid($lessonid){
        $sql = $this->gen_sql_new("select count(*) all_num,count(distinct userid) all_user "
                                  ." from %s "
                                  ." where lessonid = %u"
                                  ,self::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_list($sql);
    }

    public function get_all_info($start_time,$end_time){
        $sql = $this->gen_sql_new("select * from %s where time >= %u  ",self::DB_TABLE_NAME,$start_time);
        return $this->main_get_list($sql);
    }
}











