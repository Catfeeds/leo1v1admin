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
    use LessonPower;

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

    /**
     * 取消课程
     * /stu_manage/course_lesson_list 课程列表--排课 中的取消课程
     */
    public function cancel_lesson()
    {
        $lessonid = $this->get_in_int_val('lessonid',-1);

        $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);
        $lesson_del_flag = $lesson_info['lesson_del_flag'];
        if($lesson_del_flag!=0){
            return $this->output_err("本节课程已取消！不要重复操作！");
        }
        $start_date   = date("Y-m-d",$lesson_info['lesson_start']);
        $operate_date = date("Y-m-d",$lesson_info['operate_time']);
        $now_date     = date("Y-m-d",time());

        $lesson_type = $lesson_info['lesson_type'];
        if( $lesson_type==E\Econtract_type::V_2) {
            if ($this->t_seller_student_info->get_stu_performance_for_seller($lessonid) ) {
                return $this->output_err("试听课 有绑定,请从试听管理中取消");
            }
        }

        $lesson_status = $lesson_info['lesson_status'];
        if($lesson_status != E\Elesson_status::V_0){
            return $this->output_err("课程状态错误，只能删除未开始的课程！");
        }

        $ret = $this->t_lesson_info_b2->cancel_lesson_no_start($lessonid);
        if($ret){
            $this->add_cancel_lesson_operate_info($lessonid);
        }
        return $this->output_ret($ret);
    }

    /**
     * 修改课程课时
     */
    public function lesson_change_lesson_count()
    {
        $lessonid     = $this->get_in_int_val('lessonid',-1);
        $lesson_count = $this->get_in_int_val('lesson_count',0);

        $ret = $this->update_lesson_count_2018_1_6($lessonid,$lesson_count);

        return $ret;
    }

    /**
     * 修改课时,无法修改课时审查时间节点之前的课时  /config/admin.php  中的  lesson_confirm_start_time 时间
     * 2018年01月06日18:18:24之后不适用此接口
     */
    // public function update_lesson_count($lessonid,$lesson_count){
    //     $lesson_confirm_start_time=\App\Helper\Config::get_lesson_confirm_start_time();

    //     $lesson_start = $this->t_lesson_info->get_lesson_start($lessonid);
    //     if ($lesson_start<strtotime( $lesson_confirm_start_time) ) {
    //         return $this->output_err("上课时间太早了, 早于[$lesson_confirm_start_time] ");
    //     };

    //     $ret = $this->t_lesson_info->check_lesson_count_for_change($lessonid, $lesson_count);
    //     if (!$ret){
    //         return $this->output_err("课时数太大了");
    //     }
    //     $ret = $this->t_lesson_info->field_update_list($lessonid,[
    //         "lesson_count" => $lesson_count,
    //     ]);
    //     return $ret;
    // }

    /**
     * 课程包添加课程
     */
    public function lesson_add_lesson() {
        $courseid = $this->get_in_courseid();
        $acc      = $this->get_account();
        if(!in_array($acc,["jim"])){
            $ret = $this->add_regular_lesson($courseid,0,0);
            if(is_numeric($ret) ){
                return $this->output_succ(["lessonid" => $ret ]);
            }else{
                return $ret;
            }
        }

        $item = $this->t_course_order->field_get_list($courseid,"*");
        if(!$item["teacherid"]) {
           return $this->output_err("还没设置老师");
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
            $item["courseid"],0,$item["userid"],E\Efrom_type::V_0,$item["course_type"],$item["teacherid"],
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

    /**
     * 课程管理中的课时确认
     */
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

        //检测课时确认时间
        $check_flag   = $this->check_lesson_confirm_time_by_lessonid($lessonid);
        if($check_flag !== true){
            return $check_flag;
        }

        $lesson_confirm_start_time = \App\Helper\Config::get_lesson_confirm_start_time();
        $lesson_info  = $this->t_lesson_info->get_lesson_info($lessonid);
        $lesson_start = $lesson_info['lesson_start'];
        $lesson_type  = $lesson_info['lesson_type'];
        if ($lesson_start<strtotime($lesson_confirm_start_time)) {
            return $this->output_err("上课时间太早了, 早于[$lesson_confirm_start_time]");
        }

        if( in_array($confirm_flag,[E\Econfirm_flag::V_2,E\Econfirm_flag::V_3,E\Econfirm_flag::V_4]) ) {
            if($lesson_info['lesson_status'] != E\Elesson_status::V_END){
                $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_status" => E\Elesson_status::V_END,
                ]);
                $this->add_lesson_status_operate_info($lessonid,$lesson_info['lesson_status'],E\Elesson_status::V_END);
            }

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
                $update_lesson_count = \App\Helper\Utils::get_lesson_count($lesson_info['lesson_start'], $lesson_info['lesson_end']);
                $update_lesson_count /= 2;
                if($update_lesson_count!=$lesson_info['lesson_count']){
                    $this->t_lesson_info->field_update_list($lessonid,[
                        // "lesson_count" => $lesson_info['lesson_count']/2,
                        "lesson_count" => $update_lesson_count,
                    ]);
                    $this->add_lesson_count_operate_info($lessonid, $lesson_info['lesson_count'], $update_lesson_count);
                }
            }
        }else{
            if($lesson_info['lesson_end']>time() && $lesson_info['lesson_status']!=E\Elesson_status::V_NO_START){
                $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_status" => E\Elesson_status::V_NO_START,
                ]);
                $this->add_lesson_status_operate_info($lessonid,$lesson_info['lesson_status'],E\Elesson_status::V_NO_START);
            }
        }

        $this->t_lesson_info->field_update_list($lessonid,[
            "confirm_flag"                          => $confirm_flag,
            "confirm_adminid"                       => $this->get_account_id(),
            "confirm_time"                          => time(NULL),
            "confirm_reason"                        => $confirm_reason,
            "lesson_cancel_reason_type"             => $lesson_cancel_reason_type,
            "lesson_cancel_time_type"               => $lesson_cancel_time_type,
            "lesson_cancel_reason_next_lesson_time" => $lesson_cancel_reason_next_lesson_time,
        ]);
        $this->add_lesson_confirm_flag_operate_info($lessonid,$lesson_info['confirm_flag'],$confirm_flag);

        if($lesson_type!=2 && $lesson_cancel_reason_type>=11 && $lesson_cancel_reason_type<=21 ){
            $teacherid = $lesson_info["teacherid"];
            $realname = $this->t_teacher_info->get_realname($teacherid);
            $lesson_time = date("Y-m-d H:i:s",$lesson_info["lesson_start"]);
            $record_info = "上课完成:".E\Econfirm_flag::get_desc($confirm_flag)."<br>无效类型:".E\Elesson_cancel_reason_type::get_desc($lesson_cancel_reason_type)."<br>课堂确认情况:".E\Elesson_cancel_time_type::get_desc($lesson_cancel_time_type)."<br>无效说明:".$confirm_reason."<br>老师:".$realname."<br>上课时间:".$lesson_time;
            $revisit_time = time();

            $check_revisit_flag = $this->t_revisit_info->check_add_existed($lesson_info['userid'], $revisit_time);
            if(!$check_revisit_flag){
                $this->t_revisit_info->row_insert([
                    "userid"        => $lesson_info["userid"],
                    "revisit_time"  => $revisit_time,
                    "sys_operator"  => $this->get_account(),
                    "operator_note" => $record_info,
                    "revisit_type"  => 3
                ]);
            }
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
                $acc= $this->get_account();
                if(!in_array($acc,["jim"])){
                    $ret = $this->add_regular_lesson($courseid,$lesson_start,$lesson_end,$lesson_count,$lessonid);
                    if(is_numeric($ret) ){
                        return $this->output_succ();
                    }else{
                        return $ret;
                    }
                }

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
                        "<div>有现存的老师课程与该课程时间冲突！<a href='/teacher_info_admin/get_lesson_list?teacherid=$teacherid&lessonid=$error_lessonid' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
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
                $contract_status = $order_info['contract_status']==2?1:$order_info['contract_status'];
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
            // $appId       = \App\Helper\Config::get_teacher_wx_appid();
            // $appSecret   = \App\Helper\Config::get_teacher_wx_appsecret();
            // $wx  = new \App\Helper\Wx($appId,$appSecret);
            // $wx->send_template_msg($openid,$template_id,$data);
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

    /**
     * 设置/更改课程时间
     * @param int lessonid
     * @param int start 课程开始时间
     * @param int end   课程结束时间
     * @param int reset_lesson_count  是否重置课时 0 不重置 1 重置
     */
    public function set_lesson_time()
    {
        $lessonid = $this->get_in_int_val('lessonid',0);
        $start    = $this->get_in_str_val('start',0);
        $end      = $this->get_in_str_val('end',0);
        $reset_lesson_count = $this->get_in_str_val('reset_lesson_count',1);

        if ($start) {
            $lesson_start = strtotime($start);
            $date         = date('Y-m-d', strtotime($start));
            $lesson_end   = strtotime($date . " " . $end);
        }else{
            $lesson_start = $this->get_in_int_val("lesson_start");
            $lesson_end   = $this->get_in_int_val("lesson_end");
        }

        if ($lesson_start >= $lesson_end) {
            return $this->output_err( "时间不对: $lesson_start>$lesson_end  开始时间不能比结束时间大！");
        }

        $teacherid     = $this->t_lesson_info->get_teacherid($lessonid);
        $userid        = $this->t_lesson_info->get_userid($lessonid);
        $lesson_status = $this->t_lesson_info->get_lesson_status($lessonid);
        $lesson_count  = \App\Helper\Utils::get_lesson_count($lesson_start,$lesson_end);

        //百度分期用户首月排课限制
        // $period_limit = $this->check_is_period_first_month($userid,$lesson_count);
        //   if($period_limit){
        //   return $period_limit;
        //   }

        //逾期预警/逾期停课学员不能排课
        // $student_type = $this->t_student_info->get_type($userid);
        // if($student_type>4){
        //return $this->output_err("百度分期逾期学员不能排课!");
        // }

        $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);
        $lesson_type = $lesson_info['lesson_type'];

        $check = $this->research_fulltime_teacher_lesson_plan_limit($teacherid,$userid,$lesson_count/100,$lesson_start,$lesson_type,$lesson_end);
        if($check){
            return $check;
        }

        if ($lesson_type==E\Econtract_type::V_2) {
            if($reset_lesson_count){
                if(!$this->check_power(E\Epower::V_ADD_TEST_LESSON)) {
                    return $this->output_err("没有权限操作试听课");
                }
                $db_lesson_start = $this->t_lesson_info->get_lesson_start($lessonid);
                if ($db_lesson_start) {
                    return $this->output_err("试听课不能修改时间,只能删除,重新排新课,再设置时间");
                }
            }

            $old_date   = date("Y-m-d",$lesson_info['lesson_start']);
            $start_date = date("Y-m-d",$lesson_start);
            $end_date   = date("Y-m-d",$lesson_end);

            if($old_date!=$start_date || $old_date!=$end_date){
                return $this->output_err("只能修改在 $old_date 内");
            }
            if($lesson_status==E\Elesson_status::V_2){
                return $this->output_err("课程已结束,无法更改课程时间!");
            }
        }else{
            $userid       = $this->t_lesson_info->get_userid($lessonid);
            $is_test_user = $this->t_student_info->get_is_test_user($userid);

            if(in_array($lesson_type,[0,1,3]) && $is_test_user==0){
                if($lesson_status!=E\Elesson_status::V_0){
                    return $this->output_err("课程不是未开始状态,无法更改课程时间!");
                }

                $account_role = $this->get_account_role();
                if($account_role != E\Eaccount_role::V_1 && $account_role != E\Eaccount_role::V_12){
                    return $this->output_err("只有助教可以更改常规课时间！");
                }
            }
        }

        $userid = $this->t_lesson_info->get_userid($lessonid);
        if ($userid) {
            $ret_row = $this->t_lesson_info->check_student_time_free(
                $userid,$lessonid,$lesson_start,$lesson_end
            );
            if($ret_row) {
                $error_lessonid = $ret_row["lessonid"];
                return $this->output_err(
                    "<div>有现存的<div color=\"red\">学生</div>课程与该课程时间冲突！"
                    ."<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>"
                    ."查看[lessonid=$error_lessonid]<a/><div> "
                );
            }
        }

        $ret_row = $this->t_lesson_info->check_teacher_time_free(
            $teacherid,$lessonid,$lesson_start,$lesson_end);
        if($ret_row) {
            $error_lessonid = $ret_row["lessonid"];
            return $this->output_err(
                "<div>有现存的<div color=\"red\">老师</div>课程与该课程时间冲突！"
                ."<a href='/teacher_info_admin/get_lesson_list?teacherid=$teacherid&lessonid=$error_lessonid' target='_blank'>"
                ."查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $ret = true;
        if($lesson_type<1000 && $reset_lesson_count){
            $ret = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);
        }

        // 第一次常规课后 将课程规划与试听交接单推送老师
        if ($ret) {
            if($reset_lesson_count){
                $lesson_change_flag = $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_count" => $lesson_count,
                    "operate_time" => time(),
                    "sys_operator" => $this->get_account()
                ]);
                if($lesson_change_flag){
                    $this->add_lesson_count_operate_info($lessonid, $lesson_info['lesson_count'], $lesson_count);
                }
            }
            $lesson_time_change_flag = $this->t_lesson_info->set_lesson_time($lessonid,$lesson_start,$lesson_end);
            if($lesson_time_change_flag){
                $this->add_lesson_time_operate_info(
                    $lessonid,$lesson_info['lesson_start'],$lesson_start,$lesson_info['lesson_end'],$lesson_end
                );
            }

            // 发送微信提醒 send_template_msg($teacherid,$template_id,$data,
            $url              = "";
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
            if(!empty($lesson_info['lesson_start'])){
                $this->t_teacher_info->send_template_msg($teacherid,$template_id,$data_msg,$url);

                $wx=new \App\Helper\Wx();
                if($parent_wx_openid){
                    $ret=$wx->send_template_msg($parent_wx_openid,$template_id,$data_msg ,$url);
                }

                //非助教自己排课,发送推送给助教
                $assistantid = $this->t_lesson_info->get_assistantid($lessonid);

                $adminid_ass = $this->t_assistant_info->get_adminid_by_assistand($assistantid);
                if($adminid != $adminid_ass && $adminid_ass>0){
                    $ass_oponid = $this->t_manager_info->get_wx_openid($adminid_ass);
                    $nick = $this->t_student_info->get_nick($userid);
                    $data_msg = [
                        "first"    => "上课时间调整通知",
                        "keyword1" => "上课时间调整",
                        "keyword2" => "学生".$nick."从 $old_lesson_start 至 $old_lesson_end 的课程 已调整为 $lesson_start 至 $lesson_end",
                        "remark" => " 修改人: $operation_name 联系电话: $operation_phone"
                    ];

                    $wx->send_template_msg($ass_oponid,$template_id,$data_msg ,$url);
                    // $wx->send_template_msg("orwGAsxjW7pY7EM5JPPHpCY7X3GA",$template_id,$data_msg ,$url);
                }

                //  $wx->send_template_msg("orwGAsxjW7pY7EM5JPPHpCY7X3GA",$template_id,$data_msg ,$url);

                // 获取教务的openid
                $jw_openid = $this->t_test_lesson_subject_require->get_jw_openid($lessonid);
                if ($jw_openid) {
                    $wx->send_template_msg($jw_openid,$template_id,$data_msg ,$url);
                }

                //数据记录
                $phone = $this->t_student_info->get_phone($userid);
                $this->t_book_revisit->add_book_revisit(
                    $phone,
                    "$lessonid :从 $old_lesson_start 至 $old_lesson_end 的课程 已调整为 $lesson_start 至 $lesson_end 修改人: $operation_name 联系电话: $operation_phone",
                    "system"
                );
            }

            return $this->output_succ();
        }else{
            $str= $lesson_count/100;
            return $this->output_err("课时不足,需要课时数:$str");
        }
    }

    public function course_set_default_lesson_count () {
        $account      = $this->get_account();
        $account_role = $this->get_account_role();
        if ($this->get_account() != "jim"  && $this->get_account() != "adrian"  && $account_role!=13 ) {
            return $this->output_err("没有权限");
        }

        $orderid          = $this->get_in_int_val("orderid");
        $lesson_total     = $this->get_in_int_val("lesson_total");
        $old_lesson_total = $this->get_in_int_val("old_lesson_total");
        $lesson_left      = $this->get_in_int_val("lesson_left");

        if($lesson_total != $old_lesson_total){
            $lesson_diff = $lesson_total-$old_lesson_total;
            $lesson_left+= $lesson_diff;
            $ret = $this->t_order_info->field_update_list($orderid,[
                "lesson_total"=>$lesson_total,
                "default_lesson_count"=>1,
                "lesson_left"=>$lesson_left,
            ]);
            if(!$ret){
                return $this->output_err("更新失败！");
            }
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
        $can_period_flag = $this->get_in_int_val("can_period_flag" );
        $contract_starttime = time();
        $contract_endtime   = time()+86400*365*3;

        $this->t_order_info->field_update_list($orderid,[
            "check_money_flag"    => $check_money_flag,
            "check_money_desc"    => $check_money_desc,
            "check_money_time"    => time(NULL),
            "check_money_adminid" => $this->get_account_id(),
            "contract_starttime"  => $contract_starttime,
            "contract_endtime"    => $contract_endtime,
            "can_period_flag"     => $can_period_flag,
        ]);


        $order_item = $this->t_order_info->field_get_list($orderid,"contract_type,userid, sys_operator, lesson_total,price");

        $flowid=$this->t_flow->get_flowid_from_key_int(E\Eflow_type::V_SELLER_ORDER_REQUIRE, $orderid );
        $sys_operator  = $order_item["sys_operator"];
        $userid        = $order_item["userid"];

        //记录数据
        $phone = $this->t_student_info->get_phone($userid);
        $nick = $this->t_student_info->get_nick($userid);
        $this->t_book_revisit->add_book_revisit(
            $phone,
            $nick."财务确认",
            "system"
        );

        $contract_type = $order_item["contract_type"];
        $lesson_total  = $order_item["lesson_total"];
        $price         = $order_item["price"]/100;
        if($contract_type==0 &&  $check_money_flag == 1){
            $start_time            = strtotime(date("Y-m-d"));
            $end_time              = $start_time+20*86400-1;
            // $seller_new_count_type = E\Eseller_new_count_type::V_ORDER_ADD ;
            $seller_new_count_type = E\Eseller_new_count_type::V_CJG_ORDER_ADD;
            $value_ex              = $orderid;
            $adminid               = $this->t_manager_info->get_id_by_account($sys_operator);
            if (!$flowid  ){
                if ( $price<10000){
                    $count=3;
                }else{
                    $count=5;
                }

                if (!$this->t_seller_new_count->check_adminid_seller_new_count_type_value_ex($adminid,$seller_new_count_type,$value_ex)) {
                    // $this->t_seller_new_count->add($start_time,$end_time,$seller_new_count_type,$count,$adminid,$value_ex);
                    // $this->t_manager_info->send_wx_todo_msg(
                    //     $sys_operator,
                    //     "系统",
                    //     "新签合同赠送 抢新生名额[$count] "
                    //     ,"学生:". $this->cache_get_student_nick($userid)
                    //     ,"");
                    //公海签单奖励5个
                    $hand_get_adminid = $this->t_seller_student_new->field_get_value($userid,'hand_get_adminid');
                    if($hand_get_adminid == E\Ehand_get_adminid::V_5){
                        $this->t_seller_new_count->add($start_time,$start_time+15*86400-1,$seller_new_count_type,$count=5,$adminid,$value_ex);
                        $this->t_manager_info->send_wx_todo_msg(
                            '龚隽',
                            "CC:".$sys_operator,
                            "公海签单[$phone]赠送 抢新生名额[$count]个 ",
                            "学生:". $this->cache_get_student_nick($userid),
                            ""
                        );
                        $this->t_manager_info->send_wx_todo_msg(
                            'tom',
                            "CC:".$sys_operator,
                            "公海签单[$phone]赠送 抢新生名额[$count]个 ",
                            "学生:". $this->cache_get_student_nick($userid),
                            ""
                        );
                    }
                }
            }
            $sys_operator=$this->t_order_info->get_sys_operator($orderid);
            $this->t_student_info->noti_ass_order($userid,$sys_operator,false);
            $this->t_seller_student_new->field_update_list($userid, ['orderid'=>$orderid]);

            $order_time = $this->t_order_info->field_get_value($orderid, 'order_time');
            $origin = $this->t_seller_student_origin->get_last_origin($userid,$order_time);
            if($origin != ''){
                $this->t_seller_student_origin->field_update_list_2($userid, $origin, ['last_orderid'=>$orderid]);
            }
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
        $no_update_seller_level_flag = $this->get_in_int_val("no_update_seller_level_flag");
        $seller_student_assign_type = $this->get_in_e_seller_student_assign_type( );
        //$become_member_time      = $this->get_in_str_val("become_member_time");
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

        $manager_info = $this->t_manager_info->field_get_list($uid,'face_pic,level_face_pic,seller_level,become_full_member_time,become_full_member_flag,create_time,become_member_time');
        $face_pic = $manager_info['face_pic'];
        $level_face_pic = $manager_info['level_face_pic'];
        $level_info = $this->t_seller_level_goal->field_get_list($seller_level,'level_face');
        $level_face = $level_info['level_face'];
        if($face_pic && $seller_level != $manager_info['seller_level'] && $level_face){
            $face_pic_str = substr($face_pic,-12,5);
            $ex_str = $seller_level.$face_pic_str;
            $level_face_pic = $this->get_top_img($uid,$face_pic,$level_face,$ex_str);
        }

        if($become_full_member_flag==1 && empty($manager_info["become_full_member_time"]) && $manager_info["become_full_member_flag"]==0 ){
            $become_full_member_time = time();
        }elseif($become_full_member_flag==1 && empty($manager_info["become_full_member_time"])){
            if($manager_info["become_member_time"]){
                $manager_info["create_time"] = $manager_info["become_member_time"];
            }
            $become_full_member_time = $manager_info["create_time"]+90*86400;
        }else{
            $become_full_member_time = $manager_info["become_full_member_time"];
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
            "main_department" =>$main_department,
            "level_face_pic" => $level_face_pic,
            "no_update_seller_level_flag" => $no_update_seller_level_flag,
            "become_full_member_time"     => $become_full_member_time,
            "seller_student_assign_type"    => $seller_student_assign_type ,
            //'become_member_time' => strtotime($become_member_time.' '.date('H:i', time()))
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

    //处理等级头像
    public function get_top_img($adminid,$face_pic,$level_face,$ex_str){
        $datapath = $face_pic;
        $datapath_new = $level_face;
        $datapath_type = @end(explode(".",$datapath));
        $datapath_type_new = @end(explode(".",$datapath_new));
        $image_1 = $this->yuan_img($datapath);
        if($datapath_type_new == 'jpg' || $datapath_type_new == 'jpeg'){
            $image_2 = imagecreatefromjpeg($datapath_new);
        }elseif($datapath_type_new == 'png'){
            $image_2 = imagecreatefrompng($datapath_new);
        }elseif($datapath_type_new == 'gif'){
            $image_2 = imagecreatefromgif($datapath_new);
        }elseif($datapath_type_new == 'wbmp'){
            $image_2 = imagecreatefromwbmp($datapath_new);
        }else{
            $image_2 = imagecreatefromstring($datapath_new);
        }
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
        $color = imagecolorallocatealpha($image_3,255,255,255,1);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);

        imagecopyresampled($image_3,$image_2,0,0,0,0,imagesx($image_3),imagesy($image_3),imagesx($image_2),imagesy($image_2));
        imagecopymerge($image_1,$image_3,0,0,0,0,imagesx($image_3),imagesx($image_3),100);
        // header('Content-type: image/jpg');
        // dd(imagepng($image_1));

        $tmp_url = "/tmp/".$adminid."_".$ex_str."_gd.png";
        imagepng($image_1,$tmp_url);
        $file_name = \App\Helper\Utils::qiniu_upload($tmp_url);
        $level_face_url = '';
        if($file_name!=''){
            $cmd_rm = "rm /tmp/".$adminid."*.png";
            \App\Helper\Utils::exec_cmd($cmd_rm);
            $domain = config('admin')['qiniu']['public']['url'];
            $level_face_url = $domain.'/'.$file_name;
        }
        return $level_face_url;
    }

    /**
     *  blog:http://www.zhaokeli.com
     * 处理成圆图片,如果图片不是正方形就取最小边的圆半径,从左边开始剪切成圆形
     * @param  string $imgpath [description]
     * @return [type]          [description]
     */
    function yuan_img($imgpath = './tx.jpg') {
        $ext     = pathinfo($imgpath);
        $src_img = null;
        switch ($ext['extension']) {
        case 'jpg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'jpeg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'png':
            $src_img = imagecreatefrompng($imgpath);
            break;
        }
        $wh  = getimagesize($imgpath);
        $w   = $wh[0];
        $h   = $wh[1];
        $w   = min($w, $h);
        $h   = $w;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2-20; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        // dd($r,$y_x,$y_y);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x+14, $y+14, $rgbColor);
                }
            }
        }
        return $img;
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
        $key0=$this->get_in_str_val("key0");
        $key1=$this->get_in_str_val("key1");
        $key2=$this->get_in_str_val("key2");
        $key3=$this->get_in_str_val("key3");
        $key_str="key0";
        if ($key3){
            $key_str="key4";
        }else  if ($key2){
            $key_str="key3";
        }else  if ($key1){
            $key_str="key2";
        }elseif($key0){
            $key_str="key1";
        }
        \App\Helper\Utils::logger("key_str $key_str ");
        $list=$this->t_origin_key->get_key_list($key1,$key2,$key3,$key_str,$key0);
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
        $key0=$this->get_in_str_val("key0");
        $key1=$this->get_in_str_val("key1");
        $key2=$this->get_in_str_val("key2");
        $key3=$this->get_in_str_val("key3");

        $key0_list=$this->t_origin_key->get_key_list("","","","key0");
        $key1_list=[];
        $key2_list=[];
        $key3_list=[];
        $key4_list=[];
        if($key0){
            $key1_list=$this->t_origin_key->get_key_list("","","","key1",$key0);
            if ( $key1 ) {
                $key2_list=$this->t_origin_key->get_key_list($key1,"","","key2",$key0);
                if ($key2) {
                    $key3_list=$this->t_origin_key->get_key_list($key1,$key2,"","key3",$key0);
                    if ($key3) {
                        $key4_list=$this->t_origin_key->get_key_list($key1,$key2,$key3,"key4",$key0);
                    }
                }
            }
        }
        return $this->output_succ([
            "key0_list"=>$key0_list,
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
        $main_type_list =["助教"=>1,"销售"=>2,"教务"=>3,"全职老师"=>5];
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
        $courseid                = $this->get_in_courseid();
        $course_status           = $this->get_in_int_val("course_status");
        $teacherid               = $this->get_in_teacherid();
        $subject                 = $this->get_in_int_val("subject");
        $grade                   = $this->get_in_int_val("grade");
        $lesson_grade_type       = $this->get_in_int_val("lesson_grade_type");
        $default_lesson_count    = $this->get_in_int_val("default_lesson_count");
        $week_comment_num        = $this->get_in_int_val("week_comment_num");
        $enable_video            = $this->get_in_int_val("enable_video");
        $old_enable_video        = $this->get_in_int_val("old_enable_video");
        $reset_lesson_count_flag = $this->get_in_int_val("reset_lesson_count_flag");
        $account                 = $this->get_account();

        $check_flag = $this->check_teacher_is_pass($teacherid);
        if(!$check_flag){
            return $this->output_err("该老师不是正式老师!");
        }
        if($subject!=E\Esubject::V_2 && $reset_lesson_count_flag==1){
            return $this->output_err("只有数学可以设置‘常规课上奥数课标示’为‘是’!\n如果原课程包变更科目，请新增课程包！");
        }

        $data = [
            "course_status"        => $course_status,
            "teacherid"            => $teacherid,
            "subject"              => $subject,
            "grade"                => $grade,
            "lesson_grade_type"    => $lesson_grade_type,
            "default_lesson_count" => $default_lesson_count,
            "week_comment_num"     => $week_comment_num,
            "enable_video"         => $enable_video,
            "reset_lesson_count_flag" => $reset_lesson_count_flag,
        ];

        $ret = $this->t_course_order->field_update_list($courseid,$data);
        \App\Helper\Utils::logger("course info has update.courseid is".$courseid
                                  ." course data to:".json_encode($data)."account:".$account." time:".time());

        if($old_enable_video!=$enable_video){
            $this->t_lesson_info->reset_lesson_enable_video($courseid,$enable_video);
        }
        return $this->output_succ();
    }

    public function course_add_new(){
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

        //6-9月份新建学生课程包需升一个年级
        $month = date("m",time());
        if($month>6 && $month <9){
            $stu_info['grade'] = \App\Helper\Utils::get_next_grade($stu_info['grade']);
            $lesson_grade_type = 1;
        }

        $check_flag = $this->check_teacher_is_pass($teacherid);
        if(!$check_flag){
            return $this->output_err("该老师不是正式老师!");
        }

        $confirm_flag = $this->t_student_cc_to_cr->get_confirm_flag($userid);
        if($confirm_flag == 1){
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
            "lesson_grade_type"     => $lesson_grade_type,
            "course_status"         => $course_status,
            "is_kk_flag"            => $is_kk_flag
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

    public function auto_add_course(){
        $teacherid            = $this->get_in_teacherid();
        $subject              = $this->get_in_int_val("subject");
        $lesson_grade_type    = $this->get_in_int_val("lesson_grade_type");
        $account              = $this->get_account();
        $userid               = $this->get_in_int_val("userid");
        $competition_flag     = $this->get_in_int_val("competition_flag");
        $per_lesson_time      = $this->get_in_int_val("per_lesson_time",0) * 60;
        $course_start_time    = $this->get_in_str_val("course_start_time",0);
        $course_end_time      = $this->get_in_str_val("course_end_time",0);


        if ($course_start_time) {
            $lesson_start = strtotime( $course_start_time);
            $date         = date('Y-m-d', $lesson_start);
            $lesson_end   = strtotime($date . " " . $course_end_time);
        }

        if ($lesson_start >= $lesson_end) {
            return $this->output_err( "时间不对: $lesson_start>$lesson_end");
        } else {
            //规整时间
            $time_num = $lesson_end - $lesson_start;
            $lesson_num = round($time_num / $per_lesson_time);
            $lesson_end = $lesson_start + $lesson_num*$per_lesson_time;
        }

        $stu_info = $this->t_student_info->get_student_simple_info($userid);
        if (!$stu_info["assistantid"] ) {
            return $this->output_err("未设置助教!");
        }

        //6-9月份新建学生课程包需升一个年级
        $month = date("m",time());
        if($month>6 && $month <9){
            $stu_info['grade'] = \App\Helper\Utils::get_next_grade($stu_info['grade']);
            $lesson_grade_type = 1;
        }

        //检测是否为测试老师
        $tea_info = $this->t_teacher_info->get_teacher_info($teacherid);

        if(!$tea_info['is_test_user']){
            return $this->output_err("该老师不是测试老师!");
        }

        //检测时间冲突
        $ret_row = $this->t_lesson_info->check_student_time_free(
            $userid,0,$lesson_start,$lesson_end
        );

        if($ret_row) {
            $error_lessonid=$ret_row["lessonid"];
            return $this->output_err(
                "<div>有现存的<div style='color:red'>学生</div>课程与该课程时间冲突！"
                ."<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>"
                ."查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $ret_row=$this->t_lesson_info->check_teacher_time_free(
            $teacherid,0,$lesson_start,$lesson_end);

        if($ret_row) {
            $error_lessonid=$ret_row["lessonid"];
            return $this->output_err(
                "<div>有现存的<div  style='color:red'>老师</div>课程与该课程时间冲突！"
                ."<a href='/teacher_info_admin/get_lesson_list?teacherid=$teacherid&lessonid=$error_lessonid' target='_blank'>"
                ."查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $this->t_lesson_info->start_transaction();
        $res = $this->t_course_order->row_insert([
            "userid"                => $userid,
            "teacherid"             => $teacherid,
            "subject"               => $subject,
            "grade"                 => $stu_info['grade'],
            "assistantid"           => $stu_info['assistantid'],
            "lesson_total"          => 0,
            "assigned_lesson_count" => 0,
            "default_lesson_count"  => 0,
            "competition_flag"      => $competition_flag,
            "add_time"              => time(),
            "lesson_grade_type"     => $lesson_grade_type,
            "course_status"         => 0,
            "is_kk_flag"            => 0
        ]);
        if(!$res) {
            $this->t_lesson_info->rollback();
            return $this->output_err('排课失败,请刷新重试!');
        }

        $courseid_type0 = $this->t_course_order->get_last_insertid();


        $lesson_total = floor($lesson_num/2);

        $res = $this->t_course_order->row_insert([
            "userid"                => 0,
            "teacherid"             => $teacherid,
            "subject"               => $subject,
            "grade"                 => $stu_info['grade'],
            "assistantid"           => $stu_info['assistantid'],
            "lesson_total"          => 0,
            "assigned_lesson_count" => 0,
            "default_lesson_count"  => 0,
            "competition_flag"      => $competition_flag,
            "add_time"              => time(),
            "lesson_grade_type"     => $lesson_grade_type,
            "course_status"         => 0,
            "is_kk_flag"            => 0,
            "course_type"           => 3001,
            "course_name"           => '自动排课-小班课',
            "lesson_total"          => $lesson_total,
            "stu_total"             =>1,
        ]);
        if(!$res) {
            $this->t_lesson_info->rollback();
            return $this->output_err('排课失败,请刷新重试!');
        }

        $courseid_type3001 = $this->t_course_order->get_last_insertid();

        $res = $this->t_small_class_user->row_insert([
            "courseid"  => $courseid_type3001,
            "userid"    => $userid,
            "join_time" => time(),
        ]);
        if(!$res) {
            $this->t_lesson_info->rollback();
            return $this->output_err('排课失败,请刷新重试!');
        }


        //排课

        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");
        for ($i = 0; $i < $lesson_num; $i++) {
            $start = $lesson_start + $i*$per_lesson_time;
            $end = $start + $per_lesson_time;
            if ($i%2 == 0) {
                $courseid = $courseid_type0;
                $type = 0;
            } else {
                $courseid = $courseid_type3001;
                $type = 3001;
            }
           $res = $this->auto_add_lesson($courseid,$userid,$teacherid,$stu_info["assistantid"],
                                   $start, $end,$stu_info['grade'],$subject,
                                   $teacher_info['teacher_money_type'],$teacher_info['level'],
                                   $competition_flag,$type);

            if(!$res) {
                $this->t_lesson_info->rollback();
                return $this->output_err('排课失败,请刷新重试!');
            }

        }
        $this->t_lesson_info->commit();
        return $this->output_succ();
    }

    public function auto_add_lesson($courseid,$userid,$teacherid,$assistantid,$lesson_start,$lesson_end,$grade,$subject,$teacher_money_type,$level,$competition_flag, $type) {


        if ($type == 0) {
            $lessonid = $this->t_lesson_info->add_lesson(
                $courseid,0,$userid,0,$type,$teacherid,
                $assistantid,$lesson_start,$lesson_end,$grade,$subject,0,
                $teacher_money_type,$level,
                $competition_flag
            );
        } else {
            $lessonid = $this->t_lesson_info->add_lesson(
                $courseid,0,0,0,$type,$teacherid,
                $assistantid,$lesson_start,$lesson_end,$grade,$subject,0,
                $teacher_money_type,$level,
                $competition_flag
            );

           $res = $this->t_small_lesson_info->row_insert([
                    "lessonid" => $lessonid,
                    "userid"   => $userid,
                ]);

            if(!$res) {
                return false;
            }

        }

        if(!$lessonid) {
            return false;
        }
        $this->t_lesson_info->reset_lesson_list($courseid);
        return true;
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
        $first_gruopid = $this->get_in_int_val('first_groupid');

       $ret = $this->t_admin_main_group_name->row_insert([
            "main_type"  => $main_type ,
            "group_name"  => $group_name,
            "up_groupid"  => $first_gruopid
        ]);

        return $this->output_succ();

    }

    public function admin_major_group_add(){
        $main_type  = $this->get_in_str_val("main_type");
        $group_name = $this->get_in_str_val("group_name");

        $this->t_admin_majordomo_group_name->row_insert([
            "main_type"   => $main_type ,
            "group_name"  => $group_name,
        ]);

        return $this->output_succ();
    }

    public function admin_main_group_add_new() {
        $main_type=$this->get_in_str_val("main_type");
        $group_name=$this->get_in_str_val("group_name");
        $month = strtotime($this->get_in_str_val("start_time"));
        $first_groupid = $this->get_in_int_val("first_groupid");

        $max_groupid = $this->t_main_group_name_month->get_max_groupid($month);
        $groupid = $max_groupid + 1;
        $this->t_main_group_name_month->row_insert([
            "month"       => $month,
            "main_type"   => $main_type ,
            "group_name"  => $group_name,
            "groupid"     => $groupid,
            "up_groupid"  => $first_groupid
        ]);

        return $this->output_succ();
    }

    public function admin_major_group_add_new() {
        $main_type   = $this->get_in_str_val("main_type");
        $group_name  = $this->get_in_str_val("group_name");
        $month       = strtotime($this->get_in_str_val("start_time"));

        $max_groupid = $this->t_main_major_group_name_month->get_max_groupid($month);
        // dd($max_groupid);
        $groupid = $max_groupid + 1;
        $this->t_main_major_group_name_month->row_insert([
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

    public function admin_major_group_edit( ) {
        $groupid=$this->get_in_int_val("groupid");
        $group_name=$this->get_in_str_val("group_name");
        $master_adminid=$this->get_in_str_val("master_adminid");

        $this->t_admin_majordomo_group_name->field_update_list($groupid, [
            "group_name"  => $group_name,
            "master_adminid"=>$master_adminid
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

    public function admin_major_group_del ()  {

        $groupid  = $this->get_in_int_val("groupid");
        $this->t_admin_majordomo_group_name->row_delete($groupid);
        $this->t_admin_main_group_name->update_by_up_groupid($groupid);
        return $this->output_succ();
    }

    public function admin_main_group_del ()  {

        $groupid=$this->get_in_int_val("groupid");
        $this->t_admin_main_group_name->row_delete($groupid);
        $this->t_admin_group_name->update_by_up_groupid($groupid);
        return $this->output_succ();
    }

    public function admin_major_group_del_new ()  {

        $groupid=$this->get_in_int_val("groupid");
        $month = strtotime($this->get_in_str_val("start_time"));
        $this->t_main_major_group_name_month->row_delete_for_major($groupid,$month);
        $this->t_main_group_name_month->update_by_up_groupid($groupid,$month);
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

        $db_groupid=$this->t_admin_group_user->get_groupid_by_adminid(-1,$adminid);
        $group_name = '';
        if ($db_groupid ) {//
            $group_name=$this->t_admin_group_name->get_group_name_by_groupid($db_groupid);
            // $this->t_admin_group_user->row_delete_2( $db_groupid, $adminid);
            return $this->output_err("此人已在[$group_name]中,不能添加");
        }

        $this->t_admin_group_user->row_insert([
            "groupid"   => $groupid,
            "adminid"   => $adminid,
        ]);

        // 添加到 日志表
        $this->t_user_group_change_log->row_insert([
            "add_time"   => time(),
            "userid"     => $adminid,
            "do_adminid" => $this->get_account_id(),
            "old_group"  => $group_name
        ]);

        return $this->output_succ();
    }

    public function admin_group_user_add_new()  {
        $groupid=$this->get_in_int_val("groupid");
        $adminid=$this->get_in_int_val("adminid");
        $main_type=$this->get_in_int_val("main_type");
        $month = strtotime($this->get_in_str_val("start_time"));

        $db_groupid=$this->t_group_user_month->get_groupid_by_adminid(-1,$adminid,$month);
        if ($db_groupid ) {
            $group_name=$this->t_group_name_month->get_group_name($db_groupid,$month);
            return $this->output_err("此人已在".date('Y-m',$month)."月[$group_name]中,不能添加");
        }

        $this->t_group_user_month->row_insert([
            "groupid"   => $groupid,
            "adminid"   => $adminid,
            "month"     => $month
        ]);

        // 添加到 日志表
        $this->t_user_group_change_log->row_insert([
            "add_time"   => time(),
            "userid"     => $adminid,
            "do_adminid" => $this->get_account_id()
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

        //记录数据
        $phone = $this->t_student_info->get_phone($userid);
        $nick = $this->t_student_info->get_nick($userid);
        $this->t_book_revisit->add_book_revisit(
            $phone,
            $nick."交界单提交(老)",
            "system"
        );



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

    /**
     * 重置学生年级并更新所选时间后的课程年级
     * @param userid 重置的学生id
     * @param start_time 重置课程的开始时间,没有则不重置
     * @param grade 重置年级
     */
    public function set_stu_grade() { //设置
        $userid     = $this->get_in_userid();
        $start_time = $this->get_in_start_time_from_str();
        $grade      = $this->get_in_grade();
        $acc = $this->get_account();

        $lesson_confirm_start_time=\App\Helper\Config::get_lesson_confirm_start_time();
        if($acc != "jim" && $acc != "adrian" ) {
            if(!$this->t_order_info->has_1v1_order($userid)) {
                return $this->output_err("有合同了,不能修改年级,找jim处理");
            }else{
                return $this->output_err("没有权限");
            }
        }

        if ( $start_time < $lesson_confirm_start_time && $start_time>0 ) {
            $start_time = $lesson_confirm_start_time;
        }

        $db_grade = $this->t_student_info->get_grade($userid);
        $this->t_student_info->field_update_list($userid,[
            "grade"  => $grade,
        ]);

        // 记录操作日志
        $this->t_user_log->add_data('修改年级为'.E\Egrade::get_desc($grade), $userid);

        //设置时间再重置课程年级,避免影响老师工资
        if($start_time>0){
            $this->t_lesson_info->update_grade_by_userid($userid,$start_time,$grade);
        }
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
                                $teacherid,$userid,$lesson_count/100,$lesson_start,0,$lesson_end
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
                            $acc= $this->get_account();
                            if(!in_array($acc,["jim"])){
                                $ret1 = $this->add_regular_lesson($courseid,$lesson_start,$lesson_end,$lesson_count);
                                if(is_numeric($ret1) ){
                                    // return $this->output_succ(["lessonid" => $ret ]);
                                    //  return $this->output_succ();
                                }else{
                                    return $ret1;
                                }
                            }else{

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

                                $ret1 = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);
                                if ($ret1) {
                                    $this->t_lesson_info->field_update_list($lessonid,[
                                        "lesson_count" => $lesson_count,
                                        "operate_time" => time(),
                                        "sys_operator" => $this->get_account()
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
        if($end_time_done < time(NULL)){
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
                            $lesson_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                            $lesson_end = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$end);

                            $acc= $this->get_account();
                            if(!in_array($acc,["jim"])){
                                $ret1 = $this->add_regular_lesson($courseid,$lesson_start,$lesson_end,$lesson_count);
                                if(is_numeric($ret1) ){
                                    // return $this->output_succ(["lessonid" => $ret ]);
                                    // return $this->output_succ();
                                    /* $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                                    if($old_lesson_total >= $data){
                                        return outputJson(array('ret' => -1, 'info' => "无课可排"));
                                    }else{
                                        return json_encode($data);
                                        }*/

                                }else{
                                    return $ret1;
                                }
                            }else{
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

                                //$lesson_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                                //$lesson_end = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$end);
                                $check =  $this->research_fulltime_teacher_lesson_plan_limit($teacherid,$userid,$lesson_count/100,$lesson_start,0,$lesson_end);
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

                                $ret1 = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);

                                if ($ret1) {
                                    $this->t_lesson_info->field_update_list($lessonid,[
                                        "lesson_count" => $lesson_count,
                                        "operate_time" => time(),
                                        "sys_operator" => $this->get_account()
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
        if($end_time_done < time(NULL)){
            return $this->output_err("该周课程已结束!");
        }
        if($st_time < time(NULL) && $en_time > time(NULL)){
            $st_time = time(NULL);
        }
        $lesson_start_time = $this->get_in_str_val('lesson_start');
        if(!empty($lesson_start_time)){
            $lesson_start_time = json_decode($lesson_start_time,true);
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

                            $lesson_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                            $lesson_end = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$end);

                            //使用新方法排课
                            $acc= $this->get_account();
                            if(!in_array($acc,["jim"])){
                                $ret1 = $this->add_regular_lesson($courseid,$lesson_start,$lesson_end,$lesson_count);
                                if(is_numeric($ret1) ){
                                    // return $this->output_succ(["lessonid" => $ret ]);
                                    // return $this->output_succ();
                                    /* $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                                       if($old_lesson_total >= $data){
                                       return outputJson(array('ret' => -1, 'info' => "无课可排"));
                                       }else{
                                       return json_encode($data);
                                       }*/

                                }else{
                                    return $ret1;
                                }
                            }else{
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

                                //$lesson_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                                // $lesson_end = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$end);
                                $check =  $this->research_fulltime_teacher_lesson_plan_limit($teacherid,$userid,$lesson_count/100,$lesson_start,0,$lesson_end);
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

                                $ret1 = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);

                                if ($ret1) {
                                    $this->t_lesson_info->field_update_list($lessonid,[
                                        "lesson_count" => $lesson_count,
                                        "operate_time" => time(),
                                        "sys_operator" => $this->get_account()
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

    public function regular_lesson_plan_count_winter(){
        $userid           = $this->get_in_int_val('userid');
        $old_lesson_total = $this->get_in_str_val('old_lesson_total');
        $start_time       = $this->get_in_str_val('start_time');
        $end_time         = $this->get_in_str_val('end_time');
        $start_time_done  = strtotime($start_time." 00:00:00");
        $end_time_done    = strtotime($end_time." 23:59:59");

        $st_time = strtotime($start_time);
        $en_time = strtotime($end_time);
        if($end_time_done < time(NULL)){
            return $this->output_err("该周课程已结束!");
        }
        if($st_time < time(NULL) && $en_time > time(NULL)){
            $st_time = time(NULL);
        }
        $lesson_start_time = $this->get_in_str_val('lesson_start');
        if(!empty($lesson_start_time)){
            $lesson_start_time = json_decode($lesson_start_time,true);
        }else{
            $lesson_start_time = [];
        }
        $arr_week = [1=>"一",2=>"二",3=>"三",4=>"四",5=>"五",6=>"六",7=>"日"];
        $ret = $this->t_winter_week_regular_course->get_lesson_info(-1,$userid);
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

                            $lesson_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                            $lesson_end = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$end);

                            //使用新方法排课
                            $acc= $this->get_account();
                            if(!in_array($acc,["jim"])){
                                $ret1 = $this->add_regular_lesson($courseid,$lesson_start,$lesson_end,$lesson_count);
                                if(is_numeric($ret1) ){
                                    // return $this->output_succ(["lessonid" => $ret ]);
                                    // return $this->output_succ();
                                    /* $data = $this->t_lesson_info->get_lesson_info_ass_all($start_time_done,$end_time_done,$userid);
                                       if($old_lesson_total >= $data){
                                       return outputJson(array('ret' => -1, 'info' => "无课可排"));
                                       }else{
                                       return json_encode($data);
                                       }*/

                                }else{
                                    return $ret1;
                                }
                            }else{
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

                                //$lesson_start = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$start);
                                // $lesson_end = strtotime(date('Y-m-d',(strtotime($start_time)+($week-1)*86400))." ".$end);
                                $check =  $this->research_fulltime_teacher_lesson_plan_limit($teacherid,$userid,$lesson_count/100,$lesson_start,0,$lesson_end);
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

                                $ret1 = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);

                                if ($ret1) {
                                    $this->t_lesson_info->field_update_list($lessonid,[
                                        "lesson_count" => $lesson_count,
                                        "operate_time" => time(),
                                        "sys_operator" => $this->get_account()
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
        $page_info = $this->get_in_page_info();
        $list = $this->t_student_info->get_all_stu_info($page_info);
        foreach($list["list"] as &$item){
            E\Estudent_type::set_item_value_str($item,"type");
        }

        return $this->pageView(__METHOD__,$list);

        dd($list);
        //$this->switch_tongji_database();
        $start_time = strtotime("2017-10-01");
        $end_time = strtotime("2017-11-01");

        $lesson_consume    = $this->t_lesson_info->get_total_consume_by_grade($start_time,$end_time);
        dd($lesson_consume);
        $month_start_grade_info = $this->t_cr_week_month_info->get_data_by_type($end_time,1);
        $month_start_grade_str = @$month_start_grade_info["grade_stu_list"];
        $grade_arr = json_decode($month_start_grade_str,true);
        dd($grade_arr);

        $ret_id = $this->t_cr_week_month_info->get_info_by_type_and_time(1,$end_time);
        $finish_num = $this->t_cr_week_month_info->get_finish_num($ret_id);
        $read_num = $this->t_cr_week_month_info->get_read_num($ret_id);


        $ret = $this->t_student_info->get_read_num_by_grade();
        $arrr=[];
        foreach($ret as $k=>$val){
            $arrr[$k]=$val["num"];
        }
        $str = json_encode($arrr);
        /*新增数据*/
        $arr=[];
        $cr_order_info = $this->t_order_info->get_all_cr_order_info($start_time,$end_time);
        $arr["average_person_effect"] = !empty(@$cr_order_info["ass_num"])?round($cr_order_info["all_money"]/$cr_order_info["ass_num"]):0; //平均人效(非入职完整月)

        $all_pay = $this->t_student_info->get_student_list_for_finance_count();//所有有效合同数
        $refund_info = $this->t_order_refund->get_refund_userid_by_month(-1,$end_time);//所有退费信息
        $arr["cumulative_refund_rate"] = round(@$refund_info["orderid_count"]/$all_pay["orderid_count"]*100,2)*100;//合同累计退费率

        // 获取停课,休学,假期数
        $ret_info_stu = $this->t_student_info->get_student_count_archive();

        foreach($ret_info_stu as $item) {
            if ($item['type'] == 2) {
                @$arr['stop_student']++;
            } else if ($item['type'] == 3) {
                @$arr['drop_student']++;
            } else if ($item['type'] == 4) {
                @$arr['summer_winter_stop_student']++;
            }
        }

        //新签合同未排量(已分配/未分配)/新签学生数
        $user_order_list = $this->t_order_info->get_order_user_list_by_month($end_time);
        $new_user = [];//上月新签

        foreach ( $user_order_list as $item ) {
            if ($item['order_time'] >= $start_time ){
                $new_user[] = $item['userid'];
                if (!$item['start_time'] && $item['assistantid'] > 0) {//新签订单,未排课,已分配助教
                    @$arr['new_order_assign_num']++;
                } else if (!$item['start_time'] && !$item['assistantid']) {//新签订单,未排课,未分配助教
                    @$arr['new_order_unassign_num']++;
                }
            }

        }

        $new_user = array_unique($new_user);
        $arr['new_student_num'] = count($new_user);//新签学生数

        //结课率
        $arr["all_registered_student"] = $finish_num+$read_num+$arr["stop_student"]+$arr["drop_student"]+$arr["summer_winter_stop_student"];
        $arr["student_end_per"] = round($finish_num/$arr["all_registered_student"]*100,2)*100;

        //课时消耗目标数量
        $last_year_start = strtotime("-1 years",$start_time);
        $last_year_end = strtotime("+1 months",$last_year_start);


        $insert_data = [

            "average_person_effect"   => $arr["average_person_effect"],  //平均人效(非入职完整月)
            "cumulative_refund_rate"  => $arr["cumulative_refund_rate"], //合同累计退费率
            "stop_student"            => $arr["stop_student"],      //停课学生
            "drop_student"            => $arr["drop_student"],    //休学学员
            "summer_winter_stop_student" =>$arr["summer_winter_stop_student"],  //寒暑假停课学生
            "new_order_assign_num"    => $arr["new_order_assign_num"],  //新签合同未排量(已分配)
            "new_order_unassign_num"  => $arr["new_order_unassign_num"], //新签合同未排量(未分配)
            "student_end_per"         => $arr["student_end_per"],   //结课率
            "new_student_num"         => $arr["new_student_num"],   //本月新签学生数
            "grade_stu_list"          => $str

        ];


        if($ret_id>0){
            $this->t_cr_week_month_info->field_update_list($ret_id,$insert_data);
        }else{
            $this->t_cr_week_month_info->row_insert($insert_data);
        }


        dd($str);

        $tt = strtotime("-1 years",$time);
        dd(date("Y-m-d H:i:s",$tt));

        $list = $this->t_student_info->get_ass_create_stu_info();
        dd($list);




    }




    public function get_admin_wx_info() {
        $account= $this->get_account();
        $ret=$this->t_manager_info->get_info_by_account($account,"wx_id,name,phone");
        return $this->output_succ(["data" => $ret]);
    }

    public function send_seller_sms_msg() {
        $phone         = $this->get_in_phone_ex();
        $name          = $this->get_in_str_val("name");
        $wx_id         = $this->get_in_str_val("wx_id");
        $seller_phone  = $this->get_in_str_val("seller_phone");
        $template_code = 15960017 ;

        $userid            = $this->t_seller_student_new->get_userid_by_phone($phone);
        $admin_revisiterid = $this->t_seller_student_new->get_admin_revisiterid($userid);
        if ($admin_revisiterid != $this->get_account_id() ) {
            return $this->output_err("这个例子还不是你的,不能发短信");
        }

        $ret = (new notice())->sms_common( $phone,0,$template_code,[
            "name"  => $name,
            "wx_id" => $wx_id,
            "phone" => $seller_phone,
        ]);
        return $this->output_bool_ret($ret);
    }

    public function get_major_group_list ()
    {
        $main_type    = $this->get_in_int_val("main_type");
        $page_num     = $this->get_in_page_num();

        // $ret_info     = $this->t_admin_group_name->get_group_list_new($page_num,$main_type);// old
        $ret_info     = $this->t_admin_main_group_name->get_main_group_list($page_num,$main_type);
        foreach($ret_info['list'] as &$item){
            $item['group_master_nick']= $this->cache_get_account_nick($item['master_adminid']);
        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return outputjson_success(array('data' => $ret_info));

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

    public function get_group_list_campus ()
    {
        $page_num     = $this->get_in_page_num();
        $ret_info   = $this->t_admin_main_group_name->get_group_list_campus($page_num);
        foreach($ret_info['list'] as &$item){
            $item['group_master_nick']= $this->cache_get_account_nick($item['master_adminid']);
            $item["main_type_str"] = E\Emain_type::get_desc($item["main_type"]);
        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return outputjson_success(array('data' => $ret_info));

    }

    public function get_main_group_list_new_month()
    {
        $main_type    = $this->get_in_int_val("main_type");
        $page_num     = $this->get_in_page_num();
        $month        = strtotime($this->get_in_str_val("start_time"));
        $ret_info   = $this->t_main_group_name_month->get_group_list_new($page_num,$main_type,$month);
        // $ret_info   = $this->t_group_name_month->get_group_list_new($page_num,$main_type,$month);
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

    public function set_main_groupid(){
        $groupid          = $this->get_in_int_val("groupid");
        $first_groupid    = $this->get_in_int_val("first_groupid");
        $this->t_admin_main_group_name->field_update_list($groupid,['up_groupid'=>$first_groupid]);
        return $this->output_succ();
    }

    public function set_first_groupid_new(){
        $groupid    = $this->get_in_int_val("groupid");
        $up_groupid    = $this->get_in_int_val("first_groupid");
        $month        = strtotime($this->get_in_str_val("start_time"));
        $this->t_main_group_name_month->field_update_list_2($groupid,$month,['up_groupid'=>$up_groupid]);
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
        $groupid     = $this->get_in_int_val("groupid");
        $month_money = $this->get_in_int_val("month_money");
        $month       = $this->get_in_str_val("month");
        $ret = $this->t_admin_group_month_time->field_get_list_2($groupid, $month,"groupid");
        // dd($ret);
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

        // 添加操作日志
        $this->t_user_log->add_data("修改账号,账号:".$phone, $userid);

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

    public function get_xmpp_server_list_js()  {
        $page_num=$this->get_in_page_num();
        $server_name= $this->get_in_str_val("server_name");

        $ret_list=$this->t_xmpp_server_config->get_list($page_num, $server_name);
        $check_time=time(NULL)-60;

        foreach($ret_list["list"] as &$item) {
            //$item["status_class"]= ($item["last_active_time"] <$check_time)?"danger":"";
            //\App\Helper\Utils::unixtime2date_for_item($item,"last_active_time");
        }
        return $this->output_ajax_table($ret_list);
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
        $renew_target   = $this->get_in_str_val("renew_target");
        $group_renew_target   = $this->get_in_str_val("group_renew_target");
        $all_renew_target   = $this->get_in_str_val("all_renew_target");

        $res = $this->t_ass_group_target->field_get_list($month,"month");
        $old_arr = $this->t_ass_group_target->field_get_list($month,"rate_target,renew_target,group_renew_target,all_renew_target,change_log");
        $new_arr =[
            "rate_target"=>$lesson_target,
            "renew_target"=>$renew_target,
            "group_renew_target"=>$group_renew_target,
            "all_renew_target"=>$all_renew_target,
        ];
        $change_log = json_decode($old_arr["change_log"],true);
        $change_log[] =[
            "time" =>time(),
            "acc"  =>$this->get_account(),
            "old"  =>$old_arr,
            "new"  =>$new_arr
        ];
        $log_new = json_encode($change_log);

        if($res){
            $ret = $this->t_ass_group_target->field_update_list($month,[
                "rate_target"=>$lesson_target,
                "renew_target"=>$renew_target,
                "group_renew_target"=>$group_renew_target,
                "all_renew_target"=>$all_renew_target
            ]);

        }else{
           $ret= $this->t_ass_group_target->row_insert([
                "rate_target"=>$lesson_target,
                "month"=>$month,
                "renew_target"=>$renew_target,
                "group_renew_target"=>$group_renew_target,
                "all_renew_target"=>$all_renew_target
            ]);
        }
        if($ret){
            $this->t_ass_group_target->field_update_list($month,[
                "change_log"      =>$log_new
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
        $time = time();
        $this->t_revisit_info->field_update_list_2($userid,$revisit_time,[
            "warning_deal_url" =>$warning_deal_url,
            "warning_deal_info" => $warning_deal_info,
            "warning_deal_time" => $time,
            "is_warning_flag" => $is_warning_flag
        ]);

        if($is_warning_flag == 2) {
            //如果状态改为‘已解决’,查询预警超时表，同步修改对应状态
            $ret = $this->t_revisit_warning_overtime_info->get_overtime_info($userid, $revisit_time);
            if (count($ret) > 0){
                if ($ret['deal_type'] == 0) {
                    $add_month  = date('Y-m-1', $ret['create_time']);
                    $deal_month = date('Y-m-1', $time);
                    $deal_type  = ($add_month === $deal_month)? 1: 2;
                    $this->t_revisit_warning_overtime_info->field_update_list($ret['overtime_id'],[
                        "deal_time" => $time,
                        "deal_type" => $deal_type,
                    ]);
                }

            }

        }
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

        $group_kpi['group_kpi'] = '';
        $group_kpi['group_kpi_desc'] = '';

        switch ( $month ) {
        case "201702" :
        case "201703" :
        case "201704" :
            $group_kpi = \App\Strategy\groupMasterKpi\group_master_kpi_base::get_cur_info($adminid, $start_time, $end_time);
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_info_by_type(
                 $month, $adminid, $start_time, $end_time ) ;
            break;
        case "201705" :
        case "201706" :
        case "201707" :
        case "201708" :
            $group_kpi = \App\Strategy\groupMasterKpi\group_master_kpi_base::get_cur_info($adminid, $start_time, $end_time);
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_info_by_type(
                "201705", $adminid, $start_time, $end_time ) ;
            break;
        case "201709" :
            $group_kpi = \App\Strategy\groupMasterKpi\group_master_kpi_base::get_cur_info($adminid, $start_time, $end_time);
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_info_by_type(
                "201709", $adminid, $start_time, $end_time );
            break;
        case "201710" :
            $group_kpi = \App\Strategy\groupMasterKpi\group_master_kpi_base::get_cur_info($adminid, $start_time, $end_time);
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_info_by_type(
                "201710", $adminid, $start_time, $end_time );
            break;
        case "201711" :
            $group_kpi = \App\Strategy\groupMasterKpi\group_master_kpi_base::get_cur_info($adminid, $start_time, $end_time);
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_info_by_type(
                "201711", $adminid, $start_time, $end_time );
            break;
        case "201712" :
            $group_kpi = \App\Strategy\groupMasterKpi\group_master_kpi_base::get_cur_info($adminid, $start_time, $end_time);
            break;
        default:
            $group_kpi = \App\Strategy\groupMasterKpi\group_master_kpi_base::get_info_by_type("201801",$adminid,$start_time, $end_time);
            $arr=\App\Strategy\sellerOrderMoney\seller_order_money_base::get_cur_info(
                $adminid, $start_time, $end_time ) ;
            break;
        }
        //试听成功数
        list($res[$adminid][E\Eweek_order::V_1],$res[$adminid][E\Eweek_order::V_2],$res[$adminid][E\Eweek_order::V_3],$res[$adminid][E\Eweek_order::V_4],$res[$adminid]['lesson_per'],$res[$adminid]['kpi'],$res[$adminid]['suc_all_count'],$res[$adminid]['dis_suc_all_count'],$res[$adminid]['fail_all_count'],$res[$adminid]['test_lesson_count']) = [[],[],[],[],0,0,0,0,0,0];
        list($start_time_new,$end_time_new)= $this->get_in_date_range_month(date("Y-m-01"));
        if($end_time_new >= time()){
            $end_time_new = time();
        }
        $ret_new = $this->t_month_def_type->get_month_week_time($start_time_new);
        $test_leeson_list_new = $this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new_three($start_time_new,$end_time_new,$grade_list=[-1] , $origin_ex="",$adminid);
        foreach($test_leeson_list_new['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $lesson_start = $item['lesson_start'];
            foreach($ret_new as $info){
                $start = $info['start_time'];
                $end = $info['end_time'];
                $week_order = $info['week_order'];
               if($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_1){
                    $res[$adminid][$week_order][] = $item;
                }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_2){
                    $res[$adminid][$week_order][] = $item;
                }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_3){
                    $res[$adminid][$week_order][] = $item;
                }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_4){
                    $res[$adminid][$week_order][] = $item;
                }
            }
        }
        foreach($res as $key=>$item){
            $res[$key]['suc_lesson_count_one'] = isset($item[E\Eweek_order::V_1])?count($item[E\Eweek_order::V_1]):0;
            $res[$key]['suc_lesson_count_two'] = isset($item[E\Eweek_order::V_2])?count($item[E\Eweek_order::V_2]):0;
            $res[$key]['suc_lesson_count_three'] = isset($item[E\Eweek_order::V_3])?count($item[E\Eweek_order::V_3]):0;
            $res[$key]['suc_lesson_count_four'] = isset($item[E\Eweek_order::V_4])?count($item[E\Eweek_order::V_4]):0;
            $res[$key]['suc_lesson_count_one_rate'] = $res[$key]['suc_lesson_count_one']<12?0:15;
            $res[$key]['suc_lesson_count_two_rate'] = $res[$key]['suc_lesson_count_two']<12?0:15;
            $res[$key]['suc_lesson_count_three_rate'] = $res[$key]['suc_lesson_count_three']<12?0:15;
            $res[$key]['suc_lesson_count_four_rate'] = $res[$key]['suc_lesson_count_four']<12?0:15;
            $suc_lesson_count_rate = $res[$key]['suc_lesson_count_one_rate']+$res[$key]['suc_lesson_count_two_rate']+$res[$key]['suc_lesson_count_three_rate']+$res[$key]['suc_lesson_count_four_rate'];
            $res[$key]['suc_lesson_count_rate'] = $suc_lesson_count_rate.'%';
            $res[$key]['suc_lesson_count_rate_all'] = $suc_lesson_count_rate;
        }
        if($end_time >= time()){
            $end_time = time();
        }
        $test_leeson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new($start_time,$end_time,$grade_list=[-1] , $origin_ex="",$adminid);
        foreach($test_leeson_list['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['suc_all_count']=$item['succ_all_count'];
            $res[$adminid]['dis_suc_all_count']=$item['dis_succ_all_count'];
            $res[$adminid]['fail_all_count'] = $item['fail_all_count'];
            $res[$adminid]['test_lesson_count'] = $item['test_lesson_count'];
        }
        $lesson_per = $res[$adminid]['test_lesson_count']!=0?(round($res[$adminid]['fail_all_count']/$res[$adminid]['test_lesson_count'],2)*100):0;
        $res[$adminid]['lesson_per'] = $lesson_per>0?$lesson_per."%":0;
        $res[$adminid]['lesson_per_desc'] = $res[$adminid]['test_lesson_count']!=0?$res[$adminid]['fail_all_count'].'÷'.$res[$adminid]['test_lesson_count']:0;
        $res[$adminid]['lesson_kpi'] = $lesson_per<=18?40:0;
        $kpi = $res[$adminid]['lesson_kpi']+$res[$adminid]['suc_lesson_count_rate_all'];
        $res[$adminid]['kpi'] = ($kpi && $res[$adminid]['test_lesson_count']>0)>0?$kpi."%":0;
        $manager_info = $this->t_manager_info->field_get_list($adminid,'become_member_time,del_flag,leave_member_time');
        // if($manager_info["become_member_time"]>0 && ($end_time-$manager_info["become_member_time"])<3600*24*60 && $manager_info["del_flag"]==0){
        if($manager_info["become_member_time"]>0 && ($end_time-$manager_info["become_member_time"])<3600*24*60){
            $res[$adminid]['kpi'] = "100%";
        }
        $arr['suc_first_week'] = $res[$adminid]['suc_lesson_count_one'];
        $arr['suc_second_week'] = $res[$adminid]['suc_lesson_count_two'];
        $arr['suc_third_week'] = $res[$adminid]['suc_lesson_count_three'];
        $arr['suc_fourth_week'] = $res[$adminid]['suc_lesson_count_four'];
        $arr['test_lesson_count'] = $res[$adminid]['test_lesson_count'];
        $arr['lesson_per'] = $res[$adminid]['lesson_per_desc'].'='.$res[$adminid]['lesson_per'];
        $arr['kpi'] = $res[$adminid]['kpi'];
        $arr['cur_del_flag_str'] = '否';
        if($manager_info["del_flag"] == 1 && $manager_info["leave_member_time"]<$end_time_new){
            $arr['cur_del_flag_str'] = '是';
        }
        $arr['suc_all_count'] = $res[$adminid]['suc_all_count'];
        $arr['dis_suc_all_count'] = $res[$adminid]['dis_suc_all_count'];
        $arr['fail_all_count'] = $res[$adminid]['fail_all_count'];
        $arr['test_lesson_count'] = $res[$adminid]['test_lesson_count'];
        //月末定级
        $start_time_this = $start_time;
        $ret_time = $this->t_month_def_type->get_all_list();
        foreach($ret_time as $item){//本月
            if($start_time_new>=$item['start_time'] && $start_time_new<$item['end_time']){
                $start_time_this = $item['def_time'];
                break;
            }
        }
        $last_seller_level = $this->t_seller_level_month->get_row_by_adminid_month_date($adminid,$start_time_this);
        $arr['last_seller_level'] = isset($last_seller_level['seller_level'])?E\Eseller_salary_level::get_desc($last_seller_level['seller_level']):'';
        $arr['base_salary'] = isset($last_seller_level['base_salary'])?$last_seller_level['base_salary']:'';
        $arr['sup_salary'] = isset($last_seller_level['sup_salary'])?$last_seller_level['sup_salary']:'';
        $arr['per_salary'] = isset($last_seller_level['per_salary'])?$last_seller_level['per_salary']:'';
        //上月非退费签单金额
        $account = $this->t_manager_info->get_account_by_uid($adminid);
        $timestamp = strtotime(date("Y-m-01",$start_time));
        $firstday_last  = date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
        $lastday_last   = date('Y-m-d',strtotime("$firstday_last +1 month -1 day"));
        list($start_time_last,$end_time_last)= [strtotime($firstday_last),strtotime($lastday_last)];
        foreach($ret_time as $item){//上月
            if($start_time_this-1>=$item['start_time'] && $start_time_this-1<$item['end_time']){
                $start_time_last = $item['start_time'];
                $end_time_last = $item['end_time'];
            }
        }
        $last_all_price = $this->t_order_info->get_1v1_order_seller_month_money_new($account,$start_time_last,$end_time_last);
        $last_all_price = isset($last_all_price)?$last_all_price/100:0;
        $last_refund_list = $this->t_order_info->get_refund_month_money($account,$start_time_last,$end_time_last);
        foreach($last_refund_list as $item){
            if($item['real_refund']>0){
                $last_all_price += ($item['price']-$item['real_refund'])/100;
            }
        }
        $arr['last_all_price'] = $last_all_price;
        //上月团队金额
        $last_group_list = $this->t_order_info->month_get_1v1_order_seller_list_group($start_time_last,$end_time_last,$adminid);
        $last_group_all_price=0;
        if(count($last_group_list) ==1){
            $last_group_all_price = $last_group_list[0]["all_price"];
        }
        $arr["last_group_all_price"] = $last_group_all_price/100;

        $no_update_seller_level_flag = $this->t_manager_info->field_get_value($adminid,'no_update_seller_level_flag');
        $master_groupid = $this->t_group_name_month->get_groupid_by_adminid($adminid, $start_time_this);
        // if($no_update_seller_level_flag == 1){
        if($master_groupid>0){
            $arr['base_salary'] = 6500;
            $arr['sup_salary'] = 0;
            switch(true){
            case $arr['last_group_all_price']<500000:
                // $arr['per_salary'] = 10*$kpi;
                $arr['per_salary'] = 1000;
                $arr['last_seller_level'] = '初级';
                break;
            case $arr['last_group_all_price']<800000 && $arr['last_group_all_price']>=500000:
                // $arr['per_salary'] = 25*$kpi;
                $arr['per_salary'] = 2500;
                $arr['last_seller_level'] = '中级1';
                break;
            case $arr['last_group_all_price']<1000000 && $arr['last_group_all_price']>=800000:
                // $arr['per_salary'] = 35*$kpi;
                $arr['per_salary'] = 3500;
                $arr['last_seller_level'] = '中级2';
                break;
            default:
                // $arr['per_salary'] = 50*$kpi;
                $arr['per_salary'] = 5000;
                $arr['last_seller_level'] = '高级';
                break;
            }
            $arr['suc_first_week'] = '';
            $arr['suc_second_week'] = '';
            $arr['suc_third_week'] = '';
            $arr['suc_fourth_week'] = '';
            $arr['lesson_per'] = '';
            $arr['lesson_per_desc'] = '';
            $arr['kpi'] = '';
        }
        $arr['group_kpi'] = isset($group_kpi['group_kpi'])?$group_kpi['group_kpi']:'';
        $arr['group_kpi_desc'] = isset($group_kpi['group_kpi_desc'])?$group_kpi['group_kpi_desc']:'';

        $arr['get_per_salary'] = $arr['group_kpi']>0?($arr['per_salary']*str_replace('%','',$arr['group_kpi'])/100):($arr['per_salary']*str_replace('%','',$arr['kpi'])/100);
        $arr['group_month_avg_lesson'] = isset($group_kpi['group_month_avg_lesson'])?$group_kpi['group_month_avg_lesson']:'';
        $arr['group_month_avg_lesson_per'] = isset($group_kpi['group_month_avg_lesson_per'])?$group_kpi['group_month_avg_lesson_per']:'';
        $arr['group_month_avg_order_per'] = isset($group_kpi['group_month_avg_order_per'])?$group_kpi['group_month_avg_order_per']:'';
        $arr['group_month_avg_leave_per'] = isset($group_kpi['group_month_avg_leave_per'])?$group_kpi['group_month_avg_leave_per']:'';

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
        $lessonid = $this->get_in_lessonid();
        $reason   = $this->get_in_str_val("reason");

        $flow_type=E\Eflow_type::V_ASS_LESSON_CONFIRM_FLAG_4 ;
        if (!$this->t_flow->get_info_by_key($flow_type,$lessonid)) {
            $this->t_flow->add_flow(
                E\Eflow_type::V_ASS_LESSON_CONFIRM_FLAG_4 ,
                $this->get_account_id(),$reason, $lessonid
            );
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
        /* if($num>=15){
            return $this->output_err("本月申请次数已达上限".$num."次");
            }*/
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
                $account_role = $this->t_manager_info->get_account_role($adminid);
                $account_role_str = E\Eaccount_role::get_desc ($account_role);
                $this->t_change_teacher_list->field_update_list($id,["wx_send_time"=>time()]);
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"推荐老师","推荐老师申请",$account_role_str."-".$account."老师申请推荐老师,请尽快处理","http://admin.leo1v1.com/tea_manage_new/get_seller_require_commend_teacher_info?id=".$id);
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
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($ass_adminid,"理优教育","推荐老师申请反馈","您为学生".$nick."的推荐老师申请已处理完成,点击查看详情","http://admin.leo1v1.com/tea_manage_new/get_seller_require_commend_teacher_info_seller?id=".$id);
            }else{
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($ass_adminid,"理优教育","推荐老师申请反馈","您为学生".$nick."的推荐老师申请已被驳回,理由如下:
".$accept_reason.",点击查看详情","http://admin.leo1v1.com/tea_manage_new/get_seller_require_commend_teacher_info_seller?id=".$id);
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
        if(($change_teacher_reason_type==0) ||empty( $url) || empty( $change_reason) || empty( $except_teacher) || empty( $textbook) || empty( $phone_location) || empty($stu_score_info ) || empty($stu_character_info)){
             return $this->output_err("请填写完整!");
        }

        if(strlen(str_replace(" ","",$change_reason))<9){
            return $this->output_err('换老师原因不得少于3个字!');
        }


        $adminid = $this->get_account_id();
        $ret = $this->t_change_teacher_list->check_is_exist($teacherid,$userid,$subject,$commend_type);
        if($ret>0){
            return $this->output_err("已有该学生该老师的换老师申请!");
        }
        if($subject==2){
            $accept_adminid = 754;
        }elseif($subject==5){
            $accept_adminid = 478;
        }elseif($subject==4){
            $accept_adminid=478;
        }else{
            $accept_adminid = 486;
        }
        //更新处理人

        // if($change_teacher_reason_type == 0){
        //     return $this->output_err('请选择换老师类型!');
        // }


        // $accept_adminid = 478;
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
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","更换老师申请","助教".$account."老师申请更换老师,请尽快协调处理","http://admin.leo1v1.com/user_manage_new/get_ass_change_teacher_info?id=".$id);
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
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($ass_adminid,"理优教育","更换老师申请反馈","您更换".$realname."老师的申请已处理完成,点击查看详情","http://admin.leo1v1.com/user_manage_new/get_ass_change_teacher_info_ass?id=".$id);
            }else{
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($ass_adminid,"理优教育","更换老师申请反馈","您更换".$realname."老师的申请已被驳回,理由如下:
".$accept_reason.",点击查看详情","http://admin.leo1v1.com/user_manage_new/get_ass_change_teacher_info_ass?id=".$id);
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
        $style_character                  = $this->get_in_str_val("style_character");
        $professional_ability             = $this->get_in_str_val("professional_ability");
        $classroom_atmosphere             = $this->get_in_str_val("classroom_atmosphere");
        $courseware_requirements          = $this->get_in_str_val("courseware_requirements");
        $diathesis_cultivation            = $this->get_in_str_val("diathesis_cultivation");

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
            $tea_tag_arr=[
                "style_character"=>$style_character,
                "professional_ability"=>$professional_ability,
                "classroom_atmosphere"=>$classroom_atmosphere,
                "courseware_requirements"=>$courseware_requirements,
                "diathesis_cultivation"=>$diathesis_cultivation,
            ];
            $this->set_teacher_label_new($teacherid,$lessonid,"",$tea_tag_arr,5,1,1);

            $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","教学质量反馈待处理",$account."老师提交了一条教学质量反馈,请尽快处理","http://admin.leo1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);

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
                 $this->t_manager_info->send_wx_todo_msg_by_adminid ($require_adminid,"理优教育","教学质量反馈结果","您提交的教学质量反馈已由".$account."老师处理完毕,请确认是否解决!","http://admin.leo1v1.com/tea_manage_new/get_seller_ass_record_info_seller?id=".$id);

            }elseif($account_role==1){
                 $this->t_manager_info->send_wx_todo_msg_by_adminid ($require_adminid,"理优教育","教学质量反馈结果","您提交的教学质量反馈已由".$account."老师处理完毕,请确认是否解决!","http://admin.leo1v1.com/tea_manage_new/get_seller_ass_record_info_ass?id=".$id);

            }
            $leader_adminid = $this->t_admin_group_user->get_main_master_adminid($adminid);
            if($leader_adminid != $adminid){
                 $this->t_manager_info->send_wx_todo_msg_by_adminid ($leader_adminid,"理优教育","教学质量反馈结果",$require_account."提交的教学质量反馈已由".$account."老师处理完毕,请确认是否解决!","http://admin.leo1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
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
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($require_adminid,"理优教育","投诉老师结果反馈","您提交的老师投诉已由".$account."老师处理完毕,点击查看","http://admin.leo1v1.com/tea_manage_new/get_teacher_complaints_info_jw?id=".$id);
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
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","更换老师申请(再次提交)","助教".$account."老师申请更换老师,请尽快协调处理","http://admin.leo1v1.com/user_manage_new/get_ass_change_teacher_info?id=".$id);

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
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","教学质量反馈处理完成",$account."老师提交的教学质量反馈已处理完成,辛苦了","http://admin.leo1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
                if($leader_adminid != 1){
                    $this->t_manager_info->send_wx_todo_msg_by_adminid ($leader_adminid,"理优教育","教学质量反馈处理完成",$account."老师提交的教学质量反馈已处理完成,辛苦了","http://admin.leo1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
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
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","教学质量反馈(再次提交)",$account."老师重新提交了一条教学质量反馈,请尽快处理","http://admin.leo1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
                if($leader_adminid != 1){
                    $this->t_manager_info->send_wx_todo_msg_by_adminid ($leader_adminid,"理优教育","教学质量反馈(再次提交)",$account."老师重新提交了一条教学质量反馈,将由".$accept_account."老师处理","http://admin.leo1v1.com/tea_manage_new/get_seller_ass_record_info?id=".$id);
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

    public function sync_kaoqin_all() {
        $machine_id=$this->get_in_int_val("machine_id");

        $page_num=null;
        $uid=-1;
        $user_info="";
        $has_question_user=0;
        $creater_adminid=-1;
        $account_role=-1;
        $del_flag=0;
        $cardid=-1;
        $tquin=-1 ;
        $day_new_user_flag=-1;

        $admin_list= $this->t_manager_info->get_all_manager(
$page_num,$uid,$user_info,$has_question_user,$creater_adminid,$account_role,$del_flag,$cardid,$tquin ,$day_new_user_flag);
        foreach($admin_list["list"] as $item ) {
            $adminid=$item["uid"];
            $this->t_manager_info->sync_kaoqin_user($adminid,$machine_id);
        }
        return $this->output_succ();
    }

    public function copy_admin_group_info(){
        $month = strtotime($this->get_in_str_val("start_time"));
        $main_type_flag = $this->get_in_int_val("main_type_flag");

        $this->t_group_user_month->del_by_month($month,$main_type_flag);
        $this->t_group_name_month->del_by_month($month,$main_type_flag);
        $this->t_main_group_name_month->del_by_month($month,$main_type_flag);
        $this->t_main_major_group_name_month->del_by_month($month,$main_type_flag);
        $admin_group_name_list = $this->t_admin_group_name->get_all_list($main_type_flag);
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

        $admin_group_user_list = $this->t_admin_group_user->get_all_list($main_type_flag);
        foreach($admin_group_user_list as $item){
            $this->t_group_user_month->row_insert([
                "groupid"    =>$item["groupid"],
                "month"      =>$month,
                "adminid"  =>$item["adminid"],
                "assign_percent" =>$item["assign_percent"]
            ]);
        }

        $admin_main_group_name_list = $this->t_admin_main_group_name->get_all_list($main_type_flag);
        foreach($admin_main_group_name_list as $item){
            $this->t_main_group_name_month->row_insert([
                "groupid"    =>$item["groupid"],
                "month"      =>$month,
                "main_type"  =>$item["main_type"],
                "group_name" =>$item["group_name"],
                "master_adminid"=>$item["master_adminid"],
                "main_assign_percent" =>$item["main_assign_percent"],
                "up_groupid"    =>$item["up_groupid"]
            ]);
        }

        $major_admin_list = $this->t_admin_majordomo_group_name->get_all_list($main_type_flag);
        foreach($major_admin_list as $k=>$item){
            $this->t_main_major_group_name_month->row_insert([
                "groupid"  =>$item["groupid"],
                "month"    =>$month,
                "main_type"=>$item["main_type"],
                "group_name"=>$item["group_name"],
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
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($accept_adminid,"理优教育","老师投诉待处理",$account."老师提交了一条老师投诉,请尽快处理","http://admin.leo1v1.com/tea_manage_new/get_teacher_complaints_info?id=".$id);

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
            $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"转正申请通知","转正申请通知",$name."老师的已提交转正申请,请审核!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请通知","转正申请通知",$name."老师的已提交转正申请,请审核!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);


        }
        return $this->output_succ();
    }

    public function fulltime_teacher_positive_require_deal_master(){
        $id   = $this->get_in_int_val("id");
        $master_deal_flag  = $this->get_in_int_val("master_deal_flag");
        $base_money= $this->get_in_int_val("base_money");

        $this->t_fulltime_teacher_positive_require_list->field_update_list($id,[
            "master_deal_flag"   => $master_deal_flag,
            "mater_adminid"      => $this->get_account_id(),
            "master_assess_time" => time(),
            "base_money"         => $base_money*100
        ]);
        $adminid = $this->t_fulltime_teacher_positive_require_list->get_adminid($id);
        $name = $this->t_manager_info->get_name($adminid);

        if($master_deal_flag==2){

            $this->t_manager_info->send_wx_todo_msg_by_adminid ($adminid,"转正申请驳回","转正申请驳回通知",$name."老师,您的转正申请经主管审核,已经被驳回!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请驳回","转正申请驳回通知",$name."老师,您的转正申请经主管审核,已经被驳回!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);

        }elseif($master_deal_flag==1){
            $this->t_manager_info->send_wx_todo_msg_by_adminid (72,"转正申请提交","转正申请提交",$name."老师的转正申请经主管审核已同意,请审核!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info_master?adminid=".$adminid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请提交","转正申请提交",$name."老师的转正申请经主管审核已同意,请审核!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info_master?adminid=".$adminid);
        }
        return $this->output_succ();
    }

    public function fulltime_teacher_positive_require_deal_main_master(){
        $id   = $this->get_in_int_val("id");
        $main_master_deal_flag  = $this->get_in_int_val("master_deal_flag");
        $base_money= $this->get_in_int_val("base_money");

        $this->t_fulltime_teacher_positive_require_list->field_update_list($id,[
            "main_master_deal_flag"   => $main_master_deal_flag,
            "main_mater_adminid"      => $this->get_account_id(),
            "main_master_assess_time" => time(),
            "base_money"              => $base_money*100,
        ]);

        $positive_type = $this->t_fulltime_teacher_positive_require_list->get_positive_type($id);
        $positive_time = $this->t_fulltime_teacher_positive_require_list->get_positive_time($id);
        $adminid = $this->t_fulltime_teacher_positive_require_list->get_adminid($id);
        $name    = $this->t_manager_info->get_name($adminid);
        $email   = $this->t_manager_info->get_email($adminid);

        if($positive_type==3){
            if($main_master_deal_flag==1){
                //微信通知主管和老师
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($adminid,"延期转正申请通过","延期转正申请通过通知",$name."老师,您的延期转正申请经主管和总监审核,已经通过","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"延期转正申请通过","延期转正申请通过通知",$name."老师,您的延期转正申请经主管和总监审核,已经通过","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"延期转正申请通过","延期转正申请通过通知",$name."老师的延期转正申请经总监审核,已经通过!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"延期转正申请通过","延期转正申请通过通知",$name."老师的延期转正申请经总监审核,已经通过!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
            }elseif($main_master_deal_flag==2){
                $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"延期转正申请未通过","延期转正申请驳回通知",$name."老师的延期转正申请经总监审核,已经被驳回,请确认!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"延期转正申请未通过","延期转正申请驳回通知",$name."老师的延期转正申请经总监审核,已经被驳回,请确认!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
            }
        }else{
            if($main_master_deal_flag==1){
                $this->t_manager_info->field_update_list($adminid,[
                    "become_full_member_flag" =>1,
                    "become_full_member_time" =>time()
                ]);

                //修改老师等级
                $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
                $teacherid = $teacher_info["teacherid"];
                $this->t_teacher_info->field_update_list($teacherid,["level"=>E\Elevel::V_1]);

                $this->t_lesson_info_b2->update_teacher_level($teacherid,1);

                //邮件发送给hr
                $send_email = [$email,"sherry@leoedu.com","low-key@leoedu.com","erick@leoedu.com","cindy@leoedu.com"];

                $title = "关于 $name 老师转正申请的批复";
                $date = date("Y-m-d");
                $content = "$name 老师，恭喜您，鉴于您在试用期的表现优秀，您的转正申请已通过了上级领导审批，同意转正。<br>"
                         ."转正日期 : $date <br>"
                         ."目前教师等级:初级<br>"
                         ."转正后教师等级:中级"
                         ."转正后基本工资:$base_money";

                \App\Helper\Email::SendMailJiaoXue($send_email, $title, $content, true, 2);
                // \App\Helper\Common::send_mail_leo_com($send_email,$title,$content,true);
                // foreach($email as $e){
                //     dispatch( new \App\Jobs\SendEmailNew(
                //         $e,"全职老师转正通知","Dear all：<br>  ".$name."老师转正考核已通过,请调整该老师底薪,谢谢!!"
                //     ));
                // }

                //微信通知主管和老师
                $this->t_manager_info->send_wx_todo_msg_by_adminid ($adminid,"转正申请通过","转正申请通过通知",$name."老师,您的转正申请经主管和总监审核,已经通过,恭喜您!","");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"转正申请通过","转正申请通过通知",$name."老师的转正申请经总监审核,已经通过!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid."become_full_member_flag=1");
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请通过","转正申请通过通知",$name."老师的转正申请经总监审核,已经通过!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid."become_full_member_flag=1");
            }elseif($main_master_deal_flag==2){
                $this->t_manager_info->send_wx_todo_msg_by_adminid (480,"转正申请未通过","转正申请驳回通知",$name."老师的转正申请经总监审核,已经被驳回,请确认!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"转正申请未通过","转正申请驳回通知",$name."老师的转正申请经总监审核,已经被驳回,请确认!","http://admin.leo1v1.com/fulltime_teacher/fulltime_teacher_assessment_positive_info?adminid=".$adminid);
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
        if($account_role != E\Eaccount_role::V_4){
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
                $wx  = new \App\Helper\Wx();
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
        $this->t_seller_student_new->field_update_list($userid,['hold_flag'=>0]);
        //记录数据
        $phone = $this->t_student_info->get_phone($userid);
        $nick = $this->t_student_info->get_nick($userid);
        $this->t_book_revisit->add_book_revisit(
            $phone,
            $nick."交界单提交(新)",
            "system"
        );

        $account=$this->get_account();
        $this->t_student_info->noti_ass_order($userid, $account );
        return $this->output_succ();
    }



    /**
     1）CR对于交接单，只能驳回给CR组长，不能驳回给CC，驳回前必须添加驳回原因
     2）CR组长审核并追加意见后，可以驳回给CC，也可以退回给CR
     3）CC修改提交后的交接单，原路返回，CR组长审核后，可以再次驳回给CC，或直接下发给CR
     4）一旦学生创建了课程包，取消所有环节的交接单驳回功能
     5）可能有必要增加CR对交接单的确认操作，一旦CR确认，驳回功能取消
     6）如果5）成立，课程包创建的前提条件之一是CR必须确认交接单
     **/
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

        if($is_reject_flag>0){
            if($is_reject_flag == 1){ // 助教组长 驳回cc销售
                // 驳回
                $assistantid         = $this->t_student_info->get_assistantid_by_userid($sid);
                $ass_master_adminid  = $this->t_student_info->get_ass_master_adminid($sid);

                // if($assistantid>0){ // 待删除
                    // return $this->output_err('交接单已分配了助教老师，不能驳回交接单!');
                // }

                $ret = $this->t_student_cc_to_cr->field_update_list($id,[
                    'reject_flag' => $is_reject_flag,
                    'reject_time' => time(NULL),
                    'reject_info' => $reject_info,
                    'ass_id'      => $acc_id
                ]);
                $send_openid = $cc_openid;
                $send_name = "助教组长 $acc_nick";

            }elseif($is_reject_flag==2){ // 助教组长驳回组员
                $ret = $this->t_student_cc_to_cr->field_update_list($id,[
                    'reject_flag' => $is_reject_flag,
                    'reject_time' => time(NULL),
                    'reject_info' => $reject_info,
                    'ass_id'      => $acc_id
                ]);

                $ass_openid = $this->t_student_cc_to_cr->get_ass_openid($id);
                $send_openid = $ass_openid;
                $send_name = "助教助长 $acc_nick";

            }elseif($is_reject_flag==3){ // 助教组员驳回组长
                $ret = $this->t_student_cc_to_cr->field_update_list($id,[
                    'reject_flag' => $is_reject_flag,
                    'reject_time' => time(NULL),
                    'reject_info' => $reject_info,
                    'ass_id'      => $acc_id
                ]);
                $master_openid = $this->t_admin_group_user->get_ass_master_openid($acc_id);
                $send_openid = $master_openid;
                $send_name = "助教 $acc_nick";
            }


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
                    "keyword2"  => "$send_name 驳回交接单, 交接单合同号$orderid,驳回原因:$reject_info",
                    "keyword3"  => "$now_date",
                ];
                $url = 'http://admin.leo1v1.com/stu_manage/init_info_by_contract_cr?orderid='.$orderid;
                $wx  = new \App\Helper\Wx();
                $result = $wx->send_template_msg($send_openid,$template_id,$data_msg,$url);
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


    public function get_teacher_textbook_str(){
        $textbook = $this->get_in_str_val('teacher_textbook');
        $arr_text = json_decode($textbook,true);
        foreach( $arr_text as $vall){
            @$str .=  E\Eregion_version::get_desc ($vall).",";
            @$num .= $vall.",";
        }
        @$str = trim($str,",");
        @$num = trim($num,",");
        return $this->output_succ(["textbook"=>@$str,"textbook_value"=>@$num]);


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
            "teacher_textbook"    => $teacher_textbook,
            "textbook_check_flag" => 1
        ]);

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

        return $this->output_succ();
    }


    public function reset_record_acc(){
        $id = $this->get_in_int_val("id");
        $this->t_teacher_record_list->field_update_list($id,["acc"=>""]);
        return $this->output_succ();

    }

    public function set_new_train_lesson(){
        $id = $this->get_in_int_val("id");
        $lessonid = $this->get_in_int_val("lessonid");
        /* $this->t_lesson_info->field_update_list($lessonid,[
            "lesson_del_flag"  =>1
        ]);
        $this->t_teacher_record_list->row_delete($id);*/
        $this->t_teacher_record_list->field_update_list($id,[
            "trial_train_status"  =>4,
            "add_time"            =>time(),
            "acc"                 =>$this->get_account()
        ]);
        $trial_train_num = $this->t_lesson_info->get_trial_train_num($lessonid);
        $teacherid = $this->t_lesson_info->get_teacherid($lessonid);
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);

        $this->add_trial_train_lesson($teacher_info,2,$trial_train_num);

        return $this->output_succ();

    }

    public function set_train_lesson_recover(){
        $id = $this->get_in_int_val("id");
        $this->t_teacher_record_list->field_update_list($id,[
            "trial_train_status" =>0
        ]);
        return $this->output_succ();
    }

    public function get_teacher_interview_info(){
        $phone = $this->get_in_str_val("phone");
        $teacherid = $this->get_in_int_val("teacherid");
        $info = $this->t_teacher_lecture_info->get_last_interview_by_phone($phone);
        $str = $this->t_teacher_record_list->get_last_interview_by_phone($teacherid);
        if(empty($info)){
            $data = $str;
        }elseif(empty($str)){
            $data = $info;
        }else{
            $data = $info.";".$str;
        }
        return $this->output_succ(["data"=>$data]);
    }

    public function get_teacher_no_free_list(){
        $subject = $this->get_in_int_val("subject",1);
        $grade = $this->get_in_int_val("grade",200);
        $ret = $this->get_not_free_time_list($subject,$grade);
        $arr=[];
        foreach($ret as $v){
            $t= $v["lesson_start"];
            $arr[$t]  = $t;
        }
        return $this->output_succ(["data"=>$arr]);
        //dd($arr);
    }

    public function get_train_lesson_comment(){
        $lessonid = $this->get_in_int_val("lessonid",281011);
        $lesson_type = $this->get_in_int_val("lesson_type");
        if($lesson_type==2){
            $require_id = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);
            $arr = $this->t_test_lesson_subject_require->field_get_list($require_id,"stu_lesson_content,stu_lesson_status,stu_study_status ,stu_advantages,stu_disadvantages ,stu_lesson_plan ,stu_teaching_direction,stu_textbook_info ,stu_teaching_aim ,stu_lesson_count ,stu_advice ");
        }else{
            $stu_comment = $this->t_lesson_info->get_stu_comment($lessonid);
            $arr= json_decode($stu_comment,true);
        }
        return $this->output_succ(["data"=>$arr]);
    }

    public function get_interview_assess_by_subject_grade(){
        $subject = $this->get_in_int_val("subject",1);
        $grade = $this->get_in_int_val("grade",200);
        $teacherid = $this->get_in_int_val("teacherid");
        $phone = $this->t_teacher_info->get_phone($teacherid);
        $info = $this->t_teacher_lecture_info->get_passed_interview_by_phone($phone,$subject,$grade);
        $str = $this->t_teacher_record_list->get_passed_interview_by_phone($teacherid,$subject,$grade);

        if(empty($info)){
            $data = $str;
        }elseif(empty($str)){
            $data = $info;
        }else{
            $data = $info.";".$str;
        }
        return $this->output_succ(["data"=>$data]);

    }

    public function set_ass_hand_kk_num(){
        $adminid = $this->get_in_int_val("adminid");
        $month   = $this->get_in_int_val("month");
        $kpi_type = $this->get_in_int_val("kpi_type");
        $hand_kk_num = $this->get_in_int_val("hand_kk_num");
        $hand_tran_num  = $this->get_in_int_val("hand_tran_num");
        $this->t_month_ass_student_info->get_field_update_arr($adminid,$month,$kpi_type,[
            "hand_kk_num"  =>$hand_kk_num,
            "hand_tran_num"=>$hand_tran_num
        ]);
        return $this->output_succ();

    }

    public function confirm_order(){
        $id  = $this->get_in_int_val('id');
        $sid = $this->get_in_int_val('sid');
        $orderid = $this->get_in_int_val('orderid');
        $confirm_flag = $this->get_in_int_val('confirm_flag');

        // $this->t_student_cc_to_cr->field_update_list($id,[
        //     "confirm_flag" => $confirm_flag,
        //     "reject_flag"  => 0
        // ]);

        return $this->output_succ();
    }


    public function get_reject_info(){
        $id = $this->get_in_int_val('id');
        $reject_info = $this->t_student_cc_to_cr->get_reject_info($id);

        return $this->output_succ(['data'=>$reject_info]);

    }


    public function check_account_role(){
        $account = $this->get_in_str_val('account');
        $is_flag = $this->t_manager_info->get_account_role_by_account($account);
        return $this->output_succ(['data'=>$is_flag]);
    }


    public function delMarketExtend(){
        $id = $this->get_in_int_val('id');
        $this->t_activity_usually->field_update_list($id,[
            "activity_status" => 2 // 0:未设置 1:活动进行中 2:已失效
        ]);
        return $this->output_succ();
    }

    public function editMarketExtend(){
        $id = $this->get_in_int_val('id');
        $ret_info = $this->t_activity_usually->getMarketExtendInfo($id);
        return $this->output_succ(['data'=>$ret_info]);
    }


    public function showMarketExtendImg(){
        $id = $this->get_in_int_val('id');
        $imgList = $this->t_activity_usually->getImgList($id);
        $domain = config('admin')['qiniu']['public']['url'];
        if($imgList['shareImgUrl']){ $imgList['shareImgUrl'] = $domain."/".$imgList['shareImgUrl'];}
        if($imgList['coverImgUrl']){ $imgList['coverImgUrl'] = $domain."/".$imgList['coverImgUrl'];}
        if($imgList['activityImgUrl']){ $imgList['activityImgUrl'] = $domain."/".$imgList['activityImgUrl'];}
        if($imgList['followImgUrl']){
            $follow_arr = explode(',',$imgList['followImgUrl']);
            $imgList['followImgUrl'] = '';
            foreach($follow_arr as $item){
                $imgList['followImgUrl'] .= $domain."/".$item.',';
            }
            $imgList['followImgUrl'] = trim($imgList['followImgUrl'],',');
        }
        return $this->output_succ(['data'=>$imgList]);
    }

    public function set_stu_test_paper_download(){
        $lessonid = $this->get_in_int_val("lessonid");
        $test_lesson_subject_id= $this->get_in_int_val("test_lesson_subject_id");

        $this->t_seller_student_info->change_download_time($lessonid);
        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "tea_download_paper_time" => time(NULL),
        ]);

        return $this->output_succ();
    }
}
