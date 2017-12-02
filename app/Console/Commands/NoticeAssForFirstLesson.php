<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Helper\WxSendMsg;
use \App\Enums as E;

class NoticeAssForFirstLesson extends Command
{
    use  CacheNick;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NoticeAssForFirstLesson';

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
        $start_time = time();
        $end_time   = $start_time+60;

        $lesson_list = $this->task->t_lesson_info_b3->get_common_list($start_time,$end_time);

        foreach($lesson_list as $i=>$item){
            $not_first = $this->task->t_lesson_info_b3->check_not_first_lesson($item['userid'],$item['teacherid'],$item['subject'],$item['lesson_start']);

            if($not_first == 1){
                unset($lesson_list[$i]);
            }
        }

        //测试

        $lesson_list = [
            "lesson_count" => 150,
            "lesson_start" => 1511971200,
            "lesson_end"   => 1511978400,
            "teacherid"    => 225427,
            "subject"      => 3
        ];


        foreach($lesson_list as &$item){
            $lesson_count_str = $item['lesson_count']/100;
            $subject_str      = E\Esubject::get_desc($item['subject']);
            $tea_nick         = $this->cache_get_teacher_nick($item['teacherid']);

            /**
               {{first.DATA}}
               待办主题：{{keyword1.DATA}}
               待办内容：{{keyword2.DATA}}
               日期：{{keyword3.DATA}}
               {{remark.DATA}}
             **/

            $data = [
                "first"    => '首次课后回访未设置',
                "keyword1" => '首次课后回访',
                "keyword2" => "课时信息:".$lesson_count_str."课时,上课时间:".date("Y-m-d H:i",$item['lesson_start'])." ~ ".date('H:is',$item['lesson_end']).", 科目:$subject_str , 老师:$tea_nick",
                "keyword3" => date('Y-m-d H:i:s'),
            ];
            $url = "";
            WxSendMsg::send_ass_for_first("orwGAs_IqKFcTuZcU1xwuEtV3Kek", $data, $url); //james
        }


    }
}
