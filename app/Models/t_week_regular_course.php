<?php
namespace App\Models;
use \App\Enums as E;
class t_week_regular_course extends \App\Models\Zgen\z_t_week_regular_course
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_lesson_info($teacherid,$userid){
        if($teacherid ==-1 && $userid == -1){
            $where_arr = "teacherid =".$teacherid;
        }else{
            $where_arr=[
                ['teacherid = %s',$teacherid,-1],
                ['userid = %s',$userid,-1]
            ];
        }

        $sql = $this->gen_sql_new("select * from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["start_time"];
        });
    }

    public function get_lesson_info_new($userid){
        if($userid == "()"){
            $where_arr[]= "userid = 1";
        }else{
            $where_arr[] = "userid in".$userid;
        }

        $sql = $this->gen_sql_new("select * from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function check_userid($teacherid)
    {
        $sql = $this->gen_sql("select 1 from %s where teacherid = %u ",
                              self::DB_TABLE_NAME,
                              $teacherid
        );

        return $this->main_get_value($sql);
    }

    public function get_info_stu()
    {
        $sql = $this->gen_sql("select * from %s ",
                              self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    /**
     * 检查是否冲突
     * 判断条件
     * http://bbs.csdn.net/topics/360003491
     * 假设已有 t1<=t2, t3<=t4
     * 不重叠： t2<=t3 || t4<=t1
     * 重叠取反即可：t3<t2 && t4>t1
     */
    public function check_is_clash( $userid, $teacherid,  $start_time,$end_time,$extend_start_time ) {
        $day        = substr($start_time,0,1)*86400;
        $start_time = $day+substr($start_time,2,2  )*3600+ substr($start_time,5,2  )*60;
        $end_time   = $day+substr($end_time,0,2  )*3600+ substr($end_time,3,2  )*60;

        $where_arr1=[
            ["teacherid=%u",$teacherid,-1] ,
            ["start_time <>'%s'", $extend_start_time,""] ,
        ];

       $sql = $this->gen_sql_new(
           "select substring(start_time,1,1)*86400+substring(start_time,3,2)*3600+ substring(start_time,6,2)*60 as start_time_s,"
           ." substring(start_time,1,1)*86400+substring(end_time,1,2)*3600+substring(end_time,4,2)*60 as end_time_s "
           ." from %s "
           ." where %s "
           ." having (%u<end_time_s and %d>start_time_s) "
           ,self::DB_TABLE_NAME
           ,$where_arr1
           ,$start_time
           ,$end_time
       );

        $ret =[];
        $ret["tea"] =  $this->main_get_row($sql);
        $where_arr2=[
            ["userid=%u",$userid,-1] ,
            ["start_time <>'%s'", $extend_start_time,""] ,
        ];

        $sql=$this->gen_sql_new(
            "select  substring(start_time,1,1)*86400+ substring(start_time,3,2)*3600 + substring(start_time,6,2)*60 as start_time_s , substring(start_time,1,1)*86400+ substring(end_time,1,2)*3600 + substring(end_time,4,2)*60 as end_time_s   from %s where %s ".
            " having (  %u < end_time_s and %d > start_time_s)  ",
            self::DB_TABLE_NAME, $where_arr2,  $start_time, $end_time) ;
        $ret['stu']=  $this->main_get_row($sql);
        return $ret;
    }

    public function add_regular_course($teacherid,$userid,$start_time,$end_time,$lesson_count,$competition_flag)
    {
        return $this->row_insert([
            "teacherid" => $teacherid,
            "userid" => $userid,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "lesson_count" => $lesson_count,
            "competition_flag" => $competition_flag
        ]);

    }
    public function check_is_clash_stu( $userid, $start_time,$end_time,$teacherid) {
        $date=\App\Helper\Utils::get_week_range($start_time,1);
        $stime=$date["sdate"];
        $start = date('Y-m-d H:i',$start_time);
        $end = date('Y-m-d H:i',$end_time);
        $day = strtotime(date('Y-m-d',(int)$start_time))-strtotime(date('Y-m-d',(int)$stime))+86400;
        $start_time=$day+substr($start,11,2  )*3600+ substr($start,14,2  )*60;
        $end_time=$day+substr($end,11,2  )*3600+ substr($end,14,2  )*60;

        $where_arr=[
            ["userid=%u",$userid,-1] ,
        ];
        $res = [];
        $sql=$this->gen_sql_new(
            "select  substring(start_time,1,1)*86400+ substring(start_time,3,2)*3600 + substring(start_time,6,2)*60 as start_time_s , substring(start_time,1,1)*86400+ substring(end_time,1,2)*3600 + substring(end_time,4,2)*60 as end_time_s   from %s where %s ".
            "and teacherid = %u having (  %u = end_time_s and %d = start_time_s) ",
            self::DB_TABLE_NAME, $where_arr, $teacherid, $end_time, $start_time) ;
        $res['all'] = $this->main_get_list($sql);
        $sql=$this->gen_sql_new(
            "select  teacherid,substring(start_time,1,1)*86400+ substring(start_time,3,2)*3600 + substring(start_time,6,2)*60 as start_time_s , substring(start_time,1,1)*86400+ substring(end_time,1,2)*3600 + substring(end_time,4,2)*60 as end_time_s   from %s where %s ".
            " having (  %u < end_time_s and %d > start_time_s and (start_time_s <> %u or end_time_s <> %u or teacherid <> %u))  ",
            self::DB_TABLE_NAME, $where_arr,  $start_time, $end_time,$start_time,$end_time,$teacherid) ;
        #return $sql;
        $res['clash'] =  $this->main_get_row($sql);
        return $res;
    }

    public function check_is_clash_stu_new( $userid, $lessonid,$start_time) {
        if($userid == "()"){
            $where_arr[]= "w.userid = 1";
        }else{
            $where_arr[] = "w.userid in".$userid;
        }
        if($lessonid == "()"){
            $where_arr_lesson[]= "l.lessonid = 1";
        }else{
            $where_arr_lesson[] = "l.lessonid in".$lessonid;
        }

        $res = [];
        $sql=$this->gen_sql_new(
            "select  w.userid,l.lesson_start,l.lesson_count,substring(start_time,1,1)*86400+ substring(start_time,3,2)*3600 + substring(start_time,6,2)*60 as start_time_s , substring(start_time,1,1)*86400+ substring(end_time,1,2)*3600 + substring(end_time,4,2)*60 as end_time_s".
            " from %s w left join %s l on w.userid = l.userid  where %s "
            . " and lesson_del_flag=0 and confirm_flag in (0,1)"

            ."and %s  having ( start_time_s = ( select unix_timestamp(substring(from_unixtime(`lesson_start`),1,10))- %s +86400 +substring(from_unixtime(`lesson_start`),12,2)*3600 + substring(from_unixtime(`lesson_start`),15,2)*60 from %s where lessonid= l.lessonid)  and  end_time_s in (select unix_timestamp(substring(from_unixtime(`lesson_end`),1,10))- %s +86400 +substring(from_unixtime(`lesson_end`),12,2)*3600 + substring(from_unixtime(`lesson_end`),15,2)*60 from %s where lessonid= l.lessonid)) ",
            self::DB_TABLE_NAME,t_lesson_info::DB_TABLE_NAME,
            $where_arr, $where_arr_lesson,$start_time, t_lesson_info::DB_TABLE_NAME,$start_time,t_lesson_info::DB_TABLE_NAME) ;
        $res['all'] = $this->main_get_list($sql);
        $sql=$this->gen_sql_new(
            "select  w.userid,w.teacherid,substring(start_time,1,1)*86400+ substring(start_time,3,2)*3600 + substring(start_time,6,2)*60 as start_time_s , substring(start_time,1,1)*86400+ substring(end_time,1,2)*3600 + substring(end_time,4,2)*60 as end_time_s  ".
            " from %s w left join %s l on w.userid = l.userid  where %s and  %s"
            . " and lesson_del_flag=0 and confirm_flag in (0,1)"
            ." having (  end_time_s >  (select unix_timestamp(substring(from_unixtime(`lesson_start`),1,10))- %s +86400 +substring(from_unixtime(`lesson_start`),12,2)*3600 + substring(from_unixtime(`lesson_start`),15,2)*60 from %s where lessonid= l.lessonid) and  start_time_s <  (select unix_timestamp(substring(from_unixtime(`lesson_end`),1,10))- %s +86400 +substring(from_unixtime(`lesson_end`),12,2)*3600 + substring(from_unixtime(`lesson_end`),15,2)*60 from %s where lessonid= l.lessonid) and (start_time_s <>  (select unix_timestamp(substring(from_unixtime(`lesson_start`),1,10))- %s +86400 +substring(from_unixtime(`lesson_start`),12,2)*3600 + substring(from_unixtime(`lesson_start`),15,2)*60 from %s where lessonid= l.lessonid) or end_time_s <> (select unix_timestamp(substring(from_unixtime(`lesson_end`),1,10))- %s +86400 +substring(from_unixtime(`lesson_end`),12,2)*3600 + substring(from_unixtime(`lesson_end`),15,2)*60 from %s where lessonid= l.lessonid) or teacherid <> (select teacherid from %s where lessonid = l.lessonid)))  ",
            self::DB_TABLE_NAME,t_lesson_info::DB_TABLE_NAME,
            $where_arr,$where_arr_lesson,$start_time,t_lesson_info::DB_TABLE_NAME,$start_time,t_lesson_info::DB_TABLE_NAME,$start_time,t_lesson_info::DB_TABLE_NAME,$start_time,t_lesson_info::DB_TABLE_NAME,t_lesson_info::DB_TABLE_NAME) ;
        #return $sql;
        $res['clash'] =  $this->main_get_list($sql);
        return $res;
    }

    public function get_stu_count_total($userid){

        $where_arr=[
            ["userid=%u",$userid,-1]
        ];

        $sql = $this->gen_sql_new("select sum(lesson_count) regular_total from %s where %s group by userid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql );
    }
    public function get_stu_count_total_new($userid){
        if($userid == "()"){
            $where_arr[]= "userid = 1";
        }else{
            $where_arr[] = "userid in".$userid;
        }
        $sql = $this->gen_sql_new("select userid,sum(lesson_count) regular_total from %s where %s group by userid",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['userid'];
        });
    }

    public function get_all_week_regular_course_info_all($assistantid,$userid,$teacherid,$adminid,$ass_flag=-1){
        $where_arr = [
            ["s.userid=%u",$userid,-1],
            ["t.teacherid=%u",$teacherid,-1],
            ["m.uid=%u",$adminid,-1],
            "s.type= 0",
            "s.is_test_user=0",
            "w.userid>0"
        ];
        if($adminid==-1 && $ass_flag==-1){
            $where_arr[] = "s.assistantid=".$assistantid;
        }else{
            $where_arr[] = ["s.assistantid = %u",$assistantid,-1];
        }
        $sql = $this->gen_sql_new("select w.userid,w.teacherid,w.start_time,w.end_time,w.lesson_count,w.lesson_count, "
                                  ." (substring(start_time,1,1)-1)*86400+substring(start_time,3,2)*3600+substring(start_time,6,2)*60 as start_time_s,"
                                  ." s.nick,t.realname,m.account,m.uid "
                                  ." from %s w "
                                  ." left join %s s on w.userid = s.userid "
                                  ." left join %s t on w.teacherid = t.teacherid"
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_week_regular_course_info($lstart,$assistantid,$userid,$teacherid,$adminid,$ass_flag=-1){
        $where_arr = [
            ["s.userid=%u",$userid,-1],
            ["w.teacherid=%u",$teacherid,-1],
            ["m.uid=%u",$adminid,-1],
            "s.type= 0",
            "s.is_test_user=0",
            "w.userid>0"
        ];
        if($adminid==-1 && $ass_flag==-1){
            $where_arr[]="s.assistantid=".$assistantid;
        }else{
            $where_arr[]=["s.assistantid = %u",$assistantid,-1];
        }

        $sql = $this->gen_sql_new("select w.userid,w.teacherid,w.start_time,w.end_time,w.lesson_count,(substring(start_time,1,1)-1)*86400+ substring(start_time,3,2)*3600 + substring(start_time,6,2)*60 as start_time_s,l.lessonid,l.lesson_start "
                                  ."from %s w left join %s s on w.userid =s.userid "
                                  ." left join %s l on (w.userid = l.userid and l.teacherid = w.teacherid  and l.lesson_type <>2 and l.lesson_count = w.lesson_count and l.lesson_del_flag=0 and l.confirm_flag in (0,1) )"
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." where %s having((lesson_start-start_time_s)= %u) ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $lstart
        );
        return $this->main_get_list($sql);

    }


    public function get_user_tea_num(){
        $where_arr=[
            "s.type= 0",
            "s.is_test_user=0",
            "w.userid>0"
        ];

        $sql = $this->gen_sql_new("select count(*) num,w.userid,s.nick,m.uid  "
                                  ."from %s w left join %s s on w.userid =s.userid "
                                  ." left join %s a on a.assistantid = s.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." where %s group by w.userid ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_week_regular_course_info($teacherid,$week,$old_start_time){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            ["start_time <> '%s' ",$old_start_time,""],
        ];

        $sql = $this->gen_sql_new("select substring(start_time,1,1) week,start_time,end_time "
                                  ." from %s where %s "
                                  ." having(week=%u) ",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  $week
        );
        return $this->main_get_list($sql );

    }

    public function get_tea_stu_num_list($qz_tea_arr){
        $where_arr=[
             "s.type <> 1"
        ]; 
        $this->where_arr_teacherid($where_arr,"teacherid", $qz_tea_arr );
        $sql = $this->gen_sql_new("select count(distinct w.userid) num,sum(lesson_count) lesson_all,teacherid "
                                  ." from %s w left join %s s on w.userid = s.userid"
                                  ." where %s group by teacherid",                                 
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }

    public function get_tea_stu_num_list_detail($qz_tea_arr){
        $where_arr=[
            "s.type <> 1"
        ]; 
        $this->where_arr_teacherid($where_arr,"teacherid", $qz_tea_arr );
        $sql = $this->gen_sql_new("select sum(lesson_count) lesson_all,teacherid "
                                  ." from %s w left join %s s on w.userid = s.userid"
                                  ." where %s group by teacherid,s.userid",                                 
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_tea_stu_num_list_new($teacherid){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            "s.type <> 1"
        ];
        $sql = $this->gen_sql_new("select count(distinct w.userid) num,sum(lesson_count) lesson_all,teacherid "
                                  ." from %s w left join %s s on w.userid = s.userid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }


    public function get_regular_count($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,0]
        ];
        $sql = $this->gen_sql_new("select count(1)"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function transfer_teacher_week_regular($old_teacherid,$new_teacherid){
        $where_arr = [
            ["teacherid=%u",$old_teacherid,0],
        ];
        $sql = $this->gen_sql_new("update %s set teacherid=%u"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$new_teacherid
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_tea_stu_grade_list($teacherid,$grade){
        $where_arr=[
            "teacherid=".$teacherid
        ];
        if($grade==1){
            $where_arr[] = "s.grade>=100 and s.grade<106";
        }elseif($grade==2){
            $where_arr[] = "s.grade>=106 and s.grade<300";
        }else{
            $where_arr[] = "s.grade>=300 and s.grade<400";
        }
        $sql = $this->gen_sql_new("select count(distinct w.userid) "
                                  ." from %s w left join %s s on s.userid=w.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_fulltime_teacher_week_info(){
        $where_arr=[
            "s.type <> 1",
            "m.account_role=5"
        ];
        $sql = $this->gen_sql_new("select distinct w.teacherid ,w.userid "
                                  ." from %s w left join %s s on w.userid= s.userid"
                                  ." left join %s t on w.teacherid = t.teacherid"
                                  ." left join %s m on t.phone= m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_student_time($teacherid,$userid){
        $where_arr =[
            ["teacherid=%u",$teacherid,-1],  
            ["userid=%u",$userid,-1],  
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_end_userid(){
        $sql = $this->gen_sql_new("select distinct w.userid from %s w left join %s s on w.userid= s.userid where s.type=1 and s.is_test_user=0",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_count_all($userid_list){
        $where_arr=[];
        $where_arr[]=$this->where_get_in_str("userid",$userid_list);
        $sql = $this->gen_sql_new("select sum(lesson_count) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_all_info() {
        $sql = $this->gen_sql_new("select teacherid,userid,start_time,end_time from %s",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_info_for_start_time($teacherid, $userid, $start_time) { // 用于拉取数据
        //select operate_time,lessonid,assistantid,lesson_start  from t_lesson_info
        $sql = $this->gen_sql_new("select operate_time,lessonid,assistantid,lesson_count,lesson_start from %s where teacherid=$teacherid and userid=$userid and lesson_start=$start_time", t_lesson_info::DB_TABLE_NAME);
        return $this->main_get_row($sql);
    }

}
