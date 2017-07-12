<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\Controller;



class deal_ssh_lock_user extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $account;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account)
    {
        $this->account = $account ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $account= $this->account;
        \App\Helper\Utils::exec_cmd("sshpass -p\"yb142857\" ssh -2  -o \"StrictHostKeyChecking no\" -p56000 -lybai 114.215.66.38  sudo usermod -L    $account ");
    }

}
