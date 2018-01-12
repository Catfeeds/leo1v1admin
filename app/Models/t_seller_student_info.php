<?php
namespace App\Models;

use \App\Jobs\noti_add_seller_user;
use \App\Enums as E;
/**

 * @property t_origin_key  $t_origin_key

 * @property t_book_revisit  $t_book_revisit
 * @property t_seller_student_info_sub  $t_seller_student_info_sub

 * @property t_assistant_info  $t_assistant_info

 */

class t_seller_student_info extends \App\Models\Zgen\z_t_seller_student_info
{
    var $test_lesson_status_list_str="6,7,8,9,10,11,12,13,14,15,20,21";

    public function __construct()
    {
        parent::__construct();
    }



    public function get_today_next_revisit_count( $admin_revisiterid )
    {//
        $today=strtotime( date("Y-m-d" )) ;
        $sql = $this->gen_sql(
            "select count(*) from %s where admin_revisiterid=%u and next_revisit_time>=%u and next_revisit_time < %u  ",
            self::DB_TABLE_NAME,
            $admin_revisiterid,
            $today-864000,
            $today+86400
        );
        return $this->main_get_value($sql);
    }

    public function get_return_back_count( $admin_revisiterid )
    {//
        $sql = $this->gen_sql(
            "select count(*) from %s where admin_revisiterid=%u and status = %u and st_application_time >%u  ",
            self::DB_TABLE_NAME,
            $admin_revisiterid,
            E\Ebook_status::V_14 ,	//试听-驳回
            time(NULL)-14*86400
        );
        return $this->main_get_value($sql);
    }

    public function get_require_count( $admin_revisiterid )
    {//
        $sql = $this->gen_sql(
            "select count(*) from %s where admin_revisiterid=%u and status = %u and st_application_time >%u  ",
            self::DB_TABLE_NAME,
            $admin_revisiterid,
            E\Ebook_status::V_TEST_LESSON_REPORT,
            time(NULL)-14*86400
        );
        return $this->main_get_value($sql);
    }


    public function get_lesson_content($lessonid){
        $sql=$this->gen_sql("select stu_lesson_content from %s where st_arrange_lessonid=%u"
                            ,self::DB_TABLE_NAME
                            ,$lessonid
        );
        return $this->main_get_value($sql);
    }

    public function get_list( $page_num, $page_count, $admin_revisiterid, $status, $phone,
                              $origin,$opt_date_type_str,$start_time,$end_time,$grade,$subject,
                              $phone_location,$origin_ex,$nick,$has_pad=-1 ,$ass_adminid_flag = -1,
                              $admin_assign_time_flag=-1, $tq_called_flag=-1 ,$seller_resource_type=-1,
                              $sub_assign_adminid=-1,$test_lesson_cancel_flag =-1
    ){
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"t1.origin");

        $where_arr = [
            ["t1.seller_resource_type=%d", $seller_resource_type,-1 ] ,
            ["t1.grade=%d", $grade,-1 ] ,
            ["t1.subject=%d", $subject,-1 ] ,
            ["t1.origin like \"%%%s%%\" ", $origin,"" ] ,
            ["t1.phone like \"%%%s%%\" ", $phone ,"" ] ,
            ["t1.nick like \"%%%s%%\" ", $nick,"" ] ,
            ["t1.phone_location like \"%%%s%%\" ", $phone_location ,"" ] ,
            ["has_pad=%d", $has_pad,-1 ] ,
            ["tq_called_flag=%d", $tq_called_flag,-1 ] ,
            ["sub_assign_adminid =%d", $sub_assign_adminid,-1 ] ,
            $ret_in_str,
        ];

        if ($test_lesson_cancel_flag == -2 ) {
            $where_arr[] =      "cancel_flag<>2" ;
        }else{
            $where_arr[] =      ["cancel_flag=%d", $test_lesson_cancel_flag,-1 ] ;
        }

        if  ($admin_revisiterid ==-2 ){
            $where_arr[]="admin_revisiterid>0" ;
        }else{
            $where_arr[]=["admin_revisiterid=%d" ,  $admin_revisiterid ,-1 ];
        }

        if ($ass_adminid_flag==0 ) {
            $where_arr[]="ass_adminid =0";
        }else if   ($ass_adminid_flag==1 ) {
            $where_arr[]="ass_adminid >0";
        }

        if ($admin_assign_time_flag==0 ) {
            $where_arr[]="admin_assign_time =0";
        }else if   ($admin_assign_time_flag==1 ) {
            $where_arr[]="admin_assign_time >0";
        }

        if ( $status ==-2 ) {
           $where_arr[]= [" t1.status<>%u " , 0  ,-1 ];
        }else if ( $status ==-3){//试听用户
            $where_arr[]=  "t1.status in (6,7,8,9,10,11,12,13,14,15,20,21)";
        }else if ( $status ==-4){//需要通知用户
            $where_arr[]=  "t1.status in (10,12)";

        }else{
           $where_arr[]= ["t1.status=%d", $status ,-1 ] ;
        }

        $order_str= "id";
        if ($phone =="") {
            $order_str   = $opt_date_type_str;
            $where_arr[] = ["$order_str>=%d" ,  $start_time,-1 ];
            $where_arr[] = ["$order_str<=%d" ,  $end_time,-1 ];
        }
        $sql = $this->gen_sql_new("select t1.phone, ass_adminid ,tq_called_flag,  t1.userid, notify_lesson_day1,"
                                  ." notify_lesson_day2, t1.money_all,last_revisit_time, next_revisit_time, "
                                  ." st_application_time, t1.phone_location,id, add_time, t1.origin, t1.nick, t1.status,"
                                  ." user_desc, t1.grade, t1.subject, has_pad, admin_revisiterid, admin_assign_time,"
                                  ." last_revisit_msg, t2.teacherid, t2.lesson_start, t2.lesson_end ,st_application_time,"
                                  ." first_revisite_time,t1.st_arrange_lessonid,origin_userid, t1.sub_assign_adminid,"
                                  ." t1.sub_assign_time ,st_test_paper,tea_download_paper_time "
                                  ." from %s t1 "
                                  ." left join %s t2 on t1.st_arrange_lessonid=t2.lessonid "
                                  ." left join %s s on t1.userid=s.userid"
                                  ." where  %s "
                                  ."order by %s desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$order_str
        );
        $ret_info = $this->main_get_list_by_page($sql,$page_num,$page_count);

