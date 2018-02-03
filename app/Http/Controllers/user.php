<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class user extends TeaWxController
{
    public function __construct(){

    }

    public function get_teacher_salary_statistics(){ // 协议编号:1017
        $teacherid = $this->get_teacherid();

        // $teacher_money = new teacher_money;
        // $this->set_in_value("teacherid",$teacherid);
        $url = "http://admin.leo1v1.com/teacher_money/get_teacher_total_money";
        $post_data = array(
            "teacherid" => $teacherid,
        );
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        // $output = $teacher_money->get_teacher_total_money();
        $ret_arr = json_decode($output,true);

        if($ret_arr == null){
            return $this->output_err("工资汇总详情获取失败!");
        }else if($ret_arr!=null && !empty($ret_arr['data'])){
            return $this->output_succ([
                'data'         => $ret_arr['data'],
                'teacher_info' => $ret_arr['teacher_info']
            ]);
        }else{
            return $this->output_err('工资汇总详情不存在!');
        }
    }

    public function get_vacant_time(){ // 协议编号: 1009
        $type = $this->get_in_int_val("type");
        if($type==1){
            $teacherid = $this->get_in_int_val("teacherid");
        }else{
            $teacherid = $this->get_teacherid();
        }
        $start     = $this->get_in_int_val('start');
        $end       = $this->get_in_int_val('end');

        $tea_lessons_arr = $this->t_lesson_info_b2->get_teacher_lessons($teacherid ,$start, $end);

        $free_time_str = $this->t_teacher_freetime_for_week->get_vacant_arr($teacherid);

        $free_time_arr = json_decode($free_time_str,true);

        if($free_time_str){
            $ret_info=[];
            foreach($free_time_arr as $item){
                $flag = 0;
                $time     = @strtotime($item['0']);
                $time_end = time(NULL)+59*60;
                foreach($tea_lessons_arr as $item_lesson){
                    if($time < $item_lesson["lesson_end"] && $time_end > $item_lesson["lesson_start"]) {
                        $flag = 1;
                    }
                }
                if($time >= $start && $time <= $end) {
                    if ($time >= time(NULL) && $flag == 0) {
                        $t = [
                            "time"=>$item,
                            "can_edit"=>0
                        ];
                        array_push($ret_info,$t);
                    }else{
                        $t = [
                            "time"=>$item,
                            "can_edit"=>1
                        ];
                        array_push($ret_info,$t);
                    }
                }
            }

            return $this->output_succ([
                'data'=>$ret_info
            ]);
        }else{
            return $this->output_succ(['data'=>[]]);
        }
    }

    public function teacher_complain(){// 协议编号:1024

        $report_uid   = $this->get_teacherid();
        $report_msg   = $this->get_in_str_val('report_msg');
        $complaint_type = $this->get_in_int_val('complaint_type');

        $url="http://wx-parent.leo1v1.com/wx_teacher_api/teacher_report_msg";
        $post_data = [
            'report_msg' => $report_msg,
            'complaint_type' => $complaint_type,
            'teacherid'      => $report_uid
        ];
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        $ret_arr = json_decode($output,true);
        // dd($output);
        if($ret_arr == null){
            return $this->output_err("投诉提交失败!");
        }else{
            return $this->output_succ();
        }
    }

    public function set_vacant_time(){ // 协议编号 :1010
        $teacherid = $this->get_teacherid();
        $time      = $this->get_in_str_val('time');
        $type      = $this->get_in_str_val('type');
        $time      = $time/1000;

        $timeArr = [];
        if($time){
            $timeArr = explode(',',$time);
            if(count($timeArr) == 0 && strlen($time) >0){
                $timeArr[] = $time;
            }
        }

        $format_arr = [];
        foreach($timeArr as $item_time){
            $format_arr[] = date("Y-m-d H:i",$item_time);
        }

        $ret_str = $this->t_teacher_freetime_for_week->get_vacant_arr($teacherid);
        $ret_arr = json_decode($ret_str,true);
        if(!empty($ret_arr)){
            foreach($ret_arr as $i=>$item_ret){
                $timestamp = strtotime($item_ret);
                if($timestamp<time()){
                    unset($ret_arr[$i]);
                }else{
                    foreach($format_arr as &$item_format){
                        if($item_ret==' '){
                            unset($ret_arr[$i]);
                        }else{
                            if($item_format == @$item_ret['0']){
                                unset($ret_arr[$i]);
                            }
                        }
                    }
                }
            }
        }

        if($type == 0){
            $time_arr = [];
            foreach($format_arr as $item_for){
                $time_arr[] = $item_for;
                $time_start_hour = date('G',strtotime($item_for));
                $hour = $time_start_hour.':59';
                $time_arr[] = $hour;
            }

            if(empty($ret_arr)){
                $ret_arr = [];
            }
            array_push($ret_arr,$time_arr);
        }

        $time_value = json_encode($ret_arr);
        if($time_value){
            $ret_update = $this->t_teacher_freetime_for_week->field_update_list($teacherid,[
                'free_time_new' => $time_value
            ]);

            if($ret_update) {
                return $this->output_succ(["data"=>$ret_update]);
            }else {
                return $this->output_err('老师空闲时间设置失败!');
            }
        }
    }


    public function get_recent_lesson_time () { // 1008
        $now  = time(NULL);
        $today_time = strtotime(date('Y-m-d',time(NULL)));
        $day = date('w');
        $from_time  = $today_time-$day*24*60*60;
        $to_time    = $from_time+21*24*60*60;
        $arr = [];

        for($i=$today_time; $i<=$to_time; $i=$i+24*60*60){
            $arr[] = $i;
        }

        return $this->output_succ(['data'=>$arr]);
    }

    public function get_teacher_feedback_list (){//1019
        $teacherid = $this->get_teacherid();

        $lessonid  = $this->get_in_int_val('lessonid');
        $feedlist_arr = $this->t_teacher_feedback_list->get_feedback_list($teacherid,$lessonid);

        if(!$feedlist_arr){
            return $this->output_succ(['data'=>[]]);
        }else{
            foreach($feedlist_arr as &$item){
                $feedback_type = $item['feedback_type'];
                $feedback = [
                    "feedback_type"=>0,
                    "str" => "无"
                ];

                if ($feedback_type == 101) {
                    $feedback = [
                        "feedback_type"=>101,
                        "str" => "基础工资"
                    ];
                }else if($feedback_type == 102) {
                    $feedback = [
                        "feedback_type" => 102,
                        "str" => "课时奖励工资"
                    ];
                }else if($feedback_type == 103) {
                    $feedback = [
                        "feedback_type" => 103,
                        "str" => "全勤奖"
                    ];
                }else if($feedback_type == 104) {
                    $feedback = [
                        "feedback_type"=>104,
                        "str" =>"荣誉榜排名奖金"
                    ];
                }else if($feedback_type == 201) {
                    $feedback = [
                        "feedback_type" => 201,
                        "str" => "上课迟到扣款"
                    ];
                }else if($feedback_type == 202) {
                    $feedback = [
                        "feedback_type" => 202,
                        "str" => "规定时间内未评价扣款"
                    ];
                }else if($feedback_type == 203) {
                    $feedback = [
                        "feedback_type"=>203,
                        "str" => "课前未传讲义扣款"
                    ];
                }else if($feedback_type == 204) {
                    $feedback = [
                        "feedback_type"=>204,
                        "str" => "未提前4小时换课"
                    ];
                }else if($feedback_type == 205) {
                    $feedback = [
                        "feedback_type" => 205,
                        "str" => "教学事故"
                    ];
                }

                $item["feedback_type"]     = $feedback["feedback_type"];
                $item["feedback_type_str"] = $feedback["str"];
                $item["lesson_count"] = $item["lesson_count"]/100 ;
            }
            return $this->output_succ(["data"=>$feedlist_arr]);

        }
    }

    public function set_teacher_feedback(){ //1020
        $from_type = $this->get_in_str_val("from_type","wx");
        if($from_type=="wx"){
            $teacherid = $this->get_teacherid();
        }elseif($from_type=="admin"){
            $teacherid = $this->get_login_teacher();
        }else{
            return $this->output_err("类型出错！");
        }
        $lessonid      = $this->get_in_int_val("lessonid");
        $feedback_type = $this->get_in_int_val("feedback_type");
        $lesson_count  = $this->get_in_str_val("lesson_count");
        $tea_reason    = $this->get_in_str_val("tea_reason");



        /**
         * @ 每月6号之后 关闭上月课程申诉通道
         * @ 已发布
         * @ James
         **/

        $limit_time = strtotime(date('Y-m-1'));
        $six_time   = $limit_time + 6*86400;
        $lesson_end = $this->t_lesson_info->get_lesson_end($lessonid);
        $now = time();

        if(($lesson_end<$limit_time) && ($six_time<$now)){
            return $this->output_err('老师您好,上月课程申诉通道已关闭!请联系您的助教老师!');
        }

        // \App\Helper\Utils::logger("feedback_info_begin ");
        // $feedback = new teacher_feedback;
        // \App\Helper\Utils::logger("feedback_info_second ");
        // $ret = $feedback->add_teacher_feedback($teacherid,$lessonid,$feedback_type,$lesson_count,$tea_reason);
        // \App\Helper\Utils::logger("feedback_info :".$ret);

        // return $ret;

        if($feedback_type == 101 || $feedback_type == 102){
            $lesson_count = 100*$lesson_count;
        }else{
            $lesson_count = 0;
        }

        $add_time = time(NULL);
        $ret_flag = $this->t_teacher_feedback_list->get_feedback_count($teacherid, $lessonid, $feedback_type);
        if($ret_flag == 0){
            $ret_affect = $this->t_teacher_feedback_list->row_insert([
                "teacherid"     => $teacherid,
                "lessonid"      => $lessonid,
                "feedback_type" => $feedback_type,
                "lesson_count"  => $lesson_count,
                "tea_reason"    => $tea_reason,
                "add_time"      => $add_time,
            ]);
            if($ret_affect){
                return $this->output_succ(['data'=>$ret_affect]);
            }else{
                return $this->output_err("添加失败，请重试！");
            }
        }else{
            return $this->output_err("申诉条目已存在!");
        }
    }


    public function get_info () { //1001
        $teacherid = $this->get_teacherid();
        $ret = $this->t_teacher_info->get_teacher_info_all($teacherid);

        if($ret){
            return $this->output_succ(['data'=>$ret]);
        }else{
            return $this->output_err('获取老师信息失败!');
        }
    }


    public function get_tea_feedback_select_options(){ //1021
        $feedback_type = E\Efeedback_type::$desc_map;
        $options  = [];
        foreach($feedback_type as $key=>$val){
            $options[] = [
                "feedback_type"     => $key,
                "feedback_type_str" => $val,
            ];
        }
        // $options = [
        //     [
        //         "feedback_type"=>101,
        //         "feedback_type_str" => "基础工资"
        //     ],
        //     [
        //         "feedback_type"=>102,
        //         "feedback_type_str" =>"课时奖励工资"
        //     ],
        //     [
        //         "feedback_type"=>105,
        //         "feedback_type_str" =>"额外奖金"
        //     ],
        //     [
        //         "feedback_type"=>104,
        //         "feedback_type_str" =>"荣誉榜排名奖金"
        //     ],
        //     [
        //         "feedback_type"=>201,
        //         "feedback_type_str"=> "上课迟到扣款"
        //     ],
        //     [
        //         "feedback_type"=>202,
        //         "feedback_type_str" =>"规定时间内未评价扣款"
        //     ],
        //     [
        //         "feedback_type"=>203,
        //         "feedback_type_str" =>"课前未传讲义扣款"
        //     ],
        //     [
        //         "feedback_type"=>204,
        //         "feedback_type_str" =>"未提前4小时换课"
        //     ],
        //     [
        //         "feedback_type"=>205,
        //         "feedback_type_str" =>"教学事故"
        //     ],
        // ];
        return $this->output_succ(["data"=>$options]);

    }


}
