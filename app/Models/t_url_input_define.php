<?php
namespace App\Models;
class t_url_input_define extends \App\Models\Zgen\z_t_url_input_define
{
	public function __construct()
	{
		parent::__construct();
	}

    public function url_input_define_list($groupid,$url){
        $sql=$this->gen_sql_new("select id,role_groupid,url,field_name,field_val from %s where role_groupid = '%d' and url='%s'"
                                ,self::DB_TABLE_NAME
                                ,$groupid
                                ,$url
        );
        return $this->main_get_list($sql);
    }

    public function url_input_define_id($url,$group_id,$field_name){
        $sql=$this->gen_sql_new("select id from %s where role_groupid = '%d' and url='%s' and field_name='%s'"
                                ,self::DB_TABLE_NAME
                                ,$group_id
                                ,$url
                                ,$field_name
        );
        return $this->main_get_value($sql);
    }
}











