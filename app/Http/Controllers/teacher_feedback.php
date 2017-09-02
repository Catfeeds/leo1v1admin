<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;

class teacher_feedback extends Controller
{
    use CacheNick;

    public function teacher_feedback_list_ass(){
        if (!$this->check_in_has("assistantid")) {
            $this->set_in_value("assistantid",$this->t_assistant_info->get_assistantid($this->get_account()) );
        }
        return $this->teacher_feedback_list();
    }

    public function teacher_feedback_list_jw(){
        if (!$this->check_in_has("accept_adminid")) {
            $this->set_in_value("accept_adminid",$this->get_account_id());
        }
        return $this->teacher_feedback_list();
    }

    public function teacher_feedback_list(){
        list($start_time,$end_time,$opt_date_type) = $this->get_in_date_range(-30,0,1,[
            1 => array("add_time","反馈添加时间"),
            2 => array("lesson_start", "课程时间"),
        ]);
        $teacherid      = $this->get_in_int_val("teacherid",-1);
        $assistantid    = $this->get_in_int_val("assistantid",-1);
        $accept_adminid = $this->get_in_int_val("accept_adminid",-1);
        $lessonid       = $this->get_in_int_val("lessonid");
        $status         = $this->get_in_int_val("status",0);
        $feedback_type  = $this->get_in_int_val("feedback_type",-1);
        $del_flag       = $this->get_in_int_val("del_flag",0);
        $page_num       = $this->get_in_page_num();
        $acc            = $this->get_account();

        $lesson_deduct_key  = E\Elesson_deduct::$v2s_map;
        $lesson_deduct_info = E\Elesson_deduct::$desc_map;

        $list = $this->t_teacher_feedback_list->get_teacher_feedback_list(
            $start_time,$end_time,$teacherid,$assistantid,$accept_adminid,$lessonid,$status,$feedback_type,$page_num,$opt_date_type,$del_flag
        );
        foreach($list['list'] as &$tea_val){
            E\Efeedback_type::set_item_value_str($tea_val);
            E\Echeck_status::set_item_value_str($tea_val,"status");
            E\Egrade::set_item_value_str($tea_val,"grade");
            E\Elevel::set_item_value_str($tea_val,"level");
            E\Eteacher_money_type::set_item_value_str($tea_val,"teacher_money_type");
            \App\Helper\Utils::unixtime2date_for_item($tea_val,"add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($tea_val,"check_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($tea_val,"lesson_start","_str","Y-m-d");
            $tea_val['month_start_str'] = \App\Helper\Utils::unixtime2date($tea_val['lesson_start'],"Y-m-01");
            $tea_val['lesson_time']     = date("Y-m-d H:i",$tea_val['lesson_start'])."-".date("H:i",$tea_val['lesson_end']);
            $tea_val['stu_nick']        = $this->cache_get_student_nick($tea_val['userid']);
            $tea_val['processing_time'] = (int)$tea_val['check_time']-(int)$tea_val['add_time'];
            $tea_val['processing_time_str']=\App\Helper\Common::secsToStr($tea_val['processing_time']);
            $tea_val['lesson_deduct']="";
            foreach($lesson_deduct_key as $deduct_key => $deduct_val){
                if($tea_val[$deduct_val]>0){
                    $tea_val['lesson_deduct'] .= $lesson_deduct_info[$deduct_key]."|";
                }
            }
        }

        return $this->pageView(__METHOD__,$list,[
            "acc"            => $acc,
            "assistantid"    => $assistantid,
            "accept_adminid" => $accept_adminid,
        ]);
    }

    public function get_teacher_feedback_lesson_info(){
        $lessonid      = $this->get_in_int_val("lessonid");
        $feedback_type = $this->get_in_int_val("feedback_type");
        $feedback_arr  = E\Efeedback_type::$desc_map;

        if($lessonid==0 || !isset($feedback_arr[$feedback_type])){
            return $this->output_err("课程id或者反馈类型出错!");
        }

        $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);

        $lesson_info['lesson_count'] /=100;
        $lesson_info['already_lesson_count'] /=100;
        if($lesson_info['lesson_type']==2){
            $lesson_info['lesson_type_str']="试听课";
        }else{
            $lesson_info['lesson_type_str']="1对1";
        }

        \App\Helper\Utils::unixtime2date_range($lesson_info);
        \App\Helper\Utils::unixtime2date_range($lesson_info,"real_lesson_time","real_begin_time","real_end_time");
        \App\Helper\Utils::unixtime2date_for_item($lesson_info,"tea_attend","_str");
        \App\Helper\Utils::unixtime2date_for_item($lesson_info,"stu_attend","_str");
        \App\Helper\Utils::unixtime2date_for_item($lesson_info,"tea_rate_time","_str");
        \App\Helper\Utils::unixtime2date_for_item($lesson_info,"tea_cw_upload_time","_str");
        \App\Helper\Utils::unixtime2date_for_item($lesson_info,"stu_cw_upload_time","_str");
        E\Eteacher_money_type::set_item_value_str($lesson_info);
        E\Elevel::set_item_value_str($lesson_info);

        return $this->output_succ(["data"=>$lesson_info]);
    }

