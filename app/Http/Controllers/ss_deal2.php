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
        $seller_student_status = $item["seller_student_status"];

        //公海领取例子,拨打回流限制
        if($item["hand_get_adminid"] == E\Ehand_get_adminid::V_5 && !in_array($item['admin_revisiterid'],[831,973,60,898])){
            $ret = $this->t_tq_call_info->get_call_info_row_new($item["admin_revisiterid"],$phone,$item["admin_assign_time"]);
            if(!$ret){
                return $this->output_err($phone.'为公海领取的例子,请拨打后回流!');
            }
        }
        $ret_update = $this->t_book_revisit->add_book_revisit(
            $phone,
            "操作者:$account 状态: 回到公海 ",
            "system"
        );
        $test_subject_free_type=0;
        if ($seller_student_status==1) {
            $test_subject_free_type=3;
        }

        $this->t_test_subject_free_list->row_insert([
            "add_time" => time(NULL),
            "userid" =>   $item["userid"],
            "adminid" => $this->get_account_id(),
            "test_subject_free_type" => $test_subject_free_type,
        ],false,true);

        $this->t_seller_student_new->set_user_free($userid);
        $hand_get_adminid = 0;
        $orderid = $this->t_order_info->get_orderid_by_userid($userid,$this->get_account());
        if($orderid>0){
            $hand_get_adminid = $item["hand_get_adminid"];
        }
        $this->t_seller_student_new->field_update_list($userid,[
            "free_adminid" => $this->get_account_id(),
            "free_time" => time(),
            "hand_free_count" => $item['hand_free_count']+1,
            "hand_get_adminid" => $hand_get_adminid,
        ]);
        return $this->output_succ();
    }
    public function set_user_free_new () {
        $userid_list = $this->get_in_str_val('userid',-1);
        foreach ($userid_list as $key => $value) {
            //$userid=$this->get_in_userid();
            $userid = $value;
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
        }

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
        $learning_situation             = $this->get_in_str_val('learning_situation');//学情反馈
        $new_demand_flag                = $this->get_in_int_val('new_demand_flag',0);//试听需求新版本标识
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

        if(!$stu_request_test_lesson_time){ return $this->output_err("请选择试听时间"); }

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

        if($new_demand_flag){//update_t_seller_student_new
            $this->t_seller_student_new->field_update_list($userid,[
                'new_demand_flag'=>$new_demand_flag,
            ]);
        }

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
                'learning_situation'             => $learning_situation,
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
        $learning_situation             = $this->get_in_str_val('learning_situation');//学情反馈
        $new_demand_flag                = $this->get_in_int_val('new_demand_flag',0);//试听需求新版本标识
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
            'new_demand_flag'       => $new_demand_flag,
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
            'learning_situation'             => $learning_situation,
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

    public function set_part_time_teacher(){
        $phone                    = $this->get_in_str_val('phone');
        $app_id = $this->t_teacher_lecture_appointment_info->get_id_by_phone($phone);
        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        $train_through_new = $this->t_teacher_info->get_train_through_new($teacherid);
        if($train_through_new==1){
            return $this->output_err('已入职老师请从其他途径更改');
        }
        $this->t_teacher_lecture_appointment_info->field_update_list($app_id,[
           "full_time"=>0
        ]);
        $this->t_teacher_info->field_update_list($teacherid,[
           "teacher_type" =>0
        ]);
        return $this->output_succ();

    }

    public function add_product_info(){
        $feedback_adminid = $this->get_in_int_val('feedback_id');
        $describe   = $this->get_in_str_val('describe');
        $lesson_url = $this->get_in_str_val('lesson_url');
        $reason     = $this->get_in_str_val('reason');
        $solution   = $this->get_in_str_val('solution');
        $student_id = $this->get_in_str_val('student_id');
        $teacher_id = $this->get_in_str_val('teacher_id');
        $deal_flag  = $this->get_in_int_val('deal_flag');
        $remark     = $this->get_in_str_val('remark');
        $record_adminid = $this->get_account_id();

        $ret = $this->t_product_feedback_list->row_insert([
            "feedback_adminid" => $feedback_adminid,
            "record_adminid"   => $record_adminid,
            "describe_msg"     => $describe,
            "lesson_url"   => $lesson_url,
            "reason"       => $reason,
            "solution"     => $solution,
            "student_id"   => $student_id,
            "teacher_id"   => $teacher_id,
            "deal_flag"    => $deal_flag,
            "remark"       => $remark,
            "create_time"  => time()
        ]);

        return $this->output_succ();
    }


    public function del_product_info(){
        $id = $this->get_in_int_val('id');
        $this->t_product_feedback_list->row_delete($id);
        return $this->output_succ();
    }

    public function get_product_info(){
        $id = $this->get_in_int_val('id');
        $ret_list = $this->t_product_feedback_list->get_feedback_info($id);

        $ret_list['stu_agent_simple'] = get_machine_info_from_user_agent($ret_list["stu_agent"] );
        $ret_list['tea_agent_simple'] = get_machine_info_from_user_agent($ret_list["tea_agent"] );
        \App\Helper\Utils::unixtime2date_for_item($ret_list,"create_time");
        $ret_list['feedback_nick'] = $this->cache_get_account_nick($ret_list['feedback_adminid']);
        $ret_list['record_nick']   = $this->cache_get_account_nick($ret_list['record_adminid']);
        $ret_list['deal_flag_str'] = E\Eboolean::get_color_desc($ret_list['deal_flag']);

        return $this->output_succ(["data"=>$ret_list]);
    }

    public function update_product_info(){
        $id = $this->get_in_int_val('id');
        $describe   = $this->get_in_str_val('describe');
        $lesson_url = $this->get_in_str_val('lesson_url');
        $reason     = $this->get_in_str_val('reason');
        $solution   = $this->get_in_str_val('solution');
        $student_id = $this->get_in_str_val('student_id');
        $teacher_id = $this->get_in_str_val('teacher_id');
        $deal_flag  = $this->get_in_int_val('deal_flag');
        $remark     = $this->get_in_str_val('remark');
        $record_adminid = $this->get_account_id();
        $feedback_adminid = $this->get_in_int_val('feedback_id');

        $ret = $this->t_product_feedback_list->field_update_list($id,[
            "feedback_adminid" => $feedback_adminid,
            "record_adminid"   => $record_adminid,
            "describe_msg"     => $describe,
            "lesson_url"   => $lesson_url,
            "reason"       => $reason,
            "solution"     => $solution,
            "student_id"   => $student_id,
            "teacher_id"   => $teacher_id,
            "deal_flag"    => $deal_flag,
            "remark"       => $remark,
            "create_time"  => time()
        ]);

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

}
