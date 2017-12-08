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

    public function get_new_create_festival_list($page_info,$start_time,$end_time){
        $where_arr=[
            ["begin_time>=%u",$start_time,0],  
            ["begin_time<%u",$end_time,0],
            "days>0"
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_festival_info_by_end_time($end_time){
        $where_arr=[ 
            ["end_time=%u",$end_time,0],
            "days>0"
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_row($sql);
    }

    public function check_is_holiday($day_time){
        $where_arr=[
            ["begin_time<=%u",$day_time,0],  
            ["end_time>=%u",$day_time,0],
            "days>0"
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function get_holiday_info_by_day($day_time){
        $where_arr=[
            ["begin_time<=%u",$day_time,0],  
            ["end_time>=%u",$day_time,0],
            "days>0"
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_row($sql);

    }

}











