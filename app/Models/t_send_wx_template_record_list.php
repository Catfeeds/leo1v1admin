<?php
namespace App\Models;
use \App\Enums as E;
class t_send_wx_template_record_list extends \App\Models\Zgen\z_t_send_wx_template_record_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_send_wx_template_record_list($page_num){
        $sql = $this->gen_sql_new("select * from %s ",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }
}











