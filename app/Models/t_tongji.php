<?php
namespace App\Models;
class t_tongji extends \App\Models\Zgen\z_t_tongji
{
	public function __construct()
	{
		parent::__construct();
	}
    
    public function get_tongji_info($startday,$endday,$page_num)
    {
         
        $sql = sprintf("select * from %s where log_date >= %s and log_date <= %s order by id  desc",
                       self::DB_TABLE_NAME,
                       $startday,
                       $endday
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }








}











