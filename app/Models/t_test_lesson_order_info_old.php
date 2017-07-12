<?php
namespace App\Models;
use \App\Enums as E;
class t_test_lesson_order_info_old extends \App\Models\Zgen\z_t_test_lesson_order_info_old
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_order_info_by_teacherid($start_time,$end_time,$teacherid,$teacher_money_type,$subject){
        $where_arr = [
            ["first_lesson_time >= %u",$start_time,-1],
            ["first_lesson_time <= %u",$end_time,-1],
            ["tl.teacherid = %u",$teacherid,-1],
            ["t.teacher_money_type = %u",$teacher_money_type,-1],
            ["t.subject = %u",$subject,-1]
        ];
        $sql = $this->gen_sql_new("select sum(if(test_lesson_time >0,1,0)) order_count,tl.teacherid "
                                  ." from %s tl left join %s t on tl.teacherid = t.teacherid "
                                  ." where %s group by tl.teacherid ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
 
    }

    public function get_all_info(){
        $sql = $this->gen_sql_new("select userid,teacherid,first_lesson_time from %s",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

}











