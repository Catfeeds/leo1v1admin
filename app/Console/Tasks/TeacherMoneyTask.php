<?php
namespace App\Console\Tasks;
use \App\Enums as E;
use Illuminate\Support\Facades\Log;

use App\Helper\Net;
use App\Helper\Utils;
use CacheNick;
/**
 * 老师奖励金额类型
 * type=1 荣誉榜奖励金额,每个周期课时消耗前5(第5名并列可取多)
 * type=2 第三版工资类型的老师的试听签单奖
 * type=3 公司全职老师试听签单奖
 */
class TeacherMoneyTask extends TaskController
{

    public function set_teacher_lesson_total_list(){
        $end   = time();
        $start = strtotime("-1 week",$end);

        $lesson_list  = $this->t_lesson_info->get_teacher_lesson_total_list($start,$end);
        $num_people   = 0;
        $num_lesson   = 0;
        $lesson_total = 0;
        if(is_array($lesson_list)){
            foreach($lesson_list as $val){
                $num_people++;
                if($lesson_total != $val['lesson_total']){
                    if($num_people>5){
                        break;
                    }
                    $num_lesson++;
                    $lesson_total = $val['lesson_total'];
                    $money = E\Ehonor_list::get_desc($num_lesson);
                }

                $this->t_teacher_money_list->row_insert([
                    "teacherid"  => $val['teacherid'],
                    "type"       => 1,
                    "add_time"   => $end,
                    "money"      => $money,
                    "money_info" => $val["lesson_total"]
                ]);
            }
        }
    }

    /**
     * @param type 2 兼职老师的签单奖 3 全职老师的签单奖

     */
    public function set_teacher_trial_success_reward($type){
        if($type==2){
            $begin_date = "2016-12-1";
        }elseif($type==3){
            $begin_date = "2017-4-1";
        }
        $begin_time = strtotime($begin_date);

        $this->t_test_lesson_subject_sub_list->switch_tongji_database();
        $list = $this->t_test_lesson_subject_sub_list->get_teacher_trial_success_list($begin_time,$type);

        \App\Helper\Utils::logger("set_trial_reward :".json_encode($list)." time ".time());
        $lessonid = "";
        foreach($list as $val){
            if($type==3){
                if($val['require_admin_type']==E\Eaccount_role::V_2){
                    $money = 16000;
                }else{
                    $money = 10000;
                }
            }elseif($type==2){
                $money = 6000;
            }

            $ret = $this->t_teacher_money_list->row_insert([
                "teacherid"  => $val['teacherid'],
                "type"       => 2,
                "add_time"   => time(),
                "money"      => $money,
                "money_info" => $val['lessonid'],
            ]);

            if($ret){
                /**
                 * 模板类型 : 短信通知
                 * 模板名称 : 老师签单奖励通知
                 * 模板ID   : SMS_51410003
                 * 模板内容 : ${tea_nick}老师您好，你的学生${stu_nick}试听课签单成功，你将获得${price}元的奖励。
                   高转化率老师可获得晋升等级的机会，请继续加油提供高品质教学服务。
                */
                $data = [
                    "tea_nick" => $this->cache_get_teacher_nick($val['teacherid']),
                    "stu_nick" => $this->cache_get_student_nick($val['userid']),
                    "price"    => ($money/100),
                ];
                \App\Helper\Utils::sms_common($val['phone'],"51410003",$data);
            }
        }
    }

    public function get_trial_reward_money($type,$val){
        $money = 0;
        if($type==2){
            $money=6000;
        }elseif($type==3){
            if($val['require_admin_type']==2){
                $money=16000;
            }else{
                $money=10000;
            }
        }
        return $money;
    }

}