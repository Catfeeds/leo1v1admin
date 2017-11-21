<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_identity_for_teacher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_identity_for_teacher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新老师表(t_teacher_info)中的老师身份';

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
        $task = new \App\Console\Tasks\TaskController();
        // 获取老师身份
        $identity = $task->t_teacher_info->get_identity_for_teacher_type();
        $i = 0;
        foreach($identity as $teacherid => $item) {
            if ($i % 1000 == 0) sleep(10);
            $task->t_teacher_info->field_update_list($teacherid,[
                'identity' => $item['teacher_type']
            ]);
            $i ++;
        }
    }
}
