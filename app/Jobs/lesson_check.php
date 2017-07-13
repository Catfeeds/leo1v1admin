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
    var $lesson_type;
    var $tea_attend;
    var $stu_attend;
    var $teacher_openid;
    var $assistantid;
    var $cc_id;
    var $teacher_nick;
    var $student_nick;
    var $lesson_time;
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
        $this->teacher_nick   = $ret['teacher_nick'];
        $this->student_nick   = $ret['student_nick'];
        $this->lesson_time    = $ret['lesson_time'];
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
        $teacher_nick   = $this->teacher_nick;
        $student_nick   = $this->student_nick;
        $lesson_time    = $this->lesson_time;
        $time           = date('Y/m/d',time(null));
        if($work_type == 0){ //课前5分钟
            $this->send_wx_to_teacher($teacher_openid,$time,$teacher_nick,$student_nick,$lesson_time);
        }elseif($work_type == 1){//上课1分钟
            // $this->send_wx_to_assistant($lessonid,$assistantid,$cc_id);
        }elseif($work_type == 2){//上课3分钟

        }elseif($work_type == 3){//上课5分钟

        }elseif($work_type == 4){//上课10分钟
            if(!$tea_attend){
                // $this->send_wx_to_teacher($teacher_openid,$time,$teacher_nick,$student_nick,$lesson_time);
            }
            if(!$stu_attend){
                // $this->send_wx_to_assistant($lessonid,$assistantid,$cc_id);
            }
        }elseif($work_type == 5){//上课20分钟

        }elseif($work_type == 6){//上课40分钟
            if(!$tea_attend){
                // $this->send_wx_to_teacher($teacher_openid,$time,$teacher_nick,$student_nick,$lesson_time);
            }
            if(!$stu_attend){
                // $this->send_wx_to_assistant($lessonid,$assistantid,$cc_id);
                // $this->cancel_lesson($lessonid);
            }
        }elseif($work_type == 7){//中途退出5分钟未再次进入

        }
    }

    public function send_wx_to_teacher($openid,$time,$teacher_nick,$student_nick,$lesson_time){
        $template_id_teacher = 'rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o';
        $data = [
            'first'    => $teacher_nick.'老师您好，课程将于5分钟后开始，请尽快进入课堂',
            'keyword1' => '课程提醒',
            'keyword2' => '课程时间:'.$lesson_time.',学生姓名:'.$student_nick,
            'keyword3' => $time,
            'remark'   => '理优期待与你一起共同进步，提供高品质教学服务',
        ];
        $url = 'www.leo1v1.com';
        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id_teacher,$data,$url);
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
