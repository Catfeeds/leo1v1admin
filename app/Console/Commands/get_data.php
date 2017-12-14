<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_data {--s=} {--e=}';

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
        $month_start = strtotime($this->option('s'));
        $month_end   = strtotime($this->option('e'));

        $admin_list = $task->t_order_info->getOrderList($month_start, $month_end);

        foreach($admin_list as &$item){
            echo date("Y-m-d H:i",$item['order_time']).' '.date("Y-m-d H:i",$item['check_money_time']).' '.$item['sys_operator'].' '.date("Y-m-d H:i",$item['create_time']).' '.$item['price_money'].PHP_EOL;
        }
    }
}
