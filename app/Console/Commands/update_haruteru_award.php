<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_haruteru_award extends Command
{
    protected $award = [300,200,150,100,60];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_haruteru_award';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新春辉奖奖金';

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
        $task = new \App\Console\Tasks\TaskController();
        $start_time = strtotime(date('Y-m-1', strtotime('-1month')));
        
        $end_time = strtotime(date('Y-m-1', time()));

        $check = $task->t_teacher_money_list->get_haruteru_award($start_time, $end_time);
        if ($check) { // 本月有数据直接退出
            exit('本月数据已经刷过');
        }
        // 小学
        $p_info =$task->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,100,[],2,false);
        $this->get_person($p_info, 100, $task);
        // 初中
        $m_info =$task->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,200,[],2,false);
        $this->get_person($m_info, 200, $task);
        // 高中
        $s_info =$task->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,300,[],2,false);
        $this->get_person($s_info, 300, $task);
    }

    // 处理结果获取春辉奖得奖人
    public function get_person($info, $grade, $task) {
        $person = [];
        $sort = [];
        $sort_o = [];
        if ($info) { // 获取转化率
            foreach($info as $key => $item) {
                $convers = round($item['have_order'] / $item['lesson_num'] * 100);
                if ($item['lesson_num'] >= 6 && $convers >= 15) {
                    $sort[] = $convers;
                    $sort_o[] = $item['have_order'];
                    $item['convers'] = $convers;
                    $item['teacherid'] = $key;
                    $person[] = $item;
                }
            }
        }
        if ($person) {
            array_multisort($sort,SORT_DESC,$sort_o,SORT_DESC,$person ); // 排序
            $person = array_slice($person,0,5); // 获取前五名
            // 添加数据
            foreach($person as $k => $item) {
                $comput = array_slice($person,0,$k+1);
                $money = $this->award[$k];
                foreach($comput as $key=>$val) { // 处理排名中是澡并列
                    if ($item['convers'] == $val['convers']) {
                        $money = $this->award[$key];
                        break;
                    }
                }
                echo 'teacherid '.$item['teacherid'].'money : '.$money.' end ';
                //$time = strtotime(date('Y-m-7', strtotime('-1month')));

                // $task->t_teacher_money_list->row_insert([
                //     'teacherid' => $item['teacherid'],
                //     'add_time' => $time,
                //     'type' => 7,
                //     'money' => $money * 100,
                //     'grade' => $grade
                // ]);
            }
        }
        return;
    }
}
