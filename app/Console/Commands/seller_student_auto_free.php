<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class seller_student_auto_free extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "command:seller_student_auto_free";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "系统自动回流";


    /**
     * Execute the console command.
     * @return mixed
     */
    public function do_handle(){
        $this->task->t_seller_student_new->get_auto_free_list();
    }

}
