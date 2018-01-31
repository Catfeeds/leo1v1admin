<?php
namespace App\Models;
use \App\Enums as E;
class t_winter_week_regular_course extends \App\Models\Zgen\z_t_winter_week_regular_course
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_detail_info(){
        $sql = $this->gen_sql_new(" insert into %s select * from %s ",
                                  self::DB_TABLE_NAME,
                                  t_week_regular_course::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function check_is_clash( $userid, $teacherid,  $start_time,$end_time,$extend_start_time ) {
        $day=substr($start_time,0,1)*86400;
        //"1-19:30";
        $start_time=$day+substr($start_time,2,2  )*3600+ substr($start_time,5,2  )*60;
        $end_time=$day+substr($end_time,0,2  )*3600+ substr($end_time,3,2  )*60;

        $where_arr1=[
            #["userid=%u",$userid,-1] ,
            ["teacherid=%u",$teacherid,-1] ,
            ["start_time <>'%s'", $extend_start_time,""] ,
        ];

       $sql=$this->gen_sql_new(
           "select  substring(start_time,1,1)*86400+ substring(start_time,3,2)*3600 + substring(start_time,6,2)*60 as start_time_s , substring(start_time,1,1)*86400+ substring(end_time,1,2)*3600 + substring(end_time,4,2)*60 as end_time_s   from %s where %s ".
           " having (  %u < end_time_s and %d > start_time_s)  ",
           self::DB_TABLE_NAME, $where_arr1,  $start_time, $end_time) ;
        $ret =[];
        $ret["tea"] =  $this->main_get_row($sql);
        $where_arr2=[
            ["userid=%u",$userid,-1] ,
            #["teacherid=%u",$teacherid,-1] ,
            ["start_time <>'%s'", $extend_start_time,""] ,
        ];

        $sql=$this->gen_sql_new(
            "select  substring(start_time,1,1)*86400+ substring(start_time,3,2)*3600 + substring(start_time,6,2)*60 as start_time_s , substring(start_time,1,1)*86400+ substring(end_time,1,2)*3600 + substring(end_time,4,2)*60 as end_time_s   from %s where %s ".
            " having (  %u < end_time_s and %d > start_time_s)  ",
            self::DB_TABLE_NAME, $where_arr2,  $start_time, $end_time) ;
        $ret['stu']=  $this->main_get_row($sql);
        return $ret;
        
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

    public function delete_all_info(){
        $sql = $this->gen_sql_new(" delete from %s ",self::DB_TABLE_NAME );
        return $this->main_update($sql);

    }

    public function get_all_info() {
        $sql = $this->gen_sql_new("select teacherid,userid,start_time,end_time from %s",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

}











