<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class get_teacher_test_transfor_per extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_teacher_test_transfor_per';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新老师试听转化率';

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
        //
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $start_time = time()-60*86400;
        $end_time = time();
        $teacher_test_per_list = $task->t_teacher_info->get_teacher_test_per_list($start_time,$end_time);
        foreach($teacher_test_per_list as &$item){
            $per = !empty($item["success_lesson"])?round($item["order_number"]/$item["success_lesson"],2)*100:0;

            $task->t_teacher_info->field_update_list($item["teacherid"],["test_transfor_per"=>$per]);
            
        }

        //更新一月常规学生数
        $ret= $task->t_teacher_info->get_teacher_list(1,$start_time,$end_time);
        foreach($ret as $v){
            $task->t_teacher_info->field_update_list($v["teacherid"],[
                "month_stu_num"   =>$v["stu_num"] 
            ]);

        }


        //更新两周试听课数
        $start_time = time()-14*86400;
        $list = $task->t_lesson_info_b2->get_test_lesson_num($start_time,$end_time);
        foreach($list as $val){
            $task->t_teacher_info->field_update_list($val["teacherid"],[
               "two_week_test_lesson_num"   =>$val["num"] 
            ]);
        }


        

        //dd($teacher_test_per_list);

        
    }
}
