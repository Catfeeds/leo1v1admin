<?php
namespace App\Models;
use \App\Enums as E;
class t_yxyx_wxnews_info extends \App\Models\Zgen\z_t_yxyx_wxnews_info
{
	public function __construct()
	{
		parent::__construct();
	}
    //添加一条新闻
    public function add_new($title, $des, $pic, $new_link, $adminid, $type, $create_time) {
        $res = $this->row_insert([
            "title" => $title,
            "des" => $des,
            "pic" => $pic,
            "new_link" => $new_link,
            "adminid"  => $adminid,
            "type"  => $type,
            "create_time" => $create_time,
        ]);
        return $res;
    }

    //获取一条新闻
    public function get_one_new_info($id) {
        $where_arr = [
            'id='.$id,
        ];
        $sql =  $this->gen_sql_new( "select *"
                                    . " from %s "
                                    . " where %s "
                                    ,self::DB_TABLE_NAME
                                    ,$where_arr
        );
        return $this->main_get_row($sql);

    }
        //新闻信息
    public function get_news_info($page_info){
        $sql =  $this->gen_sql_new( "select * "
                                    . " from %s "
                                    ,self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }
}











