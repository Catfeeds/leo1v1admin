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

        $lesson_start = strtotime('+1 day',strtotime(date('Y-m-d')));
        $lesson_end = $lesson_start+86400;

        $job = new \App\Jobs\send_wx_tomorrow_tea($lesson_start, $lesson_end);
        dispatch($job);


        // if(!empty($tea_lesson_list)){
        //     foreach($tea_lesson_list as $item){

        //         $tea_lesson_info = $this->t_lesson_info_b3->get_tea_lesson_info($lesson_start, $lesson_end,$ite['teacherid']);

        //         $template_id_teacher   = "gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk";
        //         $data_teacher['first'] = "老师您好，您于明天 $lesson_begin_time 有一节模拟试听课! ";
        //         $data_teacher['keyword1']   = "$lesson_begin_time ";
        //         $data_teacher['keyword2']   = "处理人:$deal_account  处理方案:$deal_info";
        //         $data_teacher['remark']     = "";

        //         \App\Helper\Utils::send_teacher_msg_for_wx($item_teacher,$template_id_teacher, $data_teacher,$url_teacher);

        //     }
        // }
    }
}
