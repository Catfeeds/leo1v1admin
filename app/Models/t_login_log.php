<?php
namespace App\Models;
use \App\Models\Zgen as Z;
class t_login_log extends \App\Models\Zgen\z_t_login_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_login_list($account,$flag,$date_start,$date_end,$login_info,$page_num)
    {

        $where_str=$this->where_str_gen([
            array("account='%s'",$account,-1),
            array("flag=%d",$flag,-1),
        ]);
        if($login_info != ""){
            $where_str .= " and (account like '%".$this->ensql($login_info)."%' or id like '%".$this->ensql($login_info)."%') ";
        }

        $sql=sprintf("select  account, count(*) as all_count, sum(flag=1 ) as succ, sum(flag=0) as fail  from %s where login_time > %u and login_time < %u and %s group by account ",
                     self::DB_TABLE_NAME,
                     //Z\z_t_news_activity_info::DB_TABLE_NAME,
                     $date_start,
                     $date_end,
                     $where_str
        );
        return $this->main_get_list_by_page( $sql,$page_num,10,true );
    }
    public function get_login_list_flag($del_flag,$account,$date_start,$date_end)
    {
        $where_str=$this->where_str_gen([
            array("flag=%d",$del_flag,-1),
            array("account='%s'",$account,-1),
        ]);

        $sql=sprintf("select count(*) from %s where login_time > %u and login_time < %u and %s group by account",
                     self::DB_TABLE_NAME,
                     $date_start,
                     $date_end,
                     $where_str
        );

        return $this->main_get_value($sql);
    }



    public function add( $account, $ip, $flag   ) {                
        $sql=$this->gen_sql_new("update %s SET last_login_time = %u where account = '%s'",
                     t_manager_info::DB_TABLE_NAME,
                     time(),
                     $account
        );
        $this->main_update($sql);
        return $this->row_insert([
            'account'    => $account,
            'ip'         => $ip,
            'flag'       => $flag,
            "login_time" => time(NULL),
        ]);
 
    }

    public function get_login_list_by_time_new($start_time,$end_time,$account,$page_num){
        $where_arr=[
            ["login_time >=%u",$start_time,-1],
            ["login_time <=%u",$end_time,-1],
            ["account like '%%%s%%'",$account,""]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by login_time desc",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function  tongji_login_ip_info_new($start_time,$end_time,$account){       
        $where_arr=[
            ["login_time >=%u",$start_time,-1],
            ["login_time <=%u",$end_time,-1],
            ["account like '%%%s%%'",$account,""]
        ];
        $sql = $this->gen_sql_new("select count(*) all_count,sum(flag =1) suc_count,ip from %s where %s group by ip order by all_count desc",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);

    }
}
