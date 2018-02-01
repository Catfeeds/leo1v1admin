<?php
namespace App\Models;
use App\Enums as E;
class t_resource_file_evalutation extends \App\Models\Zgen\z_t_resource_file_evalutation
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_is_eval($file_id, $teacherid){
    	$where_arr = [
    		[" file_id=%u",$file_id,-1],
    		[" teacherid=%u",$teacherid,-1],
    	];
    	$sql = $this->gen_sql_new(" select count(*) "
    							." from %s "
    							." where %s "
    							,self::DB_TABLE_NAME
    							,$where_arr);
    	return $this->main_get_value($sql);
    }
}