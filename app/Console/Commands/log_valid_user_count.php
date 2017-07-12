<?php 
namespace App\Console\Commands;

use \App\Enums as E;
use Illuminate\Console\Command;

class log_valid_user_count extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:log_valid_user_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        parent::__construct();
        $this->task= new \App\Console\Tasks\TaskController();
    }



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $count=$this->task->t_student_info-> get_valid_user_count();
        $logtime=strtotime( (date("Y-m-d")) );
        $log_type=E\Edate_id_log_type::V_VALID_USER_COUNT;

        $this->task->t_id_opt_log->add_ex($log_type,$logtime,0,$count);

    }
}
