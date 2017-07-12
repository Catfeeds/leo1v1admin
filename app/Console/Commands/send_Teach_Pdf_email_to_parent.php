<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_Teach_Pdf_email_to_parent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_Teach_Pdf_email_to_parent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将教师的PDF文件邮件给家长（扫描上课时间10分钟内的）';

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
        $lesson_start = time()-36000;

        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();

        $list=$task->t_lesson_info->get_current_student_list_by_start_time($lesson_start);

        foreach ( $list as &$item) {
            $userid   = $item["userid"];
            $parentid = $this->t_student_info->get_parent_total_list($userid);
            $eamil    = $this->t_parent_info->get_parent_email_list($parentid);
        }
    }
}
