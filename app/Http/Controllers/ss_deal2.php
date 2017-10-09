<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class ss_deal2 extends Controller
{
    use CacheNick;
    use TeaPower;

    public function  seller_set_deal_cur_new_user()  {
        $adminid= $this->get_account_id();
        $userid= $this->get_in_userid();

        $key="DEAL_NEW_USER_$adminid";
        $old_userid=\App\Helper\Common::redis_get($key);
        $tq_called_flag= $this->t_seller_student_new->get_tq_called_flag($old_userid);
        if (!$tq_called_flag) {
            return $this->output_err("当前例子还未拨通!");
        }

        \App\Helper\Common::redis_set($key, $userid );
        return $this->output_succ();
    }
    public function  test_subject_free_list_get_list_js()  {
        $page_info = $this->get_in_page_info();

        $userid= $this->get_in_userid();
        $ret_info= $this->t_test_subject_free_list->get_list_by_userid($page_info, $userid );
        foreach($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            E\Etest_subject_free_type::set_item_value_str($item);
            $this->cache_set_item_account_nick($item);
        }
        return $this->output_ajax_table ($ret_info);
    }

    public function get_origin_phone_list_js() {
        $page_info = $this->get_in_page_info();
        list($start_time, $end_time) = $this->get_in_date_range(0,0);
        $opt_type_str                = $this->get_in_str_val("opt_type_str");
        $origin_level= $this->get_in_el_origin_level();
        $key1 = $this->get_in_str_val("key1");
        $key2 = $this->get_in_str_val("key2");
        $key3 = $this->get_in_str_val("key3");
        $key4 = $this->get_in_str_val("key4");

        $origin_ex_arr= preg_split("/,/", session("ORIGIN_EX"));

        if (!$origin_ex_arr[0] ){
            if ($key1!="全部") {
                $origin_ex_arr[0]=$key1;
            }
        }

        if (!isset($origin_ex_arr[1] )){
            $origin_ex_arr[1]=$key2;
        }

        if (!isset($origin_ex_arr[2] )){
            $origin_ex_arr[2]=$key3;
        }

        if (!isset($origin_ex_arr[3] )){
            $origin_ex_arr[3]=$key4;
        }

        $origin_ex= join(",", $origin_ex_arr );


        $ret_info= $this->t_seller_student_new->get_origon_list( $page_info, $start_time, $end_time,$opt_type_str, $origin_ex,$origin_level) ;
        foreach($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
        }


        return $this->output_ajax_table($ret_info);
    }


    public function seller_student_del() {
        $userid=$this->get_in_userid();
        $this->t_seller_student_new->row_delete($userid);
        $this->t_test_lesson_subject->del_by_userid( $userid );
        return $this->output_succ() ;

    }

    public function reset_sys_invaild_flag () {
        $userid=$this->get_in_userid();
        $this->t_seller_student_new->reset_sys_invaild_flag($userid);
        return $this->output_succ() ;
    }

    public function get_origin_phone_list_js_bd2() {
        $page_info = $this->get_in_page_info();
        list($start_time, $end_time) = $this->get_in_date_range(0,0);
        $opt_type_str                = $this->get_in_str_val("opt_type_str");
        $key1 = $this->get_in_str_val("key1");
        $key2 = $this->get_in_str_val("key2");
        $key3 = $this->get_in_str_val("key3");
        $key4 = $this->get_in_str_val("key4");

        $origin_ex_arr= preg_split("/,/", session("ORIGIN_EX"));

        if (!$origin_ex_arr[0] ){
            if ($key1!="全部") {
                $origin_ex_arr[0]=$key1;
            }
        }

        if (!isset($origin_ex_arr[1] )){
            $origin_ex_arr[1]=$key2;
        }

        if (!isset($origin_ex_arr[2] )){
            $origin_ex_arr[2]=$key3;
        }

        if (!isset($origin_ex_arr[3] )){
            $origin_ex_arr[3]=$key4;
        }

        $origin_ex= join(",", $origin_ex_arr );

        $this->t_seller_student_new->switch_tongji_database();

        $ret_info= $this->t_seller_student_new->get_origon_list_bd2( $page_info, $start_time, $end_time,$opt_type_str, $origin_ex) ;
        foreach($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
        }

        // dd($ret_info);


        return $this->output_ajax_table ($ret_info);
    }


    public function get_tmk_assign_time(){

        $tmk_adminid = $this->get_in_int_val('tmk_adminid');
        $start_time  = strtotime($this->get_in_str_val('start_time'));
        $end_time    = strtotime($this->get_in_str_val('end_time'));

        $ret = $this->t_seller_student_new->get_tmk_assign_time_by_adminid($tmk_adminid, $start_time, $end_time);

        foreach($ret as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"tmk_assign_time",'_str');
            \App\Helper\Utils::unixtime2date_for_item($item,"first_call_time",'_str');
            $item['global_tq_called_flag_str'] = \App\Enums\Etq_called_flag::get_desc($item['global_tq_called_flag']);
            $item['nick'] = $this->t_student_info->get_nick($item['userid']);
        }

        return $this->output_succ(['data'=>$ret]);

    }

    public function set_mail_photo(){
        $orderid  = $this->get_in_int_val("orderid");
        $mail_code_url = $this->get_in_str_val("mail_url");
        $domain = config('admin')['qiniu']['public']['url'];
        $mail_code_url = $domain.'/'.$mail_code_url;

        // $this->t_order_info->field_update_list($orderid,[
        //     "mail_code_url" => $mail_code_url
        // ]);
        return $this->output_succ(['data'=>$mail_code_url]);
    }

    /**
    *
    *
    */

    public function set_user_free () {

        $userid=$this->get_in_userid();
        $item=$this->t_seller_student_new->get_user_info_for_free($userid);
        $account = $this->get_account();

        $phone=$item["phone"];
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
            "adminid" => $this->get_account_id(),
            "test_subject_free_type" => $test_subject_free_type,
        ],false,true);

        $this->t_seller_student_new->set_user_free($userid);
        $this->t_seller_student_new->field_update_list($userid,[
            "free_adminid" => $this->get_account_id(),
            "free_time" => time(),
        ]);
        return $this->output_succ();

    }

    public function show_change_lesson_by_teacher(){
        $start_time = strtotime($this->get_in_str_val('start_time'));
        $end_time   = strtotime($this->get_in_str_val('end_time'));

        $end_time = $end_time+86400;

        $teacherid = $this->get_in_int_val('teacherid');
        $lesson_cancel_reason_type = $this->get_in_int_val('lesson_cancel_reason_type',-1);

        $ret_info = $this->t_lesson_info_b2->get_lesson_cancel_detail($start_time,$end_time,$lesson_cancel_reason_type,$teacherid);

        foreach($ret_info as &$item){
            $item['teacher_nick'] = $this->cache_get_teacher_nick($item['teacherid']);
            if($item['assistantid']){
                $item['ass_nick'] = $this->cache_get_assistant_nick($item['assistantid']);
            }else{
                $item['ass_nick'] = $this->cache_get_account_nick($item['require_adminid']);
            }
            $item['lesson_count'] = $item['lesson_count']/100;
            E\Econtract_type::set_item_value_str($item,'lesson_type');
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);

            if($lesson_cancel_reason_type == 23){
                $item['lesson_cancel_reason_type_str'] = "老师迟到"; // 临时处理
            }else{
                E\Elesson_cancel_reason_type::set_item_value_str($item);
            }

            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_end",'','H:i:s');
        }
        return $this->output_succ(['data'=>$ret_info]);
    }



    public function show_change_lesson_by_parent(){
        $start_time = strtotime($this->get_in_str_val('start_time'));
        $end_time   = strtotime($this->get_in_str_val('end_time'));
        $end_time   = $end_time+86400;
        $userid     = $this->get_in_int_val('userid');
        $lesson_cancel_reason_type = $this->get_in_int_val('lesson_cancel_reason_type',-1);

        $ret_info = $this->t_lesson_info_b2->get_lesson_cancel_detail_by_parent($start_time,$end_time,$lesson_cancel_reason_type,$userid);

        foreach($ret_info as &$item){
            $item['teacher_nick']     = $this->cache_get_teacher_nick($item['teacherid']);
            $item['lesson_count'] = $item['lesson_count']/100;
            E\Econtract_type::set_item_value_str($item,'lesson_type');
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Elesson_cancel_reason_type::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_end",'','H:i:s');

            if($item['assistantid']){
                $item['ass_nick']     = $this->cache_get_assistant_nick($item['assistantid']);
            }else{
                $item['ass_nick']     = $this->cache_get_account_nick($item['require_adminid']);
            }
        }

        // dd($ret_info);
        return $this->output_succ(['data'=>$ret_info]);
    }


    public function get_refund_teacher_detail_info(){
        $teacherid  = $this->get_in_int_val("teacherid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time   = strtotime($this->get_in_str_val("end_time"));

        $ret = $this->t_order_refund->get_refund_count_for_tec($start_time,$end_time,$teacherid);

        foreach($ret as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"apply_time","_str");
        }

        if($ret){
            return $this->output_succ(["data"=>$ret]);
        }else{
            return $this->output_succ();
        }

    }


    public function get_refund_ass_detail_info(){
        $uid  = $this->get_in_int_val("uid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time   = strtotime($this->get_in_str_val("end_time"));

        $ret = $this->t_order_refund->get_refund_count_for_ass($start_time,$end_time,$uid);

        foreach($ret as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"apply_time","_str");
        }

        if($ret){
            return $this->output_succ(["data"=>$ret]);
        }else{
            return $this->output_succ();
        }

    }

    public function get_stu_study_habit_list(){
        $study_habit = $this->get_in_str_val('study_habit',"");
        // $textbook = "2,3";
        $list    = E\Estudy_habit::$desc_map;
        $res = [];
        $data=[];
        foreach($list as $i=>$val){
            $res[]=["study_habit"=>$val,"num"=>$i];
            $data[]=$val;
        }
        if(!empty($study_habit)){
            $study_habit = trim($study_habit,",");
            $arr = explode(",",$study_habit);
            foreach ($arr as $k) {
                if( in_array($k,$data)){
                    foreach($res as $kk=>&$item){
                        if($k == $item["study_habit"]){
                            $item["has_study_habit"] = in_array($k,$data)?1:0;
                        }
                    }

                }

            }
        }
        return $this->output_succ(["data"=> $res]);
    }

    public function get_stu_study_habit_name(){
        $study_habit = $this->get_in_str_val('study_habit',"");
        $list    = E\Estudy_habit::$desc_map;
        $arr = json_decode($study_habit,true);
        $data="";
        foreach($arr as $v){
            $data .= $list[$v].",";
        }
        $data = trim($data,",");
        return $this->output_succ(["data"=> $data]);

    }

    public function get_stu_interests_hobbies_list(){
        $interests_hobbies = $this->get_in_str_val('interests_hobbies',"");
        // $textbook = "2,3";
        $list    = E\Einterests_hobbies::$desc_map;
        $res = [];
        $data=[];
        foreach($list as $i=>$val){
            $res[]=["interests_hobbies"=>$val,"num"=>$i];
            $data[]=$val;
        }
        if(!empty($interests_hobbies)){
            $interests_hobbies = trim($interests_hobbies,",");
            $arr = explode(",",$interests_hobbies);
            foreach ($arr as $k) {
                if( in_array($k,$data)){
                    foreach($res as $kk=>&$item){
                        if($k == $item["interests_hobbies"]){
                            $item["has_interests_hobbies"] = in_array($k,$data)?1:0;
                        }
                    }

                }

            }
        }
        return $this->output_succ(["data"=> $res]);
    }

    public function get_stu_interests_hobbies_name(){
        $interests_hobbies = $this->get_in_str_val('interests_hobbies',"");
        $list    = E\Einterests_hobbies::$desc_map;
        $arr = json_decode($interests_hobbies,true);
        $data="";
        foreach($arr as $v){
            $data .= $list[$v].",";
        }
        $data = trim($data,",");
        return $this->output_succ(["data"=> $data]);

    }

    public function get_stu_character_type_list(){
        $character_type = $this->get_in_str_val('character_type',"");
        // $textbook = "2,3";
        $list    = E\Echaracter_type::$desc_map;
        $res = [];
        $data=[];
        foreach($list as $i=>$val){
            $res[]=["character_type"=>$val,"num"=>$i];
            $data[]=$val;
        }
        if(!empty($character_type)){
            $character_type = trim($character_type,",");
            $arr = explode(",",$character_type);
            foreach ($arr as $k) {
                if( in_array($k,$data)){
                    foreach($res as $kk=>&$item){
                        if($k == $item["character_type"]){
                            $item["has_character_type"] = in_array($k,$data)?1:0;
                        }
                    }

                }

            }
        }
        return $this->output_succ(["data"=> $res]);
    }

    public function get_stu_character_type_name(){
        $character_type = $this->get_in_str_val('character_type',"");
        $list    = E\Echaracter_type::$desc_map;
        $arr = json_decode($character_type,true);
        $data="";
        foreach($arr as $v){
            $data .= $list[$v].",";
        }
        $data = trim($data,",");
        return $this->output_succ(["data"=> $data]);

    }

    public function get_stu_need_teacher_style_list(){
        $need_teacher_style = $this->get_in_str_val('need_teacher_style',"");
        // $textbook = "2,3";
        $list    = E\Eneed_teacher_style::$desc_map;
        $res = [];
        $data=[];
        foreach($list as $i=>$val){
            $res[]=["need_teacher_style"=>$val,"num"=>$i];
            $data[]=$val;
        }
        if(!empty($need_teacher_style)){
            $need_teacher_style = trim($need_teacher_style,",");
            $arr = explode(",",$need_teacher_style);
            foreach ($arr as $k) {
                if( in_array($k,$data)){
                    foreach($res as $kk=>&$item){
                        if($k == $item["need_teacher_style"]){
                            $item["has_need_teacher_style"] = in_array($k,$data)?1:0;
                        }
                    }

                }

            }
        }
        return $this->output_succ(["data"=> $res]);
    }

    public function get_stu_need_teacher_style_name(){
        $need_teacher_style = $this->get_in_str_val('need_teacher_style',"");
        $list    = E\Eneed_teacher_style::$desc_map;
        $arr = json_decode($need_teacher_style,true);
        $data="";
        foreach($arr as $v){
            $data .= $list[$v].",";
        }
        $data = trim($data,",");
        return $this->output_succ(["data"=> $data]);

    }

    public function ass_add_require_test_lesson() {
        $userid                         = $this->get_in_userid();
        $parent_name                     = $this->get_in_str_val('parent_name');
        $gender                         = $this->get_in_int_val("gender");
        $grade                          = $this->get_in_grade();
        $subject                        = $this->get_in_subject();
        $school                         = $this->get_in_str_val("school");
        $editionid                      = $this->get_in_int_val("editionid");//教材id
        $province                       = $this->get_in_int_val("province");//省
        $city                           = $this->get_in_str_val("city");//市.区
        $area                           = $this->get_in_str_val("area");//县市
        $region                         = $this->get_in_str_val("region");//上学省
        $recent_results                 = $this->get_in_str_val("recent_results");//近期成绩
        $advice_flag                    = $this->get_in_int_val("advice_flag");//是否进步
        $class_rank                     = $this->get_in_str_val("class_rank");//班级排名
        $grade_rank                     = $this->get_in_str_val("grade_rank");//年级排名
        $academic_goal                  = $this->get_in_int_val("academic_goal");//升学目标
        $study_habit                    = $this->get_in_str_val("study_habit");//学习习惯
        $interests_and_hobbies          = $this->get_in_str_val("interests_and_hobbies");//兴趣爱好
        $character_type                 = $this->get_in_str_val("character_type");//学习习惯
        $need_teacher_style             = $this->get_in_str_val("need_teacher_style");//所需老师风格
        $tea_province                   = $this->get_in_str_val("tea_province");//老师省
        $tea_city                       = $this->get_in_str_val("tea_city");//老师市.区
        $tea_area                       = $this->get_in_str_val("tea_area");//老师县市
        $stu_request_test_lesson_demand = $this->get_in_str_val("stu_request_test_lesson_demand");//试听需求
        $intention_level                = $this->get_in_int_val("intention_level");//上课意向
        $stu_request_test_lesson_time   = strtotime($this->get_in_str_val("stu_request_test_lesson_time"));//试听时间
        $stu_test_paper                 = $this->get_in_str_val("test_paper");//试卷
        $test_stress                    = $this->get_in_int_val("test_stress");//应试压力
        $entrance_school_type           = $this->get_in_int_val("entrance_school_type");//升学目标
        $interest_cultivation           = $this->get_in_int_val("interest_cultivation");//趣味培养
        $extra_improvement              = $this->get_in_int_val("extra_improvement");//课外提高
        $habit_remodel                  = $this->get_in_int_val("habit_remodel");//习惯重塑
        $ass_test_lesson_type           = $this->get_in_int_val('ass_test_lesson_type');//分类
        $change_teacher_reason_type     = $this->get_in_int_val('change_teacher_reason_type');//换老师原因分类
        $url                            = $this->get_in_str_val('change_reason_url');//申请原因图片
        $green_channel_teacherid        = $this->get_in_int_val("green_channel_teacherid");//绿色通道
        $change_reason                  = trim($this->get_in_str_val('change_reason'));//申请原因
        \App\Helper\Utils::logger("ass_add_require_test_lesson-change_reason: $change_reason change_teacher_reason_type: $change_teacher_reason_type");
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
        //update t_student_info
        $ss_arr=[
            "parent_name"           => $parent_name,
            "grade"                 => $grade,
            "school"                => $school,
            "editionid"             => $editionid,
            "province"              => $province,
            "city"                  => $city,
            "area"                  => $area,
            "region"                => $region,
        ];
        $this->t_student_info->field_update_list($userid,$ss_arr);
        $phone = $this->t_seller_student_new->get_phone($userid);
        if (!$phone) {//进例子,insert t_seller_student_new
            $phone=$this->t_student_info->get_phone($userid);
            $phone_location = \App\Helper\Common::get_phone_location($phone);
            $this->t_seller_student_new->row_insert([
                "userid"                => $userid,
                "class_rank"            => $class_rank,
                "grade_rank"            => $grade_rank,
                "academic_goal"         => $academic_goal,
                "study_habit"           => $study_habit,
                "interests_and_hobbies" => $interests_and_hobbies,
                "character_type"        => $character_type,
                "need_teacher_style"    => $need_teacher_style,
                "test_stress"           => $test_stress,
                "entrance_school_type"  => $entrance_school_type,
                "interest_cultivation"  => $interest_cultivation,
                "extra_improvement"     => $extra_improvement,
                "habit_remodel"         => $habit_remodel,
                "phone"                 => $phone,
                "add_time"              => time(NULL) ,
            ]);
        }else{//update t_seller_student_new
            $ss_arr=[
                "class_rank"            => $class_rank,
                "grade_rank"            => $grade_rank,
                "academic_goal"         => $academic_goal,
                "study_habit"           => $study_habit,
                "interests_and_hobbies" => $interests_and_hobbies,
                "character_type"        => $character_type,
                "need_teacher_style"    => $need_teacher_style,
                "test_stress"           => $test_stress,
                "entrance_school_type"  => $entrance_school_type,
                "interest_cultivation"  => $interest_cultivation,
                "extra_improvement"     => $extra_improvement,
                "habit_remodel"         => $habit_remodel,
            ];
            $this->t_seller_student_new->field_update_list($userid,$ss_arr);
        }
        //update t_test_lesson_subject
        $test_lesson_subject_id= $this->t_test_lesson_subject->check_and_add_ass_subject(
            $this->get_account_id(),$userid,$grade,$subject,$ass_test_lesson_type);
        $origin="3助教-".E\Eass_test_lesson_type::get_desc( $ass_test_lesson_type);
        $this->t_test_lesson_subject->field_update_list(
            $test_lesson_subject_id,[
                "subject"                        => $subject,
                "tea_province"                   => $tea_province,
                "tea_city"                       => $tea_city,
                "tea_area"                       => $tea_area,
                "stu_request_test_lesson_demand" => $stu_request_test_lesson_demand,
                "stu_request_test_lesson_time"   => $stu_request_test_lesson_time,
                "recent_results"                 => $recent_results,
                "advice_flag"                    => $advice_flag,
                "intention_level"                => $intention_level,
                "stu_test_paper"                 => $stu_test_paper,
                "ass_test_lesson_type"           => $ass_test_lesson_type,
            ]);
        //insert t_test_lesson_subject_require
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
            $change_reason_url
        );
        //update t_test_lesson_subject_require
        $require_id = $this->t_test_lesson_subject->get_current_require_id($test_lesson_subject_id);
        if($require_id>0 && $ass_test_lesson_type ==2){
            $this->t_test_lesson_subject_require->field_update_list($require_id,[
                "change_teacher_reason"          => $change_reason,
                "change_teacher_reason_img_url"  => $change_reason_url,
                "change_teacher_reason_type"     => $change_teacher_reason_type
            ]);
        }
        if (!$ret){
            \App\Helper\Utils::logger("add_require:  $test_lesson_subject_id");
            return $this->output_err("当前该同学的申请请求 还没处理完毕,不可新建");
        }else{
            $ret_flag = $this->t_test_lesson_subject_require->field_update_list($require_id,[
                "is_green_flag"                 => $is_green_flag,
                "green_channel_teacherid"       => $green_channel_teacherid,
            ]);
            // if((!$change_teacher_reason_type || !$change_reason) && $ass_test_lesson_type ==2 ){//james
            //     //rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
            //     $now = date('Y-m-d H:i:s',time());
            //     $data = [
            //         'first'    => '换老师统计-调试',
            //         'keyword1' => '换老师统计-调试',
            //         'keyword2' => "换老师统计-调试 $now"
            //     ];
            //     $teacher_url = 'http://admin.yb1v1.com/tongji_ss/tongji_change_teacher_info';
            //     \App\Helper\Utils::send_teacher_msg_for_wx('oJ_4fxPmwXgLmkCTdoJGhSY1FTlc','rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o', $data,$teacher_url);
            // }

            return $this->output_succ();
        }
    }

    public function ass_save_user_info()
    {
        $userid                         = $this->get_in_userid();
        $test_lesson_subject_id         = $this->get_in_int_val('test_lesson_subject_id');
        $require_id                     = $this->get_in_int_val("require_id");
        $parent_name                    = $this->get_in_str_val('parent_name');
        $gender                         = $this->get_in_int_val("gender");
        $grade                          = $this->get_in_grade();
        $subject                        = $this->get_in_subject();
        $school                         = $this->get_in_str_val("school");
        $editionid                      = $this->get_in_int_val("editionid");//教材id
        $province                       = $this->get_in_int_val("province");//省
        $city                           = $this->get_in_str_val("city");//市.区
        $area                           = $this->get_in_str_val("area");//县市
        $region                         = $this->get_in_str_val("region");//上学省
        $recent_results                 = $this->get_in_str_val("recent_results");//近期成绩
        $advice_flag                    = $this->get_in_int_val("advice_flag");//是否进步
        $class_rank                     = $this->get_in_str_val("class_rank");//班级排名
        $grade_rank                     = $this->get_in_str_val("grade_rank");//年级排名
        $academic_goal                  = $this->get_in_int_val("academic_goal");//升学目标
        $study_habit                    = $this->get_in_str_val("study_habit");//学习习惯
        $interests_and_hobbies          = $this->get_in_str_val("interests_and_hobbies");//兴趣爱好
        $character_type                 = $this->get_in_str_val("character_type");//学习习惯
        $need_teacher_style             = $this->get_in_str_val("need_teacher_style");//所需老师风格
        $tea_province                   = $this->get_in_str_val("tea_province");//老师省
        $tea_city                       = $this->get_in_str_val("tea_city");//老师市.区
        $tea_area                       = $this->get_in_str_val("tea_area");//老师县市
        $stu_request_test_lesson_demand = $this->get_in_str_val("stu_request_test_lesson_demand");//试听需求
        $intention_level                = $this->get_in_int_val("intention_level");//上课意向
        $stu_request_test_lesson_time   = $this->get_in_str_val("stu_request_test_lesson_time");//试听时间
        $stu_test_paper                 = $this->get_in_str_val("test_paper");//试卷
        $test_stress                    = $this->get_in_int_val("test_stress");//应试压力
        $entrance_school_type           = $this->get_in_int_val("entrance_school_type");//升学目标
        $interest_cultivation           = $this->get_in_int_val("interest_cultivation");//趣味培养
        $extra_improvement              = $this->get_in_int_val("extra_improvement");//课外提高
        $habit_remodel                  = $this->get_in_int_val("habit_remodel");//习惯重塑
        $ass_test_lesson_type           = $this->get_in_int_val('ass_test_lesson_type');//分类
        $change_teacher_reason_type     = $this->get_in_int_val('change_teacher_reason_type');//换老师原因分类
        $url                            = $this->get_in_str_val('change_reason_url');//申请原因图片
        $green_channel_teacherid        = $this->get_in_int_val("green_channel_teacherid");//绿色通道
        $change_reason                  = trim($this->get_in_str_val('change_reason'));//申请原因
        // dd($province,$region,$city,$area,$tea_province,$tea_city,$tea_area);
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
        //update t_student_info
        $stu_arr=[
            "parent_name"           => $parent_name,
            "grade"                 => $grade,
            "gender"                => $gender,
            "school"                => $school,
            "editionid"             => $editionid,
            "province"              => $province,
            "city"                  => $city,
            "area"                  => $area,
            "region"                => $region,
        ];
        $this->t_student_info->field_update_list($userid,$stu_arr);
        //update t_seller_student_new
        $ss_arr=[
            "class_rank"            => $class_rank,
            "grade_rank"            => $grade_rank,
            "academic_goal"         => $academic_goal,
            "study_habit"           => $study_habit,
            "interests_and_hobbies" => $interests_and_hobbies,
            "character_type"        => $character_type,
            "need_teacher_style"    => $need_teacher_style,
            "test_stress"           => $test_stress,
            "entrance_school_type"  => $entrance_school_type,
            "interest_cultivation"  => $interest_cultivation,
            "extra_improvement"     => $extra_improvement,
            "habit_remodel"         => $habit_remodel,
        ];
        $this->t_seller_student_new->field_update_list($userid,$ss_arr);
        //update t_test_lesson_subject
        $tt_arr=[
            "subject"                        => $subject,
            "tea_province"                   => $tea_province,
            "tea_city"                       => $tea_city,
            "tea_area"                       => $tea_area,
            "stu_request_test_lesson_demand" => $stu_request_test_lesson_demand,
            "stu_request_test_lesson_time"   => $stu_request_test_lesson_time,
            "recent_results"                 => $recent_results,
            "advice_flag"                    => $advice_flag,
            "intention_level"                => $intention_level,
            "stu_test_paper"                 => $stu_test_paper,
            "ass_test_lesson_type"           => $ass_test_lesson_type,
        ];
        $ret= $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,$tt_arr);
        //update t_test_lesson_subject_require
        $require_arr = [
            "test_stu_request_test_lesson_demand" => $stu_request_test_lesson_demand,
            "curl_stu_request_test_lesson_time"   => $stu_request_test_lesson_time,
            "test_stu_grade"                      => $grade,
            "change_teacher_reason_type"          => $change_teacher_reason_type,
            "change_teacher_reason_img_url"       => $change_reason_url,
            "change_teacher_reason"               => $change_reason,
        ];
        $this->t_test_lesson_subject_require->field_update_list($require_id,$require_arr);

        return $this->output_succ();
    }

}
