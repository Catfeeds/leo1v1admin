<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_christmas extends \App\Models\Zgen\z_t_teacher_christmas
{
	public function __construct()
	{
		parent::__construct();
	}

    public function checkHasAdd($main_pid,$next_openid,$checkScore=-1){
        $where_arr = [
            "teacherid=$main_pid",
            "next_openid='$next_openid'",
            ["type=%d",$checkScore,-1]
        ];
        $sql = $this->gen_sql_new("  select id from %s tc"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function getChriDate($teacherid){
        $where_arr = [
            ["tc.teacherid=%d",$teacherid,-1]
        ];

        $sql = $this->gen_sql_new("  select sum(if(tc.type=0,1,0)) as click_num, sum(if(tc.type=1,1,0)) as share_num, sum(if(tc.type=2,1,0)) as register_num, sum(tc.score) as currentScore  from %s tc "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function getTotalList($isLimit=-1){
        $where_arr = [];
        $start_time = strtotime("2017-12-20");
        $end_time = strtotime("2018-1-2");
        if($isLimit == -1){
            $limitStr = 'limit 60';
        }else{
            $limitStr = '';
        }

        $this->where_arr_add_time_range($where_arr, "tc.add_time", $start_time, $end_time);

        $sql = $this->gen_sql_new(" select sum(tc.score) as totalScore, tc.teacherid, t.phone from %s tc"
                                  ." left join %s t on t.teacherid=tc.teacherid"
                                  ." where %s group by tc.teacherid order by totalScore desc $limitStr"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }



    public function getChriDatePage($teacherid,$page_num){
        $where_arr = [
            ["tc.teacherid=%d",$teacherid,-1]
        ];

        $sql = $this->gen_sql_new("  select sum(if(tc.type=0,1,0)) as click_num, sum(if(tc.type=1,1,0)) as share_num, sum(if(tc.type=2,1,0)) as register_num, sum(tc.score) as currentScore  from %s tc "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list_by_page($sql, $page_num);
    }

}