    public function check_teacher_feedback(){
        $id            = $this->get_in_int_val("id");
        $lessonid      = $this->get_in_int_val("lessonid");
        $check_status  = $this->get_in_int_val("check_status");
        $check_time    = $this->get_in_int_val("check_time");
        $back_reason   = $this->get_in_str_val("back_reason");
        $feedback_type = $this->get_in_int_val("feedback_type");

        if($id==0){
            return $this->output_err("不存在该反馈记录!请刷新重试!");
        }
        $remark_str = "";
        if($check_status == 1){
            if(in_array($feedback_type,[201,202,203,204])){
                $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);
                $next_flag   = 0;
                $first_day   = strtotime(date("Y-m-01",time()));
                if($lesson_info["lesson_start"]<$first_day){
                    $check_day = strtotime(date("Y-m-02",time()));
                    $next_flag = $check_day<time()?1:0;
                }

                switch($feedback_type){
                case 201:
                    $lesson_arr['deduct_come_late']=0;
                    break;
                case 202:
                    $lesson_arr['deduct_rate_student']=0;
                    break;
                case 203:
                    $lesson_arr['deduct_upload_cw']=0;
                    break;
                case 204:
                    $lesson_arr['deduct_change_class']=0;
                    break;
                default:
                    $lesson_arr=[];
                    break;
                }

                if(!empty($lesson_arr) && $next_flag!=1){
                    $this->t_lesson_info->field_update_list($lessonid,$lesson_arr);
                }elseif($next_flag==1){
                    $remark_str .= "处理方式：本节课扣款会补偿到下次工资发放里。";
                    $lesson_cost_reward = \App\Helper\Config::get_config_2("teacher_money","lesson_cost");
                    $ret = $this->t_teacher_money_list->row_insert([
                        "teacherid"  => $lesson_info['teacherid'],
                        "type"       => 4,
                        "add_time"   => time(),
                        "money"      => $lesson_cost_reward,
                        "money_info" => "上月课程在5日后反馈处理补偿,lessonid".$lessonid,
                        "acc"        => $this->get_account(),
                    ]);
                }
            }
        }elseif($back_reason==""){
            return $this->output_err("反馈原因不能为空!");
        }

        $ret = $this->t_teacher_feedback_list->field_update_list(["id"=>$id],[
            "status"       => $check_status,
            "back_reason"  => $back_reason,
            "check_time"   => time(),
            "sys_operator" => $this->get_account(),
        ]);

        if($ret){
            if($check_time==0){
                $teacherid = $this->t_lesson_info->get_teacherid($lessonid);
                $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid);
                if($wx_openid){
                    /**
                     * 模板ID : kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc
                     * 标题   : 反馈进度通知
                     * {{first.DATA}}
                     * 反馈内容：{{keyword1.DATA}}
                     * 处理结果：{{keyword2.DATA}}
                     * {{remark.DATA}}
                     */
                    $template_id      = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";//old
                    $data["first"]    = "老师您好，您所提交的反馈已处理。";
                    $data["keyword1"] = E\Efeedback_type::get_desc($feedback_type);
                    $data["keyword2"] = E\Echeck_status::get_desc($check_status);

                    if($back_reason){
                        $remark_str .= "处理原因：".$back_reason."\n如有疑问，请联系教务老师。";
                    }else{
                        $remark_str .= "如有疑问，请联系教务老师。";
                    }
                    $data["remark"] = $remark_str;
                    \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data);
                }
            }

            return $this->output_succ();
        }else{
            return $this->output_err("审核失败,请刷新重试!");
        }
    }

    public function delete_teacher_feedback_info(){
        $id       = $this->get_in_int_val("id");
        $status   = $this->get_in_int_val("status");
        $del_flag = $this->get_in_int_val("del_flag");
        $acc      = $this->get_account();

        if($status != 0){
            return $this->output_err("状态不是未处理状态，无法操作！");
        }

        if(in_array($acc,["adrian","alan","jim"])){
            $ret = $this->t_teacher_feedback_list->field_update_list(["id"=>$id],[
                "del_flag"    => $del_flag,
                "check_time " => time(),
            ]);
        }else{
            return $this->output_err("没有权限!");
        }

        return $this->output_succ();
    }

    public function update_teacher_feedback_type(){
        $id            = $this->get_in_int_val("id");
        $feedback_type = $this->get_in_int_val("feedback_type");

        $ret = $this->t_teacher_feedback_list->field_update_list(["id"=>$id],[
            "feedback_type" => $feedback_type
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("更改失败!请重试!");
        }
    }



}