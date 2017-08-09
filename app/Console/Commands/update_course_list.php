<?php
namespace App\Console\Commands;
use \App\Enums as E;
class update_course_list extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_course_list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新课程表剩余课时为0的状态为结课';




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ret = $this->task->t_course_order->get_course_list();
        foreach ($ret as $key => $value) {
            $ret[$key]["left_lesson_count"] = ($ret[$key]["assigned_lesson_count"]-$ret[$key]["finish_lesson_count"])/100;
        }
        foreach($ret as $key => $value){
            if($ret[$key]["left_lesson_count"] <= 0){
                $ret_info = $this->task->t_course_order->update_course_status($ret[$key]['courseid']);
            }
        }
    }
}