<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums  as E;
class check_system extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_system ';

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
    public function do_handle()
    {

        $now=time();
        $d1=date("Y-m-d",$now);
        $d2=date("Y-m-d",$now-86400);
        \App\Helper\Utils::exec_cmd(" crontab -l >    ~/bin/crontab/$d1 ");
        $ret=\App\Helper\Utils::exec_cmd("diff ~/bin/crontab/$d1 ~/bin/crontab/$d2 2>&1  ");
        $title =" crontab 不变 ";
        if ($ret) {
            $title= "crontab 修改";
        }
        $ret=htmlentities($ret);
        echo $ret;

        $ret=preg_replace("/\n/is","<br/>",$ret);
        dispatch( new \App\Jobs\send_error_mail(
            "", date("H:i:s")."$title:"."$ret".
            "<br>", \App\Enums\Ereport_error_from_type::V_1
        ));
    }
}
