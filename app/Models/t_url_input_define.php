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

    public function url_input_define_by_gid($gid_str){
        $sql=$this->gen_sql_new("select id,url,field_name,field_val,field_type,GROUP_CONCAT(field_val) as field_val_str
                                 from %s where role_groupid in (%s) group by url,field_name"
                                ,self::DB_TABLE_NAME
                                ,$gid_str
        );
        $data = $this->main_get_list($sql);
        if($data){
            $arr = [];
            foreach($data as $k => $v){
                $arr[$v['id']] = $v['url'].$v['field_name'].$v['field_val'];
            }
            $arr_uni = array_unique($arr);
            if(count($arr_uni) < count($arr) ){
                foreach($data as $k => $v){
                    if(array_key_exists($v['id'], $arr_uni)){
                        unset($data[$k]);
                    }
                }
            }
        }
        return $data;
    }
}