        return $this->reset_phone_location($ret_info);
    }

    public function get_unallot($start_time,$end_time)
    {
        $where_arr = [
            "sub_assign_time = 0" ,
            "ass_adminid > 0 ",
            ["add_time >= %d", $start_time,-1 ] ,
            ["add_time <= %d", $end_time,-1 ] ,
        ];
        $sql = $this->gen_sql_new("select count(id) unallot_all from %s "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_row($sql);
    }

    public function get_unset_admin_revisiterid($start_time,$end_time )
    {
        $where_arr = [
            "sub_assign_adminid = 0 ",
            "admin_revisiterid = 0 ",
            ["add_time >= %d", $start_time,-1 ] ,
            ["add_time <= %d", $end_time,-1 ] ,
        ];
        $sql = $this->gen_sql_new("select count(id) unset_all  from  %s "
                                  ." where  %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_row($sql);
    }

    public function group_master_get_unallot($start_time,$end_time,$sub_assign_adminid)
    {
        $where_arr = [
            "admin_assign_time = 0" ,
            "ass_adminid > 0 ",
            ["sub_assign_time >= %d", $start_time,-1 ] ,
            ["sub_assign_time <= %d", $end_time,-1 ] ,
            ["sub_assign_adminid = %u", $sub_assign_adminid,-1 ] ,
        ];
        $sql = $this->gen_sql_new("select count(id) unallot_all  from  %s "
                                  ." where  %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);

        return $this->main_get_row($sql);
    }

    public function group_master_get_unset_admin_revisiterid($start_time,$end_time, $sub_assign_adminid)
    {
        $where_arr = [
            "admin_assign_time = 0" ,
            "admin_revisiterid = 0 ",
            [" sub_assign_time >= %d", $start_time,-1 ] ,
            [" sub_assign_time <= %d", $end_time,-1 ] ,
            ["sub_assign_adminid = %u", $sub_assign_adminid,-1 ] ,
        ];
        $sql = $this->gen_sql_new("select count(id) unset_all  from  %s "
                                  ." where  %s ",
                                  self::DB_TABLE_NAME,
                                  $where_arr);

        return $this->main_get_row($sql);
    }

    public function test_lesson_tongi($order_by_str, $start_time,$end_time) {
        $where_arr=[];
        $where_arr[]=  "sl.status in (6,7,8,9,10,12,13,14,15)";
        $where_arr[]= "((lesson_start>=$start_time and lesson_start<$end_time ) || (cancel_lesson_start >=$start_time and cancel_lesson_start<$end_time ))" ;

        $where_arr[]= "is_test_user=0";

        $sql=$this->gen_sql_new("select st_application_nick,  count(*) as all_count ,sum(sl.status=15) as bad_count,  sum( l.lessonid is null) as before_4_bad_count  from %s sl"
                                ." left join %s l on sl.st_arrange_lessonid = l.lessonid  "
                                ."left join %s s on sl.userid= s.userid"
                                ." where %s  "
                                ." group by st_application_nick $order_by_str ",
                                self::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list_as_page($sql);

    }

    public function test_lesson_list($page_num,$start_time,$end_time,$lesson_flag) {

        $where_arr=[];
        $where_arr[]=  "sl.status in (6,7,8,9,10,12,13,14,15)";
        $where_arr[]= "((lesson_start>=$start_time and lesson_start<$end_time ) || (cancel_lesson_start >=$start_time and cancel_lesson_start<$end_time ))" ;
        $where_arr[]= "is_test_user=0";
        if ($lesson_flag ==1)  { //正常
            $where_arr[]="confirm_flag in (0,1)";
        }else if ($lesson_flag ==2)  { //老师不要工资
            $where_arr[]="(confirm_flag in (2) or confirm_flag  is null ) ";
        }else if ($lesson_flag ==3)  { //老师要工资
            $where_arr[]="confirm_flag in (3)";
        }

        $sql=$this->gen_sql_new("select  notify_lesson_day1,notify_lesson_day2, sl.status, sl.grade,sl.subject, st_application_nick, if(lesson_start>0, lesson_start,cancel_lesson_start ) as real_lesson_start,lesson_end, cancel_lesson_start, l.teacherid, sl.userid, sl.cancel_teacherid, l.lessonid , l.confirm_flag,  sl.phone from %s sl "
                                ."left join %s l on sl.st_arrange_lessonid = l.lessonid  "
                                ."left join %s s on sl.userid= s.userid"
                                ." where %s  "
                                ,
                                self::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME,
                                $where_arr);
        $order_str="order by real_lesson_start asc";
        return $this->main_get_list_by_page($sql, $page_num,100,false,$order_str);

    }

    public function test_lesson_get_list($opt_date_str,$st_application_nick, $status,$phone,$origin,
                                         $start_time,$end_time, $grade,$subject,$page_num,
                                         $from_type,$origin_ex,$st_arrange_lessonid=-1,$userid=-1, $teacherid=-1,
                                         $confirm_flag=-1, $require_user_type=-1 ,
                                         $ass_adminid_flag =-1,  $test_lesson_cancel_flag=-1
    ){
        if ($st_arrange_lessonid>0) {
            $where_arr=[
                ["st_arrange_lessonid =%u ", $st_arrange_lessonid ,-1 ]
            ];
            $order_field="st_application_time";
        }else{
            $where_arr=[
                ["t1.grade=%d", $grade,-1 ] ,
                ["t1.subject=%d", $subject,-1 ] ,
                ["t2.userid=%d", $userid,-1 ] ,
                ["t2.teacherid=%d", $teacherid,-1 ] ,
                ["t1.origin like \"%%%s%%\" ", $origin,"" ] ,
                ["t1.phone like \"%s%%\" ", $phone ,"" ] ,
                ["st_application_nick like \"%%%s%%\" ", $st_application_nick ,"" ] ,
            ];

            if  ($test_lesson_cancel_flag == -2 ) {
                $where_arr[]="t1.cancel_flag <>2";
            }else{
                $where_arr[]=["t1.cancel_flag = %d", $test_lesson_cancel_flag ,-1 ] ;
            }

            if ($require_user_type ==0 )  { //seller
                $where_arr[]= " admin_revisiterid  <> 1 " ;

            }else if ( $require_user_type ==1  ) {
                $where_arr[]= " admin_revisiterid  = 1 " ;
            }

            if ($ass_adminid_flag==0 ) {
                $where_arr[]="ass_adminid =0";
            }else if   ($ass_adminid_flag==1 ) {
                $where_arr[]="ass_adminid >0";
            }


            if ($confirm_flag==2) {
                $where_arr[] =  "(t2.confirm_flag=2 or ( t2.confirm_flag is null and   t1.cancel_flag>0 ) )";

            } else  if ($confirm_flag==-2) {
                $where_arr[] = "(t2.confirm_flag=2 or  t1.cancel_flag>0 or t2.confirm_flag=3 )";
            }else{
                $where_arr[] = [ "t2.confirm_flag=%u",$confirm_flag,-1];
            }


            $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"t1.origin");

            $where_arr[]= $ret_in_str;
            if ( $status ==-2 ) {
                $where_arr[]= [" t1.status<>%u " , 0  ,-1 ];
            }else if ( $status ==-3){//试听用户
                $where_arr[]=  "t1.status in (6,7,8,9,10,11,12,13,14,15,20,21)";
            }else if( $status == -4){
                $where_arr[] = "confirm_flag<2 ";
                $opt_date_str = "lesson_start";
            }else{
                $where_arr[]= ["t1.status=%d", $status ,-1 ] ;
            }
            if ($from_type==-2) {
                $where_arr[]= " t1.from_type in (1,2) " ;
            }else{
                $where_arr[]= ["t1.from_type=%d", $from_type,-1 ] ;
            }

            $order_field="id";
            if ($phone =="") {
                $order_field=$opt_date_str;
                if($order_field == "lesson_start"){
                    $where_arr[]= "(( lesson_start >= ".$start_time." and lesson_start <= ".$end_time.") or (cancel_lesson_start >= ".$start_time." and cancel_lesson_start <= ".$end_time."))" ;
                }else{
                    $where_arr[]= [" $order_field>=%d" ,  $start_time,-1 ];
                    $where_arr[]= [" $order_field<=%d" ,  $end_time,-1 ];
                }
            }

        }

        $sql = $this->gen_sql_new("select id,t1.phone,ass_adminid,t1.userid st_userid,s.userid,lessonid,t1.st_arrange_lessonid"
                                  .",t1.from_type,st_application_time,t1.tea_download_paper_time "
                                  .",st_application_nick,st_demand,st_arrange_lessonid, t1.grade,t1.subject "
                                  .",t1.nick,t1.status,t1.origin,t1.phone_location,st_from_school, teacherid "
                                  .",t2.confirm_reason,t2.confirm_flag ,lesson_start,lesson_end, st_class_time"
                                  .",has_pad,st_test_paper, user_desc "
                                  .",admin_revisiterid, assigned_teacherid,last_revisit_time, last_revisit_msg, t2.courseid"
                                  .",stu_test_lesson_level,stu_test_ipad_flag, stu_score_info,stu_character_info"
                                  .",stu_request_test_lesson_time_info,stu_request_lesson_time_info"
                                  .",cancel_lesson_start,cancel_flag "
                                  .",editionid,add_time  "
                                  .",cancel_adminid "
                                  .",cancel_time "
                                  .",cancel_teacherid "
                                  .",cancel_reason "
                                  ." from %s t1 "
                                  ." left join %s  t2 on t2.lessonid=t1.st_arrange_lessonid"
                                  ." left join %s  s on t1.userid=s.userid "
                                  ." where  %s "
                                  ."order by $order_field desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        $ret_info= $this->main_get_list_by_page($sql,$page_num,10);

        return $this->reset_phone_location($ret_info);
    }

    public function reset_phone_location($ret_info) {
        foreach  ($ret_info["list"] as &$item) {
            if (!$item["phone_location"] ) {
                //设置到数据库
                $arr=explode("-",$item["phone"]);
                $phone=$arr[0];

                $item["phone_location"] = \App\Helper\Common::get_phone_location($phone);
                if ($item["phone_location"]) {
                    /**
                     *
                     $this->field_update_list($item["phone"],[
                     "phone_location" => $item["phone_location"]
                     ]);
                    */
                }
            }
        }
        return $ret_info;
    }


    public function check_phone_existed($phone) {
        $sql=$this->gen_sql("select count(*)  from %s where phone='%s'",
                            self::DB_TABLE_NAME,
                            $phone);

        return $this->main_get_value($sql) ==1;

    }
    public function add( $phone,  $origin, $nick, $user_desc, $grade, $subject, $has_pad)
    {
        return $this->row_insert([
            self::C_phone => $phone,
            self::C_origin => $origin ,
            self::C_nick=> $nick,
            self::C_user_desc => $user_desc,
            self::C_grade=> $grade,
            self::C_add_time=> time(NULL),
            self::C_subject=> $subject,
            self::C_has_pad=> $has_pad,
        ]);
    }
    //aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    public function check_permission($account, $permission)
    {
        $sql = sprintf("select permission from %s where account = '%s' ",
                       $this->manager_tab,
                       $this->ensql($account)
        );
        $grpid = $this->main_get_value( $sql);
        $grpid_arr = explode(',', $grpid);
        $perms = "";
        foreach($grpid_arr as $key => $value){
            $sql = sprintf("select group_authority from %s where groupid = %u",
                           $this->grp_tab,
                           $value
            );
            $perms .= "," . $this->main_get_value( $sql);
        }
        $perm_arr = explode(',',$perms);
        if(in_array($permission, $perm_arr))
            return true;
        return false;
    }
    //aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    public function del_student($phone)
    {
        $sql = sprintf("delete from %s  where phone = '%s' ",
                       self::DB_TABLE_NAME,
                       $this->ensql($phone)
        );

        return $this->main_update($sql);
    }
    /*

    public function check_status($phone )
    {
        $sql = sprintf("select status from %s where phone= '%s' ",
                       self::DB_TABLE_NAME,
                       $this->ensql($phone)
        );
        $grpid = $this->main_get_value( $sql);
        $grpid_arr = explode(',', $grpid);
        $perms = "";
        foreach($grpid_arr as $key => $value){
            $sql = sprintf("select group_authority from %s where groupid = %u",
                           $this->grp_tab,
                           $value
            );
            $perms .= "," . $this->main_get_value( $sql);
        }
        $perm_arr = explode(',',$perms);
        if(in_array($permission, $perm_arr))
            return true;
        return false;
    }
*/
    public function update_book_revisit($phone, $op_note, $sys_operator)
    {
        /*
          `phone` varchar(16) NOT NULL COMMENT '联系方式',
          `revisit_time` int(10) unsigned NOT NULL COMMENT '回访时间',
          `operator_note` varchar(1024) NOT NULL COMMENT '回访记录',
          `sys_operator` varchar(32) NOT NULL COMMENT '进行回访的人',
         */
        $sql = sprintf("update %s set last_revisit_time = %u, last_revisit_msg = '%s', admin_revisiterid = %u where phone = '%s'",
                       self::DB_TABLE_NAME,
                       time(),
                       $op_note,
                       $sys_operator,
                       $phone
        );

        return $this->main_update($sql);
    }

    public function update_status($phone, $status){
        $sql = sprintf("update %s set status = %u ,last_revisit_time = %u where phone = '%s'",
                      self::DB_TABLE_NAME,
                      $status,
                      time(),
                      $phone
        );
        return $this->main_update($sql);
    }

    public function delete_student($phone)
    {
        $sql = sprintf("delete from %s  where phone= '%s' ",
                       self::DB_TABLE_NAME,
                       $phone
        );
        $this->main_update( $sql  );
    }

    public function set_seller_info($phone,$sellerid,$st_application_nick)
    {

        //$this->t_seller_student_info_sub->set_seller_info($phone, $sellerid);
        $admin_assign_time=0;
        if($sellerid >0 ) {
            $admin_assign_time=time();
        }

        $set_arr=[
            "admin_revisiterid" => $sellerid,
            "admin_assign_time" => $admin_assign_time,
        ];
        if($this->get_st_application_nick($phone))   {
            $set_arr["st_application_nick"] =   $st_application_nick;
        }
        return $this->field_update_list($phone,$set_arr);


    }
    public function set_first_revisite_time($phone) {

        $sql=$this->gen_sql("update %s set   first_revisite_time=%u   where phone='%s' and first_revisite_time =0  ",
                            self::DB_TABLE_NAME, time(NULL) ,$phone);
        $this->main_update( $sql );
    }

    public function set_revisit_info($phone,$status,$note,$op_note)
    {
        if ($op_note){

            $sql=$this->gen_sql("update %s set "
                                ." status=%u, user_desc ='%s', last_revisit_msg ='%s', last_revisit_time =%u "
                                ." where phone='%s'  "
                                ,self::DB_TABLE_NAME
                                ,$status
                                ,$note
                                ,$op_note
                                ,time(NULL)
                                ,$phone
            );
        }else{
            $sql=$this->gen_sql("update %s set "
                                ." status=%u, user_desc ='%s' "
                                ." where phone='%s'"
                                ,self::DB_TABLE_NAME
                                ,$status
                                ,$note
                                ,$phone
            );
        }
        $this->main_update($sql);
    }


    public function get_userid_count($start,$end){
        $sql = sprintf("select "
                       ."count(userid) as userid_c"
                       ."from %s"
                       ."where"
                       ."lesson_start > %u and lesson_end < %u"
                       ,self::DB_TABLE_NAME
                       ,$start
                       ,$end

        );
        return $this->main_get_list($sql);
    }

    public function get_status_count($start,$end){
        $sql = sprintf("select "
                       ."count(userid) as userid_c "
                       ."from %s "
                       ."where "
                       ."last_revisit_time > %u and last_revisit_time < %u"
                       ,self::DB_TABLE_NAME
                       ,$start
                       ,$end

        );
        return $this->main_get_list($sql);
    }

    public function get_status_yy($start,$end){
        $sql = sprintf("select "
                       ."count(status) as yy "
                       ."from %s "
                       ."where "
                       ."status = 9 "
                       ."and "
                       ."last_revisit_time > %u and last_revisit_time < %u"
                       ,self::DB_TABLE_NAME
                       ,$start
                       ,$end

        );
        return $this->main_get_list($sql);

    }

    public function get_status_yst($start,$end){
        $sql = sprintf("select "
                       ."count(status) as yst "
                       ."from %s "
                       ."where "
                       ."status > 5 and status < 9 "
                       ."and "
                       ."last_revisit_time > %u and last_revisit_time < %u"
                       ,self::DB_TABLE_NAME
                       ,$start
                       ,$end

        );

        dd($sql);
        return $this->main_get_list($sql);
    }

    public function get_status_ff($start,$end){
        $sql = sprintf("select "
                       ."count(status) as ff "
                       ."from %s "
                       ."where "
                       ."status = 100 "
                       ."and "
                       ."last_revisit_time > %u and last_revisit_time < %u"
                       ,self::DB_TABLE_NAME
                       ,$start
                       ,$end

        );
        return $this->main_get_list($sql);
    }

    public function add_or_add_to_sub($nick, $phone, $grade,  $origin, $subject, $has_pad, $trial_type, $qq ,$user_desc="",$add_time=0, $add_to_main_flag=false ,$admin_revisiterid=0 ,$st_application_time=0, $st_application_nick="", $st_demand="",$status=0, $ass_adminid=0, $seller_resource_type=0,$userid=0)
    {
        $msg= "资源:手机:$phone<br/>"
            ."渠道:$origin<br/>"
            ."年级:".E\Egrade::get_desc($grade) ."<br/>"
            ."科目:".E\Esubject::get_desc($subject) ."<br/>"
            ."pad:".E\Epad_type::get_desc($has_pad) ."<br/>"
            ."";

        if (\App\Helper\Utils::check_env_is_release()) {
            if ($phone != "15601830297" ) {
                dispatch(new \App\Jobs\noti_add_seller_user( $phone,$subject, $grade,$origin,$has_pad ) );
            }
        }
        if ($add_time ==0 ) {
            $add_time=time(NULL);
        }
        //\App\Helper\Common::send_mail("xcwenn@qq.com", $origin."-". $phone ,  $origin."-". $phone  );
        $stu_row = $this->field_get_list($phone,"*") ;
        if ( $stu_row  && $add_to_main_flag) {
            $tmp_phone=$phone;
            $i=1;
            while ( $stu_row ) {
                $phone=$tmp_phone. "-$i";
                $stu_row=$this->field_get_list($phone,"phone" );
                $i++;
            }
        }

        $this->t_book_revisit->add_book_revisit($phone,"COMMING:$msg","system");


        if ($stu_row) {
            //
            return $this->t_seller_student_info_sub->row_insert([
                \App\Models\t_seller_student_info_sub::C_add_time => $add_time,
                \App\Models\t_seller_student_info_sub::C_nick         => $nick,
                \App\Models\t_seller_student_info_sub::C_phone        => $phone,
                \App\Models\t_seller_student_info_sub::C_grade        => $grade,
                \App\Models\t_seller_student_info_sub::C_origin       => $origin,
                \App\Models\t_seller_student_info_sub::C_subject      => $subject,
                \App\Models\t_seller_student_info_sub::C_has_pad      => $has_pad,
                \App\Models\t_seller_student_info_sub::C_trial_type   => $trial_type,
                \App\Models\t_seller_student_info_sub::C_qq => $qq,
                \App\Models\t_seller_student_info_sub::C_admin_revisiterid  => $stu_row["admin_revisiterid"],

            ]);
        }else{
            $admin_assign_time=0;
            if ($admin_revisiterid  ) {
                $admin_assign_time=time(NULL);
            }

            return $this->row_insert([
                \App\Models\t_seller_student_info::C_add_time => $add_time,
                \App\Models\t_seller_student_info::C_nick         => $nick,
                \App\Models\t_seller_student_info::C_phone        => $phone,
                \App\Models\t_seller_student_info::C_grade        => $grade,
                \App\Models\t_seller_student_info::C_origin       => $origin,
                \App\Models\t_seller_student_info::C_subject      => $subject,
                \App\Models\t_seller_student_info::C_has_pad      => $has_pad,
                \App\Models\t_seller_student_info::C_trial_type   => $trial_type,
                \App\Models\t_seller_student_info::C_qq => $qq,
                \App\Models\t_seller_student_info::C_user_desc => $user_desc,
                \App\Models\t_seller_student_info::C_admin_revisiterid => $admin_revisiterid ,
                \App\Models\t_seller_student_info::C_admin_assign_time => $admin_assign_time ,
                \App\Models\t_seller_student_info::C_st_application_time    => $st_application_time,
                \App\Models\t_seller_student_info::C_st_application_nick    => $st_application_nick,
                \App\Models\t_seller_student_info::C_st_demand   => $st_demand,
                \App\Models\t_seller_student_info::C_status  => $status,
                "ass_adminid" =>$ass_adminid,
                "seller_resource_type" => $seller_resource_type,
                "userid" => $userid,
            ],false,true);
        }
    }

    function get_seller_count( $opt_date_str , $start_time,$end_time,$origin_ex,$groupid="") {

        $where_arr=[];
        if(!empty($groupid) && $groupid != -1){
            $where[]= ["groupid = %d" ,$groupid,-1 ];
            $sql=$this->gen_sql_new("select adminid from %s where %s",
                                    t_admin_group_user::DB_TABLE_NAME,
                                    $where);
            $ret =  $this->main_get_list($sql);
            $str = '';
            foreach ($ret as $item){
                $str .= $item['adminid'].",";
            }
            $str = "(".rtrim($str,',').")";
            $where_arr[] = "admin_revisiterid in ".$str;
        }


        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"origin");
        $where_arr[]= ["$opt_date_str>=%d" ,  $start_time,-1 ];
        $where_arr[]= ["$opt_date_str<=%d" ,  $end_time,-1 ];
        $where_arr[]= $ret_in_str ;


        $sql=$this->gen_sql_new("select admin_revisiterid,   count(*) as all_count, sum(seller_resource_type=0 ) as all_count_0, sum(seller_resource_type=1 ) as all_count_1, sum(status=0 ) as no_call, sum(  status=0 and seller_resource_type =0  ) as no_call_0, sum(  status=0 and seller_resource_type=1  ) as no_call_1, sum(status=1) as invalid_count, sum(status=2) as no_connect   , sum(status<>0) as call_count,  sum(status in (6,7,8,9,10,12,13,14,20,21)) as reqiure_test_count  ,sum(st_arrange_lessonid>0) as test_lesson_count, sum(status=8) as order_count".
                            " from %s where  %s and admin_revisiterid>0  group by admin_revisiterid  ", self::DB_TABLE_NAME, $where_arr );
        return $this->main_get_list_as_page($sql);
    }

    function get_channel_statistics( $start_time,$end_time,$origin,$origin_ex, $admin_revisiterid=-1,$adminid_list=""){

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"origin");
        $where_arr=[
            ["origin like '%%%s%%'", $origin, "" ] ,
            ["admin_revisiterid=%u" ,$admin_revisiterid,-1],
            $ret_in_str,
        ];

        if(!empty($adminid_list)){
            if($adminid_list == "()"){
                $where_arr[] = "admin_revisiterid = -100";
            }else{
                $where_arr[] = "admin_revisiterid in ".$adminid_list;
            }
        }

        $sql=$this->gen_sql("select origin, count(origin) as al_count, sum(admin_revisiterid>0) as revisited_yi,sum( tq_called_flag=0 ) as revisited_wei, sum(status=1) as revisited_wuxiao, sum(status=2 and admin_assign_time>0) as no_call,sum(status=3) as effective_a,sum(status=4) as effective_b,sum(status=5) as effective_c,sum(status=6) as listened_dai,sum(status=7) as listened_wei,sum(status=8) as listened_yi,sum(status=9) as reservation, sum(status=10) as revisited_yipai, sum( tq_called_flag >0   ) as revisited_yhf,sum(status=11) as listen_dai, sum(status=12) as listen_que, sum(status=13) as listen_cannot, sum(status=14)  as listen_refuse , sum(money_all ) as money_all ,sum(first_money) as first_money ".
                            " from %s where  add_time >=%u and add_time<=%u and %s group by origin  ",
                            self::DB_TABLE_NAME,
                            $start_time,
                            $end_time,
                            [$this->where_str_gen($where_arr)]
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item["origin"];
        });
    }
    public function get_channel_statistics_2( $start_time,$end_time,$origin,$origin_ex ,$admin_revisiterid,$adminid_list="") {
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"origin");
        $where_arr=[
            ["origin like '%%%s%%'", $origin, "" ] ,
            ["admin_revisiterid=%d" ,  $admin_revisiterid ,-1 ],
            $ret_in_str,
        ];
        if(!empty($adminid_list)){
            if($adminid_list == "()"){
                $where_arr[] = "admin_revisiterid = -100";
            }else{
                $where_arr[] = "admin_revisiterid in ".$adminid_list;
            }
        }

        $sql=$this->gen_sql("select grade,subject,has_pad,phone_location ".
                            " from %s where  add_time >=%u and add_time<=%u and %s ",
                            self::DB_TABLE_NAME,
                            $start_time,
                            $end_time,
                            [$this->where_str_gen($where_arr)]);

        return  $this->main_get_list($sql);
    }

    function get_channel_summary( $page_num,$start_time,$end_time,$origin ,$origin_ex="") {
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"origin");
        $where_arr=[
            ["origin like '%%%s%%'", $origin, "" ] ,
            $ret_in_str,
        ];

        $sql=$this->gen_sql("select origin, count(origin) as all_count,sum(admin_assign_time>0)  admin_assign_count,  sum(status=0) as no_call,sum(status=1) as invalid_count,sum(status=2) as not_connect,sum(status=3) as effective_a,sum(status=4) as effective_b,sum(status=5) as effective_c,sum(status=6) as listened_dai,sum(status=7) as listened_wei,sum(status=8) as listened_yi,sum(status=10) as reservation,sum(status=14) as reject ".
                            "from %s where  add_time >=%u and add_time<=%u and %s group by origin  ",
                            self::DB_TABLE_NAME,
                            $start_time,
                            $end_time,
                            [$this->where_str_gen($where_arr)]
        );
        return $this->main_get_list_as_page($sql);
    }


    public function update_student_news($grade,$subject,$pad,$nick,$old_phone,$from_type,$user_desc=""){

        $sql=$this->gen_sql( " update %s set grade = %u ,subject = %u ,has_pad = %u ,nick= '%s',user_desc= '%s',from_type =%u ".
                        "where phone = '%s'",
                             self::DB_TABLE_NAME,
                             $grade,
                             $subject,
                             $pad,
                             $nick,
                             $user_desc,
                             $from_type,
                             $old_phone
        );
        $this->main_update( $sql  );
    }

    //获取浏览的用户信息
    public function get_show_student_info($phone){
        $this->switch_readonly_database();
        $sql=sprintf("select * from %s where phone   = '%s'",
                     self::DB_TABLE_NAME,
                     $phone
        );

        return $this->main_get_row($sql);
    }



    public function update_test_lesson_info($phone , $st_application_nick , $st_class_time  , $st_from_school , $st_demand)
    {
        //update time
        $sql=$this->gen_sql("update %s set st_application_time=%u "
                            ."  where phone='%s'  ",
                            self::DB_TABLE_NAME,
                            time(NULL),
                            $phone
        );
        $this->main_update($sql);

        $this->field_update_list($phone,[
            self::C_st_class_time  => $st_class_time,
            self::C_st_from_school => $st_from_school,
            self::C_st_demand      => $st_demand,
            self::C_st_application_nick      => $st_application_nick,
            self::C_status     => \App\Enums\Ebook_status::V_TEST_LESSON_REPORT,
        ]);

    }

    public function add_test_user( $st_application_nick,$phone) {
        return $this->row_insert([
            self::C_status              => \App\Enums\Ebook_status::V_TEST_LESSON_REPORT,
            self::C_st_application_nick => $st_application_nick ,
            self::C_phone               => $phone ,
            self::C_st_application_time => time(NULL),
            self::C_add_time            => time(NULL),
            self::C_origin              => "助教添加",
            self::C_from_type           => 1 ,
            self::C_admin_revisiterid   => 1,
        ]);
    }

    public function get_seller_info($telphone)
    {
        $sql=$this->gen_sql("select admin_revisiterid from %s where phone = '%s'",
                     self::DB_TABLE_NAME,
                     $telphone
        );
        return $this->main_get_value($sql);
    }
    public function set_money_all($telphone,$money_all, $first_money) {
        //已试听-已签
        //const V_8=8;
        $sql=$this->gen_sql("update %s set money_all=%u, first_money=%u".
                            " where phone like '%s%%' and status=%u and from_type=0 limit 1",
                            self::DB_TABLE_NAME,
                            $money_all,
                            $first_money,
                            $telphone,
                            \App\Enums\Ebook_status::V_8
        );
        return $this->main_update($sql);
    }

    public function get_origin_info($phone){
        $sql=$this->gen_sql("select origin,phone,status from  %s  where  phone like '%%%s%%' ",
                            self::DB_TABLE_NAME, $phone );
        return $this->main_get_list($sql);
    }

    public function get_test_lesson_info($id) {
        $sql=$this->gen_sql(" select  grade,subject,phone_location , user_desc, st_class_time, nick,userid, assigned_teacherid "
                            ." from  %s  where   id=%u  "
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_get_row($sql);
    }
    public function set_assinged_teacherid($id, $assigned_teacherid ) {

        $sql = $this->gen_sql(" update %s set assigned_teacherid=%u  where id = %u and assigned_teacherid =0 ",
                       self::DB_TABLE_NAME,
                       $assigned_teacherid,
                       $id);
        return $this->main_update($sql);

    }
    public function get_need_noti_list($start_time,$end_time ) {
        $sql = $this->gen_sql("select phone,origin,grade,subject,has_pad from %s".
                              " where add_time>=%u and add_time<%u and ".
                              " origin in ('61','91','AppStore','APP课程包','leo','wandoujia','xiaomi','yingyongbao','yingyonghui','onda','anzhi' )",
                              self::DB_TABLE_NAME,$start_time, $end_time);
        return $this->main_get_list($sql);
    }
    public function admin_revisiter_get_tongji_add_time($start_time,$end_time,$admin_revisiterid)  {

        $where_arr=[
            ["admin_revisiterid=%u",$admin_revisiterid,-1]
        ];
        $sql = $this->gen_sql_new("select admin_revisiterid ,count(*) as count from %s".
                              " where add_time>=%u and add_time<%u and %s and admin_revisiterid>0 group by admin_revisiterid  ",
                              self::DB_TABLE_NAME,$start_time, $end_time , $where_arr );
        return $this->main_get_list($sql);
    }


    public function get_tongji_add_time($start_time,$end_time,$admin_revisiterid)  {

        $where_arr=[
            ["admin_revisiterid=%u",$admin_revisiterid,-1]
        ];
        $sql = $this->gen_sql_new("select add_time as opt_time from %s".
                              " where add_time>=%u and add_time<%u and %s",
                              self::DB_TABLE_NAME,$start_time, $end_time , $where_arr );
        return $this->main_get_list($sql);
    }
    public function get_tongji_first_revisite_time($start_time,$end_time,$admin_revisiterid)  {

        $where_arr=[
            ["admin_revisiterid=%u",$admin_revisiterid,-1]
        ];

        $sql = $this->gen_sql_new("select first_revisite_time as opt_time, add_time from %s ".
                              " where first_revisite_time >=%u and first_revisite_time <%u and %s ",
                              self::DB_TABLE_NAME,$start_time, $end_time, $where_arr);
        return $this->main_get_list($sql);
    }

    public function admin_revisiter_get_tongji_first_revisite_time($start_time,$end_time,$admin_revisiterid)  {

        $where_arr=[
            ["admin_revisiterid=%u",$admin_revisiterid,-1]
        ];

        $sql = $this->gen_sql_new("select  admin_revisiterid, count(*) count, sum( first_revisite_time - add_time < 86400  )  after_24_count from %s ".
                              " where first_revisite_time >=%u and first_revisite_time <%u and %s group by  admin_revisiterid ",
                              self::DB_TABLE_NAME,$start_time, $end_time, $where_arr);
        return $this->main_get_list($sql);
    }


    public function tongji_last_revisite_time($start_time,$end_time)  {

        $sql = $this->gen_sql("select  admin_revisiterid as id, count(*) as count from %s ".
                              " where last_revisit_time >=%u and  last_revisit_time <%u group by admin_revisiterid  ",
                              self::DB_TABLE_NAME,$start_time, $end_time);
        return $this->main_get_list($sql);
    }


    public function get_notify_lesson_info( $admin_revisiterid ) {
        $now=time(NULL);
        $notify_lesson_check_end_time=strtotime(date("Y-m-d",$now+86400*2 ) );
        //试听-已排课 :10
        $where_arr=[
            "status in(10, 12)",
            ["lesson_start>=%u", $now-3600 ,-1 ],
            ["lesson_start<%u",$notify_lesson_check_end_time,-1 ],
            ["admin_revisiterid=%u",$admin_revisiterid,-1 ],
        ];
        $next_day=$notify_lesson_check_end_time-86400;

        $sql = $this->gen_sql_new("select  lesson_start, notify_lesson_day1,notify_lesson_day2  from %s t1 left join %s t2 on t1.st_arrange_lessonid=t2.lessonid where  %s  "
                                  ,
                              self::DB_TABLE_NAME,
                              t_lesson_info::DB_TABLE_NAME,
                              $where_arr);

        $list=$this->main_get_list($sql);
        $today=0;
        $tomorrow=0;
        foreach ($list as $item) {
            $lesson_start=$item["lesson_start"];
            $notify_lesson_day1=$item["notify_lesson_day1"];
            $notify_lesson_day2=$item["notify_lesson_day2"];
            if ( $lesson_start<$next_day && $notify_lesson_day1 ==0  ) { // 今天的课
                $today++;
            }
            if ( $lesson_start>=$next_day && $notify_lesson_day2 ==0  ) { // 明天的课
                $tomorrow++;
            }
        }
        return  ["today"=>$today,"tomorrow"=>$tomorrow ] ;
    }
    public function set_notify_lesson_flag($phone,$notify_flag) {
        $now=time(NULL);
        $notify_lesson_check_end_time=strtotime(date("Y-m-d",$now+86400*2 ) );
        //试听-已排课 :10
        $where_arr=[
            "status in (10, 12)",
            ["lesson_start>=%u", $now-3600 ,-1 ],
            ["lesson_start<%u",$notify_lesson_check_end_time,-1 ],
            ["phone='%s'",$phone,"" ],
        ];
        $next_day=$notify_lesson_check_end_time-86400;

        $sql = $this->gen_sql_new("select  lesson_start, notify_lesson_day1,notify_lesson_day2  from %s t1 left join %s t2 on t1.st_arrange_lessonid=t2.lessonid where  %s  "
                                  ,
                              self::DB_TABLE_NAME,
                              t_lesson_info::DB_TABLE_NAME,
                              $where_arr);

        $item=$this->main_get_row($sql);
        if($item) {
            $lesson_start=$item["lesson_start"];
            $notify_lesson_day1=$item["notify_lesson_day1"];
            $notify_lesson_day2=$item["notify_lesson_day2"];
            $update_field_name="";
            if ( $lesson_start<$next_day  ) { // 今天的课
                $update_field_name="notify_lesson_day1";
            }
            if ( $lesson_start>=$next_day   ) { // 明天的课
                $update_field_name="notify_lesson_day2";
            }
            $value=0;
            if ($notify_flag) {
                $value=time(NULL);
            }
            $this->field_update_list($phone,[
                $update_field_name => $value,
            ]);

        }


    }

    public function reset_status_and_log( $phone, $status ,$admin_account )   {

        $old_status=$this->get_status($phone);
        $arr=explode("-", $phone);
        $real_phone=$arr[0];

        if ($old_status != $status){
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $real_phone
                , sprintf("操作者: $admin_account 状态: %s=>%s",
                          E\Ebook_status::get_desc($old_status),
                          E\Ebook_status::get_desc( E\Ebook_status::V_TEST_LESSON_SET_LESSON)), "system");
            $this->field_update_list($phone,[
                "status"  => $status,
            ]) ;
        }

    }
    public function reset_origin_and_log($phone, $origin, $admin_account ) {

        $old_origin=$this->get_origin($phone);
        $arr=explode("-", $phone);
        $real_phone=$arr[0];

        if ($old_origin!= $origin){
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $real_phone
                , sprintf("操作者: $admin_account 渠道: %s=>%s",
                          $old_origin , $origin), "system");
            $this->field_update_list($phone,[
                "origin"  => $origin,
            ]) ;
        }

    }

    public function tongji_first_revisite_time($start_time,$end_time,$admin_revisiterid,$adminid_list="")  {
        $where_arr =[
           # ["admin_revisiterid=%u",$admin_revisiterid, -1] ,
            [" add_time>=%u",$start_time, -1] ,
            [" add_time<%u",$end_time, -1] ,
            [" first_revisite_time>=%u",$start_time, -1] ,
            [" first_revisite_time<%u",$end_time, -1] ,
            " FROM_UNIXTIME(first_revisite_time, '%Y-%m-%d') = FROM_UNIXTIME( add_time , '%Y-%m-%d') ",
        ];
        if(!empty($adminid_list)){
            if($adminid_list == "()"){
                $where_arr[] = "admin_revisiterid = -100";
            }else{
                $where_arr[] = "admin_revisiterid in ".$adminid_list;
            }
        }
        $sql = $this->gen_sql_new("select count(*) user_count,"
                                  ." avg(first_revisite_time-add_time )/60 as avg_call_interval , admin_revisiterid "
                                  ." from %s "
                                  ." where  %s "
                                  ." group by admin_revisiterid  "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

    public function get_stu_performance_for_seller($lessonid){
        $sql=$this->gen_sql("select stu_lesson_content,stu_lesson_status,stu_study_status,stu_advantages,"
                            ." stu_disadvantages,stu_lesson_plan,stu_teaching_direction,stu_advice"
                            ." from %s "
                            ." where st_arrange_lessonid=%u"
                            //." and status=10"
                            ,self::DB_TABLE_NAME
                            ,$lessonid
        );
        return $this->main_get_row($sql);
    }

    public function set_no_connect_for_sync_tq ( $phone ) {
        $sql=$this->gen_sql_new("update %s set status=2  "
                                ." where status=0 and  phone like '%s%%' ",
                                self::DB_TABLE_NAME,
                                $phone );
        return $this->main_update($sql);
    }

    public function set_tq_called_flag_for_sync_tq ( $phone,$called_flag ) {
        $sql=$this->gen_sql_new("update %s set tq_called_flag=%u  "
                                ." where phone like '%s%%' and tq_called_flag< %u ",
                                self::DB_TABLE_NAME, $called_flag,
                                $phone,$called_flag );
        return $this->main_update($sql);
    }


    public function get_no_called_list($page_num,$adminid ,$grade, $has_pad, $subject,$origin) {
        $where_arr=[
            ["grade=%u", $grade, -1 ],
            ["has_pad=%u", $has_pad, -1 ],
            ["subject=%u", $subject, -1 ],
            ["origin like '%%%s%%'", $origin, ""],
        ];
        $sql = $this->gen_sql_new(
            "select add_time, phone, phone_location, grade, subject,has_pad,origin from %s where  admin_assign_time <%u and admin_assign_time  > %u and admin_revisiterid<>%u  and status in (0,2)  and %s order by rand() desc ",
            self::DB_TABLE_NAME,  time(NULL)-4*86400, time(NULL)-60*86400 , $adminid, $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);
    }
    public function get_no_call_count( $adminid ) {
        $start_time=strtotime(date("Y-m-d", time(NULL)-30*86400) );
        $sql = $this->gen_sql_new("select count(*) from %s where   admin_assign_time  > %u and admin_revisiterid=%u  and status =0  ",
            self::DB_TABLE_NAME,  $start_time , $adminid );
        return $this->main_get_value($sql);
    }

    public  function first_revisite_time_tongji($start_time,$end_time) {
        $sql=$this->gen_sql_new("select from_unixtime(first_revisite_time ,'%%k' )*1 as hourid, count(*) as count  from %s "
                                ." where first_revisite_time>=%u and first_revisite_time<%u group by hourid order by hourid asc  ",
                                self::DB_TABLE_NAME, $start_time,$end_time) ;
        return $this->main_get_list_as_page($sql);
    }

    public function  get_user_init_info($phone){
        $where_arr=[
            ["phone like \"%s%%\" ", $phone ,"" ] ,
        ];
        $sql=$this->gen_sql_new(
            "select max(stu_score_info) as stu_score_info,"
            ." max( stu_character_info) as stu_character_info,"
            ." max( user_desc) as user_desc "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function ass_get_list( $page_num ,$start_time,$end_time ,$ass_adminid )
    {
        $where_arr = [
            ["ass_adminid=%d", $ass_adminid,-1 ] ,
            "ass_adminid>0",
        ];

        $order_str= "add_time";
        $where_arr[]= ["$order_str>=%d" ,  $start_time,-1 ];
        $where_arr[]= ["$order_str<=%d" ,  $end_time,-1 ];

        $sql = $this->gen_sql_new("select t1.phone, ass_adminid ,  t1.userid, notify_lesson_day1, notify_lesson_day2, t1.money_all,last_revisit_time, next_revisit_time, st_application_time, t1.phone_location,id, add_time, t1.origin, t1.nick, t1.status, user_desc, t1.grade, t1.subject, has_pad, admin_revisiterid, admin_assign_time, last_revisit_msg, t2.teacherid, t2.lesson_start, t2.lesson_end ,st_application_time,first_revisite_time  ,t1.st_arrange_lessonid ,origin_userid  from %s t1 "
                                  ." left join %s t2 on t1.st_arrange_lessonid=t2.lessonid "
                                  ." left join %s s on t1.userid=s.userid"
                                  ." where  %s "
                                  ."order by %s desc ",
                              self::DB_TABLE_NAME,
                              t_lesson_info::DB_TABLE_NAME,
                              t_student_info::DB_TABLE_NAME,
                              $where_arr, $order_str);
        $ret_info= $this->main_get_list_by_page($sql,$page_num);

        return  $this->reset_phone_location($ret_info);
    }

    public function get_seller_student_info($phone){
        $sql=$this->gen_sql_new("select userid,phone,subject,grade,st_class_time,stu_request_test_lesson_time_info "
                                ." from %s"
                                ." where phone='%s'"
                                ,self::DB_TABLE_NAME
                                ,$phone
        );
        return $this->main_get_row($sql);
    }
    public function tongji_require_count($start_time,$end_time) {
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr,"st_application_time",$start_time,$end_time);
        $where_arr[]="status in ( ".$this->test_lesson_status_list_str ." )";
        $where_arr[]=" admin_revisiterid >1  ";
        $sql = $this->gen_sql_new(
            "select admin_revisiterid  as adminid ,count(*) as value from  %s where %s group by  admin_revisiterid order by value desc ",
            self::DB_TABLE_NAME ,$where_arr );
        return $this->main_get_list($sql);
    }

    public function tongji_test_lesson_succ_count($start_time,$end_time) {
        $where_arr=[
            "confirm_flag in (0,1)" ,
            " admin_revisiterid >1  ",
        ];
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select admin_revisiterid  as adminid ,count(*) as value  from  %s  ss  left join %s l on  ss.st_arrange_lessonid = l.lessonid where  %s "
            ." group by  admin_revisiterid order by value desc ",
            self::DB_TABLE_NAME ,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_list($sql);
    }

    public function tongji_test_lesson_count($start_time,$end_time) {
        $where_arr=[
        ];
        $this->where_arr_add_time_range($where_arr,"cancel_lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new(
            "select admin_revisiterid  as adminid ,count(*) as value  from  %s where %s ss  left join %s l on  ss.st_arrange_lessonid = l.lessonid "
            ." group by  admin_revisiterid order by value desc ",
            self::DB_TABLE_NAME ,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr );
        return $this->main_get_list($sql);
    }

    public function get_lesson_admin($lessonid){
        $sql=$this->gen_sql_new("select account "
                                ." from %s s"
                                ." left join %s a on a.id=admin_revisiterid"
                                ." where st_arrange_lessonid=%u"
                                ,self::DB_TABLE_NAME
                                ,t_admin_users::DB_TABLE_NAME
                                ,$lessonid
        );
        return $this->main_get_value($sql);
    }



    public function get_no_pad_list(){
        $sql=$this->gen_sql_new("select s1.phone,o.order_time "
                                ." from %s s1 "
                                ." left join %s s on s1.phone=s.phone"
                                ." left join %s o on o.userid=s.userid"
                                ." where s1.has_pad in (0,10)"
                                ." and contract_type=0 "
                                ." and contract_status in (1,2)"
                                ." and is_test_user=0"
                                ." group by s1.phone"
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function change_download_time($lessonid){
        $sql = $this->gen_sql_new("update %s set tea_download_paper_time=%u where st_arrange_lessonid=%u"
                                  ,self::DB_TABLE_NAME
                                  ,time()
                                  ,$lessonid
        );
        return $this->main_get_list($sql);
    }

}
