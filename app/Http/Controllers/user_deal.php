<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;
//use Pingpp;

use App\Jobs\deal_lesson_online_status;


class user_deal extends Controller
{
    use CacheNick;
    use TeaPower;

    public function get_lesson_list()  {
        $userid=$this->get_in_userid(-1);
        $lesson_type=$this->get_in_int_val("lesson_type",-1);
        $page_num=$this->get_in_page_num();
        $ret_list=$this->t_lesson_info->get_list_for_ajax_list($userid,$lesson_type,$page_num);
        foreach ($ret_list["list"] as &$item) {
            $item["teacher_nick"]=$this->cache_get_teacher_nick($item["teacherid"]);
            E\Econtract_type::set_item_value_str($item,"lesson_type");
            $item["lesson_time"] =\App\Helper\Utils::fmt_lesson_time( $item["lesson_start"] ,$item["lesson_end"]);
        }
        $ret_list["page_info"]  = $this->get_page_info_for_js( $ret_list["page_info"]   );
        return outputjson_success(array('data' => $ret_list ));
    }

    public function get_lesson_log_list() {
        $lessonid     = $this->get_in_int_val('lessonid',-1);
        $server_type  = $this->get_in_int_val('server_type',-1);
        $userid       = $this->get_in_int_val('userid',-1);

    }

    public function add_contract_for_lesson_account(){

        $studentid = $this->get_in_studentid();
        $money=$this->get_in_int_val("money")*100;
        $lesson_count= $this->get_in_str_val("lesson_count",0);
        $course_name=$this->get_in_str_val("course_name");

        if ($lesson_count   <=0 ) {
           return outputjson_error("课时数不对") ;
        }
        $price= $money/$lesson_count;

        if ($price<=0) {
            return outputjson_error("金额不对") ;
        }


        $ret_auth = $this->t_manager_info ->check_permission($this->get_account(),
                                                          E\Epower::V_ADD_CONTRACT);
        if(!$ret_auth) {
            return outputjson_error(E\Eerror::V_NOT_AUTH);
        }

        $lesson_account_id=$this->t_user_lesson_account->add($this->get_account(),$studentid,$course_name,0,$price);
        if ($lesson_account_id<=0) {
            return outputjson_error(" lesson_account_id 不对") ;
        }



        srand(microtime(true) * 1000);
        $time_str = $studentid+rand(1,1000000000)%142857;
        $contractid = 'E'.date('Ymd',time()).$time_str;



        $insert_ret=$this->t_order_info->row_insert([
            \App\Models\t_order_info::C_contractid => $contractid,
            \App\Models\t_order_info::C_price => $money,
            \App\Models\t_order_info::C_userid => $studentid,
            \App\Models\t_order_info::C_from_type =>E\Efrom_type::V_1 ,
            \App\Models\t_order_info::C_order_time =>time(NULL),
            \App\Models\t_order_info::C_config_lesson_account_id=> $lesson_account_id,
            \App\Models\t_order_info::C_sys_operator=> $this->get_account() ,
            \App\Models\t_order_info::C_lesson_total => $lesson_count,
            \App\Models\t_order_info::C_lesson_left  => $lesson_count*100,
        ]);
        return outputjson_ret( $insert_ret==1);
    }

    public function cancel_lesson()
    {
        $lessonid      = $this->get_in_int_val('lessonid',-1);

        $lesson_type=$this->t_lesson_info-> get_lesson_type($lessonid);
        if( $lesson_type==2) { //test_leson
            if ($this->t_seller_student_info->get_stu_performance_for_seller($lessonid) ) {
                return $this->output_err("试听课 有绑定,请从试听管理中取消");
            }
        }

        //$this->t_lesson_info->del_if_no_start($lessonid);
        $this->t_lesson_info->field_update_list($lessonid,[
           "lesson_del_flag" =>1
        ]);

        return outputjson_success();
    }

    public function lesson_change_lesson_count()
    {
        $lessonid = $this->get_in_int_val('lessonid',-1);
        $lesson_count = $this->get_in_int_val('lesson_count',0);


        $lesson_confirm_start_time=\App\Helper\Config::get_lesson_confirm_start_time();

        $lesson_start=$this->t_lesson_info->get_lesson_start($lessonid);
        if ($lesson_start<  strtotime( $lesson_confirm_start_time) ) {
            //
            return $this->output_err("上课时间太早了, 早于[$lesson_confirm_start_time] ");
        };

        $ret=$this->t_lesson_info->check_lesson_count_for_change($lessonid, $lesson_count);
        if (!$ret){
            return $this->output_err("课时数太大了");
        }
        $this->t_lesson_info->field_update_list($lessonid,[
            "lesson_count" => $lesson_count,
        ]);
        return outputjson_success();
    }

    public function lesson_add_lesson() {
        $courseid = $this->get_in_courseid();

        $item = $this->t_course_order->field_get_list($courseid,"*");
        if ($item["teacherid"]) {
            $this->output_err("还没设置老师");
        }
        if($item["course_type"]==2){
            if(!$this->check_power(E\Epower::V_ADD_TEST_LESSON)) {
                return $this->output_err("没有权限排试听课");
            }
        }

        $check = $this->research_fulltime_teacher_lesson_plan_limit($item["teacherid"],$item["userid"]);
        if($check){
            return $check;
        }

        if($item['lesson_grade_type']==0){
            $grade = $this->t_student_info->get_grade($item["userid"]);
        }elseif($item['lesson_grade_type']==1){
            $grade = $item['grade'];
        }else{
            return $this->output_err("学生课程年级出错！请在课程包列表中修改！");
        }

        $teacher_info = $this->t_teacher_info->field_get_list($item["teacherid"],"teacher_money_type,level");
        $default_lesson_count = 0;

        $lessonid = $this->t_lesson_info->add_lesson(
            $item["courseid"],0,$item["userid"],0,$item["course_type"],$item["teacherid"],
            $item["assistantid"],0,0,$grade,$item["subject"],$default_lesson_count,
            $teacher_info["teacher_money_type"],$teacher_info["level"],
            $item["competition_flag"],2,$item['week_comment_num'],$item['enable_video']
        );

        if ($lessonid) {
            $this->t_homework_info->add($item["courseid"],0,$item["userid"],$lessonid,$item["grade"],$item["subject"]);
        }
        $this->t_lesson_info->reset_lesson_list($courseid);

        return $this->output_succ(["lessonid" => $lessonid ]);
    }

    public function user_set_passwd() {
        $userid=$this->get_in_userid();
        $passwd=$this->get_in_str_val("passwd");
        $this->t_user_info->update_password($userid,md5($passwd));
        return outputjson_success();

    }

    public function reset_lesson_count () {
        $studentid=$this->get_in_int_val('studentid');

        $this->t_student_info->reset_lesson_count($studentid);
        $this->cache_del_student_nick($studentid);

        return outputjson_success();
    }

