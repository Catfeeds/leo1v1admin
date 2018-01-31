<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        //计算排名前20cc信息
        $cc_result_info = $this->get_cc_result_info($begin_time,$end_time);
        $has_rank_count = $this->task->t_cc_day_top->get_has_rank_count($add_time);
        if($has_rank_count > 0){
            //判断是否有7天内的业绩

            //将排名前20cc信息存进t_cc_day_top
            foreach($cc_result_info as $item){
                //检查是否有信息[有就不添加]
                $this->task->t_cc_day_top->row_insert([
                    'uid' => $item['uid'],
                    'score' => $item['score'],
                    'rank' => $item['rank'],
                    'add_time' => $add_time
                ]);
            }
            echo '生成排名名单成功!';
        }else{
            echo '今日名单已经生成了!';
        }
    }
    //@desn:计算业绩排名前20的cc信息
    //@param:$begin_time,$end_time 开始时间 结束时间
    private function get_cc_result_info($begin_time,$end_time){
        $cc_result_arr = [];
        //获取所有cc信息
        $cc_info = $this->task->t_manager_info->get_seller_list();
        foreach($cc_info as &$item){
            //获取cc有效新例子个数[拨打成功]
            $item['effect_num'] = $this->task->t_seller_student_new_b2->get_effect_num($begin_time,$end_time,$item['uid']);
            //获取cc试听成功个数
            $item['test_lesson_succ_num'] = $this->task->t_test_lesson_subject_require->t_test_lesson_subject_require($begin_time,$end_time,$item['uid']);
            //获取cc签单个数
            $item['order_money'] = $this->task->t_order_info->get_order_money_by_adminid($begin_time,$end_time,$item['uid']);
        }
        dd($cc_info);
    }
}
