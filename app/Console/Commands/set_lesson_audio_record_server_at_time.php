<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class set_lesson_audio_record_server_at_time extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set_lesson_audio_record_server_at_time';

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

        $task        = new \App\Console\Tasks\TaskController();
        $active_list = $task->t_audio_record_server->get_active_server_list();
        $active_priority_list=[];
        foreach ($active_list as $a_item ) {
            for ($i=0;$i<$a_item["priority"];$i++) {
                $active_priority_list[]=$a_item["ip"];
            }
        }
        if (count($active_list)==0) {
            dispatch(new \App\Jobs\send_error_mail(
                    "xcwenn@qq.com","没有活动的声音服务器","没有活动的声音服务器"
                ));
            return;
        }

        $active_priority_list_len=count($active_priority_list);
        $lesson_list= $task->t_lesson_info->get_current_lessons("");

        foreach ($lesson_list as &$item) {
            $lessonid=$item["lessonid"];
            $record_audio_server1=$item["record_audio_server1"];
            $record_audio_server2=$item["record_audio_server2"];
            $need_set_1=false;
            $need_set_2=false;

            if (!$record_audio_server1 || !isset($active_list[$record_audio_server1] )  ) {
                $need_set_1=true;

            }
            if (!$record_audio_server2 || !isset($active_list[$record_audio_server2] )  ) {
                $need_set_2=true;
            }
            if ($need_set_1) {
                $record_audio_server1=\App\Helper\Common::get_item_from_priority_list($active_priority_list,[$record_audio_server2=>true]);
                if ($record_audio_server1) {
                    $task->t_lesson_info->field_update_list($lessonid,[
                        "record_audio_server1" => $record_audio_server1,
                    ]);
                }
            }
            /*
            if ($need_set_2) {
                $record_audio_server2=\App\Helper\Common::get_item_from_priority_list($active_priority_list,[$record_audio_server1 => true]);
                if ($record_audio_server2) {
                    $task->t_lesson_info->field_update_list($lessonid,[
                        "record_audio_server2" => $record_audio_server2,
                    ]);
                }
            }
            */

        }

    }
}
