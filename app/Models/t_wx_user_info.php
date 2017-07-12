<?php
namespace App\Models;
class t_wx_user_info extends \App\Models\Zgen\z_t_wx_user_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function add_or_udpate($user_info) {
        unset ($user_info["privilege"]);
        $user_info["update_time"]=time();
        return $this->row_insert($user_info,true);
    }
    public function get_list_for_ajax_list( $page_num,$nickname)  {
        $where_arr=[
            ["nickname like  '%%%s%%'", $nickname,""], 
        ];
        $sql=$this->gen_sql_new("select * from %s where %s order by update_time desc",
                                self::DB_TABLE_NAME,
                                $where_arr);

        return $this->main_get_list_by_page($sql,$page_num);
    }

}











