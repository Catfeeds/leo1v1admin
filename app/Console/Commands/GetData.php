<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetData extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拉数据';

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
        $ret_list = $this->task->t_student_info->get_();
    }
}
