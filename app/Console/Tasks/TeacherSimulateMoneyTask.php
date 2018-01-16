<?php
namespace App\Console\Tasks;
use \App\Enums as E;
use Illuminate\Support\Facades\Log;
use App\Helper\Net;
use App\Helper\Utils;

use App\Http\Controllers\Controller;

/**
 * @from command:SetTeacherSimulateMoney
 */
class TeacherSimulateMoneyTask extends TaskController
{
    /**
     * 模拟更新老师每月工资
     * @param int type
     * @param int timestamp 需重置的老师工资的时段
     */
    public function set_teacher_simulate_salary_list($timestamp=0){
        if($timestamp==0){
            $timestamp = time();
        }

        $teacher_money = new \App\Http\Controllers\teacher_simulate();
        $month_range   = \App\Helper\Utils::get_month_range($timestamp,true);
        $start_time    = $month_range['sdate'];
        $end_time      = $month_range['edate'];

        $tea_list = $this->t_teacher_info->get_need_set_teacher_salary_list($start_time,$end_time);
        foreach($tea_list as $t_val){
            $salary_info  = $teacher_money->get_teacher_simulate_salary($t_val['teacherid'],$start_time,$end_time);
            $lesson_money = ($salary_info['lesson_price_tax']+$salary_info['lesson_reward_admin'])*100;

            $is_full = \App\Helper\Utils::check_teacher_is_full(
                $t_val['teacher_money_type'],$t_val['teacher_type'],$t_val['teacherid']
            );
            if($is_full){
                $pay_time = strtotime("+1 month",$start_time);
            }else{
                $pay_time = $start_time;
            }

            if($lesson_money<0){
                $is_negative  = 1;
                $lesson_money = abs($lesson_money);
            }else{
                $is_negative = 0;
            }

            $check_flag = $this->t_teacher_simulate_salary_list->check_simulate_money_is_exists($t_val['teacherid'],$start_time);
            if(!$check_flag){
                $this->t_teacher_simulate_salary_list->row_insert([
                    "teacherid"          => $t_val['teacherid'],
                    "teacher_type"       => $t_val['teacher_type'],
                    "teacher_money_type" => $t_val['teacher_money_type'],
                    "pay_time"           => $pay_time,
                    "money"              => $lesson_money,
                    "is_negative"        => $is_negative,
                    "add_time"           => $start_time,
                ]);
            }else{
                $this->t_teacher_simulate_salary_list->update_teacher_simulate_money(
                    $t_val['teacherid'],$start_time,$lesson_money,$is_negative,$pay_time
                );
            }
        }
    }


}