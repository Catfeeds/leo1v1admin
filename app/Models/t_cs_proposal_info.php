<?php
namespace App\Models;
use \App\Enums as E;
class t_cs_proposal_info extends \App\Models\Zgen\z_t_cs_proposal_info
{
	public function __construct()
	{
		parent::__construct();
	}
	public function get_list($page_info,$user_id){
		  $sql = $this->gen_sql("select * from %s where create_adminid  = %d order by create_time desc ",
                              self::DB_TABLE_NAME,
                              $user_id);
        return $this->main_get_list_by_page($sql,$page_info);
	}
}