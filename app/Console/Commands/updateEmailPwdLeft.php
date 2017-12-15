<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class updateEmailPwdLeft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updateEmailPwdLeft';

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
        // $emailList = $this->t_manager_info->getEmailLeft();

        $emailList[]=[
            "email" => 'james@leoedu.com'
        ];

        //james@leoedu.com
        foreach($emailList as $item){
            // $pwd = mt_rand(0,1000000)."_bydelete";
            $pwd = "123456_bydelete";
            $email = $item['email'];

            $zmcmd= "zmprov sp $email $pwd &>/dev/null ;";
            $cmd="sshpass -p\"yb142857\" ssh -2  -o \"StrictHostKeyChecking no\" -p22 -l\"zimbra\" 115.28.89.73   \"   $zmcmd \"";
            \App\Helper\Utils::exec_cmd($cmd);
        }


        // $zmcmd= "zmprov sp $email 111111 &>/dev/null ;";
        // $cmd="sshpass -p\"yb142857\" ssh -2  -o \"StrictHostKeyChecking no\" -p22 -l\"zimbra\" 115.28.89.73   \"   $zmcmd \"";
        // \App\Helper\Utils::exec_cmd($cmd);
        // return $this->output_succ();



    }
}
