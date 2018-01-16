<?php
namespace App\Models;
use \App\Enums as E;
class t_user_log extends \App\Models\Zgen\z_t_user_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($page_info, $start_time, $end_time) {
        $whereArr = [
            ["add_time>%u", $start_time, 0],
            ["add_time<%u", $end_time, 0],
        ];

        $sql = $this->gen_sql_new("select id,add_time,userid,adminid,msg from %s where %s order by add_time desc",
                                  self::DB_TABLE_NAME,
                                  $whereArr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function add_data($msg,$userid='') {
        return $this->row_insert([
            'userid' => $userid,
            'adminid' => session('adminid'),
            'msg' => $msg,
            'add_time' => time()
        ]);
    }

    public function add_data_new($msg,$userid='',$type=0) {
        return $this->row_insert([
            'userid' => $userid,
            'adminid' => session('adminid'),
            'msg' => $msg,
            'user_log_type' => $type,
            'add_time' => time()
        ]);
    }


}











