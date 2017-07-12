<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

/*
3. 修改字段的注释，代码如下：

ALTER TABLE `student` MODIFY COLUMN `id` COMMENT '学号';

查看字段的信息，代码如下：

SHOW FULL COLUMNS  FROM `student`;
*/

class seller_api extends Controller
{
    use CacheNick;
    public  function get_top_list( $notify_count_str) {
        $c=new seller_student_new();
        /*
tmk_new_no_call_count,    "微信-新分配例子未回访数", "btn-danger"
new_not_call_count,    "新分配例子未回访数"
not_call_count,    "例子未回访数"
next_revisit_count,    "需再次回访数"
today,  "今天上课须通知数"
tomorrow, "明天上课须通知数"
return_back_count, "被驳回未处理的个数"
require_count,"已预约未排数"
no_confirm_count,"课程未确认数"
        */
        $ret_info=["list"=>[]];

        $this->set_in_value("group_seller_student_status", -1 );
        if ($notify_count_str== "tmk_new_no_call_count") {
            $this->set_in_value("seller_student_status", E\Eseller_student_status::V_0 );
            $this->set_in_date_range(4,0,date("Y-m-d",time()-60*86400),0);
            $this->set_in_value("tmk_student_status", E\Etmk_student_status::V_3 );
            $ret_info=$c->seller_student_list_data();
        }elseif($notify_count_str== "not_call_count"){
            //$this->set_in_date_rage($key,$value);
            $this->set_in_value("seller_student_status", E\Eseller_student_status::V_0 );
            $this->set_in_date_range(4,0,date("Y-m-d",time()-60*86400),0);
            $ret_info=$c->seller_student_list_data();
        }

        return $ret_info["list"];
    }

    public function get_seller_student_list() {
        $this->set_in_value("page_count",10000);
        $this->set_in_value("require_admin_type",2);
        $c=new seller_student_new();
        $ret_info=$c->seller_student_list_data();
        $count_info = $ret_info["count_info"];
        // dd($ret_info);

        //get top
        $notify_count_str=$this->get_in_str_val("notify_count_str");
        $top_list=$this->get_top_list($notify_count_str);
        $id_map=[];
        foreach ($top_list as &$item ) {
            $id_map[ $item["test_lesson_subject_id"]]=true;
            $item["top_flag"]=1;

        }

        foreach ($ret_info["list"] as $ritem ) {
            if (!@$id_map[ $ritem["test_lesson_subject_id"]])  { //不存在
                $ritem["top_flag"]=0;
                $top_list[]= $ritem;
            }

        }
        foreach ($top_list as &$item ) {
            $item["stu_test_paper_flag_str"]=\App\Helper\Common::get_test_pager_boolean_color_str_new(
                $item["stu_test_paper"],
                $item['tea_download_paper_time']);
        }


        $list = \App\Helper\Utils::list_to_page_info($top_list);
        $list["count_info"] = $count_info;
        //dd($list);
        return $this->output_api_ret_info($list);
    }