    public function lesson_set_confirm() {
        $lessonid       = $this->get_in_lessonid();
        $confirm_flag   = $this->get_in_int_val("confirm_flag");
        $confirm_reason = $this->get_in_str_val("confirm_reason");
        $lesson_cancel_reason_next_lesson_time = strtotime( $this->get_in_str_val("lesson_cancel_reason_next_lesson_time"  ));
        $lesson_cancel_reason_type = $this->get_in_str_val("lesson_cancel_reason_type"  );
        $lesson_cancel_time_type = $this->get_in_int_val("lesson_cancel_time_type");
        if ($confirm_flag==1){
            $confirm_reason = "";
        }

        $lesson_confirm_start_time = \App\Helper\Config::get_lesson_confirm_start_time();
        $lesson_info  = $this->t_lesson_info->get_all_lesson_info($lessonid);
        $lesson_start = $lesson_info['lesson_start'];
        $lesson_type  = $lesson_info['lesson_type'];
        if ($lesson_start<strtotime($lesson_confirm_start_time)) {
            return $this->output_err("上课时间太早了, 早于[$lesson_confirm_start_time]");
        }

        if($confirm_flag == 2 || $confirm_flag == 3 || $confirm_flag == 4 ) {
            $this->t_lesson_info->field_update_list($lessonid,[
                "lesson_status" => E\Elesson_status::V_END,
            ]);

            if(in_array($lesson_cancel_reason_type,[3,4,13,14]) && $confirm_reason==""){
                return $this->output_err("请填写无效原因!");
            }

            //课程取消时提醒老师
            $lesson_info['lesson_cancel_reason_type'] = $lesson_cancel_reason_type;
            $lesson_info['lesson_cancel_time_type']   = $lesson_cancel_time_type;
            $this->push_teacher_wx($lesson_info,$confirm_flag);
            if($confirm_flag==2){
                $this->check_order_lesson_list($lessonid);
            }elseif($confirm_flag==3 && $lesson_type!=2 && $lesson_type<1000){
                $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_count" => $lesson_info['lesson_count']/2,
                ]);
            }
        }else{
            if($lesson_info['lesson_end']>time()){
                $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_status" => E\Elesson_status::V_NO_START,
                ]);
            }
        }

        $this->t_lesson_info->field_update_list($lessonid,[
            "confirm_flag"    => $confirm_flag,
            "confirm_adminid" => $this->get_account_id(),
            "confirm_time"    => time(NULL),
            "confirm_reason"  => $confirm_reason,
            "lesson_cancel_reason_type" => $lesson_cancel_reason_type,
            "lesson_cancel_time_type"   => $lesson_cancel_time_type,
            "lesson_cancel_reason_next_lesson_time" => $lesson_cancel_reason_next_lesson_time,
        ]);

        if($lesson_type!=2 && $lesson_cancel_reason_type>=11 && $lesson_cancel_reason_type<=21 ){
            $teacherid = $lesson_info["teacherid"];
            $realname = $this->t_teacher_info->get_realname($teacherid);
            $lesson_time = date("Y-m-d H:i:s",$lesson_info["lesson_start"]);
            $record_info = "上课完成:".E\Econfirm_flag::get_desc($confirm_flag)."<br>无效类型:".E\Elesson_cancel_reason_type::get_desc($lesson_cancel_reason_type)."<br>课堂确认情况:".E\Elesson_cancel_time_type::get_desc($lesson_cancel_time_type)."<br>无效说明:".$confirm_reason."<br>老师:".$realname."<br>上课时间:".$lesson_time;
            $this->t_revisit_info->row_insert([
                "userid"        => $lesson_info["userid"],
                "revisit_time"  => time(),
                "sys_operator"  => $this->get_account(),
                "operator_note" => $record_info,
                "revisit_type"  => 3
            ]);
        }

        if($lesson_cancel_reason_type<10 && $lesson_cancel_reason_type>0){
            $lesson_cw    = $this->t_lesson_info->get_lesson_cw_info($lessonid);
            $courseid     = $this->get_in_courseid();
            $lesson_type  = $this->get_in_int_val("lesson_type");
            $lesson_count = $this->get_in_str_val("lesson_count")*100;
            $lesson_start = $lesson_cancel_reason_next_lesson_time;
            $lesson_end = $this->get_in_str_val("lesson_cancel_reason_next_lesson_end_time");
            $day = date('Y-m-d',$lesson_cancel_reason_next_lesson_time);
            $lesson_end = strtotime($day." ".$lesson_end);

            if($lesson_type!=2){
                $item = $this->t_course_order->field_get_list($courseid,"*");
                if($item['lesson_grade_type']==1){
                    $grade = $item['grade'];
                }else{
                    $grade = $this->t_student_info->get_grade($item["userid"]);
                }
                $teacher_info = $this->t_teacher_info->field_get_list($item["teacherid"],"teacher_money_type,level");
                $default_lesson_count=0;
                $lessonid = $this->t_lesson_info->add_lesson_new(
                    $item["courseid"],0,
                    $item["userid"],
                    0,
                    $item["course_type"],
                    $item["teacherid"],
                    $item["assistantid"],
                    0,0,
                    $grade,
                    $item["subject"],
                    $default_lesson_count,
                    $teacher_info["teacher_money_type"],
                    $teacher_info["level"],
                    $item["competition_flag"],
                    $lesson_cw['stu_cw_upload_time'],
                    $lesson_cw['stu_cw_status'],
                    $lesson_cw['stu_cw_url'],
                    $lesson_cw['tea_cw_name'],
                    $lesson_cw['tea_cw_upload_time'],
                    $lesson_cw['tea_cw_status'],
                    $lesson_cw['tea_cw_url'],
                    $lesson_cw['lesson_quiz'],
                    $lesson_cw['lesson_quiz_status'],
                    $lesson_cw['tea_more_cw_url']
                );
                if ($lessonid) {
                    $this->t_homework_info->add_new(
                        $item["courseid"],
                        0,
                        $item["userid"],
                        $lessonid,
                        $item["grade"],
                        $item["subject"],
                        0,
                        $lesson_cw['work_status'],
                        $lesson_cw['issue_url'],
                        $lesson_cw['finish_url'],
                        $lesson_cw['check_url'],
                        $lesson_cw['tea_research_url'],
                        $lesson_cw['ass_research_url'],
                        $lesson_cw['score'],
                        $lesson_cw['issue_time'],
                        $lesson_cw['finish_time'],
                        $lesson_cw['check_time'],
                        $lesson_cw['tea_research_time'],
                        $lesson_cw['ass_research_time']
                    );
                }
                $this->t_lesson_info->reset_lesson_list($courseid);

                if ($lesson_start >= $lesson_end) {
                    return $this->output_err( "时间不对: 时间不对: 下课时间早于上课时间");
                }
                if ($lesson_start <= time()) {
                    return $this->output_err( "时间不对,不能比当前时间晚");
                }

                $teacherid   = $this->t_lesson_info->get_teacherid($lessonid);
                $lesson_type = $this->t_lesson_info->get_lesson_type($lessonid);
                $userid      = $this->t_lesson_info->get_userid($lessonid);
                if ($userid) {
                    $ret_row = $this->t_lesson_info->check_student_time_free($userid,$lessonid,$lesson_start,$lesson_end);
                    if($ret_row) {
                        $error_lessonid=$ret_row["lessonid"];
                        return $this->output_err(
                            "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
                        );
                    }
                }

                $ret_row=$this->t_lesson_info->check_teacher_time_free(
                    $teacherid,$lessonid,$lesson_start,$lesson_end);

                if($ret_row) {
                    $error_lessonid=$ret_row["lessonid"];
                    return $this->output_err(
                        "<div>有现存的老师课程与该课程时间冲突！<a href='/teacher_info/get_lesson_list?teacherid=$teacherid&lessonid=$error_lessonid' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
                    );
                }

                /* 设置lesson_count */
                if(empty($lesson_count)){
                    $diff=($lesson_end-$lesson_start)/60;
                    if ($diff<=40) {
                        $lesson_count=100;
                    } else if ( $diff <= 60) {
                        $lesson_count=150;
                    } else if ( $diff <=90 ) {
                        $lesson_count=200;
                    }else{
                        $lesson_count= ceil($diff/40)*100 ;
                    }
                }

                $lesson_type = $this->t_lesson_info->get_lesson_type($lessonid);
                $ret=true;
                if($lesson_type<1000){
                    $ret = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);
                }

                if ($ret) {
                    \App\Helper\Utils::logger("set XXX ");
                    $this->t_lesson_info->field_update_list($lessonid,[
                        "lesson_count" => $lesson_count
                    ]);
                    $this->t_lesson_info->set_lesson_time($lessonid,$lesson_start,$lesson_end);
                    return $this->output_succ();
                }else{
                    $str = $lesson_count/100;
                    return $this->output_err("课时不足,需要课时数:$str");
                }
            }else{
            }
        }else{
            return $this->output_succ();
        }
    }

    private function check_order_lesson_list($lessonid){
        $ret = $this->t_order_lesson_list->get_lesson_info($lessonid);
        if(!empty($ret)){
            foreach($ret as $val){
                $order_info   = $this->t_order_info->get_order_info_by_orderid($val['orderid']);
                $lesson_left  = $val['lesson_count']+$order_info['lesson_left'];
                $lesson_total = $order_info['lesson_total']*$order_info['default_lesson_count'];
                if($lesson_left>$lesson_total || $order_info['contract_status']==3){
                    return $this->output_err("此课程课时有错!请在问题报告群里汇报,并写出此课堂的lessonid和学生!");
                }
                $contract_status=$order_info['contract_status']==2?1:$order_info['contract_status'];
                $this->t_order_info->field_update_list($val['orderid'],[
                    "lesson_left"     => $lesson_left,
                    "contract_status" => $contract_status,
                ]);
            }
        }
    }

    /**
     * 由于老师原因换课,才进行扣款(lesson_cancel_type 课程取消原因类型 2 老师调课 12 老师请假)
     * @param lesson_info  课堂信息
     * @param confirm_flag 课程确认情况 2 无效 3 无效需给老师工资 4 无效给老师工资不扣学生课时
     */
    private function push_teacher_wx($lesson_info,$confirm_flag){
        $date_time     = date("m.d日",$lesson_info['lesson_start']);
        $end_time      = date("Y-m-d H:i",$lesson_info['lesson_start'])."-".date("H:i",$lesson_info['lesson_end']);
        $lesson_type   = $lesson_info['lesson_type'];
        $type_str      = $lesson_type==2?"试听课":"1对1";
        $stu_nick      = $this->cache_get_student_nick($lesson_info['userid']);
        $lesson_count  = $lesson_info['lesson_count'];
        $grade_str     = E\Egrade::get_desc($lesson_info['grade']);
        $teacher_money = \App\Helper\Config::get_config("teacher_money");
        $lesson_cancel_reason_type = $lesson_info['lesson_cancel_reason_type'];
        $lesson_cancel_time_type   = $lesson_info['lesson_cancel_time_type'];

        $info   = "";
        $cost   = 0;
        $openid = $this->t_teacher_info->get_wx_openid($lesson_info['teacherid']);
        if($confirm_flag==2){
            if($lesson_cancel_reason_type==2 || $lesson_cancel_reason_type==12){
                if($lesson_type<1000){
                    if($lesson_cancel_time_type==1){
                        $time = \App\Helper\Utils::get_month_date();
                        $change_num = $this->t_lesson_info->get_cost_num($time['start'],$time['end'],$lesson_info['teacherid'],2);
                        if($change_num>=3){
                            $price = $teacher_money['lesson_cost']/100;
                            $info  = "老师你好，由于您未在课前4小时以上进行换课，对学生上课造成严重影响，因此扣款"
                                   .$price."元，调课请提前联系理优教务老师。";
                        }else{
                            $info  = "老师你好，本月已经第".($change_num+1)."次换课，每月总共有3次换课机会";
                        }
                        $cost = 1;
                    }
                }
            }elseif($lesson_cancel_reason_type==21){
                $price = $teacher_money['lesson_miss_cost']/100;
                $info  = "老师你好，由于您的旷课对学生造成非常严重影响，因此扣款".$price."元，请老师有事情提前请假";
                $cost  = 1;
            }elseif($lesson_type==2){
                $info = $date_time."的试听课已经取消,我们将尽快为老师安排新的学生进行试听,请老师耐心等待";
            }

            if($cost){
                $this->t_lesson_info->field_update_list($lesson_info['lessonid'],[
                    "deduct_change_class" => 1,
                ]);
            }
        }elseif($confirm_flag==3){
            if($lesson_type==2){
                if($lesson_info['lesson_start']>time() && $lesson_info['lesson_end']<time()){
                    $info = $date_time."的试听课已经取消,感谢您的耐心等待,现在您可以直接退出课堂,本次课薪资照常发放";
                }else{
                    $info = $date_time."的试听课已经取消,由于课程即将开始,因此老师可以准备其他课程,本次课薪资照常发放";
                }
            }
        }

        if($info!='' && $openid){
            /**
             * 标题     课程取消通知
             * template_id eHa4a9BoAbEycjIYSakPHx7zkqXDLoHbwEy6HDj4Gb4
             * first
             * 课程类型 keyword1
             * 上课时间 keyword2
             * remark
             */
            $data = [
                "first"    => $info,
                "keyword1" => $type_str,
                "keyword2" => $end_time,
                "remark"   => "学生:".$stu_nick."\n课时:".($lesson_count/100)."课时\n年级:".$grade_str
            ];
            $template_id = "YKGjtHUG20pS9RGBmTWm8_wYx4f30amrGv-F5NnBk8w";
            $appId       = \App\Helper\Config::get_teacher_wx_appid();
            $appSecret   = \App\Helper\Config::get_teacher_wx_appsecret();
            $wx  = new \App\Helper\Wx($appId,$appSecret);
            $wx->send_template_msg($openid,$template_id,$data);
        }
    }

    /**
     * @param lessonid 课程id
     * @param value 课程状态
     * @return int sql影响行数
     */
    private function set_lesson_status($lessonid,$value){
        $ret=$this->t_lesson_info->field_update_list($lessonid,[
            "lesson_status"=>$value,
        ]);

        return $ret;
    }

    /**
     * @param lessonid 课程id
     * @param type 1 无效课时确认 2 有效课时确认
     * @return boolean/string
     */
    private function check_order_lesson_total($lessonid,$type){
        $ret_info        = $this->t_order_lesson_list->get_simple_order_info($lessonid);
        $orderid         = $ret_info['orderid'];
        $contract_status = $ret_info['contract_status'];
        $lesson_status   = $ret_info['lesson_status'];
        $lesson_end      = $ret_info['lesson_end'];

        if($type==1){
            $lesson_left = $order_info['lesson_left']+$order_info['lesson_count'];
            if($contract_status==3){
                return $this->output_err("此合同已经提前终结,无法确认为无效课时");
            }elseif($contract_status==2){
                $this->t_order_info->field_update_list($orderid,[
                    "lesson_left"     => $lesson_left,
                    "contract_status" => 1,
                ]);
            }elseif($contract_status==1){
                $this->t_order_info->field_update_list($orderid,[
                    "lesson_left"  => $lesson_left,
                ]);
            }
        }
        return true;
    }

    public function student_set_seller() {
        $studentid      = $this->get_in_studentid();
        $seller_adminid = $this->get_in_int_val("seller_adminid");
        $this->t_student_info->field_update_list($studentid, [
            "seller_adminid" => $seller_adminid,
        ]);
        return $this->output_succ();
    }

    public function set_lesson_time()
    {
        $lessonid = $this->get_in_int_val('lessonid',0);
        $start    = $this->get_in_str_val('start',0);
        $end      = $this->get_in_str_val('end',0);

        $reset_lesson_count = $this->get_in_str_val('reset_lesson_count',1);

        if ($start) {
            $lesson_start = strtotime( $start);
            $date         = date('Y-m-d', strtotime($start));
            $lesson_end   = strtotime($date . " " . $end);
        }else{
            $lesson_start = $this->get_in_int_val("lesson_start");
            $lesson_end   = $this->get_in_int_val("lesson_end");
        }


        if ($lesson_start >= $lesson_end) {
            return $this->output_err( "时间不对: $lesson_start>$lesson_end");
        }

        $teacherid = $this->t_lesson_info->get_teacherid($lessonid);
        $userid    = $this->t_lesson_info->get_userid($lessonid);
        /* 设置lesson_count */
        $diff=($lesson_end-$lesson_start)/60;
        if ($diff<=20) {
            $lesson_count=50;
        } else if ($diff<=40) {
            $lesson_count=100;
        } else if ( $diff <= 60) {
            $lesson_count=150;
        } else if ( $diff <=90 ) {
            $lesson_count=200;
        } else if ( $diff <=100 ) {
            $lesson_count=250;
        }else{
            $lesson_count= ceil($diff/40)*100 ;
        }

        $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);
        $lesson_type = $lesson_info['lesson_type'];
        if($lesson_info['lesson_type']==2){
            $old_date   = date("Y-m-d",$lesson_info['lesson_start']);
            $start_date = date("Y-m-d",$lesson_start);
            $end_date   = date("Y-m-d",$lesson_end);

            if($old_date!=$start_date || $old_date!=$end_date){
                return $this->output_err("不能更改到其他日期的时间");
            }
        }

        $check=$this->research_fulltime_teacher_lesson_plan_limit($teacherid,$userid,$lesson_count/100,$lesson_start,$lesson_type);
        if($check){
            return $check;
        }

        if ($reset_lesson_count  && $lesson_type==2) {
            if(!$this->check_power(E\Epower::V_ADD_TEST_LESSON)) {
                return $this->output_err("没有权限排试听课");
            }
            $db_lesson_start=$this->t_lesson_info->get_lesson_start($lessonid);
            if ($db_lesson_start) {
                return $this->output_err("试听课不能修改时间,只能删除,重新排新课,再设置时间");
            }
        }

        $userid = $this->t_lesson_info->get_userid($lessonid);
        if ($userid) {
            $ret_row = $this->t_lesson_info->check_student_time_free(
                $userid,$lessonid,$lesson_start,$lesson_end
            );

            if($ret_row) {
                $error_lessonid=$ret_row["lessonid"];
                return $this->output_err(
                    "<div>有现存的<div color=\"red\">学生</div>课程与该课程时间冲突！"
                    ."<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>"
                    ."查看[lessonid=$error_lessonid]<a/><div> "
                );
            }
        }

        $ret_row=$this->t_lesson_info->check_teacher_time_free(
            $teacherid,$lessonid,$lesson_start,$lesson_end);

        if($ret_row) {
            $error_lessonid=$ret_row["lessonid"];
            return $this->output_err(
                "<div>有现存的<div color=\"red\">老师</div>课程与该课程时间冲突！"
                ."<a href='/teacher_info_admin/get_lesson_list?teacherid=$teacherid&lessonid=$error_lessonid' target='_blank'>"
                ."查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $lesson_type = $this->t_lesson_info->get_lesson_type($lessonid);
        $ret=true;
        if($lesson_type<1000 && $reset_lesson_count){
            $ret = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);
        }

        // 第一次常规课后 将课程规划与试听交接单推送老师




        if ($ret) {
            if($reset_lesson_count){
                $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_count" => $lesson_count
                ]);
            }
            $this->t_lesson_info->set_lesson_time($lessonid,$lesson_start,$lesson_end);
            // 发送微信提醒send_template_msg($teacherid,$template_id,$data,
            $url              = "http://wx-teacher.leo1v1.com";
            $old_lesson_start = date('Y-m-d H:i:s',$lesson_info['lesson_start']);
            $old_lesson_end   = date('Y-m-d H:i:s',$lesson_info['lesson_end']);
            $lesson_start     = date('Y-m-d H:i:s',$lesson_start);
            $lesson_end       = date('Y-m-d H:i:s',$lesson_end);
            $operation_name   = $this->get_account();
            $adminid          = $this->get_account_id();
            $operation_phone  = $this->t_manager_info->get_operation_phone($adminid);

            $parent_wx_openid = $this->t_student_info->get_parent_wx_openid($userid);

            /**
             *{{first.DATA}}
             待办主题：{{keyword1.DATA}}
             待办内容：{{keyword2.DATA}}
             日期：{{keyword3.DATA}}
             {{remark.DATA}}
             */
            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
            $data_msg = [
                "first"     => "上课时间调整通知",
                "keyword1"  => "上课时间调整",
                "keyword2"  => "您 从 $old_lesson_start 至 $old_lesson_end 的课程 已调整为 $lesson_start 至 $lesson_end",
                "remark"     => " 修改人: $operation_name 联系电话: $operation_phone"
            ];
            $this->t_teacher_info->send_template_msg($teacherid,$template_id,$data_msg,$url);

            $wx=new \App\Helper\Wx();
            $ret=$wx->send_template_msg($parent_wx_openid,$template_id,$data_msg ,$url);

            // 获取教务的openid
            $jw_openid = $this->t_test_lesson_subject_require->get_jw_openid($lessonid);
            if ($jw_openid) {
                $wx->send_template_msg($jw_openid,$template_id,$data_msg ,$url);
            }

            return $this->output_succ();
        }else{
            $str= $lesson_count/100;
            return $this->output_err("课时不足,需要课时数:$str");
        }
    }

    public function course_set_default_lesson_count () {
        if ($this->get_account() != "jim"  && $this->get_account() != "echo" && $this->get_account() != "adrian"  ) {
            return $this->output_err("没有权限");
        }
        $orderid=$this->get_in_int_val("orderid");
        $default_lesson_count=$this->get_in_int_val("default_lesson_count");

        if ($default_lesson_count>=0) {
            $lesson_total=  $this->t_order_info->get_lesson_total($orderid);
            $this->t_order_info->field_update_list($orderid,[
                "default_lesson_count"  => $default_lesson_count,
                "lesson_left"  => $default_lesson_count* $lesson_total ,
            ]) ;
            $courseid=$this->t_course_order->get_courseid_by_orderid($orderid);
            $this->t_course_order->field_update_list($courseid, [
                "default_lesson_count"  => $default_lesson_count,
            ]);
        }
        return $this->output_succ();
    }

    public function check_test_lesson()
    {
        $userid= $this->get_in_int_val("userid");
        $courseid= $this->t_course_order->get_test_lesson_courseid($userid);

        return $this->output_succ(["courseid"=>$courseid]);

    }



    public function order_check_money()  {
        $orderid          = $this->get_in_int_val("orderid");
        $check_money_flag = $this->get_in_int_val("check_money_flag" );
        $check_money_desc = $this->get_in_str_val("check_money_desc" );

        $contract_starttime = time();
        $contract_endtime   = time()+86400*365*3;

        $this->t_order_info->field_update_list($orderid,[
            "check_money_flag"    => $check_money_flag,
            "check_money_desc"    => $check_money_desc,
            "check_money_time"    => time(NULL),
            "check_money_adminid" => $this->get_account_id(),
            "contract_starttime"  => $contract_starttime,
            "contract_endtime"    => $contract_endtime,
        ]);

        $order_item = $this->t_order_info->field_get_list($orderid,"contract_type,userid, sys_operator, lesson_total,price");

        $flowid=$this->t_flow->get_flowid_from_key_int(E\Eflow_type::V_SELLER_ORDER_REQUIRE, $orderid );
        $sys_operator  = $order_item["sys_operator"];
        $userid        = $order_item["userid"];
        $contract_type = $order_item["contract_type"];
        $lesson_total  = $order_item["lesson_total"];
        $price         = $order_item["price"]/100;
        if($contract_type==0 &&  $check_money_flag == 1){ //
            $start_time            = strtotime(date("Y-m-d"));
            $end_time              = $start_time+20*86400-1;
            $seller_new_count_type = E\Eseller_new_count_type::V_ORDER_ADD ;
            $value_ex              = $orderid;
            $adminid               = $this->t_manager_info->get_id_by_account($sys_operator);
            if (!$flowid  ){
                if ( $price<10000) {
                    $count=6;
                }else{
                    $count=10;
                }

                if (!$this->t_seller_new_count->check_adminid_seller_new_count_type_value_ex($adminid,$seller_new_count_type,$value_ex)) {
                    $this->t_seller_new_count->add($start_time,$end_time,$seller_new_count_type,$count,$adminid,$value_ex);
                    $this->t_manager_info->send_wx_todo_msg(
                        $sys_operator,
                        "系统",
                        "新签合同赠送 抢新生名额[$count] "
                        ,"学生:". $this->cache_get_student_nick($userid)
                        ,"");
                }
            }
            $sys_operator=$this->t_order_info->get_sys_operator($orderid);
            $this->t_student_info->noti_ass_order($userid,$sys_operator,false);

        }

        return $this->output_succ();
    }

    public function set_ass_master(){
        $userid = $this->get_in_int_val("userid");
        $account = $this->get_in_str_val("account");
        $this->t_student_info->noti_ass_order($userid,$account);
        return $this->output_succ();

    }

    public function update_admin_assign_percent(){
        $groupid = $this->get_in_int_val("groupid");
        $adminid = $this->get_in_int_val("adminid");
        $assign_percent = $this->get_in_str_val("assign_percent");
        $this->t_admin_group_user->field_update_list_2($groupid,$adminid,['assign_percent'=>$assign_percent]);
        return $this->output_succ();
    }

    public function update_group_assign_percent(){
        $groupid = $this->get_in_int_val("groupid");
        $group_assign_percent = $this->get_in_int_val("group_assign_percent");
        $this->t_admin_group_name->field_update_list($groupid,['group_assign_percent'=>$group_assign_percent]);
        return $this->output_succ();
    }

    public function update_main_assign_percent(){
        $groupid = $this->get_in_int_val("groupid");
        $main_assign_percent = $this->get_in_int_val("main_assign_percent");
        $this->t_admin_main_group_name->field_update_list($groupid,['main_assign_percent'=>$main_assign_percent]);
        return $this->output_succ();
    }

    public function update_admin_info() {
        $uid                     = $this->get_in_str_val("uid");
        $phone                   = $this->get_in_phone();
        $name                    = $this->get_in_str_val("name");
        $email                   = $this->get_in_str_val("email");
        $tquin                   = $this->get_in_str_val("tquin");
        $cardid                  = $this->get_in_int_val("cardid");
        $account_role            = $this->get_in_int_val("account_role");
        $old_seller_level        = $this->get_in_int_val("old_seller_level");
        $seller_level            = $this->get_in_int_val("seller_level");
        $wx_id                   = $this->get_in_str_val("wx_id");
        $up_adminid              = $this->get_in_int_val("up_adminid");
        $become_full_member_flag = $this->get_in_int_val("become_full_member_flag");
        $day_new_user_flag       = $this->get_in_boolean_val("day_new_user_flag");
        $call_phone_passwd       = $this->get_in_str_val("call_phone_passwd");
        $call_phone_type         = $this->get_in_int_val("call_phone_type");
        $main_department         = $this->get_in_int_val("main_department");
        if (!$tquin) {
            $tquin=NULL;
        }
        if (!$cardid) {
            $cardid=NULL;
        }
        if (!$phone) {
            $phone=NULL;
        }

        if ($tquin) {
            $db_adminid=$this->t_manager_info->get_uid_by_tquin ($tquin);
            if ( $db_adminid && $db_adminid != $uid) {
                $db_account= $this->cache_get_account_nick($db_adminid);
                return $this->output_err( "TQ号 [$tquin] 已经被[$db_account]占用了"    );
            }
        }


        $set_arr=[
            \App\Models\t_manager_info::C_phone=>$phone,
            \App\Models\t_manager_info::C_name=>$name,
            \App\Models\t_manager_info::C_email=>$email,
            "account_role" => $account_role ,
            "seller_level" => $seller_level ,
            "tquin"        => $tquin,
            "wx_id"        => $wx_id,
            "cardid"       => $cardid  ,
            "up_adminid"   => $up_adminid,
            "day_new_user_flag" => $day_new_user_flag,
            "call_phone_type" => $call_phone_type,
            "call_phone_passwd" => $call_phone_passwd,
            "become_full_member_flag" => $become_full_member_flag,
            "main_department" =>$main_department

        ];

        if ($cardid) {
            $db_cardid_uid=$this->t_manager_info->get_uid_by_cardid($cardid);
            if ($db_cardid_uid  && $db_cardid_uid != $uid) {
                return $this->output_err("考勤卡冲突");
            }
        }
        $this->t_manager_info->field_update_list($uid, $set_arr);

        $this->t_manager_info->sync_kaoqin_user($uid);

        $adminid = session('adminid');
        $uid = $uid;
        $type = 2;
        $old = $old_seller_level;
        $new = $seller_level;

        $this->t_seller_edit_log->row_insert([
            "adminid"     => $adminid,
            "uid"         => $uid,
            "type"        => $type,
            "old"         => $old,
            "new"         => $new,
            "create_time" => time(NULL),
        ],false,false,true );


        /*
        $this->t_manager_info->sync_kaoqin([
            "id"=>time(NULL),
            "do"=>"upload",
            "data"=>"clockin",
            "ccid"=>[$uid],
            "from"=> date("Y-m-d 00:00:00", time(NULL) -86400-40 ) ,
            "to"=> date("Y-m-d 00:00:00", time(NULL)+86400  ) ,
        ]);
        */

        return $this->output_succ();
    }

    public function update_admin_info_new() {
        $uid               = $this->get_in_str_val("uid");
        $name              = $this->get_in_str_val("name");
        $gender            = $this->get_in_int_val("gender");
        $company           = $this->get_in_int_val("company");
        $education         = $this->get_in_int_val("education");
        $employee_level    = $this->get_in_int_val("employee_level");
        /*  $basic_pay         = $this->get_in_int_val("basic_pay");
        $merit_pay         = $this->get_in_int_val("merit_pay");
        $post_basic_pay    = $this->get_in_int_val("post_basic_pay");
        $post_merit_pay    = $this->get_in_int_val("post_merit_pay");*/

        $department        = $this->get_in_int_val("department");
        $main_department   = $this->get_in_int_val("main_department");
        $post              = $this->get_in_int_val("post");
        $department_group  = $this->get_in_int_val("department_group");
        $email             = $this->get_in_str_val("email");
        $gra_school        = $this->get_in_str_val("gra_school");
        $gra_major         = $this->get_in_str_val("gra_major");
        $identity_card     = $this->get_in_str_val("identity_card");
        $become_full_member_time     = $this->get_in_str_val("become_full_member_time");
        $order_end_time    = $this->get_in_str_val("order_end_time");
        $url    = $this->get_in_str_val("resume_url");
        if($become_full_member_time){
            $become_full_member_time = strtotime($become_full_member_time);
        }else{
            $become_full_member_time=0;
        }
        if($order_end_time){
            $order_end_time = strtotime($order_end_time);
        }else{
            $order_end_time=0;
        }
        if(!empty($url)){
            $domain = config('admin')['qiniu']['public']['url'];
            $resume_url = $domain.'/'.$url;
        }else{
            $resume_url="";
        }

        $personal_email     = $this->get_in_str_val("personal_email");
        $desc     = $this->get_in_str_val("desc");
        $this->t_manager_info->field_update_list($uid,[
            "name"                =>$name,
            "gender"              =>$gender,
            "company"             =>$company,
            "education"           =>$education,
            "employee_level"      =>$employee_level,
            // "basic_pay"           =>$basic_pay,
            // "merit_pay"           =>$merit_pay,
            // "post_basic_pay"      =>$post_basic_pay,
            //"post_merit_pay"      =>$post_merit_pay,
            "department"          =>$department,
            "main_department"     =>$main_department,
            "post"                =>$post,
            "department_group"    =>$department_group,
            "email"               =>$email,
            "gra_school"          =>$gra_school,
            "gra_major"           =>$gra_major,
            "identity_card"       =>$identity_card,
            "order_end_time"      =>$order_end_time,
            "personal_email"      =>$personal_email,
            "personal_desc"                =>$desc,
            "become_full_member_time"      =>$become_full_member_time,
            "resume_url"     =>$resume_url
        ]);
        if($become_full_member_time>0){
            $this->t_manager_info->field_update_list($uid,[
                "become_full_member_flag"  =>1
            ]);
        }

        return $this->output_succ();
    }



    public function lesson_reset_cw_info() {
        $lessonid=$this->get_in_lessonid();
        $this->t_lesson_info->field_update_list($lessonid,[
            "lesson_intro" => "知识点1|知识点2",
            "lesson_name" => "课程1",
            "tea_cw_name" => "课件",
        ]);

        return $this->output_succ();
        // mysql> update t_lesson_info set lesson_intro="11|22", lesson_name="s", tea_cw_name="22" where lessonid=17963\G
    }
    public function course_set_status() {
        $orderid=$this->get_in_int_val("orderid");
        $contract_status=$this->get_in_int_val("contract_status");
        if (!($contract_status==1 || $contract_status==2) ) {
            return $this->output_err("出错") ;
        }

        $db_v=  $this->t_order_info->get_contract_status($orderid);
        if (!($db_v==1 || $db_v==2) ) {
            return $this->output_err("db 数据 出错;v=$db_v") ;
        }
        $this->t_order_info->field_update_list($orderid,[
            "contract_status"  =>$contract_status,
        ]);

        return $this->output_succ();
    }

    public function origin_get_key_list() {
        $key1=$this->get_in_str_val("key1");
        $key2=$this->get_in_str_val("key2");
        $key3=$this->get_in_str_val("key3");
        $key_str="key1";
        if ($key3){
            $key_str="key4";
        }else  if ($key2){
            $key_str="key3";
        }else  if ($key1){
            $key_str="key2";
        }
        $list=$this->t_origin_key->get_key_list($key1,$key2,$key3,$key_str);
        $list=\App\Helper\Common::sort_pinyin($list,"k");
        $last_level=$this->t_origin_key->get_last_level( $key1, $key2 );

        return $this->output_succ(["list"=> $list ,"last_level"=> $last_level]);

    }

    public function origin_get_key_list_bd() {
        $key1=$this->get_in_str_val("key1");
        $key2=$this->get_in_str_val("key2");
        $key3=$this->get_in_str_val("key3");
        $key_str="key1";
        if ($key3){
            $key_str="key4";
        }else  if ($key2){
            $key_str="key3";
        }else  if ($key1){
            $key_str="key2";
        }

        if ($key_str == 'key1') {
            $list['0']['k'] = 'BD';
        } else {
            $list=$this->t_origin_key->get_key_list($key1,$key2,$key3,$key_str);
        }
        return $this->output_succ(["list"=> $list ]);

    }


    public function origin_init_key_list() {
        $key1=$this->get_in_str_val("key1");
        $key2=$this->get_in_str_val("key2");
        $key3=$this->get_in_str_val("key3");

        $key1_list=$this->t_origin_key->get_key_list("","","","key1");
        $key2_list=[];
        $key3_list=[];
        $key4_list=[];
        if ( $key1 ) {
            $key2_list=$this->t_origin_key->get_key_list($key1,"","","key2");
            if ($key2) {
                $key3_list=$this->t_origin_key->get_key_list($key1,$key2,"","key3");
                if ($key3) {
                    $key4_list=$this->t_origin_key->get_key_list($key1,$key2,$key3,"key4");
                }
            }
        }
        return $this->output_succ([
            "key1_list"=>$key1_list,
            "key2_list"=>$key2_list,
            "key3_list"=>$key3_list,
            "key4_list"=>$key4_list,
        ]);

    }

    public function seller_init_group_info() {
        $main_type_name=$this->get_in_str_val("main_type_name");
        $main_group_name=$this->get_in_str_val("main_group_name");
        $group_name=$this->get_in_str_val("group_name");
        $main_type_list =["助教"=>1,"销售"=>2,"教务"=>3];
        $key2_list=[];
        $key3_list=[];
        $key4_list=[];
        if ( $main_type_name ) {
            $main_type = $main_type_list[$main_type_name];
            $key2_list=$this->t_admin_main_group_name->get_group_list($main_type);
            if ($main_group_name) {
                $main_groupid = $this->t_admin_main_group_name->get_groupid_by_group_name($main_group_name);
                $key3_list=$this->t_admin_group_name->get_group_name_list($main_type,$main_groupid);
                if ($group_name) {
                    $groupid = $this->t_admin_group_name->get_groupid_by_group_name($group_name);
                    $key4_list=$this->t_admin_group_user->get_user_list_new($groupid);
                }
            }
        }
        return $this->output_succ([
            "key2_list"=>$key2_list,
            "key3_list"=>$key3_list,
            "key4_list"=>$key4_list,
        ]);

    }

    public function seller_get_group_info() {
        $main_type_name=$this->get_in_str_val("main_type_name");
        $main_group_name=$this->get_in_str_val("main_group_name");
        $group_name=$this->get_in_str_val("group_name");
        $main_type_list =["助教"=>1,"销售"=>2,"教务"=>3,"教研"=>4,"全职老师"=>5,"薪资运营"=>6];

        $list=[];
        if ($group_name) {
            $groupid = $this->t_admin_group_name->get_groupid_by_group_name($group_name);
            $list=$this->t_admin_group_user->get_user_list_new($groupid);
        }else{
            if($main_group_name){
                $main_type = $main_type_list[$main_type_name];
                $main_groupid = $this->t_admin_main_group_name->get_groupid_by_group_name($main_group_name);
                $list= $this->t_admin_group_name->get_group_name_list($main_type,$main_groupid);
            }else{
                if($main_type_name){
                    $main_type = $main_type_list[$main_type_name];
                    $list = $this->t_admin_main_group_name->get_group_list($main_type);
                }
            }
        }

        return $this->output_succ(["list"=> $list ]);
    }



    public function update_course_type() {
        $userid        = $this->get_in_int_val('userid',-1);
        $orderid       = $this->get_in_int_val('orderid');
        $course_type = $this->get_in_int_val('course_type',-1);
        $stu_from_type = $this->get_in_int_val('stu_from_type',-1);
        $subject= $this->get_in_subject();
        $grade= $this->get_in_grade();
        $competition_flag = $this->get_in_int_val("competition_flag");

        $account=$this->get_account();
        if ( !($account =="jim" || $account =="echo" )  ){
            return $this->output_err("没有权限");
        }

        $this->t_order_info->field_update_list($orderid,[
            "stu_from_type" => $stu_from_type,
            "contract_type" => $course_type,
            "subject" => $subject,
            "grade" => $grade,
            "competition_flag" => $competition_flag,
        ]);

        $this->t_course_order->update_only_course_type($userid,$orderid,$course_type,$subject);
        $courseid=$this->t_course_order-> get_coursesid_by_orderid($orderid );

        $this->t_lesson_info-> set_subject_by_courseid($courseid,$subject );
        return outputjson_success();

    }


    public function opt_table_field_list(){
        $opt_type=$this->get_in_str_val("opt_type");
        $table_key=$this->get_in_str_val("table_key");
        $data=$this->get_in_str_val("data");
        $account=$this->get_account();
        $key="T_".$table_key."_".$account;
        switch ( $opt_type ) {
        case "set" :
            Redis::set($key, $data);
            return $this->output_succ();
            break;
        case "get" :
            $field_list=null;
            try {
                $field_list= \App\Helper\Utils::json_decode_as_array(Redis::get($key));
            }catch( \Exception $e  ) {

            }
            $table_config=\App\Config\page_table::get_config($table_key);
            $row_opt_list=null;
            $field_default_flag=true;
            $filter_list=null;
            $hide_filter_list=null;
            if ($field_list) {
                $field_default_flag=false;
            }
            if ($table_config) { //
                if (!$field_list){
                    $field_list=[];
                }
                if (!$field_list) {
                    foreach (  $table_config["field_list"] as  $field_name ) {
                        if (!isset($field_list[$field_name])){
                            $field_list[$field_name]=true;
                        }
                    }
                }
                $filter_list=@$table_config["filter_list"];
                $row_opt_list=@$table_config["row_opt_list"];
                $hide_filter_list=@$table_config["hide_filter_list"];
            }
            return $this->output_succ([
                "field_list"   => $field_list,
                "field_default_flag"   => $field_default_flag,
                "filter_list"  => $filter_list,
                "hide_filter_list"  => $hide_filter_list,
                "row_opt_list" => $row_opt_list,
            ]);
            break;
        default:
            break;
        }
    }

    public function  reload_account_power(){
        (new  login() )->reset_power($this->get_account());
        return $this->output_succ();
    }

    public function lesson_set_grade()  {
        $grade    = $this->get_in_grade();
        $lessonid = $this->get_in_lessonid();
        $this->t_lesson_info->field_update_list($lessonid,[
            "grade"  => $grade
        ]);
        return $this->output_succ();
    }

    public function reset_already_lesson_count(){
        $teacherid  = $this->get_in_teacherid();
        $start_time = $this->get_in_start_time_from_str(date("Y-m-01",time(NULL)) );
        $end_time   = $this->get_in_end_time_from_str_next_day(date("Y-m-d",(time(NULL)+86400)) );
        //得到student_list
        $list = $this->t_lesson_info->get_student_list_by_teacher($teacherid,$start_time,$end_time);
        foreach($list as $item){
            $studentid = $item["userid"];
            $this->t_lesson_info->reset_teacher_student_already_lesson_count($teacherid,$studentid);
        }
        return $this->output_succ();
    }

    public function set_lesson_record_info(){
        $lessonid             = $this->get_in_lessonid();
        $lesson_upload_time   = $this->get_in_int_val("lesson_upload_time",0);
        $record_audio_server1 = $this->get_in_str_val("record_audio_server1");
        $record_audio_server2 = $this->get_in_str_val("record_audio_server2");
        $gen_video_grade      = $this->get_in_int_val("gen_video_grade");
        $this->t_lesson_info->field_update_list($lessonid,[
            "lesson_upload_time"   => $lesson_upload_time ,
            "record_audio_server1" => $record_audio_server1,
            "record_audio_server2" => $record_audio_server2,
            "gen_video_grade"      => $gen_video_grade,
        ]);
        return $this->output_succ();
    }
    public function get_student_info_for_add_contract() {
        $userid=$this->get_in_userid();
        $data=$this->t_student_info->get_stu_all_info($userid);
        if($data) {
            $data["nick"] =trim($data["nick"]);
            $data["parent_name"] =trim($data["parent_name"]);
            if (!$data["parent_phone"]) {
                $data["parent_phone"]=$data["phone"];
            }
        }

        return $this->output_succ(["data"=>$data]);
    }
    public function seller_student_lesson_set_notify_flag () {
        $phone = $this->get_in_phone();
        $notify_flag= $this->get_in_int_val("notify_flag");
        $this->t_seller_student_info-> set_notify_lesson_flag( $phone, $notify_flag);

        return $this->output_succ();
    }
    public function get_wx_user_list()  {
        $page_num=$this->get_in_page_num();
        $nickname= $this->get_in_str_val("nickname");
        $ret_list=$this->t_wx_user_info->get_list_for_ajax_list($page_num,$nickname);
        foreach ($ret_list["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"update_time");
        }
        $ret_list["page_info"]  = $this->get_page_info_for_js( $ret_list["page_info"]   );
        return $this->output_succ( ['data' => $ret_list ]);
    }

    public function binding_wx_to_admin() {
        $wx_openid=$this->get_in_str_val("wx_openid");
        $id=$this->get_in_id();
        if(!$wx_openid) {
            $wx_openid=NULL;
        }
        $this->t_manager_info->field_update_list($id,[
            "wx_openid"=> $wx_openid,
        ]);
        return $this->output_succ();
    }
    public function get_comment_list_js () {
        $account  = $this->get_in_str_val("account");
        $page_num = $this->get_in_page_num();
        list($start_time,$end_time ) = $this->get_in_date_range(0,0);

        $ret_list=$this->t_book_revisit->get_list_by_account($page_num,$account,$start_time,$end_time);
        foreach($ret_list["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"revisit_time");
            //$this->cache_set_item_student_nick($item,"userid");
        }

        $ret_list["page_info"]  = $this->get_page_info_for_js( $ret_list["page_info"]   );
        return outputjson_success(array('data' => $ret_list ));

    }

    public function course_set_status_ex() {
        $courseid             = $this->get_in_courseid();
        $course_status        = $this->get_in_int_val("course_status");
        $teacherid            = $this->get_in_teacherid();
        $subject              = $this->get_in_int_val("subject");
         $grade                = $this->get_in_int_val("grade");
        $lesson_grade_type    = $this->get_in_int_val("lesson_grade_type");
        $default_lesson_count = $this->get_in_int_val("default_lesson_count");
        $account              = $this->get_account();

        $data = [
            "course_status"        => $course_status,
            "teacherid"            => $teacherid,
            "subject"              => $subject,
            "grade"                => $grade,
            "lesson_grade_type"    => $lesson_grade_type,
            "default_lesson_count" => $default_lesson_count,
        ];

        $ret = $this->t_course_order->field_update_list($courseid,$data);

        \App\Helper\Utils::logger("course info has update.courseid is".$courseid
                                  ." course data to:".json_encode($data)."account:".$account." time:".time());

        return $this->output_succ();
    }

    public function course_add_new() {
        $course_status        = $this->get_in_int_val("course_status");
        $teacherid            = $this->get_in_teacherid();
        $subject              = $this->get_in_int_val("subject");
        $lesson_grade_type    = $this->get_in_int_val("lesson_grade_type");
        $default_lesson_count = $this->get_in_int_val("default_lesson_count");
        $account              = $this->get_account();
        $userid               = $this->get_in_int_val("userid");
        $competition_flag     = $this->get_in_int_val("competition_flag");
        $is_kk_flag           = $this->get_in_int_val("is_kk_flag");
        $require_id           = $this->get_in_int_val("require_id",0);

        $stu_info = $this->t_student_info->get_student_simple_info($userid);
        if (!$stu_info["assistantid"] ) {
            return $this->output_err("未设置助教!");
        }

        $month = date("m",time());
        if($month>6 && $month <9){
            $stu_info['grade'] = \App\Helper\Utils::get_up_grade($stu_info['grade']);
            $lesson_grade_type = 1;
        }

        $this->t_course_order->row_insert([
            "userid"                => $userid,
            "teacherid"             => $teacherid,
            "subject"               => $subject,
            "grade"                 => $stu_info['grade'],
            "assistantid"           => $stu_info['assistantid'],
            "lesson_total"          => 0,
            "assigned_lesson_count" => 0,
            "default_lesson_count"  => $default_lesson_count,
            "competition_flag"      => $competition_flag,
            "add_time"              => time(),
            "lesson_grade_type"    => $lesson_grade_type,
            "course_status"        => $course_status,
            "is_kk_flag"           => $is_kk_flag
        ]);

        if($require_id>0){
            $courseid = $this->t_course_order->get_last_insertid();
            $lessonid = $this->t_test_lesson_subject_require->get_current_lessonid($require_id);
            $this->t_course_order->clean_ass_from_test_lesson_id($lessonid);
            $this->t_course_order->field_update_list($courseid,[
                "ass_from_test_lesson_id"=>$lessonid,
            ]);
        }


        return $this->output_succ();
    }


    public function course_add(){
        $userid           = $this->get_in_int_val("userid");
        $subject          = $this->get_in_int_val("subject");
        $competition_flag = $this->get_in_int_val("competition_flag");

        $stu_info = $this->t_student_info->get_student_simple_info($userid);
        if (!$stu_info["assistantid"] ) {
            return $this->output_err("未设置助教!");
        }
        $this->t_course_order->row_insert([
            "userid"                => $userid,
            "teacherid"             => 0,
            "subject"               => $subject,
            "grade"                 => $stu_info['grade'],
            "assistantid"           => $stu_info['assistantid'],
            "lesson_total"          => 0,
            "assigned_lesson_count" => 0,
            "default_lesson_count"  => 300,
            "competition_flag"      => $competition_flag,
            "add_time"              => time()
        ]);

        return $this->output_succ();
    }

    public function course_del(){
        $courseid = $this->get_in_courseid();
        //
        $lesson_count=$this->t_lesson_info->get_course_lesson_count($courseid);
        if ($lesson_count==0)  {
            $this->t_course_order->row_delete($courseid);
            return $this->output_succ();
        }else{
            return $this->output_err("已经有安排课程了，你得只能删除排空包！");
        }
    }
    public function course_set_assigned_lesson_count () {
        $courseid              = $this->get_in_courseid();
        $assigned_lesson_count = $this->get_in_int_val("assigned_lesson_count");
        $competition_flag      = $this->get_in_int_val("competition_flag");

        $userid=$this->t_course_order->get_userid($courseid);
        $lesson_total = $this->t_order_info->get_user_lesson_total($userid,$competition_flag);
        $g_assigned_lesson_count = $this->t_course_order->get_user_assigned_lesson_count($userid,$competition_flag,$courseid);
        if ( $g_assigned_lesson_count+$assigned_lesson_count/100.0>$lesson_total ){
            $max= $lesson_total - $g_assigned_lesson_count;
            return $this->output_err("最多只能是:$max" );
        }
        //检查最小

        $tmp_list =$this->t_course_order->get_list(-1,$courseid);
        $course_info=$tmp_list[0];
        if (!$course_info)  {
            return $this->output_err("课程包不存在" );
        }

        $min_lesson_count=$course_info["finish_lesson_count"] + $course_info["no_finish_lesson_count"];
        if ( $min_lesson_count > $assigned_lesson_count ) {
            $tmp=$min_lesson_count/100;
            return $this->output_err("最少是: $tmp, 你可以 删除 未上课 的排课 " );
        }

        $this->t_course_order->field_update_list($courseid,[
            "assigned_lesson_count" => $assigned_lesson_count
        ]);
        return $this->output_succ();
    }

    public function course_set_orderid() {
        $courseid = $this->get_in_courseid();
        $orderid  = $this->get_in_int_val("orderid");
        if ($this->get_account()=="jim") {
            $this->t_course_order->field_update_list($courseid,[
                "orderid" => $orderid,
            ]);
            return $this->output_succ();
        }else{
            return $this->output_err("没有权限:<");
        }
    }


    public function course_set_teacher_subject () {
        $courseid  = $this->get_in_courseid();
        $teacherid = $this->get_in_teacherid();
        $subject   = $this->get_in_subject();
        $this->t_lesson_info-> course_set_teacher_subject($courseid,$teacherid,$subject);
        return $this->output_succ();
    }

    public function update_teacher_month_money() {
        $logtime=strtotime($this->get_in_str_val("logtime"));

        $teacherid=$this->get_in_teacherid();
        $all_count=$this->get_in_int_val("all_count");
        $l1v1_count=$this->get_in_int_val("l1v1_count");
        $test_count=$this->get_in_int_val("test_count");
        $money_all_count=$this->get_in_int_val("money_all_count");
        $money_l1v1_count=$this->get_in_int_val("money_l1v1_count");
        $money_test_count=$this->get_in_int_val("money_test_count");

        if ($money_test_count+$money_l1v1_count != $money_all_count)  {
            return $this->output_err("金额不对 $money_test_count+$money_l1v1_count != $money_all_count ");
        }
        if ($test_count+$l1v1_count != $all_count)  {
           return  $this->output_err("课时不对 $test_count+$l1v1_count != $all_count ");
        }


        $db_id=$this->t_teacher_month_money->field_get_list_2($logtime,$teacherid,"teacherid");
        if (!$db_id) { //insert
            $this->t_teacher_month_money->row_insert([
                "logtime"   => $logtime,
                "teacherid"   => $teacherid,
            ]);
        }
        $pay_flag=$this->t_teacher_month_money->get_pay_flag($logtime,$teacherid);
        if($pay_flag) {
            return $this->output_err("已支付,不可修改");
        }


        $this->t_teacher_month_money->field_update_list_2($logtime,$teacherid,[
            "all_count"=>$all_count,
            "l1v1_count"=>$l1v1_count,
            "test_count"=>$test_count,
            "money_all_count"=>$money_all_count,
            "money_l1v1_count"=>$money_l1v1_count,
            "money_test_count"=>$money_test_count,
            "confirm_flag" => 1,
            "confirm_time" => time(NULL),
            "confirm_adminid" => $this->get_account_id(),
        ]);

        return $this->output_succ();
    }


    public function teacher_month_money_un_confirm() {
        $logtime=strtotime($this->get_in_str_val("logtime"));
        $teacherid=$this->get_in_teacherid();

        $pay_flag=$this->t_teacher_month_money->get_pay_flag($logtime,$teacherid);
        if($pay_flag) {
            return $this->output_err("已支付,不可修改");
        }

        $this->t_teacher_month_money->field_update_list_2($logtime,$teacherid,[
            "confirm_flag" => 0,
            "confirm_time" => time(NULL),
            "confirm_adminid" => $this->get_account_id(),
        ]);
        return $this->output_succ();
    }

    public function teacher_month_money_pay_flag() {
        $logtime=strtotime($this->get_in_str_val("logtime"));
        $teacherid=$this->get_in_teacherid();
        $pay_flag=$this->get_in_int_val("pay_flag",0);


        $confirm_flag=$this->t_teacher_month_money->get_confirm_flag($logtime,$teacherid);
        if (!$confirm_flag && $pay_flag  ) {
            return $this->output_err("未确认,不能支付") ;
        }

        $this->t_teacher_month_money->field_update_list_2($logtime,$teacherid,[
            "pay_flag" => $pay_flag,
            "pay_time" => time(NULL),
            "pay_adminid" => $this->get_account_id(),
        ]);
        return $this->output_succ();

    }

    public function admin_group_list_js() {
        $group_list=$this->t_authority_group->get_auth_groups();
        return $this->output_succ(["data"=> $group_list]);
    }
    public function admin_group_add() {
        $main_type=$this->get_in_str_val("main_type");
        $group_name=$this->get_in_str_val("group_name");
        $up_groupid=$this->get_in_int_val("up_groupid","");

        $this->t_admin_group_name->row_insert([
            "main_type"  => $main_type ,
            "group_name"  => $group_name,
            "up_groupid"=>$up_groupid
        ]);

        return $this->output_succ();

    }

    public function admin_group_add_new() {
        $main_type=$this->get_in_str_val("main_type");
        $group_name=$this->get_in_str_val("group_name");
        $up_groupid=$this->get_in_int_val("up_groupid","");
        $month = strtotime($this->get_in_str_val("start_time"));
        $max_groupid = $this->t_group_name_month->get_max_groupid($month);
        $groupid = $max_groupid + 1;

        $this->t_group_name_month->row_insert([
            "month"     =>$month,
            "main_type"  => $main_type ,
            "group_name"  => $group_name,
            "up_groupid"=>$up_groupid,
            "groupid"   =>$groupid
        ]);

        return $this->output_succ();

    }

    public function admin_main_group_add() {
        $main_type=$this->get_in_str_val("main_type");
        $group_name=$this->get_in_str_val("group_name");

        $this->t_admin_main_group_name->row_insert([
            "main_type"  => $main_type ,
            "group_name"  => $group_name,
        ]);

        return $this->output_succ();

    }

    public function admin_main_group_add_new() {
        $main_type=$this->get_in_str_val("main_type");
        $group_name=$this->get_in_str_val("group_name");
        $month = strtotime($this->get_in_str_val("start_time"));

        $max_groupid = $this->t_main_group_name_month->get_max_groupid($month);
        $groupid = $max_groupid + 1;
        $this->t_main_group_name_month->row_insert([
            "month"       => $month,
            "main_type"   => $main_type ,
            "group_name"  => $group_name,
            "groupid"     => $groupid
        ]);

        return $this->output_succ();

    }




    public function admin_group_edit( ) {
        $groupid=$this->get_in_int_val("groupid");
        $group_name=$this->get_in_str_val("group_name");
        $master_adminid=$this->get_in_str_val("master_adminid");

        $this->t_admin_group_name->field_update_list($groupid, [
                "group_name"  => $group_name,
                "master_adminid"  => $master_adminid,
        ]);

        return $this->output_succ();

    }

    public function admin_group_edit_new( ) {
        $groupid=$this->get_in_int_val("groupid");
        $group_name=$this->get_in_str_val("group_name");
        $master_adminid=$this->get_in_str_val("master_adminid");
        $month = strtotime($this->get_in_str_val("start_time"));

        $this->t_group_name_month->field_update_list_2($groupid,$month, [
            "group_name"  => $group_name,
            "master_adminid"  => $master_adminid,
        ]);

        return $this->output_succ();

    }


    public function admin_main_group_edit( ) {
        $groupid=$this->get_in_int_val("groupid");
        $group_name=$this->get_in_str_val("group_name");
        $master_adminid=$this->get_in_str_val("master_adminid");
        $this->t_admin_main_group_name->field_update_list($groupid, [
            "group_name"  => $group_name,
            "master_adminid"=>$master_adminid
        ]);
        return $this->output_succ();

    }

    public function admin_main_group_edit_new( ) {
        $groupid=$this->get_in_int_val("groupid");
        $group_name=$this->get_in_str_val("group_name");
        $master_adminid=$this->get_in_str_val("master_adminid");
        $month = strtotime($this->get_in_str_val("start_time"));
        $this->t_main_group_name_month->field_update_list_2($groupid,$month, [
            "group_name"  => $group_name,
            "master_adminid"=>$master_adminid
        ]);
        return $this->output_succ();

    }


    public function admin_group_del ()  {

        $groupid=$this->get_in_int_val("groupid");
        $this->t_admin_group_name->row_delete($groupid);
        $this->t_admin_group_user->del_by_groupid($groupid);
        return $this->output_succ();
    }

    public function admin_group_del_new()  {

        $groupid=$this->get_in_int_val("groupid");
        $month = strtotime($this->get_in_str_val("start_time"));
        $this->t_group_name_month->row_delete_2($groupid,$month);
        $this->t_group_user_month->del_by_groupid($groupid,$month);
        return $this->output_succ();
    }


    public function admin_main_group_del ()  {

        $groupid=$this->get_in_int_val("groupid");
        $this->t_admin_main_group_name->row_delete($groupid);
        $this->t_admin_group_name->update_by_up_groupid($groupid);
        return $this->output_succ();
    }

    public function admin_main_group_del_new ()  {

        $groupid=$this->get_in_int_val("groupid");
        $month = strtotime($this->get_in_str_val("start_time"));
        $this->t_main_group_name_month->row_delete_2($groupid,$month);
        $this->t_group_name_month->update_by_up_groupid($groupid,$month);
        return $this->output_succ();
    }


    public function admin_group_user_add()  {
        $groupid=$this->get_in_int_val("groupid");
        $adminid=$this->get_in_int_val("adminid");
        $main_type=$this->get_in_int_val("main_type");

        $db_groupid=$this->t_admin_group_user->get_groupid_by_adminid($main_type,$adminid);
        if ($db_groupid ) {//
            $group_name=$this->t_admin_group_name->get_group_name($db_groupid);
            return $this->output_err("此人已在[$group_name]中,不能添加");
        }

        $this->t_admin_group_user->row_insert([
            "groupid"   => $groupid,
            "adminid"   => $adminid,
        ]);
        return $this->output_succ();
    }

    public function admin_group_user_add_new()  {
        $groupid=$this->get_in_int_val("groupid");
        $adminid=$this->get_in_int_val("adminid");
        $main_type=$this->get_in_int_val("main_type");
        $month = strtotime($this->get_in_str_val("start_time"));

        $db_groupid=$this->t_group_user_month->get_groupid_by_adminid($main_type,$adminid,$month);
        if ($db_groupid ) {//
            $group_name=$this->t_group_name_month->get_group_name($db_groupid,$month);
            return $this->output_err("此人已在[$group_name]中,不能添加");
        }

       $this->t_group_user_month->row_insert([
            "groupid"   => $groupid,
            "adminid"   => $adminid,
            "month"     => $month
        ]);
        return $this->output_succ();
    }


    public function admin_group_user_del()  {
        $groupid=$this->get_in_int_val("groupid");
        $adminid=$this->get_in_int_val("adminid");
        $this->t_admin_group_user->row_delete_2( $groupid, $adminid);
        return $this->output_succ();
    }

    public function admin_group_user_del_new()  {
        $groupid=$this->get_in_int_val("groupid");
        $adminid=$this->get_in_int_val("adminid");
        $month = strtotime($this->get_in_str_val("start_time"));
        $this->t_group_user_month->delete_admin_info( $groupid, $adminid,$month);
        return $this->output_succ();
    }

    public function admin_main_group_user_del()  {
        $groupid=$this->get_in_int_val("groupid");
        $this->t_admin_group_name->row_delete( $groupid);
        return $this->output_succ();
    }
    public function admin_main_group_user_remove()  {
        $groupid=$this->get_in_int_val("groupid");
        $this->t_admin_group_name->field_update_list($groupid,['up_groupid'=>0]);
        return $this->output_succ();
    }


    public function set_stu_init_info_tmp(){
        $cc_id  = $this->get_account_id();
        $data = \App\Helper\Utils::json_decode_as_array($this->get_in_str_val("data"));

        $orderid  = $data['orderid'];

        $state_arr = $this->t_student_cc_to_cr->get_last_id_reject_flag_by_orderid($orderid);

        $data['post_time'] = time(NULL);

        $data['cc_id']     = $cc_id;
        $data["call_time"] = strtotime($data["call_time"]);

        $check_min=date("H")*60+ date("i")  ;

        if ($check_min<8*60 || $check_min > 22*60+30) {
           return $this->output_err("22:30-8:00: 不能提交") ;
        }

        if($data["week_lesson_num"]==0){
             return $this->output_err("每周课次不能为0");
        }
        if($data["except_lesson_count"]==0){
            return $this->output_err("每次课时不能为0");
        }


        if(!$state_arr || ($state_arr['reject_flag'] == 1 && $ispost==1) ){
            // 插入
            $ret = $this->t_student_cc_to_cr->row_insert([
                "cc_id"      => $data['cc_id'],
                "orderid"    => $data['orderid'],
                "post_time"  => $data['post_time'],
                "real_name"  => $data['real_name'],
                "gender"     => $data['gender'],
                "grade"      => $data['grade'],
                "birth"      => $data['birth'],
                "school"     => $data['school'],
                "xingetedian" => $data['xingetedian'],
                "aihao"      => $data['aihao'],
                "yeyuanpai"          => $data['yeyuanpai'],
                "parent_real_name"   => $data['parent_real_name'],
                "parent_email"   => $data['parent_email'],
                "relation_ship"  => $data['relation_ship'],
                "phone"      => $data['phone'],
                "call_time"  => $data['call_time'],
                "addr"       => $data['addr'],
                "subject_yingyu"  => $data['subject_yingyu'],
                "subject_yuwen"   => $data['subject_yuwen'],
                "subject_shuxue"  => $data['subject_shuxue'],
                "subject_wuli"    => $data['subject_wuli'],
                "subject_huaxue"  => $data['subject_huaxue'],
                "class_top"       => $data['class_top'],
                "grade_top"       => $data['grade_top'],
                "subject_info"    => $data['subject_info'],
                "order_info"      => $data['order_info'],
                "teacher"         => $data['teacher'],
                "teacher_info"    => $data['teacher_info'],
                "test_lesson_info"     => $data['test_lesson_info'],
                "mail_addr"   => $data['mail_addr'],
                "has_fapiao"  => $data['has_fapiao'],
                "fapai_title" => $data['fapai_title'],
                "lesson_plan" => $data['lesson_plan'],
                "parent_other_require" => $data['parent_other_require'],
                "except_lesson_count"  => $data['except_lesson_count'],
                "week_lesson_num"      => $data['week_lesson_num']
            ]);

        }else{
            // 更新
            $this->t_student_cc_to_cr->field_update_list($state_arr['id'],$data);
        }

        return $this->output_succ();

    }

    public function set_stu_init_info () {
        $userid = $this->get_in_userid();

        $data = \App\Helper\Utils::json_decode_as_array(  $this->get_in_str_val ("data"));
        $check_min=date("H")*60+ date("i")  ;

        if ($check_min<8*60 || $check_min > 22*60+30) {
           return $this->output_err("22:30-8:00: 不能提交") ;
        }

        if($data["week_lesson_num"]==0){
             return $this->output_err("每周课次不能为0");
        }
        if($data["except_lesson_count"]==0){
            return $this->output_err("每次课时不能为0");
        }


        $data["call_time"] = strtotime($data["call_time"]);

        $this->t_student_init_info->field_update_list($userid,$data);

        $this->t_student_info->field_update_list($userid,[
            "init_info_pdf_url"   =>  "true",
            "seller_adminid" => $this->get_account_id(),
        ]);


        $account=$this->get_account();
        $this->t_student_info->noti_ass_order($userid, $account );
        return $this->output_succ();
    }

    public function seller_student_ass_add() {

        $phone=$this->get_in_phone();
        $ass_adminid = $this->get_account_id();
        $this->t_seller_student_info->add_or_add_to_sub("",$phone,0,"转介绍",0,0,0,"","",time(NULL),true, 0 ,0, "", "",0, $ass_adminid  );
        return $this->output_succ();

    }

    public function seller_student_ass_del() {

        $phone = $this->get_in_phone();

        $assigned_time= $this->t_seller_student_info->get_admin_assign_time($phone);
        if($assigned_time>0) {
            return $this->output_err("已经被分配，不能删除");
        }

        $ret = $this->t_seller_student_info->delete_student($phone);
        return $this->output_succ();


    }

    public function seller_student_ass_set() {
        $phone         = $this->get_in_phone();
        $userid        = $this->get_in_userid();
        $nick          = $this->get_in_str_val("nick");
        $grade         = $this->get_in_grade();
        $subject       = $this->get_in_subject();
        $user_desc     = $this->get_in_str_val("user_desc");
        $old_user_desc     = $this->get_in_str_val("old_user_desc");
        $origin_userid = $this->get_in_int_val("origin_userid");

        $this->t_seller_student_info->field_update_list($phone,[
            "userid"    => $userid,
            "nick"      => $nick,
            "grade"     => $grade,
            "subject"   => $subject,
            "user_desc" => $user_desc,
        ]);
        $this->t_student_info->field_update_list($userid,[
            "origin" => "转介绍",
            "originid" =>  E\Estu_origin::V_1,
            "origin_userid" =>  $origin_userid,
        ]);

        if(trim($user_desc) != trim($old_user_desc)){
            $user_desc = trim($user_desc);
            $acc = $this->get_account();
            $revisit_time = time() ;
            $ret_stu      = $this->t_student_info->get_student_simple_info($userid);
            if(count($ret_stu) == 0){
                return  $this->output_err( "系统出错");
            }
            if ( $this->t_revisit_info->check_add_existed($userid,$revisit_time) ) {
                return  $this->output_succ();
            }

            $this->t_revisit_info->add_revisit_record($userid, $revisit_time, $ret_stu['nick'], "",
                                                                 $acc, $user_desc,3);

        }
        return $this->output_succ();
    }

    public function set_order_refund(){
        $orderid=$this->get_in_int_val("orderid");

        $ret = $this->t_order_refund->field_update_list($orderid,[
            "refund_status"=>1,
        ]);

        if(!$ret){
            return $this->output_err("更改失败!");
        }
        return $this->output_succ();
    }

    public function set_teacher_check_adminid()  {
        $teacherid_list_str = $this->get_in_str_val("teacherid_list");
        $teacherid_list     = \App\Helper\Utils::json_decode_as_int_array($teacherid_list_str);
        $check_adminid= $this->get_in_int_val("check_adminid");
        foreach ($teacherid_list as $teacherid) {
            $this->t_teacher_info->field_update_list($teacherid,[
                "check_adminid"  => $check_adminid,
            ]) ;
        }
        return $this->output_succ();
    }
    //
    public function stu_set_grade() {

    }

    public function course_set_new() {
        $teacherid = $this->get_in_int_val('teacherid');
        $phone     = $this->get_in_str_val('phone');
        $userid    = $this->get_in_int_val('userid');
        if (!$userid) {
            $phone_de = substr($phone,0,11);
            $userid=$this->t_phone_to_user->get_userid_by_phone($phone_de);
            $this->t_seller_student_info->field_update_list(
                $phone, [
                    "userid" => $userid
                ]
            );
        }
        $grade        = $this->get_in_int_val('grade');
        $subject      = $this->get_in_int_val('subject');
        $lesson_count = $this->get_in_int_val('lesson_count');
        $lesson_start = $this->get_in_str_val('lesson_start');
        $ymd          = @substr($lesson_start,0,10);
        $lesson_start = @strtotime($lesson_start);
        $lesson_end   = $this->get_in_str_val('lesson_end');
        $lesson_end   = @strtotime($ymd." ".$lesson_end);
        $st_lessonid  = $this->t_seller_student_info->field_get_value($phone,"st_arrange_lessonid");
        $orderid      = 1;
        if (empty($teacherid) || empty($lesson_end) || empty($lesson_start) ) {
            return $this->output_err("请填写完整!");
        }
        if($lesson_start < time()){
            return $this->output_err("课程开始时间过早!");
        }

        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");
        $assistantid  = $this->t_student_info->get_assistantid($userid);
        $courseid     = $this->t_course_order->add_course_info_new($orderid,$userid,$grade,$subject,100,2,0,1,1,$assistantid,$teacherid);
        $lessonid = $this->t_lesson_info->add_lesson(
            $courseid,0,
            $userid,
            0,
            2,
            $teacherid,
            $assistantid,
            $lesson_start,
            $lesson_end,
            $grade,
            $subject,
            100,
            $teacher_info["teacher_money_type"],
            $teacher_info["level"]
        );

        if ($lessonid) {
            $ret_row1 = $this->t_lesson_info->check_student_time_free($userid,$lessonid,$lesson_start,$lesson_end);
            if($ret_row1) {
                $error_lessonid = $ret_row1["lessonid"];
                $this->t_lesson_info->row_delete($lessonid);
                return $this->output_err(
                    "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
                );
            }

            $ret_row2=$this->t_lesson_info->check_teacher_time_free($teacherid,$lessonid,$lesson_start,$lesson_end);
            if($ret_row2) {
                $error_lessonid=$ret_row2["lessonid"];
                $this->t_lesson_info->row_delete($lessonid);
                return $this->output_err(
                    "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
                );
            }

            $this->t_homework_info->add(
                $courseid,
                0,
                $userid,
                $lessonid,
                $grade,
                $subject,
                $teacherid
            );
            $this->t_lesson_info->reset_lesson_list($courseid);
            $this->t_seller_student_info->field_update_list($phone,['st_arrange_lessonid'=>$lessonid]);
        }
        return $this->output_succ();
    }

    public function set_stu_grade() { //设置
        $userid     = $this->get_in_userid();
        $start_time = $this->get_in_start_time_from_str();
        $grade      = $this->get_in_grade();
        $lesson_confirm_start_time=\App\Helper\Config::get_lesson_confirm_start_time();
        $acc=$this->get_account();

        if($acc != "jim" && $acc != "adrian" && $acc != "cora" ) {
            if(!$this->t_order_info->has_1v1_order($userid)) {
                return $this->output_err("有合同了,不能修改年级,找jim处理");
            }else{
                return $this->output_err("没有权限");
            }
        }

        if ( $start_time < $lesson_confirm_start_time  ) {
            $start_time= $lesson_confirm_start_time;
        }

        $db_grade=$this->t_student_info->get_grade($userid);
        $this->t_student_info->field_update_list($userid,[
            "grade"  => $grade,
        ]);

        $this->t_lesson_info->update_grade_by_userid($userid,$start_time,$grade);
        $this->t_revisit_info->sys_log( $this->get_account(),
            $userid,
            "年级 [". E\Egrade::get_desc($db_grade) ."]=>[". E\Egrade::get_desc($grade) ."]"
        );

        return $this->output_succ();
    }

    public function regular_lesson_plan(){
        $userid     = $this->get_in_int_val('userid');
        $start_time = $this->get_in_str_val('start_time');
        $end_time   = $this->get_in_str_val('end_time');
        $st_time    = strtotime($start_time);
        $en_time    = strtotime($end_time);
        if($en_time < time(NULL)){
            return $this->output_err("该周课程已结束!");
        }
        if($st_time < time(NULL) && $en_time > time(NULL)){
            $st_time = time(NULL);
        }

        $ret = $this->t_week_regular_course->get_lesson_info(-1,$userid);
        if($ret){
            foreach ($ret as $item){
                $teacherid        = $item['teacherid'];
                $lesson_count     = $item['lesson_count'];
                $start            = $item['start_time'];
                $competition_flag = $item['competition_flag'];
                $week_time        = explode("-",$start);
                $week             = $week_time[0];
                $start            = $week_time[1];
                $end              = $item['end_time'];

                $list = $this->t_course_order->get_courseid_by_stu_tea($userid,$teacherid,$competition_flag);
                $arr= [];
                $regular_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                if($regular_start >= $st_time){
                    if(!$list){
                        return $this->output_err("没有周".$week." $start"."-"."$end"."对应老师的课程包");
                    }else{
                        foreach($list as $val){
                            $lesson_use = $this->t_lesson_info->get_lesson_count_all_without_lessonid(
                                $val['courseid'],""
                            );
                            $assigned_lesson_count = $this->t_course_order->get_assigned_lesson_count($val['courseid']);
                            if($assigned_lesson_count >= $lesson_use + $lesson_count){
                                $arr[] = $val['courseid'];
                            }
                        }
                        if(empty($arr)){
                            return $this->output_err("周".$week." $start"."-"."$end"."课时数不足,请确认!");
                        }else{
                            $lesson_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                            $lesson_end   = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$end);
                            $check        = $this->research_fulltime_teacher_lesson_plan_limit(
                                $teacherid,$userid,$lesson_count/100,$lesson_start,0
                            );
                            if($check){
                                return $check;
                            }

                            $courseid = $arr[0];
                            $item     = $this->t_course_order->field_get_list($courseid,"*");
                            if($item['subject'] <=0){
                                return outputJson(array('ret' => -1, 'info' => "有课程包未设置科目,请确认!",'data'=>$data));
                            }

                            if($item['lesson_grade_type']==0){
                                $grade = $this->t_student_info->get_grade($item["userid"]);
                            }elseif($item['lesson_grade_type']==1){
                                $grade = $item['grade'];
                            }else{
                                return $this->output_err("学生课程年级出错！请在课程包列表中修改！");
                            }

                            $teacher_info=$this->t_teacher_info->field_get_list($item["teacherid"],"teacher_money_type,level");
                            $default_lesson_count=0;
                            $lessonid = $this->t_lesson_info->add_lesson(
                                $item["courseid"],0,
                                $item["userid"],
                                0,
                                $item["course_type"],
                                $item["teacherid"],
                                $item["assistantid"],
                                0,0,
                                $grade,
                                $item["subject"],
                                $default_lesson_count,
                                $teacher_info["teacher_money_type"],
                                $teacher_info["level"],
                                $item["competition_flag"],
                                2,
                                $item['week_comment_num'],
                                $item['enable_video']
                            );

                            if ($lessonid) {
                                $this->t_homework_info->add(
                                    $item["courseid"],0,$item["userid"],$lessonid,$item["grade"],$item["subject"]
                                );
                            }
                            $this->t_lesson_info->reset_lesson_list($courseid);

                            $ret_row1 = $this->t_lesson_info->check_student_time_free(
                                $userid,$lessonid,$lesson_start,$lesson_end
                            );
                            if($ret_row1){
                                $error_lessonid = $ret_row1["lessonid"];
                                return $this->output_err(
                                    "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
                                );
                            }

                            $ret_row2=$this->t_lesson_info->check_teacher_time_free(
                                $teacherid,$lessonid,$lesson_start,$lesson_end
                            );
                            if($ret_row2) {
                                $error_lessonid=$ret_row2["lessonid"];
                                return $this->output_err(
                                    "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
                                );
                            }

                            $ret = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);
                            if ($ret) {
                                $this->t_lesson_info->field_update_list($lessonid,[
                                    "lesson_count" => $lesson_count
                                ]);
                                $this->t_lesson_info ->set_lesson_time($lessonid,$lesson_start,$lesson_end);
                            }else{
                                $str= $lesson_count/100;
                                return $this->output_err("课时不足,需要课时数:$str");
                            }
                        }
                    }
                }
            }
        }else{
            return $this->output_err("常规课表为空!");
        }

        return $this->output_succ();
    }

    public function regular_lesson_plan_count(){
        $userid           = $this->get_in_int_val('userid');
        $old_lesson_total = $this->get_in_str_val('old_lesson_total');
        $start_time       = $this->get_in_str_val('start_time');
        $end_time         = $this->get_in_str_val('end_time');
        $start_time_done  = strtotime($start_time." 00:00:00");
        $end_time_done    = strtotime($end_time." 23:59:59");

        $st_time = strtotime($start_time);
        $en_time = strtotime($end_time);
        if($en_time < time(NULL)){
            return $this->output_err("该周课程已结束!");
        }
        if($st_time < time(NULL) && $en_time > time(NULL)){
            $st_time = time(NULL);
        }
        $lesson_start_time = $this->get_in_str_val('lesson_start');
        if(!empty($lesson_start_time)){
            $lesson_start_time = json_decode($lesson_start_time);
        }else{
            $lesson_start_time = [];
        }
        $arr_week = [1=>"一",2=>"二",3=>"三",4=>"四",5=>"五",6=>"六",7=>"日"];
        $ret = $this->t_week_regular_course->get_lesson_info(-1,$userid);
        if($ret){
            foreach ($ret as $item){
                $teacherid        = $item['teacherid'];
                $lesson_count     = $item['lesson_count'];
                $competition_flag = $item['competition_flag'];
                $start            = $item['start_time'];
                $week_time        = explode("-",$start);
                $week             = $week_time[0];
                $weeker           = $arr_week[$week];
                $start            = $week_time[1];
                $end              = $item['end_time'];

                $list = $this->t_course_order->get_courseid_by_stu_tea($userid,$teacherid,$competition_flag);
                $arr  = [];
                $regular_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                if($regular_start >= $st_time && !in_array($regular_start,$lesson_start_time)){
                    if(!$list){
                        $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                        return outputJson(array('ret' => -1, 'info' => "没有周".$weeker." $start"."-"."$end"."对应老师的课程包",'data'=>$data));
                    }else{
                        foreach($list as $val){
                            $lesson_use = $this->t_lesson_info->get_lesson_count_all_without_lessonid($val['courseid'],"");
                            $assigned_lesson_count= $this->t_course_order->get_assigned_lesson_count($val['courseid']);
                            if($val['course_type']==0 || $val['course_type']==3){
                                if($assigned_lesson_count >= $lesson_use + $lesson_count){
                                    $arr[] = $val['courseid'];
                                }
                            }
                        }
                        if(empty($arr)){
                            $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                            return outputJson(array('ret' => -1, 'info' => "周".$weeker." $start"."-"."$end"."课时数不足,请确认!",'data'=>$data));
                        }else{
                            $courseid = $arr[0];
                            $item     = $this->t_course_order->field_get_list($courseid,"*");
                            if($item['subject'] <=0){
                                return outputJson(array('ret' => -1, 'info' => "有课程包未设置科目,请确认!"));
                            }

                            if($item['lesson_grade_type']==0){
                                $grade = $this->t_student_info->get_grade($item["userid"]);
                            }else{
                                $grade = $item['grade'];
                            }

                            $teacher_info = $this->t_teacher_info->field_get_list($item["teacherid"],"teacher_money_type,level");
                            $default_lesson_count=0;

                            $lessonid = $this->t_lesson_info->add_lesson(
                                $item["courseid"],0,
                                $item["userid"],
                                0,
                                $item["course_type"],
                                $item["teacherid"],
                                $item["assistantid"],
                                0,0,
                                $grade,
                                $item["subject"],
                                $default_lesson_count,
                                $teacher_info["teacher_money_type"],
                                $teacher_info["level"],
                                $item["competition_flag"],
                                2,
                                $item['week_comment_num']
                            );

                            if ($lessonid) {
                                $this->t_homework_info->add(
                                    $item["courseid"],
                                    0,
                                    $item["userid"],
                                    $lessonid,
                                    $item["grade"],
                                    $item["subject"]);
                            }

                            $this->t_lesson_info->reset_lesson_list($courseid);

                            $lesson_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                            $lesson_end = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$end);
                            $check =  $this->research_fulltime_teacher_lesson_plan_limit($teacherid,$userid,$lesson_count/100,$lesson_start,0);
                            $ret_row1 = $this->t_lesson_info->check_student_time_free($userid,$lessonid,$lesson_start,$lesson_end);
                            if($ret_row1) {
                                $error_lessonid=$ret_row1["lessonid"];
                                $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                                return outputJson(array('ret' => -1, 'info' =>  "<div>有现存的学生课程与周".$weeker." $start"."-"."$end"."的课冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div>",'data'=>$data));
                            }

                            $ret_row2=$this->t_lesson_info->check_teacher_time_free($teacherid,$lessonid,$lesson_start,$lesson_end);
                            if($ret_row2) {
                                $error_lessonid=$ret_row2["lessonid"];
                                $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                                return outputJson(array('ret' => -1, 'info' =>" <div>有现存的老师课程与周".$weeker." $start"."-"."$end"."的课冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div>",'data'=>$data));
                            }

                            if($check){
                                return $check;
                            }

                            $ret = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);

                            if ($ret) {
                                $this->t_lesson_info->field_update_list($lessonid,[
                                    "lesson_count" => $lesson_count
                                ]);
                                $this->t_lesson_info->set_lesson_time($lessonid,$lesson_start,$lesson_end);
                            }else{
                                $str= $lesson_count/100;
                                return $this->output_err("课时不足,需要课时数:$str");
                            }
                        }
                    }

                }
            }


        }else{
            return $this->output_err("常规课表为空!");
        }

        $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
        if($old_lesson_total >= $data){
            return outputJson(array('ret' => -1, 'info' => "无课可排"));
        }else{
            return json_encode($data);
        }
    }

    public function regular_lesson_plan_count_summer(){
        $userid           = $this->get_in_int_val('userid');
        $old_lesson_total = $this->get_in_str_val('old_lesson_total');
        $start_time       = $this->get_in_str_val('start_time');
        $end_time         = $this->get_in_str_val('end_time');
        $start_time_done  = strtotime($start_time." 00:00:00");
        $end_time_done    = strtotime($end_time." 23:59:59");

        $st_time = strtotime($start_time);
        $en_time = strtotime($end_time);
        if($en_time < time(NULL)){
            return $this->output_err("该周课程已结束!");
        }
        if($st_time < time(NULL) && $en_time > time(NULL)){
            $st_time = time(NULL);
        }
        $lesson_start_time = $this->get_in_str_val('lesson_start');
        if(!empty($lesson_start_time)){
            $lesson_start_time = json_decode($lesson_start_time);
        }else{
            $lesson_start_time = [];
        }
        $arr_week = [1=>"一",2=>"二",3=>"三",4=>"四",5=>"五",6=>"六",7=>"日"];
        $ret = $this->t_summer_week_regular_course->get_lesson_info(-1,$userid);
        if($ret){
            foreach ($ret as $item){
                $teacherid        = $item['teacherid'];
                $lesson_count     = $item['lesson_count'];
                $competition_flag = $item['competition_flag'];
                $start            = $item['start_time'];
                $week_time        = explode("-",$start);
                $week             = $week_time[0];
                $weeker           = $arr_week[$week];
                $start            = $week_time[1];
                $end              = $item['end_time'];
                $list = $this->t_course_order->get_courseid_by_stu_tea($userid,$teacherid,$competition_flag);
                $arr  = [];
                $regular_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                if($regular_start >= $st_time && !in_array($regular_start,$lesson_start_time)){
                    if(!$list){
                        $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                        return outputJson(array('ret' => -1, 'info' => "没有周".$weeker." $start"."-"."$end"."对应老师的课程包",'data'=>$data));
                    }else{
                        foreach($list as $val){
                            $lesson_use = $this->t_lesson_info->get_lesson_count_all_without_lessonid($val['courseid'],"");
                            $assigned_lesson_count= $this->t_course_order->get_assigned_lesson_count($val['courseid']);
                            if($val['course_type']==0 || $val['course_type']==3){
                                if($assigned_lesson_count >= $lesson_use + $lesson_count){
                                    $arr[] = $val['courseid'];
                                }
                            }
                        }
                        if(empty($arr)){
                            $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                            return outputJson(array('ret' => -1, 'info' => "周".$weeker." $start"."-"."$end"."课时数不足,请确认!",'data'=>$data));
                        }else{
                            $courseid = $arr[0];
                            $item     = $this->t_course_order->field_get_list($courseid,"*");
                            if($item['subject'] <=0){
                                return outputJson(array('ret' => -1, 'info' => "有课程包未设置科目,请确认!"));
                            }

                            if($item['lesson_grade_type']==0){
                                $grade = $this->t_student_info->get_grade($item["userid"]);
                            }else{
                                $grade = $item['grade'];
                            }

                            $teacher_info = $this->t_teacher_info->field_get_list($item["teacherid"],"teacher_money_type,level");
                            $default_lesson_count=0;

                            $lessonid=$this->t_lesson_info->add_lesson(
                                $item["courseid"],0,
                                $item["userid"],
                                0,
                                $item["course_type"],
                                $item["teacherid"],
                                $item["assistantid"],
                                0,0,
                                $grade,
                                $item["subject"],
                                $default_lesson_count,
                                $teacher_info["teacher_money_type"],
                                $teacher_info["level"],
                                $item["competition_flag"],
                                2,
                                $item['week_comment_num']
                            );

                            if ($lessonid) {
                                $this->t_homework_info->add(
                                    $item["courseid"],
                                    0,
                                    $item["userid"],
                                    $lessonid,
                                    $item["grade"],
                                    $item["subject"]);
                            }

                            $this->t_lesson_info->reset_lesson_list($courseid);

                            $lesson_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                            $lesson_end = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$end);
                            $check =  $this->research_fulltime_teacher_lesson_plan_limit($teacherid,$userid,$lesson_count/100,$lesson_start,0);
                            $ret_row1 = $this->t_lesson_info->check_student_time_free($userid,$lessonid,$lesson_start,$lesson_end);
                            if($ret_row1) {
                                $error_lessonid=$ret_row1["lessonid"];
                                $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                                return outputJson(array('ret' => -1, 'info' =>  "<div>有现存的学生课程与周".$weeker." $start"."-"."$end"."的课冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div>",'data'=>$data));
                            }

                            $ret_row2=$this->t_lesson_info->check_teacher_time_free($teacherid,$lessonid,$lesson_start,$lesson_end);
                            if($ret_row2) {
                                $error_lessonid=$ret_row2["lessonid"];
                                $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                                return outputJson(array('ret' => -1, 'info' =>" <div>有现存的老师课程与周".$weeker." $start"."-"."$end"."的课冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div>",'data'=>$data));
                            }

                            if($check){
                                return $check;
                            }

                            $ret = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);

                            if ($ret) {
                                $this->t_lesson_info->field_update_list($lessonid,[
                                    "lesson_count" => $lesson_count
                                ]);
                                $this->t_lesson_info->set_lesson_time($lessonid,$lesson_start,$lesson_end);
                            }else{
                                $str= $lesson_count/100;
                                return $this->output_err("课时不足,需要课时数:$str");
                            }
                        }
                    }

                }
            }


        }else{
            return $this->output_err("常规课表为空!");
        }

        $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
        if($old_lesson_total >= $data){
            return outputJson(array('ret' => -1, 'info' => "无课可排"));
        }else{
            return json_encode($data);
        }
    }


    public function get_question_info_by_noteid(){
        $noteid=$this->get_in_int_val('noteid');
        $data = $this->t_question->get_question_tongji($noteid);
        $num = strlen($noteid);
        #$del_value=sprintf( "%06d", $note_id%1000000);
        $main_note_id = substr($noteid,0,$num-4)."0000";
        $data["main_note_name"] = $this->t_lesson_note->get_note_name($main_note_id);
        if( empty($data["main_note_name"]))  $data["main_note_name"]="";
        $second_note_id = substr($noteid,0,$num-2)."00";
        $data["second_note_name"] = $this->t_lesson_note->get_note_name($second_note_id);
        if(empty($data["second_note_name"]==0))  $data["second_note_name"]="";
        E\Esubject::set_item_value_str($data,"subject");
        E\Egrade_part::set_item_value_str($data,"grade");


        return json_encode($data);
    }


    public function cancel_lesson_by_userid()
    {
        
        $start_time = strtotime("2017-07-01");
        dd($start_time);
        $end_time = strtotime("2017-09-01");
        $list= $this->t_psychological_teacher_time_list->get_info_by_time($start_time,$end_time);
        foreach($list as &$val){
            $val["day_str"] = date("Y-m-d",$val["day"]);
        }
        dd($list);
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info($start_time);
        foreach($ass_month as $k=>$val){
            
            $this->t_month_ass_student_info->get_field_update_arr($k,$end_time,1,[
                "read_student_last"  =>$val["read_student"],
                "userid_list_last"   =>$val["userid_list"]
            ]);                    
        }
        dd($ass_month);

        

        dd($lesson_count_list);
        $start_time = strtotime("2017-07-01");
        $end_time = strtotime("2017-08-01");
        $qz_tea_arr=[51094];
        $rr = $this->get_fulltime_teacher_lesson_per($qz_tea_arr,$start_time,$end_time);
        dd($rr);
        
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = $start_time+86400;

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,16);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                if(time() >= ($val["lesson_start"]-1800)){
                    
                    $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                    if($openid){
                        $lesson_time     = date("m-d H:i",$val['lesson_start'])."-".date("H:i",$val['lesson_end']);
                        /**
                         * 标题        课前提醒
                         * template_id gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk
                         * {{first.DATA}}
                         * 上课时间：{{keyword1.DATA}}
                         * 课程类型：{{keyword2.DATA}}
                         * 教师姓名：{{keyword3.DATA}}
                         * {{remark.DATA}}
                         */
                        $template_id      = "gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk";
                        $data['first']    = "老师您好,您于30分钟后有一节模拟试听课";
                        $data['keyword1'] = $lesson_time;
                        $data['keyword2'] = "模拟试听课";
                        $data['keyword3'] = $val["tea_nick"];

                        $data['remark']   = "开课前十五分钟可提前进入课堂，请及时登录老师端，做好课前准备工作";
                        $url = "";                      

                        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
 
                        $wx_before_thiry_minute_remind_flag = 1;
                    }else{
                        $wx_before_thiry_minute_remind_flag = 2;
                        \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
                    }

                    $this->t_lesson_info->field_update_list($val['lessonid'],[
                        "wx_before_thiry_minute_remind_flag"   => $wx_before_thiry_minute_remind_flag
                    ]);
 
                }
               

            }
        }

       
    
        
        dd($lesson_list);

        $teacherid = 240314; 
        $teacher_info  = $this->t_teacher_info->get_teacher_info($teacherid);
        $ret = $this->add_trial_train_lesson($teacher_info,1);
        $lessonid = $this->t_lesson_info->get_last_insertid();
        dd($lessonid);
        for($i=1;$i<=50;$i++){
            \App\Helper\office_cmd::add_one(1,$i,0);
        }
        dd(111);
        $sync_data_list=\App\Helper\Common::redis_get_json("office_cmd");
        dd($sync_data_list);
        $start_time = strtotime("2017-07-01");
        $end_time = strtotime("2017-08-01");
        $this->t_month_ass_student_info->get_field_update_arr(324,$start_time,1,[
            "kk_num"  =>31
        ]);
        dd(111);

        $adminid_exist = $task->t_month_ass_student_info->get_ass_month_info($start_time,$k,1);

        $start_time = strtotime("2017-07-01");
        $end_time = strtotime("2017-08-01");
        $ret = $this->t_lesson_info_b2->get_teacher_regular_lesson_info($start_time,$end_time);
        foreach($ret["list"] as &$val){
            E\Esubject::set_item_value_str($val,"subject");
            E\Egrade_part_ex::set_item_value_str($val,"grade_part_ex");
            E\Egrade_range::set_item_value_str($val,"grade_start");
            E\Egrade_range::set_item_value_str($val,"grade_end");
            if($val["train_through_new_time"] !=0){
                $val["work_day"] = ceil((time()-$val["train_through_new_time"])/86400)."天";
            }else{
                $val["work_day"] ="";
            }

  
        }
        return $this->Pageview(__METHOD__,$ret);
        $last_time = $this->t_month_ass_warning_student_info->get_last_time_by_userid(133286);
        dd(date("Y-m-d H:i:s",$last_time));
        

        $ret = $this->t_teacher_info->get_all_tea_phone_location();
        foreach($ret["list"] as &$item){
            E\Eidentity::set_item_value_str($item);
            if($item["train_through_new_time"] !=0){
                $item["work_day"] = ceil((time()-$item["train_through_new_time"])/86400)."天";
            }else{
                $item["work_day"] ="";
            }

            if(empty($item["address"])){
                $item["location"] = \App\Helper\Common::get_phone_location($item["phone"]);  
                $item["location"]   = substr($item["location"], 0, -6);
            }else{
                $item["location"]= $item["address"];
            }


        }
        return $this->Pageview(__METHOD__,$ret);
        dd($ret);
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        $this->set_in_value("quarter_start",$start_time);
        $quarter_start = $this->get_in_int_val("quarter_start");

        $teacher_money_type       = $this->get_in_int_val("teacher_money_type",4);
        $arr=[161841,167237,135045,135265,146813];
        $list = $this->t_teacher_info->get_teacher_info_by_money_type_new($teacher_money_type,$start_time,$end_time,$arr);
        dd($list);

 
        $page_num = $this->get_in_page_num();
        $userid   = $this->get_in_userid();
        $userid= 57676;
        $teacherid = $this->get_in_teacherid();
        $teacherid = 85081;
        $ret_list = $this->t_course_order-> get_all_list($page_num,$userid, $teacherid ,1);
        foreach ($ret_list["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            E\Esubject::set_item_value_str($item);
        }


        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        dd($ret_list);
        return $this->output_succ(["data"=> $ret_list]);

        $month = time()-30*86400;
        $time = time()-86400;
        $date_week = \App\Helper\Utils::get_week_range($time,1);
        $lstart = $date_week["sdate"];
        $lend = $date_week["edate"];
        $ret_info = $this->t_month_ass_warning_student_info->get_week_warning_info($lstart);
        foreach($ret_info as $v){
            $userid = $v["userid"];
            $info = $this->t_month_ass_warning_student_info->get_warning_info_by_userid($userid,$month);
            if(!empty($info)  && $info["ass_renw_flag"] >0){
                $this->t_month_ass_warning_student_info->field_update_list($v["id"],[
                    "ass_renw_flag"  =>$info["ass_renw_flag"],
                    "no_renw_reason" =>$info["no_renw_reason"],
                    "renw_price"     =>$info["renw_price"],
                    "renw_week"      =>$info["renw_week"],
                    "master_renw_flag" =>$info["master_renw_flag"],
                    "master_no_renw_reason"=>$info["master_no_renw_reason"]
                ]);
            }

        }
        dd($ret_info);

        $this->switch_tongji_database();
        $start_time = strtotime("2017-06-18");
        $end_time = strtotime("2017-08-01");
        $ret= $this->t_lesson_info_b2->tongji_1v1_lesson_time($start_time,$end_time);
        $arr=[];$week=[];
        foreach($ret as $val){
            $teacherid = $val["teacherid"];
            @$arr[$teacherid][$val["day"]] = $val["time"];
            @$arr[$teacherid]["realname"] = $val["realname"];
            if($val["week"]==0){

                @$week[$teacherid]["seven"] +=$val["time"];
            }elseif($val["week"]==1){
                @$week[$teacherid]["one"] +=$val["time"];
            }
            @$week[$teacherid]["all"] +=$val["time"];


        }

        $all=["realname"=>"全部"];
        foreach($arr as $k=>&$item){
            $item["seven"] = @$week[$k]["seven"];
            $item["one"] = @$week[$k]["one"];
            @$all["20170618"] += @$item["20170618"];
            @$all["20170619"] += @$item["20170619"];
            @$all["20170625"] += @$item["20170625"];
            @$all["20170626"] += @$item["20170626"];
            @$all["20170702"] += @$item["20170702"];
            @$all["20170703"] += @$item["20170703"];
            @$all["20170709"] += @$item["20170709"];
            @$all["20170710"] += @$item["20170710"];
            @$all["20170716"] += @$item["20170716"];
            @$all["20170717"] += @$item["20170717"];
            @$all["20170717"] += @$item["20170723"];
            @$all["20170717"] += @$item["20170724"];
            @$all["20170717"] += @$item["20170730"];
            @$all["20170717"] += @$item["20170731"];
            @$all["seven"] += @$item["seven"];
            @$all["one"] += @$item["one"];
            @$all["all"] += @$item["all"];
        }
        array_unshift($arr,$all);

        foreach($arr as &$v){
            foreach($v as $s=>$kk){
                if(!in_array($s,["realname","one","seven"])){
                    $v[$s] = !empty($kk)?round($kk/3600,1)."小时":"";
                }
            }
            $v["seven_hour"] =  !empty($v["seven"])?round($v["seven"]/3600,1)."小时":"";
            $v["seven_day"] = !empty($v["seven"])?round($v["seven"]/3600*8,2)."天":"";
            $v["one_hour"] = !empty($v["one"])?round($v["one"]/3600,1)."小时":"";
            $v["one_day"] = !empty($v["one"])?round($v["one"]/3600*8,2)."天":"";
            $v["all_hour"] = !empty($v["all"])?round($v["all"]/3600,1)."小时":"";
            $v["all_day"] = !empty($v["all"])?round($v["all"]/3600*8,2)."天":"";
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($arr);
        return $this->pageView(__METHOD__,$ret_info);

        dd($arr);
        $page_info = $this->get_in_page_info();
        $end_time = strtotime("2017-07-01");
        $ret_list=$this->t_order_info->get_order_list_caiwu(
            $page_info,$start_time,$end_time);
        foreach($ret_list['list'] as &$item ){
            if(empty($item["lesson_start"]) && $item["order_time"] < strtotime(date("2016-11-01")) && $item["contract_type"]==0){
                $userid= $item["userid"];
                $item["lesson_start"] = $this->t_lesson_info->get_user_test_lesson_start($userid,$item["order_time"]);
            }
            $item['price']= $item['price']/100;
            $item['contract_status'] = E\Econtract_status::get_desc($item["contract_status"]);
            E\Egrade::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item);
            E\Econtract_from_type::set_item_value_str($item,"stu_from_type");
            E\Efrom_type::set_item_value_str($item);
            E\Ecompetition_flag::set_item_value_str($item);
            if (!$item["stu_nick"]) {
                $item["stu_nick"]=$item["stu_self_nick"];
            }

            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time");
            if($item["grade"]>=100 && $item["grade"] <200){
                $item["grade_part"]="小学";
            }else if($item["grade"]>=200 && $item["grade"] <300){
                $item["grade_part"]="初中";
            }else if($item["grade"]>=300 && $item["grade"] <400){
                $item["grade_part"]="高中";
            }
            $item["lesson_count_all"] = $item["lesson_total"]*$item["default_lesson_count"]/100;



        }

        return $this->Pageview(__METHOD__,$ret_list);

        dd($ret_list);

        $list = $this->t_month_ass_warning_student_info->get_stu_warning_info(2);
        $warning_list = $this->t_student_info->get_warning_stu_list();
        foreach($warning_list as $item){
            $userid= $item["userid"];
            if(!isset($list[$userid])){
                $this->t_month_ass_warning_student_info->row_insert([
                    "adminid"        =>$item["uid"],
                    "userid"         =>$userid,
                    "groupid"        =>$item["groupid"],
                    "group_name"     =>$item["group_name"],
                    "warning_type"   =>2,
                    "month"  =>time()
                ]);

            }else{
                $id = $list[$userid]["id"];
                $this->t_month_ass_warning_student_info->field_update_list($id,[
                    "month"  =>time()
                ]);
            }
        }
        dd($list);

        $phone=13817759346;
        $flag=0;
        $record_info=9999;
        $data=[];
        $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $url = "";
        if($flag==1){
            $data['first']    = "老师您好,恭喜您已经成功通过试讲";
            $data['keyword1'] = "通过";
            $data['keyword2'] = "\n账号:".$phone
                              ."\n密码:123456"
                              ."\n新师培训群号：315540732"
                              ."\n请在【我的培训】或【培训课程】中查看培训课程,每周我们都会组织新入职老师的培训,帮助各位老师熟悉使用软件,提高教学技能,请您准时参加,培训通过后我们会及时给您安排试听课";
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "理优期待与你一起共同进步,提供高质量教学品质";
            $url="https://jq.qq.com/?_wv=1027&k=4Bik1eq";

        }else if($flag==0){
            $data['first']    = "老师您好,通过评审老师的1对1面试,很抱歉您没有通过面试审核,希望您再接再厉";
            $data['keyword1'] = "未通过";
            $data['keyword2'] = "\n您的面试反馈情况是".$record_info
                              ."\n如果对于面试结果有疑问，请添加试讲答疑2群，群号：26592743";
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "理优教育致力于打造高水平的教学服务团队,期待您能通过下次面试,加油!如对面试结果有疑问,请联系招聘老师";
            $url="https://jq.qq.com/?_wv=1027&k=4BiqfPA";

        }

        $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
        \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);

        dd(111);
        $start= strtotime("2017-06-18");
        $end_time= time();
        $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start,$end_time);
        $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed("",$start,$end_time);
        foreach($teacher_arr as $k=>$val){
            if(!isset($teacher_list[$k])){
                $teacher_list[$k]=$k;
            }
        }

        $train_all = $this->t_lesson_info_b2->get_all_train_num_real($start,$end_time,$teacher_list,-1);

        dd($train_all);

        $ret = dispatch( new \App\Jobs\SendEmailNew(
            "jhp0416@163.com","【理优1对1】试讲邀请和安排","尊敬的".$realname."老师：<br>
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
 2、指定内容<a href='http://file.leo1v1.com/index.php/s/pUaGAgLkiuaidmW'>点击下载</a><br>
 [结果通知]<br>
  <img src='http://admin.yb1v1.com/images/lsb.png' alt='对不起,图片失效了'><br>

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
  <img src='http://admin.yb1v1.com/images/sjdy.png' alt='对不起,图片失效了'><br>

【LEO】试讲-答疑QQ群<br><br>

【岗位介绍】<br>
名称：理优在线1对1授课教师（通过理优教师端进行网络语音或视频授课）<br>
时薪：50-100RMB<br><br>

【关于理优】<br>
理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）"
        ));

    }

    public function get_admin_wx_info() {
        $account= $this->get_account();
        $ret=$this->t_manager_info->get_info_by_account($account,"wx_id,name,phone");
        return $this->output_succ(["data" => $ret]);
    }
    public function send_seller_sms_msg() {
        $phone=$this->get_in_phone_ex();
        $name=$this->get_in_str_val("name");
        $wx_id=$this->get_in_str_val("wx_id");
        $seller_phone=$this->get_in_str_val("seller_phone");
        $template_code= 15960017 ;
        $userid= $this->t_seller_student_new->get_userid_by_phone($phone);
        $admin_revisiterid =$this->t_seller_student_new->get_admin_revisiterid($userid);
        if ($admin_revisiterid != $this->get_account_id() ) {
            return $this->output_err("这个例子还不是你的,不能发短信");
        }

        $ret=(new notice())-> sms_common( $phone,0,
                                          $template_code,[
                                              "name"  => $name,
                                              "wx_id"  => $wx_id,
                                              "phone"  => $seller_phone,
                                          ]);
        return $this->output_bool_ret($ret);
    }

    public function get_group_list_new ()
    {
        $main_type    = $this->get_in_int_val("main_type");
        $page_num     = $this->get_in_page_num();
        $ret_info   = $this->t_admin_group_name->get_group_list_new($page_num,$main_type);
        foreach($ret_info['list'] as &$item){
            $item['group_master_nick']= $this->cache_get_account_nick($item['master_adminid']);
        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return outputjson_success(array('data' => $ret_info));

    }

    public function get_group_list_new_month()
    {
        $main_type    = $this->get_in_int_val("main_type");
        $page_num     = $this->get_in_page_num();
        $month        = strtotime($this->get_in_str_val("start_time"));
        $ret_info   = $this->t_group_name_month->get_group_list_new($page_num,$main_type,$month);
        foreach($ret_info['list'] as &$item){
            $item['group_master_nick']= $this->cache_get_account_nick($item['master_adminid']);
        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return outputjson_success(array('data' => $ret_info));

    }


    public function set_up_groupid(){
        $groupid    = $this->get_in_int_val("groupid");
        $up_groupid    = $this->get_in_int_val("up_groupid");
        $this->t_admin_group_name->field_update_list($groupid,['up_groupid'=>$up_groupid]);
        return $this->output_succ();
    }

    public function set_up_groupid_new(){
        $groupid    = $this->get_in_int_val("groupid");
        $up_groupid    = $this->get_in_int_val("up_groupid");
        $month        = strtotime($this->get_in_str_val("start_time"));
        $this->t_group_name_month->field_update_list_2($groupid,$month,['up_groupid'=>$up_groupid]);
        return $this->output_succ();
    }


    public function get_student_seller_list()
    {

        //return outputjson_success(array('data' => []));
        $type       = $this->get_in_str_val("type","teacher");
        $gender     = $this->get_in_int_val('gender',-1);
        $id         = $this->get_in_int_val('id',-1);
        $nick_phone = trim($this->get_in_str_val('nick_phone',""));
        $adminid = $this->get_account_id();
        $page_num  = $this->get_in_page_num();
        $ret_list  = \App\Helper\Utils::list_to_page_info( array());
        $ret_list= $this->t_student_info->get_list_for_select($id,$gender, $nick_phone, $page_num,$adminid);
        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return outputjson_success(array('data' => $ret_list ));
    }


    public function set_seller_month_money(){
        $groupid   = $this->get_in_int_val("groupid");
        $month_money   = $this->get_in_int_val("month_money");
        $month   = $this->get_in_str_val("month");
        $ret = $this->t_admin_group_month_time->field_get_list_2($groupid, $month,"groupid");
        if($ret){
            $this->t_admin_group_month_time->field_update_list_2($groupid, $month,[
                "month_money"=>$month_money,
            ]);
        }else{
            $this->t_admin_group_month_time->row_insert([
                "groupid"=>$groupid,
                "month_money"=>$month_money,
                "month"=>$month
            ]);
        }
        return $this->output_succ(array('data' => $month_money ));
    }

    public function set_seller_personal_money(){
        $adminid           = $this->get_in_int_val("adminid");
        $personal_money    = $this->get_in_int_val("personal_money");
        $month             = $this->get_in_str_val("month");
        $test_lesson_count = $this->get_in_int_val("test_lesson_count");
        $ret = $this->t_seller_month_money_target->field_get_list_2($adminid, $month,"adminid");

        if($ret){
            $this->t_seller_month_money_target->field_update_list_2($adminid, $month,[
                "personal_money"=>$personal_money,
                "test_lesson_count"=>$test_lesson_count,
            ]);
        }else{
            $this->t_seller_month_money_target->row_insert([
                "adminid"=>$adminid,
                "personal_money"=>$personal_money,
                "test_lesson_count"=>$test_lesson_count,
                "month"=>$month,
            ]);
        }
        return  $this->output_succ (array(
            'personal_money' => $personal_money,
            'test_lesson_count' => $test_lesson_count,
        ));


    }


    public function update_seller_month_time(){
        $adminid   = $this->get_in_int_val("adminid");
        $groupid   = $this->get_in_int_val("groupid");
        $month   = $this->get_in_str_val("month");
        $month_time   = $this->get_in_str_val("month_time");
        if(empty($adminid)){
            $ret = $this->t_admin_group_month_time->field_get_list_2($groupid, $month,"groupid");
            if($ret){
                $this->t_admin_group_month_time->field_update_list_2($groupid, $month,["month_time"=>$month_time]);
            }else{
                $this->t_admin_group_month_time->row_insert([
                    "groupid"=>$groupid,
                    "month_time"=>$month_time,
                    "month"=>$month
                ]);
            }
            $adminid_list = $this->t_admin_group_user->get_userid_arr($groupid);
            foreach ($adminid_list as $item){
                $res = $this->t_seller_month_money_target->field_get_list_2($item, $month,"adminid");
                if($res){
                    $this->t_seller_month_money_target->field_update_list_2($item, $month,["month_time"=>$month_time]);
                }else{
                    $this->t_seller_month_money_target->row_insert([
                        "adminid"=>$item,
                        "month_time"=>$month_time,
                        "month"=>$month
                    ]);
                }

            }

        }else{
            $ru = $this->t_seller_month_money_target->field_get_list_2($adminid, $month,"adminid");
            if($ru){
                $this->t_seller_month_money_target->field_update_list_2($adminid, $month,["month_time"=>$month_time]);
            }else{
                $this->t_seller_month_money_target->row_insert([
                    "adminid"=>$adminid,
                    "month_time"=>$month_time,
                    "month"=>$month
                ]);
            }

        }

        return $this->output_succ();
    }

    public function update_seller_month_leave_and_overtime(){
        $adminid   = $this->get_in_int_val("adminid");
        $month   = $this->get_in_str_val("month");
        $leave_and_overtime  = $this->get_in_str_val("leave_and_overtime");
        $ru = $this->t_seller_month_money_target->field_get_list_2($adminid, $month,"adminid");
        if($ru){
            $this->t_seller_month_money_target->field_update_list_2($adminid, $month,["leave_and_overtime"=>$leave_and_overtime]);
        }else{
            $this->t_seller_month_money_target->row_insert([
                "adminid"=>$adminid,
                "leave_and_overtime"=>$leave_and_overtime,
                "month"=>$month
            ]);
        }

        return $this->output_succ();
    }
    public function get_adminid_by_account() {
        $account=$this->get_in_str_val("account");
        $adminid=$this->t_manager_info->get_id_by_account($account);
        return $this->output_succ(["adminid" => $adminid]);
    }
    public function get_account_by_adminid() {
        $adminid=$this->get_in_int_val("adminid");
        return $this->output_succ(["account" => $this->t_manager_info->get_account($adminid)  ]);
    }

    public function get_item_list() {
        $item_key  = $this->get_in_str_val("item_key");
        $list_flag = $this->get_in_str_val("list_flag");
        $term      = trim($this->get_in_str_val("term"));
        $list      = \App\Helper\Common::redis_get_json($item_key);
        if(!$list){
            $list=[];
        }
        $match_list=[];
        foreach($list as $item_name ){
            if (count($match_list)>15) {
                break;
            }
            //$term=preg_quote($term);
            if (@preg_match("/$term/i",$item_name)) {
                $match_list[]=$item_name;
            }
        }

        if($list_flag)  {
            return outputJson($match_list);
        }else{
            return $this->output_succ(["list" => $match_list]);
        }
    }

    public function set_item_list_add() {
        $item_key=$this->get_in_str_val("item_key");
        $item_name=$this->get_in_str_val("item_name");

        $item_arr=\App\Helper\Common::redis_get_json($item_key);
        if (!$item_arr) {
            $item_arr=[];
        }
        $new_item_arr=[];
        $new_item_arr[]=$item_name;

        foreach($item_arr as $item_item )  {
            if ($item_item != $item_name) {
                $new_item_arr[]=$item_item;
            }
        }
        if (count($new_item_arr)>200) {
            unset($new_item_arr[200]);
        }
        \App\Helper\Common::redis_set_json($item_key, $new_item_arr);

        return $this->output_succ();
    }
    public function order_set_test_lesson_info()  {
        $orderid=$this->get_in_int_val("orderid");
        $from_test_lesson_id=$this->get_in_int_val("from_test_lesson_id");
        $origin=$this->get_in_str_val("origin");
        $this->t_order_info->field_update_list($orderid,[
            "from_test_lesson_id"   => $from_test_lesson_id,
            "origin"   => $origin
        ]);
        return $this->output_succ();
    }


    public function set_stu_account(){
        $userid    = $this->get_in_int_val("userid");
        $phone     = $this->get_in_int_val("stu_phone");
        $old_phone = $this->get_in_int_val("old_phone");
        if (!$this->check_account_in_arr(["jim","adrian"])) {
            return $this->output_err("没有权限");
        }

        if(strlen($phone)!=11){
            return $this->output_err("请输入正确的手机号码!");
        }

        $phone_list = $this->t_phone_to_user->get_all_phone();
        if(isset($phone_list[$phone])){
            return $this->output_err("该账号已存在!");
        }

        $res = $this->t_phone_to_user->check_is_exist_by_phone_and_userid($userid,$old_phone,1);
        if($res){
            $this->t_phone_to_user->delete_user_account($old_phone,$userid,1);
        }

        $this->t_phone_to_user->add($phone,1,$userid);
        $this->t_student_info->field_update_list($userid,["phone"=>$phone]);
        $ret = $this->t_phone_to_user->check_is_exist_by_phone_and_userid(-1,$old_phone,4);
        if($ret){
            $parentid = $ret["userid"];
            $this->t_phone_to_user->delete_user_account($old_phone,$parentid,4);
            $this->t_phone_to_user->add($phone,4,$parentid);
        }

        $st = $this->t_parent_info->get_parentid_by_phone($old_phone);
        if($st){
            $this->t_parent_info->update_parent_phone($phone,$old_phone);
        }
        $this->t_seller_student_new->field_update_list($userid,[
            "phone" =>  $phone
        ]);

        return $this->output_succ();
    }

    public function stu_set_origin_userid() {
        $userid=$this->get_in_userid();
        $origin_userid= $this->get_in_int_val("origin_userid");
        $this->t_student_info->field_update_list($userid,[
            "origin_userid"  => $origin_userid,
        ]);
        return $this->output_succ();
    }

    public function get_record_server_list_js()  {
        $page_num=$this->get_in_page_num();
        $ip= $this->get_in_str_val("ip");

        $ret_list=$this->t_audio_record_server->get_server_list($page_num,$ip);
        $check_time=time(NULL)-60;

        foreach($ret_list["list"] as &$item) {
            $item["status_class"]= ($item["last_active_time"] <$check_time)?"danger":"";
            \App\Helper\Utils::unixtime2date_for_item($item,"last_active_time");
        }

        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ(["data"=> $ret_list]);
    }


    public function set_user_passwd(){
        $userid = $this->get_in_int_val("userid",0);
        $passwd = $this->get_in_str_val("passwd","");

        \App\Helper\Utils::logger("set_user_passwd,userid is:".$userid." passwd is:".$passwd
                                  ." admin_acc is:".$_SESSION['acc']." time is:".date("Y-m-d H:i:s",time()));

        if($userid==0 || $passwd==""){
            return $this->output_err("更改密码出错！请刷新页面后重新更改!");
        }

        $old_passwd=$this->t_user_info->get_passwd($userid);
        if($old_passwd==md5($passwd)){
            return $this->output_err("密码与原始密码相同，无需更改!");
        }

        $ret = $this->t_user_info->field_update_list($userid,[
            "passwd"=>md5($passwd)
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("密码更改失败，请重试！");
        }
    }


    public function set_ass_month_target(){
        // $adminid         = $this->get_in_int_val("adminid");
        $lesson_target   = $this->get_in_str_val("lesson_target");
        $month           = strtotime($this->get_in_str_val("month"));

        $res = $this->t_ass_group_target->field_get_list($month,"rate_target");
        if($res){
            $this->t_ass_group_target->field_update_list($month,["rate_target"=>$lesson_target]);
        }else{
            $this->t_ass_group_target->row_insert([
                "rate_target"=>$lesson_target,
                "month"=>$month
            ]);
        }
        return $this->output_succ();

    }
    public function set_account() {
        $uid= $this->get_in_int_val("uid");
        $account= trim($this->get_in_str_val("account"));
        if (!$this->check_account_in_arr(["jim","李子璇","jack"])) {
            return $this->output_err("没有权限");
        }
        $db_uid=$this->t_manager_info->get_adminid_by_account($account);
        if ($db_uid) {
            return $this->output_err("已经存在:$account");
        }

        $old_account= $this->t_manager_info->get_account($uid);

        $this->t_manager_info->field_update_list($uid,[
            "account" => $account,
        ]);
        $this->t_admin_users->field_update_list($uid,[
            "account" => $account,
        ]);

        if ($old_account) {
            $this->t_order_info->update_sys_operator_by_change_account( $old_account, $account  );
        }

        $this->cache_del_nick("account",$uid);
        return $this->output_succ();
    }
    public function set_revisit () {
        $userid=$this->get_in_userid();
        $revisit_time=$this->get_in_int_val("revisit_time");
        $revisit_type= $this->get_in_e_revisit_type();
        $revisit_person = $this->get_in_str_val("revisit_person");
        $operator_note= $this->get_in_str_val("operator_note");
        $operation_satisfy_flag =$this->get_in_int_val("operation_satisfy_flag",0);
        $operation_satisfy_type =$this->get_in_int_val("operation_satisfy_type",0);
        $record_tea_class_flag =$this->get_in_int_val("record_tea_class_flag",0);
        $tea_content_satisfy_flag =$this->get_in_int_val("tea_content_satisfy_flag",0);
        $tea_content_satisfy_type=$this->get_in_int_val("tea_content_satisfy_type",0);
        $operation_satisfy_info= trim($this->get_in_str_val("operation_satisfy_info",""));
        $child_performance = trim($this->get_in_str_val("child_performance",""));
        $tea_content_satisfy_info= trim($this->get_in_str_val("tea_content_satisfy_info",""));
        $other_parent_info= trim($this->get_in_str_val("other_parent_info",""));
        $other_warning_info= trim($this->get_in_str_val("other_warning_info",""));
        $child_class_performance_flag =$this->get_in_int_val("child_class_performance_flag",0);
        $child_class_performance_type =$this->get_in_int_val("child_class_performance_type",0);
        $child_class_performance_info = trim($this->get_in_str_val("child_class_performance_info",""));
        $school_score_change_flag  =$this->get_in_int_val("school_score_change_flag",0);
        $school_score_change_info = trim($this->get_in_str_val("school_score_change_info",""));
        $school_work_change_flag  =$this->get_in_int_val("school_work_change_flag",0);
        $school_work_change_type =$this->get_in_int_val("school_work_change_type",0);
        $school_work_change_info = trim($this->get_in_str_val("school_work_change_info",""));
        if($operation_satisfy_flag>1 || $child_class_performance_flag>2 || $school_score_change_flag>1 || $school_work_change_flag==1 || $tea_content_satisfy_flag>2){
            $is_warning_flag=1;
        }else{
            $is_warning_flag=0;
        }

        $ret =  $this->t_revisit_info->field_update_list_2($userid,$revisit_time,[
            "revisit_type" =>$revisit_type,
            "revisit_person" => $revisit_person,
            "operator_note" => $operator_note,
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
        ]);
        if($ret){
            $max_revisit_time = $this->t_revisit_info->get_max_revisit_time($userid);
            $this->t_student_info->field_update_list($userid,[
                "ass_revisit_last_week_time"    =>$max_revisit_time
            ]);
        }

        return $this->output_succ();
    }

    public function set_revisit_warning_deal_info(){
        $userid=$this->get_in_userid();
        $revisit_time=$this->get_in_int_val("revisit_time");
        $is_warning_flag=$this->get_in_int_val("is_warning_flag");
        $url = $this->get_in_str_val("warning_deal_url");
        $warning_deal_info = $this->get_in_str_val("warning_deal_info");

        if(!empty($url)){
            $domain = config('admin')['qiniu']['public']['url'];
            $warning_deal_url = $domain.'/'.$url;

        }else{
            $warning_deal_url="";
        }
        $this->t_revisit_info->field_update_list_2($userid,$revisit_time,[
            "warning_deal_url" =>$warning_deal_url,
            "warning_deal_info" => $warning_deal_info,
            "warning_deal_time" => time(),
            "is_warning_flag" => $is_warning_flag
        ]);
        return $this->output_succ();

    }

    public function get_teacher_limit_change_list(){
        $teacherid = $this->get_in_int_val("teacherid",0);
        $type      = $this->get_in_int_val("type",1);
        if($teacherid==0){
            return $this->output_err("老师id出错!");
        }

        $list = $this->t_teacher_record_list->get_teacher_record_list($teacherid,$type);
        foreach($list as &$val){
            $val['add_time_str']=date("Y-m-d H:i:s",$val['add_time']);
            E\Egrade_range::set_item_value_str($val);
            E\Elimit_plan_lesson_type::set_item_value_str($val);
            E\Elimit_plan_lesson_type::set_item_value_str($val,"limit_plan_lesson_type_old");
            E\Eboolean::set_item_value_str($val,"seller_require_flag");
        }

        if(empty($list)){
            return $this->output_err("该老师没有限课记录!");
        }else{
            return $this->output_succ(["data"=>$list]);
        }
    }

    public function get_teacher_regular_lesson_del_list(){
        $teacherid = $this->get_in_int_val("teacherid",0);
        $type      = $this->get_in_int_val("type",1);
        if($teacherid==0){
            return $this->output_err("老师id出错!");
        }

        $list = $this->t_teacher_record_list->get_teacher_record_list($teacherid,$type);
        foreach($list as &$val){
            $val['add_time_str']=date("Y-m-d H:i:s",$val['add_time']);
        }

        if(empty($list)){
            return $this->output_err("无相关记录!");
        }else{
            return $this->output_succ(["data"=>$list]);
        }
    }


    public function get_teacher_week_lesson_num_change_list(){
        $teacherid = $this->get_in_int_val("teacherid",0);
        $type      = $this->get_in_int_val("type",1);
        if($teacherid==0){
            return $this->output_err("老师id出错!");
        }

        $list = $this->t_teacher_record_list->get_teacher_record_list($teacherid,$type);
        foreach($list as &$val){
            $val['add_time_str']=date("Y-m-d H:i:s",$val['add_time']);
            E\Eboolean::set_item_value_str($val,"seller_require_flag");
        }

        if(empty($list)){
            return $this->output_err("该老师没有限课记录!");
        }else{
            return $this->output_succ(["data"=>$list]);
        }
    }

    public function seller_month_money_info( ) {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $adminid=$this->get_in_adminid();
        $month= date("Ym",$start_time);
        switch ( $month ) {
        case "201702" :
        case "201703" :
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_info_by_type(
                 $month, $adminid, $start_time, $end_time ) ;
            break;
        default:
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_cur_info(
                $adminid, $start_time, $end_time ) ;
            break;
        }

        return $this->output_succ($arr);
    }

    public function get_renw_flag_change_list(){
        $id = $this->get_in_int_val("id",0);
        $data = $this->t_ass_warning_renw_flag_modefiy_list->get_info_by_warning_id($id);
        foreach($data as &$val){
            E\Erenw_type::set_item_value_str($val,"ass_renw_flag_before");
            E\Erenw_type::set_item_value_str($val,"ass_renw_flag_cur");
            $val["account"] = $this->t_manager_info->get_account($val["adminid"]);
            $val['add_time_str']=date("Y-m-d H:i:s",$val['add_time']);
            if(!empty($val["renw_week"])){
                $val["renw_end_day"] = date("Y-m-d",$val["add_time"]+$val["renw_week"]*7*86400);
            }else{
                $val["renw_end_day"]="";
            }

        }
        if(empty($data)){
            return $this->output_err("没有修改记录!");
        }else{
            return $this->output_succ(["data"=>$data]);
        }

    }
    public function get_student_type_change_list(){
        $userid = $this->get_in_int_val("userid",0);
        $data = $this->t_student_type_change_list->get_info_by_userid($userid);
        foreach($data as &$val){
            $val["account"] = $this->t_manager_info->get_account($val["adminid"]);
            if(empty( $val["account"])){
                $val["account"]="system";
            }
            $val["nick"] = $this->t_student_info->get_nick($val["userid"]);
            E\Estudent_type::set_item_value_str($val,"type_before");
            E\Estudent_type::set_item_value_str($val,"type_cur");
            $val["change_type_str"] = $val["change_type"]==1?"系统":"手动";
            $val['add_time_str']=date("Y-m-d H:i:s",$val['add_time']);
            if($val['recover_time']>0){
                $val['recover_time_str']=date("Y-m-d",$val['recover_time']);
            }else{
                $val['recover_time_str']="";
            }
            if($val['wx_remind_time']>0){
                $val['wx_remind_time_str']=date("Y-m-d",$val['wx_remind_time']);
            }else{
                $val['wx_remind_time_str']="";
            }

        }
        if(empty($data)){
            return $this->output_err("该老师没有更改类型记录!");
        }else{
            return $this->output_succ(["data"=>$data]);
        }

    }
    public function get_teacher_free_list(){
        $teacherid = $this->get_in_int_val("teacherid",0);
        $type      = $this->get_in_int_val("type",1);
        if($teacherid==0){
            return $this->output_err("老师id出错!");
        }

        $list = $this->t_teacher_record_list->get_teacher_record_list($teacherid,$type);
        foreach($list as &$val){
            $val['add_time_str']=date("Y-m-d H:i:s",$val['add_time']);
            if($val["is_freeze"]==0 || $val["is_freeze"]==2){
                $val["is_freeze_str"]="解除冻结";
            }else{
                $val["is_freeze_str"]="冻结排课";
            }
            E\Egrade_range::set_item_value_str($val);
            E\Eboolean::set_item_value_str($val,"seller_require_flag");

        }

        if(empty($list)){
            return $this->output_err("该老师没有冻结排课记录!");
        }else{
            return $this->output_succ(["data"=>$list]);
        }

    }

    public function get_jw_tran_info_by_adminid(){
        $adminid = $this->get_in_int_val("adminid",0);
        $all_count = $this->get_in_int_val("all_count",0);
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");

        $data        = $this->t_test_lesson_subject_require->get_teat_lesson_transfor_new($start_time,$end_time,$adminid);

        $data["tra_per"] = $all_count==0?"0":(round($data["tra_count"]/$all_count,4)*100);
        $data["tra_per_str"] = $data["tra_per"]."%";

        return json_encode($data);
    }
    public function  lesson_require_set_confirm_flag_4 () {
        $lessonid= $this->get_in_lessonid();
        $reason = $this->get_in_str_val( "reason");

        $flow_type=E\Eflow_type::V_ASS_LESSON_CONFIRM_FLAG_4 ;
        if (!$this->t_flow->get_info_by_key($flow_type,$lessonid)) {
            $this->t_flow->add_flow(
                E\Eflow_type::V_ASS_LESSON_CONFIRM_FLAG_4 ,
                $this->get_account_id(),$reason, $lessonid );

            return $this->output_succ();
        }else{
            return $this->output_err("已经添加了");
        }
    }
    public function get_flow_info_by_key( ) {
        $from_key_int=$this->get_in_int_val("from_key_int",0);
        $from_key2_int=$this->get_in_int_val("from_key2_int",0);
        $flow_type = $this->get_in_e_flow_type(0);
        $row=$this->t_flow->get_info_by_key($flow_type,$from_key_int,$from_key2_int);

        $flowid= $row["flowid"];

        $ret_info["list"]=[];
        $table_data = [] ;
        if ($flowid) {
            $flow_class = \App\Flow\flow::get_flow_class($flow_type);
            $table_data = $flow_class::get_table_data($flowid);
            \App\Helper\Common::set_item_enum_flow_status($row);

            $table_data[] = ["<font color=red>审核状态 </font>",  $row[ "flow_status_str" ] ] ;

            $ret_info   = $this->t_flow_node->get_node_list($flowid,"asc");

            foreach ($ret_info["list"] as &$item) {
                \App\Helper\Utils::unixtime2date_for_item($item,"check_time");
                $this->cache_set_item_account_nick($item);
                E\Eflow_check_flag::set_item_value_str($item);
                $item["node_name"]=$flow_class::get_node_name($item["node_type"]);
            }
        }

        return $this->output_succ([
            "table_data"=>$table_data,"node_list"=>$ret_info["list"],
            "flowid" => $flowid  ]);
    }

    public function add_seller_require_commend_teacher(){
        $userid=$this->get_in_int_val("userid");
        $subject=$this->get_in_int_val("subject");
        $grade=$this->get_in_int_val("grade");
        $commend_type=$this->get_in_int_val("commend_type");
        $except_teacher =trim($this->get_in_str_val("except_teacher"));
        $textbook =trim($this->get_in_str_val("textbook"));
        $phone_location=trim($this->get_in_str_val("phone_location"));
        $stu_score_info=trim($this->get_in_str_val("stu_score_info"));
        $stu_character_info=trim($this->get_in_str_val("stu_character_info"));
        $stu_request_test_lesson_time=$this->get_in_int_val("stu_request_test_lesson_time");
        $stu_request_test_lesson_demand  =$this->get_in_str_val("stu_request_test_lesson_demand");
        $stu_request_lesson_time_info  =$this->get_in_str_val("stu_request_lesson_time_info");
        if($subject==0 || $grade==0){
            return $this->output_err("请设置年级科目");
        }
        if($stu_request_lesson_time_info){
            $stu_request_lesson_time_info = json_encode($stu_request_lesson_time_info);
        }
        if(empty( $textbook) || empty( $stu_score_info) || empty( $stu_character_info) || empty( $stu_request_test_lesson_time) || empty( $stu_request_test_lesson_demand) ){
            return $this->output_err("请完善试听信息");
        }

        $ret = $this->t_change_teacher_list->check_is_exist(-1,$userid,$subject,$commend_type);
        if($ret>0){
            return outputJson(array('ret' => -1, 'info' =>  "<div>已有处理中的该学生该科目的推荐老师申请！<a href='/tea_manage_new/get_seller_require_commend_teacher_info_seller?id=$ret/' target='_blank'>点击查看申请信息<a/><div>"));

            //return $this->output_err("已有该学生该科目的推荐老师申请!");
        }

        $accept_adminid = $this->get_account_id_by_subject_and_grade_new($subject,$grade);
        $date_week = \App\Helper\Utils::get_week_range(time(),1);
        $lstart    = $date_week["sdate"];
        $lend      = $date_week["edate"];

        $num = $this->t_change_teacher_list->get_require_num(-1,$commend_type,$lstart,$lend);
        if($num>=15){
            return $this->output_err("本月申请次数已达上限".$num."次");
        }
        //  $accept_adminid=349;
        $adminid = $this->get_account_id();
        $res =  $this->t_change_teacher_list->row_insert([
            "userid"     =>$userid,
            "subject"    =>$subject,
            "grade"      =>$grade,
            "commend_type"       =>$commend_type,
            "except_teacher"     =>$except_teacher,
            "textbook"           =>$textbook,
            "phone_location"     =>$phone_location,
            "stu_score_info"     =>$stu_score_info,
            "stu_character_info" =>$stu_character_info,
            "add_time"           =>time(),
            "ass_adminid"        =>$adminid,
            "accept_adminid"     =>$accept_adminid,
            "stu_request_test_lesson_demand"     =>$stu_request_test_lesson_demand ,
            "stu_request_lesson_time_info"       =>$stu_request_lesson_time_info,
            "stu_request_test_lesson_time"       =>$stu_request_test_lesson_time ,
        ]);
        $account = $this->get_account();
        $accept_account = $this->t_manager_info->get_account($accept_adminid);
        $id= $this->t_change_teacher_list->check_is_exist(-1,$userid,$subject,$commend_type);
        $teacherid = $this->t_teacher_info->get_teacherid_by_adminid($accept_adminid);
        $check_lesson_on = $this->t_lesson_info_b2->check_lesson_on($teacherid);
        $num++;
        if($res){
            if($check_lesson_on ==1){
                 return outputJson(array('ret' => 1, 'info' =>  "<div>申请成功,目前对应教研老师正在上课,请稍作等待!本月可申请15次,当前已申请".$num."次.<a href='/tea_manage_new/get_seller_require_commend_teacher_info_seller?id=$id/' target='_blank'>点击查看申请信息<a/><div>"));
            }else{
                $this->t_change_teacher_list->field_update_list($id,["wx_send_time"=>time()]);
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"推荐老师","销售推荐老师申请","销售".$account."老师申请推荐老师,请尽快处理","http://admin.yb1v1.com/tea_manage_new/get_seller_require_commend_teacher_info?id=".$id);
                return outputJson(array('ret' => 1, 'info' =>  "<div>申请成功,本月可申请15次,当前已申请".$num."次<a href='/tea_manage_new/get_seller_require_commend_teacher_info_seller?id=$id/' target='_blank'>点击查看申请信息<a/><div>"));

            }

        }
        return $this->output_succ();

    }

    public function  update_seller_require_commend_teacher(){
        $id=$this->get_in_int_val("id");
        $except_teacher =trim($this->get_in_str_val("except_teacher"));
        $this->t_change_teacher_list->field_update_list($id,[
            "except_teacher"  =>$except_teacher
        ]);
        return $this->output_succ();
    }

    public function set_seller_commend_teacher_info(){
        $id=$this->get_in_int_val("id");
        $record_teacher=$this->get_in_str_val("record_teacher");
        $accept_flag=$this->get_in_int_val("accept_flag");
        $accept_reason=trim($this->get_in_str_val("accept_reason"));
        $adminid = $this->get_account_id();
        $res = $this->t_change_teacher_list->field_update_list($id,[
            "accept_reason"  =>$accept_reason,
            "record_teacher" =>$record_teacher,
            "accept_adminid" =>$adminid,
            "accept_time"    =>time(),
            "accept_flag"    =>$accept_flag
        ]);
        $ass_adminid = $this->t_change_teacher_list->get_ass_adminid($id);
        $userid =  $this->t_change_teacher_list->get_userid($id);
        $nick = $this->t_student_info->get_nick($userid);
        if($res){
            if($accept_flag==1){
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($ass_adminid,"理优教育","推荐老师申请反馈","您为学生".$nick."的推荐老师申请已处理完成,点击查看详情","http://admin.yb1v1.com/tea_manage_new/get_seller_require_commend_teacher_info_seller?id=".$id);
            }else{
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($ass_adminid,"理优教育","推荐老师申请反馈","您为学生".$nick."的推荐老师申请已被驳回,理由如下:
".$accept_reason.",点击查看详情","http://admin.yb1v1.com/tea_manage_new/get_seller_require_commend_teacher_info_seller?id=".$id);
            }

        }

        return $this->output_succ();

    }
    public function change_teacher_require_deal(){
        $teacherid=$this->get_in_int_val("teacherid");
        $userid=$this->get_in_int_val("userid");
        $subject=$this->get_in_int_val("subject");
        $grade=$this->get_in_int_val("grade");
        $commend_type=$this->get_in_int_val("commend_type");
        $change_teacher_reason_type=$this->get_in_int_val("change_teacher_reason_type");
        $change_reason=trim($this->get_in_str_val("change_reason"));
        $url=$this->get_in_str_val("change_reason_url");
        $domain = config('admin')['qiniu']['public']['url'];
        $change_reason_url = $domain.'/'.$url;
        $except_teacher =trim($this->get_in_str_val("except_teacher"));
        $textbook =trim($this->get_in_str_val("textbook"));
        $phone_location=trim($this->get_in_str_val("phone_location"));
        $stu_score_info=trim($this->get_in_str_val("stu_score_info"));
        $stu_character_info=trim($this->get_in_str_val("stu_character_info"));
        if(empty( $url) || empty( $change_reason) || empty( $except_teacher) || empty( $textbook) || empty( $phone_location) || empty($stu_score_info ) || empty($stu_character_info)){
             return $this->output_err("请填写完整!");
        }
        $adminid = $this->get_account_id();
        $ret = $this->t_change_teacher_list->check_is_exist($teacherid,$userid,$subject,$commend_type);
        if($ret>0){
            return $this->output_err("已有该学生该老师的换老师申请!");
        }
        if($subject==2){
            $accept_adminid = 754;
        }elseif($subject==5){
             $accept_adminid = 793;
        }elseif($subject==4){
            $accept_adminid=770;
        }else{
            $accept_adminid = 486;
        }
        $res =  $this->t_change_teacher_list->row_insert([
            "teacherid"  =>$teacherid,
            "userid"     =>$userid,
            "subject"    =>$subject,
            "grade"      =>$grade,
            "commend_type"       =>$commend_type,
            "change_reason"      =>$change_reason,
            "change_reason_url"  =>$change_reason_url,
            "except_teacher"     =>$except_teacher,
            "textbook"           =>$textbook,
            "phone_location"     =>$phone_location,
            "stu_score_info"     =>$stu_score_info,
            "stu_character_info" =>$stu_character_info,
            "change_teacher_reason_type"=>$change_teacher_reason_type,
            "add_time"           =>time(),
            "ass_adminid"        =>$adminid,
            "accept_adminid"     =>$accept_adminid
        ]);
        $account = $this->get_account();
        $accept_account = $this->t_manager_info->get_account($accept_adminid);
        $id= $this->t_change_teacher_list->check_is_exist($teacherid,$userid,$subject,$commend_type);
        if($res){
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","更换老师申请","助教".$account."老师申请更换老师,请尽快协调处理","http://admin.yb1v1.com/user_manage_new/get_ass_change_teacher_info?id=".$id);
            $realname = $this->t_teacher_info->get_realname($teacherid);
            $record_info = "当前老师:".$realname."<br>换老师原因:".$change_reason."<br>期望老师:".$except_teacher;
            $this->t_revisit_info->row_insert([
                "userid"  =>$userid,
                "revisit_time" =>time(),
                "sys_operator" =>$this->get_account(),
                "operator_note" =>$record_info,
                "revisit_type" =>3
            ]);


        }
        return $this->output_succ();
    }

    public function set_change_teacher_record_info(){
        $id=$this->get_in_int_val("id");
        $commend_teacherid=$this->get_in_int_val("commend_teacherid");
        $accept_flag=$this->get_in_int_val("accept_flag");
        $accept_reason=trim($this->get_in_str_val("accept_reason"));
        $adminid = $this->get_account_id();
        $res = $this->t_change_teacher_list->field_update_list($id,[
            "accept_reason"  =>$accept_reason,
            "commend_teacherid" =>$commend_teacherid,
            "accept_adminid" =>$adminid,
            "accept_time"    =>time(),
            "accept_flag"    =>$accept_flag
        ]);
        $ass_adminid = $this->t_change_teacher_list->get_ass_adminid($id);
        $teacherid =  $this->t_change_teacher_list->get_teacherid($id);
        $realname = $this->t_teacher_info->get_realname($teacherid);
        if($res){
            if($accept_flag==1){
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($ass_adminid,"理优教育","更换老师申请反馈","您更换".$realname."老师的申请已处理完成,点击查看详情","http://admin.yb1v1.com/user_manage_new/get_ass_change_teacher_info_ass?id=".$id);
            }else{
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($ass_adminid,"理优教育","更换老师申请反馈","您更换".$realname."老师的申请已被驳回,理由如下:
".$accept_reason.",点击查看详情","http://admin.yb1v1.com/user_manage_new/get_ass_change_teacher_info_ass?id=".$id);
            }

        }

        return $this->output_succ();
    }

    public function update_teacher_require_deal(){
        $id=$this->get_in_int_val("id");
        $change_reason=trim($this->get_in_str_val("change_reason"));
        $except_teacher =trim($this->get_in_str_val("except_teacher"));
        $textbook =trim($this->get_in_str_val("textbook"));
        $phone_location=trim($this->get_in_str_val("phone_location"));
        $stu_score_info=trim($this->get_in_str_val("stu_score_info"));
        $stu_character_info=trim($this->get_in_str_val("stu_character_info"));
        $url=$this->get_in_str_val("change_reason_url");
        $domain = config('admin')['qiniu']['public']['url'];
        $change_reason_url = $domain.'/'.$url;
        if(empty( $url) || empty( $change_reason) || empty( $except_teacher) || empty( $textbook) || empty( $phone_location) || empty($stu_score_info ) || empty($stu_character_info)){
            return $this->output_err("请填写完整!");
        }

        $this->t_change_teacher_list->field_update_list($id,[
            "change_reason"  =>$change_reason,
            "except_teacher" =>$except_teacher,
            "textbook"       =>$textbook,
            "phone_location" =>$phone_location,
            "stu_score_info" =>$stu_score_info,
            "stu_character_info"=>$stu_character_info,
            "change_reason_url" =>$change_reason_url
        ]);
        return $this->output_succ();


    }

    public function del_fulltime_teacher_attendance_info(){
        $id=$this->get_in_int_val("id");
        $this->t_fulltime_teacher_attendance_list->row_delete($id);
        return $this->output_succ();
    }

    public function del_teacher_require_deal(){
        $id=$this->get_in_int_val("id");
        $this->t_change_teacher_list->row_delete($id);
        return $this->output_succ();
    }

    public function del_test_lesson_require_teacher_info(){
        $id=$this->get_in_int_val("id");
        $this->t_test_lesson_require_teacher_list->row_delete($id);
        return $this->output_succ();

    }
    public function set_reload_power_time() {
        \App\Helper\Common::redis_set("POWER_CHANGE_TIME",time(NULL));
        return $this->output_succ();
    }

    public function add_seller_ass_record_info(){
        $teacherid=$this->get_in_int_val("teacherid");
        $userid=$this->get_in_int_val("userid");
        $lessonid=$this->get_in_int_val("lessonid");
        $subject=$this->get_in_int_val("subject");
        $grade=$this->get_in_int_val("grade");
        $type=$this->get_in_int_val("type");
        $is_change_teacher =$this->get_in_int_val("is_change_teacher");
        $tea_time =$this->get_in_int_val("tea_time");
        $record_info=trim($this->get_in_str_val("record_info"));
        $url=$this->get_in_str_val("record_info_url");
        $domain = config('admin')['qiniu']['public']['url'];
        $record_info_url = $domain.'/'.$url;
        $textbook =trim($this->get_in_str_val("textbook"));
        $stu_request_test_lesson_demand =trim($this->get_in_str_val("stu_request_test_lesson_demand"));
        $stu_score_info=trim($this->get_in_str_val("stu_score_info"));
        $stu_character_info=trim($this->get_in_str_val("stu_character_info"));
        if(empty( $record_info) || empty( $url) ||  empty( $textbook)  || !(!empty( $stu_request_test_lesson_demand) || !(empty($is_change_teacher) || empty($tea_time)  || empty($stu_score_info ) || empty($stu_character_info) ))){
            return $this->output_err("请填写完整!");
        }
        // return;
        $adminid = $this->get_account_id();
        $ret = $this->t_seller_and_ass_record_list->check_is_exist($lessonid);
        if($ret >0){
            return $this->output_err("已有该课程的教学质量反馈");
        }
        $accept_adminid = $this->get_account_id_by_subject_and_grade_new($subject,$grade);
        $res =  $this->t_seller_and_ass_record_list->row_insert([
            "teacherid"  =>$teacherid,
            "userid"     =>$userid,
            "lessonid"     =>$lessonid,
            "subject"    =>$subject,
            "grade"      =>$grade,
            "record_info"  =>$record_info,
            "record_info_url"  =>$record_info_url,
            "stu_request_test_lesson_demand" =>$stu_request_test_lesson_demand,
            "textbook"       =>$textbook,
            "is_change_teacher" =>$is_change_teacher,
            "tea_time" =>$tea_time,
            "stu_score_info" =>$stu_score_info,
            "stu_character_info"=>$stu_character_info,
            "add_time"          =>time(),
            "adminid"       =>$adminid,
            "type"         =>$type,
            "accept_adminid"=>$accept_adminid
        ]);
        $account = $this->get_account();
        $id= $this->t_seller_and_ass_record_list->check_is_exist($lessonid);
        $accept_account = $this->t_manager_info->get_account($accept_adminid);
        if($res){
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","教学质量反馈待处理",$account."老师提交了一条教学质量反馈,请尽快处理","http://admin.yb1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);

        }
        return $this->output_succ(["account"=>$accept_account]);

    }

    public function add_seller_ass_record_info_new(){
        $lessonid=$this->get_in_int_val("lessonid");
        $type=$this->get_in_int_val("type");
        $adminid = $this->get_in_int_val("adminid");
        $accept_adminid = $this->get_in_int_val("accept_adminid");
        $add_time = strtotime($this->get_in_str_val("add_time"));
        $accept_time = strtotime($this->get_in_str_val("accept_time"));
        $done_time = strtotime($this->get_in_str_val("done_time"));
        $record_info=trim($this->get_in_str_val("record_info"));
        $record_scheme=trim($this->get_in_str_val("record_scheme"));
        $ret = $this->t_seller_and_ass_record_list->check_is_exist($lessonid);
        if($ret >0 && !empty($lessonid)){
            return $this->output_err("已有该课程的教学质量反馈");
        }
        $lesson_info = $this->t_lesson_info->field_get_list($lessonid,"subject,grade,userid,teacherid");
        $res =  $this->t_seller_and_ass_record_list->row_insert([
            "lessonid"     =>$lessonid,
            "add_time"     =>$add_time,
            "adminid"      =>$adminid,
            "type"         =>$type,
            "accept_adminid"=>$accept_adminid,
            "accept_time"=>$accept_time,
            "done_time"     =>$done_time,
            "is_done_flag"  =>1,
            "userid"        =>$lesson_info["userid"],
            "teacherid"     =>$lesson_info["teacherid"],
            "subject"        =>$lesson_info["subject"],
            "grade"        =>$lesson_info["grade"],
            "record_info"  =>$record_info,
            "record_scheme"  =>$record_scheme,
            "add_type"      =>1
        ]);
        return $this->output_succ();

    }

    public function update_seller_ass_record_info_new(){
        $id=$this->get_in_int_val("id");
        $type=$this->get_in_int_val("type");
        $adminid = $this->get_in_int_val("adminid");
        $accept_adminid = $this->get_in_int_val("accept_adminid");
        $add_time = strtotime($this->get_in_str_val("add_time"));
        $accept_time = strtotime($this->get_in_str_val("accept_time"));
        $done_time = strtotime($this->get_in_str_val("done_time"));
        $record_info=trim($this->get_in_str_val("record_info"));
        $record_scheme=trim($this->get_in_str_val("record_scheme"));
        $res =  $this->t_seller_and_ass_record_list->field_update_list($id,[
            "add_time"     =>$add_time,
            "adminid"      =>$adminid,
            "type"         =>$type,
            "accept_adminid"=>$accept_adminid,
            "accept_time"=>$accept_time,
            "done_time"     =>$done_time,
            "record_info"  =>$record_info,
            "record_scheme"  =>$record_scheme,
        ]);
        return $this->output_succ();

    }



    public function set_seller_ass_record_scheme(){
        $id=$this->get_in_int_val("id");
        $record_scheme=trim($this->get_in_str_val("record_scheme"));
        $url=$this->get_in_str_val("record_scheme_url");
        if($url){
            $domain = config('admin')['qiniu']['public']['url'];
            $record_scheme_url = $domain.'/'.$url;
        }else{
            $record_scheme_url="";
        }
        if(empty($record_scheme)){
             return $this->output_err("处理方案不能为空!");
        }
        $adminid = $this->get_account_id();
        $ret =  $this->t_seller_and_ass_record_list->field_update_list($id,[
            "record_scheme"    =>$record_scheme,
            "record_scheme_url"=>$record_scheme_url,
            "accept_adminid" =>$adminid,
            "accept_time"    =>time()
        ]);
        $require_adminid = $this->t_seller_and_ass_record_list->get_adminid($id);
        $require_account = $this->t_manager_info->get_account($require_adminid);
        $account_role = $this->t_manager_info->get_account_role($require_adminid);
        $account = $this->get_account();

        if($ret){
            if($account_role==2 || $require_adminid==349){
                 $this->t_manager_info->send_wx_todo_msg_by_adminid ($require_adminid,"理优教育","教学质量反馈结果","您提交的教学质量反馈已由".$account."老师处理完毕,请确认是否解决!","http://admin.yb1v1.com/tea_manage_new/get_seller_ass_record_info_seller?id=".$id);

            }elseif($account_role==1){
                 $this->t_manager_info->send_wx_todo_msg_by_adminid ($require_adminid,"理优教育","教学质量反馈结果","您提交的教学质量反馈已由".$account."老师处理完毕,请确认是否解决!","http://admin.yb1v1.com/tea_manage_new/get_seller_ass_record_info_ass?id=".$id);

            }
            $leader_adminid = $this->t_admin_group_user->get_main_master_adminid($adminid);
            if($leader_adminid != $adminid){
                 $this->t_manager_info->send_wx_todo_msg_by_adminid ($leader_adminid,"理优教育","教学质量反馈结果",$require_account."提交的教学质量反馈已由".$account."老师处理完毕,请确认是否解决!","http://admin.yb1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
            }

        }
        return $this->output_succ();
    }

    public function set_teacher_complaints_record_scheme(){
        $id=$this->get_in_int_val("id");
        $record_scheme=trim($this->get_in_str_val("record_scheme"));
        $url=$this->get_in_str_val("record_scheme_url");
        if($url){
            $domain = config('admin')['qiniu']['public']['url'];
            $record_scheme_url = $domain.'/'.$url;
        }else{
            $record_scheme_url="";
        }
        if(empty($record_scheme)){
            return $this->output_err("处理方案不能为空!");
        }
        $adminid = $this->get_account_id();
        $ret =  $this->t_teacher_complaints_info->field_update_list($id,[
            "record_scheme"    =>$record_scheme,
            "record_scheme_url"=>$record_scheme_url,
            "accept_adminid" =>$adminid,
            "accept_time"    =>time()
        ]);

        $require_adminid = $this->t_teacher_complaints_info->get_adminid($id);
        $require_account = $this->t_manager_info->get_account($require_adminid);
        $account = $this->get_account();
        if($ret){
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($require_adminid,"理优教育","投诉老师结果反馈","您提交的老师投诉已由".$account."老师处理完毕,点击查看","http://admin.yb1v1.com/tea_manage_new/get_teacher_complaints_info_jw?id=".$id);
        }

        return $this->output_succ();

    }

    public function update_complaints_teacher_info(){
        $id=$this->get_in_int_val("id");
        $url = $this->get_in_str_val("complaints_info_url");
        $complaints_info  = $this->get_in_str_val("complaints_info");
        if(empty($complaints_info)){
            return $this->output_err("投诉内容不能为空!");
        }

        if(!empty($url)){
            $domain = config('admin')['qiniu']['public']['url'];
            $complaints_info_url = $domain.'/'.$url;
        }else{
            $complaints_info_url="";
        }
        $ret =  $this->t_teacher_complaints_info->field_update_list($id,[
            "complaints_info"    =>$complaints_info,
            "complaints_info_url"=>$complaints_info_url
        ]);

        return $this->output_succ();
    }

    public function update_seller_ass_record_info(){
        $id=$this->get_in_int_val("id");
        $is_change_teacher =$this->get_in_int_val("is_change_teacher");
        $tea_time =$this->get_in_int_val("tea_time");
        $record_info=trim($this->get_in_str_val("record_info"));
        $url=$this->get_in_str_val("record_info_url");
        $domain = config('admin')['qiniu']['public']['url'];
        $record_info_url = $domain.'/'.$url;
        $textbook =trim($this->get_in_str_val("textbook"));
        $stu_request_test_lesson_demand =trim($this->get_in_str_val("stu_request_test_lesson_demand"));
        $stu_score_info=trim($this->get_in_str_val("stu_score_info"));
        $stu_character_info=trim($this->get_in_str_val("stu_character_info"));
        if(empty( $record_info) || empty( $url) ||  empty( $textbook)  || !(!empty( $stu_request_test_lesson_demand) || !(empty($is_change_teacher) || empty($tea_time)  || empty($stu_score_info ) || empty($stu_character_info) ))){
            return $this->output_err("请填写完整!");
        }
        $this->t_seller_and_ass_record_list->field_update_list($id,[
            "record_info"  =>$record_info,
            "record_info_url"  =>$record_info_url,
            "stu_request_test_lesson_demand" =>$stu_request_test_lesson_demand,
            "textbook"       =>$textbook,
            "is_change_teacher" =>$is_change_teacher,
            "tea_time" =>$tea_time,
            "stu_score_info" =>$stu_score_info,
            "stu_character_info"=>$stu_character_info,
        ]);
        return $this->output_succ();

    }

    public function del_seller_and_ass_require(){
        $id=$this->get_in_int_val("id");
        $this->t_seller_and_ass_record_list->row_delete($id);
        return $this->output_succ();
    }

    public function del_complaints_teacher_info(){
        $id=$this->get_in_int_val("id");
        $this->t_teacher_complaints_info->row_delete($id);
        return $this->output_succ();
    }


    public function done_change_teacher_require(){
        $id=$this->get_in_int_val("id");
        $is_done_flag =$this->get_in_int_val("is_done_flag");
        $is_resubmit_flag =$this->get_in_int_val("is_resubmit_flag");
        $teacherid=$this->get_in_int_val("teacherid");
        $userid=$this->get_in_int_val("userid");
        $subject=$this->get_in_int_val("subject");
        $grade=$this->get_in_int_val("grade");
        $commend_type=$this->get_in_int_val("commend_type");
        $change_teacher_reason_type=$this->get_in_int_val("change_teacher_reason_type");
        $change_reason=trim($this->get_in_str_val("change_reason"));
        $url=$this->get_in_str_val("change_reason_url");
        $domain = config('admin')['qiniu']['public']['url'];
        $change_reason_url = $domain.'/'.$url;
        $except_teacher =trim($this->get_in_str_val("except_teacher"));
        $textbook =trim($this->get_in_str_val("textbook"));
        $phone_location=trim($this->get_in_str_val("phone_location"));
        $stu_score_info=trim($this->get_in_str_val("stu_score_info"));
        $stu_character_info=trim($this->get_in_str_val("stu_character_info"));
        $account = $this->get_account();
        $adminid = $this->get_account_id();
        $accept_adminid = $this->t_seller_and_ass_record_list->get_accept_adminid($id);
        $accept_account = $this->t_manager_info->get_account($accept_adminid);
        $ret=  $this->t_change_teacher_list->field_update_list($id,[
            "is_done_flag"  =>$is_done_flag,
            "is_resubmit_flag"  =>$is_resubmit_flag,
            "done_time"        =>time()
        ]);

        if($ret && $is_resubmit_flag>0){
            if(empty( $url) || empty( $change_reason) || empty( $except_teacher) || empty( $textbook) || empty( $phone_location) || empty($stu_score_info ) || empty($stu_character_info)){
                return $this->output_err("请填写完整!");
            }
            $res =  $this->t_change_teacher_list->row_insert([
                "teacherid"  =>$teacherid,
                "userid"     =>$userid,
                "subject"    =>$subject,
                "grade"      =>$grade,
                "commend_type"       =>$commend_type,
                "change_reason"      =>$change_reason,
                "change_reason_url"  =>$change_reason_url,
                "except_teacher"     =>$except_teacher,
                "textbook"           =>$textbook,
                "phone_location"     =>$phone_location,
                "stu_score_info"     =>$stu_score_info,
                "stu_character_info" =>$stu_character_info,
                "change_teacher_reason_type"=>$change_teacher_reason_type,
                "add_time"           =>time(),
                "ass_adminid"        =>$adminid,
                "accept_adminid"     =>$accept_adminid
            ]);
            $id= $this->t_change_teacher_list->check_is_exist($teacherid,$userid,$subject,$commend_type);
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","更换老师申请(再次提交)","助教".$account."老师申请更换老师,请尽快协调处理","http://admin.yb1v1.com/user_manage_new/get_ass_change_teacher_info?id=".$id);

        }

        return $this->output_succ();




    }

    public function done_seller_and_ass_require(){
        $id=$this->get_in_int_val("id");
        $is_done_flag =$this->get_in_int_val("is_done_flag");
        $is_resubmit_flag =$this->get_in_int_val("is_resubmit_flag");
        $teacherid=$this->get_in_int_val("teacherid");
        $userid=$this->get_in_int_val("userid");
        $lessonid=$this->get_in_int_val("lessonid");
        $subject=$this->get_in_int_val("subject");
        $grade=$this->get_in_int_val("grade");
        $type=$this->get_in_int_val("type");
        $is_change_teacher =$this->get_in_int_val("is_change_teacher");
        $tea_time =$this->get_in_int_val("tea_time");
        $record_info=trim($this->get_in_str_val("record_info"));
        $url=$this->get_in_str_val("record_info_url");
        $domain = config('admin')['qiniu']['public']['url'];
        $record_info_url = $domain.'/'.$url;
        $textbook =trim($this->get_in_str_val("textbook"));
        $stu_request_test_lesson_demand =trim($this->get_in_str_val("stu_request_test_lesson_demand"));
        $stu_score_info=trim($this->get_in_str_val("stu_score_info"));
        $stu_character_info=trim($this->get_in_str_val("stu_character_info"));

        $account = $this->get_account();
        $adminid = $this->get_account_id();
        $ret=  $this->t_seller_and_ass_record_list->field_update_list($id,[
            "is_done_flag"  =>$is_done_flag,
            "is_resubmit_flag"  =>$is_resubmit_flag,
            "done_time"        =>time()
        ]);
        $accept_adminid = $this->t_seller_and_ass_record_list->get_accept_adminid($id);
        $accept_account = $this->t_manager_info->get_account($accept_adminid);
        $leader_adminid = $this->get_account_leader_adminid($accept_adminid);
        if($is_resubmit_flag==0){
            if($ret){
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","教学质量反馈处理完成",$account."老师提交的教学质量反馈已处理完成,辛苦了","http://admin.yb1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
                if($leader_adminid != 1){
                    $this->t_manager_info->send_wx_todo_msg_by_adminid ($leader_adminid,"理优教育","教学质量反馈处理完成",$account."老师提交的教学质量反馈已处理完成,辛苦了","http://admin.yb1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
                }

            }
            return $this->output_succ();
        }else{
            if(empty( $record_info) || empty( $url) ||  empty( $textbook)  || !(!empty( $stu_request_test_lesson_demand) || !(empty($is_change_teacher) || empty($tea_time)  || empty($stu_score_info ) || empty($stu_character_info) ))){
                return $this->output_err("请填写完整!");
            }

            $res =  $this->t_seller_and_ass_record_list->row_insert([
                "teacherid"  =>$teacherid,
                "userid"     =>$userid,
                "lessonid"     =>$lessonid,
                "subject"    =>$subject,
                "grade"      =>$grade,
                "record_info"  =>$record_info,
                "record_info_url"  =>$record_info_url,
                "stu_request_test_lesson_demand" =>$stu_request_test_lesson_demand,
                "textbook"       =>$textbook,
                "is_change_teacher" =>$is_change_teacher,
                "tea_time" =>$tea_time,
                "stu_score_info" =>$stu_score_info,
                "stu_character_info"=>$stu_character_info,
                "add_time"          =>time(),
                "adminid"       =>$adminid,
                "accept_adminid"=>$accept_adminid,
                "type"         =>$type
            ]);
            $account = $this->get_account();
            $id= $this->t_seller_and_ass_record_list->check_is_exist($lessonid);
            if($res){
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","教学质量反馈(再次提交)",$account."老师重新提交了一条教学质量反馈,请尽快处理","http://admin.yb1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
                if($leader_adminid != 1){
                    $this->t_manager_info->send_wx_todo_msg_by_adminid ($leader_adminid,"理优教育","教学质量反馈(再次提交)",$account."老师重新提交了一条教学质量反馈,将由".$accept_account."老师处理","http://admin.yb1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
                }
            }

            return $this->output_succ();

        }


    }
    public function set_kaoqin_machine () {
        $machine_id=$this->get_in_int_val("machine_id");
        $open_door_flag=$this->get_in_int_val("open_door_flag");
        $title=$this->get_in_str_val("title");
        $desc=$this->get_in_str_val("desc");
        $this->t_kaoqin_machine->field_update_list($machine_id,[
            "open_door_flag" => $open_door_flag,
            "title" => $title,
            "`desc`" => $desc,
        ]);
        return $this->output_succ();
    }
    public function add_kaoqin_machine_adminid () {
        $machine_id=$this->get_in_int_val("machine_id");
        $adminid=$this->get_in_adminid();
        $this->t_kaoqin_machine_adminid->row_insert([
            "machine_id" => $machine_id,
            "adminid" => $adminid,
        ]);
        return $this->output_succ();
    }

    public function set_kaoqin_machine_adminid () {
        $machine_id=$this->get_in_int_val("machine_id");
        $adminid=$this->get_in_adminid();
        $auth_flag=$this->get_in_int_val("auth_flag");
        $this->t_kaoqin_machine_adminid->field_update_list_2($machine_id,$adminid, [
            "auth_flag" => $auth_flag,
        ]);
        return $this->output_succ();
    }

    public function del_kaoqin_machine_adminid () {
        $machine_id=$this->get_in_int_val("machine_id");
        $adminid=$this->get_in_adminid();
        $this->t_kaoqin_machine_adminid->row_delete_2($machine_id,$adminid);
        return $this->output_succ();
    }


    public function get_kaoqin_machine_list()  {
        $adminid=$this->get_in_adminid();
        $page_info=$this->get_in_page_info();
        $ret_list=$this->t_kaoqin_machine_adminid->get_list_by_adminid( $page_info, $adminid  );
        foreach ($ret_list["list"] as &$item) {

        }
        $ret_list["page_info"]  = $this->get_page_info_for_js( $ret_list["page_info"]   );
        return $this->output_succ(array('data' => $ret_list ));
    }

    public function sync_kaoqin() {
        $adminid=$this->get_in_adminid();
        $machine_id_list = \App\Helper\Utils::json_decode_as_int_array($this->get_in_str_val("machine_id_list"));
        $machine_map=[];
        foreach( $machine_id_list as $machine_id) {
            $machine_map[$machine_id]=true;
        }
        $ret_list=$this->t_kaoqin_machine_adminid->get_list_by_adminid( null, $adminid  );
        foreach ($ret_list["list"] as $item)  {
            $machine_id= $item["machine_id"];
            if(@$machine_map[$machine_id]) {
                if (!$item["adminid"]) {
                    $this->t_kaoqin_machine_adminid->row_insert([
                        "machine_id"=> $machine_id,
                        "adminid" => $adminid,
                    ]);
                }
            }else{
                if ($item["adminid"]) {
                    $this->t_kaoqin_machine_adminid->row_delete_2($machine_id,$adminid);
                }
            }
        }

        $this->t_manager_info->sync_kaoqin_user($adminid);
        return $this->output_succ();
    }

    public function copy_admin_group_info(){
        $month = strtotime($this->get_in_str_val("start_time"));
        $this->t_group_name_month->del_by_month($month);
        $this->t_group_user_month->del_by_month($month);
        $this->t_main_group_name_month->del_by_month($month);
        $admin_group_name_list = $this->t_admin_group_name->get_all_list();
        foreach($admin_group_name_list as $item){
            $this->t_group_name_month->row_insert([
                "groupid"    =>$item["groupid"],
                "month"      =>$month,
                "main_type"  =>$item["main_type"],
                "group_name" =>$item["group_name"],
                "master_adminid"=>$item["master_adminid"],
                "up_groupid"   =>$item["up_groupid"],
                "group_assign_percent" =>$item["group_assign_percent"]
            ]);
        }
        $admin_group_user_list = $this->t_admin_group_user->get_all_list();
        foreach($admin_group_user_list as $item){
            $this->t_group_user_month->row_insert([
                "groupid"    =>$item["groupid"],
                "month"      =>$month,
                "adminid"  =>$item["adminid"],
                "assign_percent" =>$item["assign_percent"]
            ]);
        }

        $admin_main_group_name_list = $this->t_admin_main_group_name->get_all_list();
        foreach($admin_main_group_name_list as $item){
            $this->t_main_group_name_month->row_insert([
                "groupid"    =>$item["groupid"],
                "month"      =>$month,
                "main_type"  =>$item["main_type"],
                "group_name" =>$item["group_name"],
                "master_adminid"=>$item["master_adminid"],
                "main_assign_percent" =>$item["main_assign_percent"]
            ]);
        }
        return $this->output_succ();

    }

    public function check_lesson_status($from_key_int){
        $lesson_end_time = $this->t_lesson_info->get_lesson_end($from_key_int);
        if (strtotime(date('Y-m-d',time(NULL)))<$lesson_end_time) {
            return $this->output_err( "只能申请今天之前的课程!" );
        }

        $ret_video_arr = $this->t_lesson_info_b2->get_lesson_url($from_key_int);

        $ret_video = $ret_video_arr['0'];
        if(!empty($ret_video['draw'])){
            $item['draw_url']  =  \App\Helper\Utils::gen_download_url($ret_video['draw']);
            $savePathFile = public_path('wximg').'/'.$ret_video['draw'];

            \App\Helper\Utils::savePicToServer($item['draw_url'],$savePathFile);

            $xml = file_get_contents($savePathFile);

            $xmlstring = simplexml_load_string($xml);

            $svgLists = json_decode(json_encode($xmlstring),true);

            $stroke_time = 0;

            if (!empty($svgLists['svg'])) {
                foreach($svgLists['svg'] as $svg){
                    if (array_key_exists('path',$svg)) {
                        $stroke_time = $svg['@attributes']['timestamp'];
                    }
                }

                if ($ret_video['real_begin_time']<($stroke_time-30*60)) {
                    $this->t_lesson_info_b2->field_update_list($from_key_int,[
                        "lesson_user_online_status" =>  1
                    ]);
                }
                unlink($savePathFile);
            }
        }

        $is_lesson_user_online_status = $this->t_lesson_info_b2->get_online_status_by_lessonid($from_key_int);

        if($is_lesson_user_online_status == 1 ){
            return $this->output_succ();
        }
    }

    public function  flow_add_flow() {
        $from_key_int    = $this->get_in_int_val("from_key_int",0);
        $from_key2_int   = $this->get_in_int_val("from_key2_int",0);
        $flow_type       = $this->get_in_e_flow_type(0);
        $reason          = $this->get_in_str_val("reason");

        if($flow_type == 2003){
            // 处理 seller课时有效性
            $this->check_lesson_status($from_key_int);
        }

        $ret=$this->t_flow->add_flow(
            $flow_type,$this->get_account_id(),$reason,$from_key_int,NULL,$from_key2_int
        );
        if($ret) {
            return $this->output_succ();
        }else{
            return $this->output_err( "已经申请过了" );
        }


    }

    public function add_complaints_teacher_info(){
        $teacherid=$this->get_in_int_val("teacherid");
        $subject=$this->get_in_int_val("subject");
        $url = $this->get_in_str_val("complaints_info_url");
        $complaints_info  = $this->get_in_str_val("complaints_info");
        $adminid = $this->get_account_id();
        $rr= $this->t_teacher_complaints_info->check_is_exist($teacherid,$adminid);
        if($rr>0){
            return $this->output_err( "您有对该老师的投诉正在处理中" );
        }

        if(!empty($url)){
            $domain = config('admin')['qiniu']['public']['url'];
            $complaints_info_url = $domain.'/'.$url;
        }else{
            $complaints_info_url="";
        }
        if($subject==1){
            $accept_adminid =379;
        }elseif($subject==2){
            $accept_adminid =310;
        }elseif($subject==3){
            $accept_adminid =329;
        }elseif($subject==5){
            $accept_adminid =793;
        }else{
            $accept_adminid = 478;
        }


        $res = $this->t_teacher_complaints_info->row_insert(
            [
                "complaints_info_url" =>$complaints_info_url,
                "complaints_info" => $complaints_info,
                "add_time" => time(),
                "adminid" => $adminid,
                "teacherid" =>$teacherid,
                "subject"   =>$subject,
                "accept_adminid" => $accept_adminid
            ]
        );

        $account = $this->get_account();
        $id= $this->t_teacher_complaints_info->check_is_exist($teacherid,$adminid);
        $accept_account = $this->t_manager_info->get_account($accept_adminid);
        if($res){
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","老师投诉待处理",$account."老师提交了一条老师投诉,请尽快处理","http://admin.yb1v1.com/tea_manage_new/get_teacher_complaints_info?id=".$id);

        }

        return $this->output_succ();

    }

    public function get_admin_group_subject(){
        $groupid = $this->get_in_int_val("groupid");
        $subject = $this->t_admin_group_name->get_subject($groupid);
        return $this->output_succ(["subject"=>$subject]);
    }

    public function set_admin_group_subject(){
        $groupid = $this->get_in_int_val("groupid");
        $subject = $this->get_in_int_val("subject");
        $this->t_admin_group_name->field_update_list($groupid,["subject"=>$subject]);
        return $this->output_succ();
    }


    public function jack_test(){

        $ret_info = $this->t_course_order->get_lesson_left_info(1);
        $list = $this->t_course_order->get_lesson_left_info(2);
        foreach($ret_info as $k=>$item){
            if(isset($list[$k])){
                unset($ret_info[$k]);
            }
        }
        foreach($ret_info as $k=>$val){
            $ass_master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($val["uid"]);
            if($ass_master_adminid != 297){
                unset($ret_info[$k]);
            }

        }
        dd($ret_info);

    }

    public function update_test_lesson_require_teacher_info(){

        $start_time = strtotime(date("Y-m-d",time()))+86400;
        $end_time = strtotime(date("Y-m-d",time()))+2*86400;
        $day_start = strtotime(date("Y-m-d",time()));
        $this->t_test_lesson_require_teacher_list->del_test_lesson_require_teacher_info($day_start,$start_time);
        $list = $this->t_test_lesson_subject_require->get_need_plan_require($start_time,$end_time);
        $arr=[];
        foreach($list as &$item){
            $lesson_time  = $item["stu_request_test_lesson_time"];
            $end_time = $lesson_time+2400;
            $teacherid_arr = $this->t_lesson_info->get_test_lesson_num_by_free_time_new($lesson_time,$end_time);
            $subject      = $item["subject"];
            $grade        = $item["grade"];
            $date_week = \App\Helper\Utils::get_week_range($lesson_time,1);
            $lstart    = $date_week["sdate"];
            $lend      = $date_week["edate"];

            $tea_arr="";
            $ret_info = $this->t_teacher_info->get_usefull_teacher_list($teacherid_arr,$subject,$grade,$lstart,$lend);
            foreach($ret_info as $val){
                if($val["limit_plan_lesson_type"]>0){
                    $val["week_left_num"]=$val["limit_plan_lesson_type"]-$val["week_lesson_num"];
                }else{
                    $ret_num = $this->t_lesson_info->check_teacher_have_test_lesson_pre_week($val["teacherid"],$start_time);
                    if($ret_num==1){
                        $val["week_left_num"]=$val["limit_week_lesson_num"]-$val["week_lesson_num"];
                    }else{
                        $val["week_left_num"]=6-$val["week_lesson_num"];
                    }
                }
                if($val["week_left_num"]>0){
                    $this->t_test_lesson_require_teacher_list->row_insert([
                        "add_time"   => time(),
                        "require_id" => $item["require_id"],
                        "teacherid"  => $val["teacherid"]
                    ]);
                }

            }
        }
        return $this->output_succ();
    }

    public function fulltime_teacher_assessment_deal(){
        $save_type = $this->get_in_int_val("save_type");
        $positive_type = $this->get_in_int_val("positive_type");
        $adminid   = $this->get_in_int_val("adminid");
        $observe_law_score = $this->get_in_int_val("observe_law_score");
        $core_socialist_score = $this->get_in_int_val("core_socialist_score");
        $work_responsibility_score = $this->get_in_int_val("work_responsibility_score");
        $obey_leadership_score = $this->get_in_int_val("obey_leadership_score");
        $dedication_score = $this->get_in_int_val("dedication_score");
        $prepare_lesson_score = $this->get_in_int_val("prepare_lesson_score");
        $upload_handouts_score = $this->get_in_int_val("upload_handouts_score");
        $handout_writing_score = $this->get_in_int_val("handout_writing_score");
        $no_absences_score = $this->get_in_int_val("no_absences_score");
        $late_leave_score = $this->get_in_int_val("late_leave_score");
        $prepare_quality_score = $this->get_in_int_val("prepare_quality_score");
        $class_concent_score = $this->get_in_int_val("class_concent_score");
        $tea_attitude_score = $this->get_in_int_val("tea_attitude_score");
        $after_feedback_score = $this->get_in_int_val("after_feedback_score");
        $modify_homework_score = $this->get_in_int_val("modify_homework_score");
        $teamwork_positive_score = $this->get_in_int_val("teamwork_positive_score");
        $test_lesson_prepare_score = $this->get_in_int_val("test_lesson_prepare_score");
        $undertake_actively_score = $this->get_in_int_val("undertake_actively_score");
        $active_share_score = $this->get_in_int_val("active_share_score");
        $active_part_score = $this->get_in_int_val("active_part_score");
        $order_per_score = $this->get_in_int_val("order_per_score");
        $lesson_level_score = $this->get_in_int_val("lesson_level_score");
        $stu_lesson_total_score = $this->get_in_int_val("stu_lesson_total_score");
        $complaint_refund_score = $this->get_in_int_val("complaint_refund_score");
        $moral_education_score = $this->get_in_int_val("moral_education_score");
        $tea_score = $this->get_in_int_val("tea_score");
        $teach_research_score  = $this->get_in_int_val("teach_research_score");
        $result_score = $this->get_in_int_val("result_score");
        $total_score = $this->get_in_int_val("total_score");
        $rate_stars = $this->get_in_int_val("rate_stars");
        $order_per = $this->get_in_str_val("order_per");
        $stu_lesson_total  = $this->get_in_int_val("stu_lesson_total");
        $admin_info = $this->t_manager_info->field_get_list($adminid,"post,main_department");
        $this->t_fulltime_teacher_assessment_list->row_insert([
            "adminid"          =>$adminid,
            "add_time"         =>time(),
            "observe_law_score"   =>$observe_law_score,
            "core_socialist_score"=>$core_socialist_score,
            "work_responsibility_score" =>$work_responsibility_score,
            "obey_leadership_score"     =>$obey_leadership_score,
            "dedication_score"          =>$dedication_score,
            "prepare_lesson_score"      =>$prepare_lesson_score,
            "upload_handouts_score"     =>$upload_handouts_score,
            "handout_writing_score"     =>$handout_writing_score,
            "no_absences_score"         =>$no_absences_score,
            "late_leave_score"          =>$late_leave_score,
            "prepare_quality_score"     =>$prepare_quality_score,
            "class_concent_score"       =>$class_concent_score,
            "tea_attitude_score"        =>$tea_attitude_score,
            "after_feedback_score"      =>$after_feedback_score,
            "modify_homework_score"     =>$modify_homework_score,
            "teamwork_positive_score"   =>$teamwork_positive_score,
            "test_lesson_prepare_score" =>$test_lesson_prepare_score,
            "undertake_actively_score"  =>$undertake_actively_score,
            "active_share_score"        =>$active_share_score,
            "active_part_score"         =>$active_part_score,
            "order_per_score"           =>$order_per_score,
            "lesson_level_score"        =>$lesson_level_score,
            "stu_lesson_total_score"    =>$stu_lesson_total_score,
            "complaint_refund_score"    =>$complaint_refund_score,
            "moral_education_score"     =>$moral_education_score,
            "tea_score"                 =>$tea_score,
            "teach_research_score"      =>$teach_research_score,
            "result_score"              =>$result_score,
            "total_score"               =>$total_score,
            "rate_stars"                =>$rate_stars,
            "positive_type"             =>$positive_type,
            "post"                      =>7,
            "main_department"           =>2,
            "order_per"                 =>$order_per,
            "stu_lesson_total"          =>$stu_lesson_total ,
            "lesson_level"              =>$lesson_level_score
        ]);
        $id = $this->t_fulltime_teacher_assessment_list->get_last_insertid();

        return $this->output_succ(["id"=>$id]);
    }

    public function set_fulltime_teacher_self_assessment(){
        $id = $this->get_in_int_val("id");
        $self_assessment = trim($this->get_in_str_val("self_assessment"));

        $this->t_fulltime_teacher_positive_require_list->field_update_list($id,[
           "self_assessment"  =>$self_assessment
        ]);

        return $this->output_succ();
    }
    public function fulltime_teacher_positive_require_deal(){
        $positive_type = $this->get_in_int_val("positive_type");
        $adminid   = $this->get_in_int_val("adminid");
        $rate_stars = $this->get_in_int_val("rate_stars");
        $post = $this->get_in_int_val("post");
        $main_department = $this->get_in_int_val("main_department");
        $create_time = $this->get_in_int_val("create_time");
        $positive_time = $this->get_in_int_val("positive_time");
        $self_assessment = trim($this->get_in_str_val("self_assessment"));
        $level = $this->get_in_int_val("level");
        $positive_level = $this->get_in_int_val("positive_level");
        $assess_id = $this->get_in_int_val("assess_id");
        $ret = $this->t_fulltime_teacher_positive_require_list->row_insert([
            "adminid"          =>$adminid,
            "add_time"         =>time(),
            "rate_stars"                =>$rate_stars,
            "positive_type"             =>$positive_type,
            "post"                      =>7,
            "main_department"           =>2,
            "level"                     =>$level,
            "positive_level"            =>$positive_level,
            "create_time"               =>$create_time,
            "positive_time"             =>$positive_time,
            "assess_id"                 =>$assess_id,
            "self_assessment"           =>$self_assessment
        ]);
        if($ret){
            $name = $this->t_manager_info->get_name($adminid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"转正申请通知","转正申请通知",$name."老师的已提交转正申请,请审核!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请通知","转正申请通知",$name."老师的已提交转正申请,请审核!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);


        }
        return $this->output_succ();
    }

    public function fulltime_teacher_positive_require_deal_master(){
        $id   = $this->get_in_int_val("id");
        $master_deal_flag  = $this->get_in_int_val("master_deal_flag");
        $this->t_fulltime_teacher_positive_require_list->field_update_list($id,[
            "master_deal_flag"   =>$master_deal_flag,
            "mater_adminid"      =>$this->get_account_id(),
            "master_assess_time" =>time()
        ]);
        $adminid = $this->t_fulltime_teacher_positive_require_list->get_adminid($id);
        $name = $this->t_manager_info->get_name($adminid);

        if($master_deal_flag==2){

            $this->t_manager_info->send_wx_todo_msg_by_adminid ($adminid,"转正申请驳回","转正申请驳回通知",$name."老师,您的转正申请经主管审核,已经被驳回!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请驳回","转正申请驳回通知",$name."老师,您的转正申请经主管审核,已经被驳回!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);

        }elseif($master_deal_flag==1){
            $this->t_manager_info->send_wx_todo_msg_by_adminid (72,"转正申请提交","转正申请提交",$name."老师的转正申请经主管审核已同意,请审核!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info_master?adminid=".$adminid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请提交","转正申请提交",$name."老师的转正申请经主管审核已同意,请审核!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info_master?adminid=".$adminid);
        }
        return $this->output_succ();
    }
    public function fulltime_teacher_positive_require_deal_main_master(){
        $id   = $this->get_in_int_val("id");
        $main_master_deal_flag  = $this->get_in_int_val("main_master_deal_flag");
        $this->t_fulltime_teacher_positive_require_list->field_update_list($id,[
            "main_master_deal_flag"   =>$main_master_deal_flag,
            "main_mater_adminid"      =>$this->get_account_id(),
            "main_master_assess_time" =>time()
        ]);
        $positive_type = $this->t_fulltime_teacher_positive_require_list->get_positive_type($id);
        $positive_time = $this->t_fulltime_teacher_positive_require_list->get_positive_time($id);
        $adminid = $this->t_fulltime_teacher_positive_require_list->get_adminid($id);
        $name = $this->t_manager_info->get_name($adminid);

        if($positive_type==3){
            if($main_master_deal_flag==1){
                //微信通知主管和老师
                 $this->t_manager_info->send_wx_todo_msg_by_adminid ($adminid,"延期转正申请通过","延期转正申请通过通知",$name."老师,您的延期转正申请经主管和总监审核,已经通过","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"延期转正申请通过","延期转正申请通过通知",$name."老师,您的延期转正申请经主管和总监审核,已经通过","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"延期转正申请通过","延期转正申请通过通知",$name."老师的延期转正申请经总监审核,已经通过!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"延期转正申请通过","延期转正申请通过通知",$name."老师的延期转正申请经总监审核,已经通过!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);

            }elseif($main_master_deal_flag==2){
                $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"延期转正申请未通过","延期转正申请驳回通知",$name."老师的延期转正申请经总监审核,已经被驳回,请确认!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"延期转正申请未通过","延期转正申请驳回通知",$name."老师的延期转正申请经总监审核,已经被驳回,请确认!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
            }

        }else{
            if($main_master_deal_flag==1){
                $this->t_manager_info->field_update_list($adminid,[
                    "become_full_member_flag" =>1,
                    "become_full_member_time" =>$positive_time
                ]);

                //修改老师等级
                 $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
                $teacherid = $teacher_info["teacherid"];
                $this->t_teacher_info->field_update_list($teacherid,["level"=>1]);

                $this->t_lesson_info_b2->update_teacher_level($teacherid,1);

                //邮件发送给hr
                $email = ["sherry@leoedu.com","hr@leoedu.com","low-key@leoedu.com","erick@leoedu.com","jhp0416@163.com"];
                //$email = "jack@leoedu.cn";
                foreach($email as $e){
                    dispatch( new \App\Jobs\SendEmailNew(
                        $e,"全职老师转正通知","Dear all：<br>  ".$name."老师转正考核已通过,请调整该老师底薪,谢谢!!"
                    ));
                }

                //微信通知主管和老师
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($adminid,"转正申请通过","转正申请通过通知",$name."老师,您的转正申请经主管和总监审核,已经通过,恭喜您!","");
                //$this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请通过","转正申请通过通知",$name."老师,您的转正申请经主管和总监审核,已经通过,恭喜您!","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"转正申请通过","转正申请通过通知",$name."老师的转正申请经总监审核,已经通过!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid."become_full_member_flag=1");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请通过","转正申请通过通知",$name."老师的转正申请经总监审核,已经通过!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid."become_full_member_flag=1");

            }elseif($main_master_deal_flag==2){
                $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"转正申请未通过","转正申请驳回通知",$name."老师的转正申请经总监审核,已经被驳回,请确认!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请未通过","转正申请驳回通知",$name."老师的转正申请经总监审核,已经被驳回,请确认!","http://admin.yb1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
            }

        }
        return $this->output_succ();
    }



    public function fulltime_teacher_assessment_deal_master(){
        $moral_education_score_master = $this->get_in_int_val("moral_education_score_master");
        $tea_score_master = $this->get_in_int_val("tea_score_master");
        $teach_research_score_master  = $this->get_in_int_val("teach_research_score_master");
        $result_score_master = $this->get_in_int_val("result_score_master");
        $total_score_master = $this->get_in_int_val("total_score_master");
        $rate_stars_master = $this->get_in_int_val("rate_stars_master");
        $id = $this->get_in_int_val("id");
        $this->t_fulltime_teacher_assessment_list->field_update_list($id,[
            "moral_education_score_master" =>$moral_education_score_master,
            "teach_research_score_master"  =>$teach_research_score_master,
            "tea_score_master"             =>$tea_score_master,
            "result_score_master"          =>$result_score_master,
            "total_score_master"           =>$total_score_master,
            "rate_stars_master"            =>$rate_stars_master,
            "assess_adminid"               =>$this->get_account_id(),
            "assess_time"                  =>time()
        ]);
        return $this->output_succ();

    }

    public function set_teacher_phone_click_info(){
        $adminid = $this->get_account_id();
        $account_role = $this->t_manager_info->get_account_role($adminid);
        if($account_role != 4){
            return $this->output_succ(["data"=>0]);
        }
        $time = strtotime(date("Y-m-d",time()));
        $num = $this->t_teacher_phone_click_info->get_num($adminid,$time);
        $num_next = $num+1;
        if($num>=5){
            return $this->output_succ(["data"=>$num]);
        }elseif($num>0 && $num <5){
            $this->t_teacher_phone_click_info->field_update_list_2($adminid,$time,[
               "num" => $num_next
            ]);
        }else{
            $this->t_teacher_phone_click_info->row_insert([
                "adminid"  =>$adminid,
                "click_time" =>$time,
                "num"=>1
            ]);
        }
        return $this->output_succ(["data"=>$num]);
    }


    public function set_stu_cc_to_cr_info(){

        $cc_nick = $this->get_account();
        $cc_id  = $this->get_account_id();
        $ispost = $this->get_in_int_val('ispost');
        $data = \App\Helper\Utils::json_decode_as_array($this->get_in_str_val("data"));

        $orderid  = $data['orderid'];

        $state_arr = $this->t_student_cc_to_cr->get_last_id_reject_flag_by_orderid($orderid);

        // 判断
        if($state_arr && $state_arr['reject_flag'] == 0){
            return $this->output_err('交接单已提交,不可修改!');
        }

        $data['post_time'] = time(NULL);

        $data['cc_id']     = $cc_id;
        $data["call_time"] = strtotime($data["call_time"]);
        $data["first_lesson_time"] = strtotime($data["first_lesson_time"]);

        $check_min=date("H")*60+ date("i")  ;

        if($data["first_lesson_time"] < time(NULL)){
            return $this->output_err('首次课时间应在当前时间之后!');
        }

        // 当天10至第二天8点前提交的 首次上课时间 不得早于9点
        $tomo      = strtotime('+1 days');
        $tomo_str  = strtotime(date('Y-m-d',$tomo));  //明天0点时间戳

        $ten_time_str = strtotime(date('Y-m-d'))-2*60*60; // 今天下午10点
        $nin_time_str = strtotime(date('Y-m-d'))+9*60*60; // 明天上午9点
        $now = time(NULL);
        if($ten_time_str< $now && $now<$nin_time_str){
            if($data["first_lesson_time"]<$nin_time_str){
                return $this->output_err('首次上课时间不得早于第二天9点!');
            }
        }

        $today = strtotime(date('Y-m-d'));
        $today_nin = $today + 9*60*60;
        if($now<$today_nin){
            if($data["first_lesson_time"]<$today_nin){
                return $this->output_err('首次上课时间不得早于9点!');
            }
        }

        if($data["week_lesson_num"]==0){
             return $this->output_err("每周课次不能为0");
        }
        if($data["except_lesson_count"]==0){
            return $this->output_err("每次课时不能为0");
        }


        if(!$state_arr || ($state_arr['reject_flag'] == 1 && $ispost==1) ){
            // 插入
            $ret = $this->t_student_cc_to_cr->row_insert([
                "cc_id"       => $data['cc_id'],
                "orderid"     => $data['orderid'],
                "post_time"   => $data['post_time'],
                "real_name"   => $data['real_name'],
                "gender"      => $data['gender'],
                "grade"       => $data['grade'],
                "birth"       => $data['birth'],
                "school"      => $data['school'],
                "xingetedian" => $data['xingetedian'],
                "aihao"       => $data['aihao'],
                "yeyuanpai"          => $data['yeyuanpai'],
                "parent_real_name"   => $data['parent_real_name'],
                "parent_email"       => $data['parent_email'],
                "relation_ship"      => $data['relation_ship'],
                "phone"      => $data['phone'],
                "call_time"  => $data['call_time'],
                "addr"       => $data['addr'],
                "subject_yingyu"  => $data['subject_yingyu'],
                "subject_yuwen"   => $data['subject_yuwen'],
                "subject_shuxue"  => $data['subject_shuxue'],
                "subject_wuli"    => $data['subject_wuli'],
                "subject_huaxue"  => $data['subject_huaxue'],
                "class_top"       => $data['class_top'],
                "grade_top"       => $data['grade_top'],
                "subject_info"    => $data['subject_info'],
                "order_info"      => $data['order_info'],
                "teacher"         => $data['teacher'],
                "teacher_info"    => $data['teacher_info'],
                "test_lesson_info"     => $data['test_lesson_info'],
                "mail_addr"   => $data['mail_addr'],
                "has_fapiao"  => $data['has_fapiao'],
                "fapai_title" => $data['fapai_title'],
                "lesson_plan" => $data['lesson_plan'],
                "parent_other_require" => $data['parent_other_require'],
                "except_lesson_count"  => $data['except_lesson_count'],
                "week_lesson_num"      => $data['week_lesson_num'],
                // "common_lesson_time"   => $data['common_lesson_time'],
                "first_lesson_time"    => $data['first_lesson_time']
            ]);

            /**
               cc 驳回处理后 给cr发送微信推送
            **/

            if($state_arr['reject_flag'] == 1 ){
                $master_arr = $this->t_order_info->get_master_openid_by_orderid($data['orderid']);
                $wx     = new \App\Helper\Wx();
                $url = '/user_manage_new/ass_contract_list?studentid='.$master_arr['userid'];
                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
                $data_msg = [
                    "first"     => $cc_nick."已处理 ".$master_arr['nick']."同学被驳回的交接单 ",
                    "keyword1"  => " CC交接单驳回处理完成",
                    "keyword2"  => " CC交接单驳回处理完成",
                    "keyword3"  => " 提交时间:".date('Y-m-d H:i:s'),
                ];
                $wx->send_template_msg($master_arr['wx_openid'],$template_id,$data_msg ,$url);
            }

        }else{
            // 更新
            $this->t_student_cc_to_cr->field_update_list($state_arr['id'],$data);
        }

        $userid = $this->t_order_info->get_userid($orderid);

        $this->t_student_info->field_update_list($userid,[
            "init_info_pdf_url"   =>  "true",
            "seller_adminid" => $this->get_account_id(),
        ]);


        $account=$this->get_account();
        $this->t_student_info->noti_ass_order($userid, $account );
        return $this->output_succ();
    }


    public function do_reject_flag_for_init_info(){
        $acc_id         = $this->get_account_id();
        $acc_nick       = $this->get_account();
        $is_reject_flag = $this->get_in_int_val('is_reject_flag');
        $reject_info    = $this->get_in_str_val('reject_info','');
        $id             = $this->get_in_int_val('id');
        $orderid        = $this->get_in_int_val('orderid');
        $sid            = $this->get_in_int_val('sid',0);


        $cc_id      = $this->t_student_cc_to_cr->get_cc_id_by_id($id);
        $cc_openid  = $this->t_manager_info->get_wx_openid($cc_id);
        $now_date   = date('Y-m-d H:i:s');
        \App\Helper\Utils::logger("openid1:$cc_id");


        if($is_reject_flag){
            // 驳回
            $assistantid         = $this->t_student_info->get_assistantid_by_userid($sid);
            $ass_master_adminid  = $this->t_student_info->get_ass_master_adminid($sid);

            if($assistantid>0){
                return $this->output_err('交接单已分配了助教老师，不能驳回交接单!');
            }

           $ret = $this->t_student_cc_to_cr->field_update_list($id,[
                'reject_flag' => $is_reject_flag,
                'reject_time' => time(NULL),
                'reject_info' => $reject_info,
                'ass_id'      => $acc_id
            ]);


            // 发送推送

            /**
               {{first.DATA}}
               待办主题：{{keyword1.DATA}}
               待办内容：{{keyword2.DATA}}
               日期：{{keyword3.DATA}}
               {{remark.DATA}}
             **/

            if($ret){

                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
                $data_msg = [
                    "first"     => "交接单被驳回",
                    "keyword1"  => "交接单驳回处理",
                    "keyword2"  => "助教$acc_nick 驳回交接单, 交接单合同号$orderid,驳回原因:$reject_info",
                    "keyword3"  => "$now_date",
                ];
                $url = 'http://admin.yb1v1.com/stu_manage/init_info_by_contract_cr?orderid='.$orderid;
                $wx  = new \App\Helper\Wx();
                $result = $wx->send_template_msg($cc_openid,$template_id,$data_msg,$url);
            }

        }else{
            // 确认
            $ret = $this->t_student_cc_to_cr->field_update_list($id,[
                'reject_flag' => $is_reject_flag,
                'reject_time' => time(NULL),
                'ass_id'      => $acc_id
            ]);

        }

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err('提交失败!请联系开发人员!');
        }
    }


     public function get_teacher_textbook(){
        $textbook = $this->get_in_str_val('textbook');
        // $textbook = "2,3";
        $list    = E\Eregion_version::$desc_map;
        unset($list[0]);
        $res = [];
        $data=[];
        foreach($list as $i=>$val){
            $res[]=["textbook"=>$val,"num"=>$i];
            $data[]=$i;
        }
        if(!empty($textbook)){
            $textbook = trim($textbook,",");
            $arr = explode(",",$textbook);
            foreach ($arr as $k) {
                if( in_array($k,$data)){
                    foreach($res as $kk=>&$item){
                        if($k == $item["num"]){
                            $item["has_textbook"] = in_array($k,$data)?1:0;
                        }
                    }

                }

            }
        }
        return $this->output_succ(["data"=> $res]);
    }

    public function set_teacher_textbook(){
        $teacherid = $this-> get_in_int_val('teacherid');
        $old_textbook = $this-> get_in_str_val('old_textbook');
        $textbook_list = \App\Helper\Utils::json_decode_as_int_array( $this->get_in_str_val("textbook_list"));
        $teacher_textbook = implode(",",$textbook_list);
        $this->t_teacher_info->field_update_list($teacherid,[
            "teacher_textbook" => $teacher_textbook ,
        ] );

        $arr= explode(",",$teacher_textbook);
        foreach($arr as $val){
            @$new_textbook .=  E\Eregion_version::get_desc ($val).",";
        }
        $new_textbook = trim($new_textbook,",");

        $arr2= explode(",",$old_textbook);
        foreach($arr2 as $val){
            @$old_textbook2 .=  E\Eregion_version::get_desc ($val).",";
        }
        $old_textbook2 = trim($old_textbook2,",");
        $str = "由".$old_textbook2."改为".$new_textbook;
        $this->t_teacher_record_list->row_insert([
            "teacherid"=>$teacherid,
            "type"     =>14,
            "record_info"=>$str,
            "add_time"  =>time(),
            "acc"      =>$this->get_account()
        ]);
        // $id = $this->t_teacher_record_list->get_last_insertid();


        return $this->output_succ();
    }



}
