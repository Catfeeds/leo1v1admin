<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Common;
use \App\Enums as E;


class send_wx_msg_for_trial_train_lesson_next_day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $task = new \App\Console\Tasks\TaskController();

        // 前一天晚上8点上课推送
        /***
            gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk
            {{first.DATA}}
            上课时间：{{keyword1.DATA}}
            课程类型：{{keyword2.DATA}}
            教师姓名：{{keyword3.DATA}}
            {{remark.DATA}}

            课前提醒
            x月x日

            老师您好，您于明天xx:xx有一节模拟试听课。
            上课时间：xx-xx xx:xx~xx:xx
            课程类型：模拟试听
            老师姓名：x老师
            请保持网络畅通，提前做好上课准备。

         **/

        $trial_test_lesson_lists = $task->t_teacher_record_list->get_lesson_list_for_next_day();

        if(empty($trial_test_lesson_lists)){
            foreach($trial_test_lesson_lists as $item){
                $lesson_begin_time = date("H:i:s",$item['lesson_start']);
                $lesson_end_time   = date("H:i:s",$item['lesson_end']);

                $template_id_teacher   = "gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk";
                $data_teacher['first'] = "老师您好，您于明天 $lesson_begin_time 有一节模拟试听课! ";
                $data_teacher['keyword1']   = "$lesson_begin_time ";
                $data_teacher['keyword2']   = "处理人:$deal_account  处理方案:$deal_info";
                $data_teacher['remark']     = "";

                \App\Helper\Utils::send_teacher_msg_for_wx($item_teacher,$template_id_teacher, $data_teacher,$url_teacher);

            }
        }



    }
}
