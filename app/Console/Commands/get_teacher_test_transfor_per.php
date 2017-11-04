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
        $start_time = time()-30*86400;
        $ret= $task->t_teacher_info->tongji_teacher_stu_num_new($start_time,$end_time);
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


        //每月1号至5号,更新沉睡老师信息
        $d = date("d",time());
        if($d>=1 && $d <=5){
            $end_time = strtotime(date("Y-m-01",time()));
            $start_time = strtotime("-3 month",$end_time);
            $all_throuth_teacher = $task->t_teacher_info->get_all_train_through_teacher_list($start_time);
            $all_train_through_lesson_teacher= $task->t_teacher_info->get_all_train_through_lesson_teacher_list($start_time,$end_time);
            $no_lesson_tea_list=[];
            $lesson_tea_list=[];
            foreach($all_throuth_teacher as $k=>$v){
                if(!isset($all_train_through_lesson_teacher[$k]) && $v["sleep_flag"]==0){
                    $no_lesson_tea_list[$k]=$v["teacherid"];
                }elseif(isset($all_train_through_lesson_teacher[$k]) && $v["sleep_flag"]==1){
                    $lesson_tea_list[$k]=$v["teacherid"];
                }
            }

            foreach($no_lesson_tea_list as $k=>$v){
                $task->t_teacher_info->field_update_list($k,[
                   "sleep_flag" =>1 
                ]);
            }
            foreach($lesson_tea_list as $k=>$v){
                $task->t_teacher_info->field_update_list($k,[
                    "sleep_flag" =>0
                ]);
            }

        }

        

        //dd($teacher_test_per_list);

        
    }
}
