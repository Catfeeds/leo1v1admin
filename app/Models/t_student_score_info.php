<?php
namespace App\Models;
use \App\Enums as E;
class t_student_score_info extends \App\Models\Zgen\z_t_student_score_info
{
	public function __construct()
	{
		parent::__construct();
	}
	public function get_list($page_info,$user_id){
               // dd($user_id);
		$sql = $this->gen_sql("select * from %s where userid = %d",
                              self::DB_TABLE_NAME,
                              // t_student_score_info::DB_TABLE_NAME,
                              $user_id);
        // dd($sql);
		//return $this->main_gen_list_as_page($sql, $page_num,10);
        return $this->main_get_list_by_page($sql,$page_info);
	}
}











