<?php
namespace App\Models;
use \App\Enums as E;
class t_field_modified_list extends \App\Models\Zgen\z_t_field_modified_list
{
	public function __construct()
	{
		parent::__construct();
	}


    public function add_modified_teacher_info($t_name,$f_name,$last_value,$cur_value,$adminid,$teacherid){
        $ret =  $this->row_insert([
            "modified_time"    =>time(),
            "t_name"           =>$t_name,
            "f_name"           =>$f_name,
            "last_value"       =>$last_value,
            "cur_value"        =>$cur_value,
            "adminid"          =>$adminid,
            "teacherid"        =>$teacherid
        ]);
        return $ret;
    }
    public function get_all_info(){
        $sql=$this->gen_sql_new("select * from %s",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

}











