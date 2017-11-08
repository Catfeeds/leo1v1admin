<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_lesson_plan_tomorrow extends Command
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


        $task = new \App\Console\Tasks\TaskController();

        // 前一天晚上8点上课推送
        /**
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

        $lesson_start = strtotime('+1 day',strtotime(date('Y-m-d')));
        $lesson_end = $lesson_start+86400;

        $tea_lesson_list = $task->t_lesson_info_b3->get_teacher_tomorrow_lesson_list($lesson_start, $lesson_end);


        if(!empty($tea_lesson_list)){
            foreach($tea_lesson_list as $item){

                $tea_lesson_info = $this->t_lesson_info_b3->get_tea_lesson_info($lesson_start, $lesson_end,$ite['teacherid']);
                //get_tea_lesson_info($lesson_start, $lesson_end,$teacherid)


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
