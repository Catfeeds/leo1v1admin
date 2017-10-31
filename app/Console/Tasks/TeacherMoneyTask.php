<?php
namespace App\Console\Tasks;
use \App\Enums as E;
use Illuminate\Support\Facades\Log;
use App\Helper\Net;
use App\Helper\Utils;
// use App\Http\Controllers\Controller;

/**
 * @from command:SetTeacherMoney
 */
class TeacherMoneyTask extends TaskController
{

    /**
     * 更新老师园丁奖奖金
     * 园丁奖奖励金额,每个周期课时消耗前5(第5名并列可取多)
     */
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
                $num_lesson=$num_people;
                if($lesson_total != $val['lesson_total']){
                    if($num_people>5){
                        break;
                    }
                    if($num_lesson>5){
                        $num_lesson=5;
                    }
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
     * 设置老师的签单奖
     * @param type 2 兼职老师的签单奖 3 全职老师的签单奖
     * @param day  老师签单奖更新的时间周期('day'天以内,如果为0则使用默认值)
     */
    public function set_teacher_trial_success_reward($type,$day){
        $begin_time = $this->get_begin_time($type,$day);

        $this->t_test_lesson_subject_sub_list->switch_tongji_database();
        $list = $this->t_test_lesson_subject_sub_list->get_teacher_trial_success_list($begin_time,$type);

        \App\Helper\Utils::logger("set_trial_reward :".json_encode($list)." time ".time());
        $lessonid = "";
        foreach($list as $val){
            $stu_nick = $this->cache_get_student_nick($val['userid']);
            $tea_nick = $this->cache_get_student_nick($val['teacherid']);
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
                "lessonid"   => $val['lessonid'],
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
                    "tea_nick" => $tea_nick,
                    "stu_nick" => $stu_nick,
                    "price"    => ($money/100),
                ];
                \App\Helper\Utils::sms_common($val['phone'],"51410003",$data);
            }
        }
    }

    /**
     * 获取签单奖检测的开始时间
     */
    public function get_begin_time($type,$day){
        if($day>0){
            $begin_time = strtotime("-$day day",time());
        }else{
            $begin_time = 0;
        }

        if($type==2){
            $check_time = strtotime("2016-12-1");
        }elseif($type==3){
            $check_time = strtotime("2017-4-1");
        }

        if($begin_time<$check_time){
            $begin_time = $check_time;
        }
        return $begin_time;
    }

    /**
     * 设置老师工资信息
     */
    public function set_teacher_salary_list($type,$start_time=0){
        $start_time = $start_time==0?time():$start_time;
        $month_time = \App\Helper\Utils::get_month_range($start_time,true);
        $teacher_money = new \App\Http\Controllers\teacher_money();
        echo "start";
        echo PHP_EOL;
        $teacher_money->set_in_value($type,"admin");
        $ret = $teacher_money->set_teacher_salary();
        var_dump($ret);
        echo "end";
        echo PHP_EOL;
        // $tea_list   = $this->t_teacher_info->get_need_set_teacher_salary_list($month_time['sdate'],$month_time['edate']);

    }



}