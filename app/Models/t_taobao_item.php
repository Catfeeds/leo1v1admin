<?php
namespace App\Models;
use \App\Enums as E;
class t_taobao_item extends \App\Models\Zgen\z_t_taobao_item
{
	public function __construct()
	{
		parent::__construct();
	}

    public function reset_taobao_status(){
        $sql=$this->gen_sql_new("update %s set status=0"
                                ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function truncate_taobao(){
        $sql=$this->gen_sql_new("truncate %s"
                                ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function get_taobao_item($open_iid){
        $sql=$this->gen_sql_new("select product_id,sort_order from %s where open_iid='%s'"
                                ,self::DB_TABLE_NAME
                                ,$open_iid
        );
        return $this->main_get_row($sql);
    }

    public function get_taobao_item_list($cid,$type=1){
        $where_str=$this->where_str_gen([
            ["cid like '%%%s%%'",$cid,0],
        ]);
        $str=$type==1?" limit 4 ":" ";
        $sql=$this->gen_sql("select open_iid,title,price,pict_url,product_id from %s"
                            ." where %s"
                            ." and product_id>0"
                            ." order by sort_order,last_modified desc"
                            ." %s"
                            ,self::DB_TABLE_NAME
                            ,[$where_str]
                            ,$str
        );
        return $this->main_get_list($sql);
    }

    public function get_taobao_item_by_page($page_num,$cid,$status){
        $where_arr=[
            ["cid like '%%%s%%'",$cid,-1],
            ["status=%d",$status,-1],
        ];
        $sql=$this->gen_sql_new("select * from %s"
                                ." where %s"
                                ." order by sort_order desc,last_modified desc"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function check_taobao_product($product_id){
        $sql=$this->gen_sql_new("select count(1) from %s where product_id='%s'"
                                ,self::DB_TABLE_NAME
                                ,$product_id
        );
        return $this->main_get_value($sql);
    }

    public function get_all_item_list(){
        $sql = $this->gen_sql_new("select open_iid,product_id,price"
                                  ." from %s "
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }


}
