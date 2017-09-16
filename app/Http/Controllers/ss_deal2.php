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




}
