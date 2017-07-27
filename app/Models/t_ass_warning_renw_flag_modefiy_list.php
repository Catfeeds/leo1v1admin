<?php
namespace App\Models;
use \App\Enums as E;
class t_ass_warning_renw_flag_modefiy_list extends \App\Models\Zgen\z_t_ass_warning_renw_flag_modefiy_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_info_by_warning_id($warning_id){
        $where_arr=[
            ["warning_id=%u",$warning_id,-1]  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by add_time desc",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_new_renw_list($warning_id){
        $where_arr=[
            ["warning_id=%u",$warning_id,-1]  
        ];
        $sql = $this->gen_sql_new("select a.* from %s a where add_time= (select max(add_time) from %s where warning_id=a.warning_id) and %s",
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_first_renw_time($warning_id){
        $where_arr=[
            ["warning_id=%u",$warning_id,-1]  
        ];
        $sql = $this->gen_sql_new("select a.add_time from %s a where add_time= (select min(add_time) from %s where warning_id=a.warning_id) and %s",
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }


    public function get_new_renw_list_new($warning_id){
        $where_arr=[
            ["warning_id=%u",$warning_id,-1]  
        ];
        $sql = $this->gen_sql_new("select a.* from %s a where add_time= (select max(add_time) from %s where warning_id=a.warning_id) and %s",
                                  self::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }


}











