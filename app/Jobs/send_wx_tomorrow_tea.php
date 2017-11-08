<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\Enums as E;

class send_wx_tomorrow_tea extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $lesson_start;
    var $lesson_end;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lesson_start, $lesson_end)
    {
        //
        $this->lesson_start = $lesson_start;
        $this->lesson_end = $lesson_end;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        /**
           {{first.DATA}}
           上课时间：{{keyword1.DATA}}
           课程类型：{{keyword2.DATA}}
           教师姓名：{{keyword3.DATA}}
           {{remark.DATA}}
        ***/
        $t_lesson_info_b3  = new \App\Models\t_lesson_info_b3();
        $tea_lesson_list = $t_lesson_info_b3->get_teacher_tomorrow_lesson_list($this->lesson_start, $this->lesson_end);
        foreach($tea_lesson_list as $item){
            $tea_lesson_info = $t_lesson_info_b3->get_tea_lesson_info($this->lesson_start, $this->lesson_end,$item['teacherid']);
            $keyword1 = '';
            foreach($tea_lesson_info as $i=> $v){
                $keyword1 .=$i."、".E\Esubject::get_desc($v['subject'])." - ".$v['nick']."-".date('Y-m-d',$v['lesson_start'])."~".date('Y-m-d',$v['lesson_end']);
            }
            $keyword2 = "常规课";


        }

    }
}
