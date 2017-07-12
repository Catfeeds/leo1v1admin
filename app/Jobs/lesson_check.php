<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\Enums as E;

class lesson_check extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $lessonid;
    var $work_type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ret)
    {
        $this->lessonid       = $ret['lessonid'];
        $this->lesson_type    = $ret['lesson_type'];
        $this->tea_attend     = $ret['tea_attend'];
        $this->stu_attend     = $ret['stu_attend'];
        $this->teacher_openid = $ret['teacher_openid'];
        $this->assistantid    = $ret['assistantid'];
        $this->cc_id          = $ret['cc_id'];
        $this->work_type      = $ret['work_type'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $task=new \App\Console\Tasks\TaskController();
        $lessonid       = $this->lessonid;
        $work_type      = $this->work_type;
        $lesson_type    = $this->lesson_type;
        $tea_attend     = $this->tea_attend;
        $stu_attedn     = $this->stu_attend;
        $teacher_openid = $this->teacher_openid;
        $assistantid    = $this->assistantid;
        $cc_id          = $this->cc_id;
        if($work_type == 0){
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_openid,$template_id_teacher, $data,$url);
            // $task->t_manager_info->send_wx_todo_msg($teacher_account,'主题','头信息','内容');
        }elseif($work_type == 1){
            $this->send_wx_to_assistant($lessonid,$assistantid,$cc_id);
        }elseif($work_type == 2){
            
        }elseif($work_type == 3){
            
        }elseif($work_type == 4){
            if(!isset($tea_attend)){
                $task->t_manager_info->send_wx_todo_msg($teacher_account,'','');
            }
            if(!isset($stu_attend)){
                $this->send_wx_to_assistant($lessonid,$assistantid,$cc_id);
            }
        }elseif($work_type == 5){
            
        }elseif($work_type == 6){
            if(!isset($tea_attend)){
                $task->t_manager_info->send_wx_todo_msg($teacher_account,'','');
            }
            if(!isset($stu_attend)){
                $this->send_wx_to_assistant($lessonid,$assistantid,$cc_id);
                $this->cancel_lesson($lessonid);
            }
        }elseif($work_type == 7){
            
        }
    }

    public function send_wx_to_assistant($lessonid,$assistantid,$cc_id) {
        $task=new \App\Console\Tasks\TaskController();
        if($lesson_type == 2){
            $adminid = $cc_id;
        }else{
            $adminid = $task->t_assistant_info->get_adminid_by_assistand($assistantid);
        }

        $task->t_manager_info->send_wx_todo_msg_by_adminid($adminid,'','');
    }
    public function cancel_lesson($lessonid) {
        $task=new \App\Console\Tasks\TaskController();
        $task->t_lesson_info->field_update_list($lessonid,[
            "lesson_status" => E\Elesson_status::V_END,
            "confirm_flag"    => 3,
            "confirm_time"    => time(NULL),
        ]);
    }

}
