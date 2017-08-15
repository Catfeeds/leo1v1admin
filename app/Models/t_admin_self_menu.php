<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_self_menu extends \App\Models\Zgen\z_t_admin_self_menu
{

    public function __construct()
    {
        parent::__construct();
    }
    public function get_new_order_index($adminid ) {
        $sql=$this->gen_sql_new("select max(order_index)  from  %s where adminid=%u ",
                                self::DB_TABLE_NAME, $adminid);

        return $this->main_get_value($sql)+1;
    }

    public function  switch_order_index( $id1, $id2 )  {
        $order_index1= $this->get_order_index($id1);
        $order_index2= $this->get_order_index($id2);

        $this->set_order_index($id1,NULL);
        $this->set_order_index($id2, $order_index1 );
        $this->set_order_index($id1, $order_index2 );

    }
    public function add( $adminid,$title,  $url, $icon ) {
        $order_index=$this->get_new_order_index($adminid);
        $this->row_insert([
            "adminid" => $adminid,
            "title" => $title,
            "url" => $url,
            "icon" => $icon,
            "order_index" => $order_index,
        ]);
    }
    public function del($adminid, $id  ) {
        if ($this->get_adminid($id) ==$adminid) {
            $this->row_delete($id);
        }
        return true;
    }
    public function get_id_by_admin_order_index( $adminid,$order_index ) {

        $sql=$this->gen_sql_new(
            "select id from  %s where adminid=%u and  order_index= %u ",
            self::DB_TABLE_NAME, $adminid, $order_index);
        return $this->main_get_value($sql);
    }

    public function get_next_order_index( $adminid, $order_index ,$next_flag  ) {
        if ($next_flag) {
            $sql=$this->gen_sql_new(
                "select min(order_index)  from  %s where adminid=%u and  order_index> %u ",
                self::DB_TABLE_NAME, $adminid, $order_index);
        }else{
            $sql=$this->gen_sql_new(
                "select max(order_index)  from  %s where adminid=%u and  order_index< %u ",
                self::DB_TABLE_NAME, $adminid, $order_index);
        }
        return $this->main_get_value($sql);
    }

    public function set_order_index( $id, $order_index  ) {
        $this->field_update_list($id,[
            "order_index" => $order_index,
        ]);
    }
    public function get_list( $adminid ) {
        $sql=$this->gen_sql_new("select * from %s where adminid=%u order by order_index asc",
                                self::DB_TABLE_NAME,
                                $adminid);
        return $this->main_get_list($sql);
    }
    public function get_url_info( $adminid, $url ) {
        $where_arr=[
            "adminid" => $adminid,
            "url" => $url,
        ];
        $sql= $this->gen_sql_new(
            "select  * from %s where %s  ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    public function get_menu_config($adminid) {
        $list=$this->get_list($adminid);
        $data=[];
        foreach ($list as $item )  {
            $data[]= ["name"=>$item["title"], "url" => $item["url"] ];
        }
        $arr=[[
            "name"=>"我的收藏",
            "list"=> $data
        ]];
        return $arr;
    }

}
