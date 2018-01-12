<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetTeacherFeedback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ResetTeacherFeedback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置老师反馈的状态';

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
        $date = date("Y-m-01",time());
        $end_time = strtotime ($date);
        $feedback_list = $this->t_teacher_feedback_list->get_delay_feedback_list(0,$end_time);

    }
}
