<?php

namespace App\Jobs;

use App\Jobs\Job;
use \App\Enums as E;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetTeacherMonthMoney extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $level_simulate_count_key = "level_simulate_count";
    var $all_money_count_key      = "all_money_count";
    var $has_month_key            = "has_month";
    var $teacher_ref_rate_key     = "teacher_ref_rate";
    var $month_money_key          = "month_money";
    var $lesson_total_key         = "lesson_total";
    var $start_time = 0;
    var $end_time   = 0;
    var $already_lesson_count_key = "already_lesson_count_month";
    var $money_month_key          = "money_month";

    /**
     * Create a new job instance.
     *
     * @param start_time 
     * @param end_time
     * @return void
     */
    public function __construct($start_time,$end_time)
    {
        parent::__construct();
        $this->start_time = $start_time;
        $this->end_time   = $end_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tea_list = $this->task->t_teacher_info->get_teacher_simulate_list(
            $this->start_time,$this->end_time
        );


        /**
         * 每个老师上个月的累积课时
         */
        $already_lesson_count_list = \App\Helper\Utils::redis(E\Eredis_type,$this->already_lesson_count_key,[],true);
        /**
         * 每个月的各种详细数据
         * key   month_key
         * value money,lesson_price,
         *       money_simulate,lesson_price_simulate,lesson_total
         * child_key   teacher_money_type && level
         * child_value money,lesson_price,
         *             money_simulate,lesson_price_simulate,lesson_total
         */
        $month_list = \App\Helper\Utils::redis(E\Eredis_type,$this->money_month_key,[],true);
        foreach($tea_list as $val){
            $teacher_ref_type_rate = 0;
            $teacherid          = $val['teacherid'];
            $teacher_money_type = $val['teacher_money_type'];
            $level              = $val['level'];
            $lesson_count       = $val['lesson_count']/100;
            $month_key          = date("Y-m",$val['lesson_start']);

            if(!isset($already_lesson_count_list[$month][$teacherid])){
                $last_end_time   = strtotime(date("Y-m-01",$val['lesson_start']));
                $last_start_time = strtotime("-1 month",$last_end_time);
                $already_lesson_count_simulate = $this->get_already_lesson_count(
                    $start_time,$end_time,$teacherid,$teacher_money_type
                );
                $already_lesson_count_list[$key] = $already_lesson_count_simulate;
            }else{
                $already_lesson_count_simulate = $already_lesson_count_list[$month][$teacherid];
            }

            $check_type = \App\Helper\Utils::check_teacher_money_type($teacher_money_type,$val['teacher_type']);
            if($check_type==2){
                $already_lesson_count = $already_lesson_count_simulate;
            }else{
                $already_lesson_count = $val['already_lesson_count'];
            }

            $reward           = \App\Helper\Utils::get_teacher_lesson_money_simulate($val['type'],$already_lesson_count);
            $reward_simulate  = \App\Helper\Utils::get_teacher_lesson_money_simulate($val['type_simulate'],$already_lesson_count_simulate);
            $reward          *= $lesson_count;
            $reward_simulate *= $lesson_count;
            $money            = $val['money']*$lesson_count+$reward;
            $money_simulate   = $val['money_simulate']*$lesson_count+$reward_simulate;

            if($teacher_money_type==5){
                $teacher_ref_rate = $this->get_teacher_ref_rate($val['lesson_start'],$val['teacher_ref_type']);
                if($teacher_ref_rate>0){
                    $teacher_ref_money = $money*$teacher_ref_rate;
                    $money+=$teacher_ref_money;
                }
            }

            $lesson_price = $val['lesson_price']/100;
            if(in_array($val['contract_type'],[0,3])){
                $lesson_price_simulate = $this->get_lesson_price_simulate($val);
            }else{
                $lesson_price_simulate = 0;
            }

            \App\Helper\Utils::check_isset_data($month_list[$month_key]["money"],$money);
            \App\Helper\Utils::check_isset_data($month_list[$month_key]["money_simulate"],$money_simulate);
            \App\Helper\Utils::check_isset_data($month_list[$month_key]["lesson_price"],$lesson_price);
            \App\Helper\Utils::check_isset_data($month_list[$month_key]["lesson_price_simulate"],$lesson_price_simulate);
            \App\Helper\Utils::check_isset_data($month_list[$month_key]["lesson_total"],$lesson_total);

            \App\Helper\Utils::check_isset_data(
                $month_list[$month_key][$teacher_money_type][$level]['money'],$money);
            \App\Helper\Utils::check_isset_data(
                $month_list[$month_key][$teacher_money_type][$level]['money_simulate'],$money_simulate);
            \App\Helper\Utils::check_isset_data(
                $month_list[$month_key][$teacher_money_type][$level]['lesson_price'],$lesson_price);
            \App\Helper\Utils::check_isset_data(
                $month_list[$month_key][$teacher_money_type][$level]['lesson_price_simulate'],$lesson_price_simulate);
            \App\Helper\Utils::check_isset_data(
                $month_list[$month_key][$teacher_money_type][$level]['lesson_total'],$lesson_total);
        }

        $all_money = [];
        if(is_array($month_list) && empty($month_list)){
            foreach($month_list as $m_val){
                \App\Helper\Utils::check_isset_data($all_money['money'],$m_val['money']);
                \App\Helper\Utils::check_isset_data($all_money['money_simulate'],$m_val['money_simulate']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_price'],$m_val['lesson_price']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_price_simulate'],$m_val['lesson_price_simulate']);
                \App\Helper\Utils::check_isset_data($all_money['lesson_total'],$m_val['lesson_price_simulate']);
            }
        }



    }

    public function get_already_lesson_count($start_time,$end_time,$teacherid,$teacher_money_type){
        $last_start_time = strtotime("-1 month",$start_time);
        $last_end_time   = strtotime("-1 month",$end_time);
        $already_lesson_count = $this->task->t_lesson_info->get_teacher_last_month_lesson_count(
            $teacherid,$last_start_time,$last_end_time,$teacher_money_type
        );
        return $already_lesson_count;
    }

    public function get_teacher_ref_rate($time,$teacher_ref_type){
        $start_date = date("Y-m-01",$time);
        $start_time = strtotime($start_date);

        $teacher_ref_rate_list = \App\Helper\Utils::redis(E\Eredis_type::V_GET,$this->teacher_ref_rate_key,[],true);
        if($teacher_ref_rate_list===null || !isset($teacher_ref_rate_list[$teacher_ref_type][$start_date])){
            $teacher_ref_num  = $this->t_teacher_info->get_teacher_ref_num($start_time,$teacher_ref_type);
            $teacher_ref_rate = \App\Helper\Utils::get_teacher_ref_rate($teacher_ref_num);
            $teacher_ref_rate_list[$teacher_ref_type][$start_date] = $teacher_ref_rate;
            \App\Helper\Utils::redis(E\Eredis_type::V_SET,$this->teacher_ref_rate_key,$teacher_ref_rate_list);
        }else{
            $teacher_ref_rate = $teacher_ref_rate_list[$teacher_ref_type][$start_date];
        }

        return $teacher_ref_rate;
    }

    public function get_lesson_price_simulate($info){
        $lesson_total  = $info['lesson_total']*$info['default_lesson_count']/100;
        if($lesson_total>0){
            $has_promotion = 1;
            if($info['price'] < $info['discount_price']){
                $has_promotion = 2;
            }
            switch($info['grade']){
            case 100:
                $info['grade']=101;break;
            case 200:
                $info['grade']=201;break;
            case 300:
                $info['grade']=301;break;
            default:
                $info['grade']=$info['grade'];break;
            }
            $args=[
                "from_test_lesson_id"=>0
            ];
            $price_arr_simulate = \App\OrderPrice\order_price_base::get_price_ex_cur(
                $info['competition_flag'],$has_promotion,$info['contract_type'],$info['grade'],$lesson_total,0,$args
            );
            $per_price_simulate = $price_arr_simulate['discount_price']/$lesson_total;
            $lesson_price_simulate=$info['lesson_count']*$per_price_simulate/100;
        }else{
            $lesson_price_simulate=0;
        }
        return round($lesson_price_simulate,2);
    }
}
