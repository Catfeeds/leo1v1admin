<?php
namespace App\Models;
use \App\Enums as E;
class t_cr_week_month_info extends \App\Models\Zgen\z_t_cr_week_month_info
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_data_by_type($create_time,$type){
        $where_arr = [
            ["create_time=%u",$create_time,-1],
            ["type=%u",$type,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_row($sql);
    }
    public function get_student_list_new($type,$create_time){
    	if($type == 2 || $type == 3){
    		$where_arr = [
	            ["create_time=%u",$create_time,-1],
	            " type=2 or type =3",
	        ];
    	}else if($type ==1){
    		$where_arr = [
	            ["create_time=%u",$create_time,-1],
	            " type=1",
	        ];
    	}

        $sql = $this->gen_sql_new("select student_list  from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }
    public function get_info_by_type_and_time($type,$create_time){
        $where_arr = [
            ["create_time=%u",$create_time,-1],
            ["type=%u",$type,-1]
        ];
        $sql = $this->gen_sql_new("select id from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }
}