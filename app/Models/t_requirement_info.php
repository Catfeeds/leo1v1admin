<?php
namespace App\Models;
use \App\Enums as E;
class t_requirement_info extends \App\Models\Zgen\z_t_requirement_info
{
	public function __construct()
	{
		parent::__construct();
	}
  public function get_list_requirement($page_info,$userid,$name,$priority,$significance,$status,$product_status,$development_status, $test_status,$now_status,$start_time,$end_time)
    {
        $where_arr=[
            ["create_time>=%u", $start_time, -1 ],
            ["create_time<=%u", $end_time, -1 ],
            ['create_adminid=%u',$userid,-1],
            ["name=%u",$name,-1],
            ["priority=%u",$priority,-1], 
            ["significance=%u",$significance,-1],
            ["status=%u",$status,-1],
            ["product_status=%u",$product_status,-1],
            ["development_status=%u",$development_status,-1],
            ["test_status=%u",$test_status,-1],
        ];
        $sql = $this->gen_sql_new("select * from %s "
                                  ."where %s  and del_flag = 0 order by create_time desc ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
  }
  public function get_list_product($page_info,$userid,$name,$priority,$significance,$status,$product_status,$development_status, $test_status,$now_status,$start_time,$end_time)
    {
        $where_arr=[
            ["create_time>=%u", $start_time, -1 ],
            ["create_time<=%u", $end_time, -1 ],
            ["name=%u",$name,-1],
            ["priority=%u",$priority,-1], 
            ["significance=%u",$significance,-1],
            ["status=%u",$status,-1],
            ["product_status=%u",$product_status,-1],
            ["development_status=%u",$development_status,-1],
            ["test_status=%u",$test_status,-1],
            ["product_operator = %u or product_status = 0",$userid,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s "
                                  ."where %s  and del_flag = 0 order by create_time desc ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
  }
  public function get_list_development($page_info,$userid,$name,$priority,$significance,$status=3,$development_status, $test_status,$now_status,$start_time,$end_time)
    {
        $where_arr=[
            ["create_time>=%u", $start_time, -1 ],
            ["create_time<=%u", $end_time, -1 ],
            ["name=%u",$name,-1],
            ["priority=%u",$priority,-1], 
            ["significance=%u",$significance,-1],
            ["status=%u",$status,-1],
            ["development_status=%u",$development_status,-1],
            ["test_status=%u",$test_status,-1],
            ["development_operator = %u or (development_status = 0 and status >= 3)",$userid,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s "
                                  ."where %s  and del_flag = 0 order by create_time desc ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
  }
  public function get_list_test($page_info,$userid,$name,$priority,$significance,$status, $test_status,$now_status,$start_time,$end_time)
    {
        $where_arr=[
            ["create_time>=%u", $start_time, -1 ],
            ["create_time<=%u", $end_time, -1 ],
            ["name=%u",$name,-1],
            ["priority=%u",$priority,-1], 
            ["significance=%u",$significance,-1],
            ["status=%u",$status,-1],
            ["test_status=%u",$test_status,-1],
            ["test_operator = %u or (test_status = 0 and status >= 4) or (test_status = 5 and status = 5)",$userid,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s "
                                  ."where %s  and del_flag = 0 order by create_time desc ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
  }
}