<?php
namespace App\Models;
class t_url_desc_power extends \App\Models\Zgen\z_t_url_desc_power
{
	public function __construct()
	{
		parent::__construct();
	}

    public function url_desc_power_list($groupid,$url){

        $sql=$this->gen_sql_new("select distinct(id),role_groupid,url,opt_key,open_flag from %s where role_groupid = '%d' and url='%s'"
                                ,self::DB_TABLE_NAME
                                ,$groupid
                                ,$url
        );
        return $this->main_get_list($sql);
    }

    public function url_desc_power_id($url,$group_id,$opt_key){
        $sql=$this->gen_sql_new("select id from %s where role_groupid = '%d' and url='%s' and opt_key='%s'"
                                ,self::DB_TABLE_NAME
                                ,$group_id
                                ,$url
                                ,$opt_key
        );
        return $this->main_get_value($sql);
    }
}











