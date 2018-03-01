<?php
namespace App\Models;
use \App\Enums as E;
class t_student_score_info extends \App\Models\Zgen\z_t_student_score_info
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_list($page_info,$user_id){
          $sql = $this->gen_sql("select * from %s where userid = %d and status = 0 order by create_time desc ",
                              self::DB_TABLE_NAME,
                              $user_id);
        return $this->main_get_list_by_page($sql,$page_info);
    }
    public function get_all_list($page_info,$username,$grade,$semester,$stu_score_type,$is_test_user,$userid=-1,$subject=-1  ){
        $where_arr = [
            // [" u.realname= '%s'",$username,''],
            [" u.nick= '%s'",$username,''],
            [" s.grade = %d ",$grade,-1],
            [" s.subject = %d ",$subject,-1],
            [" s.semester = %d ",$semester,-1],
            [" s.stu_score_type = %d ",$stu_score_type,-1],
            [" u.is_test_user = %d ",$is_test_user,-1],
            [" s.userid = %d ",$userid,-1],
            "s.status = 0",
        ];
        $sql = $this->gen_sql_new(" select s.admin_type, s.userid,s.create_time,s.create_adminid,s.subject,"
                                  ."s.stu_score_type,s.stu_score_time,s.score,s.total_score,s.rank,s.semester,"
                                  ."s.total_score,s.grade,s.grade_rank,s.status,s.month,s.rank_up,s.rank_down, "
                                  ."u.realname,u.school,m.name,u.nick,s.file_url,s.paper_upload_time,s.school_ex "
                                  ." from      %s s "
                                  ." left join %s u on s.userid         = u.userid "
                                  ." left join %s m on s.create_adminid = m.uid "
                                  ." where %s order by s.create_time desc ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME, //学生表userid
                                  t_manager_info::DB_TABLE_NAME, //管理员表uid
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_info);
    }
    public function get_all_list_no_page($username,$grade,$semester,$stu_score_type,$is_test_user,$userid=-1 ,$subject=-1  ){
        $where_arr = [
            // [" u.realname= '%s'",$username,''],
            [" u.nick= '%s'",$username,''],
            [" s.grade = %d ",$grade,-1],
            [" s.subject = %d ",$subject,-1],
            [" s.semester = %d ",$semester,-1],
            [" s.stu_score_type = %d ",$stu_score_type,-1],
            [" u.is_test_user = %d ",$is_test_user,-1],
            [" s.userid = %d ",$userid,-1],
            "s.status = 0",
        ];
        $sql = $this->gen_sql_new(" select s.admin_type, s.userid,s.create_time,s.create_adminid,s.subject,"
                                  ."s.stu_score_type,s.stu_score_time,s.score,s.total_score,s.rank,s.semester,"
                                  ."s.total_score,s.grade,s.grade_rank,s.status,s.month,s.rank_up,s.rank_down, "
                                  ."u.realname,u.school,m.name,u.nick,s.file_url,s.paper_upload_time "
                                  ." from      %s s "
                                  ." left join %s u on s.userid         = u.userid "
                                  ." left join %s m on s.create_adminid = m.uid "
                                  ." where %s order by s.create_time  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME, //学生表userid
                                  t_manager_info::DB_TABLE_NAME, //管理员表uid
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_last_grade_rank($subject,$userid){
        $sql = $this->gen_sql(" select grade_rank "
                              ." from %s "
                              ."  where subject = $subject and userid = $userid order by create_time desc limit 1",
                              self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
    public function get_last_grade_rank_b1($subject,$userid,$create_time){
        $sql = $this->gen_sql(" select grade_rank "
                              ." from %s "
                              ."  where subject = $subject and userid = $userid and create_time < $create_time order by create_time desc limit 1",
                              self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
    public function get_last_grade_rank_b2($subject,$userid,$create_time){
        $sql = $this->gen_sql(" select * "
                              ." from %s "
                              ."  where subject = $subject and userid = $userid and create_time > $create_time order by create_time asc limit 1",
                              self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function set_every_month_student_score($time)
    {
        $where_str=$this->where_str_gen([
            ["t1.competition_flag=%u",0, -1 ] ,
            "t1.course_type=0"  ,
        ]);
        $sql=$this->gen_sql("select t1.userid,t1.courseid,t1.grade,t1.subject,t1.lesson_grade_type,"
                            ."sum(t2.lesson_count) as lesson_count, "
                            ."sum(case when lesson_status=0 then lesson_count else 0 end )  as no_finish_lesson_count , "
                            ."sum(case when lesson_status=0 then 0 else "
                            ."(case when confirm_flag in (2,4) then 0 else t2.lesson_count end) "
                            ."end ) as finish_lesson_count,t1.add_time, "
                            ." t1.assistantid,t1.course_type, "
                            ."course_status "
                            ." from %s t1 "
                            ." left join %s t2 on t1.courseid = t2.courseid "
                            ." where %s "
                            ." and (lesson_del_flag=0 or lesson_del_flag is null)"
                            ." and t1.assistantid != 0"
                            ." group by t1.courseid having no_finish_lesson_count > 0 order by t1.userid desc, t1.subject desc "
                            ,t_course_order::DB_TABLE_NAME
                            ,t_lesson_info::DB_TABLE_NAME
                            ,[$where_str]
        );
        return $this->main_get_list($sql);
    }

    public function get_score_info_for_parent($parentid,$userid){
        $where_arr = [
            ["create_adminid = %d",$parentid,-1],
            ["userid = %d ",$userid,-1],
            'status=0',

        ];

        $sql = $this->gen_sql_new(" select subject, stu_score_type, score, grade_rank from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_stu_score_list_for_score_type($userid){
        $where_arr = [
            ['tc.userid=%d',$userid,-1],
            "tc.status=0"
        ];

        $sql = $this->gen_sql_new("  select id as scoreid, stu_score_type, grade,score, total_score, semester, subject, grade_rank, rank, file_url from %s tc"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_score_info($id){
        $sql = $this->gen_sql_new(" select grade, total_score, stu_score_type, score, grade_rank, rank from %s where id = %d "
                                  ,self::DB_TABLE_NAME
                                  ,$id
        );

        return $this->main_get_list($sql);
    }
    public function get_is_status($userid,$create_time){
        $where_arr = [
            ['create_time>=%u',$create_time,-1],
            ['userid=%u',$userid,-1],
            'status=0',
        ];

        $sql = $this->gen_sql_new("  select count(id) as num from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_all_info(){
        $sql = $this->gen_sql_new("  select score from %s s"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_input_score_list($start_time, $end_time, $admin_type, $page_num){
        $where_arr = [
            ['admin_type=%d',$admin_type,-1],
            "sc.status = 0",
            "sc.userid>0",
            "s.is_test_user=0"
        ];

        $this->where_arr_add_time_range($where_arr,"create_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select sc.id, s.nick, s.userid, sc.subject, sc.semester, sc.stu_score_type, sc.score, sc.grade_rank, sc.rank, sc.file_url, create_time, create_adminid, admin_type  from %s sc  "
                                  ." left join %s s on s.userid=sc.userid "
                                  ." where %s group by sc.userid  order by sc.create_time desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true);
    }

    









    //拉数据专用,勿动
    public function get_all_student_info(){
        $sql = "select * from db_weiyi.t_student_info";
        return $this->main_get_list($sql);
    }
    public function get_total_student_b1(){
        $sql = "select count(*) from t_student_info where is_test_user = 0";
        return $this->main_get_value($sql);

    }
    public function get_total_student_b2(){
        $sql = "select count(*) from t_student_info where is_test_user = 0 and grade in(101,102,103)";
        return $this->main_get_value($sql);
    }
    public function get_total_student_b3(){
        $sql = "select count(distinct(l.userid)) from t_lesson_info l left join t_student_info s on s.userid = l.userid where lesson_type = 2  and lesson_start < 1515168000 and s.is_test_user = 0";
        return $this->main_get_value($sql);
    }
    public function get_total_student_b4(){
        $sql = "select count(distinct(l.userid)) from t_lesson_info l left join t_student_info s on s.userid = l.userid where lesson_type = 2  and lesson_start < 1515168000 and s.is_test_user = 0 and l.grade in(101,102,103)";
        return $this->main_get_value($sql);
    }
    public function get_total_student_b5(){
        $sql = "select count(distinct(o.userid))
from db_weiyi.t_order_info o  left join db_weiyi.t_student_info s on o.userid = s.userid  left join db_weiyi.t_course_order c on o.orderid = c.orderid  left join db_weiyi.t_seller_student_new n on o.userid = n.userid  left join db_weiyi.t_lesson_info l on l.lessonid = o.from_test_lesson_id  left join db_weiyi_admin.t_flow f on ( f.from_key_int = o.orderid  and f.flow_type in ( 2002, 3002)) left join db_weiyi_admin.t_manager_info m on s.ass_master_adminid = m.uid left join db_weiyi_admin.t_manager_info m2 on o.sys_operator = m2.account left join db_weiyi.t_student_init_info ti on o.userid = ti.userid left join db_weiyi.t_child_order_info co on (co.parent_orderid = o.orderid and co.child_order_type = 2) 
where is_test_user=0  and contract_status in  (1,2,3) and o.price>0 ";
        return $this->main_get_value($sql);
    }
    public function get_total_student_b6(){
        $sql = "select count(distinct(o.userid))
from db_weiyi.t_order_info o  left join db_weiyi.t_student_info s on o.userid = s.userid  left join db_weiyi.t_course_order c on o.orderid = c.orderid  left join db_weiyi.t_seller_student_new n on o.userid = n.userid  left join db_weiyi.t_lesson_info l on l.lessonid = o.from_test_lesson_id  left join db_weiyi_admin.t_flow f on ( f.from_key_int = o.orderid  and f.flow_type in ( 2002, 3002)) left join db_weiyi_admin.t_manager_info m on s.ass_master_adminid = m.uid left join db_weiyi_admin.t_manager_info m2 on o.sys_operator = m2.account left join db_weiyi.t_student_init_info ti on o.userid = ti.userid left join db_weiyi.t_child_order_info co on (co.parent_orderid = o.orderid and co.child_order_type = 2) 
where is_test_user=0  and contract_status in  (1,2,3) and o.price>0   and s.grade in(101,102,103)";
        return $this->main_get_value($sql);
    }

    public function get_info_1(){
        $sql = "select origin_assistantid, s.userid, s.origin_userid,o.price , o.orderid from db_weiyi.t_student_info s  left join db_weiyi.t_order_info o on (o.userid = s.userid and o.contract_status>0 and  o.contract_type =0 )   left join db_weiyi.t_seller_student_new n on s.userid = n.userid where origin_assistantid>0 and n.add_time>=1510675200 and n.add_time<1514736000   ";
        return $this->main_get_list($sql);
    }

    public function get_info_by_month($start_time,$end_time){
        $where_arr = [
          ["t.lesson_start > %s",$start_time,-1],
          ["t.lesson_start < %s",$end_time,-1],
          "t.lesson_type=2"
        ];

        $sql = $this->gen_sql_new("SELECT s.phone_location, t.grade, t.subject,count(*) as num"
                                ." from %s t"
                                ." left join %s s on  s.userid = t.userid"
                                ." where %s group by t.subject, t.grade,s.phone_location"
                                ,t_lesson_info::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_grade_by_info_b(){
      $sql = "select grade, phone_location , count(*) as num from db_weiyi.t_student_info where grade in (101,102,103) and is_test_user = 0 group by grade,phone_location";
      return $this->main_get_list($sql);
    }

    public function get_info_by_month_b2($start_time,$end_time){
        $where_arr = [
          ["t.lesson_start > %s",$start_time,-1],
          ["t.lesson_start < %s",$end_time,-1],
          "t.lesson_type=2"
        ];

        $sql = $this->gen_sql_new("SELECT  m.teacherid ,m.phone_location,m.grade_start, m.grade_end,m.subject,m.second_subject"
                                ." from %s t"
                                ." left join %s m on  m.teacherid = t.teacherid "
                                ." where %s  group by m.teacherid"
                                ,t_lesson_info::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr);
        return $this->main_get_list($sql);
    }


    public function get_info_by_month_b3($start_time,$end_time){

        $sql = "select s.userid, s.nick, s.phone_location , t.subject, t.grade , k.teacherid,k.nick as teacher_name, k.grade_start, k.grade_end, k.phone_location as teacher_phone_location,n.require_admin_type  from t_lesson_info t
left join t_student_info s on s.userid = t.userid
left join t_teacher_info k on k.teacherid = t.teacherid
left join t_order_info o on s.userid = o.userid 
left join t_test_lesson_subject_sub_list m on m.lessonid = t.lessonid
left join t_test_lesson_subject_require mm on mm.require_id  = m.require_id 
left join t_test_lesson_subject n on n.test_lesson_subject_id  = mm.test_lesson_subject_id 
where lesson_start > $start_time and lesson_start < $end_time and lesson_type = 2 and contract_type  in (0,3) and price > 0 and contract_status in (1,2,3) and order_time > $start_time";
        return $this->main_get_list($sql);
    }

    public function get_all_student_phone_and_id(){
        $sql = "select userid, phone from db_weiyi.t_student_info where is_test_user = 0 order by userid asc";
        return $this->main_get_list($sql);
    }

    public function get_all_teacher_phone_and_id(){
        $sql = "select teacherid, phone from db_weiyi.t_teacher_info where is_test_user = 0 order by teacherid asc";
        return $this->main_get_list($sql);
    }


    public function get_province_info($phone){
        $sql = "select province ,city from db_weiyi.t_phone_info where id = $phone ";
        return $this->main_get_row($sql);
    }

    public function get_grade_by_info_1(){
      $sql = "select grade, phone_province,phone_city, count(*) as num from db_weiyi.t_student_info where grade in (101,102,103) and is_test_user = 0 group by grade,phone_city";
      return $this->main_get_list($sql);
    }

    public function get_b1(){
       $sql = "select s.grade,t.seller_student_status , count(*) as num from db_weiyi.t_student_info s left join db_weiyi.t_test_lesson_subject t on s.userid = t.userid where s.is_test_user = 0  and s.grade in (101,102,103)  group by s.grade, t.seller_student_status";
       return $this->main_get_list($sql);
    }

    public function get_b2(){
       $sql = "select s.grade,t.seller_student_sub_status  , count(*) as num from db_weiyi.t_student_info s left join db_weiyi.t_test_lesson_subject t on s.userid = t.userid where s.is_test_user = 0  and s.grade in (101,102,103)  group by s.grade, t.seller_student_sub_status ";
       return $this->main_get_list($sql);
    }

    public function get_b3(){
       $sql = "select s.grade ,k.test_lesson_order_fail_flag , count(*) as num
from db_weiyi.t_student_info s 
left join db_weiyi.t_test_lesson_subject t on s.userid = t.userid
left join db_weiyi.t_test_lesson_subject_require k on k.test_lesson_subject_id  = t.test_lesson_subject_id 
where s.is_test_user = 0  and s.grade in (101,102,103) group by s.grade ,k.test_lesson_order_fail_flag ";
       return $this->main_get_list($sql);
    }


    public function get_b4(){
       /*$sql = "select userid, max(start_time)
from db_weiyi.t_student_info s
left join  db_weiyi_admin.t_tq_call_info q on q.phone = s.phone
where is_called_phone = 1 and s.is_test_user = 0 group by s.phone ";*/
        $sql = "select s.grade, max_time 
from (select phone,max(start_time) as max_time from db_weiyi_admin.t_tq_call_info where  is_called_phone = 1  GROUP BY phone ) k
 left join db_weiyi.t_student_info s on s.phone = k.phone
where s.is_test_user = 0 and s.grade in (101,102,103)";
       return $this->main_get_list($sql);
    }



    public function get_all_infoxxx (){
      $sql = "select s.nick, s.phone_province, s.phone_city from db_weiyi.t_lesson_info l left join db_weiyi.t_teacher_info t on l.teacherid = t.teacherid left join db_weiyi.t_student_info s on s.userid = l.userid where lesson_start > 1512057600 and lesson_type in (0,1,3 ) and t.is_test_user = 0 group by l.userid";
       return $this->main_get_list($sql);
    }

    public function get_all_infoxxx2 (){
      $sql = "select t.nick, t.phone_province, t.phone_city from db_weiyi.t_lesson_info l left join db_weiyi.t_teacher_info t on l.teacherid = t.teacherid left join db_weiyi.t_student_info s on s.userid = l.userid where lesson_start > 1512057600 and lesson_type in (0,1,3 ) and t.is_test_user = 0 group by l.userid";
       return $this->main_get_list($sql);
    }

    public function get_xx($start_time,$end_time){
       $sql = "select count(*) as total , sum( if(l.grade = 101, 1, 0)) as one_total, sum( if(l.grade = 102, 1, 0)) as two_total, sum( if(l.grade = 103, 1, 0)) as three_total  from t_lesson_info   l
left join t_student_info s on s.userid = l.userid
where lesson_start > $start_time and lesson_start < $end_time and lesson_type = 2 and s.is_test_user = 0 and lesson_user_online_status = 1 ";

      return $this->main_get_row($sql);
    }

    public function get_yy($start_time,$end_time){
       $sql = "select count(*) as total , sum( if(grade = 101, 1, 0)) as one_total, sum( if(grade = 102, 1, 0)) as two_total, sum( if(grade = 103, 1, 0)) as three_total  
from t_student_info 
where reg_time >  $start_time and reg_time < $end_time and is_test_user = 0";

      return $this->main_get_row($sql);
    }
    public function get_zz($start_time,$end_time){
       $sql = "select count(*) as total , sum( if(o.grade = 101, 1, 0)) as one_total, sum( if(o.grade = 102, 1, 0)) as two_total, sum( if(o.grade = 103, 1, 0)) as three_total  
from t_order_info o 
left join t_student_info s on s.userid = o.userid
where order_time >  $start_time and order_time < $end_time and contract_type  = 0 and s.is_test_user = 0 and contract_status in (1,2,3) and price > 0";
      return $this->main_get_row($sql);
    }

    public function get_abcd($start_time,$end_time){
       $sql = "select s.phone_province, s.phone_city, count(*) as total
from t_student_info s where reg_time > $start_time and reg_time < $end_time and s.is_test_user = 0 and s.phone_province in ('浙江','广东','江苏') group by s.phone_province, s.phone_city";
      return $this->main_get_list($sql);
    }

    public function get_ae($start_time,$end_time){
       $sql = "select s.phone_province, s.phone_city, count(distinct(s.userid)) as total
from t_student_info s left join t_order_info o on o.userid = s.userid where reg_time > $start_time and reg_time < $end_time and s.is_test_user = 0  and s.phone_province in ('浙江','广东','江苏') and o.price>0 and  o.contract_status in(1,2,3) group by s.phone_province, s.phone_city";
      return $this->main_get_list($sql);
    }

    public function reflash_info(){
      $sql = "select teacherid, achievement , teaching_achievement from db_weiyi.t_teacher_info where achievement != '' and teaching_achievement = '' ";
      return $this->main_get_list($sql);
    }

    public function get_num(){
//       $sql = "select l.uid, l.adminid, s.userid, max(l.create_time) as create_time, m.account_role, mm.account_role as uid_account_role
// from db_weiyi_admin.t_seller_edit_log l  
// left join db_weiyi.t_seller_student_new ss on ss.userid=l.new  
// left join db_weiyi.t_student_info s on s.userid=ss.userid  
// left join db_weiyi_admin.t_manager_info m on l.adminid = m.uid
// left join db_weiyi_admin.t_manager_info mm on l.uid = mm.uid
// where l.uid <> l.adminid and l.type = 3 and s.is_test_user=0 
// and l.create_time > 1508515200 and l.create_time < 1518019200 group by s.phone";

      $sql = "select f.file_id, f.resource_id ,f.reload_adminid , f.kpi_adminid, r.adminid  from t_resource_file f 
left join t_resource r on r.resource_id = f.resource_id";
      return $this->main_get_list($sql);
    }


    public function get_num_t2(){
       $sql = "select l.userid, l.teacherid ,l.subject, max(lesson_start) as time  from t_lesson_info  l
where lesson_start < 1519833600
group by l.userid, l.subject
order by lesson_start desc";

       return $this->main_get_list($sql);
    }


    public function get_num_t3(){
      $sql = "select userid from t_student_info where lesson_count_left > 0 and is_test_user   = 0";
      return $this->main_get_list($sql);
    }

}