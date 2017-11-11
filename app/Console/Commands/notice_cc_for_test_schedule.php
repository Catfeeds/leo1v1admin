<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class notice_cc_for_test_schedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notice_cc_for_test_schedule ';

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
        /**
           为方便后期与CC做好在试听课前4h，老师匹配信息对称，增加推送通知内容如下：   您好，XX老师。 您于XX时间提交的试听课程【课程信息】，目前距开课时间还有4h。由于教务老师暂时尚未筛选到合适的授课老师进行匹配，特发此通知请您知悉。
         **/
        $task= new \App\Console\Tasks\TaskController();
        $now = time();

        $test_list = $task->t_test_lesson_subject_require->get_test_list($now);

    }
}