    public function set_test_lesson_user_to_self(){
        $c=new ss_deal();
        return $c->set_test_lesson_user_to_self();
    }
    public function get_login_jump_key() {

        $adminid=$this->get_account_id();
        $time_now=time(NULL);
        $arr=[$adminid, $time_now];

        $login_jump_key= \App\Helper\Utils::encode_str(json_encode($arr) );
        return $this->output_succ([
            "adminid" => $adminid ,
            "account" =>  $this->get_account(),
            "login_jump_key" =>$login_jump_key,
        ]);

    }
    public function ss_deal_get_user_info() {
        $userid= $this->get_in_userid();
        //$userid = 130633 ;
        $ret_info = $this->t_student_info->get_user_info_for_api($userid);
        if($ret_info["next_revisit_time"]>0){
            $ret_info["next_revisit_time"] =  date("Y-m-d H:i",$ret_info["next_revisit_time"]);
        }

        //\App\Helper\Utils::unixtime2date_for_item($ret_info,"next_revisit_time");
        E\Epad_type::set_item_value_str($ret_info, "has_pad");
        if(!$ret_info["xingetedian"]){
            $ret_info["xingetedian"] = $ret_info["stu_character_info"];
            unset($ret_info["stu_character_info"]);
        }
        if(isset($ret_info["lesson_count_left"]) && isset($ret_info["lesson_count_all"]) && !empty($ret_info["lesson_count_all"])){
            $ret_info["lesson_count_left"] = ($ret_info["lesson_count_left"]/100)."/".($ret_info["lesson_count_all"]/100);
        }
        if(isset($ret_info["birth"]) && !empty($ret_info["birth"])){
            $ret_info["birth"] = isset($ret_info["birth"])?substr($ret_info["birth"],0,4).".".substr($ret_info["birth"],4,2).".".substr($ret_info["birth"],6,2):"";
        }
        if(isset($ret_info["stu_test_paper"]) && isset($ret_info['tea_download_paper_time'])){
            $ret_info["stu_test_paper_str"] = \App\Helper\Common::get_test_pager_boolean_color_str_new(
                $ret_info["stu_test_paper"], $ret_info['tea_download_paper_time']
            );
            $ret_info["paper_url"] = \App\Helper\Common::get_url_ex($ret_info["stu_test_paper"]);
        }
        // $parent_info_list = $this->t_parent_child->get_stu_parent_info_list($userid);
        $course_list = $this->t_test_lesson_subject->get_user_course_list($userid);
        $adminid = $this->t_seller_student_new->get_admin_revisiterid($userid);
        $admin_info= $this->t_manager_info->field_get_list($adminid,"name,phone,email");
        $teacher_info_list=[];
        $tea_arr = $this->t_course_order->get_ass_tea_info($userid);
        $assistant_info = $this->t_assistant_info->field_get_list($tea_arr["assistantid"],"nick,phone,email");
        $tea_info = $this->t_teacher_info->field_get_list($tea_arr["teacherid"],"realname,phone,email");
        $par_arr=[];
        $cou_arr=[];
        $teacher_arr=[];
        if(!empty($admin_info)){
            $par_arr["teacher_type"]="咨询师";
            $par_arr["nick"]=$admin_info["name"];
            $par_arr["phone"]=$admin_info["phone"];
            $par_arr["email"]=$admin_info["email"];
            $teacher_info_list[]=$par_arr;
         }
        if(!empty($assistant_info)){
            $cou_arr["teacher_type"]="助教老师";
            $cou_arr["nick"]=$assistant_info["nick"];
            $cou_arr["phone"]=$assistant_info["phone"];
            $cou_arr["email"]=$assistant_info["email"];
            $teacher_info_list[]=$cou_arr;
        }
        if(!empty($tea_info)){
            $teacher_arr["teacher_type"]="辅导老师";
            $teacher_arr["nick"]=$tea_info["realname"];
            $teacher_arr["phone"]=$tea_info["phone"];
            $teacher_arr["email"]=$tea_info["email"];
            $teacher_info_list[]=$teacher_arr;
        }
        return $this->output_succ([
            "stu_list"=>$ret_info,
            "course_list"=>$course_list,
            "teacher_info_list"=>$teacher_info_list
        ]);

    }


