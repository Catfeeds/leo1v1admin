<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class cmd_base extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:xx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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

    //需要实现
    public function do_handle() {

    }

    public function handle(){
        \App\Helper\Utils::logger("CMD_START:". $this-> signature  );
        $this->do_handle();
        \App\Helper\Utils::logger("CMD_END:". $this-> signature  );
    }

    public function get_arg_day() {
        $day=$this->option('day');
        if ($day===null) {
            return 0;
        }else{
            if (preg_match( "/^\-?[0-9][0-9]*$/" , $day ) ) {
                return strtotime(date("Y-m-d" ,time()+86400*$day));
            }else{
                return strtotime($day);
            }
        }
    }

    public function get_in_value($field_name,$def_value=0){
        $value = $this->option($field_name);
        if($value===null){
            $value = $def_value;
        }
        return $value;
    }


}