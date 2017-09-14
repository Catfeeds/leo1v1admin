<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class sync_email_account extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync_email_account';

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
        $zmcmd= " zmprov -l gaa  leoedu.com  ";
        $cmd="sshpass -p\"yb142857\" ssh -2  -o \"StrictHostKeyChecking no\" -p22 -l\"zimbra\" 115.28.89.73   \"   $zmcmd \"";
        $ret_str=\App\Helper\Utils::exec_cmd($cmd);
        $email_list=preg_split("/\n/", $ret_str);
        $this->task->t_manager_info->reset_all_email_create_flag( $email_list );

        //
    }
}
