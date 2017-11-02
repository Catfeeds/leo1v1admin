<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_agent_group_member_result extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_agent_group_member_result';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取优学优享团成员每天统计';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //更新团员业绩
        $list=$this->task->t_agent_group_members->get_agent_group_members_list();
        foreach ($list as $item ) {
            $id=$item["agent_id"];
            echo "deal $id\n";
            $this->task->t_agent->reset_group_member_result($id);
        }
        
    }
}