    public function save_seller_student_info(){
        $userid= $this->get_in_userid();
        $realname = $this->get_in_str_val("realname");
        $stu_nick = $this->get_in_str_val("stu_nick");
        $birth = $this->get_in_str_val("birth");
        if(!empty($birth)) $birth = substr($birth,0,4).substr($birth,5,2).substr($birth,8,2);
        $school = $this->get_in_str_val("school");
        $gender = $this->get_in_int_val("gender");
        $xingetedian = $this->get_in_str_val("xingetedian");
        $grade = $this->get_in_int_val("grade");
        $aihao = $this->get_in_str_val("aihao");
        $yeyuanpai = $this->get_in_str_val("yeyuanpai");
        $address = $this->get_in_str_val("address");
        $subject = $this->get_in_int_val("subject");
        $textbook = $this->get_in_str_val("textbook");
        $parent_name      = $this->get_in_str_val("parent_name");
        $has_pad       = $this->get_in_int_val("has_pad");
        $next_revisit_time     = strtotime($this->get_in_str_val("next_revisit_time"));
        $user_desc     = $this->get_in_str_val("user_desc");
        // $stu_test_ipad_flag    = $this->get_in_str_val("stu_test_ipad_flag");
        $stu_score_info        = $this->get_in_str_val("stu_score_info");

        //$parent_info_list = $this->get_in_str_val("parent_info_list");
        //$parent_info_list = json_decode($parent_info_list,true);
        //return $parent_info_list;
        // \App\Helper\Utils::logger("result:$parent_info_list"  );
        $db_grade=$this->t_student_info->get_grade($userid);
        if ($db_grade!= $grade) {
            if($this->t_order_info->has_1v1_order($userid)) {
                return $this->output_err("有合同了,不能修改年级");
            }
        }


        $this->t_student_info->field_update_list($userid,[
            "realname"  =>$realname,
            "nick"      =>$stu_nick,
            "birth"     =>$birth,
            "school"    =>$school,
            "gender"    =>$gender,
            "grade"     =>$grade,
            "address"   =>$address,
            "parent_name" => $parent_name,
        ]);
        $this->t_seller_student_new->field_update_list($userid,[
            "stu_character_info"=>$xingetedian,
            "has_pad" =>$has_pad,
            "next_revisit_time" =>$next_revisit_time,
            // "stu_test_ipad_flag" =>$stu_test_ipad_flag,
            "stu_score_info" =>$stu_score_info,
            "user_desc"    =>$user_desc
        ]);
        $this->t_student_init_info->field_update_list($userid,[
            "xingetedian"=>$xingetedian,
            "aihao"       =>$aihao,
            "yeyuanpai"   =>$yeyuanpai
        ]);
        $this->t_test_lesson_subject->update_subject_and_textbook($userid,$subject,$textbook);
        /*if(!empty($parent_info_list)){
            $parent_info_list = json_decode($parent_info_list,true);
            foreach($parent_info_list["parent_info_list"] as $item){
                $parent_type = $item["parent_type"];
                $parentid = $this->t_parent_child->check_parent_exists_info($parent_type,$userid);
                if($parentid >0 ){
                    $this->t_parent_info->field_update_list($parentid,[
                        "nick" =>$item["parent_name"],
                        "phone" =>$item["parent_phone"],
                        "email"  =>$item["parent_email"]
                    ]);
                }else{
                    return $this->output_err("家长手机必须先注册!");
                }
            }
            }*/
        /*  $par_name      = $this->get_in_str_val("par_name");
        $has_pad       = $this->get_in_int_val("has_pad");
        $next_revisit_time     = $this->get_in_str_val("next_revisit_time");
        $stu_test_ipad_flag    = $this->get_in_str_val("stu_test_ipad_flag");
        $stu_score_info        = $this->get_in_str_val("stu_score_info");




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


        //last_revisit_msg ='%s', last_revisit_time =%u
        $ss_arr=[
            "has_pad" =>$has_pad,
            "user_desc" =>$user_desc,
            "next_revisit_time" =>$next_revisit_time,
            "stu_test_ipad_flag" =>$stu_test_ipad_flag,
            "stu_score_info" =>$stu_score_info,
            "stu_character_info" =>$stu_character_info,
        ];

        //更新首次回访时间
        if (!$this->t_seller_student_new->get_first_revisit_time($userid))  {
            $ss_arr["first_revisit_time"]=time(NULL);
        }
        if ( $revisite_info  ) {
            $ss_arr["last_revisit_time"]=time(NULL);
            $ss_arr["last_revisit_msg"]=$revisite_info;
             $this->t_book_revisit->add_book_revisit($phone , $revisite_info, $this->get_account());
        }

        $this->t_seller_student_new->field_update_list($userid,$ss_arr);


        $tt_arr=[
            "subject" =>$subject,
            "stu_request_test_lesson_time" =>$stu_request_test_lesson_time,
            "stu_request_test_lesson_time_info" =>$stu_request_test_lesson_time_info,
            "stu_request_lesson_time_info" =>$stu_request_lesson_time_info,
            "stu_request_test_lesson_demand" =>$stu_request_test_lesson_demand,
            "stu_test_lesson_level" =>$stu_test_lesson_level,
            "seller_student_sub_status" => $seller_student_sub_status,
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
            }*/

        return $this->output_succ();
    }

    public function get_student_revisit_list(){
        $page_num = $this->get_in_page_num();
        $userid= $this->get_in_userid();
        $phone = $this->t_student_info->get_phone($userid);
        $revisit_list = $this->t_book_revisit->get_rev_info_by_phone_adminid_new($phone,$page_num);
        foreach($revisit_list["list"] as &$item){
            $item["revisit_time"] = date("Y.m.d H:i:s",$item["revisit_time"]);
        }
        return $this->output_api_ret_info($revisit_list);
    }
    public function save_student_revisit_info(){
        $userid= $this->get_in_userid();
        $phone = $this->t_student_info->get_phone($userid);
        $operator_note = trim($this->get_in_str_val("operator_note"));
        $sys_operator = $this->get_in_str_val("sys_operator");
        $this->t_book_revisit->row_insert([
            "phone"=>$phone,
            "revisit_time" =>time(),
            "operator_note"=>$operator_note,
            "sys_operator"=>$sys_operator
        ]);

        return $this->output_succ();
    }

    public function get_stu_test_lesson_info(){
        $userid= $this->get_in_userid();
        //$userid = 60012;
        $list = $this->t_lesson_info->get_stu_test_lesson_info_for_api($userid);
        foreach($list as &$item){
            $item["time"] = date("Y.m.d H:i:s",$item["lesson_start"]);
            $item["lesson_type"] = "试听课";
            if($item["success_flag"]==2){
                $item["class_record"]="已取消";
            }else{
                if($item["lesson_status"]==2){
                    $item["class_record"]="已完成";
                }else{
                    $item["class_record"]="未开课";
                }

            }
        }
         return $this->output_succ([
             "list" =>$list
         ]);
    }

    public function get_free_seller_student_list_for_grab(){
        $c=new seller_student_new();
        $ret_info=$c->get_free_seller_list_data();
        if(!empty($ret_info["list"])){
            foreach($ret_info["list"] as &$item){
                $item["stu_nick"] = $this->t_student_info->get_nick($item["userid"]);
            }
        }

        foreach($ret_info["list"] as &$item){
            $item["stu_nick"] = $this->t_student_info->get_nick($item["userid"]);
        }
        return $this->output_api_ret_info($ret_info);
    }

