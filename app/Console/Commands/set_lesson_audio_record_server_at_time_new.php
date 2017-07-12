<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class set_lesson_audio_record_server_at_time_new extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set_lesson_audio_record_server_at_time_new';

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
        $this->task        = new \App\Console\Tasks\TaskController();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $active_list = $this->task->t_audio_record_server->get_active_server_list();
        if (count($active_list)==0) {
            dispatch(new \App\Jobs\send_error_mail(
                    "xcwenn@qq.com","没有活动的声音服务器","没有活动的声音服务器"
                ));
            return;
        }
        $all_max_record_count=0;
        foreach ($active_list as $a_item ) {
            $all_max_record_count+=$a_item["max_record_count"];
        }





        $lesson_list= $this->task->t_lesson_info->get_current_lessons("");

        //预期分布状况
        $lesson_list_count=count($lesson_list);
        foreach ($active_list as &$active_item ) {
            $active_item["need_count"] =$lesson_list_count* $active_item["max_record_count"]/$all_max_record_count+1;
            if ( $active_item["need_count"] >$active_item ["max_record_count"]  ) {
                $active_item["need_count"] = $active_item["max_record_count"];
            }
            $active_item["current_count"] =0;
        }

        //目前分布情况
        foreach ($lesson_list as &$item) {
            $lessonid=$item["lessonid"];
            $record_audio_server1=$item["record_audio_server1"];
            if (!$record_audio_server1 || !isset($active_list[$record_audio_server1] )  ) {
                $item["record_audio_server1"]="";
            }else{
                $active_list[$record_audio_server1]["current_count"]++;
            }
        }

        $this->reset_lesson_audio($lesson_list,$active_list);

    }

    public function reset_lesson_audio( $lesson_list, $active_list) {
        $no_enough_server_flag=false;

        shuffle($active_list);
        print_r(  $active_list);
        foreach ($lesson_list as &$item) {
            $lessonid=$item["lessonid"];
            $record_audio_server1=$item["record_audio_server1"];
            if  (!$record_audio_server1 ) {
                foreach ( $active_list as   &$active_item) {
                    if ($active_item["current_count"]<$active_item["need_count"]  ) {
                        $record_audio_server1=  $active_item["ip"] ;
                        $active_item["current_count"]++;
                        break;
                    }
                }
            }
            if ($record_audio_server1) {
                $this->task->t_lesson_info->field_update_list($lessonid,[
                    "record_audio_server1" => $record_audio_server1,
                ]);

            }else{
                $no_enough_server_flag=true;
            }

        }

        if ($no_enough_server_flag) {
            echo "no_enough_server_flag \n" ;
            \App\Helper\Utils::logger(" !no_enough_server_flag ");

            dispatch(new \App\Jobs\send_error_mail(
                "xcwenn@qq.com","声音服务器不足","声音服务器不足"));
        }

    }

}
