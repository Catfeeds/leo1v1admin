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

    public function url_desc_power_by_gid($gid_str){
        $sql=$this->gen_sql_new("select id,role_groupid,url,opt_key,open_flag from %s where role_groupid in (%s) "
                                ,self::DB_TABLE_NAME
                                ,$gid_str
        );
        $data = $this->main_get_list($sql);
        $item = [];
        if($data){
            //当两个不同的role_groupid拥有相同url,opt_key但是open_flag不一样，意味着的权限出现冲突
            $arr1 = [];
            $arr2 = [];
            foreach($data as $k=>$v){
                if( $v['open_flag'] == 1 ){
                    //有该权限
                    $arr1[$v['id']] = $v['url'].$v['opt_key'];
                    //去重
                    $arr1 = array_unique($arr1);
                }else{
                    //禁止该权限
                    $arr2[$v['id']] = $v['url'].$v['opt_key'];
                    //去重
                    $arr2 = array_unique($arr2);
                }
            }
            
            $result=array_diff($arr2,$arr1);
            $arr = array_merge($result,$arr1);
            $arr = array_keys($arr);
            if( count($arr) < count($data) ){

                foreach($data as $k => $var){
                    if(!in_array($var['id'], $arr)){
                        unset($data[$k]);
                    }
                }
            }
        }

        return $data;
    }

}











