<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTeacherLectureAudio extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $audio_list;
    var $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($audio_list=[],$url='')
    {
        //
        $this->audio_list = $audio_list;
        $this->url        = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $t_teacher_lecture_info = new \App\Models\t_teacher_lecture_info();

        if(!empty($this->audio_list) && is_array($this->audio_list) && $this->url!=''){
            foreach($this->audio_list as $val){
                $audio_build = "";
                $file_name   = "";

                $file = "/tmp/".substr($val['audio'],-17);
                $cmd_ffmpeg  = "ffmpeg -i ".$val['audio']." -f mp3 ".$file;
                \App\Helper\Utils::exec_cmd($cmd_ffmpeg);

                $file_name = \App\Helper\Utils::qiniu_upload($file);

                if($file_name!=''){
                    $audio_build = $this->url.$file_name;
                    $t_teacher_lecture_info->field_update_list(["id"=>$val['id']],[
                        "audio_build" => $audio_build
                    ]);

                    $cmd_rm = "rm ".$file;
                    \App\Helper\Utils::exec_cmd($cmd_rm);
                }

            }
        }
    }



}