<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;

class Job
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "onQueue" and "delay" queue helper methods.
    |
    */

    use Queueable;

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
    }

    public function init_task()  {
        $this->task= new \App\Console\Tasks\TaskController();
    }

    static public function test()
    {

    }

}
