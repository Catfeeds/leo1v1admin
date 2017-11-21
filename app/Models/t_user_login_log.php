<?php
namespace App\Models;
use \App\Enums as E;
class t_user_login_log extends \App\Models\Zgen\z_t_user_login_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login_list($page_info,$userid,$dymanic_flag){
        $where_arr=[
            ["dymanic_flag=%u",$dymanic_flag,-1],
            ["userid=%u",$userid,],
        ];
        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  ." where %s "
                                  ." order by login_time desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_login_list($page_info, $start_time, $end_time,$userid, $ip ){
        $where_arr=[
            ["ip='%s'",$ip,""],
            ["userid=%u",$userid,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"login_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  ." where %s "
                                  ." order by login_time desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }



    public function get_login_tongji( $start_time, $end_time  ) {
        $where_arr=[];
        $this->where_arr_add_boolean_for_value($where_arr,"lesson_count_all" ,1 );
        $this->where_arr_add_time_range($where_arr,"login_time",$start_time,$end_time);
        $sql= $this->gen_sql_new(
            "select ip,  count( distinct s.userid ) as user_count"
            . " from %s lo"
            . " join %s s on lo.userid = s.userid  "
            . " where  %s group by ip having user_count >1 ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql);

    }

    public function get_pay_stu_ip_list($start_time,$end_time,$match_type,$ip_str){
        $where_arr=[
            "s.is_test_user=0",
            ["ul.login_time >=%u",$start_time,0],
            ["ul.login_time <%u",$end_time,0],
            "s2.userid>0",
            "ul.ip not in ".$ip_str
        ];
        $order_flag=true;
        if(in_array($match_type,[0,1])){
            if($match_type==1){
                $exists_str = "not exists";
            }elseif($match_type==0){
                $exists_str = "exists";
            }
            $order_flag = $this->gen_sql_new("%s (select 1 from %s where contract_status>0 and contract_type in (0,3) and userid = s2.userid and price>0)"
                                             ,$exists_str
                                             ,t_order_info::DB_TABLE_NAME
            );
        }
        

        $sql = $this->gen_sql_new("select distinct s.nick,ul.userid,ul.ip,s2.userid s2_userid,s2.nick s2_nick"
                                  ." ,s.phone,s2.phone s2_phone,s.grade "
                                  ." from %s ul "
                                  ." left join %s s on ul.userid = s.userid and exists (select 1 from %s where contract_status>0 and contract_type in (0,3) and userid = s.userid and price>0)"
                                  ." left join %s ul2 on ul.ip = ul2.ip and ul.userid != ul2.userid"
                                  ." left join %s s2 on ul2.userid = s2.userid and s2.is_test_user=0 and %s"                                  
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $order_flag,
                                  $where_arr
        );
        return $this->main_get_list($sql);
  
    }

    


}
