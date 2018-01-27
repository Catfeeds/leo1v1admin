<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class sendForMarketTmp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendForMarketTmp';

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
        $task=new \App\Console\Tasks\TaskController();
        $a = $task->t_parent_info->get_stu();
        foreach($a as $i => $item){
            $checkNeedSend = $task->t_lesson_info_b3->checkNeedSend($item['userid']);
            if($checkNeedSend != 1){
                unset($a[$i]);
            }
        }
        echo count($a);
        // dd($a);

    }
}
