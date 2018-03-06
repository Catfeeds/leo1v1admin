<?php
namespace App\Models;
use \App\Enums as E;
class t_info_resource_book extends \App\Models\Zgen\z_t_info_resource_book
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_province_range(){
        //取出所有的省份id
        $sql = $this->gen_sql_new("select province from %s where %s group by province order by id asc"
                                  ,self::DB_TABLE_NAME,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_books($subject,$province,$city,$province_range,$page_info){
        $where_arr = [
            ['subject=%u', $subject, -1],
            ['province=%u', $province, -1],
            ['city=%u', $city, -1],
            ['is_del=%u', 0 ],
        ];
        if($province_range){
            $where_arr[] = ['province in %s', $province_range ];
        }
        $sql = $this->gen_sql_new("select * from %s where %s
                                   order by subject asc,province asc,city asc"
                                  ,self::DB_TABLE_NAME,$where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }


}











