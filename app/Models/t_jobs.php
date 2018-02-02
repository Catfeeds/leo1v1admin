<?php
namespace App\Models;
class t_jobs extends \App\Models\Zgen\z_jobs
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_all_count() {
        $sql=$this->gen_sql_new("select count(*) from %s ",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_list( $page_info, $query_text) {
        $where_arr=[];

        $where_arr[]=sprintf("payload like '%%%s%%'", $query_text);
        $sql=$this->gen_sql_new("select * from %s where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_list_by_page($sql, $page_info);
    }


}











