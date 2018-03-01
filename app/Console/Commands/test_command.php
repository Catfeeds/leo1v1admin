<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_command extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_command {--year=}{--month=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试命令';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->task->switch_tongji_database();
        $year     = $this->get_in_value("year",2017);
        $month    = $this->get_in_value("month",1);

        $date_str = $year."-".$month;
        $start    = strtotime($date_str);
        $end      = strtotime("+1 month",$start);

        $lesson_list = $this->task->t_lesson_info->get_lesson_list_for_wages(-1,$start,$end,-1);
        $check_num = [];
        $money_list = [];
        $tea_lesson_count = [];
        if(!empty($lesson_list)){
            foreach($lesson_list as $key => &$val){
                $teacherid    = $val['teacherid'];
                $lesson_count = $val['confirm_flag']!=2?($val['lesson_count']/100):0;
                $lesson_type  = $val['lesson_type'];
                $grade        = \App\Helper\Utils::change_grade_to_grade_part($val['grade']);
                if($lesson_type==E\Econtract_type::V_2){
                    continue;
                }

                if(!isset($tea_lesson_count[$teacherid])){
                    $last_lesson_count = $this->task->get_last_lesson_count_info($start,$end,$teacherid);
                    $tea_lesson_count[$teacherid] = $last_lesson_count;
                }else{
                    $last_lesson_count = $tea_lesson_count[$teacherid];
                }

                $val['money']       = $this->task->get_teacher_base_money($teacherid,$val);
                $val['lesson_base'] = $val['money']*$lesson_count;
                $reward = $this->task->get_lesson_reward_money(
                    $last_lesson_count,$val['already_lesson_count'],$val['teacher_money_type'],$val['teacher_type'],$val['type']
                );
                $val['lesson_reward'] = $reward*$lesson_count;

                if(!isset($check_num[$teacherid])){
                    $check_num[$teacherid]=[];
                }
                $this->task->get_lesson_cost_info($val,$check_num[$teacherid]);
                //老师收入,课时成本
                $teacher_money = ($val['lesson_base']+$val['lesson_reward']-$val['lesson_cost']);
                /**
                 * 课时收入：当月内，产生课时消耗得到的收入，以实际收入为准；
                 * 付费课时数：当月内实际消耗的课时数，以实际扣除学生的课时数为准；
                 */
                if(in_array($lesson_type,[0,3]) && $val['confirm_flag'] !=2 && $val['confirm_flag']!=4){
                    $lesson_price = $val['lesson_price'];
                    $lesson_pay_count = $lesson_count;
                }else{
                    $lesson_price     = 0;
                    $lesson_pay_count = 0;
                }
                //赠送课时数
                if($lesson_type==E\Econtract_type::V_1){
                    $lesson_free_count = $lesson_count;
                }else{
                    $lesson_free_count = 0;
                }
                \App\Helper\Utils::check_isset_data($money_list[$grade]['lesson_price'], $lesson_price);
                \App\Helper\Utils::check_isset_data($money_list[$grade]['teacher_money'], $teacher_money);
                \App\Helper\Utils::check_isset_data($money_list[$grade]['lesson_pay_count'], $lesson_pay_count);
                \App\Helper\Utils::check_isset_data($money_list[$grade]['lesson_free_count'], $lesson_free_count);
            }
        }

        if(!empty($money_list)){
            foreach($money_list as $m_key=>$m_val){
                echo $m_key."|".$m_val['lesson_price']."|".$m_val['lesson_pay_count']."|".$m_val['lesson_free_count']
                           ."|".$m_val['teacher_money'];
            }
        }else{
            echo "this is empty";
        }
        echo PHP_EOL;
    }



}
