<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use App\Http\Controllers\Controller;


class tea_manage_new extends Controller
{
    use CacheNick;
    use TeaPower;

    public function get_money_1v1_list() {
        $teacherid=$this->get_in_teacherid();
        $start_time=$this->get_in_start_time_from_str();
        $end_time=$this->get_in_end_time_from_str();

    }

    /**
     * 更改老师的工资类型
     * @param int teacherid
     * @param int teacher_money_type 更改后的老师工资类型
     * @param int level 更改后的老师等级
     * @param int start_time 更改后的老师等级
     */
    public function update_teacher_level() {
        $teacherid                 = $this->get_in_teacherid();
        $account                   = $this->get_account();
        $level                     = $this->get_in_int_val("level");
        $start_time                = $this->get_in_start_time_from_str();

        if($start_time>0){
            $check_start_time_flag = \App\Helper\Utils::check_teacher_salary_time($start_time);
            if(!$check_start_time_flag){
                return $this->output_err("重置课程开始时间过早，只能重置未结算工资的课程");
            }
        }

        $teacher_money_type = $this->get_in_int_val("teacher_money_type");
        if(!$this->check_account_in_arr(["jim","fly","adrian","low-key","jack"])){
            return $this->output_err("没有权限:". $account);
        }

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            'level'              => $level,
            'teacher_money_type' => $teacher_money_type,
        ]);

        if($start_time>0){
            $this->t_lesson_info->set_teacher_level_info_from_now($teacherid,$teacher_money_type,$level,$start_time);
        }
        return $this->output_succ();
    }

    public function update_is_good_flag(){
        $teacherid             = $this->get_in_teacherid();
        $is_good_flag           = $this->get_in_int_val('is_good_flag');
        $this->t_teacher_info->field_update_list($teacherid,[
            "is_good_flag"   =>$is_good_flag,
            "change_good_time" =>time()
        ]);
        return $this->output_succ();
    }
    public function update_teacher_lesson_num()  {
        $teacherid             = $this->get_in_teacherid();
        $limit_day_lesson_num           = $this->get_in_int_val('limit_day_lesson_num',4);
        $limit_week_lesson_num          = $this->get_in_int_val('limit_week_lesson_num',8);
        $limit_month_lesson_num         = $this->get_in_int_val('limit_month_lesson_num',30);
        $saturday_lesson_num         = $this->get_in_int_val('saturday_lesson_num',6);
        $seller_require_flag         = $this->get_in_int_val('seller_require_flag',0);
        $week_lesson_count        = $this->get_in_int_val('week_lesson_count',18);
        $week_limit_time_info  = $this->get_in_str_val('week_limit_time_info');
        $type       = $this->get_in_int_val('type',1);
        $old_week_num = $this->t_teacher_info->get_limit_week_lesson_num($teacherid);
        $old_week_lesson_count = $this->t_teacher_info->get_week_lesson_count($teacherid);
        $tea_nick = $this->cache_get_teacher_nick($teacherid);
        $account = $this->get_account();

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            'limit_day_lesson_num'  => $limit_day_lesson_num,
            'limit_week_lesson_num'  => $limit_week_lesson_num,
            'limit_month_lesson_num'  => $limit_month_lesson_num,
            'saturday_lesson_num'  => $saturday_lesson_num,
            "week_lesson_count"    => $week_lesson_count,
            "limit_seller_require_flag"=>$seller_require_flag
        ]);
        if($type==2){
            $this->t_teacher_info->field_update_list($teacherid,[
                "week_limit_time_info" => $week_limit_time_info
            ]);
        }
        if($ret){
            if($limit_week_lesson_num > $old_week_num){
                $this->t_manager_info->send_wx_todo_msg_by_adminid (72,"理优监课组","老师周排课数更改",$tea_nick."老师"."周排课数由".$old_week_num."节改为".$limit_week_lesson_num."节,操作人:".$account,"");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (448,"理优监课组","老师周排课数更改",$tea_nick."老师"."周排课数由".$old_week_num."节改为".$limit_week_lesson_num."节,操作人:".$account,"");


            }
            if($limit_week_lesson_num != $old_week_num){
                $this->t_teacher_record_list->row_insert([
                    "teacherid"          =>$teacherid,
                    "type"               =>7,
                    "add_time"           =>time(),
                    "acc"                =>$account,
                    "limit_week_lesson_num_new"  =>$limit_week_lesson_num,
                    "limit_week_lesson_num_old"  =>$old_week_num,
                    "seller_require_flag"        =>$seller_require_flag,
                    "record_info"        =>$tea_nick."老师"."周排课数由".$old_week_num."节改为".$limit_week_lesson_num."节"
                ]);
            }

            $old_week_lesson_count = $old_week_lesson_count/100;
            $week_lesson_count = $week_lesson_count/100;
            if($old_week_lesson_count != $week_lesson_count){
                $this->t_manager_info->send_wx_todo_msg_by_adminid (72,"理优监课组","老师周课时更改",$tea_nick."老师"."周课时由".$old_week_lesson_count."改为".$week_lesson_count.",操作人:".$account,"");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (448,"理优监课组","老师周课时更改",$tea_nick."老师"."周课时由".$old_week_lesson_count."改为".$week_lesson_count.",操作人:".$account,"");
                $this->t_teacher_record_list->row_insert([
                    "teacherid"          =>$teacherid,
                    "type"               =>7,
                    "add_time"           =>time(),
                    "acc"                =>$account,
                    "limit_week_lesson_num_new"  =>$limit_week_lesson_num,
                    "limit_week_lesson_num_old"  =>$old_week_num,
                    "seller_require_flag"        =>$seller_require_flag,
                    "record_info"        =>$tea_nick."老师"."周课时由".$old_week_lesson_count."改为".$week_lesson_count
                ]);
            }
        }
        return $this->output_succ();
    }

    public function update_teacher_info(){
        $teacherid             = $this->get_in_teacherid();
        $tea_nick              = $this->get_in_str_val('tea_nick',"");
        $realname              = $this->get_in_str_val('realname',"");
        $gender                = $this->get_in_int_val('gender', -1);
        $age                   = $this->get_in_int_val('age', -1);
        $birth                 = $this->get_in_str_val('birth',"");
        $work_year             = $this->get_in_int_val('work_year', 0);
        $email                 = $this->get_in_str_val('email',"");
        $base_intro            = $this->get_in_str_val('base_intro',"");
        $need_test_lesson_flag = $this->get_in_str_val("need_test_lesson_flag","");
        $wx_openid             = $this->get_in_str_val("wx_openid",null);
        $subject               = $this->get_in_int_val('subject',-1);
        $second_subject        = $this->get_in_int_val('second_subject',-1);
        $grade_part_ex         = $this->get_in_int_val('grade_part_ex',-1);
        $second_grade          = $this->get_in_int_val('second_grade',-1);
        $address               = $this->get_in_str_val('address',"");
        $school                = $this->get_in_str_val('school',"");
        $identity              = $this->get_in_int_val('identity');
        $phone_spare           = $this->get_in_str_val('phone_spare',"");

        $subject_old        = $this->t_teacher_info->get_subject($teacherid);
        $grade_part_ex_old  = $this->t_teacher_info->get_grade_part_ex($teacherid);
        $second_subject_old = $this->t_teacher_info->get_second_subject($teacherid);
        $second_grade_old   = $this->t_teacher_info->get_second_grade($teacherid);

        if($_SERVER['HTTP_HOST']=="admin.leo1v1.com" && !$this->check_account_in_arr(["ted","adrian"]) && $subject==0 && $subject_old!=0){
            return $this->output_err("没有权限修改第一科目至未设置,请找Erick");
        }

        $sub_arr  = [$subject,$second_subject];
        $sub_pass = $this->t_teacher_lecture_info->get_teacher_subject($teacherid);
        $adminid  = $this->get_account_id();
        $account  = $this->get_account();

        $this->cache_del_teacher_nick($teacherid);
        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            'nick'                  => $tea_nick,
            'realname'              => $realname,
            'gender'                => $gender,
            'age'                   => $age,
            'birth'                 => $birth,
            'work_year'             => $work_year,
            'email'                 => $email,
            'wx_openid'             => $wx_openid,
            'base_intro'            => $base_intro,
            'need_test_lesson_flag' => $need_test_lesson_flag,
            'address'               => $address,
            'school'                => $school,
            'identity'              => $identity,
            'subject'               => $subject,
            'grade_part_ex'         => $grade_part_ex,
            'second_grade'          => $second_grade,
            'second_subject'        => $second_subject,
            'phone_spare'           => $phone_spare,
        ]);

        $tea_arr = [];
        $tea_arr[]="72";
        if($ret && $subject_old != $subject) {
            $tea_arr[] = $this->get_tea_adminid_by_subject($subject_old);

            $this->t_field_modified_list->add_modified_teacher_info("t_teacher_info","subject",$subject_old,$subject,$adminid,$teacherid);
            $subject_str     = E\Esubject::get_desc($subject);
            $old_subject_str = E\Esubject::get_desc($subject_old);

            foreach($tea_arr as $v){
                $this->t_manager_info->send_wx_todo_msg_by_adminid($v,"理优监课组","老师第一科目更改",$tea_nick."老师,第一科目由".$old_subject_str."改为".$subject_str.",操作人:".$account,"");
            }
        }

        if($ret && $grade_part_ex_old != $grade_part_ex){
            $tea_arr[] = $this->get_tea_adminid_by_subject($subject_old);
            $this->t_field_modified_list->add_modified_teacher_info("t_teacher_info","grade_part_ex",$grade_part_ex_old,$grade_part_ex,$adminid,$teacherid);
            $grade_str     = E\Egrade_part_ex::get_desc($grade_part_ex);
            $old_grade_str = E\Egrade_part_ex::get_desc($grade_part_ex_old);
            foreach($tea_arr as $v){
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($v,"理优监课组","老师第一年级段更改",$tea_nick."老师,第一年级段由".$old_grade_str."改为".$grade_str.",操作人:".$account,"");
            }
        }

        if($ret && $second_subject_old != $second_subject){
            $tea_arr[] = $this->get_tea_adminid_by_subject($second_subject_old);
            $this->t_field_modified_list->add_modified_teacher_info("t_teacher_info","second_subject",$second_subject_old,$second_subject,$adminid,$teacherid);
            $second_subject_str = E\Esubject::get_desc($second_subject);
            $old_second_subject_str = E\Esubject::get_desc($second_subject_old);
            foreach($tea_arr as $v){
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($v,"理优监课组","老师第二科目更改",$tea_nick."老师,第二科目由".$old_second_subject_str."改为".$second_subject_str.",操作人:".$account,"");
            }
        }

        if($ret && $second_grade_old != $second_grade){
            $tea_arr[] = $this->get_tea_adminid_by_subject($second_subject_old);
            $this->t_field_modified_list->add_modified_teacher_info("t_teacher_info","second_grade",$second_grade_old,$second_grade,$adminid,$teacherid);
            $second_grade_str = E\Egrade_part_ex::get_desc($second_grade);
            $old_second_grade_str = E\Egrade_part_ex::get_desc($second_grade_old);
            foreach($tea_arr as $v){
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($v,"理优监课组","老师第二年级段更改",$tea_nick."老师,第二年级段由".$old_second_grade_str."改为".$second_grade_str.",操作人:".$account,"");
            }
        }
        return $this->output_succ();
    }

    public function update_tea_note()  {
        $teacherid = $this->get_in_teacherid();
        $tea_note  = trim($this->get_in_str_val('tea_note',""));

        $ret=$this->t_teacher_info->field_update_list($teacherid,[
            'tea_note'         => $tea_note
        ]);
        return $this->output_succ();

    }

    public function update_teacher_trial_lecture_is_pass(){
        $teacherid             = $this->get_in_teacherid();
        $trial_lecture_is_pass = $this->get_in_int_val('trial_lecture_is_pass');
        $train_through_new     = $this->get_in_int_val('train_through_new');
        $wx_use_flag           = $this->get_in_int_val('wx_use_flag');

        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        if($teacher_info['train_through_new']==0 && $train_through_new==1){
            $update_arr["train_through_new_time"] = time();
        }

        $update_arr["trial_lecture_is_pass"] = $trial_lecture_is_pass;
        $update_arr["train_through_new"]     = $train_through_new;
        $update_arr["wx_use_flag"]           = $wx_use_flag;

        $ret = $this->t_teacher_info->field_update_list($teacherid,$update_arr);
        return $this->output_succ();
    }

    public function update_interview_assess()  {
        $teacherid        = $this->get_in_teacherid();
        $interview_access = trim($this->get_in_str_val('interview_access',""));

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            'interview_access' => $interview_access
        ]);
        return $this->output_succ();
    }

    public function get_free_time_new()
    {
        $teacherid = $this->get_in_teacherid();
        $timestamp = $this->get_in_int_val("timestamp");
        $month_start = $this->get_in_int_val("month_start");
        $type = $this->get_in_int_val("type",0);
        if ($timestamp == 0) {
            $timestamp = time();
        }

        if($type==0) { //
            $ret_week=\App\Helper\Utils::get_week_range($timestamp,1);
            $start_time=$ret_week["sdate"];
            $end_time=$ret_week["edate"];
        }else{
            $ret_week=\App\Helper\Utils::get_month_range($timestamp) ;
            $start_time=$ret_week["sdate"];
            $end_time=$ret_week["edate"];
            if($month_start>0){
                $start_time = $month_start;
            }
        }
        $ret_info = $this->t_lesson_info->get_teacher_free_time_info_new( $teacherid, $start_time,$end_time );
        foreach ($ret_info["lesson_list"] as &$l_item)  {
            $lesson_type= $l_item["lesson_type"];

            $lesson_type_str=E\Econtract_type::get_desc($lesson_type);
            if($lesson_type<100) {
                $l_item["title"]="$lesson_type_str-".
                    $this->cache_get_student_nick( $l_item["userid"]);
            }else{
                $l_item["title"]="$lesson_type_str";
            }
        }
        return outputjson_success($ret_info);

    }

    public function lesson_record_server_list() {
        $page_num=$this->get_in_page_num();
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            = $this->get_in_order_by_str([],"title desc", []);

        list($start_time,$end_time)=$this->get_in_date_range_day(0,0, [
           0=> [ "lesson_start" ,"上课时间" ],
        ]);
        $record_audio_server1= $this->get_in_str_val("record_audio_server1");
        $xmpp_server_name= $this->get_in_str_val("xmpp_server_name");
        $lesson_type=$this->get_in_el_contract_type(-1, "lesson_type");
        $subject   = $this->get_in_el_subject();
        $userid=$this->get_in_userid(-1);

        $ret_info=$this->t_lesson_info_b3->lesson_record_server_list($page_num,  $start_time,$end_time, $record_audio_server1, $xmpp_server_name, $lesson_type, $subject );
        $start_index=\App\Helper\Utils::get_start_index_from_ret_info($ret_info);

        foreach($ret_info["list"] as $key=> &$item ) {
            $item["index"] =  $start_index+$key;
            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"],$item["lesson_end"]);
            E\Econtract_type::set_item_value_str($item, "lesson_type");
            if (in_array($item["lesson_type"], [0,1,3])) {
                $item["lesson_type_str"] = "常规课";
            }
            E\Esubject::set_item_value_str($item);
            //\App\Helper\Utils::fmt_lesson_name($grade,$subject,$lesson_num)
            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item);
            
        }
        if ( count( $ret_info["list"]) ==0 ) {
            $ret_info["list"][] =[];
            $ret_info["list"][] =[];
            $ret_info["list"][] =[];
            $ret_info["list"][] =[];
            $ret_info["list"][] =[];
        }

        return $this->pageView(__METHOD__, $ret_info);

    }

    public function get_seller_and_ass_lesson_info(){
        $lessonid = $this->get_in_lessonid();
        $list = $this->t_lesson_info->get_seller_and_ass_lesson_info($lessonid);
        $list["subject_str"] = E\Esubject::get_desc($list["subject"]);
        $list["grade_str"] = E\Egrade::get_desc($list["grade"]);


        //获取标签列表
        $tag = $this->get_teacher_tag_list();
        return $this->output_succ(["data"=>$list,"tag"=>$tag]);
    }

    public function get_teacher_complaints_info(){
        $id= $this->get_in_int_val("id",-1);
        $account_id = $this->get_account_id();

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $page_num    = $this->get_in_page_num();
        $adminid   = $this->get_in_int_val("adminid",-1);
        $accept_adminid   = $this->get_in_int_val("accept_adminid",-1);
        $accept_adminid_flag   = $this->get_in_str_val("accept_adminid_flag",0);
        if($accept_adminid_flag==1 || in_array($account_id,[99,349])){
            $account_id=-1;
        }
        $require_adminid      = $this->get_in_int_val("require_adminid",-1);
        $ret_info = $this->t_teacher_complaints_info->get_all_info($page_num,$account_id,$adminid,$accept_adminid,$require_adminid,$start_time,$end_time,$id);
        $start_index = \App\Helper\Utils::get_start_index_from_ret_info($ret_info) ;
        $domain = config('admin')['qiniu']['public']['url'];
        $num = strlen($domain)+1;

        foreach($ret_info["list"] as $k=>&$item){
            $item["id_index"] = $start_index+$k;
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"done_time","_str");
            E\Esubject::set_item_value_str($item);
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            if($item["accept_time"]>0){
                $item["deal_time"] = round(($item["accept_time"] - $item["add_time"])/3600,2);
            }
            if($item["complaints_info_url"]){
                $num_surl = strlen($item["complaints_info_url"]);
                $item["curl"] = substr($item["complaints_info_url"],$num,$num_surl-1);
            }else{
                $item["curl"]="";
            }

            if($item["record_scheme_url"]){
                $num_surl = strlen($item["record_scheme_url"]);
                $item["surl"] = substr($item["record_scheme_url"],$num,$num_surl-1);
            }else{
                $item["surl"]="";
            }
            $item["phone_ex"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['phone']);


        }
        $acc = $this->get_account();
        return $this->Pageview(__METHOD__,$ret_info,["acc"=>$acc]);

    }

    public function get_teacher_complaints_info_jw(){
        $adminid = $this->get_account_id();
        $this->set_in_value("adminid",$adminid);
        $this->set_in_value("accept_adminid_flag",1);
        return $this->get_teacher_complaints_info();
    }

    public function get_seller_require_commend_teacher_info_yy(){
        return  $this->get_seller_require_commend_teacher_info();
    }
    public function get_seller_require_commend_teacher_info(){
        $id= $this->get_in_int_val("id",-1);
        $account_id = $this->get_account_id();
        // $account_id=793;
        $accept_adminid_list = $this->get_accept_adminid_list($account_id);
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $page_num    = $this->get_in_page_num();
        $adminid   = $this->get_in_int_val("adminid",-1);
        $accept_adminid   = $this->get_in_int_val("accept_adminid",-1);
        $accept_adminid_flag   = $this->get_in_str_val("accept_adminid_flag",0);
        if($accept_adminid_flag==1){
            $accept_adminid_list=[];
        }
        $require_adminid      = $this->get_in_int_val("require_adminid",-1);
        $ret_info = $this->t_change_teacher_list->get_seller_require_commend_teacher_info($start_time,$end_time,$adminid,$page_num,$id,$accept_adminid,$accept_adminid_list,$require_adminid,2);
        $start_index = \App\Helper\Utils::get_start_index_from_ret_info($ret_info) ;

        foreach($ret_info["list"] as $k=>&$val){
            $val["id_index"] = $start_index+$k;
            \App\Helper\Utils::unixtime2date_for_item($val,"add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($val,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($val,"stu_request_test_lesson_time","_str");
            E\Esubject::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
            if($val["accept_time"]>0){
                $val["deal_time"] = round(($val["accept_time"] - $val["wx_send_time"])/3600,2);
            }
            $stu_request_lesson_time_info=\App\Helper\Utils::json_decode_as_array($val["stu_request_lesson_time_info"], true);
            $str_arr=[];
            if(is_array($stu_request_lesson_time_info)){
                foreach ($stu_request_lesson_time_info as $p_item) {
                    $str_arr[]=E\Eweek::get_desc($p_item["week"])." "
                        .date('H:i',@$p_item["start_time"])
                        .date('~H:i', $p_item["end_time"]);
                }
            }

            $val["stu_request_lesson_time_info_str"]= join("<br/>", $str_arr);


        }

        $acc = $this->get_account();
        return $this->Pageview(__METHOD__,$ret_info,["adminid"=>$adminid,"acc"=>$acc]);

    }
    public function get_seller_require_commend_teacher_info_seller(){
        $adminid = $this->get_account_id();
        $this->set_in_value("adminid",$adminid);
        $this->set_in_value("accept_adminid_flag",1);
        return $this->get_seller_require_commend_teacher_info();
    }
    public function get_seller_require_commend_teacher_info_ass(){
        $adminid = $this->get_account_id();
        $this->set_in_value("adminid",$adminid);
        $this->set_in_value("accept_adminid_flag",1);
        return $this->get_seller_require_commend_teacher_info();
    }



    public function get_seller_ass_record_info(){
        $id= $this->get_in_int_val("id",-1);
        $account_id = $this->get_account_id();
        $accept_adminid_list = $this->get_accept_adminid_list($account_id);
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $page_num    = $this->get_in_page_num();
        $adminid   = $this->get_in_int_val("adminid",-1);
        $accept_adminid   = $this->get_in_int_val("accept_adminid",-1);
        $accept_adminid_flag   = $this->get_in_str_val("accept_adminid_flag",0);
        if($accept_adminid_flag==1){
            $accept_adminid_list=[];
        }
        $require_adminid      = $this->get_in_int_val("require_adminid",-1);
        $ret_info = $this->t_seller_and_ass_record_list->get_seller_ass_record_info($start_time,$end_time,$adminid,$page_num,$id,$accept_adminid,$accept_adminid_list,$require_adminid);
        // dd($ret_info);
        $start_index = \App\Helper\Utils::get_start_index_from_ret_info($ret_info) ;
        $domain = config('admin')['qiniu']['public']['url'];
        $num = strlen($domain)+1;

        foreach($ret_info["list"] as $k=>&$val){
            $val["id_index"] = $start_index+$k;
            \App\Helper\Utils::unixtime2date_for_item($val,"add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($val,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($val,"done_time","_str");
            E\Esubject::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
            E\Eset_boolean::set_item_value_str($val,"is_change_teacher");
            $num_url = strlen($val["record_info_url"]);
            $val["rurl"] = substr($val["record_info_url"],$num,$num_url-1);
            if($val["record_scheme_url"]){
                $num_surl = strlen($val["record_scheme_url"]);
                $val["surl"] = substr($val["record_scheme_url"],$num,$num_surl-1);
            }else{
                $val["surl"]="";
            }
            if($val["is_done_flag"]==1){
                $val["is_done_flag_str"]="已解决";
            }elseif($val["is_done_flag"]==2){
                if($val["is_resubmit_flag"]==0){
                    $val["is_done_flag_str"]="未解决";
                }else{
                    $val["is_done_flag_str"]="未解决,已重新提交教学质量反馈";
                }
            }
            if($val["accept_time"]>0){
                $val["deal_time"] = round(($val["accept_time"] - $val["add_time"])/3600,2);
            }


        }

        $acc = $this->get_account();
        //  dd($ret_info);
        return $this->Pageview(__METHOD__,$ret_info,["adminid"=>$adminid,"acc"=>$acc]);
        // dd($ret_info);
    }

    public function get_seller_ass_record_info_ass(){
        $adminid = $this->get_account_id();
        $this->set_in_value("adminid",$adminid);
        $this->set_in_value("accept_adminid_flag",1);
        return $this->get_seller_ass_record_info();
    }

    public function get_seller_ass_record_info_seller(){
        $adminid = $this->get_account_id();
        $this->set_in_value("adminid",$adminid);
        $this->set_in_value("accept_adminid_flag",1);
        return $this->get_seller_ass_record_info();
    }


    public function get_teacher_info_by_teacherid(){
        $teacherid = $this->get_in_int_val("teacherid");
        if($teacherid == 0){
            return $this->output_err("老师id为0");
        }
        $data = $this->t_teacher_info->field_get_list(
            $teacherid,"teacher_type,teacher_money_type,level,is_test_user,create_meeting,lesson_hold_flag,teacher_ref_type"
        );

        return $this->output_succ(["data"=>$data]);
    }


    public function get_teacher_grade_range_new(){
        $teacherid = $this->get_in_int_val("teacherid");
        //$teacherid = 60029;
        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"grade_part_ex,grade_start,grade_end,not_grade");
        $arr=[];
        if($teacher_info["grade_start"]>0){
            for($i=$teacher_info["grade_start"];$i<=$teacher_info["grade_end"];$i++){
                $arr[] = $i;
            }
        }else{
            $grade = $teacher_info["grade_part_ex"];
            if($grade==1){
                $arr=[1,2];
            }else if($grade==2 ){
                $arr=[3,4];
            }else if($grade==3 ){
                $arr=[5,6];
            }else if($grade==4 || $grade==6 ){
                $arr=[1,2,3,4];
            }else if($grade==5 || $grade==7){
                $arr=[3,4,5,6];
            }
        }
        $not_grade_arr=explode(",",$teacher_info["not_grade"]);
        $list=[];
        foreach($not_grade_arr as $val){
           $grade_range= $this->get_grade_range_new($val);
            if(!in_array($grade_range,$list)){
                $list[] = $grade_range;
            }
        }
        $data=[];
        foreach($arr as $k=>$v){
            if(!in_array($v,$list)){
                $data[] = intval($v);
            }
        }
        //dd($list);
        return $this->output_succ(["list"=>$list,"data"=>$data]);
    }

    public function get_grade_range_limit_list(){
        $teacherid = $this->get_in_int_val("teacherid");
        //$teacherid = 50728;
        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"grade_part_ex,grade_start,grade_end,not_grade_limit");
        $arr=[];
        if($teacher_info["grade_start"]>0){
            for($i=$teacher_info["grade_start"];$i<=$teacher_info["grade_end"];$i++){
                $arr[] = $i;
            }
        }else{
            $grade = $teacher_info["grade_part_ex"];
            if($grade==1){
                $arr=[1,2];
            }else if($grade==2 ){
                $arr=[3,4];
            }else if($grade==3 ){
                $arr=[5,6];
            }else if($grade==4 || $grade==6 ){
                $arr=[1,2,3,4];
            }else if($grade==5 || $grade==7){
                $arr=[3,4,5,6];
            }
        }
        $data=[];
        $not_grade_limit_arr = json_decode($teacher_info["not_grade_limit"],true);

        foreach($arr as $val){
            $data[$val]["grade_range"] = $val;
            if(isset($not_grade_limit_arr[$val])){
                $data[$val]["limit_type"] = $not_grade_limit_arr[$val];
            }else{
                $data[$val]["limit_type"]=0;
            }
        }
        return $this->output_succ(["data"=>$data,"list"=>$arr]);
        //  dd($data);

    }

    public function set_grade_range(){
        $teacherid = $this->get_in_int_val("teacherid");
        $grade_start = $this->get_in_int_val("grade_start");
        $grade_end = $this->get_in_int_val("grade_end");
        if($grade_end < $grade_start){
            return $this->output_err("结束年级段不能小于开始年级段");
        }
        $this->t_teacher_info->field_update_list($teacherid,[
            "grade_start"  =>$grade_start,
            "grade_end"  =>$grade_end,
        ]);
        return $this->output_succ();
    }

    public function set_teacher_quit_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $is_quit = $this->get_in_int_val("is_quit");
        $quit_info = $this->get_in_str_val("quit_info");
        $quit_set_adminid = $this->get_account_id();
        if(empty($quit_info)){
            return $this->output_err("请填写离职确认信息");
        }

        $this->t_teacher_info->field_update_list($teacherid,[
            "is_quit"  =>$is_quit,
            "quit_time"=>time(),
            "quit_set_adminid"=>$quit_set_adminid,
            "quit_info" =>$quit_info
        ]);
        return $this->output_succ();
    }

    public function set_teacher_leave_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $leave_start_time = $this->get_in_str_val("leave_start_time");
        $leave_end_time = $this->get_in_str_val("leave_end_time");
        $leave_reason = $this->get_in_str_val("leave_reason");
        if(empty($leave_start_time) || empty($leave_end_time) || empty($leave_reason)){
            return $this->output_err("请填写完整!");
        }
        $leave_start_time = strtotime($leave_start_time);
        $leave_end_time = strtotime($leave_end_time);
        $leave_set_adminid = $this->get_account_id();
        $this->t_teacher_info->field_update_list($teacherid,[
            "leave_end_time"  =>$leave_end_time,
            "leave_start_time"  =>$leave_start_time,
            "leave_reason"  =>$leave_reason,
            "leave_set_time"=>time(),
            "leave_set_adminid"=>$leave_set_adminid
        ]);
        $this->t_teacher_leave_info->row_insert([
            "teacherid"   =>$teacherid,
            "leave_start_time" =>$leave_start_time,
            "leave_end_time" =>$leave_end_time,
            "leave_set_adminid" =>$leave_set_adminid,
            "leave_set_time"=>time(),
            "leave_reason"  =>$leave_reason,
        ]);

        $this->t_teacher_record_list->row_insert([
            "teacherid"               => $teacherid,
            "add_time"                => time(),
            "acc"                     => $this->get_account(),
            "record_info"             => $leave_reason,
            "type"                    => 5,
            "class_will_sub_type"     =>5,
            "class_will_type"         =>2,
            "recover_class_time"      =>$leave_end_time
        ]);

        return $this->output_succ();
    }

    public function get_teacher_leave_list(){
        $teacherid = $this->get_in_int_val("teacherid");
        $data = $this->t_teacher_leave_info->get_all_info_by_teacherid($teacherid);
        foreach($data as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"leave_start_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"leave_end_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"leave_set_time","_str");
            $item["account"] = $this->t_manager_info->get_account($item["leave_set_adminid"]);
        }
        return $this->output_succ(["data"=>$data]);
    }

    public function set_train_lesson_new(){
        $phone            = $this->get_in_str_val("phone");
        $tea_nick         = $this->get_in_str_val("tea_nick");
        $lesson_start     = $this->get_in_str_val("time");
        $subject          = $this->get_in_int_val("subject");
        $grade            = $this->get_in_int_val("grade");
        $id               = $this->get_in_int_val("id");
        $acc              = $this->get_account();

        if(empty($subject) || empty($lesson_start)){
            return $this->output_err("请填写完整");
        }

        $lesson_start = strtotime($lesson_start);
        $record_teacherid_list = $this->get_train_lesson_teacherid($subject,$grade,$lesson_start);
        if(empty($record_teacherid_list)){
            return $this->output_err("没有合适的老师！");
        }else{
            $record_teacherid  =  $record_teacherid_list[0];
        }

        \App\Helper\Utils::logger("hhahaha".$record_teacherid);

        if($lesson_start <= time()){
            return $this->output_err("请填写正确的上课时间");
        }

        $lesson_end = $lesson_start+1800;
        $ret_row2   = $this->t_lesson_info->check_teacher_time_free($record_teacherid,0,$lesson_start,$lesson_end);
        if($ret_row2){
            $error_lessonid = $ret_row2["lessonid"];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        $show_account_info = 0;
        if(empty($teacherid)){
            $show_account_info = 1;
            $teacher_info = [
                "phone"         => $phone,
                "tea_nick"      => $tea_nick,
                "send_sms_flag" => 0,
            ];
            $teacherid = $this->add_teacher_common($teacher_info);
        }else{
            $this->t_teacher_info->field_update_list($teacherid,[
                "realname" => $tea_nick,
                "nick"     => $tea_nick,
            ]);
        }

        //检查面试老师时间是否冲突
        $ret_row1 = $this->t_lesson_info->check_train_lesson_time_free($teacherid,0,$lesson_start,$lesson_end);
        if ($ret_row1) {
            $error_lessonid=$ret_row1["lessonid"];
            return $this->output_err(
                "<div>有现存的面试老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }


        $grade_str   = E\Egrade::get_desc($grade);
        $subject_str = E\Esubject::get_desc($subject);

        $lesson_name = mb_substr($tea_nick,0,1,"utf8")."老师".$grade_str.$subject_str."试讲";
        $courseid    = $this->t_course_order->add_open_course($teacherid,$lesson_name,$grade,$subject,1100);
        $tea_cw_url  = "http://leowww.oss-cn-shanghai.aliyuncs.com/Teacher/试讲内容——".$grade_str.$subject_str.".pdf";
        $this->t_lesson_info->row_insert([
            "courseid"           => $courseid,
            "lesson_name"        => $lesson_name,
            "lesson_start"       => $lesson_start,
            "lesson_end"         => $lesson_start+1800,
            "subject"            => $subject,
            "grade"              => $grade,
            "teacherid"          => $record_teacherid,
            "userid"             => $teacherid,
            "lesson_type"        => 1100,
            "server_type"        => 2,
            "lesson_sub_type"    => 1,
            "train_type"         => 5,
            "tea_cw_url"         => $tea_cw_url,
            "tea_cw_status"      => 1,
            "tea_cw_upload_time" => time(),
            "sys_operator"       => $acc,
        ]);

        $lessonid = $this->t_lesson_info->get_last_insertid();
        $this->t_train_lesson_user->row_insert([
            "lessonid" => $lessonid,
            "add_time" => time(),
            "userid"   => $teacherid,
            "train_type"=>5
        ]);

        $realname = $this->t_teacher_info->get_realname($teacherid);
        $phone = $this->t_teacher_info->get_phone($teacherid);
        $lesson_start_str = date("Y-m-d H:i:s",$lesson_start);
        $subject_str = E\Esubject::get_desc($subject);
        $grade_str = E\Egrade::get_desc($grade);
        $time_str = date("Y-m-d H:i:s",time());

        $lesson_time = date("Y-m-d",$lesson_start);
        $start_str = date("H:i",$lesson_start);
        $end_str = date("H:i",$lesson_start+1800);
        $lesson_time_str = $lesson_time." ".$start_str."-".$end_str;


        //删除之前排课(相同科目年级,未上课程)
        $this->delete_train_lesson_before($lessonid,$subject,$grade,$teacherid);


        //微信通知面试老师
        /**
         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
         * 标题课程 : 待办事项提醒
         * {{first.DATA}}
         * 待办主题：{{keyword1.DATA}}
         * 待办内容：{{keyword2.DATA}}
         * 日期：{{keyword3.DATA}}
         * {{remark.DATA}}
         */
        $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid);
        if($wx_openid){
            $data=[];
            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']    = $realname."老师您好,您的面试课程已排好";
            $data['keyword1'] = "1对1面试课程";
            $data['keyword2'] = "\n面试时间：$lesson_time_str "
                              ."\n面试账号：$phone"
                              ."\n面试密码：123456"
                              ."\n年级科目 : ".$grade_str."".$subject_str;
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "请查阅邮件(报名时填写的邮箱),准备好耳机和话筒,并在面试开始前5分钟进入软件,理优教育致力于打造高水平的教学服务团队,期待您的加入,加油!";
            $url = "";
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        }

        //微信通知教研老师
        $uid = $this->t_manager_info->get_adminid_by_teacherid($record_teacherid);
        $record_realname = $this->t_teacher_info->get_realname($record_teacherid);

        $this->t_manager_info->send_wx_todo_msg_by_adminid ($uid,"1对1面试课程",$record_realname."老师您好,您的面试课程已排好","
面试时间:".$lesson_time_str."
面试老师:".$realname."
年级科目:".$grade_str."".$subject_str."
请准备好耳机和话筒,并在面试开始前5分钟进入软件","http://admin.leo1v1.com/tea_manage/train_lecture_lesson?lessonid=".$lessonid);

        //邮件通知面试老师
        $email = $this->t_teacher_lecture_appointment_info->get_email_by_phone($phone);
        if($show_account_info==1){
            $show_account_html="<font color='#FF0000'>账号：".$phone."</font><br><font color='#FF0000'>密码：123456 </font><br>";
        }else{
            $show_account_html="";
        }
        if($email){
            dispatch( new \App\Jobs\SendEmailNew(
                $email,"【理优1对1】试讲邀请和安排","尊敬的".$realname."老师：<br>
感谢您对理优1对1的关注，您的录制试讲申请已收到！<br>
为了更好的评估您的教学能力，需要您尽快按照如下要求提交试讲视频<br><br>
【试讲信息】<br>".$show_account_html."
<font color='#FF0000'>时间：".$lesson_time_str."</font><br><bropt-plan-train_lesson>
【试讲方式】<br>
 面试试讲（公校老师推荐）<br>
 电话联系老师预约可排课时间，评审老师和面试老师同时进入理优培训课堂进行面试，面试通过后，进行新师培训并完成自测即可入职<br>
<font color='#FF0000'>注意：若面试老师因个人原因不能按时参加1对1面试，请提前至少4小时告知招师老师，以便招师老师安排其他面试，如未提前4小时告知招师组老师，将视为永久放弃面试机会。</font><br><br>

【试讲要求】<br>
请下载好<font color='#FF0000'>理优老师客户端</font>并准备好<font color='#FF0000'>耳机和话筒</font>，用<font color='#FF0000'>指定内容</font>在理优老师客户端进行试讲<br>
 [相关下载]↓↓↓<br>
 1、理优老师客户端<a href='http://www.leo1v1.com/common/download'>点击下载</a><br>
 2、指定内容(内附【1对1面试】操作视频教程)<a href='http://file.leo1v1.com/index.php/s/pUaGAgLkiuaidmW'>点击下载</a><br>
 [结果通知]<br>
  <img src='http://admin.leo1v1.com/images/lsb.png' alt='对不起,图片失效了'><br>

（关注并绑定理优1对1老师帮公众号：随时了解入职进度）<br>
 [通关攻略]<br>
 1、保证相对安静的试讲环境和稳定的网络环境 [通关攻略]<br>
 2、要上传讲义和板书，试讲要结合板书<br>
 3、要注意跟学生的互动（假设电脑的另一端坐着学生）<br>
 4、简历、PPT完善后需转成PDF格式才能上传；<br>
 5、准备充分再录制，面试机会只有一次，要认真对待。<br>
<font color='#FF0000'>（温馨提示：请在每次翻页后在白板中画一笔，保证白板和声音同步）</font><br>
 [面试步骤]<br>
 1、备课  —  2、试讲  —  3、培训  —  4、入职<br>
【联系我们】<br>
如有疑问请加QQ群 : 608794924<br>
  <img src='http://admin.leo1v1.com/images/sjdy.png' alt='对不起,图片失效了'><br>

【LEO】试讲-答疑QQ群<br><br>

【岗位介绍】<br>
名称：理优在线1对1授课教师（通过理优教师端进行网络语音或视频授课）<br>
时薪：50-100RMB<br><br>

【关于理优】<br>
理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）"
            ));

            $this->t_lesson_info->field_update_list($lessonid,[
                "train_email_flag"  =>1
            ]);
        }

        if($id>0){
            $this->t_teacher_lecture_appointment_info->field_update_list($id,[
                "lecture_revisit_type"  =>4
            ]);
        }


        return $this->output_succ();
    }


    public function send_train_lesson_email(){
        $phone = $this->get_in_str_val("phone");
        $lessonid = $this->get_in_int_val("lessonid");
        $lesson_time_str = $this->get_in_str_val("lesson_time");
        $realname = $this->get_in_str_val("realname");

        $email = $this->t_teacher_lecture_appointment_info->get_email_by_phone($phone);
        if($email){
            dispatch( new \App\Jobs\SendEmailNew(
                $email,"【理优1对1】试讲邀请和安排","尊敬的".$realname."老师：<br>
感谢您对理优1对1的关注，您的录制试讲申请已收到！<br>
为了更好的评估您的教学能力，需要您尽快按照如下要求提交试讲视频<br><br>
【试讲信息】<br>
<font color='#FF0000'>账号：".$phone."</font><br>
<font color='#FF0000'>密码：123456 </font><br>
<font color='#FF0000'>时间：".$lesson_time_str."</font><br><br>
【试讲方式】<br>
 面试试讲（公校老师推荐）<br>
 电话联系老师预约可排课时间，评审老师和面试老师同时进入理优培训课堂进行面试，面试通过后，进行新师培训并完成自测即可入职<br>
<font color='#FF0000'>注意：若面试老师因个人原因不能按时参加1对1面试，请提前至少4小时告知招师老师，以便招师老师安排其他面试，如未提前4小时告知招师组老师，将视为永久放弃面试机会。</font><br><br>

【试讲要求】<br>
请下载好<font color='#FF0000'>理优老师客户端</font>并准备好<font color='#FF0000'>耳机和话筒</font>，用<font color='#FF0000'>指定内容</font>在理优老师客户端进行试讲<br>
 [相关下载]↓↓↓<br>
 1、理优老师客户端<a href='http://www.leo1v1.com/common/download'>点击下载</a><br>
 2、指定内容(内附【1对1面试】操作视频教程)<a href='http://file.leo1v1.com/index.php/s/pUaGAgLkiuaidmW'>点击下载</a><br>
 [结果通知]<br>
  <img src='http://admin.leo1v1.com/images/lsb.png' alt='对不起,图片失效了'><br>

（关注并绑定理优1对1老师帮公众号：随时了解入职进度）<br>
 [通关攻略]<br>
 1、保证相对安静的试讲环境和稳定的网络环境 [通关攻略]<br>
 2、要上传讲义和板书，试讲要结合板书<br>
 3、要注意跟学生的互动（假设电脑的另一端坐着学生）<br>
 4、简历、PPT完善后需转成PDF格式才能上传；<br>
 5、准备充分再录制，面试机会只有一次，要认真对待。<br>
<font color='#FF0000'>（温馨提示：请在每次翻页后在白板中画一笔，保证白板和声音同步）</font><br>
 [面试步骤]<br>
 1、备课  —  2、试讲  —  3、培训  —  4、入职<br>
【联系我们】<br>
如有疑问请加QQ群 : 608794924<br>
  <img src='http://admin.leo1v1.com/images/sjdy.png' alt='对不起,图片失效了'><br>

【LEO】试讲-答疑QQ群<br><br>

【岗位介绍】<br>
名称：理优在线1对1授课教师（通过理优教师端进行网络语音或视频授课）<br>
时薪：50-100RMB<br><br>

【关于理优】<br>
理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）"
            ));

            $this->t_lesson_info->field_update_list($lessonid,[
                "train_email_flag"  =>1
            ]);

        }


        return $this->output_succ();

    }

    public function get_re_submit_num(){
        $phone   = $this->get_in_phone();
        $subject = $this->get_in_subject();
        $grade   = $this->get_in_grade();
        $num = $this->t_teacher_lecture_info->get_re_submit_num($phone,$subject,$grade);


        //老师标签

        $list = $this->get_teacher_tag_list();


        return $this->output_succ(["num"=>$num,"data"=>$list]);
    }

    public function set_re_submit_and_lecture_out_info(){
        $id = $this->get_in_int_val("id");
        $re_submit_list = $this->get_in_str_val("re_submit_list");
        $lecture_out_list = $this->get_in_str_val("lecture_out_list");
        $reason = trim($this->get_in_str_val("reason"));
        $re_submit_arr = !empty($re_submit_list)?json_decode($re_submit_list,true):[];
        $lecture_out_arr=!empty($lecture_out_list)?json_decode($lecture_out_list,true):[];
        $retrial_arr = array_merge($re_submit_arr,$lecture_out_arr);
        $retrial_info = json_encode($retrial_arr);
        if(!empty($lecture_out_arr)){
            $status=2;
        }else{
            $status=3;
        }
        /* $reason="";
        foreach($retrial_arr as $val){
            $reason .=  E\Eretrial::get_desc($val).",";
        }
        $reason = trim($reason,",");*/

        $this->t_teacher_lecture_info->field_update_list($id,[
            "status"   =>$status,
            "retrial_info" =>$retrial_info,
            "reason"   =>$reason,
            "confirm_time" =>time()
        ]);


        $teacher_info                          = $this->t_teacher_lecture_info->get_lecture_info($id);
        $teacher_info['id']                    = $id;
        $teacher_info['reason']                = $reason;

        $this->send_lecture_sms_new($teacher_info,$status);

        return $this->output_succ();
    }

    public function cancel_train_lesson(){
        $lessonid      = $this->get_in_int_val("lessonid");
        $trial_train_status= $this->get_in_int_val("trial_train_status");
        $adminid       = $this->get_account_id();
        $account       = $this->get_account();
        $account_role  = $this->get_account_role();

        if(in_array($trial_train_status,[-1,2]) || in_array($account_role,[12])){
            $ret = $this->t_lesson_info->field_update_list($lessonid,[
                "lesson_del_flag" => 1,
                "confirm_adminid" => $adminid
            ]);
        }else{
            return $this->output_err("课程状态不对！无法删除！");
        }

        if(!$ret){
            return $this->output_err("删除失败！请重试！");
        }
        return $this->output_succ();
    }

    public function send_not_through_notice(){
        $start_time = $this->get_in_int_val("start_time");
        $end_time   = $this->get_in_int_val("end_time");

        $list = $this->t_train_lesson_user->get_not_through_user($start_time,$end_time,1);

        $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data['first']    = "老师您好！";
        $data['keyword1'] = "邀请参训通知";
        $data['keyword2'] = "经系统核查您试讲通过多日培训未通过，为方便老师尽快完成入职手续，敬请您参加定于每周三、五19点的新师培训；若时间冲突，您可登录老师端，在【我的培训】中观看回放后，点击【自我测评】回答，通过后即收到【入职offer】，另请您在【后台】尽快设置【模拟课程时间】，通过后即成功晋升。";
        $data['keyword3'] = date("Y-m-d",time());
        $data['remark']   = "如有任何疑问可在新师培训群：315540732咨询【师训】沈老师。";

        $job = new \App\Jobs\SendTeacherWx($list,$template_id,$data,"");
        dispatch($job);
        return $this->output_succ();
    }

    public function set_full_time_teacher_record(){
        $phone       = $this->get_in_str_val("phone");
        $flag        = $this->get_in_int_val("flag");
        $record_info = $this->get_in_str_val("record_info");
        $nick        = $this->get_in_str_val("nick");
        $account     = $this->get_in_str_val("account");
        $acc         = $this->get_account();

        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        if(!$teacherid){
            return $this->output_err("老师id出错！");
        }
        if($flag==1){
            $this->set_full_time_teacher($teacherid);
        }
        $record_id = $this->t_teacher_record_list->check_have_record($teacherid,E\Erecord_type::V_12);
        if($record_id){
            $ret = $this->t_teacher_record_list->field_update_list($record_id,[
                "record_info"        => $record_info,
                "trial_train_status" => $flag,
            ]);
        }else{
            $ret = $this->t_teacher_record_list->row_insert([
                "teacherid"          => $teacherid,
                "trial_train_status" => $flag,
                "record_info"        => $record_info,
                "add_time"           => time(),
                "type"               => E\Erecord_type::V_12,
                "current_acc"        => $acc,
                "acc"                => $account,
                "phone_spare"        => $phone
            ]);
        }

        if(!$ret){
            return $this->output_err("添加反馈失败！");
        }
        return $this->output_succ();
    }

    //@desn:手动添加公开课
    public function open_class_add(){
        $lesson_start = strtotime($this->get_in_str_val('lesson_start'));
        $lesson_end = strtotime($this->get_in_str_val('lesson_end'));
        $subject = $this->get_in_int_val('subject');
        \App\Helper\Utils::logger("subject $subject");
        $grade = $this->get_in_int_val('grade');
        $teacherid = $this->get_in_int_val('tea_name');
        // $tea_name = $this->get_in_str_val('teacher_name');
        // $phone = $this->get_in_str_val('teacher_phone');
        $suit_student = $this->get_in_str_val('suit_student');
        $title = $this->get_in_str_val('title');
        $package_intro = $this->get_in_str_val('package_intro');
        // $subject_arr = E\Esubject::$desc_map;
        // $grade_arr   = E\Egrade::$desc_map;
        if(!$lesson_start)
            return $this->output_err('课程开始时间为必填项!');
        // $subject = array_search($subject,$subject_arr);
        // $grade   = array_search($grade,$grade_arr);

        // $check_phone=\App\Helper\Utils::check_phone($phone);
        // if($check_phone){
        //     $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        // }else{
        //     $teacherid = $this->t_teacher_info->get_teacherid_by_name($tea_name);
        // }
        if(!$teacherid){
            \App\Helper\Utils::logger("add open course 老师不存在".$teacherid);
            return $this->output_err('该老师不存在!');
        }

        $ret = $this->t_lesson_info->check_teacher_time_free($teacherid,0,$lesson_start,$lesson_end);

        if($ret){
            return $this->output_err("与现存的老师课程冲突".$ret["lessonid"]."老师id".$teacherid);
            \App\Helper\Utils::logger("与现存的老师课程冲突".$ret["lessonid"]."老师id".$teacherid);
        }else{
            $packageid = $this->t_appointment_info->add_appoint(
                $title,E\Econtract_type::V_1001,$package_intro,$suit_student,$subject,$grade
            );
            $courseid  = $this->t_course_order->add_open_course(
                $teacherid,$title,$grade,$subject,E\Econtract_type::V_1001,$packageid,1
            );
            $lessonid  = $this->t_lesson_info->add_open_lesson(
                $teacherid,$courseid,$lesson_start,$lesson_end,$subject,$grade
            );
            return $this->output_succ();
        }
    }


    public function add_open_class_by_xls(){
        \App\Helper\Utils::logger("begin create open class");
        $file = Input::file('file');

        if ($file->isValid()) {
            $tmpName  = $file->getFileName();
            $realPath = $file->getRealPath();

            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $obj_file  = "/tmp/001.xls";
            move_uploaded_file($realPath,$obj_file);
            $objPHPExcel = $objReader->load($obj_file);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr  = $objPHPExcel->getActiveSheet()->toArray();
            $info = "";
            $subject_arr = E\Esubject::$desc_map;
            $grade_arr   = E\Egrade::$desc_map;

            //0时间 1科目 2年级 3任课老师 4手机号 5适合学生 6课题 7内容介绍
            foreach($arr as $key=>$val){
                if($key!=0 && count($val)==8){
                    //开始添加公开课
                    $lesson_start  = strtotime($val[0]);
                    $subject       = $val[1];
                    $grade         = $val[2];
                    $tea_name      = $val[3];
                    $phone         = (string)$val[4];
                    $suit_student  = $val[5];
                    $title         = $val[6];
                    $package_intro = $val[7];

                    if(!$lesson_start){
                        continue;
                    }else{
                        $subject = array_search($subject,$subject_arr);
                        $grade   = array_search($grade,$grade_arr);

                        $check_phone=\App\Helper\Utils::check_phone($phone);
                        if($check_phone){
                            $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
                        }else{
                            $teacherid = $this->t_teacher_info->get_teacherid_by_name($tea_name);
                        }
                        if(!$teacherid){
                            \App\Helper\Utils::logger("add open course 老师不存在".$tea_name);
                            continue;
                        }

                        $lesson_end = $lesson_start+3600;
                        $ret = $this->t_lesson_info->check_teacher_time_free($teacherid,0,$lesson_start,$lesson_end);

                        if($ret){
                            \App\Helper\Utils::logger("有现存的老师课程冲突".$ret["lessonid"]."老师id".$teacherid);
                        }else{
                            $packageid = $this->t_appointment_info->add_appoint(
                                $title,E\Econtract_type::V_1001,$package_intro,$suit_student,$subject,$grade
                            );
                            $courseid  = $this->t_course_order->add_open_course(
                                $teacherid,$title,$grade,$subject,E\Econtract_type::V_1001,$packageid,1
                            );
                            $lessonid  = $this->t_lesson_info->add_open_lesson(
                                $teacherid,$courseid,$lesson_start,$lesson_end,$subject,$grade
                            );
                        }
                    }
                }
                //公开课添加结束


            }
        }
    }

    public function add_open_class_by_xls_new(){
        \App\Helper\Utils::logger("begin create open class");
        $file = Input::file('file');
        if ($file->isValid()) {
            $tmpName  = $file->getFileName();
            $realPath = $file->getRealPath();

            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $obj_file  = "/tmp/001.xls";
            move_uploaded_file($realPath,$obj_file);
            $objPHPExcel = $objReader->load($obj_file);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr  = $objPHPExcel->getActiveSheet()->toArray();
            $orderid_arr = [];
            foreach($arr as $item){
                foreach($item as $info){
                    if(!is_string($info) && $info>0){
                        $orderid_arr[] = (int)$info;
                    }
                }
            }
            $ret_info = $this->t_order_info->get_seller_add_time_by_orderid_str($orderid_arr);
            foreach($ret_info as &$item){
                \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            }
            dd($ret_info);
        }
    }

    public function set_teacher_check_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $subject   = $this->get_in_int_val("subject");
        $grade     = $this->get_in_str_val("grade");

        if(!$teacherid){
            return $this->output_err("老师错误！");
        }

        $this->t_teacher_info->field_update_list($teacherid,[
            "check_subject"=>$subject,
            "check_grade"=>$grade,
        ]);

        return $this->output_succ();
    }


    public function set_teacher_pass_type(){
        $id = $this->get_in_int_val("id");
        $teacher_pass_type   = $this->get_in_int_val("teacher_pass_type");
        $no_pass_reason     = trim($this->get_in_str_val("no_pass_reason"));
        $this->t_teacher_lecture_appointment_info->field_update_list($id,[
            "teacher_pass_type" =>$teacher_pass_type,
            "no_pass_reason"    =>$no_pass_reason
        ]);
        return $this->output_succ();

    }

    public function approved_data(){
        $this->check_and_switch_tongji_domain();
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $page_num = $this->get_in_page_num();
        $teacherid = $this->get_in_int_val("teacherid",-1);

        $this->t_lesson_info_b3->switch_tongji_database();
        $ret_info = [];
        $ret_info = $this->t_lesson_info_b3->get_tea_lesson_info_for_approved($start_time, $end_time,$page_num,$teacherid);

        foreach($ret_info['list'] as &$item){
            $cc_order_num = $this->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $item['teacherid'],'2');
            $cc_lesson_num = $this->t_order_info->get_cc_lesson_num($start_time, $end_time, $item['teacherid'], '2');
            if($cc_lesson_num>0){
                $item['cc_rate'] = $cc_order_num/$cc_lesson_num;
            }else{
                $item['cc_rate'] = 0;
            }

            $cr_order_num  = $this->t_order_info->get_cc_test_lesson_num($start_time, $end_time, $item['teacherid'],'1');
            $cr_lesson_num = $this->t_order_info->get_cc_lesson_num($start_time, $end_time, $item['teacherid'], '1');
            if($cr_order_num>0){
                $item['cr_rate'] = $cr_order_num/$cr_lesson_num;
            }else{
                $item['cr_rate'] = 0;
            }

            $item['tea_nick'] = $this->cache_get_teacher_nick($item['teacherid']);
            $violation_info = $this->t_lesson_info_b3->get_violation_num($start_time, $end_time, $item['teacherid']);
            $item['violation_num'] = array_sum($violation_info);

        }


        return $this->pageView(__METHOD__,$ret_info);
    }

    public function approved_data_new(){
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $page_num = $this->get_in_page_num();
        $teacherid = $this->get_in_int_val("teacherid",-1);

        $ret_info = $this->t_teacher_approve_refer_to_data->get_all_list($start_time, $end_time, $page_num, $teacherid);
        foreach ($ret_info['list'] as &$item) {
            if($item['cc_lesson_num']>0){
                $item['cc_rate'] = $item['cc_order_num']/$item['cc_lesson_num'];
            }else{
                $item['cc_rate'] = 0;
            }

            if($item['cr_lesson_num']>0){
                $item['cr_rate'] = $item['cr_order_num']/$item['cr_lesson_num'];
            }else{
                $item['cr_rate'] = 0;
            }

            $item['tea_nick'] = $this->cache_get_teacher_nick($item['teacherid']);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    /**
     * 添加面试试讲
     */
    public function add_train_lesson_new(){
        $phone            = $this->get_in_str_val("phone");
        $tea_nick         = $this->get_in_str_val("tea_nick");
        $lesson_start     = $this->get_in_str_val("lesson_start");
        $subject          = $this->get_in_int_val("subject");
        $grade            = $this->get_in_int_val("grade");
        $record_teacherid = $this->get_in_int_val("record_teacherid");
        $id               = $this->get_in_int_val("id");
        $acc              = $this->get_account();

        if(empty($subject) || empty($lesson_start) || empty($record_teacherid)){
            return $this->output_err("请填写完整");
        }

        $lesson_start = strtotime($lesson_start);
        if($lesson_start <= time()){
            return $this->output_err("请填写正确的上课时间");
        }

        $lesson_end = $lesson_start+1800;
        $ret_row2   = $this->t_lesson_info->check_teacher_time_free($record_teacherid,0,$lesson_start,$lesson_end);
        if($ret_row2){
            $error_lessonid = $ret_row2["lessonid"];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        $show_account_info = 0;
        if(empty($teacherid)){
            $show_account_info = 1;
            $teacher_info = [
                "phone"         => $phone,
                "tea_nick"      => $tea_nick,
                "send_sms_flag" => 0,
            ];
            $teacherid = $this->add_teacher_common($teacher_info);
        }else{
            $this->t_teacher_info->field_update_list($teacherid,[
                "realname" => $tea_nick,
                "nick"     => $tea_nick,
            ]);
        }

        //检查面试老师时间是否冲突
        $ret_row1 = $this->t_lesson_info->check_train_lesson_time_free($teacherid,0,$lesson_start,$lesson_end);
        if ($ret_row1) {
            $error_lessonid=$ret_row1["lessonid"];
            return $this->output_err(
                "<div>有现存的面试老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }


        $grade_str   = E\Egrade::get_desc($grade);
        $subject_str = E\Esubject::get_desc($subject);

        $lesson_name = mb_substr($tea_nick,0,1,"utf8")."老师".$grade_str.$subject_str."试讲";
        $courseid    = $this->t_course_order->add_open_course($teacherid,$lesson_name,$grade,$subject,1100);
        $tea_cw_url  = "http://leowww.oss-cn-shanghai.aliyuncs.com/Teacher/试讲内容——".$grade_str.$subject_str.".pdf";
        $this->t_lesson_info->row_insert([
            "courseid"           => $courseid,
            "lesson_name"        => $lesson_name,
            "lesson_start"       => $lesson_start,
            "lesson_end"         => $lesson_start+1800,
            "subject"            => $subject,
            "grade"              => $grade,
            "teacherid"          => $record_teacherid,
            "userid"             => $teacherid,
            "lesson_type"        => 1100,
            "server_type"        => 2,
            "lesson_sub_type"    => 1,
            "train_type"         => 5,
            "tea_cw_url"         => $tea_cw_url,
            "tea_cw_status"      => 1,
            "tea_cw_upload_time" => time(),
            "sys_operator"       => $acc,
        ]);

        $lessonid = $this->t_lesson_info->get_last_insertid();
        $this->t_train_lesson_user->row_insert([
            "lessonid" => $lessonid,
            "add_time" => time(),
            "userid"   => $teacherid,
            "train_type"=>5
        ]);

        $realname = $this->t_teacher_info->get_realname($teacherid);
        $phone = $this->t_teacher_info->get_phone($teacherid);
        $lesson_start_str = date("Y-m-d H:i:s",$lesson_start);
        $subject_str = E\Esubject::get_desc($subject);
        $grade_str = E\Egrade::get_desc($grade);
        $time_str = date("Y-m-d H:i:s",time());

        $lesson_time = date("Y-m-d",$lesson_start);
        $start_str = date("H:i",$lesson_start);
        $end_str = date("H:i",$lesson_start+1800);
        $lesson_time_str = $lesson_time." ".$start_str."-".$end_str;


        //删除之前排课(相同科目年级)
        $this->delete_train_lesson_before($lessonid,$subject,$grade,$teacherid);

        //微信通知面试老师
        /**
         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
         * 标题课程 : 待办事项提醒
         * {{first.DATA}}
         * 待办主题：{{keyword1.DATA}}
         * 待办内容：{{keyword2.DATA}}
         * 日期：{{keyword3.DATA}}
         * {{remark.DATA}}
         */
        $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid);
        if($wx_openid){
            $data=[];
            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']    = $realname."老师您好,您的面试课程已排好";
            $data['keyword1'] = "1对1面试课程";
            $data['keyword2'] = "\n面试时间：$lesson_time_str "
                              ."\n面试账号：$phone"
                              ."\n面试密码：123456"
                              ."\n年级科目 : ".$grade_str."".$subject_str;
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "请查阅邮件(报名时填写的邮箱),准备好耳机和话筒,并在面试开始前5分钟进入软件,理优教育致力于打造高水平的教学服务团队,期待您的加入,加油!";
            $url = "";
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        }

        //微信通知教研老师
        $uid = $this->t_manager_info->get_adminid_by_teacherid($record_teacherid);
        $record_realname = $this->t_teacher_info->get_realname($record_teacherid);

        $this->t_manager_info->send_wx_todo_msg_by_adminid ($uid,"1对1面试课程",$record_realname."老师您好,您的面试课程已排好","
面试时间:".$lesson_time_str."
面试老师:".$realname."
年级科目:".$grade_str."".$subject_str."
请准备好耳机和话筒,并在面试开始前5分钟进入软件","http://admin.leo1v1.com/tea_manage/train_lecture_lesson?lessonid=".$lessonid);

        //邮件通知面试老师
        $email = $this->t_teacher_lecture_appointment_info->get_email_by_phone($phone);
        if($show_account_info==1){
            $show_account_html="<font color='#FF0000'>账号：".$phone."</font><br><font color='#FF0000'>密码：123456 </font><br>";
        }else{
            $show_account_html="";
        }
        if($email){
            dispatch( new \App\Jobs\SendEmailNew(
                $email,"【理优1对1】试讲邀请和安排","尊敬的".$realname."老师：<br>
感谢您对理优1对1的关注，您的录制试讲申请已收到！<br>
为了更好的评估您的教学能力，需要您尽快按照如下要求提交试讲视频<br><br>
【试讲信息】<br>".$show_account_html."
<font color='#FF0000'>时间：".$lesson_time_str."</font><br><bropt-plan-train_lesson>
【试讲方式】<br>
 面试试讲（公校老师推荐）<br>
 电话联系老师预约可排课时间，评审老师和面试老师同时进入理优培训课堂进行面试，面试通过后，进行新师培训并完成自测即可入职<br>
<font color='#FF0000'>注意：若面试老师因个人原因不能按时参加1对1面试，请提前至少4小时告知招师老师，以便招师老师安排其他面试，如未提前4小时告知招师组老师，将视为永久放弃面试机会。</font><br><br>

【试讲要求】<br>
请下载好<font color='#FF0000'>理优老师客户端</font>并准备好<font color='#FF0000'>耳机和话筒</font>，用<font color='#FF0000'>指定内容</font>在理优老师客户端进行试讲<br>
 [相关下载]↓↓↓<br>
 1、理优老师客户端<a href='http://www.leo1v1.com/common/download'>点击下载</a><br>
 2、指定内容(内附【1对1面试】操作视频教程)<a href='http://file.leo1v1.com/index.php/s/pUaGAgLkiuaidmW'>点击下载</a><br>
 [结果通知]<br>
  <img src='http://admin.leo1v1.com/images/lsb.png' alt='对不起,图片失效了'><br>

（关注并绑定理优1对1老师帮公众号：随时了解入职进度）<br>
 [通关攻略]<br>
 1、保证相对安静的试讲环境和稳定的网络环境 [通关攻略]<br>
 2、要上传讲义和板书，试讲要结合板书<br>
 3、要注意跟学生的互动（假设电脑的另一端坐着学生）<br>
 4、简历、PPT完善后需转成PDF格式才能上传；<br>
 5、准备充分再录制，面试机会只有一次，要认真对待。<br>
<font color='#FF0000'>（温馨提示：请在每次翻页后在白板中画一笔，保证白板和声音同步）</font><br>
 [面试步骤]<br>
 1、备课  —  2、试讲  —  3、培训  —  4、入职<br>
【联系我们】<br>
如有疑问请加QQ群 : 608794924<br>
  <img src='http://admin.leo1v1.com/images/sjdy.png' alt='对不起,图片失效了'><br>

【LEO】试讲-答疑QQ群<br><br>

【岗位介绍】<br>
名称：理优在线1对1授课教师（通过理优教师端进行网络语音或视频授课）<br>
时薪：50-100RMB<br><br>

【关于理优】<br>
理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）"
            ));

            $this->t_lesson_info->field_update_list($lessonid,[
                "train_email_flag"  =>1
            ]);
        }

        if($id>0){
            $this->t_teacher_lecture_appointment_info->field_update_list($id,[
                "lecture_revisit_type"  =>4
            ]);
        }


        return $this->output_succ();
    }


}
