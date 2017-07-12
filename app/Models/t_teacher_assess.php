<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_assess extends \App\Models\Zgen\z_t_teacher_assess
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_assess_info($teacherid,$page_num,$stat_time=-1,$end_time=-1){
        $where_arr = [
            ['teacherid = %u',$teacherid,-1],
            ['assess_time >= %u',$stat_time,-1],
            ['assess_time <= %u',$end_time,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by assess_time desc ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }
}











