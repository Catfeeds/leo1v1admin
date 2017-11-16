<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reset_lesson_online_user_status_by_stroke_time extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';
    protected $signature = 'Command:reset_lesson_online_user_status_by_stroke_time {--day=} {--always_reset=}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $task;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->task = new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $day=$this->option('day');

        if ( $day == null ){
            $day=date("Y-m-d");
            // $day=date("Y-m-d",strtotime("-1 day"));
        }

        $start_time  = strtotime( $day); 
        $end_time    = $start_time+86400;

        // 获取系统判断无效的资源

        $invalid_list = $this->task->t_lesson_info_b2->get_lesson_user_online_status_invalid($start_time, $end_time);

        foreach($invalid_list as &$item){

            $lessonid  = $item['lessonid'];
            $userid= $item["userid"];
            $ret_video_arr = $this->task->t_lesson_info_b2->get_lesson_url($lessonid);

            $ret_video = $ret_video_arr['0'];
            if(!empty($ret_video['draw'])){
                $item['draw_url']  =  \App\Helper\Utils::gen_download_url($ret_video['draw']);
                $savePathFile = public_path('wximg').'/'.$ret_video['draw'];

                \App\Helper\Utils::savePicToServer($item['draw_url'],$savePathFile);

                $xml = file_get_contents($savePathFile);

                $xmlstring = @simplexml_load_string($xml);

                $svgLists = json_decode(json_encode($xmlstring),true);

                $stroke_time = 0;

                if (!empty($svgLists['svg'])) {
                    foreach($svgLists['svg'] as $svg){
                        if(is_array($svg) && array_key_exists('path',$svg)) {
                            $stroke_time = $svg['@attributes']['timestamp'];
                        }
                    }

                    if ($ret_video['real_begin_time']<($stroke_time-30*60)) {
                        $this->task->t_lesson_info->field_update_list($lessonid,[
                            "lesson_user_online_status" =>  1
                        ]);

                        //优学优享
                        $agent_id= $this->task->t_agent->get_agentid_by_userid($userid);
                        if ($agent_id) {
                            dispatch( new \App\Jobs\agent_reset($agent_id) );
                        }

                    }
                }
            }

            if(isset($savePathFile) && file_exists($savePathFile)){
                $unlink_re = @unlink($savePathFile);
            }

        }
    }
}