    public function get_new_seller_student_list_for_grab(){
        $c=new seller_student_new();
        $ret_arr=$c->get_new_list_data();
        $list = $ret_arr["ret_info"]["list"];
        $max_day_count= $ret_arr["max_day_count"];
        $cur_count =  $ret_arr["cur_count"] ;
        $left_count = $ret_arr["left_count"];
        $errors =  $ret_arr["errors"] ;
        if(!empty($list)){
            foreach($list as &$item){
                $item["stu_nick"] = $this->t_student_info->get_nick($item["userid"]);
            }
        }

        return $this->output_succ([
            "list" =>$list,
            "max_day_count"=>$max_day_count,
            "left_count"=>$left_count,
            "errors"=>$errors
        ]);
    }

    public function get_no_order_seller_student_list_for_grab(){
        $c=new seller_student_new();
        $ret_arr = $c->test_lesson_no_order_list_data();
        $list = $ret_arr["ret_info"]["list"];
        $seller_level_str = $ret_arr["seller_level_str"];
        $opt_count = $ret_arr["opt_count"];
        $last_count = $ret_arr["last_count"];
        if(!empty($list)){
            foreach($list as &$item){
                $item["stu_nick"] = $this->t_student_info->get_nick($item["userid"]);
            }
        }
        return $this->output_succ([
            "list" =>$list,
            // "seller_level_str"=>$seller_level_str,
            "opt_count"=>$opt_count,
            "last_count"=>$last_count
        ]);

    }

    public function get_student_init_info(){
        $userid= $this->get_in_userid();
        $ret_info = $this->t_student_info->get_user_init_info_for_api($userid);
        if($ret_info["next_revisit_time"]>0){
            $ret_info["next_revisit_time"] =  date("Y-m-d H:i",$ret_info["next_revisit_time"]);
        }

        if(!empty($ret_info)){
            $this->change_and_unset_field($ret_info,"xingetedian","stu_character_info");
            $this->change_and_unset_field($ret_info,"grade","s_grade");
            $this->change_and_unset_field($ret_info,"realname","s_realname");
            $this->change_and_unset_field($ret_info,"gender","s_gender");
            $this->change_and_unset_field($ret_info,"address","s_address");
            $this->change_and_unset_field($ret_info,"phone","s_phone");
            $this->change_and_unset_field($ret_info,"birth","s_birth");
            $this->change_and_unset_field($ret_info,"school","s_school");
        }
        if(isset($ret_info["lesson_count_left"]) && isset($ret_info["lesson_count_all"]) && !empty($ret_info["lesson_count_all"])){
            $ret_info["lesson_count_left"] = ($ret_info["lesson_count_left"]/100)."/".($ret_info["lesson_count_all"]/100);
        }
        if(isset($ret_info["birth"]) && !empty($ret_info["birth"])){
            $ret_info["birth"] = isset($ret_info["birth"])?substr($ret_info["birth"],0,4).".".substr($ret_info["birth"],4,2).".".substr($ret_info["birth"],6,2):"";
        }
        if(isset($ret_info["stu_test_paper"]) && isset($ret_info['tea_download_paper_time'])){
            $ret_info["stu_test_paper_str"] = \App\Helper\Common::get_test_pager_boolean_color_str_new(
                $ret_info["stu_test_paper"], $ret_info['tea_download_paper_time']
            );
        }
        $parent_info_list = $this->t_parent_child->get_stu_parent_info_list($userid);
        $course_list = $this->t_test_lesson_subject->get_user_course_list($userid);
        $subject_info_list = $this->t_student_init_info->get_subject_info_list($userid);
        $this->change_and_unset_field($subject_info_list,"subject_info","user_desc");
        $parent_except_info_list =  $this->t_student_init_info->get_parent_except_info_list($userid);
        return $this->output_succ([
            "stu_list"=>$ret_info,
            "parent_info_list"=>$parent_info_list,
            "course_list"=>$course_list,
            "subject_info_list"=>$subject_info_list,
            "parent_except_info_list"=>$parent_except_info_list
        ]);

    }

    public function change_and_unset_field(&$a,$b,$c) {
        if (!$a[$b]) {
            $a[$b] = $a[$c];
        }
        unset($a[$c]);
    }


