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
            "shareId='$main_pid'",
            "currentId='$next_openid'",
            ["type=%d",$checkScore,-1]
        ];
        $sql = $this->gen_sql_new("  select id from %s tc"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function getChriDate($shareId, $start_time){
        $where_arr = [
            ["tc.shareId='%s'",$shareId,-1],
            ["add_time>=%u", $start_time, 0]
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

        $sql = $this->gen_sql_new(" select sum(tc.score) as totalScore, tc.shareId, t.phone from %s tc"
                                  ." left join %s t on t.wx_openid=tc.shareId"
                                  ." where %s group by tc.shareId order by totalScore desc $limitStr"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }



    public function getChriDatePage($shareId,$page_num){
        $where_arr = [
            ["tc.shareId=%s",$shareId,""]
        ];

        $sql = $this->gen_sql_new("  select sum(if(tc.type=0,1,0)) as click_num, sum(if(tc.type=1,1,0)) as share_num, sum(if(tc.type=2,1,0)) as register_num, sum(tc.score) as currentScore  from %s tc "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list_by_page($sql, $page_num);
    }

    public function get_total($start_time) {
        $where_arr = [
            ["add_time>=%u", $start_time, 0],
            //["add_time<%u", $end_time, 0]
        ];
        $sql = $this->gen_sql_new("select count(distinct shareId) teacher_num,sum(score) score from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_all_list($start_time) {
        //select t.nick,sum(if(tc.type=0,1,0)) as click_num, sum(if(tc.type=1,1,0)) as share_num, sum(if(tc.type=2,1,0)) as register_num, sum(tc.score) as currentScore  from t_teacher_christmas tc left join t_teacher_info t on tc.shareId=t.wx_openId where tc.add_time >= unix_timestamp('2017-12-25') group by shareId
        $where_arr = [
            ["add_time>=%u", $start_time, 0]
        ];
        $sql = $this->gen_sql_new("select t.realname,t.nick,sum(if(tc.type=0,1,0)) click_num, sum(if(tc.type=1,1,0)) share_num, sum(if(tc.type=2,1,0)) register_num, sum(tc.score) score"
                                  ." from %s tc "
                                  ." left join %s t "
                                  ." on tc.shareId=t.wx_openId "
                                  ." where %s group by shareId order by score desc",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

}











