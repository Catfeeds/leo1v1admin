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

        // $list = $task->t_teacher_lecture_info->get_audio_build_list();
        // if(is_array($list) && !empty($list)){
        //     $flag = $this->check_file_is_exists($list[0]['audio']);
        //     if($flag!=true){
        //         $task->t_teacher_lecture_info->field_update_list($list[0]["id"],[
        //             "audio_build"=>"文件不存在!"
        //         ]);
        //     }else{
        //         if(!empty($list) && is_array($list) && $url!=''){
        //             foreach($list as $val){
        //                 $audio_build = "";
        //                 $file_name   = "";
        //                 $file = "/tmp/".substr($val['audio'],-17);
        //                 $cmd_ffmpeg  = "ffmpeg -i ".$val['audio']." -f mp3 ".$file;
        //                 \App\Helper\Utils::exec_cmd($cmd_ffmpeg);
        //                 $flag = $this->check_file_is_exists($file);
        //                 if($flag!=true){
        //                     $task->t_teacher_lecture_info->field_update_list($list[0]['id'],[
        //                         "audio_build" => "此视频已损坏!"
        //                     ]);
        //                 }else{
        //                     $file_name = \App\Helper\Utils::qiniu_upload($file);
        //                     if($file_name!=''){
        //                         $audio_build = $url.$file_name;
        //                         $task->t_teacher_lecture_info->field_update_list(["id"=>$val['id']],[
        //                             "audio_build" => $audio_build
        //                         ]);
        //                         $cmd_rm = "rm ".$file;
        //                         \App\Helper\Utils::exec_cmd($cmd_rm);
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        //2018年02月27日11:48:06 更新
        $ret_info = $task->t_teacher_lecture_info->get_audio_build_info();
        if(is_array($ret_info) && !empty($ret_info)){
            $audio = $ret_info['audio'];
            $id    = $ret_info['id'];
            $audio = $this->https_to_http($audio);
            if($audio!==false){
                $check_flag = $this->check_file_is_exists($audio);
            }else{
                $check_flag = false;
            }

            if($check_flag!=true){
                $task->t_teacher_lecture_info->field_update_list($id,[
                    "audio_build"=>"文件不存在!"
                ]);
            }else{
                $audio_build = "";
                $file_name   = "";

                $file       = "/tmp/".substr($audio,-17);
                $cmd_ffmpeg = "ffmpeg -i ".$audio." -f mp3 ".$file;
                \App\Helper\Utils::exec_cmd($cmd_ffmpeg);
                $flag = $this->check_file_is_exists($file);
                if($flag != true){
                    $task->t_teacher_lecture_info->field_update_list($id,[
                        "audio_build" => "此视频已损坏!"
                    ]);
                }else{
                    $file_name = \App\Helper\Utils::qiniu_upload($file);
                    if($file_name != ''){
                        $audio_build = $url.$file_name;
                        $task->t_teacher_lecture_info->field_update_list(["id"=>$id],[
                            "audio_build" => $audio_build
                        ]);

                        $cmd_rm = "rm ".$file;
                        \App\Helper\Utils::exec_cmd($cmd_rm);
                    }
                }
            }
        }
    }

    public function check_file_is_exists($file){
        $flag = @file_get_contents($file, null, null, -1, 1) ? true : false;
        return $flag;
    }

    public function https_to_http($file){
        $length = strlen($file);
        $file_ret = false;
        if($length>=5){
            $file_prefix = substr($file,0,5);
            if($file_prefix=="https"){
                $file_ret = "http".substr($file,5);
            }
        }
        return $file_ret;
    }
}
