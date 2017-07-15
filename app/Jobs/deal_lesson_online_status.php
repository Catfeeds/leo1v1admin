<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class deal_lesson_online_status extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    public $lessonid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lessonid)
    {
        //
        $this->lessonid = $lessonid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $t_lesson_info = new  \App\Models\t_lesson_info_b2();

        $lessonid  = $this->lessonid;
        $ret_video_arr = $t_lesson_info->get_lesson_url($lessonid);

        $ret_video = $ret_video_arr['0'];
        if(!empty($ret_video['draw'])){
            $item['draw_url']  =  \App\Helper\Utils::gen_download_url($ret_video['draw']);
            $savePathFile = public_path('wximg').'/'.$ret_video['draw'];

            \App\Helper\Utils::savePicToServer($item['draw_url'],$savePathFile);

            $xml = file_get_contents($savePathFile);

            $xmlstring = simplexml_load_string($xml);

            $svgLists = json_decode(json_encode($xmlstring),true);

            $stroke_time = 0;

            if (!empty($svgLists['svg'])) {
                foreach($svgLists['svg'] as $svg){
                    if (array_key_exists('path',$svg)) {
                        $stroke_time = $svg['@attributes']['timestamp'];
                    }
                }

                if ($ret_video['real_begin_time']<($stroke_time-30*60)) {
                    $t_lesson_info->field_update_list($lessonid,[
                        "lesson_user_online_status" =>  1
                    ]);
                }
                unlink($savePathFile);
            }
        }
    }
}
