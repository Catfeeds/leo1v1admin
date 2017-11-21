<?php
namespace App\Models;
class t_seller_student2 extends \App\Models\Zgen\z_t_order_activity_config
{
	public function __construct()
	{
		parent::__construct();
	}
        
    public function get_list($admin_revisiterid,  $phone, $origin, $start_time,$end_time,
                             $grade,
                             $subject,
                             $page_num  )
    {
        $where_arr=[
            ["add_time>=%d" ,  $start_time,-1 ],
            ["add_time<%d" ,  $end_time,-1 ],
            ["admin_revisiterid=%d" ,  $admin_revisiterid ,-1 ],
            ["grade=%d", $grade,-1 ] ,
            ["subject=%d", $subject,-1 ] ,
            ["origin like \"%%%s%%\" ", $origin,"" ] ,
            ["phone like \"%%%s%%\" ", $phone ,"" ] ,
        ];
        
        $where_str=$this->where_str_gen( $where_arr);
        $sql = $this->gen_sql("select * from %s where  %s order by id desc ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        $ret_info= $this->main_get_list_by_page($sql,$page_num,10);

        return  $this->reset_phone_location($ret_info); 
    }
    
    public function set_activity_info($phone,$admin_revisiterid){
        $sql = sprintf("update %s set admin_revisiterid = %u  where phone = '%s'",
                       self::DB_TABLE_NAME,
                       $admin_revisiterid,
                       $phone
        );
        $this->main_update( $sql  ); 
    }

    public function add_activity_info($phone,$admin_revisiterid){

    }
}












