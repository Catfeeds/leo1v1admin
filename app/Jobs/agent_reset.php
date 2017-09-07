<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class agent_reset extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $agent_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($agent_reset )
    {
        $agent_id=$agent_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $agent_id=$this->agent_id;
    }
}
