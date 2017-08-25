<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class lesson extends TeaWxController
{
    public function __construct(){
        // session("teacher_wx_use_flag",1);  // 本地测试时使用
    }

    public function update_comment_common() { // 协议编号 1003
        $teacherid          = $this->get_teacherid();
        $lessonid           = $this->get_in_int_val('lessonid');
        $now                = time(NULL);
        $total_judgement    = $this->get_in_int_val("total_judgement");
        $homework_situation = $this->get_in_str_val("homework_situation");
        $content_grasp      = $this->get_in_str_val("content_grasp");
        $lesson_interact    = $this->get_in_str_val("lesson_interact");
        $teacher_message_str = $this->get_in_str_val("teacher_message");
        $stu_comment        = $this->get_in_str_val("stu_comment");

        $point_note_list_arr = [];
        $teacher_message_arr = json_decode($teacher_message_str,true);
        foreach($teacher_message_arr as $index=> $item){
            $point_note_list_arr[] = [
                'point_name'     => $index,
                'point_stu_desc' => $item,
            ];
        }

        if($teacher_message_str && $stu_comment ){
            $stu_performance = [
                "total_judgement"    => $total_judgement,
                "homework_situation" => $homework_situation,
                "content_grasp"     => $content_grasp,
                "lesson_interact"   => $lesson_interact,
                "point_note_list"   => $point_note_list_arr,
                "stu_comment"       => $stu_comment
            ];
        }elseif($teacher_message_str && !$stu_comment) {
            $stu_performance = [
                "total_judgement"    => $total_judgement,
                "homework_situation" => $homework_situation,
                "content_grasp"    => $content_grasp,
                "lesson_interact"  => $lesson_interact,
                "point_note_list"  => $point_note_list_arr
            ];
        }else {
            $stu_performance = [
                "total_judgement"   => $total_judgement,
                "homework_situation"=> $homework_situation,
                "content_grasp"   => $content_grasp,
                "lesson_interact" => $lesson_interact,
                "stu_comment"     => $stu_comment
            ];
        }

        if($stu_performance) {
            $stu_performance_str = json_encode($stu_performance);
        }


        $this->t_lesson_info_b2->set_stu_performance($lessonid, $teacherid, $stu_performance_str,3);

        $comment_date = time();
        $com_state = $this->t_lesson_info_b2->set_comment_status($lessonid,$comment_date);

        if($com_state){
            return $this->output_succ(['time'=>$com_state]);
        }
    }

    public function get_teacher_money_type() { //协议编号 1018
        $teacherid = $this->get_teacherid();
        $teacher_money_type= $this->t_teacher_info->get_teacher_money_type($teacherid);
        $level= $this->t_teacher_info->get_level($teacherid);

        $type=0;
        if($teacher_money_type == 0 && $level == 3) {
            $type=0;
        }else if($teacher_money_type == 2) {
            $type=1;
        }else if($teacher_money_type == 4) {
            $type=2;
        }else if($teacher_money_type == 5) {
            $type=4;
        }else{
            $type=3;
        }

        return $this->output_succ(["type"=>$type] );
    }

    public function get_comment_list(){ //协议编号 1002

        $teacherid  = $this->get_teacherid();
        $page_num   = $this->get_in_page_num();
        $type       = $this->get_in_int_val('type');

        $type_str   = "0,1,2,3";
        $time_limit = 120;

        if ($type == 0) {
            $type_str = "2";
        } else if($type == 1){
            $type_str = "0,1,3";
        }else {
            $type_str = "0,1,2,3";
        }


        $now_time   = time(NULL);
        $now_time_int = strtotime(date('Y-m-d',$now_time))+86400;

        $ret_info = $this->t_lesson_info_b2->get_comment_list_by_page($teacherid, 0, $now_time_int, $type_str, $page_num);
        // dd($ret_info);
        $list = $ret_info['list'];
        if(empty($ret_info['list'])){
            return $this->output_succ(['list'=>[]]);
        }else{
            foreach($list as $index => &$item){
                if($type==0){
                    if($index == 0){
                        $comment_time_ex = $item['lesson_end']+60*$time_limit-time(NULL);
                        if($comment_time_ex<0){
                            $comment_time_ex = 0;
                        }
                    }else{
                        if($list[$index-1]['lesson_start']<$time_limit*60+$list[$index]['lesson_start']){
                            $comment_time_ex = $item['lesson_end']+60*$time_limit-time(NULL);
                            if($comment_time_ex<0){
                                $comment_time_ex = 0;
                            }
                        }
                    }

                    if($item['tea_rate_time']>0){
                        $comment_time_ex = 0;
                    }

                    $item["need_comment_time"] = $now_time-$item["lesson_end"] >= 0? $comment_time_ex:0;
                }else{

                    if ($now_time-$item['lesson_end'] >= 0 && $now_time-$item['lesson_end'] < 2*24*60*60 && $item["tea_rate_time"] <= 0) {
                        $comment_time_ex2 = $item['lesson_end']-$now_time+2*24*60*60;
                    }else {
                        $comment_time_ex2 = 0;
                    }
                    if($item["tea_rate_time"] > 0) {
                        $comment_time_ex = 0;
                    }

                    $item['need_comment_time'] = $comment_time_ex2;


                }
            }
            return $this->output_succ(['list'=>$list]);
        }

    }

    public function check_comment_status(){ // 协议编号 1004
        $lessonid = $this->get_in_int_val('lessonid');
        if ($lessonid == 0) {
            return $this->output_err("lessonid not exist");
        }

        $time = $this->t_lesson_info->check_comment_status($lessonid);

        return $this->output_succ(['time'=>$time]);
    }

    public function get_knowledge () { // 协议编号 1012
        $lessonid = $this->get_in_int_val('lessonid');
        if ($lessonid == 0) {
            return $this->output_err("lessonid not exist");
        }

        $lesson_intro = $this->t_lesson_info->get_lesson_intro($lessonid);

        if($lesson_intro){
            return $this->output_succ(['lesson_intro'=>$lesson_intro]);
        }else{
            return $this->output_err('知识点不存在!');
        }
    }

    public function update_pre_lesson_status(){ // 协议编号 1007
        $teacherid = $this->get_teacherid();
        $type      = $this->get_in_int_val("type");

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            'need_test_lesson_flag'=>$type
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err('试听状态不存在!');
        }
    }

    public function get_comment_common(){  // 协议编号:1006
        $lessonid = $this->get_in_int_val('lessonid');
        if ($lessonid == 0) {
            return $this->output_err("lessonid not exist");
        }

        $stu_per = $this->t_lesson_info->get_common_stu_performance($lessonid);
        $stu_per_arr = json_decode($stu_per);
        if($stu_per){
            return $this->output_succ(['data'=>$stu_per_arr]);

        }else{
            return $this->output_err('普通评价不存在!');
        }

    }

    public function get_lesson_cost_list(){ // 协议编号 1014
        $teacherid = $this->get_teacherid();
        $type      = $this->get_in_int_val("type");
        $start     = $this->get_in_int_val("start");
        $end       = $this->get_in_int_val("end");

        $lesson_type = '';
        if($type == 0) {
            $lesson_type = "0,1,3";
        }else if($type == 1) {
            $lesson_type = "2";
        }else {
            $lesson_type ="0,1,2,3";
        }

        $ret = $this->t_lesson_info_b2->get_lesson_cost_list($teacherid,$lesson_type,$start,$end);

        if($ret){
            foreach($ret as &$item){
                $item['lesson_content'] = ($item['lesson_count']/100).'课时课时费-'.$item['nick'];
                $item['count_name']     = ($item['lesson_count']/100).'课时';
                unset($item['nick']);
                unset($item['lesson_count']);
            }
            return $this->output_succ(['data'=>$ret]);
        }else{
            return $this->output_succ(['data'=>[]]);

            // return $this->output_err('课程消耗不存在!');
        }
    }

    public function get_salary_detail_list(){ // 协议编号:1015

        $teacherid   = $this->get_teacherid();
        $start_time  = $this->get_in_int_val("start");
        $end_time    = $this->get_in_int_val("end");

        \App\Helper\Utils::logger("teacherid12:$teacherid");

        $url = "http://admin.yb1v1.com/teacher_money/get_teacher_money_list";
        $post_data = array(
            "teacherid" => $teacherid,
            "start_time" => $start_time,
            "end_time"   => $end_time
        );
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        \App\Helper\Utils::logger("output1:$output");


        $ret_arr = json_decode($output,true);


        if($ret_arr && (!empty($ret_arr['all_reward_list']) || !empty($ret_arr['data']))){
            return $this->output_succ(['data'=>$ret_arr['data'],'all_reward_list'=>$ret_arr['all_reward_list']]);
        }else{
            return $this->output_succ(['data'=>[],'all_reward_list'=>[]]);
        }


       // if(!$ret_arr){
       //     return $this->output_succ(['data'=>[],'all_reward_list'=>[]]);
       //  }else if($ret_arr!=null && !empty($ret_arr['data'])){
       //     return $this->output_succ(['data'=>$ret_arr['data'],'all_reward_list'=>$ret_arr['all_reward_list']]);
       //  }else{
       //     // return $this->output_err("工资明细获取失败!");
       //     return $this->output_succ(['data'=>[],'all_reward_list'=>[]]);

       //  }

    }

    public function update_comment_pre_listen(){ // 协议编号:1011
        $teacherid    = $this->get_teacherid();
        $comment_date = time(NUll);
        $lessonid     = $this->get_in_int_val('lessonid',0);

        if ($lessonid == 0) {
            return $this->output_err("lessonid not exist");
        }

        $stu_lesson_content   = $this->get_in_str_val("stu_lesson_content");
        $stu_lesson_status    = $this->get_in_str_val("stu_lesson_status");
        $stu_study_status     = $this->get_in_str_val("stu_study_status");
        $stu_advantages       = $this->get_in_str_val("stu_advantages");
        $stu_disadvantages    = $this->get_in_str_val("stu_disadvantages");
        $stu_lesson_plan      = $this->get_in_str_val("stu_lesson_plan");
        $stu_teaching_direction = $this->get_in_str_val("stu_teaching_direction");
        $stu_advice           = $this->get_in_str_val("stu_advice");

        $requireid = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);


        if($requireid>0){
           $ret_info = $this->t_test_lesson_subject_require->set_info( $stu_lesson_content, $stu_lesson_status,
                                                            $stu_study_status,$stu_advantages,
                                                            $stu_disadvantages,$stu_lesson_plan,
                                                            $stu_teaching_direction,$stu_advice,$requireid
            );

            $ret_state = $this->t_lesson_info_b2->set_comment_status($lessonid, $comment_date);

            return $this->output_succ(['time'=>$ret_state]);
        }else{
            return $this->output_err('requireid不存在');
        }

    }


    public function get_comment_pre_listen(){ // 协议编号: 1005
        $lessonid = $this->get_in_int_val('lessonid');
        if($lessonid == 0){
            return $this->output_err('lessonid not exist!');
        }


        $requireid = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);
        \App\Helper\Utils::logger("shiting2:$requireid,lessonid:$lessonid");

        if($requireid>0){
            $ret = $this->t_test_lesson_subject_require->get_info($requireid);

            if($ret){
                \App\Helper\Utils::logger("shiting122:".$this->output_succ(['list'=>$ret]));

                return $this->output_succ(['list'=>$ret]);
            }else{
                return $this->output_err("试听评价不存在!");
            }
        }else{
            return $this->output_err('requireid不存在!');
        }
    }

    public function lesson_require_list(){ //1022

        $require_id_list = $this->get_in_int_list("require_id_list");
        $teacherid       = $this->get_teacherid();

        $list = [];
        $ret = $this->t_test_lesson_subject_require->get_list_by_id_list($require_id_list);

        foreach($ret as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eregion_version::set_item_value_str($item, "editionid");
            if($item['teacherid']){
                if($item['teacherid'] == $teacherid){
                    $item['check_flag_str'] = '已抢';
                    $item['check_flag']     = 1;
                }
            }
        }

        return $this->output_succ(['list'=>$ret]);
    }


    public function lesson_require_obtain(){ //1023

    }



    public function update_comment_pre_listen_new(){ // 新版试听课评价 标号3002
        $teacherid    = $this->get_teacherid();
        $comment_date = time(NUll);
        $lessonid     = $this->get_in_int_val('lessonid',-1);

        if ($lessonid == -1) {
            return $this->output_err("lessonid not exist");
        }

        $stu_lesson_content   = $this->get_in_str_val("stu_lesson_content");
        $stu_lesson_status    = $this->get_in_str_val("stu_lesson_status");
        $stu_study_status     = $this->get_in_str_val("stu_study_status");
        $stu_advantages       = $this->get_in_str_val("stu_advantages");
        $stu_disadvantages    = $this->get_in_str_val("stu_disadvantages");
        $stu_lesson_plan      = $this->get_in_str_val("stu_lesson_plan");
        $stu_advice           = $this->get_in_str_val("stu_advice");
        $stu_total_judgement    = $this->get_in_int_val("stu_total_judgement",-1); // 新增字段 学生星级

        $requireid = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);


        if($requireid>0){
            $ret_info = $this->t_test_lesson_subject_require->field_update_list($requireid,[
                "stu_lesson_content" => $stu_lesson_content,
                "stu_lesson_status"  => $stu_lesson_status,
                "stu_study_status"   => $stu_study_status,
                "stu_advantages"     => $stu_advantages,
                "stu_disadvantages"  => $stu_disadvantages,
                "stu_lesson_plan"    => $stu_lesson_plan,
                "stu_total_judgement" => $stu_total_judgement,
                "stu_advice"          => $stu_advice
            ]
            );

            $ret_state = $this->t_lesson_info_b2->set_comment_status($lessonid, $comment_date);

            return $this->output_succ(['time'=>$ret_state]);
        }else{
            return $this->output_err('requireid不存在');
        }



    }


    public function update_comment_common_new() { // 协议编号 3001

        $teacherid          = $this->get_teacherid();
        $lessonid           = $this->get_in_int_val('lessonid',-1);
        $comment_date       = time(NULL);
        $total_judgement    = $this->get_in_int_val("total_judgement",-1);
        $homework_situation = $this->get_in_str_val("homework_situation",'');
        $content_grasp      = $this->get_in_str_val("content_grasp",'');
        $lesson_interact    = $this->get_in_str_val("lesson_interact",'');


        $stu_performance = $this->get_in_str_val('stu_performance',''); // 学生表现
        $stu_improve = $this->get_in_str_val('stu_improve',''); // 需要改进
        $stu_comment = $stu_performance.'<br>'.$stu_improve; // 合并整体评价

        $teacher_message_str = $this->get_in_str_val("teacher_message",'');
        $point_note_list_arr = [];
        $teacher_message_arr = json_decode($teacher_message_str,true);
        foreach($teacher_message_arr as $index=> $item){
            $point_note_list_arr[] = [
                'point_name'     => $index,
                'point_stu_desc' => $item,
            ];
        }

        if($teacher_message_str && $stu_comment ){
            $stu_performance = [
                "total_judgement"    => $total_judgement,
                "homework_situation" => $homework_situation,
                "content_grasp"     => $content_grasp,
                "lesson_interact"   => $lesson_interact,
                "point_note_list"   => $point_note_list_arr,
                "stu_comment"       => $stu_comment
            ];
        }elseif($teacher_message_str && !$stu_comment) {
            $stu_performance = [
                "total_judgement"    => $total_judgement,
                "homework_situation" => $homework_situation,
                "content_grasp"    => $content_grasp,
                "lesson_interact"  => $lesson_interact,
                "point_note_list"  => $point_note_list_arr
            ];
        }else {
            $stu_performance = [
                "total_judgement"   => $total_judgement,
                "homework_situation"=> $homework_situation,
                "content_grasp"   => $content_grasp,
                "lesson_interact" => $lesson_interact,
                "stu_comment"     => $stu_comment
            ];
        }

        if(!empty($stu_performance)) {
            $stu_performance_str = json_encode($stu_performance);

            $this->t_lesson_info_b2->set_stu_performance($lessonid, $teacherid, $stu_performance_str,3);

            $com_state = $this->t_lesson_info_b2->set_comment_status($lessonid,$now);

            if($com_state){
                return $this->output_succ(['time'=>$com_state]);
            }
        }
    }








}