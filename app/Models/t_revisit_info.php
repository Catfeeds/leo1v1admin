<?php
namespace App\Models;
class t_revisit_info extends \App\Models\Zgen\z_t_revisit_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_yuedu_time($userid)
    {
        $sql=$this->gen_sql("select max(a.revisit_time) from %s a left join %s b on a.userid = b.userid where a.userid = %u and revisit_type = 2 and b.type=0",
                            self::DB_TABLE_NAME,
                            t_student_info::DB_TABLE_NAME,
                            $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_last_revisit($userid)
    {
        $sql = sprintf("select revisit_time , operator_note ,sys_operator  ".
                       " from %s where userid = %u  order by revisit_time desc limit 1",
                       self::DB_TABLE_NAME,$userid);
        return $this->main_get_row($sql);
    }

    public function get_max_revisit_time($userid)
    {
        $sql = sprintf("select revisit_time ".
                       " from %s where userid = %u and revisit_type=0 order by revisit_time desc limit 1",
                       self::DB_TABLE_NAME,$userid);
        return $this->main_get_value($sql);
    }

    public function get_max_revisit_time_list($userid)
    {
        $where_arr= [
            ["r.userid= %u",$userid,-1]
        ];
        $sql = $this->gen_sql_new("select max(r.revisit_time) time,r.userid ".
                                  " from %s r left join %s s on r.userid = s.userid where %s and s.type=0 and revisit_type=0 group by r.userid ",
                                  self::DB_TABLE_NAME,t_student_info::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }


    public function get_first_revisit($userid)
    {
        $sql = sprintf("select revisit_time , operator_note ,sys_operator  ".
                       " from %s where userid = %u and revisit_type = 1 order by revisit_time desc limit 1 ",
                       self::DB_TABLE_NAME,$userid);
        return $this->main_get_row($sql);
    }


    public function get_all_revisit($userid)
    {
        $sql = sprintf("select userid, revisit_type, from_unixtime( revisit_time ) as revisit_time, stu_nick, revisit_person, ".
                       " sys_operator, operator_note, operator_audio from %s where userid = %u order by revisit_time desc",
                       self::DB_TABLE_NAME, $userid);
        return $this->main_get_list($sql);
    }

    public function get_all_revisits($start,$end)
    {
        $sql = sprintf("select userid, revisit_type, from_unixtime( revisit_time ) as revisit_time, stu_nick, revisit_person, ".
                       " sys_operator, operator_note, operator_audio from %s where revisit_time > %u and revisit_time < %u order by userid desc",
                       self::DB_TABLE_NAME, $start, $end);
        return $this->main_get_list($sql);
    }
    public function get_all_revisit_ex($userid)
    {
        $sql = sprintf("select userid, revisit_type, revisit_time ,revisit_path, stu_nick, revisit_person, ".
                       " sys_operator, operator_note, operator_audio from %s where userid = %u order by revisit_time desc limit 10",
                       self::DB_TABLE_NAME , $userid);
        return $this->main_get_list($sql);
    }

    public function get_all_revisit_limit_list($userid)
    {
        $sql = sprintf("select * from %s where userid = %u order by revisit_time desc limit 10",
                       self::DB_TABLE_NAME , $userid);
        return $this->main_get_list($sql);
    }


    public function sys_log( $account, $userid ,  $msg ) {
        return $this->add_revisit_record($userid, time(NULL),"", 0, $account ,  $msg, 10 ) ;
    }

    public function add_revisit_record(
        $userid,$revisit_time,$stu_nick,$revisit_person,$sys_operator,
        $operator_note,$revisit_type, $call_phone_id=NULL,$operation_satisfy_flag=0,$operation_satisfy_type=0,
        $record_tea_class_flag=0,$tea_content_satisfy_flag=0,$tea_content_satisfy_type=0,$operation_satisfy_info="",$child_performance="",
        $tea_content_satisfy_info="",$other_parent_info="",$other_warning_info="",$child_class_performance_flag=0,$child_class_performance_info="",
        $child_class_performance_type=0,$school_work_change_flag=0,$school_score_change_flag=0,$school_work_change_info="",$school_work_change_type=0,
        $school_score_change_info="",$is_warning_flag=0
    ){
        return  $ret= $this->row_insert([
            "userid"         => $userid,
            "revisit_time"   => $revisit_time,
            "stu_nick"       => $stu_nick,
            "revisit_person" => $revisit_person,
            "sys_operator"   => $sys_operator,
            "operator_note"  => $operator_note,
            "revisit_type"   => $revisit_type,
            "call_phone_id"   => $call_phone_id,
            "operation_satisfy_flag" => $operation_satisfy_flag,
            "operation_satisfy_type" => $operation_satisfy_type,
            "record_tea_class_flag" => $record_tea_class_flag,
            "tea_content_satisfy_flag" => $tea_content_satisfy_flag,
            "tea_content_satisfy_type" => $tea_content_satisfy_type,
            "operation_satisfy_info" => $operation_satisfy_info,
            "child_performance" => $child_performance,
            "tea_content_satisfy_info" => $tea_content_satisfy_info,
            "other_parent_info" => $other_parent_info,
            "other_warning_info" => $other_warning_info,
            "child_class_performance_flag"=>$child_class_performance_flag,
            "child_class_performance_type"=>$child_class_performance_type,
            "child_class_performance_info"=>$child_class_performance_info,
            "school_score_change_flag" =>$school_score_change_flag,
            "school_score_change_info" =>$school_score_change_info,
            "school_work_change_flag" =>$school_work_change_flag,
            "school_work_change_type" =>$school_work_change_type,
            "school_work_change_info" =>$school_work_change_info,
            "is_warning_flag"         =>$is_warning_flag

        ],false, true, true);
    }

    public function check_add_existed($userid,$revisit_time) {
        $sql=$this->gen_sql_new("select 1 from %s where userid=%u and revisit_time=%u"
                                ,self::DB_TABLE_NAME
                                ,$userid
                                ,$revisit_time
        );
        return $this->main_get_row($sql);
    }

    public function get_revisit_list($page_num,$userid,$is_warning_flag=-1){
        $where_arr=[
            ["userid=%u",$userid, -1] ,
            ["is_warning_flag=%u",$is_warning_flag,-1]
        ];

        $sql=$this->gen_sql_new("select userid, revisit_time,revisit_person,operator_note,operator_audio,sys_operator, call_phone_id, duration,  record_url, c.phone ,revisit_type,operation_satisfy_flag ,operation_satisfy_type,operation_satisfy_info,record_tea_class_flag,child_performance,tea_content_satisfy_flag ,tea_content_satisfy_type,tea_content_satisfy_info,other_parent_info,child_class_performance_flag ,child_class_performance_type,child_class_performance_info,school_score_change_flag ,school_score_change_info,school_work_change_flag ,school_work_change_type,school_work_change_info,other_warning_info,is_warning_flag ,warning_deal_url ,warning_deal_info,m.uid,parent_guidance_except,other_subject_info,tutorial_subject_info,recover_time,revisit_path,recent_learn_info,information_confirm  "
                                ."from %s r "
                                ." left join  %s c on  c.id= r.call_phone_id   "
                                ." left join %s m on r.sys_operator = m.account"
                                . " where  %s  order by revisit_time desc ",
                                self::DB_TABLE_NAME,
                                t_tq_call_info::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function get_ass_revisit_warning_info($start_time,$end_time,$page_num,$is_warning_flag,$ass_adminid,$require_adminid_list){
        $where_arr=[
            ["is_warning_flag=%u",$is_warning_flag,-1],
            ["m.uid= %u",$ass_adminid,-1]
        ];
        $this->where_arr_adminid_in_list($where_arr,"m.uid", $require_adminid_list );
        $this->where_arr_add_time_range($where_arr,"r.revisit_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select r.revisit_time,revisit_person,r.operator_note,operator_audio,sys_operator,revisit_type,operation_satisfy_flag ,operation_satisfy_type,operation_satisfy_info,record_tea_class_flag,child_performance,tea_content_satisfy_flag ,tea_content_satisfy_type,tea_content_satisfy_info,other_parent_info,child_class_performance_flag ,child_class_performance_type,child_class_performance_info,school_score_change_flag ,school_score_change_info,school_work_change_flag ,school_work_change_type,school_work_change_info,other_warning_info,is_warning_flag ,warning_deal_url ,warning_deal_info,s.nick,r.userid "
                                  ."from %s r left join %s m on m.account = r.sys_operator "
                                  ." left join %s s on r.userid = s.userid"
                                  ." where %s order by r.revisit_time desc",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_ass_revisit_warning_info_new($start_time,$end_time,$page_num,$is_warning_flag,$ass_adminid,$require_adminid_list,$revisit_warning_type,$uid_str){

        $one   = time();
        $two   = $one - 86400*5;
        $three = $one - 86400*7;
        if ($revisit_warning_type == 1) {
            $where_arr = [
                "is_warning_flag=1",
                ["m.uid= %u",$ass_adminid,-1],
                "r.revisit_time>=$two",
                "r.revisit_type=0",
            ];
        } else if ($revisit_warning_type == 2){
            $where_arr = [
                "is_warning_flag=1",
                ["m.uid= %u",$ass_adminid,-1],
                "r.revisit_time<$two",
                "r.revisit_time>=$three",
                "r.revisit_type=0",
            ];

        } else if ($revisit_warning_type == 3){

            $where_arr = [
                ["m.uid= %u",$ass_adminid,-1],
                "wo.deal_type<>1",
            ];

            if ($uid_str != -1 && $uid_str !== null ) {
                $where_arr[] = "m.uid in ($uid_str)";
            }

            $this->where_arr_adminid_in_list($where_arr,"m.uid", $require_adminid_list );
            $sql = $this->gen_sql_new(
                "select r.revisit_time,revisit_person,r.operator_note,operator_audio,r.sys_operator,revisit_type,operation_satisfy_flag ,operation_satisfy_type,operation_satisfy_info,record_tea_class_flag,child_performance,tea_content_satisfy_flag ,tea_content_satisfy_type,tea_content_satisfy_info,other_parent_info,child_class_performance_flag ,child_class_performance_type,child_class_performance_info,school_score_change_flag ,school_score_change_info,school_work_change_flag ,school_work_change_type,school_work_change_info,other_warning_info,is_warning_flag ,warning_deal_url ,warning_deal_info,s.nick,r.userid "
                ." from %s wo "
                ." left join %s r on r.userid=wo.userid and r.revisit_time=wo.revisit_time and r.sys_operator=wo.sys_operator "
                ." left join %s m on m.account = wo.sys_operator "
                ." left join %s s on wo.userid = s.userid"
                ." where %s order by r.revisit_time desc",
                t_revisit_warning_overtime_info::DB_TABLE_NAME,
                self::DB_TABLE_NAME,
                t_manager_info::DB_TABLE_NAME,
                t_student_info::DB_TABLE_NAME,
                $where_arr
            );

            return $this->main_get_list_by_page($sql,$page_num);

        } else {
            $where_arr = [
                ["is_warning_flag=%u",$is_warning_flag,-1],
                ["m.uid= %u",$ass_adminid,-1],
            ];
            $this->where_arr_add_time_range($where_arr,"r.revisit_time",$start_time,$end_time);
        }

        if ($uid_str != -1 && $uid_str !== null) {
            $where_arr[] = "m.uid in ($uid_str)";
        }

        $this->where_arr_adminid_in_list($where_arr,"m.uid", $require_adminid_list );
        $sql = $this->gen_sql_new(
            "select r.revisit_time,revisit_person,r.operator_note,operator_audio,sys_operator,revisit_type,operation_satisfy_flag ,operation_satisfy_type,operation_satisfy_info,record_tea_class_flag,child_performance,tea_content_satisfy_flag ,tea_content_satisfy_type,tea_content_satisfy_info,other_parent_info,child_class_performance_flag ,child_class_performance_type,child_class_performance_info,school_score_change_flag ,school_score_change_info,school_work_change_flag ,school_work_change_type,school_work_change_info,other_warning_info,is_warning_flag ,warning_deal_url ,warning_deal_info,s.nick,r.userid "
            ."from %s r left join %s m on m.account = r.sys_operator "
            ." left join %s s on r.userid = s.userid"
            ." where %s order by r.revisit_time desc",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_ass_revisit_warning_count($ass_adminid, $three,$uid_str=-1){
        $where_arr=[
            "r.is_warning_flag=1",
            "r.revisit_type=0",
            "r.revisit_time>=$three",
        ];

        if ($uid_str != -1 && $uid_str !== null) {
            $where_arr[] = "m.uid in ($uid_str)";
        } else {
            $where_arr[] = ["m.uid= %u",$ass_adminid,-1];
        }

        $sql = $this->gen_sql_new(
            "select r.revisit_time "
            ."from %s r left join %s m on m.account = r.sys_operator "
            ." where %s",
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_ass_revisit_info($userid,$end_time,$userid_list=[]){
        #$userid = 84727;
        $userid_in_str=$this->where_get_in_str("t.userid",$userid_list);
        $where_arr = [
            ["t.userid = %u",$userid,-1],
            "s.assistantid > 0",
            "(s.is_test_user = 0 or s.is_test_user is null)",
            "t.sys_operator <> 'system'",
            "t.sys_operator <> '系统'",
            $userid_in_str,
        ];

        $sql = $this->gen_sql_new("select t.userid,sum(if(  t.revisit_time > (%s -28*86400) ,1,0)) as week_first_all,sum(if( (%s- 21*86400)< t.revisit_time ,1,0)) as week_second_all,sum(if( (%s- 14*86400) < t.revisit_time,1,0)) as week_third_all,sum(if( (%s- 7*86400)<t.revisit_time,1,0)) as week_fourth from %s t left join %s s on t.userid = s.userid where %s and revisit_type in(0,2) group by t.userid order by t.userid desc ",
                                  $end_time,$end_time,$end_time,$end_time,self::DB_TABLE_NAME,t_student_info::DB_TABLE_NAME,$where_arr
        );
        $res =  $this->main_get_list($sql,function($item){
            return $item['userid'];
        });
        foreach($res as &$item){
            $item['week_third'] = $item['week_third_all'] -$item['week_fourth'];
            $item['week_second'] = $item['week_second_all'] -$item['week_third_all'];
            $item['week_first'] = $item['week_first_all'] -$item['week_second_all'];
        }

        return $res;
    }

    public function get_ass_revisit_info_new($userid,$start_time,$end_time,$userid_list=[]){
        #$userid = 84727;
        $userid_in_str=$this->where_get_in_str("t.userid",$userid_list,false);
        $where_arr = [
            ["t.userid = %u",$userid,-1],
            "s.assistantid > 0",
            "(s.is_test_user = 0 or s.is_test_user is null)",
            "t.sys_operator <> 'system'",
            "t.sys_operator <> '系统'",
            $userid_in_str,
        ];

        $this->where_arr_add_time_range($where_arr,"t.revisit_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.userid,count(*) num from %s t left join %s s on t.userid = s.userid where %s and revisit_type in(0,2) group by t.userid order by t.userid desc ",
                                  self::DB_TABLE_NAME,t_student_info::DB_TABLE_NAME,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['userid'];
        });
    }
    
    public function get_ass_revisit_info_personal($userid,$start_time,$end_time,$sys_operator,$revisit_type=-2){
        $where_arr = [
            ["userid = %u",$userid,-1],
            ["sys_operator='%s'",$sys_operator,""],
            // "revisit_type in (0,1,2,3,4,5)"
        ];
        if($revisit_type==-2){
            $where_arr[]="revisit_type in (0,1,2,3,4,5)";            
        }elseif($revisit_type==5){
            $where_arr[]=["revisit_type=%u",$revisit_type,-1];
            $where_arr[]="call_phone_id>0";
        }else{
            $where_arr[]=["revisit_type=%u",$revisit_type,-1]; 
        }

        $this->where_arr_add_time_range($where_arr,"revisit_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(1) from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }



    public function get_revisit_tongji_ass($start_time,$end_time,$require_adminid_list=[]){
        $where_arr=[
            ["revisit_time >=%u",$start_time,-1],
            ["revisit_time <=%u",$end_time,-1],
            "(sys_operator <> '系统' and sys_operator <> 'system' and sys_operator <> 'adrian')"
        ];
        $this->where_arr_adminid_in_list($where_arr,"m.uid", $require_adminid_list );
        $sql = $this->gen_sql_new("select sum(if(revisit_type=0,1,0)) xq_count,sum(if(revisit_type =1,1,0)) sc_count,sys_operator ".
                                  " from %s r join %s m on r.sys_operator = m.account ".
                                  " where %s and m.account_role = 1 group by sys_operator",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql);
    }
    public function rev_delete($userid,$revisit_time) {
        $sql=sprintf("delete from %s  where  userid =%s and revisit_time=%s ",
                     self::DB_TABLE_NAME,
                     $userid,
                     $revisit_time);
        return $this->main_update($sql);
    }

    public function get_rev_info_by_phone_adminid($userid){
        $where_arr=[];
        $where_arr[]="(sys_operator <> 'system' and sys_operator <>'系统')";
        $sql = $this->gen_sql_new("select revisit_time,operator_note,sys_operator from %s where %s and userid = %u ",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  $userid);
        return $this->main_get_list($sql);
    }

    public function get_ass_first_revisit_info($start_time,$end_time,$new_user_list=[],$flag=true){
        $where_arr = [
            "s.assistantid > 0",
            "(s.is_test_user = 0 or s.is_test_user is null)",
            "t.sys_operator <> 'system'",
            "t.sys_operator <> '系统'",
            "revisit_type=1"
        ];

        $where_arr[]=$this->where_get_in_str("t.userid",$new_user_list,$flag);
        $this->where_arr_add_time_range($where_arr,"t.revisit_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct t.userid) num,m.uid "
                                  ." from %s t left join %s s on t.userid = s.userid "
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s group by m.uid  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['uid'];
        });

    }

    public function get_ass_xq_revisit_info($start_time,$end_time,$userid_list=[],$flag=true){
        $where_arr = [
            "s.assistantid > 0",
            "(s.is_test_user = 0 or s.is_test_user is null)",
            "t.sys_operator <> 'system'",
            "t.sys_operator <> '系统'",
            "revisit_type in (0,2)"
        ];

        $where_arr[]=$this->where_get_in_str("t.userid",$userid_list,$flag);
        $this->where_arr_add_time_range($where_arr,"t.revisit_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct t.userid) num,m.uid "
                                  ." from %s t left join %s s on t.userid = s.userid "
                                  ." left join %s a on s.assistantid = a.assistantid"
                                  ." left join %s m on a.phone = m.phone"
                                  ." where %s group by m.uid  ",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['uid'];
        });

    }

    public function get_ass_xq_revisit_info_new($start_time,$end_time,$userid_list=[],$flag=true){
        $where_arr = [
            // "s.assistantid > 0",
            // "(s.is_test_user = 0 or s.is_test_user is null)",
            "t.sys_operator <> 'system'",
            "t.sys_operator <> '系统'",
            "revisit_type in (0,2,3)"
        ];

        $where_arr[]=$this->where_get_in_str("t.userid",$userid_list,$flag);
        $this->where_arr_add_time_range($where_arr,"t.revisit_time",$start_time,$end_time);
        $sql = $this->gen_sql_new("select count(distinct t.userid) num,m.uid "
                                  ." from %s t left join %s m on t.sys_operator = m.account "
                                  ." where %s group by m.uid  ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['uid'];
        });

    }
    public function add_revisit_record_b2($userid, $revisit_time, $stu_nick, $revisit_person, $sys_operator, $operator_note,$revisit_type, $call_phone_id  =NULL,$operation_satisfy_flag=0,$operation_satisfy_type=0,$record_tea_class_flag=0,$tea_content_satisfy_flag=0,$tea_content_satisfy_type=0,$operation_satisfy_info="",$child_performance="",$tea_content_satisfy_info="",$other_parent_info="",$other_warning_info="",$child_class_performance_flag=0,$child_class_performance_info="",$child_class_performance_type=0,$school_work_change_flag=0,$school_score_change_flag=0,$school_work_change_info="",$school_work_change_type=0,$school_score_change_info="",$is_warning_flag=0,$recover_time,$revisit_path,$information_confirm,$parent_guidance_except,$tutorial_subject_info,$other_subject_info,$recent_learn_info)
    {
        return  $ret= $this->row_insert([
            "userid"         => $userid,
            "revisit_time"   => $revisit_time,
            "stu_nick"       => $stu_nick,
            "revisit_person" => $revisit_person,
            "sys_operator"   => $sys_operator,
            "operator_note"  => $operator_note,
            "revisit_type"   => $revisit_type,
            "call_phone_id"   => $call_phone_id,
            "operation_satisfy_flag" => $operation_satisfy_flag,
            "operation_satisfy_type" => $operation_satisfy_type,
            "record_tea_class_flag" => $record_tea_class_flag,
            "tea_content_satisfy_flag" => $tea_content_satisfy_flag,
            "tea_content_satisfy_type" => $tea_content_satisfy_type,
            "operation_satisfy_info" => $operation_satisfy_info,
            "child_performance" => $child_performance,
            "tea_content_satisfy_info" => $tea_content_satisfy_info,
            "other_parent_info" => $other_parent_info,
            "other_warning_info" => $other_warning_info,
            "child_class_performance_flag"=>$child_class_performance_flag,
            "child_class_performance_type"=>$child_class_performance_type,
            "child_class_performance_info"=>$child_class_performance_info,
            "school_score_change_flag" =>$school_score_change_flag,
            "school_score_change_info" =>$school_score_change_info,
            "school_work_change_flag" =>$school_work_change_flag,
            "school_work_change_type" =>$school_work_change_type,
            "school_work_change_info" =>$school_work_change_info,
            "is_warning_flag"         =>$is_warning_flag,
            "recover_time"            =>$recover_time,
            "revisit_path"            =>$revisit_path,
            "information_confirm"     =>$information_confirm,
            "parent_guidance_except"  =>$parent_guidance_except,
            "tutorial_subject_info"   =>$tutorial_subject_info,
            "other_subject_info"      =>$other_subject_info,
            "recent_learn_info"       =>$recent_learn_info

        ],false, true, true);

    }


    public function get_revisit_by_revisit_time_userid($userid,$revisit_time){
        $where_arr=[
            ["userid=%u",$userid,-1],
            ["revisit_time=%u",$revisit_time,-1],
        ];
        $sql = $this->gen_sql_new("select *"
                                  ." from %s  "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        //        dd($sql);
        return $this->main_get_list($sql);
    }

    public function update_revisit_record_b2($userid, $revisit_time, $stu_nick, $revisit_person, $sys_operator, $operator_note,$revisit_type, $call_phone_id  =NULL,$operation_satisfy_flag=0,$operation_satisfy_type=0,$record_tea_class_flag=0,$tea_content_satisfy_flag=0,$tea_content_satisfy_type=0,$operation_satisfy_info="",$child_performance="",$tea_content_satisfy_info="",$other_parent_info="",$other_warning_info="",$child_class_performance_flag=0,$child_class_performance_info="",$child_class_performance_type=0,$school_work_change_flag=0,$school_score_change_flag=0,$school_work_change_info="",$school_work_change_type=0,$school_score_change_info="",$is_warning_flag=0,$recover_time,$revisit_path,$information_confirm,$parent_guidance_except,$tutorial_subject_info,$other_subject_info,$recent_learn_info)
    {
        return  $ret= $this->field_update_list_2($userid,$revisit_time,[
            "stu_nick"       => $stu_nick,
            "revisit_person" => $revisit_person,
            "sys_operator"   => $sys_operator,
            "operator_note"  => $operator_note,
            "revisit_type"   => $revisit_type,
            "call_phone_id"   => $call_phone_id,
            "operation_satisfy_flag" => $operation_satisfy_flag,
            "operation_satisfy_type" => $operation_satisfy_type,
            "record_tea_class_flag" => $record_tea_class_flag,
            "tea_content_satisfy_flag" => $tea_content_satisfy_flag,
            "tea_content_satisfy_type" => $tea_content_satisfy_type,
            "operation_satisfy_info" => $operation_satisfy_info,
            "child_performance" => $child_performance,
            "tea_content_satisfy_info" => $tea_content_satisfy_info,
            "other_parent_info" => $other_parent_info,
            "other_warning_info" => $other_warning_info,
            "child_class_performance_flag"=>$child_class_performance_flag,
            "child_class_performance_type"=>$child_class_performance_type,
            "child_class_performance_info"=>$child_class_performance_info,
            "school_score_change_flag" =>$school_score_change_flag,
            "school_score_change_info" =>$school_score_change_info,
            "school_work_change_flag" =>$school_work_change_flag,
            "school_work_change_type" =>$school_work_change_type,
            "school_work_change_info" =>$school_work_change_info,
            "is_warning_flag"         =>$is_warning_flag,
            "recover_time"            =>$recover_time,
            "revisit_path"            =>$revisit_path,
            "information_confirm"     =>$information_confirm,
            "parent_guidance_except"  =>$parent_guidance_except,
            "tutorial_subject_info"   =>$tutorial_subject_info,
            "other_subject_info"      =>$other_subject_info,
            "recent_learn_info"       =>$recent_learn_info

        ]);

    }

    public function get_overtime_by_now($start_time, $end_time){
        $where_arr = [
            "revisit_time >= $start_time",
            "revisit_time < $end_time",
            "revisit_type = 0",
            "is_warning_flag=1",
        ];
        $sql = $this->gen_sql_new("select userid,revisit_time,sys_operator"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_revisit_type0_per_minute($start_time, $end_time){
        $where_arr = [
            "r.revisit_time>=$start_time",
            "r.revisit_time<$end_time",
            "r.revisit_type=0",
            "r.sys_operator!='system'",
        ];

        $sql = $this->gen_sql_new(
            "select m.uid,r.userid,r.revisit_time as revisit_time1"
            ." from %s r"
            ." left join %s m on r.sys_operator=m.account"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_today_assess_info_by_uid($ass_adminid, $start_time,$end_time){
        $where_arr = [
            "r.revisit_time>=$start_time",
            "r.revisit_time<$end_time",
            "r.revisit_type=0",
            "m.uid = $ass_adminid",
            'm.del_flag = 0 ',
        ];
        $sql = $this->gen_sql_new(
            "select count(distinct s.userid) as stu_num,"
            ." count(distinct r.userid) as revisit_num"
            ." from %s r"
            ." left join %s m on m.account=r.sys_operator"
            ." left join %s a on a.phone=m.phone"
            ." left join %s s on s.assistantid=a.assistantid and s.is_test_user=0 and s.type=0"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,t_assistant_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_revisit_type6_per_minute($start_time, $revisit_time1,$uid,$userid,$id_str){
        $where_arr = [
            "r.revisit_time>=$start_time",
            "r.revisit_time<$revisit_time1",
            "r.revisit_type=6",
            "r.userid=$userid",
            "m.uid=$uid",
        ];
        if($id_str) {
            $where_arr[] = ['r.call_phone_id not in (%s)', $id_str, ''];
        }

        $sql = $this->gen_sql_new(
            "select r.revisit_time as revisit_time2,r.call_phone_id"
            ." from %s r"
            ." left join %s m on r.sys_operator=m.account"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_revisit_type6($start_time, $revisit_time1,$uid,$userid,$id_str){
        $where_arr = [
            "r.revisit_time>=$start_time",
            "r.revisit_time<$revisit_time1",
            "r.revisit_type=6",
            "r.userid=$userid",
            "r.call_phone_id>0",
            "m.uid=$uid",
        ];
        if($id_str) {
            $where_arr[] = ['r.call_phone_id not in (%s)', $id_str, ''];
        }

        $sql = $this->gen_sql_new(
            "select r.revisit_time as revisit_time2,r.call_phone_id"
            ." from %s r"
            ." left join %s m on r.sys_operator=m.account"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_warn_stu_list(){
        $where_arr=[
            "is_warning_flag=1",
            "revisit_type=0",
            "s.is_test_user=0"
        ] ;
        $sql = $this->gen_sql_new("select distinct r.userid "
                                  ." from %s r left join %s s on r.userid=s.userid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr);
        $arr= $this->main_get_list($sql);
        $list=[];
        foreach($arr as $val){
            $list[]=$val["userid"];
        }
        return $list;
    }

    public function get_all_info($start_time, $end_time) {
        $where_arr = [
            ["o.pay_time>=%u", $start_time, 0],
            ["o.pay_time<%u", $end_time, 0],
            "r.is_warning_flag in (1,2) "
        ];
        $sql = $this->gen_sql_new("select r.userid,r.is_warning_flag,r.revisit_time from %s r"
                                  ." left join %s o on r.userid=o.userid "
                                  ." where %s order by r.revisit_time desc"
                                  , self::DB_TABLE_NAME
                                  , t_order_info::DB_TABLE_NAME
                                  , $where_arr
        );
        return $this->main_get_list($sql, function($item) {
            return $item["userid"];
        });
    }

}
