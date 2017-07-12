<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class xmpp_del_room extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:xmpp_del_room';

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
        $lessonid=146935;
        $lesson_info=$this->task->t_lesson_info->field_get_list($lessonid,"*");
        $courseid=$lesson_info["courseid"];
        $lesson_type=$lesson_info["lesson_type"];
        $lesson_num=$lesson_info["lesson_num"];
        $teacherid= $lesson_info["teacherid"];
        $userid= $lesson_info["userid"];
        $roomid = \App\Helper\Utils::gen_roomid_name($lesson_type,$courseid,$lesson_num);
        $ret_arr  = \App\Helper\Net::get_server_info(array($courseid));

        if (isset( $ret_arr["server_list"] ) &&  isset( $ret_arr["server_list"][0])) {
            $server_config = $ret_arr["server_list"][0];
            \App\Helper\Utils::del_room($teacherid,$roomid,$server_config);
            \App\Helper\Utils::del_room($userid,$roomid,$server_config);
            echo "succ\n";
        }else{
            echo " ERROR get_server_info\n";
        }
        //
    }
}
