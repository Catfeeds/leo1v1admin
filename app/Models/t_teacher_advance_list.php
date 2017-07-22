<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_advance_list extends \App\Models\Zgen\z_t_teacher_advance_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_info_by_time($page_info,$start_time,$teacher_money_type,$teacherid){
        $where_arr=[
            ["start_time = %u",$start_time,0],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            ["a.teacherid = %u",$teacherid,-1]
        ];
        $sql = $this->gen_sql_new("select a.*,t.realname "
                                  ." from %s a left join %s t on a.teacherid = t.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

}











