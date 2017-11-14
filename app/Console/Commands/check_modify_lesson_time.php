<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class check_modify_lesson_time extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_modify_lesson_time {--day=} {--always_reset=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    public $task;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->task= new \App\Console\Tasks\TaskController();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 处理家长提交调课申请后 超过一小时就发送微信推送给助教 以便跟进处理
        $now = time(NULL);
        $lesson_list = $this->task->t_lesson_time_modify->get_need_notice_lessonid($now);

        // dd(json_encode($lesson_list));

        foreach($lesson_list as $item){
            // 向助教发微信推送
            $lesson_type = $this->task->t_lesson_info_b2->get_lesson_type($item['lessonid']);
            $lesson_type_arr = [0,1,3];
            if(in_array($lesson_type,$lesson_type_arr)){
                $ass_wx_openid = $this->task->t_lesson_info_b2->get_ass_wx_openid($item['lessonid']);
            }elseif($lesson_type == 2){
                $ass_wx_openid = $this->task->t_test_lesson_subject_require->get_cur_require_adminid_by_lessonid($item['lessonid']);
            }


            $lesson_start_time = $this->task->t_lesson_info_b2->get_lesson_start($item['lessonid']);
            $lesson_start_date = date('m月d日 H:i:s',$lesson_start_time );
            $stu_nick          = $this->task->t_student_info->get_stu_nick_by_lessonid($item['lessonid']);
            $teacher_nick      = $this->task->t_teacher_info->get_teacher_nick_lessonid($item['lessonid']);
            $day_date          = date('Y-m-d H:i:s');
            $parent_modify_remark = $this->task->t_lesson_time_modify->get_parent_modify_remark($item['lessonid']);
            $result = "原因:$parent_modify_remark";
            if(!$parent_modify_remark){
                $result = '';
            }

            $ass_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';
            $data_ass = [
                'first'    => "调课申请已发起一小时,还未被处理",
                'keyword1' =>" 您的学生{ $stu_nick }的家长申请修改{ $lesson_start_date }上课时间,未被处理",
                'keyword2' => "原上课时间:{ $lesson_start_date };$result",
                'keyword3' => "$day_date",
                'remark'   => "请尽快联系{ $teacher_nick }老师!"
            ];
            $url_ass = '';//待定
            $wx = new \App\Helper\Wx();
            $ret_send = $wx->send_template_msg($ass_wx_openid, $ass_template_id, $data_ass, $url_ass);

            $this->task->t_lesson_time_modify->field_update_list($item['lessonid'],[
                'is_notice_ass_flag' => 1 // 已发送通知给助教
            ]);
        }
    }
}
