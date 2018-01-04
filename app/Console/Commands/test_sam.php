<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_sam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_sam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test_sam';

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
        //every week
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $ret_info = $task->t_order_refund->get_2017_11_refund_info();
        $file_name = '';
        $arr_title = [];
        $arr_data  = [];
        $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
        dd($ret_file_name);
    }
}
