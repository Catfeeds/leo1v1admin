<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class lesson extends TeaWxController
{
    use CacheNick;

    public function __construct(){
        // session("teacher_wx_use_flag",1);  // 本地测试时使用
    }

    public function update_comment_common() { // 协议编号 1003
        $teacherid           = $this->get_teacherid();
        $lessonid            = $this->get_in_int_val('lessonid');
        $now                 = time(NULL);
        $total_judgement     = $this->get_in_int_val("total_judgement");
        $homework_situation  = $this->get_in_str_val("homework_situation");
        $content_grasp       = $this->get_in_str_val("content_grasp");
        $lesson_interact     = $this->get_in_str_val("lesson_interact");
        $teacher_message_str = $this->get_in_str_val("teacher_message");
        $stu_comment         = $this->get_in_str_val("stu_comment");

        $point_note_list_arr = [];
        if(is_array($teacher_message_str)){
            $teacher_message_arr = $teacher_message_str;
        }else{
            $teacher_message_arr = json_decode($teacher_message_str,true);
        }
        if(!empty($teacher_message_arr)){
            foreach($teacher_message_arr as $index=> $item){
                $point_note_list_arr[] = [
                    'point_name'     => $index,
                    'point_stu_desc' => $item,
                ];
            }
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
        }else{
            return $this->output_err("更新失败,请稍后重试!");
        }
    }

    public function get_teacher_money_type() { //协议编号 1018
        $type =$this->get_in_int_val("type");
        if($type==1){
            $teacherid = $this->get_in_int_val("teacherid");
        }else{
            $teacherid = $this->get_teacherid();
        }

        $teacher_money_type = $this->t_teacher_info->get_teacher_money_type($teacherid);
        $level = $this->t_teacher_info->get_level($teacherid);
        $teacher_type= $this->t_teacher_info->get_teacher_type($teacherid);

        $type=0;
        if($teacher_money_type == E\Eteacher_money_type::V_0 && !in_array($teacher_type,[E\Eteacher_type::V_3,E\Eteacher_type::V_4])) {
            $type=5;
        }else if($teacher_money_type == E\Eteacher_money_type::V_1) {
            $type=5;
        }else if($teacher_money_type == E\Eteacher_money_type::V_2) {
            $type=5;
        }else if($teacher_money_type == E\Eteacher_money_type::V_4) {
            $type=5;
        }else if($teacher_money_type == E\Eteacher_money_type::V_5) {
            $type=4;
        }else if($teacher_money_type == E\Eteacher_money_type::V_6){
            $type=5;
        }else if($teacher_money_type == E\Eteacher_money_type::V_7){
            $type=6;
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

        // $teacherid   = $this->get_teacherid();

        $teacherid   = $this->getTeacherid();
        $start_time  = $this->get_in_int_val("start");
        $end_time    = $this->get_in_int_val("end");



        if(!$teacherid){
            return $this->output_err('登录已过期,请您从[个人中心]-[我的收入]中查看!');
        }

        if($teacherid == 225427||$teacherid==225427){
            $output = $this->get_teacher_money_list($start_time,$end_time,$teacherid);
        }else{
            $url = "http://admin.leo1v1.com/teacher_money/get_teacher_money_list";
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
        }
        // $url = "http://admin.leo1v1.com/teacher_money/get_teacher_money_list";
        // $post_data = array(
        //     "teacherid" => $teacherid,
        //     "start_time" => $start_time,
        //     "end_time"   => $end_time
        // );
        // $ch = curl_init();
        // curl_setopt($ch,CURLOPT_URL, $url);
        // curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch,CURLOPT_POST,1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // $output = curl_exec($ch);
        // curl_close($ch);


        $ret_arr = json_decode($output,true);


        /**
         * @需求 每月5号24:00 之后 关闭上月课程申诉通道
         * @作者 James
         **/
        $limit_time = strtotime(date('Y-m-1'));
        $six_time   = $limit_time + 5*86400;
        $now = time();

        foreach($ret_arr['data'] as &$item){
            $item['is_forbid'] = "0";
            $lesson_end = $this->t_lesson_info->get_lesson_end($item['lessonid']);
            if(($lesson_end<$limit_time) && ($six_time<$now)){
                $item['is_forbid'] = "1";
            }
        }
        \App\Helper\Utils::logger('data_gongzi:'.$teacherid.'get_salary_detail_list_james_return_data:'.count($ret_arr['data']).',all_reward_list:'.count($ret_arr['all_reward_list']));


        if($ret_arr && (!empty($ret_arr['all_reward_list']) || !empty($ret_arr['data']))){
            \App\Helper\Utils::logger("success_james_111");

            return $this->output_succ(['data'=>$ret_arr['data'],'all_reward_list'=>$ret_arr['all_reward_list']]);
        }else{
            \App\Helper\Utils::logger("error_james_111");
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


    public function get_teacher_money_list($start_time,$end_time,$teacherid){
        $teacherid = $this->get_in_int_val("teacherid");
        if(!$teacherid){
            return $this->output_err("老师id错误!");
        }
        // $start_time = $this->get_in_int_val("start_time",strtotime(date("Y-m-01",time())));
        // $end_time   = $this->get_in_int_val("end_time",strtotime("+1 month",$start_time));

        $simple_info  = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_type = $simple_info['teacher_type'];

        //拉取上个月的课时信息
        $last_lesson_count = $this->get_last_lesson_count_info($start_time,$end_time,$teacherid);
        $time_list   = [];
        $lesson_list = [];
        $lesson_info = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$start_time,$end_time);
        $check_num   = [];
        if(!empty($lesson_info)){
            foreach($lesson_info as $key=>&$val){
                $base_list   = [];
                $reward_list = [];
                $full_list   = [];

                $val['lesson_base']        = "0";
                $val['lesson_reward']      = "0";
                $val['lesson_full_reward'] = "0";
                $lesson_count = $val['lesson_count']/100;
                if($val['confirm_flag'] != 2){
                    if($val['lesson_type'] != 2){
                        $val['money']       = $this->get_teacher_base_money($teacherid,$val);
                        $val['lesson_base'] = $val['money']*$lesson_count;

                        $lesson_reward = $this->get_lesson_reward_money(
                            $last_lesson_count,$val['already_lesson_count'],$val['teacher_money_type'],$teacher_type,$val['type']
                        );

                        $val['lesson_reward'] = $lesson_reward*$lesson_count;
                        $reward_list['type']  = 2;
                        $reward_list['info']  = "累计课时奖励";
                        $reward_list['money'] = strval($val['lesson_reward']);
                    }else{
                        if($val['fail_greater_4_hour_flag']==0 &&
                           $val['test_lesson_fail_flag']==101 || $val['test_lesson_fail_flag']==102){
                            $val['lesson_base'] = "0";
                        }else{
                            $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                                $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
                            );
                        }
                        $val['lesson_reward'] = "0";
                    }

                    if($val['lesson_base']!=0){
                        $base_list['type']  = 1;
                        $base_list['info']  = "老师基本工资";
                        $base_list['money'] = strval($val['lesson_base']);
                    }

                    $val['lesson_full_reward'] = 0;
                    if($val['lesson_full_reward']>0){
                        $full_list['type']  = 2;
                        $full_list['info']  = "全勤奖";
                        $full_list['money'] = $val['lesson_full_reward'];
                    }

                    if(!empty($base_list)){
                        $val['list'][] = $base_list;
                    }
                    if(!empty($reward_list)){
                        $val['list'][] = $reward_list;
                    }
                    if(!empty($full_list)){
                        $val['list'][] = $full_list;
                    }
                }

                $this->get_lesson_cost_info($val,$check_num);
                $lesson_price = $val['lesson_base']+$val['lesson_reward']+$val['lesson_full_reward']-$val['lesson_cost'];
                $lesson_list[$key]['lesson_base']   = strval($val['lesson_base']);
                $lesson_list[$key]['lesson_reward'] = strval($val['lesson_reward']+$val['lesson_full_reward']);
                $lesson_list[$key]['lesson_cost']   = $val['lesson_cost'];
                $lesson_list[$key]['lesson_price']  = strval($lesson_price);
                $lesson_list[$key]['stu_nick']      = $val['stu_nick'];
                $lesson_list[$key]['lesson_time']   = date("m.d H:i",$val['lesson_start'])."-".date("H:i",$val['lesson_end']);
                $lesson_list[$key]['late_status']   = $val['deduct_come_late'];
                $lesson_list[$key]['lesson_type']   = $val['lesson_type'];
                $lesson_list[$key]['lessonid']      = $val['lessonid'];
                if(isset($val['list'])){
                    $lesson_list[$key]['list'] = $val['list'];
                }
                $time_list[$key]['time'] = $val['lesson_start'];
            }
            array_multisort($time_list,SORT_DESC,$lesson_list);
        }

        $teacher_reward_list = $this->t_teacher_money_list->get_teacher_honor_money_list($teacherid,$start_time,$end_time);
        $reward_ex['name']         = "奖金";
        $reward_compensate['name'] = "补偿";
        $reward_reference['name']  = "推荐";
        foreach($teacher_reward_list as $r_val){
            $reward['add_time_str'] = \App\Helper\Utils::unixtime2date($r_val["add_time"],"Y-m-d");
            $reward['money']        = (float)$r_val['money']/100;
            $reward['money_info']   = E\Ereward_type::get_desc($r_val['type']);
            if(in_array($r_val['type'],[E\Ereward_type::V_1,E\Ereward_type::V_2,E\Ereward_type::V_5])){
                \App\Helper\Utils::check_isset_data($reward_ex['price'],$reward['money']);

                if($r_val['type']==E\Ereward_type::V_2 && $r_val['userid']>0){
                    $stu_nick = $this->cache_get_student_nick($r_val['userid']);
                    $reward['money_info'] .= "|".$stu_nick;
                }

                $reward["type"] = 1;
                $reward_ex["reward_list"][] = $reward;
            }elseif(in_array($r_val['type'],[E\Ereward_type::V_3,E\Ereward_type::V_4])){
                \App\Helper\Utils::check_isset_data($reward_compensate['price'],$reward['money']);
                $reward["type"] = 2;
                $reward_compensate["reward_list"][] = $reward;
            }elseif(in_array($r_val['type'],[E\Ereward_type::V_6])){
                \App\Helper\Utils::check_isset_data($reward_reference['price'],$reward['money']);
                $reward['money_info'] = $this->t_teacher_info->get_nick($r_val['money_info']);
                $reward["type"] = 1;
                $reward_reference["reward_list"][] = $reward;
            }
        }
        $this->get_array_data_by_count($all_reward_list,$reward_ex);
        $this->get_array_data_by_count($all_reward_list,$reward_compensate);
        $this->get_array_data_by_count($all_reward_list,$reward_reference);
        $arr['data'] = $lesson_list;
        $arr['all_reward_list'] = $all_reward_list;
        return $arr;
        return $this->output_succ(["data"=>$lesson_list,"all_reward_list"=>$all_reward_list]);
    }


    public function get_teacher_info_for_total_money($teacherid){
        $info  = $this->t_teacher_info->get_teacher_info($teacherid);
        $level_str = \App\Helper\Utils::get_teacher_level_str($info);

        $teacher_type = $info['teacher_type']==32?32:0;
        $bank_status  = $info['bankcard']==""?"未绑定":"已绑定";
        $teacher_info = [
            "nick"         => $info['nick'],
            "face"         => $info['face'],
            "level"        => $level_str,
            "teacher_type" => $teacher_type,
            "bank_status"  => $bank_status,
            "train_through_new_time" => $info['train_through_new_time']
        ];
        return $teacher_info;
    }




    public function get_teacher_total_money($type,$show_type,$teacherid){

        if(!$teacherid){
            return $this->output_err("老师id错误!");
        }

        $this->t_lesson_info->switch_tongji_database();
        if($type=="wx"){
            $start_time = $this->t_lesson_info->get_first_lesson_start($teacherid);
            $node_time  = strtotime("2016-12-1");
            if($start_time<$node_time){
                $start_time = $node_time;
            }
            $now_time = strtotime("+1 month",strtotime(date("Y-m-01",time())));
        }elseif($type=="admin"){
            $default_date = date("Y-m-d",time());
            $start_time   = strtotime($this->get_in_str_val("start_time",$default_date));
            $end_time     = strtotime($this->get_in_str_val("end_time",$default_date));
            $now_time     = strtotime("+1 day",strtotime($end_time));
            $teacher_type = $this->t_teacher_info->get_teacher_type($teacherid);
            $check_flag   = $this->check_full_time_teacher($teacherid,$teacher_type);
            if($check_flag){
                $now_time   = $start_time;
                $start_time = strtotime("-1 month",$start_time);
            }

            if($start_time=='' || $now_time==''){
                return $this->output_err("时间错误!");
            }
        }else{
            return $this->output_err("参数错误!");
        }

        $teacher_info = $this->get_teacher_info_for_total_money($teacherid);
        $list = $this->get_teacher_lesson_money_list($teacherid,$start_time,$now_time,$show_type);

        return $this->output_succ([
            "teacher_info" => $teacher_info,
            "data"         => $list,
        ]);
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
        $now          = time(NULL);
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
        $stu_total_judgement  = $this->get_in_int_val("stu_total_judgement",-1); // 新增字段 学生星级
        $requireid = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);
        $tea_nick  = $this->cache_get_teacher_nick($teacherid);

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
            ]);

            $ret_state = $this->t_lesson_info_b2->set_comment_status($lessonid, $now);
            $this->t_lesson_info_b2->field_update_list($lessonid, [
                "ass_comment_audit" => 3 # 默认通过
            ]);
            /**
                Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI
                {{first.DATA}}
                课程名称：{{keyword1.DATA}}
                课程时间：{{keyword2.DATA}}
                学生姓名：{{keyword3.DATA}}
                {{remark.DATA}}

                课程反馈通知
                x月x日

                xx:xx的xx课xx老师已经提交了课程评价
                课程名称：{课程名称}
                课程时间：xx-xx xx:xx~xx:xx
                学生姓名：xxx
                可登录学生端或升学帮查看详情，谢谢！
             */
            $lesson_info = $this->t_lesson_info_b2->get_lesson_info_by_lessonid($lessonid);

            $subject_str  = E\Esubject::get_desc($lesson_info['subject']);
            $lesson_begin = date('H:i',$lesson_info['lesson_start']);
            if($ret_info){
                $data_par = [
                    'first'     => "$lesson_begin 的 $subject_str 课 $tea_nick 老师已经提交了课程评价",
                    'keyword1'  => " $subject_str ",
                    'keyword2'  => date('Y-m-d H:i',$lesson_info['lesson_start']).' ~ '.date('H:i',$lesson_info['lesson_end']),
                    'keyword3'  => $lesson_info['stu_nick'],
                    'remark'    => ' 可登录学生端或升学帮查看详情，谢谢！'
                ];

                $wx  = new \App\Helper\Wx();
                $template_id_parent = 'Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI';
                if($lesson_info['wx_openid']){
                    $wx->send_template_msg($lesson_info['wx_openid'],$template_id_parent,$data_par ,'');
                }
            }

            return $this->output_succ(['time'=>$ret_state]);
        }else{
            return $this->output_err('requireid不存在');
        }
    }

    public function update_comment_common_new() { // 协议编号 3001
        $teacherid          = $this->get_in_int_val('teacherid');
        $lessonid           = $this->get_in_int_val('lessonid',-1);
        $now                = time(NULL);
        $total_judgement    = $this->get_in_int_val("total_judgement",-1);
        $homework_situation = $this->get_in_str_val("homework_situation",'');
        $content_grasp      = $this->get_in_str_val("content_grasp",'');
        $lesson_interact    = $this->get_in_str_val("lesson_interact",'');
        $stu_comment_str    = $this->get_in_str_val("stu_comment");
        $teacher_message_str = $this->get_in_str_val("teacher_message",'');

        $stu_common_info_arr = [];
        if(is_array($stu_comment_str)){
            $stu_comment_arr = $stu_comment_str;
        }else{
            $stu_comment_arr = json_decode($stu_comment_str,true);
        }

        foreach($stu_comment_arr as $index=> $item){
            $stu_common_info_arr[] = [
                'stu_tip'  => $index,
                'stu_info' => $item,
            ];
        }

        $point_note_list_arr = [];
        if(is_array($teacher_message_str)){
            $teacher_message_arr = $teacher_message_str;
        }else{
            $teacher_message_arr = json_decode($teacher_message_str,true);
        }

        foreach($teacher_message_arr as $index=> $item){
            $point_note_list_arr[] = [
                'point_name'     => $index,
                'point_stu_desc' => $item,
            ];
        }

        if($teacher_message_str && $stu_comment_str ){
            $stu_performance = [
                "total_judgement"    => $total_judgement,
                "homework_situation" => $homework_situation,
                "content_grasp"     => $content_grasp,
                "lesson_interact"   => $lesson_interact,
                "point_note_list"   => $point_note_list_arr,
                "stu_comment"       => $stu_common_info_arr
            ];
        }elseif($teacher_message_str && !$stu_comment_str) {
            $stu_performance = [
                "total_judgement"    => $total_judgement,
                "homework_situation" => $homework_situation,
                "content_grasp"    => $content_grasp,
                "lesson_interact"  => $lesson_interact,
                "point_note_list"  => $point_note_list_arr
            ];
        }else{
            $stu_performance = [
                "total_judgement"    => $total_judgement,
                "homework_situation" => $homework_situation,
                "content_grasp"      => $content_grasp,
                "lesson_interact"    => $lesson_interact,
                "stu_comment"        => $stu_common_info_arr
            ];
        }

        if(!empty($stu_performance)) {
            $stu_performance_str = json_encode($stu_performance);
            $ret = $this->t_lesson_info_b2->set_stu_performance_tmp($lessonid, $teacherid, $stu_performance_str,3);
            $com_state = $this->t_lesson_info_b2->set_comment_status($lessonid,$now);
            return $this->output_succ(['time'=>$com_state]);
        }
    }

}