<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class proto_gen  extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $project;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($project)
    {
        parent::__construct();
        $this->project=$project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $cmd= app_path("../proto/gen_".$this->project );
        \App\Helper\Utils::logger("exec:%s");
        \App\Helper\Utils::exec_cmd($cmd);
    }
}
