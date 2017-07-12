<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSetLessonFullNum extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $start_time;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start_time=0)
    {
        $this->start_time = $start_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $t_lesson_info = new \App\Models\t_lesson_info();

        if($this->start_time!=0){
            $lesson_list = $t_lesson_info->get_lesson_list_for_lesson_full_num($this->start_time);
            $list = [];
            $has_finish=[];
            foreach($lesson_list as $val){
                if(!isset($list[$val['teacherid']]['lesson_full_num'])){
                    $list[$val['teacherid']]['lesson_full_num']=0;
                }
                
                if($val['lesson_type']==2){
                    if(($val['success_flag']<2 || $val['test_lesson_fail_flag']<100) && $val['lesson_start']<1485878400){
                        $list[$val['teacherid']]['lesson_full_num']++;
                    }elseif(($val["success_flag"]<2 || $val['test_lesson_fail_flag']<100) && $val['lesson_start']>1485878400
                            && $val['deduct_come_late']==0 ){
                        $list[$val['teacherid']]['lesson_full_num']++;
                    }elseif($val['fail_greater_4_hour_flag']==1
                            && ($val['test_lesson_fail_flag']==101 || $val['test_lesson_fail_flag']==102)
                    ){
                        $list[$val['teacherid']]['lesson_full_num'] = 0;
                    }elseif($val['lesson_start']>1485878400 && $val['deduct_come_late']>0){
                        $list[$val['teacherid']]['lesson_full_num'] = 0;
                    }
                }else{
                    if($val['confirm_flag']!=2 && $val['deduct_change_class']==0 && $val['deduct_come_late']==0){
                        $list[$val['teacherid']]['lesson_full_num']++;
                    }elseif($val['deduct_change_class']>0){
                        $list[$val['teacherid']]['lesson_full_num']=0;
                    }elseif($val['lesson_start']>1485878400 && $val['deduct_come_late']>0 && $val['confirm_flag']!=2){
                        $list[$val['teacherid']]['lesson_full_num']=0;
                    }
                }

                $lesson_full_num = $list[$val['teacherid']]['lesson_full_num'];
                $t_lesson_info->field_update_list($val['lessonid'],[
                    "lesson_full_num" => $lesson_full_num
                ]);
            }
        }
    }

}
