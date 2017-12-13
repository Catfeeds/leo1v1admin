<?php
namespace App\Models;
class t_student_call_data extends \App\Models\Zgen\z_t_student_call_data
{
    public function __construct()
    {
        parent::__construct();
    }
    public function check($userid){
        $sql = "select count(userid) from db_weiyi.t_student_call_data where userid = $userid";
        return $this->main_get_value($sql);
    }
    public function get_all_data($page_num,$start_time,$end_time){
    	$where_arr = [
    		['add_time>%s',$start_time,-1],
    		['add_time<%s',$end_time,-1],
    	];
    	$sql = $this->gen_sql_new("select * "
    							." from %s "
    							." where %s "
    							,self::DB_TABLE_NAME
    							,$where_arr);
    	//dd($sql);
    	return $this->main_get_list_by_page($sql,$page_num);
    }
}
