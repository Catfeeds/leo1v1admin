<?php
namespace App\Models;
class t_lesson_opt_log extends \App\Models\Zgen\z_t_lesson_opt_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_online_flag ($lessonid,$userid, $check_time)   {

        $login_time  = $this->get_login_start_time( $lessonid, $userid );
        $logout_time = $this->get_login_end_time( $lessonid, $userid );

        if ($logout_time <$login_time) {
            $logout_time = $login_time +4*86400;
        }

        if ($login_time <$check_time &&  $check_time<$logout_time ) {
            $ret_flag = true;
        } else {
            $ret_flag = false;
        }

        return array($login_time ,$ret_flag);
    }

    public function get_login_start_time ( $lessonid,$userid=-1 ) {

        $where_arr=[
            ["lessonid=%u",$lessonid, -1],
            ["userid=%u",$userid, -1],
            "server_type=2",
            "opt_type=1",
        ];

        $sql=$this->gen_sql_new("select  min( opt_time) "
                                ." from %s"
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_last_logout_time ( $lessonid,$userid=-1,$time ) {

        $where_arr=[
            ["lessonid=%u",$lessonid, -1],
            ["userid=%u",$userid, -1],
            "server_type=2",
            "opt_type=2",
            "opt_time<=".$time
        ];

        $sql=$this->gen_sql_new("select  max( opt_time) "
                                ." from %s"
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_min_login_time ( $lessonid,$userid=-1,$time ) {

        $where_arr=[
            ["lessonid=%u",$lessonid, -1],
            ["userid=%u",$userid, -1],
            "server_type=2",
            "opt_type=1",
            "opt_time>".$time
        ];

        $sql=$this->gen_sql_new("select  min( opt_time) "
                                ." from %s"
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }


    public function get_login_end_time(  $lessonid,$userid=-1 ){
        $where_arr=[
            ["lessonid=%u",$lessonid, -1],
            ["userid=%u",$userid, -1],
            "server_type=2",
            "opt_type=2",
        ];

        $sql=$this->gen_sql_new("select  max(opt_time) "
                                ." from %s"
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_login_list ( $lessonid,$userid=-1 ) {

        $where_arr=[
            ["lessonid=%u",$lessonid, -1],
            ["userid=%u",$userid, -1],
            "server_type=2",
        ];

        $sql=$this->gen_sql_new("select opt_time,  userid,  opt_type "
                                ." from %s"
                                ." where %s order by opt_time "
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_opt_time_new ( $lessonid,$userid=-1,$opt_type=1 ) {

        $where_arr=[
            ["lessonid=%u",$lessonid, -1],
            ["userid=%u",$userid, -1],
            ["opt_type=%u",$opt_type, -1],
            "server_type=2",
        ];

        if($opt_type==1){

            $sql=$this->gen_sql_new("select min(opt_time) "
                                    ." from %s"
                                    ." where %s "
                                    ,self::DB_TABLE_NAME
                                    ,$where_arr
            );

        }else{
            $sql=$this->gen_sql_new("select max(opt_time) "
                                    ." from %s"
                                    ." where %s "
                                    ,self::DB_TABLE_NAME
                                    ,$where_arr
            );

        }

        return $this->main_get_value($sql);
    }

    public function get_course_name($courseid){
        $sql = sprintf("select course_name from %s where courseid = %u",
                       self::DB_TABLE_NAME,
                       $courseid
        );
        return $this->main_get_value($sql);
    }
    //public function  get

    public function get_login_user($lessonid){
        $where_arr=[
            ["lessonid=%u",$lessonid,0],
        ];
        $sql=$this->gen_sql_new("select count(distinct(userid))"
                                ." from %s"
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_login_user_list($lessonid){
        $where_arr = [
            ["lessonid=%u",$lessonid,0]
        ];

        $sql = $this->gen_sql_new("select distinct(userid) from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_lesson_log_by_pool($lessonid,$userid,$server_type,$teacherid,$stu_id,$lesson_start,$lesson_end )
    {

        if ( $userid != -1) {
            $where_arr=[
                ['userid=%d',$userid]
            ];
        }

        if ( $server_type != -1 ){
            $where_arr=[
                ['server_type=%d',$server_type]
            ];
        }

        $where_arr=[
            "lessonid=$lessonid or (lessonid=0 and opt_time+1800>=$lesson_start and opt_time-1800<=$lesson_end and userid in ($teacherid,$stu_id))"
        ];

        $sql = $this->gen_sql_new("select lessonid, opt_time ,userid, opt_type, server_type , server_ip,program_id "
                       ." from %s "
                       ." where %s "
                       ." order by opt_time asc ",
                       self::DB_TABLE_NAME ,
                       $where_arr
        );

        return $this->main_get_list($sql);
    }
    //查询老师/学生最后一次登录/退出的信息
    public function get_lesson_log_user_last($lessonid,$userid )
    {

        $sql = $this->gen_sql_new("  select   max(opt_time) "
                                  ." from %s "
                                  ." where lessonid= %u and  userid =%u ",
                                  self::DB_TABLE_NAME ,
                                  $lessonid, $userid);
        $max_opt_time=$this->main_get_value($sql);
        $sql=$this->gen_sql_new("select  opt_time, server_ip, server_type from %s where  lessonid= %u and  userid =%u  and opt_time =%u ", self::DB_TABLE_NAME, $lessonid,  $userid,  $max_opt_time );
        $ret= $this->main_get_row($sql);
        if ($ret) {
            $ret["server_ip"]=long2ip($ret["server_ip"]);
        }
        return $ret;
    }



    public function get_test_lesson_for_login($lessonid,$userid, $lesson_start, $lesson_end){ // 课程开始五分钟
        $where_arr=[
            "lo.opt_type=1", // 登录记录
            " userid = $userid"
        ];

        $where_arr[] = "lessonid=$lessonid  or (lessonid=0 and opt_time+1800>=$lesson_start and opt_time-1800<=$lesson_end )";

        $sql = $this->gen_sql_new(" select min(opt_time) from %s lo "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }


    public function get_test_lesson_for_logout($lessonid,$userid, $lesson_start, $lesson_end){ // 课程开始五分钟
        $where_arr=[
            "lo.opt_type=2", // 登出记录
            " userid = $userid"
        ];

        $where_arr[] = "lessonid=$lessonid  or (lessonid=0 and opt_time+1800>=$lesson_start and opt_time-1800<=$lesson_end )";

        $sql = $this->gen_sql_new(" select min(opt_time) from %s lo "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }


    public function get_logout_time($lessonid,$userid){
        $sql = $this->gen_sql_new(" select max(opt_time) from %s tl where tl.lessonid=%d and opt_type=2 and userid=%d "
                                  ,self::DB_TABLE_NAME,
                                  $lessonid,
                                  $userid
        );

        return $this->main_get_value($sql);
    }

    public function get_login_time($lessonid,$userid){
        $sql = $this->gen_sql_new(" select max(opt_time) from %s tl where tl.lessonid=%d and opt_type=1 and userid=%d "
                                  ,self::DB_TABLE_NAME,
                                  $lessonid,
                                  $userid
        );

        return $this->main_get_value($sql);
    }


    public function get_time_by_lesson($lessonid,$userid,$opt_type){
        $where_arr=[
            ["lessonid=%u",$lessonid, -1],
            ["userid=%u",$userid, -1],
            ["opt_type=%u",$opt_type, -1],
            "server_type=2",
        ];
        $sql = $this->gen_sql_new("select opt_time from %s where %s order by opt_time",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);

    }


    public function get_common_lesson_for_login($lessonid,$userid){ // 课程开始五分钟
        $where_arr=[
            "lo.opt_type=1", // 登录记录
            "userid = $userid",
            "lessonid = $lessonid"
        ];

        $sql = $this->gen_sql_new(" select min(opt_time) from %s lo "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }


    public function get_stu_log($lessonid,$userid){
        $sql = $this->gen_sql_new("  select opt_time, opt_type from %s tl "
                                  ." where l.lessonid=$lessonid and userid=$userid  "
        );

        return $this->main_get_list($sql);
    }



}