    public function save_student_init_info(){
        $userid= $this->get_in_userid();
        $subject_shuxue = $this->get_in_int_val("subject_shuxue");
        $subject_yingyu = $this->get_in_int_val("subject_yingyu");
        $subject_yuwen = $this->get_in_int_val("subject_yuwen");
        $subject_wuli = $this->get_in_int_val("subject_wuli");
        $subject_huaxue = $this->get_in_int_val("subject_huaxue");
        $subject_info = $this->get_in_str_val("subject_info");
        $class_top = $this->get_in_str_val("class_top");
        $grade_top = $this->get_in_str_val("grade_top");
        $order_info = $this->get_in_str_val("order_info");
        $teacher = $this->get_in_str_val("teacher");
        $teacher_info = $this->get_in_str_val("teacher_info");
        $test_lesson_info = $this->get_in_str_val("test_lesson_info");
        $mail_addr = $this->get_in_str_val("mail_addr");
        $parent_other_require = $this->get_in_str_val("parent_other_require");
        $lesson_plan = $this->get_in_str_val("lesson_plan");
        $has_fapiao = $this->get_in_int_val("has_fapiao");
        $except_lesson_count = $this->get_in_int_val("except_lesson_count");
        $week_lesson_num = $this->get_in_int_val("week_lesson_num");
        if( $week_lesson_num==0){
            return $this->output_err("每周课次不能为0");
        }
        if($except_lesson_count==0){
            return $this->output_err("每次课时不能为0");
        }

        $this->t_seller_student_new->field_update_list($userid,[
            "user_desc"         =>$subject_info
        ]);
        $ret = $this->t_student_init_info->field_get_value($userid,"userid");
        if($ret >0){
            $this->t_student_init_info->field_update_list($userid,[
                "subject_huaxue"=>$subject_huaxue,
                "subject_wuli"   =>$subject_wuli,
                "subject_yuwen"   =>$subject_yuwen,
                "subject_shuxue"=>$subject_shuxue,
                "subject_yingyu"=>$subject_yingyu,
                "subject_info"  =>$subject_info,
                "order_info"    =>$order_info,
                "teacher"       =>$teacher,
                "teacher_info"  =>$teacher_info,
                "test_lesson_info"=>$test_lesson_info,
                "has_fapiao"    =>$has_fapiao,
                "mail_addr"     =>$mail_addr,
                "lesson_plan"   =>$lesson_plan,
                "parent_other_require"=>$parent_other_require,
                "except_lesson_count"  =>$except_lesson_count,
                "week_lesson_num"  =>$week_lesson_num,
                "grade_top"       =>$grade_top,
                "class_top"       =>$class_top
            ]);
            $init_info_pdf_url  = $this->t_student_info->get_init_info_pdf_url($userid);
            if(empty($init_info_pdf_url)){
                $this->t_student_info->field_update_list($userid,["init_info_pdf_url"=>1]);
            }
        }else{
            $this->t_student_init_info->row_insert([
                "userid"        =>$userid,
                "subject_huaxue"=>$subject_huaxue,
                "subject_wuli"   =>$subject_wuli,
                "subject_yuwen"   =>$subject_yuwen,
                "subject_shuxue"=>$subject_shuxue,
                "subject_yingyu"=>$subject_yingyu,
                "subject_info"  =>$subject_info,
                "order_info"    =>$order_info,
                "teacher"       =>$teacher,
                "teacher_info"  =>$teacher_info,
                "test_lesson_info"=>$test_lesson_info,
                "has_fapiao"    =>$has_fapiao,
                "mail_addr"     =>$mail_addr,
                "lesson_plan"   =>$lesson_plan,
                "parent_other_require"=>$parent_other_require,
                "except_lesson_count"  =>$except_lesson_count,
                "week_lesson_num"  =>$week_lesson_num,
                "grade_top"       =>$grade_top,
                "class_top"       =>$class_top
            ]);
            $this->t_student_info->field_update_list($userid,["init_info_pdf_url"=>1]);
        }
        return $this->output_succ();
    }

    public function get_student_test_lesson_info(){
        $userid= $this->get_in_userid();
        $test_lesson_subject_id = $this->get_in_int_val("test_lesson_subject_id");
        $ret_info = $this->t_student_info->get_user_info_for_api($userid);
        if($ret_info["next_revisit_time"]>0){
            $ret_info["next_revisit_time"] =  date("Y-m-d H:i",$ret_info["next_revisit_time"]);
        }
        E\Epad_type::set_item_value_str($ret_info, "has_pad");
        if(!$ret_info["xingetedian"]){
            $ret_info["xingetedian"] = $ret_info["stu_character_info"];
            unset($ret_info["stu_character_info"]);
        }
        if(isset($ret_info["lesson_count_left"]) && isset($ret_info["lesson_count_all"]) && !empty($ret_info["lesson_count_all"])){
            $ret_info["lesson_count_left"] = ($ret_info["lesson_count_left"]/100)."/".($ret_info["lesson_count_all"]/100);
        }
        if(isset($ret_info["birth"]) && !empty($ret_info["birth"])){
            $ret_info["birth"] = isset($ret_info["birth"])?substr($ret_info["birth"],0,4).".".substr($ret_info["birth"],4,2).".".substr($ret_info["birth"],6,2):"";
        }
        if(isset($ret_info["stu_test_paper"]) && isset($ret_info['tea_download_paper_time'])){
            $ret_info["stu_test_paper_str"] = \App\Helper\Common::get_test_pager_boolean_color_str_new(
                $ret_info["stu_test_paper"], $ret_info['tea_download_paper_time']
            );
        }
        $parent_info_list = $this->t_parent_child->get_stu_parent_info_list($userid);
        $course_list = $this->t_test_lesson_subject->get_user_course_list($userid);
        $test_lesson_require_info_list = $this->t_seller_student_new->get_test_lesson_require_info_for_api($userid,$test_lesson_subject_id);
        if($test_lesson_require_info_list["stu_request_test_lesson_time"]){
            $test_lesson_require_info_list["stu_request_test_lesson_time"] = date('Y-m-d H:i',$test_lesson_require_info_list["stu_request_test_lesson_time"]);
        }
        return $this->output_succ([
            "stu_list"=>$ret_info,
            "parent_info_list"=>$parent_info_list,
            "course_list"=>$course_list,
            "test_lesson_require_info_list"=>$test_lesson_require_info_list
        ]);


    }

