<?php
namespace App\Models;
class t_festival_info extends \App\Models\Zgen\z_t_festival_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_festival_list($page_num){
        $sql=$this->gen_sql("select * from %s"
                            ,self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_num,1000);
    }
    
    public function add_festival_info($date_str,$festival_str){
        $this->row_insert([
            self::C_date_str     => $date_str,
            self::C_festival_str => $festival_str,
        ]);
    }
}











