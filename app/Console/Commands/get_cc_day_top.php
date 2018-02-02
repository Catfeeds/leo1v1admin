<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class get_cc_day_top extends cmd_base 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_cc_day_top';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取cc业绩前20%名单';

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
     * @desn:获取cc7天内的业绩前20%名单
     */
    public function handle()
    {
        $end_time = strtotime(date('Y-m-d'));
        $begin_time = strtotime('- 7 day',$end_time);
        $add_time = strtotime('- 1 day',$end_time);
        //计算排名前20%cc信息
        $cc_result_info = $this->get_cc_result_info($begin_time,$end_time);
        $has_rank_count = $this->task->t_cc_day_top->get_has_rank_count($add_time);
        if($has_rank_count > 0){

            echo '今日名单已经生成了!';
        }else{
            $rank = 1;
            //将排名前20cc信息存进t_cc_day_top
            foreach($cc_result_info as $item){
                //判断是否有7天内的业绩
                if($item['result'] > 0){
                    $this->task->t_cc_day_top->row_insert([
                        'uid' => $item['uid'],
                        'score' => $item['result'],
                        'rank' => $rank++,
                        'add_time' => $add_time
                    ]);
                }
            }
            echo '生成排名名单成功!';
        }
    }
    //@desn:计算业绩排名前20的cc信息
    //@param:$begin_time,$end_time 开始时间 结束时间
    //@return arr
    private function get_cc_result_info($begin_time,$end_time){
        $cc_result_arr = [];
        //获取所有cc信息
        $cc_info = $this->task->t_manager_info->get_seller_list(E\Eseller_student_assign_type::V_SYSTEM_ASSIGN);
        //应该获取奖励的名额
        $reward_num = ceil(count($cc_info,0)*0.2);
        foreach($cc_info as &$item){
            //获取cc有效新例子个数[拨打成功]
            $item['effect_num'] = $this->task->t_seller_student_new_b2->get_effect_num($begin_time,$end_time,$item['uid']);
            //获取cc试听成功个数
            $item['test_lesson_succ_num'] = $this->task->t_test_lesson_subject_require->t_test_lesson_subject_require($begin_time,$end_time,$item['uid']);
            //获取cc签单个数
            $order_info = $this->task->t_order_info->get_order_money_by_adminid($begin_time,$end_time,$item['account']);
            $item['order_money'] = $order_info['order_money'];
            $item['order_num'] = $order_info['order_num'];
            //该cc业绩得分
            $item['results_score'] = number_format($item['order_money']/100000,2);
            //该cc合同率[签约数/试听成功数]
            if($item['test_lesson_succ_num'] > 0)
                $item['order_rate'] = number_format($item['order_num']/$item['test_lesson_succ_num']*100,2);
            else
                $item['order_rate'] = 0;
            //该cc试听率[试听成功数/有效新例子数]
            if($item['effect_num'] > 0)
                $item['test_lesson_rate'] = number_format($item['test_lesson_succ_num']/$item['effect_num']*100,2);
            else
                $item['test_lesson_rate'] = 0;
            //该cc总业绩
            $item['result'] = (0.5*$item['results_score']+0.4*$item['order_rate']+0.1*$item['test_lesson_rate'])*100;
        }
        //对数组中的业绩排序
        array_multisort(array_column($cc_info,'result'),SORT_DESC,$cc_info);
        echo "排序好的销售业绩信息\n";
        print_r($cc_info);
        $top_cc_info = array_slice($cc_info, 0,$reward_num);
        echo "取前20%\n";
        print_r($top_cc_info);
        return $top_cc_info;
    }
}
