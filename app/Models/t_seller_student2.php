<?php
namespace App\Models;
class t_seller_student2 extends \App\Models\Zgen\z_t_order_activity_config
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_list($open_flag,$can_disable_flag,$page_num)
    {
        $where_arr = [
            ["open_flag=%d" , $open_flag,-1 ],
            ["can_disable_flag=%d",$can_disable_flag,-1 ],
        ];

        $where_str=$this->where_str_gen( $where_arr);
        $sql = $this->gen_sql("select * from %s where  %s order by id desc ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,$page_num,10);

    }

    public function set_activity_info($phone,$admin_revisiterid){
        $sql = sprintf("update %s set admin_revisiterid = %u  where phone = '%s'",
                       self::DB_TABLE_NAME,
                       $admin_revisiterid,
                       $phone
        );
        $this->main_update( $sql  );
    }

    public function del_by_id($id){
        $sql=$this->gen_sql("delete from %s where id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_update($sql);

    }

    public function get_by_id($id){
        $sql=$this->gen_sql("select * from %s where id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_get_row($sql);
    }

    public function get_by_activity_id($id,$activity_id){
        $sql=$this->gen_sql("select id,activity_id from %s where id !=%u and activity_id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
                            ,$activity_id
        );
        return $this->main_get_row($sql);
    }


    public function get_activity_all_list($id){
        $sql=$this->gen_sql("select id,title from %s where id <> %u "
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_get_list($sql);
    }

    public function get_activity_exits_list($idStr){
        if( $idStr && !strpos($idStr, ")") ){
            $idStr = "(" .$idStr. ")";
            $sql=$this->gen_sql("select id,title from %s where id in %s "
                                ,self::DB_TABLE_NAME
                                ,$idStr
            );
            return $this->main_get_list($sql);

        }
        return null;
    }

}
