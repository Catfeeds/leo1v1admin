<?php
namespace App\Console\Commands;
use \App\Enums as E;
class lesson_check extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:lesson_check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $task=new \App\Console\Tasks\TaskController();
        $time = strtotime(date('Y-m-d',time(null)).date('H:i',time(null)).':00');
        $lesson_start = [$time+300,$time-60,$time-180,$time-300,$time-600,$time-1200,$time-2400];
        $lesson_info = $task->t_lesson_info_b2->get_check_lesson($lesson_start);
        if(count($lesson_info)>0){
            foreach($lesson_info as $key=>$l_item){
                $ret = [
                    'lessonid'       => $l_item['lessonid'],
                    'lesson_type'    => $l_item['lesson_type'],
                    'tea_attend'     => $l_item['tea_attend'],
                    'stu_attend'     => $l_item['stu_attend'],
                    'teacher_openid' => $l_item['teacher_openid'],
                    'assistantid'    => $l_item['assistantid'],
                    'cc_id'          => $l_item['cc_id'],
                ];
                if($l_item['lesson_start'] == $time+300){//课前5分钟
                    $ret['work_type'] = 0;
                    if(!isset($l_item['tea_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                }elseif($l_item['lesson_start'] == $time-60){//上课1分钟
                    $ret['work_type'] = 1;
                    if(!isset($l_item['stu_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                }elseif($l_item['lesson_start'] == $time-180){//上课3分钟
                    $ret['work_type'] = 2;
                }elseif($l_item['lesson_start'] == $time-300){//上课5分钟
                    $ret['work_type'] = 3;
                }elseif($l_item['lesson_start'] == $time-600){//上课10分钟
                    $ret['work_type'] = 4;
                    if(!isset($l_item['tea_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                    if(!isset($l_item['stu_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                }elseif($l_item['lesson_start'] == $time-1200){//上课20分钟
                    $ret['work_type'] = 5;
                }elseif($l_item['lesson_start'] == $time-2400){//上课40分钟
                    $ret['work_type'] = 6;
                    if(!isset($l_item['tea_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                    if(!isset($l_item['stu_attend'])){
                        $job=(new \App\Jobs\lesson_check($ret))->delay(60);
                        dispatch($job);
                    }
                }else{//学生中途退出超过5分钟
                    $ret['work_type'] = 7;
                }
            }
        }
    }

}