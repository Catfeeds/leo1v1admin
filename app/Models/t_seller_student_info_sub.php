<?php
namespace App\Models;
class t_seller_student_info_sub extends \App\Models\Zgen\z_t_seller_student_info_sub
{
	public function __construct()
	{
		parent::__construct();
	}
    public function set_seller_info($phone,$admin_revisiterid){
        $sql = sprintf("update %s set admin_revisiterid = %u  where phone = '%s'",
                       self::DB_TABLE_NAME,
                       $admin_revisiterid,
                       $phone
        );
        $this->main_update( $sql  ); 
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
    public function reset_phone_location($ret_info) {
        foreach  (  $ret_info["list"] as &$item) {
            if (!$item["phone_location"] ) {
                //设置到数据库
                $item["phone_location"] = \App\Helper\Common::get_phone_location($item["phone"]);
                if ($item["phone_location"]) {
                    $this->field_update_list($item["id"] ,[
                        "phone_location"  =>   $item["phone_location"]
                    ]);
                }
            }
        }
        return $ret_info  ;
    }
    public function get_need_noti_list($start_time,$end_time ) {
        $sql = $this->gen_sql("select phone,origin,grade,subject,has_pad from %s".
                              " where add_time>=%u and add_time<%u and ".
                              " origin in ('APP课程包' )",
                              self::DB_TABLE_NAME,$start_time, $end_time);
        return $this->main_get_list($sql);
    }



}












