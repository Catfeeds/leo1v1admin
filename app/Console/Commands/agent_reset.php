<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class agent_reset extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:agent_reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function do_handle()
    {
        $list=$this->task->t_agent->get_agent_list();
        foreach ($list as $item ) {
            $id=$item["id"];
             echo "deal $id\n";
            $this->task->t_agent->reset_user_info($id);
        }
    }

}
