<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateTeacherLectureAudio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateTeacherLectureAudio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新老师试讲课音频文件';

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
        $task = new \App\Console\Tasks\TaskController();

        $url  = "http://7u2f5q.com1.z0.glb.clouddn.com/";
        $flag = "";
        $list = $task->t_teacher_lecture_info->get_audio_build_list();

        if(is_array($list) && !empty($list)){
            $flag = @file_get_contents($list[0]['audio'], null, null, -1, 1) ? true : false;
            if($flag!=1){
                $task->t_teacher_lecture_info->field_update_list($list[0]["id"],[
                    "audio_build"=>"文件不存在!"
                ]);
            }else{
                if(!empty($list) && is_array($list) && $url!=''){
                    foreach($list as $val){
                        $audio_build = "";
                        $file_name   = "";

                        $file = "/tmp/".substr($val['audio'],-17);
                        $cmd_ffmpeg  = "ffmpeg -i ".$val['audio']." -f mp3 ".$file;
                        \App\Helper\Utils::exec_cmd($cmd_ffmpeg);

                        $file_name = \App\Helper\Utils::qiniu_upload($file);

                        if($file_name!=''){
                            $audio_build = $url.$file_name;
                            $task->t_teacher_lecture_info->field_update_list(["id"=>$val['id']],[
                                "audio_build" => $audio_build
                            ]);

                            $cmd_rm = "rm ".$file;
                            \App\Helper\Utils::exec_cmd($cmd_rm);
                        }
                    }
                }
            }
        }
    }
}
