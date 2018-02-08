<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class ss_deal extends Controller
{
    use CacheNick;
    use TeaPower;
    use LessonPower;

    public function get_in_test_lesson_subject_id($default_value=0) {
        return $this->get_in_int_val("test_lesson_subject_id",$default_value);
    }
    public function get_in_require_id() {
        return $this->get_in_int_val("require_id");
    }
    public function add_ss() {
        $phone    = $this->get_in_phone();
        $origin   = $this->get_in_str_val("origin");
        $grade    = $this->get_in_grade();
        $subject  = $this->get_in_subject();
        $tmk_flag = $this->get_in_int_val("tmk_flag", 0);

        if (strlen($phone )!=11) {
            return $this->output_err("电话号码长度不对");
        }

        $userid=$this->t_phone_to_user->get_userid_by_phone($phone);
        if ($this->t_test_lesson_subject->check_subject($userid,$subject))  {
            return $this->output_err("已经有了这个科目的例子了,不能增加");
        }

        $userid=$this->t_seller_student_new->book_free_lesson_new("",$phone,$grade,$origin,$subject,0);
        if ($tmk_flag){
            \App\Helper\Utils::logger("SET TMK INFO");

            $this->t_seller_student_new->field_update_list($userid,[
                "tmk_assign_time"  => time(NULL) ,
                "tmk_adminid"  => $this->get_account_id(),
                "tmk_join_time"  => time(NULL),
                "tmk_student_status"  => 0,
            ]);
                $account=$this->get_account();
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: 状态:  TMK新增例子  :$account ",
                "system"
            );
        }

        //例子自动分配测试环境用
        if(\App\Helper\Utils::check_env_is_test() || \App\Helper\Utils::check_env_is_local()){
            \App\Helper\Utils::logger("添加到自动抢单测试1"); 
            $uid=$this->get_in_adminid();
            $posterTag = 0;
            $job = new \App\Jobs\new_seller_student($userid,$uid,$posterTag,$phone,$origin,$subject);
            $job->handle();
        }

        $this->t_user_log->add_data("新增例子");
        return $this->output_succ();
    }


    //助教主管新增例子
    public function add_ss_ass_new() {
        $phone    = $this->get_in_phone();
        $origin   = $this->get_in_str_val("origin");
        $name     = $this->get_in_str_val("name");
        $grade    = $this->get_in_grade();
        $subject  = $this->get_in_subject();
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", 0);
        $origin_userid = $this->get_in_int_val("origin_userid", 1);
        $account = $this->get_account();

        if (strlen($phone )!=11 && $account!="adrian") {
            return $this->output_err("电话号码长度不对");
        }

        if($admin_revisiterid==0){
            return $this->output_err("请选择助教!");
        }

        $ret = $this->t_student_info->get_student_info_by_phone($phone);
        if($ret){
            return $this->output_err('此账号已经注册');
        }

        $userid=$this->t_phone_to_user->get_userid_by_phone($phone);
        if ($userid && $this->t_seller_student_new->get_phone($userid)) {

            $admin_nick=$this->cache_get_account_nick(
                $this->t_seller_student_new->get_admin_revisiterid($userid)
            );
            return $this->output_err("系统中已有这个人的账号了,销售负责人:$admin_nick");
        }
        if ($this->t_test_lesson_subject->check_subject($userid,$subject))  {
            return $this->output_err("已经有了这个科目的例子了,不能增加");
        }

        $userid=$this->t_seller_student_new->book_free_lesson_new("",$phone,$grade,$origin,$subject,0);

        //直接分配给助教
        $master_adminid = $this->t_admin_group_user-> get_master_adminid( $admin_revisiterid );
        if(empty($master_adminid)){
            $master_adminid=396;
        }
        $main_master_adminid = $this->t_admin_group_user->get_main_master_adminid( $admin_revisiterid );
        if(empty($main_master_adminid)){
            $main_master_adminid=396;
        }

        $this->t_seller_student_new->field_update_list($userid,[
            "admin_revisiterid"  =>$admin_revisiterid,
            "admin_assign_time"  =>time(),
            "admin_assignerid"   =>$this->get_account_id(),
            "sub_assign_adminid_1"=>$main_master_adminid,
            "sub_assign_time_1"  =>time(),
            "sub_assign_adminid_2"=>$master_adminid,
            "sub_assign_time_2"  =>time(),
            "ass_leader_create_flag"=>1
        ]);
        if($origin_userid<=1){
            $origin_userid=1;
        }
        $this->t_student_info->field_update_list($userid,[
            "nick"  =>$name,
            "realname"=>$name,
            "origin_assistantid"=>$admin_revisiterid,
            "origin_userid"   =>$origin_userid
        ]);


        $account=$this->get_account();
        $this->t_book_revisit->add_book_revisit(
            $phone,
            "操作者: 状态:  新增例子  :$account ",
            "system"
        );

        return $this->output_succ();
    }

    public function set_level_b() {
        $userid_list_str= $this->get_in_str_val("userid_list");
        $origin_level= $this->get_in_e_origin_level();
        $userid_list=\App\Helper\Utils::json_decode_as_int_array($userid_list_str);
        if ( count($userid_list) ==0 ) {
            return $this->output_err("还没选择例子");
        }
        $this->t_seller_student_new->set_level_b($userid_list, $origin_level );

        // 添加操作日志
        $this->t_user_log->add_data("设置可抢");
        return $this->output_succ();
    }
    public function free_to_new_user() {
        $opt_type=$this->get_in_opt_type(0);
        $opt_adminid=$this->get_in_int_val("opt_adminid", 0);
        $userid_list_str= $this->get_in_str_val("userid_list");
        $userid_list=\App\Helper\Utils::json_decode_as_int_array($userid_list_str);
        if ( count($userid_list) ==0 ) {
            return $this->output_err("还没选择例子");
        }


        $account=$this->get_account();
        foreach ( $userid_list as $userid ) {
            $this->t_seller_student_new->free_to_new_user($userid,$account);
        }

        return $this->output_succ();
    }


    public function set_origin_list() {
        $opt_type=$this->get_in_opt_type(0);
        $origin=$this->get_in_str_val("origin");
        if (!$this->check_account_in_arr(["jim","amanda"]))  {
            return $this->output_err("没有权限");
        }

        $userid_list_str= $this->get_in_str_val("userid_list");
        $userid_list=\App\Helper\Utils::json_decode_as_int_array($userid_list_str);
        if ( count($userid_list) ==0 ) {
            return $this->output_err("还没选择例子");
        }

        $this->t_student_info->update_origin_list($userid_list,$origin) ;

        // 添加操作日志
        $this->t_user_log->add_data("设置渠道");
        return $this->output_succ();
    }

    public function set_admin_id_ex ( $userid_list,  $opt_adminid, $opt_type) {
        if ( count($userid_list) ==0 ) {
            return $this->output_err("还没选择例子");
        }
        $this->t_seller_student_new->set_admin_info(
            $opt_type, $userid_list,  $opt_adminid, $this->get_account_id() );

        $account=$this->get_account();
        $opt_account=$this->t_manager_info->get_account($opt_adminid);

        foreach ( $userid_list as $userid ) {
            $phone=$this->t_seller_student_new->get_phone($userid);
            if($opt_type==0) { //set admin
                $ret_update = $this->t_book_revisit->add_book_revisit(
                    $phone,
                    "操作者: $account 状态: 分配给组员 [ $opt_account ] ",
                    "system"
                );
                $this->t_id_opt_log->add(E\Edate_id_log_type::V_SELLER_ASSIGNED_COUNT
                                         ,$opt_adminid,$userid);
            }else if($opt_type==1) { //set admin
                $ret_update = $this->t_book_revisit->add_book_revisit(
                    $phone,
                    "操作者: $account 状态: 分配给主管 [ $opt_account ] ",
                    "system"
                );

            }else if($opt_type==2) { //set admin
                $ret_update = $this->t_book_revisit->add_book_revisit(
                    $phone,
                    "操作者: $account 状态: 分配给TMK [ $opt_account ] ",
                    "system"
                );
            }
        }
    }


    public function set_adminid() {
        $opt_type=$this->get_in_opt_type(0);
        $opt_adminid=$this->get_in_int_val("opt_adminid", 0);
        $userid_list_str= $this->get_in_str_val("userid_list");
        $userid_list=\App\Helper\Utils::json_decode_as_int_array($userid_list_str);
        $seller_resource_type = $this->get_in_int_val('seller_resource_type');
        $assign_time =$this->get_in_unixtime_from_str("assign_time");
        if ( count($userid_list) ==0 ) {
            return $this->output_err("还没选择例子");
        }

        $account=$this->get_account();
        $opt_account=$this->t_manager_info->get_account($opt_adminid);

        $this->t_manager_info->send_wx_todo_msg( $opt_account ,"来自:".$account, "分配给你". count($userid_list)."个例子"  );

        foreach ( $userid_list as $userid ) {
            $origin = $this->t_student_info->field_get_value($userid, 'origin');
            if($origin == '学校-180112'){
                return $this->output_err('学校渠道不能分配!');
            }
            $this->t_seller_student_new->set_admin_info_new(
               $opt_type, $userid,  $opt_adminid, $this->get_account_id(), $opt_account, $account, $assign_time);
            $origin_assistantid= $this->t_student_info->get_origin_assistantid($userid);
            $nick = $this->t_student_info->get_nick($userid);
            $account_role = $this->t_manager_info->get_account_role($origin_assistantid);
            if($opt_type==0 && $account_role==1 && $origin_assistantid>0){
                $phone = $this->t_manager_info->get_phone($opt_adminid);
                $ass_account = $this->t_manager_info->get_account($origin_assistantid);
                $this->t_manager_info->send_wx_todo_msg($ass_account,"转介绍学生分配销售","学生:".$nick,"您的转介绍学生".$nick."已分配给销售:".$opt_account.",联系电话:".$phone,""  );
                $this->t_manager_info->send_wx_todo_msg("jack","转介绍学生分配销售","学生:".$nick,"您的转介绍学生".$nick."已分配给销售:".$opt_account.",联系电话:".$phone,""  );
            }
            //分配日志
            $this->t_seller_edit_log->row_insert([
                'adminid'=>$this->get_account_id(),//分配人
                'uid'=>$opt_adminid,//被分配人
                'new'=>$userid,//例子
                'type'=>E\Eseller_edit_log_type::V_3,
                'create_time'=>time(NULL),
            ]);
            //分配次数
            $opt_account_role = $this->t_manager_info->get_account_role($opt_adminid);
            if($opt_account_role == E\Eaccount_role::V_2){
                $distribution_count = $this->t_seller_student_new->field_get_value($userid, 'distribution_count');
                $this->t_seller_student_new->field_update_list($userid, ['distribution_count'=>$distribution_count+1]);
            }
        }

        return $this->output_succ();
    }

    public function set_accept_adminid() {
        $accept_adminid=$this->get_in_int_val("accept_adminid", 0);
        $require_id_list_str= $this->get_in_str_val("require_id_list");
        $require_id_list=\App\Helper\Utils::json_decode_as_int_array($require_id_list_str);
        if ( count($require_id_list) ==0 ) {
            return $this->output_err("还没选择课");
        }

        $require_assign_adminid=$this->get_account_id();
        $require_assign_time = time();

        foreach ( $require_id_list as $require_id ) {
            $this->t_test_lesson_subject_require->field_update_list($require_id,["require_assign_adminid"=>$require_assign_adminid,"require_assign_time"=>$require_assign_time,"accept_adminid"=>$accept_adminid]);
        }

        return $this->output_succ();
    }

    public function  get_user_info() {
        $userid= $this->get_in_userid();
        $test_lesson_subject_id = $this->get_in_test_lesson_subject_id();
        $require_id = $this->get_in_int_val('require_id');
        $student = $this->t_student_info->field_get_list($userid, "*");
        /*
        if (  @$student["lesson_count_left"] >100 ) {
            return $this->
        }
        */
        //$this->t_admin_group->
        $ss_item = $this->t_seller_student_new->field_get_list($userid,"*");

        $tt_item = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"*");
        $tr_item = $this->t_test_lesson_subject_require->field_get_list($require_id,"*");

        $ret["test_lesson_count"]   = $this->t_lesson_info_b2-> get_test_lesson_count($userid);

        $stu_nick=$student["nick"];
        $ret["stu_nick"]   = $stu_nick;
        $ret["par_nick"]   = $student["parent_name"];
        $ret["par_type"]   = $student["parent_type"];
        $ret["gender"]     = $student["gender"];
        $ret["grade"]      = $student["grade"];
        $ret["user_agent"] = $student["user_agent"];
        E\Egrade::set_item_value_str($ret);
        $ret["subject"]   = $tt_item["subject"];
        E\Esubject::set_item_value_str($ret);

        $ret["has_pad"]   = $ss_item["has_pad"];
        $ret["status"]    = $tt_item["seller_student_status"];
        $ret["seller_student_sub_status"]    = $tt_item["seller_student_sub_status"];
        $ret["user_desc"] = $ss_item["user_desc"];
        $ret["origin"]    = $student["origin"];
        $ret["editionid"] = $student["editionid"];
        $ret["school"]    = $student["school"];
        $ret["next_revisit_time"]=\App\Helper\Utils::unixtime2date(
            $ss_item["next_revisit_time"],
            'Y-m-d H:i'
        );
        $ret["stu_test_ipad_flag"] = $ss_item["stu_test_ipad_flag"];
        $ret["not_test_ipad_reason"] = $ss_item["not_test_ipad_reason"];
        $ret["address"]=$student["address"];

        $ret["stu_score_info"]    = $ss_item["stu_score_info"];
        $ret["stu_character_info"]    = $ss_item["stu_character_info"];
        $ret["stu_test_lesson_level"]    = $tt_item["stu_test_lesson_level"];
        $ret["stu_request_test_lesson_time_info"]    = $tt_item["stu_request_test_lesson_time_info"];
        $ret["stu_request_lesson_time_info"]    = $tt_item["stu_request_lesson_time_info"];
        $ret["stu_test_ipad_flag"]    = $ss_item["stu_test_ipad_flag"];
        $ret["stu_request_test_lesson_time"]    = \App\Helper\Utils::unixtime2date($tt_item["stu_request_test_lesson_time"], 'Y-m-d H:i');
        $ret["stu_request_test_lesson_time_end"]    = \App\Helper\Utils::unixtime2date($tt_item["stu_request_test_lesson_time_end"], 'Y-m-d H:i');
        $ret["stu_request_test_lesson_demand"]    = $tt_item["stu_request_test_lesson_demand"];
        $ret["province"]                          = $student["province"];
        $ret["city"]                              = $student["city"];
        $ret["area"]                              = $student["area"];
        $ret["region"]                            = $student["region"];

        //新增加信息
        $ret["class_rank"]    = $ss_item["class_rank"];
        $ret["grade_rank"]    = $ss_item["grade_rank"];
        $ret["academic_goal"]    = $ss_item["academic_goal"];
        $ret["test_stress"]    = $ss_item["test_stress"];
        $ret["entrance_school_type"]    = $ss_item["entrance_school_type"];
        $ret["interest_cultivation"]    = $ss_item["interest_cultivation"];
        $ret["extra_improvement"]    = $ss_item["extra_improvement"];
        $ret["habit_remodel"]    = $ss_item["habit_remodel"];
        $ret["study_habit"]    = $ss_item["study_habit"];
        $ret["interests_and_hobbies"]    = $ss_item["interests_and_hobbies"];
        $ret["character_type"]    = $ss_item["character_type"];
        $ret["need_teacher_style"]    = $ss_item["need_teacher_style"];
        $ret["demand_urgency"]    = $tt_item["demand_urgency"];
        $ret["quotation_reaction"]    = $tt_item["quotation_reaction"];
        $ret["knowledge_point_location"]    = $tt_item["knowledge_point_location"];
        $ret["recent_results"]    = $tt_item["recent_results"];
        $ret["advice_flag"]    = $tt_item["advice_flag"];
        $ret["stu_test_paper"]    = $tt_item["stu_test_paper"];
        $ret["intention_level"]    = $tt_item["intention_level"];
        $ret["new_demand_flag"]    = $ss_item["new_demand_flag"];
        $ret["tea_province"] = $tt_item["tea_province"];
        $ret["tea_city"] = $tt_item["tea_city"];
        $ret["tea_area"] = $tt_item["tea_area"];
        $ret["tea_identity"] = $tt_item["tea_identity"];
        $ret["tea_age"] = $tt_item["tea_age"];
        $ret["tea_gender"] = $tt_item["tea_gender"];
        $ret["stu_test_paper"] = $tt_item["stu_test_paper"];
        $ret["ass_test_lesson_type"] = $tt_item["ass_test_lesson_type"];
        $ret["change_teacher_reason_type"] = $tr_item["change_teacher_reason_type"];
        $ret["change_teacher_reason_img_url"] = $tr_item["change_teacher_reason_img_url"];
        $ret["change_teacher_reason"] = $tr_item["change_teacher_reason"];
        $ret["green_channel_teacherid"] = $tr_item["green_channel_teacherid"];
        $ret["learning_situation"]    = $tt_item["learning_situation"];
        $ret["subject_score"] = $ss_item['subject_score'];
        $ret["subject_tag"] = json_decode($tt_item['subject_tag']);
        $ret["phone_location"] = mb_substr($student["phone_location"],0,2);
        $ret["teacher_type"]   = $tt_item["teacher_type"];

        return $this->output_succ(["data" => $ret ]);
    }

    public function  ass_get_user_info(){
        $userid= $this->get_in_userid();
        $student = $this->t_student_info->field_get_list($userid, "*");
        $ss_item = $this->t_seller_student_new->field_get_list($userid,"*");
        $ret["test_lesson_count"] = $this->t_lesson_info_b2->get_test_lesson_count($userid);
        $ret["stu_nick"]       = $student["nick"];
        $ret["par_nick"]       = $student["parent_name"];
        $ret["par_type"]       = $student["parent_type"];
        $ret["gender"]         = $student["gender"];
        $ret["grade"]          = $student["grade"];
        $ret["user_agent"]     = $student["user_agent"];
        $ret["origin"]         = $student["origin"];
        $ret["editionid"]      = $student["editionid"];
        $ret["school"]         = $student["school"];
        $ret["address"]        = $student["address"];
        $ret["province"]       = $student["province"];
        $ret["city"]           = $student["city"];
        $ret["area"]           = $student["area"];
        $ret["region"]         = $student["region"];
        $ret["phone_location"] = mb_substr($student["phone_location"],0,2);

        $ret["has_pad"]               = $ss_item["has_pad"];
        $ret["user_desc"]             = $ss_item["user_desc"];
        $ret["stu_test_ipad_flag"]    = $ss_item["stu_test_ipad_flag"];
        $ret["not_test_ipad_reason"]  = $ss_item["not_test_ipad_reason"];
        $ret["stu_score_info"]        = $ss_item["stu_score_info"];
        $ret["stu_character_info"]    = $ss_item["stu_character_info"];
        $ret["stu_test_ipad_flag"]    = $ss_item["stu_test_ipad_flag"];
        $ret["class_rank"]            = $ss_item["class_rank"];
        $ret["grade_rank"]            = $ss_item["grade_rank"];
        $ret["academic_goal"]         = $ss_item["academic_goal"];
        $ret["test_stress"]           = $ss_item["test_stress"];
        $ret["entrance_school_type"]  = $ss_item["entrance_school_type"];
        $ret["interest_cultivation"]  = $ss_item["interest_cultivation"];
        $ret["extra_improvement"]     = $ss_item["extra_improvement"];
        $ret["habit_remodel"]         = $ss_item["habit_remodel"];
        $ret["study_habit"]           = $ss_item["study_habit"];
        $ret["interests_and_hobbies"] = $ss_item["interests_and_hobbies"];
        $ret["character_type"]        = $ss_item["character_type"];
        $ret["need_teacher_style"]    = $ss_item["need_teacher_style"];
        $ret["new_demand_flag"]       = $ss_item["new_demand_flag"];
        $ret["subject_score"]         = $ss_item['subject_score'];
        $ret["next_revisit_time"]     = \App\Helper\Utils::unixtime2date(
            $ss_item["next_revisit_time"],
            'Y-m-d H:i'
        );
        E\Egrade::set_item_value_str($ret);
        E\Esubject::set_item_value_str($ret);

        return $this->output_succ(["data" => $ret ]);
    }

    public function ass_save_user_info()
    {
        $userid                         = $this->get_in_userid();
        $phone                          = $this->get_in_phone();
        $test_lesson_subject_id         = $this->get_in_test_lesson_subject_id();
        $stu_nick                       = $this->get_in_str_val("stu_nick");
        $parent_name                    = $this->get_in_str_val("parent_name");
        $gender                         = $this->get_in_str_val("gender");
        $grade                          = $this->get_in_str_val("grade");
        $subject                        = $this->get_in_str_val("subject");
        $editionid                      = $this->get_in_int_val("editionid");
        $school                         = $this->get_in_str_val("school");
        $stu_request_test_lesson_time   = $this->get_in_str_val("stu_request_test_lesson_time");
        $stu_request_test_lesson_demand = $this->get_in_str_val("stu_request_test_lesson_demand");
        $ass_test_lesson_type    = $this->get_in_int_val("ass_test_lesson_type");
        $origin_userid = $this->get_in_int_val("origin_userid");
        $require_id = $this->get_in_int_val("require_id");

        $change_reason = trim($this->get_in_str_val('change_reason'));
        $change_teacher_reason_type = $this->get_in_int_val('change_teacher_reason_type');
        $url = $this->get_in_str_val('change_reason_url');

        if($ass_test_lesson_type == 2 && $change_teacher_reason_type == 0){
            return $this->output_err('请选择换老师类型!');
        }elseif($ass_test_lesson_type == 2 && !$change_reason){
            return $this->output_err('请填写换老师原因!');
        }elseif($ass_test_lesson_type == 2 && strlen(str_replace(" ","",$change_reason))<9){
            return $this->output_err('换老师原因不得少于3个字!');
        }

        if($url){
            if(preg_match('/http/i',$url)){
                $change_reason_url = $url;
            }else{
                $domain = config('admin')['qiniu']['public']['url'];
                $change_reason_url = $domain.'/'.$url;
            }
        }else{
            $change_reason_url = '';
        }

        if ($stu_request_test_lesson_time) {
            $stu_request_test_lesson_time=strtotime( $stu_request_test_lesson_time);
        } else {
            $stu_request_test_lesson_time=0;
        }

        $stu_arr=[
            "nick"        => $stu_nick,
            "parent_name" => $parent_name,
            "gender"      => $gender,
            "editionid"   => $editionid,
            "school"      => $school,
        ];

        $this->t_student_info->field_update_list($userid,$stu_arr);

        $tt_arr=[
            "stu_request_test_lesson_time"   => $stu_request_test_lesson_time,
            "stu_request_test_lesson_demand" => $stu_request_test_lesson_demand,
            "ass_test_lesson_type" => $ass_test_lesson_type,
            "subject" => $subject,
        ];

        $ret= $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,$tt_arr);

        // dd($ret);
        $require_arr = [
            "test_stu_request_test_lesson_demand"=>$stu_request_test_lesson_demand,
            "curl_stu_request_test_lesson_time" =>$stu_request_test_lesson_time,
            "change_teacher_reason"          => $change_reason,
            "change_teacher_reason_img_url"  => $change_reason_url,
            "change_teacher_reason_type" => $change_teacher_reason_type,
            "test_stu_grade"   => $grade,
        ];

        $this->t_test_lesson_subject_require->field_update_list($require_id,$require_arr);

        return $this->output_succ();
    }

    public function save_user_info()
    {
        $userid                 = $this->get_in_userid();
        $phone                  = $this->get_in_phone();
        $test_lesson_subject_id = $this->get_in_test_lesson_subject_id();

        $grade         = $this->get_in_grade();
        $gender        = $this->get_in_int_val("gender");
        $address       = $this->get_in_str_val("address");
        $stu_nick      = $this->get_in_str_val("stu_nick");
        $par_nick      = $this->get_in_str_val("par_nick");
        $editionid     = $this->get_in_int_val("editionid");
        $school        = $this->get_in_str_val("school");


        $has_pad       = $this->get_in_int_val("has_pad");
        $intention_level       = $this->get_in_int_val("intention_level");
        $user_desc     = $this->get_in_str_val("user_desc");
        $next_revisit_time     = $this->get_in_str_val("next_revisit_time");
        $stu_test_ipad_flag    = $this->get_in_str_val("stu_test_ipad_flag");
        $stu_score_info        = $this->get_in_str_val("stu_score_info");
        $stu_character_info    = $this->get_in_str_val("stu_character_info");
        $seller_student_sub_status= $this->get_in_int_val("seller_student_sub_status");


        $subject       = $this->get_in_subject();
        $seller_student_status = $this->get_in_int_val("seller_student_status");
        $stu_request_test_lesson_time = $this->get_in_str_val("stu_request_test_lesson_time");
        $stu_request_test_lesson_time_info = $this->get_in_str_val("stu_request_test_lesson_time_info");
        $stu_request_lesson_time_info      = $this->get_in_str_val("stu_request_lesson_time_info");
        $stu_request_test_lesson_demand    = $this->get_in_str_val("stu_request_test_lesson_demand");
        $stu_test_lesson_level = $this->get_in_str_val("stu_test_lesson_level");


        $revisite_info = trim($this->get_in_str_val("revisite_info"));
        if ($next_revisit_time) {
            $next_revisit_time =strtotime($next_revisit_time);
        } else {
            $next_revisit_time =0;
        }

        $diff=$next_revisit_time-time();

        if ( $next_revisit_time==0 ) {
            if (session( "account_role") ==E\Eaccount_role::V_2  ) {
                return $this->output_err("下次回访时间 需要设置");
            }
        }else if ( $diff > 7*86400 ) {
            return $this->output_err("下次回访时间只能设置最近一周时间");
        }else if (  $diff<0 ) {
            return $this->output_err("下次回访时间不能早于当前");
        }

        if ($stu_request_test_lesson_time) {
            $stu_request_test_lesson_time=strtotime( $stu_request_test_lesson_time);
        } else {
            $stu_request_test_lesson_time=0;
        }

        $db_tt_item=$this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject,seller_student_status, stu_request_test_lesson_time ,stu_request_test_lesson_demand");

        if ( $db_tt_item["seller_student_status"] ==  E\Eseller_student_status::V_200  &&
             $db_tt_item["stu_request_test_lesson_time"] != $stu_request_test_lesson_time
        )  {
            return $this->output_err("预约-未排课，不能修改时间,可以取消");
        }


        $stu_arr=[
            "gender"      => $gender,
            "address"     => $address,
            "nick"        => $stu_nick,
            "parent_name" => $par_nick,
            "editionid"   => $editionid,
            "school"      => $school,
        ];
        $this->cache_del_student_nick($userid);

        //"grade" =>$grade,
        $db_grade=$this->t_student_info->get_grade($userid);
        if ($db_grade!= $grade) {
            if($this->t_order_info->has_1v1_order($userid)) {
                return $this->output_err("有合同了,不能修改年级");
            }else{
                $stu_arr["grade"] = $grade ;

            }
        }

        $this->t_student_info->field_update_list($userid,$stu_arr);
        if($db_grade!= $grade && !$this->t_order_info->has_1v1_order($userid)){
            $this->t_book_revisit->row_insert([
                "phone"  => $phone,
                "revisit_time"  =>time(),
                "operator_note" =>"年级 [". E\Egrade::get_desc($db_grade) ."]=>[". E\Egrade::get_desc($grade) ."]",
                "sys_operator"  =>$this->get_account()
            ]);
            $this->t_field_modified_list->row_insert([
                "modified_time"  =>time(),
                "last_value"     =>$db_grade,
                "cur_value"      =>$grade,
                "adminid"        =>$this->get_account_id(),
                "t_name"         =>"t_student_info",
                "f_name"         =>"grade",
                "userid"         =>$userid
            ]);
        }

        $ss_item = $this->t_seller_student_new->field_get_list($userid,"*");
        if ( $ss_item["user_desc"] != $user_desc) {
            $this->t_book_revisit->add_book_revisit($phone , "更新备注:$user_desc" , $this->get_account());
        }

        if ($db_tt_item["stu_request_test_lesson_demand"] != $stu_request_test_lesson_demand) {
            $this->t_book_revisit->add_book_revisit($phone , "更新试听需求:$stu_request_test_lesson_demand" , $this->get_account());

        }

        if ($ss_item["stu_score_info"] != $stu_score_info) {
            $this->t_book_revisit->add_book_revisit($phone , "更新成绩情况:$stu_score_info" , $this->get_account());

        }

        if ($ss_item["stu_character_info"] != $stu_character_info) {
            $this->t_book_revisit->add_book_revisit($phone , "更新性格特点:$stu_character_info" , $this->get_account());

        }





        //last_revisit_msg ='%s', last_revisit_time =%u
        $ss_arr=[
            "has_pad" =>$has_pad,
            "user_desc" =>$user_desc,
            "next_revisit_time" =>$next_revisit_time,
            "stu_test_ipad_flag" =>$stu_test_ipad_flag,
            "stu_score_info" =>$stu_score_info,
            "stu_character_info" =>$stu_character_info,
        ];

        if ($db_tt_item["seller_student_status"] != $seller_student_status && $ss_item["seller_resource_type"] ==0 ) {
            $ss_arr["first_seller_status"]=$seller_student_status;
        }

        //更新首次回访时间
        if (! $ss_item["first_revisit_time"])  {
            $ss_arr["first_revisit_time"]=time(NULL);
        }
        if ( $revisite_info  ) {
            $ss_arr["last_revisit_time"]=time(NULL);
            $ss_arr["last_revisit_msg"]=$revisite_info;
            $this->t_book_revisit->add_book_revisit($phone , $revisite_info, $this->get_account());
        }


        $this->t_seller_student_new->field_update_list($userid,$ss_arr);


        $textbook = E\Eregion_version::get_desc($editionid);
        $tt_arr=[
            "subject" =>$subject,
            "stu_request_test_lesson_time" =>$stu_request_test_lesson_time,
            "stu_request_test_lesson_time_info" =>$stu_request_test_lesson_time_info,
            "stu_request_lesson_time_info" =>$stu_request_lesson_time_info,
            "stu_request_test_lesson_demand" =>$stu_request_test_lesson_demand,
            "stu_test_lesson_level" =>$stu_test_lesson_level,
            "seller_student_sub_status" => $seller_student_sub_status,
            "textbook"                  => $textbook,
            "intention_level"                    => $intention_level
        ];

        if ($db_tt_item["subject"] != $subject ) { //和数据库不一致

            $require_count=$this->t_test_lesson_subject_require->get_count_by_test_lesson_subject_id($test_lesson_subject_id );
            if ($require_count>0) {
                return $this->output_err("已有试听申请,不能修改科目");
            }
            if($this->t_test_lesson_subject->check_subject($userid,$subject)){
                return $this->output_err("已经有该科目了" );
            }
            $tt_arr["subject"]=$subject;
        }

        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,$tt_arr);

        //更新 seller_student_status
        if ($db_tt_item["seller_student_status"] != $seller_student_status) {
            $this->t_test_lesson_subject->set_seller_student_status( $test_lesson_subject_id, $seller_student_status,  $this->get_account() );
        }

        if($seller_student_status==420){
            $this->t_student_info->field_update_list($userid,[
               "type" =>0
            ]);
        }

        $current_require_id  =  $this->t_test_lesson_subject->get_current_require_id($test_lesson_subject_id);
        if($current_require_id>0){
            $this->t_test_lesson_subject_require->field_update_list($current_require_id,[
                "test_stu_request_test_lesson_demand"=> $stu_request_test_lesson_demand,
            ]);
        }
        return $this->output_succ();
    }

    public function save_user_info_new()
    {
        $userid                 = $this->get_in_userid();
        $phone                  = $this->get_in_phone();
        $test_lesson_subject_id = $this->get_in_test_lesson_subject_id();
        if ($phone == "") {
            $phone=$this->t_seller_student_new->get_phone($userid);
        }

        $grade         = $this->get_in_grade();
        $gender        = $this->get_in_int_val("gender");
        $address       = $this->get_in_str_val("address");
        $stu_nick      = $this->get_in_str_val("stu_nick");
        $par_nick      = $this->get_in_str_val("par_nick");
        $editionid     = $this->get_in_int_val("editionid");
        $school        = $this->get_in_str_val("school");


        $has_pad       = $this->get_in_int_val("has_pad");
        $intention_level       = $this->get_in_int_val("intention_level");//上课意向
        $user_desc     = $this->get_in_str_val("user_desc");
        $next_revisit_time     = $this->get_in_str_val("next_revisit_time");
        $stu_test_ipad_flag    = $this->get_in_str_val("stu_test_ipad_flag");
        // $stu_score_info        = $this->get_in_str_val("stu_score_info");
        // $stu_character_info    = $this->get_in_str_val("stu_character_info");
        $seller_student_sub_status= $this->get_in_int_val("seller_student_sub_status");


        $subject       = $this->get_in_subject();
        $seller_student_status = $this->get_in_int_val("seller_student_status");
        $stu_request_test_lesson_time = $this->get_in_str_val("stu_request_test_lesson_time");
        // $stu_request_test_lesson_time_info = $this->get_in_str_val("stu_request_test_lesson_time_info");
        // $stu_request_lesson_time_info      = $this->get_in_str_val("stu_request_lesson_time_info");
        $stu_request_test_lesson_demand    = $this->get_in_str_val("stu_request_test_lesson_demand");
        // $stu_test_lesson_level = $this->get_in_str_val("stu_test_lesson_level");


        // $revisite_info = trim($this->get_in_str_val("revisite_info"));

        //新增字段
        $class_rank     = $this->get_in_str_val("class_rank");//班级排名
        $grade_rank     = $this->get_in_str_val("grade_rank");//年级排名
        $academic_goal  = $this->get_in_int_val("academic_goal");//升学目标
        $test_stress    = $this->get_in_int_val("test_stress");//应试压力
        $new_demand_flag    = $this->get_in_int_val("new_demand_flag");//试听需求新版本标识
        $entrance_school_type  = $this->get_in_int_val("entrance_school_type");//升学目标
        $interest_cultivation  = $this->get_in_int_val("interest_cultivation");//趣味培养
        $extra_improvement  = $this->get_in_int_val("extra_improvement");//课外提高
        $habit_remodel  = $this->get_in_int_val("habit_remodel");//习惯重塑
        $study_habit     = $this->get_in_str_val("study_habit");//学习习惯
        $interests_and_hobbies     = $this->get_in_str_val("interests_and_hobbies");//兴趣爱好
        $character_type     = $this->get_in_str_val("character_type");//学习习惯
        $need_teacher_style     = $this->get_in_str_val("need_teacher_style");//所需老师风格
        $demand_urgency    = $this->get_in_int_val("demand_urgency");//需求急迫性
        $quotation_reaction    = $this->get_in_int_val("quotation_reaction");//报价反应
        $advice_flag    = $this->get_in_int_val("advice_flag");//是否进步
        $knowledge_point_location     = trim($this->get_in_str_val("knowledge_point_location"));//知识点定位
        $recent_results      = $this->get_in_str_val("recent_results");//近期成绩
        $city      = $this->get_in_str_val("city");//市.区
        $area      = $this->get_in_str_val("area");//县市
        $region      = $this->get_in_str_val("region");//地区,省
        $province      = $this->get_in_int_val("province");//省
        $stu_test_paper      = $this->get_in_str_val("test_paper");//地区,省


        /**
         * 需求急迫性|上课意向|报价反应 为必填项
         **/

        // if($demand_urgency == 0){ return $this->output_err("请选择需求急迫性");}
        // if($quotation_reaction == 0){ return $this->output_err("请选择报价反应");}
        if($intention_level == 0){ return $this->output_err("请选择上课意向");}

        if ($next_revisit_time) {
            $next_revisit_time =strtotime($next_revisit_time);
        } else {
            $next_revisit_time =0;
        }

        $diff=$next_revisit_time-time();

        if ( $next_revisit_time==0 ) {
            if (session( "account_role") ==E\Eaccount_role::V_2  ) {
                return $this->output_err("下次回访时间 需要设置");
            }
        }else if ( $diff > 7*86400 ) {
            return $this->output_err("下次回访时间只能设置最近一周时间");
        }else if (  $diff<0 ) {
            return $this->output_err("下次回访时间不能早于当前");
        }

        if ($stu_request_test_lesson_time) {
            $stu_request_test_lesson_time=strtotime( $stu_request_test_lesson_time);
        } else {
            $stu_request_test_lesson_time=0;
        }

        $db_tt_item=$this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject,seller_student_status, stu_request_test_lesson_time ,stu_request_test_lesson_demand");

        if ( $db_tt_item["seller_student_status"] ==  E\Eseller_student_status::V_200  &&
             $db_tt_item["stu_request_test_lesson_time"] != $stu_request_test_lesson_time
        )  {
            return $this->output_err("预约-未排课，不能修改时间,可以取消");
        }


        $stu_arr=[
            "gender"      => $gender,
            "address"     => $address,
            "nick"        => $stu_nick,
            "parent_name" => $par_nick,
            "editionid"   => $editionid,
            "school"      => $school
        ];
        $this->cache_del_student_nick($userid);
        if($region){
            $stu_arr["region"]=$region;
            $stu_arr["province"]=$province;
        }
        if($city){
            $stu_arr["city"]=$city;
        }
        if($area){
            $stu_arr["area"]=$area;
        }

        //"grade" =>$grade,
        $db_grade=$this->t_student_info->get_grade($userid);
        if ($db_grade!= $grade) {
            if($this->t_order_info->has_1v1_order($userid)) {
                return $this->output_err("有合同了,不能修改年级");
            }else{
                $stu_arr["grade"] = $grade ;
            }
        }

        $this->t_student_info->field_update_list($userid,$stu_arr);
        if($db_grade!= $grade && !$this->t_order_info->has_1v1_order($userid)){
            $revisite_info="年级 [". E\Egrade::get_desc($db_grade) ."]=>[". E\Egrade::get_desc($grade) ."]";

            $this->t_book_revisit->add_book_revisit($phone , $revisite_info, $this->get_account());
            $this->t_field_modified_list->row_insert([
                "modified_time"  =>time(),
                "last_value"     =>$db_grade,
                "cur_value"      =>$grade,
                "adminid"        =>$this->get_account_id(),
                "t_name"         =>"t_student_info",
                "f_name"         =>"grade",
                "userid"         =>$userid
            ]);
        }

        $ss_item = $this->t_seller_student_new->field_get_list($userid,"*");
        if ( $ss_item["user_desc"] != $user_desc) {
            $this->t_book_revisit->add_book_revisit($phone , "更新备注:$user_desc" , $this->get_account());
        }

        /* if ($db_tt_item["stu_request_test_lesson_demand"] != $stu_request_test_lesson_demand) {
            $this->t_book_revisit->add_book_revisit($phone , "更新试听需求:$stu_request_test_lesson_demand" , $this->get_account());

            }

        if ($ss_item["stu_score_info"] != $stu_score_info) {
            $this->t_book_revisit->add_book_revisit($phone , "更新成绩情况:$stu_score_info" , $this->get_account());

        }

        if ($ss_item["stu_character_info"] != $stu_character_info) {
            $this->t_book_revisit->add_book_revisit($phone , "更新性格特点:$stu_character_info" , $this->get_account());

            }*/





        //last_revisit_msg ='%s', last_revisit_time =%u
        $ss_arr=[
            "has_pad" =>$has_pad,
            "user_desc" =>$user_desc,
            "next_revisit_time" =>$next_revisit_time,
            "stu_test_ipad_flag" =>$stu_test_ipad_flag,
            //  "stu_score_info" =>$stu_score_info,
            // "stu_character_info" =>$stu_character_info,
            "class_rank"   =>$class_rank,
            "grade_rank"   =>$grade_rank,
            "academic_goal"   =>$academic_goal,
            "test_stress"   =>$test_stress,
            "entrance_school_type"   =>$entrance_school_type,
            "interest_cultivation"   =>$interest_cultivation,
            "extra_improvement"   =>$extra_improvement,
            "habit_remodel"   =>$habit_remodel,
            "study_habit"   =>$study_habit,
            "interests_and_hobbies"   =>$interests_and_hobbies,
            "character_type"   =>$character_type,
            "need_teacher_style"   =>$need_teacher_style,
            "new_demand_flag"   =>1,
        ];

        if ($db_tt_item["seller_student_status"] != $seller_student_status && $ss_item["seller_resource_type"] ==0 ) {
            $ss_arr["first_seller_status"]=$seller_student_status;
        }

        //更新首次回访时间
        if (! $ss_item["first_revisit_time"])  {
            $ss_arr["first_revisit_time"]=time(NULL);
        }
        if ( $user_desc  ) {
            $ss_arr["last_revisit_time"]=time(NULL);
            $ss_arr["last_revisit_msg"]=$user_desc;
            $this->t_book_revisit->add_book_revisit($phone , $user_desc, $this->get_account());
        }


        $this->t_seller_student_new->field_update_list($userid,$ss_arr);


        $textbook = E\Eregion_version::get_desc($editionid);
        $tt_arr=[
            "subject" =>$subject,
            "stu_request_test_lesson_time" =>$stu_request_test_lesson_time,
            // "stu_request_test_lesson_time_info" =>$stu_request_test_lesson_time_info,
            //  "stu_request_lesson_time_info" =>$stu_request_lesson_time_info,
           "stu_request_test_lesson_demand" =>$stu_request_test_lesson_demand,
            // "stu_test_lesson_level" =>$stu_test_lesson_level,
            "seller_student_sub_status" => $seller_student_sub_status,
            "textbook"                  => $textbook,
            "intention_level"                    => $intention_level,
            "demand_urgency"                     =>$demand_urgency,
            "quotation_reaction"                 =>$quotation_reaction,
            // "knowledge_point_location"           =>$knowledge_point_location,
            "recent_results"                     =>$recent_results,
            "advice_flag"                        =>$advice_flag,
            "stu_test_paper"                     =>$stu_test_paper
        ];

        if ($db_tt_item["subject"] != $subject ) { //和数据库不一致

            $require_count=$this->t_test_lesson_subject_require->get_count_by_test_lesson_subject_id($test_lesson_subject_id );
            if ($require_count>0) {
                return $this->output_err("已有试听申请,不能修改科目");
            }
            if($this->t_test_lesson_subject->check_subject($userid,$subject)){
                return $this->output_err("已经有该科目了" );
            }
            $tt_arr["subject"]=$subject;
        }

        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,$tt_arr);

        //更新 seller_student_status
        if ($db_tt_item["seller_student_status"] != $seller_student_status) {
            $this->t_test_lesson_subject->set_seller_student_status( $test_lesson_subject_id, $seller_student_status,  $this->get_account() );
        }

        if($seller_student_status==420){
            $this->t_student_info->field_update_list($userid,[
                "type" =>0
            ]);
        }

        $current_require_id  =  $this->t_test_lesson_subject->get_current_require_id($test_lesson_subject_id);
        if($current_require_id>0){
            $this->t_test_lesson_subject_require->field_update_list($current_require_id,[
                "test_stu_request_test_lesson_demand"=> $stu_request_test_lesson_demand,
            ]);
        }
        return $this->output_succ();
    }
    public function save_user_info_new_new()
    {
        $userid                 = $this->get_in_userid();
        $phone                  = $this->get_in_phone();
        $test_lesson_subject_id = $this->get_in_test_lesson_subject_id();
        if ($phone == "") {
            $phone=$this->t_seller_student_new->get_phone($userid);
        }

        $grade         = $this->get_in_grade();
        $gender        = $this->get_in_int_val("gender");
        $address       = $this->get_in_str_val("address");
        $stu_nick      = $this->get_in_str_val("stu_nick");
        $par_nick      = $this->get_in_str_val("par_nick");
        $editionid     = $this->get_in_int_val("editionid");
        $school        = $this->get_in_str_val("school");


        $has_pad       = $this->get_in_int_val("has_pad");
        $intention_level       = $this->get_in_int_val("intention_level");//上课意向
        $user_desc     = $this->get_in_str_val("user_desc");
        $next_revisit_time     = $this->get_in_str_val("next_revisit_time");
        $stu_test_ipad_flag    = $this->get_in_str_val("stu_test_ipad_flag");
        // $stu_score_info        = $this->get_in_str_val("stu_score_info");
        // $stu_character_info    = $this->get_in_str_val("stu_character_info");
        $seller_student_sub_status= $this->get_in_int_val("seller_student_sub_status");


        $subject       = $this->get_in_subject();
        $seller_student_status = $this->get_in_int_val("seller_student_status");
        $stu_request_test_lesson_time = $this->get_in_str_val("stu_request_test_lesson_time");
        // $stu_request_test_lesson_time_info = $this->get_in_str_val("stu_request_test_lesson_time_info");
        // $stu_request_lesson_time_info      = $this->get_in_str_val("stu_request_lesson_time_info");
        $stu_request_test_lesson_demand    = $this->get_in_str_val("stu_request_test_lesson_demand");
        // $stu_test_lesson_level = $this->get_in_str_val("stu_test_lesson_level");


        // $revisite_info = trim($this->get_in_str_val("revisite_info"));

        //新增字段
        $class_rank     = $this->get_in_str_val("class_rank");//班级排名
        $grade_rank     = $this->get_in_str_val("grade_rank");//年级排名
        $academic_goal  = $this->get_in_int_val("academic_goal");//升学目标
        $test_stress    = $this->get_in_int_val("test_stress");//应试压力
        $new_demand_flag    = $this->get_in_int_val("new_demand_flag");//试听需求新版本标识
        $entrance_school_type  = $this->get_in_int_val("entrance_school_type");//升学目标
        $interest_cultivation  = $this->get_in_int_val("interest_cultivation");//趣味培养
        $extra_improvement  = $this->get_in_int_val("extra_improvement");//课外提高
        $habit_remodel  = $this->get_in_int_val("habit_remodel");//习惯重塑
        $study_habit     = $this->get_in_str_val("study_habit");//学习习惯
        $interests_and_hobbies     = $this->get_in_str_val("interests_and_hobbies");//兴趣爱好
        $character_type     = $this->get_in_str_val("character_type");//学习习惯
        $need_teacher_style     = $this->get_in_str_val("need_teacher_style");//所需老师风格
        $demand_urgency    = $this->get_in_int_val("demand_urgency");//需求急迫性
        $quotation_reaction    = $this->get_in_int_val("quotation_reaction");//报价反应
        $advice_flag    = $this->get_in_int_val("advice_flag");//是否进步
        $knowledge_point_location     = trim($this->get_in_str_val("knowledge_point_location"));//知识点定位
        $recent_results      = $this->get_in_str_val("recent_results");//近期成绩
        $city      = $this->get_in_str_val("city");//市.区
        $area      = $this->get_in_str_val("area");//县市
        $region      = $this->get_in_str_val("region");//地区,省
        $province      = $this->get_in_int_val("province");//省
        $stu_test_paper      = $this->get_in_str_val("test_paper");//地区,省


        /**
         * 需求急迫性|上课意向|报价反应 为必填项
         **/

        // if($demand_urgency == 0){ return $this->output_err("请选择需求急迫性");}
        // if($quotation_reaction == 0){ return $this->output_err("请选择报价反应");}
        //if($intention_level == 0){ return $this->output_err("请选择上课意向");}

        if ($next_revisit_time) {
            $next_revisit_time =strtotime($next_revisit_time);
        } else {
            $next_revisit_time =0;
        }

        $diff=$next_revisit_time-time();

        if ( $next_revisit_time==0 ) {
            if (session( "account_role") ==E\Eaccount_role::V_2  ) {
                //return $this->output_err("下次回访时间 需要设置");
            }
        }else if ( $diff > 7*86400 ) {
            //return $this->output_err("下次回访时间只能设置最近一周时间");
        }else if (  $diff<0 ) {
            //return $this->output_err("下次回访时间不能早于当前");
        }

        if ($stu_request_test_lesson_time) {
            $stu_request_test_lesson_time=strtotime( $stu_request_test_lesson_time);
        } else {
            $stu_request_test_lesson_time=0;
        }

        $db_tt_item=$this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject,seller_student_status, stu_request_test_lesson_time ,stu_request_test_lesson_demand");

        if ( $db_tt_item["seller_student_status"] ==  E\Eseller_student_status::V_200  &&
             $db_tt_item["stu_request_test_lesson_time"] != $stu_request_test_lesson_time
        )  {
            return $this->output_err("预约-未排课，不能修改时间,可以取消");
        }


        $stu_arr=[
            "gender"      => $gender,
            "address"     => $address,
            "nick"        => $stu_nick,
            "parent_name" => $par_nick,
            "editionid"   => $editionid,
            "school"      => $school
        ];
        $this->cache_del_student_nick($userid);
        if($region){
            $stu_arr["region"]=$region;
            $stu_arr["province"]=$province;
        }
        if($city){
            $stu_arr["city"]=$city;
        }
        if($area){
            $stu_arr["area"]=$area;
        }

        //"grade" =>$grade,
        $db_grade=$this->t_student_info->get_grade($userid);
        if ($db_grade!= $grade) {
            if($this->t_order_info->has_1v1_order($userid)) {
                return $this->output_err("有合同了,不能修改年级");
            }else{
                $stu_arr["grade"] = $grade ;
            }
        }

        $this->t_student_info->field_update_list($userid,$stu_arr);
        if($db_grade!= $grade && !$this->t_order_info->has_1v1_order($userid)){
            $revisite_info="年级 [". E\Egrade::get_desc($db_grade) ."]=>[". E\Egrade::get_desc($grade) ."]";

            $this->t_book_revisit->add_book_revisit($phone , $revisite_info, $this->get_account());
            $this->t_field_modified_list->row_insert([
                "modified_time"  =>time(),
                "last_value"     =>$db_grade,
                "cur_value"      =>$grade,
                "adminid"        =>$this->get_account_id(),
                "t_name"         =>"t_student_info",
                "f_name"         =>"grade",
                "userid"         =>$userid
            ]);
        }

        $ss_item = $this->t_seller_student_new->field_get_list($userid,"*");
        if ( $ss_item["user_desc"] != $user_desc) {
            $this->t_book_revisit->add_book_revisit($phone , "[销售回访]更新备注:$user_desc" , $this->get_account());
        }

        /* if ($db_tt_item["stu_request_test_lesson_demand"] != $stu_request_test_lesson_demand) {
            $this->t_book_revisit->add_book_revisit($phone , "更新试听需求:$stu_request_test_lesson_demand" , $this->get_account());

            }

        if ($ss_item["stu_score_info"] != $stu_score_info) {
            $this->t_book_revisit->add_book_revisit($phone , "更新成绩情况:$stu_score_info" , $this->get_account());

        }

        if ($ss_item["stu_character_info"] != $stu_character_info) {
            $this->t_book_revisit->add_book_revisit($phone , "更新性格特点:$stu_character_info" , $this->get_account());

            }*/





        //last_revisit_msg ='%s', last_revisit_time =%u
        $ss_arr=[
            "has_pad" =>$has_pad,
            "user_desc" =>$user_desc,
            "next_revisit_time" =>$next_revisit_time,
            "stu_test_ipad_flag" =>$stu_test_ipad_flag,
            //  "stu_score_info" =>$stu_score_info,
            // "stu_character_info" =>$stu_character_info,
            "class_rank"   =>$class_rank,
            "grade_rank"   =>$grade_rank,
            "academic_goal"   =>$academic_goal,
            "test_stress"   =>$test_stress,
            "entrance_school_type"   =>$entrance_school_type,
            "interest_cultivation"   =>$interest_cultivation,
            "extra_improvement"   =>$extra_improvement,
            "habit_remodel"   =>$habit_remodel,
            "study_habit"   =>$study_habit,
            "interests_and_hobbies"   =>$interests_and_hobbies,
            "character_type"   =>$character_type,
            "need_teacher_style"   =>$need_teacher_style,
            "new_demand_flag"   =>1,
        ];

        if ($db_tt_item["seller_student_status"] != $seller_student_status && $ss_item["seller_resource_type"] ==0 ) {
            $ss_arr["first_seller_status"]=$seller_student_status;
        }

        //更新首次回访时间
        if (! $ss_item["first_revisit_time"])  {
            $ss_arr["first_revisit_time"]=time(NULL);
        }
        if ( $user_desc  ) {
            $ss_arr["last_revisit_time"]=time(NULL);
            $ss_arr["last_revisit_msg"]=$user_desc;
            $this->t_book_revisit->add_book_revisit($phone , $user_desc, $this->get_account());
        }


        $this->t_seller_student_new->field_update_list($userid,$ss_arr);


        $textbook = E\Eregion_version::get_desc($editionid);
        $tt_arr=[
            "subject" =>$subject,
            "stu_request_test_lesson_time" =>$stu_request_test_lesson_time,
            // "stu_request_test_lesson_time_info" =>$stu_request_test_lesson_time_info,
            //  "stu_request_lesson_time_info" =>$stu_request_lesson_time_info,
           "stu_request_test_lesson_demand" =>$stu_request_test_lesson_demand,
            // "stu_test_lesson_level" =>$stu_test_lesson_level,
            "seller_student_sub_status" => $seller_student_sub_status,
            "textbook"                  => $textbook,
            "intention_level"                    => $intention_level,
            "demand_urgency"                     =>$demand_urgency,
            "quotation_reaction"                 =>$quotation_reaction,
            // "knowledge_point_location"           =>$knowledge_point_location,
            "recent_results"                     =>$recent_results,
            "advice_flag"                        =>$advice_flag,
            "stu_test_paper"                     =>$stu_test_paper
        ];

        if ($db_tt_item["subject"] != $subject ) { //和数据库不一致

            $require_count=$this->t_test_lesson_subject_require->get_count_by_test_lesson_subject_id($test_lesson_subject_id );
            if ($require_count>0) {
                return $this->output_err("已有试听申请,不能修改科目");
            }
            if($this->t_test_lesson_subject->check_subject($userid,$subject)){
                return $this->output_err("已经有该科目了" );
            }
            $tt_arr["subject"]=$subject;
        }

        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,$tt_arr);

        //更新 seller_student_status
        if ($db_tt_item["seller_student_status"] != $seller_student_status) {
            $this->t_test_lesson_subject->set_seller_student_status( $test_lesson_subject_id, $seller_student_status,  $this->get_account() );
        }

        if($seller_student_status==420){
            $this->t_student_info->field_update_list($userid,[
                "type" =>0
            ]);
        }

        $current_require_id  =  $this->t_test_lesson_subject->get_current_require_id($test_lesson_subject_id);
        if($current_require_id>0){
            $this->t_test_lesson_subject_require->field_update_list($current_require_id,[
                "test_stu_request_test_lesson_demand"=> $stu_request_test_lesson_demand,
            ]);
        }
        return $this->output_succ();
    }



    public function  set_seller_student_status( ) {
        $test_lesson_subject_id= $this->get_in_test_lesson_subject_id();
        $seller_student_status= $this->get_in_e_seller_student_status();
        $seller_student_assign_type= $this->get_in_e_seller_student_assign_type();

        $wx_invaild_flag= $this->get_in_int_val("wx_invaild_flag");
        $db_tt_item=$this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"*");

        //dd($db_tt_item);
        $db_seller_student_new = $this->t_seller_student_new->get_userid_row($db_tt_item['userid']);

        //dd($db_seller_student_new);
        //更新 seller_student_status
        if ($db_tt_item["seller_student_status"] != $seller_student_status ) {
            $this->t_test_lesson_subject->set_seller_student_status( $test_lesson_subject_id,$seller_student_status,$this->get_account() );
        }

        //更新 wx_invaild_flag
        $arrwx = [];
        if ($db_seller_student_new["wx_invaild_flag"] != $wx_invaild_flag ) {
        $arrwx["wx_invaild_flag"] = $wx_invaild_flag;
        }
        $arrwx["seller_student_assign_type"] = $seller_student_assign_type;
        $this->t_seller_student_new->field_update_list( $db_seller_student_new["userid"],$arrwx);


        return $this->output_succ();


    }

    public function set_stu_test_paper() {
        $test_lesson_subject_id=$this->get_in_test_lesson_subject_id();
        $stu_test_paper=$this->get_in_str_val("stu_test_paper");
        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "stu_test_paper" => $stu_test_paper,
        ]);
        return $this->output_succ();
    }

    public function require_test_lesson() {
        $test_lesson_subject_id = $this->get_in_test_lesson_subject_id();
        $userid= $this->t_test_lesson_subject->get_userid($test_lesson_subject_id);
        $stu_test_ipad_flag   = $this->get_in_int_val("stu_test_ipad_flag");
        $not_test_ipad_reason = $this->get_in_str_val("not_test_ipad_reason");

        $curl_stu_request_test_lesson_time = $this->t_test_lesson_subject->get_stu_request_test_lesson_time($test_lesson_subject_id);
        $curl_stu_request_test_lesson_time_end = $this->t_test_lesson_subject->get_stu_request_test_lesson_time_end($test_lesson_subject_id);

        $test_stu_request_test_lesson_demand = $this->t_test_lesson_subject->get_stu_request_test_lesson_demand($test_lesson_subject_id);
        $intention_level  =  $this->t_test_lesson_subject->get_intention_level($test_lesson_subject_id);

        $this->t_seller_student_new->field_update_list($userid,['stu_test_ipad_flag'=>$stu_test_ipad_flag,'not_test_ipad_reason'=>$not_test_ipad_reason]);

        $test_stu_grade = $this->get_in_int_val("test_stu_grade");
        //试听申请
        $origin_info=$this->t_student_info->get_origin($userid);
        $ass_test_lesson_type = $this->t_test_lesson_subject->get_ass_test_lesson_type($test_lesson_subject_id);
        if($ass_test_lesson_type==1){
            $origin_info["origin"]="助教-扩课";
        }

        $ret=$this->t_test_lesson_subject_require->add_require(
            $this->get_account_id()
            , $this->get_account() ,
            $test_lesson_subject_id,
            $origin_info["origin"],
            $curl_stu_request_test_lesson_time,
            $test_stu_grade,
            $test_stu_request_test_lesson_demand,
            '',
            '',
            0,
            $curl_stu_request_test_lesson_time_end
        ) ;


        if (!$ret ) {
            $lesson_info= $this-> t_test_lesson_subject_require->get_test_lesson_subject_lesson_info($test_lesson_subject_id);
            if ($lesson_info && $lesson_info["test_lesson_student_status"]== E\Eseller_student_status::V_200 ) {
                $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                    "seller_student_status" => E\Eseller_student_status::V_200
                ]);
                return $this->output_err("当前该同学的申请请求 还没处理完毕,不可新建, 状态更新:未排课!, 请刷新界面 ");
            }else{
                return $this->output_err("当前该同学的申请请求 还没处理完毕,不可新建");
            }

        }else{
            //更新试听申请意向
            $info = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"current_require_id,intention_level");

            //判断是否top25

            $require_adminid = $this->get_account_id();
            $account_role = $this->t_manager_info->get_account_role($require_adminid);
            $start_time = strtotime(date("Y-m-01",strtotime(date("Y-m-01",$curl_stu_request_test_lesson_time))-200));

            $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list($require_adminid,  $start_time );
            if(isset($self_top_info[6]["top_index"]) || $require_adminid == 349){
                $rank = @$self_top_info[6]["top_index"];
                if(($account_role ==2 && $rank<=25) || $require_adminid == 349){
                    $month_start = strtotime(date("Y-m-01",$curl_stu_request_test_lesson_time));
                    $month_end = strtotime(date("Y-m-01",$month_start+40*86400));
                    $top_num = $this->t_test_lesson_subject_require->get_seller_top_require_num($month_start,$month_end,$require_adminid);
                    if($intention_level !=1 || $top_num>=40){
                        $seller_top_flag=0;
                    }else{
                        $seller_top_flag=1;
                    }
                }else{
                    $seller_top_flag=0;
                    $top_num =0;
                }
            }else{
                $seller_top_flag=0;
                $top_num =0;
            }


            $this->t_test_lesson_subject_require->field_update_list($info["current_require_id"],[
                "intention_level" =>$info["intention_level"],
                "seller_top_flag" =>$seller_top_flag
            ]);
            $stu_type = $this->t_student_info->get_type($userid);
            if($stu_type==0){
                $assistantid = $this->t_student_info->get_assistantid($userid);
                $nick = $this->t_student_info->get_nick($userid);
                $ass_adminid = $this->t_assistant_info->get_adminid_by_assistand($assistantid);
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($ass_adminid ,"在读学生试听申请通知","在读学生试听申请通知",$nick."有一节试听申请，请关注","");

            }

            return $this->output_succ(["seller_top_flag"=>$seller_top_flag,"top_num"=>$top_num]);
        }
    }
    /*
    public function noti_lesson_change(  $set_lesson_adminid , $require_adminid, $old_teacherid,$old_time,$new_teacher_id, $new_time ) {
        $require_admin_nick=$this->cache_get_account_nick($require_adminid );
        $old_time=\App\Helper\Utils::unixtime2date($old_time, "Y-m-d H:i" );
        $new_time=\App\Helper\Utils::unixtime2date($new_time, "Y-m-d H:i"  );

        $old_teacher_nick=$this->cache_get_teacher_nick($old_teacherid );
        $new_teacher_nick=$this->cache_get_teacher_nick($new_teacherid );

        $msg=" ";

        $this->t_manager_info->send_wx_todo_msg_by_adminid($set_lesson_adminid,"课程调整");

    }
    */

    public function get_order_list_js()  {
        $page_num = $this->get_in_page_num();
        $userid   = $this->get_in_userid();

        $ret_list = $this->t_order_info->get_order_list($page_num, 0,0xFFFFFFFF, -2, -2 ,$userid, -1, -1, false, -1 );
        $new_list = [];
        foreach ($ret_list["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time");
            $this->cache_set_item_student_nick($item );
            E\Esubject::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item);
            // if( $item["contract_type"] == 0 || $item["contract_type"] ==3  ) {
            //     $new_list[] = $item;
            // }
        }
        // $ret_list["list"]=$new_list;

        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ(["data"=> $ret_list]);
    }

    public function get_seller_origin_list_js()  {
        $userid=$this->get_in_userid();

        $ret_list=$this->t_seller_student_origin->get_user_rejoin_list($userid );
        foreach($ret_list["list"]  as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            E\Esubject::set_item_value_simple_str($item);
            E\Eseller_student_status::set_item_value_simple_str($item);
        }

        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ(["data"=> $ret_list]);
    }


    public function get_seller_new_count_list_js()  {
        $page_num = $this->get_in_page_num();
        $adminid  = $this->get_account_id();

        $ret_list = $this->t_seller_new_count->get_list($page_num,$adminid,-1);
        foreach($ret_list["list"] as &$item) {
            $this->cache_set_item_account_nick($item);
            E\Eseller_new_count_type::set_item_value_str($item);

            \App\Helper\Utils::unixtime2date_for_item($item,"start_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"end_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            $value_ex_str="";
            switch ( $item["seller_new_count_type"] ) {
            case E\Eseller_new_count_type::V_ADMIN :
                $value_ex_str="操作者:".  $this->cache_get_account_nick($item["value_ex"]) ;
                break;
            case E\Eseller_new_count_type::V_ORDER_ADD:
                $value_ex_str="合同id:". $item["value_ex"] ;
                break;
            default:
                break;
            }
            $item["value_ex_str"]=$value_ex_str;
        }

        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ(["data"=> $ret_list]);
    }

    public function get_test_lesson_subject_list_js() {
        $page_info=$this->get_in_page_info();
        $userid = $this->get_in_userid();
        //$admin_revisiterid, $seller_student_status_in_str , $userid,  $seller_student_status ,
             //$origin, $opt_date_str, $start_time, $end_time, $grade, $subject,
             //$phone_location, $has_pad, $seller_resource_type ,$origin_assistantid,$tq_called_flag
             //,$phone, $nick ,$origin_assistant_role,$success_flag,$seller_require_change_flag=-1, $adminid_list="" ,$group_seller_student_status =-1

        $ret_list=$this->t_seller_student_new ->get_seller_list_for_select  ($page_info, $userid,"","" );

        foreach($ret_list["list"] as &$item) {
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
        }

        return $this->output_succ(["data"=> $ret_list]);
    }

    public function get_require_list_js()  {
        $page_num = $this->get_in_page_num();
        $test_lesson_subject_id = $this->get_in_test_lesson_subject_id(-1);
        $userid = $this->get_in_userid(-1);
        $subject = $this->get_in_int_val("subject",-1);
        if($userid==-1 && $test_lesson_subject_id==-1){
            return $this->output_succ();
        }
        $ret_list = $this->t_test_lesson_subject_require->get_list_by_test_lesson_subject_id(
            $page_num,$test_lesson_subject_id,$userid,$subject
        );

        foreach($ret_list["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            $this->cache_set_item_teacher_nick($item);
            $this->cache_set_item_account_nick($item,"confirm_adminid", "confirm_admin_nick");
            $this->cache_set_item_account_nick($item,"accept_adminid", "accept_admin_nick");
            E\Esubject::set_item_value_str($item);
            $item["accept_flag_str"]=\App\Helper\Common::get_set_boolean_color_str($item["accept_flag"] );
            $item["success_flag_str"]=\App\Helper\Common::get_set_boolean_color_str($item["success_flag"] );
            E\Etest_lesson_fail_flag::set_item_value_str($item);
        }

        return $this->output_ajax_table($ret_list);
    }

    public function get_require_list_js_new()  {
        $page_num=$this->get_in_page_num();
        $test_lesson_subject_id = $this->get_in_test_lesson_subject_id( -1);
        $userid = $this->get_in_userid(-1);
        if ($userid==-1 && $test_lesson_subject_id==-1 ) {
            return $this->output_succ( );
        }
        $ret_list=$this->t_test_lesson_subject_require->get_list_by_test_lesson_subject_id_new($page_num,$test_lesson_subject_id,$userid);
        if(count($ret_list['list'])>0){
            $ret_new = $ret_list['list'][0];
            $ret_list['list'] = [];
            $ret_list['list'][0] = $ret_new;
        }
        foreach($ret_list["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            $this->cache_set_item_teacher_nick($item);
            $this->cache_set_item_account_nick($item,"confirm_adminid", "confirm_admin_nick");
            $this->cache_set_item_account_nick($item,"accept_adminid", "accept_admin_nick");
            E\Esubject::set_item_value_str($item);
            $item["accept_flag_str"]=\App\Helper\Common::get_set_boolean_color_str($item["accept_flag"] );
            $item["success_flag_str"]=\App\Helper\Common::get_set_boolean_color_str($item["success_flag"] );
            E\Etest_lesson_fail_flag::set_item_value_str($item);
        }
        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ(["data"=> $ret_list]);
    }

    public function set_no_accpect() {
        $require_id = $this->get_in_require_id();

        $fail_reason = $this->get_in_str_val("fail_reason");
        $tr_item=$this->t_test_lesson_subject_require->field_get_list($require_id ,"current_lessonid, test_lesson_subject_id");
        $lessonid               = $tr_item["current_lessonid"];
        $test_lesson_subject_id = $tr_item["test_lesson_subject_id"];

        //终止课程
        if ($lessonid) {
            $lesson_start=$this->t_lesson_info->get_lesson_start($lessonid);
            if ( time(NULL) > $lesson_start - 4*3600  )  {
                return $this->output_err("已排课,课前4小时不能驳回");
            }

            $this->t_test_lesson_subject_sub_list->set_lesson_del($this->get_account_id(),
                $lessonid,1,
                E\Etest_lesson_fail_flag::V_105,   $fail_reason );
        }


        $old_accept_adminid =$this->t_test_lesson_subject_require->get_accept_adminid($require_id);
        //驳回
        $this->t_test_lesson_subject_require->field_update_list($require_id,[
            'accept_flag'=> E\Eset_boolean::V_2,
            //'accept_adminid'=> $this->get_account_id(),
            'accept_time'=>time(NULL),
            "no_accept_reason" => $fail_reason,
            "jw_test_lesson_status"=> 3
        ]);

        //驳回
        $this->t_test_lesson_subject_require->set_test_lesson_status($require_id, E\Eseller_student_status::V_110, $this->get_account());
        $this->t_test_lesson_subject->set_seller_student_status($test_lesson_subject_id,
                                                                E\Eseller_student_status::V_110, $this->get_account());

        if (\App\Helper\Utils::check_env_is_release()) {
            $require_adminid=$this->t_test_lesson_subject->get_require_adminid($test_lesson_subject_id);
            $userid=$this->t_test_lesson_subject->get_userid($test_lesson_subject_id);
            $phone=$this->t_seller_student_new->get_phone($userid);
            $nick=$this->t_student_info ->get_nick($userid);

            $this->t_manager_info->send_wx_todo_msg_by_adminid ($require_adminid ,"来自:".$this->get_account()
                                                                ,"驳回[$phone][$nick] 理由:$fail_reason","","");

            if ($old_accept_adminid  ) {
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($old_accept_adminid ,
                                                                    "来自:".$this->get_account()
                                                                    ,"驳回--[$phone][$nick] 理由:$fail_reason","","");
            }

        }

        //增加驳回历史数据
        $rebut_info = $this->t_test_lesson_subject->get_rebut_info($test_lesson_subject_id);
        $rebut_arr=[
            "rebut_adminid"=> $this->get_account_id(),
            "rebut_reason" => $fail_reason,
            "rebut_time"   => time()
        ];
        if($rebut_info){
            $rebut_list = json_decode($rebut_info,true);
        }else{
            $rebut_list = [];
        }
        $rebut_list[]=$rebut_arr;
        $rebut_info_new = json_encode($rebut_list);
        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "rebut_info" =>$rebut_info_new,
            "rebut_flag" =>1
        ]);

        return $this->output_succ();
    }

    /**
     * 教务更换试听课时间/老师
     */
    public function test_lesson_change() {
        $require_id   = $this->get_in_require_id();
        $teacherid    = $this->get_in_teacherid();
        $grade        = $this->get_in_grade();
        $lesson_start = $this->get_in_str_val('lesson_start');
        $lesson_start = strtotime($lesson_start);
        $lesson_end   = $lesson_start+ 2400;


        if (empty($teacherid) || empty($lesson_end) || empty($lesson_start) ) {
            return $this->output_err("请填写完整!");
        }

        if($lesson_start<time()){
            return $this->output_err("课程开始时间过早!");
        }

        $require_info     = $this->t_test_lesson_subject_require->get_require_list_by_requireid($require_id);
        $current_lessonid = $require_info['current_lessonid'];
        $old_lesson_info  = $this->t_lesson_info->get_lesson_info($current_lessonid);
        $old_teacherid    = $old_lesson_info['teacherid'];
        $old_lesson_start = $old_lesson_info['lesson_start'];

        $date_week     = \App\Helper\Utils::get_week_range($lesson_start,1);
        $lstart        = $date_week["sdate"];
        $date_week_old = \App\Helper\Utils::get_week_range($old_lesson_start,1);
        $lstart_old    = $date_week_old["sdate"];

        if($teacherid==$old_teacherid && $lstart==$lstart_old){
            //如果老师不变,且更改后的上课时间仍在当周,则不进行限课检测
            if($lesson_start==$old_lesson_start){
                return $this->output_err("老师和时间没变,不用调课!");
            }
        }else{
            //老师年级科目限制
            $rr = $this->get_teacher_grade_freeze_limit_info($teacherid,$lesson_start,$grade,$require_id);
            if($rr){
                return $rr;
            }
        }

        $test_lesson_subject_id=$this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $tt_item=$this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject, grade, userid ");
        $userid  = $tt_item["userid"];
        $subject = $tt_item["subject"];
        $grade   = $tt_item["grade"];

        if ($current_lessonid<=0){
            return $this->output_err("还没有排课!");
        }

        $old_success_flag=$this->t_test_lesson_subject_sub_list->get_success_flag($current_lessonid);
        if ($old_success_flag >0) {
            return $this->output_err("课程已确认,不能修改!");
        }

        $check_student_time_free = $this->check_student_time_free($userid,$current_lessonid,$lesson_start,$lesson_end);
        if($check_student_time_free!=true){
            return $check_student_time_free;
        }

        $check_teacher_time_free = $this->check_teacher_time_free($teacherid,$current_lessonid,$lesson_start,$lesson_end);
        if($check_teacher_time_free!=true){
            return $check_teacher_time_free;
        }

        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");
        $lesson_row   = $this->t_lesson_info->field_get_list($current_lessonid,"courseid,teacherid,lesson_start ");

        $courseid = $lesson_row["courseid"];
        $test_lesson_fail_flag = 0;
        if ($lesson_row["teacherid"] != $teacherid ) {
            $test_lesson_fail_flag = E\Etest_lesson_fail_flag::V_104;
        }else if ($lesson_row["lesson_start"] != $lesson_start ) {
            $test_lesson_fail_flag = E\Etest_lesson_fail_flag::V_103;
        }

        if ($test_lesson_fail_flag==0) {
            return $this->output_err("没有换老师或时间!");
        }

        if ($test_lesson_fail_flag== E\Etest_lesson_fail_flag::V_104 ) {
            if ($lesson_row["lesson_start"] < 4*3600 +time(NULL) ) {
                return $this->output_err("课前4小时不能换老师");
            }
        }elseif ($test_lesson_fail_flag== E\Etest_lesson_fail_flag::V_103 ) { //换时间
            if ($lesson_row["lesson_start"] < 4*3600 +time(NULL) ) {
                if( abs( $lesson_start -$lesson_row["lesson_start"]) <2*3600 ){
                    //延后 ,提前2小时以内

                }else{
                    return $this->output_err("课前4小时不能换时间");
                }
            }
        }


        $this->t_test_lesson_subject_sub_list->set_lesson_del(
            $this->get_account_id(),
            $current_lessonid,1, $test_lesson_fail_flag, "");

        $lessonid=$this->t_lesson_info->add_lesson(
            $courseid,0,
            $userid,
            0,
            2,
            $teacherid,
            0,
            $lesson_start,
            $lesson_end,
            $grade,
            $subject,
            100,
            $teacher_info["teacher_money_type"],
            $teacher_info["level"]
        );
        $this->t_homework_info->add(
            $courseid,
            0,
            $userid,
            $lessonid,
            $grade,
            $subject,
            $teacherid
        );

        //老师维度计算
        $tea_in = $this->t_teacher_info->field_get_list($teacherid,"test_transfor_per,identity,month_stu_num");
        $record_score = $this->t_teacher_record_list->get_teacher_first_record_score($teacherid);
        if($tea_in["test_transfor_per"]>=20){
            $teacher_dimension="维度A";
        }elseif($tea_in["test_transfor_per"]>=10 && $tea_in["test_transfor_per"]<20){
            $teacher_dimension="维度B";
        }elseif($tea_in["test_transfor_per"]<10 && in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=4 && $record_score>=60 && $record_score<=90){
            $teacher_dimension="维度C";
        }elseif($tea_in["test_transfor_per"]<10 && !in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=4 && $record_score<=90){
            $teacher_dimension="维度C候选";
        }elseif($tea_in["test_transfor_per"]<10 && in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=1 && $tea_in["month_stu_num"]<=3 && $record_score>=60 && $record_score<=90){
            $teacher_dimension="维度D";
        }elseif($tea_in["test_transfor_per"]<10 && !in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=1 && $tea_in["month_stu_num"]<=3 && $record_score<=90){
            $teacher_dimension="维度D候选";
        }else{
            $teacher_dimension="其他";
        }

        $this->t_test_lesson_subject_sub_list->row_insert([
            "lessonid"  => $lessonid,
            "require_id" => $require_id,
            "set_lesson_adminid"  => $this->get_account_id(),
            "set_lesson_time"  => time(NULL) ,
            "teacher_dimension" =>$teacher_dimension
            // "seller_require_flag"=> 10
        ]);

        $this->t_test_lesson_subject_require->field_update_list($require_id , [
            'current_lessonid'=>$lessonid,
        ]);
        $this->t_test_lesson_subject_require->set_test_lesson_status(
            $require_id, E\Eseller_student_status::V_210 , $this->get_account() );


        $account_role = $this->get_account_role();
        // $checkIsFullTime = $this->t_teacher_info->checkIsFullTime($teacherid);

        # 目前只有全职老师可以使用
        // if($checkIsFullTime == 1){ // 文彬测试
            /**
             * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
             * 标题课程 : 待办事项提醒
             * {{first.DATA}}
             * 待办主题：{{keyword1.DATA}}
             * 待办内容：{{keyword2.DATA}}
             * 日期：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $wx_openid        = $this->t_teacher_info->get_wx_openid($teacherid);
            $lesson_start     = $this->t_lesson_info->get_lesson_start($lessonid);
            $lesson_end       = $this->t_lesson_info->get_lesson_end($lessonid);
            $lesson_time_str  = date("Y-m-d H:i",$lesson_start)." ~ ".date('H:i',$lesson_end);
            $nick = $this->t_student_info->get_nick($userid);
            $require_adminid  = $this->t_test_lesson_subject->get_require_adminid($test_lesson_subject_id);
            $require_phone    = $this->t_manager_info->get_phone($require_adminid);
            $stu_request_info = $this->t_test_lesson_subject->get_stu_request($lessonid);
            $demand           = $stu_request_info['stu_request_test_lesson_demand'];

            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']    = $nick."同学的试听课已排好，请尽快完成课前准备工作";
            $data['keyword1'] = "备课通知";
            $data['keyword2'] = "\n上课时间：$lesson_time_str "
                 ."\n咨询电话：$require_phone"
                 ."\n试听需求：$demand"
                 ."\n1、请及时确认试听需求并备课"
                 ."\n2、老师可提前10分钟进入课堂进行上课准备";
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "";
            $url = "http://wx-teacher-web.leo1v1.com/student_info.html?lessonid=".$lessonid; //[标签系统 给老师帮发]

            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);

        // }

        return $this->output_succ();
    }

    public function set_limit_lesson_require(){
        // dd(111);
        $require_id   = $this->get_in_require_id();
        $limit_require_teacherid  = $this->get_in_int_val('limit_require_teacherid');
        $limit_require_lesson_start  = $this->get_in_str_val('limit_require_lesson_start');
        $limit_require_lesson_start  = strtotime($limit_require_lesson_start);
        $require_adminid = $this->get_in_int_val('require_adminid');
        $grade = $this->get_in_int_val('grade');
        $subject = $this->get_in_int_val('subject');
        $is_green_flag = $this->get_in_int_val('is_green_flag');
        $limit_require_reason  = $this->get_in_str_val('limit_require_reason');

        $account_role = $this->t_manager_info->get_account_role($require_adminid ) ;

        //每月特殊申请基本量
        if($account_role==2 && $is_green_flag==1){
            $master_adminid = $this->t_admin_group_user->get_main_master_adminid($require_adminid);
        }else{
            $master_adminid_list = $this->t_admin_main_group_name->get_maste_admin_name(3,"教务排课");
            $master_adminid = $master_adminid_list["master_adminid"];
        }
        //部分情况不能做特殊申请,比如冻结等,每月特殊申请数量限制
        $rr = $this->get_seller_limit_require_info($limit_require_teacherid,$limit_require_lesson_start,$grade,$subject,$account_role,$master_adminid,$is_green_flag);
        if($rr){
            return $rr;
        }

        if(empty($master_adminid)){
            $master_adminid=349;
        }
        $this->t_test_lesson_subject_require->field_update_list($require_id,[
            "limit_require_flag"  =>1,
            "limit_require_teacherid" =>$limit_require_teacherid,
            "limit_require_lesson_start" =>$limit_require_lesson_start ,
            "limit_require_time" =>time(),
            "limit_require_adminid" =>$this->get_account_id(),
            "limit_require_send_adminid"=>$master_adminid,
            "limit_require_reason" =>$limit_require_reason,
            "limit_accept_flag"    =>0,
            "limit_accept_time"    =>0
        ]);
        $realname = $this->t_teacher_info->get_realname($limit_require_teacherid);
        if($account_role==2 && $is_green_flag==1){
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($master_adminid,$realname."老师申请解限老师并排课","限课特殊申请通知","申请理由:".$limit_require_reason."
请总监认真做好解限审核,限课冻结的老师教学质量上一般存在较大问题,请谨慎排课","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list_seller?require_id=".$require_id);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (72,$realname."老师申请解限老师并排课","限课特殊申请通知","申请理由:".$limit_require_reason."
请总监认真做好解限审核,限课冻结的老师教学质量上一般存在较大问题,请谨慎排课","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list_seller?require_id=".$require_id);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (478,$realname."老师申请解限老师并排课","限课特殊申请通知","申请理由:".$limit_require_reason."
请总监认真做好解限审核,限课冻结的老师教学质量上一般存在较大问题,请谨慎排课","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list_seller?require_id=".$require_id);


        }else{
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($master_adminid,$realname."老师申请解限老师并排课","限课特殊申请通知","申请理由:".$limit_require_reason."
请总监认真做好解限审核,限课冻结的老师教学质量上一般存在较大问题,请谨慎排课","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list_jw_leader?require_id=".$require_id);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (72,$realname."老师申请解限老师并排课","限课特殊申请通知","申请理由:".$limit_require_reason."
请总监认真做好解限审核,限课冻结的老师教学质量上一般存在较大问题,请谨慎排课","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list_jw_leader?require_id=".$require_id);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (478,$realname."老师申请解限老师并排课","限课特殊申请通知","申请理由:".$limit_require_reason."
请总监认真做好解限审核,限课冻结的老师教学质量上一般存在较大问题,请谨慎排课","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list_seller?require_id=".$require_id);



        }

        return $this->output_succ();



    }

    public function set_limit_accept_flag(){
        $require_id   = $this->get_in_require_id();
        $limit_accept_flag = $this->get_in_int_val('limit_accept_flag');
        $limit_info = $this->t_test_lesson_subject_require->field_get_list($require_id,"limit_require_adminid,limit_require_send_adminid,limit_require_teacherid");
        $tea_nick = $this->t_teacher_info->get_realname($limit_info["limit_require_teacherid"]);
        $send_nick = $this->t_manager_info->get_account($limit_info["limit_require_send_adminid"]);
        $this->t_test_lesson_subject_require->field_update_list($require_id,[
            "limit_accept_flag"  =>$limit_accept_flag,
            "limit_accept_time"  =>time()
        ]);
        if($limit_accept_flag==1){
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($limit_info["limit_require_adminid"],$tea_nick."老师的限课特殊申请审核通过","限课特殊申请审核通过通知","系统会在十分钟内根据教研审核情况自动处理,请及时确认!","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list?require_id=".$require_id);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (72,$tea_nick."老师的限课特殊申请审核通过","限课特殊申请审核通过通知","系统会在十分钟内根据教研审核情况自动处理,请及时确认!","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list?require_id=".$require_id);

        }else if($limit_accept_flag==2){
             $this->t_manager_info->send_wx_todo_msg_by_adminid ($limit_info["limit_require_adminid"],$tea_nick."老师的限课特殊申请被驳回","限课特殊申请驳回通知",$tea_nick."老师由于教学质量或者态度存在较大问题,故排课被驳回,请更换其他老师","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list?require_id=".$require_id);
             $this->t_manager_info->send_wx_todo_msg_by_adminid (72,$tea_nick."老师的限课特殊申请被驳回","限课特殊申请驳回通知",$tea_nick."老师由于教学质量或者态度存在较大问题,故排课被驳回,请更换其他老师","http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list?require_id=".$require_id);
        }
        return $this->output_succ();
    }

    /**
     * 教务排试听课
     */
    public function course_set_new(){
        $require_id      = $this->get_in_require_id();
        $teacherid       = $this->get_in_teacherid();
        $lesson_start    = $this->get_in_str_val('lesson_start');
        $grade           = $this->get_in_int_val('grade');
        $top_seller_flag = $this->get_in_int_val('top_seller_flag');
        $orderid         = 1;

        $teacher_info  = $this->t_teacher_info->get_teacher_info($teacherid);
        $check_is_full = \App\Helper\Utils::check_teacher_is_full(
            $teacher_info['teacher_money_type'],$teacher_info['teacher_type'],$teacherid
        );
        $lesson_diff = 40*60;
        $lesson_start = strtotime($lesson_start);
        $lesson_end = $lesson_start+$lesson_diff;

        $db_lessonid = $this->t_test_lesson_subject_require->get_current_lessonid($require_id);
        $account_role = $this->get_account_role();
        if ($db_lessonid && $account_role != 12){
            return $this->output_err("已经排课过了!,可以换老师&时间");
        }
        if ($teacherid<=0 || $lesson_end<=0 || $lesson_start<=0 ) {
            return $this->output_err("请填写完整!");
        }
        if($lesson_start < time()){
            return $this->output_err("课程开始时间过早!");
        }

        //老师年级科目限制
        $rr = $this->get_teacher_grade_freeze_limit_info($teacherid,$lesson_start,$grade,$require_id);
        if($rr){
            return $rr;
        }

        $test_lesson_subject_id=$this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $tt_item = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject,grade,userid");
        $userid  = $tt_item["userid"];
        $subject = $tt_item["subject"];

        $ret_row1 = $this->t_lesson_info->check_student_time_free($userid,0,$lesson_start,$lesson_end);
        //检查时间是否冲突
        if ($ret_row1) {
            $error_lessonid=$ret_row1["lessonid"];
            return $this->output_err(
                "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $ret_row2 = $this->t_lesson_info->check_teacher_time_free($teacherid,0,$lesson_start,$lesson_end);
        if($ret_row2){
            $error_lessonid = $ret_row2["lessonid"];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $courseid = $this->t_course_order->add_course_info_new(
            $orderid,$userid,$grade,$subject,100,E\Econtract_type::V_2,E\Ecourse_status::V_0,1,1,0,$teacherid
        );
        $lessonid = $this->t_lesson_info->add_lesson(
            $courseid,0,$userid,E\Efrom_type::V_0,E\Econtract_type::V_2,
            $teacherid,0,$lesson_start,$lesson_end,$grade,
            $subject,100,$teacher_info["teacher_money_type"],$teacher_info["level"]
        );
        $this->t_homework_info->add(
            $courseid,0,$userid,$lessonid,$grade,$subject,$teacherid
        );
        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "grade"  => $grade,
        ]);

        //老师维度计算
        $tea_in = $this->t_teacher_info->field_get_list($teacherid,"test_transfor_per,identity,month_stu_num");
        $record_score = $this->t_teacher_record_list->get_teacher_first_record_score($teacherid);
        if($tea_in["test_transfor_per"]>=20){
            $teacher_dimension="维度A";
        }elseif($tea_in["test_transfor_per"]>=10 && $tea_in["test_transfor_per"]<20){
            $teacher_dimension="维度B";
        }elseif($tea_in["test_transfor_per"]<10 && in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=4 && $record_score>=60 && $record_score<=90){
            $teacher_dimension="维度C";
        }elseif($tea_in["test_transfor_per"]<10 && !in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=4 && $record_score<=90){
            $teacher_dimension="维度C候选";
        }elseif($tea_in["test_transfor_per"]<10 && in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=1 && $tea_in["month_stu_num"]<=3 && $record_score>=60 && $record_score<=90){
            $teacher_dimension="维度D";
        }elseif($tea_in["test_transfor_per"]<10 && !in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=1 && $tea_in["month_stu_num"]<=3 && $record_score<=90){
            $teacher_dimension="维度D候选";
        }else{
            $teacher_dimension="其他";
        }

        $this->t_test_lesson_subject_sub_list->row_insert([
            "lessonid"           => $lessonid,
            "require_id"         => $require_id,
            "set_lesson_adminid" => $this->get_account_id(),
            "set_lesson_time"    => time(NULL) ,
            "top_seller_flag"    => $top_seller_flag,
            "teacher_dimension"  => $teacher_dimension
        ]);
        $this->t_test_lesson_subject_require->field_update_list($require_id,[
            'current_lessonid'      => $lessonid,
            'accept_flag'           => E\Eset_boolean::V_1 ,
            'accept_time'           => time(NULL),
            'jw_test_lesson_status' => 1,
            'grab_status'           => E\Egrab_status::V_2,
        ]);
        $this->t_test_lesson_subject_require->set_test_lesson_status(
            $require_id,E\Eseller_student_status::V_210,$this->get_account());

        $this->t_lesson_info->reset_lesson_list($courseid);
        $this->t_seller_student_new->field_update_list($userid,[
            "global_tq_called_flag" => 2,
            "tq_called_flag"        => 2,
        ]);

        $require_info = $this->t_test_lesson_subject_require->field_get_list($require_id,"test_lesson_subject_id,accept_adminid");
        $this->t_test_lesson_subject->field_update_list($require_info["test_lesson_subject_id"],[
            "history_accept_adminid" => $require_info["accept_adminid"]
        ]);

        if (\App\Helper\Utils::check_env_is_release()){
            $require_adminid  = $this->t_test_lesson_subject->get_require_adminid($test_lesson_subject_id);
            $userid           = $this->t_test_lesson_subject->get_userid($test_lesson_subject_id);
            $phone            = $this->t_seller_student_new->get_phone($userid);
            $nick             = $this->t_student_info ->get_nick($userid);
            $teacher_nick     = $this->cache_get_teacher_nick($teacherid);
            $require_phone    = $this->t_manager_info->get_phone($require_adminid);
            $stu_request_info = $this->t_test_lesson_subject->get_stu_request($lessonid);
            $demand           = $stu_request_info['stu_request_test_lesson_demand'];
            $lesson_time_str    = \App\Helper\Utils::fmt_lesson_time($lesson_start,$lesson_end);
            $require_admin_nick = $this->cache_get_account_nick($require_adminid);

            $do_adminid = $this->get_account_id();
            $account_role = $this->get_account_role();
            // $checkIsFullTime = $this->t_teacher_info->checkIsFullTime($teacherid);

            # 目前只有全职老师可以使用
            // if($checkIsFullTime == 1){ // 文彬测试
                /**
                 * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                 * 标题课程 : 待办事项提醒
                 * {{first.DATA}}
                 * 待办主题：{{keyword1.DATA}}
                 * 待办内容：{{keyword2.DATA}}
                 * 日期：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */
                $wx_openid        = $this->t_teacher_info->get_wx_openid($teacherid);
                $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                $data['first']    = $nick."同学的试听课已排好，请尽快完成课前准备工作";
                $data['keyword1'] = "备课通知";
                $data['keyword2'] = "\n上课时间：$lesson_time_str "
                     ."\n咨询电话：$require_phone"
                     ."\n试听需求：$demand"
                     ."\n1、请及时确认试听需求并备课"
                     ."\n2、老师可提前10分钟进入课堂进行上课准备";
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "";
                $url = "http://wx-teacher-web.leo1v1.com/student_info.html?lessonid=".$lessonid; //[标签系统 给老师帮发]

                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            // }else{
            //     $this->t_manager_info->send_wx_todo_msg(
            //         $require_admin_nick,"来自:".$this->get_account()
            //         ,"排课[$phone][$nick] 老师[$teacher_nick] 上课时间[$lesson_time_str]","","");

            //     $parentid = $this->t_student_info->get_parentid($userid);

            //     if($parentid>0){
            //         $this->t_parent_info->send_wx_todo_msg($parentid,"课程反馈","您的试听课已预约成功!", "上课时间[$lesson_time_str]","http://wx-parent.leo1v1.com/wx_parent/index", "点击查看详情" );
            //     }

            //     /**
            //      * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
            //      * 标题课程 : 待办事项提醒
            //      * {{first.DATA}}
            //      * 待办主题：{{keyword1.DATA}}
            //      * 待办内容：{{keyword2.DATA}}
            //      * 日期：{{keyword3.DATA}}
            //      * {{remark.DATA}}
            //      */
            //     $wx_openid        = $this->t_teacher_info->get_wx_openid($teacherid);
            //     $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            //     $data['first']    = $nick."同学的试听课已排好，请尽快完成课前准备工作";
            //     $data['keyword1'] = "备课通知";
            //     $data['keyword2'] = "\n上课时间：$lesson_time_str "
            //                       ."\n教务电话：$require_phone"
            //                       ."\n试听需求：$demand"
            //                       ."\n1、请及时确认试听需求并备课"
            //                       ."\n2、请尽快上传教师讲义、学生讲义（用于学生预习）和作业"
            //                       ."\n3、老师可提前15分钟进入课堂进行上课准备";
            //     $data['keyword3'] = date("Y-m-d H:i",time());
            //     $data['remark']   = "";
            //     $url = "http://www.leo1v1.com/login/teacher";

            //     \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);

            // }
        }

        //优学优享
        $agent_id = $this->t_agent->get_agentid_by_userid($userid);
        if ($agent_id) {
            dispatch( new \App\Jobs\agent_reset($agent_id) );
        }

        return $this->output_succ();
    }

    public function check_teacher_subject_and_grade($subject,$grade,$first_subject,$second_subject,$third_subject,$grade_part_ex,$second_grade,$third_grade,$is_test){
        if($is_test ==0){
            if($subject != $first_subject && $subject != $second_subject && $subject != $third_subject){
                return $this->output_err(
                    "请安排与老师科目相符合的课程!"
                );
            }

            if($subject==$first_subject){
                if($grade==106){
                    if($grade_part_ex !=1 && $grade_part_ex!=6 && $grade_part_ex!=4 ){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade==203){
                    if($grade_part_ex !=2 && $grade_part_ex!=5 && $grade_part_ex!=4 && $grade_part_ex!=7 && $grade_part_ex!=6){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }elseif($grade>=100 && $grade <200){
                    if($grade_part_ex !=1 && $grade_part_ex!=4 ){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=200 && $grade <300){
                    if($grade_part_ex !=2 && $grade_part_ex !=4 && $grade_part_ex !=5 && $grade_part_ex!=6){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=300 ){
                    if($grade_part_ex !=3 && $grade_part_ex !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }

                }
            }else if($subject==$second_subject){
                if($grade>=100 && $grade <200){
                    if($second_grade !=1 && $second_grade !=4){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=200 && $grade <300){
                    if($second_grade !=2 && $second_grade !=4 && $second_grade !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=300 ){
                    if($second_grade !=3 && $second_grade !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }
            }else if($subject==$third_subject){
                if($grade>=100 && $grade <200){
                    if($third_grade !=1 && $third_grade !=4){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=200 && $grade <300){
                    if($third_grade !=2 && $third_grade !=4 && $third_grade !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=300 ){
                    if($third_grade !=3 && $third_grade !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }
            }
            return 1;
        }else{
            return 1;
        }
    }

    public function check_teacher_grade_range($stu_info,$tea_info){
        $stu_grade_range = $this->get_grade_range($stu_info['grade']);
        $not_grade       = explode(",",$tea_info['not_grade']);
        $grade_start     = $tea_info['grade_start'];
        $grade_end       = $tea_info['grade_end'];

        if($stu_grade_range<$grade_start || $stu_grade_range>$grade_end || in_array($stu_info['grade'],$not_grade)){
            return $this->output_err("学生年级与老师年级范围不匹配!");
        }
        if($stu_info['subject']!=$tea_info['subject']){
            return $this->output_err("学生科目与老师科目不匹配!");
        }
        return 1;
    }

    public function get_grade_range($grade){
        switch($grade){
        case 101:case 102:case 103:
            $grade_range=1;
            break;
        case 104:case 105:case 106:
            $grade_range=2;
            break;
        case 201:case 202:
            $grade_range=3;
            break;
        case 203:
            $grade_range=4;
            break;
        case 301:case 302:
            $grade_range=5;
            break;
        case 303:
            $grade_range=6;
            break;
        default:
            $grade_range=0;
        }
        return $grade_range;
    }

    public function seller_add_contract_free()
    {
        $from_parent_order_type = $this->get_in_int_val("from_parent_order_type");
        $parent_order_id        = $this->get_in_int_val("parent_order_id");
        $lesson_total           = $this->get_in_int_val("lesson_total");
        $order_require_flag     = $this->get_in_int_val("order_require_flag");
        $order_require_reason   = $this->get_in_str_val("order_require_reason");
        $to_userid  = $this->get_in_int_val("to_userid");
        $from_parent_order_lesson_count  = $this->get_in_int_val("from_parent_order_lesson_count");
        $part_competition_flag  = $this->get_in_int_val("part_competition_flag");

        $tt_item=$this->t_order_info->field_get_list($parent_order_id,"userid,grade,subject,competition_flag");
        if (!$tt_item) {
            return $this->output_err("没有找到上级合同");
        }

        $userid  = $tt_item["userid"];
        $grade   = $tt_item["grade"];
        $subject = $tt_item["subject"];
        $competition_flag =0;

        if ($from_parent_order_type==E\Efrom_parent_order_type::V_1){ //转介绍
            $origin_userid=$this->t_student_info->get_origin_userid($userid);
            if (!$origin_userid) {
                return $this->output_err("没有找到对应的转介绍人");
            }

            if ($find_userid= $this->t_order_info-> check_order_origin_userid( $userid )) {
                return $this->output_err("已经创建过转介绍合同了! userid=[$find_userid]");
            }

            $userid = $origin_userid;
            $grade = $this->t_student_info->get_grade($userid);
            $competition_flag = $part_competition_flag;
        }else if ( $from_parent_order_type== E\Efrom_parent_order_type::V_2 ){ //24小时内签单
            if($this->t_lesson_info-> get_succ_test_lesson_count($userid)>1) {
                return $this->output_err("多次试听,不能  24小时内签单 -赠送合同了 ");
            }

            if($this->t_order_info->check_order_free_to_self($parent_order_id,$from_parent_order_type)) {
                return $this->output_err("该新签合同已经有 24小时内签单 -赠送合同了!");
            }

            $parent_order_lesson_count=$this->t_order_info->field_get_value($parent_order_id, "lesson_total*default_lesson_count/100");
            \App\Helper\Utils::logger(" ss: $parent_order_id: $parent_order_lesson_count");

            if ($parent_order_lesson_count>=450) {
                $lesson_total=1200;
            }else if (  $parent_order_lesson_count>=360) {
                $lesson_total=900;
            }else if (  $parent_order_lesson_count>=270) {
                $lesson_total=600;
            }else if (  $parent_order_lesson_count>=180) {
                $lesson_total=300;
            }else if (  $parent_order_lesson_count>=90) {
                $lesson_total=100;
            }else  {
                return $this->output_err("不足90课时,不能赠送! ");
            }

            $competition_flag = $tt_item["competition_flag"];

        }else if ( in_array( $from_parent_order_type, array( E\Efrom_parent_order_type::V_4 , E\Efrom_parent_order_type::V_3 ))){ //特批赠送
            $competition_flag = $tt_item["competition_flag"];
        }else if ( $from_parent_order_type == E\Efrom_parent_order_type::V_5 ) {
            //转赠
            $userid = $to_userid;
            if (!$userid) {
                return $this->output_err("请选择被赠人");
            }
            $grade = $this->t_student_info->get_grade($userid);
            $competition_flag = $part_competition_flag;


        }else if($from_parent_order_type==E\Efrom_parent_order_type::V_6){
            $adm = $this->get_account();
            /* if(!in_array($adm,["jim","jack"])){
                return $this->output_err("该功能开发中");
                }*/
            $assistantid = $this->t_assistant_info->get_assistantid($adm);
            $assign_lesson_count = $this->t_assistant_info->get_assign_lesson_count($assistantid);
            if($assign_lesson_count < $lesson_total){
                return $this->output_err("可分配课时不足,剩余:".($assign_lesson_count/100)."课时");
            }
        }else{
            if($this->t_order_info->check_order_free_to_self($parent_order_id)) {
                return $this->output_err("该新签合同已经有赠送合同了!");
            }
            $competition_flag = $tt_item["competition_flag"];
        }

        $sys_operator           = $this->get_account();
        $contract_type          = E\Econtract_type::V_1;
        $origin="";
        $price=0;
        $discount_price=0;
        $discount_reason="";
        $need_receipt=0;
        $title="";
        $requirement="";
        $from_test_lesson_id=0;
        $default_lesson_count=1;

        $order_price_type=0;
        $order_promotion_type=0;
        $promotion_discount_price=0;
        $promotion_present_lesson=0;
        $promotion_spec_discount=0;
        $promotion_spec_present_lesson=0;
        $contract_from_type=0;

        $orderid=$this->t_order_info->add_contract(
            $sys_operator,  $userid , $origin, $competition_flag,$contract_type,$grade,$subject,$lesson_total,$price ,  $discount_price ,$discount_reason , $need_receipt, $title ,$requirement, $from_test_lesson_id, $from_parent_order_type, $parent_order_id,$default_lesson_count,
            $order_price_type,
            $order_promotion_type,
            $promotion_discount_price,
            $promotion_present_lesson,
            $promotion_spec_discount,
            $promotion_spec_present_lesson,
            $contract_from_type,
            $from_parent_order_lesson_count
        );

        if ( $from_parent_order_type == E\Efrom_parent_order_type::V_5   ) {
            //重置原学生课程包分配课时
            $this->t_course_order->reset_assigned_lesson_count($tt_item["userid"],$tt_item["competition_flag"]);

            $this->t_flow->add_flow(
                E\Eflow_type::V_ORDER_EXCHANGE,
                $this->get_account_id(),$order_require_reason, $orderid);
        }elseif($from_parent_order_type == E\Efrom_parent_order_type::V_6){
            $assign_lesson_count_left = $assign_lesson_count - $lesson_total;
            $this->t_assistant_info->field_update_list($assistantid,[
               "assign_lesson_count" =>$assign_lesson_count_left
            ]);

            //合同状态更新
            $this->t_order_info->field_update_list($orderid,[
                "contract_status"=>1,
                "order_status"   =>1,
                "check_money_flag"=>1,
                "check_money_time"=>time()
            ]);

            $acc= $this->get_account();
            $this->t_manager_info->send_wx_todo_msg("jack","助教课时赠送","助教".$acc,"课时".$lesson_total,""  );

        }else{
            if($order_require_flag) {
                $this->t_flow->add_flow(
                    E\Eflow_type::V_SELLER_ORDER_REQUIRE,
                    $this->get_account_id(),$order_require_reason, $orderid);
            }
        }

        return $this->output_succ();
    }

    public function get_order_price_info() {
        $grade=$this->get_in_grade() ;
        $competition_flag= $this->get_in_int_val("competition_flag");
        $lesson_count= $this->get_in_int_val("lesson_count")/100;
        $order_promotion_type= $this->get_in_int_val("order_promotion_type");
        $contract_type = $this->get_in_int_val("contract_type");
        $period_flag= $this->get_in_int_val("period_flag");
        $account = $this->get_account();
        $require_id =$this->get_in_require_id();
        $userid= $this->t_test_lesson_subject_require->get_userid($require_id);
        $from_test_lesson_id = $this->t_test_lesson_subject_require->get_current_lessonid($require_id);
        $disable_activity_list= $this->get_in_int_list( "disable_activity_list" );

        if (!$userid) {
            $userid= $this->get_in_userid();
        }
        if (!$userid) {
            return $this->output_err("用户不存在");

        }
        if ($lesson_count==0 ) {
            return $this->output_err("课时数不能=0;");
        }
        //$before_lesson_count = $this->t_order_info->get_order_all_lesson_count($userid, $account )/100;
        //\App\Helper\Utils::logger("before_lesson_count:$before_lesson_count");
        $before_lesson_count=0;

        $ret=\App\OrderPrice\order_price_base::get_price_ex_cur(
            $competition_flag,$order_promotion_type,$contract_type,$grade,$lesson_count,$before_lesson_count,
            [
                "from_test_lesson_id"=> $from_test_lesson_id ,
                "period_flag" =>$period_flag,
                "userid" => $userid,
                "disable_activity_list" => $disable_activity_list
            ]
            );

        return $this->output_succ(["data"=>$ret]);
    }


    public function seller_add_contract()
    {
        $require_id = $this->get_in_require_id("require_id");
        $competition_flag       = $this->get_in_int_val('competition_flag');
        $lesson_total           = $this->get_in_int_val("lesson_total");
        $price                  = $this->get_in_int_val("price")*100;
        $discount_price         = $this->get_in_int_val("discount_price")*100;
        $discount_reason        = $this->get_in_str_val("discount_reason");
        $need_receipt           = $this->get_in_int_val('need_receipt');
        $title                  = $this->get_in_str_val('title', "" );
        $requirement            = $this->get_in_str_val('requirement');
        $from_test_lesson_id = $this->get_in_int_val("from_test_lesson_id");
        $order_require_flag = $this->get_in_int_val("order_require_flag");
        //默认新签
        $contract_type          = $this->get_in_enum_val(E\Econtract_type::class, 0);

        $sys_operator           = $this->get_account();
        $userid= $this->get_in_userid();
        $grade= $this->get_in_grade();
        $subject= $this->get_in_subject();
        $origin= $this->get_in_str_val("origin");

        if ($require_id ) {
            $test_lesson_subject_id= $this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
            $origin  = $this->t_test_lesson_subject_require->get_origin($require_id);
            $tt_item = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"userid,grade,subject");
            $userid  = $tt_item["userid"];
            $grade   = $tt_item["grade"];
            $subject = $tt_item["subject"];
        }

        $orderid=$this->t_order_info->add_contract($sys_operator,  $userid , $origin, $competition_flag,$contract_type,$grade,$subject,$lesson_total,$price ,  $discount_price ,$discount_reason , $need_receipt, $title ,$requirement, $from_test_lesson_id );
        //转介绍
        if($order_require_flag) {
            $this->t_flow->add_flow(
                E\Eflow_type::V_SELLER_ORDER_REQUIRE,
                $this->get_account_id(),"特殊折扣",$orderid);
        }
        return $this->output_succ();

    }
    public function seller_add_contract_new()
    {
        $require_id = $this->get_in_require_id("require_id");
        $competition_flag       = $this->get_in_int_val('competition_flag');
        $lesson_total           = $this->get_in_int_val("lesson_total");
        $discount_reason        = $this->get_in_str_val("discount_reason");
        $title                  = trim($this->get_in_str_val('title', "" ));
        $need_receipt           = !($title=="");
        $requirement            = "";
        $order_require_flag = $this->get_in_int_val("order_require_flag");
        $pre_money          = $this->get_in_int_val("pre_money");
        //默认新签
        $contract_type          = $this->get_in_enum_val(E\Econtract_type::class);
        $order_promotion_type = $this->get_in_enum_val(E\Eorder_promotion_type::class);
        $promotion_spec_discount = $this->get_in_int_val("promotion_spec_discount");
        $promotion_spec_present_lesson = $this->get_in_int_val("promotion_spec_present_lesson");
        $test_lesson_subject_id         = $this->get_in_int_val("test_lesson_subject_id");
        $seller_student_status         = $this->get_in_int_val("seller_student_status");
        $has_share_activity_flag = $this->get_in_int_val("has_share_activity_flag");
        $contract_from_type = $this->get_in_e_contract_from_type();
        $order_partition_flag = $this->get_in_int_val("order_partition_flag",0);
        $period_flag = $this->get_in_int_val("period_flag",0);
        $disable_activity_list= $this->get_in_int_list( "disable_activity_list" );
        // $child_order_info = $this->get_in_str_val("child_order_info");

        $sys_operator        = $this->get_account();
        $userid              = $this->get_in_userid();
        $grade               = $this->get_in_grade();
        $subject             = $this->get_in_subject();
        $origin              = $this->get_in_str_val("origin");
        $is_new_stu          = $this->get_in_int_val("is_new_stu");
        $from_test_lesson_id = 0;
        if (!$lesson_total ) {
            return $this->output_err("没有购买课时");
        }

        if($require_id){
            $test_lesson_subject_id = $this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
            $origin  = $this->t_test_lesson_subject_require->get_origin($require_id);
            $tt_item = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"userid,grade,subject");
            $userid  = $tt_item["userid"]*1;
            $grade   = $this->t_student_info->get_grade($userid)*1;
            $subject = $tt_item["subject"]*1;
            $from_test_lesson_id = $this->t_test_lesson_subject_require->get_current_lessonid($require_id);
        }else{
            $from_test_lesson_id = $this->t_test_lesson_subject_require->add_require_and_lessonid(
                $this->get_account_id(),$this->get_account(),$test_lesson_subject_id,$origin,$seller_student_status
            );
        }
        if ($subject <=0) {
            return $this->output_err("没有科目.");
        }

        $from_parent_order_type=0;
        $parent_order_id=0 ;
        $default_lesson_count=1;
        $order_price_type=\App\OrderPrice\order_price_base::$cur_order_price_type;

        $account = $this->get_account();
        //$before_lesson_count= $this->t_order_info->get_order_all_lesson_count($userid, $account);
        $before_lesson_count=0;
        $price_ret=\App\OrderPrice\order_price_base::get_price_ex_cur($competition_flag,$order_promotion_type,$contract_type,$grade,$lesson_total/100,$before_lesson_count, [
            "from_test_lesson_id" => $from_test_lesson_id ,
            "period_flag"=>$period_flag,
            "userid"=>$userid,
            "contract_type"=>$contract_type,
            "disable_activity_list" =>$disable_activity_list,
        ] );
        if ( $period_flag != $price_ret["can_period_flag"] ) {
            return $this->output_err("课时数过少不支持分期,请不要启用分期");
        }



        $discount_price           = $price_ret["price"]*100;
        $promotion_discount_price = $price_ret["discount_price"]*100;
        $promotion_present_lesson = $price_ret["present_lesson_count"]*100;
        $order_activity_list      = $price_ret["desc_list"];

        $promotion_spec_discount = $this->get_in_int_val("promotion_spec_discount");
        $promotion_spec_present_lesson = $this->get_in_int_val("promotion_spec_present_lesson");

        //检查是否要特殊申请
        $need_spec_require_flag=0;
        foreach ($order_activity_list as &$order_activity_item) {
          if ($order_activity_item["need_spec_require_flag"] !=0 ) {
            $need_spec_require_flag =1;
            break;
          }
        }

        if ($need_spec_require_flag  ) {
            if(!$order_require_flag)  {
                return $this->output_err("需要,特殊申请, 请填写原因, 审批过后才有效 " );
            }else{
                $promotion_spec_discount = 0 ;
                $promotion_spec_present_lesson = 0;
            }
        }


        $promotion_spec_diff_money =0;
        if( $order_require_flag) {
            if(!$promotion_spec_present_lesson)  {
                $promotion_spec_present_lesson= $promotion_present_lesson;
            }
            if(!$promotion_spec_discount) {
                $promotion_spec_discount= $promotion_discount_price;
            }
            //直接减钱
            $promotion_spec_diff_money= (  $promotion_discount_price - $promotion_spec_discount   );
            //课时
            $promotion_spec_diff_money+= ($promotion_spec_present_lesson- $promotion_present_lesson ) * ($promotion_discount_price/$lesson_total )*0.6 ;

            if($promotion_spec_diff_money <0 ){
                $promotion_spec_diff_money =0;
            }
            if ($promotion_spec_diff_money > $promotion_spec_discount *0.5 ) {
                return $this->output_err("数据有误,折扣太多");
            }
        }else{
            $promotion_spec_present_lesson = $promotion_present_lesson;
            $promotion_spec_discount       = $promotion_discount_price;
        }
       //检查 配额 cc
        if ($promotion_spec_diff_money  &&
            $this->t_manager_info->get_account_role( $this->get_account_id() ) == E\Eaccount_role::V_2  ) {
            $now=time(NULL);
            $start_time= strtotime( date("Y-m-01", $now));
            $end_time=$now;
            $spec_diff_money_all= $this->t_order_info->get_spec_diff_money_all($start_time, $end_time, E\Eaccount_role::V_2 );
            $month_spec_money= $this->t_config_date->get_config_value(E\Econfig_date_type::V_MONTH_MARKET_SELLER_DIFF_MONEY ,strtotime(date("Y-m-01")  ) ) ;
            $promotion_spec_diff_money_t= $promotion_spec_diff_money/100 ;
            if ($spec_diff_money_all +$promotion_spec_diff_money_t  > $month_spec_money ){
                return  $this->output_err("市场配额不足,额度 $month_spec_money, 已用 [$spec_diff_money_all],  需要  [$promotion_spec_diff_money_t] ");
            }

        }elseif ($promotion_spec_diff_money  &&
                 $this->t_manager_info->get_account_role( $this->get_account_id() ) == E\Eaccount_role::V_1  ) {
            $now=time(NULL);
            $start_time= strtotime( date("Y-m-01", $now));
            $end_time=$now;
            $spec_diff_money_all= $this->t_order_info->get_spec_diff_money_all($start_time, $end_time, E\Eaccount_role::V_1 );
            $month_spec_money= $this->t_config_date->get_config_value(E\Econfig_date_type::V_MONTH_MARKET_TEACH_ASSISTANT_DIFF_MONEY ,strtotime(date("Y-m-01")  ) ) ;
            $promotion_spec_diff_money_t= $promotion_spec_diff_money/100 ;
            if ($spec_diff_money_all +$promotion_spec_diff_money_t  > $month_spec_money ){
                return  $this->output_err("市场配额不足,额度 $month_spec_money, 已用 [$spec_diff_money_all],  需要  [$promotion_spec_diff_money_t] ");
            }

        }


        //最后价格
        $price=$promotion_spec_discount;
        if ($before_lesson_count && $contract_type==0) {
            $contract_from_type=11;
        }
        $pre_price=0;

        $from_parent_order_lesson_count=0;
        //8月营销活动
        //$price = $this->get_8_month_activity($userid,$price,$lesson_total,$contract_type,$has_share_activity_flag);
        $this->t_order_info->start_transaction();

        $orderid=$this->t_order_info->add_contract(
            $sys_operator,  $userid , $origin, $competition_flag,$contract_type,$grade,$subject,$lesson_total,$price ,  $discount_price ,$discount_reason , $need_receipt, $title ,$requirement, $from_test_lesson_id , $from_parent_order_type, $parent_order_id, $default_lesson_count ,
            $order_price_type,
            $order_promotion_type,
            $promotion_discount_price,
            $promotion_present_lesson,
            $promotion_spec_discount,
            $promotion_spec_present_lesson,$contract_from_type,
            $from_parent_order_lesson_count,
            $pre_price,
            "",
            $order_partition_flag,
            $period_flag,
            $is_new_stu
        );


        if( $need_spec_require_flag || ($order_require_flag && $promotion_spec_diff_money  ) ) {
            $this->t_flow->add_flow(
                E\Eflow_type::V_SELLER_ORDER_REQUIRE,
                $this->get_account_id(),"特殊折扣",$orderid);
        }

        if ($promotion_spec_present_lesson)  { //送课时
            \App\Helper\Utils::logger("promotion_spec_present_lesson:$promotion_spec_present_lesson");

            $this->t_order_info->add_contract_contract_type_1(
                E\Efrom_parent_order_type::V_0,
                $this->get_account(),
                $userid,$orderid,
                $promotion_spec_present_lesson,$competition_flag,$grade,$subject);
        }

        if ( $promotion_spec_diff_money ) {
            $this->t_order_info->field_update_list($orderid,[
                "promotion_spec_diff_money" =>  $promotion_spec_diff_money
            ]);
        }

        //生成默认子合同
        $this->t_child_order_info->row_insert([
            "child_order_type" =>0,
            "pay_status"       =>0,
            "add_time"         =>time(),
            "parent_orderid"   =>$orderid,
            "price"            => $price
        ]);
        $this->t_order_activity_info-> add_order_info( $orderid, $order_activity_list );
        $out_args= $price_ret["out_args"];
        if (isset($out_args["ruffian_activity_use_id"])) { //优学优享现金券
            $this->t_ruffian_activity->set_to_orderid( $out_args["ruffian_activity_use_id"],$orderid);
        }

        $this->t_order_info->commit();
        return $this->output_succ();
    }

    //获取子合同信息
    public function get_child_order_list(){
        $orderid  = $this->get_in_int_val("orderid");
        $data = $this->t_child_order_info->get_all_child_order_info($orderid);
        if(empty($data)){
            $price = $this->t_order_info->get_price($orderid);
            $this->t_child_order_info->row_insert([
                "child_order_type" =>0,
                "pay_status"       =>0,
                "add_time"         =>time(),
                "parent_orderid"   =>$orderid,
                "price"            => $price
            ]);
            $data = $this->t_child_order_info->get_all_child_order_info($orderid);

        }
        foreach($data as &$item){
            if($item["child_order_type"]==0){
                $item["child_order_type_str"]="默认";
            }elseif($item["child_order_type"]==1){
                $item["child_order_type_str"]="首付款";
            }elseif($item["child_order_type"]==2){
                $item["child_order_type_str"]="分期";
            }elseif($item["child_order_type"]==3){
                $item["child_order_type_str"]="其他";
            }

            if($item["pay_status"]==0){
                $item["pay_status_str"]="未付款";
            }elseif($item["pay_status"]==1){
                $item["pay_status_str"]="已付款";
            }

            if($item["child_order_type"]==2){
                $item["period_num_info"] = $item["period_num"]."期";
            }else{
                $item["period_num_info"] ="";
            }

            $userid = $this->t_order_info->get_userid($item["parent_orderid"]);
            $parentid= $this->t_student_info->get_parentid($userid);
            $parent_name = $this->t_parent_info->get_nick($parentid);
            if(empty($item["parent_name"])){
                $item["parent_name"] = $parent_name;
            }
            \App\Helper\Utils::unixtime2date_for_item($item, "pay_time","_str");



        }
        return $this->output_succ(["data"=>$data]);
    }

    public function add_child_order_info(){
        $parent_orderid  = $this->get_in_int_val("parent_orderid");
        $child_orderid  = $this->get_in_int_val("child_orderid");
        $child_order_type  = $this->get_in_int_val("child_order_type");
        $period_num  = $this->get_in_int_val("period_num");
        $price  = $this->get_in_int_val("price");

        //默认子合同金额更改
        $old_price = $this->t_child_order_info->get_price($child_orderid);
        if($price > $old_price ){
            return $this->output_err("新增子合同金额大于可拆分金额!!");
        }

        //分期合同不能全款
        $adm = $this->get_account_id();
        if($child_order_type==2 && $adm !=349){
            $period_money = $this->t_child_order_info->get_period_price_by_parent_orderid($parent_orderid);
            $all_price = $this->t_order_info->get_price($parent_orderid);
            if(($price+$period_money) >($all_price-350000)){
                 return $this->output_err("分期合同需要设置3500元的首付款!");
            }
        }


        $new_price =  $old_price-$price;
        $this->t_child_order_info->field_update_list($child_orderid,[
           "price"  =>$new_price
        ]);

        if($child_order_type != 2){
            $period_num=0;
        }


        //新增子合同
        $this->t_child_order_info->row_insert([
            "child_order_type" =>$child_order_type,
            "pay_status"       =>0,
            "add_time"         =>time(),
            "parent_orderid"   =>$parent_orderid,
            "price"            => $price,
            "period_num"       => $period_num
        ]);


        //设置主合同是否分期
        $this->set_order_partition_flag($parent_orderid);


        return $this->output_succ();

    }

    //重置子合同
    public function rebulid_child_order_info(){
        $parent_orderid  = $this->get_in_int_val("parent_orderid");
        $child_status = $this->t_child_order_info->chick_all_order_have_pay($parent_orderid,1);
        if($child_status==1){
            return $this->output_err("已有子合同付过款,不能重置");
        }

        //删除原子合同
        $this->t_child_order_info->del_contract($parent_orderid);

        //新建子合同
        $price = $this->t_order_info->get_price($parent_orderid);
        $this->t_child_order_info->row_insert([
            "child_order_type" =>0,
            "pay_status"       =>0,
            "add_time"         =>time(),
            "parent_orderid"   =>$parent_orderid,
            "price"            => $price
        ]);

        //设置主合同是否分期
        $this->set_order_partition_flag($parent_orderid);

        return $this->output_succ();



    }
    //删除子合同
    public function delete_child_order_info(){
        $parent_orderid  = $this->get_in_int_val("parent_orderid");
        $child_orderid  = $this->get_in_int_val("child_orderid");
        $old_price = $this->t_child_order_info->get_price($child_orderid);
        $default_info = $this->t_child_order_info->get_info_by_parent_orderid($parent_orderid,0);

        if($default_info["pay_status"]>0){
            $default_orderid = $default_info["child_orderid"];
            $this->t_child_order_info->field_update_list($default_orderid,[
                "child_order_type" =>3
            ]);
            $this->t_child_order_info->field_update_list($child_orderid,[
                "child_order_type" =>0,
                "period_num"       =>0
            ]);
            //设置主合同是否分期
            $this->set_order_partition_flag($parent_orderid);


            return $this->output_succ();


            //return $this->output_err("默认子合同已付款,不能删除当前子合同");
        }

        //删除子合同
        $this->t_child_order_info->row_delete($child_orderid);

        //更新默认合同信息
        $new_price = $old_price+$default_info["price"];
        $this->t_child_order_info->field_update_list($default_info["child_orderid"],[
            "price"  =>$new_price
        ]);

        //设置主合同是否分期
        $this->set_order_partition_flag($parent_orderid);


        return $this->output_succ();




    }

    //修改子合同
    public function update_child_order_info(){
        $parent_orderid  = $this->get_in_int_val("parent_orderid");
        $child_orderid  = $this->get_in_int_val("child_orderid");
        $child_order_type  = $this->get_in_int_val("child_order_type");
        $price  = $this->get_in_int_val("price");
        $period_num  = $this->get_in_int_val("period_num");

        $old_price = $this->t_child_order_info->get_price($child_orderid);
        $default_info = $this->t_child_order_info->get_info_by_parent_orderid($parent_orderid,0);


        //分期合同不能全款
        if($child_order_type==2){
            $period_money = $this->t_child_order_info->get_period_price_by_parent_orderid($parent_orderid);
            $all_price = $this->t_order_info->get_price($parent_orderid);
            if(($price+$period_money-$old_price) >($all_price-350000)){
                return $this->output_err("分期合同需要设置3500元的首付款!");
            }
        }


        if($price > ($old_price +$default_info["price"])){
            return $this->output_err("金额超出未付款总额");
        }

        if($default_info["pay_status"]>0){
            $default_orderid = $default_info["child_orderid"];
            $this->t_child_order_info->field_update_list($default_orderid,[
                "child_order_type" =>3
            ]);
            $this->t_child_order_info->row_insert([
                "child_order_type"=>0,
                "add_time"        =>time(),
                "parent_orderid"  =>$parent_orderid,
                "price"           =>0
            ]);
            $default_info = $this->t_child_order_info->get_info_by_parent_orderid($parent_orderid,0);

            //return $this->output_err("默认子合同已付款,不能修改当前子合同");
        }

        if($child_order_type != 2){
            $period_num=0;
        }



        //修改子合同
        $this->t_child_order_info->field_update_list($child_orderid,[
            "price"  =>$price,
            "child_order_type"=>$child_order_type,
            "period_num"   =>$period_num
        ]);



        //更新默认合同信息
        $new_price = $old_price+$default_info["price"]-$price;
        if($new_price<0){
            return $this->output_err("金额超限,请确认!");
        }
        $this->t_child_order_info->field_update_list($default_info["child_orderid"],[
            "price"  =>$new_price
        ]);

        //设置主合同是否分期
        $this->set_order_partition_flag($parent_orderid);


        return $this->output_succ();




    }




    public function get_test_lesson_info_by_require_id()
    {
        $require_id=$this->get_in_require_id();

        $item=$this->t_test_lesson_subject_require->get_test_lesson_info( $require_id);
        E\Egrade::set_item_value_str($item);
        E\Esubject::set_item_value_str($item);
        return $this->output_succ(["data"=> $item] );
    }
    public function get_add_user_order_info() {
        $test_lesson_subject_id=$this->get_in_test_lesson_subject_id();

        $item=$this->t_test_lesson_subject->get_add_user_order_info($test_lesson_subject_id);
        if (!$item) {
        }
        if ($item["current_lessonid"]) {
            return $this->output_err("有试听课，不能未听报") ;
        }
        E\Egrade::set_item_value_str($item);
        E\Esubject::set_item_value_str($item);
        return $this->output_succ(["data"=> $item] );
    }
    public function get_add_user_order_info_by_userid()
    {
        $userid      = $this->get_in_userid();
        $is_ass_flag = $this->get_in_int_val("is_ass_flag");

        $item = $this->t_student_info->field_get_list($userid,"userid,nick,phone,grade,origin");
        E\Egrade::set_item_value_str($item);
        if ($is_ass_flag) {
            $item["origin"] = "1助教-". $this->get_account();
        }
        return $this->output_succ(["data"=> $item] );
    }

    public function get_stu_performance_for_seller()
    {
        $require_id = $this->get_in_require_id();
        $stu_info = $this->t_test_lesson_subject_require->get_stu_performance_for_seller($require_id);
        return $this->output_succ(["data"=>$stu_info]);
    }

    /**
     * 试听排课处的确认课时
     * 路径：/seller_student_new2/test_lesson_plan_list
     */
    public function confirm_test_lesson(){
        $require_id   = $this->get_in_require_id();
        $success_flag = $this->get_in_str_val("success_flag");
        $fail_reason  = $this->get_in_str_val("fail_reason");
        $test_lesson_fail_flag    = $this->get_in_str_val("test_lesson_fail_flag");
        $fail_greater_4_hour_flag = $this->get_in_str_val("fail_greater_4_hour_flag");
        if ($success_flag==1 || $success_flag==0 ) {
            $fail_reason              = "";
            $test_lesson_fail_flag    = 0;
            $fail_greater_4_hour_flag = 0;
        }
        $lessonid = $this->t_test_lesson_subject_require->get_current_lessonid($require_id);

        $this->t_test_lesson_subject_sub_list->field_update_list($lessonid,[
            "confirm_adminid"          => $this->get_account_id(),
            "confirm_time"             => time(NULL),
            "success_flag"             => $success_flag,
            "fail_reason"              => $fail_reason,
            "test_lesson_fail_flag"    => $test_lesson_fail_flag,
            "fail_greater_4_hour_flag" => $fail_greater_4_hour_flag,
        ]);

        if ($fail_greater_4_hour_flag==1 || $test_lesson_fail_flag>100) {
            $lesson_del_flag=1;
        }else{
            $lesson_del_flag=0;
        }

        $lesson_info      = $this->t_lesson_info->get_lesson_info($lessonid);
        $phone            = $this->t_seller_student_new->get_phone($lesson_info["userid"]);
        $nick             = $this->cache_get_student_nick($lesson_info["userid"]);
        $lesson_start_str = \App\Helper\Utils::unixtime2date($lesson_info["lesson_start"],'m-d H:i');

        if($lesson_info['lesson_del_flag']!=$lesson_del_flag){
            $this->t_lesson_info->field_update_list($lessonid,[
                "lesson_del_flag" => $lesson_del_flag,
            ]);
            $this->add_cancel_lesson_operate_info($lessonid,$lesson_info['lesson_del_flag'],$lesson_del_flag);
        }

        $lesson_time  = \App\Helper\Utils::fmt_lesson_time($lesson_info["lesson_start"],$lesson_info["lesson_end"]);
        $teacherid    = $lesson_info["teacherid"] ;
        $teacher_nick = $this->cache_get_teacher_nick($teacherid);

        if($test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_100 || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_1  ){
            $this->t_test_lesson_subject_require->set_test_lesson_status(
                $require_id,E\Eseller_student_status::V_120,$this->get_account()
            );

        }else{
            $this->t_test_lesson_subject_require->set_test_lesson_status(
                $require_id,E\Eseller_student_status::V_290,$this->get_account()
            );
        }


        /**
         * @demand 课程取消后 通知到对应咨询（或者对应助教），对应教务
         * @date 2017/11/23
         **/
        $cancel_reason = E\Etest_lesson_fail_flag::get_desc($test_lesson_fail_flag);

        if($success_flag == 2){
            $set_lesson_adminid = $this->t_test_lesson_subject_sub_list->get_set_lesson_adminid($lessonid);
            $teacher_phone      = $this->t_teacher_info->get_phone($lesson_info["teacherid"]);
            $this->t_manager_info->send_wx_todo_msg_by_adminid(
                $set_lesson_adminid,
                "来自:".$this->get_account(),
                "课程取消--[$phone][$nick],老师[$teacher_nick][$teacher_phone] 上课时间[ $lesson_start_str] 取消原因:$cancel_reason","",""
            );

            $require_adminid = $this->t_test_lesson_subject_require->get_cur_require_adminid($require_id);
            if($require_adminid != $set_lesson_adminid){
                $this->t_manager_info->send_wx_todo_msg_by_adminid(
                    $require_adminid,
                    "$require_adminid 来自:".$this->get_account(),
                    "课程取消--[$phone][$nick],老师[$teacher_nick][$teacher_phone] 上课时间[ $lesson_start_str] 取消原因:$cancel_reason","",""
                );
            }
        }


        /**
         * @ 不付老师工资
         * @ 老师原因取消试听课
         * @ "[不付] 老师未到/旷课 ",
         * @ "[不付] 看错时间/抢错试听课 ",
         * @ "[不付] 内容不全/试卷/教材不清楚 ",
         * @ "[不付] 老师有常规课冲突 ",
         * @ "[不付] 老师个人原因 ",
        */
        if( $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_109
            || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_110
            || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_112
            || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_111
            || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_113
        ){
            $cancel_cause = '';
            if($test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_111){
                $cancel_cause = '学生原因';
            }else{
                $cancel_cause = '老师您的原因';
            }
            /**
             * 试听取消-不付工资2-14
             * SMS_46785153
             * 课程取消通知：${name}老师您好，您在${lesson_time} 的试听课由于${reason}无法如期进行，故作取消；
             我们会尽快给您安排新的试听课机会，请及时留意理优的推送通知。
            */
            \App\Helper\Utils::sms_common($teacher_phone,46785153,[
                "name"        => $teacher_nick,
                "lesson_time" => $lesson_time." ".$nick,
                "reason"      => $cancel_cause,
            ]);

            /**
             * 模板ID : eHa4a9BoAbEycjIYSakPHx7zkqXDLoHbwEy6HDj4Gb4
             * 标题   : 课程取消通知
             * {{first.DATA}}
             * 课程类型：{{keyword1.DATA}}
             * 上课时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $openid = $this->t_teacher_info->get_wx_openid($lesson_info["teacherid"]);
            if($openid!=''){
                $first_info  = $teacher_nick."老师您好！您在".$lesson_time.",".$nick."学生的试听课由于".$cancel_cause."无法如期进行,故作取消";
                $remark_info = "理优教务老师会尽快给您再次安排适合的试听课机会，请您及时留意理优的推送通知";
                $template_id = "eHa4a9BoAbEycjIYSakPHx7zkqXDLoHbwEy6HDj4Gb4";//old

                $data['first']    = $first_info;
                $data['keyword1'] = "试听课";
                $data['keyword2'] = $lesson_time;
                $data['remark']   = $remark_info;
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data); //正式
            }
        }


        /**
         * @ 4小时外取消
         * @ 课程取消
         * @ [不付] 学生未到/课程取消
         * @ [不付]换老师/课程取消
         * @ [不付] 换时间/学生设备出错
         **/
        if($fail_greater_4_hour_flag && ( $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_100 || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_106 || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_107 || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_108)){
            /**
             * 试听取消-不付工资2-14
             * SMS_46785153
             * 课程取消通知：${name}老师您好，您在${lesson_time} 的试听课由于${reason}无法如期进行，故作取消；
             我们会尽快给您安排新的试听课机会，请及时留意理优的推送通知。
            */
            \App\Helper\Utils::sms_common($teacher_phone,46785153,[
                "name"        => $teacher_nick,
                "lesson_time" => $lesson_time." ".$nick,
                "reason"      => "学生原因",
            ]);

            $openid = $this->t_teacher_info->get_wx_openid($lesson_info["teacherid"]);
            if($openid!=''){
                $first_info  = $teacher_nick."老师您好！您在".$lesson_time.",".$nick."学生的试听课由于学生无法如期进行,故作取消";
                $remark_info = "理优教务老师会尽快给您再次安排适合的试听课机会，请您及时留意理优的推送通知";
                $template_id = "eHa4a9BoAbEycjIYSakPHx7zkqXDLoHbwEy6HDj4Gb4";//old

                $data['first']    = $first_info;
                $data['keyword1'] = "试听课";
                $data['keyword2'] = $lesson_time;
                $data['remark']   = $remark_info;
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);

            }
        }


        /**
         * @ 4小时内取消
         * @ [付] 学生未到/课程取消
         * @ [付] 换时间/学生设备网络出错
         **/
        if(!$fail_greater_4_hour_flag && ( $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_1 || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_2 )){
            $remark_ex = "";
            /**
             * 通知老师课程取消-付工资2-14
             * SMS_46680138
             * 课程取消通知：${name}老师您好，您在${lesson_time}时间，${student_nick}学生的试听课由于${reason}无法如期进行，
             故作取消。本次课的课时费将照常如数结算给您！我们会尽快给您安排新的试听课机会，请及时留意理优的推送通知。
            */
            \App\Helper\Utils::sms_common($teacher_phone, 46680138,[
                "name"         => $teacher_nick,
                "lesson_time"  => $lesson_time,
                "student_nick" => $nick,
                "reason"       => "学生原因",
            ]);
            $remark_ex = "本次课的课时费将照常如数结算给您！";

            $openid = $this->t_teacher_info->get_wx_openid($lesson_info["teacherid"]);
            if($openid!=''){
                $first_info  = $teacher_nick."老师您好！您在".$lesson_time.",".$nick."学生的试听课由于学生无法如期进行,故作取消";
                $remark_info = $remark_ex."理优教务老师会尽快给您再次安排适合的试听课机会，请您及时留意理优的推送通知";
                $template_id = "eHa4a9BoAbEycjIYSakPHx7zkqXDLoHbwEy6HDj4Gb4";//old

                $data['first']    = $first_info;
                $data['keyword1'] = "试听课";
                $data['keyword2'] = $lesson_time;
                $data['remark']   = $remark_info;
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);

            }
        }
        return $this->output_succ();
    }

    /**
     * 试听课课时确认
     * 路径：/tea_manage/lesson_list
     */
    public function confirm_test_lesson_ass(){
        $lessonid                 = $this->get_in_lessonid();
        $success_flag             = $this->get_in_str_val("success_flag");
        $fail_reason              = $this->get_in_str_val("fail_reason");
        $test_lesson_fail_flag    = $this->get_in_str_val("test_lesson_fail_flag");
        $fail_greater_4_hour_flag = $this->get_in_str_val("fail_greater_4_hour_flag");

        //检测课程确认的时间
        $check_flag  = $this->check_lesson_confirm_time_by_lessonid($lessonid);
        if($check_flag !== true){
            return $check_flag;
        }

        if ($success_flag==1 || $success_flag==0){
            $fail_reason              = "";
            $test_lesson_fail_flag    = 0;
            $fail_greater_4_hour_flag = 0;
        }

        $this->t_test_lesson_subject_sub_list->field_update_list($lessonid,[
            "confirm_adminid"          => $this->get_account_id(),
            "confirm_time"             => time(NULL),
            "success_flag"             => $success_flag,
            "fail_reason"              => $fail_reason,
            "test_lesson_fail_flag"    => $test_lesson_fail_flag,
            "fail_greater_4_hour_flag" => $fail_greater_4_hour_flag,
        ]);

        if ($fail_greater_4_hour_flag ==1 || $test_lesson_fail_flag>100 ) {
            $lesson_del_flag = 1;
        }else{
            $lesson_del_flag = 0;
        }

        $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);

        if($lesson_info['lesson_del_flag']!=$lesson_del_flag){
            $this->t_lesson_info->field_update_list($lessonid,[
                "lesson_del_flag" => $lesson_del_flag,
            ]);
            $this->add_cancel_lesson_operate_info($lessonid,$lesson_info['lesson_del_flag'],$lesson_del_flag);
        }

        $phone = $this->t_seller_student_new->get_phone($lesson_info["userid"]);
        $nick  = $this->cache_get_student_nick($lesson_info["userid"]);
        $lesson_start_str = \App\Helper\Utils::unixtime2date($lesson_info["lesson_start"],'m-d H:i');
        $lesson_time      = \App\Helper\Utils::fmt_lesson_time($lesson_info["lesson_start"],$lesson_info["lesson_end"]);
        $teacherid        = $lesson_info["teacherid"] ;
        $teacher_nick     = $this->cache_get_teacher_nick($teacherid);
        $require_id       = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);

        if($test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_100 || $test_lesson_fail_flag == E\Etest_lesson_fail_flag::V_1){
            $this->t_test_lesson_subject_require->set_test_lesson_status(
                $require_id,E\Eseller_student_status::V_120,$this->get_account()
            );

            $set_lesson_adminid = $this->t_test_lesson_subject_sub_list->get_set_lesson_adminid($lessonid);
            $teacher_phone      = $this->t_teacher_info->get_phone($lesson_info["teacherid"]);

            $this->t_manager_info->send_wx_todo_msg_by_adminid(
                $set_lesson_adminid,
                "来自:".$this->get_account(),
                "课程取消--[$phone][$nick],老师[$teacher_nick][$teacher_phone] 上课时间[ $lesson_start_str]","",""
            );

            $remark_ex = "";
            if($fail_greater_4_hour_flag ) {
                /**
                 * 试听取消-不付工资2-14
                 * SMS_46785153
                 * 课程取消通知：${name}老师您好，您在${lesson_time} 的试听课由于${reason}无法如期进行，故作取消；
                 我们会尽快给您安排新的试听课机会，请及时留意理优的推送通知。
                */
                \App\Helper\Utils::sms_common($teacher_phone,46785153,[
                    "name"        => $teacher_nick,
                    "lesson_time" => $lesson_time." ".$nick,
                    "reason"      => "学生原因",
                ]);
            }else{
                /**
                 * 通知老师课程取消-付工资2-14
                 * SMS_46680138
                 * 课程取消通知：${name}老师您好，您在${lesson_time}时间，${student_nick}学生的试听课由于${reason}无法如期进行，
                 故作取消。本次课的课时费将照常如数结算给您！我们会尽快给您安排新的试听课机会，请及时留意理优的推送通知。
                */
                \App\Helper\Utils::sms_common($teacher_phone, 46680138,[
                    "name"         => $teacher_nick,
                    "lesson_time"  => $lesson_time,
                    "student_nick" => $nick,
                    "reason"       => "学生原因",
                ]);
                $remark_ex = "本次课的课时费将照常如数结算给您！";
            }

            /**
             * 模板ID : eHa4a9BoAbEycjIYSakPHx7zkqXDLoHbwEy6HDj4Gb4
             * 标题   : 课程取消通知
             * {{first.DATA}}
             * 课程类型：{{keyword1.DATA}}
             * 上课时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $openid = $this->t_teacher_info->get_wx_openid($lesson_info["teacherid"]);
            if($openid!=''){
                $first_info  = $teacher_nick."老师您好！您在".$lesson_time.",".$nick."学生的试听课由于学生无法如期进行,故作取消";
                $remark_info = $remark_ex."理优教务老师会尽快给您再次安排适合的试听课机会，请您及时留意理优的推送通知";
                $template_id = "eHa4a9BoAbEycjIYSakPHx7zkqXDLoHbwEy6HDj4Gb4";//old
                //$template_id = "YKGjtHUG20pS9RGBmTWm8_wYx4f30amrGv-F5NnBk8w";

                $data['first']    = $first_info;
                $data['keyword1'] = "试听课";
                $data['keyword2'] = $lesson_time;
                $data['remark']   = $remark_info;
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
            }
        }else{
            $this->t_test_lesson_subject_require->set_test_lesson_status(
                $require_id,
                E\Eseller_student_status::V_290 , $this->get_account()
            );
        }

        return $this->output_succ();
    }

    public function get_lesson_list_by_require_id_js() {
        $page_num=$this->get_in_page_num();
        $require_id = $this->get_in_require_id();
        $ret_list=$this->t_test_lesson_subject_require->get_lesson_list_by_require_id($page_num,$require_id);

        foreach($ret_list["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            \App\Helper\Utils::unixtime2date_for_item($item,"set_lesson_time");
            $this->cache_set_item_teacher_nick($item);
            E\Esubject::set_item_value_str($item);
            $item["accept_flag_str"]=\App\Helper\Common::get_set_boolean_color_str($item["accept_flag"] );
            $item["success_flag_str"]=\App\Helper\Common::get_set_boolean_color_str($item["success_flag"] );
            E\Etest_lesson_fail_flag::set_item_value_str($item);

        }

        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ(["data"=> $ret_list]);


    }
    public function ass_add_require_test_lesson() {
        $userid    = $this->get_in_userid();
        $subject   = $this->get_in_subject();
        $ass_test_lesson_type    = $this->get_in_int_val("ass_test_lesson_type");
        $green_channel_teacherid = $this->get_in_int_val("green_channel_teacherid");
        $stu_request_test_lesson_time = strtotime($this->get_in_str_val('stu_request_test_lesson_time'));
        $grade  = $this->get_in_int_val("grade");
        $stu_request_test_lesson_demand  = $this->get_in_str_val("stu_request_test_lesson_demand");
        $change_reason = trim($this->get_in_str_val('change_reason'));
        $change_teacher_reason_type = $this->get_in_int_val('change_teacher_reason_type');

        $url = $this->get_in_str_val('change_reason_url');

        \App\Helper\Utils::logger("ass_add_require_test_lesson-change_reason: $change_reason change_teacher_reason_type: $change_teacher_reason_type");

        if(!$stu_request_test_lesson_time){ return $this->output_err("请选择试听时间"); }

        if($ass_test_lesson_type == 2 && $change_teacher_reason_type == 0){
            return $this->output_err('请选择换老师类型!');
        }elseif($ass_test_lesson_type == 2 && !$change_reason){
            return $this->output_err('请填写换老师原因!');
        }elseif($ass_test_lesson_type == 2 && strlen(str_replace(" ","",$change_reason))<9){
            return $this->output_err('换老师原因不得少于3个字!');
        }

        if($url){
            $domain = config('admin')['qiniu']['public']['url'];
            $change_reason_url = $domain.'/'.$url;
        }else{
            $change_reason_url = '';
        }
        $grade=isset($grade)?$grade:$this->t_student_info->get_grade($userid);

        if($green_channel_teacherid>0){
            $is_green_flag=1;
        }else{
            $is_green_flag=0;
        }
        // init t_seller_student_new
        $phone = $this->t_seller_student_new->get_phone($userid);
        if (!$phone) {
            $phone=$this->t_student_info->get_phone($userid);

            $phone_location = \App\Helper\Common::get_phone_location($phone);

            $this->t_seller_student_new->row_insert([
                "userid"          => $userid,
                "phone"          => $phone,
                "add_time"        => time(NULL) ,
                "phone_location" => $phone_location,
            ]);
        }

        // init t_test_lesson_subject
        $test_lesson_subject_id= $this->t_test_lesson_subject->check_and_add_ass_subject(
            $this->get_account_id(),$userid,$grade,$subject,$ass_test_lesson_type);

        $origin="2助教-".E\Eass_test_lesson_type::get_desc($ass_test_lesson_type);

        $this->t_test_lesson_subject->field_update_list(
            $test_lesson_subject_id,["stu_request_test_lesson_time" => $stu_request_test_lesson_time,
                                     "stu_request_test_lesson_demand" => $stu_request_test_lesson_demand]);

        $curl_stu_request_test_lesson_time = $this->t_test_lesson_subject->get_stu_request_test_lesson_time($test_lesson_subject_id);

        $test_stu_request_test_lesson_demand = $this->t_test_lesson_subject->get_stu_request_test_lesson_demand($test_lesson_subject_id);

        $ret=$this->t_test_lesson_subject_require->add_require(
            $this->get_account_id(),
            $this->get_account(),
            $test_lesson_subject_id,
            $origin,
            $curl_stu_request_test_lesson_time,
            $grade,
            $test_stu_request_test_lesson_demand,
            $change_reason_url,
            $change_reason,
            $change_teacher_reason_type
        );

        $require_id = $this->t_test_lesson_subject->get_current_require_id($test_lesson_subject_id);

        if($ass_test_lesson_type ==2){
            $this->t_test_lesson_subject_require->field_update_list($require_id,[
                "change_teacher_reason"          => $change_reason,
                "change_teacher_reason_img_url"  => $change_reason_url,
                "change_teacher_reason_type"     => $change_teacher_reason_type
            ]);
        }

        if (!$ret){
            return $this->output_err("当前该同学的申请请求 还没处理完毕,不可新建");
        }else{
            // $require_id = $this->t_test_lesson_subject->get_current_require_id($test_lesson_subject_id);
            $ret_flag = $this->t_test_lesson_subject_require->field_update_list($require_id,[
                "green_channel_teacherid"=>$green_channel_teacherid,
                "is_green_flag"          =>$is_green_flag,
                // "change_teacher_reason"          => $change_reason,
                // "change_teacher_reason_img_url"  => $change_reason_url,
                // "change_teacher_reason_type"     => $change_teacher_reason_type
            ]);

            \App\Helper\Utils::logger("add_require: $test_lesson_subject_id ret_flag: $ret_flag");

            //确认是否第一次换老师
            $check_teacher_num = $this->t_lesson_info_b3->get_user_subject_tea_num($userid,$subject);
            if($check_teacher_num>1 && $ass_test_lesson_type ==2){
                $now_teacherid =$this->t_lesson_info_b3->get_first_user_subject_tea($userid,$subject);
                $tea_info =$this->t_teacher_info->field_get_list($now_teacherid,"grade_start,grade_end,realname");
                $subject_str = E\Esubject::get_desc($subject);
                //E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
                $grade_start =  E\Egrade_range::get_desc($tea_info["grade_start"]);
                $grade_end =  E\Egrade_range::get_desc($tea_info["grade_end"]);
                $nick = $this->t_student_info->get_nick($userid);

                $this->t_manager_info->send_wx_todo_msg_by_adminid(349,"非首次换老师","非首次换老师提醒","学生:".$nick.",原老师:".$tea_info["realname"].",科目:".$subject_str.",老师年级段:".$grade_start."至".$grade_end,"");
                $this->t_manager_info->send_wx_todo_msg_by_adminid(72,"非首次换老师","非首次换老师提醒","学生:".$nick.",原老师:".$tea_info["realname"].",科目:".$subject_str.",老师年级段:".$grade_start."至".$grade_end,"");
               


            }

            return $this->output_succ();
        }
    }



    public function ass_del_require() {
        $require_id=$this->get_in_require_id();
        $accept_flag=$this->t_test_lesson_subject_require->get_accept_flag($require_id);
        if ($accept_flag !=0) {
            return $this->output_err("教务已处理,不能删除");
        }
        if ($this->t_test_lesson_subject_sub_list->get_count_by_require_id($require_id)>0) {
            return $this->output_err("数据有误1");
        }

        $test_lesson_subject_id=$this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        if ($this->t_test_lesson_subject->get_current_require_id($test_lesson_subject_id) != $require_id){
            return $this->output_err("数据有误2");
        }

        $this->t_test_lesson_subject_require->row_delete($require_id);
        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "current_require_id" =>NULL,
        ]);
        return $this->output_succ();
    }

    public function tmk_set_no_called_to_self()  {
        $test_lesson_subject_id= $this->get_in_test_lesson_subject_id();
        $userid=$this->t_test_lesson_subject->get_userid($test_lesson_subject_id);



        $admin_assign_time= $this->t_seller_student_new->get_admin_assign_time($userid);
        $now=time(NULL);
        if ($admin_assign_time > $now-60
        ) { //
            \App\Helper\Utils::logger(" XXXx  $admin_assign_time > $now -60");

            return $this->output_err("已经被抢了");
        }
        //check 多科目
        $this->t_seller_student_new->field_update_list($userid,[
            "tmk_join_time"        => time(NULL) ,
            "tmk_assign_time"      => time(NULL) ,
            "tmk_student_status"  => 0,
            "next_revisit_time"  => 0,
            "tmk_adminid"          => $this->get_account_id()  ,
            "admin_revisiterid"    => 0 ,
            "admin_assign_time"    => 0,
            "tq_called_flag"       => 0,
            "seller_resource_type" => 0,
        ]);

        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "seller_student_status" => 0,
            "require_adminid" => 0,
        ]);


        $phone=$this->t_seller_student_new->get_phone($userid);
        $account= $this->get_account();
        $ret_update = $this->t_book_revisit->add_book_revisit(
            $phone,
            sprintf(
                "操作者: $account TMK 抢单: 电话[$phone]  "
            ),
            "system"
        );

        return $this->output_succ();
    }

    public function set_no_called_to_self()  {
        $test_lesson_subject_id= $this->get_in_test_lesson_subject_id();
        $userid=$this->t_test_lesson_subject->get_userid($test_lesson_subject_id);

        $new_flag=$this->get_in_int_val("new_flag",0);
        $free_flag=$this->get_in_int_val("free_flag",0);
        $adminid=$this->get_account_id();

        $ssn_info= $this->t_seller_student_new->field_get_list($userid,"admin_assign_time,has_pad");
        $admin_assign_time=$ssn_info["admin_assign_time"];
        $has_pad= $ssn_info["has_pad"];

        $now=time(NULL);
        if ($admin_assign_time > $now-60) { //
            return $this->output_err("11已经被抢了");
        }
        if ($new_flag) { //新资源
            //持有个数
            if ( $has_pad != E\Epad_type::V_10 || true ) {
                if(!$this->t_seller_student_new->check_admin_add($adminid,$get_count,$max_day_count )){
                    return $this->output_err("目前你持有的例子数[$get_count]>=最高上限[$max_day_count]");
                }
                if (!$this->t_seller_new_count->check_and_add_new_count($adminid,"获取新例子",$userid))  {
                    return $this->output_err("今天的配额,已经用完了");
                }
            }
            $this->t_id_opt_log->add(E\Edate_id_log_type::V_SELLER_GET_NEW_COUNT
                                     ,$adminid,$userid);
        }else  {
            if ($free_flag) {
                //持有个数
                if(!$this->t_seller_student_new->check_admin_add($adminid,$get_count,$max_day_count )){
                    return $this->output_err("目前你持有的例子数[$get_count]>=最高上限[$max_day_count]");
                }
                //限制公海抢个数
                // $start_time = strtotime(date("Y-m-d"));
                // $end_time = time();
                // $history_count = $this->t_id_opt_log->get_history_count(E\Edate_id_log_type::V_SELLER_GET_HISTORY_COUNT,$adminid,$start_time,$end_time);
                // if($history_count>30){
                //     return $this->output_err("每人每天限制领取30个公海例子,您已领取".$history_count."个!");
                // }
                $this->t_id_opt_log->add(E\Edate_id_log_type::V_SELLER_GET_HISTORY_COUNT
                                     ,$adminid,$userid);
            }else{
                $no_call_count=$this->t_seller_student_new->get_no_call_count($this->get_account_id());
                if($no_call_count>=50) {
                    return $this->output_err("你已经有50个未回访的用户了,先去回访吧:<");
                }
            }
        }

        //check 多科目
        $seller_resource_type= $new_flag? E\Eseller_resource_type::V_0: E\Eseller_resource_type::V_1;

        //自己回流过的例子
        $add_time = $this->t_test_subject_free_list->get_row_by_userid_adminid($this->get_account_id(),$userid);
        $hand_get_adminid = $add_time>0?0:5;
        $this->t_seller_student_new->field_update_list($userid,[
            "admin_revisiterid" => $this->get_account_id() ,
            "admin_assign_time" => $now,
            "tq_called_flag"    =>0,
            "hold_flag" => 1,
            "seller_resource_type" => $seller_resource_type ,
            "hand_get_adminid" => $hand_get_adminid,
        ]);

        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "seller_student_status" => 0,
            "require_adminid" => $this->get_account_id(),
        ]);
        //更新 up adminid info
        $this->t_seller_student_new->set_up_adminid_info($userid, $this->get_account_id());


        $phone=$this->t_seller_student_new->get_phone($userid);
        $account= $this->get_account();
        $ret_update = $this->t_book_revisit->add_book_revisit(
            $phone,
            sprintf(
                "操作者: $account [" . E\Eseller_resource_type::get_desc($seller_resource_type). "] 抢单: 电话[$phone]  "
            ),
            "system"
        );

        return $this->output_succ();
    }

    public function ass_add_seller_user()
    {
        $phone         = trim($this->get_in_phone());
        $subject       = $this->get_in_subject();
        $origin_userid = $this->get_in_int_val("origin_userid");
        $origin_assistantid = $this->get_in_int_val("origin_assistantid");
        $grade         = $this->get_in_grade();
        $nick = $this->get_in_str_val("nick");
        $origin_flag = $this->get_in_int_val("origin_flag");

        $origin="转介绍";
        $has_pad=0;
        $userid=$this->t_phone_to_user->get_userid_by_phone($phone);
        if ($userid && $this->t_seller_student_new->get_phone($userid)) {

            $admin_nick=$this->cache_get_account_nick(
                $this->t_seller_student_new->get_admin_revisiterid($userid)
            );
            return $this->output_err("系统中已有这个人的账号了,销售负责人:$admin_nick");
            /*
            if ($this->t_test_lesson_subject->check_subject($userid,$subject))  {
                return $this->output_err("已经有了这个科目的例子了,不能增加");
            }
            */
        }

        $adminid=$this->get_account_id();

        $userid=$this->t_seller_student_new->book_free_lesson_new(
            $nick,$phone,$grade,$origin,$subject,$has_pad);
        //处理
        $this->t_student_info->field_update_list($userid,[
            "originid"           => 1,
            "origin_assistantid" =>  $origin_assistantid,
            "origin_userid"      => $origin_userid,
            "reg_time" => time(NULL),
        ]);
        $account= $this->get_account();

        $origin_assistant_nick = $this->cache_get_account_nick($origin_assistantid);

        $origin_nick=$this->cache_get_student_nick($origin_userid);

        if($origin_flag==1){
            $this->t_seller_student_new->set_admin_info(0,[$userid],$origin_assistantid,$origin_assistantid);
            $nick=$this->t_student_info->get_nick($userid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid($origin_assistantid,"转介绍","学生[$nick][$phone]","","/seller_student_new/ass_seller_student_list?userid=$userid");
            $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: $account , 负责人: [$origin_assistant_nick] 转介绍  来自:[$origin_nick] ",
                "system"
            );

            $this->t_manager_info->send_wx_todo_msg_by_adminid(349,"转介绍","学生[$nick][$phone]","助教自签,类型1","/seller_student_new/seller_student_list_all?userid=$userid");



        }elseif($origin_flag==2){
            $groupid=0;
            $campus_id = $this->t_admin_group_user->get_campus_id_by_adminid($origin_assistantid);
            $master_adminid_arr = $this->t_admin_main_group_name->get_seller_master_adminid_by_campus_id($campus_id);
            $list=[];
            foreach($master_adminid_arr as $item){
                $list[$item["groupid"]] = $item["master_adminid"];
            }
            $num_all = count($list);
            $i=0;
            \App\Helper\Utils::logger(111);

            foreach($list as $k=>$val){
                $json_ret=\App\Helper\Common::redis_get_json("SELLER_MASTER_AUTO_ASSIGN_$k");
                if (!$json_ret) {
                    $json_ret=0;
                }
                \App\Helper\Common::redis_set_json("SELLER_MASTER_AUTO_ASSIGN_$k", $json_ret);
                if($json_ret==1){
                    $i++;
                }
            }
            if($i==$num_all){
                foreach($list as $k=>$val){
                    \App\Helper\Common::redis_set_json("SELLER_MASTER_AUTO_ASSIGN_$k", 0);
                }
            }
            \App\Helper\Utils::logger(222);
            foreach($list as $k=>$val){
                $json_ret=\App\Helper\Common::redis_get_json("SELLER_MASTER_AUTO_ASSIGN_$k");
                if($json_ret==0){
                    $groupid= $k;
                    \App\Helper\Common::redis_set_json("SELLER_MASTER_AUTO_ASSIGN_$k", 1);
                    break;
                }
            }
            //根据经理id再获得总监id
            $sub_assign_adminid_1 = $this->t_admin_main_group_name->get_major_master_adminid(-1,$groupid);
            if($sub_assign_adminid_1==0){
                $sub_assign_adminid_1= 287;
            }
            $this->t_seller_student_new->field_update_list($userid,[
                "sub_assign_adminid_1"  =>$sub_assign_adminid_1,
                "sub_assign_adminid_2"  =>$sub_assign_adminid_1,
                "admin_revisiterid"     =>$sub_assign_adminid_1,
                "admin_assign_time"     =>time()
            ]);

            $this->t_manager_info->send_wx_todo_msg_by_adminid($sub_assign_adminid_1,"转介绍","学生[$nick][$phone]","","/seller_student_new/seller_student_list_all?userid=$userid");
            $this->t_manager_info->send_wx_todo_msg_by_adminid(349,"转介绍","学生[$nick][$phone]","总监:".$sub_assign_adminid_1."类型2","/seller_student_new/seller_student_list_all?userid=$userid");

            $name = $this->t_manager_info->get_account($sub_assign_adminid_1);
            $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: $account , 负责人: [$origin_assistant_nick] 转介绍  来自:[$origin_nick] ,分配给销售总监".$name,
                "system"
            );

        }else{
            $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: $account , 负责人: [$origin_assistant_nick] 转介绍  来自:[$origin_nick] ",
                "system"
            );

            //分配给原来的销售
            $admin_revisiterid= $this->t_order_info-> get_last_seller_by_userid($origin_userid);
            //$admin_revisiterid= $origin_assistantid;

            if ($admin_revisiterid) {
                $this->t_seller_student_new->set_admin_info(0,[$userid],$admin_revisiterid,$admin_revisiterid);
                $nick=$this->t_student_info->get_nick($userid);
                $this->t_manager_info->send_wx_todo_msg_by_adminid($admin_revisiterid,"转介绍","学生[$nick][$phone]","","/seller_student_new/seller_student_list_all?userid=$userid");

                $this->t_manager_info->send_wx_todo_msg_by_adminid(349,"转介绍","学生[$nick][$phone]","给原销售,类型3","/seller_student_new/seller_student_list_all?userid=$userid");
            }

        }
        // $account_role = $this->t_manager_info->get_account_role($origin_assistantid);
        // if($account_role==1){
        //     //分配销售总监
        //     $sub_assign_adminid_1=0;
        //     $campus_id = $this->t_admin_group_user->get_campus_id_by_adminid($origin_assistantid);
        //     $master_adminid_arr = $this->t_admin_main_group_name->get_seller_master_adminid_by_campus_id($campus_id);
        //     $list=[];
        //     foreach($master_adminid_arr as $item){
        //         $list[] = $item["master_adminid"];
        //     }
        //     $num_all = count($list);
        //     $i=0;
        //     foreach($list as $val){
        //         $json_ret=\App\Helper\Common::redis_get_json("SELLER_MASTER_AUTO_ASSIGN_$val");
        //         if (!$json_ret) {
        //             $json_ret=0;
        //         }
        //         \App\Helper\Common::redis_set_json("SELLER_MASTER_AUTO_ASSIGN_$val", $json_ret);
        //         if($json_ret==1){
        //             $i++;
        //         }
        //     }
        //     if($i==$num_all){
        //         foreach($list as $val){
        //             \App\Helper\Common::redis_set_json("SELLER_MASTER_AUTO_ASSIGN_$val", 0);
        //         }
        //     }

        //     foreach($list as $val){
        //         $json_ret=\App\Helper\Common::redis_get_json("SELLER_MASTER_AUTO_ASSIGN_$val");
        //         if($json_ret==0){
        //             $sub_assign_adminid_1= $val;
        //             \App\Helper\Common::redis_set_json("SELLER_MASTER_AUTO_ASSIGN_$val", 1);
        //             break;
        //         }
        //     }
        //     if($sub_assign_adminid_1==0){
        //         $sub_assign_adminid_1= 287;
        //     }
        //     $this->t_seller_student_new->field_update_list($userid,[
        //        "sub_assign_adminid_1"  =>$sub_assign_adminid_1
        //     ]);

        //     $this->t_manager_info->send_wx_todo_msg_by_adminid($sub_assign_adminid_1,"转介绍","学生[$nick][$phone]","","/seller_student_new/seller_student_list_all?userid=$userid");
        //     $this->t_manager_info->send_wx_todo_msg_by_adminid(349,"转介绍","学生[$nick][$phone]","总监:".$sub_assign_adminid_1,"/seller_student_new/seller_student_list_all?userid=$userid");

        //     $name = $this->t_manager_info->get_account($sub_assign_adminid_1);
        //     $this->t_book_revisit->add_book_revisit(
        //         $phone,
        //         "操作者: $account , 负责人: [$origin_assistant_nick] 转介绍  来自:[$origin_nick] ,分配给销售经理".$name,
        //         "system"
        //     );



        // }else{
        //     $this->t_book_revisit->add_book_revisit(
        //         $phone,
        //         "操作者: $account , 负责人: [$origin_assistant_nick] 转介绍  来自:[$origin_nick] ",
        //         "system"
        //     );

        //     //分配给原来的销售
        //     $admin_revisiterid= $this->t_order_info-> get_last_seller_by_userid($origin_userid);
        //     //$admin_revisiterid= $origin_assistantid;

        //     if ($admin_revisiterid) {
        //         $this->t_seller_student_new->set_admin_info(0,[$userid],$admin_revisiterid,$admin_revisiterid);
        //         $nick=$this->t_student_info->get_nick($userid);
        //         $this->t_manager_info->send_wx_todo_msg_by_adminid($admin_revisiterid,"转介绍","学生[$nick][$phone]","","/seller_student_new/seller_student_list_all?userid=$userid");
        //     }

        // }


        return $this->output_succ();

    }
    public function del_seller_student() {
        $test_lesson_subject_id = $this->get_in_test_lesson_subject_id();
        $userid                 = $this->t_test_lesson_subject->get_userid($test_lesson_subject_id);
        //删除限制
        $phone = $this->t_phone_to_user->get_phone($userid);
        $ret_phone = $this->t_tq_call_info->get_row_by_phone($phone);
        if($ret_phone){
            return $this->output_err('有通话记录,不能删除!');
        }

        $this->t_test_lesson_subject->row_delete($test_lesson_subject_id);
        $this->t_seller_student_origin->del_by_userid($userid);
        $subejct_count=$this->t_test_lesson_subject->get_count_by_userid($userid);
        if(!$subejct_count) {//没有预约了,可以删除
            $this->t_seller_student_new->row_delete($userid);
        }

        return $this->output_succ();
    }

    public function get_seller_menu_info() {
        //list($start_time,$end_time)=$this->get_in_date_range(-60,30);
        $adminid=$this->get_account_id();
        $no_call_count=$this->t_seller_student_new->get_tq_no_call_count($adminid);

        $fail_count=$this->t_seller_student_new->get_fail_count_from_require($adminid);

        $today_next_revisit_count=$this->t_seller_student_new->get_next_revisit_count($adminid);
        $row_item=$this->t_seller_student_new-> get_lesson_status_count($adminid );

        $row_item["no_call_count"] = $no_call_count;
        $row_item["fail_count"] = $fail_count;
        $row_item["today_next_revisit_count"] = $today_next_revisit_count;


        return $this->output_succ($row_item);

    }
    public function seller_student_lesson_set_notify_flag () {
        $require_id = $this->get_in_require_id();
        $notify_flag= $this->get_in_int_val("notify_flag");
        $this->t_test_lesson_subject_require-> set_notify_lesson_flag( $require_id, $notify_flag, $this->get_account());

        return $this->output_succ();
    }

    public function upload_from_xls()
    {
        $file = Input::file('file');
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            (new common_new()) ->upload_from_xls_data( $realPath);

            return outputjson_success();
        } else {
            return outputjson_ret(false);
        }
    }

    public function upload_item_student_from_xls()
    {
        $file = Input::file('file');
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $ret = $objPHPExcel->getActiveSheet()->toArray();
            $ret_info = [];
            foreach($ret as $item){
                $phone = substr($item[1],0,11);
                $account = $item[5];
                $adminid = $this->t_manager_info->get_adminid_by_account($account);
                if($adminid>0 && $phone!=''){
                    $userid = $this->t_phone_to_user->get_userid($phone);
                    $ret = $this->t_seller_student_new->field_update_list($userid,[
                        // "admin_assignerid"  => 0,
                        // "sub_assign_adminid_1"  => $adminid,
                        // "sub_assign_time_1"  => time(),
                        "admin_revisiterid"  => $adminid,
                        "admin_assign_time"  => time(),
                    ]);
                    echo $ret;
                }
            }
            dd($arr);
            return outputjson_success();
        } else {
            return outputjson_ret(false);
        }
    }

    public function upload_ass_stu_from_xls(){
        $file = Input::file('file');
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            foreach($arr as $k=>&$val){
                if(empty($val[0]) || $k==0 ){
                    unset($arr[$k]);
                }

            }
            $str="";

            foreach($arr as $v){
                $userid = $this->t_student_info->get_userid_by_name($v[2],$v[1]);
                $str .= $userid.",";
            }

            $this->t_teacher_info->field_update_list(240314,[
                "limit_plan_lesson_reason" =>trim($str,",")
            ]);
            //(new common_new()) ->upload_from_xls_data( $realPath);

            return outputjson_success();
        } else {
            //return 111;
            //dd(222);
            return outputjson_ret(false);
        }

    }

    public function upload_subject_grade_textbook_from_xls(){
        $file = Input::file('file');
        $list    = E\Eregion_version::$desc_map;
        $list_new =[];
        foreach($list as $k=>$i){
            $list_new[$i] = $k;
        }
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            foreach($arr as $k=>&$val){
                if(empty($val[0]) || $k==0 || $k==1 || $k==2){
                    unset($arr[$k]);
                }

            }
            foreach($arr as $item){
                $small = $item[2];
                $small_arr = explode("、",$small);
                $small_list=[];
                foreach($small_arr as $v){
                    if(isset($list_new[$v])){
                        $small_list[] = $list_new[$v];
                    }
                }

                $small_str =  implode(",",$small_list);

                // $is_exist3 = $this->t_location_subject_grade_textbook_info->check_is_exist($item[0],$item[1],100,2);
                $is_exist3=0;
                if($is_exist3>0){
                    $this->t_location_subject_grade_textbook_info->field_update_list($is_exist3,[
                        "teacher_textbook" =>$small_str
                    ]);
                }else{
                    $this->t_location_subject_grade_textbook_info->row_insert([
                        "province"  =>$item[0],
                        "city"      =>$item[1],
                        "subject"   =>3,
                        "grade"     =>100,
                        "teacher_textbook"=>$small_str,
                        // "educational_system" =>$item[2]
                    ]);
                }



                $middle = $item[3];
                $middle_arr = explode("、",$middle);
                $middle_list=[];
                foreach($middle_arr as $v){
                    if(isset($list_new[$v])){
                        $middle_list[] = $list_new[$v];
                    }
                }

                $middle_str =  implode(",",$middle_list);

                //$is_exist = $this->t_location_subject_grade_textbook_info->check_is_exist($item[0],$item[1],200,2);
                $is_exist=0;
                if($is_exist>0){
                    $this->t_location_subject_grade_textbook_info->field_update_list($is_exist,[
                       "teacher_textbook" =>$middle_str
                    ]);
                }else{
                    $this->t_location_subject_grade_textbook_info->row_insert([
                        "province"  =>$item[0],
                        "city"      =>$item[1],
                        "subject"   =>3,
                        "grade"     =>200,
                        "teacher_textbook"=>$middle_str,
                        // "educational_system" =>$item[2]
                    ]);
                }


                $senior = $item[4];
                $senior_arr = explode("、",$senior);
                $senior_list=[];
                foreach($senior_arr as $v){
                    if(isset($list_new[$v])){
                        $senior_list[] = $list_new[$v];
                    }
                }
                $senior_str =  implode(",",$senior_list);
                // $is_exist2 = $this->t_location_subject_grade_textbook_info->check_is_exist($item[0],$item[1],300,2);
                $is_exist2=0;
                if($is_exist2>0){
                    $this->t_location_subject_grade_textbook_info->field_update_list($is_exist2,[
                        "teacher_textbook" =>$senior_str
                    ]);

                }else{

                    $this->t_location_subject_grade_textbook_info->row_insert([
                        "province"  =>$item[0],
                        "city"      =>$item[1],
                        "subject"   =>3,
                        "grade"     =>300,
                        "teacher_textbook"=>$senior_str,
                        // "educational_system" =>$item[2]
                    ]);
                }



            }


            //dd($arr);
            //(new common_new()) ->upload_from_xls_data( $realPath);

            return outputjson_success();
        } else {
            //return 111;
            //dd(222);
            return outputjson_ret(false);
        }

    }

    public function upload_permission_info_from_xls(){
        $file = Input::file('file');

        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            foreach($arr as $k=>&$val){
                if(empty($val[0]) || $k==0){
                    unset($arr[$k]);
                }

            }
            $list=[];

            //用户权限更新
            // foreach($arr as $item){
            //     @$list[$item[1]] .= $item[0].",";
            // }
            // foreach($list as $k=>$v){
            //     $v= trim($v,",");
            //     $permission_info = $this->t_manager_info->field_get_list($k,"permission,permission_backup");
            //     $this->t_manager_info->field_update_list($k,[
            //         "permission" =>$v
            //     ]);
            //     if(!$permission_info["permission_backup"]){
            //         $this->t_manager_info->field_update_list($k,[
            //             "permission_backup" => $permission_info["permission"]
            //         ]);

            //     }


            // }



            //角色更新
            // foreach($arr as $item){
            //     @$list[$item[0]] .= $item[1].",";
            // }
            // foreach($list as $k=>$v){
            //     $v= trim($v,",");
            //     $this->t_authority_group->field_update_list($k,[
            //        "group_authority"=>$v
            //     ]);

            // }


            // $arr = json_encode($list);
            // \App\Helper\Utils::logger(" PHONE:$arr ");
            // dd($arr);
            //(new common_new()) ->upload_from_xls_data( $realPath);

            return outputjson_success();
        } else {
            //return 111;
            //dd(222);
            return outputjson_ret(false);
        }



    }

    public function upload_psychological_lesson_from_xls(){
        $file = Input::file('file');
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            // foreach($arr as $k=>&$val){
            //     if(empty($val[0]) || $k==0){
            //         unset($arr[$k]);
            //     }

            // }
            // $str="";
            // foreach($arr as $item){
            //     $str .= $item[6].",";
            // }
            // $str = trim($str,",");
            // $this->t_teacher_info->field_update_list(240314,[
            //     "part_remarks"=>$str
            // ]);
            // return;

            foreach($arr as $k=>&$val){
                if(empty($val[0]) || $k==0){
                    unset($arr[$k]);
                }
                // $val[-1] = strlen($val[1]);
                if(strlen($val[1])==4){
                    $val[1]="0".$val[1];
                }
                if(strlen($val[2])==4){
                    $val[2]="0".$val[2];
                }

            }

            foreach($arr as $item){
                $day = strtotime($item[0]);
                $this->t_psychological_teacher_time_list->row_insert([
                    "day"  =>$day,
                    "start"=>$item[1],
                    "end"  =>$item[2],
                    "teacher_phone_list"=>$item[3]
                ]);
            }

            // dd($arr);
            //(new common_new()) ->upload_from_xls_data( $realPath);

            return outputjson_success();
        } else {
            //return 111;
            //dd(222);
            return outputjson_ret(false);
        }

    }
    public function upload_from_xls_cp(){
        $grade_map = [
            '小学'   => 100,
            '初中'   => 200,
            '高中'   => 300,
        ];
        $subject_map = array(
            "语文" => 1,
            "数学" => 2,
            "英语" => 3,
            "化学" => 4,
            "物理" => 5,
            "生物" => 6,
            "政治" => 7,
            "历史" => 8,
            "地理" => 9,
            "科学" => 10,
        );
        $identity_map = array(
            0 => "未设置",
            5 => "机构老师",
            6 => "公校老师",
            7 => "其他在职人士",
            8 => "在校学生",
        );
        $type_arr=[
            "未联系"=>0,
            "未接通" =>1,
            "待跟进" =>2,
            "无意向" =>3,
            "没意向" =>3,
            "已预约"=>4
        ];
        $file = Input::file('file');

        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($realPath);

            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            foreach($arr as $k=>&$val){
                if(empty($val[0]) || $k==0){
                    unset($arr[$k]);
                }
                // $val[-1] = strlen($val[1]);
                if(strlen($val[1])==4){
                    $val[1]="0".$val[1];
                }
                if(strlen($val[2])==4){
                    $val[2]="0".$val[2];
                }

            }
            foreach($arr as $item){
                $name              = $item[0];
                $answer_begin_time = strtotime($item[1]);
                $answer_end_time   = strtotime($item[2]);
                $phone             = intval($item[3]);
                $qq                = $item[4];
                $email             = $item[5];
                $subject           = $item[6];//
                $grade             = $item[7];//
                $school            = $item[8];
                $teacher_type      = $item[9];
                $reference         = $item[10];
                $lecture_revisit_type = $item[11];
                if (isset($grade_map[$grade])) {
                    $grade_ex = $grade_map[$grade] ;
                }else{
                    $grade_ex=0;
                }
                if (isset($subject_map[$subject])) {
                    $subject_ex = $subject_map[$subject] ;
                }else{
                    $subject_ex=0;
                }
                if (isset($identity_map[$teacher_type])) {
                    $teacher_type = $identity_map[$teacher_type] ;
                }else{
                    $teacher_type=0;
                }
                if (isset($type_arr[$lecture_revisit_type])) {
                    $lecture_revisit_type = $type_arr[$lecture_revisit_type] ;
                }else{
                    $lecture_revisit_type=0;
                }


                $id = $this->t_teacher_lecture_appointment_info->get_id_by_phone($phone);
                if(empty($id)){
                    $this->t_teacher_lecture_appointment_info->row_insert([
                        "answer_begin_time"  =>$answer_begin_time,
                        "answer_end_time"    =>$answer_end_time,
                        "name"               =>$name,
                        "phone"              =>$phone,
                        "email"              =>$email,
                        "qq"                 =>$qq,
                        "subject_ex"         =>$subject_ex,
                        "grade_ex"           =>$grade_ex,
                        "school"             =>$school,
                        "teacher_type"       =>$teacher_type,
                        "reference"          =>$reference,
                        "lecture_revisit_type" =>$lecture_revisit_type,
                        "accept_adminid"      =>$this->get_account_id(),
                        "accept_time"         =>time(),
                        "hand_flag"          =>1
                    ]);
                }
            }
            return outputjson_success();
        } else {
            //return 111;
            //dd(222);
            return outputjson_ret(false);
        }
    }
    public function upload_lecture_from_xls(){
        $type_arr=[
            "未联系"=>0,
            "未接通" =>1,
            "待跟进" =>2,
            "无意向" =>3,
            "没意向" =>3,
            "已预约"=>4
        ];

        $file = Input::file('file');
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            foreach($arr as $k=>&$val){
                if(empty($val[0]) || $k==0){
                    unset($arr[$k]);
                }

            }
            $userid_list="";
            foreach($arr as $v){
               $userid = intval($v[0]);
               $userid_list .= $userid.",";
            }
            $aa= trim($userid_list,",");
            $this->t_teacher_info->field_update_list(240314,["limit_plan_lesson_reason"=>$aa]);
            /* foreach($arr as $t=>&$v){
                $v[0] = intval($v[0]);
                $v[1] = $type_arr[$v[1]];
                if($t<500 && $t>=0){
                    $id = $this->t_teacher_lecture_appointment_info->get_id_by_phone($v[0]);

                    if($id>0){
                        $this->t_teacher_lecture_appointment_info->field_update_list($id,[
                            "lecture_revisit_type" =>$v[1]
                        ]);
                    }
                }

                }*/

            //dd($arr);
            //(new common_new()) ->upload_from_xls_data( $realPath);

            return outputjson_success();
        } else {
            //return 111;
            // dd(222);
            return outputjson_ret(false);
        }

    }



    public function set_test_lesson_user_to_self(){

        $userid=$this->get_in_userid();
        $adminid= $this->get_account_id();
        $add_time = $this->t_test_subject_free_list->get_add_time("userid","adminid");
        if($add_time>0){
            return $this->output_err(-1,["info"=>"该例子是您废除的!请另行选择"]);
        }
        $json_ret=\App\Helper\Common::redis_get_json("SELLER_TEST_LESSON_USER_$adminid");
        $seller_level=  $this->t_manager_info->get_seller_level( $adminid);
        $seller_level_config=\App\Helper\Config::get_seller_test_lesson_user_month_limit();
        if($seller_level == 0){
            $seller_level=3;
        }


        /*
        $level_limit_count= @$seller_level_config[$seller_level];
        if($json_ret['opt_count'] >= $level_limit_count){
            return $this->output_err(-1,["info"=>"对不起,您本月的配额已达上限"]);
        }
        */

        $this->t_seller_student_new->field_update_list($userid,[
            "admin_revisiterid"  => $adminid  ,
            "admin_assign_time"  => time(NULL),
            "tq_called_flag"  => 0,
            "first_revisit_time"  => 0,
            "hold_flag" =>  1,
            "seller_resource_type" => E\Eseller_resource_type::V_2
        ]);

        $this->t_test_lesson_subject-> set_other_admin_init($userid,$adminid );

        $phone= $this->t_seller_student_new->get_phone($userid);
        $account = $this->get_account();
        $this->t_book_revisit->add_book_revisit(
            $phone,
            "操作者: $account 抢单 未签例子: $phone  " ,
            "system"
        );


        //$json_ret["opt_count"] += 1;
        //\App\Helper\Common::redis_set_json("SELLER_TEST_LESSON_USER_$adminid", $json_ret);
        return $this->output_succ();
    }

    public function seller_student_add_subject () {
        $userid= $this->get_in_userid();
        $subject= $this->get_in_subject();
        //booken
        $grade=$this->t_student_info->get_grade($userid);

        if($this->t_test_lesson_subject->check_subject($userid,$subject)) {
            return $this->output_err("已经有[".E\Esubject::get_desc($subject)."]的扩课了" );
        }

        $adminid = $this->get_account_id();
        $account_role = $this->t_manager_info->get_account_role($adminid);
        if($account_role==1){
            $ass_test_lesson_type =1;
        }else{
            $ass_test_lesson_type =0;
        }
        $this->t_test_lesson_subject->row_insert([
            "userid"  => $userid,
            "require_adminid"  => $this->get_account_id(),
            "grade"   => $grade,
            "subject" => $subject,
            "require_admin_type" => E\Eaccount_role::V_2,
            "ass_test_lesson_type"=>$ass_test_lesson_type
        ]);
        return $this->output_succ();

    }
    public function seller_noti_info()  {

        $adminid=$this->get_account_id();
        $next_revisit_count = $this->t_seller_student_new->get_today_next_revisit_count($adminid);
        $today_free_count= $this->t_seller_student_new-> get_today_next_revisit_need_free_count($adminid);

        $require_info     = $this->t_test_lesson_subject->get_require_and_return_back_count($adminid);
        $notify_lesson_info = $this->t_test_lesson_subject_require->get_notify_lesson_info($adminid);
        $row_item=$this->t_seller_student_new-> get_lesson_status_count($adminid );
        $no_confirm_count = $this->t_test_lesson_subject_require->get_no_confirm_count($adminid);
        $end_class_stu_num = $this->t_seller_student_new->get_end_class_stu_num($adminid);
        $favorite_count = $this->t_seller_student_new->get_favorite_num($adminid);
        $today_new_count= $this->t_seller_student_new_b2->get_today_new_count($adminid);


        return $this->output_succ(
            array_merge($row_item, $require_info,$notify_lesson_info , [
                "next_revisit_count"=> $next_revisit_count,
                "no_confirm_count"=> $no_confirm_count,
                "end_class_stu_num"=>$end_class_stu_num,
                "today_free_count"  => $today_free_count,
                "favorite_count"  => $favorite_count,
                "today_new_count" =>$today_new_count,
            ] )
        );

    }
    public function tmk_save_user_info(){
        $adminid                   = $this->get_account_id();
        $userid                    = $this->get_in_userid();
        $test_lesson_subject_id    = $this->get_in_test_lesson_subject_id();
        $nick                      = $this->get_in_str_val("nick");
        $grade                     = $this->get_in_grade();
        $subject                   = $this->get_in_subject();
        $tmk_student_status        = $this->get_in_int_val("tmk_student_status");
        $tmk_student_status_old    = $this->get_in_int_val("tmk_student_status_old");
        $tmk_next_revisit_time_str = $this->get_in_str_val("tmk_next_revisit_time");
        $tmk_next_revisit_time     = strtotime($tmk_next_revisit_time_str);

        $tmk_desc = $this->get_in_str_val("tmk_desc");

        $item=$this->t_seller_student_new->field_get_list($userid,"tmk_next_revisit_time,tmk_student_status,phone ");
        $phone=$item["phone"];
        if ($item["tmk_student_status"] !=  $tmk_student_status || $item["tmk_next_revisit_time"] !=  $tmk_next_revisit_time) {
            $account=$this->get_account();
             $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: $account TMK状态: ".E\Etmk_student_status::get_desc($tmk_student_status) . "  下次回访时间:" .\App\Helper\Utils::unixtime2date($tmk_next_revisit_time) ,
                "system"
            );

            if ($tmk_student_status==E\Etmk_student_status::V_3 ) { //
                $this->t_test_lesson_subject->set_seller_student_status($test_lesson_subject_id,0, $this->get_account());
                $this->t_seller_student_new->field_update_list($userid,[
                    "seller_resource_type"=>E\Eseller_resource_type::V_0,
                ]);
                // $this->t_manager_info->send_wx_todo_msg( "李子璇","来自:$account" , "TMK 有效:$phone"  );
            }
        }
        $this->t_student_info->field_update_list($userid,[
            "nick"=>$nick,
            "grade"=>$grade,
        ]);
        if($tmk_student_status != $tmk_student_status_old && $tmk_student_status == E\Etmk_student_status::V_3){//tmk更改例子为有效
            //tmk分配给自己
            $this->t_seller_student_new->set_admin_info_new(
                $opt_type=2,$userid,$this->get_account_id(),$this->get_account_id(),$this->get_account(),$this->get_account(),$assign_time=time(null));
            $ret = $this->t_seller_student_new->field_update_list($userid,[
                "tmk_student_status"=>$tmk_student_status,
                "tmk_next_revisit_time"=>$tmk_next_revisit_time,
                "tmk_desc"=>$tmk_desc,
                "first_tmk_set_valid_admind"=>$adminid,
                "first_tmk_set_valid_time"=>time(null),
                "cc_no_called_count"=>0,
            ]);
        }elseif($tmk_student_status != $tmk_student_status_old && $tmk_student_status == E\Etmk_student_status::V_2){//tmk无效
            $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                "seller_student_status"=>E\Eseller_student_status::V_50,
            ]);
        }else{
            $this->t_seller_student_new->field_update_list($userid,[
                "tmk_student_status"=>$tmk_student_status,
                "tmk_next_revisit_time"=>$tmk_next_revisit_time,
                "tmk_desc"=>$tmk_desc,
            ]);
        }

        $admin_revisiterid=$this->t_seller_student_new->get_admin_revisiterid($userid);
        if (!$admin_revisiterid ) {
            $this->t_seller_student_new->field_update_list($userid,[
                "user_desc"   => "TMK说明:" .$tmk_desc . ". 下次回访时间:" . $tmk_next_revisit_time_str,
            ]);
        }

        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "subject"  => $subject,
        ]);

        return $this->output_succ();
    }

    public function tmk_save_user_info_new(){
        $adminid                   = $this->get_account_id();
        $userid                    = $this->get_in_userid();
        $test_lesson_subject_id    = $this->get_in_test_lesson_subject_id();
        $nick                      = $this->get_in_str_val("nick");
        $grade                     = $this->get_in_grade();
        $subject                   = $this->get_in_subject();
        $tmk_student_status        = $this->get_in_int_val("tmk_student_status");
        $tmk_student_status_old    = $this->get_in_int_val("tmk_student_status_old");
        $tmk_next_revisit_time_str = $this->get_in_str_val("tmk_next_revisit_time");
        $tmk_next_revisit_time     = strtotime($tmk_next_revisit_time_str);

        $tmk_desc = $this->get_in_str_val("tmk_desc");

        $item=$this->t_seller_student_new->field_get_list($userid,"tmk_next_revisit_time,tmk_student_status,phone ");
        $phone=$item["phone"];

        if($tmk_student_status==E\Etmk_student_status::V_3) { //拨通标记有效限制
            $ret = $this->t_tq_call_info->get_call_info_list($this->get_account_id(),$phone);
            if(!$ret){
                return $this->output_err('拨通后才可标记有效!');
            }
        }
        if ($item["tmk_student_status"] !=  $tmk_student_status || $item["tmk_next_revisit_time"] !=  $tmk_next_revisit_time) {
            $account=$this->get_account();
             $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: $account TMK状态: ".E\Etmk_student_status::get_desc($tmk_student_status) . "  下次回访时间:" .\App\Helper\Utils::unixtime2date($tmk_next_revisit_time) ,
                "system"
            );

            if ($tmk_student_status==E\Etmk_student_status::V_3 ) { //
                $this->t_test_lesson_subject->set_seller_student_status($test_lesson_subject_id,0, $this->get_account());
                $this->t_seller_student_new->field_update_list($userid,[
                    "seller_resource_type"=>E\Eseller_resource_type::V_0,
                ]);

                // $this->t_manager_info->send_wx_todo_msg( "李子璇","来自:$account" , "TMK 有效:$phone"  );

            }
        }
        $this->t_student_info->field_update_list($userid,[
            "nick"=>$nick,
            "grade"=>$grade,
        ]);
        if($tmk_student_status != $tmk_student_status_old && $tmk_student_status == E\Etmk_student_status::V_3){//tmk更改例子为有效
            $this->t_seller_student_new->set_admin_info_new(
            $opt_type=2,$userid,$this->get_account_id(),$this->get_account_id(),$this->get_account(),$this->get_account(),time(null));
            //分配日志
            $this->t_seller_edit_log->row_insert([
                'adminid'=>$this->get_account_id(),//分配人
                'uid'=>$this->get_account_id(),//组员
                'new'=>$userid,//例子
                'type'=>E\Eseller_edit_log_type::V_3,
                'create_time'=>time(NULL),
            ]);
            $this->t_seller_student_new->field_update_list($userid,[
                "tmk_student_status"=>$tmk_student_status,
                "tmk_next_revisit_time"=>$tmk_next_revisit_time,
                "tmk_desc"=>$tmk_desc,
                "first_tmk_set_valid_admind"=>$adminid,
                "first_tmk_set_valid_time"=>time(null),
                "cc_no_called_count"=>0,
            ]);
        }elseif($tmk_student_status != $tmk_student_status_old && $tmk_student_status == E\Etmk_student_status::V_2){//tmk无效
            $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                "seller_student_status"=>E\Eseller_student_status::V_50,
            ]);
        }else{
            $this->t_seller_student_new->field_update_list($userid,[
                "tmk_student_status"=>$tmk_student_status,
                "tmk_next_revisit_time"=>$tmk_next_revisit_time,
                "tmk_desc"=>$tmk_desc,
            ]);
        }

        $admin_revisiterid=$this->t_seller_student_new->get_admin_revisiterid($userid);
        if (!$admin_revisiterid ) {
            $this->t_seller_student_new->field_update_list($userid,[
                "user_desc"   => "TMK说明:" .$tmk_desc . ". 下次回访时间:" . $tmk_next_revisit_time_str,
            ]);
        }

        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "subject"  => $subject,
        ]);

        return $this->output_succ();
    }

    public function get_relation_order_list_js() {
        $orderid       = $this->get_in_int_val("orderid");
        $contract_type = $this->get_in_int_val("contract_type");
        $old_orderid   = $orderid;
        if ($contract_type ==1 ) {
            $orderid=$this->t_order_info->get_parent_order_id($orderid);
            if(!$orderid) {
                return $this->output_err("没有数据");
            }
        }

        $ret_list=$this->t_order_info->get_relation_order_list($orderid);
        foreach ($ret_list["list"] as  &$item) {
            E\Econtract_type::set_item_value_str($item);
            $item["self_flag_str"]= ($item["orderid"]==$old_orderid?"当前":"");
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time");
            $this->cache_set_item_student_nick($item );
            $item["price"]/=100;
            if ($item["contract_type"]==1) {
                E\Efrom_parent_order_type::set_item_value_str($item);
            }

        }

        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ(["data"=> $ret_list]);

    }
    public function test_lesson_time_change(){
        $require_id = $this->get_in_require_id();
        $seller_require_change_type  = $this->get_in_int_val("seller_require_change_type");
        $require_lesson_time=$this->get_in_str_val("require_change_lesson_time");
        $old_lesson_start=$this->get_in_str_val("old_lesson_start");
        $require_change_lesson_time  = strtotime($require_lesson_time);
        $seller_require_change_time  = time();
        if( $require_change_lesson_time <= time()){
            return $this->output_err("课程时间不能晚于当前时间!");
        }
        $userid = $this->get_in_int_val("userid");
        $nick  = $this->get_in_str_val("nick");
        $teacherid =  $this->get_in_int_val("teacherid");
        $now = date("Y-m-d");
        $lessonid = $this->t_test_lesson_subject_require->get_current_lessonid($require_id);
        $lesson_start = $require_change_lesson_time;
        $lesson_end = $lesson_start + 2400;

        $ret = $this->check_lesson_clash($teacherid,$userid,$lessonid,$lesson_start,$lesson_end);
        if($ret) return $ret;
        $set_lesson_adminid = $this->t_test_lesson_subject_sub_list->get_set_lesson_adminid_by_require_id($require_id);
        $this->t_test_lesson_subject_require->field_update_list($require_id,["seller_require_change_type"=>$seller_require_change_type,"require_change_lesson_time"=>$require_change_lesson_time,"seller_require_change_time"=>$seller_require_change_time,"seller_require_change_flag"=>1]);
        $this->t_manager_info->send_wx_todo_msg_by_adminid ($set_lesson_adminid,$this->get_account(),"申请更改试听课时间","学生:".$nick."的试听课时间需由".$old_lesson_start."改至".$require_lesson_time,"seller_student_new2/test_lesson_plan_list?date_type=5&opt_date_type=1&start_time=".$now."&end_time=".$now."&grade=-1&subject=-1&test_lesson_student_status=-1&lessonid=undefined&userid=".$userid."&teacherid=-1&success_flag=-1&require_admin_type=-1&require_adminid=".$this->get_account_id()."&tmk_adminid=-1&is_test_user=0&test_lesson_fail_flag=-1&accept_flag=-1&seller_groupid_ex=&seller_require_change_flag=1&ass_test_lesson_type=-1");

        return $this->output_succ();
    }

    public function done_seller_require_change(){
        $require_id =  $this->get_in_int_val("require_id");
        $lesson_start = strtotime($this->get_in_str_val("lesson_start"));
        $lesson_end = $lesson_start + 2400;

        $teacherid =  $this->get_in_int_val("teacherid");
        //$userid = $this->get_in_int_val("userid");
        $old_lessonid = $this->get_in_int_val("lessonid");
        $orderid = 1;
        if ($lesson_start<=0 ) {
            return $this->output_err("请填写完整!");
        }
        if($lesson_start < time()){
            return $this->output_err("课程开始时间过早!");
        }
        $test_lesson_subject_id=$this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $tt_item = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject, grade, userid ");
        $userid  = $tt_item["userid"];
        $subject = $tt_item["subject"];
        $grade   = $tt_item["grade"];

        $ret_row1 = $this->t_lesson_info->check_student_time_free(
            $userid,0,$lesson_start,$lesson_end);

        //检查时间是否冲突
        if($ret_row1) {
            $error_lessonid=$ret_row1["lessonid"];
            return $this->output_err(
                "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $ret_row2=$this->t_lesson_info->check_teacher_time_free(
            $teacherid,0,$lesson_start,$lesson_end);

        if($ret_row2) {
            $error_lessonid=$ret_row2["lessonid"];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");

        $courseid = $this->t_course_order->add_course_info_new($orderid,$userid,$grade,$subject,100,2,0,1,1,0,$teacherid);
        $lessonid = $this->t_lesson_info->add_lesson(
            $courseid,0,
            $userid,
            0,
            2,
            $teacherid,
            0,
            $lesson_start,
            $lesson_end,
            $grade,
            $subject,
            100,
            $teacher_info["teacher_money_type"],
            $teacher_info["level"]
        );
        $this->t_homework_info->add(
            $courseid,
            0,
            $userid,
            $lessonid,
            $grade,
            $subject,
            $teacherid
        );
        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "grade"  => $grade,
        ]);
        $this->t_test_lesson_subject_sub_list->row_insert([
            "lessonid"  => $lessonid,
            "require_id" => $require_id,
            "set_lesson_adminid"  => $this->get_account_id(),
            "set_lesson_time"  => time(NULL) ,
        ]);

        $this->t_test_lesson_subject_require->field_update_list($require_id , [
            'current_lessonid'=>$lessonid,
            'accept_flag'=>E\Eset_boolean::V_1 ,
            'accept_time'=>time(NULL),
            'seller_require_change_flag'=>2
        ]);
        $this->t_test_lesson_subject_require->set_test_lesson_status(
            $require_id, E\Eseller_student_status::V_210 , $this->get_account() );

        $this->t_lesson_info->reset_lesson_list($courseid);
        $this->t_seller_student_new->field_update_list($userid,[
            "global_tq_called_flag"  => 2,
            "tq_called_flag"  => 2,
        ]);
        $require_info = $this->t_test_lesson_subject_require->field_get_list($require_id,"test_lesson_subject_id,accept_adminid");
        $this->t_test_lesson_subject->field_update_list( $require_info["test_lesson_subject_id"],["history_accept_adminid"=>$require_info["accept_adminid"]]);
        if (\App\Helper\Utils::check_env_is_release()) {
            $require_adminid = $this->t_test_lesson_subject->get_require_adminid($test_lesson_subject_id);
            $userid          = $this->t_test_lesson_subject->get_userid($test_lesson_subject_id);
            $phone           = $this->t_seller_student_new->get_phone($userid);
            $nick            = $this->t_student_info ->get_nick($userid);

            $teacher_nick = $this->cache_get_teacher_nick($teacherid);

            $lesson_time_str    = \App\Helper\Utils::fmt_lesson_time($lesson_start,$lesson_end);
            $require_admin_nick = $this->cache_get_account_nick($require_adminid);
            $this->t_manager_info->send_wx_todo_msg(
                $require_admin_nick,"来自:".$this->get_account()
                ,"排课[$phone][$nick] 老师[$teacher_nick] 上课时间[$lesson_time_str]","","");
        }

        $this->t_test_lesson_subject_sub_list->field_update_list($old_lessonid,["success_flag"=>2,"fail_greater_4_hour_flag"=>0,"test_lesson_fail_flag"=>103]);
        $this->t_lesson_info->field_update_list($old_lessonid,["lesson_del_flag"=>1]);

        return $this->output_succ();
    }
    public function refuce_seller_require_change(){
        $require_id =  $this->get_in_int_val("require_id");
        $this->t_test_lesson_subject_require->field_update_list($require_id,["seller_require_change_flag"=>3]);

        return $this->output_succ();
    }
    public function set_hold_list() {
        $hold_flag=$this->get_in_int_val("hold_flag");
        $userid_list_str= $this->get_in_str_val("userid_list");
        $userid_list=\App\Helper\Utils::json_decode_as_int_array($userid_list_str);
        if ( count($userid_list) ==0 ) {
            return $this->output_err("还没选择例子");
        }
        $this->t_seller_student_new->  set_hold_admin_list (
            $hold_flag, $userid_list,   $this->get_account_id() );

        //$account=$this->get_account();
        //$opt_account=$this->t_manager_info->get_account($opt_adminid);

        /*
        foreach ( $userid_list as $userid ) {
            $phone=$this->t_seller_student_new->get_phone($userid);



        }
        */

        return $this->output_succ();
    }
    public function set_history_to_new() {
        $userid_list_str= $this->get_in_str_val("userid_list");
        $seller_resource_type = $this->get_in_e_seller_resource_type();
        $userid_list=\App\Helper\Utils::json_decode_as_int_array($userid_list_str);
        if ( count($userid_list) ==0 ) {
            return $this->output_err("还没选择例子");
        }
        $account= $this->get_account();
        foreach($userid_list as $userid) {
            if (  $seller_resource_type==0 ) {
                $this->t_seller_student_new->field_update_list($userid,[
                    "seller_resource_type" => $seller_resource_type,
                    "first_revisit_time"   => 0,
                    "last_revisit_msg"     => "",
                    "last_revisit_time"    => 0,
                    "next_revisit_time"    => 0,
                    "user_desc"            => "",
                    "global_tq_called_flag"            => 0,
                    "tq_called_flag"            => 0,
                    "add_time"             => time(NULL),
                    "seller_add_time"      => time(NULL),
                ]);
                $this->t_test_lesson_subject->clean_seller_info($userid );
                $phone= $this->t_seller_student_new->get_phone($userid);
                $ret_update = $this->t_book_revisit->add_book_revisit(
                    $phone,
                    "操作者:$account 状态: HISTORY_2_NEW 公海 -> 新例子 ",
                    "system"
                );
            }else{
                $this->t_seller_student_new->field_update_list($userid,[
                    "seller_resource_type" =>  1,
                ]);
                $this->t_test_lesson_subject->clean_seller_info($userid );
                $phone= $this->t_seller_student_new->get_phone($userid);
                $ret_update = $this->t_book_revisit->add_book_revisit(
                    $phone,
                    "操作者:$account 状态: HISTORY_2_NEW 新例子 ->  公海  ",
                    "system"
                );
            }
        }

        // 添加操作日志
        $this->t_user_log->add_data("公海->新");
        return $this->output_succ();
    }

    public function set_all_hold() {
        $admin_revisiterid=$this->get_account_id();
        $this->t_seller_student_new->set_all_hold($admin_revisiterid );
        return $this->output_succ();
    }


    public function set_no_hold_free() {
        $admin_revisiterid=$this->get_account_id();
        $account=$this->get_account();

        $hold_count=$this->t_seller_student_new->get_hold_count($admin_revisiterid);
        if ($account<>"jim") {
            if ($hold_count<=50) {
                $this->output_succ("保留太少,不能操作");
            }
        }
        $user_list=$this->t_seller_student_new->get_no_hold_list($admin_revisiterid);
        foreach($user_list as $item) {
            $phone = $item["phone"];
            $last_contact_time = $item['last_contact_time'];
            //公海领取的例子,回流拨打限制
            if($item["hand_get_adminid"] == E\Ehand_get_adminid::V_5 && !in_array($item['admin_revisiterid'],[831,973,60,898])){
                $ret = $this->t_tq_call_info->get_call_info_row_new($item["admin_revisiterid"],$phone,$item["admin_assign_time"]);
                if(!$ret){
                    return $this->output_err($phone.'为公海领取的例子,请拨打后回流!');
                    break;
                }
                // if($last_contact_time<$item["admin_assign_time"]){
                //     return $this->output_err($phone.'为公海领取的例子,请拨打后回流!');
                //     break;
                // }
            }
            $seller_student_status= $item["seller_student_status"];
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者:$account 状态: 回到公海 ",
                "system"
            );
            $test_subject_free_type=0;
            if ($seller_student_status==1) {
                $test_subject_free_type=3;
            }

            $this->t_test_subject_free_list ->row_insert([
                "add_time" => time(NULL),
                "userid" =>   $item["userid"],
                "adminid" => $admin_revisiterid,
                "test_subject_free_type" => $test_subject_free_type,
            ],false,true);
            $hand_get_adminid = 0;
            $orderid = $this->t_order_info->get_orderid_by_userid($item["userid"],$account);
            if($orderid>0){
                $hand_get_adminid = $item["hand_get_adminid"];
            }
            $this->t_seller_student_new->field_update_list($item["userid"],[
                "free_adminid" => $this->get_account_id(),
                "free_time" => time(),
                "hand_free_count" => $item['hand_free_count']+1,
                "hand_get_adminid" => $hand_get_adminid,
            ]);
        }
        $this->t_seller_student_new->set_no_hold_free($admin_revisiterid );
        return $this->output_succ();
    }

    public function get_course_list_js()  {
        $page_num = $this->get_in_page_num();
        $userid   = $this->get_in_userid();
        $teacherid = $this->get_in_teacherid();
        $ret_list = $this->t_course_order-> get_all_list($page_num,$userid, $teacherid,1 );
        foreach ($ret_list["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            E\Esubject::set_item_value_str($item);
        }


        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ(["data"=> $ret_list]);
    }

    public function course_order_set_test_lessonid() {
        $courseid=$this->get_in_courseid();
        $lessonid=$this->get_in_lessonid();
        $ass_test_lesson_type = $this->get_in_int_val("ass_test_lesson_type",0);
        $regular_flag = $this->get_in_int_val("regular_flag",0);

        if ($courseid <=0  ) {
            return $this->output_err("请选择课程包!");
        }

        $this->t_course_order-> clean_ass_from_test_lesson_id($lessonid);
        $this->t_course_order->field_update_list($courseid,[
            "ass_from_test_lesson_id"=>$lessonid,
        ]);
        $this->t_test_lesson_subject_sub_list->field_update_list($lessonid,[
           "order_confirm_flag" =>1
        ]);

        if($ass_test_lesson_type==2 && $regular_flag==1){
            $test_info = $this->t_lesson_info->field_get_list($lessonid,"lesson_start,subject,userid,teacherid");
            $old_teacher_arr = $this->t_lesson_info_b2->get_old_teacher_nick($test_info['lesson_start'],$test_info['subject'],$test_info['userid']);
            if($test_info["teacherid"] != $old_teacher_arr["teacherid"]){
                $this->delete_teacher_regular_lesson($test_info['userid'],0,1,$old_teacher_arr["teacherid"]);
            }

        }
        return $this->output_succ();
    }

    public function seller_new_count_add() {
        $opt_adminid=$this->get_in_int_val("opt_adminid");
        $count=$this->get_in_int_val("count");
        list($start_time,$end_time) = $this->get_in_date_range_day(0);
        if ( !$this->check_power( E\Epower::V_SELLER_ADD_NEW_STUDENT )  ) {
            return $this->output_err("没有权限");
        }

        $this->t_seller_new_count->add($start_time,$end_time-1,E\Eseller_new_count_type::V_ADMIN,$count,$opt_adminid,$this->get_account_id());

        return $this->output_succ();

    }

    public function requrie_test_lesson_without_test_paper()  {
        $test_lesson_subject_id=$this->get_in_test_lesson_subject_id();
        $reason = $this->get_in_str_val("reason");
        $this->t_flow->add_flow(
            E\Eflow_type::V_SELLER_POST_TEST_LESSON_WITHOUT_PAPER,
            $this->get_account_id(),$reason,$test_lesson_subject_id );
        return $this->output_succ();
    }


    public function jw_teacher_work_status_change(){
        $adminid=$this->get_account_id();
        $role = $this->t_manager_info->get_account_role($adminid);
        /*if($role != 3){
            return $this->output_err("非教务人员不能进行此操作!");
            }*/
        $admin_work_status = $this->t_manager_info->get_admin_work_status($adminid);
        if($admin_work_status == 0){
            $this->t_manager_info->field_update_list($adminid,["admin_work_status"=>1]);
        }else{
            $this->t_manager_info->field_update_list($adminid,["admin_work_status"=>0]);
        }
        return $this->output_succ();
    }

    public function jw_test_lesson_status_change(){
        $require_id = $this->get_in_int_val("require_id");
        $jw_test_lesson_status = $this->get_in_int_val("jw_test_lesson_status");
        if($jw_test_lesson_status ==0){
            $this->t_test_lesson_subject_require->field_update_list($require_id,["jw_test_lesson_status"=>2]);
        }else{
            $this->t_test_lesson_subject_require->field_update_list($require_id,["jw_test_lesson_status"=>0]);
        }
        return $this->output_succ();
    }

    public function get_lecture_appointment_origin_list(){
        $ret_lecture = E\Electure_appointment_origin::$desc_map;
        $ret_info = [];
        $origin   = [];

        foreach($ret_lecture as $key=>$val){
            if($key!=0){
                $origin["id"]   = $key;
                $origin["name"] = $val;
                $ret_info[]     = $origin;
            }
        }

        $ret_info              = \App\Helper\Utils::list_to_page_info($ret_info);
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );

        return outputjson_success(array('data' => $ret_info));
    }

    public function add_teacher_lecture_appointment_origin(){
        $phone   = $this->get_in_str_val("phone");
        $id_list = json_decode($this->get_in_str_val("id_list"),true);

        foreach($id_list as $item){
            $this->t_teacher_lecture_appointment_info->field_update_list($item,[
                "reference" => $phone
            ]);
        }

        return $this->output_succ();
    }

    public function update_lecture_revisit_type(){
        $id = $this->get_in_int_val("id");
        $lecture_revisit_type= $this->get_in_int_val("lecture_revisit_type");
        $custom= $this->get_in_str_val("custom");
        $ret = $this->t_teacher_lecture_appointment_info->field_update_list($id,[
            "lecture_revisit_type" => $lecture_revisit_type,
            "custom"=>$custom
        ]);
        $phone = $this->t_teacher_lecture_appointment_info->get_phone($id);
        $this->t_lecture_revisit_info->row_insert([
            "phone"             => $phone,
            "revisit_time"      => time(),
            "revisit_origin"    => $lecture_revisit_type,
            "sys_operator"      => $this->get_account(),
            "revisit_note"      => $custom
        ]);

        return $this->output_succ();

    }
    public function update_lecture_appointment_status(){
        $id = $this->get_in_int_val("id");
        $lecture_appointment_status= $this->get_in_int_val("lecture_appointment_status");
        $reference = trim($this->get_in_str_val("reference"));
        $phone     = trim($this->get_in_str_val("phone"));
        $email     = trim($this->get_in_str_val("email"));
        $name      = trim($this->get_in_str_val("name"));
        $qq        = trim($this->get_in_str_val("qq"));
        $account_role = $this->get_account_role();

        $old_phone = $this->t_teacher_lecture_appointment_info->get_phone($id);
        if($old_phone != $phone){
            $check_flag = $this->t_teacher_lecture_appointment_info->check_is_exist(0,$phone);
            if($check_flag){
                return $this->output_err("新手机号已存在！无法更改为此手机号！");
            }
            $check_flag = $this->t_teacher_lecture_info->check_is_exists($old_phone);
            if($check_flag && !in_array($account_role,[12])){
                return $this->output_err("旧手机已经提交过试讲，无法更改手机号！");
            }
        }

        $ret = $this->t_teacher_lecture_appointment_info->field_update_list($id,[
            "lecture_appointment_status" => $lecture_appointment_status,
            "reference"                  => $reference,
            "phone"                      => $phone,
            "email"                      => $email,
            "name"                       => $name,
            "qq"                         => $qq,
        ]);
        if(!$ret){
            return $this->output_err("更新失败！请重试!");
        }

        return $this->output_succ();
    }

    public function update_lecture_appointment_status_list(){
        $id_list_str= $this->get_in_str_val("id_list");
        $id_list=\App\Helper\Utils::json_decode_as_int_array($id_list_str);

        $lecture_appointment_status= $this->get_in_int_val("lecture_appointment_status");
        foreach( $id_list as $id ){
            $this->t_teacher_lecture_appointment_info->field_update_list($id,["lecture_revisit_type"=>$lecture_appointment_status]);
        }

        // 添加操作日志
        $this->t_user_log->add_data("批量修改状态");
        return $this->output_succ();
    }

    public function del_teacher_lecture_appointment_list(){
        $id_list = json_decode($this->get_in_str_val("id_list"),true);
        foreach($id_list as $item){
            $this->t_teacher_lecture_appointment_info->row_delete($item);
        }
        return $this->output_succ();

    }
    public function delete_lecture_appointment(){
        $id = $this->get_in_int_val("id");
        $acc = $this->get_account();
        if(!in_array($acc,["adrian","jack","jim"])){
            return $this->output_err("你没有权限");
        }

        $this->t_teacher_lecture_appointment_info->row_delete($id);
        return $this->output_succ();
    }

    /**
     * 后台手动添加老师报名数据
     */
    public function add_lecture_appointment_one(){
        $answer_begin_time    = strtotime($this->get_in_str_val("answer_begin_time"));
        $name                 = $this->get_in_str_val("name");
        $phone                = $this->get_in_str_val("phone");
        $email                = $this->get_in_str_val("email");
        $qq                   = $this->get_in_str_val("qq");
        $grade_ex             = $this->get_in_int_val("grade_ex");
        $subject_ex           = $this->get_in_int_val("subject_ex");
        $school               = $this->get_in_str_val("school");
        $teacher_type         = $this->get_in_int_val("teacher_type");
        $lecture_revisit_type = $this->get_in_int_val("lecture_revisit_type");
        $reference            = $this->get_in_str_val("reference");
        $account_role         = $this->get_account_role();
        $acc                  = $this->get_account();

        if(empty($answer_begin_time) || empty($phone) || empty($name) || empty($teacher_type)){
             return $this->output_err("答题时间/手机号/名字不能为空");
        }

        $id = $this->t_teacher_lecture_appointment_info->get_appointment_id_by_phone($phone);
        if($id>0){
             return $this->output_err("该手机号已存在");
        }

        $check_phone = \App\Helper\Utils::check_phone($phone);
        if(!$check_phone){
            return $this->output_err("手机错误!");
        }

        if(\App\Helper\Utils::check_env_is_release() && $account_role!=E\Eaccount_role::V_12){
            $check_email = \App\Helper\Utils::check_email($email);
            if(!$check_email){
                return $this->output_err("邮箱错误!");
            }
        }

        $ret = $this->t_teacher_lecture_appointment_info->row_insert([
            "answer_begin_time"    => $answer_begin_time,
            "name"                 => $name,
            "phone"                => $phone,
            "email"                => $email,
            "qq"                   => $qq,
            "subject_ex"           => $subject_ex,
            "grade_ex"             => $grade_ex,
            "school"               => $school,
            "teacher_type"         => $teacher_type,
            "reference"            => $reference,
            "accept_adminid"       => $this->get_account_id(),
            "accept_time"          => time(),
            "lecture_revisit_type" => $lecture_revisit_type,
            "hand_flag"            => 1
        ]);
        $teacher_info['tea_nick'] = $name;
        $teacher_info['phone']    = $phone;
        $teacher_info['identity'] = $teacher_type;
        $teacher_info['acc']      = $acc;
        $teacher_info['email']    = $email;
        $teacher_info['school']   = $school;
        $teacherid = $this->add_teacher_common($teacher_info);

        // 添加操作日志
        $this->t_user_log->add_data("新增预讲试约");
        return $this->output_succ();
    }

    /**
     * 更新老师招师库信息
     */
    public function update_lecture_appointment_info(){
        $id                   = $this->get_in_str_val("id");
        $name                 = $this->get_in_str_val("name");
        $phone                = $this->get_in_str_val("phone");
        $email                = $this->get_in_str_val("email");
        $qq                   = $this->get_in_str_val("qq");
        $reference            = $this->get_in_str_val("reference");
        $age                  = $this->get_in_int_val("age");
        $gender               = $this->get_in_int_val("gender");
        $grade_ex             = $this->get_in_int_val("grade_ex");
        $subject_ex           = $this->get_in_int_val("subject_ex");
        $teacher_type         = $this->get_in_int_val("teacher_type");
        $lecture_revisit_type = $this->get_in_int_val("lecture_revisit_type");
        $custom               = $this->get_in_int_val("custom");
        $acc                  = $this->get_account();

        if(empty($name) || empty($phone) || empty($grade_ex) || empty($subject_ex) || empty($teacher_type)
           || empty($email) || empty($qq) || empty($age) || empty($gender)
        ){
            return $this->output_err("红色部分不能为空");
        }
        $check_phone = \App\Helper\Utils::check_phone($phone);
        if(!$check_phone){
            return $this->output_err("请输入正确的手机号!");
        }
        if($reference!=""){
            $check_reference = \App\Helper\Utils::check_phone($reference);
            if(!$check_reference){
                return $this->output_err("请输入正确的推荐人手机号!");
            }
        }
        $check_email = \App\Helper\Utils::check_email($email);
        if(!$check_email){
            return $this->output_err("请输入正确的邮箱!");
        }
        if($age>100){
            return $this->output_err("请输入合理的老师年龄！");
        }
        $old_phone = $this->t_teacher_lecture_appointment_info->get_phone($id);
        if($old_phone != $phone){
            $check_phone = $this->t_teacher_lecture_appointment_info->get_appointment_id_by_phone($phone);
            if($check_phone>0){
                return $this->output_err("该手机号已存在");
            }
            $update_phone_flag = true;
        }else{
            $update_phone_flag = false;
        }
        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($old_phone);
        if(!$teacherid){
            $teacher_info['phone'] = $phone;
            $ret = $this->add_teacher_common($teacher_info);
            if($ret<=0){
                return $this->output_err($ret);
            }else{
                $teacherid = $ret;
            }
        }
        if($update_phone_flag){
            $ret = $this->change_teacher_phone($teacherid,$phone);
            if($ret!=true){
                return $ret;
            }
        }
        if($teacherid>0){
            $this->t_teacher_info->field_update_list($teacherid, [
                'realname' => $name,
                'nick'     => $name,
                'identity' => $teacher_type,
                'age'      => $age,
                'gender'   => $gender,
                'email'    => $email,
                'qq_info'  => $qq,
            ]);
        }
        $this->t_teacher_lecture_appointment_info->field_update_list($id,[
            "name"                 => $name,
            "phone"                => $phone,
            "email"                => $email,
            "qq"                   => $qq,
            "teacher_type"         => $teacher_type,
            "reference"            => $reference,
            "grade_ex"             => $grade_ex,
            "subject_ex"           => $subject_ex,
            "reference"            => $reference,
            "lecture_revisit_type" => $lecture_revisit_type,
            "custom"               => $custom,
        ]);

        return $this->output_succ();
    }

    public function set_green_channel_teacherid(){
        $require_id = $this->get_in_int_val("require_id");
        $green_channel_teacherid = $this->get_in_int_val("green_channel_teacherid");
        $this->t_test_lesson_subject_require->field_update_list($require_id,[
            "green_channel_teacherid"=>$green_channel_teacherid,
            "is_green_flag"          =>1,
            "seller_top_flag"        =>0
        ]);
        return $this->output_succ();
    }

    public function update_freeze_teacher_info(){
        $teacherid      = $this->get_in_int_val("teacherid");
        $is_freeze      = $this->get_in_int_val("is_freeze");
        $seller_require_flag      = $this->get_in_int_val("seller_require_flag",0);
        $grade_range     = $this->get_in_int_val("grade_range",0);
        $freeze_reason  = trim($this->get_in_str_val("freeze_reason"));
        $freeze_adminid = $this->get_account_id();
        $tea_nick       = $this->t_teacher_info->get_realname($teacherid);
        $not_grade      = $this->t_teacher_info->get_not_grade($teacherid);
        $account = $this->get_account();
        if($is_freeze==0){
            $freeze_time    = time();
            if($grade_range>0){
                $not_grade=$this->get_not_grade_new($grade_range,$not_grade);
            }
            $this->t_teacher_info->field_update_list($teacherid,[
                "is_freeze"       => 1
                ,"freeze_time"    => $freeze_time
                ,"freeze_adminid" => $freeze_adminid
                ,"freeze_reason"  => $freeze_reason
                ,"not_grade"      => $not_grade
            ]);

            $this->t_teacher_record_list->row_insert([
                "teacherid"          =>$teacherid,
                "type"               =>4,
                "record_info"        =>$freeze_reason,
                "add_time"           =>time(),
                "acc"                =>$account,
                "is_freeze"          => 1,
                "seller_require_flag"=>$seller_require_flag,
                "is_freeze_old"      =>0,
                "grade_range"        =>$grade_range
            ]);

            /**
             * 模板ID : n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc
             * 标题   :课程冻结通知
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 操作时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
             $template_id      = "n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc";//old
            //$template_id      = "5LlhjLrrbKZV0fOaKk-cSngCnlXWeYLBp_DjSu6JbKw";
            $data['first']    = "老师您好，近期我们进行教学质量抽查，你的课程被冻结。您的课程反馈情况是：".$freeze_reason;
            $data['keyword1'] = "试听课";
            $data['keyword2'] = date("Y-m-d H:i",time());
            $data['remark']   = "冻结期间无法继续安排试听课，参加相关培训达标后，我们会第一时间进行解冻操作，"
                              ."如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提高教学服务质量。";

            /**
             * 模板名称 : 老师冻结通知
             * 模板ID   : SMS_46660108
             * 模板内容 : 课程冻结通知：${name}老师您好，近期我们进行教学质量抽查，您的课程反馈情况是：${reason}。
             你的课程已被冻结，冻结期间无法安排试听课。参加相关培训达标后，我们会第一时间进行解冻操作，如有疑问请联系学科教研老师。
             理优期待与你共同进步，提高教学服务质量。
             */
            $sms_id   = 46660108;
            $sms_data = [
                "name"   => $tea_nick,
                "reason" => $freeze_reason,
            ];
        }else{
            $un_freeze_time    = time();
            $account_id     = $this->get_account_id();
            $freeze_adminid = $this->t_teacher_info->get_freeze_adminid($teacherid);
            $del_flag = $this->t_manager_info->get_del_flag($freeze_adminid);
            if($account_id != $freeze_adminid && $account_id !=72 && $del_flag==0 && $account_id !=349){
                return $this->output_err("您没有权限进行该操作!");
            }
            $this->t_teacher_info->field_update_list($teacherid,[
                "is_freeze"       => 0
                ,"un_freeze_time" => $un_freeze_time
            ]);

            $this->t_teacher_record_list->row_insert([
                "teacherid"          =>$teacherid,
                "type"               =>4,
                "record_info"        =>$freeze_reason,
                "add_time"           =>time(),
                "acc"                =>$account,
                "is_freeze"          =>0,
                "seller_require_flag"=>$seller_require_flag,
                "is_freeze_old"      =>1
            ]);

            /**
             * 模板ID   : gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA
             * 标题课程 : 解冻通知
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 操作时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
             $template_id      = "gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA";//old
            //$template_id      = "5LlhjLrrbKZV0fOaKk-cSngCnlXWeYLBp_DjSu6JbKw";

            if($freeze_reason){
                $data['first']    = $freeze_reason;
            }else{
                $data['first']    = "老师您好，您的课程已经解冻。";
            }
            $data['keyword1'] = "试听课";
            $data['keyword2'] = date("Y-m-d H:i",time());
            $data['remark']   = "请继续关注理优的培训活动，理优期待与你一起共同进步，提高教学服务质量。";

            /**
             * 模板名称 : 老师解冻通知
             * 模板ID   : SMS_46720087
             * 模板内容 : 课程解冻通知：${name}老师您好，您的课程已经解冻。请继续关注理优的培训活动，
             理优期待与你共同进步，提高教学服务质量。
             */
            $sms_id   = 46720087;
            $sms_data = [
                "name" => $tea_nick,
            ];
        }

        $openid = $this->t_teacher_info->get_wx_openid($teacherid);
        if($openid){
            \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
        }else{
            $sign_name = \App\Helper\Utils::get_sms_sign_name();
            $phone     = $this->t_teacher_info->get_phone($teacherid);
            \App\Helper\Utils::sms_common($phone,$sms_id,$sms_data,0,$sign_name);
        }

        return $this->output_succ();
    }

    public function freeze_all_teacher_grade(){
        $teacherid      = $this->get_in_int_val("teacherid");
        $freeze_type     = $this->get_in_int_val("freeze_type");
        $freeze_reason  = trim($this->get_in_str_val("freeze_reason"));
        $tea_nick       = $this->t_teacher_info->get_realname($teacherid);
        $freeze_adminid = $this->get_account_id();
        $account = $this->get_account();
        $seller_require_flag      = $this->get_in_int_val("seller_require_flag",0);
        if($freeze_type==1){
            $not_grade= $this->get_teacher_all_grade($teacherid);
            $this->t_teacher_info->field_update_list($teacherid,[
                "is_freeze"       => 1
                ,"freeze_time"    => time()
                ,"freeze_adminid" => $freeze_adminid
                ,"freeze_reason"  => $freeze_reason
                ,"not_grade"      => $not_grade
            ]);

            $this->t_teacher_record_list->row_insert([
                "teacherid"          =>$teacherid,
                "type"               =>4,
                "record_info"        =>$freeze_reason,
                "add_time"           =>time(),
                "acc"                =>$account,
                "is_freeze"          => 1
            ]);

            /**
             * 模板ID : n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc
             * 标题   :课程冻结通知
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 操作时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc";//old
            $data['first']    = "您好，在近期教学质量抽查中，您所有年级的课程被冻结,冻结原因:".$freeze_reason;
            $data['keyword1'] = "试听课";
            $data['keyword2'] = date("Y-m-d H:i",time());
            $data['remark']   = "冻结期间无法继续安排试听课，参加相关培训达标后，我们会第一时间进行解冻操作，"
                              ."如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提高教学服务质量。";

            /**
             * 模板名称 : 老师冻结通知
             * 模板ID   : SMS_46660108
             * 模板内容 : 课程冻结通知：${name}老师您好，近期我们进行教学质量抽查，您的课程反馈情况是：${reason}。
             你的课程已被冻结，冻结期间无法安排试听课。参加相关培训达标后，我们会第一时间进行解冻操作，如有疑问请联系学科教研老师。
             理优期待与你共同进步，提高教学服务质量。
            */
            $sms_id   = 46660108;
            $sms_data = [
                "name"   => $tea_nick,
                "reason" => $freeze_reason,
            ];


        }elseif($freeze_type==2){
            $account_id     = $this->get_account_id();
            $freeze_adminid = $this->t_teacher_info->get_freeze_adminid($teacherid);
            $del_flag = $this->t_manager_info->get_del_flag($freeze_adminid);
            if($account_id != $freeze_adminid && $account_id !=72 && $del_flag==0 && $freeze_adminid>0 && $freeze_adminid !=72 && $account_id !=448 && $freeze_adminid !=448 && $account_id !=349 && $freeze_adminid !=349){
                return $this->output_err("您没有权限进行该操作!");
            }
            $this->t_teacher_info->field_update_list($teacherid,[
                "not_grade"    =>"",
                "un_freeze_time" => time(),
                "is_freeze"    =>0,
                "freeze_adminid"=>0
            ]);

            $this->t_teacher_record_list->row_insert([
                "teacherid"          =>$teacherid,
                "type"               =>4,
                "record_info"        =>$freeze_reason,
                "add_time"           =>time(),
                "acc"                =>$account,
                "is_freeze"          =>2,
                "seller_require_flag"=>$seller_require_flag,
            ]);

            /**
             * 模板ID   : gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA
             * 标题课程 : 解冻通知
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 操作时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA";//old
            //$template_id      = "5LlhjLrrbKZV0fOaKk-cSngCnlXWeYLBp_DjSu6JbKw";
            $data['first']    = "恭喜,您所有年级的课程已经解冻,解冻原因:".$freeze_reason;
            $data['keyword1'] = "试听课";
            $data['keyword2'] = date("Y-m-d H:i",time());
            $data['remark']   = "请继续关注理优的培训活动，理优期待与你一起共同进步，提高教学服务质量。";

            /**
             * 模板名称 : 老师解冻通知
             * 模板ID   : SMS_46720087
             * 模板内容 : 课程解冻通知：${name}老师您好，您的课程已经解冻。请继续关注理优的培训活动，
             理优期待与你共同进步，提高教学服务质量。
            */
            $sms_id   = 46720087;
            $sms_data = [
                "name" => $tea_nick,
            ];

        }

        $openid = $this->t_teacher_info->get_wx_openid($teacherid);
        if($openid){
            \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
        }else{
            $sign_name = \App\Helper\Utils::get_sms_sign_name();
            $phone     = $this->t_teacher_info->get_phone($teacherid);
            \App\Helper\Utils::sms_common($phone,$sms_id,$sms_data,0,$sign_name);
        }

        return $this->output_succ();



    }
    public function update_freeze_teacher_info_new(){
        $teacherid      = $this->get_in_int_val("teacherid");
        $is_freeze      = $this->get_in_int_val("is_freeze");
        $seller_require_flag      = $this->get_in_int_val("seller_require_flag",0);
        $grade_range     = $this->get_in_int_val("grade_range",0);
        $freeze_reason  = trim($this->get_in_str_val("freeze_reason"));
        $freeze_adminid = $this->get_account_id();
        $tea_nick       = $this->t_teacher_info->get_realname($teacherid);
        $not_grade      = $this->t_teacher_info->get_not_grade($teacherid);
        $account = $this->get_account();
        $grade_str = $this->get_detail_grade($grade_range);
        if($is_freeze==1){
            $freeze_time    = time();
            if($grade_range>0){
                $not_grade=$this->get_not_grade_new($grade_range,$not_grade,true);
            }
            $this->t_teacher_info->field_update_list($teacherid,[
                "is_freeze"       => 1
                ,"freeze_time"    => $freeze_time
                ,"freeze_adminid" => $freeze_adminid
                ,"freeze_reason"  => $freeze_reason
                ,"not_grade"      => $not_grade
            ]);

            $this->t_teacher_record_list->row_insert([
                "teacherid"          =>$teacherid,
                "type"               =>4,
                "record_info"        =>$freeze_reason,
                "add_time"           =>time(),
                "acc"                =>$account,
                "is_freeze"          => 1,
                "seller_require_flag"=>$seller_require_flag,
                "grade_range"        =>$grade_range
            ]);

            /**
             * 模板ID : n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc
             * 标题   :课程冻结通知
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 操作时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc";//old
            $data['first']    = "您好，在近期教学质量抽查中，您".$grade_str."的课程被冻结,冻结原因:".$freeze_reason;
            $data['keyword1'] = "试听课";
            $data['keyword2'] = date("Y-m-d H:i",time());
            $data['remark']   = "冻结期间无法继续安排试听课，参加相关培训达标后，我们会第一时间进行解冻操作，"
                              ."如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提高教学服务质量。";

            /**
             * 模板名称 : 老师冻结通知
             * 模板ID   : SMS_46660108
             * 模板内容 : 课程冻结通知：${name}老师您好，近期我们进行教学质量抽查，您的课程反馈情况是：${reason}。
             你的课程已被冻结，冻结期间无法安排试听课。参加相关培训达标后，我们会第一时间进行解冻操作，如有疑问请联系学科教研老师。
             理优期待与你共同进步，提高教学服务质量。
            */
            $sms_id   = 46660108;
            $sms_data = [
                "name"   => $tea_nick,
                "reason" => $freeze_reason,
            ];
        }else{
            if($grade_range>0){
                $not_grade=$this->get_not_grade_new($grade_range,$not_grade,false);
            }

            $un_freeze_time    = time();
            $account_id     = $this->get_account_id();
            $freeze_adminid = $this->t_teacher_info->get_freeze_adminid($teacherid);
            $del_flag = $this->t_manager_info->get_del_flag($freeze_adminid);
            if($account_id != $freeze_adminid && $account_id !=72 && $del_flag==0 && $freeze_adminid>0 && $freeze_adminid !=72 && $account_id !=448 && $freeze_adminid !=448 && $account_id !=349 && $freeze_adminid !=349){
                return $this->output_err("您没有权限进行该操作!");
            }
            $this->t_teacher_info->field_update_list($teacherid,[
                "not_grade"    =>$not_grade
                ,"un_freeze_time" => $un_freeze_time
            ]);
            if(empty($not_grade)){
                $this->t_teacher_info->field_update_list($teacherid,[
                    "is_freeze"    =>0,
                    "freeze_adminid"=>0
                ]);

            }

            $this->t_teacher_record_list->row_insert([
                "teacherid"          =>$teacherid,
                "type"               =>4,
                "record_info"        =>$freeze_reason,
                "add_time"           =>time(),
                "acc"                =>$account,
                "is_freeze"          =>2,
                "seller_require_flag"=>$seller_require_flag,
                "grade_range"        =>$grade_range
            ]);

            /**
             * 模板ID   : gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA
             * 标题课程 : 解冻通知
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 操作时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA";//old
            //$template_id      = "5LlhjLrrbKZV0fOaKk-cSngCnlXWeYLBp_DjSu6JbKw";
            $data['first']    = "恭喜,您".$grade_str."的课程已经解冻,解冻原因:".$freeze_reason;
            $data['keyword1'] = "试听课";
            $data['keyword2'] = date("Y-m-d H:i",time());
            $data['remark']   = "请继续关注理优的培训活动，理优期待与你一起共同进步，提高教学服务质量。";

            /**
             * 模板名称 : 老师解冻通知
             * 模板ID   : SMS_46720087
             * 模板内容 : 课程解冻通知：${name}老师您好，您的课程已经解冻。请继续关注理优的培训活动，
             理优期待与你共同进步，提高教学服务质量。
            */
            $sms_id   = 46720087;
            $sms_data = [
                "name" => $tea_nick,
            ];
        }

        $openid = $this->t_teacher_info->get_wx_openid($teacherid);
        if($openid){
            \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
        }else{
            $sign_name = \App\Helper\Utils::get_sms_sign_name();
            $phone     = $this->t_teacher_info->get_phone($teacherid);
            \App\Helper\Utils::sms_common($phone,$sms_id,$sms_data,0,$sign_name);
        }

        return $this->output_succ();
    }


    public function  set_order_fail_info () {
        $require_id                  = $this->get_in_require_id();
        $test_lesson_order_fail_flag = $this->get_in_int_val("test_lesson_order_fail_flag");
        $test_lesson_order_fail_desc = $this->get_in_str_val("test_lesson_order_fail_desc");

        $test_lesson_subject_id=$this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $userid= $this->t_test_lesson_subject->get_userid($test_lesson_subject_id);
        if (!$userid) {
            return $this->output_err("这个数据有问题!");
        }

        $this->t_test_lesson_subject_require->field_update_list($require_id,[
            "test_lesson_order_fail_flag" =>$test_lesson_order_fail_flag,
            "test_lesson_order_fail_set_time" =>time(NULL),
            "test_lesson_order_fail_desc" =>$test_lesson_order_fail_desc,
        ]);

        $phone=$this->t_seller_student_new->get_phone($userid);
        $account=$this->get_account();
        $test_lesson_order_fail_flag_str= E\Etest_lesson_order_fail_flag::get_desc($test_lesson_order_fail_desc );
        $ret_update = $this->t_book_revisit->add_book_revisit(
            $phone,
            "操作者: $account 状态: 签到失败 : $test_lesson_order_fail_flag_str| $test_lesson_order_fail_desc ",
            "system"
        );

        return $this->output_succ();
    }

    public function  set_order_fail_info_ass () {
        $lessonid                    = $this->get_in_lessonid();
        $ass_test_lesson_order_fail_flag = $this->get_in_int_val("ass_test_lesson_order_fail_flag");
        $ass_test_lesson_order_fail_desc = $this->get_in_str_val("ass_test_lesson_order_fail_desc");

        $require_id = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);
        $test_lesson_subject_id=$this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $userid= $this->t_test_lesson_subject->get_userid($test_lesson_subject_id);
        if (!$userid) {
            return $this->output_err("这个数据有问题!");
        }
        if(empty($ass_test_lesson_order_fail_flag)){
             return $this->output_err("请选择失败原因");
        }

        if($ass_test_lesson_order_fail_flag==2000 && empty($ass_test_lesson_order_fail_desc)){
             return $this->output_err("原因说明不能为空");
        }
        $this->t_test_lesson_subject_sub_list->field_update_list($lessonid,[
            "ass_test_lesson_order_fail_flag" =>$ass_test_lesson_order_fail_flag,
            "ass_test_lesson_order_fail_set_time" =>time(NULL),
            "ass_test_lesson_order_fail_set_adminid" =>$this->get_account_id(),
            "ass_test_lesson_order_fail_desc" =>$ass_test_lesson_order_fail_desc,
            "order_confirm_flag"              =>2
        ]);

        $phone=$this->t_seller_student_new->get_phone($userid);
        $account=$this->get_account();
        $ass_test_lesson_order_fail_flag_str= E\Etest_lesson_order_fail_flag::get_desc($ass_test_lesson_order_fail_desc );
        /*if($ass_test_lesson_order_fail_flag==2000){
            $test_lesson_order_fail_flag_str=$ass_test_lesson_order_fail_desc;
            }*/
        $ret_update = $this->t_book_revisit->add_book_revisit(
            $phone,
            "操作者: $account 状态: 签到失败 : $ass_test_lesson_order_fail_flag_str| $ass_test_lesson_order_fail_desc ",
            "system"
        );

        return $this->output_succ();
    }

    public function set_order_get_package_time() {
        $orderid= $this->get_in_int_val("orderid");
        $get_packge_flag= $this->get_in_int_val("get_packge_flag");

        if(!$this->check_account_in_arr(["jim","谭桂连","佟肖"])) {
            return $this->output_err("没有权限");
        }

        if ($get_packge_flag) {
            $get_packge_time=time(NULL);
        }else{
            $get_packge_time=0;
        }
        $this->t_order_info->field_update_list($orderid,[
            "get_packge_time" => $get_packge_time
        ]);

        return $this->output_succ();
    }
    public  function call_ytx_phone()  {
        $phone=$this->get_in_str_val("phone","");
        $ytx_phone=session("ytx_phone");

        if(\App\Helper\Utils::check_env_is_test()){
            $userid = $this->get_in_userid();
            $admind = $this->get_in_adminid();
            //判断该例子是否还是当前cc的[已自动释放]
            $current_adminid = $this->t_seller_student_new->field_get_value($userid, 'admin_revisiterid');
            if($current_adminid != $adminid)
                return $this->output_err("当前用户销售已修改,请刷新页面!");
            //测试环境模拟拨打产生记录
        }


        $admin_info=$this->t_manager_info->field_get_list(  $this->get_account_id(),"*");
        if ($admin_info["call_phone_type"]==E\Ecall_phone_type::V_TL)  {//天润
            //?enterpriseId=&cno=&pwd=&customerNumber=&userField=
            if ($this->get_account()=="jim") {
                $admin_info["tquin"]="2063";
            }
            $ret=\App\Helper\Net:: send_get_data(
                "http://api.clink.cn/interface/PreviewOutcall",
                [
                    "enterpriseId" => 3005131 ,
                    "cno" => $admin_info["tquin"],
                    "pwd" => md5($admin_info["call_phone_passwd"]),
                    "customerNumber"=>$phone,
                    "sync"=>0,
                    // "userField"=>1,
                ]);
            $error_code_conf=[
                0=> "sync=1时表示座席已接听，sync=0时表示发起呼叫请求成功",
                1 =>" 呼叫座席失败 ",
                2=>" 参数不正确",
                3=>"  用户验证没有通过",
                4=>" 账号被停用",
                5 =>"资费不足",
                6 =>"指定的业务尚未开通",
                7 =>"电话号码不正确",
                8 =>"座席工号（cno）不存在",
                9 =>"座席状态不为空闲，可能未登录或忙",
                10 =>"其他错误",
                11 =>"电话号码为黑名单",
                12 =>"座席不在线",
                13 =>"座席正在通话/呼叫中",
                14 =>"外显号码不正确",
                33 =>"同步调用的时候在没有接收到返回值时进行重复调用",
                40 =>"外呼失败，外呼号码次数超过限制",
            ];

            $ret_arr=json_decode($ret,true );
            if (@$ret_arr["res"]) {
                //同步未拨通
                $this->t_seller_student_new->sync_tq($phone,1,time(NULL));
                $this->t_book_revisit->add_book_revisit(
                    $phone,"天润拨打出错:".$ret_arr["res"].@$error_code_conf[$ret_arr["res"]].",设置为未拨通:".$this->get_account(),"system" );
                return $this->output_err( "天润拨打出错:". $ret_arr["res"] . ":". @$error_code_conf[$ret_arr["res"]. ",请重新抢例子" ] );
            }else{
                return $this->output_succ();
            }
        }else{
            if (!$ytx_phone) {
                $ytx_phone= $admin_info["ytx_phone" ]? $admin_info["ytx_phone" ]: $admin_info["tquin" ]  ;
                session("ytx_phone",  $ytx_phone);
            }
            $ytx_account='liyou';

            if (
                $this->get_account_role() == E\Eaccount_role::V_1
                || $this->get_account_role() == E\Eaccount_role::V_8
            ) {
                $ytx_account="liyou2";
            }
            if ($this->get_account()=="zore" ) {
                $ytx_account="liyou2";
            }
            if ( $ytx_account=="liyou" ) {
                return $this->output_err("云通讯,暂停拨打");
            }
            //if ( $this->  )

            \App\Helper\Utils::logger(" PHONE: $ytx_phone ");
            if ($ytx_phone>1000000) {
                \App\Helper\Utils::logger(" 22 PHONE: $ytx_phone ");
                if (strlen($ytx_phone)==8 ) {


                    $ret=\App\Helper\Net::send_post_data(
                        "http://121.196.236.95:8090/FsCall/CallPhone",
                        [
                            "callInfo" => "$ytx_account:$ytx_phone:$phone",
                            // "callInfo" => "liyou:02151136889:15602830297",
                        ]);

                }else{

                    $ret=\App\Helper\Net::send_post_data(
                        "http://121.196.236.95:8090/FsCall/CallPhone",
                        [
                            "callInfo" => "$ytx_account:0$ytx_phone:$phone",
                            // "callInfo" => "liyou:02151136889:15602830297",
                        ]);

                }
                \App\Helper\Utils::logger("NET  ret:". json_encode( $ret ));
            }

        }


        return $this->output_succ();
    }

    public function send_video_url_to_teacher(){
        $subject         = $this->get_in_int_val("subject");
        $video_subject   = $this->get_in_int_val("video_subject");
        $grade           = $this->get_in_int_val("grade");
        $grade_part_ex   = $this->get_in_int_val("grade_part_ex");
        $identity        = $this->get_in_int_val("identity");
        $teacherid       = $this->get_in_int_val("teacherid");
        $url             = $this->get_in_str_val("url");
        $create_type     = $this->get_in_int_val("create_time");
        $tea_qua         = $this->get_in_int_val("tea_qua");
        $tra             = $this->get_in_int_val("tra");
        $send_reason     = trim($this->get_in_str_val("send_reason"));
        $class_content   = trim($this->get_in_str_val("class_content"));
        $send_teacherid  = $this->get_in_int_val("send_teacherid");
        $lesson_start    = $this->get_in_int_val("lesson_start");
        $teacher_info    = $this->t_teacher_info->field_get_list($teacherid,"realname,create_time,identity");
        $identity_str    = E\Eidentity::get_desc($teacher_info["identity"]);
        $grade_str       = E\Egrade::get_desc($grade);
        $day             = ceil((time()-$teacher_info["create_time"])/86400);
        $acc             = $this->get_account();

        $date_week = \App\Helper\Utils::get_week_range(time(),1);
        $lstart    = $date_week["sdate"];
        $lend      = $date_week["edate"];
        $send_num  = $this->t_good_video_send_list->get_video_send_num($lstart,$lend,$video_subject);
        if($send_num >=5){
           return $this->output_err("本月该科目视频推送已到上线！");
        }

        if($send_teacherid>0){
            /**
             * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
             * 标题课程 : 待办事项提醒
             * {{first.DATA}}
             * 待办主题：{{keyword1.DATA}}
             * 待办内容：{{keyword2.DATA}}
             * 日期：{{keyword3.DATA}}
             *{{remark.DATA}}
             */
            $data=[];
             $template_id   = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";//old
            //$template_id   = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
            $data['first'] = $teacher_info["realname"]."老师试听转化率较高，现推荐该老师的部分优秀视频供老师们观看，希望有所帮助";
            $data['keyword1'] = "优秀视频推荐";
            $data['keyword2'] = $grade_str.",课程内容:".$class_content;
            $data['keyword3'] = date("Y-m-d H:i",$lesson_start);
            $data['remark']   = $send_reason
                              ."\n理优期待与你共同进步,打造高品质教学服务！"
                              ."\n立即观看优秀视频";

            $openid = $this->t_teacher_info->get_wx_openid($send_teacherid);
            if(isset($openid) && isset($template_id)){
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
                $this->t_good_video_send_list->row_insert([
                    "send_time"   => time(),
                    "account"     => $acc,
                    "send_reason" => $send_reason,
                    "teacher"     => $teacher_info["realname"],
                    "url"         => $url,
                    "tea_num"     => 1,
                    "grade"       => $grade,
                    "subject"     => $video_subject
                ]);
            }else{
                return $this->output_err("该老师未绑定账号!");
            }
        }else{
            $teacherid_list = $this->t_teacher_info->get_tea_info_by_subject_and_identity($subject,$identity,$grade_part_ex);
            if($create_type==-1){
                $tea_create=$teacherid_list;
            }else{
                $tea_create= $this->t_teacher_info->get_tea_info_by_create_type($create_type,$teacherid_list);
            }
            if($tea_qua==-1){
                $tea_qua_list=$tea_create;
            }else{
                $tea_qua_list= $this->t_teacher_info->get_tea_info_by_tea_qua($tea_qua,$tea_create);
            }

            $teacherid_arr = $this->t_lesson_info->get_have_ten_test_lesson_teacher_list_ss($tea_qua_list);
            $res_teacherid = [];
            if($tra==-1){
                $res_teacherid = $tea_qua_list;
            }else{
                foreach($teacherid_arr as $item){
                    $ret = $this->t_lesson_info->get_thirty_lesson_order_info_by_teacherid($item["teacherid"]);
                    foreach($ret as $v){
                        if($v["course_teacherid"] >0){
                            @$tt ++;
                        }
                    }
                    if($tra==1){
                        if($tt >7){
                            $res_teacherid[]= $item["teacherid"];
                        }
                    }else if($tra==3){
                        if($tt <=7 && $tt >=3){
                            $res_teacherid[]= $item["teacherid"];
                        }
                    }else if($tra==2){
                        if( $tt < 3){
                            $res_teacherid[]= $item["teacherid"];
                        }
                    }
                }
            }

            if(!empty($res_teacherid)){
                foreach($res_teacherid as $val){
                    /**
                     * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                     * 标题课程 : 待办事项提醒
                     * {{first.DATA}}
                     * 待办主题：{{keyword1.DATA}}
                     * 待办内容：{{keyword2.DATA}}
                     * 日期：{{keyword3.DATA}}
                     *{{remark.DATA}}
                     */
                    $data=[];
                     $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";//old
                    //$template_id      = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
                    $data['first']    = $teacher_info["realname"]."老师试听转化率较高，现推荐该老师的部分优秀视频供老师们观看，希望有所帮助";
                    $data['keyword1'] = "优秀视频推荐";
                    $data['keyword2'] = $grade_str.",课程内容:".$class_content;
                    $data['keyword3'] = date("Y-m-d H:i",$lesson_start);
                    $data['remark']   = $send_reason
                                      ."\n理优期待与你共同进步,打造高品质教学服务！"
                                      ."\n立即观看优秀视频";
                    $openid = "oJ_4fxGZQHlRENGlUeA7Tn1nSeII";
                    $i=0;
                    if(isset($openid) && isset($template_id)){
                        // \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
                        $i++;
                    }
                }
                $this->t_good_video_send_list->row_insert([
                    "send_time"              =>time(),
                    "account"                =>$acc,
                    "send_reason"            =>$send_reason,
                    "teacher"                =>$teacher_info["realname"],
                    "url"                    =>$url,
                    "tea_num"                =>$i,
                    "grade"                  =>$grade,
                    "subject"                =>$video_subject
                ]);
            }else{
                return $this->output_err("该条件下没有老师!");
            }
        }

        return $this->output_succ();

    }

    public function send_wx_template_to_teacher(){
        $subject         = $this->get_in_int_val("subject");
        $grade_part_ex   = $this->get_in_int_val("grade_part_ex");
        $identity        = $this->get_in_int_val("identity");
        $url             = $this->get_in_str_val("url");
        $create_type     = $this->get_in_int_val("create_time");
        $tea_qua         = $this->get_in_int_val("tea_qua");
        $tra             = $this->get_in_int_val("tra");
        $first_sentence  = $this->get_in_str_val("first_sentence");
        $end_sentence    = $this->get_in_str_val("end_sentence");
        $keyword1        = $this->get_in_str_val("keyword1");
        $keyword2        = $this->get_in_str_val("keyword2");
        $keyword3        = $this->get_in_str_val("keyword3");
        $template_type     = $this->get_in_int_val("template_type");
        $send_teacherid  = $this->get_in_int_val("send_teacherid");
        $acc= $this->get_account();
        if($send_teacherid>0){
            if($template_type==4){
                /**
                 * 模板ID   : Fw49ejW84qSRcXFFCrHsiCY3Lz6H57IeZZ_gVRdq9TM
                 * 标题课程 : 资料领取通知
                 * {{first.DATA}}
                 *用户姓名：{{keyword1.DATA}}
                 *资料名称：{{keyword2.DATA}}
                 *{{remark.DATA}}
                 */
                $data=[];
                $title = "资料领取通知";
                $template_id      = "Fw49ejW84qSRcXFFCrHsiCY3Lz6H57IeZZ_gVRdq9TM";//old
                //$template_id      = "KfQxp0ynjBSEHso1pwoxP8R6CdU2SeWA7M1YvWNGld8";
                $data['first']    = $first_sentence;
                $data['keyword1'] = $keyword1;
                $data['keyword2'] = $keyword2;
                $data['remark']   = $end_sentence;
                // $openid = $v["wx_openid"];
                // $openid = $this->t_teacher_info->get_wx_openid($send_teacherid);

                $openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
                if(isset($openid) && isset($template_id)){
                    \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
                }
            }else{
                /**
                 * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                 * 标题课程 : 待办事项提醒
                 * {{first.DATA}}
                 * 待办主题：{{keyword1.DATA}}
                 * 待办内容：{{keyword2.DATA}}
                 * 日期：{{keyword3.DATA}}
                 *{{remark.DATA}}
                 */
                $data=[];
                $title = "待办事项提醒";
                $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";//old

                $data['first']    = $first_sentence;
                $data['keyword1'] = $keyword1;
                $data['keyword2'] = $keyword2;
                $data['keyword3'] = $keyword3;
                $data['remark']   = $end_sentence;
                $openid = $this->t_teacher_info->get_wx_openid($send_teacherid);
                // $openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
                if(isset($openid) && isset($template_id)){
                    \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
                }
            }
            $this->t_send_wx_template_record_list->row_insert([
                "template_id"          =>$template_id,
                "send_time"            =>time(),
                "template_type"        =>$template_type,
                "title"                =>$title,
                "first_sentence"       =>$first_sentence,
                "end_sentence"         =>$end_sentence,
                "keyword3"             =>$keyword3,
                "keyword2"             =>$keyword2,
                "keyword1"             =>$keyword1,
                "url"                  =>$url,
                "account"              =>$acc
            ]);


        }else{
            $teacherid_list = $this->t_teacher_info->get_tea_info_by_subject_and_identity($subject,$identity,$grade_part_ex);
            if($create_type==-1){
                $tea_create=$teacherid_list;
            }else{
                $tea_create= $this->t_teacher_info->get_tea_info_by_create_type($create_type,$teacherid_list);
            }
            if($tea_qua==-1){
                $tea_qua_list=$tea_create;
            }else{
                $tea_qua_list= $this->t_teacher_info->get_tea_info_by_tea_qua($tea_qua,$tea_create);
            }

            $teacherid_arr = $this->t_lesson_info->get_have_ten_test_lesson_teacher_list_ss($tea_qua_list);
            $res_teacherid=[];
            if($tra==-1){
                $res_teacherid=$tea_qua_list;
            }else{
                foreach($teacherid_arr as $item){
                    $ret = $this->t_lesson_info->get_thirty_lesson_order_info_by_teacherid($item["teacherid"]);
                    foreach($ret as $v){
                        if($v["course_teacherid"] >0){
                            @$tt ++;
                        }
                    }
                    if($tra==1){
                        if($tt >7){
                            $res_teacherid[]= $item["teacherid"];
                        }
                    }else if($tra==3){
                        if($tt <=7 && $tt >=3){
                            $res_teacherid[]= $item["teacherid"];
                        }
                    }else if($tra==2){
                        if( $tt < 3){
                            $res_teacherid[]= $item["teacherid"];
                        }
                    }
                }
            }

            if(!empty($res_teacherid)){
                foreach($res_teacherid as $val){
                    if($template_type==4){
                        /**
                         * 模板ID   : Fw49ejW84qSRcXFFCrHsiCY3Lz6H57IeZZ_gVRdq9TM
                         * 标题课程 : 资料领取通知
                         * {{first.DATA}}
                         *用户姓名：{{keyword1.DATA}}
                         *资料名称：{{keyword2.DATA}}
                         *{{remark.DATA}}
                         */
                        $data=[];
                        $title = "资料领取通知";
                         $template_id      = "Fw49ejW84qSRcXFFCrHsiCY3Lz6H57IeZZ_gVRdq9TM";//old

                        $data['first']    = $first_sentence;
                        $data['keyword1'] = $keyword1;
                        $data['keyword2'] = $keyword2;
                        $data['remark']   = $end_sentence;
                        // $openid = $v["wx_openid"];
                        $openid = $this->t_teacher_info->get_wx_openid($val);
                        // $openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
                        if(isset($openid) && isset($template_id)){
                            \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
                        }
                    }else{
                        /**
                         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                         * 标题课程 : 待办事项提醒
                         * {{first.DATA}}
                         * 待办主题：{{keyword1.DATA}}
                         * 待办内容：{{keyword2.DATA}}
                         * 日期：{{keyword3.DATA}}
                         *{{remark.DATA}}
                         */
                        $data=[];
                        $title = "待办事项提醒";
                         $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";//old
                        //$template_id      = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
                        $data['first']    = $first_sentence;
                        $data['keyword1'] = $keyword1;
                        $data['keyword2'] = $keyword2;
                        $data['keyword3'] = $keyword3;
                        $data['remark']   = $end_sentence;
                        $openid = $this->t_teacher_info->get_wx_openid($val);
                        // $openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
                        if(isset($openid) && isset($template_id)){
                            \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
                        }
                    }
                }
                $this->t_send_wx_template_record_list->row_insert([
                    "template_id"          =>$template_id,
                    "send_time"            =>time(),
                    "template_type"        =>$template_type,
                    "title"                =>$title,
                    "first_sentence"       =>$first_sentence,
                    "end_sentence"         =>$end_sentence,
                    "keyword3"             =>$keyword3,
                    "keyword2"             =>$keyword2,
                    "keyword1"             =>$keyword1,
                    "url"                  =>$url,
                    "account"              =>$acc
                ]);

            }else{
                return $this->output_err("该条件下没有老师!");
            }

        }

        return $this->output_succ();

    }

    public function add_cancel_lesson_four_hour_list(){
        $teacherid = $this->get_in_int_val("teacherid");
        $lessonid = $this->get_in_int_val("lessonid");
        $lesson_time = $this->get_in_int_val("lesson_time");
        $account = $this->get_account();
        $this->t_teacher_cancel_lesson_list->row_insert([
            "teacherid"    =>$teacherid,
            "account"      =>$account,
            "lessonid"     =>$lessonid,
            "lesson_time"  =>$lesson_time,
            "cancel_time"  =>time()
        ]);
        return $this->output_succ();
    }

    public function update_teacher_lesson_hold_flag(){
        $teacherid = $this->get_in_int_val("teacherid");
        $lesson_hold_flag = $this->get_in_int_val("lesson_hold_flag");
        $lesson_hold_flag_acc = $this->get_account();
        $this->t_teacher_info->field_update_list($teacherid,[
            "lesson_hold_flag"     =>$lesson_hold_flag,
            "lesson_hold_flag_acc" =>$lesson_hold_flag_acc,
            "lesson_hold_flag_time" =>time(),
        ]);
        return $this->output_succ();

    }

    public function update_research_note(){
        $teacherid = $this->get_in_int_val("teacherid");
        $research_note = $this->get_in_str_val("research_note");
        $this->t_teacher_info->field_update_list($teacherid,["research_note"=>$research_note]);
        return $this->output_succ();

    }


    public function get_train_lesson_record_info(){
        $id = $this->get_in_int_val("id");
        $lessonid = $this->get_in_int_val("lessonid",273923);
        $data = $this->t_teacher_record_list->field_get_list($id,"*");
        $label_info = $this->t_teacher_label->get_info_by_lessonid_new($lessonid,2);
        if(@$label_info["tag_info"]){
            $label = $label_info["tag_info"];
            $arr= json_decode($label,true);
            foreach($arr as $item){
                $ret = json_decode($item,true);
                if($ret){
                    foreach($ret as $v){
                        @$str .=$v.",";
                    }
                }
            }
            $data["label"] = trim($str,",");


        }else{
            $label = @$label_info["tea_label_type"];
            $arr= json_decode($label,true);
            if(empty($arr)){
                $arr=[];
                $data["label"]="";
            }else{
                foreach($arr as $val){
                    @$str .= E\Etea_label_type::get_desc($val).",";
                }
                $data["label"] = trim($str,",");

            }
        }
        return $this->output_succ(["data"=>$data]);
    }
    public function get_teacher_confirm_score(){
        $id = $this->get_in_int_val("id");
        //$id = 53;
        $data = $this->t_teacher_lecture_info->field_get_list($id,"*");
        // dd($data);
        return $this->output_succ(["data"=>$data]);
    }

    public function update_ass_student_renw_info(){
        $userid         = $this->get_in_int_val("userid");
        $id         = $this->get_in_int_val("id");
        $start_time     = strtotime($this->get_in_str_val("start_time"));
        $ass_renw_flag  = $this->get_in_int_val("ass_renw_flag");
        $renw_price     = $this->get_in_int_val("renw_price");
        $renw_week      = $this->get_in_int_val("renw_week");
        $no_renw_reason = $this->get_in_str_val("no_renw_reason");
        $this->t_month_ass_warning_student_info->field_update_list($id,[
            "ass_renw_flag"         =>$ass_renw_flag,
            "renw_price"            =>$renw_price,
            "no_renw_reason"        =>$no_renw_reason,
            "renw_week"             =>$renw_week
        ]);
        return $this->output_succ();
    }

    public function update_ass_student_renw_info_new(){
        $userid         = $this->get_in_int_val("userid");
        $id         = $this->get_in_int_val("id");
        $start_time     = strtotime($this->get_in_str_val("start_time"));
        $ass_renw_flag  = $this->get_in_int_val("ass_renw_flag");
        $renw_price     = $this->get_in_int_val("renw_price");
        $renw_week      = $this->get_in_int_val("renw_week");
        $no_renw_reason = trim($this->get_in_str_val("no_renw_reason"));
        if($ass_renw_flag==2 && empty($no_renw_reason)){
             return $this->output_err("不续费原因不能为空!");
        }
        if(in_array($ass_renw_flag,[1,3]) && $renw_week<=0){
            return $this->output_err("计划续费周不能为空!");
        }
        $ass_renw_flag_old= $this->t_month_ass_warning_student_info->get_ass_renw_flag($id);
        if($ass_renw_flag_old >0 && $ass_renw_flag==0){
             return $this->output_err("不能设置为未设置状态");
        }

        $ret = $this->t_month_ass_warning_student_info->field_update_list($id,[
            "ass_renw_flag"         =>$ass_renw_flag,
            "renw_price"            =>$renw_price,
            "no_renw_reason"        =>$no_renw_reason,
            "renw_week"             =>$renw_week
        ]);

        if($ret){
            if($ass_renw_flag==2){
                $this->t_month_ass_warning_student_info->field_update_list($id,[
                    "done_flag"             =>2,
                    "done_time"             =>time(),
                ]);

            }

            if(!empty($renw_week)){
                $renw_end_day = strtotime(date("Y-m-d", time()+ $renw_week*7*86400));
            }else{
                $renw_end_day=0;
            }

            $this->t_ass_warning_renw_flag_modefiy_list->row_insert([
                "add_time"   =>time(),
                "userid"     =>$userid,
                "ass_renw_flag_before"  =>$ass_renw_flag_old,
                "ass_renw_flag_cur"     =>$ass_renw_flag,
                "no_renw_reason"        =>$no_renw_reason,
                "adminid"               =>$this->get_account_id(),
                "warning_id"            =>$id,
                "renw_week"             =>$renw_week,
                "renw_end_day"          =>$renw_end_day
            ]);
        }

        return $this->output_succ();
    }


    public function update_ass_student_renw_info_master(){
        $userid         = $this->get_in_int_val("userid");
        $id         = $this->get_in_int_val("id");
        $start_time     = strtotime($this->get_in_str_val("start_time"));
        $master_renw_flag  = $this->get_in_int_val("master_renw_flag");
        $master_no_renw_reason = $this->get_in_str_val("master_no_renw_reason");
        $this->t_month_ass_warning_student_info->field_update_list($id,[
            "master_renw_flag"         =>$master_renw_flag,
            "master_no_renw_reason"        =>$master_no_renw_reason,
        ]);
        return $this->output_succ();
    }

    public function update_ass_student_renw_info_master_new(){
        $userid         = $this->get_in_int_val("userid");
        $id         = $this->get_in_int_val("id");
        $start_time     = strtotime($this->get_in_str_val("start_time"));
        $master_renw_flag  = $this->get_in_int_val("master_renw_flag");
        $master_no_renw_reason = $this->get_in_str_val("master_no_renw_reason");
        $this->t_month_ass_warning_student_info->field_update_list($id,[
            "master_renw_flag"         =>$master_renw_flag,
            "master_no_renw_reason"        =>$master_no_renw_reason,
        ]);
        if($master_renw_flag==1){
            $this->t_month_ass_warning_student_info->field_update_list($id,[
                "done_flag"             =>1,
                "done_time"             =>time(),
            ]);
        }
        return $this->output_succ();
    }

    public function sync_tq() {
        $now=time(NULL);
        $start_date = \App\Helper\Utils::unixtime2date($now-3*60*60 ,"Y-m-d H:i:s");
        $end_date   = \App\Helper\Utils::unixtime2date($now,"Y-m-d H:i:s");
        $phone= $this->get_in_phone();
        $userid= $this->get_in_userid(0);
        $tq_called_flag=$this->get_in_int_val("tq_called_flag") ;
        $adminid = $this->get_in_adminid();


        if (!$phone) {
            return $this->output_err("当前用户不存在");
        }

        if(\App\Helper\Utils::check_env_is_test() || \App\Helper\Utils::check_env_is_local()){
            //判断该例子是否还是当前cc的[已自动释放]
            $current_adminid = $this->t_seller_student_new->field_get_value($userid, 'admin_revisiterid');
            if($current_adminid != $adminid)
                return $this->output_err("当前用户销售已修改,请刷新页面!");
        }

        $cmd= new \App\Console\Commands\sync_tq();
        $count=$cmd->load_data($start_date,$end_date,$phone);
        $reload_flag=false;
        if ($userid ) {
            if( $tq_called_flag != $this->t_seller_student_new->get_tq_called_flag($userid)) {
                $reload_flag=true;
            }
        }

        return $this->output_succ([
            "reload_flag" =>  $reload_flag
        ]);
    }

    public function sync_ytx( ) {


        $now=time(NULL);
        $start_date = \App\Helper\Utils::unixtime2date($now-60*60 ,"Y-m-d H:i:s");
        $end_date  = \App\Helper\Utils::unixtime2date($now,"Y-m-d H:i:s");
        $phone=$this->get_in_phone();
        $ytx_account= $this->get_in_str_val("ytx_account","liyou2");
        if ($ytx_account=="liyou") {
            return $this->output_err("云通讯暂停");
        }


        $ytx_phone=session("ytx_phone");

        if (!$ytx_phone) {
            $ytx_phone= $this->t_manager_info->get_tquin( $this->get_account_id() );
            session("ytx_phone",  $ytx_phone);
        }

        $cmd= new \App\Console\Commands\ytx_sync ();
        $count=$cmd->load_data( $ytx_account,$start_date,$end_date,-1,$phone );
        \App\Helper\Utils::logger("$ytx_account COUNT :$count ");


        return $this->output_succ();
    }


    public function update_student_init_lesson_info(){
        $userid         = $this->get_in_int_val("userid");
        $week_lesson_num          = $this->get_in_int_val("week_lesson_num");
        $except_lesson_count          = $this->get_in_int_val("except_lesson_count");
        $this->t_student_init_info->field_update_list($userid,[
            "week_lesson_num" =>$week_lesson_num,
            "except_lesson_count" =>$except_lesson_count
        ]);
        return $this->output_succ();
    }

    public function deal_tel_state(){
        $userid_list[0]           = $this->get_in_int_val("user_id");
        $seller_status            = $this->get_in_int_val("seller_status");
        $opt_adminid              = $this->get_account_id();
        $opt_type                 = 2;

        $ret = $this->t_test_subject_free_list->set_tel_state($userid_list[0],$opt_adminid);
        if($seller_status == 1){
            $this->set_admin_id_ex($userid_list, $opt_adminid, $opt_type);
        }

        return $this->output_succ();
    }

    public function get_stu_test_lesson_suject_info(){
        $userid           = $this->get_in_int_val("userid");
        // $userid= 50314;
        $list = $this->t_student_info->get_stu_test_lesson_suject_info($userid);
        return $this->output_succ(["data"=>$list]);
        //dd($list);
    }

    public function get_course_order_info(){
        $courseid           = $this->get_in_int_val("courseid");
        $list = $this->t_course_order->get_course_order_info_new($courseid);
        $list["subject_str"] = E\Esubject::get_desc($list["subject"]);
        $list["grade_str"] = E\Egrade::get_desc($list["grade"]);

        return $this->output_succ(["data"=>$list]);
    }
    public function clean_admin_seller_student () {
        $adminid= $this->get_in_adminid();
        if ($adminid) {
            $this->t_seller_student_new->clean_by_admin_revisiterid($adminid );
        }
        return  $this->output_succ();
    }

    public function test_subject_free_list_add () {

        $userid= $this->get_in_userid();
        $adminid= $this ->get_account_id();
        //检查是否当前userid ,是否是自己的
        $now= time(NULL);

        $key="DEAL_NEW_USER_$adminid";
        $old_userid=\App\Helper\Common::redis_get($key)*1;
        //$this->t_seller_student_new->get_sell
        $old_row_data= $this->t_seller_student_new->field_get_list($old_userid,"competition_call_time, competition_call_adminid, admin_revisiterid ,tq_called_flag ");

        if (
            $old_row_data["tq_called_flag"] ==0 &&
            $old_row_data["admin_revisiterid"] !=$adminid
             &&  $now- $old_row_data["competition_call_time"] < 3600  ) {
            return $this->output_err("有新例子tq未拨打",["need_deal_cur_user_flag" =>true]);

        }

        if(!$this->t_seller_student_new->check_admin_add($adminid,$get_count,$max_day_count )){
            return $this->output_err("目前你持有的例子数[$get_count]>=最高上限[$max_day_count]");
        }

        if (!$this->t_seller_new_count->get_free_new_count_id($adminid,"获取新例子"))  {
            return $this->output_err("今天的配额,已经用完了");
        }


        $row_data= $this->t_seller_student_new->field_get_list($userid,"competition_call_time, competition_call_adminid, admin_revisiterid ");
        $competition_call_time = $row_data["competition_call_time"];
        $competition_call_adminid = $row_data["competition_call_adminid"];
        $admin_revisiterid = $row_data["admin_revisiterid"];
        if ($admin_revisiterid !=  0  && $admin_revisiterid != $adminid) {
            return $this->output_err("已经被人抢了1");
        }
        if ( $competition_call_adminid != $adminid &&  $now- $competition_call_time  < 3600  ) { //
            return $this->output_err("已经被人抢了2");
        }

        $this->t_test_subject_free_list ->row_insert([
            "add_time" => time(NULL),
            "userid" =>   $userid,
            "adminid" => $adminid,
            "test_subject_free_type" => 0,
        ],false,true);
        $this->t_seller_student_new->field_update_list($userid,[
            "competition_call_time" => $now,
            "tq_called_flag" => 0,
            "competition_call_adminid" =>$adminid
        ]);

        \App\Helper\Common::redis_set($key, $userid );

        return $this->output_succ();

    }

    public function set_teacher_limit_plan_lesson_new(){
        $teacherid                = $this->get_in_int_val("teacherid");
        $limit_plan_lesson_type   = $this->get_in_int_val("limit_plan_lesson_type");
        $seller_require_flag      = $this->get_in_int_val("seller_require_flag",0);
        $is_limit_old             = $this->get_in_int_val("is_limit_old",1);
        $limit_plan_lesson_reason = trim($this->get_in_str_val("limit_plan_lesson_reason"));
        $account                  = $this->get_account();
        $account_id = $this->get_account_id();
        $time     = time();
        $tea_nick = $this->cache_get_teacher_nick($teacherid);
        $limit_account = $this->t_teacher_info->field_get_value($teacherid,"limit_plan_lesson_account");
        $not_grade_limit_old       = $this->t_teacher_info->field_get_value($teacherid,"not_grade_limit");
        $grade_range     = $this->get_in_int_val("grade_range",0);
        $grade_detail = $this->get_detail_grade($grade_range);
        if($not_grade_limit_old){
            $not_grade_limit_old_arr = json_decode($not_grade_limit_old,true);
            if(isset($not_grade_limit_old_arr[$grade_range])){
                $old_type = $not_grade_limit_old_arr[$grade_range];
                $not_grade_limit_old_arr[$grade_range] = $limit_plan_lesson_type;
            }else{
                $old_type=0;
                $not_grade_limit_old_arr[$grade_range] = $limit_plan_lesson_type;
            }
            $not_grade_limit = json_encode($not_grade_limit_old_arr);
        }else{
            $grade_arr=[$grade_range=>$limit_plan_lesson_type];
            $not_grade_limit = json_encode($grade_arr);
            $old_type=0;
        }

        if(!empty($limit_account) && $account!=$limit_account && $account_id !=72 ){
            return $this->output_err("没有权限!");
        }

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "limit_plan_lesson_reason"  => $limit_plan_lesson_reason,
            "limit_plan_lesson_time"    => $time,
            "not_grade_limit"           => $not_grade_limit,
            "is_limit_old"              => $is_limit_old
        ]);
        if($account_id !=72){
            $this->t_teacher_info->field_update_list($teacherid,[
                "limit_plan_lesson_account" => $account
            ]);

        }
        if($ret){
            $this->t_teacher_record_list->row_insert([
                "teacherid"          =>$teacherid,
                "type"               =>3,
                "record_info"        =>$limit_plan_lesson_reason,
                "add_time"           =>time(),
                "limit_plan_lesson_type"    => $limit_plan_lesson_type,
                "acc"                =>$account,
                "seller_require_flag"=>$seller_require_flag,
                "limit_plan_lesson_type_old" =>$old_type,
                "grade_range"                =>$grade_range
            ]);

            if($limit_plan_lesson_type != $old_type && ($limit_plan_lesson_type==0 || ($limit_plan_lesson_type !=0 && $limit_plan_lesson_type > $old_type && $old_type !=0))){
                if($limit_plan_lesson_type==0){
                    $rr="不限课";
                }else{
                    $rr="一周限排".$limit_plan_lesson_type."节";
                }
                // $admin_arr=[72,448];
                $admin_arr=[349];
                foreach($admin_arr as $ss){
                    $this->t_manager_info->send_wx_todo_msg_by_adminid ($ss,"理优监课组","老师限制排课更改",$tea_nick."老师,".$grade_detail."限制排课由一周限排".$old_type."节,改为".$rr.",操作人:".$account,"");
                }

            }

            if($limit_plan_lesson_type >0 && $limit_plan_lesson_type != $old_type){
                /**
                 * 模板ID : n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc
                 * 标题   :课程冻结通知
                 * {{first.DATA}}
                 * 课程名称：{{keyword1.DATA}}
                 * 操作时间：{{keyword2.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id      = "n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc";//old

                $data['first']    = "您好，在近期的教学质量抽查过程中，您".$grade_detail."的课程被限制排课,一周试听课排课数量不超过".$limit_plan_lesson_type."次。\n限制排课原因：".$limit_plan_lesson_reason;
                $data['keyword1'] = "试听课";
                $data['keyword2'] = date("Y-m-d H:i",time());
                $data['remark']   = "参加相关培训达标后，系统会放开排课限制，"
                                  ."如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提高教学服务质量。";

                /**
                 * 模板类型 : 短信通知
                 * 模板名称 : 限制老师排课通知2-14
                 * 模板ID   : SMS_46835164
                 * 模板内容 : 限制排课通知：${name}老师您好，近期我们进行教学质量抽查，您的课程反馈情况是：${reason}。
                 您已被限制排课，一周试听课排课数量不超过${num}节。参加相关培训达标后，系统会放开排课限制，
                 如有疑问请联系各学科教研老师。理优期待与你共同进步，提高教学服务质量。
                */
                $sms_id   = 46835164;
                $sms_data = [
                    "name"   => $tea_nick,
                    "num"    => $limit_plan_lesson_type,
                    "reason" => $limit_plan_lesson_reason,
                ];
            }elseif($limit_plan_lesson_type ==0 && $limit_plan_lesson_type != $old_type){
                /**
                 * 模板ID   : gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA
                 * 标题课程 : 解冻通知
                 * {{first.DATA}}
                 * 课程名称：{{keyword1.DATA}}
                 * 操作时间：{{keyword2.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id      = "gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA";//old
                $data['first']    = "恭喜,您".$grade_detail."的课程已经解除排课限制";
                $data['keyword1'] = "试听课";
                $data['keyword2'] = date("Y-m-d H:i",time());
                $data['remark']   = "请继续关注理优的培训活动，理优期待与你一起共同进步，提高教学服务质量。";

                /**
                 * 模板名称 : 解除排课限制通知2-14
                 * 模板ID   : SMS_46725145
                 * 模板内容 : 解除排课限制通知：${name}老师您好，您的课程已经解除排课限制。
                 请继续关注理优的培训活动，理优期待与你共同进步，提高教学服务质量。
                */
                $sms_id   = 46725145;
                $sms_data = [
                    "name" => $tea_nick,
                ];
            }

            $openid = $this->t_teacher_info->get_wx_openid($teacherid);
            if(isset($openid) && isset($template_id)){
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
            }elseif(isset($sms_id)){
                $phone = $this->t_teacher_info->get_phone($teacherid);
                if(isset($phone)){
                    $sign_name = \App\Helper\Utils::get_sms_sign_name();
                    \App\Helper\Utils::sms_common($phone,$sms_id,$sms_data,0,$sign_name);
                }
            }
        }

        return $this->output_succ();
    }


    public function get_teacher_list ()
    {
        $this->switch_tongji_database();
        $page_num     = $this->get_in_page_num();
        $lesson_time  = $this->get_in_int_val("lesson_time");
        $end_time = $lesson_time+2400;
        $teacherid_arr = $this->t_lesson_info->get_test_lesson_num_by_free_time_new($lesson_time,$end_time);
        $subject      = $this->get_in_int_val("subject");
        $grade        = $this->get_in_int_val("grade");
        $date_week = \App\Helper\Utils::get_week_range($lesson_time,1);
        $lstart    = $date_week["sdate"];
        $lend      = $date_week["edate"];

        $ret_info     = $this->t_teacher_info->get_all_usefull_teacher_list($page_num,$teacherid_arr,$subject,$grade,$lstart,$lend);
        foreach($ret_info['list'] as &$item){
            $item['subject']      = E\Esubject::get_desc ($item['subject' ]) ;
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
            if($item["grade_start"]>0){
                $item["grade"] = $item["grade_start_str"]."至".$item["grade_end_str"];
            }else{
                $item["grade"] = $item["grade_part_ex_str"];
            }
            if($item["limit_plan_lesson_type"]>0){
                $item["week_left_num"]=$item["limit_plan_lesson_type"]-$item["week_lesson_num"];
            }else{
                $ret_num = $this->t_lesson_info->check_teacher_have_test_lesson_pre_week($item["teacherid"],time());
                if($ret_num==1){
                    $item["week_left_num"]=$item["limit_week_lesson_num"]-$item["week_lesson_num"];
                }else{
                    $item["week_left_num"]=6-$item["week_lesson_num"];
                }
            }
            if($item["week_left_num"]<0){
                $item["week_left_num"]=0;
            }
            $item["textbook_str"]="";

            E\Etextbook_type::set_item_value_str($item);
            if($item["textbook_type"]>0){
                $item["textbook"] = $item["textbook_type_str"];
            }elseif($item["teacher_textbook"]){
                $arr= explode(",",$item["teacher_textbook"]);
                foreach($arr as $val){
                    @$item["textbook"] .=  E\Eregion_version::get_desc ($val).",";
                }
                $item["textbook"] = trim($item["textbook"],",");
            }else{
                $item["textbook"]="";
            }

            if($item["test_transfor_per"]>=20){
                $item["fine_dimension"]="维度A";
            }elseif($item["test_transfor_per"]>=10 && $item["test_transfor_per"]<20){
                $item["fine_dimension"]="维度B";
            }elseif($item["test_transfor_per"]<10 && in_array($item["identity"],[5,6]) && $item["month_stu_num"]>=4 && $item["record_score"]>=60 && $item["record_score"]<=90){
                $item["fine_dimension"]="维度C";
            }elseif($item["test_transfor_per"]<10 && !in_array($item["identity"],[5,6]) && $item["month_stu_num"]>=4 && $item["record_score"]<=90){
                $item["fine_dimension"]="维度C候选";
            }elseif($item["test_transfor_per"]<10 && in_array($item["identity"],[5,6]) && $item["month_stu_num"]>=1 && $item["month_stu_num"]<=3 && $item["record_score"]>=60 && $item["record_score"]<=90){
                $item["fine_dimension"]="维度D";
            }elseif($item["test_transfor_per"]<10 && !in_array($item["identity"],[5,6]) && $item["month_stu_num"]>=1 && $item["month_stu_num"]<=3 && $item["record_score"]<=90){
                $item["fine_dimension"]="维度D候选";
            }else{
                $item["fine_dimension"]="其他";
            }
            if(empty($item["address"])){
                $item["address"] = \App\Helper\Common::get_phone_location($item["phone"]);
                $item["address"]   = substr($item["address"], 0, -6);
            }



        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return outputjson_success(array('data' => $ret_info));
    }

    public function get_kk_require_list ()
    {
        $userid    = $this->get_in_userid();
        $teacherid  = $this->get_in_int_val("teacherid");
        $subject  = $this->get_in_int_val("subject");
        $page_num     = $this->get_in_page_num();
        $ret_info   = $this->t_test_lesson_subject_require->get_all_kk_require_list($page_num,$userid,$teacherid,$subject);

        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return outputjson_success(array('data' => $ret_info));
    }

    public function get_stu_grade_by_sid(){
        $sid = $this->get_in_int_val('sid');
        $grade = $this->t_student_info->get_stu_grade_by_sid($sid);
        return outputjson_success(array('data' => $grade));
    }

    public function do_complaint_assign_department(){
        $accept_adminid = $this->get_in_str_val('accept_adminid');
        // $accept_adminid = $this->t_manager_info->get_id_by_account($accept_adminid_nick);
        $this->set_in_value('accept_adminid',$accept_adminid);
        return $this->do_complaint_assign();
    }

    public function do_complaint_assign(){
        $assign_adminid   = $this->get_account_id();
        $complaint_id     = $this->get_in_int_val('complaint_id');
        $assign_remarks   = $this->get_in_str_val('ass_remark');
        $accept_adminid   = $this->get_in_int_val('accept_adminid');

        $time_date        = date('Y-m-d H:i:s',time(NULL));

        $ret = $this->t_complaint_assign_info->row_insert([
            "complaint_id"      => $complaint_id,
            "assign_adminid"    => $assign_adminid,
            "assign_remarks"    => $assign_remarks,
            "accept_adminid"    => $accept_adminid,
            "assign_time"       => time(NULL),
        ]);

        $complaint_info_arr  = $this->t_complaint_info->get_complaint_info_by_id($complaint_id);
        $complaint_info      = $complaint_info_arr['complaint_info'];

        if ($ret) {
            $this->t_complaint_info->field_update_list($complaint_id,[
                "current_adminid"  =>$accept_adminid ,
                "current_admin_assign_time"  => time(NULL),
            ]);

            //向被分配人发送信息
            /**
               {{first.DATA}}
               待办主题：{{keyword1.DATA}}
               待办内容：{{keyword2.DATA}}
               日期：{{keyword3.DATA}}
               {{remark.DATA}}
             **/


            $assign_nick = $this->t_manager_info->get_ass_master_nick($assign_adminid);

            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
            $data_msg = [
                "first"     => " 你收到一条投诉处理 分配人: $assign_nick",
                "keyword1"  => " 投诉处理",
                "keyword2"  => " 投诉内容:$complaint_info",
                "keyword3"  => " 分配时间:$time_date ",
            ];
            $url = '';
            $wx=new \App\Helper\Wx();
            $qc_item = $this->t_manager_info->get_wx_openid($accept_adminid);

            $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);

            return $this->output_succ();
        }
    }


    public function get_complaint_remark_list(){
        $complaint_id = $this->get_in_int_val('complaint_id');

        $complaint_remark = $this->t_complaint_assign_info->get_remark_by_complaintid($complaint_id);

        return $this->output_succ(["data" => $complaint_remark ]);
    }


    public function deal_complaint(){
        $deal_info          = $this->get_in_str_val('deal_info');
        $suggest_info       = $this->get_in_str_val('suggest_info');
        $complaint_state    = $this->get_in_int_val('complaint_state');
        $complaint_id       = $this->get_in_int_val('complaint_id');
        $deal_adminid       = $this->get_account_id();
        $deal_account       = $this->get_account();
        $account_type       = $this->get_in_int_val('account_type');

        $this->t_complaint_deal_info->del_row_by_complaint_id($complaint_id);
        $ret = $this->t_complaint_deal_info->row_insert([
            "complaint_id"  => $complaint_id,
            "deal_adminid"  => $deal_adminid,
            "deal_time"     => time(NULL),
            "deal_info"     => $deal_info,
        ]);

        $complaint_info = $this->t_complaint_info->get_complaint_info_by_id($complaint_id);

        $add_time        = date('Y-m-d,H:i:s',$complaint_info["add_time"]);
        $complaint_info_str  = $complaint_info['complaint_info'];
        $deal_info       = $complaint_info['deal_info'];
        // $deal_time_date  = date('Y-m-d H:i:s',$complaint_info['deal_time']);
        $deal_time_date  = date('Y-m-d H:i:s');
        E\Ecomplaint_type::set_item_value_str($complaint_info);
        $complaint_type_str = $complaint_info['complaint_type_str'];

        if ($ret)
            $re = $this->t_complaint_info->field_update_list($complaint_id,[
                "suggest_info"       => $suggest_info,
                "complaint_state"    => $complaint_state
            ]);
        $deal_wx_openid_list = [];

        if ($complaint_state == 1) {
            if ($account_type == 1) { // 1:家长
                // 理优在线教育  // 8GYohyn1V6dmhuEB6ZQauz5ZtmqnnJFy-ETM8yesU3I
                /**
                   {{first.DATA}}
                   提交详情：{{keyword1.DATA}}
                   反馈内容：{{keyword2.DATA}}
                   时间：{{keyword3.DATA}}
                   {{remark.DATA}}
                */

                $parent_nick   = $this->cache_get_parent_nick($complaint_info["userid"] );
                $parent_openid = $this->t_parent_info->get_wx_openid_by_parentid($complaint_info["userid"]);

                $first_qc = "家长投诉反馈通知";
                $first_nick = "家长 $parent_nick ";

                $time_date = date('Y-m-d H:i:m',time(NULL));
                $template_id = "8GYohyn1V6dmhuEB6ZQauz5ZtmqnnJFy-ETM8yesU3I";//投诉结果通知
                $data_msg = [
                    "first"     => "尊敬的 家长 $parent_nick 您好,您的投诉我们已处理",
                    "keyword1"  => "$complaint_type_str",
                    "keyword2"  => "我们已经核实了相关问题,并进行了处理,感谢您用宝贵的时间和我们沟通!",
                    "keyword3"  => " $time_date ",
                ];
                $url = '';
                $wx=new \App\Helper\Wx();
                $ret_parent=$wx->send_template_msg($parent_openid,$template_id,$data_msg ,$url);

            }elseif($account_type == 2) {  // 2:老师
                /**
                   模板id:r6WBVhVRG8tYS_4RGVwKbPcDPVBrMtOCBqfU87sdvdE
                   {{first.DATA}}
                   投诉内容：{{keyword1.DATA}}
                   处理结果：{{keyword2.DATA}}
                   {{remark.DATA}}
                */

                $deal_wx_openid_list = [
                    "orwGAswxkjf1agdPpFYmZxSwYJsI", // coco 老师 [张科]
                    // "orwGAs1H3MQBeo0rFln3IGk4eGO8"  // sunny
                ];


                $url = '';
                $teacher_nick     = $this->cache_get_teacher_nick($complaint_info["userid"] );
                $teacher_openid   = $this->t_teacher_info->get_wx_openid_by_teacherid($complaint_info["userid"]);
                $first_qc = "老师投诉反馈通知";
                $first_nick = " 老师 $teacher_nick ";


                $template_id        = "r6WBVhVRG8tYS_4RGVwKbPcDPVBrMtOCBqfU87sdvdE";
                $data['first']      = "尊敬的老师 $teacher_nick 您好,您的投诉我们已经处理 ";
                $data['keyword1']   = $complaint_type_str;
                $data['keyword2']   = "我们已经核实了相关问题,并进行了处理,感谢您用宝贵的时间和我们沟通!";
                $data['remark']     = "感谢您用宝贵的时间和我们沟通！";
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_openid,$template_id,$data);
            } elseif($account_type == 3){ // QC

                $first_qc   = "QC退费投诉反馈通知";
                $userid = $this->t_complaint_info->get_userid($complaint_id);
                $first_nick = $this->cache_get_account_nick($userid);

                $complained_adminid_type = $this->t_complaint_info->get_complained_adminid_type($complaint_id);
                $complained_adminid     = $this->t_complaint_info->get_complained_adminid($complaint_id);
                if($complained_adminid_type == 5){

                    $subject = $this->t_teacher_info->get_subject($complained_adminid);

                    $subject_adminid_wx_openid_list = [];

                    if($subject == 1){ // 语文[千千 melody]
                        $subject_adminid_wx_openid_list = [
                            "oJ_4fxGUveIS0n2PxdmaTN2nT4j8", // 千千
                            "oJ_4fxJqMn0pH4XQfgXYiFb_1_Iw"  // melody
                        ];
                    }elseif($subject == 3) { //[英语]
                        $subject_adminid_wx_openid_list = [
                            "oJ_4fxGUveIS0n2PxdmaTN2nT4j8", // 千千
                        ];
                    }elseif($subject == 5){ //[物理]
                        $subject_adminid_wx_openid_list = [
                            "oJ_4fxOo38y6hEisvFoSxN4T1nBs", // 李红涛
                        ];
                    }elseif($subject == 4){ //[化学]
                        $subject_adminid_wx_openid_list = [
                            "oJ_4fxME6lCpNAMwtEhMcpYo5N7c", // 展慧东
                        ];
                    }elseif($subject == 2){ // 数学
                        $subject_adminid_wx_openid_list = [
                            "oJ_4fxFE0-MHPkT-vstzEDzAfRkg", // 彭老师 [wander]
                        ];
                    }

                    $subject_adminid_wx_openid_list[] = $this->t_teacher_info->get_wx_openid_by_teacherid($complained_adminid);

                    $deal_wx_openid_list = array_merge($deal_wx_openid_list,$subject_adminid_wx_openid_list);

                    $url_teacher = '';

                    $template_id_teacher  = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
                    $data_teacher['first']      = "QC投诉反馈通知! ";
                    $data_teacher['keyword1']   = $complaint_info_str;
                    $data_teacher['keyword2']   = "处理人:$deal_account  处理方案:$deal_info";
                    $data_teacher['remark']     = "";

                    foreach($deal_wx_openid_list as $item_teacher){
                        \App\Helper\Utils::send_teacher_msg_for_wx($item_teacher,$template_id_teacher, $data_teacher,$url_teacher);
                    }

                    $deal_wx_openid_list = [
                        "orwGAswxkjf1agdPpFYmZxSwYJsI", // coco 老师 [张科]
                        // "orwGAs1H3MQBeo0rFln3IGk4eGO8"  // sunny
                    ];

                }
            }


            //反馈QC与上级领导

            /**
               tK_q5C8q1Iqp7qY2KXKuRQ6-jvlj59Kc8ddB4chIstI
               反馈投诉结果通知
               {{first.DATA}}
               反馈者：{{keyword1.DATA}}
               反馈类型：{{keyword2.DATA}}
               反馈时间：{{keyword3.DATA}}
               问题描述：{{keyword4.DATA}}
               处理结果：{{keyword5.DATA}}
               {{remark.DATA}}
            **/

            $template_id = "tK_q5C8q1Iqp7qY2KXKuRQ6-jvlj59Kc8ddB4chIstI";//投诉反馈通知
            $data_msg = [
                "first"     => "$first_qc",
                "keyword1"  => "$first_nick ",
                "keyword2"  => "$complaint_type_str",
                "keyword3"  => "$deal_time_date",
                "keyword4"  => "$complaint_info_str",
                "keyword5"  => "处理人:$deal_account  处理方案:$deal_info",
            ];
            $url = 'http://admin.leo1v1.com/user_manage/complaint_department_deal_teacher/';
            $wx=new \App\Helper\Wx();
            $qc_openid_arr = [
                "orwGAswflHkLg-4PgNuJwsQZZKFE", // 沈玉莹
                "orwGAs4dM5Z-nc2VKAnG1oP0VfuQ", //谢汝毅
                "orwGAs08kfcXpQ4HZxeNV7_UqyBE", //班洁
                "orwGAs4HRuV3DIrMqWLazE0WKStY", //王皎嵘
                "orwGAs0oIUoS4fyZ2rUnMCaRro4Y",//王洪艳
                "orwGAs1KiLCE4Gdp4IZ07_6lCjpU", //童宇周
                "orwGAs16c87dXRwE5b5vE9N6zZCk", // 孙佳旭
                "orwGAs5S7U5N-FDA9ydoTpofkpCU", // 郑璞
                "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                "orwGAsyyvy1YzV0E3mmq7gBB3rms", // QC 李珉劼
                "orwGAs2Cq6JQKTqZghzcv3tUE5dU", // 王浩鸣
                "orwGAs4-nyzZL2rAdqT2o63GvxG0", // 郭冀江
                "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
                "orwGAs0ayobuEtO1YZZhW3Yed2To", // 夏宏东
                "orwGAs9GLgIN85K4nViZZ-MH5ZM8", //haku
                "orwGAs3JTSM8qO0Yn0e9HrI9GCUI", // 付玉文[shaun]
                "orwGAs1H3MQBeo0rFln3IGk4eGO8",  // sunny
                "orwGAs87gepYCYKpau66viHluRGI",  // 傅文莉
                // "orwGAs6J8tzBAO3mSKez8SX-DWq4"   // 孙瞿
            ];

            $qc_openid_arr = array_merge($qc_openid_arr,$deal_wx_openid_list);

            foreach($qc_openid_arr as $qc_item){
                $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
            }

            // 投诉相关的分配人和处理人都收到相关的推送

            $notice_wx_openid   = [];
            $notice_wx_openid[] = $this->t_manager_info->get_wx_openid_by_account($deal_account);
            $director_wx_list   = $this->t_complaint_assign_info->get_director_wx_openid($complaint_id);

            foreach($director_wx_list as $item){
                $notice_wx_openid[] = $item['wx_openid'];
            }

            $notice_wx_openid   = array_flip(array_flip($notice_wx_openid));


            foreach($notice_wx_openid as $wx_item){
                if(!in_array($wx_item,$qc_openid_arr)){// 避免qc重复推送
                    $ret_director = $wx->send_template_msg($wx_item,$template_id,$data_msg ,$url);
                }

                // if($wx_item !='orwGAswyJC8JUxMxOVo35um7dE8M'){ // 避免qc重复推送
                //     $ret_director = $wx->send_template_msg($wx_item,$template_id,$data_msg ,$url);
                // }
            }
        }
        return $this->output_succ();
    }


    public function reject_complaint(){
        $complaint_id    =  $this->get_in_int_val('complaint_id');
        $assign_adminid  =  $this->get_in_int_val('assign_adminid');
        $accept_adminid  =  $this->get_in_int_val('accept_adminid');

        $re = $this->t_complaint_assign_info->deal_reject($complaint_id,$assign_adminid,$accept_adminid);


        if($re){
            return $this->output_succ();
        } else {
            return $this->output_err();
        }

    }


    public function get_test_require_teacher_info(){
        $id    =  $this->get_in_int_val('id');
        $teacher_info = $this->t_test_lesson_require_teacher_list->get_teacher_info($id);
        $teacher_info = trim($teacher_info,",");
        $arr= explode(",",$teacher_info);
        $data=[];
        foreach($arr as $val){
            $name = $this->t_teacher_info->get_realname($val);
            $data[] = ["teacherid"=>$val,"realname"=>$name];
        }
        return $this->output_succ(["data"=>$data]);
    }

    public function set_test_require_teacher_info(){
        $id = $this-> get_in_int_val('id');
        $teacherid_list = \App\Helper\Utils::json_decode_as_int_array( $this->get_in_str_val("teacherid_list"));
        $teacher_info = ",".implode(",",$teacherid_list).",";
        $this->t_test_lesson_require_teacher_list->field_update_list($id,[
            "teacher_info" => $teacher_info
        ] );
        return $this->output_succ();
    }

    public function add_refund_complaint(){
        $userid               = $this->get_account_id();
        $account_type         = 3;// QC
        $complaint_type       = 4; // QC投诉
        $complaint_info       = $this->get_in_str_val('complaint_info');
        $complained_adminid   = $this->get_in_int_val('complained_adminid');
        $complained_adminid_type = $this->get_in_int_val('complained_adminid_type');
        $complained_adminid_nick = $this->get_in_str_val('complained_adminid_nick');

        $punish_style = $this->get_in_int_val('punish_style',0);
        $orderid      = $this->get_in_int_val('order_id',0);
        $apply_time   = $this->get_in_str_val('apply_time','');


        $ret = $this->t_complaint_info->row_insert([
            'userid'             => $userid,
            'complaint_info'     => $complaint_info,
            'complained_adminid' => $complained_adminid,
            'add_time'           => time(NULL),
            'account_type'       => $account_type,
            'complaint_type'     => $complaint_type,
            'complained_adminid_type' => $complained_adminid_type,
            'complained_adminid_nick' => $complained_adminid_nick,
            'punish_style'  => $punish_style,
            'orderid'       => $orderid,
            'apply_time'    => $apply_time
        ]);


        if($ret){

            if($complained_adminid_type == 5){
                // 通知QC处理
                $log_time_date = date('Y-m-d H:i:s',time(NULL));
                // $opt_nick= $this->cache_get_teacher_nick($complained_adminid);

                $subject = $this->t_teacher_info->get_subject($complained_adminid);

                $subject_adminid_wx_openid_list = [];

                if($subject == 1){ // 语文[千千 melody]
                    $subject_adminid_wx_openid_list = [
                        "oJ_4fxGUveIS0n2PxdmaTN2nT4j8", // 千千
                        "oJ_4fxJqMn0pH4XQfgXYiFb_1_Iw"  // melody
                    ];
                }else if($subject == 3) { //[英语]
                    $subject_adminid_wx_openid_list = [
                        "oJ_4fxGUveIS0n2PxdmaTN2nT4j8", // 千千
                    ];
                }else if($subject == 5){ //[物理]
                    $subject_adminid_wx_openid_list = [
                        "oJ_4fxOo38y6hEisvFoSxN4T1nBs", // 李红涛
                    ];
                }else if($subject == 4){ //[化学]
                    $subject_adminid_wx_openid_list = [
                        "oJ_4fxME6lCpNAMwtEhMcpYo5N7c", // 展慧东
                    ];
                }else if($subject == 2){ // 数学
                    $subject_adminid_wx_openid_list = [
                        "oJ_4fxFE0-MHPkT-vstzEDzAfRkg", // 彭老师 [wander]
                    ];
                }

                $subject_adminid_wx_openid_list[] = $this->t_teacher_info->get_wx_openid_by_teacherid($complained_adminid);




                /**
                   rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o


                   {{first.DATA}}
                   待办主题：{{keyword1.DATA}}
                   待办内容：{{keyword2.DATA}}
                   日期：{{keyword3.DATA}}
                   {{remark.DATA}}

                 **/
                $account_nick = $this->get_account();

                $template_id_teacher = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                $data_teacher_msg = [
                    'first' => "$account_nick 发布了一条退费投诉",
                    'keyword1' => "QC退费投诉",
                    'keyword2' => "QC投诉内容:$complaint_info",
                    "keyword3"  => "QC投诉时间 $log_time_date ",
                ];

                $url_teacher = '';

                foreach($subject_adminid_wx_openid_list as $item_teacher){
                    \App\Helper\Utils::send_teacher_msg_for_wx($item_teacher,$template_id_teacher, $data_teacher_msg,$url_teacher);
                }






                /**
                   {{first.DATA}}
                   待办主题：{{keyword1.DATA}}
                   待办内容：{{keyword2.DATA}}
                   日期：{{keyword3.DATA}}
                   {{remark.DATA}}
                **/

                // coco 与 sunny
                $account_nick = $this->get_account();
                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
                $data_msg = [
                    "first"     => "$account_nick 发布了一条退费投诉",
                    "keyword1"  => "QC退费投诉",
                    "keyword2"  => "QC投诉内容:$complaint_info",
                    "keyword3"  => "QC投诉时间 $log_time_date ",
                ];
                $url = 'http://admin.leo1v1.com/user_manage/qc_complaint/';
                $wx=new \App\Helper\Wx();

                $wx_openid_arr = [
                    // "orwGAs_IqKFcTuZcU1xwuEtV3Kek" ,//james
                    // "orwGAswyJC8JUxMxOVo35um7dE8M", // QC wenbin
                    // "orwGAsyyvy1YzV0E3mmq7gBB3rms", // QC 李珉劼
                    "orwGAs0ayobuEtO1YZZhW3Yed2To",  // rolon
                    "orwGAs4FNcSqkhobLn9hukmhIJDs",  // ted or erick
                    "orwGAs1H3MQBeo0rFln3IGk4eGO8",  // sunny
                    "orwGAswxkjf1agdPpFYmZxSwYJsI" // coco 老师 [张科]
                ];
                // $qc_openid_arr
                $wx_openid_list = array_merge($wx_openid_arr,$subject_adminid_wx_openid_list);

                foreach($wx_openid_list as $qc_item){
                    $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
                }
            }


            return $this->output_succ();
        }else{
            return $this->output_err();
        }
    }


    public function get_assign_log(){
        $complaint_id = $this->get_in_int_val('complaint_id');

        $ass_info = $this->t_complaint_assign_info->get_ass_log($complaint_id);

        foreach($ass_info as $key=>&$item){
            $item['ass_date'] = \App\Helper\Utils::unixtime2date($item['assign_time'],'Y-m-d H:i');
            $item['assign_str'] = $this->t_manager_info->get_ass_master_nick($item['assign_adminid']);
            $item['accept_str'] = $this->t_manager_info->get_ass_master_nick($item['accept_adminid']);
        }


        return $this->output_succ(['data'=>$ass_info]);
    }



    public function get_group_admin_name(){
        $ret = $this->t_admin_group_name->get_group_admin_name();

        foreach($ret as &$item){
            E\Eaccount_role::set_item_value_str($item);

        }

        // 将QC与sunny增加到分配列表
        $ret[] = [
            "account_role" => "0",
            "up_groupid" => "0",
            "master_adminid" => "540",
            "group_name" => "QC",
            "account" => "施文斌",
            "account_role_str" => "市场",
        ];


        $ret[] = [
            "account_role" => "0",
            "up_groupid" => "0",
            "master_adminid" => "968",
            "group_name" => "QC",
            "account" => "李珉劼",
            "account_role_str" => "市场-QC",
        ];


        $ret[] = [
            "account_role" => "0",
            "up_groupid" => "0",
            "master_adminid" => "1024",
            "group_name" => "QC",
            "account" => "王浩鸣",
            "account_role_str" => "市场-QC",
        ];



        $ret[] = [
            "account_role" => "0",
            "up_groupid" => "0",
            "master_adminid" => "895",
            "group_name" => "老师薪资及反馈",
            "account" => "sunny",
            "account_role_str" => "老师反馈处理",
        ];

        return $this->output_succ(['list'=>$ret]);
    }

    public function del_complaint(){
        $complaint_id = $this->get_in_int_val('complaint_id');
        $ret = $this->t_complaint_info->row_delete($complaint_id);
        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err();
        }

    }


    public function get_reject_log(){
        $orderid = $this->get_in_int_val('orderid');
        $reject_info = $this->t_student_cc_to_cr->get_reject_log_by_orderid($orderid);


        if($reject_info){
            foreach($reject_info as $key=>&$item){
                $item['reject_date'] = \App\Helper\Utils::unixtime2date($item['reject_time'],'Y-m-d H:i');
                $item['ass_id_str'] = $this->t_manager_info->get_ass_master_nick($item['ass_id']);
            }
        }else{
            $reject_info = [];
        }

        return $this->output_succ(['data'=>$reject_info]);
    }

    public function build_contract(){

        $orderid           = $this->get_in_int_val('orderid');
        $lesson_weeks      = $this->get_in_int_val('lesson_weeks');
        $lesson_duration   = $this->get_in_int_val('lesson_duration');
        $receive_addr   = $this->get_in_str_val('receive_addr');
        $addressee      = $this->get_in_str_val('addressee');
        $receive_phone  = $this->get_in_str_val('receive_phone');
        $is_submit      = $this->get_in_int_val('is_submit');
        //field_update_list
        $applicant = $this->get_account_id();
        $app_time  = 0;
        $parent_name = $this->get_in_str_val("parent_name");

        if($is_submit == 1){
            $app_time  = time(NULL);
        }
        $ret = $this->t_order_info->field_update_list($orderid,[
            'lesson_weeks'     => $lesson_weeks,
            'lesson_duration'  => $lesson_duration,
            'receive_addr'     => $receive_addr,
            'addressee'        => $addressee,
            'receive_phone'    => $receive_phone,
            'applicant'        => $applicant,
            'app_time'         => $app_time,
        ]);
        if ($parent_name) {
            $userid=$this->t_order_info->get_userid($orderid);
            $this->t_student_info->field_update_list($userid, [
                "parent_name" => $parent_name,
            ]);
        }
        return $this->output_succ();

    }


    public function write_contract_mail(){
        $orderid         = $this->get_in_int_val('orderid');
        $main_send_admin = $this->get_in_str_val('main_send_admin');
        $mail_send_time  = strtotime($this->get_in_str_val('mail_send_time',0));
        $mail_code       = $this->get_in_str_val('mail_code');
        $is_send_flag    = $this->get_in_int_val('is_send_flag');
        $mail_code_url   = $this->get_in_str_val('mail_code_url','0');


        $ret = $this->t_order_info->field_update_list($orderid,[
            'main_send_admin'  => $main_send_admin,
            'mail_send_time'   => $mail_send_time,
            'mail_code'        => $mail_code,
            'mail_code_url'    => $mail_code_url,
            'is_send_flag'     => $is_send_flag
        ]);

        return $this->output_succ();
    }


    public function get_contract_info(){

        $orderid = $this->get_in_int_val('orderid');
        $applicant = $this->t_order_info->get_app_by_orderid($orderid);
        if($applicant>0){
            $student_info = $this->t_order_info->get_contract_pdf_by_orderid($orderid);
            $student_info['app_time_str'] = \App\Helper\Utils::unixtime2date(
                $student_info["app_time"],'Y-m-d H:i');

        }else{
            $student_info = $this->t_order_info->get_student_info_by_orderid($orderid);
        }
        return $this->output_succ(['data'=>$student_info]);
    }

    public function get_contract_mail(){
        $orderid = $this->get_in_int_val('orderid');
        $contract_mail_info = $this->t_order_info->get_contract_mail_by_orderid($orderid);
        if($contract_mail_info){
            $contract_mail_info['mail_send_time_str'] = \App\Helper\Utils::unixtime2date(
                $contract_mail_info["mail_send_time"],'Y-m-d H:i');

        }
        return $this->output_succ(['data'=>$contract_mail_info]);

    }

    public function get_lesson_time(){
        $lessonid = $this->get_in_int_val('lessonid');
        $lesson_time_arr = $this->t_lesson_info_b2->get_lesson_time($lessonid);

        $lesson_arr['lesson_start'] = date('Y-m-d H:i:s',$lesson_time_arr[0]['lesson_start']);
        $lesson_arr['lesson_end']   = date('Y-m-d H:i:s',$lesson_time_arr[0]['lesson_end']);
        return $this->output_succ(['data'=>$lesson_arr]);
    }

    /**
     * @author adrian
     * 修改课程时间会影响老师工资等信息，2018年01月19日15:42:11 之后无法通过此借口修改课程时间
     * 此接口之前是教务用来修改试听课的课程时间
     */
    public function set_lesson_time(){
        return $this->output_err("无法修改课程时间");
        // $lessonid     = $this->get_in_int_val('lessonid');
        // $lesson_start = strtotime($this->get_in_str_val('lesson_start'));
        // $lesson_end   = strtotime($this->get_in_str_val('lesson_end'));
        // $account_id   = $this->get_account_id();

        // // 做身份判断 [只有教务可以更改时间]
        // $jiaowu_adminid = $this->t_test_lesson_subject_sub_list->get_set_lesson_adminid($lessonid);
        // $root_arr = ['60','188','68','186','349','684','831','944'];

        // if($jiaowu_adminid != $account_id ||  !in_array($account_id,$root_arr)){
        //     return $this->output_err('您没有权限修改时间!');
        // }

        // $lesson_old_start = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        // $lesson_old_end   = $this->t_lesson_info_b2->get_lesson_end($lessonid);
        // $old_lesson_count = $this->t_lesson_info_b2->get_lesson_count($lessonid);

        // //检查家长是否发起调课申请
        // $is_has_apply = $this->t_lesson_time_modify->get_parent_deal_time($lessonid);
        // if(!$is_has_apply){
        //     return $this->output_err("该家长没有申请调课时!");
        // }

        // $ret1 = $this->t_lesson_time_modify->field_update_list($lessonid,[
        //     'deal_jiaowu'         => $account_id,
        //     'deal_jiaowu_time'    => time(NULL),
        //     'is_modify_time_flag' => 1,
        //     'original_time'       => "$lesson_old_start,$lesson_old_end"
        // ]);

        // $lesson_count = \App\Helper\Utils::get_lesson_count($lesson_start, $lesson_end);
        // $ret2 = $this->t_lesson_info_b2->field_update_list($lessonid,[
        //     'lesson_start' => $lesson_start,
        //     'lesson_end'   => $lesson_end,
        //     'lesson_count' => $lesson_count,
        // ]);
        // if($ret2){
        //     $this->add_lesson_count_operate_info($lessonid, $old_lesson_count, $lesson_count);
        //     $this->add_lesson_time_operate_info($lessonid, $lesson_old_start, $lesson_start,$lesson_old_end,$lesson_end);
        // }

        // if($ret1 && $ret2){
        //     return $this->output_succ();
        // }else{
        //     return $this->output_err('提交失败,请稍后重试!');
        // }
    }

    public function get_test_lesson_confirm_info(){

        $lessonid     = $this->get_in_int_val('lessonid');
        $data = $this->t_test_lesson_subject_sub_list->field_get_list($lessonid,"confirm_adminid,confirm_time,success_flag,fail_greater_4_hour_flag,test_lesson_fail_flag,fail_reason,ass_test_lesson_order_fail_flag,ass_test_lesson_order_fail_desc,ass_test_lesson_order_fail_set_time,ass_test_lesson_order_fail_set_adminid,order_confirm_flag");
        $data["confirm_adminid_account"] = $this->t_manager_info->get_account($data["confirm_adminid"]);
        $data["fail_set_adminid_account"] = $this->t_manager_info->get_account($data["ass_test_lesson_order_fail_set_adminid"]);
        $data["confirm_time_str"] = date("Y-m-d H:i:s",$data["confirm_time"]);
        $data["ass_test_lesson_order_fail_set_time_str"] = date("Y-m-d H:i:s",$data["ass_test_lesson_order_fail_set_time"]);
        $data["ass_test_lesson_order_fail_flag_str"]= E\Eass_test_lesson_order_fail_flag::get_desc($data["ass_test_lesson_order_fail_flag"]);
        $data["test_lesson_fail_flag_str"]= E\Etest_lesson_fail_flag::get_desc($data["test_lesson_fail_flag"]);
        $data["fail_greater_4_hour_flag_str"]= E\Eboolean::get_desc($data["fail_greater_4_hour_flag"]);
        return $this->output_succ(["data"=>$data]);
    }

    /**
     * 2017-8-16至8-31号（以下订单时间为准）内下单用户且课时在90课时以上，可减300元
     * 这些用户在2017-12-31前续费，可减500元
     */
    public function get_8_month_activity($userid,$price,$lesson_total,$contract_type,$has_share_activity_flag){
        $now = time();
        $activity_start_time  = strtotime("2017-8-16");
        $activity_end_time    = strtotime("2017-9-1");
        $activity_finish_time = strtotime("2017-12-31");

        $last_lesson_start = $this->t_lesson_info_b2->get_last_trial_lesson($userid);
        $check_time = strtotime("+1 day",strtotime( date("Y-m-d 23:59",$last_lesson_start*1)));
        if($lesson_total>=9000){
            if($contract_type==0 && $check_time>time() && $has_share_activity_flag==1){
                /*
                if($now>$activity_start_time && $now<$activity_end_time){
                    $price -= 30000;
                }
                */
            }elseif($contract_type==3){
                if($now>$activity_start_time && $now<$activity_finish_time){
                    $has_normal_order=$this->t_order_info->get_order_count(
                        $userid,$activity_start_time,$activity_end_time,E\Econtract_type::V_0,1
                    );
                    if($has_normal_order==1){
                        $price -= 50000;
                    }
                }
            }
        }

        return $price;
    }

    public function get_user_change_log(){
        $adminid = $this->get_in_int_val('adminid');

        $log_info_arr = $this->t_user_group_change_log->get_user_change_log($adminid);

        $ret_info = [];

        $ret_info['add_time_formate'] = $log_info_arr['add_time']?date('Y-m-d H:i:s',$log_info_arr['add_time']):"";

        $ret_info['do_adminid_nick']  = $log_info_arr['do_adminid']?$this->cache_get_account_nick($log_info_arr['do_adminid']):"";

        $ret_info['old_group']        = $log_info_arr['old_group']?$log_info_arr['old_group']:"";

        return $this->output_succ($ret_info);
    }


    public function add_interview_remind(){
        $name = $this->get_in_str_val('name');
        $post = $this->get_in_str_val('post');
        $dept = $this->get_in_str_val('dept');
        $hr_adminid  = $this->get_account_id();
        $interviewer_id = $this->get_in_int_val('interviewer');
        $interview_time = strtotime($this->get_in_str_val('interview_time'));

        $ret = $this->t_interview_remind->row_insert([
            "name"  => $name,
            "post"  => $post,
            "dept"  => $dept,
            "interviewer_id" => $interviewer_id,
            "interview_time" => $interview_time,
            "hr_adminid" => $hr_adminid,
            "create_time" => time()
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err('添加提醒任务失败,请联系开发人员!');
        }
    }


    public function edit_interview_remind(){
        $id = $this->get_in_int_val('id');
        $name = $this->get_in_str_val('name');
        $post = $this->get_in_str_val('post');
        $dept = $this->get_in_str_val('dept');
        $hr_adminid  = $this->get_account_id();
        $interviewer_id = $this->get_in_int_val('interviewer');
        $interview_time = strtotime($this->get_in_str_val('interview_time'));

        $ret = $this->t_interview_remind->field_update_list($id,[
            "name"  => $name,
            "post"  => $post,
            "dept"  => $dept,
            "interviewer_id" => $interviewer_id,
            "interview_time" => $interview_time,
            "hr_adminid"     => $hr_adminid,
            "create_time"    => time(),
            "is_send_flag"   => 0,
            "send_msg_time"  => 0
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err('编辑任务失败,请联系开发人员!');
        }
    }


    public function interview_del(){
        $id = $this->get_in_int_val('id');

        $this->t_interview_remind->row_delete($id);

        return $this->output_succ();
    }

    public function get_violation_info(){
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $teacherid = $this->get_in_int_val('teacherid');
        $violation_info = $this->t_lesson_info_b3->get_violation_num($start_time, $end_time, $teacherid);
        return $this->output_succ(['data'=>$violation_info]);
    }

    public function addMarketExtend(){
        $gift_type = $this->get_in_int_val('gift_type');
        $title     = $this->get_in_str_val('title');
        $act_descr = $this->get_in_str_val('act_descr');
        $shareImgUrl = $this->get_in_str_val('shareImgUrl');
        $coverImgUrl = $this->get_in_str_val('coverImgUrl');
        $activityImgUrl = $this->get_in_str_val('activityImgUrl');
        $followImgUrl   = trim($this->get_in_str_val('followImgUrl'),',');
        $use_flag = $this->get_in_int_val('use_flag');
        $add_time = time();
        $uid = $this->get_account_id();

        //检测图片尺寸
        if(strlen($act_descr)>90){
            return $this->output_err('描述文字不可超出30个字!');
        }

        $coverImgUrlOnline = '';
        $activityImgUrlOnline = '';
        $followImgUrlOnline = '';
        $shareImgUrlOnline = '';
        $shareType = 0;
        $coverType = 0;
        $activityType = 0;
        $followType = 0;
        $shareWidth = 0;
        $shareHeight = 0;
        $coverWidth  = 0;
        $coverHeight = 0;
        $activityWidth = 0;
        $activityHeight = 0;
        $followWidth = 0;
        $followHeight = 0;


        $domain = config('admin')['qiniu']['public']['url'];
        if($shareImgUrl){ $shareImgUrlOnline = $domain."/".$shareImgUrl; }
        if($coverImgUrl){ $coverImgUrlOnline = $domain."/".$coverImgUrl; }
        if($activityImgUrl){ $activityImgUrlOnline = $domain."/".$activityImgUrl; }
        // if($followImgUrl){ $followImgUrlOnline = $domain."/".$followImgUrl; }


        if($followImgUrl){
            $followImgUrl_arr = explode(',',$followImgUrl);
            foreach($followImgUrl_arr as $item){
                $followImgUrlOnline = $domain."/".$item;
                list($followWidth,$followHeight,$followType,$followAttr)=getimagesize($followImgUrlOnline);

                if($followType != 3 && $followType !=0){return $this->output_err('关注页 [图片格式] 不符合,请重新上传!');}
                if(($followWidth!=750 || $followHeight<1200 || $followHeight>1340 )&&$followType!=0){ return $this->output_err('关注页 [图片尺寸] 不符合,请重新上传!');
                }
            }
        }


        if($shareImgUrlOnline){
            list($shareWidth,$shareHeight,$shareType,$shareAttr)=getimagesize($shareImgUrlOnline);
        }

        if($coverImgUrlOnline){
            list($coverWidth,$coverHeight,$coverType,$coverAttr)=getimagesize($coverImgUrlOnline);
        }

        if($activityImgUrlOnline){
            list($activityWidth,$activityHeight,$activityType,$activityAttr)=getimagesize($activityImgUrlOnline);
        }

        // if($followImgUrlOnline){
        //     list($followWidth,$followHeight,$followType,$followAttr)=getimagesize($followImgUrlOnline);
        // }



        if($shareType != 3 && $shareType !=0){return $this->output_err('分享页图片格式不符合,请重新上传!');}
        if($coverType != 3 && $coverType !=0){return $this->output_err('封面图片格式不符合,请重新上传!');}
        if($activityType != 3 && $activityType !=0){return $this->output_err('活动页图片格式不符合,请重新上传!');}
        if($followType != 3 && $followType !=0){return $this->output_err('关注页图片格式不符合,请重新上传!');}

        if(($shareWidth!=750 || $shareHeight<1200 || $shareHeight>1340 )&&$shareType!=0){ return $this->output_err('分享页图片尺寸不符合,请重新上传!'); }
        if(($coverWidth!=300 || $coverHeight!=300)&&$coverType!=0){ return $this->output_err('封面页图片尺寸不符合,请重新上传!'); }
        if(($activityWidth!=750 || $activityHeight>1340 || $activityHeight<1200 )&&$activityType!=0){ return $this->output_err('活动页图片尺寸不符合,请重新上传!'); }
        // if(($followWidth!=750 || $followHeight<1200 || $followHeight>1340 )&&$followType!=0){ return $this->output_err('关注页图片尺寸不符合,请重新上传!'); }




        $this->t_activity_usually->row_insert([
            "gift_type" => $gift_type,
            "title"     => $title,
            "add_time"  => $add_time,
            "uid"       => $uid,
            "act_descr" => $act_descr,
            "shareImgUrl" => $shareImgUrl,
            "coverImgUrl" => $coverImgUrl,
            "activityImgUrl" => $activityImgUrl,
            "followImgUrl"   => $followImgUrl,
            "use_flag"   => $use_flag
        ]);
        $id = $this->t_activity_usually->get_last_insertid();
        $share_type = $id+100;
        $url= "http://wx-parent.leo1v1.com/wx_parent_gift/marketingActivityUsually?type=".$share_type;
        $this->t_activity_usually->field_update_list($id, ['url'=>$url]);

        $this->t_web_page_info->row_insert([
            "url" =>$url,
            "title" =>$title,
            "add_time" => time(NULL),
            "add_adminid"   =>  $this->get_account_id(),
            "act_usuall_id" => $id
        ]);


        return $this->output_succ();
    }



    public function updateMarketExtend(){
        $gift_type = $this->get_in_int_val('gift_type');
        $title     = $this->get_in_str_val('title');
        $act_descr = $this->get_in_str_val('act_descr');
        $shareImgUrl = $this->get_in_str_val('shareImgUrl');
        $coverImgUrl = $this->get_in_str_val('coverImgUrl');
        $activityImgUrl = $this->get_in_str_val('activityImgUrl');
        $followImgUrl   = trim($this->get_in_str_val('followImgUrl'),',');
        $use_flag = $this->get_in_int_val('use_flag');
        $add_time = time();
        $uid = $this->get_account_id();
        $id = $this->get_in_int_val('id');

        //检测图片尺寸
        if(strlen($act_descr)>90){
            return $this->output_err('描述文字不可超出30个字!');
        }

        $coverImgUrlOnline = '';
        $activityImgUrlOnline = '';
        $followImgUrlOnline = '';
        $shareImgUrlOnline = '';
        $shareType = 0;
        $coverType = 0;
        $activityType = 0;
        $followType = 0;
        $shareWidth = 0;
        $shareHeight = 0;
        $coverWidth  = 0;
        $coverHeight = 0;
        $activityWidth = 0;
        $activityHeight = 0;
        $followWidth = 0;
        $followHeight = 0;


        $domain = config('admin')['qiniu']['public']['url'];
        if($shareImgUrl){ $shareImgUrlOnline = $domain."/".$shareImgUrl; }
        if($coverImgUrl){ $coverImgUrlOnline = $domain."/".$coverImgUrl; }
        if($activityImgUrl){ $activityImgUrlOnline = $domain."/".$activityImgUrl; }
        if($followImgUrl){
            $followImgUrl_arr = explode(',',$followImgUrl);
            foreach($followImgUrl_arr as $item){
                $followImgUrlOnline = $domain."/".$item;
                list($followWidth,$followHeight,$followType,$followAttr)=getimagesize($followImgUrlOnline);
                if($followType != 3 && $followType !=0){return $this->output_err('关注页图片格式不符合,请重新上传!');}
                if(($followWidth!=750 || $followHeight<1200 || $followHeight>1340 )&&$followType!=0){ return $this->output_err('关注页图片尺寸不符合,请重新上传!'); }
            }
        }

        if($shareImgUrlOnline){
            list($shareWidth,$shareHeight,$shareType,$shareAttr)=getimagesize($shareImgUrlOnline);
        }

        if($coverImgUrlOnline){
            list($coverWidth,$coverHeight,$coverType,$coverAttr)=getimagesize($coverImgUrlOnline);
        }

        if($activityImgUrlOnline){
            list($activityWidth,$activityHeight,$activityType,$activityAttr)=getimagesize($activityImgUrlOnline);
        }


        if($shareType != 3 && $shareType !=0){return $this->output_err('分享页图片格式不符合,请重新上传!');}
        if($coverType != 3 && $coverType !=0){return $this->output_err('封面图片格式不符合,请重新上传!');}
        if($activityType != 3 && $activityType !=0){return $this->output_err('活动页图片格式不符合,请重新上传!');}

        if(($shareWidth!=750 || $shareHeight<1200 || $shareHeight>1340 )&&$shareType!=0){ return $this->output_err('分享页图片尺寸不符合,请重新上传!'); }
        if(($coverWidth!=300 || $coverHeight!=300)&&$coverType!=0){ return $this->output_err('封面页图片尺寸不符合,请重新上传!'); }
        if(($activityWidth!=750 || $activityHeight>1340 || $activityHeight<1200 )&&$activityType!=0){ return $this->output_err('活动页图片尺寸不符合,请重新上传!'); }


        $this->t_activity_usually->field_update_list($id,[
            "gift_type" => $gift_type,
            "title"     => $title,
            "add_time"  => $add_time,
            "uid"       => $uid,
            "act_descr" => $act_descr,
            "shareImgUrl" => $shareImgUrl,
            "coverImgUrl" => $coverImgUrl,
            "activityImgUrl" => $activityImgUrl,
            "followImgUrl"   => $followImgUrl,
            "use_flag"    => $use_flag
        ]);

        $dealAccount = $this->get_account_id();
        $web_page_id = $this->t_web_page_info->updateUrlInfo($id, $title, $dealAccount);
        return $this->output_succ();
    }

    public function get_admin_info_by_id(){
        $uid = $this->get_in_int_val("adminid",-1);
        $type = $this->get_in_int_val("type",-1);
        if($type == 1){
            $ret = $this->t_assistant_info->get_assistant_detail_info_b2($uid);
            $ret['gender'] = E\Egender::get_desc($ret['gender']);
            $birth_year           = substr((string)$ret['birth'], 0, 4);
            $ret['age']    = (int)date('Y', time()) - (int)$birth_year;
            $ret['account_role'] = E\Eaccount_role::get_desc(1);
        }else{
            $ret = $this->t_manager_info->get_detail_info($uid);
            $ret['gender'] = E\Egender::get_desc($ret['gender']);
            $ret['account_role'] = E\Eaccount_role::get_desc($ret['account_role']);
        }

        return $this->output_succ(["data" => $ret ]);
    }

    public function get_assistant_info_by_id(){
        $assistantid = $this->get_in_int_val("assistantid",-1);
        $ret = $this->t_assistant_info->get_assistant_detail_info($assistantid);
        $ret['gender'] = E\Egender::get_desc($ret['gender']);
        $birth_year           = substr((string)$ret['birth'], 0, 4);
        $ret['age']    = (int)date('Y', time()) - (int)$birth_year;
        return $this->output_succ(["data" => $ret ]);
    }

    # 市场部个性海报 进入的学生数据
    public function getMarkePostertData(){
        $uid = $this->get_in_int_val('uid');
        $ret_info = $this->t_poster_share_log->getStuListData($uid);
        return $this->output_succ(['data'=>$ret_info]);
    }
}
