<?php
namespace App\Models;
use \App\Enums as E;
class t_cs_intended_user_info extends \App\Models\Zgen\z_t_cs_intended_user_info
{
	public function __construct()
	{
		parent::__construct();
	}
	public function get_list($page_info,$user_id){
        $where_arr=[
            ["create_adminid=%u",$user_id,-1] 
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by create_time desc ",
                              self::DB_TABLE_NAME,
                              $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
	}
	public function get_intended_info_by_phone($phone){
        $where_arr = [
            ["phone='%s'",$phone,""]
        ];
        $sql = $this->gen_sql_new("select id"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

}