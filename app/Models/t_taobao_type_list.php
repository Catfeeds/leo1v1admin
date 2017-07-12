<?php
namespace App\Models;
use \App\Enums as E;
class t_taobao_type_list extends \App\Models\Zgen\z_t_taobao_type_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function reset_taobao_type_status(){
        $sql=$this->gen_sql_new("update %s set status=0"
                                ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function get_taobao_select_list($parent_cid){
        $where_arr=[
            ['parent_cid=%d',$parent_cid,-1],
        ];
        $sql=$this->gen_sql_new("select * from %s "
                                ." where %s "
                                ." and status=1"
                                ." order by sort_order"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_taobao_type_list($type){
        $where_arr=[
            ["type=%d",$type,-1],
        ];
        $sql=$this->gen_sql_new("select cid,name,sort_order,type"
                                ." from %s"
                                ." where %s"
                                ." and status=1"
                                ." order by type desc"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list_by_page($sql,1,1000);
    }

}











