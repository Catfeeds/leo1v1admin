<?php
namespace App\Models;
class t_seller_student2 extends \App\Models\Zgen\z_t_order_activity_config
{
	public function __construct()
	{
		parent::__construct();
	}
        
    public function get_list($open_flag,$can_disable_flag,$contract_type_list,$period_flag_list,$page_num)
    {
        $where_arr = [
            ["open_flag=%d" , $open_flag,-1 ],
            ["can_disable_flag=%d",$can_disable_flag,-1 ],
            ["contract_type_list=%d" , $contract_type_list,-1 ],
            ["period_flag_list=%d" ,  $period_flag_list,-1 ],
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

    public function get_activity_list_by_id($id){
        $sql=$this->gen_sql("select id,title from %s where id <>%u and open_flag <> 0"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_get_list($sql);

    }

}












