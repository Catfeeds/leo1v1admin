<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;
class seller_reset_no_call_to_new_user extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:seller_reset_no_call_to_new_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * @var  \App\Console\Tasks\TaskController
     */
    public $task;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->task= new \App\Console\Tasks\TaskController();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $ret_info=$this->task->t_seller_student_new->get_no_call_to_free_list(1,1000000,-1,-1 , -1 );
        $opt_type=0;
        foreach ($ret_info["list"] as $item ) {
            $userid=$item["userid"];
            $phone = $item["phone"];
            $this->task->t_seller_student_new->set_admin_info(
                $opt_type, $userid_list, 0 , 0 );

            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: 定时任务  状态: 分配给组员 [ 0 ], 未拨通超时 ",
                "system"
            );
        }
        //
    }
}