    public function save_student_test_lesson_info(){
        $userid= $this->get_in_userid();
        $stu_test_ipad_flag = $this->get_in_int_val("stu_test_ipad_flag");
        //$user_desc = $this->get_in_str_val("user_desc");
        $stu_request_test_lesson_time = strtotime($this->get_in_str_val("stu_request_test_lesson_time"));
        // $stu_request_test_lesson_time = strtotime(date("2017-01-24 12:00"));
        //dd($stu_request_test_lesson_time);
        $seller_student_status = $this->get_in_int_val("seller_student_status");
        $stu_request_test_lesson_demand = $this->get_in_str_val("stu_request_test_lesson_demand");
        $stu_test_lesson_level = $this->get_in_int_val("stu_test_lesson_level");

        $test_lesson_subject_id = $this->get_in_int_val("test_lesson_subject_id");
        $require_id = $this->get_in_int_val("require_id");
        $accept_flag = $this->t_test_lesson_subject_require->get_accept_flag($require_id );
        // dd($accept_flag);
        $this->t_seller_student_new->field_update_list($userid,[
            "stu_test_ipad_flag" =>$stu_test_ipad_flag
        ]);
        if($seller_student_status != 200){
            $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                "stu_test_lesson_level"=>$stu_test_lesson_level,
                "stu_request_test_lesson_demand"=>$stu_request_test_lesson_demand,
                "stu_request_test_lesson_time" =>$stu_request_test_lesson_time

            ]);

            //试听申请
            $origin_info=$this->t_student_info->get_origin($userid);

            $test_stu_grade=isset($test_stu_grade)?$test_stu_grade:-1;

            $test_stu_request_test_lesson_demand = $this->t_test_lesson_subject->get_stu_request_test_lesson_demand($test_lesson_subject_id);


