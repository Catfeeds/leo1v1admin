<?php
namespace App\Models;
use App\Models\Zgen as Z;
use \App\Models as M;
use \App\Enums as E;

class t_course_order extends \App\Models\Zgen\z_t_course_order
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_course_info($courseid){
        $where_arr = [
            ["courseid=%u",$courseid,0]
        ];
        $sql = $this->gen_sql_new("select courseid,grade,subject,userid,teacherid,enable_video,reset_lesson_count_flag "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);

    }

    public function add_courser_order($userid,$course_name, $course_type, $from_type) {
        return $this->row_insert([
            "userid"      => $userid,
            "course_name" => $course_name,
            "course_type" => $course_type,
            "from_type"   => $from_type,
        ]);
    }

    public function get_lesson_count_all($courseid) {
        $arr=$this->field_get_list($courseid,"lesson_total,default_lesson_count");
        if ($arr) {
            $lesson_total = $arr["lesson_total"]*$arr["default_lesson_count"];
            return $lesson_total;
        }else{
            return 0;
        }
    }

    public function get_course_name($courseid)
    {
        $sql = sprintf("select course_name from %s where courseid = %u",
                       self::DB_TABLE_NAME,
                       $courseid
        );
        return $this->main_get_value($sql);
    }

    public function get_courseid_by_orderid($orderid) {
        $sql=$this->gen_sql("select courseid from %s where orderid=%u",
                            self::DB_TABLE_NAME,$orderid);
        return $this->main_get_value($sql);
    }

    public function get_courses_ex($teacherid,$assistantid,$course_type,$start_time,$end_time ,$courseid,$page_num  )
    {
        $lesson_open_str="true";
        if ( $courseid == -1 ) {
            $where_str=$this->where_str_gen([
                [ "tco.teacherid=%d", $teacherid , -1 ],
                [ "tco.assistantid=%d", $assistantid, -1 ],
                [ "course_type=%d", $course_type, -1 ],
            ]);
            // if ($assistantid==-1){
            //     $lesson_open_str= sprintf( "((lesson_open>=%d and lesson_open<=%d) or  lesson_open =0)",
            //                                $start_time ,
            //                                $end_time
            //     );
            // }
        }else{
            $where_str=$this->where_str_gen([
                [ "courseid=%d", $courseid, -1 ],
            ]);
        }

        $sql = $this->gen_sql_new(" select courseid, course_name, from_unixtime(lesson_open) as lesson_open, "
                                  ." tco.teacherid as teacherid, tti.nick as nick, tco.subject, tco.grade, "
                                  ." lesson_total,assistantid,lesson_left,stu_total,"
                                  ." if(tti.realname='',tti.nick,tti.realname) as teacher_nick"
                                  ." from %s as tco "
                                  ." left join %s as tti on tti.teacherid = tco.teacherid "
                                  ." where %s "
                                  ." and %s "
                                  ." and del_flag=0 "
                                  ." order by courseid desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_str
                                  ,$lesson_open_str
        );
        return $this->main_get_list_by_page($sql, $page_num,10);
    }

    public function inc_course_revisit_cnt($userid)
    {
        $sql = sprintf("select courseid from %s where userid = %u and course_status = 0 order by  courseid asc limit 1",
                       self::DB_TABLE_NAME,
                       $userid);
        $courseid = $this->main_get_value($sql);

        $sql = sprintf("update %s set revisit_cnt = revisit_cnt + 1 where courseid = %u",
                       self::DB_TABLE_NAME,
                       $courseid
        );
        return $this->main_update($sql);
    }

    public function update_course($courseid){
        $sql = sprintf(" update  %s set del_flag = 1 where courseid = %u ",
                       self::DB_TABLE_NAME,
                       $courseid
        );
        return $this->main_update($sql);
    }

    public function count_course($courseid)
    {
        $sql = sprintf("select count(courseid) from %s where courseid = %u",
                       self::DB_TABLE_NAME,
                       $courseid
        );
        return $this->main_get_value($sql);
    }

    public function set_user_assistantid( $userid,$assistantid) {
        $sql=$this->gen_sql(" update  %s  "
                            ." set assistantid=%u where userid= %u "
                            ,self::DB_TABLE_NAME
                            ,$assistantid
                            ,$userid);
        return $this->main_update($sql);
    }

    //open_class2 lala
    public function get_open_courses()
    {
        //处理方法
        $course_status_str= $this->where_get_in_str("course_status", [
                       E\Ecourse_status::V_0,
                       E\Ecourse_status::V_1,
                       E\Ecourse_status::V_2,
                       E\Ecourse_status::V_3,
                       E\Ecourse_status::V_4]);

        $course_type_str= $this->where_get_in_str("course_type", [
                       E\Econtract_type::V_1001,
                       E\Econtract_type::V_1002,
                       E\Econtract_type::V_4001,
                       E\Econtract_type::V_1003]);

        $sql =  sprintf("select courseid, course_name, course_type, nick from %s c , %s t where  " .
                        "c.teacherid = t.teacherid and %s and %s  ",
                        self::DB_TABLE_NAME,
                        \App\Models\t_teacher_info::DB_TABLE_NAME,
                        $course_status_str,
                        $course_type_str

        );
        return $this->main_get_list($sql);
    }

    public function get_open_course_list($courseid, $course_type,$search_str,$page_num)
    {
        $where_arr = array(
            array( "course_type=%u", $course_type, -1 ),
            array( "courseid=%d", $courseid, -1 ),
        );

        $course_type_str= $this->where_get_in_str("course_type", [
                       E\Econtract_type::V_1001,
                       E\Econtract_type::V_1002,
                       E\Econtract_type::V_4001,
                       E\Econtract_type::V_1003
        ]);

        if ($search_str!=""){
            $where_arr[]=sprintf( "( course_name like '%%%s%%' )",
                                    $this->ensql($search_str));
        }

        $sql = sprintf("select course_type, courseid, course_name, course_start, course_end "
                       ." from %s "
                       ." where %s and %s "
                       ." order by courseid desc"
                       ,self::DB_TABLE_NAME
                       ,$course_type_str
                       ,$this->where_str_gen($where_arr)
        );

        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function change_teacher($courseid, $new_teacherid)
    {
        $ret_course = $this->check_course_available($courseid);
        if (!$ret_course) {
            return $false;
        }
        $course_status_str= $this->where_get_in_str("course_status", [
                       E\Ecourse_status::V_0,
                       E\Ecourse_status::V_1,
                       E\Ecourse_status::V_2
        ]);

        $this->start_transaction();//老的里面的Models.php
        $sql = sprintf("update %s set teacherid = %u where courseid = %u and %s",
                       self::DB_TABLE_NAME,
                       $new_teacherid,
                       $courseid,
                       $course_status_str
        );
        $ret = $this->main_update($sql);
        if(!$ret){
            $this->rollback();
            return false;
        }

        $lesson_status_str= $this->where_get_in_str("lesson_status", [
                       E\Elesson_status::V_0,
                       E\Elesson_status::V_1,
                       E\Elesson_status::V_2,
                       E\Elesson_status::V_3]);

        $sql = sprintf("update %s set teacherid = %u where courseid = %u and lesson_status = %u",
                       \App\Models\t_lesson_info::DB_TABLE_NAME,
                       $new_teacherid,
                       $courseid,
                       $lesson_status_str
        );
        $ret = $this->main_update($sql);
        if(!$ret){
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    public function check_course_available($courseid)
    {
        $ret_get = $this->get_course_time($courseid);
        if ($ret_get === false) {
            return true;
        }

        $now_time = time();
        if ($ret_get['course_start'] < $now_time && $ret_get['course_end'] > $now_time) {
            return true;
        }

        return false;
    }

    private function get_course_time($courseid)
    {
        $sql = sprintf("select course_start, course_end from %s where courseid = %u and " .
            " lesson_left > 0 and course_status = 0",
            self::DB_TABLE_NAME,
            $courseid);
        return $this->main_get_row($sql);
    }

    function tongji_get_active_1v1_list(){
        $where_str=$this->where_str_gen([
            "course_status<>2",
            "t1.userid>0",
            "course_type in (0,1,3)",
        ]);

        $sql=$this->gen_sql("select t1.userid,t1.courseid, t1.grade,t1.subject, t1.lesson_total, sum(t2.lesson_count) as lesson_count ,   sum(case when lesson_status=0 then 0 else  (case when confirm_flag =2 then 0  else  t2.lesson_count end)  end ) as finish_lesson_count     ,  t1.teacherid, t1.assistantid,t1.course_type,t1.default_lesson_count from %s t1 left join %s t2 on  t1.courseid = t2.courseid   where %s "
                            ." and (lesson_del_flag =0  or lesson_del_flag is null)"
                            ." group by t1.courseid  having   lesson_count > finish_lesson_count  ",
                            self::DB_TABLE_NAME,
                            t_lesson_info::DB_TABLE_NAME,
                            [ $where_str]);

        return $this->main_get_list($sql,function($item ){
            return $item["userid"];
        });
    }

    function get_order_list($userid=-1,$courseid=-1 ){
        $where_str=$this->where_str_gen([
            ["t1.courseid=%u",$courseid, -1 ] ,
            ["t1.userid=%u",$userid, -1 ] ,
        ]);

        $sql=$this->gen_sql("select t1.userid,t1.courseid,t1.grade,t1.subject,t1.lesson_total, t1.teacherid,"
                            ."sum(t2.lesson_count) as lesson_count, "
                            ."sum(case when lesson_status=0 then 0 else "
                            ."(case when confirm_flag =2 then 0  else  t2.lesson_count end) "
                            ."end ) as finish_lesson_count, "
                            ."t1.teacherid, t1.assistantid,t1.course_type,t1.default_lesson_count,t1.assigned_lesson_count "
                            ." from %s t1 left join %s t2 on t1.courseid = t2.courseid "
                            ." where %s "
                            ." and (lesson_del_flag =0  or lesson_del_flag is null)"
                            ." group by t1.courseid order by t1.courseid desc ",
                            self::DB_TABLE_NAME,
                            t_lesson_info::DB_TABLE_NAME,
                            [$where_str]);
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_courseid($userid)
    {
        $sql = $this->gen_sql("select courseid from %s "
                              ."where userid = %u and course_type=2  ",
                              self::DB_TABLE_NAME,
                              $userid
        );
        return $this->main_get_value($sql);

    }

    public function get_lesson_total_all($studentid)
    {
        $course_type = $this->where_get_in_str("contract_type", [
            E\Econtract_type::V_0,
            E\Econtract_type::V_1,
            E\Econtract_type::V_3,
            //E\Econtract_type::V_1003,
        ]);

        $sql=$this->gen_sql("select sum(lesson_total*default_lesson_count) from %s  where userid = %u and %s  and  contract_status in (1,2,3) ",
                            t_order_info::DB_TABLE_NAME,
                            $studentid,
                            $course_type
        );
        return $this->main_get_value($sql);
    }

    public function get_student_courseid_by_userid($userid)
    {
        $sql=$this->gen_sql("select courseid from %s where userid = %u",
                            self::DB_TABLE_NAME,
                            $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_userid_list() {
        $sql=$this->gen_sql("select distinct userid  from %s ", self::DB_TABLE_NAME  );
        return $this->main_get_list($sql);
    }

    public function update_only_course_type($userid,$orderid,$course_type,$subject) {
        $sql=$this->gen_sql("update %s set course_type = %u,subject=%u where userid = %u and orderid = %u ",
                            self::DB_TABLE_NAME,
                            $course_type,
                            $subject,
                            $userid,
                            $orderid
        );
        return $this->main_update($sql);
    }

    public function get_coursesid_by_orderid($orderid) {
        $sql= $this->gen_sql("select courseid from %s where orderid=%u",
                             self::DB_TABLE_NAME,$orderid);
        return $this->main_get_value($sql);
    }

    public function get_user_lesson_total($courseid){
        $sql=$this->gen_sql("select format(sum(lesson_total*default_lesson_count/100),1) as lesson_total "
                            ." from %s "
                            ." where courseid=%u"
                            ." and course_type in (0,1,3)"
                            ,self::DB_TABLE_NAME
                            ,$courseid
        );
        return $this->main_get_value($sql);
    }

    public function get_user_assigned_lesson_count($userid,$competition_flag=-1, $exclude_couserid= -1 ){
        $where_arr=[
            ["courseid <> %u", $exclude_couserid,-1] ,
            ["competition_flag=%u", $competition_flag,-1] ,
        ];
        $sql=$this->gen_sql_new("select sum(assigned_lesson_count)/100 "
                                ." from %s "
                                ." where userid=%u "
                                ." and %s "
                                ." and course_type in (0,1,3 )"
                                ,self::DB_TABLE_NAME
                                ,$userid
                                ,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_user_lesson_cost($userid){
        $sql=$this->gen_sql("select format(sum(lesson_total*default_lesson_count/100),1) as lesson_cost "
                            ." from %s "
                            ." where userid=%u"
                            ." and course_type in (0,1,3)"
                            ,self::DB_TABLE_NAME
                            ,$userid
        );
        return $this->main_get_value($sql);
    }

    function get_list($userid=-1,$courseid=-1,$competition_flag=-1 ,$teacherid=-1){
        $where_str=$this->where_str_gen([
            ["t1.courseid=%u",$courseid, -1 ] ,
            ["t1.userid=%u",$userid, -1 ] ,
            ["t1.competition_flag=%u",$competition_flag, -1 ] ,
            ["t1.teacherid=%u",$teacherid, -1 ] ,
            "t1.course_type in (0,1,3)"  ,
        ]);
        $sql=$this->gen_sql("select t1.userid,t1.courseid,t1.grade,t1.subject, t1.teacherid,t1.lesson_grade_type,"
                            ."sum(t2.lesson_count) as lesson_count,t1.competition_flag, "
                            ."sum(case when lesson_status=0 then lesson_count else 0 end )  as no_finish_lesson_count , "
                            ."sum(case when lesson_status=0 then 0 else "
                            ."(case when confirm_flag in (2,4) then 0 else t2.lesson_count end) "
                            ."end ) as finish_lesson_count,t1.add_time, "
                            ."t1.teacherid, t1.assistantid,t1.course_type,t1.default_lesson_count,t1.assigned_lesson_count, "
                            ."course_status,t1.week_comment_num,t1.enable_video,t1.reset_lesson_count_flag"
                            ." from %s t1 "
                            ." left join %s t2 on t1.courseid = t2.courseid "
                            ." and (lesson_del_flag=0 or lesson_del_flag is null)"
                            ." where %s "
                            ." group by t1.courseid order by t1.courseid desc "
                            ,self::DB_TABLE_NAME
                            ,t_lesson_info::DB_TABLE_NAME
                            ,[$where_str]
        );
        return $this->main_get_list($sql);
    }

    public function get_last_courseid(){
        $sql="select last_insert_id()";
        return $this->main_get_value($sql);
    }

    function get_tea_stu($student_type,$teacherid){
        $where_arr=[
            ["s.type=%u",$student_type, -1 ] ,
            ["c.teacherid=%d",$teacherid, -100 ] ,
            "c.course_type in (0,1,3)",
        ];

        $sql=$this->gen_sql_new("select distinct s.userid ,s.nick,s.type,s.parent_name,s.phone,s.grade,s.lesson_count_all,s.lesson_count_left,last_lesson_time "
                                ." from %s c,%s s  "
                                ." where c.userid = s.userid and %s  order by  last_lesson_time desc ",
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                $where_arr
        );
       # return $sql;
       return $this->main_get_list_as_page($sql);
    }
    function get_sum_stu($teacherid){
        $where_arr=[
            ["c.teacherid=%u",$teacherid, -1 ] ,
            "l.lesson_type=0",
        ];

        $sql=$this->gen_sql_new("select sum(type=0) type0, "
                                ."sum(type=1) type1, "
                                ."sum(type=2) type2, "
                                ."sum(type=3) type3, "
                                ."from %s c,%s s,%s l"
                                ." where c.userid = s.userid = l.userid and %s "
                                ." and lesson_del_flag =0 "
                                ,
                                self::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                $where_arr
        );
       return $this->main_get_row($sql);
    }

    public function get_unassigned_lesson_total($userid,$competition_flag){
        $sql=$this->gen_sql_new("select sum(r.should_refund)/100 "
                                ." from %s r"
                                ." left join %s o on r.orderid=o.orderid"
                                ." where r.userid=%u"
                                ." and o.competition_flag=%u"
                                ,t_order_refund::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$userid
                                ,$competition_flag
        );
        $lesson_refund=$this->main_get_value($sql);

        $sql = $this->gen_sql_new("select sum(assigned_lesson_count)/100"
                                  ." from %s "
                                  ." where userid=%u"
                                  ." and competition_flag=%u"
                                  ." and course_type in (0,1,3)"
                                  ,self::DB_TABLE_NAME
                                  ,$userid
                                  ,$competition_flag
        );
        $assigned_lesson_count=$this->main_get_value($sql);

        $sql=$this->gen_sql_new("select sum(lesson_total*default_lesson_count)/100"
                                ." from %s"
                                ." where userid=%u"
                                ." and contract_type in (0,1,3)"
                                ." and contract_status in (1,2,3)"
                                ." and competition_flag=%u"
                                ,t_order_info::DB_TABLE_NAME
                                ,$userid
                                ,$competition_flag
        );
        $lesson_total=$this->main_get_value($sql);
        \App\Helper\Utils::logger("userid :".$userid."lesson_total:".$lesson_total
                                  ."lesson_refund:".$lesson_refund
                                  ."assigned_lesson_count".$assigned_lesson_count);

        $lesson_unassigned = $lesson_total-$lesson_refund-$assigned_lesson_count;
        return $lesson_unassigned;
    }

    public function get_all_list($page_num,$userid,$teacherid= -1,$course_type=-1 ){
        $where_arr=[
            ["teacherid=%u", $teacherid, -1],
        ];
        if($course_type >=0){
            $where_arr[]="course_type <>2";
        }

        $sql = $this->gen_sql_new("select * from %s where userid = %u and %s",
                                  self::DB_TABLE_NAME,
                                  $userid, $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_all_list_new($page_num,$userid,$teacherid= -1 ){
        $where_arr=[
            ["teacherid=%u", $teacherid, -1],
            "course_type in (0,3)",
            "course_status=0"
        ];

        $sql = $this->gen_sql_new("select * from %s where userid = %u and %s",
                                  self::DB_TABLE_NAME,
                                  $userid, $where_arr
        );

        return $this->main_get_list_by_page($sql,$page_num);
    }


    public function get_count_total($teacherid,$userid){

        $sql = $this->gen_sql_new("select lesson_total from %s where userid = %s and teacherid= %s",
                                  self::DB_TABLE_NAME,
                                  $userid,
                                  $teacherid
        );
        return $this->main_get_value($sql);
    }

    public function get_courseid_by_stu_tea($userid,$teacherid,$competition_flag =0)
    {
        $sql=$this->gen_sql("select courseid,course_type "
                            ." from %s "
                            ." where userid = %u "
                            ." and teacherid = %u "
                            // ." and course_status=0 "
                            ." and course_type <>2 "
                            ." and competition_flag = %u "
                            ,self::DB_TABLE_NAME
                            ,$userid
                            ,$teacherid
                            ,$competition_flag
        );
        return $this->main_get_list($sql);
    }

    function add_course_info_new(
        $orderid,$userid,$grade,$subject,$default_lesson_count,
        $course_type,$course_status,$lesson_total,$lesson_left,$assistantid,
        $teacherid
    ){
        $course_start = time();
        $date_now     = date('Y-m-d H:i:s',time());
        $year         = intval(substr($date_now,0,4))+3;
        $end_time     = $year.substr($date_now, 4,19);
        $course_end   = strtotime($end_time);
        $this->row_insert([
            "userid"               => $userid,
            "orderid"              => $orderid,
            "course_type"          => $course_type,
            "grade"                => $grade,
            "subject"              => $subject,
            "default_lesson_count" => $default_lesson_count,
            "course_status"        => $course_status,
            "lesson_total"         => $lesson_total,
            "lesson_left"          => $lesson_left,
            "course_start"         => $course_start,
            "course_end"           => $course_end,
            "assistantid"          => $assistantid,
            "teacherid"            => $teacherid
        ]);
        return $this->get_last_insertid();
    }

    public function clean_ass_from_test_lesson_id( $ass_from_test_lesson_id ) {
        $sql=$this->gen_sql_new("update %s set  ass_from_test_lesson_id =0 "
                                ." where ass_from_test_lesson_id=%u"
                                ,self::DB_TABLE_NAME
                                ,$ass_from_test_lesson_id
        );
        return $this->main_update($sql);
    }

    public function get_teacher_list($userid){
        $sql=$this->gen_sql_new("select if(t.realname='',t.nick,t.realname) as tea_nick,"
                                ." if(s.realname='',s.nick,s.realname)as stu_nick"
                                ." from %s c"
                                ." left join %s t on t.teacherid=c.teacherid"
                                ." left join %s s on s.userid=c.userid"
                                ." where c.userid in (%s)"
                                ." and c.teacherid!=0"
                                ." and course_type in (0,1,3)"
                                ." group by c.userid,c.teacherid"
                                ,self::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$userid
        );
        return $this->main_get_list($sql);
    }

    public function add_open_course($teacherid,$course_name,$grade,$subject,$course_type=1001,
                                    $packageid=0,$lesson_total=10,$stu_total=1000,$enter_type=1
    ){
        $this->t_course_order->row_insert([
            "teacherid"    => $teacherid,
            "course_name"  => $course_name,
            "grade"        => $grade,
            "subject"      => $subject,
            "course_type"  => $course_type,
            "lesson_total" => $lesson_total,
            "stu_total"    => $stu_total,
            "packageid"    => $packageid,
            "enter_type"   => $enter_type,
        ]);
        return $this->get_last_insertid();
    }

    public function get_ass_tea_info($userid){
        $sql=$this->gen_sql_new("select assistantid,teacherid from %s where userid = %u order by add_time limit 1",
                                self::DB_TABLE_NAME,
                                $userid
        );
        return $this->main_get_row($sql);
    }
    public function get_have_order_info($teacherid,$userid,$subject){
        $where_arr=[
            ["subject",$subject, -1 ] ,
            ["teacherid=%d",$teacherid, -1 ] ,
            ["userid=%d",$userid, -1 ] ,
            "course_type =0",
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_stu_assigned_list($start_time,$end_time,$student_type){
        $exists_str = $student_type==0?"exists":"not exists";
        $lesson_arr = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type in (0,1,3)",
            "lesson_status=2",
            "confirm_flag!=2",
            "lesson_del_flag=0",
        ];

        $sql = $this->gen_sql_new("select c.userid,s.nick,c.subject,sum(assigned_lesson_count) as lesson_total,s.type"
                                  ." from %s c "
                                  ." left join %s s on c.userid=s.userid"
                                  ." where %s "
                                  ." (select 1 "
                                  ." from %s"
                                  ." where %s"
                                  ." and c.userid=userid"
                                  ." )"
                                  ." and s.is_test_user=0"
                                  ." and course_type in (0,1,3)"
                                  ." group by c.userid,c.subject"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$exists_str
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$lesson_arr
        );
        return $this->main_get_list($sql,function($item){
            return ($item['userid']."_".$item['subject']);
        });

    }

    public function get_all_info_new(){
        $sql = $this->gen_sql_new("select add_time,courseid from %s order by add_time desc limit 10",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_order_turn_info($time){
        $month_start = strtotime(date("Y-m-01",time()));
        $confirm_time = strtotime(date("Y-m-01",$month_start-10*86400));
        $where_arr=[
            "c.add_time >=".$time,
            "send_wx_flag=0",
            "course_type in (0,3)",
            "(tl.account is not null or tl.account <>'')",
            // "tq.require_admin_type=2",
            "mm.account_role=2",
            "mm.del_flag=0",
            "m.del_flag =0"
            //"courseid in (20508,20386,20387,20481,20377,20492,20478)"
        ];
        $sql=$this->gen_sql_new("select distinct c.courseid,c.subject,c.teacherid,t.realname,c.userid,c.send_wx_flag,tl.account,tt.teacherid interview_teacherid,tt.realname interview_nick,c.add_time,s.nick,m.uid,c.ass_from_test_lesson_id from %s c"
                                ." left join %s t on c.teacherid=t.teacherid"
                                ." left join %s tl on (t.phone=tl.phone and tl.status=1 and tl.subject=c.subject)"
                                ." left join %s m on tl.account=m.account"
                                ." left join %s tt on m.phone=tt.phone"
                                ." left join %s s on c.userid=s.userid"
                                ." left join %s tq on (c.userid = tq.userid and c.subject = tq.subject)"
                                ." left join %s tr on tq.current_require_id =tr.require_id"
                                ." left join %s mm on tr.cur_require_adminid = mm.uid"
                                ." where %s and c.add_time=(select min(add_time) from %s where subject=c.subject and teacherid=c.teacherid and userid=c.userid and add_time>0) and tl.confirm_time >=%u ",
                                self::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_teacher_lecture_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                t_test_lesson_subject::DB_TABLE_NAME,
                                t_test_lesson_subject_require::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr,
                                self::DB_TABLE_NAME,
                                $confirm_time
        );
        return $this->main_get_list($sql);
    }
    public function get_teacher_order_turn_info_new($start_time,$end_time){
        $confirm_time = strtotime(date("Y-m-01",$start_time-40*86400));
        //$confirm_time = strtotime(date("2017-01-05"));
        $where_arr=[
            "c.add_time >=".$start_time,
            "c.add_time <".$end_time,
            // "send_wx_flag=0",
            "course_type=0",
            "(tl.account is not null or tl.account <>'')",
            "tq.require_admin_type=2"
            //"courseid in (20508,20386,20387,20481,20377,20492,20478)"
        ];
        $sql=$this->gen_sql_new("select distinct c.courseid,c.subject,c.teacherid,t.realname,c.userid,c.send_wx_flag,tl.account,tt.teacherid interview_teacherid,tt.realname interview_nick,c.add_time,s.nick,m.uid,c.ass_from_test_lesson_id from %s c"
                                ." left join %s t on c.teacherid=t.teacherid"
                                ." left join %s tl on (t.phone=tl.phone and tl.status=1 and tl.subject=c.subject)"
                                ." left join %s m on tl.account=m.account"
                                ." left join %s tt on m.phone=tt.phone"
                                ." left join %s s on c.userid=s.userid"
                                ." left join %s tq on (c.userid = tq.userid and c.subject = tq.subject)"
                                ." where %s and c.add_time=(select min(add_time) from %s where subject=c.subject and teacherid=c.teacherid and userid=c.userid and add_time>0) and tl.confirm_time >=%u ",
                                self::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_teacher_lecture_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                t_test_lesson_subject::DB_TABLE_NAME,
                                $where_arr,
                                self::DB_TABLE_NAME,
                                $confirm_time
        );
        return $this->main_get_list($sql);
    }


    public function get_teacher_order_turn_info_send($time){
        $where_arr=[
            "c.add_time >=".$time,
            "send_wx_flag=1",
            "course_type in (0,3)",
            "tt.realname like '%%张科%%'",
            "(tl.account is not null or tl.account <>'')"
        ];
        $sql=$this->gen_sql_new("select c.courseid,c.subject,c.teacherid,t.realname,c.userid,c.send_wx_flag,tl.account,tt.teacherid interview_teacherid,tt.realname interview_nick,c.add_time,s.nick,m.uid from %s c"
                                ." left join %s t on c.teacherid=t.teacherid"
                                ." left join %s tl on (t.phone=tl.phone and tl.status=1 and tl.subject=c.subject)"
                                ." left join %s m on tl.account=m.account"
                                ." left join %s tt on m.phone=tt.phone"
                                ." left join %s s on c.userid=s.userid"
                                ." where %s ",
                                self::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_teacher_lecture_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function tongji_week_teacher_order_turn_info($time){
        $where_arr=[
            "c.add_time >=".$time,
            "course_type=0",
            "(tl.account is not null or tl.account <>'')"
        ];
        $sql=$this->gen_sql_new("select count(*)*10 money,tt.teacherid,tt.realname from %s c"
                                ." left join %s t on c.teacherid=t.teacherid"
                                ." left join %s tl on (t.phone=tl.phone and tl.status=1 and tl.subject=c.subject)"
                                ." left join %s m on tl.account=m.account"
                                ." left join %s tt on m.phone=tt.phone"
                                ." left join %s s on c.userid=s.userid"
                                ." where %s and c.add_time=(select max(add_time) from %s where subject=c.subject and teacherid=c.teacherid and userid=c.userid) group by tt.teacherid",
                                self::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_teacher_lecture_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                t_teacher_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                $where_arr,
                                self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_tea_stu_info($teacherid,$account_role){
        $where_arr=[
            ["t1.teacherid=%u",$teacherid, -1 ] ,
            ["m.account_role=%u",$account_role, -1 ] ,
            "t1.course_type in (0,1,3)"  ,
            // "t1.course_status=0",
            "m.account_role in (4,5)",
            "m.del_flag=0",
            "s.is_test_user=0"
        ];
        $sql=$this->gen_sql_new("select t1.userid,t1.grade,t1.subject, t1.teacherid,s.nick,t.realname,"
                            ."sum(t2.lesson_count) as lesson_count, "
                            ."sum(case when lesson_status=0 then lesson_count else 0 end )  as no_finish_lesson_count , "
                            ."sum(case when lesson_status=0 then 0 else "
                            ."(case when confirm_flag=2 then 0  else  t2.lesson_count end) "
                            ."end ) as finish_lesson_count, "
                            ." t1.assistantid,t1.course_type,t1.default_lesson_count,t1.assigned_lesson_count "
                            ." from %s t1 "
                            ." left join %s t2 on t1.courseid = t2.courseid "
                            ." left join %s s on t1.userid = s.userid"
                            ." left join %s t on t1.teacherid = t.teacherid"
                            ." left join %s m on t.phone = m.phone"
                            ." where %s "
                            ." and (lesson_del_flag=0 or lesson_del_flag is null)"
                            ." group by t1.userid having(assigned_lesson_count-finish_lesson_count>0) ",
                            self::DB_TABLE_NAME,
                            t_lesson_info::DB_TABLE_NAME,
                            t_student_info::DB_TABLE_NAME,
                            t_teacher_info::DB_TABLE_NAME,
                            t_manager_info::DB_TABLE_NAME,
                            $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_tea_stu_num($teacherid){
        $where_str=$this->where_str_gen([
            ["teacherid=%u",$teacherid, -1 ] ,
            "course_type in (0,3)"  ,
            "course_status=0"
        ]);
        $sql=$this->gen_sql("select count(distinct userid)"
                            ." from %s where %s "
                            ,self::DB_TABLE_NAME
                            ,[$where_str]
        );
        return $this->main_get_value($sql);
    }

    public function get_tea_stu_num_list($tea_arr){
        $where_arr=[
            "course_type in (0,3)"  ,
            "course_status=0"
        ];
        $this->where_arr_teacherid($where_arr,"teacherid", $tea_arr );
        $sql=$this->gen_sql_new("select count(distinct userid) num,teacherid"
                            ." from %s where %s group by teacherid "
                            ,self::DB_TABLE_NAME
                            ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }


    public function get_tea_userid_detail_list($teacherid){
        $where_str=$this->where_str_gen([
            ["teacherid=%u",$teacherid, -1 ] ,
            "course_type in (0,3)"  ,
            "course_status=0"
        ]);
        $sql=$this->gen_sql("select distinct userid"
                            ." from %s where %s "
                            ,self::DB_TABLE_NAME
                            ,[$where_str]
        );
        $arr = $this->main_get_list($sql);
        $list=[];
        foreach($arr as $val){
            $list[] = $val;
        }
        return $list;
    }

    public function reset_assigned_lesson_count($userid,$competition_flag){
        $where_arr = [
            ["c.userid=%u",$userid,0],
            ["c.competition_flag=%u",$competition_flag,0],
        ];

        $sql = $this->gen_sql_new("select c.courseid,c.assigned_lesson_count,sum(l.lesson_count) as lesson_cost"
                                  ." from %s c"
                                  ." left join %s l on c.courseid=l.courseid and lesson_status=2 and lesson_type in (0,1,3) "
                                  ." and confirm_flag!=2 and lesson_del_flag=0"
                                  ." where %s"
                                  ." group by c.courseid"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        $user_info = $this->main_get_list($sql);

        if(is_array($user_info) && !empty($user_info)){
            $this->start_transaction();
            foreach($user_info as $user_val){
                if(empty(intval($user_val['lesson_cost']))){
                    $user_val['lesson_cost'] = 0;
                }
                if($user_val['assigned_lesson_count'] != $user_val['lesson_cost']){
                    $ret = $this->field_update_list($user_val['courseid'],[
                        "assigned_lesson_count" => $user_val['lesson_cost']
                    ]);
                    if(!$ret){
                        $this->rollback();
                        return false;
                    }
                }
            }
            $this->commit();
        }
        return true;
    }

    public function check_have_course_order($teacherid,$userid,$subject){
        $sql = $this->gen_sql_new("select 1 from %s where teacherid=%u and userid= %u and subject=%u and course_type=0",
                                  self::DB_TABLE_NAME,
                                  $teacherid,
                                  $userid,
                                  $subject
        );
        return $this->main_get_value($sql);
    }

    public function get_course_order_info_new($courseid){
        $where_arr = [
            ["courseid=%u",$courseid,-1],
        ];
        $sql = $this->gen_sql_new("select c.teacherid,c.userid,c.subject,s.grade,n.phone_location,n.stu_score_info,"
                                  ." n.stu_character_info,tt.realname,t.textbook"
                                  ." from %s c left join %s s on c.userid = s.userid"
                                  ." left join %s t on (c.userid = t.userid and c.subject = t.subject)"
                                  ." left join %s n on c.userid = n.userid"
                                  ." left join %s tt on c.teacherid = tt.teacherid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function transfer_teacher_course($old_teacherid,$new_teacherid){
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

    public function get_course_count($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
        ];
        $sql = $this->gen_sql_new("select count(1) "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_kk_succ_info($start_time,$end_time){
        $where_arr=[
            "is_kk_flag=1"
        ];
        $this->where_arr_add_time_range($where_arr,"c.add_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("select count(*) num,m.uid"
                                  ." from %s c left join %s s on c.userid = s.userid"
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." where %s group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_course_list_by_teacherid($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            "course_type in (0,1,3)",
        ];
        $sql = $this->gen_sql_new("select courseid,userid,subject,grade,assistantid,default_lesson_count,competition_flag,"
                                  ." lesson_grade_type,course_status,is_kk_flag"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_lesson_left_info($flag){
        $where_arr=[
            "c.course_type in (0,3)",
            "s.type=0",
            "s.is_test_user=0",
            "c.userid>0"
        ];
        if($flag==1){
            $where_arr[]="c.lesson_left<=19";
        }else{
            $where_arr[]="c.lesson_left>19";
        }
        $sql = $this->gen_sql_new("select distinct c.userid,s.nick,a.nick ass_nick,m.uid,s.lesson_count_all ,s.lesson_count_left,s.phone,s.stu_phone"
                                  ." from %s c left join %s s on c.userid= s.userid"
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on m.phone = a.phone"
                                  ." where %s having(m.uid>0)"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_assistant_info::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["userid"];
        });
    }

    public function get_course_order_grade(){
        $where_arr = [
            "course_type in (0,1,3)"
        ];
        $sql = $this->gen_sql_new("select c.courseid,c.subject,s.grade"
                                  ." from %s c"
                                  ." left join %s s on c.userid=s.userid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function reset_course_lesson_gradse_type($lesson_grade_type){
        $sql = $this->gen_sql_new("update %s set lesson_grade_type=%u"
                                  ,self::DB_TABLE_NAME
                                  ,$lesson_grade_type
        );
        return $this->main_update($sql);
    }
    function get_list_total($userid=-1,$courseid=-1,$competition_flag=-1 ,$teacherid=-1){
        $where_str=$this->where_str_gen([
            ["t1.courseid=%u",$courseid, -1 ] ,
            ["t1.userid=%u",$userid, -1 ] ,
            ["t1.competition_flag=%u",$competition_flag, -1 ] ,
            ["t1.teacherid=%u",$teacherid, -1 ] ,
            "t1.course_type in (0,1,3)"  ,
            "t1.course_type=0 ",
            "t1.course_status=0 "
        ]);
        /*$sql=$this->gen_sql("select t1.userid,t1.courseid,t1.grade,t1.subject, t1.teacherid,t1.lesson_grade_type,"
                            ."sum(t2.lesson_count) as lesson_count, "
                            ."sum(case when lesson_status=0 then lesson_count else 0 end )  as no_finish_lesson_count , "
                            ."sum(case when lesson_status=0 then 0 else "
                            ."(case when confirm_flag in (2,4) then 0 else t2.lesson_count end) "
                            ."end ) as finish_lesson_count,t1.add_time, "
                            ."t1.teacherid, t1.assistantid,t1.course_type,t1.default_lesson_count,t1.assigned_lesson_count, "
                            ."course_status,t1.week_comment_num,t1.enable_video"
                            ." from %s t1 "
                            ." left join %s t2 on t1.courseid = t2.courseid "
                            ." and (lesson_del_flag=0 or lesson_del_flag is null)"
                            ." where %s "
                            ." group by t1.courseid order by t1.courseid desc "
                            ,self::DB_TABLE_NAME
                            ,t_lesson_info::DB_TABLE_NAME
                            ,[$where_str]
        );
        */
        $sql=$this->gen_sql("select t1.subject"
                            ." from %s t1 "
                            ." left join %s t2 on t1.courseid = t2.courseid "
                            ." and (lesson_del_flag=0 or lesson_del_flag is null)"
                            ." where %s "
                            ." group by t1.courseid order by t1.courseid desc "
                            ,self::DB_TABLE_NAME
                            ,t_lesson_info::DB_TABLE_NAME
                            ,[$where_str]
        );
        //dd($sql);
        return $this->main_get_list($sql);
    }


    public function update_course_status($courseid){
        $sql = $this->gen_sql_new("update %s set course_status = 0 "
                                ."where courseid = %u ",
                            self::DB_TABLE_NAME,
                            $courseid
        );
        $ret =  $this->main_update($sql);
    }
    function get_course_list(){
        $where_str=$this->where_str_gen([
            //["course_status=%u",$course_status,0],
            //"t1.userid > 0", //
            //"t1.assigned_lesson_count >0 ",//
            "course_status=0"
        ]);

        $sql=$this->gen_sql("select t1.userid,t1.courseid,"
                            ."sum(t2.lesson_count) as lesson_count, "
                            ."sum(case when lesson_status=0 then lesson_count else 0 end )  as no_finish_lesson_count , "
                            ."sum(case when lesson_status=0 then 0 else "
                            ."(case when confirm_flag in (2,4) then 0 else t2.lesson_count end) "
                            ."end ) as finish_lesson_count,t1.add_time, "
                            ."t1.teacherid, t1.assistantid,t1.course_type,t1.default_lesson_count,t1.assigned_lesson_count, "
                            ."course_status"
                            ." from %s t1 "
                            ." left join %s t2 on t1.courseid = t2.courseid "
                            ." and (lesson_del_flag=0 or lesson_del_flag is null)"
                            ." where %s "
                            ." group by t1.courseid order by t1.courseid desc "
                            ,self::DB_TABLE_NAME
                            ,t_lesson_info::DB_TABLE_NAME
                            ,[$where_str]
        );
        return $this->main_get_list($sql);
    }
}