            $ret=$this->t_test_lesson_subject_require->add_require(
                $this->get_account_id()
                , $this->get_account() ,
                $test_lesson_subject_id,
                $origin_info["origin"],
                $stu_request_test_lesson_time,
                $test_stu_grade,
                $test_stu_request_test_lesson_demand
            ) ;
            if (!$ret ) {
                return $this->output_err("当前该同学的申请请求 还没处理完毕,不可新建");
            }else{
                return $this->output_succ();
            }

        }else{
            // dd(111111);
            $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                "stu_test_lesson_level"=>$stu_test_lesson_level,
                "stu_request_test_lesson_demand"=>$stu_request_test_lesson_demand
            ]);

        }
        return $this->output_succ();

    }

    public function teacher_class_comment_for_stu(){
        $lessonid= $this->get_in_lessonid();
        $stu_performance = $this->t_lesson_info->get_performance_stu_by_lessonid($lessonid);
        $level=[0=>"",1=>"加油",2=>"还行",3=>"不错",4=>"良好",5=>"优秀"];
        if(!empty($stu_performance)){
            $ret_info = json_decode($stu_performance,true);
            $level_stu = $level[$ret_info['total_judgement']];
            $knw_content = "";
            if(isset($ret_info['point_note_list']) && is_array($ret_info['point_note_list'])){
                foreach($ret_info['point_note_list'] as $key => $val){
                    $ret_info['point_name'][$key]     = $val['point_name'];
                    $ret_info['point_stu_desc'][$key] = $val['point_stu_desc'];
                    $knw_content .= $val['point_name']."、";
                }
            }
            $num = count(@$ret_info['point_stu_desc']);
        }
        $knw_content = trim(@$knw_content,"、");
        return $this->output_succ([
            "level_stu"=>@$level_stu,
            "total_judgement"=>@$ret_info["total_judgement"],
            "homework_situation"=>@$ret_info["homework_situation"],
            "lesson_interact"=>@$ret_info["lesson_interact"],
            "content_grasp" =>@$ret_info["content_grasp"],
            "knw_content"    =>@$knw_content,
            "knw_num"        =>@$num,
            "point_note_list" =>@$ret_info["point_note_list"]
        ]);

    }
    public function get_parent_info_list() {
        $userid= $this->get_in_userid();
        $parent_info_list = $this->t_parent_child->get_stu_parent_info_list($userid);
        return $this->output_succ([
            "parent_info_list"=>$parent_info_list
        ]);

    }

    public function update_parent_info(){
        $userid= $this->get_in_userid();
        $parent_type= $this->get_in_int_val("parent_type");
        $parent_name= $this->get_in_str_val("parent_name");
        $parent_phone= $this->get_in_str_val("parent_phone");
        $parent_email= $this->get_in_str_val("parent_email");
        $parent_info = $this->t_parent_info->get_parentid_by_phone($parent_phone);
        $parentid = $parent_info["parentid"];
        $this->t_parent_info->field_update_list($parentid,[
            "nick"  =>$parent_name,
            "email" =>$parent_email
        ]);
        $this->t_parent_child->field_update_list_2($parentid,$userid,["parent_type"=>$parent_type]);
        return $this->output_succ();

    }
    public function del_parent_info(){
        $userid= $this->get_in_userid();
        $parent_phone= $this->get_in_str_val("parent_phone");
        $parent_info = $this->t_parent_info->get_parentid_by_phone($parent_phone);
        $parentid = $parent_info["parentid"];
        $this->t_parent_info->row_delete($parentid);
        $this->t_parent_child->row_delete_2($parentid,$userid);
        $this->t_user_info->row_delete($parentid);
        $this->t_phone_to_user->row_delete($parent_phone);
        return $this->output_succ();
    }

    public function add_parent_info(){
        $userid= $this->get_in_userid();
        $parent_type= $this->get_in_int_val("parent_type");
        $parent_name= $this->get_in_str_val("parent_name");
        $parent_phone= $this->get_in_str_val("parent_phone");
        $parent_email= $this->get_in_str_val("parent_email");

        $parentid = $this->t_user_info->add_parent_info();
        $this->t_phone_to_user->row_insert([
            "phone"  =>$parent_phone,
            "role"   =>4,
            "userid" =>$parentid
        ]);
        $this->t_parent_info->row_insert([
            "parentid"=>$parentid,
            "nick"    =>$parent_name,
            "phone"   =>$parent_phone,
            "email"   =>$parent_email
        ]);
        $this->t_parent_child->row_insert([
            "parentid"=>$parentid,
            "parent_type"    =>$parent_type,
            "userid"   =>$userid
        ]);

        return $this->output_succ();

    }

    public function assign_sub_adminid_list(){
        $page_num              = $this->get_in_page_num();
        $assign_type = $this->get_in_int_val("assign_type",0);
        //$assign_type =0;
        $ret_info = $this->t_test_lesson_subject->get_seller_student_assign_info($page_num,$assign_type);
        foreach( $ret_info["list"] as $index=> &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"assign_time");
            E\Eseller_student_status::set_item_value_str($item);
            E\Ebook_grade::set_item_value_str($item,"grade");
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad");
        }

        return $this->output_api_ret_info($ret_info);
    }

    public function seller_rank_list(){
        list($start_time,$end_time)= $this->get_in_date_range(date("Y-m-01",time(NULL) ) ,0 );
        $year_start = strtotime(date("Y-01-01",time()));
        $adminid=$this->get_account_id();
        //$adminid = 438;
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list( $adminid,  $start_time );
        $arr=[1=>"require_count",2=>"success_test_lesson_count",3=>"success_test_lesson_percent",4=>"order_count",5=>"order_percent",6=>"order_money",7=>"assign_count",8=>"lesson_plan",9=>"order_person_count"];
        $arr_ave = $this->get_seller_ave_info();
        $self_top_info_list=[];
        foreach($self_top_info as $k=>$v){
            $key = $arr[$k];
            $self_top_info_list[$key]=$v;
            if(isset($arr_ave[$k])){
                if($arr_ave[$k] > $v["value"]){
                    $self_top_info_list[$key]["duibi"] ="down";
                }else{
                    $self_top_info_list[$key]["duibi"] ="up";
                }
            }
        }
        $self_top_trend_info =$this->t_tongji_seller_top_info->get_admin_top_trend_list( $adminid,  $year_start );
        foreach($self_top_trend_info as &$item){
            $item["month"]=date("m",$item["logtime"]);
        }
        $seller_person_info= $this->t_order_info->get_1v1_order_seller_list($start_time,$end_time);
        foreach($seller_person_info["list"] as $k=>&$item){
            $item["top_index"]=$k+1;
        }
        $seller_person_info_new= $this->t_order_info->get_1v1_order_new_seller_list($start_time,$end_time);
        foreach($seller_person_info_new as $k=>&$item){
            $item["top_index"]=$k+1;
        }
        $group_list      = $this->t_order_info->get_1v1_order_seller_list_group($start_time,$end_time);
        foreach($group_list as $k=>&$item){
            $item["top_index"]=$k+1;
        }
        return $this->output_succ([
            "self_top_info_list"     =>$self_top_info_list,
            "self_top_trend_info"    =>$self_top_trend_info,
            "seller_person_info_new" =>$seller_person_info_new,
            "seller_person_info"     =>$seller_person_info["list"],
            "group_list"             =>$group_list
        ]);


    }

    public function get_seller_ave_info(){
         list($start_time,$end_time)= $this->get_in_date_range(date("Y-m-01",time(NULL) ) ,0 );
         $success_test_lesson_count_list = $this->t_test_lesson_subject_require->get_success_test_lesson_count_list_all($start_time,$end_time);
         $success_test_lesson_count_ave = round($success_test_lesson_count_list["value"]/$success_test_lesson_count_list["all_count"],2);
         $order_count_list = $this->t_order_info->get_order_count_list_all($start_time,$end_time);
         $order_count_ave = round($order_count_list["value"]/$order_count_list["all_count"],2);
         $order_money_ave = round($order_count_list["money"]/$order_count_list["all_count"],2);
         $order_percent_ave = round($order_count_list["value"]/$order_count_list["all_count"]/($success_test_lesson_count_list["value"]/$success_test_lesson_count_list["all_count"]),2);
         $order_person_count_list = $this->t_order_info->get_order_person_count_list_all($start_time,$end_time);
         $order_person_count_ave = round($order_person_count_list["value"]/$order_person_count_list["all_count"],2);
         $assign_count_list = $this->t_seller_student_new->get_assign_count_list_all($start_time,$end_time);
         $assign_count_ave = round($assign_count_list["value"]/$assign_count_list["all_count"],2);
         $lesson_plan_list = $this->t_test_lesson_subject_sub_list->get_lesson_count_list_all($start_time,$end_time);
         $lesson_plan_ave = round($lesson_plan_list["value"]/$lesson_plan_list["all_count"],2);
         $arr_ave=[2=>$success_test_lesson_count_ave,4=>$order_count_ave,5=>$order_percent_ave,6=>$order_money_ave,7=>$assign_count_ave,8=>$lesson_plan_ave,9=>$order_percent_ave];
         return $arr_ave;
    }

    public function update_seller_student_status(){
        $test_lesson_subject_id= $this->get_in_int_val("test_lesson_subject_id");
        $seller_student_status= $this->get_in_int_val("e_seller_student_status");
        $db_tt_item=$this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"seller_student_status");
        //更新 seller_student_status
        $status_arr=[300,301,302,303,304,305];
        if(!in_array($db_tt_item["seller_student_status"],$status_arr) && in_array($seller_student_status,$status_arr)){
            $account_id = $this->get_account_id();
            $num = $this->t_test_lesson_subject->get_except_contract_num($account_id);
            if($num >=20){
                return $this->output_err("待签约人员已满额,请等待!" );
            }
        }
        if ($db_tt_item["seller_student_status"] != $seller_student_status) {
            $this->t_test_lesson_subject->set_seller_student_status( $test_lesson_subject_id, $seller_student_status,  $this->get_account() );
        }
        return $this->output_succ();

    }

    public function set_no_hold_free(){
        $userid= $this->get_in_userid();
        //$userid=60010;
        $account_id = $this->get_account_id();
        $account=$this->get_account();
        $test_lesson_subject_id = $this->get_in_int_val("test_lesson_subject_id");
        $test_subject_free_type = $this->get_in_int_val("test_subject_free_type");
        $test_subject_free_reason = $this->get_in_int_val("test_subject_free_reason");
        $hold_flag=0;
        $this->t_seller_student_new->set_no_hold_admin($hold_flag, $userid,  $account_id);

        $hold_count=$this->t_seller_student_new->get_hold_count($account_id);
        if ($account<>"jim") {
            if ($hold_count<=50) {
                $this->output_succ("保留太少,不能操作");
            }
        }
        $phone=$this->t_seller_student_new->field_get_value($userid,"phone");
        $ret = $ret_update = $this->t_book_revisit->add_book_revisit(
            $phone,
            "操作者:$account 状态: 回到公海 ",
            "system"
        );

        $rr = $this->t_seller_student_new->set_no_hold_free($account_id);
        if($rr){
            $this->t_test_subject_free_list->row_insert([
                "adminid"                  =>$account_id,
                "userid"                   =>$userid,
                "test_subject_free_reason" =>$test_subject_free_reason,
                "test_subject_free_type"   =>$test_subject_free_type,
                "add_time"                 =>time()
            ],false, true);
        }

        return $this->output_succ();

        //dd($rr);

    }

    public function seller_add_contract(){
        $c = new ss_deal();
        return $c->seller_add_contract_new();
    }

    public function set_no_accpect(){
        $c = new ss_deal();
        return $c->set_no_accpect();
    }
    public function seller_student_add_subject(){
        $c = new ss_deal();
        return $c->seller_student_add_subject();
    }

    public function del_contract(){
        $c = new user_manage();
        return $c->del_contract();
    }

    public function get_stu_performance_for_seller()
    {
        $c = new ss_deal();
        return $c->get_stu_performance_for_seller();
    }

}